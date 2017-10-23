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
    
    
    private $UNIDADES = array(
    		'',
    		'UN ',
    		'DOS ',
    		'TRES ',
    		'CUATRO ',
    		'CINCO ',
    		'SEIS ',
    		'SIETE ',
    		'OCHO ',
    		'NUEVE ',
    		'DIEZ ',
    		'ONCE ',
    		'DOCE ',
    		'TRECE ',
    		'CATORCE ',
    		'QUINCE ',
    		'DIECISEIS ',
    		'DIECISIETE ',
    		'DIECIOCHO ',
    		'DIECINUEVE ',
    		'VEINTE '
    );
    private $DECENAS = array(
    		'VEINTI',
    		'TREINTA ',
    		'CUARENTA ',
    		'CINCUENTA ',
    		'SESENTA ',
    		'SETENTA ',
    		'OCHENTA ',
    		'NOVENTA ',
    		'CIEN '
    );
    private $CENTENAS = array(
    		'CIENTO ',
    		'DOSCIENTOS ',
    		'TRESCIENTOS ',
    		'CUATROCIENTOS ',
    		'QUINIENTOS ',
    		'SEISCIENTOS ',
    		'SETECIENTOS ',
    		'OCHOCIENTOS ',
    		'NOVECIENTOS '
    );
    private $MONEDAS = array(
    		array('country' => 'Colombia', 'currency' => 'COP', 'singular' => 'PESO COLOMBIANO', 'plural' => 'PESOS COLOMBIANOS', 'symbol', '$'),
    		array('country' => 'Estados Unidos', 'currency' => 'USD', 'singular' => 'DÓLAR', 'plural' => 'DÓLARES', 'symbol', 'US$'),
    		array('country' => 'El Salvador', 'currency' => 'USD', 'singular' => 'DÓLAR', 'plural' => 'DÓLARES', 'symbol', 'US$'),
    		array('country' => 'Europa', 'currency' => 'EUR', 'singular' => 'EURO', 'plural' => 'EUROS', 'symbol', '€'),
    		array('country' => 'México', 'currency' => 'MXN', 'singular' => 'PESO MEXICANO', 'plural' => 'PESOS MEXICANOS', 'symbol', '$'),
    		array('country' => 'Perú', 'currency' => 'PEN', 'singular' => 'NUEVO SOL', 'plural' => 'NUEVOS SOLES', 'symbol', 'S/'),
    		array('country' => 'Reino Unido', 'currency' => 'GBP', 'singular' => 'LIBRA', 'plural' => 'LIBRAS', 'symbol', '£'),
    		array('country' => 'Argentina', 'currency' => 'ARS', 'singular' => 'PESO', 'plural' => 'PESOS', 'symbol', '$')
    );
    private $separator = '.';
    private $decimal_mark = ',';
    private $glue = ' CON ';
    

    function __construct($lenguaje, $formulario, $sql) {

        $this->miConfigurador = \Configurador::singleton();

        $this->miConfigurador->fabricaConexiones->setRecursoDB('principal');
        $this->lenguaje = $lenguaje;

        $this->miFormulario = $formulario;

        $this->miSql = $sql;
    }
    
    public function to_word($number, $miMoneda = null) {
    	if (strpos($number, $this->decimal_mark) === FALSE) {
    		$convertedNumber = array(
    				$this->convertNumber($number, $miMoneda, 'entero')
    		);
    	} else {
    		$number = explode($this->decimal_mark, str_replace($this->separator, '', trim($number)));
    		$convertedNumber = array(
    				$this->convertNumber($number[0], $miMoneda, 'entero'),
    				$this->convertNumber($number[1], $miMoneda, 'decimal'),
    		);
    	}
    	return implode($this->glue, array_filter($convertedNumber));
    }
    /**
     * Convierte número a letras
     * @param $number
     * @param $miMoneda
     * @param $type tipo de dígito (entero/decimal)
     * @return $converted string convertido
     */
    private function convertNumber($number, $miMoneda = null, $type) {
    
    	$converted = '';
    	if ($miMoneda !== null) {
    		try {
    
    			$moneda = array_filter($this->MONEDAS, function($m) use ($miMoneda) {
    				return ($m['currency'] == $miMoneda);
    			});
    				$moneda = array_values($moneda);
    				if (count($moneda) <= 0) {
    					throw new Exception("Tipo de moneda inválido");
    					return;
    				}
    				($number < 2 ? $moneda = $moneda[0]['singular'] : $moneda = $moneda[0]['plural']);
    		} catch (Exception $e) {
    			echo $e->getMessage();
    			return;
    		}
    	}else{
    		$moneda = '';
    	}
    	if (($number < 0) || ($number > 999999999)) {
    		return false;
    	}
    	$numberStr = (string) $number;
    	$numberStrFill = str_pad($numberStr, 9, '0', STR_PAD_LEFT);
    	$millones = substr($numberStrFill, 0, 3);
    	$miles = substr($numberStrFill, 3, 3);
    	$cientos = substr($numberStrFill, 6);
    	if (intval($millones) > 0) {
    		if ($millones == '001') {
    			$converted .= 'UN MILLON ';
    		} else if (intval($millones) > 0) {
    			$converted .= sprintf('%sMILLONES ', $this->convertGroup($millones));
    		}
    	}
    
    	if (intval($miles) > 0) {
    		if ($miles == '001') {
    			$converted .= 'MIL ';
    		} else if (intval($miles) > 0) {
    			$converted .= sprintf('%sMIL ', $this->convertGroup($miles));
    		}
    	}
    	if (intval($cientos) > 0) {
    		if ($cientos == '001') {
    			$converted .= 'UN ';
    		} else if (intval($cientos) > 0) {
    			$converted .= sprintf('%s ', $this->convertGroup($cientos));
    		}
    	}
    	$converted .= $moneda;
    	return $converted;
    }
    /**
     * Define el tipo de representación decimal (centenas/millares/millones)
     * @param $n
     * @return $output
     */
    public function convertGroup($n) {
    	$output = '';
    	if ($n == '100') {
    		$output = "CIEN ";
    	} else if ($n[0] !== '0') {
    		$output = $this->CENTENAS[$n[0] - 1];
    	}
    	$k = intval(substr($n,1));
    	if ($k <= 20) {
    		$output .= $this->UNIDADES[$k];
    	} else {
    		if(($k > 30) && ($n[2] !== '0')) {
    			$output .= sprintf('%sY %s', $this->DECENAS[intval($n[1]) - 2], $this->UNIDADES[intval($n[2])]);
    		} else {
    			$output .= sprintf('%s%s', $this->DECENAS[intval($n[1]) - 2], $this->UNIDADES[intval($n[2])]);
    		}
    	}
    	return $output;
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

        $conexion = 'sicapital';
        $siCapitalRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
        
        //$conexion = 'centralUD';
        //$centralUDRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

        $conexion = 'argo_contratos';
        $argoRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

        $conexion = 'core_central';
        $coreRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

        $conexion = 'framework';
        $frameworkRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
        
        $conexion = "argo_contratos";
        $esteRecursoDBArka = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

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

        
        $variable = "pagina=" . $miPaginaActual;
        $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variable, $directorio );
        
        $atributos["id"]="botones";
        $atributos["estilo"]="marcoBotones widget";
        echo $this->miFormulario->division("inicio",$atributos);
        
        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
        $esteCampo = 'botonRegresar';
        $atributos ['id'] = $esteCampo;
        $atributos ['enlace'] = $variable;
        $atributos ['tabIndex'] = 1;
        $atributos ['estilo'] = '';
        $atributos ['enlaceTexto'] = $this->lenguaje->getCadena ( $esteCampo );
        $atributos ['ancho'] = '10%';
        $atributos ['alto'] = '10%';
        $atributos ['redirLugar'] = true;
        echo $this->miFormulario->enlace ( $atributos );
        
        //------------------Fin Division para los botones-------------------------
        echo $this->miFormulario->division("fin");
        
        unset ( $atributos );
        	
        
		

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
        
        
        
        
        $datosSolicitudNecesidad = array(
        
        		'idObjeto' => $_REQUEST['idSolicitud']
        );
        
        
        //********************************** TOPE DE PRESUPUESTO **************************************
        $cadena_sql = $this->miSql->getCadenaSql("informacionSolicitudAgoraNoCast", $datosSolicitudNecesidad['idObjeto']);
        $resultadoNecesidadRelacionada = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        
        $datos = array (
        		'numero_disponibilidad' => $resultadoNecesidadRelacionada[0]['numero_necesidad'],
        		'vigencia' => $resultadoNecesidadRelacionada[0]['vigencia'],
        		'unidad_ejecutora' => $resultadoNecesidadRelacionada[0]['unidad_ejecutora']
        );
        $cadenaSql = $this->miSql->getCadenaSql ( 'obtenerInfoNec', $datos );
        $resultadoItemsNec = $siCapitalRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
        
        
        $esteCampo = 'tope_contratacion';
        $atributos ['id'] = $esteCampo;
        $atributos ['nombre'] = $esteCampo;
        $atributos ['tipo'] = 'hidden';
        $atributos ['estilo'] = 'jqueryui';
        $atributos ['dobleLinea'] = false;
        $atributos ['tabIndex'] = $tab;
        
        if($resultadoItemsNec){
        	$atributos ['valor'] = $resultadoItemsNec[0]['VALOR_CONTRATACION'];
        }else{
        	$atributos ['valor'] = 0;
        }
        
        
        $atributos ['deshabilitado'] = false;
        $atributos ['tamanno'] = 30;
        $atributos ['maximoTamanno'] = '';
        $tab ++;
        // Aplica atributos globales al control
        $atributos = array_merge($atributos, $atributosGlobales);
        echo $this->miFormulario->campoCuadroTexto($atributos);
        unset($atributos);
        
        
        //**********************************************************************************************
        
        
        
		
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

        
        $tipo = 'warning';
        $mensaje = "<b>ATENCIÓN</b><br>
							<br>
							Señor proveedor, el solicitante de la cotización ha realizado, una modificación (adenda) a las condiciones iniciales
        					de la misma, se han realizado cambios en los productos/servicios relacionados, por tanto, se le ha permitido a usted
        					realizar modificación de su respuesta, para que ajuste su respuesta como lo considere necesario, por favor tenga en
        					cuenta está información, a continuación, se muestran puntualmente los cambios aprobados por el responsable de la cotización,
        					también pueden ser consultados en el detalle de la cotización en el panel de la anterior pagina.
							";
        // ---------------- SECCION: Controles del Formulario -----------------------------------------------
        $esteCampo = 'mensaje';
        $atributos["id"] = $esteCampo; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
        $atributos["etiqueta"] = "";
        $atributos["estilo"] = "centrar";
        $atributos["tipo"] = $tipo;
        $atributos["mensaje"] = $mensaje;
        echo $this->miFormulario->cuadroMensaje($atributos);
        unset($atributos);
        
        
        
        
        $tipo = 'information';
        $mensaje = "<b>IMPORTANTE</b><br>
							<br>
							Recuerde que la reglamentación a tener en cuenta para los procesos derivados de las cotizaciones, son el Estatuto de Contratación y sus Resoluciones Reglamentarias y el manual de supervisión e interventoría estipulados por
				<b>ACUERDO No. 03 (11 de Marzo de 2015)</b> <i>'Por el cual se expide el Estatuto de Contratación de la Universidad Distrital Francisco José de Caldas'</i>, la
				<b>RESOLUCIÓN  No. 629 (17 de Noviembre de 2016)</b> <i>'Por medio de la cual se adopta el Manual de Supervisión e Interventoría de la Universidad Distrital Francisco José de Caldas'</i>,
        		la <b>RESOLUCIÓN  No. 262 (2 de Junio de 2015)</b> <i>'Por medio de la cual se reglamenta el Acuerdo 03 de 2015, Estatuto de Contratación de la Universidad Distrital Francisco José de Caldas y se dictan otras disposiciones'</i> y
        		la <b>RESOLUCIÓN  No. 683 (9 de Diciembre de 2016)</b> <i>'Por la cual se crea y se reglamenta el banco de proveedores en la Universidad Distrital Francisco José de Caldas'</i>.
							";
        // ---------------- SECCION: Controles del Formulario -----------------------------------------------
        $esteCampo = 'mensaje';
        $atributos["id"] = $esteCampo; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
        $atributos["etiqueta"] = "";
        $atributos["estilo"] = "centrar";
        $atributos["tipo"] = $tipo;
        $atributos["mensaje"] = $mensaje;
        echo $this->miFormulario->cuadroMensaje($atributos);
        unset($atributos);
        

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
            //echo $this->miFormulario->campoTextArea($atributos);
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
            //echo $this->miFormulario->campoTextArea($atributos);
            unset($atributos);
            
            $cadena_sql = $this->miSql->getCadenaSql ( "buscarDetalleItemsProducto", $resultadoRespuesta[0]['id']);
            $resultadoItems = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
			$esteCampo = "marcoDescripcionProducto";
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
			$atributos ['tipoEtiqueta'] = 'inicio';
			$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
			echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
			{
				
				$esteCampo = "marcoDetallePro";
				$atributos ['id'] = $esteCampo;
				$atributos ["estilo"] = "jqueryui";
				$atributos ['tipoEtiqueta'] = 'inicio';
				$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
				echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
				
				unset ( $atributos );
				
				?>


				<table id="tablaFP2" class="table1" width="100%" >
                        <!-- Cabecera de la tabla -->
                        <thead>
                            <tr>
                                <th width="0%" style="display:none;">id</th>
                                <th width="3%" >#</th>
                                <th width="8%" >Nombre</th>
                                <th width="14%" >Descripción</th>
                                <th width="6%" >Tipo</th>
                                <th width="10%" >Unidad</th>
                                <th width="0%" style="display:none;">Tiempo de Ejecución</th>
                                <th width="4%" >Cantidad</th>
                                <th width="6%" >Valor Unitario <br> Del Bien ó Servicio <br> Ofrecido</th>
                                <th width="10%" >Tarifa Iva <br> que  Aplica <br> Sobre el<br> Bien ó Servicio <br> Ofrecido</th>
                                <th width="29%" >Descripción <br>Del Bien ó <br>Servicio Ofrecido</th>
                            </tr>
                        </thead>

                        <!-- Cuerpo de la tabla con los campos -->
                        <tbody>



                            <?php
                            $cadenaSql = $this->miSql->getCadenaSql('consultar_tipo_iva');
                            $resultadoIva = $esteRecursoDBArka->ejecutarAcceso($cadenaSql, "busqueda");
                            
                            $valorPrecioTotal = 0;
                            $valorPrecioTotalIva=0;

                            if (isset($resultadoItems) && $resultadoItems) {
                                $count = count($resultadoItems);
                                $i = 0;

                                $numerosItem = $count;
                                
                                $valoresItem = '';

                                while ($i < $count) {
                                	
                                	$atributos ['cadena_sql'] = $this->miSql->getCadenaSql("consultar_tipo_iva_Item", $resultadoItems[$i]['iva']);
                                	$IvaItem = $esteRecursoDBArka->ejecutarAcceso($atributos ['cadena_sql'], "busqueda");

                                    if (intval($resultadoItems[$i][5]) === 0) {
                                        $ejecucion = '0 - NO APLICA';
                                    } else {
                                        $nyears = intval(intval($resultadoItems[$i][5]) / 360);
                                        $nmonths = intval((intval($resultadoItems[$i][5]) - intval(intval($resultadoItems[$i][5]) / 360) * 360) / 30);
                                        $ndays = intval(intval($resultadoItems[$i][5]) - (intval(intval($resultadoItems[$i][5]) / 360) * 360 + intval((intval($resultadoItems[$i][5]) - intval(intval($resultadoItems[$i][5]) / 360) * 360) / 30) * 30));
                                        $ejecucion = $nyears . " AÑO(S) - " . $nmonths . " MES(ES) - " . $ndays . " DÍA(S)";
                                    }

                                    $atributos ['cadena_sql'] = $cadenaSql = $this->miSql->getCadenaSql('tipoNecesidadAdministrativa3', $resultadoItems[$i][3]);
                                    $matrizItemsTipoItem = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");

                                    $atributos ['cadena_sql'] = $cadenaSql = $this->miSql->getCadenaSql('unidadUdistrital2', $resultadoItems[$i][4]);
                                    $matrizItemsUnidad = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");

                                    if ($resultadoItems[$i][4] === '0') {
                                        $matrizItemsUnidad[0][0] = 0;
                                        $matrizItemsUnidad[0][1] = 'NO APLICA';
                                    }


                                    if ($resultadoItems[$i][6] - intval($resultadoItems[$i][6])) { // Sobra 0.24 por lo que devuelve verdadero
                                        $numero_cantidad = number_format($resultadoItems[$i][6], 2, ',', '.');
                                    } else {
                                        $numero_cantidad = intval($resultadoItems[$i][6]);
                                    }
                                    
                                    
                                    $valorPrecioTotal += ($resultadoItems [$i] ['cantidad'] * $resultadoItems [$i] ['valor_unitario']);
                                    if ($IvaItem [0] ['descripcion'] == 'Tarifa de Cero' || $IvaItem [0] ['descripcion'] == 'Exento') {
                                    	$valorPrecioTotalIva += ($resultadoItems [$i] ['cantidad'] * $resultadoItems [$i] ['valor_unitario']);
                                    } else {
                                    	$valorPrecioTotalIva += (($resultadoItems [$i] ['cantidad'] * $resultadoItems [$i] ['valor_unitario'])) * (1 + ($IvaItem [0] ['iva']));
                                    }
                                    ?>


                                    <tr id="nFilas" >
                                        <td style="display:none;"><?php echo $resultadoItems[$i][0]?></td>
                                        <td><?php echo $i + 1 ?></td>
                                        <td><?php echo $resultadoItems[$i][1] ?></td>
                                        <td><?php echo $resultadoItems[$i][2] ?></td>
                                        <td><?php echo $matrizItemsTipoItem[0][0] . " - " . $matrizItemsTipoItem[0][1] ?></td>
                                        <td><?php echo $matrizItemsUnidad[0][0] . " - " . $matrizItemsUnidad[0][1] ?></td>
                                        <td style="display:none;"><?php echo $ejecucion ?></td>
                                        <td><?php echo $numero_cantidad ?></td>
                                        <td><?php echo '<input type="text" id="valorUnitario" value="'.round($resultadoItems[$i]['valor_unitario'],0).'" name="valorUnitario" placeholder="Valor 123456.24" maxlength="22" class="form-control  custom[number]"/>'?></td>
										<td>
														                                                        <?php
													echo '<select  id="iva" name="iva"  class="selectpicker  ">                                                                   
														                                                                                                     <option value="">Seleccione  ....</option>';
													for($j = 0; $j < count ( $resultadoIva ); $j ++) {
														
														if ($IvaItem [0] ['descripcion'] == $resultadoIva [$j] ['descripcion']) {
															echo '<option value="' . $resultadoIva [$j] ['descripcion'] . '" selected>' . $resultadoIva [$j] ['descripcion'] . '</option>';
														} else {
															echo '<option value="' . $resultadoIva [$j] ['descripcion'] . '">' . $resultadoIva [$j] ['descripcion'] . '</option>';
														}
													}
													echo '</select> '?></td>
										<td><?php echo '<textarea name="explanation" class="NOTanEditor" rows="4" cols="50"  maxlength="2000">'.$resultadoItems[$i]['ficha_tecnica'].'</textarea>' ?></td>
							
									</tr>




                                    <?php
                                    $valoresItem .= ($i + 1) . "&";
                                    $valoresItem .= ( $resultadoItems[$i][1]) . "&";
                                    $valoresItem .= ($resultadoItems[$i][2]) . "&";
                                    $valoresItem .= ($matrizItemsTipoItem[0][0] . " - " . $matrizItemsTipoItem[0][1]) . "&";
                                    $valoresItem .= ($matrizItemsUnidad[0][0] . " - " . $matrizItemsUnidad[0][1]) . "&";
                                    $valoresItem .= ($ejecucion) . "&";
                                    $valoresItem .= ($numero_cantidad) . "&";

                                    $i++;
                                }
                            }
                            ?>




                        </tbody>
                    </table>






<?php




			echo "<br><br>";
			//------------------Division para los botones-------------------------
			$atributos["id"] = "botones";
			$atributos["estilo"] = "marcoBotones widget";
			echo $this->miFormulario->division("inicio", $atributos);
			//
			//******************************************************************************************************************************
			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
			$esteCampo = 'botonAgregarInfo';
			$atributos ['id'] = $esteCampo;
			$atributos ['enlace'] = '';
			$atributos ['tabIndex'] = 1;
			$atributos ['estilo'] = 'textoSubtitulo';
			$atributos ['enlaceTexto'] = $this->lenguaje->getCadena($esteCampo);
			$atributos ['ancho'] = '10%';
			$atributos ['alto'] = '10%';
			$atributos ['redirLugar'] = false;
			echo $this->miFormulario->enlace($atributos);
			
			unset($atributos);
			//********************************************************************************************************************************
			//------------------Fin Division para los botones-------------------------
			echo $this->miFormulario->division("fin");
			
			
			unset($atributos);
			
			
			
			$esteCampo = 'idsItemsProv';
			$atributos ["id"] = $esteCampo; // No cambiar este nombre
			$atributos ["tipo"] = "hidden";
			$atributos ['estilo'] = '';
			$atributos ["obligatorio"] = false;
			$atributos ['marco'] = false;
			$atributos ["etiqueta"] = "";
			if (isset($_REQUEST [$esteCampo])) {
				$atributos ['valor'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['valor'] = '';
			}
			$atributos ['validar'] = '';
			$atributos = array_merge($atributos, $atributosGlobales);
			echo $this->miFormulario->campoCuadroTexto($atributos);
			unset($atributos);




				
				echo $this->miFormulario->marcoAgrupacion ( 'fin' );
				
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
                            
                            
                            
                            			
                            			
                            			
                            
                            $promedio = $valorPrecioTotal;
                            if($promedio > 999999999 && $promedio <= 999999999999){
                            
                            	$restCast = substr((int)$promedio, -9);
                            	$rest = str_replace ( $restCast , "" , (int)$promedio );
                            	$rest = str_pad($rest, 3, '0', STR_PAD_LEFT);
                            
                            	if ($rest == '001') {
                            		$converted = 'MIL ';
                            	} else if (intval($rest) > 0) {
                            		$converted = sprintf('%sMIL ', $this->convertGroup($rest));
                            	}
                            
                            	$converted .= $this->to_word($restCast, 'COP');
                            }else{
                            	$converted = $this->to_word($promedio, 'COP');
                            }
                            
                            $dineroCast = $converted;
                            
                            $promedioIva = $valorPrecioTotalIva;
                            if($promedioIva > 999999999 && $promedioIva <= 999999999999){
                            
                            	$restCast = substr((int)$promedioIva, -9);
                            	$rest = str_replace ( $restCast , "" , (int)$promedioIva );
                            	$rest = str_pad($rest, 3, '0', STR_PAD_LEFT);
                            
                            	if ($rest == '001') {
                            		$convertedIva = 'MIL ';
                            	} else if (intval($rest) > 0) {
                            		$convertedIva = sprintf('%sMIL ', $this->convertGroup($rest));
                            	}
                            
                            	$convertedIva .= $this->to_word($restCast, 'COP');
                            }else{
                            	$convertedIva = $this->to_word($promedioIva, 'COP');
                            }
                            
                            $dineroCastIva = $convertedIva;
                            
                            
                            $promedioSoloIva = $valorPrecioTotalIva-$valorPrecioTotal;
                            if($promedioSoloIva > 999999999 && $promedioSoloIva <= 999999999999){
                            
                            	$restCast = substr((int)$promedioSoloIva, -9);
                            	$rest = str_replace ( $restCast , "" , (int)$promedioSoloIva );
                            	$rest = str_pad($rest, 3, '0', STR_PAD_LEFT);
                            
                            	if ($rest == '001') {
                            		$convertedSoloIva = 'MIL ';
                            	} else if (intval($rest) > 0) {
                            		$convertedSoloIva = sprintf('%sMIL ', $this->convertGroup($rest));
                            	}
                            
                            	$convertedSoloIva .= $this->to_word($restCast, 'COP');
                            }else{
                            	$convertedSoloIva = $this->to_word($promedioSoloIva, 'COP');
                            }
                            
                            $dineroCastSoloIva = $convertedSoloIva;
                            
                            
                            
                            
                            	
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
                            	$tab++;
                            	
                            	// Aplica atributos globales al control
                            	$atributos = array_merge($atributos, $atributosGlobales);
                            	echo $this->miFormulario->campoCuadroTexto($atributos);
                            	unset($atributos);
                            	// ----------------FIN CONTROL: Campo de Texto FECHA SOLICITUD--------------------------------------------------------
                            	//   // ----------------INICIO CONTROL: Campo de Texto FECHA SOLICITUD--------------------------------------------------------
                            	$esteCampo = 'precioIva';
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
                            	$tab++;
                            	
                            	// Aplica atributos globales al control
                            	$atributos = array_merge($atributos, $atributosGlobales);
                            	echo $this->miFormulario->campoCuadroTexto($atributos);
                            	unset($atributos);
                            	
                            	//   // ----------------INICIO CONTROL: Campo de Texto FECHA SOLICITUD--------------------------------------------------------
                            	$esteCampo = 'precioCotIva';
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
                            	$tab++;
                            	
                            	// Aplica atributos globales al control
                            	$atributos = array_merge($atributos, $atributosGlobales);
                            	echo $this->miFormulario->campoCuadroTexto($atributos);
                            	unset($atributos);
                            	// ----------------FIN CONTROL: Campo de Texto FECHA SOLICITUD--------------------------------------------------------
                            	
                            
                            
                            
                            
                            // ----------------INICIO CONTROL: Campo de Texto FECHA SOLICITUD--------------------------------------------------------
                            $esteCampo = 'fechaVencimientoCot';
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
                            $atributos ['textoEnriquecido'] = true;
            
            
                            $atributos ['valor'] = $resultadoRespuesta[0]['descuentos'];
            
                            $tab ++;
            
                            // Aplica atributos globales al control
                            $atributos = array_merge($atributos, $atributosGlobales);
                            echo $this->miFormulario->campoTextArea($atributos);
                            unset($atributos);
            
                            
                            
                            
                            
                            
                            
            }
            echo $this->miFormulario->marcoAgrupacion('fin');

            
            
            
            
            
            
            
            
            
            
            
            
            /*
            
            $cadena_sql = $this->miSql->getCadenaSql ( "buscarDetalleItemsProducto", $resultadoRespuesta[0]['id']);
            $resultadoItems = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
            
            $esteCampo = "marcoDescripcionProducto2";
            $atributos ['id'] = $esteCampo;
            $atributos ["estilo"] = "jqueryui";
            $atributos ['tipoEtiqueta'] = 'inicio';
            $atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo);
            echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);
            {
            
            	$esteCampo = 'tipo_registro';
            	$atributos ['columnas'] = 1;
            	$atributos ['nombre'] = $esteCampo;
            	$atributos ['id'] = $esteCampo;
            	$atributos ['seleccion'] = 1;
            	$atributos ['evento'] = '';
            	$atributos ['deshabilitado'] = false;
            	$atributos ['tab'] = $tab;
            	$atributos ['tamanno'] = 1;
            	$atributos ['estilo'] = 'jqueryui';
            	$atributos ['validar'] = '';
            	$atributos ['limitar'] = false;
            	$atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
            	$atributos ['anchoEtiqueta'] = 213;
            	// Valores a mostrar en el control
            	$matrizItems = array(
            			array(
            					1,
            					'No'
            			),
            			array(
            					2,
            					'Si'
            			)
            	);
            
            	$atributos ['matrizItems'] = $matrizItems;
            
            	// Utilizar lo siguiente cuando no se pase un arreglo:
            	// $atributos['baseDatos']='ponerAquiElNombreDeLaConexión';
            	// $atributos ['cadena_sql']='ponerLaCadenaSqlAEjecutar';
            	$tab++;
            	$atributos = array_merge($atributos, $atributosGlobales);
            	echo $this->miFormulario->campoCuadroLista($atributos);
            	unset($atributos);
            	echo '<br><br><br>';
            
            	$atributos ["id"] = "cargue_elementos";
            	$atributos ["estiloEnLinea"] = "display:none";
            	$atributos = array_merge($atributos, $atributosGlobales);
            	echo $this->miFormulario->division("inicio", $atributos);
            	unset($atributos);
            	{
            
            		$valoresItem = '';
            		$numerosItem = 0;
            		?>
            
            
                                <table id="tablaFP2" class="table1" width="100%" >
                                    <!-- Cabecera de la tabla -->
                                    <thead>
                                        <tr>
                                            <th width="0%" style="display:none;">id</th>
                                            <th width="3%" >#</th>
                                            <th width="8%" >Nombre</th>
                                            <th width="14%" >Descripción</th>
                                            <th width="6%" >Tipo</th>
                                            <th width="10%" >Unidad</th>
                                            <th width="8%" >Tiempo de Ejecución</th>
                                            <th width="4%" >Cantidad</th>
                                            <th width="6%" >Valor Unitario <br> Del Bien ó Servicio <br> Ofrecido</th>
                                            <th width="10%" >Tarifa Iva <br> que  Aplica <br> Sobre el<br> Bien ó Servicio <br> Ofrecido</th>
                                            <th width="29%" >Descripción <br>Del Bien ó <br>Servicio Ofrecido</th>
                                        </tr>
                                    </thead>
            
                                    <!-- Cuerpo de la tabla con los campos -->
                                    <tbody>
            
            
            
                                        <?php
                                        $cadenaSql = $this->miSql->getCadenaSql('consultar_tipo_iva');
                                        $resultadoIva = $esteRecursoDBArka->ejecutarAcceso($cadenaSql, "busqueda");
            
                                        if (isset($resultadoItems) && $resultadoItems) {
                                            $count = count($resultadoItems);
                                            $i = 0;
            
                                            $numerosItem = $count;
            
                                            while ($i < $count) {
            
                                                if (intval($resultadoItems[$i][5]) === 0) {
                                                    $ejecucion = '0 - NO APLICA';
                                                } else {
                                                    $nyears = intval(intval($resultadoItems[$i][5]) / 360);
                                                    $nmonths = intval((intval($resultadoItems[$i][5]) - intval(intval($resultadoItems[$i][5]) / 360) * 360) / 30);
                                                    $ndays = intval(intval($resultadoItems[$i][5]) - (intval(intval($resultadoItems[$i][5]) / 360) * 360 + intval((intval($resultadoItems[$i][5]) - intval(intval($resultadoItems[$i][5]) / 360) * 360) / 30) * 30));
                                                    $ejecucion = $nyears . " AÑO(S) - " . $nmonths . " MES(ES) - " . $ndays . " DÍA(S)";
                                                }
            
                                                $atributos ['cadena_sql'] = $cadenaSql = $this->miSql->getCadenaSql('tipoNecesidadAdministrativa3', $resultadoItems[$i][3]);
                                                $matrizItemsTipoItem = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
            
                                                $atributos ['cadena_sql'] = $cadenaSql = $this->miSql->getCadenaSql('unidadUdistrital2', $resultadoItems[$i][4]);
                                                $matrizItemsUnidad = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
            
                                                if ($resultadoItems[$i][4] === '0') {
                                                    $matrizItemsUnidad[0][0] = 0;
                                                    $matrizItemsUnidad[0][1] = 'NO APLICA';
                                                }
            
            
                                                if ($resultadoItems[$i][6] - intval($resultadoItems[$i][6])) { // Sobra 0.24 por lo que devuelve verdadero
                                                    $numero_cantidad = number_format($resultadoItems[$i][6], 2, ',', '.');
                                                } else {
                                                    $numero_cantidad = intval($resultadoItems[$i][6]);
                                                }
                                                ?>
            
            
                                                <tr id="nFilas" >
                                                    <td style="display:none;"><?php echo $resultadoItems[$i][0]?></td>
                                                    <td><?php echo $i + 1 ?></td>
                                                    <td><?php echo $resultadoItems[$i][1] ?></td>
                                                    <td><?php echo $resultadoItems[$i][2] ?></td>
                                                    <td><?php echo $matrizItemsTipoItem[0][0] . " - " . $matrizItemsTipoItem[0][1] ?></td>
                                                    <td><?php echo $matrizItemsUnidad[0][0] . " - " . $matrizItemsUnidad[0][1] ?></td>
                                                    <td><?php echo $ejecucion ?></td>
                                                    <td><?php echo $numero_cantidad ?></td>
                                                    <td><?php echo '<input type="text" id="valorUnitario" name="valorUnitario" placeholder="Valor 123456.24" maxlength="22" class="form-control  custom[number]"/>'
                                                ?></td>
                                                    <td>
                                                        <?php
                                                        echo '<select  id="iva" name="iva"  class="selectpicker  ">                                                                   
                                                                                                     <option value="">Seleccione  ....</option>';
                                                        for ($j = 0; $j < count($resultadoIva); $j++) {
                                                            echo '<option value="' . $resultadoIva[$j]['descripcion'] . '">' . $resultadoIva[$j]['descripcion'] . '</option>';
                                                        }
                                                        echo '</select> '
                                                        ?></td>
                                                    <td><?php echo '<textarea name="explanation" class="NOTanEditor" rows="4" cols="50"  maxlength="2000"></textarea>' ?></td>
                                                </tr>
            
            
            
            
                                                <?php
                                                $valoresItem .= ($i + 1) . "&";
                                                $valoresItem .= ( $resultadoItems[$i][1]) . "&";
                                                $valoresItem .= ($resultadoItems[$i][2]) . "&";
                                                $valoresItem .= ($matrizItemsTipoItem[0][0] . " - " . $matrizItemsTipoItem[0][1]) . "&";
                                                $valoresItem .= ($matrizItemsUnidad[0][0] . " - " . $matrizItemsUnidad[0][1]) . "&";
                                                $valoresItem .= ($ejecucion) . "&";
                                                $valoresItem .= ($numero_cantidad) . "&";
            
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
                            }
                            echo "<br><br>";
                               //------------------Division para los botones-------------------------
                            $atributos["id"] = "botones";
                            $atributos["estilo"] = "marcoBotones widget";
                            echo $this->miFormulario->division("inicio", $atributos);
            //
                            //******************************************************************************************************************************
                            // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                            $esteCampo = 'botonAgregarInfo';
                            $atributos ['id'] = $esteCampo;
                            $atributos ['enlace'] = '';
                            $atributos ['tabIndex'] = 1;
                            $atributos ['estilo'] = 'textoSubtitulo';
                            $atributos ['enlaceTexto'] = $this->lenguaje->getCadena($esteCampo);
                            $atributos ['ancho'] = '10%';
                            $atributos ['alto'] = '10%';
                            $atributos ['redirLugar'] = false;
                            echo $this->miFormulario->enlace($atributos);
            
                            unset($atributos);
                            //********************************************************************************************************************************
                            //------------------Fin Division para los botones-------------------------
                            echo $this->miFormulario->division("fin");
                            
                            
                            echo $this->miFormulario->division("fin");
                            unset($atributos);
            
                            $esteCampo = 'idsItems';
                            $atributos ["id"] = $esteCampo; // No cambiar este nombre
                            $atributos ["tipo"] = "hidden";
                            $atributos ['estilo'] = '';
                            $atributos ["obligatorio"] = false;
                            $atributos ['marco'] = false;
                            $atributos ["etiqueta"] = "";
                            if (isset($_REQUEST [$esteCampo])) {
                                $atributos ['valor'] = $_REQUEST [$esteCampo];
                            } else {
                                $atributos ['valor'] = '';
                            }
                            $atributos ['validar'] = '';
                            $atributos = array_merge($atributos, $atributosGlobales);
                            echo $this->miFormulario->campoCuadroTexto($atributos);
                            unset($atributos);
            
                            $esteCampo = 'countItems';
                            $atributos ["id"] = $esteCampo; // No cambiar este nombre
                            $atributos ["tipo"] = "hidden";
                            $atributos ['estilo'] = '';
                            $atributos ["obligatorio"] = false;
                            $atributos ['marco'] = false;
                            $atributos ["etiqueta"] = "";
                            if (isset($_REQUEST [$esteCampo])) {
                                $atributos ['valor'] = $_REQUEST [$esteCampo];
                            } else {
                                $atributos ['valor'] = '';
                            }
                            $atributos ['validar'] = '';
                            $atributos = array_merge($atributos, $atributosGlobales);
                            echo $this->miFormulario->campoCuadroTexto($atributos);
                            unset($atributos);
                            
                            $esteCampo = 'idsItemsProv';
                            $atributos ["id"] = $esteCampo; // No cambiar este nombre
                            $atributos ["tipo"] = "hidden";
                            $atributos ['estilo'] = '';
                            $atributos ["obligatorio"] = false;
                            $atributos ['marco'] = false;
                            $atributos ["etiqueta"] = "";
                            if (isset($_REQUEST [$esteCampo])) {
                                $atributos ['valor'] = $_REQUEST [$esteCampo];
                            } else {
                                $atributos ['valor'] = '';
                            }
                            $atributos ['validar'] = '';
                            $atributos = array_merge($atributos, $atributosGlobales);
                            echo $this->miFormulario->campoCuadroTexto($atributos);
                            unset($atributos);
                            
            
            echo $this->miFormulario->marcoAgrupacion('fin');
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
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
                            $tab++;
            
                            // Aplica atributos globales al control
                            $atributos = array_merge($atributos, $atributosGlobales);
                            echo $this->miFormulario->campoCuadroTexto($atributos);
                            unset($atributos);
                            // ----------------FIN CONTROL: Campo de Texto FECHA SOLICITUD--------------------------------------------------------
                            //   // ----------------INICIO CONTROL: Campo de Texto FECHA SOLICITUD--------------------------------------------------------
                            $esteCampo = 'precioIva';
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
                            $tab++;
            
                            // Aplica atributos globales al control
                            $atributos = array_merge($atributos, $atributosGlobales);
                            echo $this->miFormulario->campoCuadroTexto($atributos);
                            unset($atributos);
            
                            //   // ----------------INICIO CONTROL: Campo de Texto FECHA SOLICITUD--------------------------------------------------------
                            $esteCampo = 'precioCotIva';
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
                            $tab++;
            
                            // Aplica atributos globales al control
                            $atributos = array_merge($atributos, $atributosGlobales);
                            echo $this->miFormulario->campoCuadroTexto($atributos);
                            unset($atributos);
                            // ----------------FIN CONTROL: Campo de Texto FECHA SOLICITUD--------------------------------------------------------
                            // ----------------INICIO CONTROL: Campo de Texto FECHA SOLICITUD--------------------------------------------------------
                            $esteCampo = 'fechaVencimientoCot';
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
            
                            $atributos ['valor'] = '';
            
                            $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
                            $atributos ['deshabilitado'] = true;
                            $atributos ['tamanno'] = 15;
                            $atributos ['maximoTamanno'] = '30';
                            $atributos ['anchoEtiqueta'] = 400;
                            $tab++;
            
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
                            $atributos ['textoEnriquecido'] = true;
            
            
                            $atributos ['valor'] = '';
            
                            $tab++;
            
                            // Aplica atributos globales al control
                            $atributos = array_merge($atributos, $atributosGlobales);
                            echo $this->miFormulario->campoTextArea($atributos);
                            unset($atributos);
            }
            
            
            */
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            

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
            $atributos ["etiquetaObligatorio"] = false;
            $atributos ["tabIndex"] = $tab ++;
            $atributos ["columnas"] = 1;
            $atributos ["estilo"] = "textoIzquierda";
            $atributos ["anchoEtiqueta"] = 400;
            $atributos ["tamanno"] = 500000;
            $atributos ["validar"] = "";
            $atributos ["etiqueta"] = $this->lenguaje->getCadena($esteCampo);
            // $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
            // $atributos ["valor"] = $valorCodificado;
            $atributos = array_merge($atributos, $atributosGlobales);
            echo $this->miFormulario->campoCuadroTexto ( $atributos );
          

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



            
            $tipo = 'warning';
            $mensaje = "<b>IMPORTANTE</b><br>
							<br>
							Solo se permite modificar la cotización <b>(1) una</b> sola vez, por favor verfique la información relacionada
							y guarde posteriormente, recuerde que no podra hacer cambios de la información diligenciada.
							";
            // ---------------- SECCION: Controles del Formulario -----------------------------------------------
            $esteCampo = 'mensaje';
            $atributos["id"] = $esteCampo; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
            $atributos["etiqueta"] = "";
            $atributos["estilo"] = "centrar";
            $atributos["tipo"] = $tipo;
            $atributos["mensaje"] = $mensaje;
            echo $this->miFormulario->cuadroMensaje($atributos);
            unset($atributos);
            
            
            
            // ------------------Division para los botones-------------------------
            $atributos ["id"] = "botonesRegCot";
            $atributos ["estilo"] = "marcoBotones";
            echo $this->miFormulario->division("inicio", $atributos);
            {
            	// -----------------CONTROL: Botón ----------------------------------------------------------------
            
            
            	$esteCampo = 'botonModificar';
            
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
            	$tab++;
            
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
 		$valorCodificado  = "action=" . $esteBloque ["nombre"];
        $valorCodificado .= "&pagina=" . $this->miConfigurador->getVariableConfiguracion('pagina');
        $valorCodificado .= "&bloque=" . $esteBloque ['nombre'];
        $valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
        $valorCodificado .= "&opcion=modificarCot";
        $valorCodificado .= "&usuario=" . $_REQUEST['usuario'];
        $valorCodificado .= "&solicitud=" . $_REQUEST['idSolicitud'];
        $valorCodificado .= "&tipoCotizacion=" . $_REQUEST['tipoCotizacion'];
        $valorCodificado .= "&id_proveedor=" . $idProveedor;
        $valorCodificado .= "&numero_solicitud=" . $_REQUEST['numero_solicitud'];
        $valorCodificado .= "&vigencia=" . $_REQUEST['vigencia'];
        $valorCodificado .= "&titulo_cotizacion=" . $_REQUEST['titulo_cotizacion'];
        $valorCodificado .= "&fecha_cierre=" . $_REQUEST['fecha_cierre'];

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
        