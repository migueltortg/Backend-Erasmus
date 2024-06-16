<?php

namespace App\Repository;

use App\Entity\ListaDefinitiva;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ListaDefinitiva>
 *
 * @method ListaDefinitiva|null find($id, $lockMode = null, $lockVersion = null)
 * @method ListaDefinitiva|null findOneBy(array $criteria, array $orderBy = null)
 * @method ListaDefinitiva[]    findAll()
 * @method ListaDefinitiva[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ListaDefinitivaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ListaDefinitiva::class);
    }

//    /**
//     * @return ListaDefinitiva[] Returns an array of ListaDefinitiva objects
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

//    public function findOneBySomeField($value): ?ListaDefinitiva
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
