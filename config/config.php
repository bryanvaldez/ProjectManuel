<?php

date_default_timezone_set ( 'America/Lima' );

header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1;mode=block');

header ( 'Content-Type: text/html; charset=utf-8' );
putenv ( 'NLS_LANG=american_america.UTF8' );

defined ( 'BASE_URL' ) || define ( 'BASE_URL', 'http://localhost:8081/MANUEL/' );
defined ( 'DOCUMENT_ROOT' ) || define ( 'DOCUMENT_ROOT', realpath ( dirname ( __FILE__ ) . '/../' ) . '/' );

/*define("DB_HOST"," (DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=192.168.49.149)(PORT=1521))(CONNECT_DATA=(SERVICE_NAME=BDD3S4))) ");
define("DB_TIPO","oci8");
define("DB_USERNAME","EVA_CONSULTA_VEC");
define("DB_PASSWORD","EVA_CONSULTA_VEC");*/

# Carga las librerias base
require 'libs/smarty/libs/Smarty.class.php';
require 'libs/adodb/adodb.inc.php';

$smarty = new Smarty();
$smarty->template_dir = DOCUMENT_ROOT . 'view/tpl';
$smarty->compile_dir = DOCUMENT_ROOT . 'view/compile';
$smarty->cache_dir = DOCUMENT_ROOT . 'view/cache';
$smarty->config_dir = DOCUMENT_ROOT . 'view/config';
$smarty->caching = false; /* Cambiar a 'true' de ser necesario */
$smarty->compile_check = true;
$smarty->debugging = false;
$smarty->left_delimiter = '{{';
$smarty->right_delimiter = '}}';


