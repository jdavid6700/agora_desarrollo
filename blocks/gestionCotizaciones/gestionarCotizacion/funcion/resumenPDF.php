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


//*************************************************************************** DBMS *******************************
		//****************************************************************************************************************
		
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

//CONSULTAR USUARIO
$cadenaSql = $this->sql->getCadenaSql ( 'buscarProveedores', $_REQUEST['idObjeto'] );
$resultadoProveedor = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );



//CONSULTAR OBJETO A CONTRATAR
$cadenaSql = $this->sql->getCadenaSql ( 'objetoContratar', $_REQUEST['idObjeto'] );
$resultadoObjeto = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );

$cadenaSql = $this->sql->getCadenaSql ( 'buscarUsuario', $resultadoObjeto[0]['responsable'] );
$resultadoUsuario = $frameworkRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );

$cadenaSql = $this->sql->getCadenaSql ( 'buscarSolicitante', $resultadoObjeto[0]['id_solicitante'] );
$resultadoSolicitante = $argoRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );

ereg("([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})", $resultadoObjeto[0]['fecha_solicitud_cotizacion'], $mifecha);
$fechana1 = $mifecha[3] . "/" . $mifecha[2] . "/" . $mifecha[1];

ereg("([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})", $resultadoObjeto[0]['fecha_apertura'], $mifecha);
$fechana2 = $mifecha[3] . "/" . $mifecha[2] . "/" . $mifecha[1];

ereg("([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})", $resultadoObjeto[0]['fecha_cierre'], $mifecha);
$fechana3 = $mifecha[3] . "/" . $mifecha[2] . "/" . $mifecha[1];

$datos = array (
		'idSolicitud' => $resultadoObjeto[0]['numero_solicitud'],
		'vigencia' => $resultadoObjeto[0]['vigencia'],
		'unidadEjecutora' => $resultadoObjeto[0]['unidad_ejecutora']
);


$certUniversidadImagen = 'sabio_caldas.png';
$directorio=$this->miConfigurador->getVariableConfiguracion("rutaBloque");


$cadenaSql = $this->sql->getCadenaSql ( 'consultarActividadesImp', $_REQUEST['idObjeto']  );
$resultadoActividades = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );


if($resultadoObjeto[0]['tipo_necesidad'] == 'SERVICIO' || $resultadoObjeto[0]['tipo_necesidad'] == 'BIEN Y SERVICIO'){
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
	$titulo = "INFORMACIÓN DE LA SOLICITUD DE COTIZACIÓN";
		
}else{
	$convocatoria = false;
	$contentConv = "";
	$titulo = "INFORMACIÓN DE LA SOLICITUD DE COTIZACIÓN";
}


$contenidoAct = '';

foreach ($resultadoActividades as $dato):
	$contenidoAct .= $dato['id_subclase'] . ' - ' . $dato['nombre'] . "<br>";
	$contenidoAct .= "<br>";
endforeach;

$listProv = "";


if($resultadoProveedor && isset($_REQUEST['proveedoresView']) && $_REQUEST['proveedoresView'] == 'true'){

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
                    <b>Correo</b>
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
                    " . $dato['correo'] . "
                </td>



            </tr>";
	endforeach;

	$listProv .= "</table></div>";

}else{

	$listProv .= "

                LOS PROVEEDORES HAN SIDO INFORMADOS, SE PODRA VER LA INFORMACIÓN DE LOS MISMOS, UNA VEZ SE CIERRE EL PERIODO DE COTIZACIÓN.
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
                    <b>Número de Solicitud de Cotización - Vigencia</b>
                </td>
                <td align='left' style='width:80%;'>
                    " . $resultadoObjeto[0]['numero_solicitud'] . " - " .$resultadoObjeto[0]['vigencia'] ."
                </td>
            </tr>
            <tr>
                <td align='center' style='width:20%;background:#BDD6EE'>
                    <b>Fecha Solicitud</b>
                </td>
                <td align='left' style='width:80%;'>
                    " . $fechana1 . "
                </td>
            </tr>
                    	
            <tr>
                <td align='center' style='width:20%;background:#BDD6EE'>
                    <b>Título Cotización</b>
                </td>
                <td align='left' style='width:80%;'>
                    " . $resultadoObjeto[0]['titulo_cotizacion']. "
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
                    <b>Fecha Apertura</b>
                </td>
                <td align='left' style='width:80%;'>
                    " . $fechana2 . "
                </td>
            </tr>

 <tr>
                <td align='center' style='width:20%;background:#BDD6EE'>
                    <b>Fecha Cierre</b>
                </td>
                <td align='left' style='width:80%;'>
                    " . $fechana3 . "
                </td>
            </tr>       		
            <tr>
                <td align='center' style='width:20%;background:#BDD6EE'>
                    <b>Objetivos/Temas</b>
                </td>
                <td align='left' style='width:80%;'>
                " . $resultadoObjeto[0]['objetivo']. "
                </td>
            </tr>  
            <tr>
                <td align='center' style='width:20%;background:#BDD6EE'>
                    <b>Requisitos</b>
                </td>
                <td align='left' style='width:80%;'>
                    " . $resultadoObjeto[0]['requisitos']. "
                </td>
            </tr>
			<tr>
                <td align='center' style='width:20%;background:#BDD6EE'>
                    <b>Observaciones Adicionales</b>
                </td>
                <td align='left' style='width:80%;'>
                    " . $resultadoObjeto[0]['observaciones']. "
                </td>
            </tr>
            <tr>
                <td align='center' style='width:20%;background:#BDD6EE'>
                    <b>Solicitante</b>
                </td>
                <td align='left' style='width:80%;'>
                    " . $resultadoSolicitante[0][0]. "
                </td>
            </tr>        		
            <tr>
                <td align='center' style='width:20%;background:#BDD6EE'>
                    <b>Dependencia Solicitante</b>
                </td>
                <td align='left' style='width:80%;'>
                    " . $resultadoObjeto[0]['dependencia']. "
                </td>
            </tr>
            <tr>
                <td align='center' style='width:20%;background:#BDD6EE'>
                    <b>Responsable de la Cotización</b>
                </td>
                <td align='left' style='width:80%;'>
                    " . $resultadoUsuario[0]['identificacion']. " - " . $resultadoUsuario[0]['nombre'] . " " . $resultadoUsuario[0]['apellido']. "
                </td>
            </tr>
                    		
                    		

        </table>

</div>

<p class=MsoNormal align=center style='text-align:center'><span
style='font-size:12.0pt;mso-bidi-font-size:11.0pt;line-height:107%'> &nbsp; </span></p>

<p class=MsoNormal align=center style='text-align:center'><b style='mso-bidi-font-weight:
normal'><span style='font-size:18.0pt;mso-bidi-font-size:11.0pt;line-height:
107%'>PROVEEDORES RELACIONADOS</span></b></p>

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



$nombreDocumento = 'objetoCotizacion_' . $_REQUEST['idObjeto'] . '.pdf';

    $html2pdf = new HTML2PDF('P','LETTER','es');
    $res = $html2pdf->WriteHTML($contenidoPagina);
    $html2pdf->Output($nombreDocumento,'D');

?>