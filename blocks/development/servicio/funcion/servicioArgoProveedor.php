<?php
use development\servicio\Funcion;

$conexion = "estructura";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );

if ( isset($_REQUEST ['servicio']) && $_REQUEST ['servicio'] != '') {
    
	$cadena_sql = $this->sql->getCadenaSql ( "informacion_proveedor" );
	$resultado = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
	
	if($resultado != false){
		$this->deliver_response(200,"Proveedores Encontrados",$resultado);
	}else{
		$this->deliver_response(200,"No se encontraron proveedores",null);
	}

}else{

 $this->deliver_response(400,"Peticion Invalidad",null);

}


