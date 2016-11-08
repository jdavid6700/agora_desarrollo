<?php
namespace hojaDeVida\crearDocente\formulario;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	
	exit ();
}
class Formulario {
	var $miConfigurador;
	var $lenguaje;
	var $miFormulario;
	var $miSql;
	
	const OBJETOCREADO = 'CREADO'; //Estado objeto creado
	
	function __construct($lenguaje, $formulario, $sql) {
		
		$this->miConfigurador = \Configurador::singleton ();
		$this->miConfigurador->fabricaConexiones->setRecursoDB ( 'principal' );
		$this->lenguaje = $lenguaje;
		$this->miFormulario = $formulario;
		$this->miSql = $sql;		
	}
	
	function formulario() {
		// Rescatar los datos de este bloque
		$esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );
		$miPaginaActual = $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
		
		$directorio = $this->miConfigurador->getVariableConfiguracion ( "host" );
		$directorio .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/index.php?";
		$directorio .= $this->miConfigurador->getVariableConfiguracion ( "enlace" );
		
		$rutaBloque = $this->miConfigurador->getVariableConfiguracion ( "host" );
		$rutaBloque .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/";
		$rutaBloque .= $esteBloque ['grupo'] ."/". $esteBloque ['nombre'];
		
		// ---------------- SECCION: Par�metros Globales del Formulario ----------------------------------
		/**
		 * Atributos que deben ser aplicados a todos los controles de este formulario.
		 * Se utiliza un arreglo
		 * independiente debido a que los atributos individuales se reinician cada vez que se declara un campo.
		 *
		 * Si se utiliza esta t�cnica es necesario realizar un mezcla entre este arreglo y el espec�fico en cada control:
		 * $atributos= array_merge($atributos,$atributosGlobales);
		 */
		$atributosGlobales ['campoSeguro'] = 'true';
		
		// -------------------------------------------------------------------------------------------------
		$conexion = "estructura";
		$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
		
		$conexion = "sicapital";
		$siCapitalRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
		
		$conexion = "argo_contratos";
		$argoRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
		
		
		$this->cadena_sql = $this->miSql->getCadenaSql ( "listaObjetoContratar", self::OBJETOCREADO );
		$resultado = $esteRecursoDB->ejecutarAcceso ( $this->cadena_sql, "busqueda" );
		
		
		
		// ---------------- SECCION: Parámetros Generales del Formulario ----------------------------------
		$esteCampo = $esteBloque ['nombre']."ConsultarRel";
		$atributos ['id'] = $esteCampo;
		$atributos ['nombre'] = $esteCampo;
		
		// Si no se coloca, entonces toma el valor predeterminado 'application/x-www-form-urlencoded'
		$atributos ['tipoFormulario'] = 'multipart/form-data';
		
		// Si no se coloca, entonces toma el valor predeterminado 'POST'
		$atributos ['metodo'] = 'POST';
		
		// Si no se coloca, entonces toma el valor predeterminado 'index.php' (Recomendado)
		$atributos ['action'] = 'index.php';
		$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo );
		
		// Si no se coloca, entonces toma el valor predeterminado.
		$atributos ['estilo'] = '';
		$atributos ['marco'] = true;
		$tab = 1;
			
		// ---------------- FIN SECCION: de Parámetros Generales del Formulario ----------------------------
			
		// ----------------INICIAR EL FORMULARIO ------------------------------------------------------------
		$atributos ['tipoEtiqueta'] = 'inicio';
		// Aplica atributos globales al control
		echo $this->miFormulario->formulario ( $atributos );
		
		$esteCampo = "marcoDatosRelacionada";
		$atributos ['id'] = $esteCampo;
		$atributos ["estilo"] = "jqueryui";
		$atributos ['tipoEtiqueta'] = 'inicio';
		$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
		echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
		
		
		
		
		
		
		
		if(isset($_REQUEST['vigenciaNecesidadRelacionada'])){
		
			$valorVigenciaRelacionada = $_REQUEST['vigenciaNecesidadRelacionada'];
			$valorUnidadEjecutoraRelacionada = $_REQUEST['unidadEjecutoraCheckRelacionada'];
			
			$datosNec = array (
					'unidadEjecutora' => $valorUnidadEjecutoraRelacionada,
					'vigencia' => $valorVigenciaRelacionada
			);
				
			$this->cadena_sql = $this->miSql->getCadenaSql ( "listarContratosRelacionadosXVigencia", $datosNec );
			$resultado = $esteRecursoDB->ejecutarAcceso ( $this->cadena_sql, "busqueda" );
				
				
			if(isset($resultado[0][0])){
				$datos = array (
						'contratos' => $resultado[0][0],
						'vigencia' => $valorVigenciaRelacionada,
						'unidadEjecutora' => $valorUnidadEjecutoraRelacionada
				);
			}else{
				$datos = array (//No existen Datos Relacionados ya en el sistema AGORA
						'contratos' => "'-1'",
						'vigencia' => $valorVigenciaRelacionada,
						'unidadEjecutora' => $valorUnidadEjecutoraRelacionada
				);
			}
			
			$cadenaSql = $this->miSql->getCadenaSql ( 'listaContratosRelacionadosXVigencia', $datos );
			$resultado = $argoRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
			
			//******************************************************************************************************************************
			$variable = "pagina=" . $miPaginaActual;
			$variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variable, $directorio );
				
			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
			$esteCampo = 'botonRegresar';
			$atributos ['id'] = $esteCampo;
			$atributos ['enlace'] = $variable;
			$atributos ['tabIndex'] = 1;
			$atributos ['estilo'] = 'textoSubtitulo';
			$atributos ['enlaceTexto'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ['ancho'] = '10%';
			$atributos ['alto'] = '10%';
			$atributos ['redirLugar'] = true;
			echo $this->miFormulario->enlace ( $atributos );
				
			unset ( $atributos );
			//********************************************************************************************************************************
			$onlyCheck = false;
			
		}else{
				
			// ---------------- CONTROL: Lista Vigencia--------------------------------------------------------
			$esteCampo = "vigenciaNecesidadRelacionada";
			$atributos ['nombre'] = $esteCampo;
			$atributos ['id'] = $esteCampo;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ["etiquetaObligatorio"] = true;
			$atributos ['anchoEtiqueta'] = 200;
			$atributos ['tab'] = $tab ++;
			$atributos ['evento'] = '';
			$atributos ['seleccion'] = - 1;
			$atributos ['deshabilitado'] = false;
			$atributos ['columnas'] = 2;
			$atributos ['tamanno'] = 1;
			$atributos ['ajax_function'] = "";
			$atributos ['ajax_control'] = $esteCampo;
			$atributos ['estilo'] = "jqueryui";
			$atributos ['validar'] = "required";
			$atributos ['limitar'] = false;
			$atributos ['anchoCaja'] = 60;
			$atributos ['miEvento'] = '';
			$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( 'filtroVigencia' );
			$matrizItems = $siCapitalRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
			$atributos ['matrizItems'] = $matrizItems;
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroLista ( $atributos );
			unset ( $atributos );
			// ----------------FIN CONTROL: Lista Vigencia--------------------------------------------------------
			
			// ---------------- CONTROL: Lista Vigencia--------------------------------------------------------
			$esteCampo = "unidadEjecutoraCheckRelacionada";
			$atributos ['nombre'] = $esteCampo;
			$atributos ['id'] = $esteCampo;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ["etiquetaObligatorio"] = true;
			$atributos ['tab'] = $tab ++;
			$atributos ['anchoEtiqueta'] = 200;
			$atributos ['evento'] = '';
			if (isset ( $estadoSolicitud )) {
				$atributos ['seleccion'] = $resultadoNecesidadRelacionadaCIIU[0]['num_division'];
			} else {
				$atributos ['seleccion'] = - 1;
			}
			$atributos ['deshabilitado'] = false;
			$atributos ['columnas'] = 2;
			$atributos ['tamanno'] = 1;
			$atributos ['ajax_function'] = "";
			$atributos ['ajax_control'] = $esteCampo;
			$atributos ['estilo'] = "jqueryui";
			$atributos ['validar'] = "required";
			$atributos ['limitar'] = false;
			$atributos ['anchoCaja'] = 60;
			$atributos ['miEvento'] = '';
			
			$matrizItems = array (
					array ( 1, '1 - Rectoría' ),
					array ( 2, '2 - IDEXUD' )
			);
			
			$atributos ['matrizItems'] = $matrizItems;
			
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroLista ( $atributos );
			unset ( $atributos );
			// ----------------FIN CONTROL: Lista Vigencia--------------------------------------------------------
				
				
			$resultado = false;
			$onlyCheck = true;
		}
		
		//echo $cadenaSql;// SI CAPITAL <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
		//var_dump($resultado);
		
		if ($resultado) {
				
				
			echo '<table id="tablaObjetosSinCotizacion" class="display" cellspacing="0" width="100%"> ';
				
			echo "<thead>
				<tr>
					<th><center>Número Contrato</center></th>
					<th><center>Vigencia</center></th>
					<th><center>Unidad Ejecutora</center></th>
					<th><center>Solicitud de Necesidad</center></th>
					<th><center>Número CDP</center></th>
					<th><center>Plazo Ejecución</center></th>
					<th><center>Contratista</center></th>
					<th><center>Tipo Contratista</center></th>
                    <th><center>Ordenador del Gasto</center></th>
					<th><center>Supervisor</center></th>
					<th><center>Fecha Registro</center></th>
					<th><center>Estado</center></th>
					<th><center>Necesidad</center></th>
					<th><center>Contrato</center></th>
					<th><center>Evaluación</center></th>
				</tr>
				</thead>
				<tbody>";
				
			foreach ($resultado as $dato):
				
			$datosCon = array (//Datos
					'numero_contrato' => $dato['numero_contrato'],
					'vigencia' => $dato['vigencia']
			);
			$cadena_sql = $this->miSql->getCadenaSql ( "consultarContratoRelacionado", $datosCon);
			$estadoCont = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
				
				
			$variableView = "pagina=" . $miPaginaActual; // pendiente la pagina para modificar parametro
			$variableView .= "&opcion=verSolicitud";
			$variableView .= "&idSolicitud=" . $dato['numero_solicitud_necesidad'];
			$variableView .= "&vigencia=" . $dato['vigencia'];
			$variableView .= "&unidadEjecutora=" . $dato['unidad_ejecutora'];
			$variableView = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableView, $directorio );
			$imagenView = 'verPro.png';
				
				
			$variableViewCon = "pagina=" . $miPaginaActual; // pendiente la pagina para modificar parametro
			$variableViewCon .= "&opcion=verSolicitudRelacionada";
			$variableViewCon .= "&idSolicitud=" . $dato['numero_contrato'];
			$variableViewCon .= "&vigencia=" . $dato['vigencia'];
			$variableViewCon .= "&unidadEjecutora=" . $dato['unidad_ejecutora'];
			$variableViewCon = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableViewCon, $directorio );
			$imagenViewCon = 'cotPro.png';
		
		
			
			
			
			$variableEdit = "pagina=" . $miPaginaActual; // pendiente la pagina para modificar parametro
			$variableEdit .= "&opcion=modificarSolicitud";
			$variableEdit .= "&idSolicitud=" . $dato['numero_contrato'];
			$variableEdit .= "&vigencia=" . $dato['vigencia'];
			$variableEdit .= "&unidadEjecutora=" . $dato['unidad_ejecutora'];
			$variableEdit = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableEdit, $directorio );
			
			
			
			if(strtoupper ( $estadoCont[0]['estado'] ) == "EVALUADO"){
				$variableEdit = "#";
				$imagenEdit = 'check.png';
				$textEst = "EVALUADO ";
			}else{
				$variableEdit = "#";
				$imagenEdit = 'cancel.png';
				$textEst = "SIN EVALUAR ";
			}
				
		

			if($dato['clase_contratista'] == 'Unión Temporal'){
				//$dato['identificacion_contratista'] = $dato['identificacion_sociedad_temporal'];
				$tipoSoc = "UNION TEMPORAL";
			}else if($dato['clase_contratista'] == 'Consorcio'){
				$tipoSoc = "CONSORCIO";
			}else if($dato['clase_contratista'] == 'Único Contratista'){
				$tipoSoc = "INDIVIDUAL";
				//$dato['identificacion_contratista'] = $dato['convenio'];
			}
			
			if(strtoupper ( $estadoCont[0]['estado'] ) == 'CREADO'){
				$estadoContrato = 'RELACIONADO';
			}else{
				$estadoContrato = strtoupper ( $estadoCont[0]['estado'] );
			}
		
			$mostrarHtml = "<tr>
						<td><center>" . $dato['numero_contrato'] . "</center></td>
						<td><center>" . $dato['vigencia'] . "</center></td>
						<td><center>" . $dato['unidad_ejecutora'] . "</center></td>
						<td><center>" . $dato['numero_solicitud_necesidad'] . "</center></td>
						<td><center>" . $dato['numero_cdp'] . "</center></td>
						<td><center>" . $dato['plazo_ejecucion'] . "</center></td>
					    <td><center>" . $dato['identificacion_contratista'] . "</center></td>
						<td><center>" . $tipoSoc . "</center></td>
						<td><center>" . $dato['nombre_ordenador_gasto'] . "</center></td>
						<td><center>" . $dato['identificacion_supervisor'] . "</center></td>
						<td><center>" . $dato['fecha_registro'] . "</center></td>
						<td><center>" . $estadoContrato . "</center></td>
						<td><center>
							<a href='" . $variableView . "'>
								<img src='" . $rutaBloque . "/images/" . $imagenView . "' width='15px'>
							</a>
						</center></td>
					    <td><center>
							<a href='" . $variableViewCon . "'>
								<img src='" . $rutaBloque . "/images/" . $imagenViewCon . "' width='15px'>
							</a>
						</center></td>
						<td><center>
							" . $textEst . " 
							<a href='" . $variableEdit . "'>
								<img src='" . $rutaBloque . "/images/" . $imagenEdit . "' width='15px'>
							</a>
						</center></td>
					</tr>";
			echo $mostrarHtml;
			unset ( $mostrarHtml );
			unset ( $variableView );
			unset ( $variableEdit );
			endforeach;
				
			echo "</tbody>";
			echo "</table>";
				
		
		} else if(isset($_REQUEST['vigenciaNecesidadRelacionada'])){
			
			if($valorUnidadEjecutoraRelacionada == 1){
				$valorUnidadEjecutoraRelacionadaText = "1 - Rectoría";
			}else{
				$valorUnidadEjecutoraRelacionadaText = "2 - IDEXUD";
			}
			
			// ------------------INICIO Division para los botones-------------------------
			$atributos ["id"] = "divNoEncontroEgresado";
			$atributos ["estilo"] = "marcoBotones";
			echo $this->miFormulario->division ( "inicio", $atributos );
			// -------------SECCION: Controles del Formulario-----------------------
			$esteCampo = "mensajeObjeto";
			$atributos ["id"] = $esteCampo; // Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
			$atributos ["etiqueta"] = "";
			$atributos ["estilo"] = "centrar";
			$atributos ["tipo"] = 'error';
			$atributos ["mensaje"] = "Actualmente no hay Contratos Disponibles con Vigencia <b>".$valorVigenciaRelacionada."</b> Relacionados en AGORA para la 
					Unidad Ejecutora <b>". $valorUnidadEjecutoraRelacionadaText . "</b> </br> Por favor relacione un contrato en la Opción <b>Contrato a RELACIONAR</b>. <br>";
		
			echo $this->miFormulario->cuadroMensaje ( $atributos );
			unset ( $atributos );
			// -------------FIN Control Formulario----------------------
			// ------------------FIN Division para los botones-------------------------
			echo $this->miFormulario->division ( "fin" );
			unset ( $atributos );
		}
		
		
		echo $this->miFormulario->marcoAgrupacion ( 'fin' );
		
		
		
		// ------------------Division para los botones-------------------------
		$atributos ["id"] = "botones";
		$atributos ["estilo"] = "marcoBotones";
		echo $this->miFormulario->division ( "inicio", $atributos );
		{
			// -----------------CONTROL: Botón ----------------------------------------------------------------
			$esteCampo = 'botonContinuar';
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
			$atributos ['nombreFormulario'] = $esteBloque ['nombre'] . "ConsultarRel";
			$tab ++;
				
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			if($onlyCheck){
				echo $this->miFormulario->campoBoton ( $atributos );
			}
			
			// -----------------FIN CONTROL: Botón -----------------------------------------------------------
		}
		// ------------------Fin Division para los botones-------------------------
		echo $this->miFormulario->division ( "fin" );
		
		
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
		
		//$valorCodificado  = "action=" . $esteBloque ["nombre"];
		$valorCodificado = "&pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
		$valorCodificado .= "&bloque=" . $esteBloque ['nombre'];
		$valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
		$valorCodificado .= "&opcion=nuevoRelacionada";
		
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


$miFormulario = new Formulario ( $this->lenguaje, $this->miFormulario, $this->sql  );

$miFormulario->formulario ();
$miFormulario->mensaje ();
?>