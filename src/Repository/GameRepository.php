<?php

namespace App\Repository;

use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    /**
     * Find all games for specified team
     *
     * @param $id
     * @return mixed
     */
    public function findTeamAllGames($id)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.home = :val')
            ->orWhere('g.guest = :val')
            ->setParameter('val', $id)
            ->orderBy('g.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find all games for specified season
     *
     * @param $id
     * @return mixed
     */
    public function findSeasonAllGames($id)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.season = :val')
            ->setParameter('val', $id)
            ->orderBy('g.date', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find next played game
     *
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findNextGame()
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.date > :val')
            ->setParameter('val', (new \DateTime())->format('Y-m-d H:i:s'))
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    // /**
    //  * @return Game[] Returns an array of Game objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Game
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
