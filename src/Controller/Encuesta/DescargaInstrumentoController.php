<?php

namespace App\Controller\Encuesta;
use App\Dto\Encuesta\ResultadosOutPutDto;
use App\Entity\Encuesta\Respuesta;
use App\Form\Encuesta\RespuestaType;
use App\Repository\Encuesta\DescargaInstrumentoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Encuesta\Pregunta;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Helper;
use Symfony\Component\Validator\Constraints\Json;
use Nelmio\ApiDocBundle\Annotation\Security;

class DescargaInstrumentoController extends AbstractController
{


    /**
     *  Get Resultados. 
     * @Route("/api/encuesta/descarga/resultados/{id}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=ResultadosOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="Respuestas")
     * @Security(name="Bearer")
     */
    public function resultados($id,DescargaInstrumentoRepository $repository): JsonResponse
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
     * @Route("/api/encuesta/descarga/resultados/sincategoria/{id}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=ResultadosOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="Respuestas")
     * @Security(name="Bearer")
     */
    public function resultadosSinCategorizacion($id,DescargaInstrumentoRepository $repository): JsonResponse
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
     * @Route("/api/encuesta/descarga/{id}/resultados/contador", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=ResultadosOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="Respuestas")
     * @Security(name="Bearer")
     */
    public function resultadosContadorDeOpciones($id,DescargaInstrumentoRepository $repository): JsonResponse
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
     * @Route("/api/encuesta/descarga/resultados/instrumento/{id}", methods={"POST"})
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
     *        @OA\Items(ref=@Model(type=ResultadosOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="Respuestas")
     * @Security(name="Bearer")
     */
    public function resultadosGeneral($id,DescargaInstrumentoRepository $repository,Request $request): Response
    {
        $param = json_decode($request->getContent(),true);
        $data = $repository
        ->generalResult($param,$id);
        if (!$data) {
            return new Response($data,200);  
        }   
         return new Response($data,200);  
    }


     /**
     *  Get Resultados. 
     * @Route("/api/encuesta/descarga/{id}/resultados/cagos/individuales", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=ResultadosOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="Respuestas")
     * @Security(name="Bearer")
     */
    public function resultadosIndividualesPorCargosSinCategorizacion($id,DescargaInstrumentoRepository $repository): JsonResponse
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
     * @Route("/api/encuesta/descarga/{id}/resultados/cagos/individuales/categorizado", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=ResultadosOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="Respuestas")
     * @Security(name="Bearer")
     */
    public function resultadosIndividualesPorCargosConCategorizacion($id,DescargaInstrumentoRepository $repository): JsonResponse
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
     * @Route("/api/encuesta/descarga/{id}/resultados/cagos/dependendencia/{idpedendencia}/categorizado", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=ResultadosOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="Respuestas")
     * @Security(name="Bearer")
     */
    public function resultadosPorCargosConCategorizacionyDependecia($id,$idpedendencia,DescargaInstrumentoRepository $repository): JsonResponse
    {
        $data = $repository
        ->resultadosPorCargosConCategorizacionyDependecia($id,$idpedendencia);
        if (!$data) {
             return new JsonResponse(array('usuario'=>$data),200);  
        }   
          return new JsonResponse($data,200);  
                
    }

    


}
