<?php

namespace App\Repository\CalendarioPluggin;
use App\Repository\Acreditacion\TipoItemRepository;
use App\Entity\CalendarioPluggin\CalendarEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Dto\CalendarioPluggin\CalendarEventOutDto;
use App\Dto\CalendarioPluggin\UsersOutDto;
use App\Entity\CalendarioPluggin\CalendarUser;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
Use App\Entity\User;
Use App\Entity\Acreditacion\ItemAcreditacion;
Use App\Entity\Acreditacion\TipoItem;
use App\Entity\CalendarioPluggin\CalendarEventCorreoNotif;

use App\Entity\Encuesta\IntrumentoCapAcreditacion;
use App\Entity\Evaluacion\InstrumentoEvaAcreditacion;
use App\Entity\Encuesta\InstrumentoCaptura;
use App\Entity\Evaluacion\Evaluacion;
use App\Entity\Encuesta\InstrumentoUsuario;
use App\Entity\Evaluacion\EvaluacionUsuario;
use App\Entity\Pais;
use App\Entity\Estado;
use App\Dto\Encuesta\InstrumentoCapturaaOutPutDto;
use App\Dto\Evaluacion\EvaluacionOutPutDto;
use App\Entity\Proyecto\Empresa;
/**
 * @method CalendarEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method CalendarEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method CalendarEvent[]    findAll()
 * @method CalendarEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CalendarEventRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, CalendarEvent::class);
    }

/**
     * Create Calendario Proyecto.
     */
    public function post($data,$validator,$helper,$email,$pushbroadcast): JsonResponse  {
         $idemp =0; 
         $entityManager = $this->getEntityManager();
         $entityManagerUser =$this->getEntityManager();
         $entityManagerInstrumento=$this->getEntityManager();
         $entityManagerEvaluacion=$this->getEntityManager();

         $entity=$helper->setParametersToEntity(new CalendarEvent(),$data);
        $entity->setStart(new \DateTime($data["start"]));
        $entity->setEnd(new \DateTime($data["end"]));

         $fechcomvinc=date(strtotime($data["start"]));
            
            $FecInc = date("d-m-Y", $fechcomvinc);
            $FecInc = $this->fechaCastellano($FecInc);
            
            $fechcomvfin=date(strtotime($data["end"]));
            $FecFin = date("d-m-Y", $fechcomvfin);
            $HorInc = date("h:i", $fechcomvinc);
            $HorFin = date("h:i", $fechcomvfin);

        $errors = $validator->validate($entity);
        if($errors->count() > 0){
            $errorsString = (string) $errors;
            return new JsonResponse(['msg'=>$errorsString],500);
        }else{
            $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
            $entity->setCreatedBy($currentUser->getUserName());
            $entity->setUpdatedBy($currentUser->getUserName());
            $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
            if($empresa)
                $entity->setIdempresa($empresa);
                $idemp = $empresa->getId();

            $entityManager->persist($entity);
            $entityManager->flush();
            $user='';
            foreach($data["users"] as $valorUser){
    
                if(isset($data["instrumentCapId"]) or  isset($data["instrumentEvaId"])){
                    $Idusers =0;
                    $user = $entityManager->getRepository(User::class)->findBy(array("email"=>$valorUser["email"]));
                    if($user!=null){
                        foreach($user as $valorUserDime){
                            $Idusers = $valorUserDime->getId();
                        }
                    }
                }

                if(isset($data["instrumentCapId"])){
                  foreach($data["instrumentCapId"] as $valorinstrumentCap){
                      
                      
                        $IntrumentoCapAcreditacion = $entityManager->getRepository(IntrumentoCapAcreditacion::class)->findBy(array("idCalendarEvent"=>$entity->getId(),"instrumentCapId"=>$valorinstrumentCap));
                        if($IntrumentoCapAcreditacion==null){
                            $currentUser =$entityManager->getRepository(User::class)->find($Idusers);
                            $IntrumentoCap =$entityManager->getRepository(InstrumentoCaptura::class)->find($valorinstrumentCap);
                            $IntrumentoCapAcred = new IntrumentoCapAcreditacion();
                            //$IntrumentoCapAcred->setIdUser($currentUser);
                            $IntrumentoCapAcred->setInstrumentCapId($IntrumentoCap);
                            $IntrumentoCapAcred->setIdCalendarEvent($entity);
                            $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                            if($empresa)
                                $entity->setIdempresa($empresa);
                            $entityManagerUser->persist($IntrumentoCapAcred);
                            $entityManagerUser->flush();
                        }
                        
                         
                    $userInstrumento= $entityManager->getRepository(InstrumentoUsuario::class)->findBy(array("idUser"=>$Idusers,
                    "IdInstrumento"=>$valorinstrumentCap));
                    if($userInstrumento==null){
                        $instrumentoUsuario = new InstrumentoUsuario();
                        $instrumentoUsuario->setIdInstrumento($IntrumentoCap);
                        $user= $entityManager->getRepository(User::class)->find($Idusers);
                        $pais= $entityManager->getRepository(Pais::class)->find(1);
                        $estado= $entityManager->getRepository(Estado::class)->find(24);
                        $instrumentoUsuario->setIdUser($user);
                        $instrumentoUsuario->setEstadoId($estado);
                        $instrumentoUsuario->setPaisId($pais);
                        $instrumentoUsuario->setRespondida(0);
                        $instrumentoUsuario->setFechaAsignacion(new \DateTime());
                        $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                        if($empresa)
                            $entity->setIdempresa($instrumentoUsuario);
                        $entityManagerInstrumento->persist($instrumentoUsuario);
                        $entityManagerInstrumento->flush();
                    }

                  }

                }

                if(isset($data["instrumentEvaId"])){
                  foreach($data["instrumentEvaId"] as $instrumentEva){
                        $IntrumentoCapAcreditacion = $entityManager->getRepository(InstrumentoEvaAcreditacion::class)->findBy(array("idCalendarEvent"=>$entity->getId(),"instrumentEvaId"=>$instrumentEva));
                        if($IntrumentoCapAcreditacion==null){
                            $currentUser =$entityManager->getRepository(User::class)->find($Idusers);
                            $IntrumentoEva =$entityManager->getRepository(Evaluacion::class)->find($instrumentEva);
                            $instrumentEva = new InstrumentoEvaAcreditacion();
                            //$instrumentEva->setIdUser($currentUser);
                            $instrumentEva->setInstrumentEvaId($IntrumentoEva);
                            $instrumentEva->setIdCalendarEvent($entity);
                            $entityManagerUser->persist($instrumentEva);
                            $entityManagerUser->flush();
                        }

                    $evaluacionUsuario = $entityManager->getRepository(EvaluacionUsuario::class)->findBy(array("IdEvaluacion"=>$instrumentEva,"idUser"=>$Idusers));
                    if($evaluacionUsuario==null){
                         $instrumentoUsuario = new EvaluacionUsuario();
                         $instrumentoUsuario->setFechaAsignacion(new \DateTime());
                         $instrumentoUsuario->setIdEvaluacion($IntrumentoEva);
                         $user= $entityManager->getRepository(User::class)->find($Idusers);
                         $instrumentoUsuario->setIdUser($user);
                         $pais= $entityManager->getRepository(Pais::class)->find(1);
                         $estado= $entityManager->getRepository(Estado::class)->find(24);
                         $instrumentoUsuario->setEstadoId($estado);
                         $instrumentoUsuario->setPaisId($pais);
                         $instrumentoUsuario->setRespondida(0);
                         $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                        if($empresa)
                           $instrumentoUsuario->setIdempresa($empresa);
                         $entityManagerEvaluacion->persist($instrumentoUsuario);
                         $entityManagerEvaluacion->flush();

                    }

                  }
                }

                //$sqlrecr = " INSERT INTO calendar_event_correo_notif (id_calendar_event, swenvio, email) VALUES (".$entity->getId().",0,'".$valorUser["email"]."') ";
                  $sqlrecr = "";
                if(isset($data["acreditacion"])==1){
                   $sqlrecr = " INSERT INTO calendar_event_correo_notif (id_calendar_event, swenvio, email, idempresa_id) VALUES (".$entity->getId().",2,'".$valorUser["email"]."', ".$idemp.") ";
                }else{
                   $sqlrecr = " INSERT INTO calendar_event_correo_notif (id_calendar_event, swenvio, email,	idempresa_id) VALUES (".$entity->getId().",0,'".$valorUser["email"]."', ".$idemp.") ";
                }
                $conn = $this->getEntityManager()->getConnection();
                $stmt = $conn->prepare($sqlrecr);
                $stmt->execute();
               //*************************************************/

                $calendarUser = new CalendarUser();
                $calendarUser->setEmail($valorUser["email"]);
                $calendarUser->setIdCalendar($entity);
                $entityManagerUser->persist($calendarUser);
                $entityManagerUser->flush();
                if(isset($data["accreditationItems"])){
                    foreach($data["accreditationItems"] as $valorItems){
                        $item = new ItemAcreditacion();
                        $item->setIdTipoITem(!is_null($valorItems["id"])?$entityManager->getRepository(TipoItem::class)->find($valorItems["id"]):null);
                        $item->setCantidad(!is_null($valorItems["quantity"])?$valorItems["quantity"]:0);
                        $item->setIdAcreditacion($calendarUser);
                        $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                        if($empresa)
                           $item->setIdempresa($empresa);
                        $entityManagerUser->persist($item);
                        $entityManagerUser->flush();
                    }
                }

                 $arrnotifica = array('destinatary' => $valorUser["email"], 'message' => 'Ha sido invitado al evento "'.$data["title"].'" el: '. $FecInc .' Desde: ' . $HorInc .' Hasta: '. $HorFin );
                $brochureBroadcast = $pushbroadcast->pushBroadcast($arrnotifica);
                
                /*
                $correoUser = $valorUser["email"];
                $user = $entityManager->getRepository(User::class)->findBy(array('email' => $correoUser));
                $Idusers =0;
                foreach($user as $valorUserDime){
                    $Idusers = $valorUserDime->getId();
                }
                
                sleep(15); */
                 
                //$datosqr = json_encode(['idEvent'=>$entity->getId(),'userId'=>$Idusers]);
                //$brochureBroadcast = $Fpdfreporte->pushCod_QR_Correo($datosqr);
                //$email->enviocorreo_qr(array("email"=>$valorUser["email"]),"Estimado Sr(a):".$valorUser["email"]."<br><br>Usted ha sido Invitado al Evento ".utf8_decode($entity->getTitle())."<br><br>".utf8_decode($entity->getDescription())."<br><br> Fecha Inicio:".$entity->getStart()->format("d/m/Y H:i")."<br>Fecha Finalizacion:".$entity->getEnd()->format("d/m/Y H:i"),$entity->getTitle() );

                 //sleep(15);
                /* sleep(30);
                
                $arrnotifica = array('destinatary' => $valorUser["email"], 'message' => 'Ha sido invitado al evento "'.$data["title"].'" el: '. $FecInc .' Desde: ' . $HorInc .' Hasta: '. $HorFin );
                $brochureBroadcast = $pushbroadcast->pushBroadcast($arrnotifica);
                $email->enviocorreo(array("email"=>$valorUser["email"]),"Estimado Sr(a):".$valorUser["email"]."<br><br>Usted ha sido Invitado al Evento ".utf8_decode($entity->getTitle())."<br><br>".utf8_decode($entity->getDescription())."<br><br> Fecha Inicio:".$entity->getStart()->format("d/m/Y H:i")."<br>Fecha Finalizacion:".$entity->getEnd()->format("d/m/Y H:i"),$entity->getTitle() ); */
            }
            return new JsonResponse(['msg'=>'Registro Creado','id'=>$entity->getId()],200);
        }    
    }

    /**
     * Update Calendario Proyecto.
     */
    public function put($data,$id,$validator,$helper,$email,$pushbroadcast): JsonResponse  {

        $entityManager = $this->getEntityManager();
        $entityManagerUser =$this->getEntityManager();
        $entity =$entityManager->getRepository(CalendarEvent::class)->find($id);
         if (!$entity) {
            return new JsonResponse(['msg'=>'No existen Registros con el id: '.$id],404);  
        }

        $entity=$helper->setParametersToEntity($entity,$data);
        $entity->setStart(new \DateTime($data["start"]));
        $entity->setEnd(new \DateTime($data["end"]));

        $errors = $validator->validate($entity);
        if($errors->count() > 0){
            $errorsString = (string) $errors;
            return new JsonResponse(['msg'=>$errorsString],500);
        }else{
            $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
            $entity->setCreatedBy($currentUser->getUserName());
            $entity->setUpdatedBy($currentUser->getUserName());
            //$entityManager->persist($entity);
            //$entityManager->flush();

          //buscar si agregaron uno nuevo  
             $fechcomvinc=date(strtotime($data["start"]));
            $FecInc = date("d-m-Y", $fechcomvinc);
            $FecInc = $this->fechaCastellano($FecInc);
                
            $fechcomvfin=date(strtotime($data["end"]));
            $FecFin = date("d-m-Y", $fechcomvfin);
            $HorInc = date("h:i", $fechcomvinc);
            $HorFin = date("h:i", $fechcomvfin);

          $sqlrecr = " SELECT * FROM calendar_user where id_calendar_id=".$id." ";
          $conn = $this->getEntityManager()->getConnection();
          $stmt = $conn->prepare($sqlrecr);
          $stmt->execute();
          $dataCelndarUser=$stmt->fetchAll();

         $encontro = false; 
            
          foreach($data["users"] as $valorUser){
            $clave = false;
            foreach($entity->getCalendarUsers() as $user){     
                $busc = $valorUser["email"];
                $busc2 = $user->getEmail();
                if($busc == $busc2){
                    $clave =true;
                    break;
                }
            }
            if($clave == false){
                $encontro=true;
                //enviar notificaciones usuarios 
                //$arrnotifica = array('destinatary' => $valorUser["email"], 'message' => 'Agregado en el evento "'.$data["title"].'"  Fecha: '. $FecInc .' Hora: ' . $HorInc .' - '. $HorFin );

                $arrnotifica = array('destinatary' => $valorUser["email"], 'message' => 'Ha sido invitado al evento "'.$data["title"].'" el: '. $FecInc .' Desde: ' . $HorInc .' Hasta: '. $HorFin );
                //$brochureBroadcast = $pushbroadcast->pushBroadcast($arrnotifica);
            //******************************/
            }
          }

           
            foreach($entity->getCalendarUsers() as $user){     
              $Estanuser[]=array($user->getEmail());                                      
              $entity->removeCalendarUser($user);
              $entityManager->persist($entity);
              $entityManager->flush();
           }

            foreach($data["users"] as $valorUser){

                foreach($entity->getCalendarUsers() as $acreditacionItems){             
                    foreach($acreditacionItems->getItemAcreditacions() as $items){             
                        $entity->removeItemAcreditacion($items);
                        $entityManager->persist($entity);
                        $entityManager->flush();
                    }
                }
    
                $calendarUser = new CalendarUser();
                $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                if($empresa)
                  $calendarUser->setIdempresa($empresa);
                
                $calendarUser->setEmail($valorUser["email"]);
                $calendarUser->setIdCalendar($entity);
                $entityManagerUser->persist($calendarUser);
                $entityManagerUser->flush();
                if(isset($data["accreditationItems"])){
                    foreach($data["accreditationItems"] as $valorItems){
                        $item = new ItemAcreditacion();
                        if($empresa)
                           $item->setIdempresa($empresa);
                        $item->setIdTipoITem(!is_null($valorItems["id"])?$entityManager->getRepository(TipoItem::class)->find($valorItems["id"]):null);
                        $item->setCantidad(!is_null($valorItems["quantity"])?$valorItems["quantity"]:0);
                        $item->setIdAcreditacion($calendarUser);
                        $entityManagerUser->persist($item);
                        $entityManagerUser->flush();
                    }
                }

                $fechcomvinc=date(strtotime($data["start"]));
                $FecInc = date("d-m-Y", $fechcomvinc);
                $FecInc = $this->fechaCastellano($FecInc);


                $fechcomvfin=date(strtotime($data["end"]));
                $FecFin = date("d-m-Y", $fechcomvfin);
                $HorInc = date("h:i", $fechcomvinc);
                $HorFin = date("h:i", $fechcomvfin);
                //enviar notificaciones usuarios 
                //$arrnotifica = array('destinatary' => $valorUser["email"], 'message' => 'Ha sido actualizado el evento ' . $data["title"] .' del dÃ­a: '.'" Fecha: '. $FecInc .' Hora: ' . $HorInc .' - '. $HorFin );
                
                $arrnotifica = array('destinatary' => $valorUser["email"], 'message' => 'Ha sido actualizado el evento "'.$data["title"].'" el: '. $FecInc .' Desde: ' . $HorInc .' Hasta: '. $HorFin );
                //$brochureBroadcast = $pushbroadcast->pushBroadcast($arrnotifica);
                //******************************/
            }

            foreach($dataCelndarUser as $claveResult2=>$user){     
                $clave = false;
                $sqlrecr = " SELECT * FROM calendar_user where email='".$user["email"]."' and id_calendar_id=".$id." ";
                $conn = $this->getEntityManager()->getConnection();
                $stmt = $conn->prepare($sqlrecr);
                $stmt->execute();
                $dataCelndarUser=$stmt->fetchAll();
                if(count($dataCelndarUser)<1){
                    $clave =true;
                   //enviar notificaciones usuarios 
                   $arrnotifica = array('destinatary' => $user["email"], 'message' => 'Ha sido retirado del evento "' . $data["title"] .'" el: '. $FecInc .' Desde: ' . $HorInc .' Hasta: '. $HorFin );
                  // $brochureBroadcast = $pushbroadcast->pushBroadcast($arrnotifica);   
                }
            }

            return new JsonResponse(['msg'=>'Registro Actualizado','id'=>$entity->getId()],200);
        }    
    }

         /**
     * Update Calendario Proyecto.
     */
    public function put_proxima_actualizacion($data,$id,$validator,$helper,$email,$pushbroadcast): JsonResponse  {

        $entityManager = $this->getEntityManager();
        $entityManagerUser =$this->getEntityManager();
        $entityManagerInstrumento=$this->getEntityManager();
        $entityManagerEvaluacion=$this->getEntityManager();
        $Idusers=0;
        $entity =$entityManager->getRepository(CalendarEvent::class)->find($id);
         if (!$entity) {
            return new JsonResponse(['msg'=>'No existen Registros con el id: '.$id],404);  
        }

        //Codigo listo *****



        if(isset($data["instrumentCapId"])){
            foreach($data["instrumentCapId"] as $valorinstrumentCap){
                $sql ="SELECT calendar_event.id,calendar_event.start,calendar_event.end,calendar_event.title,calendar_event.description,
                calendar_event.acreditacion,instrumento_usuario.respondida,instrumento_usuario.id_user_id,intrumento_cap_acreditacion.instrument_cap_id_id 
                ,instrumento_captura.nombre
                FROM calendar_event 
                INNER JOIN intrumento_cap_acreditacion ON calendar_event.id = intrumento_cap_acreditacion.id_calendar_event_id 
                INNER JOIN instrumento_usuario ON intrumento_cap_acreditacion.instrument_cap_id_id = instrumento_usuario.id_instrumento_id 
                INNER JOIN instrumento_captura ON intrumento_cap_acreditacion.instrument_cap_id_id = instrumento_captura.id 
                where intrumento_cap_acreditacion.instrument_cap_id_id=".$valorinstrumentCap." and instrumento_usuario.respondida="."1"." "; 
                $conn = $this->getEntityManager()->getConnection();
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $dataintrumento_cap =$stmt->fetchAll();
                if($dataintrumento_cap!=null){
                    return new JsonResponse(['msg'=>'El instrumento ('. $dataintrumento_cap[0]['nombre'] .") ya ha sido respondido por invitados "],409);  
                }
        

            }
        }

        if(isset($data["instrumentEvaId"])){
            foreach($data["instrumentEvaId"] as $instrumentEva){

                $sql = "SELECT calendar_event.id,evaluacion_usuario.respondida,evaluacion_usuario.id_user_id,evaluacion.nombre 
                FROM calendar_event 
                INNER JOIN instrumento_eva_acreditacion ON calendar_event.id = instrumento_eva_acreditacion.id_calendar_event_id 
                INNER JOIN evaluacion_usuario ON instrumento_eva_acreditacion.instrument_eva_id_id = evaluacion_usuario.id_evaluacion_id 
                INNER JOIN evaluacion ON instrumento_eva_acreditacion.instrument_eva_id_id = evaluacion.id 
                where instrumento_eva_acreditacion.instrument_eva_id_id=".$instrumentEva." and evaluacion_usuario.respondida="."1"." "; 
                $conn = $this->getEntityManager()->getConnection();
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $dataintrumento_cap =$stmt->fetchAll();
                if($dataintrumento_cap!=null){
                    return new JsonResponse(['msg'=>'El instrumento evaluación ('. $dataintrumento_cap[0]['nombre'] .") ya ha sido respondido por invitados "],409);                      

                }

            }
        }



        $sql ="SELECT intrumento_cap_acreditacion.instrument_cap_id_id,
        intrumento_cap_acreditacion.id_calendar_event_id
        FROM intrumento_cap_acreditacion
        where intrumento_cap_acreditacion.id_calendar_event_id=".$id." ";
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $buscdataintrumento_cap =$stmt->fetchAll();
        /* if($dataintrumento_cap!=null){
          $valorinstrumentCap = $dataintrumento_cap[0]["instrument_cap_id_id"];
        } */
       
        if(isset($data["instrumentCapId"])){

            //Vincular nuevos    
          foreach($data["instrumentCapId"] as $valorCapId){
                $clavevincula = false;
                foreach($buscdataintrumento_cap as $buscCapId){     
                    $busc = $valorCapId;
                    $busc2 = $buscCapId["instrument_cap_id_id"];
                    if($busc == $busc2){
                        $clavevincula =true;
                        break;
                    }

                if($clavevincula == false){
                  //Vincular registro 
                  $IntrumentoCapAcreditacion = $entityManager->getRepository(IntrumentoCapAcreditacion::class)->findBy(array("idCalendarEvent"=>$entity->getId(),"instrumentCapId"=>$busc));
                  if($IntrumentoCapAcreditacion==null){
                        //$currentUser =$entityManager->getRepository(User::class)->find($Idusers);
                        $IntrumentoCap =$entityManager->getRepository(InstrumentoCaptura::class)->find($busc);
                        $IntrumentoCapAcred = new IntrumentoCapAcreditacion();
                        $IntrumentoCapAcred->setInstrumentCapId($IntrumentoCap);
                        $IntrumentoCapAcred->setIdCalendarEvent($entity);
                        $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                        if($empresa)
                           $IntrumentoCapAcred->setIdempresa($empresa);
                        $entityManagerUser->persist($IntrumentoCapAcred);
                        $entityManagerUser->flush();
                  }

                  foreach($data["users"] as $valorUser){
                    if(isset($data["instrumentCapId"]) or  isset($data["instrumentEvaId"])){
                        $Idusers =0;
                        $user = $entityManager->getRepository(User::class)->findBy(array("email"=>$valorUser["email"]));
                        if($user!=null){
                            foreach($user as $valorUserDime){
                                $Idusers = $valorUserDime->getId();
                            }
                        }
                    }

                  $userInstrumento= $entityManager->getRepository(InstrumentoUsuario::class)->findBy(array("idUser"=>$Idusers,
                    "IdInstrumento"=>$busc));
                    if($userInstrumento==null){
                        $IntrumentoCap =$entityManager->getRepository(InstrumentoCaptura::class)->find($busc);
                        $instrumentoUsuario = new InstrumentoUsuario();
                        $instrumentoUsuario->setIdInstrumento($IntrumentoCap);
                        $user= $entityManager->getRepository(User::class)->find($Idusers);
                        $pais= $entityManager->getRepository(Pais::class)->find(1);
                        $estado= $entityManager->getRepository(Estado::class)->find(24);
                        $instrumentoUsuario->setIdUser($user);
                        $instrumentoUsuario->setEstadoId($estado);
                        $instrumentoUsuario->setPaisId($pais);
                        $instrumentoUsuario->setRespondida(0);
                        $instrumentoUsuario->setFechaAsignacion(new \DateTime());
                        $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                        if($empresa)
                           $instrumentoUsuario->setIdempresa($empresa);
                        $entityManagerInstrumento->persist($instrumentoUsuario);
                        $entityManagerInstrumento->flush();
                    }else{
                       //break;
                    }
                 }


                }
            }
          }

          //Desvincular 
          foreach($buscdataintrumento_cap as $buscCapId){     
              $clavedesvincula = false;
              foreach($data["instrumentCapId"] as $valorCapId){
                    $busc = $valorCapId;
                    $busc2 = $buscCapId["instrument_cap_id_id"];
                    if($busc == $busc2){
                       $clavedesvincula =true;
                    break;
                    }
              }

                if($clavedesvincula == false){
                    //Desvincular registro 
                    $sql ="SELECT id_user_id,id_instrumento_id FROM instrumento_usuario
                    where instrumento_usuario.id_instrumento_id=".$busc2." ";
                    $conn1 = $this->getEntityManager()->getConnection();
                    $stmt = $conn1->prepare($sql);
                    $stmt->execute();
                    $buscelimndataintrumento_cap =$stmt->fetchAll();
                    foreach($buscelimndataintrumento_cap as $valorelminUser){
                        $IdUsersElim = $valorelminUser["id_user_id"];
                        //Desvincular usuarios retirado del evento *************************** 
                        $sql = "DELETE FROM instrumento_usuario where id_user_id=".$IdUsersElim." and  id_instrumento_id=".$busc2." ";
                        $conn = $this->getEntityManager()->getConnection();
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                  }
                  $sql = "DELETE FROM intrumento_cap_acreditacion where instrument_cap_id_id=".$busc2." and  id_calendar_event_id=".$id." ";
                  $conn = $this->getEntityManager()->getConnection();
                  $stmt = $conn->prepare($sql);
                  $stmt->execute();
                }
               
          } 


        } 
 
        //********************************* Evaluacion *****************************************************/


        $sql ="SELECT instrumento_eva_acreditacion.instrument_eva_id_id,
        instrumento_eva_acreditacion.id_calendar_event_id
        FROM instrumento_eva_acreditacion
        where instrumento_eva_acreditacion.id_calendar_event_id=".$id." ";
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $buscdataintrumento_eva =$stmt->fetchAll();
        /* if($dataintrumento_cap!=null){
          $valorinstrumentCap = $dataintrumento_cap[0]["instrument_cap_id_id"];
        } */
       
        if(isset($data["instrumentEvaId"])){

            //Vincular nuevos    
          foreach($data["instrumentEvaId"] as $valorCapId){
                $clavevincula = false;
                foreach($buscdataintrumento_eva as $buscCapId){     
                    $busc = $valorCapId;
                    $busc2 = $buscCapId["instrument_eva_id_id"];
                    if($busc == $busc2){
                        $clavevincula =true;
                        break;
                    }

                if($clavevincula == false){
                  //Vincular registro 
                  $IntrumentoCapAcreditacion = $entityManager->getRepository(InstrumentoEvaAcreditacion::class)->findBy(array("idCalendarEvent"=>$entity->getId(),"instrumentEvaId"=>$busc));
                  if($IntrumentoCapAcreditacion==null){
                        $IntrumentoEva =$entityManager->getRepository(Evaluacion::class)->find($busc);
                        $instrumentEva = new InstrumentoEvaAcreditacion();
                        $instrumentEva = new InstrumentoEvaAcreditacion();
                        $instrumentEva->setInstrumentEvaId($IntrumentoEva);
                        $instrumentEva->setIdCalendarEvent($entity);
                        $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                        if($empresa)
                           $instrumentEva->setIdempresa($empresa);
                        $entityManagerUser->persist($instrumentEva);
                        $entityManagerUser->flush();

                  }

                  foreach($data["users"] as $valorUser){
                    if(isset($data["instrumentEvaId"])){
                        $Idusers =0;
                        $user = $entityManager->getRepository(User::class)->findBy(array("email"=>$valorUser["email"]));
                        if($user!=null){
                            foreach($user as $valorUserDime){
                                $Idusers = $valorUserDime->getId();
                            }
                        }
                    }

                    $evaluacionUsuario = $entityManager->getRepository(EvaluacionUsuario::class)->findBy(array("IdEvaluacion"=>$busc,"idUser"=>$Idusers));
                    if($evaluacionUsuario==null){
                         $instrumentoUsuario = new EvaluacionUsuario();
                         $instrumentoUsuario->setFechaAsignacion(new \DateTime());
                         $instrumentoUsuario->setIdEvaluacion($IntrumentoEva);
                         $user= $entityManager->getRepository(User::class)->find($Idusers);
                         $instrumentoUsuario->setIdUser($user);
                         $pais= $entityManager->getRepository(Pais::class)->find(1);
                         $estado= $entityManager->getRepository(Estado::class)->find(24);
                         $instrumentoUsuario->setEstadoId($estado);
                         $instrumentoUsuario->setPaisId($pais);
                         $instrumentoUsuario->setRespondida(0);
                         $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                        if($empresa)
                          $instrumentoUsuario->setIdempresa($empresa);
                         $entityManagerEvaluacion->persist($instrumentoUsuario);
                         $entityManagerEvaluacion->flush();

                    }

                  
                 }


                }
            }
          }

          //Desvincular 
          foreach($buscdataintrumento_eva as $buscCapId){     
              $clavedesvincula = false;
              foreach($data["instrumentEvaId"] as $valorCapId){
                    $busc = $valorCapId;
                    $busc2 = $buscCapId["instrument_eva_id_id"];
                    if($busc == $busc2){
                       $clavedesvincula =true;
                    break;
                    }
              }

                if($clavedesvincula == false){
                    //Desvincular registro 
                    $sql ="SELECT id_user_id,id_evaluacion_id FROM evaluacion_usuario
                    where evaluacion_usuario.id_evaluacion_id=".$busc2." ";
                    $conn1 = $this->getEntityManager()->getConnection();
                    $stmt = $conn1->prepare($sql);
                    $stmt->execute();
                    $buscelimndataintrumento_eva =$stmt->fetchAll();
                    foreach($buscelimndataintrumento_eva as $valorelminUser){
                        $IdUsersElim = $valorelminUser["id_user_id"];
                        //Desvincular usuarios retirado del evento *************************** 
                        $sql = "DELETE FROM evaluacion_usuario where id_user_id=".$IdUsersElim." and  id_evaluacion_id=".$busc2." ";
                        $conn = $this->getEntityManager()->getConnection();
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                  }
                  $sql = "DELETE FROM instrumento_eva_acreditacion where instrument_eva_id_id=".$busc2." and  id_calendar_event_id=".$id." ";
                  $conn = $this->getEntityManager()->getConnection();
                  $stmt = $conn->prepare($sql);
                  $stmt->execute();
                }
               
          } 


        }




         //**************************************************************************************/

        $valorinstrumentCap=0;
        $sql ="SELECT distinct calendar_event.id,calendar_event.start,calendar_event.end,calendar_event.title,calendar_event.description,
        calendar_event.acreditacion,intrumento_cap_acreditacion.instrument_cap_id_id
        FROM calendar_event 
        INNER JOIN intrumento_cap_acreditacion ON calendar_event.id = intrumento_cap_acreditacion.id_calendar_event_id 
        where calendar_event.id=".$id." ";
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $dataintrumento_cap =$stmt->fetchAll();
        if($dataintrumento_cap!=null){
          $valorinstrumentCap = $dataintrumento_cap[0]["instrument_cap_id_id"];
        }

        $entity=$helper->setParametersToEntity($entity,$data);
        $entity->setStart(new \DateTime($data["start"]));
        $entity->setEnd(new \DateTime($data["end"]));

        $errors = $validator->validate($entity);
        if($errors->count() > 0){
            $errorsString = (string) $errors;
            return new JsonResponse(['msg'=>$errorsString],500);
        }else{
            $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
            $entity->setCreatedBy($currentUser->getUserName());
            $entity->setUpdatedBy($currentUser->getUserName());
            //$entityManager->persist($entity);
            //$entityManager->flush();

          //buscar si agregaron uno nuevo  
             $fechcomvinc=date(strtotime($data["start"]));
            $FecInc = date("d-m-Y", $fechcomvinc);
            $FecInc = $this->fechaCastellano($FecInc);
                
            $fechcomvfin=date(strtotime($data["end"]));
            $FecFin = date("d-m-Y", $fechcomvfin);
            $HorInc = date("h:i", $fechcomvinc);
            $HorFin = date("h:i", $fechcomvfin);

          $sqlrecr = " SELECT * FROM calendar_user where id_calendar_id=".$id." ";
          $conn = $this->getEntityManager()->getConnection();
          $stmt = $conn->prepare($sqlrecr);
          $stmt->execute();
          $dataCelndarUser=$stmt->fetchAll();

         $encontro = false; 
            
          foreach($data["users"] as $valorUser){
            $clave = false;
            foreach($entity->getCalendarUsers() as $user){     
                $busc = $valorUser["email"];
                $busc2 = $user->getEmail();
                if($busc == $busc2){
                    $clave =true;
                    break;
                }
            }
            if($clave == false){
                $encontro=true;
                  //Vincular usuarios agregados al evento *************************** 

                  $user = $entityManager->getRepository(User::class)->findBy(array('email' => $busc));
                  $Idusers =0;
                  foreach($user as $valorUserDime){
                      $Idusers = $valorUserDime->getId();
                  }

                  $userInstrumento= $entityManager->getRepository(InstrumentoUsuario::class)->findBy(array("idUser"=>$Idusers,
                  "IdInstrumento"=>$valorinstrumentCap));
                  if($userInstrumento==null){
                      $IntrumentoCap =$entityManager->getRepository(InstrumentoCaptura::class)->find($valorinstrumentCap);
                      $instrumentoUsuario = new InstrumentoUsuario();
                      $instrumentoUsuario->setIdInstrumento($IntrumentoCap);
                      $user= $entityManager->getRepository(User::class)->find($Idusers);
                      $pais= $entityManager->getRepository(Pais::class)->find(1);
                      $estado= $entityManager->getRepository(Estado::class)->find(24);
                      $instrumentoUsuario->setIdUser($user);
                      $instrumentoUsuario->setEstadoId($estado);
                      $instrumentoUsuario->setPaisId($pais);
                      $instrumentoUsuario->setRespondida(0);
                      $instrumentoUsuario->setFechaAsignacion(new \DateTime());
                      $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                        if($empresa)
                           $instrumentoUsuario->setIdempresa($empresa);
                      $entityManagerInstrumento->persist($instrumentoUsuario);
                      $entityManagerInstrumento->flush();
                  }


                //enviar notificaciones usuarios 
                //$arrnotifica = array('destinatary' => $valorUser["email"], 'message' => 'Agregado en el evento "'.$data["title"].'"  Fecha: '. $FecInc .' Hora: ' . $HorInc .' - '. $HorFin );
                
                $arrnotifica = array('destinatary' => $valorUser["email"], 'message' => 'Ha sido invitado al evento "'.$data["title"].'" el: '. $FecInc .' Desde: ' . $HorInc .' Hasta: '. $HorFin );
                $brochureBroadcast = $pushbroadcast->pushBroadcast($arrnotifica);

            //******************************/
            }
          }

           
            foreach($entity->getCalendarUsers() as $user){     
              $Estanuser[]=array($user->getEmail());                                      
              $entity->removeCalendarUser($user);
              $entityManager->persist($entity);
              $entityManager->flush();
           }

            foreach($data["users"] as $valorUser){

                foreach($entity->getCalendarUsers() as $acreditacionItems){             
                    foreach($acreditacionItems->getItemAcreditacions() as $items){             
                        $entity->removeItemAcreditacion($items);
                        $entityManager->persist($entity);
                        $entityManager->flush();
                    }
                }
    
                $calendarUser = new CalendarUser();
                $calendarUser->setEmail($valorUser["email"]);
                $calendarUser->setIdCalendar($entity);
                $entityManagerUser->persist($calendarUser);
                $entityManagerUser->flush();
                if(isset($data["accreditationItems"])){
                    foreach($data["accreditationItems"] as $valorItems){
                        $item = new ItemAcreditacion();
                        $item->setIdTipoITem(!is_null($valorItems["id"])?$entityManager->getRepository(TipoItem::class)->find($valorItems["id"]):null);
                        $item->setCantidad(!is_null($valorItems["quantity"])?$valorItems["quantity"]:0);
                        $item->setIdAcreditacion($calendarUser);
                        $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                        if($empresa)
                           $item->setIdempresa($empresa);
                        $entityManagerUser->persist($item);
                        $entityManagerUser->flush();
                    }
                }

                $fechcomvinc=date(strtotime($data["start"]));
                $FecInc = date("d-m-Y", $fechcomvinc);
                $FecInc = $this->fechaCastellano($FecInc);


                $fechcomvfin=date(strtotime($data["end"]));
                $FecFin = date("d-m-Y", $fechcomvfin);
                $HorInc = date("h:i", $fechcomvinc);
                $HorFin = date("h:i", $fechcomvfin);
                //enviar notificaciones usuarios 
                //$arrnotifica = array('destinatary' => $valorUser["email"], 'message' => 'Ha sido actualizado el evento ' . $data["title"] .' del dÃ­a: '.'" Fecha: '. $FecInc .' Hora: ' . $HorInc .' - '. $HorFin );
                $arrnotifica = array('destinatary' => $valorUser["email"], 'message' => 'Ha sido actualizado el evento "'.$data["title"].'" el: '. $FecInc .' Desde: ' . $HorInc .' Hasta: '. $HorFin );
                $brochureBroadcast = $pushbroadcast->pushBroadcast($arrnotifica);
                //******************************/
            }

                


            foreach($dataCelndarUser as $claveResult2=>$user){     
                $clave = false;
                $emailelimn = $user["email"];

                $sqlrecr = " SELECT * FROM calendar_user where email='".$user["email"]."' and id_calendar_id=".$id." ";
                $conn = $this->getEntityManager()->getConnection();
                $stmt = $conn->prepare($sqlrecr);
                $stmt->execute();
                //$dataCelndarUser=$stmt->fetchAll();
                $dataCelndUser=$stmt->fetchAll();
                if(count($dataCelndUser)<1){
                    $clave =true;
                   

                    $user = $entityManager->getRepository(User::class)->findBy(array('email' => $user["email"]));
                    $Idusers =0;
                    foreach($user as $valorUserDime){
                        $Idusers = $valorUserDime->getId();
                    }

                   //Desvincular usuarios retirado del evento *************************** 
                   $sql = "DELETE FROM instrumento_usuario where id_user_id=".$Idusers." and  id_instrumento_id=".$valorinstrumentCap." ";
                   $conn = $this->getEntityManager()->getConnection();
                   $stmt = $conn->prepare($sql);
                   $stmt->execute();

                   //********************************************************************

                   //enviar notificaciones usuarios 
                   $arrnotifica = array('destinatary' => $emailelimn, 'message' => 'Ha sido retirado del evento "' . $data["title"] .'" el: '. $FecInc .' Desde: ' . $HorInc .' Hasta: '. $HorFin );
                   $brochureBroadcast = $pushbroadcast->pushBroadcast($arrnotifica);   
                }
            }

            return new JsonResponse(['msg'=>'Registro Actualizado','id'=>$entity->getId()],200);
        }    
    }




    public function findById($id){
        $entityManager = $this->getEntityManager();        
        $eventData =$entityManager->getRepository(CalendarEvent::class)->find($id);
  
        // $eventData= $entity->select("a,q")
        //     ->from("App\Entity\CalendarioPluggin\CalendarEvent","a")
        //     ->leftJoin('a.calendarUsers', 'q')
        //     ->where("a.id >=".$id)
        //     ->orderBy('a.id', 'ASC')
        //     ->getQuery()
        //     ->getResult();
        $dataCalendar=null;
        if($eventData!=null){        
            $calendarDto =new CalendarEventOutDto();
            $calendarDto->id=$eventData->getId();
            $calendarDto->start=$eventData->getStart()->format("Y-m-d H:i:s");
            $calendarDto->end=$eventData->getEnd()->format("Y-m-d H:i:s");         
            $calendarDto->title=$eventData->getTitle();
            $calendarDto->description=$eventData->getDescription();
            $calendarDto->url=$eventData->getUrl();
            $calendarDto->classNames= $eventData->getClassNames();
            $calendarDto->updatedBy=$eventData->getUpdatedBy();
            $calendarDto->ownerEvent=!is_null($eventData->getCreatedBy())?$eventData->getCreatedBy():null;
         
            $users=[];
            foreach($eventData->getCalendarUsers() as $valorUser){
                $users[]= $valorUser->getEmail();            
            }
            $calendarDto->calendarUsers=$users;
            $dataCalendar[]=$calendarDto;  
        
        }
       return new JsonResponse(['data'=>$dataCalendar],200);
    }


    public function findByIdAcreditacion($id){
        $entityManager = $this->getEntityManager();        
        $eventData =$entityManager->getRepository(CalendarEvent::class)->find($id);
  
        $dataCalendar=null;
        if($eventData!=null){        
            $calendarDto =new CalendarEventOutDto();
            $calendarDto->id=$eventData->getId();
            $calendarDto->start=$eventData->getStart()->format("Y-m-d H:i:s");
            $calendarDto->end=$eventData->getEnd()->format("Y-m-d H:i:s");         
            $calendarDto->title=$eventData->getTitle();
            $calendarDto->description=$eventData->getDescription();
            $calendarDto->url=$eventData->getUrl();
            $calendarDto->acreditacion=$eventData->getAcreditacion();
            $calendarDto->classNames= $eventData->getClassNames();
            $calendarDto->updatedBy=$eventData->getUpdatedBy();
            $calendarDto->ownerEvent=!is_null($eventData->getCreatedBy())?$eventData->getCreatedBy():null;         
            $users=[];
            foreach($eventData->getCalendarUsers() as $valorUser){
                $user = $entityManager->getRepository(User::class)->findBy(array("email"=>$valorUser->getEmail()));
                if($user!=null){
                    $dataAcreditacionItem=[];
                    foreach($valorUser->getItemAcreditacions() as $itemAcreditacion){
                        $dataAcreditacionItem[]=array(
                                "idTipo"=>$itemAcreditacion->getIdTipoITem()->getId(),
                                "description"=>$itemAcreditacion->getIdTipoITem()->getDescripcion(),
                                "Cantidad"=>$itemAcreditacion->getCantidad(),
                        );
                    }

                    $respondidaIntrumentoCap=0;
                    $datainstrumentocap=array();
                    $datainstrumentoevaluacion=array();

                    $sql ="SELECT calendar_event.id,calendar_event.start,calendar_event.end,calendar_event.title,calendar_event.description,
                    calendar_event.acreditacion,instrumento_usuario.respondida,instrumento_usuario.id_user_id,intrumento_cap_acreditacion.instrument_cap_id_id 
                    FROM calendar_event 
                    INNER JOIN intrumento_cap_acreditacion ON calendar_event.id = intrumento_cap_acreditacion.id_calendar_event_id 
                    INNER JOIN instrumento_usuario ON intrumento_cap_acreditacion.instrument_cap_id_id = instrumento_usuario.id_instrumento_id 
                    where calendar_event.id=".$id." and instrumento_usuario.id_user_id=".$user[0]->getId()." and instrumento_usuario.respondida="."1"." "; 
                    $conn = $this->getEntityManager()->getConnection();
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $dataintrumento_cap =$stmt->fetchAll();
                    if($dataintrumento_cap!=null){
                       $respondidaIntrumentoCap=1;

                     $sqlcap ="SELECT * FROM instrumento_captura 
                     WHERE id=".$dataintrumento_cap[0]["instrument_cap_id_id"]." ";
                     $connCAP = $this->getEntityManager()->getConnection();
                     $stmt = $connCAP->prepare($sqlcap);
                     $stmt->execute();
                     $dIntrumento_cap =$stmt->fetchAll();
                        foreach($dIntrumento_cap as $clave=>$valor){
                            $hol = $valor["id"];
                           //$valor->getFechaVigencia()->format("Y-m-d");
                            $instrumentocapturaDto =new InstrumentoCapturaaOutPutDto;
                            $instrumentocapturaDto->id=$valor["id"];
                            $instrumentocapturaDto->nombre=$valor["nombre"];
                            $instrumentocapturaDto->descripcion=$valor["descripcion"];
                            $instrumentocapturaDto->idTipoUnidad=$valor["id_tipo_unidad_id"];
                            //$instrumentocapturaDto->unidad=$valor->getUnidad();
                            //$instrumentocapturaDto->path=$valor->getPath();
                            $instrumentocapturaDto->puntosGlobales= $valor["puntos_globales"];
                            $datainstrumentocap[]=$instrumentocapturaDto;

                        }



                    }

                    $respondidainstrumento_eva=0;
                    $sql = "SELECT calendar_event.id,evaluacion_usuario.respondida,evaluacion_usuario.id_user_id,instrumento_eva_acreditacion.instrument_eva_id_id 
                    FROM calendar_event 
                    INNER JOIN instrumento_eva_acreditacion ON calendar_event.id = instrumento_eva_acreditacion.id_calendar_event_id 
                    INNER JOIN evaluacion_usuario ON instrumento_eva_acreditacion.instrument_eva_id_id = evaluacion_usuario.id_evaluacion_id 
                    where calendar_event.id=".$id." and evaluacion_usuario.id_user_id=".$user[0]->getId()." and evaluacion_usuario.respondida="."1"." "; 
                    $conn = $this->getEntityManager()->getConnection();
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $dataintrumento_evaluacion =$stmt->fetchAll();
                    if($dataintrumento_evaluacion!=null){
                        $respondidainstrumento_eva=1;

                        $sqlcap ="SELECT * FROM evaluacion 
                        WHERE id=".$dataintrumento_evaluacion[0]["instrument_eva_id_id"]." ";
                        $connEVA = $this->getEntityManager()->getConnection();
                        $stmt = $connEVA->prepare($sqlcap);
                        $stmt->execute();
                        $dIntrumento_cap =$stmt->fetchAll();
                        foreach($dIntrumento_cap as $clave=>$valor){
                            $hol = $valor["id"];
                           //$valor->getFechaVigencia()->format("Y-m-d");
                            $instrumentoevaluacionDto =new EvaluacionOutPutDto;
                            $instrumentoevaluacionDto->id=$valor["id"];
                            $instrumentoevaluacionDto->nombre=$valor["nombre"];
                            $instrumentoevaluacionDto->descripcion=$valor["descripcion"];
                            $instrumentoevaluacionDto->idTipoUnidad=$valor["id_tipo_unidad_id"];
                            //$instrumentocapturaDto->unidad=$valor->getUnidad();
                            //$instrumentocapturaDto->path=$valor->getPath();
                            $instrumentoevaluacionDto->puntosGlobales= $valor["puntos_globales"];
                            $datainstrumentoevaluacion[]=$instrumentoevaluacionDto;

                        }    

                    }
                    
                    $users[]= array("email"=>$user[0]->getEmail(),"primer_nombre"=>$user[0]->getPrimerNombre(),"primer_apellido"=>$user[0]->getPrimerApellido(),"cedula"=>$user[0]->getNumeroDocumento()
                    ,"accreditationItems"=>$dataAcreditacionItem,"userId"=>$user[0]->getId(),"respondidaIntrumentoCap"=>$respondidaIntrumentoCap,"intrumentoCap"=>$datainstrumentocap,"respondidaInstrumentoEva"=>$respondidainstrumento_eva,"instrumentoEva"=>$datainstrumentoevaluacion);            

                }
            }
            $calendarDto->calendarUsers=$users;
            $dataCalendar[]=$calendarDto;  
        
        }
       return new JsonResponse(['data'=>$dataCalendar],200);
    }


    public function findByIdAcreditacionAndUser($id,$iduser){
        $entityManager = $this->getEntityManager();        
        $user = $entityManager->getRepository(User::class)->find($iduser);

        $entity= $this->getEntityManager()->createQueryBuilder();

        $eventData= $entity->select("a,q,f")
            ->from("App\Entity\CalendarioPluggin\CalendarEvent","a")
            ->leftJoin('a.calendarUsers', 'q')
            ->leftJoin('q.itemAcreditacions', 'f')
            ->where("a.id =".$id)
            ->andWhere("q.Email ='".$user->getEmail()."'")
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult()[0];

        $dataCalendar=null;
        if($eventData!=null){        
            $calendarDto =new CalendarEventOutDto();
            $calendarDto->id=$eventData->getId();
            $calendarDto->start=$eventData->getStart()->format("Y-m-d H:i:s");
            $calendarDto->end=$eventData->getEnd()->format("Y-m-d H:i:s");         
            $calendarDto->title=$eventData->getTitle();
            $calendarDto->description=$eventData->getDescription();
            $calendarDto->url=$eventData->getUrl();
            $calendarDto->acreditacion=$eventData->getAcreditacion();
            $calendarDto->classNames= $eventData->getClassNames();
            $calendarDto->updatedBy=$eventData->getUpdatedBy();
            $calendarDto->ownerEvent=!is_null($eventData->getCreatedBy())?$eventData->getCreatedBy():null;         
            $users=[];
            foreach($eventData->getCalendarUsers() as $valorUser){
                $user = $entityManager->getRepository(User::class)->findBy(array("email"=>$valorUser->getEmail()));
                if($user!=null){
                    $dataAcreditacionItem=[];
                    foreach($valorUser->getItemAcreditacions() as $itemAcreditacion){
                        $dataAcreditacionItem[]=array(
                                "idTipo"=>$itemAcreditacion->getIdTipoITem()->getId(),
                                "description"=>$itemAcreditacion->getIdTipoITem()->getDescripcion(),
                                "Cantidad"=>$itemAcreditacion->getCantidad(),
                                "Usado"=>$itemAcreditacion->getUsado()==null?0:1,
                                "Id"=>$itemAcreditacion->getId()

                        );
                    }
                    $users= array("email"=>$user[0]->getEmail(),"primer_nombre"=>$user[0]->getPrimerNombre(),"primer_apellido"=>$user[0]->getPrimerApellido(),"cedula"=>$user[0]->getNumeroDocumento()
                    ,"accreditationItems"=>$dataAcreditacionItem,"userId"=>$user[0]->getId());            

                }
            }
            $calendarDto->calendarUsers=$users;
            $dataCalendar[]=$calendarDto;  
        
        }
       return new JsonResponse(['data'=>$dataCalendar],200);
    }



    public function findByRange($data,$acreditacion){
  
        $entityManager = $this->getEntityManager();        
        $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
        $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
  
        $entity= $this->getEntityManager()->createQueryBuilder();
       if($acreditacion==1){ 
        $eventData= $entity->select("a,q")
            ->from("App\Entity\CalendarioPluggin\CalendarEvent","a")
            ->leftJoin('a.calendarUsers', 'q')
            ->where("a.start >='".$data["start"]."'")
            ->andwhere('a.idempresa ='.$empresa->getId())
            ->andWhere("a.end<='".$data["end"]."'")
            ->andWhere("a.acreditacion=".$acreditacion)
            ->andWhere("a.createdBy='".$currentUser->getEmail()."' or q.Email='".$currentUser->getEmail()."'")
            //->orderBy('a.id', 'ASC')
            ->orderBy('a.start', 'ASC')
            ->getQuery()
            ->getResult();
        }else{
 
         $eventData= $entity->select("a,q")
            ->from("App\Entity\CalendarioPluggin\CalendarEvent","a")
            ->leftJoin('a.calendarUsers', 'q')
            ->where("a.start >='".$data["start"]."'")
            ->andwhere('a.idempresa ='.$empresa->getId())
            ->andWhere("a.end<='".$data["end"]."'")
            //->andWhere("a.acreditacion=".$acreditacion)
            ->andWhere("a.createdBy='".$currentUser->getEmail()."' or q.Email='".$currentUser->getEmail()."'")
            //->orderBy('a.id', 'ASC')
            ->orderBy('a.start', 'ASC')
            ->getQuery()
            ->getResult();
        }

        $dataCalendar=null;
        $users=[];
        //$users=array();
        //$users=null;
        $calendarDto=[];
        foreach($eventData as $clave=>$valor){
            //if($valor->getAcreditacion()==1 and $valor->getCreatedBy() !=  $currentUser->getEmail()){ 
            if($acreditacion==1 ){     
                $calendarDto =new CalendarEventOutDto();
                $calendarDto->id=$valor->getId();
                $calendarDto->start=$valor->getStart()->format("Y-m-d H:i:s");
                $calendarDto->end=$valor->getEnd()->format("Y-m-d H:i:s");         
                $calendarDto->title=$valor->getTitle();
                $calendarDto->description=$valor->getDescription();
                $calendarDto->url=$valor->getUrl();
                $calendarDto->acreditacion=$valor->getAcreditacion();
                $calendarDto->classNames= $valor->getClassNames();
                $calendarDto->updatedBy=$valor->getUpdatedBy();            
                $calendarDto->ownerEvent=!is_null($valor->getCreatedBy())?$valor->getCreatedBy():null;
                $sql="SELECT a.id,c.* FROM 
                calendar_event a inner join calendar_user b 
                on a.id = b.id_calendar_id
                INNER join item_acreditacion c on b.id = c.id_acreditacion_id 
                where a.id =".$calendarDto->id." and c.usado=1;";
                $conn = $this->getEntityManager()->getConnection();
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $result=$stmt->fetchAll();
                if(count($result)>0){
                    $calendarDto->editable=0;
                }else{
                    $calendarDto->editable=1;
                }

                /* foreach($valor->getCalendarUsers() as $valorUser){
                    $user = $entityManager->getRepository(User::class)->findBy(array("email"=>$valorUser->getEmail()));
                    if($user!=null){
                        $users[]= array("email"=>$user[0]->getEmail(),"primer_nombre"=>$user[0]->getPrimerNombre(),"primer_apellido"=>$user[0]->getPrimerApellido());            
                    }
                }
                $calendarDto->calendarUsers=$users; */
                $dataCalendar[]=$calendarDto;
            }else if ($acreditacion==0) {
             //si no
                //if($valor->getAcreditacion()==0){ 
                if($valor->getAcreditacion()==1 and $valor->getCreatedBy() !=  $currentUser->getEmail()){ 
                    $calendarDto =new CalendarEventOutDto();
                    $calendarDto->id=$valor->getId();
                    $calendarDto->start=$valor->getStart()->format("Y-m-d H:i:s");
                    $calendarDto->end=$valor->getEnd()->format("Y-m-d H:i:s");         
                    $calendarDto->title=$valor->getTitle();
                    $calendarDto->description=$valor->getDescription();
                    $calendarDto->url=$valor->getUrl();
                    $calendarDto->acreditacion=$valor->getAcreditacion();
                    $calendarDto->classNames= $valor->getClassNames();
                    $calendarDto->updatedBy=$valor->getUpdatedBy();            
                    $calendarDto->ownerEvent=!is_null($valor->getCreatedBy())?$valor->getCreatedBy():null;
                    $sql="SELECT a.id,c.* FROM 
                    calendar_event a inner join calendar_user b 
                    on a.id = b.id_calendar_id
                    INNER join item_acreditacion c on b.id = c.id_acreditacion_id 
                    where a.id =".$calendarDto->id." and c.usado=1;";
                    $conn = $this->getEntityManager()->getConnection();
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $result=$stmt->fetchAll();
                    if(count($result)>0){
                        $calendarDto->editable=0;
                    }else{
                        $calendarDto->editable=1;
                    }
                    /* foreach($valor->getCalendarUsers() as $valorUser){
                        $user = $entityManager->getRepository(User::class)->findBy(array("email"=>$valorUser->getEmail()));
                        if($user!=null){
                            $users[]= array("email"=>$user[0]->getEmail(),"primer_nombre"=>$user[0]->getPrimerNombre(),"primer_apellido"=>$user[0]->getPrimerApellido());            
                        }
                    }
                    $calendarDto->calendarUsers=$users; */
                    $dataCalendar[]=$calendarDto;

                }else if($valor->getAcreditacion()==0){ 
                    $calendarDto =new CalendarEventOutDto();
                    $calendarDto->id=$valor->getId();
                    $calendarDto->start=$valor->getStart()->format("Y-m-d H:i:s");
                    $calendarDto->end=$valor->getEnd()->format("Y-m-d H:i:s");         
                    $calendarDto->title=$valor->getTitle();
                    $calendarDto->description=$valor->getDescription();
                    $calendarDto->url=$valor->getUrl();
                    $calendarDto->acreditacion=$valor->getAcreditacion();
                    $calendarDto->classNames= $valor->getClassNames();
                    $calendarDto->updatedBy=$valor->getUpdatedBy();            
                    $calendarDto->ownerEvent=!is_null($valor->getCreatedBy())?$valor->getCreatedBy():null;
                    $sql="SELECT a.id,c.* FROM 
                    calendar_event a inner join calendar_user b 
                    on a.id = b.id_calendar_id
                    INNER join item_acreditacion c on b.id = c.id_acreditacion_id 
                    where a.id =".$calendarDto->id." and c.usado=1;";
                    $conn = $this->getEntityManager()->getConnection();
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $result=$stmt->fetchAll();
                    if(count($result)>0){
                        $calendarDto->editable=0;
                    }else{
                        $calendarDto->editable=1;
                    }
                    /* foreach($valor->getCalendarUsers() as $valorUser){
                        $user = $entityManager->getRepository(User::class)->findBy(array("email"=>$valorUser->getEmail()));
                        if($user!=null){
                            $users[]= array("email"=>$user[0]->getEmail(),"primer_nombre"=>$user[0]->getPrimerNombre(),"primer_apellido"=>$user[0]->getPrimerApellido());            
                        }
                    }
                    $calendarDto->calendarUsers=$users; */
                    $dataCalendar[]=$calendarDto;

                }
              }
        }
        return new JsonResponse(['data'=>$dataCalendar],200);
    }

    public function findByIdAcreditacionEvent($data){
        $entityManager = $this->getEntityManager();        
        $user = $entityManager->getRepository(User::class)->find($data["userId"]);
        $emaill = $user->getEmail();
        $arrdatra = explode("|", $data["ids"]);
        $id=0;
        $dataCalendar=null;
        $ide=0;
        $emaill="";
      foreach($arrdatra as $clave=>$valor){
        $id = $valor;            
        $entityManager = $this->getEntityManager();        
        $user = $entityManager->getRepository(User::class)->find($data["userId"]);
            $entity= $this->getEntityManager()->createQueryBuilder();
             $eventData= $entity->select("a,q,f")
            ->from("App\Entity\CalendarioPluggin\CalendarEvent","a")
            ->leftJoin('a.calendarUsers', 'q')
            ->leftJoin('q.itemAcreditacions', 'f')
            ->where("a.id =".$id)
            ->andWhere("q.Email ='".$user->getEmail()."'")
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult()[0];

        //$dataCalendar=null;
        if($eventData!=null){        
            $calendarDto =new CalendarEventOutDto();
            $calendarDto->id=$eventData->getId();
            $calendarDto->start=$eventData->getStart()->format("Y-m-d H:i:s");
            $calendarDto->end=$eventData->getEnd()->format("Y-m-d H:i:s");         
            $calendarDto->title=$eventData->getTitle();
            $calendarDto->description=$eventData->getDescription();
            $calendarDto->url=$eventData->getUrl();
            $calendarDto->acreditacion=$eventData->getAcreditacion();
            $calendarDto->classNames= $eventData->getClassNames();
            $calendarDto->updatedBy=$eventData->getUpdatedBy();
            $calendarDto->ownerEvent=!is_null($eventData->getCreatedBy())?$eventData->getCreatedBy():null;         
            $users=[];
            foreach($eventData->getCalendarUsers() as $valorUser){
                $user = $entityManager->getRepository(User::class)->findBy(array("email"=>$valorUser->getEmail()));
                if($user!=null){
                    $dataAcreditacionItem=[];
                    foreach($valorUser->getItemAcreditacions() as $itemAcreditacion){
                        $dataAcreditacionItem[]=array(
                                "idTipo"=>$itemAcreditacion->getIdTipoITem()->getId(),
                                "description"=>$itemAcreditacion->getIdTipoITem()->getDescripcion(),
                                "Cantidad"=>$itemAcreditacion->getCantidad(),
                                "Usado"=>$itemAcreditacion->getUsado()==null?0:1,
                                "Id"=>$itemAcreditacion->getId()

                        );
                    }
                    $users= array("email"=>$user[0]->getEmail(),"primer_nombre"=>$user[0]->getPrimerNombre(),"primer_apellido"=>$user[0]->getPrimerApellido(),"cedula"=>$user[0]->getNumeroDocumento()
                    ,"accreditationItems"=>$dataAcreditacionItem,"userId"=>$user[0]->getId());            

                }
            }
            $calendarDto->calendarUsers=$users;
            $dataCalendar[]=$calendarDto;  
        
        }
       }
      
       return new JsonResponse(['data'=>$dataCalendar],200);
    }


    public function listUser(){
        $entityManagerDefault = $this->getEntityManager();
        $empresa= $entityManagerDefault->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());

        $entityManager = $this->getEntityManager();        
        $entity= $this->getEntityManager()->createQueryBuilder();
        $userData= $entity->select("a")
            ->from("App\Entity\User","a")
            ->where("a.idStatus=1")
            ->andwhere('a.idempresa ='.$empresa->getId())
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult();
        $dataUsers=null;
        foreach($userData as $clave=>$valor){
            $userDto =new UsersOutDto();
            $userDto->id=$valor->getId();
            $userDto->email=$valor->getEmail();
            $userDto->nombre=$valor->getPrimerNombre();            
            $dataUsers[]=$userDto;              
        }
        return new JsonResponse(['data'=>$dataUsers],200);
    }

    /**
     * Delete Event.
     */
    public function delete($id,$validator,$helper,$pushbroadcast): JsonResponse  
    {
        $entityManager = $this->getEntityManager();
        $entity =$entityManager->getRepository(CalendarEvent::class)->find($id);
        $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());

        $titulo = $entity->getTitle();
        $useronner = $entity->getCreatedBy();
        $fechahr1 = strtotime($entity->getStart()->format("d-m-Y H:i"));
        $fechahr1 = date('d-m-Y H:i', $fechahr1);
        
        $fechahr2 = strtotime($entity->getEnd()->format("d-m-Y H:i"));
        $fechahr2 = date('d-m-Y H:i', $fechahr2);

        $emall =  $currentUser->getEmail();

        $FecIncv = strtotime($entity->getStart()->format("d-m-Y H:i"));
        $FecInc = date("d-m-Y", $FecIncv);
        $FecInc = $this->fechaCastellano($FecInc);

        $HorInc = date("h:i", $FecIncv);

        $FecFincv = strtotime($entity->getEnd()->format("d-m-Y H:i"));
        $FecFin = date("d-m-Y", $FecFincv);
        $HorFin = date("h:i", $FecFincv);        
               

        if (!$entity) {
            return new JsonResponse(['msg'=>'No existen Registros con el id: '.$id],404);  
        }

        if($currentUser->getEmail()==$entity->getCreatedBy()){

               //enviar notificaciones usuarios 
                $sql = " SELECT *  FROM `calendar_user` where id_calendar_id=".$id." "; 
                $conn = $this->getEntityManager()->getConnection();
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $dataitems=$stmt->fetchAll();    
                foreach($dataitems as $clave=>$valor){
                    $arrnotifica = array('destinatary' => $valor["email"], 'message' => 'El evento "'.$titulo.'" fue cancelado '.' el: '. $FecInc .' Desde: '. $HorInc .' Hasta: ' . $HorFin );
                    $brochureBroadcast = $pushbroadcast->pushBroadcast($arrnotifica);
                }
                foreach($entity->getCalendarUsers() as $acreditacionItems){             
                    foreach($acreditacionItems->getItemAcreditacions() as $items){             
                        $acreditacionItems->removeItemAcreditacion($items);
                        $entityManager->persist($entity);
                        $entityManager->flush();
                    }
                }
                $entityManager->remove($entity);
                $entityManager->flush();    
        }else{
            $userEmail =$entityManager->getRepository(CalendarUser::class)->findBy(array("Email"=>$currentUser->getEmail()));
            if(is_array($userEmail)){
                //verififcar si este usuario se esta eliminando y el mensaje de ser enviado Onner
                $arrnotifica = array('destinatary' => $useronner, 'message' => ucfirst($currentUser->getPrimerNombre()).' '. ucfirst($currentUser->getPrimerApellido()). ' cancelo su asistencia al evento "' . $titulo .'" el: '. $FecInc .' Desde: '. $HorInc .' Hasta: ' . $HorFin );
                $brochureBroadcast = $pushbroadcast->pushBroadcast($arrnotifica);
                //******************************/
                $emaill = $userEmail[0]->getEmail();

                $sql = " SELECT *  FROM `calendar_user` where email='".$emaill."' AND id_calendar_id=".$id." ";
                $conn = $this->getEntityManager()->getConnection();
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $dataitems=$stmt->fetchAll();    
                $idcalendar_user = $dataitems[0]["id"];
                $sql = "DELETE FROM item_acreditacion where id_acreditacion_id=".$idcalendar_user." ";
                $conn = $this->getEntityManager()->getConnection();
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $sql = "DELETE FROM calendar_user where email='".$emaill."' AND id_calendar_id=".$id." ";
                $conn = $this->getEntityManager()->getConnection();
                $stmt = $conn->prepare($sql);
                $stmt->execute();
            }   
        }
        return new JsonResponse(['msg'=>'Registro Eliminado: '.$id],200);
        
    }

    public function fechaCastellano ($fecha) {
        $fecha = substr($fecha, 0, 10);
        $numeroDia = date('d', strtotime($fecha));
        $dia = date('l', strtotime($fecha));
        $mes = date('F', strtotime($fecha));
        $anio = date('Y', strtotime($fecha));
        $dias_ES = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
        $dias_EN = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
        $nombredia = str_replace($dias_EN, $dias_ES, $dia);
        $meses_ES = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
        $meses_EN = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
        $nombreMes = str_replace($meses_EN, $meses_ES, $mes);
        //return $nombredia." ".$numeroDia." de ".$nombreMes." de ".$anio;
        return $numeroDia." de ".$nombreMes." de ".$anio;
      }

     /**
     * Create postqr.
     */
    public function postqr($data,$validator,$helper,$email,$pushbroadcast,$Fpdfreporte): JsonResponse  {

        $entityManager = $this->getEntityManager();
        $entityManagerUser =$this->getEntityManager();
        $entity=$helper->setParametersToEntity(new CalendarEvent(),$data);
        $entity->setStart(new \DateTime($data["start"]));
        $entity->setEnd(new \DateTime($data["end"]));

         $fechcomvinc=date(strtotime($data["start"]));
            
            $FecInc = date("d-m-Y", $fechcomvinc);
            $FecInc = $this->fechaCastellano($FecInc);
            
            $fechcomvfin=date(strtotime($data["end"]));
            $FecFin = date("d-m-Y", $fechcomvfin);
            $HorInc = date("h:i", $fechcomvinc);
            $HorFin = date("h:i", $fechcomvfin);

        $errors = $validator->validate($entity);
        if($errors->count() > 0){
            $errorsString = (string) $errors;
            return new JsonResponse(['msg'=>$errorsString],500);
        }else{
            $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
            $entity->setCreatedBy($currentUser->getUserName());
            $entity->setUpdatedBy($currentUser->getUserName());
            //$entityManager->persist($entity);
            //$entityManager->flush();
            foreach($data["users"] as $valorUser){
                //$calendarUser = new CalendarUser();
                //$calendarUser->setEmail($valorUser["email"]);
                //$calendarUser->setIdCalendar($entity);
                //$entityManagerUser->persist($calendarUser);
                //$entityManagerUser->flush();
                /* if(isset($data["accreditationItems"])){
                    foreach($data["accreditationItems"] as $valorItems){
                        $item = new ItemAcreditacion();
                        $item->setIdTipoITem(!is_null($valorItems["id"])?$entityManager->getRepository(TipoItem::class)->find($valorItems["id"]):null);
                        $item->setCantidad(!is_null($valorItems["quantity"])?$valorItems["quantity"]:0);
                        $item->setIdAcreditacion($calendarUser);
                        $entityManagerUser->persist($item);
                        $entityManagerUser->flush();
                    }
                } */

                //JSON.stringify({ idEvent: eventDetail.id, userId: user.userId }) 

                //$datosqr = "Estimado Sr(a): " . $valorUser["email"] .  " Fecha Inicio =" .$data["start"] .  " Fecha Inicio =" .$data["end"] ."Usted ha sido Invitado al Evento ".utf8_decode($entity->getTitle())." ".utf8_decode($entity->getDescription()); 

                //$datosqr = array('idEvent:' => 1, 'userId:' => 2668);
                //$em =$this->getDoctrine()->getManager();
                //$data = json_decode($request->getContent(),true);
                $correoUser = $valorUser["email"];
                $user = $entityManager->getRepository(User::class)->findBy(array('email' => $correoUser));
                $Idusers =0;
                foreach($user as $valorUserDime){
                    $Idusers = $valorUserDime->getId();
                }
                

                
                //$user = $this->getDoctrine()->getRepository(User::class)->findBy(array('email' => $valorUser["email"]));

                $datosqr = json_encode(['idEvent:'=>1,'userId:'=>$Idusers]);


                //$datosqr = JSON.stringify({ idEvent: eventDetail.id, userId: user.userId }); 
                $brochureBroadcast = $Fpdfreporte->pushCod_QR_Correo($datosqr);

                sleep(30);
                //$arrnotifica = array('destinatary' => $valorUser["email"], 'message' => 'Ha sido invitado al evento "'.$data["title"].'" el: '. $FecInc .' Desde: ' . $HorInc .' Hasta: '. $HorFin );
                //$brochureBroadcast = $pushbroadcast->pushBroadcast($arrnotifica);
                $email->enviocorreo_qr(array("email"=>$valorUser["email"]),"Estimado Sr(a):".$valorUser["email"]."<br><br>Usted ha sido Invitado al Evento ".utf8_decode($entity->getTitle())."<br><br>".utf8_decode($entity->getDescription())."<br><br> Fecha Inicio:".$entity->getStart()->format("d/m/Y H:i")."<br>Fecha Finalizacion:".$entity->getEnd()->format("d/m/Y H:i"),$entity->getTitle() );
            }
            return new JsonResponse(['msg'=>'Registro Creado','id'=>$entity->getId()],200);
        }    
    }


    /**
     * Create postqr.
     */
    public function findByIdQr($id,$Fpdfreporte): JsonResponse  {

        //$datosqr = json_encode(['idEvent:'=>$id,'userId:'=>2660]);
        //$datosqr = JSON.stringify({ idEvent: eventDetail.id, userId: user.userId }); 
        $brochureBroadcast = $Fpdfreporte->pushCod_QR_Correo($id);
        //$brochureBroadcast = $Fpdfreporte->pushCod_QR_Correo($datosqr);
        return new JsonResponse(['data'=>"Listo QR"],200);       

    }
    

    public function clonar($id){

        $em = $this->getEntityManager();        
        $entityManager = $this->getEntityManager();
        $entity= $this->getEntityManager()->createQueryBuilder();
        $eventUsers= $this->getEntityManager()->createQueryBuilder();
        $acreditacionEntity= $this->getEntityManager()->createQueryBuilder();
        $acreditacionItem= $this->getEntityManager()->createQueryBuilder();



        $eventData= $entity->select("a,f")
        ->from("App\Entity\CalendarioPluggin\CalendarEvent","a")
        ->leftjoin('a.calendarUsers', 'f')
        ->andWhere('a.id='.$id)
        ->orderBy('a.id', 'ASC')
        ->getQuery()
        ->getResult();

        $new_entity = clone $eventData[0];
        $em->persist($new_entity);
        $em->flush();

        $eventUsersData= $eventUsers->select("a")
            ->from("App\Entity\CalendarioPluggin\CalendarUser","a")
            ->Where('a.idCalendar='.$id)
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult();
            if(count($eventUsersData)>=0){
                foreach($eventUsersData as $users){
                    $new_user = clone $users;
                    $new_user->setIdCalendar($new_entity);
                    $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                        if($empresa)
                        $new_user->setIdempresa($empresa);
                    $em->persist($new_user); 
                    $em->flush();   
                    
                    if($users){
                    
                        if($new_user->getId()){
                            $acreditacionEntity= $this->getEntityManager()->createQueryBuilder();
                            $itemsAcreditacion=  $acreditacionEntity->select("a")
                            ->from("App\Entity\Acreditacion\ItemAcreditacion","a")
                            ->Where('a.idAcreditacion='.$users->getId())
                            ->getQuery()
                            ->getResult();
                            if(count($itemsAcreditacion)>=0){
                                foreach($itemsAcreditacion as $itemAcre){
                                    $new_itemAcreditacion = clone $itemAcre;
                                    $new_itemAcreditacion->setIdAcreditacion($new_user);
                                    $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                                    if($empresa)
                                      $new_itemAcreditacion->setIdempresa($empresa);
                                    $em->persist($new_itemAcreditacion); 
                                    $em->flush();   
                                }
                            }        
                        }

                    }
                }
            }
    } 


    public function validaUsers($data){
        $entityManagerDefault = $this->getEntityManager();
        $empresa= $entityManagerDefault->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
        $entityManager = $this->getEntityManager();          
        $eventUsers= $this->getEntityManager()->createQueryBuilder();
        $usuariosRegistrados=array();
        $usuariosNoRegistrados=array();
        foreach($data["users"] as $item){
            $eventUsers= $this->getEntityManager()->createQueryBuilder();
            $user= $eventUsers->select("a")
            ->from("App\Entity\User","a")
            ->Where("a.email='".$item."'")
            ->andWhere('a.idempresa ='.$empresa->getId())
            ->getQuery()
            ->getResult();
            if(count($user)>0){
                $usuariosRegistrados[] =array("id"=>$user[0]->getId(),"cedula"=>$user[0]->getNumeroDocumento(),"correo"=>$user[0]->getEmail(),"fullname"=>$user[0]->getPrimerNombre()." ".$user[0]->getPrimerApellido());
            }else{
                $usuariosNoRegistrados[]=array($item);
            }           
        }
        return new JsonResponse(['data'=>array("usuariosregistrados"=>$usuariosRegistrados,"usuariosnoregistrados"=>$usuariosNoRegistrados)],200);
    }

    public function printAcreditation($data){


        $ids = implode(",",explode("|", $data["ids"]));

        $entityManager = $this->getEntityManager();        
        $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
        $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
  
        $entity= $this->getEntityManager()->createQueryBuilder();

        $eventData= $entity->select("a,q")
            ->from("App\Entity\CalendarioPluggin\CalendarEvent","a")
            ->leftJoin('a.calendarUsers', 'q')
            ->where("a.id IN (".$ids.")")
            ->orderBy('a.start', 'ASC')
            ->getQuery()
            ->getResult();

       $calendarDto=[];
       $users=[];
       foreach($eventData as $clave=>$valor){
            $calendarDto =new CalendarEventOutDto();
            $calendarDto->id=$valor->getId();
            $calendarDto->start=$valor->getStart()->format("Y-m-d H:i:s");
            $calendarDto->end=$valor->getEnd()->format("Y-m-d H:i:s");         
            $calendarDto->title=$valor->getTitle();
            $calendarDto->description=$valor->getDescription();
            $calendarDto->url=$valor->getUrl();
            $calendarDto->classNames= $valor->getClassNames();
            $calendarDto->updatedBy=$valor->getUpdatedBy();            
            $calendarDto->acreditacion=$valor->getAcreditacion();
            $calendarDto->ownerEvent=$valor->getCreatedBy();
            foreach($valor->getCalendarUsers() as $valorUser){
                $user = $entityManager->getRepository(User::class)->findBy(array("email"=>$valorUser->getEmail()));
                if($user!=null){
                    $existingUserKey = array_search($valorUser->getEmail(), $users);
                    $posicion = array_search($valorUser->getEmail(), array_column($users, 'email'));
                    if ($posicion !== false) {
                        $users[$posicion]["events"][] = ["id" => $valor->getId()]; 

                    }else{
                        $users[]= array(
                            "email"=>$user[0]->getEmail(),
                            "primer_nombre"=>$user[0]->getPrimerNombre(),
                            "primer_apellido"=>$user[0]->getPrimerApellido(),
                            "numero_documento"=>$user[0]->getNumeroDocumento(),
                            "userId"=>$user[0]->getId(),
                            "respondidaIntrumentoCap"=> 0,
                            "intrumentoCap"=> [],
                            "respondidaInstrumentoEva"=> 0,
                            "instrumentoEva"=> [],                
                            "events"=>array(array("id"=>$valor->getId()))
                        );

                    }            
                }
            }
            $dataCalendar[]=$calendarDto;
       } 


        return new JsonResponse(['events'=>$dataCalendar,'calendarUsers'=>$users],200);


    }


    function userExists($email, $userList) {
        $emails = array_column($userList, 'email');
        return in_array($email, $emails);
    }

}