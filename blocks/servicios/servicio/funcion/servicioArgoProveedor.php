<?php
use servicios\servicio\Funcion;

$conexion = "estructura";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );


if ( isset($_REQUEST ['servicio']) && $_REQUEST ['servicio'] != '') {
	
	//header("Content-Type:application/json");
    
	$cadena_sql = $this->sql->getCadenaSql ( "informacion_por_proveedor", $_REQUEST['variable'] );
	$resultado = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
	
	//var_dump($resultado[0]);
	
	//$resultado = json_encode($resultado[0]);
	
	if($resultado != false){
		$this->deliver_response(200,"Proveedores Encontrados",$resultado[0]);
	}else{
		$this->deliver_response(300,"No se encontraron proveedores",null);
	}

}else{

 $this->deliver_response(400,"Peticion Invalida",null);

}


