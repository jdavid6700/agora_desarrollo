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
		$esteCampo = "marcoDatosSup";
		$atributos ['id'] = $esteCampo;
		$atributos ["estilo"] = "jqueryui";
		$atributos ['tipoEtiqueta'] = 'inicio';
		$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
		echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );	
		unset ( $atributos );		
}



if (isset ( $_REQUEST ['nit_proveedor'] ) && $_REQUEST ['nit_proveedor'] != '') {
	//-------------------------------------------------
	//-------------------------------------------------
	//Validación Petición POST Parametro SQL Injection
	if(isset($_REQUEST ['id_proveedor']) && is_numeric($_REQUEST ['id_proveedor'])){
		settype($_REQUEST ['id_proveedor'], 'integer');
		$secure = true;
            }
        elseif(isset($_REQUEST ['nit_proveedor']) && $_REQUEST ['nit_proveedor']!='')
            {$_REQUEST ['id_proveedor']=$_REQUEST ['nit_proveedor'];
		$secure = true;
            }
        else{
		$secure = false;
            }
	//-------------------------------------------------
	//-------------------------------------------------
	
	if($secure){
		
		if (isset ( $_REQUEST ['nit_proveedor'] ) && $_REQUEST ['nit_proveedor'] != '') {
			$cedula = $_REQUEST ['id_proveedor'];
		} else {
			$cedula = '';
		}
		
		unset($resultado);
		$cadena_sql = $this->sql->getCadenaSql ( "supervisor", $cedula );
		$resultado = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
		
	}else{
		$resultado = false;
	}
}
else{
	$cadena_sql = $this->sql->getCadenaSql ( "supervisor");
	$resultado = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
}



if ($resultado) {
		// -----------------Inicio de Conjunto de Controles----------------------------------------
		$esteCampo = "marcoDatosResultadoParametrizar";
		$atributos ["estilo"] = "jqueryui";
		echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
		unset ( $atributos );
		
		echo '<table id="tablaSupervisores" class="display" cellspacing="0" width="100%"> ';
		
		echo "<thead>
				<tr>
					<th><center>Documento</center></th>
					<th><center>Nombre</center></th>
					<th><center>Correo</center></th>
					<th><center>Dependencia</center></th>
					<th><center>Estado</center></th>
					<th><center>Detalle</center></th>
					<th><center>Modificar</center></th>
				</tr>
				</thead>
				<tbody>";

		foreach ($resultado as $dato):			
			
			$variableView = "pagina=consultaGeneralProveedor"; // pendiente la pagina para modificar parametro
			$variableView .= "&opcion=verPro";
			$variableView .= "&idProveedor=" . $dato['id_proveedor'];
			$variableView = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableView, $directorio );

			$variableEdit = "pagina=" . $miPaginaActual; // pendiente la pagina para modificar parametro
			$variableEdit .= "&opcion=modificarSuper";
			$variableEdit .= "&idProveedor=" . $dato['id_proveedor'];
			$variableEdit = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableEdit, $directorio );
			$imagenEdit = 'editPro.png';

			
			switch ($dato['estado']) {
				case 'ACTIVO':
					$estado = 'ACTIVO';
					$imagenView = 'verPro.png';
					break;
				case 'INACTIVO':
					$estado = 'INACTIVO';
					$imagenView = 'cancel.png';
					$variableView = '#'; 
					break;
				case 'INHABILITADO':
					$estado = 'INHABILITADO';
					$imagenView = 'cancel.png';
					$variableView = '#'; 
					break;
			}
			
			$mostrarHtml = "<tr>
						<td><center>" . $dato['num_documento'] . "</center></td>
						<td><center>" . $dato['nom_proveedor'] . "</center></td>
						<td><center>" . $dato['correo'] . "</center></td>
						<td><center>" . $dato['dependencia'] . "</center></td>
						<td><center>" . $estado . "</center></td>
						<td><center>
							<a href='" . $variableView . "'>                        
								<img src='" . $rutaBloque . "/images/" . $imagenView . "' width='15px'> 
							</a>
						</center></td>
						<td><center>
							<a href='" . $variableEdit . "'>                        
								<img src='" . $rutaBloque . "/images/" . $imagenEdit . "' width='15px'> 
							</a>
						</center></td>
					</tr>";
			echo $mostrarHtml;
			unset ( $mostrarHtml );
			unset ( $variableView );
			unset ( $variableEdit );
		endforeach; 

		echo "</tbody>";
		echo "</table>";
	
	echo $this->miFormulario->agrupacion ( 'fin' );
	unset ( $atributos );
	
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
		$esteCampo = "parametrosInvalidos";
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