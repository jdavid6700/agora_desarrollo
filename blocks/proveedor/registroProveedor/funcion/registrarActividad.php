<?php

namespace proveedor\registroProveedor\funcion;

use proveedor\registroProveedor\funcion\redireccionar;

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
	var $miLogger;
	
	function __construct($lenguaje, $sql, $funcion, $miLogger) {
		
		$this->miConfigurador = \Configurador::singleton ();
		$this->miConfigurador->fabricaConexiones->setRecursoDB ( 'principal' );
		$this->lenguaje = $lenguaje;
		$this->miSql = $sql;
		$this->miFuncion = $funcion;
		$this->miLogger= $miLogger;
	}
	
	function procesarFormulario() {
		
		
		//*************************************************************************** DBMS *******************************
		//****************************************************************************************************************
		
		$conexion = 'estructura';
		$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
		
		$conexion = 'sicapital';
		$siCapitalRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
		
		$conexion = 'centralUD';
		$centralUDRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
		
		$conexion = 'argo_contratos';
		$argoRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
		
		$conexion = 'core_central';
		$coreRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
		
		$conexion = 'framework';
		$frameworkRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
		
		//*************************************************************************** DBMS *******************************
		//****************************************************************************************************************

		
		$esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );
		
		$rutaBloque = $this->miConfigurador->getVariableConfiguracion ( "raizDocumento" ) . "/blocks/asignacionPuntajes/salariales/";
		$rutaBloque .= $esteBloque ['nombre'];
		$host = $this->miConfigurador->getVariableConfiguracion ( "host" ) . $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/asignacionPuntajes/salariales/" . $esteBloque ['nombre'];
		
		
		
		
		$SQLs = [];
		
		// ELIMINAR LAS ACTIVIDADES ECONOMICAS ACTUALES
		$cadenaSqlEliminarAct = $this->miSql->getCadenaSql ( "eliminarActividadesActuales",  $_REQUEST ['idProveedor'] );
		array_push($SQLs, $cadenaSqlEliminarAct);

		
		$actividadesArray = explode(",", $_REQUEST['idActividades']);
		// GENERAR SENTENCIAS DE REALIZACION DE REGISTRO DE ACTIVIDADES
		foreach ($actividadesArray as $dato):
			$arreglo = array (
					'fk_id_proveedor' => $_REQUEST ['idProveedor'],
					'actividad' => $dato,
					'num_documento' => $_REQUEST['numDocumento']
			);
			 
			$cadenaSqlInsertAct = $this->miSql->getCadenaSql ( "registrarActividad", $arreglo );
			array_push($SQLs, $cadenaSqlInsertAct);
		endforeach;
		
		$datos = array (
				'fk_id_proveedor' => $_REQUEST ['idProveedor'],
				'actividades' => $_REQUEST['idActividades'],
				'num_documento' => $_REQUEST['numDocumento'],
				'tipo_persona' => $_REQUEST['tipo_persona']
		);

	
		$registroActividades = $esteRecursoDB->transaccion($SQLs);
			
		if ($registroActividades) {
			
			$parametro['id_usuario'] = $_REQUEST['usuario'];
			$cadena_sql = $this->miSql->getCadenaSql("consultarUsuarios", $parametro);
			$resultadoUsuario = $frameworkRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
			$cadena_sql = $this->miSql->getCadenaSql("consultarPerfilUsuario", $parametro);
			$resultadoPerfil = $frameworkRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
			
			$c = 0;
			while ($c < count($SQLs)){
				$SQLsDec[$c] = $this->miConfigurador->fabricaConexiones->crypto->codificar($SQLs[$c]);
				$c++;
			}
			$query = json_encode($SQLsDec);
				
			$log = array('accion'=>"ACTUALIZACIÓN CIIU PERSONA ".$_REQUEST['tipo_persona'],
					'id_registro'=>$resultadoPerfil[0]['id_usuario'],
					'tipo_registro'=>"GESTION CIIU USUARIO ".$_REQUEST['tipo_persona'],
					'nombre_registro'=>"id_usuario=>".$resultadoPerfil[0]['id_usuario'].
					"|identificacion=>".$resultadoUsuario[0]['identificacion'].
					"|tipo_identificacion=>".$resultadoUsuario[0]['tipo_identificacion'].
					"|nombres=>".$resultadoUsuario[0]['nombre'].
					"|apellidos=>".$resultadoUsuario[0]['apellido'].
					"|correo=>".$resultadoUsuario[0]['correo'].
					"|telefono=>".$resultadoUsuario[0]['telefono'].
					"|subsistema=>".$resultadoPerfil[0]['id_subsistema'].
					"|perfil=>".$resultadoPerfil[0]['rol_id'].
					"|fechaIni=>".$resultadoPerfil[0]['fecha_registro'].
					"|fechaFin=>".$resultadoPerfil[0]['fecha_caduca'],
					'descripcion'=>"Modificación Actividades Económicas Usuario ".$resultadoPerfil[0]['id_usuario']." con perfil ".$resultadoPerfil[0]['rol_alias'],
					'query'=> $query,
			);
			$this->miLogger->log_usuario($log);

			redireccion::redireccionar ( 'registroActividad', $datos );
			exit ();
		} else {
			redireccion::redireccionar ( 'noregistro', $datos );
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

$miRegistrador = new Registrar ( $this->lenguaje, $this->sql, $this->funcion, $this->miLogger );

$resultado = $miRegistrador->procesarFormulario ();

?>
