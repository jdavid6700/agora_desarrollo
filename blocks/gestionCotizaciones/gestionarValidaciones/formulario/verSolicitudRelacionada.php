<?php

namespace asignacionPuntajes\salariales\experienciaDireccionAcademica\formulario;

if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
}

class FormularioRegistro {

    var $miConfigurador;
    var $lenguaje;
    var $miFormulario;
    var $miSql;

    function __construct($lenguaje, $formulario, $sql) {

        $this->miConfigurador = \Configurador::singleton();

        $this->miConfigurador->fabricaConexiones->setRecursoDB('principal');
        $this->lenguaje = $lenguaje;

        $this->miFormulario = $formulario;

        $this->miSql = $sql;
    }

    function cambiafecha_format($fecha) {
        ereg("([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})", $fecha, $mifecha);
        $fechana = $mifecha[3] . "/" . $mifecha[2] . "/" . $mifecha[1];
        return $fechana;
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
        // Rescatar los datos de este bloque
        $esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");
        $miPaginaActual = $this->miConfigurador->getVariableConfiguracion('pagina');

        $directorio = $this->miConfigurador->getVariableConfiguracion("host");
        $directorio .= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
        $directorio .= $this->miConfigurador->getVariableConfiguracion("enlace");

        $rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
        $rutaBloque .= $this->miConfigurador->getVariableConfiguracion("site") . "/blocks/";
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
        $_REQUEST ['tiempo'] = time();

        // -------------------------------------------------------------------------------------------------
        // ---------------- SECCION: Parámetros Generales del Formulario ----------------------------------
        $esteCampo = $esteBloque ['nombre'] . "Registrar";
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
        echo $this->miFormulario->formulario($atributos);





        $datosSolicitudNecesidad = array(
            'idObjeto' => $_REQUEST['idSolicitud']
        );
        
       

        //*********************************************************************************************************************************
        $cadena_sql = $this->miSql->getCadenaSql("estadoSolicitudAgora", $datosSolicitudNecesidad);
        $resultado = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

        if (isset($resultado)) {

            if (isset($resultado[0]['estado_cotizacion'])) {//CAST
                switch ($resultado[0]['estado_cotizacion']) {
                    case 1 :
                        $estadoSolicitud = 'RELACIONADO';
                        break;
                    case 2 :
                        $estadoSolicitud = 'COTIZACION';
                        break;
                    case 3 :
                        $estadoSolicitud = 'ASIGNADO';
                        break;
                    case 4 :
                        $estadoSolicitud = 'CANCELADO';
                        break;
                    case 5 :
                        $estadoSolicitud = 'PROCESADO';
                        break;
                    case 6 :
                        $estadoSolicitud = 'RECOTIZACION';
                        break;
                }
            }


            $cadena_sql = $this->miSql->getCadenaSql("informacionSolicitudAgoraNoCast", $datosSolicitudNecesidad);
            $resultadoNecesidadRelacionada = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");


            $cadena_sql = $this->miSql->getCadenaSql("informacionCIIURelacionada", $datosSolicitudNecesidad);
            $resultadoNecesidadRelacionadaCIIU = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        }
        //***************SOLICITUDES RELACIONADAS******************************************************************************************

        if (isset($resultadoNecesidadRelacionada[0]['titulo_cotizacion']))
            $_REQUEST['tituloCotizacion'] = $resultadoNecesidadRelacionada[0]['titulo_cotizacion'];
        if (isset($resultadoNecesidadRelacionada[0]['vigencia']))
            $_REQUEST['vigencia'] = $resultadoNecesidadRelacionada[0]['vigencia'];

        if (isset($resultadoNecesidadRelacionada[0]['responsable']))
            $_REQUEST['responsable'] = $resultadoNecesidadRelacionada[0]['responsable'];

        if (isset($resultadoNecesidadRelacionada[0]['unidad_ejecutora']))
            $_REQUEST['unidadEjecutora'] = $resultadoNecesidadRelacionada[0]['unidad_ejecutora'];
        if (isset($resultadoNecesidadRelacionada[0]['dependencia']))
            $_REQUEST['dependencia'] = $resultadoNecesidadRelacionada[0]['dependencia'];
        if (isset($resultadoNecesidadRelacionada[0]['id_solicitante']))
            $_REQUEST['solicitante'] = $resultadoNecesidadRelacionada[0]['id_solicitante'];

        if (isset($resultadoNecesidadRelacionada[0]['fecha_apertura']))
            $_REQUEST['fechaAperturaRead'] = $this->cambiafecha_format($resultadoNecesidadRelacionada[0]['fecha_apertura']);
        if (isset($resultadoNecesidadRelacionada[0]['fecha_cierre']))
            $_REQUEST['fechaCierreRead'] = $this->cambiafecha_format($resultadoNecesidadRelacionada[0]['fecha_cierre']);

        if (isset($resultadoNecesidadRelacionada[0]['objetivo']))
            $_REQUEST['objetivo'] = $resultadoNecesidadRelacionada[0]['objetivo'];
        if (isset($resultadoNecesidadRelacionada[0]['requisitos']))
            $_REQUEST['requisitos'] = $resultadoNecesidadRelacionada[0]['requisitos'];
        if (isset($resultadoNecesidadRelacionada[0]['observaciones']))
            $_REQUEST['observaciones'] = $resultadoNecesidadRelacionada[0]['observaciones'];
        if (isset($resultadoNecesidadRelacionada[0]['estado_cotizacion']))
            $_REQUEST['estado'] = $resultadoNecesidadRelacionada[0]['estado_cotizacion'];

        if (isset($resultadoNecesidadRelacionada[0]['forma_seleccion_id']))
            $_REQUEST['formaSeleccion'] = $resultadoNecesidadRelacionada[0]['forma_seleccion_id'];

        if (isset($resultadoNecesidadRelacionada[0]['tipo_contrato']))
            $_REQUEST['tipoContrato'] = $resultadoNecesidadRelacionada[0]['tipo_contrato'];

        if (isset($resultadoNecesidadRelacionada[0]['anexo_cotizacion']))
            $_REQUEST['cotizacionSoporte'] = $resultadoNecesidadRelacionada[0]['anexo_cotizacion'];

        if (isset($resultadoNecesidadRelacionada[0]['plan_accion'])) {
            $cadena_sql = $this->miSql->getCadenaSql("buscarPlanAccionId", $resultadoNecesidadRelacionada[0]['plan_accion']);
            $resultadoPlan = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
            $_REQUEST['planAccion'] = $resultadoPlan [0]['descripcion'];
        }
        
		if (isset ( $resultadoNecesidadRelacionada [0] ['criterio_seleccion'] ))
			$_REQUEST ['criterioSeleccion'] = $resultadoNecesidadRelacionada [0] ['criterio_seleccion'];
		if (isset ( $resultadoNecesidadRelacionada [0] ['plazo_ejecucion'] ))
			$_REQUEST ['plazoEjecucion'] = $resultadoNecesidadRelacionada [0] ['plazo_ejecucion'];

        $cadena_sql = $this->miSql->getCadenaSql("buscarDetalleFormaPago", $datosSolicitudNecesidad);
        $resultadoFP = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

        // ----------------INICIO CONTROL: Campo ESTADO RELACION-------------------------------------------------------
        $esteCampo = 'estadoSolicitudRelacionada';
        $atributos ['id'] = $esteCampo;
        $atributos ['nombre'] = $esteCampo;
        $atributos ['tipo'] = 'hidden';

        if (isset($estadoSolicitud)) {
            $atributos ['valor'] = $estadoSolicitud;
        } else {
            $atributos ['valor'] = '';
        }
        $tab ++;

        // Aplica atributos globales al control
        $atributos = array_merge($atributos, $atributosGlobales);
        echo $this->miFormulario->campoCuadroTexto($atributos);
        unset($atributos);
        // ----------------FIN CONTROL: Campo de Texto --------------------------------------------------------




        echo "<div id='marcoDatosLoad' style='width: 100%;height: 900px'>
			<div style='width: 100%;height: 100px'>
			</div>
			<center><img src='" . $rutaBloque . "/images/loading.gif'" . ' width=20% height=20% vspace=15 hspace=3 >
			</center>
		  </div>';



        //------------------Division para los botones-------------------------
        $atributos["id"] = "botones";
        $atributos["estilo"] = "marcoBotones widget";
        echo $this->miFormulario->division("inicio", $atributos);

        //******************************************************************************************************************************
        $variable = "pagina=" . $miPaginaActual;
        $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);

        if (isset($_REQUEST['paginaOrigen'])) {
            $variable = "pagina=" . $_REQUEST['paginaOrigen'];
            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
        } else {
            $variable = "pagina=" . $miPaginaActual;
            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
        }

        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
        $esteCampo = 'botonRegresar';
        $atributos ['id'] = $esteCampo;
        $atributos ['enlace'] = $variable;
        $atributos ['tabIndex'] = 1;
        $atributos ['estilo'] = 'textoSubtitulo';
        $atributos ['enlaceTexto'] = $this->lenguaje->getCadena($esteCampo);
        $atributos ['ancho'] = '10%';
        $atributos ['alto'] = '10%';
        $atributos ['redirLugar'] = true;
        echo $this->miFormulario->enlace($atributos);

        unset($atributos);
        //********************************************************************************************************************************
        //------------------Fin Division para los botones-------------------------
        echo $this->miFormulario->division("fin");

        $_REQUEST['unidadPresupuestal'] = TRUE;
        if (isset($resultadoNecesidadRelacionada[0]['unidad_ejecutora']))
            $_REQUEST['unidadEjecutoraMod'] = $resultadoNecesidadRelacionada[0]['unidad_ejecutora'];
        if (isset($resultadoNecesidadRelacionada[0]['vigencia']))
            $_REQUEST['vigencia_solicitud_consultaMod'] = $resultadoNecesidadRelacionada[0]['vigencia'];
        if (isset($resultadoNecesidadRelacionada[0]['numero_necesidad']))
            $_REQUEST['numero_disponibilidadMod'] = $resultadoNecesidadRelacionada[0]['numero_necesidad'];

        $cadena_sql = $this->miSql->getCadenaSql("buscarDetalleItems", $datosSolicitudNecesidad);
        $resultadoItems = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        
          if (isset($_REQUEST['tipoCotizacion']) && $_REQUEST['tipoCotizacion'] == 'BIEN') {
            $campo1 = "entregables";
            $campo2 = "plazoEntrega";
            $bien = true;
            $permiso = 'bien';
        } else {
            $bien = false;
            if (isset($_REQUEST['tipoCotizacion']) && ($_REQUEST['tipoCotizacion'] == 'SERVICIO')) {
                $campo1 = "desServicio";
                $campo2 = "detalleEjecucion";
                $servicio = true;
                $permiso = 'servicio';
            } else {
                $campo1 = "entregablesdesServicio";
                $campo2 = "entregaEjecucion";
                $servicio = false;

                $permiso = 'ambos';
            }
        }
        
        echo "<section>"; {

            $esteCampo = "AgrupacionDisponibilidadNec";
            $atributos ['id'] = $esteCampo;
            $atributos ['leyenda'] = "Información Necesidad Presupuesto";
            $atributos ['estilo'] = 'jqueryui';
            echo $this->miFormulario->marcoAgrupacion('inicio', $atributos); {

                $esteCampo = "AgrupacionDisponibilidad";
                $atributos ['id'] = $esteCampo;
                $atributos ['leyenda'] = "Necesidad Asociada";
                $atributos ['estilo'] = 'jqueryui';
                echo $this->miFormulario->marcoAgrupacion('inicio', $atributos); {

                    $esteCampo = 'unidadPresupuestal';
                    $atributos ['id'] = $esteCampo;
                    $atributos ['nombre'] = $esteCampo;
                    $atributos ['tipo'] = 'hidden';
                    $atributos ['estilo'] = 'jqueryui';
                    $atributos ['dobleLinea'] = false;
                    $atributos ['tabIndex'] = $tab;
                    $atributos ['valor'] = $_REQUEST[$esteCampo];
                    $atributos ['deshabilitado'] = false;
                    $atributos ['tamanno'] = 30;
                    $atributos ['maximoTamanno'] = '';
                    $tab ++;
                    // Aplica atributos globales al control
                    $atributos = array_merge($atributos, $atributosGlobales);
                    echo $this->miFormulario->campoCuadroTexto($atributos);
                    unset($atributos);

                    $esteCampo = 'unidadEjecutoraMod';
                    $atributos ['id'] = $esteCampo;
                    $atributos ['nombre'] = $esteCampo;
                    $atributos ['tipo'] = 'hidden';
                    $atributos ['estilo'] = 'jqueryui';
                    $atributos ['dobleLinea'] = false;
                    $atributos ['tabIndex'] = $tab;
                    if (isset($_REQUEST [$esteCampo])) {
                        $atributos ['valor'] = $_REQUEST [$esteCampo];
                    } else {
                        $atributos ['valor'] = '';
                    }
                    $atributos ['deshabilitado'] = false;
                    $atributos ['tamanno'] = 30;
                    $atributos ['maximoTamanno'] = '';
                    $tab ++;
                    // Aplica atributos globales al control
                    $atributos = array_merge($atributos, $atributosGlobales);
                    echo $this->miFormulario->campoCuadroTexto($atributos);
                    unset($atributos);

                    $esteCampo = 'vigencia_solicitud_consultaMod';
                    $atributos ['id'] = $esteCampo;
                    $atributos ['nombre'] = $esteCampo;
                    $atributos ['tipo'] = 'hidden';
                    $atributos ['estilo'] = 'jqueryui';
                    $atributos ['dobleLinea'] = false;
                    $atributos ['tabIndex'] = $tab;
                    if (isset($_REQUEST [$esteCampo])) {
                        $atributos ['valor'] = $_REQUEST [$esteCampo];
                    } else {
                        $atributos ['valor'] = '';
                    }
                    $atributos ['deshabilitado'] = false;
                    $atributos ['tamanno'] = 30;
                    $atributos ['maximoTamanno'] = '';
                    $tab ++;
                    // Aplica atributos globales al control
                    $atributos = array_merge($atributos, $atributosGlobales);
                    echo $this->miFormulario->campoCuadroTexto($atributos);
                    unset($atributos);

                    $esteCampo = 'numero_disponibilidadMod';
                    $atributos ['id'] = $esteCampo;
                    $atributos ['nombre'] = $esteCampo;
                    $atributos ['tipo'] = 'hidden';
                    $atributos ['estilo'] = 'jqueryui';
                    $atributos ['dobleLinea'] = false;
                    $atributos ['tabIndex'] = $tab;
                    if (isset($_REQUEST [$esteCampo])) {
                        $atributos ['valor'] = $_REQUEST [$esteCampo];
                    } else {
                        $atributos ['valor'] = '';
                    }
                    $atributos ['deshabilitado'] = false;
                    $atributos ['tamanno'] = 30;
                    $atributos ['maximoTamanno'] = '';
                    $tab ++;
                    // Aplica atributos globales al control
                    $atributos = array_merge($atributos, $atributosGlobales);
                    echo $this->miFormulario->campoCuadroTexto($atributos);
                    unset($atributos);





                    $esteCampo = 'valor_real_acumulado';
                    $atributos ['id'] = $esteCampo;
                    $atributos ['nombre'] = $esteCampo;
                    $atributos ['tipo'] = 'hidden';
                    $atributos ['estilo'] = 'jqueryui';
                    $atributos ['dobleLinea'] = false;
                    $atributos ['tabIndex'] = $tab;
                    $atributos ['valor'] = 0;
                    $atributos ['deshabilitado'] = false;
                    $atributos ['tamanno'] = 30;
                    $atributos ['maximoTamanno'] = '';
                    $tab ++;
                    // Aplica atributos globales al control
                    $atributos = array_merge($atributos, $atributosGlobales);
                    echo $this->miFormulario->campoCuadroTexto($atributos);
                    unset($atributos);


                    $esteCampo = 'indices_cdps';
                    $atributos ['id'] = $esteCampo;
                    $atributos ['nombre'] = $esteCampo;
                    $atributos ['tipo'] = 'hidden';
                    $atributos ['estilo'] = 'jqueryui';
                    $atributos ['dobleLinea'] = false;
                    $atributos ['tabIndex'] = $tab;
                    $atributos ['valor'] = "";
                    $atributos ['deshabilitado'] = false;
                    $atributos ['tamanno'] = 30;
                    $atributos ['maximoTamanno'] = '';
                    $tab ++;
                    // Aplica atributos globales al control
                    $atributos = array_merge($atributos, $atributosGlobales);
                    echo $this->miFormulario->campoCuadroTexto($atributos);
                    unset($atributos);
                    $esteCampo = 'indices_cdps_vigencias';
                    $atributos ['id'] = $esteCampo;
                    $atributos ['nombre'] = $esteCampo;
                    $atributos ['tipo'] = 'hidden';
                    $atributos ['estilo'] = 'jqueryui';
                    $atributos ['dobleLinea'] = false;
                    $atributos ['tabIndex'] = $tab;
                    $atributos ['valor'] = "";
                    $atributos ['deshabilitado'] = false;
                    $atributos ['tamanno'] = 30;
                    $atributos ['maximoTamanno'] = '';
                    $tab ++;
                    // Aplica atributos globales al control
                    $atributos = array_merge($atributos, $atributosGlobales);
                    echo $this->miFormulario->campoCuadroTexto($atributos);
                    unset($atributos);
                    $esteCampo = 'indice_tabla';
                    $atributos ['id'] = $esteCampo;
                    $atributos ['nombre'] = $esteCampo;
                    $atributos ['tipo'] = 'hidden';
                    $atributos ['estilo'] = 'jqueryui';
                    $atributos ['dobleLinea'] = false;
                    $atributos ['tabIndex'] = $tab;
                    $atributos ['valor'] = 0;
                    $atributos ['deshabilitado'] = false;
                    $atributos ['tamanno'] = 30;
                    $atributos ['maximoTamanno'] = '';
                    $tab ++;
                    // Aplica atributos globales al control
                    $atributos = array_merge($atributos, $atributosGlobales);
                    echo $this->miFormulario->campoCuadroTexto($atributos);
                    unset($atributos);

                    $esteCampo = 'unidad_ejecutora_hidden';
                    $atributos ['id'] = $esteCampo;
                    $atributos ['nombre'] = $esteCampo;
                    $atributos ['tipo'] = 'hidden';
                    $atributos ['estilo'] = 'jqueryui';
                    $atributos ['dobleLinea'] = false;
                    $atributos ['tabIndex'] = $tab;
                    $atributos ['valor'] = "";
                    $atributos ['deshabilitado'] = false;
                    $atributos ['tamanno'] = 30;
                    $atributos ['maximoTamanno'] = '';
                    $tab ++;
                    // Aplica atributos globales al control
                    $atributos = array_merge($atributos, $atributosGlobales);
                    echo $this->miFormulario->campoCuadroTexto($atributos);
                    unset($atributos);

                    $esteCampo = 'ordenador_hidden';
                    $atributos ['id'] = $esteCampo;
                    $atributos ['nombre'] = $esteCampo;
                    $atributos ['tipo'] = 'hidden';
                    $atributos ['estilo'] = 'jqueryui';
                    $atributos ['dobleLinea'] = false;
                    $atributos ['tabIndex'] = $tab;
                    $atributos ['valor'] = "";
                    $atributos ['deshabilitado'] = false;
                    $atributos ['tamanno'] = 30;
                    $atributos ['maximoTamanno'] = '';
                    $tab ++;
                    // Aplica atributos globales al control
                    $atributos = array_merge($atributos, $atributosGlobales);
                    echo $this->miFormulario->campoCuadroTexto($atributos);
                    unset($atributos);



                    /*
                     *
                     * VARIABLES - ENTRADA
                     *
                     * */

                    // ---------------- CONTROL: Lista Vigencia--------------------------------------------------------
                    $esteCampo = "unidadEjecutoraCheck";
                    $atributos ['nombre'] = $esteCampo;
                    $atributos ['id'] = $esteCampo;
                    $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                    $atributos ["etiquetaObligatorio"] = true;
                    $atributos ['tab'] = $tab ++;
                    $atributos ['anchoEtiqueta'] = 200;
                    $atributos ['evento'] = '';
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

                    if (isset($_REQUEST [$esteCampo])) {
                        $atributos ['seleccion'] = $_REQUEST [$esteCampo];
                    } else {
                        $atributos ['seleccion'] = - 1;
                    }

                    $matrizItems = array(
                        array(1, '1 - RECTORÍA'),
                        array(2, '2 - IDEXUD')
                    );

                    $atributos ['matrizItems'] = $matrizItems;

                    $atributos = array_merge($atributos, $atributosGlobales);
                    //echo $this->miFormulario->campoCuadroLista ( $atributos );
                    unset($atributos);
                    // ----------------FIN CONTROL: Lista Vigencia--------------------------------------------------------

                    $esteCampo = 'vigencia_solicitud_consulta';
                    $atributos ['columnas'] = 2;
                    $atributos ['nombre'] = $esteCampo;
                    $atributos ['id'] = $esteCampo;
                    $atributos ['evento'] = '';
                    $atributos ['deshabilitado'] = true;
                    $atributos ["etiquetaObligatorio"] = false;
                    $atributos ['tab'] = $tab;
                    $atributos ['tamanno'] = 1;
                    $atributos ['estilo'] = 'jqueryui';
                    $atributos ['validar'] = '';
                    $atributos ['limitar'] = true;
                    $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                    $atributos ['anchoEtiqueta'] = 200;

                    if (isset($_REQUEST [$esteCampo])) {
                        $atributos ['seleccion'] = $_REQUEST [$esteCampo];
                    } else {
                        $atributos ['seleccion'] = - 1;
                    }

                    //--------------------------- Consulta Agora ----------------------------------
                    $atributos ['cadena_sql'] = $cadenaSql = $this->miSql->getCadenaSql('vigencias_sica_disponibilidades');
                    $matrizItems = $siCapitalRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");

                    //$atributos ['cadena_sql'] = $this->miSql->getCadenaSql("vigencias_sica_disponibilidades");
                    //$matrizItems = $DBSICA->ejecutarAcceso($atributos ['cadena_sql'], "busqueda");

                    $atributos ['matrizItems'] = $matrizItems;

                    // Utilizar lo siguiente cuando no se pase un arreglo:
                    // $atributos['baseDatos']='ponerAquiElNombreDeLaConexión';
                    // $atributos ['cadena_sql']='ponerLaCadenaSqlAEjecutar';
                    $tab ++;
                    $atributos = array_merge($atributos, $atributosGlobales);
                    //echo $this->miFormulario->campoCuadroLista($atributos);
                    unset($atributos);

                    $esteCampo = 'numero_disponibilidad';
                    $atributos ['columnas'] = 2;
                    $atributos ['nombre'] = $esteCampo;
                    $atributos ['id'] = $esteCampo;
                    $atributos ['seleccion'] = - 1;
                    $atributos ['evento'] = '';
                    $atributos ['deshabilitado'] = true;
                    $atributos ["etiquetaObligatorio"] = false;
                    $atributos ['tab'] = $tab;
                    $atributos ['tamanno'] = 1;
                    $atributos ['estilo'] = 'jqueryui';
                    $atributos ['validar'] = 'required';
                    $atributos ['limitar'] = false;
                    $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                    $atributos ['anchoEtiqueta'] = 250;

                    if (isset($Acta [0] [$esteCampo])) {
                        $atributos ['valor'] = $Acta [0] [$esteCampo];
                    } else {
                        $atributos ['valor'] = '';
                    }

                    $atributos ['cadena_sql'] = '';
                    //

                    $arreglo = array(
                        array(
                            '',
                            'Sin CDPS Registradas'
                        )
                    );


                    $atributos ['matrizItems'] = $arreglo;
                    $tab ++;
                    $atributos = array_merge($atributos, $atributosGlobales);
                    //echo $this->miFormulario->campoCuadroLista($atributos);
                    unset($atributos);

                    //------------------Division para los botones-------------------------
                    $atributos["id"] = "botones";
                    $atributos["estilo"] = "marcoBotones widget";
                    echo $this->miFormulario->division("inicio", $atributos);

                    echo "<div class=''>
		
	<div class='row'>
        <div class='col-md-24'>
				<div class='panel panel-primary'>
					<div class='panel-heading'>
						<h3 class='panel-title'>Información Presupuestal</h3>
		
					</div>
					<table class='table1' id='tablacdpasociados' width='100%'>
						<thead>
							<tr>
								<th><center>Vigencia</center></th>
								<th><center>Solicitud de Necesidad</center></th>
								<th><center>Número de Disponibilidad</center></th>
								<th><center>Valor en pesos ($)</center></th>
								<th><center>Dependencia</center></th>
								<th><center>Rubro</center></th>
								<th><center>Estado</center></th>
		
							</tr>
						</thead>
                                               <tbody>
                                                 <tr id='0'></tr>
                                               </tbody>
                                               </table>";


                    //------------------Fin Division para los botones-------------------------
                    echo $this->miFormulario->division("fin");





                    //------------------Division para los botones-------------------------
                    $atributos["id"] = "botones";
                    $atributos["estilo"] = "marcoBotones widget";
                    //echo $this->miFormulario->division("inicio",$atributos);
                    //******************************************************************************************************************************
                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                    $esteCampo = 'eliminarCDP';
                    $atributos ['id'] = $esteCampo;
                    $atributos ['enlace'] = "#";
                    $atributos ['tabIndex'] = 1;
                    $atributos ['estilo'] = 'textoSubtitulo';
                    $atributos ['enlaceTexto'] = "Eliminar Necesidad";
                    $atributos ['ancho'] = '10%';
                    $atributos ['alto'] = '10%';
                    $atributos ['redirLugar'] = false;
                    //echo $this->miFormulario->enlace ( $atributos );

                    unset($atributos);
                    //********************************************************************************************************************************
                    //------------------Fin Division para los botones-------------------------
                    //echo $this->miFormulario->division("fin");



                    $esteCampo = 'valor_acumulado';
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
                    $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                    $atributos ['validar'] = 'required';

                    if (isset($_REQUEST [$esteCampo])) {
                        $atributos ['valor'] = $_REQUEST [$esteCampo];
                    } else {
                        $atributos ['valor'] = '';
                    }
                    $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
                    $atributos ['deshabilitado'] = TRUE;
                    $atributos ['tamanno'] = 20;
                    $atributos ['maximoTamanno'] = '';
                    $atributos ['anchoEtiqueta'] = 175;
                    $tab ++;

                    // Aplica atributos globales al control
                    $atributos = array_merge($atributos, $atributosGlobales);
                    echo $this->miFormulario->campoCuadroTexto($atributos);
                    unset($atributos);
                }
                echo $this->miFormulario->marcoAgrupacion('fin');
            }
            echo $this->miFormulario->marcoAgrupacion('fin');
        }

        echo "</section>";






        $esteCampo = "marcoDatosSolicitudCot";
        $atributos ['id'] = $esteCampo;
        $atributos ["estilo"] = "jqueryui";
        $atributos ['tipoEtiqueta'] = 'inicio';
        $atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo) . " - Solicitud Número # (" . $resultadoNecesidadRelacionada[0]['numero_solicitud'] . ")";
        echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);



        $tipo = 'information';
        $mensaje = "<b>IMPORTANTE</b><br>
							<br>
							Recuerde que la reglamentación a tener en cuenta para los procesos derivados de las cotizaciones, son el Estatuto de Contratación y sus Resoluciones Reglamentarias y el manual de supervisión e interventoría estipulados por
				<b>ACUERDO No. 03 (11 de Marzo de 2015)</b> <i>'Por el cual se expide el Estatuto de Contratación de la Universidad Distrital Francisco José de Caldas'</i>, la
				<b>RESOLUCIÓN  No. 629 (17 de Noviembre de 2016)</b> <i>'Por medio de la cual se adopta el Manual de Supervisión e Interventoría de la Universidad Distrital Francisco José de Caldas'</i>,
        		la <b>RESOLUCIÓN  No. 262 (2 de Junio de 2015)</b> <i>'Por medio de la cual se reglamenta el Acuerdo 03 de 2015, Estatuto de Contratación de la Universidad Distrital Francisco José de Caldas y se dictan otras disposiciones'</i> y
        		la <b>RESOLUCIÓN  No. 683 (9 de Diciembre de 2016)</b> <i>'Por la cual se crea y se reglamenta el banco de proveedores en la Universidad Distrital Francisco José de Caldas'</i>.
							";
        // ---------------- SECCION: Controles del Formulario -----------------------------------------------
        $esteCampo = 'mensaje';
        $atributos["id"] = $esteCampo; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
        $atributos["etiqueta"] = "";
        $atributos["estilo"] = "centrar";
        $atributos["tipo"] = $tipo;
        $atributos["mensaje"] = $mensaje;
        echo $this->miFormulario->cuadroMensaje($atributos);
        unset($atributos);





        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
        $esteCampo = 'tituloCotizacion';
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

        if (isset($_REQUEST [$esteCampo])) {
            $atributos ['valor'] = $_REQUEST [$esteCampo];
        } else {
            $atributos ['valor'] = '';
        }

        $tab ++;

        // Aplica atributos globales al control
        $atributos = array_merge($atributos, $atributosGlobales);
        echo $this->miFormulario->campoTextArea($atributos);
        unset($atributos);


        // ----------------INICIO CONTROL: Campo de Texto VIGENCIA--------------------------------------------------------
        $esteCampo = 'vigencia';
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
        $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
        $atributos ['validar'] = '';

        if (isset($_REQUEST [$esteCampo])) {
            $atributos ['valor'] = $_REQUEST [$esteCampo];
        } else {
            $atributos ['valor'] = '';
        }

        $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
        $atributos ['deshabilitado'] = true;
        $atributos ['tamanno'] = 10;
        $atributos ['maximoTamanno'] = '10';
        $atributos ['anchoEtiqueta'] = 200;
        $tab ++;

        // Aplica atributos globales al control
        $atributos = array_merge($atributos, $atributosGlobales);
        echo $this->miFormulario->campoCuadroTexto($atributos);
        unset($atributos);
        // ----------------FIN CONTROL: Campo de Texto VIGENCIA--------------------------------------------------------





        $esteCampo = "marcoDatosSolicitudCotRes";
        $atributos ['id'] = $esteCampo;
        $atributos ["estilo"] = "jqueryui";
        $atributos ['tipoEtiqueta'] = 'inicio';
        $atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo);
        echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);

        $cadenaSql = $this->miSql->getCadenaSql('buscarUsuario', $resultadoNecesidadRelacionada[0]['usuario_creo']);
        $resultadoUsuario = $frameworkRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");


        // ----------------INICIO CONTROL: Campo de Texto FUNCIONARIO--------------------------------------------------------
        $esteCampo = 'nombresResponsable';
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
        $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
        $atributos ['validar'] = 'required';

        $atributos ['valor'] = $resultadoUsuario[0]['nombre'];

        $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
        $atributos ['deshabilitado'] = true;
        $atributos ['tamanno'] = 50;
        $atributos ['maximoTamanno'] = '10';
        $atributos ['anchoEtiqueta'] = 200;
        $tab ++;

        // Aplica atributos globales al control
        $atributos = array_merge($atributos, $atributosGlobales);
        echo $this->miFormulario->campoCuadroTexto($atributos);
        unset($atributos);
        // ----------------FIN CONTROL: Campo de Texto FUNCIONARIO--------------------------------------------------------
        // ----------------INICIO CONTROL: Campo de Texto FUNCIONARIO--------------------------------------------------------
        $esteCampo = 'apellidosResponsable';
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
        $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
        $atributos ['validar'] = 'required';

        $atributos ['valor'] = $resultadoUsuario[0]['apellido'];

        $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
        $atributos ['deshabilitado'] = true;
        $atributos ['tamanno'] = 50;
        $atributos ['maximoTamanno'] = '10';
        $atributos ['anchoEtiqueta'] = 200;
        $tab ++;

        // Aplica atributos globales al control
        $atributos = array_merge($atributos, $atributosGlobales);
        echo $this->miFormulario->campoCuadroTexto($atributos);
        unset($atributos);
        // ----------------FIN CONTROL: Campo de Texto FUNCIONARIO--------------------------------------------------------
        // ----------------INICIO CONTROL: Campo de Texto FUNCIONARIO--------------------------------------------------------
        $esteCampo = 'identificacionResponsable';
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
        $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
        $atributos ['validar'] = 'required';

        $atributos ['valor'] = $resultadoUsuario[0]['identificacion'];

        $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
        $atributos ['deshabilitado'] = true;
        $atributos ['tamanno'] = 50;
        $atributos ['maximoTamanno'] = '10';
        $atributos ['anchoEtiqueta'] = 200;
        $tab ++;

        // Aplica atributos globales al control
        $atributos = array_merge($atributos, $atributosGlobales);
        echo $this->miFormulario->campoCuadroTexto($atributos);
        unset($atributos);
        // ----------------FIN CONTROL: Campo de Texto FUNCIONARIO--------------------------------------------------------
        // ---------------- CONTROL: Lista Vigencia--------------------------------------------------------
        $esteCampo = "unidadEjecutora";
        $atributos ['nombre'] = $esteCampo;
        $atributos ['id'] = $esteCampo;
        $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
        $atributos ["etiquetaObligatorio"] = true;
        $atributos ['tab'] = $tab ++;
        $atributos ['anchoEtiqueta'] = 200;
        $atributos ['evento'] = '';
        if (isset($resultadoNecesidadRelacionada[0]['unidad_ejecutora'])) {
            $atributos ['seleccion'] = $resultadoNecesidadRelacionada[0]['unidad_ejecutora'];
        } else {
            $atributos ['seleccion'] = - 1;
        }
        $atributos ['deshabilitado'] = true;
        $atributos ['columnas'] = 2;
        $atributos ['tamanno'] = 1;
        $atributos ['ajax_function'] = "";
        $atributos ['ajax_control'] = $esteCampo;
        $atributos ['estilo'] = "jqueryui";
        $atributos ['validar'] = "required";
        $atributos ['limitar'] = false;
        $atributos ['anchoCaja'] = 60;
        $atributos ['miEvento'] = '';

        $matrizItems = array(
            array(1, '1 - RECTORÍA'),
            array(2, '2 - IDEXUD')
        );

        $atributos ['matrizItems'] = $matrizItems;

        $atributos = array_merge($atributos, $atributosGlobales);
        echo $this->miFormulario->campoCuadroLista($atributos);
        unset($atributos);
        // ----------------FIN CONTROL: Lista Vigencia--------------------------------------------------------
        // ---------------- CONTROL: Lista clase CIIU--------------------------------------------------------
        $esteCampo = "ordenador";
        $atributos ['nombre'] = $esteCampo;
        $atributos ['id'] = $esteCampo;
        $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
        $atributos ["etiquetaObligatorio"] = true;
        $atributos ['tab'] = $tab ++;
        $atributos ['anchoEtiqueta'] = 200;
        $atributos ['evento'] = '';
        if (isset($resultadoNecesidadRelacionada[0]['ordenador_gasto'])) {
            $atributos ['seleccion'] = $resultadoNecesidadRelacionada[0]['ordenador_gasto'];
        } else {
            $atributos ['seleccion'] = - 1;
        }
        $atributos ['deshabilitado'] = true;
        $atributos ['columnas'] = 1;
        $atributos ['tamanno'] = 1;
        $atributos ['ajax_function'] = "";
        $atributos ['ajax_control'] = $esteCampo;
        $atributos ['estilo'] = "jqueryui";
        $atributos ['validar'] = "required";
        $atributos ['limitar'] = false;
        $atributos ['anchoCaja'] = 60;
        $atributos ['miEvento'] = '';
        $atributos ['cadena_sql'] = $cadenaSql = $this->miSql->getCadenaSql('ordenadorUdistrital');
        $matrizItems = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
        $atributos ['matrizItems'] = $matrizItems;
        $atributos = array_merge($atributos, $atributosGlobales);
        echo $this->miFormulario->campoCuadroLista($atributos);
        unset($atributos);
        // ----------------FIN CONTROL: Lista clase CIIU--------------------------------------------------------
        // ---------------- CONTROL: Lista clase CIIU--------------------------------------------------------
        $esteCampo = "dependencia";
        $atributos ['nombre'] = $esteCampo;
        $atributos ['id'] = $esteCampo;
        $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
        $atributos ["etiquetaObligatorio"] = true;
        $atributos ['tab'] = $tab ++;
        $atributos ['anchoEtiqueta'] = 200;
        $atributos ['evento'] = '';

        $cadenaSql = $this->miSql->getCadenaSql ( "dependenciaJefeLast", $resultadoNecesidadRelacionada[0]['jefe_dependencia'] );
        $dep = $coreRecursoDB->ejecutarAcceso ( $cadenaSql, 'busqueda' );
        $resultadoNecesidadRelacionada[0]['jefe_dependencia'] = $dep[0][0];

        if (isset($resultadoNecesidadRelacionada[0]['jefe_dependencia'])) {
            $atributos ['seleccion'] = $resultadoNecesidadRelacionada[0]['jefe_dependencia'];
        } else {
            $atributos ['seleccion'] = - 1;
        }
        $atributos ['deshabilitado'] = true;
        $atributos ['columnas'] = 1;
        $atributos ['tamanno'] = 1;
        $atributos ['ajax_function'] = "";
        $atributos ['ajax_control'] = $esteCampo;
        $atributos ['estilo'] = "jqueryui";
        $atributos ['validar'] = "required";
        $atributos ['limitar'] = false;
        $atributos ['anchoCaja'] = 60;
        $atributos ['miEvento'] = '';
        $atributos ['cadena_sql'] = $cadenaSql = $this->miSql->getCadenaSql('dependenciaUdistrital');
        $matrizItems = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
        $atributos ['matrizItems'] = $matrizItems;
        $atributos = array_merge($atributos, $atributosGlobales);
        echo $this->miFormulario->campoCuadroLista($atributos);
        unset($atributos);
        // ----------------FIN CONTROL: Lista clase CIIU--------------------------------------------------------
        // ---------------- CONTROL: Lista clase CIIU--------------------------------------------------------
        $esteCampo = "dependenciaDestino";
        $atributos ['nombre'] = $esteCampo;
        $atributos ['id'] = $esteCampo;
        $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
        $atributos ["etiquetaObligatorio"] = true;
        $atributos ['tab'] = $tab ++;
        $atributos ['anchoEtiqueta'] = 200;
        $atributos ['evento'] = '';

        $cadenaSql = $this->miSql->getCadenaSql ( "dependenciaJefeLast", $resultadoNecesidadRelacionada[0]['jefe_dependencia_destino'] );
        $dep = $coreRecursoDB->ejecutarAcceso ( $cadenaSql, 'busqueda' );
        $resultadoNecesidadRelacionada[0]['jefe_dependencia_destino'] = $dep[0][0];
        
        if (isset($resultadoNecesidadRelacionada[0]['jefe_dependencia_destino'])) {
            $atributos ['seleccion'] = $resultadoNecesidadRelacionada[0]['jefe_dependencia_destino'];
        } else {
            $atributos ['seleccion'] = - 1;
        }
        $atributos ['deshabilitado'] = true;
        $atributos ['columnas'] = 1;
        $atributos ['tamanno'] = 1;
        $atributos ['ajax_function'] = "";
        $atributos ['ajax_control'] = $esteCampo;
        $atributos ['estilo'] = "jqueryui";
        $atributos ['validar'] = "required";
        $atributos ['limitar'] = false;
        $atributos ['anchoCaja'] = 60;
        $atributos ['miEvento'] = '';
        $atributos ['cadena_sql'] = $cadenaSql = $this->miSql->getCadenaSql('dependenciaUdistrital');
        $matrizItems = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
        $atributos ['matrizItems'] = $matrizItems;
        $atributos = array_merge($atributos, $atributosGlobales);
        echo $this->miFormulario->campoCuadroLista($atributos);
        unset($atributos);
        // ----------------FIN CONTROL: Lista clase CIIU--------------------------------------------------------




        echo $this->miFormulario->marcoAgrupacion('fin');





        $esteCampo = "marcoDatosSolicitudCotCar";
        $atributos ['id'] = $esteCampo;
        $atributos ["estilo"] = "jqueryui";
        $atributos ['tipoEtiqueta'] = 'inicio';
        $atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo);
        echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);


        // ----------------INICIO CONTROL: Campo de Texto FECHA SOLICITUD--------------------------------------------------------
        $esteCampo = 'fechaAperturaRead';
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
        $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
        $atributos ['validar'] = 'required,custom[date]';

        if (isset($_REQUEST [$esteCampo])) {
            $atributos ['valor'] = $_REQUEST [$esteCampo];
        } else {
            $atributos ['valor'] = '';
        }

        $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
        $atributos ['deshabilitado'] = true;
        $atributos ['tamanno'] = 15;
        $atributos ['maximoTamanno'] = '30';
        $atributos ['anchoEtiqueta'] = 200;
        $tab ++;

        // Aplica atributos globales al control
        $atributos = array_merge($atributos, $atributosGlobales);
        echo $this->miFormulario->campoCuadroTexto($atributos);
        unset($atributos);
        // ----------------FIN CONTROL: Campo de Texto FECHA SOLICITUD--------------------------------------------------------
        // ----------------INICIO CONTROL: Campo de Texto FECHA SOLICITUD--------------------------------------------------------
        $esteCampo = 'fechaCierreRead';
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
        $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
        $atributos ['validar'] = 'required,custom[date]';

        if (isset($_REQUEST [$esteCampo])) {
            $atributos ['valor'] = $_REQUEST [$esteCampo];
        } else {
            $atributos ['valor'] = '';
        }

        $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
        $atributos ['deshabilitado'] = true;
        $atributos ['tamanno'] = 15;
        $atributos ['maximoTamanno'] = '30';
        $atributos ['anchoEtiqueta'] = 200;
        $tab ++;

        // Aplica atributos globales al control
        $atributos = array_merge($atributos, $atributosGlobales);
        echo $this->miFormulario->campoCuadroTexto($atributos);
        unset($atributos);
        // ----------------FIN CONTROL: Campo de Texto FECHA SOLICITUD--------------------------------------------------------
        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
        $esteCampo = 'objetivo';
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
        $atributos ['validar'] = 'required,minSize[30]';
        $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
        $atributos ['deshabilitado'] = true;
        $atributos ['tamanno'] = 20;
        $atributos ['maximoTamanno'] = '';
        $atributos ['anchoEtiqueta'] = 220;
        $atributos ['textoEnriquecido'] = true;

        if (isset($_REQUEST [$esteCampo])) {
            $atributos ['valor'] = $_REQUEST [$esteCampo];
        } else {
            $atributos ['valor'] = '';
        }

        $tab ++;

        // Aplica atributos globales al control
        $atributos = array_merge($atributos, $atributosGlobales);
        echo $this->miFormulario->campoTextArea($atributos);
        unset($atributos);



        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
        $esteCampo = 'requisitos';
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
        $atributos ['validar'] = 'required,minSize[30]';
        $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
        $atributos ['deshabilitado'] = true;
        $atributos ['tamanno'] = 20;
        $atributos ['maximoTamanno'] = '';
        $atributos ['anchoEtiqueta'] = 220;

        if (isset($_REQUEST [$esteCampo])) {
            $atributos ['valor'] = $_REQUEST [$esteCampo];
        } else {
            $atributos ['valor'] = '';
        }

        $tab ++;

        // Aplica atributos globales al control
        $atributos = array_merge($atributos, $atributosGlobales);
        echo $this->miFormulario->campoTextArea($atributos);
        unset($atributos);


        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
        $esteCampo = 'observaciones';
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
        $atributos ['validar'] = '';
        $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
        $atributos ['deshabilitado'] = true;
        $atributos ['tamanno'] = 20;
        $atributos ['maximoTamanno'] = '';
        $atributos ['anchoEtiqueta'] = 220;

        if (isset($_REQUEST [$esteCampo])) {
            $atributos ['valor'] = $_REQUEST [$esteCampo];
        } else {
            $atributos ['valor'] = '';
        }

        $tab ++;

        // Aplica atributos globales al control
        $atributos = array_merge($atributos, $atributosGlobales);
        echo $this->miFormulario->campoTextArea($atributos);
        unset($atributos);


        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
        $esteCampo = 'criterioSeleccion';
        $atributos ['id'] = $esteCampo;
        $atributos ['nombre'] = $esteCampo;
        $atributos ['tipo'] = 'text';
        $atributos ['estilo'] = 'jqueryui';
        $atributos ['marco'] = true;
        $atributos ['estiloMarco'] = '';
        $atributos ["etiquetaObligatorio"] = true;
        $atributos ['alto'] = 100;
        $atributos ['columnas'] = 120;
        $atributos ['filas'] = 8;
        $atributos ['dobleLinea'] = 0;
        $atributos ['tabIndex'] = $tab;
        $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
        $atributos ['validar'] = 'required,minSize[15], maxSize[5000]';
        $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
        $atributos ['deshabilitado'] = true;
        $atributos ['tamanno'] = 20;
        $atributos ['maximoTamanno'] = '';
        $atributos ['anchoEtiqueta'] = 220;
        
        if (isset($_REQUEST [$esteCampo])) {
        	$atributos ['valor'] = $_REQUEST [$esteCampo];
        } else {
        	$atributos ['valor'] = '';
        }
        
        $tab ++;
        
        // Aplica atributos globales al control
        $atributos = array_merge($atributos, $atributosGlobales);
        echo $this->miFormulario->campoTextArea($atributos);
        unset($atributos);
        
        
        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
        $esteCampo = 'plazoEjecucion';
        $atributos ['id'] = $esteCampo;
        $atributos ['nombre'] = $esteCampo;
        $atributos ['tipo'] = 'text';
        $atributos ['estilo'] = 'jqueryui';
        $atributos ['marco'] = true;
        $atributos ['estiloMarco'] = '';
        $atributos ["etiquetaObligatorio"] = true;
        $atributos ['alto'] = 100;
        $atributos ['columnas'] = 120;
        $atributos ['filas'] = 8;
        $atributos ['dobleLinea'] = 0;
        $atributos ['tabIndex'] = $tab;
        $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
        $atributos ['validar'] = 'required,minSize[15], maxSize[5000]';
        $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
        $atributos ['deshabilitado'] = true;
        $atributos ['tamanno'] = 20;
        $atributos ['maximoTamanno'] = '';
        $atributos ['anchoEtiqueta'] = 220;
        
        if (isset($_REQUEST [$esteCampo])) {
        	$atributos ['valor'] = $_REQUEST [$esteCampo];
        } else {
        	$atributos ['valor'] = '';
        }
        
        $tab ++;
        
        // Aplica atributos globales al control
        $atributos = array_merge($atributos, $atributosGlobales);
        echo $this->miFormulario->campoTextArea($atributos);
        unset($atributos);
        

        echo $this->miFormulario->marcoAgrupacion('fin');


        $esteCampo = "marcoPlan";
        $atributos ['id'] = $esteCampo;
        $atributos ["estilo"] = "jqueryui";
        $atributos ['tipoEtiqueta'] = 'inicio';
        $atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo);
        echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);
        // ----------------INICIO CONTROL: DOCUMENTO--------------------------------------------------------
        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
        $esteCampo = 'planAccion';
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
        $atributos ['validar'] = 'required,minSize[30]';
        $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
        $atributos ['deshabilitado'] = true;
        $atributos ['tamanno'] = 20;
        $atributos ['maximoTamanno'] = '';
        $atributos ['anchoEtiqueta'] = 220;

        if (isset($_REQUEST [$esteCampo])) {
            $atributos ['valor'] = $_REQUEST [$esteCampo];
        } else {
            $atributos ['valor'] = '';
        }

        $tab ++;

        // Aplica atributos globales al control
        $atributos = array_merge($atributos, $atributosGlobales);
        echo $this->miFormulario->campoTextArea($atributos);
        unset($atributos);

        echo $this->miFormulario->marcoAgrupacion('fin');


        
        $cadena_sql = $this->miSql->getCadenaSql("buscarDetalleItemsCast", $datosSolicitudNecesidad);
        $resultadoItemsCast = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        
        $atributos ['cadena_sql'] = $cadenaSql = $this->miSql->getCadenaSql('adendasModificacion', $resultadoItemsCast[0][0]);
        $resultadoAdendas = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
        
        $atributos ['cadena_sql'] = $cadenaSql = $this->miSql->getCadenaSql('adendasModificacionSolCast', $resultadoItemsCast[0][0]);
        $resultadoAdendasSolCast = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
        
        ?>
        		
        		<div align="center">
        			<div class="adendas">
        		<?php  
        		
               	if($resultadoAdendas && $resultadoAdendasSolCast){
               		
               		
               		$atributos ['cadena_sql'] = $cadenaSql = $this->miSql->getCadenaSql('adendasModificacionSolCastArray', $resultadoItemsCast[0][0]);
               		$resultadoAdendasSolCastArray = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
               		
               		$esteCampo = "marcoDescripcionProductoAdenda";
               		$atributos ['id'] = $esteCampo;
               		$atributos ["estilo"] = "jqueryui";
               		$atributos ['tipoEtiqueta'] = 'inicio';
               		$atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo);
               		echo $this->miFormulario->marcoAgrupacion('inicio', $atributos); 
               		
               		
        				?>    
        				
        				
        			<div class="bwl_acc_container" id="accordion">
        				    <div class="accordion_search_container">
        				        <input type="text" class="accordion_search_input_box search_icon" value="" placeholder="Search ..."/>
        				        </div> <!-- end .bwl_acc_container -->
        					<div class="search_result_container"></div> <!-- end .search_result_container -->	   		
               		
               		
               		<section>
        			    <h2 class="acc_title_bar"><a href="#">Información Inicial</a></h2>
        			    <div class="acc_container">
        			        <div class="block">
               		
               	
        						  
        						  <?php 
        						  
        						  $tipo = 'information';
        						  $mensaje = "<b>INFORMACIÓN COTIZACIÓN # (" . $resultadoNecesidadRelacionada[0]['numero_solicitud'] . ")</b><br>
        							<br>
        							A continuación se presenta la información que se registró inicialmente en la Cotización sobre los 
                                    Productos y/o Servicios antes de realizar los cambios aprobados
        						  
        							";
        						  // ---------------- SECCION: Controles del Formulario -----------------------------------------------
        						  $esteCampo = 'mensaje';
        						  $atributos["id"] = $esteCampo; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
        						  $atributos["etiqueta"] = "";
        						  $atributos["estilo"] = "centrar";
        						  $atributos["tipo"] = $tipo;
        						  $atributos["mensaje"] = $mensaje;
        						  echo $this->miFormulario->cuadroMensaje($atributos);
        						  unset($atributos);
        						  
        						  $atributos ['cadena_sql'] = $cadenaSql = $this->miSql->getCadenaSql('adendasModificacionValuesBase', $resultadoItemsCast[0][0]);
        						  $resultadoAdendasValues = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
        						  
        						  $contReg = 0;
        						  
        						  ?>
        						  
        						  
        						  
        						  
        						  
        						  
        						   <table id="tablaFP2" class="table1" width="100%" >
        	       				                <!-- Cabecera de la tabla -->
        	       				                <thead>
        	       				                    <tr>
        	       				                        <th width="5%" >Número</th>
        	       				                        <th width="10%" >Nombre</th>
        	       				                        <th width="20%" >Descripción</th>
        	       				                        <th width="10%" >Tipo</th>
        	       				                        <th width="10%" >Unidad</th>
        	       				                        <th width="5%" >Cantidad</th>
        	       				                    </tr>
        	       				                </thead>
        	       				
        	       				                <!-- Cuerpo de la tabla con los campos -->
        	       				                <tbody>
        	       				
        	       				
        	       				
        	       				                    <?php
        	       				
        	       				                        while($contReg < count($resultadoAdendasValues)){
                                                                                
                                                            $id_padre=$resultadoAdendasValues[$contReg]['item_cotizacion_padre'];
        	       				                        	
        	       				                        	$jsonReg = $resultadoAdendasValues[$contReg]['registro_anterior'];
        	       				                        	 
        	       				                        	$valoresJson = json_decode($jsonReg, true);
        
        	       				                        	$resultadoItemsJson = $valoresJson[0];
        	       				                        	
        	       				                        	
        	       				                            if (intval($resultadoItemsJson[5]) === 0) {
        	       				                                $ejecucion = '0 - NO APLICA';
        	       				                            } else {
        	       				
        	       											
        	       				
        	       				                                $nyears = intval(intval($resultadoItemsJson[5]) / 360);
        	       				                                $nmonths = intval((intval($resultadoItemsJson[5]) - intval(intval($resultadoItemsJson[5]) / 360) * 360) / 30);
        	       				                                $ndays = intval(intval($resultadoItemsJson[5]) - (intval(intval($resultadoItemsJson[5]) / 360) * 360 + intval((intval($resultadoItemsJson[5]) - intval(intval($resultadoItemsJson[5]) / 360) * 360) / 30) * 30));
        	       				
        	       				                                $ejecucion = $nyears . " AÑO(S) - " . $nmonths . " MES(ES) - " . $ndays . " DÍA(S)";
        	       				                            }
        	       										
        	       											
        	       				
        	       				                            $atributos ['cadena_sql'] = $cadenaSql = $this->miSql->getCadenaSql('tipoNecesidadAdministrativa3', $resultadoItemsJson[3]);
        	       				                            $matrizItemsTipoItem = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
        	       				
        	       				                            $atributos ['cadena_sql'] = $cadenaSql = $this->miSql->getCadenaSql('unidadUdistrital2', $resultadoItemsJson[4]);
        	       				                            $matrizItemsUnidad = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
        	       				
        	       				                            if ($resultadoItemsJson[4] === '0') {
        	       				
        	       				                                $matrizItemsUnidad[0][0] = 0;
        	       				                                $matrizItemsUnidad[0][1] = 'NO APLICA';
        	       				                            }
        	       				
        	       				
        	       				                            if ($resultadoItemsJson[6] - intval($resultadoItemsJson[6])) { // Sobra 0.24 por lo que devuelve verdadero
        	       				                                $numero_cantidad = number_format($resultadoItemsJson[6], 2, ',', '.');
        	       				                            } else {
        	       				                                $numero_cantidad = intval($resultadoItemsJson[6]);
        	       				                            }
        	       				                            ?>
        	       				
        	       				
        	       				                            <tr id="nFilas" >
        	       				                                <td><?php echo "SP".$resultadoItemsJson['id'] ?></td>
        	       				                                <td><?php echo $resultadoItemsJson[1] ?></td>
        	       				                                <td><?php echo $resultadoItemsJson[2] ?></td>
        	       				                                <td><?php echo $matrizItemsTipoItem[0][0] . " - " . $matrizItemsTipoItem[0][1] ?></td>
        	       				                                <td><?php echo $matrizItemsUnidad[0][0] . " - " . $matrizItemsUnidad[0][1] ?></td>
        	       				                                <td><?php echo $numero_cantidad ?></td>
        	       				                            </tr>
        	       				
        	       				
        	       				                    		<?php
        	       				                    		
        	       				                        	
        	       				                    		$contReg++;
        	       				                		}
        	       				            
        	       				            ?>
        	       				                </tbody>
        	       				            </table>
        	       				            <!-- Botón para agregar filas -->
        	       				            <!-- 
        	       				            <input type="button" id="agregar" value="Agregar fila" /> -->
        	       				
        	       				
        	       				
        	       				
        						  
        						  
        						  
        						  
        						  
        						  
        						  
        						  </div>
        					</div>
        				</section>
        				  
               		
               			<?php
               			$controlAd = 0;
               			
               			while($controlAd < $resultadoAdendasSolCast[0]['count']){
               				
        		       		?>
        		       		
        		       	<section>
        				    <h2 class="acc_title_bar"><a href="#">Adenda Cotización <?php echo $controlAd + 1?></a></h2>
        				    <div class="acc_container">
        				        <div class="block">	
        		   
        		       		
        		       		<?php 
               				
               				
        	       			$esteCampo = "marcoDetalleProAdenda";
        	       			$atributos ['id'] = $esteCampo;
        	       			$atributos ["estilo"] = "jqueryui";
        	       			$atributos ['tipoEtiqueta'] = 'inicio';
        	       			$atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo).($controlAd + 1) ;
        	       			echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);
        	       		
        	       				
        	       				$atributos ['cadena_sql'] = $cadenaSql = $this->miSql->getCadenaSql('adendasModificacionValues', $resultadoAdendasSolCastArray[$controlAd][0]);
        	       				$resultadoAdendasValues = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
        	       				
        	       				
        	       				$atributos ['cadena_sql'] = $cadenaSql = $this->miSql->getCadenaSql('adendasModificacionValuesEst', $resultadoAdendasSolCastArray[$controlAd][0]);
        	       				$resultadoAdendasValuesEst = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
        	       				
        	       				$titulo = "SMC-" . sprintf("%06d", $resultadoAdendasValuesEst[0]['solicitud_modificacion_cotizacion']);
        	       				
        	       				if($resultadoAdendasValuesEst[0]['estado_solicitud'] == 1){
        	       					
        	       					$titulo2 = $resultadoAdendasValuesEst[0]['fecha_estado'];
        	       					$titulo3 = $resultadoAdendasValuesEst[0]['justificacion'];
        	       					
        	       					$titulo4 = $resultadoAdendasValuesEst[1]['fecha_estado'];
        	       					$titulo5 = $resultadoAdendasValuesEst[1]['justificacion'];
        	       					
        	       				}else{
        	       					
        	       					$titulo2 = $resultadoAdendasValuesEst[1]['fecha_estado'];
        	       					$titulo3 = $resultadoAdendasValuesEst[1]['justificacion'];
        	       					
        	       					$titulo4 = $resultadoAdendasValuesEst[0]['fecha_estado'];
        	       					$titulo5 = $resultadoAdendasValuesEst[0]['justificacion'];
        	       					
        	       				}
        	       				
        	       				$titulo5 = str_replace ( "<p>" , "" , $titulo5 );
        	       				$titulo5 = str_replace ( "</p>" , "" , $titulo5 );
        	       				
        	       				$tipo = 'warning';
        	       				$mensaje = "<b>DETALLE SOLICITUD DE MODIFICACIÓN (".$titulo.")</b><br>
        							<br>
        							<i>A continuación se presenta la información de justificación de los cambios realizados, en la parte inferior se presenta una tabla con los valores
        	       						del cambio</i>.<br>
        	       						<br>
        	       						<b>FECHA SOLICITUD DE MODIFICACIÓN:</b> ".$titulo2." <br>
        	       						<b>JUSTIFICACIÓN DE SOLICITUD:</b> ".$titulo3." <br> 
        	       						<b>FECHA APROBACIÓN DE MODIFICACIÓN:</b> ".$titulo4." <br>
        	       						<b>JUSTIFICACIÓN DE APROBACIÓN:</b> ".$titulo5." <br>
        	       						<br>
        	       						<center>
        	       						La información que se presenta a continuación es la que ya tuvo modificaciones y su aprobación correspondiente.</center>
        	       						
        							";
        	       				// ---------------- SECCION: Controles del Formulario -----------------------------------------------
        	       				$esteCampo = 'mensaje';
        	       				$atributos["id"] = $esteCampo; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
        	       				$atributos["etiqueta"] = "";
        	       				$atributos["estilo"] = "centrar";
        	       				$atributos["tipo"] = $tipo;
        	       				$atributos["mensaje"] = $mensaje;
        	       				echo $this->miFormulario->cuadroMensaje($atributos);
        	       				unset($atributos);
        	       				
        	       				$contReg = 0;
        	       				
        	       				unset($atributos);
        	       				?>
        	       				
        	       				
        	       				            <table id="tablaFP2" class="table1" width="100%" >
        	       				                <!-- Cabecera de la tabla -->
        	       				                <thead>
        	       				                    <tr>
        	       				                        <th width="5%" >Número</th>
        	       				                        <th width="10%" >Nombre</th>
        	       				                        <th width="20%" >Descripción</th>
        	       				                        <th width="10%" >Tipo</th>
        	       				                        <th width="10%" >Unidad</th>
        	       				                        <th width="5%" >Cantidad</th>
                                                                        <th width="20%" >Justificación</th>
        	       				                    </tr>
        	       				                </thead>
        	       				
        	       				                <!-- Cuerpo de la tabla con los campos -->
        	       				                <tbody>
        	       				
        	       				
        	       				
        	       				                    <?php
        	       				
        	       				                        while($contReg < count($resultadoAdendasValues)){
                                                                                
                                                            $id_padre=$resultadoAdendasValues[$contReg]['item_cotizacion_padre'];
                                                                                
                                                            $atributos ['cadena_sql'] = $cadenaSql = $this->miSql->getCadenaSql('justificacionAdendas', $id_padre);
                                                            $justificacion_item = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
        	       				                        	
        	       				                        	$jsonReg = $resultadoAdendasValues[$contReg]['registro_nuevo'];
        	       				                        	
        	       				                        	$valoresJson = json_decode($jsonReg, true);
        	       				                        	 
        	       				                        	$resultadoItemsJson = $valoresJson;
        	       				                        	
        	       				                        	
        	       				                            if (intval($resultadoItemsJson['tiempo_ejecucion']) === 0) {
        	       				                                $ejecucion = '0 - NO APLICA';
        	       				                            } else {
        	       				
        	       											
        	       				
        	       				                                $nyears = intval(intval($resultadoItemsJson['tiempo_ejecucion']) / 360);
        	       				                                $nmonths = intval((intval($resultadoItemsJson['tiempo_ejecucion']) - intval(intval($resultadoItemsJson['tiempo_ejecucion']) / 360) * 360) / 30);
        	       				                                $ndays = intval(intval($resultadoItemsJson['tiempo_ejecucion']) - (intval(intval($resultadoItemsJson['tiempo_ejecucion']) / 360) * 360 + intval((intval($resultadoItemsJson['tiempo_ejecucion']) - intval(intval($resultadoItemsJson['tiempo_ejecucion']) / 360) * 360) / 30) * 30));
        	       				
        	       				                                $ejecucion = $nyears . " AÑO(S) - " . $nmonths . " MES(ES) - " . $ndays . " DÍA(S)";
        	       				                            }
        	       										
        	       											
        	       				
        	       				                            $atributos ['cadena_sql'] = $cadenaSql = $this->miSql->getCadenaSql('tipoNecesidadAdministrativa3', $resultadoItemsJson['tipo_necesidad']);
        	       				                            $matrizItemsTipoItem = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
        	       				
        	       				                            $atributos ['cadena_sql'] = $cadenaSql = $this->miSql->getCadenaSql('unidadUdistrital2', $resultadoItemsJson['unidad']);
        	       				                            $matrizItemsUnidad = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
        	       				
        	       				                            if ($resultadoItemsJson['unidad'] === '0') {
        	       				
        	       				                                $matrizItemsUnidad[0][0] = 0;
        	       				                                $matrizItemsUnidad[0][1] = 'NO APLICA';
        	       				                            }
        	       				
        	       				
        	       				                            if ($resultadoItemsJson['cantidad'] - intval($resultadoItemsJson['cantidad'])) { // Sobra 0.24 por lo que devuelve verdadero
        	       				                                $numero_cantidad = number_format($resultadoItemsJson['cantidad'], 2, ',', '.');
        	       				                            } else {
        	       				                                $numero_cantidad = intval($resultadoItemsJson['cantidad']);
        	       				                            }
        	       				                            ?>
        	       				
        	       				
        	       				                            <tr id="nFilas" >
        	       				                                <td><?php echo "SP".$resultadoItemsJson['id'] ?></td>
        	       				                                <td><?php echo $resultadoItemsJson['nombre'] ?></td>
        	       				                                <td><?php echo $resultadoItemsJson['descripcion'] ?></td>
        	       				                                <td><?php echo $matrizItemsTipoItem[0][0] . " - " . $matrizItemsTipoItem[0][1] ?></td>
        	       				                                <td><?php echo $matrizItemsUnidad[0][0] . " - " . $matrizItemsUnidad[0][1] ?></td>
        	       				                                <td><?php echo $numero_cantidad ?></td>
                                                                                <td><?php echo $justificacion_item[0]['justificacion'] ?></td>
        	       				                            </tr>
        	       				
        	       				
        	       				                    		<?php
        	       				                    		
        	       				                        	
        	       				                    		$contReg++;
        	       				                		}
        	       				            
        	       				            ?>
        	       				                </tbody>
        	       				            </table>
        	       				            <!-- Botón para agregar filas -->
        	       				            <!-- 
        	       				            <input type="button" id="agregar" value="Agregar fila" /> -->
        	       				
        	       				
        	       				
        	       				
        	       				
        	       				            <?php
        	       			 
        	       		
        	       			echo $this->miFormulario->marcoAgrupacion('fin');
        	       			
        	       			$controlAd++;
        	       			
        	       			?>
        	       					       		
        
        	       				</div>
        	       			
        	       			</div>
        				</section>
        	       					       		
        	       	        <?php 
               		
               			}
               			
               			
               		
               			?>
               			       		
               			       		</div>
               							  
               			       		
               			       			<?php
               			
               			       			
               			       			$tipo = 'information';
               			       			$mensaje = "<b>IMPORTANTE</b><br>
        							<br>
        							Tenga en cuenta que la información que se presentan en la sección Información Productos o Servicios es la actual. Esta es la que debe tenerse en cuenta para responder y/o atender la cotización.
               			       			
        							";
               			       			
               			       			// ---------------- SECCION: Controles del Formulario -----------------------------------------------
               			       			$esteCampo = 'mensaje';
               			       			$atributos["id"] = $esteCampo; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
               			       			$atributos["etiqueta"] = "";
               			       			$atributos["estilo"] = "centrar";
               			       			$atributos["tipo"] = $tipo;
               			       			$atributos["mensaje"] = $mensaje;
               			       			echo $this->miFormulario->cuadroMensaje($atributos);
               			       			unset($atributos);
               			       			
               			
               		echo $this->miFormulario->marcoAgrupacion('fin');
               		
               	}
               	
               	?>
               		</div>
               	</div>
               	
               			
              <?php
        
        
        
        $esteCampo = "marcoDescripcionProducto";
        $atributos ['id'] = $esteCampo;
        $atributos ["estilo"] = "jqueryui";
        $atributos ['tipoEtiqueta'] = 'inicio';
        $atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo);
        echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);
        {
            $esteCampo = "marcoDetallePro";
            $atributos ['id'] = $esteCampo;
            $atributos ["estilo"] = "jqueryui";
            $atributos ['tipoEtiqueta'] = 'inicio';
            $atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo);
            echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);


            unset($atributos);
            ?>


            <table id="tablaFP2" class="table1" width="100%" >
                <!-- Cabecera de la tabla -->
                <thead>
                    <tr>
                        <th width="5%" >Número</th>
                        <th width="10%" >Nombre</th>
                        <th width="30%" >Descripción</th>
                        <th width="15%" >Tipo</th>
                        <th width="15%" >Unidad</th>
                        <th width="5%" >Cantidad</th>
                    </tr>
                </thead>

                <!-- Cuerpo de la tabla con los campos -->
                <tbody>



            <?php
            if (isset($resultadoItems) && $resultadoItems) {
                $count = count($resultadoItems);
                $i = 0;

                while ($i < $count) {



                    if (intval($resultadoItems[$i][5]) === 0) {
                        $ejecucion = '0 - NO APLICA';
                    } else {



                        $nyears = intval(intval($resultadoItems[$i][5]) / 360);
                        $nmonths = intval((intval($resultadoItems[$i][5]) - intval(intval($resultadoItems[$i][5]) / 360) * 360) / 30);
                        $ndays = intval(intval($resultadoItems[$i][5]) - (intval(intval($resultadoItems[$i][5]) / 360) * 360 + intval((intval($resultadoItems[$i][5]) - intval(intval($resultadoItems[$i][5]) / 360) * 360) / 30) * 30));

                        $ejecucion = $nyears . " AÑO(S) - " . $nmonths . " MES(ES) - " . $ndays . " DÍA(S)";
                    }



                    $atributos ['cadena_sql'] = $cadenaSql = $this->miSql->getCadenaSql('tipoNecesidadAdministrativa3', $resultadoItems[$i][3]);
                    $matrizItemsTipoItem = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");

                    $atributos ['cadena_sql'] = $cadenaSql = $this->miSql->getCadenaSql('unidadUdistrital2', $resultadoItems[$i][4]);
                    $matrizItemsUnidad = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");

                    if ($resultadoItems[$i][4] === '0') {

                        $matrizItemsUnidad[0][0] = 0;
                        $matrizItemsUnidad[0][1] = 'NO APLICA';
                    }


                    if ($resultadoItems[$i][6] - intval($resultadoItems[$i][6])) { // Sobra 0.24 por lo que devuelve verdadero
                        $numero_cantidad = number_format($resultadoItems[$i][6], 2, ',', '.');
                    } else {
                        $numero_cantidad = intval($resultadoItems[$i][6]);
                    }
                    ?>


                            <tr id="nFilas" >
                                <td><?php echo $i + 1 ?></td>
                                <td><?php echo $resultadoItems[$i][1] ?></td>
                                <td><?php echo $resultadoItems[$i][2] ?></td>
                                <td><?php echo $matrizItemsTipoItem[0][0] . " - " . $matrizItemsTipoItem[0][1] ?></td>
                                <td><?php echo $matrizItemsUnidad[0][0] . " - " . $matrizItemsUnidad[0][1] ?></td>
                                <td><?php echo $numero_cantidad ?></td>
                            </tr>


                    <?php
                    $i++;
                }
            }
            ?>
                </tbody>
            </table>
            <!-- Botón para agregar filas -->
            <!-- 
            <input type="button" id="agregar" value="Agregar fila" /> -->





            <?php
            unset($atributos);
            $esteCampo = 'idsItems';
            $atributos ["id"] = $esteCampo; // No cambiar este nombre
            $atributos ["tipo"] = "hidden";
            $atributos ['estilo'] = '';
            $atributos ["obligatorio"] = false;
            $atributos ['marco'] = false;
            $atributos ["etiqueta"] = "";
            if (isset($_REQUEST [$esteCampo])) {
                $atributos ['valor'] = $_REQUEST [$esteCampo];
            } else {
                $atributos ['valor'] = '';
            }
            $atributos ['validar'] = '';
            $atributos = array_merge($atributos, $atributosGlobales);
            echo $this->miFormulario->campoCuadroTexto($atributos);
            unset($atributos);

            $esteCampo = 'countItems';
            $atributos ["id"] = $esteCampo; // No cambiar este nombre
            $atributos ["tipo"] = "hidden";
            $atributos ['estilo'] = '';
            $atributos ["obligatorio"] = false;
            $atributos ['marco'] = false;
            $atributos ["etiqueta"] = "";
            if (isset($_REQUEST [$esteCampo])) {
                $atributos ['valor'] = $_REQUEST [$esteCampo];
            } else {
                $atributos ['valor'] = '';
            }
            $atributos ['validar'] = '';
            $atributos = array_merge($atributos, $atributosGlobales);
            echo $this->miFormulario->campoCuadroTexto($atributos);
            unset($atributos);

            $esteCampo = 'permisoItem';
            $atributos ["id"] = $esteCampo; // No cambiar este nombre
            $atributos ["tipo"] = "hidden";
            $atributos ['estilo'] = '';
            $atributos ["obligatorio"] = false;
            $atributos ['marco'] = false;
            $atributos ["etiqueta"] = "";

            $atributos ['valor'] = $permiso;

            $atributos ['validar'] = '';
            $atributos = array_merge($atributos, $atributosGlobales);
            echo $this->miFormulario->campoCuadroTexto($atributos);
            unset($atributos);

            echo $this->miFormulario->marcoAgrupacion('fin');
        }

        echo $this->miFormulario->marcoAgrupacion('fin');



        $esteCampo = "marcoTipoContrato";
        $atributos ['id'] = $esteCampo;
        $atributos ["estilo"] = "jqueryui";
        $atributos ['tipoEtiqueta'] = 'inicio';
        $atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo);
        echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);
        // ----------------INICIO CONTROL: DOCUMENTO--------------------------------------------------------
        // ---------------- CONTROL: Lista clase CIIU--------------------------------------------------------
        $esteCampo = "tipoContrato";
        $atributos ['nombre'] = $esteCampo;
        $atributos ['id'] = $esteCampo;
        $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
        $atributos ["etiquetaObligatorio"] = true;
        $atributos ['tab'] = $tab ++;
        $atributos ['anchoEtiqueta'] = 200;
        $atributos ['evento'] = '';
        if (isset($_REQUEST [$esteCampo])) {
            $atributos ['seleccion'] = $_REQUEST [$esteCampo];
        } else {
            $atributos ['seleccion'] = - 1;
        }
        $atributos ['deshabilitado'] = true;
        $atributos ['columnas'] = 1;
        $atributos ['tamanno'] = 1;
        $atributos ['ajax_function'] = "";
        $atributos ['ajax_control'] = $esteCampo;
        $atributos ['estilo'] = "jqueryui";
        $atributos ['validar'] = "required";
        $atributos ['limitar'] = false;
        $atributos ['anchoCaja'] = 60;
        $atributos ['miEvento'] = '';
        $atributos ['cadena_sql'] = $cadenaSql = $this->miSql->getCadenaSql('argoTipoContratoUdistrital');
        $matrizItems = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
        $atributos ['matrizItems'] = $matrizItems;
        $atributos = array_merge($atributos, $atributosGlobales);
        echo $this->miFormulario->campoCuadroLista($atributos);
        unset($atributos);
        // ----------------FIN CONTROL: Lista clase CIIU--------------------------------------------------------

        echo $this->miFormulario->marcoAgrupacion('fin');



//         $esteCampo = "marcoSeleccion";
//         $atributos ['id'] = $esteCampo;
//         $atributos ["estilo"] = "jqueryui";
//         $atributos ['tipoEtiqueta'] = 'inicio';
//         $atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo);
//         echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);
//         // ----------------INICIO CONTROL: DOCUMENTO--------------------------------------------------------
//         // ---------------- CONTROL: Lista clase CIIU--------------------------------------------------------
//         $esteCampo = "formaSeleccion";
//         $atributos ['nombre'] = $esteCampo;
//         $atributos ['id'] = $esteCampo;
//         $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
//         $atributos ["etiquetaObligatorio"] = true;
//         $atributos ['tab'] = $tab ++;
//         $atributos ['anchoEtiqueta'] = 200;
//         $atributos ['evento'] = '';
//         if (isset($_REQUEST [$esteCampo])) {
//             $atributos ['seleccion'] = $_REQUEST [$esteCampo];
//         } else {
//             $atributos ['seleccion'] = - 1;
//         }
//         $atributos ['deshabilitado'] = true;
//         $atributos ['columnas'] = 1;
//         $atributos ['tamanno'] = 1;
//         $atributos ['ajax_function'] = "";
//         $atributos ['ajax_control'] = $esteCampo;
//         $atributos ['estilo'] = "jqueryui";
//         $atributos ['validar'] = "required";
//         $atributos ['limitar'] = false;
//         $atributos ['anchoCaja'] = 60;
//         $atributos ['miEvento'] = '';
//         $atributos ['cadena_sql'] = $cadenaSql = $this->miSql->getCadenaSql('formaSeleccionUdistrital');
//         $matrizItems = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
//         $atributos ['matrizItems'] = $matrizItems;
//         $atributos = array_merge($atributos, $atributosGlobales);
//         echo $this->miFormulario->campoCuadroLista($atributos);
//         unset($atributos);
//         // ----------------FIN CONTROL: Lista clase CIIU--------------------------------------------------------




//         echo $this->miFormulario->marcoAgrupacion('fin');



        $esteCampo = "marcoPago";
        $atributos ['id'] = $esteCampo;
        $atributos ["estilo"] = "jqueryui";
        $atributos ['tipoEtiqueta'] = 'inicio';
        $atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo);
        echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);
        // ----------------INICIO CONTROL: DOCUMENTO--------------------------------------------------------
        // ---------------- CONTROL: Lista clase CIIU--------------------------------------------------------
        $esteCampo = "medioPago";
        $atributos ['nombre'] = $esteCampo;
        $atributos ['id'] = $esteCampo;
        $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
        $atributos ["etiquetaObligatorio"] = true;
        $atributos ['tab'] = $tab ++;
        $atributos ['anchoEtiqueta'] = 200;
        $atributos ['evento'] = '';
        if (isset($resultadoNecesidadRelacionada[0]['tipo_necesidad'])) {
            $atributos ['seleccion'] = $resultadoNecesidadRelacionada[0]['medio_pago'];
        } else {
            $atributos ['seleccion'] = - 1;
        }
        $atributos ['deshabilitado'] = true;
        $atributos ['columnas'] = 1;
        $atributos ['tamanno'] = 1;
        $atributos ['ajax_function'] = "";
        $atributos ['ajax_control'] = $esteCampo;
        $atributos ['estilo'] = "jqueryui";
        $atributos ['validar'] = "required";
        $atributos ['limitar'] = false;
        $atributos ['anchoCaja'] = 60;
        $atributos ['miEvento'] = '';
        $atributos ['cadena_sql'] = $cadenaSql = $this->miSql->getCadenaSql('medioPagoUdistrital');
        $matrizItems = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
        $atributos ['matrizItems'] = $matrizItems;
        $atributos = array_merge($atributos, $atributosGlobales);
        echo $this->miFormulario->campoCuadroLista($atributos);
        unset($atributos);
        // ----------------FIN CONTROL: Lista clase CIIU--------------------------------------------------------


        $esteCampo = "marcoFormaPago";
        $atributos ['id'] = $esteCampo;
        $atributos ["estilo"] = "jqueryui";
        $atributos ['tipoEtiqueta'] = 'inicio';
        $atributos ["leyenda"] = "Detalle Forma de Pago";
        echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);


        unset($atributos);
        $esteCampo = 'countParam';
        $atributos ["id"] = $esteCampo; // No cambiar este nombre
        $atributos ["tipo"] = "text";
        $atributos ['estilo'] = 'jquery';
        $atributos ['columnas'] = 2;
        $atributos ["obligatorio"] = false;
        $atributos ['marco'] = false;
        $atributos ["etiqueta"] = "";

        $countLimit = count($resultadoFP);

        $textLimit = '( ' . $countLimit . ' ) Parámetro(s) Agregado(s)' . ' - ( Configurado el 100% )';

        $atributos ['valor'] = $textLimit;

        $atributos ['validar'] = 'required';
        $atributos ['deshabilitado'] = true;
        $atributos ['tamanno'] = '70%';
        $atributos = array_merge($atributos, $atributosGlobales);
        echo $this->miFormulario->campoCuadroTexto($atributos);
        unset($atributos);
        ?>


        <table id="tablaFP" class="table1" width="100%" >
            <!-- Cabecera de la tabla -->
            <thead>
                <tr>
                    <th width="25%" >Tipo</th>
                    <th width="30%" >Condición de Avance</th>
                    <th width="40%" >Porcentaje (%) de Pago</th>
                    <th width="5%" >&nbsp;</th>
                </tr>
            </thead>

            <!-- Cuerpo de la tabla con los campos -->
            <tbody>

                <!-- fila base para clonar y agregar al final -->
                <!-- fin de código: fila base -->



        <?php
        if (isset($resultadoFP) && $resultadoFP) {
            $count = count($resultadoFP);
            $i = 0;

            while ($i < $count) {
            
            	if ($resultadoFP[$i][0] == 1) {
            		$tipoValor = " % Completado";
            		$valueTip = round($resultadoFP[$i][2], 2);
            	} else if ($resultadoFP[$i][0] == 2) {
            		$tipoValor = "";
            		$valueTip = $resultadoFP[$i][2];
            	} else {
            		$tipoValor = " - Sin Condición de Avance";
            		$valueTip = round($resultadoFP[$i][2], 2);
            	}
            	?>
                                    <tr id="nFilas" >
                                        <td><?php echo $resultadoFP[$i][0] . " - " . $resultadoFP[$i][1] ?></td>
                                        <td><?php echo $valueTip . $tipoValor ?></td>
                                        <td><?php echo round($resultadoFP[$i][3], 2) . " %" ?></td>
                                        <th><?php echo $i + 1 ?></th>
                                    </tr>
                                    <?php
                                    $i++;
                                }
                }
                ?>

            </tbody>
        </table>
        <!-- Botón para agregar filas -->
        <!-- 
        <input type="button" id="agregar" value="Agregar fila" /> -->





                <?php
                echo $this->miFormulario->marcoAgrupacion('fin');


                unset($atributos);
                $esteCampo = 'idsFormaPago';
                $atributos ["id"] = $esteCampo; // No cambiar este nombre
                $atributos ["tipo"] = "hidden";
                $atributos ['estilo'] = '';
                $atributos ["obligatorio"] = false;
                $atributos ['marco'] = false;
                $atributos ["etiqueta"] = "";
                if (isset($_REQUEST [$esteCampo])) {
                    $atributos ['valor'] = $_REQUEST [$esteCampo];
                } else {
                    $atributos ['valor'] = '';
                }
                $atributos ['validar'] = '';
                $atributos = array_merge($atributos, $atributosGlobales);
                echo $this->miFormulario->campoCuadroTexto($atributos);
                unset($atributos);

                unset($atributos);
                $esteCampo = 'changeFormaPago';
                $atributos ["id"] = $esteCampo; // No cambiar este nombre
                $atributos ["tipo"] = "hidden";
                $atributos ['estilo'] = '';
                $atributos ["obligatorio"] = false;
                $atributos ['marco'] = false;
                $atributos ["etiqueta"] = "";
                $atributos ['valor'] = 'false';
                $atributos ['validar'] = '';
                $atributos = array_merge($atributos, $atributosGlobales);
                echo $this->miFormulario->campoCuadroTexto($atributos);
                unset($atributos);

                echo $this->miFormulario->marcoAgrupacion('fin');













                //**************************************** RELACION DATOS AGORA **********************************************************************************************
                //************************************************************************************************************************************************************





                if (isset($resultadoNecesidadRelacionada[0]['tipo_necesidad'])) {//CAST tipo de NECESIDAD
                    switch ($resultadoNecesidadRelacionada[0]['tipo_necesidad']) {
                        case 1 :
                            $resultadoNecesidadRelacionada[0]['tipo_necesidad'] = 1;
                            $service = false;
                            break;
                        case 2 :
                            $resultadoNecesidadRelacionada[0]['tipo_necesidad'] = 2;
                            $service = true;
                            break;
                        case 3 :
                            $resultadoNecesidadRelacionada [0] ['tipo_necesidad'] = 3;
                            $service = true;
                            break;
                    }
                }



                $cadenaSql = $this->miSql->getCadenaSql('consultarActividadesImp', $_REQUEST['idSolicitud']);
                $resultadoActividades = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");

                if ($resultadoActividades) {

                    $esteCampo = "marcoActividadesRel";
                    $atributos ['id'] = $esteCampo;
                    $atributos ["estilo"] = "jqueryui";
                    $atributos ['tipoEtiqueta'] = 'inicio';
                    $atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo) . " (CIIU)";
                    echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);

                    foreach ($resultadoActividades as $dato):
                        echo "<span class='textoElegante textoEnorme textoAzul'>+ </span><b>";
                        echo $dato['subclase'] . ' - ' . $dato['nombre'] . "</b><br>";
                    endforeach;

                    echo $this->miFormulario->marcoAgrupacion('fin');
                }

                if ($service) {

                    $cadenaSql = $this->miSql->getCadenaSql('consultarNBCImp', $_REQUEST['idSolicitud']);
                    $resultadoNBC = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");

                    $esteCampo = "marcoNBCRel";
                    $atributos ['id'] = $esteCampo;
                    $atributos ["estilo"] = "jqueryui";
                    $atributos ['tipoEtiqueta'] = 'inicio';
                    $atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo);
                    echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);


                    echo "<span class='textoElegante textoEnorme textoAzul'>+ </span><b>";
                    echo $resultadoNBC[0]['nucleo'] . ' - ' . $resultadoNBC[0]['nombre'] . "</b><br>";


                    echo $this->miFormulario->marcoAgrupacion('fin');
                }
                



                $esteCampo = "marcoAnexoSop";
                $atributos ['id'] = $esteCampo;
                $atributos ["estilo"] = "jqueryui";
                $atributos ['tipoEtiqueta'] = 'inicio';
                $atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo);
                echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);
                // ----------------INICIO CONTROL: DOCUMENTO--------------------------------------------------------

                $atributos["id"] = "botones";
                $atributos["estilo"] = "marcoBotones widget";
                echo $this->miFormulario->division("inicio", $atributos);

                $rutaFilesCotizacion = $this->miConfigurador->getVariableConfiguracion ( "rutaFilesSolicitante" );

                $cadenaSql = $this->miSql->getCadenaSql ( 'consultarEspTec', $datosSolicitudNecesidad );
                $resultadoAnexo= $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );

                if($resultadoAnexo[0]['anexo_tecnico'] != null){
                    $_REQUEST['cotizacionSoporteEspTec'] =  $resultadoAnexo[0]['anexo_tecnico'];
                }else{
                    $_REQUEST['cotizacionSoporteEspTec'] =  null;
                }
                
                if($_REQUEST['cotizacionSoporteEspTec'] != null){
                    $enlace = "<br><a href='" . $rutaFilesCotizacion . $_REQUEST['cotizacionSoporteEspTec'] . "' target='_blank'>";
                    $enlace.="<img src='" . $rutaBloque . "/images/pdf.png' width='35px'><br>Anexo Especificación Técnica";
                    $enlace.="</a>";
                    echo $enlace;
                }else{
                    $enlace = "<br><a href='javascript:void(0);' onClick=\"notFile()\">";
                    $enlace.="<img src='".$rutaBloque."/images/pdf.png' width='35px'><br>Anexo Especificación Técnica";
                    $enlace.="</a>";
                    echo $enlace;
                }
                
                //------------------Fin Division para los botones-------------------------
                echo $this->miFormulario->division("fin");
                //FIN enlace boton descargar RUT

                echo $this->miFormulario->marcoAgrupacion('fin');





                $esteCampo = "marcoTipoNecesidad";
                $atributos ['id'] = $esteCampo;
                $atributos ["estilo"] = "jqueryui";
                $atributos ['tipoEtiqueta'] = 'inicio';
                $atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo);
                echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);



                // ---------------- CONTROL: Lista UNIDAD --------------------------------------------------------
                $esteCampo = "tipoNecesidad";
                $atributos ['nombre'] = $esteCampo;
                $atributos ['id'] = $esteCampo;
                $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                $atributos ["etiquetaObligatorio"] = true;
                $atributos ['tab'] = $tab ++;
                $atributos ['anchoEtiqueta'] = 200;
                $atributos ['evento'] = '';

                $atributos ['seleccion'] = $resultadoNecesidadRelacionada[0]['tipo_necesidad'];

                $atributos ['deshabilitado'] = true;
                $atributos ['columnas'] = 1;
                $atributos ['tamanno'] = 1;
                $atributos ['estilo'] = "jqueryui";
                $atributos ['validar'] = "required";
                $atributos ['limitar'] = false;
                $atributos ['anchoCaja'] = 60;
                $atributos ['miEvento'] = '';

                $atributos ['cadena_sql'] = $cadenaSql = $this->miSql->getCadenaSql('tipoNecesidadAdministrativa');
                $matrizItems = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");

                $atributos ['matrizItems'] = $matrizItems;
                $atributos = array_merge($atributos, $atributosGlobales);
                echo $this->miFormulario->campoCuadroLista($atributos);
                unset($atributos);
                // ----------------FIN CONTROL: Lista UNIDAD--------------------------------------------------------


                echo $this->miFormulario->marcoAgrupacion('fin');





                // ------------------Division para los botones-------------------------
                $atributos ["id"] = "botones";
                $atributos ["estilo"] = "marcoBotones";
                echo $this->miFormulario->division("inicio", $atributos); {
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
                    $atributos ["valor"] = $this->lenguaje->getCadena($esteCampo);
                    $atributos ['nombreFormulario'] = $esteBloque ['nombre'] . "Registrar";
                    $tab ++;

                    // Aplica atributos globales al control
                    $atributos = array_merge($atributos, $atributosGlobales);
                    //echo $this->miFormulario->campoBoton ( $atributos );
                    // -----------------FIN CONTROL: Botón -----------------------------------------------------------
                }
                // ------------------Fin Division para los botones-------------------------
                echo $this->miFormulario->division("fin");


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
                $valorCodificado .= "&pagina=" . $this->miConfigurador->getVariableConfiguracion('pagina');
                $valorCodificado .= "&bloque=" . $esteBloque ['nombre'];
                $valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
                $valorCodificado .= "&opcion=registrar";
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

                $atributos ['marco'] = false;
                $atributos ['tipoEtiqueta'] = 'fin';
                echo $this->miFormulario->formulario($atributos);

                // ----------------FIN SECCION: Paso de variables -------------------------------------------------
                // ---------------- FIN SECCION: Controles del Formulario -------------------------------------------
                // ----------------FINALIZAR EL FORMULARIO ----------------------------------------------------------
                // Se debe declarar el mismo atributo de marco con que se inició el formulario.
            }

            function mensaje() {

                // Si existe algun tipo de error en el login aparece el siguiente mensaje
                $mensaje = $this->miConfigurador->getVariableConfiguracion('mostrarMensaje');
                $this->miConfigurador->setVariableConfiguracion('mostrarMensaje', null);

                if ($mensaje) {

                    $tipoMensaje = $this->miConfigurador->getVariableConfiguracion('tipoMensaje');

                    if ($tipoMensaje == 'json') {

                        $atributos ['mensaje'] = $mensaje;
                        $atributos ['json'] = true;
                    } else {
                        $atributos ['mensaje'] = $this->lenguaje->getCadena($mensaje);
                    }
                    // -------------Control texto-----------------------
                    $esteCampo = 'divMensaje';
                    $atributos ['id'] = $esteCampo;
                    $atributos ["tamanno"] = '';
                    $atributos ["estilo"] = 'information';
                    $atributos ["etiqueta"] = '';
                    $atributos ["columnas"] = ''; // El control ocupa 47% del tamaño del formulario
                    echo $this->miFormulario->campoMensaje($atributos);
                    unset($atributos);
                }

                return true;
            }

        }

        $miFormulario = new FormularioRegistro($this->lenguaje, $this->miFormulario, $this->sql);

        $miFormulario->formulario();
        $miFormulario->mensaje();
        ?>
