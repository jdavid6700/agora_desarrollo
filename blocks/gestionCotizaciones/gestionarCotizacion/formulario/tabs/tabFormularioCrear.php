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


		// Rescatar los datos de este bloque
		$esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );
		$miPaginaActual = $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
			
		$directorio = $this->miConfigurador->getVariableConfiguracion ( "host" );
		$directorio .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/index.php?";
		$directorio .= $this->miConfigurador->getVariableConfiguracion ( "enlace" );
			
		$rutaBloque = $this->miConfigurador->getVariableConfiguracion ( "host" );
		$rutaBloque .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/";
		$rutaBloque .= $esteBloque ['grupo'] . '/' . $esteBloque ['nombre'];

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


			if($_REQUEST['tipoNecesidad'] == "SERVICIO" || $_REQUEST['tipoNecesidad'] == "BIEN Y SERVICIO"){
				$marcoTipo = "marcoProveedoresConv";
				$tipoMarco = "marcoObjetoConv";
				$tipoSolicitud = $_REQUEST['tipoNecesidad'];
				$service = true;
				
				$cadenaSql = $this->miSql->getCadenaSql ( 'consultarNBCImp', $_REQUEST['idObjeto']  );
				$resultadoNBC = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
				
				if(!isset($_REQUEST['objetoNBC'])){
					$_REQUEST['objetoNBC'] = $resultadoNBC[0]['nucleo'];
					
				}				
				
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
		
		$cadenaSql = $this->miSql->getCadenaSql ( 'dependenciaUdistritalById', $solicitudCotizacion[0]['jefe_dependencia'] );
		$resultadoDependencia = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
			
		$cadenaSql = $this->miSql->getCadenaSql ( 'ordenadorUdistritalById', $solicitudCotizacion[0]['ordenador_gasto'] );
		$resultadoOrdenador = $coreRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
		$cadenaSql = $this->miSql->getCadenaSql ( 'buscarUsuario', $solicitudCotizacion[0]['usuario_creo'] );
		$resultadoUsuario = $frameworkRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
		if($solicitudCotizacion[0]['unidad_ejecutora'] == 1){
			$valorUnidadEjecutoraText = "1 - Rectoría";
		}else{
			$valorUnidadEjecutoraText = "2 - IDEXUD";
		}
		
		$esteCampo = $tipoMarco;
		$atributos ['id'] = $esteCampo;
		$atributos ["estilo"] = "jqueryui";
		$atributos ['tipoEtiqueta'] = 'inicio';
		$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
		echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
		
		
		
		
		//------------------Division para los botones-------------------------
		$atributos["id"]="botones";
		$atributos["estilo"]="marcoBotones widget";
		echo $this->miFormulario->division("inicio",$atributos);
		
		//******************************************************************************************************************************
		$variable = "pagina=" . $miPaginaActual;
		$variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variable, $directorio );
		
		// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
		$esteCampo = 'botonRegresar';
		$atributos ['id'] = $esteCampo;
		$atributos ['enlace'] = $variable;
		$atributos ['tabIndex'] = 1;
		$atributos ['estilo'] = 'textoSubtitulo';
		$atributos ['enlaceTexto'] = $this->lenguaje->getCadena ( $esteCampo );
		$atributos ['ancho'] = '10%';
		$atributos ['alto'] = '10%';
		$atributos ['redirLugar'] = true;
		echo $this->miFormulario->enlace ( $atributos );
		
		unset ( $atributos );
		//********************************************************************************************************************************
		
		//------------------Fin Division para los botones-------------------------
		echo $this->miFormulario->division("fin");
		
		
		echo '<script> 
			var pagTxt = 560;
			 </script>';
		
		
		echo "<span class='textoElegante textoEnorme textoAzul'>Título Cotización : </span>";
		echo "<span class='textoElegante textoGrande textoGris'><b>". $solicitudCotizacionCast[0]['titulo_cotizacion'] . "</b></span></br>";
		echo "<br>";
		echo "<span class='textoElegante textoEnorme textoAzul'>N° Cotización - Vigencia - Unidad Ejecutora : </span>";
		echo "<span class='textoElegante textoGrande textoGris'><b>". $_REQUEST['idObjeto']. " - ". $solicitudCotizacion[0]['vigencia'] . " - (" .$valorUnidadEjecutoraText. ")</b></span></br>";
		echo "<br>";
		echo "<span class='textoElegante textoEnorme textoAzul'>Fecha de Apertura : </span>";
		echo "<span class='textoElegante textoEnorme textoGris'><b>". $this->cambiafecha_format($solicitudCotizacionCast[0]['fecha_apertura']) . "</b></span></br>";
		echo "<br>";
		echo "<span class='textoElegante textoEnorme textoAzul'>Fecha de Cierre : </span>";
		echo "<span class='textoElegante textoEnorme textoGris'><b>". $this->cambiafecha_format($solicitudCotizacionCast[0]['fecha_cierre']). "</b></span></br>";
		echo "<br>";
		echo "<span class='textoElegante textoEnorme textoAzul'>Ordenador del Gasto Relacionado : </span>";
		echo "<span class='textoElegante textoGrande textoGris'><b>". $resultadoOrdenador[0][1]. "</b></span></br>";
		echo "<br>";
		echo "<span class='textoElegante textoEnorme textoAzul'>Dependencia Solicitante : </span>";
		echo "<span class='textoElegante textoGrande textoGris'><b>". $resultadoDependencia[0][1]. "</b></span></br>";
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
        		echo $dato['subclase'] . ' - ' . $dato['nombre'] . "</b><br>";
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
        	echo $resultadoNBC[0]['nucleo'] . ' - ' . $resultadoNBC[0]['nombre'] . "</b><br>";
        	
        	
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
				$esteCampo = "mensajeNoHayProveedoresPuntaje";
				$atributos ["id"] = $esteCampo; // Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
				$atributos ["etiqueta"] = "";
				$atributos ["estilo"] = "centrar";
				$atributos ["tipo"] = 'error';
				$atributos ["mensaje"] = "No se encontraron personas, actualmente <h2><b>(" ."0" . ")</b></h2> personas cumplen con las características de la solicitud de cotización.
						<br>
						<br>
						Por favor modifique la solicitud, diríjase al <b>Módulo de Gestión de Solicitudes de Cotización</b> y realice los cambios correspondientes.
						<br>
						<br>
						<br>
						Solicitud de Cotización : <b>". $solicitudCotizacion[0]['numero_solicitud'] ."</b>
						
						
						";
				
				echo $this->miFormulario->cuadroMensaje ( $atributos );
				unset ( $atributos );
				// -------------FIN Control Formulario----------------------
				// ------------------FIN Division para los botones-------------------------
				echo $this->miFormulario->division ( "fin" );
				unset ( $atributos );

			
			
		} else {


			//Se da un tratamiento especial a la Cotización con NBC Nulo - Análogo a BIENES
			if(isset($_REQUEST['objetoNBC']) && $_REQUEST['objetoNBC'] == '0'){
				$service = false;
			}
			
			// ------- FILTRAR POR ACTIVIDAD ECONOMICA
			
			if($service){
				$datos = array (
						'actividadEconomica' => $actividades,
						'objetoNBC' => $_REQUEST ['objetoNBC']
				);
				
				
				$cadenaSql = $this->miSql->getCadenaSql ( 'proveedoresByClasificacionConvNatural', $datos );
				$resultadoProveedorNatural = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );

				$cadenaSql = $this->miSql->getCadenaSql ( 'proveedoresByClasificacionConvJuridica', $datos );
				$resultadoProveedorJuridica = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );



				if($resultadoProveedorNatural && $resultadoProveedorJuridica){
					$resultadoProveedor = array_merge($resultadoProveedorNatural,$resultadoProveedorJuridica);
				}else if($resultadoProveedorNatural && !$resultadoProveedorJuridica){
					$resultadoProveedor = $resultadoProveedorNatural;
				}else if(!$resultadoProveedorNatural && $resultadoProveedorJuridica){
					$resultadoProveedor = $resultadoProveedorJuridica;
				}else{
					$resultadoProveedor = false;
				}



				//************* SEGMENTAR **************************

				?>
					<center>
					<style type="text/css">
					.tg  { border: 2px solid #000000;border-collapse:collapse;border-spacing:0;border-color:#aaa;}
					.tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;border-top-width:1px;border-bottom-width:1px;border-color:#aaa;color:#333;background-color:#fff;}
					.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;border-top-width:1px;border-bottom-width:1px;border-color:#aaa;color:#fff;background-color:#f38630;}
					.tg .tg-x5q1{font-size:16px;text-align:left;vertical-align:top}
					.tg .tg-13pz{font-size:18px;text-align:center;vertical-align:top}
					.tg .tg-qggo{font-size:16px;background-color:#f38630;color:#ffffff;text-align:center;vertical-align:top}
					.tg .tg-j1sc{font-size: 22px;
								  font-weight: bold;
								  color: #E6E6E6;
								  background: #000000;
								  background: -moz-linear-gradient(top, #404040 0%, #191919 66%, #000000 100%);
								  background: -webkit-linear-gradient(top, #404040 0%, #191919 66%, #000000 100%);
								  background: linear-gradient(to bottom, #404040 0%, #191919 66%, #000000 100%);
								  border-top: 1px solid #4A4A4A;text-align:center;}
					</style>
					<table class="tg">
					  <tr>
					    <th class="tg-13pz" colspan="3"><span style="font-weight:bold">Consolidado de Proveedores (Criterios Atendidos)</span></th>
					  </tr>
					  <tr>
					    <td class="tg-qggo"><span style="font-weight:bold">(Actividad Económica)</span></td>
					    <td class="tg-qggo"><span style="font-weight:bold">(Núcleo de Conocimiento)</span></td>
					    <td class="tg-qggo"><span style="font-weight:bold">(Número de Proveedores que Aplican)</span></td>
					  </tr>

				<?

				foreach ($resultadoActividades as $dato):

					$datosCn = array (
							'actividadEconomica' => "'".$dato[0]."'",
							'objetoNBC' => $datos['objetoNBC']
					);

					$cadenaSql = $this->miSql->getCadenaSql ( 'proveedoresByClasificacionConvNatural', $datosCn );
					$resultadoConsolidadoNatural = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );

					$cadenaSql = $this->miSql->getCadenaSql ( 'proveedoresByClasificacionConvJuridica', $datosCn );
					$resultadoConsolidadoJuridica = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );

					

					?>
					<tr>
					    <td class="tg-x5q1"><span style="color:rgb(49, 102, 255)">  <?   echo $dato[0] . " - " . $dato[1];    ?> </span></td>
					    <td class="tg-x5q1"><span style="color:rgb(49, 102, 255)">   <?   echo $datos['objetoNBC'] . " - " . $resultadoNBC[0]['nombre'];    ?> </span></td>
					    <td class="tg-j1sc"><span style="font-weight:bold"><?   if($resultadoConsolidadoNatural){echo count($resultadoConsolidadoNatural);}else{echo 0;}    ?></span></td>
					</tr>
					<tr>
					    <td class="tg-x5q1"><span style="color:rgb(49, 102, 255)">  <?   echo $dato[0] . " - " . $dato[1];    ?> </span></td>
					    <td class="tg-x5q1"><span style="color:rgb(49, 102, 255)">  NO APLICA PERSONA JURÍDICA </span></td>
					    <td class="tg-j1sc"><span style="font-weight:bold"><?   if($resultadoConsolidadoJuridica){echo count($resultadoConsolidadoJuridica);}else{echo 0;}    ?></span></td>
					</tr>
					<?
					
				endforeach;

				?>
				</table>
				</center>
				<?

				//************* SEGMENTAR **************************



				
			}else{
				$datos = array (
						'actividadEconomica' => $actividades
				);
				

				$cadenaSql = $this->miSql->getCadenaSql ( 'proveedoresByClasificacion', $datos );
				$resultadoProveedor = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );


				//************* SEGMENTAR **************************

				?>
					<center>
					<style type="text/css">
					.tg  { border: 2px solid #000000;border-collapse:collapse;border-spacing:0;border-color:#aaa;}
					.tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;border-top-width:1px;border-bottom-width:1px;border-color:#aaa;color:#333;background-color:#fff;}
					.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;border-top-width:1px;border-bottom-width:1px;border-color:#aaa;color:#fff;background-color:#f38630;}
					.tg .tg-x5q1{font-size:16px;text-align:left;vertical-align:top}
					.tg .tg-13pz{font-size:18px;text-align:center;vertical-align:top}
					.tg .tg-qggo{font-size:16px;background-color:#f38630;color:#ffffff;text-align:center;vertical-align:top}
					.tg .tg-j1sc{font-size: 22px;
								  font-weight: bold;
								  color: #E6E6E6;
								  background: #000000;
								  background: -moz-linear-gradient(top, #404040 0%, #191919 66%, #000000 100%);
								  background: -webkit-linear-gradient(top, #404040 0%, #191919 66%, #000000 100%);
								  background: linear-gradient(to bottom, #404040 0%, #191919 66%, #000000 100%);
								  border-top: 1px solid #4A4A4A;text-align:center;}
					</style>
					<table class="tg">
					  <tr>
					    <th class="tg-13pz" colspan="2"><span style="font-weight:bold">Consolidado de Proveedores (Criterios Atendidos)</span></th>
					  </tr>
					  <tr>
					    <td class="tg-qggo"><span style="font-weight:bold">(Actividad Económica)</span></td>
					    <td class="tg-qggo"><span style="font-weight:bold">(Número de Proveedores que Aplican)</span></td>
					  </tr>

				<?

				foreach ($resultadoActividades as $dato):

					$datosCn = array (
							'actividadEconomica' => "'".$dato[0]."'"
					);

					$cadenaSql = $this->miSql->getCadenaSql ( 'proveedoresByClasificacion', $datosCn );
					$resultadoConsolidado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );

					?>
					<tr>
					    <td class="tg-x5q1"><span style="color:rgb(49, 102, 255)">  <?   echo $dato[0] . " - " . $dato[1];    ?> </span></td>
					    <td class="tg-j1sc"><span style="font-weight:bold"><?   if($resultadoConsolidado){echo count($resultadoConsolidado);}else{echo 0;}    ?></span></td>
					  </tr>
					<?
					
				endforeach;

				?>
				</table>
				</center>
				<?

				//************* SEGMENTAR **************************

				
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
				$atributos ["mensaje"] = "No se encontraron personas, actualmente <h2><b>(" ."0" . ")</b></h2> personas cumplen con las características de la solicitud de cotización.
						<br>
						<br>
						Por favor modifique la solicitud, diríjase al <b>Módulo de Gestión de Solicitudes de Cotización</b> y realice los cambios correspondientes.
						<br>
						<br>
						<br>
						Solicitud de Cotización : <b>". $solicitudCotizacion[0]['numero_solicitud'] ."</b>
						
						
						";
				
				echo $this->miFormulario->cuadroMensaje ( $atributos );
				unset ( $atributos );
				// -------------FIN Control Formulario----------------------
				// ------------------FIN Division para los botones-------------------------
				echo $this->miFormulario->division ( "fin" );
				unset ( $atributos );
				
				
			} else {
				
				
				$proveedores = array ();
				foreach ( $resultadoProveedor as $dato ) :
				array_push ( $proveedores, $dato ['id_proveedor'] );
				endforeach;
					
				
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
				
				
				if(isset($_REQUEST['botonTrue']) && $_REQUEST['botonTrue']){
					
					
					// ------------------INICIO Division para los botones-------------------------
					$atributos ["id"] = "divEncontro";
					$atributos ["estilo"] = "marcoBotones";
					echo $this->miFormulario->division ( "inicio", $atributos );
					// -------------SECCION: Controles del Formulario-----------------------
					$esteCampo = "mensajeHayProveedores";
					$atributos ["id"] = $esteCampo; // Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
					$atributos ["etiqueta"] = "";
					$atributos ["estilo"] = "centrar";
					$atributos ["tipo"] = 'information';
					$atributos ["mensaje"] = "
										
										<h1>PROCESAR SOLICITUD: <b>". $solicitudCotizacion[0]['numero_solicitud'] ."</b></h1>
							
										Se encontraron <h2><b>(" .count($resultadoProveedor) . ")</b></h2> personas que cumplen con las características de la solicitud de cotización.
										<br>
										<br>
										Puede procesar la solicitud, se informarán todos los proveedores que puedan atender, de acuerdo con las características registradas.
										<br>
										<br>
										<br>
										Una vez procesada, no se pueden hacer más modificaciones a la solicitud N° <b>". $solicitudCotizacion[0]['numero_solicitud'] ."</b>, usted podrá ver las
												respuestas de los proveedores hasta después de la fecha de cierre relacionada.
					
					
										";
					
					echo $this->miFormulario->cuadroMensaje ( $atributos );
					unset ( $atributos );
					// -------------FIN Control Formulario----------------------
					// ------------------FIN Division para los botones-------------------------
					echo $this->miFormulario->division ( "fin" );
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
					
					
					
				}else{
					
					// ------------------INICIO Division para los botones-------------------------
					$atributos ["id"] = "divEncontro";
					$atributos ["estilo"] = "marcoBotones";
					echo $this->miFormulario->division ( "inicio", $atributos );
					// -------------SECCION: Controles del Formulario-----------------------
					$esteCampo = "mensajeHayProveedores";
					$atributos ["id"] = $esteCampo; // Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
					$atributos ["etiqueta"] = "";
					$atributos ["estilo"] = "centrar";
					$atributos ["tipo"] = 'success';
					$atributos ["mensaje"] = "Se encontraron <h2><b>(" .count($resultadoProveedor) . ")</b></h2> personas que cumplen con las características de la solicitud de cotización.
										<br>
										<br>
										Si desea procesar la solicitud, diríjase al <b>Módulo de Gestión de Solicitudes de Cotización</b> y realice el procesamiento correspondiente.
										<br>
										<br>
										<br>
										Solicitud de Cotización : <b>". $solicitudCotizacion[0]['numero_solicitud'] ."</b>
					
					
										";
					
					echo $this->miFormulario->cuadroMensaje ( $atributos );
					unset ( $atributos );
					// -------------FIN Control Formulario----------------------
					// ------------------FIN Division para los botones-------------------------
					echo $this->miFormulario->division ( "fin" );
					unset ( $atributos );
					
				}
				
				
				
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