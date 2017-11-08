<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


// URL base
$url = $this->miConfigurador->getVariableConfiguracion("host");
$url .= $this->miConfigurador->getVariableConfiguracion("site");
$url .= "/index.php?";


// Variables
$cadenaACodificarProveedor = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( "pagina" );
$cadenaACodificarProveedor .= "&procesarAjax=true";
$cadenaACodificarProveedor .= "&action=index.php";
$cadenaACodificarProveedor .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificarProveedor .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificarProveedor .= "&funcion=consultaProveedor";
$cadenaACodificarProveedor .= "&tiempo=" . $_REQUEST ['tiempo'];



// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion ( "enlace" );
$cadena = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $cadenaACodificarProveedor, $enlace );

// URL definitiva
$urlFinalProveedor = $url . $cadena;




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

<script type='text/javascript'>

$(function () {
	    $( "#<?php echo $this->campoSeguro('nombre_contratista')?>" ).keyup(function() {
    	$('#<?php echo $this->campoSeguro('nombre_contratista') ?>').val($('#<?php echo $this->campoSeguro('nombre_contratista') ?>').val().toUpperCase());
            });

        $("#<?php echo $this->campoSeguro('nombre_contratista') ?>").autocomplete({
        	minChars: 3,
        	serviceUrl: '<?php echo $urlFinalProveedor; ?>',
        	onSelect: function (suggestion) {
        	        $("#<?php echo $this->campoSeguro('id_contratista') ?>").val(suggestion.data);
        	    }
        });
});



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

</script>