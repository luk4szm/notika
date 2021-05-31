<?php

namespace App\Service;

use App\Entity\Bet;
use App\Entity\Game;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class BetService
{
    private $em;
    private $security;

    public function __construct(EntityManagerInterface $em, Security $security)
    {
        $this->em       = $em;
        $this->security = $security;
    }

    /**
     * Count missing bet for specified user
     * @param User|null $user
     * @return int|null
     */
    public function countUserMissingBets(User $user = null): ?int
    {
        $user = $user instanceof User ? $user : $this->security->getUser();

        if (!$user) {
            return null;
        }

        return count($this->em->getRepository(Bet::class)->findUserMissingBets($user));
    }

    /**
     * Calculate stats of user bets
     * @param User $user
     * @return array
     */
    public function calcUserBets(User $user): array
    {
        $bets['amount'] = $user->getBets()->count();
        $bets['hits'] = 0;
        $bets['good'] = 0;

        /** @var Bet $bet */
        foreach ($user->getBets() as $bet) {
            if ($bet->getHit() == true) {
                $bets['hits']++;
                $bets['good']++;
                continue;
            }

            if ($bet->getPts() > 0)
            {
                $bets['good']++;
            }
        }

        return $bets;
    }

    /**
     * Generate random bets for all users for specified games
     * @return bool
     * @throws \Exception
     */
    public function generateBets()
    {
        $users = $this->em->getRepository(User::class)->findAll();
        $games = $this->em->getRepository(Game::class)->findBy(['goalsHome' => null]);

        /** @var User $user */
        foreach ($users as $user) {
            /** @var Game $game */
            foreach ($games as $game) {
                $bet = $this->em
                    ->getRepository(Bet::class)
                    ->findBy([
                                 'user' => $user,
                                 'game' => $game,
                             ]);

                if ($bet) {
                    continue;
                }

                $bet = new Bet();
                $bet->setUser($user)
                    ->setGame($game)
                    ->setGoalsHome(rand(0, 4))
                    ->setGoalsGuest(rand(0, 4))
                    ->setCreatedAt(new \DateTime());

                $this->em->persist($bet);
            }
        }

        $this->em->flush();

        return true;
    }
}