<?php

class DbHandler {

    private $conn;

    function __construct() {
        try {
            require_once 'DbConnectMysql.php';
            $db = new dbConnectMysql();
            $this->conn = $db->connect(); 
        } catch (Exception $e) {
            echo 'Excepción: ', $e->getMessage(), "\n";
        }
    }

    public function getLogin($user, $password) {
        $query = "SELECT * FROM tab_usuario 
        WHERE usuario = '$user' 
        AND clave = '$password'";
        $rs = $this->conn->query($query);
        return $rs;
    }

    public function getIncidentes() {
        $query = "SELECT ID_INCIDENCIA, DETALLE, OBSERVACION, ESTADO, 0 EDIT FROM tab_incidencia";
        $rs = $this->conn->query($query);
        return $rs;
    }
    public function getProductos() {
        $query = "SELECT ID_PRODUCTO, NOMBRE_PRODUCTO, MARCA, MODELO, SERIE, ID_CATEGORIA, 0 EDIT   FROM tab_producto";
        $rs = $this->conn->query($query);
        return $rs;
    }
    public function getClientes() {
        $query = "SELECT ID_CLIENTE, TIPO_DOCUMENTO, DOCUMENTO, AP_PATERNO, AP_MATERNO, NOMBRES_RAZON, ESTADO, 0 EDIT FROM tab_cliente";
        $rs = $this->conn->query($query);
        return $rs;
    }
    public function getCategorias() {
        $query = "SELECT ID_CATEGORIA, NOMBRE_CATEGORIA, 0 EDIT FROM tab_categoria";
        $rs = $this->conn->query($query);
        return $rs;
    }        

    public function saveIncidente($data){        
        $stmt = 'INSERT INTO TAB_INCIDENCIA(ID_INCIDENCIA, DETALLE, ESTADO, OBSERVACION)';
        $stmt .= sprintf(' VALUES (NULL, "%s", %d, "%s")', $data->{'detalle'}, $data->{'estado'}, $data->{'observacion'});

        $ok = $this->conn->query($stmt);
    
        if ($ok) {
            $codRespuesta = 1;
        } else {
            $codRespuesta = -1;
        }
        return $codRespuesta;
    }
    public function saveProducto(){
        $stmt = 'INSERT INTO TAB_PRODUCTO(ID_PRODUCTO, NOMBRE_PRODUCTO, MARCA, MODELO, SERIE, ID_CATEGORIA)';
        $stmt .= sprintf(' VALUES (NULL, "%s", "%s", "%s", "%s", %d)', $data->{'nombre'}, $data->{'marca'}, $data->{'modelo'}, $data->{'serie'}, $data->{'idCategoria'});

        $ok = $this->conn->query($stmt);
    
        if ($ok) {
            $codRespuesta = 1;
        } else {
            $codRespuesta = -1;
        }
        return $codRespuesta;
    }
    public function saveCliente($data){
        $stmt = 'INSERT INTO TAB_CLIENTE(ID_CLIENTE, TIPO_DOCUMENTO, DOCUMENTO, AP_PATERNO, AP_MATERNO, NOMBRES_RAZON, ESTADO)';
        $stmt .= sprintf(' VALUES (NULL, %d, "%s", "%s", "%s", "%s", %d)', $data->{'tipoDoc'}, $data->{'documento'}, $data->{'apPaterno'}, $data->{'apMaterno'}, $data->{'nombre'}, $data->{'estado'});

        $ok = $this->conn->query($stmt);
    
        if ($ok) {
            $codRespuesta = 1;
        } else {
            $codRespuesta = -1;
        }
        return $codRespuesta;
    }
    public function saveCategoria($data){
        $stmt = 'INSERT INTO TAB_CATEGORIA(ID_CATEGORIA, NOMBRE_CATEGORIA)';
        $stmt .= sprintf(' VALUES (NULL, "%s")', $data->{'nombre'});
        $ok = $this->conn->query($stmt);
    
        if ($ok) {
            $codRespuesta = 1;
        } else {
            $codRespuesta = -1;
        }
        return $codRespuesta;
    }

    public function removeIncidente($data){        
        $id= $data->{'id'};
        $query = "DELETE FROM tab_incidencia WHERE ID_INCIDENCIA = '$id'";
        $ok = $this->conn->query($query);
    
        if ($ok) {
            $codRespuesta = 1;
        } else {
            $codRespuesta = -1;
        }
        return $codRespuesta;
    }

    public function removeProducto($data){
        $id= $data->{'id'};
        $query = "DELETE FROM tab_producto WHERE ID_PRODUCTO = '$id'";
        $ok = $this->conn->query($query);
    
        if ($ok) {
            $codRespuesta = 1;
        } else {
            $codRespuesta = -1;
        }
        return $codRespuesta;
    }
    public function removeCliente($data){
        $id= $data->{'id'};
        $query = "DELETE FROM tab_cliente WHERE ID_CLIENTE = '$id'";
        $ok = $this->conn->query($query);
    
        if ($ok) {
            $codRespuesta = 1;
        } else {
            $codRespuesta = -1;
        }
        return $codRespuesta;
    }
    public function removeCategoria($data){
        $id= $data->{'id'};
        $query = "DELETE FROM tab_categoria WHERE ID_CATEGORIA = '$id'";
        $ok = $this->conn->query($query);
    
        if ($ok) {
            $codRespuesta = 1;
        } else {
            $codRespuesta = -1;
        }
        return $codRespuesta;        
    }
}

?>
