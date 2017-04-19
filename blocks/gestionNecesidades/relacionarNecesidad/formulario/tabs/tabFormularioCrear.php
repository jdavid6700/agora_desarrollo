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

		
		$datos = array (
				'idObjeto' => $_REQUEST['idObjeto']
		);
		
		
		$cadenaSql = $this->miSql->getCadenaSql ( 'infoCotizacionCast', $datos['idObjeto'] );
		$solicitudCotizacionCast = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
		$cadenaSql = $this->miSql->getCadenaSql ( 'infoCotizacion', $datos['idObjeto'] );
		$solicitudCotizacion = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
		$cadenaSql = $this->miSql->getCadenaSql ( 'buscarUsuario', $solicitudCotizacion[0]['responsable'] );
		$resultadoUsuario = $frameworkRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
                
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
		echo "<span class='textoElegante textoGrande textoGris'><b>". $_REQUEST['idObjeto']. " - ". $solicitudCotizacion[0]['vigencia'] . " - " . $solicitudCotizacion[0]['unidad_ejecutora']. "</b></span></br>";
		echo "<br>";
		echo "<span class='textoElegante textoEnorme textoAzul'>Fecha de Apertura : </span>";
		echo "<span class='textoElegante textoEnorme textoGris'><b>". $this->cambiafecha_format($solicitudCotizacionCast[0]['fecha_apertura']) . "</b></span></br>";
		echo "<br>";
		echo "<span class='textoElegante textoEnorme textoAzul'>Fecha de Cierre : </span>";
		echo "<span class='textoElegante textoEnorme textoGris'><b>". $this->cambiafecha_format($solicitudCotizacionCast[0]['fecha_cierre']). "</b></span></br>";
		echo "<br>";
		echo "<span class='textoElegante textoEnorme textoAzul'>Dependencia : </span>";
		echo "<span class='textoElegante textoGrande textoGris'><b>". $solicitudCotizacionCast[0]['dependencia']. "</b></span></br>";
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
			$atributos ["mensaje"] = $this->lenguaje->getCadena ( $esteCampo );
			
			echo $this->miFormulario->cuadroMensaje ( $atributos );
			unset ( $atributos );
			// -------------FIN Control Formulario----------------------
			// ------------------FIN Division para los botones-------------------------
			echo $this->miFormulario->division ( "fin" );

			
			
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
				//var_dump($resultadoProveedor);
				?>
<table id="tablaPersonas"
	class="display" cellspacing="0" width="100%">
	<thead >
		<tr>
			<th align="center"><strong>Documento</strong></th>
			<th align="center"><strong>Tipo Persona</strong></th>
			<th align="center"><strong>Nombre</strong></th>
			<th align="center"><strong>Correo</strong></th>
		</tr>
	</thead>	
	
	<tbody>
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
					echo "<td align='center'>" . $dato ['tipopersona'] . "</td>";
					echo "<td align='center'>" . $dato ['nom_proveedor'] . "</td>";
					echo "<td align='left'>" . $dato ['correo'] . "</td>";
					echo "</tr>";
					
					array_push ( $proveedores, $dato ['id_proveedor'] );
				endforeach
				;
				?>
				</tbody>
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
				$esteCampo = 'botonProcesar';
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