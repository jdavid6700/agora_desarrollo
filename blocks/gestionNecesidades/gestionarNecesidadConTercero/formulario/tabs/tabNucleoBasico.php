<?php
namespace asignacionPuntajes\salariales\experienciaDireccionAcademica\formulario;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}
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
	
	function formulario() {
		
		/**
		 * IMPORTANTE: Este formulario está utilizando jquery.
		 * Por tanto en el archivo ready.php se delaran algunas funciones js
		 * que lo complementan.
		 */
		
		$conexion = "agora";
		$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
		
		$conexion = "sicapital";
		$siCapitalRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
		
		// Rescatar los datos de este bloque
		$esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );
		$miPaginaActual = $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
			
		$directorio = $this->miConfigurador->getVariableConfiguracion ( "host" );
		$directorio .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/index.php?";
		$directorio .= $this->miConfigurador->getVariableConfiguracion ( "enlace" );
			
		$rutaBloque = $this->miConfigurador->getVariableConfiguracion ( "host" );
		$rutaBloque .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/";
		$rutaBloque .= $esteBloque ['grupo'] . '/' . $esteBloque ['nombre'];
		
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
		
		// -------------------------------------------------------------------------------------------------
		// ---------------- SECCION: Parámetros Generales del Formulario ----------------------------------
		$esteCampo = $esteBloque ['nombre']."Registrar";
		$atributos ['id'] = $esteCampo;
		$atributos ['nombre'] = $esteCampo;
		
		// Si no se coloca, entonces toma el valor predeterminado 'application/x-www-form-urlencoded'
		$atributos ['tipoFormulario'] = 'multipart/form-data';
		
		// Si no se coloca, entonces toma el valor predeterminado 'POST'
		$atributos ['metodo'] = 'POST';
		
		// Si no se coloca, entonces toma el valor predeterminado 'index.php' (Recomendado)
		$atributos ['action'] = 'index.php';
		$atributos ['titulo'] = '';
		
		// Si no se coloca, entonces toma el valor predeterminado.
		$atributos ['estilo'] = '';
		$atributos ['marco'] = true;
		$tab = 1;
		
		// ---------------- FIN SECCION: de Parámetros Generales del Formulario ----------------------------
		// ----------------INICIAR EL FORMULARIO ------------------------------------------------------------
		$atributos ['tipoEtiqueta'] = 'inicio';
		echo $this->miFormulario->formulario ( $atributos );
		
		
		$cadenaSql = $this->miSql->getCadenaSql ( 'consultarNBCImp', $_REQUEST['idObjeto']  );
		$resultadoNBC = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
		
		if($resultadoNBC){
			$modificar = true;
			$marcoNBC = "marcoNBCMod";
			$botonNBC = 'botonModificar';
		}else{
			$modificar = false;
			$marcoNBC = "marcoNBC";
			$botonNBC = 'botonRegistrar';
		}
		
                //DATOS NECESIDAD
				$datos = array (
						'idSolicitud' => $_REQUEST['numSolicitud'],
						'vigencia' => $_REQUEST['vigencia'],
						'unidadEjecutora' => $_REQUEST['unidadEjecutora']
				);
				
			$cadenaSql = $this->miSql->getCadenaSql ( 'listaSolicitudNecesidadXNumSolicitud', $datos );
			$solicitudNecesidad = $siCapitalRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
			
		$esteCampo = "marcoObjetoAct";
		$atributos ['id'] = $esteCampo;
		$atributos ["estilo"] = "jqueryui";
		$atributos ['tipoEtiqueta'] = 'inicio';
		$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
		echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );                
        
		echo "<span class='textoElegante textoEnorme textoAzul'>N° Necesidad - Vigencia - Unidad Ejecutora : </span>";
				echo "<span class='textoElegante textoMediano textoGris'>". $_REQUEST['numSolicitud']. " - ". $_REQUEST['vigencia']. " - " . $_REQUEST['unidadEjecutora'] . "</span></br>";
				echo "<br>";
		echo "<span class='textoElegante textoEnorme textoAzul'>Objeto Solicitud de Necesidad : </span>"; 
                echo "<span class='textoElegante textoMediano textoGris'>". $solicitudNecesidad [0]['OBJETO'] . "</span></br>"; 
                echo "<br>";
        echo "<span class='textoElegante textoEnorme textoAzul'>Justificación Solicitud de Necesidad : </span>";
                echo "<span class='textoElegante textoMediano textoGris'>". $solicitudNecesidad [0]['JUSTIFICACION'] . "</span></br>";
                echo "<br>";
		echo "<span class='textoElegante textoEnorme textoAzul'>Dependencia : </span>"; 
                echo "<span class='textoElegante textoMediano textoGris'>". $solicitudNecesidad [0]['DEPENDENCIA'] . "</span></br>"; 
                echo "<br>";
        echo "<span class='textoElegante textoEnorme textoAzul'>Ordenador del Gasto : </span>";
                echo "<span class='textoElegante textoMediano textoGris'>". $solicitudNecesidad [0]['ORDENADOR_GASTO'] . " - " . $solicitudNecesidad [0]['CARGO_ORDENADOR_GASTO'] ."</span></br>";
        
		
        //FIN OBJETO A CONTRATAR
        echo $this->miFormulario->marcoAgrupacion ( 'fin' );
                
		// ----------------INICIO ACTIVIDADES ECONOMICAS REGISTRADAS--------------------------------------------------------
		$cadenaSql = $this->miSql->getCadenaSql ( 'consultarActividades', $_REQUEST['idObjeto']  );
        $resultadoActividades = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );

        
		if( $resultadoActividades ){
			
			$esteCampo = "marcoActividades";
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
			$atributos ['tipoEtiqueta'] = 'inicio';
			$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
			echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );

				foreach ($resultadoActividades as $dato):
					echo $dato['id_subclase'] . '-' . $dato['nombre'] . "<br>";
				endforeach;
				
			echo $this->miFormulario->marcoAgrupacion ( 'fin' );
		}				
		// ----------------FIN ACTIVIDADES ECONOMICAS REGISTRADAS--------------------------------------------------------		
				
			$esteCampo = $marcoNBC;
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
			$atributos ['tipoEtiqueta'] = 'inicio';
			$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
			echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
			
					// ---------------- CONTROL: Select --------------------------------------------------------
					$esteCampo = 'objetoArea';
					$atributos['nombre'] = $esteCampo;
					$atributos['id'] = $esteCampo;
					$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos['tab'] = $tab;
					
					$atributos['evento'] = ' ';
					$atributos['deshabilitado'] = false;
					$atributos['limitar']= 50;
					$atributos['tamanno']= 1;
					$atributos['columnas']= 1;
					$atributos ['anchoEtiqueta'] = 400;
					
					$atributos ['obligatorio'] = true;
					$atributos ['etiquetaObligatorio'] = true;
					$atributos ['validar'] = 'required';
					
					if($modificar){
						$cadenaTest = $this->miSql->getCadenaSql ( "buscarAreaConocimientoXNBC", $resultadoNBC[0]['id_nucleo']);
						$matrizPrev = $esteRecursoDB->ejecutarAcceso ( $cadenaTest, "busqueda" );
							
						$cadenaTestP = $this->miSql->getCadenaSql ( "buscarNBCAjax", $matrizPrev[0]['id_area']);
						$matrizPrevP = $esteRecursoDB->ejecutarAcceso ( $cadenaTestP, "busqueda" );
							
						$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarAreaConocimiento" );
						$matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
					}else{
						$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarAreaConocimiento" );
						$matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
					}
					
					if ($modificar) {
						$atributos['seleccion'] = $matrizPrev[0]['id_area'];
					} else {
						$atributos['seleccion'] = -1;
							
					}
						
					$atributos['matrizItems'] = $matrizItems;
					$atributos ['valor'] = '';
					
					$tab ++;
						
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroLista ( $atributos );
					// --------------- FIN CONTROL : Select --------------------------------------------------
						
					// ---------------- CONTROL: Select --------------------------------------------------------
					$esteCampo = 'objetoNBC';
					$atributos['nombre'] = $esteCampo;
					$atributos['id'] = $esteCampo;
					$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos['tab'] = $tab;
					
					if ($modificar) {
						$atributos['seleccion'] = $resultadoNBC[0]['id_nucleo'];
					} else {
						$atributos['seleccion'] = -1;
							
					}
					
					$atributos['evento'] = ' ';
					
					if($modificar){
						$atributos['deshabilitado'] = false;
					}else{
						$atributos['deshabilitado'] = true;
					}
					
					
					$atributos['limitar']= 50;
					$atributos['tamanno']= 1;
					$atributos['columnas']= 1;
					$atributos ['anchoEtiqueta'] = 400;
						
					$atributos ['obligatorio'] = true;
					$atributos ['etiquetaObligatorio'] = true;
					$atributos ['validar'] = 'required';
						
					$matrizItems=array(
							array(1,'Test A'),
							array(2,'Test B'),
								
					);
							
					if ($modificar) {
						$atributos['matrizItems'] = $matrizPrevP;
					} else {
						$atributos['matrizItems'] = $matrizItems;
							
					}
						
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$tab ++;
						
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroLista ( $atributos );
				
			echo $this->miFormulario->marcoAgrupacion ( 'fin' );
			
		$esteCampo = 'modificarNBC';
		$atributos ["id"] = $esteCampo; // No cambiar este nombre
		$atributos ["tipo"] = "hidden";
		$atributos ['estilo'] = '';
		$atributos ["obligatorio"] = false;
		$atributos ['marco'] = true;
		$atributos ["etiqueta"] = "";
		$atributos ['valor'] = $modificar;
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoCuadroTexto ( $atributos );
		unset ( $atributos );
			
			
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
		
		
		$esteCampo = 'numCotizaciones';
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
		
		$esteCampo = 'numSolicitud';
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
		
		$esteCampo = 'vigencia';
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
		
		$esteCampo = 'unidadEjecutora';
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
		
		$esteCampo = 'tipoNecesidad';
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
		
		
		
		// ------------------Division para los botones-------------------------
		$atributos ["id"] = "botones";
		$atributos ["estilo"] = "marcoBotones";
		echo $this->miFormulario->division ( "inicio", $atributos );
		{
			// -----------------CONTROL: Botón ----------------------------------------------------------------
			$esteCampo = $botonNBC;
			$atributos ["id"] = $esteCampo;
			$atributos ["tabIndex"] = $tab;
			$atributos ["tipo"] = 'boton';
			// submit: no se coloca si se desea un tipo button genérico
			$atributos ['submit'] = 'true';
			$atributos ["estiloMarco"] = '';
			$atributos ["estiloBoton"] = 'jqueryui';
			// verificar: true para verificar el formulario antes de pasarlo al servidor.
			$atributos ["verificar"] = '';
			$atributos ["tipoSubmit"] = 'jquery'; // Dejar vacio para un submit normal, en este caso se ejecuta la función submit declarada en ready.js
			$atributos ["valor"] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ['nombreFormulario'] = $esteBloque ['nombre'] . "Registrar";
			$tab ++;
			
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoBoton ( $atributos );
			
			// -----------------FIN CONTROL: Botón -----------------------------------------------------------
			
		}
	// 			------------------Fin Division para los botones-------------------------
				echo $this->miFormulario->division( "fin" );
				
				
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
				// Paso 1: crear el listado de variables
				
				$valorCodificado  = "action=" . $esteBloque ["nombre"];
				$valorCodificado .= "&pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
				$valorCodificado .= "&bloque=" . $esteBloque ['nombre'];
				$valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
				$valorCodificado .= "&opcion=registrarActividad";
				$valorCodificado .= "&usuario=" . $_REQUEST['usuario'];
				
				/**
				 * SARA permite que los nombres de los campos sean dinámicos.
				 * Para ello utiliza la hora en que es creado el formulario para
				 * codificar el nombre de cada campo.
				 */
				$valorCodificado .= "&campoSeguro=" . $_REQUEST ['tiempo'];
				$valorCodificado .= "&tiempo=" . time();
				/*
				 * Sara permite validar los campos en el formulario o funcion destino.
				 * Para ello se envía los datos atributos["validadar"] de los componentes del formulario
				 * Estos se pueden obtener en el atributo $this->miFormulario->validadorCampos del formulario
				 * La función $this->miFormulario->codificarCampos() codifica automáticamente el atributo validadorCampos
				 */
				$valorCodificado .= "&validadorCampos=" . $this->miFormulario->codificarCampos();
				
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
				
				$atributos ['marco'] = true;
				$atributos ['tipoEtiqueta'] = 'fin';
				echo $this->miFormulario->formulario ( $atributos );
								
				// ----------------FIN SECCION: Paso de variables -------------------------------------------------
				// ---------------- FIN SECCION: Controles del Formulario -------------------------------------------
			// ----------------FINALIZAR EL FORMULARIO ----------------------------------------------------------
			// Se debe declarar el mismo atributo de marco con que se inició el formulario.
		
	}
	function mensaje() {
		
		// Si existe algun tipo de error en el login aparece el siguiente mensaje
		$mensaje = $this->miConfigurador->getVariableConfiguracion ( 'mostrarMensaje' );
		$this->miConfigurador->setVariableConfiguracion ( 'mostrarMensaje', null );
		
		if ($mensaje) {
			
			$tipoMensaje = $this->miConfigurador->getVariableConfiguracion ( 'tipoMensaje' );
			
			if ($tipoMensaje == 'json') {
				
				$atributos ['mensaje'] = $mensaje;
				$atributos ['json'] = true;
			} else {
				$atributos ['mensaje'] = $this->lenguaje->getCadena ( $mensaje );
			}
			// -------------Control texto-----------------------
			$esteCampo = 'divMensaje';
			$atributos ['id'] = $esteCampo;
			$atributos ["tamanno"] = '';
			$atributos ["estilo"] = 'information';
			$atributos ["etiqueta"] = '';
			$atributos ["columnas"] = ''; // El control ocupa 47% del tamaño del formulario
			echo $this->miFormulario->campoMensaje ( $atributos );
			unset ( $atributos );
		}
		
		return true;
	}
}


$miFormulario = new FormularioRegistro ( $this->lenguaje, $this->miFormulario, $this->sql  );

$miFormulario->formulario ();
$miFormulario->mensaje ();
?>
