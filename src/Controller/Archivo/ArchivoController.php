<?php
namespace App\Controller\Archivo;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Archivo\Archivos;
use App\Entity\User;
use App\Repository\UserRepository;
use  App\Service\FileUploader;
use App\Service\Notification;
use App\Repository\Archivo\ArchivosRepository;

use App\Dto\Archivo\ArchivosOutPutDto;

use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Helper;
use Symfony\Component\Validator\Constraints\Json;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\MimeType\FileinfoMimeTypeGuesser;
use Symfony\Component\Mime\MimeTypes;

class ArchivoController extends AbstractController
{

    private $params;

    public function __construct(ParameterBagInterface $params)

    {
        $this->params = $params;

    }


        /**
        * @Route("/api/archivo/pagined", methods={"POST"})
        * @OA\Post(
         * summary="Archivo Pagined",
         * description="Archivo Pagined",
         * operationId="ArchivoAll",
         * tags={"Archivo"},
         * @OA\RequestBody(
         *    required=true,
         *    description="parametro",
         *    @OA\JsonContent(
         *       required={"page"},
         *       @OA\Property(property="page", type="integer", format="integer", example="1"),
         *       @OA\Property(property="rowByPage", type="integer", format="integer", example="1"),
         *       @OA\Property(property="word", type="integer", format="integer", example="El Informe"),
         *    ),
         * ),
         * @OA\Response(
         *    response=422,
         *    description="Wrong credentials response",
         *    @OA\JsonContent(
         *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
         *        )
         *     )
         * )
    */    
    public function findAll(Request $request,ArchivosRepository $archivorepository): JsonResponse
    {
        $param = json_decode($request->getContent(),true);
        $data = $archivorepository
        ->findAllPage($param);
         return $data;  
    }

    

    /**

         * @Route("/api/archivo/upload/archivo", methods={"POST"})

         * @OA\Post(

         * summary="upload Archivo",

         * description="upload Archivo",

         * operationId="uploadarchivo",

         * tags={"Archivo"},

         *      @OA\RequestBody(

         *         @OA\MediaType(

         *             mediaType="multipart/form-data",
        *             @OA\Schema(
         *                 @OA\Property(description="archivos",property="archivo",type="string",format="binary",),
         *                 @OA\Property(property="titulo", type="string", format="string", example="El titulo del archivo"),
         *                 @OA\Property(property="nombre_original", type="string", format="string", example="informe"),
         *                 @OA\Property(property="descripcion_archivo", type="string", format="string", example="Este es archivo informenes"),
         *                 @OA\Property(property="tamano", type="integer", format="integer", example="512"),
         *                 @OA\Property(property="publico", type="integer", format="integer", example="1"),
         *                 @OA\Property(property="id_limited_bloqueo", type="integer", format="integer", example="3"),
         *                 @OA\Property(property="users", type="array", @OA\Items(type="array",@OA\Items()), example={}),
         *                 @OA\Property(property="hashtag", type="array", @OA\Items(type="array",@OA\Items()), example={}),
         *                 @OA\Property(property="nemotecnico", type="string", format="string", example="133-20230202041029"),
         *                 @OA\Property(property="comentarios", type="string", format="string", example="Subiendo archivos en pafar"),
         *             )

         *         )

         *     ),

         * @OA\Response(

         *    response=422,

         *    description="Archivo Incorrecto",

         *    @OA\JsonContent(

         *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")

         *        )

         *     )

         * )

    */
     public function uploadArchivo(Request $request,ValidatorInterface $validator,FileUploader $fileUploader,UserRepository $repository,Notification $pushbroadcast,Helper $helper): JsonResponse
    {
        if ($request->get('nemotecnico')=='Null' or $request->get('nemotecnico')=='null' ) {

        $file = $request->files->get('archivo');
         $nombmsg = $file->getClientOriginalName();
        $repositoryvv = $this->getDoctrine()->getRepository(Archivos::class);
        $respexte = $repositoryvv->postextension($file,$validator,$helper); 
        if ($respexte!=0){
       //    $resptamano= $repositoryvv->posttamanoarchivospermitidos($file,$validator,$helper); 
       // if ($request->get('tamano') > $resptamano){
            
        $DateAndTime = date('his', time());
        $tipoestado = 1;
        $arr = array('titulo' => $request->get('titulo'), 'nombre_original' => $request->get('nombre_original'), 'descripcion_archivo' => $request->get('descripcion_archivo'), 'tamano' => $request->get('tamano'), 'publico' => $request->get('publico'), 'idestado' => $tipoestado, 'id_limited_bloqueo' => $request->get('id_limited_bloqueo'));
        //$arr = array('titulo' => $request->get('titulo'), 'nombre_original' => $request->get('nombre_original'), 'descripcion_archivo' => $request->get('descripcion_archivo'), 'tamano' => $request->get('tamano'), 'publico' => $request->get('publico'), 'idestado' => $request->get('idestado'), 'id_limited_bloqueo' => $request->get('id_limited_bloqueo'));
        $arrusers = array('users' => $request->get('users'));
        $data = json_encode($arr,true);
        $data = json_decode($data,true);

        $arrhashtag = $request->get('hashtag');

        /* $arrusers = json_encode($arrusers,true); */
        $arrusers = json_decode($request->get('users'),true);

        
        //$titulo = $request->get('titulo');
        //$user = $request->get('users');

        $em =$this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $repository = $this->getDoctrine()->getRepository(User::class);

        $userpmo = $DateAndTime;
        $nemotecnicopmo = $userpmo .'-' .date("Y") . date("m") . date("d") . $DateAndTime;
        $brochureFileName = $fileUploader->uploadwritedocument($file,"/".'archivos/giep/writedocumentos',$nemotecnicopmo);
        if ($brochureFileName!=false) {
        //$extde = $file->getMimeType();
        //$extension= $file->guessExtension();
            if ($file) {
                $repository = $this->getDoctrine()->getRepository(Archivos::class);
                $nemotecnico = $repository->post($data,$respexte,$arrusers,$arrhashtag,$file,$validator,$helper); 
                $arrayvalida=explode("#", $nemotecnico);
                $nemotecnico = $arrayvalida[0];
                $brochureFileName = $fileUploader->uploaddocument($file,"/".'archivos/giep/documentos',$nemotecnico);

                $dime = $arrayvalida[1];
                $arrnotifica = array('destinatary' => $dime, 'message' => 'Ha sido agregado como colaborador al documento ' . $nombmsg );
                //$arrnotifica = array('destinatary' => $dime, 'message' => 'Ha sido agregado como colaborador al documento '.$request->get('nombre_original'));
                $brochureBroadcast = $pushbroadcast->pushBroadcast($arrnotifica);
                return new JsonResponse(['msg'=>'Archivo cargado con exito'],200);
            }else{
                return new JsonResponse("{error:Error cargando archivo}",409);  
            }
        
        }else{
            return new JsonResponse(['error'=>'Error cargando archivo'],409);
        }
         //el tamaño del archivo no esta permitido
          
       /*  }else{
            return new JsonResponse(['error'=>'Error el tamaño del archivo no permitido'],409);
        } */

    }else{
        return new JsonResponse(['error'=>'Error extension de archivo no permitida'],409);
    }
    }else{
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $file = $request->files->get('archivo');
            $extens = $file->getClientOriginalExtension();
            $nomb = $file->getClientOriginalName();
            $nombmsg = $file->getClientOriginalName();
            
            
            $arrayarch=explode("-", $nomb); 

            $arrayarch=explode("-", $request->get('nemotecnico')); 

            $arrayarchnemotecnico=explode(".", $nomb); 
            $idarchiv = $arrayarch[0];
            //$idarchiv = $arrayarch[1];

            $repositoryvv = $this->getDoctrine()->getRepository(Archivos::class);
            $respexte = $repositoryvv->postnemotecnicoversion($idarchiv,$validator,$helper); 
            $arrayarchvalida=explode("#", $respexte); 

            if ($respexte!=false){
                if ($request->get('nemotecnico')==$arrayarchvalida[0]){
                    //los nemotecnico son iguales
                     $hh="si esta";
                     if ($arrayarchvalida[2]==1 and $arrayarchvalida[1]==$user->getId()){
                        //persitir data listo debloqueo y subir archivos
                        $compfilesubida = $fileUploader->metadata_file($file,$arrayarch[0],$request->get('nemotecnico'));
                        $compfilesubidaf=explode("/..", $compfilesubida); 
                        $compfilesubida=$compfilesubidaf[1];
                        $compfile = $fileUploader->metadata_filef('..'.$compfilesubida,'../public'.$arrayarchvalida[3]);                            
                        if ($compfile==false){
                           //persiste datos
                           $respact = $repositoryvv->postactualizarversion($idarchiv,$arrayarchvalida[3],$arrayarchvalida[1],$extens,$request->get('comentarios'),$validator,$helper);

                           $targetPath="../public/metadata/".$request->get('nemotecnico').'/'.$request->get('nemotecnico').'.'.$extens;
                           copy($targetPath,'../public/archivos/giep/documentos/'.$respact.'.'.$extens);


                           $dime = $arrayarchvalida[4];
                           $arrnotifica = array('destinatary' => $dime, 'message' => 'El documento '.$nombmsg . ' a sido actualizado');
                           $brochureBroadcast = $pushbroadcast->pushBroadcast($arrnotifica);

                           //return new JsonResponse(['msg'=>'El arhivo '.$respact.'.'.$extens .' fue actualizado'],200);    
                           return new JsonResponse(['msg'=>'El arhivo '. $nombmsg .' fue actualizado'],200);    
                        }else{   
                            //$respact = $repositoryvv->postactualizarversion($idarchiv,$arrayarchvalida[3],$arrayarchvalida[1],$extens,$validator,$helper);
                            //$targetPath="../public/metadata/".$arrayarchnemotecnico[0].'/'.$nomb;
                            //copy($targetPath,'../public/archivos/giep/documentos/'.$respact.'.'.$extens);
                            //return new JsonResponse(['msg'=>'El arhivo '.$request->get('nemotecnico').'.'.$extens .' ya se encuentra actualizado'],409);    
                            return new JsonResponse(['msg'=>'El arhivo '. $nombmsg .' ya se encuentra actualizado'],409);    
                        }
                     }else{
                       return new JsonResponse(['msg'=>'Hay conflicto de versiones, realice el pull de documento nuevamente.'],409);    
                     }
                }else{
                    //el nemotecnico no coincide con este archivo verifique   
                    return new JsonResponse(['msg'=>'El archivo que intenta subir no existe'],409);
                }
            }else{
                //no exite en base
                return new JsonResponse(['msg'=>'El archivo que intenta subir no existe'],409);
            }
        //return new JsonResponse(['msg'=>'En construcción para archivos versionados'],200);
    }   

}

/**
        * Get an Archivo by Id.
        * @Route("/api/archivo/{id}", methods={"GET"})
        * @OA\Post(
         * summary="Archivos List",
         * description="Archivos List",
         * operationId="Archivoslist",
         * tags={"Archivo"},
         * @OA\RequestBody(
         *    required=true,
         *    description="parametro",
         *    @OA\JsonContent(
         *       required={"page"},
         *       @OA\Property(property="page", type="integer", format="integer", example="1"),
         *       @OA\Property(property="rowByPage", type="integer", format="integer", example="1"),
         *       @OA\Property(property="word", type="integer", format="integer", example="1"),
         *    ),
         * ),
         * @OA\Response(
         *    response=422,
         *    description="Wrong credentials response",
         *    @OA\JsonContent(
         *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
         *        )
         *     )
         * )
         * @OA\Tag(name="Archivo")
         * @Security(name="Bearer")
    */   
    public function findById($id,Request $request,ArchivosRepository $archivosRepository): JsonResponse
    {
        $data = $archivosRepository->getArchivosById($id);
        //$data = $archivosRepository->postextensionpruebas("docx");
        return $data;  
    }   

    /**
        * Get an Archivo by Id.
        * @Route("/api/archivo/download/file/{id}", methods={"GET"})
        * @OA\Post(
         * summary="download File",
         * description="download File",
         * operationId="downloadfile",
         * tags={"Archivo"},
         * @OA\RequestBody(
         *    required=true,
         *    description="parametro",
         *    @OA\JsonContent(
         *       required={"page"},
         *       @OA\Property(property="page", type="integer", format="integer", example="1"),
         *       @OA\Property(property="rowByPage", type="integer", format="integer", example="1"),
         *       @OA\Property(property="word", type="integer", format="integer", example="1"),
         *    ),
         * ),
         * @OA\Response(
         *    response=422,
         *    description="Wrong credentials response",
         *    @OA\JsonContent(
         *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
         *        )
         *     )
         * )
         * @OA\Tag(name="Archivo")
         * @Security(name="Bearer")
    */   

    public function downloadFiles($id,Request $request,ValidatorInterface $validator,FileUploader $fileUploader,ArchivosRepository $archivosRepository): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $idfile = $id;
        $dircctoy = $this->params->get('photos_directory');
        //$brochureFileName = $fileUploader->download($file,"/".'archivos/giep/documentos',$nemotecnico);  
        $data = $archivosRepository->getBuscarArchivosById($idfile,$dircctoy,$user);
        $datazip = json_decode($data->getContent(),true);

        $archivo = $datazip['nemotecnico'].'.'.$datazip['extension'];
        $archivo1 = $datazip['filenemotecnico'];
        $imgbinary = fread(fopen($archivo1, "r"), filesize($archivo1));
        $brochureFileName = $fileUploader->download($archivo,$datazip['title']);  

        
        //return New JsonResponse(["file"=>$base64_arch,"title"=>$datazip['title'].'-'.$nomborgf[0],"extension"=>$nomborgf[1],"nemotecnico"=>$valor->getNemotecnico(),"filenemotecnico"=>$urles]); 
        $imgbinary = fread(fopen($brochureFileName, "r"), filesize($brochureFileName));
        $filetype = "zip";
        if ($filetype=='docx') {
            $base64_arch = 'data:@file/vnd.openxmlformats-officedocument.wordprocessingml.document'.';base64,' . base64_encode($imgbinary);
        }else if ($filetype=='doc') {
            $base64_arch = 'data:@file/msword;base64,' . base64_encode($imgbinary);    
        }else if ($filetype=='pptx') {
            $base64_arch = 'data:@file/vnd.openxmlformats-officedocument.presentationml.presentation'.';base64,' . base64_encode($imgbinary);
        }else if ($filetype=='ppt') {
            $base64_arch = 'data:@file/vnd.ms-powerpoint'.';base64,' . base64_encode($imgbinary);
        }else if ($filetype=='xlsx') {
            $base64_arch = 'data:@file/octet-stream'.';base64,' . base64_encode($imgbinary);
        }else if ($filetype=='xls') {
            $base64_arch = 'data:@file/msexcel'.';base64,' . base64_encode($imgbinary);
        }else if ($filetype=='png') {
            $base64_arch = 'data:image/png'.';base64,' . base64_encode($imgbinary);    
        }else if ($filetype=='jpg') {
            $base64_arch = 'data:image/jpeg'.';base64,' . base64_encode($imgbinary);        
        }else if ($filetype=='jpeg') {
            $base64_arch = 'data:image/jpeg'.';base64,' . base64_encode($imgbinary);        
        }else if ($filetype=='gif') {
            $base64_arch = 'data:image/gif'.';base64,' . base64_encode($imgbinary);            
        }else if ($filetype=='mp4') {
            $base64_arch = 'data:image/mp4'.';base64,' . base64_encode($imgbinary);            
        }else if ($filetype=='ods') {
            $base64_arch = 'data:@file/vnd.oasis.opendocument.spreadsheet'.';base64,' . base64_encode($imgbinary);                
        }else if ($filetype=='odp') {
            $base64_arch = 'data:@file/vnd.oasis.opendocument.presentation'.';base64,' . base64_encode($imgbinary);                    
        }else if ($filetype=='zip') {
          $base64_arch = 'data:@file/zip;base64,' . base64_encode($imgbinary);                    
        }else{
            $base64_arch = 'data:@file/'. $filetype .';base64,' . base64_encode($imgbinary);
        }

        //$fichero = $brochureFileName;
        /* if (file_exists($fichero)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($fichero).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($fichero));
            readfile($fichero);
        } */
        if ($data) {
            return New JsonResponse(["file"=>$base64_arch,"title"=>$datazip['title'],"extension"=>$filetype,"nemotecnico"=>$datazip['nemotecnico']]); 
            //return New JsonResponse(["file"=>$base64_arch,"title"=>$nomorg[0].'-'.$nomborgf[0],"extension"=>$nomborgf[1],"nemotecnico"=>$valor->getNemotecnico(),"filenemotecnico"=>$urles]); 

            //return new JsonResponse(['Listo'=>'Listo'],200);
            //return $data;
            //return $brochureFileName;  
        }else{
            return new JsonResponse(['msg'=>'No existen Registros'],409);  
        }   
    }

          /**
        * @Route("/api/archivo/actualizar/{id}", methods={"PUT"})
        * @OA\Put(
         * summary="Put Archivos",
         * description="Update Archivos",
         * operationId="updateArchivos",
         * tags={"Archivo"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Archivo",
         *    @OA\JsonContent(
         *       required={"nombre"},
         *       @OA\Property(property="titulo", type="string", format="string", example="El Informe mariano modificado"),
         *       @OA\Property(property="descripcion_archivo", type="string", format="string", example="Informe Parfar"),
         *       @OA\Property(property="hashtag", type="array", @OA\Items(type="array",@OA\Items()), example={"accidente","reporte"}), 
         *    ),
         * ),
         * @OA\Response(
         *    response=422,
         *    description="Wrong credentials response",
         *    @OA\JsonContent(
         *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
         *        )
         *     )
         * )
    */
    public function put($id,Request $request,ValidatorInterface $validator,Helper $helper): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(),true);
            $em =$this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository(Archivos::class);
            return $repository->put($data,$id,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


       /**
        * Get an Archivo by Id.
        * @Route("/api/metadataarchivo/{id}", methods={"GET"})
        * @OA\Post(
         * summary="Archivos List",
         * description="Archivos List",
         * operationId="Archivoslist",
         * tags={"Archivo"},
         * @OA\RequestBody(
         *    required=true,
         *    description="parametro",
         *    @OA\JsonContent(
         *       required={"page"},
         *       @OA\Property(property="page", type="integer", format="integer", example="1"),
         *       @OA\Property(property="rowByPage", type="integer", format="integer", example="1"),
         *       @OA\Property(property="word", type="integer", format="integer", example="1"),
         *    ),
         * ),
         * @OA\Response(
         *    response=422,
         *    description="Wrong credentials response",
         *    @OA\JsonContent(
         *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
         *        )
         *     )
         * )
         * @OA\Tag(name="Archivo")
         * @Security(name="Bearer")
    */   
    public function metadata($id,Request $request,ArchivosRepository $archivosRepository,FileUploader $fileUploader): JsonResponse
    {
        $dirfile = 'archivos/giep/documentos/Actividad.docx';
        $brochureFileName = $fileUploader->metadata_file($dirfile);  
        //return $brochureFileName;  
        return New JsonResponse(["metadata"=>$brochureFileName]); 
        
      
    }   


       /**
        * @Route("/api/archivo/admindesbloqueoarchivos/{id}", methods={"PUT"})
        * @OA\Put(
         * summary="Put Archivos",
         * description="Update Desbloqueo de Archivos",
         * operationId="updateDesbloqueoArchivos",
         * tags={"Archivo"},
         * @OA\RequestBody(
         *    required=true,
         *    description="parametro",
          *    @OA\JsonContent(
         *       required={"page"},
         *       @OA\Property(property="page", type="integer", format="integer", example="1"),
         *       @OA\Property(property="rowByPage", type="integer", format="integer", example="1"),
         *       @OA\Property(property="word", type="integer", format="integer", example="1"),
         *    ),
         * ),
         * @OA\Response(
         *    response=422,
         *    description="Wrong credentials response",
         *    @OA\JsonContent(
         *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
         *        )
         *     )
         * )
    */
    public function putadmindesbloqueoarchivos($id,Request $request,ValidatorInterface $validator,Helper $helper): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(),true);
            $em =$this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository(Archivos::class);
            return $repository->putadmindesbloqueoarchivos($data,$id,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }

    /**
        * @Route("/api/archivo/cambiostatusarchivo", methods={"PUT"})
        * @OA\Put(
         * summary="Put Archivos Status",
         * description="Update Archivos Status",
         * operationId="updateArchivosStatus",
         * tags={"Archivo"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Archivo",
         *    @OA\JsonContent(
         *       required={"id_archivo"},
         *       @OA\Property(property="id_archivo", type="integer", format="integer", example="1"),
         *       @OA\Property(property="id_estado", type="integer", format="integer", example="2"),
         *       @OA\Property(property="comentarios", type="string", format="string", example="Sin Comentarios"),
         *    ),
         * ),
         * @OA\Response(
         *    response=422,
         *    description="Wrong credentials response",
         *    @OA\JsonContent(
         *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
         *        )
         *     )
         * )
    */
    public function putcambiostatusarchivo(Request $request,ValidatorInterface $validator,Helper $helper): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(),true);
            $em =$this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository(Archivos::class);
            return $repository->putcambiostatusarchivo($data,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    } 

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }


}

