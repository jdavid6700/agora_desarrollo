<?php

if (!isset($GLOBALS["autorizado"])) {
    include("index.php");
    exit;
}	
ob_end_clean();
$ruta=$this->miConfigurador->getVariableConfiguracion('raizDocumento');
//include($ruta.'/core/classes/html2pdf/html2pdf.class.php');
include($ruta.'/plugin/html2pdf/html2pdf.class.php');

include($ruta.'/plugin/NumberToLetterConverter.class.php');

$converterNumber = new NumberToLetterConverter();


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


		
		//Buscar usuario para enviar correo
		$cadenaSql = $this->sql->getCadenaSql('buscarProveedores', $_REQUEST['idObjeto']);
		$resultadoProveedor = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
		
		//Buscar usuario para enviar correo
		$cadenaSql = $this->sql->getCadenaSql('infoCotizacion', $_REQUEST["idObjeto"]);
		$resultadoObjeto = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
		
		$cadenaSql = $this->sql->getCadenaSql ( 'dependenciaUdistritalById', $resultadoObjeto[0]['jefe_dependencia'] );
		$resultadoDependencia = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		 
		$cadenaSql = $this->sql->getCadenaSql ( 'ordenadorUdistritalByIdCast', $resultadoObjeto[0]['ordenador_gasto'] );
		$resultadoOrdenadorDef = $argoRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );	
		
		$cadenaSql = $this->sql->getCadenaSql('buscarUsuario', $resultadoObjeto[0]['usuario_creo']);
		$resultadoUsuarioCot = $frameworkRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
		$resultadoOrdenador = $resultadoUsuarioCot[0]['nombre'] . " " . $resultadoUsuarioCot[0]['apellido'];



//CONSULTAR OBJETO A CONTRATAR
// $cadenaSql = $this->sql->getCadenaSql ( 'objetoContratar', $_REQUEST['idObjeto'] );
// $resultadoObjeto = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );

// $cadenaSql = $this->sql->getCadenaSql ( 'buscarUsuario', $resultadoObjeto [0] ['responsable'] );
// $resultadoUsuario = $frameworkRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );


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


if($resultadoObjeto[0]['tipo_necesidad'] == 2 || $resultadoObjeto[0]['tipo_necesidad'] == 3){
	$convocatoria = true;
	
		
	$cadenaSql = $this->sql->getCadenaSql ( 'consultarNBCImp', $_REQUEST["idObjeto"]  );
	$resultadoNBC = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
	
	
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
		
}else{
	$convocatoria = false;
	$contentConv = "";
	$titulo = "INFORMACIÓN DE LA SOLICITUD DE COTIZACIÓN";
}

setlocale(LC_MONETARY,"es_CO");

$contenidoAct = '';

foreach ($resultadoActividades as $dato):
	$contenidoAct .= $dato['subclase'] . ' - ' . $dato['nombre'] . "<br>";
	$contenidoAct .= "<br>";
endforeach;

$totalCotizacion = 0;
$countTotal = 0;

$listProv = "";

if($resultadoProveedor && isset($_REQUEST['proveedoresView']) && $_REQUEST['proveedoresView'] == 'true'){

	$listProv .= "<table align='center' style='width:100%;' border=0.2 cellspacing=0 >

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
                <td rowspan='3' align='center' style='width:20%;' >
                    <b>" . $dato['nom_proveedor'] . "</b>
                </td>
                <td align='center' style='width:30%;' >
                    " . $resultadoRespuestaCot[0]['informacion_entrega'] . "
                </td>
                <td rowspan='3' align='center' style='width:30%;' >
                    " . $resultadoRespuestaCot[0]['des_sc'] . " 		
                </td>                    		
				<td rowspan='3' align='center' style='width:20%;' >
                    " . $totalItem . "
                </td>
            </tr>
                    		
            <tr>
    			<td align='center' style='width:30%;'>
                    <b>(Descuentos)</b><br> " . $resultadoRespuestaCot[0]['descuentos'] . " 		
                </td> 
  			</tr>
  			<tr>
    			<td align='center' style='width:30%;'>
                    <b>(Observaciones)</b><br> " . $resultadoRespuestaCot[0]['observaciones'] . " 		
                </td> 
  			</tr>";
		
	}else{
		
		$listProv .= "
            <tr>
                 <td align='center' style='width:20%;' >
                    <b>" . $dato['nom_proveedor'] . "</b>
                </td>
                <td align='center' style='width:30%;' >
                    " . "SIN RESPUESTA" . "
                </td>
                <td align='center' style='width:30%;' >
                    " . "SIN RESPUESTA" . "
                </td>
				<td align='center' style='width:20%;' >
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


$contenidoPagina = "
		
		
		
		<style>
			

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
      content: '';
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
		
		</style>
		
		
		
		
		<page backtop='10mm' backbottom='10mm' backleft='20mm' backright='20mm'>";
    
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
                <td align='center' style='width:20%;background:#BDD6EE;'>
                    <b>Número de Solicitud de Cotización - Vigencia</b>
                </td>
                <td align='left' style='width:80%;'>
                    " . $resultadoObjeto[0]['numero_solicitud'] . " - " .$resultadoObjeto[0]['vigencia'] ."
                </td>
            </tr>
            <tr>
                <td align='center' style='width:20%;background:#BDD6EE;'>
                    <b>Fecha Solicitud</b>
                </td>
                <td align='left' style='width:80%;'>
                    " . $fechana1 . "
                </td>
            </tr>
                    	
            <tr>
                <td align='center' style='width:20%;background:#BDD6EE;'>
                    <b>Título Cotización</b>
                </td>
                <td align='left' style='width:80%;'>
                    " . $resultadoObjeto[0]['titulo_cotizacion']. "
                </td>
            </tr>
            <tr>
                <td align='center' style='width:20%;background:#BDD6EE;'>
                    <b>Actividad Económica</b>
                </td>
                <td align='left' style='width:80%;'>		
                    " . $contenidoAct . "
                </td>
            </tr>
            . $contentConv . 

 <tr>
                <td align='center' style='width:20%;background:#BDD6EE;'>
                    <b>Fecha Apertura</b>
                </td>
                <td align='left' style='width:80%;'>
                    " . $fechana2 . "
                </td>
            </tr>

 <tr>
                <td align='center' style='width:20%;background:#BDD6EE;'>
                    <b>Fecha Cierre</b>
                </td>
                <td align='left' style='width:80%;'>
                    " . $fechana3 . "
                </td>
            </tr>       		
            <tr>
                <td align='center' style='width:20%;background:#BDD6EE;'>
                    <b>Objetivos/Temas</b>
                </td>
                <td align='left' style='width:80%;'>
                " . $resultadoObjeto[0]['objetivo']. "
                </td>
            </tr>  
            <tr>
                <td align='center' style='width:20%;background:#BDD6EE;'>
                    <b>Requisitos</b>
                </td>
                <td align='left' style='width:80%;'>
                    " . $resultadoObjeto[0]['requisitos']. "
                </td>
            </tr>
			<tr>
                <td align='center' style='width:20%;background:#BDD6EE;'>
                    <b>Observaciones Adicionales</b>
                </td>
                <td align='left' style='width:80%;'>
                    " . $resultadoObjeto[0]['observaciones']. "
                </td>
            </tr>
            <tr>
                <td align='center' style='width:20%;background:#BDD6EE;'>
                    <b>Solicitante</b>
                </td>
                <td align='left' style='width:80%;'>
                    " . $resultadoOrdenador. "
                </td>
            </tr>        		
            <tr>
                <td align='center' style='width:20%;background:#BDD6EE;'>
                    <b>Dependencia Solicitante</b>
                </td>
                <td align='left' style='width:80%;'>
                    " . $resultadoDependencia[0][1]. "
                </td>
            </tr>
            <tr>
                <td align='center' style='width:20%;background:#BDD6EE;'>
                    <b>Responsable de la Cotización</b>
                </td>
                <td align='left' style='width:80%;'>
                    " . $resultadoOrdenadorDef[0][1]. "
                </td>
            </tr>
                    		
                    		

        </table>

</div>

<p class=MsoNormal align=center style='text-align:center'><span
style='font-size:12.0pt;mso-bidi-font-size:11.0pt;line-height:107%'> &nbsp; </span></p>

<div>"
    ."<p class=MsoNormal align=center style='text-align:center'><b style='mso-bidi-font-weight:
normal'><span style='font-size:18.0pt;mso-bidi-font-size:11.0pt;line-height:
107%'>PROVEEDORES RELACIONADOS</span></b></p>"                		
                    		
                    . $listProv . 		
   "          		
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
    </page_footer>
			
			</page>";			



$nombreDocumento = 'objetoCotizacion_' . $_REQUEST['idObjeto'] . '.pdf';

    $html2pdf = new HTML2PDF('P','LETTER','es');
    $res = $html2pdf->WriteHTML($contenidoPagina);
    $html2pdf->Output($nombreDocumento,'D');

?>