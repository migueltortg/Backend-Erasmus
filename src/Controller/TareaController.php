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

class TareaController extends AbstractController
{
    private $entityManager;
    private $serializer;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }

    //FUNCION CREAR TAREA
    #[Route('/api/crearTask/{idConvocatoria}', methods: ['POST'])]
    public function crearTask(Request $request, int $idConvocatoria): JsonResponse|Response
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

        $tareaInfo = json_decode($request->request->get('task'), true);

        $convocatoriaRepository = $this->entityManager->getRepository(Convocatoria::class);
        $convocatoria = $convocatoriaRepository->find($idConvocatoria);

        $tarea = new Tarea();

        $tarea->setTitulo($tareaInfo["titulo"]);
        $tarea->setDescripcion($tareaInfo["descripcion"]);
        $tarea->setArchivo($tareaInfo["archivo"]);
        $tarea->setIdConvocatoria($convocatoria);

        $movilidadesRepository = $this->entityManager->getRepository(Movilidad::class);
        $movilidades = $movilidadesRepository->findBy(['idConvocatoria' => $convocatoria]);

        $this->entityManager->persist($tarea);
        $this->entityManager->flush();

        foreach ($movilidades as $item) {
            $movilidadTarea = new TareaMovilidad();

            $movilidadTarea->setIdTarea($tarea);
            $movilidadTarea->setIdMovilidad($item);
            if ($tareaInfo["archivo"]) {
                $movilidadTarea->setStatus("SIN ENTREGAR");
            } else {
                $movilidadTarea->setStatus("ANUNCIO");
            }

            $this->entityManager->persist($movilidadTarea);
        }

        $this->entityManager->flush();

        return new JsonResponse("OK", JsonResponse::HTTP_OK);
    }

    //LISTADO DE TAREAS (VISTA USUARIO)
    #[Route('/api/listaTareas/{idMovilidad}', methods: ['GET'])]
    public function listadoTasks(Request $request, int $idMovilidad): JsonResponse|Response
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

        $convocatoriaRepository=$this->entityManager->getRepository(Convocatoria::class);
        $convocatoria=$convocatoriaRepository->find($idMovilidad);

        $movilidadesRepository = $this->entityManager->getRepository(Movilidad::class);
        $movilidad = $movilidadesRepository->findBy(['idConvocatoria' => $convocatoria]);


        $tareaMovilidadRepository = $this->entityManager->getRepository(TareaMovilidad::class);
        $tareaMovilidad = $tareaMovilidadRepository->findBy(['idMovilidad' => $movilidad]);


        $tareasData = $this->serializer->serialize($tareaMovilidad, 'json', ['groups' => ['tareas:list', 'tareaMovilidad:list']]);

        return new JsonResponse(['tasks' => $tareasData], JsonResponse::HTTP_OK);
    }

    //FUNCION DEVUELVE TABLA DE TAREAS (VISTA COORDINADOR)
    #[Route('/api/tablaTareas/{idConvocatoria}', methods: ['GET'])]
    public function tablaTareas(Request $request, int $idConvocatoria): JsonResponse|Response
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

        $coordinadorRepository = $this->entityManager->getRepository(User::class);

        $userCoord = $coordinadorRepository->findOneBy(['email' => $email]);

        if (!($userCoord)) {
            return new JsonResponse(['message' => 'Usuario no encontrado'], JsonResponse::HTTP_NOT_FOUND);
        }

        $convocatoriaRepository = $this->entityManager->getRepository(Convocatoria::class);
        $convocatoria = $convocatoriaRepository->find($idConvocatoria);

        $movilidadesRepository = $this->entityManager->getRepository(Movilidad::class);
        $movilidades = $movilidadesRepository->findBy(["idConvocatoria" => $convocatoria]);

        $tareaMovilidadRepository = $this->entityManager->getRepository(TareaMovilidad::class);

        $tareasData = [];
        foreach ($movilidades as $movilidad) {
            $tareaMovilidad = $tareaMovilidadRepository->findBy(["idMovilidad" => $movilidad]);

            $tareasData[$movilidad->getId()]['nombre'] = $movilidad->getIdUser()->getNombre();

            foreach ($tareaMovilidad as $item) {
                if ($item->getIdTarea()->isArchivo()) {
                    $tareasData[$movilidad->getId()][$item->getIdTarea()->getTitulo()] = $item->getUrl();
                }
            }
        }

        return new JsonResponse(['tasksTable' => json_encode($tareasData, true)], JsonResponse::HTTP_OK);
    }

    //FUNCION SUBIR IMAGEN A UNA TAREA
    #[Route('/api/taskImgUpload/{idMovilidad}/{idTask}', methods: ['POST'])]
    public function taskImgUpload(int $idMovilidad, int $idTask, Request $request): JsonResponse
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

        $carpetaDestino = $this->getParameter('kernel.project_dir') . '/itemsTareas';

        $file = $request->files->get('file');


        $extension = $file->guessExtension();

        $nombreArchivo = $idTask . '-' . $idMovilidad . "." . $extension;

        $file->move($carpetaDestino, $nombreArchivo);

        $tareaMovilidadRepository = $this->entityManager->getRepository(TareaMovilidad::class);
        $tareaRepository = $this->entityManager->getRepository(Tarea::class);
        $movilidadRepository = $this->entityManager->getRepository(Movilidad::class);

        $tarea = $tareaRepository->find($idTask);
        $movilidad = $movilidadRepository->find($idMovilidad);

        $tareaMovilidad = $tareaMovilidadRepository->findOneBy(['idTarea' => $tarea, 'idMovilidad' => $movilidad]);

        $tareaMovilidad->setStatus("ENTREGADO");
        $tareaMovilidad->setUrl($nombreArchivo);

        $this->entityManager->persist($tareaMovilidad);
        $this->entityManager->flush();

        return new JsonResponse("Imagen subida exitosamente");
    }

    //FUNCION ELIMINA IMAGEN DE UNA TAREA
    #[Route('/api/deleteTaskImg', methods: ['POST'])]
    public function deleteTaskImg(Request $request): JsonResponse
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

        $idTareaMovilidad = $request->request->get('idTareaMovilidadd');


        $tareaMovilidadRepository = $this->entityManager->getRepository(TareaMovilidad::class);
        $tareaMovilidad = $tareaMovilidadRepository->find($idTareaMovilidad);

        $urlImagen = $tareaMovilidad->getUrl();

        $tareaMovilidad->setStatus("SIN ENTREGAR");
        $tareaMovilidad->setUrl(null);

        $this->entityManager->persist($tareaMovilidad);
        $this->entityManager->flush();

        $carpetaDestino = $this->getParameter('kernel.project_dir') . '/itemsTareas';
        $rutaImagen = $carpetaDestino . '/' . $urlImagen;

        if (file_exists($rutaImagen)) {
            unlink($rutaImagen);
        }

        return new JsonResponse("Imagen subida exitosamente");
    }

    //FUNCION QUE DEVUELVE IMAGEN DE LAS TAREAS
    public function serveImageTask($filename)
    {
        // Construye la ruta completa del archivo
        $filePath = $this->getParameter('kernel.project_dir') . '/itemsTareas/' . $filename;

        // Devuelve la respuesta de archivo
        return new BinaryFileResponse($filePath);
    }
}
