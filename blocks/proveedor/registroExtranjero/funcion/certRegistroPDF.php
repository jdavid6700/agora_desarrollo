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


//*************************************************************************** DBMS *******************************
//****************************************************************************************************************

$conexion = 'estructura';
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

$conexion = 'sicapital';
$siCapitalRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

$conexion = 'centralUD';
$centralUDRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

$conexion = 'argo_contratos';
$argoRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

$conexion = 'core_central';
$coreRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

$conexion = 'framework';
$frameworkRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

//*************************************************************************** DBMS *******************************
//****************************************************************************************************************


$cadenaSql = $this->sql->getCadenaSql ( 'consultar_proveedor', $_REQUEST ["usuario"] );
$resultadoDoc = $frameworkRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );


$cadenaSql = $this->sql->getCadenaSql ( 'buscarProveedorByID', $_REQUEST ["idProveedor"] );
$resultadoPro = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );

//echo $cadenaSql;

//var_dump($_REQUEST);

//var_dump($resultadoPro);

//exit();

$certUniversidadImagen = 'sabio_caldas.png';

setlocale(LC_ALL, "es_ES");
$dia = strftime("%d");
$fecha = strftime("de %B del %Y");


$resultado[0]['nom_proveedor'] = $_REQUEST['nomPersona'];
$resultado[0]['num_documento'] = $_REQUEST['numDocumento'];

$resultado[0]['tipo_persona'] = $_REQUEST['tipoPersona'];
$resultado[0]['tipo_documento'] = $resultadoDoc[0]['tipo_identificacion'];



$dateP = explode(" ", $resultadoPro[0]['fecha_registro']);
$date = explode("-", $dateP[0]);


$resultadoDate[0]['fecha_reg'] = $date[2]."/".$date[1]."/".$date[0];

//var_dump($date);
//var_dump($date2);

$resultado[0]['numero_cdp'] = "2211";
$resultado[0]['numero_contrato'] = 12;
$resultado[0]['vigencia'] = 245;
$resultado[0]['objetocontratar'] = 255;
$valorContrto = "1001";

$resultadoActaInicio[0]['fecha_inicio'] = "12/12/2017";
$resultadoActaInicio[0]['fecha_fin'] = "12/12/2017";


$contenidoPagina = "<page backtop='20mm' backbottom='20mm' backleft='20mm' backright='20mm'>";
// $contenidoPagina .= "<page_header>
//         <table align='center' style='width: 100%;'>
// 			<tr>
// 				<td align='center' >
// 					<img src='" . $directorio . "/images/" . $certUniversidadImagen . "' width='120' height='150'/>
// 					<br>
// 				</td>
// 			</tr>
//             <tr>
//                 <td align='center' >
//                     <font size='18px'><b>UNIVERSIDAD DISTRITAL</b></font>
//                     <br>
//                     <font size='18px'><b>FRANCISCO JOS&Eacute; DE CALDAS</b></font>
//                     <br>
//                 </td>
//             </tr>
//         </table>
//     </page_header>
//     <page_footer>
		
// 		<p class=MsoNormal style='text-align:center'><span style='font-size:14.0pt;
// mso-bidi-font-size:11.0pt;line-height:107%'> <b> CAMILO ANDRÉS BUSTOS PARRA </b></span> <span style='font-size:10.0pt;
// mso-bidi-font-size:11.0pt;line-height:107%'> <br> <b> Jefe Oficina Asesora Jurídica  </b> </span></p>
    
// 		<br>
//         <table align='center' width = '100%'>

//             <tr>
//                 <td align='center'>
//                     Universidad Distrital Francisco Jos&eacute; de Caldas
//                     <br>
//                     Todos los derechos reservados.
//                     <br>
//                     Carrera 8 N. 40-78 Piso 1 / PBX 3238400 - 3239300
//                     <br>
                   
//                 </td>
//             </tr>
//         </table>
//     </page_footer>";
    
    $contenidoPagina .= "
    		
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
    		
    		<table align='center' style='width: 100%;'>
            <tr>
                <td align='center' >
    				<span style='font-size:15.0pt;mso-bidi-font-size:11.0pt;line-height:
107%'>EL SISTEMA ÚNICO DE PERSONAS Y BANCO DE PROVEEDORES \"ÁGORA\" DE LA
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
mso-bidi-font-size:11.0pt;line-height:107%'>Que la persona <b>".$resultado[0]['nom_proveedor']."</b>, en su calidad de PERSONA <b>".$resultado[0]['tipo_persona']."</b> con
<b>".$resultado[0]['tipo_documento']."</b> No. <b>".$resultado[0]['num_documento'].",</b> se encuentra registrado en la Base de Datos del Sistema de Registro Único de Personas y Banco de Proveedores de la 
		Universidad Distrital Francisco José de Caldas, con fecha de registro 
		 <b>".$resultadoDate[0]['fecha_reg']."</b> y su información se encuentra disponible para los procesos que la Universidad requiera.</span>
		</p>


		
		<p class=MsoNormal style='text-align:justify'><span style='font-size:12.0pt;
mso-bidi-font-size:11.0pt;line-height:107%'>La presente certificación se expide en la 
		ciudad de Bogotá D.C. a los ".$dia." días del mes ".$fecha.".
		</span>
		</p>
				
	
	<page_footer>
		
 		<p class=MsoNormal style='text-align:center'><span style='font-size:14.0pt;
   mso-bidi-font-size:11.0pt;line-height:107%'> <b> ÁGORA </b></span> <span style='font-size:10.0pt;
   mso-bidi-font-size:11.0pt;line-height:107%'> <br> <b> Sistema de Registro Único de Personas y Banco de Proveedores  </b> </span></p>
    
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
     </page_footer>			
				
				";

$contenidoPagina .= "</page>";


	$nombreDocumento = 'certificacion.pdf';
	
	
	$html2pdf = new HTML2PDF('P', 'LETTER', 'es', true, 'UTF-8', 3);
	//$html2pdf->pdf->SetDisplayMode('fullpage');
	$html2pdf->writeHTML($contenidoPagina);
	
    //$html2pdf = new HTML2PDF('P','LETTER','es');
    //$res = $html2pdf->WriteHTML($contenidoPagina);
    $html2pdf->Output($nombreDocumento,'D');

?>