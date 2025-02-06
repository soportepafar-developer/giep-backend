<?php

namespace App\Controller\StaExped;

use App\Entity\StaExped\TipoVacaciones;
use App\Form\StaExped\TipoVacacionesType;
use App\Repository\StaExped\TipoVacacionesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Dto\StaExped\TipoVacacionesOutPutDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Helper;
use Symfony\Component\Validator\Constraints\Json;


class TipoVacacionesController extends AbstractController
{
/**
     *  Get list Tipo Vacaciones. 
     * @Route("/api/staexped/tipovacaciones/List", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns Tipo Vacaciones",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=TipoVacacionesOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="StaExped Tipo Vacaciones")
     * @Security(name="Bearer")
     */
    public function findList(Request $request,TipoVacacionesRepository $repository): JsonResponse
    {
        $em = $this->getDoctrine()->getManager('customer');
        $data = $repository
        ->findList($em);
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }

    /**
        * @Route("/api/staexped/tipovacaciones", methods={"POST"})
        * @OA\Post(
         * summary="Create Tipo Vacaciones",
         * description="Create Tipo Vacaciones",
         * operationId="tipovacaciones",
         * tags={"StaExped Tipo Vacaciones"},
         * @OA\RequestBody(
         *    required=true,
         *    description="parametro",
         *    @OA\JsonContent(
         *       required={"page"},
         *       @OA\Property(property="vacaciones", type="string", format="string", example="Por decreto"), 
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
            $em = $this->getDoctrine()->getManager('customer');
            $data = json_decode($request->getContent(),true);
            $repository = $this->getDoctrine()->getRepository(TipoVacaciones::class);
            return $repository->post($data,$validator,$helper,$em); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


}
