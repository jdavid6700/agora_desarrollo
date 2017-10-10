<?php
/**
 *
 * Los datos del bloque se encuentran en el arreglo $esteBloque.
 */
// URL base
$url = $this->miConfigurador->getVariableConfiguracion("host");
$url .= $this->miConfigurador->getVariableConfiguracion("site");
$url .= "/index.php?";
// Variables
$cadenaACodificarDocente = "pagina=" . $this->miConfigurador->getVariableConfiguracion("pagina");
$cadenaACodificarDocente .= "&procesarAjax=true";
$cadenaACodificarDocente .= "&action=index.php";
$cadenaACodificarDocente .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificarDocente .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificarDocente .= "&funcion=consultarDocente";
$cadenaACodificarDocente .= "&tiempo=" . $_REQUEST ['tiempo'];

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");
$cadena = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificarDocente, $enlace);

// URL definitiva
$urlFinalDocente = $url . $cadena;
?>

$( "#<?php echo $this->campoSeguro('docente') ?>" ).keyup(function() {
$('#<?php echo $this->campoSeguro('docente') ?>').val($('#<?php echo $this->campoSeguro('docente') ?>').val().toUpperCase());
});

$( "#<?php echo $this->campoSeguro('docente') ?>" ).change(function() {
if($('#<?php echo $this->campoSeguro('docente') ?>').val()==''){
$("#<?php echo $this->campoSeguro('id_docente') ?>").val('');
}
});

$("#<?php echo $this->campoSeguro('docente') ?>").autocomplete({
minChars: 3,
serviceUrl: '<?php echo $urlFinalDocente; ?>',
onSelect: function (suggestion) {
$("#<?php echo $this->campoSeguro('id_docente') ?>").val(suggestion.data);
}
});

////////////////////////Seleccion Procedencia Formulario Adicional//////////////////////////////////////////
$( "#<?php echo $this->campoSeguro('paisEmpresa') ?>" ).change(function() {
if($('#<?php echo $this->campoSeguro('paisEmpresa') ?>').val() == 2){
$("#marcoProcedencia").show("slow");
}else {
$("#marcoProcedencia").hide("slow");
}
});

$( "#<?php echo $this->campoSeguro('tipoPersona') ?>" ).change(function() {
if($('#<?php echo $this->campoSeguro('tipoPersona') ?>').val() == 1){
$("#marcoDatosNatural").show("slow");
$("#marcoDatosJuridica").hide("fast");
}else if($('#<?php echo $this->campoSeguro('tipoPersona') ?>').val() == 2){
$("#marcoDatosNatural").hide("fast");
$("#marcoDatosJuridica").show("slow");
}else{
$("#marcoDatosNatural").hide("fast");
$("#marcoDatosJuridica").hide("fast");
}
});

$( "#<?php echo $this->campoSeguro('tipoIdentifiExtranjera') ?>" ).change(function() {
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
});

$( "#<?php echo $this->campoSeguro('perfil') ?>" ).change(function() {
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
});

$( "#<?php echo $this->campoSeguro('perfilNat') ?>" ).change(function() {
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
});


$( "#<?php echo $this->campoSeguro('personasCargo') ?>" ).change(function() {
if($('#<?php echo $this->campoSeguro('personasCargo') ?>').val() == 1){
$("#obligatorioCantidadPersonasACargo").show("fast");
$("#marcoDetalleDependientes").show("fast");//AGREGADO Tributario
}else{
$("#obligatorioCantidadPersonasACargo").hide("fast");
$("#marcoDetalleDependientes").hide("fast");//AGREGADO Tributario
}
});

$( "#<?php echo $this->campoSeguro('discapacidad') ?>" ).change(function() {
if($('#<?php echo $this->campoSeguro('discapacidad') ?>').val() == 1){
$("#obligatorioTipoDiscapacidad").show("fast");
}else{
$("#obligatorioTipoDiscapacidad").hide("fast");
}
});

$( "#<?php echo $this->campoSeguro('medicinaPrepagadaNat') ?>" ).change(function() {//AGREGADO Tributario
if($('#<?php echo $this->campoSeguro('medicinaPrepagadaNat') ?>').val() == 1){
$("#obligatorioNumeroUVT").show("fast");
}else{
$("#obligatorioNumeroUVT").hide("fast");
}
});

$( "#<?php echo $this->campoSeguro('cuentaAFCNat') ?>" ).change(function() {//AGREGADO Tributario
if($('#<?php echo $this->campoSeguro('cuentaAFCNat') ?>').val() == 1){
$("#obligatorioDatosAFC").show("fast");
}else{
$("#obligatorioDatosAFC").hide("fast");
}
});



$("#<?php echo $this->campoSeguro('tipoCuenta') ?>").change(function(){
if($("#<?php echo $this->campoSeguro('tipoCuenta') ?>").val() == 4){

$("#infoBancos").hide("fast");

$("#<?php echo $this->campoSeguro('entidadBancaria') ?>").attr('disabled','');

$("#<?php echo $this->campoSeguro('entidadBancaria') ?>").select2();


$("#<?php echo $this->campoSeguro('numeroCuenta') ?>").attr('disabled','');


}else{

$("#infoBancos").show("fast");

$("#<?php echo $this->campoSeguro('entidadBancaria') ?>").removeAttr('disabled');

$("#<?php echo $this->campoSeguro('entidadBancaria') ?>").select2();


$("#<?php echo $this->campoSeguro('numeroCuenta') ?>").removeAttr('disabled');

}
});	


///////////////////////////////////////////////////////////////////////////////////////////////////////////


//////////////////Función que se ejecuta al seleccionar alguna opción del contexto de la Entidad////////////////////

$("#<?php echo $this->campoSeguro('divisionCIIU') ?>").change(function() {

if($("#<?php echo $this->campoSeguro('divisionCIIU') ?>").val() == ''){

$("<option value=''>Seleccione .....</option>").appendTo("#<?php echo $this->campoSeguro('grupoCIIU') ?>");

$("#<?php echo $this->campoSeguro('grupoCIIU_div') ?>").css('display','none');

}else{

$("#<?php echo $this->campoSeguro('grupoCIIU') ?>").html("");
$("<option value=''>Seleccione .....</option>").appendTo("#<?php echo $this->campoSeguro('grupoCIIU') ?>");
consultarCiudad();

$("#<?php echo $this->campoSeguro('grupoCIIU_div') ?>").css('display','block'); 

$("#<?php echo $this->campoSeguro('grupoCIIU') ?>").select2();

}

});

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////

///////////////Función que se encarga de hacer dinámico el campo país////////////////  

$("#<?php echo $this->campoSeguro('grupoCIIU') ?>").change(function() {

if($("#<?php echo $this->campoSeguro('grupoCIIU') ?>").val() == ''){

$("<option value=''>Seleccione .....</option>").appendTo("#<?php echo $this->campoSeguro('claseCIIU') ?>");

$("#<?php echo $this->campoSeguro('claseCIIU_div') ?>").css('display','none');

}else{

$("#<?php echo $this->campoSeguro('claseCIIU') ?>").html("");
$("<option value=''>Seleccione .....</option>").appendTo("#<?php echo $this->campoSeguro('claseCIIU') ?>");
consultarClase();

$("#<?php echo $this->campoSeguro('claseCIIU_div') ?>").css('display','block'); 

$("#<?php echo $this->campoSeguro('claseCIIU') ?>").select2();

}

});

<?php
$url = $this->miConfigurador->getVariableConfiguracion("host");
$url .= $this->miConfigurador->getVariableConfiguracion("site");
$url .= "/index.php?";

// Variables
$cadenaACodificarPais = "pagina=" . $this->miConfigurador->getVariableConfiguracion("pagina");
$cadenaACodificarPais .= "&procesarAjax=true";
$cadenaACodificarPais .= "&action=index.php";
$cadenaACodificarPais .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificarPais .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificarPais .= "&funcion=consultarPais";
$cadenaACodificarPais .= "&tiempo=" . $_REQUEST ['tiempo'];

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");
$cadena = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificarPais, $enlace);

// URL definitiva
$urlFinalPais = $url . $cadena;
?>

function consultarPais(elem, request, response){
$.ajax({
url: "<?php echo $urlFinalPais ?>",
dataType: "json",
data: { valor:$("#<?php echo $this->campoSeguro('contexto') ?>").val()},
success: function(data){
if(data[0]!=" "){
$("#<?php echo $this->campoSeguro('pais') ?>").html('');
$("<option value=''>Seleccione .....</option>").appendTo("#<?php echo $this->campoSeguro('pais') ?>");
$.each(data , function(indice,valor){
$("<option value='"+data[ indice ].paiscodigo+"'>"+data[ indice ].paisnombre+"</option>").appendTo("#<?php echo $this->campoSeguro('pais') ?>");
});
}
}
});
};


//////////////*******Función que permite enviar los caracteres a medida que se van ingresando e ir recibiendo una respuesta para ir mostrando posibles docentes*******/////////////// 
//////////////////////ver en procecarajax.php la función consultarDocente y en sql.class.php ver la sentencia docente.////////////////////////////////////////////////////////////////
//////////////////////Para que esta función se ejecute correctamente debe agregar//
<?php
// Variables
$cadenaACodificarClase = "pagina=" . $this->miConfigurador->getVariableConfiguracion("pagina");
$cadenaACodificarClase .= "&procesarAjax=true";
$cadenaACodificarClase .= "&action=index.php";
$cadenaACodificarClase .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificarClase .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificarClase .= "&funcion=consultarClase";
$cadenaACodificarClase .= "&tiempo=" . $_REQUEST ['tiempo'];
// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");
$cadena = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificarClase, $enlace);

// URL definitiva
$urlFinalClase = $url . $cadena;
?>

function consultarClase(elem, request, response){
$.ajax({
url: "<?php echo $urlFinalClase ?>",
dataType: "json",
data: { valor:$("#<?php echo $this->campoSeguro('grupoCIIU') ?>").val()},
success: function(data){
if(data[0]!=" "){
$("#<?php echo $this->campoSeguro('claseCIIU') ?>").html("");
$("<option value=''>Seleccione .....</option>").appendTo("#<?php echo $this->campoSeguro('claseCIIU') ?>");
$.each(data , function(indice,valor){
$("<option value='"+data[ indice ].id_subclase+"'>"+data[ indice ].nombre+"</option>").appendTo("#<?php echo $this->campoSeguro('claseCIIU') ?>");
});
}
}
});
};

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


///////////////Función que se encarga de hacer dinámico el campo categoría////////////////  
<?php
$url = $this->miConfigurador->getVariableConfiguracion("host");
$url .= $this->miConfigurador->getVariableConfiguracion("site");
$url .= "/index.php?";

// Variables
$cadenaACodificarCiudad = "pagina=" . $this->miConfigurador->getVariableConfiguracion("pagina");
$cadenaACodificarCiudad .= "&procesarAjax=true";
$cadenaACodificarCiudad .= "&action=index.php";
$cadenaACodificarCiudad .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificarCiudad .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificarCiudad .= "&funcion=consultarCiudad";
$cadenaACodificarCiudad .= "&tiempo=" . $_REQUEST ['tiempo'];
// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");
$cadena = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificarCiudad, $enlace);
// URL definitiva
$urlFinalCiudad = $url . $cadena;



// URL base
$url = $this->miConfigurador->getVariableConfiguracion("host");
$url .= $this->miConfigurador->getVariableConfiguracion("site");
$url .= "/index.php?";

//Variables
$cadenaACodificar16 = "pagina=" . $this->miConfigurador->getVariableConfiguracion("pagina");
$cadenaACodificar16 .= "&procesarAjax=true";
$cadenaACodificar16 .= "&action=index.php";
$cadenaACodificar16 .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificar16 .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificar16 .= $cadenaACodificar16 . "&funcion=consultarDepartamentoAjax";
$cadenaACodificar16 .= "&tiempo=" . $_REQUEST ['tiempo'];

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");

$cadena16 = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar16, $enlace);

// URL definitiva
$urlFinal16 = $url . $cadena16;
//echo $urlFinal16; exit;
// URL base
$url = $this->miConfigurador->getVariableConfiguracion("host");
$url .= $this->miConfigurador->getVariableConfiguracion("site");
$url .= "/index.php?";

//Variables
$cadenaACodificar17 = "pagina=" . $this->miConfigurador->getVariableConfiguracion("pagina");
$cadenaACodificar17 .= "&procesarAjax=true";
$cadenaACodificar17 .= "&action=index.php";
$cadenaACodificar17 .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificar17 .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificar17 .= $cadenaACodificar17 . "&funcion=consultarCiudadAjax";
$cadenaACodificar17 .= "&tiempo=" . $_REQUEST ['tiempo'];

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");

$cadena17 = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar17, $enlace);

// URL definitiva
$urlFinal17 = $url . $cadena17;
//echo $urlFinal16; exit;
// URL base
$url = $this->miConfigurador->getVariableConfiguracion("host");
$url .= $this->miConfigurador->getVariableConfiguracion("site");
$url .= "/index.php?";

//Variables
$cadenaACodificar18 = "pagina=" . $this->miConfigurador->getVariableConfiguracion("pagina");
$cadenaACodificar18 .= "&procesarAjax=true";
$cadenaACodificar18 .= "&action=index.php";
$cadenaACodificar18 .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificar18 .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificar18 .= $cadenaACodificar18 . "&funcion=consultarCiudadAjax";
$cadenaACodificar18 .= "&tiempo=" . $_REQUEST ['tiempo'];

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");

$cadena18 = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar18, $enlace);

// URL definitiva
$urlFinal18 = $url . $cadena18;
//echo $urlFinal16; exit;
// URL base
$url = $this->miConfigurador->getVariableConfiguracion("host");
$url .= $this->miConfigurador->getVariableConfiguracion("site");
$url .= "/index.php?";

//Variables
$cadenaACodificar19 = "pagina=" . $this->miConfigurador->getVariableConfiguracion("pagina");
$cadenaACodificar19 .= "&procesarAjax=true";
$cadenaACodificar19 .= "&action=index.php";
$cadenaACodificar19 .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificar19 .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificar19 .= $cadenaACodificar19 . "&funcion=consultarPaisAjax";
$cadenaACodificar19 .= "&tiempo=" . $_REQUEST ['tiempo'];

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");

$cadena19 = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar19, $enlace);

// URL definitiva
$urlFinal19 = $url . $cadena19;
//echo $urlFinal16; exit;
// URL base
$url = $this->miConfigurador->getVariableConfiguracion("host");
$url .= $this->miConfigurador->getVariableConfiguracion("site");
$url .= "/index.php?";

//Variables
$cadenaACodificar20 = "pagina=" . $this->miConfigurador->getVariableConfiguracion("pagina");
$cadenaACodificar20 .= "&procesarAjax=true";
$cadenaACodificar20 .= "&action=index.php";
$cadenaACodificar20 .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificar20 .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificar20 .= $cadenaACodificar20 . "&funcion=consultarCiudadAjax";
$cadenaACodificar20 .= "&tiempo=" . $_REQUEST ['tiempo'];

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");

$cadena20 = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar20, $enlace);

// URL definitiva
$urlFinal20 = $url . $cadena20;
//echo $urlFinal16; exit;
// URL base
$url = $this->miConfigurador->getVariableConfiguracion("host");
$url .= $this->miConfigurador->getVariableConfiguracion("site");
$url .= "/index.php?";

//Variables
$cadenaACodificar21 = "pagina=" . $this->miConfigurador->getVariableConfiguracion("pagina");
$cadenaACodificar21 .= "&procesarAjax=true";
$cadenaACodificar21 .= "&action=index.php";
$cadenaACodificar21 .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificar21 .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificar21 .= $cadenaACodificar21 . "&funcion=consultarNomenclatura";
$cadenaACodificar21 .= "&tiempo=" . $_REQUEST ['tiempo'];

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");

$cadena21 = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar21, $enlace);

// URL definitiva
$urlFinal21 = $url . $cadena21;
//echo $urlFinal16; exit;
// URL base
$url = $this->miConfigurador->getVariableConfiguracion("host");
$url .= $this->miConfigurador->getVariableConfiguracion("site");
$url .= "/index.php?";

//Variables
$cadenaACodificar22 = "pagina=" . $this->miConfigurador->getVariableConfiguracion("pagina");
$cadenaACodificar22 .= "&procesarAjax=true";
$cadenaACodificar22 .= "&action=index.php";
$cadenaACodificar22 .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificar22 .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificar22 .= $cadenaACodificar22 . "&funcion=consultarNomenclatura";
$cadenaACodificar22 .= "&tiempo=" . $_REQUEST ['tiempo'];

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");

$cadena22 = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar22, $enlace);

// URL definitiva
$urlFinal22 = $url . $cadena22;
//echo $urlFinal16; exit;
// URL base
$url = $this->miConfigurador->getVariableConfiguracion("host");
$url .= $this->miConfigurador->getVariableConfiguracion("site");
$url .= "/index.php?";

//Variables
$cadenaACodificar23 = "pagina=" . $this->miConfigurador->getVariableConfiguracion("pagina");
$cadenaACodificar23 .= "&procesarAjax=true";
$cadenaACodificar23 .= "&action=index.php";
$cadenaACodificar23 .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificar23 .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificar23 .= $cadenaACodificar23 . "&funcion=consultarNBC";
$cadenaACodificar23 .= "&tiempo=" . $_REQUEST ['tiempo'];

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");

$cadena23 = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar23, $enlace);

// URL definitiva
$urlFinal23 = $url . $cadena23;
//echo $urlFinal23;
// URL base
$url = $this->miConfigurador->getVariableConfiguracion("host");
$url .= $this->miConfigurador->getVariableConfiguracion("site");
$url .= "/index.php?";

//Variables
$cadenaACodificar24 = "pagina=" . $this->miConfigurador->getVariableConfiguracion("pagina");
$cadenaACodificar24 .= "&procesarAjax=true";
$cadenaACodificar24 .= "&action=index.php";
$cadenaACodificar24 .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificar24 .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificar24 .= $cadenaACodificar24 . "&funcion=consultarNBC";
$cadenaACodificar24 .= "&tiempo=" . $_REQUEST ['tiempo'];

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");

$cadena24 = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar24, $enlace);

// URL definitiva
$urlFinal24 = $url . $cadena24;
//echo $urlFinal23; exit;
// URL base
$url = $this->miConfigurador->getVariableConfiguracion("host");
$url .= $this->miConfigurador->getVariableConfiguracion("site");
$url .= "/index.php?";

//Variables
$cadenaACodificar25 = "pagina=" . $this->miConfigurador->getVariableConfiguracion("pagina");
$cadenaACodificar25 .= "&procesarAjax=true";
$cadenaACodificar25 .= "&action=index.php";
$cadenaACodificar25 .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificar25 .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificar25 .= $cadenaACodificar25 . "&funcion=consultarDepartamentoAjax";
$cadenaACodificar25 .= "&tiempo=" . $_REQUEST ['tiempo'];

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");

$cadena25 = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar25, $enlace);

// URL definitiva
$urlFinal25 = $url . $cadena25;
//echo $urlFinal16; exit;
// URL base
$url = $this->miConfigurador->getVariableConfiguracion("host");
$url .= $this->miConfigurador->getVariableConfiguracion("site");
$url .= "/index.php?";

//Variables
$cadenaACodificar26 = "pagina=" . $this->miConfigurador->getVariableConfiguracion("pagina");
$cadenaACodificar26 .= "&procesarAjax=true";
$cadenaACodificar26 .= "&action=index.php";
$cadenaACodificar26 .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificar26 .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificar26 .= $cadenaACodificar26 . "&funcion=consultarCiudadAjax";
$cadenaACodificar26 .= "&tiempo=" . $_REQUEST ['tiempo'];

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");

$cadena26 = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar26, $enlace);

// URL definitiva
$urlFinal26 = $url . $cadena26;
//echo $urlFinal16; exit;
// URL base
$url = $this->miConfigurador->getVariableConfiguracion("host");
$url .= $this->miConfigurador->getVariableConfiguracion("site");
$url .= "/index.php?";

//Variables
$cadenaACodificar27 = "pagina=" . $this->miConfigurador->getVariableConfiguracion("pagina");
$cadenaACodificar27 .= "&procesarAjax=true";
$cadenaACodificar27 .= "&action=index.php";
$cadenaACodificar27 .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificar27 .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificar27 .= $cadenaACodificar27 . "&funcion=consultarDepartamentoAjax";
$cadenaACodificar27 .= "&tiempo=" . $_REQUEST ['tiempo'];

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");

$cadena27 = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar27, $enlace);

// URL definitiva
$urlFinal27 = $url . $cadena27;
//echo $urlFinal16; exit;
// URL base
$url = $this->miConfigurador->getVariableConfiguracion("host");
$url .= $this->miConfigurador->getVariableConfiguracion("site");
$url .= "/index.php?";

//Variables
$cadenaACodificar28 = "pagina=" . $this->miConfigurador->getVariableConfiguracion("pagina");
$cadenaACodificar28 .= "&procesarAjax=true";
$cadenaACodificar28 .= "&action=index.php";
$cadenaACodificar28 .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificar28 .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificar28 .= $cadenaACodificar28 . "&funcion=consultarCiudadAjax";
$cadenaACodificar28 .= "&tiempo=" . $_REQUEST ['tiempo'];

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");

$cadena28 = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar28, $enlace);

// URL definitiva
$urlFinal28 = $url . $cadena28;
//echo $urlFinal16; exit;
//Variables
$cadenaACodificar29 = "pagina=" . $this->miConfigurador->getVariableConfiguracion("pagina");
$cadenaACodificar29 .= "&procesarAjax=true";
$cadenaACodificar29 .= "&action=index.php";
$cadenaACodificar29 .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificar29 .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificar29 .= $cadenaACodificar29 . "&funcion=consultarUnidad";
$cadenaACodificar25 .= "&tiempo=" . $_REQUEST ['tiempo'];

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");

$cadena29 = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar29, $enlace);

// URL definitiva
$urlFinal29 = $url . $cadena29;

//Variables
$cadenaACodificarArchivo = "pagina=" . $this->miConfigurador->getVariableConfiguracion("pagina");
$cadenaACodificarArchivo .= "&procesarAjax=true";
$cadenaACodificarArchivo .= "&action=index.php";
$cadenaACodificarArchivo .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificarArchivo .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificarArchivo .= $cadenaACodificarArchivo . "&funcion=verificarArchivo";
$cadenaACodificarArchivo .= "&tiempo=" . $_REQUEST ['tiempo'];

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");

$cadenaArchivo = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificarArchivo, $enlace);

// URL definitiva
$urlFinalArchivo = $url . $cadenaArchivo;
?>


    $('#<?php echo $this->campoSeguro('IvaItem') ?>').width(150);
    $("#<?php echo $this->campoSeguro('IvaItem') ?>").select2();


    function consultarCiudad(elem, request, response) {
        $.ajax({
            url: "<?php echo $urlFinalCiudad ?>",
            dataType: "json",
            data: {valor: $("#<?php echo $this->campoSeguro('divisionCIIU') ?>").val()},
            success: function (data) {
                if (data[0] != " ") {
                    $("#<?php echo $this->campoSeguro('grupoCIIU') ?>").html("");
                    $("<option value=''>Seleccione .....</option>").appendTo("#<?php echo $this->campoSeguro('grupoCIIU') ?>");
                    $.each(data, function (indice, valor) {
                        $("<option value='" + data[ indice ].id_clase + "'>" + data[ indice ].nombre + "</option>").appendTo("#<?php echo $this->campoSeguro('grupoCIIU') ?>");
                    });
                }
            }
        });
    }
    ;
    ///////////////////////////////////////////////////////////////////////////////////// 

    function hora() {
        var hora = fecha.getHours();
        var minutos = fecha.getMinutes();
        var segundos = fecha.getSeconds();
        if (hora < 10) {
            hora = '0' + hora;
        }
        if (minutos < 10) {
            minutos = '0' + minutos;
        }
        if (segundos < 10) {
            segundos = '0' + segundos;
        }
        fecha.setSeconds(fecha.getSeconds() + 1);
        var fech = "<b>Fecha: " + fecha.getFullYear() + "/" + (fecha.getMonth() + 1) + "/" + fecha.getDate() + " <br> Hora: " + hora + ":" + minutos + ":" + segundos + "</b>";

        $('#<?php echo ('bannerReloj') ?>').text("Hora: " + hora + ":" + minutos + ":" + segundos);
        setTimeout("hora()", 1000);
    }

    fecha = new Date();
    hora();


    ////////////////////////////////////////////////////////////////////////////



    function consultarDepartamentoExp(elem, request, response) {
        $.ajax({
            url: "<?php echo $urlFinal25 ?>",
            dataType: "json",
            data: {valor: $("#<?php echo $this->campoSeguro('paisExpeNat') ?>").val()},
            success: function (data) {
                if (data[0] != " ") {
                    $("#<?php echo $this->campoSeguro('departamentoExpeNat') ?>").html('');
                    $("<option value=''>Seleccione  ....</option>").appendTo("#<?php echo $this->campoSeguro('departamentoExpeNat') ?>");
                    $.each(data, function (indice, valor) {
                        $("<option value='" + data[ indice ].id_departamento + "'>" + data[ indice ].nombre + "</option>").appendTo("#<?php echo $this->campoSeguro('departamentoExpeNat') ?>");

                    });

                    $("#<?php echo $this->campoSeguro('departamentoExpeNat') ?>").removeAttr('disabled');

                    //$('#<?php echo $this->campoSeguro('departamentoExpeNat') ?>').width(250);
                    $("#<?php echo $this->campoSeguro('departamentoExpeNat') ?>").select2();

                    $("#<?php echo $this->campoSeguro('departamentoExpeNat') ?>").removeClass("validate[required]");
                    $("#<?php echo $this->campoSeguro('paisExpeNat') ?>").removeClass("validate[required]");

                }


            }

        });
    }
    ;



    function consultarCiudadExp(elem, request, response) {
        $.ajax({
            url: "<?php echo $urlFinal26 ?>",
            dataType: "json",
            data: {valor: $("#<?php echo $this->campoSeguro('departamentoExpeNat') ?>").val()},
            success: function (data) {
                if (data[0] != " ") {
                    $("#<?php echo $this->campoSeguro('ciudadExpeNat') ?>").html('');
                    $("<option value=''>Seleccione  ....</option>").appendTo("#<?php echo $this->campoSeguro('ciudadExpeNat') ?>");
                    $.each(data, function (indice, valor) {
                        $("<option value='" + data[ indice ].id_ciudad + "'>" + data[ indice ].nombreciudad + "</option>").appendTo("#<?php echo $this->campoSeguro('ciudadExpeNat') ?>");

                    });

                    $("#<?php echo $this->campoSeguro('ciudadExpeNat') ?>").removeAttr('disabled');

                    //$('#<?php echo $this->campoSeguro('ciudadExpeNat') ?>').width(250);
                    $("#<?php echo $this->campoSeguro('ciudadExpeNat') ?>").select2();

                    $("#<?php echo $this->campoSeguro('ciudadExpeNat') ?>").removeClass("validate[required]");

                }

            }

        });
    }
    ;


    ////////////////////////////////////////////////////////////////////////////



    function consultarDepartamentoRep(elem, request, response) {
        $.ajax({
            url: "<?php echo $urlFinal27 ?>",
            dataType: "json",
            data: {valor: $("#<?php echo $this->campoSeguro('paisExpeRep') ?>").val()},
            success: function (data) {
                if (data[0] != " ") {
                    $("#<?php echo $this->campoSeguro('departamentoExpeRep') ?>").html('');
                    $("<option value=''>Seleccione  ....</option>").appendTo("#<?php echo $this->campoSeguro('departamentoExpeRep') ?>");
                    $.each(data, function (indice, valor) {
                        $("<option value='" + data[ indice ].id_departamento + "'>" + data[ indice ].nombre + "</option>").appendTo("#<?php echo $this->campoSeguro('departamentoExpeRep') ?>");

                    });

                    $("#<?php echo $this->campoSeguro('departamentoExpeRep') ?>").removeAttr('disabled');

                    //$('#<?php echo $this->campoSeguro('departamentoExpeRep') ?>').width(250);
                    $("#<?php echo $this->campoSeguro('departamentoExpeRep') ?>").select2();

                    $("#<?php echo $this->campoSeguro('departamentoExpeRep') ?>").removeClass("validate[required]");
                    $("#<?php echo $this->campoSeguro('paisExpeRep') ?>").removeClass("validate[required]");

                }


            }

        });
    }
    ;



    function consultarCiudadRep(elem, request, response) {
        $.ajax({
            url: "<?php echo $urlFinal28 ?>",
            dataType: "json",
            data: {valor: $("#<?php echo $this->campoSeguro('departamentoExpeRep') ?>").val()},
            success: function (data) {
                if (data[0] != " ") {
                    $("#<?php echo $this->campoSeguro('ciudadExpeRep') ?>").html('');
                    $("<option value=''>Seleccione  ....</option>").appendTo("#<?php echo $this->campoSeguro('ciudadExpeRep') ?>");
                    $.each(data, function (indice, valor) {
                        $("<option value='" + data[ indice ].id_ciudad + "'>" + data[ indice ].nombreciudad + "</option>").appendTo("#<?php echo $this->campoSeguro('ciudadExpeRep') ?>");

                    });

                    $("#<?php echo $this->campoSeguro('ciudadExpeRep') ?>").removeAttr('disabled');

                    //$('#<?php echo $this->campoSeguro('ciudadExpeRep') ?>').width(250);
                    $("#<?php echo $this->campoSeguro('ciudadExpeRep') ?>").select2();

                    $("#<?php echo $this->campoSeguro('ciudadExpeRep') ?>").removeClass("validate[required]");

                }

            }

        });
    }
    ;


    ////////////////////////////////////////////////////////////////////////////






















    function consultarDepartamentoLug(elem, request, response) {
        $.ajax({
            url: "<?php echo $urlFinal16 ?>",
            dataType: "json",
            data: {valor: $("#<?php echo $this->campoSeguro('personaJuridicaPais') ?>").val()},
            success: function (data) {
                if (data[0] != " ") {
                    $("#<?php echo $this->campoSeguro('personaJuridicaDepartamento') ?>").html('');
                    $("<option value=''>Seleccione  ....</option>").appendTo("#<?php echo $this->campoSeguro('personaJuridicaDepartamento') ?>");
                    $.each(data, function (indice, valor) {
                        $("<option value='" + data[ indice ].id_departamento + "'>" + data[ indice ].nombre + "</option>").appendTo("#<?php echo $this->campoSeguro('personaJuridicaDepartamento') ?>");

                    });

                    $("#<?php echo $this->campoSeguro('personaJuridicaDepartamento') ?>").removeAttr('disabled');

                    //$('#<?php echo $this->campoSeguro('personaJuridicaDepartamento') ?>').width(250);
                    $("#<?php echo $this->campoSeguro('personaJuridicaDepartamento') ?>").select2();

                    $("#<?php echo $this->campoSeguro('personaJuridicaDepartamento') ?>").removeClass("validate[required]");
                    $("#<?php echo $this->campoSeguro('personaJuridicaPais') ?>").removeClass("validate[required]");

                }


            }

        });
    }
    ;

    function consultarCodigoLug(elem, request, response) {
        $.ajax({
            url: "<?php echo $urlFinal19 ?>",
            dataType: "json",
            data: {valor: $("#<?php echo $this->campoSeguro('personaJuridicaPais') ?>").val()},
            success: function (data) {
                if (data[0] != " ") {
                    $("#<?php echo $this->campoSeguro('codigoPais') ?>").val(data[0].cod_pais);
                }


            }

        });
    }
    ;

    function consultarCiudadLug(elem, request, response) {
        $.ajax({
            url: "<?php echo $urlFinal17 ?>",
            dataType: "json",
            data: {valor: $("#<?php echo $this->campoSeguro('personaJuridicaDepartamento') ?>").val()},
            success: function (data) {
                if (data[0] != " ") {
                    $("#<?php echo $this->campoSeguro('personaJuridicaCiudad') ?>").html('');
                    $("<option value=''>Seleccione  ....</option>").appendTo("#<?php echo $this->campoSeguro('personaJuridicaCiudad') ?>");
                    $.each(data, function (indice, valor) {
                        $("<option value='" + data[ indice ].id_ciudad + "'>" + data[ indice ].nombreciudad + "</option>").appendTo("#<?php echo $this->campoSeguro('personaJuridicaCiudad') ?>");

                    });

                    $("#<?php echo $this->campoSeguro('personaJuridicaCiudad') ?>").removeAttr('disabled');

                    //$('#<?php echo $this->campoSeguro('personaJuridicaCiudad') ?>').width(250);
                    $("#<?php echo $this->campoSeguro('personaJuridicaCiudad') ?>").select2();

                    $("#<?php echo $this->campoSeguro('personaJuridicaCiudad') ?>").removeClass("validate[required]");

                }

            }

        });
    }
    ;


    function consultarCiudadCon(elem, request, response) {
        $.ajax({
            url: "<?php echo $urlFinal18 ?>",
            dataType: "json",
            data: {valor: $("#<?php echo $this->campoSeguro('personaNaturalContaDepartamento') ?>").val()},
            success: function (data) {
                if (data[0] != " ") {
                    $("#<?php echo $this->campoSeguro('personaNaturalContaCiudad') ?>").html('');
                    $("<option value=''>Seleccione  ....</option>").appendTo("#<?php echo $this->campoSeguro('personaNaturalContaCiudad') ?>");
                    $.each(data, function (indice, valor) {
                        $("<option value='" + data[ indice ].id_ciudad + "'>" + data[ indice ].nombreciudad + "</option>").appendTo("#<?php echo $this->campoSeguro('personaNaturalContaCiudad') ?>");

                    });

                    $("#<?php echo $this->campoSeguro('personaNaturalContaCiudad') ?>").removeAttr('disabled');

                    //$('#<?php echo $this->campoSeguro('personaNaturalContaCiudad') ?>').width(250);
                    $("#<?php echo $this->campoSeguro('personaNaturalContaCiudad') ?>").select2();

                    $("#<?php echo $this->campoSeguro('personaNaturalContaCiudad') ?>").removeClass("validate[required]");

                }

            }

        });
    }
    ;


    function consultarCiudadJur(elem, request, response) {
        $.ajax({
            url: "<?php echo $urlFinal20 ?>",
            dataType: "json",
            data: {valor: $("#<?php echo $this->campoSeguro('departamento') ?>").val()},
            success: function (data) {
                if (data[0] != " ") {
                    $("#<?php echo $this->campoSeguro('ciudad') ?>").html('');
                    $("<option value=''>Seleccione  ....</option>").appendTo("#<?php echo $this->campoSeguro('ciudad') ?>");
                    $.each(data, function (indice, valor) {
                        $("<option value='" + data[ indice ].id_ciudad + "'>" + data[ indice ].nombreciudad + "</option>").appendTo("#<?php echo $this->campoSeguro('ciudad') ?>");

                    });

                    $("#<?php echo $this->campoSeguro('ciudad') ?>").removeAttr('disabled');

                    //$('#<?php echo $this->campoSeguro('ciudad') ?>').width(250);
                    $("#<?php echo $this->campoSeguro('ciudad') ?>").select2();

                    $("#<?php echo $this->campoSeguro('ciudad') ?>").removeClass("validate[required]");

                }

            }

        });
    }
    ;


    function consultarNomenclaturaNat(elem, request, response) {
        $.ajax({
            url: "<?php echo $urlFinal21 ?>",
            dataType: "json",
            data: {valor: $("#<?php echo $this->campoSeguro('listaNomenclaturasNat') ?>").val()},
            success: function (data) {
                if (data[0] != " ") {
                    $("#<?php echo $this->campoSeguro('seccionParametrosNat') ?>").html('');
                    $.each(data, function (indice, valor) {
                        $("<option value='" + data[ indice ].id_nomenclatura + "'>" + data[ indice ].abreviatura + "</option>").appendTo("#<?php echo $this->campoSeguro('seccionParametrosNat') ?>");

                    });

                    $("#<?php echo $this->campoSeguro('seccionParametrosNat') ?>").removeAttr('disabled');

                    //$('#<?php echo $this->campoSeguro('seccionParametrosNat') ?>').width(250);
                    $("#<?php echo $this->campoSeguro('seccionParametrosNat') ?>").select2();

                    $("#<?php echo $this->campoSeguro('seccionParametrosNat') ?>").removeClass("validate[required]");

                }

            }

        });
    }
    ;


    function consultarNomenclatura(elem, request, response) {
        $.ajax({
            url: "<?php echo $urlFinal22 ?>",
            dataType: "json",
            data: {valor: $("#<?php echo $this->campoSeguro('listaNomenclaturas') ?>").val()},
            success: function (data) {
                if (data[0] != " ") {
                    $("#<?php echo $this->campoSeguro('seccionParametros') ?>").html('');
                    $.each(data, function (indice, valor) {
                        $("<option value='" + data[ indice ].id_nomenclatura + "'>" + data[ indice ].abreviatura + "</option>").appendTo("#<?php echo $this->campoSeguro('seccionParametros') ?>");

                    });

                    $("#<?php echo $this->campoSeguro('seccionParametros') ?>").removeAttr('disabled');

                    //$('#<?php echo $this->campoSeguro('seccionParametros') ?>').width(250);
                    $("#<?php echo $this->campoSeguro('seccionParametros') ?>").select2();


                    $("#<?php echo $this->campoSeguro('seccionParametros') ?>").removeClass("validate[required]");



                }

            }

        });
    }
    ;


    function consultarNBC(elem, request, response) {

        $.ajax({
            url: "<?php echo $urlFinal23 ?>",
            dataType: "json",
            data: {valor: $("#<?php echo $this->campoSeguro('personaNaturalArea') ?>").val()},
            success: function (data) {
                if (data[0] != " ") {
                    $("#<?php echo $this->campoSeguro('personaNaturalNBC') ?>").html('');
                    $("<option value=''>Seleccione  ....</option>").appendTo("#<?php echo $this->campoSeguro('personaNaturalNBC') ?>");
                    $.each(data, function (indice, valor) {
                        $("<option value='" + data[ indice ].id_nucleo + "'>" + data[ indice ].nombre + "</option>").appendTo("#<?php echo $this->campoSeguro('personaNaturalNBC') ?>");

                    });

                    $("#<?php echo $this->campoSeguro('personaNaturalNBC') ?>").removeAttr('disabled');

                    $("#<?php echo $this->campoSeguro('personaNaturalNBC') ?>").select2();

                    $("#<?php echo $this->campoSeguro('personaNaturalArea') ?>").removeClass("validate[required]");



                }

            }

        });
    }
    ;


    function consultarNBCRep(elem, request, response) {
        $.ajax({
            url: "<?php echo $urlFinal24 ?>",
            dataType: "json",
            data: {valor: $("#<?php echo $this->campoSeguro('personaArea') ?>").val()},
            success: function (data) {
                if (data[0] != " ") {
                    $("#<?php echo $this->campoSeguro('personaNBC') ?>").html('');
                    $("<option value=''>Seleccione  ....</option>").appendTo("#<?php echo $this->campoSeguro('personaNBC') ?>");
                    $.each(data, function (indice, valor) {
                        $("<option value='" + data[ indice ].id_nucleo + "'>" + data[ indice ].nombre + "</option>").appendTo("#<?php echo $this->campoSeguro('personaNBC') ?>");

                    });

                    $("#<?php echo $this->campoSeguro('personaNBC') ?>").removeAttr('disabled');

                    $("#<?php echo $this->campoSeguro('personaNBC') ?>").select2();

                    $("#<?php echo $this->campoSeguro('personaArea') ?>").removeClass("validate[required]");



                }

            }

        });
    }
    ;

    function calcularDigito(cadenaNit) {

        var num_primos, control_mod_1, control_mod_2, tamano_nit, i, digito_verificacion;

        if (isNaN(cadenaNit)) {
            alert('El valor digitado no es un numero valido');
        } else {
            num_primos = new Array(16);
            control_mod_1 = 0;
            control_mod_2 = 0;
            tamano_nit = cadenaNit.length;

            num_primos[1] = 3;
            num_primos[2] = 7;
            num_primos[3] = 13;
            num_primos[4] = 17;
            num_primos[5] = 19;
            num_primos[6] = 23;
            num_primos[7] = 29;
            num_primos[8] = 37;
            num_primos[9] = 41;
            num_primos[10] = 43;
            num_primos[11] = 47;
            num_primos[12] = 53;
            num_primos[13] = 59;
            num_primos[14] = 67;
            num_primos[15] = 71;

            for (i = 0; i < tamano_nit; i++)
            {
                control_mod_2 = (cadenaNit.substr(i, 1));
                control_mod_1 += (control_mod_2 * num_primos[tamano_nit - i]);
            }
            control_mod_2 = control_mod_1 % 11;

            if (control_mod_2 > 1)
            {
                digito_verificacion = 11 - control_mod_2;
            } else {
                digito_verificacion = control_mod_2;
            }
            $("#<?php echo $this->campoSeguro('digito') ?>").val(digito_verificacion);
        }
    }
    ;


    function calcularDigitoCedula(cadenaCedula) {

        var num_primos, control_mod_1, control_mod_2, tamano_cedula, i, digito_verificacion;

        if (isNaN(cadenaCedula)) {
            alert('El valor digitado no es un numero valido');
        } else {
            num_primos = new Array(16);
            control_mod_1 = 0;
            control_mod_2 = 0;
            tamano_cedula = cadenaCedula.length;

            num_primos[1] = 3;
            num_primos[2] = 7;
            num_primos[3] = 13;
            num_primos[4] = 17;
            num_primos[5] = 19;
            num_primos[6] = 23;
            num_primos[7] = 29;
            num_primos[8] = 37;
            num_primos[9] = 41;
            num_primos[10] = 43;
            num_primos[11] = 47;
            num_primos[12] = 53;
            num_primos[13] = 59;
            num_primos[14] = 67;
            num_primos[15] = 71;

            for (i = 0; i < tamano_cedula; i++)
            {
                control_mod_2 = (cadenaCedula.substr(i, 1));
                control_mod_1 += (control_mod_2 * num_primos[tamano_cedula - i]);
            }
            control_mod_2 = control_mod_1 % 11;

            if (control_mod_2 > 1)
            {
                digito_verificacion = 11 - control_mod_2;
            } else {
                digito_verificacion = control_mod_2;
            }
            $("#<?php echo $this->campoSeguro('digitoNat') ?>").val(digito_verificacion);
        }
    }
    ;

    function calcularDigitoCedulaRepre(cadenaCedula) {

        var num_primos, control_mod_1, control_mod_2, tamano_cedula, i, digito_verificacion;

        if (isNaN(cadenaCedula)) {
            alert('El valor digitado no es un numero valido');
        } else {
            num_primos = new Array(16);
            control_mod_1 = 0;
            control_mod_2 = 0;
            tamano_cedula = cadenaCedula.length;

            num_primos[1] = 3;
            num_primos[2] = 7;
            num_primos[3] = 13;
            num_primos[4] = 17;
            num_primos[5] = 19;
            num_primos[6] = 23;
            num_primos[7] = 29;
            num_primos[8] = 37;
            num_primos[9] = 41;
            num_primos[10] = 43;
            num_primos[11] = 47;
            num_primos[12] = 53;
            num_primos[13] = 59;
            num_primos[14] = 67;
            num_primos[15] = 71;

            for (i = 0; i < tamano_cedula; i++)
            {
                control_mod_2 = (cadenaCedula.substr(i, 1));
                control_mod_1 += (control_mod_2 * num_primos[tamano_cedula - i]);
            }
            control_mod_2 = control_mod_1 % 11;

            if (control_mod_2 > 1)
            {
                digito_verificacion = 11 - control_mod_2;
            } else {
                digito_verificacion = control_mod_2;
            }
            $("#<?php echo $this->campoSeguro('digitoRepre') ?>").val(digito_verificacion);
        }
    }
    ;



    function clearFileInput($input) {
        if ($input.val() == '') {
            return;
        }
        // Fix for IE ver < 11, that does not clear file inputs
        // Requires a sequence of steps to prevent IE crashing but
        // still allow clearing of the file input.
        if (/MSIE/.test(navigator.userAgent)) {
            var $frm1 = $input.closest('form');
            if ($frm1.length) { // check if the input is already wrapped in a form
                $input.wrap('<form>');
                var $frm2 = $input.closest('form'), // the wrapper form
                        $tmpEl = $(document.createElement('div')); // a temporary placeholder element
                $frm2.before($tmpEl).after($frm1).trigger('reset');
                $input.unwrap().appendTo($tmpEl).unwrap();
            } else { // no parent form exists - just wrap a form element
                $input.wrap('<form>').closest('form').trigger('reset').unwrap();
            }
        } else { // normal reset behavior for other sane browsers
            $input.val('');
        }
    }


    $(function () {





        $("#<?php echo $this->campoSeguro('paisExpeNat') ?>").change(function () {
            if ($("#<?php echo $this->campoSeguro('paisExpeNat') ?>").val() != '') {
                consultarDepartamentoExp();
            } else {
                $("#<?php echo $this->campoSeguro('departamentoExpeNat') ?>").attr('disabled', '');
            }
        });

        $("#<?php echo $this->campoSeguro('departamentoExpeNat') ?>").change(function () {
            if ($("#<?php echo $this->campoSeguro('departamentoExpeNat') ?>").val() != '') {
                consultarCiudadExp();
            } else {
                $("#<?php echo $this->campoSeguro('ciudadExpeNat') ?>").attr('disabled', '');
            }
        });



        $("#<?php echo $this->campoSeguro('paisExpeRep') ?>").change(function () {
            if ($("#<?php echo $this->campoSeguro('paisExpeRep') ?>").val() != '') {
                consultarDepartamentoRep();
            } else {
                $("#<?php echo $this->campoSeguro('departamentoExpeRep') ?>").attr('disabled', '');
            }
        });

        $("#<?php echo $this->campoSeguro('departamentoExpeRep') ?>").change(function () {
            if ($("#<?php echo $this->campoSeguro('departamentoExpeRep') ?>").val() != '') {
                consultarCiudadRep();
            } else {
                $("#<?php echo $this->campoSeguro('ciudadExpeRep') ?>").attr('disabled', '');
            }
        });




        $("#<?php echo $this->campoSeguro('personaJuridicaPais') ?>").change(function () {
            if ($("#<?php echo $this->campoSeguro('personaJuridicaPais') ?>").val() != '') {
                consultarDepartamentoLug();
                consultarCodigoLug();
            } else {
                $("#<?php echo $this->campoSeguro('personaJuridicaDepartamento') ?>").attr('disabled', '');
            }
        });

        $("#<?php echo $this->campoSeguro('personaJuridicaDepartamento') ?>").change(function () {
            if ($("#<?php echo $this->campoSeguro('personaJuridicaDepartamento') ?>").val() != '') {
                consultarCiudadLug();
            } else {
                $("#<?php echo $this->campoSeguro('personaJuridicaCiudad') ?>").attr('disabled', '');
            }
        });


        $("#<?php echo $this->campoSeguro('personaNaturalContaDepartamento') ?>").change(function () {
            if ($("#<?php echo $this->campoSeguro('personaNaturalContaDepartamento') ?>").val() != '') {
                consultarCiudadCon();
            } else {
                $("#<?php echo $this->campoSeguro('personaNaturalContaCiudad') ?>").attr('disabled', '');
            }
        });


        $("#<?php echo $this->campoSeguro('departamento') ?>").change(function () {
            if ($("#<?php echo $this->campoSeguro('departamento') ?>").val() != '') {
                consultarCiudadJur();
            } else {
                $("#<?php echo $this->campoSeguro('ciudad') ?>").attr('disabled', '');
            }
        });


        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////   


        $("#<?php echo $this->campoSeguro('personaNaturalArea') ?>").change(function () {
            if ($("#<?php echo $this->campoSeguro('personaNaturalArea') ?>").val() != '') {
                consultarNBC();
            } else {
                $("#<?php echo $this->campoSeguro('personaNaturalArea') ?>").addClass("validate[required]");
                $("#<?php echo $this->campoSeguro('personaNaturalNBC') ?>").attr('disabled', '');
            }
        });

        $("#<?php echo $this->campoSeguro('personaNaturalNBC') ?>").change(function () {
            if ($("#<?php echo $this->campoSeguro('personaNaturalNBC') ?>").val() != '') {
                $("#<?php echo $this->campoSeguro('personaNaturalNBC') ?>").removeClass("validate[required]");
            } else {
                $("#<?php echo $this->campoSeguro('personaNaturalNBC') ?>").addClass("validate[required]");
            }
        });

        $("#<?php echo $this->campoSeguro('personaArea') ?>").change(function () {
            if ($("#<?php echo $this->campoSeguro('personaArea') ?>").val() != '') {
                consultarNBCRep();
            } else {
                $("#<?php echo $this->campoSeguro('personaArea') ?>").addClass("validate[required]");
                $("#<?php echo $this->campoSeguro('personaNBC') ?>").attr('disabled', '');
            }
        });

        $("#<?php echo $this->campoSeguro('personaNBC') ?>").change(function () {
            if ($("#<?php echo $this->campoSeguro('personaNBC') ?>").val() != '') {
                $("#<?php echo $this->campoSeguro('personaNBC') ?>").removeClass("validate[required]");
            } else {
                $("#<?php echo $this->campoSeguro('personaNBC') ?>").addClass("validate[required]");
            }
        });


        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////    
        $("#<?php echo $this->campoSeguro('nit') ?>").on('keyup', function () {//Ejecutar la Evaluación por Eventos de Teclado
            var value = $(this).val().length;
            if (value == 9) {//Ejecutar solo Cuando se Completa el NIT
                var cadenaNit = $(this).val();
                calcularDigito(cadenaNit);//LLamar la Función para Ejecutar Calculo Digito Verificación
            } else {
                var cadenaNit = null;
                $("#<?php echo $this->campoSeguro('digito') ?>").val(null);
            }

        }).keyup();


        $("#<?php echo $this->campoSeguro('documentoNat') ?>").on('keyup', function () {//Ejecutar la Evaluación por Eventos de Teclado
            var value = $(this).val().length;
            if (value > 3) {//Ejecutar solo Cuando se Completa el NIT
                var cadenaCedula = $(this).val();
                calcularDigitoCedula(cadenaCedula);//LLamar la Función para Ejecutar Calculo Digito Verificación
            } else {
                var cadenaCedula = null;
                $("#<?php echo $this->campoSeguro('digitoNat') ?>").val(null);
            }

        }).keyup();


        $("#<?php echo $this->campoSeguro('numeroDocumento') ?>").on('keyup', function () {//Ejecutar la Evaluación por Eventos de Teclado
            var value = $(this).val().length;
            if (value > 3) {//Ejecutar solo Cuando se Completa el NIT
                var cadenaCedula = $(this).val();
                calcularDigitoCedulaRepre(cadenaCedula);//LLamar la Función para Ejecutar Calculo Digito Verificación
            } else {
                var cadenaCedula = null;
                $("#<?php echo $this->campoSeguro('digitoRepre') ?>").val(null);
            }

        }).keyup();

    });



    $('#<?php echo $this->campoSeguro('direccion') ?>').keypress(function (tecla) {
        if (tecla.charCode != 48 && tecla.charCode != 49 && tecla.charCode != 50 &&
                tecla.charCode != 51 && tecla.charCode != 52 && tecla.charCode != 53 &&
                tecla.charCode != 54 && tecla.charCode != 55 && tecla.charCode != 56 &&
                tecla.charCode != 57 && tecla.charCode != 0 && tecla.charCode != 32)
            return false;
    });

    $(function () {
        $('#parametros').draggable({
            revert: true,
            helper: 'clone',
            start: function (event, ui) {
                $(this).fadeTo('fast', 1.5);
            },
            stop: function (event, ui) {
                $(this).fadeTo(0, 1);
            }
        });

        $('#<?php echo $this->campoSeguro('direccion') ?>').droppable({
            hoverClass: 'active',
            drop: function (event, ui) {
                if ($(ui.draggable).find('select option:selected').text() != 'Nomenclatura') {
                    this.value += $(ui.draggable).find('select option:selected').text() + " ";
                }
            }
        });


        $("#<?php echo $this->campoSeguro('listaNomenclaturas') ?>").change(function () {
            if ($("#<?php echo $this->campoSeguro('listaNomenclaturas') ?>").val() != '') {
                consultarNomenclatura();
            } else {
                $("#<?php echo $this->campoSeguro('seccionParametros') ?>").attr('disabled', '');
            }
        });
    });

    $('#<?php echo $this->campoSeguro('listaNomenclaturas') ?>').change(function () {

        $("#<?php echo $this->campoSeguro('seccionParametros') ?>").removeAttr('disabled');
        $("#<?php echo $this->campoSeguro('seccionParametros') ?>").select2();
    });



    $('#<?php echo $this->campoSeguro('DocumentoRUTNat') ?>').change(function () {
        var ext = $('#<?php echo $this->campoSeguro('DocumentoRUTNat') ?>').val().split('.').pop().toLowerCase();
        $fileupload = $('#<?php echo $this->campoSeguro('DocumentoRUTNat') ?>');
        if ($.inArray(ext, ['pdf']) == -1) {
            alert('Extension de Archivo No Permitida!');

            clearFileInput($fileupload);
            //$fileupload.replaceWith($fileupload.clone(true));
        }
    });



    $('#<?php echo $this->campoSeguro('DocumentoRUT') ?>').change(function () {
        var ext = $('#<?php echo $this->campoSeguro('DocumentoRUT') ?>').val().split('.').pop().toLowerCase();
        $fileupload = $('#<?php echo $this->campoSeguro('DocumentoRUT') ?>');
        if ($.inArray(ext, ['pdf']) == -1) {
            alert('Extension de Archivo No Permitida!');

            clearFileInput($fileupload);
            //$fileupload.replaceWith($fileupload.clone(true));
        }
    });


    $('#<?php echo $this->campoSeguro('DocumentoRUPNat') ?>').change(function () {
        var ext = $('#<?php echo $this->campoSeguro('DocumentoRUPNat') ?>').val().split('.').pop().toLowerCase();
        $fileupload = $('#<?php echo $this->campoSeguro('DocumentoRUPNat') ?>');
        if ($.inArray(ext, ['pdf']) == -1) {
            alert('Extension de Archivo No Permitida!');

            clearFileInput($fileupload);
            //$fileupload.replaceWith($fileupload.clone(true));
        }
    });



    $('#<?php echo $this->campoSeguro('DocumentoRUP') ?>').change(function () {
        var ext = $('#<?php echo $this->campoSeguro('DocumentoRUP') ?>').val().split('.').pop().toLowerCase();
        $fileupload = $('#<?php echo $this->campoSeguro('DocumentoRUP') ?>');
        if ($.inArray(ext, ['pdf']) == -1) {
            alert('Extension de Archivo No Permitida!');

            clearFileInput($fileupload);
            //$fileupload.replaceWith($fileupload.clone(true));
        }
    });



    $("#condicionesCheckNat").change(function () {

        if (this.checked) {
            $("#botonesNat").show("slow");
        } else {
            $("#botonesNat").hide("fast");
        }

    });


    $("#condicionesCheckJur").change(function () {

        if (this.checked) {
            $("#botonesJur").show("slow");
        } else {
            $("#botonesJur").hide("fast");
        }

    });




    $("#btOper1").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccion') ?>').val();
        var post = actual + " A ";
        $('#<?php echo $this->campoSeguro('direccion') ?>').val(post);
    });

    $("#btOper2").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccion') ?>').val();
        var post = actual + " B ";
        $('#<?php echo $this->campoSeguro('direccion') ?>').val(post);
    });

    $("#btOper3").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccion') ?>').val();
        var post = actual + " C ";
        $('#<?php echo $this->campoSeguro('direccion') ?>').val(post);
    });

    $("#btOper4").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccion') ?>').val();
        var post = actual + " D ";
        $('#<?php echo $this->campoSeguro('direccion') ?>').val(post);
    });

    $("#btOper5").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccion') ?>').val();
        var post = actual + " E ";
        $('#<?php echo $this->campoSeguro('direccion') ?>').val(post);
    });

    $("#btOper6").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccion') ?>').val();
        var post = actual + " F ";
        $('#<?php echo $this->campoSeguro('direccion') ?>').val(post);
    });

    $("#btOper7").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccion') ?>').val();
        var post = actual + " G ";
        $('#<?php echo $this->campoSeguro('direccion') ?>').val(post);
    });

    $("#btOper8").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccion') ?>').val();
        var post = actual + " H ";
        $('#<?php echo $this->campoSeguro('direccion') ?>').val(post);
    });

    $("#btOper9").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccion') ?>').val();
        var post = actual + " I ";
        $('#<?php echo $this->campoSeguro('direccion') ?>').val(post);
    });

    $("#btOper10").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccion') ?>').val();
        var post = actual + " J ";
        $('#<?php echo $this->campoSeguro('direccion') ?>').val(post);
    });

    $("#btOper11").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccion') ?>').val();
        var post = actual + " K ";
        $('#<?php echo $this->campoSeguro('direccion') ?>').val(post);
    });

    $("#btOper12").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccion') ?>').val();
        var post = actual + " L ";
        $('#<?php echo $this->campoSeguro('direccion') ?>').val(post);
    });

    $("#btOper13").click(function () {
        $('#<?php echo $this->campoSeguro('direccion') ?>').val("");
    });

    $("#btOper15").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccion') ?>').val();
        var post = actual + " M ";
        $('#<?php echo $this->campoSeguro('direccion') ?>').val(post);
    });

    $("#btOper16").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccion') ?>').val();
        var post = actual + " N ";
        $('#<?php echo $this->campoSeguro('direccion') ?>').val(post);
    });

    $("#btOper17").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccion') ?>').val();
        var post = actual + " O ";
        $('#<?php echo $this->campoSeguro('direccion') ?>').val(post);
    });

    $("#btOper18").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccion') ?>').val();
        var post = actual + " P ";
        $('#<?php echo $this->campoSeguro('direccion') ?>').val(post);
    });

    $("#btOper19").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccion') ?>').val();
        var post = actual + " Q ";
        $('#<?php echo $this->campoSeguro('direccion') ?>').val(post);
    });

    $("#btOper20").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccion') ?>').val();
        var post = actual + " R ";
        $('#<?php echo $this->campoSeguro('direccion') ?>').val(post);
    });

    $("#btOper21").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccion') ?>').val();
        var post = actual + " S ";
        $('#<?php echo $this->campoSeguro('direccion') ?>').val(post);
    });

    $("#btOper22").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccion') ?>').val();
        var post = actual + " T ";
        $('#<?php echo $this->campoSeguro('direccion') ?>').val(post);
    });

    $("#btOper23").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccion') ?>').val();
        var post = actual + " U ";
        $('#<?php echo $this->campoSeguro('direccion') ?>').val(post);
    });

    $("#btOper24").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccion') ?>').val();
        var post = actual + " V ";
        $('#<?php echo $this->campoSeguro('direccion') ?>').val(post);
    });

    $("#btOper25").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccion') ?>').val();
        var post = actual + " W ";
        $('#<?php echo $this->campoSeguro('direccion') ?>').val(post);
    });

    $("#btOper26").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccion') ?>').val();
        var post = actual + " X ";
        $('#<?php echo $this->campoSeguro('direccion') ?>').val(post);
    });

    $("#btOper27").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccion') ?>').val();
        var post = actual + " Y ";
        $('#<?php echo $this->campoSeguro('direccion') ?>').val(post);
    });

    $("#btOper28").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccion') ?>').val();
        var post = actual + " Z ";
        $('#<?php echo $this->campoSeguro('direccion') ?>').val(post);
    });

    $("#btOper14").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccion') ?>').val();
        if ($('#<?php echo $this->campoSeguro('seccionParametros') ?>' + ' option:selected').html() != 'Nomenclatura') {
            var post = actual + ' ' + $('#<?php echo $this->campoSeguro('seccionParametros') ?>' + ' option:selected').html() + ' ';
            $('#<?php echo $this->campoSeguro('direccion') ?>').val(post);
        }
    });




    $('#<?php echo $this->campoSeguro('direccionNat') ?>').keypress(function (tecla) {


        if (tecla.charCode != 48 && tecla.charCode != 49 && tecla.charCode != 50 &&
                tecla.charCode != 51 && tecla.charCode != 52 && tecla.charCode != 53 &&
                tecla.charCode != 54 && tecla.charCode != 55 && tecla.charCode != 56 &&
                tecla.charCode != 57 && tecla.charCode != 0 && tecla.charCode != 32)
            return false;
    });

    $(function () {
        $('#parametrosNat').draggable({
            revert: true,
            helper: 'clone',
            start: function (event, ui) {
                $(this).fadeTo('fast', 1.5);
            },
            stop: function (event, ui) {
                $(this).fadeTo(0, 1);
            }
        });

        $('#<?php echo $this->campoSeguro('direccionNat') ?>').droppable({
            hoverClass: 'active',
            drop: function (event, ui) {
                if ($(ui.draggable).find('select option:selected').text() != 'Nomenclatura') {
                    this.value += $(ui.draggable).find('select option:selected').text() + " ";
                }
            }
        });


        $("#<?php echo $this->campoSeguro('listaNomenclaturasNat') ?>").change(function () {
            if ($("#<?php echo $this->campoSeguro('listaNomenclaturasNat') ?>").val() != '') {
                consultarNomenclaturaNat();
            } else {
                $("#<?php echo $this->campoSeguro('seccionParametrosNat') ?>").attr('disabled', '');
            }
        });
    });

    $('#<?php echo $this->campoSeguro('listaNomenclaturasNat') ?>').change(function () {

        $("#<?php echo $this->campoSeguro('seccionParametrosNat') ?>").removeAttr('disabled');
        $("#<?php echo $this->campoSeguro('seccionParametrosNat') ?>").select2();
    });


    $("#btOper1Nat").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccionNat') ?>').val();
        var post = actual + " A ";
        $('#<?php echo $this->campoSeguro('direccionNat') ?>').val(post);
    });

    $("#btOper2Nat").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccionNat') ?>').val();
        var post = actual + " B ";
        $('#<?php echo $this->campoSeguro('direccionNat') ?>').val(post);
    });

    $("#btOper3Nat").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccionNat') ?>').val();
        var post = actual + " C ";
        $('#<?php echo $this->campoSeguro('direccionNat') ?>').val(post);
    });

    $("#btOper4Nat").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccionNat') ?>').val();
        var post = actual + " D ";
        $('#<?php echo $this->campoSeguro('direccionNat') ?>').val(post);
    });

    $("#btOper5Nat").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccionNat') ?>').val();
        var post = actual + " E ";
        $('#<?php echo $this->campoSeguro('direccionNat') ?>').val(post);
    });

    $("#btOper6Nat").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccionNat') ?>').val();
        var post = actual + " F ";
        $('#<?php echo $this->campoSeguro('direccionNat') ?>').val(post);
    });

    $("#btOper7Nat").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccionNat') ?>').val();
        var post = actual + " G ";
        $('#<?php echo $this->campoSeguro('direccionNat') ?>').val(post);
    });

    $("#btOper8Nat").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccionNat') ?>').val();
        var post = actual + " H ";
        $('#<?php echo $this->campoSeguro('direccionNat') ?>').val(post);
    });

    $("#btOper9Nat").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccionNat') ?>').val();
        var post = actual + " I ";
        $('#<?php echo $this->campoSeguro('direccionNat') ?>').val(post);
    });

    $("#btOper10Nat").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccionNat') ?>').val();
        var post = actual + " J ";
        $('#<?php echo $this->campoSeguro('direccionNat') ?>').val(post);
    });

    $("#btOper11Nat").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccionNat') ?>').val();
        var post = actual + " K ";
        $('#<?php echo $this->campoSeguro('direccionNat') ?>').val(post);
    });

    $("#btOper12Nat").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccionNat') ?>').val();
        var post = actual + " L ";
        $('#<?php echo $this->campoSeguro('direccionNat') ?>').val(post);
    });

    $("#btOper13Nat").click(function () {
        $('#<?php echo $this->campoSeguro('direccionNat') ?>').val("");
    });

    $("#btOper15Nat").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccionNat') ?>').val();
        var post = actual + " M ";
        $('#<?php echo $this->campoSeguro('direccionNat') ?>').val(post);
    });

    $("#btOper16Nat").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccionNat') ?>').val();
        var post = actual + " N ";
        $('#<?php echo $this->campoSeguro('direccionNat') ?>').val(post);
    });

    $("#btOper17Nat").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccionNat') ?>').val();
        var post = actual + " O ";
        $('#<?php echo $this->campoSeguro('direccionNat') ?>').val(post);
    });

    $("#btOper18Nat").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccionNat') ?>').val();
        var post = actual + " P ";
        $('#<?php echo $this->campoSeguro('direccionNat') ?>').val(post);
    });

    $("#btOper19Nat").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccionNat') ?>').val();
        var post = actual + " Q ";
        $('#<?php echo $this->campoSeguro('direccionNat') ?>').val(post);
    });

    $("#btOper20Nat").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccionNat') ?>').val();
        var post = actual + " R ";
        $('#<?php echo $this->campoSeguro('direccionNat') ?>').val(post);
    });

    $("#btOper21Nat").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccionNat') ?>').val();
        var post = actual + " S ";
        $('#<?php echo $this->campoSeguro('direccionNat') ?>').val(post);
    });

    $("#btOper22Nat").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccionNat') ?>').val();
        var post = actual + " T ";
        $('#<?php echo $this->campoSeguro('direccionNat') ?>').val(post);
    });

    $("#btOper23Nat").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccionNat') ?>').val();
        var post = actual + " U ";
        $('#<?php echo $this->campoSeguro('direccionNat') ?>').val(post);
    });

    $("#btOper24Nat").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccionNat') ?>').val();
        var post = actual + " V ";
        $('#<?php echo $this->campoSeguro('direccionNat') ?>').val(post);
    });

    $("#btOper25Nat").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccionNat') ?>').val();
        var post = actual + " W ";
        $('#<?php echo $this->campoSeguro('direccionNat') ?>').val(post);
    });

    $("#btOper26Nat").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccionNat') ?>').val();
        var post = actual + " X ";
        $('#<?php echo $this->campoSeguro('direccionNat') ?>').val(post);
    });

    $("#btOper27Nat").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccionNat') ?>').val();
        var post = actual + " Y ";
        $('#<?php echo $this->campoSeguro('direccionNat') ?>').val(post);
    });

    $("#btOper28Nat").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccionNat') ?>').val();
        var post = actual + " Z ";
        $('#<?php echo $this->campoSeguro('direccionNat') ?>').val(post);
    });

    $("#btOper14Nat").click(function () {
        var actual = $('#<?php echo $this->campoSeguro('direccionNat') ?>').val();
        if ($('#<?php echo $this->campoSeguro('seccionParametrosNat') ?>' + ' option:selected').html() != 'Nomenclatura') {
            var post = actual + ' ' + $('#<?php echo $this->campoSeguro('seccionParametrosNat') ?>' + ' option:selected').html() + ' ';
            $('#<?php echo $this->campoSeguro('direccionNat') ?>').val(post);
        }
    });







  

    function isNormalInteger(str) {
        var n = Math.floor(Number(str));
        return String(n) === str && n >= 0;
    }

    function totalDias(years, months, days) {

        var totalDays = (years * 360) + (months * 30) + days;

        return totalDays;

    }

    function inverseTotalDias(days) {

        var nyears = parseInt(days / 360);
        var nmonths = parseInt((days - parseInt(days / 360) * 360) / 30);
        var ndays = parseInt(days - (parseInt(days / 360) * 360 + parseInt((days - parseInt(days / 360) * 360) / 30) * 30));

        return nyears + " AÑO(S) - " + nmonths + " MES(ES) - " + ndays + " DÍA(S)";

    }


    function currency(value, decimals, separators) {
        decimals = decimals >= 0 ? parseInt(decimals, 0) : 2;
        separators = separators || ['.', "'", ','];
        var number = (parseFloat(value) || 0).toFixed(decimals);
        if (number.length <= (4 + decimals))
            return number.replace('.', separators[separators.length - 1]);
        var parts = number.split(/[-.]/);
        value = parts[parts.length > 1 ? parts.length - 2 : 0];
        var result = value.substr(value.length - 3, 3) + (parts.length > 1 ?
                separators[separators.length - 1] + parts[parts.length - 1] : '');
        var start = value.length - 6;
        var idx = 0;
        while (start > -3) {
            result = (start > 0 ? value.substr(start, 3) : value.substr(0, 3 + start))
                    + separators[idx] + result;
            idx = (++idx) % 2;
            start -= 3;
        }
        return (parts.length == 3 ? '-' : '') + result;
    }


    function formatearNumero(nStr) {
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? ',' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + '.' + '$2');
        }
        return x1 + x2;
    }


    $("#botonAgregar").click(function () {

        if ($("#<?php echo $this->campoSeguro('tipoItem') ?>").val() == 1) {//BIEN


            if ($("#<?php echo $this->campoSeguro('nombreItem') ?>").val() != '' &&
                    $("#<?php echo $this->campoSeguro('descripcionItem') ?>").val() != '' &&
                    $("#<?php echo $this->campoSeguro('unidadItem') ?>").val() != '' &&
                    $("#<?php echo $this->campoSeguro('IvaItem') ?>").val() != '' &&
                    $("#<?php echo $this->campoSeguro('cantidadItem') ?>").val() != '' &&
                    $("#<?php echo $this->campoSeguro('valorItem') ?>").val() != '') {


                if ($.isNumeric($("#<?php echo $this->campoSeguro('cantidadItem') ?>").val()) && $("#<?php echo $this->campoSeguro('cantidadItem') ?>").val() > 0) {

                    $("#<?php echo $this->campoSeguro('cantidadItem') ?>").css('border-color', '#DDDDDD');

                    if ($.isNumeric($("#<?php echo $this->campoSeguro('valorItem') ?>").val()) && $("#<?php echo $this->campoSeguro('valorItem') ?>").val() > 0) {

                        $("#<?php echo $this->campoSeguro('valorItem') ?>").css('border-color', '#DDDDDD');


                        //-----------------------------------------------------------------------------

                        consultarItemPushBien();

                        //-----------------------------------------------------------------------------




                    } else {

                        $("#<?php echo $this->campoSeguro('valorItem') ?>").css('border-color', '#FF0000');

                        swal({
                            title: 'Ocurrio un problema...',
                            type: 'error',
                            html:
                                    'El Contenido de <big>Valor Unitario</big>, no es Númerico. (ERROR) ',
                            confirmButtonText:
                                    'Ok'
                        })

                    }


                } else {

                    $("#<?php echo $this->campoSeguro('cantidadItem') ?>").css('border-color', '#FF0000');

                    swal({
                        title: 'Ocurrio un problema...',
                        type: 'error',
                        html:
                                'El Contenido de <big>Cantidad</big>, no es Númerico. (ERROR) ',
                        confirmButtonText:
                                'Ok'
                    })

                }


            } else {

                swal({
                    title: 'Ocurrio un problema...',
                    type: 'error',
                    html:
                            'Los Parámetros de <big>Items de Producto o Servicio</big>, ' +
                            'están mal diligenciados, No se pudieron agregar.',
                    confirmButtonText:
                            'Ok'
                })

            }


        }


        if ($("#<?php echo $this->campoSeguro('tipoItem') ?>").val() == 2) {//SERVICIO

            if ($("#<?php echo $this->campoSeguro('nombreItem') ?>").val() != '' &&
                    $("#<?php echo $this->campoSeguro('descripcionItem') ?>").val() != '' &&
                    $("#<?php echo $this->campoSeguro('tiempoItem1') ?>").val() != '' &&
                    $("#<?php echo $this->campoSeguro('tiempoItem2') ?>").val() != '' &&
                    $("#<?php echo $this->campoSeguro('tiempoItem3') ?>").val() != '' &&
                    $("#<?php echo $this->campoSeguro('IvaItem') ?>").val() != '' &&
                    $("#<?php echo $this->campoSeguro('cantidadItem') ?>").val() != '' &&
                    $("#<?php echo $this->campoSeguro('valorItem') ?>").val() != '') {


                if ($.isNumeric($("#<?php echo $this->campoSeguro('cantidadItem') ?>").val()) && $("#<?php echo $this->campoSeguro('cantidadItem') ?>").val() > 0) {

                    $("#<?php echo $this->campoSeguro('cantidadItem') ?>").css('border-color', '#DDDDDD');

                    if ($.isNumeric($("#<?php echo $this->campoSeguro('valorItem') ?>").val()) && $("#<?php echo $this->campoSeguro('valorItem') ?>").val() > 0) {

                        $("#<?php echo $this->campoSeguro('valorItem') ?>").css('border-color', '#DDDDDD');

                        if (isNormalInteger($("#<?php echo $this->campoSeguro('tiempoItem1') ?>").val())) {

                            $("#<?php echo $this->campoSeguro('tiempoItem1') ?>").css('border-color', '#DDDDDD');


                            if (isNormalInteger($("#<?php echo $this->campoSeguro('tiempoItem2') ?>").val())) {

                                $("#<?php echo $this->campoSeguro('tiempoItem2') ?>").css('border-color', '#DDDDDD');


                                if (isNormalInteger($("#<?php echo $this->campoSeguro('tiempoItem3') ?>").val())) {

                                    $("#<?php echo $this->campoSeguro('tiempoItem3') ?>").css('border-color', '#DDDDDD');



                                    var tiempo = parseInt($("#<?php echo $this->campoSeguro('tiempoItem1') ?>").val()) +
                                            parseInt($("#<?php echo $this->campoSeguro('tiempoItem2') ?>").val()) +
                                            parseInt($("#<?php echo $this->campoSeguro('tiempoItem3') ?>").val());

                                    if (tiempo > 0) {

                                        //-----------------------------------------------------------------------------

                                        consultarItemPushServicio();

                                        //-----------------------------------------------------------------------------


                                    } else {

                                        swal({
                                            title: 'Ocurrio un problema...',
                                            type: 'error',
                                            html:
                                                    'El Contenido de <big>Tiempo de Ejecución</big>, es Cero. (ERROR) ',
                                            confirmButtonText:
                                                    'Ok'
                                        })

                                    }


                                } else {

                                    $("#<?php echo $this->campoSeguro('tiempoItem3') ?>").css('border-color', '#FF0000');

                                    swal({
                                        title: 'Ocurrio un problema...',
                                        type: 'error',
                                        html:
                                                'El Contenido de <big>Días</big>, no es Númerico Entero. (ERROR) ',
                                        confirmButtonText:
                                                'Ok'
                                    })

                                }


                            } else {

                                $("#<?php echo $this->campoSeguro('tiempoItem2') ?>").css('border-color', '#FF0000');

                                swal({
                                    title: 'Ocurrio un problema...',
                                    type: 'error',
                                    html:
                                            'El Contenido de <big>Meses</big>, no es Númerico Entero. (ERROR) ',
                                    confirmButtonText:
                                            'Ok'
                                })

                            }



                        } else {

                            $("#<?php echo $this->campoSeguro('tiempoItem1') ?>").css('border-color', '#FF0000');

                            swal({
                                title: 'Ocurrio un problema...',
                                type: 'error',
                                html:
                                        'El Contenido de <big>Año</big>, no es Númerico Entero. (ERROR) ',
                                confirmButtonText:
                                        'Ok'
                            })

                        }






                    } else {

                        $("#<?php echo $this->campoSeguro('valorItem') ?>").css('border-color', '#FF0000');

                        swal({
                            title: 'Ocurrio un problema...',
                            type: 'error',
                            html:
                                    'El Contenido de <big>Valor Unitario</big>, no es Númerico. (ERROR) ',
                            confirmButtonText:
                                    'Ok'
                        })

                    }


                } else {

                    $("#<?php echo $this->campoSeguro('cantidadItem') ?>").css('border-color', '#FF0000');

                    swal({
                        title: 'Ocurrio un problema...',
                        type: 'error',
                        html:
                                'El Contenido de <big>Cantidad</big>, no es Númerico. (ERROR) ',
                        confirmButtonText:
                                'Ok'
                    })

                }




            } else {

                swal({
                    title: 'Ocurrio un problema...',
                    type: 'error',
                    html:
                            'Los Parámetros de <big>Items de Producto o Servicio</big>, ' +
                            'están mal diligenciados, No se pudieron agregar.',
                    confirmButtonText:
                            'Ok'
                })

            }


        }

    });










    var iCntIt = 0;
    var paramIt = new Array();
    var totalPrecio = 0;
     var totalPrecioIva = 0;
     var totalIva = 0;
    var fullParamIt;

    function consultarItemPushBien(elem, request, response) {
        $.ajax({
            url: "<?php echo $urlFinal29 ?>",
            dataType: "json",
            data: {valor: $("#<?php echo $this->campoSeguro('unidadItem') ?>").val()},
            success: function (data) {




                if (data[0] != "") {

                    var nFilas = $("#tablaFP tr").length;
                    var tds = 8;
                    var trs = 8;

                    paramIt.push(data[0][0]);
                    
                    var mensaje = document.getElementById("<?php echo $this->campoSeguro('IvaItem') ?>");
                    var selected = mensaje.options[mensaje.selectedIndex].text;

                    var mensaje_iva = $("#<?php echo $this->campoSeguro('IvaItem') ?>").val() + ' - ' + selected;
                    
                    
                    var totalItem=0;
                    var totalItemiva=0;
                         
                    
                    totalItem = parseFloat($("#<?php echo $this->campoSeguro('cantidadItem') ?>").val())
                            * parseFloat($("#<?php echo $this->campoSeguro('valorItem') ?>").val());
                         
                    if(selected=='Exento' || selected=='Tarifa de Cero' ){
                        totalItemiva = parseFloat($("#<?php echo $this->campoSeguro('cantidadItem') ?>").val())
                            * parseFloat($("#<?php echo $this->campoSeguro('valorItem') ?>").val());
                     
                  
                    }
                    else{
                        totalItemiva = totalItem + ((parseFloat($("#<?php echo $this->campoSeguro('cantidadItem') ?>").val())
                            * parseFloat($("#<?php echo $this->campoSeguro('valorItem') ?>").val())) *  (parseFloat(selected)/100));
                            totalIva = totalIva + ((parseFloat($("#<?php echo $this->campoSeguro('cantidadItem') ?>").val())
                            * parseFloat($("#<?php echo $this->campoSeguro('valorItem') ?>").val())) *  (parseFloat(selected)/100));
                    }
                    
            
                    totalPrecio += totalItem;
                    totalPrecioIva += totalItemiva;
                    totalIva=totalIva;
                    var count = nFilas;



                    


                    var nuevaFila = "<tr id=\"nFilas\">";
                    nuevaFila += "<td>" + ($("#<?php echo $this->campoSeguro('nombreItem') ?>").val().toUpperCase()) + "</td>";
                    nuevaFila += "<td>" + ($("#<?php echo $this->campoSeguro('descripcionItem') ?>").val().toUpperCase()) + "</td>";
                    nuevaFila += "<td>1 - BIEN</td>";
                    nuevaFila += "<td>" + (data[0][0]) + " - " + (data[0][1]) + "</td>";
                    nuevaFila += "<td>0 - NO APLICA</td>";
                    nuevaFila += "<td>" + formatearNumero(($("#<?php echo $this->campoSeguro('cantidadItem') ?>").val())) + "</td>";
                    nuevaFila += "<td>$ " + formatearNumero(($("#<?php echo $this->campoSeguro('valorItem') ?>").val())) + "</td>";
                    nuevaFila += "<td>" + (mensaje_iva) + "</td>";
                    nuevaFila += "<th class=\"eliminarFP\" scope=\"row\"><div class = \"widget\">Eliminar</div></th>";
                    nuevaFila += "</tr>";



                    $("#tablaFP").append(nuevaFila);


                    $("#<?php echo $this->campoSeguro('nombreItem') ?>").val('');
                    $("#<?php echo $this->campoSeguro('descripcionItem') ?>").val('');
                    $("#<?php echo $this->campoSeguro('cantidadItem') ?>").val('');
                    $("#<?php echo $this->campoSeguro('valorItem') ?>").val('');
                    $("#<?php echo $this->campoSeguro('tiempoItem1') ?>").val('0');
                    $("#<?php echo $this->campoSeguro('tiempoItem2') ?>").val('0');
                    $("#<?php echo $this->campoSeguro('tiempoItem3') ?>").val('0');
                    $("#<?php echo $this->campoSeguro('unidadItem') ?>").select2("val", "");
                    $("#<?php echo $this->campoSeguro('IvaItem') ?>").select2("val", -1);
                    $("#<?php echo $this->campoSeguro('tipoItem') ?>").select2("val", -1);
                    $('#parametros1').fadeOut(600);
                    $('#parametros2').fadeOut(600);
                    $('#parametros3').fadeOut(600);
                    $('#parametros4').fadeOut(600);



                    fullParamIt = "";
                    $('#tablaFP tr').each(function () {

                        /* Obtener todas las celdas */
                        var celdas = $(this).find('td');

                        /* Mostrar el valor de cada celda */
                        celdas.each(function () {
                            fullParamIt += String($(this).html()) + "&";
                        });


                    });

                    $("#<?php echo $this->campoSeguro('idsItems') ?>").val(fullParamIt);
                    $("#<?php echo $this->campoSeguro('precioCot') ?>").val("$ " + currency(totalPrecio, 0) + " pesos (COP)");
                    $("#<?php echo $this->campoSeguro('precioIva') ?>").val("$ " + currency(totalIva, 0) + " pesos (COP)");                    
                    $("#<?php echo $this->campoSeguro('precioCotIva') ?>").val("$ " + currency(totalPrecioIva, 0) + " pesos (COP)");
                    
                    

                    $("#<?php echo $this->campoSeguro('countItems') ?>").val(nFilas);

                }


            }

        });

    }
    ;


    function consultarItemPushServicio() {

        var countDays = totalDias(parseInt($("#<?php echo $this->campoSeguro('tiempoItem1') ?>").val()),
                parseInt($("#<?php echo $this->campoSeguro('tiempoItem2') ?>").val()),
                parseInt($("#<?php echo $this->campoSeguro('tiempoItem3') ?>").val()));

        var nFilas = $("#tablaFP tr").length;
        var tds = 8;
        var trs = 8;
        
        var mensaje = document.getElementById("<?php echo $this->campoSeguro('IvaItem') ?>");
                    var selected = mensaje.options[mensaje.selectedIndex].text;

                    var mensaje_iva = $("#<?php echo $this->campoSeguro('IvaItem') ?>").val() + ' - ' + selected;
         var totalItem=0;
         var totalItemiva=0;
                         
                totalItem = parseFloat($("#<?php echo $this->campoSeguro('cantidadItem') ?>").val())
                            * parseFloat($("#<?php echo $this->campoSeguro('valorItem') ?>").val());
                     
                         
                    if(selected=='Exento' || selected=='Tarifa de Cero' ){
                        totalItemiva = parseFloat($("#<?php echo $this->campoSeguro('cantidadItem') ?>").val())
                            * parseFloat($("#<?php echo $this->campoSeguro('valorItem') ?>").val());
                  
                    }
                    else{
                        totalItemiva =totalItem+( (parseFloat($("#<?php echo $this->campoSeguro('cantidadItem') ?>").val())
                            * parseFloat($("#<?php echo $this->campoSeguro('valorItem') ?>").val())) *  (parseFloat(selected)/100));
                           
                             totalIva = totalIva+ ((parseFloat($("#<?php echo $this->campoSeguro('cantidadItem') ?>").val())
                            * parseFloat($("#<?php echo $this->campoSeguro('valorItem') ?>").val())) *  (parseFloat(selected)/100));
                           
                    }

   

        totalPrecio += totalItem;
        totalPrecioIva += totalItemiva;
        totalIva=totalIva;

        var count = nFilas;


        var mensaje = document.getElementById("<?php echo $this->campoSeguro('IvaItem') ?>");
        var selected = mensaje.options[mensaje.selectedIndex].text;

        var mensaje_iva = $("#<?php echo $this->campoSeguro('IvaItem') ?>").val() + ' - ' + selected;

        var nuevaFila = "<tr id=\"nFilas\">";
        nuevaFila += "<td>" + ($("#<?php echo $this->campoSeguro('nombreItem') ?>").val().toUpperCase()) + "</td>";
        nuevaFila += "<td>" + ($("#<?php echo $this->campoSeguro('descripcionItem') ?>").val().toUpperCase()) + "</td>";
        nuevaFila += "<td>2 - SERVICIO</td>";
        nuevaFila += "<td>0 - NO APLICA</td>";
        nuevaFila += "<td>" + (inverseTotalDias(countDays)) + "</td>";
        nuevaFila += "<td>" + formatearNumero(($("#<?php echo $this->campoSeguro('cantidadItem') ?>").val())) + "</td>";
        nuevaFila += "<td> $ " + formatearNumero(($("#<?php echo $this->campoSeguro('valorItem') ?>").val())) + "</td>";
        nuevaFila += "<td>" + (mensaje_iva) + "</td>";
        nuevaFila += "<th class=\"eliminarFP\" scope=\"row\"><div class = \"widget\">Eliminar</div></th>";
        nuevaFila += "</tr>";



        $("#tablaFP").append(nuevaFila);


        $("#<?php echo $this->campoSeguro('nombreItem') ?>").val('');
        $("#<?php echo $this->campoSeguro('descripcionItem') ?>").val('');
        $("#<?php echo $this->campoSeguro('cantidadItem') ?>").val('');
        $("#<?php echo $this->campoSeguro('valorItem') ?>").val('');
        $("#<?php echo $this->campoSeguro('tiempoItem1') ?>").val('0');
        $("#<?php echo $this->campoSeguro('tiempoItem2') ?>").val('0');
        $("#<?php echo $this->campoSeguro('tiempoItem3') ?>").val('0');
        $("#<?php echo $this->campoSeguro('unidadItem') ?>").select2("val", "");
        $("#<?php echo $this->campoSeguro('IvaItem') ?>").select2("val", -1);
        $("#<?php echo $this->campoSeguro('tipoItem') ?>").select2("val", -1);
        $('#parametros1').fadeOut(600);
        $('#parametros2').fadeOut(600);
        $('#parametros3').fadeOut(600);
 
        $('#parametros4').fadeOut(600);



        fullParamIt = "";
        $('#tablaFP tr').each(function () {

            /* Obtener todas las celdas */
            var celdas = $(this).find('td');

            /* Mostrar el valor de cada celda */
            celdas.each(function () {
                fullParamIt += String($(this).html()) + "&";
            });


        });

        $("#<?php echo $this->campoSeguro('idsItems') ?>").val(fullParamIt);
        $("#<?php echo $this->campoSeguro('precioCot') ?>").val("$ " + currency(totalPrecio, 0) + " pesos (COP)");
        $("#<?php echo $this->campoSeguro('precioCotIva') ?>").val("$ " + currency(totalPrecioIva, 0) + " pesos (COP)");
        $("#<?php echo $this->campoSeguro('precioIva') ?>").val("$ " + currency(totalIva, 0) + " pesos (COP)");  
        $("#<?php echo $this->campoSeguro('countItems') ?>").val(nFilas);



    }
    ;



    /**
     
     * Funcion para eliminar la ultima columna de la tabla.
     
     * Si unicamente queda una columna, esta no sera eliminada
     
     */


    // Evento que selecciona la fila y la elimina 
    $(document).on("click", ".eliminarFP", function () {

        var parent = $(this).parents().get(0);
        var element = $(parent).text();
        var restaItemiva;
        var restaValor;

        var celdas = $(parent).find('td');

        var cantidad = String($(celdas[5]).html());
        
        var valor1 = (String($(celdas[6]).html())).replace(".","");
        valor1 = valor1.replace(",",".");
        var longvalor1 = valor1.length;
        valor = valor1.substring(2, longvalor1);
        
        var iva1 = String($(celdas[7]).html());
        var longiva1 = iva1.length;
        iva = iva1.substring(4, longiva1);
         
       
        restaValor = parseFloat(cantidad)* parseFloat(valor);
        
      
        if(iva1=='1 - Exento' || iva1=='2 - Tarifa de Cero' ){
            restaItemiva =0;

        }
        else{
            restaItemiva = restaValor + (parseFloat(cantidad)* parseFloat(valor)) *  (parseFloat(iva)/100);
            restaIva = (parseFloat(cantidad)* parseFloat(valor)) *  (parseFloat(iva)/100);
        }
        
  
        totalPrecioIva = totalPrecioIva - restaItemiva;            
        totalPrecio = totalPrecio - restaValor;
        totalIva = totalIva - restaIva;
     
   
        
        
        $(parent).remove();

        fullParamIt = "";
        $('#tablaFP tr').each(function () {

            /* Obtener todas las celdas */
            var celdas = $(this).find('td');

            /* Mostrar el valor de cada celda */
            celdas.each(function () {
                fullParamIt += String($(this).html()) + "&";
            });


        });

        $("#<?php echo $this->campoSeguro('idsItems') ?>").val(fullParamIt);
        if (totalPrecio > 0) {
            $("#<?php echo $this->campoSeguro('precioIva') ?>").val("$ " + currency(totalIva, 0) + " pesos (COP)");  
            $("#<?php echo $this->campoSeguro('precioCot') ?>").val("$ " + currency(totalPrecio, 0) + " pesos (COP)");
            $("#<?php echo $this->campoSeguro('precioCotIva') ?>").val("$ " + currency(totalPrecioIva, 0) + " pesos (COP)");
        } else {
            $("#<?php echo $this->campoSeguro('precioCot') ?>").val("");
            $("#<?php echo $this->campoSeguro('precioCotIva') ?>").val("");
            $("#<?php echo $this->campoSeguro('precioIva') ?>").val("");
        }

        var countF = $("#tablaFP tr").length - 1;

        $("#<?php echo $this->campoSeguro('countItems') ?>").val(countF);




    });




    $("#<?php echo $this->campoSeguro('cotizacionSoporte') ?>").change(function () {
        var file = $("#<?php echo $this->campoSeguro('cotizacionSoporte') ?>").val();
        var ext = file.substring(file.lastIndexOf("."));
        if (ext != ".pdf" && ext != ".xls" && ext != ".xlsx" && ext != ".doc" && ext != ".docx")
        {
            swal({
                title: 'Problema con el Archivo de Soporte',
                type: 'warning',
                html:
                        'Por favor cambie el archivo por otro en alguno de los formatos <i>(pdf, xls, xlsx, doc, docx)</i>',
                confirmButtonText:
                        'Ok'
            })
            $("#<?php echo $this->campoSeguro('cotizacionSoporte') ?>").val(null);
        }
    });
    
    
    $("#<?php echo $this->campoSeguro('documentos_elementos') ?>").change(function () {
        var file = $("#<?php echo $this->campoSeguro('documentos_elementos') ?>").val();
        var ext = file.substring(file.lastIndexOf("."));
        if (ext != ".xlsx")
        {
            swal({
                title: 'Problema con el Archivo de Elementos',
                type: 'warning',
                html:
                        'Por favor cambie el archivo por otro en formato.  <i>(xlsx)</i> recuerde que puede descargar el Archivo Plantilla adjunto y cargarlo en este campo con los elementos registrados',
                confirmButtonText:
                        'Ok'
            })
            $("#<?php echo $this->campoSeguro('documentos_elementos') ?>").val(null);
        }
    });



    if ($("#<?php echo $this->campoSeguro('precioCot') ?>").val() != null && $("#<?php echo $this->campoSeguro('precioCarga') ?>").val() > 0) {
        $("#<?php echo $this->campoSeguro('precioCot') ?>").val("$ " + currency($("#<?php echo $this->campoSeguro('precioCarga') ?>").val(), 0) + " pesos (COP)");
    }
    if ($("#<?php echo $this->campoSeguro('precioCotIva') ?>").val() != null && $("#<?php echo $this->campoSeguro('precioCargaIva') ?>").val() > 0) {
        $("#<?php echo $this->campoSeguro('precioCotIva') ?>").val("$ " + currency($("#<?php echo $this->campoSeguro('precioCargaIva') ?>").val(), 0) + " pesos (COP)");
    }
    if ($("#<?php echo $this->campoSeguro('precioTotalIva') ?>").val() != null && $("#<?php echo $this->campoSeguro('precioTotaldeIva') ?>").val() > 0) {
        $("#<?php echo $this->campoSeguro('precioTotalIva') ?>").val("$ " + currency($("#<?php echo $this->campoSeguro('precioTotaldeIva') ?>").val(), 0) + " pesos (COP)");
    }


    var fileArchivo;
    var dataArchivo;
    $("#botonCargarArchivo").click(function () {
    

        var inputFileImage = document.getElementById("<?php echo $this->campoSeguro('documentos_elementos') ?>");
       
        fileArchivo = inputFileImage.files[0];
        
        if(fileArchivo !== undefined){
            dataArchivo = new FormData();
            dataArchivo.append('file', fileArchivo);
            analizarArchivo();
        }
        else{
             swal({
                title: 'No se ha cargado ningún archivo',
                type: 'warning',
                html:
                        'Recuerde que puede descargar el Archivo Plantilla adjunto y cargarlo en este campo con los elementos registrados',
                confirmButtonText:
                        'Ok'
            }) 
        }
      
        
    });




    function analizarArchivo(elem, request, response) {

        $.ajax({
            url: "<?php echo $urlFinalArchivo ?>",
            type: "post",
            dataType: "json",
            data: dataArchivo,
            processData: false,
            contentType: false,
            success: function (data) {



                if (data[0] != " ") {



                    $.each(data, function (indice, valor) {


                        if (($("#<?php echo $this->campoSeguro('permisoItem') ?>").val() == 'bien' && data[indice].tipo.toUpperCase() == '1 - BIEN')) {
                            var nFilas = $("#tablaFP tr").length;

                            var count = nFilas;



                            var nuevaFila = "<tr id=\"nFilas\">";
                            nuevaFila += "<td>" + (data[indice].nombre.toUpperCase()) + "</td>";
                            nuevaFila += "<td>" + (data[indice].descripcion.toUpperCase()) + "</td>";
                            nuevaFila += "<td>1 - BIEN</td>";
                            nuevaFila += "<td>" + (data[indice].unidad) + "</td>";
                            nuevaFila += "<td>" + "0 - NO APLICA" + "</td>";
                            nuevaFila += "<td>" + formatearNumero(data[indice].cantidad) + "</td>";
                            nuevaFila += "<td>$ " + formatearNumero(data[indice].valor) + "</td>";
                            nuevaFila += "<td>" + (data[indice].iva) + "</td>";
                            nuevaFila += "<th class=\"eliminarFP\" scope=\"row\"><div class = \"widget\">Eliminar</div></th>";
                            nuevaFila += "</tr>";

                            $("#tablaFP").append(nuevaFila);


                            fullParamIt = "";
                            $('#tablaFP tr').each(function () {

                                /* Obtener todas las celdas */
                                var celdas = $(this).find('td');

                                /* Mostrar el valor de cada celda */
                                celdas.each(function () {
                                    fullParamIt += String($(this).html()) + "&";
                                });


                            });
                            
                             var totalItem=0;
                             var totalItemiva=0;
                         
                             totalItem=parseFloat(data[indice].cantidad)
                                    * parseFloat(data[indice].valor);
                            if(data[indice].iva=='1 - Exento' || data[indice].iva=='2 - Tarifa de Cero' ){
                                totalItemiva =parseFloat(data[indice].cantidad)
                                    * parseFloat(data[indice].valor);

                            }
                            else{
                                var cadenaiva = data[indice].iva;
                                var inicio = 4;
                                var fin    = cadenaiva.length;
                                subCadena = cadenaiva.substring(inicio, fin);
                                
                                totalItemiva = totalItem + ((parseFloat(data[indice].cantidad)
                                     * parseFloat(data[indice].valor)) *  (parseFloat(subCadena)/100));
                                     
                                totalIva = totalIva + ((parseFloat(data[indice].cantidad)
                                     * parseFloat(data[indice].valor)) *  (parseFloat(subCadena)/100));

                            }

                  
                            totalPrecio += totalItem;
                            
                      
                            totalPrecioIva += totalItemiva;
                        

                            $("#<?php echo $this->campoSeguro('idsItems') ?>").val(fullParamIt);
                            $("#<?php echo $this->campoSeguro('precioCot') ?>").val("$ " + currency(totalPrecio, 0) + " pesos (COP)");
                            $("#<?php echo $this->campoSeguro('precioCotIva') ?>").val("$ " + currency(totalPrecioIva, 0) + " pesos (COP)");
                            $("#<?php echo $this->campoSeguro('precioIva') ?>").val("$ " + currency(totalIva, 0) + " pesos (COP)");
                     

                            $("#<?php echo $this->campoSeguro('countItems') ?>").val(nFilas);

                        }


                        if (($("#<?php echo $this->campoSeguro('permisoItem') ?>").val() == 'servicio' && data[indice].tipo.toUpperCase() == '2 - SERVICIO')) {
                            var nFilas = $("#tablaFP tr").length;
                            var countDays = totalDias(parseInt(data[indice].tiempo_ejecucion_ano),
                                    parseInt(data[indice].tiempo_ejecucion_mes),
                                    parseInt(data[indice].tiempo_ejecucion_dia));

                            var count = nFilas;

                            var nuevaFila = "<tr id=\"nFilas\">";
                            nuevaFila += "<td>" + (data[indice].nombre.toUpperCase()) + "</td>";
                            nuevaFila += "<td>" + (data[indice].descripcion.toUpperCase()) + "</td>";
                            nuevaFila += "<td>2 - SERVICIO</td>";
                            nuevaFila += "<td>" + (data[indice].unidad) + "</td>";
                            nuevaFila += "<td>" + (inverseTotalDias(countDays)) + "</td>";
                            nuevaFila += "<td>" + formatearNumero(data[indice].cantidad) + "</td>";
                            nuevaFila += "<td>$ " + formatearNumero(data[indice].valor) + "</td>";
                            nuevaFila += "<td>" + (data[indice].iva) + "</td>";
                            nuevaFila += "<th class=\"eliminarFP\" scope=\"row\"><div class = \"widget\">Eliminar</div></th>";
                            nuevaFila += "</tr>";

                            $("#tablaFP").append(nuevaFila);
                            
                            fullParamIt = "";
                            $('#tablaFP tr').each(function () {

                                /* Obtener todas las celdas */
                                var celdas = $(this).find('td');

                                /* Mostrar el valor de cada celda */
                                celdas.each(function () {
                                    fullParamIt += String($(this).html()) + "&";
                                });


                            });

                              var totalItem=0;
                             var totalItemiva=0;
                         
                             totalItem=parseFloat(data[indice].cantidad)
                                    * parseFloat(data[indice].valor);
                            if(data[indice].iva=='1 - Exento' || data[indice].iva=='2 - Tarifa de Cero' ){
                                totalItemiva =parseFloat(data[indice].cantidad)
                                    * parseFloat(data[indice].valor);

                            }
                            else{
                                var cadenaiva = data[indice].iva;
                                var inicio = 4;
                                var fin    = cadenaiva.length;
                                subCadena = cadenaiva.substring(inicio, fin);
                                
                                totalItemiva = totalItem+ ((parseFloat(data[indice].cantidad)
                                     * parseFloat(data[indice].valor)) *  (parseFloat(subCadena)/100));
                                     
                                      totalIva = totalIva + ((parseFloat(data[indice].cantidad)
                                     * parseFloat(data[indice].valor)) *  (parseFloat(subCadena)/100));

                            }

                          
                            
                            totalPrecio += totalItem;
                            totalPrecioIva += totalItemiva;

                            $("#<?php echo $this->campoSeguro('idsItems') ?>").val(fullParamIt);
                            $("#<?php echo $this->campoSeguro('precioCot') ?>").val("$ " + currency(totalPrecio, 0) + " pesos (COP)");
                            $("#<?php echo $this->campoSeguro('precioCotIva') ?>").val("$ " + currency(totalPrecioIva, 0) + " pesos (COP)");
                             $("#<?php echo $this->campoSeguro('precioIva') ?>").val("$ " + currency(totalIva, 0) + " pesos (COP)");
                     

                            $("#<?php echo $this->campoSeguro('countItems') ?>").val(nFilas);

                        }

                        if (($("#<?php echo $this->campoSeguro('permisoItem') ?>").val() == 'ambos' && data[indice].tipo.toUpperCase() == '2 - SERVICIO')) {
                            var nFilas = $("#tablaFP tr").length;
                            var countDays = totalDias(parseInt(data[indice].tiempo_ejecucion_ano),
                                    parseInt(data[indice].tiempo_ejecucion_mes),
                                    parseInt(data[indice].tiempo_ejecucion_dia));

                            var count = nFilas;

                            var nuevaFila = "<tr id=\"nFilas\">";
                            nuevaFila += "<td>" + (data[indice].nombre.toUpperCase()) + "</td>";
                            nuevaFila += "<td>" + (data[indice].descripcion.toUpperCase()) + "</td>";
                            nuevaFila += "<td>2 - SERVICIO</td>";
                            nuevaFila += "<td>" + (data[indice].unidad) + "</td>";
                            nuevaFila += "<td>" + (inverseTotalDias(countDays)) + "</td>";
                            nuevaFila += "<td>" + formatearNumero(data[indice].cantidad) + "</td>";
                            nuevaFila += "<td>$ " + formatearNumero(data[indice].valor) + "</td>";
                            nuevaFila += "<td>" + (data[indice].iva) + "</td>";
                            nuevaFila += "<th class=\"eliminarFP\" scope=\"row\"><div class = \"widget\">Eliminar</div></th>";
                            nuevaFila += "</tr>";

                            $("#tablaFP").append(nuevaFila);
                            
                            fullParamIt = "";
                            $('#tablaFP tr').each(function () {

                                /* Obtener todas las celdas */
                                var celdas = $(this).find('td');

                                /* Mostrar el valor de cada celda */
                                celdas.each(function () {
                                    fullParamIt += String($(this).html()) + "&";
                                });


                            });

                               var totalItem=0;
                             var totalItemiva=0;
                         
                             totalItem=parseFloat(data[indice].cantidad)
                                    * parseFloat(data[indice].valor);
                            if(data[indice].iva=='1 - Exento' || data[indice].iva=='2 - Tarifa de Cero' ){
                                totalItemiva =parseFloat(data[indice].cantidad)
                                    * parseFloat(data[indice].valor);
                                    
                                     

                            }
                            else{
                                var cadenaiva = data[indice].iva;
                                var inicio = 4;
                                var fin    = cadenaiva.length;
                                subCadena = cadenaiva.substring(inicio, fin);
                                
                                totalItemiva = totalItem+((parseFloat(data[indice].cantidad)
                                     * parseFloat(data[indice].valor)) *  (parseFloat(subCadena)/100));
                                     
                                     
                                      totalIva = totalIva + ((parseFloat(data[indice].cantidad)
                                     * parseFloat(data[indice].valor)) *  (parseFloat(subCadena)/100));

                            }

                          
                            
                            totalPrecio += totalItem;
                            totalPrecioIva += totalItemiva;

                            $("#<?php echo $this->campoSeguro('idsItems') ?>").val(fullParamIt);
                            $("#<?php echo $this->campoSeguro('precioCot') ?>").val("$ " + currency(totalPrecio, 0) + " pesos (COP)");
                            $("#<?php echo $this->campoSeguro('precioCotIva') ?>").val("$ " + currency(totalPrecioIva, 0) + " pesos (COP)");
                            $("#<?php echo $this->campoSeguro('precioIva') ?>").val("$ " + currency(totalIva, 0) + " pesos (COP)");
                     

                            $("#<?php echo $this->campoSeguro('countItems') ?>").val(nFilas);

                        }


                        if (($("#<?php echo $this->campoSeguro('permisoItem') ?>").val() == 'ambos' && data[indice].tipo.toUpperCase() == '1 - BIEN')) {
                            var nFilas = $("#tablaFP tr").length;

                            var count = nFilas;



                            var nuevaFila = "<tr id=\"nFilas\">";
                            nuevaFila += "<td>" + (data[indice].nombre.toUpperCase()) + "</td>";
                            nuevaFila += "<td>" + (data[indice].descripcion.toUpperCase()) + "</td>";
                            nuevaFila += "<td>1 - BIEN</td>";
                            nuevaFila += "<td>" + (data[indice].unidad) + "</td>";
                            nuevaFila += "<td>" + "0 - NO APLICA" + "</td>";
                            nuevaFila += "<td>" + formatearNumero(data[indice].cantidad) + "</td>";
                            nuevaFila += "<td>$ " + formatearNumero(data[indice].valor) + "</td>";
                            nuevaFila += "<td>" + (data[indice].iva) + "</td>";
                            nuevaFila += "<th class=\"eliminarFP\" scope=\"row\"><div class = \"widget\">Eliminar</div></th>";
                            nuevaFila += "</tr>";

                            $("#tablaFP").append(nuevaFila);
                            
                            fullParamIt = "";
                            $('#tablaFP tr').each(function () {

                                /* Obtener todas las celdas */
                                var celdas = $(this).find('td');

                                /* Mostrar el valor de cada celda */
                                celdas.each(function () {
                                    fullParamIt += String($(this).html()) + "&";
                                });


                            });

                               var totalItem=0;
                             var totalItemiva=0;
                         
                             totalItem=parseFloat(data[indice].cantidad)
                                    * parseFloat(data[indice].valor);
                            if(data[indice].iva=='1 - Exento' || data[indice].iva=='2 - Tarifa de Cero' ){
                                    
                            
                                    totalItemiva =parseFloat(data[indice].cantidad)
                                    * parseFloat(data[indice].valor);
                                    
                                    

                            }
                            else{
                                var cadenaiva = data[indice].iva;
                                var inicio = 4;
                                var fin    = cadenaiva.length;
                                subCadena = cadenaiva.substring(inicio, fin);
                                
                                totalItemiva = totalItem+(parseFloat(data[indice].cantidad)
                                     * parseFloat(data[indice].valor)) *  (parseFloat(subCadena)/100);
                                     
                                  totalIva = totalIva + ((parseFloat(data[indice].cantidad)
                                     * parseFloat(data[indice].valor)) *  (parseFloat(subCadena)/100));
                                     

                            }

                          
                            
                            totalPrecio += totalItem;
                            totalPrecioIva += totalItemiva;

                            $("#<?php echo $this->campoSeguro('idsItems') ?>").val(fullParamIt);
                            $("#<?php echo $this->campoSeguro('precioCot') ?>").val("$ " + currency(totalPrecio, 0) + " pesos (COP)");
                            $("#<?php echo $this->campoSeguro('precioCotIva') ?>").val("$ " + currency(totalPrecioIva, 0) + " pesos (COP)");
                             $("#<?php echo $this->campoSeguro('precioIva') ?>").val("$ " + currency(totalIva, 0) + " pesos (COP)");
                     

                            $("#<?php echo $this->campoSeguro('countItems') ?>").val(nFilas);

                        }







                    });




                } else {

                    if (data != '') {
                        swal({
                            title: 'Ocurrio un problema...',
                            type: 'error',
                            html:
                                    'Los Datos Registrados en el Archivo de Carga se Encuentran Erroneos. Revisar la celda correspondiente : <big> ' + data + ' . </big>, ' +
                                    'Puede Verificar la Plantilla en la Pestaña GUIA para el correcto formato de los datos.',
                            confirmButtonText:
                                    'Ok'

                        })
                    } else {
                        swal({
                            title: 'Ocurrio un problema...',
                            type: 'error',
                            html:
                                    'Se ha Presentado un Error en la Carga del Archivo,  ' +
                                    'Puede Verificar la Plantilla en la Pestaña GUIA para el correcto formato de los datos.',
                            confirmButtonText:
                                    'Ok'

                        })


                    }


                }

            }
        });
    }
    ;

    
    
    $("#botonAgregarInfo").click(function () {

	var tope = $('#<?php echo $this->campoSeguro('tope_contratacion') ?>').val();
    
                            var nFilas = $("#tablaFP2 tr").length;

                            var count = nFilas;
                            
                            
                            var mytable = document.getElementById('tablaFP2');
                            var myinputs = mytable.getElementsByTagName('input');
                            var myselects = mytable.getElementsByTagName('select');
                            var mytextareas = mytable.getElementsByTagName('textarea');
                            
                            var validacionRegistros = 0;
                            
                            for (var i = 0; i < myinputs.length; i++) {
                                
                                if(mytextareas[i].value == ''){
                                            swal({
                                                title: 'Ocurrio un problema...',
                                                type: 'error',
                                                html:
                                                        'Por favor diligencie Todos los campos, <br> ' +
                                                        'Verifique el campo <b>Descripción Producto Ofrecido </b>en el elemento : <br><br> <b>' + (i+1) +'</b>',
                                                confirmButtonText:
                                                        'Ok'

                                            })
                                            
                                         validacionRegistros =1   
                                }
                                if(myselects[i].value == ''){
                                            swal({
                                                title: 'Ocurrio un problema...',
                                                type: 'error',
                                                html:
                                                        'Por favor diligencie Todos los campos, <br> ' +
                                                        'Verifique el campo <b>Iva</b> en el elemento : <br><br> <b>' + (i+1) + '</b>',
                                                confirmButtonText:
                                                        'Ok'

                                            })
                                            
                                         validacionRegistros =1   
                                }
                                if(myinputs[i].value == ''){
                                            swal({
                                                title: 'Ocurrio un problema...',
                                                type: 'error',
                                                html:
                                                        'Por favor diligencie Todos los campos, <br> ' +
                                                        'Verifique el campo <b>Valor Unitario</b> en el elemento : <br><br> <b>' + (i+1) + '</b>',
                                                confirmButtonText:
                                                        'Ok'

                                            })
                                          validacionRegistros = 1;  
                                            
                                }
                                else{
                                
                                
                                     if($.isNumeric(myinputs[i].value)){
                                            var valor = parseFloat(myinputs[i].value);
                                            var valor_final=formatearNumero(valor);

                                            
                                            
                                            
                                    }else{
                                    
                                            swal({
                                                title: 'Ocurrio un problema...',
                                                type: 'error',
                                                html:
                                                        'Por favor verifique el tipo de Dato,  ' +
                                                        'Verifique el campo <b>Valor Unitario</b> en el elemento : <br><br>  <b>' + (i+1) + '</b>',
                                                confirmButtonText:
                                                        'Ok'

                                            })
                                          validacionRegistros = 1;  
                                    
                                    
                                    }
                                
                                
                                       
                                    
                       
                                
                                }
                                
                                 
                                
                                if(validacionRegistros == 1 ){
                                         break;
                                }
                               
                                
                                
                            }
                            
                            if(validacionRegistros==0){
                            
                                var nFilas = $("#tablaFP2 tr").length;
                               
                                fullParamId = "";
                                var cotizacion_total = 0;
                                var iva_total = 0;
                                var iva_cotizacion_total = 0;
                                
                                var arregloValor=[];
                                $('#tablaFP2 tr').each(function () {

                                            /* Obtener todas las celdas */
                                            var celdas = $(this).find('td');

                                           
                                            fullParamId += String($(celdas[0]).html()) + "&";
                                            
                                            arregloValor.push(String($(celdas[7]).html()));
                                            
                                            


                                });
                              
                                     
                                
                                
                                var myinputs = mytable.getElementsByTagName('input');
                                var myselects = mytable.getElementsByTagName('select');
                                var mytextareas = mytable.getElementsByTagName('textarea');
                                
                                fullParamProv = "";
                                
                              
                                
                                for (var i = 0; i < myinputs.length; i++) {
                                    fullParamProv += formatearNumero(myinputs[i].value) + "&";
                                    fullParamProv += myselects[i].value + "&";
                                    fullParamProv += mytextareas[i].value + "&";
                                
                                     var combo = document.getElementById("producto");
 
                                 
                                    
                                    if(myselects[i].value =='Exento' || myselects[i].value=='Tarifa de Cero' ){
                                            
                                           var valor_item=myinputs[i].value;
                                           var cantidad= parseFloat(arregloValor[i+1]);
                               
                                           cotizacion_total+=cantidad * parseFloat(valor_item);
                                           iva_cotizacion_total+=(cantidad * parseFloat(valor_item));
                                        
                                          
                                      
                                    }
                                    else{
                                        
                                        
                                        var valor_item=myinputs[i].value;
                                        var cantidad= parseFloat(arregloValor[i+1]);
                                        
                                        
                                        
                                        cotizacion_total+=(cantidad * parseFloat(valor_item));
                                                                        
                                        iva_cotizacion_total+= (cantidad * parseFloat(valor_item)) + ((cantidad * parseFloat(valor_item)) * (parseFloat(myselects[i].value)/100));
                                        
                                        iva_total += (cantidad * parseFloat(valor_item)) * (parseFloat(myselects[i].value)/100);
                                        
                                        
                                      
                                    
                                    }
                                    
                                    
                                
                                }
                                
								var limiteCon = parseFloat(tope);
								var total = parseFloat(cotizacion_total);
								if(total > limiteCon){
									
									swal({
                                                title: 'Atención',
                                                type: 'warning',
                                                html:
                                                		'Registros Ingresados...  <br>Cálculo realizado correctamente.<br><br>' +
                                                		'<b>IMPORTANTE</b> <br>' +
                                                        'El Precio de la Cotización excede el Limite del Presupuesto destinado ' +
                                                        'para la cotización, por favor verifique los valores ingresados, las ' +
                                                        'cotizaciones con valores superiores al limite presupuestal no serán tenidas ' +
                                                        'en cuenta <br><br>' + 'LIMITE CONTRATACIÓN: '+ '<br>$ ' + currency(limiteCon, 0) + ' pesos (COP)' +
                                                        '<br><br>' + 'SU PRECIO DE COTIZACIÓN ES: '+ '<br>$ ' + currency(total, 0) + ' pesos (COP)',
                                                confirmButtonText:
                                                        'Ok'

                                            })
									
								}else{
									
									
									 swal({
                                                title: 'Registros Ingresados...',
                                                type: 'success',
                                                html:
                                                        'Cálculo realizado correctamente.  ' ,
                                                confirmButtonText:
                                                        'Ok'

                                            })
									
									
								}
                                
                                var nFilas = $("#tablaFP2 tr").length;
                                
                                
                                 $("#<?php echo $this->campoSeguro('idsItems') ?>").val(fullParamId);   
                                 $("#<?php echo $this->campoSeguro('idsItemsProv') ?>").val(fullParamProv); 
                                 $("#<?php echo $this->campoSeguro('countItems') ?>").val(nFilas);
                                 
                                 $("#<?php echo $this->campoSeguro('precioCot') ?>").val("$ " + currency(cotizacion_total, 0) + " pesos (COP)");
                                 $("#<?php echo $this->campoSeguro('precioCotIva') ?>").val("$ " + currency(iva_cotizacion_total, 0) + " pesos (COP)");
                                 $("#<?php echo $this->campoSeguro('precioIva') ?>").val("$ " + currency(iva_total, 0) + " pesos (COP)");
                            
                                  
                            
                               
                                            
                                            
                                 
                                            
                                            
                                            
                                            
                            
                            }
                            
                          
                           
                            
                            
                            


     });
     
   
   
   $("#botonesRegCot").hover(function() {
					  alertCriterioReg();
					  $(this).unbind('mouseenter mouseleave');
	});
   
   function alertCriterioReg() {
    	
    	swal({
			                title: 'Importante <br>DESCUENTOS',
			                type: 'warning',
			                html:
			                        'Nos permitimos solicitarle tenga en cuenta los descuentos que realiza la Universidad los cuales se calculan antes de IVA, así:<br><br><b>'
									+'- Estampilla UD (1%)<br>'
									+'- Adulto Mayor (2%)<br>'
									+'- Pro Cultura (0.5%)<br><br></b>'
			                        +'Por favor, tenga presente esta información. Gracias',
			                confirmButtonText:
			                        'Aceptar'
			            })
    	
    }
   
