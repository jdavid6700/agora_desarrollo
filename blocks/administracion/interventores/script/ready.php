$("#interventoresRegistrar").validationEngine({
	promptPosition : "bottomRight:-150", 
	scroll: false,
	autoHidePrompt: true,
	autoHideDelay: 2000
});

$("#interventoresRegistrar").submit(function() {
	$resultado=$("#parametricasRegistrar").validationEngine("validate");
	if ($resultado) {
		return true;
	}
	return false;
});

$("button").button().click(function (event) { 
    event.preventDefault();
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
$('#<?php echo $this->campoSeguro('estado')?>').width(350);


//////////////////**********Se definen los campos que requieren campos de select2**********////////////////
$("#<?php echo $this->campoSeguro('dependencia')?>").select2();
$('#<?php echo $this->campoSeguro('personaNaturalParaSupervisor')?>').select2();
$('#<?php echo $this->campoSeguro('estado')?>').select2();


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////


$('#tablaInterventores').DataTable({
        
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