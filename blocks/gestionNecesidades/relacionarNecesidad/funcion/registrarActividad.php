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
		$conexion = "agora";
		$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
		
		$esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );
		
		$rutaBloque = $this->miConfigurador->getVariableConfiguracion ( "raizDocumento" ) . "/blocks/asignacionPuntajes/salariales/";
		$rutaBloque .= $esteBloque ['nombre'];
		$host = $this->miConfigurador->getVariableConfiguracion ( "host" ) . $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/asignacionPuntajes/salariales/" . $esteBloque ['nombre'];
		
		// VERIFICAR SI YA REGISTRO LA ACTIVIDAD
		$arreglo = array (
				'idObjeto' => $_REQUEST ['idObjeto'],
				'actividad' => $_REQUEST ['claseCIIU'],
				'idSolicitud' => $_REQUEST['numSolicitud'],
				'vigencia' => $_REQUEST['vigencia'],
				'unidadEjecutora' => $_REQUEST['unidadEjecutora'],
				'tipoNecesidad' => $_REQUEST['tipoNecesidad'],
				'numCotizaciones' => $_REQUEST['numCotizaciones'],
				'usuario' => $_REQUEST ['usuario']
		);
		
		$cadenaSql = $this->miSql->getCadenaSql ( "verificarActividad", $arreglo );
		$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'busqueda' );
		
		if ($resultado) {
			// La actividad ya existe
			redireccion::redireccionar ( 'mensajeExisteActividad', $arreglo );
			exit ();
		} else {
			
			// Guardar ACTIVIDAD
			$cadenaSql = $this->miSql->getCadenaSql ( "registrarActividad", $arreglo );
			$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso' );
			
			if ($resultado) {
				redireccion::redireccionar ( 'registroActividad', $arreglo );
				exit ();
			} else {
				redireccion::redireccionar ( 'noregistro', $arreglo );
				exit ();
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
