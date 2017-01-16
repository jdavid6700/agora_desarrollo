<?php

namespace hojaDeVida\crearDocente;

if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
}

include_once ("core/manager/Configurador.class.php");

class Frontera {

    var $ruta;
    var $sql;
    var $funcion;
    var $lenguaje;
    var $formulario;
    var $miConfigurador;

    function __construct() {

        $this->miConfigurador = \Configurador::singleton();
    }

    public function setRuta($unaRuta) {
        $this->ruta = $unaRuta;
    }

    public function setLenguaje($lenguaje) {
        $this->lenguaje = $lenguaje;
    }

    public function setFormulario($formulario) {
        $this->formulario = $formulario;
    }

    function frontera() {
        $this->html();
    }

    function setSql($a) {
        $this->sql = $a;
    }

    function setFuncion($funcion) {
        $this->funcion = $funcion;
    }

    function html() {

        include_once("core/builder/FormularioHtml.class.php");

        $this->ruta = $this->miConfigurador->getVariableConfiguracion("rutaBloque");
        $this->miFormulario = new \FormularioHtml();


        if (isset($_REQUEST['opcion'])) {

            switch ($_REQUEST['opcion']) {

                case "mensaje":
                    include_once($this->ruta . "/formulario/mensaje.php");
                    break;
                
                case "consultar":
                    include_once($this->ruta . "/formulario/consultar.php");
                    break;
                    
				case "actividad" :
					
					if ((isset ( $_REQUEST ["terminar"] ) && $_REQUEST ["terminar"] == 'true' && $_REQUEST["sigueNBC"] == null ) || (isset ( $_REQUEST ["estadoSolicitudAct"] ) && $_REQUEST ["estadoSolicitudAct"] == 'CON ACTIVIDADES Y NBC') ) {
						include_once($this->ruta . "/formulario/solicitudCotizacion.php");
					}else if (isset ( $_REQUEST ["terminar"] ) && $_REQUEST ["terminar"] == 'true' && isset ( $_REQUEST ["tipoNecesidad"] ) && $_REQUEST ["tipoNecesidad"] == 'SERVICIO'){
						include_once ($this->ruta . "formulario/tabs/tabNucleoBasico.php");
					}else{
						include_once ($this->ruta . "formulario/tabs/tabActividad.php");
					}
					
					break;
                
                case "nuevo":
                     include_once($this->ruta . "/formulario/formulario.php");
                    break;
          
                case "nuevoRelacionar":
                  	include_once($this->ruta . "/formulario/tabs/tabRegistrar.php");
                   	break;
                    
				case "nuevoRelacionada" ://Reestructuración a Corto Plazo
					include_once ($this->ruta . "/formulario/formulario.php");
					break;
					
				case "nuevoCotizacion" ://Reestructuración a Corto Plazo
					include_once ($this->ruta . "/formulario/tabs/tabCotizacion.php");
					break;
					
                case "cotizacion":
                     include_once($this->ruta . "/formulario/solicitudCotizacion.php");
                    break;					
                
                 case "modificar":
                     include_once($this->ruta . "/formulario/modificar.php");
                    break;
                    
				case "verSolicitud" :
					include_once ($this->ruta . "/formulario/verSolicitud.php");
					break;
				
				case "modificarSolicitud" :
					include_once ($this->ruta . "/formulario/modificarSolicitud.php");
					break;
					
				case "modificarSolicitudRelacionada" :
					include_once ($this->ruta . "/formulario/modificarSolicitud.php");
					break;
					
				case "verSolicitudRelacionada" :
					include_once ($this->ruta . "/formulario/verSolicitudRelacionada.php");
					break;
					
				case "cotizarSolicitud" :
					include_once ($this->ruta . "/formulario/solicitudCotizacion.php");
					break;
					
				case "verCotizacionSolicitud" :
					include_once ($this->ruta . "/formulario/infoCotizacion.php");
					break;
					
				case "resultadoCotizacion" :
					include_once ($this->ruta . "/formulario/resultadoCotizacion.php");
					break;
					
				case "verCotizacionProveedor" :
					include_once ($this->ruta . "/formulario/verCotizacion.php");
					break;
            }
        } else {
            $_REQUEST['opcion'] = "mostrar";
            include_once($this->ruta . "/formulario/formulario.php");
        }
    }

}

?>
