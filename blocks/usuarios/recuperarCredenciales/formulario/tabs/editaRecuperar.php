<?php
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}

use usuarios\recuperarCredenciales\funcion\redireccion;

class registrarForm {
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
		
		// lineas para conectar base de d atos-------------------------------------------------------------------------------------------------
		$conexion = "framework";
		$frameworkRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
		
		$conexion = "estructura";
		$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
		
		//$conexion = "wconexionclave";
		//$esteRecursoDBORA = $this->miConfigurador->fabricaConexiones->getRecursoDB( $conexion );

        $seccion ['tiempo'] = $tiempo;
        $miSesion = \Sesion::singleton();

                
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
                        $esteCampo = 'botonRegresar';
                        $atributos ['id'] = $esteCampo;
                        $atributos ['enlace'] = $variable;
                        $atributos ['tabIndex'] = 1;
                        $atributos ['enlaceTexto'] = $this->lenguaje->getCadena ( $esteCampo );
                        $atributos ['estilo'] = 'textoPequenno textoGris';
                        $atributos ['enlaceImagen'] = $rutaBloque."/images/player_rew.png";
                        $atributos ['posicionImagen'] = "atras";//"adelante";
                        $atributos ['ancho'] = '30px';
                        $atributos ['alto'] = '30px';
                        $atributos ['redirLugar'] = true;
                       // echo $this->miFormulario->enlace ( $atributos );
                        unset ( $atributos );
                        
                        
                        
            //var_dump($_REQUEST);   
            
            
            $cadena_sql = $this->miSql->getCadenaSql("consultarUsuario", $_REQUEST['id_usuario']);
            $resultadoUs = $frameworkRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
            
            
            //echo $cadena_sql;
            //var_dump($resultadoUs);
            
            
            
          	$i = 0;
          	$cierto = 0;
            while($i < 3){
            	if($_REQUEST['preguntaSR'.$i] == $_REQUEST['radioinput'.$i]){
            		$cierto++;
            	}
            	
            	$i++;
            }
            
            //var_dump($cierto);
            
            
            if($cierto == 3){
            	
            	//************************************************************
            	//****************** INFORMACIÓN VALIDA **********************
            	
            	
            	
            	
            	$parametro['id_usuario']=$_REQUEST['usuario'];
            	$cadena_sql = $this->miSql->getCadenaSql("consultarUsuarios", $parametro);
            	$resultadoUsuarios = $frameworkRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
            	
            	
            	
            	$esteCampo = "mensajeCambiarClave";
            	$atributos ['id'] = $esteCampo;
            	$atributos ["estilo"] = "jqueryui";
            	$atributos ['tipoEtiqueta'] = 'inicio';
            	$atributos ["leyenda"] =  $this->lenguaje->getCadena ( $esteCampo );
            	echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
            	unset ( $atributos );
            	{	
            		
					
            		
            		
            		
            		$mensajeText = '		<div>
	            				Usuario de Acceso:
	            			</div>
	            			<div>
	            				<b><h3>'.$resultadoUsuarios[0]['id_usuario'].'</h3></b>
	            			</div>
            				<br>
	            			<div>
	            				Nombres:
	            			</div>
	            			<div>
	            				<b>'.$resultadoUsuarios[0]['nombre'].'</b>
	            			</div>
	      					<br>
	            			<div>
	            				Apellidos:
	            			</div>
	            			<div>
	            				<b>'.$resultadoUsuarios[0]['apellido'].'</b>
	            			</div>';
            		
            		
            		
            		
            		$esteCampo = 'mensaje';
            			
            		$tipo = 'information';
            			
            		$atributos["id"] = $esteCampo; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
            		$atributos["etiqueta"] = "";
            		$atributos["estilo"] = "centrar";
            		$atributos["tipo"] = $tipo;
            		
            			
            		$atributos["mensaje"] = $mensajeText;
            		echo $this->miFormulario->cuadroMensaje($atributos);
            		unset($atributos);
            		
            		
            		
            		// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
            	$esteCampo = 'id_usuarioT';
            	$atributos ['id'] = $esteCampo;
            	$atributos ['nombre'] = $esteCampo;
            	$atributos ['tipo'] = 'hidden';
            	$atributos ['estilo'] = 'jqueryui';
            	$atributos ['marco'] = true;
            	$atributos ['estiloMarco'] = '';
            	$atributos ["etiquetaObligatorio"] = false;
            	//$atributos ['columnas'] = 1;
            	$atributos ['dobleLinea'] = 0;
            	//$atributos ['tabIndex'] = $tab;
            	//$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
            	$atributos ['validar']="required, minSize[5]";
            	$atributos ['valor'] = $resultadoUsuarios[0]['id_usuario'];
            	//$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
            	$atributos ['deshabilitado'] = true;
            	
            	// Aplica atributos globales al control
            	$atributos = array_merge ( $atributos, $atributosGlobales );
            	echo $this->miFormulario->campoCuadroTexto ( $atributos );
            	unset ( $atributos );
            	// ---------------- FIN CONTROL: Cuadro de Texto --------------------------------------------------------
            	// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
            	$esteCampo = 'nombres';
            	$atributos ['id'] = $esteCampo;
            	$atributos ['nombre'] = $esteCampo;
            	$atributos ['tipo'] = 'hidden';
            	$atributos ['estilo'] = 'jqueryui';
            	$atributos ['marco'] = true;
            	$atributos ['estiloMarco'] = '';
            	$atributos ["etiquetaObligatorio"] = false;
            	//$atributos ['columnas'] = 1;
            	$atributos ['dobleLinea'] = 0;
            	//$atributos ['tabIndex'] = $tab;
            	//$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
            	$atributos ['validar']="required, minSize[2]";
            	$atributos ['valor'] = $resultadoUsuarios[0]['nombre'];;
            	//$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
            	$atributos ['deshabilitado'] = true;
            	// Aplica atributos globales al control
            	$atributos = array_merge ( $atributos, $atributosGlobales );
            	echo $this->miFormulario->campoCuadroTexto ( $atributos );
            	unset ( $atributos );
            	// ---------------- FIN CONTROL: Cuadro de Texto --------------------------------------------------------
            	// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
            	$esteCampo = 'apellidos';
            	$atributos ['id'] = $esteCampo;
            	$atributos ['nombre'] = $esteCampo;
            	$atributos ['tipo'] = 'hidden';
            	$atributos ['estilo'] = 'jqueryui';
            	$atributos ['marco'] = true;
            	$atributos ['estiloMarco'] = '';
            	$atributos ["etiquetaObligatorio"] = false;
            	//$atributos ['columnas'] = 1;
            	$atributos ['dobleLinea'] = 0;
            	//$atributos ['tabIndex'] = $tab;
            	//$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
            	$atributos ['validar']="required, minSize[2]";
            	$atributos ['valor'] =  $resultadoUsuarios[0]['apellido'];
            	//$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
            	$atributos ['deshabilitado'] = true;
            	// Aplica atributos globales al control
            	$atributos = array_merge ( $atributos, $atributosGlobales );
            	echo $this->miFormulario->campoCuadroTexto ( $atributos );
            	unset ( $atributos );
            	// ---------------- FIN CONTROL: Cuadro de Texto --------------------------------------------------------

            	
            	
            	
            	
            	$esteCampo = "mensajeCambiarClavePass";
            	$atributos ['id'] = $esteCampo;
            	$atributos ["estilo"] = "jqueryui";
            	$atributos ['tipoEtiqueta'] = 'inicio';
            	$atributos ["leyenda"] =  $this->lenguaje->getCadena ( $esteCampo );
            	echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
            	unset ( $atributos );
            	{
            	
            	
            	
            	
            	// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
            	$esteCampo = 'contrasena';
            	$atributos ['id'] = $esteCampo;
            	$atributos ['nombre'] = $esteCampo;
            	$atributos ['tipo'] = 'password';
            	$atributos ['estilo'] = 'jqueryui';
            	$atributos ['marco'] = true;
            	$atributos ['estiloMarco'] = '';
            	$atributos ["etiquetaObligatorio"] = true;
            	$atributos ['columnas'] = 1;
            	$atributos ['dobleLinea'] = 0;
            	$atributos ['tabIndex'] = $tab;
            	$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
            	$atributos ['validar']="required,minSize[8],maxSize[16],custom[minNumberChars],custom[minLowerAlphaChars]";
            	$atributos ['valor'] = '';
            	$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
            	$atributos ['deshabilitado'] = false;
            	$atributos ['tamanno'] = 60;
            	$atributos ['maximoTamanno'] = '';
            	$atributos ['anchoEtiqueta'] = 170;
            	$tab ++;
            	// Aplica atributos globales al control
            	$atributos = array_merge ( $atributos, $atributosGlobales );
            	echo $this->miFormulario->campoCuadroTexto ( $atributos );
            	unset ( $atributos );
            	// ---------------- FIN CONTROL: Cuadro de Texto --------------------------------------------------------
            	// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
            	$esteCampo = 'contrasenaConfirm';
            	$atributos ['id'] = $esteCampo;
            	$atributos ['nombre'] = $esteCampo;
            	$atributos ['tipo'] = 'password';
            	$atributos ['estilo'] = 'jqueryui';
            	$atributos ['marco'] = true;
            	$atributos ['estiloMarco'] = '';
            	$atributos ["etiquetaObligatorio"] = true;
            	$atributos ['columnas'] = 1;
            	$atributos ['dobleLinea'] = 0;
            	$atributos ['tabIndex'] = $tab;
            	$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
            	$atributos ['validar']="required,minSize[8],maxSize[16],custom[minNumberChars],custom[minLowerAlphaChars], passwordEquals[contrasena]";
            	$atributos ['valor'] = '';
            	$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
            	$atributos ['deshabilitado'] = false;
            	$atributos ['tamanno'] = 60;
            	$atributos ['maximoTamanno'] = '';
            	$atributos ['anchoEtiqueta'] = 170;
            	$tab ++;
            	// Aplica atributos globales al control
            	$atributos = array_merge ( $atributos, $atributosGlobales );
            	echo $this->miFormulario->campoCuadroTexto ( $atributos );
            	unset ( $atributos );
            	// ---------------- FIN CONTROL: Cuadro de Texto --------------------------------------------------------
            	 
            	
            	}
            	echo $this->miFormulario->marcoAgrupacion ( 'fin' );
            	
            	
            	
            	
            	
            	
            	
            	
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
            	echo $this->miFormulario->division ( 'fin' );
            	
            	echo $this->miFormulario->marcoAgrupacion ( 'fin' );
            	
            	// ---------------- FIN SECCION: Controles del Formulario -------------------------------------------
            	// ----------------FINALIZAR EL FORMULARIO ----------------------------------------------------------
            	// Se debe declarar el mismo atributo de marco con que se inició el formulario.
            	}
            		
            	// -----------------FIN CONTROL: Botón -----------------------------------------------------------
            	// ------------------Fin Division para los botones-------------------------
            	echo $this->miFormulario->division ( "fin" );
            		
            	
            	
            	
            	
            	
            	
            	
            	
            	
            	//*************************************************************
            	//*************************************************************
            	
            }else{
            	
            	$resultadoUsuarios[0]['id_usuario'] = null;
            	
            	$esteCampo = "mensajeNoInformacion";
            	$atributos ['id'] = $esteCampo;
            	$atributos ["estilo"] = "jqueryui";
            	$atributos ['tipoEtiqueta'] = 'inicio';
            	$atributos ["leyenda"] =  $this->lenguaje->getCadena ( $esteCampo );
            	echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
            	unset ( $atributos );
            	{
            		
            		
            		// ---------------- SECCION: Controles del Formulario -----------------------------------------------
            		$esteCampo = 'mensaje';
            		 
            		$tipo = 'error';
            		 
            		$atributos["id"] = $esteCampo; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
            		$atributos["etiqueta"] = "";
            		$atributos["estilo"] = "centrar";
            		$atributos["tipo"] = $tipo;
            		 
            		$mensajeLey = "Las respuestas a las preguntas de Seguridad del Usuario o Número de Identificación: <br> <br> <center><b>".$_REQUEST['id_usuario']."</b></center> <br> <br> No coinciden con la información que se encuentra Registrada en el Sistema
            				de Registro Único de Personas y Banco de Proveedores ÁGORA. Si considera que es un Error por favor comuníquese con el Administrador del Sistema.";
            		 
            		$atributos["mensaje"] = $mensajeLey;
            		echo $this->miFormulario->cuadroMensaje($atributos);
            		unset($atributos);
            		
            		
            	}
            	echo $this->miFormulario->marcoAgrupacion ( 'fin' );
            	
            		 
            	
            	// ------------------Division para los botones-------------------------
            	$atributos ["id"] = "botones";
            	$atributos ["estilo"] = "marcoBotones widget";
            	echo $this->miFormulario->division ( "inicio", $atributos );
            	unset ( $atributos );
            	{
            	
            		$esteCampo = 'botonTerminar';
            		$atributos ['id'] = $esteCampo;
            		$atributos ['enlace'] = '/agora';
            		$atributos ['tabIndex'] = 1;
            		$atributos ['estilo'] = 'jqueryui';
            		$atributos ['enlaceTexto'] = $this->lenguaje->getCadena ( $esteCampo );
            		//$atributos ['ancho'] = '10%';
            		//$atributos ['alto'] = '10%';
            		$atributos ['redirLugar'] = false;
            	
            		echo $this->miFormulario->enlace ( $atributos );
            	
            		unset($atributos);
            	}
            	echo $this->miFormulario->division ( 'fin' );
            	
            	
            	
            }
                        
                        
                        
			
			
			
			
			

			$numeroDocumento = 0;
			
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
			$valorCodificado .= "&opcion=cambiarClaveRecuperar";
			$valorCodificado .= "&id_usuario=".$resultadoUsuarios[0]['id_usuario'];
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
}

$miSeleccionador = new registrarForm ( $this->lenguaje, $this->miFormulario, $this->sql );

$miSeleccionador->miForm ();
?>
