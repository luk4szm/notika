<?php

namespace App\Service;

use App\Entity\User;

class UserService
{
    /**
     * Get all user ranking divided to current and finished
     * @param User $user
     * @return array|null
     */
    public function getRankings(User $user): ?array
    {
        if (!$user->getClassifications()->count()) {
            return null;
        }

        $current  = [];
        $finished = [];
        foreach ($user->getClassifications() as $classification) {
            if ($classification->getRanking()->getIsActive()) {
                $current[] = $classification;
            } else {
                $finished[] = $classification;
            }
        }

        return [
            'current'  => $current,
            'finished' => $finished,
        ];
    }
}