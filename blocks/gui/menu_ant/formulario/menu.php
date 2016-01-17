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


		$directorio = $this->miConfigurador->getVariableConfiguracion ( "host" );
		$directorio .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/index.php?";
		$directorio .= $this->miConfigurador->getVariableConfiguracion ( "enlace" );
		
		$url = $miConfigurador->configuracion ["host"] . $miConfigurador->configuracion ["site"] . "/index.php?";
		$url .= $miConfigurador->configuracion ['enlace'];
		
		//PROVEEDOR
		$pagina = 'pagina=consultaProveedor';
		$pagina = $this->miConfigurador->fabricaConexiones->crypto->codificar($pagina);
		$url = $directorio . '=' . $pagina;
		echo "<li><a href='" . $url . "'>Consultar Proveedor</a></li>";

		//OBJETO
		$pagina = 'pagina=objeto';
		$pagina = $this->miConfigurador->fabricaConexiones->crypto->codificar($pagina);
		$url = $directorio . '=' . $pagina;
		echo "<li><a href='" . $url . "'>Objeto a contratar</a></li>";
		
		//CONTRATO
		$pagina = 'pagina=contrato';
		$pagina = $this->miConfigurador->fabricaConexiones->crypto->codificar($pagina);
		$url = $directorio . '=' . $pagina;
		echo "<li><a href='" . $url . "'>Contrato</a></li>";

		//EVALUACION
		$pagina = 'pagina=evaluacion';
		$pagina = $this->miConfigurador->fabricaConexiones->crypto->codificar($pagina);
		$url = $directorio . '=' . $pagina;
		echo "<li><a href='" . $url . "'>Evaluación</a></li>";
		
		//INDICADORES
		$pagina = 'pagina=indicadores';
		$pagina = $this->miConfigurador->fabricaConexiones->crypto->codificar($pagina);
		$url = $directorio . '=' . $pagina;
		echo "<li><a href='" . $url . "'>Indicadores</a></li>";
		
		//PARAMETRICAS
		$pagina = 'pagina=parametricas';
		$pagina = $this->miConfigurador->fabricaConexiones->crypto->codificar($pagina);
		$url = $directorio . '=' . $pagina;
		echo "<li><a href='" . $url . "'>Parametricas</a></li>";

		//OTROS
		$pagina = 'pagina=desenlace';
		$pagina = $this->miConfigurador->fabricaConexiones->crypto->codificar($pagina);
		$url = $directorio . '=' . $pagina;
		echo "<li><a href='" . $url . "'>Desenlace</a></li>";

?>			
          </ul>		
        </div><!--/.navbar-collapse -->
      </div>
    </nav>


