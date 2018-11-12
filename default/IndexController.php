<?php
include_once 'app/controller.php';
include_once 'rest/Consume.php';
include_once 'app/Method.php';
include_once 'libs/captcha/securimage.php'; 
include_once 'default/constant.php'; 
require 'libs/aws/aws-autoloader.php';
require_once 'libs/jwt/autoload.php';
use Aws\Sts\StsClient;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Firebase\JWT\JWT;
define('ENCRYPTION_KEY', 'd0a7e7997b6d5fcd55f4b5c32611b87cd923e88837b63bf2941ef819dc8ca282');     

class Default_IndexController extends App_Controller
{       

    public function consultaParametros(){
        $data = array("operacion" => 'ListarParametros', "data" => ''); 
        $response = Rest_Consume::consumeApi( $data );
        $this->setDataParameter($response);         
    }

    private function convertLFecha($date){
        $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $diaL = date('w', strtotime($date));
        $diaN = date('d', strtotime($date));
        $mesL = date('n', strtotime($date))-1;
        $año = date('Y', strtotime($date));
        $fecha  = "Este ".$dias[$diaL]." ". $diaN." de ".$meses[$mesL]." del ". $año;
        return $fecha;
    }

    private function setDataParameter($parametros){
        foreach ($parametros->data as $value) {
            foreach($value as $p){
                if($p['CODIGO'] == Model_Constant::PARAM_ELECCION){
                    $_SESSION['PARAM_ELECCION_NOMBRE'] = $p['DESCRIPCION'];
                    $_SESSION['PARAM_ELECCION_FECHA_L'] = $this->convertLFecha($p['FECHA']);
                    $_SESSION['PARAM_ELECCION_FECHA'] = $p['FECHA'];
                }
                if($p['CODIGO'] == Model_Constant::PARAM_TIPO_CONSULTA){
                    $_SESSION['PARAM_TIPO_CONSULTA'] = $p['ESTADO'];
                }
                if($p['CODIGO'] == Model_Constant::PARAM_TACHA){
                    $_SESSION['PARAM_TACHA_TEXTO'] = $p['VALOR'];
                }
                if($p['CODIGO'] == Model_Constant::PARAM_BANNER){
                    $_SESSION['PARAM_BANNER'] = $p['IMAGEN'];
                }
                if($p['CODIGO'] == Model_Constant::PARAM_INFO_ADICIONAL){
                    $_SESSION['PARAM_INFO_ADICIONAL'] = $p['ESTADO'];
                }
                if($p['CODIGO'] == Model_Constant::PARAM_FECHA_JORNADA_CAP){
                    $_SESSION['PARAM_FECHA_JORNADA_CAP'] = $p['VALOR'];
                }
                if($p['CODIGO'] == Model_Constant::PARAM_URL_LOCAL_CAP){
                    $_SESSION['PARAM_URL_LOCAL_CAP'] = $p['VALOR'];
                }
                if($p['CODIGO'] == Model_Constant::PARAM_SOLUCIONES_TEC){
                    $_SESSION['PARAM_SOLUCIONES_TEC'] = $p['VALOR'];
                }
                if($p['CODIGO'] == Model_Constant::PARAM_CROQUIS_LOCALES){
                    $_SESSION['PARAM_CROQUIS_LOCALES'] = $p['ESTADO'];
                }
                if($p['CODIGO'] == Model_Constant::PARAM_CREDENCIALES_MM){
                    $_SESSION['PARAM_CREDENCIALES_MM'] = $p['ESTADO'];
                }                                                                             
                if($p['CODIGO'] == Model_Constant::PARAM_ETIQUETAS){
                    $etiquetas = json_decode($p['VALOR']);
                    $_SESSION['ETIQUETA_DEP'] = $etiquetas->DEPARTAMENTO;
                    $_SESSION['ETIQUETA_PRO'] = $etiquetas->PROVINCIA;
                    $_SESSION['ETIQUETA_DIS'] = $etiquetas->DISTRITO;                       
                }    
                if($p['CODIGO'] == Model_Constant::PARAM_HORA_CIERRE){
                    $_SESSION['PARAM_HORA_CIERRE'] = $p['VALOR'];
                }
                if($p['CODIGO'] == Model_Constant::PARAM_URL_CREDENCIALES){
                    $_SESSION['PARAM_URL_CREDENCIALES'] = $p['VALOR'];
                } 

                if($p['CODIGO'] == Model_Constant::PARAM_URL_CROQUIS){
                    $_SESSION['PARAM_URL_CROQUIS'] = $p['VALOR'];
                } 
                if($p['CODIGO'] == Model_Constant::PARAM_TITULO){
                    $_SESSION['PARAM_TITULO'] = $p['VALOR'];
                } 
                if($p['CODIGO'] == Model_Constant::PARAM_URL_EXCUSA){
                    $_SESSION['PARAM_URL_EXCUSA'] = $p['VALOR'];
                } 
            }
        }
    }

    function hoursToSecods ($hour) { 
        $parse = array();
        if (!preg_match ('#^(?<hours>[\d]{2}):(?<mins>[\d]{2}):(?<secs>[\d]{2})$#',$hour,$parse)) {
            throw new RuntimeException ("Hour Format not valid");
        }
            return (int) $parse['hours'] * 3600 + (int) $parse['mins'] * 60 + (int) $parse['secs'];
    }

    public function validateDate(){
        $nowseconds=strtotime(date('Y-m-j H:i'));
        $now = date('Y-m-j H:i');
        $fechaCierre = strtotime($_SESSION['PARAM_ELECCION_FECHA']);     
        $horaCierre =  date('H:i:s',strtotime($_SESSION['PARAM_HORA_CIERRE']));
        $seconds = $this->hoursToSecods($horaCierre);
        $date = $fechaCierre + $seconds;
        $limit = date ( 'Y-m-j H:i' , ($fechaCierre + $seconds));
        $limitseconds=strtotime($limit);
        $_SESSION['PARAM_FECHA_COMPLETA_FIN'] = $date ;        
        if($nowseconds <= $limitseconds){
            return true;
        }else{
            return false;
        }

    }

    public function indexAction () {
        $this->consultaParametros();
        $validate = $this->validateDate();
        $this->getSmarty()->assign("WEB_TITLE", "ONPE - Oficina Nacional de Procesos Electorales");
        $this->getSmarty()->assign("WEB_DESCRIPCION", "La ONPE pone a su disposición la consulta local de votación.");
        $this->getSmarty()->assign("_metakeywords", "onpe, civil, estado, peruano, electoral, sistema, elecciones");  
        $this->getSmarty()->assign("_TITULO_VOTACION",  $_SESSION['PARAM_TITULO']);
        if($validate){
            $this->getSmarty()->assign("_texto_tacha", $_SESSION['PARAM_TACHA_TEXTO']);
            $this->getSmarty()->assign("_estado_tacha", $_SESSION['PARAM_TIPO_CONSULTA']);
            $this->getSmarty()->assign("tipoConsulta", $_SESSION['PARAM_TIPO_CONSULTA']);
            $this->getSmarty()->assign("PARAM_BANNER", $_SESSION['PARAM_BANNER']);
            $this->render ( 'consultaListado.tpl' ); 
        }else{
            $this->getSmarty()->assign("_nombre_proceso", $_SESSION['PARAM_ELECCION_NOMBRE']);
            $this->getSmarty()->assign("_MESSAGE_EXPIRED",  "El periodo de consulta ha finalizado.<br><br>Gracias por participar."); 
            $this->render ( 'fin.tpl' );  

            //$this->render ( 'consultaListado.tpl' );           
        }
    }

    private function generateCode()
	{
		$code = '';
        $this->charset = 'ABCDEFGHKLMNPRSTUVWYZ23456789';
		for($i = 1, $cslen = strlen($this->charset); $i <= 4; ++$i) {
			$code .= $this->charset{rand(0, $cslen - 1)};
        }
        $time = time();
        $token = array(
            'iat' => $time,
            'exp' => $time + (60*10),
            'data' => [
                'captcha' => $code
                ]
            );
        $jwt = JWT::encode($token, Model_Constant::KEY_JWT);
		return $jwt;
    }
    
    public function generateCaptchaAction(){
        $code= $this->generateCode();
        echo json_encode(array(
            'captcha' => $code
      ));
    }

    public function consultaUbigeoAction () {
        if( $_SESSION['PARAM_TIPO_CONSULTA'] == 0 ){
                $data = array("operacion" => 'ListadoMM',
                              "data" => array("ubigeo" => ''));

                $response = Rest_Consume::consumeApi( $data );

                foreach ($response->data as $value) {
                    $_rows[] = $value;
                }

                $this->getSmarty()->assign("_tipoConsulta", $_SESSION['PARAM_TIPO_CONSULTA']);
                $this->getSmarty()->assign("ETIQUETA_DEP", $_SESSION['ETIQUETA_DEP']);
                $this->getSmarty()->assign("ETIQUETA_PRO", $_SESSION['ETIQUETA_PRO']);
                $this->getSmarty()->assign("ETIQUETA_DIS", $_SESSION['ETIQUETA_DIS']); 
                $this->getSmarty()->assign("_data", $_rows);
                $this->render ( 'listadoUbigeoDni.tpl' );
        }
        if( $_SESSION['PARAM_TIPO_CONSULTA'] == 1 ){
            $data = array("operacion" => 'ListadoMM');
        
            $response = Rest_Consume::consumeApi( $data );

            foreach ($response->data as $value) {
                $_rows[] = $value;
            }

            $this->getSmarty()->assign("_tipoConsulta", $_SESSION['PARAM_TIPO_CONSULTA']);
            $this->getSmarty()->assign("_data", $_rows);
            $this->render ( 'consultaDni.tpl' );
        }
    }

    public function consultaUbigeoProAction () {
        $data = array("operacion" => "ListadoMM",
                      "data" => array("ubigeo" => $this->getParam('ubigeo'))
                );
    
        $response = Rest_Consume::consumeApi( $data );

        if($response->data == null){

        }else{    
            foreach ($response->data as $value) {
                $_rows[] = $value;
            }
        }

        $this->getSmarty()->assign("_data", $_rows);
        $this->render ( 'listadoProvincia.tpl' );
    }

    public function consultaUbigeoDisAction () {
        $data = array("operacion" => 'ListadoMM',
                      "data" => array("ubigeo" => $this->getParam('ubigeo'))
                );

        $response = Rest_Consume::consumeApi( $data );

        if($response->data == null){

        }else{    
            foreach ($response->data as $value) {
                $_rows[] = $value;
            }
        }

        $this->getSmarty()->assign("_data", $_rows);
        $this->render ( 'listadoDistrito.tpl' );
    }

    

    public function pdfAction($ubigeo){

        $pdf = str_replace("/", "_", $ubigeo['distrito'], $count);

        $filename = "PDF/".$ubigeo['departamento']."/".$ubigeo['provincia']."/".$pdf."/".$pdf.".pdf";

        if(file_exists($filename)){
            $_link = $filename;
            $_textoListado = 'Lista de Miembros de Mesa';
            $_disable = true;
        } else {
            $_link = null;
            $_textoListado = 'Lista de Miembros de Mesa';
            $_disable = false;
        }

        $_arrayPdf = array('link' => $_link, 'textoListado' => $_textoListado, 'disable' => $_disable);
        return $_arrayPdf; 
    }

    public function consultaDniAction () {
        $this->getSmarty()->assign("_tipoConsulta", $_SESSION['PARAM_TIPO_CONSULTA']);    
        $this->getSmarty()->assign("captcha", $this->generateCode()); 
        $this->render ( 'consultaDni.tpl' );
    }

    public function footernAction () {
        $validate = $this->validateDate();
        if($validate){
            $this->getSmarty()->assign("_MUESTRA_DESC_CRED", $_SESSION['PARAM_TIPO_CONSULTA'] == 1 && $_SESSION['PARAM_CREDENCIALES_MM']);
            $this->render ( 'footerListadoConsulta.tpl' );
        }else{
            $this->render ( 'footerFin.tpl' );
        }
    }

    public function footerlAction () {
        $this->render ( 'footerListado.tpl' );
    }

    public function footermAction ($data) {
        $this->getSmarty()->assign("_MUESTRA_INFO_GNRAL", $_SESSION['PARAM_INFO_ADICIONAL']);
        $this->getSmarty()->assign("_MUESTRA_DESC_CRED", $_SESSION['PARAM_CREDENCIALES_MM']);
        $this->getSmarty()->assign("_row", $data);

        echo json_encode(array(
          'data' => $data,
          'page' => $this->getSmarty()->fetch ( 'footerConsulta.tpl' ),
        ));
    }

    public function footerPorNombresAction () {
        $this->render ( 'footerPorNombres.tpl' );
    }

    function validateAction(){
            $documento = $this->getParam('documento');
            $response = new stdClass();
            if(strlen($documento) != 8 || $documento == ''){
                $response->success = false;            
                $response->message = Model_Constant::VALIDA_DNI;
                $response->data = 'VALIDA_DNI';
                echo json_encode($response);
            }else{
                $response->success = true; 
                $time = time();
                $documento = array(
                    'iat' => $time, // Tiempo que inició el token
                    'exp' => $time + (5), // Tiempo que expirará el token (+1 hora)
                    'data' => [ // información del usuario
                        'numele' => $documento
                        ]
                );
                $response->token=JWT::encode($documento, Model_Constant::KEY_JWT);
                echo json_encode($response);
            }
    }

    function consultaAction () {      
        $jwt = $this->getParam('token'); 
        try{
            $data = JWT::decode($jwt, Model_Constant::KEY_JWT, array('HS256'));
            $numele=$data->data->numele;

            if( $_SESSION['PARAM_TIPO_CONSULTA'] == 0 ){
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    $data = array("operacion" => 'ConsultarMM',
                                  "data" => array("numeroDocumento" =>$numele,
                                  "key" => 'dexqsdfdfgds34werfe576wgerhgrthtrrt')
                            );            
    
                    $dataMM = array("operacion" => "CargosMM", 
                                    "data" => array("documento" => $numele),
                            );
                            
                    $response = Rest_Consume::consumeApi( $data );
                    $responseMM = Rest_Consume::consumeApi( $dataMM );                
    
                    if(isset($response->data)){
                        if(isset($response->mesa)){
                            $_arrayPdf = $this->pdfAction($response->mesa[0]);
                            if($_arrayPdf['link'] != null){
                                $_link = '<a target="_blank" href="'.$_arrayPdf['link'].'">'.$_arrayPdf['textoListado'].'</a>';
                            }else {
                                $_link = '<a style="cursor:no-drop;">'.$_arrayPdf['textoListado'].'</a>';
                            }   
    
                            $this->getSmarty()->assign('_BASE_URL', BASE_URL);
                            $this->getSmarty()->assign("_consulta", $responseMM->data);
                            $longitudNombres=0;
                            if(isset($response->data["nombres"])){
                                $longitudNombres+=strlen($response->data["nombres"]);
                            }
                            $longitudApellidos=0;
                            if(isset($response->data["apPaterno"])){
                                $longitudApellidos+=strlen($response->data["apPaterno"]);
                            }
                            if(isset($response->data["apMaterno"])){
                                $longitudApellidos+=strlen($response->data["apMaterno"]);
                            }
                            
                            $this->getSmarty()->assign("_row", $response->data);   
                            $this->getSmarty()->assign("_row_mesa", $response->mesa);
                            $this->getSmarty()->assign("_longitud_ape", $longitudApellidos);
                            $this->getSmarty()->assign("_longitud_nombres", $longitudNombres);
                            $this->getSmarty()->assign('_link', $_link);
                            $this->getSmarty()->assign("_nombre_proceso", $_SESSION['PARAM_ELECCION_NOMBRE']);
                            $this->getSmarty()->assign("_texto_tacha", $_SESSION['PARAM_TACHA_TEXTO']);
                            if($this->getParam('definefooter') == true){
                                $footer = $this->getSmarty()->fetch ( 'footerPorNombres.tpl' );
                            }else{
                                $footer = $this->getSmarty()->fetch ( 'footerListado.tpl' );
                            }
    
                            echo json_encode(array(
                              'success' => true,
                              /*'data' => $responseMM->data,
                              'link' => $_link,
                              'nombre_proceso' => $response->nombre_proceso,*/
                              'page' => $this->getSmarty()->fetch ( 'resultadoListadoDni.tpl' ),
                              'footerm' => $footer,
                            ));
    
                        }else{
                             
                            foreach ($response->data as $value) {
                                $_rows[] = $value;
                            }
    
                            $this->getSmarty()->assign('_BASE_URL', BASE_URL);
                            $this->getSmarty()->assign("_consulta", $responseMM->data);
                            $this->getSmarty()->assign("_row", $response->data); 
                            $longitudNombres=0;
                            if(isset($response->data["nombres"])){
                                $longitudNombres+=strlen($response->data["nombres"]);
                            }
                            $longitudApellidos=0;
                            if(isset($response->data["apPaterno"])){
                                $longitudApellidos+=strlen($response->data["apPaterno"]);
                            }
                            if(isset($response->data["apMaterno"])){
                                $longitudApellidos+=strlen($response->data["apMaterno"]);
                            }  
                            $this->getSmarty()->assign("_nombre_proceso", $_SESSION['PARAM_ELECCION_NOMBRE']);
                            $this->getSmarty()->assign("_texto_tacha", $_SESSION['PARAM_TACHA_TEXTO']);
                            echo json_encode(array(
                              'success' => true,
                              /*'nombre_proceso' => $response->nombre_proceso,
                              'page' => $this->getSmarty()->fetch ( 'resultadoListadoDni.tpl' ),*/
                              'page' => $this->getSmarty()->fetch ( 'resultadoListadoDni.tpl' ),
                              'footerm' => $this->getSmarty()->fetch ( 'footerListado.tpl' ),
                            ));
                        }
                    }else{
                        echo json_encode(array(
                          'success' => false,
                          'message' => Model_Constant::ERROR_SERVICIO,
                        ));
                }
                }else{
                    header('location:https://www.onpe.gob.pe/');
                }
            }
            if( $_SESSION['PARAM_TIPO_CONSULTA'] == 1 ){
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    /*if($this->getParam('key') == NULL){   
                        $doc = $this->mc_decrypt($this->getParam('documento'), ENCRYPTION_KEY);           
                        if(is_numeric($doc) && strlen($doc) == 8){
                            $decrypt_data = $doc;
                        }else{
                            echo json_encode(array(
                              'success' => false,
                              'message' => Model_Constant::ERROR_KEY_DOC,
                            ));
                            exit();
                        }
                    }else{
                        $decrypt_data = $this->getParam('documento');
                    }*/
                    $data = array("operacion" => 'ConsultarMM',
                                "data" => array("numeroDocumento" => $numele,
                                "key" => 'dexqsdfdfgds34werfe576wgerhgrthtrrt')
                             ); 
    
                    $response = Rest_Consume::consumeApi( $data );   
    
                    if(isset($response->data)){
                        if(!empty($response->mesa)){
                            foreach ($response->mesa as $value) {
                                $_PABELLON = $value;
                            }     
    
                            if(isset($_PABELLON['pabellon'])){
                                $_PABELLOND = $_PABELLON['pabellon'];
                            }
    
                            if(isset($_PABELLOND)){
                                $_PABELLONS = 1;
                            }else{
                                $_PABELLONS = 0;
                            }
    
                            $time = time();
    
                            $this->getSmarty()->assign("_BASE_URL", BASE_URL);
                            $this->getSmarty()->assign("_MUESTRA_INFO_GNRAL", $_SESSION['PARAM_INFO_ADICIONAL']);
                            $this->getSmarty()->assign("_MUESTRA_DESC_CRED", $_SESSION['PARAM_CREDENCIALES_MM']);
                            $this->getSmarty()->assign("PARAM_CROQUIS_LOCALES", $_SESSION['PARAM_CROQUIS_LOCALES']);
                            $this->getSmarty()->assign("_PABELLON", $_PABELLONS);
                            $this->getSmarty()->assign("_MUESTRA_LOCALES_", 'S');
                            $this->getSmarty()->assign("_row", $response->data);
    
                            $documento = array(
                                'iat' => $time, // Tiempo que inició el token
                                'exp' => $time + (60*60*24), // Tiempo que expirará el token (+1 hora)
                                'data' => [ // información del usuario
                                    'numele' => $response->data['numeroDocumento']
                                    ]
                            );
    
                            $this->getSmarty()->assign("_numeroDocumento", JWT::encode($documento, Model_Constant::KEY_JWT));         
     
                            foreach ($response->mesa as $key=>$mesa) {
    
                                $token = array(
                                'iat' => $time, // Tiempo que inició el token
                                'exp' => $time + (60*20), // Tiempo que expirará el token (+1 hora)
                                'data' => [ // información del usuario
                                    'local' => $mesa['id_local']
                                    ]
                                );
                                $jwt = JWT::encode($token, Model_Constant::KEY_JWT);
                                $response->mesa[$key]['tokenLocal'] = $jwt;
                            }
    
                            $this->getSmarty()->assign("_row_mesa", $response->mesa); 
    
                            $token = array(
                                'iat' => $time, // Tiempo que inició el token
                                'exp' => $time + (60*20), // Tiempo que expirará el token (+1 hora)
                                'data' => [ // información del usuario
                                    'mesa' => $response->data['numMesa']
                                    ]
                                );
                            $jwt = JWT::encode($token, Model_Constant::KEY_JWT);
                            $this->getSmarty()->assign("_token_mesa", $jwt);
    
                            $this->getSmarty()->assign("_nombre_proceso", $_SESSION['PARAM_ELECCION_NOMBRE']); 
    
                            echo json_encode(array(
                              'success' => true,
                              //'data' => $response->data,
                              //'mesa' => $response->mesa,
                              'msj_m_mesa' => str_replace('[FECHA]',$_SESSION['PARAM_ELECCION_FECHA_L'] ,Model_Constant::MSJ_M_MESA),
                              'muestra_msj_cred' => $_SESSION['PARAM_CREDENCIALES_MM'],
                              'no_mm' => Model_Constant::NO_MM,
                              'msj_no_mm' => Model_Constant::MSJ_NO_MM,
                              'msj_credencial' => Model_Constant::MSJ_CREDENCIAL,
                              'm_mesa' => isset($response->data['cargo'])?$response->data['cargo']:'',
                              'validate_m_mesa' => isset($response->data['participaProceso'])?$response->data['participaProceso']:false,
                              'nombre_proceso' => $response->nombre_proceso,
                              'page' => $this->getSmarty()->fetch ( 'resultadoConsultaDni.tpl' ),
                              'footerm' => $this->getSmarty()->fetch ( 'footerConsulta.tpl' ),
                            ));
                            
                        }else{
                            $this->getSmarty()->assign("_BASE_URL", BASE_URL);
                            $this->getSmarty()->assign("_MUESTRA_INFO_GNRAL", $_SESSION['PARAM_INFO_ADICIONAL']);
                            $this->getSmarty()->assign("_MUESTRA_DESC_CRED", $_SESSION['PARAM_CREDENCIALES_MM']);
                            $this->getSmarty()->assign("PARAM_CROQUIS_LOCALES", $_SESSION['PARAM_CROQUIS_LOCALES']);
                            $this->getSmarty()->assign("_row", $response->data);
                            $this->getSmarty()->assign("_nombre_proceso", $_SESSION['PARAM_ELECCION_NOMBRE']);   
    
                            echo json_encode(array(
                              'success' => true,
                              'data' => $response->data,
                              'nombre_proceso' => $response->nombre_proceso,
                              'page' => $this->getSmarty()->fetch ( 'resultadoConsultaDni.tpl' ),
                              'footerm' => $this->getSmarty()->fetch ( 'footerConsulta.tpl' ),
                            ));
                        }
                }else{
                    echo json_encode(array(
                      'success' => false,
                      'message' => Model_Constant::ERROR_SERVICIO,
                    ));
                }
                }else{
                    header('location:https://www.onpe.gob.pe/');
                }
            }  
            
        }catch(Exception $e){
            echo json_encode(array(
                'success' => false,
                'message' => "El token es incorrecto o su consulta ha expirado.",
              ));
        }         
    }

    public function informacionAction(){
        $jwt = ($this->getParam('token')!==null)?$this->getParam('token'):$this->getParam('nummesa');

        $data = JWT::decode($jwt, Model_Constant::KEY_JWT, array('HS256'));

        $data = array("operacion" => "InformacionMM",
                      "data" => array("numeroMesa" => $data->data->mesa)
                     );
        $response = Rest_Consume::consumeApi( $data );   


            $cadena='';
            //$cadena .= '<a href="https://www.web.onpe.gob.pe/sep-2016/docs/material-capacitacion/cartilla-MM-sea.pdf" target="_blank" class="cuadro-grisinstruccion2">Cartilla Miembros de mesa - SEA</a> <br>';
            $cadena .= '<a href="#" class="cuadro-grisinstruccion2">Cartilla Miembros de mesa - Voto Convencional</a> <br>';
            $cadena .= '<a href="#" class="cuadro-grisinstruccion2">Cartilla Miembros de mesa - Voto Electrónico</a> <br>';
            /*$cadena .= '<a href="archivos/Cartilla_mm.pdf" target="_blank" class="cuadro-grisinstruccion2">Cartilla de Miembros de Mesa - Voto Electrónico</a> <br>';*/

        $this->getSmarty()->assign("_cadena",$cadena);

        $this->getSmarty()->assign("PARAM_SOLUCIONES_TEC",$_SESSION['PARAM_SOLUCIONES_TEC']);
        $this->getSmarty()->assign("WEB_TITLE", "ONPE - Oficina Nacional de Procesos Electorales");
        $this->getSmarty()->assign("WEB_DESCRIPCION", "La ONPE pone a su disposición la consulta de miembros de mesa.");
        $this->getSmarty()->assign("_metakeywords", "onpe, civil, estado, peruano, electoral, sistema, elecciones");  
        $this->getSmarty()->assign("PARAM_FECHA_JORNADA_CAP", $_SESSION['PARAM_FECHA_JORNADA_CAP'] );
        $this->getSmarty()->assign("PARAM_URL_LOCAL_CAP", $_SESSION['PARAM_URL_LOCAL_CAP'] );
        $this->getSmarty()->assign("PARAM_URL_EXCUSA", $_SESSION['PARAM_URL_EXCUSA'] );

        $this->getSmarty()->assign("_BASE_URL", BASE_URL);

        if(isset($response->data)){
            $this->getSmarty()->assign("_row", $response->data);   
        } 
        if(isset($response->sedes_distritales)){
            $this->getSmarty()->assign("_sedes", $response->sedes_distritales);  
        }  
        if(isset($response->local_capacitacion)){
            $this->getSmarty()->assign("_lcapacitacion", $response->local_capacitacion);  
        }       
        $this->render ( 'informacionGeneral.tpl' );  
    }

    public function credencialAction(){        
        $this->getSmarty()->assign("WEB_TITLE", "ONPE - Oficina Nacional de Procesos Electorales");
        $this->getSmarty()->assign("WEB_DESCRIPCION", "La ONPE pone a su disposición la consulta de miembros de mesa.");
        $this->getSmarty()->assign("_metakeywords", "onpe, civil, estado, peruano, electoral, sistema, elecciones");   
        $this->render ( 'credencialMM.tpl' );  
    }

    public function descargarCredencialAction(){
        $response = new stdClass();
        if(strlen(trim($this->getParam('documento'))) != 8){
            echo json_encode(array(
                  'success' => false,
                  'message' => Model_Constant::VALIDA_DNI,
                  'data' => 'VALIDA_DNI',
                ));
        }else if(strlen($this->getParam('digito')) != 1){
            echo json_encode(array(
                  'success' => false,
                  'message' => Model_Constant::VALIDA_DIGITO,
                  'data' => 'VALIDA_DIGITO',
                ));
        } else{
            $datePart=explode ( '/', $this->getParam('fecha') );
            if (!checkdate ($datePart[1],$datePart[0],$datePart[2]))
            {
                echo json_encode(array(
                    'success' => false,
                    'message' => Model_Constant::VALIDA_FECHA,
                    'data' => 'VALIDA_FECHA',
                  ));
                return;
            }
            $data = array("operacion" => 'ConsultarMM',
                            "data" => array("numeroDocumento" => $this->getParam('documento'),
                            "key" => 'dexqsdfdfgds34werfe576wgerhgrthtrrt')
                         ); 

            $responseMM = Rest_Consume::consumeApi( $data ); 
            if(isset($responseMM->data['codigoMM'])){
                if($responseMM->data['codigoMM'] == 0){
                echo json_encode(array(
                      'success' => false,
                      'message' => 'Usted no es Miembro de Mesa.',
                      'data' => 'NO_MM_CREDENCIAL',
                    ));
                }else{
                    $response = new stdClass();
                    $data = array("operacion" => "DescargarCMM",
                                  "data" => array("documento" => $this->getParam('documento'),
                                                  "digito" => $this->getParam('digito'),
                                                  "fechaNac" => $datePart[2].$datePart[1].$datePart[0],
                                                  "key" => '$qwedsa12#1234WERKLFNDFksjdfnsdjkfd')
                                 );

                    $responses = Rest_Consume::consumeApi( $data );

                    if($responses->data['valorDescarga'] == true){
                        $time = time();
                        $token = array(
                            'iat' => $time, // Tiempo que inició el token
                            'exp' => $time + (60*1), // Tiempo que expirará el token (+1 hora)
                            'data' => [ // información del usuario
                                'numele' => $this->getParam('documento')
                            ]
                        );
                        $jwt = JWT::encode($token, Model_Constant::KEY_JWT);
                        echo json_encode(array(
                          'success' => true,
                          'jwt' => $jwt
                        ));
                    }else{
                        echo json_encode(array(
                          'success' => false,
                          'message' => Model_Constant::MSJ_CRED_DATOS_INCORRECTOS,
                          'data' => 'DATOS_INCORRECTOS',
                        ));
                    }
                }
            }else{
                echo json_encode(array(
                      'success' => false,
                      'message' => 'Usted no es Miembro de Mesa.',
                      'data' => 'NO_MM_CREDENCIAL',
                    ));
            }          
        }
    }

    public function cargaCredencialAction(){
        $jwt = $this->getParam('token');
        $data = JWT::decode($jwt, Model_Constant::KEY_JWT, array('HS256'));

        $documento = $data->data->numele; //$this->getParam('documento');
        //Descarga de PDF
        $nombre = $documento.".pdf"; 
        $filename = $_SESSION['PARAM_URL_CREDENCIALES']. $documento . '.pdf';

        try {
            $s3 = new S3Client([
                'region' => 'us-east-2',
                'version' => 'latest',
                'credentials' => [
                    'key'    => Model_Constant::ACCESS_KEY,
                    'secret' => Model_Constant::SECRET_KEY,
                ],
                'http'    => [
                    'verify' => false
                ]
            ]);

            $bucket = str_replace ("http://","",$_SESSION['PARAM_URL_CREDENCIALES']);
            $bucket = str_replace ("https://","",$bucket);

            $result = $s3->getObject([
                    'Bucket' => $bucket ,
                    'Key'    => $nombre
            ]);

            $size = $result["ContentLength"];
            header("Content-Transfer-Encoding: binary"); 
            header("Content-type: {$result['ContentType']}"); 
            header("Content-Disposition: attachment; filename=$nombre"); 
            header("Content-Length:$size"); 
            echo $result['Body'];

        } catch (S3Exception $e) {
             echo $e->getMessage() . PHP_EOL;
            echo 'no-credencial';
        }

    }

    public function validatenombresAction(){
        $response = new stdClass();
        if($this->getParam("appaterno") == null && $this->getParam("apmaterno") == null){
                $response->success = false;            
                $response->message = Model_Constant::VALIDA_APPAT_APMAT;
                $response->data = 'VALIDA_APPAT_APMAT';
                echo json_encode($response);
        }else if($this->getParam("nombres") == ''){
            $response->success = false;            
            $response->message = Model_Constant::VALIDA_NOMBRES;
            $response->data = 'VALIDA_NOMBRES';
            echo json_encode($response);
        }else if(strlen($this->getParam('dep')) != 6){
            $response->success = false;            
            $response->message = Model_Constant::VALIDA_DEP . $_SESSION['ETIQUETA_DEP'];
            $response->data = 'VALIDA_DEP';
            echo json_encode($response);
        }else if(strlen($this->getParam('pro')) != 6){
            $response->success = false;            
            $response->message = Model_Constant::VALIDA_PRO . $_SESSION['ETIQUETA_PRO'];
            $response->data = 'VALIDA_PRO';
            echo json_encode($response);
        }else if(strlen($this->getParam('dis')) != 6){
            $response->success = false;            
            $response->message = Model_Constant::VALIDA_DIS . $_SESSION['ETIQUETA_DIS'];
            $response->data = 'VALIDA_DIS';
            echo json_encode($response);
        }else{
            $response->success = true; 
            echo json_encode(array(
                  'success' => $response,
            ));
        }
    }

    public function listadoAction () { 
        
        $paterno = str_replace( "'", "''",$this->getParam("appaterno"), $count1);
        $materno = str_replace( "'", "''",$this->getParam("apmaterno"), $count2);
        $nombres = str_replace( "'", "''",$this->getParam("nombres"), $count3);

        //echo $paterno;

        header('Content-Type: application/javascript');
        $data = array("operacion" => 'ConsultarPorNombres',
                      "data" => array("apePaterno" => ($count1 >= 1) ? mb_strtoupper ($paterno) : mb_strtoupper ($this->getParam("appaterno")),
                                      "apeMaterno" => ($count2 >= 1) ? mb_strtoupper ($materno) : mb_strtoupper ($this->getParam("apmaterno")),
                                      "nombres" => ($count3 >= 1) ? mb_strtoupper ($nombres) : mb_strtoupper ($this->getParam("nombres")),
                                      "ubigeo" => $this->getParam('dis'),
                                      "key" => '2serdfe4$fsdwwerds590'));

        $response = Rest_Consume::consumeApi( $data );

        if(isset($response->data)){

            if($response->message == ''){
                $success = true;
            }else{
                $success = false;
            }

            $this->getSmarty()->assign("_nombre_proceso", $_SESSION['PARAM_ELECCION_NOMBRE']);
            $this->getSmarty()->assign("_success", $success);
            $this->getSmarty()->assign("_BASE_URL", BASE_URL);

            $time=time();

            foreach ($response->data as $key=>$resultado) {
    
                $token = array(
                'iat' => $time, // Tiempo que inició el token
                'exp' => $time + (60*10), // Tiempo que expirará el token
                'data' => [ // información del usuario
                    'numele' => $resultado['dni']
                    ]
                );
                $jwt = JWT::encode($token, Model_Constant::KEY_JWT);
                $response->data[$key]['token'] = $jwt;
            }

            $this->getSmarty()->assign('_consulta', $response->data); 
            $this->getSmarty()->assign("_texto_tacha", $_SESSION['PARAM_TACHA_TEXTO']);
            echo json_encode(array(
              'success' => true,
              'data' => $response->data,
              'footerm' => $this->getSmarty()->fetch ( 'footerListado.tpl' ),
              'page' => $this->getSmarty()->fetch ( 'resultadoPorNombres.tpl' ),
            ));
        }else{
            echo json_encode(array(
              'success' => false,
              'message' => Model_Constant::ERROR_SERVICIO,
            ));
        }
        
    }

    public function descargarCroquisAction(){

        $jwt = $this->getParam('token');
        $data = JWT::decode($jwt, Model_Constant::KEY_JWT, array('HS256'));

        $c_local = $data->data->local; //$c_local = $this->getParam('c_local');;

        //Descarga de PDF
        $nombre = "Croquis.pdf";

        $filename = $_SESSION['PARAM_URL_CROQUIS']. $c_local . '.pdf';

        try {
            $s3 = new S3Client([
                'region' => 'us-east-2',
                'version' => 'latest',
                'credentials' => [
                    'key'    => Model_Constant::ACCESS_KEY,
                    'secret' => Model_Constant::SECRET_KEY,
                ],
                'http'    => [
                    'verify' => false
                ]
            ]);

            $bucket = str_replace ("http://","",$_SESSION['PARAM_URL_CROQUIS']);
            $bucket = str_replace ("https://","",$bucket);


            $result = $s3->getObject([
                    'Bucket' => $bucket ,
                    'Key'    => $c_local . '.pdf'
            ]);

            $size = $result["ContentLength"];
            header("Content-Transfer-Encoding: binary"); 
            header("Content-type: {$result['ContentType']}"); 
            header("Content-Disposition: attachment; filename=$nombre"); 
            header("Content-Length:$size"); 
            echo $result['Body'];

        } catch (S3Exception $e) {
             //echo $e->getMessage() . PHP_EOL;
            echo 'no-croquis';
        }
        
    }

    public function compartirAction(){
        $this->getSmarty()->assign('_documento', $this->getParam('dni'));
        $this->getSmarty()->assign('_BASE_URL', BASE_URL);
        echo json_encode(array(
              'page' => $this->getSmarty()->fetch ( 'compartir.tpl' ),
            ));
    }    

    public function captchaAction(){
        $captcha = $this->getParam('captcha');
        $img = new securimage();
        echo $img->show();
        //echo $captcha;
    }

    function mc_encrypt($str,$passw=null){
        $r='';
        $md=$passw?substr(md5($passw),0,16):'';
        $str=base64_encode($md.$str);
        $abc='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $a=str_split('+/='.$abc);
        $b=strrev('-_='.$abc);
        if($passw){
            $b=$this->_mixing_passw($b,$passw);
        }else{
            $r=rand(10,65);
            $b=mb_substr($b,$r).mb_substr($b,0,$r);
        }
        $s='';
        $b=str_split($b);
        $str=str_split($str);
        $lens=count($str);
        $lena=count($a);
        for($i=0;$i<$lens;$i++){
            for($j=0;$j<$lena;$j++){
                if($str[$i]==$a[$j]){
                    $s.=$b[$j];
                }
            };
        };
        return $s.$r;
    }

    function mc_decrypt($str,$passw=null){
        $abc='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $a=str_split('+/='.$abc);
        $b=strrev('-_='.$abc);
        if($passw){
            $b=$this->_mixing_passw($b,$passw);
        }else{
            $r=mb_substr($str,-2);
            $str=mb_substr($str,0,-2);
            $b=mb_substr($b,$r).mb_substr($b,0,$r);
        }
        $s='';
        $b=str_split($b);
        $str=str_split($str);
        $lens=count($str);
        $lenb=count($b);
        for($i=0;$i<$lens;$i++){
            for($j=0;$j<$lenb;$j++){
                if($str[$i]==$b[$j]){
                    $s.=$a[$j];
                }
            };
        };
        $s=base64_decode($s);
        if($passw&&substr($s,0,16)==substr(md5($passw),0,16)){
            return substr($s,16);
        }else{
            return $s;
        }
    }

    function _mixing_passw($b,$passw){
        $s='';
        $c=$b;
        $b=str_split($b);
        $passw=str_split(sha1($passw));
        $lenp=count($passw);
        $lenb=count($b);
        for($i=0;$i<$lenp;$i++){
            for($j=0;$j<$lenb;$j++){
                if($passw[$i]==$b[$j]){
                    $c=str_replace($b[$j],'',$c);
                    if(!preg_match('/'.$b[$j].'/',$s)){
                        $s.=$b[$j];
                    }
                }
            };
        };
        return $c.''.$s;
    }
}