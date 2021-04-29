<?php

namespace App\Service;

use App\Entity\Game;
use App\Entity\Season;
use Doctrine\ORM\EntityManagerInterface;

class ScheduleService
{
    private $em;
    private $now;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em  = $em;
        $this->now = (new \DateTime("2021-02-06 20:34:00"));
    }

    /**
     * Get whole schedule for season divided into games past, present and upcoming
     * @param Season $season
     * @return array
     */
    public function getSeasonSchedule(Season $season): array
    {
        $seasonGames = $this->em->getRepository(Game::class)->findSeasonGames($season);

        return $this->splitGamesByDate($seasonGames);
    }

    /**
     * Get list of games in specified round in season
     * @param Season $season
     * @param int $round
     * @return array
     */
    public function getRoundSchedule(Season $season, int $round): array
    {
        $roundGames = $this->em->getRepository(Game::class)->findRoundGames($season, $round);

        return $roundGames;
    }

    /**
     * Divide games array for past, upcoming, and present with label
     * @param array $gamesCollection
     * @return array
     */
    private function splitGamesByDate(array $gamesCollection): array
    {
        /** @var Game $game */
        foreach ($gamesCollection AS $game) {
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

        return $games;
    }
}