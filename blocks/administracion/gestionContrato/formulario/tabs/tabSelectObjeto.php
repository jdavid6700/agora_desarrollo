<?php
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}
class listarDatos {
	var $miConfigurador;
	var $lenguaje;
	var $miFormulario;
	var $miSql;
	
	const ESTADOOBJETO = 'COTIZACION'; //COTIZACION
	
	function __construct($lenguaje, $formulario, $sql) {
		
		$this->miConfigurador = \Configurador::singleton ();
		$this->miConfigurador->fabricaConexiones->setRecursoDB ( 'principal' );
		$this->lenguaje = $lenguaje;		
		$this->miFormulario = $formulario;
		$this->miSql = $sql;
	}
	function miLista() 
	{	
		// Rescatar los datos de este bloque
		$esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );
		$miPaginaActual = $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
		
		$directorio = $this->miConfigurador->getVariableConfiguracion ( "host" );
		$directorio .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/index.php?";
		$directorio .= $this->miConfigurador->getVariableConfiguracion ( "enlace" );
		
		$rutaBloque = $this->miConfigurador->getVariableConfiguracion ( "host" );
		$rutaBloque .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/";
		$rutaBloque .= $esteBloque ['grupo'] . $esteBloque ['nombre'];
		
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

        $this->cadena_sql = $this->miSql->getCadenaSql("listaObjetoContratar", self::ESTADOOBJETO);
        $resultado = $esteRecursoDB->ejecutarAcceso($this->cadena_sql, "busqueda");
        
        

		if( $resultado ){
			?>
			<br>
			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h4 class="list-group-item-heading">
								Seleccione Objeto a Contratar para asignar Contrato
							</h4>
						</div>
					</div>
				</div>			
			</div>
			<table class="table table-bordered table-striped table-hover table-condensed">
				<tr class="info">
							<td align="center"><strong>Objeto de Necesidad</strong></td>	
							<td align="center"><strong>Vigencia</strong></td>
							<td align="center"><strong>Número de Solicitud de Necesidad</strong></td>
							<td align="center"><strong>Actividad Econòmica</strong></td>
							<td align="center"><strong>Fecha de Relación</strong></td>
							<td align="center"><strong>Unidad</strong></td>
							<td align="center"><strong>Cantidad</strong></td>
							<td align="center"><strong>Asignar Contrato</strong></td>
				</tr>	
			<?php 
				foreach ($resultado as $dato):
		
					echo "<tr>";
					echo "<td >" . $dato['id_objeto']. "</td>";
					echo "<td >" . $dato['vigencia']. "</td>";
					echo "<td >" . $dato['numero_solicitud']. "</td>";
					echo "<td align='center'>" . $dato['codigociiu'] . '-' . $dato['actividad'] . "</td>";
					echo "<td align='center'>" . $dato['fecharegistro'] . "</td>";
					echo "<td align='right'>" . $dato['unidad'] . "</td>";
					echo "<td align='right'>" . $dato['cantidad'] . "</td>";
					echo "<td class='text-center'>";
						$variable = "pagina=" . $miPaginaActual;
						$variable.="&opcion=nuevo";
						$variable .= "&idObjeto=".$dato["id_objeto"];
			
						$variable = $this->miConfigurador->fabricaConexiones->crypto->codificar($variable);
						$url = $directorio . '=' . $variable;
		
						if( $dato['estado'] == 'COTIZACION'){
							$valor = 'Seleccionar';
							$clase = "btn btn-default";
						}else{
							$valor = 'Inactivo';
							$clase = "btn btn-danger";
						}
					echo '<a class="' . $clase . '" href="' . $url . '">' .  $valor . ' </span></a>';
					echo "</td>";			
					echo "</tr>";
				endforeach; 
			?>
			</table>
			<?php
		}else{
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
}

$miSeleccionador = new listarDatos ( $this->lenguaje, $this->miFormulario, $this->sql );
$miSeleccionador->miLista ();
?>
