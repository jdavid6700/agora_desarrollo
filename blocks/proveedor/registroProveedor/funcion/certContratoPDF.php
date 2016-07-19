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

$certUniversidadImagen = 'sabio_caldas.png';

$date = explode("-", $resultado[0]['fecha_rp']);

$dateIn = explode("-", $resultado[0]['fecha_inicio']);
$dateFi = explode("-", $resultado[0]['fecha_finalizacion']);

$resultado[0]['fecha_inicio'] = $dateIn[2]."/".$dateIn[1]."/".$dateIn[0];
$resultado[0]['fecha_finalizacion'] = $dateFi[2]."/".$dateFi[1]."/".$dateFi[0];

switch ($date[1]) {
	case '01' :
		$date[1] = "Enero";
		break;

	case '02' :
		$date[1] = "Febrero";
		break;

	case '03' :
		$date[1] = "Marzo";
		break;
			
	case "04":
		$date[1] = "Abril";
		break;
			
	case "05":
		$date[1] = "Mayo";
		break;
		
	case '06':
		$date[1] = "Junio";
		break;
		
	case '07' :
		$date [1] = "Julio";
		break;
	
	case '08' :
		$date [1] = "Agosto";
		break;
	
	case '09' :
		$date [1] = "Septiembre";
		break;
	
	case "10" :
		$date [1] = "Octubre";
		break;
	
	case "11" :
		$date [1] = "Noviembre";
		break;
	
	case '12' :
		$date [1] = "Diciembre";
		break;
}

setlocale(LC_ALL, "es_ES");
$dia = strftime("%d");
$fecha = strftime("de %B del %Y");

$contenidoPagina = "<page backtop='60mm' backbottom='10mm' backleft='20mm' backright='20mm'>";
$contenidoPagina .= "<page_header>
        <table align='center' style='width: 100%;'>
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
    </page_header>
    <page_footer>
		
		<p class=MsoNormal style='text-align:center'><span style='font-size:14.0pt;
mso-bidi-font-size:11.0pt;line-height:107%'> <b> CAMILO ANDRÉS BUSTOS PARRA </b></span> <span style='font-size:10.0pt;
mso-bidi-font-size:11.0pt;line-height:107%'> <br> <b> Jefe Oficina Asesora Jurídica  </b> </span></p>
    
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
    		
    		<table align='center' style='width: 100%;'>
            <tr>
                <td align='center' >
    				<span style='font-size:15.0pt;mso-bidi-font-size:11.0pt;line-height:
107%'>EL SUSCRITO JEFE DE LA OFICINA ASESORA DE JURÍDICA DE LA
UNIVERSIDAD DISTRITAL FRANCISCO JOSÉ DE CALDAS</span>
                </td>
            </tr>
    		<tr>
                <td align='center' >
    				<br>
    				<br>
    				<br>
                    <font size='30px'><b>CERTIFICA:</b></font>
    				<br>
    				<br>
                </td>
            </tr>
        </table>


<p class=MsoNormal style='text-align:justify'><span style='font-size:12.0pt;
mso-bidi-font-size:11.0pt;line-height:107%'>Que el proveedor <b>".$resultado[0]['nom_proveedor']."</b>, con
NIT (o CC) No. <b>".$resultado[0]['num_documento']."</b>, prestó sus servicios en calidad de contratista con la 
		Universidad Distrital Francisco José de Caldas, respaldada con la Disponibilidad Presupuestal 
		No. <b>".$resultado[0]['numero_cdp']."</b> y Registro Presupuestal No. <b>".$resultado[0]['numero_rp']."</b>  
				con fecha de <b>".$date[2]."</b> de <b>".$date[1]."</b> del <b>".$date[0]."</b> y en virtud 
						del siguiente contrato:</span>
		</p>

<p class=MsoNormal style='text-align:justify'><span style='font-size:12.0pt;
mso-bidi-font-size:11.0pt;line-height:107%'><b>CONTRATO No. " . $resultado[0]['numero_contrato'] . " DE " . $resultado[0]['vigencia'] . "</b>  </span></p>

<p class=MsoNormal style='text-align:justify'><span style='font-size:12.0pt;
mso-bidi-font-size:11.0pt;line-height:107%'><b>OBJETO : </b><br> <br>
" . $resultado[0]['objetocontratar'] . "  </span></p>
		<br>
		<p class=MsoNormal style='text-align:justify'><span style='font-size:12.0pt;
mso-bidi-font-size:11.0pt;line-height:107%'> <b>VALOR DEL CONTRATO : $</b>
" . $valorContrto . "  </span></p>

<p class=MsoNormal style='text-align:justify'><span style='font-size:12.0pt;
mso-bidi-font-size:11.0pt;line-height:107%'><b>FECHA DE INICIO: </b>
" . $resultado[0]['fecha_inicio'] . "  <br><b>FECHA DE FINALIZACIÓN: </b>
" . $resultado[0]['fecha_finalizacion'] . "  </span></p>
		
		<p class=MsoNormal style='text-align:justify'><span style='font-size:12.0pt;
mso-bidi-font-size:11.0pt;line-height:107%'>La presente certificación se expide en la 
		ciudad de Bogotá D.C. a los ".$dia." días del mes ".$fecha.".
		</span>
		</p>";

$contenidoPagina .= "</page>";


	$nombreDocumento = 'certificacion.pdf';

    $html2pdf = new HTML2PDF('P','LETTER','es');
    $res = $html2pdf->WriteHTML($contenidoPagina);
    $html2pdf->Output($nombreDocumento,'D');

?>