<?php

namespace App\Repository;

use App\Entity\Ranking;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * Find all users who have saved their predictions as part of the ranking
     * @param Ranking $ranking
     * @return array|null
     */
    public function findRankingParticipants(Ranking $ranking): ?array
    {
        $qb = $this->createQueryBuilder('u')
                   ->leftJoin('u.bets', 'b')
                   ->leftJoin('b.game', 'g')
                   ->leftJoin('g.season', 's')
                   ->leftJoin('s.rankings', 'r')
                   ->andWhere('r.id = :id')
                   ->setParameter('id', $ranking->getId());

        if ($ranking->getStartRound() && $ranking->getEndRound()) {
            $qb->leftJoin('g.round', 'rd');
        }

        if ($ranking->getStartRound()) {
            $qb->andWhere('rd.ordinal >= :start')
               ->setParameter('start', $ranking->getStartRound());
        }

        if ($ranking->getEndRound()) {
            $qb->andWhere('rd.ordinal <= :end')
               ->setParameter('end', $ranking->getEndRound());
        }

        return $qb->getQuery()->getResult();
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
