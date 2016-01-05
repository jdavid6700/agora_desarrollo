<?php
use asignacionPuntajes\salariales\premiosDocente\Sql;

$conexion = "estructura";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );


//Estas funciones se llaman desde ajax.php y estas a la vez realizan las consultas de Sql.class.php 

if ($_REQUEST ['funcion'] == 'consultarClase') {
	$cadenaSql = $this->sql->getCadenaSql ( 'ciiuClase', $_REQUEST["valor"]);
	$datos = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
	echo json_encode( $datos );
}



if ($_REQUEST ['funcion'] == 'consultarCiudad') {
	$cadenaSql = $this->sql->getCadenaSql ( 'ciiuGrupo', $_REQUEST["valor"]);
	$datos = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
	echo json_encode( $datos );
}

?>