<?php

namespace App\Repository;

use App\Entity\ItemBaremableConvocatoria;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ItemBaremableConvocatoria>
 *
 * @method ItemBaremableConvocatoria|null find($id, $lockMode = null, $lockVersion = null)
 * @method ItemBaremableConvocatoria|null findOneBy(array $criteria, array $orderBy = null)
 * @method ItemBaremableConvocatoria[]    findAll()
 * @method ItemBaremableConvocatoria[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemBaremableConvocatoriaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ItemBaremableConvocatoria::class);
    }

    //    /**
    //     * @return ItemBaremableConvocatoria[] Returns an array of ItemBaremableConvocatoria objects
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

    //    public function findOneBySomeField($value): ?ItemBaremableConvocatoria
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
