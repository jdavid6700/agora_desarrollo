window.onload = detectarCarga;

function detectarCarga() {
	$('#marcoDatosLoad').fadeOut('slow');
    $('#marcoDatosBasicosDt').fadeIn('slow');

    tinyMCE.get('<?php echo $this->campoSeguro('query')?>').theme.resizeTo("100%", 650);

    tinyMCE.get('<?php echo $this->campoSeguro('data')?>').theme.resizeTo("100%", 650);
    
}

$( ".widget input[type=submit], .widget a, .widget button" ).button();

$("#accordionR").bwlAccordion({
		search: false,
        theme: 'theme-blue',
        toggle: true,
        animation: 'faderight'
    });


$("#accordionRLt").bwlAccordion({
		search: false,
        theme: 'theme-blue',
        toggle: true,
        animation: 'faderight'
    });

<?php ?>

// Asociar el widget de validación al formulario
$("#consultaContratosAprobados").validationEngine({
promptPosition : "centerRight", 
scroll: false,
autoHidePrompt: true,
autoHideDelay: 2000
});


$(function() {
$("#consultaContratosAprobados").submit(function() {
$resultado=$("#consultaContratosAprobados").validationEngine("validate");
if ($resultado) {

return true;
}
return false;
});
});

$('#tablaTitulos').dataTable( {
"sPaginationType": "full_numbers"
} );

$('#tablaParticipantesSociedad').DataTable({
dom: 'T<"clear">lfrtip',
tableTools: {
"sRowSelect": "os",
"aButtons": ["select_all", "select_none"]
},
"language": {
"sProcessing": "Procesando...",
"sLengthMenu": "Mostrar _MENU_ registros",
"sZeroRecords": "No se encontraron resultados",
"sSearch": "Buscar:",
"sLoadingRecords": "Cargando...",
"sEmptyTable": "Ningún dato disponible en esta tabla",
"sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
"sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
"sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
"oPaginate": {
"sFirst": "Primero",
"sLast": "Ãšltimo",
"sNext": "Siguiente",
"sPrevious": "Anterior"
}
},
"columnDefs": [
{
"targets": [0, 1],
"visible": false,
"searchable": false
}
],
processing: true,
searching: true,
info: true,
"scrollY": "400px",
"scrollCollapse": false,
"bLengthChange": false,
"bPaginate": false,
"aoColumns": [
{sWidth: "10%", sClass: "center"},
{sWidth: "10%", sClass: "center"},
{sWidth: "10%", sClass: "center"},
{sWidth: "10%", sClass: "center"},
{sWidth: "10%", sClass: "center"},
{sWidth: "10%", sClass: "center"},
]


});

$('#tablaRegistros').DataTable();

$("#<?php echo $this->campoSeguro('clase_contrato') ?>").width(220);
$("#<?php echo $this->campoSeguro('clase_contrato') ?>").select2();

$("#<?php echo $this->campoSeguro('vigencia') ?>").width(220);
$("#<?php echo $this->campoSeguro('vigencia') ?>").select2();






