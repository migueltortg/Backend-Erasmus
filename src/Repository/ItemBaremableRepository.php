<?php

namespace App\Repository;

use App\Entity\ItemBaremable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ItemBaremable>
 *
 * @method ItemBaremable|null find($id, $lockMode = null, $lockVersion = null)
 * @method ItemBaremable|null findOneBy(array $criteria, array $orderBy = null)
 * @method ItemBaremable[]    findAll()
 * @method ItemBaremable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemBaremableRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ItemBaremable::class);
    }

    //    /**
    //     * @return ItemBaremable[] Returns an array of ItemBaremable objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('i.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ItemBaremable
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
