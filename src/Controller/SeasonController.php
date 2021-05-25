<?php

namespace App\Controller;

use App\Entity\Season;
use App\Service\ScheduleService;
use App\Service\SeasonService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SeasonController extends AbstractController
{
    private $em;
    private $seasonService;
    private $scheduleService;

    public function __construct(
        EntityManagerInterface $em,
        ScheduleService $scheduleService,
        SeasonService $seasonService
    )
    {
        $this->em              = $em;
        $this->seasonService   = $seasonService;
        $this->scheduleService = $scheduleService;
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
     * @Route("/schedule/{slug}", name="season_schedule")
     * @param Season $season
     * @return Response
     */
    public function schedule(Season $season): Response
    {
        $games = $this->scheduleService->getSeasonSchedule($season);

        return $this->render('season/schedule.html.twig', [
            'season' => $season,
            'games'  => $games,
        ]);
    }

    /**
     * @Route(name="active_seasons")
     * @return Response
     */
    public function active(): Response
    {
        $activeSeasons = $this->em->getRepository(Season::class)->findActiveSeasons();

        return $this->render('_partials/active_seasons.html.twig', [
            'seasons' => $activeSeasons,
        ]);
    }

    /**
     * Creating missing round entities for specified season games
     * @Route("/season/{slug}/create-missing-round", name="season_create_missing_round")
     * @param Season $season
     * @return Response
     * @throws \Exception
     */
    public function createMissingRound(Season $season): Response
    {
        $rounds = $this->seasonService->createMissingRound($season);

        return $this->json('rounds created: ' . $rounds);
    }

    /**
     * Creating missing round entities for all seasons
     * @Route("/create-missing-round", name="create_missing_round")
     * @return Response
     * @throws \Exception
     */
    public function createMissingRoundAllSeason(): Response
    {
        $rounds = $this->seasonService->createAllMissingRound();

        return $this->json('rounds created: ' . $rounds);
    }
}
