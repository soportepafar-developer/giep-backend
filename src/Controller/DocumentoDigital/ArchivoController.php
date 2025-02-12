<?php
namespace App\Controller\DocumentoDigital;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\DocumentoDigital\Archivosd;
use App\Entity\User;
use App\Repository\UserRepository;
use  App\Service\FileUploader;
use App\Service\Notification;
use App\Repository\DocumentoDigital\ArchivosdRepository;

use App\Dto\DocumentoDigital\ArchivosOutPutDto;

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
Use App\Entity\DocumentoDigital\TipoEstadod;

class ArchivoController extends AbstractController
{
    private $params;
    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

        /**
        * @Route("/api/archivodigital/pagined", methods={"POST"})
        * @OA\Post(
         * summary="Archivo Pagined",
         * description="Archivo Pagined",
         * operationId="ArchivoDigitalAll",
         * tags={"Archivos Digital"},
         * @OA\RequestBody(
         *    required=true,
         *    description="parametro",
         *    @OA\JsonContent(
         *       required={"page"},
         *       @OA\Property(property="page", type="integer", format="integer", example="1"),
         *       @OA\Property(property="rowByPage", type="integer", format="integer", example="5"),
         *       @OA\Property(property="word", type="integer", format="integer", example=null),
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
    public function findAll(Request $request,ArchivosdRepository $archivorepository): JsonResponse
    {
        $param = json_decode($request->getContent(),true);
        $em = $this->getDoctrine()->getManager('documentodigital');
        $data = $archivorepository
        ->findAllPage($param,$em);
         return $data;  
    }


    
/**
         * @Route("/api/archivodigital/upload/archivo", methods={"POST"})
         * @OA\Post(
         * summary="upload Archivo",
         * description="upload Archivo",
         * operationId="ArchivoDigitaluploadarchivo",
         * tags={"Archivos Digital"},
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
         *                 @OA\Property(property="folios", type="string", format="string", example="1 - 200"),
         *                 @OA\Property(property="num_dela_caja", type="string", format="string", example="A-15-2024"),   
         *                 @OA\Property(property="Fecha_extrema_inicio", type="datetime", example="2023-12-30"), 
         *                 @OA\Property(property="Fecha_extrema_fin", type="datetime", example="2024-12-30"),  
         *                 @OA\Property(property="id_tipo_almacen", type="integer", format="integer", example="1"), 
         *                 @OA\Property(property="idubica1", type="integer", format="integer", example="1"), 
         *                 @OA\Property(property="idubica2", type="integer", format="integer", example="2"),  
         *                 @OA\Property(property="idubica3", type="integer", format="integer", example="3"),  
         *                 @OA\Property(property="idubica4", type="integer", format="integer", example="4"),  
         *                 @OA\Property(property="codigo_serie_subserie", type="integer", format="integer", example="1"),   
         *                 @OA\Property(property="id_pais", type="integer", format="integer", example="1"),   
         *                 @OA\Property(property="id_estado", type="integer", format="integer", example="1"),    
         *                 @OA\Property(property="id_ciudad", type="integer", format="integer", example="1"),    
         *                 @OA\Property(property="id_estructura_organizativa", type="integer", format="integer", example="1"),     
         *                 @OA\Property(property="asuntos", type="string", format="string", example="Asuntos"), 
         *                 @OA\Property(property="fecha_fin_conservac", type="datetime", example="2022-09-12"),  
         *                 @OA\Property(property="sw_archivo_fisico", type="integer", format="integer", example="0"),      
         *                 @OA\Property(property="argumento_justificacion", type="string", format="string", example="Argumento Justificación"),  
         *                 @OA\Property(property="num_expediente", type="string", format="string", example="AFT-15-2024"),   
         *                 @OA\Property(property="fecha_documento", type="datetime", example="2021-09-12"),   
         *                 @OA\Property(property="cantidad_caja", type="integer", format="integer", example="1"),     
         *                 @OA\Property(property="id_user_entrega", type="integer", format="integer", example="1"),     
         *                 @OA\Property(property="idcontenido_caja", type="integer", format="integer", example="1"),       
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
    public function uploadArchivo(Request $request,ValidatorInterface $validator,FileUploader $fileUploader,ArchivosdRepository $repositoryAechv,Notification $pushbroadcast,Helper $helper): JsonResponse
    {
        if ($request->get('nemotecnico')=='Null' or $request->get('nemotecnico')=='null' ) {

        $file = $request->files->get('archivo');
         $nombmsg = $file->getClientOriginalName();
        $repositoryvv = $this->getDoctrine()->getRepository(Archivosd::class);
        $em = $this->getDoctrine()->getManager('documentodigital');
        $respexte = $repositoryvv->postextension($file,$validator,$helper,$em); 
        if ($respexte!=0){
       //    $resptamano= $repositoryvv->posttamanoarchivospermitidos($file,$validator,$helper); 
       // if ($request->get('tamano') > $resptamano){
            
        $DateAndTime = date('his', time());
        $tipoestado = 1;
        $arr = array('titulo' => $request->get('titulo'), 'nombre_original' => $request->get('nombre_original'), 'descripcion_archivo' => $request->get('descripcion_archivo'), 'tamano' => $request->get('tamano'), 'publico' => $request->get('publico'), 'idestado' => $tipoestado
        , 'id_limited_bloqueo' => $request->get('id_limited_bloqueo'), 'folios' => $request->get('folios'), 'num_dela_caja' => $request->get('num_dela_caja')
        , 'Fecha_extrema_inicio' => $request->get('Fecha_extrema_inicio'), 'Fecha_extrema_fin' => $request->get('Fecha_extrema_fin')
        , 'id_tipo_almacen' => $request->get('id_tipo_almacen'), 'idubica1' => $request->get('idubica1')
        , 'idubica2' => $request->get('idubica2'), 'idubica3' => $request->get('idubica3'), 'idubica4' => $request->get('idubica4')
        , 'idregion' => $request->get('idregion'), 'codigo_serie_subserie' => $request->get('codigo_serie_subserie')
        , 'id_status_tipoestado' => $request->get('id_status_tipoestado'), 'id_pais' => $request->get('id_pais')
        , 'id_estado' => $request->get('id_estado'), 'id_ciudad' => $request->get('id_ciudad')
        , 'id_estructura_organizativa' => $request->get('id_estructura_organizativa'), 'asuntos' => $request->get('asuntos')
        , 'fecha_fin_conservac' => $request->get('fecha_fin_conservac'), 'sw_archivo_fisico' => $request->get('sw_archivo_fisico')
        , 'argumento_justificacion' => $request->get('argumento_justificacion'), 'num_expediente' => $request->get('num_expediente')
        , 'fecha_documento' => $request->get('fecha_documento'), 'cantidad_caja' => $request->get('cantidad_caja')
        , 'id_user_entrega' => $request->get('id_user_entrega')
        , 'idcontenido_caja' => $request->get('idcontenido_caja')

        );



        //$arr = array('titulo' => $request->get('titulo'), 'nombre_original' => $request->get('nombre_original'), 'descripcion_archivo' => $request->get('descripcion_archivo'), 'tamano' => $request->get('tamano'), 'publico' => $request->get('publico'), 'idestado' => $request->get('idestado'), 'id_limited_bloqueo' => $request->get('id_limited_bloqueo'));
        $arrusers = array('users' => $request->get('users'));
        $data = json_encode($arr,true);
        $data = json_decode($data,true);

        $arrhashtag = $request->get('hashtag');

        /* $arrusers = json_encode($arrusers,true); */
        $arrusers = json_decode($request->get('users'),true);

        
        //$titulo = $request->get('titulo');
        //$user = $request->get('users');

        //$em =$this->getDoctrine()->getManager();
        $em = $this->getDoctrine()->getManager('documentodigital');

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $repository = $this->getDoctrine()->getRepository(User::class);

        $userpmo = $DateAndTime;
        $nemotecnicopmo = $userpmo .'-' .date("Y") . date("m") . date("d") . $DateAndTime;
        $brochureFileName = $fileUploader->uploadwritedocument($file,"/".'archivos/giep/writedocumentosdigital',$nemotecnicopmo);
        if ($brochureFileName!=false) {
        //$extde = $file->getMimeType();
        //$extension= $file->guessExtension();
            if ($file) {
                $repositoryAechv = $this->getDoctrine()->getRepository(Archivosd::class,'documentodigital');
                //$em = $this->getDoctrine()->getManager('documentodigital');
                $em = $this->getDoctrine()->getManager('documentodigital');

                $nemotecnico = $repositoryAechv->post($data,$respexte,$arrusers,$arrhashtag,$file,$validator,$helper,$em); 
                $arrayvalida=explode("#", $nemotecnico);
                $nemotecnico = $arrayvalida[0];
                $brochureFileName = $fileUploader->uploaddocument($file,"/".'archivos/giep/documentosdigital',$nemotecnico);

                //$dime = $arrayvalida[1];
                //$arrnotifica = array('destinatary' => $dime, 'message' => 'Ha sido agregado como colaborador al documento ' . $nombmsg );
                //$arrnotifica = array('destinatary' => $dime, 'message' => 'Ha sido agregado como colaborador al documento '.$request->get('nombre_original'));
                //$brochureBroadcast = $pushbroadcast->pushBroadcast($arrnotifica);
                return new JsonResponse(['msg'=>'Archivo cargado con exito'],200);
            }else{
                return new JsonResponse("{error:Error cargando archivo}",409);  
            }
        
        }else{
            return new JsonResponse(['error'=>'Error cargando archivo'],409);
        }
         //el tama�0�9o del archivo no esta permitido
          
       /*  }else{
            return new JsonResponse(['error'=>'Error el tama�0�9o del archivo no permitido'],409);
        } */

    }else{
        return new JsonResponse(['error'=>'Error extension de archivo no permitida'],409);
    }
    }else{
           //Esto es para actualizar documento en esta version de archivos digitalizado no se usa
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

            $repositoryvv = $this->getDoctrine()->getRepository(Archivosd::class);
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


                           //$dime = $arrayarchvalida[4];
                           //$arrnotifica = array('destinatary' => $dime, 'message' => 'El documento '.$nombmsg . ' a sido actualizado');
                           //$brochureBroadcast = $pushbroadcast->pushBroadcast($arrnotifica);

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
        //return new JsonResponse(['msg'=>'En construcci��n para archivos versionados'],200);
    }   

}

/**
        * Get an Archivos Digital by Id.
        * @Route("/api/archivodigital/{id}", methods={"GET"})
        * @OA\Post(
         * summary="Archivos List",
         * description="Archivos List",
         * operationId="ArchivoDigitallist",
         * tags={"Archivos Digital"},
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
         * @OA\Tag(name="Archivos Digital")
         * @Security(name="Bearer")
    */   
    public function findById($id,Request $request,ArchivosdRepository $archivosRepository): JsonResponse
    {
        $em = $this->getDoctrine()->getManager('documentodigital');
        $data = $archivosRepository->getArchivosById($id,$em);
        //$data = $archivosRepository->postextensionpruebas("docx");
        return $data;  
    }   

    /**
        * Get an Archivos Digital by Id.
        * @Route("/api/archivodigital/view/file/{id}", methods={"GET"})
        * @OA\Post(
         * summary="download File",
         * description="download File",
         * operationId="ArchivoDigitaldownloadfile",
         * tags={"Archivos Digital"},
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
         * @OA\Tag(name="Archivos Digital")
         * @Security(name="Bearer")
    */   

    public function downloadFiles($id,Request $request,ValidatorInterface $validator,FileUploader $fileUploader,ArchivosdRepository $archivosRepository): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $idfile = $id;
        $dircctoy = $this->params->get('photos_directory');
        //$brochureFileName = $fileUploader->download($file,"/".'archivos/giep/documentos',$nemotecnico);  
        $em = $this->getDoctrine()->getManager('documentodigital');
        $data = $archivosRepository->getBuscarArchivosById($idfile,$dircctoy,$user,$em);
        $datazip = json_decode($data->getContent(),true);

        
//file ="data:@file/pdf;base64,JVBERi0xLjcKCjQgMCBvYmoKPDwKL0JpdHNQZXJDb21wb25lbnQgOAovQ29sb3JTcGFjZSAvRGV2aWNlUkdCCi9GaWx0ZXIgL0RDVERlY29kZQovSGVpZ2h0IDUKL0xlbmd0aCA4NTUKL1N1YnR5cGUgL0ltYWdlCi9UeXBlIC9YT2JqZWN0Ci9XaWR0aCA4OTgKPj4Kc3RyZWFtCv/Y/+AAEEpGSUYAAQEBAGAAYAAA/9sAQwANCQoLCggNCwsLDw4NEBQhFRQSEhQoHR4YITAqMjEvKi4tNDtLQDQ4RzktLkJZQkdOUFRVVDM/XWNcUmJLU1RR/9sAQwEODw8UERQnFRUnUTYuNlFRUVFRUVFRUVFRUVFRUVFRUVFRUVFRUVFRUVFRUVFRUVFRUVFRUVFRUVFRUVFRUVFR/8AAEQgABQOCAwEiAAIRAQMRAf/EAB8AAAEFAQEBAQEBAAAAAAAAAAABAgMEBQYHCAkKC//EALUQAAIBAwMCBAMFBQQEAAABfQECAwAEEQUSITFBBhNRYQcicRQygZGhCCNCscEVUtHwJDNicoIJChYXGBkaJSYnKCkqNDU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6g4SFhoeIiYqSk5SVlpeYmZqio6Slpqeoqaqys7S1tre4ubrCw8TFxsfIycrS09TV1tfY2drh4uPk5ebn6Onq8fLz9PX29/j5+v/EAB8BAAMBAQEBAQEBAQEAAAAAAAABAgMEBQYHCAkKC//EALURAAIBAgQEAwQHBQQEAAECdwABAgMRBAUhMQYSQVEHYXETIjKBCBRCkaGxwQkjM1LwFWJy0QoWJDThJfEXGBkaJicoKSo1Njc4OTpDREVGR0hJSlNUVVZXWFlaY2RlZmdoaWpzdHV2d3h5eoKDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uLj5...
//title ="CONFIGURAR_DNS_NIC-7-20241111084556"
//extension ="pdf"

        /* $archivo = $datazip['nemotecnico'].'.'.$datazip['extension'];
        $archivo1 = $datazip['filenemotecnico'];
        $imgbinary = fread(fopen($archivo1, "r"), filesize($archivo1)); */

        //pendiente
        //$brochureFileName = $fileUploader->download($archivo,$datazip['title']);  

        
        //return New JsonResponse(["file"=>$base64_arch,"title"=>$datazip['title'].'-'.$nomborgf[0],"extension"=>$nomborgf[1],"nemotecnico"=>$valor->getNemotecnico(),"filenemotecnico"=>$urles]); 
        //$imgbinary = fread(fopen($brochureFileName, "r"), filesize($brochureFileName));

        /* $filetype = "zip";
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
 */
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
            return New JsonResponse(["file"=>$datazip['file'],"extension"=>$datazip['extension']]); 
            //return New JsonResponse(["file"=>$base64_arch,"title"=>$datazip['title'],"extension"=>$filetype,"nemotecnico"=>$datazip['nemotecnico']]); 
            //return New JsonResponse(["file"=>$base64_arch,"title"=>$nomorg[0].'-'.$nomborgf[0],"extension"=>$nomborgf[1],"nemotecnico"=>$valor->getNemotecnico(),"filenemotecnico"=>$urles]); 

            //return new JsonResponse(['Listo'=>'Listo'],200);
            //return $data;
            //return $brochureFileName;  
        }else{
            return new JsonResponse(['msg'=>'No existen Registros'],409);  
        }   
    }

          /**
        * @Route("/api/archivodigital/actualizar/{id}", methods={"PUT"})
        * @OA\Put(
         * summary="Put Archivos",
         * description="Update Archivos",
         * operationId="ArchivoDigitalupdateArchivos",
         * tags={"Archivos Digital"},
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
            $repository = $this->getDoctrine()->getRepository(Archivosd::class);
            return $repository->put($data,$id,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


      /**
        * Get an Archivos Digital by Id.
        * @Route("/api/metadataarchivosdigital/{id}", methods={"GET"})
        * @OA\Post(
         * summary="Archivos List",
         * description="Archivos List",
         * operationId="ArchivoDigitalslist",
         * tags={"Archivos Digital"},
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
         * @OA\Tag(name="Archivos Digital")
         * @Security(name="Bearer")
    */   
    public function metadata($id,Request $request,ArchivosdRepository $archivosRepository,FileUploader $fileUploader): JsonResponse
    {
        $dirfile = 'archivos/giep/documentos/Actividad.docx';
        $brochureFileName = $fileUploader->metadata_file($dirfile);  
        //return $brochureFileName;  
        return New JsonResponse(["metadata"=>$brochureFileName]); 
        
      
    }   


       /**
        * @Route("/api/archivodigital/admindesbloqueoarchivos/{id}", methods={"PUT"})
        * @OA\Put(
         * summary="Put Archivos",
         * description="Update Desbloqueo de Archivos",
         * operationId="ArchivoDigitalupdateDesbloqueoArchivos",
         * tags={"Archivos Digital"},
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
            $repository = $this->getDoctrine()->getRepository(Archivosd::class);
            return $repository->putadmindesbloqueoarchivos($data,$id,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }

    /**
        * @Route("/api/archivodigital/cambiostatusarchivo", methods={"PUT"})
        * @OA\Put(
         * summary="Put Archivos Status",
         * description="Update Archivos Status",
         * operationId="ArchivoDigitalupdateArchivosStatus",
         * tags={"Archivos Digital"},
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
            $repository = $this->getDoctrine()->getRepository(Archivosd::class);
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