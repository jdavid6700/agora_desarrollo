<?php
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}

/*
 * To change this license header, choose License Headers in Project Properties. To change this template file, choose Tools | Templates and open the template in the editor.
 */


$esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );

$rutaBloque = $this->miConfigurador->getVariableConfiguracion ( "raizDocumento" ) . "/blocks/inventarios/";
$rutaBloque .= $esteBloque ['nombre'];
$host = $this->miConfigurador->getVariableConfiguracion ( "host" ) . $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/inventarios/" . $esteBloque ['nombre'];

$conexion = "estructura";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );

$resultado = '';

$poliza = $_REQUEST ['poliza']==''?0:$_REQUEST ['poliza'];

$arreglo = array (
		$_REQUEST ['num_contrato'],
		$_REQUEST ['fecha_inicio_c'],
		$_REQUEST ['fecha_final_c'],
		$_REQUEST ['supervisor'],
		$_REQUEST ['numActoAdmin'],
		$_REQUEST ['tipoActoAdmin'],
		$_REQUEST ['numCDP'],		
		$_REQUEST ['numRP'],
		$_REQUEST ['fecha_RP'],
		$_REQUEST ['modalidad'],
		$_REQUEST ['proveedor'],
		$_REQUEST ['valor'],
		$_REQUEST ['rubro'],
		$poliza,		
		$_REQUEST ['formaPago'],
		$_REQUEST ['idContrato']
);

$cadenaSql = $this->sql->getCadenaSql ( "actualizarContrato", $arreglo );
$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso',$arreglo,"actualizarContrato" );

// Crear Variables necesarias en los métodos

$variable = '';

if ($resultado) {
	$this->funcion->Redireccionador ( 'actualizoContrato', $_REQUEST['idContrato'] );
	exit();
	
} else {
	$this->funcion->Redireccionador ( 'noactualizoContrato', $_REQUEST['idContrato'] );
	exit();
}