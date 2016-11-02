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
			}
		}
	});
};
///////////////////////////////////////////////////////////////////////////////////// 
    	 


$("#<?php echo $this->campoSeguro('personaNaturalParaSupervisor')?>").change(function() {	

	if($("#<?php echo $this->campoSeguro('personaNaturalParaSupervisor')?>").val() != ''){
	
		var date = $("#<?php echo $this->campoSeguro('personaNaturalParaSupervisor')?>").val();
		$("#<?php echo $this->campoSeguro('cedula')?>").val(date);
		
		consultarPersona();
		 		
	}
	
});
