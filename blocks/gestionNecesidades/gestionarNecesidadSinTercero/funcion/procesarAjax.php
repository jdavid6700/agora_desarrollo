<?php
use asignacionPuntajes\salariales\premiosDocente\Sql;

$conexion = "agora";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );


//Estas funciones se llaman desde ajax.php y estas a la vez realizan las consultas de Sql.class.php 


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
	
	if ($_REQUEST ['funcion'] == 'consultarNBC') {
		$cadenaSql = $this->sql->getCadenaSql ( 'buscarNBCAjax', $_REQUEST['valor'] );
		$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		$resultado = json_encode ( $resultado);
		echo $resultado;
	}

}

?>