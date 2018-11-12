<?php
include_once 'rest/response.php';

class Rest_Consume
{

    /**
     * 
     * @param type $url Url to send request
     * @param type $method Method to request GET or POST
     * @param type $data Array of data to send
     * @return \App_Response
     */
    public static function consumeApi ( $data ) {

        $data = json_encode($data);

        $ch = curl_init('http://localhost:8081/WEBSERVICE/app/ejecutarOperacion');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data))
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
		
		// echo $data;exit;
 
        //execute post 
        $result = curl_exec($ch);
        //var_dump($result);

        //close connection
        curl_close($ch);

        $rConvert = json_decode ( $result, true );  
        $response = new App_Response();


        if(isset($rConvert[ 'elector' ])){
            if ( isset ( $rConvert ) ) {
                $response->data = $rConvert[ 'elector' ];
            }   
            if ( isset ( $rConvert[ 'mesas' ] ) ) {
                $response->mesa = $rConvert[ 'mesas' ];
            }  
            if ( isset ( $rConvert[ 'nombre_proceso' ] ) ) {
                $response->nombre_proceso = $rConvert[ 'nombre_proceso' ];
            }  
        }
        if(isset($rConvert[ 'electores' ])){
            if ( isset ( $rConvert ) ) {
                $response->data = $rConvert[ 'electores' ];
            }   
            if ( isset ( $rConvert ) ) {
                $response->message = $rConvert[ 'message' ];
            } 
            if ( isset ( $rConvert ) ) {
                $response->nombre_proceso = $rConvert[ 'nombre_proceso' ];
            } 
        }
        if(isset($rConvert[ 'odpe' ])){
            if ( isset ( $rConvert ) ) {
                $response->data = $rConvert[ 'odpe' ];
            }   
            if ( isset ( $rConvert ) ) {
                $response->sedes_distritales = $rConvert[ 'sedes_distritales' ];
            }
            if ( isset ( $rConvert ) ) {
                $response->local_capacitacion = $rConvert[ 'local_capacitacion' ];               
            }
        }
        if(isset($rConvert[ 'credencial' ])){
            if ( isset ( $rConvert ) ) {
                $response->data = $rConvert[ 'credencial' ];
            }   
        }
        if(isset($rConvert[ 'ubigeo' ])){
            if ( isset ( $rConvert ) ) {
                $response->data = $rConvert[ 'ubigeo' ];
            }   
        }
        if(isset($rConvert[ 'parametro' ])){
            if ( isset ( $rConvert ) ) {
                $response->data = $rConvert[ 'parametro' ];
            }   
        }        
        return $response;
    }

}






