<?php

namespace hojaDeVida\crearDocente;

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
        	
        	case "salarioMinimoVigente" :
        		$year = date('Y');
        		$cadenaSql = "SELECT * FROM core.salario_minimo ";
        		$cadenaSql.= "WHERE vigencia = " . $year . " LIMIT 1 ";
        		break;

            case "ordenadorUdistritalListAjax" :
                $cadenaSql = " SELECT DISTINCT ON (CR.id) CR.id , ('(' ||JD.tercero_id || ') - ' || UPPER(CR.cargo) ) as ordenador , DP.nombre, JD.fecha_inicio, JD.fecha_fin, JD.tercero_id";
                $cadenaSql.=" FROM core.ordenador_gasto CR";
                $cadenaSql.=" JOIN oikos.dependencia DP ON CR.dependencia_id = DP.id";
                $cadenaSql.=" JOIN core.jefe_dependencia JD ON JD.dependencia_id = DP.id";
                $cadenaSql.=" GROUP BY CR.id, CR.cargo, DP.nombre, JD.tercero_id, JD.fecha_inicio, JD.fecha_fin";
                $cadenaSql.=" ORDER BY CR.id, CR.cargo, JD.fecha_fin DESC";
                break;

            case "requisitosNecesidad" :
                $cadenaSql = "SELECT * FROM CO.CO_SOL_REQUISITO WHERE VIGENCIA=" . $variable ['vigencia'] . " and ";
                $cadenaSql.= "NUM_SOL_ADQ=" . $variable ['numero_disponibilidad'] . " ORDER BY ITEM";
                break;

            case "obtenerInfoNec" :
                $cadenaSql = " SELECT SN.NUM_SOL_ADQ, SCDP.ID_SOL_CDP, CDP.NUMERO_DISPONIBILIDAD,SN.VIGENCIA, CDP.ID_RESPONSABLE_PRESUPUESTO, CDP.ID_SOLICITANTE ,DP.NOMBRE_DEPENDENCIA,DE.OBSERVACIONES, ";
                $cadenaSql .= " SN.ESTADO, SN.JUSTIFICACION, SN.OBJETO,SN.VALOR_CONTRATACION,CDP.ESTADO as ESTADOCDP , CDP.FECHA_REGISTRO,SN.RUBRO_INTERNO, RB.DESCRIPCION, ";
                $cadenaSql .= " DP2.NOMBRE_DEPENDENCIA as DEP_DESTINO";
                $cadenaSql .= " FROM CO.CO_SOL_CDP SCDP, PR.PR_DISPONIBILIDADES CDP , CO.CO_DEPENDENCIAS DP, CO.CO_DEPENDENCIAS DP2, PR.PR_RUBRO RB, CO.CO_SOLICITUD_ADQ SN   ";
                $cadenaSql .= " LEFT JOIN CO.CO_DTLLE_SOL_ADQ_S DE ON  DE.VIGENCIA = SN.VIGENCIA and DE.NUM_SOL_ADQ = SN.NUM_SOL_ADQ   ";
                $cadenaSql .= " WHERE SN.NUM_SOL_ADQ = SCDP.NUM_SOL_ADQ and SN.VIGENCIA = SCDP.VIGENCIA and SN.DEPENDENCIA = DP.COD_DEPENDENCIA and SN.DEPENDENCIA_DESTINO = DP2.COD_DEPENDENCIA";
                $cadenaSql .= " and SN.VIGENCIA = RB.VIGENCIA and SN.RUBRO_INTERNO = RB.INTERNO  ";
                $cadenaSql .= " and CDP.VIGENCIA = SCDP.VIGENCIA and CDP.NUM_SOL_ADQ = SCDP.ID_SOL_CDP and CDP.CODIGO_COMPANIA = SCDP.CODIGO_COMPANIA  ";
                $cadenaSql .= " and CDP.VIGENCIA = SCDP.VIGENCIA and SN.VIGENCIA=" . $variable ['vigencia'] . " and ";
                $cadenaSql .= "  SN.CODIGO_UNIDAD_EJECUTORA='0" . $variable ['unidad_ejecutora'] . "' and SN.NUM_SOL_ADQ=" . $variable ['numero_disponibilidad'] . " ";
                $cadenaSql .= "  ORDER BY SN.NUM_SOL_ADQ ";
                break;

            case "obtenerInfoNecOrdenador" :
                $cadenaSql = " SELECT SN.NUM_SOL_ADQ, SCDP.ID_SOL_CDP, CDP.NUMERO_DISPONIBILIDAD,SN.VIGENCIA, CDP.ID_RESPONSABLE_PRESUPUESTO, CDP.ID_SOLICITANTE, PERS.NOMBRES, PERS.CARGO,";
                $cadenaSql .= " PERS.PRIMER_APELLIDO, PERS.SEGUNDO_APELLIDO, PERS.NUMERO_DOCUMENTO, PERS.OFICINA ,DP.NOMBRE_DEPENDENCIA,DE.OBSERVACIONES, ";
                $cadenaSql .= " SN.ESTADO, SN.JUSTIFICACION, SN.OBJETO,SN.VALOR_CONTRATACION,CDP.ESTADO as ESTADOCDP , CDP.FECHA_REGISTRO,SN.RUBRO_INTERNO, RB.DESCRIPCION, ";
                $cadenaSql .= " DP2.NOMBRE_DEPENDENCIA as DEP_DESTINO";
                $cadenaSql .= " FROM CO.CO_SOL_CDP SCDP, PR.PR_DISPONIBILIDADES CDP, PR.PR_PERSONAS_CDP PERS , CO.CO_DEPENDENCIAS DP, CO.CO_DEPENDENCIAS DP2, PR.PR_RUBRO RB, CO.CO_SOLICITUD_ADQ SN ";
                $cadenaSql .= " LEFT JOIN CO.CO_DTLLE_SOL_ADQ_S DE ON DE.VIGENCIA = SN.VIGENCIA and DE.NUM_SOL_ADQ = SN.NUM_SOL_ADQ ";
                $cadenaSql .= " WHERE SN.NUM_SOL_ADQ = SCDP.NUM_SOL_ADQ and SN.VIGENCIA = SCDP.VIGENCIA and SN.DEPENDENCIA = DP.COD_DEPENDENCIA and SN.DEPENDENCIA_DESTINO = DP2.COD_DEPENDENCIA";
                $cadenaSql .= " and SN.VIGENCIA = RB.VIGENCIA and SN.RUBRO_INTERNO = RB.INTERNO and CDP.ID_SOLICITANTE = PERS.SECUENCIAL";
                $cadenaSql .= " and CDP.VIGENCIA = SCDP.VIGENCIA and CDP.NUM_SOL_ADQ = SCDP.ID_SOL_CDP and CDP.CODIGO_COMPANIA = SCDP.CODIGO_COMPANIA ";
                $cadenaSql .= " and CDP.VIGENCIA = SCDP.VIGENCIA and SN.VIGENCIA=" . $variable ['vigencia'] . " and ";
                $cadenaSql .= "  SN.CODIGO_UNIDAD_EJECUTORA='0" . $variable ['unidad_ejecutora'] . "' and SN.NUM_SOL_ADQ=" . $variable ['numero_disponibilidad'] . " ";
                $cadenaSql .= "  ORDER BY SN.NUM_SOL_ADQ ";
                break;

            case "obtenerInfoCdp" :
                $cadenaSql = " SELECT SN.NUM_SOL_ADQ, SCDP.ID_SOL_CDP, CDP.NUMERO_DISPONIBILIDAD,SN.VIGENCIA ,DP.NOMBRE_DEPENDENCIA,DE.OBSERVACIONES, ";
                $cadenaSql .= " SN.ESTADO, SN.JUSTIFICACION, SN.OBJETO,SN.VALOR_CONTRATACION,CDP.ESTADO as ESTADOCDP , CDP.FECHA_REGISTRO,SN.RUBRO_INTERNO, RB.DESCRIPCION ";
                $cadenaSql .= " FROM CO.CO_SOL_CDP SCDP, PR.PR_DISPONIBILIDADES CDP , CO.CO_DEPENDENCIAS DP, PR.PR_RUBRO RB, CO.CO_SOLICITUD_ADQ SN   ";
                $cadenaSql .= " LEFT JOIN CO.CO_DTLLE_SOL_ADQ_S DE ON  DE.VIGENCIA = SN.VIGENCIA and DE.NUM_SOL_ADQ = SN.NUM_SOL_ADQ   ";
                $cadenaSql .= " WHERE SN.NUM_SOL_ADQ = SCDP.NUM_SOL_ADQ and SN.VIGENCIA = SCDP.VIGENCIA and SN.DEPENDENCIA = DP.COD_DEPENDENCIA ";
                $cadenaSql .= " and SN.VIGENCIA = RB.VIGENCIA and SN.RUBRO_INTERNO = RB.INTERNO  ";
                $cadenaSql .= " and CDP.VIGENCIA = SCDP.VIGENCIA and CDP.NUM_SOL_ADQ = SCDP.ID_SOL_CDP and CDP.CODIGO_COMPANIA = SCDP.CODIGO_COMPANIA  ";
                $cadenaSql .= " and CDP.VIGENCIA = SCDP.VIGENCIA and SN.VIGENCIA=" . $variable ['vigencia'] . " and ";
                $cadenaSql .= "  SN.CODIGO_UNIDAD_EJECUTORA='0" . $variable ['unidad_ejecutora'] . "' and CDP.NUMERO_DISPONIBILIDAD=" . $variable ['numero_disponibilidad'] . " ";
                $cadenaSql .= "  ORDER BY SN.NUM_SOL_ADQ ";
                break;

            case "obtener_necesidades_vigencia" :
                $cadenaSql = " SELECT SN.NUM_SOL_ADQ as valor, SN.NUM_SOL_ADQ as informacion ";
                $cadenaSql .= " FROM CO.CO_SOLICITUD_ADQ SN, CO.CO_SOL_CDP SCDP, PR.PR_DISPONIBILIDADES CDP  ";
                $cadenaSql .= " WHERE SN.NUM_SOL_ADQ = SCDP.NUM_SOL_ADQ and SN.VIGENCIA = SCDP.VIGENCIA ";
                $cadenaSql .= " and CDP.VIGENCIA = SCDP.VIGENCIA and CDP.NUM_SOL_ADQ = SCDP.ID_SOL_CDP and CDP.CODIGO_COMPANIA = SCDP.CODIGO_COMPANIA  ";
                $cadenaSql .= " and CDP.VIGENCIA = SCDP.VIGENCIA and SN.VIGENCIA=" . $variable ['vigencia'] . " and SN.NUM_SOL_ADQ NOT IN (" . $variable ['cdps_seleccion'] . ") and ";
                $cadenaSql .= "  SN.CODIGO_UNIDAD_EJECUTORA='0" . $variable ['unidad_ejecutora'] . "' ";
                $cadenaSql .= "  ORDER BY SN.NUM_SOL_ADQ ";
                break;

            case "obtener_cdps_vigencia" :
                $cadenaSql = " SELECT CDP.NUMERO_DISPONIBILIDAD as valor, CDP.NUMERO_DISPONIBILIDAD as informacion ";
                $cadenaSql .= " FROM CO.CO_SOLICITUD_ADQ SN, CO.CO_SOL_CDP SCDP, PR.PR_DISPONIBILIDADES CDP  ";
                $cadenaSql .= " WHERE SN.NUM_SOL_ADQ = SCDP.NUM_SOL_ADQ and SN.VIGENCIA = SCDP.VIGENCIA ";
                $cadenaSql .= " and CDP.VIGENCIA = SCDP.VIGENCIA and CDP.NUM_SOL_ADQ = SCDP.ID_SOL_CDP and CDP.CODIGO_COMPANIA = SCDP.CODIGO_COMPANIA  ";
                $cadenaSql .= " and CDP.VIGENCIA = SCDP.VIGENCIA and SN.VIGENCIA=" . $variable ['vigencia'] . " and CDP.NUMERO_DISPONIBILIDAD NOT IN (" . $variable ['cdps_seleccion'] . ") and ";
                $cadenaSql .= "  SN.CODIGO_UNIDAD_EJECUTORA='0" . $variable ['unidad_ejecutora'] . "' ";
                $cadenaSql .= "  ORDER BY CDP.NUMERO_DISPONIBILIDAD ";

                break;


            case "vigencias_sica_disponibilidades" :
                $year = date('Y');
                $cadenaSql = " SELECT DISTINCT SN.VIGENCIA AS valor, SN.VIGENCIA AS informacion  FROM CO.CO_SOLICITUD_ADQ SN WHERE SN.VIGENCIA = " . $year;
                $cadenaSql .= " ORDER BY SN.VIGENCIA DESC ";

                break;


            case "consultarTipoFormaPagoByNumPush" :
                $cadenaSql = " SELECT id, nombre";
                $cadenaSql.=" FROM agora.tipo_condicion WHERE estado = TRUE AND id = " . $variable . " ;";
                break;

            case "tipoFormaPagoUdistrital" :
                $cadenaSql = " SELECT id, nombre";
                $cadenaSql.=" FROM agora.tipo_condicion WHERE estado = TRUE;";
                break;

            case "medioPagoUdistrital" :
                $cadenaSql = " SELECT id, nombre";
                $cadenaSql.=" FROM core.medio_pago WHERE estado = TRUE;";
                break;


            case "formaSeleccionUdistrital" :
                $cadenaSql = "SELECT id, nombre";
                $cadenaSql.=" FROM agora.forma_seleccion WHERE estado = TRUE";
                break;

            case "tipoNecesidadAdministrativa" :
                $cadenaSql = " SELECT id, nombre";
                $cadenaSql.=" FROM administrativa.tipo_necesidad WHERE estado = TRUE;";
                break;

            case "ordenadorUdistrital" :
                $cadenaSql = " SELECT DISTINCT ON (CR.id) CR.id , ('(' ||JD.tercero_id || ') - ' || UPPER(CR.cargo) ) as ordenador , DP.nombre, JD.fecha_inicio, JD.fecha_fin";
                $cadenaSql.=" FROM core.ordenador_gasto CR";
                $cadenaSql.=" JOIN oikos.dependencia DP ON CR.dependencia_id = DP.id";
                $cadenaSql.=" JOIN core.jefe_dependencia JD ON JD.dependencia_id = DP.id";
                $cadenaSql.=" GROUP BY CR.id, CR.cargo, DP.nombre, JD.tercero_id, JD.fecha_inicio, JD.fecha_fin";
                $cadenaSql.=" ORDER BY CR.id, CR.cargo, JD.fecha_fin DESC";
                break;

            case "dependenciaUdistrital" :
                $cadenaSql = " SELECT DISTINCT ON (D.id) D.id, (UPPER(D.nombre) || ' - (' || JD.tercero_id || ')') as jefe, JD.tercero_id, JD.fecha_inicio, JD.fecha_fin";
                $cadenaSql.=" FROM oikos.dependencia D";
                $cadenaSql.=" JOIN core.jefe_dependencia JD ON JD.dependencia_id = D.id";
                $cadenaSql.=" GROUP BY D.id, JD.tercero_id, D.nombre, JD.tercero_id, JD.fecha_inicio, JD.fecha_fin";
                $cadenaSql.=" ORDER BY D.id, D.nombre, JD.fecha_fin DESC";
                break;

            case "ordenadorUdistritalById" :
                $cadenaSql = " SELECT DISTINCT ON (CR.id) CR.id , ('(' ||JD.tercero_id || ') - ' || UPPER(CR.cargo) ) as ordenador , DP.nombre, JD.fecha_inicio, JD.fecha_fin";
                $cadenaSql.=" FROM core.ordenador_gasto CR";
                $cadenaSql.=" JOIN oikos.dependencia DP ON CR.dependencia_id = DP.id";
                $cadenaSql.=" JOIN core.jefe_dependencia JD ON JD.dependencia_id = DP.id";
                $cadenaSql.=" WHERE CR.id = " . $variable;
                $cadenaSql.=" GROUP BY CR.id, CR.cargo, DP.nombre, JD.tercero_id, JD.fecha_inicio, JD.fecha_fin";
                $cadenaSql.=" ORDER BY CR.id, CR.cargo, JD.fecha_fin DESC";
                break;

            case "dependenciaUdistritalById" :
                $cadenaSql = " SELECT DISTINCT ON (D.id) D.id, (UPPER(D.nombre)) as jefe, JD.tercero_id, JD.fecha_inicio, JD.fecha_fin";
                $cadenaSql.=" FROM oikos.dependencia D";
                $cadenaSql.=" JOIN core.jefe_dependencia JD ON JD.dependencia_id = D.id";
                $cadenaSql.=" WHERE D.id = " . $variable;
                $cadenaSql.=" GROUP BY D.id, JD.tercero_id, D.nombre, JD.tercero_id, JD.fecha_inicio, JD.fecha_fin";
                $cadenaSql.=" ORDER BY D.id, D.nombre, JD.fecha_fin DESC";
                break;

            case "buscarDependenciaOikosById" :
                $cadenaSql = "SELECT";
                $cadenaSql .= "	*";
                $cadenaSql .= " FROM ";
                $cadenaSql .= " oikos.dependencia";
                $cadenaSql .= " WHERE id = " . $variable;
                break;


            case "buscarEstadoJefeDependencia" :
                $cadenaSql = "SELECT";
                $cadenaSql .= "	*";
                $cadenaSql .= " FROM ";
                $cadenaSql .= " core.jefe_dependencia";
                $cadenaSql .= " WHERE tercero_id = " . $variable . " ORDER BY fecha_fin DESC LIMIT 1; ";
                break;

            case "buscarSolicitante" :
                $cadenaSql = "SELECT";
                $cadenaSql .= "	cargo";
                $cadenaSql .= " FROM ";
                $cadenaSql .= " argo.cargo_supervisor_temporal";
                $cadenaSql .= " WHERE id = " . $variable;
                break;


            case "solicitante" :
                $cadenaSql = "SELECT";
                $cadenaSql .= " id,";
                $cadenaSql .= "	cargo";
                $cadenaSql .= " FROM ";
                $cadenaSql .= " argo.cargo_supervisor_temporal";
                $cadenaSql .= " ORDER BY cargo";
                break;


            case "buscarAreaConocimiento" :
                $cadenaSql = "SELECT ";
                $cadenaSql .= " id_area,";
                $cadenaSql .= " nombre";
                $cadenaSql .= " FROM ";
                $cadenaSql .= " core.snies_area ";
                $cadenaSql .= " WHERE estado != 'INACTIVO';";
                break;

            case "buscarAreaConocimientoXNBC" :
                $cadenaSql = "SELECT ";
                $cadenaSql .= " id_area,";
                $cadenaSql .= " nombre";
                $cadenaSql .= " FROM ";
                $cadenaSql .= " core.snies_nucleo_basico ";
                $cadenaSql .= " WHERE id_nucleo = " . $variable . ";";
                break;

            case "buscarNBCAjax" :
                $cadenaSql = "SELECT ";
                $cadenaSql .= " id_nucleo,";
                $cadenaSql .= " nombre";
                $cadenaSql .= " FROM ";
                $cadenaSql .= " core.snies_nucleo_basico ";
                $cadenaSql .= " WHERE estado != 'INACTIVO'";
                $cadenaSql .= " AND id_area = " . $variable . ";";
                break;

            case "buscarNBC" :
                $cadenaSql = "SELECT ";
                $cadenaSql .= " id_nucleo,";
                $cadenaSql .= " nombre";
                $cadenaSql .= " FROM ";
                $cadenaSql .= " core.snies_nucleo_basico ";
                $cadenaSql .= " WHERE estado != 'INACTIVO'";
                $cadenaSql .= " AND id_nucleo = " . $variable . ";";
                break;

            case "buscarUsuario" ://****************************************************************************
                $cadenaSql = " SELECT";
                $cadenaSql.=" *";
                $cadenaSql.=" FROM ";
                $cadenaSql.=" prov_usuario";
                $cadenaSql.=" WHERE id_usuario = '" . $variable . "'";
                break;

            /* CONSULTAR siCapital */
            case "listaSolicitudNecesidad" :
                $cadenaSql = "SELECT *";
                $cadenaSql .= " FROM ";
                $cadenaSql .= " CO_SOLICITUD_ADQ";
                $cadenaSql .= " WHERE VIGENCIA = " . $variable . "";
                break;

            case "listaSolicitudNecesidadXVigencia" :
                $cadenaSql = " SELECT ";
                $cadenaSql.=" P.NUM_SOL_ADQ as NUM_SOL_ADQ,";
                $cadenaSql.=" P.VIGENCIA as VIGENCIA,";
                $cadenaSql.=" U.NOMBRE_DEPENDENCIA as DEPENDENCIA,";
                $cadenaSql.=" F.FUN_NOMBRE as FUNCIONARIO,";
                $cadenaSql.=" F.FUN_CARGO as FUNCIONARIO_CARGO,";
                $cadenaSql.=" F.FUN_TIPO as FUNCIONARIO_TIPO,";
                $cadenaSql.=" P.FECHA_SOLICITUD as FECHA_SOLICITUD,";
                $cadenaSql.=" P.ORIGEN_SOLICITUD as ORIGEN_SOLICITUD,";
                $cadenaSql.=" V.NOMBRE_DEPENDENCIA as DEPENDENCIA_DESTINO,";
                $cadenaSql.=" P.JUSTIFICACION as JUSTIFICACION,";
                $cadenaSql.=" P.CONDICIONES_CONTRATACION as CONDICIONES,";
                $cadenaSql.=" P.VALOR_CONTRATACION as VALOR_CONTRATACION,";
                $cadenaSql.=" P.OBJETO as OBJETO,";
                $cadenaSql.=" P.TIPO_CONTRATACION as TIPO_CONTRATACION,";
                $cadenaSql.=" P.PLAZO_EJECUCION as PLAZO_EJECUCION,";
                $cadenaSql.=" P.ELABORADO_POR as ELABORADO_POR,";
                $cadenaSql.=" O.ORG_NOMBRE as ORDENADOR_GASTO,";
                $cadenaSql.=" O.ORG_ORDENADOR_GASTO as CARGO_ORDENADOR_GASTO,";
                $cadenaSql.=" P.CODIGO_UNIDAD_EJECUTORA as CODIGO_UNIDAD_EJECUTORA,";
                $cadenaSql.=" P.ESTADO as ESTADO";
                $cadenaSql.=" FROM ";
                $cadenaSql.=" CO_SOLICITUD_ADQ P";
                $cadenaSql.=" JOIN CO_DEPENDENCIAS U ON U.COD_DEPENDENCIA = P.DEPENDENCIA";
                $cadenaSql.=" JOIN CO_DEPENDENCIAS V ON V.COD_DEPENDENCIA = P.DEPENDENCIA_DESTINO";
                $cadenaSql.=" JOIN SICAARKA.FUNCIONARIOS F ON F.FUN_IDENTIFICADOR = P.FUNCIONARIO";
                $cadenaSql.=" JOIN SICAARKA.ORDENADORES_GASTO O ON O.ORG_TIPO_ORDENADOR = P.CODIGO_ORDENADOR AND O.ORG_ESTADO = 'A'";
                $cadenaSql.=" WHERE P.VIGENCIA = " . $variable ['vigencia'];
                $cadenaSql.=" AND P.NUM_SOL_ADQ NOT IN (" . $variable ['solicitudes'] . ")";
                $cadenaSql.=" AND P.CODIGO_UNIDAD_EJECUTORA = " . $variable ['unidadEjecutora'];
                $cadenaSql.=" ORDER BY P.NUM_SOL_ADQ DESC";
                break;

            case "listaSolicitudNecesidadXNumSolicitud" :
                $cadenaSql = " SELECT ";
                $cadenaSql .= " P.NUM_SOL_ADQ as NUM_SOL_ADQ,";
                $cadenaSql .= " P.VIGENCIA as VIGENCIA,";
                $cadenaSql .= " U.NOMBRE_DEPENDENCIA as DEPENDENCIA,";
                $cadenaSql .= " F.FUN_NOMBRE as FUNCIONARIO,";
                $cadenaSql .= " F.FUN_CARGO as FUNCIONARIO_CARGO,";
                $cadenaSql .= " F.FUN_TIPO as FUNCIONARIO_TIPO,";
                $cadenaSql .= " P.FECHA_SOLICITUD as FECHA_SOLICITUD,";
                $cadenaSql .= " P.ORIGEN_SOLICITUD as ORIGEN_SOLICITUD,";
                $cadenaSql .= " V.NOMBRE_DEPENDENCIA as DEPENDENCIA_DESTINO,";
                $cadenaSql .= " P.JUSTIFICACION as JUSTIFICACION,";
                $cadenaSql .= " P.CONDICIONES_CONTRATACION as CONDICIONES,";
                $cadenaSql .= " P.VALOR_CONTRATACION as VALOR_CONTRATACION,";
                $cadenaSql .= " P.OBJETO as OBJETO,";
                $cadenaSql .= " P.TIPO_CONTRATACION as TIPO_CONTRATACION,";
                $cadenaSql .= " P.PLAZO_EJECUCION as PLAZO_EJECUCION,";
                $cadenaSql .= " P.ELABORADO_POR as ELABORADO_POR,";
                $cadenaSql .= " O.ORG_NOMBRE as ORDENADOR_GASTO,";
                $cadenaSql .= " O.ORG_ORDENADOR_GASTO as CARGO_ORDENADOR_GASTO,";
                $cadenaSql .= " P.CODIGO_UNIDAD_EJECUTORA as CODIGO_UNIDAD_EJECUTORA,";
                $cadenaSql .= " P.ESTADO as ESTADO";
                $cadenaSql .= " FROM ";
                $cadenaSql .= " CO_SOLICITUD_ADQ P";
                $cadenaSql .= " JOIN CO_DEPENDENCIAS U ON U.COD_DEPENDENCIA = P.DEPENDENCIA";
                $cadenaSql .= " JOIN CO_DEPENDENCIAS V ON V.COD_DEPENDENCIA = P.DEPENDENCIA_DESTINO";
                $cadenaSql .= " JOIN SICAARKA.FUNCIONARIOS F ON F.FUN_IDENTIFICADOR = P.FUNCIONARIO";
                $cadenaSql .= " JOIN SICAARKA.ORDENADORES_GASTO O ON O.ORG_TIPO_ORDENADOR = P.CODIGO_ORDENADOR AND O.ORG_ESTADO = 'A'";
                $cadenaSql .= " WHERE P.NUM_SOL_ADQ = " . $variable['idSolicitud'];
                $cadenaSql .= " AND P.VIGENCIA = " . $variable['vigencia'];
                $cadenaSql .= " AND P.CODIGO_UNIDAD_EJECUTORA = " . $variable ['unidadEjecutora'];
                break;

            case "listarObjetosSinCotizacionXVigencia" :
                $cadenaSql = " SELECT ";
                $cadenaSql.=" string_agg(cast(numero_solicitud as text),',' ";
                $cadenaSql.=" ORDER BY ";
                $cadenaSql.=" numero_solicitud) ";
                $cadenaSql.=" FROM ";
                $cadenaSql.=" agora.objeto_contratar ";
                $cadenaSql.=" WHERE ";
                $cadenaSql.=" vigencia = " . $variable['vigencia'];
                $cadenaSql.=" AND unidad_ejecutora = " . $variable ['unidadEjecutora'];
                $cadenaSql.=" AND ";
                $cadenaSql.=" estado = 'CREADO';";
                break;

            case "listarObjetosRelacionadosXVigencia" :
                $cadenaSql = " SELECT ";
                $cadenaSql .= " string_agg(cast(numero_solicitud as text),',' ";
                $cadenaSql .= " ORDER BY ";
                $cadenaSql .= " numero_solicitud) ";
                $cadenaSql .= " FROM ";
                $cadenaSql .= " agora.objeto_contratar ";
                $cadenaSql .= " WHERE ";
                $cadenaSql .= " vigencia = " . $variable['vigencia'];
                $cadenaSql .= " AND unidad_ejecutora = " . $variable ['unidadEjecutora'] . ";";
                break;


            case "listarObjetosConCotizacionXVigencia" :
                $cadenaSql = " SELECT ";
                $cadenaSql .= " string_agg(cast(numero_solicitud as text),',' ";
                $cadenaSql .= " ORDER BY ";
                $cadenaSql .= " numero_solicitud) ";
                $cadenaSql .= " FROM ";
                $cadenaSql .= " agora.objeto_contratar ";
                $cadenaSql .= " WHERE ";
                $cadenaSql .= " vigencia = " . $variable['vigencia'];
                $cadenaSql .= " AND unidad_ejecutora = " . $variable ['unidadEjecutora'];
                $cadenaSql .= " AND ";
                $cadenaSql .= " estado = 'COTIZACION';";
                break;

            case "listaSolicitudNecesidadXNumSolicitudSinCotizar" :
                $cadenaSql = " SELECT ";
                $cadenaSql .= " P.NUM_SOL_ADQ as NUM_SOL_ADQ,";
                $cadenaSql .= " P.VIGENCIA as VIGENCIA,";
                $cadenaSql .= " U.NOMBRE_DEPENDENCIA as DEPENDENCIA,";
                $cadenaSql .= " F.FUN_NOMBRE as FUNCIONARIO,";
                $cadenaSql .= " F.FUN_CARGO as FUNCIONARIO_CARGO,";
                $cadenaSql .= " F.FUN_TIPO as FUNCIONARIO_TIPO,";
                $cadenaSql .= " P.FECHA_SOLICITUD as FECHA_SOLICITUD,";
                $cadenaSql .= " P.ORIGEN_SOLICITUD as ORIGEN_SOLICITUD,";
                $cadenaSql .= " V.NOMBRE_DEPENDENCIA as DEPENDENCIA_DESTINO,";
                $cadenaSql .= " P.JUSTIFICACION as JUSTIFICACION,";
                $cadenaSql .= " P.CONDICIONES_CONTRATACION as CONDICIONES,";
                $cadenaSql .= " P.VALOR_CONTRATACION as VALOR_CONTRATACION,";
                $cadenaSql .= " P.OBJETO as OBJETO,";
                $cadenaSql .= " P.TIPO_CONTRATACION as TIPO_CONTRATACION,";
                $cadenaSql .= " P.PLAZO_EJECUCION as PLAZO_EJECUCION,";
                $cadenaSql .= " P.ELABORADO_POR as ELABORADO_POR,";
                $cadenaSql .= " O.ORG_NOMBRE as ORDENADOR_GASTO,";
                $cadenaSql .= " O.ORG_ORDENADOR_GASTO as CARGO_ORDENADOR_GASTO,";
                $cadenaSql .= " P.CODIGO_UNIDAD_EJECUTORA as CODIGO_UNIDAD_EJECUTORA,";
                $cadenaSql .= " P.ESTADO as ESTADO";
                $cadenaSql .= " FROM ";
                $cadenaSql .= " CO_SOLICITUD_ADQ P";
                $cadenaSql .= " JOIN CO_DEPENDENCIAS U ON U.COD_DEPENDENCIA = P.DEPENDENCIA";
                $cadenaSql .= " JOIN CO_DEPENDENCIAS V ON V.COD_DEPENDENCIA = P.DEPENDENCIA_DESTINO";
                $cadenaSql .= " JOIN SICAARKA.FUNCIONARIOS F ON F.FUN_IDENTIFICADOR = P.FUNCIONARIO";
                $cadenaSql .= " JOIN SICAARKA.ORDENADORES_GASTO O ON O.ORG_TIPO_ORDENADOR = P.CODIGO_ORDENADOR AND O.ORG_ESTADO = 'A'";
                $cadenaSql .= " WHERE P.NUM_SOL_ADQ IN (" . $variable ['solicitudes'] . ")";
                $cadenaSql .= " AND P.VIGENCIA = " . $variable ['vigencia'];
                $cadenaSql .= " AND P.CODIGO_UNIDAD_EJECUTORA = " . $variable ['unidadEjecutora'];
                break;

            case "listaSolicitudNecesidadXNumSolicitudEnCotizar" :
                $cadenaSql = " SELECT ";
                $cadenaSql .= " P.NUM_SOL_ADQ as NUM_SOL_ADQ,";
                $cadenaSql .= " P.VIGENCIA as VIGENCIA,";
                $cadenaSql .= " U.NOMBRE_DEPENDENCIA as DEPENDENCIA,";
                $cadenaSql .= " F.FUN_NOMBRE as FUNCIONARIO,";
                $cadenaSql .= " F.FUN_CARGO as FUNCIONARIO_CARGO,";
                $cadenaSql .= " F.FUN_TIPO as FUNCIONARIO_TIPO,";
                $cadenaSql .= " P.FECHA_SOLICITUD as FECHA_SOLICITUD,";
                $cadenaSql .= " P.ORIGEN_SOLICITUD as ORIGEN_SOLICITUD,";
                $cadenaSql .= " V.NOMBRE_DEPENDENCIA as DEPENDENCIA_DESTINO,";
                $cadenaSql .= " P.JUSTIFICACION as JUSTIFICACION,";
                $cadenaSql .= " P.CONDICIONES_CONTRATACION as CONDICIONES,";
                $cadenaSql .= " P.VALOR_CONTRATACION as VALOR_CONTRATACION,";
                $cadenaSql .= " P.OBJETO as OBJETO,";
                $cadenaSql .= " P.TIPO_CONTRATACION as TIPO_CONTRATACION,";
                $cadenaSql .= " P.PLAZO_EJECUCION as PLAZO_EJECUCION,";
                $cadenaSql .= " P.ELABORADO_POR as ELABORADO_POR,";
                $cadenaSql .= " O.ORG_NOMBRE as ORDENADOR_GASTO,";
                $cadenaSql .= " O.ORG_ORDENADOR_GASTO as CARGO_ORDENADOR_GASTO,";
                $cadenaSql .= " P.CODIGO_UNIDAD_EJECUTORA as CODIGO_UNIDAD_EJECUTORA,";
                $cadenaSql .= " P.ESTADO as ESTADO";
                $cadenaSql .= " FROM ";
                $cadenaSql .= " CO_SOLICITUD_ADQ P";
                $cadenaSql .= " JOIN CO_DEPENDENCIAS U ON U.COD_DEPENDENCIA = P.DEPENDENCIA";
                $cadenaSql .= " JOIN CO_DEPENDENCIAS V ON V.COD_DEPENDENCIA = P.DEPENDENCIA_DESTINO";
                $cadenaSql .= " JOIN SICAARKA.FUNCIONARIOS F ON F.FUN_IDENTIFICADOR = P.FUNCIONARIO";
                $cadenaSql .= " JOIN SICAARKA.ORDENADORES_GASTO O ON O.ORG_TIPO_ORDENADOR = P.CODIGO_ORDENADOR AND O.ORG_ESTADO = 'A'";
                $cadenaSql .= " WHERE P.NUM_SOL_ADQ IN (" . $variable ['solicitudes'] . ")";
                $cadenaSql .= " AND P.VIGENCIA = " . $variable ['vigencia'];
                $cadenaSql .= " AND P.CODIGO_UNIDAD_EJECUTORA = " . $variable ['unidadEjecutora'];
                break;

            case "estadoSolicitudAgora" :
                $cadenaSql = " SELECT ";
                $cadenaSql .= " estado ";
                $cadenaSql .= " FROM ";
                $cadenaSql .= " agora.objeto_contratar ";
                $cadenaSql .= " WHERE numero_solicitud = " . $variable['idSolicitud'];
                $cadenaSql .= " AND vigencia = " . $variable['vigencia'];
                $cadenaSql .= " AND unidad_ejecutora = " . $variable['unidadEjecutora'];
                break;

            case "informacionSolicitudAgora" :
                $cadenaSql = " SELECT ";
                $cadenaSql .= " id_objeto, ";
                $cadenaSql .= " fecharegistro, ";
                $cadenaSql .= " id_unidad, ";
                $cadenaSql .= " cantidad, ";
                $cadenaSql .= " numero_cotizaciones, ";
                $cadenaSql .= " fechasolicitudcotizacion, ";
                $cadenaSql .= " tipo_necesidad, ";
                $cadenaSql .= " estado ";
                $cadenaSql .= " FROM ";
                $cadenaSql .= " agora.objeto_contratar ";
                $cadenaSql .= " WHERE numero_solicitud = " . $variable ['idSolicitud'];
                $cadenaSql .= " AND vigencia = " . $variable ['vigencia'];
                $cadenaSql .= " AND unidad_ejecutora = " . $variable['unidadEjecutora'];
                $cadenaSql .= " LIMIT 1; ";
                break;


            case "informacionCIIURelacionada" :
                $cadenaSql = " SELECT D.id_division as num_division, P.id_clase as num_clase, C.id_subclase as num_subclase";
                $cadenaSql.=" FROM agora.objeto_contratar T";
                $cadenaSql.=" JOIN agora.ciiu_subclase C ON C.id_subclase = T.codigociiu";
                $cadenaSql.=" JOIN agora.ciiu_clase P ON P.id_clase = C.clase";
                $cadenaSql.=" JOIN agora.ciiu_division D ON D.id_division = P.division";
                $cadenaSql .= " WHERE T.numero_solicitud = " . $variable ['idSolicitud'];
                $cadenaSql .= " AND T.vigencia = " . $variable ['vigencia'];
                $cadenaSql .= " AND T.unidad_ejecutora = " . $variable ['unidadEjecutora'];
                $cadenaSql .= " LIMIT 1; ";
                break;


            case "filtroVigencia" :
                $cadenaSql = " SELECT ";
                $cadenaSql.=" VIGENCIA AS ID, ";
                $cadenaSql.=" VIGENCIA AS VIGENCIA ";
                $cadenaSql.=" FROM ";
                $cadenaSql.=" CO_SOLICITUD_ADQ ";
                $cadenaSql.=" GROUP BY VIGENCIA ";
                $cadenaSql.=" ORDER BY VIGENCIA";
                break;

            /* CONSULTAR ACTIVIDADES DE LA NECESIDAD */
            case "consultarActividades" : // ********************************************************************
                $cadenaSql = " SELECT";
                $cadenaSql .= " A.id_subclase,";
                $cadenaSql .= " nombre";
                $cadenaSql .= " FROM ";
                $cadenaSql .= " agora.objeto_cotizacion_actividad_ciiu A";
                $cadenaSql .= " JOIN core.ciiu_subclase S ON S.id_subclase = A.id_subclase ";
                $cadenaSql .= " WHERE id_objeto = " . $variable;
                break;

            case "consultarActividadesImp" : // ********************************************************************
                $cadenaSql = " SELECT";
                $cadenaSql .= " A.subclase,";
                $cadenaSql .= " UPPER(S.nombre) as nombre";
                $cadenaSql .= " FROM ";
                $cadenaSql .= " agora.objeto_cotizacion_actividad_ciiu A";
                $cadenaSql .= " JOIN core.ciiu_subclase S ON S.id_subclase = A.subclase ";
                $cadenaSql .= " WHERE objeto = " . $variable;
                break;

            case "consultarNBCImp" : // ********************************************************************
                $cadenaSql = " SELECT";
                $cadenaSql .= " A.nucleo,";
                $cadenaSql .= " UPPER(S.nombre) as nombre";
                $cadenaSql .= " FROM ";
                $cadenaSql .= " agora.objeto_cotizacion_nucleo_basico A";
                $cadenaSql .= " JOIN core.snies_nucleo_basico S ON S.id_nucleo = A.nucleo ";
                $cadenaSql .= " WHERE A.objeto = " . $variable;
                break;


            /* REGISTRAR DATOS - USUARIO */
            case "registrarActividad" : // **********************************************************
                $cadenaSql = " INSERT INTO ";
                $cadenaSql .= " agora.objeto_cotizacion_actividad_ciiu ";
                $cadenaSql .= " (";
                $cadenaSql .= " objeto,";
                $cadenaSql .= " subclase";
                $cadenaSql .= " )";
                $cadenaSql .= " VALUES";
                $cadenaSql .= " (";
                $cadenaSql .= " '" . $variable ['idObjeto'] . "',";
                $cadenaSql .= " '" . $variable ['actividad'] . "'";
                $cadenaSql .= " );";
                break;

            case "registrarNucleoBasico" : // **********************************************************
                $cadenaSql = " INSERT INTO ";
                $cadenaSql .= " agora.objeto_cotizacion_nucleo_basico ";
                $cadenaSql .= " (";
                $cadenaSql .= " objeto,";
                $cadenaSql .= " nucleo";
                $cadenaSql .= " )";
                $cadenaSql .= " VALUES";
                $cadenaSql .= " (";
                $cadenaSql .= " '" . $variable ['idObjeto'] . "',";
                $cadenaSql .= " '" . $variable ['objetoNBC'] . "'";
                $cadenaSql .= " );";
                break;

            case "actualizarNucleoBasico" : // **********************************************************
                $cadenaSql = " UPDATE agora.objeto_cotizacion_nucleo_basico SET ";
                $cadenaSql .= " id_nucleo = " . $variable ['objetoNBC'];
                $cadenaSql .= " WHERE";
                $cadenaSql .= " id_objeto = '" . $variable ['idObjeto'] . "';";
                break;

            case "consultarNucleoBasico" : // **********************************************************
                $cadenaSql = " SELECT ";
                $cadenaSql .= " id_nucleo ";
                $cadenaSql .= " FROM ";
                $cadenaSql .= " agora.objeto_cotizacion_nucleo_basico ";
                $cadenaSql .= " WHERE";
                $cadenaSql .= " id_objeto = '" . $variable . "';";
                break;





            /* VERIFICAR NUMERO DE NIT */
            case "verificarActividad" : // ******************************************************************
                $cadenaSql = " SELECT";
                $cadenaSql .= " objeto";
                $cadenaSql .= " FROM ";
                $cadenaSql .= " agora.objeto_cotizacion_actividad_ciiu ";
                $cadenaSql .= " WHERE objeto= '" . $variable ['idObjeto'] . "'";
                $cadenaSql .= " AND subclase = '" . $variable ['actividad'] . "'";
                break;

            case "actividadesXNecesidad" :
                $cadenaSql = " SELECT ";
                $cadenaSql .= " string_agg('''' || cast(subclase as text) || '''',',' ";
                $cadenaSql .= " ORDER BY ";
                $cadenaSql .= " subclase) ";
                $cadenaSql .= " FROM ";
                $cadenaSql .= " agora.objeto_cotizacion_actividad_ciiu ";
                $cadenaSql .= " WHERE ";
                $cadenaSql .= " objeto = " . $variable . ";";
                break;










            /* REGISTRAR codigo validacion */
            case "ingresarCodigo" :
                $cadenaSql = " INSERT INTO agora.codigo_validacion";
                $cadenaSql .= " (";
                $cadenaSql .= " id_tabla,";
                $cadenaSql .= " tipo_certificacion,";
                $cadenaSql .= " fecha";
                $cadenaSql .= " )";
                $cadenaSql .= " VALUES";
                $cadenaSql .= " (";
                $cadenaSql .= " '" . $variable ['idObjeto'] . "',";
                $cadenaSql .= " '" . $variable ['tipo'] . "',";
                $cadenaSql .= " '" . $variable ['fecha'] . "'";
                $cadenaSql .= " ) RETURNING id_codigo_validacion;";
                break;

            /* BUSCAR PROVEEDORES SOLICITUD DE COTIZACION */
            case "buscarProveedores" :
                $cadenaSql = "SELECT";
                $cadenaSql .= " P.id_proveedor,";
                $cadenaSql .= " P.tipopersona,";
                $cadenaSql .= "	P.correo,";
                $cadenaSql .= " P.nom_proveedor,";
                $cadenaSql .= "	P.num_documento,";
                $cadenaSql .= " P.puntaje_evaluacion,";
                $cadenaSql .= "	P.clasificacion_evaluacion";
                $cadenaSql .= " FROM ";
                $cadenaSql .= " agora.solicitud_cotizacion S";
                $cadenaSql .= " JOIN agora.informacion_proveedor P ON P.id_proveedor = S.id_proveedor";
                $cadenaSql .= " WHERE  id_objeto=" . $variable;
                $cadenaSql .= " ORDER BY puntaje_evaluacion DESC";
                break;

            case "buscarProveedoresInfoCotizacion" :
                $cadenaSql = " SELECT";
                $cadenaSql.=" P.id_proveedor,";
                $cadenaSql.=" P.tipopersona,";
                $cadenaSql.=" P.correo,";
                $cadenaSql.=" P.direccion,";
                $cadenaSql.=" P.web,";
                $cadenaSql.=" (U.nombre || ', ' || D.nombre || ', ' || 'Colombia') as ubicacion, ";
                $cadenaSql.=" P.nom_proveedor,";
                $cadenaSql.=" P.num_documento,";
                $cadenaSql.=" P.puntaje_evaluacion,";
                $cadenaSql.=" P.clasificacion_evaluacion";
                $cadenaSql.=" FROM";
                $cadenaSql.=" agora.solicitud_cotizacion S";
                $cadenaSql.=" JOIN agora.informacion_proveedor P ON P.id_proveedor = S.id_proveedor";
                $cadenaSql.=" JOIN agora.ciudad U ON P.id_ciudad_contacto = U.id_ciudad";
                $cadenaSql.=" JOIN agora.departamento D ON U.id_departamento = D.id_departamento";
                $cadenaSql.=" WHERE  id_objeto = " . $variable;
                break;

            /* REGISTRAR COTIZACION */
            case "ingresarCotizacion" :
                $hoy = date("Y-m-d");

                $cadenaSql = " INSERT INTO agora.solicitud_cotizacion";
                $cadenaSql .= " (";
                $cadenaSql .= " id_objeto,";
                $cadenaSql .= " id_proveedor";
                $cadenaSql .= " )";
                $cadenaSql .= " VALUES";
                $cadenaSql .= " (";
                $cadenaSql .= " '" . $variable [0] . "',";
                $cadenaSql .= " '" . $variable [1] . "'";
                $cadenaSql .= " );";
                break;

            /* ACTUALIZAR - OBJETO CONTRATO - ESTADO */
            case 'actualizarObjetoNum' :
                $cadenaSql = "UPDATE agora.objeto_cotizacion SET ";
                $cadenaSql .= "numero_solicitud = '" . $variable ['numero_solicitud'] . "'";
                $cadenaSql .= " WHERE id=";
                $cadenaSql .= "'" . $variable ['idObjeto'] . "' ";
                break;

            /* ACTUALIZAR - OBJETO CONTRATO - ESTADO */
            case 'actualizarObjeto' :
                $cadenaSql = "UPDATE agora.objeto_cotizacion SET ";
                $cadenaSql .= "numero_solicitud = '" . $variable ['numero_solicitud'] . "',";
                $cadenaSql .= "estado = '" . $variable ['estado'] . "',";
                $cadenaSql .= "fecha_solicitud_cotizacion = '" . $variable ['fecha'] . "'";
                $cadenaSql .= " WHERE id_objeto=";
                $cadenaSql .= "'" . $variable ['idObjeto'] . "' ";
                break;

            /* verificar si existe proveedores con la actividad economica */
            case "verificarActividadProveedor" :
                $cadenaSql = "SELECT *";
                $cadenaSql .= " FROM ";
                $cadenaSql .= " agora.proveedor_actividad_ciiu";
                $cadenaSql .= " WHERE  id_subclase IN (" . $variable . ");";
                break;

            /**
             * Lista proveedores
             * Que cumlen con la actividad economica
             * Que Tienen puntaje mayor a 45 (Se elimina la restriccion por peticion de Usuario)
             * El limite de registros lo establece el objeto a contratar
             */
            case "proveedoresByClasificacion" :
                $cadenaSql = "SELECT DISTINCT";
                $cadenaSql .= " id_proveedor,";
                $cadenaSql .= " P.num_documento,";
                $cadenaSql .= "	nom_proveedor,";
                $cadenaSql .= "	puntaje_evaluacion,";
                $cadenaSql .= "	tipopersona,";
                $cadenaSql .= "	correo,";
                $cadenaSql .= "	clasificacion_evaluacion";
                $cadenaSql .= " FROM ";
                $cadenaSql .= " agora.informacion_proveedor P";
                $cadenaSql .= " JOIN agora.proveedor_actividad_ciiu A ON A.num_documento = P.num_documento";
                $cadenaSql .= " WHERE  A.id_subclase IN (" . $variable ['actividadEconomica'] . ")";
                //$cadenaSql .= " AND P.puntaje_evaluacion > 45";
                $cadenaSql .= " AND P.estado = '1'";
                //$cadenaSql .= " ORDER BY puntaje_evaluacion DESC";
                break;

            case "proveedoresByClasificacionConv" :
                $cadenaSql = "SELECT DISTINCT";
                $cadenaSql .= " id_proveedor,";
                $cadenaSql .= " P.num_documento,";
                $cadenaSql .= "	nom_proveedor,";
                $cadenaSql .= "	puntaje_evaluacion,";
                $cadenaSql .= "	tipopersona,";
                $cadenaSql .= "	correo,";
                $cadenaSql .= "	clasificacion_evaluacion";
                $cadenaSql .= " FROM ";
                $cadenaSql .= " agora.informacion_proveedor P";
                $cadenaSql .= " JOIN agora.proveedor_actividad_ciiu A ON A.num_documento = P.num_documento";
                $cadenaSql .= " JOIN agora.informacion_persona_natural PN ON PN.num_documento_persona = P.num_documento";
                $cadenaSql .= " WHERE  A.id_subclase IN (" . $variable ['actividadEconomica'] . ")";
                $cadenaSql .= " AND PN.id_nucleo_basico = " . $variable ['objetoNBC'];
                $cadenaSql .= " AND P.estado = '1'";
                //$cadenaSql .= " AND P.tipopersona = 'NATURAL'";
                //$cadenaSql .= " ORDER BY puntaje_evaluacion DESC";
                break;


            /* ULTIMO NUMERO DE SECUENCIA */
            case "lastIdObjeto" :
                $cadenaSql = "SELECT";
                $cadenaSql .= " last_value";
                $cadenaSql .= " FROM ";
                $cadenaSql .= " agora.prov_objeto_contratar_id_objeto_seq";
                break;

            /* CONSULTAR - OBJETO A CONTRATAR - ESPECIFICO */
            case "objetoContratar" :
                $cadenaSql = "SELECT";
                $cadenaSql .= " titulo_cotizacion,";
                $cadenaSql .= " numero_solicitud,";
                $cadenaSql .= " vigencia,";
                $cadenaSql .= " id_solicitante,";
                $cadenaSql .= " unidad_ejecutora,";
                $cadenaSql .= "	fecha_registro,";
                $cadenaSql .= "	fecha_solicitud_cotizacion,";
                $cadenaSql .= "	tipo_necesidad,";
                $cadenaSql .= "	esp.dependencia,";
                $cadenaSql .= " objetivo,";
                $cadenaSql .= " requisitos,";
                $cadenaSql .= " observaciones,";
                $cadenaSql .= " responsable,";
                $cadenaSql .= " fecha_apertura,";
                $cadenaSql .= " fecha_cierre,";
                $cadenaSql .= "	estado";
                $cadenaSql .= " FROM ";
                $cadenaSql .= " agora.objeto_cotizacion O";
                $cadenaSql .= " JOIN agora.parametro_dependencia esp ON esp.id_dependencia = O.dependencia";
                $cadenaSql .= " WHERE  id_objeto=" . $variable; // Activo
                break;

            /* LISTA - OBJETO A CONTRATAR */
            case "listaObjetoContratar" :
                $cadenaSql = "SELECT";
                $cadenaSql .= " id_objeto,";
                $cadenaSql .= " numero_solicitud,";
                $cadenaSql .= " vigencia,";
                $cadenaSql .= " unidad_ejecutora,";
                $cadenaSql .= "	cantidad,";
                $cadenaSql .= "	fechasolicitudcotizacion,";
                $cadenaSql .= "	fecharegistro,";
                $cadenaSql .= "	numero_cotizaciones,";
                $cadenaSql .= "	estado";
                $cadenaSql .= " FROM ";
                $cadenaSql .= " agora.objeto_contratar O";
                $cadenaSql .= " WHERE  estado= '" . $variable . "'"; // Activo
                $cadenaSql .= " ORDER BY fechaRegistro";
                break;

            /* CIIU */
            case "ciiuDivision" :
                $cadenaSql = "SELECT";
                $cadenaSql .= " id_division,";
                $cadenaSql .= "	nombre";
                $cadenaSql .= " FROM ";
                $cadenaSql .= " agora.ciiu_division";
                $cadenaSql .= " ORDER BY nombre";
                break;

            case "ciiuGrupo" :
                $cadenaSql = "SELECT";
                $cadenaSql .= " id_clase,";
                $cadenaSql .= "	nombre";
                $cadenaSql .= " FROM ";
                $cadenaSql .= " agora.ciiu_clase";
                $cadenaSql .= " WHERE division ='" . $variable . "'";
                $cadenaSql .= " ORDER BY nombre";
                break;

            case "ciiuClase" :
                $cadenaSql = "SELECT";
                $cadenaSql .= " id_subclase,";
                $cadenaSql .= "	nombre";
                $cadenaSql .= " FROM ";
                $cadenaSql .= " agora.ciiu_subclase";
                $cadenaSql .= " WHERE clase ='" . $variable . "'";
                $cadenaSql .= " ORDER BY nombre";
                break;
            /* LISTA - ORDENAR DEL GASTO */
            case "ordenador" :
                $cadenaSql = "SELECT";
                $cadenaSql .= " id_ordenador,";
                $cadenaSql .= "	nombre_ordenador";
                $cadenaSql .= " FROM ";
                $cadenaSql .= " agora.ordenador_gasto";
                $cadenaSql .= " order by nombre_ordenador";
                break;
            /* LISTA - DEPENDENCIA */
            case "dependencia" :
                $cadenaSql = "SELECT";
                $cadenaSql .= " id_dependencia,";
                $cadenaSql .= "	dependencia";
                $cadenaSql .= " FROM ";
                $cadenaSql .= " agora.parametro_dependencia";
                $cadenaSql .= " ORDER BY dependencia";
                break;

            /* LISTA - DEPENDENCIA */
            case "buscarDependencia" :
                $cadenaSql = "SELECT";
                $cadenaSql .= "	dependencia";
                $cadenaSql .= " FROM ";
                $cadenaSql .= " agora.parametro_dependencia";
                $cadenaSql .= " WHERE id_dependencia = " . $variable;
                break;

            /* LISTA - UNIDAD */
            case "unidad" :
                $cadenaSql = "SELECT";
                $cadenaSql .= " id_unidad,";
                $cadenaSql .= "	(tipo || '-' || unidad) AS unidad";
                $cadenaSql .= " FROM ";
                $cadenaSql .= " agora.parametro_unidad";
                $cadenaSql .= " ORDER BY tipo";
                break;


            case "ciiuSubClase" :
                $cadenaSql = "SELECT";
                $cadenaSql .= " id_subclase AS id_subclase,";
                $cadenaSql .= "	id_subclase||' - ('||nombre||')' AS  nombre";
                $cadenaSql .= " FROM ";
                $cadenaSql .= " core.ciiu_subclase";
                $cadenaSql .= " ORDER BY id_subclase";
                break;

            case "ciiuSubClaseByNum" :
                $cadenaSql = "SELECT";
                $cadenaSql .= " id_subclase AS id_subclase,";
                $cadenaSql .= "	id_subclase||' - ('||nombre||')' AS  nombre";
                $cadenaSql .= " FROM ";
                $cadenaSql .= " core.ciiu_subclase";
                $cadenaSql .= " WHERE id_subclase = '" . $variable . "'";
                break;


            case "ciiuSubClaseByNumPush" :
                $cadenaSql = "SELECT";
                $cadenaSql .= " clase AS clase,";
                $cadenaSql .= " nombre AS descripcion,";
                $cadenaSql .= " id_subclase AS id_subclase,";
                $cadenaSql .= "	id_subclase||' - ('||nombre||')' AS  nombre";
                $cadenaSql .= " FROM ";
                $cadenaSql .= " core.ciiu_subclase";
                $cadenaSql .= " WHERE id_subclase = '" . $variable . "'";
                break;



            case "infoCotizacion" :
                $cadenaSql = "SELECT";
                $cadenaSql .= " * ";
                $cadenaSql .= " FROM ";
                $cadenaSql .= " agora.objeto_cotizacion";
                $cadenaSql .= " WHERE id = " . $variable;
                break;

            case "infoCotizacionCast" :
                $cadenaSql = "SELECT";
                $cadenaSql .= " oc.titulo_cotizacion, ";
                $cadenaSql .= " oc.fecha_apertura, ";
                $cadenaSql .= " oc.fecha_cierre ";
                $cadenaSql .= " FROM ";
                $cadenaSql .= " agora.objeto_cotizacion oc";
                $cadenaSql .= " WHERE oc.id = " . $variable;
                break;


            case "registrarFormaPagoXCotizacion" :
                $hoy = date("Y-m-d");
                $cadenaSql = " INSERT INTO agora.objeto_cotizacion_forma_pago";
                $cadenaSql .= " (";
                $cadenaSql .= " objeto_cotizacion_id,";
                $cadenaSql .= " forma_pago_id";
                $cadenaSql .= " )";
                $cadenaSql .= " VALUES";
                $cadenaSql .= " (";
                $cadenaSql .= " " . $variable ['objeto_cotizacion_id'] . ",";
                $cadenaSql .= " " . $variable ['forma_pago_id'] . "";
                $cadenaSql .= " );";
                break;


            case "registrarFormaPago" :
                $hoy = date("Y-m-d");
                $cadenaSql = " INSERT INTO agora.forma_pago";
                $cadenaSql .= " (";
                $cadenaSql .= " tipo_condicion,";
                $cadenaSql .= " valor_condicion,";
                $cadenaSql .= " porcentaje_pago";
                $cadenaSql .= " )";
                $cadenaSql .= " VALUES";
                $cadenaSql .= " (";
                $cadenaSql .= " " . $variable ['tipo_condicion'] . ",";
                $cadenaSql .= " " . $variable ['valor_condicion'] . ",";
                $cadenaSql .= " " . $variable ['porcentaje_pago'] . "";
                $cadenaSql .= " )";
                $cadenaSql .= " RETURNING  id; ";
                break;

            case "registrarPlanAccion" :
                $hoy = date("Y-m-d");
                $cadenaSql = " INSERT INTO administrativa.plan_accion";
                $cadenaSql .= " (";
                $cadenaSql .= " descripcion";
                $cadenaSql .= " )";
                $cadenaSql .= " VALUES";
                $cadenaSql .= " (";
                $cadenaSql .= " '" . $variable . "' ";
                $cadenaSql .= " )";
                $cadenaSql .= " RETURNING  id; ";
                break;

            /* REGISTRAR DATOS DEL OBJETO A CONTRATAR */
            case "registrar" :
                $hoy = date("Y-m-d");
                $cadenaSql = " INSERT INTO agora.objeto_cotizacion";
                $cadenaSql .= " (";
                $cadenaSql .= " titulo_cotizacion,";
                $cadenaSql .= " jefe_dependencia,";
                $cadenaSql .= " jefe_dependencia_destino,";
                $cadenaSql .= " ordenador_gasto,";
                $cadenaSql .= " fecha_apertura,";
                $cadenaSql .= " fecha_cierre,";
                $cadenaSql .= " objetivo,";
                $cadenaSql .= " requisitos,";
                $cadenaSql .= " observaciones,";
                $cadenaSql .= " plan_accion,";
                $cadenaSql .= " vigencia,";
                $cadenaSql .= " unidad_ejecutora,";
                $cadenaSql .= " estado_cotizacion,";
                $cadenaSql .= " tipo_necesidad,";
                $cadenaSql .= " forma_seleccion_id,";
                $cadenaSql .= " fecha_registro,";
                $cadenaSql .= " medio_pago,";
                $cadenaSql .= " numero_necesidad,";
                $cadenaSql .= " usuario_creo,";
                $cadenaSql .= " anexo_cotizacion";
                $cadenaSql .= " )";
                $cadenaSql .= " VALUES";
                $cadenaSql .= " (";
                $cadenaSql .= " '" . $variable ['titulo_cotizacion'] . "',";
                $cadenaSql .= " " . $variable ['dependencia'] . ",";
                $cadenaSql .= " " . $variable ['dependencia_destino'] . ",";
                $cadenaSql .= " " . $variable ['ordenador'] . ",";
                $cadenaSql .= " '" . $variable ['fecha_apertura'] . "',";
                $cadenaSql .= " '" . $variable ['fecha_cierre'] . "',";
                $cadenaSql .= " '" . $variable ['objetivo'] . "',";
                $cadenaSql .= " '" . $variable ['requisitos'] . "',";
                $cadenaSql .= " '" . $variable ['observaciones'] . "',";
                $cadenaSql .= " " . $variable ['plan'] . ",";
                $cadenaSql .= " " . $variable ['vigencia'] . ",";
                $cadenaSql .= " " . $variable ['unidad_ejecutora'] . ",";
                $cadenaSql .= " '1',";
                $cadenaSql .= " '" . $variable ['tipo_necesidad'] . "',";
                $cadenaSql .= " '" . $variable ['forma_seleccion'] . "',";
                $cadenaSql .= " '" . $hoy . "',";
                $cadenaSql .= " " . $variable ['medio_pago'] . ",";
                $cadenaSql .= " '" . $variable ['numero_disponibilidad'] . "',";
                $cadenaSql .= " '" . $variable ['usuario'] . "',";
                $cadenaSql .= " '" . $variable ['anexo'] . "' ";
                $cadenaSql .= " )";
                $cadenaSql .= " RETURNING  id; ";
               
                break;
            
             case "registrarItemProducto" :
                $cadenaSql = " INSERT INTO ";
                $cadenaSql .= "agora.item_cotizacion_padre";
                $cadenaSql .= " (";
                $cadenaSql .= " objeto_cotizacion_id,";
                $cadenaSql .= " nombre,";
                $cadenaSql .= " descripcion,";
                $cadenaSql .= " tipo_necesidad,";
                $cadenaSql .= " unidad, ";
                $cadenaSql .= " tiempo_ejecucion, ";
                $cadenaSql .= " cantidad ";
                $cadenaSql .= " )";
                $cadenaSql .= " VALUES";
                $cadenaSql .= " (";
                $cadenaSql .= $variable ['objeto_cotizacion'] . ",";
                $cadenaSql .= " '" . $variable ['nombre'] . "',";
                $cadenaSql .= " '" . $variable ['descripcion'] . "',";
                $cadenaSql .= " '" . $variable ['tipo'] . "',";
                $cadenaSql .= " '" . $variable ['unidad'] . "',";
                $cadenaSql .= " '" . $variable ['tiempo'] . "',";
                $cadenaSql .= " '" . $variable ['cantidad'] . "' ";
                $cadenaSql .= " );";
                break;

            /* ACTUALIZAR DATOS DEL OBJETO A CONTRATAR */
            case "actualizar" :
                $hoy = date("Y-m-d");
                $cadenaSql = " UPDATE agora.objeto_contratar SET";
                $cadenaSql .= " numero_solicitud = " . $variable ['numero_solicitud'] . ",";
                $cadenaSql .= " vigencia = " . $variable ['vigencia'] . ",";
                $cadenaSql .= " unidad_ejecutora = " . $variable ['unidad_ejecutora'] . ",";
                $cadenaSql .= " id_unidad = '" . $variable ['unidad'] . "',";
                $cadenaSql .= " cantidad = '" . $variable ['cantidad'] . "',";
                $cadenaSql .= " numero_cotizaciones = '" . $variable ['cotizaciones'] . "',";
                $cadenaSql .= " estado = 'CREADO',";
                $cadenaSql .= " tipo_necesidad = '" . $variable ['tipo_necesidad'] . "',";
                $cadenaSql .= " fecharegistro = '" . $hoy . "'";
                $cadenaSql .= " WHERE numero_solicitud = " . $variable ['numero_solicitud'];
                $cadenaSql .= " AND vigencia = " . $variable ['vigencia'] . ";";
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

                foreach ($_REQUEST as $clave => $valor) {
                    $cadenaSql .= "( ";
                    $cadenaSql .= "'" . $idSesion . "', ";
                    $cadenaSql .= "'" . $variable ['formulario'] . "', ";
                    $cadenaSql .= "'" . $clave . "', ";
                    $cadenaSql .= "'" . $valor . "', ";
                    $cadenaSql .= "'" . $variable ['fecha'] . "' ";
                    $cadenaSql .= "),";
                }

                $cadenaSql = substr($cadenaSql, 0, (strlen($cadenaSql) - 1));
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
            case "tipoNecesidadAdministrativaOnlyBien" :
                $cadenaSql = " SELECT id, nombre";
                $cadenaSql .= " FROM administrativa.tipo_necesidad WHERE id = 1;";
                break;
            
            case "tipoNecesidadAdministrativaOnlyServicio" :
                $cadenaSql = " SELECT id, nombre";
                $cadenaSql .= " FROM administrativa.tipo_necesidad WHERE id = 2;";
                break;
            
            case "tipoNecesidadAdministrativa2" :
                $cadenaSql = " SELECT id, nombre";
                $cadenaSql .= " FROM administrativa.tipo_necesidad WHERE estado = TRUE AND (id = 1  OR id = 2);";
                break;
            
            case "unidadUdistrital" :
                $cadenaSql = " SELECT id, unidad";
                $cadenaSql .= " FROM agora.unidad WHERE estado = TRUE;";
                break;
            case "consultar_tipo_iva" :

                $cadenaSql = "SELECT id_iva, descripcion ";
                $cadenaSql .= "FROM arka.aplicacion_iva;";

                break;
            case "consultarUnidadByNumPush" :
                $cadenaSql = " SELECT *";
                $cadenaSql .= " FROM agora.unidad WHERE estado = TRUE AND id = " . $variable . ";";
                break;
        }

        return $cadenaSql;
    }

}

?>
