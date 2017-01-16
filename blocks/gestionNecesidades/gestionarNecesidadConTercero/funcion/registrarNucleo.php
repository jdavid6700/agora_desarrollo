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
		
		$arreglo = array (
				'idObjeto' => $_REQUEST ['idObjeto'],
				'objetoNBC' => $_REQUEST ['objetoNBC'],
				'idSolicitud' => $_REQUEST['numSolicitud'],
				'vigencia' => $_REQUEST['vigencia'],
				'unidadEjecutora' => $_REQUEST['unidadEjecutora'],
				'tipoNecesidad' => $_REQUEST['tipoNecesidad'],
				'modificarNBC' => $_REQUEST ['modificarNBC'],
				'numCotizaciones' => $_REQUEST['numCotizaciones'],
				'usuario' => $_REQUEST ['usuario']
		);
		
		if($_REQUEST ['modificarNBC']){
			
			// Modificar NUCLEO BASICO
			$cadenaSql = $this->miSql->getCadenaSql ( "actualizarNucleoBasico", $arreglo );
			$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso' );
			
			
		}else{
				
			// Guardar NUCLEO BASICO
			$cadenaSql = $this->miSql->getCadenaSql ( "registrarNucleoBasico", $arreglo );
			$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso' );

			
		}
		
			
			if ($resultado) {
				redireccion::redireccionar ( 'registroNucleo', $arreglo );
				exit ();
			} else {
				redireccion::redireccionar ( 'noregistro', $arreglo );
				exit ();
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
