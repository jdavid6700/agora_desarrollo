<?php

namespace hojaDeVida\crearDocente\funcion;

use hojaDeVida\crearDocente\funcion\redireccionar;

include_once ('redireccionar.php');
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}
class Registrar {
	
	var $miConfigurador;
	var $lenguaje;
	var $miFormulario;
	var $miFuncion;
	var $miSql;
	var $conexion;
	
	function __construct($lenguaje, $sql, $funcion) {
		
		$this->miConfigurador = \Configurador::singleton ();
		$this->miConfigurador->fabricaConexiones->setRecursoDB ( 'principal' );
		$this->lenguaje = $lenguaje;
		$this->miSql = $sql;
		$this->miFuncion = $funcion;
	}
	function procesarFormulario() {
		$conexion = "estructura";
		$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
		
		$esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );
		
		$rutaBloque = $this->miConfigurador->getVariableConfiguracion ( "raizDocumento" ) . "/blocks/asignacionPuntajes/salariales/";
		$rutaBloque .= $esteBloque ['nombre'];
		$host = $this->miConfigurador->getVariableConfiguracion ( "host" ) . $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/asignacionPuntajes/salariales/" . $esteBloque ['nombre'];
		
		
		
		//VERIFICAR SI YA REGISTRO LA ACTIVIDAD
		$arreglo = array('nit' => $_REQUEST['nit'],
						'actividad' =>  $_REQUEST['claseCIIU']);
		
		$cadenaSql = $this->miSql->getCadenaSql ( "verificarActividad", $arreglo);		
		$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'busqueda' );
	
		if ($resultado) {
			//El proveedor ya existe
			redireccion::redireccionar ( 'mensajeExisteActividad',  $_REQUEST ['nit']);
			exit();    
		}else{
						
				//Guardar ACTIVIDAD
				$cadenaSql = $this->miSql->getCadenaSql ( "registrarActividad", $arreglo );
				$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso' );
		
				if ($resultado) {
								redireccion::redireccionar ( 'registroActividad',  $arreglo);
								exit();
				} else {
								redireccion::redireccionar ( 'noregistro',  $arreglo);
								exit();
				}  
		}		


	}
	
	function resetForm() {
		foreach ( $_REQUEST as $clave => $valor ) {
			
			if ($clave != 'pagina' && $clave != 'development' && $clave != 'jquery' && $clave != 'tiempo') {
				unset ( $_REQUEST [$clave] );
			}
		}
	}
}

$miRegistrador = new Registrar ( $this->lenguaje, $this->sql, $this->funcion );

$resultado = $miRegistrador->procesarFormulario ();

?>
