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
			case "todos_proveedores" :
				$cadenaSql = " SELECT numdocumento, primerapellido, segundoapellido, primernombre, segundonombre FROM proveedor.prov_proveedor_info;  ";
				break;
				
			case "consultar_tipo_proveedor" :
				$cadenaSql = " SELECT tipopersona FROM agora.informacion_proveedor ";
				$cadenaSql .= " WHERE num_documento = $variable ";
				break;
			
			case "informacion_por_proveedor_juridica" :
				$cadenaSql=" SELECT";
				$cadenaSql.=" P.tipopersona as TIPO_PERSONA,";
				$cadenaSql.=" PJ.num_nit_empresa as NUM_NIT_EMPRESA,";
				$cadenaSql.=" PJ.digito_verificacion as DIGITO_VERIFICACION_EMPRESA,";
				$cadenaSql.=" PJ.nom_proveedor as NOM_EMPRESA,";
				$cadenaSql.=" PJ.genero as GENERO_EMPRESA,";
				$cadenaSql.=" PJ.procedencia_empresa as PROCEDENCIA_EMPRESA,";
				$cadenaSql.=" JLP.nombre_pais as NOM_PAIS_EMPRESA,";
				$cadenaSql.=" JLD.nombre as NOM_DEPARTAMENTO_EMPRESA,";
				$cadenaSql.=" JL.nombre as NOM_CIUDAD_EMPRESA,";
				$cadenaSql.=" PJ.codigo_pais_dian as CODIGO_PAIS_DIAN_EMPRESA,";
				$cadenaSql.=" PJ.codigo_postal as CODIGO_POSTAL_EMPRESA,";
				$cadenaSql.=" PJ.tipo_identificacion_extranjera as TIPO_IDENT_EXTRANJERA_EMPRESA,";
				$cadenaSql.=" PJ.num_cedula_extranjeria as NUM_CEDULA_EXTRANJERA_EMPRESA,";
				$cadenaSql.=" PJ.num_pasaporte as NUM_PASAPORTE_EMPRESA,";
				$cadenaSql.=" TC.nombre as TIPO_CONFORMACION_EMPRESA,";
				$cadenaSql.=" PJ.monto_capital_autorizado as MONTO_CAPITAL_EMPRESA,";
				$cadenaSql.=" PJ.regimen_contributivo as REGIMEN_CONTRIBUTIVO_EMPRESA,";
				$cadenaSql.=" LP.nombre_pais as NOM_PAIS_CONTACTO,";
				$cadenaSql.=" LD.nombre as NOM_DEPARTAMENTO_CONTACTO,";
				$cadenaSql.=" L.nombre as NOM_CIUDAD_CONTACTO,";
				$cadenaSql.=" P.direccion as DIR_CONTACTO,";
				$cadenaSql.=" P.correo as CORREO_CONTACTO,";
				$cadenaSql.=" P.web as WEB_CONTACTO,";
				$cadenaSql.=" P.nom_asesor as NOM_ASESOR_EMPRESA,";
				$cadenaSql.=" P.tel_asesor as TEL_ASESOR_EMPRESA,";
				$cadenaSql.=" S.nombre_banco as NOM_BANCO_EMPRESA,";
				$cadenaSql.=" P.tipo_cuenta_bancaria as TIPO_CUENTA_BANCARIA_EMPRESA,";
				$cadenaSql.=" P.num_cuenta_bancaria as NUM_CUENTA_BANCARIA_EMPRESA,";
				$cadenaSql.=" PNT.valor_parametro as TIPO_DOCUMENTO_REPRESENTANTE,";
				$cadenaSql.=" PN.num_documento_persona as NUM_DOCUMENTO_REPRESENTANTE,";
				$cadenaSql.=" PN.digito_verificacion as DIGITO_VERIFICACION_REPRESENTANTE,";
				$cadenaSql.=" PN.primer_apellido as PRIMER_APELLIDO_REPRESENTANTE,";
				$cadenaSql.=" PN.segundo_apellido as SEGUNDO_APELLIDO_REPRESENTANTE,";
				$cadenaSql.=" PN.primer_nombre as PRIMER_NOMBRE_REPRESENTANTE,";
				$cadenaSql.=" PN.segundo_nombre as SEGUNDO_NOMBRE_REPRESENTANTE,";
				$cadenaSql.=" PN.genero as GENERO_REPRESENTANTE,";
				$cadenaSql.=" PN.cargo as CARGO_REPRESENTANTE,";
				$cadenaSql.=" PNR.nombre_pais as PAIS_NACIMIENTO_REPRESENTANTE,";
				$cadenaSql.=" PER.valor_parametro as PERFIL_REPRESENTANTE,";
				$cadenaSql.=" PN.profesion as PROFESION_REPRESENTANTE,";
				$cadenaSql.=" PN.especialidad as ESPECIALIDAD_REPRESENTANTE,";
				$cadenaSql.=" R.telefono_contacto as TEL_CONTACTO_REPRESENTANTE,";
				$cadenaSql.=" R.correo_representante as CORREO_CONTACTO_REPRESENTANTE,";
				$cadenaSql.=" P.fecha_registro as FECHA_REGISTRO_EMPRESA,";
				$cadenaSql.=" P.fecha_ultima_modificacion as FECHA_ULTIMO_CAMBIO_EMPRESA,";
				$cadenaSql.=" E.valor_parametro as ESTADO_EMPRESA";
				$cadenaSql.=" FROM agora.informacion_proveedor P";
				$cadenaSql.=" JOIN agora.ciudad L ON P.id_ciudad_contacto = L.id_ciudad";
				$cadenaSql.=" JOIN agora.departamento LD ON L.id_departamento = LD.id_departamento";
				$cadenaSql.=" JOIN agora.pais LP ON LD.id_pais = LP.id_pais";
				$cadenaSql.=" JOIN agora.parametro_estandar E ON P.estado = E.id_parametro";
				$cadenaSql.=" JOIN agora.banco S ON P.id_entidad_bancaria = S.id_codigo";
				$cadenaSql.=" JOIN agora.proveedor_representante_legal R ON P.id_proveedor = R.id_proveedor";
				$cadenaSql.=" JOIN agora.informacion_persona_natural PN ON PN.num_documento_persona = R.id_representante";
				$cadenaSql.=" JOIN agora.parametro_estandar PNT ON PNT.id_parametro = PN.tipo_documento";
				$cadenaSql.=" JOIN agora.pais PNR ON PNR.id_pais = PN.id_pais_nacimiento";
				$cadenaSql.=" JOIN agora.parametro_estandar PER ON PER.id_parametro = PN.perfil";
				$cadenaSql.=" JOIN agora.informacion_persona_juridica PJ ON PJ.num_nit_empresa = P.num_documento";
				$cadenaSql.=" JOIN agora.ciudad JL ON PJ.id_ciudad_origen = JL.id_ciudad";
				$cadenaSql.=" JOIN agora.departamento JLD ON JL.id_departamento = JLD.id_departamento";
				$cadenaSql.=" JOIN agora.pais JLP ON JLD.id_pais = JLP.id_pais";
				$cadenaSql.=" JOIN agora.tipo_conformacion TC ON TC.id_conformacion = PJ.id_tipo_conformacion";
				$cadenaSql.=" WHERE";
				$cadenaSql.=" num_documento = $variable ";
				break;
				
			case "informacion_por_proveedor_natural" :
				$cadenaSql=" SELECT";
				$cadenaSql.=" P.tipopersona as TIPO_PERSONA,";
				$cadenaSql.=" PNT.valor_parametro as TIPO_DOCUMENTO_PERSONA_NATURAL,";
				$cadenaSql.=" PN.num_documento_persona as NUM_DOCUMENTO_PERSONA_NATURAL,";
				$cadenaSql.=" PN.digito_verificacion as DIGITO_VERIFICACION_PERSONA_NATURAL,";
				$cadenaSql.=" PN.primer_apellido as PRIMER_APELLIDO_PERSONA_NATURAL,";
				$cadenaSql.=" PN.segundo_apellido as SEGUNDO_APELLIDO_PERSONA_NATURAL,";
				$cadenaSql.=" PN.primer_nombre as PRIMER_NOMBRE_PERSONA_NATURAL,";
				$cadenaSql.=" PN.segundo_nombre as SEGUNDO_NOMBRE_PERSONA_NATURAL,";
				$cadenaSql.=" PN.genero as GENERO_PERSONA_NATURAL,";
				$cadenaSql.=" PN.cargo as CARGO_PERSONA_NATURAL,";
				$cadenaSql.=" PNR.nombre_pais as PAIS_NACIMIENTO_PERSONA_NATURAL,";
				$cadenaSql.=" PER.valor_parametro as PERFIL_PERSONA_NATURAL,";
				$cadenaSql.=" PN.profesion as PROFESION_PERSONA_NATURAL,";
				$cadenaSql.=" PN.especialidad as ESPECIALIDAD_PERSONA_NATURAL,";
				$cadenaSql.=" LP.nombre_pais as NOM_PAIS_CONTACTO,";
				$cadenaSql.=" LD.nombre as NOM_DEPARTAMENTO_CONTACTO,";
				$cadenaSql.=" L.nombre as NOM_CIUDAD_CONTACTO,";
				$cadenaSql.=" P.direccion as DIR_CONTACTO,";
				$cadenaSql.=" P.correo as CORREO_CONTACTO,";
				$cadenaSql.=" P.web as WEB_CONTACTO,";
				$cadenaSql.=" S.nombre_banco as NOM_BANCO_PERSONA_NATURAL,";
				$cadenaSql.=" P.tipo_cuenta_bancaria as TIPO_CUENTA_BANCARIA_PERSONA_NATURAL,";
				$cadenaSql.=" P.num_cuenta_bancaria as NUM_CUENTA_BANCARIA_PERSONA_NATURAL,";
				$cadenaSql.=" P.fecha_registro as FECHA_REGISTRO_PERSONA_NATURAL,";
				$cadenaSql.=" P.fecha_ultima_modificacion as FECHA_ULTIMO_CAMBIO_PERSONA_NATURAL,";
				$cadenaSql.=" E.valor_parametro as ESTADO_PERSONA_NATURAL";
				$cadenaSql.=" FROM agora.informacion_proveedor P";
				$cadenaSql.=" JOIN agora.ciudad L ON P.id_ciudad_contacto = L.id_ciudad";
				$cadenaSql.=" JOIN agora.departamento LD ON L.id_departamento = LD.id_departamento";
				$cadenaSql.=" JOIN agora.pais LP ON LD.id_pais = LP.id_pais";
				$cadenaSql.=" JOIN agora.parametro_estandar E ON P.estado = E.id_parametro";
				$cadenaSql.=" JOIN agora.banco S ON P.id_entidad_bancaria = S.id_codigo";
				$cadenaSql.=" JOIN agora.informacion_persona_natural PN ON PN.num_documento_persona = P.num_documento";
				$cadenaSql.=" JOIN agora.parametro_estandar PNT ON PNT.id_parametro = PN.tipo_documento";
				$cadenaSql.=" JOIN agora.pais PNR ON PNR.id_pais = PN.id_pais_nacimiento";
				$cadenaSql.=" JOIN agora.parametro_estandar PER ON PER.id_parametro = PN.perfil";
				$cadenaSql.=" WHERE";
				$cadenaSql.=" num_documento = $variable ";
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
