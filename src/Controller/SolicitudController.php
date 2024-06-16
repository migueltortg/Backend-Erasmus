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

class SolicitudController extends AbstractController
{
    private $entityManager;
    private $serializer;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }

    //LISTADO DE SOLICITUDES
    #[Route('/api/listaSolicitudes', methods: ['GET'])]
    public function listadoSolicitudes(Request $request): JsonResponse|Response
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

        if ($user) {
            $solicitudRepository = $this->entityManager->getRepository(Solicitud::class);
            $query = $solicitudRepository->createQueryBuilder('s')
                ->where('s.status = :done')
                ->setParameter('done', 'DONE')
                ->getQuery();

            $solicitudesData = $query->getResult();

            $solicitudesData = $this->serializer->serialize($solicitudesData, 'json', ['groups' => ['solicitud:list', 'convocatoria:list', 'user:list', 'grupo:list', 'itemBaremable:list']]);


            $responseData = [
                'solicitudes' => json_decode($solicitudesData, true),
            ];

            return new JsonResponse($responseData, JsonResponse::HTTP_OK);
        } else {
            return new JsonResponse(['message' => 'Usuario no encontrado'], JsonResponse::HTTP_NOT_FOUND);
        }
    }

    //LISTADO DE IMAGENES DE UNA SOLICITUD
    #[Route('/api/listaImgSolicitud/{solicitudId}', methods: ['GET'])]
    public function listaImgSolicitud(Request $request, int $solicitudId): JsonResponse
    {
        // Retrieve the authorization header
        $authorizationHeader = $request->headers->get('Authorization');

        // Check if the authorization header is valid
        if (!$authorizationHeader || !str_starts_with($authorizationHeader, 'Bearer ')) {
            return new JsonResponse(['message' => 'Token no proporcionado o inválido'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        // Extract and decode the token
        $token = str_replace('Bearer ', '', $authorizationHeader);
        $decodedToken = base64_decode($token);

        // Validate the token decoding
        if ($decodedToken === false) {
            return new JsonResponse(['message' => 'Token inválido'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        // Extract email from the decoded token
        $tokenParts = explode('|', $decodedToken);
        $email = $tokenParts[1] ?? null;

        // Validate the email extraction
        if (!$email) {
            return new JsonResponse(['message' => 'Token inválido'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        // Fetch the user from the repository
        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->findOneBy(['email' => $email]);

        // Check if the user exists
        if (!$user) {
            return new JsonResponse(['message' => 'Usuario no encontrado'], JsonResponse::HTTP_NOT_FOUND);
        }

        // Fetch the solicitud from the repository
        $solicitudRepository = $this->entityManager->getRepository(Solicitud::class);
        $solicitud = $solicitudRepository->find($solicitudId);

        // Check if the solicitud exists
        if (!$solicitud) {
            return new JsonResponse(['message' => 'Solicitud no encontrada'], JsonResponse::HTTP_NOT_FOUND);
        }

        // Define the base URL for the images
        $imagesDirectory = $this->getParameter('kernel.project_dir') . '\itemsBaremables/';

        $images = [];

        // Retrieve URLs of images related to the solicitud
        foreach (scandir($imagesDirectory) as $file) {
            if (strpos($file, $solicitudId . '-') === 0) {
                $fileNameWithoutExtension = pathinfo($file, PATHINFO_FILENAME);
                $itemBaremableId = explode('-', $fileNameWithoutExtension)[1] ?? null;
                if ($itemBaremableId) {
                    $images[$itemBaremableId] = $file;
                }
            }
        }

        // Return the response with the image URLs
        return new JsonResponse(['imagenes' => $images], JsonResponse::HTTP_OK);
    }

    //FUNCION QUE DEVUELVE LAS IMAGENES
    public function serveImage($filename)
    {
        // Construye la ruta completa del archivo
        $filePath = $this->getParameter('kernel.project_dir') . '/itemsBaremables/' . $filename;

        // Devuelve la respuesta de archivo
        return new BinaryFileResponse($filePath);
    }

    //FUNCION BAREMAR SOLICITUD
    #[Route('/api/baremarSolicitud/{idSolicitud}', methods: ['POST'])]
    public function baremarSolicitud(int $idSolicitud, Request $request): JsonResponse
    {
        $authorizationHeader = $request->headers->get('Authorization');

        $token = str_replace('Bearer ', '', $authorizationHeader);
        $decodedToken = base64_decode($token);

        $email = explode('|', $decodedToken)[1] ?? null;

        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            return new JsonResponse(['message' => 'Usuario no encontrado'], JsonResponse::HTTP_NOT_FOUND);
        }

        $solicitudRepository = $this->entityManager->getRepository(Solicitud::class);
        $solicitud = $solicitudRepository->find($idSolicitud);



        $solicitud->getIdConvocatoria()->getItemsBaremables();

        $nota = 0;
        foreach ($solicitud->getIdConvocatoria()->getItemsBaremables() as $item) {
            $nota = $nota + $request->request->get($item->getId());
        }


        $solicitud->setNota($nota);
        $solicitud->setStatus("BAREMADO");

        $this->entityManager->persist($solicitud);
        $this->entityManager->flush();

        return new JsonResponse("TODO BIEN");
    }

    //FUNCION HACER LISTADO PROVISIONAL
    #[Route('/api/listadoProvisional/{idConvocatoria}', methods: ['GET'])]
    public function listadoProvisional(Request $request, int $idConvocatoria): JsonResponse
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

        $tokenParts = explode('|', $decodedToken);
        $email = $tokenParts[1] ?? null;

        if (!$email) {
            return new JsonResponse(['message' => 'Token inválido'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            return new JsonResponse(['message' => 'Usuario no encontrado'], JsonResponse::HTTP_NOT_FOUND);
        }

        $solicitudRepository = $this->entityManager->getRepository(Solicitud::class);
        $convocatoriaRepository = $this->entityManager->getRepository(Convocatoria::class);

        $convocatoria = $convocatoriaRepository->find($idConvocatoria);
        $solicitudes = $solicitudRepository->findBy(
            ['idConvocatoria' => $convocatoria],
            ['nota' => 'DESC']
        );

        $arrayList = array();

        foreach ($solicitudes as $item) {
            array_push($arrayList, $item->getIdUser()->getNombre() . " " . $item->getIdUser()->getApellido() . "  -  " . $item->getIdUser()->getDni() . "  |  " . $item->getNota());
        }

        $listaProvisional = new ListaProvisional();

        $listaProvisional->setIdConvocatoria($convocatoria);
        $listaProvisional->setListado($arrayList);

        $convocatoria->setStatus("LISTADO PROVISIONAL");

        $this->entityManager->persist($listaProvisional);
        $this->entityManager->persist($convocatoria);
        $this->entityManager->flush();

        return new JsonResponse("Lista rovisional creada con exito", JsonResponse::HTTP_OK);
    }

    //FUNCION RECIBIR LISTA PROVISIONAL
    #[Route('/api/listadoProvisionalLista/{idConvocatoria}', methods: ['GET'])]
    public function listadoProvisionalLista(Request $request, int $idConvocatoria): JsonResponse
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

        $tokenParts = explode('|', $decodedToken);
        $email = $tokenParts[1] ?? null;

        if (!$email) {
            return new JsonResponse(['message' => 'Token inválido'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            return new JsonResponse(['message' => 'Usuario no encontrado'], JsonResponse::HTTP_NOT_FOUND);
        }

        $listadoProvisionalRepository = $this->entityManager->getRepository(ListaProvisional::class);
        $convocatoriaRepository = $this->entityManager->getRepository(Convocatoria::class);

        $convocatoria = $convocatoriaRepository->find($idConvocatoria);
        $listadoProvisional = $listadoProvisionalRepository->findOneBy(['idConvocatoria' => $convocatoria]);

        $resultArray = [];

        foreach ($listadoProvisional->getListado() as $puesto) {

            list($nombreDni, $puntos) = explode('|', $puesto);

            list($nombre, $dni) = explode('-', $nombreDni);

            $nombre = trim($nombre);
            $dni = trim($dni);
            $puntos = trim($puntos);

            $obj = new stdClass();
            $obj->nombre = $nombre;
            $obj->dni = $dni;
            $obj->nota = $puntos;

            $resultArray[] = $obj;
        }

        return new JsonResponse(["solicitantes" => $resultArray], JsonResponse::HTTP_OK);
    }

    //FUNCION HACER LISTADO DEFINITIVO
    #[Route('/api/listadoDefinitivo/{idConvocatoria}', methods: ['POST'])]
    public function listadoDefinitivo(Request $request, int $idConvocatoria): JsonResponse
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

        $tokenParts = explode('|', $decodedToken);
        $email = $tokenParts[1] ?? null;

        if (!$email) {
            return new JsonResponse(['message' => 'Token inválido'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            return new JsonResponse(['message' => 'Usuario no encontrado'], JsonResponse::HTTP_NOT_FOUND);
        }

        $convocatoriaRepository = $this->entityManager->getRepository(Convocatoria::class);

        $convocatoria = $convocatoriaRepository->find($idConvocatoria);


        $listadoFinalArray = $request->request->get('listadoDefinitivo');

        $listaDefinitiva = new ListaDefinitiva();

        $listaDefinitiva->setIdConvocatoria($convocatoria);

        $listaDefinitiva->setListado(json_decode($listadoFinalArray, true));

        $convocatoria->setStatus("LISTADO FINAL");

        $i = 1;
        foreach (json_decode($listadoFinalArray) as $listado) {
            if ($i <= $convocatoria->getNumMovilidades()) {
                $userObj = $userRepository->findOneBy(['dni' => $listado->dni]);
                
                $movilidad = new Movilidad();
                $movilidad->setIdUser($userObj);
                $movilidad->setIdConvocatoria($convocatoria);
                $movilidad->setIdCoordinador($convocatoria->getIdCoordinador());

                $this->entityManager->persist($movilidad);
                $i++;
            }
        }

        $this->entityManager->persist($listaDefinitiva);
        $this->entityManager->persist($convocatoria);

        $this->entityManager->flush();


        return new JsonResponse("OK", JsonResponse::HTTP_OK);
    }
}
