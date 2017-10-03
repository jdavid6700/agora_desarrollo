<?php

/*
 * To change this license header, choose License Headers in Project Properties. To change this template file, choose Tools | Templates and open the template in the editor.
 */
$esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );

$rutaBloque = $this->miConfigurador->getVariableConfiguracion ( "raizDocumento" ) . "/blocks/inventarios/";
$rutaBloque .= $esteBloque ['nombre'];
$host = $this->miConfigurador->getVariableConfiguracion ( "host" ) . $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/inventarios/" . $esteBloque ['nombre'];


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


unset($resultado);
/*
//VERIFICAR SI LA CEDULA YA SE ENCUENTRA REGISTRADA
$cadenaSql = $this->sql->getCadenaSql ( "verificarCedula", $_REQUEST ['cedula']);
$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'busqueda' );
*/

//-------------------------------------------------
//-------------------------------------------------
//Validación Petición AJAX Parametro SQL Injection
if(isset($_REQUEST['cedula']) && is_numeric($_REQUEST['dependencia'])){
	//settype($_REQUEST['cedula'], 'integer');
	settype($_REQUEST['dependencia'], 'integer');
	
	if (ereg("[^A-Za-z0-9ñÑáéíóúÁÉÍÓÚ\s]+", $_REQUEST['nombre'])) {//Validación Petición AJAX Parametro SQL Injection
		
		if (preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $_REQUEST['correo'])) {//Validación Petición AJAX Parametro SQL Injection
					
			$datosSupervisor = array (
					'cedula' =>	$_REQUEST['cedula'],
					'dependencia' => $_REQUEST['dependencia'],
					'nombre' => $_REQUEST['nombre'],
					'correo' => $_REQUEST['correo'],
					'id_proveedor' => $_REQUEST['id_proveedor']
			);
			
			$secure = true;
			
		}else{
			
			$secure = false;
		}
		
	}else{
		
		$secure = false;
	}
	
	
}else{
	
	$secure = false;
}
//-------------------------------------------------
//-------------------------------------------------

if ($secure) {
	
        //Guardar datos
        $cadenaSql = $this->sql->getCadenaSql ( "registrar", $datosSupervisor );
        $resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso' );


        $cadenaSql = $this->sql->getCadenaSql ( "consultarUsuario", $datosSupervisor );
        $resultadoUser = $frameworkRecursoDB->ejecutarAcceso ( $cadenaSql, 'busqueda' );

        //********** PERFIL DE SUPERVISOR ********************************
        //****************************************************************
        	$hoy = date("Y-m-d");
        	$_REQUEST['id_usuario'] = $resultadoUser[0]['id_usuario']; 
			$_REQUEST['subsistema'] = 5;
			$_REQUEST['perfil'] = 10;
			$_REQUEST['fechaFin'] = "2020-12-12";
		//****************************************************************

									$arregloDatos = array(
								                              'id_usuario'=>$_REQUEST['id_usuario'],
								                              'subsistema'=>$_REQUEST['subsistema'],
								                              'perfil'=>$_REQUEST['perfil'],
								                              'fechaIni'  =>$hoy,
								                              'fechaFin'  =>$_REQUEST['fechaFin'] 
								                         );

        $cadenaSql = $this->sql->getCadenaSql("insertarPerfilUsuario", $arregloDatos);
		$resultadoPerfil = $frameworkRecursoDB->ejecutarAcceso($cadenaSql, "acceso");

		//********** PERFIL DE SUPERVISOR ********************************
        //****************************************************************

        if ($resultado && $resultadoPerfil) {
        	
        		/*
                //Insertar datos en la tabla USUARIO
                        $_REQUEST ["contrasena"]= $this->miConfigurador->fabricaConexiones->crypto->codificarClave($_REQUEST ['cedula'] );
                        $_REQUEST ["tipo"] = 1;//Supervisor
                        $_REQUEST ["rolMenu"] = 2;//MENU SUPERVISOR
                        $_REQUEST ["estado"] = 2;//Para solicitar cambio de contraseña
                        

                        $cadenaSql = $this->sql->getCadenaSql ( "registrarUsuario", $_REQUEST );
                        $resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso'); 
                        
                */        

                        $this->funcion->Redireccionador ( 'registroSupervisor', $datosSupervisor );
                        exit();
        } else {
                        $this->funcion->Redireccionador ( 'noregistro', $_REQUEST['usuario'] );
                        exit();
        }

}else{
	$this->funcion->Redireccionador ( 'noregistro', $_REQUEST['usuario'] );
	exit();
}







