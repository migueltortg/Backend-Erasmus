<?php

namespace App\Repository;

use App\Entity\Convocatoria;
use App\Entity\ItemBaremableConvocatoria;
use App\Entity\ListaProvisional;
use App\Entity\Solicitud;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Convocatoria>
 *
 * @method Convocatoria|null find($id, $lockMode = null, $lockVersion = null)
 * @method Convocatoria|null findOneBy(array $criteria, array $orderBy = null)
 * @method Convocatoria[]    findAll()
 * @method Convocatoria[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConvocatoriaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Convocatoria::class);
    }

    public function add(Convocatoria $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Convocatoria $convocatoria): void
    {
        $entityManager = $this->getEntityManager();

        // Eliminar todos los items baremables relacionados
        $itemBaremableConvocatoriaRepository = $entityManager->getRepository(ItemBaremableConvocatoria::class);
        $itemsBaremables = $itemBaremableConvocatoriaRepository->findBy(['idConvocatoria' => $convocatoria]);
        foreach ($itemsBaremables as $item) {
            $entityManager->remove($item);
        }

        // Eliminar todas las solicitudes relacionadas
        $solicitudRepository = $entityManager->getRepository(Solicitud::class);
        $solicitudes = $solicitudRepository->findBy(['idConvocatoria' => $convocatoria]);
        foreach ($solicitudes as $solicitud) {
            $entityManager->remove($solicitud);
        }

        $listaProvisionalRepository = $entityManager->getRepository(ListaProvisional::class);
        $listasProvisionales = $listaProvisionalRepository->findBy(['idConvocatoria' => $convocatoria]);
        foreach ($listasProvisionales as $listaProvisional) {
            $entityManager->remove($listaProvisional);
        }

        $entityManager->flush();

        $entityManager->remove($convocatoria);
        $entityManager->flush();
    }
    //    /**
    //     * @return Convocatoria[] Returns an array of Convocatoria objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Convocatoria
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
