<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class LoginController
{
    private $entityManager;
    private $hasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $hasher)
    {
        $this->entityManager = $entityManager;
        $this->hasher = $hasher;
    }

    //LOGUEO
    #[Route('/api/login', methods: ['POST'])]
    public function login(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        if (!$email || !$password) {
            return new JsonResponse(['message' => 'Email and password are required'], 400);
        }

        $userRepository = $this->entityManager->getRepository(User::class);

        $user = $userRepository->findOneBy(['email' => $email]);

        if (($user && $user->getPassword() === $password)) { 
            if($user && $user->getPassword() === $password){
                //CONSEGUIMOS TODA LA INFORMACIÓN QUE QUEREMOS DEVOLVER EN EL TOKEN Y LA ENCRIPTAMOS
                $userId = $user->getId();
                $userName = $user->getNombre();
                $roles = $user->getRoles();
                $rolesString = implode(',', $roles);
    
                $tokenData = "$userId|$email|$userName|$rolesString";
                $encodedToken = base64_encode($tokenData);
    
                return new JsonResponse(['token' => $encodedToken]);
            }
            
        } else {
            return new JsonResponse(['message' => 'Usuario no encontrado o contraseña incorrecta'], 404);
        }
    }
}
