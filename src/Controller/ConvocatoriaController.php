<?php

namespace App\Controller;

use App\Entity\Convocatoria;
use App\Entity\Grupo;
use App\Entity\ItemBaremableConvocatoria;
use App\Entity\Solicitud;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\ConvocatoriaRepository;
use App\Service\EmailService;

class ConvocatoriaController extends AbstractController
{
    private $entityManager;
    private $serializer;
    private $convocatoriaRepository;
    private $emailService;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer, ConvocatoriaRepository $convocatoriaRepository, EmailService $emailService)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->convocatoriaRepository = $convocatoriaRepository;
        $this->emailService = $emailService;
    }

    // LISTADOS DE CONVOCATORIAS
    #[Route('/api/listaConvocatorias', methods: ['GET'])]
    public function listadoConvocatoria(Request $request): JsonResponse|Response
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

        $grupoRepository = $this->entityManager->getRepository(Grupo::class);
        $grupos = $grupoRepository->findAll();

        if ($user) {
            $convocatorias = $this->convocatoriaRepository->findAll();

            $convocatoriasData = $this->serializer->serialize($convocatorias, 'json', ['groups' => ['convocatoria:list', 'itemBaremable:list']]);
            $gruposData = $this->serializer->serialize($grupos, 'json');

            $responseData = [
                'convocatorias' => json_decode($convocatoriasData, true),
                'grupos' => json_decode($gruposData, true),
            ];

            return new JsonResponse($responseData, JsonResponse::HTTP_OK);
        } else {
            return new JsonResponse(['message' => 'Usuario no encontrado'], JsonResponse::HTTP_NOT_FOUND);
        }
    }

    //CREAR CONVOCATORIA
    #[Route('/api/crearConvocatoria', methods: ['POST'])]
    public function crearConvocatoria(Request $request): JsonResponse
    {
        $titulo = $request->request->get('titulo');
        $tipoMovilidad = $request->request->get('tipoMovilidad');
        $numMovilidades = $request->request->get('numMovilidades');
        $paises = $request->request->get('paises');
        $fechaInicio = $request->request->get('fechaInicio');
        $fechaFin = $request->request->get('fechaFin');
        $fechaInscripcionInicio = $request->request->get('fechaInscripcionInicio');
        $fechaInscripcionFin = $request->request->get('fechaInscripcionFin');
        $fechaListaProvisional = $request->request->get('fechaListaProvisional');
        $fechaApelacionesInicio = $request->request->get('fechaApelacionesInicio');
        $fechaApelacionesFin = $request->request->get('fechaApelacionesFin');
        $idCoordinador = $request->request->get('idCoordinador');
        $fechaListaFinal = $request->request->get('fechaListaFinal');

        $fechaInicioDate = \DateTime::createFromFormat('d/m/Y', $fechaInicio);
        $fechaFinDate = \DateTime::createFromFormat('d/m/Y', $fechaFin);
        $fechaInscripcionInicioDate = \DateTime::createFromFormat('d/m/Y', $fechaInscripcionInicio);
        $fechaInscripcionFinDate = \DateTime::createFromFormat('d/m/Y', $fechaInscripcionFin);
        $fechaListaProvisionalDate = \DateTime::createFromFormat('d/m/Y', $fechaListaProvisional);
        $fechaApelacionesInicioDate = \DateTime::createFromFormat('d/m/Y', $fechaApelacionesInicio);
        $fechaApelacionesFinDate = \DateTime::createFromFormat('d/m/Y', $fechaApelacionesFin);
        $fechaListaFinalDate = \DateTime::createFromFormat('d/m/Y', $fechaListaFinal);

        $coordinador = $this->entityManager->getRepository(User::class)->find($idCoordinador);

        $convocatoria = new Convocatoria();
        $convocatoria->setTitulo($titulo);
        $convocatoria->setTipoMovilidad($tipoMovilidad);
        $convocatoria->setNumMovilidades($numMovilidades);
        $convocatoria->setPaises(array_map('trim', explode(',', $paises)));
        $convocatoria->setFechaInicio($fechaInicioDate);
        $convocatoria->setFechaFin($fechaFinDate);
        $convocatoria->setFechaInscripcionInicio($fechaInscripcionInicioDate);
        $convocatoria->setFechaInscripcionFin($fechaInscripcionFinDate);
        $convocatoria->setFechaListaProvisional($fechaListaProvisionalDate);
        $convocatoria->setFechaApelacionesInicio($fechaApelacionesInicioDate);
        $convocatoria->setFechaApelacionesFin($fechaApelacionesFinDate);
        $convocatoria->setFechaListaFinal($fechaListaFinalDate);
        $convocatoria->setIdCoordinador($coordinador);
        $convocatoria->setStatus("INSCRIPCIONES");

        $this->convocatoriaRepository->add($convocatoria, true);

        $itemsBaremables = json_decode($request->request->get('itemsBaremables'), true);

        if (count($itemsBaremables) > 0) {
            foreach ($itemsBaremables as $item) {
                $itemObj = new ItemBaremableConvocatoria();

                $itemObj->setNombre($item["nombre"]);
                $itemObj->setObligatorio($item["obligatorio"] == "SI" ? true : false);
                $itemObj->setPresentaUser($item["presenta_user"] == "SI" ? true : false);
                $itemObj->setValorMin($item["valor_min"]);
                $itemObj->setValorMax($item["valor_max"]);
                $itemObj->setIdConvocatoria($convocatoria);

                $this->entityManager->persist($itemObj);
            }
        }

        $this->entityManager->flush();

        return new JsonResponse("created");
    }

    //BORRAR CONVOCATORIA
    #[Route('/api/deleteConvocatoria/{id}', name: 'delete_convocatoria', methods: ['DELETE'])]
    public function deleteConvocatoria(int $id): JsonResponse
    {
        $convocatoria = $this->convocatoriaRepository->find($id);

        $this->convocatoriaRepository->remove($convocatoria, true);

        return new JsonResponse("deleted");
    }

    //INFO CONVOCATORIA POR ID
    #[Route('/api/getConvocatoria/{id}', name: 'getConvocatoria', methods: ['GET'])]
    public function getConvocatoria(int $id): Response
    {
        $convocatoria = $this->convocatoriaRepository->find($id);

        $data = $this->serializer->serialize($convocatoria, 'json', ['groups' => 'convocatoria:list']);

        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    //EDITAR CONVOCATORIA
    #[Route('/api/editConvocatoria', methods: ['POST'])]
    public function editConvocatoria(Request $request): JsonResponse
    {
        $id = $request->request->get('id');
        $titulo = $request->request->get('titulo');
        $tipoMovilidad = $request->request->get('tipoMovilidad');
        $numMovilidades = $request->request->get('numMovilidades');
        $paises = $request->request->get('paises');
        $fechaInicio = $request->request->get('fechaInicio');
        $fechaFin = $request->request->get('fechaFin');
        $fechaInscripcionInicio = $request->request->get('fechaInscripcionInicio');
        $fechaInscripcionFin = $request->request->get('fechaInscripcionFin');
        $fechaListaProvisional = $request->request->get('fechaListaProvisional');
        $fechaApelacionesInicio = $request->request->get('fechaApelacionesInicio');
        $fechaApelacionesFin = $request->request->get('fechaApelacionesFin');
        $fechaListaFinal = $request->request->get('fechaListaFinal');

        // Date parsing
        $fechaInicioDate = \DateTime::createFromFormat('Y-m-d', $fechaInicio);
        $fechaFinDate = \DateTime::createFromFormat('Y-m-d', $fechaFin);
        $fechaInscripcionInicioDate = \DateTime::createFromFormat('Y-m-d', $fechaInscripcionInicio);
        $fechaInscripcionFinDate = \DateTime::createFromFormat('Y-m-d', $fechaInscripcionFin);
        $fechaListaProvisionalDate = \DateTime::createFromFormat('Y-m-d', $fechaListaProvisional);
        $fechaApelacionesInicioDate = \DateTime::createFromFormat('Y-m-d', $fechaApelacionesInicio);
        $fechaApelacionesFinDate = \DateTime::createFromFormat('Y-m-d', $fechaApelacionesFin);
        $fechaListaFinalDate = \DateTime::createFromFormat('Y-m-d', $fechaListaFinal);

        // Find the convocatoria
        $convocatoria = $this->convocatoriaRepository->find($id);

        // Update the convocatoria
        $convocatoria->setTitulo($titulo);
        $convocatoria->setTipoMovilidad($tipoMovilidad);
        $convocatoria->setNumMovilidades($numMovilidades);
        $convocatoria->setPaises(array_map('trim', explode(',', $paises)));
        $convocatoria->setFechaInicio($fechaInicioDate);
        $convocatoria->setFechaFin($fechaFinDate);
        $convocatoria->setFechaInscripcionInicio($fechaInscripcionInicioDate);
        $convocatoria->setFechaInscripcionFin($fechaInscripcionFinDate);
        $convocatoria->setFechaListaProvisional($fechaListaProvisionalDate);
        $convocatoria->setFechaApelacionesInicio($fechaApelacionesInicioDate);
        $convocatoria->setFechaApelacionesFin($fechaApelacionesFinDate);
        $convocatoria->setFechaListaFinal($fechaListaFinalDate);

        $this->convocatoriaRepository->add($convocatoria, true);

        return new JsonResponse("edited");
    }

    //HACER SOLICITUD A UNA CONVOCATORIA
    #[Route('/api/hacerSolicitud/{idGrupo}', methods: ['POST'])]
    public function hacerSolicitud(string $idGrupo, Request $request): JsonResponse
    {
        $token = $request->request->get('token');
        $idConvocatoria = $request->request->get('idConvocatoria');

        $decodedToken = base64_decode($token);
        $tokenParts = explode('|', $decodedToken);
        $email = $tokenParts[1] ?? null;

        $convocatoria = $this->convocatoriaRepository->find($idConvocatoria);
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        $grupo = $this->entityManager->getRepository(Grupo::class)->findOneBy(['clave' => $idGrupo]);

        $existingSolicitud = $this->entityManager->getRepository(Solicitud::class)
            ->findOneBy(['idConvocatoria' => $convocatoria, 'idUser' => $user]);

        if (!$grupo) {
            return new JsonResponse("No has seleccionado un grupo", 400);
        }

        if ($existingSolicitud) {
            return new JsonResponse("Ya existe una solicitud para esta persona y convocatoria", 400);
        }

        if ($grupo && !$existingSolicitud) {
            $solicitud = new Solicitud();
            $solicitud->setIdConvocatoria($convocatoria);
            $solicitud->setIdUser($user);
            $solicitud->setStatus("DONE");
            $solicitud->setIdGrupo($grupo);
            $solicitud->setNota(0);

            $this->entityManager->persist($solicitud);
            $this->entityManager->flush();

            $carpetaDestino = $this->getParameter('kernel.project_dir') . '/itemsBaremables';

            foreach ($convocatoria->getItemsBaremables() as $item) {
                if ($item->isPresentaUser()) {

                    if (isset($_FILES['file_' . $item->getId()])) {
                        $extension = pathinfo($_FILES['file_' . $item->getId()]['name'], PATHINFO_EXTENSION);

                        $nombreArchivo = $solicitud->getId() . '-' . $item->getId() . "." . $extension;

                        move_uploaded_file($_FILES['file_' . $item->getId()]['tmp_name'], $carpetaDestino . '/' . $nombreArchivo);
                    }
                }
            }

        }

        return new JsonResponse("Solicitud creada exitosamente");
        /* $this->emailService->sendEmail(
            "migueltortg@gmail.com", "PRUEBA", "CUERPO"
        ); */
    }
}
