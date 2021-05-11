<?php

namespace App\Controller;

use App\Entity\Game;
use App\Service\GameService;
use App\Service\ScheduleService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    private $em;
    private $gameService;
    private $scheduleService;

    public function __construct(
        GameService $gameService,
        ScheduleService $scheduleService,
        EntityManagerInterface $em
    )
    {
        $this->gameService     = $gameService;
        $this->scheduleService = $scheduleService;
        $this->em              = $em;
    }

    /**
     * @Route("/game/{id}", name="game_detail")
     */
    public function index(Game $game): Response
    {
        $roundSchedule = $this->scheduleService->getRoundSchedule(
            $game->getSeason(),
            $game->getRound()
        );

        return $this->render('game/game.html.twig', [
            'game'          => $game,
            'roundSchedule' => $roundSchedule,
            'bets'          => $this->gameService->getPercentageBetDistribution($game),
            'userBet'       => $this->gameService->getUserBet($game),
        ]);
    }

    /**
     * @Route("/game/{id}/save-bet", name="game_save_bet", methods={"POST"})
     * @IsGranted("ROLE_USER")
     */
    public function saveBet(Request $request, Game $game): Response
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
    public function saveResult(Request $request, Game $game): Response
    {
        if ($game->getGoalsHome() !== null && $game->getGoalsGuest() !== null) {
            return $this->json(['status' => 'error'], 302);
        }

        $this->gameService->saveGameResult($game, $this->getUser(), json_decode($request->request->get('data'), true));

        return $this->json(['status' => 'success']);
    }
}
