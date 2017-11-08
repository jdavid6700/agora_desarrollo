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
		
		$esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );
		
		$rutaBloque = $this->miConfigurador->getVariableConfiguracion ( "raizDocumento" ) . "/blocks/asignacionPuntajes/salariales/";
		$rutaBloque .= $esteBloque ['nombre'];
		$host = $this->miConfigurador->getVariableConfiguracion ( "host" ) . $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/asignacionPuntajes/salariales/" . $esteBloque ['nombre'];
		
		
		$respuestaGen = $_POST[$this->campoSeguroCodificar('respuestaGen', $_REQUEST['tiempo'])];
		$justificacion = $_POST[$this->campoSeguroCodificar('justificacion', $_REQUEST['tiempo'])];
		$rechazado = false;
		
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

				$datos = array (
						'id_objeto' => $_REQUEST ['idObjeto'],
						'id_solicitud' => $solicitudXProveedorSel[0]['id'],
						'respuesta' => $respuestaDet,
						'decision' => $estadoPro,
						'usuario' => $_REQUEST ['usuario']
				);

				// Inserto las solicitudes de cotizacion para cada proveedor
				$cadenaSql = $this->miSql->getCadenaSql ( 'ingresarRespuestaCotizacion', $datos );
				$resultadoRegRes = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "insertar" );
				
				$estadoPro = 2;
				
			}else{
				
				$rechazado = true;
				
				$estadoPro = 2;
				
			}
			
			
		}
		
		
		//Buscar usuario para enviar correo
		$cadenaSql = $this->miSql->getCadenaSql ( 'solicitudesXCotizacionSinMensaje', $_REQUEST ['idObjeto'] );
		$resultadoMensajesGenerales = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );

		
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
			$cadenaSql = $this->miSql->getCadenaSql ( 'ingresarRespuestaCotizacion', $datos );
			$resultadoRegRes = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "insertar" );
			
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
			
			$cadenaSql = $this->miSql->getCadenaSql ( 'actualizarObjetoDecNo', $datos );
			$resultadoAct = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "insertar" );
			
		}else{
			
			$cadenaSql = $this->miSql->getCadenaSql ( 'solicitudesXCotizacion', $_REQUEST ['idObjeto'] );
			$solicitudIndividualesCotizacion = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
			
			$cadenaSql = $this->miSql->getCadenaSql ( 'buscarSolicitudesXCotizacionSolicitante', $solicitudIndividualesCotizacion[0][0] );
			$solicitudIndividualesCotizacionSolicitante = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
			
			$cadenaSql = $this->miSql->getCadenaSql ( 'buscarProveedorSeleccionado', $solicitudIndividualesCotizacionSolicitante [0] ['solicitud_cotizacion'] );
			$solicitudIndividualInfoPro = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
			
			
			$datos = array (
					'id_objeto' => $_REQUEST ['idObjeto'],
					'proveedor' => $solicitudIndividualInfoPro[0]['id_proveedor'],
					'justificacion' => $justificacion,
					'estado' => '7',
					'usuario' => $_REQUEST ['usuario']
			);
			
			$cadenaSql = $this->miSql->getCadenaSql ( 'actualizarObjetoDec', $datos );
			$resultadoAct = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "insertar" );
			
		}
		
	
		
		
		
		if ($resultadoAct) {
			redireccion::redireccionar ( 'respondioCotizacionGen', $datos );
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
