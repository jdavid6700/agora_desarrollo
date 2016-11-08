<?php
use inventarios\gestionContrato\Sql;


$conexion = "estructura";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);



/*
if ($_REQUEST ['funcion'] == 'consultaProveedor') {
	
	var_dump(PASSSS);
	
	$cadenaSql = $this->sql->cadena_sql ( 'buscar_Proveedores', $_GET ['query'] );

	$resultadoItems = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );

	foreach ( $resultadoItems as $key => $values ) {
		$keys = array (
				'value',
				'data'
		);
		$resultado [$key] = array_intersect_key ( $resultadoItems [$key], array_flip ( $keys ) );
	}

	echo '{"suggestions":' . json_encode ( $resultado ) . '}';
}

if ($_REQUEST ['funcion'] == 'consultarProveedorFiltro') {
	$cadenaSql = $this->sql->getCadenaSql('buscarProveedoresFiltro', $_GET ['query']);
	$resultadoItems = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
	foreach ($resultadoItems as $key => $values) {
		$keys = array(
				'value',
				'data'
		);
		$resultado [$key] = array_intersect_key($resultadoItems [$key], array_flip($keys));
	}

	echo '{"suggestions":' . json_encode($resultado) . '}';
}
*/


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





?>