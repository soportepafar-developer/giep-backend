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

use App\Repository\DocumentoDigital\TipoAlmacenRepository;
use App\Dto\DocumentoDigital\TipoAlmacenOutPutDto;
use App\Entity\DocumentoDigital\TipoAlmacen;


class TipoAlmacenController extends AbstractController
{
    /**
     *  Get list archivodigitaltipoalmacen. 
     * @Route("/api/archivodigitaltipoalmacen/list", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns Tipo Almacen",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=TipoAlmacenOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="Archivos Digital")
     * @Security(name="Bearer")
     */
    public function findList(Request $request,TipoAlmacenRepository $repository): JsonResponse
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
     *  Get listid archivodigitaltipoalmacen. 
     * @Route("/api/archivodigitaltipoalmacen/listid/{id}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns Tipo Almacen",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=TipoAlmacenOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="Archivos Digital")
     * @Security(name="Bearer")
     */
    public function findListid($id,Request $request,TipoAlmacenRepository $repository): JsonResponse
    {
        $em = $this->getDoctrine()->getManager('documentodigital');
        $data = $repository
        ->findListid($id,$em);
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }


    /**
        * @Route("/api/archivodigitaltipoalmacen/tipoalmacen", methods={"POST"})
        * @OA\Post(
         * summary="Create Tipo Almacen",
         * description="Create Tipo Almacen",
         * operationId="tipoalmacen",
         * tags={"Archivos Digital"},
         * @OA\RequestBody(
         *    required=true,
         *    description="parametro",
         *    @OA\JsonContent(
         *       required={"page"},
         *       @OA\Property(property="tiporeposo", type="string", format="string", example="Operación"), 
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
    */    

    public function post(Request $request,ValidatorInterface $validator,Helper $helper): Response
    {   
        try {
            $em = $this->getDoctrine()->getManager('documentodigital');
            $data = json_decode($request->getContent(),true);
            $repository = $this->getDoctrine()->getRepository(TipoAlmacen::class);
            return $repository->post($data,$validator,$helper,$em); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }

}
