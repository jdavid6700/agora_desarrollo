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
	$esteCampo = "marcoInfoReg";
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
	<p>A continuación podra generar el certificado de registro en el Sistema, para
	validar que su información esta disponible para los procesos que así lo requieran.</p>
</div>


<?php
	
}
echo $this->miFormulario->marcoAgrupacion ( 'fin', $atributos );


$valorCodificado = "pagina=" . $miPaginaActual;
$valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
$valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];

//INICIO enlace boton descargar resumen
$variableResumen = "pagina=" . $miPaginaActual;
$variableResumen.= "&action=".$esteBloque["nombre"];
$variableResumen.= "&bloque=" . $esteBloque["id_bloque"];
$variableResumen.= "&bloqueGrupo=" . $esteBloque["grupo"];
$variableResumen.= "&opcion=certRegistro";
$variableResumen.= "&nomPersona=" . $nombrePersona;
$variableResumen.= "&numDocumento=" . $numeroDocumento;
$variableResumen.= "&tipoPersona=" . $tipoPersona;
$variableResumen = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableResumen, $directorio);




				$esteCampo = "marcoCertReg";
				$atributos ['id'] = $esteCampo;
				$atributos ["estilo"] = "jqueryui";
				$atributos ['tipoEtiqueta'] = 'inicio';
				$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
				echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
				{
					
				//INICIO enlace boton descargar RUT
				//------------------Division para los botones-------------------------
				$atributos["id"]="botones";
				$atributos["estilo"]="marcoBotones widget";
				echo $this->miFormulario->division("inicio",$atributos);
			
				$enlace = "<a href='".$variableResumen."'>";
				$enlace.="<img src='".$rutaBloque."/images/pdf.png' width='35px'><br>Certificado de Registro ";
				$enlace.="</a><br><br>";       
				echo $enlace;
				//------------------Fin Division para los botones-------------------------
				echo $this->miFormulario->division("fin");
				//FIN enlace boton descargar RUT
				
				
				}
				echo $this->miFormulario->marcoAgrupacion ( 'fin' );
			
			



$atributos ['marco'] = true;
$atributos ['tipoEtiqueta'] = 'fin';
echo $this->miFormulario->formulario ( $atributos );
unset ( $atributos );




?>