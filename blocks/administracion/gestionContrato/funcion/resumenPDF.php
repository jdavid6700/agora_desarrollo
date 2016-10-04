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

$conexion = "sicapital";
$siCapitalRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );

//CONSULTAR USUARIO
$cadenaSql = $this->sql->getCadenaSql ( 'buscarProveedores', $_REQUEST['idObjeto'] );
$resultadoProveedor = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );



//CONSULTAR OBJETO A CONTRATAR
$cadenaSql = $this->sql->getCadenaSql ( 'objetoContratar', $_REQUEST['idObjeto'] );
$resultadoObjeto = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );


$datos = array (
		'idSolicitud' => $resultadoObjeto[0]['numero_solicitud'],
		'vigencia' => $resultadoObjeto[0]['vigencia']
);


$cadenaSql = $this->sql->getCadenaSql ( 'listaSolicitudNecesidadXNumSolicitud', $datos );
$solicitudNecesidad = $siCapitalRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );


$certUniversidadImagen = 'sabio_caldas.png';
$directorio=$this->miConfigurador->getVariableConfiguracion("rutaBloque");


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
        <table align='center' width = '100%'>

            <tr>
                <td align='center'>
                    Universidad Distrital Francisco Jos&eacute; de Caldas
                    <br>
                    Todos los derechos reservados.
                    <br>
                    Carrera 8 N. 40-78 Piso 1 / PBX 3238400 - 3239300
                    <br>
                    Codigo de Validación : " . $_REQUEST['idCodigo']  . "
                   
                </td>
            </tr>
        </table>
    </page_footer>";
    
    $contenidoPagina .= "



<p class=MsoNormal align=center style='text-align:center'><b style='mso-bidi-font-weight:
normal'><span style='font-size:18.0pt;mso-bidi-font-size:11.0pt;line-height:
107%'>OBJETO SOLICITUD DE NECESIDAD A COTIZAR</span></b></p>



<div align=center>
        
        <table align='center' class=MsoTableGrid border=1 cellspacing=5 cellpadding=5
 style='width:100%;border-collapse:collapse;border:none;'> 
 

            <tr>
                <td align='center' style='width:20%;background:#BDD6EE'>
                    <b>Fecha</b>
                </td>
                <td align='left' style='width:80%;'>
                    " . $resultadoObjeto[0]['fechasolicitudcotizacion'] . "
                </td>
            </tr>
                    	
            <tr>
                <td align='center' style='width:20%;background:#BDD6EE'>
                    <b>Objeto Solicitud de Necesidad</b>
                </td>
                <td align='left' style='width:80%;'>
                    " . $solicitudNecesidad [0]['OBJETO'] . "
                </td>
            </tr>
            <tr>
                <td align='center' style='width:20%;background:#BDD6EE'>
                    <b>Actividad Económica</b>
                </td>
                <td align='left' style='width:80%;'>
                    " . $resultadoObjeto[0]['codigociiu'] . '-' . $resultadoObjeto[0]['actividad'] . "
                </td>
            </tr>
            <tr>
                <td align='center' style='width:20%;background:#BDD6EE'>
                    <b>Justificación</b>
                </td>
                <td align='left' style='width:80%;'>
                    " . $solicitudNecesidad [0]['JUSTIFICACION'] . "
                </td>
            </tr>
            <tr>
                <td align='center' style='width:20%;background:#BDD6EE'>
                    <b>Número de Solicitud de Necesidad - Vigencia</b>
                </td>
                <td align='left' style='width:80%;'>
                    " . $solicitudNecesidad [0]['NUM_SOL_ADQ'] . " - " . $solicitudNecesidad [0]['VIGENCIA'] . "
                </td>
            </tr>  
            <tr>
                <td align='center' style='width:20%;background:#BDD6EE'>
                    <b>Cantidad</b>
                </td>
                <td align='left' style='width:80%;'>
                    " . $resultadoObjeto[0]['cantidad'] . "
                </td>
            </tr>
            <tr>
                <td align='center' style='width:20%;background:#BDD6EE'>
                    <b>Dependencia</b>
                </td>
                <td align='left' style='width:80%;'>
                    " . $solicitudNecesidad [0]['DEPENDENCIA'] . "
                </td>
            </tr>
            <tr>
                <td align='center' style='width:20%;background:#BDD6EE'>
                    <b>Ordenador del Gasto</b>
                </td>
                <td align='left' style='width:80%;'>
                    " . $solicitudNecesidad [0]['ORDENADOR_GASTO'] . " - " . $solicitudNecesidad [0]['CARGO_ORDENADOR_GASTO'] . "
                </td>
            </tr>
                    		
                    		

        </table>

</div>

<p class=MsoNormal align=center style='text-align:center'><span
style='font-size:12.0pt;mso-bidi-font-size:11.0pt;line-height:107%'> &nbsp; </span></p>

<p class=MsoNormal align=center style='text-align:center'><b style='mso-bidi-font-weight:
normal'><span style='font-size:18.0pt;mso-bidi-font-size:11.0pt;line-height:
107%'>PROVEEDORES SELECCIONADOS</span></b></p>

<div align=center>";
            
if($resultadoProveedor){
	
	$contenidoPagina .= "<table align='center' class=MsoTableGrid border=1 cellspacing=5 cellpadding=5
    style='width:100%;border-collapse:collapse;border:none;'> 

            <tr>
                <td align='center' style='background:#BDD6EE'>
                    <b>Documento</b>
                </td>
                <td align='center' style='background:#BDD6EE'>
                    <b>Proveedor</b>
                </td>
                <td align='center' style='background:#BDD6EE'>
                    <b>Puntaje Evaluación</b>
                </td>
                <td align='center' style='background:#BDD6EE'>
                    <b>Clasificación</b>
                </td>
            </tr>";
	
	foreach ($resultadoProveedor as $dato):
	$contenidoPagina .= "
            <tr>
                <td align='center' >
                    " . $dato['num_documento'] . "
                </td>
                <td align='center' >
                    " . $dato['nom_proveedor'] . "
                </td>
                <td align='center' >
                    " . $dato['puntaje_evaluacion'] . "
                </td>
                <td align='center' >
                    " . $dato['clasificacion_evaluacion'] . "
                </td>
	
	
	
            </tr>";
	endforeach;
	
	$contenidoPagina .= "</table></div></page>";
	
}else{
	
	$contenidoPagina .= "
            
                NO EXISTEN PROVEEDORES RELACIONADOS
            </div></page>";
	
}
    
			



$nombreDocumento = 'objetoContratar_' . $_REQUEST['idObjeto'] . '.pdf';

    $html2pdf = new HTML2PDF('P','LETTER','es');
    $res = $html2pdf->WriteHTML($contenidoPagina);
    $html2pdf->Output($nombreDocumento,'D');

?>