<?php

namespace gestionParametros\gestionarSupervisor;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}

include_once ("core/manager/Configurador.class.php");
include_once ("core/connection/Sql.class.php");

// Para evitar redefiniciones de clases el nombre de la clase del archivo sqle debe corresponder al nombre del bloque
// en camel case precedida por la palabra sql
class Sql extends \Sql {
	var $miConfigurador;
	function __construct() {
		$this->miConfigurador = \Configurador::singleton ();
	}
	function getCadenaSql($tipo, $variable = "") {

		/**
		 * 1.
		 * Revisar las variables para evitar SQL Injection
		 */
		$prefijo = $this->miConfigurador->getVariableConfiguracion ( "prefijo" );
		$idSesion = $this->miConfigurador->getVariableConfiguracion ( "id_sesion" );
		
		switch ($tipo) {
		    
		    /* VERIFICAR NUMERO DE DOCUMENTO Y TIPO PERSONA */
		    
					
				///////////////////////////////////

			case "dependenciaUdistritalSupervisor" :
		            $cadenaSql = "  SELECT DISTINCT D.id, UPPER(D.nombre) as nombre ";
		            $cadenaSql .= " FROM oikos.dependencia D ";
		            $cadenaSql .= " LEFT JOIN core.jefe_dependencia JD ON JD.dependencia_id = D.id ";
		            $cadenaSql .= " WHERE D.id NOT IN (1,2,3) AND D.id NOT IN ( ";
		            $cadenaSql .= "  SELECT dependencia_id FROM core.ordenador_gasto ";
    	            	    $cadenaSql .= ") ORDER BY nombre;";
		        break;

		        case "dependenciaUdistritalOrdenador" :
		            $cadenaSql = "  SELECT DISTINCT D.id, UPPER(D.nombre) as nombre ";
		            $cadenaSql .= " FROM oikos.dependencia D ";
		            $cadenaSql .= " JOIN core.jefe_dependencia JD ON JD.dependencia_id = D.id ";
		            $cadenaSql .= " WHERE D.id IN ( ";
		            $cadenaSql .= "  SELECT dependencia_id FROM core.ordenador_gasto ";
    	            $cadenaSql .= ") ORDER BY nombre;";
		        break;

		        case "obtenerInformacionElaborador" :

                $cadenaSql = " 	SELECT nombre , apellido, identificacion  ";
                $cadenaSql .= " FROM prov_usuario  ";
                $cadenaSql .= " WHERE id_usuario = '$variable'; ";

                break;

                case "obtenerInfoUsuario" :
                $cadenaSql = "SELECT  dependencia as nombre ";
                $cadenaSql .= "FROM prov_usuario  ";
                $cadenaSql .= "WHERE id_usuario='" . $variable . "' ";
                break;

                case "consultarFuncionarioGeneralSupervisor" :

                $cadenaSql = " SELECT JD.id, JD.fecha_inicio, JD.fecha_fin, JD.tercero_id, IP.nom_proveedor nombre_funcionario, JD.dependencia_id, D.nombre nombre_dependencia, JD.acta_aprobacion  ";
                $cadenaSql .= " FROM  oikos.dependencia D   ";
                $cadenaSql .= " JOIN core.jefe_dependencia JD ON JD.dependencia_id = D.id  ";
                $cadenaSql .= " JOIN agora.informacion_proveedor IP ON IP.num_documento= JD.tercero_id::character varying  ";
                if ($variable != '') {
                    $cadenaSql .= " WHERE dependencia_id=" .$variable;
                }
                $cadenaSql .= " AND  D.id NOT IN ( ";
		        $cadenaSql .= "  SELECT dependencia_id FROM core.ordenador_gasto ";
    	        $cadenaSql .= ")";
                $cadenaSql .= " ORDER BY JD.fecha_fin DESC;";

                break;

                case "consultarFuncionarioGeneralOrdenador" :

                $cadenaSql = " SELECT JD.id, JD.fecha_inicio, JD.fecha_fin, JD.tercero_id, IP.nom_proveedor nombre_funcionario, JD.dependencia_id, D.nombre nombre_dependencia, JD.acta_aprobacion  ";
                $cadenaSql .= " FROM  oikos.dependencia D   ";
                $cadenaSql .= " JOIN core.jefe_dependencia JD ON JD.dependencia_id = D.id  ";
                $cadenaSql .= " JOIN agora.informacion_proveedor IP ON IP.num_documento= JD.tercero_id::character varying  ";
                if ($variable != '') {
                    $cadenaSql .= " WHERE dependencia_id=" .$variable;
                }
                $cadenaSql .= " AND  D.id IN ( ";
		        $cadenaSql .= "  SELECT dependencia_id FROM core.ordenador_gasto ";
    	        $cadenaSql .= ")";
                $cadenaSql .= " ORDER BY JD.fecha_fin DESC;";

                break;


                case "consultarMayorId" :

                $cadenaSql = "SELECT max(id)  ";
                $cadenaSql .= "FROM core.jefe_dependencia ";
                $cadenaSql .= "WHERE 1=1; ";
                break;

                case "consultarDependenciaOikos" :

                $cadenaSql = "SELECT nombre  ";
                $cadenaSql .= "FROM oikos.dependencia ";
                $cadenaSql .= "WHERE id=".$variable.";";
                break;


    
    			case "dependenciasConsultadasAll" :
                $cadenaSql = " SELECT id, nombre ";
                $cadenaSql .= " FROM oikos.dependencia ";
                $cadenaSql .= " ORDER BY  nombre ;";
                break;

    			case "consultarNombreDependencia" :

                $cadenaSql = " SELECT nombre as nombre_dependencia ";
                $cadenaSql .= " FROM  oikos.dependencia ";
                $cadenaSql .= " WHERE id=" .$variable .";";
                break;
                

                case "buscarProveedoresFiltro" :
				$cadenaSql = " SELECT DISTINCT num_documento||' - ('||nom_proveedor||')' AS  value, num_documento AS data  ";
				$cadenaSql .= " FROM agora.informacion_proveedor ";
				$cadenaSql .= " WHERE cast(num_documento as text) LIKE '%$variable%' OR nom_proveedor LIKE '%$variable%' LIMIT 10; ";
				break;


                case "registrarFuncionarioCore" :
                $cadenaSql = " INSERT INTO core.jefe_dependencia (fecha_inicio, fecha_fin, tercero_id, dependencia_id,acta_aprobacion)  ";
                $cadenaSql .= " VALUES (";
                $cadenaSql .= "'" . $variable['fecha_inicio'] . "', ";
                $cadenaSql .= "'" . $variable['fecha_fin'] . "', ";
                $cadenaSql .= $variable['tercero'] . ", ";
                $cadenaSql .= $variable['dependencia'] . ", ";
                 $cadenaSql .= "'" . $variable['acta'] . "')";
                break;

                case "actualizarFuncionarioCore" :
                $cadenaSql = " UPDATE core.jefe_dependencia   ";
                $cadenaSql .= " SET ";
                $cadenaSql .= " fecha_fin= '".$variable['fecha_fin']."',";
                $cadenaSql .= " acta_aprobacion= '".$variable['acta'] . "' ";
                $cadenaSql .= " WHERE id= " . $variable['id'] . ";";
                break;

                
                case "consultarInformacionFuncionario" :
                $cadenaSql = " SELECT fecha_inicio, fecha_fin, tercero_id, dependencia_id,acta_aprobacion  ";
                $cadenaSql .= " FROM  core.jefe_dependencia  ";
                $cadenaSql .= " WHERE id=". $variable;
                break;
                

                case "consultarInformacionTercero" :
                $cadenaSql = " SELECT num_documento||' - ('||nom_proveedor||')' AS  proveedor  ";
                $cadenaSql .= " FROM agora.informacion_proveedor ";
                $cadenaSql .= " WHERE num_documento='". $variable ."';";
                break;
		
                
		        

		}
		
		return $cadenaSql;
	}
}

?>
