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
			
			case "listaContratoXNumContratoFechas" :
				$cadenaSql = " SELECT ";
				$cadenaSql .= " CG.fecha_inicio as inicio,";
				$cadenaSql .= " CG.fecha_fin as fin ";
				$cadenaSql .= " FROM contractual.acta_inicio CG";
				$cadenaSql .= " WHERE CG.vigencia = " . $variable ['vigencia'];
				$cadenaSql .= " AND CG.numero_contrato = '" . $variable ['num_contrato'] . "';";
				break;
			
			case "listaContratoXNumContrato" :
				$cadenaSql = " SELECT ";
				$cadenaSql .= " CG.numero_contrato as numero_contrato,";
				$cadenaSql .= " CG.vigencia as vigencia, ";
				$cadenaSql .= " CG.objeto_contrato as objeto_contrato,";
				$cadenaSql .= " CG.fecha_inicio as fecha_inicio, ";
				$cadenaSql .= " CG.fecha_final as fecha_final,";
				$cadenaSql .= " cast(CG.plazo_ejecucion as text) || ' ' || UE.descripcion as plazo_ejecucion,";
				$cadenaSql .= " FP.descripcion as forma_pago,";
				$cadenaSql .= " OG.\"ORG_NOMBRE\" as nombre_ordenador_gasto,";
				$cadenaSql .= " OG.\"ORG_IDENTIFICACION\" as identificacion_ordenador_gasto,";
				$cadenaSql .= " OG.\"ORG_ORDENADOR_GASTO\" as cargo_ordenador_gasto,";
				$cadenaSql .= " OG.\"ORG_ESTADO\" as estado_ordenador_gasto,";
				$cadenaSql .= " CG.supervisor as identificacion_supervisor,";
				$cadenaSql .= " CG.numero_solicitud_necesidad as numero_solicitud_necesidad,";
				$cadenaSql .= " CG.numero_cdp as numero_cdp,";
				$cadenaSql .= " CG.contratista as identificacion_contratista,";
				$cadenaSql .= " CG.id_sociedad_temporal as identificacion_sociedad_temporal,";
				$cadenaSql .= " CG.valor_contrato as valor_contrato,";
				$cadenaSql .= " CG.justificacion as justificacion,";
				$cadenaSql .= " CG.condiciones as condiciones,";
				$cadenaSql .= " CG.descripcion_forma_pago as descripcion_forma_pago,";
				$cadenaSql .= " CG.fecha_registro as fecha_registro,";
				$cadenaSql .= " TC.descripcion as tipo_control";
				$cadenaSql .= " FROM contractual.contrato_general CG";
				$cadenaSql .= " JOIN contractual.parametros UE ON UE.id_parametro = CG.unidad_ejecucion";
				$cadenaSql .= " JOIN contractual.parametros FP ON FP.id_parametro = CG.forma_pago";
				$cadenaSql .= " JOIN contractual.argo_ordenadores OG ON OG.\"ORG_IDENTIFICADOR_UNICO\" = CG.ordenador_gasto";
				$cadenaSql .= " JOIN contractual.parametros TC ON TC.id_parametro = CG.tipo_control";
				$cadenaSql .= " WHERE CG.vigencia = " . $variable ['vigencia'];
				$cadenaSql .= " AND CG.numero_contrato = '" . $variable ['num_contrato'] . "';";
				break;
				
		    /* REGISTRAR codigo validacion */
			case "ingresarCodigo" :
				$cadenaSql = " INSERT INTO agora.codigo_validacion";
				$cadenaSql .= " (";
				$cadenaSql .= " id_tabla,";
				$cadenaSql .= " tipo_certificacion,";
				$cadenaSql .= " fecha";
				$cadenaSql .= " )";
				$cadenaSql .= " VALUES";
				$cadenaSql .= " (";
				$cadenaSql .= " '" . $variable ['idTabla'] . "',";
				$cadenaSql .= " '" . $variable ['tipo'] . "',";
				$cadenaSql .= " '" . $variable ['fecha'] . "'";
				$cadenaSql .= " ) RETURNING id_codigo_validacion;";
				break;
				
				
			case "listarProveedoresXContrato" :
				$cadenaSql = " SELECT ";
				$cadenaSql .= " string_agg( cast(id_proveedor as text) ,',' ";
				$cadenaSql .= " ORDER BY ";
				$cadenaSql .= " id_proveedor) ";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.contrato_proveedor ";
				$cadenaSql .= " WHERE ";
				$cadenaSql .= " id_contrato = " . $variable . ";";
				break;
				
			
			/* CONSULTAR - PROVEEDOR POR NIT */
			case "consultarProveedor" :
				$cadenaSql = "SELECT";
				$cadenaSql .= " id_proveedor,";
				$cadenaSql .= " num_documento,";
				$cadenaSql .= "	nom_proveedor,";
				$cadenaSql .= "	puntaje_evaluacion,";
				$cadenaSql .= "	clasificacion_evaluacion";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.informacion_proveedor";
				$cadenaSql .= " WHERE  num_documento = " . $variable;
				break;
			
			/* CONSULTAR - PROVEEDOR POR ID */
			case "consultarProveedorByID" :
				$cadenaSql = "SELECT";
				$cadenaSql .= " id_proveedor,";
				$cadenaSql .= " num_documento,";
				$cadenaSql .= "	nom_proveedor,";
				$cadenaSql .= "	tipopersona,";
				$cadenaSql .= "	puntaje_evaluacion,";
				$cadenaSql .= "	clasificacion_evaluacion";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.informacion_proveedor";
				$cadenaSql .= " WHERE  id_proveedor=" . $variable;
				break;
				
			case "consultarProveedoresByID" :
				$cadenaSql = "SELECT";
				$cadenaSql .= " id_proveedor,";
				$cadenaSql .= " num_documento,";
				$cadenaSql .= "	nom_proveedor,";
				$cadenaSql .= "	tipopersona,";
				$cadenaSql .= "	puntaje_evaluacion,";
				$cadenaSql .= "	clasificacion_evaluacion";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.informacion_proveedor";
				$cadenaSql .= " WHERE  id_proveedor IN (" . $variable . ");";
				break;
			
			/* CONSULTAR - CONTRATO - POR ID */
			case "contratoByID" :
				$cadenaSql = "SELECT";
				$cadenaSql .= " C.numero_contrato,";
				$cadenaSql .= " C.vigencia,";
				$cadenaSql .= " C.id_objeto,";
				$cadenaSql .= " C.id_supervisor";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.contrato C";
				$cadenaSql .= " WHERE  id_contrato = " . $variable; // Activo
				break;
				
			case "contratoByProveedor" :
				$cadenaSql = "SELECT";
				$cadenaSql .= " C.id_relacion,";
				$cadenaSql .= " C.id_proveedor";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.contrato_proveedor C";
				$cadenaSql .= " WHERE  id_contrato = " . $variable; // Activo
				break;
			
			
			/* CONSULTAR - CONTRATO - NO. CONTRATO */
			case "contratoByNumero" :
				$cadenaSql = "SELECT";
				$cadenaSql .= " id_contrato,";
				$cadenaSql .= " numero_contrato,";
				$cadenaSql .= " P.numero_solicitud,";
				$cadenaSql .= " P.vigencia,";
				$cadenaSql .= " C.estado";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.contrato C";
				$cadenaSql .= " JOIN agora.objeto_contratar P ON P.id_objeto = C.id_objeto ";
				$cadenaSql .= " WHERE 1=1 ";
				if ($variable [0] != '') {
					$cadenaSql .= " AND  numero_contrato= '" . $variable . "'";
				}
				break;
			
			/* CONSULTAR - EVALUACION POR ID CONTRATO */
			case "evalaucionByIdContrato" :
				$cadenaSql = "SELECT *";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.evaluacion ";
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
				$cadenaSql .= " agora.evaluacion E";
				$cadenaSql .= " JOIN agora.contrato C ON C.id_contrato = E.id_contrato ";
				$cadenaSql .= " JOIN agora.supervisor S ON S.id_supervisor = C.id_supervisor ";
				$cadenaSql .= " JOIN agora.objeto_contratar O ON O.id_objeto = C.id_objeto ";
				$cadenaSql .= " WHERE id_proveedor= '" . $variable . "'";
				break;
			
			/* LISTAR - CONTRATO */
			case "listaContratoAdmin" :
				$cadenaSql = "SELECT  ";
				$cadenaSql .= " C.id_contrato, ";
				$cadenaSql .= " C.numero_contrato, ";
				$cadenaSql .= " OC.numero_solicitud, ";
				$cadenaSql .= " C.vigencia, ";
				$cadenaSql .= " S.nombre_supervisor, ";
				$cadenaSql .= " S.cedula, ";
				$cadenaSql .= " S.correo_supervisor, ";
				$cadenaSql .= " D.dependencia, ";
				$cadenaSql .= " C.fecha_registro, ";
				$cadenaSql .= " C.estado ";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.contrato C";
				$cadenaSql .= " JOIN agora.supervisor S ON S.id_supervisor = C.id_supervisor ";
				$cadenaSql .= " JOIN prov_usuario U ON U.usuario = S.cedula::text ";
				$cadenaSql .= " JOIN agora.objeto_contratar OC ON OC.id_objeto = C.id_objeto ";
				$cadenaSql .= " JOIN agora.dependencia D ON D.id_dependencia = S.id_dependencia ";
				$cadenaSql .= " WHERE 1=1 AND C.estado = 'CREADO'";
				$cadenaSql .= " ORDER BY id_contrato ";
				break;
				
				/* LISTAR - CONTRATO */
			case "listaContratoSupervisor" :
				$cadenaSql = "SELECT  ";
				$cadenaSql .= " C.id_contrato, ";
				$cadenaSql .= " C.numero_contrato, ";
				$cadenaSql .= " OC.numero_solicitud, ";
				$cadenaSql .= " C.vigencia, ";
				$cadenaSql .= " S.nombre_supervisor, ";
				$cadenaSql .= " S.cedula, ";
				$cadenaSql .= " S.correo_supervisor, ";
				$cadenaSql .= " D.dependencia, ";
				$cadenaSql .= " C.fecha_registro, ";
				$cadenaSql .= " C.estado ";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.contrato C";
				$cadenaSql .= " JOIN agora.supervisor S ON S.id_supervisor = C.id_supervisor ";
				$cadenaSql .= " JOIN prov_usuario U ON U.usuario = S.cedula::text ";
				$cadenaSql .= " JOIN agora.objeto_contratar OC ON OC.id_objeto = C.id_objeto ";
				$cadenaSql .= " JOIN agora.dependencia D ON D.id_dependencia = S.id_dependencia ";
				$cadenaSql .= " WHERE 1=1 AND C.estado = 'CREADO' AND U.id_usuario=" . $variable;
				$cadenaSql .= " ORDER BY id_contrato ";
				break;
			
			/* GUARDAR - NUEVA EVALUACION */
			case 'registroEvaluacion' :
				$cadenaSql = 'INSERT INTO ';
				$cadenaSql .= 'agora.evaluacion';
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
				$cadenaSql = "UPDATE agora.contrato SET ";
				$cadenaSql .= "estado='" . $variable ['estado'] . "'";
				$cadenaSql .= " WHERE id_contrato=";
				$cadenaSql .= "'" . $variable ['idContrato'] . "' ";
				break;
			
			/* ACTUALIZAR - PROVEEEDOR PUNTAJE Y CLASIFICACION */
			case 'actualizarProveedor' :
				$cadenaSql = "UPDATE agora.informacion_proveedor SET ";
				$cadenaSql .= "puntaje_evaluacion = '" . $variable ['puntajeNuevo'] . "', ";
				$cadenaSql .= "clasificacion_evaluacion='" . $variable ['clasificacion'] . "'";
				$cadenaSql .= " WHERE id_proveedor=";
				$cadenaSql .= "'" . $variable ['idProveedor'] . "' ";
				break;

		}
		
		return $cadenaSql;
	}
}

?>
