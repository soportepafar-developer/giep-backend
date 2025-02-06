<?php



// src/Command/ExampleCommand.php

namespace App\Command;

use App\Entity\Proyecto\Proyecto;

Use App\Entity\User;

use App\Controller\CalendarioPluggin\CalendarEvent;



use Symfony\Component\Console\Command\Command;

use Symfony\Component\Console\Input\InputInterface;

use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Console\Style\SymfonyStyle;

use Symfony\Component\Security\Core\Security;

use Symfony\Component\Validator\Validator\ValidatorInterface;

use App\Service\Notification;

use  App\Service\Correo;

use App\Service\Fpdfreporte;

use App\Repository\CalendarioPluggin\CalendarEventRepository;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

use Symfony\Component\HttpFoundation\JsonResponse;



// 1. Import the ORM EntityManager Interface

use Doctrine\ORM\EntityManagerInterface;

use Doctrine\Persistence\ManagerRegistry;

class CorreoLotesCommand  extends Command

{

    // the name of the command (the part after "bin/console")

    protected static $defaultName = 'app:CorreoLotes';

    private $security;

    

    // 2. Expose the EntityManager in the class level

    private $entityManager;

    private $doctrine;

    private $email;

    private $pushbroadcast;

    private $Fpdfreporte;

    private $CalendarEvent;

    private $params;



    public function __construct(EntityManagerInterface $entityManager,Security $security,ValidatorInterface $validator, Correo $email, Notification $pushbroadcast, Fpdfreporte $Fpdfreporte, CalendarEventRepository $CalendarEvent, ParameterBagInterface $params, ManagerRegistry $doctrine)

    {

        // 3. Update the value of the private entityManager variable through injection

        $this->entityManager = $entityManager;

        $this->doctrine = $doctrine;

        $this->email = $email;

        $this->pushbroadcast = $pushbroadcast;

        

        $this->Fpdfreporte = $Fpdfreporte;



        $this->CalendarEvent = $CalendarEvent;

        $this->params = $params;



        $this->security = $security;

        $this->validator = $validator;

        parent::__construct();

    }

    

    protected function configure()

    {

        // ...

    }



    // 4. Use the entity manager in the command code ...

    protected function execute(InputInterface $input, OutputInterface $output)

    {

        $io = new SymfonyStyle($input, $output);



        $sqlbus1 = " SELECT a.id, a.email, a.id_calendar_event, b.title, b.description, b.start, b.end, a.swenvio FROM `calendar_event_correo_notif` a 

            inner join calendar_event b on a.id_calendar_event = b.id where swenvio=0 or swenvio=2 LIMIT 1;";

        

        //$sqlbus1 = " SELECT *  FROM calendar_event_correo_notif where swenvio=0 LIMIT 5 order by id ASC"; 

        $conn =  $this->entityManager->getConnection();

        $stmt = $conn->prepare($sqlbus1);

        $stmt->execute();

        $databus1=$stmt->fetchAll();

        $esDiaFeriado=false;

        $ip=0;

        $fechaes = date("Y-m-d");

        $fecha1 = $fechaes;

        $fecha = strtotime($fecha1);

        $dia=date("w", strtotime($fechaes));

        $fechag= date("Y-m-d h:i:s");

        if(count($databus1)>=1){

        foreach($databus1 as $claveResult2=>$valorResultbusc){

                

                /* $fech_fincompara1 = strtotime($valorResultbusc["fechainicio"]);

                $fech_inicocompara = date('Y-m-d', $fech_fincompara1);

                $fech_inicocompara = strtotime($fech_inicocompara); */

                

                /*$em = $this->doctrine->getManager('notification');

                $emailbusc=$valorResultbusc["email"];

                $sqlbusnotf = " SELECT * FROM `user` where mail='$emailbusc';";

                $conn123 =  $em->getConnection();

                $stmtnotf = $conn123->prepare($sqlbusnotf);

                $stmtnotf->execute();

                $datauserbus=$stmtnotf->fetchAll();

                if(count($datauserbus)>=1){

                }else{

                    //$fechag=(new \DateTime());

                    $fechag= date("Y-m-d h:i:s");

                    $sqlrecr = " INSERT INTO user (mail, id_state, created_at, 	updated_at) VALUES ('".$emailbusc."',1,'".$fechag."','".$fechag."') ";

                    $connuser1 =  $em->getConnection();

                    $stmtuserinst = $connuser1->prepare($sqlrecr);

                    $stmtuserinst->execute();

                }*/



                $fechcomvinc=date(strtotime($valorResultbusc["start"]));

            

                $FecInc = date("d-m-Y", $fechcomvinc);

                $FecInc = $this->fechaCastellano($FecInc);

            

                $fechcomvfin=date(strtotime($valorResultbusc["end"]));

                $FecFin = date("d-m-Y", $fechcomvfin);

                $HorInc = date("h:i", $fechcomvinc);

                $HorFin = date("h:i", $fechcomvfin);



                $fechahorainic = $FecInc .' '.$HorInc;

                $fechahorafin = $FecFin .' '.$HorFin;



                $arrnotifica = array('destinatary' => $valorResultbusc["email"], 'message' => 'Ha sido invitado al evento "'.$valorResultbusc["title"].'" el: '. $FecInc .' Desde: ' . $HorInc .' Hasta: '. $HorFin );

                $brochureBroadcast = $this->pushbroadcast->pushBroadcast($arrnotifica);

                

                sleep(30);

 

                $correoUser = $valorResultbusc["email"];

                $user = $this->entityManager->getRepository(User::class)->findBy(array('email' => $correoUser));

                $Idusers =0;

                foreach($user as $valorUserDime){

                    $Idusers = $valorUserDime->getId();

                }

                

                $id_calendar_event = intval($valorResultbusc["id_calendar_event"]);



                $datosqr = json_encode(['idEvent'=>$id_calendar_event,'userId'=>$Idusers]);

                

                //$datosqr = json_encode(['idEvent'=>$valorResultbusc["id_calendar_event"],'userId'=>$Idusers]);

              if($valorResultbusc["swenvio"]==0){

                //llamar controlador ***************************************

                $datotoken = json_encode(['username'=>"baezgregoric@gmail.com",'password'=>"12345678",'username'=>"baezgregoric@gmail.com"]);

                $tokenpafar ="";

                //$data = json_decode($request->getContent(),true);

                //$url = "http://localhost/giep/public/api/login_check";

                $url = "https://bofficegiepstage.pafar.com.ve/public/api/login_check";

                $ch = curl_init();

                //$authorization = "Authorization: Bearer ".$tokenbancocredicard; // **Prepare Autorisation Token**

                //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // **Inject Token into Header**

                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); // **Inject Token into Header**

                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                curl_setopt($ch, CURLOPT_POSTFIELDS,$datotoken);

                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

                curl_setopt($ch, CURLOPT_URL,$url);

                $result=curl_exec($ch);

                curl_close($ch);

                $tokenpafar = json_decode($result);

                foreach($tokenpafar as $valorUserDime){

                    //$Idusers = $valorUserDime->getId();

                    $tokenpafar = $valorUserDime;

                }



                $id=225;

                //$url = "http://localhost/giep/public/api/calendario/event/qr/". $datosqr;

                $url = "https://bofficegiepstage.pafar.com.ve/public/api/calendario/event/qr/". $datosqr;



                $ch = curl_init();

                //$tokenpafar="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE3MTE1Njg2MDIsImV4cCI6MTcxMTU4MTIwMiwicm9sZXMiOlsiUk9MRV9BRE1JTklTVFJBRE9SIiwiUk9MRV9TVEFFWFBFRCIsIlJPTEVfU1RBRVhQRURfUkVQT1JUUyJdLCJ1c2VybmFtZSI6InZhbGVyaWViYWV6LnRyYWJham9AZ21haWwuY29tIn0.Uk1SyBCKOwW6H8ObBHtkASK6Z1cssz2SUNTxon6CzzkXyr6gpJO8MRtuhyClKMBF50qfhWOUnyl2rbOaPXkvJlhSx-iVj2COPuF7Hlmt_YFvrrONEfS2BexBKEzUQ234xak48B2iHO5wnFAGYq1Jy2D6BpuziwdQJ0Pm87BjR-LSDvhXsQEhCouX6Ruc7ciSQ67arunedAxvF6gH55A_r-oRhNMKvDuqRZ6wo4QPncjNXEnJdxkUPgO9pi3LYqvuw-7UFwwRX5HDTC53hkYrNSqagUcm0NXGQ7cY2YmycZ8mh3y0dJcam_rDl97g9DxQT4H4CcacoQkQ2G4lIszdMQ-OF6W_QvlQsE0LJVWtWNoFDsyXowalmTRtS4HQ31e57J8hikh9iN--j_JuCYhfuvloYZgwmA1rZXlS2EzgH7yQDyhCNpsATML-J_hCoMBMrmjyqtDUfMtE4RYKlzZfK5j1GRd3yPEVBALKfMXN0snknmjo3sRb8xHAj1vUZeMrUrlsp2ClD-Gda5Ds2IUTDJjWYZGUjzdr95hqRmFbP28xWDFDLg-SNNpxRowZOxHpYYjnjOi7UP8AbTsKAFcENgoo3zcASxquEKzFBZiDQDFJ805Q2RiaqTGIneU9WDacWcMyuynES9guvETYtdMD7bhAcDW5JraWdljlTEwD7Wk";

                $authorization = "Authorization: Bearer ".$tokenpafar; // **Prepare Autorisation Token**

                //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); // **Inject Token into Header**

                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // **Inject Token into Header**

                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                //curl_setopt($ch, CURLOPT_POSTFIELDS,$numtarjeta);

                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

                curl_setopt($ch, CURLOPT_URL,$url);

                $result=curl_exec($ch);

                curl_close($ch);

                //return  new JsonResponse(json_decode($result, true));



                //******************************************************** */

               }

                //$this->CalendarEvent

                //$this->CalendarEvent->findByIdQr($datosqr,$this->Fpdfreporte);



                //$brochureBroadcast = $this->Fpdfreporte->pushCod_QR_Correo($datosqr);



                sleep(30);

                //$email->enviocorreo_qr(array("email"=>$valorUser["email"]),"Estimado Sr(a):".$valorUser["email"]."<br><br>Usted ha sido Invitado al Evento ".utf8_decode($entity->getTitle())."<br><br>".utf8_decode($entity->getDescription())."<br><br> Fecha Inicio:".$entity->getStart()->format("d/m/Y H:i")."<br>Fecha Finalizacion:".$entity->getEnd()->format("d/m/Y H:i"),$entity->getTitle() );

               if($valorResultbusc["swenvio"]==0){

                    $this->email->enviocorreo_qr(array("email"=>$valorResultbusc["email"]),"Estimado Sr(a):".$valorResultbusc["email"]."<br><br>Usted ha sido Invitado al Evento ".utf8_decode($valorResultbusc["title"])."<br><br>".utf8_decode($valorResultbusc["description"])."<br><br> Fecha Inicio:".$fechahorainic."<br>Fecha Finalizacion:".$fechahorafin,$valorResultbusc["title"]); 

               }else{

                   $this->email->enviocorreo_calendar(array("email"=>$valorResultbusc["email"]),"Estimado Sr(a):".$valorResultbusc["email"]."<br><br>Usted ha sido Invitado al Evento ".utf8_decode($valorResultbusc["title"])."<br><br>".utf8_decode($valorResultbusc["description"])."<br><br> Fecha Inicio:".$fechahorainic."<br>Fecha Finalizacion:".$fechahorafin,$valorResultbusc["title"]); 

               }

                    $sql2 = "update calendar_event_correo_notif set swenvio=1, fecha_entrega='".$fechag."' where id =".$valorResultbusc["id"]." ";

                    $conn2 = $this->entityManager->getConnection();

                    $stmt2 = $conn2->prepare($sql2);

                    $stmt2->execute();  



                sleep(60);



         }

          $io->success('Registro actualizado con exito.'.$fechaes);

          return Command::SUCCESS;

        }else{

            return 1;

        }      

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



}