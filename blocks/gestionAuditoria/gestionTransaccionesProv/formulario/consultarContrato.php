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
        $conexionFrameWork = "estructura";
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

        $ventanaClaseContratista = 'none';
        $ventanaConvenio = 'none';

        if (isset($_REQUEST ['opcion']) == 'modificarContrato') {
            $datosContrato = array($_REQUEST ['numero_contrato'], $_REQUEST ['vigencia']);
            $cadena_sql = $this->miSql->getCadenaSql('Consultar_Contrato_Particular', $datosContrato);
            $contrato = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
            $contrato = $contrato [0];

            if ($contrato ['clase_contratista'] != '33') {
                $contrato ['clase_contratista'] = '32';
            }


            $arregloContrato = array(
                "numero_contrato" => $contrato ['numero_contrato'],
                "clase_contratista" => $contrato ['clase_contratista'],
                "clase_contrato" => $contrato ['tipologia_contrato'],
                "tipo_compromiso" => $contrato ['tipo_compromiso'],
                "plazo_ejecucion" => $contrato ['plazo_ejecucion'],
                "unidad_ejecucion_tiempo" => $contrato ['unidad_ejecucion'],
                "tipologia_especifica" => $contrato ['tipologia_contrato'],
                "tipo_contrato" => $contrato ['tipo_contrato'],
                "numero_constancia" => $contrato ['numero_constancia'],
                "modalidad_seleccion" => $contrato ['modalidad_seleccion'],
                "procedimiento" => $contrato ['procedimiento'],
                "regimen_contratación" => $contrato ['regimen_contratacion'],
                "tipo_moneda" => $contrato ['tipo_moneda'],
                "tipo_gasto" => $contrato ['tipo_gasto'],
                "origen_recursos" => $contrato ['origen_recursos'],
                "origen_presupuesto" => $contrato ['origen_presupueso'],
                "tema_gasto_inversion" => $contrato ['tema_gasto_inversion'],
                "valor_contrato_moneda_ex" => $contrato ['valor_contrato_me'],
                "tasa_cambio" => $contrato ['valor_tasa_cambio'],
                "observacionesContrato" => $contrato ['observaciones'],
                "tipo_control" => $contrato ['tipo_control'],
                "nombre_supervisor" => $contrato ['documento'] . "-" . $contrato['nombre'],
                "cargo_supervisor" => $contrato ['cargo'],
                "numero_convenio" => $contrato ['convenio'],
                "digito_supervisor" => $contrato ['digito_verificacion'],
                "formaPago" => $contrato ['forma_pago'],
                "clausula_presupuesto" => $contrato ['clausula_registro_presupuestal'],
                "objeto_contrato" => $contrato ['objeto_contrato'],
                "actividades" => $contrato ['actividades'],
                "valor_contrato" => $contrato ['valor_contrato'],
                "dependencia_solicitante" => $contrato ['dependencia_solicitante'],
                "sede" => $contrato ['sede_solicitante'],
                "dependencia_supervisor" => $contrato ['dependencia_supervisor'],
                "sede_super" => $contrato ['sede_supervisor'],
                "justificacion" => $contrato ['justificacion'],
                "especificaciones_tecnicas" => $contrato ['especificaciones_tecnicas'],
                "descripcion_forma_pago" => $contrato ['descripcion_forma_pago'],
                "condiciones" => $contrato ['condiciones'],
                "ordenador_gasto" => $contrato ['ordenador_gasto'],
                "convenio_solicitante" => $contrato ['convenio'],
                "ejecucionCiudad" => $contrato ['ciudad'],
                "sede_ejecucion" => $contrato ['sede'],
                "dependencia_ejecucion" => $contrato ['dependencia'],
                "direccion_ejecucion" => $contrato ['direccion'],
                "tipo_supervisor" => $contrato['tipo']
            );
            $_REQUEST = array_merge($_REQUEST, $arregloContrato);
        }
        $sqlPadresCiudad = $this->miSql->getCadenaSql("buscarPadresCiudad", $_REQUEST['ejecucionCiudad']);
        $padresCiudad = $esteRecursoDBCore->ejecutarAcceso($sqlPadresCiudad, "busqueda");
        $padresCiudad = array(
            'ejecucionDepartamento' => $padresCiudad[0]['id_departamento'],
            'ejecucionPais' => $padresCiudad[0]['id_pais'],
        );


        $_REQUEST = array_merge($_REQUEST, $padresCiudad);

         $cadenaSql = $this->miSql->getCadenaSql('consultaArrendamiento', $datosContrato);
        $arrendamiento = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
        
     
        
        $arrendamientoN = array(
            'destinacionArrendamiento_hidden' => $arrendamiento[0]['destinacion'],
            'diasHabiles_hidden' => $arrendamiento[0]['plazo_pago_mensual'],
            'valorMensualArrendamiento_hidden' => $arrendamiento[0]['valor_arrendamiento'],
            'reajusteArrendamiento_hidden' => $arrendamiento[0]['reajuste'],
            'diasHabilesAdmin_hidden' =>$arrendamiento[0]['plazo_administracion'],
            'valorAdmin_hidden' =>intval($arrendamiento[0]['valor_administracion']),
            'diasHabilesEntrega_hidden' => $arrendamiento[0]['plazo_entrega'],
            
        );
          
        $_REQUEST = array_merge($_REQUEST, $arrendamientoN);


        $cadenaSqlelaboro = $this->miSql->getCadenaSql('obtenerInformacionElaborador', $contrato['usuario']);
        $usuario = $DBFrameWork->ejecutarAcceso($cadenaSqlelaboro, "busqueda");


        $esteCampo = "marcoDatos";
        $atributos ['id'] = $esteCampo;
        $atributos ["estilo"] = "jqueryui";
        $atributos ['tipoEtiqueta'] = 'inicio';
        $atributos ["leyenda"] = "Consultar Contrato " . $_REQUEST['mensaje_titulo'] . " | Elaborador por : " . $usuario[0]['nombre'] . " " . $usuario[0]['apellido'];
        ;
        echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);
        unset($atributos); {

            $datosContrato = array('vigencia' => $_REQUEST['vigencia'], 'numero_contrato' => $_REQUEST['numero_contrato']);
            if (isset($_REQUEST['ordenATC']) && $_REQUEST['ordenATC'] == "true") {

                $arreglo = preg_replace('!s:(\d+):"(.*?)";!e', "'s:'.strlen('$2').':\"$2\";'", $_REQUEST['arreglo']);
                $arreglo = unserialize($arreglo);
                $variable = "pagina=gestionContratosATC";
                $variable .= "&opcion=ConsultarContratos";
                $variable .= "&estado=" . $arreglo['estado'];
                $variable .= "&tipo_contrato=" . $arreglo['tipo'];
                $variable .= "&fecha_inicial=" . $arreglo['fecha\_inicial'];
                $variable .= "&fecha_final=" . $arreglo['fecha\_final'];
                $variable .= "&vigencia=" . $arreglo['vigencia'];
                $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
            } elseif (isset($_REQUEST['polizas']) && $_REQUEST['polizas'] == "true") {

                $arreglo = preg_replace('!s:(\d+):"(.*?)";!e', "'s:'.strlen('$2').':\"$2\";'", $_REQUEST['arreglo']);
                $arreglo = unserialize($arreglo);

                $variable = "bloque=gestionPolizas";
                $variable .= "&pagina=gestionPolizas";
                $variable .= "&opcion=ConsultarContratos";
                $variable .= "&clase_contrato=" . $arreglo['clase\_contrato'];
                $variable .= "&id_contrato=" . $arreglo['numero\_contrato'] . "-(" . $arreglo['vigencia'] . ")";
                $variable .= "&fecha_inicio_sub=" . $arreglo['fecha\_inicial'];
                $variable .= "&id_contratista=" . $arreglo['nit'];
                $variable .= "&fecha_final_sub=" . $arreglo['fecha\_final'];
                $variable .= "&unidad_ejecutora_consulta=" . $arreglo['unidad\_ejecutora'];

                $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
            } else {

                $arreglo = preg_replace('!s:(\d+):"(.*?)";!e', "'s:'.strlen('$2').':\"$2\";'", $_REQUEST['arreglo']);
                $arreglo = unserialize($arreglo);

                $variable = "pagina=" . $miPaginaActual;
                $variable .= "&opcion=ConsultarContratos";
                $variable .= "&id_contrato=" . $arreglo ['numero\_contrato'] . "-(" . $arreglo ['vigencia'] . ")";
                $variable .= "&clase_contrato=" . $arreglo ['clase\_contrato'];
                $variable .= "&id_contratista=" . $arreglo ['nit'];
                $variable .= "&fecha_inicio_sub=" . $arreglo ['fecha\_inicial'];
                $variable .= "&fecha_final_sub=" . $arreglo ['fecha\_final'];
                $variable .= "&usuario=" . $_REQUEST ['usuario'];

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

              $cadenaAmparosParametros = $this->miSql->getCadenaSql("obtenerAmparosParametros");
            $amparosParametros = $esteRecursoDBCore->ejecutarAcceso($cadenaAmparosParametros, "busqueda");
            
              $cadenaSql = $this->miSql->getCadenaSql('consultaArrendamientoAmparo',$datosContrato);
              $arrendamientoGeneral = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
             
                                                                           
            
           $tablaAmparosDinamica ='<div class="container">
                                           <div class="row clearfix">
                                                <div class="col-md-20 column">
                                                    <table class="table table-bordered table-hover" id="tab_amparos">
                                                                <thead>
                                                                        <tr >
                                                                                <th class="text-center">
                                                                                                #
                                                                                </th>
                                                                                <th class="text-center">
                                                                                        Amparo
                                                                                </th>
                                                                                <th class="text-center">
                                                                                        Suficiencia
                                                                                </th>
                                                                                <th class="text-center">
                                                                                        Descripción
                                                                                </th>
                                                                        </tr>
                                                                </thead>
                                                                <tbody>';
                                                                           $contadorArrend =0;
                                                                           $numeral = 1;
                                                                           
                                                                           
                                                                           if($arrendamientoGeneral !=false){
//                                                                           
                                                                            while($contadorArrend<count($arrendamientoGeneral)){
//                                                                                   
//                                                                                                    
                                                                               $tablaAmparosDinamica .=' <tr id="addr'.$contadorArrend.'">
                                                                                        <td>'.$numeral .' </td>';
                                                                                                $cadenaAmparosParametros = $this->miSql->getCadenaSql("obtenerAmparosParametros2", $arrendamientoGeneral[$contadorArrend]['tipo_amparo']);
                                                                                                $amparosParametros2 = $esteRecursoDBCore->ejecutarAcceso($cadenaAmparosParametros, "busqueda");
                                                                                                
                                                                                                 $tablaAmparosDinamica .='<td><select  id="amparo'.$contadorArrend.'" name="amparo'.$contadorArrend.'"  class="selectpicker " disabled> '
                                                                                                         . '<option value="">Seleccione  ....</option>';
                                                                                                         for ($i = 0; $i < count($amparosParametros); $i++) {
                                                                                                        
                                                                                                         if ($amparosParametros[$i]['id'] ==$amparosParametros2[0][0]){
                                                                                                             $tablaAmparosDinamica.='<option value="' . $amparosParametros[$i]['id'] . '" selected="true">' . $amparosParametros[$i]['nombre'] . '</option>';
                                                                                                            }
                                                                                                         else{
                                                                                                             $tablaAmparosDinamica.='<option value="' . $amparosParametros[$i]['id'] . '">' . $amparosParametros[$i]['nombre'] . '</option>';
                                                                                                          }
                                                                                                        }             
                                                                                                    $tablaAmparosDinamica.='</select>
                                                                                                    </td>';
                                                                                                    $tablaAmparosDinamica.='<td>
                                                                                                    <input type="text" id="porcentajeamparo'.$contadorArrend.'" name="porcentajeamparo'.$contadorArrend.'" placeholder="Porcentaje(%)-> 10%" maxlength="3" value="'.$arrendamientoGeneral[$contadorArrend]['suficiencia'].'" class="form-control  custom[number]" disabled/>
                                                                                                    </td>
                                                                                                    <td>
                                                                                                    <input type="text" id="valoramparo'.$contadorArrend.'" name="valoramparo'.$contadorArrend.'" placeholder="Descripción" maxlength="500" value="'.$arrendamientoGeneral[$contadorArrend]['vigencia'].'" class="form-control  " disabled/>
                                                                                                    </td>';
                                                                                                  $tablaAmparosDinamica .= '</tr>';  
                                                                                                  $contadorArrend++;
                                                                                                  $numeral++;
//                                                                                                   
                                                                                  }
                                                                              }



                                                                               $tablaAmparosDinamica .= '</tbody></table></div></div></div>';



                                                                           
                                                                              
                                                                         
            
                                       
                                                                                                                                 
                                                                                                                                          
                                                                                                                                     
//          
                                                                                                                                          
            echo "<input id='amparosOculto' name='amparosOculto' type='hidden' value='" . json_encode($amparosParametros) . "'>";
            
            echo "<br>";

            if (isset($_REQUEST['ordenATC']) && $_REQUEST['ordenATC'] == "true") {

                $host = $this->miConfigurador->getVariableConfiguracion("host") . $this->miConfigurador->getVariableConfiguracion("site") . "/blocks/gestionContractual/novedad/registrarNovedad/archivoSoporte/";
                $hostLiquidados = $this->miConfigurador->getVariableConfiguracion("host") . $this->miConfigurador->getVariableConfiguracion("site") . "/blocks/gestionContractual/gestionContratosATC/archivoSoporte/";


                if ($_REQUEST['estado'] == '7') {
                    $consultaOrdenATC = $this->miSql->getCadenaSql("info_orden_Cancelada", $datosContrato);
                    $infoCancelada = $esteRecursoDB->ejecutarAcceso($consultaOrdenATC, "busqueda");
                    $mensaje = "Contrato Cancelado en la fecha:" . $infoCancelada[0]['fecha_cancelacion'] . "<br>";
                    $mensaje .= "Motivo de Cancelacion:" . $infoCancelada[0]['motivo_cancelacion'] . "<br>";
                    $mensaje .= "Usuario:" . $infoCancelada[0]['usuario'] . "<br>";
                    $mensaje .= "Acto Administrativo:" . $infoCancelada[0]['usuario'] . "<br>";
                } elseif ($_REQUEST['estado'] == '5') {
                    $consultaOrdenATC = $this->miSql->getCadenaSql("info_orden_Anulada", $datosContrato);
                    $infoAnulada = $esteRecursoDB->ejecutarAcceso($consultaOrdenATC, "busqueda");
                    $mensaje = "Contrato Anulado en la fecha: " . $infoAnulada[0]['fecha_registro'] . "<br>";
                    $mensaje .= "Motivo de Anulación: " . $infoAnulada[0]['descripcion'] . "<br>";
                    $mensaje .= "Tipo de Anulación: " . $infoAnulada[0]['tipoanulacion'] . "<br>";
                    $mensaje .= "Usuario: " . $infoAnulada[0]['usuario'] . "<br>";
                    $mensaje .= "Acto Administrativo: " . $infoAnulada[0]['acto_administrativo'] . "<br>";
                    $mensaje .= "Documento: <a href='" . $host . $infoAnulada[0]['documento'] . "' TARGET='_blank' >" . $infoAnulada[0]['documento'] . "</a>";
                } elseif ($_REQUEST['estado'] == '8') {
                    $consultaOrdenATC = $this->miSql->getCadenaSql("info_orden_terminada_anticipada", $datosContrato);
                    $infoTerminadoAnticipado = $esteRecursoDB->ejecutarAcceso($consultaOrdenATC, "busqueda");
                    $mensaje = "Contrato Terminado Anticipadamente en la fecha: " . $infoTerminadoAnticipado[0]['fecha'] . "<br>";
                    $mensaje .= "Motivo de la Terminacion Anticipada: " . $infoTerminadoAnticipado[0]['descripcion'] . "<br>";
                    $mensaje .= "Usuario: " . $infoTerminadoAnticipado[0]['usuario'] . "<br>";
                    $mensaje .= "Acto Administrativo: " . $infoTerminadoAnticipado[0]['acto_administrativo'] . "<br>";
                    $mensaje .= "Documento: <a href='" . $host . $infoTerminadoAnticipado[0]['documento'] . "' TARGET='_blank' >" . $infoTerminadoAnticipado[0]['documento'] . "</a>";
                } elseif ($_REQUEST['estado'] == '9') {
                    $consultaOrdenATC = $this->miSql->getCadenaSql("info_contrato_liquidado", $datosContrato);
                    $infoLiquidado = $esteRecursoDB->ejecutarAcceso($consultaOrdenATC, "busqueda");
                    $mensaje = "Contrato Liquidado en la fecha: " . $infoLiquidado[0]['fecha_liquidacion'] . "<br>";
                    $mensaje .= "Observaciones: " . $infoLiquidado[0]['observaciones'] . "<br>";
                    $mensaje .= "Usuario: " . $infoLiquidado[0]['usuario'] . "<br>";
                    $mensaje .= "Acto Administrativo: " . $infoLiquidado[0]['numero_acto'] . "<br>";
                    $mensaje .= "Documento: <a href='" . $hostLiquidados . $infoLiquidado[0]['documento'] . "' TARGET='_blank' >" . $infoLiquidado[0]['documento'] . "</a>";
                } else {
                    $consultaOrdenATC = $this->miSql->getCadenaSql("info_orden_termminada", $datosContrato);
                    $infoTerminada = $esteRecursoDB->ejecutarAcceso($consultaOrdenATC, "busqueda");
                    $mensaje = "Contrato Finalizado en la fecha: " . $infoTerminada[0]['fecha_registro'] . "<br>";
                    $mensaje .= "Observaciones: " . $infoTerminada[0]['descripcion'] . "<br>";
                    $mensaje .= "Usuario: " . $infoTerminada[0]['usuario'] . "<br>";
                    $mensaje .= "Acto Administrativo: " . $infoTerminada[0]['acto_administrativo'] . "<br>";
                    $mensaje .= "Documento: <a href='" . $host . $infoTerminada[0]['documento'] . "' TARGET='_blank' >" . $infoTerminada[0]['documento'] . "</a>";
                };
                echo "<center><h3><a href='javascript:void(0);' onclick='VerMensajeATC()'>Ver Información Adicional(Clic Aqui!)</a></h3></center><div id='mensajeATC' name='mensajeATC' style='display:none;'>";
                echo "<a href='javascript:void(0);' onclick='CerrarMensajeATC()'>Cerrar</a> ";
                $esteCampo = "marcoContratoATC";
                $atributos ['id'] = $esteCampo;
                $atributos ["estilo"] = "jqueryui";
                $atributos ['tipoEtiqueta'] = 'inicio';
                $atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo);

                echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);

                $esteCampo = 'mensajeATC';
                $atributos ['id'] = $esteCampo;
                $atributos ['tipo'] = 'information';
                $atributos ['estilo'] = 'textoCentrar';
                $atributos ['mensaje'] = $mensaje;

                $tab ++;

                // Aplica atributos globales al control
                $atributos = array_merge($atributos, $atributosGlobales);
                echo $this->miFormulario->cuadroMensaje($atributos);

                unset($atributos);


                echo $this->miFormulario->agrupacion('fin');

                echo "</div>";
                echo "</div>";
            }

            $cadenaSqlUnidad = $this->miSql->getCadenaSql("obtenerInfoUsuario", $_REQUEST['usuario']);
            $unidadEjecutora = $DBFrameWork->ejecutarAcceso($cadenaSqlUnidad, "busqueda");


            $esteCampo = 'unidad_ejecutora';
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
            $atributos ['validar'] = '';

            if (isset($unidadEjecutora)) {
                $atributos ['valor'] = $unidadEjecutora[0]['nombre'];
            } else {
                $atributos ['valor'] = 'Usuario sin Dependencia Registrada';
            }
            $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
            $atributos ['deshabilitado'] = true;
            $atributos ['tamanno'] = 35;
            $atributos ['maximoTamanno'] = '';
            $atributos ['anchoEtiqueta'] = 180;
            $tab ++;

            // Aplica atributos globales al control
            $atributos = array_merge($atributos, $atributosGlobales);
            echo $this->miFormulario->campoCuadroTexto($atributos);
            unset($atributos);
            // ------------------Division para los botones-------------------------
            $atributos ["id"] = "ventanaA";
            echo $this->miFormulario->division("inicio", $atributos);
            unset($atributos); {

                echo "<h3>Información de Contrato</h3>
							<section>"; {

                    if ($contrato['tipo_contrato'] == '1') {

                        $cadena_sql = $this->miSql->getCadenaSql('Consultar_Tipo_orden', $datosContrato);
                        $tipoOrden = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                        $arregloTipoOrden = array('tipo_orden' => $tipoOrden[0][0]);
                        $_REQUEST = array_merge($_REQUEST, $arregloTipoOrden);

                        $esteCampo = 'tipo_orden';
                        $atributos ['columnas'] = 2;
                        $atributos ['nombre'] = $esteCampo;
                        $atributos ['id'] = $esteCampo;
                        $atributos ['evento'] = '';
                        $atributos ['deshabilitado'] = true;
                        $atributos ["etiquetaObligatorio"] = true;
                        $atributos ['tab'] = $tab;
                        $atributos ['tamanno'] = 1;
                        $atributos ['estilo'] = 'jqueryui';
                        $atributos ['validar'] = 'required';
                        $atributos ['limitar'] = true;
                        $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                        $atributos ['anchoEtiqueta'] = 213;

                        if (isset($_REQUEST [$esteCampo])) {
                            $atributos ['seleccion'] = $_REQUEST [$esteCampo];
                        } else {
                            $atributos ['seleccion'] = - 1;
                        }

                        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("tipo_orden");
                        $matrizItems = $esteRecursoDB->ejecutarAcceso($atributos ['cadena_sql'], "busqueda");
                        $atributos ['matrizItems'] = $matrizItems;
                        $tab ++;
                        $atributos = array_merge($atributos, $atributosGlobales);
                        echo $this->miFormulario->campoCuadroLista($atributos);
                        unset($atributos);
                    }

                    $esteCampo = 'tipo_compromiso';
                    $atributos ['columnas'] = 2;
                    $atributos ['nombre'] = $esteCampo;
                    $atributos ['id'] = $esteCampo;
                    $atributos ['evento'] = '';
                    $atributos ['deshabilitado'] = true;
                    $atributos ["etiquetaObligatorio"] = true;
                    $atributos ['tab'] = $tab;
                    $atributos ['tamanno'] = 1;
                    $atributos ['estilo'] = 'jqueryui';
                    $atributos ['validar'] = 'required';
                    $atributos ['limitar'] = true;
                    $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                    $atributos ['anchoEtiqueta'] = 213;
                    $atributos ['anchoCaja'] = 20;
                    if (isset($_REQUEST [$esteCampo])) {
                        $atributos ['seleccion'] = $_REQUEST [$esteCampo];
                    } else {
                        $atributos ['seleccion'] = - 1;
                    }

                    $matrizItems = array(
                        array(
                            ' ',
                            'Sin Solicitud de Necesidad'
                        )
                    );

                    // $atributos ['matrizItems'] = $matrizItems;
                    // Utilizar lo siguiente cuando no se pase un arreglo:
                    $atributos ['baseDatos'] = 'contractual';
                    $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("tipo_compromiso");

                    $tab ++;
                    $atributos = array_merge($atributos, $atributosGlobales);
                    echo $this->miFormulario->campoCuadroLista($atributos);
                    unset($atributos);


                    $esteCampo = 'tipo_contrato';
                    $atributos ['columnas'] = 2;
                    $atributos ['nombre'] = $esteCampo;
                    $atributos ['id'] = $esteCampo;
                    $atributos ['evento'] = '';
                    $atributos ['deshabilitado'] = true;
                    $atributos ["etiquetaObligatorio"] = true;
                    $atributos ['tab'] = $tab;
                    $atributos ['tamanno'] = 1;
                    $atributos ['estilo'] = 'jqueryui';
                    $atributos ['validar'] = 'required';
                    $atributos ['limitar'] = false;
                    $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                    $atributos ['anchoEtiqueta'] = 213;
                    $atributos ['anchoCaja'] = 30;
                    if (isset($_REQUEST [$esteCampo])) {
                        $atributos ['seleccion'] = $_REQUEST [$esteCampo];
                    } else {
                        $atributos ['seleccion'] = - 1;
                    }

                    $matrizItems = array(
                        array(
                            ' ',
                            'Sin Solicitud de Necesidad'
                        )
                    );

                    // $atributos ['matrizItems'] = $matrizItems;
                    // Utilizar lo siguiente cuando no se pase un arreglo:
                    $atributos ['baseDatos'] = 'contractual';
                    $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("tipo_contrato");
                    $tab ++;
                    $atributos = array_merge($atributos, $atributosGlobales);
                    echo $this->miFormulario->campoCuadroLista($atributos);
                    unset($atributos);



                    if ($_REQUEST ['tipo_contrato'] == 6) {

                        $datosContrato = array($_REQUEST ['numero_contrato'], $_REQUEST ['vigencia']);

                        $cadena_sql = $this->miSql->getCadenaSql('ConsultarperfilCPS', $datosContrato);
                        $perfil = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                        $perfil = $perfil [0];

                        $estiloPerfil = "";
                    } else {
                        $estiloPerfil = "display:none";
                    }


                    $atributos ["id"] = "divisionPerfil";
                    $atributos = array_merge($atributos, $atributosGlobales);
                    $atributos ["estiloEnLinea"] = $estiloPerfil;
                    echo $this->miFormulario->division("inicio", $atributos);
                    unset($atributos);
                    {

                        $esteCampo = 'perfil';
                        $atributos ['columnas'] = 2;
                        $atributos ['nombre'] = $esteCampo;
                        $atributos ['id'] = $esteCampo;
                        $atributos ['evento'] = '';
                        $atributos ['deshabilitado'] = true;
                        $atributos ["etiquetaObligatorio"] = true;
                        $atributos ['tab'] = $tab;
                        $atributos ['tamanno'] = 1;
                        $atributos ['estilo'] = 'jqueryui';
                        $atributos ['validar'] = '';
                        $atributos ['limitar'] = true;
                        $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                        $atributos ['anchoEtiqueta'] = 213;
                        $atributos ['anchoCaja'] = 30;

                        if (isset($perfil [$esteCampo])) {
                            $atributos ['seleccion'] = $perfil [$esteCampo];
                        } else {
                            $atributos ['seleccion'] = - 1;
                        }

                        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("perfiles");
                        $matrizItems = $esteRecursoDBAgora->ejecutarAcceso($atributos ['cadena_sql'], "busqueda");
                        $atributos ['matrizItems'] = $matrizItems;
                        $tab ++;
                        $atributos = array_merge($atributos, $atributosGlobales);
                        echo $this->miFormulario->campoCuadroLista($atributos);
                        unset($atributos);
                    }
                    echo $this->miFormulario->division("fin");





                    if ($_REQUEST ['tipo_compromiso'] == 3) {
                        $estiloConvenio = "";
                    } else {
                        $estiloConvenio = "display:none";
                    }


                    $atributos ["id"] = "divisionConvenio";
                    $atributos = array_merge($atributos, $atributosGlobales);
                    $atributos ["estiloEnLinea"] = $estiloConvenio;
                    echo $this->miFormulario->division("inicio", $atributos);
                    unset($atributos);
                    {




                        $esteCampo = "convenio_solicitante";
                        $atributos ['columnas'] = 2;
                        $atributos ['nombre'] = $esteCampo;
                        $atributos ['id'] = $esteCampo;
                        $atributos ['evento'] = '';
                        $atributos ['deshabilitado'] = true;
                        $atributos ["etiquetaObligatorio"] = true;
                        $atributos ['tab'] = $tab;
                        $atributos ['tamanno'] = 1;
                        $atributos ['estilo'] = 'jqueryui';
                        $atributos ['validar'] = 'required';
                        $atributos ['limitar'] = false;
                        $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                        $atributos ['anchoEtiqueta'] = 213;
                        $atributos ['anchoCaja'] = 15;
                        $atributos ['baseDatos'] = 'contractual';
                        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("convenios");
                        if (isset($_REQUEST [$esteCampo])) {
                            $atributos ['seleccion'] = $_REQUEST [$esteCampo];
                        } else {
                            $atributos ['seleccion'] = - 1;
                        }
                        $tab ++;
                        $atributos = array_merge($atributos, $atributosGlobales);
                        echo $this->miFormulario->campoCuadroLista($atributos);
                        unset($atributos);

                        $sqlNombreConvenio = $this->miSql->getCadenaSql("buscar_nombre_convenio", $_REQUEST['convenio_solicitante']);
                        $nombreConvenio = $esteRecursoDB->ejecutarAcceso($sqlNombreConvenio, "busqueda");


                        $esteCampo = 'nombre_convenio_solicitante';
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
                        $atributos ['validar'] = 'minSize[1]';
                        $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
                        $atributos ['deshabilitado'] = true;
                        $atributos ['tamanno'] = 20;
                        $atributos ['maximoTamanno'] = '';
                        $atributos ['anchoEtiqueta'] = 220;
                        if ($nombreConvenio != false) {
                            $atributos ['valor'] = $nombreConvenio[0][0];
                        } else {
                            $atributos ['valor'] = '';
                        }
                        $tab ++;

                        // Aplica atributos globales al control
                        $atributos = array_merge($atributos, $atributosGlobales);
                        echo $this->miFormulario->campoTextArea($atributos);
                        unset($atributos);
                    }
                    echo $this->miFormulario->division("fin");
                    unset($atributos);

                    $esteCampo = 'tipologia_especifica';
                    $atributos ['columnas'] = 2;
                    $atributos ['nombre'] = $esteCampo;
                    $atributos ['id'] = $esteCampo;
                    $atributos ['evento'] = '';
                    $atributos ['deshabilitado'] = true;
                    $atributos ["etiquetaObligatorio"] = true;
                    $atributos ['tab'] = $tab;
                    $atributos ['tamanno'] = 1;
                    $atributos ['estilo'] = 'jqueryui';
                    $atributos ['validar'] = 'required';
                    $atributos ['limitar'] = false;
                    $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                    $atributos ['anchoEtiqueta'] = 213;
                    $atributos ['anchoCaja'] = 15;
                    if (isset($_REQUEST [$esteCampo])) {
                        $atributos ['seleccion'] = $_REQUEST [$esteCampo];
                    } else {
                        $atributos ['seleccion'] = - 1;
                    }

                    $atributos ['baseDatos'] = 'contractual';
                    $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("tipologia_contrato");
                    $tab ++;
                    $atributos = array_merge($atributos, $atributosGlobales);
                    echo $this->miFormulario->campoCuadroLista($atributos);
                    unset($atributos);

                    $esteCampo = 'modalidad_seleccion';
                    $atributos ['columnas'] = 2;
                    $atributos ['nombre'] = $esteCampo;
                    $atributos ['id'] = $esteCampo;
                    $atributos ['evento'] = '';
                    $atributos ['deshabilitado'] = true;
                    $atributos ["etiquetaObligatorio"] = true;
                    $atributos ['tab'] = $tab;
                    $atributos ['tamanno'] = 1;
                    $atributos ['estilo'] = 'jqueryui';
                    $atributos ['validar'] = 'required';
                    $atributos ['limitar'] = false;
                    $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                    $atributos ['anchoEtiqueta'] = 213;
                    $atributos ['anchoCaja'] = 27;
                    if (isset($_REQUEST [$esteCampo])) {
                        $atributos ['seleccion'] = $_REQUEST [$esteCampo];
                    } else {
                        $atributos ['seleccion'] = - 1;
                    }

                    $matrizItems = array(
                        array(
                            ' ',
                            'Sin Solicitud de Necesidad'
                        )
                    );

                    // $atributos ['matrizItems'] = $matrizItems;
                    // Utilizar lo siguiente cuando no se pase un arreglo:
                    $atributos ['baseDatos'] = 'contractual';
                    $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("modalidad_seleccion");
                    $tab ++;
                    $atributos = array_merge($atributos, $atributosGlobales);
                    echo $this->miFormulario->campoCuadroLista($atributos);
                    unset($atributos);

                    $esteCampo = 'procedimiento';
                    $atributos ['columnas'] = 2;
                    $atributos ['nombre'] = $esteCampo;
                    $atributos ['id'] = $esteCampo;
                    $atributos ['evento'] = '';
                    $atributos ['deshabilitado'] = true;
                    $atributos ["etiquetaObligatorio"] = true;
                    $atributos ['tab'] = $tab;
                    $atributos ['tamanno'] = 1;
                    $atributos ['estilo'] = 'jqueryui';
                    $atributos ['validar'] = 'required';
                    $atributos ['limitar'] = false;
                    $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                    $atributos ['anchoEtiqueta'] = 213;
                    $atributos ['anchoCaja'] = 30;
                    if (isset($_REQUEST [$esteCampo])) {
                        $atributos ['seleccion'] = $_REQUEST [$esteCampo];
                    } else {
                        $atributos ['seleccion'] = - 1;
                    }

                    $matrizItems = array(
                        array(
                            ' ',
                            'Sin Solicitud de Necesidad'
                        )
                    );

                    // $atributos ['matrizItems'] = $matrizItems;
                    // Utilizar lo siguiente cuando no se pase un arreglo:
                    $atributos ['baseDatos'] = 'contractual';
                    $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("tipo_procedimiento");
                    $tab ++;
                    $atributos = array_merge($atributos, $atributosGlobales);
                    echo $this->miFormulario->campoCuadroLista($atributos);
                    unset($atributos);

                    $esteCampo = 'regimen_contratación';
                    $atributos ['columnas'] = 2;
                    $atributos ['nombre'] = $esteCampo;
                    $atributos ['id'] = $esteCampo;
                    $atributos ['evento'] = '';
                    $atributos ['deshabilitado'] = true;
                    $atributos ["etiquetaObligatorio"] = true;
                    $atributos ['tab'] = $tab;
                    $atributos ['tamanno'] = 1;
                    $atributos ['estilo'] = 'jqueryui';
                    $atributos ['validar'] = 'required';
                    $atributos ['limitar'] = false;
                    $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                    $atributos ['anchoEtiqueta'] = 213;
                    $atributos ['anchoCaja'] = 30;
                    if (isset($_REQUEST [$esteCampo])) {
                        $atributos ['seleccion'] = $_REQUEST [$esteCampo];
                    } else {
                        $atributos ['seleccion'] = - 1;
                    }

                    $matrizItems = array(
                        array(
                            ' ',
                            'Sin Solicitud de Necesidad'
                        )
                    );

                    // $atributos ['matrizItems'] = $matrizItems;
                    // Utilizar lo siguiente cuando no se pase un arreglo:
                    $atributos ['baseDatos'] = 'contractual';
                    $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("regimen_contratacion");
                    $tab ++;
                    $atributos = array_merge($atributos, $atributosGlobales);
                    echo $this->miFormulario->campoCuadroLista($atributos);
                    unset($atributos);


                    $esteCampo = 'unidad_ejecucion_tiempo';
                    $atributos ['columnas'] = 2;
                    $atributos ['nombre'] = $esteCampo;
                    $atributos ['id'] = $esteCampo;
                    $atributos ['evento'] = '';
                    $atributos ['deshabilitado'] = true;
                    $atributos ["etiquetaObligatorio"] = true;
                    $atributos ['tab'] = $tab;
                    $atributos ['tamanno'] = 1;
                    $atributos ['estilo'] = 'jqueryui';
                    $atributos ['validar'] = 'required';
                    $atributos ['limitar'] = true;
                    $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                    $atributos ['anchoEtiqueta'] = 213;
                    $atributos ['anchoCaja'] = 20;
                    if (isset($_REQUEST [$esteCampo])) {
                        $atributos ['seleccion'] = $_REQUEST [$esteCampo];
                    } else {
                        $atributos ['seleccion'] = - 1;
                    }

                    $matrizItems = array(
                        array(
                            ' ',
                            'Sin Solicitud de Necesidad'
                        )
                    );

                    // $atributos ['matrizItems'] = $matrizItems;
                    // Utilizar lo siguiente cuando no se pase un arreglo:
                    $atributos ['baseDatos'] = 'contractual';
                    $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("tipo_ejecucion_tiempo");
                    $tab ++;
                    $atributos = array_merge($atributos, $atributosGlobales);
                    echo $this->miFormulario->campoCuadroLista($atributos);
                    unset($atributos);


                    $esteCampo = 'plazo_ejecucion';
                    $atributos ['id'] = $esteCampo;
                    $atributos ['nombre'] = $esteCampo;
                    $atributos ['tipo'] = 'text';
                    $atributos ['estilo'] = 'jqueryui';
                    $atributos ['marco'] = true;
                    $atributos ['estiloMarco'] = '';
                    $atributos ["etiquetaObligatorio"] = true;
                    $atributos ['columnas'] = 2;
                    $atributos ['tabIndex'] = $tab;
                    $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                    $atributos ['validar'] = 'required,custom[onlyNumberSp]';

                    if (isset($_REQUEST [$esteCampo])) {
                        $atributos ['valor'] = $_REQUEST [$esteCampo];
                    } else {
                        $atributos ['valor'] = '';
                    }
                    $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
                    $atributos ['deshabilitado'] = true;
                    $atributos ['tamanno'] = 20;
                    $atributos ['maximoTamanno'] = '';
                    $atributos ['anchoEtiqueta'] = 213;
                    $tab ++;

                    // Aplica atributos globales al control
                    $atributos = array_merge($atributos, $atributosGlobales);
                    echo $this->miFormulario->campoCuadroTexto($atributos);
                    unset($atributos);
                }
                echo "</section>";

                echo "<h3>Garantía y Mecanismos de Cobertura del Riesgo</h3><section>";
                
                
                  echo 
                                $tablaAmparosDinamica .
                          
                          
                          
                
                 "</section>";


                echo "<h3>Información Solicitante y Supervisor</h3><section>";

                $esteCampo = "AgrupacionSolicitante";
                $atributos ['id'] = $esteCampo;
                $atributos ['leyenda'] = "Información del Solicitante";
                echo $this->miFormulario->agrupacion('inicio', $atributos);
                {

                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                    $esteCampo = 'sede';
                    $atributos ['columnas'] = 2;
                    $atributos ['nombre'] = $esteCampo;
                    $atributos ['id'] = $esteCampo;
                    $atributos ['evento'] = '';
                    $atributos ['deshabilitado'] = true;
                    $atributos ["etiquetaObligatorio"] = true;
                    $atributos ['tab'] = $tab;
                    $atributos ['tamanno'] = 1;
                    $atributos ['estilo'] = 'jqueryui';
                    $atributos ['validar'] = 'required';
                    $atributos ['limitar'] = true;
                    $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                    $atributos ['anchoEtiqueta'] = 170;

                    if (isset($_REQUEST [$esteCampo])) {
                        $atributos ['seleccion'] = $_REQUEST [$esteCampo];
                    } else {
                        $atributos ['seleccion'] = - 1;
                    }

                    $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("sede");
                    $matrizItems = $esteRecursoDB->ejecutarAcceso($atributos ['cadena_sql'], "busqueda");
                    $atributos ['matrizItems'] = $matrizItems;


                    // Utilizar lo siguiente cuando no se pase un arreglo:
                    // $atributos['baseDatos']='ponerAquiElNombreDeLaConexión';
                    // $atributos ['cadena_sql']='ponerLaCadenaSqlAEjecutar';
                    $tab ++;
                    $atributos = array_merge($atributos, $atributosGlobales);
                    echo $this->miFormulario->campoCuadroLista($atributos);
                    unset($atributos);

                    $esteCampo = "dependencia_solicitante";
                    $atributos ['columnas'] = 2;
                    $atributos ['nombre'] = $esteCampo;
                    $atributos ['id'] = $esteCampo;
                    $atributos ['evento'] = '';
                    $atributos ['deshabilitado'] = true;
                    $atributos ["etiquetaObligatorio"] = true;
                    $atributos ['tab'] = $tab;
                    $atributos ['tamanno'] = 1;
                    $atributos ['estilo'] = 'jqueryui';
                    $atributos ['validar'] = 'required';
                    $atributos ['limitar'] = true;
                    $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                    $atributos ['anchoEtiqueta'] = 115;
                    $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("dependenciasConsultadas", $_REQUEST['sede']);
                    $matrizItems = $esteRecursoDB->ejecutarAcceso($atributos ['cadena_sql'], "busqueda");
                    $atributos ['matrizItems'] = $matrizItems;

                    if (isset($_REQUEST [$esteCampo])) {
                        $atributos ['seleccion'] = $_REQUEST [$esteCampo];
                    } else {
                        $atributos ['seleccion'] = - 1;
                    }

                    $tab ++;
                    $atributos = array_merge($atributos, $atributosGlobales);
                    echo $this->miFormulario->campoCuadroLista($atributos);
                    unset($atributos);


                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                }
                echo $this->miFormulario->agrupacion('fin');

                $esteCampo = "AgrupacionSupervisor";
                $atributos ['id'] = $esteCampo;
                $atributos ['leyenda'] = "Datos del Supervisor";
                echo $this->miFormulario->agrupacion('inicio', $atributos);
                {

                    //------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                    $esteCampo = 'sede_super';
                    $atributos ['columnas'] = 3;
                    $atributos ['nombre'] = $esteCampo;
                    $atributos ['id'] = $esteCampo;
                    $atributos ['evento'] = '';
                    $atributos ['deshabilitado'] = true;
                    $atributos ["etiquetaObligatorio"] = true;
                    $atributos ['tab'] = $tab;
                    $atributos ['tamanno'] = 1;
                    $atributos ['estilo'] = 'jqueryui';
                    $atributos ['validar'] = 'required';
                    $atributos ['limitar'] = true;
                    $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                    $atributos ['anchoEtiqueta'] = 175;

                    if (isset($_REQUEST [$esteCampo])) {
                        $atributos ['seleccion'] = $_REQUEST [$esteCampo];
                    } else {
                        $atributos ['seleccion'] = - 1;
                    }

                    $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("sede");
                    $matrizItems = $esteRecursoDB->ejecutarAcceso($atributos ['cadena_sql'], "busqueda");
                    $atributos ['matrizItems'] = $matrizItems;

                    // Utilizar lo siguiente cuando no se pase un arreglo:
                    // $atributos['baseDatos']='ponerAquiElNombreDeLaConexión';
                    // $atributos ['cadena_sql']='ponerLaCadenaSqlAEjecutar';
                    $tab ++;
                    $atributos = array_merge($atributos, $atributosGlobales);
                    echo $this->miFormulario->campoCuadroLista($atributos);
                    unset($atributos);

                    $esteCampo = 'dependencia_supervisor';
                    $atributos ['columnas'] = 3;
                    $atributos ['nombre'] = $esteCampo;
                    $atributos ['id'] = $esteCampo;
                    $atributos ['evento'] = '';
                    $atributos ['deshabilitado'] = true;
                    $atributos ["etiquetaObligatorio"] = true;
                    $atributos ['tab'] = $tab;
                    $atributos ['tamanno'] = 1;
                    $atributos ['estilo'] = 'jqueryui';
                    $atributos ['validar'] = 'required';
                    $atributos ['limitar'] = true;
                    $atributos ['anchoEtiqueta'] = 175;
                    $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                    $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("dependenciasConsultadas", $_REQUEST['sede_super']);
                    $matrizItems = $esteRecursoDB->ejecutarAcceso($atributos ['cadena_sql'], "busqueda");

                    $atributos ['matrizItems'] = $matrizItems;

                    if (isset($_REQUEST [$esteCampo])) {
                        $atributos ['seleccion'] = $_REQUEST [$esteCampo];
                    } else {
                        $atributos ['seleccion'] = - 1;
                    }

                    $tab ++;
                    $atributos = array_merge($atributos, $atributosGlobales);
                    echo $this->miFormulario->campoCuadroLista($atributos);
                    unset($atributos);


                    $esteCampo = 'tipo_supervisor';
                    $atributos ['columnas'] = 3;
                    $atributos ['nombre'] = $esteCampo;
                    $atributos ['id'] = $esteCampo;
                    $atributos ['evento'] = '';
                    $atributos ['deshabilitado'] = true;
                    $atributos ["etiquetaObligatorio"] = true;
                    $atributos ['tab'] = $tab;
                    $atributos ['tamanno'] = 1;
                    $atributos ['estilo'] = 'jqueryui';
                    $atributos ['validar'] = 'required';
                    $atributos ['limitar'] = true;
                    $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                    $atributos ['anchoEtiqueta'] = 175;
                    if (isset($_REQUEST [$esteCampo])) {
                        $atributos ['seleccion'] = $_REQUEST [$esteCampo];
                    } else {
                        $atributos ['seleccion'] = - 1;
                    }
                    $matrizItems = array(
                        array(1, 'FUNCIONARIO'),
                        array(2, 'INTERVENTOR')
                    );

                    $atributos ['matrizItems'] = $matrizItems;


                    $tab ++;
                    $atributos = array_merge($atributos, $atributosGlobales);
                    echo $this->miFormulario->campoCuadroLista($atributos);
                    unset($atributos);

                    $estiloDivisionFuncionario = "display:none";
                    $estiloDivisionInterventor = "display:none";
                    if ($_REQUEST['tipo_supervisor'] == 1) {
                        $estiloDivisionFuncionario = "";
                    } else {
                        $estiloDivisionInterventor = "";
                    }


                    $atributos ["id"] = "divisionSupervisorFuncionario";
                    $atributos = array_merge($atributos, $atributosGlobales);
                    $atributos ["estiloEnLinea"] = $estiloDivisionFuncionario;
                    echo $this->miFormulario->division("inicio", $atributos);
                    unset($atributos); {
                        $esteCampo = 'nombre_supervisor';
                        $atributos ['nombre'] = $esteCampo;
                        $atributos ['id'] = $esteCampo;
                        $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                        $atributos ["etiquetaObligatorio"] = true;
                        $atributos ['tab'] = $tab ++;
                        $atributos ['anchoEtiqueta'] = 175;
                        $atributos ['evento'] = '';
                        if (isset($_REQUEST [$esteCampo]) && $_REQUEST['tipo_supervisor'] == 1) {
                            $atributos ['seleccion'] = $_REQUEST [$esteCampo];
                        } else {
                            $atributos ['seleccion'] = - 1;
                        }
                        $atributos ['deshabilitado'] = true;
                        $atributos ['columnas'] = 3;
                        $atributos ['tamanno'] = 1;
                        $atributos ['ajax_function'] = "";
                        $atributos ['ajax_control'] = $esteCampo;
                        $atributos ['estilo'] = "jqueryui";
                        $atributos ['validar'] = "required";
                        $atributos ['limitar'] = true;
                        $atributos ['anchoCaja'] = 52;
                        $atributos ['miEvento'] = '';
                        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("funcionarios");
                        $matrizItems = array(
                            array(
                                0,
                                ' '
                            )
                        );
                        $matrizItems = $DBSICA->ejecutarAcceso($atributos ['cadena_sql'], "busqueda");
                        $atributos ['matrizItems'] = $matrizItems;
                        $atributos = array_merge($atributos, $atributosGlobales);
                        echo $this->miFormulario->campoCuadroLista($atributos);
                        unset($atributos);
                    }
                    echo $this->miFormulario->division("fin");
                    unset($atributos);
                    $atributos ["id"] = "divisionSupervisorInterventor";
                    $atributos = array_merge($atributos, $atributosGlobales);
                    $atributos ["estiloEnLinea"] = $estiloDivisionInterventor;
                    echo $this->miFormulario->division("inicio", $atributos);
                    unset($atributos); {

                        $esteCampo = 'nombre_supervisor_interventor';
                        $atributos ['nombre'] = $esteCampo;
                        $atributos ['id'] = $esteCampo;
                        $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                        $atributos ["etiquetaObligatorio"] = true;
                        $atributos ['tab'] = $tab ++;
                        $atributos ['anchoEtiqueta'] = 175;
                        $atributos ['evento'] = '';
                        if (isset($_REQUEST ['nombre_supervisor']) && $_REQUEST['tipo_supervisor'] == 2) {
                            $atributos ['seleccion'] = $_REQUEST ['nombre_supervisor'];
                        } else {
                            $atributos ['seleccion'] = - 1;
                        }
                        $atributos ['deshabilitado'] = true;
                        $atributos ['columnas'] = 3;
                        $atributos ['tamanno'] = 1;
                        $atributos ['ajax_function'] = "";
                        $atributos ['ajax_control'] = $esteCampo;
                        $atributos ['estilo'] = "jqueryui";
                        $atributos ['validar'] = "required";
                        $atributos ['limitar'] = true;
                        $atributos ['anchoCaja'] = 52;
                        $atributos ['miEvento'] = '';
                        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("interventores");
                        $matrizItems = $esteRecursoDBAgora->ejecutarAcceso($atributos ['cadena_sql'], "busqueda");
                        $atributos ['matrizItems'] = $matrizItems;
                        $atributos = array_merge($atributos, $atributosGlobales);
                        echo $this->miFormulario->campoCuadroLista($atributos);
                        unset($atributos);
                    }
                    echo $this->miFormulario->division("fin");
                    unset($atributos);

                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                    $esteCampo = 'cargo_supervisor';
                    $atributos ['columnas'] = 2;
                    $atributos ['nombre'] = $esteCampo;
                    $atributos ['id'] = $esteCampo;
                    $atributos ['evento'] = '';
                    $atributos ['deshabilitado'] = true;
                    $atributos ["etiquetaObligatorio"] = true;
                    $atributos ['tab'] = $tab;
                    $atributos ['tamanno'] = 1;
                    $atributos ['estilo'] = 'jqueryui';
                    $atributos ['validar'] = 'required';
                    $atributos ['limitar'] = false;
                    $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                    $atributos ['anchoEtiqueta'] = 115;
                    $atributos ['anchoCaja'] = 50;
                    if (isset($_REQUEST [$esteCampo])) {
                        $atributos ['seleccion'] = $_REQUEST [$esteCampo];
                    } else {
                        $atributos ['seleccion'] = - 1;
                    }

                    // $atributos ['matrizItems'] = $matrizItems;
                    // Utilizar lo siguiente cuando no se pase un arreglo:
                    $atributos ['baseDatos'] = 'contractual';
                    $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("cargosFuncionarios");
                    $tab ++;
                    $atributos = array_merge($atributos, $atributosGlobales);
                    echo $this->miFormulario->campoCuadroLista($atributos);
                    unset($atributos);

                    // -----------------CONTROL: Botón ----------------------------------------------------------------


                    $esteCampo = 'tipo_control';
                    $atributos ['columnas'] = 3;
                    $atributos ['nombre'] = $esteCampo;
                    $atributos ['id'] = $esteCampo;
                    $atributos ['evento'] = '';
                    $atributos ['deshabilitado'] = true;
                    $atributos ["etiquetaObligatorio"] = true;
                    $atributos ['tab'] = $tab;
                    $atributos ['tamanno'] = 1;
                    $atributos ['estilo'] = 'jqueryui';
                    $atributos ['validar'] = 'required';
                    $atributos ['limitar'] = false;
                    $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                    $atributos ['anchoEtiqueta'] = 175;
                    $atributos ['anchoCaja'] = 100;
                    if (isset($_REQUEST [$esteCampo])) {
                        $atributos ['seleccion'] = $_REQUEST [$esteCampo];
                    } else {
                        $atributos ['seleccion'] = '-1';
                    }

                    $matrizItems = array(
                        array(
                            ' ',
                            'Sin Solicitud de Necesidad'
                        )
                    );

                    // $atributos ['matrizItems'] = $matrizItems;
                    // Utilizar lo siguiente cuando no se pase un arreglo:
                    $atributos ['baseDatos'] = 'contractual';
                    $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("tipo_control");
                    $tab ++;
                    $atributos = array_merge($atributos, $atributosGlobales);
                    echo $this->miFormulario->campoCuadroLista($atributos);
                    unset($atributos);

                    $esteCampo = 'digito_supervisor';
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
                    $atributos ['validar'] = 'required,custom[onlyNumberSp]';

                    if (isset($_REQUEST [$esteCampo])) {
                        $atributos ['valor'] = $_REQUEST [$esteCampo];
                    } else {
                        $atributos ['valor'] = '';
                    }
                    $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
                    $atributos ['deshabilitado'] = true;
                    $atributos ['tamanno'] = 20;
                    $atributos ['maximoTamanno'] = '';
                    $atributos ['anchoEtiqueta'] = 175;
                    $tab ++;

                    // Aplica atributos globales al control
                    $atributos = array_merge($atributos, $atributosGlobales);
                    echo $this->miFormulario->campoCuadroTexto($atributos);
                    unset($atributos);
                }
                echo $this->miFormulario->agrupacion('fin');
                $esteCampo = "AgrupacionLugarEjecucion";
                $atributos ['id'] = $esteCampo;
                $atributos ['leyenda'] = "Lugar de Ejecución";
                echo $this->miFormulario->agrupacion('inicio', $atributos); {

                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------

                    $esteCampo = 'ejecucionPais';
                    $atributos['nombre'] = $esteCampo;
                    $atributos['id'] = $esteCampo;
                    $atributos['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                    $atributos['tab'] = $tab;
                    $atributos['seleccion'] = -1;
                    $atributos['evento'] = ' ';
                    $atributos['deshabilitado'] = true;
                    $atributos['limitar'] = 50;
                    $atributos['tamanno'] = 1;
                    $atributos['columnas'] = 2;
                    $atributos ['anchoEtiqueta'] = 175;

                    $atributos ['obligatorio'] = true;
                    $atributos ['etiquetaObligatorio'] = true;
                    $atributos ['validar'] = '';

                    $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("buscarPais");
                    $matrizItems = $esteRecursoDBCore->ejecutarAcceso($atributos ['cadena_sql'], "busqueda");

                    $atributos['matrizItems'] = $matrizItems;
                    if (isset($_REQUEST [$esteCampo])) {
                        $atributos ['seleccion'] = $_REQUEST [$esteCampo];
                    } else {
                        $atributos ['seleccion'] = '';
                    }

                    $tab ++;

                    // Aplica atributos globales al control
                    $atributos = array_merge($atributos, $atributosGlobales);
                    echo $this->miFormulario->campoCuadroLista($atributos);

                    $esteCampo = 'ejecucionDepartamento';
                    $atributos['nombre'] = $esteCampo;
                    $atributos['id'] = $esteCampo;
                    $atributos['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                    $atributos['tab'] = $tab;
                    $atributos['seleccion'] = -1;
                    $atributos['evento'] = ' ';
                    $atributos['deshabilitado'] = true;
                    $atributos['limitar'] = 50;
                    $atributos['tamanno'] = 1;
                    $atributos['columnas'] = 2;
                    $atributos ['anchoEtiqueta'] = 175;

                    $atributos ['obligatorio'] = true;
                    $atributos ['etiquetaObligatorio'] = true;
                    $atributos ['validar'] = '';

                    $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("buscarDepartamento");
                    $matrizItems = $esteRecursoDBCore->ejecutarAcceso($atributos ['cadena_sql'], "busqueda");
                    $atributos['matrizItems'] = $matrizItems;

                    if (isset($_REQUEST [$esteCampo])) {
                        $atributos ['seleccion'] = $_REQUEST [$esteCampo];
                    } else {
                        $atributos ['seleccion'] = '';
                    }
                    $tab ++;

                    // Aplica atributos globales al control
                    $atributos = array_merge($atributos, $atributosGlobales);
                    echo $this->miFormulario->campoCuadroLista($atributos);
                    // --------------- FIN CONTROL : Select --------------------------------------------------
                    // ---------------- CONTROL: Select --------------------------------------------------------
                    $esteCampo = 'ejecucionCiudad';
                    $atributos['nombre'] = $esteCampo;
                    $atributos['id'] = $esteCampo;
                    $atributos['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                    $atributos['tab'] = $tab;
                    $atributos['seleccion'] = -1;
                    $atributos['evento'] = ' ';
                    $atributos['deshabilitado'] = true;
                    $atributos['limitar'] = 50;
                    $atributos['tamanno'] = 1;
                    $atributos['columnas'] = 2;
                    $atributos ['anchoEtiqueta'] = 175;

                    $atributos ['obligatorio'] = true;
                    $atributos ['etiquetaObligatorio'] = true;
                    $atributos ['validar'] = 'required';

                    $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("buscarCiudad");
                    $matrizItems = $esteRecursoDBCore->ejecutarAcceso($atributos ['cadena_sql'], "busqueda");
                    $atributos ['matrizItems'] = $matrizItems;

                    if (isset($_REQUEST [$esteCampo])) {
                        $atributos ['seleccion'] = $_REQUEST [$esteCampo];
                    } else {
                        $atributos ['seleccion'] = '';
                    }
                    $tab ++;

                    // Aplica atributos globales al control
                    $atributos = array_merge($atributos, $atributosGlobales);
                    echo $this->miFormulario->campoCuadroLista($atributos);
                    unset($atributos);

                    $esteCampo = 'sede_ejecucion';
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
                    $atributos ['anchoEtiqueta'] = 175;

                    if (isset($_REQUEST [$esteCampo])) {
                        $atributos ['seleccion'] = $_REQUEST [$esteCampo];
                    } else {
                        $atributos ['seleccion'] = - 1;
                    }

                    $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("sede");
                    $matrizItems = $esteRecursoDB->ejecutarAcceso($atributos ['cadena_sql'], "busqueda");
                    $atributos ['matrizItems'] = $matrizItems;

                    // Utilizar lo siguiente cuando no se pase un arreglo:
                    // $atributos['baseDatos']='ponerAquiElNombreDeLaConexión';
                    // $atributos ['cadena_sql']='ponerLaCadenaSqlAEjecutar';
                    $tab ++;
                    $atributos = array_merge($atributos, $atributosGlobales);
                    echo $this->miFormulario->campoCuadroLista($atributos);
                    unset($atributos);

                    $esteCampo = 'dependencia_ejecucion';
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
                    $atributos ['anchoEtiqueta'] = 175;

                    $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("dependenciasConsultadas", $_REQUEST['sede_ejecucion']);

                    $matrizItems = $esteRecursoDB->ejecutarAcceso($atributos ['cadena_sql'], "busqueda");
                    $atributos ['matrizItems'] = $matrizItems;

                    if (isset($_REQUEST [$esteCampo])) {
                        $atributos ['seleccion'] = $_REQUEST [$esteCampo];
                    } else {
                        $atributos ['seleccion'] = - 1;
                    }
                    $tab ++;
                    $atributos = array_merge($atributos, $atributosGlobales);
                    echo $this->miFormulario->campoCuadroLista($atributos);
                    unset($atributos);




                    $esteCampo = 'direccion_ejecucion';
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

                    if (isset($_REQUEST [$esteCampo])) {
                        $atributos ['valor'] = $_REQUEST [$esteCampo];
                    } else {
                        $atributos ['valor'] = '';
                    }
                    $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
                    $atributos ['deshabilitado'] = true;
                    $atributos ['tamanno'] = 20;
                    $atributos ['maximoTamanno'] = '';
                    $atributos ['anchoEtiqueta'] = 175;
                    $tab ++;

                    // Aplica atributos globales al control
                    $atributos = array_merge($atributos, $atributosGlobales);
                    echo $this->miFormulario->campoCuadroTexto($atributos);
                    unset($atributos);
                }
                echo $this->miFormulario->agrupacion('fin');
//
                echo "</section>";



                echo "<h3>Información Contratista</h3><section>";

                $esteCampo = 'clase_contratista';
                $atributos ['columnas'] = 3;
                $atributos ['nombre'] = $esteCampo;
                $atributos ['id'] = $esteCampo;
                $atributos ['evento'] = '';
                $atributos ['deshabilitado'] = true;
                $atributos ["etiquetaObligatorio"] = true;
                $atributos ['tab'] = $tab;
                $atributos ['tamanno'] = 1;
                $atributos ['estilo'] = 'jqueryui';
                $atributos ['validar'] = 'required';
                $atributos ['limitar'] = false;
                $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                $atributos ['anchoEtiqueta'] = 213;
                $atributos ['anchoCaja'] = 35;
                if (isset($_REQUEST [$esteCampo])) {
                    $atributos ['seleccion'] = $_REQUEST [$esteCampo];
                } else {
                    $atributos ['seleccion'] = - 1;
                }

                $matrizItems = array(
                    array(
                        ' ',
                        'Sin Solicitud de Necesidad'
                    )
                );

                // $atributos ['matrizItems'] = $matrizItems;
                // Utilizar lo siguiente cuando no se pase un arreglo:
                $atributos ['baseDatos'] = 'contractual';
                $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("tipo_clase_contratista");

                $tab ++;
                $atributos = array_merge($atributos, $atributosGlobales);
                echo $this->miFormulario->campoCuadroLista($atributos);
                unset($atributos);

                if (isset($_REQUEST['clase_contratista'])) {
                    if ($_REQUEST['clase_contratista'] == '33') {
                        $estiloUnicoContratista = "";
                        $estiloSociedadTemporal = "display:none";
                        $sqlDatosProveedor = $this->miSql->getCadenaSql("buscar_Informacion_proveedor_edicion", $contrato['contratista']);
                        $datosProveedor = $esteRecursoDBAgora->ejecutarAcceso($sqlDatosProveedor, "busqueda");

                        $infoProveedorUnico = '<b>TIPO PERSONA: </b>' . $datosProveedor[0]['tipopersona'] . '<br>
                            <b>NOMBRE:</b> ' . $datosProveedor[0]['nom_proveedor'] . '<br>
                            <b>DOCUMENTO:</b> ' . $datosProveedor[0]['num_documento'] . '<br>
                            <b>CIUDAD DE CONTACTO:</b> ' . $datosProveedor[0]['nombreciudad'] . '<br>
                            <b>DIRECCIÓN:</b> ' . $datosProveedor[0]['direccion'] . '<br>
                            <b>CORREO:</b> ' . $datosProveedor[0]['correo'] . '<br>
                            <b>SITIO WEB:</b> ' . $datosProveedor[0]['web'] . '<br>
                            <b>ASESOR:</b> ' . $datosProveedor[0]['nom_asesor'] . '<br>
                            <b>TELÉFONO ASESOR:</b> ' . $datosProveedor[0]['tel_asesor'] . '<br>
                            <b>DESCRIPCIÓN:</b> ' . $datosProveedor[0]['descripcion'] . '<br>
                            <b>PUNTAJE DE EVALUACIÓN:</b> ' . $datosProveedor[0]['puntaje_evaluacion'] . '<br>
                            <b>TIPO CUENTA BANCARIA:</b> ' . $datosProveedor[0]['tipo_cuenta_bancaria'] . '<br>
                            <b>NÚMERO CUENTA:</b> ' . $datosProveedor[0]['num_cuenta_bancaria'] . '<br>
                            <b>ENTIDAD BANCARIA:</b> ' . $datosProveedor[0]['nombrebanco'];


                        $Infoparticipantes = "";
                        $infoSociedadTemporal = "";
                    } elseif ($_REQUEST['clase_contratista'] == '31' || $_REQUEST['clase_contratista'] == '32') {

                        $estiloUnicoContratista = "display:none";
                        $estiloSociedadTemporal = "";

                        $sqlDatosProveedor = $this->miSql->getCadenaSql("buscar_Informacion_sociedad", $contrato['contratista']);
                        $infoSociedad = $esteRecursoDBAgora->ejecutarAcceso($sqlDatosProveedor, "busqueda");

                        $infoSociedadTemporal = '<b>TIPO SOCIEDAD:</b> ' . $infoSociedad[0]['tipopersona'] . '<br>' .
                                '<b>NOMBRE:</b> ' . $infoSociedad[0]['nom_proveedor'] . '<br>' .
                                '<b>DOCUMENTO:</b> ' . $infoSociedad[0]['num_documento'] . '<br>' .
                                '<b>DIGITO DE VERIFICACIÓN:</b> ' . $infoSociedad[0]['digito_verificacion'] . '<br>' .
                                '<b>CIUDAD DE CONTACTO:</b> ' . $infoSociedad[0]['nombreciudad'] . '<br>' .
                                '<b>DIRECCIÓN:</b> ' . $infoSociedad[0]['direccion'] . '<br>' .
                                '<b>CORREO:</b> ' . $infoSociedad[0]['correo'] . '<br>' .
                                '<b>SITIO WEB:</b> ' . $infoSociedad[0]['web'] . '<br>' .
                                '<b>REPRESENTANTE:</b> ' . $infoSociedad[0]['inforepresentante'] . '<br>' .
                                '<b>REPRESENTANTE SUPLENTE:</b> ' . $infoSociedad[0]['inforepresentantesuplente'] . '<br>' .
                                '<b>PUNTAJE DE EVALUACIÓN:</b> ' . $infoSociedad[0]['puntaje_evaluacion'] . '<br>' .
                                '<b>TIPO CUENTA BANCARIA:</b> ' . $infoSociedad[0]['tipo_cuenta_bancaria'] . '<br>' .
                                '<b>NÚMERO CUENTA:</b> ' . $infoSociedad[0]['num_cuenta_bancaria'] . '<br>' .
                                '<b>ENTIDAD BANCARIA:</b> ' . $infoSociedad[0]['nombrebanco'];

                        $sqlDatosParticipantes = $this->miSql->getCadenaSql("buscar_participantes_sociedad", $contrato['contratista']);
                        $participantes = $esteRecursoDBAgora->ejecutarAcceso($sqlDatosParticipantes, "busqueda");


                        $Infoparticipantes = "<table class='table table-bordered table-hover'><thead><tr>";
                        $Infoparticipantes .= " <th class='text-center'>PARTICIPANTE</th><th class='text-center'>PORCENTAJE DE PARTICIPACIÓN</th></tr></thead><tbody>";
                        for ($i = 0; $i < count($participantes); $i++) {
                            $Infoparticipantes .= "<tr><td class='text-center'>" . $participantes[$i][0] . "</td><td class='text-center'>" . $participantes[$i][1] . "%</td><tr>";
                        }
                        $Infoparticipantes .= "</tbody></table>";

                        $infoProveedorUnico = "";
                    } else {
                        $estiloUnicoContratista = "display:none";
                        $estiloSociedadTemporal = "display:none";
                    }


                    $atributos ["id"] = "divisionClaseContratista";
                    $atributos ["estiloEnLinea"] = $estiloUnicoContratista;
                    $atributos = array_merge($atributos, $atributosGlobales);
                    echo $this->miFormulario->division("inicio", $atributos);
                    unset($atributos); {
                        echo "<br>";
                        echo "<br>";
                        echo "<br>";

                        echo "<center>";

                        echo "<h3>Contratista</h3>";


                        echo "</center>";




                        $atributos ["id"] = "divisionInformacionContratista";
                        $atributos = array_merge($atributos, $atributosGlobales);
                        echo $this->miFormulario->division("inicio", $atributos);





                        echo "<br><br><div id='infoProveedor' ><table class='table table-bordered table-hover' id='tab_participante'>
                                	<thead>
                                        	<tr >                                              	
                					
                					<th class='text-center'>
        							INFORMACIÓN DEL CONTRATISTA:
        						</th>
                					                					
                                        	</tr>
                                        </thead>
                                        <tbody>
        					<tr >                                              	
                					
                					<td id='infoproveedortd' class='text-center'>
        							<span class='infoproveedorspan'>$infoProveedorUnico</span>
        						</td>
                					                					
                                        	</tr>
                                        </tbody>
                            </table></div>";

                        echo $this->miFormulario->division("fin", $atributos);
                    }
                    // ------------------Fin Division para los botones-------------------------
                    echo $this->miFormulario->division("fin");
                    unset($atributos);


                    $atributos ["id"] = "divisionSociedadTemporal";
                    $atributos = array_merge($atributos, $atributosGlobales);
                    $atributos ["estiloEnLinea"] = $estiloSociedadTemporal;
                    echo $this->miFormulario->division("inicio", $atributos);
                    unset($atributos); {



                        echo "<br>";
                        echo "<br>";
                        echo "<br>";
                        echo "<center>";
                        echo "<h3>Sociedad Contratista</h3>";





                        echo "</center>";


                        $atributos ["id"] = "divisionInformacionSociedad";
                        $atributos = array_merge($atributos, $atributosGlobales);
                        echo $this->miFormulario->division("inicio", $atributos);
                        unset($atributos);

                        echo "<br><br><div id='infoSociedad' ><table class='table table-bordered table-hover' id='tab_participante'>
                                	<thead>
                                        	<tr >                                              	
                					
                					<th class='text-center'>
        							INFORMACIÓN DE LA SOCIEDAD
        						</th>
                					<th class='text-center'>
        							PARTICIPANTES
        						</th>
                					                					
                                        	</tr>
                                        </thead>
                                        <tbody>
        					<tr >                                              	
                					
                					<td id='infosociedadtd' class='text-center'>
        							<span class='infosociedadspan'>$infoSociedadTemporal</span>
        						</td>
                                                        
                					<td id='infosociedadtd' class='text-center'>
        							<span class='infosociedadparticipantesspan'>$Infoparticipantes</span>
        						</td>
                					                					
                                        	</tr>
        					
                                        </tbody>
                            </table></div>";

                        echo $this->miFormulario->division("fin");
                        unset($atributos);
                    }
                    echo $this->miFormulario->division("fin");
                }


                $esteCampo = 'id_proveedor';
                $atributos ['id'] = $esteCampo;
                $atributos ['nombre'] = $esteCampo;
                $atributos ['tipo'] = 'hidden';
                $atributos ['estilo'] = 'jqueryui';
                $atributos ['marco'] = true;
                $atributos ['columnas'] = 1;
                $atributos ['dobleLinea'] = false;
                $atributos ['tabIndex'] = $tab;
                $atributos ['valor'] = $contrato['contratista'];
                $atributos ['deshabilitado'] = false;
                $atributos ['tamanno'] = 30;
                $atributos ['maximoTamanno'] = '';
                $tab ++;
                // Aplica atributos globales al control
                $atributos = array_merge($atributos, $atributosGlobales);
                echo $this->miFormulario->campoCuadroTexto($atributos);
                unset($atributos);

                                  $esteCampo = 'destinacionArrendamiento_hidden';
                $atributos ['id'] = $esteCampo;
                $atributos ['nombre'] = $esteCampo;
                $atributos ['tipo'] = 'hidden';
                $atributos ['estilo'] = 'jqueryui';
                $atributos ['marco'] = true;
                $atributos ['columnas'] = 1;
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

                  $esteCampo = 'diasHabiles_hidden';
                $atributos ['id'] = $esteCampo;
                $atributos ['nombre'] = $esteCampo;
                $atributos ['tipo'] = 'hidden';
                $atributos ['estilo'] = 'jqueryui';
                $atributos ['marco'] = true;
                $atributos ['columnas'] = 1;
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
                 $esteCampo = 'valorMensualArrendamiento_hidden';
                $atributos ['id'] = $esteCampo;
                $atributos ['nombre'] = $esteCampo;
                $atributos ['tipo'] = 'hidden';
                $atributos ['estilo'] = 'jqueryui';
                $atributos ['marco'] = true;
                $atributos ['columnas'] = 1;
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
                 $esteCampo = 'reajusteArrendamiento_hidden';
                $atributos ['id'] = $esteCampo;
                $atributos ['nombre'] = $esteCampo;
                $atributos ['tipo'] = 'hidden';
                $atributos ['estilo'] = 'jqueryui';
                $atributos ['marco'] = true;
                $atributos ['columnas'] = 1;
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
                 $esteCampo = 'diasHabilesAdmin_hidden';
                $atributos ['id'] = $esteCampo;
                $atributos ['nombre'] = $esteCampo;
                $atributos ['tipo'] = 'hidden';
                $atributos ['estilo'] = 'jqueryui';
                $atributos ['marco'] = true;
                $atributos ['columnas'] = 1;
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
                $esteCampo = 'valorAdmin_hidden';
                $atributos ['id'] = $esteCampo;
                $atributos ['nombre'] = $esteCampo;
                $atributos ['tipo'] = 'hidden';
                $atributos ['estilo'] = 'jqueryui';
                $atributos ['marco'] = true;
                $atributos ['columnas'] = 1;
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
                     $esteCampo = 'diasHabilesEntrega_hidden';
                $atributos ['id'] = $esteCampo;
                $atributos ['nombre'] = $esteCampo;
                $atributos ['tipo'] = 'hidden';
                $atributos ['estilo'] = 'jqueryui';
                $atributos ['marco'] = true;
                $atributos ['columnas'] = 1;
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
                 $esteCampo = 'tablAmparos_hidden';
                $atributos ['id'] = $esteCampo;
                $atributos ['nombre'] = $esteCampo;
                $atributos ['tipo'] = 'hidden';
                $atributos ['estilo'] = 'jqueryui';
                $atributos ['marco'] = true;
                $atributos ['columnas'] = 1;
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
                  $esteCampo = 'tablaSuficiencia_hidden';
                $atributos ['id'] = $esteCampo;
                $atributos ['nombre'] = $esteCampo;
                $atributos ['tipo'] = 'hidden';
                $atributos ['estilo'] = 'jqueryui';
                $atributos ['marco'] = true;
                $atributos ['columnas'] = 1;
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
                  $esteCampo = 'tablaVigencia_hidden';
                $atributos ['id'] = $esteCampo;
                $atributos ['nombre'] = $esteCampo;
                $atributos ['tipo'] = 'hidden';
                $atributos ['estilo'] = 'jqueryui';
                $atributos ['marco'] = true;
                $atributos ['columnas'] = 1;
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
                  $esteCampo = 'tablaVigenciaNueva_hidden';
                $atributos ['id'] = $esteCampo;
                $atributos ['nombre'] = $esteCampo;
                $atributos ['tipo'] = 'hidden';
                $atributos ['estilo'] = 'jqueryui';
                $atributos ['marco'] = true;
                $atributos ['columnas'] = 1;
                $atributos ['dobleLinea'] = false;
                $atributos ['tabIndex'] = $tab;
                $atributos ['valor'] = $tablaAmparosDinamica;
                $atributos ['deshabilitado'] = false;
                $atributos ['tamanno'] = 30;
                $atributos ['maximoTamanno'] = '';
                $tab ++;
                // Aplica atributos globales al control
                $atributos = array_merge($atributos, $atributosGlobales);
                echo $this->miFormulario->campoCuadroTexto($atributos);
                unset($atributos);
                $esteCampo = 'cantidadAmparos_hidden';
                $atributos ['id'] = $esteCampo;
                $atributos ['nombre'] = $esteCampo;
                $atributos ['tipo'] = 'hidden';
                $atributos ['estilo'] = 'jqueryui';
                $atributos ['marco'] = true;
                $atributos ['columnas'] = 1;
                $atributos ['dobleLinea'] = false;
                $atributos ['tabIndex'] = $tab;
                $atributos ['valor'] = $contadorArrend;
                $atributos ['deshabilitado'] = false;
                $atributos ['tamanno'] = 30;
                $atributos ['maximoTamanno'] = '';
                $tab ++;
                // Aplica atributos globales al control
                $atributos = array_merge($atributos, $atributosGlobales);
                echo $this->miFormulario->campoCuadroTexto($atributos);
                unset($atributos);
                
                 $esteCampo = 'contadorSelect_hidden';
                $atributos ['id'] = $esteCampo;
                $atributos ['nombre'] = $esteCampo;
                $atributos ['tipo'] = 'hidden';
                $atributos ['estilo'] = 'jqueryui';
                $atributos ['marco'] = true;
                $atributos ['columnas'] = 1;
                $atributos ['dobleLinea'] = false;
                $atributos ['tabIndex'] = $tab;
                $atributos ['valor'] = $contadorArrend;
                $atributos ['deshabilitado'] = false;
                $atributos ['tamanno'] = 30;
                $atributos ['maximoTamanno'] = '';
                $tab ++;
                // Aplica atributos globales al control
                $atributos = array_merge($atributos, $atributosGlobales);
                echo $this->miFormulario->campoCuadroTexto($atributos);
                unset($atributos);
                
                
                echo "</section>";

                echo "<h3>Información Presupuestal 1</h3>
							<section>";
                {


                    $datos_disponibilidad = array(0 => $_REQUEST ['numero_contrato'], 1 => $_REQUEST['vigencia']);
                    $cadena_sql = $this->miSql->getCadenaSql('ConsultarDisponibilidadesContrato', $datos_disponibilidad);

                    $disponibilidades = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");


                    $esteCampo = "AgrupacionDisponibilidad";
                    $atributos ['id'] = $esteCampo;
                    $atributos ['leyenda'] = "Disponibilidades Presupuestales Asociadas";
                    echo $this->miFormulario->agrupacion('inicio', $atributos);
                    {







                        echo "<br><br><br><div class='container'>
    
	<div class='row'>
        <div class='col-md-24'>
				<div class='panel panel-primary'>
					<div class='panel-heading'>
						<h3 class='panel-title'>Información Presupuestal</h3>
						
					</div>
					<table class='table table-bordered table-hover' id='tablacdpasociados'>
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
                                               <tbody>";
                        $valor_acumulado = 0;
                        $indices_cdp = "";
                        $indices_cdp_vigencia = "";
                        for ($i = 0; $i < count($disponibilidades); $i++) {
                            $datos_info_disponibilidad = array('numero_disponibilidad' => $disponibilidades[$i]['numero_cdp'], 'vigencia' => $disponibilidades[$i]['vigencia_cdp'],
                                'unidad_ejecutora' => $unidad[0]['unidad_ejecutora']);
                            $cadena_sql = $this->miSql->getCadenaSql('obtenerInfoCdp', $datos_info_disponibilidad);
                            $info_disponibilidad = $DBSICA->ejecutarAcceso($cadena_sql, "busqueda");
                            $valor_acumulado = $valor_acumulado + $info_disponibilidad[0]['VALOR_CONTRATACION'];
                            $indices_cdp.= "," . $info_disponibilidad[0]['NUMERO_DISPONIBILIDAD'];
                            $indices_cdp_vigencia.= "," . $info_disponibilidad[0]['NUMERO_DISPONIBILIDAD'] . "-" . $info_disponibilidad[0]['VIGENCIA'];

                            echo "<tr id='$i'><td><center>" . $info_disponibilidad[0]['VIGENCIA'] . "</center></td>"
                            . "<td><center>" . $info_disponibilidad[0]['NUM_SOL_ADQ'] . "</center></td>"
                            . "<td><center>" . $info_disponibilidad[0]['NUMERO_DISPONIBILIDAD'] . "</center></td>"
                            . "<td><center>" . $info_disponibilidad[0]['VALOR_CONTRATACION'] . "</center></td>"
                            . "<td><center>" . $info_disponibilidad[0]['NOMBRE_DEPENDENCIA'] . "</center></td>"
                            . "<td><center>" . $info_disponibilidad[0]['DESCRIPCION'] . "</center></td>"
                            . "<td><center>" . $info_disponibilidad[0]['ESTADO'] . "</center></td>";
                            echo "</tr>";
                        }
                        $indice_tabla = count($disponibilidades);

                        echo "            </tbody>
                                               </table>
                                                </div>
			</div>";



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
                        $atributos ['valor'] = number_format($valor_acumulado, 2, ",", ".");
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





                        echo "
                        </div></div>";
                    }
                    echo $this->miFormulario->agrupacion('fin');
                }
                echo "</section>";
                echo "<h3>Información Presupuestal 2</h3>
							<section>";
                {




                    $atributos ["id"] = "division";
                    echo $this->miFormulario->division("inicio", $atributos);
                    unset($atributos);
                    {



                        $esteCampo = 'tipo_moneda';
                        $atributos ['columnas'] = 2;
                        $atributos ['nombre'] = $esteCampo;
                        $atributos ['id'] = $esteCampo;
                        $atributos ['evento'] = '';
                        $atributos ['deshabilitado'] = true;
                        $atributos ["etiquetaObligatorio"] = true;
                        $atributos ['tab'] = $tab;
                        $atributos ['tamanno'] = 1;
                        $atributos ['estilo'] = 'jqueryui';
                        $atributos ['validar'] = 'required';
                        $atributos ['limitar'] = false;
                        $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                        $atributos ['anchoEtiqueta'] = 213;
                        $atributos ['anchoCaja'] = 27;
                        if (isset($_REQUEST [$esteCampo])) {
                            $atributos ['seleccion'] = $_REQUEST [$esteCampo];
                        } else {
                            $atributos ['seleccion'] = 139;
                        }

                        $matrizItems = array(
                            array(
                                ' ',
                                'Sin Solicitud de Necesidad'
                            )
                        );

                        // $atributos ['matrizItems'] = $matrizItems;
                        // Utilizar lo siguiente cuando no se pase un arreglo:
                        $atributos ['baseDatos'] = 'contractual';
                        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("tipo_moneda");
                        $tab ++;
                        $atributos = array_merge($atributos, $atributosGlobales);
                        echo $this->miFormulario->campoCuadroLista($atributos);
                        unset($atributos);

                        $esteCampo = 'valor_contrato';
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
                        $atributos ['validar'] = 'required,custom[number]';

                        if (isset($_REQUEST [$esteCampo])) {
                            $atributos ['valor'] = $_REQUEST [$esteCampo];
                        } else {
                            $atributos ['valor'] = '';
                        }
                        $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
                        $atributos ['deshabilitado'] = true;
                        $atributos ['tamanno'] = 20;
                        $atributos ['maximoTamanno'] = '';
                        $atributos ['anchoEtiqueta'] = 213;
                        $tab ++;

                        // Aplica atributos globales al control
                        $atributos = array_merge($atributos, $atributosGlobales);
                        echo $this->miFormulario->campoCuadroTexto($atributos);
                        unset($atributos);
                    }
                    // ------------------Fin Division para los botones-------------------------
                    echo $this->miFormulario->division("fin");
                    unset($atributos);

                    $atributos ["id"] = "division";
                    echo $this->miFormulario->division("inicio", $atributos);
                    unset($atributos);

                    $esteCampo = 'ordenador_gasto';
                    $atributos ['columnas'] = 2;
                    $atributos ['nombre'] = $esteCampo;
                    $atributos ['id'] = $esteCampo;
                    $atributos ['evento'] = '';
                    $atributos ['deshabilitado'] = true;
                    $atributos ["etiquetaObligatorio"] = true;
                    $atributos ['tab'] = $tab;
                    $atributos ['tamanno'] = 1;
                    $atributos ['estilo'] = 'jqueryui';
                    $atributos ['validar'] = 'required';
                    $atributos ['limitar'] = false;
                    $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                    $atributos ['anchoEtiqueta'] = 213;
                    $atributos ['anchoCaja'] = 26;
                    if (isset($_REQUEST [$esteCampo])) {
                        $atributos ['seleccion'] = $_REQUEST [$esteCampo];
                    } else {
                        $atributos ['seleccion'] = - 1;
                    }

                    $matrizItems = array(
                        array(
                            ' ',
                            'Sin Solicitud de Necesidad'
                        )
                    );

                    // $atributos ['matrizItems'] = $matrizItems;
                    // Utilizar lo siguiente cuando no se pase un arreglo:
                    $atributos ['baseDatos'] = 'sicapital';
                    $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("ordenadorGasto");
                    $tab ++;
                    $atributos = array_merge($atributos, $atributosGlobales);
                    echo $this->miFormulario->campoCuadroLista($atributos);
                    unset($atributos);

                    $sqlNombreOrdenador = $this->miSql->getCadenaSql("informacion_ordenador", $_REQUEST['ordenador_gasto']);
                    $nombreOrdenador = $DBSICA->ejecutarAcceso($sqlNombreOrdenador, "busqueda");

                    $esteCampo = 'nombreOrdenador';
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
                    $atributos ['validar'] = 'required, minSize[1],maxSize[2000]';

                    if ($nombreOrdenador != false) {
                        $atributos ['valor'] = $nombreOrdenador[0][0];
                    } else {
                        $atributos ['valor'] = '';
                    }
                    $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
                    $atributos ['deshabilitado'] = true;
                    $atributos ['tamanno'] = 35;
                    $atributos ['maximoTamanno'] = '';
                    $atributos ['anchoEtiqueta'] = 213;
                    $tab ++;

                    // Aplica atributos globales al control
                    $atributos = array_merge($atributos, $atributosGlobales);
                    echo $this->miFormulario->campoCuadroTexto($atributos);
                    unset($atributos);

                    $atributos ["id"] = "division";
                    echo $this->miFormulario->division("inicio", $atributos);
                    unset($atributos);
                    {

                        $esteCampo = 'tipo_gasto';
                        $atributos ['columnas'] = 2;
                        $atributos ['nombre'] = $esteCampo;
                        $atributos ['id'] = $esteCampo;
                        $atributos ['evento'] = '';
                        $atributos ['deshabilitado'] = true;
                        $atributos ["etiquetaObligatorio"] = true;
                        $atributos ['tab'] = $tab;
                        $atributos ['tamanno'] = 1;
                        $atributos ['estilo'] = 'jqueryui';
                        $atributos ['validar'] = 'required';
                        $atributos ['limitar'] = true;
                        $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                        $atributos ['anchoEtiqueta'] = 213;
                        $atributos ['anchoCaja'] = 100;
                        if (isset($_REQUEST [$esteCampo])) {
                            $atributos ['seleccion'] = $_REQUEST [$esteCampo];
                        } else {
                            $atributos ['seleccion'] = - 1;
                        }

                        $matrizItems = array(
                            array(
                                ' ',
                                'Sin Solicitud de Necesidad'
                            )
                        );

                        // $atributos ['matrizItems'] = $matrizItems;
                        // Utilizar lo siguiente cuando no se pase un arreglo:
                        $atributos ['baseDatos'] = 'contractual';
                        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("tipo_gasto");
                        $tab ++;
                        $atributos = array_merge($atributos, $atributosGlobales);
                        echo $this->miFormulario->campoCuadroLista($atributos);
                        unset($atributos);

                        $esteCampo = 'origen_recursos';
                        $atributos ['columnas'] = 2;
                        $atributos ['nombre'] = $esteCampo;
                        $atributos ['id'] = $esteCampo;
                        $atributos ['evento'] = '';
                        $atributos ['deshabilitado'] = true;
                        $atributos ["etiquetaObligatorio"] = true;
                        $atributos ['tab'] = $tab;
                        $atributos ['tamanno'] = 1;
                        $atributos ['estilo'] = 'jqueryui';
                        $atributos ['validar'] = 'required';
                        $atributos ['limitar'] = true;
                        $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                        $atributos ['anchoEtiqueta'] = 213;
                        $atributos ['anchoCaja'] = 100;
                        if (isset($_REQUEST [$esteCampo])) {
                            $atributos ['seleccion'] = $_REQUEST [$esteCampo];
                        } else {
                            $atributos ['seleccion'] = - 1;
                        }

                        $matrizItems = array(
                            array(
                                ' ',
                                'Sin Solicitud de Necesidad'
                            )
                        );

                        // $atributos ['matrizItems'] = $matrizItems;
                        // Utilizar lo siguiente cuando no se pase un arreglo:
                        $atributos ['baseDatos'] = 'contractual';
                        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("origen_recursos");
                        $tab ++;
                        $atributos = array_merge($atributos, $atributosGlobales);
                        echo $this->miFormulario->campoCuadroLista($atributos);
                        unset($atributos);
                    }
                    // ------------------Fin Division para los botones-------------------------
                    echo $this->miFormulario->division("fin");
                    unset($atributos);

                    $atributos ["id"] = "division";
                    echo $this->miFormulario->division("inicio", $atributos);
                    unset($atributos);
                    {

                        $esteCampo = 'origen_presupuesto';
                        $atributos ['columnas'] = 2;
                        $atributos ['nombre'] = $esteCampo;
                        $atributos ['id'] = $esteCampo;
                        $atributos ['evento'] = '';
                        $atributos ['deshabilitado'] = true;
                        $atributos ["etiquetaObligatorio"] = true;
                        $atributos ['tab'] = $tab;
                        $atributos ['tamanno'] = 1;
                        $atributos ['estilo'] = 'jqueryui';
                        $atributos ['validar'] = 'required';
                        $atributos ['limitar'] = true;
                        $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                        $atributos ['anchoEtiqueta'] = 213;
                        $atributos ['anchoCaja'] = 100;
                        if (isset($_REQUEST [$esteCampo])) {
                            $atributos ['seleccion'] = $_REQUEST [$esteCampo];
                        } else {
                            $atributos ['seleccion'] = - 1;
                        }

                        $matrizItems = array(
                            array(
                                ' ',
                                'Sin Solicitud de Necesidad'
                            )
                        );

                        // $atributos ['matrizItems'] = $matrizItems;
                        // Utilizar lo siguiente cuando no se pase un arreglo:
                        $atributos ['baseDatos'] = 'contractual';
                        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("origen_presupuesto");
                        $tab ++;
                        $atributos = array_merge($atributos, $atributosGlobales);
                        echo $this->miFormulario->campoCuadroLista($atributos);
                        unset($atributos);

                        $esteCampo = 'tema_gasto_inversion';
                        $atributos ['columnas'] = 2;
                        $atributos ['nombre'] = $esteCampo;
                        $atributos ['id'] = $esteCampo;
                        $atributos ['evento'] = '';
                        $atributos ['deshabilitado'] = true;
                        $atributos ["etiquetaObligatorio"] = true;
                        $atributos ['tab'] = $tab;
                        $atributos ['tamanno'] = 1;
                        $atributos ['estilo'] = 'jqueryui';
                        $atributos ['validar'] = 'required';
                        $atributos ['limitar'] = true;
                        $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                        $atributos ['anchoEtiqueta'] = 213;
                        $atributos ['anchoCaja'] = 100;
                        if (isset($_REQUEST [$esteCampo])) {
                            $atributos ['seleccion'] = $_REQUEST [$esteCampo];
                        } else {
                            $atributos ['seleccion'] = 164;
                        }

                        $matrizItems = array(
                            array(
                                ' ',
                                'Sin Solicitud de Necesidad'
                            )
                        );

                        // $atributos ['matrizItems'] = $matrizItems;
                        // Utilizar lo siguiente cuando no se pase un arreglo:
                        $atributos ['baseDatos'] = 'contractual';
                        $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("tema_gasto");
                        $tab ++;
                        $atributos = array_merge($atributos, $atributosGlobales);
                        echo $this->miFormulario->campoCuadroLista($atributos);
                        unset($atributos);
                    }
                    // ------------------Fin Division para los botones-------------------------
                    echo $this->miFormulario->division("fin");
                    unset($atributos);

                    if ($_REQUEST['tipo_moneda'] != 137) {
                        $estiloDivisionModenaExt = "";
                    } else {
                        $estiloDivisionModenaExt = "display:none";
                    }

                    $atributos ["id"] = "divisionMonedaExtranjera";
                    $atributos = array_merge($atributos, $atributosGlobales);
                    $atributos ["estiloEnLinea"] = $estiloDivisionModenaExt;
                    echo $this->miFormulario->division("inicio", $atributos);
                    unset($atributos);
                    {

                        $esteCampo = 'valor_contrato_moneda_ex';
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

                        if (isset($_REQUEST [$esteCampo])) {
                            $atributos ['valor'] = $_REQUEST [$esteCampo];
                        } else {
                            $atributos ['valor'] = '';
                        }
                        $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
                        $atributos ['deshabilitado'] = true;
                        $atributos ['tamanno'] = 20;
                        $atributos ['maximoTamanno'] = '';
                        $atributos ['anchoEtiqueta'] = 213;
                        $tab ++;

                        // Aplica atributos globales al control
                        $atributos = array_merge($atributos, $atributosGlobales);
                        echo $this->miFormulario->campoCuadroTexto($atributos);
                        unset($atributos);

                        $esteCampo = 'tasa_cambio';
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

                        if (isset($_REQUEST [$esteCampo])) {
                            $atributos ['valor'] = $_REQUEST [$esteCampo];
                        } else {
                            $atributos ['valor'] = '';
                        }
                        $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
                        $atributos ['deshabilitado'] = true;
                        $atributos ['tamanno'] = 20;
                        $atributos ['maximoTamanno'] = '';
                        $atributos ['anchoEtiqueta'] = 213;
                        $tab ++;

                        // Aplica atributos globales al control
                        $atributos = array_merge($atributos, $atributosGlobales);
                        echo $this->miFormulario->campoCuadroTexto($atributos);
                        unset($atributos);
                    }
                    // ------------------Fin Division para los botones-------------------------
                    echo $this->miFormulario->division("fin");
                    unset($atributos);

                    //---------Campo de Seleccion Clausula Presupuestal-------------------------------
                    $esteCampo = 'clausula_presupuesto';
                    $atributos ['id'] = $esteCampo;
                    $atributos ['nombre'] = $esteCampo;
                    $atributos ['estilo'] = 'campoCuadroSeleccionCorta';
                    $atributos ['marco'] = true;
                    $atributos ['estiloMarco'] = true;
                    $atributos ["etiquetaObligatorio"] = true;
                    $atributos ['anchoEtiqueta'] = 70;
                    $atributos ['columnas'] = 3;
                    $atributos ['dobleLinea'] = 1;
                    $atributos ['tabIndex'] = $tab;
                    $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                    $atributos ['validar'] = '';

                    if (isset($_REQUEST [$esteCampo]) && $_REQUEST [$esteCampo] == 't') {
                        $atributos ['valor'] = $_REQUEST [$esteCampo];
                        $atributos ['seleccionado'] = 'checked';
                    } else {
                        $atributos ['valor'] = 'TRUE';
                    }

                    $atributos ['deshabilitado'] = true;
                    $tab ++;
                    //Aplica atributos globales al control
                    $atributos = array_merge($atributos, $atributosGlobales);
                    echo $this->miFormulario->campoCuadroSeleccion($atributos);
                    unset($atributos);

                    $esteCampo = 'formaPago';
                    $atributos ['nombre'] = $esteCampo;
                    $atributos ['id'] = $esteCampo;
                    $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                    $atributos ["etiquetaObligatorio"] = false;
                    $atributos ['tab'] = $tab ++;
                    $atributos ['anchoEtiqueta'] = 120;
                    $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
                    $atributos ['evento'] = '';
                    $atributos ['seleccion'] = 240;
                    $atributos ['deshabilitado'] = true;
                    $atributos ['columnas'] = 2;
                    $atributos ['tamanno'] = 1;
                    $atributos ['estilo'] = "jqueryui";
                    $atributos ['validar'] = 'required';
                    $atributos ['limitar'] = true;
                    $atributos ['anchoCaja'] = 20;
                    $atributos ['cadena_sql'] = $this->miSql->getCadenaSql("forma_pago");
                    $matrizItems = array(
                        array(
                            0,
                            ' '
                        )
                    );
                    $matrizItems = $esteRecursoDB->ejecutarAcceso($atributos ['cadena_sql'], "busqueda");
                    $atributos ['matrizItems'] = $matrizItems;
                    $atributos = array_merge($atributos, $atributosGlobales);
                    echo $this->miFormulario->campoCuadroLista($atributos);
                    unset($atributos);
                    //---------Fin Campo de Seleccion Clausula Presupuestal-------------------------------
                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------

                    $esteCampo = 'descripcion_forma_pago';
                    $atributos ['id'] = $esteCampo;
                    $atributos ['nombre'] = $esteCampo;
                    $atributos ['tipo'] = 'text';
                    $atributos ['estilo'] = 'jqueryui';
                    $atributos ['marco'] = true;
                    $atributos ['estiloMarco'] = '';
                    $atributos ["etiquetaObligatorio"] = true;
                    $atributos ['columnas'] = 145;
                    $atributos ['filas'] = 7;
                    $atributos ['dobleLinea'] = 0;
                    $atributos ['tabIndex'] = $tab;
                    $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                    $atributos ['validar'] = 'required, minSize[1]';
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
                }
                echo "</section>";



                echo" <h3>Objeto de Contrato</h3>
							<section>"; {


                    $esteCampo = "AgrupacionObjetoContrato";
                    $atributos ['id'] = $esteCampo;
                    $atributos ['leyenda'] = "Objeto del Contrato";
                    echo $this->miFormulario->agrupacion('inicio', $atributos); {



                        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                        $esteCampo = 'objeto_contrato';
                        $atributos ['id'] = $esteCampo;
                        $atributos ['nombre'] = $esteCampo;
                        $atributos ['tipo'] = 'text';
                        $atributos ['estilo'] = 'jqueryui';
                        $atributos ['marco'] = true;
                        $atributos ['estiloMarco'] = '';
                        $atributos ["etiquetaObligatorio"] = true;
                        $atributos ['columnas'] = 145;
                        $atributos ['filas'] = 7;
                        $atributos ['dobleLinea'] = 0;
                        $atributos ['tabIndex'] = $tab;
                        $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                        $atributos ['validar'] = 'required, minSize[1]';
                        $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
                        $atributos ['deshabilitado'] = true;
                        $atributos ['tamanno'] = 20;
                        $atributos ['maximoTamanno'] = '';
                        $atributos ['anchoEtiqueta'] = 220;

                        if (isset($_REQUEST ['objeto_contrato'])) {
                            $atributos ['valor'] = $_REQUEST ['objeto_contrato'];
                        } else {
                            $atributos ['valor'] = '';
                        }
                        $tab ++;

                        // Aplica atributos globales al control
                        $atributos = array_merge($atributos, $atributosGlobales);
                        echo $this->miFormulario->campoTextArea($atributos);

                        unset($atributos);

                        $esteCampo = 'actividades';
                        $atributos ['id'] = $esteCampo;
                        $atributos ['nombre'] = $esteCampo;
                        $atributos ['tipo'] = 'text';
                        $atributos ['estilo'] = 'jqueryui';
                        $atributos ['marco'] = true;
                        $atributos ['estiloMarco'] = '';
                        $atributos ["etiquetaObligatorio"] = true;
                        $atributos ['columnas'] = 145;
                        $atributos ['filas'] = 7;
                        $atributos ['dobleLinea'] = 0;
                        $atributos ['tabIndex'] = $tab;
                        $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                        $atributos ['validar'] = 'required, minSize[1]';
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
                    }

                    echo $this->miFormulario->agrupacion('fin');
                    unset($atributos);
                }

                echo "</section>";

                echo "<h3>Justificación y Condiciones</h3><section>";

                $esteCampo = "AgrupacionJustificacionCondiciones";
                $atributos ['id'] = $esteCampo;
                $atributos ['leyenda'] = "Justificación y Condiciones";
                echo $this->miFormulario->agrupacion('inicio', $atributos);
                {
                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                    $esteCampo = 'condiciones';
                    $atributos ['id'] = $esteCampo;
                    $atributos ['nombre'] = $esteCampo;
                    $atributos ['tipo'] = 'text';
                    $atributos ['estilo'] = 'jqueryui';
                    $atributos ['marco'] = true;
                    $atributos ['estiloMarco'] = '';
                    $atributos ["etiquetaObligatorio"] = true;
                    $atributos ['columnas'] = 145;
                    $atributos ['filas'] = 7;
                    $atributos ['dobleLinea'] = 0;
                    $atributos ['tabIndex'] = $tab;
                    $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                    $atributos ['validar'] = 'required, minSize[1]';
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

                    $esteCampo = 'justificacion';
                    $atributos ['id'] = $esteCampo;
                    $atributos ['nombre'] = $esteCampo;
                    $atributos ['tipo'] = 'text';
                    $atributos ['estilo'] = 'jqueryui';
                    $atributos ['marco'] = true;
                    $atributos ['estiloMarco'] = '';
                    $atributos ["etiquetaObligatorio"] = true;
                    $atributos ['columnas'] = 145;
                    $atributos ['filas'] = 7;
                    $atributos ['dobleLinea'] = 0;
                    $atributos ['tabIndex'] = $tab;
                    $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                    $atributos ['validar'] = 'required, minSize[1]';
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
                }

                echo $this->miFormulario->agrupacion('fin');
                unset($atributos);


                echo "</section>";
                echo "<h3>Observaciones</h3><section>";

                $esteCampo = "AgrupacionObservaciones";
                $atributos ['id'] = $esteCampo;
                $atributos ['leyenda'] = "Observaciones";
                echo $this->miFormulario->agrupacion('inicio', $atributos);
                {
                    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------

                    $esteCampo = 'especificaciones_tecnicas';
                    $atributos ['id'] = $esteCampo;
                    $atributos ['nombre'] = $esteCampo;
                    $atributos ['tipo'] = 'text';
                    $atributos ['estilo'] = 'jqueryui';
                    $atributos ['marco'] = true;
                    $atributos ['estiloMarco'] = '';
                    $atributos ["etiquetaObligatorio"] = true;
                    $atributos ['columnas'] = 145;
                    $atributos ['filas'] = 7;
                    $atributos ['dobleLinea'] = 0;
                    $atributos ['tabIndex'] = $tab;
                    $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                    $atributos ['validar'] = 'required, minSize[1]';
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


                    $esteCampo = 'observacionesContrato';
                    $atributos ['id'] = $esteCampo;
                    $atributos ['nombre'] = $esteCampo;
                    $atributos ['tipo'] = 'text';
                    $atributos ['estilo'] = 'jqueryui';
                    $atributos ['marco'] = true;
                    $atributos ['estiloMarco'] = '';
                    $atributos ["etiquetaObligatorio"] = true;
                    $atributos ['columnas'] = 145;
                    $atributos ['filas'] = 7;
                    $atributos ['dobleLinea'] = 0;
                    $atributos ['tabIndex'] = $tab;
                    $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                    $atributos ['validar'] = '';
                    $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
                    $atributos ['deshabilitado'] = true;
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
                    echo $this->miFormulario->campoTextArea($atributos);
                    unset($atributos);
                }

                echo $this->miFormulario->agrupacion('fin');
                unset($atributos);


                echo "</section>";



                // // ---------------- CONTROL: hidden Atributos Contrado--------------------------------------------------------
                $esteCampo = 'atributosContratoTempHidden';
                $atributos ['id'] = $esteCampo;
                $atributos ['nombre'] = $esteCampo;
                $atributos ['tipo'] = 'hidden';
                $atributos ['estilo'] = 'jqueryui';
                $atributos ['marco'] = true;
                $atributos ['columnas'] = 1;
                $atributos ['dobleLinea'] = false;
                $atributos ['tabIndex'] = $tab;
                $atributos ['valor'] = $_REQUEST ['numero_contrato'];
                $atributos ['deshabilitado'] = true;
                $atributos ['tamanno'] = 30;
                $atributos ['maximoTamanno'] = '';
                $tab ++;
                // Aplica atributos globales al control
                $atributos = array_merge($atributos, $atributosGlobales);
                echo $this->miFormulario->campoCuadroTexto($atributos);
                unset($atributos);
            }

            // ------------------Fin Division para los botones-------------------------
            echo $this->miFormulario->division("fin");
            unset($atributos);

            // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
        }



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
        $valorCodificado .= "&numero_contrato=" . $_REQUEST ['numero_contrato'];
        $valorCodificado .= "&vigencia=" . $_REQUEST ['vigencia'];

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
