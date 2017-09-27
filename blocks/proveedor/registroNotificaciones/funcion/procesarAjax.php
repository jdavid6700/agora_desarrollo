<?php

use asignacionPuntajes\salariales\premiosDocente\Sql;

$ruta_1 = $this->miConfigurador->getVariableConfiguracion('raizDocumento') . '/plugin/php_excel/Classes/PHPExcel.class.php';
$ruta_2 = $this->miConfigurador->getVariableConfiguracion('raizDocumento') . '/plugin/php_excel/Classes/PHPExcel/Reader/Excel2007.class.php';

include_once ($ruta_1);
include_once ($ruta_2);

$conexion = "estructura";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);


//Estas funciones se llaman desde ajax.php y estas a la vez realizan las consultas de Sql.class.php
//-------------------------------------------------
//-------------------------------------------------
//Validación Petición AJAX Parametro SQL Injection
if (isset($_REQUEST['valor']) && is_numeric($_REQUEST['valor'])) {
    settype($_REQUEST['valor'], 'integer');
    $secure = true;
} else {
    $secure = false;
}
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

    if ($_REQUEST ['funcion'] == 'consultarDepartamentoAjax') {
        $cadenaSql = $this->sql->getCadenaSql('buscarDepartamentoAjax', $_REQUEST['valor']);
        $resultado = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
        $resultado = json_encode($resultado);
        echo $resultado;
    }
    if ($_REQUEST ['funcion'] == 'consultarCiudadAjax') {
        $cadenaSql = $this->sql->getCadenaSql('buscarCiudadAjax', $_REQUEST['valor']);
        $resultado = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
        $resultado = json_encode($resultado);
        echo $resultado;
    }

    if ($_REQUEST ['funcion'] == 'consultarPaisAjax') {
        $cadenaSql = $this->sql->getCadenaSql('buscarPaisCod', $_REQUEST['valor']);
        $resultado = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
        $resultado = json_encode($resultado);
        echo $resultado;
    }

    if ($_REQUEST ['funcion'] == 'consultarNomenclatura') {
        $cadenaSql = $this->sql->getCadenaSql('buscarNomenclaturaAbreviatura', $_REQUEST['valor']);
        $resultado = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
        $resultado = json_encode($resultado);
        echo $resultado;
    }

    if ($_REQUEST ['funcion'] == 'consultarNBC') {
        $cadenaSql = $this->sql->getCadenaSql('buscarNBCAjax', $_REQUEST['valor']);
        $resultado = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
        $resultado = json_encode($resultado);
        echo $resultado;
    }

    if ($_REQUEST ['funcion'] == 'consultarUnidad') {
        $cadenaSql = $this->sql->getCadenaSql('consultarUnidadByNumPush', $_REQUEST['valor']);
        $datos = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
        echo json_encode($datos);
    }
} else {
    if ($_REQUEST ['funcion'] == 'verificarArchivo') {


        $esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");
        // ** Ruta a directorio ******
        $rutaBloque = $this->miConfigurador->getVariableConfiguracion("raizDocumento") . "/blocks/proveedor/";
        $rutaBloque .= $esteBloque ['nombre'];
        $host = $this->miConfigurador->getVariableConfiguracion("host") . $this->miConfigurador->getVariableConfiguracion("site") . "/blocks/proveedor/" . $esteBloque ['nombre'];


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
                
                $arregloValidacion=0;
                $arregloServicioValidacion=0;
                
                if($highestRow > 2){
                    
                
                    $arregloValidacion=1;
                    
          
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
                    $datos [$i] ['Valor_Unitario'] = $objPHPExcel->getActiveSheet()->getCell('F' . $i)->getCalculatedValue();

                    if (is_null($datos [$i] ['Valor_Unitario']) == true) {

                        $tipo_validacion = ' Datos vacios en Columna F - Fila Pestaña Bien ' . $i;
                        echo json_encode($tipo_validacion);
                        exit();
                    }
                    $datos [$i] ['Iva'] = $objPHPExcel->getActiveSheet()->getCell('G' . $i)->getCalculatedValue();

                    if (is_null($datos [$i] ['Iva']) == true) {

                        $tipo_validacion = ' Datos vacios en Columna G - Fila Pestaña Bien ' . $i;
                        echo json_encode($tipo_validacion);
                        exit();
                    }
                    
                     $datos [$i] ['Tiempo_Ejecucion_Ano'] = 0;
                     $datos [$i] ['Tiempo_Ejecucion_Mes']=0;
                     $datos [$i] ['Tiempo_Ejecucion_Dia'] = 0;  
                             
//                    $datos [$i] ['Tiempo_Ejecucion_Ano'] = $objPHPExcel->getActiveSheet()->getCell('E' . $i)->getCalculatedValue();
//                    if (($datos [$i] ['Tipo'] == 'SERVICIO') && is_null($datos [$i] ['Tiempo_Ejecucion_Ano']) == true) {
//
//                        $tipo_validacion = ' Datos vacios para Tiempo de Ejecucion en Columna E - Fila ' . $i;
//                        echo json_encode($tipo_validacion);
//                        exit();
//                    }
//                    $datos [$i] ['Tiempo_Ejecucion_Mes'] = $objPHPExcel->getActiveSheet()->getCell('F' . $i)->getCalculatedValue();
//                    if (($datos [$i] ['Tipo'] == 'SERVICIO') && is_null($datos [$i] ['Tiempo_Ejecucion_Mes']) == true) {
//
//                        $tipo_validacion = ' Datos vacios para Tiempo de Ejecucion en Columna E - Fila ' . $i;
//                        echo json_encode($tipo_validacion);
//                        exit();
//                    }
//                    $datos [$i] ['Tiempo_Ejecucion_Dia'] = $objPHPExcel->getActiveSheet()->getCell('G' . $i)->getCalculatedValue();
//                    if (($datos [$i] ['Tipo'] == 'SERVICIO') && is_null($datos [$i] ['Tiempo_Ejecucion_Dia']) == true) {
//
//                        $tipo_validacion = ' Datos vacios para Tiempo de Ejecucion en Columna E - Fila ' . $i;
//                        echo json_encode($tipo_validacion);
//                        exit();
//                    }
                 
               }
               
               
               
               for ($i = 3; $i <= $highestRow; $i++) {
                      
                        $arreglo[] = array(
                            'tipo' => trim($datos [$i] ['Tipo'], "'"),
                            'nombre' => trim($datos [$i] ['Nombre'], "'"),
                            'descripcion' => trim($datos [$i] ['Descripcion'], "'"),
                            'unidad' => trim($datos [$i] ['Unidad'], "'"),
                            'tiempo_ejecucion_ano' => $datos [$i] ['Tiempo_Ejecucion_Ano'],
                            'tiempo_ejecucion_mes' => $datos [$i] ['Tiempo_Ejecucion_Mes'],
                            'tiempo_ejecucion_dia' => $datos [$i] ['Tiempo_Ejecucion_Dia'],
                            'cantidad' => $datos [$i] ['Cantidad'],
                            'valor' => $datos [$i] ['Valor_Unitario'],
                            'iva' => trim($datos [$i] ['Iva'], "'")
                        );
                    }
                    
                 }   
               
                 $objPHPExcel->setActiveSheetIndex(1);
                $objWorksheet = $objPHPExcel->setActiveSheetIndex(1);
                $highestRow = $objWorksheet->getHighestRow(0);
                
                if($highestRow>3){
                    
                    
                $arregloServicioValidacion=1;
                
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
                    $datosServicio [$i] ['Unidad'] = $objPHPExcel->getActiveSheet()->getCell('D' . $i)->getCalculatedValue();
                    if (is_null($datosServicio [$i] ['Unidad']) == true) {

                        $tipo_validacion = ' Datos vacios en Columna D - Fila Pestaña Servicios' . $i;
                        echo json_encode($tipo_validacion);
                        exit();
                    }
                    
                    $datosServicio [$i] ['Tiempo_Ejecucion_Ano'] = $objPHPExcel->getActiveSheet()->getCell('E' . $i)->getCalculatedValue();
                    if (($datosServicio [$i] ['Tipo'] == 'SERVICIO') && is_null($datosServicio [$i] ['Tiempo_Ejecucion_Ano']) == true) {

                        $tipo_validacion = ' Datos vacios para Tiempo de Ejecucion en Columna E - Fila Pestaña Servicios' . $i;
                        echo json_encode($tipo_validacion);
                        exit();
                    }
                    $datosServicio [$i] ['Tiempo_Ejecucion_Mes'] = $objPHPExcel->getActiveSheet()->getCell('F' . $i)->getCalculatedValue();
                    if (($datosServicio [$i] ['Tipo'] == 'SERVICIO') && is_null($datosServicio [$i] ['Tiempo_Ejecucion_Mes']) == true) {

                        $tipo_validacion = ' Datos vacios para Tiempo de Ejecucion en Columna F - Fila Pestaña Servicios' . $i;
                        echo json_encode($tipo_validacion);
                        exit();
                    }
                    $datosServicio [$i] ['Tiempo_Ejecucion_Dia'] = $objPHPExcel->getActiveSheet()->getCell('G' . $i)->getCalculatedValue();
                    if (($datosServicio [$i] ['Tipo'] == 'SERVICIO') && is_null($datosServicio [$i] ['Tiempo_Ejecucion_Dia']) == true) {

                        $tipo_validacion = ' Datos vacios para Tiempo de Ejecucion en Columna G - Fila Pestaña Servicios' . $i;
                        echo json_encode($tipo_validacion);
                        exit();
                    }
                       $datosServicio [$i] ['Cantidad'] = $objPHPExcel->getActiveSheet()->getCell('H' . $i)->getCalculatedValue();
                    if (is_null($datosServicio [$i] ['Cantidad']) == true) {

                        $tipo_validacion = ' Datos vacios en Columna H - Fila Pestaña Servicios' . $i;
                        echo json_encode($tipo_validacion);
                        exit();
                    }
                    $datosServicio [$i] ['Valor_Unitario'] = $objPHPExcel->getActiveSheet()->getCell('I' . $i)->getCalculatedValue();

                    if (is_null($datosServicio [$i] ['Valor_Unitario']) == true) {

                        $tipo_validacion = ' Datos vacios en Columna I - Fila Pestaña Servicios' . $i;
                        echo json_encode($tipo_validacion);
                        exit();
                    }
                    $datosServicio [$i] ['Iva'] = $objPHPExcel->getActiveSheet()->getCell('J' . $i)->getCalculatedValue();

                    if (is_null($datosServicio [$i] ['Iva']) == true) {

                        $tipo_validacion = ' Datos vacios en Columna J - Fila Pestaña Servicios' . $i;
                        echo json_encode($tipo_validacion);
                        exit();
                    }
  
                             
                    
                 
               }
                
               
                for ($i = 4; $i <= $highestRow; $i++) {
                      
                        $arregloServicio[] = array(
                            'tipo' => trim($datosServicio [$i] ['Tipo'], "'"),
                            'nombre' => trim($datosServicio [$i] ['Nombre'], "'"),
                            'descripcion' => trim($datosServicio [$i] ['Descripcion'], "'"),
                            'unidad' => trim($datosServicio [$i] ['Unidad'], "'"),
                            'tiempo_ejecucion_ano' => $datosServicio [$i] ['Tiempo_Ejecucion_Ano'],
                            'tiempo_ejecucion_mes' => $datosServicio [$i] ['Tiempo_Ejecucion_Mes'],
                            'tiempo_ejecucion_dia' => $datosServicio [$i] ['Tiempo_Ejecucion_Dia'],
                            'cantidad' => $datosServicio [$i] ['Cantidad'],
                            'valor' => $datosServicio [$i] ['Valor_Unitario'],
                            'iva' => trim($datosServicio [$i] ['Iva'], "'")
                        );
                }
                
                }
                
                if($arregloValidacion==1 && $arregloServicioValidacion ==1){
                    $arregloDefinitivo=array_merge ( $arreglo, $arregloServicio );
                }
                else{
                    if($arregloValidacion==1){
                        $arregloDefinitivo=$arreglo;
                    }
                    else{
                        if($arregloServicioValidacion==1){
                        $arregloDefinitivo=$arreglo;
                        }
                        else{
                            $arregloDefinitivo=null;
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
        
        if($tipo_validacion != ''){
            $arreglo=$tipo_validacion;
        }

        echo json_encode($arregloDefinitivo);
    }
}
?>