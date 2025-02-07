<?php

namespace App\Repository\Proyecto;

use App\Entity\Proyecto\Items;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\Proyecto\TrazaRepository;
use App\Entity\Proyecto\ItemComentario;

Use App\Entity\User;
use App\Repository\Proyecto\SpringRepository;
use App\Repository\Proyecto\SprintItemRepository;
use App\Repository\Proyecto\NivelBoardPanelRepository;

Use App\Entity\Proyecto\Spring;
Use App\Entity\Proyecto\SprintItem;
use App\Entity\Proyecto\TypeEvent;
use App\Entity\Proyecto\NivelBoardPanel;
use App\Entity\Proyecto\Statusisuues;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use	Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\Asset\Packages;
use App\Entity\Proyecto\Empresa;
/**
 * @method Items|null find($id, $lockMode = null, $lockVersion = null)
 * @method Items|null findOneBy(array $criteria, array $orderBy = null)
 * @method Items[]    findAll()
 * @method Items[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemsRepository extends ServiceEntityRepository
{

    private $assetPackage;
    private $security;
    private $traza;
    public function __construct(ManagerRegistry $registry,Security $security, TrazaRepository $traza,Packages $assetPackage)
    {
        $this->traza=$traza;
        $this->assetPackage=$assetPackage;
        $this->security = $security;
        parent::__construct($registry, Items::class);
    }  

    /**
     * Create Tareas .
     */
    public function post_tareas($data,$validator,$helper): JsonResponse  {
        $entityManager = $this->getEntityManager();

        $entity =$entityManager->getRepository(User::class)->find($data['IdRecurso']);
        if (!$entity) {
            return new JsonResponse(['msg'=>'No existen Registros de Recursos (IdRecurso) con el id: '.$data['IdRecurso']],404);  
        }

        $entity =$entityManager->getRepository(Spring::class)->find($data['IdSpring']);
        if (!$entity) {
            return new JsonResponse(['msg'=>'No existen Registros de Spring (IdSpring) con el id: '.$data['IdSpring']],404);  
        }

        $entity=$helper->setParametersToEntity(new Items(),$data);
        $entity->setTitulo($data['titulo']);
        $entity->setDescripcion($data['descripcion']);
        $entity->setPeso($data['horasTareas']);

        $currentUser =$entityManager->getRepository(User::class)->find($data['IdRecurso']);
        $entity->setIdUser($currentUser); 
        //$entity->setIdUser($currentUser->getId()); 
        
                                            
        $currentBacklock =$entityManager->getRepository(Items::class)->find($data['IdBacklogPadre']);
        $entity->setIdBacklogPadre($currentBacklock); 
        //$entity->setIdBacklogPadre($currentBacklock->getIdBacklogPadre()); 

        //$entity->setIdBacklogPadre($data['IdBacklogPadre']);

        $errors = $validator->validate($entity);
        if($errors->count() > 0){
            $errorsString = (string) $errors;
            return new JsonResponse(['msg'=>$errorsString],500);
        }else{
            /* $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
            $entity->setCreateBy($currentUser->getUserName());
            $entity->setCreateBy($currentUser->getUserName());
  */
            $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
            if($empresa)
                $entity->setIdempresa($empresa); 
            $entityManager->persist($entity);
            $entityManager->flush();

            //Agregar la relacion entre Spring y las Tareas
            $sprinitems = new SprintItem();
                                                   
            $currentSpring =$entityManager->getRepository(Spring::class)->find($data['IdSpring']);
            $sprinitems->setIdSpring($currentSpring); 
            $currentItems =$entityManager->getRepository(Items::class)->find(intval($entity->getId()));
            $sprinitems->setIdItem($currentItems);  
            $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
            if($empresa)
                $sprinitems->setIdempresa($empresa); 

            $errors = $validator->validate($sprinitems);
            if($errors->count() > 0){
                $errorsString = (string) $errors;
                return new JsonResponse(['msg'=>$errorsString],500);
            }else{
                $entityManager->persist($sprinitems);
                $entityManager->flush();
            }   

            return new JsonResponse(['msg'=>'Registro Creado','id'=>$entity->getId()],200);
        }    
    }

    public function post_items($data,$validator,$helper): JsonResponse  {
        $entityManager = $this->getEntityManager();


        $entity=$helper->setParametersToEntity(new Items(),$data);
        $entity->setTitulo($data['title']);
                                                    
        if($data['parentId']!=null){
            $currentBacklock =$entityManager->getRepository(Items::class)->find($data['parentId']);
            $entity->setIdBacklogPadre($currentBacklock); 
        }
        if($data['typeItemId']!=null){
            $currentEven =$entityManager->getRepository(TypeEvent::class)->find($data['typeItemId']);
            $entity->setIdTypeevent($currentEven); 
        }
        if($data['levelBoardId']!=null){
            $currentIdBoard =$entityManager->getRepository(NivelBoardPanel::class)->find($data['levelBoardId']);
            $entity->setIdnivelboardpanel($currentIdBoard); 
        }    
        $entity->setOrden($data['order']); 
        
        $currentUser2 =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
        $entity->setCreateBy($currentUser2->getUserName());
        $entity->setCreateAt(new \DateTime('now'));

        $errors = $validator->validate($entity);
        if($errors->count() > 0){
            $errorsString = (string) $errors;
            return new JsonResponse(['msg'=>$errorsString],500);
        }else{
            $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
            if($empresa)
              $entity->setIdempresa($empresa); 
              
            $entityManager->persist($entity);
            $entityManager->flush();
            $sprinitems = new SprintItem();
            $currentSpring =$entityManager->getRepository(Spring::class)->find($data['springId']);
            $sprinitems->setIdSpring($currentSpring); 
            $sprinitems->setIdItem($entity);  
            $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
            if($empresa)
               $sprinitems->setIdempresa($empresa); 
            $entityManager->persist($sprinitems);
            $entityManager->flush();

            return new JsonResponse(['msg'=>'Registro Creado','id'=>$entity->getId()],200);
        }    
    }


    public function activities($id,$url)
    {

        $data=[];
        // $sql = " SELECT c.`id`,`id_user_id`,`id_backlog_padre_id`,
        // c.fechainicio,c.fechafin,
        // `titulo`,`descripcion`,`peso`,c.idproyecto_id FROM
        //  `items` a INNER JOIN sprint_item b on a.id = b.id_item_id 
        //  INNER JOIN spring c on c.id = b.id_spring_id where c.id=".$id." and a.id_backlog_padre_id is null";
        $sql="select c.`id`, c.fechainicio,c.fechafin, c.`nombre`,c.idproyecto_id FROM spring c where c.id=$id";
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $data=$stmt->fetchAll();
        if(count($data)>0){
            $dataReturn["data"]=array("springId"=>$data[0]["id"],
                "springName"=>$data[0]["nombre"],
                "startDate"=>$data[0]["fechainicio"],
                "endDate"=>$data[0]["fechafin"],
            );
        }
           
        $sql = " select a.id, a.titulo,a.id,a.id_user_id,a.peso,
        a.orden,a.idnivelboardpanel_id,CONCAT(d.primer_nombre,' ',d.primer_apellido)as nombre
        ,d.id as userid,d.foto,d.sexo,x.id as idtype,x.tipodescripcion,x.icon_class,x.color,a.orden,
        (select sum(j.peso) from items j where j.id_backlog_padre_id = a.id) as sumHoursChild 
        FROM `items` a INNER JOIN sprint_item b on a.id = b.id_item_id 
        INNER JOIN spring c on c.id = b.id_spring_id left join user d 
        on a.id_user_id = d.id 
        LEFT JOIN type_event x on a.id_typeevent_id = x.id
        where c.id=".$id." and a.id_backlog_padre_id is null order by a.orden";
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $data=$stmt->fetchAll();  
        $activities=[]; 
         

        foreach($data as $clave=>$valor){

            if(!is_null($valor["foto"])){
                //$stringFoto=$url.'\fotos\\'.$valor->getFoto();
                $stringFoto=$url.$valor["foto"];

                $stringFoto = filter_var($stringFoto, FILTER_SANITIZE_URL);
                $stringFoto = str_replace('\\', '', $stringFoto);
           }

            
            if($valor["sexo"]=="M"){
                $foto= (is_null($valor["foto"])) ? $this->assetPackage->getUrl($url.'\images\avatar_masculino.png'):$this->assetPackage->getUrl($stringFoto);
            }else{
                $foto= (is_null($valor["foto"])) ? $this->assetPackage->getUrl($url.'\images\avatar_femenino.png'):$this->assetPackage->getUrl($stringFoto);                
            }
            $activities[]=array("itemId"=>$valor["id"],
                "name"=>$valor["titulo"],
                "assignedName"=>$valor["nombre"],
                "assignedId"=>$valor["userid"],
                "avatar"=>$foto,
                "order"=>$valor["orden"],
                "hours"=>$valor["sumHoursChild"],
                "nivelboardpanelId"=>$valor["idnivelboardpanel_id"],
                "itemType"=>array("id"=>$valor["idtype"],"label"=>$valor["tipodescripcion"],
                "icon_class"=>$valor["icon_class"],"color"=>$valor["color"]),
                "tasks"=>$this->getTask($valor["id"],$url)
            );
           



            $dataReturn["data"]["activities"]  = $activities;
         

        }    
        return $dataReturn;
    }

    private function getTask($idTaskFather,$url){
        $sql="select a.id, a.titulo,a.id,a.id_user_id,a.peso, 
        a.orden,a.idnivelboardpanel_id,CONCAT(d.primer_nombre,' ',d.primer_apellido)as nombre,
        d.id as userid,d.foto,d.sexo,f.attr_key,x.id as idtype,
        x.tipodescripcion,x.icon_class,x.color,a.orden FROM `items` a 
        INNER JOIN sprint_item b on a.id = b.id_item_id 
        INNER JOIN spring c on c.id = b.id_spring_id 
        left join user d on a.id_user_id = d.id 
        left join nivel_board_panel f on a.idnivelboardpanel_id = f.id 
        LEFT JOIN type_event x on a.id_typeevent_id = x.id
        where a.id_backlog_padre_id  = ". $idTaskFather ." order by a.orden";
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $data=$stmt->fetchAll();  
        $taks=[]; 

        foreach($data as $clave=>$valor){
            if(!is_null($valor["foto"])){
                //$stringFoto=$url.'\fotos\\'.$valor->getFoto();
                $stringFoto=$url.$valor["foto"];

                $stringFoto = filter_var($stringFoto, FILTER_SANITIZE_URL);
                $stringFoto = str_replace('\\', '', $stringFoto);
           }

            
            if($valor["sexo"]=="M"){
                $foto= (is_null($valor["foto"])) ? $this->assetPackage->getUrl($url.'\images\avatar_masculino.png'):$this->assetPackage->getUrl($stringFoto);
            }else{
                $foto= (is_null($valor["foto"])) ? $this->assetPackage->getUrl($url.'\images\avatar_femenino.png'):$this->assetPackage->getUrl($stringFoto);                
            }
 
            $datos=array("itemId"=>$valor["id"],
            "name"=>$valor["titulo"],
            "assignedName"=>$valor["nombre"],
            "order"=>$valor["orden"],
            "assignedId"=>$valor["userid"],
            "avatar"=>$foto,
            "hours"=>$valor["peso"],
            "nivelboardpanelId"=>$valor["idnivelboardpanel_id"],
            "itemType"=>array("id"=>$valor["idtype"],"label"=>$valor["tipodescripcion"],
            "icon_class"=>$valor["icon_class"],"color"=>$valor["color"])
            );
            if (array_key_exists($valor["attr_key"], $taks)) {
                $taks[$valor["attr_key"]][]=$datos;
            }else{
                $taks[$valor["attr_key"]][]=$datos;

            }
        }
        return $taks;
    }    


/**
     * Update Items.
     */
    public function putItems($data,$validator,$helper): JsonResponse  
    {
        $entityManager = $this->getEntityManager();
        $query =  $entityManager->createQueryBuilder();
        $allAppointmentsQuery = $query->select('items.id,items.orden')
        ->from(Items::class,'items') 
        ->Where('items.idBacklogPadre='.$data["idBacklogPadre"])
        ->addOrderBy('items.orden', 'ASC')
        ->getQuery();
        $queryult = $query->getQuery();
        $dataItems =  $queryult->execute();
        $contorden = $data["orden"];
        $bordeno=false;
       
       foreach($data["currentArray"] as $clave=>$currdatacurrent){
            $sql2 = "update items set orden=".$currdatacurrent["order"]." where id =".$currdatacurrent["idTask"]." ";
            $conn2 = $this->getEntityManager()->getConnection();
            $stmt2 = $conn2->prepare($sql2);
            $stmt2->execute(); 
        }

        if($data["idnivelboardpanel"]== 3){
            $sql2 = "update items set orden=".$data["orden"].", id_backlog_padre_id=".$data["idBacklogPadre"].", idnivelboardpanel_id=".$data["idnivelboardpanel"].", peso=0 where id =".$data["id"]." ";
            $conn2 = $this->getEntityManager()->getConnection();
            $stmt2 = $conn2->prepare($sql2);
            $stmt2->execute();  
        }else{
            $sql2 = "update items set orden=".$data["orden"].", id_backlog_padre_id=".$data["idBacklogPadre"].", idnivelboardpanel_id=".$data["idnivelboardpanel"]." where id =".$data["id"]." ";
            $conn2 = $this->getEntityManager()->getConnection();
            $stmt2 = $conn2->prepare($sql2);
            $stmt2->execute();
        }

        foreach($data["previousArray"] as $clave=>$currdataprevious){
            $sql2 = "update items set orden=".$currdataprevious["order"]." where id =".$currdataprevious["idTask"]." ";
            $conn2 = $this->getEntityManager()->getConnection();
            $stmt2 = $conn2->prepare($sql2);
            $stmt2->execute(); 
        }

        $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
        $entity =$entityManager->getRepository(Items::class)->find($data["id"]);
        $data=array("tipoEntidad"=>"Mover tareas","idEntidad"=>$entity->getId(),"createdBy"=>$currentUser->getId(),"sqlInstruction"=>"update","accion"=>7);
        $this->traza->post($data,$validator,$helper);
       return new JsonResponse(['msg'=>'Registro Actualizado: '.$data["idEntidad"]],200);
    }

/**
     * Update Detalles Items.
     */
    public function putItemsDetalles($data,$validator,$helper): JsonResponse  
    {
        $idb = $data["id"];
        $sql2='';
        $entityManager = $this->getEntityManager();
        $entity =$entityManager->getRepository(Items::class)->find($data['id']);
        if (!$entity) {
            return new JsonResponse(['msg'=>'No existen Registros de Items con el id: '.$data['id']],404);  
        }
        $currentUser =$entityManager->getRepository(User::class)->find($data['id_user_id']);        
        //$sql2 = "update items set id_user_id=".$data['id_user_id'].",titulo='".$data['titulo']."',descripcion='".$data['descripcion']."',id_typeevent_id=".$data['id_typeevent_id']."";

        $contin = 0;
        $sql2 = "update items set ";

        if($data['id_user_id']!=Null && $data['id_user_id']!=null ){
            $sql2 = $sql2 . "id_user_id=".$data['id_user_id'] ;
            $contin=1;
        }
      
        if($data['titulo']!=Null && $data['titulo']!=null ){
            if($contin ==0 ){
                $sql2 = $sql2 . "id_user_id=".$data['id_user_id']  ;
            }{
                $sql2 = $sql2 . ",";
                $sql2 = $sql2 . "titulo='".$data['titulo']."'";
            }
            $contin=1;
        }

        if($data['descripcion']!=Null && $data['descripcion']!=null ){
            if($contin ==0 ){
                $sql2 = $sql2 . "descripcion='".$data['descripcion']."'";
            }{
                $sql2 = $sql2 . ",";
                $sql2 = $sql2 . "descripcion='".$data['descripcion']."'";
            }
            $contin=1;  
        }
        if($data['id_typeevent_id']!=Null && $data['id_typeevent_id']!=null ){
            if($contin ==0 ){
                $sql2 = $sql2 . "id_typeevent_id=".$data['id_typeevent_id']."";
            }{
                $sql2 = $sql2 . ",";
                $sql2 = $sql2 . "id_typeevent_id=".$data['id_typeevent_id']."";
            }
            $contin=1;  
        }
        
        if($data['id_backlog_padre_id']!=Null && $data['id_backlog_padre_id']!=null ){
            if($contin ==0 ){
                $sql2 = $sql2 . "id_backlog_padre_id=".$data['id_backlog_padre_id']."";
            }{
                $sql2 = $sql2 . ",";
                $sql2 = $sql2 . "id_backlog_padre_id=".$data['id_backlog_padre_id']."";
            }
            $contin=1;
        }

        if($data['idnivelboardpanel_id']!=Null && $data['idnivelboardpanel_id']!=null){
            if($contin ==0 ){
                $sql2 = $sql2 . "idnivelboardpanel_id=".$data['idnivelboardpanel_id']."";
            }{
                $sql2 = $sql2 . ",";
                $sql2 = $sql2 . "idnivelboardpanel_id=".$data['idnivelboardpanel_id']."";
            }
            $contin=1;  
        }
        if($data['peso']!=Null && $data['peso']!=null){
                $sql2 = $sql2 . ",";
                $sql2 = $sql2 . "peso=".$data['peso'];
        }
        $sql2 = $sql2 ." where id =".$data["id"];

        $conn2 = $this->getEntityManager()->getConnection();
        $stmt2 = $conn2->prepare($sql2);
        $stmt2->execute(); 
        $currentUserconx =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
        $entity =$entityManager->getRepository(Items::class)->find($data["id"]);
        $datat=array("tipoEntidad"=>"Actualizar Detalles Items","idEntidad"=>$entity->getId(),"createdBy"=>$currentUserconx->getId(),"sqlInstruction"=>"update","accion"=>8);
        $this->traza->post($datat,$validator,$helper);
        if($data['comentario']!=Null && $data['comentario']!=null){
            $currentUser =$entityManager->getRepository(User::class)->find($data['id_user_id']);        
            $opciones = new ItemComentario();
            $opciones->setComentario($data["comentario"]);
            $opciones->setIdUser($currentUser);
            $opciones->setItem($entity);
            $opciones->setCreateBy($currentUserconx->getUserName());
            $opciones->setCreateAt(new \DateTime());
            $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
            if($empresa)
               $opciones->setIdempresa($empresa); 
            $entityManager->persist($opciones);
            $entityManager->flush();    
            $entity =$entityManager->getRepository(Items::class)->find($data["id"]);
            $data=array("tipoEntidad"=>"Agregar Comentario Items","idEntidad"=>$entity->getId(),"createdBy"=>$currentUser->getId(),"sqlInstruction"=>"update","accion"=>9);
            $this->traza->post($data,$validator,$helper);
         }
        return new JsonResponse(['msg'=>'Registro Actualizado: '.$idb],200);
    }

    public function detalleItems($id,$url)
    {

        $data=[];
           
        $sql = " select a.id, a.titulo,a.id_user_id,
        a.peso, a.orden,a.idnivelboardpanel_id,
        CONCAT(d.primer_nombre,' ',d.primer_apellido)as user_full_name ,
        (select sum(j.peso) from items j where j.id_backlog_padre_id = a.id) as sumHoursChild,
        d.id as userid,d.foto,d.sexo,x.id as idtype,
        x.tipodescripcion,x.icon_class,x.color, 
        a.id_backlog_padre_id,a.descripcion,f.id as idnivelpanel, 
        f.nivelpanel FROM `items` a 
        left join user d on a.id_user_id = d.id 
        left join nivel_board_panel f on a.idnivelboardpanel_id = f.id 
        LEFT JOIN type_event x on a.id_typeevent_id = x.id WHERE a.id  = ".$id;

        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $data=$stmt->fetchAll();  
        $activities=[]; 
         
        foreach($data as $clave=>$valor){

            if(!is_null($valor["foto"])){
                //$stringFoto=$url.'\fotos\\'.$valor->getFoto();
                $stringFoto=$url.$valor["foto"];
                $stringFoto = filter_var($stringFoto, FILTER_SANITIZE_URL);
                $stringFoto = str_replace('\\', '', $stringFoto);
           }

            
            if($valor["sexo"]=="M"){
                $foto= (is_null($valor["foto"])) ? $this->assetPackage->getUrl($url.'\images\avatar_masculino.png'):$this->assetPackage->getUrl($stringFoto);
            }else{
                $foto= (is_null($valor["foto"])) ? $this->assetPackage->getUrl($url.'\images\avatar_femenino.png'):$this->assetPackage->getUrl($stringFoto);                
            }
            $activities=array("id"=>$valor["id"],
                "id_user_id"=>$valor["id_user_id"],
                "user_full_name"=>$valor["user_full_name"],
                "id_backlog_padre_id"=>$valor["id_backlog_padre_id"],
                "user_avatar"=>$foto,
                "peso"=>$valor["id_backlog_padre_id"]!=null?$valor["peso"]:$valor["sumHoursChild"],
                "titulo"=>$valor["titulo"],
                "idnivelboardpanel_id"=>$valor["idnivelpanel"],
                "descripcion"=>$valor["descripcion"],
                "nivelboardpanel"=>array("idnivelboardpanel_id"=>$valor["idnivelpanel"],"labelnibelboardpanel"=>$valor["nivelpanel"]),
                "typeevent"=>array("id_typeevent_id"=>$valor["idtype"],
                "label_typeevent"=>$valor["tipodescripcion"],
                "icon_class_typeevent"=>$valor["icon_class"],
                "color_typeevent"=>$valor["color"]),
                "comentarios"=>$this->getComment($valor["id"],$url)
            );
    
        }    
        $dataReturn["data"] = $activities;

        return $dataReturn;
    }


    private function getComment($id,$url){
        $sql=" SELECT item_comentario.id,`comentario`,`id_user_id`, 
        CONCAT(d.primer_nombre,' ',d.primer_apellido)as user_full_name,d.foto,
        d.sexo,create_at
        FROM `item_comentario`
                left join user d on item_comentario.id_user_id = d.id 
          where item_id = ". $id ." order by id";
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $data=$stmt->fetchAll();  
        $taks=[]; 

        foreach($data as $clave=>$valor){
            if(!is_null($valor["foto"])){
                //$stringFoto=$url.'\fotos\\'.$valor->getFoto();
                $stringFoto=$url.$valor["foto"];
                $stringFoto = filter_var($stringFoto, FILTER_SANITIZE_URL);
                $stringFoto = str_replace('\\', '', $stringFoto);
           }
           if($valor["sexo"]=="M"){
            $foto= (is_null($valor["foto"])) ? $this->assetPackage->getUrl($url.'\images\avatar_masculino.png'):$this->assetPackage->getUrl($stringFoto);
           }else{
            $foto= (is_null($valor["foto"])) ? $this->assetPackage->getUrl($url.'\images\avatar_femenino.png'):$this->assetPackage->getUrl($stringFoto);                
            }

            $taks[]=array("idcomentario"=>$valor["id"],
            "descripcioncomentario"=>$valor["comentario"],
            "user_full_name"=>$valor["user_full_name"],
            "create_at"=>$valor["create_at"],
            "user_avatar"=>$foto,
     
            );
        }
        return $taks;
    }    


     /**
     * Update Mover Items.
     */
    public function putImoverItems($data,$validator,$helper): JsonResponse  
    {
        $entityManager = $this->getEntityManager();
        $id_backlog_padre = 0; 
        $entity =$entityManager->getRepository(Spring::class)->find($data['springfutureid']);
        if (!$entity) {
            return new JsonResponse(['msg'=>'No existen Registros de Spring (springid) con el id: '.$data['springfutureid']],404);  
        }        
        $sql = " SELECT a.`id`,`id_user_id`,`id_backlog_padre_id`,`titulo`,`descripcion`,`peso`,`idnivelboardpanel_id`,`idstatusisuues_id`,`id_typeevent_id`,`orden`,c.idproyecto_id,c.id 
        FROM `items` a INNER JOIN sprint_item b on a.id = b.id_item_id 
        INNER JOIN spring c on c.id = b.id_spring_id where a.id=".$data['itemsid']." ";
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $dataitems=$stmt->fetchAll();
        foreach($dataitems as $clave=>$valor){
            $id_backlog_padre = $valor["id_backlog_padre_id"];
            $sql1 = " SELECT a.`id`,b.`id_spring_id`,b.`id_item_id` 
            FROM `spring` a INNER JOIN sprint_item b on a.id = b.id_item_id 
            where a.id=".$data['springfutureid']." ";
            $conn = $this->getEntityManager()->getConnection();
            $stmt = $conn->prepare($sql1);
            $stmt->execute();
            $dataidiitems=$stmt->fetchAll();

           $sql2 = " SELECT a.`id`,`id_user_id`,`id_backlog_padre_id`,`titulo`,`descripcion`,`peso`,`idnivelboardpanel_id`,`idstatusisuues_id`,`id_typeevent_id`,`orden`,c.idproyecto_id,c.id 
           FROM `items` a INNER JOIN sprint_item b on a.id = b.id_item_id 
           INNER JOIN spring c on c.id = b.id_spring_id where a.id=".$dataidiitems[0]['id_item_id']." ";
           $conn = $this->getEntityManager()->getConnection();
           $stmt = $conn->prepare($sql2);
           $stmt->execute();
           $data_hijo=$stmt->fetchAll();

           $sql2 = " SELECT a.`id`,`id_user_id`,`id_backlog_padre_id`,`titulo`,`descripcion`,`peso`,`idnivelboardpanel_id`,`idstatusisuues_id`,`id_typeevent_id`,`orden`,c.idproyecto_id,c.id 
           FROM `items` a INNER JOIN sprint_item b on a.id = b.id_item_id 
           INNER JOIN spring c on c.id = b.id_spring_id where a.id=".$data_hijo[0]['id_backlog_padre_id']." ";
           $conn = $this->getEntityManager()->getConnection();
           $stmt = $conn->prepare($sql2);
           $stmt->execute();
           $databacklog_padre=$stmt->fetchAll();
            $entity=$helper->setParametersToEntity(new Items(),$databacklog_padre);
            $errors = $validator->validate($entity);
            if($errors->count() > 0){
                $errorsString = (string) $errors;
                return new JsonResponse(['msg'=>$errorsString],500);
            }else{
                $entity->setTitulo($databacklog_padre[0]['titulo']);
                $entity->setDescripcion($databacklog_padre[0]['descripcion']);
                $entity->setPeso($databacklog_padre[0]['peso']);
                if($databacklog_padre[0]['id_user_id']!=Null && $databacklog_padre[0]['id_user_id']!=null){
                    $currentUser =$entityManager->getRepository(User::class)->find($databacklog_padre[0]['id_user_id']);
                    $entity->setIdUser($currentUser); 
                }
                $currentNivelBoardPanel =$entityManager->getRepository(NivelBoardPanel::class)->find($databacklog_padre[0]['idnivelboardpanel_id']);
                $entity->setIdnivelboardpanel($currentNivelBoardPanel); 
                $currentStatusIsuues =$entityManager->getRepository(Statusisuues::class)->find($databacklog_padre[0]['idstatusisuues_id']);
                $entity->setIdstatusisuues($currentStatusIsuues); 
                $currentEven =$entityManager->getRepository(TypeEvent::class)->find($databacklog_padre[0]['id_typeevent_id']);
                $entity->setIdTypeevent($currentEven); 
                $entity->setSwactivo(0); 
                $entityManager->persist($entity);
                $entityManager->flush();
                $idbacklog_padre_new = $entity->getId();
                //$idbacklog_padre_new = 27;
                $entity=$helper->setParametersToEntity(new Items(),$data_hijo);
                $errors = $validator->validate($entity);
                if($errors->count() > 0){
                    $errorsString = (string) $errors;
                    return new JsonResponse(['msg'=>$errorsString],500);
                }else{
                    $entity->setTitulo($data_hijo[0]['titulo']);
                    $entity->setDescripcion($data_hijo[0]['descripcion']);
                    $entity->setPeso($data_hijo[0]['peso']);
                    $entity->setOrden($data_hijo[0]['orden']);
                    $currentItems =$entityManager->getRepository(Items::class)->find($idbacklog_padre_new);
                    $entity->setIdBacklogPadre($currentItems);
                    if($databacklog_padre[0]['id_user_id']!=Null && $databacklog_padre[0]['id_user_id']!=null){
                        $currentUser =$entityManager->getRepository(User::class)->find($data_hijo[0]['id_user_id']);
                        $entity->setIdUser($currentUser); 
                    }
                    $currentNivelBoardPanel =$entityManager->getRepository(NivelBoardPanel::class)->find($data_hijo[0]['idnivelboardpanel_id']);
                    $entity->setIdnivelboardpanel($currentNivelBoardPanel); 
                    $currentStatusIsuues =$entityManager->getRepository(Statusisuues::class)->find($data_hijo[0]['idstatusisuues_id']);
                    $entity->setIdstatusisuues($currentStatusIsuues); 
                    $currentEven =$entityManager->getRepository(TypeEvent::class)->find($data_hijo[0]['id_typeevent_id']);
                    $entity->setIdTypeevent($currentEven); 
                    $entity->setSwactivo(0); 
                    $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                    if($empresa)
                       $entity->setIdempresa($empresa); 
                    $entityManager->persist($entity);
                    $entityManager->flush();
                    $idbacklog_hijo_new = $entity->getId();
                    //$idbacklog_hijo_new = 29;
                }
                $sprinitems = new SprintItem();
                $currentSpring =$entityManager->getRepository(Spring::class)->find($data['springfutureid']);
                $sprinitems->setIdSpring($currentSpring); 
                $currentItems =$entityManager->getRepository(Items::class)->find($idbacklog_hijo_new);
                $sprinitems->setIdItem($currentItems);
                $entityManager->persist($sprinitems);
                $entityManager->flush();
                $sql2 = "update items set swactivo=1 where id =".$dataidiitems[0]['id_item_id']." ";
                $conn2 = $this->getEntityManager()->getConnection();
                $stmt2 = $conn2->prepare($sql2);
                $stmt2->execute();  
                return new JsonResponse(['msg'=>'Registro Creado','id'=>$idbacklog_hijo_new],200);
            }
        }    
    }
}