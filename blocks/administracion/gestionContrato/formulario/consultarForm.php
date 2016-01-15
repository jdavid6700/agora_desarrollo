<?php
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}

$esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );

$rutaBloque = $this->miConfigurador->getVariableConfiguracion ( "host" );
$rutaBloque .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/";
$rutaBloque .= $esteBloque ['grupo'] . "/" . $esteBloque ['nombre'];

$directorio = $this->miConfigurador->getVariableConfiguracion ( "host" );
$directorio .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/index.php?";
$directorio .= $this->miConfigurador->getVariableConfiguracion ( "enlace" );
$miSesion = Sesion::singleton ();

$miPaginaActual = $this->miConfigurador->getVariableConfiguracion ( "pagina" );

$nombreFormulario = $esteBloque ["nombre"];

$conexion = "estructura";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );


{
	$tab = 1;
	
	include_once ("core/crypto/Encriptador.class.php");
	$cripto = Encriptador::singleton ();
	
	// ---------------Inicio Formulario (<form>)--------------------------------
	$atributos ["id"] = $nombreFormulario;
	$atributos ["tipoFormulario"] = "multipart/form-data";
	$atributos ["metodo"] = "POST";
	$atributos ["nombreFormulario"] = $nombreFormulario;
	$atributos ["tipoEtiqueta"] = 'inicio';
	$verificarFormulario = "1";
	echo $this->miFormulario->formulario ( $atributos );
	
	
	$miPaginaActual = $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
		
	$directorio = $this->miConfigurador->getVariableConfiguracion ( "host" );
	$directorio .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/index.php?";
	$directorio .= $this->miConfigurador->getVariableConfiguracion ( "enlace" );
		
	$variable = "pagina=" . $miPaginaActual;
	//$variable .= "&usuario=".$_REQUEST['usuario'];
	$variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variable, $directorio );
		
	// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
		$esteCampo = "marcoResultado";
		$atributos ['id'] = $esteCampo;
		$atributos ["estilo"] = "jqueryui";
		$atributos ['tipoEtiqueta'] = 'inicio';
		$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
		echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );	
		unset ( $atributos );		
}

if (isset ( $_REQUEST ['num_contrato'] ) && $_REQUEST ['num_contrato'] != '') {
	$numeroContrato = $_REQUEST ['num_contrato'];
} else {
	$numeroContrato = '';
}


if (isset ( $_REQUEST ['fecha_inicio_r'] ) && $_REQUEST ['fecha_inicio_r'] != '') {
	// $fechaInicio = $_REQUEST ['fecha_inicio'];
	
	if ($_REQUEST ['fecha_final_r'] == '') {
		$esteCampo = "FechasError";
		$atributos ["id"] = $esteCampo; // Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
		$atributos ["etiqueta"] = '';
		$atributos ["estilo"] = "centrar";
		$atributos ["tipo"] = 'error';
		$atributos ["mensaje"] = $this->lenguaje->getCadena ( $esteCampo );
		
		echo $this->miFormulario->cuadroMensaje ( $atributos );
		unset ( $atributos );
		
		exit ();
	} else {
		
		$fechaInicio_R = $_REQUEST ['fecha_inicio_r'];
	}
} else {
	
	$fechaInicio_R = '';
}

if (isset ( $_REQUEST ['fecha_final_r'] ) && $_REQUEST ['fecha_final_r'] != '') {
	// $fechaInicio = $_REQUEST ['fecha_inicio'];
	
	if ($_REQUEST ['fecha_inicio_r'] == '') {
		$esteCampo = "FechasError";
		$atributos ["id"] = $esteCampo; // Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
		$atributos ["etiqueta"] = '';
		$atributos ["estilo"] = "centrar";
		$atributos ["tipo"] = 'error';
		$atributos ["mensaje"] = $this->lenguaje->getCadena ( $esteCampo );
		
		echo $this->miFormulario->cuadroMensaje ( $atributos );
		unset ( $atributos );
		
		exit ();
	} else {
		
		$fechaFin_R = $_REQUEST ['fecha_final_r'];
	}
} else {
	
	$fechaFin_R = '';
}

$arreglo = array (
		$numeroContrato,
		$fechaInicio_R,
		$fechaFin_R 
);
unset($resultadoContratos);
$cadena_sql = $this->sql->getCadenaSql ( "consultarContrato", $arreglo );
$resultadoContratos = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );

if ($resultadoContratos) {
		// -----------------Inicio de Conjunto de Controles----------------------------------------
		$esteCampo = "marcoDatosResultadoParametrizar";
		$atributos ["estilo"] = "jqueryui";
		echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
		unset ( $atributos );
		
		echo "<table id='tablaContratos'>";
		
		echo "<thead>
				<tr>
					<th><center>Número Contrato</center></th>
					<th><center>Fecha Inicio</center></th>
					<th><center>Fecha Final</center></th>
					<th><center>Empresa Proveedor</center></th>
					<th><center>No. Acto Admininistrativo</center></th>
					<th><center>No. CDP</center></th>
					<th><center>No. RP</center></th>
					<th><center>Supervisor</center></th>
					<th><center>Estado</center></th>
					<th><center>Modificar</center></th>
				</tr>
				</thead>
				<tbody>";

		foreach ($resultadoContratos as $dato):
			$variable = "pagina=" . $miPaginaActual; // pendiente la pagina para modificar parametro
			$variable .= "&opcion=modificar";
			$variable .= "&idContrato=" . $dato['id_contrato'];
			//$variable .= "&usuario=".$_REQUEST['usuario'];
			$variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variable, $directorio );
			
			
			if($dato['estado']==1){
					$estado = 'Nuevo';
					$imagen = 'edit.png';
			}else{
					$estado = 'Evaluado';
					$variable = '#';
					$imagen = 'cancel.png';
			}
			
			$mostrarHtml = "<tr>
						<td><center>" . $dato['numero_contrato'] . "</center></td>
						<td><center>" . $dato['fecha_inicio'] . "</center></td>
						<td><center>" . $dato['fecha_finalizacion'] . "</center></td>
						<td><center>" . $dato['nomempresa'] . "</center></td>
						<td><center>" . $dato['numero_acto_admin'] . "</center></td>
						<td><center>" . $dato['numero_cdp'] . "</center></td>
						<td><center>" . $dato['numero_rp'] . "</center></td>
						<td>" . $dato['nombre_supervisor'] . "</td>
						<td><center>" . $estado . "</center></td>
						<td><center>
							<a href='" . $variable . "'>                        
								<img src='" . $rutaBloque . "/images/" . $imagen . "' width='15px'> 
							</a>
						</center></td>						
					</tr>";
			echo $mostrarHtml;
			unset ( $mostrarHtml );
			unset ( $variable );
		endforeach; 

		echo "</tbody>";
		echo "</table>";
	
	echo $this->miFormulario->agrupacion ( 'fin' );
	unset ( $atributos );
	
} else {
		$nombreFormulario = $esteBloque ["nombre"];
		include_once ("core/crypto/Encriptador.class.php");
		$cripto = Encriptador::singleton ();
		$directorio = $this->miConfigurador->getVariableConfiguracion ( "rutaUrlBloque" ) . "/imagen/";
		
		$miPaginaActual = $this->miConfigurador->getVariableConfiguracion ( "pagina" );
		
		$tab = 1;
		// ---------------Inicio Formulario (<form>)--------------------------------
		$atributos ["id"] = $nombreFormulario;
		$atributos ["tipoFormulario"] = "multipart/form-data";
		$atributos ["metodo"] = "POST";
		$atributos ["nombreFormulario"] = $nombreFormulario;
		$verificarFormulario = "1";
		$atributos ['marco'] = true;
		$atributos ['tipoEtiqueta'] = 'inicio';
		echo $this->miFormulario->formulario ( $atributos );
		
		$atributos ["id"] = "divNoEncontroEgresado";
		$atributos ["estilo"] = "marcoBotones";
		// $atributos["estiloEnLinea"]="display:none";
		echo $this->miFormulario->division ( "inicio", $atributos );
		
		// -------------Control Boton-----------------------
		$esteCampo = "noRetornaDatosContrato";
		$atributos ["id"] = $esteCampo; // Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
		$atributos ["etiqueta"] = "";
		$atributos ["estilo"] = "centrar";
		$atributos ["tipo"] = 'error';
		$atributos ["mensaje"] = $this->lenguaje->getCadena ( $esteCampo );
		
		echo $this->miFormulario->cuadroMensaje ( $atributos );
		unset ( $atributos );
		
		$valorCodificado = "pagina=" . $miPaginaActual;
		$valorCodificado .= "&bloque=" . $esteBloque ["id_bloque"];
		$valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
		$valorCodificado = $cripto->codificar ( $valorCodificado );
		// -------------Fin Control Boton----------------------
		// ------------------Fin Division para los botones-------------------------
		echo $this->miFormulario->division ( "fin" );
		
		
		echo $this->miFormulario->agrupacion ( 'fin' );
		unset ( $atributos );
		// -------------Control cuadroTexto con campos ocultos-----------------------
		// Para pasar variables entre formularios o enviar datos para validar sesiones
		$atributos ["id"] = "formSaraData"; // No cambiar este nombre
		$atributos ["tipo"] = "hidden";
		$atributos ["obligatorio"] = false;
		$atributos ["etiqueta"] = "";
		$atributos ["valor"] = $valorCodificado;
		echo $this->miFormulario->campoCuadroTexto ( $atributos );
		unset ( $atributos );
}


$atributos ['marco'] = true;
$atributos ['tipoEtiqueta'] = 'fin';
echo $this->miFormulario->formulario ( $atributos );
unset($atributos);


?>