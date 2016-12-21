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
        
        
        
        
        
        
        
//////////////////Función que se ejecuta al seleccionar alguna opción del contexto de la Entidad////////////////////

$("#<?php echo $this->campoSeguro('reclamaciones')?>").change(function() {

	if($("#<?php echo $this->campoSeguro('reclamaciones')?>").val() == 0){
		  $("#reclamacionSolucion_div").css("display", "block");
                
		 		
        }else{
                    $("#reclamacionSolucion_div").css("display", "none");
	}
});

$("#<?php echo $this->campoSeguro('garantia')?>").change(function() {

	if($("#<?php echo $this->campoSeguro('garantia')?>").val() == 0){
		  $("#garantiaSatisfaccion_div").css("display", "block");
                
		 		
        }else{
                    $("#garantiaSatisfaccion_div").css("display", "none");
	}
});

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        
        
        
        
        
        
        
        
        
        
        
        
        
        
});

</script>