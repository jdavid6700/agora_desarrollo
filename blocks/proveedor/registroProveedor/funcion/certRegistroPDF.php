<?php

if (!isset($GLOBALS["autorizado"])) {
    include("index.php");
    exit;
}	

ob_end_clean();

//$ruta=$this->miConfigurador->getVariableConfiguracion('raizDocumento');
//include($ruta.'/plugin/html2pdf/html2pdf.class.php');

$ruta = $this->miConfigurador->getVariableConfiguracion("raizDocumento");
require_once ($ruta . "/plugin/mpdf/mpdf.php");

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


$cadenaSql = $this->sql->getCadenaSql ( 'buscarProveedorByID', $_REQUEST ["idProveedor"] );
$resultadoPro = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );


if($_REQUEST['tipoPersona'] == 'JURIDICA'){
	$usuarioRe = "NIT" . $_REQUEST['numDocumento'];
}else{
	$cadenaSql = $this->sql->getCadenaSql ( 'consultar_proveedor_user', $_REQUEST['numDocumento'] );
	$resultadoUsuRe = $frameworkRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
	$usuarioRe = $resultadoUsuRe[0]['id_usuario'];
}
$cadenaSql = $this->sql->getCadenaSql ( 'consultar_proveedor', $usuarioRe );
$resultadoDoc = $frameworkRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );

if(!$resultadoDoc){
	if($_REQUEST['tipoPersona'] == 'JURIDICA'){
		$sigla = 'NIT';
		$cadenaSql = $this->sql->getCadenaSql ( 'consultar_param_esp', '11' );
		$resultadoSigParaDes = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		$desSig = $resultadoSigParaDes[0]['valor_parametro'];
	}else{
		$cadenaSql = $this->sql->getCadenaSql ( 'consultarProveedorNatural', $_REQUEST['numDocumento'] );
		$resultadoSigParaDesNat = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		$cadenaSql = $this->sql->getCadenaSql ( 'consultar_param_esp', $resultadoSigParaDesNat[0]['tipo_documento'] );
		$resultadoSigParaDes = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );

		$sigla = $resultadoSigParaDes[0]['abreviatura'];
		$desSig = $resultadoSigParaDes[0]['valor_parametro'];
	}
}

$certUniversidadImagen = 'sabio_caldas.png';

setlocale(LC_ALL, "es_ES");
$dia = strftime("%d");
$fecha = strftime("de %B del %Y");


$resultado[0]['nom_proveedor'] = $_REQUEST['nomPersona'];
$resultado[0]['num_documento'] = $_REQUEST['numDocumento'];

$resultado[0]['tipo_persona'] = $_REQUEST['tipoPersona'];
$resultado[0]['tipo_documento'] = $resultadoDoc[0]['tipo_identificacion'];

if($resultadoDoc){
	$cadenaSql = $this->sql->getCadenaSql ( 'consultar_sigl_doc', $resultado[0]['tipo_documento'] );
	$resultadoSig = $frameworkRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
	$resultTipo = $resultadoSig[0]['tipo_nombre'] ." (". $resultado[0]['tipo_documento'] . ")";
}else{
	$resultTipo = $desSig ." (". $sigla . ")";
}


$dateP = explode(" ", $resultadoPro[0]['fecha_registro']);
$date = explode("-", $dateP[0]);

$resultadoDate[0]['fecha_reg'] = $date[2]."/".$date[1]."/".$date[0];
$dateNum = $date[1];
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

$datel[2] = $date[2];
$datel[1] = $date[1];
$datel[0] = $date[0] + 2;

$resultadoDate[0]['fecha_lim'] = $datel[2]."/".$datel[1]."/".$datel[0];

switch ($datel[1]) {
	case '01' :
		$datel[1] = "Enero";
		break;

	case '02' :
		$datel[1] = "Febrero";
		break;

	case '03' :
		$datel[1] = "Marzo";
		break;
			
	case "04":
		$datel[1] = "Abril";
		break;
			
	case "05":
		$datel[1] = "Mayo";
		break;

	case '06':
		$datel[1] = "Junio";
		break;

	case '07' :
		$datel[1] = "Julio";
		break;

	case '08' :
		$datel[1] = "Agosto";
		break;

	case '09' :
		$datel[1] = "Septiembre";
		break;

	case "10" :
		$datel[1] = "Octubre";
		break;

	case "11" :
		$datel[1] = "Noviembre";
		break;

	case '12' :
		$datel[1] = "Diciembre";
		break;
}


$resultado[0]['numero_cdp'] = "2211";
$resultado[0]['numero_contrato'] = 12;
$resultado[0]['vigencia'] = 245;
$resultado[0]['objetocontratar'] = 255;
$valorContrto = "1001";

$resultadoActaInicio[0]['fecha_inicio'] = "12/12/2017";
$resultadoActaInicio[0]['fecha_fin'] = "12/12/2017";



if($_REQUEST['tipoPersona'] == 'JURIDICA'){
	$id_tipo = 11;
}else{
	$cadenaSql = $this->sql->getCadenaSql ( 'consultarProveedorNatural', $_REQUEST['numDocumento'] );
	$resultadoSigParaDesNat = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
	$id_tipo = $resultadoSigParaDesNat[0]['tipo_documento'];
}

$marca = abs(($_REQUEST['numDocumento']*$id_tipo)+(date('Ymd')*3333)-(($date[2].$dateNum.$date[0])*$id_tipo));

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
                                font-size : '.$valorTamañoLetra.'pt;
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



$contenidoPagina = "<page backtop='10mm' backbottom='30mm' backleft='20mm' backright='20mm'>";
    
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
    		<br>
			<br>
			<br>
			<br>
    		<table align='center' style='width: 100%;'>
            <tr>
                <td align='center' >
    				<span style='font-size:15.0pt;mso-bidi-font-size:11.0pt;line-height:
107%'>EL SISTEMA DE REGISTRO ÚNICO DE PERSONAS Y BANCO DE PROVEEDORES \"ÁGORA\"</span>
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
			<br>
			<br>
	    <table>
			<tr>
				<td style='text-align: justify'>
					<p>Que <b>".$resultado[0]['nom_proveedor']."</b>, en su calidad de <b>PERSONA ".$resultado[0]['tipo_persona']."</b> con
					<b>".$resultTipo."</b> No. <b>".$resultado[0]['num_documento'].",</b> se encuentra registrado(a) en la Base de Datos del Sistema de Registro Único de Personas y Banco de Proveedores de la 
							Universidad Distrital Francisco José de Caldas, desde el 
							 <b>".$date[2]." de ".$date[1]." del ".$date[0]."</b>.
							</p>
		        		<br>
						<br>
						<p>La presente certificación se expide en la 
						ciudad de Bogotá D.C. a los ".$dia." días del mes ".$fecha.".
						</p>
								
				</td>
		   </tr>
		</table>		
				
				";

$contenidoPagina .= "</page>";


$pie = "
         <table align='center' width = '100%'>

             <tr>
                 <td align='center'>
                     Universidad Distrital Francisco Jos&eacute; de Caldas
                     <br>
                     Todos los derechos reservados.
                     <br>
                     Carrera 8 N. 40-78 Piso 1 / PBX 3239300
                     <br><br>
						<i>RUPBP-".$marca."</i>
						<br>
                   
                 </td>
             </tr>
         </table>
     ";


	$nombreDocumento = 'certificacionRegistro('.$_REQUEST['nomPersona'].').pdf';
	
	//echo $contenidoPagina;exit();
	$mpdf = new mPDF('', 'LETTER', 11, 'ARIAL', 20, 15, 5, 15, 7, 10);
	//$html2pdf = new HTML2PDF('P', 'LETTER', 'es', true, 'UTF-8', 3);
	//$html2pdf->pdf->SetDisplayMode('fullpage');
	//$html2pdf->writeHTML($contenidoPagina);
	$mpdf->writeHTML($contenidoPagina);
	$mpdf->setHTMLFooter($pie);
	//$mpdf->setFooter('{PAGENO}');
	
    //$html2pdf = new HTML2PDF('P','LETTER','es');
    //$res = $html2pdf->WriteHTML($contenidoPagina);
    //$html2pdf->Output($nombreDocumento,'D');
    $mpdf->Output($nombreDocumento,'D');

?>