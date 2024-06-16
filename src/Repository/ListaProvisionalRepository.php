<?php

namespace App\Repository;

use App\Entity\ListaProvisional;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ListaProvisional>
 *
 * @method ListaProvisional|null find($id, $lockMode = null, $lockVersion = null)
 * @method ListaProvisional|null findOneBy(array $criteria, array $orderBy = null)
 * @method ListaProvisional[]    findAll()
 * @method ListaProvisional[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ListaProvisionalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ListaProvisional::class);
    }

    //    /**
    //     * @return ListaProvisional[] Returns an array of ListaProvisional objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ListaProvisional
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
