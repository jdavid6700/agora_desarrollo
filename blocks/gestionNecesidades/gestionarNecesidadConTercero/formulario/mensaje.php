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
    $esteRecursoDBE = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );

    $conexion = "agora";
    $esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
    
    $conexion = "sicapital";
    $siCapitalRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );


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

    if ($_REQUEST['mensaje'] == 'confirma') {
    	
    	if($_REQUEST['unidadEjecutora'] == '01'){
    		$valorUnidadEjecutoraText = "1 - Rectoría";
    	}else{
    		$valorUnidadEjecutoraText = "2 - IDEXUD";
    	}
    	$hoy = date ( "d/m/Y" );
    	
    	$cadenaSql = $this->sql->getCadenaSql ( 'buscarUsuario', $_REQUEST['usuario'] );
    	$resultadoUsuario = $esteRecursoDBE->ejecutarAcceso ( $cadenaSql, "busqueda" );
        
        if(isset($_REQUEST['estadoSolicitud']) && $_REQUEST['estadoSolicitud'] == "CREADO"){
        	
        	$valorCodificado = "pagina=".$miPaginaActual;
        	$valorCodificado.="&opcion=actividad";
        	$valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
        	$valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
        	$valorCodificado.="&idObjeto=" . $_REQUEST["idObjeto"];
        	$valorCodificado.="&numSolicitud=".$_REQUEST['numSolicitud'];
        	$valorCodificado.="&vigencia=".$_REQUEST['vigencia'];
        	$valorCodificado.="&unidadEjecutora=".$_REQUEST['unidadEjecutora'];
        	$valorCodificado.="&tipoNecesidad=".$_REQUEST['tipoNecesidad'];
        	$valorCodificado.="&numCotizaciones=" . $_REQUEST["numCotizaciones"];
        	
        	$Proceso = "la <b>Modificación</b>";
        	
        }else if(isset($_REQUEST['estadoSolicitudAct']) && $_REQUEST['estadoSolicitudAct'] == "CON ACTIVIDADES"){
        	
        	$valorCodificado = "pagina=".$miPaginaActual;
        	$valorCodificado.="&opcion=cotizacion";
        	$valorCodificado.="&idObjeto=" . $_REQUEST["idObjeto"];
        	$valorCodificado.="&numSolicitud=".$_REQUEST['numSolicitud'];
        	$valorCodificado.="&vigencia=".$_REQUEST['vigencia'];
        	$valorCodificado.="&unidadEjecutora=".$_REQUEST['unidadEjecutora'];
        	$valorCodificado.="&tipoNecesidad=".$_REQUEST['tipoNecesidad'];
        	$valorCodificado.="&numCotizaciones=" . $_REQUEST["numCotizaciones"];
        	
        	$Proceso = "la <b>Modificación</b>";
        
        }else{	
        	
        	$Proceso = "el <b>Registro</b>";
        	
        	$valorCodificado = "pagina=".$miPaginaActual;
        	$valorCodificado.="&opcion=actividad";
        	$valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
        	$valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
        	$valorCodificado.="&idObjeto=" . $_REQUEST["idObjeto"];
        	$valorCodificado.="&numSolicitud=".$_REQUEST['numSolicitud'];
        	$valorCodificado.="&vigencia=".$_REQUEST['vigencia'];
        	$valorCodificado.="&unidadEjecutora=".$_REQUEST['unidadEjecutora'];
        	$valorCodificado.="&numCotizaciones=" . $_REQUEST["numCotizaciones"];
        	$valorCodificado.="&tipoNecesidad=".$_REQUEST['tipoNecesidad'];
        	$valorCodificado.="&estadoSolicitudAct=CON ACTIVIDADES";
        	
        }
        
        $tipo = 'success';
        $mensaje =  "Se realizo " . $Proceso . " de la Solicitud de Necesidad <b>N° ".$_REQUEST['numSolicitud']."</b> con Vigencia <b>".$_REQUEST['vigencia']."</b> para la
					Unidad Ejecutora <b>". $valorUnidadEjecutoraText . "</b>
				</br>
				</br><b>Fecha del Proceso:</b> ".$hoy."
				</br><b>Usuario:</b> (" . $resultadoUsuario[0]['identificacion'] . " - " . $resultadoUsuario[0]['nombre'] . " " . $resultadoUsuario[0]['apellido'] . ")<br>";
        $boton = "continuar";
		
        
        
 
    } else if($_REQUEST['mensaje'] == 'confirmaCotizacion') {
            
        
		
		//Buscar usuario para enviar correo
		$cadenaSql = $this->sql->getCadenaSql ( 'buscarProveedores', $_REQUEST['idObjeto'] );
		$resultadoProveedor = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
		//Buscar usuario para enviar correo
		$cadenaSql = $this->sql->getCadenaSql ( 'objetoContratar', $_REQUEST["idObjeto"] );
		$objetoEspecifico = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
		$datos = array (
				'idSolicitud' => $objetoEspecifico[0]['numero_solicitud'],
				'vigencia' => $objetoEspecifico[0]['vigencia'],
				'unidadEjecutora' => $objetoEspecifico[0]['unidad_ejecutora']
		);
		
		$cadenaSql = $this->sql->getCadenaSql ( 'listaSolicitudNecesidadXNumSolicitud', $datos );
		$solicitudNecesidad = $siCapitalRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		 
		 
		$cadenaSql = $this->sql->getCadenaSql ( 'consultarActividadesImp', $_REQUEST['idObjeto']  );
		$resultadoActividades = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
		
		
		$tipo = 'success';
		if($objetoEspecifico[0]['tipo_necesidad'] == 'SERVICIO'){
			$convocatoria = true;
			$mensaje =  $this->lenguaje->getCadena('mensajeConvocatoria');
			
			$cadenaSql = $this->sql->getCadenaSql ( 'consultarNBCImp', $_REQUEST["idObjeto"]  );
			$resultadoNBC = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
			
		}else{
			$convocatoria = false;
			$mensaje =  $this->lenguaje->getCadena('mensajeCotizacion');
		}
		$boton = "regresar";
		
		//INICIO enlace boton descargar resumen
		$variableResumen = "pagina=" . $miPaginaActual;
		$variableResumen.= "&action=".$esteBloque["nombre"];
		$variableResumen.= "&bloque=" . $esteBloque["id_bloque"];
		$variableResumen.= "&bloqueGrupo=" . $esteBloque["grupo"];
		$variableResumen.= "&opcion=resumen";
		$variableResumen.= "&idObjeto=" . $_REQUEST['idObjeto'];
		$variableResumen.= "&idCodigo=" . $_REQUEST['idCodigo'];
		$variableResumen = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableResumen, $directorio);
		
		//------------------Division para los botones-------------------------
		$atributos["id"]="botones";
		$atributos["estilo"]="marcoBotones";
		echo $this->miFormulario->division("inicio",$atributos);
		
		$enlace = "<a href='".$variableResumen."'>";
		if($convocatoria){
			$enlace.="<img src='".$rutaBloque."/images/pdf.png' width='35px'><br>Descargar Información Convocatoria ";
		}else{
			$enlace.="<img src='".$rutaBloque."/images/pdf.png' width='35px'><br>Descargar Solicitud Cotización ";
		}
		$enlace.="</a><br><br>";
		echo $enlace;
		//------------------Fin Division para los botones-------------------------
		echo $this->miFormulario->division("fin");
		//FIN enlace boton descargar resumen
		
		
		
		
		$contenidoAct = '<br>';
		
		foreach ($resultadoActividades as $dato):
			$contenidoAct .= $dato['id_subclase'] . ' - ' . $dato['nombre'] . "<br>";
			$contenidoAct .= "<br>";
		endforeach;
		
		if(!isset($solicitudNecesidad [0]['OBJETO'])) $solicitudNecesidad [0]['OBJETO'] = "SIN INFORMACIÓN ";
		if(!isset($solicitudNecesidad [0]['JUSTIFICACION'])) $solicitudNecesidad [0]['JUSTIFICACION'] = "SIN INFORMACIÓN ";
		if(!isset($solicitudNecesidad [0]['DEPENDENCIA'])) $solicitudNecesidad [0]['DEPENDENCIA'] = "SIN INFORMACIÓN ";
		if(!isset($solicitudNecesidad [0]['ORDENADOR_GASTO'])) $solicitudNecesidad [0]['ORDENADOR_GASTO'] = "SIN INFORMACIÓN ";
		if(!isset($solicitudNecesidad [0]['CARGO_ORDENADOR_GASTO'])) $solicitudNecesidad [0]['CARGO_ORDENADOR_GASTO'] = "SIN INFORMACIÓN ";
		
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
		        $mail->Subject="Solicitud de Cotización BANCO PROVEEDORES UDISTRITAL";
		        
		        
		        $contenido= "<p>Señor proveedor, la Universidad Distrital Francisco José de Caldas se encuentra interesada en poder contar con sus servicios para la adquisición de: </p>";
		        $contenido.= "<b>Objeto Solicitud de Necesidad : </b>" . $solicitudNecesidad [0]['OBJETO'] . "<br>";
		        $contenido.= "<br>";
		        $contenido.= "<b>Justificación Solicitud de Necesidad : </b>" . $solicitudNecesidad [0]['JUSTIFICACION'] . "<br>";
		        $contenido.= "<br>";
		        $contenido.= "<b>Actividad(es) econ&oacute;mica(s) (CIIU) : </b><br>" . $contenidoAct . "<br>";
		        $contenido.= "<br>";
		        
		        if($convocatoria){
		        	$contenido.= "<b>Profesión Relacionada en la Convocatoria (Núcleo Básico de Conocimiento SNIES): </b><br>" . $resultadoNBC[0]['id_nucleo'] . ' - ' . $resultadoNBC[0]['nombre'] . "<br>";
		        	$contenido.= "<br>";
		        }
		        	
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

				foreach ($resultadoProveedor as $dato):
					$to_mail=$dato ['correo'];
				    //$to_mail="jdavid.6700@gmail.com";//PRUEBAS**********************************************************************************
					$mail->AddAddress($to_mail);
					//$mail->Send();
					
					if(!$mail->Send()) {
		    			echo "Error al enviar el mensaje a ". $to_mail .": " . $mail->ErrorInfo;
				    }
					
				endforeach;
				
		        $mail->ClearAllRecipients();
		        $mail->ClearAttachments();
		        
		        /*
		        $name = "JOSE";
		        $email_address = "davidencolr6700@hotmail.com";
		        $phone = "4565845";
		        $message = $contenido;
		        
		        // Create the email and send the message
		        $to = 'jdavid.6700@gmail.com'; // Add your email address inbetween the '' replacing yourname@yourdomain.com - This is where the form will send a message to.
		        $email_subject = "Formulario de contacto:  $name";
		        $email_body = "Ha recibido un nuevo mensaje de su formulario de contacto.\n\n"."Aqui tienes los detalles:\n\nNombre: $name\n\nEmail: $email_address\n\nMensaje:\n$message";
		        $headers = "From: agora@udistrital.edu.co"; // This is the email address the generated message will be from. We recommend using something like noreply@yourdomain.com.
		        $headers .= "Reply-To: $email_address";
		        mail($to,$email_subject,$email_body,$headers);
		        */
		        
		        
		//FIN ENVIO DE CORREO AL USUARIO                
		
		        $valorCodificado = "pagina=".$miPaginaActual;
		        $valorCodificado.="&opcion=nuevo";
		        $valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
		        $valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
		        
    } else if($_REQUEST['mensaje'] == 'error') {
        $tipo = 'error';
        $mensaje =  $this->lenguaje->getCadena('mensajeError');
        $boton = "regresar";

        $valorCodificado = "pagina=".$miPaginaActual;
        $valorCodificado.="&opcion=nuevo";
        $valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
        $valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
       
    } else if($_REQUEST['mensaje'] == 'actualizo') {
        $tipo = 'success';
        $mensaje = $this->lenguaje->getCadena('mensajeActualizar') . $_REQUEST ['docente'] . ".";
        $boton = "continuar";

        $valorCodificado = "pagina=".$esteBloque['nombre'];
        $valorCodificado.="&opcion=consultar";
        $valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
        $valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
       
    }else if($_REQUEST['mensaje'] == 'noActualizo') {
        $tipo = 'error';
        $mensaje = $this->lenguaje->getCadena('mensajeNoActualizo') . $_REQUEST ['docente'] . ".";
        $boton = "continuar";

        $valorCodificado = "pagina=".$esteBloque['nombre'];
        $valorCodificado.="&opcion=consultar";
        $valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
        $valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
       
    }else if($_REQUEST['mensaje'] == 'mensajeExisteActividad') {
        $tipo = 'error';
        $mensaje = "Error en el cargue. <br>La Actividad ya se encuentra registrada.";
        $boton = "regresar";
        
        $actividades = true;

        $valorCodificado = "pagina=". $miPaginaActual;
        $valorCodificado.="&opcion=actividad";
        $valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
        $valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
        $valorCodificado.="&idObjeto=" . $_REQUEST["idObjeto"];
        $valorCodificado.="&numSolicitud=".$_REQUEST['numSolicitud'];
        $valorCodificado.="&vigencia=".$_REQUEST['vigencia'];
        $valorCodificado.="&unidadEjecutora=".$_REQUEST['unidadEjecutora'];
        $valorCodificado.="&numCotizaciones=" . $_REQUEST["numCotizaciones"];
        $valorCodificado.="&tipoNecesidad=".$_REQUEST['tipoNecesidad'];
        $valorCodificado.="&estadoSolicitudAct=CON ACTIVIDADES";
        
    }else if($_REQUEST['mensaje'] == 'registroActividad') {
        $tipo = 'success';
        $mensaje = "Se registro la Actividad Económica<br >";
		$mensaje .= "<strong>" . $_REQUEST['actividad'] . "</strong><br >";
        $boton = "regresar";

        $actividades = true;
        
        if($_REQUEST['tipoNecesidad'] == 'SERVICIO'){
        	$faltaNBC = true;
        }else{
        	$faltaNBC = false;
        }

        $valorCodificado = "pagina=". $miPaginaActual;
        $valorCodificado.="&opcion=actividad";
        $valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
        $valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
        $valorCodificado.="&idObjeto=" . $_REQUEST["idObjeto"];
        $valorCodificado.="&numSolicitud=".$_REQUEST['numSolicitud'];
        $valorCodificado.="&vigencia=".$_REQUEST['vigencia'];
        $valorCodificado.="&unidadEjecutora=".$_REQUEST['unidadEjecutora'];
        $valorCodificado.="&numCotizaciones=" . $_REQUEST["numCotizaciones"];
        $valorCodificado.="&tipoNecesidad=".$_REQUEST['tipoNecesidad'];
        $valorCodificado.="&estadoSolicitudAct=CON ACTIVIDADES";
        $valorCodificado.="&sigueNBC=".$faltaNBC;
        
    }else if($_REQUEST['mensaje'] == 'registroNucleo') {
    	
    	$cadenaSql = $this->sql->getCadenaSql ( 'buscarNBC', $_REQUEST['nucleo']  );
    	$resultadoNBC = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
    	
    	if($_REQUEST['modificarNBC']){
    		$mensaje = "Se <b>Modificó</b> el Núcleo Básico de Conocimiento Relacionado<br >";
    	}else{
    		$mensaje = "Se <b>Registró</b> el Núcleo Básico de Conocimiento Relacionado<br >";
    	}
    	
        $tipo = 'success';
		$mensaje .= "<strong>" . $_REQUEST['nucleo'] ." - ". $resultadoNBC[0]['nombre'] . "</strong><br >";
        $boton = "regresar";
        
        //$actividades = false;

        $valorCodificado = "pagina=". $miPaginaActual;
        $valorCodificado.="&opcion=actividad";
        $valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
        $valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
        $valorCodificado.="&idObjeto=" . $_REQUEST["idObjeto"];
        $valorCodificado.="&numSolicitud=".$_REQUEST['numSolicitud'];
        $valorCodificado.="&vigencia=".$_REQUEST['vigencia'];
        $valorCodificado.="&unidadEjecutora=".$_REQUEST['unidadEjecutora'];
        $valorCodificado.="&numCotizaciones=" . $_REQUEST["numCotizaciones"];
        $valorCodificado.="&tipoNecesidad=".$_REQUEST['tipoNecesidad'];
        $valorCodificado.="&objetoNBC=".$_REQUEST['nucleo'];
        $valorCodificado.="&estadoSolicitudAct=CON ACTIVIDADES Y NBC";
    }
    
    


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

   
    
    if(isset($actividades) && $actividades = true){
    	
    	// -----------------CONTROL: Botón ----------------------------------------------------------------
    	$esteCampo = 'agregar';
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
    	echo $this->miFormulario->campoBoton($atributos);
    	// -----------------FIN CONTROL: Botón -----------------------------------------------------------
    	
    	// -----------------CONTROL: Botón ----------------------------------------------------------------
    	$esteCampo = 'terminar';
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
    	echo $this->miFormulario->campoBoton($atributos);
    	// -----------------FIN CONTROL: Botón -----------------------------------------------------------
    }else{
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
    	echo $this->miFormulario->campoBoton($atributos);
    	// -----------------FIN CONTROL: Botón -----------------------------------------------------------
    }
    
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
    $atributos ['marco'] = true;
    $atributos ["etiqueta"] = "";
    $atributos ["valor"] = $valorCodificado;
    echo $this->miFormulario->campoCuadroTexto($atributos);
    unset($atributos);

    // ----------------FIN SECCION: Paso de variables -------------------------------------------------
    // ---------------- FIN SECCION: Controles del Formulario -------------------------------------------
    // ----------------FINALIZAR EL FORMULARIO ----------------------------------------------------------
    // Se debe declarar el mismo atributo de marco con que se inició el formulario.
    $atributos ['marco'] = true;
    $atributos ['tipoEtiqueta'] = 'fin';
    echo $this->miFormulario->formulario($atributos);

    return true;
}
?>