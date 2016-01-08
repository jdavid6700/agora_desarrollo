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
	$variable .= "&usuario=".$_REQUEST['usuario'];
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
	
	
	
	$esteCampo = "Agrupacion";
	$atributos ['id'] = $esteCampo;
	$atributos ["estilo"] = "jqueryui";
	$atributos ['tipoEtiqueta'] = 'inicio';
	$atributos ['leyenda'] = "Registro Contratos";
	echo $this->miFormulario->agrupacion ( 'inicio', $atributos );
	

	
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
	
		echo "<span class='textoElegante textoEnorme textoAzul'>PROVEEDOR</span><hr>"; 
		echo '<table style="width:100%">';
		echo '<tr><td style="width:20%"><span class="textoAzul">NIT</td><td><span class="textoGris">' . $resultadoProveedor[0][1] . "</span></td></tr>";
		echo '<tr><td style="width:20%"><span class="textoAzul">NOMBRE</td><td><span class="textoGris">' . $resultadoProveedor[0][2] . "</span></td></tr>";
		echo '<tr><td style="width:20%"><span class="textoAzul">PUNTAJE TOTAL</td><td><span class="textoGris">' . $resultadoProveedor[0][3] . "</span></td></tr>";
		if( $resultadoProveedor[0][4]==0 || $resultadoProveedor[0][4] == '' )
			echo '<tr><td style="width:20%"><span class="textoAzul">CLASIFICACIÒN</td><td><span class="textoGris">NO SE ENCUENTRA EVALUADO</span></td></tr>';
		else
			echo '<tr><td style="width:20%"><span class="textoAzul">CLASIFICACIÒN</td><td><span class="textoGris">' . $resultadoProveedor[0][4] . "</span></td></tr>";
		echo '</table>';
		//FIN OBJETO A CONTRATAR		
echo "<pre>";
var_dump ($resultadoProveedor);
echo "</pre>"; exit;	
if( $resultadoProveedor[0][4]!=0 && $resultadoProveedor[0][4] != '' ){
//CONSULTAR EVALUACIONES DEL PROVEEDOR
$cadena_sql = $this->sql->getCadenaSql ( "consultarProveedor", $_REQUEST ['nit_proveedor'] );
$resultadoProveedor = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );	
	
	
	
	
}
	// -----------------Inicio de Conjunto de Controles----------------------------------------
	$esteCampo = "marcoDatosResultadoParametrizar";
	$atributos ["estilo"] = "jqueryui";
// 	$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
	echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
	unset ( $atributos );
	
	echo "<table id='tablaContratos'>";
	
	echo "<thead>
                <tr>
		     <th>Número Contrato</th>
			 <th>Identificación<br>Contratista</th>
             <th>Fecha Contrato</th>
			 <th>Fecha Registro</th>
             <th>Documento</th>
             <th>Modificar</th>
        	 </tr>
            </thead>
            <tbody>";
	
	for($i = 0; $i < count ( $resultadoContratos ); $i ++) {
		
		$variable = "pagina=" . $miPaginaActual; // pendiente la pagina para modificar parametro
		$variable .= "&opcion=modificar";
		$variable .= "&contrato=" . $resultadoContratos [$i] [4];
		$variable .= "&nombre_contrato=" . $resultadoContratos [$i] [0];
		$variable .= "&identificador_contrato=" . $resultadoContratos [$i] [9];
		$variable .= "&usuario=".$_REQUEST['usuario'];
		$variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variable, $directorio );
		
		$mostrarHtml = "<tr>
					
				    <td><center>" . $resultadoContratos [$i] [6] . "</center></td>
                    <td><center>" . $resultadoContratos [$i] [5] . "</center></td>
                    <td><center>" . $resultadoContratos [$i] [7] . "</center></td>
                    <td><center>" . $resultadoContratos [$i] [8] . "</center></td>
                    <td><center><A HREF=\"" . $resultadoContratos [$i] [2] . "\" target=\"_blank\">" . $resultadoContratos [$i] [0] . "</A></center></td>
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
	
	echo $this->miFormulario->agrupacion ( 'fin' );
	unset ( $atributos );
	
	
	// Fin de Conjunto de Controles
	// echo $this->miFormulario->marcoAgrupacion("fin");
} else {
	$nombreFormulario = $esteBloque ["nombre"];
	include_once ("core/crypto/Encriptador.class.php");
	$cripto = Encriptador::singleton ();
	$directorio = $this->miConfigurador->getVariableConfiguracion ( "rutaUrlBloque" ) . "/imagen/";
	
	$miPaginaActual = $this->miConfigurador->getVariableConfiguracion ( "pagina" );
	
	$tab = 1;
	// ---------------Inicio Formulario (<form>)--------------------------------
	$atributos ["id"] = $nombreFormulario;
	$atributos ["tipoFormulario"] = "multipart/form-data";
	$atributos ["metodo"] = "POST";
	$atributos ["nombreFormulario"] = $nombreFormulario;
	$verificarFormulario = "1";
	$atributos ['marco'] = true;
	$atributos ['tipoEtiqueta'] = 'inicio';
	echo $this->miFormulario->formulario ( $atributos );
	
	$atributos ["id"] = "divNoEncontroEgresado";
	$atributos ["estilo"] = "marcoBotones";
	// $atributos["estiloEnLinea"]="display:none";
	echo $this->miFormulario->division ( "inicio", $atributos );
	
	// -------------Control Boton-----------------------
	$esteCampo = "noEncontroProcesos";
	$atributos ["id"] = $esteCampo; // Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
	$atributos ["etiqueta"] = "";
	$atributos ["estilo"] = "centrar";
	$atributos ["tipo"] = 'error';
	$atributos ["mensaje"] = $this->lenguaje->getCadena ( $esteCampo );
	
	echo $this->miFormulario->cuadroMensaje ( $atributos );
	unset ( $atributos );
	
	$valorCodificado = "pagina=" . $miPaginaActual;
	$valorCodificado .= "&bloque=" . $esteBloque ["id_bloque"];
	$valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
	$valorCodificado = $cripto->codificar ( $valorCodificado );
	// -------------Fin Control Boton----------------------
	// ------------------Fin Division para los botones-------------------------
	echo $this->miFormulario->division ( "fin" );
	
	
	echo $this->miFormulario->agrupacion ( 'fin' );
	unset ( $atributos );
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


$atributos ['marco'] = true;
$atributos ['tipoEtiqueta'] = 'fin';
echo $this->miFormulario->formulario ( $atributos );
unset($atributos);


?>