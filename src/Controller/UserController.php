<?php

namespace App\Controller;

use App\Entity\Convocatoria;
use App\Entity\ListaDefinitiva;
use App\Entity\ListaProvisional;
use App\Entity\Movilidad;
use App\Entity\Solicitud;
use App\Entity\Tarea;
use App\Entity\TareaMovilidad;
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

class UserController extends AbstractController
{
    private $entityManager;
    private $serializer;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }

    //SACAR LISTADO USUARIOS
    #[Route('/api/listUser', methods: ['GET'])]
    public function listUser(Request $request): JsonResponse|Response
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

        if (!$user) {
            return new JsonResponse(['message' => 'Usuario no encontrado'], JsonResponse::HTTP_NOT_FOUND);
        }
        
        if (!in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return new JsonResponse(['message' => 'Usuario no autorizado'], JsonResponse::HTTP_FORBIDDEN);
        }

        $users = $userRepository->findAll();


        usort($users, function ($a, $b) {
            return strcmp($a->getNombre(), $b->getNombre());
        });

        $result = array_map(function($entity) {
            return [
                'id' => $entity->getId(),
                'nombre' => $entity->getNombre(),
                'apellido' => $entity->getApellido(),
                'email' => $entity->getEmail(),
                'roles' => $entity->getRoles(),
                'dni' => $entity->getDni(),
            ];
        }, $users);

        return new JsonResponse(['users' => $result], JsonResponse::HTTP_OK);
    }

    //EDITAR ROLES USUARIO
    #[Route('/api/editUser/{idUser}', methods: ['POST'])]
    public function editUser(Request $request,int $idUser): JsonResponse|Response
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

        if (!$user) {
            return new JsonResponse(['message' => 'Usuario no encontrado'], JsonResponse::HTTP_NOT_FOUND);
        }

        $userEdit=$userRepository->find($idUser);
        $userEdit->setRoles(explode(",", $request->request->get('roles')));

        $this->entityManager->persist($userEdit);
        $this->entityManager->flush();

        return new JsonResponse("OK", JsonResponse::HTTP_OK);
    }

    //ELIMINAR USUARIO
    #[Route('/api/deleteUser/{idUser}', methods: ['GET'])]
    public function deleteUser(Request $request,int $idUser): JsonResponse|Response
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

        if (!$user) {
            return new JsonResponse(['message' => 'Usuario no encontrado'], JsonResponse::HTTP_NOT_FOUND);
        }
        
        $userDelete=$userRepository->find($idUser);

        $this->entityManager->remove($userDelete);
        $this->entityManager->flush();

        return new JsonResponse("OK", JsonResponse::HTTP_OK);
    }
}
