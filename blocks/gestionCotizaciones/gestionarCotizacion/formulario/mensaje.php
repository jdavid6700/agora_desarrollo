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

    $conexion = "framework";
    $esteRecursoDBE = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

    $conexion = "estructura";
    $esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

    $conexion = 'argo_contratos';
    $argoRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

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



    $miPaginaActual = $this->miConfigurador->getVariableConfiguracion('pagina');

    $directorio = $this->miConfigurador->getVariableConfiguracion("host");
    $directorio .= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
    $directorio .= $this->miConfigurador->getVariableConfiguracion("enlace");

    $variable = "pagina=" . $miPaginaActual;
    $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);

    $atributos["id"] = "divNoEncontroEgresado";
    $atributos["estilo"] = "marcoBotones";

    //$atributos["estiloEnLinea"]="display:none"; 
    echo $this->miFormulario->division("inicio", $atributos);

    if ($_REQUEST['mensaje'] == 'confirma') {

        if ($_REQUEST['unidadEjecutora'] == '01') {
            $valorUnidadEjecutoraText = "1 - Rectoría";
        } else {
            $valorUnidadEjecutoraText = "2 - IDEXUD";
        }
        $hoy = date("d/m/Y");

        $cadenaSql = $this->sql->getCadenaSql ( 'buscarUsuario', $_REQUEST['usuario'] );
    	$resultadoUsuario = $esteRecursoDBE->ejecutarAcceso ( $cadenaSql, "busqueda" );
    	
    	$cadenaSql = $this->sql->getCadenaSql ( 'dependenciaUdistritalById', $_REQUEST['dependencia'] );
    	$resultadoDependencia = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
    	
    	$cadenaSql = $this->sql->getCadenaSql ( 'dependenciaUdistritalById', $_REQUEST['dependencia_destino'] );
    	$resultadoDependenciaDes = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
    	
    	$cadenaSql = $this->sql->getCadenaSql ( 'ordenadorUdistritalById', $_REQUEST['ordenador'] );
    	$resultadoOrdenador = $argoRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );

        if (isset($_REQUEST['estadoSolicitud']) && $_REQUEST['estadoSolicitud'] == "RELACIONADO") {

            $valorCodificado = "pagina=" . $miPaginaActual;
            $valorCodificado.="&opcion=actividad";
            $valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
            $valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
            $valorCodificado.="&idObjeto=" . $_REQUEST["idObjeto"];
            $valorCodificado.="&vigencia=" . $_REQUEST['vigencia'];
            $valorCodificado.="&unidadEjecutora=" . $_REQUEST['unidadEjecutora'];
            $valorCodificado.="&tipoNecesidad=" . $_REQUEST['tipoNecesidad'];

            $Proceso = "la <b>Modificación</b>";
        } else if (isset($_REQUEST['estadoSolicitudAct']) && $_REQUEST['estadoSolicitudAct'] == "CON ACTIVIDADES") {

            $valorCodificado = "pagina=" . $miPaginaActual;
            $valorCodificado.="&opcion=cotizacion";
            $valorCodificado.="&idObjeto=" . $_REQUEST["idObjeto"];
            $valorCodificado.="&vigencia=" . $_REQUEST['vigencia'];
            $valorCodificado.="&unidadEjecutora=" . $_REQUEST['unidadEjecutora'];
            $valorCodificado.="&tipoNecesidad=" . $_REQUEST['tipoNecesidad'];

            $Proceso = "la <b>Modificación</b>";
        } else {

            //***************************************************************************
        	$numberSolicitud = "SC-" . sprintf("%06d", $_REQUEST['idObjeto']);
        	
        	$parametros = array (
        			'idObjeto' => $_REQUEST ['idObjeto'],
        			'numero_solicitud' => $numberSolicitud,
        	);
        	
        	$cadenaSql = $this->sql->getCadenaSql ( 'actualizarObjetoNum', $parametros );
        	$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "insertar" );
        	//***************************************************************************
        	
        	$Proceso = "el <b>Registro</b>";
        	
        	$valorCodificado = "pagina=".$miPaginaActual;
        	$valorCodificado.="&opcion=actividad";
        	$valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
        	$valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
        	$valorCodificado.="&idObjeto=" . $_REQUEST["idObjeto"];
        	$valorCodificado.="&vigencia=".$_REQUEST['vigencia'];
        	$valorCodificado.="&unidadEjecutora=".$_REQUEST['unidadEjecutora'];
        	$valorCodificado.="&tipoNecesidad=".$_REQUEST['tipoNecesidad'];
        	$valorCodificado.="&estadoSolicitudAct=CON ACTIVIDADES";
        }

        $tipo = 'success';
        $mensaje =  "Se realizo " . $Proceso . " de la Solicitud de Cotización <b>N° ".$_REQUEST['idObjeto']."</b> con Vigencia <b>".$_REQUEST['vigencia']."</b> para la
					Unidad Ejecutora <b>". $valorUnidadEjecutoraText . "</b>
				</br>
				</br><b>Ordenador del Gasto Relacionado: </b> ".$resultadoOrdenador[0][1]."
				</br><b>Dependencia Solicitante: </b> ".$resultadoDependencia[0][1]."
				</br><b>Dependencia Destino: </b> ".$resultadoDependenciaDes[0][1]."
				</br><b>Fecha del Proceso: </b> ".$hoy."
				</br><b>Responsable: </b> (" . $resultadoUsuario[0]['identificacion'] . " - " . $resultadoUsuario[0]['nombre'] . " " . $resultadoUsuario[0]['apellido'] . ")<br>";
        $boton = "continuar";
        
    } else if ($_REQUEST['mensaje'] == 'confirmaCotizacion') {

        $hoy = date("d/m/Y");

        $cadenaSql = $this->sql->getCadenaSql('buscarUsuario', $_REQUEST['usuario']);
        $resultadoUsuario = $esteRecursoDBE->ejecutarAcceso($cadenaSql, "busqueda");

        //Buscar usuario para enviar correo
        $cadenaSql = $this->sql->getCadenaSql('buscarProveedores', $_REQUEST['idObjeto']);
        $resultadoProveedor = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");

        //Buscar usuario para enviar correo
        $cadenaSql = $this->sql->getCadenaSql('infoCotizacion', $_REQUEST["idObjeto"]);
        $objetoEspecifico = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");

        $cadenaSql = $this->sql->getCadenaSql ( 'dependenciaUdistritalById', $objetoEspecifico[0]['jefe_dependencia'] );
        $resultadoDependencia = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
        	
        $cadenaSql = $this->sql->getCadenaSql ( 'ordenadorUdistritalByIdCast', $objetoEspecifico[0]['ordenador_gasto'] );
        $resultadoOrdenadorDef = $argoRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );

        $cadenaSql = $this->sql->getCadenaSql('buscarUsuario', $objetoEspecifico[0]['usuario_creo']);
        $resultadoUsuarioCot = $esteRecursoDBE->ejecutarAcceso($cadenaSql, "busqueda");
        $resultadoOrdenador = $resultadoUsuarioCot[0]['nombre'] . " " . $resultadoUsuarioCot[0]['apellido'];

        $datos = array(
            'idSolicitud' => $objetoEspecifico[0]['numero_solicitud'],
            'vigencia' => $objetoEspecifico[0]['vigencia'],
            'unidadEjecutora' => $objetoEspecifico[0]['unidad_ejecutora']
        );

        $cadenaSql = $this->sql->getCadenaSql('consultarActividadesImp', $_REQUEST['idObjeto']);
        $resultadoActividades = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");



        if ($objetoEspecifico[0]['tipo_necesidad'] == 2 || $objetoEspecifico[0]['tipo_necesidad'] == 3) {
            $convocatoria = true;
            $mensaje = $this->lenguaje->getCadena('mensajeConvocatoria');

            $cadenaSql = $this->sql->getCadenaSql('consultarNBCImp', $_REQUEST["idObjeto"]);
            $resultadoNBC = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
        } else {
            $convocatoria = false;
            $mensaje = $this->lenguaje->getCadena('mensajeCotizacion');
        }
        $boton = "regresar";



        //INICIO enlace boton descargar resumen
        $variableResumen = "pagina=" . $miPaginaActual;
        $variableResumen.= "&action=" . $esteBloque["nombre"];
        $variableResumen.= "&bloque=" . $esteBloque["id_bloque"];
        $variableResumen.= "&bloqueGrupo=" . $esteBloque["grupo"];
        $variableResumen.= "&opcion=resumen";
        $variableResumen.= "&idObjeto=" . $_REQUEST['idObjeto'];
        $variableResumen.= "&idCodigo=" . $_REQUEST['idCodigo'];
        $variableResumen.= "&proveedoresView=false";
        $variableResumen = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableResumen, $directorio);

        //------------------Division para los botones-------------------------
        $atributos["id"] = "botones";
        $atributos["estilo"] = "marcoBotones widget";
        echo $this->miFormulario->division("inicio", $atributos);

        $enlace = "<a href='" . $variableResumen . "'>";
        if ($convocatoria) {
            $enlace.="<img src='" . $rutaBloque . "/images/pdf.png' width='35px'><br>Descargar Solicitud Cotización ";
        } else {
            $enlace.="<img src='" . $rutaBloque . "/images/pdf.png' width='35px'><br>Descargar Solicitud Cotización ";
        }
        $enlace.="</a><br><br>";
        echo $enlace;
        //------------------Fin Division para los botones-------------------------
        echo $this->miFormulario->division("fin");
        //FIN enlace boton descargar resumen


        $contenidoAct = '<br>';

        foreach ($resultadoActividades as $dato):
            $contenidoAct .= $dato['subclase'] . ' - ' . $dato['nombre'] . "<br>";
            $contenidoAct .= "<br>";
        endforeach;


        $dateInit = date_create($objetoEspecifico[0]['fecha_apertura']);
        $stringDateInit = date_format($dateInit, 'd/m/Y');

        $dateEnd = date_create($objetoEspecifico[0]['fecha_cierre']);
        $stringDateEnd = date_format($dateEnd, 'd/m/Y');

        //INICIO ENVIO DE CORREO AL USUARIO
        $rutaClases = $this->miConfigurador->getVariableConfiguracion("raizDocumento") . "/classes";

        include_once($rutaClases . "/mail/class.phpmailer.php");
        include_once($rutaClases . "/mail/class.smtp.php");

        $mail = new PHPMailer();

        //configuracion de cuenta de envio
        $mail->Host = "200.69.103.49";
        $mail->Mailer = "smtp";
        $mail->SMTPAuth = true;
        $mail->Username = "condor@udistrital.edu.co";
        $mail->Password = "CondorOAS2012";
        $mail->Timeout = 1200;
        $mail->Charset = "utf-8";
        $mail->IsHTML(true);

        $mail->From = 'agora@udistrital.edu.co';
        $mail->FromName = 'UNIVERSIDAD DISTRITAL FRANCISCO JOSÉ DE CALDAS';
        $mail->Subject = "Solicitud de Cotización N° (" . $objetoEspecifico[0]['numero_solicitud'] . ") BANCO ÁGORA UDISTRITAL";

        
        
        $contenido = "<p>Señor proveedor, la Universidad Distrital Francisco José de Caldas se encuentra interesada en poder contar con sus servicios para la adquisición de: </p>";

        $contenido.= "<br>";
        $contenido.= "<b>Número Cotización : </b>" . $objetoEspecifico[0]['numero_solicitud'] . "<br>";
        $contenido.= "<b>Título Cotización : </b>" . $objetoEspecifico[0]['titulo_cotizacion'] . "<br>";
        $contenido.= "<br>";
        $contenido.= "<b>Objeto/Tema Solicitud de Cotización : </b><br>" . $objetoEspecifico[0]['objetivo'] . "<br>";
        $contenido.= "<br>";
        $contenido.= "<b>Requisitos : </b><br>" . $objetoEspecifico[0]['requisitos'] . "<br>";
        $contenido.= "<br>";
        $contenido.= "<b>Observaciones Adicionales : </b><br>" . $objetoEspecifico[0]['observaciones'] . "<br>";
        $contenido.= "<br>";
        $contenido.= "<b>Fecha Apertura : </b><u>" . $stringDateInit . "</u><br>";
        $contenido.= "<br>";
        $contenido.= "<b>Fecha Cierre : </b><u>" . $stringDateEnd . "</u><br>";
        $contenido.= "<br>";
        $contenido.= "<br>";
        $contenido.= "<br>";
        $contenido.= "<b>Actividad(es) econ&oacute;mica(s) (CIIU) : </b><br>" . $contenidoAct . "<br>";
        $contenido.= "<br>";

        if ($convocatoria) {
            $contenido.= "<b>Profesión Relacionada en la Cotización (Núcleo Básico de Conocimiento SNIES): </b><br>" . $resultadoNBC[0]['nucleo'] . ' - ' . $resultadoNBC[0]['nombre'] . "<br>";
            $contenido.= "<br>";
        }

        $contenido.= "<b>Solicitante : </b>" . $resultadoOrdenador . "<br>";
        $contenido.= "<br>";
        $contenido.= "<b>Dependencia Solicitante : </b>" . $resultadoDependencia[0][1] . "<br>";
        $contenido.= "<br>";
        $contenido.= "<b>Responsable : </b>" . $resultadoOrdenadorDef[0][1];
        $contenido.= "<br>";
        $contenido.= "<br>";
        $contenido.= "<br>";
        $contenido.= "<br>";
        $contenido.= "<p>Por lo tanto, lo invitamos a presentar su cotización en las fechas indicadas como se relaciona en este presente comunicado (<b>Fecha Apertura y Fecha de Cierre</b>), recuerde que este
    			proceso debe ser realizado ingresando al Sistema de Registro Único de Personas y Banco de Proveedores ÁGORA (<a href='https://funcionarios.portaloas.udistrital.edu.co/agora/'>Click Aquí</a>).</p>";
        $contenido.= "<br>";
        $contenido.= "<p>Agradeciendo su gentil atención.</p>";
        $contenido.= "<br>";
        $contenido.= "<br>";
        $contenido.= "Cordialmente,<br>";
        $contenido.= "<b>UNIVERSIDAD DISTRITAL FRANCISCO JOSÉ DE CALDAS</b>";
        $contenido.= "<br>";
        $contenido.= "<b>NIT 899999230-7</b>";
        $contenido.= "<br>";
        $contenido.= "<br>";
        $contenido.= "<br>";
        $contenido.= "<br>";
        $contenido.= "<p>Este mensaje ha sido generado automáticamente, favor no responder..</p>";
        $contenido.= "<br>";
        $contenido.= "<br>";
		
        $mail->Body = $contenido;

        foreach ($resultadoProveedor as $dato):
            //$to_mail=$dato ['correo'];
            $to_mail = "jdavid.6700@gmail.com"; //PRUEBAS**********************************************************************************
            $mail->AddAddress($to_mail);

            if (!$mail->Send()) {
                echo "<h3>Error al enviar el mensaje a " . $to_mail . ": " . $mail->ErrorInfo . "</h3>";
                $mensajeEnvio = "<b>no</b> han sido informados mediante correo, ocurrió un problema con el envió de correos, por favor comuníquese con el Administrador del Sistema <b>(ERROR 1271)</b>.";
                $tipo = 'warning';
            } else {
                $mensajeEnvio = "ya han sido informados mediante correo.";
                $tipo = 'success';
            }

        endforeach;

        $mail->ClearAllRecipients();
        $mail->ClearAttachments();

        //FIN ENVIO DE CORREO AL USUARIO





        $mensaje = "Se realizó <b>PROCESAMIENTO</b> de la Solicitud de Cotización <b>N° " . $_REQUEST['idObjeto'] . "</b> con los Proveedores Encontrados que pueden responder a la misma.
				</br>
							</br>
							Los Proveedores que cumplen las características " . $mensajeEnvio . "
							</br>
				</br><b>Fecha del Proceso:</b> " . $hoy . "
				</br><b>Usuario:</b> (" . $resultadoUsuario[0]['identificacion'] . " - " . $resultadoUsuario[0]['nombre'] . " " . $resultadoUsuario[0]['apellido'] . ")<br>";
        $boton = "continuar";

        $valorCodificado = "pagina=" . $miPaginaActual;
        $valorCodificado.="&opcion=nuevo";
        $valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
        $valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
    } else if ($_REQUEST['mensaje'] == 'confirmaSolicitudModificacion') {
        $hoy = date("d/m/Y");
        
        $tipo = 'success';

        $cadenaSql = $this->sql->getCadenaSql('buscarUsuario', $_REQUEST['usuario']);
        $resultadoUsuario = $esteRecursoDBE->ejecutarAcceso($cadenaSql, "busqueda");
        
        $cadenaSql = $this->sql->getCadenaSql('buscarEstadoSolicitud', $_REQUEST['estado']);
  
        $resultadoEstado = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
        

        
        $mensaje = "Se realizó <b>SOLICITUD DE MODIFICACIÓN</b> sobre Cotización <b>N° " . $_REQUEST['idObjeto'] . "</b> .
				</br>
							</br>
							Estado : " . $resultadoEstado[0][0] . "
							</br>
				</br><b>Fecha del Proceso:</b> " . $hoy . "
				</br><b>Usuario:</b> (" . $resultadoUsuario[0]['identificacion'] . " - " . $resultadoUsuario[0]['nombre'] . " " . $resultadoUsuario[0]['apellido'] . ")<br>";
        
        $boton = "continuar";
        
        $valorCodificado = "pagina=" . $esteBloque['nombre'];
        $valorCodificado.="&opcion=nuevo";
        $valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
        $valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
    }
    else if ($_REQUEST['mensaje'] == 'confirmaModificacionSolicitud') {
        $hoy = date("d/m/Y");
        
        $tipo = 'success';

        $cadenaSql = $this->sql->getCadenaSql('buscarUsuario', $_REQUEST['usuario']);
        $resultadoUsuario = $esteRecursoDBE->ejecutarAcceso($cadenaSql, "busqueda");
        
    
        

        
        $mensaje = "Se realizó la <b> MODIFICACIÓN SOLICITADA</b> sobre Cotización <b>N° " . $_REQUEST['idObjeto'] . "</b> .
				</br>
				</br><b>Fecha del Proceso:</b> " . $hoy . "
				</br><b>Usuario:</b> (" . $resultadoUsuario[0]['identificacion'] . " - " . $resultadoUsuario[0]['nombre'] . " " . $resultadoUsuario[0]['apellido'] . ")<br>";
        
        $boton = "continuar";
        
        $valorCodificado = "pagina=" . $esteBloque['nombre'];
        $valorCodificado.="&opcion=nuevo";
        $valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
        $valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
    }
    else if ($_REQUEST['mensaje'] == 'errorSolicitudModificacion') {
        $tipo = 'error';
        $mensaje = $this->lenguaje->getCadena('mensajeErrorSolicitudModificacion') . ".";
        $boton = "continuar";

        $valorCodificado = "pagina=" . $esteBloque['nombre'];
        $valorCodificado.="&opcion=nuevo";
        $valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
        $valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
        
    }
    else if ($_REQUEST['mensaje'] == 'errorModificacionSolicitud') {
        $tipo = 'error';
        $mensaje = $this->lenguaje->getCadena('mensajeErrorModificacionSolicitud') . ".";
        $boton = "continuar";

        $valorCodificado = "pagina=" . $esteBloque['nombre'];
        $valorCodificado.="&opcion=nuevo";
        $valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
        $valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
        
    }
    else if ($_REQUEST['mensaje'] == 'error') {
        $tipo = 'error';
        $mensaje = $this->lenguaje->getCadena('mensajeError');
        $boton = "regresar";

        $valorCodificado = "pagina=" . $miPaginaActual;
        $valorCodificado.="&opcion=nuevo";
        $valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
        $valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
    } else if ($_REQUEST['mensaje'] == 'actualizo') {
        $tipo = 'success';
        $mensaje = $this->lenguaje->getCadena('mensajeActualizar') . $_REQUEST ['docente'] . ".";
        $boton = "continuar";

        $valorCodificado = "pagina=" . $esteBloque['nombre'];
        $valorCodificado.="&opcion=consultar";
        $valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
        $valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
    } else if ($_REQUEST['mensaje'] == 'noActualizo') {
        $tipo = 'error';
        $mensaje = $this->lenguaje->getCadena('mensajeNoActualizo') . $_REQUEST ['docente'] . ".";
        $boton = "continuar";

        $valorCodificado = "pagina=" . $esteBloque['nombre'];
        $valorCodificado.="&opcion=consultar";
        $valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
        $valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
    } else if ($_REQUEST['mensaje'] == 'mensajeExisteActividad') {
        $tipo = 'error';
        $mensaje = "Error en el cargue. <br>La Actividad ya se encuentra registrada.";
        $boton = "regresar";

        $actividades = true;

        if ($_REQUEST['tipoNecesidad'] == 'SERVICIO' || $_REQUEST['tipoNecesidad'] == 'BIEN Y SERVICIO') {
            $faltaNBC = true;
        } else {
            $faltaNBC = false;
        }

        $valorCodificado = "pagina=" . $miPaginaActual;
        $valorCodificado.="&opcion=actividad";
        $valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
        $valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
        $valorCodificado.="&idObjeto=" . $_REQUEST["idObjeto"];
        $valorCodificado.="&tipoNecesidad=" . $_REQUEST['tipoNecesidad'];
        $valorCodificado.="&estadoSolicitudAct=CON ACTIVIDADES";
        $valorCodificado.="&sigueNBC=" . $faltaNBC;
    } else if ($_REQUEST['mensaje'] == 'registroActividad') {


        $actividadesArray = explode(",", $_REQUEST['actividadesArray']);
        $listAc = "";

        foreach ($actividadesArray as $dato):
            $cadenaSql = $this->sql->getCadenaSql('ciiuSubClaseByNum', $dato);
            $resultadoCIIU = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");

            $listAc .= "<strong>" . $resultadoCIIU[0]['nombre'] . "</strong><br >";
            ;
        endforeach;




        $tipo = 'success';
        $mensaje = "Se realizó la modificación a la(s) Actividad(es) Económica(s)<br ><br>";
        $mensaje .= $listAc;
        $boton = "regresar";


        $actividades = true;

        if ($_REQUEST['tipoNecesidad'] == 'SERVICIO' || $_REQUEST['tipoNecesidad'] == 'BIEN Y SERVICIO') {
            $faltaNBC = true;
        } else {
            $faltaNBC = false;
        }

        $valorCodificado = "pagina=" . $miPaginaActual;
        $valorCodificado.="&opcion=actividad";
        $valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
        $valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
        $valorCodificado.="&idObjeto=" . $_REQUEST["idObjeto"];
        $valorCodificado.="&tipoNecesidad=" . $_REQUEST['tipoNecesidad'];
        $valorCodificado.="&estadoSolicitudAct=CON ACTIVIDADES";
        $valorCodificado.="&sigueNBC=" . $faltaNBC;
    } else if ($_REQUEST['mensaje'] == 'registroNucleo') {

        $cadenaSql = $this->sql->getCadenaSql('buscarNBC', $_REQUEST['nucleo']);
        $resultadoNBC = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");

        if ($_REQUEST['modificarNBC']) {
            $mensaje = "Se <b>Modificó</b> el Núcleo Básico de Conocimiento Relacionado<br >";
        } else {
            $mensaje = "Se <b>Registró</b> el Núcleo Básico de Conocimiento Relacionado<br >";
        }

        $tipo = 'success';
        $mensaje .= "<strong>" . $_REQUEST['nucleo'] . " - " . $resultadoNBC[0]['nombre'] . "</strong><br >";
        $boton = "regresar";

        //$actividades = false;

        $valorCodificado = "pagina=" . $miPaginaActual;
        $valorCodificado.="&opcion=actividad";
        $valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
        $valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
        $valorCodificado.="&idObjeto=" . $_REQUEST["idObjeto"];
        $valorCodificado.="&tipoNecesidad=" . $_REQUEST['tipoNecesidad'];
        $valorCodificado.="&objetoNBC=" . $_REQUEST['nucleo'];
        $valorCodificado.="&estadoSolicitudAct=CON ACTIVIDADES Y NBC";
    } else if ($_REQUEST['mensaje'] == 'respondioCotizacion') {

        //$cadenaSql = $this->sql->getCadenaSql ( 'buscarNBC', $_REQUEST['nucleo']  );
        //$resultadoNBC = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
        /*
          if($_REQUEST['modificarNBC']){
          $mensaje = "Se <b>Modificó</b> el Núcleo Básico de Conocimiento Relacionado<br >";
          }else{
          $mensaje = "Se <b>Registró</b> el Núcleo Básico de Conocimiento Relacionado<br >";
          } */

        //Buscar usuario para enviar correo
        $cadenaSql = $this->sql->getCadenaSql('objetoContratar', $_REQUEST["idObjeto"]);
        $objetoEspecifico = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");

        $tipo = 'success';
        $mensaje = "Se realizo <b>Registro</b> satisfactorio respuesta cotización</strong><br >";
        $boton = "regresar";

        //$actividades = false;

        $valorCodificado = "pagina=" . $miPaginaActual;
        $valorCodificado .= "&opcion=" . "verCotizacionSolicitud";
        $valorCodificado .= "&bloque=" . $esteBloque["id_bloque"];
        $valorCodificado .= "&bloqueGrupo=" . $esteBloque["grupo"];
        $valorCodificado .= "&idSolicitud=" . $_REQUEST['idObjeto'];
        $valorCodificado .= "&vigencia=" . $objetoEspecifico[0]['vigencia'];
        $valorCodificado .= "&unidadEjecutora=" . $objetoEspecifico[0]['unidad_ejecutora'];
        $valorCodificado .= "&usuario=" . $_REQUEST['usuario'];

//         $valorCodificado.="&idObjeto=" . $_REQUEST["idObjeto"];
//         $valorCodificado.="&tipoNecesidad=".$_REQUEST['tipoNecesidad'];
//         $valorCodificado.="&objetoNBC=".$_REQUEST['nucleo'];
//         $valorCodificado.="&estadoSolicitudAct=CON ACTIVIDADES Y NBC";
    } else if ($_REQUEST['mensaje'] == 'eliminoCotizacion') {
    	
    	$cadenaSql = $this->sql->getCadenaSql('buscarUsuario', $_REQUEST['usuario']);
    	$resultadoUsuario = $esteRecursoDBE->ejecutarAcceso($cadenaSql, "busqueda");
    	
    	//Buscar usuario para enviar correo
    	$cadenaSql = $this->sql->getCadenaSql('buscarProveedores', $_REQUEST['idObjeto']);
    	$resultadoProveedor = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
    	
    	//Buscar usuario para enviar correo
    	$cadenaSql = $this->sql->getCadenaSql('infoCotizacion', $_REQUEST["idObjeto"]);
    	$objetoEspecifico = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
    	
    	$hoy = date("d/m/Y");
    	
        $tipo = 'success';
        $mensaje = "Se eliminó satisfactoriamente la Solicitud de Cotización <b>(".$objetoEspecifico[0]['numero_solicitud'].")</b>.<br>
        		<br>
        		<b>Fecha de Eliminación:</b> ".$hoy."
        		";
        $boton = "continuar";

        $valorCodificado = "pagina=" . $esteBloque['nombre'];
        $valorCodificado.="&opcion=nuevo";
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



    if (isset($actividades) && $actividades = true) {

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
//        echo $this->miFormulario->campoBoton($atributos);
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
    } else {
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
    $atributos ['marco'] = false;
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