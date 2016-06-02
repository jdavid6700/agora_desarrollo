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
			"tabHistoricoAnual" => $this->lenguaje->getCadena ( "tabHistoricoAnual" ),
			"tabProvSeleccionados" => $this->lenguaje->getCadena ( "tabProvSeleccionados" ),
			"tabTotalProveedoresA" => $this->lenguaje->getCadena ( "tabTotalProveedoresA" ),
			"tabTotalProveedoresB" => $this->lenguaje->getCadena ( "tabTotalProveedoresB" ),
			"tabTotalProveedoresC" => $this->lenguaje->getCadena ( "tabTotalProveedoresC" ),
			"tabProcesoContratacion" => $this->lenguaje->getCadena ( "tabProcesoContratacion" )
			
	);
	$atributos ["items"] = $items;
	$atributos ["estilo"] = "jqueryui";
	$atributos ["pestañas"] = "true";
	echo $this->miFormulario->listaNoOrdenada ( $atributos );
	
	// -----------------INICIO Division para la pestaña 1-------------------------
		$esteCampo = "tabHistoricoAnual";
		$atributos ['id'] = $esteCampo;
		$atributos ["estilo"] = "jqueryui";
		$atributos ['tipoEtiqueta'] = 'inicio';
		// $atributos ["leyenda"] = "Contratos ViceRectoria";
		echo $this->miFormulario->agrupacion ( 'inicio', $atributos );
		unset ( $atributos );
		{
			include ($this->ruta . "formulario/tabs/tabHistoricoAnual.php");
		}
		echo $this->miFormulario->agrupacion ( 'fin' );
	// -----------------FIN Division para la pestaña 1-------------------------

	// -----------------INICIO Division para la pestaña 1-------------------------
		$esteCampo = "tabProvSeleccionados";
		$atributos ['id'] = $esteCampo;
		$atributos ["estilo"] = "jqueryui";
		$atributos ['tipoEtiqueta'] = 'inicio';
		// $atributos ["leyenda"] = "Contratos ViceRectoria";
		echo $this->miFormulario->agrupacion ( 'inicio', $atributos );
		unset ( $atributos );
		{
			include ($this->ruta . "formulario/tabs/tabProvSeleccionados.php");
		}
		echo $this->miFormulario->agrupacion ( 'fin' );
	// -----------------FIN Division para la pestaña 1-------------------------
	
	// -----------------INICIO Division para la pestaña 2-------------------------
		$esteCampo = "tabTotalProveedoresA";
		$atributos ['id'] = $esteCampo;
		$atributos ["estilo"] = "jqueryui";
		$atributos ['tipoEtiqueta'] = 'inicio';
		// $atributos ["leyenda"] = "Contratos ViceRectoria";
		echo $this->miFormulario->agrupacion ( 'inicio', $atributos );
		unset ( $atributos );
		{
			include ($this->ruta . "formulario/tabs/tabProveedorTipo.php");
		}
		echo $this->miFormulario->agrupacion ( 'fin' );
	// -----------------Fin Division para la pestaña 2-------------------------

	// -----------------INICIO Division para la pestaña 3-------------------------
		$esteCampo = "tabTotalProveedoresB";
		$atributos ['id'] = $esteCampo;
		$atributos ["estilo"] = "jqueryui";
		$atributos ['tipoEtiqueta'] = 'inicio';
		// $atributos ["leyenda"] = "Contratos ViceRectoria";
		echo $this->miFormulario->agrupacion ( 'inicio', $atributos );
		unset ( $atributos );
		{
			include ($this->ruta . "formulario/tabs/tabProveedorTipoB.php");
		}
		echo $this->miFormulario->agrupacion ( 'fin' );	
	// -----------------FIN Division para la pestaña 3-------------------------
	
	// -----------------INICIO Division para la pestaña 3-------------------------
		$esteCampo = "tabTotalProveedoresC";
		$atributos ['id'] = $esteCampo;
		$atributos ["estilo"] = "jqueryui";
		$atributos ['tipoEtiqueta'] = 'inicio';
		// $atributos ["leyenda"] = "Contratos ViceRectoria";
		echo $this->miFormulario->agrupacion ( 'inicio', $atributos );
		unset ( $atributos );
		{
			include ($this->ruta . "formulario/tabs/tabProveedorTipoC.php");
		}
		echo $this->miFormulario->agrupacion ( 'fin' );	
	// -----------------FIN Division para la pestaña 3-------------------------	
	
}
echo $this->miFormulario->division ( "fin" );

?>
