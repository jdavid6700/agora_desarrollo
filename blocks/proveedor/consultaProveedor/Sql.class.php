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
			
			
			case "buscarProveedoresFiltro" :
				$cadenaSql = " SELECT DISTINCT num_documento||' - ('||nom_proveedor||')' AS  value, num_documento AS data  ";
				$cadenaSql .= " FROM agora.informacion_proveedor ";
				$cadenaSql .= " WHERE cast(num_documento as text) LIKE '%$variable%' OR nom_proveedor LIKE '%$variable%' LIMIT 10; ";
				break;
			
			/* ACTUALIZAR - PROVEEEDOR ESTADO INHABILITADO */			
				case 'actualizarProveedor' :
					$cadenaSql = "UPDATE agora.informacion_proveedor SET ";
					$cadenaSql .= " estado='" . $variable ['estado'] . "',";
					$cadenaSql .= " fecha_ultima_modificacion='" . $variable ['fecha_modificacion'] . "'";
					$cadenaSql .= " WHERE id_proveedor=";
					$cadenaSql .= "'" . $variable ['idProveedor'] . "' ";
					break;
					
			/* REGISTRAR INHABILIDAD */
				case "ingresarInhabilidad" :
					$cadenaSql=" INSERT INTO agora.inhabilidad";
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
					$cadenaSql .= " num_documento, ";
					$cadenaSql .= " nom_proveedor, ";
					$cadenaSql .= " correo, ";
					$cadenaSql .= " web, ";
					$cadenaSql .= " puntaje_evaluacion, ";
					$cadenaSql .= " clasificacion_evaluacion  ";				
					$cadenaSql .= " FROM ";
					$cadenaSql .= " agora.informacion_proveedor";
					$cadenaSql .= " WHERE id_proveedor= '" . $variable . "'";
					break;
				
			/* CONSULTAR - PROVEEDOR */					
				case "consultarProveedor" :			
					$cadenaSql = "SELECT  ";
					$cadenaSql .= " id_proveedor, ";
					$cadenaSql .= " num_documento, ";
					$cadenaSql .= " nom_proveedor, ";
					$cadenaSql .= " correo, ";
					$cadenaSql .= " web, ";
					$cadenaSql .= " tipopersona,";
					$cadenaSql .= " id_ciudad_contacto,";
					$cadenaSql .= " direccion,";
					$cadenaSql .= " nom_asesor,";
					$cadenaSql .= " tel_asesor,";
					$cadenaSql .= " tipo_cuenta_bancaria,";
					$cadenaSql .= " num_cuenta_bancaria,";
					$cadenaSql .= " id_entidad_bancaria,";
// 					$cadenaSql .= " telefono, ";
// 					$cadenaSql .= " ext1, ";
// 					$cadenaSql .= " movil, ";
// 					$cadenaSql .= " primerapellido, ";
// 					$cadenaSql .= " segundoapellido, ";
// 					$cadenaSql .= " primernombre, ";
// 					$cadenaSql .= " segundonombre,  ";
					$cadenaSql .= " puntaje_evaluacion, ";
					$cadenaSql .= " clasificacion_evaluacion, ";
					$cadenaSql .= " estado  ";				
					$cadenaSql .= " FROM ";
					$cadenaSql .= " agora.informacion_proveedor";
					$cadenaSql .= " WHERE 1=1 ";
					if ($variable [0] != '') {
						$cadenaSql .= " AND  num_documento = '" . $variable [0] . "'";
					}
					if ($variable [1] != '') {
						$cadenaSql .=" AND nom_proveedor LIKE '%" . $variable [1] . "%'";
					}
					break;
					
					
			case "consultarContactoTelProveedor" :
				$cadenaSql = "SELECT  ";
				$cadenaSql .= " P.id_proveedor, ";
				$cadenaSql .= " P.num_documento, ";
				$cadenaSql .= " P.nom_proveedor, ";
				$cadenaSql .= " C.id_telefono,  ";
				$cadenaSql .= " T.numero_tel,  ";
				$cadenaSql .= " T.tipo,  ";
				$cadenaSql .= " T.extension,  ";
				$cadenaSql .= " P.estado  ";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.informacion_proveedor P ";
				$cadenaSql .= " JOIN agora.proveedor_telefono C ON C.id_proveedor = P.id_proveedor ";
				$cadenaSql .= " JOIN agora.telefono T ON C.id_telefono = T.id_telefono ";
				$cadenaSql .= " WHERE 1=1 ";
				$cadenaSql .= " AND  P.num_documento = '" . $variable . "'";
				$cadenaSql .= " AND  T.tipo = '1' LIMIT 1;";
				break;
				
			case "consultarContactoMovilProveedor" :
				$cadenaSql = "SELECT  ";
				$cadenaSql .= " P.id_proveedor, ";
				$cadenaSql .= " P.num_documento, ";
				$cadenaSql .= " P.nom_proveedor, ";
				$cadenaSql .= " C.id_telefono,  ";
				$cadenaSql .= " T.numero_tel,  ";
				$cadenaSql .= " T.tipo,  ";
				$cadenaSql .= " T.extension,  ";
				$cadenaSql .= " P.estado  ";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.informacion_proveedor P ";
				$cadenaSql .= " JOIN agora.proveedor_telefono C ON C.id_proveedor = P.id_proveedor ";
				$cadenaSql .= " JOIN agora.telefono T ON C.id_telefono = T.id_telefono ";
				$cadenaSql .= " WHERE 1=1 ";
				$cadenaSql .= " AND  P.num_documento = '" . $variable . "'";
				$cadenaSql .= " AND  T.tipo = '2' LIMIT 1;";
				break;
				
		}
		
		return $cadenaSql;
	}
}

?>
