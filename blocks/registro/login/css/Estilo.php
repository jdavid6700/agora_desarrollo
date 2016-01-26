<?php
$indice=0;
$estilo[$indice++]="bootstrap.min.css";
$estilo[$indice++]="navbar-fixed-top.css";

$estilo[$indice++]="estiloBloque.css";
$estilo[$indice++]="validationEngine.jquery.css";
$estilo[$indice++]="style.css";
$estilo[$indice++]="grande.css";


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