<?php

namespace hojaDeVida\crearDocente\funcion;

use hojaDeVida\crearDocente\funcion\redireccionar;

include_once ('redireccionar.php');
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}
class Formulario {
	
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
		

		unset($resultado);
		//VERIFICAR SI LA CEDULA YA SE ENCUENTRA REGISTRADA
		$cadenaSql = $this->miSql->getCadenaSql ( "verificarNIT", $_REQUEST ['nit']);
		$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'busqueda' );

		if ($resultado) {
			//El proveedor ya existe
			redireccion::redireccionar ( 'existeProveedor',  $_REQUEST ['nit']);
			exit();    
		}else{
                    
				//Guardar datos PROVEEDOR
				$cadenaSql = $this->miSql->getCadenaSql ( "registrarProveedor", $_REQUEST );
				$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso' );
				
				if ($resultado) {
						//Insertar datos en la tabla USUARIO
						$_REQUEST ["contrasena"]= $this->miConfigurador->fabricaConexiones->crypto->codificarClave($_REQUEST ['nit'] );
						$_REQUEST ["tipo"] = 2;//usuario Normal
						$_REQUEST ["rolMenu"] = 9;//MENU usuario proveedor
						$_REQUEST ["estado"] = 2;//Para solicitar cambio de contraseña
						$_REQUEST ["nombre"] = $_REQUEST ["primerNombre"] . ' ' . $_REQUEST ["segundoNombre"];
						$_REQUEST ["apellido"] = $_REQUEST ["primerApellido"] . ' ' . $_REQUEST ["segundoApellido"];;
								
								//FALTA EL CAMPO DEL MENU
		
								$cadenaSql = $this->miSql->getCadenaSql ( "registrarUsuario", $_REQUEST );
								$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso'); 
		
								redireccion::redireccionar ( 'registroProveedor',  $_REQUEST['nit']);
								exit();
				} else {
								redireccion::redireccionar ( 'noregistro',  $_REQUEST['usuario']);
								exit();
				}
		
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

$miRegistrador = new Formulario ( $this->lenguaje, $this->sql, $this->funcion );

$resultado = $miRegistrador->procesarFormulario ();

?>
