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

				
//Cargo array con los datos para insertar en la table INHABILIDAD
$arreglo = array (
		$_REQUEST ['idProveedor'],
		$_REQUEST ['tipo'],
		$_REQUEST ['tiempoInh'],
		$_REQUEST ['fecha'],
		$_REQUEST ['descripcion']
);


//Guardar datos de la inhabilidad
$cadenaSql = $this->sql->getCadenaSql ( "ingresarInhabilidad", $arreglo );
$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso' );


if ($resultado) {
		//Actualizo el estado del proveedor a INHABILITADO
		$valores = array (
				'idProveedor' => $_REQUEST ['idProveedor'],
				'estado' => 2
		);

		$cadenaSql = $this->sql->getCadenaSql ( "actualizarProveedor", $valores );
		$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso');
			
		$this->funcion->Redireccionador ( 'registroExitoso', $_REQUEST['idProveedor'] );
		exit();
} else {
		$this->funcion->Redireccionador ( 'noregistroInhabilidad', $_REQUEST['usuario'] );
		exit();
}
