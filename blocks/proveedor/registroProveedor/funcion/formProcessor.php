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
//VERIFICAR SI LA CEDULA YA SE ENCUENTRA REGISTRADA
$cadenaSql = $this->sql->getCadenaSql ( "verificarNIT", $_REQUEST ['nit']);
$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'busqueda' );


if ($resultado) {
	//El proveedor ya existe
	$this->funcion->Redireccionador ( 'existeProveedor', $_REQUEST ['nit'] );
	exit();    
}else{
        //Guardar datos PROVEEDOR
        $cadenaSql = $this->sql->getCadenaSql ( "registrarProveedor", $_REQUEST );
		$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso' );

        if ($resultado) {
                //Insertar datos en la tabla USUARIO
                        $_REQUEST ["contrasena"]= $this->miConfigurador->fabricaConexiones->crypto->codificarClave($_REQUEST ['nit'] );
                        $_REQUEST ["tipo"] = 2;//usuario Normal
						$_REQUEST ["rolMenu"] = 9;//MENU
                        $_REQUEST ["estado"] = 2;//Para solicitar cambio de contraseña
						$_REQUEST ["nombre"] = $_REQUEST ["primerNombre"] . ' ' . $_REQUEST ["segundoNombre"];
						$_REQUEST ["apellido"] = $_REQUEST ["primerApellido"] . ' ' . $_REQUEST ["segundoApellido"];;
						
						//FALTA EL CAMPO DEL MENU

                        $cadenaSql = $this->sql->getCadenaSql ( "registrarUsuario", $_REQUEST );
                        $resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso'); 

                        $this->funcion->Redireccionador ( 'registroProveedor', $_REQUEST['nit'] );
                        exit();
        } else {
                        $this->funcion->Redireccionador ( 'noregistro', $_REQUEST['usuario'] );
                        exit();
        }

        
}