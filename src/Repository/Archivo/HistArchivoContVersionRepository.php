<?php

namespace App\Repository\Archivo;
use App\Entity\Archivo\Archivos;
use App\Entity\Archivo\HistArchivoContVersion;
use App\Entity\Archivo\TipoOrientacion;
use App\Entity\Archivo\TipoOperaciones;
Use App\Entity\User;

//use App\Archivo\Repository\HistArchivoContVersionRepository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;
use App\Entity\Proyecto\Empresa;

/**
 * @method HistArchivoContVersion|null find($id, $lockMode = null, $lockVersion = null)
 * @method HistArchivoContVersion|null findOneBy(array $criteria, array $orderBy = null)
 * @method HistArchivoContVersion[]    findAll()
 * @method HistArchivoContVersion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistArchivoContVersionRepository extends ServiceEntityRepository
{
    
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, HistArchivoContVersion::class);
    }



        //Buscar el id del archivo y desacargar
        public function getBuscarHistoricoArchivosById($id,$dirserv){
            try {
            $entityManager = $this->getEntityManager();
            $query= $this->createQueryBuilder('a');
            //$query->select("b,a")
            $query->select("a,b,o,p")
            ->innerJoin('a.iduser', 'b')
            ->innerJoin('a.id_orientacion', 'o')
            ->innerJoin('a.id_tipo_operaciones', 'p');
            $query->Where("a.id =".$id);
            $query->orderBy('a.id', 'ASC');   
            $query->getQuery();
            $paginatorTotalCount = new Paginator($query);	
            $paginator = new Paginator($query);	
            $paginator->getQuery();	
            $dataarchivos=array();
            $base64_arch="";
            $urles="";
            foreach($paginator as $clave=>$valor){
                $nomborg = explode("/", $valor->getUrlAlojamiento()); 
                $nomborgf = explode(".", $nomborg[4]);
    
                //$dirserv="/home/pafarco1/public_html/bofficegiepstage/public";
    
                $urles = $dirserv . $valor->getUrlAlojamiento();
                $imgbinary = fread(fopen($urles, "r"), filesize($urles));
    
                $filetype = $nomborgf[1];
                if ($filetype=='docx') {
                    $base64_arch = 'data:@file/vnd.openxmlformats-officedocument.wordprocessingml.document'.';base64,' . base64_encode($imgbinary);
                }else if ($filetype=='doc') {
                    $base64_arch = 'data:@file/msword;base64,' . base64_encode($imgbinary);
                }else{
                    $base64_arch = 'data:@file/'. $filetype .';base64,' . base64_encode($imgbinary);
                }
    
                $opcioHistArchivoContVersion = new HistArchivoContVersion();
                $opcioHistArchivoContVersion->setUrlAlojamiento($valor->getUrlAlojamiento());
                $opcioHistArchivoContVersion->setTamano($valor->getTamano());

                $opcioHistArchivoContVersion->setNombreOriginal($valor->getNombreOriginal());
        
                $opcioHistArchivoContVersion->setFechaCreacionHist(date('Y-m-d h:i:s', time() - 84600 * 1));
                
                $opcioHistArchivoContVersion->setNemotecnico($valor->getNemotecnico());
        
                $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
                if (!$currentUser) {
                    return new JsonResponse(['msg'=>'No existen el Usuario id : '.$this->security->getUser()->getId()],404);  
                }
                //$opcionesuser->setIdusuario($currentUser);
                $opcioHistArchivoContVersion->setIduser($currentUser);
        
        
                $currentOrientacion =$entityManager->getRepository(TipoOrientacion::class)->find(1);
                if (!$currentOrientacion) {
                    return new JsonResponse(['msg'=>'No existen el Tipo Orientacion id : '."1"],404);  
                }
                $opcioHistArchivoContVersion->setIdOrientacion($currentOrientacion);

                $currentOperaciones =$entityManager->getRepository(TipoOperaciones::class)->find(2);
                if (!$currentOperaciones) {
                    return new JsonResponse(['msg'=>'No existen el Tipo Operación id : '."2"],404);  
                }
                $opcioHistArchivoContVersion->setIdTipoOperaciones($currentOperaciones);

                $dime =$valor->getIdarchivo();

                $currentArchivos =$entityManager->getRepository(Archivos::class)->find($valor->getIdarchivo());
                if (!$currentArchivos) {
                    return new JsonResponse(['msg'=>'No existen el Archivo id : '.$valor->getId()],404);  
                }
                $opcioHistArchivoContVersion->setIdarchivo($currentArchivos);
                $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                if($empresa)
                $opcioHistArchivoContVersion->setIdempresa($empresa);   
        
                $entityManager->persist($opcioHistArchivoContVersion);
                $entityManager->flush(); 
    
                $nomorg = explode(".", $valor->getNombreOriginal()); 
    
            }
            if ($base64_arch) {
                return New JsonResponse(["file"=>$base64_arch,"title"=>$nomorg[0].'-'.$nomborgf[0],"extension"=>$nomborgf[1],"nemotecnico"=>$valor->getNemotecnico()]); 
            }else{
                return new JsonResponse(['msg'=>'No existe el Archivo: '.$id],404);  
            }
    
            } catch (Exception $e) {
                return new JsonResponse(['msg'=>'Error descargando el archivo '.$urles],500);
            }
            
        }
         


    // /**
    //  * @return HistArchivoContVersion[] Returns an array of HistArchivoContVersion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HistArchivoContVersion
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
