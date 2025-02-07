<?php

namespace App\Controller\DocumentoDigital;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Helper;
use Symfony\Component\Validator\Constraints\Json;

use App\Repository\DocumentoDigital\ControlArchivoDigitalRepository;
//use App\Dto\DocumentoDigital\ContenidoCajaOutPutDto;

class ControlArchivoDigitalController extends AbstractController
{

    /**
        * Get an Control Archivos Digital by Id.
        * @Route("/api/controlarchivodigital/listid/{id}", methods={"GET"})
        * @OA\Post(
         * summary="Control Archivos Digital List",
         * description="Control Archivos Digital List",
         * operationId="ControlArchivoDigitallist",
         * tags={"Archivos Digital"},
         * @OA\RequestBody(
         *    required=true,
         *    description="parametro",
         *    @OA\JsonContent(
         *       required={"page"},
         *       @OA\Property(property="page", type="integer", format="integer", example="1"),
         *       @OA\Property(property="rowByPage", type="integer", format="integer", example="1"),
         *       @OA\Property(property="word", type="integer", format="integer", example="1"),
         *    ),
         * ),
         * @OA\Response(
         *    response=422,
         *    description="Wrong credentials response",
         *    @OA\JsonContent(
         *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
         *        )
         *     )
         * )
         * @OA\Tag(name="Archivos Digital")
         * @Security(name="Bearer")
    */   
    public function findById($id,Request $request,ControlArchivoDigitalRepository $controlarchivosdigitalRepository): JsonResponse
    {
        $em = $this->getDoctrine()->getManager('documentodigital');
        $data = $controlarchivosdigitalRepository->getArchivosById($id,$em);
        //$data = $archivosRepository->postextensionpruebas("docx");
        return $data;  
    }   

}
