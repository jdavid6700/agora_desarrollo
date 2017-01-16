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
	function miForm() {

		/**
		 * IMPORTANTE: Este formulario está utilizando jquery.
		 * Por tanto en el archivo ready.php se delaran algunas funciones js
		 * que lo complementan.
		 */
		// Rescatar los datos de este bloque
		$conexion = "agora";
		$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
		
		$conexion = "sicapital";
		$siCapitalRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
		
		
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
		$atributos ['marco'] = true;
		$tab = 1;
		// ---------------- FIN SECCION: de Parámetros Generales del Formulario ----------------------------
		// ----------------INICIAR EL FORMULARIO ------------------------------------------------------------
		$atributos ['tipoEtiqueta'] = 'inicio';
		echo $this->miFormulario->formulario ( $atributos );
		
		
		
		
		
		if(isset($_REQUEST['tipoNecesidad'])){
		
			if($_REQUEST['tipoNecesidad'] == "SERVICIO"){
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
		
		}else{
		
			$datosSolicitudNecesidad = array (
					'idSolicitud' => $_REQUEST['idSolicitud'],
					'vigencia' => $_REQUEST['vigencia'],
					'unidadEjecutora' => $_REQUEST['unidadEjecutora']
			);
		
			$cadena_sql = $this->miSql->getCadenaSql ( "informacionSolicitudAgora", $datosSolicitudNecesidad);
			$resultadoNecesidadRelacionada = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
			
			if($resultadoNecesidadRelacionada[0]['tipo_necesidad'] == "SERVICIO"){
				$marcoTipo = "marcoProveedoresConv";
				$tipoMarco = "marcoObjetoConv";
				$tipoSolicitud = $resultadoNecesidadRelacionada[0]['tipo_necesidad'];
				$service = true;
			}else{
				$marcoTipo = "marcoProveedores";
				$tipoMarco = "marcoObjeto";
				$tipoSolicitud = $resultadoNecesidadRelacionada[0]['tipo_necesidad'];
				$service = false;
			}
			
			
			$cadena_sql = $this->miSql->getCadenaSql ( "consultarNucleoBasico", $resultadoNecesidadRelacionada[0]['id_objeto']);
			$resultadoNBCRel = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );

			$_REQUEST ['objetoNBC'] = $resultadoNBCRel[0]['id_nucleo'];
		
		}
		

		if(isset($_REQUEST['idSolicitud']) && isset($_REQUEST['vigencia']) && isset($_REQUEST['unidadEjecutora'])){
			
			
			$datos = array (
					'idSolicitud' => $_REQUEST['idSolicitud'],
					'vigencia' => $_REQUEST['vigencia'],
					'unidadEjecutora' => $_REQUEST['unidadEjecutora']
			);
			
			$cadenaSql = $this->miSql->getCadenaSql ( 'informacionSolicitudAgora', $datos );
			$objeto = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
			
			$_REQUEST["idObjeto"] = $objeto[0]['id_objeto'];
			$_REQUEST['numCotizaciones'] = $objeto[0]['numero_cotizaciones'];
				
		}
		
		//DATOS DEL OBJETO A CONTRATAR SELECCIONADO
		$cadenaSql = $this->miSql->getCadenaSql ( 'objetoContratar', $_REQUEST["idObjeto"] );
		$objetoEspecifico = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
		$datos = array (
				'idSolicitud' => $objetoEspecifico[0]['numero_solicitud'],
				'vigencia' => $objetoEspecifico[0]['vigencia'],
				'unidadEjecutora' => $objetoEspecifico[0]['unidad_ejecutora']
		);
		
		
		$cadenaSql = $this->miSql->getCadenaSql ( 'listaSolicitudNecesidadXNumSolicitud', $datos );
		$solicitudNecesidad = $siCapitalRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
                
		$esteCampo = $tipoMarco;
		$atributos ['id'] = $esteCampo;
		$atributos ["estilo"] = "jqueryui";
		$atributos ['tipoEtiqueta'] = 'inicio';
		$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
		echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );                
                
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
        		echo $dato['id_subclase'] . ' - ' . $dato['nombre'] . "</b><br>";
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
        	echo $resultadoNBC[0]['id_nucleo'] . ' - ' . $resultadoNBC[0]['nombre'] . "</b><br>";
        	
        	
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
			$esteCampo = "mensajeNoHayProveedores";
			$atributos ["id"] = $esteCampo; // Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
			$atributos ["etiqueta"] = "";
			$atributos ["estilo"] = "centrar";
			$atributos ["tipo"] = 'error';
			$atributos ["mensaje"] = $this->lenguaje->getCadena ( $esteCampo ) . $idActividad . ' - ' . $objetoEspecifico[0]['actividad'];
			
			echo $this->miFormulario->cuadroMensaje ( $atributos );
			unset ( $atributos );
			// -------------FIN Control Formulario----------------------
			// ------------------FIN Division para los botones-------------------------
			echo $this->miFormulario->division ( "fin" );
			unset ( $atributos );
		} else {

			// LISTA DE PROVEEDORES CON MEJOR CLASIFICACION
			// ------- FILTRAR POR ACTIVIDAD ECONOMICA
			
			if($service){
				$datos = array (
						'actividadEconomica' => $actividades,
						'objetoNBC' => $_REQUEST ['objetoNBC'],
						'numCotizaciones' => $_REQUEST ['numCotizaciones']
				);
				
				
				$cadenaSql = $this->miSql->getCadenaSql ( 'proveedoresByClasificacionConv', $datos );
				$resultadoProveedor = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
				
			}else{
				$datos = array (
						'actividadEconomica' => $actividades,
						'numCotizaciones' => $_REQUEST ['numCotizaciones']
				);
				
				
				// -------- Limite de registros
				// --------- evaluacion mayor a 45
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
				$atributos ["mensaje"] = $this->lenguaje->getCadena ( $esteCampo );
				
				echo $this->miFormulario->cuadroMensaje ( $atributos );
				unset ( $atributos );
				// -------------FIN Control Formulario----------------------
				// ------------------FIN Division para los botones-------------------------
				echo $this->miFormulario->division ( "fin" );
				unset ( $atributos );
			} else {
				// ---------------INICIO TABLA CON LISTA DE PROVEEDORES---------------------
				$esteCampo = $marcoTipo;
				$atributos ['id'] = $esteCampo;
				$atributos ["estilo"] = "jqueryui";
				$atributos ['tipoEtiqueta'] = 'inicio';
				$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
				echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
				
				?>
<table
	class="table table-bordered table-striped table-hover table-condensed">
	<tr class="info">
		<td align="center"><strong>Documento</strong></td>
		<td align="center"><strong>Proveedor</strong></td>
		<td align="center"><strong>Puntaje Evaluaciòn</strong></td>
		<td align="center"><strong>Clasificaciòn</strong></td>
	</tr>	
			<?php
				
				$proveedores = array ();
				foreach ( $resultadoProveedor as $dato ) :
					
					if($dato ['clasificacion_evaluacion'] == null){
						$clasificacion = 'SIN CLASIFICACIÓN';
					}else{
						$clasificacion = $dato ['clasificacion_evaluacion'];
					}
				
					echo "<tr>";
					echo "<td align='center'>" . $dato ['num_documento'] . "</td>";
					echo "<td align='center'>" . $dato ['nom_proveedor'] . "</td>";
					echo "<td align='right'>" . $dato ['puntaje_evaluacion'] . "</td>";
					echo "<td align='right'>" . $clasificacion . "</td>";
					echo "</tr>";
					
					array_push ( $proveedores, $dato ['id_proveedor'] );
				endforeach
				;
				?>
			</table>
<?php
				
				echo $this->miFormulario->marcoAgrupacion ( 'fin' );
				
				$esteCampo = 'idProveedor';
				$atributos ["id"] = $esteCampo; // No cambiar este nombre
				$atributos ["tipo"] = "hidden";
				$atributos ['estilo'] = '';
				$atributos ["obligatorio"] = false;
				$atributos ['marco'] = true;
				$atributos ["etiqueta"] = "";
				$atributos ['valor'] = serialize ( $proveedores );
				
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroTexto ( $atributos );
				unset ( $atributos );
				
				// ---------------FIN TABLA CON LISTA DE PROVEEDORES---------------------
				
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
				
				// ------------------Division para los botones-------------------------
				$atributos ["id"] = "botones";
				$atributos ["estilo"] = "marcoBotones";
				echo $this->miFormulario->division ( "inicio", $atributos );
				
				// -----------------CONTROL: Botón ----------------------------------------------------------------
				$esteCampo = 'botonAceptar';
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
				$atributos ['nombreFormulario'] = $esteBloque ['nombre'];
				$tab ++;
				
				// Aplica atributos globales al control
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoBoton ( $atributos );
				unset ( $atributos );
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
		$atributos ['marco'] = true;
		$atributos ['tipoEtiqueta'] = 'fin';
		echo $this->miFormulario->formulario ( $atributos );
		
		return true;
                
                }
            }
	}
}

$miSeleccionador = new registrarForm ( $this->lenguaje, $this->miFormulario, $this->sql );

$miSeleccionador->miForm ();
?>