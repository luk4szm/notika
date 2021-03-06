<?php

namespace App\Repository;

use App\Entity\Game;
use App\Entity\Round;
use App\Entity\Season;
use App\Entity\Team;
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
     * @param $id
     * @return mixed
     */
    public function findTeamGames(Team $team)
    {
        return $this->createQueryBuilder('g')
                    ->andWhere('g.home = :val')
                    ->orWhere('g.guest = :val')
                    ->setParameter('val', $team)
                    ->orderBy('g.date', 'DESC')
                    ->getQuery()
                    ->getResult();
    }

    /**
     * Find all games for specified season
     * @param Season $season
     * @return mixed
     */
    public function findSeasonGames(Season $season)
    {
        return $this->createQueryBuilder('g')
                    ->andWhere('g.season = :val')
                    ->setParameter('val', $season)
                    ->orderBy('g.date', 'ASC')
                    ->getQuery()
                    ->getResult();
    }

    /**
     * Find all games for specified season round
     * @param Season $season
     * @param int $round
     * @return mixed
     */
    public function findRoundGames(Season $season, Round $round)
    {
        return $this->createQueryBuilder('g')
                    ->andWhere('g.season = :season')
                    ->setParameter('season', $season)
                    ->andWhere('g.round = :round')
                    ->setParameter('round', $round)
                    ->orderBy('g.date', 'ASC')
                    ->getQuery()
                    ->getResult();
    }

    /**
     * Find next played game
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findNextGame()
    {
        return $this->createQueryBuilder('g')
                    ->andWhere('g.date > :val')
                    ->setParameter('val', (new \DateTime()))
                    ->setMaxResults(1)
                    ->getQuery()
                    ->getOneOrNullResult();
    }

    /**
     * Find upcoming games for specified season
     * @param Season $season
     * @return mixed
     * @throws \Exception
     */
    public function findUpcomingGames(Season $season)
    {
        return $this->createQueryBuilder('g')
                    ->andWhere('g.season = :season')
                    ->setParameter('season', $season)
                    ->andWhere('g.date > :val')
                    ->setParameter('val', (new \DateTime()))
                    ->getQuery()
                    ->getResult();
    }

    /**
     * Find all games with missing result
     * @return mixed
     * @throws \Exception
     */
    public function findGamesWithNoResult()
    {
        $time = (new \DateTime())
            ->sub(new \DateInterval('PT' . Game::GAMETIME . 'M'));

        return $this->createQueryBuilder('g')
                    ->andWhere('g.date < :val')
                    ->setParameter('val', $time)
                    ->andWhere('g.goalsHome IS NULL')
                    ->andWhere('g.goalsGuest IS NULL')
                    ->orderBy('g.date', 'ASC')
                    ->getQuery()
                    ->getResult();
    }
}
