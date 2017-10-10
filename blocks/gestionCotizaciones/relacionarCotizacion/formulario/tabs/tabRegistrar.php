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

        $conexion = "argo_contratos";
        $esteRecursoDBArka = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);



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

        $host = $this->miConfigurador->getVariableConfiguracion("host") . $this->miConfigurador->getVariableConfiguracion("site") . "/blocks/gestionCotizaciones/" . $esteBloque ['nombre'] . "/plantilla/archivo_items_plantilla.xlsx";


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

        //******************************************************************************************************************************
        $variable = "pagina=" . $miPaginaActual;
        $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);


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




        echo "<div id='marcoDatosLoad' style='width: 100%;height: 900px'>
			<div style='width: 100%;height: 100px'>
			</div>
			<center><img src='" . $rutaBloque . "/images/loading.gif'" . ' width=20% height=20% vspace=15 hspace=3 >
			</center>
		  </div>';



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
                    if (isset($estadoSolicitud)) {
                        $atributos ['seleccion'] = '';
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

                    $matrizItems = array(
                        array(1, '1 - RECTORÍA'),
                        array(2, '2 - IDEXUD')
                    );

                    $atributos ['matrizItems'] = $matrizItems;

                    $atributos = array_merge($atributos, $atributosGlobales);
                    echo $this->miFormulario->campoCuadroLista($atributos);
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
                    echo $this->miFormulario->campoCuadroLista($atributos);
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
                    echo $this->miFormulario->campoCuadroLista($atributos);
                    unset($atributos);



                    echo "<br><br><br><br><br><br><div class='container'>
		
	<div class='row'>
        <div class='col-md-24'>
				<div class='panel panel-primary'>
					<div class='panel-heading'>
						<center><h3 class='panel-title'>Información Presupuestal</h3></center>
		
					</div>
					<table class='table1' id='tablacdpasociados' width='100%'>
						<thead>
							<tr>
								<th><center>Vigencia</center></th>
								<th><center>Solicitud de Necesidad</center></th>
								<th><center>Número de Disponibilidad</center></th>
								<th><center>$ Valor (En pesos)</center></th>
								<th><center>Dependencia</center></th>
								<th><center>Rubro</center></th>
								<th><center>Estado</center></th>
		
							</tr>
						</thead>
                                               <tbody>
                                                 <tr id='0'></tr>
                                               </tbody>
                                               </table>";


                    //------------------Division para los botones-------------------------
                    $atributos["id"] = "botones";
                    $atributos["estilo"] = "marcoBotones widget";
                    echo $this->miFormulario->division("inicio", $atributos);

                    //******************************************************************************************************************************
                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                    $esteCampo = 'eliminarCDP';
                    $atributos ['id'] = $esteCampo;
                    $atributos ['enlace'] = "#";
                    $atributos ['tabIndex'] = 1;
                    $atributos ['estilo'] = 'textoSubtitulo';
                    $atributos ['enlaceTexto'] = "Eliminar (Necesidad)";
                    $atributos ['ancho'] = '10%';
                    $atributos ['alto'] = '10%';
                    $atributos ['redirLugar'] = false;
                    echo $this->miFormulario->enlace($atributos);

                    unset($atributos);
                    //********************************************************************************************************************************
                    //------------------Fin Division para los botones-------------------------
                    echo $this->miFormulario->division("fin");



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
        $atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo);
        echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);
        
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
        
        $atributos ['valor'] = '';
        
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

        $year = date('Y');
        $atributos ['valor'] = $year;

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
        if (isset($estadoSolicitud)) {
            $atributos ['seleccion'] = '';
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
        if (isset($_REQUEST [$esteCampo])) {
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
        if (isset($_REQUEST [$esteCampo])) {
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

        //$dia=date("d/m/Y");
        //$newDate = date("d/m/Y", strtotime($resultado[0]['FECHA_SOLICITUD']));

        $atributos ['valor'] = '';

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

        $atributos ['valor'] = '';

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
        $atributos ['validar'] = 'required,minSize[20],maxSize[5000]';
        $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
        $atributos ['deshabilitado'] = true;
        $atributos ['tamanno'] = 20;
        $atributos ['maximoTamanno'] = '';
        $atributos ['anchoEtiqueta'] = 220;
        $atributos ['textoEnriquecido'] = true;

        $atributos ['valor'] = '';

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
        $atributos ['validar'] = 'required,minSize[20],maxSize[5000]';
        $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
        $atributos ['deshabilitado'] = true;
        $atributos ['tamanno'] = 20;
        $atributos ['maximoTamanno'] = '';
        $atributos ['anchoEtiqueta'] = 220;

        $atributos ['valor'] = '';

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
        $atributos ['validar'] = 'maxSize[5000]';
        $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
        $atributos ['deshabilitado'] = true;
        $atributos ['tamanno'] = 20;
        $atributos ['maximoTamanno'] = '';
        $atributos ['anchoEtiqueta'] = 220;

        $atributos ['valor'] = '';

        $tab ++;

        // Aplica atributos globales al control
        $atributos = array_merge($atributos, $atributosGlobales);
        echo $this->miFormulario->campoTextArea($atributos);
        unset($atributos);


        // ----------------INICIO CONTROL: Campo de Texto DEPENDENCIA DESTINO--------------------------------------------------------
        $esteCampo = 'estado';
        $atributos ['id'] = $esteCampo;
        $atributos ['nombre'] = $esteCampo;
        $atributos ['tipo'] = 'hidden';
        $atributos ['estilo'] = 'jqueryui';
        $atributos ['columnas'] = 1;
        $atributos ['dobleLinea'] = 0;
        $atributos ['tabIndex'] = $tab;
        $atributos ['validar'] = '';

        $atributos ['valor'] = 'RELACIONADO';
        $tab ++;

        // Aplica atributos globales al control
        $atributos = array_merge($atributos, $atributosGlobales);
        echo $this->miFormulario->campoCuadroTexto($atributos);
        unset($atributos);
        // ----------------FIN CONTROL: Campo de Texto DEPENDENCIA DESTINO--------------------------------------------------------



        //------------------Division para los botones-------------------------
        $atributos["id"] = "criteriosEva";
        $atributos["estilo"] = "";
        echo $this->miFormulario->division("inicio", $atributos);
        
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
        $atributos ['deshabilitado'] = false;
        $atributos ['tamanno'] = 20;
        $atributos ['maximoTamanno'] = '';
        $atributos ['anchoEtiqueta'] = 220;
        
        $atributos ['valor'] = '';
        
        $tab ++;
        
        // Aplica atributos globales al control
        $atributos = array_merge($atributos, $atributosGlobales);
        echo $this->miFormulario->campoTextArea($atributos);
        unset($atributos);
        
        echo $this->miFormulario->division("fin");
        
        
        
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
        
        $atributos ['valor'] = '';
        
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
        $atributos ['validar'] = 'required,minSize[20],maxSize[5000]';
        $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
        $atributos ['deshabilitado'] = false;
        $atributos ['tamanno'] = 20;
        $atributos ['maximoTamanno'] = '';
        $atributos ['anchoEtiqueta'] = 220;

        $atributos ['valor'] = '';

        $tab ++;

        // Aplica atributos globales al control
        $atributos = array_merge($atributos, $atributosGlobales);
        echo $this->miFormulario->campoTextArea($atributos);
        unset($atributos);

        echo $this->miFormulario->marcoAgrupacion('fin');




        $esteCampo = "marcoDescripcionProducto";
        $atributos ['id'] = $esteCampo;
        $atributos ["estilo"] = "jqueryui";
        $atributos ['tipoEtiqueta'] = 'inicio';
        $atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo);
        echo $this->miFormulario->marcoAgrupacion('inicio', $atributos); {

            $esteCampo = 'tipo_registro';
            $atributos ['columnas'] = 1;
            $atributos ['nombre'] = $esteCampo;
            $atributos ['id'] = $esteCampo;
            $atributos ['seleccion'] = 1;
            $atributos ['evento'] = '';
            $atributos ['deshabilitado'] = false;
            $atributos ['tab'] = $tab;
            $atributos ['tamanno'] = 1;
            $atributos ['estilo'] = 'jqueryui';
            $atributos ['validar'] = '';
            $atributos ['limitar'] = false;
            $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
            $atributos ['anchoEtiqueta'] = 213;
            // Valores a mostrar en el control
            $matrizItems = array(
                array(
                    1,
                    'Solo Un Elemento'
                ),
                array(
                    2,
                    'Cargue Masivo Elementos'
                )
            );

            $atributos ['matrizItems'] = $matrizItems;

            // Utilizar lo siguiente cuando no se pase un arreglo:
            // $atributos['baseDatos']='ponerAquiElNombreDeLaConexión';
            // $atributos ['cadena_sql']='ponerLaCadenaSqlAEjecutar';
            $tab++;
            $atributos = array_merge($atributos, $atributosGlobales);
            echo $this->miFormulario->campoCuadroLista($atributos);
            unset($atributos);
            echo '<br><br><br>';


            $atributos ["id"] = "cargar_elemento";
            $atributos ["estiloEnLinea"] = "display:block";
            $atributos = array_merge($atributos, $atributosGlobales);
            echo $this->miFormulario->division("inicio", $atributos);
            unset($atributos); {
                echo '<div align="center">'; {
                    $esteCampo = "marcoParametrosItem";
                    $atributos ['id'] = $esteCampo;
                    $atributos ["estilo"] = "jqueryui";
                    $atributos ['tipoEtiqueta'] = 'inicio';
                    $atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo);
                    echo $this->miFormulario->marcoAgrupacion('inicio', $atributos); {

                        echo '<div align="left">';
                        {
                            // ---------------- CONTROL: Tipo Forma Pago--------------------------------------------------------
                            $esteCampo = "tipoItem";
                            $atributos ['nombre'] = $esteCampo;
                            $atributos ['id'] = $esteCampo;
                            $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                            $atributos ["etiquetaObligatorio"] = false;
                            $atributos ['tab'] = $tab++;
                            $atributos ['anchoEtiqueta'] = 200;
                            $atributos ['evento'] = '';
                            if (isset($_REQUEST [$esteCampo])) {
                                $atributos ['seleccion'] = $_REQUEST [$esteCampo];
                            } else {
                                $atributos ['seleccion'] = - 1;
                            }
                            $atributos ['deshabilitado'] = false;
                            $atributos ['columnas'] = 2;
                            $atributos ['tamanno'] = 1;
                            $atributos ['ajax_function'] = "";
                            $atributos ['ajax_control'] = $esteCampo;
                            $atributos ['estilo'] = "jqueryui";
                            $atributos ['validar'] = "";
                            $atributos ['limitar'] = false;
                            $atributos ['anchoCaja'] = 60;
                            $atributos ['miEvento'] = '';



//
                            if ($bien) {
                                $atributos ['cadena_sql'] = $cadenaSql = $this->miSql->getCadenaSql('tipoNecesidadAdministrativaOnlyBien');
                            } else if ($servicio) {
                                $atributos ['cadena_sql'] = $cadenaSql = $this->miSql->getCadenaSql('tipoNecesidadAdministrativaOnlyServicio');
                            } else {
                                $atributos ['cadena_sql'] = $cadenaSql = $this->miSql->getCadenaSql('tipoNecesidadAdministrativa2');
                            }



                            $matrizItems = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");

                            $atributos ['matrizItems'] = $matrizItems;
                            $atributos = array_merge($atributos, $atributosGlobales);
                            echo $this->miFormulario->campoCuadroLista($atributos);
                            unset($atributos);


                            echo '<div id="parametros1">';
                            {

                                // ----------------INICIO CONTROL: Campo de Texto VIGENCIA--------------------------------------------------------
                                $esteCampo = 'nombreItem';
                                $atributos ['id'] = $esteCampo;
                                $atributos ['nombre'] = $esteCampo;
                                $atributos ['tipo'] = 'text';
                                $atributos ['estilo'] = 'jqueryui mayuscula';
                                $atributos ['marco'] = true;
                                $atributos ['estiloMarco'] = '';
                                $atributos ["etiquetaObligatorio"] = false;
                                $atributos ['columnas'] = 1;
                                $atributos ['dobleLinea'] = 0;
                                $atributos ['tabIndex'] = $tab;
                                $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                                $atributos ['validar'] = '';

                                $atributos ['valor'] = '';

                                $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
                                $atributos ['deshabilitado'] = false;
                                $atributos ['tamanno'] = 40;
                                $atributos ['maximoTamanno'] = '30';
                                $atributos ['anchoEtiqueta'] = 200;
                                $tab++;

                                // Aplica atributos globales al control
                                $atributos = array_merge($atributos, $atributosGlobales);
                                echo $this->miFormulario->campoCuadroTexto($atributos);
                                unset($atributos);
                                // ----------------FIN CONTROL: Campo de Texto VIGENCIA--------------------------------------------------------
                                // ----------------INICIO CONTROL: Campo de Texto VIGENCIA--------------------------------------------------------
                                $esteCampo = 'descripcionItem';
                                $atributos ['id'] = $esteCampo;
                                $atributos ['nombre'] = $esteCampo;
                                $atributos ['tipo'] = 'text';
                                $atributos ['estilo'] = 'jqueryui mayuscula';
                                $atributos ['marco'] = true;
                                $atributos ['estiloMarco'] = '';
                                $atributos ["etiquetaObligatorio"] = false;
                                $atributos ['columnas'] = 1;
                                $atributos ['dobleLinea'] = 0;
                                $atributos ['tabIndex'] = $tab;
                                $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                                $atributos ['validar'] = '';

                                $atributos ['valor'] = '';

                                $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
                                $atributos ['deshabilitado'] = false;
                                $atributos ['tamanno'] = 80;
                                $atributos ['maximoTamanno'] = '100';
                                $atributos ['anchoEtiqueta'] = 200;
                                $tab++;

                                // Aplica atributos globales al control
                                $atributos = array_merge($atributos, $atributosGlobales);
                                echo $this->miFormulario->campoCuadroTexto($atributos);
                                unset($atributos);
                                // ----------------FIN CONTROL: Campo de Texto VIGENCIA--------------------------------------------------------
                            }
                            echo '</div>';


                            echo '<div id="parametros2">'; {
                                // ---------------- CONTROL: Tipo Forma Pago--------------------------------------------------------
                                $esteCampo = "unidadItem";
                                $atributos ['nombre'] = $esteCampo;
                                $atributos ['id'] = $esteCampo;
                                $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                                $atributos ["etiquetaObligatorio"] = false;
                                $atributos ['tab'] = $tab++;
                                $atributos ['anchoEtiqueta'] = 200;
                                $atributos ['evento'] = '';
                                if (isset($_REQUEST [$esteCampo])) {
                                    $atributos ['seleccion'] = $_REQUEST [$esteCampo];
                                } else {
                                    $atributos ['seleccion'] = - 1;
                                }
                                $atributos ['deshabilitado'] = false;
                                $atributos ['columnas'] = 2;
                                $atributos ['tamanno'] = 1;
                                $atributos ['ajax_function'] = "";
                                $atributos ['ajax_control'] = $esteCampo;
                                $atributos ['estilo'] = "jqueryui";
                                $atributos ['validar'] = "";
                                $atributos ['limitar'] = false;
                                $atributos ['anchoCaja'] = 60;
                                $atributos ['miEvento'] = '';
                                $atributos ['cadena_sql'] = $cadenaSql = $this->miSql->getCadenaSql('unidadUdistrital');
                                $matrizItems = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");


                                $atributos ['matrizItems'] = $matrizItems;
                                $atributos = array_merge($atributos, $atributosGlobales);
                                echo $this->miFormulario->campoCuadroLista($atributos);
                                unset($atributos);
                                // ----------------FIN CONTROL: Tipo Forma Pago--------------------------------------------------------
                            }
                            echo '</div>';

                            echo '<div id="parametros3">';
                            {

//                                $esteCampo = "marcoParametrosItemTiempo";
//                                $atributos ['id'] = $esteCampo;
//                                $atributos ["estilo"] = "jqueryui";
//                                $atributos ['tipoEtiqueta'] = 'inicio';
//                                $atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo);
//                                echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);
//                                {

//                                    // ----------------INICIO CONTROL: Campo de Texto VIGENCIA--------------------------------------------------------
//                                    $esteCampo = 'tiempoItem1';
//                                    $atributos ['id'] = $esteCampo;
//                                    $atributos ['nombre'] = $esteCampo;
//                                    $atributos ['tipo'] = 'text';
//                                    $atributos ['estilo'] = 'jqueryui';
//                                    $atributos ['marco'] = true;
//                                    $atributos ['estiloMarco'] = '';
//                                    $atributos ["etiquetaObligatorio"] = false;
//                                    $atributos ['columnas'] = 3;
//                                    $atributos ['dobleLinea'] = 0;
//                                    $atributos ['tabIndex'] = $tab;
//                                    $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
//                                    $atributos ['validar'] = 'custom[integer]';
//
//                                    $atributos ['valor'] = '0';
//
//                                    $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
//                                    $atributos ['deshabilitado'] = false;
//                                    $atributos ['tamanno'] = 10;
//                                    $atributos ['maximoTamanno'] = '20';
//                                    $atributos ['anchoEtiqueta'] = 100;
//                                    $tab++;
//
//                                    // Aplica atributos globales al control
//                                    $atributos = array_merge($atributos, $atributosGlobales);
//                                    echo $this->miFormulario->campoCuadroTexto($atributos);
//                                    unset($atributos);
//                                    // ----------------FIN CONTROL: Campo de Texto VIGENCIA--------------------------------------------------------
//                                    // ----------------INICIO CONTROL: Campo de Texto VIGENCIA--------------------------------------------------------
//                                    $esteCampo = 'tiempoItem2';
//                                    $atributos ['id'] = $esteCampo;
//                                    $atributos ['nombre'] = $esteCampo;
//                                    $atributos ['tipo'] = 'text';
//                                    $atributos ['estilo'] = 'jqueryui';
//                                    $atributos ['marco'] = true;
//                                    $atributos ['estiloMarco'] = '';
//                                    $atributos ["etiquetaObligatorio"] = false;
//                                    $atributos ['columnas'] = 3;
//                                    $atributos ['dobleLinea'] = 0;
//                                    $atributos ['tabIndex'] = $tab;
//                                    $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
//                                    $atributos ['validar'] = 'custom[integer]';
//
//                                    $atributos ['valor'] = '0';
//
//                                    $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
//                                    $atributos ['deshabilitado'] = false;
//                                    $atributos ['tamanno'] = 10;
//                                    $atributos ['maximoTamanno'] = '20';
//                                    $atributos ['anchoEtiqueta'] = 100;
//                                    $tab++;
//
//                                    // Aplica atributos globales al control
//                                    $atributos = array_merge($atributos, $atributosGlobales);
//                                    echo $this->miFormulario->campoCuadroTexto($atributos);
//                                    unset($atributos);
//                                    // ----------------FIN CONTROL: Campo de Texto VIGENCIA--------------------------------------------------------
//                                    // ----------------INICIO CONTROL: Campo de Texto VIGENCIA--------------------------------------------------------
//                                    $esteCampo = 'tiempoItem3';
//                                    $atributos ['id'] = $esteCampo;
//                                    $atributos ['nombre'] = $esteCampo;
//                                    $atributos ['tipo'] = 'text';
//                                    $atributos ['estilo'] = 'jqueryui';
//                                    $atributos ['marco'] = true;
//                                    $atributos ['estiloMarco'] = '';
//                                    $atributos ["etiquetaObligatorio"] = false;
//                                    $atributos ['columnas'] = 3;
//                                    $atributos ['dobleLinea'] = 0;
//                                    $atributos ['tabIndex'] = $tab;
//                                    $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
//                                    $atributos ['validar'] = 'custom[integer]';
//
//                                    $atributos ['valor'] = '0';
//
//                                    $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
//                                    $atributos ['deshabilitado'] = false;
//                                    $atributos ['tamanno'] = 10;
//                                    $atributos ['maximoTamanno'] = '20';
//                                    $atributos ['anchoEtiqueta'] = 100;
//                                    $tab++;
//
//                                    // Aplica atributos globales al control
//                                    $atributos = array_merge($atributos, $atributosGlobales);
//                                    echo $this->miFormulario->campoCuadroTexto($atributos);
//                                    unset($atributos);
                                    // ----------------FIN CONTROL: Campo de Texto VIGENCIA--------------------------------------------------------
//                                }
//
//                                echo $this->miFormulario->marcoAgrupacion('fin');
                            }

                            echo '</div>';

                            echo '<div id="parametros4">'; {

                                // ----------------INICIO CONTROL: Campo de Texto VIGENCIA--------------------------------------------------------
                                $esteCampo = 'cantidadItem';
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
                                $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                                $atributos ['validar'] = 'custom[number]';

                                $atributos ['valor'] = '';

                                $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
                                $atributos ['deshabilitado'] = false;
                                $atributos ['tamanno'] = 20;
                                $atributos ['maximoTamanno'] = '20';
                                $atributos ['anchoEtiqueta'] = 200;
                                $tab++;

                                // Aplica atributos globales al control
                                $atributos = array_merge($atributos, $atributosGlobales);
                                echo $this->miFormulario->campoCuadroTexto($atributos);
                                unset($atributos);
                                // ----------------FIN CONTROL: Campo de Texto VIGENCIA--------------------------------------------------------
                                
                               
                            }
                            echo '</div>';
                        }

                        echo '</div>';
                    }

                    echo $this->miFormulario->marcoAgrupacion('fin');
                }
                echo '</div>';
                //------------------Division para los botones-------------------------
                $atributos["id"] = "botones";
                $atributos["estilo"] = "marcoBotones widget";
                echo $this->miFormulario->division("inicio", $atributos);

                //******************************************************************************************************************************
                // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                $esteCampo = 'botonAgregarItem';
                $atributos ['id'] = $esteCampo;
                $atributos ['enlace'] = '';
                $atributos ['tabIndex'] = 1;
                $atributos ['estilo'] = 'textoSubtitulo';
                $atributos ['enlaceTexto'] = $this->lenguaje->getCadena($esteCampo);
                $atributos ['ancho'] = '10%';
                $atributos ['alto'] = '10%';
                $atributos ['redirLugar'] = false;
                echo $this->miFormulario->enlace($atributos);

                unset($atributos);
                //********************************************************************************************************************************
                //------------------Fin Division para los botones-------------------------
                echo $this->miFormulario->division("fin");
            }
            echo $this->miFormulario->division("fin");

            $atributos ["id"] = "cargue_elementos";
            $atributos ["estiloEnLinea"] = "display:none";
            $atributos = array_merge($atributos, $atributosGlobales);
            echo $this->miFormulario->division("inicio", $atributos);
            unset($atributos);
            {
                $esteCampo = "AgrupacionInformacion";
                $atributos ['id'] = $esteCampo;
                $atributos ['leyenda'] = "Cargue Masivo de Elementos";
                echo $this->miFormulario->agrupacion('inicio', $atributos);
                {


                    $mensaje = "- El Archivo Tiene que Ser Tipo Excel.
								<br>- Solo Se Cargaran de forma Correcta de Acuerdo a la Plantilla Preedeterminada.
								<br>- Enlace de Archivo Plantilla : <A HREF=" . $host . "> Archivo Plantilla </A>";

                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                    $esteCampo = 'mensajeRegistro';
                    $atributos ['id'] = $esteCampo;
                    $atributos ['tipo'] = 'warning';
                    $atributos ['estilo'] = 'textoCentrar';
                    $atributos ['mensaje'] = $mensaje;

                    $tab++;

                    // Aplica atributos globales al control
                    $atributos = array_merge($atributos, $atributosGlobales);
                    echo $this->miFormulario->cuadroMensaje($atributos);

                    $esteCampo = "documentos_elementos";
                    $atributos ["id"] = $esteCampo; // No cambiar este nombre
                    $atributos ["nombre"] = $esteCampo;
                    $atributos ["tipo"] = "file";
                    $atributos ["obligatorio"] = true;
                    $atributos ["etiquetaObligatorio"] = true;
                    $atributos ["tabIndex"] = $tab++;
                    $atributos ["columnas"] = 1;
                    $atributos ["estilo"] = "textoIzquierda";
                    $atributos ["anchoEtiqueta"] = 190;
                    $atributos ["tamanno"] = 500000;
                    $atributos ["validar"] = "";
                    $atributos ["etiqueta"] = $this->lenguaje->getCadena($esteCampo);
                    // $atributos ["valor"] = $valorCodificado;
                    $atributos = array_merge($atributos, $atributosGlobales);
                    echo $this->miFormulario->campoCuadroTexto($atributos);
                    unset($atributos);

                    //------------------Division para los botones-------------------------
                    $atributos["id"] = "botones";
                    $atributos["estilo"] = "marcoBotones widget";
                    echo $this->miFormulario->division("inicio", $atributos);

                    //******************************************************************************************************************************
                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                    $esteCampo = 'botonCargarArchivo';
                    $atributos ['id'] = $esteCampo;
                    $atributos ['enlace'] = '';
                    $atributos ['tabIndex'] = 1;
                    $atributos ['estilo'] = 'textoSubtitulo';
                    $atributos ['enlaceTexto'] = $this->lenguaje->getCadena($esteCampo);
                    $atributos ['ancho'] = '10%';
                    $atributos ['alto'] = '10%';
                    $atributos ['redirLugar'] = false;
                    echo $this->miFormulario->enlace($atributos);

                    unset($atributos);
                    //********************************************************************************************************************************
                    //------------------Fin Division para los botones-------------------------
                    echo $this->miFormulario->division("fin");
                }
                echo $this->miFormulario->agrupacion('fin');
            }
            echo $this->miFormulario->division("fin");

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
                        <th width="5%" >#</th>
                        <th width="10%" >Nombre</th>
                        <th width="20%" >Descripción</th>
                        <th width="10%" >Tipo</th>
                        <th width="10%" >Unidad</th>
                        <th width="5%" >Cantidad</th>
                        <th width="10%" >&nbsp;</th>
                    </tr>
                </thead>

                <!-- Cuerpo de la tabla con los campos -->
                <tbody>

                    <!-- fila base para clonar y agregar al final -->
                    <!-- fin de código: fila base -->
                    <!--  
                                   <tr>
                                           <td>dsfdf</td>
                                           <td>dsfdf</td>
                                           <td>dsfdf</td>
                                           <td>dsfdf</td>
                                           <td>dsfdf</td>
                                           <td>dsfdf</td>
                                           <td>dsfdf</td>
                                           <th class="eliminar" scope="row">Eliminar</th>
                                   </tr> -->
                    <!-- fin de código: fila de ejemplo -->

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
//         $atributos ['deshabilitado'] = false;
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
        if (isset($_REQUEST [$esteCampo])) {
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
        $atributos ['cadena_sql'] = $cadenaSql = $this->miSql->getCadenaSql('medioPagoUdistrital');
        $matrizItems = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
        $atributos ['matrizItems'] = $matrizItems;
        $atributos = array_merge($atributos, $atributosGlobales);
        echo $this->miFormulario->campoCuadroLista($atributos);
        unset($atributos);
        // ----------------FIN CONTROL: Lista clase CIIU--------------------------------------------------------






        $esteCampo = "marcoParametrosFormaPago";
        $atributos ['id'] = $esteCampo;
        $atributos ["estilo"] = "jqueryui";
        $atributos ['tipoEtiqueta'] = 'inicio';
        $atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo);
        echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);

        // ---------------- CONTROL: Tipo Forma Pago--------------------------------------------------------
        $esteCampo = "tipoFormaPago";
        $atributos ['nombre'] = $esteCampo;
        $atributos ['id'] = $esteCampo;
        $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
        $atributos ["etiquetaObligatorio"] = false;
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
        $atributos ['validar'] = "";
        $atributos ['limitar'] = false;
        $atributos ['anchoCaja'] = 60;
        $atributos ['miEvento'] = '';
        $atributos ['cadena_sql'] = $cadenaSql = $this->miSql->getCadenaSql('tipoFormaPagoUdistrital');
        $matrizItems = $coreRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
        $atributos ['matrizItems'] = $matrizItems;
        $atributos = array_merge($atributos, $atributosGlobales);
        echo $this->miFormulario->campoCuadroLista($atributos);
        unset($atributos);
        // ----------------FIN CONTROL: Tipo Forma Pago--------------------------------------------------------


        $atributos ["id"] = "valoresForma";
        $atributos ["estilo"] = "";
        echo $this->miFormulario->division("inicio", $atributos);


        $atributos ["id"] = "valorAvance";
        $atributos ["estilo"] = "";
        echo $this->miFormulario->division("inicio", $atributos);

        // ----------------INICIO CONTROL: Campo de Texto VIGENCIA--------------------------------------------------------
        $esteCampo = 'valorFormaPago';
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
        $atributos ['validar'] = 'custom[number]';

        $atributos ['valor'] = '';

        $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
        $atributos ['deshabilitado'] = false;
        $atributos ['tamanno'] = 20;
        $atributos ['maximoTamanno'] = '20';
        $atributos ['anchoEtiqueta'] = 200;
        $tab ++;

        // Aplica atributos globales al control
        $atributos = array_merge($atributos, $atributosGlobales);
        echo $this->miFormulario->campoCuadroTexto($atributos);
        unset($atributos);
        // ----------------FIN CONTROL: Campo de Texto VIGENCIA--------------------------------------------------------
        echo $this->miFormulario->division("fin");

        // ----------------INICIO CONTROL: Campo de Texto VIGENCIA--------------------------------------------------------
        $esteCampo = 'porcentajePagoForma';
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
        $atributos ['validar'] = 'custom[number]';

        $atributos ['valor'] = '';

        $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
        $atributos ['deshabilitado'] = false;
        $atributos ['tamanno'] = 20;
        $atributos ['maximoTamanno'] = '20';
        $atributos ['anchoEtiqueta'] = 200;
        $tab ++;

        // Aplica atributos globales al control
        $atributos = array_merge($atributos, $atributosGlobales);
        echo $this->miFormulario->campoCuadroTexto($atributos);
        unset($atributos);
        // ----------------FIN CONTROL: Campo de Texto VIGENCIA--------------------------------------------------------

        echo $this->miFormulario->division("fin");


        echo $this->miFormulario->marcoAgrupacion('fin');


        //------------------Division para los botones-------------------------
        $atributos["id"] = "botones";
        $atributos["estilo"] = "marcoBotones widget";
        echo $this->miFormulario->division("inicio", $atributos);

        //******************************************************************************************************************************
        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
        $esteCampo = 'botonAgregar';
        $atributos ['id'] = $esteCampo;
        $atributos ['enlace'] = '';
        $atributos ['tabIndex'] = 1;
        $atributos ['estilo'] = 'textoSubtitulo';
        $atributos ['enlaceTexto'] = $this->lenguaje->getCadena($esteCampo);
        $atributos ['ancho'] = '10%';
        $atributos ['alto'] = '10%';
        $atributos ['redirLugar'] = false;
        echo $this->miFormulario->enlace($atributos);

        unset($atributos);
        //********************************************************************************************************************************
        //------------------Fin Division para los botones-------------------------
        echo $this->miFormulario->division("fin");


        $esteCampo = "marcoFormaPago";
        $atributos ['id'] = $esteCampo;
        $atributos ["estilo"] = "jqueryui";
        $atributos ['tipoEtiqueta'] = 'inicio';
        $atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo);
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
        if (isset($_REQUEST [$esteCampo])) {
            $atributos ['valor'] = $_REQUEST [$esteCampo];
        } else {
            $atributos ['valor'] = '';
        }
        $atributos ['validar'] = 'required';
        $atributos ['deshabilitado'] = true;
        $atributos ['tamanno'] = '70%';
        $atributos = array_merge($atributos, $atributosGlobales);
        echo $this->miFormulario->campoCuadroTexto($atributos);
        unset($atributos);
        ?>
        <p>Configurado el 100% de la Forma Pago</p>
        <!-- Slide THREE -->
        <div class="slideThree">  
            <input type="checkbox" value="None" id="slideThree" name="check" class="validate[required]" readonly="readonly" />
            <label for="slideThree"></label>
        </div>

        <?php ?>


        <table id="tablaFP" class="table1" width="100%" >
            <!-- Cabecera de la tabla -->
            <thead>
                <tr>
                    <th width="20%" >Tipo</th>
                    <th width="30%" >Condición de Avance</th>
                    <th width="35%" >Porcentaje (%) de Pago</th>
                    <th width="15%" >&nbsp;</th>
                </tr>
            </thead>

            <!-- Cuerpo de la tabla con los campos -->
            <tbody>

                <!-- fila base para clonar y agregar al final -->
                <!-- fin de código: fila base -->

                <!--					 Fila de ejemplo 
                                                        <tr>
                                                                <td><input type="text" class="nombre" size="40" /></td>
                                                                <td><input type="text" class="apellidos" size="125" /></td>
                                                                <td><input type="text" class="apellidos" size="65" /></td>
                                                                <th class="eliminar" scope="row">Eliminar</th>
                                                        </tr>-->
                <!-- fin de código: fila de ejemplo -->

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

        echo $this->miFormulario->marcoAgrupacion('fin');



        $esteCampo = "marcoAnexo";
        $atributos ['id'] = $esteCampo;
        $atributos ["estilo"] = "jqueryui";
        $atributos ['tipoEtiqueta'] = 'inicio';
        $atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo);
        //echo $this->miFormulario->marcoAgrupacion('inicio', $atributos); ---


        // ----------------INICIO CONTROL: DOCUMENTO--------------------------------------------------------
        $esteCampo = "cotizacionSoporte";
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

        $atributos ["etiqueta"] = $this->lenguaje->getCadena($esteCampo);
        // $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
        // $atributos ["valor"] = $valorCodificado;
        $atributos = array_merge($atributos, $atributosGlobales);
        //echo $this->miFormulario->campoCuadroTexto($atributos); ---
        unset($atributos);
        // ----------------FIN CONTROL: DOCUMENTO--------------------------------------------------------

        //echo $this->miFormulario->marcoAgrupacion('fin'); ---





        //**************************************** RELACION DATOS AGORA **********************************************************************************************
        //************************************************************************************************************************************************************


        $esteCampo = "marcoTipoNecesidadCot";
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

        if (isset($resultadoNecesidadRelacionada[0]['tipo_necesidad'])) {//CAST tipo de NECESIDAD
            switch ($resultadoNecesidadRelacionada[0]['tipo_necesidad']) {
                case 'BIEN' :
                    $resultadoNecesidadRelacionada[0]['tipo_necesidad'] = 1;
                    break;
                case 'SERVICIO' :
                    $resultadoNecesidadRelacionada[0]['tipo_necesidad'] = 2;
                    break;
            }
        }

        if (isset($estadoSolicitud)) {
            $atributos ['seleccion'] = $resultadoNecesidadRelacionada[0]['tipo_necesidad'];
        } else {
            $atributos ['seleccion'] = - 1;
        }
        $atributos ['deshabilitado'] = false;
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


            if (isset($estadoSolicitud)) {
                $esteCampo = 'botonModificar';
            } else {
                $esteCampo = 'botonRegistrar';
            }


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
            echo $this->miFormulario->campoBoton($atributos);

            // -----------------FIN CONTROL: Botón -----------------------------------------------------------
        }
        // ------------------Fin Division para los botones-------------------------
        echo $this->miFormulario->division("fin");


        echo $this->miFormulario->marcoAgrupacion('fin');








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
