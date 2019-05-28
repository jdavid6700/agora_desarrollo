<?php

namespace gestionParametros\gestionarSupervisor\funcion;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("index.php");
	exit ();
}
class redireccion {
	public static function redireccionar($opcion, $valor = "") {
		$miConfigurador = \Configurador::singleton ();
		$miPaginaActual = $miConfigurador->getVariableConfiguracion ( "pagina" );

		
		switch ($opcion) {

            case "inserto":
                            $variable = "pagina=" . $miPaginaActual;
                            $variable.="&opcion=mensaje";
                            $variable.="&mensaje=insertoFuncionario";
                            $variable.="&fecha_inicio=" . $valor['fecha_inicio'];
                            $variable.="&fecha_fin=" . $valor['fecha_fin'];
                            $variable.="&tercero=" . $valor['tercero'];
                            $variable.="&dependencia=" . $valor['dependencia'];
                            $variable.="&tipoFuncionario=" . $valor['tipoFuncionario'];                            
                            $variable.="&acta=" . $valor['acta'];
                            $variable.="&nombreFuncionario=" . $valor['nombreFuncionario'];                            
                            $variable.="&nombreDependencia=" . $valor['nombreDependencia'];
                            $variable.="&usuario=" . $valor['usuario'];
      
             break;		

             case "noInserto":
                            $variable = "pagina=" . $miPaginaActual;
                            $variable.="&opcion=mensaje";
                            $variable.="&mensaje=NoinsertoFuncionario";
                            $variable.="&fecha_inicio=" . $valor['fecha_inicio'];
                            $variable.="&fecha_fin=" . $valor['fecha_fin'];
                            $variable.="&tercero=" . $valor['tercero'];
                            $variable.="&dependencia=" . $valor['dependencia'];
                            $variable.="&tipoFuncionario=" . $valor['tipoFuncionario'];                            
                            $variable.="&acta=" . $valor['acta'];
                            $variable.="&nombreFuncionario=" . $valor['nombreFuncionario'];                            
                            $variable.="&nombreDependencia=" . $valor['nombreDependencia'];
                            $variable.="&usuario=" . $valor['usuario'];
             break;	

            case "modifico":
                            $variable = "pagina=" . $miPaginaActual;
                            $variable.="&opcion=mensaje";
                            $variable.="&mensaje=modificoFuncionario";
                            $variable.="&fecha_inicio=" . $valor['fecha_inicio'];
                            $variable.="&fecha_fin=" . $valor['fecha_fin'];
                            $variable.="&tercero=" . $valor['tercero'];
                            $variable.="&dependencia=" . $valor['dependencia'];
                            $variable.="&tipoFuncionario=" . $valor['tipoFuncionario'];                            
                            $variable.="&acta=" . $valor['acta'];
                            $variable.="&nombreFuncionario=" . $valor['nombreFuncionario'];                            
                            $variable.="&nombreDependencia=" . $valor['nombreDependencia'];
                            $variable.="&usuario=" . $valor['usuario'];
             break;		

             case "noModifico":
                            $variable = "pagina=" . $miPaginaActual;
                            $variable.="&opcion=mensaje";
                            $variable.="&mensaje=NomodificoFuncionario";
                            $variable.="&fecha_inicio=" . $valor['fecha_inicio'];
                            $variable.="&fecha_fin=" . $valor['fecha_fin'];
                            $variable.="&tercero=" . $valor['tercero'];
                            $variable.="&dependencia=" . $valor['dependencia'];
                            $variable.="&tipoFuncionario=" . $valor['tipoFuncionario'];         
                            $variable.="&nombreFuncionario=" . $valor['nombreFuncionario'];                            
                            $variable.="&nombreDependencia=" . $valor['nombreDependencia'];                   
                            $variable.="&acta=" . $valor['acta'];
                            $variable.="&usuario=" . $valor['usuario'];
             break;					


             

             /////////////////eliminar
	
			case "registroActividad" :
				$variable = "pagina=" . $miPaginaActual;
				$variable.= "&opcion=mensaje";
				$variable.= "&mensaje=registroActividad";
				$variable.="&nit=" . $valor['nit'];
				$variable.="&actividad=" . $valor['actividad'];
				break;	
                            
 			case "existeProveedor" :
				$variable = "pagina=" . $miPaginaActual;
				$variable.= "&opcion=mensaje";
				$variable.= "&mensaje=mensajeExisteProveedor";
				$variable.="&nit=" . $valor;
				break;                           
                            
			
			case "noregistro" :
				$variable = "pagina=" . $miPaginaActual;
				$variable .= "&opcion=mensaje";
				$variable .= "&mensaje=error";
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
				break;
			
			case "noActualizo" :
				$variable = "pagina=" . $miPaginaActual;
				$variable .= "&opcion=mensaje";
				$variable .= "&mensaje=noActualizo";
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