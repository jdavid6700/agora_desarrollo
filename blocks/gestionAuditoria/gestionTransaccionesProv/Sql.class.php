<?php

namespace gestionContractual\consultaContratosAprobados;

if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
}

include_once ("core/manager/Configurador.class.php");
include_once ("core/connection/Sql.class.php");

// Para evitar redefiniciones de clases el nombre de la clase del archivo sqle debe corresponder al nombre del bloque
// en camel case precedida por la palabra sql
class Sql extends \Sql {

    var $miConfigurador;

    function __construct() {
        $this->miConfigurador = \Configurador::singleton();
    }

    function getCadenaSql($tipo, $variable = "") {

        /**
         * 1.
         * Revisar las variables para evitar SQL Injection
         */
        $prefijo = $this->miConfigurador->getVariableConfiguracion("prefijo");
        $idSesion = $this->miConfigurador->getVariableConfiguracion("id_sesion");

        switch ($tipo) {

            /**
             * Clausulas específicas
             */


            case "obtenerInfoAuditoriaAgoraIdLog" :

                $cadenaSql = "SELECT * ";
                $cadenaSql .= "FROM prov_log_proveedor cs ";
                $cadenaSql .= "WHERE id = " .$variable. ";";
                break;

            case "obtenerInfoAuditoriaAgoraId" :

                $cadenaSql = "SELECT * ";
                $cadenaSql .= "FROM prov_log_proveedor_error cs ";
                $cadenaSql .= "WHERE id = " .$variable. ";";
                break;

            case "obtenerInfoAuditoriaAgoraAll" :

                $cadenaSql = "SELECT * ";
                $cadenaSql .= "FROM prov_log_proveedor_error cs ";
                break;

            case "obtenerInfoAuditoriaAgoraAllLog" :

                $cadenaSql = "SELECT * ";
                $cadenaSql .= "FROM prov_log_proveedor cs ";
                break;

            case "consultarContratosGeneralAuditError" :

                $cadenaSql = "SELECT * ";
                $cadenaSql .= "FROM frame_work.argo_log_contrato_error cs ";
                $cadenaSql .= "WHERE 1=1 ";
                if ($variable ['clase_contrato'] != '') {
                    $cadenaSql .= " AND cs.tipo_contrato = '" . $variable ['clase_contrato'] . "' ";
                }
                if ($variable ['numero_contrato'] != '') {
                    $cadenaSql .= " AND cs.consecutivo_contrato = '" . $variable ['numero_contrato'] . "' ";
                }
                if ($variable ['vigencia'] != '') {
                    $cadenaSql .= " AND cs.vigencia = '" . $variable ['vigencia'] . "' ";
                }

                $cadenaSql .= "  ORDER BY cs.id DESC; ";
                break;

            case "obtenerInfoAuditoria" :

                $cadenaSql = "SELECT * ";
                $cadenaSql .= "FROM frame_work.argo_log_contrato cs ";
                $cadenaSql .= "WHERE id = " .$variable. ";";
                break;

            case "obtenerInfoAuditoriaError" :

                $cadenaSql = "SELECT * ";
                $cadenaSql .= "FROM frame_work.argo_log_contrato_error cs ";
                $cadenaSql .= "WHERE id = " .$variable. ";";
                break;

            case "consultarContratosGeneralAudit" :

                $cadenaSql = "SELECT * ";
                $cadenaSql .= "FROM frame_work.argo_log_contrato cs ";
                $cadenaSql .= "WHERE 1=1 ";
                if ($variable ['clase_contrato'] != '') {
                    $cadenaSql .= " AND cs.tipo_contrato = '" . $variable ['clase_contrato'] . "' ";
                }
                if ($variable ['numero_contrato'] != '') {
                    $cadenaSql .= " AND cs.consecutivo_contrato = '" . $variable ['numero_contrato'] . "' ";
                }
                if ($variable ['vigencia'] != '') {
                    $cadenaSql .= " AND cs.vigencia = '" . $variable ['vigencia'] . "' ";
                }

                $cadenaSql .= "  ORDER BY cs.consecutivo_contrato DESC; ";
                break;

            case 'buscar_contrato_audit' :
                $cadenaSql = " SELECT  DISTINCT cs.consecutivo_contrato||'-('||cs.vigencia||')' AS  data, cs.consecutivo_contrato||' - ('||cs.vigencia||')'  AS value  ";
                $cadenaSql .= " FROM frame_work.argo_log_contrato cs  ";
                $cadenaSql .= " WHERE (cast(cs.consecutivo_contrato as text) LIKE '%" . $variable . "%') ";
                $cadenaSql .= " ORDER BY data ASC LIMIT 10;";
                break;    

            case "vigencias_existentes" :

                $cadenaSql = "SELECT DISTINCT vigencia as id, vigencia as vigencia  ";
                $cadenaSql .= " FROM frame_work.argo_log_contrato; ";
                break;

            case "unidad_ejecutora_gasto" :

                $cadenaSql = "SELECT id_parametro  id, pr.descripcion valor   ";
                $cadenaSql .= " FROM relacion_parametro rl ";
                $cadenaSql .= "JOIN parametros pr ON pr.rel_parametro=rl.id_rel_parametro ";
                $cadenaSql .= "WHERE rl.descripcion ='unidad_ejecutora_gasto' ORDER BY id_parametro DESC ; ";
                break;

            case "tipo_orden" :

                $cadenaSql = " 	SELECT 	id_parametro, ";
                $cadenaSql .= " descripcion ";
                $cadenaSql .= " FROM  ";
                $cadenaSql .= " parametros ";
                $cadenaSql .= " WHERE  ";
                $cadenaSql .= " estado_registro=TRUE  ";
                $cadenaSql .= " AND  ";
                $cadenaSql .= " rel_parametro=30;  ";

                break;

            case "obtenerInformacionElaborador" :

                $cadenaSql = " 	SELECT nombre , apellido  ";
                $cadenaSql .= " FROM frame_work.argo_usuario  ";
                $cadenaSql .= " WHERE id_usuario = '$variable'; ";

                break;

            case "Consultar_Tipo_orden" :

                $cadenaSql = " 	SELECT 	tipo_orden ";
                $cadenaSql .= " FROM  ";
                $cadenaSql .= " orden ";
                $cadenaSql .= " WHERE  ";
                $cadenaSql .= " numero_contrato='" . $variable['numero_contrato'] . "'  ";
                $cadenaSql .= " AND vigencia=" . $variable['vigencia'] . ";  ";


                break;
            
            case "consultaArrendamientoGeneral" :
                $cadenaSql = " SELECT *  ";
                $cadenaSql .= "  FROM argo.contrato_general_por_arrendamiento cga ";
                $cadenaSql .= " WHERE cga.numero_contrato='$variable[0]' and cga.vigencia = $variable[1] ORDER BY consecutivo_general_arrendamiento;  ";
                
                break;
            
             case "consultaArrendamiento" :
                $cadenaSql = " SELECT *  ";
                $cadenaSql .= "  FROM argo.contrato_arrendamiento ca ";
                $cadenaSql .= " WHERE ca.numero_contrato='$variable[0]' and ca.vigencia = $variable[1] ";
               
                break;
            
              case "obtenerAmparosParametros" :
                $cadenaSql = " SELECT id, nombre FROM core.amparos; ";

                break;
            
            case "obtenerAmparosParametros2" :
                $cadenaSql = " SELECT id, nombre FROM core.amparos WHERE id=".$variable." ; ";
                 
                break;
            
            case "consultaArrendamientoAmparo" :
                $cadenaSql = " SELECT *  ";
                $cadenaSql .= "  FROM argo.amparo_contrato cga ";
                $cadenaSql .= " WHERE cga.numero_contrato='".$variable['numero_contrato']."' AND cga.vigencia_contrato=".$variable['vigencia']." ORDER BY id;  ";
               
                break;

            case "obtenerPolizarOrden" :
                $cadenaSql = " 	SELECT poliza, fecha_inicio, fecha_final ";
                $cadenaSql .= " FROM contrato_poliza ";
                $cadenaSql .= " WHERE numero_contrato='" . $variable['numero_contrato'] . "' AND vigencia= " . $variable['vigencia'] . ";";

                break;

            case "polizas" :
                $cadenaSql = " SELECT ";
                $cadenaSql .= " id_poliza,";
                $cadenaSql .= " nombre_de_la_poliza, ";
                $cadenaSql .= " descripcion_poliza, estado ";
                $cadenaSql .= " FROM ";
                $cadenaSql .= " poliza ORDER BY id_poliza; ";
                break;



            case "buscar_Proveedores" :
                $cadenaSql = " SELECT nit||' - ('||nomempresa||')' AS  value, nit AS data  ";
                $cadenaSql .= " FROM proveedor.prov_proveedor_info ";
                $cadenaSql .= " WHERE cast(nit as text) LIKE '%$variable%' OR nomempresa LIKE '%$variable%' LIMIT 10; ";
                break;

            case "info_orden_Cancelada" :

                $cadenaSql = " 	SELECT fecha_cancelacion, motivo_cancelacion, usuario  ";
                $cadenaSql .= " FROM contrato_cancelado ";
                $cadenaSql .= " WHERE numero_contrato = '" . $variable['numero_contrato'] . "' AND vigencia=" . $variable['vigencia'] . ";";

                break;
            case "info_orden_Anulada" :

                $cadenaSql = " 	SELECT n.fecha_registro, n.usuario, n.acto_administrativo, n.descripcion, n.documento, p.descripcion as tipoAnulacion   ";
                $cadenaSql .= " FROM novedad_contractual n, anulacion a, parametros p  ";
                $cadenaSql .= " WHERE n.id = a.id AND p.id_parametro = a.tipo_anulacion ";
                $cadenaSql .= " AND numero_contrato = '" . $variable['numero_contrato'] . "' AND vigencia=" . $variable['vigencia'] . ";";

                break;

            case "info_orden_termminada" :

                $cadenaSql = " 	SELECT fecha_registro, usuario, acto_administrativo, descripcion, documento   ";
                $cadenaSql .= " FROM novedad_contractual ";
                $cadenaSql .= " WHERE tipo_novedad=218 ";
                $cadenaSql .= " AND numero_contrato = '" . $variable['numero_contrato'] . "' AND vigencia=" . $variable['vigencia'] . ";";

                break;

            case "info_orden_terminada_anticipada" :

                $cadenaSql = " 	SELECT ta.fecha, n.usuario, n.acto_administrativo, n.descripcion, n.documento   ";
                $cadenaSql .= " FROM novedad_contractual n, terminacion_anticipada ta ";
                $cadenaSql .= " WHERE n.id = ta.id and tipo_novedad=257 ";
                $cadenaSql .= " AND n.numero_contrato = '" . $variable['numero_contrato'] . "' AND n.vigencia=" . $variable['vigencia'] . ";";

                break;
            case "info_contrato_liquidado" :

                $cadenaSql = " 	SELECT *   ";
                $cadenaSql .= " FROM contrato_liquidado ";
                $cadenaSql .= " WHERE  ";
                $cadenaSql .= " numero_contrato = '" . $variable['numero_contrato'] . "' AND vigencia=" . $variable['vigencia'] . ";";

                break;


            case "ConsultarCDPContrato" :
                $cadenaSql = " 	SELECT numero_cdp  ";
                $cadenaSql .= " FROM contrato_general ";
                $cadenaSql .= " WHERE numero_contrato = '" . $variable['numero_contrato'] . "' AND vigencia=" . $variable['vigencia'] . ";";
                break;




            case "ConsultarDisponibilidadesContrato" :
                $cadenaSql = " SELECT numero_cdp, vigencia_cdp FROM contrato_disponibilidad ";
                $cadenaSql .= " WHERE numero_contrato='" . $variable[0] . "' and vigencia=" . $variable[1] . "; ";

                break;

            case "ConsultarDisponibilidadesContratoRP" :
                $cadenaSql = " SELECT id||'-'||numero_cdp||'-'||vigencia_cdp as value,numero_cdp||'-'||vigencia_cdp as informacion FROM contrato_disponibilidad ";
                $cadenaSql .= " WHERE numero_contrato='" . $variable[0] . "' and vigencia=" . $variable[1] . "; ";

                break;

            case "ConsultarRegistrosPresupuestalesContratoRP" :
                $cadenaSql = " SELECT rp.registro_presupuestal,rp.vigencia_rp,rp.id,cdp.numero_cdp, cdp.vigencia_cdp,rp.id as idrp   ";
                $cadenaSql .= " FROM contrato_disponibilidad cdp,  registro_presupuestal_disponibilidad rp  ";
                $cadenaSql .= " WHERE rp.id_disponibilidad_contrato = cdp.id ";
                $cadenaSql .= " AND numero_contrato='" . $variable[0] . "' and vigencia=" . $variable[1] . "; ";

                break;


            case "ConsultarRP" :
                $cadenaSql = " SELECT rp.vigencia_rp, rp.registro_presupuestal, cdp.numero_cdp ";
                $cadenaSql .= " FROM registro_presupuestal_disponibilidad rp , contrato_disponibilidad cdp";
                $cadenaSql .= " WHERE cdp.id = rp.id_disponibilidad_contrato AND rp.id = $variable; ";
                break;

            case "inactivarrp" :
                $cadenaSql = " DELETE FROM registro_presupuestal_disponibilidad ";
                $cadenaSql .= " WHERE id = $variable; ";
                break;

            case "obtenerInfoCdp" :
                $cadenaSql = " SELECT SN.NUM_SOL_ADQ, SCDP.ID_SOL_CDP, CDP.NUMERO_DISPONIBILIDAD,SN.VIGENCIA ,DP.NOMBRE_DEPENDENCIA, ";
                $cadenaSql.=" SN.ESTADO, SN.JUSTIFICACION, CDP.OBJETO,SN.VALOR_CONTRATACION,CDP.ESTADO as ESTADOCDP , CDP.FECHA_REGISTRO,SN.RUBRO_INTERNO, RB.DESCRIPCION ";
                $cadenaSql.=" FROM CO.CO_SOLICITUD_ADQ SN, CO.CO_SOL_CDP SCDP, PR.PR_DISPONIBILIDADES CDP , CO.CO_DEPENDENCIAS DP, PR.PR_RUBRO RB  ";
                $cadenaSql.=" WHERE SN.NUM_SOL_ADQ = SCDP.NUM_SOL_ADQ and SN.VIGENCIA = SCDP.VIGENCIA and SN.DEPENDENCIA = DP.COD_DEPENDENCIA ";
                $cadenaSql.=" and SN.VIGENCIA = RB.VIGENCIA and SN.RUBRO_INTERNO = RB.INTERNO  ";
                $cadenaSql.=" and CDP.VIGENCIA = SCDP.VIGENCIA and CDP.NUM_SOL_ADQ = SCDP.ID_SOL_CDP and CDP.CODIGO_COMPANIA = SCDP.CODIGO_COMPANIA  ";
                $cadenaSql.=" and CDP.VIGENCIA = SCDP.VIGENCIA and SN.VIGENCIA=" . $variable['vigencia'] . " and ";
                $cadenaSql.="  SN.CODIGO_UNIDAD_EJECUTORA='0" . $variable['unidad_ejecutora'] . "' and CDP.NUMERO_DISPONIBILIDAD=" . $variable['numero_disponibilidad'] . " ";
                $cadenaSql.="  ORDER BY SN.NUM_SOL_ADQ ";

                break;

            case "registroRp" :
                $cadenaSql = " INSERT INTO registro_presupuestal_disponibilidad (id_disponibilidad_contrato,registro_presupuestal,vigencia_rp)  ";
                $cadenaSql.=" VALUES(" . $variable['numero_cdp'] . ", " . $variable['rp'] . "," . $variable['vigencia_rp'] . ");";

                break;

            case "convenios" :
                $cadenaSql = " SELECT ";
                $cadenaSql .= " \"NUMERO_PRO\" as value,";
                $cadenaSql .= " \"NUMERO_PRO\" as data";
                $cadenaSql .= " FROM ";
                $cadenaSql .= " convenio; ";
                break;


            case "vigencia_convenios" :
                $cadenaSql = " SELECT DISTINCT ";
                $cadenaSql .= " \"ANIO_PRO\" as value,";
                $cadenaSql .= " \"ANIO_PRO\" as data";
                $cadenaSql .= " FROM ";
                $cadenaSql .= " convenio ORDER BY \"ANIO_PRO\" DESC; ";
                break;

            case "buscar_nombre_convenio" :
                $cadenaSql = " SELECT ";
                $cadenaSql .= " \"NOMBRE\"";
                $cadenaSql .= " FROM ";
                $cadenaSql .= " convenio";
                $cadenaSql .= " WHERE ";
                $cadenaSql .= " \"NUMERO_PRO\" = '$variable' ";
                break;

            case "consultarContratosGeneral" :

                $cadenaSql = "SELECT DISTINCT cg.clase_contratista,p.descripcion, cg.numero_contrato, "
                        . " cg.vigencia, cg.fecha_registro, cg.contratista as proveedor,cg.tipologia_contrato, "
                        . " ec.nombre_estado, tpc.tipo_contrato,"
                        . " ce.fecha_registro as fecha_registro_estado, cg.convenio, cs.numero_contrato_suscrito  , cs.fecha_suscripcion  ";
                $cadenaSql .= "FROM parametros p, contrato_general cg, tipo_contrato tpc , ";
                $cadenaSql .= "contrato_estado ce, estado_contrato ec, contrato_suscrito cs  ";
                $cadenaSql .= "WHERE cg.tipologia_contrato = p.id_parametro AND  cg.tipo_contrato = tpc.id ";
                $cadenaSql .= "AND cg.numero_contrato = cs.numero_contrato and cg.vigencia = cs.vigencia ";
                $cadenaSql .= "AND cg.numero_contrato = ce.numero_contrato and cg.vigencia = ce.vigencia and ce.estado = ec.id ";
                $cadenaSql .= "AND ce.fecha_registro = (SELECT MAX(cee.fecha_registro) from contrato_estado cee where cg.numero_contrato = cee.numero_contrato and  cg.vigencia = cee.vigencia) ";
                $cadenaSql .= "AND cg.unidad_ejecutora = " . $variable ['unidad_ejecutora'] . "  and tpc.id_grupo_tipo_contrato = 2 ";
                $cadenaSql .= "AND cg.estado = 'true' AND ec.id =3  AND cg.vigencia = " . $variable['vigencia_curso'] . "  AND cg.clase_contratista is not null ";
                if ($variable ['clase_contrato'] != '') {
                    $cadenaSql .= " AND cg.tipo_contrato = '" . $variable ['clase_contrato'] . "' ";
                }
                if ($variable ['numero_contrato'] != '') {
                    $cadenaSql .= " AND cs.numero_contrato_suscrito = '" . $variable ['numero_contrato'] . "' ";
                }
                if ($variable ['vigencia'] != '') {
                    $cadenaSql .= " AND cs.vigencia = '" . $variable ['vigencia'] . "' ";
                }

                if ($variable ['nit'] != '') {
                    $cadenaSql .= " AND cg.contratista = '" . $variable ['nit'] . "' ";
                }

                if ($variable ['fecha_inicial'] != '' && $variable ['fecha_final'] != '') {
                    $cadenaSql .= " AND cg.fecha_registro BETWEEN CAST ( '" . $variable ['fecha_inicial'] . "' AS DATE) ";
                    $cadenaSql .= " AND  CAST ( '" . $variable ['fecha_final'] . "' AS DATE)  ";
                }

                $cadenaSql .= "  ORDER BY cs.numero_contrato_suscrito DESC; ";

                break;
            //-------------------- Agregadas -----------------------------------------------------

            case "buscar_numero_orden" :

                $cadenaSql = " 	SELECT 	o.numero_contrato ||'-'|| o.vigencia as value, o.numero_contrato ||'-'||o.vigencia as orden ";
                $cadenaSql .= " FROM orden o, contrato_general cg, contrato_estado ce, estado_contrato ec  ";
                $cadenaSql .= " WHERE o.numero_contrato = cg.numero_contrato and o.vigencia = cg.vigencia and  cg.unidad_ejecutora ='" . $variable['unidad'] . "' ";
                $cadenaSql .= " AND cg.numero_contrato = ce.numero_contrato and cg.vigencia = ce.vigencia and ce.estado = ec.id ";
                $cadenaSql .= " AND ce.fecha_registro = (SELECT MAX(cee.fecha_registro) from contrato_estado cee where o.numero_contrato = cee.numero_contrato and  o.vigencia = cee.vigencia) ";
                $cadenaSql .= " and tipo_orden ='" . $variable['tipo_orden'] . "' and cg.estado_aprobacion = 't' AND ec.id = 3 ;";

                break;
            case "buscarProveedoresFiltro" :
                $cadenaSql = " SELECT num_documento||' - '||nom_proveedor AS  value, id_proveedor  AS data  ";
                $cadenaSql .= " FROM agora.informacion_proveedor ";
                $cadenaSql .= " WHERE (cast(num_documento as text) LIKE '%" . $variable . "%'  ";
                $cadenaSql .= "  OR nom_proveedor  LIKE '%" . $variable . "%') LIMIT 15; ";
                break;

            case "registroCancelacion" :
                $cadenaSql = " INSERT INTO contrato_cancelado(";
                $cadenaSql .= " numero_contrato, vigencia,fecha_cancelacion ,motivo_cancelacion,usuario,fecha_registro) ";
                $cadenaSql .= " VALUES (";
                $cadenaSql .= "'" . $variable ['numero_contrato'] . "',";
                $cadenaSql .= $variable ['vigencia'] . ",";
                $cadenaSql .= "'" . $variable ['fecha_cancelacion'] . "',";
                $cadenaSql .= "'" . $variable ['motivo_cancelacion'] . "',";
                $cadenaSql .= "'" . $variable ['usuario'] . "',";
                $cadenaSql .= "'" . $variable ['fecha_registo'] . "');";

                break;

            case "informacion_convenio" :
                $cadenaSql = " SELECT ";
                $cadenaSql .= " * ";
                $cadenaSql .= " FROM ";
                $cadenaSql .= " convenio";
                $cadenaSql .= " WHERE ";
                $cadenaSql .= " \"NUMERO_PRO\" = '$variable' ";
                break;

            case "informacion_contratista_unico" :
                $cadenaSql = " SELECT p.tipopersona, p.num_documento,c.nombre , ";
                $cadenaSql.=" p.direccion, p.correo, p.web, p.puntaje_evaluacion, p.clasificacion_evaluacion, ";
                $cadenaSql.=" pe.valor_parametro, p.tipo_cuenta_bancaria, p.num_cuenta_bancaria, ";
                $cadenaSql.=" eb.nombre_banco, p.fecha_registro, p.nom_proveedor";
                $cadenaSql.=" FROM agora.informacion_proveedor p, agora.parametro_estandar pe, core.ciudad c, core.banco eb ";
                $cadenaSql.=" WHERE pe.id_parametro = p.estado AND p.id_ciudad_contacto = c.id_ciudad";
                $cadenaSql.=" AND eb.id_codigo = p.id_entidad_bancaria AND p.id_proveedor = $variable;";

                break;

            case "informacion_sociedad_temporal_consulta" :
                $cadenaSql = " SELECT p.tipopersona, p.num_documento,c.nombre , ";
                $cadenaSql.=" p.direccion, p.correo, p.web, p.puntaje_evaluacion, p.clasificacion_evaluacion, ";
                $cadenaSql.=" pe.valor_parametro, p.tipo_cuenta_bancaria, p.num_cuenta_bancaria, ";
                $cadenaSql.=" eb.nombre_banco, p.fecha_registro, p.nom_proveedor, pr.num_documento||' - '||pr.nom_proveedor as representante, ";
                $cadenaSql.=" prs.num_documento||' - '||prs.nom_proveedor as representante_suplente ";
                $cadenaSql.=" FROM agora.informacion_proveedor p, agora.parametro_estandar pe, core.ciudad c, core.banco eb,  ";
                $cadenaSql.=" agora.informacion_sociedad_temporal ist, agora.informacion_proveedor pr, agora.informacion_proveedor prs  ";
                $cadenaSql.=" WHERE pe.id_parametro = p.estado AND p.id_ciudad_contacto = c.id_ciudad ";
                $cadenaSql.=" AND ist.id_proveedor_sociedad = p.id_proveedor ";
                $cadenaSql.=" AND ist.representante = pr.id_proveedor ";
                $cadenaSql.=" AND ist.representante_suplente = prs.id_proveedor ";
                $cadenaSql.=" AND eb.id_codigo = p.id_entidad_bancaria AND p.id_proveedor = $variable;";

                break;



            case 'buscar_contrato' :
                $cadenaSql = " SELECT  DISTINCT cs.numero_contrato_suscrito||'-('||cs.vigencia||')' AS  data, cs.numero_contrato_suscrito||' - ('||cs.vigencia||')'  AS value  ";
                $cadenaSql .= " FROM contrato_general cg, contrato_estado ce, estado_contrato ec, contrato_suscrito cs,tipo_contrato tpc  ";
                $cadenaSql .= " WHERE cg.unidad_ejecutora ='" . $variable['unidad'] . "' AND cg.tipo_contrato=tpc.id  ";
                $cadenaSql .= " AND cg.numero_contrato = cs.numero_contrato and cg.vigencia = cs.vigencia ";
                $cadenaSql .= " AND cg.numero_contrato = ce.numero_contrato and cg.vigencia = ce.vigencia and ce.estado = ec.id AND tpc.id_grupo_tipo_contrato = 2 AND cg.vigencia = '" . $variable['vigencia_curso'] . "' ";
                $cadenaSql .= " AND ce.fecha_registro = (SELECT MAX(cee.fecha_registro) from contrato_estado cee where cg.numero_contrato = cee.numero_contrato and  cg.vigencia = cee.vigencia) ";
                $cadenaSql .= " AND ec.id =3 AND (cast(cs.numero_contrato_suscrito as text) LIKE '%" . $variable['parametro'] . "%' ";
                $cadenaSql .= " OR cast(cg.vigencia as text ) LIKE '%" . $variable['parametro'] . "%' ) ";
                $cadenaSql .= " ORDER BY data ASC LIMIT 10;";
                break;

            case "obtenerInfoUsuario" :
                $cadenaSql = "SELECT u.dependencia_especifica ||' - '|| u.dependencia as nombre, unidad_ejecutora ";
                $cadenaSql .= "FROM frame_work.argo_usuario u  ";
                $cadenaSql .= "WHERE u.id_usuario='" . $variable . "' ";
                break;

            case "tipo_clase_contrato" :

                $cadenaSql = "SELECT id_parametro  id, pr.descripcion valor   ";
                $cadenaSql .= " FROM relacion_parametro rl ";
                $cadenaSql .= "JOIN parametros pr ON pr.rel_parametro=rl.id_rel_parametro ";
                $cadenaSql .= "WHERE rl.id_rel_parametro= 8 or  rl.id_rel_parametro= 6; ";

                break;

            case "consultarContratoProcesarAjax" :
                $cadenaSql = " SELECT  tp.tipo_contrato,  cg.unidad_ejecutora  FROM contrato_general cg, tipo_contrato tp ";
                $cadenaSql .= " WHERE tp.id = cg.tipo_contrato and numero_contrato='$variable[1]' and vigencia = $variable[2] ";
                break;

            case 'buscarDepartamento' : // Solo Departamentos de Colombia

                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'id_departamento as ID_DEPARTAMENTO, ';
                $cadenaSql .= 'nombre as NOMBRE ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'core.departamento ';
                $cadenaSql .= 'ORDER BY NOMBRE';
                break;

            case 'buscarCiudad' :
                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'id_ciudad as ID_CIUDAD, ';
                $cadenaSql .= 'nombre as NOMBRE ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'core.ciudad ';
                $cadenaSql .= 'ORDER BY NOMBRE;';
                break;

            case 'buscarCiudadAjax' :

                $cadenaSql = 'SELECT DISTINCT ';
                $cadenaSql .= 'id_ciudad as ID_CIUDAD, ';
                $cadenaSql .= 'nombre as NOMBRECIUDAD ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'core.ciudad ';
                $cadenaSql .= 'WHERE ';
                $cadenaSql .= 'id_departamento = ' . $variable . ' ';
                $cadenaSql .= 'ORDER BY NOMBRE';
                break;


            case "consultarBanco" :
                $cadenaSql = "SELECT";
                $cadenaSql .= " id_codigo,";
                $cadenaSql .= "	nombre_banco";
                $cadenaSql .= " FROM ";
                $cadenaSql .= " core.banco";
                $cadenaSql .= " WHERE estado != 'INACTIVO' ";
                $cadenaSql .= " ORDER BY nombre_banco";
                break;

            case "informacion_sociedad_proveedor" :

                $cadenaSql = " SELECT num_documento,id_ciudad_contacto,correo,web,";
                $cadenaSql.= " tipo_cuenta_bancaria,num_cuenta_bancaria,";
                $cadenaSql.= " id_entidad_bancaria,nom_proveedor,";
                $cadenaSql.= " documento_representante, documento_suplente,digito_verificacion ";
                $cadenaSql.=" FROM agora.informacion_sociedad_temporal, agora.informacion_proveedor  ";
                $cadenaSql.=" WHERE num_documento = identificacion and  num_documento=$variable; ";

                break;
            case "informacion_sociedad_telefono" :
                $cadenaSql = " SELECT t.numero_tel FROM agora.telefono t, ";
                $cadenaSql.=" agora.proveedor_telefono pt, agora.informacion_proveedor ip";
                $cadenaSql.=" WHERE t.id_telefono = pt.id_telefono ";
                $cadenaSql.=" AND pt.id_proveedor = ip.id_proveedor ";
                $cadenaSql.=" AND ip.num_documento = $variable;";
                break;
            case "informacion_sociedad_temporal" :

                $cadenaSql = " SELECT identificacion, documento_representante, documento_suplente,nombre,digito_verificacion ";
                $cadenaSql.=" FROM sociedad_temporal WHERE identificacion=$variable; ";

                break;
            case "nombre_participante" :

                $cadenaSql = " SELECT nom_proveedor, tipopersona,puntaje_evaluacion ";
                $cadenaSql.=" FROM agora.informacion_proveedor WHERE num_documento=$variable; ";

                break;

            case "nombre_participante_natural" :

                $cadenaSql = " SELECT p.num_documento_persona||'-('||p.primer_apellido||' '"
                        . "||p.segundo_apellido||' '||p.primer_nombre||' '||p.segundo_nombre||')' AS value  ";
                $cadenaSql .= " FROM  agora.informacion_persona_natural p  WHERE num_documento_persona=$variable;   ";

                break;


            case "obtener_participantes" :

                $cadenaSql = " SELECT ip.num_documento ||'-'|| ip.nom_proveedor as nombre_participante, istp.porcentaje_participacion ";
                $cadenaSql.=" FROM agora.informacion_sociedad_participante istp, agora.informacion_proveedor ip ";
                $cadenaSql.=" WHERE ip.id_proveedor = istp.id_contratista AND id_proveedor_sociedad = $variable; ";

                break;
            case "buscarDepartamentodeCiudad" :

                $cadenaSql = " select d.id_departamento ";
                $cadenaSql.=" from core.departamento d, core.ciudad c ";
                $cadenaSql.=" where c.id_departamento = d.id_departamento ";
                $cadenaSql.=" and c.id_ciudad = $variable;";

                break;


            case 'Consultar_Contrato_Particular' :
                $cadenaSql = " SELECT  ";
                $cadenaSql .= " cg.*, s.documento, s.nombre,s.id as idSupervisor, s.cargo,s.sede_supervisor,s.dependencia_supervisor,s.digito_verificacion,  ";
                $cadenaSql .= " le.direccion, le.sede, le.dependencia, le.ciudad,s.tipo ";
                $cadenaSql .= " FROM ";
                $cadenaSql .= " contrato_general cg, supervisor_contrato s, lugar_ejecucion le ";
                $cadenaSql .= " WHERE ";
                $cadenaSql .= " cg.supervisor= s.id and cg.lugar_ejecucion = le.id and ";
                $cadenaSql .= " cg.numero_contrato= '$variable[0]' and ";
                $cadenaSql .= " cg.vigencia = $variable[1] ; ";
                break;


            case 'buscarPais' :

                $cadenaSql = 'SELECT ';
                $cadenaSql .= 'id_pais as ID_PAIS, ';
                $cadenaSql .= 'nombre_pais as NOMBRE, ';
                $cadenaSql .= 'codigo_pais as COD_PAIS ';
                $cadenaSql .= 'FROM ';
                $cadenaSql .= 'core.pais ';
                $cadenaSql .= 'ORDER BY NOMBRE';
                break;

            case "buscarDepartamentodeCiudad" :

                $cadenaSql = " select d.id_departamento ";
                $cadenaSql.=" from agora.departamento d, agora.ciudad c ";
                $cadenaSql.=" where c.id_departamento = d.id_departamento ";
                $cadenaSql.=" and c.id_ciudad = $variable;";

                break;



            case 'buscarPadresCiudad' :
                $cadenaSql = " select p.id_pais, d.id_departamento from core.pais p,core.departamento d,";
                $cadenaSql.=" core.ciudad c where p.id_pais = d.id_pais and d.id_departamento = c.id_departamento ";
                $cadenaSql.=" and c.id_ciudad = $variable ;";
                break;


            case "obtenerIdsTerceros" :

                $cadenaSql = " SELECT id_proveedor FROM agora.solicitud_cotizacion where id_objeto = $variable;";
                break;


            case "obtenerTerceros" :

                $cadenaSql = " SELECT id_proveedor,nom_proveedor,num_documento,tipopersona,puntaje_evaluacion FROM agora.informacion_proveedor ";
                $cadenaSql .= " WHERE id_proveedor IN ($variable) ;";
                break;


            case "obtenerIdObjeto" :
                $cadenaSql = " SELECT id_objeto  FROM agora.objeto_contratar  ";
                $cadenaSql .= " WHERE numero_solicitud = " . $variable[0] . " and vigencia =" . $variable[1] . ";";
                break;

            case "sede" :

                $cadenaSql = "SELECT DISTINCT  \"ESF_ID_SEDE\", \"ESF_SEDE\" ";
                $cadenaSql .= " FROM \"sedes_SIC\" ";
                $cadenaSql .= " WHERE   \"ESF_ESTADO\"='A' ";
                $cadenaSql .= " AND    \"ESF_COD_SEDE\" >  0 ;";
                break;

            case "dependenciasConsultadas" :
                $cadenaSql = "SELECT DISTINCT  \"ESF_CODIGO_DEP\" , \"ESF_DEP_ENCARGADA\" ";
                $cadenaSql .= " FROM \"dependencia_SIC\" ad ";
                $cadenaSql .= " JOIN  \"espaciosfisicos_SIC\" ef ON  ef.\"ESF_ID_ESPACIO\"=ad.\"ESF_ID_ESPACIO\" ";
                $cadenaSql .= " JOIN  \"sedes_SIC\" sa ON sa.\"ESF_COD_SEDE\"=ef.\"ESF_COD_SEDE\" ";
                $cadenaSql .= " WHERE  ad.\"ESF_ESTADO\"='A' ";

                break;




            case "Consultar_Disponibilidad" :
                $cadenaSql = " SELECT CDP.NUMERO_DISPONIBILIDAD, CDP.VIGENCIA, RB.DESCRIPCION, ";
                $cadenaSql.=" CDP.RUBRO_INTERNO , CDP.VALOR ";
                $cadenaSql.=" FROM PR.PR_DISPONIBILIDAD_RUBRO CDP, PR.PR_RUBRO RB";
                $cadenaSql.=" WHERE CDP.VIGENCIA = RB.VIGENCIA and CDP.RUBRO_INTERNO = RB.INTERNO ";
                $cadenaSql.=" and CDP.VIGENCIA = $variable[1] AND CDP.CODIGO_UNIDAD_EJECUTORA='0$variable[2]' ";
                $cadenaSql.=" AND CDP.NUMERO_DISPONIBILIDAD = $variable[0] ";
                break;


            case "Consultar_Rubros" :
                $cadenaSql = " SELECT RP.NUMERO_REGISTRO, RP.RUBRO_INTERNO,RB.DESCRIPCION, ";
                $cadenaSql.=" RP.VALOR,  RP.VIGENCIA FROM PR.PR_REGISTRO_DISPONIBILIDAD RP, PR.PR_RUBRO RB";
                $cadenaSql.=" WHERE RP.VIGENCIA = RB.VIGENCIA and RP.RUBRO_INTERNO = RB.INTERNO ";
                $cadenaSql.=" and RP.VIGENCIA = $variable[1]  and RP.CODIGO_UNIDAD_EJECUTORA = '0$variable[2]' ";
                $cadenaSql.=" and RP.NUMERO_DISPONIBILIDAD=  $variable[0] and RP.NUMERO_REGISTRO = $variable[3] ";
                break;

            case "consultarRegistroDisponibilidad" :
                $cadenaSql = " SELECT DISTINCT RP.NUMERO_REGISTRO VALOR, RP.NUMERO_REGISTRO  as INFORMACION ";
                $cadenaSql.= " FROM PR.PR_REGISTRO_DISPONIBILIDAD RP, PR.PR_RUBRO RB  ";
                $cadenaSql.=" WHERE RP.VIGENCIA = RB.VIGENCIA and RP.RUBRO_INTERNO = RB.INTERNO ";
                $cadenaSql.=" and RP.VIGENCIA = $variable[1]  and RP.CODIGO_UNIDAD_EJECUTORA = '0$variable[2]' ";
                $cadenaSql.=" and RP.NUMERO_DISPONIBILIDAD=  $variable[0] and RP.NUMERO_REGISTRO NOT IN ($variable[3]) ";
                break;


            case "tipo_clase_contratista" :

                $cadenaSql = "SELECT id_parametro  id,pr.codigo_contraloria|| ' - ' ||pr.descripcion valor   ";
                $cadenaSql .= " FROM relacion_parametro rl ";
                $cadenaSql .= "JOIN parametros pr ON pr.rel_parametro=rl.id_rel_parametro ";
                $cadenaSql .= "WHERE rl.descripcion ='clase_contratista'; ";
                break;


            case "tipo_compromiso" :

                $cadenaSql = " SELECT id as id, codigo_contraloria|| ' - ' || grupo_contrato as valor";
                $cadenaSql.=" FROM argo.grupo_tipo_contrato WHERE (id = 2 or id = 3) ;";

                break;
            case "tipo_contrato" :

                $cadenaSql = " SELECT id as id, tipo_contrato as valor";
                $cadenaSql.=" FROM argo.tipo_contrato WHERE estado = 't' and id_grupo_tipo_contrato <> 1 ;";

                break;

            case "ConsultarperfilCPS" :
                $cadenaSql = " SELECT perfil from  argo.contrato_cps ";
                $cadenaSql.=" WHERE ";
                $cadenaSql .= " numero_contrato= '$variable[0]' and ";
                $cadenaSql .= " vigencia = $variable[1] ; ";

                break;

            case "perfiles" :

                $cadenaSql = " SELECT id_parametro, INITCAP(valor_parametro) ";
                $cadenaSql .= " FROM agora.parametro_estandar  ";
                $cadenaSql .= " WHERE clase_parametro = 'Tipo Perfil'; ";

                break;

            case "tipo_ejecucion_tiempo" :

                $cadenaSql = "SELECT id_parametro  id, pr.descripcion valor   ";
                $cadenaSql .= " FROM relacion_parametro rl ";
                $cadenaSql .= "JOIN parametros pr ON pr.rel_parametro=rl.id_rel_parametro ";
                $cadenaSql .= "WHERE rl.descripcion ='tipo_ejecucion_tiempo'; ";
                break;

            case "tipologia_contrato" :

                $cadenaSql = "SELECT id_parametro  id,pr.codigo_contraloria|| ' - ' ||pr.descripcion valor   ";
                $cadenaSql .= " FROM relacion_parametro rl ";
                $cadenaSql .= "JOIN parametros pr ON pr.rel_parametro=rl.id_rel_parametro ";
                $cadenaSql .= "WHERE rl.descripcion ='tipologia_contrato'; ";
                break;

            case "modalidad_seleccion" :

                $cadenaSql = "SELECT id_parametro  id,pr.codigo_contraloria|| ' - ' ||pr.descripcion valor   ";
                $cadenaSql .= " FROM relacion_parametro rl ";
                $cadenaSql .= "JOIN parametros pr ON pr.rel_parametro=rl.id_rel_parametro ";
                $cadenaSql .= "WHERE rl.descripcion ='modalidad_seleccion'; ";
                break;

            case "tipo_procedimiento" :

                $cadenaSql = "SELECT id_parametro  id,pr.codigo_contraloria|| ' - ' ||pr.descripcion valor   ";
                $cadenaSql .= " FROM relacion_parametro rl ";
                $cadenaSql .= "JOIN parametros pr ON pr.rel_parametro=rl.id_rel_parametro ";
                $cadenaSql .= "WHERE rl.descripcion ='procedimiento'; ";
                break;

            case "regimen_contratacion" :

                $cadenaSql = "SELECT id_parametro  id,pr.codigo_contraloria|| ' - ' ||pr.descripcion valor   ";
                $cadenaSql .= " FROM relacion_parametro rl ";
                $cadenaSql .= "JOIN parametros pr ON pr.rel_parametro=rl.id_rel_parametro ";
                $cadenaSql .= "WHERE rl.descripcion ='regimen_contratacion'; ";
                break;

            case "tipo_moneda" :

                $cadenaSql = "SELECT id_parametro  id,pr.codigo_contraloria|| ' - ' ||pr.descripcion valor   ";
                $cadenaSql .= " FROM relacion_parametro rl ";
                $cadenaSql .= "JOIN parametros pr ON pr.rel_parametro=rl.id_rel_parametro ";
                $cadenaSql .= "WHERE rl.descripcion ='tipo_moneda'; ";
                break;

            case "ordenadorGasto" :

                $cadenaSql = " SELECT ORG_IDENTIFICADOR, ORG_ORDENADOR_GASTO FROM SICAARKA.ORDENADORES_GASTO ";
                $cadenaSql.=" WHERE ORG_ESTADO='A' ";

                break;

            case "tipo_gasto" :

                $cadenaSql = "SELECT id_parametro  id,pr.codigo_contraloria|| ' - ' ||pr.descripcion valor   ";
                $cadenaSql .= " FROM relacion_parametro rl ";
                $cadenaSql .= "JOIN parametros pr ON pr.rel_parametro=rl.id_rel_parametro ";
                $cadenaSql .= "WHERE rl.descripcion ='tipo_gasto'; ";
                break;

            case "origen_recursos" :

                $cadenaSql = "SELECT id_parametro  id,pr.codigo_contraloria|| ' - ' ||pr.descripcion valor   ";
                $cadenaSql .= " FROM relacion_parametro rl ";
                $cadenaSql .= "JOIN parametros pr ON pr.rel_parametro=rl.id_rel_parametro ";
                $cadenaSql .= "WHERE rl.descripcion ='origen_recursos'; ";
                break;


            case "origen_presupuesto" :

                $cadenaSql = "SELECT id_parametro  id,pr.codigo_contraloria|| ' - ' ||pr.descripcion valor   ";
                $cadenaSql .= " FROM relacion_parametro rl ";
                $cadenaSql .= "JOIN parametros pr ON pr.rel_parametro=rl.id_rel_parametro ";
                $cadenaSql .= "WHERE rl.descripcion ='origen_presupuesto'; ";
                break;

            case "tema_gasto" :

                $cadenaSql = "SELECT id_parametro  id,pr.codigo_contraloria|| ' - ' ||pr.descripcion valor   ";
                $cadenaSql .= " FROM relacion_parametro rl ";
                $cadenaSql .= "JOIN parametros pr ON pr.rel_parametro=rl.id_rel_parametro ";
                $cadenaSql .= "WHERE rl.descripcion ='tema_gasto'; ";
                break;

            case "tipo_control" :

                $cadenaSql = "SELECT id_parametro  id,pr.codigo_contraloria|| ' - ' ||pr.descripcion valor   ";
                $cadenaSql .= " FROM relacion_parametro rl ";
                $cadenaSql .= "JOIN parametros pr ON pr.rel_parametro=rl.id_rel_parametro ";
                $cadenaSql .= "WHERE rl.descripcion ='tipo_control'; ";
                break;


            case "informacion_ordenador" :
                $cadenaSql = " 	SELECT  ORG_NOMBRE,  ORG_IDENTIFICACION ";
                $cadenaSql .= " FROM SICAARKA.ORDENADORES_GASTO  ";
                $cadenaSql .= " WHERE ORG_IDENTIFICADOR = $variable and ORG_ESTADO = 'A'";

                break;


            case "funcionarios" :

                $cadenaSql = " SELECT  FUN_IDENTIFICACION||'-'||FUN_NOMBRE , FUN_IDENTIFICACION ";
                $cadenaSql .= " ||' '|| FUN_NOMBRE  FROM SICAARKA.FUNCIONARIOS WHERE FUN_ESTADO != 'I' ";

                break;


            case "cargosFuncionarios" :

                $cadenaSql = " SELECT cargo  as data, cargo  as value ";
               $cadenaSql .= " FROM argo.cargo_supervisor_temporal ";
               $cadenaSql .= " ORDER BY data; ";
                break;


            case "interventores" :
                $cadenaSql = " SELECT ip.num_documento ||'-'||ip.nom_proveedor AS data , ip.num_documento ||'-'||ip.nom_proveedor as value from ";
                $cadenaSql.=" agora.informacion_proveedor ip, agora.informacion_persona_natural ipn ";
                $cadenaSql.=" where ip.num_documento = ipn.num_documento_persona;";
                $cadenaSql.=" ";
                break;


            case "tipo_unidad_ejecucion" :
                $cadenaSql = " SELECT id_parametro, descripcion  ";
                $cadenaSql .= " FROM parametros WHERE rel_parametro=21; ";

                break;




            case "ConsultarDescripcionParametro" :
                $cadenaSql = "SELECT descripcion ";
                $cadenaSql .= " FROM parametros ";
                $cadenaSql .= " WHERE id_parametro=" . $variable;

                break;

            case "registroActaInicio" :
                $cadenaSql = " INSERT INTO acta_inicio(";
                $cadenaSql .= " numero_contrato, vigencia,fecha_inicio,fecha_fin,usuario,descripcion) ";
                $cadenaSql .= " VALUES (";
                $cadenaSql .= "'" . $variable ['numero_contrato'] . "',";
                $cadenaSql .= $variable ['vigencia'] . ",";
                $cadenaSql .= "'" . $variable ['fecha_inicio_acta'] . "',";
                $cadenaSql .= "'" . $variable ['fecha_final_acta'] . "',";
                $cadenaSql .= "'" . $variable ['usuario'] . "',";
                $cadenaSql .= "'" . $variable ['observaciones'] . "');";

                break;

            case "obtenerPolizas" :
                $cadenaSql = "SELECT p.id_poliza,p.descripcion_poliza,p.fecha_registro,p.estado,  ";
                $cadenaSql .= " ea.nombre as nombre_aseguradora ,p.fecha_inicio,p.fecha_fin,p.numero_poliza,p.fecha_aprobacion  ";
                $cadenaSql .= "FROM argo.poliza p, core.entidad_aseguradora ea  ";
                $cadenaSql .= "WHERE p.numero_contrato = '" . $variable['numero_contrato'] . "' and p.vigencia = " . $variable['vigencia'] . " AND ";
                $cadenaSql .= " p.entidad_aseguradora = ea.id  ";
                $cadenaSql .= "ORDER BY p.id_poliza; ";
                break;
            case "obtenerAmparos" :
                $cadenaSql = "SELECT ap.*, a.nombre as nombre_amparo from amparo_poliza ap, core.amparos a ";
                $cadenaSql .= "WHERE ap.amparo = a.id AND ap.poliza  = $variable; ";
                break;


            case "insertarEstadoActaContratoGeneral" :
                $cadenaSql = " INSERT INTO contrato_estado(";
                $cadenaSql .= " numero_contrato, vigencia,fecha_registro,usuario,estado ) ";
                $cadenaSql .= " VALUES (";
                $cadenaSql .= "'" . $variable ['numero_contrato'] . "',";
                $cadenaSql .= $variable ['vigencia'] . ",";
                $cadenaSql .= "'" . $variable ['fecha'] . "',";
                $cadenaSql .= "'" . $variable ['usuario'] . "',";
                $cadenaSql .= $variable ['estado'] . ");";

                break;

            case "forma_pago" :
                $cadenaSql = " 	SELECT id_parametro, descripcion ";
                $cadenaSql .= " FROM  parametros ";
                $cadenaSql .= " WHERE rel_parametro=28;";

                break;

            case "obtenerFechadeSuscripcion" :
                $cadenaSql = " 	SELECT fecha_suscripcion ";
                $cadenaSql .= " FROM  argo.contrato_suscrito ";
                $cadenaSql .= " WHERE numero_contrato = '" . $variable['numero_contrato'] . "' AND vigencia=" . $variable['vigencia'] . ";";

                break;

            case "buscar_Informacion_proveedor_edicion" :

                $cadenaSql = " SELECT ip.*, c.nombre as nombreCiudad, b.nombre_banco as nombreBanco FROM agora.informacion_proveedor ip, core.ciudad c, core.banco b ";
                $cadenaSql .= " WHERE c.id_ciudad = ip.id_ciudad_contacto AND b.id_codigo = ip.id_entidad_bancaria AND ip.id_proveedor = $variable;";


                break;

            case "buscar_Informacion_sociedad" :

                $cadenaSql = " SELECT ip.*, c.nombre as nombreCiudad, b.nombre_banco as nombreBanco,ist.digito_verificacion, ";
                $cadenaSql .= " ir.num_documento ||'-'|| ir.nom_proveedor as inforepresentante , irs.num_documento  ||'-'|| irs.nom_proveedor as  inforepresentantesuplente    ";
                $cadenaSql .= " FROM agora.informacion_proveedor ip, core.ciudad c, core.banco b, agora.informacion_sociedad_temporal ist,  ";
                $cadenaSql .= " agora.informacion_proveedor ir , agora.informacion_proveedor irs  ";
                $cadenaSql .= " WHERE c.id_ciudad = ip.id_ciudad_contacto AND ist.id_proveedor_sociedad = ip.id_proveedor ";
                $cadenaSql .= " AND ir.id_proveedor = ist.representante AND irs.id_proveedor = ist.representante_suplente ";
                $cadenaSql .= " AND b.id_codigo = ip.id_entidad_bancaria AND ip.id_proveedor = $variable; ";

                break;

            case "buscar_participantes_sociedad" :

                $cadenaSql = " SELECT ip.num_documento||'-'||ip.nom_proveedor as participante, sp.porcentaje_participacion ";
                $cadenaSql .= " FROM agora.informacion_sociedad_participante sp , agora.informacion_proveedor ip  ";
                $cadenaSql .= " WHERE sp.id_contratista = ip.id_proveedor ";
                $cadenaSql .= " AND sp.id_proveedor_sociedad = $variable; ";

                break;
        }
        return $cadenaSql;
    }

}

?>

