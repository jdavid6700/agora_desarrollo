<?php

namespace hojaDeVida\crearDocente\funcion;

use hojaDeVida\crearDocente\funcion\redireccionar;

include_once ('redireccionar.php');
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}
class SolicitudCotizacion {
	
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
		
		$proveedores = unserialize ( stripslashes ( $_REQUEST ['idProveedor'] ) );
		
		$count = count ( $proveedores );
		
		
		for($i = 0; $i < $count; $i ++) {
			
			$datos = array (
					$_REQUEST ['idObjeto'],
					$proveedores [$i],
					'usuario' => $_REQUEST ['usuario'] 
			);
			// Inserto las solicitudes de cotizacion para cada proveedor
			$cadenaSql = $this->miSql->getCadenaSql ( 'ingresarCotizacion', $datos );
			$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "insertar" );
		}
		
		
		
		// actualizo estado del objeto a contratar a 2(cotizacion)
		// actualizo fecha de solicitud
		// Actualizar estado del OBJETO CONTRATO A ASIGNADA
		
		$numberSolicitud = "SC-" . sprintf("%05d", $_REQUEST['idObjeto']);
		
		$parametros = array (
				'idObjeto' => $_REQUEST ['idObjeto'],
				'numero_solicitud' => $numberSolicitud,
				'estado' => 'COTIZACION', // solicitud de cotizacion
				'fecha' => date ( "Y-m-d" ),
				'usuario' => $_REQUEST ['usuario'] 
		);
		// Actualizo estado del objeto a contratar
		$cadenaSql = $this->miSql->getCadenaSql ( 'actualizarObjeto', $parametros );
		$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "insertar" );
		
		$parametros2 = array (
				'idObjeto' => $_REQUEST ['idObjeto'],
				'tipo' => 2, // objeto
				'fecha' => date ( "Y-m-d H:i:s" ),
				'usuario' => $_REQUEST ['usuario'] 
		);
		// Inserto codigo de validacion
		$cadenaSql = $this->miSql->getCadenaSql ( 'ingresarCodigo', $parametros2 );
		$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
		$datos = array (
				'idObjeto' => $_REQUEST ['idObjeto'],
				'idCodigo' => $resultado [0] ['id_codigo_validacion'] 
		);
		
		
		if ($resultado) {
			redireccion::redireccionar ( 'insertoCotizacion', $datos );
			exit ();
		} else {
			redireccion::redireccionar ( 'noInserto' );
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

$miRegistrador = new SolicitudCotizacion ( $this->lenguaje, $this->sql, $this->funcion );

$resultado = $miRegistrador->procesarFormulario ();

?>
