<?php

namespace App\Service;

use App\Entity\Bet;
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
}