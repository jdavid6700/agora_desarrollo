<?php

namespace registro\loginTitan\funcion;

if (!isset($GLOBALS ["autorizado"])) {
    include ("index.php");
    exit();
}

class Redireccionador {

    public static function redireccionar($opcion, $valor = "") {
        $miConfigurador = \Configurador::singleton();

        $miPaginaActual = $miConfigurador->getVariableConfiguracion("pagina");

        switch ($opcion) {
            case "index" :
                $variable = 'pagina=bienvenida';
                $variable .= '&registro=' . $valor [0];
                break;
				
            case "indexProveedor" :
                $variable = 'pagina=actualizarProveedor';
                $variable .= '&usuario=' . $valor [0];
                break;				

            case "claves" :
                $variable = 'pagina=cambiarClave';
                $variable .= '&usuario=' . $valor [0]['id_usuario'];
                break;

              /** Otros casos */
            case "paginaPrincipal" :
                $variable = "pagina=" . $miPaginaActual;
                if (isset($valor) && $valor != '') {
                    $variable .= "&error=" . $valor;
                }
                break;

            default :
                $variable = 'pagina=' . $miPaginaActual;
                break;
        }
        foreach ($_REQUEST as $clave => $valor) {
            unset($_REQUEST [$clave]);
        }

        $url = $miConfigurador->configuracion ["host"] . $miConfigurador->configuracion ["site"] . "/index.php?";
        $enlace = $miConfigurador->configuracion ['enlace'];
        $variable = $miConfigurador->fabricaConexiones->crypto->codificar($variable);
        $_REQUEST [$enlace] = $enlace . '=' . $variable;
        $redireccion = $url . $_REQUEST [$enlace];

        echo "<script>location.replace('" . $redireccion . "')</script>";
    }

}

?>