<?php
$indice = 0;
$funcion [$indice ++] = "select2.js";
$funcion [$indice ++] = "select2_locale_es.js";
$funcion [$indice ++] = "jquery.dataTables.js";
$funcion [$indice ++] = "jquery.autocomplete.js";
$funcion [$indice ++] = "jquery.autocomplete.min.js";

$funcion [$indice++]  = "jquery.dataTables.min.js";

$funcion [$indice++]  = "dataTables.bootstrap.min.js";
$funcion [$indice++]  = "dataTables.bootstrap.js";
$funcion [$indice++]  = "dataTables.jqueryui.js";
$funcion [$indice++]  = "dataTables.jqueryui.min.js";
// $funcion[$indice++]="jquery.formToWizard.js";


$embebido [$indice] = true;
$funcion [$indice ++] = "ajax.php";

$rutaBloque = $this->miConfigurador->getVariableConfiguracion ( "host" );
$rutaBloque .= $this->miConfigurador->getVariableConfiguracion ( "site" );

if ($esteBloque ["grupo"] == "") {
	$rutaBloque .= "/blocks/" . $esteBloque ["nombre"];
} else {
	$rutaBloque .= "/blocks/" . $esteBloque ["grupo"] . "/" . $esteBloque ["nombre"];
}

$_REQUEST['tiempo']=time();


if (isset ( $funcion [0] )) {
	foreach ( $funcion as $clave => $nombre ) {
		if (! isset ( $embebido [$clave] )) {
			echo "\n<script type='text/javascript' src='" . $rutaBloque . "/script/" . $nombre . "'>\n</script>\n";
		} else {
			echo "\n<script type='text/javascript'>";
			include ($nombre);
			echo "\n</script>\n";
		}
	}
}

?>
