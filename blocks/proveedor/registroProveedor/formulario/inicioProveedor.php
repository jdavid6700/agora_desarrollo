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



$cadenaSql = $this->sql->getCadenaSql ( 'consultar_proveedor', $_REQUEST ["usuario"] );
$resultadoDoc = $frameworkRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );

$cadena_sql = $this->sql->getCadenaSql ( "buscarProveedorLog", $resultadoDoc[0][0] );
$resultado = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );




setlocale(LC_ALL, "es_ES");
$fecha = strftime("%A %d de %B del %Y");
$fechaHoy = utf8_encode(ucwords($fecha));
?>

<br><br>
    <div class="container">
     <div align="right">
     	<p><?php echo $fechaHoy ?></p>
     	<div id="bannerReloj">
     	
     	</div>
     </div>
		<br>
      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron" align="center">
        <h2>Sistema de Registro Único de Proveedores!</h2>
        <p>Módulo de Proveedores</p>
        <h3><b>Bienvenido Proveedor:</b> <i><?php echo $resultado[0]['nom_proveedor'] ?></i></h3>
        

      </div>

    </div> <!-- /container -->