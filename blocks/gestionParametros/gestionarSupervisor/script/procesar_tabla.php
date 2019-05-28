<script type='text/javascript'>

    $(function () {


        $(function () {

            $('#marcoDatosBasicosTablaSupervisores').ready(function () {

                var table = $('#tablafuncionario').dataTable({
                    "aaSorting": [[ 0, "desc" ]] ,
                    "language": {
                        "sProcessing": "Procesando...",
                        "sLengthMenu": "Mostrar _MENU_ registros",
                        "sZeroRecords": "No se encontraron resultados",
                        "sSearch": "Buscar:",
                        "sLoadingRecords": "Cargando...",
                        "sEmptyTable": "Ningún dato disponible en esta tabla",
                        "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                        "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                        "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                        "oPaginate": {
                            "sFirst": "Primero",
                            "sLast": "Ãšltimo",
                            "sNext": "Siguiente",
                            "sPrevious": "Anterior"
                        }
                    },
                    processing: true,
                    searching: true,
                    info: true,
                    "scrollY": "300px",
                    "scrollCollapse": true,
                    "bLengthChange": false,
                    "bPaginate": false,
                    "aoColumns": [
                        {sWidth: "6%"}, 
                        {sWidth: "13%"}, 
                        {sWidth: "13%"}, 
                        {sWidth: "15%"},
                        {sWidth: "13%"},
                        {sWidth: "12%"},
                        {sWidth: "15%"},
                        {sWidth: "6%"},
                        {sWidth: "6%"},
                    ]

                });



            });

        });



    });




</script>