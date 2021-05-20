<?php

namespace App\Repository;

use App\Entity\RankingInfo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RankingInfo|null find($id, $lockMode = null, $lockVersion = null)
 * @method RankingInfo|null findOneBy(array $criteria, array $orderBy = null)
 * @method RankingInfo[]    findAll()
 * @method RankingInfo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RankingInfoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RankingInfo::class);
    }

    // /**
    //  * @return RankingInfo[] Returns an array of RankingInfo objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RankingInfo
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
