<?php
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}
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
	
	function cambiafecha_format($fecha) {
		ereg("([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})", $fecha, $mifecha);
		$fechana = $mifecha[3] . "/" . $mifecha[2] . "/" . $mifecha[1];
		return $fechana;
	}
	
	function miForm() {

		/**
		 * IMPORTANTE: Este formulario está utilizando jquery.
		 * Por tanto en el archivo ready.php se delaran algunas funciones js
		 * que lo complementan.
		 */
		// Rescatar los datos de este bloque
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
		
		// -------------------------------------------------------------------------------------------------
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
		$atributos ['titulo'] = '';
		
		// Si no se coloca, entonces toma el valor predeterminado.
		$atributos ['estilo'] = '';
		$atributos ['marco'] = false;
		$tab = 1;
		// ---------------- FIN SECCION: de Parámetros Generales del Formulario ----------------------------
		// ----------------INICIAR EL FORMULARIO ------------------------------------------------------------
		$atributos ['tipoEtiqueta'] = 'inicio';
		echo $this->miFormulario->formulario ( $atributos );
		
		
			if($_REQUEST['tipoNecesidad'] == "SERVICIO" || $_REQUEST['tipoNecesidad'] == "BIEN Y SERVICIO"){
				$marcoTipo = "marcoProveedoresConv";
				$tipoMarco = "marcoObjetoConv";
				$tipoSolicitud = $_REQUEST['tipoNecesidad'];
				$service = true;
			}else{
				$marcoTipo = "marcoProveedores";
				$tipoMarco = "marcoObjeto";
				$tipoSolicitud = $_REQUEST['tipoNecesidad'];
				$service = false;
			}

		
		$datos = array (
				'idObjeto' => $_REQUEST['idObjeto']
		);
		
		
		$cadenaSql = $this->miSql->getCadenaSql ( 'infoCotizacionCast', $datos['idObjeto'] );
		$solicitudCotizacionCast = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
		$cadenaSql = $this->miSql->getCadenaSql ( 'infoCotizacion', $datos['idObjeto'] );
		$solicitudCotizacion = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
		$cadenaSql = $this->miSql->getCadenaSql ( 'dependenciaUdistritalById', $solicitudCotizacion[0]['jefe_dependencia'] );
		$resultadoDependencia = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
			
		$cadenaSql = $this->miSql->getCadenaSql ( 'ordenadorUdistritalById', $solicitudCotizacion[0]['ordenador_gasto'] );
		$resultadoOrdenador = $argoRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
		$cadenaSql = $this->miSql->getCadenaSql ( 'buscarUsuario', $solicitudCotizacion[0]['usuario_creo'] );
		$resultadoUsuario = $frameworkRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
		if($solicitudCotizacion[0]['unidad_ejecutora'] == 1){
			$valorUnidadEjecutoraText = "1 - Rectoría";
		}else{
			$valorUnidadEjecutoraText = "2 - IDEXUD";
		}
                
		$esteCampo = $tipoMarco;
		$atributos ['id'] = $esteCampo;
		$atributos ["estilo"] = "jqueryui";
		$atributos ['tipoEtiqueta'] = 'inicio';
		$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
		echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
		
		echo "<span class='textoElegante textoEnorme textoAzul'>Título Cotización : </span>";
		echo "<span class='textoElegante textoGrande textoGris'><b>". $solicitudCotizacionCast[0]['titulo_cotizacion'] . "</b></span></br>";
		echo "<br>";
		echo "<span class='textoElegante textoEnorme textoAzul'>N° Cotización - Vigencia - Unidad Ejecutora : </span>";
		echo "<span class='textoElegante textoGrande textoGris'><b>". $_REQUEST['idObjeto']. " - ". $solicitudCotizacion[0]['vigencia'] . " - (" .$valorUnidadEjecutoraText. ")</b></span></br>";
		echo "<br>";
		echo "<span class='textoElegante textoEnorme textoAzul'>Fecha de Apertura : </span>";
		echo "<span class='textoElegante textoEnorme textoGris'><b>". $this->cambiafecha_format($solicitudCotizacionCast[0]['fecha_apertura']) . "</b></span></br>";
		echo "<br>";
		echo "<span class='textoElegante textoEnorme textoAzul'>Fecha de Cierre : </span>";
		echo "<span class='textoElegante textoEnorme textoGris'><b>". $this->cambiafecha_format($solicitudCotizacionCast[0]['fecha_cierre']). "</b></span></br>";
		echo "<br>";
		echo "<span class='textoElegante textoEnorme textoAzul'>Ordenador del Gasto Relacionado : </span>";
		echo "<span class='textoElegante textoGrande textoGris'><b>". $resultadoOrdenador[0][1]. "</b></span></br>";
		echo "<br>";
		echo "<span class='textoElegante textoEnorme textoAzul'>Dependencia Solicitante : </span>";
		echo "<span class='textoElegante textoGrande textoGris'><b>". $resultadoDependencia[0][1]. "</b></span></br>";
		echo "<br>";
		echo "<span class='textoElegante textoEnorme textoAzul'>Responsable : </span>";
		echo "<span class='textoElegante textoGrande textoGris'><b>". $resultadoUsuario[0]['identificacion'] . " - " . $resultadoUsuario[0]['nombre'] . " " . $resultadoUsuario[0]['apellido']."</b></span></br>";
		

		//FIN OBJETO A CONTRATAR
        echo $this->miFormulario->marcoAgrupacion ( 'fin' );
        
        $cadenaSql = $this->miSql->getCadenaSql ( 'consultarActividadesImp', $_REQUEST['idObjeto']  );
        $resultadoActividades = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );

        if( $resultadoActividades ){
        		
        	$esteCampo = "marcoActividadesRel";
        	$atributos ['id'] = $esteCampo;
        	$atributos ["estilo"] = "jqueryui";
        	$atributos ['tipoEtiqueta'] = 'inicio';
        	$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
        	echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
        
        	foreach ($resultadoActividades as $dato):
        	echo "<span class='textoElegante textoEnorme textoAzul'>+ </span><b>";
        		echo $dato['subclase'] . ' - ' . $dato['nombre'] . "</b><br>";
        	endforeach;
        
        	echo $this->miFormulario->marcoAgrupacion ( 'fin' );
        }
        
        if($service){
        	
        	$cadenaSql = $this->miSql->getCadenaSql ( 'consultarNBCImp', $_REQUEST['idObjeto']  );
        	$resultadoNBC = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
        	
        	$esteCampo = "marcoNBCRel";
        	$atributos ['id'] = $esteCampo;
        	$atributos ["estilo"] = "jqueryui";
        	$atributos ['tipoEtiqueta'] = 'inicio';
        	$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
        	echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
        	
        	
        	echo "<span class='textoElegante textoEnorme textoAzul'>+ </span><b>";
        	echo $resultadoNBC[0]['nucleo'] . ' - ' . $resultadoNBC[0]['nombre'] . "</b><br>";
        	
        	
        	echo $this->miFormulario->marcoAgrupacion ( 'fin' );
        	
        }
        
        
        $cadenaSql = $this->miSql->getCadenaSql ( 'actividadesXNecesidad', $_REQUEST["idObjeto"] );
        $resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );

        $actividades = $resultado[0][0];
        
		$cadenaSql = $this->miSql->getCadenaSql ( 'verificarActividadProveedor', $actividades );
		$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );

		if (! $resultado) {
			
			
			// ------------------INICIO Division para los botones-------------------------
				$atributos ["id"] = "divNoEncontroEgresado";
				$atributos ["estilo"] = "marcoBotones";
				echo $this->miFormulario->division ( "inicio", $atributos );
				// -------------SECCION: Controles del Formulario-----------------------
				$esteCampo = "mensajeNoHayProveedoresPuntaje";
				$atributos ["id"] = $esteCampo; // Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
				$atributos ["etiqueta"] = "";
				$atributos ["estilo"] = "centrar";
				$atributos ["tipo"] = 'error';
				$atributos ["mensaje"] = "No se encontraron proveedores, actualmente <h2><b>(" ."0" . ")</b></h2> proveedores cumplen con las características de la solicitud de cotización.
						<br>
						<br>
						Por favor modifique la solicitud, diríjase al <b>Módulo de Gestión de Solicitudes de Cotización</b> y realice los cambios correspondientes.
						<br>
						<br>
						<br>
						Solicitud de Cotización : <b>". $solicitudCotizacion[0]['numero_solicitud'] ."</b>
						
						
						";
				
				echo $this->miFormulario->cuadroMensaje ( $atributos );
				unset ( $atributos );
				// -------------FIN Control Formulario----------------------
				// ------------------FIN Division para los botones-------------------------
				echo $this->miFormulario->division ( "fin" );
				unset ( $atributos );

			
			
		} else {


			// ------- FILTRAR POR ACTIVIDAD ECONOMICA
			
			if($service){
				$datos = array (
						'actividadEconomica' => $actividades,
						'objetoNBC' => $_REQUEST ['objetoNBC']
				);
				
				
				$cadenaSql = $this->miSql->getCadenaSql ( 'proveedoresByClasificacionConv', $datos );
				$resultadoProveedor = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
				
			}else{
				$datos = array (
						'actividadEconomica' => $actividades
				);
				

				$cadenaSql = $this->miSql->getCadenaSql ( 'proveedoresByClasificacion', $datos );
				$resultadoProveedor = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
				
			}
			
			
			
			if (! $resultadoProveedor) {
				
				// ------------------INICIO Division para los botones-------------------------
				$atributos ["id"] = "divNoEncontroEgresado";
				$atributos ["estilo"] = "marcoBotones";
				echo $this->miFormulario->division ( "inicio", $atributos );
				// -------------SECCION: Controles del Formulario-----------------------
				$esteCampo = "mensajeNoHayProveedoresPuntaje";
				$atributos ["id"] = $esteCampo; // Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
				$atributos ["etiqueta"] = "";
				$atributos ["estilo"] = "centrar";
				$atributos ["tipo"] = 'error';
				$atributos ["mensaje"] = "No se encontraron proveedores, actualmente <h2><b>(" ."0" . ")</b></h2> proveedores cumplen con las características de la solicitud de cotización.
						<br>
						<br>
						Por favor modifique la solicitud, diríjase al <b>Módulo de Gestión de Solicitudes de Cotización</b> y realice los cambios correspondientes.
						<br>
						<br>
						<br>
						Solicitud de Cotización : <b>". $solicitudCotizacion[0]['numero_solicitud'] ."</b>
						
						
						";
				
				echo $this->miFormulario->cuadroMensaje ( $atributos );
				unset ( $atributos );
				// -------------FIN Control Formulario----------------------
				// ------------------FIN Division para los botones-------------------------
				echo $this->miFormulario->division ( "fin" );
				unset ( $atributos );
				
				
			} else {
				
				
				
				// ------------------INICIO Division para los botones-------------------------
				$atributos ["id"] = "divEncontro";
				$atributos ["estilo"] = "marcoBotones";
				echo $this->miFormulario->division ( "inicio", $atributos );
				// -------------SECCION: Controles del Formulario-----------------------
				$esteCampo = "mensajeHayProveedores";
				$atributos ["id"] = $esteCampo; // Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
				$atributos ["etiqueta"] = "";
				$atributos ["estilo"] = "centrar";
				$atributos ["tipo"] = 'success';
				$atributos ["mensaje"] = "Se encontraron <h2><b>(" .count($resultadoProveedor) . ")</b></h2> personas que cumplen con las características de la solicitud de cotización.
						<br>
						<br>
						Si desea procesar la solicitud, diríjase al <b>Módulo de Gestión de Solicitudes de Cotización</b> y realice el procesamiento correspondiente.
						<br>
						<br>
						<br>
						Solicitud de Cotización : <b>". $solicitudCotizacion[0]['numero_solicitud'] ."</b>
						
						
						";
				
				echo $this->miFormulario->cuadroMensaje ( $atributos );
				unset ( $atributos );
				// -------------FIN Control Formulario----------------------
				// ------------------FIN Division para los botones-------------------------
				echo $this->miFormulario->division ( "fin" );
				unset ( $atributos );
				
				
				
				
			}
		}
		
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
		
		$valorCodificado = "action=" . $esteBloque ["nombre"];
		$valorCodificado .= "&pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
		$valorCodificado .= "&bloque=" . $esteBloque ['nombre'];
		$valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
		$valorCodificado .= "&opcion=cotizacion";
		$valorCodificado .= "&usuario=" . $_REQUEST['usuario'];

		/**
		 * SARA permite que los nombres de los campos sean dinámicos.
		 * Para ello utiliza la hora en que es creado el formulario para
		 * codificar el nombre de cada campo.
		 */
		$valorCodificado .= "&campoSeguro=" . $_REQUEST ['tiempo'];
		
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
		
		// ----------------FIN SECCION: Paso de variables -------------------------------------------------
		// ---------------- FIN SECCION: Controles del Formulario -------------------------------------------
		// ----------------FINALIZAR EL FORMULARIO ----------------------------------------------------------
		// Se debe declarar el mismo atributo de marco con que se inició el formulario.
		$atributos ['marco'] = false;
		$atributos ['tipoEtiqueta'] = 'fin';
		echo $this->miFormulario->formulario ( $atributos );
		
		return true;
                
       
	}
}

$miSeleccionador = new registrarForm ( $this->lenguaje, $this->miFormulario, $this->sql );

$miSeleccionador->miForm ();
?>