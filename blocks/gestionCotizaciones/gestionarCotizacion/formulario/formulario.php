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
	
	function cambiafecha_format($fecha) {
		ereg("([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})", $fecha, $mifecha);
		$fechana = $mifecha[3] . "/" . $mifecha[2] . "/" . $mifecha[1];
		return $fechana;
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
		
		$conexion = "framework";
		$frameworkRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
		
		//$conexion = "sicapital";
		//$siCapitalRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
		
		//$this->cadena_sql = $this->miSql->getCadenaSql ( "listaObjetoContratar", self::OBJETOCOTIZACION );
		//$resultado = $esteRecursoDB->ejecutarAcceso ( $this->cadena_sql, "busqueda" );
		
		
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
		$atributos ['marco'] = false;
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
		

		
		$this->cadena_sql = $this->miSql->getCadenaSql ( "listarObjetosParaCotizacion", $_REQUEST['usuario'] );
		$resultado = $esteRecursoDB->ejecutarAcceso ( $this->cadena_sql, "busqueda" );
		
		$cadenaSql = $this->miSql->getCadenaSql ( 'buscarUsuario', $_REQUEST['usuario'] );
		$resultadoUsuario = $frameworkRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
		if ($resultado) {
			
			$tipo = 'success';
			$mensaje =  "Lista de Solicitudes de Cotización Generadas. </b>
				</br>
				</br><b>Gestión Habilitada</b><center>
				</br><b>Usuario:</b> (" . $resultadoUsuario[0]['identificacion'] . " - " . $resultadoUsuario[0]['nombre'] . " " . $resultadoUsuario[0]['apellido'] . ")</center><br>";
			// ---------------- SECCION: Controles del Formulario -----------------------------------------------
			$esteCampo = 'mensaje';
			$atributos["id"] = $esteCampo; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
			$atributos["etiqueta"] = "";
			$atributos["estilo"] = "centrar";
			$atributos["tipo"] = $tipo;
			$atributos["mensaje"] = $mensaje;
			echo $this->miFormulario->cuadroMensaje($atributos);
			unset($atributos);
			
		
			echo "<div id='marcoDatosLoad' style='width: 100%;height: 900px'>
			<div style='width: 100%;height: 100px'>
			</div>
			<center><img src='" . $rutaBloque . "/images/loading.gif'".' width=20% height=20% vspace=15 hspace=3 >
			</center>
		  </div>';
			

		$esteCampo = "marcoDatosCotizacionList";
		$atributos ['id'] = $esteCampo;
		$atributos ["estilo"] = "jqueryui";
		$atributos ['tipoEtiqueta'] = 'inicio';
		$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo ) . " (" . $resultadoUsuario[0]['nombre'] . " " . $resultadoUsuario[0]['apellido'] . ")";
		echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );

			echo '<table id="tablaObjetosEnCotizacion" class="display" cellspacing="0" width="100%"> ';
				
			echo "<thead>
							<tr>
								<th><center>Número Solicitud</center></th>
								<th><center>Vigencia</center></th>
								<th><center>Unidad Ejecutora</center></th>
								<th><center>Fecha Procesamiento</center></th>
								<th><center>Título</center></th>
								<th><center>Fecha Apertura</center></th>
			                    <th><center>Fecha Cierre</center></th>
								<th><center>Dependencia</center></th>
								<th><center>Tipo Cotización</center></th>
								<th><center>Estado</center></th>
								<th><center>Detalle</center></th>
								<th><center>Modificar</center></th>
								<th><center>Procesar</center></th>
								<th><center>Cotizaciones</center></th>
								<th><center>Borrar</center></th>
							</tr>
							</thead>
							<tbody>";
				
			foreach ($resultado as $dato):
			$variableView = "pagina=" . $miPaginaActual; // pendiente la pagina para modificar parametro
			$variableView .= "&opcion=verSolicitudRelacionada";
			$variableView .= "&idSolicitud=" . $dato['id'];
			$variableView .= "&vigencia=" . $dato['vigencia'];
			$variableView .= "&unidadEjecutora=" . $dato['unidad_ejecutora'];
			$variableView .= "&usuario=" . $_REQUEST['usuario'];
			$variableView = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableView, $directorio );
			$imagenView = 'verPro.png';

			$cadena_sql = $this->miSql->getCadenaSql ( "dependenciaUdistritalById", $dato['jefe_dependencia']);
			$resultadoDep = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );

			if(isset($dato['unidad_ejecutora'])){//CAST
				switch($dato['unidad_ejecutora']){
					case 1 :
						$unidadEjecutora = '1 - RECTORÍA';
						break;
					case 2 :
						$unidadEjecutora = '2 - IDEXUD';
						break;
				}
			}
			
			if(isset($dato['tipo_necesidad'])){//CAST
				switch($dato['tipo_necesidad']){
						case 1 :
							$dato ['tipo_necesidad'] = 'BIEN';
							break;
						case 2 :
							$dato ['tipo_necesidad'] = 'SERVICIO';
							break;
						case 3 :
							$dato ['tipo_necesidad'] = 'BIEN Y SERVICIO';
							break;
				}
			}
			
			if(isset($dato['estado_cotizacion'])){//CAST
				switch($dato['estado_cotizacion']){
						case 1 :
							$estadoCotizacionArq = 'RELACIONADO';
							break;
						case 2 :
							$estadoCotizacionArq = 'COTIZACION';
							break;
						case 3 :
							$estadoCotizacionArq = 'ASIGNADO';
							break;
						case 4 :
							$estadoCotizacionArq = 'CANCELADO';
							break;
						case 5 :
							$estadoCotizacionArq = 'PROCESADO';
							break;
						case 6 :
							$estadoCotizacionArq = 'RECOTIZACION';
							break;
						case 7 :
							$estadoCotizacionArq = 'SELECCIONADO';
							break;
						case 8 :
							$estadoCotizacionArq = 'RECHAZADO';
							break;
				}
			}
			
			if($estadoCotizacionArq == "COTIZACION" || $estadoCotizacionArq == "SELECCIONADO" || $estadoCotizacionArq == "RECHAZADO"){
				
				
				/* VALIDAR FECHA DE CIERRE ++++++++++++++++++++++++++++++++++++*/
				$fecha = date('Y-m-d h:i:s A');
					
				$datetime1 = date_create($dato['fecha_cierre']);
				$datetime2 = date_create($fecha);
				
				$interval = $datetime1->diff($datetime2);
				//echo $interval->format('%R%a días');
				$intervalo = (int)$interval->format('%R%a');
				/*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
				
				if($intervalo > 0){ // Mayor a Cero Dia No lo Incluye If 19/07/2017 - 19/07/2017 No esta Habilitado Aún
					
					$variableAdd = "pagina=" . $miPaginaActual; // pendiente la pagina para modificar parametro
					$variableAdd .= "&opcion=verCotizacionSolicitud";
					$variableAdd .= "&idSolicitud=" . $dato['id'];
					$variableAdd .= "&vigencia=" . $dato['vigencia'];
					$variableAdd .= "&unidadEjecutora=" . $dato['unidad_ejecutora'];
					$variableAdd .= "&usuario=" . $_REQUEST['usuario'];
					$variableAdd = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableAdd, $directorio );
					$imagenAdd = 'cotPro.png';

				}else{
					
					$variableAdd = "#";
					$imagenAdd = 'cancel.png';
					
				}
				
				$variableCan = "#";
				$imagenCan = 'cancel.png';
				
				$variableMod = "#";
				$imagenMod = 'cancel.png';
				
				$variableCal = "#";
				$imagenCal = 'cancel.png';
				
			}else if($estadoCotizacionArq == "ASIGNADO"){
				
				$variableAdd = "#";
				$imagenAdd = 'cancel.png';
				
				$variableMod = "#";
				$imagenMod = 'cancel.png';
				
				$variableCal = "#";
				$imagenCal = 'cancel.png';
				
				$variableCan = "#";
				$imagenCan = 'cancel.png';
				
			}else{
				
				$variableAdd = "#";
				$imagenAdd = 'cancel.png';
				
				$variableMod = "pagina=" . $miPaginaActual; // pendiente la pagina para modificar parametro
				$variableMod .= "&opcion=modificarSolicitudRelacionada";
				$variableMod .= "&idSolicitud=" . $dato['id'];
				$variableMod .= "&vigencia=" . $dato['vigencia'];
				$variableMod .= "&unidadEjecutora=" . $dato['unidad_ejecutora'];
				$variableMod .= "&usuario=" . $_REQUEST['usuario'];
				$variableMod = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableMod, $directorio );
				$imagenMod = 'editPro.png';
				
				$variableCal = "pagina=" . $miPaginaActual; // pendiente la pagina para modificar parametro
				$variableCal .= "&opcion=cotizarSolicitud";
				$variableCal .= "&idSolicitud=" . $dato['id'];
				$variableCal .= "&idObjeto=" . $dato['id'];
				$variableCal .= "&vigencia=" . $dato['vigencia'];
				$variableCal .= "&unidadEjecutora=" . $dato['unidad_ejecutora'];
				$variableCal .= "&tipoNecesidad=" . $dato['tipo_necesidad'];
				$variableCal .= "&botonTrue=true";
				$variableCal .= "&usuario=" . $_REQUEST['usuario'];
				$variableCal = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableCal, $directorio );
				$imagenCal = 'calPro.png';
				
				$variableCan = "pagina=" . $miPaginaActual; // pendiente la pagina para modificar parametro
				$variableCan .= "&opcion=eliminarSolicitud";
				$variableCan .= "&idSolicitud=" . $dato['id'];
				$variableCan .= "&idObjeto=" . $dato['id'];
				$variableCan .= "&vigencia=" . $dato['vigencia'];
				$variableCan .= "&unidadEjecutora=" . $dato['unidad_ejecutora'];
				$variableCan .= "&tipoNecesidad=" . $dato['tipo_necesidad'];
				$variableCan .= "&botonTrue=true";
				$variableCan .= "&usuario=" . $_REQUEST['usuario'];
				$variableCan = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableCan, $directorio );
				$imagenCan = 'canPro.png';
				
				
			}
			if($dato['fecha_solicitud_cotizacion'] != null){
				$dateSolicitud = $this->cambiafecha_format($dato['fecha_solicitud_cotizacion']);
			}else{
				$dateSolicitud = 'SIN PROCESAR';
			}
			
			
			$mostrarHtml = "<tr>
									<td><center>" . $dato['numero_solicitud'] . "</center></td>
									<td><center>" . $dato['vigencia'] . "</center></td>
									<td><center>" . $unidadEjecutora. "</center></td>
									<td><center>" . $dateSolicitud . "</center></td>
									<td><center>" . $dato['titulo_cotizacion'] . "</center></td>
									<td><center>" . $this->cambiafecha_format($dato['fecha_apertura']) . "</center></td>
									<td><center>" . $this->cambiafecha_format($dato['fecha_cierre']) . "</center></td>
									<td><center>" . $resultadoDep[0][1]. "</center></td>".									

									/*<td><center>" . substr($dato['JUSTIFICACION'], 0, 400) . "</center></td>
									<td><center>" . substr($dato['OBJETO'], 0, 400) . "</center></td>*/

									
								   "<td><center>" . $dato['tipo_necesidad'] . "</center></td>
									<td><center>" . $estadoCotizacionArq . "</center></td>
									<td><center>
										<a href='" . $variableView . "'>
											<img src='" . $rutaBloque . "/images/" . $imagenView . "' width='15px'>
										</a>
									</center></td>
		
									<td><center>
										<a href='" . $variableMod . "'>
											<img src='" . $rutaBloque . "/images/" . $imagenMod . "' width='15px'>
										</a>
									</center></td>	
									<td><center>
										<a href='" . $variableCal . "'>
											<img src='" . $rutaBloque . "/images/" . $imagenCal . "' width='15px'>
										</a>
									</center></td>
		
									<td><center>
										<a href='" . $variableAdd . "'>
											<img src='" . $rutaBloque . "/images/" . $imagenAdd . "' width='15px'>
										</a>
									</center></td>
													
									<td><center>
										<a href='" . $variableCan . "'>
											<img src='" . $rutaBloque . "/images/" . $imagenCan . "' width='15px'>
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
			
			echo $this->miFormulario->marcoAgrupacion ( 'fin');

?>



<?php
		} else {
			
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
			$atributos ["mensaje"] = "Actualmente no hay Solicitudes de Cotización Relacionadas por 
			el Perfil. </br><b>Usuario:</b> (" . $resultadoUsuario[0]['identificacion'] . " - " . $resultadoUsuario[0]['nombre'] . " " . $resultadoUsuario[0]['apellido'] . ")</center><br>
				</br> Ingrese por la pestaña Generar Cotización para crear una Solicitud. <br>";
			
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
			if(isset($onlyCheck)){
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
		
		$atributos ['marco'] = false;
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