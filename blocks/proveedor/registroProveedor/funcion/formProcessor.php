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


var_dump($_REQUEST);exit;

//VERIFICAR SI LA CEDULA YA SE ENCUENTRA REGISTRADA
$cadenaSql = $this->sql->getCadenaSql ( "verificarNIT", $_REQUEST ['nit']);
echo $cadenaSql;
$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso' );

if ($resultado) {
        //El proveedor ya existe
	$this->funcion->Redireccionador ( 'existeProveedor', $_REQUEST ['nit'] );
	exit();    
}else{
    
        //Guardar datos
        $cadenaSql = $this->sql->getCadenaSql ( "registrarProveedor", $_REQUEST );
        
        echo $cadenaSql; exit;
        $resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso' );

        if ($resultado) {
                //Insertar datos en la tabla USUARIO
                        $_REQUEST ["contrasena"]= $this->miConfigurador->fabricaConexiones->crypto->codificarClave($_REQUEST ['cedula'] );
                        $_REQUEST ["tipo"] = 2;//Supervisor
                        $_REQUEST ["estado"] = 2;//Para solicitar cambio de contraseña

                        $cadenaSql = $this->sql->getCadenaSql ( "registrarUsuario", $_REQUEST );
                        $resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso'); 

                        $this->funcion->Redireccionador ( 'registroSupervisor', $_REQUEST['cedula'] );
                        exit();
        } else {
                        $this->funcion->Redireccionador ( 'noregistro', $_REQUEST['usuario'] );
                        exit();
        }

        
}