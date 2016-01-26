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
$cadena_sql = $miSql->getCadenaSql("consultarMenu", $regUser [0] ['rolmenu']);
$resultado = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");


// Fin de la sesión
$_REQUEST ['tiempo'] = time();
$enlaceFinSesion ['enlace'] = "action=login";
$enlaceFinSesion ['enlace'] .= "&pagina=index";
$enlaceFinSesion ['enlace'] .= "&bloque=login";
$enlaceFinSesion ['enlace'] .= "&bloqueGrupo=registro";
$enlaceFinSesion ['enlace'] .= "&opcion=finSesion";
$enlaceFinSesion ['enlace'] .= "&campoSeguro=" . $_REQUEST ['tiempo'];
$enlaceFinSesion ['enlace'] .= "&sesion=" . $miSesion->getSesionId();
$enlaceFinSesion ['enlace'] .= "&usuario=" . $id_usuario;
$enlaceFinSesion ['urlCodificada'] = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($enlaceFinSesion ['enlace'], $directorio);
// ------------------------------- Inicio del Menú-------------------------- //
?>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

		
    <!-- Fixed navbar -->
    <nav class="navbar navbar-default navbar-fixed-top">
     
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
                                if($dato['parametros']!=''){
                                    $enlace .= $dato['parametros'];
                                }
				$url = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $enlace, $directorio );
				echo "<li><a href='" . $url . "'>" . $dato['nombre_menu'] . "</a></li>";
				
			endforeach;
			
			?>	
			<!--Cerrar Sesión-->
			<li><a href='<?php echo $enlaceFinSesion ['urlCodificada']; ?>'>Cerrar Sesión</a></li>
          </ul>		
        </div>
    
    </nav>