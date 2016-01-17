<?php
if (! isset ( $GLOBALS ["autorizado"] )) {
    include ("../index.php");
    exit ();
}

include_once ("core/manager/Configurador.class.php");
include_once ("core/connection/Sql.class.php");

//Para evitar redefiniciones de clases el nombre de la clase del archivo sqle debe corresponder al nombre del bloque
//en camel case precedida por la palabra sql

class Sqlmenu extends sql {
	
	
	var 


$miConfigurador;

function __construct() {
    
    $this->miConfigurador = Configurador::singleton ();

}

function cadena_sql($tipo, $variable = "") {
    
    /**
     * 1.
     * Revisar las variables para evitar SQL Injection
     */
    $prefijo = $this->miConfigurador->getVariableConfiguracion ( "prefijo" );
    $idSesion = $this->miConfigurador->getVariableConfiguracion ( "id_sesion" );
    
    switch ($tipo) {


            case "datosUsuario":
                $cadena_sql =" SELECT DISTINCT ";
                $cadena_sql.=" id_usuario, ";
                $cadena_sql.=" nombre ,";
                $cadena_sql.=" apellido ,";
                $cadena_sql.=" correo ,";
                $cadena_sql.=" imagen ,";
                $cadena_sql.=" estado ";
                $cadena_sql.=" FROM ".$prefijo."usuario";
                $cadena_sql.=" WHERE id_usuario='" . $variable . "' ";                
                break;

				
        case "consultarMenu" :
            $cadenaSql = "SELECT ";
            $cadenaSql .= "P.nombre, ";
			$cadenaSql .= "nombre_menu, ";
			$cadenaSql .= "tooltip ";
            $cadenaSql .= "FROM ";
            $cadenaSql .= "proveedor.param_menu M ";
            $cadenaSql .= "JOIN proveedor.prov_pagina P ON P.id_pagina = M.id_pagina ";
			$cadenaSql .= "WHERE ";
            $cadenaSql .= "tipo ='" . $variable . "' ";
			$cadenaSql .= "ORDER BY orden";
            break;
    }
    
    return $cadenaSql;

}
}
?>
