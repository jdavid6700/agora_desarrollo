<?php
$rutaPrincipal = $this->miConfigurador->getVariableConfiguracion ( 'host' ) . $this->miConfigurador->getVariableConfiguracion ( 'site' );
$indice = $rutaPrincipal . "/index.php?";
$directorio = $rutaPrincipal . '/' . $this->miConfigurador->getVariableConfiguracion ( 'bloques' ) . "/menu_principal/imagen/";

$urlBloque = $this->miConfigurador->getVariableConfiguracion ( 'rutaUrlBloque' );


?>
<br><br>
    <div class="container">

      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron" align="center">
        <h2>¡Bienvenido al Registro Único de Proveedores!</h2>
        <p>Módulo de Administración.</p>

      </div>

    </div> <!-- /container -->
		