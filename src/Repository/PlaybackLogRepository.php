<?php

namespace App\Repository;

use App\Entity\PlaybackLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PlaybackLog>
 */
class PlaybackLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlaybackLog::class);
    }

    public function save(PlaybackLog $playbackLog, bool $flush = true): void
    {
        $this->getEntityManager()->persist($playbackLog);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findTopTracks(int $limit = 3): array
    {
        return $this->createQueryBuilder('pl')
            ->select('t.title AS title, COUNT(pl.id) AS playbacks_count')
            ->join('pl.track', 't')
            ->groupBy('t.id')
            ->orderBy('playbacks_count', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getArrayResult();
    }
}
