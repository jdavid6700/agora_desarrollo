<?php

namespace registro\loginTitan;

if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
}

class Banner {

    var $miConfigurador;
    var $lenguaje;
    var $miFormulario;

    function __construct($lenguaje, $formulario) {
        $this->miConfigurador = \Configurador::singleton();

        $this->miConfigurador->fabricaConexiones->setRecursoDB('principal');

        $this->lenguaje = $lenguaje;

        $this->miFormulario = $formulario;
    }

    function formulario() {
        $directorioImagenes = $this->miConfigurador->getVariableConfiguracion("rutaUrlBloque") . "/imagenes";
        // Rescatar los datos de este bloque
        $esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");

        $rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
        $rutaBloque .= $this->miConfigurador->getVariableConfiguracion("site") . "/blocks/";
        $rutaBloque .= $esteBloque ['grupo'] . "/" . $esteBloque ['nombre'];
		
		$directorio = $this->miConfigurador->getVariableConfiguracion("host");
		$directorio .= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
		$directorio .= $this->miConfigurador->getVariableConfiguracion("enlace");		
  		
     ?>

    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
			<a href='  <?php echo $directorio ?> '>                        
				<img src='<?php echo $rutaBloque . "/imagenes/AGORA.png"; ?>' > 
			</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
		  
			<!--Registro de proveedor-->
			<?php
				
				if(isset($_REQUEST["pagina"] ) && $_REQUEST["pagina"] != 'index'){
					// Fin de la sesión
					$_REQUEST ['tiempo'] = time();
					$enlaceFinSesion ['enlace'] = "action=login";
					$enlaceFinSesion ['enlace'] .= "&pagina=index";
					$enlaceFinSesion ['enlace'] .= "&bloque=login";
					$enlaceFinSesion ['enlace'] .= "&bloqueGrupo=registro";
					$enlaceFinSesion ['enlace'] .= "&opcion=finSesion";
					$enlaceFinSesion ['enlace'] .= "&campoSeguro=" . $_REQUEST ['tiempo'];
					$enlaceFinSesion ['enlace'] .= "&sesion=''";
					$enlaceFinSesion ['enlace'] .= "&usuario=''";
					$urlCodificada = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($enlaceFinSesion ['enlace'], $directorio);
					echo "<li><a href='" . $urlCodificada . "'>Cerrar Sesión</a></li>";					
				}else{
					$enlace = 'pagina=registroProveedor';
					$urlCodificada = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $enlace, $directorio );
					echo "<li><a href='" . $urlCodificada . "'>Registro Proveedor Nuevo</a></li>";
				}
			?>	

          </ul>		
        </div>		
      </div>
    </nav>




        <?php
    }



}

$miFormulario = new Banner($this->lenguaje, $this->miFormulario);
$miFormulario->formulario();

?>