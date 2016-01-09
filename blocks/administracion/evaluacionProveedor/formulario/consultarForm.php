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

// validamos los datos que llegan

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

		
	// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------

	
	
	

	

	
	$valorCodificado = "pagina=" . $miPaginaActual;
	$valorCodificado .= "&bloque=" . $esteBloque ["id_bloque"];
	$valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
	$valorCodificado = $cripto->codificar ( $valorCodificado );
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

if (isset ( $_REQUEST ['num_contrato'] ) && $_REQUEST ['num_contrato'] != '') {
	$numeroContrato = $_REQUEST ['num_contrato'];
} else {
	$numeroContrato = '';
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
		$numeroContrato,
		$fechaInicio_C,
		$fechaFin_C,
		$fechaInicio_R,
		$fechaFin_R 
);
unset($resultadoContratos);

//datos del contrtato
$cadena_sql = $this->sql->getCadenaSql ( "contratoByNumero", $arreglo );
$resultadoContratos = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );

if ($resultadoContratos) {
	
		echo "<span class='textoElegante textoEnorme textoAzul'>Contrato</span><hr>"; 
		echo '<table style="width:100%">';
		echo '<tr><td style="width:20%"><span class="textoAzul">No. Contrato</td><td><span class="textoGris">' . $resultadoContratos[0][1] . "</span></td></tr>";
		echo '<tr><td style="width:20%"><span class="textoAzul">Fecha Inicial</td><td><span class="textoGris">' . $resultadoContratos[0][2] . "</span></td></tr>";
		echo '<tr><td style="width:20%"><span class="textoAzul">Fecha Final</td><td><span class="textoGris">' . $resultadoContratos[0][3] . "</span></td></tr>";	
		echo '<tr><td style="width:20%"><span class="textoAzul">Proveedor</td><td><span class="textoGris">' . $resultadoContratos[0][4] . "</span></td></tr>";	
		echo '</table>';
		//FIN OBJETO A CONTRATAR	
	
	if ($resultadoContratos[0]["estado"] == 1 ){
		
		//MOSTRAT MENSAJE QUE NO SE HA EVALUADO EL CONTRATO
		
		echo "NO SE HA EVALUADO EL CONTATO"; exit;
		
	}else{
		//datos de la evaluacion
		$idContrato = $resultadoContratos[0]["id_contrato"];
		$cadena_sql = $this->sql->getCadenaSql ( "evalaucionByIdContrato", $idContrato );
		$resultadoEvaluacion = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );	
	?>
		<table class="table table-bordered table-condensed">
			<tr class="info">
						<td align="center"><strong>CRITERIO</strong></td>	
						<td align="center"><strong>SUBCRITERIO </strong></td>
						<td align="center"><strong>ÍTEM </strong></td>
						<td align="center"><strong>RESPUESTA </strong></td>
						<td align="center"><strong>VALOR</strong></td>
						<td align="center"><strong>PUNTAJE TOTAL</strong></td>
			</tr>
			<!-- CUMPLIMIENTO -->
			<tr >
						<td rowspan="2" align="center"><small><br>CUMPLIMIENTO</small></td>	
						<td align="center"><small>Tiempos de entrega </small></td>
						<td align="center"><small>¿Se cumplieron los tiempos de entrega de bienes o la prestación de los servicios ofertados por el proveedor? </small></td>
						<td align="center"><small><?php if( $resultadoEvaluacion[0]["tiemo_entrega"] == 12 ) echo "Si"; else echo "No"; ; ?> </small></td>
						<td align="center"><small><?php echo $resultadoEvaluacion[0]["tiemo_entrega"]; ?></small></td>
						<td rowspan="2" align="center"><small><br><?php echo $resultadoEvaluacion[0]["tiemo_entrega"] + $resultadoEvaluacion[0]["cantidades"]; ?></small></td>
			</tr>
			<tr >	
						<td align="center"><small>Cantidades </small></td>
						<td align="center"><small>¿Se entregan las cantidades solicitadas? </small></td>
						<td align="center"><small><?php if( $resultadoEvaluacion[0]["cantidades"] == 12 ) echo "Si"; else echo "No"; ; ?> </small></td>
						<td align="center"><small><?php echo $resultadoEvaluacion[0]["cantidades"]; ?></small></td>
			</tr>
			<!-- CALIDAD -->
			<tr >
						<td rowspan="2" align="center"><small><br>CALIDAD</small></td>	
						<td align="center"><small>Conformidad </small></td>
						<td align="center"><small>¿El bien o servicio cumplió con las especificaciones y requisitos pactados en el momento de entrega? </small></td>
						<td align="center"><small><?php if( $resultadoEvaluacion[0]["conformidad"] == 20 ) echo "Si"; else echo "No"; ; ?> </small></td>
						<td align="center"><small><?php echo $resultadoEvaluacion[0]["conformidad"]; ?></small></td>
						<td rowspan="2" align="center"><small><br><?php echo $resultadoEvaluacion[0]["conformidad"] + $resultadoEvaluacion[0]["funcionalidad_adicional"]; ?></small></td>
			</tr>
			<tr >	
						<td align="center"><small>Funcionalidad adicional </small></td>
						<td align="center"><small>¿El producto comprado o servicio prestado proporcionó más herramientas o funciones de las solicitadas originalmente? </small></td>
						<td align="center"><small><?php if( $resultadoEvaluacion[0]["funcionalidad_adicional"] == 10 ) echo "Si"; else echo "No"; ; ?> </small></td>
						<td align="center"><small><?php echo $resultadoEvaluacion[0]["funcionalidad_adicional"]; ?></small></td>
			</tr>
			<!-- POS CONTRACTUAL -->
			<tr >
						<td rowspan="3" align="center"><small><br>POS CONTRACTUAL</small></td>	
						<td rowspan="2" align="center"><small>Reclamaciones </small></td>
						<td align="center"><small>¿Se han presentado reclamaciones al proveedor en calidad o gestión? </small></td>
						<td align="center"><small><?php if( $resultadoEvaluacion[0]["reclamaciones"] == 12 ) echo "No"; else echo "Si"; ; ?> </small></td>
						<td align="center"><small><?php echo $resultadoEvaluacion[0]["reclamaciones"]; ?></small></td>
						<td rowspan="3" align="center"><small><br><?php echo $resultadoEvaluacion[0]["reclamaciones"] + $resultadoEvaluacion[0]["reclamacion_solucion"] + $resultadoEvaluacion[0]["servicio_venta"]; ?></small></td>
			</tr>
			<tr >
						<td align="center"><small>¿El proveedor soluciona oportunamente las no conformidades de calidad y gestión de los bienes o servicios recibidos? </small></td>
						<td align="center"><small><?php if( $resultadoEvaluacion[0]["reclamacion_solucion"] == 12 ) echo "Si"; else echo "No"; ; ?> </small></td>
						<td align="center"><small><?php echo $resultadoEvaluacion[0]["reclamacion_solucion"]; ?></small></td>
			</tr>
			<tr >	
						<td align="center"><small>Servicio pos venta </small></td>
						<td align="center"><small>¿El proveedor cumple con los compromisos pactados dentro del contrato u orden de servicio o compra?
(aplicación de garantías, mantenimiento, cambios, reparaciones, capacitaciones, entre otras) </small></td>
						<td align="center"><small><?php if( $resultadoEvaluacion[0]["servicio_venta"] == 10 ) echo "Si"; else echo "No"; ; ?> </small></td>
						<td align="center"><small><?php echo $resultadoEvaluacion[0]["servicio_venta"]; ?></small></td>
			</tr>
			<!-- GESTION -->
			<tr >
						<td rowspan="3" align="center"><small><br>GESTIÒN</small></td>	
						<td align="center"><small>Procedimientos </small></td>
						<td align="center"><small>¿El contrato es suscrito en el tiempo pactado, entrega las pólizas a tiempo y las facturas son radicadas en el tiempo indicado
con las condiciones y soportes requeridos para su trámite contractual? </small></td>
						<td align="center"><small>
						<?php 
							switch ( $resultadoEvaluacion[0]["procedimientos"] ) {
								case 9:
									echo "Excelente";
									break;
								case 6:
									echo "Bueno";
									break;
								case 3:
									echo "Regular";
									break;
								case 0:
									echo "Malo";
									break;
							}
						?>
						</small></td>
						<td align="center"><small><?php echo $resultadoEvaluacion[0]["procedimientos"]; ?></small></td>
						<td rowspan="3" align="center"><small><br><?php echo $resultadoEvaluacion[0]["procedimientos"] + $resultadoEvaluacion[0]["garantia"] + $resultadoEvaluacion[0]["garantia_satisfaccion"]; ?></small></td>
			</tr>
			<tr >	
						<td rowspan="2" align="center"><small>Garantìa </small></td>
						<td align="center"><small>¿Se requirió hacer uso de la garantía del producto o servicio? </small></td>
						<td align="center"><small><?php if( $resultadoEvaluacion[0]["garantia"] == 15 ) echo "No"; else echo "Si"; ; ?> </small></td>
						<td align="center"><small><?php echo $resultadoEvaluacion[0]["garantia"]; ?></small></td>
			</tr>
			<tr >	
						<td align="center"><small>¿El proveedor cumplió a satisfacción con la garantía pactada? </small></td>
						<td align="center"><small><?php if( $resultadoEvaluacion[0]["garantia_satisfaccion"] == 15 ) echo "Si"; else echo "No"; ; ?> </small></td>
						<td align="center"><small><?php echo $resultadoEvaluacion[0]["garantia_satisfaccion"]; ?></small></td>
			</tr>
			<tr >	
						<td colspan="3"></td>
						<td colspan="2" align="center"><strong>TOTAL</strong></td>
						<td align="center"><small><?php echo $resultadoEvaluacion[0]["puntaje_total"]; ?></small></td>
			</tr>
			<tr >	
						<td colspan="3"></td>
						<td colspan="3" align="center"><strong>CLASIFICACIÒN TIPO
						<?php 
						if( $resultadoEvaluacion[0]["puntaje_total"] > 79 )
							echo "A";
						elseif( $resultadoEvaluacion[0]["puntaje_total"] > 45 )
							echo "B";
						else echo "C";
						?>						
						</strong></td>
			</tr>
		</table>
<?php	
	}

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