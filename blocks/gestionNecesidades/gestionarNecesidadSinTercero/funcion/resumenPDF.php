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


$conexion = "agora";
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
		'vigencia' => $resultadoObjeto[0]['vigencia'],
		'unidadEjecutora' => $resultadoObjeto[0]['unidad_ejecutora']
);


$cadenaSql = $this->sql->getCadenaSql ( 'listaSolicitudNecesidadXNumSolicitud', $datos );
$solicitudNecesidad = $siCapitalRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );


$certUniversidadImagen = 'sabio_caldas.png';
$directorio=$this->miConfigurador->getVariableConfiguracion("rutaBloque");


$cadenaSql = $this->sql->getCadenaSql ( 'consultarActividadesImp', $_REQUEST['idObjeto']  );
$resultadoActividades = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );


if($resultadoObjeto[0]['tipo_necesidad'] == 'SERVICIO'){
	$convocatoria = true;
	
		
	$cadenaSql = $this->sql->getCadenaSql ( 'consultarNBCImp', $_REQUEST["idObjeto"]  );
	$resultadoNBC = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
	
	
	$contentConv = "
						<tr>
			                <td align='center' style='width:20%;background:#BDD6EE'>
			                    <b>Profesión Relacionada (Núcleo Básico de Conocimiento SNIES)</b>
			                </td>
			                <td align='left' style='width:80%;'>		
			                    " . $resultadoNBC[0]['id_nucleo'] . ' - ' . $resultadoNBC[0]['nombre'] . "
			                </td>
			            </tr>	
				    ";
	$titulo = "OBJETO SOLICITUD DE NECESIDAD PARA CONVOCATORIA";
		
}else{
	$convocatoria = false;
	$contentConv = "";
	$titulo = "OBJETO SOLICITUD DE NECESIDAD A COTIZAR";
}


$contenidoAct = '';

foreach ($resultadoActividades as $dato):
	$contenidoAct .= $dato['id_subclase'] . ' - ' . $dato['nombre'] . "<br>";
	$contenidoAct .= "<br>";
endforeach;

if(!isset($solicitudNecesidad [0]['OBJETO'])) $solicitudNecesidad [0]['OBJETO'] = "SIN INFORMACIÓN ";
if(!isset($solicitudNecesidad [0]['JUSTIFICACION'])) $solicitudNecesidad [0]['JUSTIFICACION'] = "SIN INFORMACIÓN ";
if(!isset($solicitudNecesidad [0]['DEPENDENCIA'])) $solicitudNecesidad [0]['DEPENDENCIA'] = "SIN INFORMACIÓN ";
if(!isset($solicitudNecesidad [0]['ORDENADOR_GASTO'])) $solicitudNecesidad [0]['ORDENADOR_GASTO'] = "SIN INFORMACIÓN ";
if(!isset($solicitudNecesidad [0]['CARGO_ORDENADOR_GASTO'])) $solicitudNecesidad [0]['CARGO_ORDENADOR_GASTO'] = "SIN INFORMACIÓN ";

$listProv = "";

if($resultadoProveedor){

	$listProv .= "<table align='center' class=MsoTableGrid border=1 cellspacing=5 cellpadding=5
    style='width:100%;border-collapse:collapse;border:none;'>

            <tr>
                <td align='center' style='background:#BDD6EE'>
                    <b>Documento</b>
                </td>
				<td align='center' style='background:#BDD6EE'>
                    <b>Tipo</b>
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
	$listProv .= "
            <tr>
                <td align='center' >
                    " . $dato['num_documento'] . "
                </td>
                 <td align='center' >
                    " . " ". $dato['tipopersona'] . " " . "
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

	$listProv .= "</table></div>";

}else{

	$listProv .= "

                NO EXISTEN PROVEEDORES RELACIONADOS
            </div>";

}


$contenidoPagina = "<page backtop='10mm' backbottom='10mm' backleft='20mm' backright='20mm'>";
    
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

<p class=MsoNormal align=center style='text-align:center'><b style='mso-bidi-font-weight:
normal'><span style='font-size:18.0pt;mso-bidi-font-size:11.0pt;line-height:
107%'>" . $titulo . "</span></b></p>



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
                    " . $contenidoAct . "
                </td>
            </tr>
            . $contentConv .        		
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

<div align=center>"
                    . $listProv .		
   "<page_footer>
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
    </page_footer>
			
			</page>";			



$nombreDocumento = 'objetoContratar_' . $_REQUEST['idObjeto'] . '.pdf';

    $html2pdf = new HTML2PDF('P','LETTER','es');
    $res = $html2pdf->WriteHTML($contenidoPagina);
    $html2pdf->Output($nombreDocumento,'D');

?>