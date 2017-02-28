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
