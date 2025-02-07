<?php

namespace App\Repository\Evaluacion;

use App\Entity\Evaluacion\EvaluacionUsuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;
use App\Dto\Evaluacion\EvaluacionUsersOutPutDto;
use App\Entity\Evaluacion\Evaluacion;
use App\Dto\Evaluacion\InstrumentoCapturaUsersOutPutDto;
/**
 * @method EvaluacionUsuario|null find($id, $lockMode = null, $lockVersion = null)
 * @method EvaluacionUsuario|null findOneBy(array $criteria, array $orderBy = null)
 * @method EvaluacionUsuario[]    findAll()
 * @method EvaluacionUsuario[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvaluacionUsuarioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EvaluacionUsuario::class);
    }

    // /**
    //  * @return EvaluacionUsuario[] Returns an array of EvaluacionUsuario objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EvaluacionUsuario
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


    public function getEvaluacionByUsuario($data)
    {

        

        if ($data['page'] != 0 && $data['page'] != 1) {
            $offset = ($data['page'] - 1) * $data['rowByPage'];
        }
       

        if ($data['page'] != 0 && $data['page'] != 1) {
            $offset = ($data['page'] - 1) * $data['rowByPage'];
        }

        $query= $this->createQueryBuilder('a');

        $query->select("b,a,f")
        ->innerJoin('a.IdEvaluacion', 'b')
        ->innerJoin('a.idUser', 'f');

        if($data['fechaDesde']!=null && $data['fechaHasta']==null){
            $fechaDesde=$data['fechaDesde']." 00:00:00";
            $fechaHasta=$data['fechaHasta']." 23:59:59";
            
            $query->where("a.fechaAsignacion >= '".$fechaDesde."'");

            if($data['word']!=null){
                $query->andWhere("(f.primerNombre like '%".$data['word']."%' or f.primerApellido like '%".$data['word']."%' or f.email like '%".$data['word']."%' or f.numeroDocumento like '%".$data['word']."%')");
            }
            if($data['evaluacion']!=null){
                $query->andWhere("b.id =".$data['evaluacion']);
            }
        
        }else if($data['fechaDesde']!=null && $data['fechaHasta']!=null){
            $fechaDesde=$data['fechaDesde']." 00:00:00";
            $fechaHasta=$data['fechaHasta']." 23:59:59";
            
            $query->where("a.fechaAsignacion between '".$fechaDesde."' and '".$fechaHasta."' ");

            if($data['word']!=null){
                $query->andWhere("(f.primerNombre like '%".$data['word']."%' or f.primerApellido like '%".$data['word']."%' or f.email like '%".$data['word']."%' or f.numeroDocumento like '%".$data['word']."%')");
            }
            if($data['evaluacion']!=null){
                $query->andWhere("b.id =".$data['evaluacion']);
            }
        }else{
            if($data['word']!=null){
                $query->Where("(f.primerNombre like '%".$data['word']."%' or f.primerApellido like '%".$data['word']."%' or f.email like '%".$data['word']."%' or f.numeroDocumento like '%".$data['word']."%')");
                if($data['evaluacion']!=null){
                    $query->andWhere("b.id =".$data['evaluacion']);
                }    
            }else{
                if($data['evaluacion']!=null){
                    $query->andWhere("  b.id =".$data['evaluacion']);
                }    
            }       
        }

        
        $query->orderBy('a.id', 'ASC');   
        $query->getQuery();
      
      
        $paginatorTotalCount = new Paginator($query);	
        $paginator = new Paginator($query);	
    	$paginator->getQuery()	
      	->setFirstResult($data['rowByPage'] *($data['page']-1))	
        ->setMaxResults($data['rowByPage']);
        $dataevaluacion=array();

        foreach($paginator as $clave=>$valor){
            $evaluacionDto =new EvaluacionUsersOutPutDto();
            $evaluacionDto->nombre=  $valor->getIdEvaluacion()->getNombre();
            $evaluacionDto->respondida =$valor->getRespondida()==1?"Si":"No";
            $evaluacionDto->primerNombre=$valor->getIdUser()->getPrimerNombre();
            $evaluacionDto->primerApellido=$valor->getIdUser()->getPrimerApellido();
            $evaluacionDto->email=$valor->getIdUser()->getEmail();
            $evaluacionDto->id=$valor->getIdUser()->getId();
            $evaluacionDto->idInstrumento=$valor->getIdEvaluacion()->getId();
            $evaluacionDto->fecha=$valor->getFechaAsignacion()!=null? $valor->getFechaAsignacion()->format("Y-m-d"):null;
            $dataevaluacion[]=$evaluacionDto;                
        }



        // foreach($paginator as $clave=>$valor){
        //     foreach($valor->getInstrumentoUsuarios() as $claveInstrumentoUser=>$valorInstrumentoUser){
        //         $evaluacionDto =new InstrumentoCapturaUsersOutPutDto();
        //         $evaluacionDto->nombre=  $valor->getNombre();
        //         $evaluacionDto->respondida =$valorInstrumentoUser->getRespondida()==1?"Si":"No";
        //         $evaluacionDto->primerNombre=$valorInstrumentoUser->getIdUser()->getPrimerNombre();
        //         $evaluacionDto->primerApellido=$valorInstrumentoUser->getIdUser()->getPrimerApellido();
        //         $evaluacionDto->email=$valorInstrumentoUser->getIdUser()->getEmail();
        //         $evaluacionDto->id=$valor->getId();
        //         $evaluacionDto->fecha=$valorInstrumentoUser->getFechaInicio()!=null? $valorInstrumentoUser->getFechaInicio()->format("Y-m-d"):null;
        //         if($paginatorTotalCount<$data['rowByPage']){
        //             $dataevaluacion[]=$evaluacionDto;
        //         }
        //     }
        // }
        return array("count"=>count($paginatorTotalCount),"data"=>$dataevaluacion);

    }



    public function getEvaluacionByUsuarioPage($data)
    {

        

        if ($data['page'] != 0 && $data['page'] != 1) {
            $offset = ($data['page'] - 1) * $data['rowByPage'];
        }
       

        if ($data['page'] != 0 && $data['page'] != 1) {
            $offset = ($data['page'] - 1) * $data['rowByPage'];
        }

        $query= $this->createQueryBuilder('a');

        $query->select("b,a,f")
        ->innerJoin('a.IdEvaluacion', 'b')
        ->innerJoin('a.idUser', 'f');

        $query->Where("1=1");

        if($data['word']!=null){
            $query->andWhere("(f.primerNombre like '%".$data['word']."%' or f.primerApellido like '%".$data['word']."%' or f.email like '%".$data['word']."%' or f.numeroDocumento like '%".$data['word']."%')");
            if($data['idInstrumento']!=null){
                $query->andWhere("b.id =".$data['idInstrumento']);
            }    
        }else{
            if($data['idInstrumento']!=null){
                $query->andWhere("  b.id =".$data['idInstrumento']);
            }    
        }       
        if($data['paisId']!=null){
            $query->andWhere(" a.paisId =".$data['paisId']);     
        } 
        if($data['estadoId']!=null){
            $query->andWhere("  a.estadoId =".$data['estadoId']);     
        } 

        $query->orderBy('a.id', 'ASC');   
        
      
        $paginatorTotalCount = new Paginator($query);	
        $paginator = new Paginator($query);	
    	$paginator->getQuery()	
      	->setFirstResult($data['rowByPage'] *($data['page']-1))	
        ->setMaxResults($data['rowByPage']);
        $dataevaluacion=array();

        foreach($paginator as $clave=>$valor){
            $evaluacionDto =new InstrumentoCapturaUsersOutPutDto();
            $evaluacionDto->nombre=  $valor->getIdUser()->getPrimerNombre() ." " .$valor->getIdUser()->getPrimerApellido();
            $evaluacionDto->respondida =$valor->getRespondida();
            $evaluacionDto->email=$valor->getIdUser()->getEmail();
            $evaluacionDto->id=$valor->getIdUser()->getId();
            $evaluacionDto->idInstrumento=$valor->getIdEvaluacion()->getId();
            $evaluacionDto->estado=$valor->getEstadoId()!=null? $valor->getEstadoId()->getNombre():null;
            $evaluacionDto->pais=$valor->getPaisId()!=null?$valor->getPaisId()->getNombre():null;

            $dataevaluacion[]=$evaluacionDto;                
        }

        return array("count"=>count($paginatorTotalCount),"data"=>$dataevaluacion);

    }
    
    /**
     * Desvincular usuarios de instrumento Evaluacion.
    */
    public function getEvaluacionDesvincularByUsuario($data): JsonResponse  {
        foreach($data["users"] as $valorUser){
            //Desvincular usuarios del instrumento*************************** 
            $sql = "DELETE FROM evaluacion_usuario where id_user_id=".$valorUser." and  id_evaluacion_id=".$data["idinstrumento"]." ";
            $conn = $this->getEntityManager()->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
       }
       return new JsonResponse(['msg'=>'Exito Usuarios Desvinculado'],200);
   }
}
