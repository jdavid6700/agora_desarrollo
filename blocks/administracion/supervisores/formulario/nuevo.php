<?php
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}
/**
 * Este script está incluido en el método html de la clase Frontera.class.php.
 *
 * La ruta absoluta del bloque está definida en $this->ruta
 */

$esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );

$nombreFormulario = $esteBloque ["nombre"];

include_once ("core/crypto/Encriptador.class.php");
$cripto = Encriptador::singleton ();
$valorCodificado = "action=" . $esteBloque ["nombre"];
$valorCodificado .= "&bloque=" . $esteBloque ["id_bloque"];
$valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];

$valorCodificado = $cripto->codificar ( $valorCodificado );
$directorio = $this->miConfigurador->getVariableConfiguracion ( "rutaUrlBloque" ) . "/imagen/";

// ------------------Division para las pestañas-------------------------
$atributos ["id"] = "tabs";
$atributos ["estilo"] = "";

echo $this->miFormulario->division ( "inicio", $atributos );
unset ( $atributos );
{
	// -------------------- Listado de Pestañas (Como lista No Ordenada) -------------------------------
	
	$items = array (
			"tabEvaluar" => $this->lenguaje->getCadena ( "tabEvaluar" ),
			"tabConsultar" => $this->lenguaje->getCadena ( "tabConsultar" ),
			"tabConsultarProveedor" => $this->lenguaje->getCadena ( "tabConsultarProveedor" )			
	);
	$atributos ["items"] = $items;
	$atributos ["estilo"] = "jqueryui";
	$atributos ["pestañas"] = "true";
	echo $this->miFormulario->listaNoOrdenada ( $atributos );

	// -----------------INICIO Division para la pestaña 1-------------------------
		$esteCampo = "tabEvaluar";
		$atributos ['id'] = $esteCampo;
		$atributos ["estilo"] = "jqueryui";
		$atributos ['tipoEtiqueta'] = 'inicio';
		// $atributos ["leyenda"] = "Contratos ViceRectoria";
		echo $this->miFormulario->agrupacion ( 'inicio', $atributos );
		unset ( $atributos );
		{
			include ($this->ruta . "formulario/tabs/tabFormularioCrear.php");
		}
		echo $this->miFormulario->agrupacion ( 'fin' );
	// -----------------FIN Division para la pestaña 1-------------------------
	
	// -----------------INICIO Division para la pestaña 2-------------------------
		$esteCampo = "tabConsultar";
		$atributos ['id'] = $esteCampo;
		$atributos ["estilo"] = "jqueryui";
		$atributos ['tipoEtiqueta'] = 'inicio';
		// $atributos ["leyenda"] = "Contratos ViceRectoria";
		echo $this->miFormulario->agrupacion ( 'inicio', $atributos );
		unset ( $atributos );
		{
			include ($this->ruta . "formulario/tabs/tabSearchByContrato.php");
		}
		echo $this->miFormulario->agrupacion ( 'fin' );
	// -----------------Fin Division para la pestaña 2-------------------------

	// -----------------INICIO Division para la pestaña 3-------------------------
		$esteCampo = "tabConsultarProveedor";
		$atributos ['id'] = $esteCampo;
		$atributos ["estilo"] = "jqueryui";
		$atributos ['tipoEtiqueta'] = 'inicio';
		// $atributos ["leyenda"] = "Contratos ViceRectoria";
		echo $this->miFormulario->agrupacion ( 'inicio', $atributos );
		unset ( $atributos );
		{
			include ($this->ruta . "formulario/tabs/tabSearchByproveedor.php");
		}
		echo $this->miFormulario->agrupacion ( 'fin' );	
	// -----------------FIN Division para la pestaña 3-------------------------	
	
	
	
	
	
	
	
	
}
echo $this->miFormulario->division ( "fin" );

?>
