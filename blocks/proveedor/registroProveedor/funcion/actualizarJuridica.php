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
		
		$rutaBloque = $this->miConfigurador->getVariableConfiguracion ( "raizDocumento" ) . "/blocks/proveedor/";
		$rutaBloque .= $esteBloque ['nombre'];
		$host = $this->miConfigurador->getVariableConfiguracion ( "host" ) . $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/proveedor/" . $esteBloque ['nombre'];
		

		unset($resultado);
		
		
		
// Guardar el archivo
if ($_FILES) {
	foreach ( $_FILES as $key => $values ) {
		$archivo = $_FILES [$key];
	}
	// obtenemos los datos del archivo
	$tamano = $archivo ['size'];
	$tipo = $archivo ['type'];
	$archivo1 = $archivo ['name'];
	$prefijo = substr ( md5 ( uniqid ( rand () ) ), 0, 6 );
	$nombreDoc = $prefijo . "-" . $archivo1;
	
	if ($archivo1 != "") {
		// guardamos el archivo a la carpeta files
		$destino = $rutaBloque . "/files/" . $nombreDoc;

		if (copy ( $archivo ['tmp_name'], $destino )) {
			$status = "Archivo subido: <b>" . $archivo1 . "</b>";
			$_REQUEST['destino'] = $host . "/files/" . $prefijo . "-" . $archivo1;
				//Actualizar RUT
				$cadenaSql = $this->miSql->getCadenaSql ( "actualizarRUT", $_REQUEST );
				$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso' );			
		} else {
			$status = "<br>Error al subir el archivo1";
		}
	} else {
		$status = "<br>Error al subir archivo2";
	}
} else {
	echo "<br>NO existe el archivo D:!!!";
}		

				//Guardar datos PROVEEDOR
				$cadenaSql = $this->miSql->getCadenaSql ( "actualizarProveedor", $_REQUEST );
				$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso' );

				if ($resultado) {
					redireccion::redireccionar ( 'actualizo',  $_REQUEST['nit']);
					exit();
				} else {
					redireccion::redireccionar ( 'noActualizo',  $_REQUEST['nit']);
					exit();
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
