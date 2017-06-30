window.onload = detectarCarga;
function detectarCarga(){

	$('#marcoDatosLoad').fadeOut(1000, function (){
		$('#marcoDatosTable').fadeIn(500);
		$('#marcoDatos').fadeIn(500);
	});
	
}

$("#consultaGeneralProveedor").validationEngine({
    validateNonVisibleFields: false,
	promptPosition : "bottomRight:-150", 
	scroll: false,
	autoHidePrompt: true,
	autoHideDelay: 2000
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

$( ".widget input[type=submit], .widget a, .widget button" ).button();


/*
 * Asociar el widget de validación al formulario
 */

/*
 * Se define el ancho de los campos de listas desplegables
 */


$('#<?php echo $this->campoSeguro('fechaExpeNat')?>').datepicker({
        dateFormat: 'dd/mm/yy',
        changeYear: true,
        monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
		monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
		dayNames: ['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'],
		dayNamesShort: ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
		dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa']
});	


$('#<?php echo $this->campoSeguro('fechaExpeRep')?>').datepicker({
        dateFormat: 'dd/mm/yy',
        changeYear: true,
        monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
		monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
		dayNames: ['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'],
		dayNamesShort: ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
		dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa']
});	


// Asociar el widget de validación al formulario

/////////Se define el ancho de los campos de listas desplegables///////

$('#<?php echo $this->campoSeguro('paisExpeNat')?>').width(250);
$("#<?php echo $this->campoSeguro('paisExpeNat')?>").select2();
$('#<?php echo $this->campoSeguro('departamentoExpeNat')?>').width(250);
$("#<?php echo $this->campoSeguro('departamentoExpeNat')?>").select2();
$('#<?php echo $this->campoSeguro('ciudadExpeNat')?>').width(250);
$("#<?php echo $this->campoSeguro('ciudadExpeNat')?>").select2();


$('#<?php echo $this->campoSeguro('paisExpeRep')?>').width(250);
$("#<?php echo $this->campoSeguro('paisExpeRep')?>").select2();
$('#<?php echo $this->campoSeguro('departamentoExpeRep')?>').width(250);
$("#<?php echo $this->campoSeguro('departamentoExpeRep')?>").select2();
$('#<?php echo $this->campoSeguro('ciudadExpeRep')?>').width(250);
$("#<?php echo $this->campoSeguro('ciudadExpeRep')?>").select2();


$('#<?php echo $this->campoSeguro('afiliacionEPSNat')?>').width(300);
$("#<?php echo $this->campoSeguro('afiliacionEPSNat')?>").select2();
$('#<?php echo $this->campoSeguro('afiliacionPensionNat')?>').width(300);
$("#<?php echo $this->campoSeguro('afiliacionPensionNat')?>").select2();
$('#<?php echo $this->campoSeguro('afiliacionCajaNat')?>').width(300);
$("#<?php echo $this->campoSeguro('afiliacionCajaNat')?>").select2();


$('#<?php echo $this->campoSeguro('divisionCIIU')?>').width(750);
$('#<?php echo $this->campoSeguro('grupoCIIU')?>').width(750);
$('#<?php echo $this->campoSeguro('claseCIIU')?>').width(750);


$('#<?php echo $this->campoSeguro('tipoPersona')?>').width(150);
$('#<?php echo $this->campoSeguro('paisEmpresa')?>').width(150);
$('#<?php echo $this->campoSeguro('tipoIdentifiExtranjera')?>').width(250);
$('#<?php echo $this->campoSeguro('tipoDocumento')?>').width(250);
$('#<?php echo $this->campoSeguro('tipoDocumentoNat')?>').width(250);

$('#<?php echo $this->campoSeguro('productoImportacion')?>').width(150);
$('#<?php echo $this->campoSeguro('regimenContributivo')?>').width(150);
$('#<?php echo $this->campoSeguro('pyme')?>').width(150);
$('#<?php echo $this->campoSeguro('registroMercantil')?>').width(150);

$('#<?php echo $this->campoSeguro('personaJuridicaPais')?>').width(250);
$("#<?php echo $this->campoSeguro('personaJuridicaPais')?>").select2();
$('#<?php echo $this->campoSeguro('personaJuridicaDepartamento')?>').width(250);
$("#<?php echo $this->campoSeguro('personaJuridicaDepartamento')?>").select2();
$('#<?php echo $this->campoSeguro('personaJuridicaCiudad')?>').width(250);
$("#<?php echo $this->campoSeguro('personaJuridicaCiudad')?>").select2();

$('#<?php echo $this->campoSeguro('personaNaturalContaDepartamento')?>').width(250);
$("#<?php echo $this->campoSeguro('personaNaturalContaDepartamento')?>").select2();
$('#<?php echo $this->campoSeguro('personaNaturalContaCiudad')?>').width(250);
$("#<?php echo $this->campoSeguro('personaNaturalContaCiudad')?>").select2();

$('#<?php echo $this->campoSeguro('personaNaturalArea')?>').width(350);
$("#<?php echo $this->campoSeguro('personaNaturalArea')?>").select2();
$('#<?php echo $this->campoSeguro('personaNaturalNBC')?>').width(350);
$("#<?php echo $this->campoSeguro('personaNaturalNBC')?>").select2();

$('#<?php echo $this->campoSeguro('personaArea')?>').width(350);
$("#<?php echo $this->campoSeguro('personaArea')?>").select2();
$('#<?php echo $this->campoSeguro('personaNBC')?>').width(350);
$("#<?php echo $this->campoSeguro('personaNBC')?>").select2();

$('#<?php echo $this->campoSeguro('departamento')?>').width(250);
$("#<?php echo $this->campoSeguro('departamento')?>").select2();
$('#<?php echo $this->campoSeguro('ciudad')?>').width(250);
$("#<?php echo $this->campoSeguro('ciudad')?>").select2();

$('#<?php echo $this->campoSeguro('genero')?>').width(250);
$("#<?php echo $this->campoSeguro('genero')?>").select2();
$('#<?php echo $this->campoSeguro('generoNat')?>').width(250);
$("#<?php echo $this->campoSeguro('generoNat')?>").select2();

$('#<?php echo $this->campoSeguro('perfil')?>').width(250);
$("#<?php echo $this->campoSeguro('perfil')?>").select2();

$('#<?php echo $this->campoSeguro('tipoCuenta')?>').width(250);
$("#<?php echo $this->campoSeguro('tipoCuenta')?>").select2();
$('#<?php echo $this->campoSeguro('entidadBancaria')?>').width(300);
$("#<?php echo $this->campoSeguro('entidadBancaria')?>").select2();
$('#<?php echo $this->campoSeguro('tipoConformacion')?>').width(300);
$("#<?php echo $this->campoSeguro('tipoConformacion')?>").select2();
$('#<?php echo $this->campoSeguro('paisNacimiento')?>').width(250);
$("#<?php echo $this->campoSeguro('paisNacimiento')?>").select2();


$('#<?php echo $this->campoSeguro('perfilNat')?>').width(250);
$("#<?php echo $this->campoSeguro('perfilNat')?>").select2();

$('#<?php echo $this->campoSeguro('tipoCuentaNat')?>').width(250);
$("#<?php echo $this->campoSeguro('tipoCuentaNat')?>").select2();
$('#<?php echo $this->campoSeguro('entidadBancariaNat')?>').width(300);
$("#<?php echo $this->campoSeguro('entidadBancariaNat')?>").select2();
$('#<?php echo $this->campoSeguro('paisNacimientoNat')?>').width(250);
$("#<?php echo $this->campoSeguro('paisNacimientoNat')?>").select2();

$('#<?php echo $this->campoSeguro('sujetoDeRetencion')?>').width(150);
$("#<?php echo $this->campoSeguro('sujetoDeRetencion')?>").select2();
$('#<?php echo $this->campoSeguro('agenteRetenedor')?>').width(150);
$("#<?php echo $this->campoSeguro('agenteRetenedor')?>").select2();
$('#<?php echo $this->campoSeguro('responsableICA')?>').width(150);
$("#<?php echo $this->campoSeguro('responsableICA')?>").select2();
$('#<?php echo $this->campoSeguro('responsableIVA')?>').width(150);
$("#<?php echo $this->campoSeguro('responsableIVA')?>").select2();

 $('#<?php echo $this->campoSeguro('seccionParametrosNat')?>').width(150);
 $("#<?php echo $this->campoSeguro('seccionParametrosNat')?>").select2(); 
 $('#<?php echo $this->campoSeguro('listaNomenclaturasNat')?>').width(240);
 $("#<?php echo $this->campoSeguro('listaNomenclaturasNat')?>").select2();
 
 $('#<?php echo $this->campoSeguro('seccionParametros')?>').width(150);
 $("#<?php echo $this->campoSeguro('seccionParametros')?>").select2(); 
 $('#<?php echo $this->campoSeguro('listaNomenclaturas')?>').width(240);
 $("#<?php echo $this->campoSeguro('listaNomenclaturas')?>").select2();

//////////////////**********Se definen los campos que requieren campos de select2**********////////////////
$('#<?php echo $this->campoSeguro('divisionCIIU')?>').select2();
$('#<?php echo $this->campoSeguro('grupoCIIU')?>').select2();
$('#<?php echo $this->campoSeguro('claseCIIU')?>').select2();

$('#<?php echo $this->campoSeguro('tipoPersona')?>').select2();
$('#<?php echo $this->campoSeguro('paisEmpresa')?>').select2();
$('#<?php echo $this->campoSeguro('tipoIdentifiExtranjera')?>').select2();
$('#<?php echo $this->campoSeguro('tipoDocumento')?>').select2();
$('#<?php echo $this->campoSeguro('tipoDocumentoNat')?>').select2();

$('#<?php echo $this->campoSeguro('productoImportacion')?>').select2();
$('#<?php echo $this->campoSeguro('regimenContributivo')?>').select2();
$('#<?php echo $this->campoSeguro('pyme')?>').select2();
$('#<?php echo $this->campoSeguro('registroMercantil')?>').select2();

$('#<?php echo $this->campoSeguro('grupoEtnico')?>').width(250);
$("#<?php echo $this->campoSeguro('grupoEtnico')?>").select2();
$('#<?php echo $this->campoSeguro('comunidadLGBT')?>').width(150);
$("#<?php echo $this->campoSeguro('comunidadLGBT')?>").select2();
$('#<?php echo $this->campoSeguro('cabezaFamilia')?>').width(150);
$("#<?php echo $this->campoSeguro('cabezaFamilia')?>").select2();
$('#<?php echo $this->campoSeguro('personasCargo')?>').width(150);
$("#<?php echo $this->campoSeguro('personasCargo')?>").select2();

$('#<?php echo $this->campoSeguro('estadoCivil')?>').width(250);
$("#<?php echo $this->campoSeguro('estadoCivil')?>").select2();

$('#<?php echo $this->campoSeguro('discapacidad')?>').width(150);
$("#<?php echo $this->campoSeguro('discapacidad')?>").select2();
$('#<?php echo $this->campoSeguro('tipoDiscapacidad')?>').width(250);
$("#<?php echo $this->campoSeguro('tipoDiscapacidad')?>").select2();

//AGREGADO Tributaria
$('#<?php echo $this->campoSeguro('declaranteRentaNat')?>').width(150);
$("#<?php echo $this->campoSeguro('declaranteRentaNat')?>").select2();
$('#<?php echo $this->campoSeguro('medicinaPrepagadaNat')?>').width(150);
$("#<?php echo $this->campoSeguro('medicinaPrepagadaNat')?>").select2();
$('#<?php echo $this->campoSeguro('cuentaAFCNat')?>').width(150);
$("#<?php echo $this->campoSeguro('cuentaAFCNat')?>").select2();
$('#<?php echo $this->campoSeguro('entidadBancariaAFCNat')?>').width(300);
$("#<?php echo $this->campoSeguro('entidadBancariaAFCNat')?>").select2();
$('#<?php echo $this->campoSeguro('hijosMenoresEdadNat')?>').width(150);
$("#<?php echo $this->campoSeguro('hijosMenoresEdadNat')?>").select2();

$('#<?php echo $this->campoSeguro('hijosMayoresEdadEstudiandoNat')?>').width(150);
$("#<?php echo $this->campoSeguro('hijosMayoresEdadEstudiandoNat')?>").select2();
$('#<?php echo $this->campoSeguro('hijosMayoresEdadMas23Nat')?>').width(150);
$("#<?php echo $this->campoSeguro('hijosMayoresEdadMas23Nat')?>").select2();
$('#<?php echo $this->campoSeguro('conyugeDependienteNat')?>').width(150);
$("#<?php echo $this->campoSeguro('conyugeDependienteNat')?>").select2();
$('#<?php echo $this->campoSeguro('padresHermanosDependienteNat')?>').width(150);
$("#<?php echo $this->campoSeguro('padresHermanosDependienteNat')?>").select2();

//////////////////Efectos Campos de Selección y Campos Dependientes///////////////////////////////////////


if($('#<?php echo $this->campoSeguro('tipoPersona_Update') ?>').val() == 1){
	$("#marcoDatosJuridicaUP").hide("fast");
}else if($('#<?php echo $this->campoSeguro('tipoPersona_Update') ?>').val() == 2){
	$("#marcoDatosNaturalUP").hide("fast");
}else{
	$("#marcoDatosNatural").hide("fast");
	$("#marcoDatosJuridica").hide("fast");
}


if($('#<?php echo $this->campoSeguro('tipoPersona_Update') ?>').val() == 1){
	$("#marcoDatosJuridicaUPV").hide("fast");
}else if($('#<?php echo $this->campoSeguro('tipoPersona_Update') ?>').val() == 2){
	$("#marcoDatosNaturalUPV").hide("fast");
}else{
	$("#marcoDatosNatural").hide("fast");
	$("#marcoDatosJuridica").hide("fast");
}

if($('#<?php echo $this->campoSeguro('medicinaPrepagadaNat') ?>').val() == 1){//AGREGADO Tributario
	$("#obligatorioNumeroUVT").show("fast");
}else{
	$("#obligatorioNumeroUVT").hide("fast");
}

if($('#<?php echo $this->campoSeguro('cuentaAFCNat') ?>').val() == 1){//AGREGADO Tributario
	$("#obligatorioDatosAFC").show("fast");
}else{
	$("#obligatorioDatosAFC").hide("fast");
}


if($('#<?php echo $this->campoSeguro('personasCargo') ?>').val() == 1){
	$("#obligatorioCantidadPersonasACargo").show("fast");
	$("#marcoDetalleDependientes").show("fast");//AGREGADO Tributario
}else{
	$("#obligatorioCantidadPersonasACargo").hide("fast");
	$("#marcoDetalleDependientes").hide("fast");//AGREGADO Tributario
}

if($('#<?php echo $this->campoSeguro('discapacidad') ?>').val() == 1){
	$("#obligatorioTipoDiscapacidad").show("fast");
}else{
	$("#obligatorioTipoDiscapacidad").hide("fast");
}



//$("#editarBotonesConcepto").show("slow");
$("#marcoProcedencia").hide("fast");
$("#obligatorioCedula").hide("fast");
$("#obligatorioPasaporte").hide("fast");


if($('#<?php echo $this->campoSeguro('perfil') ?>').val() == 4 || $('#<?php echo $this->campoSeguro('perfil') ?>').val() == 6 || $('#<?php echo $this->campoSeguro('perfil') ?>').val() == 7){
	$("#obligatorioProfesion").show("fast");
	$("#obligatorioEspecialidad").show("fast");
}else if ($('#<?php echo $this->campoSeguro('perfil') ?>').val() == 3 || $('#<?php echo $this->campoSeguro('perfil') ?>').val() == 2){
	$("#obligatorioProfesion").show("fast");
	$("#obligatorioEspecialidad").hide("fast");
}else{
	$("#obligatorioProfesion").hide("fast");
	$("#obligatorioEspecialidad").hide("fast");
}


if($('#<?php echo $this->campoSeguro('perfilNat') ?>').val() == 4 || $('#<?php echo $this->campoSeguro('perfilNat') ?>').val() == 6 || $('#<?php echo $this->campoSeguro('perfilNat') ?>').val() == 7){
	$("#obligatorioProfesionNat").show("fast");
	$("#obligatorioEspecialidadNat").show("fast");
}else if ($('#<?php echo $this->campoSeguro('perfilNat') ?>').val() == 3 || $('#<?php echo $this->campoSeguro('perfilNat') ?>').val() == 2){
	$("#obligatorioProfesionNat").show("fast");
	$("#obligatorioEspecialidadNat").hide("fast");
}else{
	$("#obligatorioProfesionNat").hide("fast");
	$("#obligatorioEspecialidadNat").hide("fast");
}


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


$('#tablaContratos').DataTable({
        
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