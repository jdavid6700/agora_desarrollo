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
			
			/* REGISTRAR DATOS - USUARIO */
				case "registrarUsuario" :
					$cadenaSql=" INSERT INTO ";
					$cadenaSql.= $prefijo."usuario ";
					$cadenaSql.=" (";					
					$cadenaSql.=" id_usuario,";
					$cadenaSql.=" nombre,";
					$cadenaSql.=" apellido,";
					$cadenaSql.=" correo,";
					$cadenaSql.=" telefono, ";
					$cadenaSql.=" clave, ";
					$cadenaSql.=" tipo,";
					$cadenaSql.=" estado";
					$cadenaSql.=" )";
					$cadenaSql.=" VALUES";
					$cadenaSql.=" (";
					$cadenaSql.=" '" . $variable['cedula']. "',";
					$cadenaSql.=" '" . $variable['nombre']. "',";
					$cadenaSql.=" '" . $variable['apellido']. "',";
					$cadenaSql.=" '" . $variable['correo']. "',";
					$cadenaSql.=" '" . $variable['telefono']. "',";
					$cadenaSql.=" '" . $variable['contrasena']. "',";
					$cadenaSql.=" '" . $variable['tipo']. "',";
					$cadenaSql.=" '" . $variable['estado']. "'";
					$cadenaSql.=" );";
					break;						

			/* REGISTRAR DATOS - SUPERVISOR */
				case "registrar" :
					$cadenaSql=" INSERT INTO proveedor.param_supervisor";
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
					$cadenaSql.=" '" . $variable['nombre'] . ' ' . $variable['apellido']. "',";
					$cadenaSql.=" '" . $variable['dependencia']. "',";
					$cadenaSql.=" '" . $variable['correo']. "',";
					$cadenaSql.=" '1'";
					$cadenaSql.=" );";
					break;		
		
			/* LISTA - DEPENDENCIA */
				case "dependencia" :
					$cadenaSql = "SELECT";
					$cadenaSql .= " id_dependencia,";
					$cadenaSql .= "	dependencia";
					$cadenaSql .= " FROM ";
					$cadenaSql .= " proveedor.param_dependencia";
					$cadenaSql .= " ORDER BY dependencia";
					break;
					
			/* LISTA - SUPERVISOR */		
				case "supervisor" :
					$cadenaSql=" SELECT";
					$cadenaSql.=" documento_docente||' - '||primer_nombre||' '||segundo_nombre||' '||primer_apellido||' '||segundo_apellido AS value, ";
					$cadenaSql.=" documento_docente AS data ";
					$cadenaSql.=" FROM ";
					$cadenaSql.=" docencia.docente WHERE documento_docente||' - '||primer_nombre||' '||segundo_nombre||' '||primer_apellido||' '||segundo_apellido ";
					$cadenaSql.=" LIKE '%" . $variable . "%' AND estado = true LIMIT 10;";
					break;

		}
		
		return $cadenaSql;
	}
}

?>
