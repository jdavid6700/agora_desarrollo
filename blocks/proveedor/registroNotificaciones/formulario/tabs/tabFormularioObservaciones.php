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

        $cadenaSql = $this->miSql->getCadenaSql('listarObjetosParaMensajeJoin', $_REQUEST['idSolicitud']);
        $datosSolicitud= $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda", $_REQUEST['idSolicitud'], 'listarObjetosParaMensajeJoin');
		
        
        $cadenaSql = $this->miSql->getCadenaSql ( 'dependenciaUdistritalById', $datosSolicitud[0]['jefe_dependencia'] );
        $resultadoDependencia = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
         
        $cadenaSql = $this->miSql->getCadenaSql ( 'dependenciaUdistritalById', $datosSolicitud[0]['jefe_dependencia_destino'] );
        $resultadoDependenciaDes = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
         
        $cadenaSql = $this->miSql->getCadenaSql ( 'ordenadorUdistritalById', $datosSolicitud[0]['ordenador_gasto'] );
        $resultadoOrdenador = $argoRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
        
        
        $cadenaSql = $this->miSql->getCadenaSql('consultarObservacionesReg', $id_solicitud[0][0]);
        $resultadoObservacionesReg = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
        

        $numeroDocumento = $resultadoDoc[0]['identificacion'];

        $cadenaSql = $this->miSql->getCadenaSql('consultar_DatosProveedor', $numeroDocumento);
        $resultadoDats = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");

        $idProveedor = $resultadoDats[0]['id_proveedor'];
        $tipoPersona = $resultadoDats[0]['tipopersona'];
        $nombrePersona = $resultadoDats[0]['nom_proveedor'];
        $correo = $resultadoDats[0]['correo'];
        $direccion = $resultadoDats[0]['direccion'];
        
        
        
        //------------------Division para los botones-------------------------
        $atributos["id"] = "botones";
        $atributos["estilo"] = "marcoBotones widget";
        echo $this->miFormulario->division("inicio", $atributos);
        
        //******************************************************************************************************************************
        $variable = "pagina=" . $miPaginaActual;
        $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
        
        if (isset($_REQUEST['paginaOrigen'])) {
        	$variable = "pagina=" . $_REQUEST['paginaOrigen'];
        	$variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
        } else {
        	$variable = "pagina=" . $miPaginaActual;
        	$variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
        }
        
        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
        $esteCampo = 'botonRegresar';
        $atributos ['id'] = $esteCampo;
        $atributos ['enlace'] = $variable;
        $atributos ['tabIndex'] = 1;
        $atributos ['estilo'] = 'textoSubtitulo';
        $atributos ['enlaceTexto'] = $this->lenguaje->getCadena($esteCampo);
        $atributos ['ancho'] = '10%';
        $atributos ['alto'] = '10%';
        $atributos ['redirLugar'] = true;
        echo $this->miFormulario->enlace($atributos);
        
        unset($atributos);
        //********************************************************************************************************************************
        //------------------Fin Division para los botones-------------------------
        echo $this->miFormulario->division("fin");
        
        

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

        
        $tipo = 'success';
        $mensaje = "<b>ATENCIÓN</b><br>
							<br>
							A continuación, usted puede registrar las distintas observaciones que considere pertinentes para que estás sean tenidas en 
        					cuenta por el Solicitante de la Cotización.
        					<br><br><br>
        					<b>Número:</b><br>
							Solicitud de Cotización (" . $datosSolicitud[0]["numero_solicitud"] . " - " . $_REQUEST['vigencia'] . ") <br><br>" . 


							"<b>Titulo:</b><br>". $datosSolicitud[0]["titulo_cotizacion"] . "<br><br>
							 </br><b>Ordenador del Gasto Relacionado: </b> ".$resultadoOrdenador[0][1]."
							 </br><b>Dependencia Solicitante: </b> ".$resultadoDependencia[0][1]."
				             </br><b>Dependencia Destino: </b> ".$resultadoDependenciaDes[0][1]."	
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
        

        if($resultadoObservacionesReg){
        	

        	// ---------------- CONTROL: Cuadro de Texto--------------------------------------------------------
        	$esteCampo = 'countObservaciones';
        	$atributos ['id'] = $esteCampo;
        	$atributos ['nombre'] = $esteCampo;
        	$atributos ['tipo'] = 'hidden';
        	$atributos ['tabIndex'] = $tab;
        		
        	$atributos ['valor'] = count($resultadoObservacionesReg);
        	$tab ++;
        		
        	// Aplica atributos globales al control
        	$atributos = array_merge ( $atributos, $atributosGlobales );
        	echo $this->miFormulario->campoCuadroTexto ( $atributos );
        	unset ( $atributos );
        	
        	?>
        	<div align="center">
        	<fieldset id="marcoObservacionView">
			        <legend id="leyenda2" class="ui-state-default ui-corner-all" >Observaciones Registradas</legend>
        	
        	<?php

        		    		
        		?>
				

				<?php
				echo "<center>";
				$i = 0;
				while ( $i < count ( $resultadoObservacionesReg ) ) {
        			
        			
        			?>
        			
      
        				<div>
		        			<textarea id="observacion<?php echo $i?>" readonly>
								<?php echo $resultadoObservacionesReg[$i]['observacion'] ?>
							</textarea>
							
							<?php 
							if($resultadoObservacionesReg[$i]['visto'] == 'f'){
								?>
								
								<ul class="semaforo rojo">
									<li></li>
									<li></li>
									<li></li>
									<li></li>
								</ul>
								<div style="clear: both;"></div>
								
								<?php
							}else{
							?>
								<ul class="semaforo verde">
									<li></li>
									<li></li>
									<li></li>
									<li></li>
								</ul>
								<div style="clear: both;"></div>
							
							<?php 
							}
							?>
						
						</div>
					</br>
					
        			
        			<?php
        			
        			
        			$i++;
        			
        		}
        		?>
        		
        		</div>
        		
        		
        		<div align="right">
	        		<div id="marcoDes" class="shadow">
						<center><b>Estado Observación</b></center>
						<ul class="semaforop panel">
							<li>Enviada&nbsp;</li>
							<li></li>
							<li></li>
							<li></li>
							<li></li>
							<li>Vista&nbsp;</li>
							<li></li>
							<li></li>
							<li></li>
							<li></li>
						</ul>
						<div style="clear: both;"></div>
					</div>
				</div>
        		
        		
        		
        		<?php
        		echo "</center>";
        		
        		
        	?>
        	</fieldset>
        	</div>
        	<?php	 
        	
        }
        
        
        

        $esteCampo = "marcoObservacionAdd";
        $atributos ['id'] = $esteCampo;
        $atributos ["estilo"] = "jqueryui";
        $atributos ['tipoEtiqueta'] = 'inicio';
        $atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo);
        echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);
        {

            $esteCampo = 'observacion';
            $atributos ['id'] = $esteCampo;
            $atributos ['nombre'] = $esteCampo;
            $atributos ['tipo'] = 'text';
            $atributos ['estilo'] = 'jqueryui';
            $atributos ['marco'] = true;
            $atributos ['estiloMarco'] = '';
            $atributos ["etiquetaObligatorio"] = true;
            $atributos ['columnas'] = 20;
            $atributos ['filas'] = 3;
            $atributos ['dobleLinea'] = 0;
            $atributos ['tabIndex'] = $tab;
            $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
            $atributos ['validar'] = 'required, minSize[20]';
            $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
            $atributos ['deshabilitado'] = false;
            $atributos ['textoEnriquecido'] = true;
            $atributos ['tamanno'] = 20;
            $atributos ['maximoTamanno'] = '';
            $atributos ['anchoEtiqueta'] = 220;
            
            
            $atributos ['valor'] = $resultadoRespuesta[0]['observaciones'];
            
            $tab ++;
            
            // Aplica atributos globales al control
            $atributos = array_merge ( $atributos, $atributosGlobales );
            echo $this->miFormulario->campoTextArea ( $atributos );
            


            
            
            // ------------------Division para los botones-------------------------
            $atributos ["id"] = "botones";
            $atributos ["estilo"] = "marcoBotones";
            echo $this->miFormulario->division("inicio", $atributos);
            {
            	// -----------------CONTROL: Botón ----------------------------------------------------------------
            
            
            	$esteCampo = 'botonRegistrar';
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

        unset ( $atributos );


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
        $valorCodificado .= "&opcion=observacion";
        $valorCodificado .= "&usuario=" . $_REQUEST['usuario'];
        $valorCodificado .= "&solicitud=" . $_REQUEST['idSolicitud'];
        $valorCodificado .= "&solicitudPro=" . $id_solicitud[0][0];
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
        