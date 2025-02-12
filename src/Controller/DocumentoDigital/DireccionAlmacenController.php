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

use App\Repository\DocumentoDigital\DireccionAlmacenRepository;
use App\Dto\DocumentoDigital\DireccionAlmacenOutPutDto;
use App\Entity\DocumentoDigital\DireccionAlmacen;

class DireccionAlmacenController extends AbstractController
{

  /**
     *  Get list archivodigitalstatus. 
     * @Route("/api/archivodigitaldireccionalmacen/list", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns Status",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=DireccionAlmacenOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="Archivos Digital")
     * @Security(name="Bearer")
     */
    public function findList(Request $request,DireccionAlmacenRepository $repository): JsonResponse
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
        * @Route("/api/archivodigitaldireccionalmacen/direccionalmacen", methods={"POST"})
        * @OA\Post(
         * summary="Create Direccion Almacen",
         * description="Create Direccion Almacen",
         * operationId="direccionalmacen",
         * tags={"Archivos Digital"},
         * @OA\RequestBody(
         *    required=true,
         *    description="parametro",
         *    @OA\JsonContent(
         *       required={"page"},
         *       @OA\Property(property="direccionzona", type="string", format="string", example="Los Ruices Torre 1 Piso 1 Oficina B101"), 
         *       @OA\Property(property="nombre", type="string", format="string", example="Archivo Los Ruices"), 
         *       @OA\Property(property="telefono", type="string", format="string", example="02128658956-0412345643"), 
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
            $repository = $this->getDoctrine()->getRepository(DireccionAlmacen::class);
            return $repository->post($data,$validator,$helper,$em); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }

}
