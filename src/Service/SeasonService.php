<?php

namespace App\Service;

use App\Entity\Game;
use App\Entity\Round;
use App\Entity\Season;
use Doctrine\ORM\EntityManagerInterface;

class SeasonService
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Create missing rounds for all seasons
     * @return int
     * @throws \Exception
     */
    public function createAllMissingRound(): int
    {
        $seasons       = $this->em->getRepository(Season::class)->findAll();
        $createdRounds = 0;

        foreach ($seasons as $season) {
            $newRounds     = $this->createMissingRound($season);
            $createdRounds += $newRounds;
        }

        return $createdRounds;
    }

    /**
     * Create missing rounds for specific season
     * @param Season $season
     * @return int
     * @throws \Exception
     */
    public function createMissingRound(Season $season): int
    {
        $games     = $this->em->getRepository(Game::class)->findSeasonGames($season);
        $newRounds = 0;

        /** @var Game $game */
        foreach ($games as $game) {
            $round = $this->em->getRepository(Round::class)
                              ->findOneBy([
                                              'season'  => $season,
                                              'ordinal' => $game->getRoundNr(),
                                          ]);

            if (!$round instanceof Round) {
                $round = new Round();
                $round->setSeason($season)
                      ->setOrdinal($game->getRoundNr())
                      ->setGamesCount($season->getRoundGames())
                      ->setCreatedAt($game->getCreatedAt());
                $this->em->persist($round);
                $this->em->flush();
                $newRounds++;
            }
        }

        return $newRounds;
    }
}