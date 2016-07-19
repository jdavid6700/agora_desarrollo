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
?>

$( "#<?php echo $this->campoSeguro('docente')?>" ).keyup(function() {
	$('#<?php echo $this->campoSeguro('docente') ?>').val($('#<?php echo $this->campoSeguro('docente') ?>').val().toUpperCase());
});

$( "#<?php echo $this->campoSeguro('docente')?>" ).change(function() {
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
	if($('#<?php echo $this->campoSeguro('perfil') ?>').val() == 4){
		$("#obligatorioProfesion").show("fast");
		$("#obligatorioEspecialidad").show("fast");
	}else if ($('#<?php echo $this->campoSeguro('perfil') ?>').val() == 3){
		$("#obligatorioProfesion").show("fast");
		$("#obligatorioEspecialidad").hide("fast");
	}else{
		$("#obligatorioProfesion").hide("fast");
		$("#obligatorioEspecialidad").hide("fast");
	}
});

$( "#<?php echo $this->campoSeguro('perfilNat')?>" ).change(function() {
	if($('#<?php echo $this->campoSeguro('perfilNat') ?>').val() == 4){
		$("#obligatorioProfesionNat").show("fast");
		$("#obligatorioEspecialidadNat").show("fast");
	}else if ($('#<?php echo $this->campoSeguro('perfilNat') ?>').val() == 3){
		$("#obligatorioProfesionNat").show("fast");
		$("#obligatorioEspecialidadNat").hide("fast");
	}else{
		$("#obligatorioProfesionNat").hide("fast");
		$("#obligatorioEspecialidadNat").hide("fast");
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
		
      $(function () {
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
    	 
