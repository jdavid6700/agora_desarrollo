<?php
namespace administracion\gestionObjeto\formulario;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	
	exit ();
}
class Formulario {
	var $miConfigurador;
	var $lenguaje;
	var $miFormulario;
	var $miSql;
	
	const OBJETOCOTIZACION = 'COTIZACION'; //Estado objeto creado
	
	function __construct($lenguaje, $formulario, $sql) {
		
		$this->miConfigurador = \Configurador::singleton ();
		$this->miConfigurador->fabricaConexiones->setRecursoDB ( 'principal' );
		$this->lenguaje = $lenguaje;
		$this->miFormulario = $formulario;
		$this->sql = $sql;		
	}
	
	function cambiafecha_format($fecha) {
		ereg("([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})", $fecha, $mifecha);
		$fechana = $mifecha[3] . "/" . $mifecha[2] . "/" . $mifecha[1];
		return $fechana;
	}
	
	function formulario() {
		// Rescatar los datos de este bloque
		$esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );
		$miPaginaActual = $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
		
		$directorio = $this->miConfigurador->getVariableConfiguracion ( "host" );
		$directorio .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/index.php?";
		$directorio .= $this->miConfigurador->getVariableConfiguracion ( "enlace" );
		
		$rutaBloque = $this->miConfigurador->getVariableConfiguracion ( "host" );
		$rutaBloque .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/";
		$rutaBloque .= $esteBloque ['grupo'] ."/". $esteBloque ['nombre'];
		
		// ---------------- SECCION: Par�metros Globales del Formulario ----------------------------------
		/**
		 * Atributos que deben ser aplicados a todos los controles de este formulario.
		 * Se utiliza un arreglo
		 * independiente debido a que los atributos individuales se reinician cada vez que se declara un campo.
		 *
		 * Si se utiliza esta t�cnica es necesario realizar un mezcla entre este arreglo y el espec�fico en cada control:
		 * $atributos= array_merge($atributos,$atributosGlobales);
		 */
		$atributosGlobales ['campoSeguro'] = 'true';
		
		// -------------------------------------------------------------------------------------------------
		$conexion = "estructura";
		$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
		
		$conexion = "framework";
		$frameworkRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
		
		//$conexion = "sicapital";
		//$siCapitalRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
		
		//$this->cadena_sql = $this->miSql->getCadenaSql ( "listaObjetoContratar", self::OBJETOCOTIZACION );
		//$resultado = $esteRecursoDB->ejecutarAcceso ( $this->cadena_sql, "busqueda" );
		
		
		// ---------------- SECCION: Parámetros Generales del Formulario ----------------------------------
		$esteCampo = $esteBloque ['nombre']."ConsultarCot";
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
    
    
    $esteCampo = "marcoDatosCotizacion";
    $atributos ['id'] = $esteCampo;
    $atributos ["estilo"] = "jqueryui";
    $atributos ['tipoEtiqueta'] = 'inicio';
    $atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
    echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );

    
    
    $miPaginaActual = $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
    	
    $directorio = $this->miConfigurador->getVariableConfiguracion ( "host" );
    $directorio .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/index.php?";
    $directorio .= $this->miConfigurador->getVariableConfiguracion ( "enlace" );
    	
    $variable = "pagina=" . $miPaginaActual;
    $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variable, $directorio );
    	
    $atributos["id"] = "divNoEncontroEgresado";
    $atributos["estilo"] = "marcoBotones";

    //$atributos["estiloEnLinea"]="display:none"; 
    echo $this->miFormulario->division("inicio", $atributos);
    

    //********************************************************************************************************************************
    
    $datosSolicitudNecesidad = array (
    		'idObjeto' => $_REQUEST['idSolicitud']
    );
    
    //*********************************************************************************************************************************
    
    $cadena_sql = $this->sql->getCadenaSql ( "estadoSolicitudAgora", $datosSolicitudNecesidad);
    $resultado = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
    
    if(isset($resultado)) {
    	$estadoSolicitud = $resultado[0]['estado_cotizacion'];
    
    
    	$cadena_sql = $this->sql->getCadenaSql ( "informacionSolicitudAgora", $datosSolicitudNecesidad);
    	$resultadoNecesidadRelacionada = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
    
    	//$cadena_sql = $this->sql->getCadenaSql ( "informacionCIIURelacionada", $datosSolicitudNecesidad);
    	//$resultadoNecesidadRelacionadaCIIU = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );

    }
		
		//Buscar usuario para enviar correo
		$cadenaSql = $this->sql->getCadenaSql ( 'buscarProveedoresInfoCotizacion', $resultadoNecesidadRelacionada[0]['id'] );
		$resultadoProveedor = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );

		//Buscar usuario para enviar correo
		//$cadenaSql = $this->sql->getCadenaSql ( 'objetoContratar', $resultadoNecesidadRelacionada[0]['id'] );
		//$objetoEspecifico = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
		$cadenaSql = $this->sql->getCadenaSql('infoCotizacion', $resultadoNecesidadRelacionada[0]['id']);
		$objetoEspecifico = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");

		
		if($objetoEspecifico[0]['tipo_necesidad'] == 2 || $objetoEspecifico[0]['tipo_necesidad'] == 3){
			$convocatoria = true;
				
			$cadenaSql = $this->sql->getCadenaSql ( 'consultarNBCImp', $resultadoNecesidadRelacionada[0]['id']  );
			$resultadoNBC = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
			
			$tipoMN = "Cotización";
				
		}else{
			$convocatoria = false;
			
			$tipoMN = "Cotización";
		}

		if(isset($objetoEspecifico[0]['estado_cotizacion'])){//CAST
			switch($objetoEspecifico[0]['estado_cotizacion']){
				case 1 :
					$estadoCotizacionArq = 'RELACIONADO';
					break;
				case 2 :
					$estadoCotizacionArq = 'COTIZACION';
					break;
				case 3 :
					$estadoCotizacionArq = 'ASIGNADO';
					break;
				case 4 :
					$estadoCotizacionArq = 'CANCELADO';
					break;
				case 5 :
					$estadoCotizacionArq = 'PROCESADO';
					break;
				case 6 :
					$estadoCotizacionArq = 'RECOTIZACION';
					break;
				case 7 :
					$estadoCotizacionArq = 'SELECCIONADO';
					break;
				case 8 :
					$estadoCotizacionArq = 'RECHAZADO';
					break;
			}
		}
		
		$tipo = 'success';
		if($convocatoria){
			$mensaje =  "Estado del Objeto a Contratar: ".$estadoCotizacionArq.".<br> La Solicitud de Cotización ya ha sido Cerrada. <br>
						<br>
						El Proveedor que fue <b>Seleccionado</b> aparece resaltado en color amarillo en la siguiente Lista.";
		}else{
			$mensaje =  "Estado del Objeto a Contratar: ".$estadoCotizacionArq.".<br> La Solicitud de Cotización ya ha sido Cerrada. <br>
						<br>
						El Proveedor que fue <b>Seleccionado</b> aparece resaltado en color amarillo en la siguiente Lista.";
		}
		$boton = "regresar";
		
		//INICIO enlace boton descargar resumen
		$variableResumen = "pagina=" . $miPaginaActual;
		$variableResumen.= "&action=".$esteBloque["nombre"];
		$variableResumen.= "&bloque=" . $esteBloque["id_bloque"];
		$variableResumen.= "&bloqueGrupo=" . $esteBloque["grupo"];
		$variableResumen.= "&opcion=resumen";
		$variableResumen.= "&idObjeto=" . $resultadoNecesidadRelacionada[0]['id'];
		$variableResumen.= "&idCodigo=" . "1112";
		$variableResumen.= "&proveedoresView=true";
		$variableResumen = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableResumen, $directorio);
		
		//------------------Division para los botones-------------------------
		$atributos["id"]="botones";
		$atributos["estilo"]="marcoBotones widget";
		echo $this->miFormulario->division("inicio",$atributos);

		$enlace = "<a href='".$variableResumen."'>";		
		if($convocatoria){
			$enlace.="<img src='".$rutaBloque."/images/pdf.png' width='35px'><br>Ver Solicitud Cotización";
		}else{
			$enlace.="<img src='".$rutaBloque."/images/pdf.png' width='35px'><br>Ver Solicitud Cotización";
		}
		$enlace.="</a><br><br>";
		echo $enlace;
		//------------------Fin Division para los botones-------------------------
		echo $this->miFormulario->division("fin");
		//FIN enlace boton descargar resumen
		
		
		
		// ---------------- SECCION: Controles del Formulario -----------------------------------------------
		$esteCampo = 'mensaje';
		$atributos["id"] = $esteCampo; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
		$atributos["etiqueta"] = "";
		$atributos["estilo"] = "centrar";
		$atributos["tipo"] = $tipo;
		$atributos["mensaje"] = $mensaje;
		echo $this->miFormulario->cuadroMensaje($atributos);
		unset($atributos);
		// ------------------Division para los botones-------------------------
		$atributos ["id"] = "botones";
		$atributos ["estilo"] = "marcoBotones";
		echo $this->miFormulario->division("inicio", $atributos);
		
		
		
		echo "<div id='marcoDatosLoad' style='width: 100%;height: 900px'>
			<div style='width: 100%;height: 100px'>
			</div>
			<center><img src='" . $rutaBloque . "/images/loading.gif'".' width=20% height=20% vspace=15 hspace=3 >
			</center>
		  </div>';
		
		
		if($objetoEspecifico[0]['estado_cotizacion'] == 1 || $objetoEspecifico[0]['estado_cotizacion'] == 2){
			
			
			$esteCampo = "marcoDatosCotizacionListPer";
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
			$atributos ['tipoEtiqueta'] = 'inicio';
			$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo ) . " (Número Cotización: ". $resultadoNecesidadRelacionada[0]['numero_solicitud'] ." - Titulo: " . substr(str_replace("<p>", "", $resultadoNecesidadRelacionada[0]['titulo_cotizacion']), 0, 100) . "...)";
			echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
			
			echo '<table id="tablaObjetosSinCotizacion" class="display" cellspacing="0" width="100%"> ';
			
			echo "<thead>
							<tr>
								<th><center>Documento</center></th>
								<th><center>Proveedor</center></th>
								<th><center>Tipo Persona</center></th>
								<th><center>Dirección</center></th>
								<th><center>Web</center></th>
								<th><center>Correo</center></th>
								<th><center>Ubicación Contacto</center></th>
"/* 			                    <th><center>Puntaje Evaluacióń</center></th>
			 <th><center>Clasificación Evaluación</center></th>*/."
    							<th><center>Estado Cotización</center></th>
								<th><center>Detalle Persona</center></th>
								<th><center>Responder</center></th>
								<th><center> " . $tipoMN . "</center></th>
							</tr>
							</thead>
							<tbody>";
			
				
			if($resultadoProveedor){
			
				foreach ($resultadoProveedor as $dato):
					
				$datosSolicitud = array (
						'objeto' => $resultadoNecesidadRelacionada[0]['id'],
						'proveedor' => $dato['id_proveedor']
				);
					
				$cadenaSql = $this->sql->getCadenaSql ( 'consultarSolicitudxProveedor', $datosSolicitud );
				$resultadoSolicitudxPersona = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
			
				$cadenaSql = $this->sql->getCadenaSql ( 'consultarRespuestaxProveedor', $resultadoSolicitudxPersona[0]['id'] );
				$respuestaCotizacionProveedor = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
			
			
				if($respuestaCotizacionProveedor){ //Se manejaba ESTADO Cerrado ---- $resultadoSolicitudxPersona[0]['estado'] == 'CERRADO'
						
						
					$cadenaSql = $this->sql->getCadenaSql ( 'consultarRespuestaxSolicitante', $resultadoSolicitudxPersona[0]['id'] );
					$respuestaCotizacionSolicitante = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
						
						
					if($respuestaCotizacionSolicitante){
			
						//INHABILITADO VOLVER A RESPONDER
						$variableView = "#";
						$imagenView = "cancel.png";
			
					}else{
			
						$variableView = "pagina=" . $miPaginaActual; // pendiente la pagina para modificar parametro
						$variableView .= "&opcion=resultadoCotizacion";
						$variableView .= "&idSolicitudCotizacion=" . "XXXX";
						$variableView .= "&idProveedor=" . $dato['id_proveedor'];
						$variableView .= "&idSolicitud=" . $objetoEspecifico[0]['numero_solicitud'];
						$variableView .= "&vigencia=" . $objetoEspecifico[0]['vigencia'];
						$variableView .= "&unidadEjecutora=" . $_REQUEST['unidadEjecutora'];
						$variableView .= "&tipoCotizacion=" . $objetoEspecifico[0]['tipo_necesidad'];
						$variableView .= "&idObjeto=" . $datosSolicitudNecesidad['idObjeto'];
						$variableView .= "&idSolicitudIndividual=" . $resultadoSolicitudxPersona[0]['id'];
						$variableView = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableView, $directorio );
						$imagenView = 'resPro.png';
			
					}
						
						
						
					$variableAdd = "pagina=" . $miPaginaActual; // pendiente la pagina para modificar parametro
					$variableAdd .= "&opcion=verCotizacionProveedor";
					$variableAdd .= "&idSolicitudCotizacion=" . "XXXX";
					$variableAdd .= "&idProveedor=" . $dato['id_proveedor'];
					$variableAdd .= "&idSolicitud=" . $objetoEspecifico[0]['numero_solicitud'];
					$variableAdd .= "&vigencia=" . $objetoEspecifico[0]['vigencia'];
					$variableAdd .= "&unidadEjecutora=" . $_REQUEST['unidadEjecutora'];
					$variableAdd .= "&tipoCotizacion=" . $objetoEspecifico[0]['tipo_necesidad'];
					$variableAdd .= "&idObjeto=". $datosSolicitudNecesidad['idObjeto'];
					$variableAdd .= "&idSolicitudIndividual=" . $resultadoSolicitudxPersona[0]['id'];
					$variableAdd = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableAdd, $directorio );
					$imagenAdd = 'addPro.png';
						
					$estadoRes = "RESPONDIDA";
						
				}else{
						
					//INHABILITADO RESPONDER
					$variableView = "#";
					$imagenView = "cancel.png";
						
					$variableAdd = "#";
					$imagenAdd = "cancel.png";
						
					$estadoRes = "SIN RESPUESTA";
						
				}
			
			
					
				$variableViewPro = "pagina="."consultaGeneralProveedor";//$miPaginaActual; // pendiente la pagina para modificar parametro
				$variableViewPro .= "&opcion=verPro";
				$variableViewPro .= "&idProveedor=" . $dato['id_proveedor'];
				$variableViewPro .= "&paginaOrigen=" . $miPaginaActual;
				$variableViewPro .= "&opcionOrigen=verCotizacionSolicitud";
				$variableViewPro .= "&idSolicitudOrigen=" . $_REQUEST['idSolicitud'];
				$variableViewPro .= "&vigenciaOrigen=" . $objetoEspecifico[0]['vigencia'];
				$variableViewPro .= "&unidadEjecutoraOrigen=" . $_REQUEST['unidadEjecutora'];
				$variableViewPro .= "&usuarioOrigen=" . $_REQUEST['usuario'];
				$variableViewPro = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableViewPro, $directorio );
				$imagenViewPro = 'verPro.png';
					
					
					
			
					
				$mostrarHtml = "<tr>
									<td><center>" . $dato['num_documento'] . "</center></td>
									<td><center>" . $dato['nom_proveedor'] . "</center></td>
									<td><center>" . $dato['tipopersona'] . "</center></td>
									<td><center>" . $dato['direccion'] . "</center></td>
									<td><center>" . $dato['web'] . "</center></td>
								    <td><center>" . $dato['correo'] . "</center></td>
									<td><center>" . $dato['ubicacion'] . "</center></td>"
														/*<td><center>" . $dato['puntaje_evaluacion'] . "</center></td>
														 <td><center>" . $dato['clasificacion_evaluacion'] . "</center></td>*/."
									<td><center>" . $estadoRes . "</center></td>
									<td><center>
										<a href='" . $variableViewPro . "'>
											<img src='" . $rutaBloque . "/images/" . $imagenViewPro . "' width='15px'>
										</a>
									</center></td>
									<td><center>
										<a href='" . $variableView . "'>
											<img src='" . $rutaBloque . "/images/" . $imagenView . "' width='15px'>
										</a>
									</center></td>
									<td><center>
										<a href='" . $variableAdd . "'>
											<img src='" . $rutaBloque . "/images/" . $imagenAdd . "' width='15px'>
										</a>
									</center></td>
								</tr>";
				echo $mostrarHtml;
				unset ( $mostrarHtml );
				unset ( $variableView );
				unset ( $variableEdit );
				unset ( $variableAdd );
				endforeach;
					
			}
			
			echo "</tbody>";
			echo "</table>";
			
				
			echo $this->miFormulario->marcoAgrupacion ( 'fin');
				
			
			$paraIdSolicitud = $_REQUEST['idSolicitud'];
			$paraVigencia = $_REQUEST['vigencia'];
			$paraUnidad = $_REQUEST['unidadEjecutora'];
			$paraUsuario = $_REQUEST['usuario'];
				
				
			$variableRet = "pagina=" . $miPaginaActual . "&opcion=terminarCotizacion&idSolicitud=".$paraIdSolicitud
			."&vigencia=".$paraVigencia."&unidadEjecutora=".$paraUnidad."&usuario=".$paraUsuario;
			$variableRet = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableRet, $directorio );
				
				
			$atributos["id"]="botonReg";
			$atributos["estilo"]=" marcoBotones widget";
			echo $this->miFormulario->division("inicio",$atributos);
			{
				$enlace = "<a href='".$variableRet."'>";
				$enlace.="<img src='".$rutaBloque."/images/asiPro.png' width='35px'><br>Decisión Cotización ";
				$enlace.="</a><br><br>";
				echo $enlace;
			}
			//------------------Fin Division para los botones-------------------------
			echo $this->miFormulario->division("fin");
			unset ( $atributos );
			
			
			
			
			
			
		}else if($objetoEspecifico[0]['estado_cotizacion'] == 3 || $objetoEspecifico[0]['estado_cotizacion'] == 7 || $objetoEspecifico[0]['estado_cotizacion'] == 8){
			
			
			$esteCampo = "marcoDatosCotizacionListPer";
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
			$atributos ['tipoEtiqueta'] = 'inicio';
			$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo ) . " (Número Cotización: ". $resultadoNecesidadRelacionada[0]['numero_solicitud'] ." - Titulo: " . substr(str_replace("<p>", "", $resultadoNecesidadRelacionada[0]['titulo_cotizacion']), 0, 100) . "...)";
			echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
			
			echo '<table id="tablaObjetosSinCotizacion" class="display" cellspacing="0" width="100%"> ';
			
			echo "<thead>
							<tr>
								<th><center>Documento</center></th>
								<th><center>Proveedor</center></th>
								<th><center>Tipo Persona</center></th>
								<th><center>Dirección</center></th>
								<th><center>Web</center></th>
								<th><center>Correo</center></th>
								<th><center>Ubicación Contacto</center></th>
"/* 			                    <th><center>Puntaje Evaluacióń</center></th>
			 <th><center>Clasificación Evaluación</center></th>*/."
    							<th><center>Estado Cotización</center></th>
								<th><center>Detalle Persona</center></th>
			 					<th><center>Respuesta Solicitante</center></th>
								<th><center> " . $tipoMN . "</center></th>
							</tr>
							</thead>
							<tbody>";
			
				
			if($resultadoProveedor){
			
				foreach ($resultadoProveedor as $dato):
					
				$datosSolicitud = array (
						'objeto' => $resultadoNecesidadRelacionada[0]['id'],
						'proveedor' => $dato['id_proveedor']
				);
					
				$cadenaSql = $this->sql->getCadenaSql ( 'consultarSolicitudxProveedor', $datosSolicitud );
				$resultadoSolicitudxPersona = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
			
				$cadenaSql = $this->sql->getCadenaSql ( 'consultarRespuestaxProveedor', $resultadoSolicitudxPersona[0]['id'] );
				$respuestaCotizacionProveedor = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
			
			
				if($respuestaCotizacionProveedor){ //Se manejaba ESTADO Cerrado ---- $resultadoSolicitudxPersona[0]['estado'] == 'CERRADO'
						
						
					$cadenaSql = $this->sql->getCadenaSql ( 'consultarRespuestaxSolicitante', $resultadoSolicitudxPersona[0]['id'] );
					$respuestaCotizacionSolicitante = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
						
						
					if($respuestaCotizacionSolicitante){
			
						//INHABILITADO VOLVER A RESPONDER
						$variableView = "#";
						$imagenView = "cancel.png";
			
					}else{
			
						$variableView = "pagina=" . $miPaginaActual; // pendiente la pagina para modificar parametro
						$variableView .= "&opcion=resultadoCotizacion";
						$variableView .= "&idSolicitudCotizacion=" . "XXXX";
						$variableView .= "&idProveedor=" . $dato['id_proveedor'];
						$variableView .= "&idSolicitud=" . $objetoEspecifico[0]['numero_solicitud'];
						$variableView .= "&vigencia=" . $objetoEspecifico[0]['vigencia'];
						$variableView .= "&unidadEjecutora=" . $_REQUEST['unidadEjecutora'];
						$variableView .= "&tipoCotizacion=" . $objetoEspecifico[0]['tipo_necesidad'];
						$variableView .= "&idObjeto=" . $datosSolicitudNecesidad['idObjeto'];
						$variableView .= "&idSolicitudIndividual=" . $resultadoSolicitudxPersona[0]['id'];
						$variableView = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableView, $directorio );
						$imagenView = 'resPro.png';
			
					}
						
						
						
					$variableAdd = "pagina=" . $miPaginaActual; // pendiente la pagina para modificar parametro
					$variableAdd .= "&opcion=verCotizacionProveedor";
					$variableAdd .= "&idSolicitudCotizacion=" . "XXXX";
					$variableAdd .= "&idProveedor=" . $dato['id_proveedor'];
					$variableAdd .= "&idSolicitud=" . $objetoEspecifico[0]['numero_solicitud'];
					$variableAdd .= "&vigencia=" . $objetoEspecifico[0]['vigencia'];
					$variableAdd .= "&unidadEjecutora=" . $_REQUEST['unidadEjecutora'];
					$variableAdd .= "&tipoCotizacion=" . $objetoEspecifico[0]['tipo_necesidad'];
					$variableAdd .= "&idObjeto=". $datosSolicitudNecesidad['idObjeto'];
					$variableAdd .= "&idSolicitudIndividual=" . $resultadoSolicitudxPersona[0]['id'];
					$variableAdd = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableAdd, $directorio );
					$imagenAdd = 'addPro.png';
						
					$estadoRes = "RESPONDIDA";
						
				}else{
						
					//INHABILITADO RESPONDER
					$variableView = "#";
					$imagenView = "cancel.png";
						
					$variableAdd = "#";
					$imagenAdd = "cancel.png";
						
					$estadoRes = "SIN RESPUESTA";
						
				}
			
			
					
				$variableViewPro = "pagina="."consultaGeneralProveedor";//$miPaginaActual; // pendiente la pagina para modificar parametro
				$variableViewPro .= "&opcion=verPro";
				$variableViewPro .= "&idProveedor=" . $dato['id_proveedor'];
				$variableViewPro .= "&paginaOrigen=" . $miPaginaActual;
				$variableViewPro .= "&opcionOrigen=verCotizacionSolicitud";
				$variableViewPro .= "&idSolicitudOrigen=" . $_REQUEST['idSolicitud'];
				$variableViewPro .= "&vigenciaOrigen=" . $objetoEspecifico[0]['vigencia'];
				$variableViewPro .= "&unidadEjecutoraOrigen=" . $_REQUEST['unidadEjecutora'];
				$variableViewPro .= "&usuarioOrigen=" . $_REQUEST['usuario'];
				$variableViewPro = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableViewPro, $directorio );
				$imagenViewPro = 'verPro.png';
					
				
				$variableViewAdd = "pagina=" . $miPaginaActual; // pendiente la pagina para modificar parametro
				$variableViewAdd .= "&opcion=verResCotizacionProveedor";
				$variableViewAdd .= "&idSolicitudCotizacion=" . "XXXX";
				$variableViewAdd .= "&idProveedor=" . $dato['id_proveedor'];
				$variableViewAdd .= "&idSolicitud=" . $objetoEspecifico[0]['numero_solicitud'];
				$variableViewAdd .= "&vigencia=" . $objetoEspecifico[0]['vigencia'];
				$variableViewAdd .= "&unidadEjecutora=" . $_REQUEST['unidadEjecutora'];
				$variableViewAdd .= "&tipoCotizacion=" . $objetoEspecifico[0]['tipo_necesidad'];
				$variableViewAdd .= "&idObjeto=". $datosSolicitudNecesidad['idObjeto'];
				$variableViewAdd .= "&idSolicitudIndividual=" . $resultadoSolicitudxPersona[0]['id'];
				$variableViewAdd = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableViewAdd, $directorio );
				$imagenViewAdd = 'resPro.png';
				
				
				if($dato['id_proveedor'] == $objetoEspecifico[0]['informacion_proveedor']){
					echo '<tr style="background-color:#D7DF01;">';
				}else{
					echo '<tr>';
				}
					
				$mostrarHtml = "
									<td><center>" . $dato['num_documento'] . "</center></td>
									<td><center>" . $dato['nom_proveedor'] . "</center></td>
									<td><center>" . $dato['tipopersona'] . "</center></td>
									<td><center>" . $dato['direccion'] . "</center></td>
									<td><center>" . $dato['web'] . "</center></td>
								    <td><center>" . $dato['correo'] . "</center></td>
									<td><center>" . $dato['ubicacion'] . "</center></td>
									<td><center>" . $estadoRes . "</center></td>
									<td><center>
										<a href='" . $variableViewPro . "'>
											<img src='" . $rutaBloque . "/images/" . $imagenViewPro . "' width='15px'>
										</a>
									</center></td>
										
									<td><center>
										<a href='" . $variableViewAdd . "'>
											<img src='" . $rutaBloque . "/images/" . $imagenViewAdd . "' width='15px'>
										</a>
									</center></td>				
													
									<td><center>
										<a href='" . $variableAdd . "'>
											<img src='" . $rutaBloque . "/images/" . $imagenAdd . "' width='15px'>
										</a>
									</center></td>
								</tr>";
				echo $mostrarHtml;
				unset ( $mostrarHtml );
				unset ( $variableView );
				unset ( $variableEdit );
				unset ( $variableAdd );
				endforeach;
					
			}
			
			echo "</tbody>";
			echo "</table>";
			
			$esteCampo = "justificacion";
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
			$atributos ['validar'] = 'required,minSize[20],maxSize[5000]';
			$atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
			$atributos ['deshabilitado'] = true;
			$atributos ['tamanno'] = 20;
			$atributos ['maximoTamanno'] = '';
			$atributos ['anchoEtiqueta'] = 220;
			$atributos ['textoEnriquecido'] = true;
			
			$atributos ['valor'] = $objetoEspecifico[0]['justificacion_seleccion'];
			
			
			$tab ++;
			
			// Aplica atributos globales al control
			$atributos = array_merge($atributos, $atributosGlobales);
			echo $this->miFormulario->campoTextArea($atributos);
			unset($atributos);
			
			
				
			echo $this->miFormulario->marcoAgrupacion ( 'fin');
				
			
		}


			
			
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
			
			
		echo $this->miFormulario->marcoAgrupacion ( 'fin');
		


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
		
		//$valorCodificado  = "action=" . $esteBloque ["nombre"];
		$valorCodificado = "&pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
		$valorCodificado .= "&bloque=" . $esteBloque ['nombre'];
		$valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
		$valorCodificado .= "&opcion=nuevoCotizacion";
		$valorCodificado .= "&usuario=" . $_REQUEST['usuario'];
		
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
		
		
		$miFormulario = new Formulario ( $this->lenguaje, $this->miFormulario, $this->sql  );
		
		$miFormulario->formulario ();
		$miFormulario->mensaje ();
		?>