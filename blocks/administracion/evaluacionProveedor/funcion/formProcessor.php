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

$resultado = '';

$fechaActual = date ( 'Y-m-d' );
//INICIO Calculo puntaje total
$puntajeTotal = 0;
$puntajeTotal = $_REQUEST ['tiempoEntrega'] + $_REQUEST ['cantidades'] + $_REQUEST ['conformidad'] + $_REQUEST ['funcionalidadAdicional'];
$_REQUEST ['reclamaciones'] = $_REQUEST ['reclamacionSolucion']==12?0:$_REQUEST ['reclamaciones'];
$puntajeTotal = $puntajeTotal + $_REQUEST ['reclamaciones'] + $_REQUEST ['reclamacionSolucion'] + $_REQUEST ['servicioVenta'] + $_REQUEST ['procedimientos'];
$_REQUEST ['garantia'] = $_REQUEST ['garantiaSatisfaccion']==15?0:$_REQUEST ['garantia'];
$puntajeTotal =  $puntajeTotal + $_REQUEST ['garantia'] + $_REQUEST ['garantiaSatisfaccion'];
//FIN Calculo puntaje total

//INICIO CALCULO CLASIFICACION
    function clasificacion($puntajeTotal = '') {
		if( $puntajeTotal > 79 )
			$valor = "A";
		elseif( $puntajeTotal > 45 )
			$valor = "B";
		else $valor = "C";
        return $valor;
    }
	$clasificacion = clasificacion($puntajeTotal); 
	
//FIN CALCULO CLASIFICACION
						
//Cargo array con los datos para insertar en la table evaluacionProveedor
$arreglo = array (
		$_REQUEST ['idContrato'],
		$fechaActual,
		$_REQUEST ['tiempoEntrega'],
		$_REQUEST ['cantidades'],
		$_REQUEST ['conformidad'],
		$_REQUEST ['funcionalidadAdicional'],
		$_REQUEST ['reclamaciones'],
		$_REQUEST ['reclamacionSolucion'],
		$_REQUEST ['servicioVenta'],		
		$_REQUEST ['procedimientos'],
		$_REQUEST ['garantia'],		
		$_REQUEST ['garantiaSatisfaccion'],
		$puntajeTotal,
		$clasificacion,
		1
);//FALTA EL ID DEL SUPERVISOR


//Guardar datos de la evaluacion
$cadenaSql = $this->sql->getCadenaSql ( "registroEvaluacion", $arreglo );
echo $cadenaSql; 
$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso' );
echo $resultado; 
$resultado =true;

if ($resultado) {
	//Actualizar estado del CONTRATO A EVALUADO
		$parametros = array (
				'idContrato' => $_REQUEST ['idContrato'],
				'estado' => 2  //evaluado
		);
		$cadenaSql = $this->sql->getCadenaSql ( "actualizarContrato", $parametros );
		$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso');
	
	//Actualizar PUNTAJE TOTAL DEL PROVEEDOR Y SU CLASIFICACION
 		//Consulto puntaje total del Evaluado
		$cadenaSql = $this->sql->getCadenaSql ( 'consultarProveedorByID', $_REQUEST["idProveedor"] );
		$proveedor = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
		$puntajeActual = $proveedor[0]["puntaje_evaluacion"];
		$claseficacionActual = $proveedor[0]["clasificacion_evaluacion"];
	
		if(  $claseficacionActual == 'A' || $claseficacionActual == 'B' || $claseficacionActual == 'C' ){
			$puntajeNuevo = ( $puntajeActual + $puntajeTotal )/2;
			$clasficacionNueva = clasificacion($puntajeNuevo); 
		}else{
			$puntajeNuevo = $puntajeTotal;
			$clasficacionNueva = $clasificacion;
		}

		$valores = array (
				'idProveedor' => $_REQUEST ['idProveedor'],
				'puntajeNuevo' => $puntajeNuevo,
				'clasificacion' => $clasficacionNueva
		);
		
		$cadenaSql = $this->sql->getCadenaSql ( "actualizarProveedor", $valores );
		$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso');
			
		$this->funcion->Redireccionador ( 'registroExitoso', $_REQUEST['usuario'] );
		exit();
} else {
		$this->funcion->Redireccionador ( 'noregistroDocumento', $_REQUEST['usuario'] );
		exit();
}
