<?php

namespace App\Repository\DocumentoDigital;

use App\Entity\DocumentoDigital\MaterialRecibido;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;
use App\Dto\DocumentoDigital\MaterialRecibidoOutPutDto;
use Symfony\Component\HttpFoundation\JsonResponse;
Use App\Entity\User;
use App\Entity\Proyecto\Empresa;

/**
 * @method MaterialRecibido|null find($id, $lockMode = null, $lockVersion = null)
 * @method MaterialRecibido|null findOneBy(array $criteria, array $orderBy = null)
 * @method MaterialRecibido[]    findAll()
 * @method MaterialRecibido[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaterialRecibidoRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, MaterialRecibido::class);
    }

     /**
     * Listar Material Recibido.
     */
    public function findList($em)
    {
        $dataMaterialRecibido=[];
        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('materialrecibido')
        ->from(MaterialRecibido::class,'materialrecibido')
        ->addOrderBy('materialrecibido.id', 'ASC')
        ->getQuery();
        $queryult = $query->getQuery();
        $data =  $queryult->execute();
        $cont=0;
        foreach($data as $clave=>$valor){
          $materialRecibidoDto =new MaterialRecibidoOutPutDto();
          $materialRecibidoDto->id=$valor->getId();
          $materialRecibidoDto->nombrematerial=$valor->getNombrematerial();
          $dataMaterialRecibido[]=$materialRecibidoDto;
      }
       return array("data"=>$dataMaterialRecibido);
    }

     /**
     * Create Material Recibido.
     */
    public function post($data,$validator,$helper,$em): JsonResponse  {
        $entityManager = $em;
        $entity = new MaterialRecibido();
        $errors = $validator->validate($entity);
        if($errors->count() > 0){
            $errorsString = (string) $errors;
            return new JsonResponse(['msg'=>$errorsString],500);
        }else{
            $entityManagerDefault = $this->getEntityManager();
            $currentUser =$entityManagerDefault->getRepository(User::class)->find($this->security->getUser()->getId());
            $entity->setCreateBy($currentUser->getUserName());
            $entity->setCreateAt(new \DateTime());
            $empresa= $entityManagerDefault->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
            if($empresa)
                  $entity->setIdempresa($empresa->getId());

            if($data['nombrematerial']!="null"){
                  $entity->setNombrematerial($data['nombrematerial']);        
            }else{
                  return new JsonResponse(['msg'=>'Verifique Nombre Conservación Null '],404);
            }

            $entityManager->persist($entity);
            $entityManager->flush();
            return new JsonResponse(['msg'=>'Registro Creado','id'=>$entity->getId()],200);
        }    
    }

    // /**
    //  * @return MaterialRecibido[] Returns an array of MaterialRecibido objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MaterialRecibido
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
