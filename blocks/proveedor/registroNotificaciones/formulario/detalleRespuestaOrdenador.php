<?php

if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
}

use usuarios\cambiarClave\funcion\redireccion;

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

    function miForm() {

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

        $_REQUEST ['tiempo'] = time();
        $tiempo = $_REQUEST ['tiempo'];





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
        // $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo );
        // Si no se coloca, entonces toma el valor predeterminado.
        $atributos ['estilo'] = '';
        $atributos ['marco'] = false;
        $tab = 1;
        // ---------------- FIN SECCION: de Parámetros Generales del Formulario ----------------------------
        // ----------------INICIAR EL FORMULARIO ------------------------------------------------------------
        $atributos ['tipoEtiqueta'] = 'inicio';
        echo $this->miFormulario->formulario($atributos); {
            // ---------------- SECCION: Controles del Formulario -----------------------------------------------

            $miPaginaActual = $this->miConfigurador->getVariableConfiguracion('pagina');

            $rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
            $rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/";
            $rutaBloque.= $esteBloque['grupo'] . "/" . $esteBloque['nombre'];

            $directorio = $this->miConfigurador->getVariableConfiguracion("host");
            $directorio .= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
            $directorio .= $this->miConfigurador->getVariableConfiguracion("enlace");

            $variable = "pagina=" . $miPaginaActual;
            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);



            // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
            $esteCampo = "marcoContratos";
            $atributos ['id'] = $esteCampo;
            $atributos ["estilo"] = "jqueryui";
            $atributos ['tipoEtiqueta'] = 'inicio';
            $atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo);
            echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);
            unset($atributos);



            //****************************************************************************************
            //****************************************************************************************

            $datosConsultaSol = array(
                'proveedor' => $_REQUEST['id_proveedor'],
                'solicitud' => $_REQUEST['idSolicitud']
            );
                
           
            
            $cadenaSql = $this->miSql->getCadenaSql('consultarIdsolicitud', $datosConsultaSol);
            $id_solicitud = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda", $datosConsultaSol, 'consultarIdsolicitud');


            $cadenaSql = $this->miSql->getCadenaSql('consultar_respuesta', $id_solicitud[0][0]);
            $resultadoRespuesta = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");


            $cadenaSql = $this->miSql->getCadenaSql('consultar_proveedor', $_REQUEST ["usuario"]);
            $resultadoDoc = $frameworkRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");


            $cadenaSql = $this->miSql->getCadenaSql('informacionSolicitudAgoraNoCast', $_REQUEST['idSolicitud']);
            $resultadoOrdenadorInfo = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");

           
            

            $cadenaSql = $this->miSql->getCadenaSql ( 'informacionSolicitudAgoraNoCast', $_REQUEST['idSolicitud'] );
            $solicitudCotizacion = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
            
            $cadenaSql = $this->miSql->getCadenaSql ( 'dependenciaUdistritalById', $solicitudCotizacion[0]['jefe_dependencia'] );
            $resultadoDependencia = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
            	
            $cadenaSql = $this->miSql->getCadenaSql ( 'ordenadorUdistritalByIdCastDoc', $solicitudCotizacion[0]['ordenador_gasto'] );
            $resultadoOrdenador = $argoRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );


            
            $cadenaSql = $this->miSql->getCadenaSql('buscarUsuario', $resultadoOrdenadorInfo[0]['usuario_creo']);
            $resultadoUsuario = $frameworkRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
           

            $cadenaSql = $this->miSql->getCadenaSql ( 'informacionPersonaOrdenador', $resultadoOrdenador[0]['tercero_id'] );
            $datoAgoraOrdenador = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );

            $cadenaSql = $this->miSql->getCadenaSql ( 'buscarUsuarioDoc', $resultadoOrdenador[0]['tercero_id'] );
            $datoAgoraOrdenadorFrame = $frameworkRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
            
            $nombreOrdenador = $datoAgoraOrdenadorFrame[0]['nombre'];
            $apellidoOrdenador = $datoAgoraOrdenadorFrame[0]['apellido'];
            $identificacionOrdenador = $resultadoOrdenador[0]['tercero_id'];
            
            
            $numeroDocumento = $resultadoDoc[0]['identificacion'];
            
            
            
            

            $cadenaSql = $this->miSql->getCadenaSql('consultar_DatosProveedor', $numeroDocumento);
            $resultadoDats = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");

            $idProveedor = $resultadoDats[0]['id_proveedor'];


            $tipoPersona = $resultadoDats[0]['tipopersona'];
            $nombrePersona = $resultadoDats[0]['nom_proveedor'];
            $correo = $resultadoDats[0]['correo'];
            $direccion = $resultadoDats[0]['direccion'];


            $esteCampo = "marcoInfoContPer";
            $atributos ['id'] = $esteCampo;
            $atributos ["estilo"] = "jqueryui";
            $atributos ['tipoEtiqueta'] = 'inicio';
            $atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo);
            echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);
            {

                //INICIO INFORMACION
                echo "<span class='textoElegante textoGrande textoAzul'>Nombres: </span>";
                echo "<span class='textoElegante textoGrande textoGris'>" . $nombreOrdenador . "</span></br>";
                echo "<span class='textoElegante textoGrande textoAzul'>Apellidos : </span>";
                echo "<span class='textoElegante textoGrande textoGris'>" . $apellidoOrdenador . "</span></br>";
                echo "<span class='textoElegante textoGrande textoAzul'>Identificacion : </span>";
                echo "<span class='textoElegante textoGrande textoGris'>" . $identificacionOrdenador . "</span></br>";
                echo "<span class='textoElegante textoGrande textoAzul'>Cargo : </span>";
                echo "<span class='textoElegante textoGrande textoGris'>" . $resultadoOrdenador[0]['ordenador'] . "</span></br>";
                echo "<span class='textoElegante textoGrande textoAzul'>Dependencia Solicitante : </span>";
                echo "<span class='textoElegante textoGrande textoGris'>" . $resultadoDependencia[0]['jefe'] . "</span></br>";
                echo "<span class='textoElegante textoGrande textoAzul'>Solicitante : </span>";
                echo "<span class='textoElegante textoGrande textoGris'>" . $resultadoUsuario[0]['nombre']." ". $resultadoUsuario[0]['apellido'] . "</span></br>";
                //FIN INFORMACION
            }
            echo $this->miFormulario->marcoAgrupacion('fin', $atributos);

            if (isset($_REQUEST['tipoCotizacion']) && $_REQUEST['tipoCotizacion'] == 'BIEN') {
                $campo1 = "entregables";
                $campo2 = "plazoEntrega";
            } else {
                if (isset($_REQUEST['tipoCotizacion']) && $_REQUEST['tipoCotizacion'] == 'SERVICIO') {
                    $campo1 = "desServicio";
                    $campo2 = "detalleEjecucion";
                } else {
                    $campo1 = "entregablesdesServicio";
                    $campo2 = "entregaEjecucion";
                }
            }
            
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
            
            
//            $datosConsultaSol = array(
//            'proveedor' =>$_REQUEST['id_proveedor'],
//            'solicitud' => $_REQUEST['solicitud']
//            );
//        
        $cadenaSql = $this->miSql->getCadenaSql('consultarIdsolicitud', $datosConsultaSol);
        $id_solicitud= $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda", $datosConsultaSol, 'consultarIdsolicitud');
 
            $datosConsultaRes = array(
            	'solicitud' =>$id_solicitud[0][0],
            	'objeto' => $_REQUEST['idSolicitud']
            );

            $cadenaSql = $this->miSql->getCadenaSql('consultar_DatosRespuestaOrdenador', $datosConsultaRes);
            $resultadoDatosRes = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
            
            
            $cadenaSql = $this->miSql->getCadenaSql('consultar_DatosRespuestaOrdenadorResultado', $resultadoDatosRes[0]['resultado']);
            $resultadoDatosResCas = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
          

            $esteCampo = "marcoDatosSolicitudCot";
            $atributos ['id'] = $esteCampo;
            $atributos ["estilo"] = "jqueryui";
            $atributos ['tipoEtiqueta'] = 'inicio';
            $atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo);
            echo $this->miFormulario->marcoAgrupacion('inicio', $atributos); {

            $esteCampo = 'decision';
            $atributos ['id'] = $esteCampo;
            $atributos ['nombre'] = $esteCampo;
            $atributos ['tipo'] = 'text';
            $atributos ['estilo'] = 'jqueryui';
            $atributos ['marco'] = true;
            $atributos ['estiloMarco'] = '';
            $atributos ["etiquetaObligatorio"] = false;
            $atributos ['columnas'] = 1;
            $atributos ['dobleLinea'] = 0;
            $atributos ['tabIndex'] = $tab;
            $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);

            $atributos ['valor'] = $resultadoDatosResCas[0]['nombre'];

            $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
            $atributos ['deshabilitado'] = true;
            $atributos ['tamanno'] = 30;
            $atributos ['maximoTamanno'] = '30';
            $atributos ['anchoEtiqueta'] = 400;
            $tab ++;

            // Aplica atributos globales al control
            $atributos = array_merge($atributos, $atributosGlobales);
            echo $this->miFormulario->campoCuadroTexto($atributos);
            unset($atributos);
                
//                // ---------------- CONTROL: Lista Vigencia--------------------------------------------------------
//                $esteCampo = "decision";
//                $atributos ['nombre'] = $esteCampo;
//                $atributos ['id'] = $esteCampo;
//                $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
//                $atributos ["etiquetaObligatorio"] = true;
//                $atributos ['tab'] = $tab ++;
//                $atributos ['anchoEtiqueta'] = 250;
//                $atributos ['evento'] = '';
//                if (isset($estadoSolicitud)) {
//                    $atributos ['seleccion'] = $_REQUEST [$esteCampo];
//                } else {
//                    $atributos ['seleccion'] = - 1;
//                }
//                $atributos ['deshabilitado'] = false;
//                $atributos ['columnas'] = 1;
//                $atributos ['tamanno'] = 1;
//                $atributos ['ajax_function'] = "";
//                $atributos ['ajax_control'] = $esteCampo;
//                $atributos ['estilo'] = "jqueryui";
//                $atributos ['validar'] = "required";
//                $atributos ['limitar'] = false;
//                $atributos ['anchoCaja'] = 60;
//                $atributos ['miEvento'] = '';
//
//                $atributos = array_merge($atributos, $atributosGlobales);
//                echo $this->miFormulario->campoCuadroLista($atributos);
//                unset($atributos);
                // ----------------FIN CONTROL: Lista Vigencia--------------------------------------------------------
                // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                $esteCampo = "respuesta";
                $atributos ['id'] = $esteCampo;
                $atributos ['nombre'] = $esteCampo;
                $atributos ['tipo'] = 'text';
                $atributos ['estilo'] = 'jqueryui';
                $atributos ['marco'] = true;
                $atributos ['estiloMarco'] = '';
                $atributos ["etiquetaObligatorio"] = false;
                $atributos ['columnas'] = 120;
                $atributos ['filas'] = 8;
                $atributos ['dobleLinea'] = 0;
                $atributos ['tabIndex'] = $tab;
                $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
                $atributos ['deshabilitado'] = true;
                $atributos ['tamanno'] = 20;
                $atributos ['maximoTamanno'] = '';
                $atributos ['anchoEtiqueta'] = 220;
                $atributos ['textoEnriquecido'] = true; //Este atributo se coloca una sola vez en todo el formulario (ERROR paso de datos)

                    $atributos ['valor'] = $resultadoDatosRes[0]['respuesta'];
            
                $tab ++;

                // Aplica atributos globales al control
                $atributos = array_merge($atributos, $atributosGlobales);
                echo $this->miFormulario->campoTextArea($atributos);
                unset($atributos);



             
            }
            echo $this->miFormulario->marcoAgrupacion('fin');




            $atributos["id"] = "botones";
            $atributos["estilo"] = "marcoBotones widget";
            echo $this->miFormulario->division("inicio", $atributos);

            // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
            $esteCampo = 'botonRegresar';
            $atributos ['id'] = $esteCampo;
            $atributos ['enlace'] = $variable;
            $atributos ['tabIndex'] = 1;
            $atributos ['estilo'] = '';
            $atributos ['enlaceTexto'] = $this->lenguaje->getCadena($esteCampo);
            $atributos ['ancho'] = '10%';
            $atributos ['alto'] = '10%';
            $atributos ['redirLugar'] = true;
            echo $this->miFormulario->enlace($atributos);

      

            unset($atributos);
            echo $this->miFormulario->division("fin");
        }
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

        $valorCodificado = "action=" . $esteBloque ["nombre"];
        $valorCodificado .= "&pagina=" . $this->miConfigurador->getVariableConfiguracion('pagina');
        $valorCodificado .= "&bloque=" . $esteBloque ['nombre'];
        $valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
        $valorCodificado .= "&opcion=registrarRespuestaCot";
        $valorCodificado .= "&usuario=" . $_REQUEST['usuario'];

        /**
         * SARA permite que los nombres de los campos sean dinámicos.
         * Para ello utiliza la hora en que es creado el formulario para
         * codificar el nombre de cada campo. Si se utiliza esta técnica es necesario pasar dicho tiempo como una variable:
         * (a) invocando a la variable $_REQUEST ['tiempo'] que se ha declarado en ready.php o
         * (b) asociando el tiempo en que se está creando el formulario
         */
        $valorCodificado .= "&campoSeguro=" . $_REQUEST ['tiempo'];
        $valorCodificado .= "&tiempo=" . time();
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

        return true;
    }

}

$miSeleccionador = new FormularioRegistro($this->lenguaje, $this->miFormulario, $this->sql);

$miSeleccionador->miForm();
?>