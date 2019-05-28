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
		

		$tipoAcceso = $_REQUEST['tipo_acceso'];
		$SQLs = [];
		
		$respuestaGen = $_POST[$this->campoSeguroCodificar('respuestaGen', $_REQUEST['tiempo'])];
		$justificacion = $_POST[$this->campoSeguroCodificar('justificacion', $_REQUEST['tiempo'])];
		$rechazado = false;

		//Limpieza Data POST ******************************************************
		$respuestaGen = str_replace("'", "\"", $respuestaGen);
		$justificacion = str_replace("'", "\"", $justificacion);
		//*************************************************************************

		$id_solicitud_sel = false;
		$val_id_solicitud_sel = null;
		
		if(isset($_REQUEST['estadoSolicitudSel']) && $_REQUEST['estadoSolicitudSel']){// TRUE Proveedor Previamente SELECCIONADO
			
			$estadoPro = 2;
			
		}else{
		
			$estadoPro = 1;
			
			if(isset($_REQUEST['decisionPro']) && $_REQUEST['decisionPro'] != null){
				
				$datos = array (
						'objeto' => $_REQUEST ['idObjeto'],
						'proveedor' => $_REQUEST ['decisionPro']
				);
				
				$cadenaSql = $this->miSql->getCadenaSql ( 'solicitudXProveedorSel', $datos);
				$solicitudXProveedorSel = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
				
				$respuestaDet = $_POST[$this->campoSeguroCodificar('respuestaDet', $_REQUEST['tiempo'])];
				$respuestaDet = str_replace("'", "\"", $respuestaDet);

				$datos = array (
						'id_objeto' => $_REQUEST ['idObjeto'],
						'id_solicitud' => $solicitudXProveedorSel[0]['id'],
						'respuesta' => $respuestaDet,
						'decision' => $estadoPro,
						'usuario' => $_REQUEST ['usuario']
				);

				// Insert la respuesta para solicitud de cotizacion para proveedor seleccionado
				$cadenaSqlReg = $this->miSql->getCadenaSql ( 'ingresarRespuestaCotizacion', $datos );
				array_push($SQLs, $cadenaSqlReg);

				$id_solicitud_sel = true;
				$val_id_solicitud_sel = $solicitudXProveedorSel[0]['id'];
				
				$estadoPro = 2;
				
			}else{
				
				$rechazado = true;
				
				$estadoPro = 2;
				
			}
			
			
		}
		
		if($id_solicitud_sel){

			$datosCastSol = array (
					'id_objeto' => $_REQUEST ['idObjeto'],
					'id_solicitud' => $val_id_solicitud_sel,
			);

			$cadenaSql = $this->miSql->getCadenaSql ( 'solicitudesXCotizacionSinMensajeEx', $datosCastSol );
			$resultadoMensajesGenerales = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		}else{
			$cadenaSql = $this->miSql->getCadenaSql ( 'solicitudesXCotizacionSinMensaje', $_REQUEST ['idObjeto'] );
			$resultadoMensajesGenerales = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		}
		

		
		$limit = $resultadoMensajesGenerales[0]['contador'];
		$i = 0;
		
		$resultadoMensajesGenerales[0]['string_agg'] = str_replace ( "'" , "", $resultadoMensajesGenerales[0]['string_agg']);
		$idSplit = explode( ',', $resultadoMensajesGenerales[0]['string_agg'] )  ;
		
		
		//Registro de Respuestas GENERALES Proveedores NO Seleccionados
		while($i < $limit){
			
			
			$datos = array (
					'id_respuesta_directa' => null,
					'id_solicitud' => $idSplit[$i],
					'respuesta' => $respuestaGen,
					'decision' => $estadoPro,
					'usuario' => $_REQUEST ['usuario']
			);

			// Inserto las solicitudes de cotizacion para cada proveedor
			$cadenaSqlRegRes = $this->miSql->getCadenaSql ( 'ingresarRespuestaCotizacion', $datos );
			array_push($SQLs, $cadenaSqlRegRes);
			
			$i++;
			
		}
		
		if($rechazado){
			
			$datos = array (
					'id_objeto' => $_REQUEST ['idObjeto'],
					'proveedor' => null,
					'justificacion' => $justificacion,
					'estado' => '8',
					'usuario' => $_REQUEST ['usuario']
			);
			
			$cadenaSqlAct = $this->miSql->getCadenaSql ( 'actualizarObjetoDecNo', $datos );
			array_push($SQLs, $cadenaSqlAct);
			
		}else{
			

			if($id_solicitud_sel){
				$cadenaSql = $this->miSql->getCadenaSql ( 'buscarProveedorSeleccionado', $val_id_solicitud_sel );
				$solicitudIndividualInfoPro = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
			}else{
				$cadenaSql = $this->miSql->getCadenaSql ( 'solicitudesXCotizacion', $_REQUEST ['idObjeto'] );
				$solicitudIndividualesCotizacion = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
				
				$cadenaSql = $this->miSql->getCadenaSql ( 'buscarSolicitudesXCotizacionSolicitante', $solicitudIndividualesCotizacion[0][0] );
				$solicitudIndividualesCotizacionSolicitante = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
				
				$cadenaSql = $this->miSql->getCadenaSql ( 'buscarProveedorSeleccionado', $solicitudIndividualesCotizacionSolicitante [0] ['solicitud_cotizacion'] );
				$solicitudIndividualInfoPro = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
			}

			
			if($solicitudIndividualInfoPro){

				$datos = array (
						'id_objeto' => $_REQUEST ['idObjeto'],
						'proveedor' => $solicitudIndividualInfoPro[0]['id_proveedor'],
						'justificacion' => $justificacion,
						'estado' => '7',
						'usuario' => $_REQUEST ['usuario']
				);
				
				$cadenaSqlAct = $this->miSql->getCadenaSql ( 'actualizarObjetoDec', $datos );
				array_push($SQLs, $cadenaSqlAct);

			}else{

				$datos = array (
						'id_objeto' => $_REQUEST ['idObjeto'],
						'proveedor' => null,
						'justificacion' => $justificacion,
						'estado' => '8',
						'usuario' => $_REQUEST ['usuario']
				);
				
				$cadenaSqlAct = $this->miSql->getCadenaSql ( 'actualizarObjetoDecNo', $datos );
				array_push($SQLs, $cadenaSqlAct);

			}
			
			
			
			
		}
		
		//************************************** VALIDACION AUTOMATICA PARA ORDENADOR DE GASTO *******************************
		if($tipoAcceso == '1'){
			
			if(!$rechazado){// Cotización APROBADA por el Ordenador
			
				$datos = array (
						'id_objeto' => $_REQUEST ['idObjeto'],
						'observaciones' => $justificacion,
						'estado' => '3',
						'usuario' => $_REQUEST ['usuario']
				);
			
				$validacionOrdenador = $this->miSql->getCadenaSql ( 'validacionOrdenador', $datos );
				array_push($SQLs, $validacionOrdenador);
			
				$validacionOrdenador = $this->miSql->getCadenaSql ( 'validacionObjetoCotizacion', $datos );
				array_push($SQLs, $validacionOrdenador);
			
			}else{
			
				$datos = array (
						'id_objeto' => $_REQUEST ['idObjeto'],
						'observaciones' => $justificacion,
						'estado' => '8',
						'usuario' => $_REQUEST ['usuario']
				);
			
				$validacionOrdenador = $this->miSql->getCadenaSql ( 'validacionOrdenador', $datos );
				array_push($SQLs, $validacionOrdenador);
			
				$validacionOrdenador = $this->miSql->getCadenaSql ( 'validacionObjetoCotizacion', $datos );
				array_push($SQLs, $validacionOrdenador);
			
			}
			
		}
		//**********************************************************************************************************************
		
		$updateCotizacion = $esteRecursoDB->transaccion($SQLs);


		if ($updateCotizacion) {


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
	                    'modulo' => 'DCOT',
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


			redireccion::redireccionar ( 'respondioCotizacionGen', $datos );
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
	                    'modulo' => 'DCOT',
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
