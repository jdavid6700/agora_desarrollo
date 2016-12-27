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

$datosContrato = array (
		'numeroContrato' => $_REQUEST['numeroContrato'],
		'vigenciaContrato' => $_REQUEST['vigenciaContrato']
);

//CONSULTAR USUARIO
$cadena_sql = $this->sql->getCadenaSql ( "consultarContratosARGOByNum",  $datosContrato);
$resultado = $argoRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );

$numeroContratistas = count($resultado);


// echo $cadena_sql;
// var_dump($_REQUEST);

// var_dump($resultado);

$datosContrato = array (
		'num_contrato' => $resultado[0]['numero_contrato'],
		'vigencia' => $resultado[0]['vigencia'],
		'unidad_ejecutora' => $resultado[0]['unidad_ejecutora']
);


$cadena_sql = $this->sql->getCadenaSql ( "consultarActaInicio", $datosContrato);
$resultadoActaInicio = $argoRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );

$fechaInicio = date("d/m/Y", strtotime($resultadoActaInicio[0]['fecha_inicio']));
$fechaFin = date("d/m/Y", strtotime($resultadoActaInicio[0]['fecha_fin']));
	
$cadena_sql = $this->sql->getCadenaSql ( "consultarNovedadesContrato", $datosContrato);
$resultadoNovedades = $argoRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );


$cadena_sql = $this->sql->getCadenaSql ( "listaContratoXNumContrato", $datosContrato);
$resultadoContrato = $argoRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );

$cadenaSql = $this->sql->getCadenaSql ( 'consultar_proveedor', $_REQUEST ["usuario"] );
$resultadoDoc = $frameworkRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );



$resultado[0]['nom_proveedor'] = $_REQUEST['nomProveedor'];
$resultado[0]['num_documento'] = $_REQUEST['docProveedor'];

$resultado[0]['tipo_persona'] = $_REQUEST['tipoProveedor'];
$resultado[0]['tipo_documento'] = $resultadoDoc[0]['tipo_identificacion'];

$resultado[0]['numero_cdp'] = $resultadoContrato[0]['numero_cdp'];
$resultado[0]['numero_rp'] = $resultadoContrato[0]['numero_rp'];
$resultado[0]['numero_contrato'] = $resultadoContrato[0]['numero_contrato'];
$resultado[0]['vigencia'] = $resultadoContrato[0]['vigencia'];
$resultado[0]['objetocontratar'] = $resultadoContrato[0]['objeto_contrato'];


$valorContrto = number_format($resultadoContrato[0]['valor_contrato']);

$certUniversidadImagen = 'sabio_caldas.png';

//$date = explode("-", $resultado[0]['fecha_rp']);
$date = explode("-", $resultadoContrato[0]['fecha_registro']);

$dateIn = explode("-", $resultadoActaInicio[0]['fecha_inicio']);
$dateFi = explode("-", $resultadoActaInicio[0]['fecha_fin']);

$resultadoActaInicio[0]['fecha_inicio'] = $dateIn[2]."/".$dateIn[1]."/".$dateIn[0];
$resultadoActaInicio[0]['fecha_fin'] = $dateFi[2]."/".$dateFi[1]."/".$dateFi[0];

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
mso-bidi-font-size:11.0pt;line-height:107%'>Que la persona <b>".$resultado[0]['nom_proveedor']."</b>, en su calidad de PERSONA <b>".$resultado[0]['tipo_persona']."</b> con
<b>".$resultado[0]['tipo_documento']."</b> No. <b>".$resultado[0]['num_documento'].",</b> prestó sus servicios en calidad de contratista con la 
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
" . $resultadoActaInicio[0]['fecha_inicio'] . "  <br><b>FECHA DE FINALIZACIÓN: </b>
" . $resultadoActaInicio[0]['fecha_fin'] . "  </span></p>
		
		<p class=MsoNormal style='text-align:justify'><span style='font-size:12.0pt;
mso-bidi-font-size:11.0pt;line-height:107%'>La presente certificación se expide en la 
		ciudad de Bogotá D.C. a los ".$dia." días del mes ".$fecha.".
		</span>
		</p>
				
	
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