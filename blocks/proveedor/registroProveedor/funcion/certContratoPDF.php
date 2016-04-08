<?php

if (!isset($GLOBALS["autorizado"])) {
    include("index.php");
    exit;
}	

ob_end_clean();

$ruta=$this->miConfigurador->getVariableConfiguracion('raizDocumento');
include($ruta.'/plugin/html2pdf/html2pdf.class.php');


$directorio=$this->miConfigurador->getVariableConfiguracion("rutaBloque");
$aplicativo=$this->miConfigurador->getVariableConfiguracion("nombreAplicativo");
$url = $this->miConfigurador->configuracion ["host"] . $this->miConfigurador->configuracion ["site"];
$correo=$this->miConfigurador->getVariableConfiguracion("correoAdministrador");


$conexion = "estructura";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );

//CONSULTAR USUARIO
$cadena_sql = $this->sql->getCadenaSql ( "consultarContratoByID", $_REQUEST['idContrato'] );
$resultado = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );

$valorContrto = number_format($resultado[0]['valor']);

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
		
		<p class=MsoNormal style='text-align:center'><span style='font-size:14.0pt;
mso-bidi-font-size:11.0pt;line-height:107%'> <b> CAMILO ANDRÉS BUSTOS PARRA </b>  </span></p>
    		<p class=MsoNormal style='text-align:center'><span style='font-size:10.0pt;
mso-bidi-font-size:11.0pt;line-height:107%'> <b> Jefe Oficina Asesora Jurídica  </b>  </span></p>
    
		<br>
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
107%'>CERTIFICACIÓN</span></b></p>

<p class=MsoNormal style='text-align:justify'><span style='font-size:12.0pt;
mso-bidi-font-size:11.0pt;line-height:107%'>El proveedor <b>".$resultado[0]['nomempresa']."</b>, con
NIT <b>".$resultado[0]['nit']."</b>, prestó sus servicios a este Departamento como se relaciona a continuación:  </span></p>

<p class=MsoNormal style='text-align:justify'><span style='font-size:12.0pt;
mso-bidi-font-size:11.0pt;line-height:107%'> <b>CONTRATO No. " . $resultado[0]['numero_contrato'] . " DE " . $resultado[0]['vigencia'] . "</b>  </span></p>

<p class=MsoNormal style='text-align:justify'><span style='font-size:12.0pt;
mso-bidi-font-size:11.0pt;line-height:107%'> <b>OBJETO : </b>
" . $resultado[0]['objetocontratar'] . "  </span></p>

<p class=MsoNormal style='text-align:justify'><span style='font-size:12.0pt;
mso-bidi-font-size:11.0pt;line-height:107%'> <b>FECHA INICIO - FECHA FIN : </b>
" . $resultado[0]['fecha_inicio'] . '-'  . $resultado[0]['fecha_finalizacion'] . "  </span></p>

<p class=MsoNormal style='text-align:justify'><span style='font-size:12.0pt;
mso-bidi-font-size:11.0pt;line-height:107%'> <b>VALOR DEL CONTRATO : $</b>
" . $valorContrto . "  </span></p>";

$contenidoPagina .= "</page>";


	$nombreDocumento = 'certificacion.pdf';

    $html2pdf = new HTML2PDF('P','LETTER','es');
    $res = $html2pdf->WriteHTML($contenidoPagina);
    $html2pdf->Output($nombreDocumento,'D');

?>