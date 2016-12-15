<?php
/**
 *
 * Los datos del bloque se encuentran en el arreglo $esteBloque.
 */

?>


///////////////Función que se encarga de hacer dinámico el campo categoría////////////////  
<?php

$url = $this->miConfigurador->getVariableConfiguracion ( "host" );
$url .= $this->miConfigurador->getVariableConfiguracion ( "site" );
$url .= "/index.php?";

// Variables
$cadenaACodificar = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( "pagina" );
$cadenaACodificar .= "&procesarAjax=true";
$cadenaACodificar .= "&action=index.php";
$cadenaACodificar .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificar .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificar .= "&funcion=consultarPersona";
$cadenaACodificar .= "&tiempo=" . $_REQUEST ['tiempo'];
// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion ( "enlace" );
$cadena = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $cadenaACodificar, $enlace );
// URL definitiva
$urlFinal = $url . $cadena;
?>

function consultarPersona(elem, request, response){
	$.ajax({
		url: "<?php echo $urlFinal?>",
		dataType: "json",
		data: { valor:$("#<?php echo $this->campoSeguro('cedula')?>").val()},
		success: function(data){
			if(data[0]!=" "){
				$("#<?php echo $this->campoSeguro('nombre')?>").val(data[0].nom_proveedor.toUpperCase());
				$("#<?php echo $this->campoSeguro('correo')?>").val(data[0].correo);
				$("#<?php echo $this->campoSeguro('id_proveedor')?>").val(data[0].id_proveedor);
			}
		}
	});
};
///////////////////////////////////////////////////////////////////////////////////// 


<?php    

$url = $this->miConfigurador->getVariableConfiguracion ( "host" );
$url .= $this->miConfigurador->getVariableConfiguracion ( "site" );
$url .= "/index.php?";

$cadenaACodificarProveedorFiltro = "pagina=" . $this->miConfigurador->getVariableConfiguracion("pagina");
$cadenaACodificarProveedorFiltro .= "&procesarAjax=true";
$cadenaACodificarProveedorFiltro .= "&action=index.php";
$cadenaACodificarProveedorFiltro .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificarProveedorFiltro .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificarProveedorFiltro .= $cadenaACodificarProveedorFiltro . "&funcion=consultarProveedorFiltro";
$cadenaACodificarProveedorFiltro .= "&tiempo=" . $_REQUEST ['tiempo'];
// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");
$cadenaACodificarProveedorFiltro = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificarProveedorFiltro, $enlace);
// URL definitiva
$urlProveedorFiltro = $url . $cadenaACodificarProveedorFiltro;

?>

$("#<?php echo $this->campoSeguro('nit_proveedor') ?>").keyup(function () {
    $('#<?php echo $this->campoSeguro('nit_proveedor') ?>').val($('#<?php echo $this->campoSeguro('nit_proveedor') ?>').val());
});
$("#<?php echo $this->campoSeguro('nit_proveedor') ?>").autocomplete({
    minChars: 3,
    serviceUrl: '<?php echo $urlProveedorFiltro; ?>',
    onSelect: function (suggestions) {
        $("#<?php echo $this->campoSeguro('id_proveedor') ?>").val(suggestions.data);
    }
});










$("#<?php echo $this->campoSeguro('personaNaturalParaSupervisor')?>").change(function() {	

	if($("#<?php echo $this->campoSeguro('personaNaturalParaSupervisor')?>").val() != ''){
	
		var date = $("#<?php echo $this->campoSeguro('personaNaturalParaSupervisor')?>").val();
		$("#<?php echo $this->campoSeguro('cedula')?>").val(date);
		
		consultarPersona();
		 		
	}
	
});
