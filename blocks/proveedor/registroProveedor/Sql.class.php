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
					//$cadenaSql.=" usuario,";
					$cadenaSql.=" nombre,";
					$cadenaSql.=" apellido,";
					$cadenaSql.=" correo,";
					$cadenaSql.=" telefono, ";
					$cadenaSql.=" imagen, ";
					$cadenaSql.=" clave, ";
					$cadenaSql.=" tipo,";
					//$cadenaSql.=" rolMenu,";
					$cadenaSql.=" estado";
					$cadenaSql.=" )";
					$cadenaSql.=" VALUES";
					$cadenaSql.=" (";
					//$cadenaSql.=" '" . $variable['nit']. "',";
					$cadenaSql.=" '" . $variable['nombre']. "',";
					$cadenaSql.=" '" . $variable['apellido']. "',";
					$cadenaSql.=" '" . $variable['correo']. "',";
					$cadenaSql.=" '" . $variable['telefono']. "',";
					$cadenaSql.=" '-',";
					$cadenaSql.=" '" . $variable['contrasena']. "',";
					$cadenaSql.=" '" . $variable['tipo']. "',";
					//$cadenaSql.=" '" . $variable['rolMenu']. "',";
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
                                    
			/* VERIFICAR NUMERO DE NIT */		
				case "verificarNIT" :
					$cadenaSql=" SELECT";
					$cadenaSql.=" nit";
					$cadenaSql.=" FROM ";
					$cadenaSql.=" proveedor.prov_proveedor_info ";
					$cadenaSql.=" WHERE nit= " . $variable;	
					break; 

					


		}
		
		return $cadenaSql;
	}
}

?>
