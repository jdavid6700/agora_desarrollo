<?php
$rutaPrincipal = $this->miConfigurador->getVariableConfiguracion ( 'host' ) . $this->miConfigurador->getVariableConfiguracion ( 'site' );
$indice = $rutaPrincipal . "/index.php?";
$directorio = $rutaPrincipal . '/' . $this->miConfigurador->getVariableConfiguracion ( 'bloques' ) . "/menu_principal/imagen/";

$urlBloque = $this->miConfigurador->getVariableConfiguracion ( 'rutaUrlBloque' );

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
        <h2>¡Bienvenido al Sistema de Registro Único de Proveedores!</h2>
        <p>Módulo de Administración.</p>

      </div>

    </div> <!-- /container -->
		