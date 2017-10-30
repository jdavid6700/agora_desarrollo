<?php


namespace hojaDeVida\crearDocente;

use hojaDeVida\crearDocente\funcion\redireccion;

include_once ('redireccionar.php');
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}
class SolicitudModificacion {
	
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
                
                
                
                
		
		$SQLs = [];
                // Inserto las solicitudes de cotizacion para cada proveedor
                $cadenaSqlSol = $this->miSql->getCadenaSql ( 'ingresarSolicitudModificacion', $_REQUEST ['idObjeto'] );
                array_push($SQLs, $cadenaSqlSol);
          

		
		
		//perfil_ordenador

		
		$datosSolicitud = array (
				'idSolicitud' => "currval('agora.solicitud_modificacion_cotizacion_id_seq')",
				'justificacion' => $_REQUEST['solicitudModificacion'],
				'estado_solmod' => 1, // solicitud de cotizacion
				'fecha' => date("Y-m-d H:i:s") ,
				'responsable2' => $_REQUEST ['usuario'],
                                'responsable' => 125647                                
		);
		
		$cadenaSqlRelSol = $this->miSql->getCadenaSql ( 'IngresarRelacionSolModificacion', $datosSolicitud );
		array_push($SQLs, $cadenaSqlRelSol);
		
		$datos = array (
				'objeto' => $_REQUEST ['idObjeto'],
                                'estado' => 1,
				'usuario' =>$_REQUEST ['usuario']
		);      
                
                if(isset($_REQUEST['perfil_ordenador']) && $_REQUEST['perfil_ordenador']=='activo'){
                    
                    $datosSolicitud2 = array (
				'idSolicitud' => "currval('agora.solicitud_modificacion_cotizacion_id_seq')",
				'justificacion' => "<p>".$_REQUEST['solicitudModificacion']."</p>",
				'estado_solmod' => 3, // solicitud de cotizacion
				'fecha' => date("Y-m-d H:i:s") ,
				'responsable2' => $_REQUEST ['usuario'],
                                'responsable' => 125647                                
		);
		
		$cadenaSqlRelSol2 = $this->miSql->getCadenaSql ( 'IngresarRelacionSolModificacion', $datosSolicitud2 );
		array_push($SQLs, $cadenaSqlRelSol2);
                
                $datos = array (
				'objeto' => $_REQUEST ['idObjeto'],
                                'estado' => 3,
				'usuario' =>$_REQUEST ['usuario']
		);      
                
                }
                
                $insertoSolicitud = $esteRecursoDB->transaccion($SQLs);
		
		         
                  
		if ($insertoSolicitud) {
			redireccion::redireccionar ( 'insertoSolicitudModificacionCotizacion', $datos );
			exit ();
		} else {
			redireccion::redireccionar ( 'noInsertoSolicitudModificacionCotizacion' );
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

$miRegistrador = new SolicitudModificacion ( $this->lenguaje, $this->sql, $this->funcion );

$resultado = $miRegistrador->procesarFormulario ();

?>
