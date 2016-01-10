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
		$esteCampo = "marcoDatos";
		$atributos ['id'] = $esteCampo;
		$atributos ["estilo"] = "jqueryui";
		$atributos ['tipoEtiqueta'] = 'inicio';
		$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
		echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );	
		unset ( $atributos );		
}

		unset($resultado);
		//CONSULTAR
		$annnoActual = date ( 'Y' );
		$filtroVigencita = $annnoActual - 3;//Se filtra por los ultimos 3 años
		
		$cadenaSql = $this->sql->getCadenaSql ( 'listaContato', $filtroVigencita );
		$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );	

		if ($resultado) {
				echo "<table id='tablaContratos'>";
				echo "<thead>
						<tr>
							<th>NIT</th>
							<th>Proveedor</th>
							<th>No. Contrato</th>
							<th>Fecha Inicial</th>
							<th>Fecha Final</th>
							<th>Fecha Registro <br>Contrato</th>
							<th>Supervisor</th>
							<th>Puntaje Total</th>
							<th>Clasificaciòn</th>
							<th>Estado</th>
						</tr>
						</thead>
						<tbody>";
	
				foreach ($resultado as $dato):
					$estado = $dato['estado']==1?"Nuevo":"Evaluado";
					
					$mostrarHtml = "<tr>
								<td><right>" . $dato['nit'] . "</center></td>
								<td><center>" . $dato['nombre_proveedor'] . "</center></td>
								<td><center>" . $dato['numero_contrato'] . "</center></td>
								<td><center>" . $dato['fecha_inicio'] . "</center></td>
								<td><center>" . $dato['fecha_finalizacion'] . "</center></td>
								<td><center>" . $dato['fecha_registro'] . "</center></td>
								<td><right>" . $dato['nombre_supervisor'] . "</center></td>
								<td><right>" . $dato['puntaje_total'] . "</center></td>
								<td><center>" . $dato['clasificacion'] . "</center></td>
								<td><center>" . $estado . "</center></td>
							</tr>";
					echo $mostrarHtml;
					unset ( $mostrarHtml );
					unset ( $variable );
				endforeach; 
				
				echo "</tbody>";
				echo "</table>";
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




