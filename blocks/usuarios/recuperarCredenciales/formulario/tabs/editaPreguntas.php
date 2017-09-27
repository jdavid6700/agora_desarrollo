<?php
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}

use usuarios\recuperarCredenciales\funcion\redireccion;

class registrarForm {
	var $miConfigurador;
	var $lenguaje;
	var $miFormulario;
	var $miSql;
	function __construct($lenguaje, $formulario, $sql) {
		$this->miConfigurador = \Configurador::singleton ();
		
		$this->miConfigurador->fabricaConexiones->setRecursoDB ( 'principal' );
		
		$this->lenguaje = $lenguaje;
		
		$this->miFormulario = $formulario;
		
		$this->miSql = $sql;
	}
	function miForm() {
		
		// Rescatar los datos de este bloque
		$esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );
		
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
		
		$_REQUEST ['tiempo'] = time ();
		$tiempo = $_REQUEST ['tiempo'];
		
		// lineas para conectar base de d atos-------------------------------------------------------------------------------------------------
		$conexion = "framework";
		$frameworkRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
		
		$conexion = "estructura";
		$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
		
		//$conexion = "wconexionclave";
		//$esteRecursoDBORA = $this->miConfigurador->fabricaConexiones->getRecursoDB( $conexion );

        $seccion ['tiempo'] = $tiempo;
        $miSesion = \Sesion::singleton();

                
		// ---------------- SECCION: Parámetros Generales del Formulario ----------------------------------
		$esteCampo = $esteBloque ['nombre'];
		$atributos ['id'] = $esteCampo;
		$atributos ['nombre'] = $esteCampo;
		// Si no se coloca, entonces toma el valor predeterminado 'application/x-www-form-urlencoded'
		$atributos ['tipoFormulario'] = 'multipart/form-data';
		// Si no se coloca, entonces toma el valor predeterminado 'POST'
		$atributos ['metodo'] = 'POST';
		// Si no se coloca, entonces toma el valor predeterminado 'index.php' (Recomendado)
		$atributos ['action'] = 'index.php';
		// $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo );
		// Si no se coloca, entonces toma el valor predeterminado.
		$atributos ['estilo'] = '';
		$atributos ['marco'] = false;
		$tab = 1;
		// ---------------- FIN SECCION: de Parámetros Generales del Formulario ----------------------------
		// ----------------INICIAR EL FORMULARIO ------------------------------------------------------------
		$atributos ['tipoEtiqueta'] = 'inicio';
		echo $this->miFormulario->formulario ( $atributos );
		{
			// ---------------- SECCION: Controles del Formulario -----------------------------------------------
			
			$miPaginaActual = $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
			
                        $rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
                        $rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/";
                        $rutaBloque.= $esteBloque['grupo'] . "/" . $esteBloque['nombre'];
                        
			$directorio = $this->miConfigurador->getVariableConfiguracion ( "host" );
			$directorio .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/index.php?";
			$directorio .= $this->miConfigurador->getVariableConfiguracion ( "enlace" );
			
			$variable = "pagina=" . $miPaginaActual;
			$variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variable, $directorio );
			
			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                        $esteCampo = 'botonRegresar';
                        $atributos ['id'] = $esteCampo;
                        $atributos ['enlace'] = $variable;
                        $atributos ['tabIndex'] = 1;
                        $atributos ['enlaceTexto'] = $this->lenguaje->getCadena ( $esteCampo );
                        $atributos ['estilo'] = 'textoPequenno textoGris';
                        $atributos ['enlaceImagen'] = $rutaBloque."/images/player_rew.png";
                        $atributos ['posicionImagen'] = "atras";//"adelante";
                        $atributos ['ancho'] = '30px';
                        $atributos ['alto'] = '30px';
                        $atributos ['redirLugar'] = true;
                       // echo $this->miFormulario->enlace ( $atributos );
                        unset ( $atributos );
                        
                        
                        
            //var_dump($_REQUEST);   
            
            
            $cadena_sql = $this->miSql->getCadenaSql("consultarUsuario", $_REQUEST['id_usuario']);
            $resultadoUs = $frameworkRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
            
            
            //echo $cadena_sql;
            //var_dump($resultadoUs);
            
            
            if($resultadoUs){
            	
            	
            	$esteCampo = 'id_usuario';
            	$atributos ["id"] = $esteCampo; // No cambiar este nombre
            	$atributos ["tipo"] = "hidden";
            	$atributos ['estilo'] = '';
            	$atributos ["obligatorio"] = false;
            	$atributos ['marco'] = true;
            	$atributos ["etiqueta"] = "";
            	$atributos ['valor'] = $_REQUEST['id_usuario'];
            	$atributos = array_merge($atributos, $atributosGlobales);
            	echo $this->miFormulario->campoCuadroTexto($atributos);
            	unset($atributos);
            	
            	$esteCampo = 'usuario';
            	$atributos ["id"] = $esteCampo; // No cambiar este nombre
            	$atributos ["tipo"] = "hidden";
            	$atributos ['estilo'] = '';
            	$atributos ["obligatorio"] = false;
            	$atributos ['marco'] = true;
            	$atributos ["etiqueta"] = "";
            	$atributos ['valor'] = $resultadoUs[0]['id_usuario'];
            	$atributos = array_merge($atributos, $atributosGlobales);
            	echo $this->miFormulario->campoCuadroTexto($atributos);
            	unset($atributos);
            	
            	
         
            	
            	$esteCampo = "mensajeCambiarClavePreguntas";
            	$atributos ['id'] = $esteCampo;
            	$atributos ["estilo"] = "jqueryui";
            	$atributos ['tipoEtiqueta'] = 'inicio';
            	$atributos ["leyenda"] =  $this->lenguaje->getCadena ( $esteCampo );
            	echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
            	unset ( $atributos );
            	{
            	
            	
            		$datosAleatorios = false;
            		//$cadena_sql = $this->miSql->getCadenaSql("datosAleatorios", $variable);
            		//$datosAleatorios=$esteRecursoDBORA->ejecutarAcceso($cadena_sql, "busqueda");
          
            		
            		$pregunta1="¿Cuál de los siguientes números de teléfono tiene registrado en el sistema?";
            		$pregunta2="¿Cuál de las siguientes cuentas bancarias tiene registrada en el sistema?";
				    //$pregunta2="¿Cuál de las siguientes direcciones tiene registrada en el sistema?";
				    $pregunta3="¿Cuál de los siguientes correos electrónicos le pertenece?";
				    $pregunta4="¿Cuál de los siguientes números de identificación le pertenece?";
				    
				        
				    $preguntas = array("$pregunta1", "$pregunta2", "$pregunta3", "$pregunta4");
				    $randomPreguntas = array_rand($preguntas, 3);
				    $cuenta=count($randomPreguntas);
            		
            		$caracteres = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz123456789";
            		$caracteresDir = " AB CD E ab cd e ";
            		$caracteresMail = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
            		
            		$proveedores=array("gmail.com","yahoo.com","yahoo.es","hotmail.com","latinmail.com","udistrital.edu.co","correo.udistrital.edu.co","terra.com");
            		
            		$cardinales=array("","Sur","Este");
            		$cierto=0;
            		
            		
					$cadena_sql = $this->miSql->getCadenaSql ( "datosPersonas", $resultadoUs [0] ['identificacion'] );
					$datos = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
					
					if ($datos) {
						$cierto = 1;
					}
            			

            		
            		
            		
            		if($cierto==1)
            		{
            			//Telefonos
            			if($datos[0][3]!='')
            			{

            				$limit=strlen($datos[0][3]);
            				$f=0;
            				$cadena1='';
            				$cadena2='';
            				
            				while($f < $limit){
            					$cadena1=$cadena1 . '2';
            					$cadena2=$cadena2 . '9';
            					$f++;
            				}
            				
            				
            				$telefono1=$datos[0][3];
            				$telefono2=$datos[0][3]+rand('1','50');
            				$telefono3=rand($cadena1,$cadena2);
            			}
            			else
            			{
            				$telefono1="Sin registro";
            				$telefono2=rand('2222222','5555555');
            				$telefono3=rand('5555556','9999999');
            			}

            			
            			//Cuenta Bancaria
            			if($datos[0][7]!='')
            			{
            				
            				$limit=strlen($datos[0][7]);
            				$j=0;
            				$cadena1='';
            				$cadena2='';
            				
            				while($j < $limit){
            					$cadena1=$cadena1 . '2';
            					$cadena2=$cadena2 . '9';
            					$j++;
            				}
            				
            				$cuenta1=$datos[0][7];
            				$cuenta2=$datos[0][7]+rand('1','50');
            				$cuenta3=rand($cadena1,$cadena2);
            			}
            			else
            			{
            				$cuenta1="Sin registro";
            				$cuenta2=rand('22222222222','55555555555');
            				$cuenta3=rand('55555555556','99999999999');
            			}
            			
            			
            			//Direcciones
            			if($datos[0][2]!='')
            			{
            				$direccion1=$datos[0][2];
            				if($datosAleatorios[0][5]!='')
            				{
            					$direccion2=$datosAleatorios[0][5];
            				}
            				else
            				{
            					$direccion2="Cra ".rand('1','50')." ".substr($caracteresDir,rand(0,strlen($caracteresDir)),1)." No. ".rand('0','100')." ".substr($caracteresDir,rand(0,strlen($caracteresDir)),1)." - ".rand('1','100')." ";
            				}
            				$direccion3=substr($datos[0][2], 0, -4);
            			}
            			else
            			{
            				$direccion1="Sin registro";
            				$direccion2="Cra ".rand('1','50')." ".substr($caracteresDir,rand(0,strlen($caracteresDir)),1)." No. ".rand('0','100')." ".substr($caracteresDir,rand(0,strlen($caracteresDir)),1)." - ".rand('1','100')." ".$cardinales[rand(0,count($cardinales))]." ";
            				$direccion3="Cll ".rand('51','100')." ".substr($caracteresDir,rand(0,strlen($caracteresDir)),1)." ".$cardinales[rand(0,count($cardinales))]." No. ".rand('101','200')." ".substr($caracteresDir,rand(0,strlen($caracteresDir)),1)." - ".rand('1','100')." ";
            			}
            			
            
            			//Correos
            			if($datos[0][6]!='')
            			{
            				$correo1=$datos[0][6];
            				$corroAleatorio=explode('@',$datos[0][6]);
            		
            				if($datosAleatorios[0][13]!='' && $datosAleatorios)
            				{
            					$correo2=$datosAleatorios[0][13];
            				}
            				else
            				{
            					$correo2=$corroAleatorio[0].substr($caracteres,rand(0,strlen($caracteres)),1).'@'.$corroAleatorio[1];
            				}
            				$correo3=$corroAleatorio[0].'@'.$proveedores[rand(0,7)];
            			}
            			else
            			{
            				$corroAleatorio=explode(' ',$datos[0][1]);
            				$correo1="Sin registro";
            				$correo2=$corroAleatorio[0].'@'.$proveedores[rand(1,count($proveedores))];
            				if($datosAleatorios[0][13]!='' && $datosAleatorios)
            				{
            					$correo3=$datosAleatorios[0][13];
            				}
            				else
            				{
            					$correo3=$corroAleatorio[0].'@'.$proveedores[rand(1,count($proveedores))];
            				}
            			}
            			 
            			//Cedulas
            			$cedula1=$datos[0][0];
            			$cedula2=$datos[0][0]+rand(0,1000);
            			$cedula3=$datos[0][0]+rand(1001,10000);
            			 
            			$html="<table>";
            			 
            			for($i=0; $i<$cuenta; $i++)
            			{
            				$numPreg=$i+1;
            				if($preguntas[0]==$preguntas[$randomPreguntas[$i]])
            				{
            					$respuestas=array($telefono1,$telefono2,$telefono3);
            				}
            				if($preguntas[1]==$preguntas[$randomPreguntas[$i]])
            				{
            					$respuestas=array($cuenta1,$cuenta2,$cuenta3);
            				}
            				if($preguntas[2]==$preguntas[$randomPreguntas[$i]])
            				{
            					$respuestas=array($correo1,$correo2,$correo3);
            				}
            				if($preguntas[3]==$preguntas[$randomPreguntas[$i]])
            				{
            					$respuestas=array($cedula1,$cedula2,$cedula3);
            				}
            				
            				$esteCampo = 'preguntaSR';
            				$atributos ["id"] = $esteCampo.$i; // No cambiar este nombre
            				$atributos ["tipo"] = "hidden";
            				$atributos ['estilo'] = '';
            				$atributos ["obligatorio"] = false;
            				$atributos ['marco'] = true;
            				$atributos ["etiqueta"] = "";
            				$atributos ['valor'] = $respuestas[0];
            				$atributos = array_merge($atributos, $atributosGlobales);
            				echo $this->miFormulario->campoCuadroTexto($atributos);
            				unset($atributos);
            				
            		
            				shuffle($respuestas);
            				

            				
            				$html.="<tr>";
            				$html.="<td><h2>";
            				$html.=$numPreg.". ".$preguntas[$randomPreguntas[$i]];
            				$html.="</h2></td>";
            				$html.="</tr>";
            				$html.="<tr>";
            				$html.="<td>";
            				//------------------Control Lista Desplegable------------------------------
            				$html.="<table>";
            				$html.="<tr>";
            				
            				
            				
            				/*
            				// ---------------- CONTROL: Lista NACIONALIDAD Empresa --------------------------------------------------------
            				$esteCampo = "preguntaSeguridad";
            				$atributos ['nombre'] = $esteCampo.$i;
            				$atributos ['id'] = $esteCampo.$i;
            				$atributos ['etiqueta'] = $numPreg.". ".$preguntas[$randomPreguntas[$i]];
            				$atributos ["etiquetaObligatorio"] = false;
            				$atributos ['tab'] = $tab ++;
            				$atributos ['anchoEtiqueta'] = 550;
            				$atributos ['evento'] = '';
            				if (isset ( $_REQUEST [$esteCampo] )) {
            					$atributos ['seleccion'] = $_REQUEST [$esteCampo];
            				} else {
            					$atributos ['seleccion'] = -1;
            				}
            				$atributos ['deshabilitado'] = false;
            				$atributos ['columnas'] = 1;
            				$atributos ['tamanno'] = 1;
            				$atributos ['estilo'] = "jqueryui";
            				$atributos ['validar'] = "required";
            				$atributos ['limitar'] = false;
            				$atributos ['anchoCaja'] = 60;
            				$atributos ['miEvento'] = '';
            				// Valores a mostrar en el control
            				
            				
            				$matrizItems = array (
            						array ( 1, $respuestas[0] ),
            						array ( 2, $respuestas[1] ),
            						array ( 3, $respuestas[2] )
            				);
            				
            				$atributos ['matrizItems'] = $matrizItems;
            				
            				$atributos = array_merge ( $atributos, $atributosGlobales );
            				echo $this->miFormulario->campoCuadroLista ( $atributos );
            				unset ( $atributos );
            				// ----------------FIN CONTROL: Lista NACIONALIDAD Empresa--------------------------------------------------------
            				*/
            				
            				
            				
            				
            				
            				
            				
            				foreach ($respuestas as $respuestas)
            				{
            					$html.="<td align=\"center\" width=\"450px\">";
            					
            					
            					
            				$nombre = 'radioinput';
            				$atributos ['id'] = $nombre.$i;
            				$atributos ['nombre'] = $nombre.$i;
            				$atributos ['marco'] = true;
            				$atributos ['estilo'] = 'jquery';
            				$atributos ['estiloMarco'] = true;
            				$atributos ["etiquetaObligatorio"] = false;
            				$atributos ['columnas'] = 1;
            				$atributos ['dobleLinea'] = 1;
            				$atributos ['tabIndex'] = $tab;
            				$atributos ['etiqueta'] = $respuestas;
            				$atributos ['validar'] = 'required';
            				//$atributos ['seleccionado'] = 'true';
            				$atributos ['valor'] = $respuestas;
            				$atributos ['deshabilitado'] = false;
            				$tab ++;
            				
            				// Aplica atributos globales al control
            				$atributos = array_merge($atributos, $atributosGlobales);
            				$html.=$this->miFormulario->campoBotonRadial($atributos);
            				unset($atributos);
            					
            					
            					
            					
            					$html.="</td>";
            				}
            				
            				
            				
            				$html.="</tr>";
            				$html.="</table>";
            				$html.="</td>";
            				$html.="</tr>";
            				
            				
            			}
            			 
            			$html.="</table>";
            			
            			echo $html;
            			
            			
            			
            			// ------------------Division para los botones-------------------------
            			$atributos ["id"] = "botones";
            			$atributos ["estilo"] = "marcoBotones";
            			echo $this->miFormulario->division ( "inicio", $atributos );
            			unset ( $atributos );
            			{
            				// -----------------CONTROL: Botón ----------------------------------------------------------------
            				$esteCampo = 'botonEnviar';
            				$atributos ["id"] = $esteCampo;
            				$atributos ["tabIndex"] = $tab;
            				$atributos ["tipo"] = 'boton';
            				// submit: no se coloca si se desea un tipo button genérico
            				$atributos ['submit'] = true;
            				$atributos ["estiloMarco"] = '';
            				$atributos ["estiloBoton"] = 'jqueryui';
            				// verificar: true para verificar el formulario antes de pasarlo al servidor.
            				$atributos ["verificar"] = true;
            				$atributos ["tipoSubmit"] = 'jquery'; // Dejar vacio para un submit normal, en este caso se ejecuta la función submit declarada en ready.js
            				$atributos ["valor"] = $this->lenguaje->getCadena ( $esteCampo );
            				$atributos ['nombreFormulario'] = $esteBloque ['nombre'];
            				$tab ++;
            			
            				// Aplica atributos globales al control
            				$atributos = array_merge ( $atributos, $atributosGlobales );
            				echo $this->miFormulario->campoBoton ( $atributos );
            				// -----------------FIN CONTROL: Botón -----------------------------------------------------------
            			}
            			echo $this->miFormulario->division ( 'fin' );
            			 
            			 
            	

            		}else{
            			
            			
            			$esteCampo = "mensajeNoInformacion";
            			$atributos ['id'] = $esteCampo;
            			$atributos ["estilo"] = "jqueryui";
            			$atributos ['tipoEtiqueta'] = 'inicio';
            			$atributos ["leyenda"] =  $this->lenguaje->getCadena ( $esteCampo );
            			echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
            			unset ( $atributos );
            			{
            			
            			
            				// ---------------- SECCION: Controles del Formulario -----------------------------------------------
            				$esteCampo = 'mensaje';
            				 
            				$tipo = 'error';
            				 
            				$atributos["id"] = $esteCampo; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
            				$atributos["etiqueta"] = "";
            				$atributos["estilo"] = "centrar";
            				$atributos["tipo"] = $tipo;
            				 
            				$mensajeLey = "El Usuario o Número de Identificación: <br> <br> <center><b>".$_REQUEST['id_usuario']."</b></center> <br> <br> se encuentra Registrado en el Sistema
            				de Registro Único de Personas y Banco de Proveedores ÁGORA. Pero ha ocurrido un Error de Procedimiento(1180) por favor comuníquese con el Administrador del Sistema.";
            				 
            				$atributos["mensaje"] = $mensajeLey;
            				echo $this->miFormulario->cuadroMensaje($atributos);
            				unset($atributos);
            			
            			
            			}
            			echo $this->miFormulario->marcoAgrupacion ( 'fin' );
            			
            			
            			
            			// ------------------Division para los botones-------------------------
            			$atributos ["id"] = "botones";
            			$atributos ["estilo"] = "marcoBotones widget";
            			echo $this->miFormulario->division ( "inicio", $atributos );
            			unset ( $atributos );
            			{
            			
            				$esteCampo = 'botonTerminar';
            				$atributos ['id'] = $esteCampo;
            				$atributos ['enlace'] = '/agora';
            				$atributos ['tabIndex'] = 1;
            				$atributos ['estilo'] = 'jqueryui';
            				$atributos ['enlaceTexto'] = $this->lenguaje->getCadena ( $esteCampo );
            				//$atributos ['ancho'] = '10%';
            				//$atributos ['alto'] = '10%';
            				$atributos ['redirLugar'] = false;
            			
            				echo $this->miFormulario->enlace ( $atributos );
            			
            				unset($atributos);
            			}
            			echo $this->miFormulario->division ( 'fin' );
            			
            			
            		}
            		 
            		 
            		 
            		 
            		 
            		 
            	
            	
            	 }
            	 echo $this->miFormulario->marcoAgrupacion ( 'fin' );
            	
            	
            	
            }else{
            	
            	
            	
            	$esteCampo = "mensajeNoInformacion";
            	$atributos ['id'] = $esteCampo;
            	$atributos ["estilo"] = "jqueryui";
            	$atributos ['tipoEtiqueta'] = 'inicio';
            	$atributos ["leyenda"] =  $this->lenguaje->getCadena ( $esteCampo );
            	echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
            	unset ( $atributos );
            	{
            		
            		
            		// ---------------- SECCION: Controles del Formulario -----------------------------------------------
            		$esteCampo = 'mensaje';
            		 
            		$tipo = 'error';
            		 
            		$atributos["id"] = $esteCampo; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
            		$atributos["etiqueta"] = "";
            		$atributos["estilo"] = "centrar";
            		$atributos["tipo"] = $tipo;
            		 
            		$mensajeLey = "El Usuario o Número de Identificación: <br> <br> <center><b>".$_REQUEST['id_usuario']."</b></center> <br> <br> No se encuentra Registrado en el Sistema
            				de Registro Único de Personas y Banco de Proveedores ÁGORA. Si considera que es un Error por favor comuníquese con el Administrador del Sistema.";
            		 
            		$atributos["mensaje"] = $mensajeLey;
            		echo $this->miFormulario->cuadroMensaje($atributos);
            		unset($atributos);
            		
            		
            		// ------------------Division para los botones-------------------------
            		$atributos ["id"] = "botones";
            		$atributos ["estilo"] = "marcoBotones widget";
            		echo $this->miFormulario->division ( "inicio", $atributos );
            		unset ( $atributos );
            		{
            			 
            			$esteCampo = 'botonTerminar';
            			$atributos ['id'] = $esteCampo;
            			$atributos ['enlace'] = '/agora';
            			$atributos ['tabIndex'] = 1;
            			$atributos ['estilo'] = 'jqueryui';
            			$atributos ['enlaceTexto'] = $this->lenguaje->getCadena ( $esteCampo );
            			//$atributos ['ancho'] = '10%';
            			//$atributos ['alto'] = '10%';
            			$atributos ['redirLugar'] = false;
            		
            			echo $this->miFormulario->enlace ( $atributos );
            		
            			unset($atributos);
            		}
            		echo $this->miFormulario->division ( 'fin' );
            		
            		
            	}
            	echo $this->miFormulario->marcoAgrupacion ( 'fin' );
            	

            	
            	
            	
            	
            }
                        
                        
                        
			
			
			
			
			

			$numeroDocumento = 0;
			
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
			
			//$valorCodificado = "action=" . $esteBloque ["nombre"];
			$valorCodificado = "&pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
			$valorCodificado .= "&bloque=" . $esteBloque ['nombre'];
			$valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
			$valorCodificado .= "&opcion=validarPreguntas";
			$valorCodificado .= "&id_usuario=".$numeroDocumento;
			/**
			 * SARA permite que los nombres de los campos sean dinámicos.
			 * Para ello utiliza la hora en que es creado el formulario para
			 * codificar el nombre de cada campo. Si se utiliza esta técnica es necesario pasar dicho tiempo como una variable:
			 * (a) invocando a la variable $_REQUEST ['tiempo'] que se ha declarado en ready.php o
			 * (b) asociando el tiempo en que se está creando el formulario
			 */
			$valorCodificado .= "&campoSeguro=" . $_REQUEST ['tiempo'];
			$valorCodificado .= "&tiempo=" . time ();
			// Paso 2: codificar la cadena resultante
			$valorCodificado = $this->miConfigurador->fabricaConexiones->crypto->codificar ( $valorCodificado );
			
			$atributos ["id"] = "formSaraData"; // No cambiar este nombre
			$atributos ["tipo"] = "hidden";
			$atributos ['estilo'] = '';
			$atributos ["obligatorio"] = false;
			$atributos ['marco'] = true;
			$atributos ["etiqueta"] = "";
			$atributos ["valor"] = $valorCodificado;
			echo $this->miFormulario->campoCuadroTexto ( $atributos );
			unset ( $atributos );
			
			$atributos ['marco'] = false;
			$atributos ['tipoEtiqueta'] = 'fin';
			echo $this->miFormulario->formulario ( $atributos );
			
			return true;
		}
	}
}

$miSeleccionador = new registrarForm ( $this->lenguaje, $this->miFormulario, $this->sql );

$miSeleccionador->miForm ();
?>
