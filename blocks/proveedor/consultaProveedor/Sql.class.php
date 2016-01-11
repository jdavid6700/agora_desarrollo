<?php

namespace inventarios\gestionContrato;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}

include_once ("core/manager/Configurador.class.php");
include_once ("core/connection/Sql.class.php");

// Para evitar redefiniciones de clases el nombre de la clase del archivo sqle debe corresponder al nombre del bloque
// en camel case precedida por la palabra sql
class Sql extends \Sql {
	var $miConfigurador;
	function __construct() {
		$this->miConfigurador = \Configurador::singleton ();
	}
	function getCadenaSql($tipo, $variable = "") {

		/**
		 * 1.
		 * Revisar las variables para evitar SQL Injection
		 */
		$prefijo = $this->miConfigurador->getVariableConfiguracion ( "prefijo" );
		$idSesion = $this->miConfigurador->getVariableConfiguracion ( "id_sesion" );
		
		switch ($tipo) {

			/* CONSULTAR - PROVEEDOR */					
			case "consultarProveedor" :			
				$cadenaSql = "SELECT  ";
				$cadenaSql .= " id_proveedor, ";
				$cadenaSql .= " nit, ";
				$cadenaSql .= " nomempresa, ";
				$cadenaSql .= " correo, ";
				$cadenaSql .= " web, ";
				$cadenaSql .= " telefono, ";
				$cadenaSql .= " ext1, ";
				$cadenaSql .= " movil, ";
				$cadenaSql .= " apellido1, ";
				$cadenaSql .= " apellido2, ";
				$cadenaSql .= " nombre1, ";
				$cadenaSql .= " nombre2,  ";
				$cadenaSql .= " puntaje_evaluacion, ";
				$cadenaSql .= " clasificacion_evaluacion  ";				
				$cadenaSql .= " FROM ";
				$cadenaSql .= " prov_proveedor_info";
				$cadenaSql .= " WHERE 1=1 ";
				if ($variable [0] != '') {
					$cadenaSql .= " AND  nit= '" . $variable [0] . "'";
				}
				
			/*	if ($variable [1] != '') {
					$cadenaSql .= " AND fecha_contrato BETWEEN CAST ( '" . $variable [1] . "' AS DATE) ";
					$cadenaSql .= " AND  CAST ( '" . $variable [2] . "' AS DATE)  ";
				}
				
				if ($variable [3] != '') {
					$cadenaSql .= " AND C.fecha_registro BETWEEN '" . $variable [3] . "' AND '" . $variable [4] . "'" ;
				}*/
				break;
				
		}
		
		return $cadenaSql;
	}
}

?>
