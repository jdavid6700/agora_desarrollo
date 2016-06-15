<?php

namespace servicios\servicio;

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
	function getCadenaSql($tipo, $variable = '') {
		
		/**
		 * 1.
		 * Revisar las variables para evitar SQL Injection
		 */
		$prefijo = $this->miConfigurador->getVariableConfiguracion ( "prefijo" );
		$idSesion = $this->miConfigurador->getVariableConfiguracion ( "id_sesion" );
		
		switch ($tipo) {
			
			/**
			 * Clausulas específicas
			 */
			case "informacion_proveedor" :
				$cadenaSql = " SELECT numdocumento, primerapellido, segundoapellido, primernombre, segundonombre FROM proveedor.prov_proveedor_info;  ";
				break;
			
			case "informacion_por_proveedor" :
				$cadenaSql = " SELECT * FROM proveedor.prov_proveedor_info";
				$cadenaSql .= " WHERE nit= $variable ";
				break;
		}
		
		return $cadenaSql;
	}
}
?>
