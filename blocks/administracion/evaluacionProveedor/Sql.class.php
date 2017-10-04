<?php

namespace administracion\evaluacionProveedor;

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
			
			
			/* GUARDAR - NUEVO CONTRATO */
			case 'registroContrato' :
				$cadenaSql = 'INSERT INTO ';
				$cadenaSql .= 'agora.contrato_evaluacion';
				$cadenaSql .= '( ';
				$cadenaSql .= 'numero_contrato,';
				$cadenaSql .= 'vigencia,';
				$cadenaSql .= 'documento_evaluador,';
				$cadenaSql .= 'fecha_registro,';
				$cadenaSql .= 'numero_necesidad,';
				$cadenaSql .= 'unidad_ejecutora,';
				$cadenaSql .= 'estado';
				$cadenaSql .= ') ';
				$cadenaSql .= 'VALUES ';
				$cadenaSql .= '( ';
				$cadenaSql .= '\'' . $variable ['numero_contrato'] . '\', ';
				$cadenaSql .= '\'' . $variable ['vigencia_contrato'] . '\', ';
				$cadenaSql .= '\'' . $variable ['documento_evaluador'] . '\', ';
				$cadenaSql .= '\'' . $variable ['fecha_registro'] . '\', ';
				$cadenaSql .= '\'' . $variable ['numero_necesidad'] . '\', ';
				$cadenaSql .= '\'' . $variable ['unidad_ejecutora'] . '\', ';
				$cadenaSql .= '\'' . $variable ['estado'] . '\'';
				$cadenaSql .= ')';
				$cadenaSql .= " RETURNING  id_contrato; ";
				break;
			
			case 'registroProveedorContrato' :
				$cadenaSql = 'INSERT INTO ';
				$cadenaSql .= 'agora.contrato_proveedor';
				$cadenaSql .= '( ';
				$cadenaSql .= 'id_contrato,';
				$cadenaSql .= 'id_proveedor,';
				$cadenaSql .= 'vigencia';
				$cadenaSql .= ') ';
				$cadenaSql .= 'VALUES ';
				$cadenaSql .= '( ';
				$cadenaSql .= '\'' . $variable ['id_contrato'] . '\', ';
				$cadenaSql .= '\'' . $variable ['id_proveedor'] . '\', ';
				$cadenaSql .= '\'' . $variable ['vigencia'] . '\' ';
				$cadenaSql .= ');';
				break;
			
			case "consultarContratoGrupal" :
				$cadenaSql = "SELECT ";
				$cadenaSql .= " SC.id_participante, ";
				$cadenaSql .= " SC.id_contratista, ";
				$cadenaSql .= " SC.porcentaje_participacion, ";
				$cadenaSql .= " SC.estado ";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.informacion_sociedad_participante SC";
				$cadenaSql .= " WHERE ";
				$cadenaSql .= " SC.id_proveedor_sociedad = " . $variable . ";";
				break;
			
			
			case "consultarProveedorDatos" :
				$cadenaSql = "SELECT *";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.informacion_proveedor";
				$cadenaSql .= " WHERE ";
				$cadenaSql .= " id_proveedor = " . $variable . ";";
				break;
			
			case "informacionCDP" :
				$cadenaSql=" SELECT ";
				$cadenaSql.=" * ";
				$cadenaSql.=" FROM ";
				$cadenaSql.=" PR.PR_DISPONIBILIDADES";
				$cadenaSql.=" WHERE";
				$cadenaSql.=" VIGENCIA = " . $variable['vigencia'];
				$cadenaSql.=" AND CODIGO_UNIDAD_EJECUTORA = " . $variable ['unidadEjecutora'];
				$cadenaSql.=" AND NUMERO_DISPONIBILIDAD = " . $variable ['numeroDisponibilidad'];
				break;
			
			
			case "informacionRP" :
				$cadenaSql = " SELECT ";
				$cadenaSql .= " * ";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " PR.PR_REGISTRO_PRESUPUESTAL";
				$cadenaSql .= " WHERE";
				$cadenaSql .= " VIGENCIA = " . $variable ['vigencia'];
				$cadenaSql .= " AND CODIGO_UNIDAD_EJECUTORA = " . $variable ['unidadEjecutora'];
				$cadenaSql .= " AND NUMERO_DISPONIBILIDAD = " . $variable ['numeroRegistroPresupuestal'];
				break;
			
			case "estadoContratoAgora" :
				$cadenaSql = " SELECT ";
				$cadenaSql .= " estado ";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.contrato_evaluacion ";
				$cadenaSql .= " WHERE numero_contrato = " . $variable ['numeroContrato'];
				$cadenaSql .= " AND vigencia = " . $variable ['vigenciaContrato'];
				$cadenaSql .= " AND unidad_ejecutora = " . $variable ['unidadEjecutora'];
				break;
			
			case "listaSolicitudNecesidadXNumSolicitud" :
				$cadenaSql = " SELECT ";
				$cadenaSql .= " P.NUM_SOL_ADQ as NUM_SOL_ADQ,";
				$cadenaSql .= " P.VIGENCIA as VIGENCIA,";
				$cadenaSql .= " U.NOMBRE_DEPENDENCIA as DEPENDENCIA,";
				$cadenaSql .= " F.FUN_NOMBRE as FUNCIONARIO,";
				$cadenaSql .= " F.FUN_CARGO as FUNCIONARIO_CARGO,";
				$cadenaSql .= " F.FUN_TIPO as FUNCIONARIO_TIPO,";
				$cadenaSql .= " P.FECHA_SOLICITUD as FECHA_SOLICITUD,";
				$cadenaSql .= " P.ORIGEN_SOLICITUD as ORIGEN_SOLICITUD,";
				$cadenaSql .= " V.NOMBRE_DEPENDENCIA as DEPENDENCIA_DESTINO,";
				$cadenaSql .= " P.JUSTIFICACION as JUSTIFICACION,";
				$cadenaSql .= " P.CONDICIONES_CONTRATACION as CONDICIONES,";
				$cadenaSql .= " P.VALOR_CONTRATACION as VALOR_CONTRATACION,";
				$cadenaSql .= " P.OBJETO as OBJETO,";
				$cadenaSql .= " P.TIPO_CONTRATACION as TIPO_CONTRATACION,";
				$cadenaSql .= " P.PLAZO_EJECUCION as PLAZO_EJECUCION,";
				$cadenaSql .= " P.ELABORADO_POR as ELABORADO_POR,";
				$cadenaSql .= " O.ORG_NOMBRE as ORDENADOR_GASTO,";
				$cadenaSql .= " O.ORG_ORDENADOR_GASTO as CARGO_ORDENADOR_GASTO,";
				$cadenaSql .= " P.CODIGO_UNIDAD_EJECUTORA as CODIGO_UNIDAD_EJECUTORA,";
				$cadenaSql .= " P.ESTADO as ESTADO";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " CO_SOLICITUD_ADQ P";
				$cadenaSql .= " JOIN CO_DEPENDENCIAS U ON U.COD_DEPENDENCIA = P.DEPENDENCIA";
				$cadenaSql .= " JOIN CO_DEPENDENCIAS V ON V.COD_DEPENDENCIA = P.DEPENDENCIA_DESTINO";
				$cadenaSql .= " JOIN SICAARKA.FUNCIONARIOS F ON F.FUN_IDENTIFICADOR = P.FUNCIONARIO";
				$cadenaSql .= " JOIN SICAARKA.ORDENADORES_GASTO O ON O.ORG_TIPO_ORDENADOR = P.CODIGO_ORDENADOR AND O.ORG_ESTADO = 'A'";
				$cadenaSql .= " WHERE P.NUM_SOL_ADQ = " . $variable['idSolicitud'];
				$cadenaSql .= " AND P.VIGENCIA = " . $variable['vigencia'];
				$cadenaSql .= " AND P.CODIGO_UNIDAD_EJECUTORA = " . $variable ['unidadEjecutora'];
				break;
			
			case "consultarContratoRelacionado" :
				$cadenaSql = "SELECT *";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.contrato_evaluacion";
				$cadenaSql .= " WHERE ";
				$cadenaSql .= " vigencia = " . $variable ['vigencia'] . "";
				$cadenaSql .= " AND numero_contrato = " . $variable ['numero_contrato'] . ";";
				break;
			
			case "consultarContratosARGOByNum" :
				$cadenaSql = " SELECT ";
				$cadenaSql.=" CG.numero_contrato as numero_contrato,";
				$cadenaSql.=" CG.vigencia as vigencia, ";
				$cadenaSql.=" CG.unidad_ejecutora as unidad_ejecutora, ";
				$cadenaSql.=" upper(CG.objeto_contrato) as objeto_contrato,";
				$cadenaSql.=" cast(CG.plazo_ejecucion as text) || ' ' || UE.descripcion as plazo_ejecucion,";
				$cadenaSql.=" upper(FP.descripcion) as forma_pago,";
				$cadenaSql.=" OG.\"ORG_NOMBRE\" as nombre_ordenador_gasto,";
				$cadenaSql.=" OG.\"ORG_IDENTIFICACION\" as identificacion_ordenador_gasto,";
				$cadenaSql.=" OG.\"ORG_ORDENADOR_GASTO\" as cargo_ordenador_gasto,";
				$cadenaSql.=" OG.\"ORG_ESTADO\" as estado_ordenador_gasto,";
				$cadenaSql.=" CG.supervisor as identificacion_supervisor,";
				$cadenaSql.=" CG.numero_solicitud_necesidad as numero_solicitud_necesidad,";
				$cadenaSql.=" CG.numero_cdp as numero_cdp,";
				$cadenaSql.=" CG.resgistro_presupuestal as numero_rp,";
				$cadenaSql.=" CG.contratista as identificacion_contratista,";
				$cadenaSql.=" CG.convenio as convenio,";
				$cadenaSql.=" CG.valor_contrato as valor_contrato,";
				$cadenaSql.=" upper(CG.justificacion) as justificacion,";
				$cadenaSql.=" upper(CG.condiciones) as condiciones,";
				$cadenaSql.=" upper(CG.descripcion_forma_pago) as descripcion_forma_pago,";
				$cadenaSql.=" CG.fecha_registro as fecha_registro,";
				$cadenaSql.=" CC.descripcion as clase_contratista, ";
				$cadenaSql.=" upper(TC.descripcion) as tipo_control,";
				
				$cadenaSql.=" SC.nombre as nombre_supervisor,";
				$cadenaSql.=" SC.documento as documento_supervisor,";
				$cadenaSql.=" SC.digito_verificacion as digito_verificacion_supervisor,";
				$cadenaSql.=" SC.cargo as cargo_supervisor,";
				$cadenaSql.=" SC.tipo as tipo_supervisor,";
				$cadenaSql.=" SSI.\"ESF_SEDE\" as sede_supervisor,";
				
				$cadenaSql.=" EC.nombre_estado as estado";
				$cadenaSql.=" FROM argo.contrato_general CG";
				$cadenaSql.=" JOIN argo.parametros UE ON UE.id_parametro = CG.unidad_ejecucion";
				$cadenaSql.=" JOIN argo.parametros FP ON FP.id_parametro = CG.forma_pago";
				$cadenaSql.=" JOIN argo.argo_ordenadores OG ON OG.\"ORG_IDENTIFICADOR_UNICO\" = CG.ordenador_gasto";
				$cadenaSql.=" JOIN argo.parametros TC ON TC.id_parametro = CG.tipo_control";
				$cadenaSql.=" JOIN argo.parametros CC ON CC.id_parametro = CG.clase_contratista";
				$cadenaSql.=" JOIN argo.contrato_estado CE ON CE.numero_contrato = CG.numero_contrato AND CE.vigencia = CG.vigencia";
				$cadenaSql.=" JOIN argo.estado_contrato EC ON EC.id = CE.estado";
				
				$cadenaSql.=" JOIN argo.supervisor_contrato SC ON SC.id = CG.supervisor ";
				$cadenaSql.=" JOIN argo.\"sedes_SIC\" SSI ON SSI.\"ESF_ID_SEDE\" = SC.sede_supervisor ";
				
				$cadenaSql .= " WHERE CG.numero_contrato = '" . $variable['numeroContrato'] . "' AND CG.vigencia = " . $variable['vigenciaContrato'];
				$cadenaSql .= " AND CE.fecha_registro IN (SELECT fecha_registro FROM argo.contrato_estado WHERE numero_contrato = CG.numero_contrato ORDER BY fecha_registro DESC LIMIT 1)";
				$cadenaSql .= " GROUP BY CG.numero_contrato, CG.vigencia, UE.descripcion, FP.descripcion, OG.\"ORG_NOMBRE\", OG.\"ORG_IDENTIFICACION\", OG.\"ORG_ORDENADOR_GASTO\", OG.\"ORG_ESTADO\", CC.descripcion, TC.descripcion, EC.nombre_estado, CE.fecha_registro, SC.nombre, SC.documento, SC.digito_verificacion, SC.cargo, SC.tipo, SSI.\"ESF_SEDE\"";
				break;
			
			case "consultarIdSuperInter" :
				$cadenaSql=" SELECT ";
				$cadenaSql.=" string_agg(cast(id as text),',' ";
				$cadenaSql.=" ORDER BY ";
				$cadenaSql.=" id) ";
				$cadenaSql.=" FROM ";
				$cadenaSql.=" argo.supervisor_contrato ";
				$cadenaSql.=" WHERE ";
				$cadenaSql.=" documento = " . $variable;
				break;
			
			case "consultarContratosARGOBySupInt" :
				$cadenaSql = " SELECT ";
				$cadenaSql.=" CG.numero_contrato as numero_contrato,";
				$cadenaSql.=" CG.vigencia as vigencia, ";
				$cadenaSql.=" CG.unidad_ejecutora as unidad_ejecutora, ";
				$cadenaSql.=" upper(CG.objeto_contrato) as objeto_contrato,";
				$cadenaSql.=" cast(CG.plazo_ejecucion as text) || ' ' || UE.descripcion as plazo_ejecucion,";
				$cadenaSql.=" upper(FP.descripcion) as forma_pago,";
				$cadenaSql.=" OG.\"ORG_NOMBRE\" as nombre_ordenador_gasto,";
				$cadenaSql.=" OG.\"ORG_IDENTIFICACION\" as identificacion_ordenador_gasto,";
				$cadenaSql.=" OG.\"ORG_ORDENADOR_GASTO\" as cargo_ordenador_gasto,";
				$cadenaSql.=" OG.\"ORG_ESTADO\" as estado_ordenador_gasto,";
				$cadenaSql.=" CG.supervisor as identificacion_supervisor,";
				$cadenaSql.=" CG.numero_solicitud_necesidad as numero_solicitud_necesidad,";
				$cadenaSql.=" CG.numero_cdp as numero_cdp,";
				$cadenaSql.=" CG.resgistro_presupuestal as numero_rp,";
				$cadenaSql.=" CG.contratista as identificacion_contratista,";
				$cadenaSql.=" CG.convenio as convenio,";
				$cadenaSql.=" CG.valor_contrato as valor_contrato,";
				$cadenaSql.=" upper(CG.justificacion) as justificacion,";
				$cadenaSql.=" upper(CG.condiciones) as condiciones,";
				$cadenaSql.=" upper(CG.descripcion_forma_pago) as descripcion_forma_pago,";
				$cadenaSql.=" CG.fecha_registro as fecha_registro,";
				$cadenaSql.=" CC.descripcion as clase_contratista, ";
				$cadenaSql.=" upper(TC.descripcion) as tipo_control,";
				
				$cadenaSql.=" SC.nombre as nombre_supervisor,";
				$cadenaSql.=" SC.documento as documento_supervisor,";
				$cadenaSql.=" SC.digito_verificacion as digito_verificacion_supervisor,";
				$cadenaSql.=" SC.cargo as cargo_supervisor,";
				$cadenaSql.=" SC.tipo as tipo_supervisor,";
				$cadenaSql.=" SSI.\"ESF_SEDE\" as sede_supervisor,";
				
				$cadenaSql.=" EC.nombre_estado as estado";
				$cadenaSql.=" FROM argo.contrato_general CG";
				$cadenaSql.=" JOIN argo.parametros UE ON UE.id_parametro = CG.unidad_ejecucion";
				$cadenaSql.=" JOIN argo.parametros FP ON FP.id_parametro = CG.forma_pago";
				$cadenaSql.=" JOIN argo.argo_ordenadores OG ON OG.\"ORG_IDENTIFICADOR_UNICO\" = CG.ordenador_gasto";
				$cadenaSql.=" JOIN argo.parametros TC ON TC.id_parametro = CG.tipo_control";
				$cadenaSql.=" JOIN argo.parametros CC ON CC.id_parametro = CG.clase_contratista";
				$cadenaSql.=" JOIN argo.contrato_estado CE ON CE.numero_contrato = CG.numero_contrato AND CE.vigencia = CG.vigencia";
				$cadenaSql.=" JOIN argo.estado_contrato EC ON EC.id = CE.estado";
				
				$cadenaSql.=" JOIN argo.supervisor_contrato SC ON SC.id = CG.supervisor ";
				$cadenaSql.=" JOIN argo.\"sedes_SIC\" SSI ON SSI.\"ESF_ID_SEDE\" = SC.sede_supervisor ";
				
				$cadenaSql .= " WHERE CG.supervisor IN (" . $variable . ")";
				$cadenaSql .= " AND CE.fecha_registro IN (SELECT fecha_registro FROM argo.contrato_estado WHERE numero_contrato = CG.numero_contrato ORDER BY fecha_registro DESC LIMIT 1)";
				$cadenaSql .= " GROUP BY CG.numero_contrato, CG.vigencia, UE.descripcion, FP.descripcion, OG.\"ORG_NOMBRE\", OG.\"ORG_IDENTIFICACION\", OG.\"ORG_ORDENADOR_GASTO\", OG.\"ORG_ESTADO\", CC.descripcion, TC.descripcion, EC.nombre_estado, CE.fecha_registro, SC.nombre, SC.documento, SC.digito_verificacion, SC.cargo, SC.tipo, SSI.\"ESF_SEDE\"";
				$cadenaSql .= " ORDER BY CG.numero_contrato ASC";
				break;
			
			case "consultarContratosARGOADM" :
				$cadenaSql = " SELECT ";
				$cadenaSql.=" CG.numero_contrato as numero_contrato,";
				$cadenaSql.=" CG.vigencia as vigencia, ";
				$cadenaSql.=" CG.unidad_ejecutora as unidad_ejecutora, ";
				$cadenaSql.=" upper(CG.objeto_contrato) as objeto_contrato,";
				$cadenaSql.=" cast(CG.plazo_ejecucion as text) || ' ' || UE.descripcion as plazo_ejecucion,";
				$cadenaSql.=" upper(FP.descripcion) as forma_pago,";
				$cadenaSql.=" OG.\"ORG_NOMBRE\" as nombre_ordenador_gasto,";
				$cadenaSql.=" OG.\"ORG_IDENTIFICACION\" as identificacion_ordenador_gasto,";
				$cadenaSql.=" OG.\"ORG_ORDENADOR_GASTO\" as cargo_ordenador_gasto,";
				$cadenaSql.=" OG.\"ORG_ESTADO\" as estado_ordenador_gasto,";
				$cadenaSql.=" CG.supervisor as identificacion_supervisor,";
				$cadenaSql.=" CG.numero_solicitud_necesidad as numero_solicitud_necesidad,";
				$cadenaSql.=" CG.numero_cdp as numero_cdp,";
				$cadenaSql.=" CG.resgistro_presupuestal as numero_rp,";
				$cadenaSql.=" CG.contratista as identificacion_contratista,";
				$cadenaSql.=" CG.convenio as convenio,";
				$cadenaSql.=" CG.valor_contrato as valor_contrato,";
				$cadenaSql.=" upper(CG.justificacion) as justificacion,";
				$cadenaSql.=" upper(CG.condiciones) as condiciones,";
				$cadenaSql.=" upper(CG.descripcion_forma_pago) as descripcion_forma_pago,";
				$cadenaSql.=" CG.fecha_registro as fecha_registro,";
				$cadenaSql.=" CC.descripcion as clase_contratista, ";
				$cadenaSql.=" upper(TC.descripcion) as tipo_control,";
				
				$cadenaSql.=" SC.nombre as nombre_supervisor,";
				$cadenaSql.=" SC.documento as documento_supervisor,";
				$cadenaSql.=" SC.digito_verificacion as digito_verificacion_supervisor,";
				$cadenaSql.=" SC.cargo as cargo_supervisor,";
				$cadenaSql.=" SC.tipo as tipo_supervisor,";
				$cadenaSql.=" SSI.\"ESF_SEDE\" as sede_supervisor,";
				
				$cadenaSql.=" EC.nombre_estado as estado";
				$cadenaSql.=" FROM argo.contrato_general CG";
				$cadenaSql.=" JOIN argo.parametros UE ON UE.id_parametro = CG.unidad_ejecucion";
				$cadenaSql.=" JOIN argo.parametros FP ON FP.id_parametro = CG.forma_pago";
				$cadenaSql.=" JOIN argo.argo_ordenadores OG ON OG.\"ORG_IDENTIFICADOR_UNICO\" = CG.ordenador_gasto";
				$cadenaSql.=" JOIN argo.parametros TC ON TC.id_parametro = CG.tipo_control";
				$cadenaSql.=" JOIN argo.parametros CC ON CC.id_parametro = CG.clase_contratista";
				$cadenaSql.=" JOIN argo.contrato_estado CE ON CE.numero_contrato = CG.numero_contrato AND CE.vigencia = CG.vigencia";
				$cadenaSql.=" JOIN argo.estado_contrato EC ON EC.id = CE.estado";
				
				$cadenaSql.=" JOIN argo.supervisor_contrato SC ON SC.id = CG.supervisor ";
				$cadenaSql.=" JOIN argo.\"sedes_SIC\" SSI ON SSI.\"ESF_ID_SEDE\" = SC.sede_supervisor ";
				
				$cadenaSql .= " WHERE CE.fecha_registro IN (SELECT fecha_registro FROM argo.contrato_estado WHERE numero_contrato = CG.numero_contrato ORDER BY fecha_registro DESC LIMIT 1)";
				$cadenaSql .= " GROUP BY CG.numero_contrato, CG.vigencia, UE.descripcion, FP.descripcion, OG.\"ORG_NOMBRE\", OG.\"ORG_IDENTIFICACION\", OG.\"ORG_ORDENADOR_GASTO\", OG.\"ORG_ESTADO\", CC.descripcion, TC.descripcion, EC.nombre_estado, CE.fecha_registro, SC.nombre, SC.documento, SC.digito_verificacion, SC.cargo, SC.tipo, SSI.\"ESF_SEDE\"";
				$cadenaSql .= " ORDER BY CG.numero_contrato ASC";
				break;
			
			case "consultar_proveedor" :
				$cadenaSql = " SELECT U.identificacion, U.tipo_identificacion FROM prov_usuario U";
				$cadenaSql .= " WHERE U.id_usuario = '" . $variable . "'";
				break;
					
			case "consultar_DatosProveedor" :
				$cadenaSql = " SELECT * FROM agora.informacion_proveedor ";
				$cadenaSql .= " WHERE num_documento = '" . $variable . "';";
				break;
			
			case "Roles" :
				$cadenaSql = "SELECT DISTINCT  ";
				$cadenaSql.= " perfil.id_usuario usuario, ";
				$cadenaSql.= " perfil.id_subsistema cod_app, ";
				$cadenaSql.= " perfil.rol_id cod_rol, ";
				$cadenaSql.= " rol.rol_alias rol, ";
				$cadenaSql.= " perfil.fecha_caduca fecha_caduca, ";
				$cadenaSql.= " perfil.estado estado ";
				$cadenaSql.= " FROM ".$prefijo."usuario_subsistema perfil ";
				$cadenaSql.= " INNER JOIN ".$prefijo."rol rol  ";
				$cadenaSql.= " ON rol.rol_id=perfil.rol_id  ";
				$cadenaSql.= " AND rol.estado_registro_id=1 ";
				$cadenaSql.= " WHERE ";
				$cadenaSql.= " id_usuario='" . $variable . "'; ";
			
				break;
			
			case "listaContratoXNumContratoFechas" :
				$cadenaSql = " SELECT ";
				$cadenaSql .= " CG.fecha_inicio as inicio,";
				$cadenaSql .= " CG.fecha_fin as fin ";
				$cadenaSql .= " FROM argo.acta_inicio CG";
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
				$cadenaSql .= " FROM argo.contrato_general CG";
				$cadenaSql .= " JOIN argo.parametros UE ON UE.id_parametro = CG.unidad_ejecucion";
				$cadenaSql .= " JOIN argo.parametros FP ON FP.id_parametro = CG.forma_pago";
				$cadenaSql .= " JOIN argo.argo_ordenadores OG ON OG.\"ORG_IDENTIFICADOR_UNICO\" = CG.ordenador_gasto";
				$cadenaSql .= " JOIN argo.parametros TC ON TC.id_parametro = CG.tipo_control";
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
				$cadenaSql .= " WHERE  num_documento = '" . $variable."'";
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
				$cadenaSql .= " C.numero_necesidad,";
				$cadenaSql .= " C.documento_evaluador";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.contrato_evaluacion C";
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
				$cadenaSql .= " numero_necesidad,";
				$cadenaSql .= " vigencia,";
				$cadenaSql .= " documento_evaluador,";
				$cadenaSql .= " fecha_registro,";
				$cadenaSql .= " unidad_ejecutora,";
				$cadenaSql .= " estado";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.contrato_evaluacion ";
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
				$cadenaSql .= "	C.vigencia,";
				$cadenaSql .= "	C.unidad_ejecutora,";
				$cadenaSql .= "	CG.objeto_contrato,";
				$cadenaSql .= "	SC.nombre";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.evaluacion E";
				$cadenaSql .= " JOIN agora.contrato_evaluacion C ON C.id_contrato = E.id_contrato ";
				$cadenaSql .= " JOIN agora.contrato_proveedor CP ON CP.id_contrato = E.id_contrato ";
				$cadenaSql .= " JOIN argo.contrato_general CG ON CAST (CG.numero_contrato AS INTEGER) = C.numero_contrato AND CG.vigencia = C.vigencia";
				$cadenaSql .= " JOIN argo.supervisor_contrato SC ON SC.id = CG.supervisor ";
				$cadenaSql .= " WHERE CP.id_proveedor= '" . $variable . "'";
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
				$cadenaSql .= " JOIN agora.parametro_dependencia D ON D.id_dependencia = S.id_dependencia ";
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
				$cadenaSql .= " JOIN agora.parametro_dependencia D ON D.id_dependencia = S.id_dependencia ";
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
				$cadenaSql = "UPDATE agora.contrato_evaluacion SET ";
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
