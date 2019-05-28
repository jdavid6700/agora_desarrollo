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


if(isset($_REQUEST['tipo_persona'])){

	$datosControl = array (
			'tipo_persona'=>$_REQUEST['tipo_persona'],
			'num_documento'=>$_REQUEST['num_documento']
	);

}else{

	$cadenaSql = $this->sql->getCadenaSql ( 'consultar_proveedor', $_REQUEST ["usuario"] );
	$resultadoUser = $frameworkRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
	if($resultadoUser[0]['tipo_identificacion'] != 'NIT'){
		$_REQUEST['tipo_persona'] = "NATURAL";
	}else{
		$_REQUEST['tipo_persona'] = "JURIDICA";
	}
		
	$datosControl = array (
			'tipo_persona'=>$_REQUEST['tipo_persona'],
			'num_documento'=>$resultadoUser[0]['identificacion']
	);


}

$cadenaSql = $this->sql->getCadenaSql ( 'buscarProveedorByUnique', $datosControl );
$datosProvedor = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );

$esteCampo = "marcoInfoCont";
$atributos ['id'] = $esteCampo;
$atributos ["estilo"] = "jqueryui";
$atributos ['tipoEtiqueta'] = 'inicio';
$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
{
	//INICIO INFORMACION
	echo "<span class='textoElegante textoGrande textoAzul'>Nombre de la Persona: </span>";
	echo "<span class='textoElegante textoGrande textoGris'>". $datosProvedor[0]['nom_proveedor'] . "</span></br>";
	echo "<span class='textoElegante textoGrande textoAzul'>Documento : </span>";
	echo "<span class='textoElegante textoGrande textoGris'>". $datosProvedor[0]['num_documento'] . "</span></br>";
	echo "<span class='textoElegante textoGrande textoAzul'>Tipo Persona : </span>";
	echo "<span class='textoElegante textoGrande textoGris'>". $datosProvedor[0]['tipopersona'] . "</span></br>";
	echo "<span class='textoElegante textoGrande textoAzul'>Dirección : </span>";
	echo "<span class='textoElegante textoGrande textoGris'>". $datosProvedor[0]['direccion'] . "</span></br>";
	echo "<span class='textoElegante textoGrande textoAzul'>Correo : </span>";
	echo "<span class='textoElegante textoGrande textoGris'>". $datosProvedor[0]['correo'] . "</span></br>";
	//FIN INFORMACION

echo '<script> 
			var pagTxt = 500;
			 </script>';
?>

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
$variableResumen.= "&nomPersona=" . $datosProvedor[0]['nom_proveedor'];
$variableResumen.= "&numDocumento=" . $datosProvedor[0]['num_documento'];
$variableResumen.= "&tipoPersona=" . $datosProvedor[0]['tipopersona'];
$variableResumen.= "&usuario=" . $_REQUEST ["usuario"];
$variableResumen.= "&idProveedor=" . $datosProvedor[0]['id_proveedor'];
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
				$enlace.="<img src='".$rutaBloque."/images/pdf.png' width='35px'><br>Generar certificado de registro ";
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