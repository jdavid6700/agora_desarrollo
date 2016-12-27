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



//*************************************************************************** DBMS *******************************
//****************************************************************************************************************

$conexion = 'estructura';
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

$conexion = 'sicapital';
$siCapitalRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

$conexion = 'centralUD';
$centralUDRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

$conexion = 'argo_contratos';
$argoRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

$conexion = 'core_central';
$coreRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

$conexion = 'framework';
$frameworkRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

//*************************************************************************** DBMS *******************************
//****************************************************************************************************************




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

//****************************************************************************************
//****************************************************************************************

$cadenaSql = $this->sql->getCadenaSql ( 'consultar_proveedor', $_REQUEST ["usuario"] );
$resultadoDoc = $frameworkRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );

$numeroDocumento = $resultadoDoc[0]['identificacion'];

$cadenaSql = $this->sql->getCadenaSql ( 'consultar_DatosProveedor', $numeroDocumento );
$resultadoDats = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );

$idProveedor = $resultadoDats[0]['id_proveedor'];
$tipoPersona = $resultadoDats[0]['tipopersona'];
$nombrePersona = $resultadoDats[0]['nom_proveedor'];
$correo = $resultadoDats[0]['correo'];
$direccion = $resultadoDats[0]['direccion'];

$esteCampo = "marcoInfoCont";
$atributos ['id'] = $esteCampo;
$atributos ["estilo"] = "jqueryui";
$atributos ['tipoEtiqueta'] = 'inicio';
$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
{


		//INICIO INFORMACION
		echo "<span class='textoElegante textoGrande textoAzul'>Nombre de la Persona: </span>";
		echo "<span class='textoElegante textoGrande textoGris'>". $nombrePersona . "</span></br>";
		echo "<span class='textoElegante textoGrande textoAzul'>Documento : </span>";
		echo "<span class='textoElegante textoGrande textoGris'>". $numeroDocumento . "</span></br>";
		echo "<span class='textoElegante textoGrande textoAzul'>Tipo Persona : </span>";
		echo "<span class='textoElegante textoGrande textoGris'>". $tipoPersona . "</span></br>";
		echo "<span class='textoElegante textoGrande textoAzul'>Dirección : </span>";
		echo "<span class='textoElegante textoGrande textoGris'>". $direccion . "</span></br>";
		echo "<span class='textoElegante textoGrande textoAzul'>Correo : </span>";
		echo "<span class='textoElegante textoGrande textoGris'>". $correo . "</span></br>";
		//FIN INFORMACION

?>

<div id="dialogo">
	<p>A continuación podra observar todos los distintos contratos en lo que ha participado o esta 
	participando en la Universidad, con lo cual obtener sus Certificados, solo
	si estos ya se han culminado satisfactoriamente.</p>
</div>


<?php
	
}
echo $this->miFormulario->marcoAgrupacion ( 'fin', $atributos );



$esteCampo = "marcoContratosTabla";
$atributos ['id'] = $esteCampo;
$atributos ["estilo"] = "jqueryui";
$atributos ['tipoEtiqueta'] = 'inicio';
$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
{

	
//*************** $numeroDocumento
//*************** $idProveedor

	
//********** CONSULTAR Consorcios y Uniones Temporales en las que Participa **********

$cadena_sql = $this->sql->getCadenaSql ( "consultarConsorciosUniones", $idProveedor );
$resultadoConsorUnion = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );

if($resultadoConsorUnion[0][0] != null){
	$cadenaIdContratista = $resultadoConsorUnion[0][0] . "," . $idProveedor;
}else{
	$cadenaIdContratista = $idProveedor;
}

//********** CONSULTAR Contratos **********

$cadena_sql = $this->sql->getCadenaSql ( "consultarContratosARGO", $cadenaIdContratista );
$resultadoCont = $argoRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );

//echo $cadena_sql;
//var_dump(count($resultadoCont));
$i = 0;
$contratos = array();

while($i < count($resultadoCont)){
	
	$datosContrato = array (
			'num_contrato' => $resultadoCont[$i]['numero_contrato'],
			'vigencia' => $resultadoCont[$i]['vigencia'],
			'unidad_ejecutora' => $resultadoCont[$i]['unidad_ejecutora']
	);
	
	$datosSolicitudNecesidad = array (
			'num_necesidad' => $resultadoCont[$i]['numero_solicitud_necesidad'],
			'vigencia' => $resultadoCont[$i]['vigencia']
	);
	
	
	//*********************************************************************************************************************************
	//$cadena_sql = $this->sql->getCadenaSql ( "estadoContratoAgora", $datosContrato);
	//$resultado = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
	
	//if(isset($resultado)) {
		$estadoSolicitud = $resultadoCont[$i]['estado'];
		//var_dump($estadoSolicitud);
	
		$cadena_sql = $this->sql->getCadenaSql ( "consultarActaInicio", $datosContrato);
		$resultadoActaInicio = $argoRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
		
		
		if(!$resultadoActaInicio){
			$fechaInicio[$i] = "--/--/----";
			$fechaFin[$i] = "--/--/----";
			$fechas[$i] = false;
		}else{
			$fechaInicio[$i] = date("d/m/Y", strtotime($resultadoActaInicio[0]['fecha_inicio']));
			$fechaFin[$i] = date("d/m/Y", strtotime($resultadoActaInicio[0]['fecha_fin']));
			$fechas[$i] = true;
		}
	
		$cadena_sql = $this->sql->getCadenaSql ( "consultarNovedadesContrato", $datosContrato);
		$resultadoNovedades = $argoRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
	//}
	//***************CONTRATOS RELACIONADOS******************************************************************************************
	
	
	//$cadena_sql = $this->sql->getCadenaSql ( "listaContratoXNumContrato", $datosContrato);
	//$resultado = $argoRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
	
	
	//array_push($contratos, $resultado[0]);
	
	$i++;
}



if ($resultadoCont) {
	
	// -----------------Inicio de Conjunto de Controles----------------------------------------
	$esteCampo = "marcoDatosResultadoParametrizar";
	$atributos ["estilo"] = "jqueryui";
	//echo $this->miFormulario->marcoAgrupacion ( "inicio", $atributos );
	unset ( $atributos );
	?>
<br>

<table id="tablaReporteCont" class="display" cellspacing="0" width="100%">
	<thead>
		<tr>
			<th>Número Contrato</th>
			<th>Vigencia</th>
			<th>N° CDP</th>
			<th>N° RP</th>
			<th>Fecha Inicio</th>
			<th>Fecha Final</th>
			<th>Valor</th>
			<th>Plazo de Ejecución</th>
			<th>Clase</th>
			<th>Certificado</th>
			<th>Estado</th>
			<th>Seguimiento</th>
		</tr>
	</thead>
	<tbody>
                    

			
			
			
<?php
	$j = 0;
	foreach ( $resultadoCont as $dato ) :
		
		
		$variable = "pagina=" . $miPaginaActual;
		$variable .= "&action=" . $esteBloque ["nombre"];
		$variable .= "&bloque=" . $esteBloque ['nombre'];
		$variable .= "&bloqueGrupo=" . $esteBloque ["grupo"];
		$variable .= "&opcion=certContrato";
		$variable .= "&numeroContrato=" . $dato ['numero_contrato'];
		$variable .= "&vigenciaContrato=" . $dato ['vigencia'];
		$variable .= "&docProveedor=" . $numeroDocumento;
		$variable .= "&nomProveedor=" . $nombrePersona;
		$variable .= "&tipoProveedor=" . $tipoPersona;
		$variable .= "&usuario=" . $_REQUEST ["usuario"];
		$variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variable, $directorio );
		
		$hoy = date ( "Y-m-d" );
		$msj = '';
		
		if($fechas[$j]){
			
			
			if (strtotime($hoy) > strtotime($fechaFin[$j])) {
					
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
				//$certUniversidadImagen = 'cancel.png';
				$certUniversidadImagen = 'pdf.png';
				$certSatisImagen = 'cancel.png';
				//$variable = '#';
				$varSatisfaccion = '#';
			}
			
		}else{
			$msj = 'El contrato no tiene Acta de Inicio';
			$certUniversidadImagen = 'cancel.png';
			$certSatisImagen = 'cancel.png';
			$variable = '#';
			$varSatisfaccion = '#';
		}
		
		
		
		echo "<tr>";
		echo "<td align='center' width='5%'>" . $dato ['numero_contrato'] . "</td>";
		echo "<td align='center'>" . $dato ['vigencia'] . "</td>";
		echo "<td align='center'>" . $dato ['numero_cdp'] . "</td>";
		echo "<td align='center'>" . $dato ['numero_rp'] . "</td>";
		echo "<td align='center'>" . $fechaInicio[$j] . "</td>";
		echo "<td align='center'>" . $fechaFin[$j] . "</td>";
		$valorContrto = number_format ( $dato ['valor_contrato'] );
		echo "<td align='right' width='13%'>$ " . $valorContrto . "</td>";
		echo "<td align='center'>" . $dato ['plazo_ejecucion'] . "</td>";
		echo "<td align='center'>" . $dato ['clase_contratista'] . "</td>";
		echo "<td align='center'>";
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
		echo "<td align='left'>" . $dato ['estado'] . "</td>";
		echo "<td align='left'>" . $msj . "</td>";
		echo "</tr>";
		$j++;
	endforeach
	;

?>
			
			           			</tbody>
</table>

<?php
	
	//echo $this->miFormulario->agrupacion ( 'fin' );
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


}
echo $this->miFormulario->marcoAgrupacion ( 'fin' );

?>