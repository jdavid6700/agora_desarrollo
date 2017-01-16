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

$conexion = "agora";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );

// ------------------Division para las pestañas-------------------------
$atributos ["id"] = "tabs";
$atributos ["estilo"] = "";

echo $this->miFormulario->division ( "inicio", $atributos );
unset ( $atributos );
{
	// -------------------- Listado de Pestañas (Como lista No Ordenada) -------------------------------
	
	
	
	if(isset($_REQUEST['tipoNecesidad'])){
		
		if($_REQUEST['tipoNecesidad'] == "SERVICIO"){
			$tabMensaje = "tabConsultarConv";
		}else{
			$tabMensaje = "tabConsultar";
		}
		
	}else{
		
		$datosSolicitudNecesidad = array (
				'idSolicitud' => $_REQUEST['idSolicitud'],
				'vigencia' => $_REQUEST['vigencia'],
				'unidadEjecutora' => $_REQUEST['unidadEjecutora']
		);
		
		$cadena_sql = $this->sql->getCadenaSql ( "informacionSolicitudAgora", $datosSolicitudNecesidad);
		$resultadoNecesidadRelacionada = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
		
		if($resultadoNecesidadRelacionada[0]['tipo_necesidad'] == "SERVICIO"){
			$tabMensaje = "tabConsultarConv";
		}else{
			$tabMensaje = "tabConsultar";
		}
		
	}
	
	
	
	$items = array (
			
			"tabConsultar" => $this->lenguaje->getCadena ( $tabMensaje )
	);
	$atributos ["items"] = $items;
	$atributos ["estilo"] = "jqueryui";
	$atributos ["pestañas"] = "true";
	echo $this->miFormulario->listaNoOrdenada ( $atributos );
	
	$esteCampo = "tabConsultar";
	$atributos ['id'] = $esteCampo;
	$atributos ["estilo"] = "jqueryui";
	$atributos ['tipoEtiqueta'] = 'inicio';
	// $atributos ["leyenda"] = "Contratos ViceRectoria";
	echo $this->miFormulario->agrupacion ( 'inicio', $atributos );
	unset ( $atributos );
	{
		
		include ($this->ruta . "formulario/tabs/tabFormularioCrear.php");
		// -----------------Fin Division para la pestaña 2-------------------------
	}
	echo $this->miFormulario->agrupacion ( 'fin' );
	

}
echo $this->miFormulario->division ( "fin" );

?>
