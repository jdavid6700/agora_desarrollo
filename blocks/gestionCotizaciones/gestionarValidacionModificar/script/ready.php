
window.onload = detectarCarga;
function detectarCarga(){

	$('#marcoDatosLoad').fadeOut(1000, function (){
		$('#marcoDatosSolicitudCot').fadeIn(500);
		$('#marcoDatosCotizacionList').fadeIn(500);
		$('#marcoDatosCotizacionListPer').fadeIn(500);
		$('#marcoContratos').fadeIn(500);
		$('#AgrupacionDisponibilidadNec').fadeIn(500);
		
	});
	
	if ( $('#<?php echo $this->campoSeguro('unidadPresupuestal')?>').length &&
		 $('#<?php echo $this->campoSeguro('criterioSeleccion')?>').length &&
		 $('#<?php echo $this->campoSeguro('plazoEjecucion')?>').length &&
		 $('#<?php echo $this->campoSeguro('observaciones')?>').length
	   ) {
	   
	   
	    if($('#<?php echo $this->campoSeguro('unidadPresupuestal')?>').val() == 1){
        	InfoCDPMod();
		}
		
		tinyMCE.get('<?php echo $this->campoSeguro('criterioSeleccion')?>').theme.resizeTo("100%", 150);
		tinyMCE.get('<?php echo $this->campoSeguro('plazoEjecucion')?>').theme.resizeTo("100%", 50);
		
		tinyMCE.get('<?php echo $this->campoSeguro('observaciones')?>').theme.resizeTo("100%", 60);
	   
	   
	}
	
	
	
	if($('#<?php echo $this->campoSeguro('countObservacionesByPro') ?>').val() > 0){
		
		
		var tempj = $('#<?php echo $this->campoSeguro('countObservacionesByPro') ?>').val();
		var contj = 0;
		
		while(contj < tempj){
		
			var temp = $('#countObservaciones'+contj).val();
			var cont = 0;
			
			while(cont < temp){
			
				tinyMCE.get('observacion'+contj+cont).theme.resizeTo("80%", 100);
				
				tinyMCE.get('observacion'+contj+cont).setMode('readonly');
	
				cont++;
			}
		
			contj++;		
		}	
		
	}
	
	$("#accordion").accordion({
	    header: "h3",
	    active: false,
	    collapsible: true,
	    autoHeight: false,
	    navigation: true 
	});
	
}


	$(':checkbox[readonly=readonly]').click(function(){
	
		return false;        

	}); 

$('#<?php echo $this->campoSeguro('fechaCierre')?>').datepicker({
		<?php /*?>timeFormat: 'HH:mm:ss',<?php */?>
                dateFormat: 'dd/mm/yy',
		minDate: 0,
        yearRange: '0:+50',
		changeYear: true,
		changeMonth: true,
		monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
		    'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
		    monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
		    dayNames: ['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'],
		    dayNamesShort: ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
		    dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
		    
			
	   });
	   
$('#<?php echo $this->campoSeguro('fechaApertura')?>').datepicker({
		<?php /*?>timeFormat: 'HH:mm:ss',<?php */?>
                dateFormat: 'dd/mm/yy',
		minDate: 0,
        yearRange: '0:+50',
		changeYear: true,
		changeMonth: true,
		monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
		    'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
		    monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
		    dayNames: ['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'],
		    dayNamesShort: ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
		    dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
		    
			
	   });
	   
	   
$( function() {
		var dateFormat = "dd/mm/yy",
			from = $( '#<?php echo $this->campoSeguro('fechaApertura')?>' )
				.datepicker({
				defaultDate: "+1w",
			        yearRange: '0:+50',
					changeYear: true,
					changeMonth: true,
					monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
				    monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
				    dayNames: ['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'],
				    dayNamesShort: ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
				    dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa']
				})
				.on( "change", function() {
					to.datepicker( "option", "minDate", getDate( this ) );
				}),
			to = $( '#<?php echo $this->campoSeguro('fechaCierre')?>' ).datepicker({
				defaultDate: "+1w",
			        yearRange: '0:+50',
					changeYear: true,
					changeMonth: true,
					monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
				    monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
				    dayNames: ['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'],
				    dayNamesShort: ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
				    dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa']
			})
			.on( "change", function() {
				from.datepicker( "option", "maxDate", getDate( this ) );
			});

		function getDate( element ) {
			var date;
			try {
				date = $.datepicker.parseDate( dateFormat, element.value );
			} catch( error ) {
				date = null;
			}

			return date;
		}
	} );	


$("#gestionarCotizacionRegistrar").validationEngine({
	validateNonVisibleFields: true,
	promptPosition : "inline", 
	scroll: true,
	autoHidePrompt: true,
	autoHideDelay: 9000,
    updatePromptsPosition:false
});

$("#gestionarValidacionModificar").validationEngine({
	validateNonVisibleFields: true,
	promptPosition : "inline", 
	scroll: true,
	autoHidePrompt: true,
	autoHideDelay: 9000,
    updatePromptsPosition:false
});

$("#gestionObjetoConsultarRel").validationEngine({
	promptPosition : "bottomRight:-150", 
	scroll: false,
	autoHidePrompt: true,
	autoHideDelay: 2000,
	updatePromptsPosition:true
});

$("#gestionObjetoConsultarCot").validationEngine({
	promptPosition : "bottomRight:-150", 
	scroll: false,
	autoHidePrompt: true,
	autoHideDelay: 2000,
	updatePromptsPosition:true
});

$( ".widget input[type=submit], .widget a, .widget button" ).button();
        
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
$('#<?php echo $this->campoSeguro('unidadEjecutora')?>').width(250);
$('#<?php echo $this->campoSeguro('dependencia')?>').width(750);
$('#<?php echo $this->campoSeguro('ordenador')?>').width(750);
$('#<?php echo $this->campoSeguro('solicitante')?>').width(750);
$('#<?php echo $this->campoSeguro('decision')?>').width(750);
$('#<?php echo $this->campoSeguro('medioPago')?>').width(250);
$('#<?php echo $this->campoSeguro('tipoFormaPago')?>').width(350);
$('#<?php echo $this->campoSeguro('decisionPro')?>').width(750);
$('#<?php echo $this->campoSeguro('formaSeleccion')?>').width(350);
$('#<?php echo $this->campoSeguro('tipoContrato')?>').width(450);

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
$('#<?php echo $this->campoSeguro('unidadEjecutora')?>').select2();
$('#<?php echo $this->campoSeguro('dependencia')?>').select2();
$('#<?php echo $this->campoSeguro('ordenador')?>').select2();
$('#<?php echo $this->campoSeguro('solicitante')?>').select2();
$('#<?php echo $this->campoSeguro('decision')?>').select2();
$('#<?php echo $this->campoSeguro('medioPago')?>').select2();
$('#<?php echo $this->campoSeguro('tipoFormaPago')?>').select2();
$('#<?php echo $this->campoSeguro('decisionPro')?>').select2();
$('#<?php echo $this->campoSeguro('formaSeleccion')?>').select2();
$('#<?php echo $this->campoSeguro('tipoContrato')?>').select2();



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

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

$('#tablaPersonas').dataTable({
        
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
    },
    "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                    if ( aData[12] == '<center>SOLICITADA</center>' )
                    {
                        $('td', nRow).css('background-color', '##fff7bb');
                    }
                }
});


$('#<?php echo $this->campoSeguro('fechaCierre')?>').datepicker({
		<?php /*?>timeFormat: 'HH:mm:ss',<?php */?>
                dateFormat: 'dd/mm/yy',
		minDate: 0,
        yearRange: '0:+50',
		changeYear: true,
		changeMonth: true,
		monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
		    'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
		    monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
		    dayNames: ['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'],
		    dayNamesShort: ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
		    dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
		    
			
	   });
	   
$('#<?php echo $this->campoSeguro('unidadEjecutoraCheck') ?>').width(300);
$("#<?php echo $this->campoSeguro('unidadEjecutoraCheck') ?>").select2();
$('#<?php echo $this->campoSeguro('vigencia_solicitud_consulta') ?>').width(200);
$("#<?php echo $this->campoSeguro('vigencia_solicitud_consulta') ?>").select2();
$('#<?php echo $this->campoSeguro('numero_disponibilidad') ?>').width(200);
$("#<?php echo $this->campoSeguro('numero_disponibilidad') ?>").select2();
$('#<?php echo $this->campoSeguro('dependenciaDestino')?>').width(750);
$('#<?php echo $this->campoSeguro('dependenciaDestino')?>').select2();
