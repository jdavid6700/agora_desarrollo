<?php
/**
 *
 * Los datos del bloque se encuentran en el arreglo $esteBloque.
 */

// URL base
$url = $this->miConfigurador->getVariableConfiguracion ( "host" );
$url .= $this->miConfigurador->getVariableConfiguracion ( "site" );
$url .= "/index.php?";
// Variables
$cadenaACodificarDocente = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( "pagina" );
$cadenaACodificarDocente .= "&procesarAjax=true";
$cadenaACodificarDocente .= "&action=index.php";
$cadenaACodificarDocente .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificarDocente .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificarDocente .= "&funcion=consultarDocente";
$cadenaACodificarDocente .= "&tiempo=" . $_REQUEST ['tiempo'];

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion ( "enlace" );
$cadena = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $cadenaACodificarDocente, $enlace );

// URL definitiva
$urlFinalDocente = $url . $cadena;



$cadenaACodificarProveedorFiltro = "pagina=" . $this->miConfigurador->getVariableConfiguracion("pagina");
$cadenaACodificarProveedorFiltro .= "&procesarAjax=true";
$cadenaACodificarProveedorFiltro .= "&action=index.php";
$cadenaACodificarProveedorFiltro .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificarProveedorFiltro .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificarProveedorFiltro .= $cadenaACodificarProveedorFiltro . "&funcion=consultarProveedorFiltro";
$cadenaACodificarProveedorFiltro .= "&tiempo=" . $_REQUEST ['tiempo'];
// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");
$cadenaACodificarProveedorFiltro = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificarProveedorFiltro, $enlace);
// URL definitiva
$urlProveedorFiltro = $url . $cadenaACodificarProveedorFiltro;

?>

$("#<?php echo $this->campoSeguro('nit_proveedor') ?>").keyup(function () {
    $('#<?php echo $this->campoSeguro('nit_proveedor') ?>').val($('#<?php echo $this->campoSeguro('nit_proveedor') ?>').val());
});
$("#<?php echo $this->campoSeguro('nit_proveedor') ?>").autocomplete({
    minChars: 3,
    serviceUrl: '<?php echo $urlProveedorFiltro; ?>',
    onSelect: function (suggestions) {
        $("#<?php echo $this->campoSeguro('id_proveedor') ?>").val(suggestions.data);
    }
});


////////////////////////Seleccion Procedencia Formulario Adicional//////////////////////////////////////////
$( "#<?php echo $this->campoSeguro('paisEmpresa')?>" ).change(function() {
	if($('#<?php echo $this->campoSeguro('paisEmpresa') ?>').val() == 2){
		$("#marcoProcedencia").show("slow");
	}else {
		$("#marcoProcedencia").hide("slow");
	}
});

$( "#<?php echo $this->campoSeguro('tipoPersona')?>" ).change(function() {
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

$( "#<?php echo $this->campoSeguro('tipoIdentifiExtranjera')?>" ).change(function() {
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

$( "#<?php echo $this->campoSeguro('perfil')?>" ).change(function() {
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

$( "#<?php echo $this->campoSeguro('perfilNat')?>" ).change(function() {
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

$( "#<?php echo $this->campoSeguro('personasCargo')?>" ).change(function() {
	if($('#<?php echo $this->campoSeguro('personasCargo') ?>').val() == 1){
		$("#obligatorioCantidadPersonasACargo").show("fast");
		$("#marcoDetalleDependientes").show("fast");//AGREGADO Tributario
	}else{
		$("#obligatorioCantidadPersonasACargo").hide("fast");
		$("#marcoDetalleDependientes").hide("fast");//AGREGADO Tributario
	}
});

$( "#<?php echo $this->campoSeguro('discapacidad')?>" ).change(function() {
	if($('#<?php echo $this->campoSeguro('discapacidad') ?>').val() == 1){
		$("#obligatorioTipoDiscapacidad").show("fast");
	}else{
		$("#obligatorioTipoDiscapacidad").hide("fast");
	}
});

$( "#<?php echo $this->campoSeguro('medicinaPrepagadaNat')?>" ).change(function() {//AGREGADO Tributario
	if($('#<?php echo $this->campoSeguro('medicinaPrepagadaNat') ?>').val() == 1){
		$("#obligatorioNumeroUVT").show("fast");
	}else{
		$("#obligatorioNumeroUVT").hide("fast");
	}
});

$( "#<?php echo $this->campoSeguro('cuentaAFCNat')?>" ).change(function() {//AGREGADO Tributario
	if($('#<?php echo $this->campoSeguro('cuentaAFCNat') ?>').val() == 1){
		$("#obligatorioDatosAFC").show("fast");
	}else{
		$("#obligatorioDatosAFC").hide("fast");
	}
});

///////////////////////////////////////////////////////////////////////////////////////////////////////////


//////////////////Función que se ejecuta al seleccionar alguna opción del contexto de la Entidad////////////////////

$("#<?php echo $this->campoSeguro('divisionCIIU')?>").change(function() {

	if($("#<?php echo $this->campoSeguro('divisionCIIU')?>").val() == ''){

		$("<option value=''>Seleccione .....</option>").appendTo("#<?php echo $this->campoSeguro('grupoCIIU')?>");
		
		$("#<?php echo $this->campoSeguro('grupoCIIU_div')?>").css('display','none');
		 		
	}else{

		$("#<?php echo $this->campoSeguro('grupoCIIU')?>").html("");
		$("<option value=''>Seleccione .....</option>").appendTo("#<?php echo $this->campoSeguro('grupoCIIU')?>");
		consultarCiudad();
		
		$("#<?php echo $this->campoSeguro('grupoCIIU_div')?>").css('display','block'); 
		
		$("#<?php echo $this->campoSeguro('grupoCIIU')?>").select2();
		
	}
	
});

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////

///////////////Función que se encarga de hacer dinámico el campo país////////////////  

$("#<?php echo $this->campoSeguro('grupoCIIU')?>").change(function() {

	if($("#<?php echo $this->campoSeguro('grupoCIIU')?>").val() == ''){

		$("<option value=''>Seleccione .....</option>").appendTo("#<?php echo $this->campoSeguro('claseCIIU')?>");
		
		$("#<?php echo $this->campoSeguro('claseCIIU_div')?>").css('display','none');
		 		
	}else{

		$("#<?php echo $this->campoSeguro('claseCIIU')?>").html("");
		$("<option value=''>Seleccione .....</option>").appendTo("#<?php echo $this->campoSeguro('claseCIIU')?>");
		consultarClase();
		
		$("#<?php echo $this->campoSeguro('claseCIIU_div')?>").css('display','block'); 
		
		$("#<?php echo $this->campoSeguro('claseCIIU')?>").select2();
		
	}
	
});

<?php

$url = $this->miConfigurador->getVariableConfiguracion ( "host" );
$url .= $this->miConfigurador->getVariableConfiguracion ( "site" );
$url .= "/index.php?";

// Variables
$cadenaACodificarPais = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( "pagina" );
$cadenaACodificarPais .= "&procesarAjax=true";
$cadenaACodificarPais .= "&action=index.php";
$cadenaACodificarPais .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificarPais .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificarPais .= "&funcion=consultarPais";
$cadenaACodificarPais .= "&tiempo=" . $_REQUEST ['tiempo'];

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion ( "enlace" );
$cadena = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $cadenaACodificarPais, $enlace );

// URL definitiva
$urlFinalPais = $url . $cadena;

?>

function consultarPais(elem, request, response){
	$.ajax({
		url: "<?php echo $urlFinalPais?>",
		dataType: "json",
		data: { valor:$("#<?php echo $this->campoSeguro('contexto')?>").val()},
		success: function(data){
			if(data[0]!=" "){
				$("#<?php echo $this->campoSeguro('pais')?>").html('');
				$("<option value=''>Seleccione .....</option>").appendTo("#<?php echo $this->campoSeguro('pais')?>");
				$.each(data , function(indice,valor){
					$("<option value='"+data[ indice ].paiscodigo+"'>"+data[ indice ].paisnombre+"</option>").appendTo("#<?php echo $this->campoSeguro('pais')?>");
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
$cadenaACodificarClase = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( "pagina" );
$cadenaACodificarClase .= "&procesarAjax=true";
$cadenaACodificarClase .= "&action=index.php";
$cadenaACodificarClase .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificarClase .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificarClase .= "&funcion=consultarClase";
$cadenaACodificarClase .= "&tiempo=" . $_REQUEST ['tiempo'];
// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion ( "enlace" );
$cadena = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $cadenaACodificarClase, $enlace );

// URL definitiva
$urlFinalClase = $url . $cadena;
?>

function consultarClase(elem, request, response){
	$.ajax({
		url: "<?php echo $urlFinalClase?>",
		dataType: "json",
		data: { valor:$("#<?php echo $this->campoSeguro('grupoCIIU')?>").val()},
		success: function(data){
			if(data[0]!=" "){
				$("#<?php echo $this->campoSeguro('claseCIIU')?>").html("");
				$("<option value=''>Seleccione .....</option>").appendTo("#<?php echo $this->campoSeguro('claseCIIU')?>");
				$.each(data , function(indice,valor){
					$("<option value='"+data[ indice ].id_subclase+"'>"+data[ indice ].nombre+"</option>").appendTo("#<?php echo $this->campoSeguro('claseCIIU')?>");
				});
			}
		}
	});
};

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


///////////////Función que se encarga de hacer dinámico el campo categoría////////////////  
<?php

$url = $this->miConfigurador->getVariableConfiguracion ( "host" );
$url .= $this->miConfigurador->getVariableConfiguracion ( "site" );
$url .= "/index.php?";

// Variables
$cadenaACodificarCiudad = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( "pagina" );
$cadenaACodificarCiudad .= "&procesarAjax=true";
$cadenaACodificarCiudad .= "&action=index.php";
$cadenaACodificarCiudad .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificarCiudad .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificarCiudad .= "&funcion=consultarCiudad";
$cadenaACodificarCiudad .= "&tiempo=" . $_REQUEST ['tiempo'];
// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion ( "enlace" );
$cadena = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $cadenaACodificarCiudad, $enlace );
// URL definitiva
$urlFinalCiudad = $url . $cadena;



// URL base
$url = $this->miConfigurador->getVariableConfiguracion ( "host" );
$url .= $this->miConfigurador->getVariableConfiguracion ( "site" );
$url .= "/index.php?";

//Variables
$cadenaACodificar16 = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( "pagina" );
$cadenaACodificar16 .= "&procesarAjax=true";
$cadenaACodificar16 .= "&action=index.php";
$cadenaACodificar16 .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificar16 .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificar16 .= $cadenaACodificar16 . "&funcion=consultarDepartamentoAjax";
$cadenaACodificar16 .= "&tiempo=" . $_REQUEST ['tiempo'];

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion ( "enlace" );

$cadena16 = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $cadenaACodificar16, $enlace );

// URL definitiva
$urlFinal16 = $url . $cadena16;
//echo $urlFinal16; exit;


// URL base
$url = $this->miConfigurador->getVariableConfiguracion ( "host" );
$url .= $this->miConfigurador->getVariableConfiguracion ( "site" );
$url .= "/index.php?";

//Variables
$cadenaACodificar17 = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( "pagina" );
$cadenaACodificar17 .= "&procesarAjax=true";
$cadenaACodificar17 .= "&action=index.php";
$cadenaACodificar17 .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificar17 .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificar17 .= $cadenaACodificar17 . "&funcion=consultarCiudadAjax";
$cadenaACodificar17 .= "&tiempo=" . $_REQUEST ['tiempo'];

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion ( "enlace" );

$cadena17 = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $cadenaACodificar17, $enlace );

// URL definitiva
$urlFinal17 = $url . $cadena17;
//echo $urlFinal16; exit;


// URL base
$url = $this->miConfigurador->getVariableConfiguracion ( "host" );
$url .= $this->miConfigurador->getVariableConfiguracion ( "site" );
$url .= "/index.php?";

//Variables
$cadenaACodificar18 = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( "pagina" );
$cadenaACodificar18 .= "&procesarAjax=true";
$cadenaACodificar18 .= "&action=index.php";
$cadenaACodificar18 .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificar18 .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificar18 .= $cadenaACodificar18 . "&funcion=consultarCiudadAjax";
$cadenaACodificar18 .= "&tiempo=" . $_REQUEST ['tiempo'];

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion ( "enlace" );

$cadena18 = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $cadenaACodificar18, $enlace );

// URL definitiva
$urlFinal18 = $url . $cadena18;
//echo $urlFinal16; exit;



// URL base
$url = $this->miConfigurador->getVariableConfiguracion ( "host" );
$url .= $this->miConfigurador->getVariableConfiguracion ( "site" );
$url .= "/index.php?";

//Variables
$cadenaACodificar19 = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( "pagina" );
$cadenaACodificar19 .= "&procesarAjax=true";
$cadenaACodificar19 .= "&action=index.php";
$cadenaACodificar19 .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificar19 .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificar19 .= $cadenaACodificar19 . "&funcion=consultarPaisAjax";
$cadenaACodificar19 .= "&tiempo=" . $_REQUEST ['tiempo'];

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion ( "enlace" );

$cadena19 = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $cadenaACodificar19, $enlace );

// URL definitiva
$urlFinal19 = $url . $cadena19;
//echo $urlFinal16; exit;


// URL base
$url = $this->miConfigurador->getVariableConfiguracion ( "host" );
$url .= $this->miConfigurador->getVariableConfiguracion ( "site" );
$url .= "/index.php?";

//Variables
$cadenaACodificar20 = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( "pagina" );
$cadenaACodificar20 .= "&procesarAjax=true";
$cadenaACodificar20 .= "&action=index.php";
$cadenaACodificar20 .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificar20 .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificar20 .= $cadenaACodificar20 . "&funcion=consultarCiudadAjax";
$cadenaACodificar20 .= "&tiempo=" . $_REQUEST ['tiempo'];

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion ( "enlace" );

$cadena20 = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $cadenaACodificar20, $enlace );

// URL definitiva
$urlFinal20 = $url . $cadena20;
//echo $urlFinal16; exit;


// URL base
$url = $this->miConfigurador->getVariableConfiguracion ( "host" );
$url .= $this->miConfigurador->getVariableConfiguracion ( "site" );
$url .= "/index.php?";

//Variables
$cadenaACodificar21 = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( "pagina" );
$cadenaACodificar21 .= "&procesarAjax=true";
$cadenaACodificar21 .= "&action=index.php";
$cadenaACodificar21 .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificar21 .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificar21 .= $cadenaACodificar21 . "&funcion=consultarNomenclatura";
$cadenaACodificar21 .= "&tiempo=" . $_REQUEST ['tiempo'];

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion ( "enlace" );

$cadena21 = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $cadenaACodificar21, $enlace );

// URL definitiva
$urlFinal21 = $url . $cadena21;
//echo $urlFinal16; exit;


// URL base
$url = $this->miConfigurador->getVariableConfiguracion ( "host" );
$url .= $this->miConfigurador->getVariableConfiguracion ( "site" );
$url .= "/index.php?";

//Variables
$cadenaACodificar22 = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( "pagina" );
$cadenaACodificar22 .= "&procesarAjax=true";
$cadenaACodificar22 .= "&action=index.php";
$cadenaACodificar22 .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificar22 .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificar22 .= $cadenaACodificar22 . "&funcion=consultarNomenclatura";
$cadenaACodificar22 .= "&tiempo=" . $_REQUEST ['tiempo'];

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion ( "enlace" );

$cadena22 = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $cadenaACodificar22, $enlace );

// URL definitiva
$urlFinal22 = $url . $cadena22;
//echo $urlFinal16; exit;



// URL base
$url = $this->miConfigurador->getVariableConfiguracion ( "host" );
$url .= $this->miConfigurador->getVariableConfiguracion ( "site" );
$url .= "/index.php?";

//Variables
$cadenaACodificar23 = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( "pagina" );
$cadenaACodificar23 .= "&procesarAjax=true";
$cadenaACodificar23 .= "&action=index.php";
$cadenaACodificar23 .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificar23 .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificar23 .= $cadenaACodificar23 . "&funcion=consultarNBC";
$cadenaACodificar23 .= "&tiempo=" . $_REQUEST ['tiempo'];

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion ( "enlace" );

$cadena23 = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $cadenaACodificar23, $enlace );

// URL definitiva
$urlFinal23 = $url . $cadena23;
//echo $urlFinal16; exit;




// URL base
$url = $this->miConfigurador->getVariableConfiguracion ( "host" );
$url .= $this->miConfigurador->getVariableConfiguracion ( "site" );
$url .= "/index.php?";

//Variables
$cadenaACodificar24 = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( "pagina" );
$cadenaACodificar24 .= "&procesarAjax=true";
$cadenaACodificar24 .= "&action=index.php";
$cadenaACodificar24 .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificar24 .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificar24 .= $cadenaACodificar24 . "&funcion=consultarNBC";
$cadenaACodificar24 .= "&tiempo=" . $_REQUEST ['tiempo'];

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion ( "enlace" );

$cadena24 = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $cadenaACodificar24, $enlace );

// URL definitiva
$urlFinal24 = $url . $cadena24;
//echo $urlFinal16; exit;






// URL base
$url = $this->miConfigurador->getVariableConfiguracion ( "host" );
$url .= $this->miConfigurador->getVariableConfiguracion ( "site" );
$url .= "/index.php?";

//Variables
$cadenaACodificar25 = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( "pagina" );
$cadenaACodificar25 .= "&procesarAjax=true";
$cadenaACodificar25 .= "&action=index.php";
$cadenaACodificar25 .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificar25 .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificar25 .= $cadenaACodificar25 . "&funcion=consultarDepartamentoAjax";
$cadenaACodificar25 .= "&tiempo=" . $_REQUEST ['tiempo'];

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion ( "enlace" );

$cadena25 = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $cadenaACodificar25, $enlace );

// URL definitiva
$urlFinal25 = $url . $cadena25;
//echo $urlFinal16; exit;


// URL base
$url = $this->miConfigurador->getVariableConfiguracion ( "host" );
$url .= $this->miConfigurador->getVariableConfiguracion ( "site" );
$url .= "/index.php?";

//Variables
$cadenaACodificar26 = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( "pagina" );
$cadenaACodificar26 .= "&procesarAjax=true";
$cadenaACodificar26 .= "&action=index.php";
$cadenaACodificar26 .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificar26 .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificar26 .= $cadenaACodificar26 . "&funcion=consultarCiudadAjax";
$cadenaACodificar26 .= "&tiempo=" . $_REQUEST ['tiempo'];

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion ( "enlace" );

$cadena26 = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $cadenaACodificar26, $enlace );

// URL definitiva
$urlFinal26 = $url . $cadena26;
//echo $urlFinal16; exit;







// URL base
$url = $this->miConfigurador->getVariableConfiguracion ( "host" );
$url .= $this->miConfigurador->getVariableConfiguracion ( "site" );
$url .= "/index.php?";

//Variables
$cadenaACodificar27 = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( "pagina" );
$cadenaACodificar27 .= "&procesarAjax=true";
$cadenaACodificar27 .= "&action=index.php";
$cadenaACodificar27 .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificar27 .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificar27 .= $cadenaACodificar27 . "&funcion=consultarDepartamentoAjax";
$cadenaACodificar27 .= "&tiempo=" . $_REQUEST ['tiempo'];

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion ( "enlace" );

$cadena27 = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $cadenaACodificar27, $enlace );

// URL definitiva
$urlFinal27 = $url . $cadena27;
//echo $urlFinal16; exit;


// URL base
$url = $this->miConfigurador->getVariableConfiguracion ( "host" );
$url .= $this->miConfigurador->getVariableConfiguracion ( "site" );
$url .= "/index.php?";

//Variables
$cadenaACodificar28 = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( "pagina" );
$cadenaACodificar28 .= "&procesarAjax=true";
$cadenaACodificar28 .= "&action=index.php";
$cadenaACodificar28 .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificar28 .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificar28 .= $cadenaACodificar28 . "&funcion=consultarCiudadAjax";
$cadenaACodificar28 .= "&tiempo=" . $_REQUEST ['tiempo'];

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion ( "enlace" );

$cadena28 = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $cadenaACodificar28, $enlace );

// URL definitiva
$urlFinal28 = $url . $cadena28;
//echo $urlFinal16; exit;



?>

function consultarCiudad(elem, request, response){
	$.ajax({
		url: "<?php echo $urlFinalCiudad?>",
		dataType: "json",
		data: { valor:$("#<?php echo $this->campoSeguro('divisionCIIU')?>").val()},
		success: function(data){
			if(data[0]!=" "){
				$("#<?php echo $this->campoSeguro('grupoCIIU')?>").html("");
				$("<option value=''>Seleccione .....</option>").appendTo("#<?php echo $this->campoSeguro('grupoCIIU')?>");
				$.each(data , function(indice,valor){
					$("<option value='"+data[ indice ].id_clase+"'>"+data[ indice ].nombre+"</option>").appendTo("#<?php echo $this->campoSeguro('grupoCIIU')?>");
				});
			}
		}
	});
};
///////////////////////////////////////////////////////////////////////////////////// 

function hora(){  
    var hora=fecha.getHours();
    var minutos=fecha.getMinutes();
    var segundos=fecha.getSeconds();
    if(hora<10){ hora='0'+hora;}
    if(minutos<10){minutos='0'+minutos; }
    if(segundos<10){ segundos='0'+segundos; }     
    fecha.setSeconds(fecha.getSeconds()+1);
    var fech = "<b>Fecha: " + fecha.getFullYear() + "/" + (fecha.getMonth() + 1) + "/" + fecha.getDate() + " <br> Hora: " + hora +":"+minutos+":"+segundos + "</b>";       
    
    $('#<?php echo ('bannerReloj') ?>').text( "Hora: " + hora + ":" + minutos + ":" + segundos );
    setTimeout("hora()",1000);
}

fecha = new Date(); 
hora();



////////////////////////////////////////////////////////////////////////////



function consultarDepartamentoExp(elem, request, response){
	  $.ajax({
	    url: "<?php echo $urlFinal25?>",
	    dataType: "json",
	    data: { valor:$("#<?php echo $this->campoSeguro('paisExpeNat')?>").val()},
	    success: function(data){
	        if(data[0]!=" "){
	            $("#<?php echo $this->campoSeguro('departamentoExpeNat')?>").html('');
	            $("<option value=''>Seleccione  ....</option>").appendTo("#<?php echo $this->campoSeguro('departamentoExpeNat')?>");
	            $.each(data , function(indice,valor){
	            	$("<option value='"+data[ indice ].id_departamento+"'>"+data[ indice ].nombre+"</option>").appendTo("#<?php echo $this->campoSeguro('departamentoExpeNat')?>");
	            	
	            });
	            
	            $("#<?php echo $this->campoSeguro('departamentoExpeNat')?>").removeAttr('disabled');
	            
	            //$('#<?php echo $this->campoSeguro('departamentoExpeNat')?>').width(250);
	            $("#<?php echo $this->campoSeguro('departamentoExpeNat')?>").select2();
	            
	            $("#<?php echo $this->campoSeguro('departamentoExpeNat')?>").removeClass("validate[required]");
	            $("#<?php echo $this->campoSeguro('paisExpeNat')?>").removeClass("validate[required]");
	            
		    }
		    
	  			
	    }
		                    
	   });
	};
	
	
	
		function consultarCiudadExp(elem, request, response){
		  $.ajax({
		    url: "<?php echo $urlFinal26?>",
		    dataType: "json",
		    data: { valor:$("#<?php echo $this->campoSeguro('departamentoExpeNat')?>").val()},
		    success: function(data){ 
		        if(data[0]!=" "){
		            $("#<?php echo $this->campoSeguro('ciudadExpeNat')?>").html('');
		            $("<option value=''>Seleccione  ....</option>").appendTo("#<?php echo $this->campoSeguro('ciudadExpeNat')?>");
		            $.each(data , function(indice,valor){
		            	$("<option value='"+data[ indice ].id_ciudad+"'>"+data[ indice ].nombreciudad+"</option>").appendTo("#<?php echo $this->campoSeguro('ciudadExpeNat')?>");
		            	
		            });
		            
		            $("#<?php echo $this->campoSeguro('ciudadExpeNat')?>").removeAttr('disabled');
		            
		            //$('#<?php echo $this->campoSeguro('ciudadExpeNat')?>').width(250);
		            $("#<?php echo $this->campoSeguro('ciudadExpeNat')?>").select2();
		            
		            $("#<?php echo $this->campoSeguro('ciudadExpeNat')?>").removeClass("validate[required]");
		            
			        }
		    			
		    }
			                    
		   });
		};


////////////////////////////////////////////////////////////////////////////



function consultarDepartamentoRep(elem, request, response){
	  $.ajax({
	    url: "<?php echo $urlFinal27?>",
	    dataType: "json",
	    data: { valor:$("#<?php echo $this->campoSeguro('paisExpeRep')?>").val()},
	    success: function(data){
	        if(data[0]!=" "){
	            $("#<?php echo $this->campoSeguro('departamentoExpeRep')?>").html('');
	            $("<option value=''>Seleccione  ....</option>").appendTo("#<?php echo $this->campoSeguro('departamentoExpeRep')?>");
	            $.each(data , function(indice,valor){
	            	$("<option value='"+data[ indice ].id_departamento+"'>"+data[ indice ].nombre+"</option>").appendTo("#<?php echo $this->campoSeguro('departamentoExpeRep')?>");
	            	
	            });
	            
	            $("#<?php echo $this->campoSeguro('departamentoExpeRep')?>").removeAttr('disabled');
	            
	            //$('#<?php echo $this->campoSeguro('departamentoExpeRep')?>').width(250);
	            $("#<?php echo $this->campoSeguro('departamentoExpeRep')?>").select2();
	            
	            $("#<?php echo $this->campoSeguro('departamentoExpeRep')?>").removeClass("validate[required]");
	            $("#<?php echo $this->campoSeguro('paisExpeRep')?>").removeClass("validate[required]");
	            
		    }
		    
	  			
	    }
		                    
	   });
	};
	
	
	
		function consultarCiudadRep(elem, request, response){
		  $.ajax({
		    url: "<?php echo $urlFinal28?>",
		    dataType: "json",
		    data: { valor:$("#<?php echo $this->campoSeguro('departamentoExpeRep')?>").val()},
		    success: function(data){ 
		        if(data[0]!=" "){
		            $("#<?php echo $this->campoSeguro('ciudadExpeRep')?>").html('');
		            $("<option value=''>Seleccione  ....</option>").appendTo("#<?php echo $this->campoSeguro('ciudadExpeRep')?>");
		            $.each(data , function(indice,valor){
		            	$("<option value='"+data[ indice ].id_ciudad+"'>"+data[ indice ].nombreciudad+"</option>").appendTo("#<?php echo $this->campoSeguro('ciudadExpeRep')?>");
		            	
		            });
		            
		            $("#<?php echo $this->campoSeguro('ciudadExpeRep')?>").removeAttr('disabled');
		            
		            //$('#<?php echo $this->campoSeguro('ciudadExpeRep')?>').width(250);
		            $("#<?php echo $this->campoSeguro('ciudadExpeRep')?>").select2();
		            
		            $("#<?php echo $this->campoSeguro('ciudadExpeRep')?>").removeClass("validate[required]");
		            
			        }
		    			
		    }
			                    
		   });
		};


////////////////////////////////////////////////////////////////////////////













function consultarDepartamentoLug(elem, request, response){
	  $.ajax({
	    url: "<?php echo $urlFinal16?>",
	    dataType: "json",
	    data: { valor:$("#<?php echo $this->campoSeguro('personaJuridicaPais')?>").val()},
	    success: function(data){
	        if(data[0]!=" "){
	            $("#<?php echo $this->campoSeguro('personaJuridicaDepartamento')?>").html('');
	            $("<option value=''>Seleccione  ....</option>").appendTo("#<?php echo $this->campoSeguro('personaJuridicaDepartamento')?>");
	            $.each(data , function(indice,valor){
	            	$("<option value='"+data[ indice ].id_departamento+"'>"+data[ indice ].nombre+"</option>").appendTo("#<?php echo $this->campoSeguro('personaJuridicaDepartamento')?>");
	            	
	            });
	            
	            $("#<?php echo $this->campoSeguro('personaJuridicaDepartamento')?>").removeAttr('disabled');
	            
	            //$('#<?php echo $this->campoSeguro('personaJuridicaDepartamento')?>').width(250);
	            $("#<?php echo $this->campoSeguro('personaJuridicaDepartamento')?>").select2();
	            
	            $("#<?php echo $this->campoSeguro('personaJuridicaDepartamento')?>").removeClass("validate[required]");
	            $("#<?php echo $this->campoSeguro('personaJuridicaPais')?>").removeClass("validate[required]");
	            
		    }
		    
	  			
	    }
		                    
	   });
	};
	
	function consultarCodigoLug(elem, request, response){
	  $.ajax({
	    url: "<?php echo $urlFinal19?>",
	    dataType: "json",
	    data: { valor:$("#<?php echo $this->campoSeguro('personaJuridicaPais')?>").val()},
	    success: function(data){
	        if(data[0]!=" "){
	            $("#<?php echo $this->campoSeguro('codigoPais')?>").val(data[0].cod_pais);
		    }
		    
	  			
	    }
		                    
	   });
	};

	function consultarCiudadLug(elem, request, response){
		  $.ajax({
		    url: "<?php echo $urlFinal17?>",
		    dataType: "json",
		    data: { valor:$("#<?php echo $this->campoSeguro('personaJuridicaDepartamento')?>").val()},
		    success: function(data){ 
		        if(data[0]!=" "){
		            $("#<?php echo $this->campoSeguro('personaJuridicaCiudad')?>").html('');
		            $("<option value=''>Seleccione  ....</option>").appendTo("#<?php echo $this->campoSeguro('personaJuridicaCiudad')?>");
		            $.each(data , function(indice,valor){
		            	$("<option value='"+data[ indice ].id_ciudad+"'>"+data[ indice ].nombreciudad+"</option>").appendTo("#<?php echo $this->campoSeguro('personaJuridicaCiudad')?>");
		            	
		            });
		            
		            $("#<?php echo $this->campoSeguro('personaJuridicaCiudad')?>").removeAttr('disabled');
		            
		            //$('#<?php echo $this->campoSeguro('personaJuridicaCiudad')?>').width(250);
		            $("#<?php echo $this->campoSeguro('personaJuridicaCiudad')?>").select2();
		            
		            $("#<?php echo $this->campoSeguro('personaJuridicaCiudad')?>").removeClass("validate[required]");
		            
			        }
		    			
		    }
			                    
		   });
		};
		
		
    function consultarCiudadCon(elem, request, response){
		  $.ajax({
		    url: "<?php echo $urlFinal18?>",
		    dataType: "json",
		    data: { valor:$("#<?php echo $this->campoSeguro('personaNaturalContaDepartamento')?>").val()},
		    success: function(data){ 
		        if(data[0]!=" "){
		            $("#<?php echo $this->campoSeguro('personaNaturalContaCiudad')?>").html('');
		            $("<option value=''>Seleccione  ....</option>").appendTo("#<?php echo $this->campoSeguro('personaNaturalContaCiudad')?>");
		            $.each(data , function(indice,valor){
		            	$("<option value='"+data[ indice ].id_ciudad+"'>"+data[ indice ].nombreciudad+"</option>").appendTo("#<?php echo $this->campoSeguro('personaNaturalContaCiudad')?>");
		            	
		            });
		            
		            $("#<?php echo $this->campoSeguro('personaNaturalContaCiudad')?>").removeAttr('disabled');
		            
		            //$('#<?php echo $this->campoSeguro('personaNaturalContaCiudad')?>').width(250);
		            $("#<?php echo $this->campoSeguro('personaNaturalContaCiudad')?>").select2();
		            
		            $("#<?php echo $this->campoSeguro('personaNaturalContaCiudad')?>").removeClass("validate[required]");
		            
			        }
		    			
		    }
			                    
		   });
		};
		
	
	function consultarCiudadJur(elem, request, response){
		  $.ajax({
		    url: "<?php echo $urlFinal20?>",
		    dataType: "json",
		    data: { valor:$("#<?php echo $this->campoSeguro('departamento')?>").val()},
		    success: function(data){ 
		        if(data[0]!=" "){
		            $("#<?php echo $this->campoSeguro('ciudad')?>").html('');
		            $("<option value=''>Seleccione  ....</option>").appendTo("#<?php echo $this->campoSeguro('ciudad')?>");
		            $.each(data , function(indice,valor){
		            	$("<option value='"+data[ indice ].id_ciudad+"'>"+data[ indice ].nombreciudad+"</option>").appendTo("#<?php echo $this->campoSeguro('ciudad')?>");
		            	
		            });
		            
		            $("#<?php echo $this->campoSeguro('ciudad')?>").removeAttr('disabled');
		            
		            //$('#<?php echo $this->campoSeguro('ciudad')?>').width(250);
		            $("#<?php echo $this->campoSeguro('ciudad')?>").select2();
		            
		            $("#<?php echo $this->campoSeguro('ciudad')?>").removeClass("validate[required]");
		            
			        }
		    			
		    }
			                    
		   });
		};
		
		
	function consultarNomenclaturaNat(elem, request, response){
		  $.ajax({
		    url: "<?php echo $urlFinal21?>",
		    dataType: "json",
		    data: { valor:$("#<?php echo $this->campoSeguro('listaNomenclaturasNat')?>").val()},
		    success: function(data){ 
		        if(data[0]!=" "){
		            $("#<?php echo $this->campoSeguro('seccionParametrosNat')?>").html('');
		            $.each(data , function(indice,valor){
		            	$("<option value='"+data[ indice ].id_nomenclatura+"'>"+data[ indice ].abreviatura+"</option>").appendTo("#<?php echo $this->campoSeguro('seccionParametrosNat')?>");
		            	
		            });
		            
		            $("#<?php echo $this->campoSeguro('seccionParametrosNat')?>").removeAttr('disabled');
		            
		            //$('#<?php echo $this->campoSeguro('seccionParametrosNat')?>').width(250);
		            $("#<?php echo $this->campoSeguro('seccionParametrosNat')?>").select2();
		            
		            $("#<?php echo $this->campoSeguro('seccionParametrosNat')?>").removeClass("validate[required]");
		            
			        }
		    			
		    }
			                    
		   });
		};	
		
		
	function consultarNomenclatura(elem, request, response){
		  $.ajax({
		    url: "<?php echo $urlFinal22?>",
		    dataType: "json",
		    data: { valor:$("#<?php echo $this->campoSeguro('listaNomenclaturas')?>").val()},
		    success: function(data){ 
		        if(data[0]!=" "){
		            $("#<?php echo $this->campoSeguro('seccionParametros')?>").html('');
		            $.each(data , function(indice,valor){
		            	$("<option value='"+data[ indice ].id_nomenclatura+"'>"+data[ indice ].abreviatura+"</option>").appendTo("#<?php echo $this->campoSeguro('seccionParametros')?>");
		            	
		            });
		            
		            $("#<?php echo $this->campoSeguro('seccionParametros')?>").removeAttr('disabled');
		            
		            //$('#<?php echo $this->campoSeguro('seccionParametros')?>').width(250);
		            $("#<?php echo $this->campoSeguro('seccionParametros')?>").select2();
		            
		            $("#<?php echo $this->campoSeguro('seccionParametros')?>").removeClass("validate[required]");
		            
			        }
		    			
		    }
			                    
		   });
		};	
		
		
		function consultarNBC(elem, request, response){
		  $.ajax({
		    url: "<?php echo $urlFinal23?>",
		    dataType: "json",
		    data: { valor:$("#<?php echo $this->campoSeguro('personaNaturalArea')?>").val()},
		    success: function(data){ 
		        if(data[0]!=" "){
		            $("#<?php echo $this->campoSeguro('personaNaturalNBC')?>").html('');
		            $("<option value=''>Seleccione  ....</option>").appendTo("#<?php echo $this->campoSeguro('personaNaturalNBC')?>");
		            $.each(data , function(indice,valor){
		            	$("<option value='"+data[ indice ].id_nucleo+"'>"+data[ indice ].nombre+"</option>").appendTo("#<?php echo $this->campoSeguro('personaNaturalNBC')?>");
		            	
		            });
		            
		            $("#<?php echo $this->campoSeguro('personaNaturalNBC')?>").removeAttr('disabled');
		            
		            $("#<?php echo $this->campoSeguro('personaNaturalNBC')?>").select2();
		            
		            $("#<?php echo $this->campoSeguro('personaNaturalArea')?>").removeClass("validate[required]");
		            
		            
		            
			        }
		    			
		    }
			                    
		   });
		};	
		
	
		function consultarNBCRep(elem, request, response){
		  $.ajax({
		    url: "<?php echo $urlFinal24?>",
		    dataType: "json",
		    data: { valor:$("#<?php echo $this->campoSeguro('personaArea')?>").val()},
		    success: function(data){ 
		        if(data[0]!=" "){
		            $("#<?php echo $this->campoSeguro('personaNBC')?>").html('');
		            $("<option value=''>Seleccione  ....</option>").appendTo("#<?php echo $this->campoSeguro('personaNBC')?>");
		            $.each(data , function(indice,valor){
		            	$("<option value='"+data[ indice ].id_nucleo+"'>"+data[ indice ].nombre+"</option>").appendTo("#<?php echo $this->campoSeguro('personaNBC')?>");
		            	
		            });
		            
		            $("#<?php echo $this->campoSeguro('personaNBC')?>").removeAttr('disabled');
		            
		            $("#<?php echo $this->campoSeguro('personaNBC')?>").select2();
		            
		            $("#<?php echo $this->campoSeguro('personaArea')?>").removeClass("validate[required]");
		            
		            
		            
			        }
		    			
		    }
			                    
		   });
		};		
		
		
	function calcularDigito(cadenaNit){
		  
		  var num_primos, control_mod_1, control_mod_2, tamano_nit, i, digito_verificacion;
		  
		  if(isNaN(cadenaNit)){
		  		alert('El valor digitado no es un numero valido');	
		  }else{
		  		num_primos = new Array (16); 
		       	control_mod_1 = 0; 
		        control_mod_2 = 0; 
		        tamano_nit = cadenaNit.length ;
		
		       	num_primos[1]=3;
		       	num_primos[2]=7;
		       	num_primos[3]=13; 
		       	num_primos[4]=17;
		       	num_primos[5]=19;
		       	num_primos[6]=23;
		       	num_primos[7]=29;
		       	num_primos[8]=37;
		       	num_primos[9]=41;
		       	num_primos[10]=43;
		       	num_primos[11]=47;  
		       	num_primos[12]=53;  
		       	num_primos[13]=59; 
		       	num_primos[14]=67; 
		       	num_primos[15]=71;
		       	
		       	for(i=0 ; i < tamano_nit ; i++)
       			{ 
         	 		control_mod_2 = (cadenaNit.substr(i,1));
           			control_mod_1 += (control_mod_2 * num_primos[tamano_nit - i]);
       			} 
        		control_mod_2 = control_mod_1 % 11;
		  		
		  		if (control_mod_2 > 1)
		       	{
		            digito_verificacion = 11 - control_mod_2;
		       	} else {
		            digito_verificacion = control_mod_2;
		       	}
		  		$("#<?php echo $this->campoSeguro('digito')?>").val(digito_verificacion);
		  }
	};
	
	
	function calcularDigitoCedula(cadenaCedula){
		  
		  var num_primos, control_mod_1, control_mod_2, tamano_cedula, i, digito_verificacion;
		  
		  if(isNaN(cadenaCedula)){
		  		alert('El valor digitado no es un numero valido');	
		  }else{
		  		num_primos = new Array (16); 
		       	control_mod_1 = 0; 
		        control_mod_2 = 0; 
		        tamano_cedula = cadenaCedula.length ;
		
		       	num_primos[1]=3;
		       	num_primos[2]=7;
		       	num_primos[3]=13; 
		       	num_primos[4]=17;
		       	num_primos[5]=19;
		       	num_primos[6]=23;
		       	num_primos[7]=29;
		       	num_primos[8]=37;
		       	num_primos[9]=41;
		       	num_primos[10]=43;
		       	num_primos[11]=47;  
		       	num_primos[12]=53;  
		       	num_primos[13]=59; 
		       	num_primos[14]=67; 
		       	num_primos[15]=71;
		       	
		       	for(i=0 ; i < tamano_cedula ; i++)
       			{ 
         	 		control_mod_2 = (cadenaCedula.substr(i,1));
           			control_mod_1 += (control_mod_2 * num_primos[tamano_cedula - i]);
       			} 
        		control_mod_2 = control_mod_1 % 11;
		  		
		  		if (control_mod_2 > 1)
		       	{
		            digito_verificacion = 11 - control_mod_2;
		       	} else {
		            digito_verificacion = control_mod_2;
		       	}
		  		$("#<?php echo $this->campoSeguro('digitoNat')?>").val(digito_verificacion);
		  }
	};
	
	function calcularDigitoCedulaRepre(cadenaCedula){
		  
		  var num_primos, control_mod_1, control_mod_2, tamano_cedula, i, digito_verificacion;
		  
		  if(isNaN(cadenaCedula)){
		  		alert('El valor digitado no es un numero valido');	
		  }else{
		  		num_primos = new Array (16); 
		       	control_mod_1 = 0; 
		        control_mod_2 = 0; 
		        tamano_cedula = cadenaCedula.length ;
		
		       	num_primos[1]=3;
		       	num_primos[2]=7;
		       	num_primos[3]=13; 
		       	num_primos[4]=17;
		       	num_primos[5]=19;
		       	num_primos[6]=23;
		       	num_primos[7]=29;
		       	num_primos[8]=37;
		       	num_primos[9]=41;
		       	num_primos[10]=43;
		       	num_primos[11]=47;  
		       	num_primos[12]=53;  
		       	num_primos[13]=59; 
		       	num_primos[14]=67; 
		       	num_primos[15]=71;
		       	
		       	for(i=0 ; i < tamano_cedula ; i++)
       			{ 
         	 		control_mod_2 = (cadenaCedula.substr(i,1));
           			control_mod_1 += (control_mod_2 * num_primos[tamano_cedula - i]);
       			} 
        		control_mod_2 = control_mod_1 % 11;
		  		
		  		if (control_mod_2 > 1)
		       	{
		            digito_verificacion = 11 - control_mod_2;
		       	} else {
		            digito_verificacion = control_mod_2;
		       	}
		  		$("#<?php echo $this->campoSeguro('digitoRepre')?>").val(digito_verificacion);
		  }
	};
	
	
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
      
            
      			 $("#<?php echo $this->campoSeguro('paisExpeNat')?>").change(function(){
		        	if($("#<?php echo $this->campoSeguro('paisExpeNat')?>").val()!=''){
						consultarDepartamentoExp();
		    		}else{
		    			$("#<?php echo $this->campoSeguro('departamentoExpeNat')?>").attr('disabled','');
		    			}
		    	      });
		    	      
		        $("#<?php echo $this->campoSeguro('departamentoExpeNat')?>").change(function(){
		        	if($("#<?php echo $this->campoSeguro('departamentoExpeNat')?>").val()!=''){
		            	consultarCiudadExp();
		    		}else{
		    			$("#<?php echo $this->campoSeguro('ciudadExpeNat')?>").attr('disabled','');
		    			}
		    	      });
      
      
      
      			 $("#<?php echo $this->campoSeguro('paisExpeRep')?>").change(function(){
		        	if($("#<?php echo $this->campoSeguro('paisExpeRep')?>").val()!=''){
						consultarDepartamentoRep();
		    		}else{
		    			$("#<?php echo $this->campoSeguro('departamentoExpeRep')?>").attr('disabled','');
		    			}
		    	      });
		    	      
		        $("#<?php echo $this->campoSeguro('departamentoExpeRep')?>").change(function(){
		        	if($("#<?php echo $this->campoSeguro('departamentoExpeRep')?>").val()!=''){
		            	consultarCiudadRep();
		    		}else{
		    			$("#<?php echo $this->campoSeguro('ciudadExpeRep')?>").attr('disabled','');
		    			}
		    	      });
            
      
      
      
		        $("#<?php echo $this->campoSeguro('personaJuridicaPais')?>").change(function(){
		        	if($("#<?php echo $this->campoSeguro('personaJuridicaPais')?>").val()!=''){
						consultarDepartamentoLug();
						consultarCodigoLug();
		    		}else{
		    			$("#<?php echo $this->campoSeguro('personaJuridicaDepartamento')?>").attr('disabled','');
		    			}
		    	      });
		    	      
		        $("#<?php echo $this->campoSeguro('personaJuridicaDepartamento')?>").change(function(){
		        	if($("#<?php echo $this->campoSeguro('personaJuridicaDepartamento')?>").val()!=''){
		            	consultarCiudadLug();
		    		}else{
		    			$("#<?php echo $this->campoSeguro('personaJuridicaCiudad')?>").attr('disabled','');
		    			}
		    	      });
		    	      
		    	
		    	$("#<?php echo $this->campoSeguro('personaNaturalContaDepartamento')?>").change(function(){
		        	if($("#<?php echo $this->campoSeguro('personaNaturalContaDepartamento')?>").val()!=''){
		            	consultarCiudadCon();
		    		}else{
		    			$("#<?php echo $this->campoSeguro('personaNaturalContaCiudad')?>").attr('disabled','');
		    			}
		    	      });
		    	      
		    	      
		    	$("#<?php echo $this->campoSeguro('departamento')?>").change(function(){
		        	if($("#<?php echo $this->campoSeguro('departamento')?>").val()!=''){
		            	consultarCiudadJur();
		    		}else{
		    			$("#<?php echo $this->campoSeguro('ciudad')?>").attr('disabled','');
		    			}
		    	      });         
		    	
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////   

  
		    	$("#<?php echo $this->campoSeguro('personaNaturalArea')?>").change(function(){
		        	if($("#<?php echo $this->campoSeguro('personaNaturalArea')?>").val()!=''){
		            	consultarNBC();
		    		}else{
		    			$("#<?php echo $this->campoSeguro('personaNaturalArea')?>").addClass("validate[required]");
		    			$("#<?php echo $this->campoSeguro('personaNaturalNBC')?>").attr('disabled','');
		    			}
		    	      });
		    	      
		    	$("#<?php echo $this->campoSeguro('personaNaturalNBC')?>").change(function(){
		        	if($("#<?php echo $this->campoSeguro('personaNaturalNBC')?>").val()!=''){
		            	$("#<?php echo $this->campoSeguro('personaNaturalNBC')?>").removeClass("validate[required]");
		    		}else{
		    			$("#<?php echo $this->campoSeguro('personaNaturalNBC')?>").addClass("validate[required]");
		    			}
		    	      });      
		    	 
		    	$("#<?php echo $this->campoSeguro('personaArea')?>").change(function(){
		        	if($("#<?php echo $this->campoSeguro('personaArea')?>").val()!=''){
		            	consultarNBCRep();
		    		}else{
		    			$("#<?php echo $this->campoSeguro('personaArea')?>").addClass("validate[required]");
		    			$("#<?php echo $this->campoSeguro('personaNBC')?>").attr('disabled','');
		    			}
		    	      });
		    	      
		    	$("#<?php echo $this->campoSeguro('personaNBC')?>").change(function(){
		        	if($("#<?php echo $this->campoSeguro('personaNBC')?>").val()!=''){
		            	$("#<?php echo $this->campoSeguro('personaNBC')?>").removeClass("validate[required]");
		    		}else{
		    			$("#<?php echo $this->campoSeguro('personaNBC')?>").addClass("validate[required]");
		    			}
		    	      });       		    	      
		    	              
		    	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////    		    	
		    	
		    	      
		    	$("#<?php echo $this->campoSeguro('nit')?>").on('keyup', function(){//Ejecutar la Evaluación por Eventos de Teclado
        				var value = $(this).val().length;
        				if(value == 9){//Ejecutar solo Cuando se Completa el NIT
        					var cadenaNit = $(this).val();
        					calcularDigito(cadenaNit);//LLamar la Función para Ejecutar Calculo Digito Verificación
        				}else{
        					var cadenaNit = null;
        					$("#<?php echo $this->campoSeguro('digito')?>").val(null);
        				}
        				
    			}).keyup();
    			
    			
    			$("#<?php echo $this->campoSeguro('documentoNat')?>").on('keyup', function(){//Ejecutar la Evaluación por Eventos de Teclado
        				var value = $(this).val().length;
        				if(value > 3){//Ejecutar solo Cuando se Completa el NIT
        					var cadenaCedula = $(this).val();
        					calcularDigitoCedula(cadenaCedula);//LLamar la Función para Ejecutar Calculo Digito Verificación
        				}else{
        					var cadenaCedula = null;
        					$("#<?php echo $this->campoSeguro('digitoNat')?>").val(null);
        				}
        				
    			}).keyup();
    			
    			
    			$("#<?php echo $this->campoSeguro('numeroDocumento')?>").on('keyup', function(){//Ejecutar la Evaluación por Eventos de Teclado
        				var value = $(this).val().length;
        				if(value > 3){//Ejecutar solo Cuando se Completa el NIT
        					var cadenaCedula = $(this).val();
        					calcularDigitoCedulaRepre(cadenaCedula);//LLamar la Función para Ejecutar Calculo Digito Verificación
        				}else{
        					var cadenaCedula = null;
        					$("#<?php echo $this->campoSeguro('digitoRepre')?>").val(null);
        				}
        				
    			}).keyup();      
	    	      
		 });
		  
 
 
 		$( '#<?php echo $this->campoSeguro('DocumentoRUTNat')?>' ).change(function() {
			var ext = $('#<?php echo $this->campoSeguro('DocumentoRUTNat')?>').val().split('.').pop().toLowerCase();
			$fileupload = $('#<?php echo $this->campoSeguro('DocumentoRUTNat')?>');
			if($.inArray(ext, ['pdf']) == -1) {
			    alert('Extension de Archivo No Permitida!');
			    
				clearFileInput($fileupload);
				//$fileupload.replaceWith($fileupload.clone(true));
			}
		});
		
		
		
		$( '#<?php echo $this->campoSeguro('DocumentoRUT')?>' ).change(function() {
			var ext = $('#<?php echo $this->campoSeguro('DocumentoRUT')?>').val().split('.').pop().toLowerCase();
			$fileupload = $('#<?php echo $this->campoSeguro('DocumentoRUT')?>');
			if($.inArray(ext, ['pdf']) == -1) {
			    alert('Extension de Archivo No Permitida!');
			    
				clearFileInput($fileupload);
				//$fileupload.replaceWith($fileupload.clone(true));
			}
		});
		
		
		$( '#<?php echo $this->campoSeguro('DocumentoRUPNat')?>' ).change(function() {
			var ext = $('#<?php echo $this->campoSeguro('DocumentoRUPNat')?>').val().split('.').pop().toLowerCase();
			$fileupload = $('#<?php echo $this->campoSeguro('DocumentoRUPNat')?>');
			if($.inArray(ext, ['pdf']) == -1) {
			    alert('Extension de Archivo No Permitida!');
			    
				clearFileInput($fileupload);
				//$fileupload.replaceWith($fileupload.clone(true));
			}
		});
		
		
		
		$( '#<?php echo $this->campoSeguro('DocumentoRUP')?>' ).change(function() {
			var ext = $('#<?php echo $this->campoSeguro('DocumentoRUP')?>').val().split('.').pop().toLowerCase();
			$fileupload = $('#<?php echo $this->campoSeguro('DocumentoRUP')?>');
			if($.inArray(ext, ['pdf']) == -1) {
			    alert('Extension de Archivo No Permitida!');
			    
				clearFileInput($fileupload);
				//$fileupload.replaceWith($fileupload.clone(true));
			}
		});
 
 
		 
		$( '#<?php echo $this->campoSeguro('direccion')?>' ).keypress(function(tecla) {
			 if(tecla.charCode != 48  && tecla.charCode != 49 && tecla.charCode != 50 && 
			    tecla.charCode != 51  && tecla.charCode != 52 && tecla.charCode != 53 && 
			    tecla.charCode != 54  && tecla.charCode != 55 && tecla.charCode != 56 && 
			    tecla.charCode != 57  && tecla.charCode != 0  && tecla.charCode != 32) return false;
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
		
			    $('#<?php echo $this->campoSeguro('direccion')?>').droppable({
			        hoverClass: 'active',
			        drop: function (event, ui) {
			        	if($(ui.draggable).find('select option:selected').text() != 'Nomenclatura'){
			        		this.value += $(ui.draggable).find('select option:selected').text() + " ";
			        	}
			        }
			    });
			    
			    
			    $("#<?php echo $this->campoSeguro('listaNomenclaturas')?>").change(function(){
		        	if($("#<?php echo $this->campoSeguro('listaNomenclaturas')?>").val()!=''){
						consultarNomenclatura();
		    		}else{
		    			$("#<?php echo $this->campoSeguro('seccionParametros')?>").attr('disabled','');
		    			}
		    	    });
		});
		
		$( '#<?php echo $this->campoSeguro('listaNomenclaturas')?>' ).change(function() {
		
			$("#<?php echo $this->campoSeguro('seccionParametros')?>").removeAttr('disabled');
		    $("#<?php echo $this->campoSeguro('seccionParametros')?>").select2();
		});
		
		$("#btOper1").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccion')?>').val();
			var post = actual + " A ";
			$('#<?php echo $this->campoSeguro('direccion')?>').val(post);
		});
		
		$("#btOper2").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccion')?>').val();
			var post = actual + " B ";
			$('#<?php echo $this->campoSeguro('direccion')?>').val(post);
		});
		
		$("#btOper3").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccion')?>').val();
			var post = actual + " C ";
			$('#<?php echo $this->campoSeguro('direccion')?>').val(post);
		});
		
		$("#btOper4").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccion')?>').val();
			var post = actual + " D ";
			$('#<?php echo $this->campoSeguro('direccion')?>').val(post);
		});
		
		$("#btOper5").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccion')?>').val();
			var post = actual + " E ";
			$('#<?php echo $this->campoSeguro('direccion')?>').val(post);
		});
		
		$("#btOper6").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccion')?>').val();
			var post = actual + " F ";
			$('#<?php echo $this->campoSeguro('direccion')?>').val(post);
		});
		
		$("#btOper7").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccion')?>').val();
			var post = actual + " G ";
			$('#<?php echo $this->campoSeguro('direccion')?>').val(post);
		});
		
		$("#btOper8").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccion')?>').val();
			var post = actual + " H ";
			$('#<?php echo $this->campoSeguro('direccion')?>').val(post);
		});
		
		$("#btOper9").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccion')?>').val();
			var post = actual + " I ";
			$('#<?php echo $this->campoSeguro('direccion')?>').val(post);
		});
		
		$("#btOper10").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccion')?>').val();
			var post = actual + " J ";
			$('#<?php echo $this->campoSeguro('direccion')?>').val(post);
		});
		
		$("#btOper11").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccion')?>').val();
			var post = actual + " K ";
			$('#<?php echo $this->campoSeguro('direccion')?>').val(post);
		});
		
		$("#btOper12").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccion')?>').val();
			var post = actual + " L ";
			$('#<?php echo $this->campoSeguro('direccion')?>').val(post);
		});
		
		$("#btOper13").click(function(){
			$('#<?php echo $this->campoSeguro('direccion')?>').val("");
		});
		
		$("#btOper15").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccion')?>').val();
			var post = actual + " M ";
			$('#<?php echo $this->campoSeguro('direccion')?>').val(post);
		});
		
		$("#btOper16").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccion')?>').val();
			var post = actual + " N ";
			$('#<?php echo $this->campoSeguro('direccion')?>').val(post);
		});
		
		$("#btOper17").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccion')?>').val();
			var post = actual + " O ";
			$('#<?php echo $this->campoSeguro('direccion')?>').val(post);
		});
		
		$("#btOper18").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccion')?>').val();
			var post = actual + " P ";
			$('#<?php echo $this->campoSeguro('direccion')?>').val(post);
		});
		
		$("#btOper19").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccion')?>').val();
			var post = actual + " Q ";
			$('#<?php echo $this->campoSeguro('direccion')?>').val(post);
		});
		
		$("#btOper20").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccion')?>').val();
			var post = actual + " R ";
			$('#<?php echo $this->campoSeguro('direccion')?>').val(post);
		});
		
		$("#btOper21").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccion')?>').val();
			var post = actual + " S ";
			$('#<?php echo $this->campoSeguro('direccion')?>').val(post);
		});
		
		$("#btOper22").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccion')?>').val();
			var post = actual + " T ";
			$('#<?php echo $this->campoSeguro('direccion')?>').val(post);
		});
		
		$("#btOper23").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccion')?>').val();
			var post = actual + " U ";
			$('#<?php echo $this->campoSeguro('direccion')?>').val(post);
		});
		
		$("#btOper24").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccion')?>').val();
			var post = actual + " V ";
			$('#<?php echo $this->campoSeguro('direccion')?>').val(post);
		});
		
		$("#btOper25").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccion')?>').val();
			var post = actual + " W ";
			$('#<?php echo $this->campoSeguro('direccion')?>').val(post);
		});
		
		$("#btOper26").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccion')?>').val();
			var post = actual + " X ";
			$('#<?php echo $this->campoSeguro('direccion')?>').val(post);
		});
		
		$("#btOper27").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccion')?>').val();
			var post = actual + " Y ";
			$('#<?php echo $this->campoSeguro('direccion')?>').val(post);
		});
		
		$("#btOper28").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccion')?>').val();
			var post = actual + " Z ";
			$('#<?php echo $this->campoSeguro('direccion')?>').val(post);
		});
		
		$("#btOper14").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccion')?>').val();
			if($('#<?php echo $this->campoSeguro('seccionParametros')?>' + ' option:selected').html() != 'Nomenclatura'){
				var post = actual + ' ' + $('#<?php echo $this->campoSeguro('seccionParametros')?>' + ' option:selected').html() + ' ';
				$('#<?php echo $this->campoSeguro('direccion')?>').val(post);
			}
		});
		
		
		
		
		
		
		
		
		
		$( '#<?php echo $this->campoSeguro('direccionNat')?>' ).keypress(function(tecla) {
			 if(tecla.charCode != 48  && tecla.charCode != 49 && tecla.charCode != 50 && 
			    tecla.charCode != 51  && tecla.charCode != 52 && tecla.charCode != 53 && 
			    tecla.charCode != 54  && tecla.charCode != 55 && tecla.charCode != 56 && 
			    tecla.charCode != 57  && tecla.charCode != 0  && tecla.charCode != 32) return false;
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
		
			    $('#<?php echo $this->campoSeguro('direccionNat')?>').droppable({
			        hoverClass: 'active',
			        drop: function (event, ui) {
			        	if($(ui.draggable).find('select option:selected').text() != 'Nomenclatura'){
			        		this.value += $(ui.draggable).find('select option:selected').text() + " ";
			        	}
			        }
			    });
			    
			    
			    $("#<?php echo $this->campoSeguro('listaNomenclaturasNat')?>").change(function(){
		        	if($("#<?php echo $this->campoSeguro('listaNomenclaturasNat')?>").val()!=''){
						consultarNomenclaturaNat();
		    		}else{
		    			$("#<?php echo $this->campoSeguro('seccionParametrosNat')?>").attr('disabled','');
		    			}
		    	    });
		});
		
		$( '#<?php echo $this->campoSeguro('listaNomenclaturasNat')?>' ).change(function() {
		
			$("#<?php echo $this->campoSeguro('seccionParametrosNat')?>").removeAttr('disabled');
		    $("#<?php echo $this->campoSeguro('seccionParametrosNat')?>").select2();
		});
		
		
		$("#btOper1Nat").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccionNat')?>').val();
			var post = actual + " A ";
			$('#<?php echo $this->campoSeguro('direccionNat')?>').val(post);
		});
		
		$("#btOper2Nat").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccionNat')?>').val();
			var post = actual + " B ";
			$('#<?php echo $this->campoSeguro('direccionNat')?>').val(post);
		});
		
		$("#btOper3Nat").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccionNat')?>').val();
			var post = actual + " C ";
			$('#<?php echo $this->campoSeguro('direccionNat')?>').val(post);
		});
		
		$("#btOper4Nat").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccionNat')?>').val();
			var post = actual + " D ";
			$('#<?php echo $this->campoSeguro('direccionNat')?>').val(post);
		});
		
		$("#btOper5Nat").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccionNat')?>').val();
			var post = actual + " E ";
			$('#<?php echo $this->campoSeguro('direccionNat')?>').val(post);
		});
		
		$("#btOper6Nat").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccionNat')?>').val();
			var post = actual + " F ";
			$('#<?php echo $this->campoSeguro('direccionNat')?>').val(post);
		});
		
		$("#btOper7Nat").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccionNat')?>').val();
			var post = actual + " G ";
			$('#<?php echo $this->campoSeguro('direccionNat')?>').val(post);
		});
		
		$("#btOper8Nat").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccionNat')?>').val();
			var post = actual + " H ";
			$('#<?php echo $this->campoSeguro('direccionNat')?>').val(post);
		});
		
		$("#btOper9Nat").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccionNat')?>').val();
			var post = actual + " I ";
			$('#<?php echo $this->campoSeguro('direccionNat')?>').val(post);
		});
		
		$("#btOper10Nat").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccionNat')?>').val();
			var post = actual + " J ";
			$('#<?php echo $this->campoSeguro('direccionNat')?>').val(post);
		});
		
		$("#btOper11Nat").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccionNat')?>').val();
			var post = actual + " K ";
			$('#<?php echo $this->campoSeguro('direccionNat')?>').val(post);
		});
		
		$("#btOper12Nat").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccionNat')?>').val();
			var post = actual + " L ";
			$('#<?php echo $this->campoSeguro('direccionNat')?>').val(post);
		});
		
		$("#btOper13Nat").click(function(){
			$('#<?php echo $this->campoSeguro('direccionNat')?>').val("");
		});
		
		$("#btOper15Nat").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccionNat')?>').val();
			var post = actual + " M ";
			$('#<?php echo $this->campoSeguro('direccionNat')?>').val(post);
		});
		
		$("#btOper16Nat").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccionNat')?>').val();
			var post = actual + " N ";
			$('#<?php echo $this->campoSeguro('direccionNat')?>').val(post);
		});
		
		$("#btOper17Nat").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccionNat')?>').val();
			var post = actual + " O ";
			$('#<?php echo $this->campoSeguro('direccionNat')?>').val(post);
		});
		
		$("#btOper18Nat").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccionNat')?>').val();
			var post = actual + " P ";
			$('#<?php echo $this->campoSeguro('direccionNat')?>').val(post);
		});
		
		$("#btOper19Nat").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccionNat')?>').val();
			var post = actual + " Q ";
			$('#<?php echo $this->campoSeguro('direccionNat')?>').val(post);
		});
		
		$("#btOper20Nat").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccionNat')?>').val();
			var post = actual + " R ";
			$('#<?php echo $this->campoSeguro('direccionNat')?>').val(post);
		});
		
		$("#btOper21Nat").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccionNat')?>').val();
			var post = actual + " S ";
			$('#<?php echo $this->campoSeguro('direccionNat')?>').val(post);
		});
		
		$("#btOper22Nat").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccionNat')?>').val();
			var post = actual + " T ";
			$('#<?php echo $this->campoSeguro('direccionNat')?>').val(post);
		});
		
		$("#btOper23Nat").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccionNat')?>').val();
			var post = actual + " U ";
			$('#<?php echo $this->campoSeguro('direccionNat')?>').val(post);
		});
		
		$("#btOper24Nat").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccionNat')?>').val();
			var post = actual + " V ";
			$('#<?php echo $this->campoSeguro('direccionNat')?>').val(post);
		});
		
		$("#btOper25Nat").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccionNat')?>').val();
			var post = actual + " W ";
			$('#<?php echo $this->campoSeguro('direccionNat')?>').val(post);
		});
		
		$("#btOper26Nat").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccionNat')?>').val();
			var post = actual + " X ";
			$('#<?php echo $this->campoSeguro('direccionNat')?>').val(post);
		});
		
		$("#btOper27Nat").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccionNat')?>').val();
			var post = actual + " Y ";
			$('#<?php echo $this->campoSeguro('direccionNat')?>').val(post);
		});
		
		$("#btOper28Nat").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccionNat')?>').val();
			var post = actual + " Z ";
			$('#<?php echo $this->campoSeguro('direccionNat')?>').val(post);
		});
		
		$("#btOper14Nat").click(function(){
			var actual = $('#<?php echo $this->campoSeguro('direccionNat')?>').val();
			if($('#<?php echo $this->campoSeguro('seccionParametrosNat')?>' + ' option:selected').html() != 'Nomenclatura'){
				var post = actual + ' ' + $('#<?php echo $this->campoSeguro('seccionParametrosNat')?>' + ' option:selected').html() + ' ';
				$('#<?php echo $this->campoSeguro('direccionNat')?>').val(post);
			}
		});
    	 
