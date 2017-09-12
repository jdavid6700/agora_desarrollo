<?php
use asignacionPuntajes\salariales\premiosDocente\Sql;

$conexion = "estructura";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );

$conexionSICA = "sicapital";
$DBSICA = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexionSICA);

$conexion = 'core_central';
$coreRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
//Estas funciones se llaman desde ajax.php y estas a la vez realizan las consultas de Sql.class.php 


//-------------------------------------------------
//-------------------------------------------------
//Validación Petición AJAX Parametro SQL Injection
if(isset($_REQUEST['valor'])){

	if(is_numeric($_REQUEST['valor'])){
		$subclase = $_REQUEST['valor'];
		settype($_REQUEST['valor'], 'integer');
		$secure = true;
	}else{
		$secure = false;
	}

}

if(isset($_REQUEST['vigencia']) && isset($_REQUEST['unidad']) && isset($_REQUEST['cdpseleccion'])){
	$secure = true;
}

if(isset($_REQUEST ['numero_disponibilidad'])){
	$secure = true;
}

//-------------------------------------------------
//-------------------------------------------------

if($secure){

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
	
	if ($_REQUEST ['funcion'] == 'consultarNBC') {
		$cadenaSql = $this->sql->getCadenaSql ( 'buscarNBCAjax', $_REQUEST['valor'] );
		$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		$resultado = json_encode ( $resultado);
		echo $resultado;
	}
    if ($_REQUEST ['funcion'] == 'consultarCIIUPush') {
		$cadenaSql = $this->sql->getCadenaSql ( 'ciiuSubClaseByNumPush', $subclase);
		$datos = $coreRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		echo json_encode( $datos );
	}
    if ($_REQUEST ['funcion'] == 'consultarActividad') {
		$cadenaSql = $this->sql->getCadenaSql ( 'consultarActividades', $subclase);
		$datos = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		echo json_encode( $datos );
	}
       
	if ($_REQUEST ['funcion'] == 'consultarTipoFormaPago') {
		$cadenaSql = $this->sql->getCadenaSql ( 'consultarTipoFormaPagoByNumPush', $subclase);
		$datos = $coreRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		echo json_encode( $datos );
	}
	
	if ($_REQUEST ['funcion'] == 'ObtenersCdps') {
	
		if ($_REQUEST['cdpseleccion'] != "") {
			$seleccionados = "";
			$disponibilidades = explode(",", substr($_REQUEST['cdpseleccion'], 1));
			for ($i = 0; $i < count($disponibilidades); $i++) {
				if ($_REQUEST ['vigencia'] == explode("-", $disponibilidades[$i])[1]) {
					$seleccionados .= "," . explode("-", $disponibilidades[$i])[0];
				}
			}
			if ($seleccionados != "") {
				$seleccionados = substr($seleccionados, 1);
			} else {
				$seleccionados = 0;
			}
		} else {
			$seleccionados = 0;
		}
	
		$datos = array('unidad_ejecutora' => $_REQUEST ['unidad'], 'vigencia' => $_REQUEST ['vigencia'], 'cdps_seleccion' => $seleccionados);
		$cadenaSql = $this->sql->getCadenaSql('obtener_necesidades_vigencia', $datos);
		$resultadoItems = $DBSICA->ejecutarAcceso($cadenaSql, "busqueda");
		$resultado = json_encode($resultadoItems);
		echo $resultado;
	}
	
	if ($_REQUEST ['funcion'] == 'ObtenerInfoCdps') {
	
		$datos = array('numero_disponibilidad' => $_REQUEST ['numero_disponibilidad'],
				'vigencia' => $_REQUEST ['vigencia'], 'unidad_ejecutora' => $_REQUEST ['unidad']);
		$cadenaSql = $this->sql->getCadenaSql('obtenerInfoNec', $datos);
		$resultadoItems = $DBSICA->ejecutarAcceso($cadenaSql, "busqueda");
		$resultadoArray[0] = $resultadoItems[0];
		$cadenaSql2 = $this->sql->getCadenaSql('requisitosNecesidad', $datos);
		$resultadoItems2 = $DBSICA->ejecutarAcceso($cadenaSql2, "busqueda");
		$resultadoArray[1] = $resultadoItems2;
		$resultado = json_encode($resultadoArray);
		echo $resultado;
	}

}

?>