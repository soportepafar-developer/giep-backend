<?php

namespace App\Controller\StaExped;

use App\Entity\StaExped\TipoMotivoPermiso;
use App\Form\StaExped\TipoMotivoPermisoType;
use App\Repository\StaExped\TipoMotivoPermisoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Dto\StaExped\TipoMotivoPermisoOutPutDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Helper;
use Symfony\Component\Validator\Constraints\Json;

class TipoMotivoPermisoController extends AbstractController
{

/**
     *  Get list Tipo Motivo Permisos. 
     * @Route("/api/staexped/tipomotivopermisos/List", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns Motivo Permisos",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=TipoMotivoPermisoOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="StaExped Tipo Motivo Permisos")
     * @Security(name="Bearer")
     */
    public function findList(Request $request,TipoMotivoPermisoRepository $repository): JsonResponse
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
        * @Route("/api/staexped/tipomotivopermisos", methods={"POST"})
        * @OA\Post(
         * summary="Create Tipo Motivo Permisos",
         * description="Create Tipo Motivo PermisosProfesion",
         * operationId="tipomotivopermisos",
         * tags={"StaExped Tipo Motivo Permisos"},
         * @OA\RequestBody(
         *    required=true,
         *    description="parametro",
         *    @OA\JsonContent(
         *       required={"page"},
         *       @OA\Property(property="permiso", type="string", format="string", example="Estudios"), 
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
            $repository = $this->getDoctrine()->getRepository(TipoMotivoPermiso::class);
            return $repository->post($data,$validator,$helper,$em); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


}
