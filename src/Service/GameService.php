<?php

namespace App\Service;

use App\Entity\Bet;
use App\Entity\Game;
use App\Entity\User;
use Symfony\Component\Security\Core\Security;

class GameService
{
    private $security;

    public function __construct(Security $security)
    {
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
}