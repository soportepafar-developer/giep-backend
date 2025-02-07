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

use App\Repository\DocumentoDigital\EstructuraOrganizativaRepository;
use App\Dto\DocumentoDigital\EstructuraOrganizativaOutPutDto;

class EstructuraOrganizativaController extends AbstractController
{

  /**
     *  Get list archivodigitalestructuraorganizativa. 
     * @Route("/api/archivodigitalestructuraorganizativa/list", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns Estructura Organizativa",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=EstructuraOrganizativaOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="Archivos Digital")
     * @Security(name="Bearer")
     */
    public function findList(Request $request,EstructuraOrganizativaRepository $repository): JsonResponse
    {
        $em = $this->getDoctrine()->getManager('documentodigital');
        $data = $repository
        ->findList($em);
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }


    /**
     *  Get listid archivodigitalestructuraorganizativa. 
     * @Route("/api/archivodigitalestructuraorganizativa/listid/{id}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns Estructura Organizativa",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=EstructuraOrganizativaOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="Archivos Digital")
     * @Security(name="Bearer")
     */
    public function findListid($id,Request $request,EstructuraOrganizativaRepository $repository): JsonResponse
    {
        $em = $this->getDoctrine()->getManager('documentodigital');
        $data = $repository
        ->findListid($id,$em);
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }

}
