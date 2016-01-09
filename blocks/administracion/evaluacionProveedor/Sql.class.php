<?php

namespace inventarios\gestionContrato;

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

			/* CONSULTAR - PROVEEDOR POR NIT */
				case "consultarProveedor" :
					$cadenaSql = "SELECT";
					$cadenaSql .= " id_proveedor,";
					$cadenaSql .= " nit,";
					$cadenaSql .= "	nombre_proveedor,";
					$cadenaSql .= "	puntaje_evaluacion,";
					$cadenaSql .= "	clasificacion_evaluacion";
					$cadenaSql .= " FROM ";
					$cadenaSql .= " prov_proveedor";
					$cadenaSql .= " WHERE  NIT=" . $variable;  
					break;
					
			/* CONSULTAR - PROVEEDOR POR ID */
				case "consultarProveedorByID" :
					$cadenaSql = "SELECT";
					$cadenaSql .= " id_proveedor,";
					$cadenaSql .= " nit,";
					$cadenaSql .= "	nombre_proveedor,";
					$cadenaSql .= "	puntaje_evaluacion,";
					$cadenaSql .= "	clasificacion_evaluacion";
					$cadenaSql .= " FROM ";
					$cadenaSql .= " prov_proveedor";
					$cadenaSql .= " WHERE  id_proveedor=" . $variable;  
					break;
					
			/* CONSULTAR - CONTRATO - POR ID */
				case "contratoByID" :
					$cadenaSql = "SELECT";
					$cadenaSql .= " numero_contrato,";
					$cadenaSql .= "	fecha_inicio,";
					$cadenaSql .= "	fecha_finalizacion,";
					$cadenaSql .= " nombre_proveedor,";
					$cadenaSql .= " C.id_proveedor";
					$cadenaSql .= " FROM ";
					$cadenaSql .= " prov_contrato C";
					$cadenaSql .= " JOIN prov_proveedor prov ON prov.id_proveedor = C.id_proveedor ";
					$cadenaSql .= " WHERE  id_contrato=" . $variable;  //Activo
					break;

			/* CONSULTAR - CONTRATO - NO. CONTRATO */
				case "contratoByNumero" :
					$cadenaSql = "SELECT";
					$cadenaSql .= " id_contrato,";
					$cadenaSql .= " numero_contrato,";
					$cadenaSql .= "	fecha_inicio,";
					$cadenaSql .= "	fecha_finalizacion,";
					$cadenaSql .= " nombre_proveedor,";
					$cadenaSql .= " C.estado";
					$cadenaSql .= " FROM ";
					$cadenaSql .= " prov_contrato C";
					$cadenaSql .= " JOIN prov_proveedor prov ON prov.id_proveedor = C.id_proveedor ";
					$cadenaSql .= " WHERE 1=1 ";
					if ($variable [0] != '') {
						$cadenaSql .= " AND  numero_contrato= '" . $variable [0] . "'";
					}
					break;

			/* CONSULTAR - EVALUACION - ID CONTRATO */
				case "evalaucionByIdContrato" :
					$cadenaSql = "SELECT *";
					$cadenaSql .= " FROM ";
					$cadenaSql .= " prov_evaluacion C";
					$cadenaSql .= " WHERE id_contrato= '" . $variable . "'";
					break;
					
			/* LISTAR - CONTRATO */					
				case "listaContato" :			
					$cadenaSql = "SELECT  ";
					$cadenaSql .= " id_contrato, ";
					$cadenaSql .= " numero_contrato, ";
					$cadenaSql .= " fecha_inicio, ";
					$cadenaSql .= " fecha_finalizacion, ";
					$cadenaSql .= " sup.nombre_supervisor, ";
					$cadenaSql .= " prov.nombre_proveedor, ";
					$cadenaSql .= " numero_acto_admin, ";
					$cadenaSql .= " tipo_acto_admin, ";
					$cadenaSql .= " numero_cdp, ";
					$cadenaSql .= " numero_rp, ";
					$cadenaSql .= " fecha_registro, ";
					$cadenaSql .= " valor, ";
					$cadenaSql .= " modalidad, ";
					$cadenaSql .= " C.estado ";
					$cadenaSql .= " FROM ";
					$cadenaSql .= " prov_contrato C";
					$cadenaSql .= " JOIN param_supervisor sup ON sup.id_supervisor = C.id_supervisor ";
					$cadenaSql .= " JOIN prov_proveedor prov ON prov.id_proveedor = C.id_proveedor ";
					$cadenaSql .= " WHERE 1=1 AND C.estado=1 ";
					break;

			/* GUARDAR - NUEVA EVALUACION */
				case 'registroEvaluacion' :
					$cadenaSql = 'INSERT INTO ';
					$cadenaSql .= 'prov_evaluacion';
					$cadenaSql .= '( ';
					$cadenaSql .= 'id_contrato,';
					$cadenaSql .= 'fecha_registro,';
					$cadenaSql .= 'tiemo_entrega,';
					$cadenaSql .= 'cantidades,';
					$cadenaSql .= 'conformidad,';
					$cadenaSql .= 'funcionalidad_adicional,';
					$cadenaSql .= 'reclamaciones,';
					$cadenaSql .= 'reclamacion_solucion,';
					$cadenaSql .= 'servicio_venta,';
					$cadenaSql .= 'procedimientos,';
					$cadenaSql .= 'garantia,';
					$cadenaSql .= 'garantia_satisfaccion,';
					$cadenaSql .= 'puntaje_total,';
					$cadenaSql .= 'clasificacion,';
					$cadenaSql .= 'id_usuario'; //FALTA EL ID DEL SUPERVISOR
					$cadenaSql .= ') ';
					$cadenaSql .= 'VALUES ';
					$cadenaSql .= '( ';
					$cadenaSql .= '\'' . $variable [0] . '\', ';
					$cadenaSql .= '\'' . $variable [1] . '\', ';
					$cadenaSql .= '\'' . $variable [2] . '\', ';
					$cadenaSql .= '\'' . $variable [3] . '\', ';
					$cadenaSql .= '\'' . $variable [4] . '\', ';
					$cadenaSql .= '\'' . $variable [5] . '\', ';
					$cadenaSql .= '\'' . $variable [6] . '\', ';
					$cadenaSql .= '\'' . $variable [7] . '\', ';
					$cadenaSql .= '\'' . $variable [8] . '\', ';
					$cadenaSql .= '\'' . $variable [9] . '\', ';
					$cadenaSql .= '\'' . $variable [10] . '\', ';
					$cadenaSql .= '\'' . $variable [11] . '\', ';
					$cadenaSql .= '\'' . $variable [12] . '\', ';
					$cadenaSql .= '\'' . $variable [13] . '\', ';
					$cadenaSql .= '\'' . $variable [14] . '\'';
					$cadenaSql .= ');';
					break;					

			/* ACTUALIZAR - CONTRATO - ESTADO */			
				case 'actualizarContrato' :
					$cadenaSql = "UPDATE prov_contrato SET ";
					$cadenaSql .= "estado='" . $variable ['estado'] . "'";
					$cadenaSql .= " WHERE id_contrato=";
					$cadenaSql .= "'" . $variable ['idContrato'] . "' ";
					break;
					
			/* ACTUALIZAR - PROVEEEDOR PUNTAJE Y CLASIFICACION */			
				case 'actualizarProveedor' :
					$cadenaSql = "UPDATE prov_proveedor SET ";
					$cadenaSql .= "puntaje_evaluacion='" . $variable ['puntajeNuevo'] . "', ";
					$cadenaSql .= "clasificacion_evaluacion='" . $variable ['clasificacion'] . "'";
					$cadenaSql .= " WHERE id_proveedor=";
					$cadenaSql .= "'" . $variable ['idProveedor'] . "' ";
					break;




					
					
					
					
					
					
					
					
					





				

				
				
				
				
				
				
				
				

			/* LISTA - OBJETO A CONTRATAR  */
				case "listaObjetoContratar" :
					$cadenaSql = "SELECT";
					$cadenaSql .= " id_objeto,";
					$cadenaSql .= " objetocontratar,";
					$cadenaSql .= " codigociiu,";
					$cadenaSql .= " fecharegistro,";
					$cadenaSql .= " unidad,";
					$cadenaSql .= " cantidad,";
					$cadenaSql .= "	descripcion,";
					$cadenaSql .= "	estado";
					$cadenaSql .= " FROM ";
					$cadenaSql .= " prov_objeto_contratar";
					$cadenaSql .= " WHERE  estado=" . $variable;  //Activo
					$cadenaSql .= " order by fechaRegistro";
					break;		
		
			/* LISTA - SUPERVISORES */
				case "supervisor" :
					$cadenaSql = "SELECT";
					$cadenaSql .= " id_supervisor,";
					$cadenaSql .= "	nombre_supervisor";
					$cadenaSql .= " FROM ";
					$cadenaSql .= " param_supervisor";
					$cadenaSql .= " WHERE  estado=1";  //Activo
					$cadenaSql .= " order by nombre_supervisor";
					break;
		
	

		
			case "proveedores" :
				$cadenaSql = " SELECT PRO_NIT,PRO_NIT||' - '||PRO_RAZON_SOCIAL AS proveedor ";
				$cadenaSql .= " FROM PROVEEDORES ";
				
				break;
			
			case "consultarContratoParticular" :
				$cadenaSql = "SELECT  ";
				$cadenaSql .= "nombre_contratista, numero_contrato, fecha_contrato,id_documento_soporte,\"PRO_NIT\"||' - ('||\"PRO_RAZON_SOCIAL\"||')' AS  nom_razon  ";
				$cadenaSql .= " FROM contratos cn";
				$cadenaSql .= " JOIN  arka_parametros.arka_proveedor ap ON ap.\"PRO_NIT\"=cn.nombre_contratista ";
				$cadenaSql .= "WHERE  id_contrato='" . $variable . "';";
				
				break;
			
			case "buscar_Proveedores" :
				$cadenaSql = " SELECT \"PRO_NIT\"||' - ('||\"PRO_RAZON_SOCIAL\"||')' AS  value,\"PRO_NIT\"  AS data  ";
				$cadenaSql .= " FROM arka_parametros.arka_proveedor  ";
				$cadenaSql .= "WHERE cast(\"PRO_NIT\" as text) LIKE '%" . $variable . "%' ";
				$cadenaSql .= "OR \"PRO_RAZON_SOCIAL\" LIKE '%" . $variable . "%' LIMIT 10; ";
				
				break;
			
			case 'registroContrato_anterior' :
				$cadenaSql = 'INSERT INTO ';
				$cadenaSql .= 'contratos';
				$cadenaSql .= '( ';
				$cadenaSql .= 'nombre_contratista,';
				$cadenaSql .= 'numero_contrato,';
				$cadenaSql .= 'fecha_contrato,';
				$cadenaSql .= 'id_documento_soporte,';
				$cadenaSql .= 'fecha_registro,';
				$cadenaSql .= 'estado';
				$cadenaSql .= ') ';
				$cadenaSql .= 'VALUES ';
				$cadenaSql .= '( ';
				$cadenaSql .= '\'' . $variable [0] . '\', ';
				$cadenaSql .= '\'' . $variable [1] . '\', ';
				$cadenaSql .= '\'' . $variable [2] . '\', ';
				$cadenaSql .= '\'' . $variable [3] . '\', ';
				$cadenaSql .= '\'' . $variable [4] . '\', ';
				$cadenaSql .= '\'' . $variable [5] . '\'';
				$cadenaSql .= ');';
				break;
			
			case 'registroDocumento' :
				$cadenaSql = 'INSERT INTO ';
				$cadenaSql .= 'arka_inventarios.registro_documento';
				$cadenaSql .= '( ';
				$cadenaSql .= 'documento_nombre,';
				$cadenaSql .= 'documento_idunico,';
				$cadenaSql .= 'documento_fechar,';
				$cadenaSql .= 'documento_ruta,';
				$cadenaSql .= 'documento_estado';
				$cadenaSql .= ') ';
				$cadenaSql .= 'VALUES ';
				$cadenaSql .= '( ';
				$cadenaSql .= '\'' . $variable ['nombre_archivo'] . '\', ';
				$cadenaSql .= '\'' . $variable ['id_unico'] . '\', ';
				$cadenaSql .= '\'' . $variable ['fecha_registro'] . '\', ';
				$cadenaSql .= '\'' . $variable ['ruta'] . '\', ';
				$cadenaSql .= '\'' . $variable ['estado'] . '\'';
				$cadenaSql .= ') RETURNING documento_id;';
				break;
			

			
			case 'actualizarDocumento' :
				$cadenaSql = 'UPDATE arka_inventarios.registro_documento SET ';
				$cadenaSql .= 'documento_nombre=\'' . $variable ['nombre_archivo'] . '\',';
				$cadenaSql .= 'documento_idunico=\'' . $variable ['id_unico'] . '\',';
				$cadenaSql .= 'documento_fechar=\'' . $variable ['fecha_registro'] . '\',';
				$cadenaSql .= 'documento_ruta=\'' . $variable ['ruta'] . '\',';
				$cadenaSql .= 'documento_estado=\'' . $variable ['estado'] . '\'';
				$cadenaSql .= ' WHERE documento_id=';
				$cadenaSql .= '\'' . $variable ['id_doc'] . '\' ';
				break;
			
			case 'actualizarContrato' :
				$cadenaSql = "UPDATE contratos SET ";
				$cadenaSql .= "nombre_contratista='" . $variable [0] . "',";
				$cadenaSql .= "numero_contrato='" . $variable [1] . "',";
				$cadenaSql .= "fecha_contrato='" . $variable [2] . "' ";
				$cadenaSql .= " WHERE id_contrato=";
				$cadenaSql .= "'" . $variable [3] . "' ";
				
				break;
			/**
			 * /**
			 * Clausulas específicas
			 */
			case "buscarUsuario" :
				$cadenaSql = "SELECT ";
				$cadenaSql .= "FECHA_CREACION, ";
				$cadenaSql .= "PRIMER_NOMBRE ";
				$cadenaSql .= "FROM ";
				$cadenaSql .= "USUARIOS ";
				$cadenaSql .= "WHERE ";
				$cadenaSql .= "`PRIMER_NOMBRE` ='" . $variable . "' ";
				break;
			
			case "insertarRegistro" :
				$cadenaSql = "INSERT INTO ";
				$cadenaSql .= $prefijo . "registradoConferencia ";
				$cadenaSql .= "( ";
				$cadenaSql .= "`idRegistrado`, ";
				$cadenaSql .= "`nombre`, ";
				$cadenaSql .= "`apellido`, ";
				$cadenaSql .= "`identificacion`, ";
				$cadenaSql .= "`codigo`, ";
				$cadenaSql .= "`correo`, ";
				$cadenaSql .= "`tipo`, ";
				$cadenaSql .= "`fecha` ";
				$cadenaSql .= ") ";
				$cadenaSql .= "VALUES ";
				$cadenaSql .= "( ";
				$cadenaSql .= "NULL, ";
				$cadenaSql .= "'" . $variable ['nombre'] . "', ";
				$cadenaSql .= "'" . $variable ['apellido'] . "', ";
				$cadenaSql .= "'" . $variable ['identificacion'] . "', ";
				$cadenaSql .= "'" . $variable ['codigo'] . "', ";
				$cadenaSql .= "'" . $variable ['correo'] . "', ";
				$cadenaSql .= "'0', ";
				$cadenaSql .= "'" . time () . "' ";
				$cadenaSql .= ")";
				break;
			
			case "actualizarRegistro" :
				$cadenaSql = "UPDATE ";
				$cadenaSql .= $prefijo . "conductor ";
				$cadenaSql .= "SET ";
				$cadenaSql .= "`nombre` = '" . $variable ["nombre"] . "', ";
				$cadenaSql .= "`apellido` = '" . $variable ["apellido"] . "', ";
				$cadenaSql .= "`identificacion` = '" . $variable ["identificacion"] . "', ";
				$cadenaSql .= "`telefono` = '" . $variable ["telefono"] . "' ";
				$cadenaSql .= "WHERE ";
				$cadenaSql .= "`idConductor` =" . $_REQUEST ["registro"] . " ";
				break;
			
			/**
			 * Clausulas genéricas.
			 * se espera que estén en todos los formularios
			 * que utilicen esta plantilla
			 */
			case "iniciarTransaccion" :
				$cadenaSql = "START TRANSACTION";
				break;
			
			case "finalizarTransaccion" :
				$cadenaSql = "COMMIT";
				break;
			
			case "cancelarTransaccion" :
				$cadenaSql = "ROLLBACK";
				break;
			
			case "eliminarTemp" :
				
				$cadenaSql = "DELETE ";
				$cadenaSql .= "FROM ";
				$cadenaSql .= $prefijo . "tempFormulario ";
				$cadenaSql .= "WHERE ";
				$cadenaSql .= "id_sesion = '" . $variable . "' ";
				break;
			
			case "insertarTemp" :
				$cadenaSql = "INSERT INTO ";
				$cadenaSql .= $prefijo . "tempFormulario ";
				$cadenaSql .= "( ";
				$cadenaSql .= "id_sesion, ";
				$cadenaSql .= "formulario, ";
				$cadenaSql .= "campo, ";
				$cadenaSql .= "valor, ";
				$cadenaSql .= "fecha ";
				$cadenaSql .= ") ";
				$cadenaSql .= "VALUES ";
				
				foreach ( $_REQUEST as $clave => $valor ) {
					$cadenaSql .= "( ";
					$cadenaSql .= "'" . $idSesion . "', ";
					$cadenaSql .= "'" . $variable ['formulario'] . "', ";
					$cadenaSql .= "'" . $clave . "', ";
					$cadenaSql .= "'" . $valor . "', ";
					$cadenaSql .= "'" . $variable ['fecha'] . "' ";
					$cadenaSql .= "),";
				}
				
				$cadenaSql = substr ( $cadenaSql, 0, (strlen ( $cadenaSql ) - 1) );
				break;
			
			case "rescatarTemp" :
				$cadenaSql = "SELECT ";
				$cadenaSql .= "id_sesion, ";
				$cadenaSql .= "formulario, ";
				$cadenaSql .= "campo, ";
				$cadenaSql .= "valor, ";
				$cadenaSql .= "fecha ";
				$cadenaSql .= "FROM ";
				$cadenaSql .= $prefijo . "tempFormulario ";
				$cadenaSql .= "WHERE ";
				$cadenaSql .= "id_sesion='" . $idSesion . "'";
				break;
		}
		
		return $cadenaSql;
	}
}

?>
