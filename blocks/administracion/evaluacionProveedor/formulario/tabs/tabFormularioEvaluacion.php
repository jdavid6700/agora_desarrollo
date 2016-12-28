<?php

namespace administracion\evaluacionProveedor\formulario\tabs;

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
		
		
		
		
		//var_dump($_REQUEST);
		
		
		
		//DATOS DEL CONTRATO
		//$cadenaSql = $this->miSql->getCadenaSql ( 'contratoByID', $_REQUEST["idContrato"] );
		//$consulta = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
		$datosContrato = array (
				'numeroContrato' => $_REQUEST["numeroContrato"],
				'vigenciaContrato' => $_REQUEST["vigenciaContrato"]
		);
		
		$cadenaSql = $this->miSql->getCadenaSql ( 'consultarContratosARGOByNum', $datosContrato );
		$consulta2 = $argoRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
		//$cadenaSql = $this->miSql->getCadenaSql ( 'contratoByProveedor', $_REQUEST["idContrato"] );
		//$consulta3 = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
		//var_dump($consulta2);
		
		if($consulta2[0]['clase_contratista'] != "Único Contratista"){
			
// 			$cadenaSql = $this->miSql->getCadenaSql ( 'listarProveedoresXContrato', $_REQUEST["idContrato"] );
// 			$consulta3_1 = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
			
// 			$cadenaSql = $this->miSql->getCadenaSql ( 'consultarProveedoresByID', $consulta3_1[0][0] );
// 			$consulta4 = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
			
			$cantidad = "multiple";
			//$numeroPro = count($consulta3);
			
		}else{
			$cadenaSql = $this->miSql->getCadenaSql ( 'consultarProveedorByID', $consulta2[0]['identificacion_contratista'] );
			$consulta4 = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
			
			$cantidad = "individual";
			$numeroPro = 1;
		}
		
		
		
		//echo $cadenaSql;
		//var_dump($_REQUEST);

		$esteCampo = "marcoContrato";
		$atributos ['id'] = $esteCampo;
		$atributos ["estilo"] = "jqueryui";
		$atributos ['tipoEtiqueta'] = 'inicio';
		$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
		echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );                
                
				//INICIO INFO CONTRATO
				//echo "<span class='textoElegante textoEnorme textoAzul'>Número Contrato : </span>"; 
                //echo "<span class='textoElegante textoGrande textoGris'>". $consulta2[0]['numero_contrato']  . " - " . $consulta2[0]['vigencia'] . "</span></br>"; 
				
				
                // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                $esteCampo = 'objetoContrato';
                $atributos ['id'] = $esteCampo;
                $atributos ['nombre'] = $esteCampo;
                $atributos ['tipo'] = 'text';
                $atributos ['estilo'] = 'jqueryui';
                $atributos ['marco'] = true;
                $atributos ['estiloMarco'] = '';
                $atributos ["etiquetaObligatorio"] = true;
                $atributos ['filas'] = 5;
                $atributos ['dobleLinea'] = 0;
                $atributos ['tabIndex'] = $tab;
                $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
                $atributos ['validar'] = '';
                $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                $atributos ['deshabilitado'] = true;
                $atributos ['tamanno'] = 50;
                $atributos ['maximoTamanno'] = '';
                $atributos ['anchoEtiqueta'] = 300;
                
                $atributos ['valor'] = mb_strtoupper($consulta2[0]['objeto_contrato'],'utf-8');
                
                $tab ++;
                
                // Aplica atributos globales al control
                $atributos = array_merge ( $atributos, $atributosGlobales );
                echo $this->miFormulario->campoTextArea ( $atributos );
                unset ( $atributos );
                
                
				
				//echo "<span class='textoElegante textoEnorme textoAzul'>Objeto de Contrato : </span>"; 
                //echo "<span class='textoElegante textoGrande textoGris'>". $consulta2[0]['objeto_contrato'] . "</span></br>"; 
				//echo "<span class='textoElegante textoEnorme textoAzul'>Proveedor : </span><br><b>";
				
                $cadenaSql = $this->miSql->getCadenaSql ( 'consultarProveedorDatos', $consulta2[0]['identificacion_contratista'] );
                $resultadoProveedorTit = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
				
                // ----------------INICIO CONTROL: Campo de Texto DEPENDENCIA DESTINO--------------------------------------------------------
                $esteCampo = 'nombreProveedorTitular';
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
                
                $atributos ['valor'] = $resultadoProveedorTit[0]['nom_proveedor'];
                
                $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                $atributos ['deshabilitado'] = true;
                $atributos ['tamanno'] = 50;
                $atributos ['maximoTamanno'] = '30';
                $atributos ['anchoEtiqueta'] = 150;
                $tab ++;
                
                // Aplica atributos globales al control
                $atributos = array_merge ( $atributos, $atributosGlobales );
                echo $this->miFormulario->campoCuadroTexto ( $atributos );
                unset ( $atributos );
                // ----------------FIN CONTROL: Campo de Texto DEPENDENCIA DESTINO--------------------------------------------------------
                
                // ----------------INICIO CONTROL: Campo de Texto DEPENDENCIA DESTINO--------------------------------------------------------
                $esteCampo = 'tipoProveedorTitular';
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
                
                $atributos ['valor'] = $resultadoProveedorTit[0]['tipopersona'];
                
                $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                $atributos ['deshabilitado'] = true;
                $atributos ['tamanno'] = 20;
                $atributos ['maximoTamanno'] = '30';
                $atributos ['anchoEtiqueta'] = 150;
                $tab ++;
                
                // Aplica atributos globales al control
                $atributos = array_merge ( $atributos, $atributosGlobales );
                echo $this->miFormulario->campoCuadroTexto ( $atributos );
                unset ( $atributos );
                // ----------------FIN CONTROL: Campo de Texto DEPENDENCIA DESTINO--------------------------------------------------------
				
                $esteCampo = 'idTitular';
                $atributos ["id"] = $esteCampo; // No cambiar este nombre
                $atributos ["tipo"] = "hidden";
                $atributos ['estilo'] = '';
                $atributos ["obligatorio"] = false;
                $atributos ['marco'] = true;
                $atributos ["etiqueta"] = "";
                $atributos ['valor'] = $resultadoProveedorTit[0]['id_proveedor'];
                $atributos = array_merge ( $atributos, $atributosGlobales );
                echo $this->miFormulario->campoCuadroTexto ( $atributos );
                unset ( $atributos );
                
                ?>
                		
                		 <div id="dialogoEval" style="display:none">   
                			
                			<div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix">
                				<center>
                			       <h2><?php echo $consulta2[0]['clase_contratista']; ?></h2>
                			    </center>
                			</div>
                			
                			<?php 
                
                
				
				if($cantidad == "individual"){
					
					
					$cadenaSql = $this->miSql->getCadenaSql ( 'consultarProveedorDatos', $consulta2[0]['identificacion_contratista'] );
					$resultadoProveedorDat = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
					
						
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
												
											//$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
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
												
											//$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
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
											
											//$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
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
											
											
											$cadenaSql = $this->miSql->getCadenaSql ( 'consultarContratoGrupal', $consulta2[0]['identificacion_contratista'] );
											$resultadoContratoGrupal = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
											
											
											$cadenaSql = $this->miSql->getCadenaSql ( 'consultarProveedorDatos', $consulta2[0]['identificacion_contratista'] );
											$resultadoProveedorGrup = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
											
											
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
													
												//$atributos ['titulo'] = $this->lenguaje->getCadena ( 'nombreProveedor' . 'Titulo' );
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
													
												//$atributos ['titulo'] = $this->lenguaje->getCadena ( 'identificacionProveedor' . 'Titulo' );
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
												
												//$atributos ['titulo'] = $this->lenguaje->getCadena ( 'participacionProveedor' . 'Titulo' );
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
													
												//$atributos ['titulo'] = $this->lenguaje->getCadena ( 'tipoProveedor' . 'Titulo' );
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
															$esteCampo = 'botonContratistaEval';
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
				
             
				//FIN INFO CONTRATO
        echo $this->miFormulario->marcoAgrupacion ( 'fin' );
                
		if ($cantidad == "individual") {
			$_REQUEST["idProveedor"] = $consulta2[0]['identificacion_contratista'];
		} else {
			$_REQUEST["idProveedor"] = 0;
			$i = 0;
			while ( $i < $numeroProveedoresConsorcio ) {
				$_REQUEST["idProveedor".$i] = $resultadoContratoGrupal[$i]["id_contratista"];
				$i ++;
			}
		}  
		
	
                
                
		//var_dump($_REQUEST);
	
		$esteCampo = "marcoDatosEval";
		$atributos ['id'] = $esteCampo;
		$atributos ["estilo"] = "jqueryui";
		$atributos ['tipoEtiqueta'] = 'inicio';
		$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
		echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );		

			$esteCampo = "marcoCumplimiento";
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
			$atributos ['tipoEtiqueta'] = 'inicio';
			$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
			echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
			
			// ---------------- CONTROL: Lista TIEMPO ENTREGA--------------------------------------------------------
				$esteCampo = "tiempoEntrega";
				$atributos ['nombre'] = $esteCampo;
				$atributos ['id'] = $esteCampo;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['tab'] = $tab ++;
				$atributos ['anchoEtiqueta'] = 850;
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
						array ( 12, 'Si' ),
						array ( 0, 'No' )
				);
				$atributos ['matrizItems'] = $matrizItems;
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroLista ( $atributos );
				unset ( $atributos );
			// ----------------FIN CONTROL: Lista TIEMPO ENTREGA--------------------------------------------------------

			// ---------------- CONTROL: Lista CANTIDADES --------------------------------------------------------
				$esteCampo = "cantidades";
				$atributos ['nombre'] = $esteCampo;
				$atributos ['id'] = $esteCampo;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['tab'] = $tab ++;
				$atributos ['anchoEtiqueta'] = 850;
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
						array ( 12, 'Si' ),
						array ( 0, 'No' )
				);
				$atributos ['matrizItems'] = $matrizItems;
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroLista ( $atributos );
				unset ( $atributos );
			// ----------------FIN CONTROL: Lista CANTIDADES--------------------------------------------------------			
				
			echo $this->miFormulario->marcoAgrupacion ( 'fin' );

			$esteCampo = "marcoCalidad";
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
			$atributos ['tipoEtiqueta'] = 'inicio';
			$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
			echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
			
			// ---------------- CONTROL: Lista CONFORMIDAD--------------------------------------------------------
				$esteCampo = "conformidad";
				$atributos ['nombre'] = $esteCampo;
				$atributos ['id'] = $esteCampo;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['tab'] = $tab ++;
				$atributos ['anchoEtiqueta'] = 850;
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
						array ( 20, 'Si' ),
						array ( 0, 'No' )
				);
				$atributos ['matrizItems'] = $matrizItems;
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroLista ( $atributos );
				unset ( $atributos );
			// ----------------FIN CONTROL: Lista CONFORMIDAD--------------------------------------------------------

			// ---------------- CONTROL: Lista FUNCIONALIDAD ADICIONAL --------------------------------------------------------
				$esteCampo = "funcionalidadAdicional";
				$atributos ['nombre'] = $esteCampo;
				$atributos ['id'] = $esteCampo;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['tab'] = $tab ++;
				$atributos ['anchoEtiqueta'] = 850;
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
						array ( 10, 'Si' ),
						array ( 0, 'No' )
				);
				$atributos ['matrizItems'] = $matrizItems;
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroLista ( $atributos );
				unset ( $atributos );
			// ----------------FIN CONTROL: Lista FUNCIONALIDAD ADICIONAL--------------------------------------------------------			
				
			echo $this->miFormulario->marcoAgrupacion ( 'fin' );

			$esteCampo = "marcoContractual";
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
			$atributos ['tipoEtiqueta'] = 'inicio';
			$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
			echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
			
			// ---------------- CONTROL: Lista RECLAMACIONES--------------------------------------------------------
				$esteCampo = "reclamaciones";
				$atributos ['nombre'] = $esteCampo;
				$atributos ['id'] = $esteCampo;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['tab'] = $tab ++;
				$atributos ['anchoEtiqueta'] = 850;
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
						array ( 0, 'Si' ),
						array ( 12, 'No' )
				);
				$atributos ['matrizItems'] = $matrizItems;
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroLista ( $atributos );
				unset ( $atributos );
			// ----------------FIN CONTROL: Lista RECLAMACIONES--------------------------------------------------------

			// ---------------- CONTROL: Lista RECLAMACIONES SOLUCION --------------------------------------------------------
				echo '<div id="reclamacionSolucion_div" style="display: none;">';
                                $esteCampo = "reclamacionSolucion";
				$atributos ['nombre'] = $esteCampo;
				$atributos ['id'] = $esteCampo;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['tab'] = $tab ++;
				$atributos ['anchoEtiqueta'] = 850;
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
						array ( 12, 'Si' ),
						array ( 0, 'No' )
				);
				$atributos ['matrizItems'] = $matrizItems;
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroLista ( $atributos );
				unset ( $atributos );
                               echo "</div>";
			// ----------------FIN CONTROL: Lista RECLAMACIONES SOLUCION--------------------------------------------------------			
			
			// ---------------- CONTROL: Lista SERVICIOS POS VENTA --------------------------------------------------------
                                $esteCampo = "servicioVenta";
				$atributos ['nombre'] = $esteCampo;
				$atributos ['id'] = $esteCampo;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['tab'] = $tab ++;
				$atributos ['anchoEtiqueta'] = 850;
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
						array ( 10, 'Si' ),
						array ( 0, 'No' )
				);
				$atributos ['matrizItems'] = $matrizItems;
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroLista ( $atributos );
				unset ( $atributos );
			// ----------------FIN CONTROL: Lista SERVICIOS POS VENTA--------------------------------------------------------				
				
			echo $this->miFormulario->marcoAgrupacion ( 'fin' );

			$esteCampo = "marcoGestion";
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
			$atributos ['tipoEtiqueta'] = 'inicio';
			$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
			echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
			
			// ---------------- CONTROL: Lista PROCEDIMIENTOS--------------------------------------------------------
				$esteCampo = "procedimientos";
				$atributos ['nombre'] = $esteCampo;
				$atributos ['id'] = $esteCampo;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['tab'] = $tab ++;
				$atributos ['anchoEtiqueta'] = 850;
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
						array ( 9, 'Excelente' ),
						array ( 6, 'Bueno' ),
						array ( 3, 'Regular' ),
						array ( 0, 'Malo' )
				);
				$atributos ['matrizItems'] = $matrizItems;
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroLista ( $atributos );
				unset ( $atributos );
			// ----------------FIN CONTROL: Lista PROCEDIMIENTOS--------------------------------------------------------

			// ---------------- CONTROL: Lista GARANTIA --------------------------------------------------------
				$esteCampo = "garantia";
				$atributos ['nombre'] = $esteCampo;
				$atributos ['id'] = $esteCampo;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['tab'] = $tab ++;
				$atributos ['anchoEtiqueta'] = 850;
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
						array ( 0, 'Si' ),
						array ( 15, 'No' )
				);
				$atributos ['matrizItems'] = $matrizItems;
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroLista ( $atributos );
				unset ( $atributos );
			// ----------------FIN CONTROL: Lista GARANTIA--------------------------------------------------------			

			// ---------------- CONTROL: Lista GARANTIA SATISFACCION--------------------------------------------------------
				echo '<div id="garantiaSatisfaccion_div" style="display: none;">';
                                $esteCampo = "garantiaSatisfaccion";
				$atributos ['nombre'] = $esteCampo;
				$atributos ['id'] = $esteCampo;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['tab'] = $tab ++;
				$atributos ['anchoEtiqueta'] = 850;
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
						array ( 15, 'Si' ),
						array ( 0, 'No' )
				);
				$atributos ['matrizItems'] = $matrizItems;
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroLista ( $atributos );
				unset ( $atributos );
                                echo "</div>";
			// ----------------FIN CONTROL: Lista GARANTIA SATISFACCION--------------------------------------------------------			
			
			echo $this->miFormulario->marcoAgrupacion ( 'fin' );
			
		echo $this->miFormulario->marcoAgrupacion ( 'fin' );
	
		$esteCampo = 'vigenciaContrato';
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
		
		$esteCampo = 'numeroContrato';
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
		
		$esteCampo = 'idProveedor';
		$atributos ["id"] = $esteCampo; // No cambiar este nombre
		$atributos ["tipo"] = "hidden";
		$atributos ['estilo'] = '';
		$atributos ["obligatorio"] = false;
		$atributos ['marco'] = true;
		$atributos ["etiqueta"] = "";
		$atributos ['valor'] = $_REQUEST [$esteCampo];
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
		$valorCodificado .= "&opcion=nuevaEvaluacion";
		//$valorCodificado .= "&usuario=".$_REQUEST['usuario'];
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

$miSeleccionador = new registrarForm ( $this->lenguaje, $this->miFormulario, $this->sql );

$miSeleccionador->miForm ();
?>