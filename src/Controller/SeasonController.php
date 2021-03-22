<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Season;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SeasonController extends AbstractController
{
    private $now;
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->now = (new \DateTime("2021-02-06 20:34:00"));
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
        $seasonGames = $this->em->getRepository(Game::class)->findSeasonAllGames($season->getId());

        foreach ($seasonGames AS $game) {
            // get list of past games
            if ($game->getDate() < $this->now) {
                $gamesPast[$game->getRound()][] = $game;
            }
            // get list of upcoming games
            if ($game->getDate() > $this->now) {
                $gamesUpcoming[$game->getRound()][] = $game;
            }
            //get list of present games
            if ($game->getDate() <= $this->now and $game->getEndDate() > $this->now) {
                $gamesPresent[$game->getRound()][] = $game;
            }
        }

        $games = [];
        if (!empty($gamesPresent)) {
            ksort($gamesPresent, SORT_NUMERIC);
            $games[] = ['label' => 'Spotkania w toku', 'data' => $gamesPresent];
        }
        if (!empty($gamesUpcoming)) {
            ksort($gamesUpcoming, SORT_NUMERIC);
            $games[] = ['label' => 'Zaplanowane spotkania', 'data' => $gamesUpcoming];
        }
        if (!empty($gamesPast)) {
            krsort($gamesPast, SORT_NUMERIC);
            $games[] = ['label' => 'Rozegrane spotkania', 'data' => $gamesPast];
        }

        return $this->render('season/schedule.html.twig', [
            'season' => $season,
            'games' => $games
        ]);
    }
}
