// Asociar el widget de validación al formulario
$("#recuperarCredenciales").validationEngine({
    promptPosition : "centerRight", 
    scroll: false
});

$( ".widget input[type=submit], .widget a, .widget button" ).button();

$(function() {
    $("#recuperarCredenciales").submit(function() {
        var resultado=$("#recuperarCredenciales").validationEngine("validate");
        if (resultado) {
            // console.log(filasGrilla);
            return true;
        }
        return false;
    });
});

$(function() {
    $( "button" )
    .button()
    .click(function( event ) {
        event.preventDefault();
    });
});


$(function() {
    $( document ).tooltip();
});

//Asociar el widget tabs a la división cuyo id es tabs
$(function() {
    $( "#tabs" ).tabs();
});
        
$(function() {
    $("button").button().click(function(event) {
        event.preventDefault();
    });
});

var tablaHt = "<table>"+
  				"<tr>"+
  					"<th>Tipo</th>"+
  					"<th>Sigla</th>"+
  				"</tr>"+
			  "<tr>"+
			    "<td>CÉDULA DE CIUDADANÍA</td>"+
			    "<td>CC</td>"+
			  "</tr>" +
			  "<tr>"+
			    "<td>CARNÉ DIPLOMÁTICO</td>"+
			    "<td>CD</td>"+
			  "</tr>" +
			  "<tr>"+
			    "<td>CÉDULA DE EXTRANJERÍA</td>"+
			    "<td>CE</td>"+
			  "</tr>" +
			  "<tr>"+
			    "<td>CERTIFICADO REGISTRADURÍA SIN IDENTIFICACIÓN</td>"+
			    "<td>CRSI</td>"+
			  "</tr>" +
			  "<tr>"+
			    "<td>NÚMERO DE IDENTIFICACIÓN TRIBUTARIA</td>"+
			    "<td>NIT</td>"+
			  "</tr>" +
			  "<tr>"+
			    "<td>PASAPORTE</td>"+
			    "<td>PAS</td>"+
			  "</tr>" +
			  "<tr>"+
			    "<td>REGISTRO CIVIL DE NACIMIENTO</td>"+
			    "<td>RC</td>"+
			  "</tr>" +
			  "<tr>"+
			    "<td>TARJETA DE EXTRANJERÍA</td>"+
			    "<td>TE</td>"+
			  "</tr>" +
			  "<tr>"+
			    "<td>TARJETA DE IDENTIDAD</td>"+
			    "<td>TI</td>"+
			  "</tr>" +
			 "</table>";

$(function() {
    $("#botonAyuTip").click(function(event) {
    	swal({
            title: 'TIPOS DE DOCUMENTO',
            type: 'info',
            html:
                    'Recuerde, la parte inicial de su USUARIO DE ACCESO corresponden a las siglas del TIPO DE DOCUMENTO.<br><br>'
                    +' Los tipos de Documentos Contemplados en el Sistema y sus Correspondientes siglas son:<br><br><br>'
                    +tablaHt,
            confirmButtonText:
                    'Aceptar'
        })
    });
});

function alertCriterio() {
	swal({
		                title: 'Importante <br>USUARIO DE ACCESO',
		                type: 'info',
		                html:
		                        'Recuerde, para poder recuperar contraseña debe consultar con su USUARIO DE ACCESO,'
		                        +' no con el número de identificación.<br><br> <b>AYUDA</b> (Por Ejemplo: Si la Cédula de la Persona es 123456 el USUARIO DE ACCESO es CC123456, la'
		                        +' primera parte del usuario corresponde al TIPO DE DOCUMENTO registrado).',
		                confirmButtonText:
		                        'Aceptar'
		            })
	
}

$("#mensaje").hover(function() {  
	alertCriterio();
	$(this).unbind('mouseenter mouseleave');
});