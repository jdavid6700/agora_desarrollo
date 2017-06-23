<?php
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}

use usuarios\cambiarClave\funcion\redireccion;

class FormularioRegistro {
	var $miConfigurador;
	var $lenguaje;
	var $miFormulario;
	var $miSql;
	function __construct($lenguaje, $formulario, $sql) {
		$this->miConfigurador = \Configurador::singleton ();
		$this->miConfigurador->fabricaConexiones->setRecursoDB ( 'principal' );
		$this->lenguaje = $lenguaje;
		$this->miFormulario = $formulario;
		$this->miSql = $sql;
	}
	
	function cambiafecha_format($fecha) {
		ereg("([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})", $fecha, $mifecha);
		$fechana = $mifecha[3] . "/" . $mifecha[2] . "/" . $mifecha[1];
		return $fechana;
	}
	
	function miForm() {
		
		// Rescatar los datos de este bloque
		$esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );
		
		// ---------------- SECCION: Parámetros Globales del Formulario ----------------------------------
		/**
		 * Atributos que deben ser aplicados a todos los controles de este formulario.
		 * Se utiliza un arreglo
		 * independiente debido a que los atributos individuales se reinician cada vez que se declara un campo.
		 *
		 * Si se utiliza esta técnica es necesario realizar un mezcla entre este arreglo y el específico en cada control:
		 * $atributos= array_merge($atributos,$atributosGlobales);
		 */
		$atributosGlobales ['campoSeguro'] = 'true';
		
		$_REQUEST ['tiempo'] = time ();
		$tiempo = $_REQUEST ['tiempo'];
		
		
		
		
		
		//*************************************************************************** DBMS *******************************
		//****************************************************************************************************************
		
		$conexion = 'estructura';
		$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
		
		//$conexion = 'sicapital';
		//$siCapitalRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
		
		//$conexion = 'centralUD';
		//$centralUDRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
		
		$conexion = 'argo_contratos';
		$argoRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
		
		$conexion = 'core_central';
		$coreRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
		
		$conexion = 'framework';
		$frameworkRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
		
		//*************************************************************************** DBMS *******************************
		//****************************************************************************************************************
		
		
		
		
		
		
		               
		// ---------------- SECCION: Parámetros Generales del Formulario ----------------------------------
		$esteCampo = $esteBloque ['nombre'];
		$atributos ['id'] = $esteCampo;
		$atributos ['nombre'] = $esteCampo;
		// Si no se coloca, entonces toma el valor predeterminado 'application/x-www-form-urlencoded'
		$atributos ['tipoFormulario'] = 'multipart/form-data';
		// Si no se coloca, entonces toma el valor predeterminado 'POST'
		$atributos ['metodo'] = 'POST';
		// Si no se coloca, entonces toma el valor predeterminado 'index.php' (Recomendado)
		$atributos ['action'] = 'index.php';
		// $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo );
		// Si no se coloca, entonces toma el valor predeterminado.
		$atributos ['estilo'] = '';
		$atributos ['marco'] = false;
		$tab = 1;
		// ---------------- FIN SECCION: de Parámetros Generales del Formulario ----------------------------
		// ----------------INICIAR EL FORMULARIO ------------------------------------------------------------
		$atributos ['tipoEtiqueta'] = 'inicio';
		echo $this->miFormulario->formulario ( $atributos );
		{
			// ---------------- SECCION: Controles del Formulario -----------------------------------------------
			
			$miPaginaActual = $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
			
			$rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
			$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/";
			$rutaBloque.= $esteBloque['grupo'] . "/" . $esteBloque['nombre'];
                        
			$directorio = $this->miConfigurador->getVariableConfiguracion ( "host" );
			$directorio .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/index.php?";
			$directorio .= $this->miConfigurador->getVariableConfiguracion ( "enlace" );
			
			$variable = "pagina=" . $miPaginaActual;
			$variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variable, $directorio );
			

			
			
			
			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
			$esteCampo = "marcoContratos";
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
			$atributos ['tipoEtiqueta'] = 'inicio';
			$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
			echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
			unset ( $atributos );
		
		
		unset ( $resultado );
		//var_dump($_REQUEST);
		//****************************************************************************************
		//****************************************************************************************
		
		//$cadenaSql = $this->miSql->getCadenaSql ( 'consultar_proveedor', $_REQUEST ["usuario"] );
		//$resultadoDoc = $frameworkRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
		//$numeroDocumento = $resultadoDoc[0]['identificacion'];
		
		$cadenaSql = $this->miSql->getCadenaSql ( 'consultar_DatosProveedor', $_REQUEST['idProveedor'] );
		$resultadoDats = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
		$idProveedor = $resultadoDats[0]['id_proveedor'];
		$tipoPersona = $resultadoDats[0]['tipopersona'];
		$nombrePersona = $resultadoDats[0]['nom_proveedor'];
		$correo = $resultadoDats[0]['correo'];
		$direccion = $resultadoDats[0]['direccion'];
		
		$numeroDocumento = $resultadoDats[0]['num_documento'];
		
		
		
		
		$variable = "pagina=" . $miPaginaActual;
		$variable .= "&opcion=" . "verCotizacionSolicitud";
		$variable .= "&idSolicitud=" . $_REQUEST['idObjeto'];
		$variable .= "&vigencia=" . $_REQUEST['vigencia'];
		$variable .= "&unidadEjecutora=" . $_REQUEST['unidadEjecutora'];
		$variable .= "&usuario=" . $_REQUEST['usuario'];
		$variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variable, $directorio );
		
		$atributos["id"]="botones";
		$atributos["estilo"]="marcoBotones widget";
		echo $this->miFormulario->division("inicio",$atributos);
		
		// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
		$esteCampo = 'botonRegresar';
		$atributos ['id'] = $esteCampo;
		$atributos ['enlace'] = $variable;
		$atributos ['tabIndex'] = 1;
		$atributos ['estilo'] = '';
		$atributos ['enlaceTexto'] = $this->lenguaje->getCadena ( $esteCampo );
		$atributos ['ancho'] = '10%';
		$atributos ['alto'] = '10%';
		$atributos ['redirLugar'] = true;
		echo $this->miFormulario->enlace ( $atributos );
		
		//------------------Fin Division para los botones-------------------------
		echo $this->miFormulario->division("fin");
		
		unset ( $atributos );
		
		
		$datosRespuesta = array (
				'idObjeto' => $_REQUEST['idObjeto'],
				'idProveedor' => $_REQUEST['idProveedor']
		);
		
		$cadenaSql = $this->miSql->getCadenaSql ( 'consultarIdRespuestaProveedor', $datosRespuesta );
		$resultadoIdRespuesta = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );

		
		
		$cadenaSql = $this->miSql->getCadenaSql ( 'consultarRespuestaProveedor', $resultadoIdRespuesta[0][0]);
		$resultadoRespuesta = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
		
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
				
		}
		echo $this->miFormulario->marcoAgrupacion ( 'fin', $atributos );
			
			

			
			if(isset($_REQUEST['tipoCotizacion']) && $_REQUEST['tipoCotizacion'] == 'BIEN'){
				$campo1 = "entregables";
				$campo2 = "plazoEntrega";
			}else{
				$campo1 = "desServicio";
				$campo2 = "detalleEjecucion";
			}
			
			$esteCampo = "marcoContratosTablaRes";
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
			$atributos ['tipoEtiqueta'] = 'inicio';
			$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo ) . "Persona: (".$numeroDocumento." - ". $nombrePersona . ")";
			echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
			{
			
			
				// ---------------- CONTROL: Lista Vigencia--------------------------------------------------------
				$esteCampo = "decision";
				$atributos ['nombre'] = $esteCampo;
				$atributos ['id'] = $esteCampo;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['tab'] = $tab ++;
				$atributos ['anchoEtiqueta'] = 250;
				$atributos ['evento'] = '';
				if (isset ( $estadoSolicitud )) {
					$atributos ['seleccion'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['seleccion'] = - 1;
				}
				$atributos ['deshabilitado'] = false;
				$atributos ['columnas'] = 1;
				$atributos ['tamanno'] = 1;
				$atributos ['ajax_function'] = "";
				$atributos ['ajax_control'] = $esteCampo;
				$atributos ['estilo'] = "jqueryui";
				$atributos ['validar'] = "required";
				$atributos ['limitar'] = false;
				$atributos ['anchoCaja'] = 60;
				$atributos ['miEvento'] = '';
				
				$matrizItems = array (
						array ( 1, 'APROBADO - (PROVEEDOR SELECCIONADO)' ),
						array ( 2, 'RECHAZADO - (GRACIAS)' )
				);
				
				$atributos ['matrizItems'] = $matrizItems;
				
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroLista ( $atributos );
				unset ( $atributos );
				// ----------------FIN CONTROL: Lista Vigencia--------------------------------------------------------
				
				
  				// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                $esteCampo = "respuesta";
                $atributos ['id'] = $esteCampo;
                $atributos ['nombre'] = $esteCampo;
                $atributos ['tipo'] = 'text';
                $atributos ['estilo'] = 'jqueryui';
                $atributos ['marco'] = true;
                $atributos ['estiloMarco'] = '';
                $atributos ["etiquetaObligatorio"] = true;
                $atributos ['columnas'] = 120;
                $atributos ['filas'] = 8;
                $atributos ['dobleLinea'] = 0;
                $atributos ['tabIndex'] = $tab;
                $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                $atributos ['validar'] = 'required,minSize[4]';
                $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
                $atributos ['deshabilitado'] = false;
                $atributos ['tamanno'] = 20;
                $atributos ['maximoTamanno'] = '';
                $atributos ['anchoEtiqueta'] = 220;
                $atributos ['textoEnriquecido'] = true; //Este atributo se coloca una sola vez en todo el formulario (ERROR paso de datos)

				if (isset ( $_REQUEST [$esteCampo] )) {
					$atributos ['valor'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['valor'] = '';
				}

                $tab ++;

                // Aplica atributos globales al control
                $atributos = array_merge($atributos, $atributosGlobales);
                echo $this->miFormulario->campoTextArea($atributos);
                unset($atributos);

				
                $esteCampo = 'idObjeto';
                $atributos ["id"] = $esteCampo; // No cambiar este nombre
                $atributos ["tipo"] = "hidden";
                $atributos ['estilo'] = '';
                $atributos ["obligatorio"] = false;
                $atributos ['marco'] = true;
                $atributos ["etiqueta"] = "";
                if (isset ( $_REQUEST [$esteCampo] )) {
                	$atributos ['valor'] = $_REQUEST [$esteCampo];
                } else {
                	$atributos ['valor'] = '';
                }
                $atributos = array_merge ( $atributos, $atributosGlobales );
                echo $this->miFormulario->campoCuadroTexto ( $atributos );
                unset ( $atributos );
                
                $esteCampo = 'idProveedor';
                $atributos ["id"] = $esteCampo; // No cambiar este nombre
                $atributos ["tipo"] = "hidden";
                $atributos ['estilo'] = '';
                $atributos ["obligatorio"] = false;
                $atributos ['marco'] = true;
                $atributos ["etiqueta"] = "";
                if (isset ( $_REQUEST [$esteCampo] )) {
                	$atributos ['valor'] = $_REQUEST [$esteCampo];
                } else {
                	$atributos ['valor'] = '';
                }
                $atributos = array_merge ( $atributos, $atributosGlobales );
                echo $this->miFormulario->campoCuadroTexto ( $atributos );
                unset ( $atributos );
                
                $esteCampo = 'idSolicitudIndividual';
                $atributos ["id"] = $esteCampo; // No cambiar este nombre
                $atributos ["tipo"] = "hidden";
                $atributos ['estilo'] = '';
                $atributos ["obligatorio"] = false;
                $atributos ['marco'] = true;
                $atributos ["etiqueta"] = "";
                if (isset ( $_REQUEST [$esteCampo] )) {
                	$atributos ['valor'] = $_REQUEST [$esteCampo];
                } else {
                	$atributos ['valor'] = '';
                }
                $atributos = array_merge ( $atributos, $atributosGlobales );
                echo $this->miFormulario->campoCuadroTexto ( $atributos );
                unset ( $atributos );
                
				/*
				$esteCampo = "marcoAnexo";
				$atributos ['id'] = $esteCampo;
				$atributos ["estilo"] = "jqueryui";
				$atributos ['tipoEtiqueta'] = 'inicio';
				$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
				echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
				
				// ----------------INICIO CONTROL: DOCUMENTO--------------------------------------------------------
				$esteCampo = "cotizacionSoporte";
				$atributos ["id"] = $esteCampo; // No cambiar este nombre
				$atributos ["nombre"] = $esteCampo;
				$atributos ["tipo"] = "file";
				// $atributos ["obligatorio"] = true;
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ["tabIndex"] = $tab ++;
				$atributos ["columnas"] = 1;
				$atributos ["estilo"] = "textoIzquierda";
				$atributos ["anchoEtiqueta"] = 400;
				$atributos ["tamanno"] = 500000;
				$atributos ["validar"] = "required";
				$atributos ["etiqueta"] = $this->lenguaje->getCadena ( $esteCampo );
				// $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
				// $atributos ["valor"] = $valorCodificado;
				$atributos = array_merge ( $atributos, $atributosGlobales );
				//echo $this->miFormulario->campoCuadroTexto ( $atributos );
				
				
				
				//------------------Division para los botones-------------------------
				$atributos["id"]="botones";
				$atributos["estilo"]="marcoBotones widget";
				echo $this->miFormulario->division("inicio",$atributos);
					
				$enlace = "<br><a href='". $resultadoRespuesta[0]['soporte_cotizacion'] ."' target='_blank'>";
				$enlace.="<img src='".$rutaBloque."/images/pdf.png' width='35px'><br>Anexo Cotización Detallada ";
				$enlace.="</a>";
				echo $enlace;
				//------------------Fin Division para los botones-------------------------
				echo $this->miFormulario->division("fin");
				//FIN enlace boton descargar RUT
				
				unset ( $atributos );
				// ----------------FIN CONTROL: DOCUMENTO--------------------------------------------------------
				
				echo $this->miFormulario->marcoAgrupacion ( 'fin' );
				*/
				
				
				
			
			}
			echo $this->miFormulario->marcoAgrupacion ( 'fin' );
			
			
			
			$tipo = 'warning';
			$mensaje =  "<b>IMPORTANTE</b><br>
							<br>
							Solo se permite responder al proveedor <b>(1) una</b> sola vez, por favor verfique la información relacionada
							y guarde posteriormente, recuerde que no podra hacer cambios de la información diligenciada.
							";
			// ---------------- SECCION: Controles del Formulario -----------------------------------------------
			$esteCampo = 'mensaje';
			$atributos["id"] = $esteCampo; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
			$atributos["etiqueta"] = "";
			$atributos["estilo"] = "centrar";
			$atributos["tipo"] = $tipo;
			$atributos["mensaje"] = $mensaje;
			echo $this->miFormulario->cuadroMensaje($atributos);
			unset($atributos);
			
			
				
				// ------------------Division para los botones-------------------------
				$atributos ["id"] = "botones";
				$atributos ["estilo"] = "marcoBotones";
				echo $this->miFormulario->division ( "inicio", $atributos );
				unset ( $atributos );
				{
					// -----------------CONTROL: Botón ----------------------------------------------------------------
					$esteCampo = 'botonGuardar';
					$atributos ["id"] = $esteCampo;
					$atributos ["tabIndex"] = $tab;
					$atributos ["tipo"] = 'boton';
					// submit: no se coloca si se desea un tipo button genérico
					$atributos ['submit'] = true;
					$atributos ["estiloMarco"] = '';
					$atributos ["estiloBoton"] = 'jqueryui';
					// verificar: true para verificar el formulario antes de pasarlo al servidor.
					$atributos ["verificar"] = true;
					$atributos ["tipoSubmit"] = 'jquery'; // Dejar vacio para un submit normal, en este caso se ejecuta la función submit declarada en ready.js
					$atributos ["valor"] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ['nombreFormulario'] = $esteBloque ['nombre'];
					$tab ++;
					
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoBoton ( $atributos );
					// -----------------FIN CONTROL: Botón -----------------------------------------------------------
				
				

				}
				echo $this->miFormulario->division ( "fin" );
				
		}
		echo $this->miFormulario->marcoAgrupacion ( 'fin', $atributos );
			
			// ------------------- SECCION: Paso de variables ------------------------------------------------
			
			/**
			 * En algunas ocasiones es útil pasar variables entre las diferentes páginas.
			 * SARA permite realizar esto a través de tres
			 * mecanismos:
			 * (a). Registrando las variables como variables de sesión. Estarán disponibles durante toda la sesión de usuario. Requiere acceso a
			 * la base de datos.
			 * (b). Incluirlas de manera codificada como campos de los formularios. Para ello se utiliza un campo especial denominado
			 * formsara, cuyo valor será una cadena codificada que contiene las variables.
			 * (c) a través de campos ocultos en los formularios. (deprecated)
			 */
			// En este formulario se utiliza el mecanismo (b) para pasar las siguientes variables:
			
			$valorCodificado = "action=" . $esteBloque ["nombre"];
			$valorCodificado .= "&pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
			$valorCodificado .= "&bloque=" . $esteBloque ['nombre'];
			$valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
			$valorCodificado .= "&opcion=registrarRespuestaCot";
			$valorCodificado .= "&usuario=".$_REQUEST['usuario'];

			/**
			 * SARA permite que los nombres de los campos sean dinámicos.
			 * Para ello utiliza la hora en que es creado el formulario para
			 * codificar el nombre de cada campo. Si se utiliza esta técnica es necesario pasar dicho tiempo como una variable:
			 * (a) invocando a la variable $_REQUEST ['tiempo'] que se ha declarado en ready.php o
			 * (b) asociando el tiempo en que se está creando el formulario
			 */
			$valorCodificado .= "&campoSeguro=" . $_REQUEST ['tiempo'];
			$valorCodificado .= "&tiempo=" . time ();
			// Paso 2: codificar la cadena resultante
			$valorCodificado = $this->miConfigurador->fabricaConexiones->crypto->codificar ( $valorCodificado );
			
			$atributos ["id"] = "formSaraData"; // No cambiar este nombre
			$atributos ["tipo"] = "hidden";
			$atributos ['estilo'] = '';
			$atributos ["obligatorio"] = false;
			$atributos ['marco'] = true;
			$atributos ["etiqueta"] = "";
			$atributos ["valor"] = $valorCodificado;
			echo $this->miFormulario->campoCuadroTexto ( $atributos );
			unset ( $atributos );
			
			$atributos ['marco'] = false;
			$atributos ['tipoEtiqueta'] = 'fin';
			echo $this->miFormulario->formulario ( $atributos );
			
			return true;
		
	}
}

$miSeleccionador = new FormularioRegistro ( $this->lenguaje, $this->miFormulario, $this->sql );

$miSeleccionador->miForm ();
?>