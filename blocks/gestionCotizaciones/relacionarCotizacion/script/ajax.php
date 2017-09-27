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
$cadenaACodificar29 = "pagina=" . $this->miConfigurador->getVariableConfiguracion("pagina");
$cadenaACodificar29 .= "&procesarAjax=true";
$cadenaACodificar29 .= "&action=index.php";
$cadenaACodificar29 .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificar29 .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificar29 .= $cadenaACodificar29 . "&funcion=consultarUnidad";
$cadenaACodificar29 .= "&tiempo=" . $_REQUEST ['tiempo'];

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");

$cadena29 = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar29, $enlace);

// URL definitiva
$urlFinal29 = $url . $cadena29;


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
$cadenaACodificar24 .= $cadenaACodificar24 . "&funcion=consultarCIIUPush";
$cadenaACodificar24 .= "&tiempo=" . $_REQUEST ['tiempo'];

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion ( "enlace" );

$cadena24 = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $cadenaACodificar24, $enlace );

// URL definitiva
$urlFinal24 = $url . $cadena24;

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
		    	
		    	
		   
		   
		   $("#<?php echo $this->campoSeguro('tipoFormaPago')?>").change(function(){
		        	if($("#<?php echo $this->campoSeguro('tipoFormaPago')?>").val() == 1){
		            	$('#valoresForma').fadeOut(300 , function (){
							$("#<?php echo $this->campoSeguro('valorFormaPago') ?>").val('100');
                        	$("#<?php echo $this->campoSeguro('porcentajePagoForma') ?>").val('100');
						});
		            	
		    		}else if($("#<?php echo $this->campoSeguro('tipoFormaPago')?>").val() == 2){
		    			$('#valoresForma').fadeIn(300);
		    			$('#valorAvance').fadeIn(300);
		    			$("#<?php echo $this->campoSeguro('valorFormaPago') ?>").val('');
                        $("#<?php echo $this->campoSeguro('porcentajePagoForma') ?>").val('');
		    		}else{
		    			$('#valoresForma').fadeIn(300);
		    			$('#valorAvance').fadeOut(300 , function (){
		    				$("#<?php echo $this->campoSeguro('valorFormaPago') ?>").val('0');
		    			});
                        $("#<?php echo $this->campoSeguro('porcentajePagoForma') ?>").val('');
		    		}
		    });
		   
		   
		    	
		    	
			 $("#botonAgregar").click(function(){
			 
		    		
		    	if ($("#<?php echo $this->campoSeguro('tipoFormaPago') ?>").val() != '' &&
		    		$("#<?php echo $this->campoSeguro('valorFormaPago') ?>").val() != '' &&
		    		$("#<?php echo $this->campoSeguro('porcentajePagoForma') ?>").val() != '' &&
		    		$("#<?php echo $this->campoSeguro('porcentajePagoForma') ?>").val() > 0) {
		    		
		    		
		    		if( $.isNumeric($("#<?php echo $this->campoSeguro('valorFormaPago') ?>").val()) ){
		    			
		    			if(($("#<?php echo $this->campoSeguro('tipoFormaPago') ?>").val() == 2 && 	
		    				$("#<?php echo $this->campoSeguro('valorFormaPago') ?>").val() <= 100) ||
		    				$("#<?php echo $this->campoSeguro('tipoFormaPago') ?>").val() == 1 || 
		    				$("#<?php echo $this->campoSeguro('tipoFormaPago') ?>").val() == 3){
		    			
		    				$("#<?php echo $this->campoSeguro('valorFormaPago') ?>").css('border-color','#DDDDDD');
		    		
		    			if( $.isNumeric($("#<?php echo $this->campoSeguro('porcentajePagoForma') ?>").val()) && 
		    			    $("#<?php echo $this->campoSeguro('porcentajePagoForma') ?>").val() <= 100){
		    			
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
							
							
							if(($("#<?php echo $this->campoSeguro('tipoFormaPago') ?>").val() != 3 &&
							$("#<?php echo $this->campoSeguro('valorFormaPago') ?>").val() > 0) ||
							$("#<?php echo $this->campoSeguro('tipoFormaPago') ?>").val() == 3){
							
								$("#<?php echo $this->campoSeguro('valorFormaPago') ?>").css('border-color','#DDDDDD');
							
								if ($("#<?php echo $this->campoSeguro('tipoFormaPago') ?>").val() != '') {
									consultarTipoFormaPagoPush();
	
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
		    var totalPago = 0;
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
		        											tipoValor = " % Completado"
		        										}else if(data[0][0] == 2){
		        											tipoValor = " % Completado del Total";
		        										}else{
		        											tipoValor = " - Sin Condición de Avance";
		        										}
		        										
		        																	
                                                       var nuevaFila="<tr id=\"nFilas\">";
                                                              nuevaFila+="<td>"+(data[0][0])+" - "+(data[0][1])+"</td>";
                                                              nuevaFila+="<td>"+($("#<?php echo $this->campoSeguro('valorFormaPago') ?>").val())+tipoValor+"</td>";
                                                              nuevaFila+="<td>"+($("#<?php echo $this->campoSeguro('porcentajePagoForma') ?>").val())+" %</td>";
                                                              nuevaFila+="<th class=\"eliminarFP\" scope=\"row\"><div class = \"widget\">Eliminar</div></th>";	    
                                                              nuevaFila+="</tr>";
                                                                                        
                                                       

                                                       $("#tablaFP").append(nuevaFila);
                                                       
                                                       if($("#<?php echo $this->campoSeguro('tipoFormaPago') ?>").val() != 3){
                                                       		$("#<?php echo $this->campoSeguro('valorFormaPago') ?>").val('');
                                                       }
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
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
		    	
		    	
		    	
		     var iCnt = 0;   
		     var actividades = new Array(); 	
		    	
		    	
		     $(function(){
                        function eliminarFila(index) {
                          $("#" + index).remove();
                            }
		    	
		    	
		    	$("#<?php echo $this->campoSeguro('claseCIIU')?>").change(function(){
		        	
                        
                          
                     if ($("#<?php echo $this->campoSeguro('claseCIIU') ?>").val() != '') {
								consultarCIIUPush();
                                      <!--            InfoTabla();-->
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
                                                                                        nuevaFila+="<td><strong>"+(data[0][1])+"</strong></td>";
                                                                                        nuevaFila+="<td>"+(data[0][0])+"</td>";
                                                                                        nuevaFila+="<th class=\"eliminar\" scope=\"row\"><div class = \"widget\">Eliminar</div></th>";	    
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
             };
					
                                                                
	
					
					 
		 					/**
					
					         * Funcion para eliminar la ultima columna de la tabla.
					
					         * Si unicamente queda una columna, esta no sera eliminada
					
					         */
					         
					         
					         // Evento que selecciona la fila y la elimina 
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


$("#<?php echo $this->campoSeguro('cotizacionSoporte')?>").change(function (){
         var file = $("#<?php echo $this->campoSeguro('cotizacionSoporte')?>").val();
        var ext = file.substring(file.lastIndexOf("."));
       if(ext != ".pdf" && ext != ".xls" && ext != ".xlsx" && ext != ".doc" &&  ext != ".docx")
         {
         	 swal({
			  title: 'Problema con el Archivo de Soporte',
			  type: 'warning',
			  html:
			    'Por favor cambie el archivo por otro en alguno de los formatos <i>(pdf, xls, xlsx, doc, docx)</i>' ,
			  confirmButtonText:
			    'Ok'
			 })
             $("#<?php echo $this->campoSeguro('cotizacionSoporte')?>").val(null);
         }
     });
     
<?php 

$url = $this->miConfigurador->getVariableConfiguracion ( "host" );
$url .= $this->miConfigurador->getVariableConfiguracion ( "site" );
$url .= "/index.php?";

$cadenaACodificarSolCdp = "pagina=" . $this->miConfigurador->getVariableConfiguracion("pagina");
$cadenaACodificarSolCdp .= "&procesarAjax=true";
$cadenaACodificarSolCdp .= "&action=index.php";
$cadenaACodificarSolCdp .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificarSolCdp .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificarSolCdp .= $cadenaACodificarSolCdp . "&funcion=ObtenersCdps";
$cadenaACodificarSolCdp .= "&tiempo=" . $_REQUEST ['tiempo'];

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");
$cadenaACodificarSolCdp = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificarSolCdp, $enlace);

// URL definitiva
$urlFinalSolCdp = $url . $cadenaACodificarSolCdp;

?>


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

     
//--------------Inicio JavaScript y Ajax Vigencia y Numero Disponibilidad ---------------------------------------------------------------------------------------------    


	$("#<?php echo $this->campoSeguro('unidadEjecutoraCheck') ?>").change(function () {

        if ($("#<?php echo $this->campoSeguro('unidadEjecutoraCheck') ?>").val() != '') {
            $("#<?php echo $this->campoSeguro('vigencia_solicitud_consulta')?>").removeAttr('disabled');
            $("#<?php echo $this->campoSeguro('unidad_ejecutora_hidden') ?>").val($("#<?php echo $this->campoSeguro('unidadEjecutoraCheck') ?>").val());
        } else {
            $("#<?php echo $this->campoSeguro('vigencia_solicitud_consulta') ?>").attr('disabled', '');
        }

    });

    $("#<?php echo $this->campoSeguro('vigencia_solicitud_consulta') ?>").change(function () {

        if ($("#<?php echo $this->campoSeguro('vigencia_solicitud_consulta') ?>").val() != '') {
            $("#<?php echo $this->campoSeguro('numero_disponibilidad') ?>").attr('disabled', '');
            consultarCdps();
        } else {
            $("#<?php echo $this->campoSeguro('vigencia_solicitud_consulta') ?>").attr('disabled', '');
        }

    });

    function consultarCdps(elem, request, response) {

        $.ajax({
            url: "<?php echo $urlFinalSolCdp ?>",
            dataType: "json",
            data: {vigencia: $("#<?php echo $this->campoSeguro('vigencia_solicitud_consulta') ?>").val(),
                unidad: $("#<?php echo $this->campoSeguro('unidad_ejecutora_hidden') ?>").val(),
                cdpseleccion: $("#<?php echo $this->campoSeguro('indices_cdps_vigencias') ?>").val()},
            success: function (data) {

                if (data[0] != " ") {

                    $("#<?php echo $this->campoSeguro('numero_disponibilidad') ?>").html('');
                    $("<option value=''>Seleccione  ....</option>").appendTo("#<?php echo $this->campoSeguro('numero_disponibilidad') ?>");
                    $.each(data, function (indice, valor) {

                        $("<option value='" + data[ indice ].VALOR + "'>" + data[ indice ].INFORMACION + "</option>").appendTo("#<?php echo $this->campoSeguro('numero_disponibilidad') ?>");

                    });

                    $("#<?php echo $this->campoSeguro('numero_disponibilidad') ?>").removeAttr('disabled');

                    $('#<?php echo $this->campoSeguro('numero_disponibilidad') ?>').width(200);
                    $("#<?php echo $this->campoSeguro('numero_disponibilidad') ?>").select2();

                }


            }

        });
    }
    ;

    //--------------Fin JavaScript y Ajax SVigencia y Numero solicitud --------------------------------------------------------------------------------------------------  
    


<?php 


$url = $this->miConfigurador->getVariableConfiguracion ( "host" );
$url .= $this->miConfigurador->getVariableConfiguracion ( "site" );
$url .= "/index.php?";

$cadenaACodificarInfoCDP = "pagina=" . $this->miConfigurador->getVariableConfiguracion("pagina");
$cadenaACodificarInfoCDP .= "&procesarAjax=true";
$cadenaACodificarInfoCDP .= "&action=index.php";
$cadenaACodificarInfoCDP .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificarInfoCDP .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificarInfoCDP .= $cadenaACodificarInfoCDP . "&funcion=ObtenerInfoCdps";
$cadenaACodificarInfoCDP .= "&tiempo=" . $_REQUEST ['tiempo'];

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");
$cadenaACodificarInfoCDP = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificarInfoCDP, $enlace);

// URL definitiva
$urlInfoCDP = $url . $cadenaACodificarInfoCDP;

?>


//---------------------Inicio Ajax Numero de Solicitud de Necesidad------------------


    $("#<?php echo $this->campoSeguro('numero_disponibilidad') ?>").change(function () {
        if ($("#<?php echo $this->campoSeguro('numero_disponibilidad') ?>").val() != '') {
            InfoCDP();
        } else {

        }
    });

    function InfoCDP(elem, request, response) {
        $.ajax({
            url: "<?php echo $urlInfoCDP ?>",
            dataType: "json",
            data: {unidad: $("#<?php echo $this->campoSeguro('unidad_ejecutora_hidden') ?>").val(),
                vigencia: $("#<?php echo $this->campoSeguro('vigencia_solicitud_consulta') ?>").val(),
                numero_disponibilidad: $("#<?php echo $this->campoSeguro('numero_disponibilidad') ?>").val()},
            success: function (data) {
 
                if (data[0] != "") {
                    $('#' + $("#<?php echo $this->campoSeguro('indice_tabla') ?>").val()).html("<td><center>" + data[0].VIGENCIA + "</center></td>\n\\n\
                                   <td><center>" + data[0].NUM_SOL_ADQ + "</center></td>\n\
                                   <td><center>" + data[0].NUMERO_DISPONIBILIDAD + "</center></td>\n\
                                   <td><center>" + data[0].VALOR_CONTRATACION + "</center></td>\n\
                                   <td><center>" + data[0].NOMBRE_DEPENDENCIA + "</center></td>\n\
                                   <td><center>" + data[0].DESCRIPCION + "</center></td>\n\
                                   <td><center>" + data[0].ESTADO + "</center></td>");

                    if ($("#<?php echo $this->campoSeguro('indice_tabla') ?>").val() == 0) {

                        $("#<?php echo $this->campoSeguro('objeto_contrato') ?>").val(data[0].OBJETO);
                        $("#<?php echo $this->campoSeguro('justificacion') ?>").val(data[0].JUSTIFICACION);
                        $("#<?php echo $this->campoSeguro('actividades') ?>").val(data[0].OBSERVACIONES);
                    }

                    var indice = parseFloat($("#<?php echo $this->campoSeguro('indice_tabla') ?>").val()) + 1;
                    $("#<?php echo $this->campoSeguro('indice_tabla') ?>").val(indice);
                    $('#tablacdpasociados').append('<tr id="' + $("#<?php echo $this->campoSeguro('indice_tabla') ?>").val() + '"></tr>');
                    $("#<?php echo $this->campoSeguro('indices_cdps') ?>").val($("#<?php echo $this->campoSeguro('indices_cdps') ?>").val() + "," + data[0].NUM_SOL_ADQ);
                    $("#<?php echo $this->campoSeguro('indices_cdps_vigencias') ?>").val($("#<?php echo $this->campoSeguro('indices_cdps_vigencias') ?>").val() + "," + data[0].NUM_SOL_ADQ + "-" + data[0].VIGENCIA);
                    var acumulado = parseFloat($("#<?php echo $this->campoSeguro('valor_real_acumulado') ?>").val()) + parseFloat(data[0].VALOR_CONTRATACION);
                    $("#<?php echo $this->campoSeguro('valor_real_acumulado') ?>").val(acumulado);
                    acumulado = new Intl.NumberFormat(["ban", "id"]).format(acumulado);
                    $("#<?php echo $this->campoSeguro('valor_acumulado') ?>").val(acumulado);
                    $("#<?php echo $this->campoSeguro('numero_disponibilidad') ?>").attr('disabled', '');
                    $("#<?php echo $this->campoSeguro('vigencia_solicitud_consulta') ?>").val(null);
                    $("#<?php echo $this->campoSeguro('numero_disponibilidad') ?>").val(null);
                    $("#<?php echo $this->campoSeguro('numero_disponibilidad') ?>").select2();
                    $("#<?php echo $this->campoSeguro('vigencia_solicitud_consulta') ?>").select2();

                    $("#<?php echo $this->campoSeguro('valor_contrato') ?>").val($("#<?php echo $this->campoSeguro('valor_real_acumulado') ?>").val());
						
						
					$("#<?php echo $this->campoSeguro('unidadEjecutoraCheck') ?>").attr('disabled', '');
					$("#<?php echo $this->campoSeguro('vigencia_solicitud_consulta') ?>").attr('disabled', '');
					$("#<?php echo $this->campoSeguro('numero_disponibilidad') ?>").attr('disabled', '');
					
					
					$(tinymce.get('<?php echo $this->campoSeguro('objetivo') ?>').getBody()).html('<p><h3>OBJETIVO:</h3>'+data[0].OBJETO+'<br><h3>JUSTIFICACIÓN:</h3>'+data[0].JUSTIFICACION+'</p>');
					$("#<?php echo $this->campoSeguro('objetivo') ?>").val('<p><h3>Objetivo</h3><br><br>'+data[0].OBJETO+'<br><h3>Justificación</h3><br><br>'+data[0].JUSTIFICACION+'</p>');
					
					if(data[0].OBSERVACIONES != null){
						$(tinymce.get('<?php echo $this->campoSeguro('observaciones') ?>').getBody()).html('<p>'+data[0].OBSERVACIONES+'</p>');
						$("#<?php echo $this->campoSeguro('observaciones') ?>").val('<p>'+data[0].OBSERVACIONES+'</p>');
					}else{
						$(tinymce.get('<?php echo $this->campoSeguro('observaciones') ?>').getBody()).html('<p>SIN OBSERVACIONES ADICIONALES</p>');
						$("#<?php echo $this->campoSeguro('observaciones') ?>").val('<p>SIN OBSERVACIONES ADICIONALES</p>');
					}
					
					

                    $("#<?php echo $this->campoSeguro('unidadEjecutora') ?>").val($("#<?php echo $this->campoSeguro('unidadEjecutoraCheck') ?>").val());
                    $("#<?php echo $this->campoSeguro('unidadEjecutora') ?>").select2();
                    
                    $(tinymce.get('<?php echo $this->campoSeguro('tituloCotizacion') ?>').getBody()).html('<p><i>'+data[0].OBJETO + ' - <b>NECESIDAD ('+data[0].VIGENCIA+"-"+data[0].NUM_SOL_ADQ+")"+'</b></i></p>');
					$("#<?php echo $this->campoSeguro('tituloCotizacion') ?>").val('<p><i>'+data[0].OBJETO + ' - <b>NECESIDAD ('+data[0].VIGENCIA+"-"+data[0].NUM_SOL_ADQ+")"+'</b></i></p>');
					
                    $(tinymce.get('<?php echo $this->campoSeguro('plazoEjecucion') ?>').getBody()).html('<p><i>'+data[0].PLAZO_EJECUCION + '</i></p>');
					$("#<?php echo $this->campoSeguro('plazoEjecucion') ?>").val('<p><i>'+data[0].PLAZO_EJECUCION + '</i></p>');
					
                    
                    
                    
                    
                    // Validación CDP Normativa Tipo de Caracteristicas -----------------------------------------------------------------------------------
                    
                    var valorCDP = data[0].VALOR_CONTRATACION;
                    
                    var salario = data[4].valor;
                    
                    var limiteASalario = 100 * salario;
                    var limiteBSalario = 200 * salario;
                    
                    if(valorCDP > limiteBSalario){
                    	
                    	swal({
			                title: 'Información <br>ACUERDO No. 03 de 2015',
			                type: 'error',
			                html:
			                        'De acuerdo con lo estipulado en el ACUERDO 03, se valida que el valor del presente CDP es:<br><br><b>'
			                        + new Intl.NumberFormat(["ban", "id"]).format(valorCDP) + ' pesos Colombianos.</b><br><br>El valor esta por encima del Limite de los <b>200 SMLMV</b> '
			                        + 'por tanto, no se puede realizar la Cotización mediante el Sistema ÁGORA para la compra correspondiente.<br><br>'
			                        + 'Parámetros de la Vigencia,<br><br> Año: 2017 <br> SMLMV: '+ new Intl.NumberFormat(["ban", "id"]).format(salario) +' pesos Colombianos',
			                confirmButtonText:
			                        'Aceptar'
			            })
			            eliminarCDP();
                    	
                    }else if(valorCDP > limiteASalario){
						
						swal.setDefaults({
						  confirmButtonText: 'Siguiente &rarr;',
						  showCancelButton: false,
						  animation: true,
						  progressSteps: ['1', '2']
						})
						
						var steps = [
						  {
						    title: 'Información <br>ACUERDO No. 03 de 2015',
						  	html:
			                        'De acuerdo con lo estipulado en el ACUERDO 03, se valida que el valor del presente CDP es:<br><br><b>'
			                        + new Intl.NumberFormat(["ban", "id"]).format(valorCDP) + ' pesos Colombianos.</b><br><br>El valor esta por encima del Limite de los <b>100 SMLMV</b> '
			                        + 'por tanto, se debe validar lo estipulado en el Artículo 16°.<br><br>'
			                        + 'Parámetros de la Vigencia,<br><br> Año: 2017 <br> SMLMV: '+ new Intl.NumberFormat(["ban", "id"]).format(salario) +' pesos Colombianos',
			                type: 'warning',
						  },
						  {
						    title: 'Información <br>ACUERDO No. 03 de 2015',
						    type: 'question',
						  	html:
			                        'Por favor, responda lo siguiente:<br><br>'
			                        + 'La Cotización se requiere para Productos de Bienes y Servicios de Caracteristicas Técnicas Uniformes y de Común Utilización',
			                input: 'select',
							inputOptions: {
							    '1': 'Si',
							    '2': 'No'
							}
						  }
						]
						
						swal.queue(steps).then(function (result) {
						  swal.resetDefaults()
						
						  if(result[1] == 1){
						  
						  	swal({
						  	
						  		title: 'Información <br>ACUERDO No. 03 de 2015',
				                type: 'error',
				                html:
				                        'De acuerdo con lo estipulado en el Articulo 16° del ACUERDO 03 y '
				                        + 'dado que se categorizó como Productos de Bienes y Servicios de Caracteristicas Técnicas Uniformes y de Común Utilización '
				                        + 'no se puede realizar la Cotización mediante el Sistema ÁGORA para la compra correspondiente.<br><br>Debe ser realizado mediante (Acuerdo Marco de Precios, Bolsa de Productos o Subasta Inversa).<br><br>'
				                        + 'Parámetros de la Vigencia,<br><br> Año: 2017 <br> SMLMV: '+ new Intl.NumberFormat(["ban", "id"]).format(salario) +' pesos Colombianos',
				                confirmButtonText:
				                        'Aceptar'
							        //JSON.stringify(result) +
							  })
						  	
						  	eliminarCDP();
						  }else{
						  
						  	  swal({
						  	
						  		title: 'Información <br>ACUERDO No. 03 de 2015',
				                type: 'info',
				                html:
				                        'De acuerdo con lo estipulado en el Articulo 16° del ACUERDO 03 y '
				                        + 'dado que se no se categorizó como Productos de Bienes y Servicios de Caracteristicas Técnicas Uniformes y de Común Utilización '
				                        + 'se puede realizar la Cotización mediante el Sistema ÁGORA para la compra correspondiente.<br><br>'
				                        + 'Parámetros de la Vigencia,<br><br> Año: 2017 <br> SMLMV: '+ new Intl.NumberFormat(["ban", "id"]).format(salario) +' pesos Colombianos',
				                confirmButtonText:
				                        'Aceptar'
							        //JSON.stringify(result) +
							  })
						  
						  	$('#marcoDatosSolicitudCot').fadeIn(500);
						  }
						  
						}, function () {
						  	swal.resetDefaults()
						})
                    
                    }else{
                    	
                    	swal({
			                title: 'Información <br>ACUERDO No. 03 de 2015',
			                type: 'info',
			                html:
			                        'De acuerdo con lo estipulado en el Articulo 16°, se valida que el valor del presente CDP es:<br><br><b>'
			                        + new Intl.NumberFormat(["ban", "id"]).format(valorCDP) + ' pesos Colombianos.</b><br><br>El valor esta por debajo del Limite de los <b>100 SMLMV</b> '
			                        + 'por tanto, se puede realizar la Cotización mediante el Sistema ÁGORA para la compra correspondiente.<br><br>'
			                        + 'Parámetros de la Vigencia,<br><br> Año: 2017 <br> SMLMV: '+ new Intl.NumberFormat(["ban", "id"]).format(salario) +' pesos Colombianos',
			                confirmButtonText:
			                        'Aceptar'
			            })
			            
			            $('#marcoDatosSolicitudCot').fadeIn(500);
                    
                    }
                    
                    // Fin Validación CDP Normativa Tipo de Caracteristicas -------------------------------------------------------------------------------
                    
                    
                    
                    
					

                }
                
                if(data[1] != ""){
                	var i = 0;
                	var listRequisitos = "";
                	while(i < data[1].length){
                		
                		if(data[1][i].OBSERVACIONES == null){
                			data[1][i].OBSERVACIONES = "Sin observaciones al requisito adicionales";
                		}
                		
                		listRequisitos += "<h3>"+data[1][i].ITEM+"\. "+data[1][i].REQUISITO+"</h3><p style='padding-left: 30px;'>"+data[1][i].OBSERVACIONES+"</p><br>";
                		i++;
                	}
                	
                	$(tinymce.get('<?php echo $this->campoSeguro('requisitos') ?>').getBody()).html('<p>'+listRequisitos+'</p>');
                	$("#<?php echo $this->campoSeguro('requisitos') ?>").val('<p>'+listRequisitos+'</p>');
                
                }else{
                	$(tinymce.get('<?php echo $this->campoSeguro('requisitos') ?>').getBody()).html('<p>NO APLICA<br>LOS REQUISITOS NO FUERON ESTABLECIDOS</p>');
                	$("#<?php echo $this->campoSeguro('requisitos') ?>").val('<p>NO APLICA<br>LOS REQUISITOS NO FUERON ESTABLECIDOS</p>');
                }
                
                if(data[2] != "" && data[3] != ""){
                	
                	var i = 0;
                	var control = false;
                	while(i < data[3].length){
                		if(data[3][i].tercero_id == data[2].NUMERO_DOCUMENTO){
                			$("#<?php echo $this->campoSeguro('ordenador') ?>").val(data[3][i].id);
                    		$("#<?php echo $this->campoSeguro('ordenador') ?>").select2();
                    		$("#<?php echo $this->campoSeguro('ordenador_hidden') ?>").val(data[3][i].id);
                    		control = true;
                		}
                		i++;
                	}
                	
                	if(!control){
                	
                		swal({
			                title: 'Inconsistencia datos SICAPITAL y ÁGORA',
			                type: 'error',
			                html:
			                        'Atención, la información del ordenador del gasto no esta actualizada,'
			                        +' existen inconsistencias con el Sistema ÁGORA, la necesidad N°'+data[0].NUM_SOL_ADQ+' de SICAPITAL presenta'
			                        +' como ordenador de gasto al Señor(a):<br><br> <b>('+data[2].NUMERO_DOCUMENTO+') - '+data[2].NOMBRES+' '+data[2].PRIMER_APELLIDO
			                        +' '+data[2].SEGUNDO_APELLIDO+'</b>, como <b>'+data[2].CARGO+'</b>',
			                confirmButtonText:
			                        'Aceptar'
			            })
			            
			            eliminarCDP();
                	
                	}
                	
                }
                

                if(data[5] != ""){
                	var i = 0;
                	var listFactores = "";
                	while(i < data[5].length){
                		
                		if(data[5][i].SUGERIR_CLASIFICACION == null){
                			data[5][i].SUGERIR_CLASIFICACION = "Sin sugerencias adicionales";
                		}
                		var j = i + 1;
                		listFactores += "<h3>"+j+"\. "+data[5][i].TIPO+"</h3><p style='padding-left: 30px;'>"+data[5][i].SUGERIR_CLASIFICACION+"</p><br>";
                		i++;
                	}
                	
                	$(tinymce.get('<?php echo $this->campoSeguro('criterioSeleccion') ?>').getBody()).html('<p>'+listFactores+'</p>');
                	$("#<?php echo $this->campoSeguro('criterioSeleccion') ?>").val('<p>'+listFactores+'</p>');
                
                }else{
                
                	$("#criteriosEva").hover(function() {
					  alertCriterio();
					  $(this).unbind('mouseenter mouseleave');
					});
                
                }


            }

        });
    }
    ;
    $(document).ready(function () {

        $("#eliminarCDP").click(function () {
			eliminarCDP();
        });

    });
    
    function alertCriterio() {
    	
    	swal({
			                title: 'Importante <br>CRITERIOS DE SELECCIÓN - SICAPITAL',
			                type: 'warning',
			                html:
			                        'Atención, la información de los criterios de selección, no aparece registrada en SICAPITAL,'
			                        +' la información debe ser diligenciada en el campo correspondiente.',
			                confirmButtonText:
			                        'Aceptar'
			            })
    	
    }
    
    function eliminarCDP (){
    
    	    var indice = parseFloat($("#<?php echo $this->campoSeguro('indice_tabla') ?>").val());
            var table = document.getElementById('tablacdpasociados');
            for (var r = 0, n = table.rows.length; r < n; r++) {
                for (var c = 0, m = table.rows[r].cells.length; c < m; c++) {
                    var row = table.rows[r].cells[3].innerHTML;

                }
            }
            row = row.replace("<center>", "");
            row = row.replace("</center>", "");
            var valor = parseFloat(row);
            if (indice > 0) {
                $("#" + (indice - 1)).html('');
                var acumulado = parseFloat($("#<?php echo $this->campoSeguro('valor_real_acumulado') ?>").val()) - valor;
                $("#<?php echo $this->campoSeguro('valor_real_acumulado') ?>").val(acumulado);
                acumulado = new Intl.NumberFormat(["ban", "id"]).format(acumulado);
                if (acumulado != 0) {
                    $("#<?php echo $this->campoSeguro('valor_acumulado') ?>").val(acumulado);
                } else {
                    $("#<?php echo $this->campoSeguro('valor_acumulado') ?>").val("");
                }
                indice = indice - 1;
                $("#<?php echo $this->campoSeguro('indice_tabla') ?>").val(indice);
                var indices = $("#<?php echo $this->campoSeguro('indices_cdps') ?>").val();
                var arregloindices = indices.split(",");
                arregloindices.splice(arregloindices.length - 1, 1);
                arregloindices = arregloindices.toString();
                $("#<?php echo $this->campoSeguro('indices_cdps') ?>").val(arregloindices);

                var indicesvigencias = $("#<?php echo $this->campoSeguro('indices_cdps_vigencias') ?>").val();
                var arregloindicesvigencias = indicesvigencias.split(",");
                arregloindicesvigencias.splice(arregloindicesvigencias.length - 1, 1);
                arregloindicesvigencias = arregloindicesvigencias.toString();
                $("#<?php echo $this->campoSeguro('indices_cdps_vigencias') ?>").val(arregloindicesvigencias);
                $("#<?php echo $this->campoSeguro('valor_contrato') ?>").val($("#<?php echo $this->campoSeguro('valor_real_acumulado') ?>").val());
                $("#<?php echo $this->campoSeguro('numero_disponibilidad') ?>").attr('disabled', '');
                $("#<?php echo $this->campoSeguro('vigencia_solicitud_consulta') ?>").val(null);
                $("#<?php echo $this->campoSeguro('numero_disponibilidad') ?>").val(null);
                $("#<?php echo $this->campoSeguro('numero_disponibilidad') ?>").select2();
                $("#<?php echo $this->campoSeguro('vigencia_solicitud_consulta') ?>").select2();

                if (indice == 0) {
                    $("#<?php echo $this->campoSeguro('objeto_contrato') ?>").val("");
                    $("#<?php echo $this->campoSeguro('justificacion') ?>").val("");
                    $("#<?php echo $this->campoSeguro('actividades') ?>").val("");
                }


            }
            $("#<?php echo $this->campoSeguro('unidadEjecutoraCheck') ?>").val(null);
            $("#<?php echo $this->campoSeguro('unidadEjecutoraCheck') ?>").select2();
            $("#<?php echo $this->campoSeguro('unidadEjecutoraCheck') ?>").removeAttr('disabled');
            $('#marcoDatosSolicitudCot').fadeOut(500);
    
    }
    
//---------------------Fin Ajax Numero de Solicitud de Necesidad------------------  


    $('#<?php echo $this->campoSeguro('tipoItem') ?>').change(function () {

        $("#<?php echo $this->campoSeguro('nombreItem') ?>").val('');
        $("#<?php echo $this->campoSeguro('descripcionItem') ?>").val('');
        $("#<?php echo $this->campoSeguro('cantidadItem') ?>").val('');
        $("#<?php echo $this->campoSeguro('tiempoItem1') ?>").val('0');
        $("#<?php echo $this->campoSeguro('tiempoItem2') ?>").val('0');
        $("#<?php echo $this->campoSeguro('tiempoItem3') ?>").val('0');
        $("#<?php echo $this->campoSeguro('unidadItem') ?>").select2("val", "");

        if ($('#<?php echo $this->campoSeguro('tipoItem') ?>').val() != '') {
            $('#parametros1').fadeIn(500);
            $('#parametros4').fadeIn(500);

            if ($('#<?php echo $this->campoSeguro('tipoItem') ?>').val() == 1) {
                $('#parametros2').fadeIn(500);
                $('#parametros3').fadeOut(100);
            }

            if ($('#<?php echo $this->campoSeguro('tipoItem') ?>').val() == 2) {
                $('#parametros2').fadeOut(100);
                $('#parametros3').fadeIn(500);
            }


        } else {
            $('#parametros1').fadeOut(600);
            $('#parametros2').fadeOut(600);
            $('#parametros3').fadeOut(600);
            $('#parametros4').fadeOut(600);
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

    
    
    $("#botonAgregarItem").click(function () {
    
        if ($("#<?php echo $this->campoSeguro('tipoItem') ?>").val() == 1) {//BIEN
            
            if ($("#<?php echo $this->campoSeguro('nombreItem') ?>").val() != '' &&
                    $("#<?php echo $this->campoSeguro('descripcionItem') ?>").val() != '' &&
                    $("#<?php echo $this->campoSeguro('unidadItem') ?>").val() != '' &&
                    $("#<?php echo $this->campoSeguro('cantidadItem') ?>").val() != '') {


                if ($.isNumeric($("#<?php echo $this->campoSeguro('cantidadItem') ?>").val()) && $("#<?php echo $this->campoSeguro('cantidadItem') ?>").val() > 0) {
                    $("#<?php echo $this->campoSeguro('cantidadItem') ?>").css('border-color', '#DDDDDD');

                        //-----------------------------------------------------------------------------

                        consultarItemPushBien();

                        //-----------------------------------------------------------------------------

                }
            
            }
            
            else {

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
                    $("#<?php echo $this->campoSeguro('cantidadItem') ?>").val() != '' ) {


                if ($.isNumeric($("#<?php echo $this->campoSeguro('cantidadItem') ?>").val()) && $("#<?php echo $this->campoSeguro('cantidadItem') ?>").val() > 0) {

                    $("#<?php echo $this->campoSeguro('cantidadItem') ?>").css('border-color', '#DDDDDD');

                    

                 

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
     var sumadorBienes = 0;
     var sumadorServicios = 0;
     var totalIva = 0;
    var fullParamIt;
    var ContadorNumItem = 0;
    
    function consultarItemPushBien(elem, request, response) {
      
        $.ajax({
            url: "<?php echo $urlFinal29 ?>",
            dataType: "json",
            data: {valor: $("#<?php echo $this->campoSeguro('unidadItem') ?>").val()},
            success: function (data) {




                if (data[0] != "") {

                    var nFilas = $("#tablaFP2 tr").length;
                    var tds = 8;
                    var trs = 8;

                    paramIt.push(data[0][0]);
                    
                    var totalItem=0;
                    var totalItemiva=0;
                         
               
                    var count = nFilas;


                    ContadorNumItem =ContadorNumItem+1;
                    


                    var nuevaFila = "<tr id=\"nFilas\">";
                    nuevaFila += "<td>" + (nFilas)+"</td>";
                    nuevaFila += "<td>" + ($("#<?php echo $this->campoSeguro('nombreItem') ?>").val().toUpperCase()) + "</td>";
                    nuevaFila += "<td>" + ($("#<?php echo $this->campoSeguro('descripcionItem') ?>").val().toUpperCase()) + "</td>";
                    nuevaFila += "<td>1 - BIEN</td>";
                    nuevaFila += "<td>" + (data[0][0]) + " - " + (data[0][1]) + "</td>";
                    nuevaFila += "<td>0 - NO APLICA</td>";
                    nuevaFila += "<td>" + formatearNumero(($("#<?php echo $this->campoSeguro('cantidadItem') ?>").val())) + "</td>";
                    nuevaFila += "<th class=\"eliminarItem\" scope=\"row\"><div class = \"widget\">Eliminar</div></th>";
                    nuevaFila += "</tr>";



                    $("#tablaFP2").append(nuevaFila);


                    $("#<?php echo $this->campoSeguro('nombreItem') ?>").val('');
                    $("#<?php echo $this->campoSeguro('descripcionItem') ?>").val('');
                    $("#<?php echo $this->campoSeguro('cantidadItem') ?>").val('');
                    $("#<?php echo $this->campoSeguro('tiempoItem1') ?>").val('0');
                    $("#<?php echo $this->campoSeguro('tiempoItem2') ?>").val('0');
                    $("#<?php echo $this->campoSeguro('tiempoItem3') ?>").val('0');
                    $("#<?php echo $this->campoSeguro('unidadItem') ?>").select2("val", "");
                    $("#<?php echo $this->campoSeguro('tipoItem') ?>").select2("val", -1);
                    $('#parametros1').fadeOut(600);
                    $('#parametros2').fadeOut(600);
                    $('#parametros3').fadeOut(600);
                    $('#parametros4').fadeOut(600);



                    fullParamIt = "";
                    $('#tablaFP2 tr').each(function () {

                        /* Obtener todas las celdas */
                        var celdas = $(this).find('td');

                        /* Mostrar el valor de cada celda */
                        celdas.each(function () {
                            fullParamIt += String($(this).html()) + "&";
                        });


                    });

                    $("#<?php echo $this->campoSeguro('idsItems') ?>").val(fullParamIt);
                    
                    

                    $("#<?php echo $this->campoSeguro('countItems') ?>").val(nFilas);
                    
                    sumadorBienes = sumadorBienes + 1;
                    
                    if(sumadorBienes>0 && sumadorServicios>0){
                         $("#<?php echo $this->campoSeguro('tipoNecesidad') ?>").select2('val', 3);
                         
                    }
                    else{
                      if(sumadorBienes>0){
                        $("#<?php echo $this->campoSeguro('tipoNecesidad') ?>").select2('val', 1);
                      }
                      else{
                        if(sumadorServicios>0){
                            $("#<?php echo $this->campoSeguro('tipoNecesidad') ?>").select2('val', 2);
                        }
                        else{
                           $("#<?php echo $this->campoSeguro('tipoNecesidad') ?>").select2('val', null);
                        }
                      }
                    }
                    
           
                          
                }


            }

        });

    }
    ;
    
    
    function consultarItemPushServicio() {

        var countDays = totalDias(parseInt($("#<?php echo $this->campoSeguro('tiempoItem1') ?>").val()),
                parseInt($("#<?php echo $this->campoSeguro('tiempoItem2') ?>").val()),
                parseInt($("#<?php echo $this->campoSeguro('tiempoItem3') ?>").val()));

        var nFilas = $("#tablaFP2 tr").length;
        var tds = 8;
        var trs = 8;



        var totalItem=0;
        

    

        var count = nFilas;





        var nuevaFila = "<tr id=\"nFilas\">";
        nuevaFila += "<td>" + (nFilas)+"</td>";
        nuevaFila += "<td>" + ($("#<?php echo $this->campoSeguro('nombreItem') ?>").val().toUpperCase()) + "</td>";
        nuevaFila += "<td>" + ($("#<?php echo $this->campoSeguro('descripcionItem') ?>").val().toUpperCase()) + "</td>";
        nuevaFila += "<td>2 - SERVICIO</td>";
        nuevaFila += "<td>0 - NO APLICA</td>";
        nuevaFila += "<td>" + (inverseTotalDias(countDays)) + "</td>";
        nuevaFila += "<td>" + formatearNumero(($("#<?php echo $this->campoSeguro('cantidadItem') ?>").val())) + "</td>";
        nuevaFila += "<th class=\"eliminarItem\" scope=\"row\"><div class = \"widget\">Eliminar</div></th>";
        nuevaFila += "</tr>";



        $("#tablaFP2").append(nuevaFila);


        $("#<?php echo $this->campoSeguro('nombreItem') ?>").val('');
        $("#<?php echo $this->campoSeguro('descripcionItem') ?>").val('');
        $("#<?php echo $this->campoSeguro('cantidadItem') ?>").val('');
        $("#<?php echo $this->campoSeguro('tiempoItem1') ?>").val('0');
        $("#<?php echo $this->campoSeguro('tiempoItem2') ?>").val('0');
        $("#<?php echo $this->campoSeguro('tiempoItem3') ?>").val('0');
        $("#<?php echo $this->campoSeguro('unidadItem') ?>").select2("val", "");
        $("#<?php echo $this->campoSeguro('tipoItem') ?>").select2("val", -1);
        $('#parametros1').fadeOut(600);
        $('#parametros2').fadeOut(600);
        $('#parametros3').fadeOut(600);
 
        $('#parametros4').fadeOut(600);



        fullParamIt = "";
        $('#tablaFP2 tr').each(function () {

            /* Obtener todas las celdas */
            var celdas = $(this).find('td');

            /* Mostrar el valor de cada celda */
            celdas.each(function () {
                fullParamIt += String($(this).html()) + "&";
            });


        });

        $("#<?php echo $this->campoSeguro('idsItems') ?>").val(fullParamIt);
        $("#<?php echo $this->campoSeguro('countItems') ?>").val(nFilas);

        sumadorServicios = sumadorServicios + 1;
                    
                                    if(sumadorBienes>0 && sumadorServicios>0){
                                         $("#<?php echo $this->campoSeguro('tipoNecesidad') ?>").select2('val', 3);
                                    }
                                    else{
                                      if(sumadorBienes>0){
                                        $("#<?php echo $this->campoSeguro('tipoNecesidad') ?>").select2('val', 1);
                                      }
                                      else{
                                        if(sumadorServicios>0){
                                            $("#<?php echo $this->campoSeguro('tipoNecesidad') ?>").select2('val', 2);
                                        }
                                        else{
                                           $("#<?php echo $this->campoSeguro('tipoNecesidad') ?>").select2('val', null);
                                        }
                                      }
                                    }

    }
    ;
    
    
     // Evento que selecciona la fila y la elimina 
    $(document).on("click", ".eliminarItem", function () {

        var parent = $(this).parents().get(0);
        var element = $(parent).text();
        var restaItemiva;
        var restaValor;

        var celdas = $(parent).find('td');

        var tipo_item = String($(celdas[3]).html());
        
        $(parent).remove();

        fullParamIt = "";
        var contNumeral=0;
        $('#tablaFP2 tr').each(function () {

            /* Obtener todas las celdas */
            var celdas = $(this).find('td');
            
            $(celdas[0]).text(contNumeral);

            /* Mostrar el valor de cada celda */
            celdas.each(function () {
                fullParamIt += String($(this).html()) + "&";
            });
            contNumeral = contNumeral+1;

        });

        $("#<?php echo $this->campoSeguro('idsItems') ?>").val(fullParamIt);
      

        var countF = $("#tablaFP2 tr").length - 1;

        $("#<?php echo $this->campoSeguro('countItems') ?>").val(countF);


        if(tipo_item === '1 - BIEN'){
            sumadorBienes = sumadorBienes -1;
        }
        if(tipo_item === '2 - SERVICIO')
            {
                sumadorServicios = sumadorServicios - 1; 
            }
            if(sumadorBienes>0 && sumadorServicios>0){
                                         $("#<?php echo $this->campoSeguro('tipoNecesidad') ?>").select2('val', 3);
                                    }
                                    else{
                                      if(sumadorBienes>0){
                                        $("#<?php echo $this->campoSeguro('tipoNecesidad') ?>").select2('val', 1);
                                      }
                                      else{
                                        if(sumadorServicios>0){
                                            $("#<?php echo $this->campoSeguro('tipoNecesidad') ?>").select2('val', 2);
                                        }
                                        else{
                                           $("#<?php echo $this->campoSeguro('tipoNecesidad') ?>").select2('val', null);
                                        }
                                      }
                                    }

        
    });


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
                        
                      
                        
                        if (($("#<?php echo $this->campoSeguro('permisoItem') ?>").val() == 'ambos' && data[indice].tipo.toUpperCase() == '1 - BIEN')) {
                            var nFilas = $("#tablaFP2 tr").length;

                            var count = nFilas;
                     

                            var nuevaFila = "<tr id=\"nFilas\">";
                            nuevaFila += "<td>" + (nFilas)+"</td>";
                            nuevaFila += "<td>" + (data[indice].nombre.toUpperCase()) + "</td>";
                            nuevaFila += "<td>" + (data[indice].descripcion.toUpperCase()) + "</td>";
                            nuevaFila += "<td>1 - BIEN</td>";
                            nuevaFila += "<td>" + (data[indice].unidad) + "</td>";
                            nuevaFila += "<td>" + "0 - NO APLICA" + "</td>";
                            nuevaFila += "<td>" + formatearNumero(data[indice].cantidad) + "</td>";
                            nuevaFila += "<th class=\"eliminarItem\" scope=\"row\"><div class = \"widget\">Eliminar</div></th>";
                            nuevaFila += "</tr>";

                            $("#tablaFP2").append(nuevaFila);
                            
                            fullParamIt = "";
                            $('#tablaFP2 tr').each(function () {

                                /* Obtener todas las celdas */
                                var celdas = $(this).find('td');

                                /* Mostrar el valor de cada celda */
                                celdas.each(function () {
                                    fullParamIt += String($(this).html()) + "&";
                                });


                            });
                            
                            

                            
                           
                          

                            $("#<?php echo $this->campoSeguro('idsItems') ?>").val(fullParamIt);

                            $("#<?php echo $this->campoSeguro('countItems') ?>").val(nFilas);
                            
                            sumadorBienes = sumadorBienes + 1;
                    
                                    if(sumadorBienes>0 && sumadorServicios>0){
                                         $("#<?php echo $this->campoSeguro('tipoNecesidad') ?>").select2('val', 3);
                                    }
                                    else{
                                      if(sumadorBienes>0){
                                        $("#<?php echo $this->campoSeguro('tipoNecesidad') ?>").select2('val', 1);
                                      }
                                      else{
                                        if(sumadorServicios>0){
                                            $("#<?php echo $this->campoSeguro('tipoNecesidad') ?>").select2('val', 2);
                                        }
                                        else{
                                           $("#<?php echo $this->campoSeguro('tipoNecesidad') ?>").select2('val', null);
                                        }
                                      }
                                    }

                        }
                        
                            if (($("#<?php echo $this->campoSeguro('permisoItem') ?>").val() == 'ambos' && data[indice].tipo.toUpperCase() == '2 - SERVICIO')) {
                            var nFilas = $("#tablaFP2 tr").length;
                            
                             var countDays = totalDias(parseInt(data[indice].tiempo_ejecucion_ano),
                                    parseInt(data[indice].tiempo_ejecucion_mes),
                                    parseInt(data[indice].tiempo_ejecucion_dia));

                            var count = nFilas;
                


                            var nuevaFila = "<tr id=\"nFilas\">";
                            nuevaFila += "<td>" + (nFilas)+"</td>";
                            nuevaFila += "<td>" + (data[indice].nombre.toUpperCase()) + "</td>";
                            nuevaFila += "<td>" + (data[indice].descripcion.toUpperCase()) + "</td>";
                            nuevaFila += "<td>2 - SERVICIO</td>";
                            nuevaFila += "<td>" + (data[indice].unidad) + "</td>";
                            nuevaFila += "<td>" + (inverseTotalDias(countDays)) + "</td>";
                            nuevaFila += "<td>" + formatearNumero(data[indice].cantidad) + "</td>";
                            nuevaFila += "<th class=\"eliminarItem\" scope=\"row\"><div class = \"widget\">Eliminar</div></th>";
                            nuevaFila += "</tr>";

                            $("#tablaFP2").append(nuevaFila);
                            
                            fullParamIt = "";
                            $('#tablaFP2 tr').each(function () {

                                /* Obtener todas las celdas */
                                var celdas = $(this).find('td');

                                /* Mostrar el valor de cada celda */
                                celdas.each(function () {
                                    fullParamIt += String($(this).html()) + "&";
                                });


                            });
                            
                            

                            
                           
                          

                            $("#<?php echo $this->campoSeguro('idsItems') ?>").val(fullParamIt);

                            $("#<?php echo $this->campoSeguro('countItems') ?>").val(nFilas);
                                sumadorServicios = sumadorServicios + 1;
                    
                                    if(sumadorBienes>0 && sumadorServicios>0){
                                         $("#<?php echo $this->campoSeguro('tipoNecesidad') ?>").select2('val', 3);
                                    }
                                    else{
                                      if(sumadorBienes>0){
                                        $("#<?php echo $this->campoSeguro('tipoNecesidad') ?>").select2('val', 1);
                                      }
                                      else{
                                        if(sumadorServicios>0){
                                            $("#<?php echo $this->campoSeguro('tipoNecesidad') ?>").select2('val', 2);
                                        }
                                        else{
                                           $("#<?php echo $this->campoSeguro('tipoNecesidad') ?>").select2('val', null);
                                        }
                                      }
                                    }
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

    $("#<?php echo $this->campoSeguro('tipoNecesidad')?>").change(function() {
    
       
         if(sumadorBienes>0 && sumadorServicios>0){
                         $("#<?php echo $this->campoSeguro('tipoNecesidad') ?>").select2('val', 3);
                         
                    }
                    else{
                      if(sumadorBienes>0){
                        $("#<?php echo $this->campoSeguro('tipoNecesidad') ?>").select2('val', 1);
                      }
                      else{
                        if(sumadorServicios>0){
                            $("#<?php echo $this->campoSeguro('tipoNecesidad') ?>").select2('val', 2);
                        }
                        else{
                           $("#<?php echo $this->campoSeguro('tipoNecesidad') ?>").select2('val', null);
                        }
                      }
                    }
    });
   

<?php 


$url = $this->miConfigurador->getVariableConfiguracion ( "host" );
$url .= $this->miConfigurador->getVariableConfiguracion ( "site" );
$url .= "/index.php?";

$cadenaACodificarInfoTipoCon = "pagina=" . $this->miConfigurador->getVariableConfiguracion("pagina");
$cadenaACodificarInfoTipoCon .= "&procesarAjax=true";
$cadenaACodificarInfoTipoCon .= "&action=index.php";
$cadenaACodificarInfoTipoCon .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificarInfoTipoCon .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificarInfoTipoCon .= $cadenaACodificarInfoTipoCon . "&funcion=formaDePagoAjax";
$cadenaACodificarInfoTipoCon .= "&tiempo=" . $_REQUEST ['tiempo'];

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");
$cadenaACodificarInfoTipoCon = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificarInfoTipoCon, $enlace);

// URL definitiva
$urlInfoTipoCon = $url . $cadenaACodificarInfoTipoCon;

?>



$("#<?php echo $this->campoSeguro('tipoContrato')?>").change(function() {
	if($("#<?php echo $this->campoSeguro('tipoContrato')?>").val() == ''){
		$("#<?php echo $this->campoSeguro('tipoFormaPago')?>").html("");
		$("<option value=''>Seleccione .....</option>").appendTo("#<?php echo $this->campoSeguro('tipoFormaPago')?>");
		$("#<?php echo $this->campoSeguro('tipoFormaPago')?>").addAttr('disabled');
		$("#<?php echo $this->campoSeguro('tipoFormaPago')?>").select2();
	}else{
		consultarPago();
		$("#<?php echo $this->campoSeguro('tipoFormaPago')?>").removeAttr('disabled');
		$("#<?php echo $this->campoSeguro('tipoFormaPago')?>").select2();
	}
});


function consultarPago(elem, request, response){
	$.ajax({
		url: "<?php echo $urlInfoTipoCon?>",
		dataType: "json",
		data: { valor:$("#<?php echo $this->campoSeguro('tipoContrato')?>").val()},
		success: function(data){
			if(data[0]!=" "){
				$("#<?php echo $this->campoSeguro('tipoFormaPago')?>").html("");
				$("<option value=''>Seleccione .....</option>").appendTo("#<?php echo $this->campoSeguro('tipoFormaPago')?>");
				$.each(data , function(indice,valor){
					$("<option value='"+data[ indice ].id+"'>"+data[ indice ].nombre+"</option>").appendTo("#<?php echo $this->campoSeguro('tipoFormaPago')?>");
				});
			}
		}
	});
};


$("#<?php echo $this->campoSeguro('medioPago')?>").change(function() {
	if($("#<?php echo $this->campoSeguro('medioPago')?>").val() == 3){
		
		swal({
                title: 'Importante<br>MEDIO DE PAGO',
                type: 'info',
                html:
                        'Recuerde que el Pago mediante TARJETA DE CRÉDITO, esta regulado según lo dispuesto en la <i>RESOLUCIÓN 067 del 09 de Febrero de 2017</i>',
                confirmButtonText:
                        'Aceptar'
            })
     }

});
