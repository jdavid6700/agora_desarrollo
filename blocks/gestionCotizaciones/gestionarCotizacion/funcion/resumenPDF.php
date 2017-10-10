<?php

if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
}

ob_end_clean();

$ruta = $this->miConfigurador->getVariableConfiguracion("raizDocumento");

$host = $this->miConfigurador->getVariableConfiguracion("host") . $this->miConfigurador->getVariableConfiguracion("site") . "/plugin/html2pfd/";

require_once ($ruta . "/plugin/mpdf/mpdf.php");

include($ruta . '/plugin/NumberToLetterConverter.class.php');

class RegistradorOrden {

    var $miConfigurador;
    var $lenguaje;
    var $miFormulario;
    var $miFuncion;
    var $sql;
    var $conexion;

    function __construct($lenguaje, $sql, $funcion) {
        $this->miConfigurador = \Configurador::singleton();
        $this->miConfigurador->fabricaConexiones->setRecursoDB('principal');
        $this->lenguaje = $lenguaje;
        $this->sql = $sql;
        $this->miFuncion = $funcion;
    }

    function documento() {

        //$directorio=$this->miConfigurador->getVariableConfiguracion("rutaUrlBloque");


        $converterNumber = new NumberToLetterConverter();

//*************************************************************************** DBMS *******************************
        //****************************************************************************************************************


        $directorio = $this->miConfigurador->getVariableConfiguracion("rutaBloque");
        $aplicativo = $this->miConfigurador->getVariableConfiguracion("nombreAplicativo");
        $url = $this->miConfigurador->configuracion ["host"] . $this->miConfigurador->configuracion ["site"];
        $correo = $this->miConfigurador->getVariableConfiguracion("correoAdministrador");

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
        //Buscar usuario para enviar correo
        //Buscar usuario para enviar correo
        $cadenaSql = $this->sql->getCadenaSql('buscarProveedores', $_REQUEST['idObjeto']);
        $resultadoProveedor = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");

        $cadenaSql = $this->sql->getCadenaSql('infoCotizacion', $_REQUEST["idObjeto"]);
        $resultadoObjeto = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");

        $cadenaSql = $this->sql->getCadenaSql('dependenciaUdistritalById', $resultadoObjeto[0]['jefe_dependencia']);
        $resultadoDependencia = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");

        $cadenaSql = $this->sql->getCadenaSql('ordenadorUdistritalByIdCast', $resultadoObjeto[0]['ordenador_gasto']);
        $resultadoOrdenadorDef = $argoRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");

        $cadenaSql = $this->sql->getCadenaSql('buscarUsuario', $resultadoObjeto[0]['usuario_creo']);
        $resultadoUsuarioCot = $frameworkRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
        $resultadoOrdenador = $resultadoUsuarioCot[0]['nombre'] . " " . $resultadoUsuarioCot[0]['apellido'];


        ereg("([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})", $resultadoObjeto[0]['fecha_solicitud_cotizacion'], $mifecha);
        $fechana1 = $mifecha[3] . "/" . $mifecha[2] . "/" . $mifecha[1];

        ereg("([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})", $resultadoObjeto[0]['fecha_apertura'], $mifecha);
        $fechana2 = $mifecha[3] . "/" . $mifecha[2] . "/" . $mifecha[1];

        ereg("([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})", $resultadoObjeto[0]['fecha_cierre'], $mifecha);
        $fechana3 = $mifecha[3] . "/" . $mifecha[2] . "/" . $mifecha[1];

        $datos = array(
            'idSolicitud' => $resultadoObjeto[0]['numero_solicitud'],
            'vigencia' => $resultadoObjeto[0]['vigencia'],
            'unidadEjecutora' => $resultadoObjeto[0]['unidad_ejecutora']
        );


        $certUniversidadImagen = 'sabio_caldas.png';
        $directorio = $this->miConfigurador->getVariableConfiguracion("rutaBloque");


        $cadenaSql = $this->sql->getCadenaSql('consultarActividadesImp', $_REQUEST['idObjeto']);
        $resultadoActividades = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");


        if ($resultadoObjeto[0]['tipo_necesidad'] == 2 || $resultadoObjeto[0]['tipo_necesidad'] == 3) {
            $convocatoria = true;


            $cadenaSql = $this->sql->getCadenaSql('consultarNBCImp', $_REQUEST["idObjeto"]);
            $resultadoNBC = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");


            $contentConv = "
                                                                <tr>
                                                        <td align='center' style='width:20%;background:#BDD6EE;'>
                                                            <b>Profesión Relacionada (Núcleo Básico de Conocimiento SNIES)</b>
                                                        </td>
                                                        <td align='left' style='width:80%;'>		
                                                            " . $resultadoNBC[0]['nucleo'] . ' - ' . $resultadoNBC[0]['nombre'] . "
                                                        </td>
                                                    </tr>	
                                                    ";
            $titulo = "INFORMACIÓN DE LA SOLICITUD DE COTIZACIÓN";
        } else {
            $convocatoria = false;
            $contentConv = "";
            $titulo = "INFORMACIÓN DE LA SOLICITUD DE COTIZACIÓN";
        }

        setlocale(LC_MONETARY, "es_CO");


        $contenidoAct = '';

        foreach ($resultadoActividades as $dato):
            $contenidoAct .= $dato['subclase'] . ' - ' . $dato['nombre'] . "<br>";
            $contenidoAct .= "<br>";
        endforeach;
        
        
        $totalCotizacion = 0;
                $countTotal = 0;

                $listProv = "";

                if($resultadoProveedor && isset($_REQUEST['proveedoresView']) && $_REQUEST['proveedoresView'] == 'true'){

                        $listProv .= "<table align='center' style='width:100%;table-layout:fixed;' border=0.2 cellspacing=0 >

                            <thead>
                                          <tr>
                                                <th align='center' style='width:20%;'>
                                    <b>Nombre</b>
                                </th>
                                <th align='center' style='width:30%;'>
                                    <b>Condiciones Ofrecidas</b>
                                </th>
                                <th align='center' style='width:30%;'>
                                    <b>Objeto</b>
                                </th>
                                                <th align='center' style='width:20%;'>
                                    <b>Valor Ofrecido</b>
                                </th>
                                          </tr>
                            </thead>
                                        <tbody>";

                        foreach ($resultadoProveedor as $dato):

                        $datosSC = array (
                                        'id' => $resultadoObjeto[0]['id'],
                                        'proveedor' => $dato['id_proveedor']
                        );
                        $cadenaSql = $this->sql->getCadenaSql ( 'informacionRespuestaPDFCotizacion', $datosSC  );
                        $resultadoRespuestaCot = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );

                        if($resultadoRespuestaCot){

                                $count = count($resultadoRespuestaCot);
                                $totalItem = 0;
                                $i = 0;

                                while ($i < $count){

                                        $totalItem += ((float)$resultadoRespuestaCot[$i]['cantidad'] * (float)$resultadoRespuestaCot[$i]['valor_unitario']);
                                        $i++;
                                }

                                $totalCotizacion += $totalItem;
                                $totalItem = money_format('%#15.0n', $totalItem);
                                $countTotal++;

                                if($resultadoRespuestaCot[0]['descuentos'] == null){
                                        $resultadoRespuestaCot[0]['descuentos'] = "NO APLICA";
                                }

                                if($resultadoRespuestaCot[0]['observaciones'] == null){
                                        $resultadoRespuestaCot[0]['observaciones'] = "SIN OBSERVACIONES";
                                }

                                $listProv .= "
                            <tr>
                                <td rowspan='3' align='center' style='width:20%;border-top: 0.1px solid black;' >
                                    <b>" . $dato['nom_proveedor'] . "</b>
                                </td>
                                <td align='center' style='width:30%;border-top: 0.1px solid black;' >
                                   " . wordwrap($resultadoRespuestaCot[0]['informacion_entrega'],30,"<br>",TRUE) . "
                                </td>
                                <td rowspan='3' align='center' style='width:30%;border-top: 0.1px solid black;' >
                                   " . wordwrap($resultadoRespuestaCot[0]['des_sc'],30,"<br>",TRUE) . " 		
                                </td>                    		
                                                <td rowspan='3' align='center' style='width:20%;border-top: 0.1px solid black;' >
                                    " . $totalItem . "
                                </td>
                            </tr>

                            <tr>
                                        <td align='center' style='width:30%'  >
                                    <b>(Descuentos)</b><br> " . wordwrap($resultadoRespuestaCot[0]['descuentos'] , 30, "<br>" ,TRUE). " 		
                                </td> 
                                        </tr>
                                        <tr>
                                        <td align='center' style='width:30%'>
                                    <b>(Observaciones)</b><br> " . wordwrap($resultadoRespuestaCot[0]['observaciones'],30,"<br>",TRUE ). " 		
                                </td> 
                                        </tr>";

                        }else{

                                $listProv .= "
                            <tr>
                                 <td align='center' style='width:20%;border-top: 0.1px solid black;' >
                                    <b>" . $dato['nom_proveedor'] . "</b>
                                </td>
                                <td align='center' style='width:30%;border-top: 0.1px solid black;' >
                                    " . "SIN RESPUESTA" . "
                                </td>
                                <td align='center' style='width:30%;border-top: 0.1px solid black;' >
                                    " . "SIN RESPUESTA" . "
                                </td>
                                                <td align='center' style='width:20%;border-top: 0.1px solid black;' >
                                    " . "NO APLICÓ" . "
                                </td>
                            </tr>";

                        }


                        endforeach;

                        $listProv .= "
                            <tr>
                                <td align='right' colspan='3' style='background:#B4B4B4;' >
                                    " . "VALOR PROMEDIO" . "
                                </td>
                                                <td align='center' >
                                    " . money_format('%#15.0n', (int)($totalCotizacion/$countTotal)) . "
                                </td>
                            </tr>
                            </tbody>";


                        $promedio = ($totalCotizacion/$countTotal);
                        if($promedio > 999999999 && $promedio <= 999999999999){

                                $restCast = substr((int)$promedio, -9);
                                $rest = str_replace ( $restCast , "" , (int)$promedio );
                                $rest = str_pad($rest, 3, '0', STR_PAD_LEFT);

                                if ($rest == '001') {
                                        $converted = 'MIL ';
                                } else if (intval($rest) > 0) {
                                        $converted = sprintf('%sMIL ', $converterNumber->convertGroup($rest));
                                }

                                $converted .= $converterNumber->to_word($restCast, 'COP');
                        }else{
                                $converted = $converterNumber->to_word($promedio, 'COP');
                        }

                        $dineroCast = $converted;

                        $listProv .= "</table>

                                        <p>
                                        <b>VALOR PROMEDIO EN LETRAS:</b> ".$dineroCast."
                                        </p>

                                        </div>";

                }else{

                        $listProv .= "

                                LOS PROVEEDORES HAN SIDO INFORMADOS, SE PODRA VER LA INFORMACIÓN DE LOS MISMOS, UNA VEZ SE CIERRE EL PERIODO DE COTIZACIÓN.
                            </div>";

                }
        
        
        


        //ARMAR PAGINA DE IMPRESION





        $estilos = '                  
                            <style type="text/css">

                            .divTable{
                                    display: table;
                                    width: 100%;
                            }
                            .divTableRow {
                                    display: table-row;
                            }
                            .divTableHeading {
                                    background-color: #EEE;
                                    display: table-header-group;
                            }
                            .divTableCell, .divTableHead {
                                    border: 1px solid #999999;
                                    display: table-cell;
                                    padding: 3px 10px;
                            }
                            .divTableHeading {
                                    background-color: #EEE;
                                    display: table-header-group;
                                    font-weight: bold;
                            }
                            .divTableFoot {
                                    background-color: #EEE;
                                    display: table-footer-group;
                                    font-weight: bold;
                            }
                            .divTableBody {
                                    display: table-row-group;
                            }


                            /* Create three equal columns that floats next to each other */
                            .column {
                                float: left;
                                padding: 10px;
                                display: table-cell;
                            }

                            /* Clear floats after the columns */
                            .row:after {
                                content: "";
                                display: table;
                                clear: both;
                            }
                            .todo {
                              border: 0.5px solid black;
                            }

                            /* Style the footer */
                            .footer {
                                background-color: #f1f1f1;
                                padding: 10px;
                                text-align: center;
                            }
                            
                            table {
                                  background-color: white;
                                  padding: 1em;
                                  &, * {
                                    border-color: #27ae60;
                                    }
                                  th {
                                    text-transform: uppercase;
                                    font-weight: 300;
                                    text-align: center;
                                    color: white;
                                    background-color: #27ae60;
                                    position: relative;
                                    &:after {
                                      content: "";
                                      display: block;
                                      height: 5px;
                                      right: 0;
                                      left: 0;
                                      bottom: 0;
                                      background-color: #16a085;
                                      position: absolute;
                                      }
                                    }
                                 
                                  }

                             #credits {
                                  text-align: right;
                                  color: white;
                                  a {
                                    color: #16a085;
                                    text-decoration: none;
                                    &:hover {
                                      text-decoration: underline;
                                      }
                                    }
                                  }

                            /* Responsive layout - makes the three columns stack on top of each other instead of next to each other */
                            @media (max-width: 600px) {
                                .column {
                                    width: 100%;
                                }
                            }
                            
                            
                            </style>';


        $contenidoPagina .= "               <page backtop='10mm' backleft='20mm' backright='20mm'>      
            

                                            <table align='center' style='width: 100%;' word-break: break-all;>
                                                                <tr>
                                                                        <td align='center' >
                                                                                <img src='" . $directorio . "/images/" . $certUniversidadImagen . "' width='120' height='150'/>
                                                                                <br>
                                                                        </td>
                                                                </tr>
                                                    <tr>
                                                        <td align='center' >
                                                            <font size='18px'><b>UNIVERSIDAD DISTRITAL</b></font>
                                                            <br>
                                                            <font size='18px'><b>FRANCISCO JOS&Eacute; DE CALDAS</b></font>
                                                            <br>
                                                        </td>
                                                    </tr>
                                        </table>

                                        <p class=MsoNormal align=center style='text-align:center'><b style='mso-bidi-font-weight:
                                        normal'><span style='font-size:18.0pt;mso-bidi-font-size:11.0pt;line-height:
                                        107%'>" . $titulo . "</span></b></p>";

        $contenidoPagina .= '


                                <div class="todo">
                                  <div class="row" style="background-color:#b3ccff;">
                                    <div class="column" width="14.9%" style="background-color:#b3ccff;"><b>Número de Solicitud de Cotización - Vigencia</b></div>
                                    <div class="column" height="9%" width="79%" style="background-color:#ffffff;border-left: 0.5px solid black;"> ' . $resultadoObjeto[0]['numero_solicitud'] . " - " . $resultadoObjeto[0]['vigencia'] . ' </div>
                                  </div>
                                </div>
                                <div class="todo">
                                  <div class="row" style="background-color:#b3ccff;">
                                    <div class="column" width="14.9%" style="background-color:#b3ccff;"><b>Fecha Solicitud</b></div>
                                    <div class="column" height="4%" width="79%" style="background-color:#ffffff;border-left: 0.5px solid black;"> ' . $fechana1 . ' </div>
                                  </div>
                                </div>
                                <div class="todo">
                                  <div class="row" style="background-color:#b3ccff;">
                                    <div class="column" width="14.9%" style="background-color:#b3ccff;"><b>Título Cotización</b></div>
                                    <div class="column" width="79%" style="background-color:#ffffff;border-left: 0.5px solid black;"> ' . $resultadoObjeto[0]['titulo_cotizacion'] .  ' </div>
                                  </div>
                                </div>
                                <div class="todo">
                                  <div class="row" style="background-color:#b3ccff;">
                                    <div class="column" width="14.9%" style="background-color:#b3ccff;"><b>Actividad Económica</b></div>
                                    <div class="column" width="79%" style="background-color:#ffffff;border-left: 0.5px solid black;"> ' . $contenidoAct . ' </div>
                                  </div>
                                </div>
                                <div class="todo" >
                                  <div class="row" style="background-color:#b3ccff;">
                                    <div class="column" width="14.9%" style="background-color:#b3ccff;"><b>Fecha Apertura</b></div>
                                    <div class="column" height="50px" width="79%" style="background-color:#ffffff;border-left: 0.5px solid black;"> ' . $fechana2 .' </div>
                                  </div>
                                </div>
                                <div class="todo">
                                  <div class="row" style="background-color:#b3ccff;">
                                    <div class="column" width="14.9%" style="background-color:#b3ccff;"><b>Fecha Cierre</b></div>
                                    <div class="column" height="50px" width="79%" style="background-color:#ffffff;border-left: 0.5px solid black;"> ' . $fechana3 . ' </div>
                                  </div>
                                </div>
                                <div class="todo">
                                  <div class="row" style="background-color:#b3ccff;">
                                    <div class="column" width="14.9%" style="background-color:#b3ccff;"><b>Objetivos<br>/Temas</b></div>
                                    <div class="column" width="79%" style="background-color:#ffffff;border-left: 0.5px solid black;"> ' . $resultadoObjeto[0]['objetivo'] . ' </div>
                                  </div>
                                </div>
                                <div class="todo">
                                  <div class="row" style="background-color:#b3ccff;">
                                    <div class="column" width="14.9%" style="background-color:#b3ccff;"><b>Requisitos</b></div>
                                    <div class="column" width="79%" style="background-color:#ffffff;border-left: 0.5px solid black;"> ' . $resultadoObjeto[0]['requisitos'] . ' </div>
                                  </div>
                                </div>
                                <div class="todo">
                                  <div class="row" style="background-color:#b3ccff;">
                                    <div class="column" width="14.9%" style="background-color:#b3ccff;"><b>Observaciones Adicionales</b></div>
                                    <div class="column" width="79%" style="background-color:#ffffff;border-left: 0.5px solid black;"> ' . $resultadoObjeto[0]['observaciones'] . ' </div>
                                  </div>
                                </div>
                                <div class="todo">
                                  <div class="row" style="background-color:#b3ccff;">
                                    <div class="column" width="14.9%" style="background-color:#b3ccff;"><b>Solicitante</b></div>
                                    <div class="column" height="50px" width="79%" style="background-color:#ffffff;border-left: 0.5px solid black;"> ' . $resultadoOrdenador . ' </div>
                                  </div>
                                </div>
                                <div class="todo">
                                  <div class="row" style="background-color:#b3ccff;">
                                    <div class="column" width="14.9%" style="background-color:#b3ccff;"><b>Dependencia Solicitante</b></div>
                                    <div class="column" height="50px" width="79%" style="background-color:#ffffff;border-left: 0.5px solid black;"> ' . $resultadoDependencia[0][1] . ' </div>
                                  </div>
                                </div>
                                <div class="todo">
                                  <div class="row" style="background-color:#b3ccff;">
                                    <div class="column" width="14.9%" style="background-color:#b3ccff;"><b>Responsable de la Cotización</b></div>
                                    <div class="column" height="50px" width="79%" style="background-color:#ffffff;border-left: 0.5px solid black;"> ' . $resultadoOrdenadorDef[0][1] . ' </div>
                                  </div>
                                </div>


                                   
';

     $contenidoPagina .= "       <table style='page-break-inside: avoid;'>
         
                                <tr>
                                <td align=center>
                                <p class=MsoNormal align=center style='text-align:center'><span
                                style='font-size:12.0pt;mso-bidi-font-size:11.0pt;line-height:107%'> &nbsp; </span></p>
"
                                
                                    ."<p class=MsoNormal align=center style='text-align:center'><b style='mso-bidi-font-weight:
                                normal'><span style='font-size:18.0pt;mso-bidi-font-size:11.0pt;line-height:
                                107%'>PROVEEDORES RELACIONADOS</span></b></p></td></tr> <tr><td>".$listProv."</td></tr>

<tr>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
</tr>
<tr><td align='center'>________________________________________________</td></tr>                                    
<tr>
                                        <td align='center'>
                                            Universidad Distrital Francisco Jos&eacute; de Caldas
                                            <br>
                                            Todos los derechos reservados.
                                            <br>
                                            Carrera 8 N. 40-78 Piso 1 / PBX 3238400 - 3239300
                                            <br>
                                            Codigo de Validación : " . $_REQUEST['idCodigo'] . "

                                        </td>
                                    </tr>
                                  </table>  
                                     

                                                </page>";
        
     





        $contenidoPiePagina = "";



        $datosContrato[0] = 'aaa';
        $datosContrato[1] = 'bbb';


        $contenidoPaginaEncabezado = '';
        
        $nombreDocumento = 'objetoCotizacion_' . $_REQUEST['idObjeto'];

        $textos = array(0 => $contenidoPaginaEncabezado, 1 => $contenidoPagina, 2 => $contenidoPiePagina, 3 => $estilos, 4 => $nombreDocumento );

        return $textos;
    }

}

$miRegistrador = new RegistradorOrden($this->lenguaje, $this->sql, $this->funcion);

$textos = $miRegistrador->documento();

$mpdf = new mPDF('', 'LETTER', 10, 'ARIAL', 20, 15, 5, 15, 7, 10);


//$mpdf->shrink_tables_to_fit=1;
//$mpdf->tableMinSizePriority = true;
//$mpdf->AddPage();
//// asignamos los estilos
$mpdf->WriteHTML($textos[3], 1);
$mpdf->setFooter('{PAGENO}');
$mpdf->SetHTMLHeader($textos[0], 'O', true);

// colocamos el html para el documento
$mpdf->WriteHTML($textos[1]);
// colocamos el html para el pie de pagina
//$mpdf->setHTMLFooter($textos[2]);
//$mpdf->setFooter('{PAGENO}');
// establecemos el nombre del archivo

$mpdf->Output($textos[4] . '.pdf', 'D');
?>
