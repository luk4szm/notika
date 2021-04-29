<?php

namespace App\Controller;

use App\Entity\Game;
use App\Service\ScheduleService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

        return $this->render('game/game.html.twig', [
            'game'          => $game,
            'roundSchedule' => $roundSchedule,
        ]);
    }
}
