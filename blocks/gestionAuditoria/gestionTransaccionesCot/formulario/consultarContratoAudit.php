<?php

if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
}

require_once ("plugin/geshi/geshi.php");
require_once ("plugin/phpsqlparser/PHPSQLParser.php");
//include_once 'geshi.php';

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

        // -------------------------------------------------------------------------------------------------
        $conexion = "contractual";
        $esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
        $conexionFrameWork = "framework";
        $DBFrameWork = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexionFrameWork);
        $conexionSICA = "sicapital";
        $DBSICA = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexionSICA);


        $conexionAgora = "agora";
        $esteRecursoDBAgora = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexionAgora);

        $conexionCore = "core";
        $esteRecursoDBCore = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexionCore);

        $miPaginaActual = $this->miConfigurador->getVariableConfiguracion('pagina');

        $directorio = $this->miConfigurador->getVariableConfiguracion("host");
        $directorio .= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
        $directorio .= $this->miConfigurador->getVariableConfiguracion("enlace");


        $rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
        $rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/";
        $rutaBloque.= $esteBloque['grupo'] . "/" . $esteBloque['nombre'];


        $id_usuario = $_REQUEST['usuario'];
        $cadenaSqlUnidad = $this->miSql->getCadenaSql("obtenerInfoUsuario", $id_usuario);
        $unidad = $DBFrameWork->ejecutarAcceso($cadenaSqlUnidad, "busqueda");

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
        $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo);
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


        //$cadenaSqlelaboro = $this->miSql->getCadenaSql('obtenerInformacionElaborador', $_REQUEST ['responsable']);
        //$usuario = $DBFrameWork->ejecutarAcceso($cadenaSqlelaboro, "busqueda");

        $cadenaSqlevento = $this->miSql->getCadenaSql('obtenerInfoAuditoriaAgoraIdLog', $_REQUEST ['id']);
        $evento = $DBFrameWork->ejecutarAcceso($cadenaSqlevento, "busqueda");





        $usuarioRol = false;
        if($usuarioRol){

            //------- USUARIO ----------------------------------------------------------------
            $json_query = json_decode($evento[0]['query'], true);
            $mensaje_query = "<h2>EVENTOS EJECUTADOS EN BASE DE DATOS</h2><h3>(TRANSACCIÓN)</h3>";
            $c = 0;
            $i = 1;
            while ($c < count($json_query)){
                $json_query_dec[$c] = $this->miConfigurador->fabricaConexiones->crypto->decodificar($json_query[$c]);

                $source = $json_query_dec[$c];
                $language = 'sql';

                $parser = new PHPSQLParser();
                $parsed = $parser->parse($source, true);
                //print_r($parsed);
                //var_dump($parsed);
                if (strpos($source, 'INSERT') !== false) {


                    //****************************************************** INSERT **********************************************************
                    //************************************************************************************************************************
                    $k = 0;
                    $t = 0;
                    while ($k < count($parsed['INSERT'][0]['columns'])){

                        $field [$t++] = $parsed['INSERT'][0]['columns'][$k]['base_expr'];
                        $k++;
        
                    }


                    $k = 0;
                    $t = 0;
                    while ($k < count($parsed['VALUES'][0]['data'])){

                        if($parsed['VALUES'][0]['data'][$k]['expr_type'] === 'reserved'){
                            $data [$t++] = $parsed['VALUES'][0]['data'][$k]['base_expr'];

                            $contr = $k + 1;
                            if($parsed['VALUES'][0]['data'][$contr]['expr_type'] === 'bracket_expression'){
                                $k = $k + 2;
                            }else{
                                $k++;
                            }

                            
                        }else{
                            $data [$t++] = $parsed['VALUES'][0]['data'][$k]['base_expr'];
                            $k++;
                        }
                    }

                    $p = 0;


                    $mensaje_query .= '           
                                        
                                        <section>
                                            <h2 class="acc_title_bar"><a href="#">'."ACCIÓN ".$i." - (INSERT):".'</a></h2>
                                            <div class="acc_container">
                                                <div class="block" align="center">
                    ';

                    $mensaje_query .= '<table id="tablaAU2'.$i.'" class="table1" width="100%" >';
                    $mensaje_query .= "<thead>";
                    $mensaje_query .= "<tr>
                                        <th width='30%' >Campo</th>
                                        <th width='70%' >Valor</th> 
                                      </tr>";
                    $mensaje_query .= "</thead>
                                       <tbody>";      
                    while($p < count($field)){
                        if($data[$p] != 'currval'){
                            $mensaje_query .= "<tr>
                                <td>".$field[$p]."</td>
                                <td>".$data[$p]."</td>
                              </tr>";
                        }
                        
                        $p++;
                    }
                    $mensaje_query .= "</tbody>
                                       </table>";


                    $mensaje_query .= '</div>
                                                </div>
                                            </section>';


                    //************************************************************************************************************************
                    //************************************************************************************************************************                        

                }else if (strpos($source, 'UPDATE') !== false){


                    //****************************************************** UPDATE **********************************************************
                    //************************************************************************************************************************

                    $k = 0;
                    $t = 0;
                    while ($k < count($parsed['SET'])){
                        $dateEx = explode("=", $parsed['SET'][$k]['base_expr']);
                        $field [$k] = $dateEx[0];
                        $data [$k] = $dateEx[1];
                        $k++;
        
                    }

                    $p = 0;


                    $mensaje_query .= '           
                                        
                                        <section>
                                            <h2 class="acc_title_bar"><a href="#">'."ACCIÓN ".$i." - (UPDATE):".'</a></h2>
                                            <div class="acc_container">
                                                <div class="block" align="center">
                    ';

                    $mensaje_query .= '<table id="tablaAU2'.$i.'" class="table1" width="100%" >';
                    $mensaje_query .= "<thead>";
                    $mensaje_query .= "<tr>
                                        <th width='30%' >Campo</th>
                                        <th width='70%' >Valor</th> 
                                      </tr>";
                    $mensaje_query .= "</thead>
                                       <tbody>";      
                    while($p < count($field)){
                        if($data[$p] != 'currval'){
                            $mensaje_query .= "<tr>
                                <td>".$field[$p]."</td>
                                <td>".$data[$p]."</td>
                              </tr>";
                        }
                        
                        $p++;
                    }
                    $mensaje_query .= "</tbody>
                                       </table>";


                    $mensaje_query .= '</div>
                                                </div>
                                            </section>';

                    //************************************************************************************************************************
                    //************************************************************************************************************************ 


                }else{
                    //****************************************************** DELETE **********************************************************
                    //************************************************************************************************************************

                    $field [0] = 'DELETE';
                    $data [0] = $parsed['DELETE']['TABLES'][0];

                    $k = 1;
                    $t = 1;
                    while ($k < count($parsed['WHERE'])){
                        $field [$k] = $parsed['WHERE'][$k]['base_expr'];
                        $data [$k] = $parsed['WHERE'][$k]['base_expr'];
                        $k++;
        
                    }


                    $p = 0;


                    $mensaje_query .= '           
                                        
                                        <section>
                                            <h2 class="acc_title_bar"><a href="#">'."ACCIÓN ".$i." - (DELETE):".'</a></h2>
                                            <div class="acc_container">
                                                <div class="block" align="center">
                    ';

                    $mensaje_query .= '<table id="tablaAU2'.$i.'" class="table1" width="100%" >';
                    $mensaje_query .= "<thead>";
                    $mensaje_query .= "<tr>
                                        <th width='30%' >Campo</th>
                                        <th width='70%' >Valor</th> 
                                      </tr>";
                    $mensaje_query .= "</thead>
                                       <tbody>";      
                    while($p < count($field)){
                        if($data[$p] != 'currval'){

                            if($p < 1){

                                $mensaje_query .= "<tr>
                                <td>".$field[$p]."</td>
                                <td>".$data[$p]."</td>
                              </tr>";

                            }else{

                                $mensaje_query .= "<tr>
                                <td colspan='2'>".$field[$p]."</td>
                              </tr>";

                            }
                            
                        }
                        
                        $p++;
                    }
                    $mensaje_query .= "</tbody>
                                       </table>";


                    $mensaje_query .= '</div>
                                                </div>
                                            </section>';

                    //************************************************************************************************************************
                    //************************************************************************************************************************ 
                }

                $data = null;
                $field = null;
                $i++;
                $c++;
            }


            $_REQUEST ['tipo_log'] = $evento[0]['tipo_log'];
            $_REQUEST ['modulo'] = $evento[0]['modulo'];
            $_REQUEST ['numero_cotizacion'] = $evento[0]['numero_cotizacion'];      
            $_REQUEST ['query'] = $mensaje_query;
            $_REQUEST ['host'] = $evento[0]['host'];
            $_REQUEST ['id_responsable'] = $evento[0]['id_usuario']; 
            $_REQUEST ['data'] = $evento[0]['data'];
            setlocale(LC_TIME,"es_ES.UTF-8");
            $_REQUEST ['date_evento'] = strftime("El día %A (%d) de %B del %Y, a las %H:%M horas", strtotime($evento[0]['fecha_log'])); 

             
            //---------------------------------------------------------------------------------------

        }else{

            //------- DESARROLLADOR ----------------------------------------------------------------
            $json_query = json_decode($evento[0]['query'], true);
            $mensaje_query = "<h2>EVENTOS EJECUTADOS EN BASE DE DATOS</h2><h3>(TRANSACCIÓN)</h3>";
            $c = 0;
            $i = 1;
            while ($c < count($json_query)){
                $json_query_dec[$c] = $this->miConfigurador->fabricaConexiones->crypto->decodificar($json_query[$c]);

                $mensaje_query .= "----------------------<br>";
                $mensaje_query .= "SCRIPT ".$i.": <br>";
                $mensaje_query .= "----------------------<br>";
                $source = $json_query_dec[$c];
                $source = str_replace("<", "+", $source);
                $language = 'sql';
                $geshi = new GeSHi($source, $language);
                $geshi->set_header_type(GESHI_HEADER_DIV);
                $mensaje_query .= "" . $geshi->parse_code() . "";

                $i++;
                $c++;
            }      

            $mensaje_data = "";
            if($evento[0]['data'] != null && $evento[0]['modulo'] != 'MCOTP' && $evento[0]['modulo'] != 'MOCOTP' && $evento[0]['modulo'] != 'SMCOPT'){
                
                $json_data = json_decode($evento[0]['data'], true);
                $mensaje_data = "<h2>DATA</h2>";
                $i = 1;

                foreach ($json_data as $item => $value){

                    $value = $this->miConfigurador->fabricaConexiones->crypto->decodificar($value);

                    $param = json_decode($value);

                   $tbDt = '                                 
                        <section>
                            <h2 class="acc_title_bar"><a href="#">'."INFORMACIÓN (".$item."):".'</a></h2>
                            <div class="acc_container">
                                <div class="block" align="center">
                    ';

                    $tbDt .= '<table id="tablaAU22'.$i.'" class="table1" width="100%" >';
                    $tbDt .= "<thead>";
                    $tbDt .= "<tr>
                                        <th width='30%' >Campo</th>
                                        <th width='70%' >Valor</th> 
                                      </tr>";
                    $tbDt .= "</thead>
                                       <tbody>";   
                    foreach ($param as $par => $val){
                        if(is_numeric($par)){
                            continue;
                        }
                        $val = str_replace("<", "+", $val);
                        if($val == 't'){
                            $val = 'TRUE';
                        }elseif($val == 'f'){
                            $val = 'FALSE';
                        }
                        $tbDt .= "<tr><td>". $par . "</td><td>" . $val . "</td></tr>";
                    }
                    $tbDt .= "</tbody></table>

                    </div>
                    </div>
                    </section>";

                    if(is_numeric($value)){

                    }

                    $mensaje_data .= $tbDt;
                    $i++;
                } 

            }



            $_REQUEST ['tipo_log'] = $evento[0]['tipo_log'];
            $_REQUEST ['modulo'] = $evento[0]['modulo'];
            $_REQUEST ['numero_cotizacion'] = $evento[0]['numero_cotizacion'];        
            $_REQUEST ['query'] = $mensaje_query;
            $_REQUEST ['host'] = $evento[0]['host'];
            $_REQUEST ['id_responsable'] = $evento[0]['id_usuario'];
            $_REQUEST ['data'] = $mensaje_data;
            setlocale(LC_TIME,"es_ES.UTF-8");
            $_REQUEST ['date_evento'] = strftime("El día %A (%d) de %B del %Y, a las %H:%M horas", strtotime($evento[0]['fecha_log'])); 
            
            //---------------------------------------------------------------------------------------

        }




        echo "<div id='marcoDatosLoad' style='width: 100%;height: 900px'>
            <div style='width: 100%;height: 100px'>
            </div>
            <center><img src='" . $rutaBloque . "/images/loading.gif'".' width=20% height=20% vspace=15 hspace=3 >
            </center>
          </div>';




        $esteCampo = "marcoDatosBasicosDt";
        $atributos ['id'] = $esteCampo;
        $atributos ["estilo"] = "jqueryui";
        $atributos ['tipoEtiqueta'] = 'inicio';
        $atributos ["leyenda"] = "CONSULTAR AUDITORÍA COTIZACIÓN <br>";
        
        echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);
        unset($atributos); 

           
                $variable = "pagina=" . $miPaginaActual;
                $variable .= "&opcion=logCot";

                $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);


                if($evento[0]['tipo_log'] === 'MODIFICACION' && $evento[0]['modulo'] != 'SMCOTP' && $evento[0]['modulo'] != 'MCOTP' && $evento[0]['modulo'] != 'MOCOTP'){
                    $registroEve = false;
                    $modificacionEve = true;
                }else{
                    $registroEve = true;
                    $modificacionEve = false;
                }

                if($registroEve){


                    $esteCampo = "agrupaInfoReg";
                    $atributos ['id'] = $esteCampo;
                    $atributos ["estilo"] = "jqueryui";
                    $atributos ['leyenda'] = "Información Auditoría";
                    echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);

                        unset($atributos);
                        $esteCampo = 'tipo_log';
                        $atributos ['id'] = $esteCampo;
                        $atributos ['nombre'] = $esteCampo;
                        $atributos ['tipo'] = 'text';
                        $atributos ['estilo'] = 'jqueryui';
                        $atributos ['marco'] = true;
                        $atributos ['estiloMarco'] = '';
                        $atributos ["etiquetaObligatorio"] = true;
                        $atributos ['columnas'] = 1;
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
                        $atributos ['tamanno'] = 80;
                        $atributos ['maximoTamanno'] = '';
                        $atributos ['anchoEtiqueta'] = 250;
                        $tab ++;

                        // Aplica atributos globales al control
                        $atributos = array_merge($atributos, $atributosGlobales);
                        echo $this->miFormulario->campoCuadroTexto($atributos);
                        unset($atributos);


                        $esteCampo = 'modulo';
                        $atributos ['id'] = $esteCampo;
                        $atributos ['nombre'] = $esteCampo;
                        $atributos ['tipo'] = 'text';
                        $atributos ['estilo'] = 'jqueryui';
                        $atributos ['marco'] = true;
                        $atributos ['estiloMarco'] = '';
                        $atributos ["etiquetaObligatorio"] = true;
                        $atributos ['columnas'] = 1;
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
                        $atributos ['tamanno'] = 80;
                        $atributos ['maximoTamanno'] = '';
                        $atributos ['anchoEtiqueta'] = 250;
                        $tab ++;

                        // Aplica atributos globales al control
                        $atributos = array_merge($atributos, $atributosGlobales);
                        echo $this->miFormulario->campoCuadroTexto($atributos);
                        unset($atributos);

                        $esteCampo = 'numero_cotizacion';
                        $atributos ['id'] = $esteCampo;
                        $atributos ['nombre'] = $esteCampo;
                        $atributos ['tipo'] = 'text';
                        $atributos ['estilo'] = 'jqueryui';
                        $atributos ['marco'] = true;
                        $atributos ['estiloMarco'] = '';
                        $atributos ["etiquetaObligatorio"] = true;
                        $atributos ['columnas'] = 1;
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
                        $atributos ['tamanno'] = 80;
                        $atributos ['maximoTamanno'] = '';
                        $atributos ['anchoEtiqueta'] = 250;
                        $tab ++;

                        // Aplica atributos globales al control
                        $atributos = array_merge($atributos, $atributosGlobales);
                        echo $this->miFormulario->campoCuadroTexto($atributos);
                        unset($atributos);

                        if($usuarioRol){

                            $esteCampo = "agrupaInfoReg";
                            $atributos ['id'] = $esteCampo;
                            $atributos ["estilo"] = "jqueryui";
                            $atributos ['leyenda'] = "Información Afectada";
                            echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);


                                $controlAd = 0;


                                $esteCampo = "marcoDescripcionProductoAdendaRes";
                                $atributos ['id'] = $esteCampo;
                                $atributos ["estilo"] = "jqueryui";
                                $atributos ['tipoEtiqueta'] = 'inicio';
                                $atributos ["leyenda"] = "Afectaciones";
                                echo $this->miFormulario->marcoAgrupacion('inicio', $atributos); 
                                        
                                        
                                        ?>    
                                            
                                            
                                        <div class="bwl_acc_container" id="accordionR">
                                                <div class="accordion_search_container">
                                                    <input type="text" class="accordion_search_input_box search_icon" value="" placeholder="Search ..."/>
                                                </div> <!-- end .bwl_acc_container -->
                                                <div class="search_result_container"></div> <!-- end .search_result_container -->           
                                                                                
                                        <?php

                                                    echo $mensaje_query;

                                        ?>

                                                </div>
                                        </div>


                                        <?php





                            echo $this->miFormulario->marcoAgrupacion('fin');

                        }else{
                            $esteCampo = 'query';
                            $atributos ['id'] = $esteCampo;
                            $atributos ['nombre'] = $esteCampo;
                            $atributos ['tipo'] = 'text';
                            $atributos ['estilo'] = 'jqueryui';
                            $atributos ['marco'] = true;
                            $atributos ['estiloMarco'] = '';
                            $atributos ["etiquetaObligatorio"] = true;
                            $atributos ['columnas'] = 240;
                            $atributos ['filas'] = 2;
                            $atributos ['dobleLinea'] = 0;
                            $atributos ['tabIndex'] = $tab;
                            $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                            $atributos ['validar'] = 'minSize[1]';
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
                        }

                        

                        $esteCampo = 'host';
                        $atributos ['id'] = $esteCampo;
                        $atributos ['nombre'] = $esteCampo;
                        $atributos ['tipo'] = 'text';
                        $atributos ['estilo'] = 'jqueryui';
                        $atributos ['marco'] = true;
                        $atributos ['estiloMarco'] = '';
                        $atributos ["etiquetaObligatorio"] = true;
                        $atributos ['columnas'] = 1;
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
                        $atributos ['tamanno'] = 70;
                        $atributos ['maximoTamanno'] = '';
                        $atributos ['anchoEtiqueta'] = 250;
                        $tab ++;

                        // Aplica atributos globales al control
                        $atributos = array_merge($atributos, $atributosGlobales);
                        echo $this->miFormulario->campoCuadroTexto($atributos);
                        unset($atributos);

                        $esteCampo = 'id_responsable';
                        $atributos ['id'] = $esteCampo;
                        $atributos ['nombre'] = $esteCampo;
                        $atributos ['tipo'] = 'text';
                        $atributos ['estilo'] = 'jqueryui';
                        $atributos ['marco'] = true;
                        $atributos ['estiloMarco'] = '';
                        $atributos ["etiquetaObligatorio"] = true;
                        $atributos ['columnas'] = 1;
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
                        $atributos ['tamanno'] = 70;
                        $atributos ['maximoTamanno'] = '';
                        $atributos ['anchoEtiqueta'] = 250;
                        $tab ++;

                        // Aplica atributos globales al control
                        $atributos = array_merge($atributos, $atributosGlobales);
                        echo $this->miFormulario->campoCuadroTexto($atributos);
                        unset($atributos);

                        $esteCampo = 'date_evento';
                        $atributos ['id'] = $esteCampo;
                        $atributos ['nombre'] = $esteCampo;
                        $atributos ['tipo'] = 'text';
                        $atributos ['estilo'] = 'jqueryui';
                        $atributos ['marco'] = true;
                        $atributos ['estiloMarco'] = '';
                        $atributos ["etiquetaObligatorio"] = true;
                        $atributos ['columnas'] = 1;
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
                        $atributos ['tamanno'] = 70;
                        $atributos ['maximoTamanno'] = '';
                        $atributos ['anchoEtiqueta'] = 250;
                        $tab ++;

                        // Aplica atributos globales al control
                        $atributos = array_merge($atributos, $atributosGlobales);
                        echo $this->miFormulario->campoCuadroTexto($atributos);
                        unset($atributos);

                    echo $this->miFormulario->marcoAgrupacion('fin');
                        
                }



                if($modificacionEve){



                    $esteCampo = "agrupaInfoMod";
                    $atributos ['id'] = $esteCampo;
                    $atributos ["estilo"] = "jqueryui";
                    $atributos ['leyenda'] = "Información Auditoría";
                    echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);

                        unset($atributos);
                        $esteCampo = 'tipo_log';
                        $atributos ['id'] = $esteCampo;
                        $atributos ['nombre'] = $esteCampo;
                        $atributos ['tipo'] = 'text';
                        $atributos ['estilo'] = 'jqueryui';
                        $atributos ['marco'] = true;
                        $atributos ['estiloMarco'] = '';
                        $atributos ["etiquetaObligatorio"] = true;
                        $atributos ['columnas'] = 1;
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
                        $atributos ['tamanno'] = 80;
                        $atributos ['maximoTamanno'] = '';
                        $atributos ['anchoEtiqueta'] = 250;
                        $tab ++;

                        // Aplica atributos globales al control
                        $atributos = array_merge($atributos, $atributosGlobales);
                        echo $this->miFormulario->campoCuadroTexto($atributos);
                        unset($atributos);


                        $esteCampo = 'modulo';
                        $atributos ['id'] = $esteCampo;
                        $atributos ['nombre'] = $esteCampo;
                        $atributos ['tipo'] = 'text';
                        $atributos ['estilo'] = 'jqueryui';
                        $atributos ['marco'] = true;
                        $atributos ['estiloMarco'] = '';
                        $atributos ["etiquetaObligatorio"] = true;
                        $atributos ['columnas'] = 1;
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
                        $atributos ['tamanno'] = 80;
                        $atributos ['maximoTamanno'] = '';
                        $atributos ['anchoEtiqueta'] = 250;
                        $tab ++;

                        // Aplica atributos globales al control
                        $atributos = array_merge($atributos, $atributosGlobales);
                        echo $this->miFormulario->campoCuadroTexto($atributos);
                        unset($atributos);

                        $esteCampo = 'numero_cotizacion';
                        $atributos ['id'] = $esteCampo;
                        $atributos ['nombre'] = $esteCampo;
                        $atributos ['tipo'] = 'text';
                        $atributos ['estilo'] = 'jqueryui';
                        $atributos ['marco'] = true;
                        $atributos ['estiloMarco'] = '';
                        $atributos ["etiquetaObligatorio"] = true;
                        $atributos ['columnas'] = 1;
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
                        $atributos ['tamanno'] = 80;
                        $atributos ['maximoTamanno'] = '';
                        $atributos ['anchoEtiqueta'] = 250;
                        $tab ++;

                        // Aplica atributos globales al control
                        $atributos = array_merge($atributos, $atributosGlobales);
                        echo $this->miFormulario->campoCuadroTexto($atributos);
                        unset($atributos);

                        if($usuarioRol){

                            $esteCampo = "agrupaInfoReg";
                            $atributos ['id'] = $esteCampo;
                            $atributos ["estilo"] = "jqueryui";
                            $atributos ['leyenda'] = "Información Afectada";
                            echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);


                                $controlAd = 0;


                                $esteCampo = "marcoDescripcionProductoAdendaRes";
                                $atributos ['id'] = $esteCampo;
                                $atributos ["estilo"] = "jqueryui";
                                $atributos ['tipoEtiqueta'] = 'inicio';
                                $atributos ["leyenda"] = "Afectaciones";
                                echo $this->miFormulario->marcoAgrupacion('inicio', $atributos); 
                                        
                                        
                                        ?>    
                                            
                                            
                                        <div class="bwl_acc_container" id="accordionR">
                                                <div class="accordion_search_container">
                                                    <input type="text" class="accordion_search_input_box search_icon" value="" placeholder="Search ..."/>
                                                </div> <!-- end .bwl_acc_container -->
                                                <div class="search_result_container"></div> <!-- end .search_result_container -->           
                                                                                
                                        <?php

                                                    echo $mensaje_query;

                                        ?>

                                                </div>
                                        </div>


                                        <?php





                            echo $this->miFormulario->marcoAgrupacion('fin');

                        }else{
                            $esteCampo = 'query';
                            $atributos ['id'] = $esteCampo;
                            $atributos ['nombre'] = $esteCampo;
                            $atributos ['tipo'] = 'text';
                            $atributos ['estilo'] = 'jqueryui';
                            $atributos ['marco'] = true;
                            $atributos ['estiloMarco'] = '';
                            $atributos ["etiquetaObligatorio"] = true;
                            $atributos ['columnas'] = 240;
                            $atributos ['filas'] = 2;
                            $atributos ['dobleLinea'] = 0;
                            $atributos ['tabIndex'] = $tab;
                            $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                            $atributos ['validar'] = 'minSize[1]';
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
                        }

                        /*$esteCampo = 'data';
                        $atributos ['id'] = $esteCampo;
                        $atributos ['nombre'] = $esteCampo;
                        $atributos ['tipo'] = 'text';
                        $atributos ['estilo'] = 'jqueryui';
                        $atributos ['marco'] = true;
                        $atributos ['estiloMarco'] = '';
                        $atributos ["etiquetaObligatorio"] = true;
                        $atributos ['columnas'] = 240;
                        $atributos ['filas'] = 2;
                        $atributos ['dobleLinea'] = 0;
                        $atributos ['tabIndex'] = $tab;
                        $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                        $atributos ['validar'] = 'minSize[1]';
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
                        unset($atributos);*/


                        $esteCampo = "agrupaInfoData";
                        $atributos ['id'] = $esteCampo;
                        $atributos ["estilo"] = "jqueryui";
                        $atributos ['leyenda'] = "Información Anterior";
                        echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);


                            $controlAd = 0;


                            $esteCampo = "marcoDescripcionAnt";
                            $atributos ['id'] = $esteCampo;
                            $atributos ["estilo"] = "jqueryui";
                            $atributos ['tipoEtiqueta'] = 'inicio';
                            $atributos ["leyenda"] = "Afectaciones";
                            echo $this->miFormulario->marcoAgrupacion('inicio', $atributos); 
                                    
                                    
                                    ?>    
                                        
                                        
                                    <div class="bwl_acc_container" id="accordionRLt">
                                            <div class="accordion_search_container">
                                                <input type="text" class="accordion_search_input_box search_icon" value="" placeholder="Search ..."/>
                                            </div> <!-- end .bwl_acc_container -->
                                            <div class="search_result_container"></div> <!-- end .search_result_container -->           
                                                                            
                                    <?php

                                                echo $mensaje_data;

                                    ?>

                                            </div>
                                    </div>


                                    <?php





                        echo $this->miFormulario->marcoAgrupacion('fin');

                        $esteCampo = 'host';
                        $atributos ['id'] = $esteCampo;
                        $atributos ['nombre'] = $esteCampo;
                        $atributos ['tipo'] = 'text';
                        $atributos ['estilo'] = 'jqueryui';
                        $atributos ['marco'] = true;
                        $atributos ['estiloMarco'] = '';
                        $atributos ["etiquetaObligatorio"] = true;
                        $atributos ['columnas'] = 1;
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
                        $atributos ['tamanno'] = 70;
                        $atributos ['maximoTamanno'] = '';
                        $atributos ['anchoEtiqueta'] = 250;
                        $tab ++;

                        // Aplica atributos globales al control
                        $atributos = array_merge($atributos, $atributosGlobales);
                        echo $this->miFormulario->campoCuadroTexto($atributos);
                        unset($atributos);

                        $esteCampo = 'id_responsable';
                        $atributos ['id'] = $esteCampo;
                        $atributos ['nombre'] = $esteCampo;
                        $atributos ['tipo'] = 'text';
                        $atributos ['estilo'] = 'jqueryui';
                        $atributos ['marco'] = true;
                        $atributos ['estiloMarco'] = '';
                        $atributos ["etiquetaObligatorio"] = true;
                        $atributos ['columnas'] = 1;
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
                        $atributos ['tamanno'] = 70;
                        $atributos ['maximoTamanno'] = '';
                        $atributos ['anchoEtiqueta'] = 250;
                        $tab ++;

                        // Aplica atributos globales al control
                        $atributos = array_merge($atributos, $atributosGlobales);
                        echo $this->miFormulario->campoCuadroTexto($atributos);
                        unset($atributos);

                        $esteCampo = 'date_evento';
                        $atributos ['id'] = $esteCampo;
                        $atributos ['nombre'] = $esteCampo;
                        $atributos ['tipo'] = 'text';
                        $atributos ['estilo'] = 'jqueryui';
                        $atributos ['marco'] = true;
                        $atributos ['estiloMarco'] = '';
                        $atributos ["etiquetaObligatorio"] = true;
                        $atributos ['columnas'] = 1;
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
                        $atributos ['tamanno'] = 70;
                        $atributos ['maximoTamanno'] = '';
                        $atributos ['anchoEtiqueta'] = 250;
                        $tab ++;

                        // Aplica atributos globales al control
                        $atributos = array_merge($atributos, $atributosGlobales);
                        echo $this->miFormulario->campoCuadroTexto($atributos);
                        unset($atributos);

                    echo $this->miFormulario->marcoAgrupacion('fin');


                }


            $atributos["id"]="botonReg";
            $atributos["estilo"]=" marcoBotones widget";
            echo $this->miFormulario->division("inicio",$atributos);
            {
                $enlace = "<a href='".$variable."'>";
                $enlace.="<img src='".$rutaBloque."/images/player_rew.png' width='35px'><br>Regresar";
                $enlace.="</a><br><br>";
                echo $enlace;
            }
            //------------------Fin Division para los botones-------------------------
            echo $this->miFormulario->division("fin");
            unset ( $atributos );
            

            // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------




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
        $atributos ['marco'] = true;
        $atributos ["etiqueta"] = "";
        $atributos ["valor"] = $valorCodificado;
        echo $this->miFormulario->campoCuadroTexto($atributos);
        unset($atributos);

        $atributos ['marco'] = true;
        $atributos ['tipoEtiqueta'] = 'fin';
        echo $this->miFormulario->formulario($atributos);
    }

}

$miSeleccionador = new registrarForm($this->lenguaje, $this->miFormulario, $this->sql);

$miSeleccionador->miForm();
?>
