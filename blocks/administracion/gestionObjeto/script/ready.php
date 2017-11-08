$("#gestionObjetoRegistrar").validationEngine({
	promptPosition : "bottomRight:-150", 
	scroll: false,
	autoHidePrompt: true,
	autoHideDelay: 2000
});

$("#gestionObjetoConsultarRel").validationEngine({
	promptPosition : "bottomRight:-150", 
	scroll: false,
	autoHidePrompt: true,
	autoHideDelay: 2000
});

$("#gestionObjetoConsultarCot").validationEngine({
	promptPosition : "bottomRight:-150", 
	scroll: false,
	autoHidePrompt: true,
	autoHideDelay: 2000
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
$('#<?php echo $this->campoSeguro('unidad')?>').width(250);

$('#<?php echo $this->campoSeguro('objetoArea')?>').width(750);
$('#<?php echo $this->campoSeguro('objetoNBC')?>').width(750);

$('#<?php echo $this->campoSeguro('unidadEjecutoraCheck')?>').width(250);
$('#<?php echo $this->campoSeguro('unidadEjecutoraCheckRelacionada')?>').width(250);
$('#<?php echo $this->campoSeguro('unidadEjecutoraCheckCotizacion')?>').width(250);

$('#<?php echo $this->campoSeguro('vigenciaNecesidad')?>').width(250);
$('#<?php echo $this->campoSeguro('vigenciaNecesidadRelacionada')?>').width(250);
$('#<?php echo $this->campoSeguro('vigenciaNecesidadCotizacion')?>').width(250);

$('#<?php echo $this->campoSeguro('tipoNecesidad')?>').width(250);




//////////////////**********Se definen los campos que requieren campos de select2**********////////////////
$('#<?php echo $this->campoSeguro('divisionCIIU')?>').select2();
$('#<?php echo $this->campoSeguro('grupoCIIU')?>').select2();
$('#<?php echo $this->campoSeguro('claseCIIU')?>').select2();
$('#<?php echo $this->campoSeguro('unidad')?>').select2();

$('#<?php echo $this->campoSeguro('objetoArea')?>').select2();
$('#<?php echo $this->campoSeguro('objetoNBC')?>').select2();

$('#<?php echo $this->campoSeguro('unidadEjecutoraCheck')?>').select2();
$('#<?php echo $this->campoSeguro('unidadEjecutoraCheckRelacionada')?>').select2();
$('#<?php echo $this->campoSeguro('unidadEjecutoraCheckCotizacion')?>').select2();

$('#<?php echo $this->campoSeguro('vigenciaNecesidad')?>').select2();
$('#<?php echo $this->campoSeguro('vigenciaNecesidadRelacionada')?>').select2();
$('#<?php echo $this->campoSeguro('vigenciaNecesidadCotizacion')?>').select2();

$('#<?php echo $this->campoSeguro('tipoNecesidad')?>').select2();



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$('#tablaObjetos').dataTable({
        
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

$('#tablaObjetosSinCotizacion').dataTable({
        
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


$('#tablaObjetosEnCotizacion').dataTable({
        
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
