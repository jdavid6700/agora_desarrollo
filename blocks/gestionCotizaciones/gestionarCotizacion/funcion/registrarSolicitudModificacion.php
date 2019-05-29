<?php

namespace hojaDeVida\crearDocente\funcion;

use hojaDeVida\crearDocente\funcion\redireccionar;

include_once ('redireccionar.php');
if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
}

class SolicitudModificacion {

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

        $esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");

        $rutaBloque = $this->miConfigurador->getVariableConfiguracion("raizDocumento") . "/blocks/asignacionPuntajes/salariales/";
        $rutaBloque .= $esteBloque ['nombre'];

        $rutaBloqueArchivo = $this->miConfigurador->getVariableConfiguracion ( "raizDocumento" ) . "/blocks/gestionCotizaciones/relacionarCotizacion";



        $fechaCierre = $this->cambiafecha_format($_REQUEST['fechaCierre']);
        $fechaCierreInicial = $this->cambiafecha_format($_REQUEST['fechaCierreInicial']);

        $arregloFecha[] = array(
            'fecha_cierre' => $fechaCierreInicial
        );

        $SQLs = [];
        $SQLDevs = [];

        $datosSolicitud = array(
            'idObjeto' => $_REQUEST['idObjeto'],
            'fecha_cierre' => $fechaCierre
        );






        //********************************** Registro Base INFORMACIÓN INICIAL Adendas ********************************************

        $cadena_sql = $this->miSql->getCadenaSql("buscarDetalleItemsCast", $datosSolicitud);
        $resultadoItemsCast = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

        $cadenaSql = $this->miSql->getCadenaSql('adendasModificacion', $resultadoItemsCast[0][0]);
        $resultadoAdendas = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");

        if (!$resultadoAdendas) { // Ejecutar Registro de ITEMS Base SOLO Para la Primera Adenda (Caso ´Único para la Primera Modificación)
            //No existen adendas registradas
            $cadenaSql = $this->miSql->getCadenaSql('buscarDetalleItemsCastArray', $datosSolicitud);
            $resultadoAdendasSolCastArray = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");

            $cantidadParametros = count($resultadoAdendasSolCastArray);
            $limitb = 0;

            while ($limitb < $cantidadParametros) {

                $cadena_sql = $this->miSql->getCadenaSql("buscarDetalleItem", $resultadoAdendasSolCastArray[$limitb][0]);
                $resultadoItem = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

                $arregloDefinitivo = array_merge($resultadoItem, $arregloFecha);
                $json_item = json_encode($arregloDefinitivo);

                $datosSolicitudLog = array(
                    'item' => $resultadoAdendasSolCastArray[$limitb][0],
                    'json' => $json_item,
                    'fecha' => date("Y-m-d H:i:s"),
                    'idSolMod' => $_REQUEST['idSolicitudMod']
                );

                $cadenaSqlLogB = $this->miSql->getCadenaSql('insertarLogItemBase', $datosSolicitudLog);
                array_push($SQLs, $cadenaSqlLogB);

                array_push($SQLDevs, $cadenaSqlLogB);


                $limitb++;
            }
        }

        //**************************************************************************************************************************





        $cadenaSqlFechaCierre = $this->miSql->getCadenaSql('actualizarFechaCierre', $datosSolicitud);

        array_push($SQLs, $cadenaSqlFechaCierre);

        $countFPParam = $_REQUEST ['countItems'];
        $subFP = explode("@$&$@", $_REQUEST ['idsItems']);

        $cantidadParametros = ($countFPParam) * 7;

        $limitP = 0;
        while ($limitP < $cantidadParametros) {
            $subCount[$limitP] = explode(" ", $subFP[$limitP]);
            $limitP++;
        }

        $limit = 0;

        while ($limit < $cantidadParametros) {


            $registroCant = str_replace(".", "", $subCount[$limit + 5][0]);
            $registroCant = str_replace(",", ".", $registroCant);

            $cadena_sql = $this->miSql->getCadenaSql("buscarDetalleItem", $subFP[$limit]);
            $resultadoItem = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

            $arregloDefinitivo = array_merge($resultadoItem, $arregloFecha);
            $json_item = json_encode($arregloDefinitivo);
            
            
             $datosSolicitudLogNuevo = array(
                'id' => $subFP[$limit],
                'nombre' => $subFP[$limit + 1],
                'descripcion' => $subFP[$limit + 2],
                'tipo_necesidad' => $subCount[$limit + 3][0],
                'unidad' => $subCount[$limit + 4][0],
                'tiempo_ejecucion' => 0,
                'cantidad' => $registroCant
            );
             
            $json_item_Nuevo = json_encode($datosSolicitudLogNuevo);

            $datosSolicitudLog = array(
                'item' => $subFP[$limit],
                'json' => $json_item,
                'fecha' => date("Y-m-d H:i:s"),
                'idSolMod' => $_REQUEST['idSolicitudMod'],
                'jsonNuevo' => $json_item_Nuevo
            );

           

    
            $cadenaSqlLog = $this->miSql->getCadenaSql('insertarLogItem', $datosSolicitudLog);
            array_push($SQLs, $cadenaSqlLog);

            array_push($SQLDevs, $cadenaSqlLog);


            $datosSolicitudJustificacionLog = array(
                'item' => $subFP[$limit],
                'justificacion' => $subFP[$limit + 6],
                'idSolMod' => $_REQUEST['idSolicitudMod']
            );

            $cadenaSqlJustificacionLog = $this->miSql->getCadenaSql('insertarLogJustificacionItem', $datosSolicitudJustificacionLog);
            array_push($SQLs, $cadenaSqlJustificacionLog);



            if ($subCount[$limit + 3][0] == 1) {

                $datoFP = array(
                    'id_item' => $subFP[$limit],
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
                    'id_item' => $subFP[$limit],
                    'nombre' => $subFP[$limit + 1],
                    'descripcion' => $subFP[$limit + 2],
                    'tipo' => $subCount[$limit + 3][0],
                    'unidad' => $subCount[$limit + 4][0],
                    'tiempo' => 0,
                    'cantidad' => $registroCant
                );
            }
            $datoRegItem = $this->miSql->getCadenaSql('actualizarItemProducto', $datoFP);
            array_push($SQLs, $datoRegItem);

            $limit = $limit + 7;
        }

        $cadena_sql = $this->miSql->getCadenaSql("buscarRespuestaDeCotizacion", $_REQUEST['idObjeto']);
        $resultadoRespuestaCotizacion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

        if ($resultadoRespuestaCotizacion != false) {

            $cantidadRespuestas = count($resultadoRespuestaCotizacion);
            $i = 0;
            while ($i < $cantidadRespuestas) {
                $datoActRespuestaCotizacion = $this->miSql->getCadenaSql('actualizarRespuestaDeCotizacion', $resultadoRespuestaCotizacion[$i]['id']);
                array_push($SQLs, $datoActRespuestaCotizacion);
                $i = $i + 1;
            }
        }

        $datoActRespuestaCotizacionInfo = $this->miSql->getCadenaSql('actualizarEstadoInformadoProveedor', $datosSolicitud['idObjeto']);
        array_push($SQLs, $datoActRespuestaCotizacionInfo);


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

        
        $insertoModificacionSolicitud = $esteRecursoDB->transaccion($SQLs);



        $datos = array(
            'objeto' => $_REQUEST ['idObjeto'],
            'usuario' => $_REQUEST ['usuario']
        );

        if ($insertoModificacionSolicitud) {

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
                
                $c = 0;
                while ($c < count($SQLDevs)){
                    $SQLsDec[$c] = $this->miConfigurador->fabricaConexiones->crypto->codificar($SQLDevs[$c]);
                    $c++;
                }
                $data = json_encode($SQLsDec);    

                $datosLog = array (
                        'tipo_log' => 'MODIFICACION',
                        'modulo' => 'MCOTP',
                        'numero_cotizacion' => $numberSolicitud,
                        'vigencia' => date("Y"),
                        'query' => $query,
                        'data' => $data,
                        'host' => $ip,
                        'fecha_log' => date("Y-m-d H:i:s"),
                        'usuario' => $_REQUEST ['usuario']
                );
                $cadenaSQL = $this->miSql->getCadenaSql("insertarLogCotizacionUp", $datosLog);
                $resultadoLog = $frameworkRecursoDB->ejecutarAcceso($cadenaSQL, 'busqueda');

            redireccion::redireccionar('insertoModificacionSolicitudCotizacion', $datos);
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
                    $numberSolicitud = "SMC-" . sprintf("%05d", $_REQUEST['idObjeto']);
                    $error = json_encode(error_get_last());
                    
                    $datosLog = array (
                            'tipo_log' => 'MODIFICACION',
                            'modulo' => 'MCOTP',
                            'numero_cotizacion' => $numberSolicitud,
                            'vigencia' => date("Y"),
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

$miRegistrador = new SolicitudModificacion($this->lenguaje, $this->sql, $this->funcion);

$resultado = $miRegistrador->procesarFormulario();
?>
