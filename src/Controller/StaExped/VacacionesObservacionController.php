<?php

namespace App\Controller\StaExped;

use App\Entity\StaExped\VacacionesObservacion;
use App\Repository\StaExped\VacacionesObservacionRepository;
use App\Dto\StaExped\VacacionesObservacionOutPutDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Helper;
use Symfony\Component\Validator\Constraints\Json;

class VacacionesObservacionController extends AbstractController
{

 /**
     *  Get an Datos Especialidades Areas by Id. 
     * @Route("/api/staexped/vacacionesobservacion/{Id}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns vacacionesobservacion",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=VacacionesObservacionOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="StaExped Vacaciones Observacion")
     * @Security(name="Bearer")
     */
    public function getVacacionesobservacionByCi($Id,VacacionesObservacionRepository $datosvacacionesobservacionRepository): JsonResponse
    {
        $em = $this->getDoctrine()->getManager('customer');
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $data = $datosvacacionesobservacionRepository
        ->getVacacionesobservacionByCi($Id,$em);
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }



/**
        * @Route("/api/staexped/vacacionesobservacion", methods={"POST"})
        * @OA\Post(
         * summary="Create Vacaciones Observacion",
         * description="Create Vacaciones Observacion",
         * operationId="vacacionesobservacion",
         * tags={"StaExped Vacaciones Observacion"},
         * @OA\RequestBody(
         *    required=true,
         *    description="parametro",
         *    @OA\JsonContent(
         *       required={"page"},
         *       @OA\Property(property="id_datos_personales", type="integer", format="integer", example="4"),
         *       @OA\Property(property="observacion", type="string", format="string", example="Vacaciones vencidas sin difrutes"), 
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
            $repository = $this->getDoctrine()->getRepository(VacacionesObservacion::class);
            return $repository->post($data,$validator,$helper,$em); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


     /**
        * @Route("/api/staexped/vacacionesobservacion/actualizar/{id}", methods={"PUT"})
        * @OA\Put(
         * summary="Put Vacaciones Observacion",
         * description="Update Vacaciones Observacion",
         * operationId="updateVacacionesObservacion",
         * tags={"StaExped Vacaciones Observacion"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Vacaciones Observacion",
         *    @OA\JsonContent(
         *       required={"nombre"},
         *       @OA\Property(property="observacion", type="string", format="string", example="Vacaciones vencidas sin difrutes Actualizados"), 
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
    public function put($id,Request $request,ValidatorInterface $validator,Helper $helper): JsonResponse
    {
        try {
            $em = $this->getDoctrine()->getManager('customer');

            $data = json_decode($request->getContent(),true);
            $repository = $this->getDoctrine()->getRepository(VacacionesObservacion::class);
            return $repository->put($data,$id,$validator,$helper,$em); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }




}
