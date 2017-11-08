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


        $esteCampo = "marcoActividadesCot";
        $atributos ['id'] = $esteCampo;
        $atributos ["estilo"] = "jqueryui";
        $atributos ['tipoEtiqueta'] = 'inicio';
        $atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo);
        echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);


        //DATOS NECESIDAD
        $datos = array(
            'idObjeto' => $_REQUEST['idObjeto']
        );


    	$cadenaSql = $this->miSql->getCadenaSql ( 'infoCotizacionCast', $datos['idObjeto'] );
		$solicitudCotizacionCast = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );

		$cadenaSql = $this->miSql->getCadenaSql ( 'infoCotizacion', $datos['idObjeto'] );
		$solicitudCotizacion = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );

		$cadenaSql = $this->miSql->getCadenaSql ( 'dependenciaUdistritalById', $solicitudCotizacion[0]['jefe_dependencia'] );
		$resultadoDependencia = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		 
		$cadenaSql = $this->miSql->getCadenaSql ( 'ordenadorUdistritalById', $solicitudCotizacion[0]['ordenador_gasto'] );
		$resultadoOrdenador = $argoRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
		$cadenaSql = $this->miSql->getCadenaSql ( 'buscarUsuario', $solicitudCotizacion[0]['usuario_creo'] );
		$resultadoUsuario = $frameworkRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
		if($solicitudCotizacion[0]['unidad_ejecutora'] == 1){
			$valorUnidadEjecutoraText = "1 - Rectoría";
		}else{
			$valorUnidadEjecutoraText = "2 - IDEXUD";
		}

		$esteCampo = "marcoObjetoAct";
		$atributos ['id'] = $esteCampo;
		$atributos ["estilo"] = "jqueryui";
		$atributos ['tipoEtiqueta'] = 'inicio';
		$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
		echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
		
		echo "<span class='textoElegante textoEnorme textoAzul'>Título Cotización : </span>";
		echo "<span class='textoElegante textoGrande textoGris'><b>". $solicitudCotizacionCast[0]['titulo_cotizacion'] . "</b></span></br>";
		echo "<br>";
		echo "<span class='textoElegante textoEnorme textoAzul'>N° Cotización - Vigencia - Unidad Ejecutora : </span>";
		echo "<span class='textoElegante textoGrande textoGris'><b>". $_REQUEST['idObjeto']. " - ". $solicitudCotizacion[0]['vigencia'] . " - (" .$valorUnidadEjecutoraText. ")</b></span></br>";
		echo "<br>";
		echo "<span class='textoElegante textoEnorme textoAzul'>Fecha de Apertura : </span>";
		echo "<span class='textoElegante textoEnorme textoGris'><b>". $this->cambiafecha_format($solicitudCotizacionCast[0]['fecha_apertura']) . "</b></span></br>";
		echo "<br>";
		echo "<span class='textoElegante textoEnorme textoAzul'>Fecha de Cierre : </span>";
		echo "<span class='textoElegante textoEnorme textoGris'><b>". $this->cambiafecha_format($solicitudCotizacionCast[0]['fecha_cierre']). "</b></span></br>";
		echo "<br>";
		echo "<span class='textoElegante textoEnorme textoAzul'>Ordenador del Gasto Relacionado : </span>";
		echo "<span class='textoElegante textoGrande textoGris'><b>". $resultadoOrdenador[0][1]. "</b></span></br>";
		echo "<br>";
		echo "<span class='textoElegante textoEnorme textoAzul'>Dependencia Solicitante : </span>";
		echo "<span class='textoElegante textoGrande textoGris'><b>". $resultadoDependencia[0][1]. "</b></span></br>";
		echo "<br>";
		echo "<span class='textoElegante textoEnorme textoAzul'>Responsable : </span>";
		echo "<span class='textoElegante textoGrande textoGris'><b>". $resultadoUsuario[0]['identificacion'] . " - " . $resultadoUsuario[0]['nombre'] . " " . $resultadoUsuario[0]['apellido']."</b></span></br>";
		
		
		//FIN OBJETO A CONTRATAR
		echo $this->miFormulario->marcoAgrupacion ( 'fin' );

        // ----------------INICIO ACTIVIDADES ECONOMICAS REGISTRADAS--------------------------------------------------------
        $cadenaSql = $this->miSql->getCadenaSql('consultarActividades', $_REQUEST['idObjeto']);
        $resultadoActividades = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");

        if ($resultadoActividades) {

            $esteCampo = "marcoActividades";
            $atributos ['id'] = $esteCampo;
            $atributos ["estilo"] = "jqueryui";
            $atributos ['tipoEtiqueta'] = 'inicio';
            $atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo);
            echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);

            foreach ($resultadoActividades as $dato):
				echo "<span class='textoElegante textoEnorme textoAzul'>+ </span><b>";
				echo $dato['subclase'] . ' - ' . $dato['nombre'] . "</b><br>";
			endforeach;

            echo $this->miFormulario->marcoAgrupacion('fin');
        }


        // ----------------FIN ACTIVIDADES ECONOMICAS REGISTRADAS--------------------------------------------------------		

        $esteCampo = "marcoCIIU";
        $atributos ['id'] = $esteCampo;
        $atributos ["estilo"] = "jqueryui";
        $atributos ['tipoEtiqueta'] = 'inicio';
        $atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo);
        echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);

        // ---------------- CONTROL: Lista clase CIIU--------------------------------------------------------
        $esteCampo = "claseCIIU";
        $atributos ['nombre'] = $esteCampo;
        $atributos ['id'] = $esteCampo;
        $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
        $atributos ["etiquetaObligatorio"] = false;
        $atributos ['tab'] = $tab ++;
        $atributos ['anchoEtiqueta'] = 200;
        $atributos ['evento'] = '';
        if (isset($_REQUEST [$esteCampo])) {
            $atributos ['seleccion'] = $_REQUEST [$esteCampo];
        } else {
            $atributos ['seleccion'] = - 1;
        }
        $atributos ['deshabilitado'] = false;
        $atributos ['columnas'] = 1;
        $atributos ['tamanno'] = 1;
        $atributos ['ajax_function'] = "";
        $atributos ['ajax_control'] = $esteCampo;
        $atributos ['estilo'] = "jqueryui";
        $atributos ['validar'] = "";
        $atributos ['limitar'] = false;
        $atributos ['anchoCaja'] = 60;
        $atributos ['miEvento'] = '';
        $atributos ['cadena_sql'] = $cadenaSql = $this->miSql->getCadenaSql('ciiuSubClase');
        $matrizItems = $coreRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
        $atributos ['matrizItems'] = $matrizItems;
        $atributos = array_merge($atributos, $atributosGlobales);
        echo $this->miFormulario->campoCuadroLista($atributos);
        unset($atributos);
        // ----------------FIN CONTROL: Lista clase CIIU--------------------------------------------------------

        echo $this->miFormulario->marcoAgrupacion('fin');
        
      

        
       
        $esteCampo = 'idsObjeto';
        $atributos ["id"] = $esteCampo; // No cambiar este nombre
        $atributos ["tipo"] = "hidden";
        $atributos ['estilo'] = '';
        $atributos ["obligatorio"] = false;
        $atributos ['marco'] = true;
        $atributos ["etiqueta"] = "";
        if (isset($_REQUEST [$esteCampo])) {
            $atributos ['valor'] = $_REQUEST [$esteCampo];
        } else {
            $atributos ['valor'] = $_REQUEST['idObjeto'];
        }
        $atributos = array_merge($atributos, $atributosGlobales);
        echo $this->miFormulario->campoCuadroTexto($atributos);
        unset($atributos);
        
      

        $esteCampo = 'idsActividades';
        $atributos ["id"] = $esteCampo; // No cambiar este nombre
        $atributos ["tipo"] = "hidden";
        $atributos ['estilo'] = '';
        $atributos ["obligatorio"] = false;
        $atributos ['marco'] = true;
        $atributos ["etiqueta"] = "";
        if (isset($_REQUEST [$esteCampo])) {
            $atributos ['valor'] = $_REQUEST [$esteCampo];
        } else {
            $atributos ['valor'] = '';
        }
        $atributos = array_merge($atributos, $atributosGlobales);
        echo $this->miFormulario->campoCuadroTexto($atributos);
        unset($atributos);

        $esteCampo = "marcoCIIU";
        $atributos ['id'] = $esteCampo;
        $atributos ["estilo"] = "jqueryui";
        $atributos ['tipoEtiqueta'] = 'inicio';
        $atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo);
        echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);






        echo '<table id="tabla" class="table1" width="100%" >
				<!-- Cabecera de la tabla -->
				<thead>
					<tr>
						<th width="10%" >Codigo</th>
						<th width="50%" >Descripción</th>
						<th width="25%" >Clase</th>
						<th width="15%" >&nbsp;</th>
					</tr>
				</thead>
			 
				<!-- Cuerpo de la tabla con los campos -->
				<tbody>
			 
					<!-- fila base para clonar y agregar al final -->
					<tr class="fila-base">
						<td><input type="text" class="nombre" size="40" /></td>
						<td><input type="text" class="apellidos" size="125" /></td>
						<td><input type="text" class="apellidos" size="65" /></td>
						<th class="eliminar" scope="row">Eliminar</th>
					</tr>
					<!-- fin de código: fila base -->';



        echo '<!--					 Fila de ejemplo 
					<tr>
						<td><input type="text" class="nombre" size="40" /></td>
						<td><input type="text" class="apellidos" size="125" /></td>
						<td><input type="text" class="apellidos" size="65" /></td>
						<th class="eliminar" scope="row">Eliminar</th>
					</tr>-->
					<!-- fin de código: fila de ejemplo -->
			 
				</tbody>
			</table>
			';










        echo $this->miFormulario->marcoAgrupacion('fin');





        // ------------------Division para los botones-------------------------
        $atributos ["id"] = "botonesReg";
        $atributos ["estilo"] = "marcoBotones";
        echo $this->miFormulario->division("inicio", $atributos);
        {
            // -----------------CONTROL: Botón ----------------------------------------------------------------
            $esteCampo = 'botonContinuar';
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
        // 			------------------Fin Division para los botones-------------------------
        echo $this->miFormulario->division("fin");

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
        $valorCodificado .= "&idObjeto=" . $_REQUEST['idObjeto'];
        $valorCodificado .= "&tipoNecesidad=" . $_REQUEST['tipoNecesidad'];

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