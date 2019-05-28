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

		$conexion = 'framework';
        $frameworkRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
		
		$esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );
		
		$rutaBloque = $this->miConfigurador->getVariableConfiguracion ( "raizDocumento" ) . "/blocks/asignacionPuntajes/salariales/";
		$rutaBloque .= $esteBloque ['nombre'];
		$host = $this->miConfigurador->getVariableConfiguracion ( "host" ) . $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/asignacionPuntajes/salariales/" . $esteBloque ['nombre'];

		$SQLs = [];
		
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
			array_push($SQLs, $cadenaSql);
		}
		
		//Aqui se tenia ESTADO ABIERTO *************** OBJETO x PROVEEDOR Verificar

		
		// actualizo estado del objeto a contratar a 2(cotizacion)
		// actualizo fecha de solicitud
		// Actualizar estado del OBJETO CONTRATO A ASIGNADA
		
		$numberSolicitud = "SC-" . sprintf("%06d", $_REQUEST['idObjeto']);
		
		$parametros = array (
				'idObjeto' => $_REQUEST ['idObjeto'],
				'numero_solicitud' => $numberSolicitud,
				'estado' => '2', // solicitud de cotizacion
				'fecha' => date ( "Y-m-d" ),
				'usuario' => $_REQUEST ['usuario']
		);
		// Actualizo estado del objeto a contratar
		$cadenaSql = $this->miSql->getCadenaSql ( 'actualizarObjeto', $parametros );
		$resultado1 = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "insertar" );
		array_push($SQLs, $cadenaSql);
		
		$parametros2 = array (
				'idObjeto' => $_REQUEST ['idObjeto'],
				'tipo' => 2, // objeto
				'fecha' => date ( "Y-m-d H:i:s" ),
				'usuario' => $_REQUEST ['usuario']
		);
		// Inserto codigo de validacion
		$cadenaSql = $this->miSql->getCadenaSql ( 'ingresarCodigo', $parametros2 );
		$resultado2 = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		array_push($SQLs, $cadenaSql);
		
		$datos = array (
				'idObjeto' => $_REQUEST ['idObjeto'],
				'idCodigo' => $resultado [0] ['id_codigo_validacion']
		);
		
		
		if ($resultado1 && $resultado2) {

				if (!empty($_SERVER['HTTP_CLIENT_IP'])){
	                $ip = $_SERVER['HTTP_CLIENT_IP'];
	            }elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
	                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	            }else{
	                $ip = $_SERVER['REMOTE_ADDR'];
	            }
	            $c = 0;
	            while ($c < count($SQLs)){
	                $SQLsDec[$c] = $this->miConfigurador->fabricaConexiones->crypto->codificar($SQLs[$c]);
	                $c++;
	            }
	            $query = json_encode($SQLsDec);
	            $numberSolicitud = "SC-" . sprintf("%05d", $_REQUEST['idObjeto']);
	                
	            $datosLog = array (
	                    'tipo_log' => 'PROCESAMIENTO',
	                    'modulo' => 'PCOT',
	                    'numero_cotizacion' => $numberSolicitud,
	                    'vigencia' => date("Y"),
	                    'query' => $query,
	                    'data' => null,
	                    'host' => $ip,
	                    'fecha_log' => date("Y-m-d H:i:s"),
	                    'usuario' => $_REQUEST ['usuario']
	            );
	            $cadenaSQL = $this->miSql->getCadenaSql("insertarLogCotizacion", $datosLog);
	            $resultadoLog = $frameworkRecursoDB->ejecutarAcceso($cadenaSQL, 'busqueda');


			redireccion::redireccionar ( 'insertoCotizacion', $datos );
			exit ();
		} else {

	            if (!empty($_SERVER['HTTP_CLIENT_IP'])){
	                $ip = $_SERVER['HTTP_CLIENT_IP'];
	            }elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
	                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	            }else{
	                $ip = $_SERVER['REMOTE_ADDR'];
	            }
	            $c = 0;
	            while ($c < count($SQLs)){
	                $SQLsDec[$c] = $this->miConfigurador->fabricaConexiones->crypto->codificar($SQLs[$c]);
	                $c++;
	            }
	            $query = json_encode($SQLsDec);
	            $numberSolicitud = "SC-" . sprintf("%05d", $_REQUEST['idObjeto']);
	            $error = json_encode(error_get_last());
	            
	            $datosLog = array (
	                    'tipo_log' => 'PROCESAMIENTO',
	                    'modulo' => 'PCOT',
	                    'numero_cotizacion' => $numberSolicitud,
	                    'vigencia' => date("Y"),
	                    'query' => $query,
	                    'error' => $error,
	                    'host' => $ip,
	                    'fecha_log' => date("Y-m-d H:i:s"),
	                    'usuario' => $_REQUEST ['usuario']
	            );
	            $cadenaSQL = $this->miSql->getCadenaSql("insertarLogCotizacionError", $datosLog);
	            $resultadoLog = $frameworkRecursoDB->ejecutarAcceso($cadenaSQL, 'busqueda');
	                
	            $caso = "RCL-" . date("Y") . "-" . $resultadoLog[0][0];

			redireccion::redireccionar ('noInserto', $caso);
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
