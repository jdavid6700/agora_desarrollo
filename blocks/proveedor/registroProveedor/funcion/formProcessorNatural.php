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
	
	function campoSeguroCodificar($cadena, $tiempoRequest) {
	    /* +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
	    /* +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
	    /* ++++++++++++++++++++++++++++++++++++++++++++ OBTENER CAMPO POST (Codificar) +++++++++++++++++++++++++++++++++++++++++++ */
	    
	    $tiempo = (int) substr($tiempoRequest, 0, -2);
	    $tiempo = $tiempo * pow(10, 2);
	    
	    $campoSeguro = $this->miConfigurador->fabricaConexiones->crypto->codificar($cadena . $tiempo);
	    
	    
	    
	    
	    /* +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
	    /* +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
	    return $campoSeguro;
	}
	
	function campoSeguroDecodificar($campoSeguroRequest, $tiempoRequest) {
	    /* +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
	    /* +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
	    /* ++++++++++++++++++++++++++++++++++++++++++++ OBTENER CAMPO POST (Decodificar) +++++++++++++++++++++++++++++++++++++++++ */
	    
	    $tiempo = (int) substr($tiempoRequest, 0, -2);
	    $tiempo = $tiempo * pow(10, 2);
	    
	    $campoSeguro = $this->miConfigurador->fabricaConexiones->crypto->decodificar($campoSeguroRequest);
	    
	    $campo = str_replace($tiempo, "", $campoSeguro);
	    
	    /* +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
	    /* +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
	    return $campo;
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
	
		
		/*Variables Texto Enriquecido ----------------------------------------------------------*/
		/*--------------------------------------------------------------------------------------*/
		$descripcion = $_POST[$this->campoSeguroCodificar('descripcionNat', $_REQUEST['tiempo'])];
		$_REQUEST['descripcionNat'] = str_replace("'", "\"", $descripcion);
		
		
		
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


		$_REQUEST ['destino1'] = '';//soportesHijosNat
		$_REQUEST ['destino2'] = '';//declaracionRentaNat
		$_REQUEST ['destino3'] = '';//DocumentoRUTNat
		$_REQUEST ['destino4'] = '';//DocumentoRUPNat


		// Gestión Carga de Archivos PN
		//*******************************************************************************
		if($_FILES[$this->campoSeguroCodificar('soportesHijosNat', $_REQUEST['tiempo'])]){
			$archivo = $_FILES[$this->campoSeguroCodificar('soportesHijosNat', $_REQUEST['tiempo'])];
			// Obtenemos los datos del archivo
			$tamano = $archivo ['size'];
			$tipo = $archivo ['type'];
			$archivoName = $archivo ['name'];
			$prefijo = substr ( md5 ( uniqid ( rand () ) ), 0, 6 );
			$nombreDoc = $prefijo . "-" . $archivoName;
		
			if ($archivoName != "") {
				// Guardamos el archivo a la carpeta files
				$destino = $rutaBloque . "/files/" . $nombreDoc;
		
				if (copy ( $archivo ['tmp_name'], $destino )) {
					$status = "Archivo subido: <b>" . $archivoName . "</b>";
					$_REQUEST ['destino1'] = $prefijo . "-" . $archivoName;
				} else {
					$status = "<br>Error al subir el archivo";
				}
			} else {
				$status = "<br>Error al subir archivo";
			}
		}else{
			echo "<br>NO existe el archivo !!!";
		}
		//*******************************************************************************
		if($_FILES[$this->campoSeguroCodificar('declaracionRentaNat', $_REQUEST['tiempo'])]){
			$archivo = $_FILES[$this->campoSeguroCodificar('declaracionRentaNat', $_REQUEST['tiempo'])];
			// Obtenemos los datos del archivo
			$tamano = $archivo ['size'];
			$tipo = $archivo ['type'];
			$archivoName = $archivo ['name'];
			$prefijo = substr ( md5 ( uniqid ( rand () ) ), 0, 6 );
			$nombreDoc = $prefijo . "-" . $archivoName;
		
			if ($archivoName != "") {
				// Guardamos el archivo a la carpeta files
				$destino = $rutaBloque . "/files/" . $nombreDoc;
		
				if (copy ( $archivo ['tmp_name'], $destino )) {
					$status = "Archivo subido: <b>" . $archivoName . "</b>";
					$_REQUEST ['destino2'] = $prefijo . "-" . $archivoName;
				} else {
					$status = "<br>Error al subir el archivo";
				}
			} else {
				$status = "<br>Error al subir archivo";
			}
		}else{
			echo "<br>NO existe el archivo !!!";
		}
		//*******************************************************************************
		if($_FILES[$this->campoSeguroCodificar('DocumentoRUTNat', $_REQUEST['tiempo'])]){
			$archivo = $_FILES[$this->campoSeguroCodificar('DocumentoRUTNat', $_REQUEST['tiempo'])];
			// Obtenemos los datos del archivo
			$tamano = $archivo ['size'];
			$tipo = $archivo ['type'];
			$archivoName = $archivo ['name'];
			$prefijo = substr ( md5 ( uniqid ( rand () ) ), 0, 6 );
			$nombreDoc = $prefijo . "-" . $archivoName;
		
			if ($archivoName != "") {
				// Guardamos el archivo a la carpeta files
				$destino = $rutaBloque . "/files/" . $nombreDoc;
		
				if (copy ( $archivo ['tmp_name'], $destino )) {
					$status = "Archivo subido: <b>" . $archivoName . "</b>";
					$_REQUEST ['destino3'] = $prefijo . "-" . $archivoName;
				} else {
					$status = "<br>Error al subir el archivo";
				}
			} else {
				$status = "<br>Error al subir archivo";
			}
		}else{
			echo "<br>NO existe el archivo !!!";
		}
		//*******************************************************************************
		if($_FILES[$this->campoSeguroCodificar('DocumentoRUPNat', $_REQUEST['tiempo'])]){
			$archivo = $_FILES[$this->campoSeguroCodificar('DocumentoRUPNat', $_REQUEST['tiempo'])];
			// Obtenemos los datos del archivo
			$tamano = $archivo ['size'];
			$tipo = $archivo ['type'];
			$archivoName = $archivo ['name'];
			$prefijo = substr ( md5 ( uniqid ( rand () ) ), 0, 6 );
			$nombreDoc = $prefijo . "-" . $archivoName;
		
			if ($archivoName != "") {
				// Guardamos el archivo a la carpeta files
				$destino = $rutaBloque . "/files/" . $nombreDoc;
		
				if (copy ( $archivo ['tmp_name'], $destino )) {
					$status = "Archivo subido: <b>" . $archivoName . "</b>";
					$_REQUEST ['destino4'] = $prefijo . "-" . $archivoName;
				} else {
					$status = "<br>Error al subir el archivo";
				}
			} else {
				$status = "<br>Error al subir archivo";
			}
		}else{
			echo "<br>NO existe el archivo !!!";
		}
		//*******************************************************************************


		
		if(isset($_REQUEST['tipoPersona'])){//CAST
			switch($_REQUEST['tipoPersona']){
				case 1 :
					$_REQUEST['tipoPersona']='NATURAL';
					break;
				case 2 :
					$_REQUEST['tipoPersona']='JURIDICA';
					break;
			}
		}
		
		
		$arregloUnique = array (
				'num_documento' => $_REQUEST['documentoNat'],
				'tipo_persona' => $_REQUEST ['tipoPersona']
		);


		unset($resultado);
		//VERIFICAR SI EL PROVEEDOR YA SE ENCUENTRA REGISTRADO
		$cadenaSql = $this->miSql->getCadenaSql ( "verificarProveedor", $arregloUnique);
		$resultadoVerificar = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'busqueda' );
		
		
		if ($resultadoVerificar) {
			//El proveedor ya existe
			redireccion::redireccionar ( 'existeProveedor',  $_REQUEST ['documentoNat']);
			exit();    
		}else{
				
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
				
				if(isset($_REQUEST['grupoEtnico'])){
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
							$_REQUEST ['grupoEtnico']='NO APLICA';
							break;
						}
				}
				
				if(isset($_REQUEST['estadoCivil'])){
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
				
				
				if(isset($_REQUEST['tipoDiscapacidad'])){
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

				if(isset($_REQUEST['hijosPersona'])){
					switch($_REQUEST ['hijosPersona']){
						case 1 :
							$_REQUEST ['hijosPersona']='TRUE';
							break;
						case 2 :
							$_REQUEST ['hijosPersona']='FALSE';
							break;
						default:
							$_REQUEST ['hijosPersona']='NULL';
							break;
					}
				}
				if(isset($_REQUEST['pensionadoNat'])){
					switch($_REQUEST ['pensionadoNat']){
						case 1 :
							$_REQUEST ['pensionadoNat']='TRUE';
							break;
						case 2 :
							$_REQUEST ['pensionadoNat']='FALSE';
							break;
						default:
							$_REQUEST ['pensionadoNat']='NULL';
							break;
					}
				}
				
				//***********************************************************************************************
				
				
				//CAST****************************************************************
				$dateExp = explode("/", $_REQUEST ['fechaExpeNat']);
				$cadena_fecha = $dateExp[2]."-".$dateExp[1]."-".$dateExp[0];
				$_REQUEST ['fechaExpeNat'] = $cadena_fecha;
				//********************************************************************

				//CAST****************************************************************
				$dateNac = explode("/", $_REQUEST ['fechaNacNat']);
				$cadena_fecha = $dateNac[2]."-".$dateNac[1]."-".$dateNac[0];
				$_REQUEST ['fechaNacNat'] = $cadena_fecha;
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
						'anexo_rut' => $_REQUEST ['destino3'],
						'anexo_rup' => $_REQUEST ['destino4'],
						'descripcion_proveedor' => $_REQUEST['descripcionNat'],
						'fecha_registro' => $fechaActual,
						'fecha_modificación' => $fechaActual,
						'id_estado' => '1' //Estado Inactivo
				);
				
				//Guardar datos TABLA PROVEEDOR
				$cadenaSqlInfoProveedor = $this->miSql->getCadenaSql("insertarInformacionProveedor",$datosInformacionProveedorPersonaNatural);
				array_push($SQLs, $cadenaSqlInfoProveedor);
				
				
				
				$datosTelefonoFijoPersonaProveedor = array (
						'num_telefono' => $_REQUEST['telefonoNat'],
						'extension_telefono' => $_REQUEST['extensionNat'],
						'tipo' => '1'
				);
				
				
				$cadenaSqlTelFijo = $this->miSql->getCadenaSql("insertarInformacionProveedorTelefono",$datosTelefonoFijoPersonaProveedor);
				array_push($SQLs, $cadenaSqlTelFijo);
				
				
				
				
				$datosTelefonoProveedorTipoA = array (
						'fki_id_tel' => "currval('agora.prov_proveedor_telefono')",
						'fki_id_Proveedor' => "currval('agora.prov_proveedor_info_id_proveedor_seq')"
				);
				
				$cadenaSqlResTelFijo = $this->miSql->getCadenaSql("insertarInformacionProveedorXTelefono",$datosTelefonoProveedorTipoA);
				array_push($SQLs, $cadenaSqlResTelFijo);
				
				
				
				
				
				$datosTelefonoMovilPersonaProveedor = array (
						'num_telefono' => $_REQUEST['movilNat'],
						'extension_telefono' => null,
						'tipo' => '2'
				);
				
				$cadenaSqlTelMovil = $this->miSql->getCadenaSql("insertarInformacionProveedorTelefono",$datosTelefonoMovilPersonaProveedor);
				array_push($SQLs, $cadenaSqlTelMovil);
				
				

				
				$datosTelefonoProveedorTipoB = array (
						'fki_id_tel' => "currval('agora.prov_proveedor_telefono')",
						'fki_id_Proveedor' => "currval('agora.prov_proveedor_info_id_proveedor_seq')"
				);
				
				$cadenaSqlResTelFijo = $this->miSql->getCadenaSql("insertarInformacionProveedorXTelefono",$datosTelefonoProveedorTipoB);
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
						'declarante_renta' => $_REQUEST ['declaranteRentaNat'],
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
						'id_lugar_expedicion_doc' => $_REQUEST ['ciudadExpeNat'],
						'anexo_hijos' => $_REQUEST ['destino1'],
						'anexo_declaracion' => $_REQUEST ['destino2'],
						'hijos' => $_REQUEST ['hijosPersona'],
						'numero_hijos' => $_REQUEST ['numeroHijosPersona'],
						'pensionado' => $_REQUEST ['pensionadoNat'],
						'fecha_nacimiento' => $_REQUEST ['fechaNacNat']
				);
				
				
				//Guardar datos PROVEEDOR NATURAL
				$cadenaSqlPerNatural = $this->miSql->getCadenaSql ( "registrarProveedorNatural", $datosInformacionPersonaNatural );
				array_push($SQLs, $cadenaSqlPerNatural);
				
				
				// REGISTRO LA ACTIVIDAD
				$arregloCIIU = array (
						'fk_id_proveedor' => "currval('agora.prov_proveedor_info_id_proveedor_seq')",
						'num_documento' => $_REQUEST['documentoNat'],
						'actividad' => $_REQUEST ['claseCIIUNat']
				);
				// Guardar ACTIVIDAD
				$cadenaSqlActividad = $this->miSql->getCadenaSql ( "registrarActividad", $arregloCIIU );
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
						        
						        $c = 0;
						        while ($c < count($SQLs)){
						        	$SQLsDec[$c] = $this->miConfigurador->fabricaConexiones->crypto->codificar($SQLs[$c]);
						        	$c++;
						        }
						        $query = json_encode($SQLsDec);
						        
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
				                		    'query'=> $query,
				                           );
								
								

						        $this->miLogger->log_usuario($log);

						        $arregloDatos['perfilUs'] = $resultadoPerfil[0]['rol_alias'];


						        $datosRegistroUsuario = array (
						        		'tipo_identificacion'=>$_REQUEST['tipo_identificacion'],
										'num_documento' => $_REQUEST ['documentoNat'],
						        		'tipo_persona' => $_REQUEST ['tipoPersona'],
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




						        if (!empty($_SERVER['HTTP_CLIENT_IP'])){
									$ip = $_SERVER['HTTP_CLIENT_IP'];
								}elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
									$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
								}else{
									$ip = $_SERVER['REMOTE_ADDR'];
								}
								$c = 0;
								while ($c < count($SQLs)){
									$SQLsDec[$c] = $this->miConfigurador->fabricaConexiones->crypto->codificar($SQLs[$c]);
									$c++;
								}
								$query = json_encode($SQLsDec);
								$error = json_encode(error_get_last());
									
								$datosLog = array (
										'tipo_log' => 'REGISTRO',
										'tipo_persona' => 'NATURAL',
										'documento' => $_REQUEST['documentoNat'],
										'query' => $query,
										'data' => null,
										'host' => $ip,
										'fecha_log' => date("Y-m-d H:i:s"),
										'usuario' => 'REG777'
								);
								$cadenaSQL = $this->miSql->getCadenaSql("insertarLogProveedorBnRg", $datosLog);
								$resultadoLog = $frameworkRecursoDB->ejecutarAcceso($cadenaSQL, 'busqueda');


								if($resultadoEstado && $resultadoPerfil){

									redireccion::redireccionar ( 'registroProveedor',  $datosRegistroUsuario);
									exit();

								}else{

									redireccion::redireccionar ( 'noregistroUsuario',  $_REQUEST['usuario']);
									exit();

								}

		
								
				} else {
					
					if (!empty($_SERVER['HTTP_CLIENT_IP'])){
						$ip = $_SERVER['HTTP_CLIENT_IP'];
					}elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
						$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
					}else{
						$ip = $_SERVER['REMOTE_ADDR'];
					}
					$c = 0;
					while ($c < count($SQLs)){
						$SQLsDec[$c] = $this->miConfigurador->fabricaConexiones->crypto->codificar($SQLs[$c]);
						$c++;
					}
					$query = json_encode($SQLsDec);
					$error = json_encode(error_get_last());
						
					$datosLog = array (
							'tipo_log' => 'REGISTRO',
							'tipo_persona' => 'NATURAL',
							'documento' => $_REQUEST['documentoNat'],
							'query' => $query,
							'error' => $error,
							'host' => $ip,
							'fecha_log' => date("Y-m-d H:i:s"),
							'usuario' => 'REG777'
					);
					$cadenaSQL = $this->miSql->getCadenaSql("insertarLogProveedor", $datosLog);
					$resultadoLog = $frameworkRecursoDB->ejecutarAcceso($cadenaSQL, 'busqueda');
					
					$caso = "RC-" . date("Y") . "-" . $resultadoLog[0][0];
					
								redireccion::redireccionar ( 'noregistro',  $caso);
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
