<?php
use asignacionPuntajes\salariales\premiosDocente\Sql;

$conexion = "estructura";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );

$conexion = 'core_central';
$coreRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

//Estas funciones se llaman desde ajax.php y estas a la vez realizan las consultas de Sql.class.php


//-------------------------------------------------
//-------------------------------------------------
//Validación Petición AJAX Parametro SQL Injection

if(isset($_REQUEST['valor'])){
	if(is_numeric($_REQUEST['valor'])){
		$subclase = $_REQUEST['valor'];
		settype($_REQUEST['valor'], 'integer');
		$secure = true;
	}else{
		$secure = false;
	}
}else{
	
	if(preg_match("/^[0-9]+$/", $_REQUEST ['documento']) || preg_match("/^[0-9a-zA-Z]+$/", $_REQUEST ['documento'])){
		$secure = true;
	}else{
		$arregloUniqueFail = array (
				'seguridad' => true,
				'fallo' => "true"
		);
		$secure = false;
		echo json_encode( $arregloUniqueFail );
	}
	
	
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
	
	if ($_REQUEST ['funcion'] == 'consultarDepartamentoAjax') {
		$cadenaSql = $this->sql->getCadenaSql ( 'buscarDepartamentoAjax', $_REQUEST['valor'] );
		$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		$resultado = json_encode ( $resultado);
		echo $resultado;
	}
	if ($_REQUEST ['funcion'] == 'consultarCiudadAjax') {
		$cadenaSql = $this->sql->getCadenaSql ( 'buscarCiudadAjax', $_REQUEST['valor'] );
		$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		$resultado = json_encode ( $resultado);
		echo $resultado;
	}
	
	if ($_REQUEST ['funcion'] == 'consultarPaisAjax') {
		$cadenaSql = $this->sql->getCadenaSql ( 'buscarPaisCod', $_REQUEST['valor'] );
		$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		$resultado = json_encode ( $resultado);
		echo $resultado;
	}
	
	if ($_REQUEST ['funcion'] == 'consultarNomenclatura') {
		$cadenaSql = $this->sql->getCadenaSql ( 'buscarNomenclaturaAbreviatura', $_REQUEST['valor'] );
		$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		$resultado = json_encode ( $resultado);
		echo $resultado;
	}
	
	if ($_REQUEST ['funcion'] == 'consultarNBC') {
		$cadenaSql = $this->sql->getCadenaSql ( 'buscarNBCAjax', $_REQUEST['valor'] );
		$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		$resultado = json_encode ( $resultado);
		echo $resultado;
	}
	
	if ($_REQUEST ['funcion'] == 'consultarCIIUPush') {
		$cadenaSql = $this->sql->getCadenaSql ( 'ciiuSubClaseByNumPush', $subclase);
		$datos = $coreRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		echo json_encode( $datos );
	}
	
	if ($_REQUEST ['funcion'] == 'consultarActividad') {
		$cadenaSql = $this->sql->getCadenaSql ( 'consultarActividades', $subclase);
		$datos = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		echo json_encode( $datos );
	}
	
	if ($_REQUEST ['funcion'] == 'consultarRepresentante') {
		if(isset($_REQUEST ['documento'])){$_REQUEST ['documento']=mb_strtoupper($_REQUEST ['documento'],'utf-8');}
		$arregloUnique = array (
				'num_documento' => $_REQUEST ['documento'],
				'tipo_persona' => "NATURAL"
		);
		$cadenaSql = $this->sql->getCadenaSql ( "verificarProveedor", $arregloUnique);
		$datos = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		echo json_encode( $datos );
	}
	
}


?>