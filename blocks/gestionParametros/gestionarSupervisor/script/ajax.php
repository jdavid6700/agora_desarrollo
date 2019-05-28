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



//Variables
$cadenaACodificarDependencia = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( "pagina" );
$cadenaACodificarDependencia .= "&procesarAjax=true";
$cadenaACodificarDependencia .= "&action=index.php";
$cadenaACodificarDependencia .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificarDependencia .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificarDependencia .= $cadenaACodificarDependencia . "&funcion=consultarDependencia";
$cadenaACodificarDependencia .= "&tiempo=" . $_REQUEST ['tiempo'];

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion ( "enlace" );

$cadenaDependencia = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $cadenaACodificarDependencia, $enlace );

// URL definitiva
$urlFinalDependencia = $url . $cadenaDependencia;
//echo $urlFinal16; exit;



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







///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/////// Si existe un cambio en valor de select 

		$("#<?php echo $this->campoSeguro('tipoFuncionario')?>").change(function(){
		    if($("#<?php echo $this->campoSeguro('tipoFuncionario')?>").val()!=''){
		            	consultarDep();
		    		}
		    });


function consultarDep(elem, request, response){
		  $.ajax({
		    url: "<?php echo $urlFinalDependencia?>",
		    dataType: "json",
		    data: { valor:$("#<?php echo $this->campoSeguro('tipoFuncionario')?>").val()},
		    success: function(data){ 

		        if(data[0]!=" "){


		            $("#<?php echo $this->campoSeguro('dependenciaFuncionario')?>").html('');
		            $("<option value=''>Seleccione  ....</option>").appendTo("#<?php echo $this->campoSeguro('dependenciaFuncionario')?>");
		            $.each(data , function(indice,valor){
		            	
		            	$("<option value='"+ data[ indice ].id+ "'>"+data[ indice ].nombre+"</option>").appendTo("#<?php echo $this->campoSeguro('dependenciaFuncionario')?>");
		            	
		            });
		            
		           
		            $('#<?php echo $this->campoSeguro('dependenciaFuncionario')?>').width(450); 
		            $("#<?php echo $this->campoSeguro('dependenciaFuncionario')?>").select2();
		           
			        }
		    			
		    }
			                    
		   });
		};	


  $(function () {

		 from = $('#<?php echo $this->campoSeguro('fecha_inicio') ?>').datepicker({
            dateFormat: 'dd/mm/yy',
            changeYear: true,
            changeMonth: true,
            minDate: $('#<?php echo $this->campoSeguro('fecha_inicio_validacion') ?>').val(),
            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            dayNames: ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'],
            dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
            dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
            onSelect: function (dateText, inst) {
                var lockDate = new Date($('#<?php echo $this->campoSeguro('fecha_inicio') ?>').datepicker('getDate'));
                $('input#<?php echo $this->campoSeguro('fecha_fin') ?>').datepicker('option', 'minDate', lockDate);
            }
        }),

         to = $( '#<?php echo $this->campoSeguro('fecha_fin')?>' ).datepicker({
            defaultDate: "+1w",
            	dateFormat: 'dd/mm/yy',
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


  });     

$('#<?php echo $this->campoSeguro('fecha_fin_mod')?>').datepicker({
    dateFormat: 'dd/mm/yy',
    yearRange: '0:+0',
    changeYear: true,
    changeMonth: true,
    monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
        'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
        monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
        dayNames: ['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'],
        dayNamesShort: ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
        dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
        
        
});
       
$('#<?php echo $this->campoSeguro('fecha_inicio_mod')?>').datepicker({
        dateFormat: 'dd/mm/yy',
        yearRange: '0:+0',
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
        from = $( '#<?php echo $this->campoSeguro('fecha_inicio_mod')?>' )
            .datepicker({
            defaultDate: "+1w",
                yearRange: '0:+0',
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
        to = $( '#<?php echo $this->campoSeguro('fecha_fin_mod')?>' ).datepicker({
            defaultDate: "+1w",
                yearRange: '0:+0',
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

$("#<?php echo $this->campoSeguro('fecha_fin_mod') ?>").datepicker( "option", "minDate", dateAdd($("#<?php echo $this->campoSeguro('fecha_inicio_hidden') ?>").val(), 2) );
$("#<?php echo $this->campoSeguro('fecha_fin_mod') ?>").datepicker( "option", "maxDate",$("#<?php echo $this->campoSeguro('fecha_fin_hidden') ?>").val() );

$("#<?php echo $this->campoSeguro('fecha_inicio_mod') ?>").datepicker('disable');
$("#<?php echo $this->campoSeguro('fecha_inicio_mod') ?>").datepicker( "option", "disabled", true );


function dateAdd(nDate, plus) {
        var date = new Date(nDate);
        date.setHours(0,0,0,0);
        date.setDate(date.getDate() + plus);
        return date;
}