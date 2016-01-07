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
//INICIO Calculo puntaje total
$puntajeTotal = 0;
$puntajeTotal = $_REQUEST ['tiempoEntrega'] + $_REQUEST ['cantidades'] + $_REQUEST ['conformidad'] + $_REQUEST ['funcionalidadAdicional'];
$_REQUEST ['reclamaciones'] = $_REQUEST ['relcamacionSolucion']==12?0:$_REQUEST ['reclamaciones'];
$puntajeTotal = $puntajeTotal + $_REQUEST ['reclamaciones'] + $_REQUEST ['relcamacionSolucion'] + $_REQUEST ['servicioVenta'] + $_REQUEST ['procedimientos'];
$_REQUEST ['garantia'] = $_REQUEST ['garantiaSatisfaccion']==15?0:$_REQUEST ['garantia'];
$puntajeTotal =  $puntajeTotal + $_REQUEST ['garantia'] + $_REQUEST ['garantiaSatisfaccion'];
//FIN Calculo puntaje total

//Cargo array con los datos para insertar en la table evaluacionProveedor
$arreglo = array (
		$_REQUEST ['idContrato'],
		$fechaActual,
		$_REQUEST ['tiempoEntrega'],
		$_REQUEST ['cantidades'],
		$_REQUEST ['conformidad'],
		$_REQUEST ['funcionalidadAdicional'],
		$_REQUEST ['reclamaciones'],
		$_REQUEST ['relcamacionSolucion'],
		$_REQUEST ['servicioVenta'],		
		$_REQUEST ['procedimientos'],
		$_REQUEST ['garantia'],		
		$_REQUEST ['garantiaSatisfaccion'],
		$puntajeTotal,
		1
);//FALTA EL ID DEL SUPERVISOR

//Guardar datos de la evaluacion
$cadenaSql = $this->sql->getCadenaSql ( "registroEvaluacion", $arreglo );
$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso' );

if ($resultado) {
	//Actualizar estado del CONTRATO A EVALUADO
		$parametros = array (
				'idContrato' => $_REQUEST ['idContrato'],
				'estado' => 2  //evaluado
		);
		
		$cadenaSql = $this->sql->getCadenaSql ( "actualizarContrato", $parametros );
		$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso');
				
		$this->funcion->Redireccionador ( 'registroExitoso', $_REQUEST['usuario'] );
		exit();
} else {
		$this->funcion->Redireccionador ( 'noregistroDocumento', $_REQUEST['usuario'] );
		exit();
}
