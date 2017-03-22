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
		
		$conexion = 'sicapital';
		$siCapitalRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
		
		$conexion = 'centralUD';
		$centralUDRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
		
		//$conexion = 'argo_contratos';
		//$argoRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
		
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
			

			
			
			//********************************************************************************************** PERSONA JURIDICA****************************
                        
			
				$esteCampo = "marcoEmpresa";
				$atributos ['id'] = $esteCampo;
				$atributos ["estilo"] = "jqueryui";
				$atributos ['tipoEtiqueta'] = 'inicio';
				$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
				echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos ); 

					// ---------------- CONTROL: Cuadro de Texto NIT--------------------------------------------------------
						$esteCampo = 'nit';
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
						$atributos ['validar'] = 'required, minSize[1],maxSize[14],custom[onlyNumberSp]';
						
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['valor'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['valor'] = '';
						}
						$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
						$atributos ['deshabilitado'] = false;
						$atributos ['tamanno'] = 15;
						$atributos ['maximoTamanno'] = 9;
						$atributos ['anchoEtiqueta'] = 200;
						$tab ++;
						
						// Aplica atributos globales al control
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroTexto ( $atributos );
						unset ( $atributos );
					// ---------------- FIN CONTROL: Cuadro de Texto  NIT--------------------------------------------------------
		
					// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
						$esteCampo = 'digito';
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
						$atributos ['validar'] = 'minSize[1],maxSize[2],custom[onlyNumberSp]';
						
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['valor'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['valor'] = '';
						}
						$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
						$atributos ['deshabilitado'] = true;
						$atributos ['tamanno'] = 15;
						$atributos ['maximoTamanno'] = '';
						$atributos ['anchoEtiqueta'] = 200;
						$tab ++;
						
						// Aplica atributos globales al control
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroTexto ( $atributos );
						unset ( $atributos );
					// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
					
					// ---------------- CONTROL: Cuadro de Texto NOMBRE EMPRESA--------------------------------------------------------
						$esteCampo = 'nombreEmpresa';
						$atributos ['id'] = $esteCampo;
						$atributos ['nombre'] = $esteCampo;
						$atributos ['tipo'] = 'text';
						$atributos ['estilo'] = 'jqueryui mayuscula';
						$atributos ['marco'] = true;
						$atributos ['estiloMarco'] = '';
						$atributos ["etiquetaObligatorio"] = true;
						$atributos ['columnas'] = 1;
						$atributos ['dobleLinea'] = 0;
						$atributos ['tabIndex'] = $tab;
						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
						$atributos ['validar'] = 'required, minSize[1],maxSize[100]';
						
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['valor'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['valor'] = '';
						}
						$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
						$atributos ['deshabilitado'] = false;
						$atributos ['tamanno'] = 50;
						$atributos ['maximoTamanno'] = '';
						$atributos ['anchoEtiqueta'] = 200;
						$tab ++;
						
						// Aplica atributos globales al control
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroTexto ( $atributos );
						unset ( $atributos );
					// ---------------- FIN CONTROL: Cuadro de Texto NOMBRE EMPRESA--------------------------------------------------------
					
					
						
						$esteCampo = "marcoProcedenciaExtranjero";
						$atributos ['id'] = $esteCampo;
						$atributos ["estilo"] = "jqueryui";
						$atributos ['tipoEtiqueta'] = 'inicio';
						$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
						echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
						unset($atributos);
						{
							// ---------------- CONTROL: Cuadro de Texto PAIS--------------------------------------------------------
							
							
							
							// ---------------- CONTROL: Select --------------------------------------------------------
							$esteCampo = 'personaJuridicaPais';
							$atributos['nombre'] = $esteCampo;
							$atributos['id'] = $esteCampo;
							$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
							$atributos['tab'] = $tab;
							$atributos['seleccion'] = -1;
							$atributos['evento'] = ' ';
							$atributos['deshabilitado'] = false;
							$atributos['limitar']= 50;
							$atributos['tamanno']= 1;
							$atributos['columnas']= 1;
							
							$atributos ['obligatorio'] = true;
							$atributos ['etiquetaObligatorio'] = true;
							$atributos ['validar'] = 'required';
							
							$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarPais" );
							$matrizItems = $coreRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
							
							$atributos['matrizItems'] = $matrizItems;
							
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else {
								$atributos ['valor'] = '';
							}
							$tab ++;
							
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroLista ( $atributos );
							// --------------- FIN CONTROL : Select --------------------------------------------------
							
							// ---------------- CONTROL: Select --------------------------------------------------------
							$esteCampo = 'personaJuridicaDepartamento';
							$atributos['nombre'] = $esteCampo;
							$atributos['id'] = $esteCampo;
							$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
							$atributos['tab'] = $tab;
							$atributos['seleccion'] = -1;
							$atributos['evento'] = ' ';
							$atributos['deshabilitado'] = true;
							$atributos['limitar']= 50;
							$atributos['tamanno']= 1;
							$atributos['columnas']= 1;
							
							$atributos ['obligatorio'] = true;
							$atributos ['etiquetaObligatorio'] = true;
							$atributos ['validar'] = 'required';
							
							$matrizItems=array(
									array(1,'Cundinamarca'),
									array(2,'Antioquia'),
									array(3,'Santander'),
									array(4,'Bolivar'),
									array(5,'Bogotá D.C.')
							
							);
							
							$atributos['matrizItems'] = $matrizItems;
							
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else {
								$atributos ['valor'] = '';
							}
							$tab ++;
							
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroLista ( $atributos );
							// --------------- FIN CONTROL : Select --------------------------------------------------
							
							// ---------------- CONTROL: Select --------------------------------------------------------
							$esteCampo = 'personaJuridicaCiudad';
							$atributos['nombre'] = $esteCampo;
							$atributos['id'] = $esteCampo;
							$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
							$atributos['tab'] = $tab;
							$atributos['seleccion'] = -1;
							$atributos['evento'] = ' ';
							$atributos['deshabilitado'] = true;
							$atributos['limitar']= 50;
							$atributos['tamanno']= 1;
							$atributos['columnas']= 1;
							
							$atributos ['obligatorio'] = true;
							$atributos ['etiquetaObligatorio'] = true;
							$atributos ['validar'] = 'required';
							
							$matrizItems=array(
									array(1,'Bogota D.C.'),
									array(2,'Medellin'),
									array(3,'Barranquilla'),
									array(4,'Cali'),
									array(5,'Cucuta'),
									array(6,'Bucaramanga')
							
							);
							$atributos['matrizItems'] = $matrizItems;
							
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else {
								$atributos ['valor'] = '';
							}
							$tab ++;
							
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroLista ( $atributos );
							 
							
							
							/*
							$esteCampo = 'pais';
							$atributos ['id'] = $esteCampo;
							$atributos ['nombre'] = $esteCampo;
							$atributos ['tipo'] = 'text';
							$atributos ['estilo'] = 'jqueryui';
							$atributos ['marco'] = true;
							$atributos ['estiloMarco'] = '';
							$atributos ["etiquetaObligatorio"] = true;
							$atributos ['columnas'] = 2;
							$atributos ['dobleLinea'] = 0;
							$atributos ['tabIndex'] = $tab ++;
							$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
							$atributos ['validar'] = 'required, minSize[1],maxSize[50]';
							
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else {
								$atributos ['valor'] = '';
							}
							$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
							$atributos ['deshabilitado'] = false;
							$atributos ['tamanno'] = 30;
							$atributos ['maximoTamanno'] = '';
							$atributos ['anchoEtiqueta'] = 160;
							$tab ++;
							
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroTexto ( $atributos );
							unset ( $atributos );
							*/
							// ---------------- FIN CONTROL: Cuadro de Texto  PAIS--------------------------------------------------------
							
							// ---------------- CONTROL: Cuadro de Texto  Codigo Pais--------------------------------------------------------
							$esteCampo = 'codigoPais';
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
							$atributos ['validar'] = 'required, minSize[1],maxSize[30],custom[onlyNumberSp]';
							
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else {
								$atributos ['valor'] = '';
							}
							$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
							$atributos ['deshabilitado'] = true;
							$atributos ['tamanno'] = 10;
							$atributos ['maximoTamanno'] = '';
							$atributos ['anchoEtiqueta'] = 180;
							$tab ++;
							
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroTexto ( $atributos );
							unset ( $atributos );
							// ---------------- FIN CONTROL: Cuadro de Texto  Codigo Pais--------------------------------------------------------
							
							// ---------------- CONTROL: Cuadro de Texto Codigo Postal--------------------------------------------------------
							$esteCampo = 'codigoPostal';
							$atributos ['id'] = $esteCampo;
							$atributos ['nombre'] = $esteCampo;
							$atributos ['tipo'] = 'text';
							$atributos ['estilo'] = 'jqueryui';
							$atributos ['marco'] = true;
							$atributos ['estiloMarco'] = '';
							$atributos ["etiquetaObligatorio"] = false;
							$atributos ['columnas'] = 2;
							$atributos ['dobleLinea'] = 0;
							$atributos ['tabIndex'] = $tab ++;
							$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
							$atributos ['validar'] = 'minSize[1],maxSize[30],custom[onlyNumberSp]';
								
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else {
								$atributos ['valor'] = '';
							}
							$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
							$atributos ['deshabilitado'] = false;
							$atributos ['tamanno'] = 30;
							$atributos ['maximoTamanno'] = '';
							$atributos ['anchoEtiqueta'] = 160;
							$tab ++;
								
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							//echo $this->miFormulario->campoCuadroTexto ( $atributos );
							unset ( $atributos );
							// ---------------- FIN CONTROL: Cuadro de Texto  Codigo Postal--------------------------------------------------------
								
						
							
						}				
						echo $this->miFormulario->marcoAgrupacion ( 'fin' );
						
									
						
				echo $this->miFormulario->marcoAgrupacion ( 'fin' );			
			
			
				$esteCampo = "marcoContacto";
				$atributos ['id'] = $esteCampo;
				$atributos ["estilo"] = "jqueryui";
				$atributos ['tipoEtiqueta'] = 'inicio';
				$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
				echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );                    
				
				
					/*
					// ---------------- CONTROL: Cuadro de Texto CIUDAD--------------------------------------------------------
						$esteCampo = 'ciudad';
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
						$atributos ['validar'] = 'required, minSize[1],maxSize[50]';
						
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['valor'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['valor'] = '';
						}
						$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
						$atributos ['deshabilitado'] = false;
						$atributos ['tamanno'] = 15;
						$atributos ['maximoTamanno'] = '';
						$atributos ['anchoEtiqueta'] = 160;
						$tab ++;
						
						// Aplica atributos globales al control
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroTexto ( $atributos );
						unset ( $atributos );
					// ---------------- FIN CONTROL: Cuadro de Texto  NIT--------------------------------------------------------
					
					*/
						
						
						
					
						
						
						
						
					
					// ---------------- CONTROL: Cuadro de Texto  Dirección--------------------------------------------------------
						$esteCampo = 'direccionExt';
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
						$atributos ['validar'] = 'required, minSize[1],maxSize[150]';
						
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['valor'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['valor'] = '';
						}
						$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
						$atributos ['deshabilitado'] = false;
						$atributos ['tamanno'] = 30;
						$atributos ['maximoTamanno'] = '';
						$atributos ['anchoEtiqueta'] = 160;
						$tab ++;
						
						// Aplica atributos globales al control
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroTexto ( $atributos );
						unset ( $atributos );
					// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
					
						

						
						
					// ---------------- CONTROL: Cuadro de Texto Correo--------------------------------------------------------
						$esteCampo = 'correo';
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
						$atributos ['validar'] = 'required, custom[email], maxSize[320]';
							
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['valor'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['valor'] = '';
						}
						$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
						$atributos ['deshabilitado'] = false;
						$atributos ['tamanno'] = 30;
						$atributos ['maximoTamanno'] = '320';
						$atributos ['anchoEtiqueta'] = 160;
						$tab ++;
							
						// Aplica atributos globales al control
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroTexto ( $atributos );
						unset ( $atributos );
					// ---------------- FIN CONTROL: Cuadro de Texto  NIT--------------------------------------------------------
					

						
		
					// ---------------- CONTROL: Cuadro de Texto  Sitio Web--------------------------------------------------------
						$esteCampo = 'sitioWeb';
						$atributos ['id'] = $esteCampo;
						$atributos ['nombre'] = $esteCampo;
						$atributos ['tipo'] = 'text';
						$atributos ['estilo'] = 'jqueryui';
						$atributos ['marco'] = true;
						$atributos ['estiloMarco'] = '';
						$atributos ["etiquetaObligatorio"] = false;
						$atributos ['columnas'] = 6;
						$atributos ['dobleLinea'] = 0;
						$atributos ['tabIndex'] = $tab;
						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
						$atributos ['validar'] = 'minSize[1],maxSize[100]';
						
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['valor'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['valor'] = '';
						}
						$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
						$atributos ['deshabilitado'] = false;
						$atributos ['tamanno'] = 30;
						$atributos ['maximoTamanno'] = '';
						$atributos ['anchoEtiqueta'] = 160;
						$tab ++;
						
						// Aplica atributos globales al control
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroTexto ( $atributos );
						unset ( $atributos );
					// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------


				echo $this->miFormulario->marcoAgrupacion ( 'fin' );			

				
				
				
				
				
				
				
// 				$esteCampo = "marcoRepresentante";
// 				$atributos ['id'] = $esteCampo;
// 				$atributos ["estilo"] = "jqueryui";
// 				$atributos ['tipoEtiqueta'] = 'inicio';
// 				$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
// 				echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos ); 
						
				
						




// 				// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
// 				$esteCampo = 'primerApellido';
// 				$atributos ['id'] = $esteCampo;
// 				$atributos ['nombre'] = $esteCampo;
// 				$atributos ['tipo'] = 'text';
// 				$atributos ['estilo'] = 'jqueryui mayuscula';
// 				$atributos ['marco'] = true;
// 				$atributos ['estiloMarco'] = '';
// 				$atributos ["etiquetaObligatorio"] = true;
// 				$atributos ['columnas'] = 2;
// 				$atributos ['dobleLinea'] = 0;
// 				$atributos ['tabIndex'] = $tab;
// 				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
// 				$atributos ['validar'] = 'required, minSize[1],maxSize[30]';
				
// 				if (isset ( $_REQUEST [$esteCampo] )) {
// 					$atributos ['valor'] = $_REQUEST [$esteCampo];
// 				} else {
// 					$atributos ['valor'] = '';
// 				}
// 				$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
// 				$atributos ['deshabilitado'] = false;
// 				$atributos ['tamanno'] = 15;
// 				$atributos ['maximoTamanno'] = '';
// 				$atributos ['anchoEtiqueta'] = 200;
// 				$tab ++;
				
// 				// Aplica atributos globales al control
// 				$atributos = array_merge ( $atributos, $atributosGlobales );
// 				echo $this->miFormulario->campoCuadroTexto ( $atributos );
// 				unset ( $atributos );
// 				// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
				
// 				// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
// 				$esteCampo = 'segundoApellido';
// 				$atributos ['id'] = $esteCampo;
// 				$atributos ['nombre'] = $esteCampo;
// 				$atributos ['tipo'] = 'text';
// 				$atributos ['estilo'] = 'jqueryui mayuscula';
// 				$atributos ['marco'] = true;
// 				$atributos ['estiloMarco'] = '';
// 				$atributos ["etiquetaObligatorio"] = false;
// 				$atributos ['columnas'] = 2;
// 				$atributos ['dobleLinea'] = 0;
// 				$atributos ['tabIndex'] = $tab;
// 				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
// 				$atributos ['validar'] = 'minSize[1],maxSize[30]';
				
// 				if (isset ( $_REQUEST [$esteCampo] )) {
// 					$atributos ['valor'] = $_REQUEST [$esteCampo];
// 				} else {
// 					$atributos ['valor'] = '';
// 				}
// 				$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
// 				$atributos ['deshabilitado'] = false;
// 				$atributos ['tamanno'] = 15;
// 				$atributos ['maximoTamanno'] = '';
// 				$atributos ['anchoEtiqueta'] = 200;
// 				$tab ++;
				
// 				// Aplica atributos globales al control
// 				$atributos = array_merge ( $atributos, $atributosGlobales );
// 				echo $this->miFormulario->campoCuadroTexto ( $atributos );
// 				unset ( $atributos );
// 				// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
				
// 				// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
// 				$esteCampo = 'primerNombre';
// 				$atributos ['id'] = $esteCampo;
// 				$atributos ['nombre'] = $esteCampo;
// 				$atributos ['tipo'] = 'text';
// 				$atributos ['estilo'] = 'jqueryui mayuscula';
// 				$atributos ['marco'] = true;
// 				$atributos ['estiloMarco'] = '';
// 				$atributos ["etiquetaObligatorio"] = true;
// 				$atributos ['columnas'] = 2;
// 				$atributos ['dobleLinea'] = 0;
// 				$atributos ['tabIndex'] = $tab;
// 				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
// 				$atributos ['validar'] = 'required, minSize[1],maxSize[30]';
				
// 				if (isset ( $_REQUEST [$esteCampo] )) {
// 					$atributos ['valor'] = $_REQUEST [$esteCampo];
// 				} else {
// 					$atributos ['valor'] = '';
// 				}
// 				$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
// 				$atributos ['deshabilitado'] = false;
// 				$atributos ['tamanno'] = 15;
// 				$atributos ['maximoTamanno'] = '';
// 				$atributos ['anchoEtiqueta'] = 200;
// 				$tab ++;
				
// 				// Aplica atributos globales al control
// 				$atributos = array_merge ( $atributos, $atributosGlobales );
// 				echo $this->miFormulario->campoCuadroTexto ( $atributos );
// 				unset ( $atributos );
// 				// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
				
// 				// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
// 				$esteCampo = 'segundoNombre';
// 				$atributos ['id'] = $esteCampo;
// 				$atributos ['nombre'] = $esteCampo;
// 				$atributos ['tipo'] = 'text';
// 				$atributos ['estilo'] = 'jqueryui mayuscula';
// 				$atributos ['marco'] = true;
// 				$atributos ['estiloMarco'] = '';
// 				$atributos ["etiquetaObligatorio"] = false;
// 				$atributos ['columnas'] = 2;
// 				$atributos ['dobleLinea'] = 0;
// 				$atributos ['tabIndex'] = $tab;
// 				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
// 				$atributos ['validar'] = 'minSize[1],maxSize[30]';
				
// 				if (isset ( $_REQUEST [$esteCampo] )) {
// 					$atributos ['valor'] = $_REQUEST [$esteCampo];
// 				} else {
// 					$atributos ['valor'] = '';
// 				}
// 				$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
// 				$atributos ['deshabilitado'] = false;
// 				$atributos ['tamanno'] = 15;
// 				$atributos ['maximoTamanno'] = '';
// 				$atributos ['anchoEtiqueta'] = 200;
// 				$tab ++;
				
// 				// Aplica atributos globales al control
// 				$atributos = array_merge ( $atributos, $atributosGlobales );
// 				echo $this->miFormulario->campoCuadroTexto ( $atributos );
// 				unset ( $atributos );
// 				// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
							
				
				
// 						$esteCampo = "marcoExpRep";
// 						$atributos ['id'] = $esteCampo;
// 						$atributos ["estilo"] = "jqueryui";
// 						$atributos ['tipoEtiqueta'] = 'inicio';
// 						$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
// 						echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
						
						

// 						// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
// 						$esteCampo = "tipoDocumento";
// 						$atributos ['nombre'] = $esteCampo;
// 						$atributos ['id'] = $esteCampo;
// 						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
// 						$atributos ["etiquetaObligatorio"] = true;
// 						$atributos ['tab'] = $tab ++;
// 						$atributos ['anchoEtiqueta'] = 200;
// 						$atributos ['evento'] = '';
// 						if (isset ( $_REQUEST [$esteCampo] )) {
// 							$atributos ['seleccion'] = $_REQUEST [$esteCampo];
// 						} else {
// 							$atributos ['seleccion'] = -1;
// 						}
// 						$atributos ['deshabilitado'] = false;
// 						$atributos ['columnas'] = 1;
// 						$atributos ['tamanno'] = 1;
// 						$atributos ['estilo'] = "jqueryui";
// 						$atributos ['validar'] = "required";
// 						$atributos ['limitar'] = false;
// 						$atributos ['anchoCaja'] = 60;
// 						$atributos ['miEvento'] = '';
						
						
// 						$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "consultarTipoDocumento" );
// 						$matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
						
// 						// Valores a mostrar en el control
// 						/*$matrizItems = array (
// 						 array ( 1, 'Registro Civil de Nacimiento' ),
// 						 array ( 2, 'Tarjeta de Identidad' ),
// 						 array ( 3, 'Cédula de Ciudadania' ),
// 						 array ( 4, 'Certificado de Registraduria' ),
// 						 array ( 5, 'Tarjeta de Extranjería' ),
// 						 array ( 6, 'Cédula de Extranjería' ),
// 						 array ( 7, 'Pasaporte' ),
// 						 array ( 8, 'Carne Diplomatico' )
// 						 );*/
// 						$atributos ['matrizItems'] = $matrizItems;
// 						$atributos = array_merge ( $atributos, $atributosGlobales );
// 						echo $this->miFormulario->campoCuadroLista ( $atributos );
// 						unset ( $atributos );
// 						// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
						
						
// 						// ---------------- CONTROL: Cuadro de Texto NIT--------------------------------------------------------
// 						$esteCampo = 'numeroDocumento';
// 						$atributos ['id'] = $esteCampo;
// 						$atributos ['nombre'] = $esteCampo;
// 						$atributos ['tipo'] = 'text';
// 						$atributos ['estilo'] = 'jqueryui';
// 						$atributos ['marco'] = true;
// 						$atributos ['estiloMarco'] = '';
// 						$atributos ["etiquetaObligatorio"] = true;
// 						$atributos ['columnas'] = 2;
// 						$atributos ['dobleLinea'] = 0;
// 						$atributos ['tabIndex'] = $tab;
// 						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
// 						$atributos ['validar'] = 'required, minSize[1],maxSize[15],custom[onlyNumberSp]';
						
// 						if (isset ( $_REQUEST [$esteCampo] )) {
// 							$atributos ['valor'] = $_REQUEST [$esteCampo];
// 						} else {
// 							$atributos ['valor'] = '';
// 						}
// 						$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
// 						$atributos ['deshabilitado'] = false;
// 						$atributos ['tamanno'] = 15;
// 						$atributos ['maximoTamanno'] = '';
// 						$atributos ['anchoEtiqueta'] = 200;
// 						$tab ++;
						
// 						// Aplica atributos globales al control
// 						$atributos = array_merge ( $atributos, $atributosGlobales );
// 						echo $this->miFormulario->campoCuadroTexto ( $atributos );
// 						unset ( $atributos );
// 						// ---------------- FIN CONTROL: Cuadro de Texto  NIT--------------------------------------------------------
							
// 						// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
// 						$esteCampo = 'digitoRepre';
// 						$atributos ['id'] = $esteCampo;
// 						$atributos ['nombre'] = $esteCampo;
// 						$atributos ['tipo'] = 'text';
// 						$atributos ['estilo'] = 'jqueryui';
// 						$atributos ['marco'] = true;
// 						$atributos ['estiloMarco'] = '';
// 						$atributos ["etiquetaObligatorio"] = true;
// 						$atributos ['columnas'] = 2;
// 						$atributos ['dobleLinea'] = 0;
// 						$atributos ['tabIndex'] = $tab;
// 						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
// 						$atributos ['validar'] = 'minSize[1],maxSize[2],custom[onlyNumberSp]';
							
// 						if (isset ( $_REQUEST [$esteCampo] )) {
// 							$atributos ['valor'] = $_REQUEST [$esteCampo];
// 						} else {
// 							$atributos ['valor'] = '';
// 						}
// 						$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
// 						$atributos ['deshabilitado'] = true;
// 						$atributos ['tamanno'] = 15;
// 						$atributos ['maximoTamanno'] = '';
// 						$atributos ['anchoEtiqueta'] = 200;
// 						$tab ++;
							
// 						// Aplica atributos globales al control
// 						$atributos = array_merge ( $atributos, $atributosGlobales );
// 						echo $this->miFormulario->campoCuadroTexto ( $atributos );
// 						unset ( $atributos );
// 						// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
						
								
						
						
// 						// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
// 						$esteCampo = 'fechaExpeRep';
// 						$atributos ['id'] = $esteCampo;
// 						$atributos ['nombre'] = $esteCampo;
// 						$atributos ['tipo'] = 'text';
// 						$atributos ['estilo'] = 'jqueryui';
// 						$atributos ['marco'] = true;
// 						$atributos ['estiloMarco'] = '';
// 						$atributos ["etiquetaObligatorio"] = true;
// 						$atributos ['columnas'] = 1;
// 						$atributos ['dobleLinea'] = 0;
// 						$atributos ['tabIndex'] = $tab;
// 						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
// 						$atributos ['validar'] = 'required,minSize[1],maxSize[10],custom[date]';
							
// 						if (isset ( $_REQUEST [$esteCampo] )) {
// 							$atributos ['valor'] = $_REQUEST [$esteCampo];
// 						} else {
// 							$atributos ['valor'] = '';
// 						}
// 						$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
// 						$atributos ['deshabilitado'] = false;
// 						$atributos ['tamanno'] = 15;
// 						$atributos ['maximoTamanno'] = '';
// 						$atributos ['anchoEtiqueta'] = 300;
// 						$tab ++;
							
// 						// Aplica atributos globales al control
// 						$atributos = array_merge ( $atributos, $atributosGlobales );
// 						echo $this->miFormulario->campoCuadroTexto ( $atributos );
// 						unset ( $atributos );
// 						// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
						
						
// 						// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
// 						$esteCampo = "paisExpeRep";
// 						$atributos ['nombre'] = $esteCampo;
// 						$atributos ['id'] = $esteCampo;
// 						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
// 						$atributos ["etiquetaObligatorio"] = true;
// 						$atributos ['tab'] = $tab ++;
// 						$atributos ['anchoEtiqueta'] = 200;
// 						$atributos ['evento'] = '';
// 						if (isset ( $_REQUEST [$esteCampo] )) {
// 							$atributos ['seleccion'] = $_REQUEST [$esteCampo];
// 						} else {
// 							$atributos ['seleccion'] = -1;
// 						}
// 						$atributos ['deshabilitado'] = false;
// 						$atributos ['columnas'] = 3;
// 						$atributos ['tamanno'] = 1;
// 						$atributos ['estilo'] = "jqueryui";
// 						$atributos ['validar'] = "required";
// 						$atributos ['limitar'] = false;
// 						$atributos ['anchoCaja'] = 60;
// 						$atributos ['miEvento'] = '';
						
// 						$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "consultarPaises" );
// 						$matrizItems = $coreRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
// 						/* Valores a mostrar en el control
// 						 $matrizItems = array (
// 						 array ( 1, 'Ahorros' ),
// 						 array ( 2, 'Corriente' )
// 						 );*/
// 						$atributos ['matrizItems'] = $matrizItems;
// 						$atributos = array_merge ( $atributos, $atributosGlobales );
// 						echo $this->miFormulario->campoCuadroLista ( $atributos );
// 						unset ( $atributos );
// 						// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
						
						
// 						// ---------------- CONTROL: Select --------------------------------------------------------
// 						$esteCampo = 'departamentoExpeRep';
// 						$atributos['nombre'] = $esteCampo;
// 						$atributos['id'] = $esteCampo;
// 						$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
// 						$atributos['tab'] = $tab;
// 						$atributos ['anchoEtiqueta'] = 200;
// 						$atributos['seleccion'] = -1;
// 						$atributos['evento'] = ' ';
// 						$atributos['deshabilitado'] = true;
// 						$atributos['limitar']= 50;
// 						$atributos['tamanno']= 1;
// 						$atributos['columnas']= 3;
							
// 						$atributos ['obligatorio'] = true;
// 						$atributos ['etiquetaObligatorio'] = true;
// 						$atributos ['validar'] = 'required';
							
// 						$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarDepartamento" );
// 						$matrizItems = $coreRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
							
// 						$atributos['matrizItems'] = $matrizItems;
							
// 						if (isset ( $_REQUEST [$esteCampo] )) {
// 							$atributos ['valor'] = $_REQUEST [$esteCampo];
// 						} else {
// 							$atributos ['valor'] = '';
// 						}
// 						$tab ++;
							
// 						// Aplica atributos globales al control
// 						$atributos = array_merge ( $atributos, $atributosGlobales );
// 						echo $this->miFormulario->campoCuadroLista ( $atributos );
// 						// --------------- FIN CONTROL : Select --------------------------------------------------
							
// 						// ---------------- CONTROL: Select --------------------------------------------------------
// 						$esteCampo = 'ciudadExpeRep';
// 						$atributos['nombre'] = $esteCampo;
// 						$atributos['id'] = $esteCampo;
// 						$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
// 						$atributos['tab'] = $tab;
// 						$atributos ['anchoEtiqueta'] = 200;
// 						$atributos['seleccion'] = -1;
// 						$atributos['evento'] = ' ';
// 						$atributos['deshabilitado'] = true;
// 						$atributos['limitar']= 50;
// 						$atributos['tamanno']= 1;
// 						$atributos['columnas']= 3;
							
// 						$atributos ['obligatorio'] = true;
// 						$atributos ['etiquetaObligatorio'] = true;
// 						$atributos ['validar'] = 'required';
							
// 						$matrizItems=array(
// 								array(1,'Bogota D.C.'),
// 								array(2,'Medellin'),
// 								array(3,'Barranquilla'),
// 								array(4,'Cali'),
// 								array(5,'Cucuta'),
// 								array(6,'Bucaramanga')
									
// 						);
// 						$atributos['matrizItems'] = $matrizItems;
							
// 						if (isset ( $_REQUEST [$esteCampo] )) {
// 							$atributos ['valor'] = $_REQUEST [$esteCampo];
// 						} else {
// 							$atributos ['valor'] = '';
// 						}
// 						$tab ++;
							
// 						// Aplica atributos globales al control
// 						$atributos = array_merge ( $atributos, $atributosGlobales );
// 						echo $this->miFormulario->campoCuadroLista ( $atributos );
						
						
						
// 						echo $this->miFormulario->marcoAgrupacion ( 'fin' );
						
						
						
						
						
						
						
						
						
						
						
						
						
						

						
// 						// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
// 						$esteCampo = "genero";
// 						$atributos ['nombre'] = $esteCampo;
// 						$atributos ['id'] = $esteCampo;
// 						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
// 						$atributos ["etiquetaObligatorio"] = true;
// 						$atributos ['tab'] = $tab ++;
// 						$atributos ['anchoEtiqueta'] = 200;
// 						$atributos ['evento'] = '';
// 						if (isset ( $_REQUEST [$esteCampo] )) {
// 							$atributos ['seleccion'] = $_REQUEST [$esteCampo];
// 						} else {
// 							$atributos ['seleccion'] = -1;
// 						}
// 						$atributos ['deshabilitado'] = false;
// 						$atributos ['columnas'] = 1;
// 						$atributos ['tamanno'] = 1;
// 						$atributos ['estilo'] = "jqueryui";
// 						$atributos ['validar'] = "required";
// 						$atributos ['limitar'] = false;
// 						$atributos ['anchoCaja'] = 60;
// 						$atributos ['miEvento'] = '';
						
// 						// Valores a mostrar en el control
// 						$matrizItems = array (
// 								array ( 1, 'Masculino' ),
// 								array ( 2, 'Femenino' )
// 						);
// 						$atributos ['matrizItems'] = $matrizItems;
// 						$atributos = array_merge ( $atributos, $atributosGlobales );
// 						echo $this->miFormulario->campoCuadroLista ( $atributos );
// 						unset ( $atributos );
// 						// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
						
						
// 						// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
// 						$esteCampo = 'cargo';
// 						$atributos ['id'] = $esteCampo;
// 						$atributos ['nombre'] = $esteCampo;
// 						$atributos ['tipo'] = 'text';
// 						$atributos ['estilo'] = 'jqueryui mayuscula';
// 						$atributos ['marco'] = true;
// 						$atributos ['estiloMarco'] = '';
// 						$atributos ["etiquetaObligatorio"] = true;
// 						$atributos ['columnas'] = 2;
// 						$atributos ['dobleLinea'] = 0;
// 						$atributos ['tabIndex'] = $tab;
// 						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
// 						$atributos ['validar'] = 'required, minSize[1],maxSize[30]';
						
// 						if (isset ( $_REQUEST [$esteCampo] )) {
// 							$atributos ['valor'] = $_REQUEST [$esteCampo];
// 						} else {
// 							$atributos ['valor'] = '';
// 						}
// 						$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
// 						$atributos ['deshabilitado'] = false;
// 						$atributos ['tamanno'] = 30;
// 						$atributos ['maximoTamanno'] = '';
// 						$atributos ['anchoEtiqueta'] = 200;
// 						$tab ++;
						
// 						// Aplica atributos globales al control
// 						$atributos = array_merge ( $atributos, $atributosGlobales );
// 						echo $this->miFormulario->campoCuadroTexto ( $atributos );
// 						unset ( $atributos );
// 						// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
						
// 						// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
// 						$esteCampo = 'correoPer';
// 						$atributos ['id'] = $esteCampo;
// 						$atributos ['nombre'] = $esteCampo;
// 						$atributos ['tipo'] = 'text';
// 						$atributos ['estilo'] = 'jqueryui';
// 						$atributos ['marco'] = true;
// 						$atributos ['estiloMarco'] = '';
// 						$atributos ["etiquetaObligatorio"] = true;
// 						$atributos ['columnas'] = 2;
// 						$atributos ['dobleLinea'] = 0;
// 						$atributos ['tabIndex'] = $tab;
// 						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
// 						$atributos ['validar'] = 'required, custom[email], maxSize[320]';
						
// 						if (isset ( $_REQUEST [$esteCampo] )) {
// 							$atributos ['valor'] = $_REQUEST [$esteCampo];
// 						} else {
// 							$atributos ['valor'] = '';
// 						}
// 						$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
// 						$atributos ['deshabilitado'] = false;
// 						$atributos ['tamanno'] = 30;
// 						$atributos ['maximoTamanno'] = '320';
// 						$atributos ['anchoEtiqueta'] = 200;
// 						$tab ++;
						
// 						// Aplica atributos globales al control
// 						$atributos = array_merge ( $atributos, $atributosGlobales );
// 						echo $this->miFormulario->campoCuadroTexto ( $atributos );
// 						unset ( $atributos );
// 						// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
						
// 						// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
// 						$esteCampo = "paisNacimiento";
// 						$atributos ['nombre'] = $esteCampo;
// 						$atributos ['id'] = $esteCampo;
// 						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
// 						$atributos ["etiquetaObligatorio"] = true;
// 						$atributos ['tab'] = $tab ++;
// 						$atributos ['anchoEtiqueta'] = 200;
// 						$atributos ['evento'] = '';
// 						if (isset ( $_REQUEST [$esteCampo] )) {
// 							$atributos ['seleccion'] = $_REQUEST [$esteCampo];
// 						} else {
// 							$atributos ['seleccion'] = -1;
// 						}
// 						$atributos ['deshabilitado'] = false;
// 						$atributos ['columnas'] = 1;
// 						$atributos ['tamanno'] = 1;
// 						$atributos ['estilo'] = "jqueryui";
// 						$atributos ['validar'] = "required";
// 						$atributos ['limitar'] = false;
// 						$atributos ['anchoCaja'] = 60;
// 						$atributos ['miEvento'] = '';
						
// 						$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "consultarPaises" );
// 						$matrizItems = $coreRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
// 						/* Valores a mostrar en el control
// 						 $matrizItems = array (
// 						 array ( 1, 'Ahorros' ),
// 						 array ( 2, 'Corriente' )
// 						 );*/
// 						$atributos ['matrizItems'] = $matrizItems;
// 						$atributos = array_merge ( $atributos, $atributosGlobales );
// 						echo $this->miFormulario->campoCuadroLista ( $atributos );
// 						unset ( $atributos );
// 						// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
						
// 						// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
// 						$esteCampo = "perfil";
// 						$atributos ['nombre'] = $esteCampo;
// 						$atributos ['id'] = $esteCampo;
// 						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
// 						$atributos ["etiquetaObligatorio"] = true;
// 						$atributos ['tab'] = $tab ++;
// 						$atributos ['anchoEtiqueta'] = 200;
// 						$atributos ['evento'] = '';
// 						if (isset ( $_REQUEST [$esteCampo] )) {
// 							$atributos ['seleccion'] = $_REQUEST [$esteCampo];
// 						} else {
// 							$atributos ['seleccion'] = -1;
// 						}
// 						$atributos ['deshabilitado'] = false;
// 						$atributos ['columnas'] = 1;
// 						$atributos ['tamanno'] = 1;
// 						$atributos ['estilo'] = "jqueryui";
// 						$atributos ['validar'] = "required";
// 						$atributos ['limitar'] = false;
// 						$atributos ['anchoCaja'] = 60;
// 						$atributos ['miEvento'] = '';
						
// 						// Valores a mostrar en el control
// 						$matrizItems = array (
// 								array ( 1, 'Asistencial' ),
// 								array ( 2, 'Técnico' ),
// 								array ( 3, 'Profesional' ),
// 								array ( 4, 'Profesional Especializado' ),
// 								array ( 6, 'Asesor 1' ),
// 								array ( 7, 'Asesor 2' ),
// 								array ( 5, 'No Aplica' )
// 						);
// 						$atributos ['matrizItems'] = $matrizItems;
// 						$atributos = array_merge ( $atributos, $atributosGlobales );
// 						echo $this->miFormulario->campoCuadroLista ( $atributos );
// 						unset ( $atributos );
// 						// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
						
// 						echo "<br>";
// 						echo "<br>";
// 						echo "<br>";
// 						echo "<br>";
// 						echo "<br>";
// 						echo "<br>";
// 						echo "<br>";
// 						echo "<br>";
// 						echo "<br>";
// 						echo "<br>";
						
// 						$atributos ["id"] = "obligatorioProfesion";
// 						$atributos ["estilo"] = "Marco";
// 						echo $this->miFormulario->division ( "inicio", $atributos );
// 						unset ( $atributos );
// 						{
							
// 							// ---------------- CONTROL: Select --------------------------------------------------------
// 							$esteCampo = 'personaArea';
// 							$atributos['nombre'] = $esteCampo;
// 							$atributos['id'] = $esteCampo;
// 							$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
// 							$atributos['tab'] = $tab;
// 							$atributos['seleccion'] = -1;
// 							$atributos['evento'] = ' ';
// 							$atributos['deshabilitado'] = false;
// 							$atributos['limitar']= 50;
// 							$atributos['tamanno']= 1;
// 							$atributos['columnas']= 1;
// 							$atributos ['anchoEtiqueta'] = 350;
							
// 							$atributos ['obligatorio'] = true;
// 							$atributos ['etiquetaObligatorio'] = true;
// 							$atributos ['validar'] = 'required';
							
// 							$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarAreaConocimiento" );
// 							$matrizItems = $coreRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
							
// 							$atributos['matrizItems'] = $matrizItems;
							
// 							if (isset ( $_REQUEST [$esteCampo] )) {
// 								$atributos ['valor'] = $_REQUEST [$esteCampo];
// 							} else {
// 								$atributos ['valor'] = '';
// 							}
// 							$tab ++;
							
// 							// Aplica atributos globales al control
// 							$atributos = array_merge ( $atributos, $atributosGlobales );
// 							echo $this->miFormulario->campoCuadroLista ( $atributos );
// 							// --------------- FIN CONTROL : Select --------------------------------------------------
							
// 							// ---------------- CONTROL: Select --------------------------------------------------------
// 							$esteCampo = 'personaNBC';
// 							$atributos['nombre'] = $esteCampo;
// 							$atributos['id'] = $esteCampo;
// 							$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
// 							$atributos['tab'] = $tab;
// 							$atributos['seleccion'] = -1;
// 							$atributos['evento'] = ' ';
// 							$atributos['deshabilitado'] = true;
// 							$atributos['limitar']= 50;
// 							$atributos['tamanno']= 1;
// 							$atributos['columnas']= 1;
// 							$atributos ['anchoEtiqueta'] = 350;
							
// 							$atributos ['obligatorio'] = true;
// 							$atributos ['etiquetaObligatorio'] = true;
// 							$atributos ['validar'] = 'required';
							
// 							$matrizItems=array(
// 									array(1,'Test A'),
// 									array(2,'Test B'),
							
// 							);
// 							$atributos['matrizItems'] = $matrizItems;
							
// 							if (isset ( $_REQUEST [$esteCampo] )) {
// 								$atributos ['valor'] = $_REQUEST [$esteCampo];
// 							} else {
// 								$atributos ['valor'] = '';
// 							}
// 							$tab ++;
							
// 							// Aplica atributos globales al control
// 							$atributos = array_merge ( $atributos, $atributosGlobales );
// 							echo $this->miFormulario->campoCuadroLista ( $atributos );
							
							
// 							// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
// 							$esteCampo = 'profesion';
// 							$atributos ['id'] = $esteCampo;
// 							$atributos ['nombre'] = $esteCampo;
// 							$atributos ['tipo'] = 'text';
// 							$atributos ['estilo'] = 'jqueryui mayuscula';
// 							$atributos ['marco'] = true;
// 							$atributos ['estiloMarco'] = '';
// 							$atributos ["etiquetaObligatorio"] = true;
// 							$atributos ['columnas'] = 1;
// 							$atributos ['dobleLinea'] = 0;
// 							$atributos ['tabIndex'] = $tab;
// 							$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
// 							$atributos ['validar'] = 'required, minSize[1],maxSize[40]';
							
// 							if (isset ( $_REQUEST [$esteCampo] )) {
// 								$atributos ['valor'] = $_REQUEST [$esteCampo];
// 							} else {
// 								$atributos ['valor'] = '';
// 							}
// 							$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
// 							$atributos ['deshabilitado'] = false;
// 							$atributos ['tamanno'] = 40;
// 							$atributos ['maximoTamanno'] = '';
// 							$atributos ['anchoEtiqueta'] = 350;
// 							$tab ++;
							
// 							// Aplica atributos globales al control
// 							$atributos = array_merge ( $atributos, $atributosGlobales );
// 							echo $this->miFormulario->campoCuadroTexto ( $atributos );
// 							unset ( $atributos );
// 							// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
// 						}
// 						echo $this->miFormulario->division ( "fin");
						
// 						$atributos ["id"] = "obligatorioEspecialidad";
// 						$atributos ["estilo"] = "Marco";
// 						echo $this->miFormulario->division ( "inicio", $atributos );
// 						unset ( $atributos );
// 						{
// 							// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
// 							$esteCampo = 'especialidad';
// 							$atributos ['id'] = $esteCampo;
// 							$atributos ['nombre'] = $esteCampo;
// 							$atributos ['tipo'] = 'text';
// 							$atributos ['estilo'] = 'jqueryui mayuscula';
// 							$atributos ['marco'] = true;
// 							$atributos ['estiloMarco'] = '';
// 							$atributos ["etiquetaObligatorio"] = true;
// 							$atributos ['columnas'] = 1;
// 							$atributos ['dobleLinea'] = 0;
// 							$atributos ['tabIndex'] = $tab;
// 							$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
// 							$atributos ['validar'] = 'required, minSize[1],maxSize[40]';
							
// 							if (isset ( $_REQUEST [$esteCampo] )) {
// 								$atributos ['valor'] = $_REQUEST [$esteCampo];
// 							} else {
// 								$atributos ['valor'] = '';
// 							}
// 							$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
// 							$atributos ['deshabilitado'] = false;
// 							$atributos ['tamanno'] = 40;
// 							$atributos ['maximoTamanno'] = '';
// 							$atributos ['anchoEtiqueta'] = 350;
// 							$tab ++;
							
// 							// Aplica atributos globales al control
// 							$atributos = array_merge ( $atributos, $atributosGlobales );
// 							echo $this->miFormulario->campoCuadroTexto ( $atributos );
// 							unset ( $atributos );
// 							// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
// 						}
// 						echo $this->miFormulario->division ( "fin");
						
// 						// ---------------- CONTROL: Cuadro de Texto NIT--------------------------------------------------------
// 						$esteCampo = 'numeroContacto';
// 						$atributos ['id'] = $esteCampo;
// 						$atributos ['nombre'] = $esteCampo;
// 						$atributos ['tipo'] = 'text';
// 						$atributos ['estilo'] = 'jqueryui';
// 						$atributos ['marco'] = true;
// 						$atributos ['estiloMarco'] = '';
// 						$atributos ["etiquetaObligatorio"] = true;
// 						$atributos ['columnas'] = 1;
// 						$atributos ['dobleLinea'] = 0;
// 						$atributos ['tabIndex'] = $tab;
// 						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
// 						$atributos ['validar'] = 'required, minSize[7],maxSize[10],custom[onlyNumberSp]';
						
// 						if (isset ( $_REQUEST [$esteCampo] )) {
// 							$atributos ['valor'] = $_REQUEST [$esteCampo];
// 						} else {
// 							$atributos ['valor'] = '';
// 						}
// 						$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
// 						$atributos ['deshabilitado'] = false;
// 						$atributos ['tamanno'] = 15;
// 						$atributos ['maximoTamanno'] = '';
// 						$atributos ['anchoEtiqueta'] = 200;
// 						$tab ++;
						
// 						// Aplica atributos globales al control
// 						$atributos = array_merge ( $atributos, $atributosGlobales );
// 						echo $this->miFormulario->campoCuadroTexto ( $atributos );
// 						unset ( $atributos );
// 						// ---------------- FIN CONTROL: Cuadro de Texto  NIT--------------------------------------------------------
						
						
															
// 				echo $this->miFormulario->marcoAgrupacion ( 'fin' );
				
				
				
				
				
				
				
				
				
				$esteCampo = "marcoFinanciero";
				$atributos ['id'] = $esteCampo;
				$atributos ["estilo"] = "jqueryui";
				$atributos ['tipoEtiqueta'] = 'inicio';
				$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
				echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
						
						// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
						$esteCampo = "tipoCuenta";
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
							$atributos ['seleccion'] = -1;
						}
						$atributos ['deshabilitado'] = false;
						$atributos ['columnas'] = 1;
						$atributos ['tamanno'] = 1;
						$atributos ['estilo'] = "jqueryui";
						$atributos ['validar'] = "required";
						$atributos ['limitar'] = false;
						$atributos ['anchoCaja'] = 60;
						$atributos ['miEvento'] = '';
						
						// Valores a mostrar en el control
						$matrizItems = array (
								array ( 1, 'Ahorros' ),
								array ( 2, 'Corriente' ),
								array ( 3, 'Extranjera' ),
								array ( 4, 'No Aplica' )
						);
						$atributos ['matrizItems'] = $matrizItems;
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroLista ( $atributos );
						unset ( $atributos );
						// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
				
						echo "<br>";
						echo "<br>";
						echo "<br>";
						
						$atributos ["id"] = "infoBancos";
						$atributos ["estilo"] = "";
						echo $this->miFormulario->division ( "inicio", $atributos );
						unset ( $atributos );
						{

							// ---------------- CONTROL: Cuadro de Texto NIT--------------------------------------------------------
							$esteCampo = 'numeroCuenta';
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
							$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
							$atributos ['validar'] = 'required,minSize[1],maxSize[15],custom[onlyNumberSp]';
							
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else {
								$atributos ['valor'] = '';
							}
							$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
							$atributos ['deshabilitado'] = false;
							$atributos ['tamanno'] = 15;
							$atributos ['maximoTamanno'] = '';
							$atributos ['anchoEtiqueta'] = 200;
							$tab ++;
							
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroTexto ( $atributos );
							unset ( $atributos );
							// ---------------- FIN CONTROL: Cuadro de Texto  NIT--------------------------------------------------------
							
							
							// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
							$esteCampo = "entidadBancaria";
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
								$atributos ['seleccion'] = -1;
							}
							$atributos ['deshabilitado'] = false;
							$atributos ['columnas'] = 2;
							$atributos ['tamanno'] = 1;
							$atributos ['estilo'] = "jqueryui";
							$atributos ['validar'] = "required";
							$atributos ['limitar'] = false;
							$atributos ['anchoCaja'] = 60;
							$atributos ['miEvento'] = '';
							
							$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "consultarBanco" );
							$matrizItems = $coreRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
							/* Valores a mostrar en el control
							$matrizItems = array (
									array ( 1, 'Ahorros' ),
									array ( 2, 'Corriente' )
							);*/
							$atributos ['matrizItems'] = $matrizItems;
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroLista ( $atributos );
							unset ( $atributos );
							// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
				
						}
						echo $this->miFormulario->division ( 'fin' );
						
						
						
				
				echo $this->miFormulario->marcoAgrupacion ( 'fin' );
				
				
				
				
				$esteCampo = "marcoCIIUReg";
				$atributos ['id'] = $esteCampo;
				$atributos ["estilo"] = "jqueryui";
				$atributos ['tipoEtiqueta'] = 'inicio';
				$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
				echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
				
				
				
				// ---------------- SECCION: Controles del Formulario -----------------------------------------------
				$esteCampo = 'mensaje';
					
				$tipo = 'warning';
					
				$atributos["id"] = $esteCampo; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
				$atributos["etiqueta"] = "";
				$atributos["estilo"] = "centrar";
				$atributos["tipo"] = $tipo;
					
				$mensajeLey = "<b>Recuerde que se solicita relacionar Una (1) Actividad Económica como mínimo para tener un
						registro exitoso e identificar los servicios que presta la empresa, relacione una actividad que represente 
						lo mejor posible los servicios que desarrolla la compañia extranjera según su criterio.</b>";
					
				$atributos["mensaje"] = $mensajeLey;
				echo $this->miFormulario->cuadroMensaje($atributos);
				unset($atributos);
				
				
				
				
				// ---------------- CONTROL: Lista clase CIIU--------------------------------------------------------
				$esteCampo = "claseCIIUJur";
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
				$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
				$atributos ['cadena_sql'] = $cadenaSql = $this->miSql->getCadenaSql ( 'ciiuSubClase' );
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
				$atributos ['columnas'] = 90;
				$atributos ['filas'] = 2;
				$atributos ['dobleLinea'] = 0;
				$atributos ['tabIndex'] = $tab;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ['validar'] = 'required,maxSize[250]';
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
					echo $this->miFormulario->campoBoton ( $atributos );
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
