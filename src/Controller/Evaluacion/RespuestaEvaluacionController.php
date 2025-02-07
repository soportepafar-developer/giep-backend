<?php

namespace App\Controller\Evaluacion;
use App\Dto\Evaluacion\ResultadosEvaluacionDto;
use App\Entity\Evaluacion\RespuestaEvaluacion;
use App\Repository\Evaluacion\RespuestaEvaluacionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Evaluacion\Pregunta;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Helper;
use Symfony\Component\Validator\Constraints\Json;
use Nelmio\ApiDocBundle\Annotation\Security;

class RespuestaEvaluacionController extends AbstractController
{

      /**
        * @Route("/api/evaluacion/respuesta", methods={"POST"})
        * @OA\Post(
         * summary="Create Respuesta",
         * description="Create Respuesta",
         * operationId="createRespuestaevaluacion",
         * tags={"Respuesta Evaluacion"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Respuesta",
         *    @OA\JsonContent(
         *       required={"response"},
         *       @OA\Property(property="id", type="integer", example="1"),
         *       @OA\Property(property="users", type="array", @OA\Items(type="array",@OA\Items()), example={"id": 10,"users":{{"userId": 1,"questions": {{"id": 65,"response": {{"idOption": "4","text": null}}},{"id": 66,"response":{{"idOption": "3","text": null}}}}}}}),
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
    public function post(Request $request,ValidatorInterface $validator,Helper $helper): JsonResponse
    {   
        try {
            $data = json_decode($request->getContent(),true);
            $repository = $this->getDoctrine()->getRepository(RespuestaEvaluacion::class);
            return $repository->post($data,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


    /**
     *  Get Resultados. 
     * @Route("/api/evaluacion/resultados/{id}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=ResultadosEvaluacionDto::class))
     *     )
     * )
     * @OA\Tag(name="Respuesta Evaluacion")
     * @Security(name="Bearer")
     */
    public function resultados($id,RespuestaRepository $repository): JsonResponse
    {
        $data = $repository
        ->resultados($id);
        if (!$data) {
            return new JsonResponse(array('usuario'=>$data),200);  
        }   
         return new JsonResponse($data,200);  
    }

     /**
     *  Get Resultados. 
     * @Route("/api/evaluacion/resultados/sincategoria/{id}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=ResultadosEvaluacionDto::class))
     *     )
     * )
     * @OA\Tag(name="Respuesta Evaluacion")
     * @Security(name="Bearer")
     */
    public function resultadosSinCategorizacion($id,RespuestaRepository $repository): JsonResponse
    {
        $data = $repository
        ->resultadosSinCategorizacion($id);
        if (!$data) {
            return new JsonResponse(array('usuario'=>$data),200);  
        }   
         return new JsonResponse($data,200);  
    }

    
     /**
     *  Get Resultados. 
     * @Route("/api/evaluacion/{id}/resultados/contador", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=ResultadosEvaluacionDto::class))
     *     )
     * )
     * @OA\Tag(name="Respuesta Evaluacion")
     * @Security(name="Bearer")
     */
    public function resultadosContadorDeOpciones($id,RespuestaRepository $repository): JsonResponse
    {
        $data = $repository
        ->resultadosContadorDeOpciones($id);
        if (!$data) {
             return new JsonResponse(array('usuario'=>$data),200);  
        }   
          return new JsonResponse($data,200);  
          
        
    }

    
    /**
     *  Get Resultados. 
     * @Route("/api/evaluacion/resultados/instrumento/{id}", methods={"POST"})
     * @OA\RequestBody(
    *    required=true,
    *    description="parametro",
    *    @OA\JsonContent(
    *       required={"page"},
    *       @OA\Property(property="page", type="integer", format="integer", example="1"),
    *       @OA\Property(property="rowByPage", type="integer", format="integer", example="1"),
    *       @OA\Property(property="word", type="integer", format="integer", example="1"),
    *       @OA\Property(property="byuser", type="integer", format="integer", example="1"),
    *       @OA\Property(property="desde", type="datetime", format="datetime", example="2022-01-01"),
    *       @OA\Property(property="hasta", type="datetime", format="datetime", example="2022-12-31"),
    *       @OA\Property(property="sexo", type="string", format="string", example="f"),
    *       @OA\Property(property="pais", type="integer", format="integer", example="1"),
    *       @OA\Property(property="estado", type="integer", format="integer", example="1"),
    *       @OA\Property(property="ciudad", type="integer", format="integer", example="1"),

    *    ),
    * ),
     * @OA\Response(
     *     response=200,
     *     description="Returns user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=ResultadosEvaluacionDto::class))
     *     )
     * )
     * @OA\Tag(name="Respuesta Evaluacion")
     * @Security(name="Bearer")
     */
    public function resultadosGeneral($id,RespuestaRepository $repository,Request $request): JsonResponse
    {
        $param = json_decode($request->getContent(),true);
        $data = $repository
        ->generalResult($param,$id);
        if (!$data) {
            return new JsonResponse(array('usuario'=>$data),200);  
        }   
         return new JsonResponse($data,200);  
    }


     /**
     *  Get Resultados. 
     * @Route("/api/evaluacion/{id}/resultados/cagos/individuales", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=ResultadosEvaluacionDto::class))
     *     )
     * )
     * @OA\Tag(name="Respuesta Evaluacion")
     * @Security(name="Bearer")
     */
    public function resultadosIndividualesPorCargosSinCategorizacion($id,RespuestaRepository $repository): JsonResponse
    {
        $data = $repository
        ->resultadosIndividualesPorCargosSinCategorizacion($id);
        if (!$data) {
             return new JsonResponse(array('usuario'=>$data),200);  
        }   
          return new JsonResponse($data,200);  
          
        
    }

     /**
     *  Get Resultados. 
     * @Route("/api/evaluacion/{id}/resultados/cagos/individuales/categorizado", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=ResultadosEvaluacionDto::class))
     *     )
     * )
     * @OA\Tag(name="Respuesta Evaluacion")
     * @Security(name="Bearer")
     */
    public function resultadosIndividualesPorCargosConCategorizacion($id,RespuestaRepository $repository): JsonResponse
    {
        $data = $repository
        ->resultadosIndividualesPorCargosConCategorizacion($id);
        if (!$data) {
             return new JsonResponse(array('usuario'=>$data),200);  
        }   
          return new JsonResponse($data,200);  
          
        
    }
     /**
     *  Get Resultados. 
     * @Route("/api/evaluacion/{id}/resultados/cagos/dependendencia/{idpedendencia}/categorizado", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=ResultadosEvaluacionDto::class))
     *     )
     * )
     * @OA\Tag(name="Respuesta Evaluacion")
     * @Security(name="Bearer")
     */
    public function resultadosPorCargosConCategorizacionyDependecia($id,$idpedendencia,RespuestaRepository $repository): JsonResponse
    {
        $data = $repository
        ->resultadosPorCargosConCategorizacionyDependecia($id,$idpedendencia);
        if (!$data) {
             return new JsonResponse(array('usuario'=>$data),200);  
        }   
          return new JsonResponse($data,200);  
                
    }

    


}
