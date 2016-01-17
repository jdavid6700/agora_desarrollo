<?php
// include_once("../Sql.class.php");
$miSql = new Sqlmenu ();
// var_dump($this->miConfigurador);
$esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");

$rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
$rutaBloque .= $this->miConfigurador->getVariableConfiguracion("site") . "/blocks/";
$rutaBloque .= $esteBloque ['grupo'] . "/" . $esteBloque ['nombre'];

$directorio = $this->miConfigurador->getVariableConfiguracion("host");
$directorio .= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
$directorio .= $this->miConfigurador->getVariableConfiguracion("enlace");

$conexion = "estructura";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

$miSesion = Sesion::singleton();

// verifica los roles del usuario en el sistema
$roles = $miSesion->RolesSesion();
$roles_unicos = $miSesion->RolesSesion_unico();

// consulta datos del usuario
$id_usuario = $miSesion->getSesionUsuarioId();

$_REQUEST ['usuario'] = $id_usuario;
$cadena_sql = $miSql->getCadenaSql("datosUsuario", $id_usuario);
$regUser = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");




//CONSULTAR MENU
$cadena_sql = $miSql->getCadenaSql("consultarMenu", $regUser [0] ['tipo']);
$resultado = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");









// ------------------------------- Inicio del Menú-------------------------- //
?>


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

	
	
	
    <!-- Fixed navbar -->
	

    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
							<a href='" . $variable . "'>                        
								<img src='<?php echo $rutaBloque . "/images/AGORA.png"; ?>' > 
							</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
		  
<?php

foreach ($resultado as $dato):

	$enlace = 'pagina=' . $dato['nombre'];
	$url = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $enlace, $directorio );
	
	echo "<li><a href='" . $url . "'>" . utf8_encode($dato['nombre_menu']) . "</a></li>";
	
	
endforeach;

?>			

          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Mi Sesión <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li class="dropdown-header">Usuario: <?php echo $regUser [0] ['nombre']; ?> </li>
                <li><a href="#">Cambiar Contraseña</a></li>
                <li><a href="#">Cerrar Sesión</a></li>
              </ul>
            </li>
          </ul>
          </ul>		
        </div><!--/.navbar-collapse -->
      </div>
    </nav>


