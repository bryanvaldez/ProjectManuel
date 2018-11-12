<?php
include_once 'app/controller.php';
include_once 'rest/Consume.php';
include_once 'app/Method.php';
include_once 'libs/captcha/securimage.php'; 
include_once 'default/constant.php'; 


class Default_BaseController
{       

    private $_smarty;
    private $_jscript;

    public function __construct ( $params = array () ) {
        $this->_smarty = $GLOBALS[ 'smarty' ];
        $this->_params = $_POST + $_GET + $params;
    }    

    private function render ($template) {
        $this->_smarty->assign ( '_BASE_URL', BASE_URL );
        $this->_smarty->display ($template);

    }    


    public function indexAction () {
        $this->render('layout.tpl');
    }



}