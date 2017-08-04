<?php

namespace proveedor\registroProveedor\formulario;

if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
}

class FormularioRegistro {

    var $miConfigurador;
    var $lenguaje;
    var $miFormulario;
    var $miSql;

    function __construct($lenguaje, $formulario, $sql) {

        $this->miConfigurador = \Configurador::singleton();

        $this->miConfigurador->fabricaConexiones->setRecursoDB('principal');
        $this->lenguaje = $lenguaje;

        $this->miFormulario = $formulario;

        $this->miSql = $sql;
    }
    
    function cambiafecha_format($fecha) {
    	ereg("([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})", $fecha, $mifecha);
    	$fechana = $mifecha[3] . "/" . $mifecha[2] . "/" . $mifecha[1];
    	return $fechana;
    }
    
    function inverseTotalDias($days){
    
    	$nyears = intval($days/360);
    	$nmonths = intval(($days-intval($days/360)*360)/30);
    	$ndays = intval($days-(intval($days/360)*360+intval(($days-intval($days/360)*360)/30)*30));
    		
    	return $nyears . " AÑO(S) - " . $nmonths . " MES(ES) - " . $ndays . " DÍA(S)";
    		
    }

    function formulario() {

        /**
         * IMPORTANTE: Este formulario está utilizando jquery.
         * Por tanto en el archivo ready.php se delaran algunas funciones js
         * que lo complementan.
         */
        //*************************************************************************** DBMS *******************************
        //****************************************************************************************************************

        $conexion = 'estructura';
        $esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

        //$conexion = 'sicapital';
        //$siCapitalRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
        //$conexion = 'centralUD';
        //$centralUDRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

        $conexion = 'argo_contratos';
        $argoRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

        $conexion = 'core_central';
        $coreRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

        $conexion = 'framework';
        $frameworkRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

        //*************************************************************************** DBMS *******************************
        //****************************************************************************************************************
        // Rescatar los datos de este bloque
        $esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");
        $miPaginaActual = $this->miConfigurador->getVariableConfiguracion('pagina');

        $directorio = $this->miConfigurador->getVariableConfiguracion("host");
        $directorio .= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
        $directorio .= $this->miConfigurador->getVariableConfiguracion("enlace");

        $rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
        $rutaBloque .= $this->miConfigurador->getVariableConfiguracion("site") . "/blocks/";
        $rutaBloque .= $esteBloque ['grupo'] . '/' . $esteBloque ['nombre'];

        // ---------------- SECCION: Parámetros Globales del Formulario ----------------------------------
        /**
         * Atributos que deben ser aplicados a todos los controles de este formulario.
         * Se utiliza un arreglo
         * independiente debido a que los atributos individuales se reinician cada vez que se declara un campo.
         *
         * Si se utiliza esta técnica es necesario realizar un mezcla entre este arreglo y el específico en cada control:
         * $atributos= array_merge($atributos,$atributosGlobales);
         */
        $atributosGlobales ['campoSeguro'] = 'true';
        $_REQUEST ['tiempo'] = time();

        // -------------------------------------------------------------------------------------------------
        // ---------------- SECCION: Parámetros Generales del Formulario ----------------------------------
        $esteCampo = $esteBloque ['nombre'] . "Registrar";
        $atributos ['id'] = $esteCampo;
        $atributos ['nombre'] = $esteCampo;

        // Si no se coloca, entonces toma el valor predeterminado 'application/x-www-form-urlencoded'
        $atributos ['tipoFormulario'] = 'multipart/form-data';

        // Si no se coloca, entonces toma el valor predeterminado 'POST'
        $atributos ['metodo'] = 'POST';

        // Si no se coloca, entonces toma el valor predeterminado 'index.php' (Recomendado)
        $atributos ['action'] = 'index.php';
        $atributos ['titulo'] = '';

        // Si no se coloca, entonces toma el valor predeterminado.
        $atributos ['estilo'] = '';
        $atributos ['marco'] = false;
        $tab = 1;

        // ---------------- FIN SECCION: de Parámetros Generales del Formulario ----------------------------
        // ----------------INICIAR EL FORMULARIO ------------------------------------------------------------
        $atributos ['tipoEtiqueta'] = 'inicio';
        // Aplica atributos globales al control
        echo $this->miFormulario->formulario($atributos);





        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
        $esteCampo = "marcoContratos";
        $atributos ['id'] = $esteCampo;
        $atributos ["estilo"] = "jqueryui";
        $atributos ['tipoEtiqueta'] = 'inicio';
        $atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo);
        echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);
        unset($atributos);

		

        //****************************************************************************************
        //****************************************************************************************
        
        $datosConsultaSol = array(
            'proveedor' =>$_REQUEST['id_proveedor'],
            'solicitud' => $_REQUEST['idSolicitud']
        );
        
        $cadenaSql = $this->miSql->getCadenaSql('consultarIdsolicitud', $datosConsultaSol);
        $id_solicitud= $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda", $datosConsultaSol, 'consultarIdsolicitud');


        $cadenaSql = $this->miSql->getCadenaSql('consultar_respuesta', $id_solicitud[0][0]);
        $resultadoRespuesta = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");

        
        $cadenaSql = $this->miSql->getCadenaSql('consultar_proveedor', $_REQUEST ["usuario"]);
        $resultadoDoc = $frameworkRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");

		


        $numeroDocumento = $resultadoDoc[0]['identificacion'];

        $cadenaSql = $this->miSql->getCadenaSql('consultar_DatosProveedor', $numeroDocumento);
        $resultadoDats = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");

        $idProveedor = $resultadoDats[0]['id_proveedor'];
        $tipoPersona = $resultadoDats[0]['tipopersona'];
        $nombrePersona = $resultadoDats[0]['nom_proveedor'];
        $correo = $resultadoDats[0]['correo'];
        $direccion = $resultadoDats[0]['direccion'];

        $esteCampo = "marcoInfoCont";
        $atributos ['id'] = $esteCampo;
        $atributos ["estilo"] = "jqueryui";
        $atributos ['tipoEtiqueta'] = 'inicio';
        $atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo);
        echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);
        {


            //INICIO INFORMACION
            echo "<span class='textoElegante textoGrande textoAzul'>Nombre de la Persona: </span>";
            echo "<span class='textoElegante textoGrande textoGris'>" . $nombrePersona . "</span></br>";
            echo "<span class='textoElegante textoGrande textoAzul'>Documento : </span>";
            echo "<span class='textoElegante textoGrande textoGris'>" . $numeroDocumento . "</span></br>";
            echo "<span class='textoElegante textoGrande textoAzul'>Tipo Persona : </span>";
            echo "<span class='textoElegante textoGrande textoGris'>" . $tipoPersona . "</span></br>";
            echo "<span class='textoElegante textoGrande textoAzul'>Dirección : </span>";
            echo "<span class='textoElegante textoGrande textoGris'>" . $direccion . "</span></br>";
            echo "<span class='textoElegante textoGrande textoAzul'>Correo : </span>";
            echo "<span class='textoElegante textoGrande textoGris'>" . $correo . "</span></br>";
            //FIN INFORMACION
        }
        echo $this->miFormulario->marcoAgrupacion('fin', $atributos);

        if (isset($_REQUEST['tipoCotizacion']) && $_REQUEST['tipoCotizacion'] == 'BIEN') {
            $campo1 = "entregables";
            $campo2 = "plazoEntrega";
        } else {
            if (isset($_REQUEST['tipoCotizacion']) && $_REQUEST['tipoCotizacion'] == 'SERVICIO') {
                $campo1 = "desServicio";
                $campo2 = "detalleEjecucion";
            } else {
                $campo1 = "entregablesdesServicio";
                $campo2 = "entregaEjecucion";
            }
        }


        $esteCampo = "marcoContratosTablaRes";
        $atributos ['id'] = $esteCampo;
        $atributos ["estilo"] = "jqueryui";
        $atributos ['tipoEtiqueta'] = 'inicio';
        $atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo);
        echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);
        {


            // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
            $esteCampo = $campo1;
            $atributos ['id'] = $esteCampo;
            $atributos ['nombre'] = $esteCampo;
            $atributos ['tipo'] = 'text';
            $atributos ['estilo'] = 'jqueryui';
            $atributos ['marco'] = true;
            $atributos ['estiloMarco'] = '';
            $atributos ["etiquetaObligatorio"] = false;
            $atributos ['columnas'] = 120;
            $atributos ['filas'] = 8;
            $atributos ['dobleLinea'] = 0;
            $atributos ['tabIndex'] = $tab;
            $atributos ['etiqueta'] = $this->lenguaje->getCadena($campo1);
            $atributos ['validar'] = 'required,minSize[20]';
            $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
            $atributos ['deshabilitado'] = true;
            $atributos ['tamanno'] = 20;
            $atributos ['maximoTamanno'] = '';
            $atributos ['anchoEtiqueta'] = 220;
            $atributos ['textoEnriquecido'] = true; //Este atributo se coloca una sola vez en todo el formulario (ERROR paso de datos)

            $atributos ['valor'] = $resultadoRespuesta[0]['descripcion'];

            $tab ++;

            // Aplica atributos globales al control
            $atributos = array_merge($atributos, $atributosGlobales);
            echo $this->miFormulario->campoTextArea($atributos);
            unset($atributos);


            // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
            $esteCampo = $campo2;
            $atributos ['id'] = $esteCampo;
            $atributos ['nombre'] = $esteCampo;
            $atributos ['tipo'] = 'text';
            $atributos ['estilo'] = 'jqueryui';
            $atributos ['marco'] = true;
            $atributos ['estiloMarco'] = '';
            $atributos ["etiquetaObligatorio"] = false;
            $atributos ['columnas'] = 120;
            $atributos ['filas'] = 8;
            $atributos ['dobleLinea'] = 0;
            $atributos ['tabIndex'] = $tab;
            $atributos ['etiqueta'] = $this->lenguaje->getCadena($campo2);
            $atributos ['validar'] = 'required,minSize[20]';
            $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
            $atributos ['deshabilitado'] = true;
            $atributos ['tamanno'] = 20;
            $atributos ['maximoTamanno'] = '';
            $atributos ['anchoEtiqueta'] = 220;



            $atributos ['valor'] = $resultadoRespuesta[0]['informacion_entrega'];

            $tab ++;

            // Aplica atributos globales al control
            $atributos = array_merge($atributos, $atributosGlobales);
            echo $this->miFormulario->campoTextArea($atributos);
            unset($atributos);
            
            $cadena_sql = $this->miSql->getCadenaSql ( "buscarDetalleItemsProducto", $resultadoRespuesta[0]['id']);
            $resultadoItems = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
            
            
            $esteCampo = "marcoDescripcionProducto";
            $atributos ['id'] = $esteCampo;
            $atributos ["estilo"] = "jqueryui";
            $atributos ['tipoEtiqueta'] = 'inicio';
            $atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo);
            echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);
            {
            	 
            	$esteCampo = "marcoDetallePro";
            	$atributos ['id'] = $esteCampo;
            	$atributos ["estilo"] = "jqueryui";
            	$atributos ['tipoEtiqueta'] = 'inicio';
            	$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
            	echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
            
            	 
            	unset ( $atributos );
            	 
            	 
            	?>
                            			
                            						
                            						<table id="tablaFP" class="table1" width="100%" >
                            							<!-- Cabecera de la tabla -->
                            							<thead>
                            								<tr>
                            									<th width="10%" >Nombre</th>
                            									<th width="25%" >Descripción</th>
                            									<th width="10%" >Tipo</th>
                            									<th width="10%" >Unidad</th>
                            									<th width="20%" >Tiempo de Ejecución</th>
                            									<th width="10%" >Cantidad</th>
                            									<th width="10%" >Valor Unitario</th>
                            									<th width="5%" >&nbsp;</th>
                            								</tr>
                            							</thead>
                            						 
                            							<!-- Cuerpo de la tabla con los campos -->
														<tbody>
													 
															<!-- fila base para clonar y agregar al final -->
															<!-- fin de código: fila base -->
													 		
						
													 		
													 		<?php 
													 		
													 		$valorPrecioTotal = 0;
													 		
													 		if (isset ( $resultadoItems ) && $resultadoItems) {
													 		$count = count($resultadoItems);
													 		$i = 0;
													 		
														 		while ($i < $count){
														 			
														 			if($resultadoItems[$i]['tipo_necesidad'] == 1){
														 				$tipo = "1 - BIEN";
														 			}else{
														 				$tipo = "2 - SERVICIO";
														 			}
														 			
														 			if($resultadoItems[$i]['unidad'] == 0){
														 				$unidad = "0 - NO APLICA";
														 			}else{
														 				
														 				$cadena_sql = $this->miSql->getCadenaSql ( "buscarUnidadItem", $resultadoItems[$i]['unidad']);
														 				$resultadoUnidadItem = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
								
														 				$unidad = $resultadoUnidadItem[0]['id']." - ".$resultadoUnidadItem[0]['unidad'];
														 			}
														 			
														 			if($resultadoItems[$i]['tiempo_ejecucion'] == 0){
														 				$tiempo = "0 - NO APLICA";
														 			}else{
														 				$tiempo = $this->inverseTotalDias($resultadoItems[$i]['tiempo_ejecucion']);
														 			}
														 			
														 			$valorPrecioTotal += ($resultadoItems[$i]['cantidad'] * $resultadoItems[$i]['valor_unitario']);
														 			
														 		?>
																<tr id="nFilas" >
																	<td><?php echo $resultadoItems[$i]['nombre']  ?></td>
													 				<td><?php echo $resultadoItems[$i]['descripcion']  ?></td>
													 				<td><?php echo $tipo  ?></td>
													 				<td><?php echo $unidad  ?></td>
													 				<td><?php echo $tiempo  ?></td>
													 				<td><?php echo round($resultadoItems[$i]['cantidad'],0)  ?></td>
													 				<td><?php echo "$ " . round($resultadoItems[$i]['valor_unitario'],0)  ?></td>
													 				<th scope="row"><div class = "widget"><?php echo $i+1  ?></div></th>
													 			</tr>
																<?php
																$i++;
														 		}
													 		}
													 	
															
															?>
													 
														</tbody>
                            						</table>
                            						<!-- Botón para agregar filas -->
                            						<!-- 
                            						<input type="button" id="agregar" value="Agregar fila" /> -->
                            						
                            			
                            			
                            						
                            						
                            						<?php
                            						
                            						
                            						
                            	
                            						
                            						echo $this->miFormulario->marcoAgrupacion ( 'fin' );
                            			
                            						unset ( $atributos );
                            						$esteCampo = 'precioCarga';
                            						$atributos ["id"] = $esteCampo; // No cambiar este nombre
                            						$atributos ["tipo"] = "hidden";
                            						$atributos ['estilo'] = '';
                            						$atributos ["obligatorio"] = false;
                            						$atributos ['marco'] = false;
                            						$atributos ["etiqueta"] = "";
                            						
                            						$atributos ['valor'] = $valorPrecioTotal;
                            						
                            						$atributos ['validar'] = '';
                            						$atributos = array_merge ( $atributos, $atributosGlobales );
                            						echo $this->miFormulario->campoCuadroTexto ( $atributos );
                            						unset ( $atributos );
                            						
                            						unset ( $atributos );
                            						$esteCampo = 'idsItems';
                            						$atributos ["id"] = $esteCampo; // No cambiar este nombre
                            						$atributos ["tipo"] = "hidden";
                            						$atributos ['estilo'] = '';
                            						$atributos ["obligatorio"] = false;
                            						$atributos ['marco'] = false;
                            						$atributos ["etiqueta"] = "";
                            						if (isset ( $_REQUEST [$esteCampo] )) {
                            							$atributos ['valor'] = $_REQUEST [$esteCampo];
                            						} else {
                            							$atributos ['valor'] = '';
                            						}
                            						$atributos ['validar'] = '';
                            						$atributos = array_merge ( $atributos, $atributosGlobales );
                            						echo $this->miFormulario->campoCuadroTexto ( $atributos );
                            						unset ( $atributos );
                            						
                            						$esteCampo = 'countItems';
                            						$atributos ["id"] = $esteCampo; // No cambiar este nombre
                            						$atributos ["tipo"] = "hidden";
                            						$atributos ['estilo'] = '';
                            						$atributos ["obligatorio"] = false;
                            						$atributos ['marco'] = false;
                            						$atributos ["etiqueta"] = "";
                            						if (isset ( $_REQUEST [$esteCampo] )) {
                            							$atributos ['valor'] = $_REQUEST [$esteCampo];
                            						} else {
                            							$atributos ['valor'] = '';
                            						}
                            						$atributos ['validar'] = '';
                            						$atributos = array_merge ( $atributos, $atributosGlobales );
                            						echo $this->miFormulario->campoCuadroTexto ( $atributos );
                            						unset ( $atributos );
                            						
                            			echo $this->miFormulario->marcoAgrupacion ( 'fin' );
                            
                            
                            // ----------------INICIO CONTROL: Campo de Texto FECHA SOLICITUD--------------------------------------------------------
                            $esteCampo = 'precioCot';
                            $atributos ['id'] = $esteCampo;
                            $atributos ['nombre'] = $esteCampo;
                            $atributos ['tipo'] = 'text';
                            $atributos ['estilo'] = 'jqueryui';
                            $atributos ['marco'] = true;
                            $atributos ['estiloMarco'] = '';
                            $atributos ["etiquetaObligatorio"] = true;
                            $atributos ['columnas'] = 1;
                            $atributos ['dobleLinea'] = 0;
                            $atributos ['tabIndex'] = $tab;
                            $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                            $atributos ['validar'] = 'required';
                            
                            $atributos ['valor'] = '';
                            
                            $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
                            $atributos ['deshabilitado'] = true;
                            $atributos ['tamanno'] = 75;
                            $atributos ['maximoTamanno'] = '75';
                            $atributos ['anchoEtiqueta'] = 400;
                            $tab ++;
                            
                            // Aplica atributos globales al control
                            $atributos = array_merge($atributos, $atributosGlobales);
                            echo $this->miFormulario->campoCuadroTexto($atributos);
                            unset($atributos);
                            // ----------------FIN CONTROL: Campo de Texto FECHA SOLICITUD--------------------------------------------------------
                            // ----------------INICIO CONTROL: Campo de Texto FECHA SOLICITUD--------------------------------------------------------
                            $esteCampo = 'fechaVencimientoCotRead';
                            $atributos ['id'] = $esteCampo;
                            $atributos ['nombre'] = $esteCampo;
                            $atributos ['tipo'] = 'text';
                            $atributos ['estilo'] = 'jqueryui';
                            $atributos ['marco'] = true;
                            $atributos ['estiloMarco'] = '';
                            $atributos ["etiquetaObligatorio"] = true;
                            $atributos ['columnas'] = 1;
                            $atributos ['dobleLinea'] = 0;
                            $atributos ['tabIndex'] = $tab;
                            $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                            $atributos ['validar'] = 'required,custom[date]';
                            
                            $atributos ['valor'] =  $this->cambiafecha_format($resultadoRespuesta[0]['fecha_vencimiento']);
                            
                            $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
                            $atributos ['deshabilitado'] = true;
                            $atributos ['tamanno'] = 15;
                            $atributos ['maximoTamanno'] = '30';
                            $atributos ['anchoEtiqueta'] = 400;
                            $tab ++;
                            
                            // Aplica atributos globales al control
                            $atributos = array_merge($atributos, $atributosGlobales);
                            echo $this->miFormulario->campoCuadroTexto($atributos);
                            unset($atributos);
                            // ----------------FIN CONTROL: Campo de Texto FECHA SOLICITUD--------------------------------------------------------
                            
                            
                            
                            
                            
                            
            
            
                           
                            // ----------------FIN CONTROL: Campo de Texto FECHA SOLICITUD--------------------------------------------------------
                            // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                            $esteCampo = 'descuentos';
                            $atributos ['id'] = $esteCampo;
                            $atributos ['nombre'] = $esteCampo;
                            $atributos ['tipo'] = 'text';
                            $atributos ['estilo'] = 'jqueryui';
                            $atributos ['marco'] = true;
                            $atributos ['estiloMarco'] = '';
                            $atributos ["etiquetaObligatorio"] = true;
                            $atributos ['columnas'] = 120;
                            $atributos ['filas'] = 8;
                            $atributos ['dobleLinea'] = 0;
                            $atributos ['tabIndex'] = $tab;
                            $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                            $atributos ['validar'] = '';
                            $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
                            $atributos ['deshabilitado'] = false;
                            $atributos ['tamanno'] = 20;
                            $atributos ['maximoTamanno'] = '';
                            $atributos ['anchoEtiqueta'] = 220;
            
            
                            $atributos ['valor'] = $resultadoRespuesta[0]['descuentos'];
            
                            $tab ++;
            
                            // Aplica atributos globales al control
                            $atributos = array_merge($atributos, $atributosGlobales);
                            echo $this->miFormulario->campoTextArea($atributos);
                            unset($atributos);
            
                            
                            
                            
                            
                            
                            
            }
            echo $this->miFormulario->marcoAgrupacion('fin');


            $esteCampo = "marcoAnexo";
            $atributos ['id'] = $esteCampo;
            $atributos ["estilo"] = "jqueryui";
            $atributos ['tipoEtiqueta'] = 'inicio';
            $atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo);
            echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);
            // ----------------INICIO CONTROL: DOCUMENTO--------------------------------------------------------
            // ----------------INICIO CONTROL: DOCUMENTO--------------------------------------------------------
            $esteCampo = "cotizacionSoporte";
            $atributos ["id"] = $esteCampo; // No cambiar este nombre
            $atributos ["nombre"] = $esteCampo;
            $atributos ["tipo"] = "file";
            // $atributos ["obligatorio"] = true;
            $atributos ["etiquetaObligatorio"] = true;
            $atributos ["tabIndex"] = $tab ++;
            $atributos ["columnas"] = 1;
            $atributos ["estilo"] = "textoIzquierda";
            $atributos ["anchoEtiqueta"] = 400;
            $atributos ["tamanno"] = 500000;
            $atributos ["validar"] = "required";
            $atributos ["etiqueta"] = $this->lenguaje->getCadena($esteCampo);
            // $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
            // $atributos ["valor"] = $valorCodificado;
            $atributos = array_merge($atributos, $atributosGlobales);
            //echo $this->miFormulario->campoCuadroTexto ( $atributos );
          

            $atributos["id"] = "botones";
            $atributos["estilo"] = "marcoBotones widget";
            echo $this->miFormulario->division("inicio", $atributos);

            $enlace = "<br><a href='" .$resultadoRespuesta[0]['soporte_cotizacion'] . "' target='_blank'>";
            $enlace.="<img src='" . $rutaBloque . "/images/pdf.png' width='35px'><br>Anexo Cotización Detallada ";
            $enlace.="</a>";
            echo $enlace;
            //------------------Fin Division para los botones-------------------------
            echo $this->miFormulario->division("fin");
            //FIN enlace boton descargar RUT

            echo $this->miFormulario->marcoAgrupacion('fin');


            $esteCampo = 'observaciones';
            $atributos ['id'] = $esteCampo;
            $atributos ['nombre'] = $esteCampo;
            $atributos ['tipo'] = 'text';
            $atributos ['estilo'] = 'jqueryui';
            $atributos ['marco'] = true;
            $atributos ['estiloMarco'] = '';
            $atributos ["etiquetaObligatorio"] = true;
            $atributos ['columnas'] = 120;
            $atributos ['filas'] = 8;
            $atributos ['dobleLinea'] = 0;
            $atributos ['tabIndex'] = $tab;
            $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
            $atributos ['validar'] = '';
            $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
            $atributos ['deshabilitado'] = false;
            $atributos ['tamanno'] = 20;
            $atributos ['maximoTamanno'] = '';
            $atributos ['anchoEtiqueta'] = 220;
            
            
            $atributos ['valor'] = $resultadoRespuesta[0]['observaciones'];
            
            $tab ++;
            
            // Aplica atributos globales al control
            $atributos = array_merge ( $atributos, $atributosGlobales );
            echo $this->miFormulario->campoTextArea ( $atributos );
            unset ( $atributos );



            // ------------------Division para los botones-------------------------
            $atributos ["id"] = "botones";
            $atributos ["estilo"] = "marcoBotones";
            echo $this->miFormulario->division("inicio", $atributos);
            {
                // -----------------CONTROL: Botón ----------------------------------------------------------------



                $esteCampo = 'regresar';



                $atributos ["id"] = $esteCampo;
                $atributos ["tabIndex"] = $tab;
                $atributos ["tipo"] = 'boton';
                // submit: no se coloca si se desea un tipo button genérico
                $atributos ['submit'] = 'true';
                $atributos ["estiloMarco"] = '';
                $atributos ["estiloBoton"] = 'jqueryui';
                // verificar: true para verificar el formulario antes de pasarlo al servidor.
                $atributos ["verificar"] = '';
                $atributos ["tipoSubmit"] = 'jquery'; // Dejar vacio para un submit normal, en este caso se ejecuta la función submit declarada en ready.js
                $atributos ["valor"] = $this->lenguaje->getCadena($esteCampo);
                $atributos ['nombreFormulario'] = $esteBloque ['nombre'] . "Registrar";
                $tab ++;

                // Aplica atributos globales al control
                $atributos = array_merge($atributos, $atributosGlobales);
                echo $this->miFormulario->campoBoton($atributos);

                // -----------------FIN CONTROL: Botón -----------------------------------------------------------
            }
            // ------------------Fin Division para los botones-------------------------
            echo $this->miFormulario->division("fin");
        }
        echo $this->miFormulario->marcoAgrupacion('fin', $atributos);




// ------------------- SECCION: Paso de variables ------------------------------------------------

        /**
         * En algunas ocasiones es útil pasar variables entre las diferentes páginas.
         * SARA permite realizar esto a través de tres
         * mecanismos:
         * (a). Registrando las variables como variables de sesión. Estarán disponibles durante toda la sesión de usuario. Requiere acceso a
         * la base de datos.
         * (b). Incluirlas de manera codificada como campos de los formularios. Para ello se utiliza un campo especial denominado
         * formsara, cuyo valor será una cadena codificada que contiene las variables.
         * (c) a través de campos ocultos en los formularios. (deprecated)
         */
        // En este formulario se utiliza el mecanismo (b) para pasar las siguientes variables:
        // Paso 1: crear el listado de variables
//		$valorCodificado  = "action=" . $esteBloque ["nombre"];
        $valorCodificado = "&pagina=" . $this->miConfigurador->getVariableConfiguracion('pagina');
        $valorCodificado .= "&bloque=" . $esteBloque ['nombre'];
        $valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
        $valorCodificado .= "&opcion=mostrar";
        $valorCodificado .= "&usuario=" . $_REQUEST['usuario'];
        $valorCodificado .= "&solicitud=" . $_REQUEST['idSolicitud'];
        $valorCodificado .= "&tipoCotizacion=" . $_REQUEST['tipoCotizacion'];

        /**
         * SARA permite que los nombres de los campos sean dinámicos.
         * Para ello utiliza la hora en que es creado el formulario para
         * codificar el nombre de cada campo.
         */
        $valorCodificado .= "&campoSeguro=" . $_REQUEST ['tiempo'];
        $valorCodificado .= "&tiempo=" . time();
        /*
         * Sara permite validar los campos en el formulario o funcion destino.
         * Para ello se envía los datos atributos["validadar"] de los componentes del formulario
         * Estos se pueden obtener en el atributo $this->miFormulario->validadorCampos del formulario
         * La función $this->miFormulario->codificarCampos() codifica automáticamente el atributo validadorCampos
         */
        $valorCodificado .= "&validadorCampos=" . $this->miFormulario->codificarCampos();

        // Paso 2: codificar la cadena resultante
        $valorCodificado = $this->miConfigurador->fabricaConexiones->crypto->codificar($valorCodificado);

        $atributos ["id"] = "formSaraData"; // No cambiar este nombre
        $atributos ["tipo"] = "hidden";
        $atributos ['estilo'] = '';
        $atributos ["obligatorio"] = false;
        $atributos ['marco'] = true;
        $atributos ["etiqueta"] = "";
        $atributos ["valor"] = $valorCodificado;
        echo $this->miFormulario->campoCuadroTexto($atributos);
        unset($atributos);

        $atributos ['marco'] = false;
        $atributos ['tipoEtiqueta'] = 'fin';
        echo $this->miFormulario->formulario($atributos);

        // ----------------FIN SECCION: Paso de variables -------------------------------------------------
        // ---------------- FIN SECCION: Controles del Formulario -------------------------------------------
        // ----------------FINALIZAR EL FORMULARIO ----------------------------------------------------------
        // Se debe declarar el mismo atributo de marco con que se inició el formulario.
    }

    function mensaje() {

        // Si existe algun tipo de error en el login aparece el siguiente mensaje
        $mensaje = $this->miConfigurador->getVariableConfiguracion('mostrarMensaje');
        $this->miConfigurador->setVariableConfiguracion('mostrarMensaje', null);

        if ($mensaje) {

            $tipoMensaje = $this->miConfigurador->getVariableConfiguracion('tipoMensaje');

            if ($tipoMensaje == 'json') {

                $atributos ['mensaje'] = $mensaje;
                $atributos ['json'] = true;
            } else {
                $atributos ['mensaje'] = $this->lenguaje->getCadena($mensaje);
            }
            // -------------Control texto-----------------------
            $esteCampo = 'divMensaje';
            $atributos ['id'] = $esteCampo;
            $atributos ["tamanno"] = '';
            $atributos ["estilo"] = 'information';
            $atributos ["etiqueta"] = '';
            $atributos ["columnas"] = ''; // El control ocupa 47% del tamaño del formulario
            echo $this->miFormulario->campoMensaje($atributos);
            unset($atributos);
        }

        return true;
    }

}

$miFormulario = new FormularioRegistro($this->lenguaje, $this->miFormulario, $this->sql);

$miFormulario->formulario();
$miFormulario->mensaje();
?>
        