<?php

namespace administracion\consultaGeneralProveedor\funcion;

use administracion\consultaGeneralProveedor\funcion\redireccionar;

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
	
	function __construct($lenguaje, $sql, $funcion) {
		
		$this->miConfigurador = \Configurador::singleton ();
		$this->miConfigurador->fabricaConexiones->setRecursoDB ( 'principal' );
		$this->lenguaje = $lenguaje;
		$this->miSql = $sql;
		$this->miFuncion = $funcion;
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
		
		$rutaBloque = $this->miConfigurador->getVariableConfiguracion ( "raizDocumento" ) . "/blocks/administracion/";
		$rutaBloque .= $esteBloque ['nombre'];
		$host = $this->miConfigurador->getVariableConfiguracion ( "host" ) . $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/administracion/" . $esteBloque ['nombre'];
		
		$hostFiles = $this->miConfigurador->getVariableConfiguracion ( "host" ) . $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/proveedor/registroProveedor";

		
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
		
		
		unset($resultado);
			
		//Guardar RUT adjuntado Persona Natural*************************************************************************************
		$_REQUEST ['destino'] = '';
		// Guardar el archivo
		if ($_FILES) {
			$i = 0;
			foreach ( $_FILES as $key => $values ) {
				$archivoCarga[$i] = $_FILES [$key];
				$i++;
			}
			$archivo = $archivoCarga[0];
			// obtenemos los datos del archivo
			$tamano = $archivo ['size'];
			$tipo = $archivo ['type'];
			$archivo1 = $archivo ['name'];
			$prefijo = substr ( md5 ( uniqid ( rand () ) ), 0, 6 );
			$nombreDoc = $prefijo . "-" . $archivo1;
				
			if ($archivo1 != "") {
				$CambioARCHIVO = true;
				// guardamos el archivo a la carpeta files
				
				$rutaBloqueChange = $this->miConfigurador->getVariableConfiguracion ( "raizDocumento" ) . "/blocks/proveedor/registroProveedor";
				
				$destino = $rutaBloqueChange . "/files/" . $nombreDoc;
		
				if (copy ( $archivo ['tmp_name'], $destino )) {
					
					$hostChange = $this->miConfigurador->getVariableConfiguracion ( "host" ) . $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/proveedor/registroProveedor";
					
					$status = "Archivo subido: <b>" . $archivo1 . "</b>";
					$_REQUEST ['destino'] = $hostChange . "/files/" . $prefijo . "-" . $archivo1;
					
					//Actualizar RUT
					$cadenaSql = $this->miSql->getCadenaSql ( "actualizarRUT", $_REQUEST );
					$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso' );
					
				} else {
					$status = "<br>Error al subir el archivo1";
				}
			} else {
				$CambioARCHIVO = false;
				$status = "<br>Error al subir archivo2";
			}
		} else {
			echo "<br>NO existe el archivo D:!!!";
		}
		//***************************************************************************************************************************
		
		
		//Guardar RUP adjuntado Persona Natural*************************************************************************************
		$_REQUEST ['destino2'] = '';
		// Guardar el archivo
		if ($_FILES) {
			$i = 0;
			foreach ( $_FILES as $key => $values ) {
				$archivoCarga[$i] = $_FILES [$key];
				$i++;
			}
			$archivo = $archivoCarga[1];
			// obtenemos los datos del archivo
			$tamano = $archivo ['size'];
			$tipo = $archivo ['type'];
			$archivo1 = $archivo ['name'];
			$prefijo = substr ( md5 ( uniqid ( rand () ) ), 0, 6 );
			$nombreDoc = $prefijo . "-" . $archivo1;
		
			if ($archivo1 != "") {
				$CambioARCHIVO2 = true;
				// guardamos el archivo a la carpeta files
				
				$rutaBloqueChange = $this->miConfigurador->getVariableConfiguracion ( "raizDocumento" ) . "/blocks/proveedor/registroProveedor";
				
				$destino = $rutaBloqueChange . "/files/" . $nombreDoc;
		
				if (copy ( $archivo ['tmp_name'], $destino )) {
					
					$hostChange = $this->miConfigurador->getVariableConfiguracion ( "host" ) . $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/proveedor/registroProveedor";
					
					$status = "Archivo subido: <b>" . $archivo1 . "</b>";
					$_REQUEST ['destino2'] = $hostChange . "/files/" . $prefijo . "-" . $archivo1;
						
					//Actualizar RUP
					$cadenaSql = $this->miSql->getCadenaSql ( "actualizarRUP", $_REQUEST );
					$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso' );
						
				} else {
					$status = "<br>Error al subir el archivo1";
				}
			} else {
				$CambioARCHIVO2 = false;
				$status = "<br>Error al subir archivo2";
			}
		} else {
			echo "<br>NO existe el archivo D:!!!";
		}
		//***************************************************************************************************************************
		
		
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
				
				
				//CAST****************************************************************
				$dateExp = explode("/", $_REQUEST ['fechaExpeNat']);
				$cadena_fecha = $dateExp[2]."-".$dateExp[1]."-".$dateExp[0];
				$_REQUEST ['fechaExpeNat'] = $cadena_fecha;
				//********************************************************************
				
				
				
				
				$nombrePersona = $_REQUEST['primerNombreNat'] . ' ' . $_REQUEST['segundoNombreNat'] . ' ' . $_REQUEST['primerApellidoNat'] . ' ' . $_REQUEST['segundoApellidoNat'];
				
				$fechaActualCambio = date ( 'Y-m-d' . ' - ' .'h:i:s A');
		
				
				$datosInformacionProveedorPersonaNatural = array (
						'id_Proveedor' => $_REQUEST['id_Proveedor'],
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
						'descripcion_proveedor' => $_REQUEST['descripcionNat'],
						'fecha_modificación' => $fechaActualCambio,
				);
				
				$cadenaSql = $this->miSql->getCadenaSql ( "actualizarInformacionProveedor", $datosInformacionProveedorPersonaNatural );
				$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso' );
				
				
				
				$datosTelefonoFijoPersonaProveedor = array (
						'id_telefono' => $_REQUEST['id_TelefonoNat'],
						'num_telefono' => $_REQUEST['telefonoNat'],
						'extension_telefono' => $_REQUEST['extensionNat'],
						'tipo' => '1'
				);
				
				
				$cadenaSql = $this->miSql->getCadenaSql("actualizarInformacionProveedorTelefono",$datosTelefonoFijoPersonaProveedor);
				$id_TelefonoFijo = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
				
				$datosTelefonoMovilPersonaProveedor = array (
						'id_telefono' => $_REQUEST['id_MovilNat'],
						'num_telefono' => $_REQUEST['movilNat'],
						'extension_telefono' => null,
						'tipo' => '2'
				);
				
				$cadenaSql = $this->miSql->getCadenaSql("actualizarInformacionProveedorTelefono",$datosTelefonoMovilPersonaProveedor);
				$id_TelefonoMovil = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
				
				
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
				$cadenaSql = $this->miSql->getCadenaSql ( "actualizarProveedorNatural", $datosInformacionPersonaNatural );
				$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso' );
				
				if ($resultado) {
					redireccion::redireccionar ( 'actualizo',  $_REQUEST['documentoNat']);
					exit();
				} else {
					redireccion::redireccionar ( 'noActualizo',  $_REQUEST['documentoNat']);
					exit();
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

$miRegistrador = new Formulario ( $this->lenguaje, $this->sql, $this->funcion );

$resultado = $miRegistrador->procesarFormulario ();

?>
