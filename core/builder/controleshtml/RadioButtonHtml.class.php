<?php
require_once ("core/builder/HtmlBase.class.php");

class RadioButtonHtml extends HtmlBase{
    
    function campoBotonRadial($atributos) {
        
        $this->setAtributos ( $atributos );
        $this->campoSeguro();
    
        if (isset ( $this->atributos [self::ESTILO] ) && $this->atributos [self::ESTILO] != "") {
            $this->cadenaHTML = "<div class='" . $this->atributos [self::ESTILO] . "'>\n";
        } else {
            $this->cadenaHTML = "<div class='campoBotonRadial'>\n";
        }
    
        
        //Se requiere agregar estilos en el bloque que se utilice el RadioInput (Completely CSS: Custom checkboxes, radio buttons and select boxes KENAN YUSUF)
        //****************************************************************************
        //****************************************************************************
        
        
        if (isset ( $this->atributos [self::ETIQUETA] ) && $this->atributos [self::ETIQUETA] != "") {
            
        	$this->cadenaHTML .= '<label class="control control--radio">'.$this->atributos [self::ETIQUETA];
            $this->cadenaHTML .= $this->radioButton ();
            $this->cadenaHTML .= '<div class="control__indicator"></div>';
            $this->cadenaHTML .= '</label>';
            
        }else{
        	
        	$this->cadenaHTML .= '<label class="control control--radio">';
        	$this->cadenaHTML .= $this->radioButton ();
        	$this->cadenaHTML .= '<div class="control__indicator"></div>';
        	$this->cadenaHTML .= '</label>';
        	
        }
        
       //**************************************************************************** 
       //****************************************************************************
    
        $this->cadenaHTML .= "\n</div>\n";
        return $this->cadenaHTML;
    
    }
    
    function radioButton() {
    
        $this->setAtributos ( $this->atributos );
        $this->miOpcion = "";
        $nombre = $this->atributos [self::ID];
        $id = "campo" . rand ();
    
        if (isset ( $this->atributos ["opciones"] )) {
            $opciones = explode ( "|", $this->atributos ["opciones"] );
    
            if (is_array ( $opciones )) {
    
                $this->miOpcion .= $this->opcionesRadioButton ( $opciones );
            }
        } else {
    
            $this->miOpcion .= "<input type='radio' ";
            //$this->miOpcion .= self::HTMLNAME . "'" . $id . "' ";
            $this->miOpcion .= "id='" . $id . "' ";
            $this->miOpcion .= self::HTMLNAME . "'" . $nombre . "' ";
    
            $this->miOpcion .= self::HTMLVALUE . "'" . $this->atributos [self::VALOR] . "' ";
    
            if (isset ( $this->atributos [self::TABINDEX] )) {
                $this->miOpcion .= self::HTMLTABINDEX . "'" . $this->atributos [self::TABINDEX] . "' ";
            }
    
            if (isset ( $this->atributos [self::SELECCIONADO] ) && $this->atributos [self::SELECCIONADO]) {
                $this->miOpcion .= "checked='true' ";
            }
            
            if (isset ( $this->atributos ['validar'] ) && $this->atributos ['validar'] != "") {
            	$this->miOpcion .= "class='validate[" . $this->atributos ['validar'] . "]'";
            }
    
            $this->miOpcion .= "/> ";
            //$this->miOpcion .= self::HTMLLABEL . "'" . $id . "'>";
            //$this->miOpcion .= $this->atributos [self::ETIQUETA];
           //$this->miOpcion .= self::HTMLENDLABEL;
        }
        return $this->miOpcion;
    
    }
    
    function opcionesRadioButton($opciones) {
    
        $cadena = '';
        foreach ( $opciones as $clave => $valor ) {
            $opcion = explode ( "&", $valor );
            if ($opcion [0] != "") {
                if ($opcion [0] != $this->atributos ["seleccion"]) {
                    $cadena .= "<div>";
                    $cadena .= "<input type='radio' id='" . $id . "' " . self::HTMLNAME . "'" . $nombre . "' value='" . $opcion [0] . "' />";
                    $cadena .= self::HTMLLABEL . "'" . $id . "' align=\"center\">";
                    $cadena .= $opcion [1] . "";
                    $cadena .= "</label>";
                    $cadena .= "</div>";
                } else {
                    $cadena .= "<div>";
                    $cadena .= "<input type='radio' id='" . $id . "' " . self::HTMLNAME . "'" . $nombre . "' value='" . $opcion [0] . "' checked /> ";
                    $cadena .= self::HTMLLABEL . "'" . $id . "' align=\"center\">";
                    $cadena .= $opcion [1] . "";
                    $cadena .= "</label>";
                    $cadena .= "</div>";
                }
            }
        }
    
        return $cadena;
    
    }
}