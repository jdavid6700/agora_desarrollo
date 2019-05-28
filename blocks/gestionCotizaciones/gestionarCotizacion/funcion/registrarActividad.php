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
                                
		$cadenaSql = $this->miSql->getCadenaSql ( "eliminarActividadActual",  $_REQUEST ['idObjeto'] );
		$resultadoEliminacion = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'busqueda' );
                
                
		$actividadesArray = explode(",", $_REQUEST['idsActividades']);
		// VERIFICAR SI YA REGISTRO LA ACTIVIDAD
		foreach ($actividadesArray as $dato):
			$arreglo = array (
				'idObjeto' => $_REQUEST ['idObjeto'],
				'actividad' => $dato,
				'tipoNecesidad' => $_REQUEST['tipoNecesidad'],
				'usuario' => $_REQUEST ['usuario'],
				'actividades' => $_REQUEST['idsActividades']
			);

			$cadenaSql = $this->miSql->getCadenaSql ( "registrarActividad", $arreglo );
			$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso' );

			array_push($SQLs, $cadenaSql);
		endforeach;
			
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
	            $numberSolicitud = "SC-" . sprintf("%05d", $_REQUEST['idObjeto']);

	            $dataBackDec = unserialize($this->miConfigurador->fabricaConexiones->crypto->decodificar($_REQUEST['dataSerCi']));
	            foreach($dataBackDec as $tabl => $param)
	            {
	                $dataBackCod[$tabl] = $this->miConfigurador->fabricaConexiones->crypto->codificar(json_encode($param[0]));
	            }
	            $data = json_encode($dataBackCod);
	                
	            $datosLog = array (
	                    'tipo_log' => 'MODIFICACION',
	                    'modulo' => 'MCIIU',
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


				redireccion::redireccionar ( 'registroActividad', $arreglo );
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
	                        'modulo' => 'MCIIU',
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
