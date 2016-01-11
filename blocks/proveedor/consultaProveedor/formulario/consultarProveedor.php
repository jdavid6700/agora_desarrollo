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
		$esteCampo = "marcoDatos";
		$atributos ['id'] = $esteCampo;
		$atributos ["estilo"] = "jqueryui";
		$atributos ['tipoEtiqueta'] = 'inicio';
		$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
		echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );	
		unset ( $atributos );		
}

if (isset ( $_REQUEST ['nit_proveedor'] ) && $_REQUEST ['nit_proveedor'] != '') {
	$NIT = $_REQUEST ['nit_proveedor'];
} else {
	$NIT = '';
}

if (isset ( $_REQUEST ['fecha_inicio_c'] ) && $_REQUEST ['fecha_inicio_c'] != '') {
	// $fechaInicio = $_REQUEST ['fecha_inicio'];
	
	if ($_REQUEST ['fecha_final_c'] == '') {
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
		
		$fechaInicio_C = $_REQUEST ['fecha_inicio_c'];
	}
} else {
	
	$fechaInicio_C = '';
}

if (isset ( $_REQUEST ['fecha_final_c'] ) && $_REQUEST ['fecha_final_c'] != '') {
	// $fechaInicio = $_REQUEST ['fecha_inicio'];
	
	if ($_REQUEST ['fecha_inicio_c'] == '') {
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
		
		$fechaFin_C = $_REQUEST ['fecha_final_c'];
	}
} else {
	
	$fechaFin_C = '';
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
		$NIT,
		$fechaInicio_C,
		$fechaFin_C,
		$fechaInicio_R,
		$fechaFin_R 
);
unset($resultado);
$cadena_sql = $this->sql->getCadenaSql ( "consultarProveedor", $arreglo );
$resultado = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );

if ($resultado) {
		// -----------------Inicio de Conjunto de Controles----------------------------------------
		$esteCampo = "marcoDatosResultadoParametrizar";
		$atributos ["estilo"] = "jqueryui";
		echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
		unset ( $atributos );
		
		echo "<table id='tablaContratos'>";
		
		echo "<thead>
				<tr>
					<th><center>NIT</center></th>
					<th><center>Nombre Empresa</center></th>
					<th><center>Correo</center></th>
					<th><center>Web</center></th>
					<th><center>Telèfono</center></th>
					<th><center>Ext.</center></th>
					<th><center>Movil</center></th>
					<th><center>Contacto</center></th>
					<th><center>Puntaje Total</center></th>
					<th><center>Clasificaciòn</center></th>
				</tr>
				</thead>
				<tbody>";

		foreach ($resultado as $dato):
			$variable = "pagina=" . $miPaginaActual; // pendiente la pagina para modificar parametro
			$variable .= "&opcion=modificar";
			$variable .= "&idContrato=" . $dato['id_proveedor'];
			$variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variable, $directorio );
			
			$mostrarHtml = "<tr>
						<td><center>" . $dato['nit'] . "</center></td>
						<td><center>" . $dato['nomempresa'] . "</center></td>
						<td><center>" . $dato['correo'] . "</center></td>
						<td><center>" . $dato['web'] . "</center></td>
						<td><center>" . $dato['telefono'] . "</center></td>
						<td><center>" . $dato['ext1'] . "</center></td>
						<td><center>" . $dato['movil'] . "</center></td>
						<td>" . $dato['nombre1'] . ' ' . $dato['nombre2'] . ' '. $dato['apellido1'] . ' ' . $dato['apellido2'] . "</td>
						<td><center>" . $dato['puntaje_evaluacion'] . "</center></td>
						<td><center>" . $dato['clasificacion_evaluacion'] . "</center></td>
						<td><center>
							<a href='" . $variable . "'>                        
								<img src='" . $rutaBloque . "/images/edit.png' width='15px'> 
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
		$esteCampo = "noEncontroProcesos";
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