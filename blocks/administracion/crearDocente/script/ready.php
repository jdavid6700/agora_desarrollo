$("#crearDocente").validationEngine({
	promptPosition : "bottomRight:-150", 
	scroll: false,
	autoHidePrompt: true,
	autoHideDelay: 2000
});

$("#crearDocente").submit(function() {
	$resultado=$("#crearDocente").validationEngine("validate");
	if ($resultado) {
		return true;
	}
	return false;
});

$("#crearDocenteRegistrar").validationEngine({
	promptPosition : "bottomRight:-150", 
	scroll: false,
	autoHidePrompt: true,
	autoHideDelay: 2000
});

$("#crearDocenteRegistrar").submit(function() {
	$resultado=$("#crearDocenteRegistrar").validationEngine("validate");		
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
 * Asociar el widget de validación al formulario
 */

/*
 * Se define el ancho de los campos de listas desplegables
 */


// Asociar el widget de validación al formulario

/////////Se define el ancho de los campos de listas desplegables///////
$('#<?php echo $this->campoSeguro('divisionCIIU')?>').width(310);
$('#<?php echo $this->campoSeguro('grupoCIIU')?>').width(310);
$('#<?php echo $this->campoSeguro('claseCIIU')?>').width(310);
$('#<?php echo $this->campoSeguro('ordenador')?>').width(250);
$('#<?php echo $this->campoSeguro('dependencia')?>').width(250);
$('#<?php echo $this->campoSeguro('unidad')?>').width(250);

      
$('#<?php echo $this->campoSeguro('facultad')?>').width(450);      
$('#<?php echo $this->campoSeguro('proyectoCurricular')?>').width(450);      
$('#<?php echo $this->campoSeguro('dedicacion')?>').width(450);      
$('#<?php echo $this->campoSeguro('docenteRegistrar')?>').width(465);
$('#<?php echo $this->campoSeguro('tipoDocumento')?>').width(450);
$('#<?php echo $this->campoSeguro('categoriaActualDocente')?>').width(450);


//////////////////**********Se definen los campos que requieren campos de select2**********////////////////
$('#<?php echo $this->campoSeguro('divisionCIIU')?>').select2();
$('#<?php echo $this->campoSeguro('grupoCIIU')?>').select2();
$('#<?php echo $this->campoSeguro('claseCIIU')?>').select2();
$("#<?php echo $this->campoSeguro('ordenador')?>").select2();
$("#<?php echo $this->campoSeguro('dependencia')?>").select2();


$("#<?php echo $this->campoSeguro('proyectoCurricular')?>").select2();
$("#<?php echo $this->campoSeguro('tipoDocumento')?>").select2();
$('#<?php echo $this->campoSeguro('categoriaActualDocente')?>').select2();
$('#<?php echo $this->campoSeguro('dedicacion')?>').select2();


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
