<?php

namespace App\Repository\Evaluacion;
use App\Entity\Evaluacion\RespuestaEvaluacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


use App\Dto\Evaluacion\PreguntaOutPutDto;
use App\Entity\Evaluacion\PreguntaEvaluacion;
use App\Entity\Evaluacion\TipoCategoriaEvaluacion;
use App\Entity\Evaluacion\TipoInputEvaluacion;
use App\Entity\Evaluacion\Evaluacion;
use App\Entity\Evaluacion\OpcionesEvaluacion;
use App\Entity\Evaluacion\EvaluacionUsuario;
use App\Entity\User;
use App\Entity\Status;
use App\Entity\Proyecto\Empresa;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;
//use Proxies\__CG__\App\Entity\Evaluacion\Evaluacion as Evaluacion;

/**
 * @method RespuestaEvaluacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method RespuestaEvaluacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method RespuestaEvaluacion[]    findAll()
 * @method RespuestaEvaluacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RespuestaEvaluacionRepository extends ServiceEntityRepository
{
    private $security;
    private $totalCount=0;
    private $muestra=0;

    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, RespuestaEvaluacion::class);
    }


    /**
     * Create Respuesta.
     */
    public function post($data,$validator,$helper): JsonResponse  {
        $entityManager = $this->getEntityManager();
        $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
        foreach ($data["users"] as $user) {
           $userEvaluation= $entityManager->getRepository(User::class)->find($user["userId"]);
           $evaluacionUsuario = $entityManager->getRepository(EvaluacionUsuario::class)->findOneBy(array('IdEvaluacion' => $data["id"],'idUser' => $user["userId"]));
           if($evaluacionUsuario!=null){
                if($evaluacionUsuario->getRespondida()==0){
                        foreach ($user["questions"] as $valueQuestion) {
                            $idQuestion=$valueQuestion["id"];
                            foreach ($valueQuestion["response"] as $key=>$value) {
                                $entity = new RespuestaEvaluacion();
                                $respuestaEvaluacion = $entityManager->getRepository(RespuestaEvaluacion::class)->findOneBy(array('idUser' => $user["userId"],'idPregunta' => $idQuestion));
                                if($respuestaEvaluacion ==null){        
                                    $entityPreguntaEvaluacion = $entityManager->getRepository(PreguntaEvaluacion::class)->find($idQuestion);
                                    if($value["idOption"]!=null){
                                        $entityOpcion = $entityManager->getRepository(OpcionesEvaluacion::class)->find($value["idOption"]);
                                        if($entityOpcion!=null){
                                            $entity->setIdOpcion($entityOpcion);                                
                                        }
                                        if($value["text"]!=null){
                                            $entity->setEntradaTexto($value["text"]);
                                        }
                                    }
                                    $entity->setIdUser($userEvaluation);
                                    $entity->setIdPregunta($entityPreguntaEvaluacion);
                                    $entity->setCreateBy($currentUser->getUserName());
                                    $entity->setUpdateBy($currentUser->getUserName());                
                                    $errors = $validator->validate($entity);    
                                    if($errors->count() > 0){
                                        foreach ($errors as $violation) {
                                            $messages[$violation->getPropertyPath()][] = $violation->getMessage();
                                        }
                                        return new JsonResponse($messages,409);
                                    }else{
                                        $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                                        if($empresa)
                                            $entity->setIdempresa($empresa); 
                                        $entityManager->persist($entity);
                                        $entityManager->flush();
                                    }
                                }
                            }
                        } 	              
        
                    }
            }
            if($evaluacionUsuario!=null){
                $evaluacionUsuario->setRespondida(1);
                $fechaFinal= new \Datetime(date("Y-m-d H:i:s") );
                $evaluacionUsuario->setFechaFin($fechaFinal);
                $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                if($empresa)
                  $evaluacionUsuario->setIdempresa($empresa); 
                $entityManager->persist($evaluacionUsuario);
                $entityManager->flush();                
            }    

   
        }
 
        return new JsonResponse(['msg'=>'Registro Creado'],200);
             
    }
}
