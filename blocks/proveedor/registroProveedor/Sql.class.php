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
                    
			/* ACTUALIZAR - ESTADO PROVEEDOR */			
				case 'updateEstado' :
					$cadenaSql = "UPDATE proveedor.prov_proveedor_info SET ";
					$cadenaSql .= "estado='" . $variable ['estado'] . "'";
					$cadenaSql .= " WHERE id_proveedor=";
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
				case "registrarActividad" :
					$cadenaSql=" INSERT INTO ";
					$cadenaSql.="proveedor.prov_actividad ";
					$cadenaSql.=" (";					
					$cadenaSql.=" nit,";
					$cadenaSql.=" actividad";
					$cadenaSql.=" )";
					$cadenaSql.=" VALUES";
					$cadenaSql.=" (";
					$cadenaSql.=" '" . $variable['nit']. "',";
					$cadenaSql.=" '" . $variable['actividad']. "'";
					$cadenaSql.=" );";
					break;
					
			/* VERIFICAR NUMERO DE NIT */		
				case "verificarActividad" :
					$cadenaSql=" SELECT";
					$cadenaSql.=" nit";
					$cadenaSql.=" FROM ";
					$cadenaSql.=" proveedor.prov_actividad ";
					$cadenaSql.=" WHERE nit= '" . $variable['nit'] . "'";
					$cadenaSql.=" AND actividad= '" . $variable['actividad'] . "'";
					break;			
			
			/* CONSULTAR ACTIVIDADES DEL PROVEEDOR */		
				case "consultarActividades" :
					$cadenaSql=" SELECT";
					$cadenaSql.=" actividad,";
                                        $cadenaSql.=" nombre";
					$cadenaSql.=" FROM ";
					$cadenaSql.=" proveedor.prov_actividad A";
                                        $cadenaSql.=" JOIN proveedor.prov_ciiu_subclase S ON S.id = A.actividad ";
					$cadenaSql.=" WHERE nit= " . $variable;	
					break;			

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
					$cadenaSql.=" puntaje_evaluacion, ";
					$cadenaSql.=" clasificacion_evaluacion,";
					$cadenaSql.=" estado";
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
					$cadenaSql.=" '0',";//puntaje
					$cadenaSql.=" '0',";//clasificacion
					$cadenaSql.=" '2'";//estado inactivo
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
                                        $cadenaSql .= "registromercantil='" . $variable ['registroMercantil'] . "'";
					$cadenaSql .= " WHERE id_proveedor=";
					$cadenaSql .= "'" . $variable ['idProveedor'] . "' ";
					break;                                    
                                    
			/* VERIFICAR NUMERO DE NIT */		
				case "verificarNIT" :
					$cadenaSql=" SELECT";
					$cadenaSql.=" nit,";
					$cadenaSql.=" nomempresa,";
					$cadenaSql.=" correo,";
					$cadenaSql.=" id_proveedor,";
                                        $cadenaSql.=" estado";
					$cadenaSql.=" FROM ";
					$cadenaSql.=" proveedor.prov_proveedor_info ";
					$cadenaSql.=" WHERE nit= " . $variable;	
					break; 
                                    
			/* DATOS DEL PROVEEDOR POR USUARIO */		
				case "buscarProveedorByUsuario" :
					$cadenaSql=" SELECT";
                                        $cadenaSql.=" P.id_proveedor,";
                                        $cadenaSql.=" P.nit,";
					$cadenaSql.=" P.digitoverificacion,";
                                        $cadenaSql.=" P.nomempresa,";
					$cadenaSql.=" P.municipio,";
                                        $cadenaSql.=" P.direccion,";
					$cadenaSql.=" P.correo,";
                                        $cadenaSql.=" P.web,";                                        
					$cadenaSql.=" P.telefono,";
                                        $cadenaSql.=" P.ext1,";
					$cadenaSql.=" P.movil,";
                                        $cadenaSql.=" P.nomasesor,";
                                        $cadenaSql.=" P.telasesor,";                                        
					$cadenaSql.=" P.tipodocumento,";
                                        $cadenaSql.=" P.numdocumento,";
					$cadenaSql.=" P.primerapellido,";
                                        $cadenaSql.=" P.segundoapellido,";                                        
					$cadenaSql.=" P.primernombre,";
					$cadenaSql.=" P.segundonombre,";
                                        $cadenaSql.=" P.tipopersona,";
					$cadenaSql.=" P.regimen,";
                                        $cadenaSql.=" P.importacion,";                                        
					$cadenaSql.=" P.pyme,";
					$cadenaSql.=" P.registromercantil,";                                        
                                        $cadenaSql.=" P.estado";                                        
					$cadenaSql.=" FROM ";
					$cadenaSql.=" prov_usuario U";
                                        $cadenaSql.=" JOIN proveedor.prov_proveedor_info P ON P.nit::text = U.usuario";
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
                                    
		
			/* CIIU */				
				case "ciiuDivision" :
					$cadenaSql = "SELECT";
					$cadenaSql .= " id,";
					$cadenaSql .= "	nombre";
					$cadenaSql .= " FROM ";
					$cadenaSql .= " proveedor.prov_ciiu_division";	
					$cadenaSql .= " ORDER BY nombre";
					break;
				
				case "ciiuGrupo" :
					$cadenaSql = "SELECT";
					$cadenaSql .= " id,";
					$cadenaSql .= "	nombre";
					$cadenaSql .= " FROM ";
					$cadenaSql .= " proveedor.prov_ciiu_clase";
					$cadenaSql .= " WHERE division ='" . $variable ."'";
					$cadenaSql .= " ORDER BY nombre";
					break;

				case "ciiuClase" :
					$cadenaSql = "SELECT";
					$cadenaSql .= " id,";
					$cadenaSql .= "	nombre";
					$cadenaSql .= " FROM ";
					$cadenaSql .= " proveedor.prov_ciiu_subclase";
					$cadenaSql .= " WHERE clase ='" . $variable ."'";
					$cadenaSql .= " ORDER BY nombre";
					break;

		}
		
		return $cadenaSql;
	}
}

?>
