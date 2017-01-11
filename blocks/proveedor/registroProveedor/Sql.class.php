<?php

namespace proveedor\registroProveedor;

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
			
			case "consultarContratosARGO" :
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
				$cadenaSql.=" EC.nombre_estado as estado";
				$cadenaSql.=" FROM argo.contrato_general CG";
				$cadenaSql.=" JOIN argo.parametros UE ON UE.id_parametro = CG.unidad_ejecucion";
				$cadenaSql.=" JOIN argo.parametros FP ON FP.id_parametro = CG.forma_pago";
				$cadenaSql.=" JOIN argo.argo_ordenadores OG ON OG.\"ORG_IDENTIFICADOR_UNICO\" = CG.ordenador_gasto";
				$cadenaSql.=" JOIN argo.parametros TC ON TC.id_parametro = CG.tipo_control";
				$cadenaSql.=" JOIN argo.parametros CC ON CC.id_parametro = CG.clase_contratista";
				$cadenaSql.=" JOIN argo.contrato_estado CE ON CE.numero_contrato = CG.numero_contrato AND CE.vigencia = CG.vigencia";
				$cadenaSql.=" JOIN argo.estado_contrato EC ON EC.id = CE.estado";
				$cadenaSql .= " WHERE CG.contratista IN (" . $variable . ")";
				$cadenaSql .= " AND CE.fecha_registro IN (SELECT fecha_registro FROM argo.contrato_estado WHERE numero_contrato = CG.numero_contrato ORDER BY fecha_registro DESC LIMIT 1)";
				$cadenaSql .= " GROUP BY CG.numero_contrato, CG.vigencia, UE.descripcion, FP.descripcion, OG.\"ORG_NOMBRE\", OG.\"ORG_IDENTIFICACION\", OG.\"ORG_ORDENADOR_GASTO\", OG.\"ORG_ESTADO\", CC.descripcion, TC.descripcion, EC.nombre_estado, CE.fecha_registro";
				$cadenaSql .= " ORDER BY CG.numero_contrato ASC";
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
					$cadenaSql.=" EC.nombre_estado as estado";
					$cadenaSql.=" FROM argo.contrato_general CG";
					$cadenaSql.=" JOIN argo.parametros UE ON UE.id_parametro = CG.unidad_ejecucion";
					$cadenaSql.=" JOIN argo.parametros FP ON FP.id_parametro = CG.forma_pago";
					$cadenaSql.=" JOIN argo.argo_ordenadores OG ON OG.\"ORG_IDENTIFICADOR_UNICO\" = CG.ordenador_gasto";
					$cadenaSql.=" JOIN argo.parametros TC ON TC.id_parametro = CG.tipo_control";
					$cadenaSql.=" JOIN argo.parametros CC ON CC.id_parametro = CG.clase_contratista";
					$cadenaSql.=" JOIN argo.contrato_estado CE ON CE.numero_contrato = CG.numero_contrato AND CE.vigencia = CG.vigencia";
					$cadenaSql.=" JOIN argo.estado_contrato EC ON EC.id = CE.estado";
					$cadenaSql .= " WHERE CG.numero_contrato = '" . $variable['numeroContrato'] . "' AND CG.vigencia = " . $variable['vigenciaContrato'];
					$cadenaSql .= " AND CE.fecha_registro IN (SELECT fecha_registro FROM argo.contrato_estado WHERE numero_contrato = CG.numero_contrato ORDER BY fecha_registro DESC LIMIT 1)";
					$cadenaSql .= " GROUP BY CG.numero_contrato, CG.vigencia, UE.descripcion, FP.descripcion, OG.\"ORG_NOMBRE\", OG.\"ORG_IDENTIFICACION\", OG.\"ORG_ORDENADOR_GASTO\", OG.\"ORG_ESTADO\", CC.descripcion, TC.descripcion, EC.nombre_estado, CE.fecha_registro";
					break;
			
			case "consultarEPS" :
				$cadenaSql = " SELECT";
				$cadenaSql .= " id,";
				$cadenaSql .= " nombre";
				$cadenaSql .= " FROM";
				$cadenaSql .= " core.parametro_entidad";
				$cadenaSql .= " WHERE id_estado != 2";
				$cadenaSql .= " AND id_tipo_entidad = 1";
				$cadenaSql .= " ORDER BY nombre";
				break;
			case "consultarFondoPension" :
				$cadenaSql = " SELECT";
				$cadenaSql .= " id,";
				$cadenaSql .= " nombre";
				$cadenaSql .= " FROM";
				$cadenaSql .= " core.parametro_entidad";
				$cadenaSql .= " WHERE id_estado != 2";
				$cadenaSql .= " AND id_tipo_entidad = 3";
				$cadenaSql .= " ORDER BY nombre";
				break;
			
			case "consultarCaja" :
				$cadenaSql = " SELECT";
				$cadenaSql .= " id,";
				$cadenaSql .= " nombre";
				$cadenaSql .= " FROM";
				$cadenaSql .= " core.parametro_entidad";
				$cadenaSql .= " WHERE id_estado != 2";
				$cadenaSql .= " AND id_tipo_entidad = 4";
				$cadenaSql .= " ORDER BY nombre";
				break;
			
			/* CONSULTAR - EVALUACION POR ID CONTRATO */
			case "evalaucionByIdContrato" :
				$cadenaSql = "SELECT ";
				$cadenaSql .= " puntaje_total";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.evaluacion ";
				$cadenaSql .= " WHERE id_contrato= '" . $variable . "'";
				break;
			
			/* CONSULTAR - EVALUACION POR ID CONTRATO */
			case "buscarAreaConocimiento" :
				$cadenaSql = "SELECT ";
				$cadenaSql .= " id_area,";
				$cadenaSql .= " nombre";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " core.snies_area ";
				$cadenaSql .= " WHERE estado != 'INACTIVO';";
				break;
			
			case "buscarAreaConocimientoXNBC" :
				$cadenaSql = "SELECT ";
				$cadenaSql .= " id_area,";
				$cadenaSql .= " nombre";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " core.snies_nucleo_basico ";
				$cadenaSql .= " WHERE id_nucleo = " . $variable . ";";
				break;
			
			case "buscarNBCAjax" :
				$cadenaSql = "SELECT ";
				$cadenaSql .= " id_nucleo,";
				$cadenaSql .= " nombre";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " core.snies_nucleo_basico ";
				$cadenaSql .= " WHERE estado != 'INACTIVO'";
				$cadenaSql .= " AND id_area = " . $variable . ";";
				break;
			
			/* CONSULTAR - CONTRATO por ID */
			case "consultarContratoByID" : // ***********************************************************************
				$cadenaSql = " SELECT ";
				$cadenaSql .= " C.id_contrato, ";
				$cadenaSql .= " numero_contrato,";
				$cadenaSql .= " C.vigencia, ";
				$cadenaSql .= " C.unidad_ejecutora, ";
				$cadenaSql .= " P.nom_proveedor, ";
				$cadenaSql .= " P.num_documento, ";
				$cadenaSql .= " O.numero_solicitud ";
				$cadenaSql .= " FROM agora.contrato C ";
				$cadenaSql .= " JOIN agora.objeto_contratar O ON O.id_objeto = C.id_objeto";
				$cadenaSql .= " JOIN agora.contrato_proveedor CP ON CP.id_contrato = C.id_contrato ";
				$cadenaSql .= " JOIN agora.informacion_proveedor P ON P.id_proveedor = CP.id_proveedor ";
				$cadenaSql .= " WHERE  C.id_contrato=" . $variable;
				break;
			
			/* ACTUALIZAR - ESTADO PROVEEDOR */
			case 'updateEstado' : // ****************************************************************************************
				$cadenaSql = "UPDATE agora.informacion_proveedor SET ";
				$cadenaSql .= "estado = '" . $variable ['estado'] . "'";
				$cadenaSql .= " WHERE id_proveedor = ";
				$cadenaSql .= "'" . $variable ['idProveedor'] . "' ";
				break;
			
			/* ULTIMO NUMERO DE SECUENCIA */
			case "lastIdProveedor" :
				$cadenaSql = "SELECT";
				$cadenaSql .= " last_value";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.prov_proveedor_info_id_proveedor_seq";
				break;
			
			/* REGISTRAR DATOS - USUARIO */
			case "registrarActividad" : // **********************************************************
				$cadenaSql = " INSERT INTO ";
				$cadenaSql .= " agora.proveedor_actividad_ciiu ";
				$cadenaSql .= " (";
				$cadenaSql .= " num_documento,";
				$cadenaSql .= " id_subclase";
				$cadenaSql .= " )";
				$cadenaSql .= " VALUES";
				$cadenaSql .= " (";
				$cadenaSql .= " '" . $variable ['nit'] . "',";
				$cadenaSql .= " '" . $variable ['actividad'] . "'";
				$cadenaSql .= " );";
				break;
			
			/* VERIFICAR NUMERO DE NIT */
			case "verificarActividad" : // ******************************************************************
				$cadenaSql = " SELECT";
				$cadenaSql .= " num_documento";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.proveedor_actividad_ciiu ";
				$cadenaSql .= " WHERE num_documento= '" . $variable ['nit'] . "'";
				$cadenaSql .= " AND id_subclase = '" . $variable ['actividad'] . "'";
				break;
			
			/* CONSULTAR ACTIVIDADES DEL PROVEEDOR */
			case "consultarActividades" : // ********************************************************************
				$cadenaSql = " SELECT";
				$cadenaSql .= " A.id_subclase,";
				$cadenaSql .= " nombre";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.proveedor_actividad_ciiu A";
				$cadenaSql .= " JOIN core.ciiu_subclase S ON S.id_subclase = A.id_subclase ";
				$cadenaSql .= " WHERE num_documento= " . $variable;
				break;
			
			// **************************************************************************
			/* REGISTRAR DATOS - USUARIO */
			case "registrarUsuario2" :
				$cadenaSql = " INSERT INTO ";
				$cadenaSql .= $prefijo . "usuario ";
				$cadenaSql .= " (";
				$cadenaSql .= " usuario,";
				$cadenaSql .= " nombre,";
				$cadenaSql .= " apellido,";
				$cadenaSql .= " correo,";
				$cadenaSql .= " telefono, ";
				$cadenaSql .= " imagen, ";
				$cadenaSql .= " clave, ";
				$cadenaSql .= " tipo,";
				$cadenaSql .= " rolmenu,";
				$cadenaSql .= " estado";
				$cadenaSql .= " )";
				$cadenaSql .= " VALUES";
				$cadenaSql .= " (";
				$cadenaSql .= " '" . $variable ['num_documento'] . "',";
				$cadenaSql .= " '" . $variable ['nombre'] . "',";
				$cadenaSql .= " '" . $variable ['apellido'] . "',";
				$cadenaSql .= " '" . $variable ['correo'] . "',";
				$cadenaSql .= " '" . $variable ['telefono'] . "',";
				$cadenaSql .= " '-',";
				$cadenaSql .= " '" . $variable ['contrasena'] . "',";
				$cadenaSql .= " '" . $variable ['tipo'] . "',";
				$cadenaSql .= " '" . $variable ['rolMenu'] . "',";
				$cadenaSql .= " '" . $variable ['estado'] . "'";
				$cadenaSql .= " );";
				break;
			
			/* REGISTRAR DATOS - USUARIO */
			case "insertarInformacionProveedor" :
				$cadenaSql = " INSERT INTO ";
				$cadenaSql .= " agora.informacion_proveedor ";
				$cadenaSql .= " (";
				$cadenaSql .= " tipopersona,";
				$cadenaSql .= " num_documento,";
				$cadenaSql .= " nom_proveedor,";
				$cadenaSql .= " id_ciudad_contacto,";
				$cadenaSql .= " direccion,";
				$cadenaSql .= " correo,";
				$cadenaSql .= " web,";
				$cadenaSql .= " nom_asesor,";
				$cadenaSql .= " tel_asesor,";
				$cadenaSql .= " descripcion,";
				$cadenaSql .= " anexorut,";
				$cadenaSql .= " anexorup,";
				$cadenaSql .= " tipo_cuenta_bancaria,";
				$cadenaSql .= " num_cuenta_bancaria,";
				$cadenaSql .= " id_entidad_bancaria,";
				$cadenaSql .= " fecha_registro,";
				$cadenaSql .= " fecha_ultima_modificacion,";
				$cadenaSql .= " estado";
				$cadenaSql .= " ) ";
				$cadenaSql .= " VALUES ";
				$cadenaSql .= " ( ";
				$cadenaSql .= " '" . $variable ['tipoPersona'] . "', ";
				$cadenaSql .= $variable ['numero_documento'] . ", ";
				$cadenaSql .= " '" . $variable ['nombre_proveedor'] . "', ";
				$cadenaSql .= $variable ['id_ciudad_contacto'] . ", ";
				$cadenaSql .= " '" . $variable ['direccion_contacto'] . "', ";
				$cadenaSql .= " '" . $variable ['correo_contacto'] . "', ";
				$cadenaSql .= " '" . $variable ['web_contacto'] . "', ";
				$cadenaSql .= " '" . $variable ['nom_asesor_comercial_contacto'] . "', ";
				$cadenaSql .= " '" . $variable ['tel_asesor_comercial_contacto'] . "', ";
				$cadenaSql .= " '" . $variable ['descripcion_proveedor'] . "', ";
				$cadenaSql .= " '" . $variable ['anexo_rut'] . "', ";
				$cadenaSql .= " '" . $variable ['anexo_rup'] . "', ";
				$cadenaSql .= " '" . $variable ['tipo_cuenta_bancaria'] . "', ";
				$cadenaSql .= " '" . $variable ['num_cuenta_bancaria'] . "', ";
				$cadenaSql .= $variable ['id_entidad_bancaria'] . ", ";
				$cadenaSql .= " '" . $variable ['fecha_registro'] . "', ";
				$cadenaSql .= " '" . $variable ['fecha_modificación'] . "', ";
				$cadenaSql .= $variable ['id_estado'] . " ";
				$cadenaSql .= " ) ";
				$cadenaSql .= "RETURNING  id_proveedor; ";
				break;
			
			/* INSERTAR - PROVEEEDOR DATOS TELEFONO */
			case 'insertarInformacionProveedorTelefono' :
				$cadenaSql = " INSERT INTO ";
				$cadenaSql .= " agora.telefono ";
				$cadenaSql .= " (";
				$cadenaSql .= " numero_tel,";
				if ($variable ['extension_telefono'] != null) {
					$cadenaSql .= " extension,";
				}
				$cadenaSql .= " tipo";
				$cadenaSql .= " ) ";
				$cadenaSql .= " VALUES ";
				$cadenaSql .= " ( ";
				$cadenaSql .= $variable ['num_telefono'] . ", ";
				if ($variable ['extension_telefono'] != null) {
					$cadenaSql .= $variable ['extension_telefono'] . ", ";
				}
				$cadenaSql .= $variable ['tipo'] . " ";
				$cadenaSql .= " ) ";
				$cadenaSql .= "RETURNING  id_telefono; ";
				break;
			
			/* INSERTAR - PROVEEEDOR DATOS X TELEFONO */
			case 'insertarInformacionProveedorXTelefono' :
				$cadenaSql = " INSERT INTO ";
				$cadenaSql .= " agora.proveedor_telefono ";
				$cadenaSql .= " (";
				$cadenaSql .= " id_proveedor,";
				$cadenaSql .= " id_telefono";
				$cadenaSql .= " ) ";
				$cadenaSql .= " VALUES ";
				$cadenaSql .= " ( ";
				$cadenaSql .= $variable ['fki_id_Proveedor'] . ", ";
				$cadenaSql .= $variable ['fki_id_tel'] . " ";
				$cadenaSql .= " ); ";
				break;
			
			/* REGISTRAR DATOS - USUARIO NATURAL */
			case "registrarProveedorNatural" :
				$cadenaSql = " INSERT INTO ";
				$cadenaSql .= " agora.informacion_persona_natural ";
				$cadenaSql .= " (";
				$cadenaSql .= " tipo_documento,";
				$cadenaSql .= " num_documento_persona,";
				$cadenaSql .= " digito_verificacion,";
				$cadenaSql .= " primer_apellido, ";
				$cadenaSql .= " segundo_apellido, ";
				$cadenaSql .= " primer_nombre,";
				$cadenaSql .= " segundo_nombre,";
				$cadenaSql .= " cargo,";
				$cadenaSql .= " id_pais_nacimiento,";
				$cadenaSql .= " perfil,";
				$cadenaSql .= " id_nucleo_basico,";
				$cadenaSql .= " profesion,";
				$cadenaSql .= " especialidad,";
				if ($variable ['monto_capital_autorizado'] != null) {
					$cadenaSql .= " monto_capital_autorizado,";
				}
				$cadenaSql .= " genero,";
				// **********************************************************************
				if ($variable ['grupoEtnico'] != null) {
					$cadenaSql .= " grupo_etnico,";
				}
				$cadenaSql .= " comunidad_lgbt,";
				$cadenaSql .= " cabeza_familia,";
				$cadenaSql .= " personas_a_cargo,";
				if ($variable ['numeroPersonasCargo'] != null) {
					$cadenaSql .= " numero_personas_a_cargo,";
				}
				$cadenaSql .= " estado_civil,";
				if ($variable ['tipoDiscapacidad'] != null) {
					$cadenaSql .= " tipo_discapacidad,";
				}
				$cadenaSql .= " discapacitado,";
				// ***********************************************************************
				$cadenaSql .= " medicina_prepagada,";
				if ($variable ['valor_uvt_prepagada'] != null) {
					$cadenaSql .= " valor_uvt_prepagada,";
				}
				$cadenaSql .= " cuenta_ahorro_afc,";
				if ($variable ['cuenta_ahorro_afc'] == 'TRUE') {
					$cadenaSql .= " num_cuenta_bancaria_afc,";
					$cadenaSql .= " id_entidad_bancaria_afc,";
					$cadenaSql .= " interes_vivienda_afc,";
				}
				if ($variable ['personasCargo'] == 'TRUE') {
					$cadenaSql .= " dependiente_hijo_menor_edad,";
					$cadenaSql .= " dependiente_hijo_menos23_estudiando,";
					$cadenaSql .= " dependiente_hijo_mas23_discapacitado,";
					$cadenaSql .= " dependiente_conyuge,";
					$cadenaSql .= " dependiente_padre_o_hermano,";
				}
				if ($variable ['id_eps'] != null && $variable ['id_fondo_pension'] != null) {
					$cadenaSql .= " id_eps,";
					$cadenaSql .= " id_fondo_pension,";
				}
				if ($variable ['id_caja_compensacion'] != null) {
					$cadenaSql .= " id_caja_compensacion,";
				}
				$cadenaSql .= " fecha_expedicion_documento,";
				$cadenaSql .= " id_ciudad_expedicion_documento,";
				$cadenaSql .= " declarante_renta";
				$cadenaSql .= " )";
				$cadenaSql .= " VALUES";
				$cadenaSql .= " (";
				$cadenaSql .= " " . $variable ['id_tipo_documento'] . ",";
				$cadenaSql .= " " . $variable ['fki_numero_documento'] . ",";
				$cadenaSql .= " " . $variable ['digito_verificacion'] . ",";
				$cadenaSql .= " '" . $variable ['primer_apellido'] . "',";
				$cadenaSql .= " '" . $variable ['segundo_apellido'] . "',";
				$cadenaSql .= " '" . $variable ['primer_nombre'] . "',";
				$cadenaSql .= " '" . $variable ['segundo_nombre'] . "',";
				$cadenaSql .= " '" . $variable ['cargo'] . "',";
				$cadenaSql .= " " . $variable ['id_pais_nacimiento'] . ",";
				$cadenaSql .= " " . $variable ['id_perfil'] . ",";
				$cadenaSql .= " " . $variable ['id_nucleo_basico'] . ",";
				$cadenaSql .= " '" . $variable ['profesion'] . "',";
				$cadenaSql .= " '" . $variable ['especialidad'] . "',";
				if ($variable ['monto_capital_autorizado'] != null) {
					$cadenaSql .= " " . $variable ['monto_capital_autorizado'] . ",";
				}
				$cadenaSql .= " '" . $variable ['genero'] . "',";
				
				if ($variable ['grupoEtnico'] != null) {
					$cadenaSql .= " '" . $variable ['grupoEtnico'] . "',";
				}
				$cadenaSql .= " " . $variable ['comunidadLGBT'] . ",";
				$cadenaSql .= " " . $variable ['cabezaFamilia'] . ",";
				$cadenaSql .= " " . $variable ['personasCargo'] . ",";
				if ($variable ['numeroPersonasCargo'] != null) {
					$cadenaSql .= " " . $variable ['numeroPersonasCargo'] . ",";
				}
				$cadenaSql .= " '" . $variable ['estadoCivil'] . "',";
				if ($variable ['tipoDiscapacidad'] != null) {
					$cadenaSql .= " '" . $variable ['tipoDiscapacidad'] . "',";
				}
				$cadenaSql .= " " . $variable ['discapacidad'] . ",";
				// **************************************************************************
				$cadenaSql .= " " . $variable ['medicina_prepagada'] . ",";
				if ($variable ['valor_uvt_prepagada'] != null) {
					$cadenaSql .= " " . $variable ['valor_uvt_prepagada'] . ",";
				}
				$cadenaSql .= " " . $variable ['cuenta_ahorro_afc'] . ",";
				if ($variable ['cuenta_ahorro_afc'] == 'TRUE') {
					$cadenaSql .= " '" . $variable ['num_cuenta_bancaria_afc'] . "',";
					$cadenaSql .= " " . $variable ['id_entidad_bancaria_afc'] . ",";
					$cadenaSql .= " " . $variable ['interes_vivienda_afc'] . ",";
				}
				if ($variable ['personasCargo'] == 'TRUE') {
					$cadenaSql .= " " . $variable ['dependiente_hijo_menor_edad'] . ",";
					$cadenaSql .= " " . $variable ['dependiente_hijo_menos23_estudiando'] . ",";
					$cadenaSql .= " " . $variable ['dependiente_hijo_mas23_discapacitado'] . ",";
					$cadenaSql .= " " . $variable ['dependiente_conyuge'] . ",";
					$cadenaSql .= " " . $variable ['dependiente_padre_o_hermano'] . ",";
				}
				if ($variable ['id_eps'] != null && $variable ['id_fondo_pension'] != null) {
					$cadenaSql .= " " . $variable ['id_eps'] . ",";
					$cadenaSql .= " " . $variable ['id_fondo_pension'] . ",";
				}
				if ($variable ['id_caja_compensacion'] != null) {
					$cadenaSql .= " " . $variable ['id_caja_compensacion'] . ",";
				}
				$cadenaSql .= " '" . $variable ['fecha_expedicion_doc'] . "',";
				$cadenaSql .= " " . $variable ['id_lugar_expedicion_doc'] . ",";
				$cadenaSql .= " " . $variable ['declarante_renta'] . "";
				$cadenaSql .= " ); ";
				break;
			
			case "consultar_proveedor" :
				$cadenaSql = " SELECT U.identificacion, U.tipo_identificacion FROM prov_usuario U";
				$cadenaSql .= " WHERE U.id_usuario = '" . $variable . "'";
				break;
			
			case "consultar_DatosProveedor" :
				$cadenaSql = " SELECT * FROM agora.informacion_proveedor ";
				$cadenaSql .= " WHERE num_documento = " . $variable . ";";
				break;
				
			case "consultarConsorciosUniones" :
				$cadenaSql=" SELECT ";
				$cadenaSql.=" string_agg(cast(id_proveedor_sociedad as text),',' ";
				$cadenaSql.=" ORDER BY ";
				$cadenaSql.=" id_proveedor_sociedad) ";
				$cadenaSql.=" FROM ";
				$cadenaSql.=" agora.informacion_sociedad_participante ";
				$cadenaSql.=" WHERE ";
				$cadenaSql.=" id_contratista = " . $variable;
				break;
				
			case "consultar_tipo_proveedor" :
				$cadenaSql = " SELECT P.tipoPersona FROM agora.informacion_proveedor P";
				$cadenaSql .= " WHERE P.num_documento = $variable ";
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
			
			/* CONSULTAR DATOS - USUARIO NATURAL */
			case "consultarProveedorNatural" :
				$cadenaSql = " SELECT ";
				$cadenaSql .= " tipo_documento,";
				$cadenaSql .= " num_documento_persona,";
				$cadenaSql .= " digito_verificacion,";
				$cadenaSql .= " primer_apellido, ";
				$cadenaSql .= " segundo_apellido, ";
				$cadenaSql .= " primer_nombre,";
				$cadenaSql .= " segundo_nombre,";
				$cadenaSql .= " cargo,";
				$cadenaSql .= " id_pais_nacimiento,";
				$cadenaSql .= " perfil,";
				$cadenaSql .= " id_nucleo_basico,";
				$cadenaSql .= " profesion,";
				$cadenaSql .= " especialidad,";
				$cadenaSql .= " monto_capital_autorizado,";
				$cadenaSql .= " genero,";
				$cadenaSql .= " grupo_etnico,";
				$cadenaSql .= " comunidad_lgbt,";
				$cadenaSql .= " cabeza_familia,";
				$cadenaSql .= " personas_a_cargo,";
				$cadenaSql .= " numero_personas_a_cargo,";
				$cadenaSql .= " estado_civil,";
				$cadenaSql .= " tipo_discapacidad,";
				$cadenaSql .= " discapacitado,";
				$cadenaSql .= " medicina_prepagada,"; // AGREGADO Benefi*************************************
				$cadenaSql .= " valor_uvt_prepagada,";
				$cadenaSql .= " cuenta_ahorro_afc,";
				$cadenaSql .= " num_cuenta_bancaria_afc,";
				$cadenaSql .= " id_entidad_bancaria_afc,";
				$cadenaSql .= " interes_vivienda_afc,";
				$cadenaSql .= " dependiente_hijo_menor_edad,";
				$cadenaSql .= " dependiente_hijo_menos23_estudiando,";
				$cadenaSql .= " dependiente_hijo_mas23_discapacitado,";
				$cadenaSql .= " dependiente_conyuge,";
				$cadenaSql .= " dependiente_padre_o_hermano,";
				$cadenaSql .= " id_eps,";
				$cadenaSql .= " id_fondo_pension,";
				$cadenaSql .= " id_caja_compensacion,";
				$cadenaSql .= " fecha_expedicion_documento,";
				$cadenaSql .= " id_ciudad_expedicion_documento,";
				$cadenaSql .= " declarante_renta";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.informacion_persona_natural";
				$cadenaSql .= " WHERE num_documento_persona = '" . $variable . "'";
				break;
			
			/* ACTUALIZAR DATOS - USUARIO */
			case "actualizarInformacionProveedor" :
				$cadenaSql = " UPDATE ";
				$cadenaSql .= " agora.informacion_proveedor ";
				$cadenaSql .= " SET";
				$cadenaSql .= " num_documento = " . $variable ['numero_documento'] . ", ";
				;
				$cadenaSql .= " nom_proveedor = " . " '" . $variable ['nombre_proveedor'] . "', ";
				$cadenaSql .= " id_ciudad_contacto = " . $variable ['id_ciudad_contacto'] . ", ";
				$cadenaSql .= " direccion = " . " '" . $variable ['direccion_contacto'] . "', ";
				$cadenaSql .= " correo = " . " '" . $variable ['correo_contacto'] . "', ";
				$cadenaSql .= " web = " . " '" . $variable ['web_contacto'] . "', ";
				$cadenaSql .= " nom_asesor = " . " '" . $variable ['nom_asesor_comercial_contacto'] . "', ";
				$cadenaSql .= " tel_asesor = " . " '" . $variable ['tel_asesor_comercial_contacto'] . "', ";
				$cadenaSql .= " descripcion = " . " '" . $variable ['descripcion_proveedor'] . "', ";
				$cadenaSql .= " tipo_cuenta_bancaria = " . " '" . $variable ['tipo_cuenta_bancaria'] . "', ";
				$cadenaSql .= " num_cuenta_bancaria = '" . $variable ['num_cuenta_bancaria'] . "', ";
				$cadenaSql .= " id_entidad_bancaria = " . $variable ['id_entidad_bancaria'] . ", ";
				$cadenaSql .= " fecha_ultima_modificacion = " . " '" . $variable ['fecha_modificación'] . "' ";
				$cadenaSql .= " WHERE id_proveedor = ";
				$cadenaSql .= "'" . $variable ['id_Proveedor'] . "' ";
				break;
			
			/* ACTUALIZAR - PROVEEEDOR DATOS TELEFONO */
			case 'actualizarInformacionProveedorTelefono' :
				$cadenaSql = " UPDATE ";
				$cadenaSql .= " agora.telefono ";
				$cadenaSql .= " SET";
				$cadenaSql .= " numero_tel = " . $variable ['num_telefono'] . ", ";
				if ($variable ['extension_telefono'] != null) {
					$cadenaSql .= " extension = " . $variable ['extension_telefono'] . ", ";
				}
				$cadenaSql .= " tipo = " . $variable ['tipo'] . " ";
				$cadenaSql .= " WHERE id_telefono = ";
				$cadenaSql .= "'" . $variable ['id_telefono'] . "' ";
				break;
			
			/* ACTUALIZAR DATOS - USUARIO NATURAL */
			case "actualizarProveedorNatural" :
				$cadenaSql = " UPDATE ";
				$cadenaSql .= " agora.informacion_persona_natural ";
				$cadenaSql .= " SET";
				$cadenaSql .= " tipo_documento = " . " " . $variable ['id_tipo_documento'] . ",";
				$cadenaSql .= " num_documento_persona = " . " " . $variable ['fki_numero_documento'] . ",";
				$cadenaSql .= " digito_verificacion = " . " " . $variable ['digito_verificacion'] . ",";
				$cadenaSql .= " primer_apellido = " . " '" . $variable ['primer_apellido'] . "',";
				$cadenaSql .= " segundo_apellido = " . " '" . $variable ['segundo_apellido'] . "',";
				$cadenaSql .= " primer_nombre = " . " '" . $variable ['primer_nombre'] . "',";
				$cadenaSql .= " segundo_nombre = " . " '" . $variable ['segundo_nombre'] . "',";
				$cadenaSql .= " cargo = " . " '" . $variable ['cargo'] . "',";
				$cadenaSql .= " id_pais_nacimiento = " . " " . $variable ['id_pais_nacimiento'] . ",";
				$cadenaSql .= " id_nucleo_basico = " . " " . $variable ['id_nucleo_basico'] . ",";
				$cadenaSql .= " perfil = " . " " . $variable ['id_perfil'] . ",";
				if ($variable ['id_perfil'] == 18 || $variable ['id_perfil'] == 22) {
					$cadenaSql .= " profesion = null,";
					$cadenaSql .= " especialidad = null,";
				} else if ($variable ['id_perfil'] == 19 || $variable ['id_perfil'] == 20) {
					$cadenaSql .= " profesion = " . " '" . $variable ['profesion'] . "',";
					$cadenaSql .= " especialidad = null,";
				} else {
					$cadenaSql .= " profesion = " . " '" . $variable ['profesion'] . "',";
					$cadenaSql .= " especialidad = " . " '" . $variable ['especialidad'] . "',";
				}
				
				if ($variable ['monto_capital_autorizado'] != null) {
					$cadenaSql .= " monto_capital_autorizado = " . " " . $variable ['monto_capital_autorizado'] . ",";
				}
				$cadenaSql .= " genero = " . " '" . $variable ['genero'] . "',";
				
				// *************************************************************
				if ($variable ['grupoEtnico'] != null) {
					$cadenaSql .= " grupo_etnico =" . " '" . $variable ['grupoEtnico'] . "',";
				}
				$cadenaSql .= " comunidad_lgbt =" . " " . $variable ['comunidadLGBT'] . ",";
				$cadenaSql .= " cabeza_familia =" . " " . $variable ['cabezaFamilia'] . ",";
				$cadenaSql .= " personas_a_cargo =" . " " . $variable ['personasCargo'] . ",";
				if ($variable ['personasCargo'] == 'TRUE') {
					$cadenaSql .= " numero_personas_a_cargo =" . " " . $variable ['numeroPersonasCargo'] . ",";
				} else {
					$cadenaSql .= " numero_personas_a_cargo = null,";
				}
				$cadenaSql .= " estado_civil =" . " '" . $variable ['estadoCivil'] . "',";
				if ($variable ['discapacidad'] == 'TRUE') {
					$cadenaSql .= " tipo_discapacidad =" . " '" . $variable ['tipoDiscapacidad'] . "',";
				} else {
					$cadenaSql .= " tipo_discapacidad = null,";
				}
				$cadenaSql .= " discapacitado =" . " " . $variable ['discapacidad'] . ",";
				// ************************************************************************************BENEFICIOS Tributar
				$cadenaSql .= " medicina_prepagada =" . " " . $variable ['medicina_prepagada'] . ",";
				if ($variable ['medicina_prepagada'] == 'TRUE') {
					$cadenaSql .= " valor_uvt_prepagada =" . " " . $variable ['valor_uvt_prepagada'] . ",";
				} else {
					$cadenaSql .= " valor_uvt_prepagada = null, ";
				}
				$cadenaSql .= " cuenta_ahorro_afc =" . " " . $variable ['cuenta_ahorro_afc'] . ",";
				if ($variable ['cuenta_ahorro_afc'] == 'TRUE') {
					$cadenaSql .= " num_cuenta_bancaria_afc =" . " '" . $variable ['num_cuenta_bancaria_afc'] . "',";
					$cadenaSql .= " id_entidad_bancaria_afc =" . " " . $variable ['id_entidad_bancaria_afc'] . ",";
					$cadenaSql .= " interes_vivienda_afc =" . " " . $variable ['interes_vivienda_afc'] . ",";
				} else {
					$cadenaSql .= " num_cuenta_bancaria_afc = null, ";
					$cadenaSql .= " id_entidad_bancaria_afc = null, ";
					$cadenaSql .= " interes_vivienda_afc = null, ";
				}
				if ($variable ['personasCargo'] == 'TRUE') {
					$cadenaSql .= " dependiente_hijo_menor_edad =" . " " . $variable ['dependiente_hijo_menor_edad'] . ",";
					$cadenaSql .= " dependiente_hijo_menos23_estudiando =" . " " . $variable ['dependiente_hijo_menos23_estudiando'] . ",";
					$cadenaSql .= " dependiente_hijo_mas23_discapacitado =" . " " . $variable ['dependiente_hijo_mas23_discapacitado'] . ",";
					$cadenaSql .= " dependiente_conyuge =" . " " . $variable ['dependiente_conyuge'] . ",";
					$cadenaSql .= " dependiente_padre_o_hermano =" . " " . $variable ['dependiente_padre_o_hermano'] . ",";
				} else {
					$cadenaSql .= " dependiente_hijo_menor_edad = FALSE, ";
					$cadenaSql .= " dependiente_hijo_menos23_estudiando = FALSE, ";
					$cadenaSql .= " dependiente_hijo_mas23_discapacitado = FALSE, ";
					$cadenaSql .= " dependiente_conyuge = FALSE, ";
					$cadenaSql .= " dependiente_padre_o_hermano = FALSE, ";
				}
				if ($variable ['id_eps'] != null && $variable ['id_fondo_pension'] != null) {
					$cadenaSql .= " id_eps = " . " " . $variable ['id_eps'] . ",";
					$cadenaSql .= " id_fondo_pension = " . " " . $variable ['id_fondo_pension'] . ",";
				}
				if ($variable ['id_caja_compensacion'] != null) {
					$cadenaSql .= " id_caja_compensacion = " . " " . $variable ['id_caja_compensacion'] . ",";
				} else {
					$cadenaSql .= " id_caja_compensacion = null,";
				}
				$cadenaSql .= " fecha_expedicion_documento = " . " '" . $variable ['fecha_expedicion_doc'] . "',";
				$cadenaSql .= " id_ciudad_expedicion_documento = " . " " . $variable ['id_lugar_expedicion_doc'] . ",";
				$cadenaSql .= " declarante_renta =" . " " . $variable ['declarante_renta'] . "";
				$cadenaSql .= " WHERE num_documento_persona = ";
				$cadenaSql .= "'" . $variable ['fki_numero_documento'] . "' ";
				break;
			
			/* INSERTAR - PROVEEEDOR DATOS X TELEFONO */
			case 'insertarInformacionProveedorXRepresentante' :
				$cadenaSql = " INSERT INTO ";
				$cadenaSql .= " agora.proveedor_representante_legal ";
				$cadenaSql .= " (";
				$cadenaSql .= " id_proveedor,";
				$cadenaSql .= " id_representante,";
				$cadenaSql .= " telefono_contacto,";
				$cadenaSql .= " correo_representante";
				$cadenaSql .= " ) ";
				$cadenaSql .= " VALUES ";
				$cadenaSql .= " ( ";
				$cadenaSql .= $variable ['fki_id_Proveedor'] . ", ";
				$cadenaSql .= $variable ['fki_id_Representante'] . ", ";
				$cadenaSql .= $variable ['tel_Repre'] . ", ";
				$cadenaSql .= " '" . $variable ['correo_Repre'] . "' ";
				$cadenaSql .= " ); ";
				break;
			
			/* REGISTRAR DATOS - USUARIO JURIDICA */
			case "registrarProveedorJuridica" :
				$cadenaSql = " INSERT INTO ";
				$cadenaSql .= " agora.informacion_persona_juridica ";
				$cadenaSql .= " (";
				$cadenaSql .= " num_nit_empresa, ";
				$cadenaSql .= " digito_verificacion, ";
				if ($variable ['procedencia_empresa'] == 'NACIONAL') {
					$cadenaSql .= " procedencia_empresa, ";
				} else {
					$cadenaSql .= " procedencia_empresa, ";
					$cadenaSql .= " id_ciudad_origen, ";
					$cadenaSql .= " codigo_pais_dian, ";
					if ($variable ['codigo_postal'] != null) {
						$cadenaSql .= " codigo_postal, ";
					}
					$cadenaSql .= " tipo_identificacion_extranjera, ";
					if ($variable ['tipo_identificacion_extranjera'] == 'PASAPORTE') {
						$cadenaSql .= " num_pasaporte, ";
					} else {
						$cadenaSql .= " num_cedula_extranjeria, ";
					}
				}
				$cadenaSql .= " id_tipo_conformacion, ";
				$cadenaSql .= " monto_capital_autorizado, ";
				$cadenaSql .= " exclusividad_producto, ";
				$cadenaSql .= " regimen_contributivo, ";
				$cadenaSql .= " pyme, ";
				$cadenaSql .= " registro_mercantil, ";
				$cadenaSql .= " sujeto_retencion, ";
				$cadenaSql .= " agente_retenedor, ";
				$cadenaSql .= " \"responsable_ICA\", ";
				$cadenaSql .= " \"responsable_IVA\", ";
				$cadenaSql .= " genero, ";
				$cadenaSql .= " nom_proveedor";
				$cadenaSql .= " )";
				$cadenaSql .= " VALUES";
				$cadenaSql .= " (";
				$cadenaSql .= " " . $variable ['fki_numero_documento'] . ",";
				$cadenaSql .= " " . $variable ['digito_verificacion'] . ",";
				if ($variable ['procedencia_empresa'] == 'NACIONAL') {
					$cadenaSql .= " '" . $variable ['procedencia_empresa'] . "',";
				} else {
					$cadenaSql .= " '" . $variable ['procedencia_empresa'] . "',";
					$cadenaSql .= " " . $variable ['id_ciudad_origen'] . ",";
					$cadenaSql .= " " . $variable ['codigo_pais_dian'] . ",";
					if ($variable ['codigo_postal'] != null) {
						$cadenaSql .= " " . $variable ['codigo_postal'] . ",";
					}
					$cadenaSql .= " '" . $variable ['tipo_identificacion_extranjera'] . "',";
					if ($variable ['tipo_identificacion_extranjera'] == 'PASAPORTE') {
						$cadenaSql .= " " . $variable ['num_pasaporte'] . ",";
					} else {
						$cadenaSql .= " " . $variable ['num_cedula_extranjeria'] . ",";
					}
				}
				$cadenaSql .= " " . $variable ['id_tipo_conformacion'] . ",";
				$cadenaSql .= " " . $variable ['monto_capital_autorizado'] . ",";
				$cadenaSql .= " " . $variable ['exclusividad_producto'] . ",";
				$cadenaSql .= " '" . $variable ['regimen_contributivo'] . "',";
				$cadenaSql .= " " . $variable ['pyme'] . ",";
				$cadenaSql .= " " . $variable ['registro_mercantil'] . ",";
				$cadenaSql .= " " . $variable ['sujeto_retencion'] . ",";
				$cadenaSql .= " " . $variable ['agente_retenedor'] . ",";
				$cadenaSql .= " " . $variable ['responsable_ICA'] . ",";
				$cadenaSql .= " " . $variable ['responsable_IVA'] . ",";
				$cadenaSql .= " '" . $variable ['genero'] . "',";
				$cadenaSql .= " '" . $variable ['nom_proveedor'] . "'";
				$cadenaSql .= " ); ";
				break;
			
			/* CONSULTAR - PROVEEEDOR DATOS X TELEFONO */
			case 'consultarInformacionProveedorXRepresentante' :
				$cadenaSql = " SELECT ";
				$cadenaSql .= " id_proveedor,";
				$cadenaSql .= " id_representante,";
				$cadenaSql .= " telefono_contacto,";
				$cadenaSql .= " correo_representante";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.proveedor_representante_legal ";
				$cadenaSql .= " WHERE id_proveedor = ";
				$cadenaSql .= "'" . $variable . "' ";
				break;
			
			case "consultarProveedorJuridica" :
				$cadenaSql = " SELECT ";
				$cadenaSql .= " num_nit_empresa, ";
				$cadenaSql .= " digito_verificacion, ";
				$cadenaSql .= " procedencia_empresa, ";
				$cadenaSql .= " id_ciudad_origen, ";
				$cadenaSql .= " codigo_pais_dian, ";
				$cadenaSql .= " codigo_postal, ";
				$cadenaSql .= " tipo_identificacion_extranjera, ";
				$cadenaSql .= " num_pasaporte, ";
				$cadenaSql .= " num_cedula_extranjeria, ";
				$cadenaSql .= " id_tipo_conformacion, ";
				$cadenaSql .= " monto_capital_autorizado, ";
				$cadenaSql .= " exclusividad_producto, ";
				$cadenaSql .= " regimen_contributivo, ";
				$cadenaSql .= " pyme, ";
				$cadenaSql .= " registro_mercantil, ";
				$cadenaSql .= " sujeto_retencion, ";
				$cadenaSql .= " agente_retenedor, ";
				$cadenaSql .= " \"responsable_ICA\", ";
				$cadenaSql .= " \"responsable_IVA\", ";
				$cadenaSql .= " genero, ";
				$cadenaSql .= " nom_proveedor";
				$cadenaSql .= " FROM";
				$cadenaSql .= " agora.informacion_persona_juridica ";
				$cadenaSql .= " WHERE num_nit_empresa = ";
				$cadenaSql .= "'" . $variable . "' ";
				break;
			
			/* ACTUALIZAR - PROVEEEDOR DATOS X TELEFONO */
			case 'actualizarInformacionProveedorXRepresentante' :
				$cadenaSql = " UPDATE ";
				$cadenaSql .= " agora.proveedor_representante_legal ";
				$cadenaSql .= " SET";
				$cadenaSql .= " telefono_contacto = " . $variable ['tel_Repre'] . ", ";
				$cadenaSql .= " correo_representante = " . " '" . $variable ['correo_Repre'] . "' ";
				$cadenaSql .= " WHERE id_proveedor = ";
				$cadenaSql .= "'" . $variable ['fki_id_Proveedor'] . "' AND ";
				$cadenaSql .= " id_representante = ";
				$cadenaSql .= "'" . $variable ['fki_id_Representante'] . "' ";
				break;
			
			/* ACTUALIZAR DATOS - USUARIO JURIDICA */
			case "actualizarProveedorJuridica" :
				$cadenaSql = " UPDATE ";
				$cadenaSql .= " agora.informacion_persona_juridica ";
				$cadenaSql .= " SET";
				$cadenaSql .= " num_nit_empresa = " . " " . $variable ['fki_numero_documento'] . ",";
				$cadenaSql .= " digito_verificacion = " . " " . $variable ['digito_verificacion'] . ",";
				if ($variable ['procedencia_empresa'] == 'NACIONAL') {
					$cadenaSql .= " procedencia_empresa = " . " '" . $variable ['procedencia_empresa'] . "',";
				} else {
					$cadenaSql .= " procedencia_empresa = " . " '" . $variable ['procedencia_empresa'] . "',";
					$cadenaSql .= " id_ciudad_origen = " . " " . $variable ['id_ciudad_origen'] . ",";
					$cadenaSql .= " codigo_pais_dian = " . " " . $variable ['codigo_pais_dian'] . ",";
					if ($variable ['codigo_postal'] != null) {
						$cadenaSql .= " codigo_postal = " . " " . $variable ['codigo_postal'] . ",";
					}
					$cadenaSql .= " tipo_identificacion_extranjera = " . " '" . $variable ['tipo_identificacion_extranjera'] . "',";
					if ($variable ['tipo_identificacion_extranjera'] == 'PASAPORTE') {
						$cadenaSql .= " num_pasaporte = " . " " . $variable ['num_pasaporte'] . ",";
					} else {
						$cadenaSql .= " num_cedula_extranjeria = " . " " . $variable ['num_cedula_extranjeria'] . ",";
					}
				}
				$cadenaSql .= " id_tipo_conformacion = " . " " . $variable ['id_tipo_conformacion'] . ",";
				$cadenaSql .= " monto_capital_autorizado = " . " " . $variable ['monto_capital_autorizado'] . ",";
				$cadenaSql .= " exclusividad_producto = " . " " . $variable ['exclusividad_producto'] . ",";
				$cadenaSql .= " regimen_contributivo = " . " '" . $variable ['regimen_contributivo'] . "',";
				$cadenaSql .= " pyme = " . " " . $variable ['pyme'] . ",";
				$cadenaSql .= " registro_mercantil = " . " " . $variable ['registro_mercantil'] . ",";
				$cadenaSql .= " sujeto_retencion = " . " " . $variable ['sujeto_retencion'] . ",";
				$cadenaSql .= " agente_retenedor = " . " " . $variable ['agente_retenedor'] . ",";
				$cadenaSql .= " \"responsable_ICA\" = " . " " . $variable ['responsable_ICA'] . ",";
				$cadenaSql .= " \"responsable_IVA\" = " . " " . $variable ['responsable_IVA'] . ",";
				$cadenaSql .= " genero = " . " '" . $variable ['genero'] . "',";
				$cadenaSql .= " nom_proveedor = " . " '" . $variable ['nom_proveedor'] . "'";
				$cadenaSql .= " WHERE num_nit_empresa = ";
				$cadenaSql .= "'" . $variable ['fki_numero_documento'] . "'; ";
				break;
			
			/* ACTUALIZAR - PROVEEEDOR DATOS */
			case 'actualizarRUT' :
				$cadenaSql = "UPDATE agora.informacion_proveedor SET ";
				$cadenaSql .= "anexorut='" . $variable ['destino'] . "'";
				$cadenaSql .= " WHERE id_proveedor = ";
				$cadenaSql .= "'" . $variable ['id_Proveedor'] . "' ";
				break;
				
				/* ACTUALIZAR - PROVEEEDOR DATOS */
			case 'actualizarRUP' :
				$cadenaSql = "UPDATE agora.informacion_proveedor SET ";
				$cadenaSql .= "anexorup='" . $variable ['destino2'] . "'";
				$cadenaSql .= " WHERE id_proveedor = ";
				$cadenaSql .= "'" . $variable ['id_Proveedor'] . "' ";
				break;
				
			/* VERIFICAR NUMERO DE NIT */
			case "verificarNIT" : // ******************************************************************************
				$cadenaSql = " SELECT";
				$cadenaSql .= " num_documento,";
				$cadenaSql .= " tipopersona,";
				$cadenaSql .= " direccion,";
				$cadenaSql .= " nom_proveedor,";
				$cadenaSql .= " correo,";
				$cadenaSql .= " id_proveedor,";
				$cadenaSql .= " estado";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.informacion_proveedor ";
				$cadenaSql .= " WHERE num_documento = " . $variable;
				break;
				
				/* DATOS DEL PROVEEDOR POR USUARIO */
				case "buscarProveedorByID" : // ****************************************************************************
					$cadenaSql = " SELECT";
					$cadenaSql .= " * ";
					$cadenaSql .= " FROM ";
					$cadenaSql .= " agora.informacion_proveedor ";
					$cadenaSql .= " WHERE id_proveedor = " . $variable . ";";
					break;
			
			/* DATOS DEL PROVEEDOR POR USUARIO */
			case "buscarProveedorByDocumento" : // ****************************************************************************
				$cadenaSql = " SELECT";
				$cadenaSql .= " P.id_proveedor,";
				$cadenaSql .= " P.num_documento,";
				$cadenaSql .= " P.tipopersona,";
				$cadenaSql .= " P.nom_proveedor,";
				$cadenaSql .= " P.id_ciudad_contacto,";
				$cadenaSql .= " P.direccion,";
				$cadenaSql .= " P.correo,";
				$cadenaSql .= " P.web,";
				$cadenaSql .= " P.nom_asesor,";
				$cadenaSql .= " P.tel_asesor,";
				$cadenaSql .= " P.tipo_cuenta_bancaria,";
				$cadenaSql .= " P.num_cuenta_bancaria,";
				$cadenaSql .= " P.id_entidad_bancaria,";
				$cadenaSql .= " P.descripcion,";
				$cadenaSql .= " P.anexorut,";
				$cadenaSql .= " P.anexorup,";
				$cadenaSql .= " P.fecha_registro,";
				$cadenaSql .= " P.fecha_ultima_modificacion,";
				$cadenaSql .= " P.estado";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.informacion_proveedor P";
				$cadenaSql .= " WHERE num_documento = '" . $variable . "'";
				break;
			
			/* DATOS BIENVENIDA PROVEEDOR */
			case "buscarProveedorLog" : // ******************************************************************+
				$cadenaSql = " SELECT";
				$cadenaSql .= " t1.nom_proveedor,";
				$cadenaSql .= " t1.num_documento";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.informacion_proveedor t1";
				$cadenaSql .= " WHERE t1.num_documento = " . $variable . ";";
				break;
			
			/* CONSULTAR CONTRATOS DEL PROVEEDOR */
			case "consultarContratos" : // ****************************************************************************
				$cadenaSql = " SELECT ";
				$cadenaSql .= " CP.id_contrato, ";
				$cadenaSql .= " CP.vigencia,";
				$cadenaSql .= " P.nom_proveedor,";
				$cadenaSql .= " P.num_documento,";
				$cadenaSql .= " C.id_objeto,";
				$cadenaSql .= " C.numero_contrato,";
				$cadenaSql .= " C.id_supervisor,";
				$cadenaSql .= " C.unidad_ejecutora,";
				$cadenaSql .= " C.estado";
				$cadenaSql .= " FROM agora.informacion_proveedor P";
				$cadenaSql .= " JOIN agora.contrato_proveedor CP ON CP.id_proveedor = P.id_proveedor";
				$cadenaSql .= " JOIN agora.contrato C ON C.id_contrato = CP.id_contrato";
				$cadenaSql .= " WHERE P.num_documento = '" . $variable . "'";
				break;
			
			case "listaContratoXNumContrato" :
				$cadenaSql = " SELECT ";
				$cadenaSql .= " CG.numero_contrato as numero_contrato,";
				$cadenaSql .= " CG.vigencia as vigencia, ";
				$cadenaSql .= " CG.unidad_ejecutora as unidad_ejecutora, ";
				$cadenaSql .= " upper(CG.objeto_contrato) as objeto_contrato,";
				$cadenaSql .= " cast(CG.plazo_ejecucion as text) || ' ' || UE.descripcion as plazo_ejecucion,";
				$cadenaSql .= " upper(FP.descripcion) as forma_pago,";
				$cadenaSql .= " OG.\"ORG_NOMBRE\" as nombre_ordenador_gasto,";
				$cadenaSql .= " OG.\"ORG_IDENTIFICACION\" as identificacion_ordenador_gasto,";
				$cadenaSql .= " OG.\"ORG_ORDENADOR_GASTO\" as cargo_ordenador_gasto,";
				$cadenaSql .= " OG.\"ORG_ESTADO\" as estado_ordenador_gasto,";
				$cadenaSql .= " CG.supervisor as identificacion_supervisor,";
				$cadenaSql .= " CG.numero_solicitud_necesidad as numero_solicitud_necesidad,";
				$cadenaSql .= " CG.numero_cdp as numero_cdp,";
				$cadenaSql .= " CG.resgistro_presupuestal as numero_rp,";
				$cadenaSql .= " CG.contratista as identificacion_contratista,";
				$cadenaSql .= " CG.convenio as convenio,";
				// $cadenaSql .= " CG.id_sociedad_temporal as identificacion_sociedad_temporal,";
				$cadenaSql .= " CG.valor_contrato as valor_contrato,";
				$cadenaSql .= " upper(CG.justificacion) as justificacion,";
				$cadenaSql .= " upper(CG.condiciones) as condiciones,";
				$cadenaSql .= " upper(CG.descripcion_forma_pago) as descripcion_forma_pago,";
				$cadenaSql .= " CG.fecha_registro as fecha_registro,";
				$cadenaSql .= " CC.descripcion as clase_contratista, ";
				$cadenaSql .= " upper(TC.descripcion) as tipo_control";
				$cadenaSql .= " FROM argo.contrato_general CG";
				$cadenaSql .= " JOIN argo.parametros UE ON UE.id_parametro = CG.unidad_ejecucion";
				$cadenaSql .= " JOIN argo.parametros FP ON FP.id_parametro = CG.forma_pago";
				$cadenaSql .= " JOIN argo.argo_ordenadores OG ON OG.\"ORG_IDENTIFICADOR_UNICO\" = CG.ordenador_gasto";
				$cadenaSql .= " JOIN argo.parametros TC ON TC.id_parametro = CG.tipo_control";
				$cadenaSql .= " JOIN argo.parametros CC ON CC.id_parametro = CG.clase_contratista";
				$cadenaSql .= " WHERE CG.vigencia = " . $variable ['vigencia'];
				$cadenaSql .= " AND CG.numero_contrato = '" . $variable ['num_contrato'] . "';";
				break;
			
			/* VERIFICAR NUMERO DE NIT */
			case "verificarProveedor" :
				$cadenaSql = " SELECT";
				$cadenaSql .= " num_documento,";
				$cadenaSql .= " nom_proveedor";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.informacion_proveedor ";
				$cadenaSql .= " WHERE num_documento = '" . $variable . "'";
				break;
			
			// ********************************************************************************
			
			// CONTRATOS++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			case "consultarEstadoContrato" :
				$cadenaSql = " SELECT";
				$cadenaSql .= " EST.nombre_estado as estado ";
				$cadenaSql .= " FROM argo.contrato_estado CE";
				$cadenaSql .= " JOIN argo.estado_contrato EST ON EST.id = CE.estado ";
				$cadenaSql .= " WHERE ";
				$cadenaSql .= " CE.fecha_registro = (";
				$cadenaSql .= " 	SELECT ";
				$cadenaSql .= " 	MAX(fecha_registro) ";
				$cadenaSql .= " 	FROM argo.contrato_estado ";
				$cadenaSql .= " WHERE fecha_registro <= now() ";
				$cadenaSql .= " AND vigencia = " . $variable ['vigencia'] . "";
				$cadenaSql .= " AND numero_contrato = '" . $variable ['num_contrato'] . "');";
				break;
			
			case "consultarNovedadesContrato" :
				$cadenaSql = "SELECT ";
				$cadenaSql .= " NC.id as id_novedad,";
				$cadenaSql .= " P.descripcion as tipo_novedad,";
				$cadenaSql .= " NC.numero_contrato as numero_contrato,";
				$cadenaSql .= " NC.vigencia as vigencia,";
				$cadenaSql .= " NC.estado as estado,";
				$cadenaSql .= " NC.fecha_registro as fecha_registro,";
				$cadenaSql .= " NC.usuario as usuario,";
				$cadenaSql .= " NC.acto_administrativo as acto_administrativo,";
				$cadenaSql .= " NC.descripcion as descripcion ";
				$cadenaSql .= " FROM argo.novedad_contractual NC";
				$cadenaSql .= " JOIN argo.parametros P ON P.id_parametro = NC.tipo_novedad";
				$cadenaSql .= " WHERE ";
				$cadenaSql .= " vigencia = " . $variable ['vigencia'] . "";
				$cadenaSql .= " AND numero_contrato = '" . $variable ['num_contrato'] . "';";
				break;
			
			case "consultarActaInicio" :
				$cadenaSql = "SELECT *";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " argo.acta_inicio";
				$cadenaSql .= " WHERE ";
				$cadenaSql .= " vigencia = " . $variable ['vigencia'] . "";
				$cadenaSql .= " AND numero_contrato = '" . $variable ['num_contrato'] . "';";
				break;
			
			case "listaContratoXNumContrato" :
				$cadenaSql = " SELECT ";
				$cadenaSql .= " CG.numero_contrato as numero_contrato,";
				$cadenaSql .= " CG.vigencia as vigencia, ";
				$cadenaSql .= " CG.unidad_ejecutora as unidad_ejecutora, ";
				$cadenaSql .= " upper(CG.objeto_contrato) as objeto_contrato,";
				$cadenaSql .= " cast(CG.plazo_ejecucion as text) || ' ' || UE.descripcion as plazo_ejecucion,";
				$cadenaSql .= " upper(FP.descripcion) as forma_pago,";
				$cadenaSql .= " OG.\"ORG_NOMBRE\" as nombre_ordenador_gasto,";
				$cadenaSql .= " OG.\"ORG_IDENTIFICACION\" as identificacion_ordenador_gasto,";
				$cadenaSql .= " OG.\"ORG_ORDENADOR_GASTO\" as cargo_ordenador_gasto,";
				$cadenaSql .= " OG.\"ORG_ESTADO\" as estado_ordenador_gasto,";
				$cadenaSql .= " CG.supervisor as identificacion_supervisor,";
				$cadenaSql .= " CG.numero_solicitud_necesidad as numero_solicitud_necesidad,";
				$cadenaSql .= " CG.numero_cdp as numero_cdp,";
				$cadenaSql .= " CG.resgistro_presupuestal as numero_rp,";
				$cadenaSql .= " CG.contratista as identificacion_contratista,";
				$cadenaSql .= " CG.convenio as convenio,";
				// $cadenaSql .= " CG.id_sociedad_temporal as identificacion_sociedad_temporal,";
				$cadenaSql .= " CG.valor_contrato as valor_contrato,";
				$cadenaSql .= " upper(CG.justificacion) as justificacion,";
				$cadenaSql .= " upper(CG.condiciones) as condiciones,";
				$cadenaSql .= " upper(CG.descripcion_forma_pago) as descripcion_forma_pago,";
				$cadenaSql .= " CG.fecha_registro as fecha_registro,";
				$cadenaSql .= " CC.descripcion as clase_contratista, ";
				$cadenaSql .= " upper(TC.descripcion) as tipo_control";
				$cadenaSql .= " FROM argo.contrato_general CG";
				$cadenaSql .= " JOIN argo.parametros UE ON UE.id_parametro = CG.unidad_ejecucion";
				$cadenaSql .= " JOIN argo.parametros FP ON FP.id_parametro = CG.forma_pago";
				$cadenaSql .= " JOIN argo.argo_ordenadores OG ON OG.\"ORG_IDENTIFICADOR_UNICO\" = CG.ordenador_gasto";
				$cadenaSql .= " JOIN argo.parametros TC ON TC.id_parametro = CG.tipo_control";
				$cadenaSql .= " JOIN argo.parametros CC ON CC.id_parametro = CG.clase_contratista";
				$cadenaSql .= " WHERE CG.vigencia = " . $variable ['vigencia'];
				$cadenaSql .= " AND CG.numero_contrato = '" . $variable ['num_contrato'] . "';";
				break;
			
			case "estadoContratoAgora" :
				$cadenaSql = " SELECT ";
				$cadenaSql .= " estado ";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.contrato ";
				$cadenaSql .= " WHERE numero_contrato = " . $variable ['num_contrato'];
				$cadenaSql .= " AND vigencia = " . $variable ['vigencia'];
				$cadenaSql .= " AND unidad_ejecutora = " . $variable ['unidad_ejecutora'];
				break;
			
			// CONTRATOS++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			
			case 'buscarNomenclaturaAbreviatura' :
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_nomenclatura as ID_NOMENCLATURA, ';
				$cadenaSql .= 'abreviatura as ABREVIATURA ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'agora.parametro_nomenclatura_dian ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_nomenclatura = ' . $variable . ' ';
				$cadenaSql .= 'ORDER BY NOMENCLATURA';
				break;
			
			case 'buscarNomenclaturas' :
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_nomenclatura as ID_NOMENCLATURA, ';
				$cadenaSql .= 'nomenclatura as NOMENCLATURA ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'agora.parametro_nomenclatura_dian ';
				$cadenaSql .= 'ORDER BY NOMENCLATURA';
				break;
			
			case 'consultarPaises' :
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_pais as ID_PAIS, ';
				$cadenaSql .= 'nombre_pais as NOMBRE, ';
				$cadenaSql .= 'codigo_pais as COD_PAIS ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'core.pais ';
				$cadenaSql .= 'ORDER BY NOMBRE';
				break;
			
			case "consultarBanco" :
				$cadenaSql = "SELECT";
				$cadenaSql .= " id_codigo,";
				$cadenaSql .= "	nombre_banco";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " core.banco";
				$cadenaSql .= " WHERE estado_activo != 'FALSE' ";
				$cadenaSql .= " ORDER BY nombre_banco";
				break;
			
			case "consultarConformacion" :
				$cadenaSql = "SELECT";
				$cadenaSql .= " id_conformacion,";
				$cadenaSql .= "	nombre";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.parametro_tipo_conformacion";
				$cadenaSql .= " WHERE estado != 'INACTIVO' ";
				$cadenaSql .= " ORDER BY nombre";
				break;
			
			/* CIIU */
			case "ciiuDivision" :
				$cadenaSql = "SELECT";
				$cadenaSql .= " id_division AS id_division,";
				$cadenaSql .= "	id_division||' - ('||nombre||')' AS  nombre";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " core.ciiu_division";
				$cadenaSql .= " ORDER BY id_division";
				break;
			
			case "ciiuGrupo" :
				$cadenaSql = "SELECT";
				$cadenaSql .= " id_clase AS id_clase,";
				$cadenaSql .= "	id_clase||' - ('||nombre||')' AS  nombre";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " core.ciiu_clase";
				$cadenaSql .= " WHERE (division)::int ='" . $variable . "'";
				$cadenaSql .= " ORDER BY id_clase";
				break;
			
			case "ciiuClase" :
				$cadenaSql = "SELECT";
				$cadenaSql .= " id_subclase AS id_subclase,";
				$cadenaSql .= "	id_subclase||' - ('||nombre||')' AS  nombre";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " core.ciiu_subclase";
				$cadenaSql .= " WHERE (clase)::int ='" . $variable . "'";
				$cadenaSql .= " ORDER BY id_subclase";
				break;
			
			case 'buscarPais' :
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_pais as ID_PAIS, ';
				$cadenaSql .= 'nombre_pais as NOMBRE, ';
				$cadenaSql .= 'codigo_pais as COD_PAIS ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'core.pais ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_pais != 112 ';
				$cadenaSql .= 'ORDER BY NOMBRE';
				break;
				
				
				case 'buscarPaises' :
						
					$cadenaSql = 'SELECT ';
					$cadenaSql .= 'id_pais as ID_PAIS, ';
					$cadenaSql .= 'nombre_pais as NOMBRE, ';
					$cadenaSql .= 'codigo_pais as COD_PAIS ';
					$cadenaSql .= 'FROM ';
					$cadenaSql .= 'core.pais ';
					$cadenaSql .= 'ORDER BY NOMBRE';
					break;
				
			
			case 'buscarPaisXDepa' :
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_pais as ID_PAIS ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'core.departamento ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_departamento = ' . $variable . ' ;';
				break;
			
			case 'buscarPaisCod' :
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'codigo_pais as COD_PAIS ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'core.pais ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_pais = ' . $variable . ' ;';
				break;
			
			case 'buscarCiudad' :
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_ciudad as ID_CIUDAD, ';
				$cadenaSql .= 'nombre as NOMBRE, ';
				$cadenaSql .= 'id_departamento as ID_DEPARTAMENTO ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'core.ciudad ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_ciudad = ' . $variable . ' ';
				$cadenaSql .= 'ORDER BY NOMBRE;';
				break;
			
			case 'buscarDepartamento' : // Solo Departamentos de Colombia
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_departamento as ID_DEPARTAMENTO, ';
				$cadenaSql .= 'nombre as NOMBRE ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'core.departamento ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_pais = 112 ';
				$cadenaSql .= 'ORDER BY NOMBRE';
				break;
			
			case 'buscarDepartamentoAjax' :
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_departamento as ID_DEPARTAMENTO, ';
				$cadenaSql .= 'nombre as NOMBRE ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'core.departamento ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_pais = ' . $variable . ' ';
				$cadenaSql .= 'ORDER BY NOMBRE';
				break;
			
			case 'buscarCiudadAjax' :
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_ciudad as ID_CIUDAD, ';
				$cadenaSql .= 'nombre as NOMBRECIUDAD ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'core.ciudad ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_departamento = ' . $variable . ' ';
				$cadenaSql .= 'ORDER BY NOMBRE';
				break;
			
			case 'consultarTipoDocumento' :
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_parametro as ID_PARAMETRO, ';
				$cadenaSql .= 'INITCAP(LOWER(valor_parametro)) as VALOR_TIPO ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'agora.parametro_estandar ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'valor_parametro != \'NIT\' ';
				$cadenaSql .= 'AND clase_parametro = \'Tipo Documento\';';
				break;
			
			case 'consultarGrupoEtnico' :
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_parametro as ID_PARAMETRO, ';
				$cadenaSql .= 'INITCAP(LOWER(valor_parametro)) as VALOR_TIPO ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'agora.parametro_estandar ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'clase_parametro = \'Tipo Etnia\';';
				break;
			
			case 'consultarTipoEstadoCivil' :
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_parametro as ID_PARAMETRO, ';
				$cadenaSql .= 'INITCAP(LOWER(valor_parametro)) as VALOR_TIPO ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'agora.parametro_estandar ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'clase_parametro = \'Estado Civil\';';
				break;
			
			case 'consultarTipoDiscapacidad' :
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_parametro as ID_PARAMETRO, ';
				$cadenaSql .= 'INITCAP(LOWER(valor_parametro)) as VALOR_TIPO ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'agora.parametro_estandar ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'clase_parametro = \'Tipo Discapacidad\';';
				break;
			
			case "consultarUsuarios" :
				
				$cadenaSql = "SELECT DISTINCT ";
				$cadenaSql .= " usu.id_usuario, ";
				$cadenaSql .= "usu.nombre, ";
				$cadenaSql .= "usu.apellido, ";
				$cadenaSql .= " usu.correo, ";
				$cadenaSql .= " usu.telefono, ";
				$cadenaSql .= " usu.tipo ,";
				$cadenaSql .= " (CASE WHEN usu.tipo='0' THEN 'Anonimo' ELSE 'Conocido' END) nivel, ";
				$cadenaSql .= " est.estado_registro_alias estado, ";
				$cadenaSql .= " usu.identificacion, ";
				$cadenaSql .= " usu.tipo_identificacion, ";
				$cadenaSql .= " tiden.tipo_nombre, ";
				$cadenaSql .= " usu.fecha_registro  ";
				$cadenaSql .= "FROM " . $prefijo . "usuario usu ";
				$cadenaSql .= "INNER JOIN " . $prefijo . "estado_registro est ";
				$cadenaSql .= "ON est.estado_registro_id=usu.estado ";
				$cadenaSql .= "INNER JOIN " . $prefijo . "tipo_identificacion tiden ";
				$cadenaSql .= "ON tiden.tipo_identificacion=usu.tipo_identificacion ";
				
				if (isset ( $variable ['tipoAdm'] ) && $variable ['tipoAdm'] == 'subsistema') {
					$cadenaSql .= "INNER JOIN " . $prefijo . "usuario_subsistema mod ON mod.id_usuario=usu.id_usuario ";
					$cadenaSql .= " AND mod.rol_id NOT IN (0) ";
				}
				if (isset ( $variable ['identificacion'] ) && $variable ['identificacion'] > 0) {
					$cadenaSql .= " WHERE ";
					$cadenaSql .= " usu.identificacion='" . $variable ['identificacion'] . "'";
					$cadenaSql .= " AND usu.tipo_identificacion='" . $variable ['tipo_identificacion'] . "'";
				} elseif (isset ( $variable ['id_usuario'] ) && $variable ['id_usuario'] != '') {
					$cadenaSql .= " WHERE ";
					$cadenaSql .= " usu.id_usuario='" . $variable ['id_usuario'] . "'";
				}
				$cadenaSql .= " ORDER BY id_usuario";
				break;
			
			case "consultarPerfilUsuario" :
				
				$cadenaSql = "SELECT DISTINCT ";
				$cadenaSql .= " sist.id_usuario,  ";
				if (! isset ( $variable ['tipo'] )) {
					$cadenaSql .= "sist.id_subsistema, ";
					$cadenaSql .= "mod.etiketa subsistema, ";
					$cadenaSql .= "sist.fecha_registro,  ";
					$cadenaSql .= "sist.fecha_caduca,  ";
				}
				// $cadenaSql .= "sist.id_subsistema, ";
				// $cadenaSql .= "mod.etiketa subsistema, ";
				$cadenaSql .= "sist.rol_id, ";
				$cadenaSql .= "rol.rol_alias , ";
				// $cadenaSql .= "sist.fecha_registro, ";
				// $cadenaSql .= "sist.fecha_caduca, ";
				$cadenaSql .= "est.estado_registro_alias estado  ";
				$cadenaSql .= "FROM " . $prefijo . "usuario_subsistema sist ";
				$cadenaSql .= "INNER JOIN " . $prefijo . "subsistema mod ON mod.id_subsistema=sist.id_subsistema ";
				$cadenaSql .= "INNER JOIN " . $prefijo . "rol rol ON rol.rol_id=sist.rol_id ";
				$cadenaSql .= "INNER JOIN " . $prefijo . "estado_registro est ";
				$cadenaSql .= "ON est.estado_registro_id=sist.estado ";
				$cadenaSql .= "WHERE sist.id_usuario='" . $variable ['id_usuario'] . "'";
				if (isset ( $variable ['subsistema'] ) && $variable ['subsistema'] > 0) {
					$cadenaSql .= " AND ";
					$cadenaSql .= " sist.id_subsistema='" . $variable ['subsistema'] . "' ";
				}
				if (isset ( $variable ['rol_id'] )) {
					$cadenaSql .= " AND rol.rol_id ='" . $variable ['rol_id'] . "'";
				}
				if (isset ( $variable ['tipo'] ) && $variable ['tipo'] == 'unico') {
					$cadenaSql .= " AND sist.estado=1 ";
				}
				$cadenaSql .= " ORDER BY rol.rol_alias";
				break;
			
			case "consultarUsuariosEditar" :
				
				$cadenaSql = "SELECT id_usuario, nombre, apellido, correo, telefono, tipo, identificacion ";
				$cadenaSql .= "FROM " . $prefijo . "usuario ";
				$cadenaSql .= " WHERE  id_usuario = '" . $variable . "'";
				break;
			
			case "consultarLogUsuario" :
				$cadenaSql = "SELECT DISTINCT id_usuario ";
				$cadenaSql .= "FROM " . $prefijo . "log_usuario ";
				$cadenaSql .= " WHERE  id_usuario = '" . $variable ['id_usuario'] . "'";
				
				break;
			
			case "tipoIdentificacion" :
				$cadenaSql = "SELECT   tipo_identificacion,  tipo_nombre ";
				$cadenaSql .= "FROM " . $prefijo . "tipo_identificacion ";
				$cadenaSql .= " WHERE  tipo_estado = 1";
				$cadenaSql .= " ORDER BY tipo_nombre ASC";
				break;
			
			case "tipoUsuario" :
				$cadenaSql = "SELECT  idtipo, descripcion ";
				$cadenaSql .= "FROM " . $prefijo . "tipousuario ";
				$cadenaSql .= " WHERE  idtipo != 1";
				break;
			
			case "subsistema" :
				$tam = count ( $variable );
				$aux = 1;
				$cadenaSql = "SELECT  id_subsistema, etiketa ";
				$cadenaSql .= "FROM " . $prefijo . "subsistema ";
				$cadenaSql .= "WHERE  ";
				$cadenaSql .= " id_subsistema ";
				if ($tam > 1) {
					$cadenaSql .= " IN ( ";
					foreach ( $variable as $value ) {
						$cadenaSql .= $value;
						$aux < $tam ? $cadenaSql .= ',' : '';
						$aux ++;
					}
					$cadenaSql .= " ) ";
				} elseif ($tam == 1 && $variable [0] == 1) {
					$cadenaSql .= " > 0 ";
				} else {
					$cadenaSql .= " = " . $variable [0];
				}
				$cadenaSql .= " ORDER BY  etiketa ";
				
				break;
			
			case "consultaPerfiles" :
				$cadenaSql = " SELECT DISTINCT rol.\"rol_id\", ";
				$cadenaSql .= " rol.\"rol_alias\" ";
				$cadenaSql .= "  FROM " . $prefijo . "rol rol ";
				$cadenaSql .= "INNER JOIN  " . $prefijo . "rol_subsistema sub  ";
				$cadenaSql .= "ON rol.\"rol_id\"=sub.\"rol_id\"  ";
				$cadenaSql .= "AND rol.estado_registro_id='1' ";
				if (isset ( $variable ['subsistema'] ) && $variable ['subsistema'] > 0) {
					$cadenaSql .= " WHERE ";
					$cadenaSql .= " sub.id_subsistema='" . $variable ['subsistema'] . "' ";
					if (isset ( $variable ['roles'] )) {
						$cadenaSql .= " AND rol.rol_id NOT IN (" . $variable ['roles'] . ")";
					}
				}
				break;
			
			case "insertarUsuario" :
				
				$cadenaSql = "INSERT INTO " . $prefijo . "usuario(id_usuario, nombre, apellido, correo, telefono, imagen, clave, tipo, estilo, idioma, estado, fecha_registro, identificacion,tipo_identificacion) ";
				$cadenaSql .= " VALUES ( ";
				$cadenaSql .= " '" . $variable ['id_usuario'] . "', ";
				$cadenaSql .= " '" . $variable ['nombres'] . "', ";
				$cadenaSql .= " '" . $variable ['apellidos'] . "', ";
				$cadenaSql .= " '" . $variable ['correo'] . "', ";
				$cadenaSql .= " '" . $variable ['telefono'] . "', ";
				$cadenaSql .= " 'N/A', ";
				$cadenaSql .= " '" . $variable ['password'] . "', ";
				$cadenaSql .= " '1', ";
				$cadenaSql .= " 'basico', ";
				$cadenaSql .= " 'es_es', ";
				$cadenaSql .= " 2, ";
				$cadenaSql .= " '" . $variable ['fechaIni'] . "', ";
				$cadenaSql .= " " . $variable ['identificacion'] . ", ";
				$cadenaSql .= " '" . $variable ['tipo_identificacion'] . "' ";
				$cadenaSql .= " )";
				
				break;
			
			case "insertarPerfilUsuario" :
				
				$cadenaSql = "INSERT INTO " . $prefijo . "usuario_subsistema(id_usuario, id_subsistema, rol_id, fecha_registro, fecha_caduca, estado) ";
				$cadenaSql .= " VALUES ( ";
				$cadenaSql .= " '" . $variable ['id_usuario'] . "', ";
				$cadenaSql .= " '" . $variable ['subsistema'] . "', ";
				$cadenaSql .= " '" . $variable ['perfil'] . "', ";
				$cadenaSql .= " '" . $variable ['fechaIni'] . "', ";
				$cadenaSql .= " '" . $variable ['fechaFin'] . "', ";
				$cadenaSql .= " '1'";
				$cadenaSql .= " )";
				
				break;
			
			case "actualizarUsuario" :
				
				$cadenaSql = "UPDATE " . $prefijo . "usuario SET ";
				$cadenaSql .= " nombre = '" . $variable ['nombres'] . "', ";
				$cadenaSql .= " apellido = '" . $variable ['apellidos'] . "', ";
				$cadenaSql .= " correo = '" . $variable ['correo'] . "', ";
				$cadenaSql .= " telefono = '" . $variable ['telefono'] . "' ";
				$cadenaSql .= " WHERE id_usuario = '" . $variable ['id_usuario'] . "' ";
				break;
			
			case "CambiarEstadoUsuario" :
				
				$cadenaSql = "UPDATE " . $prefijo . "usuario SET ";
				$cadenaSql .= " estado = '" . $variable ['estado'] . "'";
				$cadenaSql .= " WHERE id_usuario = '" . $variable ['usuario'] . "' ";
				break;
			
			case "editarPerfilUsuario" :
				
				$cadenaSql = "UPDATE " . $prefijo . "usuario_subsistema SET ";
				$cadenaSql .= " estado = '" . $variable ['estado'] . "' , ";
				$cadenaSql .= " fecha_caduca = '" . $variable ['fechaFin'] . "'";
				$cadenaSql .= " WHERE id_usuario = '" . $variable ['id_usuario'] . "' ";
				$cadenaSql .= " AND id_subsistema = '" . $variable ['id_subsistema'] . "' ";
				$cadenaSql .= " AND rol_id = '" . $variable ['rol_id'] . "' ";
				break;
			
			case "CambiarEstadoPerfil" :
				
				$cadenaSql = "UPDATE " . $prefijo . "usuario_subsistema SET ";
				$cadenaSql .= " estado = '" . $variable ['estado'] . "'";
				$cadenaSql .= " WHERE id_usuario = '" . $variable ['id_usuario'] . "' ";
				$cadenaSql .= " AND id_subsistema = '" . $variable ['id_subsistema'] . "' ";
				$cadenaSql .= " AND rol_id = '" . $variable ['rol_id'] . "' ";
				break;
			
			case "borrarPerfil" :
				$cadenaSql = "DELETE FROM " . $prefijo . "usuario_subsistema ";
				$cadenaSql .= " WHERE id_usuario = '" . $variable ['id_usuario'] . "' ";
				break;
			
			case "borrarUsuario" :
				$cadenaSql = "DELETE FROM " . $prefijo . "usuario ";
				$cadenaSql .= " WHERE id_usuario = '" . $variable ['id_usuario'] . "' ";
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

                foreach ($_REQUEST as $clave => $valor) {
                    $cadenaSql .= "( ";
                    $cadenaSql .= "'" . $idSesion . "', ";
                    $cadenaSql .= "'" . $variable ['formulario'] . "', ";
                    $cadenaSql .= "'" . $clave . "', ";
                    $cadenaSql .= "'" . $valor . "', ";
                    $cadenaSql .= "'" . $variable ['fecha'] . "' ";
                    $cadenaSql .= "),";
                }

                $cadenaSql = substr($cadenaSql, 0, (strlen($cadenaSql) - 1));
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

            /**
             * 
             */
		}
		
		return $cadenaSql;
	}
}

?>
