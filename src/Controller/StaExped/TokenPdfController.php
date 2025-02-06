<?php

namespace App\Controller\StaExped;

use App\Entity\StaExped\TokenPdf;
use App\Dto\StaExped\TokenPdfOutPutDto;
use App\Form\StaExped\TokenPdfType;
use App\Repository\StaExped\TokenPdfRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Helper;
use Symfony\Component\Validator\Constraints\Json;
use App\Service\Fpdfreporte;

class TokenPdfController extends AbstractController
{
    /**
     *  Get an Datos Reporte by tipo. 
     * @Route("/api/tokenpdf/reportes/{tiporeporte}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=TokenPdfOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="StaExped Pdf Reporte")
     * @Security(name="Bearer")
     */
    public function getReporteByTipo($tiporeporte,Request $request,ValidatorInterface $validator,Helper $helper, Fpdfreporte $Fpdfreporte): JsonResponse
    {
    
        try {
            $em = $this->getDoctrine()->getManager('customer');
            $repository = $this->getDoctrine()->getRepository(TokenPdf::class);
            //$data = json_decode($request->getContent(),true);
            
            if ($tiporeporte==1) {
            //reporte general
            
            //NOMBRE DEL TITULO PRINCIPAL DEL REPORTE!!!
            $tituloReporte = "Reporte Datos Personales";
            //LOGO DEL REPORTE!!!
            $logo = "logomovilnet.jpg";
            //CODIGO QR DEL REPORTE!!!
            $logoqr = "../public/qrreportes.png";
            $DateAndTime = date('his', time());
            $nonmarchivo = "Expedientes" .'-' .date("Y") . date("m") . date("d") . $DateAndTime;
            //**************************************************
            // DEFINICIÓN DE UNA TABLA DE DATOS
            //**************************************************
            //TITULO DE LA TABLA DE DATOS!!!
            $titulotb ='Datos Personales';
            //ALTO CELDAS!!!
            $arrtbaltoceldas = array(8,8,8,8,8,8,8,8,8);
            //ANCHO CELDAS!!!
            $arrtbanchoceldas = array(10,15,25,25,25,22,15,18,25);
            //la alineación de cada COLUMNA!!!
            $arrtbalineacioncolumna = array('C','C','C','C','C','C','C','C','C');
            //INICA NUEVO REPORTE CREAR CABECERA Y FOOTER!!!
            $brochureBroadcast = $Fpdfreporte->pushCabecera_Pie_Repotes($tituloReporte,$logo,$logoqr,$nonmarchivo);
            //NOMBRES DE LAS COLUMNAS!!!
            $arrtbcabecera = array('0' => 'Id','1' => 'Cédula','2' => 'Nombres','3' => 'Apellidos','4' => 'Nacimiento','5' => 'Ingreso','6' => 'Familiar','7' => 'Autorización','8' => 'Fecha Creación');
            //CONFIGURACION DINAMICA DEL ENCABEZADO DE LA TABLA!!!
            $brochureBroadcast = $Fpdfreporte->pushEncabezadoTablasRepotes($arrtbcabecera,$arrtbanchoceldas,$arrtbaltoceldas,$titulotb,$tituloReporte,$logo,$logoqr);
            //LLAMADA PARA LA INYECCION DE DATOS!!!
            $data = $repository->post($request->get('tiporeporte'),$validator,$helper,$em,$Fpdfreporte,$tituloReporte,$logo,$logoqr,$arrtbcabecera,$titulotb,$arrtbanchoceldas,$arrtbaltoceldas,$nonmarchivo);  
            //GUARDA EL REPORTE!!!
            
            $base64_arch = $Fpdfreporte->pushCierreRepotes($nonmarchivo);
            //**************************************************
            // FIN DE DEFINICIÓN DE REPORTE
            //**************************************************
         }else{
            //reporte filtrados

            return new JsonResponse(['msg'=>'Reporte en construcción'],409);  
         } 

            if ($base64_arch) {
                return New JsonResponse(["file"=>$base64_arch[0],"title"=>$base64_arch[1],"extension"=>$base64_arch[2]],200); 
            }else{
                return new JsonResponse(['msg'=>'No existen Registros'],409);  
            }   

        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
 }

   

    /**
         * @Route("/api/tokenpdf/reportesstaff/", methods={"POST"})
         * @OA\Post(
         * summary="Token Pdf Reporte",
         * description="Token Pdf Reporte",
         * operationId="tokenpdfreporte",
         * tags={"StaExped Pdf Reporte"},
         *      @OA\RequestBody(
         *         @OA\MediaType(
         *             mediaType="multipart/form-data",
         *             @OA\Schema(
         *                 @OA\Property(property="tiporeporte", type="integer", format="integer", example="1"),
         *                 @OA\Property(property="reportes", type="array", @OA\Items(type="array",@OA\Items()), example={}),
         *                 @OA\Property(property="id_datos_personales", type="integer", format="integer", example="4"),
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
    public function post(Request $request,ValidatorInterface $validator,Helper $helper, Fpdfreporte $Fpdfreporte): Response
    {   
        try {
            $data = json_decode($request->getContent(),true);
            $em = $this->getDoctrine()->getManager('customer');
            $repository = $this->getDoctrine()->getRepository(TokenPdf::class);

            $iddatospers = $data["id_datos_personales"];
            /* $iddatospers = 95;
            $tipreportes = 2;
            $reports ='Documentos de ingresos'; */


            //NOMBRE DEL TITULO PRINCIPAL DEL REPORTE!!!
            $tituloReporte = "Reporte Datos Personales";
            //LOGO DEL REPORTE!!!
            $logo = "logomovilnet.jpg";
            //CODIGO QR DEL REPORTE!!!
            $logoqr = "../public/qrreportes.png";
            $DateAndTime = date('his', time());
            $nonmarchivo = "Expedientes" .'-' .date("Y") . date("m") . date("d") . $DateAndTime;
            //**************************************************
            // DEFINICIÓN DE UNA TABLA DE DATOS
            //**************************************************
            //TITULO DE LA TABLA DE DATOS!!!
            $titulotb ='Datos Personales';
            //ALTO CELDAS!!!
            $arrtbaltoceldas = array(8,8,8,8,8,8,8,8,8);
            //ANCHO CELDAS!!!
            $arrtbanchoceldas = array(10,15,25,25,25,22,15,18,25);
            //la alineación de cada COLUMNA!!!
            $arrtbalineacioncolumna = array('C','C','C','C','C','C','C','C','C');
            //INICA NUEVO REPORTE CREAR CABECERA Y FOOTER!!!
            $brochureBroadcast = $Fpdfreporte->pushCabecera_Pie_Repotes($tituloReporte,$logo,$logoqr,$nonmarchivo);
            //NOMBRES DE LAS COLUMNAS!!!
            $arrtbcabecera = array('0' => 'Id','1' => 'Cédula','2' => 'Nombres','3' => 'Apellidos','4' => 'Nacimiento','5' => 'Ingreso','6' => 'Familiar','7' => 'Autorización','8' => 'Fecha Creación');
            //CONFIGURACION DINAMICA DEL ENCABEZADO DE LA TABLA!!!
            $brochureBroadcast = $Fpdfreporte->pushEncabezadoTablasRepotes($arrtbcabecera,$arrtbanchoceldas,$arrtbaltoceldas,$titulotb,$tituloReporte,$logo,$logoqr);
            //LLAMADA PARA LA INYECCION DE DATOS!!!
            $dataentr = $repository->postItems($request->get('tiporeporte'),$validator,$helper,$em,$Fpdfreporte,$tituloReporte,$logo,$logoqr,$arrtbcabecera,$titulotb,$arrtbanchoceldas,$arrtbaltoceldas,$nonmarchivo,$iddatospers);  
            //**************************************************
            // FIN DE DEFINICIÓN DE REPORTE
            //**************************************************
            //$Fpdfreporte->pushSalto();

            $iddatospers = $data["id_datos_personales"];
            $tipreportes = $data["tiporeporte"];

            
            foreach($data["reportes"] as $clave=>$valor){
               $reports = $valor["reporte"];
               $data = $repository->postItems($tipreportes,$validator,$helper,$em,$Fpdfreporte,$tituloReporte,$logo,$logoqr,$arrtbcabecera,$reports,$arrtbanchoceldas,$arrtbaltoceldas,$nonmarchivo,$iddatospers);   
            }

            
           
            //$data = json_decode($request->getContent(),true);
            
           
            //GUARDA EL REPORTE!!!
            
            $base64_arch = $Fpdfreporte->pushCierreRepotes($nonmarchivo);
            //**************************************************
            // FIN DE DEFINICIÓN DE REPORTE
            //**************************************************
        
            if ($base64_arch) {
                return New JsonResponse(["file"=>$base64_arch[0],"title"=>$base64_arch[1],"extension"=>$base64_arch[2]],200); 
            }else{
                return new JsonResponse(['msg'=>'No existen Registros'],409);  
            }   

        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }

     /**
     *  Get an Datos documentos ingresos by Id. 
     * @Route("/api/tokenpdf/reportesdetallesexcel/{Reporte}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=TokenPdfOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="StaExped Pdf Reporte")
     * @Security(name="Bearer")
     */
    public function getReporteByDetalles($Reporte,Request $request,ValidatorInterface $validator,Helper $helper, Fpdfreporte $Fpdfreporte): JsonResponse
    {
    
        try {
            $em = $this->getDoctrine()->getManager('customer');
            $repository = $this->getDoctrine()->getRepository(TokenPdf::class);
            //reporte detalles
            //CODIGO QR DEL REPORTE!!!
            $logoqr = "../public/qrreportes.png";
            $DateAndTime = date('his', time());
            switch ($Reporte) {
                case "Expedientes-Detalles-Lineal":
                       $nonmarchivo = "Expedientes-Detalles-Lineal" .'-' .date("Y") . date("m") . date("d") . $DateAndTime;
                        $codQr = $Fpdfreporte->pushCod_QR($nonmarchivo);
                        $arrtbcabecera = array('0' => 'Id','1' => 'Cédula','2' => 'Nombres','3' => 'Apellidos','4' => 'Nacimiento'
                        ,'5' => 'Ingreso','6' => 'Familiar','7' => 'Autorización','8' => 'Solicitud_empleo','9' => 'Sintesis_curricular','10' => 'Copia_cedula'
                        ,'11' => 'Constancia_trabajo','12' => 'Reg_informacion_fiscal','13' => 'Verificacion_ref_laborales'
                        ,'14' => 'Certificacion_declaracion_jurada','15' => 'Licencia','16' => 'Certificado_medico','17' => 'Punto_cuenta','18' => 'Poseer_titulo'
                        ,'19' => 'Descripcion_cargo','20' => 'Confidencialidad','21' => 'Cargo','22' => 'Departamento','23' => 'Area'
                        ,'24' => 'Region','25' => 'Normas_internas','26' => 'Forma_ari','27' => 'Inscrito_ivss','28' => 'Const_depos_prestac_sociales'
                        ,'29' => 'Recibo_anual_intereses','30' => 'Ruta_metro','31' => 'Analisis_seguro_trabajo','32' => 'Entrega_equipo_proteccion'
                        ,'33' => 'Constancia_examenes_ocupacionales','34' => 'Constancia_normas_seguridad','35' => 'Copia_registro_delegado'
                        ,'36' => 'Expedientes_legal','37' => 'Curso_desarrollo_area_laboral','38' => 'Categoria','39' => 'Motivo Expediente'
                        ,'40' => 'Observación'
                        );
                        $base64_arch = $repository->postDetalles($validator,$helper,$em,$logoqr,$arrtbcabecera,$nonmarchivo,$codQr);  
                        break;
                case "Expedientes-Estudios-Academicos":
                        $nonmarchivo = "Expedientes-Estudios-Academicos" .'-' .date("Y") . date("m") . date("d") . $DateAndTime;
                        $codQr = $Fpdfreporte->pushCod_QR($nonmarchivo);
                        $arrtbcabecera = array('0' => 'Id','1' => 'Cédula','2' => 'Nombres','3' => 'Apellidos','4' => 'Nacimiento'
                        ,'5' => 'Ingreso','6' => 'Familiar','7' => 'Autorización','8' => 'Profesion','9' => 'Fecha Graduado');
                        $base64_arch = $repository->postDetallesEstudiosAcademicos($validator,$helper,$em,$logoqr,$arrtbcabecera,$nonmarchivo,$codQr);  
                        break;
                case "Expedientes-Especialidades-Area":
                       $nonmarchivo = "Expedientes-Especialidades-Area" .'-' .date("Y") . date("m") . date("d") . $DateAndTime;
                        $codQr = $Fpdfreporte->pushCod_QR($nonmarchivo);
                        $arrtbcabecera = array('0' => 'Id','1' => 'Cédula','2' => 'Nombres','3' => 'Apellidos','4' => 'Nacimiento'
                        ,'5' => 'Ingreso','6' => 'Familiar','7' => 'Autorización','8' => 'Area');
                        $base64_arch = $repository->postDetallesEspecialidadesArea($validator,$helper,$em,$logoqr,$arrtbcabecera,$nonmarchivo,$codQr); 
                        break;
                case "Expedientes-Transferencias":
                    $nonmarchivo = "Expedientes-Transferencias" .'-' .date("Y") . date("m") . date("d") . $DateAndTime;
                    $codQr = $Fpdfreporte->pushCod_QR($nonmarchivo);
                    $arrtbcabecera = array('0' => 'Id','1' => 'Cédula','2' => 'Nombres','3' => 'Apellidos','4' => 'Nacimiento'
                    ,'5' => 'Ingreso','6' => 'Familiar','7' => 'Autorización','8' => 'Fecha Transferencia','9' => 'Region',
                    '10' => 'Departamento','11' => 'Area');
                    $base64_arch = $repository->postDetallesMovimientosTransferencias($validator,$helper,$em,$logoqr,$arrtbcabecera,$nonmarchivo,$codQr);   
                    break;
                case "Expedientes-Promocion":
                    $nonmarchivo = "Expedientes-Promocion" .'-' .date("Y") . date("m") . date("d") . $DateAndTime;
                    $codQr = $Fpdfreporte->pushCod_QR($nonmarchivo);
                    $arrtbcabecera = array('0' => 'Id','1' => 'Cédula','2' => 'Nombres','3' => 'Apellidos','4' => 'Nacimiento'
                    ,'5' => 'Ingreso','6' => 'Familiar','7' => 'Autorización','8' => 'Cargo','9' => 'Fecha Promoción');
                    $base64_arch = $repository->postDetallesPromocion($validator,$helper,$em,$logoqr,$arrtbcabecera,$nonmarchivo,$codQr);
                    break;
                case "Expedientes-Vacaciones":
                    $nonmarchivo = "Expedientes-Vacaciones" .'-' .date("Y") . date("m") . date("d") . $DateAndTime;
                    $codQr = $Fpdfreporte->pushCod_QR($nonmarchivo);
                    $arrtbcabecera = array('0' => 'Id','1' => 'Cédula','2' => 'Nombres','3' => 'Apellidos','4' => 'Nacimiento'
                    ,'5' => 'Ingreso','6' => 'Familiar','7' => 'Autorización','8' => 'Tipo Permiso','9' => 'Autorizacion Permiso'
                    ,'10' => 'Fecha_Desde','11' => 'Fecha_Hasta','12' => 'Fecha_Incorporacion','13' => 'Periodos_Acumulados'
                    ,'14' => 'Periodo_Difrute','15' => 'Tiempo_Difrute'
                    );
                    $base64_arch = $repository->postDetallesVacaciones($validator,$helper,$em,$logoqr,$arrtbcabecera,$nonmarchivo,$codQr); 
                    break;
                case "Expedientes-Permisos":
                    $nonmarchivo = "Expedientes-Permisos" .'-' .date("Y") . date("m") . date("d") . $DateAndTime;
                    $codQr = $Fpdfreporte->pushCod_QR($nonmarchivo);
                    $arrtbcabecera = array('0' => 'Id','1' => 'Cédula','2' => 'Nombres','3' => 'Apellidos','4' => 'Nacimiento'
                    ,'5' => 'Ingreso','6' => 'Familiar','7' => 'Autorización','8' => 'Tipo Permiso','9' => 'Autorizacion Permiso'
                    ,'10' => 'Fecha_Desde','11' => 'Fecha_Hasta','12' => 'Tiempo_Permiso'
                    );
                    $base64_arch = $repository->postDetallesPermisos($validator,$helper,$em,$logoqr,$arrtbcabecera,$nonmarchivo,$codQr);  
                    break;
                case "Expedientes-Reposos":
                    $nonmarchivo = "Expedientes-Reposos" .'-' .date("Y") . date("m") . date("d") . $DateAndTime;
                    $codQr = $Fpdfreporte->pushCod_QR($nonmarchivo);
                    $arrtbcabecera = array('0' => 'Id','1' => 'Cédula','2' => 'Nombres','3' => 'Apellidos','4' => 'Nacimiento'
                    ,'5' => 'Ingreso','6' => 'Familiar','7' => 'Autorización','8' => 'Tipo Reposos','9' => 'Motivo Reposos'
                    ,'10' => 'Fecha_Desde','11' => 'Fecha_Hasta','12' => 'Tiempo_Reposos'
                    );
                    $base64_arch = $repository->postDetallesReposos($validator,$helper,$em,$logoqr,$arrtbcabecera,$nonmarchivo,$codQr);  
                    break;
            }

            //GUARDA EL REPORTE!!!
            
            //$base64_arch = $Fpdfreporte->pushCierreRepotes($nonmarchivo);
           

            if ($base64_arch) {
                return New JsonResponse(["file"=>$base64_arch[0],"title"=>$base64_arch[1],"extension"=>$base64_arch[2]],200); 
            }else{
                return new JsonResponse(['msg'=>'No existen Registros'],409);  
            }   

        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
 }


/**
     *  Get an Datos Reporte by carga. 
     * @Route("/api/tokenpdf/reportescargausuario/{users}/{fechadesde}/{fechahasta}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=TokenPdfOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="StaExped Pdf Reporte")
     * @Security(name="Bearer")
     */
    public function getReportesCargaUsuario($users,$fechadesde,$fechahasta,Request $request,ValidatorInterface $validator,Helper $helper, Fpdfreporte $Fpdfreporte): JsonResponse
    {
    
        try {
            $em = $this->getDoctrine()->getManager('customer');
            $repository = $this->getDoctrine()->getRepository(TokenPdf::class);
            //reporte general
            
            //NOMBRE DEL TITULO PRINCIPAL DEL REPORTE!!!
            $tituloReporte = "Reporte Detalles Cargas Expedientes Usuarios";
            //LOGO DEL REPORTE!!!
            $logo = "logomovilnet.jpg";
            //CODIGO QR DEL REPORTE!!!
            $logoqr = "../public/qrreportes.png";
            $DateAndTime = date('his', time());
            $nonmarchivo = "Expedientes" .'-' .date("Y") . date("m") . date("d") . $DateAndTime;
            //**************************************************
            // DEFINICIÓN DE UNA TABLA DE DATOS
            //**************************************************
            //TITULO DE LA TABLA DE DATOS!!!
            $titulotb ='Carga Datos Personales '.$users;
            //ALTO CELDAS!!!
            $arrtbaltoceldas = array(8,8,8,8,8,8,8,8,8);
            //ANCHO CELDAS!!!
            $arrtbanchoceldas = array(10,15,25,25,25,22,15,18,25);
            //la alineación de cada COLUMNA!!!
            $arrtbalineacioncolumna = array('C','C','C','C','C','C','C','C','C');
            //INICA NUEVO REPORTE CREAR CABECERA Y FOOTER!!!
            $brochureBroadcast = $Fpdfreporte->pushCabecera_Pie_Repotes($tituloReporte,$logo,$logoqr,$nonmarchivo);
            //NOMBRES DE LAS COLUMNAS!!!
            $arrtbcabecera = array('0' => 'Id','1' => 'Cédula','2' => 'Nombres','3' => 'Apellidos','4' => 'Nacimiento','5' => 'Ingreso','6' => 'Familiar','7' => 'Autorización','8' => 'Fecha Creación');
            //CONFIGURACION DINAMICA DEL ENCABEZADO DE LA TABLA!!!
            $brochureBroadcast = $Fpdfreporte->pushEncabezadoTablasRepotes($arrtbcabecera,$arrtbanchoceldas,$arrtbaltoceldas,$titulotb,$tituloReporte,$logo,$logoqr);
            //LLAMADA PARA LA INYECCION DE DATOS!!!
            $data = $repository->getReportesCargaUsuario($users,$fechadesde,$fechahasta,$validator,$helper,$em,$Fpdfreporte,$tituloReporte,$logo,$logoqr,$arrtbcabecera,$titulotb,$arrtbanchoceldas,$arrtbaltoceldas,$nonmarchivo);  
            //GUARDA EL REPORTE!!!
            
            $base64_arch = $Fpdfreporte->pushCierreRepotes($nonmarchivo);
            //**************************************************
            // FIN DE DEFINICIÓN DE REPORTE
            //**************************************************

            if ($base64_arch) {
                return New JsonResponse(["file"=>$base64_arch[0],"title"=>$base64_arch[1],"extension"=>$base64_arch[2]],200); 
            }else{
                return new JsonResponse(['msg'=>'No existen Registros'],409);  
            }   

        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
 }




 /**
     *  Get an Datos Reporte by usuarios carga. 
     * @Route("/api/tokenpdf/usuarioscargasexpedientes/List", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=TokenPdfOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="StaExped Pdf Reporte")
     * @Security(name="Bearer")
     */
    public function getUsuariosCargasExpedientes(Request $request,ValidatorInterface $validator,Helper $helper, Fpdfreporte $Fpdfreporte): JsonResponse
    {
        try {
            $em = $this->getDoctrine()->getManager('customer');
            $repository = $this->getDoctrine()->getRepository(TokenPdf::class);
            $data = $repository
            ->findList($em);
            if (!$data) {
                return new JsonResponse(['msg'=>'No existen Registros'],200);  
            }   
             return new JsonResponse($data,200);  
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
 }

    

   /**
     *  Get an Validador de Expedientes by expedientes. 
     * @Route("/account/tokenpdf/validador/{expediente}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns expedientes",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=TokenPdfOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="StaExped Pdf Validador Expediente")
     */
    public function validarExpedientes($expediente,Request $request,ValidatorInterface $validator,Helper $helper):JsonResponse
    {  
        try {
            $em = $this->getDoctrine()->getManager('customer');
            $data = json_decode($request->getContent(),true);
            $repository = $this->getDoctrine()->getRepository(TokenPdf::class);
            return $repository->getValidExpByExp($expediente,$em); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }

}
