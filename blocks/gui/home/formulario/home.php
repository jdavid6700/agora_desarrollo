<?php
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}

$rutaPrincipal = $this->miConfigurador->getVariableConfiguracion ( 'host' ) . $this->miConfigurador->getVariableConfiguracion ( 'site' );

$indice = $rutaPrincipal . "/index.php?";

$directorio = $rutaPrincipal . '/' . $this->miConfigurador->getVariableConfiguracion ( 'bloques' ) . "/menu_principal/imagen/";

$urlBloque = $this->miConfigurador->getVariableConfiguracion ( 'rutaUrlBloque' );

$enlace= $this->miConfigurador->getVariableConfiguracion ( 'enlace' );


?>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">ECOSIIS - AGORA</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
<?php
		$miConfigurador = \Configurador::singleton();
		
		$objeto = 'pagina=objeto';
						
        $url = $miConfigurador->configuracion ["host"] . $miConfigurador->configuracion ["site"] . "/index.php?";
        $enlace = $miConfigurador->configuracion ['enlace'];
		
        $objeto = $miConfigurador->fabricaConexiones->crypto->codificar($objeto);
		
        $_REQUEST [$enlace] = $enlace . '=' . $objeto;
        $redireccion = $url . $_REQUEST [$enlace];

		echo "<li><a href='" . $redireccion . "'>Objeto a contratar</a></li>";

		
		
		//CONTRATO
		$desenlace = 'pagina=contrato';
		$enlace = $miConfigurador->configuracion ['enlace'];
		$desenlace = $miConfigurador->fabricaConexiones->crypto->codificar($desenlace);
		
        $_REQUEST [$enlace] = $enlace . '=' . $desenlace;
        $redireccion = $url . $_REQUEST [$enlace];		
		
		echo "<li><a href='" . $redireccion . "'>Contrato</a></li>";




		
		$desenlace = 'pagina=desenlace';
		$enlace = $miConfigurador->configuracion ['enlace'];
		$desenlace = $miConfigurador->fabricaConexiones->crypto->codificar($desenlace);
		
        $_REQUEST [$enlace] = $enlace . '=' . $desenlace;
        $redireccion = $url . $_REQUEST [$enlace];		
		
		echo "<li><a href='" . $redireccion . "'>Desenlace</a></li>";
		
		echo "<li><a href='" . $redireccion . "'>Consultar proveedor</a></li>";
?>			
          </ul>		
    <!--      <form class="navbar-form navbar-right">
            <div class="form-group">
              <input type="text" placeholder="Usuario" class="form-control">
            </div>
            <div class="form-group">
              <input type="password" placeholder="Clave" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Ingresar</button>
          </form>  -->
        </div><!--/.navbar-collapse -->
      </div>
    </nav>


<!--
    <div class="container">

      <div class="row">
        <div class="col-md-4">
          <h2>Heading</h2>
          <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
          <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
        </div>
        <div class="col-md-4">
          <h2>Heading</h2>
          <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
          <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
       </div>
        <div class="col-md-4">
          <h2>Heading</h2>
          <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
          <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
        </div>
      </div>


    </div> --><!-- /container -->


