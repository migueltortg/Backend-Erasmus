<?php

namespace App\Repository;

use App\Entity\ItemBaremableSolicitud;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ItemBaremableSolicitud>
 *
 * @method ItemBaremableSolicitud|null find($id, $lockMode = null, $lockVersion = null)
 * @method ItemBaremableSolicitud|null findOneBy(array $criteria, array $orderBy = null)
 * @method ItemBaremableSolicitud[]    findAll()
 * @method ItemBaremableSolicitud[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemBaremableSolicitudRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ItemBaremableSolicitud::class);
    }

    //    /**
    //     * @return ItemBaremableSolicitud[] Returns an array of ItemBaremableSolicitud objects
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

    //    public function findOneBySomeField($value): ?ItemBaremableSolicitud
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
