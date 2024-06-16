<?php

namespace App\Controller;

use App\Entity\Convocatoria;
use App\Entity\ListaDefinitiva;
use App\Entity\ListaProvisional;
use App\Entity\Movilidad;
use App\Entity\Solicitud;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;

class MovilidadController extends AbstractController
{
    private $entityManager;
    private $serializer;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }

    //LISTADO MOVILIDADES
    #[Route('/api/listaMovilidades', methods: ['GET'])]
    public function listaMovilidades(Request $request): JsonResponse|Response
    {
        $authorizationHeader = $request->headers->get('Authorization');
        if (!$authorizationHeader || !str_starts_with($authorizationHeader, 'Bearer ')) {
            return new JsonResponse(['message' => 'Token no proporcionado o inválido'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $token = str_replace('Bearer ', '', $authorizationHeader);
        $decodedToken = base64_decode($token);

        if ($decodedToken === false) {
            return new JsonResponse(['message' => 'Token inválido'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $email = explode('|', $decodedToken)[1] ?? null;
        if (!$email) {
            return new JsonResponse(['message' => 'Token inválido'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $userRepository = $this->entityManager->getRepository(User::class);

        $user = $userRepository->findOneBy(['email' => $email]);

        if (!($user)) {
            return new JsonResponse(['message' => 'Usuario no encontrado'], JsonResponse::HTTP_NOT_FOUND);
        }

        $movilidadRepository = $this->entityManager->getRepository(Movilidad::class);

        $queryBuilder = $movilidadRepository->createQueryBuilder('m')
            ->select('m')
            ->innerJoin('m.idConvocatoria', 'c')
            ->where('m.idUser = :user OR m.idCoordinador = :user')
            ->setParameter('user', $user)
            ->groupBy('c.id')
            ->orderBy('m.id', 'DESC');

        $movilidades = $queryBuilder->getQuery()->getResult();


        $movilidadesData = $this->serializer->serialize($movilidades, 'json', ['groups' => ['convocatoria:list', 'user:list', 'movilidad:list']]);

        return new JsonResponse(['listadoMovilidades' => $movilidadesData], JsonResponse::HTTP_OK);
    }
}
