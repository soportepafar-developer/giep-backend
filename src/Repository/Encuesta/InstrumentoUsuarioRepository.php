<?php

namespace App\Repository\Encuesta;

use App\Entity\Encuesta\InstrumentoUsuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;
use App\Dto\Encuesta\InstrumentoCapturaUsersOutPutDto;
use App\Entity\Encuesta\InstrumentoCaptura;
use App\Entity\Proyecto\Empresa;
/**
 * @method InstrumentoUsuario|null find($id, $lockMode = null, $lockVersion = null)
 * @method InstrumentoUsuario|null findOneBy(array $criteria, array $orderBy = null)
 * @method InstrumentoUsuario[]    findAll()
 * @method InstrumentoUsuario[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstrumentoUsuarioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, InstrumentoUsuario::class);
    }

    // /**
    //  * @return InstrumentoUsuario[] Returns an array of InstrumentoUsuario objects
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
    public function findOneBySomeField($value): ?InstrumentoUsuario
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


    public function getInstrumentosByUsuario($data)
    {
        $entityManagerDefault = $this->getEntityManager();
        $empresa= $entityManagerDefault->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());

        if ($data['page'] != 0 && $data['page'] != 1) {
            $offset = ($data['page'] - 1) * $data['rowByPage'];
        }
       

        if ($data['page'] != 0 && $data['page'] != 1) {
            $offset = ($data['page'] - 1) * $data['rowByPage'];
        }

        $query= $this->createQueryBuilder('a');

        $query->select("b,a,f")
        ->innerJoin('a.IdInstrumento', 'b')
        ->innerJoin('a.idUser', 'f');

        if($data['fechaDesde']!=null && $data['fechaHasta']==null){
            $fechaDesde=$data['fechaDesde']." 00:00:00";
            $fechaHasta=$data['fechaHasta']." 23:59:59";
            
            $query->where("a.fechaAsignacion >= '".$fechaDesde."'");

            if($data['word']!=null){
                $query->andWhere("(f.primerNombre like '%".$data['word']."%' or f.primerApellido like '%".$data['word']."%' or f.email like '%".$data['word']."%' or f.numeroDocumento like '%".$data['word']."%' ) and a.idempresa = ".$empresa->getId()." ");
            }else{
                $query->andwhere("a.idempresa = ".$empresa->getId());
            }
            if($data['instrumento']!=null){
                $query->andWhere("b.id =".$data['instrumento']);
            }
        
        }else if($data['fechaDesde']!=null && $data['fechaHasta']!=null){
            $fechaDesde=$data['fechaDesde']." 00:00:00";
            $fechaHasta=$data['fechaHasta']." 23:59:59";
            
            $query->where("a.fechaAsignacion between '".$fechaDesde."' and '".$fechaHasta."' ");

            if($data['word']!=null){
                $query->andWhere("(f.primerNombre like '%".$data['word']."%' or f.primerApellido like '%".$data['word']."%' or f.email like '%".$data['word']."%' or f.numeroDocumento like '%".$data['word']."%') and a.idempresa = ".$empresa->getId()." ");
            }else{
                $query->andwhere("a.idempresa = ".$empresa->getId());
            }

            if($data['instrumento']!=null){
                $query->andWhere("b.id =".$data['instrumento']);
            }
        }else{
            if($data['word']!=null){
                $query->Where("(f.primerNombre like '%".$data['word']."%' or f.primerApellido like '%".$data['word']."%' or f.email like '%".$data['word']."%' or f.numeroDocumento like '%".$data['word']."%') and a.idempresa = ".$empresa->getId()." ");
                if($data['instrumento']!=null){
                    $query->andWhere("b.id =".$data['instrumento']);
                }    
            }else{

                $query->andwhere("a.idempresa = ".$empresa->getId());

                if($data['instrumento']!=null){
                    $query->andWhere("  b.id =".$data['instrumento']);
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
        $datainstrumentocap=array();

        foreach($paginator as $clave=>$valor){
            $instrumentocapturaDto =new InstrumentoCapturaUsersOutPutDto();
            $instrumentocapturaDto->nombre=  $valor->getIdInstrumento()->getNombre();
            $instrumentocapturaDto->respondida =$valor->getRespondida()==1?"Si":"No";
            $instrumentocapturaDto->primerNombre=$valor->getIdUser()->getPrimerNombre();
            $instrumentocapturaDto->primerApellido=$valor->getIdUser()->getPrimerApellido();
            $instrumentocapturaDto->email=$valor->getIdUser()->getEmail();
            $instrumentocapturaDto->id=$valor->getIdUser()->getId();
            $instrumentocapturaDto->idInstrumento=$valor->getIdInstrumento()->getId();
            $instrumentocapturaDto->fecha=$valor->getFechaAsignacion()!=null? $valor->getFechaAsignacion()->format("Y-m-d"):null;
            $datainstrumentocap[]=$instrumentocapturaDto;                
        }



        // foreach($paginator as $clave=>$valor){
        //     foreach($valor->getInstrumentoUsuarios() as $claveInstrumentoUser=>$valorInstrumentoUser){
        //         $instrumentocapturaDto =new InstrumentoCapturaUsersOutPutDto();
        //         $instrumentocapturaDto->nombre=  $valor->getNombre();
        //         $instrumentocapturaDto->respondida =$valorInstrumentoUser->getRespondida()==1?"Si":"No";
        //         $instrumentocapturaDto->primerNombre=$valorInstrumentoUser->getIdUser()->getPrimerNombre();
        //         $instrumentocapturaDto->primerApellido=$valorInstrumentoUser->getIdUser()->getPrimerApellido();
        //         $instrumentocapturaDto->email=$valorInstrumentoUser->getIdUser()->getEmail();
        //         $instrumentocapturaDto->id=$valor->getId();
        //         $instrumentocapturaDto->fecha=$valorInstrumentoUser->getFechaInicio()!=null? $valorInstrumentoUser->getFechaInicio()->format("Y-m-d"):null;
        //         if($paginatorTotalCount<$data['rowByPage']){
        //             $datainstrumentocap[]=$instrumentocapturaDto;
        //         }
        //     }
        // }
        return array("count"=>count($paginatorTotalCount),"data"=>$datainstrumentocap);

    }



    public function getInstrumentosByUsuarioPage($data)
    {

        

        if ($data['page'] != 0 && $data['page'] != 1) {
            $offset = ($data['page'] - 1) * $data['rowByPage'];
        }
       

        if ($data['page'] != 0 && $data['page'] != 1) {
            $offset = ($data['page'] - 1) * $data['rowByPage'];
        }

        $query= $this->createQueryBuilder('a');

        $query->select("b,a,f")
        ->innerJoin('a.IdInstrumento', 'b')
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
            $query->andWhere("  a.paisId =".$data['paisId']);     
        } 
        if($data['estadoId']!=null){
            $query->andWhere("  a.estadoId =".$data['estadoId']);     
        } 

        $query->orderBy('a.id', 'ASC');   
        $query->getQuery();
      
      
        $paginatorTotalCount = new Paginator($query);	
        $paginator = new Paginator($query);	
    	$paginator->getQuery()	
      	->setFirstResult($data['rowByPage'] *($data['page']-1))	
        ->setMaxResults($data['rowByPage']);
        $datainstrumentocap=array();

        foreach($paginator as $clave=>$valor){
            $instrumentocapturaDto =new InstrumentoCapturaUsersOutPutDto();
            $instrumentocapturaDto->nombre=  $valor->getIdUser()->getPrimerNombre() ." " .$valor->getIdUser()->getPrimerApellido();
            $instrumentocapturaDto->respondida =$valor->getRespondida();
            $instrumentocapturaDto->email=$valor->getIdUser()->getEmail();
            $instrumentocapturaDto->id=$valor->getIdUser()->getId();
            $instrumentocapturaDto->idInstrumento=$valor->getIdInstrumento()->getId();
            $instrumentocapturaDto->estado=$valor->getEstadoId()!=null? $valor->getEstadoId()->getNombre():null;
            $instrumentocapturaDto->pais=$valor->getPaisId()!=null?$valor->getPaisId()->getNombre():null;

            $datainstrumentocap[]=$instrumentocapturaDto;                
        }

        return array("count"=>count($paginatorTotalCount),"data"=>$datainstrumentocap);

    }
    
    /**
     * Desvincular usuarios de instrumento Captura.
    */
    public function getInstrumentosDesvincularByUsuario($data): JsonResponse  {
        foreach($data["users"] as $valorUser){
            //Desvincular usuarios del instrumento*************************** 
            $sql = "DELETE FROM instrumento_usuario where id_user_id=".$valorUser." and  id_instrumento_id=".$data["idinstrumento"]." ";
            $conn = $this->getEntityManager()->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
       }
       return new JsonResponse(['msg'=>'Exito Usuarios Desvinculado'],200);
   }
}