$("#parametricasRegistrar").validationEngine({
	promptPosition : "bottomRight:-150", 
	scroll: false,
	autoHidePrompt: true,
	autoHideDelay: 2000
});

$("#parametricasRegistrar").submit(function() {
	$resultado=$("#parametricasRegistrar").validationEngine("validate");
	if ($resultado) {
		return true;
	}
	return false;
});

$("button").button().click(function (event) { 
    event.preventDefault();
});

$("#crearDocenteModificar").submit(function() {
	$resultado=$("#crearDocenteModificar").validationEngine("validate");
	if ($resultado) {
		return true;
	}
	return false;
});

$("#crearDocenteModificar").validationEngine({
	promptPosition : "bottomRight:-150", 
	scroll: false,
	autoHidePrompt: true,
	autoHideDelay: 2000
});

$('#tablaTitulos').dataTable( {
	"sPaginationType": "full_numbers"
});
        
/*
 * Función que organiza los tabs en la interfaz gráfica
 */
$(function() {
	$("#tabs").tabs();
}); 


/*
 * Se define el ancho de los campos de listas desplegables
 */
/////////Se define el ancho de los campos de listas desplegables///////
$('#<?php echo $this->campoSeguro('dependencia')?>').width(450);
$('#<?php echo $this->campoSeguro('personaNaturalParaSupervisor')?>').width(350);


//////////////////**********Se definen los campos que requieren campos de select2**********////////////////
$("#<?php echo $this->campoSeguro('dependencia')?>").select2();
$('#<?php echo $this->campoSeguro('personaNaturalParaSupervisor')?>').select2();


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
