<?php
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}

use usuarios\cambiarClave\funcion\redireccion;

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
		$atributos ['marco'] = true;
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
		
		//****************************************************************************************
		//****************************************************************************************
		
		$cadenaSql = $this->miSql->getCadenaSql ( 'consultar_proveedor', $_REQUEST ["usuario"] );
		$resultadoDoc = $frameworkRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
		$numeroDocumento = $resultadoDoc[0]['identificacion'];
		
		$cadenaSql = $this->miSql->getCadenaSql ( 'consultar_DatosProveedor', $numeroDocumento );
		$resultadoDats = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
		$idProveedor = $resultadoDats[0]['id_proveedor'];
		$tipoPersona = $resultadoDats[0]['tipopersona'];
		$nombrePersona = $resultadoDats[0]['nom_proveedor'];
		$correo = $resultadoDats[0]['correo'];
		$direccion = $resultadoDats[0]['direccion'];
		
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
			
			?>
			
			<div id="dialogo">
				<p>A continuación podrá observar las distintas cotizaciones en las cuales
				ha recibido una invitación a participar.</p>
			</div>
			
			
			<?php
				
			}
			echo $this->miFormulario->marcoAgrupacion ( 'fin', $atributos );
			
			
			
			$esteCampo = "marcoContratosTabla";
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
			$atributos ['tipoEtiqueta'] = 'inicio';
			$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
			echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
			{
			
			
			$cadena_sql = $this->miSql->getCadenaSql ( "listarObjetosParaCotizacionJoin", $numeroDocumento);
			$resultado = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
                        
                       
                      
                        
                 
			
			
			if ($resultado) {
				
				// -----------------Inicio de Conjunto de Controles----------------------------------------
				$esteCampo = "marcoDatosResultadoParametrizar";
				$atributos ["estilo"] = "jqueryui";
				//echo $this->miFormulario->marcoAgrupacion ( "inicio", $atributos );
				unset ( $atributos );
				
				
				
				echo '<table id="tablaReporteCont" class="display" cellspacing="0" width="100%"> ';
				
				echo "<thead>
							<tr>
								<th><center>Número Solicitud</center></th>
								<th><center>Vigencia</center></th>".
								/*<th><center>Unidad Ejecutora</center></th>
								<th><center>Fecha Solicitud</center></th>*/
								"<th><center>Título</center></th>
								<th><center>Fecha Apertura</center></th>
			                    <th><center>Fecha Cierre</center></th>
								<th><center>Dependencia</center></th>
								<th><center>Tipo Cotización</center></th>
								<th><center>Estado</center></th>
								<th><center>Detalle</center></th>
								<th><center>Responder</center></th>
                                                                <th><center>Detalle Respuesta</center></th>
                                                                <th><center>Respuesta Ordenador</center></th>".
								/*<th><center>Procesar</center></th>
								<th><center>Cotizaciones</center></th>*/
						   "</tr>
							</thead>
							<tbody>";
				
				foreach ($resultado as $dato):
				$variableView = "pagina=" . "gestionarNecesidadConTercero"; // pendiente la pagina para modificar parametro
				$variableView .= "&opcion=verSolicitudRelacionada";
				$variableView .= "&idSolicitud=" . $dato['id_objeto'];
				$variableView .= "&vigencia=" . $dato['vigencia'];
				$variableView .= "&unidadEjecutora=" . $dato['unidad_ejecutora'];
				$variableView .= "&usuario=" . $_REQUEST['usuario'];
				$variableView .= "&paginaOrigen=" . $miPaginaActual;
				$variableView = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableView, $directorio );
				$imagenView = 'verPro.png';
				
				$cadena_sql = $this->miSql->getCadenaSql ( "buscarDependencia", $dato['dependencia']);
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
                                
                                if($dato['estado_solicitud'] == "ABIERTO"){
//					
					$variableAdd = "pagina=" . "gestionarNecesidadConTercero"; // pendiente la pagina para modificar parametro
					$variableAdd .= "&opcion=verCotizacionSolicitud";
					$variableAdd .= "&idSolicitud=" . $dato['id_objeto'];
					$variableAdd .= "&vigencia=" . $dato['vigencia'];
					$variableAdd .= "&unidadEjecutora=" . $dato['unidad_ejecutora'];
					$variableAdd .= "&usuario=" . $_REQUEST['usuario'];
					$variableAdd = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableAdd, $directorio );
					$imagenAdd = 'cotPro.png';
					
					$variableMod = "pagina=" . $miPaginaActual; // pendiente la pagina para modificar parametro
					$variableMod .= "&opcion=modificarSolicitudRelacionada";
					$variableMod .= "&idSolicitud=" . $dato['id_objeto'];
					$variableMod .= "&vigencia=" . $dato['vigencia'];
					$variableMod .= "&unidadEjecutora=" . $dato['unidad_ejecutora'];
                                        $variableMod .= "&numero_solicitud=" . $dato['numero_solicitud'];
                                        $variableMod .= "&vigencia=" . $dato['vigencia'];
                                        $variableMod .= "&titulo_cotizacion=" . $dato['titulo_cotizacion'];
                                        $variableMod .= "&fecha_cierre=" . $this->cambiafecha_format($dato['fecha_cierre']);
					$variableMod .= "&usuario=" . $_REQUEST['usuario'];
					$variableMod .= "&tipoCotizacion=" . $dato['tipo_necesidad'];
					$variableMod = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableMod, $directorio );
					$imagenMod = 'resPro.png';
					
					$variableCal = "#";
					$imagenCal = 'cancel.png';
                                        
                                        $variableDetalle = "#";
					$imagenDetalle = 'cancel.png';
                                        
                                        
									
		
				}else{
                                    
                                    if($dato['estado_solicitud'] == "CERRADO"){
//					
								
					$variableDetalle = "pagina=" . $miPaginaActual; // pendiente la pagina para modificar parametro
					$variableDetalle .= "&opcion=verDetalleRespuesta";
					$variableDetalle .= "&idSolicitud=" . $dato['id_objeto'];
					$variableDetalle .= "&vigencia=" . $dato['vigencia'];
					$variableDetalle .= "&unidadEjecutora=" . $dato['unidad_ejecutora'];
					$variableDetalle .= "&usuario=" . $_REQUEST['usuario'];
					$variableDetalle .= "&tipoCotizacion=" . $dato['tipo_necesidad'];
                                        $variableDetalle .= "&id_proveedor=" . $idProveedor;
                                        
                                        
					$variableDetalle = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableDetalle, $directorio );
					$imagenDetalle = 'verPro.png';
					
                                        $variableMod = "#";
					$imagenMod = 'cancel.png';
					
					
                                       }
                                       else{
                                           
                                          $variableAdd = "#";
					$imagenAdd = 'cancel.png';
					
					$variableMod = "#";
					$imagenMod = 'cancel.png';
					
					$variableCal = "#";
					$imagenCal = 'cancel.png'; 
                                        
                                        $variableDetalle = "#";
					$imagenDetalle = 'cancel.png';
                                       }
					
					
					
				}
                                
                                  $datosConsultaResOrd = array(
                                        'objeto' =>$dato['id_objeto'],
                                        'solicitud' => $dato['id_solicitud']
                                    );
                                $cadena_sql = $this->miSql->getCadenaSql ( "consultarRespuestaOrdenador", $datosConsultaResOrd);
                                $validacionResOrd = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
                                
                                
                                if($validacionResOrd[0][0] != null){
                                    $variableRespuestaOrd = "pagina=" . $miPaginaActual; // pendiente la pagina para modificar parametro
					$variableRespuestaOrd .= "&opcion=verRespuestaOrdenador";
					$variableRespuestaOrd .= "&idSolicitud=" . $dato['id_objeto'];
					$variableRespuestaOrd .= "&vigencia=" . $dato['vigencia'];
					$variableRespuestaOrd .= "&unidadEjecutora=" . $dato['unidad_ejecutora'];
					$variableRespuestaOrd .= "&usuario=" . $_REQUEST['usuario'];
					$variableRespuestaOrd .= "&tipoCotizacion=" . $dato['tipo_necesidad'];
                                        $variableRespuestaOrd .= "&id_proveedor=" . $idProveedor;
                                        
                                        $variableRespuestaOrd = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableRespuestaOrd, $directorio );
					$imagenRespuestaOrd = 'verPro.png';
                                        $variableMod = "#";
					$imagenMod = 'cancel.png';
                                }
                                else{
                                    $variableRespuestaOrd = "#";
			            $imagenRespuestaOrd = 'cancel.png';
                                }
                        
//                                
//                                SELECT id_respuesta_ordenador, id_respuesta_directa, id_solicitud, id_objeto, 
//       respuesta, decision
//  FROM agora.respuesta_cotizacion_ordenador;

                                
                                if($dato['fecha_cierre'] < date('Y-m-d')){
                                    
                                    if($dato['estado'] == 'ASIGNADO'){
                                               $dato['estado']='FINALIZADA';
                                               
                                       }
                                       else{
                                           $dato['estado']='EN ESTUDIO';
                                       }
//					
                                        
                                                                              
                                        $variableMod = "#";
					$imagenMod = 'cancel.png';
                                        
                                 
					
					
				}
                                
                                 if($dato['estado'] == 'ASIGNADO'){
                                               $dato['estado']='FINALIZADA';
                                               
                                       }            
                            
                                
//				if($dato['estado'] == "COTIZACION"){
//					
//					$variableAdd = "pagina=" . "gestionarNecesidadConTercero"; // pendiente la pagina para modificar parametro
//					$variableAdd .= "&opcion=verCotizacionSolicitud";
//					$variableAdd .= "&idSolicitud=" . $dato['id_objeto'];
//					$variableAdd .= "&vigencia=" . $dato['vigencia'];
//					$variableAdd .= "&unidadEjecutora=" . $dato['unidad_ejecutora'];
//					$variableAdd .= "&usuario=" . $_REQUEST['usuario'];
//					$variableAdd = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableAdd, $directorio );
//					$imagenAdd = 'cotPro.png';
//					
//					$variableMod = "pagina=" . $miPaginaActual; // pendiente la pagina para modificar parametro
//					$variableMod .= "&opcion=modificarSolicitudRelacionada";
//					$variableMod .= "&idSolicitud=" . $dato['id_objeto'];
//					$variableMod .= "&vigencia=" . $dato['vigencia'];
//					$variableMod .= "&unidadEjecutora=" . $dato['unidad_ejecutora'];
//					$variableMod .= "&usuario=" . $_REQUEST['usuario'];
//					$variableMod .= "&tipoCotizacion=" . $dato['tipo_necesidad'];
//					$variableMod = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableMod, $directorio );
//					$imagenMod = 'resPro.png';
//					
//					$variableCal = "#";
//					$imagenCal = 'cancel.png';
//					
//				}else if($dato['estado'] == "ASIGNADO"){
//					
//					$variableAdd = "#";
//					$imagenAdd = 'cancel.png';
//					
//					$variableMod = "#";
//					$imagenMod = 'cancel.png';
//					
//					$variableCal = "#";
//					$imagenCal = 'cancel.png';
//					
//				}
				
				
				if($dato['fecha_solicitud_cotizacion'] != null){
					$dateSolicitud = $this->cambiafecha_format($dato['fecha_solicitud_cotizacion']);
				}else{
					$dateSolicitud = $dato['fecha_solicitud_cotizacion'];
				}
				
				
				$mostrarHtml = "<tr>
									<td><center>" . $dato['numero_solicitud'] . "</center></td>
									<td><center>" . $dato['vigencia'] . "</center></td>".
									/*<td><center>" . $unidadEjecutora. "</center></td>
									<td><center>" . $dateSolicitud . "</center></td>*/
									"<td><center>" . $dato['titulo_cotizacion'] . "</center></td>
									<td><center>" . $this->cambiafecha_format($dato['fecha_apertura']) . "</center></td>
									<td><center>" . $this->cambiafecha_format($dato['fecha_cierre']) . "</center></td>
									<td><center>" . $resultadoDep[0][0]. "</center></td>".
									
									/*<td><center>" . substr($dato['JUSTIFICACION'], 0, 400) . "</center></td>
									 <td><center>" . substr($dato['OBJETO'], 0, 400) . "</center></td>*/
				
				
				"<td><center>" . $dato['tipo_necesidad'] . "</center></td>
									<td><center>" . $dato['estado'] . "</center></td>
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
										<a href='" . $variableDetalle . "'>
											<img src='" . $rutaBloque . "/images/" . $imagenDetalle . "' width='15px'>
										</a>
									</center></td>
                                        <td><center>
										<a href='" . $variableRespuestaOrd . "'>
											<img src='" . $rutaBloque . "/images/" . $imagenRespuestaOrd . "' width='15px'>
										</a>
									</center></td>".
                                        
                                        
                                        
                                        
                                        
									/*<td><center>
										<a href='" . $variableCal . "'>
											<img src='" . $rutaBloque . "/images/" . $imagenCal . "' width='15px'>
										</a>
									</center></td>
							
									<td><center>
										<a href='" . $variableAdd . "'>
											<img src='" . $rutaBloque . "/images/" . $imagenAdd . "' width='15px'>
										</a>
									</center></td>*/
								"</tr>";
				echo $mostrarHtml;
				unset ( $mostrarHtml );
				unset ( $variableView );
				unset ( $variableAdd );
				endforeach;
				
				echo "</tbody>";
				echo "</table>";
				
				
				
				//echo $this->miFormulario->agrupacion ( 'fin' );
				unset ( $atributos );
			} else {
				
				$atributos ["id"] = "divNoEncontroEgresado";
				$atributos ["estilo"] = "marcoBotones";
				echo $this->miFormulario->division ( "inicio", $atributos );
				
				// -------------Control Boton-----------------------
				$esteCampo = "noEncontroContrato";
				$atributos ["id"] = $esteCampo; // Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
				$atributos ["etiqueta"] = "";
				$atributos ["estilo"] = "centrar";
				$atributos ["tipo"] = 'error';
				$atributos ["mensaje"] = $this->lenguaje->getCadena ( $esteCampo );
				
				echo $this->miFormulario->cuadroMensaje ( $atributos );
				unset ( $atributos );
				
				// ------------------Fin Division para los botones-------------------------
				echo $this->miFormulario->division ( "fin" );
			}
			
			$atributos ['marco'] = true;
			$atributos ['tipoEtiqueta'] = 'fin';
			echo $this->miFormulario->formulario ( $atributos );
			unset ( $atributos );
			
			
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
					//echo $this->miFormulario->campoBoton ( $atributos );
					// -----------------FIN CONTROL: Botón -----------------------------------------------------------
				
				

				}
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
			
			$valorCodificado = "action=" . $esteBloque ["nombre"];
			$valorCodificado .= "&pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
			$valorCodificado .= "&bloque=" . $esteBloque ['nombre'];
			$valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
			$valorCodificado .= "&opcion=registrar";

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
