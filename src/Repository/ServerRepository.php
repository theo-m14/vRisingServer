<?php

namespace App\Repository;

use App\Entity\Server;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Server>
 *
 * @method Server|null find($id, $lockMode = null, $lockVersion = null)
 * @method Server|null findOneBy(array $criteria, array $orderBy = null)
 * @method Server[]    findAll()
 * @method Server[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Server::class);
    }

    public function add(Server $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Server $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function searchServer($name, $type, $openDay, $clan_size, $discord, $wipe)
    {
        $result = $this->createQueryBuilder('s')
            ->andWhere('s.name LIKE :name')
            ->setParameter('name', '%' . $name . '%');

        if ($type !== 'all') {
            $result->andWhere('s.type = :type')
                ->setParameter('type', $type == 'pvp');
        }

        if ($openDay !== 'all') {
            if ($openDay !== 'past') {
                $result->andWhere('s.openDay >= :now')
                    ->setParameter('now', new DateTime());
            } else {
                $result->andWhere('s.openDay <= :now')
                    ->setParameter('now', new DateTime());
            }
        }

        if ($clan_size !== 'all') {
            $result->andWhere('s.clan_size = :clan_size')
                ->setParameter('clan_size', $clan_size);
        }

        if ($discord !== 'all') {
            if ($discord == 'with') {
                $result->andWhere('s.discord IS NOT NULL');
            } else {
                $result->andWhere('s.discord IS NULL');
            }
        }

        if ($wipe !== 'all') {
            $result->andWhere('s.wipe = :wipe')
                ->setParameter('wipe', $wipe == 'with');
        }

        return $result->getQuery()->getResult();
    }

    //    /**
    //     * @return Server[] Returns an array of Server objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Server
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
