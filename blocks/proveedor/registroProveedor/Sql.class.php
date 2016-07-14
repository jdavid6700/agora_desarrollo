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
				$cadenaSql .= " proveedor.evaluacion ";
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
				$cadenaSql .= " proveedor.contrato C";
				$cadenaSql .= " JOIN proveedor.objeto_contratar O ON O.id_objeto = C.id_objeto";
				$cadenaSql .= " JOIN proveedor.informacion_proveedor P ON P.id_proveedor = C.id_proveedor";
				$cadenaSql .= " WHERE  id_contrato=" . $variable;
				break;
			
			/* ACTUALIZAR - ESTADO PROVEEDOR */
			case 'updateEstado' ://****************************************************************************************
				$cadenaSql = "UPDATE proveedor.informacion_proveedor SET ";
				$cadenaSql .= "estado = '" . $variable ['estado'] . "'";
				$cadenaSql .= " WHERE id_proveedor = ";
				$cadenaSql .= "'" . $variable ['idProveedor'] . "' ";
				break;
			
			/* ULTIMO NUMERO DE SECUENCIA */
			case "lastIdProveedor" :
				$cadenaSql = "SELECT";
				$cadenaSql .= " last_value";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " proveedor.prov_proveedor_info_id_proveedor_seq";
				break;
			
			/* REGISTRAR DATOS - USUARIO */
			case "registrarActividad" ://**********************************************************
				$cadenaSql = " INSERT INTO ";
				$cadenaSql .= "proveedor.proveedor_actividad_ciiu ";
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
				$cadenaSql .= " proveedor.proveedor_actividad_ciiu ";
				$cadenaSql .= " WHERE num_documento= '" . $variable ['nit'] . "'";
				$cadenaSql .= " AND id_subclase = '" . $variable ['actividad'] . "'";
				break;
			
			/* CONSULTAR ACTIVIDADES DEL PROVEEDOR */
			case "consultarActividades" ://********************************************************************
				$cadenaSql = " SELECT";
				$cadenaSql .= " A.id_subclase,";
				$cadenaSql .= " nombre";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " proveedor.proveedor_actividad_ciiu A";
				$cadenaSql .= " JOIN parametro.ciiu_subclase S ON S.id_subclase = A.id_subclase ";
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
				$cadenaSql .= "proveedor.informacion_proveedor ";
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
				$cadenaSql .= "proveedor.telefono ";
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
				$cadenaSql .= "proveedor.proveedor_telefono ";
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
				$cadenaSql .= "proveedor.informacion_persona_natural ";
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
				$cadenaSql .= " monto_capital_autorizado,";
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
				$cadenaSql .= " " . $variable ['monto_capital_autorizado'] . ",";
				$cadenaSql .= " '" . $variable ['genero'] . "'";
				$cadenaSql .= " ); ";
				break;
					
					
					
					
					
					
					
					
					
					
					
					
					
                                    
			/* ACTUALIZAR - PROVEEEDOR DATOS */			
				case 'actualizarProveedor' :
					$cadenaSql = "UPDATE proveedor.prov_proveedor_info SET ";
					$cadenaSql .= "tipopersona='" . $variable ['tipoPersona'] . "',";
					$cadenaSql .= "digitoverificacion='" . $variable ['digito'] . "',";
					$cadenaSql .= "nomempresa='" . $variable ['nombreEmpresa'] . "',";
					$cadenaSql .= "municipio='" . $variable ['ciudad'] . "',";
					$cadenaSql .= "direccion='" . $variable ['direccion'] . "',";
					$cadenaSql .= "correo='" . $variable ['correo'] . "',";
					$cadenaSql .= "web='" . $variable ['sitioWeb'] . "',";
					$cadenaSql .= "telefono='" . $variable ['telefono'] . "',";
					$cadenaSql .= "ext1='" . $variable ['extension'] . "',";
					$cadenaSql .= "movil='" . $variable ['movil'] . "',";
					$cadenaSql .= "nomasesor='" . $variable ['asesorComercial'] . "',";
					$cadenaSql .= "telasesor='" . $variable ['telAsesor'] . "',";
					$cadenaSql .= "tipodocumento='" . $variable ['tipoDocumento'] . "',";
					$cadenaSql .= "numdocumento='" . $variable ['numeroDocumento'] . "',";
					$cadenaSql .= "primerapellido='" . $variable ['primerApellido'] . "',";
					$cadenaSql .= "segundoapellido='" . $variable ['segundoApellido'] . "',";
					$cadenaSql .= "primernombre='" . $variable ['primerNombre'] . "',";
					$cadenaSql .= "segundonombre='" . $variable ['segundoNombre'] . "',";
					$cadenaSql .= "importacion='" . $variable ['productoImportacion'] . "',";
					$cadenaSql .= "regimen='" . $variable ['regimenContributivo'] . "',";
					$cadenaSql .= "pyme='" . $variable ['pyme'] . "',";
					$cadenaSql .= "registromercantil='" . $variable ['registroMercantil'] . "',";
					$cadenaSql .= "descripcion='" . $variable ['descripcion'] . "',";
					
					if($variable['paisEmpresa'] == 1){
						$cadenaSql.= "tipo_procedencia= 'Nacional',";
						$cadenaSql.= "pais= NULL,";
						$cadenaSql.= "codigo_pais= NULL,";
						$cadenaSql.= "codigo_postal= NULL,";
						$cadenaSql.= "tipo_doc_extranjero= NULL,";
						$cadenaSql.= "cedula_extranjeria= NULL,";
						$cadenaSql.= "pasaporte= NULL";
					}else{
						$cadenaSql.= "tipo_procedencia= 'Extranjero',";
						$cadenaSql.= "pais= '" . $variable['pais']. "',";
						$cadenaSql.= "codigo_pais= ".$variable['codigoPais']. ",";
						$cadenaSql.= "codigo_postal= ".$variable['codigoPostal']. ",";
						if($variable['tipoIdentifiExtranjera'] == 1){
							$cadenaSql.= "tipo_doc_extranjero= 'Cedula de Extranjeria',";
							$cadenaSql.= "cedula_extranjeria= ". $variable['cedulaExtranjeria'].",";
							$cadenaSql.= "pasaporte= NULL";
						}else{
							$cadenaSql.= "tipo_doc_extranjero= 'Pasaporte',";
							$cadenaSql.= "pasaporte= ". $variable['pasaporte'].",";
							$cadenaSql.= "cedula_extranjeria= NULL";
						}
					}
					
					$cadenaSql .= " WHERE id_proveedor=";
					$cadenaSql .= "'" . $variable ['idProveedor'] . "' ";
					break; 

			/* ACTUALIZAR - PROVEEEDOR DATOS */			
				case 'actualizarRUT' :
					$cadenaSql = "UPDATE proveedor.prov_proveedor_info SET ";
					$cadenaSql .= "anexorut='" . $variable ['destino'] . "'";
					$cadenaSql .= " WHERE id_proveedor=";
					$cadenaSql .= "'" . $variable ['idProveedor'] . "' ";
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
					$cadenaSql.=" proveedor.informacion_proveedor ";
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
					$cadenaSql.=" JOIN proveedor.informacion_proveedor P ON P.num_documento::text = U.usuario";
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
				$cadenaSql .= " proveedor.informacion_proveedor t1";
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
					$cadenaSql.=" JOIN proveedor.informacion_proveedor P ON P.num_documento::text = U.usuario";
					$cadenaSql.=" JOIN proveedor.contrato C ON C.id_proveedor = P.id_proveedor";
					$cadenaSql.=" WHERE id_usuario = '" . $variable . "'";
					break; 					
         
			/* VERIFICAR NUMERO DE NIT */		
				case "verificarProveedor" :
					$cadenaSql=" SELECT";
					$cadenaSql.=" num_documento,";
					$cadenaSql.=" nom_proveedor";
					$cadenaSql.=" FROM ";
					$cadenaSql.=" proveedor.informacion_proveedor ";
					$cadenaSql.=" WHERE num_documento = '" . $variable . "'";
					break;
				
				// ********************************************************************************+
				
					
					
					
					
					
					
					
					
					
					
					
					
			case 'buscarNomenclaturaAbreviatura' :
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_nomenclatura as ID_NOMENCLATURA, ';
				$cadenaSql .= 'abreviatura as ABREVIATURA ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'parametro.nomenclatura_dian ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_nomenclatura = ' . $variable . ' ';
				$cadenaSql .= 'ORDER BY NOMENCLATURA';
				break;
			case 'buscarNomenclaturas' :
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_nomenclatura as ID_NOMENCLATURA, ';
				$cadenaSql .= 'nomenclatura as NOMENCLATURA ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'parametro.nomenclatura_dian ';
				$cadenaSql .= 'ORDER BY NOMENCLATURA';
				break;
			
			case 'consultarPaises' :
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_pais as ID_PAIS, ';
				$cadenaSql .= 'nombre_pais as NOMBRE, ';
				$cadenaSql .= 'codigo_pais as COD_PAIS ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'parametro.pais ';
				$cadenaSql .= 'ORDER BY NOMBRE';
				break;
					
			case "consultarBanco" :
				$cadenaSql = "SELECT";
				$cadenaSql .= " id_codigo,";
				$cadenaSql .= "	nombre_banco";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " parametro.banco";
				$cadenaSql .= " WHERE estado != 'INACTIVO' ";
				$cadenaSql .= " ORDER BY nombre_banco";
				break;
				
			case "consultarConformacion" :
				$cadenaSql = "SELECT";
				$cadenaSql .= " id_conformacion,";
				$cadenaSql .= "	nombre";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " parametro.tipo_conformacion";
				$cadenaSql .= " WHERE estado != 'INACTIVO' ";
				$cadenaSql .= " ORDER BY nombre";
				break;
					
			/* CIIU */
			case "ciiuDivision" :
				$cadenaSql = "SELECT";
				$cadenaSql .= " id_division,";
				$cadenaSql .= "	nombre";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " parametro.ciiu_division";
				$cadenaSql .= " ORDER BY nombre";
				break;
			
			case "ciiuGrupo" :
				$cadenaSql = "SELECT";
				$cadenaSql .= " id_clase,";
				$cadenaSql .= "	nombre";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " parametro.ciiu_clase";
				$cadenaSql .= " WHERE division ='" . $variable . "'";
				$cadenaSql .= " ORDER BY nombre";
				break;
			
			case "ciiuClase" :
				$cadenaSql = "SELECT";
				$cadenaSql .= " id_subclase,";
				$cadenaSql .= "	nombre";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " parametro.ciiu_subclase";
				$cadenaSql .= " WHERE clase ='" . $variable . "'";
				$cadenaSql .= " ORDER BY nombre";
				break;
			
			case 'buscarPais' :
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_pais as ID_PAIS, ';
				$cadenaSql .= 'nombre_pais as NOMBRE, ';
				$cadenaSql .= 'codigo_pais as COD_PAIS ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'parametro.pais ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_pais != 112 ';
				$cadenaSql .= 'ORDER BY NOMBRE';
				break;
				
			case 'buscarPaisCod' :
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'codigo_pais as COD_PAIS ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'parametro.pais ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_pais = ' . $variable . ' ;';
				break;
			
			case 'buscarDepartamento' : // Solo Departamentos de Colombia
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_departamento as ID_DEPARTAMENTO, ';
				$cadenaSql .= 'nombre as NOMBRE ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'parametro.departamento ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_pais = 112 ';
				$cadenaSql .= 'ORDER BY NOMBRE';
				break;
			
			case 'buscarDepartamentoAjax' :
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_departamento as ID_DEPARTAMENTO, ';
				$cadenaSql .= 'nombre as NOMBRE ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'parametro.departamento ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_pais = ' . $variable . ' ';
				$cadenaSql .= 'ORDER BY NOMBRE';
				break;
			
			case 'buscarCiudadAjax' :
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_ciudad as ID_CIUDAD, ';
				$cadenaSql .= 'nombre as NOMBRECIUDAD ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'parametro.ciudad ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_departamento = ' . $variable . ' ';
				$cadenaSql .= 'ORDER BY NOMBRE';
				break;
				
			case 'consultarTipoDocumento' :
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_parametro as ID_PARAMETRO, ';
				$cadenaSql .= 'INITCAP(LOWER(valor_parametro)) as VALOR_TIPO ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'parametro.parametro_estandar ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'valor_parametro != \'NIT\' ';
				$cadenaSql .= 'AND clase_parametro = \'Tipo Documento\';';
				break;

		}
		
		return $cadenaSql;
	}
}

?>
