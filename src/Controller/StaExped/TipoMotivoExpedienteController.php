<?php

namespace App\Controller\StaExped;

use App\Entity\StaExped\TipoMotivoExpediente;
use App\Form\StaExped\TipoMotivoExpedienteType;
use App\Repository\StaExped\TipoMotivoExpedienteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Dto\StaExped\TipoMotivoExpedienteOutPutDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Helper;
use Symfony\Component\Validator\Constraints\Json;


class TipoMotivoExpedienteController extends AbstractController
{

    /**
     *  Get list Motivo Expediente. 
     * @Route("/api/staexped/tipomotivoexpediente/List", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns Motivo Expediente",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=TipoMotivoExpedienteOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="StaExped Tipo Motivo Expediente")
     * @Security(name="Bearer")
     */
    public function findList(Request $request,TipoMotivoExpedienteRepository $repository): JsonResponse
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
        * @Route("/api/staexped/tipomotivoexpediente", methods={"POST"})
        * @OA\Post(
         * summary="Create Tipo Motivo Expediente",
         * description="Create Tipo Motivo Expediente",
         * operationId="tipomotivoexpediente",
         * tags={"StaExped Tipo Motivo Expediente"},
         * @OA\RequestBody(
         *    required=true,
         *    description="parametro",
         *    @OA\JsonContent(
         *       required={"page"},
         *       @OA\Property(property="motivoexpediente", type="string", format="string", example="Falta Laboral"), 
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
            $repository = $this->getDoctrine()->getRepository(TipoMotivoExpediente::class);
            return $repository->post($data,$validator,$helper,$em); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


}
