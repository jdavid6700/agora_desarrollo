<?php

namespace usuarios\gestionUsuarios\funcion;

use usuarios\gestionUsuarios\funcion\redireccion;

include_once ('redireccionar.php');

if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
}

class BuscarProveedor {

    var $miConfigurador;
    var $lenguaje;
    var $miFormulario;
    var $miFuncion;
    var $miSql;
    var $conexion;
    var $miLogger;

    function __construct($lenguaje, $sql, $funcion, $miLogger) {
        $this->miConfigurador = \Configurador::singleton();
        $this->miConfigurador->fabricaConexiones->setRecursoDB('principal');
        $this->lenguaje = $lenguaje;
        $this->miSql = $sql;
        $this->miFuncion = $funcion;
        $this->miLogger= $miLogger;
    }

    function procesarFormulario() {

        $conexion="estructura";
        $esteRecursoDB=$this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

        
//var_dump($esteRecursoDB); die();
           $variable['cedula'] = $_REQUEST['cedula'];
        $this->cadena_sql = $this->miSql->getCadenaSql("buscarRegistro", $variable);
        //echo $this->cadena_sql;
        $resultadoPerfil = $esteRecursoDB->ejecutarAcceso($this->cadena_sql, "busqueda");
	
		//var_dump($resultadoPerfil); die();

        if($resultadoPerfil)
            { 
				$arreglo = array (
						"nombreEmpresa" =>  $_REQUEST["nombreEmpresa"],
						"cedula" =>  $_REQUEST["cedula"]
				);
//	var_dump($arreglo); die();		
                redireccion::redireccionar('buscarProveedor',$arreglo);  exit();
            }
        else
            {
               redireccion::redireccionar('noEncontroProveedor',$arregloDatos);  exit();
            }

  
    }

    function resetForm() {
        foreach ($_REQUEST as $clave => $valor) {

            if ($clave != 'pagina' && $clave != 'development' && $clave != 'jquery' && $clave != 'tiempo') {
                unset($_REQUEST [$clave]);
            }
        }
    }

}

$miRegistrador = new BuscarProveedor($this->lenguaje, $this->sql, $this->funcion,$this->miLogger);

$resultado = $miRegistrador->procesarFormulario();
?>