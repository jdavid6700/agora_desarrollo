<?php
namespace administracion\evaluacionProveedor\formulario\tabs;

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
		
		//*************************************************************************** DBMS *******************************
		//****************************************************************************************************************
		
		$conexion = 'estructura';
		$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
		
		$conexion = 'sicapital';
		$siCapitalRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
		
		$conexion = 'centralUD';
		$centralUDRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
		
		$conexion = 'argo_contratos';
		$argoRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
		
		$conexion = 'core_central';
		$coreRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
		
		$conexion = 'framework';
		$frameworkRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
		
		//*************************************************************************** DBMS *******************************
		//****************************************************************************************************************
		
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
		
		
		//******************************************************************************** REGRESAR ****************************************
		//**********************************************************************************************************************************
		// ------------------Division para los botones-------------------------
		$atributos ["id"] = "botones";
		$atributos ["estilo"] = "marcoBotones";
		echo $this->miFormulario->division ( "inicio", $atributos );
		{
			
			$variable = "pagina=" . $miPaginaActual;
			$variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variable, $directorio );
			
			echo '<div class="widget">';
			
			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
			$esteCampo = 'botonRegresar';
			$atributos ['id'] = $esteCampo;
			$atributos ['enlace'] = $variable;
			$atributos ['tabIndex'] = 1;
			$atributos ['estilo'] = 'ui-button ui-widget ui-corner-all';
			$atributos ['enlaceTexto'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ['ancho'] = '10%';
			$atributos ['alto'] = '10%';
			$atributos ['redirLugar'] = true;
			echo $this->miFormulario->enlace ( $atributos );
			
			echo '</div>';
			
			
		}
		// ------------------Fin Division para los botones-------------------------
		echo $this->miFormulario->division ( "fin" );
		unset ( $atributos );
		//**********************************************************************************************************************************
		//**********************************************************************************************************************************
		//******************************************************************************** REGRESAR ****************************************
		
		$datosContrato = array (
				'numeroContrato' => $_REQUEST['idSolicitud'],
				'vigenciaContrato' => $_REQUEST['vigencia'],
				'unidadEjecutora' => $_REQUEST['unidadEjecutora']
		);
		
		
		//*********************************************************************************************************************************
// 		$cadena_sql = $this->miSql->getCadenaSql ( "estadoContratoAgora", $datosContrato);
// 		$resultado = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );//Verifica si ya se relaciono el Contrato, al ser Evaluado
		
// 		if($resultado) {
// 			$estadoSolicitud = $resultado[0]['estado'];
				
				
// 			$cadena_sql = $this->miSql->getCadenaSql ( "consultarActaInicio", $datosContrato);
// 			$resultadoActaInicio = $argoRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
				
// 			$fechaInicio = date("d/m/Y", strtotime($resultadoActaInicio[0]['fecha_inicio']));
// 			$fechaFin = date("d/m/Y", strtotime($resultadoActaInicio[0]['fecha_fin']));
				
// 			$cadena_sql = $this->miSql->getCadenaSql ( "consultarNovedadesContrato", $datosContrato);
// 			$resultadoNovedades = $argoRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
				
// 		}
		//***************CONTRATOS RELACIONADOS******************************************************************************************
		
		
		
		
		$cadena_sql = $this->miSql->getCadenaSql ( "consultarContratosARGOByNum", $datosContrato);
		$resultado = $argoRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
		
		//var_dump($resultado);
		
		// ----------------INICIO CONTROL: Campo ESTADO RELACION-------------------------------------------------------
		$esteCampo = 'estadoSolicitudRelacionada';
		$atributos ['id'] = $esteCampo;
		$atributos ['nombre'] = $esteCampo;
		$atributos ['tipo'] = 'hidden';
		
		if (isset ( $estadoSolicitud )) {
			$atributos ['valor'] = $estadoSolicitud;
		} else {
			$atributos ['valor'] = '';
		}
		$tab ++;
		
		// Aplica atributos globales al control
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoCuadroTexto ( $atributos );
		unset ( $atributos );
		// ----------------FIN CONTROL: Campo de Texto --------------------------------------------------------
		
		
		
		//echo $cadena_sql;// SI CAPITAL <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
		
		$esteCampo = "marcoDatosContrato";
		$atributos ['id'] = $esteCampo;
		$atributos ["estilo"] = "jqueryui";
		$atributos ['tipoEtiqueta'] = 'inicio';
		$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
		echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
		
		
		// ----------------INICIO CONTROL: Campo de Texto NUM SOLICITUD--------------------------------------------------------
		$esteCampo = 'numContrato';
		$atributos ['id'] = $esteCampo;
		$atributos ['nombre'] = $esteCampo;
		$atributos ['tipo'] = 'text';
		$atributos ['estilo'] = 'jqueryui';
		$atributos ['marco'] = true;
		$atributos ['estiloMarco'] = '';
		$atributos ["etiquetaObligatorio"] = true;
		$atributos ['columnas'] = 2;
		$atributos ['dobleLinea'] = 0;
		$atributos ['tabIndex'] = $tab;
		$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
		$atributos ['validar'] = '';
		
		$atributos ['valor'] = $resultado[0]['numero_contrato'];
		
		$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
		$atributos ['deshabilitado'] = true;
		$atributos ['tamanno'] = 10;
		$atributos ['maximoTamanno'] = '10';
		$atributos ['anchoEtiqueta'] = 200;
		$tab ++;
		
		// Aplica atributos globales al control
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoCuadroTexto ( $atributos );
		unset ( $atributos );
		// ----------------FIN CONTROL: Campo de Texto NUM SOLICITUD--------------------------------------------------------
		
		// ----------------INICIO CONTROL: Campo de Texto VIGENCIA--------------------------------------------------------
		$esteCampo = 'vigencia';
		$atributos ['id'] = $esteCampo;
		$atributos ['nombre'] = $esteCampo;
		$atributos ['tipo'] = 'text';
		$atributos ['estilo'] = 'jqueryui';
		$atributos ['marco'] = true;
		$atributos ['estiloMarco'] = '';
		$atributos ["etiquetaObligatorio"] = true;
		$atributos ['columnas'] = 2;
		$atributos ['dobleLinea'] = 0;
		$atributos ['tabIndex'] = $tab;
		$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
		$atributos ['validar'] = '';
		
		$atributos ['valor'] = $resultado[0]['vigencia'];
		
		$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
		$atributos ['deshabilitado'] = true;
		$atributos ['tamanno'] = 30;
		$atributos ['maximoTamanno'] = '10';
		$atributos ['anchoEtiqueta'] = 200;
		$tab ++;
		
		// Aplica atributos globales al control
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoCuadroTexto ( $atributos );
		unset ( $atributos );
		// ----------------FIN CONTROL: Campo de Texto VIGENCIA--------------------------------------------------------
		
		// ----------------INICIO CONTROL: Campo de Texto VIGENCIA--------------------------------------------------------
		$esteCampo = 'unidadEjecutora';
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
		$atributos ['validar'] = '';
		
		if($resultado[0]['unidad_ejecutora'] == 1){
			$unidad = "1 - RECTORÍA - ADMINISTRATIVA";
		}else if($resultado[0]['unidad_ejecutora'] == 2){
			$unidad = "2 - IDEXUD";
		}
		
		$atributos ['valor'] = $unidad;
		
		$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
		$atributos ['deshabilitado'] = true;
		$atributos ['tamanno'] = 30;
		$atributos ['maximoTamanno'] = '10';
		$atributos ['anchoEtiqueta'] = 200;
		$tab ++;
		
		// Aplica atributos globales al control
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoCuadroTexto ( $atributos );
		unset ( $atributos );
		// ----------------FIN CONTROL: Campo de Texto VIGENCIA--------------------------------------------------------
		
		// ----------------INICIO CONTROL: Campo de Texto DEPENDENCIA--------------------------------------------------------
		$esteCampo = 'numSolicitud';
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
		$atributos ['validar'] = '';
		
		$atributos ['valor'] = $resultado[0]['numero_solicitud_necesidad'];
		
		$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
		$atributos ['deshabilitado'] = true;
		$atributos ['tamanno'] = 10;
		$atributos ['maximoTamanno'] = '10';
		$atributos ['anchoEtiqueta'] = 200;
		$tab ++;
		
		// Aplica atributos globales al control
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoCuadroTexto ( $atributos );
		unset ( $atributos );
		// ----------------FIN CONTROL: Campo de Texto DEPENDENCIA--------------------------------------------------------
		
		// ----------------INICIO CONTROL: Campo de Texto FUNCIONARIO--------------------------------------------------------
		$esteCampo = 'numCDP';
		$atributos ['id'] = $esteCampo;
		$atributos ['nombre'] = $esteCampo;
		$atributos ['tipo'] = 'text';
		$atributos ['estilo'] = 'jqueryui';
		$atributos ['marco'] = true;
		$atributos ['estiloMarco'] = '';
		$atributos ["etiquetaObligatorio"] = true;
		$atributos ['columnas'] = 2;
		$atributos ['dobleLinea'] = 0;
		$atributos ['tabIndex'] = $tab;
		$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
		$atributos ['validar'] = '';
		
		$atributos ['valor'] = $resultado[0]['numero_cdp'];
		
		$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
		$atributos ['deshabilitado'] = true;
		$atributos ['tamanno'] = 40;
		$atributos ['maximoTamanno'] = '10';
		$atributos ['anchoEtiqueta'] = 200;
		$tab ++;
		
		// Aplica atributos globales al control
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoCuadroTexto ( $atributos );
		unset ( $atributos );
		// ----------------FIN CONTROL: Campo de Texto FUNCIONARIO--------------------------------------------------------
		
		// ----------------INICIO CONTROL: Campo de Texto FUNCIONARIO--------------------------------------------------------
		$esteCampo = 'fechaNumCDP';
		$atributos ['id'] = $esteCampo;
		$atributos ['nombre'] = $esteCampo;
		$atributos ['tipo'] = 'text';
		$atributos ['estilo'] = 'jqueryui';
		$atributos ['marco'] = true;
		$atributos ['estiloMarco'] = '';
		$atributos ["etiquetaObligatorio"] = true;
		$atributos ['columnas'] = 2;
		$atributos ['dobleLinea'] = 0;
		$atributos ['tabIndex'] = $tab;
		$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
		$atributos ['validar'] = '';
		
		$datosCDP = array (
				'numeroDisponibilidad' => $resultado[0]['numero_cdp'],
				'vigencia' => $_REQUEST['vigencia'],
				'unidadEjecutora' => $resultado[0]['unidad_ejecutora']
		);
		
		$cadena_sql = $this->miSql->getCadenaSql ( "informacionCDP", $datosCDP);
		$resultadoCDP = $siCapitalRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
		
		$atributos ['valor'] = date("d/m/Y", strtotime($resultadoCDP[0]['FECHA_REGISTRO']));
		
		$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
		$atributos ['deshabilitado'] = true;
		$atributos ['tamanno'] = 40;
		$atributos ['maximoTamanno'] = '10';
		$atributos ['anchoEtiqueta'] = 200;
		$tab ++;
		
		// Aplica atributos globales al control
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoCuadroTexto ( $atributos );
		unset ( $atributos );
		// ----------------FIN CONTROL: Campo de Texto FUNCIONARIO--------------------------------------------------------
		
		// ----------------INICIO CONTROL: Campo de Texto FUNCIONARIO--------------------------------------------------------
		$esteCampo = 'numRP';
		$atributos ['id'] = $esteCampo;
		$atributos ['nombre'] = $esteCampo;
		$atributos ['tipo'] = 'text';
		$atributos ['estilo'] = 'jqueryui';
		$atributos ['marco'] = true;
		$atributos ['estiloMarco'] = '';
		$atributos ["etiquetaObligatorio"] = true;
		$atributos ['columnas'] = 2;
		$atributos ['dobleLinea'] = 0;
		$atributos ['tabIndex'] = $tab;
		$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
		$atributos ['validar'] = '';
		
		if(isset($resultado[0]['numero_rp'])){	
			$atributos ['valor'] = $resultado[0]['numero_rp'];
		}else{
			$atributos ['valor'] = 'SIN RELACIONAR';
		}
		
		$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
		$atributos ['deshabilitado'] = true;
		$atributos ['tamanno'] = 40;
		$atributos ['maximoTamanno'] = '10';
		$atributos ['anchoEtiqueta'] = 200;
		$tab ++;
		
		// Aplica atributos globales al control
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoCuadroTexto ( $atributos );
		unset ( $atributos );
		// ----------------FIN CONTROL: Campo de Texto FUNCIONARIO--------------------------------------------------------
		
		// ----------------INICIO CONTROL: Campo de Texto FUNCIONARIO--------------------------------------------------------
		$esteCampo = 'fechaNumRP';
		$atributos ['id'] = $esteCampo;
		$atributos ['nombre'] = $esteCampo;
		$atributos ['tipo'] = 'text';
		$atributos ['estilo'] = 'jqueryui';
		$atributos ['marco'] = true;
		$atributos ['estiloMarco'] = '';
		$atributos ["etiquetaObligatorio"] = true;
		$atributos ['columnas'] = 2;
		$atributos ['dobleLinea'] = 0;
		$atributos ['tabIndex'] = $tab;
		$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
		$atributos ['validar'] = '';
		
		if(isset($resultado[0]['numero_rp'])){
			
			$datosRP = array (
					'numeroRegistroPresupuestal' => $resultado[0]['numero_rp'],
					'vigencia' => $_REQUEST['vigencia'],
					'unidadEjecutora' => $resultado[0]['unidad_ejecutora']
			);
			
			$cadena_sql = $this->miSql->getCadenaSql ( "informacionRP", $datosRP);
			$resultadoRP = $siCapitalRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
			
			$atributos ['valor'] = date("d/m/Y", strtotime($resultadoRP[0]['FECHA_REGISTRO']));
		}else{
			$atributos ['valor'] = 'SIN RELACIONAR';
		}
		
		
		$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
		$atributos ['deshabilitado'] = true;
		$atributos ['tamanno'] = 40;
		$atributos ['maximoTamanno'] = '10';
		$atributos ['anchoEtiqueta'] = 200;
		$tab ++;
		
		// Aplica atributos globales al control
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoCuadroTexto ( $atributos );
		unset ( $atributos );
		// ----------------FIN CONTROL: Campo de Texto FUNCIONARIO--------------------------------------------------------
		
		
		// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
		$esteCampo = 'objetoContrato';
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
		$atributos ['validar'] = '';
		$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
		$atributos ['deshabilitado'] = true;
		$atributos ['tamanno'] = 20;
		$atributos ['maximoTamanno'] = '';
		$atributos ['anchoEtiqueta'] = 220;
		
		$atributos ['valor'] = mb_strtoupper($resultado[0]['objeto_contrato'],'utf-8');
		
		$tab ++;
		
		// Aplica atributos globales al control
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoTextArea ( $atributos );
		unset ( $atributos );
		
		
		// ----------------INICIO CONTROL: Campo de Texto DEPENDENCIA DESTINO--------------------------------------------------------
		$esteCampo = 'dependenciaDestino';
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
		$atributos ['validar'] = '';
		
		$atributos ['valor'] = "";//$resultado[0]['DEPENDENCIA_DESTINO'];
		
		$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
		$atributos ['deshabilitado'] = true;
		$atributos ['tamanno'] = 90;
		$atributos ['maximoTamanno'] = '30';
		$atributos ['anchoEtiqueta'] = 200;
		$tab ++;
		
		// Aplica atributos globales al control
		$atributos = array_merge ( $atributos, $atributosGlobales );
		//echo $this->miFormulario->campoCuadroTexto ( $atributos );
		unset ( $atributos );
		// ----------------FIN CONTROL: Campo de Texto DEPENDENCIA DESTINO--------------------------------------------------------
		
		
		
		// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
		$esteCampo = 'justificacion';
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
		$atributos ['validar'] = '';
		$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
		$atributos ['deshabilitado'] = true;
		$atributos ['tamanno'] = 20;
		$atributos ['maximoTamanno'] = '';
		$atributos ['anchoEtiqueta'] = 220;
		
		$atributos ['valor'] = mb_strtoupper($resultado[0]['justificacion'],'utf-8');
		
		$tab ++;
		
		// Aplica atributos globales al control
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoTextArea ( $atributos );
		unset ( $atributos );
		
		
		
		// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
		$esteCampo = 'condiciones';
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
		$atributos ['validar'] = '';
		$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
		$atributos ['deshabilitado'] = true;
		$atributos ['tamanno'] = 20;
		$atributos ['maximoTamanno'] = '';
		$atributos ['anchoEtiqueta'] = 220;
		
		$atributos ['valor'] = strtoupper($resultado[0]['condiciones']);
		
		$tab ++;
		
		// Aplica atributos globales al control
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoTextArea ( $atributos );
		unset ( $atributos );
		
		
		// ----------------INICIO CONTROL: Campo de Texto DEPENDENCIA DESTINO--------------------------------------------------------
		$esteCampo = 'valorContratacion';
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
		$atributos ['validar'] = '';
		
		$atributos ['valor'] = '$ ' . number_format($resultado[0]['valor_contrato'], 1, ',', '.');
		
		$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
		$atributos ['deshabilitado'] = true;
		$atributos ['tamanno'] = 50;
		$atributos ['maximoTamanno'] = '30';
		$atributos ['anchoEtiqueta'] = 200;
		$tab ++;
		
		// Aplica atributos globales al control
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoCuadroTexto ( $atributos );
		unset ( $atributos );
		// ----------------FIN CONTROL: Campo de Texto DEPENDENCIA DESTINO--------------------------------------------------------
		
		
		// ----------------INICIO CONTROL: Campo de Texto DEPENDENCIA DESTINO--------------------------------------------------------
		$esteCampo = 'tipoContratacion';
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
		$atributos ['validar'] = '';
		
		$atributos ['valor'] = mb_strtoupper($resultado[0]['clase_contratista'],'utf-8');
		
		$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
		$atributos ['deshabilitado'] = true;
		$atributos ['tamanno'] = 50;
		$atributos ['maximoTamanno'] = '30';
		$atributos ['anchoEtiqueta'] = 200;
		$tab ++;
		
		// Aplica atributos globales al control
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoCuadroTexto ( $atributos );
		unset ( $atributos );
		// ----------------FIN CONTROL: Campo de Texto DEPENDENCIA DESTINO--------------------------------------------------------
		
		
		
		?>
		
		 <div id="dialogo" style="display:none">   
			
			<div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix">
				<center>
			       <h2><?php echo $resultado[0]['clase_contratista']; ?></h2>
			    </center>
			</div>
			
			<?php 
			
			
			
			
			
			
			
			
			//**************************************** RELACION DATOS AGORA **********************************************************************************************
			//************************************************************************************************************************************************************
			
			
			$datosConsulta = array (
					'num_contrato' => $_REQUEST['idSolicitud'],
					'vigencia' => $_REQUEST['vigencia'],
					'unidadEjecutora' => $_REQUEST['unidadEjecutora']
			);
			
			$cadena_sql = $this->miSql->getCadenaSql ( "listaContratoXNumContratoFechas", $datosConsulta);
			$resultadoDate = $argoRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
			
			$fechaInicio = date("d/m/Y", strtotime($resultadoDate[0]['inicio']));
			$fechaFin = date("d/m/Y", strtotime($resultadoDate[0]['fin']));
			
			//var_dump($resultadoNecesidadRelacionada);
			//var_dump($resultadoNecesidadRelacionadaCIIU);
			
			
			
			$esteCampo = "marcoRelacionContrato";
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
			$atributos ['tipoEtiqueta'] = 'inicio';
			$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
			echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
			
			// ----------------INICIO CONTROL: Campo de Texto DEPENDENCIA DESTINO--------------------------------------------------------
			$esteCampo = 'supervisor';
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
			$atributos ['validar'] = '';
			
			$atributos ['valor'] = $resultado[0]['nombre_supervisor'];
			
			$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
			$atributos ['deshabilitado'] = true;
			$atributos ['tamanno'] = 60;
			$atributos ['maximoTamanno'] = '30';
			$atributos ['anchoEtiqueta'] = 300;
			$tab ++;
			
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroTexto ( $atributos );
			unset ( $atributos );
			// ----------------FIN CONTROL: Campo de Texto DEPENDENCIA DESTINO--------------------------------------------------------
			
			// ----------------INICIO CONTROL: Campo de Texto DEPENDENCIA DESTINO--------------------------------------------------------
			$esteCampo = 'docSupervisor';
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
			$atributos ['validar'] = '';
			
			$atributos ['valor'] = $resultado[0]['documento_supervisor'];
			
			$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
			$atributos ['deshabilitado'] = true;
			$atributos ['tamanno'] = 60;
			$atributos ['maximoTamanno'] = '30';
			$atributos ['anchoEtiqueta'] = 300;
			$tab ++;
			
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroTexto ( $atributos );
			unset ( $atributos );
			// ----------------FIN CONTROL: Campo de Texto DEPENDENCIA DESTINO--------------------------------------------------------
			
			// ----------------INICIO CONTROL: Campo de Texto DEPENDENCIA DESTINO--------------------------------------------------------
			$esteCampo = 'fechaInicio';
			$atributos ['id'] = $esteCampo;
			$atributos ['nombre'] = $esteCampo;
			$atributos ['tipo'] = 'text';
			$atributos ['estilo'] = 'jqueryui';
			$atributos ['marco'] = true;
			$atributos ['estiloMarco'] = '';
			$atributos ["etiquetaObligatorio"] = true;
			$atributos ['columnas'] = 2;
			$atributos ['dobleLinea'] = 0;
			$atributos ['tabIndex'] = $tab;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ['validar'] = '';
			
			$atributos ['valor'] = $fechaInicio;
			
			$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
			$atributos ['deshabilitado'] = true;
			$atributos ['tamanno'] = 30;
			$atributos ['maximoTamanno'] = '30';
			$atributos ['anchoEtiqueta'] = 200;
			$tab ++;
			
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroTexto ( $atributos );
			unset ( $atributos );
			// ----------------FIN CONTROL: Campo de Texto DEPENDENCIA DESTINO--------------------------------------------------------
			
			// ----------------INICIO CONTROL: Campo de Texto DEPENDENCIA DESTINO--------------------------------------------------------
			$esteCampo = 'fechaFinal';
			$atributos ['id'] = $esteCampo;
			$atributos ['nombre'] = $esteCampo;
			$atributos ['tipo'] = 'text';
			$atributos ['estilo'] = 'jqueryui';
			$atributos ['marco'] = true;
			$atributos ['estiloMarco'] = '';
			$atributos ["etiquetaObligatorio"] = true;
			$atributos ['columnas'] = 2;
			$atributos ['dobleLinea'] = 0;
			$atributos ['tabIndex'] = $tab;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ['validar'] = '';
			
			$atributos ['valor'] = $fechaFin;
			
			$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
			$atributos ['deshabilitado'] = true;
			$atributos ['tamanno'] = 30;
			$atributos ['maximoTamanno'] = '30';
			$atributos ['anchoEtiqueta'] = 200;
			$tab ++;
			
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroTexto ( $atributos );
			unset ( $atributos );

			
			if($resultado[0]['clase_contratista'] == 'Único Contratista'){
			
				$esteCampo = "marcoRelacionContratoProveedor";
				$atributos ['id'] = $esteCampo;
				$atributos ["estilo"] = "jqueryui";
				$atributos ['tipoEtiqueta'] = 'inicio';
				$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
				echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
				?>
			
			<fieldset class="warning">
				<legend> Proveedor</legend>
														
							<?php
								
							$cadenaSql = $this->miSql->getCadenaSql ( 'consultarProveedorDatos', $resultado[0]['identificacion_contratista'] );
							$resultadoProveedorDat = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
							
							// ----------------INICIO CONTROL: Campo de Texto DEPENDENCIA DESTINO--------------------------------------------------------
							$esteCampo = 'nombreProveedor';
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
							$atributos ['validar'] = '';
								
							$atributos ['valor'] = $resultadoProveedorDat[0]['nom_proveedor'];
								
							$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
							$atributos ['deshabilitado'] = true;
							$atributos ['tamanno'] = 100;
							$atributos ['maximoTamanno'] = '30';
							$atributos ['anchoEtiqueta'] = 200;
							$tab ++;
								
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroTexto ( $atributos );
							unset ( $atributos );
							// ----------------FIN CONTROL: Campo de Texto DEPENDENCIA DESTINO--------------------------------------------------------
								
								
							// ----------------INICIO CONTROL: Campo de Texto DEPENDENCIA DESTINO--------------------------------------------------------
							$esteCampo = 'identificacionProveedor';
							$atributos ['id'] = $esteCampo;
							$atributos ['nombre'] = $esteCampo;
							$atributos ['tipo'] = 'text';
							$atributos ['estilo'] = 'jqueryui';
							$atributos ['marco'] = true;
							$atributos ['estiloMarco'] = '';
							$atributos ["etiquetaObligatorio"] = true;
							$atributos ['columnas'] = 2;
							$atributos ['dobleLinea'] = 0;
							$atributos ['tabIndex'] = $tab;
							$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
							$atributos ['validar'] = '';
								
							$atributos ['valor'] = $resultadoProveedorDat[0]['num_documento'];
								
							$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
							$atributos ['deshabilitado'] = true;
							$atributos ['tamanno'] = 20;
							$atributos ['maximoTamanno'] = '30';
							$atributos ['anchoEtiqueta'] = 200;
							$tab ++;
								
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroTexto ( $atributos );
							unset ( $atributos );
							// ----------------FIN CONTROL: Campo de Texto DEPENDENCIA DESTINO--------------------------------------------------------
								
							// ----------------INICIO CONTROL: Campo de Texto DEPENDENCIA DESTINO--------------------------------------------------------
							$esteCampo = 'tipoProveedor';
							$atributos ['id'] = $esteCampo;
							$atributos ['nombre'] = $esteCampo;
							$atributos ['tipo'] = 'text';
							$atributos ['estilo'] = 'jqueryui';
							$atributos ['marco'] = true;
							$atributos ['estiloMarco'] = '';
							$atributos ["etiquetaObligatorio"] = true;
							$atributos ['columnas'] = 2;
							$atributos ['dobleLinea'] = 0;
							$atributos ['tabIndex'] = $tab;
							$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
							$atributos ['validar'] = '';
							
							$atributos ['valor'] = $resultadoProveedorDat[0]['tipopersona'];
							
							$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
							$atributos ['deshabilitado'] = true;
							$atributos ['tamanno'] = 20;
							$atributos ['maximoTamanno'] = '30';
							$atributos ['anchoEtiqueta'] = 200;
							$tab ++;
							
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroTexto ( $atributos );
							unset ( $atributos );
							// ----------------FIN CONTROL: Campo de Texto DEPENDENCIA DESTINO--------------------------------------------------------
							
							// ----------------INICIO CONTROL: Campo OCULTO-------------------------------------------------------*********************
							$esteCampo = 'idProveedor';
							$atributos ['id'] = $esteCampo;
							$atributos ['nombre'] = $esteCampo;
							$atributos ['tipo'] = 'hidden';
							$atributos ['valor'] = $resultadoProveedorDat[0]['id_proveedor'];
							$tab ++;
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroTexto ( $atributos );
							unset ( $atributos );
							// ----------------FIN CONTROL: Campo OCULTO --------------------------------------------------------*********************
							
							?>
							
							</fieldset>
			
			<?php	
								
							echo $this->miFormulario->marcoAgrupacion ( 'fin' );
							
						}else{
							
							$cadenaSql = $this->miSql->getCadenaSql ( 'consultarContratoGrupal', $resultado[0]['identificacion_contratista'] );
							$resultadoContratoGrupal = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
							
							//var_dump($cadenaSql);
							//var_dump($resultadoContratoGrupal);
							$numeroProveedoresConsorcio = count($resultadoContratoGrupal);
							$i = 0;
							
							
							
							// ----------------INICIO CONTROL: Campo OCULTO-------------------------------------------------------*********************
							$esteCampo = 'numeroProveedores';
							$atributos ['id'] = $esteCampo;
							$atributos ['nombre'] = $esteCampo;
							$atributos ['tipo'] = 'hidden';
							$atributos ['valor'] = $numeroProveedoresConsorcio;
							$tab ++;
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroTexto ( $atributos );
							unset ( $atributos );
							// ----------------FIN CONTROL: Campo OCULTO --------------------------------------------------------*********************
							
							$esteCampo = "marcoRelacionContratoProveedorConsorcio";
							$atributos ['id'] = $esteCampo;
							$atributos ["estilo"] = "jqueryui";
							$atributos ['tipoEtiqueta'] = 'inicio';
							$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
							echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
								
							
							while($i < $numeroProveedoresConsorcio){
								
								?>
								
								<fieldset class="warning" >
									<legend> Proveedor <?php echo " ".$i + 1?></legend>
															
								<?php
								
								
								// ----------------INICIO CONTROL: Campo de Texto DEPENDENCIA DESTINO--------------------------------------------------------
								$esteCampo = 'nombreProveedor'.$i;
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
								$atributos ['etiqueta'] = $this->lenguaje->getCadena ( 'nombreProveedor' );
								$atributos ['validar'] = '';
								
								$cadenaSql = $this->miSql->getCadenaSql ( 'consultarProveedorDatos', $resultadoContratoGrupal[$i]['id_contratista'] );
								$resultadoProveedorDat = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
									
								$atributos ['valor'] = $resultadoProveedorDat[0]['nom_proveedor'];
									
								$atributos ['titulo'] = $this->lenguaje->getCadena ( 'nombreProveedor' . 'Titulo' );
								$atributos ['deshabilitado'] = true;
								$atributos ['tamanno'] = 100;
								$atributos ['maximoTamanno'] = '30';
								$atributos ['anchoEtiqueta'] = 200;
								$tab ++;
									
								// Aplica atributos globales al control
								$atributos = array_merge ( $atributos, $atributosGlobales );
								echo $this->miFormulario->campoCuadroTexto ( $atributos );
								unset ( $atributos );
								// ----------------FIN CONTROL: Campo de Texto DEPENDENCIA DESTINO--------------------------------------------------------
									
									
								// ----------------INICIO CONTROL: Campo de Texto DEPENDENCIA DESTINO--------------------------------------------------------
								$esteCampo = 'identificacionProveedor'.$i;
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
								$atributos ['etiqueta'] = $this->lenguaje->getCadena ( 'identificacionProveedor' );
								$atributos ['validar'] = '';
									
								$atributos ['valor'] = $resultadoProveedorDat[0]['num_documento'];
									
								$atributos ['titulo'] = $this->lenguaje->getCadena ( 'identificacionProveedor' . 'Titulo' );
								$atributos ['deshabilitado'] = true;
								$atributos ['tamanno'] = 20;
								$atributos ['maximoTamanno'] = '30';
								$atributos ['anchoEtiqueta'] = 200;
								$tab ++;
									
								// Aplica atributos globales al control
								$atributos = array_merge ( $atributos, $atributosGlobales );
								echo $this->miFormulario->campoCuadroTexto ( $atributos );
								unset ( $atributos );
								// ----------------FIN CONTROL: Campo de Texto DEPENDENCIA DESTINO--------------------------------------------------------
								
								// ----------------INICIO CONTROL: Campo de Texto DEPENDENCIA DESTINO--------------------------------------------------------
								$esteCampo = 'participacionProveedor'.$i;
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
								$atributos ['etiqueta'] = $this->lenguaje->getCadena ( 'participacionProveedor' );
								$atributos ['validar'] = '';
								
								$atributos ['valor'] = ($resultadoContratoGrupal[$i]['porcentaje_participacion']) . " %";
								
								$atributos ['titulo'] = $this->lenguaje->getCadena ( 'participacionProveedor' . 'Titulo' );
								$atributos ['deshabilitado'] = true;
								$atributos ['tamanno'] = 20;
								$atributos ['maximoTamanno'] = '30';
								$atributos ['anchoEtiqueta'] = 200;
								$tab ++;
								
								// Aplica atributos globales al control
								$atributos = array_merge ( $atributos, $atributosGlobales );
								echo $this->miFormulario->campoCuadroTexto ( $atributos );
								unset ( $atributos );
								// ----------------FIN CONTROL: Campo de Texto DEPENDENCIA DESTINO--------------------------------------------------------
								
								// ----------------INICIO CONTROL: Campo de Texto DEPENDENCIA DESTINO--------------------------------------------------------
								$esteCampo = 'tipoProveedor'.$i;
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
								$atributos ['etiqueta'] = $this->lenguaje->getCadena ( 'tipoProveedor' );
								$atributos ['validar'] = '';
									
								$atributos ['valor'] = $resultadoProveedorDat[0]['tipopersona'];
									
								$atributos ['titulo'] = $this->lenguaje->getCadena ( 'tipoProveedor' . 'Titulo' );
								$atributos ['deshabilitado'] = true;
								$atributos ['tamanno'] = 20;
								$atributos ['maximoTamanno'] = '30';
								$atributos ['anchoEtiqueta'] = 200;
								$tab ++;
									
								// Aplica atributos globales al control
								$atributos = array_merge ( $atributos, $atributosGlobales );
								echo $this->miFormulario->campoCuadroTexto ( $atributos );
								unset ( $atributos );
								// ----------------FIN CONTROL: Campo de Texto DEPENDENCIA DESTINO--------------------------------------------------------
								
								?>
								
								</fieldset>
								
								<?php
			
								$i++;
							}
							
								
							echo $this->miFormulario->marcoAgrupacion ( 'fin' );
							
						}
					
					
					
					
					echo $this->miFormulario->marcoAgrupacion ( 'fin' );
			
			
			
			
			
			
			
			
			
			
			
			
			
			?>
			
			

			
			
		</div> 
	
		
		<div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix">
		<?php
		
		
		
		//******************************************************************************** REGRESAR ****************************************
		//**********************************************************************************************************************************
		// ------------------Division para los botones-------------------------
		$atributos ["id"] = "botones";
		$atributos ["estilo"] = "marcoBotones";
		echo $this->miFormulario->division ( "inicio", $atributos );
		{
			
			echo '<div class="widget">';
			
			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
			$esteCampo = 'botonContratista';
			$atributos ['id'] = $esteCampo;
			$atributos ['enlace'] = "#";
			$atributos ['tabIndex'] = 1;
			$atributos ['estilo'] = 'ui-button ui-widget ui-corner-all';
			$atributos ['enlaceTexto'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ['ancho'] = '10%';
			$atributos ['alto'] = '10%';
			$atributos ['redirLugar'] = false;
			echo $this->miFormulario->enlace ( $atributos );
			
			echo '</div>';
			
			
		}
		// ------------------Fin Division para los botones-------------------------
		echo $this->miFormulario->division ( "fin" );
		unset ( $atributos );
		//**********************************************************************************************************************************
		//**********************************************************************************************************************************
		//******************************************************************************** REGRESAR ****************************************
		
		
		?>
		
		</div>
		
		<?php
		
		
		
		
		
		// ----------------INICIO CONTROL: Campo de Texto DEPENDENCIA DESTINO--------------------------------------------------------
		$esteCampo = 'plazo';
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
		$atributos ['validar'] = '';
		
		$atributos ['valor'] = $resultado[0]['plazo_ejecucion'];
		
		$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
		$atributos ['deshabilitado'] = true;
		$atributos ['tamanno'] = 80;
		$atributos ['maximoTamanno'] = '30';
		$atributos ['anchoEtiqueta'] = 200;
		$tab ++;
		
		// Aplica atributos globales al control
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoCuadroTexto ( $atributos );
		unset ( $atributos );
		// ----------------FIN CONTROL: Campo de Texto DEPENDENCIA DESTINO--------------------------------------------------------
		
		// ----------------INICIO CONTROL: Campo de Texto DEPENDENCIA DESTINO--------------------------------------------------------
		$esteCampo = 'ordenador';
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
		$atributos ['validar'] = '';
		
		$atributos ['valor'] = $resultado[0]['nombre_ordenador_gasto'];
		
		$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
		$atributos ['deshabilitado'] = true;
		$atributos ['tamanno'] = 90;
		$atributos ['maximoTamanno'] = '30';
		$atributos ['anchoEtiqueta'] = 200;
		$tab ++;
		
		// Aplica atributos globales al control
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoCuadroTexto ( $atributos );
		unset ( $atributos );
		// ----------------FIN CONTROL: Campo de Texto DEPENDENCIA DESTINO--------------------------------------------------------
		
		// ----------------INICIO CONTROL: Campo de Texto DEPENDENCIA DESTINO--------------------------------------------------------
		$esteCampo = 'ordenadorCargo';
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
		$atributos ['validar'] = '';
		
		$atributos ['valor'] = $resultado[0]['cargo_ordenador_gasto'];
		
		$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
		$atributos ['deshabilitado'] = true;
		$atributos ['tamanno'] = 90;
		$atributos ['maximoTamanno'] = '30';
		$atributos ['anchoEtiqueta'] = 200;
		$tab ++;
		
		// Aplica atributos globales al control
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoCuadroTexto ( $atributos );
		unset ( $atributos );
		// ----------------FIN CONTROL: Campo de Texto DEPENDENCIA DESTINO--------------------------------------------------------
		
		
		// ----------------INICIO CONTROL: Campo de Texto DEPENDENCIA DESTINO--------------------------------------------------------
		$esteCampo = 'tipoControl';
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
		$atributos ['validar'] = '';
		
		$atributos ['valor'] = mb_strtoupper($resultado[0]['tipo_control'],'utf-8');
		
		$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
		$atributos ['deshabilitado'] = true;
		$atributos ['tamanno'] = 50;
		$atributos ['maximoTamanno'] = '30';
		$atributos ['anchoEtiqueta'] = 200;
		$tab ++;
		
		// Aplica atributos globales al control
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoCuadroTexto ( $atributos );
		unset ( $atributos );
		// ----------------FIN CONTROL: Campo de Texto DEPENDENCIA DESTINO--------------------------------------------------------
		
		// ----------------INICIO CONTROL: Campo de Texto DEPENDENCIA DESTINO--------------------------------------------------------
		$esteCampo = 'formaPago';
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
		$atributos ['validar'] = '';
		
		$atributos ['valor'] = strtoupper($resultado[0]['forma_pago']);
		
		$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
		$atributos ['deshabilitado'] = true;
		$atributos ['tamanno'] = 50;
		$atributos ['maximoTamanno'] = '30';
		$atributos ['anchoEtiqueta'] = 200;
		$tab ++;
		
		// Aplica atributos globales al control
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoCuadroTexto ( $atributos );
		unset ( $atributos );
		// ----------------FIN CONTROL: Campo de Texto DEPENDENCIA DESTINO--------------------------------------------------------
		
		// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
		$esteCampo = 'descripcionPago';
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
		$atributos ['validar'] = '';
		$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
		$atributos ['deshabilitado'] = true;
		$atributos ['tamanno'] = 20;
		$atributos ['maximoTamanno'] = '';
		$atributos ['anchoEtiqueta'] = 220;
		
		$atributos ['valor'] = mb_strtoupper($resultado[0]['descripcion_forma_pago'],'utf-8');
		
		$tab ++;
		
		// Aplica atributos globales al control
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoTextArea ( $atributos );
		unset ( $atributos );
		
		
		
		
		echo $this->miFormulario->marcoAgrupacion ( 'fin' );
		
		
		
		
		
		//**************************************** RELACION DATOS AGORA **********************************************************************************************
		//************************************************************************************************************************************************************
		
		
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


$miFormulario = new FormularioRegistro ( $this->lenguaje, $this->miFormulario, $this->sql  );

$miFormulario->formulario ();
$miFormulario->mensaje ();
?>
