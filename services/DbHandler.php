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


}

?>
