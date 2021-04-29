<?php

namespace App\Repository;

use App\Entity\Bet;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

/**
 * @method Bet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bet[]    findAll()
 * @method Bet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BetRepository extends ServiceEntityRepository
{
    private $security;

    public function __construct(ManagerRegistry $registry, Security $security)
    {
        parent::__construct($registry, Bet::class);
        $this->security = $security;
    }

    /**
     * Return array of user Bets objects
     *
     * @param User|null $user
     * @return mixed
     */
    public function findAllUserBets(User $user = null)
    {
        $user = $user ? $user : $this->security->getUser();

        return $this->createQueryBuilder('bet')
                    ->leftJoin('bet.game', 'game')
                    ->where('bet.user = :user')
                    ->setParameter('user', $user)
                    ->getQuery()
                    ->getResult();
    }

    public function findUserMissingBets(User $user = null)
    {
        $user = $user ? $user : $this->security->getUser();

        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT t1.* FROM (
                    SELECT m.*
                    FROM game m
                    WHERE m.date > :date
                ) AS t1
                LEFT JOIN (
                    SELECT m.*
                    FROM game m
                    LEFT JOIN bet b ON b.game_id = m.ID
                    WHERE b.user_id = :user
                ) AS t2
                ON t1.ID = t2.ID
                WHERE t2.ID IS NULL";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue('date', (new \DateTime("2021-02-06 20:34:00"))->format('Y-m-d H:i:s'));
        $stmt->bindValue('user', $user->getId());
        $stmt->execute();

        return $stmt->fetchAllAssociative();
    }

    public function countUserMissingBets(User $user = null)
    {
        $user = $user ? $user : $this->security->getUser();

        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT COUNT(t1.id) FROM (
                    SELECT m.*
                    FROM game m
                    WHERE m.date > :date
                ) AS t1
                LEFT JOIN (
                    SELECT m.*
                    FROM game m
                    LEFT JOIN bet b ON b.game_id = m.ID
                    WHERE b.user_id = :user
                ) AS t2
                ON t1.ID = t2.ID
                WHERE t2.ID IS NULL";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue('date', (new \DateTime("2021-02-06 20:34:00"))->format('Y-m-d H:i:s'));
        $stmt->bindValue('user', $user->getId());
        $stmt->execute();

        return $stmt->fetchOne();
    }
}
