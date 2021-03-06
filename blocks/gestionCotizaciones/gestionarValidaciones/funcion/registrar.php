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
		
		
		
		
		
		
		
		
		//Guardar Soporte adjuntado *************************************************************************************
		$_REQUEST ['destino'] = '';
		// Guardar el archivo
		if ($_FILES) {
			$i = 0;
			foreach ( $_FILES as $key => $values ) {
				$archivoCarga[$i] = $_FILES [$key];
				$i++;
			}
			$archivo = $archivoCarga[0];
			// obtenemos los datos del archivo
			$tamano = $archivo ['size'];
			$tipo = $archivo ['type'];
			$archivo1 = $archivo ['name'];
			$prefijo = substr ( md5 ( uniqid ( rand () ) ), 0, 6 );
			$nombreDoc = $prefijo . "-" . $archivo1;
		
			if ($archivo1 != "") {
				$CambioARCHIVO = true;
				// guardamos el archivo a la carpeta files
		
				$rutaBloqueChange = $this->miConfigurador->getVariableConfiguracion ( "raizDocumento" ) . "/blocks/gestionCotizaciones/relacionarCotizacion";
		
				$destino = $rutaBloqueChange . "/soportes/" . $nombreDoc;
		
				if (copy ( $archivo ['tmp_name'], $destino )) {
						
					$hostChange = $this->miConfigurador->getVariableConfiguracion ( "host" ) . $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/gestionCotizaciones/relacionarCotizacion";
						
					$status = "Archivo subido: <b>" . $archivo1 . "</b>";
					$_REQUEST ['destino'] = $hostChange . "/soportes/" . $prefijo . "-" . $archivo1;
						
					//Actualizar Soporte
					$cadenaSql = $this->miSql->getCadenaSql ( "actualizarSoporte", $_REQUEST );
					//$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso' );
						
				} else {
					$status = "<br>Error al subir el archivo1";
				}
			} else {
				$CambioARCHIVO = false;
				$status = "<br>Error al subir archivo2";
			}
		} else {
			echo "<br>NO existe el archivo D:!!!";
		}
		//***************************************************************************************************************************
		
			
		
		$datosSolicitudNecesidad = array (
				'idObjeto' => $_REQUEST['idObjeto']
		);
		
		$cadena_sql = $this->miSql->getCadenaSql ( "informacionSolicitudAgoraNoCast", $datosSolicitudNecesidad);
		$resultadoCotizacion = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
		
		
		$datosFkCotizacion = array (
				'jefe_dependencia' => $resultadoCotizacion [0]['jefe_dependencia'],
				'ordenador_gasto' => $resultadoCotizacion [0]['ordenador_gasto'],
				'plan_accion' => $resultadoCotizacion [0]['plan_accion']
		);
		

		
		if(isset($_REQUEST['tituloCotizacion'])){$_REQUEST['tituloCotizacion']=mb_strtoupper($_REQUEST['tituloCotizacion'],'utf-8');}
		
		
		/*Variables Texto Enriquecido ----------------------------------------------------------*/
		/*--------------------------------------------------------------------------------------*/
		$objetivos = $_POST[$this->campoSeguroCodificar('objetivo', $_REQUEST['tiempo'])];
		$requisitos = $_POST[$this->campoSeguroCodificar('requisitos', $_REQUEST['tiempo'])];
		$observaciones = $_POST[$this->campoSeguroCodificar('observaciones', $_REQUEST['tiempo'])];
		$plan = $_POST[$this->campoSeguroCodificar('planAccion', $_REQUEST['tiempo'])];
		
		
		$datosTextoEnriquecido = array (
				'objetivos' => $objetivos,
				'requisitos' => $requisitos,
				'observaciones' => $observaciones,
				'plan' => $plan
		);
		/*--------------------------------------------------------------------------------------*/
		/*--------------------------------------------------------------------------------------*/
		
		
		$fechaApertura = $this->cambiafecha_format($_REQUEST['fechaApertura']);
		$fechaCierre = $this->cambiafecha_format($_REQUEST['fechaCierre']);
		
		
		$datosPlan = array (
				'plan' => $plan,
				'fk_id_plan' => $datosFkCotizacion['plan_accion']
		);
		
		
		$SQLs = [];
		
		
		
		$planAccion = $this->miSql->getCadenaSql ( 'actualizarPlanAccion', $datosPlan );
		array_push($SQLs, $planAccion);
		
		
		$datosSolicitud = array (
				'idObjeto' => $_REQUEST['idObjeto'],
				'titulo_cotizacion' => $_REQUEST ['tituloCotizacion'],
				'vigencia' => $_REQUEST ['vigencia'],
				'unidad_ejecutora' => (int)$_REQUEST ['unidadEjecutora'],
				'dependencia' => $_REQUEST ['dependencia'],
				'ordenador' => $_REQUEST ['ordenador'],
				'fecha_apertura' => $fechaApertura,
				'fecha_cierre' => $fechaCierre,
				'objetivo' => $datosTextoEnriquecido['objetivos'],
				'requisitos' => $datosTextoEnriquecido['requisitos'],
				'observaciones' => $datosTextoEnriquecido['observaciones'],
				'plan' => $datosFkCotizacion['plan_accion'],
				'tipo_necesidad' => $_REQUEST ['tipoNecesidad'],
				'medio_pago' => $_REQUEST ['medioPago'],
				'usuario' => $_REQUEST ['usuario']
		);
		
		
		$datosSolicitudCotizacion = $this->miSql->getCadenaSql ( 'actualizar', $datosSolicitud );
		array_push($SQLs, $datosSolicitudCotizacion);
		
		

		if(isset($_REQUEST['changeFormaPago']) && $_REQUEST['changeFormaPago'] == 'true'){
			
			
			$datosCleanRelaciones = $this->miSql->getCadenaSql ( 'configuracionRelaciones', $_REQUEST['idObjeto'] );
			array_push($SQLs, $datosCleanRelaciones);

			
			//******************************** FORMA PAGO ****************************************************************
			
			$subCount = explode(" ", $_REQUEST ['countParam']);
			$countFPParam = $subCount[1];
			
			$subFP = explode("&", $_REQUEST ['idsFormaPago']);
			$cantidadParametros = ($countFPParam) * 3;
			
			$limitP = 0;
			while($limitP < $cantidadParametros){
				 
				$subCount[$limitP] = explode(" ", $subFP[$limitP]);
				 
				$limitP++;
				 
			}
			
			$limit = 0;
			while($limit < $cantidadParametros){
				 
				 
				$datoFP = array (
						'tipo_condicion' => $subCount[$limit][0],
						'valor_condicion' => $subCount[$limit+1][0],
						'porcentaje_pago' => $subCount[$limit+2][0]
				);
				 
				$datoRegFP = $this->miSql->getCadenaSql ( 'registrarFormaPago', $datoFP );
				array_push($SQLs, $datoRegFP);
				 
				$datoFPxCot = array (
						'objeto_cotizacion_id' => $_REQUEST['idObjeto'],
						'forma_pago_id' => "currval('agora.forma_pago_id_seq')"
				);
				 
				$datoRegFPxCot = $this->miSql->getCadenaSql ( 'registrarFormaPagoXCotizacion', $datoFPxCot );
				array_push($SQLs, $datoRegFPxCot);
				 
				 
				$limit = $limit + 3;
			}
			
			
			//*************************************************************************************************************
			
		}

		$actualizoCotizacion = $esteRecursoDB->transaccion($SQLs);
		
		if ($actualizoCotizacion) {
			

				
					if(isset($_REQUEST['tipoNecesidad'])){//CAST tipo de NECESIDAD
						switch($_REQUEST['tipoNecesidad']){
							case 1 :
								$_REQUEST['tipoNecesidad']='BIEN';
								break;
							case 2 :
								$_REQUEST['tipoNecesidad']='SERVICIO';
								break;
							case 3 :
								$_REQUEST ['tipoNecesidad'] = 'BIEN Y SERVICIO';
								break;
						}
					}
			
				
				$datos = array (
						'idObjeto' => $_REQUEST['idObjeto'],
						'titulo_cotizacion' => $_REQUEST ['tituloCotizacion'],
						'vigencia' => $_REQUEST ['vigencia'],
						'unidad_ejecutora' => (int)$_REQUEST ['unidadEjecutora'],
						'dependencia' => $_REQUEST ['dependencia'],
						'ordenador' => $_REQUEST ['ordenador'],
						'fecha_apertura' => $fechaApertura,
						'fecha_cierre' => $fechaCierre,
						'objetivo' => $datosTextoEnriquecido['objetivos'],
						'requisitos' => $datosTextoEnriquecido['requisitos'],
						'observaciones' => $datosTextoEnriquecido['observaciones'],
						'plan' => $datosTextoEnriquecido['plan'],
						'tipo_necesidad' => $_REQUEST ['tipoNecesidad'],
						'medio_pago' => $_REQUEST ['medioPago'],
						'estadoSolicitud' => $_REQUEST ['estadoSolicitudRelacionada'],
						'usuario' => $_REQUEST ['usuario']
				);
				
			
			redireccion::redireccionar ( 'inserto',  $datos);
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

$miRegistrador = new Registrar ( $this->lenguaje, $this->sql, $this->funcion );

$resultado = $miRegistrador->procesarFormulario ();

?>
