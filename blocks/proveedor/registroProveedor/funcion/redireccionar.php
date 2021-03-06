<?php

namespace proveedor\registroProveedor\funcion;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("index.php");
	exit ();
}
class redireccion {
	public static function redireccionar($opcion, $valor = "") {
		$miConfigurador = \Configurador::singleton ();
		$miPaginaActual = $miConfigurador->getVariableConfiguracion ( "pagina" );

		
		switch ($opcion) {
			
			case "registroProveedor" :
				$variable = "pagina=" . $miPaginaActual;
				$variable .= "&opcion=mensaje";
				$variable .= "&mensaje=confirma";
				$variable .= "&nit=" . $valor ['num_documento'];
				$variable .= "&correo=" . $valor ['correo'];
				$variable .= "&clave=" . $valor ['contrasena'];
				$variable .= "&generada=" . $valor ['generadaPass'];
				$variable .= "&id_usuario=" . $valor ['id_usuario'];
				$variable .= "&tipo_identificacion=" . $valor ['tipo_identificacion'];
				$variable .= "&tipo_persona=" . $valor ['tipo_persona'];
				$variable .= "&tipo=" . $valor ['tipo'];
				$variable .= "&rol_menu=" . $valor ['rolMenu'];
				$variable .= "&telefono=" . $valor ['telefono'];
				$variable .= "&nombres=" . $valor ['nombre'];
				$variable .= "&apellidos=" . $valor ['apellido'];
				break;				
	
			case "registroActividad" :
				$variable = "pagina=" . $miPaginaActual;
				$variable.= "&opcion=mensaje";
				$variable.= "&mensaje=registroActividad";
				$variable.="&num_documento=" . $valor['num_documento'];
				$variable.="&actividades=" . $valor['actividades'];
				$variable.="&idProveedor=" . $valor['fk_id_proveedor'];
				$variable.="&tipo_persona=" . $valor['tipo_persona'];
				break;	
                            
 			case "existeProveedor" :
				$variable = "pagina=" . $miPaginaActual;
				$variable.= "&opcion=mensaje";
				$variable.= "&mensaje=mensajeExisteProveedor";
				$variable.="&nit=" . $valor;
				break;
				
			case "existeProveedorLegal" :
				$variable = "pagina=" . $miPaginaActual;
				$variable .= "&opcion=mensaje";
				$variable .= "&mensaje=mensajeExisteProveedorLegal";
				$variable .= "&nit=" . $valor;
				break;
			
			case "noregistro" :
				$variable = "pagina=" . $miPaginaActual;
				$variable .= "&opcion=mensaje";
				$variable .= "&mensaje=error";
				$variable .= "&caso=".$valor;
				break;

			case "noregistroUsuario" :
				$variable = "pagina=" . $miPaginaActual;
				$variable .= "&opcion=mensaje";
				$variable .= "&mensaje=errorUsuario";
				break;	
			
			case "mensajeExisteActividad" :
				$variable = "pagina=" . $miPaginaActual;
				$variable.= "&opcion=mensaje";
				$variable.= "&mensaje=mensajeExisteActividad";
				$variable.="&nit=" . $valor;
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
				$variable .= "&idPersona=".$valor;
				break;
			
			case "noActualizo" :
				$variable = "pagina=" . $miPaginaActual;
				$variable .= "&opcion=mensaje";
				$variable .= "&mensaje=noActualizo";
				$variable .= "&caso=".$valor;
				$variable .= "&idPersona=".$valor;
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