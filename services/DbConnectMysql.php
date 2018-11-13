<?php
putenv("NLS_LANG=american_america.UTF8");
require_once 'libs/adodb/adodb.inc.php';

class dbConnectMysql {

    function __construct() {
        define("DB_USERNAME", 'root');
        define("DB_PASSWORD", ''); 
        define("DB_HOST", 'localhost');
        define("DB_NAME", 'manuel');
        define("DB_TIPO", 'mysql');
    }
  

    /**
     * Establishing database connection
     * @return database connection handler
     */
    function connect() {
        try {
            $db = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
            $db->set_charset("utf8");
            return $db;
        } catch (Exception $e) {
            echo 'Excepción: ', $e->getMessage(), "\n";
        }
    }

}

?>