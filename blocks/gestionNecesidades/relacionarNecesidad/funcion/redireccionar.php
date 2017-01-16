<?php

namespace hojaDeVida\crearDocente\funcion;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("index.php");
	exit ();
}
class redireccion {
	public static function redireccionar($opcion, $valor = "") {
		$miConfigurador = \Configurador::singleton ();
		$miPaginaActual = $miConfigurador->getVariableConfiguracion ( "pagina" );
		//var_dump($_REQUEST);exit();
		switch ($opcion) {

			case "inserto" :
				$variable = "pagina=" . $miPaginaActual;
				$variable .= "&opcion=mensaje";
				$variable .= "&mensaje=confirma";
				$variable .= "&idObjeto=".$valor['idObjeto'];
				$variable .= "&numSolicitud=".$valor['numero_solicitud'];
				$variable .= "&vigencia=".$valor['vigencia'];
				$variable .= "&unidadEjecutora=".$valor['unidad_ejecutora'];
				$variable .= "&numCotizaciones=".$valor['cotizaciones'];
				$variable .= "&tipoNecesidad=".$valor['tipo_necesidad'];
				$variable .= "&estadoSolicitud=".$valor['estadoSolicitud'];
				$variable .= "&usuario=".$valor['usuario'];
				break;
				
			case "insertoCotizacion" :
				$variable = "pagina=" . $miPaginaActual;
				$variable .= "&opcion=mensaje";
				$variable .= "&mensaje=confirmaCotizacion";
                $variable .= "&idObjeto=".$valor['idObjeto'];
                $variable .= "&idCodigo=".$valor['idCodigo'];
                $variable .= "&usuario=".$valor['usuario'];
				break;				
			
			case "noInserto" :
				$variable = "pagina=" . $miPaginaActual;
				$variable .= "&opcion=mensaje";
				$variable .= "&mensaje=error";
				break;
			
			case "noItems" :
				$variable = "pagina=" . $miPaginaActual;
				$variable .= "&opcion=mensaje";
				$variable .= "&mensaje=otros";
				$variable .= "&errores=noItems";
				break;
			
			case "noDatos" :
				$variable = "pagina=" . $miPaginaActual;
				$variable .= "&opcion=mensaje";
				$variable .= "&mensaje=otros";
				$variable .= "&errores=noDatos";
				break;
			
			case "regresar" :
				$variable = "pagina=" . $miPaginaActual;
				break;
				
			case "actualizo" :
				$variable = "pagina=" . $miPaginaActual;
				$variable .= "&opcion=mensaje";
				$variable .= "&mensaje=actualizo";
				$variable .= "&docente=" . $valor;
				break;
			
			case "noActualizo" :
				$variable = "pagina=" . $miPaginaActual;
				$variable .= "&opcion=mensaje";
				$variable .= "&mensaje=noActualizo";
				$variable .= "&docente=" . $valor;
				break;
			
			case "registrar" :
				$variable = "pagina=" . $miPaginaActual;
				$variable .= "&opcion=asociarActa";
				break;
			
			case "paginaPrincipal" :
				$variable = "pagina=" . $miPaginaActual;
				break;
			
			case "paginaConsulta" :
				$variable = "pagina=" . $miPaginaActual;
				$variable .= "&opcion=consultar";
				$variable .= "&id_docente=".$valor[0];
				$variable .= "&facultad=".$valor[1];
				$variable .= "&proyectoCurricular=".$valor[2];
				break;
				
			case "registroActividad" :
				$variable = "pagina=" . $miPaginaActual;
				$variable .= "&opcion=mensaje";
				$variable .= "&mensaje=registroActividad";
				$variable .= "&idObjeto=" . $valor ['idObjeto'];
				$variable .= "&actividad=" . $valor ['actividad'];
				$variable .= "&numSolicitud=".$valor['idSolicitud'];
				$variable .= "&vigencia=".$valor['vigencia'];
				$variable .= "&unidadEjecutora=".$valor['unidadEjecutora'];
				$variable .= "&tipoNecesidad=".$valor['tipoNecesidad'];
				$variable .= "&numCotizaciones=".$valor['numCotizaciones'];
				$variable .= "&usuario=".$valor['usuario'];
				break;
				
			case "registroNucleo" :
				$variable = "pagina=" . $miPaginaActual;
				$variable .= "&opcion=mensaje";
				$variable .= "&mensaje=registroNucleo";
				$variable .= "&idObjeto=" . $valor ['idObjeto'];
				$variable .= "&nucleo=" . $valor ['objetoNBC'];
				$variable .= "&numSolicitud=" . $valor ['idSolicitud'];
				$variable .= "&vigencia=" . $valor ['vigencia'];
				$variable .= "&unidadEjecutora=" . $valor ['unidadEjecutora'];
				$variable .= "&tipoNecesidad=" . $valor ['tipoNecesidad'];
				$variable .= "&numCotizaciones=" . $valor ['numCotizaciones'];
				$variable .= "&modificarNBC=" . $valor ['modificarNBC'];
				$variable .= "&usuario=".$valor['usuario'];
				break;
			
			case "mensajeExisteActividad" :
				$variable = "pagina=" . $miPaginaActual;
				$variable .= "&opcion=mensaje";
				$variable .= "&mensaje=mensajeExisteActividad";
				$variable .= "&idObjeto=" . $valor ['idObjeto'];
				$variable .= "&actividad=" . $valor ['actividad'];
				$variable .= "&numSolicitud=".$valor['idSolicitud'];
				$variable .= "&vigencia=".$valor['vigencia'];
				$variable .= "&unidadEjecutora=".$valor['unidadEjecutora'];
				$variable .= "&tipoNecesidad=".$valor['tipoNecesidad'];
				$variable .= "&numCotizaciones=".$valor['numCotizaciones'];
				$variable .= "&usuario=".$valor['usuario'];
				break;
				
			case "noregistro" :
				$variable = "pagina=" . $miPaginaActual;
				$variable .= "&opcion=mensaje";
				$variable .= "&mensaje=error";
				break;
				
			case "cotizacion" :
				$variable = "pagina=" . $miPaginaActual;
				$variable .= "&opcion=cotizacion";
				$variable.="&idObjeto=" . $_REQUEST["idObjeto"];
				$variable.="&numCotizaciones=" . $_REQUEST["numCotizaciones"];
				$variable .= "&usuario=".$valor['usuario'];
				break;
				
		}
		
		foreach ( $_REQUEST as $clave => $valor ) {
			unset ( $_REQUEST [$clave] );
		}
		
		$url = $miConfigurador->configuracion ["host"] . $miConfigurador->configuracion ["site"] . "/index.php?";
		$enlace = $miConfigurador->configuracion ['enlace'];
		$variable = $miConfigurador->fabricaConexiones->crypto->codificar ( $variable );
		$_REQUEST [$enlace] = $enlace . '=' . $variable;
		$redireccion = $url . $_REQUEST [$enlace];
		
		echo "<script>location.replace('" . $redireccion . "')</script>";
		
		// $enlace =$miConfigurador->getVariableConfiguracion("enlace");
		// $variable = $miConfigurador->fabricaConexiones->crypto->codificar($variable);
		// // echo $enlace;
		// // // echo $variable;
		// // exit;
		// $_REQUEST[$enlace] = $variable;
		// $_REQUEST["recargar"] = true;
		// return true;
	}
}

?>