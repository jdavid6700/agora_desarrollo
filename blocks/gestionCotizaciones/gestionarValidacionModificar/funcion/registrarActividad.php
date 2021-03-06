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
		
                
                
                
//                
		$cadenaSql = $this->miSql->getCadenaSql ( "eliminarActividadActual",  $_REQUEST ['idObjeto'] );
		$resultadoEliminacion = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'busqueda' );
                
                
                $actividadesArray = explode(",", $_REQUEST['idsActividades']);
		// VERIFICAR SI YA REGISTRO LA ACTIVIDAD
                foreach ($actividadesArray as $dato):
                    	$arreglo = array (
				'idObjeto' => $_REQUEST ['idObjeto'],
				'actividad' => $dato,
				'tipoNecesidad' => $_REQUEST['tipoNecesidad'],
				'usuario' => $_REQUEST ['usuario'],
				'actividades' => $_REQUEST['idsActividades']
		);
            	
                        $cadenaSql = $this->miSql->getCadenaSql ( "registrarActividad", $arreglo );
			$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso' );
                endforeach;
	
       
			
			if ($resultado) {
				redireccion::redireccionar ( 'registroActividad', $arreglo );
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
