<?php

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
} else {

    $esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");

    $rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
    $rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/";
    $rutaBloque.= $esteBloque['grupo'] . "/" . $esteBloque['nombre'];

    $directorio = $this->miConfigurador->getVariableConfiguracion("host");
    $directorio.= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
    $directorio.=$this->miConfigurador->getVariableConfiguracion("enlace");
    $miSesion = Sesion::singleton();

    $nombreFormulario = $esteBloque["nombre"];

    include_once("core/crypto/Encriptador.class.php");
    $cripto = Encriptador::singleton();

    $directorio = $this->miConfigurador->getVariableConfiguracion("rutaUrlBloque") . "/imagen/";

    $miPaginaActual = $this->miConfigurador->getVariableConfiguracion("pagina");

    $conexion = "estructura";
    $esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
    
    //$conexion = "sicapital";
    //$siCapitalRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );


    $tab = 1;
//---------------Inicio Formulario (<form>)--------------------------------
    $atributos["id"] = $nombreFormulario;
    $atributos["tipoFormulario"] = "multipart/form-data";
    $atributos["metodo"] = "POST";
    $atributos["nombreFormulario"] = $nombreFormulario;
    $atributos["tipoEtiqueta"] = 'inicio';
    $atributos ['titulo'] = '';
    $verificarFormulario = "1";
    echo $this->miFormulario->formulario($atributos);
    
    
    
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
    
    //var_dump($_REQUEST);
    $datosSolicitudNecesidad = array (
    		'idObjeto' => $_REQUEST['idSolicitud']
    );
    
    
    //*********************************************************************************************************************************
    $cadena_sql = $this->sql->getCadenaSql ( "estadoSolicitudAgora", $datosSolicitudNecesidad);
    $resultado = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
    
    if(isset($resultado)) {
    	$estadoSolicitud = $resultado[0]['estado'];
    
    
    	$cadena_sql = $this->sql->getCadenaSql ( "informacionSolicitudAgora", $datosSolicitudNecesidad);
    	$resultadoNecesidadRelacionada = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
    
    
    	$cadena_sql = $this->sql->getCadenaSql ( "informacionCIIURelacionada", $datosSolicitudNecesidad);
    	$resultadoNecesidadRelacionadaCIIU = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
    }
		
		//Buscar usuario para enviar correo
		$cadenaSql = $this->sql->getCadenaSql ( 'buscarProveedoresInfoCotizacion', $resultadoNecesidadRelacionada[0]['id_objeto'] );
		$resultadoProveedor = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
		//Buscar usuario para enviar correo
		$cadenaSql = $this->sql->getCadenaSql ( 'objetoContratar', $resultadoNecesidadRelacionada[0]['id_objeto'] );
		$objetoEspecifico = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
		
		if($objetoEspecifico[0]['tipo_necesidad'] == 'SERVICIO' || $objetoEspecifico[0]['tipo_necesidad'] == 'BIEN Y SERVICIO'){
			$convocatoria = true;
				
			$cadenaSql = $this->sql->getCadenaSql ( 'consultarNBCImp', $resultadoNecesidadRelacionada[0]['id_objeto']  );
			$resultadoNBC = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
			
			$tipoMN = "Cotización";
				
		}else{
			$convocatoria = false;
			
			$tipoMN = "Cotización";
		}

		
		$tipo = 'success';
		if($convocatoria){
			$mensaje =  $this->lenguaje->getCadena('mensajeEnCotizacion');
		}else{
			$mensaje =  $this->lenguaje->getCadena('mensajeEnCotizacion');
		}
		$boton = "regresar";
		
		//INICIO enlace boton descargar resumen
		$variableResumen = "pagina=" . $miPaginaActual;
		$variableResumen.= "&action=".$esteBloque["nombre"];
		$variableResumen.= "&bloque=" . $esteBloque["id_bloque"];
		$variableResumen.= "&bloqueGrupo=" . $esteBloque["grupo"];
		$variableResumen.= "&opcion=resumen";
		$variableResumen.= "&idObjeto=" . $resultadoNecesidadRelacionada[0]['id_objeto'];
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
		

		$esteCampo = "marcoDatosCotizacionListPer";
		$atributos ['id'] = $esteCampo;
		$atributos ["estilo"] = "jqueryui";
		$atributos ['tipoEtiqueta'] = 'inicio';
		$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo ) . " (Número Cotización: ". $resultadoNecesidadRelacionada[0]['numero_solicitud'] ." - Titulo: " . $resultadoNecesidadRelacionada[0]['titulo_cotizacion'] . ")";
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
						'objeto' => $resultadoNecesidadRelacionada[0]['id_objeto'],
						'proveedor' => $dato['id_proveedor']
				);
					
				$cadenaSql = $this->sql->getCadenaSql ( 'consultarSolicitudxProveedor', $datosSolicitud );
				$resultadoSolicitudxPersona = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
				
				
				if($resultadoSolicitudxPersona[0]['estado'] == 'CERRADO'){
					
					$variableView = "pagina=" . $miPaginaActual; // pendiente la pagina para modificar parametro
					$variableView .= "&opcion=resultadoCotizacion";
					$variableView .= "&idSolicitudCotizacion=" . "XXXX";
					$variableView .= "&idProveedor=" . $dato['id_proveedor'];
					$variableView .= "&idSolicitud=" . $objetoEspecifico[0]['numero_solicitud'];
					$variableView .= "&vigencia=" . $objetoEspecifico[0]['vigencia'];
					$variableView .= "&unidadEjecutora=" . $_REQUEST['unidadEjecutora'];
					$variableView .= "&tipoCotizacion=" . $objetoEspecifico[0]['tipo_necesidad'];
					$variableView .= "&idObjeto=" . $datosSolicitudNecesidad['idObjeto'];
					$variableView .= "&idSolicitudIndividual=" . $resultadoSolicitudxPersona[0]['id_solicitud'];
					$variableView = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableView, $directorio );
					$imagenView = 'resPro.png';
					
					$variableAdd = "pagina=" . $miPaginaActual; // pendiente la pagina para modificar parametro
					$variableAdd .= "&opcion=verCotizacionProveedor";
					$variableAdd .= "&idSolicitudCotizacion=" . "XXXX";
					$variableAdd .= "&idProveedor=" . $dato['id_proveedor'];
					$variableAdd .= "&idSolicitud=" . $objetoEspecifico[0]['numero_solicitud'];
					$variableAdd .= "&vigencia=" . $objetoEspecifico[0]['vigencia'];
					$variableAdd .= "&unidadEjecutora=" . $_REQUEST['unidadEjecutora'];
					$variableAdd .= "&tipoCotizacion=" . $objetoEspecifico[0]['tipo_necesidad'];
					$variableAdd .= "&idObjeto=". $datosSolicitudNecesidad['idObjeto'];
					$variableAdd .= "&idSolicitudIndividual=" . $resultadoSolicitudxPersona[0]['id_solicitud'];
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
				
				//var_dump($resultadoSolicitudxPersona);
			
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


			echo $this->miFormulario->marcoAgrupacion ( 'fin');
			
			
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
			

//EVALUAR Opcion de Gestionar NUEVO Envio

/*			
//ERROR Correo se ENVIA a SPAM*******-----------------------------------------------------------------------------------------------------
//INICIO ENVIO DE CORREO AL USUARIO
    $rutaClases=$this->miConfigurador->getVariableConfiguracion("raizDocumento")."/classes";

    include_once($rutaClases."/mail/class.phpmailer.php");
    include_once($rutaClases."/mail/class.smtp.php");
	
	$mail = new PHPMailer(); 


	//configuracion de cuenta de envio
	$mail->Host     = "200.69.103.49";
	$mail->Mailer   = "smtp";
	$mail->SMTPAuth = true;
	$mail->Username = "condor@udistrital.edu.co";
	$mail->Password = "CondorOAS2012";
	$mail->Timeout  = 1200;
	$mail->Charset  = "utf-8";
	$mail->IsHTML(true);

        $mail->From='agora@udistrital.edu.co';
        $mail->FromName='UNIVERSIDAD DISTRITAL FRANCISCO JOSÉ DE CALDAS';
        $mail->Subject="Solicitud de Cotización";
        $contenido= "<p>Señor proveedor, la Universidad Distrital Francisco José de Caldas se encuentra interesada en poder contar con sus servicios para la adquisición de: </p>";
        $contenido.= "<b>Objeto Solicitud de Necesidad : </b>" . $solicitudNecesidad [0]['OBJETO'] . "<br>";
        $contenido.= "<br>";
        $contenido.= "<b>Justificación Solicitud de Necesidad : </b>" . $solicitudNecesidad [0]['JUSTIFICACION'] . "<br>";
        $contenido.= "<br>";
        $contenido.= "<b>Actividad econ&oacute;mica : </b>" . $objetoEspecifico[0]['codigociiu'] . ' - ' . $objetoEspecifico[0]['actividad'] . "<br>";
        $contenido.= "<br>";
        $contenido.= "<b>Dependencia Solicitante : </b>" . $solicitudNecesidad [0]['DEPENDENCIA']. "<br>";
        $contenido.= "<br>";
        $contenido.= "<b>Responsable : </b>" . $solicitudNecesidad [0]['ORDENADOR_GASTO'];
        $contenido.= "<br>";
        $contenido.= "<p>Por lo tanto, lo invitamos a presentar su cotización con una fecha máxima no superior a tres (3) días hábiles a partir del recibo del presente comunicado, para lo cual 
        		         puede ingresar al Banco de Proveedores.</p>";
        $contenido.= "<br>";
        $contenido.= "<p>Agradeciendo su gentil atención.</p>";
        $contenido.= "<br>";
        $contenido.= "<br>";
        $contenido.= "Cordialmente :<br>";
		$contenido.= "<b>" . $solicitudNecesidad [0]['ORDENADOR_GASTO'] . " - " . $solicitudNecesidad [0]['CARGO_ORDENADOR_GASTO'] . "</b>";
		$contenido.= "<br>";
		$contenido.= "<br>";
		$contenido.= "<br>";
		$contenido.= "<br>";
		$contenido.= "<p>Este mensaje ha sido generado automáticamente, favor no responder..</p>";
		
        $mail->Body=$contenido;

		//foreach ($resultadoProveedor as $dato):
			//$to_mail=$dato ['correo'];
		    $to_mail="jdavid.6700@gmail.com";//PRUEBAS**********************************************************************************
			$mail->AddAddress($to_mail);
			//$mail->Send();
			
			if(!$mail->Send()) {
				echo "Error al enviar el mensaje a ". $to_mail .": " . $mail->ErrorInfo;
			}else{
				echo "Enviado!!! ". $to_mail .": " . $mail->ErrorInfo;
			}
			
		//endforeach; 
		
			var_dump($mail);
			
        $mail->ClearAllRecipients();
        $mail->ClearAttachments();
//FIN ENVIO DE CORREO AL USUARIO     
*/

        $valorCodificado = "pagina=".$miPaginaActual;
        $valorCodificado.="&opcion=nuevo";
        $valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
        $valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
        $valorCodificado.="&usuario=" . $_REQUEST['usuario'];
        
			
			
    


    /**
     * IMPORTANTE: Este formulario está utilizando jquery.
     * Por tanto en el archivo ready.php se delaran algunas funciones js
     * que lo complementan.
     */
    // Rescatar los datos de este bloque
    $esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");

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
    $_REQUEST['tiempo'] = time();

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
    $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo);

    // Si no se coloca, entonces toma el valor predeterminado.
    $atributos ['estilo'] = '';
    $atributos ['marco'] = true;
    $tab = 1;
    // ---------------- FIN SECCION: de Parámetros Generales del Formulario ----------------------------
    // ----------------INICIAR EL FORMULARIO ------------------------------------------------------------
    $atributos['tipoEtiqueta'] = 'inicio';
    echo $this->miFormulario->formulario($atributos);

    // -----------------CONTROL: Botón ----------------------------------------------------------------
    $esteCampo = 'continuar';
    $atributos ["id"] = $esteCampo;
    $atributos ["tabIndex"] = $tab;
    $atributos ["tipo"] = 'boton';
    // submit: no se coloca si se desea un tipo button genérico
    $atributos ['submit'] = true;
    $atributos ["estiloMarco"] = '';
    $atributos ["estiloBoton"] = 'jqueryui';
    // verificar: true para verificar el formulario antes de pasarlo al servidor.
    $atributos ["verificar"] = '';
    $atributos ["tipoSubmit"] = 'jquery'; // Dejar vacio para un submit normal, en este caso se ejecuta la función submit declarada en ready.js
    $atributos ["valor"] = $this->lenguaje->getCadena($esteCampo);
    $atributos ['nombreFormulario'] = $esteBloque ['nombre'];
    $tab ++;

    // Aplica atributos globales al control
    $atributos = array_merge($atributos, $atributosGlobales);
    //echo $this->miFormulario->campoBoton($atributos);
    // -----------------FIN CONTROL: Botón -----------------------------------------------------------
    // ------------------Fin Division para los botones-------------------------
    echo $this->miFormulario->division("fin");

    // ------------------- SECCION: Paso de variables ------------------------------------------------

    /**
     * SARA permite que los nombres de los campos sean dinámicos.
     * Para ello utiliza la hora en que es creado el formulario para
     * codificar el nombre de cada campo. 
     */
    $valorCodificado .= "&campoSeguro=" . $_REQUEST['tiempo'];
    // Paso 2: codificar la cadena resultante
    $valorCodificado = $this->miConfigurador->fabricaConexiones->crypto->codificar($valorCodificado);

    $atributos ["id"] = "formSaraData"; // No cambiar este nombre
    $atributos ["tipo"] = "hidden";
    $atributos ['estilo'] = '';
    $atributos ["obligatorio"] = false;
    $atributos ['marco'] = false;
    $atributos ["etiqueta"] = "";
    $atributos ["valor"] = $valorCodificado;
    echo $this->miFormulario->campoCuadroTexto($atributos);
    unset($atributos);

    // ----------------FIN SECCION: Paso de variables -------------------------------------------------
    // ---------------- FIN SECCION: Controles del Formulario -------------------------------------------
    // ----------------FINALIZAR EL FORMULARIO ----------------------------------------------------------
    // Se debe declarar el mismo atributo de marco con que se inició el formulario.
    $atributos ['marco'] = false;
    $atributos ['tipoEtiqueta'] = 'fin';
    echo $this->miFormulario->formulario($atributos);

    return true;
}
?>