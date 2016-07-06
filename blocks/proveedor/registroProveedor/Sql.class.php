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
					$cadenaSql.=" '" . $variable['nit']. "',";
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
				case "registrarProveedor" :
					$cadenaSql=" INSERT INTO ";
					$cadenaSql.="proveedor.prov_proveedor_info ";
					$cadenaSql.=" (";					
					$cadenaSql.=" tipopersona,";
					$cadenaSql.=" nit,";
					$cadenaSql.=" digitoverificacion,";
					$cadenaSql.=" nomempresa,";
					$cadenaSql.=" municipio,";
					$cadenaSql.=" direccion,";
					$cadenaSql.=" correo, ";
					$cadenaSql.=" web, ";
					$cadenaSql.=" telefono,";
					$cadenaSql.=" ext1,";
					$cadenaSql.=" movil,";
					$cadenaSql.=" nomAsesor,";
					$cadenaSql.=" telAsesor,";
					$cadenaSql.=" tipodocumento,";
					$cadenaSql.=" numdocumento,";
					$cadenaSql.=" primerApellido, ";
					$cadenaSql.=" segundoapellido, ";
					$cadenaSql.=" primerNombre,";
					$cadenaSql.=" segundoNombre,";
					$cadenaSql.=" importacion,";
					$cadenaSql.=" regimen,";
					$cadenaSql.=" pyme,";
					$cadenaSql.=" registromercantil, ";
					$cadenaSql.=" descripcion, ";
					$cadenaSql.=" anexorut,";
					$cadenaSql.=" puntaje_evaluacion, ";
					$cadenaSql.=" clasificacion_evaluacion,";
					$cadenaSql.=" estado,";
					
					if($variable['paisEmpresa'] == 1){
						$cadenaSql.=" tipo_procedencia";
					}else{
						$cadenaSql.=" tipo_procedencia,";
						$cadenaSql.=" pais,";
						$cadenaSql.=" codigo_pais,";
						$cadenaSql.=" codigo_postal,";
						$cadenaSql.=" tipo_doc_extranjero,";
						if($variable['tipoIdentifiExtranjera'] == 1){
							$cadenaSql.=" cedula_extranjeria";
						}else{
							$cadenaSql.=" pasaporte";
						}
					}
					$cadenaSql.=" )";
					$cadenaSql.=" VALUES";
					$cadenaSql.=" (";
					$cadenaSql.=" '" . $variable['tipoPersona']. "',";
					$cadenaSql.=" '" . $variable['nit']. "',";
					$cadenaSql.=" '" . $variable['digito']. "',";
					$cadenaSql.=" '" . $variable['nombreEmpresa']. "',";
					$cadenaSql.=" '" . $variable['ciudad']. "',";
					$cadenaSql.=" '" . $variable['direccion']. "',";
					$cadenaSql.=" '" . $variable['correo']. "',";
					$cadenaSql.=" '" . $variable['sitioWeb']. "',";
					$cadenaSql.=" '" . $variable['telefono']. "',";
					$cadenaSql.=" '" . $variable['extension']. "',";					
					$cadenaSql.=" '" . $variable['movil']. "',";
					$cadenaSql.=" '" . $variable['asesorComercial']. "',";
					$cadenaSql.=" '" . $variable['telAsesor']. "',";
					$cadenaSql.=" '" . $variable['tipoDocumento']. "',";
					$cadenaSql.=" '" . $variable['numeroDocumento']. "',";
					$cadenaSql.=" '" . $variable['primerApellido']. "',";
					$cadenaSql.=" '" . $variable['segundoApellido']. "',";
					$cadenaSql.=" '" . $variable['primerNombre']. "',";
					$cadenaSql.=" '" . $variable['segundoNombre']. "',";
					$cadenaSql.=" '" . $variable['productoImportacion']. "',";
					$cadenaSql.=" '" . $variable['regimenContributivo']. "',";
					$cadenaSql.=" '" . $variable['pyme']. "',";
					$cadenaSql.=" '" . $variable['registroMercantil']. "',";
					$cadenaSql.=" '" . $variable['descripcion']. "',";
					$cadenaSql.=" '" . $variable['destino']. "',";
					$cadenaSql.=" '0',";//puntaje
					$cadenaSql.=" '0',";//clasificacion
					$cadenaSql.=" '2',";//estado inactivo
					if($variable['paisEmpresa'] == 1){
						$cadenaSql.=" 'Nacional'";
					}else{
						$cadenaSql.=" 'Extranjero',";
						$cadenaSql.=" '" . $variable['pais']. "',";
						$cadenaSql.= $variable['codigoPais']. ",";
						$cadenaSql.= $variable['codigoPostal']. ",";
						if($variable['tipoIdentifiExtranjera'] == 1){
							$cadenaSql.=" 'Cedula de Extranjeria',";
							$cadenaSql.= $variable['cedulaExtranjeria'];
						}else{
							$cadenaSql.=" 'Pasaporte',";
							$cadenaSql.= $variable['pasaporte'];
						}
					}
					$cadenaSql.=" );";
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
					$cadenaSql.=" P.digito_verificacion,";
					$cadenaSql.=" P.nom_proveedor,";
					$cadenaSql.=" P.id_pais_origen,";
					$cadenaSql.=" P.direccion,";
					$cadenaSql.=" P.correo,";
					$cadenaSql.=" P.web,";                                        
// 					$cadenaSql.=" P.telefono,";
// 					$cadenaSql.=" P.ext1,";
// 					$cadenaSql.=" P.movil,";
// 					$cadenaSql.=" P.nomasesor,";
// 					$cadenaSql.=" P.telasesor,";                                        
// 					$cadenaSql.=" P.tipodocumento,";
// 					$cadenaSql.=" P.numdocumento,";
// 					$cadenaSql.=" P.primerapellido,";
// 					$cadenaSql.=" P.segundoapellido,";                                        
// 					$cadenaSql.=" P.primernombre,";
// 					$cadenaSql.=" P.segundonombre,";
					$cadenaSql.=" P.tipopersona,";
					$cadenaSql.=" P.regimen,";
					$cadenaSql.=" P.importacion,";                                        
					$cadenaSql.=" P.pyme,";
					$cadenaSql.=" P.registro_mercantil,";
					$cadenaSql.=" P.descripcion,";
					$cadenaSql.=" P.anexorut,";
					$cadenaSql.=" P.estado,";
					$cadenaSql.=" P.tipo_procedencia,";
// 					$cadenaSql.=" P.pais,";
// 					$cadenaSql.=" P.codigo_pais,";
// 					$cadenaSql.=" P.codigo_postal,";
					$cadenaSql.=" P.tipo_doc_extranjero,";
					$cadenaSql.=" P.num_cedula_extranjeria,";
					$cadenaSql.=" P.num_pasaporte";
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
				case "verificarNITProveedor" :
					$cadenaSql=" SELECT";
					$cadenaSql.=" usuario";
					$cadenaSql.=" FROM ";
					$cadenaSql.=" prov_usuario ";
					$cadenaSql.=" WHERE usuario = '" . $variable . "'";
					break;
				
			// ********************************************************************************+
				
					
					
					
					
					
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
				$cadenaSql .= 'nombre_pais as NOMBRE ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'parametro.pais ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_pais != 112 ';
				$cadenaSql .= 'ORDER BY NOMBRE';
				break;
			
			case 'buscarDepartamento' : // Solo Departamentos de Colombia
				
				$cadenaSql = 'SELECT ';
				$cadenaSql .= 'id_departamento as ID_DEPARTAMENTO, ';
				$cadenaSql .= 'nombre as NOMBRE ';
				$cadenaSql .= 'FROM ';
				$cadenaSql .= 'parametro.departamento ';
				$cadenaSql .= 'WHERE ';
				$cadenaSql .= 'id_pais = 112;';
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
