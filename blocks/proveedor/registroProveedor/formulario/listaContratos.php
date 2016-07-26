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
	// $variable .= "&usuario=".$_REQUEST['usuario'];
	$variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variable, $directorio );
	
	// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	$esteCampo = "marcoContratos";
	$atributos ['id'] = $esteCampo;
	$atributos ["estilo"] = "jqueryui";
	$atributos ['tipoEtiqueta'] = 'inicio';
	$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
	echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
	unset ( $atributos );
}

unset ( $resultado );
$cadena_sql = $this->sql->getCadenaSql ( "consultarContratos", $_REQUEST ['usuario'] );
$resultado = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );

if ($resultado) {
	
	// -----------------Inicio de Conjunto de Controles----------------------------------------
	$esteCampo = "marcoDatosResultadoParametrizar";
	$atributos ["estilo"] = "jqueryui";
	echo $this->miFormulario->marcoAgrupacion ( "inicio", $atributos );
	unset ( $atributos );
	?>
<br>

<table id="tablaReporteCont" class="display" cellspacing="0" width="100%">
	<thead>
		<tr>
			<th>Número Contrato</th>
			<th>Fecha Inicio</th>
			<th>Fecha Final</th>
			<th>Valor</th>
			<th>Certificado</th>
			<th>Estado</th>
		</tr>
	</thead>
	<tbody>
                    

			
			
			
<?php
	foreach ( $resultado as $dato ) :
		
		$variable = "pagina=" . $miPaginaActual;
		$variable .= "&action=" . $esteBloque ["nombre"];
		$variable .= "&bloque=" . $esteBloque ['nombre'];
		$variable .= "&bloqueGrupo=" . $esteBloque ["grupo"];
		$variable .= "&opcion=certContrato";
		$variable .= "&idContrato=" . $dato ['id_contrato'];
		$variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variable, $directorio );
		
		$hoy = date ( "Y-m-d" );
		$msj = '';
		if ($hoy > $dato ['fecha_finalizacion']) {
			$certUniversidadImagen = 'pdf.png';
			
			switch ($dato ['estado']) {
				case 'CREADO' :
					$msj = 'No se ha realizado el proceso de evaluaci&oacute;n';
					$varSatisfaccion = '#';
					$certSatisImagen = 'cancel.png';
					break;
				case 'EVALUADO' :
					$cadena_sql = $this->sql->getCadenaSql ( "evalaucionByIdContrato", $dato ['id_contrato'] );
					$evaluacion = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
					
					if ($evaluacion [0] ['puntaje_total'] > 45) {
						$msj = 'Evaluado';
						
						//$variable = "pagina=" . $miPaginaActual;
						//$variable .= "&action=" . $esteBloque ["nombre"];
						//$variable .= "&bloque=" . $esteBloque ['nombre'];
						//$variable .= "&bloqueGrupo=" . $esteBloque ["grupo"];
						//$variable .= "&opcion=certContrato";
						//$variable .= "&idContrato=" . $dato ['id_contrato'];
						//$varSatisfaccion = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variable, $directorio );
						$certSatisImagen = 'pdf.png';
					} else {
						$msj = 'Evaluado, pero no cumplio a satisfacción';
						$varSatisfaccion = '#';
						$certSatisImagen = 'cancel.png';
					}
					
					break;
			}
		} else {
			$msj = 'El contrato no se ha terminado';
			$certUniversidadImagen = 'cancel.png';
			$certSatisImagen = 'cancel.png';
			$variable = '#';
			$varSatisfaccion = '#';
		}
		
		echo "<tr>";
		echo "<td>" . $dato ['numero_contrato'] . "</td>";
		echo "<td align='center'>" . $dato ['fecha_inicio'] . "</td>";
		echo "<td align='center'>" . $dato ['fecha_finalizacion'] . "</td>";
		$valorContrto = number_format ( $resultado [0] ['valor'] );
		echo "<td align='right'>$ " . $valorContrto . "</td>";
		echo "<td class='text-center'>";
		echo "<a href='" . $variable . "'>                        
														<img src='" . $rutaBloque . "/images/" . $certUniversidadImagen . "' width='15px'> 
													</a>";
		echo "</td>";
		/*
		 * echo "<td class='text-center'>";
		 * echo "<a href='" . $varSatisfaccion . "'>
		 * <img src='" . $rutaBloque . "/images/" . $certSatisImagen . "' width='15px'>
		 * </a>";
		 * echo "</td>";
		 */
		echo "<td align='left'>" . $msj . "</td>";
		echo "</tr>";
	endforeach
	;

?>
			
			           			</tbody>
</table>

<?php
	
	echo $this->miFormulario->agrupacion ( 'fin' );
	unset ( $atributos );
} else {
	
	$atributos ["id"] = "divNoEncontroEgresado";
	$atributos ["estilo"] = "marcoBotones";
	echo $this->miFormulario->division ( "inicio", $atributos );
	
	// -------------Control Boton-----------------------
	$esteCampo = "noEncontroContrato";
	$atributos ["id"] = $esteCampo; // Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
	$atributos ["etiqueta"] = "";
	$atributos ["estilo"] = "centrar";
	$atributos ["tipo"] = 'error';
	$atributos ["mensaje"] = $this->lenguaje->getCadena ( $esteCampo );
	
	echo $this->miFormulario->cuadroMensaje ( $atributos );
	unset ( $atributos );
	
	// ------------------Fin Division para los botones-------------------------
	echo $this->miFormulario->division ( "fin" );
}

$atributos ['marco'] = true;
$atributos ['tipoEtiqueta'] = 'fin';
echo $this->miFormulario->formulario ( $atributos );
unset ( $atributos );

?>