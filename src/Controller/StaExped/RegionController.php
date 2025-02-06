<?php

namespace App\Controller\StaExped;

use App\Entity\StaExped\Region;
use App\Form\StaExped\RegionType;
use App\Repository\StaExped\RegionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Dto\StaExped\RegionOutPutDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Helper;
use Symfony\Component\Validator\Constraints\Json;



class RegionController extends AbstractController
{

/**
     *  Get list Region. 
     * @Route("/api/staexped/region/List", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns Region",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=RegionOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="StaExped Region")
     * @Security(name="Bearer")
     */
    public function findList(Request $request,RegionRepository $repository): JsonResponse
    {
        $em = $this->getDoctrine()->getManager('customer');
        $data = $repository
        ->findList($em);
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }


}
