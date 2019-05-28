<?php

namespace asignacionPuntajes\salariales\experienciaDireccionAcademica\formulario;

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
        echo $this->miFormulario->formulario($atributos);


        echo "<div id='marcoDatosLoad' style='width: 100%;height: 900px'>
			<div style='width: 100%;height: 100px'>
			</div>
			<center><img src='" . $rutaBloque . "/images/loading.gif'" . ' width=20% height=20% vspace=15 hspace=3 >
			</center>
		  </div>';
        

        // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
        $esteCampo = "marcoContratos";
        $atributos ['id'] = $esteCampo;
        $atributos ["estilo"] = "jqueryui";
        $atributos ['tipoEtiqueta'] = 'inicio';
        $atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo);
        echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);
        
        
        

        //****************************************************************************************
        //****************************************************************************************
       
        
        $cadenaSql = $this->miSql->getCadenaSql('listarObjetosParaMensajeJoin', $_REQUEST['idSolicitud']);
        $datosSolicitud= $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda", $_REQUEST['idSolicitud'], 'listarObjetosParaMensajeJoin');
        
    
        $cadenaSql = $this->miSql->getCadenaSql ( 'dependenciaUdistritalById', $datosSolicitud[0]['jefe_dependencia'] );
        $resultadoDependencia = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
         
        $cadenaSql = $this->miSql->getCadenaSql ( 'dependenciaUdistritalById', $datosSolicitud[0]['jefe_dependencia_destino'] );
        $resultadoDependenciaDes = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
         
        $cadenaSql = $this->miSql->getCadenaSql ( 'ordenadorUdistritalById', $datosSolicitud[0]['ordenador_gasto'] );
        $resultadoOrdenador = $coreRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
        
        
        //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        
        $cadenaSql = $this->miSql->getCadenaSql('castStringSolicitudes', $_REQUEST['idSolicitud']);
        $arrayCast = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
        
        $cadenaSql = $this->miSql->getCadenaSql('infoProveedorObservaciones', $arrayCast[0][0]);
        $arrayInfoCast = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
        
        
		
        
        
        $cadenaSql = $this->miSql->getCadenaSql('listProveedoresObs', $_REQUEST['idSolicitud']);
        $idListObs = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
		
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        
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
        
        
        
        if($arrayInfoCast){
        
        
	        $tipo = 'success';
	        $mensaje = "<b>ATENCIÓN</b><br>
								<br>
								A continuación, usted puede ver las distintas observaciones que los proveedores relacionados a la cotización
	        					han registrado para que las tenga en cuenta, es importante tener en cuenta que no presenta información del proveedor
	        					que realiza la observación por transparencia, solo se limitara a agrupar las observaciones por cada proveedor.
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
	        
	        
		        // ---------------- CONTROL: Cuadro de Texto--------------------------------------------------------
		        $esteCampo = 'countObservacionesByPro';
		        $atributos ['id'] = $esteCampo;
		        $atributos ['nombre'] = $esteCampo;
		        $atributos ['tipo'] = 'hidden';
		        $atributos ['tabIndex'] = $tab;
		         
		        $atributos ['valor'] = count($idListObs);
		         
		        // Aplica atributos globales al control
		        $atributos = array_merge ( $atributos, $atributosGlobales );
		        echo $this->miFormulario->campoCuadroTexto ( $atributos );
		        unset ( $atributos );
	        
	        	
	        	$j = 0;
	        	while($j < count($idListObs)){
	        	
	        		
	        		$cadenaSql = $this->miSql->getCadenaSql('consultarObservacionesReg', $idListObs[$j][0]);
	        		$resultadoObservacionesReg = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
	        		
	        		?>
	        		
	        		
	        		<?php
	        
			        if($resultadoObservacionesReg){
			        	
			        	?>
			        	
			        	<input type="hidden" id="countObservaciones<?php echo $j?>" value="<?php echo count($resultadoObservacionesReg)?>">
			        	
			        	<?php 
			        	
			        	
			        	
			        	
			        				?>
			        	        	<div align="center">
			        	        	<fieldset id="marcoObservacionView">
			        				        <legend id="leyenda2" class="ui-state-default ui-corner-all" >Observaciones Registradas (Proveedor N° <?php echo $j + 1?>)</legend>
			        	        				        					
			        	
			        					<?php
			        					echo "<center>";
			        					$i = 0;
			        					while ( $i < count ( $resultadoObservacionesReg ) ) {
			        	        			
			        	        			
			        	        			?>
			        	        			
			    								<div>
			        			        			<textarea id="observacion<?php echo $j.$i?>" readonly>
			        									<?php echo $resultadoObservacionesReg[$i]['observacion'] ?>
			        								</textarea>
			        								
			        								<?php 
			        								
			        								$cadenaSql = $this->miSql->getCadenaSql('cambioEstadoObs', $resultadoObservacionesReg[$i]['id']);
			        								$resultadoObservacionesView = $esteRecursoDB->ejecutarAcceso($cadenaSql, "insertar");
			        								
			        								?>
			        							
			        							</div>
			        	        			
			        	        			<?php
			        	        			
			        	        			
			        	        			$i++;
			        	        			
			        	        		}
			        	        		?>		        	        		
			        	        		
			        	        		<?php
			        	        		echo "</center>";
			        	        		
			        	        		
			        	        	?>
			        	        	</fieldset>
			        	        	</div>
			        	        	<?php	
			        	 
 
			                	
			         }
			                
			         $j++;  
		                
	        	}
	        	
	        	?>
	        	
	        	
	        	<?php 
                
        	}else{
        		
        		$tipo = 'information';
	        $mensaje = "<b>INFORMACIÓN</b><br>
								<br>
								No se registran observaciones, por parte de ningún proveedor relacionado.
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
	        
        		
        }
        
        
        echo "<div id=\"limitObs\">";
        
        $esteCampo = 'observacionLimit';
        $atributos ['id'] = $esteCampo;
        $atributos ['nombre'] = $esteCampo;
        $atributos ['tipo'] = 'hidden';
        $atributos ['estilo'] = 'jqueryui';
        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
        $atributos ['textoEnriquecido'] = true;
        $atributos ['tabIndex'] = $tab;
        
        
        $atributos ['valor'] = "";
        
        $tab ++;
        
        // Aplica atributos globales al control
        $atributos = array_merge ( $atributos, $atributosGlobales );
        echo $this->miFormulario->campoTextArea ( $atributos );
        
        echo "</div>";

        unset($atributos);
        echo $this->miFormulario->marcoAgrupacion('fin');

        

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

        $valorCodificado = "action=" . $esteBloque ["nombre"];
        $valorCodificado .= "&pagina=" . $this->miConfigurador->getVariableConfiguracion('pagina');
        $valorCodificado .= "&bloque=" . $esteBloque ['nombre'];
        $valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
        $valorCodificado .= "&opcion=registrarActividad";
        $valorCodificado .= "&usuario=" . $_REQUEST['usuario'];

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
        $atributos ['marco'] = false;
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