<?php

namespace App\Service;

use App\Entity\Bet;
use App\Entity\Game;
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
     * Save game result
     * @param Game $game
     * @param User $user
     * @param array $data
     * @return Game
     * @throws \Exception
     */
    public function saveGameResult(Game $game, User $user, array $data): Game
    {
        $game->setGoalsHome(intval($data['goalsHome']));
        $game->setGoalsGuest(intval($data['goalsGuest']));
        $game->setUpdatedAt(new \DateTimeImmutable());
        $game->setUpdatedBy($user);

        $this->em->flush();

        return $game;
    }
}