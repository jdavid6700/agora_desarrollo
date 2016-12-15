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
//var_dump($_REQUEST);
unset($resultadoContratos);

//datos del contrtato
$cadena_sql = $this->sql->getCadenaSql ( "contratoByNumero", $numeroContrato );
$resultadoContratos = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );

$_REQUEST['idContrato'] = $resultadoContratos[0]['id_contrato'];
$_REQUEST['idCodigo'] = "XX";


$cadenaSql = $this->sql->getCadenaSql ( 'contratoByProveedor', $_REQUEST["idContrato"] );
$consulta3 = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );


if(count($consulta3) > 1){
		
	$cadenaSql = $this->sql->getCadenaSql ( 'listarProveedoresXContrato', $_REQUEST["idContrato"] );
	$consulta3_1 = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
	$cadenaSql = $this->sql->getCadenaSql ( 'consultarProveedoresByID', $consulta3_1[0][0] );
	$consulta4 = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
	$cantidad = "multiple";
	$numeroPro = count($consulta3);
		
}else{
	$cadenaSql = $this->sql->getCadenaSql ( 'consultarProveedorByID', $consulta3[0]['id_proveedor'] );
	$consulta4 = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
	$cantidad = "individual";
	$numeroPro = count($consulta3);
}



if ($resultadoContratos) {
	
		$esteCampo = "marcoContrato";
		$atributos ['id'] = $esteCampo;
		$atributos ["estilo"] = "jqueryui";
		$atributos ['tipoEtiqueta'] = 'inicio';
		$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
		echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );                
                    
				//INICIO OBJETO A CONTRATAR
				echo "<span class='textoElegante textoEnorme textoAzul'>Nùmero Contrato : </span>"; 
                echo "<span class='textoElegante textoMediano textoGris'>". $resultadoContratos[0]['numero_contrato'] . " - ". $resultadoContratos[0]['vigencia'] ."</span></br>"; 
				//echo "<span class='textoElegante textoEnorme textoAzul'>Fecha Inicial - Final : </span>"; 
                //echo "<span class='textoElegante textoMediano textoGris'>". $resultadoContratos[0][2] . '-' . $resultadoContratos[0][3] . "</span></br>"; 
				echo "<span class='textoElegante textoEnorme textoAzul'>Empresa Proveedor : </span><b><br>"; 
                if($cantidad == "individual"){
					echo "- <span class='textoElegante textoMediano textoGris'>". $consulta4[0]['num_documento']  . " - " .$consulta4[0]['nom_proveedor'] . " (" . $consulta4[0]['tipopersona'] . ")"."</span></br>";
				}else{
					$i = 0;
					while($i < $numeroPro){
						echo "- <span class='textoElegante textoMediano textoGris'>". $consulta4[$i]['num_documento']  . " - " .$consulta4[$i]['nom_proveedor']. " (" . $consulta4[$i]['tipopersona'] . ")"."</span></br>";
						$i++;
					}
				}
                echo "</b>";                 
				//FIN CONTRATO
		echo $this->miFormulario->marcoAgrupacion ( 'fin' );

				
	
		if ($resultadoContratos[0]["estado"] == 1 ){
			//MOSTRAT MENSAJE QUE NO SE HA EVALUADO EL CONTRATO
				// ------------------INICIO Division para los botones-------------------------
				$atributos ["id"] = "divNoEncontroEgresado";
				$atributos ["estilo"] = "marcoBotones";
				echo $this->miFormulario->division ( "inicio", $atributos );
				// -------------SECCION: Controles del Formulario-----------------------
				$esteCampo = "mensaNoEvaluado";
				$atributos ["id"] = $esteCampo; // Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
				$atributos ["etiqueta"] = "";
				$atributos ["estilo"] = "centrar";
				$atributos ["tipo"] = 'error';
				$atributos ["mensaje"] = $this->lenguaje->getCadena ( $esteCampo );
				
				echo $this->miFormulario->cuadroMensaje ( $atributos );
				unset ( $atributos );
				// -------------FIN Control Formulario----------------------
				// ------------------FIN Division para los botones-------------------------
				echo $this->miFormulario->division ( "fin" );
				unset ( $atributos );			
			
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
(apl	icación de garantías, mantenimiento, cambios, reparaciones, capacitaciones, entre otras) </small></td>
							<td align="center"><small><?php if( $resultadoEvaluacion[0]["servicio_venta"] == 10 ) echo "Si"; else echo "No"; ; ?> </small></td>
							<td align="center"><small><?php echo $resultadoEvaluacion[0]["servicio_venta"]; ?></small></td>
				</tr>
				<!-- GESTION -->
				<tr >
							<td rowspan="3" align="center"><small><br>GESTIÒN</small></td>	
							<td align="center"><small>Procedimientos </small></td>
							<td align="center"><small>¿El contrato es suscrito en el tiempo pactado, entrega las pólizas a tiempo y las facturas son radicadas en el tiempo indicado
con 	las condiciones y soportes requeridos para su trámite contractual? </small></td>
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
							<td align="center"><strong><?php echo $resultadoEvaluacion[0]["puntaje_total"]; ?></strong></td>
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
		
		//INICIO enlace boton descargar resumen******************************************************************************************
		$variableResumen = "pagina=" . $miPaginaActual;
		$variableResumen.= "&action=".$esteBloque["nombre"];
		$variableResumen.= "&bloque=" . $esteBloque["id_bloque"];
		$variableResumen.= "&bloqueGrupo=" . $esteBloque["grupo"];
		$variableResumen.= "&opcion=resumen";
		$variableResumen.= "&idContrato=" . $_REQUEST['idContrato'];
		$variableResumen.= "&idCodigo=" . $_REQUEST['idCodigo'];
		$variableResumen = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableResumen, $directorio);
		
		//------------------Division para los botones-------------------------
		$atributos["id"]="botones";
		$atributos["estilo"]="marcoBotones";
		echo $this->miFormulario->division("inicio",$atributos);
		
		$enlace = "<a href='".$variableResumen."'>";
		$enlace.="<img src='".$rutaBloque."/images/pdf.png' width='35px'><br>Descargar Evaluación ";
		$enlace.="</a><br><br>";
		echo $enlace;
		//------------------Fin Division para los botones-------------------------
		echo $this->miFormulario->division("fin");
		//FIN enlace boton descargar resumen**********************************************************************************************

}else{
		// ------------------INICIO Division para los botones-------------------------
		$atributos ["id"] = "divNoEncontroEgresado";
		$atributos ["estilo"] = "marcoBotones";
		echo $this->miFormulario->division ( "inicio", $atributos );
		// -------------SECCION: Controles del Formulario-----------------------
		$esteCampo = "mensajeNoEncontroContratoByNoContrato";
		$atributos ["id"] = $esteCampo; // Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
		$atributos ["etiqueta"] = "";
		$atributos ["estilo"] = "centrar";
		$atributos ["tipo"] = 'error';
		$atributos ["mensaje"] = $this->lenguaje->getCadena ( $esteCampo );
		
		echo $this->miFormulario->cuadroMensaje ( $atributos );
		unset ( $atributos );
		// -------------FIN Control Formulario----------------------
		// ------------------FIN Division para los botones-------------------------
		echo $this->miFormulario->division ( "fin" );
		unset ( $atributos );
}	


$atributos ['marco'] = true;
$atributos ['tipoEtiqueta'] = 'fin';
echo $this->miFormulario->formulario ( $atributos );
unset($atributos);


?>