<?php

require_once 'DbHandler.php';

include_once 'app/controller.php';
include_once 'rest/Consume.php';
include_once 'app/Method.php';
include_once 'libs/captcha/securimage.php'; 
include_once 'default/constant.php'; 


class Services_LoginController
{       


    private $dbhandler;
    private $_smarty;
    private $_jscript;

    public function __construct ( $params = array () ) {
        try {         
            $this->_smarty = $GLOBALS[ 'smarty' ];
            $this->_params = $_POST + $_GET + $params;
            $this->dbhandler = new DbHandler();   
        } catch (Exception $e) {
            echo 'Excepción: ', $e->getMessage(), "\n";
        }
    }    

    private function render ($template) {
        $this->_smarty->assign ( '_BASE_URL', BASE_URL );
        $this->_smarty->display ($template);

    }    


    public function indexAction ($request) {
        $data = json_decode($request);
        $user = $data->{'user'};
        $password = $data->{'password'};        
        $response = $this->dbhandler->getLogin($user, $password);
        if ($response->num_rows > 0) {
            while ($_data = $response->fetch_assoc()) {
                $_row[]=$_data;
            }
            $temp = new stdClass();
            foreach ($_row as $_rows) {
                $temp->id = $_rows['ID_USUARIO'];
                $temp->usuario = $_rows['USUARIO'];
                $temp->apPaterno = $_rows['AP_PATERNO'];
                $temp->apMaterno = $_rows['AP_MATERNO'];
                $temp->nombres = $_rows['NOMBRES'];
                $temp->estado = $_rows['ESTADO'];
                $jReponse = $temp;                
            }
            echo '{"success":true,"data":' . json_encode($jReponse) . '}';           
        }else{
            echo '{"success":false,"message":"Usuario y/o Contraseña Incrorrecta"}';
        }         

    }

}