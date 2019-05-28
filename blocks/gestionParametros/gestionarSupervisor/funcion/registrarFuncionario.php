<?php

namespace gestionParametros\gestionarSupervisor\funcion;

use gestionParametros\gestionarSupervisor\funcion\redireccion;

include_once ('redireccionar.php');

if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
}

class RegistradorFuncionario {

    var $miConfigurador;
    var $lenguaje;
    var $miFormulario;
    var $miFuncion;
    var $miSql;
    var $conexion;

    function __construct($lenguaje, $sql, $funcion) {
        $this->miConfigurador = \Configurador::singleton();
        $this->miConfigurador->fabricaConexiones->setRecursoDB('principal');
        $this->lenguaje = $lenguaje;
        $this->miSql = $sql;
        $this->miFuncion = $funcion;
    }

    function cambiafecha_format($fecha) {
      ereg("([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})", $fecha, $mifecha);
      $fechana = $mifecha[3] . "-" . $mifecha[2] . "-" . $mifecha[1];
      return $fechana;
    }

    function procesarFormulario() {

      $conexion = 'estructura';
      $esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
      $conexion = 'core_central';
      $coreRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

      $SQLs = [];


     

      $fechaInicio = $this->cambiafecha_format($_REQUEST['fecha_inicio']);
      $fechaFin = $this->cambiafecha_format($_REQUEST['fecha_fin']);

      $arregloDatos = array(
          'fecha_inicio' => $fechaInicio,
          'fecha_fin' => $fechaFin,
          'tercero' => $_REQUEST['id_proveedor'],
          'dependencia' => $_REQUEST['dependencia_hidden'],
          'acta' => $_REQUEST['acta_aprobacion'],
          'tipoFuncionario' =>  $_REQUEST['tipoFuncionario'],
          'nombreDependencia' =>  $_REQUEST['nombreDependencia'],
          'nombreFuncionario' =>  $_REQUEST['nit_proveedor'],
          'dependencia' => $_REQUEST['dependencia_hidden'],
          'usuario' => $_REQUEST['usuario'] 
      );


      $funcionarioCont = $this->miSql->getCadenaSql ( 'registrarFuncionarioCore', $arregloDatos );
      array_push($SQLs, $funcionarioCont);

      $registroFuncionarioCont = $coreRecursoDB->transaccion($SQLs);

    if($registroFuncionarioCont){
            redireccion::redireccionar('inserto',$arregloDatos);  
            exit();
        }else{       
            redireccion::redireccionar('noInserto',$arregloDatos);  
            exit();
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

$miRegistrador = new RegistradorFuncionario($this->lenguaje, $this->sql, $this->funcion);

$resultado = $miRegistrador->procesarFormulario();
?>