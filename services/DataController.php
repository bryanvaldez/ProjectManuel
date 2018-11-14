<?php

require_once 'DbHandler.php';

include_once 'app/controller.php';
include_once 'rest/Consume.php';
include_once 'app/Method.php';
include_once 'libs/captcha/securimage.php'; 
include_once 'default/constant.php'; 


class Services_DataController
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


    public function indexAction ($type) {

    	try {
	    	if($type ==1){
	    		$LisData = $this->dbhandler->getIncidentes();
	    	}else if($type ==2){
				$LisData = $this->dbhandler->getProductos();
	    	}else if($type ==3){
				$LisData = $this->dbhandler->getClientes();
	    	}else if($type ==4){
				$LisData = $this->dbhandler->getCategorias();
	    	}
	    	if ($LisData->num_rows > 0) {
	    		$rows = array();
	            while ($data = $LisData->fetch_assoc()) {
	                $rows[]=$data;
	            }
	            $jReponse = $this->setData($type, $rows);
            	echo '{"success":true,"data":' . json_encode($jReponse) . '}'; 
	    	}
    		
    	} catch (Exception $e) {
    		
    	}
    }

    public function saveAction ($request) {
         $body = json_decode($request);
         $type = $body->{'type'};
         $data = $body->{'data'};
        try {
            if($type ==1){
                $code = $this->dbhandler->saveIncidente($data);
            }else if($type ==2){
                $code = $this->dbhandler->saveProducto($data);
            }else if($type ==3){
                $code = $this->dbhandler->saveCliente($data);
            }else if($type ==4){
                $code = $this->dbhandler->saveCategoria($data);
            }

            if($code ==1){
                echo '{"success":true}';  
            }else{
                echo '{"success":false,"message":"No se pudo guardar el registro"}'; 
            }
        } catch (Exception $e) {
            echo '{"success":false,"message":"Error de conexion"}';             
        }
        
    }    

    public function removeAction ($request) {
         $body = json_decode($request);
         $type = $body->{'type'};
         $data = $body->{'data'};
        try {
            if($type ==1){
                $code = $this->dbhandler->removeIncidente($data);
            }else if($type ==2){
                $code = $this->dbhandler->removeProducto($data);
            }else if($type ==3){
                $code = $this->dbhandler->removeCliente($data);
            }else if($type ==4){
                $code = $this->dbhandler->removeCategoria($data);
            }
            if($code ==1){
                echo '{"success":true}';  
            }else{
                echo '{"success":false,"message":"No se pudo eliminar el registro"}'; 
            }

        } catch (Exception $e) {
            echo '{"success":false,"message":"Error de conexion"}';             
        }
        
    }     

    private function setData($type, $rows){
    	$jReponse = array();
    	if($type ==1){
    		$jReponse = $this->setIncidentes($rows);
    	}else if($type ==2){
			$jReponse = $this->setProductos($rows);
    	}else if($type ==3){
			$jReponse = $this->setClientes($rows);
    	}else if($type ==4){
			$jReponse = $this->setCategorias($rows);
    	}  	
        return $jReponse;
    }

    private function setIncidentes ($rows){
    	$jReponse = array();
        foreach ($rows as $row) {
	        $temp = new stdClass();
            $temp->id = $row['ID_INCIDENCIA'];
            $temp->detalle = $row['DETALLE'];
            $temp->observacion = $row['OBSERVACION'];
            $temp->estado = $row['ESTADO'];   
            $temp->edit = $row['EDIT'];      
            $jReponse[] = $temp;                
        }  
        return $jReponse;  	    	
    }
    private function setProductos ($rows){
    	$jReponse = array();
        foreach ($rows as $row) {
	        $temp = new stdClass();
            $temp->id = $row['ID_PRODUCTO'];
            $temp->nombre = $row['NOMBRE_PRODUCTO'];
            $temp->marca = $row['MARCA'];
            $temp->modelo = $row['MODELO'];        
            $temp->serie = $row['SERIE'];
            $temp->tipoCategoria = $row['ID_CATEGORIA'];
            $temp->edit = $row['EDIT']; 
            $jReponse[] = $temp;                
        }  
        return $jReponse; 
    }
    private function setClientes ($rows){
    	$jReponse = array();
        foreach ($rows as $row) {
	        $temp = new stdClass();
            $temp->id = $row['ID_CLIENTE'];
            $temp->tipoDocumento = $row['TIPO_DOCUMENTO'];
            $temp->documento = $row['DOCUMENTO'];
            $temp->apPaterno = $row['AP_PATERNO'];        
            $temp->apMaterno = $row['AP_MATERNO'];        
            $temp->nombre = $row['NOMBRES_RAZON'];
            $temp->estado = $row['ESTADO'];
            $temp->edit = $row['EDIT']; 
            $jReponse[] = $temp;                
        }  
        return $jReponse; 
    }
    private function setCategorias ($rows){
    	$jReponse = array();
        foreach ($rows as $row) {
	        $temp = new stdClass();
            $temp->id = $row['ID_CATEGORIA'];
            $temp->nombre = $row['NOMBRE_CATEGORIA'];
            $temp->edit = $row['EDIT']; 
            $jReponse[] = $temp;                
        }  
        return $jReponse; 
    }        



}