<?php

namespace proveedor\registroProveedor\funcion;

use proveedor\registroProveedor\funcion\redireccionar;

include_once ('redireccionar.php');
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}
class Formulario {
	
	var $miConfigurador;
	var $lenguaje;
	var $miFormulario;
	var $miFuncion;
	var $miSql;
	var $conexion;
	var $miLogger;
	
	function __construct($lenguaje, $sql, $funcion, $miLogger) {
		
		$this->miConfigurador = \Configurador::singleton ();
		$this->miConfigurador->fabricaConexiones->setRecursoDB ( 'principal' );
		$this->lenguaje = $lenguaje;
		$this->miSql = $sql;
		$this->miFuncion = $funcion;
		$this->miLogger= $miLogger;
	}
	
	function procesarFormulario() {
		
		
		//*************************************************************************** DBMS *******************************
		//****************************************************************************************************************
		
		$conexion = 'estructura';
		$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
		
		$conexion = 'sicapital';
		$siCapitalRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
		
		$conexion = 'centralUD';
		$centralUDRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
		
		$conexion = 'argo_contratos';
		$argoRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
		
		$conexion = 'core_central';
		$coreRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
		
		$conexion = 'framework';
		$frameworkRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
		
		//*************************************************************************** DBMS *******************************
		//****************************************************************************************************************
		
		$esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );
		
		$rutaBloque = $this->miConfigurador->getVariableConfiguracion ( "raizDocumento" ) . "/blocks/proveedor/";
		$rutaBloque .= $esteBloque ['nombre'];
		$host = $this->miConfigurador->getVariableConfiguracion ( "host" ) . $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/proveedor/" . $esteBloque ['nombre'];

		
		$SQLs = [];
	
		
		if(isset($_REQUEST['correoNat'])){$_REQUEST['correoNat'] = str_replace('\\', "", $_REQUEST['correoNat']);}
		if(isset($_REQUEST['correo'])){$_REQUEST['correo'] = str_replace('\\', "", $_REQUEST['correo']);}
		
		
		if(isset($_REQUEST['primerApellidoNat'])){$_REQUEST['primerApellidoNat']=mb_strtoupper($_REQUEST['primerApellidoNat'],'utf-8');}
		if(isset($_REQUEST['segundoApellidoNat'])){$_REQUEST['segundoApellidoNat']=mb_strtoupper($_REQUEST['segundoApellidoNat'],'utf-8');}
		if(isset($_REQUEST['primerNombreNat'])){$_REQUEST['primerNombreNat']=mb_strtoupper($_REQUEST['primerNombreNat'],'utf-8');}
		if(isset($_REQUEST['segundoNombreNat'])){$_REQUEST['segundoNombreNat']=mb_strtoupper($_REQUEST['segundoNombreNat'],'utf-8');}
		if(isset($_REQUEST['profesionNat'])){$_REQUEST['profesionNat']=mb_strtoupper($_REQUEST['profesionNat'],'utf-8');}
		if(isset($_REQUEST['especialidadNat'])){$_REQUEST['especialidadNat']=mb_strtoupper($_REQUEST['especialidadNat'],'utf-8');}
		if(isset($_REQUEST['asesorComercialNat'])){$_REQUEST['asesorComercialNat']=mb_strtoupper($_REQUEST['asesorComercialNat'],'utf-8');}
		if(isset($_REQUEST['descripcionNat'])){$_REQUEST['descripcionNat']=mb_strtoupper($_REQUEST['descripcionNat'],'utf-8');}
		

		//Guardar RUT adjuntado Persona Natural*********************************************************************
		$_REQUEST ['destino'] = '';
		// Guardar el archivo
		if ($_FILES) {
			$i = 0;
			foreach ( $_FILES as $key => $values ) {
				$archivoCarga[$i] = $_FILES [$key];
				$i++;
			}
			$archivo = $archivoCarga[2];
			// obtenemos los datos del archivo
			$tamano = $archivo ['size'];
			$tipo = $archivo ['type'];
			$archivo1 = $archivo ['name'];
			$prefijo = substr ( md5 ( uniqid ( rand () ) ), 0, 6 );
			$nombreDoc = $prefijo . "-" . $archivo1;
		
			if ($archivo1 != "") {
				// guardamos el archivo a la carpeta files
				$destino = $rutaBloque . "/files/" . $nombreDoc;
		
				if (copy ( $archivo ['tmp_name'], $destino )) {
					$status = "Archivo subido: <b>" . $archivo1 . "</b>";
					$_REQUEST ['destino'] = $host . "/files/" . $prefijo . "-" . $archivo1;
				} else {
					$status = "<br>Error al subir el archivo1";
				}
			} else {
				$status = "<br>Error al subir archivo2";
			}
		} else {
			echo "<br>NO existe el archivo D:!!!";
		}
		//************************************************************************************************************		

		
		
		//Guardar RUP adjuntado Persona Natural*********************************************************************
		$_REQUEST ['destino2'] = '';
		// Guardar el archivo
		if ($_FILES) {
			$i = 0;
			foreach ( $_FILES as $key => $values ) {
				$archivoCarga[$i] = $_FILES [$key];
				$i++;
			}
			$archivo = $archivoCarga[3];
			// obtenemos los datos del archivo
			$tamano = $archivo ['size'];
			$tipo = $archivo ['type'];
			$archivo1 = $archivo ['name'];
			$prefijo = substr ( md5 ( uniqid ( rand () ) ), 0, 6 );
			$nombreDoc = $prefijo . "-" . $archivo1;
		
			if ($archivo1 != "") {
				// guardamos el archivo a la carpeta files
				$destino = $rutaBloque . "/files/" . $nombreDoc;
		
				if (copy ( $archivo ['tmp_name'], $destino )) {
					$status = "Archivo subido: <b>" . $archivo1 . "</b>";
					$_REQUEST ['destino2'] = $host . "/files/" . $prefijo . "-" . $archivo1;
				} else {
					$status = "<br>Error al subir el archivo1";
				}
			} else {
				$status = "<br>Error al subir archivo2";
			}
		} else {
			echo "<br>NO existe el archivo D:!!!";
		}
		//************************************************************************************************************


		unset($resultado);
		//VERIFICAR SI LA CEDULA YA SE ENCUENTRA REGISTRADA
		$cadenaSql = $this->miSql->getCadenaSql ( "verificarProveedor", $_REQUEST ['documentoNat']);
		$resultadoVerificar = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'busqueda' );
		
		
		
		if ($resultadoVerificar) {
			//El proveedor ya existe
			redireccion::redireccionar ( 'existeProveedor',  $_REQUEST ['documentoNat']);
			exit();    
		}else{
                   
			
				if(isset($_REQUEST['tipoPersona'])){//CAST genero tipoCuenta
					switch($_REQUEST['tipoPersona']){
						case 1 :
							$_REQUEST['tipoPersona']='NATURAL';
							break;
						case 2 :
							$_REQUEST['tipoPersona']='JURIDICA';
							break;
					}
				}
				
				if(isset($_REQUEST['tipoCuentaNat'])){//CAST
					switch($_REQUEST['tipoCuentaNat']){
						case 1 :
							$_REQUEST['tipoCuentaNat']='AHORROS';
							break;
						case 2 :
							$_REQUEST['tipoCuentaNat']='CORRIENTE';
							break;
					}
				}
				
				if(isset($_REQUEST['generoNat'])){//CAST
					switch($_REQUEST['generoNat']){
						case 1 :
							$_REQUEST['generoNat']='MASCULINO';
							break;
						case 2 :
							$_REQUEST['generoNat']='FEMENINO';
							break;
					}
				}
				
				
				if(isset($_REQUEST['perfilNat'])){//CAST
					switch($_REQUEST['perfilNat']){
					case 1 :
						$_REQUEST ['perfilNat'] = 18;
						$_REQUEST ['personaNaturalNBC'] = 49207;
						break;
					case 2 :
						$_REQUEST ['perfilNat'] = 19;
						break;
					case 3 :
						$_REQUEST ['perfilNat'] = 20;
						break;
					case 4 :
						$_REQUEST ['perfilNat'] = 21;
						break;
					case 5 :
						$_REQUEST ['perfilNat'] = 22;
						$_REQUEST ['personaNaturalNBC'] = 0;
						break;
					case 6 :
						$_REQUEST ['perfilNat'] = 38;
						break;
					case 7 :
						$_REQUEST ['perfilNat'] = 39;
						break;
					}
				}
				
				if(isset($_REQUEST['grupoEtnico'])){//CAST genero tipoCuenta
					switch($_REQUEST['grupoEtnico']){
						case 23 :
							$_REQUEST['grupoEtnico']='AFRODESCENDIENTES';
							break;
						case 24 :
							$_REQUEST['grupoEtnico']='INDIGENAS';
							break;
						case 25 :
							$_REQUEST['grupoEtnico']='RAIZALES';
							break;
						case 26 :
							$_REQUEST['grupoEtnico']='ROM';
							break;
						case 40 :
							$_REQUEST ['grupoEtnico']=null;
							break;
						}
				}
				
				if(isset($_REQUEST['estadoCivil'])){//CAST genero tipoCuenta
					switch($_REQUEST['estadoCivil']){
						case 27 :
							$_REQUEST['estadoCivil']='SOLTERO';
							break;
						case 28 :
							$_REQUEST['estadoCivil']='CASADO';
							break;
						case 29 :
							$_REQUEST['estadoCivil']='UNION LIBRE';
							break;
						case 30 :
							$_REQUEST['estadoCivil']='VIUDO';
							break;
						case 31 :
							$_REQUEST['estadoCivil']='DIVORCIADO';
							break;
					}
				}
				
				
				if(isset($_REQUEST['tipoDiscapacidad'])){//CAST genero tipoCuenta
					switch($_REQUEST['tipoDiscapacidad']){
						case 32 :
							$_REQUEST['tipoDiscapacidad']='FISICA';
							break;
						case 33 :
							$_REQUEST['tipoDiscapacidad']='SENSORIAL';
							break;
						case 34 :
							$_REQUEST['tipoDiscapacidad']='AUDITIVA';
							break;
						case 35 :
							$_REQUEST['tipoDiscapacidad']='VISUAL';
							break;
						case 36 :
							$_REQUEST['tipoDiscapacidad']='PSIQUICA';
							break;
						case 37 :
							$_REQUEST['tipoDiscapacidad']='MENTAL';
							break;
					}
				}
				
				if(isset($_REQUEST['comunidadLGBT'])){
					switch($_REQUEST ['comunidadLGBT']){
						case 1 :
							$_REQUEST ['comunidadLGBT']='TRUE';
							break;
						case 2 :
							$_REQUEST ['comunidadLGBT']='FALSE';
							break;
						default:
							$_REQUEST ['comunidadLGBT']='NULL';
							break;
					}
				}
				if(isset($_REQUEST['cabezaFamilia'])){
					switch($_REQUEST ['cabezaFamilia']){
						case 1 :
							$_REQUEST ['cabezaFamilia']='TRUE';
							break;
						case 2 :
							$_REQUEST ['cabezaFamilia']='FALSE';
							break;
						default:
							$_REQUEST ['cabezaFamilia']='NULL';
							break;
					}
				}
				if(isset($_REQUEST['personasCargo'])){
					switch($_REQUEST ['personasCargo']){
						case 1 :
							$_REQUEST ['personasCargo']='TRUE';
							break;
						case 2 :
							$_REQUEST ['personasCargo']='FALSE';
							break;
						default:
							$_REQUEST ['personasCargo']='NULL';
							break;
					}
				}
				if(isset($_REQUEST['discapacidad'])){
					switch($_REQUEST ['discapacidad']){
						case 1 :
							$_REQUEST ['discapacidad']='TRUE';
							break;
						case 2 :
							$_REQUEST ['discapacidad']='FALSE';
							break;
						default:
							$_REQUEST ['discapacidad']='NULL';
							break;
					}
				}
				
				//AGREGADO Beneficios TRIBUTARIOS ***************************************************************
				
				if(isset($_REQUEST['declaranteRentaNat'])){
					switch($_REQUEST ['declaranteRentaNat']){
						case 1 :
							$_REQUEST ['declaranteRentaNat']='TRUE';
							break;
						case 2 :
							$_REQUEST ['declaranteRentaNat']='FALSE';
							break;
						default:
							$_REQUEST ['declaranteRentaNat']='NULL';
							break;
					}
				}
				
				if(isset($_REQUEST['medicinaPrepagadaNat'])){
					switch($_REQUEST ['medicinaPrepagadaNat']){
						case 1 :
							$_REQUEST ['medicinaPrepagadaNat']='TRUE';
							break;
						case 2 :
							$_REQUEST ['medicinaPrepagadaNat']='FALSE';
							break;
						default:
							$_REQUEST ['medicinaPrepagadaNat']='NULL';
							break;
					}
				}
				
				if(isset($_REQUEST['cuentaAFCNat'])){
					switch($_REQUEST ['cuentaAFCNat']){
						case 1 :
							$_REQUEST ['cuentaAFCNat']='TRUE';
							break;
						case 2 :
							$_REQUEST ['cuentaAFCNat']='FALSE';
							break;
						default:
							$_REQUEST ['cuentaAFCNat']='NULL';
							break;
					}
				}
				
				
				
				if(isset($_REQUEST['hijosMenoresEdadNat'])){
					switch($_REQUEST ['hijosMenoresEdadNat']){
						case 1 :
							$_REQUEST ['hijosMenoresEdadNat']='TRUE';
							break;
						case 2 :
							$_REQUEST ['hijosMenoresEdadNat']='FALSE';
							break;
						default:
							$_REQUEST ['hijosMenoresEdadNat']='NULL';
							break;
					}
				}
				if(isset($_REQUEST['hijosMayoresEdadEstudiandoNat'])){
					switch($_REQUEST ['hijosMayoresEdadEstudiandoNat']){
						case 1 :
							$_REQUEST ['hijosMayoresEdadEstudiandoNat']='TRUE';
							break;
						case 2 :
							$_REQUEST ['hijosMayoresEdadEstudiandoNat']='FALSE';
							break;
						default:
							$_REQUEST ['hijosMayoresEdadEstudiandoNat']='NULL';
							break;
					}
				}
				if(isset($_REQUEST['hijosMayoresEdadMas23Nat'])){
					switch($_REQUEST ['hijosMayoresEdadMas23Nat']){
						case 1 :
							$_REQUEST ['hijosMayoresEdadMas23Nat']='TRUE';
							break;
						case 2 :
							$_REQUEST ['hijosMayoresEdadMas23Nat']='FALSE';
							break;
						default:
							$_REQUEST ['hijosMayoresEdadMas23Nat']='NULL';
							break;
					}
				}
				if(isset($_REQUEST['conyugeDependienteNat'])){
					switch($_REQUEST ['conyugeDependienteNat']){
						case 1 :
							$_REQUEST ['conyugeDependienteNat']='TRUE';
							break;
						case 2 :
							$_REQUEST ['conyugeDependienteNat']='FALSE';
							break;
						default:
							$_REQUEST ['conyugeDependienteNat']='NULL';
							break;
					}
				}
				if(isset($_REQUEST['padresHermanosDependienteNat'])){
					switch($_REQUEST ['padresHermanosDependienteNat']){
						case 1 :
							$_REQUEST ['padresHermanosDependienteNat']='TRUE';
							break;
						case 2 :
							$_REQUEST ['padresHermanosDependienteNat']='FALSE';
							break;
						default:
							$_REQUEST ['padresHermanosDependienteNat']='NULL';
							break;
					}
				}
				
				//***********************************************************************************************
				
				
				//CAST****************************************************************
				$dateExp = explode("/", $_REQUEST ['fechaExpeNat']);
				$cadena_fecha = $dateExp[2]."-".$dateExp[1]."-".$dateExp[0];
				$_REQUEST ['fechaExpeNat'] = $cadena_fecha;
				//********************************************************************
				
				

				$nombrePersona = $_REQUEST['primerNombreNat'] . ' ' . $_REQUEST['segundoNombreNat'] . ' ' . $_REQUEST['primerApellidoNat'] . ' ' . $_REQUEST['segundoApellidoNat'];
				
				$fechaActual = date ( 'Y-m-d' . ' - ' .'h:i:s A');

				$datosInformacionProveedorPersonaNatural = array (
						'tipoPersona' => $_REQUEST['tipoPersona'],
						'numero_documento' => $_REQUEST['documentoNat'],
						'nombre_proveedor' => $nombrePersona,
						'id_ciudad_contacto' =>	$_REQUEST['personaNaturalContaCiudad'],
						'direccion_contacto' => $_REQUEST['direccionNat'],
						'correo_contacto' => $_REQUEST['correoNat'],
						'web_contacto' => $_REQUEST['sitioWebNat'],
						'nom_asesor_comercial_contacto' => $_REQUEST['asesorComercialNat'],
						'tel_asesor_comercial_contacto' => $_REQUEST['telAsesorNat'],
						'tipo_cuenta_bancaria' => $_REQUEST['tipoCuentaNat'],
						'num_cuenta_bancaria' => $_REQUEST['numeroCuentaNat'],
						'id_entidad_bancaria' => $_REQUEST['entidadBancariaNat'],
						'anexo_rut' => $_REQUEST ['destino'],
						'anexo_rup' => $_REQUEST ['destino2'],
						'descripcion_proveedor' => $_REQUEST['descripcionNat'],
						'fecha_registro' => $fechaActual,
						'fecha_modificación' => $fechaActual,
						'id_estado' => '1' //Estado Inactivo
				);
				
				//Guardar datos PROVEEDOR
				$cadenaSqlInfoProveedor = $this->miSql->getCadenaSql("insertarInformacionProveedor",$datosInformacionProveedorPersonaNatural);
				//$id_proveedor = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda", $datosInformacionProveedorPersonaNatural, "insertarInformacionProveedor");
				array_push($SQLs, $cadenaSqlInfoProveedor);
				//Curval*********************************************
				
				
				
				$datosTelefonoFijoPersonaProveedor = array (
						'num_telefono' => $_REQUEST['telefonoNat'],
						'extension_telefono' => $_REQUEST['extensionNat'],
						'tipo' => '1'
				);
				
				
				$cadenaSql = $this->miSql->getCadenaSql("insertarInformacionProveedorTelefono",$datosTelefonoFijoPersonaProveedor);
				$id_TelefonoFijo = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda", $datosTelefonoFijoPersonaProveedor, "insertarInformacionProveedorTelefono");
				//array_push($SQLs, $cadenaSqlTelFijo);
				
				
				
				$datosTelefonoMovilPersonaProveedor = array (
						'num_telefono' => $_REQUEST['movilNat'],
						'extension_telefono' => null,
						'tipo' => '2'
				);
				
				$cadenaSql = $this->miSql->getCadenaSql("insertarInformacionProveedorTelefono",$datosTelefonoMovilPersonaProveedor);
				$id_TelefonoMovil = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda", $datosTelefonoMovilPersonaProveedor, "insertarInformacionProveedorTelefono");
				//array_push($SQLs, $cadenaSqlTelMovil);
				
				
				
				
				$datosTelefonoProveedorTipoA = array (
						'fki_id_tel' => $id_TelefonoFijo[0][0],
						'fki_id_Proveedor' => "currval('agora.prov_proveedor_info_id_proveedor_seq')"
				);
				
				$cadenaSqlResTelFijo = $this->miSql->getCadenaSql("insertarInformacionProveedorXTelefono",$datosTelefonoProveedorTipoA);
				//$resultadoTelefonoFijo = $esteRecursoDB->ejecutarAcceso($cadenaSql, "acceso");
				array_push($SQLs, $cadenaSqlResTelFijo);
				
				
				
				$datosTelefonoProveedorTipoB = array (
						'fki_id_tel' => $id_TelefonoMovil[0][0],
						'fki_id_Proveedor' => "currval('agora.prov_proveedor_info_id_proveedor_seq')"
				);
				
				$cadenaSqlResTelFijo = $this->miSql->getCadenaSql("insertarInformacionProveedorXTelefono",$datosTelefonoProveedorTipoB);
				//$resultadoTelefonoMovil = $esteRecursoDB->ejecutarAcceso($cadenaSql, "acceso");
				array_push($SQLs, $cadenaSqlResTelFijo);
				
				
				
				$datosInformacionPersonaNatural = array (
					    'id_tipo_documento' =>	$_REQUEST['tipoDocumentoNat'],
						'fki_numero_documento' => $_REQUEST['documentoNat'],
						'digito_verificacion' => $_REQUEST['digitoNat'],
						'primer_apellido' => $_REQUEST['primerApellidoNat'],
						'segundo_apellido' => $_REQUEST['segundoApellidoNat'],
						'primer_nombre' => $_REQUEST['primerNombreNat'],
						'segundo_nombre' => $_REQUEST['segundoNombreNat'],
						'genero' => $_REQUEST['generoNat'],
						'cargo' => null,
						'id_pais_nacimiento' => $_REQUEST['paisNacimientoNat'],
						'id_perfil' => $_REQUEST['perfilNat'],
						'id_nucleo_basico' => $_REQUEST['personaNaturalNBC'],
						'profesion' => $_REQUEST['profesionNat'],
						'especialidad' => $_REQUEST['especialidadNat'],
						'monto_capital_autorizado' => $_REQUEST['montoNat'],
						'grupoEtnico' => $_REQUEST['grupoEtnico'],
						'comunidadLGBT' => $_REQUEST['comunidadLGBT'],
						'cabezaFamilia' => $_REQUEST['cabezaFamilia'],
						'personasCargo' => $_REQUEST['personasCargo'],
						'numeroPersonasCargo' => $_REQUEST['numeroPersonasCargo'],
						'estadoCivil' => $_REQUEST['estadoCivil'],
						'discapacidad' => $_REQUEST['discapacidad'],
						'tipoDiscapacidad' => $_REQUEST['tipoDiscapacidad'],
						'declarante_renta' => $_REQUEST ['declaranteRentaNat'],//AGREGADO Beneficios Tributarios *****************
						'medicina_prepagada' => $_REQUEST ['medicinaPrepagadaNat'],
						'valor_uvt_prepagada' => $_REQUEST ['numeroUVTNat'],
						'cuenta_ahorro_afc' => $_REQUEST ['cuentaAFCNat'],
						'num_cuenta_bancaria_afc' => $_REQUEST ['numeroCuentaAFCNat'],
						'id_entidad_bancaria_afc' => $_REQUEST ['entidadBancariaAFCNat'],
						'interes_vivienda_afc' => $_REQUEST ['interesViviendaAFCNat'],
						'dependiente_hijo_menor_edad' => $_REQUEST ['hijosMenoresEdadNat'],
						'dependiente_hijo_menos23_estudiando' => $_REQUEST ['hijosMayoresEdadEstudiandoNat'],
						'dependiente_hijo_mas23_discapacitado' => $_REQUEST ['hijosMayoresEdadMas23Nat'],
						'dependiente_conyuge' => $_REQUEST ['conyugeDependienteNat'],
						'dependiente_padre_o_hermano' => $_REQUEST ['padresHermanosDependienteNat'],
						'id_eps' => $_REQUEST ['afiliacionEPSNat'],
						'id_fondo_pension' => $_REQUEST ['afiliacionPensionNat'],
						'id_caja_compensacion' => $_REQUEST ['afiliacionCajaNat'],
						'fecha_expedicion_doc' => $_REQUEST ['fechaExpeNat'],
						'id_lugar_expedicion_doc' => $_REQUEST ['ciudadExpeNat']
				);
				
				
				
				//Guardar datos PROVEEDOR NATURAL
				$cadenaSqlPerNatural = $this->miSql->getCadenaSql ( "registrarProveedorNatural", $datosInformacionPersonaNatural );
				//$resultadoPersonaNatural = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso' );
				array_push($SQLs, $cadenaSqlPerNatural);
				
				
				
				
				
				// REGISTRO LA ACTIVIDAD
				$arregloCIIU = array (
						'nit' => $_REQUEST['documentoNat'],
						'actividad' => $_REQUEST ['claseCIIUNat']
				);
				// Guardar ACTIVIDAD
				$cadenaSqlActividad = $this->miSql->getCadenaSql ( "registrarActividad", $arregloCIIU );
				//$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso' );
				array_push($SQLs, $cadenaSqlActividad);
				
				
				
				
				
				
				
				
				$registroPersona = $esteRecursoDB->transaccion($SQLs);
				
				
				
				if ($registroPersona != false) {
						
						
						

					if(isset($_REQUEST['tipoDocumentoNat'])){
					switch($_REQUEST['tipoDocumentoNat']){
						case 5 :
							$abvUser='RC';
							break;
						case 6 :
							$abvUser='TI';
							break;
						case 7 :
							$abvUser='CC';
							break;
						case 8 :
							$abvUser='CRSI';
							break;
						case 9 :
							$abvUser='TE';
							break;
						case 10 :
							$abvUser='CE';
							break;
						case 12 :
							$abvUser='IEDD';
							break;
						case 13 :
							$abvUser='PAS';
							break;
						case 14 :
							$abvUser='DIE';
							break;
						case 15 :
							$abvUser='SIED';
							break;
						case 16 :
							$abvUser='DIEPJ';
							break;
						case 17 :
							$abvUser='CD';
							break;					
						default:
							$abvUser='NIT';
							break;
					}
				}


						//****************************************************************

							$_REQUEST ["nombre"] = $_REQUEST ["primerNombreNat"] . ' ' . $_REQUEST ["segundoNombreNat"];
							$_REQUEST ["apellido"] = $_REQUEST ["primerApellidoNat"] . ' ' . $_REQUEST ["segundoApellidoNat"];;

								$_REQUEST['identificacion'] = $_REQUEST['documentoNat'];
								$_REQUEST['tipo_identificacion'] = $abvUser;
								$_REQUEST['nombres'] = $_REQUEST ["nombre"];
								$_REQUEST['apellidos'] = $_REQUEST ["apellido"];
							    $_REQUEST['correo'] = $_REQUEST['correoNat'];
							    $_REQUEST['telefono'] = $_REQUEST['telefonoNat'];
							    $_REQUEST['subsistema'] = 2;
							    $_REQUEST['perfil'] = 7;
							    
							    $fechaEnd = date('Y-m-j');
							    $nuevafechaEnd = strtotime ( '+2 year' , strtotime ( $fechaEnd ) ) ;
							    $nuevafechaEnd = date ( 'Y-m-j' , $nuevafechaEnd );
							    
								$_REQUEST['fechaFin'] = $nuevafechaEnd;
								//****************************************************************


						
								$caracteres = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz"; 
						        $num = '1234567890';
						        $caracter = '=$';
						        $numerodeletras=5; 
						        $pass = "";
						        $keycar=$keyNum= "";
						        for($i=0;$i<$numerodeletras;$i++)
						            {$pass .= substr($caracteres,rand(0,strlen($caracteres)),1); }

						        $maxCar = strlen($caracter)-1;
						        $maxNum = strlen($num)-1;

						        for($j=0;$j < 1;$j++)
						               { $keycar .= $caracter{mt_rand(0,$maxCar)};}       
						        for($k=0;$k < 2;$k++)
						               { $keyNum .= $num{mt_rand(0,$maxNum)};}       
						        $pass=$pass.$keycar.$keyNum;       
						        $password = $this->miConfigurador->fabricaConexiones->crypto->codificarClave ( $pass );
						        $hoy = date("Y-m-d");

								$arregloDatos = array(
							                              'id_usuario'=>$_REQUEST['tipo_identificacion'].$_REQUEST['identificacion'],
							                              'nombres'=>$_REQUEST['nombres'],
							                              'apellidos'=>$_REQUEST['apellidos'],
							                              'correo'=>$_REQUEST['correo'],
							                              'telefono'=>$_REQUEST['telefono'],
							                              'subsistema'=>$_REQUEST['subsistema'],
							                              'perfil'=>$_REQUEST['perfil'],
							                              'password'=>$password,
							                              'pass'=>$pass,
							                              'fechaIni'  =>$hoy,
							                              'fechaFin'  =>$_REQUEST['fechaFin'],  
							                              'identificacion'=>$_REQUEST['identificacion'],
							                              'tipo_identificacion'=>$_REQUEST['tipo_identificacion'],  );


					
						            

						        $this->cadena_sql = $this->miSql->getCadenaSql("insertarUsuario", $arregloDatos);
						        $resultadoEstado = $frameworkRecursoDB->ejecutarAcceso($this->cadena_sql, "acceso");


						        $this->cadena_sql = $this->miSql->getCadenaSql("insertarPerfilUsuario", $arregloDatos);
						        $resultadoPerfil = $frameworkRecursoDB->ejecutarAcceso($this->cadena_sql, "acceso");    

						                
						        $parametro['id_usuario'] = $arregloDatos['id_usuario'];
						        $cadena_sql = $this->miSql->getCadenaSql("consultarPerfilUsuario", $parametro);
						        $resultadoPerfil = $frameworkRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
						                
						                $log = array('accion'=>"REGISTRO PERSONA NATURAL",
						                            'id_registro'=>$_REQUEST['tipo_identificacion'].$_REQUEST['identificacion'],
						                            'tipo_registro'=>"GESTION USUARIO NATURAL",
						                            'nombre_registro'=>"id_usuario=>".$_REQUEST['tipo_identificacion'].$_REQUEST['identificacion'].
						                                               "|identificacion=>".$_REQUEST['identificacion'].
						                                               "|tipo_identificacion=>".$_REQUEST['tipo_identificacion'].
						                                               "|nombres=>".$_REQUEST['nombres'].
						                                               "|apellidos=>".$_REQUEST['apellidos'].
						                                               "|correo=>".$_REQUEST['correo'].
						                                               "|telefono=>".$_REQUEST['telefono'].
						                                               "|subsistema=>".$_REQUEST['subsistema'].
						                                               "|perfil=>".$_REQUEST['perfil'].
						                                               "|fechaIni=>".$hoy.
						                                               "|fechaFin=>".$_REQUEST['fechaFin'],
						                            'descripcion'=>"Registro de nuevo Usuario ".$_REQUEST['tipo_identificacion'].$_REQUEST['identificacion']." con perfil ".$resultadoPerfil[0]['rol_alias'],
						                           );
								
								

						        $this->miLogger->log_usuario($log);

						        $arregloDatos['perfilUs'] = $resultadoPerfil[0]['rol_alias'];


						        $datosRegistroUsuario = array (
						        		'tipo_identificacion'=>$_REQUEST['tipo_identificacion'],
										'num_documento' => $_REQUEST ['documentoNat'],
										'contrasena' => $password,
										'generadaPass' => $pass,
										'tipo' => "Tercero",
										'rolMenu' => "Proveedor",
										'estado' => "Inactivo",
										'nombre' => $_REQUEST ["nombre"],
										'apellido' => $_REQUEST ["apellido"],
										'correo' => $_REQUEST['correoNat'],
										'telefono' => $_REQUEST['telefonoNat'],
										'id_usuario' => $arregloDatos['id_usuario']
								);	

								if($resultadoEstado && $resultadoPerfil){

									redireccion::redireccionar ( 'registroProveedor',  $datosRegistroUsuario);
									exit();

								}else{

									redireccion::redireccionar ( 'noregistroUsuario',  $_REQUEST['usuario']);
									exit();

								}

		
								
				} else {
								redireccion::redireccionar ( 'noregistro',  $_REQUEST['usuario']);
								exit();
				}
		
		}		
		
	


	}
	
	function resetForm() {
		foreach ( $_REQUEST as $clave => $valor ) {
			
			if ($clave != 'pagina' && $clave != 'development' && $clave != 'jquery' && $clave != 'tiempo') {
				unset ( $_REQUEST [$clave] );
			}
		}
	}
}

$miRegistrador = new Formulario ( $this->lenguaje, $this->sql, $this->funcion, $this->miLogger );

$resultado = $miRegistrador->procesarFormulario ();

?>
