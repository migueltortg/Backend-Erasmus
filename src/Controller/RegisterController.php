<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

class RegisterController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    //REGISTRO DE UN USUARIO
    #[Route('/api/register', methods: ['POST'])]
    public function register(Request $request)
    {
        // Obtener los datos del formulario
        $name = $request->request->get('name');
        $surnames = $request->request->get('surnames');
        $dni = $request->request->get('dni');
        $tfno = $request->request->get('tfno');
        $email = $request->request->get('email');
        $adress = $request->request->get('adress');
        $password = $request->request->get('password');

        // Crear un nuevo objeto User
        $user = new User();
        $user->setNombre($name);
        $user->setApellido($surnames);
        $user->setDni($dni);
        $user->setTfno($tfno);
        $user->setEmail($email);
        $user->setDireccion($adress);
        $user->setPassword($password);
        $user->setRoles(["ROLE_USER"]);

        $currentYear = date('Y');
        $nextYear = $currentYear + 1;
        $anoEscolar = "$currentYear/$nextYear";
        $user->setAnoEscolar($anoEscolar);

        // Guardar el usuario en la base de datos
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse("created");
    }
}
