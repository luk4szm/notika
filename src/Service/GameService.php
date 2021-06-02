<?php

namespace App\Service;

use App\Entity\Bet;
use App\Entity\Game;
use App\Entity\Round;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class GameService
{
    private $em;
    private $security;

    public function __construct(Security $security, EntityManagerInterface $em)
    {
        $this->em       = $em;
        $this->security = $security;
    }

    /**
     * Get specified User bet for Game
     * @param Game $game
     * @param User|null $user
     * @return Bet|null
     */
    public function getUserBet(Game $game, User $user = null): ?Bet
    {
        if (!$game->getBets()->count()) {
            return null;
        }

        if (!$user = $user ? $user : $this->security->getUser()) {
            return null;
        }

        foreach ($game->getBets() as $bet) {
            if ($bet->getUser() === $user) {
                return $bet;
            }
        }

        return null;
    }

    /**
     * Save new or update exist user Bet
     * @param Game $game
     * @param User $user
     * @param array $data
     * @return Bet
     * @throws \Exception
     */
    public function saveUserBet(Game $game, User $user, array $data): Bet
    {
        $bet = $this->em->getRepository(Bet::class)->findOneBy(['game' => $game, 'user' => $user]);
        $bet = $bet ? $bet : new Bet();
        $bet->setGoalsHome($data['goalsHome']);
        $bet->setGoalsGuest($data['goalsGuest']);

        if (!$bet->getId()) {
            $bet->setUser($user);
            $bet->setGame($game);
            $bet->setCreatedAt(new \DateTimeImmutable());

            $this->em->persist($bet);
        } else {
            $bet->setUpdatedAt(new \DateTimeImmutable());
        }

        $this->em->flush();

        return $bet;
    }

    /**
     * Return bet distribution [home|draw|guest]
     * @param Game $game
     * @return array|null
     */
    public function getBetDistribution(Game $game): ?array
    {
        if (!$game->getBets()->count()) {
            return null;
        }

        $bets = [
            'home'  => 0,
            'draw'  => 0,
            'guest' => 0,
        ];

        foreach ($game->getBets() as $bet) {
            if ($bet->getGoalsHome() > $bet->getGoalsGuest()) {
                $bets['home']++;
            } elseif ($bet->getGoalsHome() == $bet->getGoalsGuest()) {
                $bets['draw']++;
            } else {
                $bets['guest']++;
            }
        }

        return $bets;
    }

    /**
     * Return bet distribution [home|draw|guest] shown as a percentage
     * @param Game $game
     * @return array|null
     */
    public function getPercentageBetDistribution(Game $game): ?array
    {
        if (!$game->getBets()->count()) {
            return null;
        }

        $bets = $this->getBetDistribution($game);

        return [
            'home'  => number_format($bets['home'] / $game->getBets()->count() * 100, 1),
            'draw'  => number_format($bets['draw'] / $game->getBets()->count() * 100, 1),
            'guest' => number_format($bets['guest'] / $game->getBets()->count() * 100, 1),
        ];
    }

    /**
     * Returns betting statistics for the specified game
     * @param Game $game
     * @return array|null
     */
    public function getBetStats(Game $game): ?array
    {
        if (!$game->getBets()->count()) {
            return null;
        }

        if ($game->getGoalsHome() === null && $game->getGoalsGuest() === null) {
            return null;
        }

        $hits   = 0;
        $scored = 0;
        $pts    = 0;
        /** @var Bet $bet */
        foreach ($game->getBets() as $bet) {
            if ($bet->getHit()) {
                $hits++;
            }

            if ($bet->getPts() > 0) {
                $scored++;
                $pts += $bet->getPts();
            }
        }

        return [
            'scored' => $scored,
            'hits'   => $hits,
            'avgPts' => $pts / $game->getBets()->count(),
        ];
    }

    /**
     * Save game result
     * @param Game $game
     * @param User $user
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    public function saveGameResult(Game $game, User $user, array $data): bool
    {
        $game->setGoalsHome(intval($data['goalsHome']));
        $game->setGoalsGuest(intval($data['goalsGuest']));
        $game->setUpdatedAt(new \DateTimeImmutable());
        $game->setUpdatedBy($user);

        $this->em->flush();

        return true;
    }

    /**
     * Make up appeal to round for all games
     * @return int
     */
    public function assignRoundEntity(): int
    {
        $games = $this->em->getRepository(Game::class)->findBy(['round' => null]);
        $count = 0;

        /** @var Game $game */
        /** @var Round $round */
        foreach ($games as $game) {
            $round = $this->em->getRepository(Round::class)
                              ->findOneBy([
                                              'season'  => $game->getSeason(),
                                              'ordinal' => $game->getRoundNr(),
                                          ]);

            $game->setRound($round);

            $count++;
        }

        $this->em->flush();

        return $count;
    }

    /**
     * Calculate points for every bet in game
     * @param Game $game
     * @return bool
     */
    public function calcBetPoints(Game $game)
    {
        if (!$game->getBets()->count()) {
            return null;
        }

        if ($game->getGoalsHome() === null || $game->getGoalsGuest() === null) {
            return null;
        }

        if (!$game->getIsCounted()) {
            foreach ($game->getBets() as $bet) {
                $bet->setPts(0);
            }

            $this->em->flush();

            return true;
        }

        /** @var Bet $bet */
        foreach ($game->getBets() as $bet) {
            $bet->setPts(0);

            // exact hit & number of goals over 3.5
            if ($game->getGoalsHome() == $bet->getGoalsHome()
                && $game->getGoalsGuest() == $bet->getGoalsGuest()
                && $game->getGoalsHome() + $game->getGoalsGuest() > 3.5
            ) {
                $bet->setPts(5);
                $bet->setHit(true);
            } // exact hit & number of goals below 3.5
            elseif ($game->getGoalsHome() == $bet->getGoalsHome()
                    && $game->getGoalsGuest() == $bet->getGoalsGuest()
                    && $game->getGoalsHome() + $game->getGoalsGuest() < 3.5
            ) {
                $bet->setPts(4);
                $bet->setHit(true);
            } // correct settlement & good goals diffrence
            elseif ((($game->getGoalsHome() > $game->getGoalsGuest() && $bet->getGoalsHome() > $bet->getGoalsGuest())
                     ||
                     ($game->getGoalsHome() < $game->getGoalsGuest() && $bet->getGoalsHome() < $bet->getGoalsGuest())
                     ||
                     ($game->getGoalsHome() == $game->getGoalsGuest() && $bet->getGoalsHome() == $bet->getGoalsGuest()))
                    &&
                    abs($game->getGoalsHome() - $game->getGoalsGuest()) == abs($bet->getGoalsHome() - $bet->getGoalsGuest())
            ) {
                if ($game->getGoalsHome() == $bet->getGoalsHome()) {
                    $denominator = 1;
                } else {
                    $denominator = abs($game->getGoalsHome() - $bet->getGoalsHome());
                }
                $bet->setPts(
                    number_format(2 + 1 / $denominator, 1)
                );
            } // correct settlement & wrong goals diffrence
            elseif ((($game->getGoalsHome() > $game->getGoalsGuest() && $bet->getGoalsHome() > $bet->getGoalsGuest())
                     ||
                     ($game->getGoalsHome() < $game->getGoalsGuest() && $bet->getGoalsHome() < $bet->getGoalsGuest())
                     ||
                     ($game->getGoalsHome() == $game->getGoalsGuest() && $bet->getGoalsHome() == $bet->getGoalsGuest()))
                    &&
                    abs($game->getGoalsHome() - $game->getGoalsGuest()) != abs($bet->getGoalsHome() - $bet->getGoalsGuest())
            ) {
                $bet->setPts(2);
            }

            if ($game->getIsAwarded()) {
                $bet->setPts(2 * $bet->getPts());
            }
        }

        $this->em->flush();

        return true;
    }
}