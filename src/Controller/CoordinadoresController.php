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

class CoordinadoresController extends AbstractController
{
    private $entityManager;
    private $serializer;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }

    //DEVUELVE INFORMACION DE COORDINADORES PARA UN SELECT
    #[Route('/api/cargarCoordinadores', methods: ['GET'])]
    public function cargarCoordinadores(Request $request): JsonResponse|Response
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

        $users = $userRepository->findAll();

        $response = [];

        $coordinadores = array_filter($users, function ($user) {
            return in_array('ROLE_COORDINADOR', $user->getRoles());
        });

        foreach ($coordinadores as $coordinador) {
            $response[] = [
                'value' => $coordinador->getId(),
                'label' => $coordinador->getNombre()
            ];
        }

        return new JsonResponse($response, JsonResponse::HTTP_OK);
    }
}
