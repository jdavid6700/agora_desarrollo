<?php

namespace hojaDeVida\crearDocente\funcion;

use hojaDeVida\crearDocente\funcion\redireccionar;

include_once ('redireccionar.php');
if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
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

        $conexion = 'framework';
        $frameworkRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

        $conexion = 'core_central';
        $coreRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

        $esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");

        $rutaBloque = $this->miConfigurador->getVariableConfiguracion("raizDocumento") . "/blocks/asignacionPuntajes/salariales/";
        $rutaBloque .= $esteBloque ['nombre'];
        $host = $this->miConfigurador->getVariableConfiguracion("host") . $this->miConfigurador->getVariableConfiguracion("site") . "/blocks/asignacionPuntajes/salariales/" . $esteBloque ['nombre'];

        $rutaBloqueArchivo = $this->miConfigurador->getVariableConfiguracion ( "raizDocumento" ) . "/blocks/gestionCotizaciones/relacionarCotizacion";


        $datosSolicitudNecesidad = array(
            'idObjeto' => $_REQUEST['idObjeto']
        );

        $cadena_sql = $this->miSql->getCadenaSql("informacionSolicitudAgoraNoCast", $datosSolicitudNecesidad);
        $resultadoCotizacion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");


        $datosFkCotizacion = array(
            'jefe_dependencia' => $resultadoCotizacion [0]['jefe_dependencia'],
            'ordenador_gasto' => $resultadoCotizacion [0]['ordenador_gasto'],
            'plan_accion' => $resultadoCotizacion [0]['plan_accion']
        );


        /* Variables Texto Enriquecido ---------------------------------------------------------- */
        /* -------------------------------------------------------------------------------------- */
        $objetivos = $_POST[$this->campoSeguroCodificar('objetivo', $_REQUEST['tiempo'])];
        $requisitos = $_POST[$this->campoSeguroCodificar('requisitos', $_REQUEST['tiempo'])];
        $observaciones = $_POST[$this->campoSeguroCodificar('observaciones', $_REQUEST['tiempo'])];
        $plan = $_POST[$this->campoSeguroCodificar('planAccion', $_REQUEST['tiempo'])];
        $titulo = $_POST[$this->campoSeguroCodificar('tituloCotizacion', $_REQUEST['tiempo'])];
        $criterio = $_POST[$this->campoSeguroCodificar('criterioSeleccion', $_REQUEST['tiempo'])];
        $plazo = $_POST[$this->campoSeguroCodificar('plazoEjecucion', $_REQUEST['tiempo'])];

        $SQLs = [];

        $datosTextoEnriquecido = array(
            'objetivos' => str_replace("'", "\"", $objetivos), //Limpieza Comilla Simple para evitar Errores En Ejecución Base de Datos
            'requisitos' => str_replace("'", "\"", $requisitos),
            'observaciones' => str_replace("'", "\"", $observaciones),
            'plan' => str_replace("'", "\"", $plan),
        	'titulo' => str_replace("'", "\"", $titulo),
        	'criterio' => str_replace("'", "\"", $criterio),
        	'plazo' => str_replace("'", "\"", $plazo)
        );
        /* -------------------------------------------------------------------------------------- */
        /* -------------------------------------------------------------------------------------- */



        //******************************** ANEXO ****************************************************************

        if($_FILES[$this->campoSeguroCodificar('cotizacionSoporteEspTec', $_REQUEST['tiempo'])]){
            $archivo = $_FILES[$this->campoSeguroCodificar('cotizacionSoporteEspTec', $_REQUEST['tiempo'])];
            // Obtenemos los datos del archivo
            $tamano = $archivo ['size'];
            $tipo = $archivo ['type'];
            $archivoName = $archivo ['name'];
            $prefijo = substr ( md5 ( uniqid ( rand () ) ), 0, 6 );
            $nombreDoc = $prefijo . "-" . $archivoName;
        
            if ($archivoName != "") {
                // Guardamos el archivo a la carpeta files
                $destino = $rutaBloqueArchivo . "/soportes/" . $nombreDoc;
        
                if (copy ( $archivo ['tmp_name'], $destino )) {
                    $status = "Archivo subido: <b>" . $archivoName . "</b>";
                    $destino1 = $prefijo . "-" . $archivoName;
                } else {
                    $status = "<br>Error al subir el archivo";
                }
            } else {
                $status = "<br>Error al subir archivo";
            }
        }else{
            echo "<br>NO existe el archivo !!!";
        }

        
        if(isset($archivo) && $archivo ['name'] != ""){

            $datoAneXCot = array (
                    'objeto_cotizacion_id' => $_REQUEST['idObjeto'],
                    'idObjeto' => $_REQUEST['idObjeto'],
                    'anexo' => $destino1
            );

            $cadenaSql = $this->miSql->getCadenaSql ( 'consultarEspTec', $datoAneXCot );
            $resultadoAnexo= $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );

            if($resultadoAnexo){
                $anexoEspTec = $this->miSql->getCadenaSql ( 'actualizarAnexoEspTec', $datoAneXCot );
                array_push($SQLs, $anexoEspTec);  
            }else{
                $anexoEspTec = $this->miSql->getCadenaSql ( 'registrarAnexoEspTec', $datoAneXCot );
                array_push($SQLs, $anexoEspTec); 
            }

              
        }
        //*******************************************************************************************************


        $fechaApertura = $this->cambiafecha_format($_REQUEST['fechaApertura']);
        $fechaCierre = $this->cambiafecha_format($_REQUEST['fechaCierre']);


        $datosPlan = array(
            'plan' => $plan,
            'fk_id_plan' => $datosFkCotizacion['plan_accion']
        );



        $planAccion = $this->miSql->getCadenaSql('actualizarPlanAccion', $datosPlan);
        array_push($SQLs, $planAccion);


        $valorCDP = str_replace(",", "", $_REQUEST['indices_cdps']);


        /**********************************************/        
        /*
        JEFE DEPENDENCIA (Vigente)
        */
        $cadenaSql = $this->miSql->getCadenaSql ( "jefeDependenciaLast", $_REQUEST['dependencia'] );
        $jefeOrigen = $coreRecursoDB->ejecutarAcceso ( $cadenaSql, 'busqueda' );
        $_REQUEST['dependencia'] = $jefeOrigen[0][0];

        $cadenaSql = $this->miSql->getCadenaSql ( "jefeDependenciaLast", $_REQUEST['dependenciaDestino'] );
        $jefeDestino = $coreRecursoDB->ejecutarAcceso ( $cadenaSql, 'busqueda' );
        $_REQUEST['dependenciaDestino'] = $jefeDestino[0][0];

        /**********************************************/


        $datosSolicitud = array(
            'idObjeto' => $_REQUEST['idObjeto'],
            'titulo_cotizacion' => $datosTextoEnriquecido['titulo'],
            'vigencia' => $_REQUEST ['vigencia'],
            'unidad_ejecutora' => (int) $_REQUEST ['unidad_ejecutora_hidden'],
            'dependencia' => $_REQUEST ['dependencia'],
            'dependencia_destino' => $_REQUEST ['dependenciaDestino'],
            'ordenador' => $_REQUEST ['ordenador_hidden'],
            'fecha_apertura' => $fechaApertura,
            'fecha_cierre' => $fechaCierre,
            'objetivo' => $datosTextoEnriquecido['objetivos'],
            'requisitos' => $datosTextoEnriquecido['requisitos'],
            'observaciones' => $datosTextoEnriquecido['observaciones'],
            'plan' => $datosFkCotizacion['plan_accion'],
            'tipo_necesidad' => $_REQUEST ['tipoNecesidad'],
            //'forma_seleccion' => $_REQUEST ['formaSeleccion'],
			'criterio' => $datosTextoEnriquecido['criterio'],
        	'plazo' => $datosTextoEnriquecido['plazo'],
            'medio_pago' => $_REQUEST ['medioPago'],
        	'tipo_contrato' => $_REQUEST ['tipoContrato'],
            'numero_disponibilidad' => $valorCDP,
            'usuario' => $_REQUEST ['usuario']
        );


        $datosSolicitudCotizacion = $this->miSql->getCadenaSql('actualizar', $datosSolicitud);
        array_push($SQLs, $datosSolicitudCotizacion);



        if (isset($_REQUEST['changeFormaPago']) && $_REQUEST['changeFormaPago'] == 'true') {


            $datosCleanRelaciones = $this->miSql->getCadenaSql('configuracionRelaciones', $_REQUEST['idObjeto']);
            array_push($SQLs, $datosCleanRelaciones);


            //******************************** FORMA PAGO ****************************************************************

            $subCount = explode(" ", $_REQUEST ['countParam']);
            $countFPParam = $subCount[1];

            $subFP = explode("&", $_REQUEST ['idsFormaPago']);
            $cantidadParametros = ($countFPParam) * 3;

            $limitP = 0;
            while ($limitP < $cantidadParametros) {

                $subCount[$limitP] = explode(" ", $subFP[$limitP]);

                $limitP++;
            }

            $limit = 0;
            while ($limit < $cantidadParametros) {

            	if($subCount[$limit][0] == "2"){
            		$paraCon = $subFP[$limit+1];
            		$paraCon = str_replace("\\", " ", $paraCon);
            		$paraCon = str_replace("'", "\"", $paraCon);
            	}else{
            		$paraCon = $subCount[$limit+1][0];
            	}

                $datoFP = array(
                    'tipo_condicion' => $subCount[$limit][0],
                    'valor_condicion' => $paraCon,
                    'porcentaje_pago' => $subCount[$limit + 2][0]
                );

                $datoRegFP = $this->miSql->getCadenaSql('registrarFormaPago', $datoFP);
                array_push($SQLs, $datoRegFP);

                $datoFPxCot = array(
                    'objeto_cotizacion_id' => $_REQUEST['idObjeto'],
                    'forma_pago_id' => "currval('agora.forma_pago_id_seq')"
                );

                $datoRegFPxCot = $this->miSql->getCadenaSql('registrarFormaPagoXCotizacion', $datoFPxCot);
                array_push($SQLs, $datoRegFPxCot);


                $limit = $limit + 3;
            }


            //*************************************************************************************************************
        }

        //******************************** ITEMS ****************************************************************
        
        
         $datoEliminarItem = $this->miSql->getCadenaSql('EliminarItemProducto', $_REQUEST['idObjeto']);
          array_push($SQLs, $datoEliminarItem);
        

        $countFPParam = $_REQUEST ['countItems'];


        $subFP = explode("@$&$@", $_REQUEST ['idsItems']);
        

        $cantidadParametros = ($countFPParam) * 6;

        $limitP = 0;
        while ($limitP < $cantidadParametros) {

            $subCount[$limitP] = explode(" ", $subFP[$limitP]);

            $limitP++;
        }


        $limit = 0;
        while ($limit < $cantidadParametros) {
      
            
            $registroCant =str_replace(".", "", $subCount[$limit + 5][0]);
            $registroCant =str_replace(",", ".", $registroCant);

            if ($subCount[$limit + 3][0] == 1) {

                $datoFP = array(
                    'objeto_cotizacion' => $_REQUEST['idObjeto'],
                    'nombre' => $subFP[$limit + 1],
                    'descripcion' => $subFP[$limit + 2],
                    'tipo' => $subCount[$limit + 3][0],
                    'unidad' => $subCount[$limit + 4][0],
                    'tiempo' => 0,
                    'cantidad' => $registroCant
                );
            }


            if ($subCount[$limit + 3][0] == 2) {



                
                
                
                $datoFP = array(
                    'objeto_cotizacion' => $_REQUEST['idObjeto'],
                    'nombre' => $subFP[$limit + 1],
                    'descripcion' => $subFP[$limit + 2],
                    'tipo' => $subCount[$limit + 3][0],
                    'unidad' => $subCount[$limit + 4][0],
                    'tiempo' => 0,
                    'cantidad' => $registroCant
                );
            }

            $datoRegItem = $this->miSql->getCadenaSql('registrarItemProducto', $datoFP);


            array_push($SQLs, $datoRegItem);


            $limit = $limit + 6;
        }
        
       
         
        $actualizoCotizacion = $esteRecursoDB->transaccion($SQLs);
        

        if ($actualizoCotizacion) {



            if (isset($_REQUEST['tipoNecesidad'])) {//CAST tipo de NECESIDAD
                switch ($_REQUEST['tipoNecesidad']) {
                    case 1 :
                        $_REQUEST['tipoNecesidad'] = 'BIEN';
                        break;
                    case 2 :
                        $_REQUEST['tipoNecesidad'] = 'SERVICIO';
                        break;
                    case 3 :
                        $_REQUEST ['tipoNecesidad'] = 'BIEN Y SERVICIO';
                        break;
                }
            }


            $datos = array(
                'idObjeto' => $_REQUEST['idObjeto'],
                'titulo_cotizacion' => $datosTextoEnriquecido['titulo'],
                'vigencia' => $_REQUEST ['vigencia'],
                'unidad_ejecutora' => (int) $_REQUEST ['unidad_ejecutora_hidden'],
                'dependencia' => $_REQUEST ['dependencia'],
                'dependencia_destino' => $_REQUEST ['dependenciaDestino'],
                'ordenador' => $_REQUEST ['ordenador_hidden'],
                'fecha_apertura' => $fechaApertura,
                'fecha_cierre' => $fechaCierre,
                'objetivo' => $datosTextoEnriquecido['objetivos'],
                'requisitos' => $datosTextoEnriquecido['requisitos'],
                'observaciones' => $datosTextoEnriquecido['observaciones'],
                'plan' => $datosTextoEnriquecido['plan'],
                'tipo_necesidad' => $_REQUEST ['tipoNecesidad'],
                'medio_pago' => $_REQUEST ['medioPago'],
            	'tipo_contrato' => $_REQUEST ['tipoContrato'],
                'estadoSolicitud' => $_REQUEST ['estadoSolicitudRelacionada'],
                'usuario' => $_REQUEST ['usuario']
            );


            if (!empty($_SERVER['HTTP_CLIENT_IP'])){
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            }elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }else{
                $ip = $_SERVER['REMOTE_ADDR'];
            }
            $c = 0;
            while ($c < count($SQLs)){
                $SQLsDec[$c] = $this->miConfigurador->fabricaConexiones->crypto->codificar($SQLs[$c]);
                $c++;
            }
            $query = json_encode($SQLsDec);
            $numberSolicitud = "SC-" . sprintf("%05d", $_REQUEST['idObjeto']);

            $dataBackDec = unserialize($this->miConfigurador->fabricaConexiones->crypto->decodificar($_REQUEST['dataSerCt']));
            foreach($dataBackDec as $tabl => $param)
            {
                $dataBackCod[$tabl] = $this->miConfigurador->fabricaConexiones->crypto->codificar(json_encode($param[0]));
            }
            $data = json_encode($dataBackCod);
                
            $datosLog = array (
                    'tipo_log' => 'MODIFICACION',
                    'modulo' => 'MCOT',
                    'numero_cotizacion' => $numberSolicitud,
                    'vigencia' => $_REQUEST ['vigencia'],
                    'query' => $query,
                    'data' => $data,
                    'host' => $ip,
                    'fecha_log' => date("Y-m-d H:i:s"),
                    'usuario' => $_REQUEST ['usuario']
            );
            $cadenaSQL = $this->miSql->getCadenaSql("insertarLogCotizacionUp", $datosLog);
            $resultadoLog = $frameworkRecursoDB->ejecutarAcceso($cadenaSQL, 'busqueda');

            
            redireccion::redireccionar('inserto', $datos);
            exit();
        } else {

                if (!empty($_SERVER['HTTP_CLIENT_IP'])){
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
                }elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                }else{
                    $ip = $_SERVER['REMOTE_ADDR'];
                }
                $c = 0;
                while ($c < count($SQLs)){
                    $SQLsDec[$c] = $this->miConfigurador->fabricaConexiones->crypto->codificar($SQLs[$c]);
                    $c++;
                }
                $query = json_encode($SQLsDec);
                $numberSolicitud = "SC-" . sprintf("%05d", $_REQUEST['idObjeto']);
                $error = json_encode(error_get_last());
                
                $datosLog = array (
                        'tipo_log' => 'MODIFICACION',
                        'modulo' => 'MCOT',
                        'numero_cotizacion' => $numberSolicitud,
                        'vigencia' => $_REQUEST ['vigencia'],
                        'query' => $query,
                        'error' => $error,
                        'host' => $ip,
                        'fecha_log' => date("Y-m-d H:i:s"),
                        'usuario' => $_REQUEST ['usuario']
                );
                $cadenaSQL = $this->miSql->getCadenaSql("insertarLogCotizacionError", $datosLog);
                $resultadoLog = $frameworkRecursoDB->ejecutarAcceso($cadenaSQL, 'busqueda');
                    
                $caso = "RCL-" . date("Y") . "-" . $resultadoLog[0][0];

            redireccion::redireccionar('noInserto', $caso);
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
