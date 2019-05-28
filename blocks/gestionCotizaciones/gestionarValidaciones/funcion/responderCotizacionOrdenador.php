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
	function cambiafecha_format($fecha) {
		ereg("([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})", $fecha, $mifecha);
		$fechana = $mifecha[3] . "-" . $mifecha[2] . "-" . $mifecha[1];
		return $fechana;
	}
	
	function campoSeguroCodificar($cadena, $tiempoRequest){
		/*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
		/*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
		/*++++++++++++++++++++++++++++++++++++++++++++ OBTENER CAMPO POST (Codificar) +++++++++++++++++++++++++++++++++++++++++++*/
	
		$tiempo = (int) substr($tiempoRequest, 0, -2);
		$tiempo = $tiempo * pow(10, 2);
	
		$campoSeguro = $this->miConfigurador->fabricaConexiones->crypto->codificar($cadena.$tiempo);
	
		/*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
		/*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
		return $campoSeguro;
	}
	
	function campoSeguroDecodificar($campoSeguroRequest, $tiempoRequest){
		/*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
		/*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
		/*++++++++++++++++++++++++++++++++++++++++++++ OBTENER CAMPO POST (Decodificar) +++++++++++++++++++++++++++++++++++++++++*/
	
		$tiempo = (int) substr($tiempoRequest, 0, -2);
		$tiempo = $tiempo * pow(10, 2);
	
		$campoSeguro = $this->miConfigurador->fabricaConexiones->crypto->decodificar($campoSeguroRequest);
	
		$campo = str_replace($tiempo, "", $campoSeguro);
	
		/*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
		/*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
		return $campo;
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

		
		$observaciones = $_POST[$this->campoSeguroCodificar('observaciones', $_REQUEST['tiempo'])];
		$SQLs = [];
		
		if(isset($_REQUEST['decision']) && $_REQUEST['decision'] == "1"){// Cotización APROBADA por el Ordenador
				
			$datos = array (
					'objeto' => $_REQUEST ['idObjeto'],
					'observaciones' => $observaciones,
					'estado' => '3'
			);
			
			$validacionOrdenador = $this->miSql->getCadenaSql ( 'validacionOrdenador', $datos );
			array_push($SQLs, $validacionOrdenador);
			
			$validacionOrdenador = $this->miSql->getCadenaSql ( 'validacionObjetoCotizacion', $datos );
			array_push($SQLs, $validacionOrdenador);
				
		}else{
			
			$datos = array (
					'objeto' => $_REQUEST ['idObjeto'],
					'observaciones' => $observaciones,
					'estado' => '8'
			);
			
			$validacionOrdenador = $this->miSql->getCadenaSql ( 'validacionOrdenador', $datos );
			array_push($SQLs, $validacionOrdenador);
			
			$validacionOrdenador = $this->miSql->getCadenaSql ( 'validacionObjetoCotizacion', $datos );
			array_push($SQLs, $validacionOrdenador);
		
		}
		
		$actualizoCotizacion = $esteRecursoDB->transaccion($SQLs);
		
		
		if ($actualizoCotizacion) {

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
	                    'tipo_log' => 'DECISION',
	                    'modulo' => 'VDCOT',
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

			redireccion::redireccionar ( 'respondioCotizacionOrdenador', $datos );
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
	                    'tipo_log' => 'DECISION',
	                    'modulo' => 'VDCOT',
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

			redireccion::redireccionar ( 'noInserto', $caso );
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
