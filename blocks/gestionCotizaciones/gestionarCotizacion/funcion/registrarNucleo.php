<?php

namespace hojaDeVida\crearDocente\funcion;

use hojaDeVida\crearDocente\funcion\redireccionar;

include_once ('redireccionar.php');
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}
class Registrar {
	
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

		$conexion = 'framework';
		$frameworkRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
		
		$esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );
		
		$rutaBloque = $this->miConfigurador->getVariableConfiguracion ( "raizDocumento" ) . "/blocks/asignacionPuntajes/salariales/";
		$rutaBloque .= $esteBloque ['nombre'];
		$host = $this->miConfigurador->getVariableConfiguracion ( "host" ) . $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/asignacionPuntajes/salariales/" . $esteBloque ['nombre'];
		
		$SQLs = [];

		$arreglo = array (
				'idObjeto' => $_REQUEST ['idObjeto'],
				'objetoNBC' => $_REQUEST ['objetoNBC'],
				'tipoNecesidad' => $_REQUEST['tipoNecesidad'],
				'modificarNBC' => $_REQUEST ['modificarNBC'],
				'usuario' => $_REQUEST ['usuario']
		);
		
		if($_REQUEST ['modificarNBC']){
			
			// Modificar NUCLEO BASICO
			$cadenaSql = $this->miSql->getCadenaSql ( "actualizarNucleoBasico", $arreglo );
			$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso' );
			
			if(!$resultado){//Falla en el Registro Inicial (Mal Uso del Usuario)
				// Guardar NUCLEO BASICO
				$cadenaSql = $this->miSql->getCadenaSql ( "registrarNucleoBasico", $arreglo );
				$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso' );
			}
			
			
		}else{
				
			// Guardar NUCLEO BASICO
			$cadenaSql = $this->miSql->getCadenaSql ( "registrarNucleoBasico", $arreglo );
			$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso' );

			
		}
		array_push($SQLs, $cadenaSql);
			
			if ($resultado) {

				if (!empty($_SERVER['HTTP_CLIENT_IP'])){
					$ip = $_SERVER['HTTP_CLIENT_IP'];
				}elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
					$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
				}else{
					$ip = $_SERVER['REMOTE_ADDR'];
				}
				$c = 0;
				while ($c < count($SQLs)){
					$SQLsDec[$c] = $this->miConfigurador->fabricaConexiones->crypto->codificar($SQLs[$c]);
					$c++;
				}
				$query = json_encode($SQLsDec);
				$numberSolicitud = "SC-" . sprintf("%05d", $_REQUEST ['idObjeto']);

				$dataBackDec = unserialize($this->miConfigurador->fabricaConexiones->crypto->decodificar($_REQUEST['dataSerNb']));
	            foreach($dataBackDec as $tabl => $param)
	            {
	                $dataBackCod[$tabl] = $this->miConfigurador->fabricaConexiones->crypto->codificar(json_encode($param[0]));
	            }
	            $data = json_encode($dataBackCod);
					
				$datosLog = array (
						'tipo_log' => 'MODIFICACION',
						'modulo' => 'MNBC',
						'numero_cotizacion' => $numberSolicitud,
						'vigencia' => date("Y"),
						'query' => $query,
						'data' => $data,
						'host' => $ip,
						'fecha_log' => date("Y-m-d H:i:s"),
						'usuario' => $_REQUEST ['usuario']
				);
				$cadenaSQL = $this->miSql->getCadenaSql("insertarLogCotizacionUp", $datosLog);
				$resultadoLog = $frameworkRecursoDB->ejecutarAcceso($cadenaSQL, 'busqueda');

				redireccion::redireccionar ( 'registroNucleo', $arreglo );
				exit ();
			} else {


					if (!empty($_SERVER['HTTP_CLIENT_IP'])){
	                    $ip = $_SERVER['HTTP_CLIENT_IP'];
	                }elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
	                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	                }else{
	                    $ip = $_SERVER['REMOTE_ADDR'];
	                }
	                $c = 0;
	                while ($c < count($SQLs)){
	                    $SQLsDec[$c] = $this->miConfigurador->fabricaConexiones->crypto->codificar($SQLs[$c]);
	                    $c++;
	                }
	                $query = json_encode($SQLsDec);
	                $numberSolicitud = "SC-" . sprintf("%05d", $_REQUEST['idObjeto']);
	                $error = json_encode(error_get_last());
	                
	                $datosLog = array (
	                        'tipo_log' => 'MODIFICACION',
	                        'modulo' => 'MNBC',
	                        'numero_cotizacion' => $numberSolicitud,
	                        'vigencia' => date("Y"),
	                        'query' => $query,
	                        'error' => $error,
	                        'host' => $ip,
	                        'fecha_log' => date("Y-m-d H:i:s"),
	                        'usuario' => $_REQUEST ['usuario']
	                );
	                $cadenaSQL = $this->miSql->getCadenaSql("insertarLogCotizacionError", $datosLog);
	                $resultadoLog = $frameworkRecursoDB->ejecutarAcceso($cadenaSQL, 'busqueda');
	                    
	                $caso = "RCL-" . date("Y") . "-" . $resultadoLog[0][0];

	            redireccion::redireccionar('noInserto', $caso);
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

$miRegistrador = new Registrar ( $this->lenguaje, $this->sql, $this->funcion );

$resultado = $miRegistrador->procesarFormulario ();

?>
