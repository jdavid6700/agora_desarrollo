$("#gestionObjetoRegistrar").validationEngine({
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
$('#<?php echo $this->campoSeguro('divisionCIIU')?>').width(750);
$('#<?php echo $this->campoSeguro('grupoCIIU')?>').width(750);
$('#<?php echo $this->campoSeguro('claseCIIU')?>').width(750);
$('#<?php echo $this->campoSeguro('ordenador')?>').width(350);
$('#<?php echo $this->campoSeguro('dependencia')?>').width(400);
$('#<?php echo $this->campoSeguro('unidad')?>').width(250);



//////////////////**********Se definen los campos que requieren campos de select2**********////////////////
$('#<?php echo $this->campoSeguro('divisionCIIU')?>').select2();
$('#<?php echo $this->campoSeguro('grupoCIIU')?>').select2();
$('#<?php echo $this->campoSeguro('claseCIIU')?>').select2();
$("#<?php echo $this->campoSeguro('ordenador')?>").select2();
$("#<?php echo $this->campoSeguro('dependencia')?>").select2();




//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$('#tablaObjetos').DataTable({
        
    "language": {
        "sProcessing":     "Procesando...",
        "sLengthMenu":     "Mostrar _MENU_ registros",
	"sZeroRecords":    "No se encontraron resultados",
        "sSearch":         "Buscar:",
        "sLoadingRecords": "Cargando...",
        "sEmptyTable":     "Ningún dato disponible en esta tabla",
	"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
	"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
	"oPaginate": {
		"sFirst":    "Primero",
		"sLast":     "Ãšltimo",
		"sNext":     "Siguiente",
		"sPrevious": "Anterior"
		}
    }
});
