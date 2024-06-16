<?php

namespace App\Repository;

use App\Entity\TareaMovilidad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TareaMovilidad>
 *
 * @method TareaMovilidad|null find($id, $lockMode = null, $lockVersion = null)
 * @method TareaMovilidad|null findOneBy(array $criteria, array $orderBy = null)
 * @method TareaMovilidad[]    findAll()
 * @method TareaMovilidad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TareaMovilidadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TareaMovilidad::class);
    }

    //    /**
    //     * @return TareaMovilidad[] Returns an array of TareaMovilidad objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?TareaMovilidad
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
