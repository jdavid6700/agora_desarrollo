<?php

namespace gestionParametros\gestionarSupervisor;

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
	var $miInspectorHTML;
	var $error;
	var $miRecursoDB;
	var $crypto;
// 	function verificarCampos() {
// 		include_once ($this->ruta . "/funcion/verificarCampos.php");
// 		if ($this->error == true) {
// 			return false;
// 		} else {
// 			return true;
// 		}
// 	}
	function formProcessor() {
		include_once ($this->ruta . "/funcion/formProcessor.php");
	}
	function procesarAjax() {
		include_once ($this->ruta . "funcion/procesarAjax.php");
	}
	function consultarContrato() {
		include_once ($this->ruta . "/funcion/consultarContrato.php");
	}
	function modificarContrato() {
		include_once ($this->ruta . "/funcion/modificarContrato.php");
	}
	function actualizarJuridica() {
		include_once ($this->ruta . "/funcion/actualizarJuridica.php");
	}
	function actualizarNatural() {
		include_once ($this->ruta . "/funcion/actualizarNatural.php");
	}
	function registrarFuncionario() {
		include_once ($this->ruta . "/funcion/registrarFuncionario.php");
	}
	function modificarFuncionario() {
		include_once ($this->ruta . "/funcion/modificarFuncionario.php");
	}



	
	function action() {

		// Evitar que se ingrese codigo HTML y PHP en los campos de texto
		// Campos que se quieren excluir de la limpieza de código. Formato: nombreCampo1|nombreCampo2|nombreCampo3
		$excluir = "";
		$_REQUEST = $this->miInspectorHTML->limpiarPHPHTML ( $_REQUEST );
		
		// Aquí se coloca el código que procesará los diferentes formularios que pertenecen al bloque
		// aunque el código fuente puede ir directamente en este script, para facilitar el mantenimiento
		// se recomienda que aqui solo sea el punto de entrada para incluir otros scripts que estarán
		// en la carpeta funcion
		// Importante: Es adecuado que sea una variable llamada opcion o action la que guie el procesamiento:
		$_REQUEST = $this->miInspectorHTML->limpiarSQL ( $_REQUEST );
		// Realizar una validación específica para los campos de este formulario:
		// $validacion = $this->verificarCampos ();
		// if ($validacion == false) {
		// // Instanciar a la clase pagina con mensaje de correcion de datos
		// echo "Datos Incorrectos";
		// } else {
		// Validar las variables para evitar un tipo insercion de SQL
		// $this->Redireccionador( "exito" );
		// }
		
		if (isset ( $_REQUEST ['procesarAjax'] )) {
			$this->procesarAjax ();
		} else if (isset ( $_REQUEST ["opcion"] )) {
			
			switch ($_REQUEST ["opcion"]) {
				case 'consultar' :
					$this->consultarContrato ();
					break;
				
				case 'guardarInhabilidad' :
					$this->formProcessor ();
					break;
				
				case 'documentoModificar' :
					$this->modificarContrato ();
					break;

				case 'registroFuncionario' :
					$this->registrarFuncionario ();
					break;	
				case 'modificoFuncionario' :
					$this->modificarFuncionario ();
					break;		
					
					
				case 'actualizar' :
					if (isset ( $_REQUEST ["botonRegresar"] ) && $_REQUEST ["botonRegresar"] == 'true') {
						$arreglo = unserialize ( $_REQUEST ['arreglo'] );
						redireccion::redireccionar ( "paginaConsulta", $arreglo );
						exit ();
					} else if (isset ( $_REQUEST ["botonGuardar"] ) && $_REQUEST ["botonGuardar"] == 'true') {
						$this->actualizarJuridica ();
					} else if (isset ( $_REQUEST ["botonGuardarNat"] ) && $_REQUEST ['botonGuardarNat'] == 'true') {
						$this->actualizarNatural ();
					}
					break;
			}
		} else {
			echo "request opcion no existe";
		}
	}
	function __construct() {
		$this->miConfigurador = \Configurador::singleton ();
		
		$this->miInspectorHTML = \InspectorHTML::singleton ();
		
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
	function setSql($a) {
		$this->sql = $a;
	}
	function setFuncion($funcion) {
		$this->funcion = $funcion;
	}
	public function setLenguaje($lenguaje) {
		$this->lenguaje = $lenguaje;
	}
	public function setFormulario($formulario) {
		$this->formulario = $formulario;
	}
	function Redireccionador($opcion, $valor = "") {
		include_once ($this->ruta . "/funcion/Redireccionador.php");
	}
}

?>
