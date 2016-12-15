<?php
use servicios\servicio\Funcion;
use core\general\ValidadorCampos;



if ( isset($_REQUEST ['servicio']) && $_REQUEST ['servicio'] != '') {
	
	//header("Content-Type:application/json");
	
	
	
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
	
	
	
	
	//-------------------------------------------------
	//-------------------------------------------------
	//Validación Petición AJAX Parametro SQL Injection
	if(is_numeric($_REQUEST['parametro1'])){
		settype($_REQUEST['parametro1'], 'integer');
		$secure = true;
	}else{
		$secure = false;
	}
	//-------------------------------------------------
	//-------------------------------------------------
	
	if($secure){
		
		
		
		$cadena_sql = $this->sql->getCadenaSql ( "consultar_tipo_proveedor", $_REQUEST['parametro1'] );
		$resultadoTipo = $centralUDRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
		
		if($resultadoTipo[0]['tipopersona'] == 'JURIDICA'){
		
			$cadena_sql = $this->sql->getCadenaSql ( "informacion_por_proveedor_juridica", $_REQUEST['parametro1'] );
			$resultado = $centralUDRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
		
			$cadena_sql = $this->sql->getCadenaSql ( "consultarContactoTelProveedor", $_REQUEST['parametro1'] );
			$resultadoTel = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
			$resultado[0][46] = $resultadoTel[0]['numero_tel'];
			$resultado[0]['telefono_empresa'] = $resultadoTel[0]['numero_tel'];
			$resultado[0][47] = $resultadoTel[0]['extension'];
			$resultado[0]['telefono_extension_empresa'] = $resultadoTel[0]['extension'];
		
		
			$cadena_sql = $this->sql->getCadenaSql ( "consultarContactoMovilProveedor", $_REQUEST['parametro1'] );
			$resultadoMovil = $centralUDRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
			$resultado[0][48] = $resultadoMovil[0]['numero_tel'];
			$resultado[0]['movil_empresa'] = $resultadoMovil[0]['numero_tel'];
		
		
			$tamaño = count($resultado[0])/2;
			for($x=0; $x <= $tamaño; $x++){
				unset($resultado[0][$x]);
			}
		
			//var_dump($resultado[0]);
			$datos = $this->codifica_utf8($resultado[0]);
		
		}else if($resultadoTipo[0]['tipopersona'] == 'NATURAL'){
			$cadena_sql = $this->sql->getCadenaSql ( "informacion_por_proveedor_natural", $_REQUEST['parametro1'] );
			$resultado = $centralUDRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
		
			$cadena_sql = $this->sql->getCadenaSql ( "consultarContactoTelProveedor", $_REQUEST['parametro1'] );
			$resultadoTel = $centralUDRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
			$resultado[0][26] = $resultadoTel[0]['numero_tel'];
			$resultado[0]['telefono_persona_natural'] = $resultadoTel[0]['numero_tel'];
			$resultado[0][27] = $resultadoTel[0]['extension'];
			$resultado[0]['telefono_extension_persona_natural'] = $resultadoTel[0]['extension'];
		
		
			$cadena_sql = $this->sql->getCadenaSql ( "consultarContactoMovilProveedor", $_REQUEST['parametro1'] );
			$resultadoMovil = $centralUDRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
			$resultado[0][28] = $resultadoMovil[0]['numero_tel'];
			$resultado[0]['movil_persona_natural'] = $resultadoMovil[0]['numero_tel'];
		
		
			$tamaño = count($resultado[0])/2;
			for($x=0; $x <= $tamaño; $x++){
				unset($resultado[0][$x]);
			}
		
			//var_dump($resultado[0]);
			$datos = $this->codifica_utf8($resultado[0]);
		}
		
		
		//var_dump($resultado[0]);
		//$resultado = json_encode($resultado[0]);
		
		if($resultado != false){
			$this->deliver_response(200,"Proveedores Encontrados",$datos);
		}else{
			$this->deliver_response(300,"No se encontraron proveedores",null);
		}
		
		
		
	}else{
		$this->deliver_response(400,"Peticion Invalida",null);
	}
	
    


}else{

 $this->deliver_response(400,"Peticion Invalida",null);

}


