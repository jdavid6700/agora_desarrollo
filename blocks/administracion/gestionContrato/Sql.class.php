<?php

namespace hojaDeVida\crearDocente;

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
			
			/* CONSULTAR siCapital */
			case "listaSolicitudNecesidad" :
				$cadenaSql = "SELECT *";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " CO_SOLICITUD_ADQ";
				$cadenaSql .= " WHERE VIGENCIA = " . $variable . "";
				break;
				
			case "listaSolicitudNecesidadXVigencia" :
				$cadenaSql=" SELECT ";
				$cadenaSql.=" P.NUM_SOL_ADQ as NUM_SOL_ADQ,";
				$cadenaSql.=" P.VIGENCIA as VIGENCIA,";
				$cadenaSql.=" U.NOMBRE_DEPENDENCIA as DEPENDENCIA,";
				$cadenaSql.=" F.FUN_NOMBRE as FUNCIONARIO,";
				$cadenaSql.=" F.FUN_CARGO as FUNCIONARIO_CARGO,";
				$cadenaSql.=" F.FUN_TIPO as FUNCIONARIO_TIPO,";
				$cadenaSql.=" P.FECHA_SOLICITUD as FECHA_SOLICITUD,";
				$cadenaSql.=" P.ORIGEN_SOLICITUD as ORIGEN_SOLICITUD,";
				$cadenaSql.=" V.NOMBRE_DEPENDENCIA as DEPENDENCIA_DESTINO,";
				$cadenaSql.=" P.JUSTIFICACION as JUSTIFICACION,";
				$cadenaSql.=" P.CONDICIONES_CONTRATACION as CONDICIONES,";
				$cadenaSql.=" P.VALOR_CONTRATACION as VALOR_CONTRATACION,";
				$cadenaSql.=" P.OBJETO as OBJETO,";
				$cadenaSql.=" P.TIPO_CONTRATACION as TIPO_CONTRATACION,";
				$cadenaSql.=" P.PLAZO_EJECUCION as PLAZO_EJECUCION,";
				$cadenaSql.=" P.ELABORADO_POR as ELABORADO_POR,";
				$cadenaSql.=" O.ORG_NOMBRE as ORDENADOR_GASTO,";
				$cadenaSql.=" O.ORG_ORDENADOR_GASTO as CARGO_ORDENADOR_GASTO,";
				$cadenaSql.=" P.CODIGO_UNIDAD_EJECUTORA as CODIGO_UNIDAD_EJECUTORA,";
				$cadenaSql.=" P.ESTADO as ESTADO";
				$cadenaSql.=" FROM ";
				$cadenaSql.=" CO_SOLICITUD_ADQ P";
				$cadenaSql.=" JOIN CO_DEPENDENCIAS U ON U.COD_DEPENDENCIA = P.DEPENDENCIA";
				$cadenaSql.=" JOIN CO_DEPENDENCIAS V ON V.COD_DEPENDENCIA = P.DEPENDENCIA_DESTINO";
				$cadenaSql.=" JOIN SICAARKA.FUNCIONARIOS F ON F.FUN_IDENTIFICADOR = P.FUNCIONARIO";
				$cadenaSql.=" JOIN SICAARKA.ORDENADORES_GASTO O ON O.ORG_TIPO_ORDENADOR = P.CODIGO_ORDENADOR AND O.ORG_ESTADO = 'A'";
				$cadenaSql.=" WHERE P.VIGENCIA = " . $variable ['vigencia'];
				$cadenaSql.=" AND P.NUM_SOL_ADQ NOT IN (" . $variable ['solicitudes'] . ")";
				$cadenaSql.=" ORDER BY P.NUM_SOL_ADQ DESC";
				break;
				
			case "listaContratoXVigencia" :
				$cadenaSql = " SELECT ";
				$cadenaSql.=" CG.numero_contrato as numero_contrato,";
				$cadenaSql.=" CG.vigencia as vigencia, ";
				$cadenaSql.=" CG.objeto_contrato as objeto_contrato,";
				$cadenaSql.=" CG.fecha_inicio as fecha_inicio, ";
				$cadenaSql.=" CG.fecha_final as fecha_final,";
				$cadenaSql.=" cast(CG.plazo_ejecucion as text) || ' ' || UE.descripcion as plazo_ejecucion,";
				$cadenaSql.=" FP.descripcion as forma_pago,";
				$cadenaSql.=" OG.\"ORG_NOMBRE\" as nombre_ordenador_gasto,";
				$cadenaSql.=" OG.\"ORG_IDENTIFICACION\" as identificacion_ordenador_gasto,";
				$cadenaSql.=" OG.\"ORG_ORDENADOR_GASTO\" as cargo_ordenador_gasto,";
				$cadenaSql.=" OG.\"ORG_ESTADO\" as estado_ordenador_gasto,";
				$cadenaSql.=" CG.supervisor as identificacion_supervisor,";
				$cadenaSql.=" CG.numero_solicitud_necesidad as numero_solicitud_necesidad,";
				$cadenaSql.=" CG.numero_cdp as numero_cdp,";
				$cadenaSql.=" CG.contratista as identificacion_contratista,";
				$cadenaSql.=" CG.valor_contrato as valor_contrato,";
				$cadenaSql.=" CG.justificacion as justificacion,";
				$cadenaSql.=" CG.condiciones as condiciones,";
				$cadenaSql.=" CG.descripcion_forma_pago as descripcion_forma_pago,";
				$cadenaSql.=" CG.fecha_registro as fecha_registro,";
				$cadenaSql.=" TC.descripcion as tipo_control";
				$cadenaSql.=" FROM contractual.contrato_general CG";
				$cadenaSql.=" JOIN contractual.parametros UE ON UE.id_parametro = CG.unidad_ejecucion";
				$cadenaSql.=" JOIN contractual.parametros FP ON FP.id_parametro = CG.forma_pago";
				$cadenaSql.=" JOIN contractual.argo_ordenadores OG ON OG.\"ORG_IDENTIFICADOR_UNICO\" = CG.ordenador_gasto";
				$cadenaSql.=" JOIN contractual.parametros TC ON TC.id_parametro = CG.tipo_control";
				$cadenaSql .= " WHERE CG.vigencia = " . $variable ['vigencia'];
				$cadenaSql .= " AND CG.numero_contrato NOT IN (" . $variable ['contratos'] . ")";
				$cadenaSql .= " ORDER BY CG.numero_contrato DESC";
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
				break;
			
			case "listarObjetosSinCotizacionXVigencia" :
				$cadenaSql=" SELECT ";
				$cadenaSql.=" string_agg(cast(numero_solicitud as text),',' ";
				$cadenaSql.=" ORDER BY ";
				$cadenaSql.=" numero_solicitud) ";
				$cadenaSql.=" FROM ";
				$cadenaSql.=" agora.objeto_contratar ";
				$cadenaSql.=" WHERE ";
				$cadenaSql.=" vigencia = " . $variable;
				$cadenaSql.=" AND ";
				$cadenaSql.=" estado = 'CREADO';";
				break;
				
			case "listarContratosRelacionadosXVigencia" :
				$cadenaSql = " SELECT ";
				$cadenaSql .= " string_agg(''' || cast(numero_contrato as text) || ''',',' ";
				$cadenaSql .= " ORDER BY ";
				$cadenaSql .= " numero_contrato) ";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.contrato ";
				$cadenaSql .= " WHERE ";
				$cadenaSql .= " vigencia = " . $variable . ";";
				break;
				
				
			case "listarObjetosConCotizacionXVigencia" :
				$cadenaSql = " SELECT ";
				$cadenaSql .= " string_agg(cast(numero_solicitud as text),',' ";
				$cadenaSql .= " ORDER BY ";
				$cadenaSql .= " numero_solicitud) ";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.objeto_contratar ";
				$cadenaSql .= " WHERE ";
				$cadenaSql .= " vigencia = " . $variable;
				$cadenaSql .= " AND ";
				$cadenaSql .= " estado = 'COTIZACION';";
				break;
				
			case "listaSolicitudNecesidadXNumSolicitudSinCotizar" :
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
				$cadenaSql .= " WHERE P.NUM_SOL_ADQ IN (" . $variable ['solicitudes'] . ")";
				$cadenaSql .= " AND P.VIGENCIA = " . $variable ['vigencia'];
				break;
				
			case "listaSolicitudNecesidadXNumSolicitudEnCotizar" :
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
				$cadenaSql .= " WHERE P.NUM_SOL_ADQ IN (" . $variable ['solicitudes'] . ")";
				$cadenaSql .= " AND P.VIGENCIA = " . $variable ['vigencia'];
				break;
				
			case "estadoSolicitudAgora" :
				$cadenaSql = " SELECT ";
				$cadenaSql .= " estado ";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.objeto_contratar ";
				$cadenaSql .= " WHERE numero_solicitud = " . $variable['idSolicitud'];
				$cadenaSql .= " AND vigencia = " . $variable['vigencia'];
				break;
				
			case "informacionSolicitudAgora" :
				$cadenaSql = " SELECT ";
				$cadenaSql .= " id_objeto, ";
				$cadenaSql .= " fecharegistro, ";
				$cadenaSql .= " id_unidad, ";
				$cadenaSql .= " cantidad, ";
				$cadenaSql .= " numero_cotizaciones, ";
				$cadenaSql .= " fechasolicitudcotizacion, ";
				$cadenaSql .= " codigociiu, ";
				$cadenaSql .= " estado ";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.objeto_contratar ";
				$cadenaSql .= " WHERE numero_solicitud = " . $variable ['idSolicitud'];
				$cadenaSql .= " AND vigencia = " . $variable ['vigencia'];
				$cadenaSql .= " LIMIT 1; ";
				break;
				
				
			case "informacionCIIURelacionada" :
				$cadenaSql=" SELECT D.id_division as num_division, P.id_clase as num_clase, C.id_subclase as num_subclase";
				$cadenaSql.=" FROM agora.objeto_contratar T";
				$cadenaSql.=" JOIN agora.ciiu_subclase C ON C.id_subclase = T.codigociiu";
				$cadenaSql.=" JOIN agora.ciiu_clase P ON P.id_clase = C.clase";
				$cadenaSql.=" JOIN agora.ciiu_division D ON D.id_division = P.division";
				$cadenaSql .= " WHERE T.numero_solicitud = " . $variable ['idSolicitud'];
				$cadenaSql .= " AND T.vigencia = " . $variable ['vigencia'];
				$cadenaSql .= " LIMIT 1; ";
				break;
				
				
			case "filtroVigencia" :
				$cadenaSql=" SELECT ";
				$cadenaSql.=" VIGENCIA AS ID, ";
				$cadenaSql.=" VIGENCIA AS VIGENCIA ";
				$cadenaSql.=" FROM ";
				$cadenaSql.=" CO_SOLICITUD_ADQ ";
				$cadenaSql.=" GROUP BY VIGENCIA ";
				$cadenaSql.=" ORDER BY VIGENCIA";
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
				$cadenaSql .= " '" . $variable ['idObjeto'] . "',";
				$cadenaSql .= " '" . $variable ['tipo'] . "',";
				$cadenaSql .= " '" . $variable ['fecha'] . "'";
				$cadenaSql .= " ) RETURNING id_codigo_validacion;";
				break;
			
			/* BUSCAR PROVEEDORES SOLICITUD DE COTIZACION */
			case "buscarProveedores" :
				$cadenaSql = "SELECT";
				$cadenaSql .= " P.id_proveedor,";
				$cadenaSql .= "	P.correo,";
				$cadenaSql .= " P.nom_proveedor,";
				$cadenaSql .= "	P.num_documento,";
				$cadenaSql .= " P.puntaje_evaluacion,";
				$cadenaSql .= "	P.clasificacion_evaluacion";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.solicitud_cotizacion S";
				$cadenaSql .= " JOIN agora.informacion_proveedor P ON P.id_proveedor = S.id_proveedor";
				$cadenaSql .= " WHERE  id_objeto=" . $variable;
				break;
				
			case "buscarProveedoresInfoCotizacion" :
				$cadenaSql=" SELECT";
				$cadenaSql.=" P.id_proveedor,";
				$cadenaSql.=" P.tipopersona,";
				$cadenaSql.=" P.correo,";
				$cadenaSql.=" P.direccion,";
				$cadenaSql.=" P.web,";
				$cadenaSql.=" (U.nombre || ', ' || D.nombre || ', ' || 'Colombia') as ubicacion, ";
				$cadenaSql.=" P.nom_proveedor,";
				$cadenaSql.=" P.num_documento,";
				$cadenaSql.=" P.puntaje_evaluacion,";
				$cadenaSql.=" P.clasificacion_evaluacion";
				$cadenaSql.=" FROM";
				$cadenaSql.=" agora.solicitud_cotizacion S";
				$cadenaSql.=" JOIN agora.informacion_proveedor P ON P.id_proveedor = S.id_proveedor";
				$cadenaSql.=" JOIN agora.ciudad U ON P.id_ciudad_contacto = U.id_ciudad";
				$cadenaSql.=" JOIN agora.departamento D ON U.id_departamento = D.id_departamento";
				$cadenaSql.=" WHERE  id_objeto=" . $variable;
				break;
			
			/* REGISTRAR COTIZACION */
			case "ingresarCotizacion" :
				$hoy = date ( "Y-m-d" );
				
				$cadenaSql = " INSERT INTO agora.solicitud_cotizacion";
				$cadenaSql .= " (";
				$cadenaSql .= " id_objeto,";
				$cadenaSql .= " id_proveedor";
				$cadenaSql .= " )";
				$cadenaSql .= " VALUES";
				$cadenaSql .= " (";
				$cadenaSql .= " '" . $variable [0] . "',";
				$cadenaSql .= " '" . $variable [1] . "'";
				$cadenaSql .= " );";
				break;
			
			/* ACTUALIZAR - OBJETO CONTRATO - ESTADO */
			case 'actualizarObjeto' :
				$cadenaSql = "UPDATE agora.objeto_contratar SET ";
				$cadenaSql .= "estado='" . $variable ['estado'] . "',";
				$cadenaSql .= "fechasolicitudcotizacion='" . $variable ['fecha'] . "'";
				$cadenaSql .= " WHERE id_objeto=";
				$cadenaSql .= "'" . $variable ['idObjeto'] . "' ";
				break;
			
			/* verificar si existe proveedores con la actividad economica */
			case "verificarActividad" :
				$cadenaSql = "SELECT *";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.proveedor_actividad_ciiu";
				$cadenaSql .= " WHERE  id_subclase = '" . $variable . "'";
				$cadenaSql .= " LIMIT 5; ";
				break;
			
			/**
			 * Lista proveedores
			 * Que cumlen con la actividad economica
			 * Que Tienen puntaje mayor a 45
			 * El limite de registros lo establece el objeto a contratar
			 */
			case "proveedoresByClasificacion" :
				$cadenaSql = "SELECT";
				$cadenaSql .= " id_proveedor,";
				$cadenaSql .= " P.num_documento,";
				$cadenaSql .= "	nom_proveedor,";
				$cadenaSql .= "	puntaje_evaluacion,";
				$cadenaSql .= "	clasificacion_evaluacion";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.informacion_proveedor P";
				$cadenaSql .= " JOIN agora.proveedor_actividad_ciiu A ON A.num_documento = P.num_documento";
				$cadenaSql .= " WHERE  A.id_subclase = '" . $variable ['actividadEconomica'] . "'";
				$cadenaSql .= " AND P.puntaje_evaluacion > 45";
				$cadenaSql .= " AND P.estado = '1'";
				$cadenaSql .= " ORDER BY puntaje_evaluacion DESC";
				$cadenaSql .= " LIMIT " . $variable ['numCotizaciones'];
				break;
			
			/* ULTIMO NUMERO DE SECUENCIA */
			case "lastIdObjeto" :
				$cadenaSql = "SELECT";
				$cadenaSql .= " last_value";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.prov_objeto_contratar_id_objeto_seq";
				break;
			
			/* CONSULTAR - OBJETO A CONTRATAR - ESPECIFICO */
			case "objetoContratar" :
				$cadenaSql = "SELECT";
				$cadenaSql .= " numero_solicitud,";
				$cadenaSql .= " vigencia,";
				$cadenaSql .= "	codigociiu,";
				$cadenaSql .= "	UPPER(S.nombre) AS actividad,";
				$cadenaSql .= "	cantidad,";
				$cadenaSql .= "	fecharegistro,";
				$cadenaSql .= "	fechasolicitudcotizacion,";
				$cadenaSql .= "	estado";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.objeto_contratar O";
				$cadenaSql .= " JOIN agora.ciiu_subclase S ON S.id_subclase = O.codigociiu";
				$cadenaSql .= " WHERE  id_objeto=" . $variable; // Activo
				break;
			
			/* LISTA - OBJETO A CONTRATAR */
			case "listaObjetoContratar" :
				$cadenaSql = "SELECT";
				$cadenaSql .= " id_objeto,";
				$cadenaSql .= " numero_solicitud,";
				$cadenaSql .= " vigencia,";
				$cadenaSql .= "	codigociiu,";
				$cadenaSql .= "	UPPER(S.nombre) AS actividad,";
				$cadenaSql .= "	cantidad,";
				$cadenaSql .= "	fechasolicitudcotizacion,";
				$cadenaSql .= "	fecharegistro,";
				$cadenaSql .= "	numero_cotizaciones,";
				$cadenaSql .= "	estado";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.objeto_contratar O";
				$cadenaSql .= " JOIN agora.ciiu_subclase S ON S.id_subclase = O.codigociiu";
				$cadenaSql .= " WHERE  estado= '" . $variable ."'"; // Activo
				$cadenaSql .= " ORDER BY fechaRegistro";
				break;
			
			/* CIIU */
			case "ciiuDivision" :
				$cadenaSql = "SELECT";
				$cadenaSql .= " id_division,";
				$cadenaSql .= "	nombre";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.ciiu_division";
				$cadenaSql .= " ORDER BY nombre";
				break;
			
			case "ciiuGrupo" :
				$cadenaSql = "SELECT";
				$cadenaSql .= " id_clase,";
				$cadenaSql .= "	nombre";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.ciiu_clase";
				$cadenaSql .= " WHERE division ='" . $variable . "'";
				$cadenaSql .= " ORDER BY nombre";
				break;
			
			case "ciiuClase" :
				$cadenaSql = "SELECT";
				$cadenaSql .= " id_subclase,";
				$cadenaSql .= "	nombre";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.ciiu_subclase";
				$cadenaSql .= " WHERE clase ='" . $variable . "'";
				$cadenaSql .= " ORDER BY nombre";
				break;
			/* LISTA - ORDENAR DEL GASTO */
			case "ordenador" :
				$cadenaSql = "SELECT";
				$cadenaSql .= " id_ordenador,";
				$cadenaSql .= "	nombre_ordenador";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.ordenador_gasto";
				$cadenaSql .= " order by nombre_ordenador";
				break;
			/* LISTA - DEPENDENCIA */
			case "dependencia" :
				$cadenaSql = "SELECT";
				$cadenaSql .= " id_dependencia,";
				$cadenaSql .= "	dependencia";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.dependencia";
				$cadenaSql .= " ORDER BY dependencia";
				break;
			
			/* LISTA - UNIDAD */
			case "unidad" :
				$cadenaSql = "SELECT";
				$cadenaSql .= " id_unidad,";
				$cadenaSql .= "	(tipo || '-' || unidad) AS unidad";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.unidad";
				$cadenaSql .= " ORDER BY tipo";
				break;
				
				
				
				
			/* REGISTRAR DATOS DEL OBJETO A CONTRATAR */
			case "registrar" :
				$hoy = date ( "Y-m-d" );
				$cadenaSql = " INSERT INTO agora.objeto_contratar";
				$cadenaSql .= " (";
				$cadenaSql .= " numero_solicitud,";
				$cadenaSql .= " vigencia,";
				$cadenaSql .= " codigociiu,";
				$cadenaSql .= " id_unidad,";
				$cadenaSql .= " cantidad,";
				$cadenaSql .= " numero_cotizaciones,";
				$cadenaSql .= " estado,";
				$cadenaSql .= " fecharegistro";
				$cadenaSql .= " )";
				$cadenaSql .= " VALUES";
				$cadenaSql .= " (";
				$cadenaSql .= " " . $variable ['numero_solicitud'] . ",";
				$cadenaSql .= " " . $variable ['vigencia'] . ",";
				$cadenaSql .= " '" . $variable ['claseCIIU'] . "',";
				$cadenaSql .= " '" . $variable ['unidad'] . "',";
				$cadenaSql .= " '" . $variable ['cantidad'] . "',";
				$cadenaSql .= " '" . $variable ['cotizaciones'] . "',";
				$cadenaSql .= " 'CREADO',";
				$cadenaSql .= " '" . $hoy . "'";
				$cadenaSql .= " );";
				break;
				
				/* ACTUALIZAR DATOS DEL OBJETO A CONTRATAR */
			case "actualizar" :
				$hoy = date ( "Y-m-d" );
				$cadenaSql = " UPDATE agora.objeto_contratar SET";
				$cadenaSql .= " numero_solicitud = " . $variable ['numero_solicitud'] . ",";
				$cadenaSql .= " vigencia = " . $variable ['vigencia'] . ",";
				$cadenaSql .= " codigociiu = '" . $variable ['claseCIIU'] . "',";
				$cadenaSql .= " id_unidad = '" . $variable ['unidad'] . "',";
				$cadenaSql .= " cantidad = '" . $variable ['cantidad'] . "',";
				$cadenaSql .= " numero_cotizaciones = '" . $variable ['cotizaciones'] . "',";
				$cadenaSql .= " estado = 'CREADO',";
				$cadenaSql .= " fecharegistro = '" . $hoy . "'";
				$cadenaSql .= " WHERE numero_solicitud = " . $variable ['numero_solicitud'];
				$cadenaSql .= " AND vigencia = " . $variable ['vigencia'] . ";";
				break;
	
					
				
				






		
			/**
			 * Clausulas genéricas.
			 * se espera que estén en todos los formularios
			 * que utilicen esta plantilla
			 */
			case "iniciarTransaccion" :
				$cadenaSql = "START TRANSACTION";
				break;
			
			case "finalizarTransaccion" :
				$cadenaSql = "COMMIT";
				break;
			
			case "cancelarTransaccion" :
				$cadenaSql = "ROLLBACK";
				break;
			
			case "eliminarTemp" :
				
				$cadenaSql = "DELETE ";
				$cadenaSql .= "FROM ";
				$cadenaSql .= $prefijo . "tempFormulario ";
				$cadenaSql .= "WHERE ";
				$cadenaSql .= "id_sesion = '" . $variable . "' ";
				break;
			
			case "insertarTemp" :
				$cadenaSql = "INSERT INTO ";
				$cadenaSql .= $prefijo . "tempFormulario ";
				$cadenaSql .= "( ";
				$cadenaSql .= "id_sesion, ";
				$cadenaSql .= "formulario, ";
				$cadenaSql .= "campo, ";
				$cadenaSql .= "valor, ";
				$cadenaSql .= "fecha ";
				$cadenaSql .= ") ";
				$cadenaSql .= "VALUES ";
				
				foreach ( $_REQUEST as $clave => $valor ) {
					$cadenaSql .= "( ";
					$cadenaSql .= "'" . $idSesion . "', ";
					$cadenaSql .= "'" . $variable ['formulario'] . "', ";
					$cadenaSql .= "'" . $clave . "', ";
					$cadenaSql .= "'" . $valor . "', ";
					$cadenaSql .= "'" . $variable ['fecha'] . "' ";
					$cadenaSql .= "),";
				}
				
				$cadenaSql = substr ( $cadenaSql, 0, (strlen ( $cadenaSql ) - 1) );
				break;
			
			case "rescatarTemp" :
				$cadenaSql = "SELECT ";
				$cadenaSql .= "id_sesion, ";
				$cadenaSql .= "formulario, ";
				$cadenaSql .= "campo, ";
				$cadenaSql .= "valor, ";
				$cadenaSql .= "fecha ";
				$cadenaSql .= "FROM ";
				$cadenaSql .= $prefijo . "tempFormulario ";
				$cadenaSql .= "WHERE ";
				$cadenaSql .= "id_sesion='" . $idSesion . "'";
				break;
			

								

		}
		
		return $cadenaSql;
	}
}

?>
