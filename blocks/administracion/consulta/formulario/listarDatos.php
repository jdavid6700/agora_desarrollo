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
	function __construct($lenguaje, $formulario, $sql) {
		$this->miConfigurador = \Configurador::singleton ();
		
		$this->miConfigurador->fabricaConexiones->setRecursoDB ( 'principal' );
		
		$this->lenguaje = $lenguaje;
		
		$this->miFormulario = $formulario;
		
		$this->miSql = $sql;
	}
	function miLista() {
		
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
		$variable['cedula'] = $_REQUEST['cedula'];
        $this->cadena_sql = $this->miSql->getCadenaSql("buscarProveedor", $variable);
        $resultadoPerfil = $esteRecursoDB->ejecutarAcceso($this->cadena_sql, "busqueda");
//var_dump($resultadoPerfil); die();					
?>
<table class="table table-bordered table-striped table-hover table-condensed">
	<tr >

                <td align="center">Cédula</td>	
                <td align="center">Nombre</td>
                <td align="center">Empresa</td>
                <td align="center">Correo</td>
                <td align="center">Teléfono</td>
		<td align="center">editar</td>
	</tr>	
<?php for($contador = 0; $contador < count ( $resultadoPerfil ); $contador ++) { ?>
        <tr bgcolor="#ECECEC">
		<td><?php echo $resultadoPerfil[$contador][1] ?></td>
		<td><?php echo $resultadoPerfil[$contador][16] ?></td>
                <td><?php echo $resultadoPerfil[$contador][2] ?></td>
		<td><?php echo $resultadoPerfil[$contador][6] ?></td>
		<td><?php echo $resultadoPerfil[$contador][8] ?></td>	
                    
                    
                <?php echo "<td class='text-center'>";
			$enlace = 'inicio/cliente/';
			/*if( $lista['CL_estado']==1){
				$valor = 'Activo';
				$clase = "btn btn-success";
			}else{
				$valor = 'Inactivo';
				$clase = "btn btn-danger";
			}*/
				$valor = 'Inactivo';
				$clase = "btn btn-danger";			
			echo '<a class="' . $clase . '" href="' . $enlace . '">' .  $valor . ' <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>';
			 
			echo "</td>";		?>
	</tr>	
                    <?php
				}
				?>
                                </table>

<?php

	}
}

$miSeleccionador = new listarDatos ( $this->lenguaje, $this->miFormulario, $this->sql );

$miSeleccionador->miLista ();
?>
