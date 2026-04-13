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

use App\Repository\DocumentoDigital\SubSerieRepository;
use App\Dto\DocumentoDigital\SubSerieOutPutDto;

class SubSerieController extends AbstractController
{
    /**
     *  Get listid archivodigitalsubserie. 
     * @Route("/api/archivodigitalsubserie/listid/{id}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns Sub Serie",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=SubSerieOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="Archivos Digital")
     * @Security(name="Bearer")
     */
    public function findListid($id,Request $request,SubSerieRepository $repository): JsonResponse
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
        * @Route("/api/archivodigitalsubserie/actualizarsubserie", methods={"POST"})
        * @OA\Post(
         * summary="User Actualizar Sub Serie",
         * description="User Actualizar Sub Serie",
         * operationId="actualizarsubserie",
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
    */    public function findSubSerieActualizar(Request $request,SubSerieRepository $repository): JsonResponse
    {
        $em = $this->getDoctrine()->getManager('documentodigital');
        $param = json_decode($request->getContent(),true);
        return $repository->findSubSerieActualizar($em);
    }



}
