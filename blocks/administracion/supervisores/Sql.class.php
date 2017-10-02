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

			case "insertarPerfilUsuario":

                $cadenaSql = "INSERT INTO ".$prefijo."usuario_subsistema(id_usuario, id_subsistema, rol_id, fecha_registro, fecha_caduca, estado) ";
                $cadenaSql .= " VALUES ( ";
                $cadenaSql .= " '".$variable['id_usuario']."', ";
                $cadenaSql .= " '".$variable['subsistema']."', ";
                $cadenaSql .= " '".$variable['perfil']."', ";
                $cadenaSql .= " '".$variable['fechaIni']."', ";
                $cadenaSql .= " '".$variable['fechaFin']."', ";
                $cadenaSql .= " '1'";
                $cadenaSql .= " )";

            break; 

            case "consultarUsuario":

                $cadenaSql = "SELECT ";
				$cadenaSql .= " id_usuario,";
				$cadenaSql .= " identificacion,";
				$cadenaSql .= " estado";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " prov_usuario ";
				$cadenaSql .= " WHERE identificacion = '" . $variable['cedula'] . "';";

            break; 
			
			case "consultarPersonaNatural" :
				$cadenaSql = "SELECT ";
				$cadenaSql .= " id_proveedor,";
				$cadenaSql .= " nom_proveedor,";
				$cadenaSql .= " correo";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.informacion_proveedor ";
				$cadenaSql .= " WHERE estado = 1";
				$cadenaSql .= " AND num_documento = '" . $variable . "';";
				break;

			case "consultarPersonaNaturalReg" :
				$cadenaSql = "SELECT ";
				$cadenaSql .= " S.id_proveedor,";
				$cadenaSql .= " P.num_documento,";
				$cadenaSql .= " P.nom_proveedor,";
				$cadenaSql .= " S.id_dependencia,";
				$cadenaSql .= " S.estado,";
				$cadenaSql .= " P.correo";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.informacion_supervisor S";
				$cadenaSql .= " JOIN agora.informacion_proveedor P ON P.id_proveedor = S.id_proveedor";
				$cadenaSql .= " WHERE P.id_proveedor = " . $variable . ";";
				break;	
			
			case "consultarPersonasNoSupervisoras" :
				$cadenaSql=" SELECT ";
				$cadenaSql.=" num_documento AS data, ";
				$cadenaSql.=" num_documento||' - ('||upper(nom_proveedor)||')' AS  value ";
				$cadenaSql.=" FROM ";
				$cadenaSql.=" agora.informacion_proveedor ";
				$cadenaSql.=" WHERE ";
				$cadenaSql.=" estado = 1";
				$cadenaSql.=" AND ";
				$cadenaSql.=" id_proveedor NOT IN (" . $variable . ")";
				$cadenaSql.=" AND ";
				$cadenaSql.=" tipopersona = 'NATURAL';";
				break;

			case "consultarPersonaSupervisor" :
				$cadenaSql=" SELECT ";
				$cadenaSql.=" id_proveedor AS data, ";
				$cadenaSql.=" num_documento||' - ('||upper(nom_proveedor)||')' AS  value ";
				$cadenaSql.=" FROM ";
				$cadenaSql.=" agora.informacion_proveedor ";
				$cadenaSql.=" WHERE ";
				$cadenaSql.=" estado = 1";
				$cadenaSql.=" AND ";
				$cadenaSql.=" id_proveedor = " . $variable . " ";
				$cadenaSql.=" AND ";
				$cadenaSql.=" tipopersona = 'NATURAL';";
				break;	
			
			case "consultarListaSupervisores" :
				$cadenaSql=" SELECT ";
				$cadenaSql.=" string_agg(cast(id_proveedor as text),',' ";
				$cadenaSql.=" ORDER BY ";
				$cadenaSql.=" id_proveedor) ";
				$cadenaSql.=" FROM ";
				$cadenaSql.=" agora.informacion_supervisor; ";
				break;
			
			case "consultarListaInterventores" :
				$cadenaSql=" SELECT ";
				$cadenaSql.=" string_agg(cast(id_proveedor as text),',' ";
				$cadenaSql.=" ORDER BY ";
				$cadenaSql.=" id_proveedor) ";
				$cadenaSql.=" FROM ";
				$cadenaSql.=" agora.informacion_interventor; ";
				break;
			
                    
			/* VERIFICAR NUMERO DE CEDULA */		
				case "verificarCedula" :
					$cadenaSql=" SELECT";
					$cadenaSql.=" usuario";
					$cadenaSql.=" FROM ";
					$cadenaSql.=" prov_usuario ";
					$cadenaSql.=" WHERE usuario = '" . $variable . "'";
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
					$cadenaSql.=" '" . $variable['cedula']. "',";
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

			/* REGISTRAR DATOS - SUPERVISOR */
				case "registrar" :
					$cadenaSql=" INSERT INTO agora.informacion_supervisor";
					$cadenaSql.=" (";					
					$cadenaSql.=" id_proveedor,";
					$cadenaSql.=" id_dependencia,";
					$cadenaSql.=" estado";
					$cadenaSql.=" )";
					$cadenaSql.=" VALUES";
					$cadenaSql.=" (";
					$cadenaSql.=" '" . $variable['id_proveedor']. "',";
					$cadenaSql.=" '" . $variable['dependencia']. "',";
					$cadenaSql.=" 'ACTIVO'";
					$cadenaSql.=" );";
					break;

				case 'actualizar' :
					$cadenaSql = "UPDATE agora.informacion_supervisor SET ";
					$cadenaSql .= " id_dependencia = " . $variable ['dependencia'] . ",";
					$cadenaSql .= " estado = '" . $variable ['estado'] . "' ";
					$cadenaSql .= " WHERE id_proveedor = ";
					$cadenaSql .= " " . $variable ['id_proveedor'] . "; ";
					break;			
		
			/* LISTA - DEPENDENCIA */
				case "dependencia" :
					$cadenaSql = "SELECT";
					$cadenaSql .= " id_dependencia,";
					$cadenaSql .= "	dependencia";
					$cadenaSql .= " FROM ";
					$cadenaSql .= " agora.parametro_dependencia";
					$cadenaSql .= " ORDER BY dependencia";
					break;
					
			/* LISTA - SUPERVISOR */		
				case "supervisor" :
					$cadenaSql=" SELECT";
					$cadenaSql.=" S.id_supervisor, S.id_proveedor, P.num_documento, P.nom_proveedor, D.dependencia, P.correo, S.estado";
					$cadenaSql.=" FROM ";
					$cadenaSql.=" agora.informacion_supervisor S";
					$cadenaSql.=" JOIN agora.parametro_dependencia D ON D.id_dependencia = S.id_dependencia";
					$cadenaSql.=" JOIN agora.informacion_proveedor P ON P.id_proveedor = S.id_proveedor";
					$cadenaSql.=" WHERE 1=1 ";
					if ($variable != '') {
						$cadenaSql .= " AND P.num_documento = '" . $variable . "'";
					}					
					break;


				case "buscarProveedoresFiltro" :
					$cadenaSql = " SELECT DISTINCT P.num_documento||' - ('||P.nom_proveedor||')' AS  value, P.num_documento AS data  ";
					$cadenaSql .= " FROM agora.informacion_supervisor S";
					$cadenaSql .= " JOIN agora.informacion_proveedor P ON P.id_proveedor = S.id_proveedor";
					$cadenaSql .= " WHERE cast(P.num_documento as text) LIKE '%$variable%' OR P.nom_proveedor LIKE '%$variable%' LIMIT 10; ";
				break;

		}
		
		return $cadenaSql;
	}
}

?>
