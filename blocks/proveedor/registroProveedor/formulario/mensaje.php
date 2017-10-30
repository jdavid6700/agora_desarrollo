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


    $tab = 1;
//---------------Inicio Formulario (<form>)--------------------------------
    $atributos["id"] = $nombreFormulario;
    $atributos["tipoFormulario"] = "multipart/form-data";
    $atributos["metodo"] = "POST";
    $atributos["nombreFormulario"] = $nombreFormulario;
    $atributos["tipoEtiqueta"] = 'inicio';
    $verificarFormulario = "1";
    echo $this->miFormulario->formulario($atributos);

    
    
    $miPaginaActual = $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
    	
    $directorio = $this->miConfigurador->getVariableConfiguracion ( "host" );
    $directorio .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/index.php?";
    $directorio .= $this->miConfigurador->getVariableConfiguracion ( "enlace" );
    	
    $variable = "pagina=" . $miPaginaActual;
    //$variable .= "&usuario=".$_REQUEST['usuario'];
    $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variable, $directorio );
    	
    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------    	
    unset ( $atributos );

    $atributos["id"] = "divNoEncontroEgresado";
    $atributos["estilo"] = "marcoBotones";

    //$atributos["estiloEnLinea"]="display:none"; 
    echo $this->miFormulario->division("inicio", $atributos);

    if ($_REQUEST['mensaje'] == 'confirma') {
    	
    	$variableResumen = "pagina=gestionUsuarios"; //pendiente la pagina para modificar parametro
    	$variableResumen.= "&action=".'gestionUsuarios';
    	$variableResumen.= "&bloque=" . '6';
    	$variableResumen.= "&bloqueGrupo=" . 'usuarios';
    	$variableResumen.= "&opcion=resumen";
    	$variableResumen.= "&identificacion=" . $_REQUEST['nit'];
    	$variableResumen.= "&nombres=" .$_REQUEST['nombres'];
    	$variableResumen.= "&apellidos=" .$_REQUEST['apellidos'];
    	$variableResumen.= "&correo=" .$_REQUEST['correo'];
    	$variableResumen.= "&telefono=" .$_REQUEST['telefono'];
    	$variableResumen.= "&id_usuario=" .$_REQUEST ['tipo_identificacion'].$_REQUEST ['nit'];
    	$variableResumen.= "&tipo_persona=" . $_REQUEST ['tipo_persona'];
    	$variableResumen.= "&perfilUs=" .'Persona Natural o Jurídica';
    	$variableResumen.= "&password=" .$_REQUEST['generada'];
    	$variableResumen = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableResumen, $directorio);
    	
    	//------------------Division para los botones-------------------------
    	$atributos["id"]="botones";
    	$atributos["estilo"]="marcoBotones widget";
    	echo $this->miFormulario->division("inicio",$atributos);
    	
    	$enlace = "<a href='".$variableResumen."'>";
    	$enlace.="<img src='".$rutaBloque."/images/pdf.png' width='35px'><br>Descargar Credenciales ";
    	$enlace.="</a><br><br>";
    	echo $enlace;
    	//------------------Fin Division para los botones-------------------------
    	echo $this->miFormulario->division("fin");
    	
    	
        $tipo = 'success';
        $mensaje = "Se registro la PERSONA, puede OPRIMIR CONTINUAR e ingresar más Actividades Económicas, si lo requiere.<br >";
		$mensaje .= "<strong>Usuario: </strong>" . $_REQUEST ['tipo_identificacion'].$_REQUEST ['nit'] . "<br >";
		$mensaje .= "<strong><br >";
		$mensaje .= "<strong>Clave Disponible en el Correo que registro, por favor revise su Bandeja de Entrada<br >";
		$mensaje .= "<strong><br >";
		$mensaje .= "<strong>Igualmente puede descargar sus credenciales de ACCESO en el enlace superior, si así lo prefiere o si se presentó algún problema con el envió a su correo.<br >";
        $boton = "continuar";
        
        if(isset($_REQUEST['correo'])){$_REQUEST['correo'] = str_replace('\\', "", $_REQUEST['correo']);}
        
        //**************************************************************************************
        $usuario = $_REQUEST ['tipo_identificacion'].$_REQUEST ['nit'];
        
        $cadenaSqlSend = $this->sql->getCadenaSql ( "verificarEnvio", $usuario);
        $resultadoVerificar = $frameworkRecursoDB->ejecutarAcceso ( $cadenaSqlSend, 'busqueda' );
        //**************************************************************************************
        
        if($resultadoVerificar[0]['informado'] == 'f'){
        	
        	//INICIO ENVIO DE CORREO AL USUARIO
        	$rutaClases=$this->miConfigurador->getVariableConfiguracion("raizDocumento")."/classes";
        	
        	include_once($rutaClases."/mail/class.phpmailer.php");
        	include_once($rutaClases."/mail/class.smtp.php");
        	
        	$mail = new PHPMailer();
        	
        	$mail->SMTPOptions = array (
        			'ssl' => array (
        					'verify_peer' => false,
        					'verify_peer_name' => false,
        					'allow_self_signed' => true
        			)
        	);
        	
        	// configuracion de cuenta de envio
        	$mail->SMTPSecure = 'tls';
        	$mail->Host = "mail.udistrital.edu.co";
        	$mail->Port = 587;
        	$mail->Mailer = "smtp";
        	$mail->SMTPAuth = true;
        	$mail->Username = "agora@udistrital.edu.co";
        	$mail->Password = "CondorOAS2012";
        	$mail->Timeout = 1200;
        	$mail->Charset = "utf-8";
        	$mail->SMTPDebug = 1;
        	$mail->IsHTML ( true );
        	
        	//remitente
        	$fecha = date("d-M-Y g:i:s A");
        	$to_mail=$_REQUEST ['correo'];
        	
        	$mail->AddAddress($to_mail);
        	$mail->From='agora@udistrital.edu.co';
        	$mail->FromName='UNIVERSIDAD DISTRITAL FRANCISCO JOSÉ DE CALDAS';
        	$mail->Subject="Datos de Acceso - Registro de Personas";
        	$contenido="<p>Fecha de envio: " . $fecha . "</p>";
        	$contenido.= "<p>Señor usuario, Bienvenido al <b>Sistema de Registro Único de Personas ÁGORA</b> de la Universidad Distrital Francisco José de Caldas. </p>";
        	$contenido.= "<p>Sus datos de acceso son los siguientes:</p>";
        	$contenido.= "<b>Usuario:</b> " . $_REQUEST ['tipo_identificacion'].$_REQUEST ['nit'] . "<br>";
        	$contenido.= "<b>Clave de acceso:</b> " . $_REQUEST ['generada'];
        	$contenido.= "<p>Este es su usuario y clave para ingresar al Sistema ÁGORA de la Universidad Distrital. Al ingresar el sistema le solicitará cambiar su clave de acceso.</p>";
        	$contenido.= "<p>Este mensaje ha sido generado automáticamente, favor no responder.</p>";
        	
        	$mail->Body=$contenido;
        	
        	if(!$mail->Send())
        	{
        		?>
        	                <script language='javascript'>
        	                alert('Error! El mensaje no pudo ser enviado, es posible que la dirección de correo electrónico no sea válido.!');
        	                </script>
        	                <?
        	}
        	else
        	{
        				
        				$cadenaSqlExitSend = $this->sql->getCadenaSql ( "actualizarEstadoInformado", $usuario );
        				$resultadoInformado = $frameworkRecursoDB->ejecutarAcceso ( $cadenaSqlExitSend, 'acceso' );
        				
        	  			?>
        				<script language='javascript'>
        				alert('Se envió un correo con los datos de ingreso.');
        				</script>
        				<?php
        	}    
        	
        	$mail->ClearAllRecipients();
        	$mail->ClearAttachments();
        	
        	//FIN ENVIO DE CORREO AL USUARIO	
        	
        }
        
        $valorCodificado = "pagina=" . $miPaginaActual;
		$valorCodificado.="&opcion=actividad";
        $valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
        $valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
        $valorCodificado.="&num_documento=" . $_REQUEST['nit'];
        $valorCodificado.="&id_usuario=" . $_REQUEST['id_usuario'];
        $valorCodificado.="&tipo_persona=" . $_REQUEST['tipo_persona'];
        
        
    } else if($_REQUEST['mensaje'] == 'mensajeExisteProveedor') {
        $tipo = 'error';
        $mensaje = "Error en el cargue. <br>El número de Documento ".$_REQUEST['nit']." ya se encuentra Registrado.";
        $boton = "regresar";

        $valorCodificado = "pagina=". $miPaginaActual;
        $valorCodificado.="&opcion=mostrar";
        $valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
        $valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
    } else if($_REQUEST['mensaje'] == 'mensajeExisteProveedorLegal') {
        	$tipo = 'error';
        	$mensaje = "Error en el cargue. <br>El número de Documento del Representante Legal ".$_REQUEST['nit']." ya se encuentra Registrado.";
        	$boton = "regresar";
        
        	$valorCodificado = "pagina=". $miPaginaActual;
        	$valorCodificado.="&opcion=mostrar";
        	$valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
        	$valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
       
    }else if($_REQUEST['mensaje'] == 'mensajeExisteActividad') {
        $tipo = 'error';
        $mensaje = "Error en el cargue. <br>La Actividad ya se encuentra Registrada.";
        $boton = "regresar";

        $valorCodificado = "pagina=". $miPaginaActual;
        $valorCodificado.="&opcion=actividad";
        $valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
        $valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
		$valorCodificado.="&nit=" . $_REQUEST['nit'];
       
    }else if($_REQUEST['mensaje'] == 'error') {
        $tipo = 'error';
        $mensaje = "Error al Cargar los Datos. <br>La Informaciòn no se diligenció Correctamente, Por Favor Vuelva a Intertarlo";
        $boton = "regresar";

        $valorCodificado = "pagina=". $miPaginaActual;
        $valorCodificado.="&opcion=mostrar";
        $valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
        $valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];

    }else if($_REQUEST['mensaje'] == 'errorUsuario') {
        $tipo = 'warning';
        $mensaje = "Se Registro Información Personal. <br>Existe un PROBLEMA (No se Registro USUARIO en el Sistema AGORA.), Por Favor Comuniquese con el 
    		Administrador del Sistema para solicitar un Usuario. No debe volver a Registrarse";
        $boton = "regresar";

        $valorCodificado = "pagina=". $miPaginaActual;
        $valorCodificado.="&opcion=mostrar";
        $valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
        $valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];    
       
    } else if($_REQUEST['mensaje'] == 'registroActividad') {
    
    	$actividadesArray = explode(",", $_REQUEST['actividades']);
        $listAc = "";

        foreach ($actividadesArray as $dato):
            $cadenaSql = $this->sql->getCadenaSql('ciiuSubClaseByNum', $dato);
            $resultadoCIIU = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");

            $listAc .= "<strong>" . $resultadoCIIU[0]['nombre'] . "</strong><br >";
            ;
        endforeach;

        $tipo = 'success';
        $mensaje = "</span>Se realizó la modificación a la(s) Actividad(es) Económica(s)<br><br>";
        $mensaje .= $listAc;
        $boton = "regresar";

        $valorCodificado = "pagina=". $miPaginaActual;
        $valorCodificado.="&opcion=actividad";
        $valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
        $valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
        $valorCodificado.="&num_documento=" . $_REQUEST['num_documento'];
        $valorCodificado.="&tipo_persona=" . $_REQUEST['tipo_persona'];
        $valorCodificado.="&idProveedor=" . $_REQUEST["idProveedor"];
		
       
    }else if($_REQUEST['mensaje'] == 'actualizo') {
        $tipo = 'success';
        $mensaje = "Se guardaron los datos de la Persona.<br >";
        $boton = "regresar";

        $valorCodificado = "pagina=". $miPaginaActual;
        $valorCodificado.="&opcion=modificar";
        $valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
        $valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
       
    }else if($_REQUEST['mensaje'] == 'noActualizo') {
        $tipo = 'error';
        $mensaje = "Error en el cargue. <br>No se Actualizaron los Datos.";
        $boton = "regresar";

        $valorCodificado = "pagina=". $miPaginaActual;
        $valorCodificado.="&opcion=modificar";
        $valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
        $valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
       
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
    $atributos ['marco'] = false;
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

    // -----------------CONTROL: Botón ----------------------------------------------------------------
    $esteCampo = $boton;
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