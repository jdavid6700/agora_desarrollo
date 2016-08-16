<?php
namespace hojaDeVida\crearDocente\formulario;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	
	exit ();
}
class Formulario {
	var $miConfigurador;
	var $lenguaje;
	var $miFormulario;
	var $miSql;
	
	const OBJETOCREADO = 'CREADO'; //Estado objeto creado
	
	function __construct($lenguaje, $formulario, $sql) {
		
		$this->miConfigurador = \Configurador::singleton ();
		$this->miConfigurador->fabricaConexiones->setRecursoDB ( 'principal' );
		$this->lenguaje = $lenguaje;
		$this->miFormulario = $formulario;
		$this->miSql = $sql;		
	}
	
	function formulario() {
		// Rescatar los datos de este bloque
		$esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );
		$miPaginaActual = $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
		
		$directorio = $this->miConfigurador->getVariableConfiguracion ( "host" );
		$directorio .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/index.php?";
		$directorio .= $this->miConfigurador->getVariableConfiguracion ( "enlace" );
		
		$rutaBloque = $this->miConfigurador->getVariableConfiguracion ( "host" );
		$rutaBloque .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/";
		$rutaBloque .= $esteBloque ['grupo'] ."/". $esteBloque ['nombre'];
		
		// ---------------- SECCION: Par�metros Globales del Formulario ----------------------------------
		/**
		 * Atributos que deben ser aplicados a todos los controles de este formulario.
		 * Se utiliza un arreglo
		 * independiente debido a que los atributos individuales se reinician cada vez que se declara un campo.
		 *
		 * Si se utiliza esta t�cnica es necesario realizar un mezcla entre este arreglo y el espec�fico en cada control:
		 * $atributos= array_merge($atributos,$atributosGlobales);
		 */
		$atributosGlobales ['campoSeguro'] = 'true';
		
		// -------------------------------------------------------------------------------------------------
		$conexion = "estructura";
		$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
		
		$conexion = "sicapital";
		$siCapitalRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
		
		$this->cadena_sql = $this->miSql->getCadenaSql ( "listaObjetoContratar", self::OBJETOCREADO );
		$resultado = $esteRecursoDB->ejecutarAcceso ( $this->cadena_sql, "busqueda" );
		
		$this->cadena_sql = $this->miSql->getCadenaSql ( "listarObjetosSinCotizacionXVigencia", 2008 );
		$resultado = $esteRecursoDB->ejecutarAcceso ( $this->cadena_sql, "busqueda" );
		
		
		$datos = array (
				'solicitudes' => $resultado[0][0],
				'vigencia' => 2008
		);
		
		$cadenaSql = $this->miSql->getCadenaSql ( 'listaSolicitudNecesidadXNumSolicitudSinCotizar', $datos );
		$resultado = $siCapitalRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		

		echo "todas las solciitudes relacionadas sin cotización";
		
		
		if ($resultado) {
			?>
				<br>
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<h4 class="list-group-item-heading">Seleccione Objeto a Contratar
									para solicitar cotizaciòn</h4>
							</div>
						</div>
					</div>
				</div>


<?php 

			echo '<table id="tablaObjetosSinCotizacion" class="display" cellspacing="0" width="100%"> ';
				
			echo "<thead>
							<tr>
								<th><center>Número Solicitud</center></th>
								<th><center>Vigencia</center></th>
								<th><center>Dependencia</center></th>
								<th><center>Fecha Solicitud</center></th>
								<th><center>Origen Solicitud</center></th>
								<th><center>Dependencia Destino</center></th>
								<th><center>Justificación</center></th>
			                    <th><center>Objeto</center></th>
								<th><center>Tipo Contratación</center></th>
								<th><center>Plazo Ejecución</center></th>
								<th><center>Estado</center></th>
								<th><center>Detalle</center></th>
								<th><center>Modificar</center></th>
								<th><center>Cotizar</center></th>
							</tr>
							</thead>
							<tbody>";
				
			foreach ($resultado as $dato):
			$variableView = "pagina=" . $miPaginaActual; // pendiente la pagina para modificar parametro
			$variableView .= "&opcion=verSolicitudRelacionada";
			$variableView .= "&idSolicitud=" . $dato['NUM_SOL_ADQ'];
			$variableView .= "&vigencia=" . $dato['VIGENCIA'];
			$variableView = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableView, $directorio );
			$imagenView = 'verPro.png';
			
			
			
			$variableEdit = "pagina=" . $miPaginaActual; // pendiente la pagina para modificar parametro
			$variableEdit .= "&opcion=modificarSolicitudRelacionada";
			$variableEdit .= "&idSolicitud=" . $dato['NUM_SOL_ADQ'];
			$variableEdit .= "&vigencia=" . $dato['VIGENCIA'];
			$variableEdit = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableEdit, $directorio );
			$imagenEdit = 'editPro.png';

			
			
			$variableAdd = "pagina=" . $miPaginaActual; // pendiente la pagina para modificar parametro
			$variableAdd .= "&opcion=cotizarSolicitud";
			$variableAdd .= "&idSolicitud=" . $dato['NUM_SOL_ADQ'];
			$variableAdd .= "&vigencia=" . $dato['VIGENCIA'];
			$variableAdd = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableAdd, $directorio );
			$imagenAdd = 'calPro.png';
			
			
			if(!isset($dato['NUM_SOL_ADQ'])) $dato['NUM_SOL_ADQ'] = " ";
			if(!isset($dato['VIGENCIA'])) $dato['VIGENCIA'] = " ";
			if(!isset($dato['DEPENDENCIA'])) $dato['DEPENDENCIA'] = " ";
			if(!isset($dato['FECHA_SOLICITUD'])) $dato['FECHA_SOLICITUD'] = " ";
			if(!isset($dato['ORIGEN_SOLICITUD'])) $dato['ORIGEN_SOLICITUD'] = " ";
			if(!isset($dato['DEPENDENCIA_DESTINO'])) $dato['DEPENDENCIA_DESTINO'] = " ";
			if(!isset($dato['JUSTIFICACION'])) $dato['JUSTIFICACION'] = " ";
			if(!isset($dato['OBJETO'])) $dato['OBJETO'] = " ";
			if(!isset($dato['TIPO_CONTRATACION'])) $dato['TIPO_CONTRATACION'] = " ";
			if(!isset($dato['PLAZO_EJECUCION'])) $dato['PLAZO_EJECUCION'] = " ";
			if(!isset($dato['ESTADO'])) $dato['ESTADO'] = " ";
			
			$mostrarHtml = "<tr>
									<td><center>" . $dato['NUM_SOL_ADQ'] . "</center></td>
									<td><center>" . $dato['VIGENCIA'] . "</center></td>
									<td><center>" . $dato['DEPENDENCIA'] . "</center></td>
									<td><center>" . $dato['FECHA_SOLICITUD'] . "</center></td>
									<td><center>" . $dato['ORIGEN_SOLICITUD'] . "</center></td>
								    <td><center>" . $dato['DEPENDENCIA_DESTINO'] . "</center></td>
									<td><center>" . $dato['JUSTIFICACION'] . "</center></td>
									<td><center>" . $dato['OBJETO'] . "</center></td>
									<td><center>" . $dato['TIPO_CONTRATACION'] . "</center></td>
									<td><center>" . $dato['PLAZO_EJECUCION'] . "</center></td>
									<td><center>" . "RELACIONADO"/*$dato['ESTADO']*/ . "</center></td>
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
									<td><center>
										<a href='" . $variableAdd . "'>
											<img src='" . $rutaBloque . "/images/" . $imagenAdd . "' width='15px'>
										</a>
									</center></td>	
								</tr>";
			echo $mostrarHtml;
			unset ( $mostrarHtml );
			unset ( $variableView );
			unset ( $variableEdit );
			unset ( $variableAdd );
			endforeach;
				
			echo "</tbody>";
			echo "</table>";

?>



<?php
		} else {
			// ------------------INICIO Division para los botones-------------------------
			$atributos ["id"] = "divNoEncontroEgresado";
			$atributos ["estilo"] = "marcoBotones";
			echo $this->miFormulario->division ( "inicio", $atributos );
			// -------------SECCION: Controles del Formulario-----------------------
			$esteCampo = "mensajeObjeto";
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
	}
	function mensaje() {
		
		// Si existe algun tipo de error en el login aparece el siguiente mensaje
		$mensaje = $this->miConfigurador->getVariableConfiguracion ( 'mostrarMensaje' );
		$this->miConfigurador->setVariableConfiguracion ( 'mostrarMensaje', null );
		
		if ($mensaje) {
			
			$tipoMensaje = $this->miConfigurador->getVariableConfiguracion ( 'tipoMensaje' );
			
			if ($tipoMensaje == 'json') {
				
				$atributos ['mensaje'] = $mensaje;
				$atributos ['json'] = true;
			} else {
				$atributos ['mensaje'] = $this->lenguaje->getCadena ( $mensaje );
			}
			// -------------Control texto-----------------------
			$esteCampo = 'divMensaje';
			$atributos ['id'] = $esteCampo;
			$atributos ["tamanno"] = '';
			$atributos ["estilo"] = 'information';
			$atributos ["etiqueta"] = '';
			$atributos ["columnas"] = ''; // El control ocupa 47% del tamaño del formulario
			echo $this->miFormulario->campoMensaje ( $atributos );
			unset ( $atributos );
		}
		
		return true;
	}
}


$miFormulario = new Formulario ( $this->lenguaje, $this->miFormulario, $this->sql  );

$miFormulario->formulario ();
$miFormulario->mensaje ();
?>