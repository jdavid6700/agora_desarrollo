<?php
namespace administracion\gestionObjeto\formulario;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	
	exit ();
}
class Formulario {
	var $miConfigurador;
	var $lenguaje;
	var $miFormulario;
	var $miSql;
	
	const OBJETOCOTIZACION = 'COTIZACION'; //Estado objeto creado
	
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
		$conexion = "agora";
		$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
		
		$conexion = "sicapital";
		$siCapitalRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
		
		$this->cadena_sql = $this->miSql->getCadenaSql ( "listaObjetoContratar", self::OBJETOCOTIZACION );
		$resultado = $esteRecursoDB->ejecutarAcceso ( $this->cadena_sql, "busqueda" );
		
		
		// ---------------- SECCION: Parámetros Generales del Formulario ----------------------------------
		$esteCampo = $esteBloque ['nombre']."ConsultarCot";
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
		// Aplica atributos globales al control
		echo $this->miFormulario->formulario ( $atributos );
		
		$esteCampo = "marcoDatosCotizacion";
		$atributos ['id'] = $esteCampo;
		$atributos ["estilo"] = "jqueryui";
		$atributos ['tipoEtiqueta'] = 'inicio';
		$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
		echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
		
		
		
		
		
		if(isset($_REQUEST['vigenciaNecesidadCotizacion'])){
			
			$valorVigenciaCotizacion = $_REQUEST['vigenciaNecesidadCotizacion'];
			$valorUnidadEjecutoraCotizacion = $_REQUEST['unidadEjecutoraCheckCotizacion'];
			
			$datosNec = array (
					'unidadEjecutora' => $valorUnidadEjecutoraCotizacion,
					'vigencia' => $valorVigenciaCotizacion
			);
			
			$this->cadena_sql = $this->miSql->getCadenaSql ( "listarObjetosConCotizacionXVigencia", $datosNec );
			$resultado = $esteRecursoDB->ejecutarAcceso ( $this->cadena_sql, "busqueda" );
			
			if(isset($resultado[0][0])){
				$datos = array (
						'solicitudes' => $resultado[0][0],
						'vigencia' => $valorVigenciaCotizacion,
						'unidadEjecutora' => $valorUnidadEjecutoraCotizacion
				);
			}else{
				$datos = array (//No existen Datos Relacionados ya en el sistema AGORA
						'solicitudes' => "-1",
						'vigencia' => $valorVigenciaCotizacion,
						'unidadEjecutora' => $valorUnidadEjecutoraCotizacion
				);
			}
			
			$cadenaSql = $this->miSql->getCadenaSql ( 'listaSolicitudNecesidadXNumSolicitudEnCotizar', $datos );
			$resultado = $siCapitalRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
				
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
			$esteCampo = "vigenciaNecesidadCotizacion";
			$atributos ['nombre'] = $esteCampo;
			$atributos ['id'] = $esteCampo;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ["etiquetaObligatorio"] = true;
			$atributos ['anchoEtiqueta'] = 200;
			$atributos ['tab'] = $tab ++;
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
			$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( 'filtroVigencia' );
			$matrizItems = $siCapitalRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
			$atributos ['matrizItems'] = $matrizItems;
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroLista ( $atributos );
			unset ( $atributos );
			// ----------------FIN CONTROL: Lista Vigencia--------------------------------------------------------
			
			// ---------------- CONTROL: Lista Vigencia--------------------------------------------------------
			$esteCampo = "unidadEjecutoraCheckCotizacion";
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
		
		
		
		if ($resultado) {
			?>
				<br>
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<h4 class="list-group-item-heading">Solicitudes de Necesidad Relacionadas En (Cotización / Convocatoria)</h4>
							</div>
						</div>
					</div>
				</div>


<?php 

			echo '<table id="tablaObjetosEnCotizacion" class="display" cellspacing="0" width="100%"> ';
				
			echo "<thead>
							<tr>
								<th><center>Número Solicitud</center></th>
								<th><center>Vigencia</center></th>
								<th><center>Unidad Ejecutora</center></th>
								<th><center>Dependencia</center></th>
								<th><center>Fecha Solicitud</center></th>
								<th><center>Origen Solicitud</center></th>
								<th><center>Dependencia Destino</center></th>
								<th><center>Justificación</center></th>
			                    <th><center>Objeto</center></th>
								<th><center>Tipo Contratación</center></th>
								<th><center>Plazo Ejecución</center></th>
								<th><center>Estado</center></th>
								<th><center>Detalle</center></th>
								<th><center>Cotizaciones</center></th>
							</tr>
							</thead>
							<tbody>";
				
			foreach ($resultado as $dato):
			$variableView = "pagina=" . $miPaginaActual; // pendiente la pagina para modificar parametro
			$variableView .= "&opcion=verSolicitudRelacionada";
			$variableView .= "&idSolicitud=" . $dato['NUM_SOL_ADQ'];
			$variableView .= "&vigencia=" . $dato['VIGENCIA'];
			$variableView .= "&unidadEjecutora=" . $dato['CODIGO_UNIDAD_EJECUTORA'];
			$variableView .= "&usuario=" . $_REQUEST['usuario'];
			$variableView = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableView, $directorio );
			$imagenView = 'verPro.png';

			
			
			$variableAdd = "pagina=" . $miPaginaActual; // pendiente la pagina para modificar parametro
			$variableAdd .= "&opcion=verCotizacionSolicitud";
			$variableAdd .= "&idSolicitud=" . $dato['NUM_SOL_ADQ'];
			$variableAdd .= "&vigencia=" . $dato['VIGENCIA'];
			$variableAdd .= "&unidadEjecutora=" . $dato['CODIGO_UNIDAD_EJECUTORA'];
			$variableAdd .= "&usuario=" . $_REQUEST['usuario'];
			$variableAdd = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableAdd, $directorio );
			$imagenAdd = 'cotPro.png';
			
			
			if(!isset($dato['NUM_SOL_ADQ'])) $dato['NUM_SOL_ADQ'] = " ";
			if(!isset($dato['VIGENCIA'])) $dato['VIGENCIA'] = " ";
			if(!isset($dato['CODIGO_UNIDAD_EJECUTORA'])) $dato['CODIGO_UNIDAD_EJECUTORA'] = " ";
			if(!isset($dato['DEPENDENCIA'])) $dato['DEPENDENCIA'] = " ";
			if(!isset($dato['FECHA_SOLICITUD'])) $dato['FECHA_SOLICITUD'] = " ";
			if(!isset($dato['ORIGEN_SOLICITUD'])) $dato['ORIGEN_SOLICITUD'] = " ";
			if(!isset($dato['DEPENDENCIA_DESTINO'])) $dato['DEPENDENCIA_DESTINO'] = " ";
			if(!isset($dato['JUSTIFICACION'])) $dato['JUSTIFICACION'] = " ";
			if(!isset($dato['OBJETO'])) $dato['OBJETO'] = " ";
			if(!isset($dato['TIPO_CONTRATACION'])) $dato['TIPO_CONTRATACION'] = " ";
			if(!isset($dato['PLAZO_EJECUCION'])) $dato['PLAZO_EJECUCION'] = " ";
			if(!isset($dato['ESTADO'])) $dato['ESTADO'] = " ";
			
			$mostrarHtml = "<tr>
									<td><center>" . $dato['NUM_SOL_ADQ'] . "</center></td>
									<td><center>" . $dato['VIGENCIA'] . "</center></td>
									<td><center>" . $dato['CODIGO_UNIDAD_EJECUTORA'] . "</center></td>
									<td><center>" . $dato['DEPENDENCIA'] . "</center></td>
									<td><center>" . $dato['FECHA_SOLICITUD'] . "</center></td>
									<td><center>" . $dato['ORIGEN_SOLICITUD'] . "</center></td>
								    <td><center>" . $dato['DEPENDENCIA_DESTINO'] . "</center></td>
									<td><center>" . substr($dato['JUSTIFICACION'], 0, 400) . "</center></td>
									<td><center>" . substr($dato['OBJETO'], 0, 400) . "</center></td>
									<td><center>" . $dato['TIPO_CONTRATACION'] . "</center></td>
									<td><center>" . $dato['PLAZO_EJECUCION'] . "</center></td>
									<td><center>" . "COTIZACIÓN"/*$dato['ESTADO']*/ . "</center></td>
									<td><center>
										<a href='" . $variableView . "'>
											<img src='" . $rutaBloque . "/images/" . $imagenView . "' width='15px'>
										</a>
									</center></td>
									<td><center>
										<a href='" . $variableAdd . "'>
											<img src='" . $rutaBloque . "/images/" . $imagenAdd . "' width='15px'>
										</a>
									</center></td>	
								</tr>";
			echo $mostrarHtml;
			unset ( $mostrarHtml );
			unset ( $variableView );
			unset ( $variableAdd );
			endforeach;
				
			echo "</tbody>";
			echo "</table>";

?>



<?php
		} else if(isset($_REQUEST['vigenciaNecesidadCotizacion'])){
			
			if($valorUnidadEjecutoraCotizacion == 1){
				$valorUnidadEjecutoraCotizacionText = "1 - Rectoría";
			}else{
				$valorUnidadEjecutoraCotizacionText = "2 - IDEXUD";
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
			$atributos ["mensaje"] = "Actualmente no hay Solicitudes de Necesidad Relacionadas con Vigencia <b>".$valorVigenciaCotizacion."</b> en Cotización para la 
					Unidad Ejecutora <b>". $valorUnidadEjecutoraCotizacionText . "</b>
				</br> Ingrese por la pestaña Objeto a Contratar para crear una Relación. <br>";
			
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
			$atributos ['nombreFormulario'] = $esteBloque ['nombre'] . "ConsultarCot";
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
		$valorCodificado .= "&opcion=nuevoCotizacion";
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


$miFormulario = new Formulario ( $this->lenguaje, $this->miFormulario, $this->sql  );

$miFormulario->formulario ();
$miFormulario->mensaje ();
?>