<?php

namespace proveedor\registroNotificaciones\funcion;

use proveedor\registroNotificaciones\funcion\redireccionar;

include_once ('redireccionar.php');

if (!isset($GLOBALS["autorizado"])) {
    include("index.php");
    exit;
}

class Registrar {

    var $miConfigurador;
    var $lenguaje;
    var $miFormulario;
    var $miFuncion;
    var $miSql;
    var $conexion;

    function __construct($lenguaje, $sql, $funcion) {

        $this->miConfigurador = \Configurador::singleton();
        $this->miConfigurador->fabricaConexiones->setRecursoDB('principal');
        $this->lenguaje = $lenguaje;
        $this->miSql = $sql;
        $this->miFuncion = $funcion;
    }

    function cambiafecha_format($fecha) {
        ereg("([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})", $fecha, $mifecha);
        $fechana = $mifecha[3] . "-" . $mifecha[2] . "-" . $mifecha[1];
        return $fechana;
    }

    function campoSeguroCodificar($cadena, $tiempoRequest) {
        /* +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
        /* +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
        /* ++++++++++++++++++++++++++++++++++++++++++++ OBTENER CAMPO POST (Codificar) +++++++++++++++++++++++++++++++++++++++++++ */

        $tiempo = (int) substr($tiempoRequest, 0, -2);
        $tiempo = $tiempo * pow(10, 2);

        $campoSeguro = $this->miConfigurador->fabricaConexiones->crypto->codificar($cadena . $tiempo);




        /* +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
        /* +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
        return $campoSeguro;
    }

    function campoSeguroDecodificar($campoSeguroRequest, $tiempoRequest) {
        /* +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
        /* +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
        /* ++++++++++++++++++++++++++++++++++++++++++++ OBTENER CAMPO POST (Decodificar) +++++++++++++++++++++++++++++++++++++++++ */

        $tiempo = (int) substr($tiempoRequest, 0, -2);
        $tiempo = $tiempo * pow(10, 2);

        $campoSeguro = $this->miConfigurador->fabricaConexiones->crypto->decodificar($campoSeguroRequest);

        $campo = str_replace($tiempo, "", $campoSeguro);

        /* +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
        /* +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
        return $campo;
    }

    function procesarFormulario() {

        $conexion = "estructura";
        $esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

        $esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");

        $rutaBloque = $this->miConfigurador->getVariableConfiguracion("raizDocumento") . "/blocks/proveedor/";
        $rutaBloque .= $esteBloque ['nombre'];
        
        $rutaBloqueArchivo = $this->miConfigurador->getVariableConfiguracion ( "raizDocumento" ) . "/blocks/proveedor/registroProveedor";
	
        $host = $this->miConfigurador->getVariableConfiguracion("host") . $this->miConfigurador->getVariableConfiguracion("site") . "/blocks/proveedor/" . $esteBloque ['nombre'];


        $hostArchivo = $this->miConfigurador->getVariableConfiguracion ( "host" ) . $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/proveedor/registroProveedor";


        foreach ($_FILES as $key => $values) {

            $archivo = $_FILES [$key];
        }



        $cotizacion_soporte = $this->campoSeguroCodificar('cotizacionSoporte', $_REQUEST['tiempo']);

        if ($_FILES [$cotizacion_soporte] ['name'] != '') {

            // obtenemos los datos del archivo
            $tamano = $archivo ['size'];
            $tipo = $archivo ['type'];
            $archivo1 = $archivo ['name'];
            $prefijo = substr(md5(uniqid(rand())), 0, 6);

            if ($archivo1 != "") {
                // guardamos el archivo a la carpeta files
                $destino1 = $rutaBloqueArchivo . "/files/" . $prefijo . "_" . $archivo1;

                if (copy($archivo ['tmp_name'], $destino1)) {
                    $status = "Archivo subido: <b>" . $archivo1 . "</b>";
                    $destino1 = $hostArchivo . "/files/" . $prefijo . "_" . $archivo1;
                } else {
                    $status = "Error al subir el archivo";
                }
            } else {
                $status = "Error al subir archivo";
            }
        } else {

            $destino1 = NULL;
            $archivo1 = NULL;
        }


        $entregaServicio = '';
        $plazoEjecucion = '';

        /* -------------------------------------------------------------------------------------- */
        /* -------------------------------------------------------------------------------------- */
//
        if (isset($_REQUEST['tipoCotizacion'])) {//CAST tipo de NECESIDAD
            switch ($_REQUEST['tipoCotizacion']) {
                case 'BIEN' :
                    $entregaServicio = 'entregables';
                    $plazoEjecucion = 'plazoEntrega';
                    break;
                case 'SERVICIO' :
                    $entregaServicio = 'desServicio';
                    $plazoEjecucion = 'detalleEjecucion';
                    break;
                default:
                    $entregaServicio = 'entregablesdesServicio';
                    $plazoEjecucion = 'entregaEjecucion';
                    break;
            }
        }


        $entregables = $_POST[$this->campoSeguroCodificar($entregaServicio, $_REQUEST['tiempo'])];
        $plazoEntrega = $_POST[$this->campoSeguroCodificar($plazoEjecucion, $_REQUEST['tiempo'])];
        $formaPagoCot = $_POST[$this->campoSeguroCodificar('formaPagoCot', $_REQUEST['tiempo'])];
        $descuentos = $_POST[$this->campoSeguroCodificar('descuentos', $_REQUEST['tiempo'])];


        $datosTextoEnriquecido = array(
            'entregables' => $entregables,
            'plazoEntrega' => $plazoEntrega,
            'formaPagoCot' => $formaPagoCot,
            'descuentos' => $descuentos
        );
        
        
        $datosConsultaSol = array(
            'proveedor' =>$_REQUEST['id_proveedor'],
            'solicitud' => $_REQUEST['solicitud']
        );
        
        $cadenaSql = $this->miSql->getCadenaSql('consultarIdsolicitud', $datosConsultaSol);
        $id_solicitud= $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda", $datosConsultaSol, 'consultarIdsolicitud');


        $fechaVencimiento = $this->cambiafecha_format($_REQUEST ['fechaVencimientoCot']);

        $datosSolicitud = array(
            'solicitud' =>$id_solicitud[0][0],
            'entregables' => $datosTextoEnriquecido['entregables'],
            'plazoEntrega' => $datosTextoEnriquecido['plazoEntrega'],
            'precio' => $_REQUEST['precioCot'],
            'formapago' => $datosTextoEnriquecido['formaPagoCot'],
            'descuentos' => $datosTextoEnriquecido['descuentos'],
            'fechaVencimiento' => $fechaVencimiento,
            'soporte' => $destino1,
            'fechaRegistro' => date('Y-m-d'),
            'usuario' => $_REQUEST ['usuario']
        );

        
           

        $cadenaSql = $this->miSql->getCadenaSql('registrarRespuesta', $datosSolicitud);
        $resultado = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda", $datosSolicitud, 'registrarRespuesta');
        
        
        $cadenaSql = $this->miSql->getCadenaSql('actualizarEstado', $id_solicitud[0][0]);
        $resultadoActualizacion = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda", $datosSolicitud, 'registrarRespuesta');



        if ($resultado[0][0]) {


             $datos = array(
                'resultado' =>  $resultado[0][0],
                'solicitud' => $id_solicitud[0][0],
                'entregables' => $datosTextoEnriquecido['entregables'],
                'plazoEntrega' => $datosTextoEnriquecido['plazoEntrega'],
                'precio' => $_REQUEST['precioCot'],
                'formapago' => $datosTextoEnriquecido['formaPagoCot'],
                'descuentos' => $datosTextoEnriquecido['descuentos'],
                'fechaVencimiento' => $fechaVencimiento,
                'soporte' => $destino1,
                'fechaRegistro' => date('Y-m-d'),
                'usuario' => $_REQUEST ['usuario'],
                 'proveedor' =>$_REQUEST['id_proveedor'],
                 'objeto' => $_REQUEST['solicitud'],
                 'numero_solicitud' => $_REQUEST['numero_solicitud'],
                 'vigencia' => $_REQUEST['vigencia'],
                 'titulo_cotizacion' => $_REQUEST['titulo_cotizacion'],
                  'fecha_cierre' => $_REQUEST['fecha_cierre']
                 
            );

            
            redireccion::redireccionar('inserto', $datos);
            exit();
        } else {
           
            redireccion::redireccionar('noInserto');
            exit();
        }
    }

    function resetForm() {
        foreach ($_REQUEST as $clave => $valor) {

            if ($clave != 'pagina' && $clave != 'development' && $clave != 'jquery' && $clave != 'tiempo') {
                unset($_REQUEST [$clave]);
            }
        }
    }

}

$miRegistrador = new Registrar($this->lenguaje, $this->sql, $this->funcion);

$resultado = $miRegistrador->procesarFormulario();
?>