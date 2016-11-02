<?php
use inventarios\gestionContrato\Sql;


$conexion = "estructura";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

//-------------------------------------------------
//-------------------------------------------------
//Validación Petición AJAX Parametro SQL Injection
if(is_numeric($_REQUEST['valor'])){
	settype($_REQUEST['valor'], 'integer');
	$secure = true;
}else{
	$secure = false;
}
//-------------------------------------------------
//-------------------------------------------------


if($secure){
	if ($_REQUEST ['funcion'] == 'consultarPersona') {
		$cadenaSql = $this->sql->getCadenaSql ( 'consultarPersonaNatural', $_REQUEST["valor"]);
		$datos = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		echo json_encode( $datos );
	}
	
}





?>