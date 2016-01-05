<?php

namespace usuarios\gestionUsuarios\funcion;

use usuarios\gestionUsuarios\funcion\redireccion;

include_once ('redireccionar.php');

if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
}

class RegistradorObjeto {

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
        
        $hoy = date("Y-m-d");   
	$arregloDatos = array(
                              'objetocontratar'=>$_REQUEST['objetoContrato'],
                              'codigociiu'=>$_REQUEST['codigo'],
                              'id_dependencia'=>$_REQUEST['dependencia'],
                              'fecharegistro'=>$hoy,
                              'unidad'=>$_REQUEST['unidad'],
                              'cantidad'=>$_REQUEST['cantidad'],
                              'descripcion'=>$_REQUEST['descripcion'],
                              'caracteristicas'=>$_REQUEST['caracteristicas'],
                              'id_ordenador'=>$_REQUEST['ordenador'] );
        
        

        $this->cadena_sql = $this->miSql->getCadenaSql("insertarObjeto", $arregloDatos);
        
	$resultadoUsuario = $esteRecursoDB->ejecutarAcceso($this->cadena_sql, "acceso");
       

            if($resultadoUsuario)
            {	
  /*      $mensaje="Se guardo el objeto del contrato.";
        $html="<script>alert('".$mensaje."');</script>";
        echo $html;
        echo "<script>location.replace('')</script>";    */  
        $nombreSecuencia = "proveedor.prov_objeto_contratar_id_objeto_seq";        
        $this->cadena_sql = $this->miSql->getCadenaSql("consultarSecuencia", $nombreSecuencia);
	$resultadoSecuencia = $esteRecursoDB->ejecutarAcceso($this->cadena_sql, "busqueda");                
                
           
           $arregloDatos['id_objeto']=$resultadoSecuencia[0]['last_value'];
                
                
                redireccion::redireccionar('inserto',$arregloDatos);  exit();
            }else
            {
                    redireccion::redireccionar('noInserto',$arregloDatos);  exit();
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

$miRegistrador = new RegistradorObjeto($this->lenguaje, $this->sql, $this->funcion,$this->miLogger);

$resultado = $miRegistrador->procesarFormulario();
?>