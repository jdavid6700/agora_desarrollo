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
			
			/* ACTUALIZAR - PROVEEEDOR ESTADO INHABILITADO */			
				case 'actualizarProveedor' :
					$cadenaSql = "UPDATE proveedor.prov_proveedor_info SET ";
					$cadenaSql .= "estado='" . $variable ['estado'] . "'";
					$cadenaSql .= " WHERE id_proveedor=";
					$cadenaSql .= "'" . $variable ['idProveedor'] . "' ";
					break;
					
			/* REGISTRAR INHABILIDAD */
				case "ingresarInhabilidad" :
					$cadenaSql=" INSERT INTO proveedor.prov_inhabilidad";
					$cadenaSql.=" (";					
					$cadenaSql.=" id_proveedor,";
					$cadenaSql.=" tipo_inhabilidad,";
					$cadenaSql.=" tiempo_inhabilidad,";
					$cadenaSql.=" fecha_inhabilidad,";
					$cadenaSql.=" descripcion";
					$cadenaSql.=" )";
					$cadenaSql.=" VALUES";
					$cadenaSql.=" (";
					$cadenaSql.=" '" . $variable[0]. "',";
					$cadenaSql.=" '" . $variable[1]. "',";
					$cadenaSql.=" '" . $variable[2]. "',";
					$cadenaSql.=" '" . $variable[3]. "',";
					$cadenaSql.=" '" . $variable[4]. "'";
					$cadenaSql.=" );";
					break;
					
			/* CONSULTAR - PROVEEDOR POR ID */					
				case "buscarProveedor" :			
					$cadenaSql = "SELECT  ";
					$cadenaSql .= " id_proveedor, ";
					$cadenaSql .= " nit, ";
					$cadenaSql .= " nomempresa, ";
					$cadenaSql .= " correo, ";
					$cadenaSql .= " web, ";
					$cadenaSql .= " telefono, ";
					$cadenaSql .= " ext1, ";
					$cadenaSql .= " movil, ";
					$cadenaSql .= " primerapellido, ";
					$cadenaSql .= " segundoapellido, ";
					$cadenaSql .= " primernombre, ";
					$cadenaSql .= " segundonombre,  ";
					$cadenaSql .= " puntaje_evaluacion, ";
					$cadenaSql .= " clasificacion_evaluacion  ";				
					$cadenaSql .= " FROM ";
					$cadenaSql .= " proveedor.prov_proveedor_info";
					$cadenaSql .= " WHERE id_proveedor= '" . $variable . "'";
					break;
				
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
					$cadenaSql .= " primerapellido, ";
					$cadenaSql .= " segundoapellido, ";
					$cadenaSql .= " primernombre, ";
					$cadenaSql .= " segundonombre,  ";
					$cadenaSql .= " puntaje_evaluacion, ";
					$cadenaSql .= " clasificacion_evaluacion, ";
					$cadenaSql .= " estado  ";				
					$cadenaSql .= " FROM ";
					$cadenaSql .= " proveedor.prov_proveedor_info";
					$cadenaSql .= " WHERE 1=1 ";
					if ($variable [0] != '') {
						$cadenaSql .= " AND  nit= '" . $variable [0] . "'";
					}
					if ($variable [1] != '') {
						$cadenaSql .=" AND nomempresa LIKE '%" . $variable [1] . "%'";
					}
					break;
				
		}
		
		return $cadenaSql;
	}
}

?>
