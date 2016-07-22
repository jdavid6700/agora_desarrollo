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
				
			/* CONSULTAR - EVALUACION POR ID CONTRATO */
			case "evalaucionByIdContrato" :
				$cadenaSql = "SELECT ";
				$cadenaSql .= " puntaje_total";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.evaluacion ";
				$cadenaSql .= " WHERE id_contrato= '" . $variable . "'";
				break;
			
			/* CONSULTAR - CONTRATO por ID */
			case "consultarContratoByID" ://***********************************************************************
				$cadenaSql = "SELECT  ";
				$cadenaSql .= " id_contrato, ";
				$cadenaSql .= " numero_contrato, ";
				$cadenaSql .= " fecha_inicio, ";
				$cadenaSql .= " fecha_finalizacion, ";
				$cadenaSql .= " valor, ";
				$cadenaSql .= " vigencia, ";
				$cadenaSql .= " P.nom_proveedor, ";
				$cadenaSql .= " P.num_documento, ";
				$cadenaSql .= " O.objetocontratar, ";
				$cadenaSql .= " O.descripcion, ";
				$cadenaSql .= " C.numero_cdp, ";
				$cadenaSql .= " C.numero_rp, ";
				$cadenaSql .= " C.fecha_rp ";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.contrato C";
				$cadenaSql .= " JOIN agora.objeto_contratar O ON O.id_objeto = C.id_objeto";
				$cadenaSql .= " JOIN agora.informacion_proveedor P ON P.id_proveedor = C.id_proveedor";
				$cadenaSql .= " WHERE  id_contrato=" . $variable;
				break;
			
			/* ACTUALIZAR - ESTADO PROVEEDOR */
			case 'updateEstado' ://****************************************************************************************
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
			case "registrarActividad" ://**********************************************************
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
			case "verificarActividad" ://******************************************************************
				$cadenaSql = " SELECT";
				$cadenaSql .= " num_documento";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.proveedor_actividad_ciiu ";
				$cadenaSql .= " WHERE num_documento= '" . $variable ['nit'] . "'";
				$cadenaSql .= " AND id_subclase = '" . $variable ['actividad'] . "'";
				break;
			
			/* CONSULTAR ACTIVIDADES DEL PROVEEDOR */
			case "consultarActividades" ://********************************************************************
				$cadenaSql = " SELECT";
				$cadenaSql .= " A.id_subclase,";
				$cadenaSql .= " nombre";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.proveedor_actividad_ciiu A";
				$cadenaSql .= " JOIN agora.ciiu_subclase S ON S.id_subclase = A.id_subclase ";
				$cadenaSql .= " WHERE num_documento= " . $variable;
				break;	
					
					
					
					
			//**************************************************************************
			/* REGISTRAR DATOS - USUARIO */
				case "registrarUsuario" :
					$cadenaSql=" INSERT INTO ";
					$cadenaSql.= $prefijo."usuario ";
					$cadenaSql.=" (";					
					$cadenaSql.=" usuario,";
					$cadenaSql.=" nombre,";
					$cadenaSql.=" apellido,";
					$cadenaSql.=" correo,";
					$cadenaSql.=" telefono, ";
					$cadenaSql.=" imagen, ";
					$cadenaSql.=" clave, ";
					$cadenaSql.=" tipo,";
					$cadenaSql.=" rolmenu,";
					$cadenaSql.=" estado";
					$cadenaSql.=" )";
					$cadenaSql.=" VALUES";
					$cadenaSql.=" (";
					$cadenaSql.=" '" . $variable['num_documento']. "',";
					$cadenaSql.=" '" . $variable['nombre']. "',";
					$cadenaSql.=" '" . $variable['apellido']. "',";
					$cadenaSql.=" '" . $variable['correo']. "',";
					$cadenaSql.=" '" . $variable['telefono']. "',";
					$cadenaSql.=" '-',";
					$cadenaSql.=" '" . $variable['contrasena']. "',";
					$cadenaSql.=" '" . $variable['tipo']. "',";
					$cadenaSql.=" '" . $variable['rolMenu']. "',";
					$cadenaSql.=" '" . $variable['estado']. "'";
					$cadenaSql.=" );";
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
				$cadenaSql .= " '" . $variable ['tipo_cuenta_bancaria'] . "', ";
				$cadenaSql .= $variable ['num_cuenta_bancaria'] . ", ";
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
				if($variable ['extension_telefono'] != null){
					$cadenaSql .= " extension,";
				}
				$cadenaSql .= " tipo";
				$cadenaSql .= " ) ";
				$cadenaSql .= " VALUES ";
				$cadenaSql .= " ( ";
				$cadenaSql .= $variable ['num_telefono'] . ", ";
				if($variable ['extension_telefono'] != null){
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
				$cadenaSql .= " profesion,";
				$cadenaSql .= " especialidad,";
				if($variable ['monto_capital_autorizado'] != null){
					$cadenaSql .= " monto_capital_autorizado,";
				}
				$cadenaSql .= " genero";
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
				$cadenaSql .= " '" . $variable ['profesion'] . "',";
				$cadenaSql .= " '" . $variable ['especialidad'] . "',";
				if($variable ['monto_capital_autorizado'] != null){
					$cadenaSql .= " " . $variable ['monto_capital_autorizado'] . ",";
				}
				$cadenaSql .= " '" . $variable ['genero'] . "'";
				$cadenaSql .= " ); ";
				break;
				
			case "consultar_tipo_proveedor" :
				$cadenaSql = " SELECT U.usuario, P.tipoPersona FROM public.prov_usuario U";
				$cadenaSql.= " JOIN agora.informacion_proveedor P ON P.num_documento = U.usuario::numeric";
				$cadenaSql.= " WHERE U.id_usuario = $variable ";
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
				$cadenaSql .= " profesion,";
				$cadenaSql .= " especialidad,";
				$cadenaSql .= " monto_capital_autorizado,";
				$cadenaSql .= " genero";
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
				$cadenaSql .= " num_cuenta_bancaria = " . $variable ['num_cuenta_bancaria'] . ", ";
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
				$cadenaSql .= " perfil = " . " " . $variable ['id_perfil'] . ",";
				$cadenaSql .= " profesion = " . " '" . $variable ['profesion'] . "',";
				$cadenaSql .= " especialidad = " . " '" . $variable ['especialidad'] . "',";
				if($variable ['monto_capital_autorizado'] != null){
						$cadenaSql .= " monto_capital_autorizado = " . " " . $variable ['monto_capital_autorizado'] . ",";
				}
				$cadenaSql .= " genero = " . " '" . $variable ['genero'] . "'";
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
				$cadenaSql.=" num_nit_empresa, ";
				$cadenaSql.=" digito_verificacion, ";
				if($variable['procedencia_empresa'] == 'NACIONAL'){
					$cadenaSql.=" procedencia_empresa, ";
				}else{
					$cadenaSql.=" procedencia_empresa, ";
					$cadenaSql.=" id_ciudad_origen, ";
					$cadenaSql.=" codigo_pais_dian, ";
				    if($variable ['codigo_postal'] != null){
				    	$cadenaSql.=" codigo_postal, ";
					}
					$cadenaSql.=" tipo_identificacion_extranjera, ";
					if($variable ['tipo_identificacion_extranjera'] == 'PASAPORTE'){
						$cadenaSql.=" num_pasaporte, ";
					}else{
						$cadenaSql.=" num_cedula_extranjeria, ";
					}
				}
				$cadenaSql.=" id_tipo_conformacion, ";
				$cadenaSql.=" monto_capital_autorizado, ";
				$cadenaSql.=" exclusividad_producto, ";
				$cadenaSql.=" regimen_contributivo, ";
				$cadenaSql.=" pyme, ";
				$cadenaSql.=" registro_mercantil, ";
				$cadenaSql.=" sujeto_retencion, ";
				$cadenaSql.=" agente_retenedor, ";
				$cadenaSql.=" \"responsable_ICA\", ";
				$cadenaSql.=" \"responsable_IVA\", ";
				$cadenaSql.=" genero, ";
				$cadenaSql.=" nom_proveedor";
				$cadenaSql .= " )";
				$cadenaSql .= " VALUES";
				$cadenaSql .= " (";
				$cadenaSql .= " " . $variable ['fki_numero_documento'] . ",";
				$cadenaSql .= " " . $variable ['digito_verificacion'] . ",";
				if($variable['procedencia_empresa'] == 'NACIONAL'){
					$cadenaSql .= " '" . $variable ['procedencia_empresa'] . "',";
				}else{
					$cadenaSql .= " '" . $variable ['procedencia_empresa'] . "',";
					$cadenaSql .= " " . $variable ['id_ciudad_origen'] . ",";
					$cadenaSql .= " " . $variable ['codigo_pais_dian'] . ",";
					if($variable ['codigo_postal'] != null){
						$cadenaSql .= " " . $variable ['codigo_postal'] . ",";
					}
					$cadenaSql .= " '" . $variable ['tipo_identificacion_extranjera'] . "',";
					if($variable ['tipo_identificacion_extranjera'] == 'PASAPORTE'){
						$cadenaSql .= " " . $variable ['num_pasaporte'] . ",";
					}else{
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
					if($variable ['codigo_postal'] != null){
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
                                    
			/* VERIFICAR NUMERO DE NIT */		
				case "verificarNIT" ://******************************************************************************
					$cadenaSql=" SELECT";
					$cadenaSql.=" num_documento,";
					$cadenaSql.=" nom_proveedor,";
					$cadenaSql.=" correo,";
					$cadenaSql.=" id_proveedor,";
                    $cadenaSql.=" estado";
					$cadenaSql.=" FROM ";
					$cadenaSql.=" agora.informacion_proveedor ";
					$cadenaSql.=" WHERE num_documento = " . $variable;	
					break; 
                                    
			/* DATOS DEL PROVEEDOR POR USUARIO */		
				case "buscarProveedorByUsuario" ://****************************************************************************
					$cadenaSql=" SELECT";
					$cadenaSql.=" P.id_proveedor,";
					$cadenaSql.=" P.num_documento,";
					$cadenaSql.=" P.tipopersona,";
					$cadenaSql.=" P.nom_proveedor,";
					$cadenaSql.=" P.id_ciudad_contacto,";
					$cadenaSql.=" P.direccion,";
					$cadenaSql.=" P.correo,";
					$cadenaSql.=" P.web,";
					$cadenaSql.=" P.nom_asesor,";
					$cadenaSql.=" P.tel_asesor,";
					$cadenaSql.=" P.tipo_cuenta_bancaria,";
					$cadenaSql.=" P.num_cuenta_bancaria,";
					$cadenaSql.=" P.id_entidad_bancaria,";
					$cadenaSql.=" P.descripcion,";
					$cadenaSql.=" P.anexorut,";
					$cadenaSql.=" P.fecha_registro,";
					$cadenaSql.=" P.fecha_ultima_modificacion,";
					$cadenaSql.=" P.estado";
					
					
					//$cadenaSql.=" P.digito_verificacion,";
					
					//$cadenaSql.=" P.id_pais_origen,";
					                                       
// 					$cadenaSql.=" P.telefono,";
// 					$cadenaSql.=" P.ext1,";
// 					$cadenaSql.=" P.movil,";
                                      
// 					$cadenaSql.=" P.tipodocumento,";
// 					$cadenaSql.=" P.numdocumento,";
// 					$cadenaSql.=" P.primerapellido,";
// 					$cadenaSql.=" P.segundoapellido,";                                        
// 					$cadenaSql.=" P.primernombre,";
// 					$cadenaSql.=" P.segundonombre,";
					
					//$cadenaSql.=" P.regimen,";
					//$cadenaSql.=" P.importacion,";                                        
					//$cadenaSql.=" P.pyme,";
					//$cadenaSql.=" P.registro_mercantil,";
					
					//$cadenaSql.=" P.tipo_procedencia,";
// 					$cadenaSql.=" P.pais,";
// 					$cadenaSql.=" P.codigo_pais,";
// 					$cadenaSql.=" P.codigo_postal,";
					//$cadenaSql.=" P.tipo_doc_extranjero,";
					//$cadenaSql.=" P.num_cedula_extranjeria,";
					//$cadenaSql.=" P.num_pasaporte";
					$cadenaSql.=" FROM ";
					$cadenaSql.=" prov_usuario U";
					$cadenaSql.=" JOIN agora.informacion_proveedor P ON P.num_documento::text = U.usuario";
					$cadenaSql.=" WHERE id_usuario = '" . $variable . "'";
					break;
					
					
					
					
					
					
					
				
				/* DATOS BIENVENIDA PROVEEDOR */
			case "buscarProveedorLog" ://******************************************************************+
				$cadenaSql = " SELECT";
				$cadenaSql .= " t1.nom_proveedor,";
				$cadenaSql .= " t1.num_documento,";
				$cadenaSql .= " t2.usuario,";
				$cadenaSql .= " t2.id_usuario ";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.informacion_proveedor t1";
				$cadenaSql .= " INNER JOIN prov_usuario t2 ON t1.num_documento::varchar = t2.usuario";
				$cadenaSql .= " WHERE t2.id_usuario = " . $variable . ";";
				break;
							
					
			/* CONSULTAR CONTRATOS DEL PROVEEDOR */		
				case "consultarContratos" ://****************************************************************************
					$cadenaSql=" SELECT";
					$cadenaSql .= " id_contrato, ";
					$cadenaSql .= " numero_contrato, ";
					$cadenaSql .= " fecha_inicio, ";
					$cadenaSql .= " fecha_finalizacion, ";
					$cadenaSql .= " valor,";
					$cadenaSql .= " C.estado";
					$cadenaSql.=" FROM ";
					$cadenaSql.=" prov_usuario U";
					$cadenaSql.=" JOIN agora.informacion_proveedor P ON P.num_documento::text = U.usuario";
					$cadenaSql.=" JOIN agora.contrato C ON C.id_proveedor = P.id_proveedor";
					$cadenaSql.=" WHERE id_usuario = '" . $variable . "'";
					break; 					
         
			/* VERIFICAR NUMERO DE NIT */		
				case "verificarProveedor" :
					$cadenaSql=" SELECT";
					$cadenaSql.=" num_documento,";
					$cadenaSql.=" nom_proveedor";
					$cadenaSql.=" FROM ";
					$cadenaSql.=" agora.informacion_proveedor ";
					$cadenaSql.=" WHERE num_documento = '" . $variable . "'";
					break;
				
				// ********************************************************************************+
				
					
					
					
					
					
					
					
					
					
					
					
					
			case 'buscarNomenclaturaAbreviatura' :
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_nomenclatura as ID_NOMENCLATURA, ';
				$cadenaSql .= 'abreviatura as ABREVIATURA ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'agora.nomenclatura_dian ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_nomenclatura = ' . $variable . ' ';
				$cadenaSql .= 'ORDER BY NOMENCLATURA';
				break;
			case 'buscarNomenclaturas' :
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_nomenclatura as ID_NOMENCLATURA, ';
				$cadenaSql .= 'nomenclatura as NOMENCLATURA ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'agora.nomenclatura_dian ';
				$cadenaSql .= 'ORDER BY NOMENCLATURA';
				break;
			
			case 'consultarPaises' :
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_pais as ID_PAIS, ';
				$cadenaSql .= 'nombre_pais as NOMBRE, ';
				$cadenaSql .= 'codigo_pais as COD_PAIS ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'agora.pais ';
				$cadenaSql .= 'ORDER BY NOMBRE';
				break;
					
			case "consultarBanco" :
				$cadenaSql = "SELECT";
				$cadenaSql .= " id_codigo,";
				$cadenaSql .= "	nombre_banco";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.banco";
				$cadenaSql .= " WHERE estado != 'INACTIVO' ";
				$cadenaSql .= " ORDER BY nombre_banco";
				break;
				
			case "consultarConformacion" :
				$cadenaSql = "SELECT";
				$cadenaSql .= " id_conformacion,";
				$cadenaSql .= "	nombre";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.tipo_conformacion";
				$cadenaSql .= " WHERE estado != 'INACTIVO' ";
				$cadenaSql .= " ORDER BY nombre";
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
			
			case 'buscarPais' :
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_pais as ID_PAIS, ';
				$cadenaSql .= 'nombre_pais as NOMBRE, ';
				$cadenaSql .= 'codigo_pais as COD_PAIS ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'agora.pais ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_pais != 112 ';
				$cadenaSql .= 'ORDER BY NOMBRE';
				break;
				
			case 'buscarPaisXDepa' :
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_pais as ID_PAIS ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'agora.departamento ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_departamento = ' . $variable . ' ;';
				break;
				
			case 'buscarPaisCod' :
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'codigo_pais as COD_PAIS ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'agora.pais ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_pais = ' . $variable . ' ;';
				break;
				
			case 'buscarCiudad' : 
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_ciudad as ID_CIUDAD, ';
				$cadenaSql .= 'nombre as NOMBRE, ';
				$cadenaSql .= 'id_departamento as ID_DEPARTAMENTO ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'agora.ciudad ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_ciudad = ' . $variable . ' ';
				$cadenaSql .= 'ORDER BY NOMBRE;';
				break;
			
			case 'buscarDepartamento' : // Solo Departamentos de Colombia
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_departamento as ID_DEPARTAMENTO, ';
				$cadenaSql .= 'nombre as NOMBRE ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'agora.departamento ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_pais = 112 ';
				$cadenaSql .= 'ORDER BY NOMBRE';
				break;
			
			case 'buscarDepartamentoAjax' :
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_departamento as ID_DEPARTAMENTO, ';
				$cadenaSql .= 'nombre as NOMBRE ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'agora.departamento ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_pais = ' . $variable . ' ';
				$cadenaSql .= 'ORDER BY NOMBRE';
				break;
			
			case 'buscarCiudadAjax' :
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_ciudad as ID_CIUDAD, ';
				$cadenaSql .= 'nombre as NOMBRECIUDAD ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'agora.ciudad ';
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

		}
		
		return $cadenaSql;
	}
}

?>
