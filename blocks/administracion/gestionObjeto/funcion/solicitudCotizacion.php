<?php

namespace hojaDeVida\crearDocente\funcion;

use hojaDeVida\crearDocente\funcion\redireccionar;

include_once ('redireccionar.php');
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}
class SolicitudCotizacion {
	
	var $miConfigurador;
	var $lenguaje;
	var $miFormulario;
	var $miFuncion;
	var $miSql;
	var $conexion;
	
	function __construct($lenguaje, $sql, $funcion) {
		
		$this->miConfigurador = \Configurador::singleton ();
		$this->miConfigurador->fabricaConexiones->setRecursoDB ( 'principal' );
		$this->lenguaje = $lenguaje;
		$this->miSql = $sql;
		$this->miFuncion = $funcion;
	}
	function procesarFormulario() {
		$conexion = "estructura";
		$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
		
		$esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );
		
		$rutaBloque = $this->miConfigurador->getVariableConfiguracion ( "raizDocumento" ) . "/blocks/asignacionPuntajes/salariales/";
		$rutaBloque .= $esteBloque ['nombre'];
		$host = $this->miConfigurador->getVariableConfiguracion ( "host" ) . $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/asignacionPuntajes/salariales/" . $esteBloque ['nombre'];
		
		
        $proveedores = unserialize(stripslashes($_REQUEST['idProveedor']));

        
        
$count = count($proveedores);    
for ($i = 0; $i < $count; $i++) {
   
   $datos = array (
		$_REQUEST ['idObjeto'],
		$proveedores[$i]
    );
    //Inserto las solicitudes de cotizacion para cada proveedor
    $cadenaSql = $this->miSql->getCadenaSql ( 'ingresarCotizacion', $datos );
    $resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "insertar" );
    
    if($resultado){
        //Envio correo al preveedor

    }
}



    //actualizo estado del objeto a contratar a 2(cotizacion)
    //actualizo fecha de solicitud
	//Actualizar estado del OBJETO CONTRATO A ASIGNADA
        $parametros = array (
		'idObjeto' => $_REQUEST ['idObjeto'],
		'estado' => 2,  //solicitud de cotizacion
                'fecha' => date("Y-m-d") 
	);

    $cadenaSql = $this->miSql->getCadenaSql ( 'actualizarObjeto', $parametros );
    $resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "insertar" );


		if ($resultado) {
			redireccion::redireccionar ( 'insertoCotizacion',  $_REQUEST['idObjeto']);
			exit ();
		} else {
			redireccion::redireccionar ( 'noInserto' );
			exit ();
		}
	}
	
	function resetForm() {
		foreach ( $_REQUEST as $clave => $valor ) {
			
			if ($clave != 'pagina' && $clave != 'development' && $clave != 'jquery' && $clave != 'tiempo') {
				unset ( $_REQUEST [$clave] );
			}
		}
	}
}

$miRegistrador = new SolicitudCotizacion ( $this->lenguaje, $this->sql, $this->funcion );

$resultado = $miRegistrador->procesarFormulario ();

?>
