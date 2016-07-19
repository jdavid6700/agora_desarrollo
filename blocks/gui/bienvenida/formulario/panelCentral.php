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
        <?php 
        
        $conexion = "estructura";
        $esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
        
        if (! $esteRecursoDB) {
        
        	// Este se considera un error fatal
        	exit ();
        }
        
        $cadena_sql = $this->sql->cadena_sql ( 'rolUsuario', $_REQUEST['usuario'] );
        
        $registro = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
        
        if($registro[0]['rolmenu'] == '1'){
        ?>
        	<p>Módulo de Administración.</p>
        	<h3><b>Bienvenido Administrador:</b> <i><?php echo $registro[0]['nombre'].' '.$registro[0]['apellido'] ?></i></h3>
        <?php 
        }else{
        ?>
        	<p>Módulo de Supervisor.</p>
        	<h3><b>Bienvenido Supervisor:</b> <i><?php echo $registro[0]['nombre'].' '.$registro[0]['apellido'] ?></i></h3>
        <?php
        }
        ?>
        

      </div>

    </div> <!-- /container -->
		