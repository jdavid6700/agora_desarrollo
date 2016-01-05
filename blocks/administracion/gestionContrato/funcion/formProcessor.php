<?php

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

$fechaActual = date ( 'Y-m-d' );

$arreglo = array (
		$_REQUEST ['objeto'],
		$_REQUEST ['num_contrato'],
		$_REQUEST ['fecha_inicio_c'],
		$_REQUEST ['fecha_final_c'],
		$_REQUEST ['supervisor'],
		$_REQUEST ['numActoAdmin'],
		$_REQUEST ['tipoActoAdmin'],
		$_REQUEST ['numCDP'],		
		$_REQUEST ['numRP'],
		$_REQUEST ['fecha_RP'],
		$fechaActual,
		$_REQUEST ['modalidad'],
		$_REQUEST ['proveedor'],
		$_REQUEST ['valor'],
		$_REQUEST ['rubro'],
		$_REQUEST ['poliza'],		
		$_REQUEST ['formaPago']
);

$cadenaSql = $this->sql->getCadenaSql ( "registroContrato", $arreglo );
$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso',$arreglo,"registroContrato" );

// Crear Variables necesarias en los métodos


$variable = '';

if ($resultado) {
    //$this->miConfigurador->setVariableConfiguracion("cache",true);
	$this->funcion->Redireccionador ( 'registroContrato', $_REQUEST['usuario'] );
	exit();
} else {
	$this->funcion->Redireccionador ( 'noregistroDocumento', $_REQUEST['usuario'] );
	exit();
}