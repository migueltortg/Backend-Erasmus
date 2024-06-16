<?php

namespace App\Repository;

use App\Entity\Movilidad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Movilidad>
 *
 * @method Movilidad|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movilidad|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movilidad[]    findAll()
 * @method Movilidad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovilidadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movilidad::class);
    }

    //    /**
    //     * @return Movilidad[] Returns an array of Movilidad objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Movilidad
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
