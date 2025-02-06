<?php

namespace App\Controller\StaExped;

use App\Entity\StaExped\Area;
use App\Form\StaExped\AreaType;
use App\Repository\StaExped\DocumentosIngresosRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


use App\Dto\StaExped\AreaOutPutDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Helper;
use Symfony\Component\Validator\Constraints\Json;

class ReporteController extends AbstractController
{

/**
     *  Get Report. 
     * @Route("/api/staexped/reportes", methods={"POST"}),
             * @OA\RequestBody(
         *    required=true,
         *    description="parametro",
         *    @OA\JsonContent(
         *       required={"page"},
         *       @OA\Property(property="page", type="integer", format="integer", example="1"),
         *       @OA\Property(property="rowByPage", type="integer", format="integer", example="1"),
         *       @OA\Property(property="departament", type="integer", format="integer", example="1"),
         *       @OA\Property(property="startDate", type="string", format="integer", example="2023-01-01"),
         *       @OA\Property(property="endDate", type="string", format="integer", example="2023-12-31"),

         *    ),
         * ),
     * @OA\Response(
     *     response=200,
     *     description="Returns Area",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=AreaOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="StaExped Reportes")
     * @Security(name="Bearer")
     */
    public function report(Request $request,DocumentosIngresosRepository $repository): JsonResponse
    {
        $em = $this->getDoctrine()->getManager('customer');
        $param = json_decode($request->getContent(),true);
        $data = $repository
        ->getReporte($em,$param);
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }

}
