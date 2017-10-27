<?php

use asignacionPuntajes\salariales\premiosDocente\Sql;

$conexion = "estructura";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

$conexionSICA = "sicapital";
$DBSICA = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexionSICA);

$conexion = 'core_central';
$coreRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
//Estas funciones se llaman desde ajax.php y estas a la vez realizan las consultas de Sql.class.php 
$ruta_1 = $this->miConfigurador->getVariableConfiguracion('raizDocumento') . '/plugin/php_excel/Classes/PHPExcel.class.php';
$ruta_2 = $this->miConfigurador->getVariableConfiguracion('raizDocumento') . '/plugin/php_excel/Classes/PHPExcel/Reader/Excel2007.class.php';


$directorio = $this->miConfigurador->getVariableConfiguracion("host");
$directorio .= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
$directorio .= $this->miConfigurador->getVariableConfiguracion("enlace");
include_once ($ruta_1);
include_once ($ruta_2);

//-------------------------------------------------
//-------------------------------------------------
//Validación Petición AJAX Parametro SQL Injection
if (isset($_REQUEST['valor'])) {

    if (is_numeric($_REQUEST['valor'])) {
        $subclase = $_REQUEST['valor'];
        settype($_REQUEST['valor'], 'integer');
        $secure = true;
    } else {
        $secure = false;
    }
} else {


    if (isset($_REQUEST['vigencia']) && isset($_REQUEST['unidad']) && isset($_REQUEST['cdpseleccion'])) {
        $secure = true;
    } else {

        if (isset($_REQUEST ['numero_disponibilidad'])) {
            $secure = true;
        } else {
            $secure = false;
        }
    }
}

//if(isset($_REQUEST['vigencia']) && isset($_REQUEST['unidad']) && isset($_REQUEST['cdpseleccion'])){
//	$secure = true;
//}
//
//if(isset($_REQUEST ['numero_disponibilidad'])){
//	$secure = true;
//}
//-------------------------------------------------
//-------------------------------------------------

if ($secure) {

    if ($_REQUEST ['funcion'] == 'consultarClase') {
        $cadenaSql = $this->sql->getCadenaSql('ciiuClase', $_REQUEST["valor"]);
        $datos = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
        echo json_encode($datos);
    }

    if ($_REQUEST ['funcion'] == 'consultarCiudad') {
        $cadenaSql = $this->sql->getCadenaSql('ciiuGrupo', $_REQUEST["valor"]);
        $datos = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
        echo json_encode($datos);
    }

    if ($_REQUEST ['funcion'] == 'consultarNBC') {
        $cadenaSql = $this->sql->getCadenaSql('buscarNBCAjax', $_REQUEST['valor']);
        $resultado = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
        $resultado = json_encode($resultado);
        echo $resultado;
    }
    if ($_REQUEST ['funcion'] == 'consultarCIIUPush') {
        $cadenaSql = $this->sql->getCadenaSql('ciiuSubClaseByNumPush', $subclase);
        $datos = $coreRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
        echo json_encode($datos);
    }
    if ($_REQUEST ['funcion'] == 'consultarActividad') {
        $cadenaSql = $this->sql->getCadenaSql('consultarActividades', $subclase);
        $datos = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
        echo json_encode($datos);
    }

    if ($_REQUEST ['funcion'] == 'consultarTipoFormaPago') {
        $cadenaSql = $this->sql->getCadenaSql('consultarTipoFormaPagoByNumPush', $subclase);
        $datos = $coreRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
        echo json_encode($datos);
    }

    if ($_REQUEST ['funcion'] == 'formaDePagoAjax') {

        if ($_REQUEST ['valor'] != 13) {//Contrato de Obra -> Se debe Permitir Anticipo, de lo Contrario NO
            $cadenaSql = $this->sql->getCadenaSql('consultarTipoFormaPagoExep');
        } else {
            $cadenaSql = $this->sql->getCadenaSql('consultarTipoFormaPagoFull');
        }
        $datos = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
        echo json_encode($datos);
    }

    if ($_REQUEST ['funcion'] == 'ObtenersCdps') {

        if ($_REQUEST['cdpseleccion'] != "") {
            $seleccionados = "";
            $disponibilidades = explode(",", substr($_REQUEST['cdpseleccion'], 1));
            for ($i = 0; $i < count($disponibilidades); $i++) {
                if ($_REQUEST ['vigencia'] == explode("-", $disponibilidades[$i])[1]) {
                    $seleccionados .= "," . explode("-", $disponibilidades[$i])[0];
                }
            }
            if ($seleccionados != "") {
                $seleccionados = substr($seleccionados, 1);
            } else {
                $seleccionados = 0;
            }
        } else {
            $seleccionados = 0;
        }

        $datos = array('unidad_ejecutora' => $_REQUEST ['unidad'], 'vigencia' => $_REQUEST ['vigencia'], 'cdps_seleccion' => $seleccionados);
        $cadenaSql = $this->sql->getCadenaSql('obtener_necesidades_vigencia', $datos);
        $resultadoItems = $DBSICA->ejecutarAcceso($cadenaSql, "busqueda");
        $resultado = json_encode($resultadoItems);
        echo $resultado;
    }

    if ($_REQUEST ['funcion'] == 'ObtenerInfoCdps') {

        $datos = array('numero_disponibilidad' => $_REQUEST ['numero_disponibilidad'],
            'vigencia' => $_REQUEST ['vigencia'], 'unidad_ejecutora' => $_REQUEST ['unidad']);
        $cadenaSql = $this->sql->getCadenaSql('obtenerInfoNec', $datos);
        $resultadoItems = $DBSICA->ejecutarAcceso($cadenaSql, "busqueda");
        $resultadoArray[0] = $resultadoItems[0];
        $cadenaSql2 = $this->sql->getCadenaSql('requisitosNecesidad', $datos);
        $resultadoItems2 = $DBSICA->ejecutarAcceso($cadenaSql2, "busqueda");
        $resultadoArray[1] = $resultadoItems2;
        $cadenaSql3 = $this->sql->getCadenaSql('obtenerInfoNecOrdenador', $datos);
        $resultadoItems3 = $DBSICA->ejecutarAcceso($cadenaSql3, "busqueda");
        $resultadoArray[2] = $resultadoItems3[0];
        $cadenaSql4 = $this->sql->getCadenaSql('ordenadorUdistritalListAjax');
        $resultadoItems4 = $coreRecursoDB->ejecutarAcceso($cadenaSql4, "busqueda");
        $resultadoArray[3] = $resultadoItems4;
        $cadenaSql5 = $this->sql->getCadenaSql('salarioMinimoVigente');
        $resultadoItems5 = $coreRecursoDB->ejecutarAcceso($cadenaSql5, "busqueda");
        $resultadoArray[4] = $resultadoItems5[0];
        $cadenaSql6 = $this->sql->getCadenaSql('criteriosNecesidad', $datos);
        $resultadoItems6 = $DBSICA->ejecutarAcceso($cadenaSql6, "busqueda");
        $resultadoArray[5] = $resultadoItems6;
        $resultado = json_encode($resultadoArray);
        echo $resultado;
    }
    if ($_REQUEST ['funcion'] == 'consultarUnidad') {


        $cadenaSql = $this->sql->getCadenaSql('consultarUnidadByNumPush', $_REQUEST['valor']);
        $datos = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
        echo json_encode($datos);
    }
} else {

    if ($_REQUEST ['funcion'] == 'consultarTipoFormaPago') {
        $cadenaSql = $this->sql->getCadenaSql('consultarTipoFormaPagoByNumPush', $subclase);
        $datos = $coreRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
        echo json_encode($datos);
    }

    if ($_REQUEST ['funcion'] == 'consultarUnidad') {


        $cadenaSql = $this->sql->getCadenaSql('consultarUnidadByNumPush', $_REQUEST['valor']);
        $datos = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
        echo json_encode($datos);
    }

    if ($_REQUEST ['funcion'] == 'generarDocumentoCotizacion') {





        $tamaño_arreglo = json_decode($_REQUEST['tamanoLetra']);
        $tamaño_letra = $tamaño_arreglo[0];
        $variable_url = $_REQUEST['urlCot'];


        $variable_tamaño = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable_url . '&tamanoletra=' . $tamaño_letra, $directorio);





        echo json_encode($variable_tamaño);
    }


    if ($_REQUEST ['funcion'] == 'verificarArchivo') {


        $esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");
        // ** Ruta a directorio ******
        $rutaBloque = $this->miConfigurador->getVariableConfiguracion("raizDocumento") . "/blocks/gestionCotizaciones/";
        $rutaBloque .= $esteBloque ['nombre'];
        $host = $this->miConfigurador->getVariableConfiguracion("host") . $this->miConfigurador->getVariableConfiguracion("site") . "/blocks/gestionCotizaciones/" . $esteBloque ['nombre'];


        $tipo_validacion = '';
        $ingreso = 0;

        $ruta_eliminar_xlsx = $rutaBloque . "/archivo/*.xlsx";

        $ruta_eliminar_xls = $rutaBloque . "/archivo/*.xls";

        foreach (glob($ruta_eliminar_xlsx) as $filename) {
            unlink($filename);
        }
        foreach (glob($ruta_eliminar_xls) as $filename) {
            unlink($filename);
        }

        foreach ($_FILES as $key => $values) {
            $archivo [] = $_FILES [$key];
        }

        $archivo = $archivo [0];

        $trozos = explode(".", $archivo ['name']);
        $extension = end($trozos);
        if ($extension == 'xlsx') {
            if ($archivo) {
                // obtenemos los datos del archivo
                $tamano = $archivo ['size'];
                $tipo = $archivo ['type'];
                $archivo1 = $archivo ['name'];
                $prefijo = "archivo";

                if ($archivo1 != "") {
                    // guardamos el archivo a la carpeta files
                    $ruta_absoluta = $rutaBloque . "/archivo/" . $archivo1;
                    // echo $ruta_absoluta;exit;

                    if (copy($archivo ['tmp_name'], $ruta_absoluta)) {
                        $status = "Archivo subido: <b>" . $archivo1 . "</b>";
                    } else {
                        $tipo_validacion = 'error copia del archivo en el servidor';
                        exit();
                    }
                } else {
                    $tipo_validacion = 'error nombre del archivo';
                    exit();
                }
            }
            if (file_exists($ruta_absoluta)) {
                $objReader = new \PHPExcel_Reader_Excel2007 ();
                $objPHPExcel = $objReader->load($ruta_absoluta);
                $objFecha = new \PHPExcel_Shared_Date ();

                // Asignar hoja de excel activa

                $objPHPExcel->setActiveSheetIndex(0);
                $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
                $highestRow = $objWorksheet->getHighestRow();

                $arregloValidacion = 0;
                $arregloServicioValidacion = 0;

                if ($highestRow > 2) {


                    $arregloValidacion = 1;


                    for ($i = 3; $i <= $highestRow; $i++) {

                        $datos [$i] ['Tipo'] = $objPHPExcel->getActiveSheet()->getCell('A' . $i)->getCalculatedValue();
                        if (is_null($datos [$i] ['Tipo']) == true) {

                            $tipo_validacion = ' Datos vacios en Columna A - Fila Pestaña Bien ' . $i;
                            echo json_encode($tipo_validacion);
                            exit();
                        }

                        $datos [$i] ['Nombre'] = $objPHPExcel->getActiveSheet()->getCell('B' . $i)->getCalculatedValue();
                        if (is_null($datos [$i] ['Nombre']) == true) {

                            $tipo_validacion = ' Datos vacios en Columna B - Fila Pestaña Bien ' . $i;
                            echo json_encode($tipo_validacion);
                            exit();
                        }
                        $datos [$i] ['Descripcion'] = $objPHPExcel->getActiveSheet()->getCell('C' . $i)->getCalculatedValue();
                        if (is_null($datos [$i] ['Descripcion']) == true) {

                            $tipo_validacion = ' Datos vacios en Columna C - Fila Pestaña Bien ' . $i;
                            echo json_encode($tipo_validacion);
                            exit();
                        }
                        $datos [$i] ['Unidad'] = $objPHPExcel->getActiveSheet()->getCell('D' . $i)->getCalculatedValue();
                        if (is_null($datos [$i] ['Unidad']) == true) {

                            $tipo_validacion = ' Datos vacios en Columna D - Fila Pestaña Bien ' . $i;
                            echo json_encode($tipo_validacion);
                            exit();
                        }

                        $datos [$i] ['Cantidad'] = $objPHPExcel->getActiveSheet()->getCell('E' . $i)->getCalculatedValue();
                        if (is_null($datos [$i] ['Cantidad']) == true) {

                            $tipo_validacion = ' Datos vacios en Columna E - Fila Pestaña Bien ' . $i;
                            echo json_encode($tipo_validacion);
                            exit();
                        }
                    }



                    for ($i = 3; $i <= $highestRow; $i++) {

                        $arreglo[] = array(
                            'tipo' => trim($datos [$i] ['Tipo'], "'"),
                            'nombre' => trim($datos [$i] ['Nombre'], "'"),
                            'descripcion' => trim($datos [$i] ['Descripcion'], "'"),
                            'unidad' => trim($datos [$i] ['Unidad'], "'"),
                            'cantidad' => $datos [$i] ['Cantidad']
                        );
                    }
                }

                $objPHPExcel->setActiveSheetIndex(1);
                $objWorksheet = $objPHPExcel->setActiveSheetIndex(1);
                $highestRow = $objWorksheet->getHighestRow(0);

                if ($highestRow > 3) {


                    $arregloServicioValidacion = 1;

                    for ($i = 4; $i <= $highestRow; $i++) {

                        $datosServicio [$i] ['Tipo'] = $objPHPExcel->getActiveSheet()->getCell('A' . $i)->getCalculatedValue();
                        if (is_null($datosServicio [$i] ['Tipo']) == true) {

                            $tipo_validacion = ' Datos vacios en Columna A - Fila Pestaña Servicios' . $i;
                            echo json_encode($tipo_validacion);
                            exit();
                        }

                        $datosServicio [$i] ['Nombre'] = $objPHPExcel->getActiveSheet()->getCell('B' . $i)->getCalculatedValue();
                        if (is_null($datosServicio [$i] ['Nombre']) == true) {

                            $tipo_validacion = ' Datos vacios en Columna B - Fila Pestaña Servicios' . $i;
                            echo json_encode($tipo_validacion);
                            exit();
                        }
                        $datosServicio [$i] ['Descripcion'] = $objPHPExcel->getActiveSheet()->getCell('C' . $i)->getCalculatedValue();
                        if (is_null($datosServicio [$i] ['Descripcion']) == true) {

                            $tipo_validacion = ' Datos vacios en Columna C - Fila Pestaña Servicios' . $i;
                            echo json_encode($tipo_validacion);
                            exit();
                        }
                        $datosServicio [$i] ['Cantidad'] = $objPHPExcel->getActiveSheet()->getCell('D' . $i)->getCalculatedValue();
                        if (is_null($datosServicio [$i] ['Cantidad']) == true) {

                            $tipo_validacion = ' Datos vacios en Columna D - Fila Pestaña Servicios' . $i;
                            echo json_encode($tipo_validacion);
                            exit();
                        }
                    }


                    for ($i = 4; $i <= $highestRow; $i++) {

                        $arregloServicio[] = array(
                            'tipo' => trim($datosServicio [$i] ['Tipo'], "'"),
                            'nombre' => trim($datosServicio [$i] ['Nombre'], "'"),
                            'descripcion' => trim($datosServicio [$i] ['Descripcion'], "'"),
                            'cantidad' => $datosServicio [$i] ['Cantidad']
                        );
                    }
                }

                if ($arregloValidacion == 1 && $arregloServicioValidacion == 1) {
                    $arregloDefinitivo = array_merge($arreglo, $arregloServicio);
                } else {
                    if ($arregloValidacion == 1) {
                        $arregloDefinitivo = $arreglo;
                    } else {
                        if ($arregloServicioValidacion == 1) {
                            $arregloDefinitivo = $arregloServicio;
                        } else {
                            $arregloDefinitivo = null;
                        }
                    }
                }



                foreach (glob($ruta_eliminar_xlsx) as $filename) {
                    unlink($filename);
                }
                foreach (glob($ruta_eliminar_xls) as $filename) {
                    unlink($filename);
                }
            }
        } else {
            $tipo_validacion = 'error extension del archivo debe ser xlsx';
        }
//		
//                $cadenaSql = $this->sql->getCadenaSql ( 'tipoNecesidadAdministrativaOnlyBien');
//		$datos = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );

        if ($tipo_validacion != '') {
            $arreglo = $tipo_validacion;
        }

        echo json_encode($arregloDefinitivo);
    }
}
?>