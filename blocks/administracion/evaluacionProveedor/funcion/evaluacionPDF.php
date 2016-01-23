<?php

if (!isset($GLOBALS["autorizado"])) {
    include("index.php");
    exit;
}	
ob_end_clean();
$ruta=$this->miConfigurador->getVariableConfiguracion('raizDocumento');
//include($ruta.'/core/classes/html2pdf/html2pdf.class.php');
include($ruta.'/plugin/html2pdf/html2pdf.class.php');

//$directorio=$this->miConfigurador->getVariableConfiguracion("rutaUrlBloque");
$directorio=$this->miConfigurador->getVariableConfiguracion("rutaBloque");
$aplicativo=$this->miConfigurador->getVariableConfiguracion("nombreAplicativo");
$url = $this->miConfigurador->configuracion ["host"] . $this->miConfigurador->configuracion ["site"];
$correo=$this->miConfigurador->getVariableConfiguracion("correoAdministrador");


$conexion = "estructura";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
//CONSULTAR CONTRATO
$cadena_sql = $this->sql->getCadenaSql ( "contratoByID", $_REQUEST ['idContrato'] );
$resultadoContrato = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );


//CONSULTAR PROVEEDOR
$cadena_sql = $this->sql->getCadenaSql ( "consultarProveedorByID", $resultadoContrato[0]['id_proveedor'] );
$resultadoProveedor = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );

//CONSULTAR EVALUACION
$cadena_sql = $this->sql->getCadenaSql ( "evalaucionByIdContrato", $_REQUEST ['idContrato'] );
$resultadoEvaluacion = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );

$tiempoEntrega = $resultadoEvaluacion[0]["tiemo_entrega"] == 12?"Si":"No";
$cantidades = $resultadoEvaluacion[0]["cantidades"] == 12?"Si":"No";
$conformidad = $resultadoEvaluacion[0]["conformidad"] == 20?"Si":"No";
$funcionalidadAdicional = $resultadoEvaluacion[0]["funcionalidad_adicional"] == 10?"Si":"No";


$puntajeCumplido = $resultadoEvaluacion[0]["tiemo_entrega"] + $resultadoEvaluacion[0]["cantidades"];
$puntajeCalidad = $resultadoEvaluacion[0]["conformidad"] + $resultadoEvaluacion[0]["funcionalidad_adicional"];
$puntajePosContractual = $resultadoEvaluacion[0]["reclamaciones"] + $resultadoEvaluacion[0]["reclamacion_solucion"] + $resultadoEvaluacion[0]["servicio_venta"];
$puntajeGestion = $resultadoEvaluacion[0]["procedimientos"] + $resultadoEvaluacion[0]["garantia"] + $resultadoEvaluacion[0]["garantia_satisfaccion"];

if( $resultadoEvaluacion[0]["puntaje_total"] > 79 )
	$casificacion = "A";
elseif( $resultadoEvaluacion[0]["puntaje_total"] > 45 )
	$casificacion = "B";
else $casificacion = "C";

$contenidoPagina = "<page backtop='30mm' backbottom='10mm' backleft='20mm' backright='20mm'>";
$contenidoPagina .= "<page_header>
        <table align='center' style='width: 100%;'>
            <tr>
                <td align='center' >
                    <font size='18px'><b>UNIVERSIDAD DISTRITAL</b></font>
                    <br>
                    <font size='18px'><b>FRANCISCO JOS&Eacute; DE CALDAS</b></font>
                    <br>
                </td>
            </tr>
        </table>
    </page_header>
    <page_footer>
        <table align='center' width = '100%'>

            <tr>
                <td align='center'>
                    Universidad Distrital Francisco Jos&eacute; de Caldas
                    <br>
                    Todos los derechos reservados.
                    <br>
                    Carrera 8 N. 40-78 Piso 1 / PBX 3238400 - 3239300
                    <br>
                   
                </td>
            </tr>
        </table>
    </page_footer>";
    
    $contenidoPagina .= "



<p class=MsoNormal align=center style='text-align:center'><b style='mso-bidi-font-weight:
normal'><span style='font-size:18.0pt;mso-bidi-font-size:11.0pt;line-height:
107%'>CONTRATO</span></b></p>



<div align=center>
 
<table align='center' class=MsoTableGrid border=1 cellspacing=0 cellpadding=0
 style='width:80%;border-collapse:collapse;border:none;'>   
 
  <tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes'>
  <td width=123 valign=top style='width:12%;border:solid windowtext 1.0pt; 
  mso-border-alt:solid windowtext .5pt;background:#BDD6EE;mso-background-themecolor:
  accent1;mso-background-themetint:102;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height: 
  normal'><b style='mso-bidi-font-weight:normal'><span style='font-size:12.0pt;
  mso-bidi-font-size:11.0pt'>No. Contrato   </span></b></p>
  </td>
  <td width=236 valign=top style='width:95%;border:solid windowtext 1.0pt;  
  border-left:none;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:
  solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:left;line-height:normal'><span style='font-size:12.0pt;
  mso-bidi-font-size:11.0pt'>".$resultadoContrato[0]['numero_contrato']."</span></p>
  </td>
 </tr>
 
 <tr style='mso-yfti-irow:1'>
  <td width=123 valign=top style='width:92.1pt;border:solid windowtext 1.0pt;
  border-top:none;mso-border-top-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;
  background:#BDD6EE;mso-background-themecolor:accent1;mso-background-themetint:
  102;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><b style='mso-bidi-font-weight:normal'><span style='font-size:12.0pt;
  mso-bidi-font-size:11.0pt'>Fecha Inicio  </span></b></p>
  </td>
  <td width=236 valign=top style='width:177.25pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt'>
" . $resultadoContrato[0]['fecha_inicio'] . "
  </td>
 </tr>

 <tr style='mso-yfti-irow:1'>
  <td width=123 valign=top style='width:92.1pt;border:solid windowtext 1.0pt;
  border-top:none;mso-border-top-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;
  background:#BDD6EE;mso-background-themecolor:accent1;mso-background-themetint:
  102;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><b style='mso-bidi-font-weight:normal'><span style='font-size:12.0pt;
  mso-bidi-font-size:11.0pt'>Fecha Final  </span></b></p>
  </td>
  <td width=236 valign=top style='width:177.25pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt'>
" . $resultadoContrato[0]['fecha_finalizacion'] . "
  </td>
 </tr>


</table>

</div>

<p class=MsoNormal align=center style='text-align:center'><span
style='font-size:12.0pt;mso-bidi-font-size:11.0pt;line-height:107%'> &nbsp; </span></p>

<p class=MsoNormal align=center style='text-align:center'><b style='mso-bidi-font-weight:
normal'><span style='font-size:18.0pt;mso-bidi-font-size:11.0pt;line-height:
107%'>PROVEEDOR</span></b></p>

<div align=center>

<table align='center' class=MsoTableGrid border=1 cellspacing=0 cellpadding=0
 style='border-collapse:collapse;border:none;mso-border-alt:solid windowtext .5pt;
 mso-yfti-tbllook:1184;mso-padding-alt:0cm 5.4pt 0cm 5.4pt'>
 <tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes'>
  <td width=123 valign=top style='width:12%;border:solid windowtext 1.0pt;
  mso-border-alt:solid windowtext .5pt;background:#BDD6EE;mso-background-themecolor:
  accent1;mso-background-themetint:102;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><b style='mso-bidi-font-weight:normal'><span style='font-size:12.0pt;
  mso-bidi-font-size:11.0pt'>NIT </span></b></p>
  </td>
  <td width=236 valign=top style='width:75%;border:solid windowtext 1.0pt; 
  border-left:none;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:
  solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><span style='font-size:12.0pt;
  mso-bidi-font-size:11.0pt'>".$resultadoProveedor[0]['nit']."</span></p>
  </td>
 </tr>
  <tr style='mso-yfti-irow:3'>
  <td width=123 valign=top style='width:92.1pt;border:solid windowtext 1.0pt;
  border-top:none;mso-border-top-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;
  background:#BDD6EE;mso-background-themecolor:accent1;mso-background-themetint:
  102;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><b style='mso-bidi-font-weight:normal'><span style='font-size:12.0pt;
  mso-bidi-font-size:11.0pt'>Nombre Empresa </span></b></p>
  </td>
  <td width=236 valign=top style='width:177.25pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><span style='font-size:12.0pt;
  mso-bidi-font-size:11.0pt'>".$resultadoProveedor[0]['nomempresa']."</span></p>
  </td>
 </tr>

</table>

</div>

<p class=MsoNormal align=center style='text-align:center'><span
style='font-size:12.0pt;mso-bidi-font-size:11.0pt;line-height:107%'> &nbsp; </span></p>

<p class=MsoNormal align=center style='text-align:center'><b style='mso-bidi-font-weight:
normal'><span style='font-size:18.0pt;mso-bidi-font-size:11.0pt;line-height:
107%'>EVALUACIÓN</span></b></p>

<div align=center>

<table align='center' class=MsoTableGrid border=1 cellspacing=0 cellpadding=0
 style='border-collapse:collapse;border:none;mso-border-alt:solid windowtext .5pt;
 mso-yfti-tbllook:1184;mso-padding-alt:0cm 5.4pt 0cm 5.4pt'>
 <tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes'>
  <td width=10 valign=top align=center style='width:12%;border:solid windowtext 1.0pt;
  mso-border-alt:solid windowtext .5pt;background:#BDD6EE;mso-background-themecolor:
  accent1;mso-background-themetint:102;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><b style='mso-bidi-font-weight:normal'><span style='font-size:12.0pt;
  mso-bidi-font-size:11.0pt'>CRITERIO  </span></b></p>
  </td> 
  <td width=16 valign=top align=center style='width:12%;border:solid windowtext 1.0pt;
  mso-border-alt:solid windowtext .5pt;background:#BDD6EE;mso-background-themecolor:
  accent1;mso-background-themetint:102;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><b style='mso-bidi-font-weight:normal'><span style='font-size:12.0pt;
  mso-bidi-font-size:11.0pt'>SUBCRITERIO  </span></b></p>
  </td>
 
  <td width=16 valign=top align=center style='width:12%;border:solid windowtext 1.0pt;
  mso-border-alt:solid windowtext .5pt;background:#BDD6EE;mso-background-themecolor:
  accent1;mso-background-themetint:102;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><b style='mso-bidi-font-weight:normal'><span style='font-size:12.0pt;
  mso-bidi-font-size:11.0pt'>PUNTAJE TOTAL  </span></b></p>
  </td>

 
 </tr>";
 
 //INICIO CUMPLIMIENTO
$contenidoPagina .= " 
              <tr>
                <td rowspan=2 align='center' valign=center >
                    <font size='18px'><b>CUMPLIMIENTO</b></font>
                </td>	
                <td align='center' >
                    <font size='18px'><b>Tiempos de entrega </b></font>
                </td>
                <td rowspan=2 align='center' valign=center >
                    <font size='18px'>" . $puntajeCumplido . "</font>
                </td>                
            </tr>
            
             <tr>
                <td align='center' >
                    <font size='18px'><b>Cantidades </b></font>
                </td>               
            </tr>";
//FIN CUMPLIMIENTO

//INICIO CALIDAD
$contenidoPagina .= " 
              <tr>
                <td rowspan=2 align='center' valign=center >
                    <font size='18px'><b>CALIDAD</b></font>
                </td>	
                <td align='center' >
                    <font size='18px'><b>Conformidad </b></font>
                </td>
                <td rowspan=2 align='center' valign=center >
                    <font size='18px'>" . $puntajeCalidad . "</font>
                </td>                
            </tr>
            
             <tr>
                <td align='center' >
                    <font size='18px'><b>Funcionalidad adicional </b></font>
                </td>               
            </tr>";
//FIN CALIDAD

//INICIO POS CONTRACTUAL
$contenidoPagina .= " 
              <tr>
                <td rowspan=2 align='center' valign=center >
                    <font size='18px'><b>CONTRACTUAL</b></font>
                </td>	
                <td align='center' >
                    <font size='18px'><b>Reclamaciones </b></font>
                </td>
                <td rowspan=2 align='center' valign=center >
                    <font size='18px'>" . $puntajePosContractual . "</font>
                </td>                
            </tr>
            
             <tr>
                <td align='center' >
                    <font size='18px'><b>Servicio pos venta </b></font>
                </td>               
            </tr>";
//FIN POS CONTRACTUAL

//INICIO GESTIoN
$contenidoPagina .= " 
              <tr>
                <td rowspan=2 align='center' valign=center >
                    <font size='18px'><b>GESTIÓN</b></font>
                </td>	
                <td align='center' >
                    <font size='18px'><b>Procedimientos </b></font>
                </td>
                <td rowspan=2 align='center' valign=center >
                    <font size='18px'>" . $puntajeGestion . "</font>
                </td>                
            </tr>
            
             <tr>
                <td align='center' >
                    <font size='18px'><b>Garantía </b></font>
                </td>               
            </tr>";
//FIN GESTIoN

//INICIO TOTAL
$contenidoPagina .= " 
              <tr>
                <td colspan=2  >  </td>
                <td align='center' >
                    <font size='18px'><b>TOTAL : </b> " . $resultadoEvaluacion[0]["puntaje_total"] . "</font>
                    <br>
                    <font size='18px'><b>CLASIFICACIÓN TIPO:</b> " . $casificacion . " </font>
                    <br>
                </td>				
            </tr>";
//FIN TOTAL

$contenidoPagina .= "</table></div></page>";



    $html2pdf = new HTML2PDF('P','LETTER','es');
    $res = $html2pdf->WriteHTML($contenidoPagina);
    $html2pdf->Output('resumenEvaluacion.pdf','D');

?>