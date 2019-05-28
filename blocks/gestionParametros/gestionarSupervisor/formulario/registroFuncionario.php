<?php

if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
}

class registrarForm {

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
        $miPaginaActual = $this->miConfigurador->getVariableConfiguracion('pagina');

        $directorio = $this->miConfigurador->getVariableConfiguracion("host");
        $directorio .= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
        $directorio .= $this->miConfigurador->getVariableConfiguracion("enlace");

        $rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
        $rutaBloque .= $this->miConfigurador->getVariableConfiguracion("site") . "/blocks/";
        $rutaBloque .= $esteBloque ['grupo'] . "/" . $esteBloque ['nombre'];

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
        $conexion = "estructura";
        $esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

        // Limpia Items Tabla temporal
        // ---------------- SECCION: Parámetros Generales del Formulario ----------------------------------
        $esteCampo = $esteBloque ['nombre'];

        $atributos ['id'] = $esteCampo;
        $atributos ['nombre'] = $esteCampo;
        

        /**
         * Nuevo a partir de la versión 1.0.0.2, se utiliza para crear de manera rápida el js asociado a
         * validationEngine.
         */
        $atributos ['validar'] = false;

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
        $atributos = array_merge($atributos);
        echo $this->miFormulario->formulario($atributos);
        // ---------------- SECCION: Controles del Formulario -----------------------------------------------
  


        $conexionFrameWork = "framework";
        $frameworkRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexionFrameWork);

        $cadenaSqlUnidad = $this->miSql->getCadenaSql("obtenerInfoUsuario", $_REQUEST['usuario']);
        $unidad = $frameworkRecursoDB->ejecutarAcceso($cadenaSqlUnidad, "busqueda");



       if (isset($_REQUEST ['dependencia']) && $_REQUEST ['dependencia'] != '') {
            $dependenciaCar = $_REQUEST ['dependencia'];

           $cadenaSql = $this->miSql->getCadenaSql('consultarDependenciaOikos',  $dependenciaCar);
           $dependencia = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
           $valueDep = $dependencia[0][0];



        } else {
            $dependenciaCar = '';
        }


        if( $_REQUEST['tipoFuncionario']=='2'){
                    $cadenaSql = $this->miSql->getCadenaSql('consultarFuncionarioGeneralOrdenador', $dependenciaCar);
                    $funcionario = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
        }
        else{
                    $cadenaSql = $this->miSql->getCadenaSql('consultarFuncionarioGeneralSupervisor', $dependenciaCar);
                   $funcionario = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");

            
               
        }

        $cadenaSqlUnidad = $this->miSql->getCadenaSql("obtenerInformacionElaborador", $_REQUEST['usuario']);
        $usuarioIn = $frameworkRecursoDB->ejecutarAcceso($cadenaSqlUnidad, "busqueda");
        

        echo "<div id='marcoDatosLoad2' style='width: 100%;height: 900px'>
                    <div style='width: 100%;height: 100px'>
                    </div>
                    <center><img src='" . $rutaBloque . "/images/loading.gif'".' width=20% height=20% vspace=15 hspace=3 >
                    </center>
                  </div>';

        if($_REQUEST['tipoFuncionario']==2){
            $tipofun='Ordenador';
        }
        else{
            $tipofun='Supervisor';
        }


        $esteCampo = "marcoDatosBasicosPerRe";
        $atributos ['id'] = $esteCampo;
        $atributos ["estilo"] = "jqueryui";
        $atributos ['tipoEtiqueta'] = 'inicio';
        $atributos ["leyenda"] = "Registrar ". $tipofun;
        echo $this->miFormulario->marcoAgrupacion('inicio', $atributos); {

            $atributos["id"]="botonReg";
            $atributos["estilo"]="widget textoPequenno";
            echo $this->miFormulario->division("inicio",$atributos);
            {
                $variable = "pagina=" . $miPaginaActual;
                $variable .= "&opcion=consultarDependencia";
                $variable .= "&tipoFuncionario=".$_REQUEST['tipoFuncionario'];
                $variable .= "&dependenciaFuncionario=" . $_REQUEST['dependencia'];
                $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                $esteCampo = 'botonRegresar';
                $atributos ['id'] = $esteCampo;
                $atributos ['enlace'] = $variable;
                $atributos ['tabIndex'] = 1;
                $atributos ['estilo'] = '';
                $atributos ['enlaceTexto'] = 'Regresar';
                $atributos ['ancho'] = '10%';
                $atributos ['alto'] = '10%';
                $atributos ['redirLugar'] = true;
                echo $this->miFormulario->enlace($atributos);
            }
            echo $this->miFormulario->division("fin");
            unset ( $atributos );
            

            $tipo = 'success';
            $mensaje =  "<br>Registro <b>". $tipofun ."</b>
             <br> <i>Dependencia</i> (<b>".$valueDep."</b>)
            </br><b>Responsable:</b> (" . $usuarioIn[0]['identificacion'] . " - " . $usuarioIn[0]['nombre'] . " " . $usuarioIn[0]['apellido'] . ")</center><br>";
            // ---------------- SECCION: Controles del Formulario -----------------------------------------------
            $esteCampo = 'mensaje';
            $atributos["id"] = $esteCampo; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
            $atributos["etiqueta"] = "";
            $atributos["estilo"] = "centrar";
            $atributos["tipo"] = $tipo;
            $atributos["mensaje"] = $mensaje;
            echo $this->miFormulario->cuadroMensaje($atributos);
            unset($atributos);



                $nuevafecha = strtotime ( '+1 day' , strtotime (  $funcionario[0]['fecha_fin'] ) ) ;
                $nuevafecha = date ( 'Y-m-d' , $nuevafecha );
        
                $fecha =  strtotime (  $funcionario[0]['fecha_fin'] ) ;
                $fecha = date ( 'Y-m-d' , $fecha );    
                         

                if ($fecha < date ('Y-m-d')  ||  !isset($funcionario[0]['fecha_fin'])) {
                    $visualizarRegistro='display:block';
                    $visualizarMensaje='display:none';
                }
                else{
                     $visualizarRegistro='display:none';
                     $visualizarMensaje='display:block';
                }
   
                $atributos ["id"] = "divisionSupervisorMensaje";
                $atributos = array_merge($atributos, $atributosGlobales);
                $atributos ["estiloEnLinea"] = $visualizarMensaje;
                echo $this->miFormulario->division("inicio", $atributos);
                unset($atributos);
                    {
                        $tipo = 'error';
                        $mensaje =  "
                        <b>No es posible realizar el registro de un nuevo Funcionario ya que actualmente existe uno activo.
                        Se podrá realizar el registro de un nuevo Funcionario un día después de la fecha fin del actual referente a: </b>
                        <br><br> <b>Nombre:</b> " .$funcionario[0]['nombre_funcionario'] . " <br>
                             <b>Documento:</b> CC " .$funcionario[0]['tercero_id'] . " <br>
                             <b>Fecha Inicio : </b>" .$funcionario[0]['fecha_inicio'] . " <br>
                             <b>Fecha Fin: </b>" .$funcionario[0]['fecha_fin'] . " <br>
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

                   }
                echo $this->miFormulario->division("fin");
                unset($atributos); 

                $atributos ["id"] = "divisionSupervisorRegistro";
                $atributos = array_merge($atributos, $atributosGlobales);
                $atributos ["estiloEnLinea"] = $visualizarRegistro;
                echo $this->miFormulario->division("inicio", $atributos);
                unset($atributos);
            {



                    $esteCampo = "marcoDatosBasicos";
                    $atributos ['id'] = $esteCampo;
                    $atributos ["estilo"] = "jqueryui";
                    $atributos ['tipoEtiqueta'] = 'inicio';
                    $atributos ["leyenda"] = "Datos Funcionario";
                    echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);


                        $esteCampo = 'dependencia';
                        $atributos ['columnas'] = 1;
                        $atributos ['nombre'] = $esteCampo;
                        $atributos ['id'] = $esteCampo;
                        $atributos ['evento'] = '';
                        $atributos ['deshabilitado'] = true;
                        $atributos ["etiquetaObligatorio"] = false;
                        $atributos ['tab'] = $tab;
                        $atributos ['tamanno'] = 1;
                        $atributos ['estilo'] = 'jqueryui';
                        $atributos ['validar'] = ' ';
                        $atributos ['limitar'] = false;
                        $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                        $atributos ['anchoEtiqueta'] = 213;

                        if (isset($_REQUEST [$esteCampo])) {
                            $atributos ['seleccion'] = $_REQUEST [$esteCampo];
                        } else {
                            $atributos ['seleccion'] = - 1;
                        }

                        // $atributos ['matrizItems'] = $matrizItems;
                        // Utilizar lo siguiente cuando no se pase un arreglo:
                        $atributos ['baseDatos'] = 'estructura';
                        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("dependenciasConsultadasAll");
                        $tab ++;
                        $atributos = array_merge($atributos, $atributosGlobales);
                        echo $this->miFormulario->campoCuadroLista($atributos);
                        unset($atributos);

                        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                        //---------------------------------TEMP-------------------------
                        $esteCampo = 'dependencia_hidden';
                        $atributos ['id'] = $esteCampo;
                        $atributos ['nombre'] = $esteCampo;
                        $atributos ['tipo'] = 'hidden';
                        $atributos ['estilo'] = 'jqueryui';

                        if (isset($_REQUEST ['dependencia'])) {
                            $atributos ['valor'] = $_REQUEST ['dependencia'];
                        } else {
                            $atributos ['valor'] = '';
                        }

                        // Aplica atributos globales al control
                        $atributos = array_merge($atributos, $atributosGlobales);
                        echo $this->miFormulario->campoCuadroTexto($atributos);
                        unset($atributos);
                        //---------------------------------TEMP-------------------------




                        $esteCampo = "marcoSearchByNIT";
                        $atributos ['id'] = $esteCampo;
                        $atributos ["estilo"] = "jqueryui";
                        $atributos ['tipoEtiqueta'] = 'inicio';
                        $atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
                        echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
                    
                            // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                            $esteCampo = 'nit_proveedor';
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
                            $atributos ['validar'] = 'required';
                            $atributos ['textoFondo'] = 'Ingrese Mínimo 3 Caracteres de Búsqueda';
                            if (isset($_REQUEST [$esteCampo])) {
                                $atributos ['valor'] = $_REQUEST [$esteCampo];
                            } else {
                                $atributos ['valor'] = '';
                            }
                            $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
                            $atributos ['deshabilitado'] = false;
                            $atributos ['tamanno'] = 60;
                            $atributos ['maximoTamanno'] = '';
                            $atributos ['anchoEtiqueta'] = 200;
                            $tab ++;
                            // Aplica atributos globales al control
                            $atributos = array_merge($atributos, $atributosGlobales);
                            echo $this->miFormulario->campoCuadroTexto($atributos);
                            unset($atributos);
                            $esteCampo = 'id_proveedor';
                            $atributos ["id"] = $esteCampo; // No cambiar este nombre
                            $atributos ["tipo"] = "hidden";
                            $atributos ['estilo'] = '';
                            $atributos ["obligatorio"] = false;
                            $atributos ['marco'] = true;
                            $atributos ["etiqueta"] = "";
                            if (isset($_REQUEST [$esteCampo])) {
                                $atributos ['valor'] = $_REQUEST [$esteCampo];
                            } else {
                                $atributos ['valor'] = '';
                            }
                            $atributos = array_merge($atributos, $atributosGlobales);
                            echo $this->miFormulario->campoCuadroTexto($atributos);
                            unset($atributos);

                           echo $this->miFormulario->marcoAgrupacion ( 'fin' );
                    
              
                    
                        //---------------------------------TEMP-------------------------

                               

                                $esteCampo = 'acta_aprobacion';
                                $atributos ['id'] = $esteCampo;
                                $atributos ['nombre'] = $esteCampo;
                                $atributos ['tipo'] = 'text';
                                $atributos ['estilo'] = 'jqueryui';
                                $atributos ['marco'] = true;
                                $atributos ['estiloMarco'] = '';
                                $atributos ["etiquetaObligatorio"] = true;
                                $atributos ['columnas'] = 140;
                                $atributos ['filas'] = 2;
                                $atributos ['dobleLinea'] = 0;
                                $atributos ['tabIndex'] = $tab;
                                $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                                $atributos ['validar'] = 'required,minSize[1]';
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
                                $tab ++;

                                // Aplica atributos globales al control
                                $atributos = array_merge($atributos, $atributosGlobales);
                                echo $this->miFormulario->campoTextArea($atributos);
                                unset($atributos);


                        $esteCampo = "marcoDatosBasicosDate";
                        $atributos ['id'] = $esteCampo;
                        $atributos ["estilo"] = "jqueryui";
                        $atributos ['tipoEtiqueta'] = 'inicio';
                        $atributos ["leyenda"] = "Datos de Vigencia";
                        echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);



                        $tipo = 'warning';
                        $mensaje =  "Atención</b>
                        </br><i>La fecha de inicio de la vigencia del cargo no se pueden modificar, por favor tenga cuidado con este aspecto</i>";
                        // ---------------- SECCION: Controles del Formulario -----------------------------------------------
                        $esteCampo = 'mensaje';
                        $atributos["id"] = $esteCampo; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
                        $atributos["etiqueta"] = "";
                        $atributos["estilo"] = "centrar";
                        $atributos["tipo"] = $tipo;
                        $atributos["mensaje"] = $mensaje;
                        echo $this->miFormulario->cuadroMensaje($atributos);
                        unset($atributos);

                        $esteCampo = 'fecha_inicio';
                       $atributos ['id'] = $esteCampo;
                        $atributos ['nombre'] = $esteCampo;
                        $atributos ['tipo'] = 'text';
                        $atributos ['estilo'] = 'jqueryui';
                        $atributos ['marco'] = true;
                        $atributos ['estiloMarco'] = '';
                        $atributos ["etiquetaObligatorio"] = true;
                        $atributos ['columnas'] = 2;
                        $atributos ['filas'] = 5;
                        $atributos ['dobleLinea'] = 0;
                        $atributos ['tabIndex'] = $tab;
                        $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                        $atributos ['validar'] = 'required, minSize[10],maxSize[10]';
                        $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
                        $atributos ['deshabilitado'] = false;
                        $atributos ['tamanno'] = 10;
                        $atributos ['maximoTamanno'] = '';
                        $atributos ['anchoEtiqueta'] = 220;

                        if (isset($_REQUEST [$esteCampo])) {
                            $atributos ['valor'] = $_REQUEST [$esteCampo];
                        } else {
                            $atributos ['valor'] =  '';
                        }
                       $tab ++;

                        // Aplica atributos globales al control
                        $atributos = array_merge($atributos, $atributosGlobales);
                        echo $this->miFormulario->campoCuadroTexto($atributos);
                        unset($atributos);


                        $esteCampo = 'fecha_fin';
                        $atributos ['id'] = $esteCampo;
                        $atributos ['nombre'] = $esteCampo;
                        $atributos ['tipo'] = 'text';
                        $atributos ['estilo'] = 'jqueryui';
                        $atributos ['marco'] = true;
                        $atributos ['estiloMarco'] = '';
                        $atributos ["etiquetaObligatorio"] = true;
                        $atributos ['columnas'] = 2;
                        $atributos ['filas'] = 5;
                        $atributos ['dobleLinea'] = 0;
                        $atributos ['tabIndex'] = $tab;
                        $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                        $atributos ['validar'] = 'required, minSize[10],maxSize[10]';
                        $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
                        $atributos ['deshabilitado'] = false;
                        $atributos ['tamanno'] = 10;
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
                        echo $this->miFormulario->campoCuadroTexto($atributos);
                        unset($atributos);

                        echo $this->miFormulario->marcoAgrupacion('fin');


                  
               

                        $esteCampo = 'fecha_inicio_validacion';
                        $atributos ["id"] = $esteCampo; // No cambiar este nombre
                        $atributos ["tipo"] = "hidden";
                        $atributos ['estilo'] = '';
                        $atributos ["obligatorio"] = false;
                        $atributos ['marco'] = true;
                        $atributos ["etiqueta"] = "";
                        $atributos ['valor'] = $this->cambiafecha_format($nuevafecha);
                        $atributos = array_merge($atributos, $atributosGlobales);
                        echo $this->miFormulario->campoCuadroTexto($atributos);            

                        

                                $atributos ["id"] = "botones"; 
                                $atributos ["estilo"] = "marcoBotones";
                                echo $this->miFormulario->division ( "inicio", $atributos );


                                // -----------------CONTROL: Botón ----------------------------------------------------------------
                                $esteCampo = 'botonRegistrar';
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
                                // ---------------------------------------------------------
                                echo $this->miFormulario->division("fin");

                  }
                  echo $this->miFormulario->division("fin");
                  unset($atributos); 

                 
            echo $this->miFormulario->marcoAgrupacion('fin');

           

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
        // Paso 1: crear el listado de variables

        $valorCodificado = "action=" . $esteBloque ["nombre"];
        $valorCodificado .= "&pagina=" . $this->miConfigurador->getVariableConfiguracion('pagina');
        $valorCodificado .= "&bloque=" . $esteBloque ['nombre'];
        $valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
        $valorCodificado .= "&opcion=registroFuncionario";
        $valorCodificado .= "&tipoFuncionario= " . $_REQUEST['tipoFuncionario'];    
        $valorCodificado .= "&nombreDependencia= " . $valueDep;
        $valorCodificado .= "&usuario=" . $_REQUEST ['usuario'];
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
        $atributos ['marco'] = false;
        $atributos ["etiqueta"] = "";
        $atributos ["valor"] = $valorCodificado;
        echo $this->miFormulario->campoCuadroTexto($atributos);
        unset($atributos);

        $atributos ['marco'] = false;
        $atributos ['tipoEtiqueta'] = 'fin';
        echo $this->miFormulario->formulario($atributos);
    }

}

$miSeleccionador = new registrarForm($this->lenguaje, $this->miFormulario, $this->sql);

$miSeleccionador->miForm();
?>
