<?php
$indice=0;

$estilo[$indice++]="ui.jqgrid.css";
$estilo[$indice++]="ui.multiselect.css";
$estilo[$indice++]="timepicker.css";
$estilo[$indice++]="jquery-te.css";
$estilo[$indice++]="validationEngine.jquery.css";
$estilo[$indice++]="autocomplete.css";
$estilo[$indice++]="chosen.css";
$estilo[$indice++]="select2.css";
$estilo[$indice++]="jquery_switch.css";

// Tablas
$estilo[$indice++]="demo_page.css";
$estilo[$indice++]="demo_table.css";
$estilo[$indice++]="jquery.dataTables.css";
$estilo[$indice++]="jquery.dataTables_themeroller.css";
$estilo[$indice++]="jquery.auto-complete.css";
$estilo[$indice++]="bootstrap-theme.css";
$estilo[$indice++]="bootstrap-theme.min.css";
$estilo[$indice++]="jquery.dataTables.css";
$estilo[$indice++]="dataTables.tableTools.css";
$estilo[$indice++]="bootstrap.css";
$estilo[$indice++]="bootstrap.min.css";

//if(isset($_REQUEST['pagina'])){
//	if($_REQUEST['pagina'] == "modificaProveedor" || $_REQUEST['pagina'] == "registroProveedor"){
//		$estilo[$indice++]="miestilo.css";
//		
//		$estilo[$indice++]="bootstrap.min.css";
//		$estilo[$indice++]="miestiloInput.css";
//		
//		
//	}
//}
//
//$estilo[$indice++]="timepicker.css";
//$estilo[$indice++]="validationEngine.jquery.css";
//$estilo[$indice++]="jquery.auto-complete.css";
//$estilo[$indice++]="select2.css";
//
//
//
//// $estilo[$indice++]="formToWizard.css";
//
//// Tablas
////$estilo[$indice++]="demo_table.css";
////$estilo[$indice++]="jquery.dataTables.css";
////$estilo[$indice++]="jquery.dataTables.min.css";
////$estilo[$indice++]="dataTables.bootstrap4.css";
////$estilo[$indice++]="dataTables.bootstrap4.min.css";
//$estilo[$indice++]="jquery.dataTables_themeroller.css";
//
//
//
//// Tablas
//$estilo[$indice++]="demo_page.css";
//// $estilo[$indice++]="demo_table.css";
////$estilo[$indice++]="jquery.dataTables.css";
//$estilo[$indice++]="jquery.dataTables_themeroller.css";

$rutaBloque=$this->miConfigurador->getVariableConfiguracion("host");
$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site");

if($unBloque["grupo"]==""){
	$rutaBloque.="/blocks/".$unBloque["nombre"];
}else{
	$rutaBloque.="/blocks/".$unBloque["grupo"]."/".$unBloque["nombre"];
}

foreach ($estilo as $nombre){
	echo "<link rel='stylesheet' type='text/css' href='".$rutaBloque."/css/".$nombre."'>\n";
}
?>
