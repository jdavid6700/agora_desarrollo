<?php

namespace administracion\evaluacionProveedor\formulario\tabs;

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
		//*************************************************************************** DBMS *******************************
		//****************************************************************************************************************
		
		$conexion = 'estructura';
		$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
		
		$conexion = 'sicapital';
		$siCapitalRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
		
		$conexion = 'centralUD';
		$centralUDRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
		
		$conexion = 'argo_contratos';
		$argoRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
		
		$conexion = 'core_central';
		$coreRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
		
		$conexion = 'framework';
		$frameworkRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
		
		//*************************************************************************** DBMS *******************************
		//****************************************************************************************************************
		
		
		$cadenaSql = $this->miSql->getCadenaSql("Roles", $_REQUEST['usuario']);
		$resultadoRol = $frameworkRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
		
		
		$count=0;
		$admin = false;
		while($count < count($resultadoRol)){
			if($resultadoRol[$count]['cod_rol'] == 8){
				$admin = true;
				break;
			}else{
				$admin = false;
			}
			$count++;
		}
		
		
		
		
		if($admin){
			$this->cadena_sql = $this->miSql->getCadenaSql("consultarContratosARGOADM");
			
			//echo ($this->cadena_sql);
			
			$resultado = $argoRecursoDB->ejecutarAcceso($this->cadena_sql, "busqueda");
			
		}else{
			
			//****************************************************************************************
			//****************************************************************************************
			
			
			//************************** CREDENCIALES INTERVENTOR ************************************
			$cadenaSql = $this->miSql->getCadenaSql ( 'consultar_proveedor', $_REQUEST ["usuario"] );
			$resultadoDoc = $frameworkRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
			
			$numeroDocumento = $resultadoDoc[0]['identificacion'];
			
			$cadenaSql = $this->miSql->getCadenaSql ( 'consultar_DatosProveedor', $numeroDocumento );
			$resultadoDats = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
			
			$idProveedor = $resultadoDats[0]['id_proveedor'];
			$tipoPersona = $resultadoDats[0]['tipopersona'];
			$nombrePersona = $resultadoDats[0]['nom_proveedor'];
			$correo = $resultadoDats[0]['correo'];
			$direccion = $resultadoDats[0]['direccion'];
			//****************************************************************************************
			
			
			
			//*************************** CREDENCIALES SUPERVISOR ************************************
				
			//Datos de una Persona FUNCIONARIO SiCapital
			
			
			//$numeroDocumento = FUNCIONARIO
			
			//****************************************************************************************
			
			
			$cadena_sql = $this->miSql->getCadenaSql ( "consultarIdSuperInter", $numeroDocumento );
			$resultadoIds = $argoRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
			
			if($resultadoIds){
				
				
				//***************************** CONSULTAR Contratos **************************************
				
					
				$cadena_sql = $this->miSql->getCadenaSql ( "consultarContratosARGOBySupInt", $resultadoIds[0][0] );
				$resultado = $argoRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
				
				
				//****************************************************************************************
				
				
			}else{
				$resultado = false;
			}
			
			
			
		}

		
		$esteCampo = "marcoContratosTabla";
		$atributos ['id'] = $esteCampo;
		$atributos ["estilo"] = "jqueryui";
		$atributos ['tipoEtiqueta'] = 'inicio';
		$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
		echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
		{
        
		
		if( $resultado ){
			// -----------------Inicio de Conjunto de Controles----------------------------------------
	$esteCampo = "marcoDatosResultadoParametrizar";
	$atributos ["estilo"] = "jqueryui";
	//echo $this->miFormulario->marcoAgrupacion ( "inicio", $atributos );
	unset ( $atributos );
	?>
<br>

<table id="tablaReporteContEval" class="display" cellspacing="0" width="100%">
	<thead>
		<tr>
			<td align="center" width='8%'><strong>No. Contrato</strong></td>
								<td align="center"width='5%'><strong>Vigencia </strong></td>	
								<td align="center" width='10%'><strong>No. Solicitud de Necesidad </strong></td>
								<td align="center"><strong>Fecha Registro </strong></td>
								<td align="center"><strong>Nombre Evaluador</strong></td>
								<td align="center"><strong>Documento Evaluador</strong></td>
								<td align="center"><strong>Tipo Evaluador</strong></td>
								<td align="center"><strong>Sede</strong></td>
								<td align="center" width='10%'><strong>Estado</strong></td>
								<td align="center" width='9%'><strong>Gestión</strong></td>
		</tr>
	</thead>
	<tbody>
                    

			
			
			
<?php

	foreach ( $resultado as $dato ) :
	
						if( $dato['tipo_supervisor'] == 1){
							$tipo = "SUPERVISOR";
						}else{
							$tipo = "INTERVENTOR";
						}
			
						echo "<tr>";
						echo "<td align='center'><b>" . $dato['numero_contrato']. "</b></td>";
						echo "<td align='center'><b>" . $dato['vigencia'] . "</b></td>";
						echo "<td align='center'>" . $dato['numero_solicitud_necesidad'] . "</td>";
						echo "<td align='center'>" . $dato['fecha_registro'] . "</td>";
						echo "<td align='center'>" . $dato['nombre_supervisor'] . "</td>";
						echo "<td align='center'>" . $dato['documento_supervisor'] . "</td>";
						echo "<td align='center'>" . $tipo . "</td>";
						echo "<td align='center'>" . $dato['sede_supervisor'] . "</td>";
						echo "<td align='center'>" . $dato['estado'] . "</td>";
						echo "<td class='text-center'>";
							$variable = "pagina=" . $miPaginaActual;
							$variable.="&opcion=nuevo";
							$variable .= "&numeroContrato=".$dato["numero_contrato"];
							$variable .= "&vigenciaContrato=".$dato["vigencia"];
				
							$variable = $this->miConfigurador->fabricaConexiones->crypto->codificar($variable);
							$url = $directorio . '=' . $variable;
						if($admin){
							
							if( $dato['estado'] == 'CREADO'){
								$valor = 'Sin Evaluar';
								$clase = "btn btn-default";
							}else{
								$valor = 'Evaluado';
								$clase = "btn btn-danger";
							}
							echo $valor;
							
						}else{
							if( $dato['estado'] == 'CREADO'){
								$valor = 'Seleccionar';
								$clase = "btn btn-default";
							}else{
								$valor = 'Inactivo';
								$clase = "btn btn-danger";
							}
							echo '<a class="' . $clase . '" href="' . $url . '">' .  $valor . ' </span></a>';
						}
						
						echo "</td>";			
						echo "</tr>";
	endforeach
	;

?>
			
			           			</tbody>
</table>

<?php
	
	//echo $this->miFormulario->agrupacion ( 'fin' );
	unset ( $atributos );
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
		echo $this->miFormulario->marcoAgrupacion ( 'fin' );
		
	}
}

$miSeleccionador = new listarDatos ( $this->lenguaje, $this->miFormulario, $this->sql );
$miSeleccionador->miLista ();
?>
