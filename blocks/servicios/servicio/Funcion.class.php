<?php

namespace servicios\servicio;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}

include_once ("core/manager/Configurador.class.php");
include_once ("core/builder/InspectorHTML.class.php");
include_once ("core/builder/Mensaje.class.php");
include_once ("core/crypto/Encriptador.class.php");

// Esta clase contiene la logica de negocio del bloque y extiende a la clase funcion general la cual encapsula los
// metodos mas utilizados en la aplicacion

// Para evitar redefiniciones de clases el nombre de la clase del archivo funcion debe corresponder al nombre del bloque
// en camel case precedido por la palabra Funcion
class Funcion {
	var $sql;
	var $funcion;
	var $lenguaje;
	var $ruta;
	var $miConfigurador;
	var $error;
	var $miRecursoDB;
	var $crypto;
	
	function servicioArgoProveedor() {
		include_once ($this->ruta . "funcion/servicioArgoProveedor.php");
	}
	function servicioArkaProveedor() {
		include_once ($this->ruta . "funcion/servicioArkaProveedor.php");
	}
	function procesarAjax() {
		include_once ($this->ruta . "funcion/procesarAjax.php");
	}
	
	function codifica_utf8($dat) // -- It returns $dat encoded to UTF8
	{
		if (is_string($dat)) return utf8_encode($dat);
		if (!is_array($dat)) return $dat;
		$ret = array();
		foreach($dat as $i=>$d) $ret[$i] = $this->codifica_utf8($d);
		return $ret;
	}
	
	function decodifica_utf8($dat) // -- It returns $dat decoded from UTF8
	{
		if (is_string($dat)) return utf8_decode($dat);
		if (!is_array($dat)) return $dat;
		$ret = array();
		foreach($dat as $i=>$d) $ret[$i] = $this->decodifica_utf8($d);
		return $ret;
	}
	
	function deliver_response($status,$status_message,$data){
				
				header("HTTP/1.1 $status."-".$status_message");
				echo "<json>";
				$response['status']=$status;
				$response['message']= $status_message;
				$response['datos']= $data;
			    $json_response = json_encode($response);
				echo $json_response;
				echo "<json>";
				
				/*Acentos DECODIFICA UTF8 Arreglo JSON
				$jsondata = $json_response;
				$obj = json_decode($jsondata, true);
				var_dump($this->decodifica_utf8($obj));
				*/
				
	}
	function action() {
		$resultado = true;
		
		// Aquí se coloca el código que procesará los diferentes formularios que pertenecen al bloque
		// aunque el código fuente puede ir directamente en este script, para facilitar el mantenimiento
		// se recomienda que aqui solo sea el punto de entrada para incluir otros scripts que estarán
		// en la carpeta funcion
		
		// Importante: Es adecuado que sea una variable llamada opcion o action la que guie el procesamiento:
		
		if (isset ( $_REQUEST ['procesarAjax'] )) {
			$this->procesarAjax ();
		} elseif (isset ( $_REQUEST ['servicio'] )) {
			
			switch ($_REQUEST ['servicio']) {
				
				case 'servicioArgoProveedor' :
					$resultado = $this->servicioArgoProveedor ();
					break;
					
				case 'servicioArkaProveedor' :
					$resultado = $this->servicioArkaProveedor ();
					break;
			}
		}
		
		return $resultado;
	}
	function __construct() {
		$this->miConfigurador = \Configurador::singleton ();
		
		$this->ruta = $this->miConfigurador->getVariableConfiguracion ( "rutaBloque" );
		
		$this->miMensaje = \Mensaje::singleton ();
		
		$conexion = "aplicativo";
		$this->miRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
		
		if (! $this->miRecursoDB) {
			
			$this->miConfigurador->fabricaConexiones->setRecursoDB ( $conexion, "tabla" );
			$this->miRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
		}
	}
	public function setRuta($unaRuta) {
		$this->ruta = $unaRuta;
	}
	public function setSql($a) {
		$this->sql = $a;
	}
	function setFuncion($funcion) {
		$this->funcion = $funcion;
	}
	public function setFormulario($formulario) {
		$this->formulario = $formulario;
	}
}

?>
