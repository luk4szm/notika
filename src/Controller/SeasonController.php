<?php

namespace App\Controller;

use App\Entity\Season;
use App\Service\ScheduleService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SeasonController extends AbstractController
{
    private $em;
    private $scheduleService;

    public function __construct(ScheduleService $scheduleService, EntityManagerInterface $em)
    {
        $this->scheduleService = $scheduleService;
        $this->em = $em;
    }

    /**
     * @Route("/season/{slug}", name="season_detail")
     * @param Season $season
     * @return Response
     */
    public function index(Season $season): Response
    {
        return $this->render('season/index.html.twig', [
            'season' => $season,
        ]);
    }

    /**
     * @Route("/season/{slug}/schedule", name="season_schedule")
     * @param Season $season
     * @return Response
     */
    public function schedule(Season $season): Response
    {
        $games = $this->scheduleService->getSchedule($season);

        return $this->render('season/schedule.html.twig', [
            'games' => $games
        ]);
    }

    /**
     * Route (name="active_seasons")
     * @return Response
     */
    public function active(): Response
    {
        $activeSeasons = $this->em->getRepository(Season::class)->findActiveSeasons();

        return $this->render('_partials/active_seasons.html.twig', [
            'seasons' => $activeSeasons
        ]);
    }
}
