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
	
	const CONTRATOCREADO = 1; //Estado CONTRATO creado
	
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
		
        $this->cadena_sql = $this->miSql->getCadenaSql("listaCrontato", $_REQUEST['usuario']);


        
		$resultado = $esteRecursoDB->ejecutarAcceso($this->cadena_sql, "busqueda");
		
		if( $resultado ){
			?>
				<br>
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<h4 class="list-group-item-heading">
									Seleccione Contrato para Evaluar
								</h4>
							</div>
						</div>
					</div>			
				</div>
				<table class="table table-bordered table-striped table-hover table-condensed">
					<tr class="info">
								<td align="center"><strong>No. Contrato</strong></td>	
								<td align="center"><strong>Fecha Inicial </strong></td>
								<td align="center"><strong>Fecha Final </strong></td>
								<td align="center"><strong>Empresa Proveedor </strong></td>
								<td align="center"><strong>No. Acto Administrativo</strong></td>
								<td align="center"><strong>No. CDP</strong></td>
								<td align="center"><strong>No. RP</strong></td>
								<td align="center"><strong>Valor</strong></td>
								<td align="center"><strong>Evaluar</strong></td>
					</tr>	
				<?php 
					foreach ($resultado as $dato):
			
						echo "<tr>";
						echo "<td align='right'>" . $dato['numero_contrato']. "</td>";
						echo "<td align='center'>" . $dato['fecha_inicio'] . "</td>";
						echo "<td align='center'>" . $dato['fecha_finalizacion'] . "</td>";
						echo "<td align='center'>" . $dato['nom_proveedor'] . "</td>";
						echo "<td align='right'>" . $dato['numero_acto_admin'] . "</td>";
						echo "<td align='right'>" . $dato['numero_cdp'] . "</td>";
						echo "<td align='right'>" . $dato['numero_rp'] . "</td>";
						echo "<td align='right'>$ " . $dato['valor'] . "</td>";				
						echo "<td class='text-center'>";
							$variable = "pagina=" . $miPaginaActual;
							$variable.="&opcion=nuevo";
							$variable .= "&idContrato=".$dato["id_contrato"];
				
							$variable = $this->miConfigurador->fabricaConexiones->crypto->codificar($variable);
							$url = $directorio . '=' . $variable;
			
							if( $dato['estado'] == 'CREADO'){
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
				$esteCampo = "mensajeNoEncontroContrato";
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
