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


//Guardar datos
$cadenaSql = $this->sql->getCadenaSql ( "registrar", $_REQUEST );
$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso' );

if ($resultado) {
	//Insertar datos en la tabla USUARIO
		/*$parametros = array (
				'idContrato' => $_REQUEST ['idContrato'],
				'estado' => 2  //evaluado
		);
		$cadenaSql = $this->sql->getCadenaSql ( "actualizarContrato", $parametros );
		$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso'); */
	

			
		$this->funcion->Redireccionador ( 'registroSupervisor', $_REQUEST['usuario'] );
		exit();
} else {
		$this->funcion->Redireccionador ( 'noregistro', $_REQUEST['usuario'] );
		exit();
}









