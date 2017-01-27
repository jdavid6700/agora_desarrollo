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

			$atributos ["id"] = "selecPerson";
			$atributos ["estilo"] = "marcoSelect";
			echo $this->miFormulario->division ( "inicio", $atributos );
			unset ( $atributos );
			{
			
				// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
				$esteCampo = "tipoPersona";
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
				$atributos ['columnas'] = 12;
				$atributos ['tamanno'] = 1;
				$atributos ['estilo'] = "jqueryui";
				$atributos ['validar'] = "required";
				$atributos ['limitar'] = false;
				$atributos ['anchoCaja'] = 60;
				$atributos ['miEvento'] = '';
				// Valores a mostrar en el control
				$matrizItems = array (
						array ( 1, 'Natural' ),
						array ( 2, 'Jurídica' )
				);
				$atributos ['matrizItems'] = $matrizItems;
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroLista ( $atributos );
				unset ( $atributos );
				// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
			
			}
			echo $this->miFormulario->division ( 'fin' );
			
			echo "<br>";
			echo "<br>";
			echo "<br>";
			
			
			//********************************************************************************************** PERSONA JURIDICA****************************
                        
			$esteCampo = "marcoDatosJuridica";
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
			$atributos ['tipoEtiqueta'] = 'inicio';
			$atributos ["leyenda"] =  $this->lenguaje->getCadena ( $esteCampo );
			echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
			unset ( $atributos );
			{	
			
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
					
					
						// ---------------- CONTROL: Lista NACIONALIDAD Empresa --------------------------------------------------------
						$esteCampo = "paisEmpresa";
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
								array ( 1, 'Nacional' ),
								array ( 2, 'Extranjero' )
						);
						$atributos ['matrizItems'] = $matrizItems;
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroLista ( $atributos );
						unset ( $atributos );
						// ----------------FIN CONTROL: Lista NACIONALIDAD Empresa--------------------------------------------------------
						
						echo "<br>";
						echo "<br>";
						echo "<br>";
						echo "<br>";
						echo "<br>";
						echo "<br>";
						echo "<br>";
						
						$esteCampo = "marcoProcedencia";
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
							echo $this->miFormulario->campoCuadroTexto ( $atributos );
							unset ( $atributos );
							// ---------------- FIN CONTROL: Cuadro de Texto  Codigo Postal--------------------------------------------------------
								
							// ---------------- CONTROL: Lista Tipo Identificacion Empresa --------------------------------------------------------
							$esteCampo = "tipoIdentifiExtranjera";
							$atributos ['nombre'] = $esteCampo;
							$atributos ['id'] = $esteCampo;
							$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
							$atributos ["etiquetaObligatorio"] = true;
							$atributos ['tab'] = $tab ++;
							$atributos ['anchoEtiqueta'] = 300;
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
									array ( 1, 'Cédula de extranjería' ),
									array ( 2, 'Pasaporte' )
							);
							$atributos ['matrizItems'] = $matrizItems;
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroLista ( $atributos );
							unset ( $atributos );
							// ----------------FIN CONTROL: Lista Tipo Identificacion Empresa--------------------------------------------------------
							
							echo "<br>";
							echo "<br>";
							echo "<br>";
							echo "<br>";
							echo "<br>";
							echo "<br>";
							echo "<br>";
							echo "<br>";
							echo "<br>";
							echo "<br>";
							echo "<br>";
							echo "<br>";
							
							$atributos ["id"] = "obligatorioCedula";
							$atributos ["estilo"] = "Marco";
							echo $this->miFormulario->division ( "inicio", $atributos );
							unset ( $atributos );
							{
								
								// ---------------- CONTROL: Cuadro de Texto CEDULA EXTRANJERIA--------------------------------------------------------
								$esteCampo = 'cedulaExtranjeria';
								$atributos ['id'] = $esteCampo;
								$atributos ['nombre'] = $esteCampo;
								$atributos ['tipo'] = 'text';
								$atributos ['estilo'] = 'jqueryui';
								$atributos ['marco'] = true;
								$atributos ['estiloMarco'] = '';
								$atributos ["etiquetaObligatorio"] = true;
								$atributos ['columnas'] = 1;
								$atributos ['dobleLinea'] = 0;
								$atributos ['tabIndex'] = $tab ++;
								$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
								$atributos ['validar'] = 'required, minSize[1],maxSize[30],custom[onlyNumberSp]';
									
								if (isset ( $_REQUEST [$esteCampo] )) {
									$atributos ['valor'] = $_REQUEST [$esteCampo];
								} else {
									$atributos ['valor'] = '';
								}
								$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
								$atributos ['deshabilitado'] = false;
								$atributos ['tamanno'] = 30;
								$atributos ['maximoTamanno'] = '';
								$atributos ['anchoEtiqueta'] = 190;
								$tab ++;
									
								// Aplica atributos globales al control
								$atributos = array_merge ( $atributos, $atributosGlobales );
								echo $this->miFormulario->campoCuadroTexto ( $atributos );
								unset ( $atributos );
								// ---------------- FIN CONTROL: Cuadro de Texto  CEDULA EXTRANJERIA--------------------------------------------------------				
								
							}
							echo $this->miFormulario->division ( 'fin' );
							
							$atributos ["id"] = "obligatorioPasaporte";
							$atributos ["estilo"] = "Marco";
							echo $this->miFormulario->division ( "inicio", $atributos );
							unset ( $atributos );
							{
							
								// ---------------- CONTROL: Cuadro de Texto  PASAPORTE--------------------------------------------------------
								$esteCampo = 'pasaporte';
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
								$atributos ['validar'] = 'required, minSize[1],maxSize[30],custom[onlyNumberSp]';
									
								if (isset ( $_REQUEST [$esteCampo] )) {
									$atributos ['valor'] = $_REQUEST [$esteCampo];
								} else {
									$atributos ['valor'] = '';
								}
								$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
								$atributos ['deshabilitado'] = false;
								$atributos ['tamanno'] = 30;
								$atributos ['maximoTamanno'] = '';
								$atributos ['anchoEtiqueta'] = 190;
								$tab ++;
									
								// Aplica atributos globales al control
								$atributos = array_merge ( $atributos, $atributosGlobales );
								echo $this->miFormulario->campoCuadroTexto ( $atributos );
								unset ( $atributos );
								// ---------------- FIN CONTROL: Cuadro de Texto  PASAPORTE--------------------------------------------------------
							
							
							}
							echo $this->miFormulario->division ( 'fin' );
							
						}				
						echo $this->miFormulario->marcoAgrupacion ( 'fin' );
						
									
						
				echo $this->miFormulario->marcoAgrupacion ( 'fin' );			
			
			
				$esteCampo = "marcoContacto";
				$atributos ['id'] = $esteCampo;
				$atributos ["estilo"] = "jqueryui";
				$atributos ['tipoEtiqueta'] = 'inicio';
				$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
				echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );                    
				
				
						// ---------------- CONTROL: Select --------------------------------------------------------
						$esteCampo = 'departamento';
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
							
						$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarDepartamento" );
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
						$esteCampo = 'ciudad';
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
						
						
						
						$esteCampo = "marcoDatosDireccion";
						$atributos ['id'] = $esteCampo;
						$atributos ["estilo"] = "jqueryui";
						$atributos ['tipoEtiqueta'] = 'inicio';
						$atributos ["leyenda"] = "Dirección Tipo DIAN";
						echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
						{
						
							$atributos ["id"] = "panelDireccion";
							$atributos ["estilo"] = "row";
							echo $this->miFormulario->division ( "inicio", $atributos );
							{
								$atributos ["id"] = "ingresoDireccion";
								$atributos ["estilo"] = "col-md-6";
								echo $this->miFormulario->division ( "inicio", $atributos );
								{
									// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
									$esteCampo = 'direccion';
									$atributos ['id'] = $esteCampo;
									$atributos ['nombre'] = $esteCampo;
									$atributos ['estilo'] = '';
									$atributos ['marco'] = false;
									$atributos ['correccion'] = false;
									$atributos ['columnas'] = 50;
									$atributos ['filas'] = 4;
									$atributos ['tabIndex'] = $tab;
									$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
									$atributos ['anchoEtiqueta'] = 150;
									$atributos ['deshabilitado'] =false;
										
									$atributos ['obligatorio'] = true;
									$atributos ['etiquetaObligatorio'] = true;
									$atributos ['validar'] = 'required, minSize[1], maxSize[5]';
										
									if (isset ( $_REQUEST [$esteCampo] )) {
										$atributos ['valor'] = $_REQUEST [$esteCampo];
									} else {
										$atributos ['valor'] = '';
									}
									$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
									$tab ++;
										
									// Aplica atributos globales al control
									$atributos = array_merge ( $atributos, $atributosGlobales );
									echo $this->miFormulario->campoTextArea ( $atributos );
									// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
						
									unset($atributos);
						
									unset($atributos);
						
									$atributos ["id"] = "ingresoBotones";
									$atributos ["estilo"] = "col-md-12";
									echo $this->miFormulario->division ( "inicio", $atributos );
									{
						
										$atributos ["id"] = "botonesPanel";
										$atributos ["estilo"] = "col-md-12 btn-group";
										echo $this->miFormulario->division ( "inicio", $atributos );
										{
											echo "<input type=\"button\" id=\"btOper1\" value=\"A\" class=\"btn btn-primary btn-xs\"/>";
											echo "<input type=\"button\" id=\"btOper2\" value=\"B\" class=\"btn btn-primary btn-xs\" />";
											echo "<input type=\"button\" id=\"btOper3\" value=\"C\" class=\"btn btn-primary btn-xs\"/>";
											echo "<input type=\"button\" id=\"btOper4\" value=\"D\" class=\"btn btn-primary btn-xs\" />";
											echo "<input type=\"button\" id=\"btOper5\" value=\"E\" class=\"btn btn-primary btn-xs\"/>";
											echo "<input type=\"button\" id=\"btOper6\" value=\"F\" class=\"btn btn-primary btn-xs\" />";
											echo "<input type=\"button\" id=\"btOper7\" value=\"G\" class=\"btn btn-primary btn-xs\"/>";
											echo "<input type=\"button\" id=\"btOper8\" value=\"H\" class=\"btn btn-primary btn-xs\" />";
											echo "<input type=\"button\" id=\"btOper9\" value=\"I\" class=\"btn btn-primary btn-xs\" />";
											echo "<input type=\"button\" id=\"btOper10\" value=\"J\" class=\"btn btn-primary btn-xs\" />";
											echo "<input type=\"button\" id=\"btOper11\" value=\"K\" class=\"btn btn-primary btn-xs\" />";
											echo "<input type=\"button\" id=\"btOper12\" value=\"L\" class=\"btn btn-primary btn-xs\" />";
											
											echo "<input type=\"button\" id=\"btOper15\" value=\"M\" class=\"btn btn-primary btn-xs\" />";
											echo "<input type=\"button\" id=\"btOper16\" value=\"N\" class=\"btn btn-primary btn-xs\" />";
											echo "<input type=\"button\" id=\"btOper17\" value=\"O\" class=\"btn btn-primary btn-xs\" />";
											echo "<input type=\"button\" id=\"btOper18\" value=\"P\" class=\"btn btn-primary btn-xs\" />";
											echo "<input type=\"button\" id=\"btOper19\" value=\"Q\" class=\"btn btn-primary btn-xs\" />";
											echo "<input type=\"button\" id=\"btOper20\" value=\"R\" class=\"btn btn-primary btn-xs\" />";
											echo "<input type=\"button\" id=\"btOper21\" value=\"S\" class=\"btn btn-primary btn-xs\" />";
											echo "<input type=\"button\" id=\"btOper22\" value=\"T\" class=\"btn btn-primary btn-xs\" />";
											echo "<input type=\"button\" id=\"btOper23\" value=\"U\" class=\"btn btn-primary btn-xs\" />";
											echo "<input type=\"button\" id=\"btOper24\" value=\"V\" class=\"btn btn-primary btn-xs\" />";
											echo "<input type=\"button\" id=\"btOper25\" value=\"W\" class=\"btn btn-primary btn-xs\" />";
											echo "<input type=\"button\" id=\"btOper26\" value=\"X\" class=\"btn btn-primary btn-xs\" />";
											echo "<input type=\"button\" id=\"btOper27\" value=\"Y\" class=\"btn btn-primary btn-xs\" />";
											echo "<input type=\"button\" id=\"btOper28\" value=\"Z\" class=\"btn btn-primary btn-xs\" />";
											
											echo "<input type=\"button\" id=\"btOper13\" value=\"Borrar\" class=\"btn btn-danger btn-xs\" />";
										}
										echo $this->miFormulario->division ( "fin" );
						
									}
									echo $this->miFormulario->division ( "fin" );
						
						
						
						
						
								}
								echo $this->miFormulario->division ( "fin" );
									
								$atributos ["id"] = "variables";
								$atributos ["estilo"] = "col-md-6";
								echo $this->miFormulario->division ( "inicio", $atributos );
								{
									unset($atributos);
										
										
									$esteCampo = "marcoDatosParametros";
									$atributos ['id'] = $esteCampo;
									$atributos ["estilo"] = "jqueryui";
									$atributos ['tipoEtiqueta'] = 'inicio';
									$atributos ["leyenda"] = "Panel Nomenclaturas DIAN";
									echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
									{
											
										$atributos ["id"] = "listaNomenclaturasNatural";
										$atributos ["estilo"] = "col-md-12";
										echo $this->miFormulario->division ( "inicio", $atributos );
										{
											// ---------------- CONTROL: Select --------------------------------------------------------
											$esteCampo = 'listaNomenclaturas';
											$atributos['nombre'] = $esteCampo;
											$atributos['id'] = $esteCampo;
											$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
											$atributos ['anchoEtiqueta'] = 230;
											$atributos['tab'] = $tab;
											$atributos['seleccion'] = -1;
											$atributos['evento'] = ' ';
											$atributos['deshabilitado'] = false;
											$atributos['limitar']= 50;
											$atributos['tamanno']= 1;
											$atributos['columnas']= 1;
												
											$atributos ['obligatorio'] = false;
											$atributos ['etiquetaObligatorio'] = false;
											$atributos ['validar'] = '';
												
						
											$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarNomenclaturas" );
											$matrizParametros = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
												
											$atributos['matrizItems'] = $matrizParametros;
												
											if (isset ( $_REQUEST [$esteCampo] )) {
												$atributos ['valor'] = $_REQUEST [$esteCampo];
											} else {
												$atributos ['valor'] = '';
											}
											$atributos ["titulo"] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
											$tab ++;
												
											// Aplica atributos globales al control
											$atributos = array_merge ( $atributos, $atributosGlobales );
											echo $this->miFormulario->campoCuadroLista ( $atributos );
											// --------------- FIN CONTROL : Select --------------------------------------------------
										}
										echo $this->miFormulario->division ( "fin" );
											
										unset($atributos);
											
										$atributos ["id"] = "parametros";
										$atributos ["estilo"] = "col-md-10";
										echo $this->miFormulario->division ( "inicio", $atributos );
										{
											// ---------------- CONTROL: Select --------------------------------------------------------
											$esteCampo = 'seccionParametros';
											$atributos['nombre'] = $esteCampo;
											$atributos['id'] = $esteCampo;
											$atributos['etiqueta'] = '';
											$atributos ['anchoEtiqueta'] = 180;
											$atributos['tab'] = $tab;
											$atributos['seleccion'] = 0;
											$atributos['evento'] = ' ';
											$atributos['deshabilitado'] = true;
											$atributos['limitar']= 50;
											$atributos['tamanno']= 1;
											$atributos['columnas']= 1;
												
											$atributos ['obligatorio'] = false;
											$atributos ['etiquetaObligatorio'] = false;
											$atributos ['validar'] = '';
												
											//$atributos ['cadena_sql'] = $this->miSql->getCadenaSql("buscarCategoriaParametro");
											//$matrizParametros=$primerRecursoDB->ejecutarAcceso($atributos['cadena_sql'], "busqueda");
												
											//$atributos['matrizItems'] = $matrizParametros;
												
											$matrizItems=array(
													array(1,'Nomenclatura'),
													array(2,'TEST'),
														
											);
											$atributos['matrizItems'] = $matrizItems;
												
											if (isset ( $_REQUEST [$esteCampo] )) {
												$atributos ['valor'] = $_REQUEST [$esteCampo];
											} else {
												$atributos ['valor'] = '';
											}
											$atributos ["titulo"] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
											$tab ++;
												
											// Aplica atributos globales al control
											$atributos = array_merge ( $atributos, $atributosGlobales );
											echo $this->miFormulario->campoCuadroLista ( $atributos );
											// --------------- FIN CONTROL : Select --------------------------------------------------
										}
										echo $this->miFormulario->division ( "fin" );
										
										$atributos ["id"] = "botonParametros";
										$atributos ["estilo"] = "col-md-2";
										echo $this->miFormulario->division ( "inicio", $atributos );
										{
											$atributos ["id"] = "botonesPanel";
											$atributos ["estilo"] = "col-md-12 btn-group btn-group-lg";
											echo $this->miFormulario->division ( "inicio", $atributos );
											{
												echo "<input type=\"button\" id=\"btOper14\" value=\"Insertar\" class=\"btn btn-success\" />";
											}
											echo $this->miFormulario->division ( "fin" );
										}
										echo $this->miFormulario->division ( "fin" );
											
									}
									echo $this->miFormulario->marcoAgrupacion ( "fin" );
										
									unset($atributos);
										
								}
								echo $this->miFormulario->division ( "fin" );
									
									
									
							}
							echo $this->miFormulario->division ( "fin" );
						
						}
						echo $this->miFormulario->marcoAgrupacion ( "fin" );
						
						
						
						
						
						
					/*
					// ---------------- CONTROL: Cuadro de Texto  Dirección--------------------------------------------------------
						$esteCampo = 'direccion';
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
					*/
						
						
						$esteCampo = "marcoDatosNotificar";
						$atributos ['id'] = $esteCampo;
						$atributos ["estilo"] = "jqueryui";
						$atributos ['tipoEtiqueta'] = 'inicio';
						$atributos ["leyenda"] = "Información Contacto Directo";
						echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
						
						
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
					
						// ---------------- CONTROL: Cuadro de Texto Correo--------------------------------------------------------
						$esteCampo = 'correoConfirm';
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
						$atributos ['validar'] = 'required, custom[email], maxSize[320], equals[correo]';
							
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
						
						
						echo $this->miFormulario->marcoAgrupacion ( 'fin');
						
		
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

					// ---------------- CONTROL: Cuadro de Texto Teléfono --------------------------------------------------------
						$esteCampo = 'telefono';
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
						$atributos ['validar'] = 'required, minSize[7],maxSize[7],custom[onlyNumberSp]';
						
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
					// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------

					// ---------------- CONTROL: Cuadro de Texto Extensión --------------------------------------------------------
						$esteCampo = 'extension';
						$atributos ['id'] = $esteCampo;
						$atributos ['nombre'] = $esteCampo;
						$atributos ['tipo'] = 'text';
						$atributos ['estilo'] = 'jqueryui';
						$atributos ['marco'] = true;
						$atributos ['estiloMarco'] = '';
						$atributos ["etiquetaObligatorio"] = false;
						$atributos ['columnas'] = 2;
						$atributos ['dobleLinea'] = 0;
						$atributos ['tabIndex'] = $tab;
						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
						$atributos ['validar'] = 'minSize[1],maxSize[4],custom[onlyNumberSp]';
						
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['valor'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['valor'] = '';
						}
						$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
						$atributos ['deshabilitado'] = false;
						$atributos ['tamanno'] = 4;
						$atributos ['maximoTamanno'] = '';
						$atributos ['anchoEtiqueta'] = 160;
						$tab ++;
						
						// Aplica atributos globales al control
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroTexto ( $atributos );
						unset ( $atributos );
					// ---------------- FIN CONTROL: Cuadro de Texto  NIT--------------------------------------------------------
					
					/*	
					// ---------------- CONTROL: Cuadro de Texto Movil--------------------------------------------------------
						$esteCampo = 'movil';
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
						$atributos ['validar'] = 'required, minSize[1],maxSize[12],custom[onlyNumberSp]';
						
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
					// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
					*/

					// ---------------- CONTROL: Cuadro de Texto  Asesor Comercial--------------------------------------------------------
						$esteCampo = 'asesorComercial';
						$atributos ['id'] = $esteCampo;
						$atributos ['nombre'] = $esteCampo;
						$atributos ['tipo'] = 'text';
						$atributos ['estilo'] = 'jqueryui mayuscula';
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
						$atributos ['tamanno'] = 50;
						$atributos ['maximoTamanno'] = '';
						$atributos ['anchoEtiqueta'] = 160;
						$tab ++;
						
						// Aplica atributos globales al control
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroTexto ( $atributos );
						unset ( $atributos );
					// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------

					// ---------------- CONTROL: Cuadro de Texto Teléfono del Asesor--------------------------------------------------------
						$esteCampo = 'telAsesor';
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
						$atributos ['validar'] = 'required, minSize[7],maxSize[7],custom[onlyNumberSp]';
						
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

				echo $this->miFormulario->marcoAgrupacion ( 'fin' );			

				$esteCampo = "marcoRepresentante";
				$atributos ['id'] = $esteCampo;
				$atributos ["estilo"] = "jqueryui";
				$atributos ['tipoEtiqueta'] = 'inicio';
				$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
				echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos ); 
						
				
						




				// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
				$esteCampo = 'primerApellido';
				$atributos ['id'] = $esteCampo;
				$atributos ['nombre'] = $esteCampo;
				$atributos ['tipo'] = 'text';
				$atributos ['estilo'] = 'jqueryui mayuscula';
				$atributos ['marco'] = true;
				$atributos ['estiloMarco'] = '';
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['columnas'] = 2;
				$atributos ['dobleLinea'] = 0;
				$atributos ['tabIndex'] = $tab;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ['validar'] = 'required, minSize[1],maxSize[30]';
				
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
				// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
				
				// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
				$esteCampo = 'segundoApellido';
				$atributos ['id'] = $esteCampo;
				$atributos ['nombre'] = $esteCampo;
				$atributos ['tipo'] = 'text';
				$atributos ['estilo'] = 'jqueryui mayuscula';
				$atributos ['marco'] = true;
				$atributos ['estiloMarco'] = '';
				$atributos ["etiquetaObligatorio"] = false;
				$atributos ['columnas'] = 2;
				$atributos ['dobleLinea'] = 0;
				$atributos ['tabIndex'] = $tab;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ['validar'] = 'minSize[1],maxSize[30]';
				
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
				// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
				
				// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
				$esteCampo = 'primerNombre';
				$atributos ['id'] = $esteCampo;
				$atributos ['nombre'] = $esteCampo;
				$atributos ['tipo'] = 'text';
				$atributos ['estilo'] = 'jqueryui mayuscula';
				$atributos ['marco'] = true;
				$atributos ['estiloMarco'] = '';
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['columnas'] = 2;
				$atributos ['dobleLinea'] = 0;
				$atributos ['tabIndex'] = $tab;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ['validar'] = 'required, minSize[1],maxSize[30]';
				
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
				// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
				
				// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
				$esteCampo = 'segundoNombre';
				$atributos ['id'] = $esteCampo;
				$atributos ['nombre'] = $esteCampo;
				$atributos ['tipo'] = 'text';
				$atributos ['estilo'] = 'jqueryui mayuscula';
				$atributos ['marco'] = true;
				$atributos ['estiloMarco'] = '';
				$atributos ["etiquetaObligatorio"] = false;
				$atributos ['columnas'] = 2;
				$atributos ['dobleLinea'] = 0;
				$atributos ['tabIndex'] = $tab;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ['validar'] = 'minSize[1],maxSize[30]';
				
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
				// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
							
				
				
						$esteCampo = "marcoExpRep";
						$atributos ['id'] = $esteCampo;
						$atributos ["estilo"] = "jqueryui";
						$atributos ['tipoEtiqueta'] = 'inicio';
						$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
						echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
						
						

						// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
						$esteCampo = "tipoDocumento";
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
						
						
						$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "consultarTipoDocumento" );
						$matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
						
						// Valores a mostrar en el control
						/*$matrizItems = array (
						 array ( 1, 'Registro Civil de Nacimiento' ),
						 array ( 2, 'Tarjeta de Identidad' ),
						 array ( 3, 'Cédula de Ciudadania' ),
						 array ( 4, 'Certificado de Registraduria' ),
						 array ( 5, 'Tarjeta de Extranjería' ),
						 array ( 6, 'Cédula de Extranjería' ),
						 array ( 7, 'Pasaporte' ),
						 array ( 8, 'Carne Diplomatico' )
						 );*/
						$atributos ['matrizItems'] = $matrizItems;
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroLista ( $atributos );
						unset ( $atributos );
						// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
						
						
						// ---------------- CONTROL: Cuadro de Texto NIT--------------------------------------------------------
						$esteCampo = 'numeroDocumento';
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
						$atributos ['validar'] = 'required, minSize[1],maxSize[15],custom[onlyNumberSp]';
						
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
							
						// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
						$esteCampo = 'digitoRepre';
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
						
								
						
						
						// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
						$esteCampo = 'fechaExpeRep';
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
						$atributos ['validar'] = 'required,minSize[1],maxSize[10],custom[date]';
							
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['valor'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['valor'] = '';
						}
						$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
						$atributos ['deshabilitado'] = false;
						$atributos ['tamanno'] = 15;
						$atributos ['maximoTamanno'] = '';
						$atributos ['anchoEtiqueta'] = 300;
						$tab ++;
							
						// Aplica atributos globales al control
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroTexto ( $atributos );
						unset ( $atributos );
						// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
						
						
						// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
						$esteCampo = "paisExpeRep";
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
						$atributos ['columnas'] = 3;
						$atributos ['tamanno'] = 1;
						$atributos ['estilo'] = "jqueryui";
						$atributos ['validar'] = "required";
						$atributos ['limitar'] = false;
						$atributos ['anchoCaja'] = 60;
						$atributos ['miEvento'] = '';
						
						$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "consultarPaises" );
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
						
						
						// ---------------- CONTROL: Select --------------------------------------------------------
						$esteCampo = 'departamentoExpeRep';
						$atributos['nombre'] = $esteCampo;
						$atributos['id'] = $esteCampo;
						$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
						$atributos['tab'] = $tab;
						$atributos ['anchoEtiqueta'] = 200;
						$atributos['seleccion'] = -1;
						$atributos['evento'] = ' ';
						$atributos['deshabilitado'] = true;
						$atributos['limitar']= 50;
						$atributos['tamanno']= 1;
						$atributos['columnas']= 3;
							
						$atributos ['obligatorio'] = true;
						$atributos ['etiquetaObligatorio'] = true;
						$atributos ['validar'] = 'required';
							
						$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarDepartamento" );
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
						$esteCampo = 'ciudadExpeRep';
						$atributos['nombre'] = $esteCampo;
						$atributos['id'] = $esteCampo;
						$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
						$atributos['tab'] = $tab;
						$atributos ['anchoEtiqueta'] = 200;
						$atributos['seleccion'] = -1;
						$atributos['evento'] = ' ';
						$atributos['deshabilitado'] = true;
						$atributos['limitar']= 50;
						$atributos['tamanno']= 1;
						$atributos['columnas']= 3;
							
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
						
						
						
						echo $this->miFormulario->marcoAgrupacion ( 'fin' );
						
						
						
						
						
						
						
						
						
						
						
						
						
						

						
						// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
						$esteCampo = "genero";
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
								array ( 1, 'Masculino' ),
								array ( 2, 'Femenino' )
						);
						$atributos ['matrizItems'] = $matrizItems;
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroLista ( $atributos );
						unset ( $atributos );
						// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
						
						
						// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
						$esteCampo = 'cargo';
						$atributos ['id'] = $esteCampo;
						$atributos ['nombre'] = $esteCampo;
						$atributos ['tipo'] = 'text';
						$atributos ['estilo'] = 'jqueryui mayuscula';
						$atributos ['marco'] = true;
						$atributos ['estiloMarco'] = '';
						$atributos ["etiquetaObligatorio"] = true;
						$atributos ['columnas'] = 2;
						$atributos ['dobleLinea'] = 0;
						$atributos ['tabIndex'] = $tab;
						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
						$atributos ['validar'] = 'required, minSize[1],maxSize[30]';
						
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['valor'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['valor'] = '';
						}
						$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
						$atributos ['deshabilitado'] = false;
						$atributos ['tamanno'] = 30;
						$atributos ['maximoTamanno'] = '';
						$atributos ['anchoEtiqueta'] = 200;
						$tab ++;
						
						// Aplica atributos globales al control
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroTexto ( $atributos );
						unset ( $atributos );
						// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
						
						// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
						$esteCampo = 'correoPer';
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
						$atributos ['anchoEtiqueta'] = 200;
						$tab ++;
						
						// Aplica atributos globales al control
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroTexto ( $atributos );
						unset ( $atributos );
						// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
						
						// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
						$esteCampo = "paisNacimiento";
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
						
						$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "consultarPaises" );
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
						
						// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
						$esteCampo = "perfil";
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
								array ( 1, 'Asistencial' ),
								array ( 2, 'Técnico' ),
								array ( 3, 'Profesional' ),
								array ( 4, 'Profesional Especializado' ),
								array ( 6, 'Asesor 1' ),
								array ( 7, 'Asesor 2' ),
								array ( 5, 'No Aplica' )
						);
						$atributos ['matrizItems'] = $matrizItems;
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroLista ( $atributos );
						unset ( $atributos );
						// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
						
						echo "<br>";
						echo "<br>";
						echo "<br>";
						echo "<br>";
						echo "<br>";
						echo "<br>";
						echo "<br>";
						echo "<br>";
						echo "<br>";
						echo "<br>";
						
						$atributos ["id"] = "obligatorioProfesion";
						$atributos ["estilo"] = "Marco";
						echo $this->miFormulario->division ( "inicio", $atributos );
						unset ( $atributos );
						{
							
							// ---------------- CONTROL: Select --------------------------------------------------------
							$esteCampo = 'personaArea';
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
							$atributos ['anchoEtiqueta'] = 350;
							
							$atributos ['obligatorio'] = true;
							$atributos ['etiquetaObligatorio'] = true;
							$atributos ['validar'] = 'required';
							
							$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarAreaConocimiento" );
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
							$esteCampo = 'personaNBC';
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
							$atributos ['anchoEtiqueta'] = 350;
							
							$atributos ['obligatorio'] = true;
							$atributos ['etiquetaObligatorio'] = true;
							$atributos ['validar'] = 'required';
							
							$matrizItems=array(
									array(1,'Test A'),
									array(2,'Test B'),
							
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
							
							
							// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
							$esteCampo = 'profesion';
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
							$atributos ['validar'] = 'required, minSize[1],maxSize[40]';
							
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else {
								$atributos ['valor'] = '';
							}
							$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
							$atributos ['deshabilitado'] = false;
							$atributos ['tamanno'] = 40;
							$atributos ['maximoTamanno'] = '';
							$atributos ['anchoEtiqueta'] = 350;
							$tab ++;
							
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroTexto ( $atributos );
							unset ( $atributos );
							// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
						}
						echo $this->miFormulario->division ( "fin");
						
						$atributos ["id"] = "obligatorioEspecialidad";
						$atributos ["estilo"] = "Marco";
						echo $this->miFormulario->division ( "inicio", $atributos );
						unset ( $atributos );
						{
							// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
							$esteCampo = 'especialidad';
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
							$atributos ['validar'] = 'required, minSize[1],maxSize[40]';
							
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else {
								$atributos ['valor'] = '';
							}
							$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
							$atributos ['deshabilitado'] = false;
							$atributos ['tamanno'] = 40;
							$atributos ['maximoTamanno'] = '';
							$atributos ['anchoEtiqueta'] = 350;
							$tab ++;
							
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroTexto ( $atributos );
							unset ( $atributos );
							// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
						}
						echo $this->miFormulario->division ( "fin");
						
						// ---------------- CONTROL: Cuadro de Texto NIT--------------------------------------------------------
						$esteCampo = 'numeroContacto';
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
						$atributos ['validar'] = 'required, minSize[7],maxSize[10],custom[onlyNumberSp]';
						
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
						
						
															
				echo $this->miFormulario->marcoAgrupacion ( 'fin' );
				
				
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
						$atributos ['columnas'] = 2;
						$atributos ['tamanno'] = 1;
						$atributos ['estilo'] = "jqueryui";
						$atributos ['validar'] = "required";
						$atributos ['limitar'] = false;
						$atributos ['anchoCaja'] = 60;
						$atributos ['miEvento'] = '';
						
						// Valores a mostrar en el control
						$matrizItems = array (
								array ( 1, 'Ahorros' ),
								array ( 2, 'Corriente' )
						);
						$atributos ['matrizItems'] = $matrizItems;
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroLista ( $atributos );
						unset ( $atributos );
						// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
				
						// ---------------- CONTROL: Cuadro de Texto NIT--------------------------------------------------------
						$esteCampo = 'numeroCuenta';
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
						$atributos ['validar'] = 'required, minSize[1],maxSize[15],custom[onlyNumberSp]';
						
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
						$atributos ['columnas'] = 1;
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
						
						// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
						$esteCampo = "tipoConformacion";
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
						
						$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "consultarConformacion" );
						$matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
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
						
						// ---------------- CONTROL: Cuadro de Texto NIT--------------------------------------------------------
						$esteCampo = 'monto';
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
						$atributos ['validar'] = 'required, minSize[1],maxSize[14],custom[number]';
						
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
						
				
				
				echo $this->miFormulario->marcoAgrupacion ( 'fin' );
				
				$esteCampo = "marcoEmpresa";
				$atributos ['id'] = $esteCampo;
				$atributos ["estilo"] = "jqueryui";
				$atributos ['tipoEtiqueta'] = 'inicio';
				$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
				echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos ); 

					// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
						$esteCampo = "productoImportacion";
						$atributos ['nombre'] = $esteCampo;
						$atributos ['id'] = $esteCampo;
						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
						$atributos ["etiquetaObligatorio"] = true;
						$atributos ['tab'] = $tab ++;
						$atributos ['anchoEtiqueta'] = 350;
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
						// Valores a mostrar en el control
						$matrizItems = array (
								array ( 1, 'Si' ),
								array ( 2, 'No' ) 
						);
						$atributos ['matrizItems'] = $matrizItems;
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroLista ( $atributos );
						unset ( $atributos );
					// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
					
					// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
						$esteCampo = "regimenContributivo";
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
						// Valores a mostrar en el control
						$matrizItems = array (
								array ( 1, 'Comun' ),
								array ( 2, 'Simplificado' ) 
						);
						$atributos ['matrizItems'] = $matrizItems;
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroLista ( $atributos );
						unset ( $atributos );
					// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
					
					// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
						$esteCampo = "pyme";
						$atributos ['nombre'] = $esteCampo;
						$atributos ['id'] = $esteCampo;
						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
						$atributos ["etiquetaObligatorio"] = true;
						$atributos ['tab'] = $tab ++;
						$atributos ['anchoEtiqueta'] = 350;
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
						// Valores a mostrar en el control
						$matrizItems = array (
								array ( 1, 'Si' ),
								array ( 2, 'No' ) 
						);
						$atributos ['matrizItems'] = $matrizItems;
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroLista ( $atributos );
						unset ( $atributos );
					// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
				
					// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
						$esteCampo = "registroMercantil";
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
						// Valores a mostrar en el control
						$matrizItems = array (
								array ( 1, 'Si' ),
								array ( 2, 'No' ) 
						);
						$atributos ['matrizItems'] = $matrizItems;
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroLista ( $atributos );
						unset ( $atributos );
					// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
					
						// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
						$esteCampo = "sujetoDeRetencion";
						$atributos ['nombre'] = $esteCampo;
						$atributos ['id'] = $esteCampo;
						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
						$atributos ["etiquetaObligatorio"] = true;
						$atributos ['tab'] = $tab ++;
						$atributos ['anchoEtiqueta'] = 350;
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
						// Valores a mostrar en el control
						$matrizItems = array (
								array ( 1, 'Si' ),
								array ( 2, 'No' )
						);
						$atributos ['matrizItems'] = $matrizItems;
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroLista ( $atributos );
						unset ( $atributos );
						// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
						
						// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
						$esteCampo = "agenteRetenedor";
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
						// Valores a mostrar en el control
						$matrizItems = array (
								array ( 1, 'Si' ),
								array ( 2, 'No' )
						);
						$atributos ['matrizItems'] = $matrizItems;
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroLista ( $atributos );
						unset ( $atributos );
						// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
						
						// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
						$esteCampo = "responsableICA";
						$atributos ['nombre'] = $esteCampo;
						$atributos ['id'] = $esteCampo;
						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
						$atributos ["etiquetaObligatorio"] = true;
						$atributos ['tab'] = $tab ++;
						$atributos ['anchoEtiqueta'] = 350;
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
						// Valores a mostrar en el control
						$matrizItems = array (
								array ( 1, 'Si' ),
								array ( 2, 'No' )
						);
						$atributos ['matrizItems'] = $matrizItems;
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroLista ( $atributos );
						unset ( $atributos );
						// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
						
						// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
						$esteCampo = "responsableIVA";
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
						// Valores a mostrar en el control
						$matrizItems = array (
								array ( 1, 'Si' ),
								array ( 2, 'No' )
						);
						$atributos ['matrizItems'] = $matrizItems;
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroLista ( $atributos );
						unset ( $atributos );
						// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
						

echo $this->miFormulario->marcoAgrupacion ( 'fin' );
				
				$esteCampo = "marcoRUT";
				$atributos ['id'] = $esteCampo;
				$atributos ["estilo"] = "jqueryui";
				$atributos ['tipoEtiqueta'] = 'inicio';
				$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
				echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
				// ----------------INICIO CONTROL: DOCUMENTO--------------------------------------------------------
				$esteCampo = "DocumentoRUT";
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
				$atributos ["etiqueta"] = $this->lenguaje->getCadena ( $esteCampo );
				// $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
				// $atributos ["valor"] = $valorCodificado;
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroTexto ( $atributos );
				unset ( $atributos );								
// ----------------FIN CONTROL: DOCUMENTO--------------------------------------------------------									

				echo $this->miFormulario->marcoAgrupacion ( 'fin' );			

				
				
				
				$esteCampo = "marcoRUP";
				$atributos ['id'] = $esteCampo;
				$atributos ["estilo"] = "jqueryui";
				$atributos ['tipoEtiqueta'] = 'inicio';
				$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
				echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
				// ----------------INICIO CONTROL: DOCUMENTO--------------------------------------------------------
				$esteCampo = "DocumentoRUP";
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
				$atributos ["etiqueta"] = $this->lenguaje->getCadena ( $esteCampo );
				// $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
				// $atributos ["valor"] = $valorCodificado;
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroTexto ( $atributos );
				unset ( $atributos );
				// ----------------FIN CONTROL: DOCUMENTO--------------------------------------------------------
				
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
						registro exitoso, sin embargo, si desea relacionar más Actividades, puede ingresar al Sistema una
						vez se registre y puede adicionar más Actividades Económicas, si así lo requiere.</b>";
					
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
       				
				
				
				
				?>
								
								<div class="panel panel-primary">
								    <div class="panel-heading">
								      <h3 class="panel-title">Términos de Registro</h3>
								    </div>
								    <div class="panel-body">
									      <div class="alert alert-success">
											<center><label for="condiciones">
								                            <strong>Acepta y reconoce bajo consentimiento propio el registro de información 
								                            personal en el Sistema de Registro Único y Banco de Proveedores de la
								                            Universidad Distrital Francisco Jóse de Caldas</strong>
								            </label></center>
							                <center><input id="condicionesCheckJur" type="checkbox"></center>
										 </div>
								    </div>
								</div>
								
								
								<?php
				
				
				
				
				
				// ------------------Division para los botones-------------------------
				$atributos ["id"] = "botonesJur";
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
				echo $this->miFormulario->division ( 'fin' );
				
		    echo $this->miFormulario->marcoAgrupacion ( 'fin' );
				// ---------------- FIN SECCION: Controles del Formulario -------------------------------------------
				// ----------------FINALIZAR EL FORMULARIO ----------------------------------------------------------
				// Se debe declarar el mismo atributo de marco con que se inició el formulario.
			
			// -----------------FIN CONTROL: Botón -----------------------------------------------------------
			// ------------------Fin Division para los botones-------------------------
			
			
			
			
			
		    
		    
		    
		    
		    
		    
		    
		    
		    
		    
		    
		    
		    
			
			//********************************************************************************************** PERSONA NATURAL****************************
			
			$esteCampo = "marcoDatosNatural";
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
			$atributos ['tipoEtiqueta'] = 'inicio';
			$atributos ["leyenda"] =  $this->lenguaje->getCadena ( $esteCampo );
			echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
			unset ( $atributos );
			{
					
				$esteCampo = "marcoPersona";
				$atributos ['id'] = $esteCampo;
				$atributos ["estilo"] = "jqueryui";
				$atributos ['tipoEtiqueta'] = 'inicio';
				$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
				echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
				
				
				
				
				// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
				$esteCampo = 'primerApellidoNat';
				$atributos ['id'] = $esteCampo;
				$atributos ['nombre'] = $esteCampo;
				$atributos ['tipo'] = 'text';
				$atributos ['estilo'] = 'jqueryui mayuscula';
				$atributos ['marco'] = true;
				$atributos ['estiloMarco'] = '';
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['columnas'] = 2;
				$atributos ['dobleLinea'] = 0;
				$atributos ['tabIndex'] = $tab;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ['validar'] = 'required, minSize[1],maxSize[30]';
				
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
				// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
				
				// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
				$esteCampo = 'segundoApellidoNat';
				$atributos ['id'] = $esteCampo;
				$atributos ['nombre'] = $esteCampo;
				$atributos ['tipo'] = 'text';
				$atributos ['estilo'] = 'jqueryui mayuscula';
				$atributos ['marco'] = true;
				$atributos ['estiloMarco'] = '';
				$atributos ["etiquetaObligatorio"] = false;
				$atributos ['columnas'] = 2;
				$atributos ['dobleLinea'] = 0;
				$atributos ['tabIndex'] = $tab;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ['validar'] = 'minSize[1],maxSize[30]';
				
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
				// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
				
				// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
				$esteCampo = 'primerNombreNat';
				$atributos ['id'] = $esteCampo;
				$atributos ['nombre'] = $esteCampo;
				$atributos ['tipo'] = 'text';
				$atributos ['estilo'] = 'jqueryui mayuscula';
				$atributos ['marco'] = true;
				$atributos ['estiloMarco'] = '';
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['columnas'] = 2;
				$atributos ['dobleLinea'] = 0;
				$atributos ['tabIndex'] = $tab;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ['validar'] = 'required, minSize[1],maxSize[30]';
				
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
				// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
				
				// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
				$esteCampo = 'segundoNombreNat';
				$atributos ['id'] = $esteCampo;
				$atributos ['nombre'] = $esteCampo;
				$atributos ['tipo'] = 'text';
				$atributos ['estilo'] = 'jqueryui mayuscula';
				$atributos ['marco'] = true;
				$atributos ['estiloMarco'] = '';
				$atributos ["etiquetaObligatorio"] = false;
				$atributos ['columnas'] = 2;
				$atributos ['dobleLinea'] = 0;
				$atributos ['tabIndex'] = $tab;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ['validar'] = 'minSize[1],maxSize[30]';
				
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
				// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
				
				
				
				
				
				
				
				$esteCampo = "marcoExp";
				$atributos ['id'] = $esteCampo;
				$atributos ["estilo"] = "jqueryui";
				$atributos ['tipoEtiqueta'] = 'inicio';
				$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
				echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
				
				
				
				
				// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
				$esteCampo = "tipoDocumentoNat";
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
				
				$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "consultarTipoDocumento" );
				$matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
				/*$matrizItems = array (
				 array ( 1, 'Registro Civil de Nacimiento' ),
				 array ( 2, 'Tarjeta de Identidad' ),
				 array ( 3, 'Cédula de Ciudadania' ),
				 array ( 4, 'Certificado de Registraduria' ),
				 array ( 5, 'Tarjeta de Extranjería' ),
				 array ( 6, 'Cédula de Extranjería' ),
				 array ( 7, 'Pasaporte' ),
				 array ( 8, 'Carne Diplomatico' )
				 );*/
				$atributos ['matrizItems'] = $matrizItems;
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroLista ( $atributos );
				unset ( $atributos );
				// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
					
					
				// ---------------- CONTROL: Cuadro de Texto NIT--------------------------------------------------------
				$esteCampo = 'documentoNat';
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
				$atributos ['maximoTamanno'] = 15;
				$atributos ['anchoEtiqueta'] = 200;
				$tab ++;
					
				// Aplica atributos globales al control
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroTexto ( $atributos );
				unset ( $atributos );
				// ---------------- FIN CONTROL: Cuadro de Texto  NIT--------------------------------------------------------
					
				// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
				$esteCampo = 'digitoNat';
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
						
				
				
				
				// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
				$esteCampo = 'fechaExpeNat';
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
				$atributos ['validar'] = 'required,minSize[1],maxSize[10],custom[date]';
					
				if (isset ( $_REQUEST [$esteCampo] )) {
					$atributos ['valor'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['valor'] = '';
				}
				$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
				$atributos ['deshabilitado'] = false;
				$atributos ['tamanno'] = 15;
				$atributos ['maximoTamanno'] = '';
				$atributos ['anchoEtiqueta'] = 300;
				$tab ++;
					
				// Aplica atributos globales al control
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroTexto ( $atributos );
				unset ( $atributos );
				// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
						
				
				
				$esteCampo = "marcoExpLug";
				$atributos ['id'] = $esteCampo;
				$atributos ["estilo"] = "jqueryui";
				$atributos ['tipoEtiqueta'] = 'inicio';
				$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
				//echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
				
				// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
				$esteCampo = "paisExpeNat";
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
				$atributos ['columnas'] = 3;
				$atributos ['tamanno'] = 1;
				$atributos ['estilo'] = "jqueryui";
				$atributos ['validar'] = "required";
				$atributos ['limitar'] = false;
				$atributos ['anchoCaja'] = 60;
				$atributos ['miEvento'] = '';
				
				$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "consultarPaises" );
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
				
				
				// ---------------- CONTROL: Select --------------------------------------------------------
				$esteCampo = 'departamentoExpeNat';
				$atributos['nombre'] = $esteCampo;
				$atributos['id'] = $esteCampo;
				$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos['tab'] = $tab;
				$atributos ['anchoEtiqueta'] = 200;
				$atributos['seleccion'] = -1;
				$atributos['evento'] = ' ';
				$atributos['deshabilitado'] = true;
				$atributos['limitar']= 50;
				$atributos['tamanno']= 1;
				$atributos['columnas']= 3;
					
				$atributos ['obligatorio'] = true;
				$atributos ['etiquetaObligatorio'] = true;
				$atributos ['validar'] = 'required';
					
				$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarDepartamento" );
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
				$esteCampo = 'ciudadExpeNat';
				$atributos['nombre'] = $esteCampo;
				$atributos['id'] = $esteCampo;
				$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos['tab'] = $tab;
				$atributos ['anchoEtiqueta'] = 200;
				$atributos['seleccion'] = -1;
				$atributos['evento'] = ' ';
				$atributos['deshabilitado'] = true;
				$atributos['limitar']= 50;
				$atributos['tamanno']= 1;
				$atributos['columnas']= 3;
					
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
				
				//echo $this->miFormulario->marcoAgrupacion ( 'fin' );
				
				
				
				echo $this->miFormulario->marcoAgrupacion ( 'fin' );
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
				$esteCampo = "generoNat";
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
						array ( 1, 'Masculino' ),
						array ( 2, 'Femenino' )
				);
				$atributos ['matrizItems'] = $matrizItems;
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroLista ( $atributos );
				unset ( $atributos );
				// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
				
				/*
				// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
				$esteCampo = 'cargoNat';
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
				$atributos ['validar'] = 'required,minSize[1],maxSize[30]';
				
				if (isset ( $_REQUEST [$esteCampo] )) {
					$atributos ['valor'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['valor'] = '';
				}
				$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
				$atributos ['deshabilitado'] = false;
				$atributos ['tamanno'] = 40;
				$atributos ['maximoTamanno'] = '';
				$atributos ['anchoEtiqueta'] = 200;
				$tab ++;
				
				// Aplica atributos globales al control
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroTexto ( $atributos );
				unset ( $atributos );
				// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
				*/
				/*// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
				$esteCampo = 'correoPerNat';
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
				$atributos ['validar'] = 'required, custom[email], maxSize[40]';
				
				if (isset ( $_REQUEST [$esteCampo] )) {
					$atributos ['valor'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['valor'] = '';
				}
				$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
				$atributos ['deshabilitado'] = false;
				$atributos ['tamanno'] = 40;
				$atributos ['maximoTamanno'] = '';
				$atributos ['anchoEtiqueta'] = 200;
				$tab ++;
				
				// Aplica atributos globales al control
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroTexto ( $atributos );
				unset ( $atributos );
				// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------*/
				
				// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
				$esteCampo = "paisNacimientoNat";
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
				
				$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "consultarPaises" );
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
				
				// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
				$esteCampo = "perfilNat";
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
						array ( 1, 'Asistencial' ),
						array ( 2, 'Técnico' ),
						array ( 3, 'Profesional' ),
						array ( 4, 'Profesional Especializado' ),
						array ( 6, 'Asesor 1' ),
						array ( 7, 'Asesor 2' ),
						array ( 5, 'No Aplica' )
				);
				$atributos ['matrizItems'] = $matrizItems;
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroLista ( $atributos );
				unset ( $atributos );
				// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
				
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				
				$atributos ["id"] = "obligatorioProfesionNat";
				$atributos ["estilo"] = "Marco";
				echo $this->miFormulario->division ( "inicio", $atributos );
				unset ( $atributos );
				{
					
					// ---------------- CONTROL: Select --------------------------------------------------------
					$esteCampo = 'personaNaturalArea';
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
					$atributos ['anchoEtiqueta'] = 350;
						
					$atributos ['obligatorio'] = true;
					$atributos ['etiquetaObligatorio'] = true;
					$atributos ['validar'] = 'required';
						
					$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarAreaConocimiento" );
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
					$esteCampo = 'personaNaturalNBC';
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
					$atributos ['anchoEtiqueta'] = 350;
						
					$atributos ['obligatorio'] = true;
					$atributos ['etiquetaObligatorio'] = true;
					$atributos ['validar'] = 'required';
						
					$matrizItems=array(
							array(1,'Test A'),
							array(2,'Test B'),
								
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
					
					
					
					
					// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
					$esteCampo = 'profesionNat';
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
					$atributos ['validar'] = 'required, minSize[1],maxSize[40]';
						
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = false;
					$atributos ['tamanno'] = 40;
					$atributos ['maximoTamanno'] = '';
					$atributos ['anchoEtiqueta'] = 350;
					$tab ++;
						
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					unset ( $atributos );
					// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
					
					
					
				}
				echo $this->miFormulario->division ( "fin");
				
				$atributos ["id"] = "obligatorioEspecialidadNat";
				$atributos ["estilo"] = "Marco";
				echo $this->miFormulario->division ( "inicio", $atributos );
				unset ( $atributos );
				{
					// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
					$esteCampo = 'especialidadNat';
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
					$atributos ['validar'] = 'required, minSize[1],maxSize[40]';
						
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = false;
					$atributos ['tamanno'] = 40;
					$atributos ['maximoTamanno'] = '';
					$atributos ['anchoEtiqueta'] = 350;
					$tab ++;
						
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					unset ( $atributos );
					// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
				}
				echo $this->miFormulario->division ( "fin");
				
				echo $this->miFormulario->marcoAgrupacion ( 'fin' );
				
				
				
				
				
				
				$esteCampo = "marcoCaracterizacion";
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
					
				$mensajeLey = "<b>Recuerde que el Registro y Tratamiento de Información sensible se realizará mediante lo establecido
											según la Ley 1581 de 2012 y la Sentencia 334 de 2010 de la Corte Constitucional</b>";
					
				$atributos["mensaje"] = $mensajeLey;
				echo $this->miFormulario->cuadroMensaje($atributos);
				unset($atributos);
					
				
				
				
				
				// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
				$esteCampo = "grupoEtnico";
				$atributos ['nombre'] = $esteCampo;
				$atributos ['id'] = $esteCampo;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ["etiquetaObligatorio"] = false;
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
				$atributos ['validar'] = " ";
				$atributos ['limitar'] = false;
				$atributos ['anchoCaja'] = 60;
				$atributos ['miEvento'] = '';
				// Valores a mostrar en el control
				
				$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "consultarGrupoEtnico" );
				$matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );

				$atributos ['matrizItems'] = $matrizItems;
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroLista ( $atributos );
				unset ( $atributos );
				// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
				
				// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
				$esteCampo = "comunidadLGBT";
				$atributos ['nombre'] = $esteCampo;
				$atributos ['id'] = $esteCampo;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['tab'] = $tab ++;
				$atributos ['anchoEtiqueta'] = 350;
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
				
				$matrizItems=array(
						array(1,'Si'),
						array(2,'No')
				);
				$atributos['matrizItems'] = $matrizItems;
				 
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroLista ( $atributos );
				unset ( $atributos );
				// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
				
				
				// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
				$esteCampo = "cabezaFamilia";
				$atributos ['nombre'] = $esteCampo;
				$atributos ['id'] = $esteCampo;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['tab'] = $tab ++;
				$atributos ['anchoEtiqueta'] = 350;
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
				
				$matrizItems=array(
						array(1,'Si'),
						array(2,'No')
				);
				$atributos['matrizItems'] = $matrizItems;
					
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroLista ( $atributos );
				unset ( $atributos );
				// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
				
				// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
				$esteCampo = "personasCargo";
				$atributos ['nombre'] = $esteCampo;
				$atributos ['id'] = $esteCampo;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['tab'] = $tab ++;
				$atributos ['anchoEtiqueta'] = 350;
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
				
				$matrizItems=array(
						array(1,'Si'),
						array(2,'No')
				);
				$atributos['matrizItems'] = $matrizItems;
					
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroLista ( $atributos );
				unset ( $atributos );
				// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
				
				
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";

				// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
				
				$atributos ["id"] = "obligatorioCantidadPersonasACargo";
				$atributos ["estilo"] = "Marco";
				echo $this->miFormulario->division ( "inicio", $atributos );
				unset ( $atributos );
				{
					// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
					$esteCampo = 'numeroPersonasCargo';
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
					$atributos ['validar'] = 'required, custom[number], minSize[1],maxSize[10]';
				
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = false;
					$atributos ['tamanno'] = 15;
					$atributos ['maximoTamanno'] = '';
					$atributos ['anchoEtiqueta'] = 350;
					$tab ++;
				
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					unset ( $atributos );
					// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
				}
				echo $this->miFormulario->division ( "fin");
				
				
				// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
				$esteCampo = "estadoCivil";
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
				
				$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "consultarTipoEstadoCivil" );
				$matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
				
				$atributos ['matrizItems'] = $matrizItems;
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroLista ( $atributos );
				unset ( $atributos );
				// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
				
				
				
				
				// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
				$esteCampo = "discapacidad";
				$atributos ['nombre'] = $esteCampo;
				$atributos ['id'] = $esteCampo;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['tab'] = $tab ++;
				$atributos ['anchoEtiqueta'] = 350;
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
				
				$matrizItems=array(
						array(1,'Si'),
						array(2,'No')
				);
				$atributos['matrizItems'] = $matrizItems;
					
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroLista ( $atributos );
				unset ( $atributos );
				// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
				
				
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				
				// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
				
				$atributos ["id"] = "obligatorioTipoDiscapacidad";
				$atributos ["estilo"] = "Marco";
				echo $this->miFormulario->division ( "inicio", $atributos );
				unset ( $atributos );
				{
					// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
					$esteCampo = "tipoDiscapacidad";
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
					
					$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "consultarTipoDiscapacidad" );
					$matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
					
					$atributos ['matrizItems'] = $matrizItems;
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroLista ( $atributos );
					unset ( $atributos );
					// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
				}
				echo $this->miFormulario->division ( "fin");
				
				echo $this->miFormulario->marcoAgrupacion ( 'fin' );
				
				
				
				
				
				
				
				
				
				
				$esteCampo = "marcoBeneficiosTributarios";
				$atributos ['id'] = $esteCampo;
				$atributos ["estilo"] = "jqueryui";
				$atributos ['tipoEtiqueta'] = 'inicio';
				$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
				echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
				
				// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
				$esteCampo = "declaranteRentaNat";
				$atributos ['nombre'] = $esteCampo;
				$atributos ['id'] = $esteCampo;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['tab'] = $tab ++;
				$atributos ['anchoEtiqueta'] = 400;
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
						array ( 1, 'Si' ),
						array ( 2, 'No' )
				);
				$atributos ['matrizItems'] = $matrizItems;
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroLista ( $atributos );
				unset ( $atributos );
				// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
				
				
				// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
				$esteCampo = "medicinaPrepagadaNat";
				$atributos ['nombre'] = $esteCampo;
				$atributos ['id'] = $esteCampo;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['tab'] = $tab ++;
				$atributos ['anchoEtiqueta'] = 400;
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
						array ( 1, 'Si' ),
						array ( 2, 'No' )
				);
				$atributos ['matrizItems'] = $matrizItems;
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroLista ( $atributos );
				unset ( $atributos );
				// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
				
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				
				// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
				
				$atributos ["id"] = "obligatorioNumeroUVT";
				$atributos ["estilo"] = "Marco";
				echo $this->miFormulario->division ( "inicio", $atributos );
				unset ( $atributos );
				{
					// ---------------- CONTROL: Cuadro de Texto NIT--------------------------------------------------------
					$esteCampo = 'numeroUVTNat';
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
					$atributos ['validar'] = 'required, minSize[1],maxSize[6],custom[number]';
					
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = false;
					$atributos ['tamanno'] = 15;
					$atributos ['maximoTamanno'] = '';
					$atributos ['anchoEtiqueta'] = 400;
					$tab ++;
					
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					unset ( $atributos );
					// ---------------- FIN CONTROL: Cuadro de Texto  NIT--------------------------------------------------------
				}
				echo $this->miFormulario->division ( "fin");
				
				
				// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
				$esteCampo = "cuentaAFCNat";
				$atributos ['nombre'] = $esteCampo;
				$atributos ['id'] = $esteCampo;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['tab'] = $tab ++;
				$atributos ['anchoEtiqueta'] = 500;
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
						array ( 1, 'Si' ),
						array ( 2, 'No' )
				);
				$atributos ['matrizItems'] = $matrizItems;
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroLista ( $atributos );
				unset ( $atributos );
				// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
				
				echo "<br>";
				echo "<br>";
				echo "<br>";
				
				// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
				
				$atributos ["id"] = "obligatorioDatosAFC";
				$atributos ["estilo"] = "Marco";
				echo $this->miFormulario->division ( "inicio", $atributos );
				unset ( $atributos );
				{
					// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
					$esteCampo = "entidadBancariaAFCNat";
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
					$atributos ['matrizItems'] = $matrizItems;
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroLista ( $atributos );
					unset ( $atributos );
					// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
					
					
					// ---------------- CONTROL: Cuadro de Texto NIT--------------------------------------------------------
					$esteCampo = 'numeroCuentaAFCNat';
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
					$atributos ['validar'] = 'required, minSize[1],maxSize[15],custom[onlyNumberSp]';
						
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
					
					// ---------------- CONTROL: Cuadro de Texto NIT--------------------------------------------------------
					$esteCampo = 'interesViviendaAFCNat';
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
					$atributos ['validar'] = 'required, minSize[1],maxSize[6],custom[number]';
					
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
					
				}
				echo $this->miFormulario->division ( "fin");
				
				
				
					$esteCampo = "marcoDetalleDependientes";
					$atributos ['id'] = $esteCampo;
					$atributos ["estilo"] = "jqueryui";
					$atributos ['tipoEtiqueta'] = 'inicio';
					$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
					echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
					
					
					
					// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
					$esteCampo = "hijosMenoresEdadNat";
					$atributos ['nombre'] = $esteCampo;
					$atributos ['id'] = $esteCampo;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ["etiquetaObligatorio"] = true;
					$atributos ['tab'] = $tab ++;
					$atributos ['anchoEtiqueta'] = 800;
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
							array ( 1, 'Si' ),
							array ( 2, 'No' )
					);
					$atributos ['matrizItems'] = $matrizItems;
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroLista ( $atributos );
					unset ( $atributos );
					// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
					
					// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
					$esteCampo = "hijosMayoresEdadEstudiandoNat";
					$atributos ['nombre'] = $esteCampo;
					$atributos ['id'] = $esteCampo;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ["etiquetaObligatorio"] = true;
					$atributos ['tab'] = $tab ++;
					$atributos ['anchoEtiqueta'] = 800;
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
							array ( 1, 'Si' ),
							array ( 2, 'No' )
					);
					$atributos ['matrizItems'] = $matrizItems;
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroLista ( $atributos );
					unset ( $atributos );
					// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
					
					
					// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
					$esteCampo = "hijosMayoresEdadMas23Nat";
					$atributos ['nombre'] = $esteCampo;
					$atributos ['id'] = $esteCampo;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ["etiquetaObligatorio"] = true;
					$atributos ['tab'] = $tab ++;
					$atributos ['anchoEtiqueta'] = 800;
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
							array ( 1, 'Si' ),
							array ( 2, 'No' )
					);
					$atributos ['matrizItems'] = $matrizItems;
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroLista ( $atributos );
					unset ( $atributos );
					// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
					
					// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
					$esteCampo = "conyugeDependienteNat";
					$atributos ['nombre'] = $esteCampo;
					$atributos ['id'] = $esteCampo;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ["etiquetaObligatorio"] = true;
					$atributos ['tab'] = $tab ++;
					$atributos ['anchoEtiqueta'] = 800;
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
							array ( 1, 'Si' ),
							array ( 2, 'No' )
					);
					$atributos ['matrizItems'] = $matrizItems;
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroLista ( $atributos );
					unset ( $atributos );
					// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
					
					// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
					$esteCampo = "padresHermanosDependienteNat";
					$atributos ['nombre'] = $esteCampo;
					$atributos ['id'] = $esteCampo;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ["etiquetaObligatorio"] = true;
					$atributos ['tab'] = $tab ++;
					$atributos ['anchoEtiqueta'] = 800;
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
							array ( 1, 'Si' ),
							array ( 2, 'No' )
					);
					$atributos ['matrizItems'] = $matrizItems;
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroLista ( $atributos );
					unset ( $atributos );
					// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
					
					
					// ---------------- SECCION: Controles del Formulario -----------------------------------------------
					$esteCampo = 'mensaje';
					
					$tipo = 'warning';
					
					$atributos["id"] = $esteCampo; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
					$atributos["etiqueta"] = "";
					$atributos["estilo"] = "centrar";
					$atributos["tipo"] = $tipo;
					
					$mensajeLey = "<b>Recuerde que la información aquí señalada está sujeta a presentación de los certificados pertinentes a 
								cada caso, para su justificación, además recuerde que la información indicada está bajo la gravedad de 
								juramento de que la deducción para la base de la retención en la fuente solicitada por concepto de dependientes, 
								no ha sido ni será solicitada por más de un contribuyente en relación con un mismo dependiente.
								(Art. 3 del Decreto 99 de 2013)</b>";
					
					$atributos["mensaje"] = $mensajeLey;
					echo $this->miFormulario->cuadroMensaje($atributos);
					unset($atributos);
					
					
					
				
					echo $this->miFormulario->marcoAgrupacion ( 'fin' );
				
				
				
				
				
				
				
				echo $this->miFormulario->marcoAgrupacion ( 'fin' );
				
				
				
				
				
				
				
				
				
				
				
				
				$esteCampo = "marcoContacto";
				$atributos ['id'] = $esteCampo;
				$atributos ["estilo"] = "jqueryui";
				$atributos ['tipoEtiqueta'] = 'inicio';
				$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
				echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
				
				
				
				// ---------------- CONTROL: Select --------------------------------------------------------
				$esteCampo = 'personaNaturalContaDepartamento';
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
					
				$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarDepartamento" );
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
				$esteCampo = 'personaNaturalContaCiudad';
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
				
				
				
				$esteCampo = "marcoDatosDireccion";
				$atributos ['id'] = $esteCampo;
				$atributos ["estilo"] = "jqueryui";
				$atributos ['tipoEtiqueta'] = 'inicio';
				$atributos ["leyenda"] = "Dirección Tipo DIAN";
				echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
				{
				
				$atributos ["id"] = "panelDireccion";
				$atributos ["estilo"] = "row";
				echo $this->miFormulario->division ( "inicio", $atributos );
				{
					$atributos ["id"] = "ingresoDireccion";
					$atributos ["estilo"] = "col-md-6";
					echo $this->miFormulario->division ( "inicio", $atributos );
					{
						// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
						$esteCampo = 'direccionNat';
						$atributos ['id'] = $esteCampo;
						$atributos ['nombre'] = $esteCampo;
						$atributos ['estilo'] = '';
						$atributos ['marco'] = false;
						$atributos ['correccion'] = false;
						$atributos ['columnas'] = 50;
						$atributos ['filas'] = 4;
						$atributos ['tabIndex'] = $tab;
						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
						$atributos ['anchoEtiqueta'] = 150;
						$atributos ['deshabilitado'] = false;
							
						$atributos ['obligatorio'] = true;
						$atributos ['etiquetaObligatorio'] = true;
						$atributos ['validar'] = 'required, minSize[1], maxSize[5]';
							
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['valor'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['valor'] = '';
						}
						$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
						$tab ++;
							
						// Aplica atributos globales al control
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoTextArea ( $atributos );
						// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
				
						unset($atributos);
						
						unset($atributos);
						
						$atributos ["id"] = "ingresoBotones";
						$atributos ["estilo"] = "col-md-12";
						echo $this->miFormulario->division ( "inicio", $atributos );
						{
								
							$atributos ["id"] = "botonesPanel";
							$atributos ["estilo"] = "col-md-12 btn-group";
							echo $this->miFormulario->division ( "inicio", $atributos );
							{
								echo "<input type=\"button\" id=\"btOper1Nat\" value=\"A\" class=\"btn btn-primary btn-xs\"/>";
								echo "<input type=\"button\" id=\"btOper2Nat\" value=\"B\" class=\"btn btn-primary btn-xs\" />";
								echo "<input type=\"button\" id=\"btOper3Nat\" value=\"C\" class=\"btn btn-primary btn-xs\"/>";
								echo "<input type=\"button\" id=\"btOper4Nat\" value=\"D\" class=\"btn btn-primary btn-xs\" />";
								echo "<input type=\"button\" id=\"btOper5Nat\" value=\"E\" class=\"btn btn-primary btn-xs\"/>";
								echo "<input type=\"button\" id=\"btOper6Nat\" value=\"F\" class=\"btn btn-primary btn-xs\" />";
								echo "<input type=\"button\" id=\"btOper7Nat\" value=\"G\" class=\"btn btn-primary btn-xs\"/>";
								echo "<input type=\"button\" id=\"btOper8Nat\" value=\"H\" class=\"btn btn-primary btn-xs\" />";
								echo "<input type=\"button\" id=\"btOper9Nat\" value=\"I\" class=\"btn btn-primary btn-xs\" />";
								echo "<input type=\"button\" id=\"btOper10Nat\" value=\"J\" class=\"btn btn-primary btn-xs\" />";
								echo "<input type=\"button\" id=\"btOper11Nat\" value=\"K\" class=\"btn btn-primary btn-xs\" />";
								echo "<input type=\"button\" id=\"btOper12Nat\" value=\"L\" class=\"btn btn-primary btn-xs\" />";
								
								echo "<input type=\"button\" id=\"btOper15Nat\" value=\"M\" class=\"btn btn-primary btn-xs\" />";
								echo "<input type=\"button\" id=\"btOper16Nat\" value=\"N\" class=\"btn btn-primary btn-xs\" />";
								echo "<input type=\"button\" id=\"btOper17Nat\" value=\"O\" class=\"btn btn-primary btn-xs\" />";
								echo "<input type=\"button\" id=\"btOper18Nat\" value=\"P\" class=\"btn btn-primary btn-xs\" />";
								echo "<input type=\"button\" id=\"btOper19Nat\" value=\"Q\" class=\"btn btn-primary btn-xs\" />";
								echo "<input type=\"button\" id=\"btOper20Nat\" value=\"R\" class=\"btn btn-primary btn-xs\" />";
								echo "<input type=\"button\" id=\"btOper21Nat\" value=\"S\" class=\"btn btn-primary btn-xs\" />";
								echo "<input type=\"button\" id=\"btOper22Nat\" value=\"T\" class=\"btn btn-primary btn-xs\" />";
								echo "<input type=\"button\" id=\"btOper23Nat\" value=\"U\" class=\"btn btn-primary btn-xs\" />";
								echo "<input type=\"button\" id=\"btOper24Nat\" value=\"V\" class=\"btn btn-primary btn-xs\" />";
								echo "<input type=\"button\" id=\"btOper25Nat\" value=\"W\" class=\"btn btn-primary btn-xs\" />";
								echo "<input type=\"button\" id=\"btOper26Nat\" value=\"X\" class=\"btn btn-primary btn-xs\" />";
								echo "<input type=\"button\" id=\"btOper27Nat\" value=\"Y\" class=\"btn btn-primary btn-xs\" />";
								echo "<input type=\"button\" id=\"btOper28Nat\" value=\"Z\" class=\"btn btn-primary btn-xs\" />";
								
								echo "<input type=\"button\" id=\"btOper13Nat\" value=\"Borrar\" class=\"btn btn-danger btn-xs\" />";
							}
							echo $this->miFormulario->division ( "fin" );
						
						}
						echo $this->miFormulario->division ( "fin" );
						
						
						
						
						
					}
					echo $this->miFormulario->division ( "fin" );
					
					$atributos ["id"] = "variables";
					$atributos ["estilo"] = "col-md-6";
					echo $this->miFormulario->division ( "inicio", $atributos );
					{
						unset($atributos);
					
					
						$esteCampo = "marcoDatosParametros";
						$atributos ['id'] = $esteCampo;
						$atributos ["estilo"] = "jqueryui";
						$atributos ['tipoEtiqueta'] = 'inicio';
						$atributos ["leyenda"] = "Panel Nomenclaturas DIAN";
						echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
						{
					
							$atributos ["id"] = "listaNomenclaturasNatural";
							$atributos ["estilo"] = "col-md-12";
							echo $this->miFormulario->division ( "inicio", $atributos );
							{
								// ---------------- CONTROL: Select --------------------------------------------------------
								$esteCampo = 'listaNomenclaturasNat';
								$atributos['nombre'] = $esteCampo;
								$atributos['id'] = $esteCampo;
								$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
								$atributos ['anchoEtiqueta'] = 230;
								$atributos['tab'] = $tab;
								$atributos['seleccion'] = -1;
								$atributos['evento'] = ' ';
								$atributos['deshabilitado'] = false;
								$atributos['limitar']= 50;
								$atributos['tamanno']= 1;
								$atributos['columnas']= 1;
					
								$atributos ['obligatorio'] = false;
								$atributos ['etiquetaObligatorio'] = false;
								$atributos ['validar'] = '';
					
								
								$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarNomenclaturas" );
								$matrizParametros = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
					
								$atributos['matrizItems'] = $matrizParametros;
					
								if (isset ( $_REQUEST [$esteCampo] )) {
									$atributos ['valor'] = $_REQUEST [$esteCampo];
								} else {
									$atributos ['valor'] = '';
								}
								$atributos ["titulo"] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
								$tab ++;
					
								// Aplica atributos globales al control
								$atributos = array_merge ( $atributos, $atributosGlobales );
								echo $this->miFormulario->campoCuadroLista ( $atributos );
								// --------------- FIN CONTROL : Select --------------------------------------------------
							}
							echo $this->miFormulario->division ( "fin" );
					
							unset($atributos);
					
							$atributos ["id"] = "parametrosNat";
							$atributos ["estilo"] = "col-md-10";
							echo $this->miFormulario->division ( "inicio", $atributos );
							{
								// ---------------- CONTROL: Select --------------------------------------------------------
								$esteCampo = 'seccionParametrosNat';
								$atributos['nombre'] = $esteCampo;
								$atributos['id'] = $esteCampo;
								$atributos['etiqueta'] = '';
								$atributos ['anchoEtiqueta'] = 180;
								$atributos['tab'] = $tab;
								$atributos['seleccion'] = 0;
								$atributos['evento'] = ' ';
								$atributos['deshabilitado'] = true;
								$atributos['limitar']= 50;
								$atributos['tamanno']= 1;
								$atributos['columnas']= 1;
					
								$atributos ['obligatorio'] = false;
								$atributos ['etiquetaObligatorio'] = false;
								$atributos ['validar'] = '';
					
								//$atributos ['cadena_sql'] = $this->miSql->getCadenaSql("buscarCategoriaParametro");
								//$matrizParametros=$primerRecursoDB->ejecutarAcceso($atributos['cadena_sql'], "busqueda");
					
								//$atributos['matrizItems'] = $matrizParametros;
					
								$matrizItems=array(
										array(1,'Nomenclatura'),
										array(2,'TEST'),
					
								);
								$atributos['matrizItems'] = $matrizItems;
					
								if (isset ( $_REQUEST [$esteCampo] )) {
									$atributos ['valor'] = $_REQUEST [$esteCampo];
								} else {
									$atributos ['valor'] = '';
								}
								$atributos ["titulo"] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
								$tab ++;
					
								// Aplica atributos globales al control
								$atributos = array_merge ( $atributos, $atributosGlobales );
								echo $this->miFormulario->campoCuadroLista ( $atributos );
								// --------------- FIN CONTROL : Select --------------------------------------------------
							}
							echo $this->miFormulario->division ( "fin" );
							
							
							$atributos ["id"] = "botonParametrosNat";
							$atributos ["estilo"] = "col-md-2";
							echo $this->miFormulario->division ( "inicio", $atributos );
							{
								$atributos ["id"] = "botonesPanel";
								$atributos ["estilo"] = "col-md-12 btn-group btn-group-lg";
								echo $this->miFormulario->division ( "inicio", $atributos );
								{
									echo "<input type=\"button\" id=\"btOper14Nat\" value=\"Insertar\" class=\"btn btn-success\" />";
								}
								echo $this->miFormulario->division ( "fin" );
							}
							echo $this->miFormulario->division ( "fin" );
					
						}
						echo $this->miFormulario->marcoAgrupacion ( "fin" );
					
						unset($atributos);
					
					}
					echo $this->miFormulario->division ( "fin" );
					
					
					
				}
				echo $this->miFormulario->division ( "fin" );
				
				}
				echo $this->miFormulario->marcoAgrupacion ( "fin" );
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				/*
				// ---------------- CONTROL: Cuadro de Texto  Dirección--------------------------------------------------------
				$esteCampo = 'direccionNat';
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
				// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------*/
				
				
				$esteCampo = "marcoDatosNotificar";
				$atributos ['id'] = $esteCampo;
				$atributos ["estilo"] = "jqueryui";
				$atributos ['tipoEtiqueta'] = 'inicio';
				$atributos ["leyenda"] = "Información Contacto Directo";
				echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
				
					
				// ---------------- CONTROL: Cuadro de Texto Correo--------------------------------------------------------
				$esteCampo = 'correoNat';
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
				
				
				// ---------------- CONTROL: Cuadro de Texto Correo--------------------------------------------------------
				$esteCampo = 'correoNatConfirm';
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
				$atributos ['validar'] = 'required, custom[email], maxSize[320], equals[correoNat]';
					
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
				
				
				echo $this->miFormulario->marcoAgrupacion ( 'fin' );
				
				
				
				// ---------------- CONTROL: Cuadro de Texto  Sitio Web--------------------------------------------------------
				$esteCampo = 'sitioWebNat';
				$atributos ['id'] = $esteCampo;
				$atributos ['nombre'] = $esteCampo;
				$atributos ['tipo'] = 'text';
				$atributos ['estilo'] = 'jqueryui';
				$atributos ['marco'] = true;
				$atributos ['estiloMarco'] = '';
				$atributos ["etiquetaObligatorio"] = false;
				$atributos ['columnas'] = 2;
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
				
				// ---------------- CONTROL: Cuadro de Texto Teléfono --------------------------------------------------------
				$esteCampo = 'movilNat';
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
				$atributos ['validar'] = 'required, minSize[10],maxSize[10],custom[onlyNumberSp]';
				
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
				// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
				
				// ---------------- CONTROL: Cuadro de Texto Extensión --------------------------------------------------------
				$esteCampo = 'telefonoNat';
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
				$atributos ['validar'] = 'required, minSize[7],maxSize[10],custom[onlyNumberSp]';
				
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
				
				// ---------------- CONTROL: Cuadro de Texto Movil--------------------------------------------------------
				$esteCampo = 'extensionNat';
				$atributos ['id'] = $esteCampo;
				$atributos ['nombre'] = $esteCampo;
				$atributos ['tipo'] = 'text';
				$atributos ['estilo'] = 'jqueryui';
				$atributos ['marco'] = true;
				$atributos ['estiloMarco'] = '';
				$atributos ["etiquetaObligatorio"] = false;
				$atributos ['columnas'] = 2;
				$atributos ['dobleLinea'] = 0;
				$atributos ['tabIndex'] = $tab;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ['validar'] = 'minSize[1],maxSize[4],custom[onlyNumberSp]';
				
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
				// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
				
				// ---------------- CONTROL: Cuadro de Texto  Asesor Comercial--------------------------------------------------------
				$esteCampo = 'asesorComercialNat';
				$atributos ['id'] = $esteCampo;
				$atributos ['nombre'] = $esteCampo;
				$atributos ['tipo'] = 'text';
				$atributos ['estilo'] = 'jqueryui mayuscula';
				$atributos ['marco'] = true;
				$atributos ['estiloMarco'] = '';
				$atributos ["etiquetaObligatorio"] = false;
				$atributos ['columnas'] = 2;
				$atributos ['dobleLinea'] = 0;
				$atributos ['tabIndex'] = $tab;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ['validar'] = 'minSize[1],maxSize[150]';
				
				if (isset ( $_REQUEST [$esteCampo] )) {
					$atributos ['valor'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['valor'] = '';
				}
				$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
				$atributos ['deshabilitado'] = false;
				$atributos ['tamanno'] = 50;
				$atributos ['maximoTamanno'] = '';
				$atributos ['anchoEtiqueta'] = 160;
				$tab ++;
				
				// Aplica atributos globales al control
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroTexto ( $atributos );
				unset ( $atributos );
				// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
				
				// ---------------- CONTROL: Cuadro de Texto Teléfono del Asesor--------------------------------------------------------
				$esteCampo = 'telAsesorNat';
				$atributos ['id'] = $esteCampo;
				$atributos ['nombre'] = $esteCampo;
				$atributos ['tipo'] = 'text';
				$atributos ['estilo'] = 'jqueryui';
				$atributos ['marco'] = true;
				$atributos ['estiloMarco'] = '';
				$atributos ["etiquetaObligatorio"] = false;
				$atributos ['columnas'] = 2;
				$atributos ['dobleLinea'] = 0;
				$atributos ['tabIndex'] = $tab;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ['validar'] = 'minSize[7],maxSize[7],custom[onlyNumberSp]';
				
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
				
				echo $this->miFormulario->marcoAgrupacion ( 'fin' );
				
				
				
				
				$esteCampo = "marcoAfiliaciones";//Son CONSULTAS a Base de DATOS CENTRAL ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$atributos ['id'] = $esteCampo;
				$atributos ["estilo"] = "jqueryui";
				$atributos ['tipoEtiqueta'] = 'inicio';
				$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
				echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
				
				// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
				$esteCampo = "afiliacionEPSNat";
				$atributos ['nombre'] = $esteCampo;
				$atributos ['id'] = $esteCampo;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['tab'] = $tab ++;
				$atributos ['anchoEtiqueta'] = 300;
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
				
				$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "consultarEPS" );
				$matrizItems = $coreRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
				
				$atributos ['matrizItems'] = $matrizItems;
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroLista ( $atributos );
				unset ( $atributos );
				// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
				
				
				// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
				$esteCampo = "afiliacionPensionNat";
				$atributos ['nombre'] = $esteCampo;
				$atributos ['id'] = $esteCampo;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['tab'] = $tab ++;
				$atributos ['anchoEtiqueta'] = 300;
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
				
				$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "consultarFondoPension" );
				$matrizItems = $coreRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );

				$atributos ['matrizItems'] = $matrizItems;
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroLista ( $atributos );
				unset ( $atributos );
				// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
				
				// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
				$esteCampo = "afiliacionCajaNat";
				$atributos ['nombre'] = $esteCampo;
				$atributos ['id'] = $esteCampo;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ["etiquetaObligatorio"] = false;
				$atributos ['tab'] = $tab ++;
				$atributos ['anchoEtiqueta'] = 300;
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
				$atributos ['validar'] = "";
				$atributos ['limitar'] = false;
				$atributos ['anchoCaja'] = 60;
				$atributos ['miEvento'] = '';
				
				$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "consultarCaja" );
				$matrizItems = $coreRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );

				$atributos ['matrizItems'] = $matrizItems;
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroLista ( $atributos );
				unset ( $atributos );
				// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
				
				
				echo $this->miFormulario->marcoAgrupacion ( 'fin' );
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				$esteCampo = "marcoFinanciero";
				$atributos ['id'] = $esteCampo;
				$atributos ["estilo"] = "jqueryui";
				$atributos ['tipoEtiqueta'] = 'inicio';
				$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
				echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
				
				// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
				$esteCampo = "tipoCuentaNat";
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
				
				// Valores a mostrar en el control
				$matrizItems = array (
						array ( 1, 'Ahorros' ),
						array ( 2, 'Corriente' )
				);
				$atributos ['matrizItems'] = $matrizItems;
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroLista ( $atributos );
				unset ( $atributos );
				// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
				
				// ---------------- CONTROL: Cuadro de Texto NIT--------------------------------------------------------
				$esteCampo = 'numeroCuentaNat';
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
				$atributos ['validar'] = 'required, minSize[1],maxSize[15],custom[onlyNumberSp]';
				
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
				$esteCampo = "entidadBancariaNat";
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
				
				// ---------------- CONTROL: Cuadro de Texto NIT--------------------------------------------------------
				$esteCampo = 'tipoConformacionNat';
				$atributos ['id'] = $esteCampo;
				$atributos ['nombre'] = $esteCampo;
				$atributos ['tipo'] = 'hidden';
				$atributos ['estilo'] = 'jqueryui';
				$atributos ['marco'] = true;
				$atributos ['estiloMarco'] = '';
				$atributos ['columnas'] = 2;
				$atributos ['dobleLinea'] = 0;
				$atributos ['tabIndex'] = $tab;
				
				$atributos ['valor'] = 'Persona Natural';
				$tab ++;
				
				// Aplica atributos globales al control
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroTexto ( $atributos );
				unset ( $atributos );
				// ---------------- FIN CONTROL: Cuadro de Texto  NIT--------------------------------------------------------
				
				// ---------------- CONTROL: Cuadro de Texto NIT--------------------------------------------------------
				$esteCampo = 'montoNat';
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
				$atributos ['validar'] = 'minSize[1],maxSize[14],custom[number]';
				
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
				
				
				echo $this->miFormulario->marcoAgrupacion ( 'fin' );
				
					
				$esteCampo = "marcoRUT";
				$atributos ['id'] = $esteCampo;
				$atributos ["estilo"] = "jqueryui";
				$atributos ['tipoEtiqueta'] = 'inicio';
				$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
				echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
				// ----------------INICIO CONTROL: DOCUMENTO--------------------------------------------------------
				$esteCampo = "DocumentoRUTNat";
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
				$atributos ["etiqueta"] = $this->lenguaje->getCadena ( $esteCampo );
				// $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
				// $atributos ["valor"] = $valorCodificado;
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroTexto ( $atributos );
				unset ( $atributos );
				// ----------------FIN CONTROL: DOCUMENTO--------------------------------------------------------
				
				echo $this->miFormulario->marcoAgrupacion ( 'fin' );
				
				
				

				$esteCampo = "marcoRUP";
				$atributos ['id'] = $esteCampo;
				$atributos ["estilo"] = "jqueryui";
				$atributos ['tipoEtiqueta'] = 'inicio';
				$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
				echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
				// ----------------INICIO CONTROL: DOCUMENTO--------------------------------------------------------
				$esteCampo = "DocumentoRUPNat";
				$atributos ["id"] = $esteCampo; // No cambiar este nombre
				$atributos ["nombre"] = $esteCampo;
				$atributos ["tipo"] = "file";
				// $atributos ["obligatorio"] = true;
				$atributos ["etiquetaObligatorio"] = false;
				$atributos ["tabIndex"] = $tab ++;
				$atributos ["columnas"] = 1;
				$atributos ["estilo"] = "textoIzquierda";
				$atributos ["anchoEtiqueta"] = 400;
				$atributos ["tamanno"] = 500000;
				$atributos ["validar"] = "";
				$atributos ["etiqueta"] = $this->lenguaje->getCadena ( $esteCampo );
				// $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
				// $atributos ["valor"] = $valorCodificado;
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroTexto ( $atributos );
				unset ( $atributos );
				// ----------------FIN CONTROL: DOCUMENTO--------------------------------------------------------
				
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
						registro exitoso, sin embargo, si desea relacionar más Actividades, puede ingresar al Sistema una
						vez se registre y puede adicionar más Actividades Económicas, si así lo requiere.</b>";
					
				$atributos["mensaje"] = $mensajeLey;
				echo $this->miFormulario->cuadroMensaje($atributos);
				unset($atributos);
				
				
				
				
				// ---------------- CONTROL: Lista clase CIIU--------------------------------------------------------
				$esteCampo = "claseCIIUNat";
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
				$esteCampo = 'descripcionNat';
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
				
				
				?>
				
				<div class="panel panel-primary">
				    <div class="panel-heading">
				      <h3 class="panel-title">Términos de Registro</h3>
				    </div>
				    <div class="panel-body">
					      <div class="alert alert-success">
							<center><label for="condiciones">
				                            <strong>Acepta y reconoce bajo consentimiento propio el registro de información 
				                            personal en el Sistema de Registro Único y Banco de Proveedores de la
				                            Universidad Distrital Francisco Jóse de Caldas</strong>
				            </label></center>
			                <center><input id="condicionesCheckNat" type="checkbox"></center>
						 </div>
				    </div>
				</div>
				
				
				<?php
				 
				// ------------------Division para los botones-------------------------
				$atributos ["id"] = "botonesNat";
				$atributos ["estilo"] = "marcoBotones";
				echo $this->miFormulario->division ( "inicio", $atributos );
				unset ( $atributos );
				{
					// -----------------CONTROL: Botón ----------------------------------------------------------------
					$esteCampo = 'botonGuardarNat';
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
				echo $this->miFormulario->division ( 'fin' );
				
				
				
				
				
				
			}
			echo $this->miFormulario->marcoAgrupacion ( 'fin' );
			
			//*************************************************************************************************************************************+
			
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
