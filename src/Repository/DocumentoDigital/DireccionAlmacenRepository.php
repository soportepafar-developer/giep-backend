<?php

namespace App\Repository\DocumentoDigital;

use App\Entity\DocumentoDigital\DireccionAlmacen;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;
use App\Dto\DocumentoDigital\DireccionAlmacenOutPutDto;
use Symfony\Component\HttpFoundation\JsonResponse;
Use App\Entity\User;
use App\Entity\Proyecto\Empresa;

/**
 * @method DireccionAlmacen|null find($id, $lockMode = null, $lockVersion = null)
 * @method DireccionAlmacen|null findOneBy(array $criteria, array $orderBy = null)
 * @method DireccionAlmacen[]    findAll()
 * @method DireccionAlmacen[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DireccionAlmacenRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, DireccionAlmacen::class);
    }

    /**
     * Listar Status.
     */
    public function findList($em)
    {
        $dataDireciionalmacen=[];
        $query = $em->createQueryBuilder();
        $allAppointmentsQuery = $query->select('direccionalmacen')
        ->from(DireccionAlmacen::class,'direccionalmacen')
        ->addOrderBy('direccionalmacen.id', 'ASC')
        ->getQuery();
        $queryult = $query->getQuery();
        $data =  $queryult->execute();
        $cont=0;
        foreach($data as $clave=>$valor){
          $profesionDto =new DireccionAlmacenOutPutDto();
          $profesionDto->id=$valor->getId();
          $profesionDto->nombre=$valor->getNombre();
          $profesionDto->direccionzona=$valor->getDireccionzona();
          $profesionDto->telefono=$valor->getTelefono();
          $dataDireciionalmacen[]=$profesionDto;
      }
       return array("data"=>$dataDireciionalmacen);
    }

         /**
     * Create Tipo Almacen.
     */
    public function post($data,$validator,$helper,$em): JsonResponse  {
        $entityManager = $em;
        $entity = new DireccionAlmacen();
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

            if($data['direccionzona']!="null"){
                  $entity->setDireccionzona($data['direccionzona']);        
            }else{
                  return new JsonResponse(['msg'=>'Verifique Dirección Zona Null '],404);
            }

            if($data['nombre']!="null"){
                $entity->setNombre($data['nombre']);        
            }else{
                    return new JsonResponse(['msg'=>'Verifique Nombre Null '],404);
            }

            if($data['telefono']!="null"){
                $entity->setTelefono($data['telefono']);        
            }else{
                    return new JsonResponse(['msg'=>'Verifique Telefono Null '],404);
            }

            $entityManager->persist($entity);
            $entityManager->flush();
            return new JsonResponse(['msg'=>'Registro Creado','id'=>$entity->getId()],200);
        }    
    }


    // /**
    //  * @return DireccionAlmacen[] Returns an array of DireccionAlmacen objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DireccionAlmacen
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
