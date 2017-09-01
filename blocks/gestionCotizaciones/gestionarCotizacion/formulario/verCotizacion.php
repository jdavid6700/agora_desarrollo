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
	
	private $UNIDADES = array(
			'',
			'UN ',
			'DOS ',
			'TRES ',
			'CUATRO ',
			'CINCO ',
			'SEIS ',
			'SIETE ',
			'OCHO ',
			'NUEVE ',
			'DIEZ ',
			'ONCE ',
			'DOCE ',
			'TRECE ',
			'CATORCE ',
			'QUINCE ',
			'DIECISEIS ',
			'DIECISIETE ',
			'DIECIOCHO ',
			'DIECINUEVE ',
			'VEINTE '
	);
	private $DECENAS = array(
			'VEINTI',
			'TREINTA ',
			'CUARENTA ',
			'CINCUENTA ',
			'SESENTA ',
			'SETENTA ',
			'OCHENTA ',
			'NOVENTA ',
			'CIEN '
	);
	private $CENTENAS = array(
			'CIENTO ',
			'DOSCIENTOS ',
			'TRESCIENTOS ',
			'CUATROCIENTOS ',
			'QUINIENTOS ',
			'SEISCIENTOS ',
			'SETECIENTOS ',
			'OCHOCIENTOS ',
			'NOVECIENTOS '
	);
	private $MONEDAS = array(
			array('country' => 'Colombia', 'currency' => 'COP', 'singular' => 'PESO COLOMBIANO', 'plural' => 'PESOS COLOMBIANOS', 'symbol', '$'),
			array('country' => 'Estados Unidos', 'currency' => 'USD', 'singular' => 'DÓLAR', 'plural' => 'DÓLARES', 'symbol', 'US$'),
			array('country' => 'El Salvador', 'currency' => 'USD', 'singular' => 'DÓLAR', 'plural' => 'DÓLARES', 'symbol', 'US$'),
			array('country' => 'Europa', 'currency' => 'EUR', 'singular' => 'EURO', 'plural' => 'EUROS', 'symbol', '€'),
			array('country' => 'México', 'currency' => 'MXN', 'singular' => 'PESO MEXICANO', 'plural' => 'PESOS MEXICANOS', 'symbol', '$'),
			array('country' => 'Perú', 'currency' => 'PEN', 'singular' => 'NUEVO SOL', 'plural' => 'NUEVOS SOLES', 'symbol', 'S/'),
			array('country' => 'Reino Unido', 'currency' => 'GBP', 'singular' => 'LIBRA', 'plural' => 'LIBRAS', 'symbol', '£'),
			array('country' => 'Argentina', 'currency' => 'ARS', 'singular' => 'PESO', 'plural' => 'PESOS', 'symbol', '$')
	);
	private $separator = '.';
	private $decimal_mark = ',';
	private $glue = ' CON ';
	
	
	function __construct($lenguaje, $formulario, $sql) {
		$this->miConfigurador = \Configurador::singleton ();
		$this->miConfigurador->fabricaConexiones->setRecursoDB ( 'principal' );
		$this->lenguaje = $lenguaje;
		$this->miFormulario = $formulario;
		$this->miSql = $sql;
	}
	
	public function to_word($number, $miMoneda = null) {
		if (strpos($number, $this->decimal_mark) === FALSE) {
			$convertedNumber = array(
					$this->convertNumber($number, $miMoneda, 'entero')
			);
		} else {
			$number = explode($this->decimal_mark, str_replace($this->separator, '', trim($number)));
			$convertedNumber = array(
					$this->convertNumber($number[0], $miMoneda, 'entero'),
					$this->convertNumber($number[1], $miMoneda, 'decimal'),
			);
		}
		return implode($this->glue, array_filter($convertedNumber));
	}
	/**
	 * Convierte número a letras
	 * @param $number
	 * @param $miMoneda
	 * @param $type tipo de dígito (entero/decimal)
	 * @return $converted string convertido
	 */
	private function convertNumber($number, $miMoneda = null, $type) {
	
		$converted = '';
		if ($miMoneda !== null) {
			try {
	
				$moneda = array_filter($this->MONEDAS, function($m) use ($miMoneda) {
					return ($m['currency'] == $miMoneda);
				});
					$moneda = array_values($moneda);
					if (count($moneda) <= 0) {
						throw new Exception("Tipo de moneda inválido");
						return;
					}
					($number < 2 ? $moneda = $moneda[0]['singular'] : $moneda = $moneda[0]['plural']);
			} catch (Exception $e) {
				echo $e->getMessage();
				return;
			}
		}else{
			$moneda = '';
		}
		if (($number < 0) || ($number > 999999999)) {
			return false;
		}
		$numberStr = (string) $number;
		$numberStrFill = str_pad($numberStr, 9, '0', STR_PAD_LEFT);
		$millones = substr($numberStrFill, 0, 3);
		$miles = substr($numberStrFill, 3, 3);
		$cientos = substr($numberStrFill, 6);
		if (intval($millones) > 0) {
			if ($millones == '001') {
				$converted .= 'UN MILLON ';
			} else if (intval($millones) > 0) {
				$converted .= sprintf('%sMILLONES ', $this->convertGroup($millones));
			}
		}
	
		if (intval($miles) > 0) {
			if ($miles == '001') {
				$converted .= 'MIL ';
			} else if (intval($miles) > 0) {
				$converted .= sprintf('%sMIL ', $this->convertGroup($miles));
			}
		}
		if (intval($cientos) > 0) {
			if ($cientos == '001') {
				$converted .= 'UN ';
			} else if (intval($cientos) > 0) {
				$converted .= sprintf('%s ', $this->convertGroup($cientos));
			}
		}
		$converted .= $moneda;
		return $converted;
	}
	/**
	 * Define el tipo de representación decimal (centenas/millares/millones)
	 * @param $n
	 * @return $output
	 */
	public function convertGroup($n) {
		$output = '';
		if ($n == '100') {
			$output = "CIEN ";
		} else if ($n[0] !== '0') {
			$output = $this->CENTENAS[$n[0] - 1];
		}
		$k = intval(substr($n,1));
		if ($k <= 20) {
			$output .= $this->UNIDADES[$k];
		} else {
			if(($k > 30) && ($n[2] !== '0')) {
				$output .= sprintf('%sY %s', $this->DECENAS[intval($n[1]) - 2], $this->UNIDADES[intval($n[2])]);
			} else {
				$output .= sprintf('%s%s', $this->DECENAS[intval($n[1]) - 2], $this->UNIDADES[intval($n[2])]);
			}
		}
		return $output;
	}
	
	
	function cambiafecha_format($fecha) {
		ereg("([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})", $fecha, $mifecha);
		$fechana = $mifecha[3] . "/" . $mifecha[2] . "/" . $mifecha[1];
		return $fechana;
	}
	
	function inverseTotalDias($days){
	
		$nyears = intval($days/360);
		$nmonths = intval(($days-intval($days/360)*360)/30);
		$ndays = intval($days-(intval($days/360)*360+intval(($days-intval($days/360)*360)/30)*30));
	
		return $nyears . " AÑO(S) - " . $nmonths . " MES(ES) - " . $ndays . " DÍA(S)";
	
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
			
			
			$paraIdSolicitud = $_REQUEST['idObjeto'];
			$paraVigencia = $_REQUEST['vigencia'];
			$paraUnidad = $_REQUEST['unidadEjecutora'];
			$paraUsuario = $_REQUEST['usuario'];
			
			$variableRet = "pagina=" . $miPaginaActual . "&opcion=verCotizacionSolicitud&idSolicitud=".$paraIdSolicitud
							."&vigencia=".$paraVigencia."&unidadEjecutora=".$paraUnidad."&usuario=".$paraUsuario;
			$variableRet = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableRet, $directorio );
					
			
			
			
			echo "<div id='marcoDatosLoad' style='width: 100%;height: 900px'>
			<div style='width: 100%;height: 100px'>
			</div>
			<center><img src='" . $rutaBloque . "/images/loading.gif'".' width=20% height=20% vspace=15 hspace=3 >
			</center>
		  </div>';
			
			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
			$esteCampo = "marcoContratos";
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
			$atributos ['tipoEtiqueta'] = 'inicio';
			$atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo);
			echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);
			unset($atributos);
			

			$atributos["id"]="botones";
			$atributos["estilo"]="marcoBotones widget";
			echo $this->miFormulario->division("inicio",$atributos);
				
			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
			$esteCampo = 'botonRegresar';
			$atributos ['id'] = $esteCampo;
			$atributos ['enlace'] = $variableRet;
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
			
			
			//****************************************************************************************
			//****************************************************************************************
			
			$datosConsultaSol = array(
					'proveedor' =>$_REQUEST['idProveedor'],
					'solicitud' => $_REQUEST['idObjeto']
			);
			
			$cadenaSql = $this->miSql->getCadenaSql('consultarIdsolicitud', $datosConsultaSol);
			$id_solicitud= $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda", $datosConsultaSol, 'consultarIdsolicitud');
			
			
			$cadenaSql = $this->miSql->getCadenaSql('consultar_respuesta', $id_solicitud[0][0]);
			$resultadoRespuesta = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
			
			
			$cadenaSql = $this->miSql->getCadenaSql ( 'consultar_DatosProveedor', $_REQUEST['idProveedor'] );
			$resultadoDats = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
			
			$idProveedor = $resultadoDats[0]['id_proveedor'];
			$tipoPersona = $resultadoDats[0]['tipopersona'];
			$nombrePersona = $resultadoDats[0]['nom_proveedor'];
			$correo = $resultadoDats[0]['correo'];
			$direccion = $resultadoDats[0]['direccion'];
			
			$numeroDocumento = $resultadoDats[0]['num_documento'];
			
			$esteCampo = "marcoInfoCont";
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
			$atributos ['tipoEtiqueta'] = 'inicio';
			$atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo);
			echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);
			{
			
			
				//INICIO INFORMACION
				echo "<span class='textoElegante textoGrande textoAzul'>Nombre de la Persona: </span>";
				echo "<span class='textoElegante textoGrande textoGris'>" . $nombrePersona . "</span></br>";
				echo "<span class='textoElegante textoGrande textoAzul'>Documento : </span>";
				echo "<span class='textoElegante textoGrande textoGris'>" . $numeroDocumento . "</span></br>";
				echo "<span class='textoElegante textoGrande textoAzul'>Tipo Persona : </span>";
				echo "<span class='textoElegante textoGrande textoGris'>" . $tipoPersona . "</span></br>";
				echo "<span class='textoElegante textoGrande textoAzul'>Dirección : </span>";
				echo "<span class='textoElegante textoGrande textoGris'>" . $direccion . "</span></br>";
				echo "<span class='textoElegante textoGrande textoAzul'>Correo : </span>";
				echo "<span class='textoElegante textoGrande textoGris'>" . $correo . "</span></br>";
				//FIN INFORMACION
			}
			echo $this->miFormulario->marcoAgrupacion('fin', $atributos);
			
			if (isset($_REQUEST['tipoCotizacion']) && $_REQUEST['tipoCotizacion'] == 'BIEN') {
				$campo1 = "entregables";
				$campo2 = "plazoEntrega";
			} else {
				if (isset($_REQUEST['tipoCotizacion']) && $_REQUEST['tipoCotizacion'] == 'SERVICIO') {
					$campo1 = "desServicio";
					$campo2 = "detalleEjecucion";
				} else {
					$campo1 = "entregablesdesServicio";
					$campo2 = "entregaEjecucion";
				}
			}
			
			
			$tipo = 'information';
			$mensaje = "<b>IMPORTANTE</b><br>
							<br>
							Recuerde que la reglamentación a tener en cuenta para los procesos derivados de las cotizaciones, son el estatuto de contratación de la universidad y el acuerdo de supervisión e interventoria de contratos estipulados en el
				<b>ACUERDO No. 03 (11 de Marzo de 2015)</b> <i>'Por el cual se expide el Estatuto de Contratación de la Universidad Distrital Francisco José de Caldas'</i> y la
				<b>RESOLUCIÓN  No. 629 (17 de Noviembre de 2016)</b>    <i>'Por medio de la cual se adopta el Manual De.Supervisión e Interventoría de la Universidad Distrital Francisco José de Caldas'</i>.
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
			
			
			
			$esteCampo = "marcoContratosTablaRes";
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
			$atributos ['tipoEtiqueta'] = 'inicio';
			$atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo) . " PROVEEDOR (".$numeroDocumento." - ".$nombrePersona.")";
			echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);
			{
			
			
				// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
				$esteCampo = $campo1;
				$atributos ['id'] = $esteCampo;
				$atributos ['nombre'] = $esteCampo;
				$atributos ['tipo'] = 'text';
				$atributos ['estilo'] = 'jqueryui';
				$atributos ['marco'] = true;
				$atributos ['estiloMarco'] = '';
				$atributos ["etiquetaObligatorio"] = false;
				$atributos ['columnas'] = 120;
				$atributos ['filas'] = 8;
				$atributos ['dobleLinea'] = 0;
				$atributos ['tabIndex'] = $tab;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena($campo1);
				$atributos ['validar'] = 'required,minSize[4]';
				$atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
				$atributos ['deshabilitado'] = true;
				$atributos ['tamanno'] = 20;
				$atributos ['maximoTamanno'] = '';
				$atributos ['anchoEtiqueta'] = 220;
				$atributos ['textoEnriquecido'] = true; //Este atributo se coloca una sola vez en todo el formulario (ERROR paso de datos)
			
				$atributos ['valor'] = $resultadoRespuesta[0]['descripcion'];
			
				$tab ++;
			
				// Aplica atributos globales al control
				$atributos = array_merge($atributos, $atributosGlobales);
				echo $this->miFormulario->campoTextArea($atributos);
				unset($atributos);
			
			
				// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
				$esteCampo = $campo2;
				$atributos ['id'] = $esteCampo;
				$atributos ['nombre'] = $esteCampo;
				$atributos ['tipo'] = 'text';
				$atributos ['estilo'] = 'jqueryui';
				$atributos ['marco'] = true;
				$atributos ['estiloMarco'] = '';
				$atributos ["etiquetaObligatorio"] = false;
				$atributos ['columnas'] = 120;
				$atributos ['filas'] = 8;
				$atributos ['dobleLinea'] = 0;
				$atributos ['tabIndex'] = $tab;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena($campo2);
				$atributos ['validar'] = 'required,minSize[4]';
				$atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
				$atributos ['deshabilitado'] = true;
				$atributos ['tamanno'] = 20;
				$atributos ['maximoTamanno'] = '';
				$atributos ['anchoEtiqueta'] = 220;
			
			
			
				$atributos ['valor'] = $resultadoRespuesta[0]['informacion_entrega'];
			
				$tab ++;
			
				// Aplica atributos globales al control
				$atributos = array_merge($atributos, $atributosGlobales);
				echo $this->miFormulario->campoTextArea($atributos);
				unset($atributos);
			
				$cadena_sql = $this->miSql->getCadenaSql ( "buscarDetalleItemsProducto", $resultadoRespuesta[0]['id']);
				$resultadoItems = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
			
			
				$esteCampo = "marcoDescripcionProducto";
				$atributos ['id'] = $esteCampo;
				$atributos ["estilo"] = "jqueryui";
				$atributos ['tipoEtiqueta'] = 'inicio';
				$atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo);
				echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);
				{
			
					$esteCampo = "marcoDetallePro";
					$atributos ['id'] = $esteCampo;
					$atributos ["estilo"] = "jqueryui";
					$atributos ['tipoEtiqueta'] = 'inicio';
					$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
					echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
			
			
					unset ( $atributos );
			
			
					?>
			                            			
			                            						
			                            						<table id="tablaFP" class="table1" width="100%" >
			                            							<!-- Cabecera de la tabla -->
			                            							<thead>
			                            								<tr>
			                            									<th width="10%" >Nombre</th>
			                            									<th width="25%" >Descripción</th>
			                            									<th width="10%" >Tipo</th>
			                            									<th width="10%" >Unidad</th>
			                            									<th width="20%" >Tiempo de Ejecución</th>
			                            									<th width="10%" >Cantidad</th>
			                            									<th width="10%" >Valor Unitario</th>
			                            									<th width="5%" >&nbsp;</th>
			                            								</tr>
			                            							</thead>
			                            						 
			                            							<!-- Cuerpo de la tabla con los campos -->
																	<tbody>
																 
																		<!-- fila base para clonar y agregar al final -->
																		<!-- fin de código: fila base -->
																 		
									
																 		
																 		<?php 
																 		
																 		$valorPrecioTotal = 0;
																 		
																 		if (isset ( $resultadoItems ) && $resultadoItems) {
																 		$count = count($resultadoItems);
																 		$i = 0;
																 		
																	 		while ($i < $count){
																	 			
																	 			if($resultadoItems[$i]['tipo_necesidad'] == 1){
																	 				$tipo = "1 - BIEN";
																	 			}else{
																	 				$tipo = "2 - SERVICIO";
																	 			}
																	 			
																	 			if($resultadoItems[$i]['unidad'] == 0){
																	 				$unidad = "0 - NO APLICA";
																	 			}else{
																	 				
																	 				$cadena_sql = $this->miSql->getCadenaSql ( "buscarUnidadItem", $resultadoItems[$i]['unidad']);
																	 				$resultadoUnidadItem = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
											
																	 				$unidad = $resultadoUnidadItem[0]['id']." - ".$resultadoUnidadItem[0]['unidad'];
																	 			}
																	 			
																	 			if($resultadoItems[$i]['tiempo_ejecucion'] == 0){
																	 				$tiempo = "0 - NO APLICA";
																	 			}else{
																	 				$tiempo = $this->inverseTotalDias($resultadoItems[$i]['tiempo_ejecucion']);
																	 			}
																	 			
																	 			$valorPrecioTotal += ($resultadoItems[$i]['cantidad'] * $resultadoItems[$i]['valor_unitario']);
																	 			
																	 		?>
																			<tr id="nFilas" >
																				<td><?php echo $resultadoItems[$i]['nombre']  ?></td>
																 				<td><?php echo $resultadoItems[$i]['descripcion']  ?></td>
																 				<td><?php echo $tipo  ?></td>
																 				<td><?php echo $unidad  ?></td>
																 				<td><?php echo $tiempo  ?></td>
																 				<td><?php echo number_format(round($resultadoItems[$i]['cantidad'],0), 0, '', '.')  ?></td>
																 				<td><?php echo "$ " . number_format(round($resultadoItems[$i]['valor_unitario'],0), 0, '', '.')  ?></td>
																 				<th scope="row"><div class = "widget"><?php echo $i+1  ?></div></th>
																 			</tr>
																			<?php
																			$i++;
																	 		}
																 		}
																 	
																		
																		?>
																 
																	</tbody>
			                            						</table>
			                            						<!-- Botón para agregar filas -->
			                            						<!-- 
			                            						<input type="button" id="agregar" value="Agregar fila" /> -->
			                            						
			                            			
			                            			
			                            						
			                            						
			                            						<?php
			                            						
			                            						
			                            						
			                            	
			                            						
			                            						echo $this->miFormulario->marcoAgrupacion ( 'fin' );
			                            			
			                            						unset ( $atributos );
			                            						$esteCampo = 'precioCarga';
			                            						$atributos ["id"] = $esteCampo; // No cambiar este nombre
			                            						$atributos ["tipo"] = "hidden";
			                            						$atributos ['estilo'] = '';
			                            						$atributos ["obligatorio"] = false;
			                            						$atributos ['marco'] = false;
			                            						$atributos ["etiqueta"] = "";
			                            						
			                            						$atributos ['valor'] = $valorPrecioTotal;
			                            						
			                            						$atributos ['validar'] = '';
			                            						$atributos = array_merge ( $atributos, $atributosGlobales );
			                            						echo $this->miFormulario->campoCuadroTexto ( $atributos );
			                            						unset ( $atributos );
			                            						
			                            						unset ( $atributos );
			                            						$esteCampo = 'idsItems';
			                            						$atributos ["id"] = $esteCampo; // No cambiar este nombre
			                            						$atributos ["tipo"] = "hidden";
			                            						$atributos ['estilo'] = '';
			                            						$atributos ["obligatorio"] = false;
			                            						$atributos ['marco'] = false;
			                            						$atributos ["etiqueta"] = "";
			                            						if (isset ( $_REQUEST [$esteCampo] )) {
			                            							$atributos ['valor'] = $_REQUEST [$esteCampo];
			                            						} else {
			                            							$atributos ['valor'] = '';
			                            						}
			                            						$atributos ['validar'] = '';
			                            						$atributos = array_merge ( $atributos, $atributosGlobales );
			                            						echo $this->miFormulario->campoCuadroTexto ( $atributos );
			                            						unset ( $atributos );
			                            						
			                            						$esteCampo = 'countItems';
			                            						$atributos ["id"] = $esteCampo; // No cambiar este nombre
			                            						$atributos ["tipo"] = "hidden";
			                            						$atributos ['estilo'] = '';
			                            						$atributos ["obligatorio"] = false;
			                            						$atributos ['marco'] = false;
			                            						$atributos ["etiqueta"] = "";
			                            						if (isset ( $_REQUEST [$esteCampo] )) {
			                            							$atributos ['valor'] = $_REQUEST [$esteCampo];
			                            						} else {
			                            							$atributos ['valor'] = '';
			                            						}
			                            						$atributos ['validar'] = '';
			                            						$atributos = array_merge ( $atributos, $atributosGlobales );
			                            						echo $this->miFormulario->campoCuadroTexto ( $atributos );
			                            						unset ( $atributos );
			                            						
			                            			echo $this->miFormulario->marcoAgrupacion ( 'fin' );
			                            
			                            
			                            
			                            			$promedio = $valorPrecioTotal;
			                            			if($promedio > 999999999 && $promedio <= 999999999999){
			                            			
			                            				$restCast = substr((int)$promedio, -9);
			                            				$rest = str_replace ( $restCast , "" , (int)$promedio );
			                            				$rest = str_pad($rest, 3, '0', STR_PAD_LEFT);
			                            			
			                            				if ($rest == '001') {
			                            					$converted = 'MIL ';
			                            				} else if (intval($rest) > 0) {
			                            					$converted = sprintf('%sMIL ', $this->convertGroup($rest));
			                            				}
			                            			
			                            				$converted .= $this->to_word($restCast, 'COP');
			                            			}else{
			                            				$converted = $this->to_word($promedio, 'COP');
			                            			}
			                            			
			                            			$dineroCast = $converted;
			                            			
			                            			echo "<center>";
			                            			// ------------------Division para los botones-------------------------
			                            			$atributos ["id"] = "botones";
			                            			$atributos ["estilo"] = "jqueryui clean-gray";
			                            			$atributos ['tabIndex'] = $tab;
			                            			echo $this->miFormulario->division("inicio", $atributos);
			                            			{
			                            				 
			                            				// ----------------INICIO CONTROL: Campo de Texto FECHA SOLICITUD--------------------------------------------------------
			                            				$esteCampo = 'precioCot';
			                            				$atributos ['id'] = $esteCampo;
			                            				$atributos ['nombre'] = $esteCampo;
			                            				$atributos ['tipo'] = 'text';
			                            				$atributos ['estilo'] = 'jqueryui';
			                            				$atributos ['marco'] = true;
			                            				$atributos ['estiloMarco'] = '';
			                            				$atributos ["etiquetaObligatorio"] = false;
			                            				$atributos ['columnas'] = 1;
			                            				$atributos ['dobleLinea'] = 0;
			                            				$atributos ['tabIndex'] = $tab;
			                            				$atributos ['etiqueta'] = "<b>".$this->lenguaje->getCadena($esteCampo)."</b>";
			                            				$atributos ['validar'] = 'required, minSize[1], maxSize[40]';
			                            				 
			                            				$atributos ['valor'] = '';
			                            				 
			                            				$atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
			                            				$atributos ['deshabilitado'] = true;
			                            				$atributos ['tamanno'] = 55;
			                            				$atributos ['maximoTamanno'] = '75';
			                            				$atributos ['anchoEtiqueta'] = 400;
			                            				$tab ++;
			                            				 
			                            				// Aplica atributos globales al control
			                            				$atributos = array_merge($atributos, $atributosGlobales);
			                            				echo $this->miFormulario->campoCuadroTexto($atributos);
			                            				unset($atributos);
			                            				// ----------------FIN CONTROL: Campo de Texto FECHA SOLICITUD--------------------------------------------------------
			                            				 
			                            				echo "<div class='lefht' >";
			                            				echo "<b>VALOR DE LA COTIZACIÓN EN LETRAS:</b>  ". $dineroCast;
			                            				echo "</div>";
			                            			}
			                            			echo $this->miFormulario->division("fin");
			                            			echo "</center>";
			                            			
			                            
			                            
			                            // ----------------INICIO CONTROL: Campo de Texto FECHA SOLICITUD--------------------------------------------------------
			                            $esteCampo = 'fechaVencimientoCotRead';
			                            $atributos ['id'] = $esteCampo;
			                            $atributos ['nombre'] = $esteCampo;
			                            $atributos ['tipo'] = 'text';
			                            $atributos ['estilo'] = 'jqueryui';
			                            $atributos ['marco'] = true;
			                            $atributos ['estiloMarco'] = '';
			                            $atributos ["etiquetaObligatorio"] = true;
			                            $atributos ['columnas'] = 1;
			                            $atributos ['dobleLinea'] = 0;
			                            $atributos ['tabIndex'] = $tab;
			                            $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
			                            $atributos ['validar'] = 'required,custom[date]';
			                            
			                            $atributos ['valor'] =  $this->cambiafecha_format($resultadoRespuesta[0]['fecha_vencimiento']);
			                            
			                            $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
			                            $atributos ['deshabilitado'] = true;
			                            $atributos ['tamanno'] = 15;
			                            $atributos ['maximoTamanno'] = '30';
			                            $atributos ['anchoEtiqueta'] = 400;
			                            $tab ++;
			                            
			                            // Aplica atributos globales al control
			                            $atributos = array_merge($atributos, $atributosGlobales);
			                            echo $this->miFormulario->campoCuadroTexto($atributos);
			                            unset($atributos);
			                            // ----------------FIN CONTROL: Campo de Texto FECHA SOLICITUD--------------------------------------------------------
			                            
			                            
			                            
			                            
			                            
			                            
			            
			            
			                           
			                            // ----------------FIN CONTROL: Campo de Texto FECHA SOLICITUD--------------------------------------------------------
			                            // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
			                            $esteCampo = 'descuentos';
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
			            
			            
			                            $atributos ['valor'] = $resultadoRespuesta[0]['descuentos'];
			            
			                            $tab ++;
			            
			                            // Aplica atributos globales al control
			                            $atributos = array_merge($atributos, $atributosGlobales);
			                            echo $this->miFormulario->campoTextArea($atributos);
			                            unset($atributos);
			            
			                            
			                            
			                            
			                            
			                            
			                            
			            }
			            echo $this->miFormulario->marcoAgrupacion('fin');
			
			
			            $esteCampo = "marcoAnexo";
			            $atributos ['id'] = $esteCampo;
			            $atributos ["estilo"] = "jqueryui";
			            $atributos ['tipoEtiqueta'] = 'inicio';
			            $atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo);
			            echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);
			            // ----------------INICIO CONTROL: DOCUMENTO--------------------------------------------------------
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
			            $atributos ["etiqueta"] = $this->lenguaje->getCadena($esteCampo);
			            // $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
			            // $atributos ["valor"] = $valorCodificado;
			            $atributos = array_merge($atributos, $atributosGlobales);
			            //echo $this->miFormulario->campoCuadroTexto ( $atributos );
			          
			
			            $atributos["id"] = "botones";
			            $atributos["estilo"] = "marcoBotones widget";
			            echo $this->miFormulario->division("inicio", $atributos);
			
			            $enlace = "<br><a href='" .$resultadoRespuesta[0]['soporte_cotizacion'] . "' target='_blank'>";
			            $enlace.="<img src='" . $rutaBloque . "/images/pdf.png' width='35px'><br>Anexo Cotización Detallada ";
			            $enlace.="</a>";
			            echo $enlace;
			            //------------------Fin Division para los botones-------------------------
			            echo $this->miFormulario->division("fin");
			            //FIN enlace boton descargar RUT
			
			            echo $this->miFormulario->marcoAgrupacion('fin');
			
			
			            $esteCampo = 'observaciones';
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
			            $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			            $atributos ['validar'] = 'required,minSize[30]';
			            $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
			            $atributos ['deshabilitado'] = false;
			            $atributos ['tamanno'] = 20;
			            $atributos ['maximoTamanno'] = '';
			            $atributos ['anchoEtiqueta'] = 220;
			            
			            
			            $atributos ['valor'] = $resultadoRespuesta[0]['observaciones'];
			            
			            $tab ++;
			            
			            // Aplica atributos globales al control
			            $atributos = array_merge ( $atributos, $atributosGlobales );
			            echo $this->miFormulario->campoTextArea ( $atributos );
			            unset ( $atributos );
			
			
			        }
			        echo $this->miFormulario->marcoAgrupacion('fin');
						
			
			
			
				

				

			
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

$miSeleccionador = new FormularioRegistro ( $this->lenguaje, $this->miFormulario, $this->sql );

$miSeleccionador->miForm ();
?>