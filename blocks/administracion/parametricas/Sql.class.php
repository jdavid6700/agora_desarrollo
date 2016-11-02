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
			
			case "consultarPersonaNatural" :
				$cadenaSql = "SELECT ";
				$cadenaSql .= " id_proveedor,";
				$cadenaSql .= " nom_proveedor";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.informacion_proveedor ";
				$cadenaSql .= " WHERE estado = 1";
				$cadenaSql .= " AND num_documento = " . $variable . ";";
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
				$cadenaSql.=" num_documento NOT IN (" . $variable . ")";
				$cadenaSql.=" AND ";
				$cadenaSql.=" tipopersona = 'NATURAL';";
				break;
			
			case "consultarListaSupervisores" :
				$cadenaSql=" SELECT ";
				$cadenaSql.=" string_agg(cast(cedula as text),',' ";
				$cadenaSql.=" ORDER BY ";
				$cadenaSql.=" cedula) ";
				$cadenaSql.=" FROM ";
				$cadenaSql.=" agora.supervisor ";
				$cadenaSql.=" WHERE ";
				$cadenaSql.=" estado = 'ACTIVO';";
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
					$cadenaSql=" INSERT INTO agora.supervisor";
					$cadenaSql.=" (";					
					$cadenaSql.=" cedula,";
					$cadenaSql.=" nombre_supervisor,";
					$cadenaSql.=" id_dependencia,";
					$cadenaSql.=" correo_supervisor,";
					$cadenaSql.=" estado";
					$cadenaSql.=" )";
					$cadenaSql.=" VALUES";
					$cadenaSql.=" (";
					$cadenaSql.=" '" . $variable['cedula']. "',";
					$cadenaSql.=" '" . $variable['nombre'] . "' ,";
					$cadenaSql.=" '" . $variable['dependencia']. "',";
					$cadenaSql.=" '" . $variable['correo']. "',";
					$cadenaSql.=" 'ACTIVO'";
					$cadenaSql.=" );";
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
					
			/* LISTA - SUPERVISOR */		
				case "supervisor" :
					$cadenaSql=" SELECT";
					$cadenaSql.=" cedula, nombre_supervisor, correo_supervisor";
					$cadenaSql.=" FROM ";
					$cadenaSql.=" agora.supervisor ";
					$cadenaSql.=" WHERE 1=1 ";
					if ($variable != '') {
						$cadenaSql .= " AND cedula= '" . $variable . "'";
					}					
					break;

		}
		
		return $cadenaSql;
	}
}

?>
