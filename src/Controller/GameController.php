<?php

namespace App\Controller;

use App\Entity\Bet;
use App\Entity\Game;
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
    private $scheduleService;

    public function __construct(ScheduleService $scheduleService, EntityManagerInterface $em)
    {
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

        $userBet = null;
        foreach ($game->getBets() as $bet) {
            if ($this->getUser() === $bet->getUser()) {
                $userBet = $bet;
                break;
            }
        }

        return $this->render('game/game.html.twig', [
            'game'          => $game,
            'roundSchedule' => $roundSchedule,
            'userBet'       => $userBet,
        ]);
    }

    /**
     * @Route("/game/{id}/save-bet", name="game_save_bet", methods={"POST"})
     * @IsGranted("ROLE_USER")
     */
    public function saveBet(Request $request, Game $game): Response
    {
        $data = json_decode($request->request->get('data'), true);

        /** @var Bet $bet */
        $bet = $this->em
            ->getRepository(Bet::class)
            ->findOneBy([
                            'game' => $game,
                            'user' => $this->getUser(),
                        ]);
        $bet = $bet ? $bet : new Bet();
        $bet->setGoalsHome($data['goalsHome']);
        $bet->setGoalsGuest($data['goalsGuest']);

        if (!$bet->getId()) {
            $bet->setUser($this->getUser());
            $bet->setGame($game);
            $bet->setCreatedAt(new \DateTimeImmutable());

            $this->em->persist($bet);
        } else {
            $bet->setUpdatedAt(new \DateTimeImmutable());
        }

        $this->em->flush();

        return $this->json(['success' => 'ok']);
    }

    /**
     * @Route("/game/{id}/save-result", name="game_save_result", methods={"POST"})
     * @IsGranted("ROLE_USER")
     */
    public function saveResult(Request $request, Game $game): Response
    {
        $data = json_decode($request->request->get('data'), true);

        $game->setGoalsHome(intval($data['goalsHome']));
        $game->setGoalsGuest(intval($data['goalsGuest']));
        $game->setUpdatedAt(new \DateTimeImmutable());
        $game->setUpdatedBy($this->getUser());

        $this->em->flush();

        return $this->json(['success' => 'ok']);
    }
}
