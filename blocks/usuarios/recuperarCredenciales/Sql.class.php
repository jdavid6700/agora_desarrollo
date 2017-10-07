<?php

namespace usuarios\recuperarCredenciales;

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
			
			/**
			 * Clausulas específicas
			 */
                        case "idioma":

				$cadenaSql = "SET lc_time_names = 'es_ES' ";
			break;
                    
                        case "consultarUsuarios":
                                
				$cadenaSql = "SELECT usu.id_usuario, ";
                            	$cadenaSql .= "usu.nombre, ";
                            	$cadenaSql .= "usu.apellido, ";
                                $cadenaSql .= " usu.correo, ";
                                $cadenaSql .= " usu.telefono, ";
                                $cadenaSql .= " usu.tipo ,";
                                $cadenaSql .= " (CASE WHEN usu.tipo='0' THEN 'Anonimo' ELSE 'Conocido' END) nivel, ";
                                $cadenaSql .= " est.estado_registro_alias estado, ";
                                $cadenaSql .= " usu.identificacion, ";
                                $cadenaSql .= " usu.tipo_identificacion, ";
                                $cadenaSql .= " tiden.tipo_nombre, ";
                                $cadenaSql .= " usu.fecha_registro, ";
                                $cadenaSql .= " usu.clave  ";
                                $cadenaSql .= "FROM ".$prefijo."usuario usu ";
                                $cadenaSql .= "INNER JOIN ".$prefijo."estado_registro est ";
                                $cadenaSql .= "ON est.estado_registro_id=usu.estado ";
                                $cadenaSql .= "INNER JOIN ".$prefijo."tipo_identificacion tiden ";
                                $cadenaSql .= "ON tiden.tipo_identificacion=usu.tipo_identificacion ";
                                if(isset($variable['id_usuario']) && $variable['id_usuario']!='')
                                    { $cadenaSql .= " WHERE ";
                                      $cadenaSql .= " usu.id_usuario='".$variable['id_usuario']."'"; 
                                    }    
                                $cadenaSql .= " ORDER BY id_usuario";
			break;                       
                    
                    
			case "modificaClave" :
				 $cadenaSql = "UPDATE ";
				 $cadenaSql .= $prefijo."usuario ";
				 $cadenaSql .= "SET ";
				 $cadenaSql .= "clave='".$variable['contrasena']."', ";
				 $cadenaSql .= "estado = 1 ";
				 $cadenaSql .= "WHERE ";
				 $cadenaSql .= "id_usuario = '".$variable['id_usuario']."' ";
				break;
				
				
				case "consultarUsuario" :
					$cadenaSql = "SELECT * FROM ";
					$cadenaSql .= $prefijo."usuario ";
					$cadenaSql .= "WHERE ";
					if(is_numeric($variable)){
						$cadenaSql .= "id_usuario = '".$variable."' OR identificacion = ".$variable;
					}else{
						$cadenaSql .= "id_usuario = '".$variable."'";
					}
					break;
					
					
					case "datosPersonas":
						$cadenaSql="SELECT IP.num_documento, ";
						$cadenaSql.="IP.nom_proveedor, ";
						$cadenaSql.="IP.direccion, ";
						$cadenaSql.="T.numero_tel, ";
						$cadenaSql.="IP.id_entidad_bancaria, ";
						$cadenaSql.="IP.tipo_cuenta_bancaria, ";
						$cadenaSql.="IP.correo, ";
						$cadenaSql.="IP.num_cuenta_bancaria ";
						$cadenaSql.="FROM agora.informacion_proveedor IP ";
						$cadenaSql.="INNER JOIN agora.proveedor_telefono PT ON PT.id_proveedor = IP.id_proveedor ";
						$cadenaSql.="INNER JOIN agora.telefono T ON T.id_telefono = PT.id_telefono ";
                                                
						$cadenaSql.="WHERE ";
						$cadenaSql.="IP.num_documento IN ('".$variable['identificacion']."') ";
                                                if($variable['tipo_identificacion']=='NIT')
                                                     { $cadenaSql .= " AND tipopersona <> 'NATURAL'"; }
                                                else { $cadenaSql .= " AND tipopersona = 'NATURAL'"; }    
                                                
                                                $cadenaSql.=" LIMIT 1";
						break;
						
						
						case "datosAleatorios":
							$cadenaSql="SELECT * FROM ";
							$cadenaSql.="( SELECT * FROM mntac.acdocente ";
							$cadenaSql.="ORDER BY dbms_random.value ) ";
							$cadenaSql.="WHERE rownum <= 1 ";
							$cadenaSql.="AND ";
							$cadenaSql.="doc_estado='A'";
                            			
			case "rescatarValorSesion" :
				 $cadenaSql = "SELECT sesionid, variable, valor, expiracion FROM ".$prefijo."valor_sesion";
				break;
			
				/**
				 * Clausulas genéricas. se espera que estén en todos los formularios
				 * que utilicen esta plantilla
				 */

			case "iniciarTransaccion":
				$cadenaSql="START TRANSACTION";
				break;

			case "finalizarTransaccion":
				$cadenaSql="COMMIT";
				break;

			case "cancelarTransaccion":
				$cadenaSql="ROLLBACK";
				break;

		}
		return $cadenaSql;
	}
}

?>
