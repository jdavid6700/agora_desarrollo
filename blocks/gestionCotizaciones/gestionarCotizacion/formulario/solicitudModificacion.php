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

        $host = $this->miConfigurador->getVariableConfiguracion("host") . $this->miConfigurador->getVariableConfiguracion("site") . "/blocks/gestionCotizaciones/" . $esteBloque ['nombre'] . "/plantilla/archivo_items_plantilla.xlsx";


        $datosSolicitudNecesidad = array(
            'idObjeto' => $_REQUEST['idSolicitud']
        );

        //*********************************************************************************************************************************

        $cadenaSql = $this->miSql->getCadenaSql('buscarUsuario', $_REQUEST['usuario']);
        $resultadoUsuario = $frameworkRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");


        //************************** CONSULTAR ESTADO ACTIVO Jefe Dependencia (Core - Jefe de Dependencia) *******************

        $cadenaSql = $this->miSql->getCadenaSql('buscarOrdenadorActivo', $resultadoUsuario[0]['identificacion']);
        $resultadoJefe = $coreRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");




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
            $_REQUEST['fechaApertura'] = $this->cambiafecha_format($resultadoNecesidadRelacionada[0]['fecha_apertura']);
        if (isset($resultadoNecesidadRelacionada[0]['fecha_cierre']))
            $_REQUEST['fechaCierre'] = $this->cambiafecha_format($resultadoNecesidadRelacionada[0]['fecha_cierre']);

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

        if (isset($resultadoNecesidadRelacionada [0] ['criterio_seleccion']))
            $_REQUEST ['criterioSeleccion'] = $resultadoNecesidadRelacionada [0] ['criterio_seleccion'];
        if (isset($resultadoNecesidadRelacionada [0] ['plazo_ejecucion']))
            $_REQUEST ['plazoEjecucion'] = $resultadoNecesidadRelacionada [0] ['plazo_ejecucion'];

        $cadena_sql = $this->miSql->getCadenaSql("buscarDetalleFormaPago", $datosSolicitudNecesidad);
        $resultadoFP = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");


        $cadena_sql = $this->miSql->getCadenaSql("buscarDetalleItems", $datosSolicitudNecesidad);
        $resultadoItems = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

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
        $tab++;

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

        if (isset($resultadoNecesidadRelacionada[0]['unidad_ejecutora']))
            $_REQUEST['unidad_ejecutora_hidden'] = $resultadoNecesidadRelacionada[0]['unidad_ejecutora'];
        if (isset($resultadoNecesidadRelacionada[0]['ordenador_gasto']))
            $_REQUEST['ordenador_hidden'] = $resultadoNecesidadRelacionada[0]['ordenador_gasto'];





        $esteCampo = "marcoDatosSolicitudCot";
        $atributos ['id'] = $esteCampo;
        $atributos ["estilo"] = "jqueryui";
        $atributos ['tipoEtiqueta'] = 'inicio';
        $atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo) . " - Solicitud Número # (" . $resultadoNecesidadRelacionada[0]['numero_solicitud'] . ")";
        echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);





        $cadena_sql = $this->miSql->getCadenaSql("buscarSolicitudesModificacion", $_REQUEST['idSolicitud']);
        $resultadoSolicitudesMod = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        
        
        

        if ($resultadoSolicitudesMod) {
            $visualizarSolcicitudes = "display:block";
            $esteCampo = "marcoDatosSolicitudModCot";
            $atributos ['id'] = $esteCampo;
            $atributos ["estilo"] = "jqueryui";
            $atributos ['tipoEtiqueta'] = 'inicio';
            $atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo);
            echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);
        } else {
            $visualizarSolcicitudes = "display:none";
        }
        $atributos ["id"] = "tabla_solicitud_modificacion";
        $atributos ["estiloEnLinea"] = $visualizarSolcicitudes;
        $atributos = array_merge($atributos, $atributosGlobales);
        echo $this->miFormulario->division("inicio", $atributos);
        unset($atributos); {
            ?>


            <table id="tablaSolicitudesMod" class="table1" width="100%" >
                <!-- Cabecera de la tabla -->
                <thead>
                    <tr>
                        <th width="5%" >#</th>
                        <th width="7%" >Fecha <br> Solicitud</th>
                        <th width="39%" >Justificación <br> Solicitud</th>
                        <th width="39%" >Justificación <br> Respuesta</th>
                        <th width="5%" >Estado</th>
                        <th width="5%" >Modificación</th>
                    </tr>
                </thead>

                <!-- Cuerpo de la tabla con los campos -->
                <tbody>



                    <?php
                    if (isset($resultadoSolicitudesMod) && $resultadoSolicitudesMod) {
                        $count = count($resultadoSolicitudesMod);
                        $i = 0;
                        $j = 0;
                        $hayRegistro = 0;

                        while ($i < $count) {

                            $cadena_sql = $this->miSql->getCadenaSql("buscarSolicitudesModificacionLog", $resultadoSolicitudesMod[$i]['id']);
                            $resultadoSolicitudesLog = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

                            if ($i == 0 && $resultadoSolicitudesLog) {
                                $hayRegistro = 1;
                            }

                            if ($resultadoSolicitudesLog != false) {
                                $variableMod = "#";
                                $imagenMod = 'cancel.png';
                            } else {

                                $variableMod = "pagina=" . $miPaginaActual; // pendiente la pagina para modificar parametro
                                $variableMod .= "&opcion=modificarSolicitudModificacion";
                                $variableMod .= "&idObjeto=" . $_REQUEST['idSolicitud'];
                                $variableMod .= "&idSolicitud=" . $_REQUEST['idSolicitud'];
                                $variableMod .= "&vigencia=" . $_REQUEST['vigencia'];
                                $variableMod .= "&unidadEjecutora=" . $_REQUEST['unidadEjecutora'];
                                $variableMod .= "&titulo_cotizacion=" . $_REQUEST['tituloCotizacion'];
                                $variableMod .= "&fecha_cierre=" . $this->cambiafecha_format($_REQUEST['fechaCierre']);
                                $variableMod .= "&tipoCotizacion=" . $_REQUEST['tipoNecesidad'];
                                $variableMod .= "&idSolicitudMod=" . $resultadoSolicitudesMod[$i]['id'];



                                $variableMod .= "&usuario=" . $_REQUEST['usuario'];
                                $variableMod = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variableMod, $directorio);
                                $imagenMod = 'resPro.png';
                            }



                            $variableMod2 = "#";
                            $imagenMod2 = 'cancel.png';
//                        
                            if ($resultadoSolicitudesMod[$i]['estado_solicitud'] === '1') {
                                ?>
                                <tr id="nFilas" >
                                    <td><?php echo $j + 1 ?></td>
                                    <td><?php echo $resultadoSolicitudesMod[$i]['fecha_estado'] ?></td>
                                    <td><?php echo $resultadoSolicitudesMod[$i]['justificacion'] ?></td>
                                    <td><?php echo '' ?></td>
                                    <td class="solicitado"><?php echo $resultadoSolicitudesMod[$i]['nombre_estado'] ?></td>
                                    <td><?php echo "<a href='" . $variableMod2 . "'>
											<img src='" . $rutaBloque . "/images/" . $imagenMod2 . "' width='15px'>
										</a>" ?>                           




                                    </td>
                                </tr>
                                <?php
                                $i++;
                            } else {
                                if ($resultadoSolicitudesMod[$i]['estado_solicitud'] === '3') {
                                    ?>
                                    <tr id="nFilas" >
                                        <td><?php echo $j + 1 ?></td>
                                        <td><?php echo $resultadoSolicitudesMod[$i + 1]['fecha_estado'] ?></td>
                                        <td><?php echo $resultadoSolicitudesMod[$i + 1]['justificacion'] ?></td>
                                        <td><?php echo $resultadoSolicitudesMod[$i]['justificacion'] ?></td>
                                        <td class="aceptado"><?php echo $resultadoSolicitudesMod[$i]['nombre_estado'] ?></td>
                                        <td><?php echo "<a href='" . $variableMod . "'>
											<img src='" . $rutaBloque . "/images/" . $imagenMod . "' width='15px'>
										</a>" ?> 
                                        </td>
                                    </tr>
                                    <?php
                                } else {
                                    ?>
                                    <tr id="nFilas" >
                                        <td><?php echo $j + 1 ?></td>
                                        <td><?php echo $resultadoSolicitudesMod[$i + 1]['fecha_estado'] ?></td>
                                        <td><?php echo $resultadoSolicitudesMod[$i + 1]['justificacion'] ?></td>
                                        <td><?php echo $resultadoSolicitudesMod[$i]['justificacion'] ?></td>
                                        <td class="rechazado"><?php echo $resultadoSolicitudesMod[$i]['nombre_estado'] ?></td>
                                        <td><?php echo "<a href='" . $variableMod2 . "'>
											<img src='" . $rutaBloque . "/images/" . $imagenMod2 . "' width='15px'>
										</a>" ?> </td>
                                    </tr>
                                    <?php
                                }
                                $i = $i + 2;
                            }
                            $j = $j + 1;
                        }
                    }
                    ?>
                </tbody>
            </table>
            <?php
        }

        echo $this->miFormulario->division("fin");

        if ($resultadoSolicitudesMod) {
            echo $this->miFormulario->marcoAgrupacion('fin');
        }

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

        $tab++;

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
        $tab++;

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

        $cadenaSql = $this->miSql->getCadenaSql('buscarUsuario', $_REQUEST['usuario']);
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
        $tab++;

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
        $tab++;

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
        $tab++;

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
        $atributos ['tab'] = $tab++;
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
        $atributos ['tab'] = $tab++;
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
        $atributos ['tab'] = $tab++;
        $atributos ['anchoEtiqueta'] = 200;
        $atributos ['evento'] = '';
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
        $atributos ['tab'] = $tab++;
        $atributos ['anchoEtiqueta'] = 200;
        $atributos ['evento'] = '';
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
        // ----------------FIN CONTROL: Lista clase CIIU--------------------------------------------------------







        echo $this->miFormulario->marcoAgrupacion('fin');



        $esteCampo = "marcoDatosSolicitudCotCar";
        $atributos ['id'] = $esteCampo;
        $atributos ["estilo"] = "jqueryui";
        $atributos ['tipoEtiqueta'] = 'inicio';
        $atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo);
        echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);


        // ----------------INICIO CONTROL: Campo de Texto FECHA SOLICITUD--------------------------------------------------------
        $esteCampo = 'fechaApertura';
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
        $tab++;

        // Aplica atributos globales al control
        $atributos = array_merge($atributos, $atributosGlobales);
        echo $this->miFormulario->campoCuadroTexto($atributos);
        unset($atributos);
        // ----------------FIN CONTROL: Campo de Texto FECHA SOLICITUD--------------------------------------------------------
        // ----------------INICIO CONTROL: Campo de Texto FECHA SOLICITUD--------------------------------------------------------
        $esteCampo = 'fechaCierre';
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
        $tab++;

        // Aplica atributos globales al control
        $atributos = array_merge($atributos, $atributosGlobales);
        echo $this->miFormulario->campoCuadroTexto($atributos);
        unset($atributos);

        echo $this->miFormulario->marcoAgrupacion('fin');

        // ----------------FIN CONTROL: Campo de Texto FECHA SOLICITUD--------------------------------------------------------

        $esteCampo = 'perfil_ordenador';
        $atributos ['id'] = $esteCampo;
        $atributos ['nombre'] = $esteCampo;
        $atributos ['tipo'] = 'hidden';
        $atributos ['estilo'] = 'jqueryui';
        $atributos ['dobleLinea'] = false;
        $atributos ['tabIndex'] = $tab;
        if($resultadoJefe!=false){
            $atributos ['valor'] = 'activo';
        }else{
             $atributos ['valor'] = 'inactivo';
            
        }
        
        
        $atributos ['deshabilitado'] = false;
        $atributos ['tamanno'] = 30;
        $atributos ['maximoTamanno'] = '';
        $tab ++;
        // Aplica atributos globales al control
        $atributos = array_merge($atributos, $atributosGlobales);
        echo $this->miFormulario->campoCuadroTexto($atributos);
        unset($atributos);


        $atributos["id"] = "criteriosEva";
        $atributos["estilo"] = "";
        echo $this->miFormulario->division("inicio", $atributos);


        $visualizarSolcitudMod = "display:block";


        if ($resultadoSolicitudesMod[0]['estado_solicitud'] == 1) {
            $visualizarSolcitudMod = "display:none";
        }
        if ($resultadoSolicitudesMod[0]['estado_solicitud'] == 2) {
            $visualizarSolcitudMod = "display:block";
        }
        if ($resultadoSolicitudesMod[0]['estado_solicitud'] == 3 && $hayRegistro == 1) {

            $visualizarSolcitudMod = "display:block";
        }
        if ($resultadoSolicitudesMod[0]['estado_solicitud'] == 3 && $hayRegistro == 0) {

            $visualizarSolcitudMod = "display:none";
        }





//        else {
//            if ($resultadoSolicitudesMod[0]['estado_solicitud'] == 3 && $resultadoSolicitudesLog) {
//                $visualizarSolcitudMod = "display:block";
//            } else {
//                if ($resultadoSolicitudesMod[0]['estado_solicitud'] == 2 ) {
//                    $visualizarSolcitudMod = "display:block";
//                }
//                else{
//                    if ($resultadoSolicitudesMod[0]['estado_solicitud'] == 3 ){
//                        
//                        $visualizarSolcitudMod = "display:none";
//                    }
//                    else{
//                        $visualizarSolcitudMod = "display:block";
//                    }
//                   
//                   
//                    
//                }
//                
//            }
//        }
        $atributos ["id"] = "solicitud_modificacion";
        $atributos ["estiloEnLinea"] = $visualizarSolcitudMod;
        $atributos = array_merge($atributos, $atributosGlobales);
        echo $this->miFormulario->division("inicio", $atributos);
        unset($atributos); {
            // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
            $esteCampo = 'solicitudModificacion';
            $atributos ['id'] = $esteCampo;
            $atributos ['nombre'] = $esteCampo;
            $atributos ['tipo'] = 'text';
            $atributos ['estilo'] = 'jqueryui';
            $atributos ['marco'] = true;
            $atributos ['estiloMarco'] = '';
            $atributos ["etiquetaObligatorio"] = true;
            $atributos ['alto'] = 100;
            $atributos ['columnas'] = 120;
            $atributos ['filas'] = 4;
            $atributos ['dobleLinea'] = 0;
            $atributos ['tabIndex'] = $tab;
            $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
            $atributos ['validar'] = 'required,minSize[27], maxSize[5000]';
            $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
            $atributos ['deshabilitado'] = false;
            $atributos ['tamanno'] = 20;
            $atributos ['maximoTamanno'] = '';
            $atributos ['anchoEtiqueta'] = 220;

            if (isset($_REQUEST [$esteCampo])) {
                $atributos ['valor'] = $_REQUEST [$esteCampo];
            } else {
                $atributos ['valor'] = '';
            }

            $tab++;

            // Aplica atributos globales al control
            $atributos = array_merge($atributos, $atributosGlobales);
            echo $this->miFormulario->campoTextArea($atributos);
            unset($atributos);
        }



        echo $this->miFormulario->division("fin");




        echo $this->miFormulario->marcoAgrupacion('fin');


//                  // //------------------Division para los botones-------------------------
//        $atributos["id"] = "criteriosEva";
//        $atributos["estilo"] = "";
//        echo $this->miFormulario->division("inicio", $atributos);
//        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
//        $esteCampo = 'solicitudModificacion';
//        $atributos ['id'] = $esteCampo;
//        $atributos ['nombre'] = $esteCampo;
//        $atributos ['tipo'] = 'text';
//        $atributos ['estilo'] = 'jqueryui';
//        $atributos ['marco'] = true;
//        $atributos ['estiloMarco'] = '';
//        $atributos ["etiquetaObligatorio"] = true;
//        $atributos ['columnas'] = 120;
//        $atributos ['filas'] = 8;
//        $atributos ['dobleLinea'] = 0;
//        $atributos ['tabIndex'] = $tab;
//        $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
//        $atributos ['validar'] = 'required,minSize[20],maxSize[5000]';
//        $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
//        $atributos ['deshabilitado'] = false;
//        $atributos ['tamanno'] = 20;
//        $atributos ['maximoTamanno'] = '';
//        $atributos ['anchoEtiqueta'] = 220;
//        $atributos ['textoEnriquecido'] = true;
//
//        if (isset($_REQUEST [$esteCampo])) {
//            $atributos ['valor'] = $_REQUEST [$esteCampo];
//        } else {
//            $atributos ['valor'] = '';
//        }
//
//        $tab ++;
//
//        // Aplica atributos globales al control
//        $atributos = array_merge($atributos, $atributosGlobales);
//        echo $this->miFormulario->campoTextArea($atributos);
//        unset($atributos);
//
//
//        echo $this->miFormulario->division("fin");
//        
//        
        //------------------Division para los botones-------------------------
        // ------------------Division para los botones-------------------------
        $atributos ["id"] = "botones";
        $atributos ["estilo"] = "marcoBotones";
        echo $this->miFormulario->division("inicio", $atributos); {
            // -----------------CONTROL: Botón ----------------------------------------------------------------



            $esteCampo = 'botonEnviarSolicitud';





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
            $tab++;

            // Aplica atributos globales al control
            $atributos = array_merge($atributos, $atributosGlobales);
            echo $this->miFormulario->campoBoton($atributos);

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
        $valorCodificado .= "&opcion=solicitarModificacion";
        $valorCodificado .= "&usuario=" . $_REQUEST['usuario'];
        $valorCodificado .= "&idObjeto=" . $_REQUEST['idSolicitud'];
        $valorCodificado .= "&tipoNecesidad=" . $resultadoNecesidadRelacionada[0]['tipo_necesidad'];

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
