<?php
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}

$esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );

$rutaBloque = $this->miConfigurador->getVariableConfiguracion ( "host" );
$rutaBloque .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/";
$rutaBloque .= $esteBloque ['grupo'] . "/" . $esteBloque ['nombre'];

$directorio = $this->miConfigurador->getVariableConfiguracion ( "host" );
$directorio .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/index.php?";
$directorio .= $this->miConfigurador->getVariableConfiguracion ( "enlace" );
$miSesion = Sesion::singleton ();

$miPaginaActual = $this->miConfigurador->getVariableConfiguracion ( "pagina" );

$nombreFormulario = $esteBloque ["nombre"];

$conexion = "estructura";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );

// validamos los datos que llegan



// $variable_fecha = $_REQUEST ['fechaRegistro'];

{
	$tab = 1;
	
	include_once ("core/crypto/Encriptador.class.php");
	$cripto = Encriptador::singleton ();
	
	// ---------------Inicio Formulario (<form>)--------------------------------
	$atributos ["id"] = $nombreFormulario;
	$atributos ["tipoFormulario"] = "multipart/form-data";
	$atributos ["metodo"] = "POST";
	$atributos ["nombreFormulario"] = $nombreFormulario;
	$atributos ["tipoEtiqueta"] = 'inicio';
	$verificarFormulario = "1";
	echo $this->miFormulario->formulario ( $atributos );
	
	
	$miPaginaActual = $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
		
	$directorio = $this->miConfigurador->getVariableConfiguracion ( "host" );
	$directorio .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/index.php?";
	$directorio .= $this->miConfigurador->getVariableConfiguracion ( "enlace" );
		
	$variable = "pagina=" . $miPaginaActual;
	//$variable .= "&usuario=".$_REQUEST['usuario'];
	$variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variable, $directorio );
		
	// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
	$esteCampo = 'botonRegresar';
	$atributos ['id'] = $esteCampo;
	$atributos ['enlace'] = $variable;
	$atributos ['tabIndex'] = 1;
	$atributos ['estilo'] = 'textoSubtitulo';
	$atributos ['enlaceTexto'] = $this->lenguaje->getCadena ( $esteCampo );
	$atributos ['ancho'] = '10%';
	$atributos ['alto'] = '10%';
	$atributos ['redirLugar'] = true;
	echo $this->miFormulario->enlace ( $atributos );
		
	unset ( $atributos );
	
	
	

	

	
	$valorCodificado = "pagina=" . $miPaginaActual;
	$valorCodificado .= "&bloque=" . $esteBloque ["id_bloque"];
	$valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
	$valorCodificado = $cripto->codificar ( $valorCodificado );
	// -------------Control cuadroTexto con campos ocultos-----------------------
	// Para pasar variables entre formularios o enviar datos para validar sesiones
	$atributos ["id"] = "formSaraData"; // No cambiar este nombre
	$atributos ["tipo"] = "hidden";
	$atributos ["obligatorio"] = false;
	$atributos ["etiqueta"] = "";
	$atributos ["valor"] = $valorCodificado;
	echo $this->miFormulario->campoCuadroTexto ( $atributos );
	unset ( $atributos );

	
	
	
}

unset($resultadoContratos);
//CONSULTAR PROVEEDOR
$cadena_sql = $this->sql->getCadenaSql ( "consultarProveedor", $_REQUEST ['nit_proveedor'] );
$resultadoProveedor = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );

if ($resultadoProveedor) {
    

		$esteCampo = "marcoProveedor";
		$atributos ['id'] = $esteCampo;
		$atributos ["estilo"] = "jqueryui";
		$atributos ['tipoEtiqueta'] = 'inicio';
		$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
		echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );                
                    
		//INICIO INFORMACION PROVEEDOR	
		echo "<span class='textoElegante textoEnorme textoAzul'>NIT: </span>"; 
                echo "<span class='textoElegante textoMediano textoGris'>". $resultadoProveedor[0][1] . "</span></br>"; 
		echo "<span class='textoElegante textoEnorme textoAzul'>Nombre Empresa: </span>"; 
                echo "<span class='textoElegante textoMediano textoGris'>". $resultadoProveedor[0][2] . "</span></br>"; 
		echo "<span class='textoElegante textoEnorme textoAzul'>Puntaje Total : </span>"; 
                echo "<span class='textoElegante textoMediano textoGris'>". $resultadoProveedor[0][3] . "</span></br>";  
                echo "<span class='textoElegante textoEnorme textoAzul'>Clasificación : </span>"; 
		$claseficacionActual = $resultadoProveedor[0]["clasificacion_evaluacion"];	
		if(  $claseficacionActual == 'A' || $claseficacionActual == 'B' || $claseficacionActual == 'C' ){
                    echo "<span class='textoElegante textoMediano textoGris'>". $claseficacionActual . "</span></br>"; 
                }else{
                    echo "<span class='textoElegante textoMediano textoGris'>No se encuentra evaluado</span></br>"; 
                }                
		//FIN INFORMACION PROVEEDOR
		echo $this->miFormulario->marcoAgrupacion ( 'fin' );


//Si se encuentra evaluado muestro las evaluaciones
if(  $claseficacionActual == 'A' || $claseficacionActual == 'B' || $claseficacionActual == 'C' ){
	
	$idProveedor = $resultadoProveedor[0]["id_proveedor"];	
	//CONSULTAR EVALUACIONES DEL PROVEEDOR
	$cadena_sql = $this->sql->getCadenaSql ( "evalaucionByIdProveedor", $idProveedor  );
	$resultadoContratos = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );	

	
	

	// -----------------Inicio de Conjunto de Controles----------------------------------------
		$esteCampo = "marcoEvaluacion";
		$atributos ['id'] = $esteCampo;
		$atributos ["estilo"] = "jqueryui";
		$atributos ['tipoEtiqueta'] = 'inicio';
		$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
		echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );         
                unset ( $atributos );
                
	echo "<table id='tablaContratos'>";
	
	echo "<thead>
                <tr>
		     <th>Número Contrato</th>
			 <th>Objeto</th>
             <th>Supervisor</th>
			 <th>Fecha Evaluaciòn</th>
             <th>Puntaje total</th>
             <th>Clasificaciòn</th>
			 <th>Ver Evaluaciòn</th>
        	 </tr>
            </thead>
            <tbody>";
	
	for($i = 0; $i < count ( $resultadoContratos ); $i ++) {
		
		$variable = "pagina=" . $miPaginaActual; // pendiente la pagina para modificar parametro
		$variable .= "&opcion=consultar";
		$variable .= "&num_contrato=" . $resultadoContratos [$i] ["numero_contrato"];
		$variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variable, $directorio );
		
		$mostrarHtml = "<tr>
					
				    <td><center>" . $resultadoContratos [$i] ["numero_contrato"] . "</center></td>
                    <td><center>" . $resultadoContratos [$i] ["objetocontratar"] . "</center></td>
                    <td><center>" . $resultadoContratos [$i] ["nombre_supervisor"] . "</center></td>
                    <td><center>" . $resultadoContratos [$i] ["fecha_registro"] . "</center></td>
					<td><center>" . $resultadoContratos [$i] ["puntaje_total"] . "</center></td>
					<td><center>" . $resultadoContratos [$i] ["clasificacion"] . "</center></td>
                    <td><center>
                        <a href='" . $variable . "'>                        
                            <img src='" . $rutaBloque . "/images/edit.png' width='15px'> 
                        </a>
                   </center></td>
                </tr>";
		echo $mostrarHtml;
		unset ( $mostrarHtml );
		unset ( $variable );
	}
	
	echo "</tbody>";
	
	echo "</table>";
	

        echo $this->miFormulario->marcoAgrupacion ( 'fin' );
	unset ( $atributos );
	
	
	// Fin de Conjunto de Controles
	// echo $this->miFormulario->marcoAgrupacion("fin");
}} else {

		// ------------------INICIO Division para los botones-------------------------
		$atributos ["id"] = "divNoEncontroEgresado";
		$atributos ["estilo"] = "marcoBotones";
		echo $this->miFormulario->division ( "inicio", $atributos );
		// -------------SECCION: Controles del Formulario-----------------------
		$esteCampo = "mensajeNoEncontroProveedor";
		$atributos ["id"] = $esteCampo; // Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
		$atributos ["etiqueta"] = "";
		$atributos ["estilo"] = "centrar";
		$atributos ["tipo"] = 'error';
		$atributos ["mensaje"] = $this->lenguaje->getCadena ( $esteCampo );
		
		echo $this->miFormulario->cuadroMensaje ( $atributos );
		unset ( $atributos );
		// -------------FIN Control Formulario----------------------
		// ------------------FIN Division para los botones-------------------------
		echo $this->miFormulario->division ( "fin" );
		unset ( $atributos );	

}


$atributos ['marco'] = true;
$atributos ['tipoEtiqueta'] = 'fin';
echo $this->miFormulario->formulario ( $atributos );
unset($atributos);


?>