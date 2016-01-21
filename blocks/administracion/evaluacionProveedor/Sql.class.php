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

			/* CONSULTAR - PROVEEDOR POR NIT */
				case "consultarProveedor" :
					$cadenaSql = "SELECT";
					$cadenaSql .= " id_proveedor,";
					$cadenaSql .= " nit,";
					$cadenaSql .= "	nomempresa,";
					$cadenaSql .= "	puntaje_evaluacion,";
					$cadenaSql .= "	clasificacion_evaluacion";
					$cadenaSql .= " FROM ";
					$cadenaSql .= " prov_proveedor_info";
					$cadenaSql .= " WHERE  NIT=" . $variable;  
					break;
					
			/* CONSULTAR - PROVEEDOR POR ID */
				case "consultarProveedorByID" :
					$cadenaSql = "SELECT";
					$cadenaSql .= " id_proveedor,";
					$cadenaSql .= " nit,";
					$cadenaSql .= "	nomempresa,";
					$cadenaSql .= "	puntaje_evaluacion,";
					$cadenaSql .= "	clasificacion_evaluacion";
					$cadenaSql .= " FROM ";
					$cadenaSql .= " proveedor.prov_proveedor_info";
					$cadenaSql .= " WHERE  id_proveedor=" . $variable;  
					break;
					
			/* CONSULTAR - CONTRATO - POR ID */
				case "contratoByID" :
					$cadenaSql = "SELECT";
					$cadenaSql .= " numero_contrato,";
					$cadenaSql .= "	fecha_inicio,";
					$cadenaSql .= "	fecha_finalizacion,";
					$cadenaSql .= " nomempresa,";
					$cadenaSql .= " C.id_proveedor";
					$cadenaSql .= " FROM ";
					$cadenaSql .= " proveedor.prov_contrato C";
					$cadenaSql .= " JOIN proveedor.prov_proveedor_info P ON P.id_proveedor = C.id_proveedor ";
					$cadenaSql .= " WHERE  id_contrato=" . $variable;  //Activo
					break;

			/* CONSULTAR - CONTRATO - NO. CONTRATO */
				case "contratoByNumero" :
					$cadenaSql = "SELECT";
					$cadenaSql .= " id_contrato,";
					$cadenaSql .= " numero_contrato,";
					$cadenaSql .= "	fecha_inicio,";
					$cadenaSql .= "	fecha_finalizacion,";
					$cadenaSql .= " nomempresa,";
					$cadenaSql .= " C.estado";
					$cadenaSql .= " FROM ";
					$cadenaSql .= " proveedor.prov_contrato C";
					$cadenaSql .= " JOIN proveedor.prov_proveedor_info P ON P.id_proveedor = C.id_proveedor ";
					$cadenaSql .= " WHERE 1=1 ";
					if ($variable [0] != '') {
						$cadenaSql .= " AND  numero_contrato= '" . $variable . "'";
					}
					break;

			/* CONSULTAR - EVALUACION POR ID CONTRATO */
				case "evalaucionByIdContrato" :
					$cadenaSql = "SELECT *";
					$cadenaSql .= " FROM ";
					$cadenaSql .= " proveedor.prov_evaluacion ";
					$cadenaSql .= " WHERE id_contrato= '" . $variable . "'";
					break;
					
			/* CONSULTAR - EVALUACIONES POR ID PROVEEDOR */
				case "evalaucionByIdProveedor" :
					$cadenaSql = "SELECT ";
					$cadenaSql .= " E.fecha_registro,";
					$cadenaSql .= " E.puntaje_total,";
					$cadenaSql .= "	E.clasificacion,";
					$cadenaSql .= "	C.numero_contrato,";
					$cadenaSql .= " S.nombre_supervisor,";
					$cadenaSql .= " O.objetocontratar";
					$cadenaSql .= " FROM ";
					$cadenaSql .= " prov_evaluacion E";
					$cadenaSql .= " JOIN prov_contrato C ON C.id_contrato = E.id_contrato ";
					$cadenaSql .= " JOIN param_supervisor S ON S.id_supervisor = C.id_supervisor ";
					$cadenaSql .= " JOIN prov_objeto_contratar O ON O.id_objeto = C.id_objeto ";
					$cadenaSql .= " WHERE id_proveedor= '" . $variable . "'";
					break;
					
			/* LISTAR - CONTRATO */					
				case "listaCrontato" :			
					$cadenaSql = "SELECT  ";
					$cadenaSql .= " id_contrato, ";
					$cadenaSql .= " numero_contrato, ";
					$cadenaSql .= " fecha_inicio, ";
					$cadenaSql .= " fecha_finalizacion, ";
					$cadenaSql .= " S.nombre_supervisor, ";
					$cadenaSql .= " P.nomempresa, ";
					$cadenaSql .= " numero_acto_admin, ";
					$cadenaSql .= " tipo_acto_admin, ";
					$cadenaSql .= " numero_cdp, ";
					$cadenaSql .= " numero_rp, ";
					$cadenaSql .= " fecha_registro, ";
					$cadenaSql .= " valor, ";
					$cadenaSql .= " modalidad, ";
					$cadenaSql .= " C.estado ";
					$cadenaSql .= " FROM ";
					$cadenaSql .= " proveedor.prov_contrato C";
					$cadenaSql .= " JOIN proveedor.param_supervisor S ON S.id_supervisor = C.id_supervisor ";
                                        $cadenaSql .= " JOIN prov_usuario U ON U.usuario = S.cedula::text ";
					$cadenaSql .= " JOIN proveedor.prov_proveedor_info P ON P.id_proveedor = C.id_proveedor ";
					$cadenaSql .= " WHERE 1=1 AND C.estado=1 AND U.id_usuario=" . $variable ;
					$cadenaSql .= " ORDER BY id_contrato ";
					break;

			/* GUARDAR - NUEVA EVALUACION */
				case 'registroEvaluacion' :
					$cadenaSql = 'INSERT INTO ';
					$cadenaSql .= 'proveedor.prov_evaluacion';
					$cadenaSql .= '( ';
					$cadenaSql .= 'id_contrato,';
					$cadenaSql .= 'fecha_registro,';
					$cadenaSql .= 'tiemo_entrega,';
					$cadenaSql .= 'cantidades,';
					$cadenaSql .= 'conformidad,';
					$cadenaSql .= 'funcionalidad_adicional,';
					$cadenaSql .= 'reclamaciones,';
					$cadenaSql .= 'reclamacion_solucion,';
					$cadenaSql .= 'servicio_venta,';
					$cadenaSql .= 'procedimientos,';
					$cadenaSql .= 'garantia,';
					$cadenaSql .= 'garantia_satisfaccion,';
					$cadenaSql .= 'puntaje_total,';
					$cadenaSql .= 'clasificacion';
					$cadenaSql .= ') ';
					$cadenaSql .= 'VALUES ';
					$cadenaSql .= '( ';
					$cadenaSql .= '\'' . $variable [0] . '\', ';
					$cadenaSql .= '\'' . $variable [1] . '\', ';
					$cadenaSql .= '\'' . $variable [2] . '\', ';
					$cadenaSql .= '\'' . $variable [3] . '\', ';
					$cadenaSql .= '\'' . $variable [4] . '\', ';
					$cadenaSql .= '\'' . $variable [5] . '\', ';
					$cadenaSql .= '\'' . $variable [6] . '\', ';
					$cadenaSql .= '\'' . $variable [7] . '\', ';
					$cadenaSql .= '\'' . $variable [8] . '\', ';
					$cadenaSql .= '\'' . $variable [9] . '\', ';
					$cadenaSql .= '\'' . $variable [10] . '\', ';
					$cadenaSql .= '\'' . $variable [11] . '\', ';
					$cadenaSql .= '\'' . $variable [12] . '\', ';
					$cadenaSql .= '\'' . $variable [13] . '\' ';
					$cadenaSql .= ');';
					break;					

			/* ACTUALIZAR - CONTRATO - ESTADO */			
				case 'actualizarContrato' :
					$cadenaSql = "UPDATE proveedor.prov_contrato SET ";
					$cadenaSql .= "estado='" . $variable ['estado'] . "'";
					$cadenaSql .= " WHERE id_contrato=";
					$cadenaSql .= "'" . $variable ['idContrato'] . "' ";
					break;
					
			/* ACTUALIZAR - PROVEEEDOR PUNTAJE Y CLASIFICACION */			
				case 'actualizarProveedor' :
					$cadenaSql = "UPDATE proveedor.prov_proveedor_info SET ";
					$cadenaSql .= "puntaje_evaluacion='" . $variable ['puntajeNuevo'] . "', ";
					$cadenaSql .= "clasificacion_evaluacion='" . $variable ['clasificacion'] . "'";
					$cadenaSql .= " WHERE id_proveedor=";
					$cadenaSql .= "'" . $variable ['idProveedor'] . "' ";
					break;

		}
		
		return $cadenaSql;
	}
}

?>
