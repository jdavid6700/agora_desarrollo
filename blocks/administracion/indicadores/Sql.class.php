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
	

			/* LISTAR - CONTRATO */					
				case "listaPorTipo" :			
					$cadenaSql = "SELECT  ";
					$cadenaSql .= " P.nom_proveedor, ";
					$cadenaSql .= " P.num_documento, ";
					$cadenaSql .= " P.correo, ";
					$cadenaSql .= " P.clasificacion_evaluacion, ";
					$cadenaSql .= " P.puntaje_evaluacion ";
					$cadenaSql .= " FROM ";
					$cadenaSql .= " agora.informacion_proveedor P";
					$cadenaSql .= " WHERE P.clasificacion_evaluacion = '" . $variable . "'";
					$cadenaSql .= " ORDER BY P.puntaje_evaluacion DESC";
					break;	
			/* LISTAR - CONTRATO */					
				case "listaContato" :			
					$cadenaSql = "SELECT  ";
					$cadenaSql .= " C.id_contrato, ";
					$cadenaSql .= " numero_contrato, ";
					$cadenaSql .= " fecha_inicio, ";
					$cadenaSql .= " fecha_finalizacion, ";
					$cadenaSql .= " S.nombre_supervisor, ";
					$cadenaSql .= " P.nom_proveedor, ";
					$cadenaSql .= " P.num_documento, ";
					$cadenaSql .= " C.fecha_registro, ";
					$cadenaSql .= " puntaje_total, ";
					$cadenaSql .= " clasificacion, ";
					$cadenaSql .= " C.estado ";
					$cadenaSql .= " FROM ";
					$cadenaSql .= " agora.contrato C";
					$cadenaSql .= " JOIN agora.supervisor S ON S.id_supervisor = C.id_supervisor ";
					$cadenaSql .= " JOIN agora.informacion_proveedor P ON P.id_proveedor = C.id_proveedor ";
					$cadenaSql .= " LEFT JOIN agora.evaluacion E ON E.id_contrato = C.id_contrato ";
					$cadenaSql .= " WHERE C.vigencia >= " . $variable;
					$cadenaSql .= " ORDER BY C.id_contrato";
					break;


		}
		
		return $cadenaSql;
	}
}

?>
