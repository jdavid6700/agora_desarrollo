<?php
use inventarios\gestionContrato\Sql;


$conexion = "estructura";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

//-------------------------------------------------
//-------------------------------------------------
//Validación Petición AJAX Parametro SQL Injection
//if(isset($_REQUEST['valor']) && is_numeric($_REQUEST['valor'])){//se elimina para permitir tipo de datos varchar
  if(isset($_REQUEST['valor']) && $_REQUEST['valor']!=''){
	//settype($_REQUEST['valor'], 'integer');//se elimina por tipo de datos 
	$secure = true;
}else{
	$secure = false;
}
//-------------------------------------------------
//-------------------------------------------------


if ($_REQUEST ['funcion'] == 'consultarProveedorFiltro') {
	
	if (!ereg("[^A-Za-z0-9()ñÑáéíóúÁÉÍÓÚ\s\.\-]+", $_GET ['query'])) {//Validación Petición AJAX Parametro SQL Injection
		$cadenaSql = $this->sql->getCadenaSql('buscarProveedoresFiltro', $_GET ['query']);
		$resultadoItems = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
		
		if(isset($resultadoItems) && $resultadoItems != null){
			foreach ($resultadoItems as $key => $values) {
				$keys = array(
						'value',
						'data'
				);
				$resultado [$key] = array_intersect_key($resultadoItems [$key], array_flip($keys));
			}
			echo '{"suggestions":' . json_encode($resultado) . '}';
		}
	}
}


if($secure){
	if ($_REQUEST ['funcion'] == 'consultarPersona') {
		$cadenaSql = $this->sql->getCadenaSql ( 'consultarPersonaNatural', $_REQUEST["valor"]);
		$datos = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		echo json_encode( $datos );
	}
	
}





?>