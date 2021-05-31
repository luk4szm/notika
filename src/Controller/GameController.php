<?php

namespace App\Controller;

use App\Entity\Game;
use App\Service\GameService;
use App\Service\RankingService;
use App\Service\ScheduleService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    private $em;
    private $gameService;
    private $scheduleService;
    private $rankingService;

    public function __construct(
        GameService $gameService,
        ScheduleService $scheduleService,
        RankingService $rankingService,
        EntityManagerInterface $em
    )
    {
        $this->gameService     = $gameService;
        $this->scheduleService = $scheduleService;
        $this->rankingService  = $rankingService;
        $this->em              = $em;
    }

    /**
     * @Route("/game/{id}", name="game_detail")
     */
    public function index(Game $game): Response
    {
        $roundSchedule = $this->em->getRepository(Game::class)->findRoundGames(
            $game->getSeason(),
            $game->getRound()
        );

        return $this->render('game/game.html.twig', [
            'game'             => $game,
            'roundSchedule'    => $roundSchedule,
            'betsDistribution' => $this->gameService->getPercentageBetDistribution($game),
            'userBet'          => $this->gameService->getUserBet($game),
        ]);
    }

    /**
     * @Route("/game/{id}/save-bet", name="game_save_bet", methods={"POST"})
     * @IsGranted("ROLE_USER")
     */
    public function saveBet(Request $request, Game $game): JsonResponse
    {
        if (new \DateTime() > $game->getDate()) {
            return $this->json(['errorMsg' => 'the game has already begun'], 302);
        }

        $this->gameService->saveUserBet($game, $this->getUser(), json_decode($request->request->get('data'), true));

        return $this->json($this->gameService->getPercentageBetDistribution($game));
    }

    /**
     * @Route("/game/{id}/save-result", name="game_save_result", methods={"POST"})
     * @IsGranted("ROLE_USER")
     */
    public function saveResult(Request $request, Game $game): JsonResponse
    {
        if ($game->getGoalsHome() !== null && $game->getGoalsGuest() !== null) {
            return $this->json(['errorMsg' => 'we already have game result in database'], 302);
        }

        $this->gameService->saveGameResult($game, $this->getUser(), json_decode($request->request->get('data'), true));
        $this->gameService->calcBetPoints($game);
        try {
            $this->rankingService->calculateFromGame($game);
            foreach ($this->rankingService->selectDependentRankings($game) as $ranking) {
                $this->rankingService->setOrder($ranking);
            }
        } catch (\Exception $exception) {
            return $this->json($exception->getMessage(), 500);
        }

        return $this->json(['success']);
    }

    /**
     * @Route("/assign-round-entity", name="assign_round_entity")
     * @return JsonResponse
     */
    public function assignRoundEntity(): JsonResponse
    {
        $count = $this->gameService->assignRoundEntity();

        return $this->json('games updated: ' . $count);
    }
}
