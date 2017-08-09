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

//Variables
$cadenaACodificarCIUU = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( "pagina" );
$cadenaACodificarCIUU .= "&procesarAjax=true";
$cadenaACodificarCIUU .= "&action=index.php";
$cadenaACodificarCIUU .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificarCIUU .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificarCIUU .= $cadenaACodificarCIUU . "&funcion=consultarCIIUPush";
$cadenaACodificarCIUU .= "&tiempo=" . $_REQUEST ['tiempo'];

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion ( "enlace" );

$cadenaCIUU = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $cadenaACodificarCIUU, $enlace );

// URL definitiva
$urlInfoClaseCIUU = $url . $cadenaCIUU;


$cadenaACodificarActividad= "pagina=" . $this->miConfigurador->getVariableConfiguracion ( "pagina" );
$cadenaACodificarActividad .= "&procesarAjax=true";
$cadenaACodificarActividad .= "&action=index.php";
$cadenaACodificarActividad .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificarActividad .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificarActividad .= $cadenaACodificarCIUU . "&funcion=consultarActividad";
$cadenaACodificarActividad .= "&tiempo=" . $_REQUEST ['tiempo'];

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion ( "enlace" );

$cadenaActividad = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $cadenaACodificarActividad, $enlace );

// URL definitiva
$urlFinalActividad = $url . $cadenaActividad;


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



//Variables
$cadenaACodificar25 = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( "pagina" );
$cadenaACodificar25 .= "&procesarAjax=true";
$cadenaACodificar25 .= "&action=index.php";
$cadenaACodificar25 .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificar25 .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificar25 .= $cadenaACodificar25 . "&funcion=consultarTipoFormaPago";
$cadenaACodificar25 .= "&tiempo=" . $_REQUEST ['tiempo'];

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion ( "enlace" );

$cadena25 = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $cadenaACodificar25, $enlace );

// URL definitiva
$urlFinal25 = $url . $cadena25;

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
    	 


$("#<?php echo $this->campoSeguro('decision')?>").change(function(){

		        	if($("#<?php echo $this->campoSeguro('decision')?>").val() == 1){
		            	
		            	$('#marcoRespuestaEspecificaPro').fadeIn(500);
		            	
		            	$("#<?php echo $this->campoSeguro('respuestaDet') ?>").val();
		            	$("#<?php echo $this->campoSeguro('respuestaDet')?>").addClass("validate[required]");
		            	$("#<?php echo $this->campoSeguro('decisionPro')?>").addClass("validate[required]");
		            	
		    		}else{
		    			
		    			$('#marcoRespuestaEspecificaPro').fadeOut(500);
		    			
		    			$("#<?php echo $this->campoSeguro('respuestaDet') ?>").val("No seleccionado Proveedor Para Cotización");
		    			$("#<?php echo $this->campoSeguro('respuestaDet')?>").removeClass("validate[required]");
		    			$("#<?php echo $this->campoSeguro('decisionPro')?>").removeClass("validate[required]");
		    			
		    		}
});


if($("#<?php echo $this->campoSeguro('contexto')?>").val() != '' ){
	$('#<?php echo $this->campoSeguro('pais')?>').width(470);
	$("#<?php echo $this->campoSeguro('pais')?>").select2();
}

if($("#<?php echo $this->campoSeguro('pais')?>").val() != '' ){
	$('#<?php echo $this->campoSeguro('ciudad')?>').width(470);
	$("#<?php echo $this->campoSeguro('ciudad')?>").select2();
}
if ($("#<?php echo $this->campoSeguro('idsObjeto') ?>").val() != '') {
  consultarActividadExistente();
}
 var iCnt = 0;  
     var actividades = new Array();
function consultarActividadExistente(elem, request, response){
	$.ajax({
		url: "<?php echo $urlFinalActividad?>",
		dataType: "json",
		data: { valor:$("#<?php echo $this->campoSeguro('idsObjeto')?>").val()},
		success: function(data){
			if(data[0]!=" "){
                                 var nFilas = $("#tabla tr").length;
				$.each(data , function(indice,valor){
					actividades.push(data[indice][0]);
                                        var nuevaFila="<tr id=\"nFilas\">";

                                                                                        nuevaFila+="<td>"+(data[indice][0])+"</td>";
                                                                                        nuevaFila+="<td>"+(data[indice][1])+"</td>";
                                                                                        nuevaFila+="<td>"+(data[indice][2])+"</td>";
                                                                                        nuevaFila+="<th class=\"eliminar\" scope=\"row\">Eliminar</th>";		    
                                                                                        <!--nuevaFila+="<td><input type='button' value='Eliminar' /></td>";-->	
                                                                                        nuevaFila+="</tr>";
                                         $("#tabla").append(nuevaFila);
                                         $("#<?php echo $this->campoSeguro('idsActividades') ?>").val(actividades);
				});
			}
		}
	});
};

    	
  $(function(){
                       
		    	
		    	$("#<?php echo $this->campoSeguro('claseCIIU')?>").change(function(){
		        	
                                 if ($("#<?php echo $this->campoSeguro('claseCIIU') ?>").val() != '') {
                                          consultarCIIUPush();
                                 } else {

                                  }
                        }); 
                        
                           function consultarCIIUPush(elem, request, response) {
                                 $.ajax({
                                            url: "<?php echo $urlInfoClaseCIUU ?>",
                                            dataType: "json",
                                            data: {valor: $("#<?php echo $this->campoSeguro('claseCIIU') ?>").val()},
                                            success: function (data) {  
                                             if (data[0] != "") {
                                                              var nFilas = $("#tabla tr").length;
                                                              var validacion=0;
                                                              $('#tabla tr').each(function(){
                                                                        var celdas = $(this).find('td');

                                                                        if(data[0][2]===$(celdas[0]).html()){
                                                                            validacion=1;
                                                                         }
                                                               });
                                                                if(validacion===0){
                                                                var tds=4;
                                                                var trs=4;
                                                                actividades.push(data[0][2]);
		        	
                                                        var nuevaFila="<tr id=\"nFilas\">";

                                                                                        nuevaFila+="<td>"+(data[0][2])+"</td>";
                                                                                        nuevaFila+="<td>"+(data[0][1])+"</td>";
                                                                                        nuevaFila+="<td>"+(data[0][0])+"</td>";
                                                                                        nuevaFila+="<th class=\"eliminar\" scope=\"row\">Eliminar</th>";		    
                                                                                        <!--nuevaFila+="<td><input type='button' value='Eliminar' /></td>";-->	
                                                                                        nuevaFila+="</tr>";
                                                          $("#<?php echo $this->campoSeguro('idsActividades') ?>").val(actividades);

                                                        $("#tabla").append(nuevaFila);
                                                    }
                                                     else{                                   
                                                        
                                                        swal({
																						  title: 'Problema con Código Actividad Económica CIIU ('+(data[0][2])+')',
																						  type: 'warning',
																						  html:
																						    'La actividad ya se encuentra registrada</br> '+ '</br>' + 
																						    'Actividad Económica: (' + (data[0][2])+') ' + (data[0][1]) ,
																						  confirmButtonText:
																						    'Ok'
																						})

                                                     }
                                                     
                                                     $("#<?php echo $this->campoSeguro('claseCIIU')?>").select2("val", "");
															$("#<?php echo $this->campoSeguro('claseCIIU')?>").removeClass("validate[required]");
                                                              
                                }
                                            }
                                 });
                                 
                                

                           
                           
                           }
                           
                        

               $(document).on("click",".eliminar",function(){
									var parent = $(this).parents().get(0);
									var element = $(parent).text();
									var codigoCIIU = element.substring(0, 4);
									
									
									var index = actividades.indexOf(codigoCIIU);
									
									if (index > -1) {
									    actividades.splice(index, 1);
									}
									
									$("#<?php echo $this->campoSeguro('idsActividades') ?>").val(actividades);
									
									$(parent).remove();
								});
                                                                
            
            });
            
            
                                                                
                                    



			 $("#botonAgregar").click(function(){
			 
		    		
		    	if ($("#<?php echo $this->campoSeguro('tipoFormaPago') ?>").val() != '' &&
		    		$("#<?php echo $this->campoSeguro('valorFormaPago') ?>").val() != '' &&
		    		$("#<?php echo $this->campoSeguro('porcentajePagoForma') ?>").val() != '') {
		    		
		    		
		    		if( $.isNumeric($("#<?php echo $this->campoSeguro('valorFormaPago') ?>").val()) ){
		    			
		    			if(($("#<?php echo $this->campoSeguro('tipoFormaPago') ?>").val() == 2 && 	
		    				$("#<?php echo $this->campoSeguro('valorFormaPago') ?>").val() <= 100) ||
		    				$("#<?php echo $this->campoSeguro('tipoFormaPago') ?>").val() == 1){
		    			
		    				$("#<?php echo $this->campoSeguro('valorFormaPago') ?>").css('border-color','#DDDDDD');
		    		
		    			if( $.isNumeric($("#<?php echo $this->campoSeguro('porcentajePagoForma') ?>").val()) && 
		    			    $("#<?php echo $this->campoSeguro('porcentajePagoForma') ?>").val() < 100){
		    			
		    				$("#<?php echo $this->campoSeguro('porcentajePagoForma') ?>").css('border-color','#DDDDDD');

		   
							
							swal({
							  title: 'Parámetro Forma de Pago',
							  type: 'success',
							  html:
							    'El Parámetro fue agregado correctamente.</br> '+
							    'Porcentaje Agregado: ' + $("#<?php echo $this->campoSeguro('porcentajePagoForma') ?>").val() + '%' ,
							  confirmButtonText:
							    'Ok'
							})
							
							
							//-----------------------------------------------------------------------------
							
							
							if ($("#<?php echo $this->campoSeguro('tipoFormaPago') ?>").val() != '') {
								consultarTipoFormaPagoPush();

                   	 		}
							
							
							//-----------------------------------------------------------------------------
							
		    			
		    			}else{
		    				$("#<?php echo $this->campoSeguro('porcentajePagoForma') ?>").css('border-color','#FF0000');
							
							swal({
							  title: 'Ocurrio un problema...',
							  type: 'error',
							  html:
							    'El Valor de <big>Porcentaje Forma de Pago</big>, no es Númerico. (ERROR) ',
							  confirmButtonText:
							    'Ok'
							})
            				
		    			}
		    		
		    		}else{
		    		
		    			$("#<?php echo $this->campoSeguro('valorFormaPago') ?>").css('border-color','#FF0000');
						
						swal({
						  title: 'Ocurrio un problema...',
						  type: 'error',
						  html:
						    'El Valor de <big>Forma de Pago</big>, es un valor Porcentual Incorrecto. (ERROR) ',
						  confirmButtonText:
						    'Ok'
						})
		    			
		    		}
		    		
		    	}else{
		    			$("#<?php echo $this->campoSeguro('valorFormaPago') ?>").css('border-color','#FF0000');
						
						swal({
						  title: 'Ocurrio un problema...',
						  type: 'error',
						  html:
						    'El Valor de <big>Forma de Pago</big>, no es Númerico. (ERROR) ',
						  confirmButtonText:
						    'Ok'
						})
						
            			
		    	}
		    		

              }else{	
					
					swal({
					  title: 'Ocurrio un problema...',
					  type: 'error',
					  html:
					    'Los Parámetros de <big>Forma de Pago</big>, ' +
					    'están mal diligenciados, No se pudieron agregar.',
					  confirmButtonText:
					    'Ok'
					})
            		
			  }
			});	   
			
			
			
			
			
			var iCntFP = 0;   
		    var paramFP = new Array();
		    var totalPago = 100;
		    var fullParam;  
			
			function consultarTipoFormaPagoPush(elem, request, response) {
                                        $.ajax({
                                            url: "<?php echo $urlFinal25 ?>",
                                            dataType: "json",
                                            data: {valor: $("#<?php echo $this->campoSeguro('tipoFormaPago') ?>").val()},
                                            success: function (data) {                       




                                                 if (data[0] != "") {
                                                             
                                                       var nFilas = $("#tablaFP tr").length;
                                                       var tds = 4;
                                                       var trs = 4;
		        									   var tipoValor;	
		        									   
		        									   var preCarga = totalPago + parseFloat($("#<?php echo $this->campoSeguro('porcentajePagoForma') ?>").val());
		        									   
		        									   if(preCarga == 100){
		        									   		$('#slideThree').prop("checked", true);
		        									   }else{
		        									   		$('#slideThree').prop("checked", false);
		        									   }
		        									   
		        									   if(preCarga > 100){
		        									   		
		        									   		swal({
															  title: 'Se excedio el 100%...',
															  type: 'warning',
															  html:
															    'El Valor de Porcentaje de Pago, Ingresado no es Valido, ' +
															    'Por favor, Validar.',
															  confirmButtonText:
															    'Ok'
															})
															
															$("#<?php echo $this->campoSeguro('valorFormaPago') ?>").val('');
                                                       		$("#<?php echo $this->campoSeguro('porcentajePagoForma') ?>").val('');
															
		        									   
		        									   }else{	
		        									   		
                                                             
                                                            paramFP.push(data[0][0]); 
                                                             
                                                           	totalPago += parseFloat($("#<?php echo $this->campoSeguro('porcentajePagoForma') ?>").val());
                                                           	
                                                           	var count = nFilas;
		        									   		$("#<?php echo $this->campoSeguro('countParam') ?>").val('( '+count+' ) Parámetro(s) Agregado(s)'
		        									   		+' - ( Configurado el '+ totalPago +'% )')					
		        																	
		        										if(data[0][0] == 1){
		        											tipoValor = " días Transcurridos del Inicio"
		        										}else{
		        											tipoValor = " % Completado del Total";
		        										}
		        										
		        																	
                                                       var nuevaFila="<tr id=\"nFilas\">";
                                                              nuevaFila+="<td>"+(data[0][0])+" - "+(data[0][1])+"</td>";
                                                              nuevaFila+="<td>"+($("#<?php echo $this->campoSeguro('valorFormaPago') ?>").val())+tipoValor+"</td>";
                                                              nuevaFila+="<td>"+($("#<?php echo $this->campoSeguro('porcentajePagoForma') ?>").val())+" %</td>";
                                                              nuevaFila+="<th class=\"eliminarFP\" scope=\"row\"><div class = \"widget\">Eliminar</div></th>";	    
                                                              nuevaFila+="</tr>";
                                                                                        
                                                       

                                                       $("#tablaFP").append(nuevaFila);
                                                       
                                                       
                                                       $("#<?php echo $this->campoSeguro('valorFormaPago') ?>").val('');
                                                       $("#<?php echo $this->campoSeguro('porcentajePagoForma') ?>").val('');
                                                       
                                                       fullParam = "";
                                                       $('#tablaFP tr').each(function(){
 
														        /* Obtener todas las celdas */
														        var celdas = $(this).find('td');
														 		
														        /* Mostrar el valor de cada celda */
														        celdas.each(function(){
														        	fullParam += String($(this).html())+"&"; 
														        });
														 
														 
													   });
													   
													   $("#<?php echo $this->campoSeguro('idsFormaPago') ?>").val(fullParam);
													   
                                					   $("#<?php echo $this->campoSeguro('changeFormaPago') ?>").val(true);
                                					   
                									}

                                 				}


                     				 }

                         });
			
			};
			
			
			/**
					
					         * Funcion para eliminar la ultima columna de la tabla.
					
					         * Si unicamente queda una columna, esta no sera eliminada
					
					         */
					         
					         
					         // Evento que selecciona la fila y la elimina 
								$(document).on("click",".eliminarFP",function(){
								
								
									var parent = $(this).parents().get(0);
									var element = $(parent).text();
									
									var celdas = $(parent).find('td');
														 		
								
									
									
									var cadena = String($(celdas[2]).html()),
    									separador = " ", // un espacio en blanco
    									limite    = 2,
    								quickPago = cadena.split(separador, limite);
									
									
									totalPago = totalPago - parseFloat(quickPago[0]);
									
									
									var nFilas = $("#tablaFP tr").length;
									
									var count = nFilas - 2;
									
									if(totalPago < 0){
										totalPago = 0;
									}
									
		        					$("#<?php echo $this->campoSeguro('countParam') ?>").val('( '+count+' ) Parámetro(s) Agregado(s)'
		        					+' - ( Configurado el '+ totalPago +'% )');
									
									if(totalPago == 100){
		        						$('#slideThree').prop("checked", true);
		        					}else{
		        						$('#slideThree').prop("checked", false);
		        					}
									
									$(parent).remove();

							});
		
		
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
	
	$("#<?php echo $this->campoSeguro('precioCot') ?>").val("$ " + currency($("#<?php echo $this->campoSeguro('precioCarga')?>").val(), 0) + " pesos (COP)");	
	
	
			
			                                              