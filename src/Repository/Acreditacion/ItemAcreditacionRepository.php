<?php

namespace App\Repository\Acreditacion;

use App\Entity\Acreditacion\ItemAcreditacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Proyecto\Empresa;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
Use App\Entity\User;
Use App\Entity\Acreditacion\TipoItem;



/**
 * @method ItemAcreditacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method ItemAcreditacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method ItemAcreditacion[]    findAll()
 * @method ItemAcreditacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemAcreditacionRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, ItemAcreditacion::class);
    }


    /**
     * Create Calendario Proyecto.
     */
    public function put($data,$id,$validator,$helper): JsonResponse  {

        $entityManager = $this->getEntityManager();
        $entity =$entityManager->getRepository(ItemAcreditacion::class)->find($id);
        if (!$entity) {
            return new JsonResponse(['msg'=>'No existen Registros con el id: '.$id],404);  
        }

        $errors = $validator->validate($entity);
        if($errors->count() > 0){
            $errorsString = (string) $errors;
            return new JsonResponse(['msg'=>$errorsString],500);
        }else{
            $entity->setUsado(1);
            $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
            if($empresa)
                $entity->setIdempresa($empresa);
            $entityManager->flush();
            return new JsonResponse(['msg'=>'Registro Actualizado','id'=>$entity->getId()],200);
        }    
    }


    
}
