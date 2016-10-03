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
		
        $datosIdenContrato = array (
        		'numero_solicitud' => $_REQUEST ['numSolicitud'],
        		'vigencia' => $_REQUEST ['vigencia'],
        		'unidad_ejecutora' => $_REQUEST ['unidadEjecutora']
        );
        
        
        $cadenaSql = $this->miSql->getCadenaSql ( 'consultarIdObjeto', $datosIdenContrato );
        $resultadoIdObjeto = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );

        
        $fechaActual = date ( 'Y-m-d' );
        $annnoActual = date ( 'Y' );
        
        
        if(isset($_REQUEST ['idProveedor'])){
        	$datosIdenContrato = array (
        			'id_objeto' => $resultadoIdObjeto[0]['id_objeto'],
        			'numero_contrato' => $_REQUEST ['numContrato'],
        			'unidad_ejecutora' => $_REQUEST ['unidadEjecutora'],
        			'numero_solicitud' => $_REQUEST ['numSolicitud'],
        			'fecha_registro' => $fechaActual,
        			'id_supervisor' => $_REQUEST ['idSupervisor'],
        			'id_proveedor' => $_REQUEST ['idProveedor'],
        			'vigencia' => $_REQUEST ['vigencia'],
        			'estado' => 'CREADO'
        	);
        	
        	
        	$cadenaSql = $this->miSql->getCadenaSql("registroContrato",$datosIdenContrato);
        	$id_contrato = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda", $datosIdenContrato, "registroContrato");
        	
        	$datosIdenProveedorContrato = array (
        			'id_contrato' => $id_contrato[0][0],
        			'id_proveedor' => $_REQUEST ['idProveedor'],
        			'vigencia' => $_REQUEST ['vigencia']
        	);
        	
        	$cadenaSql = $this->miSql->getCadenaSql ( 'registroProveedorContrato', $datosIdenProveedorContrato );
        	$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "insertar" );
        	
        }else{
        	
        	$datosIdenContrato = array (
        			'id_objeto' => $resultadoIdObjeto[0]['id_objeto'],
        			'numero_contrato' => $_REQUEST ['numContrato'],
        			'unidad_ejecutora' => $_REQUEST ['unidadEjecutora'],
        			'numero_solicitud' => $_REQUEST ['numSolicitud'],
        			'fecha_registro' => $fechaActual,
        			'id_supervisor' => $_REQUEST ['idSupervisor'],
        			'vigencia' => $_REQUEST ['vigencia'],
        			'estado' => 'CREADO'
        	);
        	 
        	 
        	$cadenaSql = $this->miSql->getCadenaSql("registroContrato",$datosIdenContrato);
        	$id_contrato = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda", $datosIdenContrato, "registroContrato");
        	
        	$i = 0;
        	while($i < (int)$_REQUEST['numeroProveedores']){
        		
        		
        		$datosIdenProveedorContrato = array (
        				'id_contrato' => $id_contrato[0][0],
        				'id_proveedor' => $_REQUEST ['idProveedor'.$i],
        				'vigencia' => $_REQUEST ['vigencia']
        		);
        		
        		$cadenaSql = $this->miSql->getCadenaSql ( 'registroProveedorContrato', $datosIdenProveedorContrato );
        		$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "insertar" );

        		$i++;
        	}
        	
        	
        }
        
        /*
        if (isset($_REQUEST['estadoSolicitudRelacionada']) && $_REQUEST['estadoSolicitudRelacionada'] == "CREADO" ) {
        	//Actualizar datos del Objeto a contratar
        	$cadenaSql = $this->miSql->getCadenaSql ( 'actualizar', $datosSolicitud );
        	$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "insertar" );
        }else {
        	//Guardar datos del Objeto a contratar
        	$cadenaSql = $this->miSql->getCadenaSql ( 'registrar', $datosSolicitud );
        	$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "insertar" );
        }
        */
        
		
		if ($resultado) {
			
			/*
			if (isset($_REQUEST['estadoSolicitudRelacionada']) && $_REQUEST['estadoSolicitudRelacionada'] == "CREADO" ) {
				
				$datosSolicitudNecesidad = array (
						'idSolicitud' => $_REQUEST['numSolicitud'],
						'vigencia' => $_REQUEST['vigencia']
				);
				
				$cadena_sql = $this->miSql->getCadenaSql ( "informacionSolicitudAgora", $datosSolicitudNecesidad);
				$resultadoNecesidadRelacionada = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
				
				$datos = array (
						'idObjeto' => $resultadoNecesidadRelacionada[0]['id_objeto'],
						'numero_solicitud' => $_REQUEST ['numSolicitud'],
						'vigencia' => $_REQUEST ['vigencia'],
						'cotizaciones' => $_REQUEST ['cotizaciones'],
						'estadoSolicitud' => $_REQUEST['estadoSolicitudRelacionada']
				);
				
			}else{
				//Conusltar el ultimo ID del objeto
				$cadenaSql = $this->miSql->getCadenaSql ( 'lastIdObjeto' );
				$lastId = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
				
				$datos = array (
						'idObjeto' => $lastId[0][0],
						'numero_solicitud' => $_REQUEST ['numSolicitud'],
						'vigencia' => $_REQUEST ['vigencia'],
						'cotizaciones' => $_REQUEST ['cotizaciones'],
						'estadoSolicitud' => $_REQUEST['estadoSolicitudRelacionada']
				);
			}
			*/
			
			
                    
			redireccion::redireccionar ( 'inserto',  $datosIdenContrato);
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
