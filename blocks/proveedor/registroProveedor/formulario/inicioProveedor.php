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

$cadena_sql = $this->sql->getCadenaSql ( "buscarProveedorLog", $_REQUEST ['usuario'] );
$resultado = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );

?>

<br><br>
    <div class="container">

      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron" align="center">
        <h2>Sistema de Registro Único de Proveedores!</h2>
        <p>Módulo de Proveedores</p>
        <h3>!Bienvenido Proveedor: <?php echo $resultado[0]['nomempresa'] ?></h3>

      </div>

    </div> <!-- /container -->