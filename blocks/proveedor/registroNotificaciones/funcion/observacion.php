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

		
        $SQLs = [];


        $observacion = $_POST[$this->campoSeguroCodificar('observacion', $_REQUEST['tiempo'])];
        
        
        $datosTextoEnriquecido = array(
        		'observacion' => $observacion
        ); 
        
        $date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s')));

        $datosSolicitud = array(
            'solicitud' =>$_REQUEST ['solicitudPro'],
            'observacion' => $datosTextoEnriquecido['observacion'],
            'visto' => 'FALSE',
            'fechaRegistro' => $date,
            'usuario' => $_REQUEST ['usuario']
        );
        
        $datosObservacion = $this->miSql->getCadenaSql ( 'registrarObservacion', $datosSolicitud );
        array_push($SQLs, $datosObservacion);

        $registroObservacion = $esteRecursoDB->transaccion($SQLs);
      
        if ($registroObservacion) {
        	
        	
			$datos = array(
	            'solicitud' =>$_REQUEST ['solicitudPro'],
				'objeto' =>$_REQUEST ['solicitud'],
	            'observacion' => $datosTextoEnriquecido['observacion'],
	            'visto' => 'FALSE',
	            'fechaRegistro' => $date,
	            'usuario' => $_REQUEST ['usuario']
	        );

            
            redireccion::redireccionar('insertoObs', $datos);
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