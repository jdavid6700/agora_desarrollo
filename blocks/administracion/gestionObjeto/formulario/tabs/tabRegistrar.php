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
		
		$conexion = "estructura";
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
		
		$esteCampo = "marcoDatos";
		$atributos ['id'] = $esteCampo;
		$atributos ["estilo"] = "jqueryui";
		$atributos ['tipoEtiqueta'] = 'inicio';
		$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
		echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
		
		
		
		$this->cadena_sql = $this->miSql->getCadenaSql ( "listarObjetosRelacionadosXVigencia", 2008 );
		$resultado = $esteRecursoDB->ejecutarAcceso ( $this->cadena_sql, "busqueda" );
		
		$datos = array (//Datos Relacionados ya en el sistema AGORA
				'solicitudes' => $resultado[0][0],
				'vigencia' => 2008
		);
		
		$cadena_sql = $this->miSql->getCadenaSql ( "listaSolicitudNecesidadXVigencia", $datos);
		$resultado = $siCapitalRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
		
		
		//echo $cadena_sql;// SI CAPITAL <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
		//var_dump($resultado);
		
		
		
		if ($resultado) {
			
			
			echo '<table id="tablaObjetos" class="display" cellspacing="0" width="100%"> ';
			
			echo "<thead>
				<tr>
					<th><center>Número Solicitud</center></th>
					<th><center>Vigencia</center></th>
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
					<th><center>Relacionar</center></th>
				</tr>
				</thead>
				<tbody>";
			
			foreach ($resultado as $dato):
			$variableView = "pagina=" . $miPaginaActual; // pendiente la pagina para modificar parametro
			$variableView .= "&opcion=verSolicitud";
			$variableView .= "&idSolicitud=" . $dato['NUM_SOL_ADQ'];
			$variableView .= "&vigencia=" . $dato['VIGENCIA'];
			$variableView = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableView, $directorio );
			$imagenView = 'verPro.png';
				
				
				
			$variableEdit = "pagina=" . $miPaginaActual; // pendiente la pagina para modificar parametro
			$variableEdit .= "&opcion=modificarSolicitud";
			$variableEdit .= "&idSolicitud=" . $dato['NUM_SOL_ADQ'];
			$variableEdit .= "&vigencia=" . $dato['VIGENCIA'];
			$variableEdit = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableEdit, $directorio );
			$imagenEdit = 'addPro.png';
				
				
			if(!isset($dato['NUM_SOL_ADQ'])) $dato['NUM_SOL_ADQ'] = " ";
			if(!isset($dato['VIGENCIA'])) $dato['VIGENCIA'] = " ";
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
						<td><center>" . $dato['DEPENDENCIA'] . "</center></td>
						<td><center>" . $dato['FECHA_SOLICITUD'] . "</center></td>
						<td><center>" . $dato['ORIGEN_SOLICITUD'] . "</center></td>
					    <td><center>" . $dato['DEPENDENCIA_DESTINO'] . "</center></td>
						<td><center>" . $dato['JUSTIFICACION'] . "</center></td>
						<td><center>" . $dato['OBJETO'] . "</center></td>
						<td><center>" . $dato['TIPO_CONTRATACION'] . "</center></td>
						<td><center>" . $dato['PLAZO_EJECUCION'] . "</center></td>
						<td><center>" . $dato['ESTADO'] . "</center></td>
						<td><center>
							<a href='" . $variableView . "'>
								<img src='" . $rutaBloque . "/images/" . $imagenView . "' width='15px'>
							</a>
						</center></td>
						<td><center>
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
			
		
		}
		
		
		
		
		/*
		// ----------------INICIO CONTROL: Campo de Texto OBJETO A CONTRATAR--------------------------------------------------------
		$esteCampo = 'objetoContrato';
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
		$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
		$atributos ['validar'] = 'required, minSize[6],maxSize[255]';
		
		if (isset ( $_REQUEST [$esteCampo] )) {
			$atributos ['valor'] = $_REQUEST [$esteCampo];
		} else {
			$atributos ['valor'] = '';
		}
		$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
		$atributos ['deshabilitado'] = false;
		$atributos ['tamanno'] = 90;
		$atributos ['maximoTamanno'] = '30';
		$atributos ['anchoEtiqueta'] = 200;
		$tab ++;
		
		// Aplica atributos globales al control
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoCuadroTexto ( $atributos );
		unset ( $atributos );
		// ----------------FIN CONTROL: Campo de Texto OBJETO A CONTRATAR--------------------------------------------------------
		
		// ---------------- CONTROL: Lista Ordenador del gasto --------------------------------------------------------
		$esteCampo = "ordenador";
		$atributos ['nombre'] = $esteCampo;
		$atributos ['id'] = $esteCampo;
		$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
		$atributos ["etiquetaObligatorio"] = true;
		$atributos ['tab'] = $tab ++;
		$atributos ['anchoEtiqueta'] = 200;
		$atributos ['evento'] = '';
		if (isset ( $_REQUEST [$esteCampo] )) {
			$atributos ['seleccion'] = $_REQUEST [$esteCampo];
		} else {
			$atributos ['seleccion'] = - 1;
		}
		$atributos ['deshabilitado'] = false;
		$atributos ['columnas'] = 1;
		$atributos ['tamanno'] = 1;
		$atributos ['estilo'] = "jqueryui";
		$atributos ['validar'] = "required";
		$atributos ['limitar'] = false;
		$atributos ['anchoCaja'] = 60;
		$atributos ['miEvento'] = '';
		$atributos ['cadena_sql'] = $cadenaSql = $this->miSql->getCadenaSql ( 'ordenador' );
		$matrizItems = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		$atributos ['matrizItems'] = $matrizItems;
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoCuadroLista ( $atributos );
		unset ( $atributos );
		// ----------------FIN CONTROL: Lista Ordenador del gasto--------------------------------------------------------
		
		// ---------------- CONTROL: Lista DEPENDENCIA --------------------------------------------------------
		$esteCampo = "dependencia";
		$atributos ['nombre'] = $esteCampo;
		$atributos ['id'] = $esteCampo;
		$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
		$atributos ["etiquetaObligatorio"] = true;
		$atributos ['tab'] = $tab ++;
		$atributos ['anchoEtiqueta'] = 200;
		$atributos ['evento'] = '';
		if (isset ( $_REQUEST [$esteCampo] )) {
			$atributos ['seleccion'] = $_REQUEST [$esteCampo];
		} else {
			$atributos ['seleccion'] = - 1;
		}
		$atributos ['deshabilitado'] = false;
		$atributos ['columnas'] = 1;
		$atributos ['tamanno'] = 1;
		$atributos ['estilo'] = "jqueryui";
		$atributos ['validar'] = "required";
		$atributos ['limitar'] = false;
		$atributos ['anchoCaja'] = 60;
		$atributos ['miEvento'] = '';
		$atributos ['cadena_sql'] = $cadenaSql = $this->miSql->getCadenaSql ( 'dependencia' );
		$matrizItems = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		$atributos ['matrizItems'] = $matrizItems;
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoCuadroLista ( $atributos );
		unset ( $atributos );
		// ----------------FIN CONTROL: Lista DEPENDENCIA--------------------------------------------------------
		
		// ----------------INICIO CONTROL: Campo de Texto NUMERO CDP--------------------------------------------------------
		$esteCampo = 'numCDP';
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
		$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
		$atributos ['validar'] = 'required, minSize[2],maxSize[10],custom[onlyNumberSp]';
		
		if (isset ( $_REQUEST [$esteCampo] )) {
			$atributos ['valor'] = $_REQUEST [$esteCampo];
		} else {
			$atributos ['valor'] = '';
		}
		$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
		$atributos ['deshabilitado'] = false;
		$atributos ['tamanno'] = 15;
		$atributos ['maximoTamanno'] = '30';
		$atributos ['anchoEtiqueta'] = 200;
		$tab ++;
		
		// Aplica atributos globales al control
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoCuadroTexto ( $atributos );
		unset ( $atributos );
		// ----------------FIN CONTROL: Campo de Texto NUMERO CDP--------------------------------------------------------
		
		// ---------------- CONTROL: Lista UNIDAD --------------------------------------------------------
		$esteCampo = "unidad";
		$atributos ['nombre'] = $esteCampo;
		$atributos ['id'] = $esteCampo;
		$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
		$atributos ["etiquetaObligatorio"] = true;
		$atributos ['tab'] = $tab ++;
		$atributos ['anchoEtiqueta'] = 200;
		$atributos ['evento'] = '';
		if (isset ( $_REQUEST [$esteCampo] )) {
			$atributos ['seleccion'] = $_REQUEST [$esteCampo];
		} else {
			$atributos ['seleccion'] = - 1;
		}
		$atributos ['deshabilitado'] = false;
		$atributos ['columnas'] = 3;
		$atributos ['tamanno'] = 1;
		$atributos ['estilo'] = "jqueryui";
		$atributos ['validar'] = "";
		$atributos ['limitar'] = false;
		$atributos ['anchoCaja'] = 60;
		$atributos ['miEvento'] = '';
		$atributos ['cadena_sql'] = $cadenaSql = $this->miSql->getCadenaSql ( 'unidad' );
		$matrizItems = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		$atributos ['matrizItems'] = $matrizItems;
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoCuadroLista ( $atributos );
		unset ( $atributos );
		// ----------------FIN CONTROL: Lista UNIDAD--------------------------------------------------------
		
		// ----------------INICIO CONTROL: Campo de Texto CANTIDAD--------------------------------------------------------
		$esteCampo = 'cantidad';
		$atributos ['id'] = $esteCampo;
		$atributos ['nombre'] = $esteCampo;
		$atributos ['tipo'] = 'text';
		$atributos ['estilo'] = 'jqueryui';
		$atributos ['marco'] = true;
		$atributos ['estiloMarco'] = '';
		$atributos ["etiquetaObligatorio"] = true;
		$atributos ['columnas'] = 3;
		$atributos ['dobleLinea'] = 0;
		$atributos ['tabIndex'] = $tab;
		$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
		$atributos ['validar'] = 'required, minSize[1],maxSize[15],custom[onlyNumberSp]';
		
		if (isset ( $_REQUEST [$esteCampo] )) {
			$atributos ['valor'] = $_REQUEST [$esteCampo];
		} else {
			$atributos ['valor'] = '';
		}
		$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
		$atributos ['deshabilitado'] = false;
		$atributos ['tamanno'] = 32;
		$atributos ['maximoTamanno'] = '30';
		$atributos ['anchoEtiqueta'] = 200;
		$tab ++;
		
		// Aplica atributos globales al control
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoCuadroTexto ( $atributos );
		unset ( $atributos );
		// ----------------FIN CONTROL: Campo de Texto CANTIDAD--------------------------------------------------------
		
		// ----------------INICIO CONTROL: Campo de Texto CANTIDAD--------------------------------------------------------
		$esteCampo = 'cotizaciones';
		$atributos ['id'] = $esteCampo;
		$atributos ['nombre'] = $esteCampo;
		$atributos ['tipo'] = 'text';
		$atributos ['estilo'] = 'jqueryui';
		$atributos ['marco'] = true;
		$atributos ['estiloMarco'] = '';
		$atributos ["etiquetaObligatorio"] = true;
		$atributos ['columnas'] = 3;
		$atributos ['dobleLinea'] = 0;
		$atributos ['tabIndex'] = $tab;
		$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
		$atributos ['validar'] = 'required, minSize[1],maxSize[1],custom[onlyNumberSp]';
		
		if (isset ( $_REQUEST [$esteCampo] )) {
			$atributos ['valor'] = $_REQUEST [$esteCampo];
		} else {
			$atributos ['valor'] = '';
		}
		$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
		$atributos ['deshabilitado'] = false;
		$atributos ['tamanno'] = 32;
		$atributos ['maximoTamanno'] = '30';
		$atributos ['anchoEtiqueta'] = 200;
		$tab ++;
		
		// Aplica atributos globales al control
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoCuadroTexto ( $atributos );
		unset ( $atributos );
		// ----------------FIN CONTROL: Campo de Texto CANTIDAD--------------------------------------------------------
		
		*/
		
		echo $this->miFormulario->marcoAgrupacion ( 'fin' );
		
		/*
		
		$esteCampo = "marcoCIIU";
		$atributos ['id'] = $esteCampo;
		$atributos ["estilo"] = "jqueryui";
		$atributos ['tipoEtiqueta'] = 'inicio';
		$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
		echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
		
		// ---------------- CONTROL: Lista division CIIU--------------------------------------------------------
		$esteCampo = "divisionCIIU";
		$atributos ['nombre'] = $esteCampo;
		$atributos ['id'] = $esteCampo;
		$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
		$atributos ["etiquetaObligatorio"] = true;
		$atributos ['tab'] = $tab ++;
		$atributos ['anchoEtiqueta'] = 200;
		$atributos ['evento'] = '';
		if (isset ( $_REQUEST [$esteCampo] )) {
			$atributos ['seleccion'] = $_REQUEST [$esteCampo];
		} else {
			$atributos ['seleccion'] = - 1;
		}
		$atributos ['deshabilitado'] = false;
		$atributos ['columnas'] = 1;
		$atributos ['tamanno'] = 1;
		$atributos ['ajax_function'] = "";
		$atributos ['ajax_control'] = $esteCampo;
		$atributos ['estilo'] = "jqueryui";
		$atributos ['validar'] = "required";
		$atributos ['limitar'] = false;
		$atributos ['anchoCaja'] = 60;
		$atributos ['miEvento'] = '';
		$atributos ['cadena_sql'] = $cadenaSql = $this->miSql->getCadenaSql ( 'ciiuDivision' );
		$matrizItems = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		$atributos ['matrizItems'] = $matrizItems;
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoCuadroLista ( $atributos );
		unset ( $atributos );
		// ----------------FIN CONTROL: Lista division CIIU--------------------------------------------------------
		
		// ---------------- CONTROL: Lista grupo CIIU--------------------------------------------------------
		$esteCampo = "grupoCIIU";
		$atributos ['nombre'] = $esteCampo;
		$atributos ['id'] = $esteCampo;
		$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
		$atributos ["etiquetaObligatorio"] = true;
		$atributos ['tab'] = $tab ++;
		$atributos ['anchoEtiqueta'] = 200;
		$atributos ['evento'] = '';
		if (isset ( $_REQUEST [$esteCampo] )) {
			$atributos ['seleccion'] = $_REQUEST [$esteCampo];
		} else {
			$atributos ['seleccion'] = - 1;
		}
		$atributos ['deshabilitado'] = false;
		$atributos ['columnas'] = 1;
		$atributos ['tamanno'] = 1;
		$atributos ['ajax_function'] = "";
		$atributos ['ajax_control'] = $esteCampo;
		$atributos ['estilo'] = "jqueryui";
		$atributos ['validar'] = "required";
		$atributos ['limitar'] = false;
		$atributos ['anchoCaja'] = 60;
		$atributos ['miEvento'] = '';
		$atributos ['cadena_sql'] = $cadenaSql = $this->miSql->getCadenaSql ( 'ciiuGrupo', '01' );
		$matrizItems = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		$atributos ['matrizItems'] = $matrizItems;
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoCuadroLista ( $atributos );
		unset ( $atributos );
		// ----------------FIN CONTROL: Lista grupo CIIU--------------------------------------------------------
		
		// ---------------- CONTROL: Lista clase CIIU--------------------------------------------------------
		$esteCampo = "claseCIIU";
		$atributos ['nombre'] = $esteCampo;
		$atributos ['id'] = $esteCampo;
		$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
		$atributos ["etiquetaObligatorio"] = true;
		$atributos ['tab'] = $tab ++;
		$atributos ['anchoEtiqueta'] = 200;
		$atributos ['evento'] = '';
		if (isset ( $_REQUEST [$esteCampo] )) {
			$atributos ['seleccion'] = $_REQUEST [$esteCampo];
		} else {
			$atributos ['seleccion'] = - 1;
		}
		$atributos ['deshabilitado'] = false;
		$atributos ['columnas'] = 1;
		$atributos ['tamanno'] = 1;
		$atributos ['ajax_function'] = "";
		$atributos ['ajax_control'] = $esteCampo;
		$atributos ['estilo'] = "jqueryui";
		$atributos ['validar'] = "required";
		$atributos ['limitar'] = false;
		$atributos ['anchoCaja'] = 60;
		$atributos ['miEvento'] = '';
		$atributos ['cadena_sql'] = $cadenaSql = $this->miSql->getCadenaSql ( 'ciiuClase', '011' );
		$matrizItems = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		$atributos ['matrizItems'] = $matrizItems;
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoCuadroLista ( $atributos );
		unset ( $atributos );
		// ----------------FIN CONTROL: Lista clase CIIU--------------------------------------------------------
		
		echo $this->miFormulario->marcoAgrupacion ( 'fin' );
		
		// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
		$esteCampo = 'descripcion';
		$atributos ['id'] = $esteCampo;
		$atributos ['nombre'] = $esteCampo;
		$atributos ['tipo'] = 'text';
		$atributos ['estilo'] = 'jqueryui';
		$atributos ['marco'] = true;
		$atributos ['estiloMarco'] = '';
		$atributos ["etiquetaObligatorio"] = true;
		$atributos ['columnas'] = 80;
		$atributos ['filas'] = 2;
		$atributos ['dobleLinea'] = 0;
		$atributos ['tabIndex'] = $tab;
		$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
		$atributos ['validar'] = 'required';
		$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
		$atributos ['deshabilitado'] = false;
		$atributos ['tamanno'] = 20;
		$atributos ['maximoTamanno'] = '';
		$atributos ['anchoEtiqueta'] = 220;
		if (isset ( $_REQUEST [$esteCampo] )) {
			$atributos ['valor'] = $_REQUEST [$esteCampo];
		} else {
			$atributos ['valor'] = '';
		}
		$tab ++;
		
		// Aplica atributos globales al control
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoTextArea ( $atributos );
		unset ( $atributos );
		
		// ---------------- FIN CONTROL: Cuadro de Texto --------------------------------------------------------
		
		// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
		$esteCampo = 'caracteristicas';
		$atributos ['id'] = $esteCampo;
		$atributos ['nombre'] = $esteCampo;
		$atributos ['tipo'] = 'text';
		$atributos ['estilo'] = 'jqueryui';
		$atributos ['marco'] = true;
		$atributos ['estiloMarco'] = '';
		$atributos ["etiquetaObligatorio"] = true;
		$atributos ['columnas'] = 80;
		$atributos ['filas'] = 2;
		$atributos ['dobleLinea'] = 0;
		$atributos ['tabIndex'] = $tab;
		$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
		$atributos ['validar'] = 'required';
		$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
		$atributos ['deshabilitado'] = false;
		$atributos ['tamanno'] = 20;
		$atributos ['maximoTamanno'] = '';
		$atributos ['anchoEtiqueta'] = 220;
		if (isset ( $_REQUEST [$esteCampo] )) {
			$atributos ['valor'] = $_REQUEST [$esteCampo];
		} else {
			$atributos ['valor'] = '';
		}
		$tab ++;
		
		// Aplica atributos globales al control
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoTextArea ( $atributos );
		unset ( $atributos );
		
		*/
		
		// ------------------Division para los botones-------------------------
		$atributos ["id"] = "botones";
		$atributos ["estilo"] = "marcoBotones";
		echo $this->miFormulario->division ( "inicio", $atributos );
		{
			// -----------------CONTROL: Botón ----------------------------------------------------------------
			$esteCampo = 'botonRegistrar';
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
			//echo $this->miFormulario->campoBoton ( $atributos );
			
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
				
				$valorCodificado  = "action=" . $esteBloque ["nombre"];
				$valorCodificado .= "&pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
				$valorCodificado .= "&bloque=" . $esteBloque ['nombre'];
				$valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
				$valorCodificado .= "&opcion=registrar";
				
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
