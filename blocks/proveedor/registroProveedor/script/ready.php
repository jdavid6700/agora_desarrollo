$("#registroProveedor").validationEngine({
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

$("#registroProveedorRegistrar").validationEngine({
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
$('#<?php echo $this->campoSeguro('divisionCIIU')?>').width(750);
$('#<?php echo $this->campoSeguro('grupoCIIU')?>').width(750);
$('#<?php echo $this->campoSeguro('claseCIIU')?>').width(750);


$('#<?php echo $this->campoSeguro('tipoPersona')?>').width(150);
$('#<?php echo $this->campoSeguro('paisEmpresa')?>').width(150);
$('#<?php echo $this->campoSeguro('tipoIdentifiExtranjera')?>').width(250);
$('#<?php echo $this->campoSeguro('tipoDocumento')?>').width(250);

$('#<?php echo $this->campoSeguro('productoImportacion')?>').width(150);
$('#<?php echo $this->campoSeguro('regimenContributivo')?>').width(150);
$('#<?php echo $this->campoSeguro('pyme')?>').width(150);
$('#<?php echo $this->campoSeguro('registroMercantil')?>').width(150);



//////////////////**********Se definen los campos que requieren campos de select2**********////////////////
$('#<?php echo $this->campoSeguro('divisionCIIU')?>').select2();
$('#<?php echo $this->campoSeguro('grupoCIIU')?>').select2();
$('#<?php echo $this->campoSeguro('claseCIIU')?>').select2();

$('#<?php echo $this->campoSeguro('tipoPersona')?>').select2();
$('#<?php echo $this->campoSeguro('paisEmpresa')?>').select2();
$('#<?php echo $this->campoSeguro('tipoIdentifiExtranjera')?>').select2();
$('#<?php echo $this->campoSeguro('tipoDocumento')?>').select2();

$('#<?php echo $this->campoSeguro('productoImportacion')?>').select2();
$('#<?php echo $this->campoSeguro('regimenContributivo')?>').select2();
$('#<?php echo $this->campoSeguro('pyme')?>').select2();
$('#<?php echo $this->campoSeguro('registroMercantil')?>').select2();



//////////////////Efectos Campos de Selección y Campos Dependientes///////////////////////////////////////

//$("#editarBotonesConcepto").show("slow");
$("#marcoProcedencia").hide("fast");
$("#obligatorioCedula").hide("fast");
$("#obligatorioPasaporte").hide("fast");


if($('#<?php echo $this->campoSeguro('paisEmpresa') ?>').val() == 2){
		$("#marcoProcedencia").show("slow");
}else {
		$("#marcoProcedencia").hide("slow");
}


if($('#<?php echo $this->campoSeguro('tipoIdentifiExtranjera') ?>').val() == 1){
		$("#obligatorioCedula").show("fast");
		$("#obligatorioPasaporte").hide("fast");
}else if ($('#<?php echo $this->campoSeguro('tipoIdentifiExtranjera') ?>').val() == 2){
		$("#obligatorioCedula").hide("fast");
		$("#obligatorioPasaporte").show("fast");
}else{
		$("#obligatorioCedula").hide("fast");
		$("#obligatorioPasaporte").hide("fast");
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
