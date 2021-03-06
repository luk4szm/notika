<?php

namespace App\Service;

use App\Entity\Classification;
use App\Entity\Game;
use App\Entity\Ranking;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class RankingService
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Recalculate the whole ranking
     * @param Ranking $ranking
     * @return array|null
     * @throws \Exception
     */
    public function recalculate(Ranking $ranking): ?array
    {
        $rankingParticipant = $this->em->getRepository(User::class)->findRankingParticipants($ranking);

        if (empty($rankingParticipant)) {
            return null;
        }

        /** @var User $user */
        foreach ($rankingParticipant as $user) {
            $rankClassification[$user->getId()] = $this->em
                ->getRepository(Classification::class)
                ->findOneBy([
                                'user'    => $user,
                                'ranking' => $ranking,
                            ]);

            if ($rankClassification[$user->getId()] === null) {
                $rankClassification[$user->getId()] = (new Classification())
                    ->setUser($user)
                    ->setRanking($ranking)
                    ->setCreatedAt(new \DateTime());
            } else {
                /** @var Classification $classification */
                $classification = $rankClassification[$user->getId()];
                $classification->setPts(0)
                               ->setTypedRounds([])
                               ->setTypedGames(0)
                               ->setHits(0)
                               ->setScored(0)
                               ->setUpdatedAt(new \DateTime());
            }

        }

        /** @var Game $game */
        foreach ($ranking->getSeason()->getGames() as $game) {
            if ($ranking->getStartRound() && $ranking->getEndRound()) {
                if ($game->getRound()->getOrdinal() < $ranking->getStartRound() ||
                    $game->getRound()->getOrdinal() > $ranking->getEndRound()
                ) {
                    continue;
                }
            }

            foreach ($game->getBets() as $bet) {
                if ($bet->getPts() !== null) {
                    $user = $bet->getUser();

                    /** @var Classification $classification */
                    $classification = $rankClassification[$user->getId()];

                    if (!in_array($game->getRound()->getId(), $classification->getTypedRounds())) {
                        $classification->addTypedRound($game->getRound()->getId());
                        $classification->increaseTypedRounds();
                    }

                    if ($bet->getPts() > 0) {
                        $classification->addPts($bet->getPts());
                        $classification->increaseScored();
                    }

                    if ($bet->getHit() === true) {
                        $classification->increaseHit();
                    }

                    $classification->increaseTypedGames();
                }
            }
        }

        foreach ($rankClassification as $classification) {
            $this->em->persist($classification);
        }

        $this->em->flush();

        return $rankClassification;
    }

    /**
     * Calculate points from single game
     * @param Game $game
     * @return array
     * @throws \Exception
     */
    public function calculateFromGame(Game $game): array
    {
        $rankings = $this->selectDependentRankings($game);
        $bets     = 0;

        foreach ($rankings as $ranking) {
            if ($ranking->getStartRound() && $ranking->getEndRound()) {
                if ($game->getRound()->getOrdinal() < $ranking->getStartRound() ||
                    $game->getRound()->getOrdinal() > $ranking->getEndRound()
                ) {
                    continue;
                }
            }

            foreach ($game->getBets() as $bet) {
                if ($bet->getPts() !== null) {
                    $user = $bet->getUser();

                    /** @var Classification $classification */
                    $classification = $this
                        ->em
                        ->getRepository(Classification::class)
                        ->findOneBy([
                                        'user'    => $user,
                                        'ranking' => $ranking,
                                    ]);

                    if (!$classification) {
                        $classification = (new Classification())
                            ->setUser($user)
                            ->setRanking($ranking)
                            ->setCreatedAt(new \DateTime());

                        $this->em->persist($classification);
                    }

                    if (!in_array($game->getRound()->getId(), $classification->getTypedRounds())) {
                        $classification->addTypedRound($game->getRound()->getId());
                        $classification->increaseTypedRounds();
                    }

                    if ($bet->getPts() > 0) {
                        $classification->addPts($bet->getPts());
                        $classification->increaseScored();
                    }

                    if ($bet->getHit() === true) {
                        $classification->increaseHit();
                    }

                    $classification->increaseTypedGames();

                    $bets++;
                }
            }
        }

        $this->em->flush();

        return [count($rankings), $bets];
    }

    /**
     * Set the order of ranking participants according to the rules
     * @param Ranking $ranking
     * @return array|null
     */
    public function setOrder(Ranking $ranking): ?array
    {
        if (empty($ranking->getClassifications())) {
            return null;
        }

        $classifications = $ranking->getClassifications()->toArray();

        usort($classifications, function (Classification $userA, Classification $userB): int {
            return
                ($userB->getPts() <=> $userA->getPts()) * 1000 + //pts DESC
                ($userB->getHits() <=> $userA->getHits()) * 100 + //hits DESC
                ($userB->getScored() / $userB->getTypedGames() <=> $userA->getScored() / $userA->getTypedGames()) * 10 + //efficient DESC
                ($userA->getTypedGames() <=> $userB->getTypedGames()); //typed games ASC
        });

        $place = 1;
        /** @var Classification $classification */
        foreach ($classifications as $classification) {
            $classification->setPlace($place++);
        }

        $this->em->flush();

        return $classifications;
    }

    /**
     * Select dependant rankings for specified game
     * @param Game $game
     * @return array
     */
    public function selectDependentRankings(Game $game): array
    {
        return $game->getSeason()->getRankings()->toArray();
    }
}