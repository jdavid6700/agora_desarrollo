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
		
		$rutaBloque = $this->miConfigurador->getVariableConfiguracion ( "raizDocumento" ) . "/blocks/gestionCotizaciones/";
		$rutaBloque .= $esteBloque ['nombre'];
		$host = $this->miConfigurador->getVariableConfiguracion ( "host" ) . $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/gestionCotizaciones/" . $esteBloque ['nombre'];

		
		//Guardar RUT adjuntado Persona Natural*********************************************************************
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
				// guardamos el archivo a la carpeta files
				$destino = $rutaBloque . "/soportes/" . $nombreDoc;
		
				if (copy ( $archivo ['tmp_name'], $destino )) {
					$status = "Archivo subido: <b>" . $archivo1 . "</b>";
					$_REQUEST ['destino'] = $host . "/soportes/" . $prefijo . "-" . $archivo1;
				} else {
					$status = "<br>Error al subir el archivo1";
				}
			} else {
				$status = "<br>Error al subir archivo2";
			}
		} else {
			echo "<br>NO existe el archivo D:!!!";
		}
		//************************************************************************************************************
		
		
		/*Variables Texto Enriquecido ----------------------------------------------------------*/
		/*--------------------------------------------------------------------------------------*/
		$objetivos = $_POST[$this->campoSeguroCodificar('objetivo', $_REQUEST['tiempo'])];
		$requisitos = $_POST[$this->campoSeguroCodificar('requisitos', $_REQUEST['tiempo'])];
		$observaciones = $_POST[$this->campoSeguroCodificar('observaciones', $_REQUEST['tiempo'])];
		$plan = $_POST[$this->campoSeguroCodificar('planAccion', $_REQUEST['tiempo'])];
		$titulo = $_POST[$this->campoSeguroCodificar('tituloCotizacion', $_REQUEST['tiempo'])];
		
		
		
		$datosTextoEnriquecido = array (
				'objetivos' => str_replace("'", "\"", $objetivos), //Limpieza Comilla Simple para evitar Errores En Ejecución Base de Datos
				'requisitos' => str_replace("'", "\"", $requisitos),
				'observaciones' => str_replace("'", "\"", $observaciones),
				'plan' => str_replace("'", "\"", $plan),
				'titulo' => str_replace("'", "\"", $titulo)
		);
		/*--------------------------------------------------------------------------------------*/
		/*--------------------------------------------------------------------------------------*/
        
		$fechaApertura = $this->cambiafecha_format($_REQUEST['fechaApertura']);
		$fechaCierre = $this->cambiafecha_format($_REQUEST['fechaCierre']);
		
		
		$SQLs = [];
		
		
		
		
		$planAccion = $this->miSql->getCadenaSql ( 'registrarPlanAccion', $datosTextoEnriquecido['plan'] );
		array_push($SQLs, $planAccion);
		
		$valorCDP = str_replace(",", "", $_REQUEST['indices_cdps']);
		
		
        $datosSolicitud = array (
        		'titulo_cotizacion' => $datosTextoEnriquecido['titulo'],
        		'vigencia' => $_REQUEST ['vigencia'],
        		'unidad_ejecutora' => (int)$_REQUEST ['unidad_ejecutora_hidden'],
        		'dependencia' => $_REQUEST ['dependencia'],
        		'dependencia_destino' => $_REQUEST ['dependenciaDestino'],
        		'ordenador' => $_REQUEST ['ordenador_hidden'],
        		'fecha_apertura' => $fechaApertura,
        		'fecha_cierre' => $fechaCierre,
        		'objetivo' => $datosTextoEnriquecido['objetivos'],
        		'requisitos' => $datosTextoEnriquecido['requisitos'],
        		'observaciones' => $datosTextoEnriquecido['observaciones'],
        		'plan' => "currval('administrativa.plan_accion_id_seq')",
        		'tipo_necesidad' => $_REQUEST ['tipoNecesidad'],
        		'forma_seleccion' => $_REQUEST ['formaSeleccion'],
        		'medio_pago' => $_REQUEST ['medioPago'],
        		'tipo_contrato' => $_REQUEST ['tipoContrato'],
        		'numero_disponibilidad' => $valorCDP,
        		'usuario' => $_REQUEST ['usuario'],
        		'anexo' => $_REQUEST ['destino']
        );

        
        $datosSolicitudCotizacion = $this->miSql->getCadenaSql ( 'registrar', $datosSolicitud );
        array_push($SQLs, $datosSolicitudCotizacion);
        
        
        
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
        			'objeto_cotizacion_id' => "currval('agora.prov_objeto_contratar_id_objeto_seq')",
        			'forma_pago_id' => "currval('agora.forma_pago_id_seq')"
        	);
        	
        	$datoRegFPxCot = $this->miSql->getCadenaSql ( 'registrarFormaPagoXCotizacion', $datoFPxCot );
        	array_push($SQLs, $datoRegFPxCot);
        	
        	
        	$limit = $limit + 3;
        }
        
        
        //******************************** ITEMS ****************************************************************
        
        $countFPParam = $_REQUEST ['countItems'];
        
     
        $subFP = explode("&", $_REQUEST ['idsItems']);
        
     
       
        $cantidadParametros = ($countFPParam) * 7;
        
        $limitP = 0;
        while($limitP < $cantidadParametros){
        	 
        	$subCount[$limitP] = explode(" ", $subFP[$limitP]);
        	 
        	$limitP++;
        	 
        }
       
     
        $limit = 0;
        while($limit < $cantidadParametros){
            
                $registroCant =str_replace(".", "", $subCount[$limit + 6][0]);
                $registroCant =str_replace(",", ".", $registroCant);
        	 
        
        	if($subCount[$limit+3][0] == 1){
        		
        		$datoFP = array (
        				'objeto_cotizacion' => "currval('agora.prov_objeto_contratar_id_objeto_seq')",
        				'nombre' => $subFP[$limit+1],
        				'descripcion' => $subFP[$limit+2],
        				'tipo' => $subCount[$limit+3][0],
        				'unidad' => $subCount[$limit+4][0],
        				'tiempo' => $subCount[$limit+5][0],
        				'cantidad' => $registroCant
        		);
        		
        	}
        	
        	
        	if($subCount[$limit+3][0] == 2){
        		
        		$subTime = explode(" ", $subFP[$limit+5]);
        		$totalDays = (intval($subTime[0]) * 360) + (intval($subTime[3]) * 30) + intval($subTime[6]);
        		
        		$datoFP = array (
        				'objeto_cotizacion' => "currval('agora.prov_objeto_contratar_id_objeto_seq')",
        				'nombre' => $subFP[$limit+1],
        				'descripcion' => $subFP[$limit+2],
        				'tipo' => $subCount[$limit+3][0],
        				'unidad' => $subCount[$limit+4][0],
        				'tiempo' => $totalDays,
        				'cantidad' => $registroCant
        		);

        	}
        	
        	$datoRegFP = $this->miSql->getCadenaSql ( 'registrarItemProducto', $datoFP );
             
            
        	array_push($SQLs, $datoRegFP);
        	 
        	 
        	$limit = $limit + 7;
        }
       
      
        
        //*************************************************************************************************************
        
        $registroCotizacion = $esteRecursoDB->transaccion($SQLs);
        
          
        

		if ($registroCotizacion) {
			
			
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
			
			
				//Conusltar el ultimo ID del objeto
				$cadenaSql = $this->miSql->getCadenaSql ( 'lastIdObjeto' );
				$lastId = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
				
				$datos = array (
						'idObjeto' => $lastId[0][0],
						'titulo_cotizacion' => $datosTextoEnriquecido['titulo'],
						'vigencia' => $_REQUEST ['vigencia'],
						'unidad_ejecutora' => (int)$_REQUEST ['unidad_ejecutora_hidden'],
						'dependencia' => $_REQUEST ['dependencia'],
						'dependencia_destino' => $_REQUEST ['dependenciaDestino'],
						'ordenador' => $_REQUEST ['ordenador_hidden'],
						'fecha_apertura' => $fechaApertura,
						'fecha_cierre' => $fechaCierre,
						'objetivo' => $datosTextoEnriquecido['objetivos'],
						'requisitos' => $datosTextoEnriquecido['requisitos'],
						'observaciones' => $datosTextoEnriquecido['observaciones'],
						'plan' => $datosTextoEnriquecido['plan'],
						'tipo_necesidad' => $_REQUEST ['tipoNecesidad'],
						'tipo_contrato' => $_REQUEST ['tipoContrato'],
						'medio_pago' => $_REQUEST ['medioPago'],
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
