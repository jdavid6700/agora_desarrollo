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

?>


//////////////////Función que se ejecuta al seleccionar alguna opción del contexto de la Entidad////////////////////

$("#<?php echo $this->campoSeguro('divisionCIIU')?>").change(function() {


		$("#<?php echo $this->campoSeguro('grupoCIIU')?>").attr('disabled', true);
		$("#<?php echo $this->campoSeguro('grupoCIIU')?>").select2();
		$("#<?php echo $this->campoSeguro('claseCIIU')?>").attr('disabled', true);
		$("#<?php echo $this->campoSeguro('claseCIIU')?>").select2();
		
		$("#<?php echo $this->campoSeguro('claseCIIU')?>").html("");
		$("<option value=''>Seleccione .....</option>").appendTo("#<?php echo $this->campoSeguro('claseCIIU')?>");
		

	if($("#<?php echo $this->campoSeguro('divisionCIIU')?>").val() == ''){

		$("<option value=''>Seleccione .....</option>").appendTo("#<?php echo $this->campoSeguro('grupoCIIU')?>");
		
		$("#<?php echo $this->campoSeguro('grupoCIIU_div')?>").css('display','none');
		 		
	}else{

		$("#<?php echo $this->campoSeguro('grupoCIIU')?>").html("");
		$("<option value=''>Seleccione .....</option>").appendTo("#<?php echo $this->campoSeguro('grupoCIIU')?>");
		consultarCiudad();
		
		$("#<?php echo $this->campoSeguro('grupoCIIU_div')?>").css('display','block'); 
		
		$("#<?php echo $this->campoSeguro('grupoCIIU')?>").removeAttr('disabled');
		
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
		
		$("#<?php echo $this->campoSeguro('claseCIIU')?>").removeAttr('disabled');
		
		$("#<?php echo $this->campoSeguro('claseCIIU')?>").select2();
		
	}
	
});

	function consultarNBC(elem, request, response){
		  $.ajax({
		    url: "<?php echo $urlFinal23?>",
		    dataType: "json",
		    data: { valor:$("#<?php echo $this->campoSeguro('objetoArea')?>").val()},
		    success: function(data){ 
		        if(data[0]!=" "){
		            $("#<?php echo $this->campoSeguro('objetoNBC')?>").html('');
		            $("<option value=''>Seleccione  ....</option>").appendTo("#<?php echo $this->campoSeguro('objetoNBC')?>");
		            $.each(data , function(indice,valor){
		            	$("<option value='"+data[ indice ].id_nucleo+"'>"+data[ indice ].nombre+"</option>").appendTo("#<?php echo $this->campoSeguro('objetoNBC')?>");
		            	
		            });
		            
		            $("#<?php echo $this->campoSeguro('objetoNBC')?>").removeAttr('disabled');
		            
		            $("#<?php echo $this->campoSeguro('objetoNBC')?>").select2();
		            
		            $("#<?php echo $this->campoSeguro('objetoArea')?>").removeClass("validate[required]");
		            
		            
		            
			        }
		    			
		    }
			                    
		   });
		};
		
	
	
				$("#<?php echo $this->campoSeguro('objetoArea')?>").change(function(){
		        	if($("#<?php echo $this->campoSeguro('objetoArea')?>").val()!=''){
		            	consultarNBC();
		    		}else{
		    			$("#<?php echo $this->campoSeguro('objetoArea')?>").addClass("validate[required]");
		    			$("#<?php echo $this->campoSeguro('objetoNBC')?>").attr('disabled','');
		    			}
		    	});
		    	      
		    	$("#<?php echo $this->campoSeguro('objetoNBC')?>").change(function(){
		        	if($("#<?php echo $this->campoSeguro('objetoNBC')?>").val()!=''){
		            	$("#<?php echo $this->campoSeguro('objetoNBC')?>").removeClass("validate[required]");
		    		}else{
		    			$("#<?php echo $this->campoSeguro('objetoNBC')?>").addClass("validate[required]");
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
				
				
				$("#<?php echo $this->campoSeguro('claseCIIU')?>").html("");
				$("<option value=''>Seleccione .....</option>").appendTo("#<?php echo $this->campoSeguro('claseCIIU')?>");
			}
		}
	});
};
///////////////////////////////////////////////////////////////////////////////////// 
    	 


if($("#<?php echo $this->campoSeguro('contexto')?>").val() != '' ){
	$('#<?php echo $this->campoSeguro('pais')?>').width(470);
	$("#<?php echo $this->campoSeguro('pais')?>").select2();
}

if($("#<?php echo $this->campoSeguro('pais')?>").val() != '' ){
	$('#<?php echo $this->campoSeguro('ciudad')?>').width(470);
	$("#<?php echo $this->campoSeguro('ciudad')?>").select2();
}
