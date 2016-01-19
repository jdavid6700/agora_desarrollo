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
				case "registrarProveedor" :
					$cadenaSql=" INSERT INTO ";
					$cadenaSql.="proveedor.proveedor_info ";
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
					$cadenaSql.=" apellido1, ";
					$cadenaSql.=" apellido2, ";
					$cadenaSql.=" nombre1,";
                                        $cadenaSql.=" nombre2,";
                                        
                                        
					$cadenaSql.=" regimen,";
					$cadenaSql.=" importacion,";
					$cadenaSql.=" pyme,";
					$cadenaSql.=" registromercantil, ";
					$cadenaSql.=" puntaje_evaluacion, ";
					$cadenaSql.=" clasificacion_evaluacion";
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
                                    
			/* VERIFICAR NUMERO DE NIT */		
				case "verificarNIT" :
					$cadenaSql=" SELECT";
					$cadenaSql.=" NIT";
					$cadenaSql.=" FROM ";
					$cadenaSql.=" proveedor.prov_proveedor_info ";
					$cadenaSql.=" WHERE nit= '" . $variable . "'";	
					break;                                    


		}
		
		return $cadenaSql;
	}
}

?>
