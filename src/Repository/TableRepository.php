<?php

namespace App\Repository;

use App\Entity\Table;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Table|null find($id, $lockMode = null, $lockVersion = null)
 * @method Table|null findOneBy(array $criteria, array $orderBy = null)
 * @method Table[]    findAll()
 * @method Table[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TableRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Table::class);
    }

    public function findOrderedSeasonTable(int $id)
    {
        $qb = $this->_em->createQueryBuilder();

        return $this->createQueryBuilder('t')
                    ->andWhere('t.season = :val')
                    ->setParameter('val', $id)
                    ->addOrderBy('t.division', 'ASC')
                    ->addOrderBy('t.bracket', 'ASC')
                    ->addOrderBy('t.place', 'ASC')
                    ->addOrderBy('t.points', 'DESC')
                    ->addOrderBy($qb->expr()->diff('t.goalsFor', 't.goalsAgainst'), 'DESC')
                    ->addOrderBy('t.goalsFor', 'DESC')
                    ->addOrderBy('t.won', 'DESC')
                    ->getQuery()
                    ->getResult();
    }
}
