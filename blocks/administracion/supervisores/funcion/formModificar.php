<?php

/*
 * To change this license header, choose License Headers in Project Properties. To change this template file, choose Tools | Templates and open the template in the editor.
 */
$esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );

$rutaBloque = $this->miConfigurador->getVariableConfiguracion ( "raizDocumento" ) . "/blocks/inventarios/";
$rutaBloque .= $esteBloque ['nombre'];
$host = $this->miConfigurador->getVariableConfiguracion ( "host" ) . $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/inventarios/" . $esteBloque ['nombre'];

$conexion = "estructura";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );


unset($resultado);
/*
//VERIFICAR SI LA CEDULA YA SE ENCUENTRA REGISTRADA
$cadenaSql = $this->sql->getCadenaSql ( "verificarCedula", $_REQUEST ['cedula']);
$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'busqueda' );
*/


//-------------------------------------------------
//-------------------------------------------------
//Validación Petición AJAX Parametro SQL Injection
if(is_numeric($_REQUEST['cedula']) && is_numeric($_REQUEST['dependencia'])){
	settype($_REQUEST['cedula'], 'integer');
	settype($_REQUEST['dependencia'], 'integer');
	
	if (ereg("[^A-Za-z0-9ñÑáéíóúÁÉÍÓÚ\s]+", $_REQUEST['nombre'])) {//Validación Petición AJAX Parametro SQL Injection
		
		if (ereg("^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,4})", $_REQUEST['correo'])) {//Validación Petición AJAX Parametro SQL Injection
					

			if(isset($_REQUEST['estado'])){//CAST
					switch($_REQUEST['estado']){
						case 1 :
							$_REQUEST['estado'] = 'ACTIVO';
							break;
						case 2 :
							$_REQUEST['estado'] = 'INACTIVO';
							break;
					}
			}


			$datosSupervisor = array (
					'cedula' =>	$_REQUEST['cedula'],
					'dependencia' => $_REQUEST['dependencia'],
					'nombre' => $_REQUEST['nombre'],
					'correo' => $_REQUEST['correo'],
					'id_proveedor' => $_REQUEST['id_proveedor'],
					'estado' => $_REQUEST['estado']
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
        $cadenaSql = $this->sql->getCadenaSql ( "actualizar", $datosSupervisor );
        $resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso' );
        
//         echo $cadenaSql;
//         var_dump($resultado);
//         exit();

        if ($resultado) {
        	
        		/*
                //Insertar datos en la tabla USUARIO
                        $_REQUEST ["contrasena"]= $this->miConfigurador->fabricaConexiones->crypto->codificarClave($_REQUEST ['cedula'] );
                        $_REQUEST ["tipo"] = 1;//Supervisor
                        $_REQUEST ["rolMenu"] = 2;//MENU SUPERVISOR
                        $_REQUEST ["estado"] = 2;//Para solicitar cambio de contraseña
                        

                        $cadenaSql = $this->sql->getCadenaSql ( "registrarUsuario", $_REQUEST );
                        $resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso'); 
                        
                */        

                        $this->funcion->Redireccionador ( 'actualizoSupervisor', $datosSupervisor );
                        exit();
        } else {
                        $this->funcion->Redireccionador ( 'noregistro', $_REQUEST['usuario'] );
                        exit();
        }

}else{
	$this->funcion->Redireccionador ( 'noregistro', $_REQUEST['usuario'] );
	exit();
}







