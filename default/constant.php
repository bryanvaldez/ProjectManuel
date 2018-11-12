<?php
//$tipoConsulta = 0 ----->Listado
//$tipoConsulta = 1 ----->Consulta
//define("TIPO_CONSULTA", '0');

class Model_Constant
{
	const TIPO_CONSULTA = '1';
	const MUESTRA_INFO_GNRAL = '1';
	const MUESTRA_DESC_CRED = '0';
	const TEXTO_TACHA = '1';


	//const TITULO_VOTACION = 'Consulte <br/>si es Miembro de Mesa';
	const TITULO_VOTACION  = 'Consulte su Local de Votación <br>y si es Miembro de Mesa';
	const MSJ_CRED_DATOS_INCORRECTOS = 'Alguno de los datos ingresados son incorrectos.';
	const MSJ_CRED_NO_PROCESADA = 'Su Credencial aún no ha sido procesada';
	const DNI_OBLIGATORIO = 'Su DNI es Obligatorio.';
	const VALIDA_DNI = 'Verifique el DNI ingresado (8 dígitos).';
	const VALIDA_DIGITO = 'El Dígito de Chequeo es obligatorio.';
	const VALIDA_GRUPO_VOTACION = 'El campo Grupo de Votación es obligatorio (6 dígitos).';
	const VALIDA_FECHA = 'La Fecha de Nacimiento no es válida.';
	const VALIDA_CAPTCHA = 'El Código Captcha ingresado es incorrecto.';
	const VALIDA_APPAT_APMAT = 'Ingrese Apellidos.';
	const VALIDA_NOMBRES = 'El campo nombre no puede estar vacío.';
	const VALIDA_DEP = 'Seleccione ';
	const VALIDA_PRO = 'Seleccione ';
	const VALIDA_DIS = 'Seleccione ';
	const ERROR_SERVICIO = 'Servicio no disponible, intentelo nuevamente.';
	const ERROR_KEY_DOC = 'Datos incorrectos, intentelo nuevamente.';

	const MSJ_M_MESA = '[FECHA], volveremos a contar con su participación.<br><br>
	Para garantizar el inicio de la jornada debe estar presente en su Local de Votación a las 7:30 AM.<br><br>
	Recuerde que los miembros de mesa tienen un deber ciudadano al ser la autoridad y garantizar el derecho al voto en su mesa de sufragio.';
	const MSJ_CREDENCIAL = 'Complete el registro de información con los datos de su DNI seleccionando el icono para descargar su credencial.';
	const NO_MM = 'Hagamos más fluida la votación.';
	const MSJ_NO_MM = 'Para votar más rápido, comparta, imprima y/o anote los siguientes datos, le ayudarán a ubicar directamente su mesa, pabellón y número de orden en la Relación de Electores.<br><br>
	Comparta esta recomendación con su familia y amigos';

	const URL_CREDENCIALES='http://10.1.1.35/credenciales/';
	const URL_MAPAS='http://10.1.1.35/mapas/';


	//const Parametro Codigo

	const PARAM_ELECCION = '0';
	const PARAM_TIPO_CONSULTA = '3';
	const PARAM_TACHA = '4';
	const PARAM_BANNER = '1';
	const PARAM_INFO_ADICIONAL = '5';
	const PARAM_FECHA_JORNADA_CAP = '6';
	const PARAM_URL_LOCAL_CAP = '7';
	const PARAM_SOLUCIONES_TEC = '8';
	const PARAM_CROQUIS_LOCALES = '11';
	const PARAM_CREDENCIALES_MM = '9';
	const PARAM_ETIQUETAS = '2';
	const PARAM_HORA_CIERRE = '13';
	const PARAM_URL_CREDENCIALES = '10';
	const PARAM_URL_CROQUIS = '12';
	const PARAM_TITULO = '14';
	const PARAM_URL_EXCUSA = '15';


	const ACCESS_KEY='AKIAJIMW6E3EMUNXV7ZA';
	const SECRET_KEY='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';
	const KEY_JWT='s3Cr3tJwt$';
}