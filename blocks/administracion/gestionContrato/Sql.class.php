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
				
			/* CONSULTAR - CONTRATO por ID */
			case "consultarContratoByID" :
				$cadenaSql = "SELECT  ";
				$cadenaSql .= " id_contrato, ";
				$cadenaSql .= " id_objeto, ";
				$cadenaSql .= " numero_contrato, ";
				$cadenaSql .= " fecha_inicio, ";
				$cadenaSql .= " fecha_finalizacion, ";
				$cadenaSql .= " id_supervisor, ";
				$cadenaSql .= " id_proveedor, ";
				$cadenaSql .= " numero_acto_admin, ";
				$cadenaSql .= " tipo_acto_admin, ";
				$cadenaSql .= " numero_cdp, ";
				$cadenaSql .= " numero_rp, ";
				$cadenaSql .= " fecha_rp, ";
				$cadenaSql .= " modalidad,  ";
				$cadenaSql .= " valor, ";
				$cadenaSql .= " rubro, ";
				$cadenaSql .= " poliza, ";
				$cadenaSql .= " forma_pago,  ";
				$cadenaSql .= " estado  ";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.contrato C";
				$cadenaSql .= " WHERE  id_contrato=" . $variable; // Activo
				break;
			
			/* CONSULTAR - CONTRATO */
			case "consultarContrato" :
				$cadenaSql = "SELECT  ";
				$cadenaSql .= " C.id_contrato, ";
				$cadenaSql .= " C.numero_contrato, ";
				$cadenaSql .= " C.fecha_inicio, ";
				$cadenaSql .= " C.fecha_finalizacion, ";
				$cadenaSql .= " S.nombre_supervisor, ";
				$cadenaSql .= " P.nom_proveedor, ";
				$cadenaSql .= " C.numero_acto_admin, ";
				$cadenaSql .= " tipo_acto_admin, ";
				$cadenaSql .= " C.numero_cdp, ";
				$cadenaSql .= " C.numero_rp, ";
				$cadenaSql .= " C.fecha_registro, ";
				$cadenaSql .= " C.modalidad,  ";
				$cadenaSql .= " C.estado  ";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.contrato C";
				$cadenaSql .= " JOIN agora.supervisor S ON S.id_supervisor = C.id_supervisor ";
				$cadenaSql .= " JOIN agora.informacion_proveedor P ON P.id_proveedor = C.id_proveedor "; // falta colocar la tabla que es para proveedores
				$cadenaSql .= " WHERE 1=1 ";
				if ($variable [0] != '') {
					$cadenaSql .= " AND  numero_contrato= '" . $variable [0] . "'";
				}
				if ($variable [1] != '') {
					$cadenaSql .= " AND C.fecha_registro BETWEEN '" . $variable [1] . "' AND '" . $variable [2] . "'";
				}
				break;
			
			/* ACTUALIZAR - OBJETO CONTRATO - ESTADO */
			case 'actualizarObjeto' :
				$cadenaSql = "UPDATE agora.objeto_contratar SET ";
				$cadenaSql .= "estado='" . $variable ['estado'] . "'";
				$cadenaSql .= " WHERE id_objeto=";
				$cadenaSql .= "'" . $variable ['idObjeto'] . "' ";
				break;
			
			/* LISTA - OBJETO A CONTRATAR */
			case "listaObjetoContratar" :
				$cadenaSql=" SELECT ";
				$cadenaSql.=" O.id_objeto, ";
				$cadenaSql.=" O.vigencia, ";
				$cadenaSql.=" O.numero_solicitud, ";
				$cadenaSql.=" O.codigociiu, ";
				$cadenaSql.=" S.nombre AS actividad, ";
				$cadenaSql.=" O.fecharegistro, ";
				$cadenaSql.=" O.id_unidad, ";
				$cadenaSql.=" (U.unidad || ' - ' || U.tipo) as unidad, ";
				$cadenaSql.=" O.cantidad, ";
				$cadenaSql.=" O.numero_cotizaciones, ";
				$cadenaSql.=" O.estado ";
				$cadenaSql.=" FROM agora.objeto_contratar O ";
				$cadenaSql.=" JOIN agora.ciiu_subclase S ON S.id_subclase = O.codigociiu ";
				$cadenaSql.=" JOIN agora.unidad U ON U.id_unidad = O.id_unidad ";
				$cadenaSql.=" WHERE  estado = '" . $variable . "'";
				$cadenaSql.=" ORDER BY fechaRegistro;";
				break;
			
			/* LISTA - PROVEEDORS */
			case "proveedores" :
				$cadenaSql = "SELECT";
				$cadenaSql .= " id_proveedor,";
				$cadenaSql .= "	nom_proveedor";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.informacion_proveedor";
				$cadenaSql .= " ORDER BY nom_proveedor";
				break;
			
			/* LISTA - SUPERVISORES */
			case "supervisor" :
				$cadenaSql = "SELECT";
				$cadenaSql .= " id_supervisor,";
				$cadenaSql .= "	nombre_supervisor";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.supervisor";
				$cadenaSql .= " WHERE  estado = 'ACTIVO'"; // Activo
				$cadenaSql .= " ORDER BY nombre_supervisor";
				break;
			
			/* CONSULTAR - OBJETO A CONTRATAR - ESPECIFICO */
			case "objetoContratar" :
				$cadenaSql=" SELECT ";
				$cadenaSql.=" O.id_objeto, ";
				$cadenaSql.=" O.vigencia, ";
				$cadenaSql.=" O.numero_solicitud, ";
				$cadenaSql.=" O.codigociiu, ";
				$cadenaSql.=" S.nombre AS actividad, ";
				$cadenaSql.=" O.fecharegistro, ";
				$cadenaSql.=" O.id_unidad, ";
				$cadenaSql.=" (U.unidad || ' - ' || U.tipo) as unidad, ";
				$cadenaSql.=" O.cantidad, ";
				$cadenaSql.=" O.numero_cotizaciones, ";
				$cadenaSql.=" O.estado ";
				$cadenaSql.=" FROM agora.objeto_contratar O ";
				$cadenaSql.=" JOIN agora.ciiu_subclase S ON S.id_subclase = O.codigociiu ";
				$cadenaSql.=" JOIN agora.unidad U ON U.id_unidad = O.id_unidad ";
				$cadenaSql.=" WHERE  O.id_objeto=" . $variable; 
				break;
			
			/* ACTUALIZAR - CONTRATO */
			case 'actualizarContrato' :
				$cadenaSql = "UPDATE agora.contrato SET ";
				$cadenaSql .= "numero_contrato='" . $variable [0] . "',";
				$cadenaSql .= "fecha_inicio='" . $variable [1] . "',";
				$cadenaSql .= "fecha_finalizacion='" . $variable [2] . "',";
				$cadenaSql .= "id_supervisor='" . $variable [3] . "',";
				$cadenaSql .= "numero_acto_admin='" . $variable [4] . "',";
				$cadenaSql .= "tipo_acto_admin='" . $variable [5] . "',";
				$cadenaSql .= "numero_cdp='" . $variable [6] . "',";
				$cadenaSql .= "numero_rp='" . $variable [7] . "',";
				$cadenaSql .= "fecha_rp='" . $variable [8] . "',";
				$cadenaSql .= "modalidad='" . $variable [9] . "',";
				$cadenaSql .= "id_proveedor='" . $variable [10] . "',";
				$cadenaSql .= "valor='" . $variable [11] . "',";
				$cadenaSql .= "rubro='" . $variable [12] . "',";
				$cadenaSql .= "poliza='" . $variable [13] . "',";
				$cadenaSql .= "forma_pago='" . $variable [14] . "' ";
				$cadenaSql .= " WHERE id_contrato=";
				$cadenaSql .= "'" . $variable [15] . "' ";
				break;
			
			/* GUARDAR - NUEVO CONTRATO */
			case 'registroContrato' :
				$cadenaSql = 'INSERT INTO ';
				$cadenaSql .= 'agora.contrato';
				$cadenaSql .= '( ';
				$cadenaSql .= 'id_objeto,';
				$cadenaSql .= 'numero_contrato,';
				$cadenaSql .= 'fecha_inicio,';
				$cadenaSql .= 'fecha_finalizacion,';
				$cadenaSql .= 'id_supervisor,';
				$cadenaSql .= 'numero_acto_admin,';
				$cadenaSql .= 'tipo_acto_admin,';
				$cadenaSql .= 'numero_cdp,';
				$cadenaSql .= 'numero_rp,';
				$cadenaSql .= 'fecha_rp,';
				$cadenaSql .= 'fecha_registro,';
				$cadenaSql .= 'modalidad,';
				$cadenaSql .= 'id_proveedor,';
				$cadenaSql .= 'valor,';
				$cadenaSql .= 'rubro,';
				$cadenaSql .= 'poliza,';
				$cadenaSql .= 'forma_pago,';
				$cadenaSql .= 'vigencia,';
				$cadenaSql .= 'estado';
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
				$cadenaSql .= '\'' . $variable [14] . '\', ';
				$cadenaSql .= '\'' . $variable [15] . '\', ';
				$cadenaSql .= '\'' . $variable [16] . '\', ';
				$cadenaSql .= '\'' . $variable [17] . '\', ';
				$cadenaSql .= '\'' . $variable [18] . '\'';
				$cadenaSql .= ');';
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