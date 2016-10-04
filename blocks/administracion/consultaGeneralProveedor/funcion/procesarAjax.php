<?php
use inventarios\gestionContrato\Sql;


$conexion = "estructura";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);



if ($_REQUEST ['funcion'] == 'consultaProveedor') {
	
	
	

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


if ($_REQUEST ['funcion'] == 'consultarClase') {
	$cadenaSql = $this->sql->getCadenaSql ( 'ciiuClase', $_REQUEST["valor"]);
	$datos = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
	echo json_encode( $datos );
}



if ($_REQUEST ['funcion'] == 'consultarCiudad') {
	$cadenaSql = $this->sql->getCadenaSql ( 'ciiuGrupo', $_REQUEST["valor"]);
	$datos = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
	echo json_encode( $datos );
}

if ($_REQUEST ['funcion'] == 'consultarDepartamentoAjax') {
	$cadenaSql = $this->sql->getCadenaSql ( 'buscarDepartamentoAjax', $_REQUEST['valor'] );
	$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
	$resultado = json_encode ( $resultado);
	echo $resultado;
}
if ($_REQUEST ['funcion'] == 'consultarCiudadAjax') {
	$cadenaSql = $this->sql->getCadenaSql ( 'buscarCiudadAjax', $_REQUEST['valor'] );
	$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
	$resultado = json_encode ( $resultado);
	echo $resultado;
}

if ($_REQUEST ['funcion'] == 'consultarPaisAjax') {
	$cadenaSql = $this->sql->getCadenaSql ( 'buscarPaisCod', $_REQUEST['valor'] );
	$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
	$resultado = json_encode ( $resultado);
	echo $resultado;
}

if ($_REQUEST ['funcion'] == 'consultarNomenclatura') {
	$cadenaSql = $this->sql->getCadenaSql ( 'buscarNomenclaturaAbreviatura', $_REQUEST['valor'] );
	$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
	$resultado = json_encode ( $resultado);
	echo $resultado;
}

if ($_REQUEST ['funcion'] == 'consultarNBC') {
	$cadenaSql = $this->sql->getCadenaSql ( 'buscarNBCAjax', $_REQUEST['valor'] );
	$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
	$resultado = json_encode ( $resultado);
	echo $resultado;
}





?>