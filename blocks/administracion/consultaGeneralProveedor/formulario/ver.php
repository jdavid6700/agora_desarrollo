<?php

namespace desarrollo\bloqueBase\formulario;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}
class Formulario {
	var $miConfigurador;
	var $lenguaje;
	var $miFormulario;
	var $miSql;
	function __construct($lenguaje, $formulario, $sql) {
		$this->miConfigurador = \Configurador::singleton ();
		$this->miConfigurador->fabricaConexiones->setRecursoDB ( 'principal' );
		$this->lenguaje = $lenguaje;
		$this->miFormulario = $formulario;
		$this->miSql = $sql;
	}
	function formulario() {

		// Rescatar los datos de este bloque
		$conexion = "estructura";
		$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
            
		$esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );
		
		// ---------------- SECCION: Parámetros Globales del Formulario ----------------------------------
		/**
		 * Atributos que deben ser aplicados a todos los controles de este formulario.
		 * Se utiliza un arreglo
		 * independiente debido a que los atributos individuales se reinician cada vez que se declara un campo.
		 *
		 * Si se utiliza esta técnica es necesario realizar un mezcla entre este arreglo y el específico en cada control:
		 * $atributos= array_merge($atributos,$atributosGlobales);
		 */
		$atributosGlobales ['campoSeguro'] = 'true';
		
		$_REQUEST ['tiempo'] = time ();
		$tiempo = $_REQUEST ['tiempo'];
		               
		// ---------------- SECCION: Parámetros Generales del Formulario ----------------------------------
		$esteCampo = $esteBloque ['nombre'];
		$atributos ['id'] = $esteCampo;
		$atributos ['nombre'] = $esteCampo;
		// Si no se coloca, entonces toma el valor predeterminado 'application/x-www-form-urlencoded'
		$atributos ['tipoFormulario'] = 'multipart/form-data';
		// Si no se coloca, entonces toma el valor predeterminado 'POST'
		$atributos ['metodo'] = 'POST';
		// Si no se coloca, entonces toma el valor predeterminado 'index.php' (Recomendado)
		$atributos ['action'] = 'index.php';
		// $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo );
		// Si no se coloca, entonces toma el valor predeterminado.
		$atributos ['estilo'] = '';
		$atributos ['marco'] = false;
		$tab = 1;
		// ---------------- FIN SECCION: de Parámetros Generales del Formulario ----------------------------
		// ----------------INICIAR EL FORMULARIO ------------------------------------------------------------
		$atributos ['tipoEtiqueta'] = 'inicio';
		echo $this->miFormulario->formulario ( $atributos );
		{
			// ---------------- SECCION: Controles del Formulario -----------------------------------------------
			
			$miPaginaActual = $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
			
			$rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
			$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/";
			$rutaBloque.= $esteBloque['grupo'] . "/" . $esteBloque['nombre'];
                        
			$directorio = $this->miConfigurador->getVariableConfiguracion ( "host" );
			$directorio .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/index.php?";
			$directorio .= $this->miConfigurador->getVariableConfiguracion ( "enlace" );
			
			$variable = "pagina=" . $miPaginaActual;
			$variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variable, $directorio );
			
			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------

		// ---------------- INICIO: Lista Variables Modificar--------------------------------------------------------
			
		$cadenaSql = $this->miSql->getCadenaSql ( 'consultar_tipo_proveedor', $_REQUEST["idProveedor"] );
		$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
		$_REQUEST['usuario'] = $resultado[0]['id_usuario'];
		
		if(isset($resultado[0][1])){//CAST genero tipoCuenta
			switch($resultado[0][1]){
				case 'NATURAL' :
					$_REQUEST['tipoPersona'] = 1;
					$_TIPO = 1;
					break;
				case 'JURIDICA' :
					$_REQUEST['tipoPersona'] = 2;
					$_TIPO = 2;
					break;
			}
		}
		
		$esteCampo = 'tipoPersona_Update';
		$atributos ["id"] = $esteCampo; // No cambiar este nombre
		$atributos ["tipo"] = "hidden";
		$atributos ['estilo'] = '';
		$atributos ["obligatorio"] = false;
		$atributos ['marco'] = true;
		$atributos ["etiqueta"] = "";
		$atributos ['valor'] = $_REQUEST['tipoPersona'];
		$atributos = array_merge($atributos, $atributosGlobales);
		echo $this->miFormulario->campoCuadroTexto($atributos);
		unset($atributos);
		
		$fechaModificacion = date ( 'Y-m-d' . ' - ' .'h:i:s A');
		
		if($_TIPO == 1){
			
				
				//******************************************************************************************************NATURAL****************************************
				
				$cadenaSql = $this->miSql->getCadenaSql ( 'buscarProveedorByUsuario', $_REQUEST["usuario"] );
				$resultadoPersonaNatural = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
				
				$_REQUEST['fki_idProveedor'] =  $resultadoPersonaNatural[0]["id_proveedor"];
				$_REQUEST['tipoPersona'] =  $resultadoPersonaNatural[0]["tipopersona"];
				$_REQUEST['documentoNat'] =  $resultadoPersonaNatural[0]['num_documento'];
				$_REQUEST['fki_idciudadContacto'] =  $resultadoPersonaNatural[0]["id_ciudad_contacto"];
				$_REQUEST['direccionNat'] =  $resultadoPersonaNatural[0]['direccion'];
				$_REQUEST['correoNat'] =  $resultadoPersonaNatural[0]["correo"];
				$_REQUEST['sitioWebNat'] =  $resultadoPersonaNatural[0]['web'];
				$_REQUEST['asesorComercialNat'] =  $resultadoPersonaNatural[0]['nom_asesor'];
				$_REQUEST['telAsesorNat'] =  $resultadoPersonaNatural[0]["tel_asesor"];
				$_REQUEST['descripcionNat'] =  $resultadoPersonaNatural[0]["descripcion"];
				$_REQUEST['DocumentoRUTNat'] =  $resultadoPersonaNatural[0]['anexorut'];
				$_REQUEST['tipoCuentaNat'] =  $resultadoPersonaNatural[0]["tipo_cuenta_bancaria"];
				$_REQUEST['numeroCuentaNat'] =  $resultadoPersonaNatural[0]["num_cuenta_bancaria"];
				$_REQUEST['entidadBancariaNat'] =  $resultadoPersonaNatural[0]["id_entidad_bancaria"];
				$_REQUEST['fecha_RegistroNat'] =  $resultadoPersonaNatural[0]["fecha_registro"];
				$_REQUEST['fecha_CambiosNat'] =  $fechaModificacion ;
				$_REQUEST['estadoNat'] =  $resultadoPersonaNatural[0]["estado"];
		
				 
				$_REQUEST['personaNaturalContaCiudad'] = $_REQUEST['fki_idciudadContacto'];
				
				
				$cadena_sql = $this->miSql->getCadenaSql ( 'consultarContactoTelProveedor', $resultadoPersonaNatural[0]['num_documento'] );
				$resultadoTel = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
				$_REQUEST['telefonoNat'] = $resultadoTel[0]['numero_tel'];
				$_REQUEST['extensionNat'] = $resultadoTel[0]['extension'];
			
				
				$cadena_sql = $this->miSql->getCadenaSql ( 'consultarContactoMovilProveedor', $resultadoPersonaNatural[0]['num_documento'] );
				$resultadoMovil = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
				$_REQUEST['movilNat'] = $resultadoMovil[0]['numero_tel'];
				
				
				
				$cadenaSql = $this->miSql->getCadenaSql ( 'consultarProveedorNatural', $resultadoPersonaNatural[0]['num_documento'] );
				$resultadoPersonaNaturalInfo = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
				
				
				$_REQUEST['tipoDocumentoNat'] =  $resultadoPersonaNaturalInfo[0]["tipo_documento"];
				$_REQUEST['primerApellidoNat'] =  $resultadoPersonaNaturalInfo[0]["primer_apellido"];
				$_REQUEST['segundoApellidoNat'] =  $resultadoPersonaNaturalInfo[0]["segundo_apellido"];
				$_REQUEST['primerNombreNat'] =  $resultadoPersonaNaturalInfo[0]["primer_nombre"];
				$_REQUEST['segundoNombreNat'] =  $resultadoPersonaNaturalInfo[0]["segundo_nombre"];
				$_REQUEST['generoNat'] =  $resultadoPersonaNaturalInfo[0]["genero"];
				$_REQUEST['cargoNat'] =  $resultadoPersonaNaturalInfo[0]["cargo"];
				$_REQUEST['paisNacimientoNat'] =  $resultadoPersonaNaturalInfo[0]["id_pais_nacimiento"];
				$_REQUEST['perfilNat'] =  $resultadoPersonaNaturalInfo[0]["perfil"];
				$_REQUEST['montoNat'] =  $resultadoPersonaNaturalInfo[0]["monto_capital_autorizado"];
				$_REQUEST['profesionNat'] =  $resultadoPersonaNaturalInfo[0]["profesion"];
				$_REQUEST['especialidadNat'] =  $resultadoPersonaNaturalInfo[0]["especialidad"];
				
				$_REQUEST['grupoEtnico'] = $resultadoPersonaNaturalInfo[0]["grupo_etnico"];
				$_REQUEST['comunidadLGBT'] = $resultadoPersonaNaturalInfo[0]["comunidad_lgbt"];
				$_REQUEST['cabezaFamilia'] = $resultadoPersonaNaturalInfo[0]["cabeza_familia"];
				$_REQUEST['personasCargo'] = $resultadoPersonaNaturalInfo[0]["personas_a_cargo"];
				$_REQUEST['numeroPersonasCargo'] = $resultadoPersonaNaturalInfo[0]["numero_personas_a_cargo"];
				$_REQUEST['estadoCivil'] = $resultadoPersonaNaturalInfo[0]["estado_civil"];
				$_REQUEST['discapacidad'] = $resultadoPersonaNaturalInfo[0]["discapacitado"];
				$_REQUEST['tipoDiscapacidad'] = $resultadoPersonaNaturalInfo[0]["tipo_discapacidad"];
				
				
				if(isset($_REQUEST['perfilNat'])){//CAST
					switch($_REQUEST['perfilNat']){
						case 18 :
							$_REQUEST ['perfilNat'] = 1;
							break;
						case 19 :
							$_REQUEST ['perfilNat'] = 2;
							break;
						case 20 :
							$_REQUEST ['perfilNat'] = 3;
							break;
						case 21 :
							$_REQUEST ['perfilNat'] = 4;
							break;
						case 22 :
							$_REQUEST ['perfilNat'] = 5;
							break;
					}
				}
				
				if(isset($_REQUEST['comunidadLGBT'])){
					switch($_REQUEST ['comunidadLGBT']){
						case 't' :
							$_REQUEST ['comunidadLGBT'] = 1;
							break;
						case 'f' :
							$_REQUEST ['comunidadLGBT'] = 2;
							break;
						default:
							$_REQUEST ['comunidadLGBT'] = -1;
							break;
					}
				}
				
				if(isset($_REQUEST['personasCargo'])){
					switch($_REQUEST ['personasCargo']){
						case 't' :
							$_REQUEST ['personasCargo'] = 1;
							break;
						case 'f' :
							$_REQUEST ['personasCargo'] = 2;
							break;
						default:
							$_REQUEST ['personasCargo'] = -1;
							break;
					}
				}
				
				if(isset($_REQUEST['cabezaFamilia'])){
					switch($_REQUEST ['cabezaFamilia']){
						case 't' :
							$_REQUEST ['cabezaFamilia'] = 1;
							break;
						case 'f' :
							$_REQUEST ['cabezaFamilia'] = 2;
							break;
						default:
							$_REQUEST ['cabezaFamilia'] = -1;
							break;
					}
				}
				
				if(isset($_REQUEST['discapacidad'])){
					switch($_REQUEST ['discapacidad']){
						case 't' :
							$_REQUEST ['discapacidad'] = 1;
							break;
						case 'f' :
							$_REQUEST ['discapacidad'] = 2;
							break;
						default:
							$_REQUEST ['discapacidad'] = -1;
							break;
					}
				}
				
				if(isset($_REQUEST['grupoEtnico'])){//CAST
					switch($_REQUEST['grupoEtnico']){
						case 'AFRODESCENDIENTES' :
							$_REQUEST['grupoEtnico'] = 23;
							break;
						case 'INDIGENAS' :
							$_REQUEST['grupoEtnico'] = 24;
							break;
						case 'RAIZALES' :
							$_REQUEST['grupoEtnico'] = 25;
							break;
						case 'ROM' :
							$_REQUEST['grupoEtnico'] = 26;
							break;
					}
				}
				
				if(isset($_REQUEST['estadoCivil'])){//CAST
					switch($_REQUEST['estadoCivil']){
						case 'SOLTERO' :
							$_REQUEST['estadoCivil'] = 27;
							break;
						case 'CASADO' :
							$_REQUEST['estadoCivil'] = 28;
							break;
						case 'UNION LIBRE' :
							$_REQUEST['estadoCivil'] = 29;
							break;
						case 'VIUDO' :
							$_REQUEST['estadoCivil'] = 30;
							break;
						case 'DIVORCIADO' :
							$_REQUEST['estadoCivil'] = 31;
							break;
					}
				}
				
				
				if(isset($_REQUEST['tipoDiscapacidad'])){//CAST
					switch($_REQUEST['tipoDiscapacidad']){
						case 'FISICA' :
							$_REQUEST['tipoDiscapacidad'] = 32;
							break;
						case 'SENSORIAL' :
							$_REQUEST['tipoDiscapacidad'] = 33;
							break;
						case 'AUDITIVA' :
							$_REQUEST['tipoDiscapacidad'] = 34;
							break;
						case 'VISUAL' :
							$_REQUEST['tipoDiscapacidad'] = 35;
							break;
						case 'PSIQUICA' :
							$_REQUEST['tipoDiscapacidad'] = 36;
							break;
						case 'MENTAL' :
							$_REQUEST['tipoDiscapacidad'] = 37;
							break;
					}
				}
				
				$esteCampo = 'id_Proveedor';
				$atributos ["id"] = $esteCampo; // No cambiar este nombre
				$atributos ["tipo"] = "hidden";
				$atributos ['estilo'] = '';
				$atributos ["obligatorio"] = false;
				$atributos ['marco'] = true;
				$atributos ["etiqueta"] = "";
				$atributos ['valor'] = $_REQUEST['fki_idProveedor'];
				$atributos = array_merge($atributos, $atributosGlobales);
				echo $this->miFormulario->campoCuadroTexto($atributos);
				unset($atributos);
				
				
				$esteCampo = 'id_TelefonoNat';
				$atributos ["id"] = $esteCampo; // No cambiar este nombre
				$atributos ["tipo"] = "hidden";
				$atributos ['estilo'] = '';
				$atributos ["obligatorio"] = false;
				$atributos ['marco'] = true;
				$atributos ["etiqueta"] = "";
				$atributos ['valor'] = $resultadoTel[0]['id_telefono'];
				$atributos = array_merge($atributos, $atributosGlobales);
				echo $this->miFormulario->campoCuadroTexto($atributos);
				unset($atributos);
				
				$esteCampo = 'id_MovilNat';
				$atributos ["id"] = $esteCampo; // No cambiar este nombre
				$atributos ["tipo"] = "hidden";
				$atributos ['estilo'] = '';
				$atributos ["obligatorio"] = false;
				$atributos ['marco'] = true;
				$atributos ["etiqueta"] = "";
				$atributos ['valor'] = $resultadoMovil[0]['id_telefono'];;
				$atributos = array_merge($atributos, $atributosGlobales);
				echo $this->miFormulario->campoCuadroTexto($atributos);
				unset($atributos);
		
		
		}else{
		
				//*******************************************************************************************************************JURIDICA******************
				
				
				$cadenaSql = $this->miSql->getCadenaSql ( 'buscarProveedorByUsuario', $_REQUEST["usuario"] );
				$resultadoPersonaJuridica = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
				
				$_REQUEST['fki_idProveedorJur'] =  $resultadoPersonaJuridica[0]["id_proveedor"];
				$_REQUEST['tipoPersona'] =  $resultadoPersonaJuridica[0]["tipopersona"];
				$_REQUEST['nit'] =  $resultadoPersonaJuridica[0]['num_documento'];
				$_REQUEST['fki_idciudadContactoJur'] =  $resultadoPersonaJuridica[0]["id_ciudad_contacto"];
				$_REQUEST['direccion'] =  $resultadoPersonaJuridica[0]['direccion'];
				$_REQUEST['correo'] =  $resultadoPersonaJuridica[0]["correo"];
				$_REQUEST['sitioWeb'] =  $resultadoPersonaJuridica[0]['web'];
				$_REQUEST['asesorComercial'] =  $resultadoPersonaJuridica[0]['nom_asesor'];
				$_REQUEST['telAsesor'] =  $resultadoPersonaJuridica[0]["tel_asesor"];
				$_REQUEST['descripcion'] =  $resultadoPersonaJuridica[0]["descripcion"];
				$_REQUEST['DocumentoRUT'] =  $resultadoPersonaJuridica[0]['anexorut'];
				$_REQUEST['tipoCuenta'] =  $resultadoPersonaJuridica[0]["tipo_cuenta_bancaria"];
				$_REQUEST['numeroCuenta'] =  $resultadoPersonaJuridica[0]["num_cuenta_bancaria"];
				$_REQUEST['entidadBancaria'] =  $resultadoPersonaJuridica[0]["id_entidad_bancaria"];
				$_REQUEST['fecha_Registro'] =  $resultadoPersonaJuridica[0]["fecha_registro"];
				$_REQUEST['fecha_Cambios'] =  $fechaModificacion ;
				$_REQUEST['estado'] =  $resultadoPersonaJuridica[0]["estado"];
				
				
				
				$_REQUEST['ciudad'] = $_REQUEST['fki_idciudadContactoJur'];
				
				
				$cadena_sql = $this->miSql->getCadenaSql ( 'consultarContactoTelProveedor', $_REQUEST['nit']);
				$resultadoTel = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
				$_REQUEST['telefono'] = $resultadoTel[0]['numero_tel'];
				$_REQUEST['extension'] = $resultadoTel[0]['extension'];
				
				
				
				$cadena_sql = $this->miSql->getCadenaSql ( 'consultarInformacionProveedorXRepresentante', $_REQUEST['fki_idProveedorJur']);
				$resultadoRepr = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
				$_REQUEST['correoPer'] =  $resultadoRepr[0]["correo_representante"];
				$_REQUEST['numeroContacto'] =  $resultadoRepr[0]["telefono_contacto"];
				
				
				
				$cadenaSql = $this->miSql->getCadenaSql ( 'consultarProveedorNatural', $resultadoRepr[0]['id_representante'] );
				$resultadoPersonaNaturalInfo = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
				
				$_REQUEST['numeroDocumento'] =  $resultadoPersonaNaturalInfo[0]["num_documento_persona"];
				$_REQUEST['tipoDocumento'] =  $resultadoPersonaNaturalInfo[0]["tipo_documento"];
				$_REQUEST['primerApellido'] =  $resultadoPersonaNaturalInfo[0]["primer_apellido"];
				$_REQUEST['segundoApellido'] =  $resultadoPersonaNaturalInfo[0]["segundo_apellido"];
				$_REQUEST['primerNombre'] =  $resultadoPersonaNaturalInfo[0]["primer_nombre"];
				$_REQUEST['segundoNombre'] =  $resultadoPersonaNaturalInfo[0]["segundo_nombre"];
				$_REQUEST['genero'] =  $resultadoPersonaNaturalInfo[0]["genero"];
				$_REQUEST['cargo'] =  $resultadoPersonaNaturalInfo[0]["cargo"];
				$_REQUEST['paisNacimiento'] =  $resultadoPersonaNaturalInfo[0]["id_pais_nacimiento"];
				$_REQUEST['perfil'] =  $resultadoPersonaNaturalInfo[0]["perfil"];
				//$_REQUEST['monto'] =  $resultadoPersonaNaturalInfo[0]["monto_capital_autorizado"];
				$_REQUEST['profesion'] =  $resultadoPersonaNaturalInfo[0]["profesion"];
				$_REQUEST['especialidad'] =  $resultadoPersonaNaturalInfo[0]["especialidad"];
				
				
				if(isset($_REQUEST['perfil'])){//CAST
					switch($_REQUEST['perfil']){
						case 18 :
							$_REQUEST ['perfil'] = 1;
							break;
						case 19 :
							$_REQUEST ['perfil'] = 2;
							break;
						case 20 :
							$_REQUEST ['perfil'] = 3;
							break;
						case 21 :
							$_REQUEST ['perfil'] = 4;
							break;
						case 22 :
							$_REQUEST ['perfil'] = 5;
							break;
					}
				}
				
				$esteCampo = 'id_Proveedor';
				$atributos ["id"] = $esteCampo; // No cambiar este nombre
				$atributos ["tipo"] = "hidden";
				$atributos ['estilo'] = '';
				$atributos ["obligatorio"] = false;
				$atributos ['marco'] = true;
				$atributos ["etiqueta"] = "";
				$atributos ['valor'] = $_REQUEST['fki_idProveedorJur'];
				$atributos = array_merge($atributos, $atributosGlobales);
				echo $this->miFormulario->campoCuadroTexto($atributos);
				unset($atributos);
				
				
				$esteCampo = 'id_Telefono';
				$atributos ["id"] = $esteCampo; // No cambiar este nombre
				$atributos ["tipo"] = "hidden";
				$atributos ['estilo'] = '';
				$atributos ["obligatorio"] = false;
				$atributos ['marco'] = true;
				$atributos ["etiqueta"] = "";
				$atributos ['valor'] = $resultadoTel[0]['id_telefono'];
				$atributos = array_merge($atributos, $atributosGlobales);
				echo $this->miFormulario->campoCuadroTexto($atributos);
				unset($atributos);
		
				
				
				
				$cadenaSql = $this->miSql->getCadenaSql ( 'consultarProveedorJuridica', $_REQUEST['nit'] );
				$resultadoPersonaJuridicaInfo = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
				
				$_REQUEST['paisEmpresa'] = $resultadoPersonaJuridicaInfo[0]['procedencia_empresa'];
				$_REQUEST['tipoIdentifiExtranjera'] = $resultadoPersonaJuridicaInfo[0]['tipo_identificacion_extranjera'];
				$_REQUEST['regimenContributivo'] = $resultadoPersonaJuridicaInfo[0]['regimen_contributivo'];
				
				$_REQUEST['productoImportacion'] = $resultadoPersonaJuridicaInfo[0]['exclusividad_producto'];
				$_REQUEST['pyme'] = $resultadoPersonaJuridicaInfo[0]['pyme'];
				$_REQUEST['registroMercantil'] = $resultadoPersonaJuridicaInfo[0]['registro_mercantil'];
				$_REQUEST['sujetoDeRetencion'] = $resultadoPersonaJuridicaInfo[0]['sujeto_retencion'];
				$_REQUEST['agenteRetenedor'] = $resultadoPersonaJuridicaInfo[0]['agente_retenedor'];
				$_REQUEST['responsableICA'] = $resultadoPersonaJuridicaInfo[0]['responsable_ICA'];
				$_REQUEST['responsableIVA'] = $resultadoPersonaJuridicaInfo[0]['responsable_IVA'];
				
				
				$_REQUEST['personaJuridicaCiudad'] = $resultadoPersonaJuridicaInfo[0]['id_ciudad_origen'];
				
				
				$_REQUEST['codigoPais'] = $resultadoPersonaJuridicaInfo[0]['codigo_pais_dian'];
				$_REQUEST['codigoPostal'] = $resultadoPersonaJuridicaInfo[0]['codigo_postal'];
				$_REQUEST['pasaporte'] = $resultadoPersonaJuridicaInfo[0]['num_pasaporte'];
				$_REQUEST['cedulaExtranjeria'] = $resultadoPersonaJuridicaInfo[0]['num_cedula_extranjeria'];
				$_REQUEST['tipoConformacion'] = $resultadoPersonaJuridicaInfo[0]['id_tipo_conformacion'];
				$_REQUEST['monto'] = $resultadoPersonaJuridicaInfo[0]['monto_capital_autorizado'];
				
				$_REQUEST['nombreEmpresa'] = $resultadoPersonaJuridicaInfo[0]['nom_proveedor'];
				
				if(isset($_REQUEST['paisEmpresa'])){//CAST
					switch($_REQUEST['paisEmpresa']){
						case 'NACIONAL' :
							$_REQUEST['paisEmpresa'] = 1;
							break;
						case 'EXTRANJERO' :
							$_REQUEST['paisEmpresa'] = 2;
							break;
					}
				}
					
				if(isset($_REQUEST['tipoIdentifiExtranjera'])){//CAST
					switch($_REQUEST['tipoIdentifiExtranjera']){
						case 'CEDULA DE EXTRANJERIA' :
							$_REQUEST['tipoIdentifiExtranjera'] = 1;
							break;
						case 'PASAPORTE' :
							$_REQUEST['tipoIdentifiExtranjera'] = 2;
							break;
					}
				}
					
				if(isset($_REQUEST['regimenContributivo'])){//CAST
					switch($_REQUEST['regimenContributivo']){
						case 'COMUN' :
							$_REQUEST['regimenContributivo'] = 1;
							break;
						case 'SIMPLIFICADO' :
							$_REQUEST['regimenContributivo'] = 2;
							break;
					}
				}
				
				
				// 7 campos TRUE FALSE
					
				if(isset($_REQUEST['productoImportacion'])){
					switch($_REQUEST ['productoImportacion']){
						case 't' :
							$_REQUEST ['productoImportacion'] = 1;
							break;
						case 'f' :
							$_REQUEST ['productoImportacion'] = 2;
							break;
						default:
							$_REQUEST ['productoImportacion'] = -1;
							break;
					}
				}
					
				if(isset($_REQUEST['pyme'])){
					switch($_REQUEST ['pyme']){
						case 't' :
							$_REQUEST ['pyme'] = 1;
							break;
						case 'f' :
							$_REQUEST ['pyme'] = 2;
							break;
						default:
							$_REQUEST ['pyme'] = -1;
							break;
					}
				}
					
				if(isset($_REQUEST['registroMercantil'])){
					switch($_REQUEST ['registroMercantil']){
						case 't' :
							$_REQUEST ['registroMercantil'] = 1;
							break;
						case 'f' :
							$_REQUEST ['registroMercantil'] = 2;
							break;
						default:
							$_REQUEST ['registroMercantil'] = -1;
							break;
					}
				}
					
				if(isset($_REQUEST['sujetoDeRetencion'])){
					switch($_REQUEST ['sujetoDeRetencion']){
						case 't' :
							$_REQUEST ['sujetoDeRetencion'] = 1;
							break;
						case 'f' :
							$_REQUEST ['sujetoDeRetencion'] = 2;
							break;
						default:
							$_REQUEST ['sujetoDeRetencion'] = -1;
							break;
					}
				}
					
					
				if(isset($_REQUEST['agenteRetenedor'])){
					switch($_REQUEST ['agenteRetenedor']){
						case 't' :
							$_REQUEST ['agenteRetenedor'] = 1;
							break;
						case 'f' :
							$_REQUEST ['agenteRetenedor'] = 2;
							break;
						default:
							$_REQUEST ['agenteRetenedor'] = -1;
							break;
					}
				}
					
				if(isset($_REQUEST['responsableICA'])){
					switch($_REQUEST ['responsableICA']){
						case 't' :
							$_REQUEST ['responsableICA'] = 1;
							break;
						case 'f' :
							$_REQUEST ['responsableICA'] = 2;
							break;
						default:
							$_REQUEST ['responsableICA'] = -1;
							break;
					}
				}
					
					
				if(isset($_REQUEST['responsableIVA'])){
					switch($_REQUEST ['responsableIVA']){
						case 't' :
							$_REQUEST ['responsableIVA'] = 1;
							break;
						case 'f' :
							$_REQUEST ['responsableIVA'] = 2;
							break;
						default:
							$_REQUEST ['responsableIVA'] = -1;
							break;
					}
				}
				
				
		}
		
		
		
		// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
		$esteCampo = 'botonRegresar';
		$atributos ['id'] = $esteCampo;
		$atributos ['enlace'] = $variable;
		$atributos ['tabIndex'] = 1;
		$atributos ['estilo'] = 'textoSubtitulo';
		$atributos ['enlaceTexto'] = $this->lenguaje->getCadena ( $esteCampo );
		$atributos ['ancho'] = '10%';
		$atributos ['alto'] = '10%';
		$atributos ['redirLugar'] = true;
		echo $this->miFormulario->enlace ( $atributos );
		
		unset ( $atributos );
		
		
		// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
		
		$atributos ["id"] = "selecPerson";
		$atributos ["estilo"] = "marcoSelect";
		echo $this->miFormulario->division ( "inicio", $atributos );
		unset ( $atributos );
		{
				
			// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
			$esteCampo = "tipoPersona";
			$atributos ['nombre'] = $esteCampo;
			$atributos ['id'] = $esteCampo;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ["etiquetaObligatorio"] = true;
			$atributos ['tab'] = $tab ++;
			$atributos ['anchoEtiqueta'] = 200;
			$atributos ['evento'] = '';
			
			
			$atributos ['seleccion'] = $_TIPO;
			
			$atributos ['deshabilitado'] = true;
			$atributos ['columnas'] = 12;
			$atributos ['tamanno'] = 1;
			$atributos ['estilo'] = "jqueryui";
			$atributos ['validar'] = "required";
			$atributos ['limitar'] = false;
			$atributos ['anchoCaja'] = 60;
			$atributos ['miEvento'] = '';
			// Valores a mostrar en el control
			$matrizItems = array (
					array ( 1, 'Natural' ),
					array ( 2, 'Jurídica' )
			);
			$atributos ['matrizItems'] = $matrizItems;
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroLista ( $atributos );
			unset ( $atributos );
			// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
				
		}
		echo $this->miFormulario->division ( 'fin' );
			
		echo "<br>";
		echo "<br>";
		echo "<br>";
			
		
		
		
		//********************************************************************************************** PERSONA JURIDICA****************************
		
		$esteCampo = "marcoDatosJuridicaUPV";
		$atributos ['id'] = $esteCampo;
		$atributos ["estilo"] = "jqueryui";
		$atributos ['tipoEtiqueta'] = 'inicio';
		$atributos ["leyenda"] =  $this->lenguaje->getCadena ( $esteCampo );
		echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
		unset ( $atributos );
		{
				
			$esteCampo = "marcoEmpresa";
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
			$atributos ['tipoEtiqueta'] = 'inicio';
			$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
			echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
		
			// ---------------- CONTROL: Cuadro de Texto NIT--------------------------------------------------------
			$esteCampo = 'nit';
			$atributos ['id'] = $esteCampo;
			$atributos ['nombre'] = $esteCampo;
			$atributos ['tipo'] = 'text';
			$atributos ['estilo'] = 'jqueryui';
			$atributos ['marco'] = true;
			$atributos ['estiloMarco'] = '';
			$atributos ["etiquetaObligatorio"] = true;
			$atributos ['columnas'] = 3;
			$atributos ['dobleLinea'] = 0;
			$atributos ['tabIndex'] = $tab;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ['validar'] = 'required, minSize[1],maxSize[14],custom[onlyNumberSp]';
		
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['valor'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['valor'] = '';
			}
			$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
			$atributos ['deshabilitado'] = true;
			$atributos ['tamanno'] = 15;
			$atributos ['maximoTamanno'] = 9;
			$atributos ['anchoEtiqueta'] = 200;
			$tab ++;
		
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroTexto ( $atributos );
			unset ( $atributos );
			// ---------------- FIN CONTROL: Cuadro de Texto  NIT--------------------------------------------------------
		
			// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
			$esteCampo = 'digito';
			$atributos ['id'] = $esteCampo;
			$atributos ['nombre'] = $esteCampo;
			$atributos ['tipo'] = 'text';
			$atributos ['estilo'] = 'jqueryui';
			$atributos ['marco'] = true;
			$atributos ['estiloMarco'] = '';
			$atributos ["etiquetaObligatorio"] = true;
			$atributos ['columnas'] = 3;
			$atributos ['dobleLinea'] = 0;
			$atributos ['tabIndex'] = $tab;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ['validar'] = 'minSize[1],maxSize[2],custom[onlyNumberSp]';
		
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['valor'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['valor'] = '';
			}
			$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
			$atributos ['deshabilitado'] = true;
			$atributos ['tamanno'] = 15;
			$atributos ['maximoTamanno'] = '';
			$atributos ['anchoEtiqueta'] = 200;
			$tab ++;
		
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroTexto ( $atributos );
			unset ( $atributos );
			// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
				
			// ---------------- CONTROL: Cuadro de Texto NOMBRE EMPRESA--------------------------------------------------------
			$esteCampo = 'nombreEmpresa';
			$atributos ['id'] = $esteCampo;
			$atributos ['nombre'] = $esteCampo;
			$atributos ['tipo'] = 'text';
			$atributos ['estilo'] = 'jqueryui';
			$atributos ['marco'] = true;
			$atributos ['estiloMarco'] = '';
			$atributos ["etiquetaObligatorio"] = true;
			$atributos ['columnas'] = 1;
			$atributos ['dobleLinea'] = 0;
			$atributos ['tabIndex'] = $tab;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ['validar'] = 'required, minSize[1],maxSize[100]';
		
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['valor'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['valor'] = '';
			}
			$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
			$atributos ['deshabilitado'] = true;
			$atributos ['tamanno'] = 50;
			$atributos ['maximoTamanno'] = '';
			$atributos ['anchoEtiqueta'] = 200;
			$tab ++;
		
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroTexto ( $atributos );
			unset ( $atributos );
			// ---------------- FIN CONTROL: Cuadro de Texto NOMBRE EMPRESA--------------------------------------------------------
				
				
			// ---------------- CONTROL: Lista NACIONALIDAD Empresa --------------------------------------------------------
			$esteCampo = "paisEmpresa";
			$atributos ['nombre'] = $esteCampo;
			$atributos ['id'] = $esteCampo;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ["etiquetaObligatorio"] = true;
			$atributos ['tab'] = $tab ++;
			$atributos ['anchoEtiqueta'] = 200;
			$atributos ['evento'] = '';
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['seleccion'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['seleccion'] = -1;
			}
			$atributos ['deshabilitado'] = true;
			$atributos ['columnas'] = 1;
			$atributos ['tamanno'] = 1;
			$atributos ['estilo'] = "jqueryui";
			$atributos ['validar'] = "required";
			$atributos ['limitar'] = false;
			$atributos ['anchoCaja'] = 60;
			$atributos ['miEvento'] = '';
			// Valores a mostrar en el control
			$matrizItems = array (
					array ( 1, 'Nacional' ),
					array ( 2, 'Extranjero' )
			);
			$atributos ['matrizItems'] = $matrizItems;
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroLista ( $atributos );
			unset ( $atributos );
			// ----------------FIN CONTROL: Lista NACIONALIDAD Empresa--------------------------------------------------------
		
			echo "<br>";
			echo "<br>";
			echo "<br>";
			echo "<br>";
			echo "<br>";
			echo "<br>";
			echo "<br>";
		
			$esteCampo = "marcoProcedencia";
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
			$atributos ['tipoEtiqueta'] = 'inicio';
			$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
			echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
			unset($atributos);
			{
				// ---------------- CONTROL: Cuadro de Texto PAIS--------------------------------------------------------
					
				if($_TIPO == 2){	
					
				// ---------------- CONTROL: Select --------------------------------------------------------
				$esteCampo = 'personaJuridicaPais';
				$atributos['nombre'] = $esteCampo;
				$atributos['id'] = $esteCampo;
				$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos['tab'] = $tab;
				
				$atributos['evento'] = ' ';
				$atributos['deshabilitado'] = true;
				$atributos['limitar']= 50;
				$atributos['tamanno']= 1;
				$atributos['columnas']= 1;
					
				$atributos ['obligatorio'] = true;
				$atributos ['etiquetaObligatorio'] = true;
				$atributos ['validar'] = 'required';
				
				
				$cadenaTest = $this->miSql->getCadenaSql ( "buscarCiudad", $_REQUEST['personaJuridicaCiudad']);
				$matrizPrev = $esteRecursoDB->ejecutarAcceso ( $cadenaTest, "busqueda" );
				
				$cadenaTestP = $this->miSql->getCadenaSql ( "buscarPaisXDepa", $matrizPrev[0]['id_departamento']);
				$matrizPrevP = $esteRecursoDB->ejecutarAcceso ( $cadenaTestP, "busqueda" );
					
				$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarPais" );
				$matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
					
				$atributos['matrizItems'] = $matrizItems;
					
				
				$atributos['seleccion'] = $matrizPrevP[0]['id_pais'];
				$atributos ['valor'] = $matrizPrevP[0]['id_pais'];
				
				$tab ++;
					
				// Aplica atributos globales al control
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroLista ( $atributos );
				// --------------- FIN CONTROL : Select --------------------------------------------------
					
				
				
				
				// ---------------- CONTROL: Select --------------------------------------------------------
				$esteCampo = 'personaJuridicaDepartamento';
				$atributos['nombre'] = $esteCampo;
				$atributos['id'] = $esteCampo;
				$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos['tab'] = $tab;
				
				$atributos['evento'] = ' ';
				$atributos['deshabilitado'] = true;
				$atributos['limitar']= 50;
				$atributos['tamanno']= 1;
				$atributos['columnas']= 1;
					
				$atributos ['obligatorio'] = true;
				$atributos ['etiquetaObligatorio'] = true;
				$atributos ['validar'] = 'required';
				
				
				
				
				$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarDepartamentoAjax", $matrizPrevP[0]['id_pais'] );
				$matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
					
				$atributos['matrizItems'] = $matrizItems;
				$atributos['seleccion'] = $matrizPrev[0]['id_departamento'];
				if (isset ( $_REQUEST [$esteCampo] )) {
					$atributos ['valor'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['valor'] = $matrizPrev[0]['id_departamento'];
				}
				$tab ++;
					
				// Aplica atributos globales al control
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroLista ( $atributos );
				// --------------- FIN CONTROL : Select --------------------------------------------------
					
				// ---------------- CONTROL: Select --------------------------------------------------------
				$esteCampo = 'personaJuridicaCiudad';
				$atributos['nombre'] = $esteCampo;
				$atributos['id'] = $esteCampo;
				$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos['tab'] = $tab;
				$atributos['seleccion'] = $_REQUEST['personaJuridicaCiudad'];
				$atributos['evento'] = ' ';
				$atributos['deshabilitado'] = true;
				$atributos['limitar']= 50;
				$atributos['tamanno']= 1;
				$atributos['columnas']= 1;
					
				$atributos ['obligatorio'] = true;
				$atributos ['etiquetaObligatorio'] = true;
				$atributos ['validar'] = 'required';
					
				$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarCiudadAjax", $matrizPrev[0]['id_departamento']);
				$matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
				
				$atributos['matrizItems'] = $matrizItems;
					
				if (isset ( $_REQUEST [$esteCampo] )) {
					$atributos ['valor'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['valor'] = '';
				}
				$tab ++;
					
				// Aplica atributos globales al control
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroLista ( $atributos );
				
				
				
				}
				
				
				
				/*
				
				// ---------------- CONTROL: Select --------------------------------------------------------
				$esteCampo = 'personaJuridicaDepartamento';
				$atributos['nombre'] = $esteCampo;
				$atributos['id'] = $esteCampo;
				$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos['tab'] = $tab;
				$atributos['seleccion'] = -1;
				$atributos['evento'] = ' ';
				$atributos['deshabilitado'] = true;
				$atributos['limitar']= 50;
				$atributos['tamanno']= 1;
				$atributos['columnas']= 1;
					
				$atributos ['obligatorio'] = true;
				$atributos ['etiquetaObligatorio'] = true;
				$atributos ['validar'] = 'required';
					
				$matrizItems=array(
						array(1,'Cundinamarca'),
						array(2,'Antioquia'),
						array(3,'Santander'),
						array(4,'Bolivar'),
						array(5,'Bogotá D.C.')
							
				);
					
				$atributos['matrizItems'] = $matrizItems;
					
				if (isset ( $_REQUEST [$esteCampo] )) {
					$atributos ['valor'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['valor'] = '';
				}
				$tab ++;
					
				// Aplica atributos globales al control
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroLista ( $atributos );
				// --------------- FIN CONTROL : Select --------------------------------------------------
					
				// ---------------- CONTROL: Select --------------------------------------------------------
				$esteCampo = 'personaJuridicaCiudad';
				$atributos['nombre'] = $esteCampo;
				$atributos['id'] = $esteCampo;
				$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos['tab'] = $tab;
				$atributos['seleccion'] = -1;
				$atributos['evento'] = ' ';
				$atributos['deshabilitado'] = true;
				$atributos['limitar']= 50;
				$atributos['tamanno']= 1;
				$atributos['columnas']= 1;
					
				$atributos ['obligatorio'] = true;
				$atributos ['etiquetaObligatorio'] = true;
				$atributos ['validar'] = 'required';
					
				$matrizItems=array(
						array(1,'Bogota D.C.'),
						array(2,'Medellin'),
						array(3,'Barranquilla'),
						array(4,'Cali'),
						array(5,'Cucuta'),
						array(6,'Bucaramanga')
							
				);
				$atributos['matrizItems'] = $matrizItems;
					
				if (isset ( $_REQUEST [$esteCampo] )) {
					$atributos ['valor'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['valor'] = '';
				}
				$tab ++;
					
				// Aplica atributos globales al control
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroLista ( $atributos );
		
					*/
					
				/*
				 $esteCampo = 'pais';
				 $atributos ['id'] = $esteCampo;
				 $atributos ['nombre'] = $esteCampo;
				 $atributos ['tipo'] = 'text';
				 $atributos ['estilo'] = 'jqueryui';
				 $atributos ['marco'] = true;
				 $atributos ['estiloMarco'] = '';
				 $atributos ["etiquetaObligatorio"] = true;
				 $atributos ['columnas'] = 2;
				 $atributos ['dobleLinea'] = 0;
				 $atributos ['tabIndex'] = $tab ++;
				 $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				 $atributos ['validar'] = 'required, minSize[1],maxSize[50]';
						
				 if (isset ( $_REQUEST [$esteCampo] )) {
				 $atributos ['valor'] = $_REQUEST [$esteCampo];
				 } else {
				 $atributos ['valor'] = '';
				 }
				 $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
				 $atributos ['deshabilitado'] = true;
				 $atributos ['tamanno'] = 30;
				 $atributos ['maximoTamanno'] = '';
				 $atributos ['anchoEtiqueta'] = 160;
				 $tab ++;
						
				 // Aplica atributos globales al control
				 $atributos = array_merge ( $atributos, $atributosGlobales );
				 echo $this->miFormulario->campoCuadroTexto ( $atributos );
				 unset ( $atributos );
				 */
				// ---------------- FIN CONTROL: Cuadro de Texto  PAIS--------------------------------------------------------
					
				// ---------------- CONTROL: Cuadro de Texto  Codigo Pais--------------------------------------------------------
				$esteCampo = 'codigoPais';
				$atributos ['id'] = $esteCampo;
				$atributos ['nombre'] = $esteCampo;
				$atributos ['tipo'] = 'text';
				$atributos ['estilo'] = 'jqueryui';
				$atributos ['marco'] = true;
				$atributos ['estiloMarco'] = '';
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['columnas'] = 2;
				$atributos ['dobleLinea'] = 0;
				$atributos ['tabIndex'] = $tab;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ['validar'] = 'required, minSize[1],maxSize[30],custom[onlyNumberSp]';
					
				if (isset ( $_REQUEST [$esteCampo] )) {
					$atributos ['valor'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['valor'] = '';
				}
				$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
				$atributos ['deshabilitado'] = true;
				$atributos ['tamanno'] = 10;
				$atributos ['maximoTamanno'] = '';
				$atributos ['anchoEtiqueta'] = 180;
				$tab ++;
					
				// Aplica atributos globales al control
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroTexto ( $atributos );
				unset ( $atributos );
				// ---------------- FIN CONTROL: Cuadro de Texto  Codigo Pais--------------------------------------------------------
					
				// ---------------- CONTROL: Cuadro de Texto Codigo Postal--------------------------------------------------------
				$esteCampo = 'codigoPostal';
				$atributos ['id'] = $esteCampo;
				$atributos ['nombre'] = $esteCampo;
				$atributos ['tipo'] = 'text';
				$atributos ['estilo'] = 'jqueryui';
				$atributos ['marco'] = true;
				$atributos ['estiloMarco'] = '';
				$atributos ["etiquetaObligatorio"] = false;
				$atributos ['columnas'] = 2;
				$atributos ['dobleLinea'] = 0;
				$atributos ['tabIndex'] = $tab ++;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ['validar'] = 'minSize[1],maxSize[30],custom[onlyNumberSp]';
		
				if (isset ( $_REQUEST [$esteCampo] )) {
					$atributos ['valor'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['valor'] = '';
				}
				$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
				$atributos ['deshabilitado'] = true;
				$atributos ['tamanno'] = 30;
				$atributos ['maximoTamanno'] = '';
				$atributos ['anchoEtiqueta'] = 160;
				$tab ++;
		
				// Aplica atributos globales al control
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroTexto ( $atributos );
				unset ( $atributos );
				// ---------------- FIN CONTROL: Cuadro de Texto  Codigo Postal--------------------------------------------------------
		
				// ---------------- CONTROL: Lista Tipo Identificacion Empresa --------------------------------------------------------
				$esteCampo = "tipoIdentifiExtranjera";
				$atributos ['nombre'] = $esteCampo;
				$atributos ['id'] = $esteCampo;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['tab'] = $tab ++;
				$atributos ['anchoEtiqueta'] = 300;
				$atributos ['evento'] = '';
				if (isset ( $_REQUEST [$esteCampo] )) {
					$atributos ['seleccion'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['seleccion'] = -1;
				}
				$atributos ['deshabilitado'] = true;
				$atributos ['columnas'] = 1;
				$atributos ['tamanno'] = 1;
				$atributos ['estilo'] = "jqueryui";
				$atributos ['validar'] = "required";
				$atributos ['limitar'] = false;
				$atributos ['anchoCaja'] = 60;
				$atributos ['miEvento'] = '';
				// Valores a mostrar en el control
				$matrizItems = array (
						array ( 1, 'Cédula de extranjería' ),
						array ( 2, 'Pasaporte' )
				);
				$atributos ['matrizItems'] = $matrizItems;
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroLista ( $atributos );
				unset ( $atributos );
				// ----------------FIN CONTROL: Lista Tipo Identificacion Empresa--------------------------------------------------------
					
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
					
				$atributos ["id"] = "obligatorioCedula";
				$atributos ["estilo"] = "Marco";
				echo $this->miFormulario->division ( "inicio", $atributos );
				unset ( $atributos );
				{
		
					// ---------------- CONTROL: Cuadro de Texto CEDULA EXTRANJERIA--------------------------------------------------------
					$esteCampo = 'cedulaExtranjeria';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'text';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['estiloMarco'] = '';
					$atributos ["etiquetaObligatorio"] = true;
					$atributos ['columnas'] = 1;
					$atributos ['dobleLinea'] = 0;
					$atributos ['tabIndex'] = $tab ++;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ['validar'] = 'required, minSize[1],maxSize[30],custom[onlyNumberSp]';
						
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = true;
					$atributos ['tamanno'] = 30;
					$atributos ['maximoTamanno'] = '';
					$atributos ['anchoEtiqueta'] = 190;
					$tab ++;
						
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					unset ( $atributos );
					// ---------------- FIN CONTROL: Cuadro de Texto  CEDULA EXTRANJERIA--------------------------------------------------------
		
				}
				echo $this->miFormulario->division ( 'fin' );
					
				$atributos ["id"] = "obligatorioPasaporte";
				$atributos ["estilo"] = "Marco";
				echo $this->miFormulario->division ( "inicio", $atributos );
				unset ( $atributos );
				{
						
					// ---------------- CONTROL: Cuadro de Texto  PASAPORTE--------------------------------------------------------
					$esteCampo = 'pasaporte';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'text';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['estiloMarco'] = '';
					$atributos ["etiquetaObligatorio"] = true;
					$atributos ['columnas'] = 1;
					$atributos ['dobleLinea'] = 0;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ['validar'] = 'required, minSize[1],maxSize[30],custom[onlyNumberSp]';
						
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = true;
					$atributos ['tamanno'] = 30;
					$atributos ['maximoTamanno'] = '';
					$atributos ['anchoEtiqueta'] = 190;
					$tab ++;
						
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					unset ( $atributos );
					// ---------------- FIN CONTROL: Cuadro de Texto  PASAPORTE--------------------------------------------------------
						
						
				}
				echo $this->miFormulario->division ( 'fin' );
					
			}
			echo $this->miFormulario->marcoAgrupacion ( 'fin' );
		
				
		
			echo $this->miFormulario->marcoAgrupacion ( 'fin' );
				
				
			$esteCampo = "marcoContacto";
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
			$atributos ['tipoEtiqueta'] = 'inicio';
			$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
			echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
		
		
			// ---------------- CONTROL: Select --------------------------------------------------------
			$esteCampo = 'departamento';
			$atributos['nombre'] = $esteCampo;
			$atributos['id'] = $esteCampo;
			$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos['tab'] = $tab;
			
			$atributos['evento'] = ' ';
			$atributos['deshabilitado'] = true;
			$atributos['limitar']= 50;
			$atributos['tamanno']= 1;
			$atributos['columnas']= 1;
				
			$atributos ['obligatorio'] = true;
			$atributos ['etiquetaObligatorio'] = true;
			$atributos ['validar'] = 'required';
			
			
			$cadenaTest = $this->miSql->getCadenaSql ( "buscarCiudad", $_REQUEST['fki_idciudadContactoJur']);
			$matrizPrev = $esteRecursoDB->ejecutarAcceso ( $cadenaTest, "busqueda" );
			
			$cadenaTestP = $this->miSql->getCadenaSql ( "buscarPaisXDepa", $matrizPrev[0]['id_departamento']);
			$matrizPrevP = $esteRecursoDB->ejecutarAcceso ( $cadenaTestP, "busqueda" );
			
			$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarDepartamentoAjax", $matrizPrevP[0]['id_pais'] );
			$matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
				
			$atributos['matrizItems'] = $matrizItems;
			$atributos['seleccion'] = $matrizPrev[0]['id_departamento'];
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['valor'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['valor'] = $matrizPrev[0]['id_departamento'];
			}
			$tab ++;
				
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroLista ( $atributos );
			// --------------- FIN CONTROL : Select --------------------------------------------------
				
			// ---------------- CONTROL: Select --------------------------------------------------------
			$esteCampo = 'ciudad';
			$atributos['nombre'] = $esteCampo;
			$atributos['id'] = $esteCampo;
			$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos['tab'] = $tab;
			$atributos['seleccion'] = $_REQUEST['fki_idciudadContactoJur'];
			$atributos['evento'] = ' ';
			$atributos['deshabilitado'] = true;
			$atributos['limitar']= 50;
			$atributos['tamanno']= 1;
			$atributos['columnas']= 1;
				
			$atributos ['obligatorio'] = true;
			$atributos ['etiquetaObligatorio'] = true;
			$atributos ['validar'] = 'required';
				
			$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarCiudadAjax", $matrizPrev[0]['id_departamento']);
			$matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
			
			$atributos['matrizItems'] = $matrizItems;
				
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['valor'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['valor'] = '';
			}
			$tab ++;
				
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroLista ( $atributos );
			
			/*
			
			
			// ---------------- CONTROL: Select --------------------------------------------------------
			$esteCampo = 'departamento';
			$atributos['nombre'] = $esteCampo;
			$atributos['id'] = $esteCampo;
			$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos['tab'] = $tab;
			$atributos['seleccion'] = -1;
			$atributos['evento'] = ' ';
			$atributos['deshabilitado'] = false;
			$atributos['limitar']= 50;
			$atributos['tamanno']= 1;
			$atributos['columnas']= 1;
				
			$atributos ['obligatorio'] = true;
			$atributos ['etiquetaObligatorio'] = true;
			$atributos ['validar'] = 'required';
				
			$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarDepartamento" );
			$matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
				
			$atributos['matrizItems'] = $matrizItems;
				
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['valor'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['valor'] = '';
			}
			$tab ++;
				
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroLista ( $atributos );
			// --------------- FIN CONTROL : Select --------------------------------------------------
				
			// ---------------- CONTROL: Select --------------------------------------------------------
			$esteCampo = 'ciudad';
			$atributos['nombre'] = $esteCampo;
			$atributos['id'] = $esteCampo;
			$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos['tab'] = $tab;
			$atributos['seleccion'] = -1;
			$atributos['evento'] = ' ';
			$atributos['deshabilitado'] = false;
			$atributos['limitar']= 50;
			$atributos['tamanno']= 1;
			$atributos['columnas']= 1;
				
			$atributos ['obligatorio'] = true;
			$atributos ['etiquetaObligatorio'] = true;
			$atributos ['validar'] = 'required';
				
			$matrizItems=array(
					array(1,'Bogota D.C.'),
					array(2,'Medellin'),
					array(3,'Barranquilla'),
					array(4,'Cali'),
					array(5,'Cucuta'),
					array(6,'Bucaramanga')
						
			);
			$atributos['matrizItems'] = $matrizItems;
				
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['valor'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['valor'] = '';
			}
			$tab ++;
				
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroLista ( $atributos );
		*/
			/*
			 // ---------------- CONTROL: Cuadro de Texto CIUDAD--------------------------------------------------------
			 $esteCampo = 'ciudad';
			 $atributos ['id'] = $esteCampo;
			 $atributos ['nombre'] = $esteCampo;
			 $atributos ['tipo'] = 'text';
			 $atributos ['estilo'] = 'jqueryui';
			 $atributos ['marco'] = true;
			 $atributos ['estiloMarco'] = '';
			 $atributos ["etiquetaObligatorio"] = true;
			 $atributos ['columnas'] = 2;
			 $atributos ['dobleLinea'] = 0;
			 $atributos ['tabIndex'] = $tab;
			 $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			 $atributos ['validar'] = 'required, minSize[1],maxSize[50]';
		
			 if (isset ( $_REQUEST [$esteCampo] )) {
			 $atributos ['valor'] = $_REQUEST [$esteCampo];
			 } else {
			 $atributos ['valor'] = '';
			 }
			 $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
			 $atributos ['deshabilitado'] = true;
			 $atributos ['tamanno'] = 15;
			 $atributos ['maximoTamanno'] = '';
			 $atributos ['anchoEtiqueta'] = 160;
			 $tab ++;
		
			 // Aplica atributos globales al control
			 $atributos = array_merge ( $atributos, $atributosGlobales );
			 echo $this->miFormulario->campoCuadroTexto ( $atributos );
			 unset ( $atributos );
			 // ---------------- FIN CONTROL: Cuadro de Texto  NIT--------------------------------------------------------
					
			 */
		
			/*
		
			$esteCampo = "marcoDatosDireccion";
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
			$atributos ['tipoEtiqueta'] = 'inicio';
			$atributos ["leyenda"] = "Dirección Tipo DIAN";
			echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
			{
		
				$atributos ["id"] = "panelDireccion";
				$atributos ["estilo"] = "row";
				echo $this->miFormulario->division ( "inicio", $atributos );
				{
					$atributos ["id"] = "ingresoDireccion";
					$atributos ["estilo"] = "col-md-6";
					echo $this->miFormulario->division ( "inicio", $atributos );
					{
						// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
						$esteCampo = 'direccion';
						$atributos ['id'] = $esteCampo;
						$atributos ['nombre'] = $esteCampo;
						$atributos ['estilo'] = '';
						$atributos ['marco'] = false;
						$atributos ['correccion'] = false;
						$atributos ['columnas'] = 50;
						$atributos ['filas'] = 4;
						$atributos ['tabIndex'] = $tab;
						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
						$atributos ['anchoEtiqueta'] = 150;
						$atributos ['deshabilitado'] =false;
		
						$atributos ['obligatorio'] = true;
						$atributos ['etiquetaObligatorio'] = true;
						$atributos ['validar'] = 'required, minSize[1], maxSize[5]';
		
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['valor'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['valor'] = '';
						}
						$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
						$tab ++;
		
						// Aplica atributos globales al control
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoTextArea ( $atributos );
						// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
		
						unset($atributos);
		
						unset($atributos);
		
						$atributos ["id"] = "ingresoBotones";
						$atributos ["estilo"] = "col-md-12";
						echo $this->miFormulario->division ( "inicio", $atributos );
						{
		
							$atributos ["id"] = "botonesPanel";
							$atributos ["estilo"] = "col-md-12 btn-group btn-group-lg";
							echo $this->miFormulario->division ( "inicio", $atributos );
							{
								echo "<input type=\"button\" id=\"btOper1\" value=\"A\" class=\"btn btn-primary\"/>";
								echo "<input type=\"button\" id=\"btOper2\" value=\"B\" class=\"btn btn-primary\" />";
								echo "<input type=\"button\" id=\"btOper3\" value=\"C\" class=\"btn btn-primary\"/>";
								echo "<input type=\"button\" id=\"btOper4\" value=\"D\" class=\"btn btn-primary\" />";
								echo "<input type=\"button\" id=\"btOper5\" value=\"E\" class=\"btn btn-primary\"/>";
								echo "<input type=\"button\" id=\"btOper6\" value=\"F\" class=\"btn btn-primary\" />";
								echo "<input type=\"button\" id=\"btOper7\" value=\"G\" class=\"btn btn-primary\"/>";
								echo "<input type=\"button\" id=\"btOper8\" value=\"H\" class=\"btn btn-primary\" />";
								echo "<input type=\"button\" id=\"btOper9\" value=\"I\" class=\"btn btn-primary\" />";
								echo "<input type=\"button\" id=\"btOper10\" value=\"J\" class=\"btn btn-primary\" />";
								echo "<input type=\"button\" id=\"btOper11\" value=\"K\" class=\"btn btn-primary\" />";
								echo "<input type=\"button\" id=\"btOper12\" value=\"L\" class=\"btn btn-primary\" />";
								echo "<input type=\"button\" id=\"btOper13\" value=\"Borrar\" class=\"btn btn-danger\" />";
							}
							echo $this->miFormulario->division ( "fin" );
		
						}
						echo $this->miFormulario->division ( "fin" );
		
		
		
		
		
					}
					echo $this->miFormulario->division ( "fin" );
						
					$atributos ["id"] = "variables";
					$atributos ["estilo"] = "col-md-6";
					echo $this->miFormulario->division ( "inicio", $atributos );
					{
						unset($atributos);
		
		
						$esteCampo = "marcoDatosParametros";
						$atributos ['id'] = $esteCampo;
						$atributos ["estilo"] = "jqueryui";
						$atributos ['tipoEtiqueta'] = 'inicio';
						$atributos ["leyenda"] = "Panel Nomenclaturas DIAN";
						echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
						{
								
							$atributos ["id"] = "listaNomenclaturasNatural";
							$atributos ["estilo"] = "col-md-12";
							echo $this->miFormulario->division ( "inicio", $atributos );
							{
								// ---------------- CONTROL: Select --------------------------------------------------------
								$esteCampo = 'listaNomenclaturas';
								$atributos['nombre'] = $esteCampo;
								$atributos['id'] = $esteCampo;
								$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
								$atributos ['anchoEtiqueta'] = 230;
								$atributos['tab'] = $tab;
								$atributos['seleccion'] = -1;
								$atributos['evento'] = ' ';
								$atributos['deshabilitado'] = false;
								$atributos['limitar']= 50;
								$atributos['tamanno']= 1;
								$atributos['columnas']= 1;
		
								$atributos ['obligatorio'] = false;
								$atributos ['etiquetaObligatorio'] = false;
								$atributos ['validar'] = '';
		
		
								$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarNomenclaturas" );
								$matrizParametros = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
		
								$atributos['matrizItems'] = $matrizParametros;
		
								if (isset ( $_REQUEST [$esteCampo] )) {
									$atributos ['valor'] = $_REQUEST [$esteCampo];
								} else {
									$atributos ['valor'] = '';
								}
								$atributos ["titulo"] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
								$tab ++;
		
								// Aplica atributos globales al control
								$atributos = array_merge ( $atributos, $atributosGlobales );
								echo $this->miFormulario->campoCuadroLista ( $atributos );
								// --------------- FIN CONTROL : Select --------------------------------------------------
							}
							echo $this->miFormulario->division ( "fin" );
								
							unset($atributos);
								
							$atributos ["id"] = "parametros";
							$atributos ["estilo"] = "col-md-12";
							echo $this->miFormulario->division ( "inicio", $atributos );
							{
								// ---------------- CONTROL: Select --------------------------------------------------------
								$esteCampo = 'seccionParametros';
								$atributos['nombre'] = $esteCampo;
								$atributos['id'] = $esteCampo;
								$atributos['etiqueta'] = '';
								$atributos ['anchoEtiqueta'] = 180;
								$atributos['tab'] = $tab;
								$atributos['seleccion'] = 0;
								$atributos['evento'] = ' ';
								$atributos['deshabilitado'] = true;
								$atributos['limitar']= 50;
								$atributos['tamanno']= 1;
								$atributos['columnas']= 1;
		
								$atributos ['obligatorio'] = false;
								$atributos ['etiquetaObligatorio'] = false;
								$atributos ['validar'] = '';
		
								//$atributos ['cadena_sql'] = $this->miSql->getCadenaSql("buscarCategoriaParametro");
								//$matrizParametros=$primerRecursoDB->ejecutarAcceso($atributos['cadena_sql'], "busqueda");
		
								//$atributos['matrizItems'] = $matrizParametros;
		
								$matrizItems=array(
										array(1,'Nomenclatura'),
										array(2,'TEST'),
		
								);
								$atributos['matrizItems'] = $matrizItems;
		
								if (isset ( $_REQUEST [$esteCampo] )) {
									$atributos ['valor'] = $_REQUEST [$esteCampo];
								} else {
									$atributos ['valor'] = '';
								}
								$atributos ["titulo"] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
								$tab ++;
		
								// Aplica atributos globales al control
								$atributos = array_merge ( $atributos, $atributosGlobales );
								echo $this->miFormulario->campoCuadroLista ( $atributos );
								// --------------- FIN CONTROL : Select --------------------------------------------------
							}
							echo $this->miFormulario->division ( "fin" );
								
						}
						echo $this->miFormulario->marcoAgrupacion ( "fin" );
		
						unset($atributos);
		
					}
					echo $this->miFormulario->division ( "fin" );
						
						
						
				}
				echo $this->miFormulario->division ( "fin" );
		
			}
			echo $this->miFormulario->marcoAgrupacion ( "fin" );
		
			*/
		
		
		
		
			
			 // ---------------- CONTROL: Cuadro de Texto  Dirección--------------------------------------------------------
			 $esteCampo = 'direccion';
			 $atributos ['id'] = $esteCampo;
			 $atributos ['nombre'] = $esteCampo;
			 $atributos ['tipo'] = 'text';
			 $atributos ['estilo'] = 'jqueryui';
			 $atributos ['marco'] = true;
			 $atributos ['estiloMarco'] = '';
			 $atributos ["etiquetaObligatorio"] = true;
			 $atributos ['columnas'] = 1;
			 $atributos ['dobleLinea'] = 0;
			 $atributos ['tabIndex'] = $tab;
			 $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			 $atributos ['validar'] = 'required, minSize[1],maxSize[150]';
		
			 if (isset ( $_REQUEST [$esteCampo] )) {
			 $atributos ['valor'] = $_REQUEST [$esteCampo];
			 } else {
			 $atributos ['valor'] = '';
			 }
			 $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
			 $atributos ['deshabilitado'] = true;
			 $atributos ['tamanno'] = 60;
			 $atributos ['maximoTamanno'] = '';
			 $atributos ['anchoEtiqueta'] = 160;
			 $tab ++;
		
			 // Aplica atributos globales al control
			 $atributos = array_merge ( $atributos, $atributosGlobales );
			 echo $this->miFormulario->campoCuadroTexto ( $atributos );
			 unset ( $atributos );
			 // ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
			 
		
		
			// ---------------- CONTROL: Cuadro de Texto Correo--------------------------------------------------------
			$esteCampo = 'correo';
			$atributos ['id'] = $esteCampo;
			$atributos ['nombre'] = $esteCampo;
			$atributos ['tipo'] = 'text';
			$atributos ['estilo'] = 'jqueryui';
			$atributos ['marco'] = true;
			$atributos ['estiloMarco'] = '';
			$atributos ["etiquetaObligatorio"] = true;
			$atributos ['columnas'] = 1;
			$atributos ['dobleLinea'] = 0;
			$atributos ['tabIndex'] = $tab;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ['validar'] = 'required, custom[email], maxSize[40]';
				
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['valor'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['valor'] = '';
			}
			$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
			$atributos ['deshabilitado'] = true;
			$atributos ['tamanno'] = 30;
			$atributos ['maximoTamanno'] = '30';
			$atributos ['anchoEtiqueta'] = 160;
			$tab ++;
				
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroTexto ( $atributos );
			unset ( $atributos );
			// ---------------- FIN CONTROL: Cuadro de Texto  NIT--------------------------------------------------------
		
			// ---------------- CONTROL: Cuadro de Texto  Sitio Web--------------------------------------------------------
			$esteCampo = 'sitioWeb';
			$atributos ['id'] = $esteCampo;
			$atributos ['nombre'] = $esteCampo;
			$atributos ['tipo'] = 'text';
			$atributos ['estilo'] = 'jqueryui';
			$atributos ['marco'] = true;
			$atributos ['estiloMarco'] = '';
			$atributos ["etiquetaObligatorio"] = false;
			$atributos ['columnas'] = 6;
			$atributos ['dobleLinea'] = 0;
			$atributos ['tabIndex'] = $tab;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ['validar'] = 'minSize[1],maxSize[100]';
		
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['valor'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['valor'] = '';
			}
			$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
			$atributos ['deshabilitado'] = true;
			$atributos ['tamanno'] = 30;
			$atributos ['maximoTamanno'] = '';
			$atributos ['anchoEtiqueta'] = 160;
			$tab ++;
		
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroTexto ( $atributos );
			unset ( $atributos );
			// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
		
			// ---------------- CONTROL: Cuadro de Texto Teléfono --------------------------------------------------------
			$esteCampo = 'telefono';
			$atributos ['id'] = $esteCampo;
			$atributos ['nombre'] = $esteCampo;
			$atributos ['tipo'] = 'text';
			$atributos ['estilo'] = 'jqueryui';
			$atributos ['marco'] = true;
			$atributos ['estiloMarco'] = '';
			$atributos ["etiquetaObligatorio"] = true;
			$atributos ['columnas'] = 2;
			$atributos ['dobleLinea'] = 0;
			$atributos ['tabIndex'] = $tab;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ['validar'] = 'required, minSize[1],maxSize[13],custom[onlyNumberSp]';
		
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['valor'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['valor'] = '';
			}
			$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
			$atributos ['deshabilitado'] = true;
			$atributos ['tamanno'] = 15;
			$atributos ['maximoTamanno'] = '';
			$atributos ['anchoEtiqueta'] = 160;
			$tab ++;
		
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroTexto ( $atributos );
			unset ( $atributos );
			// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
		
			// ---------------- CONTROL: Cuadro de Texto Extensión --------------------------------------------------------
			$esteCampo = 'extension';
			$atributos ['id'] = $esteCampo;
			$atributos ['nombre'] = $esteCampo;
			$atributos ['tipo'] = 'text';
			$atributos ['estilo'] = 'jqueryui';
			$atributos ['marco'] = true;
			$atributos ['estiloMarco'] = '';
			$atributos ["etiquetaObligatorio"] = false;
			$atributos ['columnas'] = 2;
			$atributos ['dobleLinea'] = 0;
			$atributos ['tabIndex'] = $tab;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ['validar'] = 'minSize[1],maxSize[7],custom[onlyNumberSp]';
		
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['valor'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['valor'] = '';
			}
			$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
			$atributos ['deshabilitado'] = true;
			$atributos ['tamanno'] = 15;
			$atributos ['maximoTamanno'] = '';
			$atributos ['anchoEtiqueta'] = 160;
			$tab ++;
		
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroTexto ( $atributos );
			unset ( $atributos );
			// ---------------- FIN CONTROL: Cuadro de Texto  NIT--------------------------------------------------------
				
			/*
			 // ---------------- CONTROL: Cuadro de Texto Movil--------------------------------------------------------
			 $esteCampo = 'movil';
			 $atributos ['id'] = $esteCampo;
			 $atributos ['nombre'] = $esteCampo;
			 $atributos ['tipo'] = 'text';
			 $atributos ['estilo'] = 'jqueryui';
			 $atributos ['marco'] = true;
			 $atributos ['estiloMarco'] = '';
			 $atributos ["etiquetaObligatorio"] = true;
			 $atributos ['columnas'] = 3;
			 $atributos ['dobleLinea'] = 0;
			 $atributos ['tabIndex'] = $tab;
			 $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			 $atributos ['validar'] = 'required, minSize[1],maxSize[12],custom[onlyNumberSp]';
		
			 if (isset ( $_REQUEST [$esteCampo] )) {
			 $atributos ['valor'] = $_REQUEST [$esteCampo];
			 } else {
			 $atributos ['valor'] = '';
			 }
			 $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
			 $atributos ['deshabilitado'] = true;
			 $atributos ['tamanno'] = 15;
			 $atributos ['maximoTamanno'] = '';
			 $atributos ['anchoEtiqueta'] = 160;
			 $tab ++;
		
			 // Aplica atributos globales al control
			 $atributos = array_merge ( $atributos, $atributosGlobales );
			 echo $this->miFormulario->campoCuadroTexto ( $atributos );
			 unset ( $atributos );
			 // ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
			 */
		
			// ---------------- CONTROL: Cuadro de Texto  Asesor Comercial--------------------------------------------------------
			$esteCampo = 'asesorComercial';
			$atributos ['id'] = $esteCampo;
			$atributos ['nombre'] = $esteCampo;
			$atributos ['tipo'] = 'text';
			$atributos ['estilo'] = 'jqueryui';
			$atributos ['marco'] = true;
			$atributos ['estiloMarco'] = '';
			$atributos ["etiquetaObligatorio"] = true;
			$atributos ['columnas'] = 2;
			$atributos ['dobleLinea'] = 0;
			$atributos ['tabIndex'] = $tab;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ['validar'] = 'required, minSize[1],maxSize[150]';
		
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['valor'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['valor'] = '';
			}
			$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
			$atributos ['deshabilitado'] = true;
			$atributos ['tamanno'] = 15;
			$atributos ['maximoTamanno'] = '';
			$atributos ['anchoEtiqueta'] = 160;
			$tab ++;
		
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroTexto ( $atributos );
			unset ( $atributos );
			// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
		
			// ---------------- CONTROL: Cuadro de Texto Teléfono del Asesor--------------------------------------------------------
			$esteCampo = 'telAsesor';
			$atributos ['id'] = $esteCampo;
			$atributos ['nombre'] = $esteCampo;
			$atributos ['tipo'] = 'text';
			$atributos ['estilo'] = 'jqueryui';
			$atributos ['marco'] = true;
			$atributos ['estiloMarco'] = '';
			$atributos ["etiquetaObligatorio"] = true;
			$atributos ['columnas'] = 2;
			$atributos ['dobleLinea'] = 0;
			$atributos ['tabIndex'] = $tab;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ['validar'] = 'required, minSize[1],maxSize[15],custom[onlyNumberSp]';
		
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['valor'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['valor'] = '';
			}
			$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
			$atributos ['deshabilitado'] = true;
			$atributos ['tamanno'] = 15;
			$atributos ['maximoTamanno'] = '';
			$atributos ['anchoEtiqueta'] = 160;
			$tab ++;
		
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroTexto ( $atributos );
			unset ( $atributos );
			// ---------------- FIN CONTROL: Cuadro de Texto  NIT--------------------------------------------------------
		
			echo $this->miFormulario->marcoAgrupacion ( 'fin' );
		
			$esteCampo = "marcoRepresentante";
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
			$atributos ['tipoEtiqueta'] = 'inicio';
			$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
			echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
		
		
			// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
			$esteCampo = "tipoDocumento";
			$atributos ['nombre'] = $esteCampo;
			$atributos ['id'] = $esteCampo;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ["etiquetaObligatorio"] = true;
			$atributos ['tab'] = $tab ++;
			$atributos ['anchoEtiqueta'] = 200;
			$atributos ['evento'] = '';
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['seleccion'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['seleccion'] = -1;
			}
			$atributos ['deshabilitado'] = true;
			$atributos ['columnas'] = 1;
			$atributos ['tamanno'] = 1;
			$atributos ['estilo'] = "jqueryui";
			$atributos ['validar'] = "required";
			$atributos ['limitar'] = false;
			$atributos ['anchoCaja'] = 60;
			$atributos ['miEvento'] = '';
		
		
			$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "consultarTipoDocumento" );
			$matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
		
			// Valores a mostrar en el control
			/*$matrizItems = array (
			array ( 1, 'Registro Civil de Nacimiento' ),
			array ( 2, 'Tarjeta de Identidad' ),
			array ( 3, 'Cédula de Ciudadania' ),
			array ( 4, 'Certificado de Registraduria' ),
			array ( 5, 'Tarjeta de Extranjería' ),
			array ( 6, 'Cédula de Extranjería' ),
			array ( 7, 'Pasaporte' ),
			array ( 8, 'Carne Diplomatico' )
			);*/
			$atributos ['matrizItems'] = $matrizItems;
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroLista ( $atributos );
			unset ( $atributos );
			// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
		
		
			// ---------------- CONTROL: Cuadro de Texto NIT--------------------------------------------------------
			$esteCampo = 'numeroDocumento';
			$atributos ['id'] = $esteCampo;
			$atributos ['nombre'] = $esteCampo;
			$atributos ['tipo'] = 'text';
			$atributos ['estilo'] = 'jqueryui';
			$atributos ['marco'] = true;
			$atributos ['estiloMarco'] = '';
			$atributos ["etiquetaObligatorio"] = true;
			$atributos ['columnas'] = 3;
			$atributos ['dobleLinea'] = 0;
			$atributos ['tabIndex'] = $tab;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ['validar'] = 'required, minSize[1],maxSize[15],custom[onlyNumberSp]';
		
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['valor'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['valor'] = '';
			}
			$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
			$atributos ['deshabilitado'] = true;
			$atributos ['tamanno'] = 15;
			$atributos ['maximoTamanno'] = '';
			$atributos ['anchoEtiqueta'] = 200;
			$tab ++;
		
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroTexto ( $atributos );
			unset ( $atributos );
			// ---------------- FIN CONTROL: Cuadro de Texto  NIT--------------------------------------------------------
				
			// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
			$esteCampo = 'digitoRepre';
			$atributos ['id'] = $esteCampo;
			$atributos ['nombre'] = $esteCampo;
			$atributos ['tipo'] = 'text';
			$atributos ['estilo'] = 'jqueryui';
			$atributos ['marco'] = true;
			$atributos ['estiloMarco'] = '';
			$atributos ["etiquetaObligatorio"] = true;
			$atributos ['columnas'] = 3;
			$atributos ['dobleLinea'] = 0;
			$atributos ['tabIndex'] = $tab;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ['validar'] = 'minSize[1],maxSize[2],custom[onlyNumberSp]';
				
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['valor'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['valor'] = '';
			}
			$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
			$atributos ['deshabilitado'] = true;
			$atributos ['tamanno'] = 15;
			$atributos ['maximoTamanno'] = '';
			$atributos ['anchoEtiqueta'] = 200;
			$tab ++;
				
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroTexto ( $atributos );
			unset ( $atributos );
			// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
		
			// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
			$esteCampo = 'primerApellido';
			$atributos ['id'] = $esteCampo;
			$atributos ['nombre'] = $esteCampo;
			$atributos ['tipo'] = 'text';
			$atributos ['estilo'] = 'jqueryui';
			$atributos ['marco'] = true;
			$atributos ['estiloMarco'] = '';
			$atributos ["etiquetaObligatorio"] = true;
			$atributos ['columnas'] = 2;
			$atributos ['dobleLinea'] = 0;
			$atributos ['tabIndex'] = $tab;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ['validar'] = 'required, minSize[1],maxSize[30]';
		
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['valor'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['valor'] = '';
			}
			$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
			$atributos ['deshabilitado'] = true;
			$atributos ['tamanno'] = 15;
			$atributos ['maximoTamanno'] = '';
			$atributos ['anchoEtiqueta'] = 200;
			$tab ++;
		
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroTexto ( $atributos );
			unset ( $atributos );
			// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
		
			// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
			$esteCampo = 'segundoApellido';
			$atributos ['id'] = $esteCampo;
			$atributos ['nombre'] = $esteCampo;
			$atributos ['tipo'] = 'text';
			$atributos ['estilo'] = 'jqueryui';
			$atributos ['marco'] = true;
			$atributos ['estiloMarco'] = '';
			$atributos ["etiquetaObligatorio"] = false;
			$atributos ['columnas'] = 2;
			$atributos ['dobleLinea'] = 0;
			$atributos ['tabIndex'] = $tab;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ['validar'] = 'minSize[1],maxSize[30]';
		
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['valor'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['valor'] = '';
			}
			$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
			$atributos ['deshabilitado'] = true;
			$atributos ['tamanno'] = 15;
			$atributos ['maximoTamanno'] = '';
			$atributos ['anchoEtiqueta'] = 200;
			$tab ++;
		
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroTexto ( $atributos );
			unset ( $atributos );
			// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
		
			// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
			$esteCampo = 'primerNombre';
			$atributos ['id'] = $esteCampo;
			$atributos ['nombre'] = $esteCampo;
			$atributos ['tipo'] = 'text';
			$atributos ['estilo'] = 'jqueryui';
			$atributos ['marco'] = true;
			$atributos ['estiloMarco'] = '';
			$atributos ["etiquetaObligatorio"] = true;
			$atributos ['columnas'] = 2;
			$atributos ['dobleLinea'] = 0;
			$atributos ['tabIndex'] = $tab;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ['validar'] = 'required, minSize[1],maxSize[30]';
		
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['valor'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['valor'] = '';
			}
			$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
			$atributos ['deshabilitado'] = true;
			$atributos ['tamanno'] = 15;
			$atributos ['maximoTamanno'] = '';
			$atributos ['anchoEtiqueta'] = 200;
			$tab ++;
		
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroTexto ( $atributos );
			unset ( $atributos );
			// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
		
			// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
			$esteCampo = 'segundoNombre';
			$atributos ['id'] = $esteCampo;
			$atributos ['nombre'] = $esteCampo;
			$atributos ['tipo'] = 'text';
			$atributos ['estilo'] = 'jqueryui';
			$atributos ['marco'] = true;
			$atributos ['estiloMarco'] = '';
			$atributos ["etiquetaObligatorio"] = false;
			$atributos ['columnas'] = 2;
			$atributos ['dobleLinea'] = 0;
			$atributos ['tabIndex'] = $tab;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ['validar'] = 'minSize[1],maxSize[30]';
		
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['valor'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['valor'] = '';
			}
			$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
			$atributos ['deshabilitado'] = true;
			$atributos ['tamanno'] = 15;
			$atributos ['maximoTamanno'] = '';
			$atributos ['anchoEtiqueta'] = 200;
			$tab ++;
		
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroTexto ( $atributos );
			unset ( $atributos );
			// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
				
		
			// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
			$esteCampo = "genero";
			$atributos ['nombre'] = $esteCampo;
			$atributos ['id'] = $esteCampo;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ["etiquetaObligatorio"] = true;
			$atributos ['tab'] = $tab ++;
			$atributos ['anchoEtiqueta'] = 200;
			$atributos ['evento'] = '';
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['seleccion'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['seleccion'] = -1;
			}
			$atributos ['deshabilitado'] = true;
			$atributos ['columnas'] = 1;
			$atributos ['tamanno'] = 1;
			$atributos ['estilo'] = "jqueryui";
			$atributos ['validar'] = "required";
			$atributos ['limitar'] = false;
			$atributos ['anchoCaja'] = 60;
			$atributos ['miEvento'] = '';
		
			// Valores a mostrar en el control
			$matrizItems = array (
					array ( 1, 'Masculino' ),
					array ( 2, 'Femenino' )
			);
			$atributos ['matrizItems'] = $matrizItems;
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroLista ( $atributos );
			unset ( $atributos );
			// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
		
		
			// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
			$esteCampo = 'cargo';
			$atributos ['id'] = $esteCampo;
			$atributos ['nombre'] = $esteCampo;
			$atributos ['tipo'] = 'text';
			$atributos ['estilo'] = 'jqueryui';
			$atributos ['marco'] = true;
			$atributos ['estiloMarco'] = '';
			$atributos ["etiquetaObligatorio"] = true;
			$atributos ['columnas'] = 2;
			$atributos ['dobleLinea'] = 0;
			$atributos ['tabIndex'] = $tab;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ['validar'] = 'minSize[1],maxSize[30]';
		
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['valor'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['valor'] = '';
			}
			$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
			$atributos ['deshabilitado'] = true;
			$atributos ['tamanno'] = 40;
			$atributos ['maximoTamanno'] = '';
			$atributos ['anchoEtiqueta'] = 200;
			$tab ++;
		
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroTexto ( $atributos );
			unset ( $atributos );
			// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
		
			// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
			$esteCampo = 'correoPer';
			$atributos ['id'] = $esteCampo;
			$atributos ['nombre'] = $esteCampo;
			$atributos ['tipo'] = 'text';
			$atributos ['estilo'] = 'jqueryui';
			$atributos ['marco'] = true;
			$atributos ['estiloMarco'] = '';
			$atributos ["etiquetaObligatorio"] = true;
			$atributos ['columnas'] = 2;
			$atributos ['dobleLinea'] = 0;
			$atributos ['tabIndex'] = $tab;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ['validar'] = 'required, custom[email], maxSize[40]';
		
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['valor'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['valor'] = '';
			}
			$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
			$atributos ['deshabilitado'] = true;
			$atributos ['tamanno'] = 40;
			$atributos ['maximoTamanno'] = '';
			$atributos ['anchoEtiqueta'] = 200;
			$tab ++;
		
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroTexto ( $atributos );
			unset ( $atributos );
			// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
		
			// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
			$esteCampo = "paisNacimiento";
			$atributos ['nombre'] = $esteCampo;
			$atributos ['id'] = $esteCampo;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ["etiquetaObligatorio"] = true;
			$atributos ['tab'] = $tab ++;
			$atributos ['anchoEtiqueta'] = 200;
			$atributos ['evento'] = '';
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['seleccion'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['seleccion'] = -1;
			}
			$atributos ['deshabilitado'] = true;
			$atributos ['columnas'] = 1;
			$atributos ['tamanno'] = 1;
			$atributos ['estilo'] = "jqueryui";
			$atributos ['validar'] = "required";
			$atributos ['limitar'] = false;
			$atributos ['anchoCaja'] = 60;
			$atributos ['miEvento'] = '';
		
			$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "consultarPaises" );
			$matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
			/* Valores a mostrar en el control
			 $matrizItems = array (
			 array ( 1, 'Ahorros' ),
			 array ( 2, 'Corriente' )
			 );*/
			$atributos ['matrizItems'] = $matrizItems;
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroLista ( $atributos );
			unset ( $atributos );
			// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
		
			// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
			$esteCampo = "perfil";
			$atributos ['nombre'] = $esteCampo;
			$atributos ['id'] = $esteCampo;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ["etiquetaObligatorio"] = true;
			$atributos ['tab'] = $tab ++;
			$atributos ['anchoEtiqueta'] = 200;
			$atributos ['evento'] = '';
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['seleccion'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['seleccion'] = -1;
			}
			$atributos ['deshabilitado'] = true;
			$atributos ['columnas'] = 1;
			$atributos ['tamanno'] = 1;
			$atributos ['estilo'] = "jqueryui";
			$atributos ['validar'] = "required";
			$atributos ['limitar'] = false;
			$atributos ['anchoCaja'] = 60;
			$atributos ['miEvento'] = '';
		
			// Valores a mostrar en el control
			$matrizItems = array (
					array ( 1, 'Asistencial' ),
					array ( 2, 'Técnico' ),
					array ( 3, 'Profesional' ),
					array ( 4, 'Profesional Especializado' ),
					array ( 5, 'No Aplica' )
			);
			$atributos ['matrizItems'] = $matrizItems;
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroLista ( $atributos );
			unset ( $atributos );
			// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
		
			echo "<br>";
			echo "<br>";
			echo "<br>";
			echo "<br>";
			echo "<br>";
			echo "<br>";
			echo "<br>";
			echo "<br>";
			echo "<br>";
			echo "<br>";
			echo "<br>";
			echo "<br>";
			echo "<br>";
			echo "<br>";
			echo "<br>";
			echo "<br>";
			echo "<br>";
			echo "<br>";
		
			$atributos ["id"] = "obligatorioProfesion";
			$atributos ["estilo"] = "Marco";
			echo $this->miFormulario->division ( "inicio", $atributos );
			unset ( $atributos );
			{
				// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
				$esteCampo = 'profesion';
				$atributos ['id'] = $esteCampo;
				$atributos ['nombre'] = $esteCampo;
				$atributos ['tipo'] = 'text';
				$atributos ['estilo'] = 'jqueryui';
				$atributos ['marco'] = true;
				$atributos ['estiloMarco'] = '';
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['columnas'] = 2;
				$atributos ['dobleLinea'] = 0;
				$atributos ['tabIndex'] = $tab;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ['validar'] = 'required, minSize[1],maxSize[40]';
					
				if (isset ( $_REQUEST [$esteCampo] )) {
					$atributos ['valor'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['valor'] = '';
				}
				$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
				$atributos ['deshabilitado'] = true;
				$atributos ['tamanno'] = 40;
				$atributos ['maximoTamanno'] = '';
				$atributos ['anchoEtiqueta'] = 200;
				$tab ++;
					
				// Aplica atributos globales al control
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroTexto ( $atributos );
				unset ( $atributos );
				// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
			}
			echo $this->miFormulario->division ( "fin");
		
			$atributos ["id"] = "obligatorioEspecialidad";
			$atributos ["estilo"] = "Marco";
			echo $this->miFormulario->division ( "inicio", $atributos );
			unset ( $atributos );
			{
				// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
				$esteCampo = 'especialidad';
				$atributos ['id'] = $esteCampo;
				$atributos ['nombre'] = $esteCampo;
				$atributos ['tipo'] = 'text';
				$atributos ['estilo'] = 'jqueryui';
				$atributos ['marco'] = true;
				$atributos ['estiloMarco'] = '';
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['columnas'] = 2;
				$atributos ['dobleLinea'] = 0;
				$atributos ['tabIndex'] = $tab;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ['validar'] = 'required, minSize[1],maxSize[40]';
					
				if (isset ( $_REQUEST [$esteCampo] )) {
					$atributos ['valor'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['valor'] = '';
				}
				$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
				$atributos ['deshabilitado'] = true;
				$atributos ['tamanno'] = 40;
				$atributos ['maximoTamanno'] = '';
				$atributos ['anchoEtiqueta'] = 200;
				$tab ++;
					
				// Aplica atributos globales al control
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroTexto ( $atributos );
				unset ( $atributos );
				// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
			}
			echo $this->miFormulario->division ( "fin");
		
			// ---------------- CONTROL: Cuadro de Texto NIT--------------------------------------------------------
			$esteCampo = 'numeroContacto';
			$atributos ['id'] = $esteCampo;
			$atributos ['nombre'] = $esteCampo;
			$atributos ['tipo'] = 'text';
			$atributos ['estilo'] = 'jqueryui';
			$atributos ['marco'] = true;
			$atributos ['estiloMarco'] = '';
			$atributos ["etiquetaObligatorio"] = true;
			$atributos ['columnas'] = 1;
			$atributos ['dobleLinea'] = 0;
			$atributos ['tabIndex'] = $tab;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ['validar'] = 'required, minSize[1],maxSize[15],custom[onlyNumberSp]';
		
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['valor'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['valor'] = '';
			}
			$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
			$atributos ['deshabilitado'] = true;
			$atributos ['tamanno'] = 15;
			$atributos ['maximoTamanno'] = '';
			$atributos ['anchoEtiqueta'] = 200;
			$tab ++;
		
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroTexto ( $atributos );
			unset ( $atributos );
			// ---------------- FIN CONTROL: Cuadro de Texto  NIT--------------------------------------------------------
		
		
				
			echo $this->miFormulario->marcoAgrupacion ( 'fin' );
		
		
			$esteCampo = "marcoFinanciero";
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
			$atributos ['tipoEtiqueta'] = 'inicio';
			$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
			echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
		
			// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
			$esteCampo = "tipoCuenta";
			$atributos ['nombre'] = $esteCampo;
			$atributos ['id'] = $esteCampo;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ["etiquetaObligatorio"] = true;
			$atributos ['tab'] = $tab ++;
			$atributos ['anchoEtiqueta'] = 200;
			$atributos ['evento'] = '';
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['seleccion'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['seleccion'] = -1;
			}
			$atributos ['deshabilitado'] = true;
			$atributos ['columnas'] = 2;
			$atributos ['tamanno'] = 1;
			$atributos ['estilo'] = "jqueryui";
			$atributos ['validar'] = "required";
			$atributos ['limitar'] = false;
			$atributos ['anchoCaja'] = 60;
			$atributos ['miEvento'] = '';
		
			// Valores a mostrar en el control
			$matrizItems = array (
					array ( 1, 'Ahorros' ),
					array ( 2, 'Corriente' )
			);
			$atributos ['matrizItems'] = $matrizItems;
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroLista ( $atributos );
			unset ( $atributos );
			// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
		
			// ---------------- CONTROL: Cuadro de Texto NIT--------------------------------------------------------
			$esteCampo = 'numeroCuenta';
			$atributos ['id'] = $esteCampo;
			$atributos ['nombre'] = $esteCampo;
			$atributos ['tipo'] = 'text';
			$atributos ['estilo'] = 'jqueryui';
			$atributos ['marco'] = true;
			$atributos ['estiloMarco'] = '';
			$atributos ["etiquetaObligatorio"] = true;
			$atributos ['columnas'] = 2;
			$atributos ['dobleLinea'] = 0;
			$atributos ['tabIndex'] = $tab;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ['validar'] = 'required, minSize[1],maxSize[15],custom[onlyNumberSp]';
		
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['valor'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['valor'] = '';
			}
			$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
			$atributos ['deshabilitado'] = true;
			$atributos ['tamanno'] = 15;
			$atributos ['maximoTamanno'] = '';
			$atributos ['anchoEtiqueta'] = 200;
			$tab ++;
		
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroTexto ( $atributos );
			unset ( $atributos );
			// ---------------- FIN CONTROL: Cuadro de Texto  NIT--------------------------------------------------------
		
		
			// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
			$esteCampo = "entidadBancaria";
			$atributos ['nombre'] = $esteCampo;
			$atributos ['id'] = $esteCampo;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ["etiquetaObligatorio"] = true;
			$atributos ['tab'] = $tab ++;
			$atributos ['anchoEtiqueta'] = 200;
			$atributos ['evento'] = '';
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['seleccion'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['seleccion'] = -1;
			}
			$atributos ['deshabilitado'] = true;
			$atributos ['columnas'] = 1;
			$atributos ['tamanno'] = 1;
			$atributos ['estilo'] = "jqueryui";
			$atributos ['validar'] = "required";
			$atributos ['limitar'] = false;
			$atributos ['anchoCaja'] = 60;
			$atributos ['miEvento'] = '';
		
			$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "consultarBanco" );
			$matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
			/* Valores a mostrar en el control
			 $matrizItems = array (
			 array ( 1, 'Ahorros' ),
			 array ( 2, 'Corriente' )
			 );*/
			$atributos ['matrizItems'] = $matrizItems;
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroLista ( $atributos );
			unset ( $atributos );
			// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
		
			// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
			$esteCampo = "tipoConformacion";
			$atributos ['nombre'] = $esteCampo;
			$atributos ['id'] = $esteCampo;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ["etiquetaObligatorio"] = true;
			$atributos ['tab'] = $tab ++;
			$atributos ['anchoEtiqueta'] = 200;
			$atributos ['evento'] = '';
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['seleccion'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['seleccion'] = -1;
			}
			$atributos ['deshabilitado'] = true;
			$atributos ['columnas'] = 1;
			$atributos ['tamanno'] = 1;
			$atributos ['estilo'] = "jqueryui";
			$atributos ['validar'] = "required";
			$atributos ['limitar'] = false;
			$atributos ['anchoCaja'] = 60;
			$atributos ['miEvento'] = '';
		
			$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "consultarConformacion" );
			$matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
			/* Valores a mostrar en el control
			 $matrizItems = array (
			 array ( 1, 'Ahorros' ),
			 array ( 2, 'Corriente' )
			 );*/
			$atributos ['matrizItems'] = $matrizItems;
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroLista ( $atributos );
			unset ( $atributos );
			// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
		
			// ---------------- CONTROL: Cuadro de Texto NIT--------------------------------------------------------
			$esteCampo = 'monto';
			$atributos ['id'] = $esteCampo;
			$atributos ['nombre'] = $esteCampo;
			$atributos ['tipo'] = 'text';
			$atributos ['estilo'] = 'jqueryui';
			$atributos ['marco'] = true;
			$atributos ['estiloMarco'] = '';
			$atributos ["etiquetaObligatorio"] = true;
			$atributos ['columnas'] = 2;
			$atributos ['dobleLinea'] = 0;
			$atributos ['tabIndex'] = $tab;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ['validar'] = 'required, minSize[1],maxSize[15]';
		
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['valor'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['valor'] = '';
			}
			$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
			$atributos ['deshabilitado'] = true;
			$atributos ['tamanno'] = 15;
			$atributos ['maximoTamanno'] = '';
			$atributos ['anchoEtiqueta'] = 200;
			$tab ++;
		
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroTexto ( $atributos );
			unset ( $atributos );
			// ---------------- FIN CONTROL: Cuadro de Texto  NIT--------------------------------------------------------
		
		
		
			echo $this->miFormulario->marcoAgrupacion ( 'fin' );
		
			$esteCampo = "marcoEmpresa";
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
			$atributos ['tipoEtiqueta'] = 'inicio';
			$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
			echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
		
			// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
			$esteCampo = "productoImportacion";
			$atributos ['nombre'] = $esteCampo;
			$atributos ['id'] = $esteCampo;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ["etiquetaObligatorio"] = true;
			$atributos ['tab'] = $tab ++;
			$atributos ['anchoEtiqueta'] = 350;
			$atributos ['evento'] = '';
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['seleccion'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['seleccion'] = -1;
			}
			$atributos ['deshabilitado'] = true;
			$atributos ['columnas'] = 2;
			$atributos ['tamanno'] = 1;
			$atributos ['estilo'] = "jqueryui";
			$atributos ['validar'] = "required";
			$atributos ['limitar'] = false;
			$atributos ['anchoCaja'] = 60;
			$atributos ['miEvento'] = '';
			// Valores a mostrar en el control
			$matrizItems = array (
					array ( 1, 'Si' ),
					array ( 2, 'No' )
			);
			$atributos ['matrizItems'] = $matrizItems;
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroLista ( $atributos );
			unset ( $atributos );
			// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
				
			// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
			$esteCampo = "regimenContributivo";
			$atributos ['nombre'] = $esteCampo;
			$atributos ['id'] = $esteCampo;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ["etiquetaObligatorio"] = true;
			$atributos ['tab'] = $tab ++;
			$atributos ['anchoEtiqueta'] = 200;
			$atributos ['evento'] = '';
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['seleccion'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['seleccion'] = -1;
			}
			$atributos ['deshabilitado'] = true;
			$atributos ['columnas'] = 2;
			$atributos ['tamanno'] = 1;
			$atributos ['estilo'] = "jqueryui";
			$atributos ['validar'] = "required";
			$atributos ['limitar'] = false;
			$atributos ['anchoCaja'] = 60;
			$atributos ['miEvento'] = '';
			// Valores a mostrar en el control
			$matrizItems = array (
					array ( 1, 'Comun' ),
					array ( 2, 'Simplificado' )
			);
			$atributos ['matrizItems'] = $matrizItems;
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroLista ( $atributos );
			unset ( $atributos );
			// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
				
			// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
			$esteCampo = "pyme";
			$atributos ['nombre'] = $esteCampo;
			$atributos ['id'] = $esteCampo;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ["etiquetaObligatorio"] = true;
			$atributos ['tab'] = $tab ++;
			$atributos ['anchoEtiqueta'] = 350;
			$atributos ['evento'] = '';
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['seleccion'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['seleccion'] = -1;
			}
			$atributos ['deshabilitado'] = true;
			$atributos ['columnas'] = 2;
			$atributos ['tamanno'] = 1;
			$atributos ['estilo'] = "jqueryui";
			$atributos ['validar'] = "required";
			$atributos ['limitar'] = false;
			$atributos ['anchoCaja'] = 60;
			$atributos ['miEvento'] = '';
			// Valores a mostrar en el control
			$matrizItems = array (
					array ( 1, 'Si' ),
					array ( 2, 'No' )
			);
			$atributos ['matrizItems'] = $matrizItems;
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroLista ( $atributos );
			unset ( $atributos );
			// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
		
			// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
			$esteCampo = "registroMercantil";
			$atributos ['nombre'] = $esteCampo;
			$atributos ['id'] = $esteCampo;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ["etiquetaObligatorio"] = true;
			$atributos ['tab'] = $tab ++;
			$atributos ['anchoEtiqueta'] = 200;
			$atributos ['evento'] = '';
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['seleccion'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['seleccion'] = -1;
			}
			$atributos ['deshabilitado'] = true;
			$atributos ['columnas'] = 2;
			$atributos ['tamanno'] = 1;
			$atributos ['estilo'] = "jqueryui";
			$atributos ['validar'] = "required";
			$atributos ['limitar'] = false;
			$atributos ['anchoCaja'] = 60;
			$atributos ['miEvento'] = '';
			// Valores a mostrar en el control
			$matrizItems = array (
					array ( 1, 'Si' ),
					array ( 2, 'No' )
			);
			$atributos ['matrizItems'] = $matrizItems;
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroLista ( $atributos );
			unset ( $atributos );
			// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
				
			// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
			$esteCampo = "sujetoDeRetencion";
			$atributos ['nombre'] = $esteCampo;
			$atributos ['id'] = $esteCampo;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ["etiquetaObligatorio"] = true;
			$atributos ['tab'] = $tab ++;
			$atributos ['anchoEtiqueta'] = 350;
			$atributos ['evento'] = '';
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['seleccion'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['seleccion'] = -1;
			}
			$atributos ['deshabilitado'] = true;
			$atributos ['columnas'] = 2;
			$atributos ['tamanno'] = 1;
			$atributos ['estilo'] = "jqueryui";
			$atributos ['validar'] = "required";
			$atributos ['limitar'] = false;
			$atributos ['anchoCaja'] = 60;
			$atributos ['miEvento'] = '';
			// Valores a mostrar en el control
			$matrizItems = array (
					array ( 1, 'Si' ),
					array ( 2, 'No' )
			);
			$atributos ['matrizItems'] = $matrizItems;
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroLista ( $atributos );
			unset ( $atributos );
			// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
		
			// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
			$esteCampo = "agenteRetenedor";
			$atributos ['nombre'] = $esteCampo;
			$atributos ['id'] = $esteCampo;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ["etiquetaObligatorio"] = true;
			$atributos ['tab'] = $tab ++;
			$atributos ['anchoEtiqueta'] = 200;
			$atributos ['evento'] = '';
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['seleccion'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['seleccion'] = -1;
			}
			$atributos ['deshabilitado'] = true;
			$atributos ['columnas'] = 2;
			$atributos ['tamanno'] = 1;
			$atributos ['estilo'] = "jqueryui";
			$atributos ['validar'] = "required";
			$atributos ['limitar'] = false;
			$atributos ['anchoCaja'] = 60;
			$atributos ['miEvento'] = '';
			// Valores a mostrar en el control
			$matrizItems = array (
					array ( 1, 'Si' ),
					array ( 2, 'No' )
			);
			$atributos ['matrizItems'] = $matrizItems;
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroLista ( $atributos );
			unset ( $atributos );
			// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
		
			// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
			$esteCampo = "responsableICA";
			$atributos ['nombre'] = $esteCampo;
			$atributos ['id'] = $esteCampo;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ["etiquetaObligatorio"] = true;
			$atributos ['tab'] = $tab ++;
			$atributos ['anchoEtiqueta'] = 350;
			$atributos ['evento'] = '';
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['seleccion'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['seleccion'] = -1;
			}
			$atributos ['deshabilitado'] = true;
			$atributos ['columnas'] = 2;
			$atributos ['tamanno'] = 1;
			$atributos ['estilo'] = "jqueryui";
			$atributos ['validar'] = "required";
			$atributos ['limitar'] = false;
			$atributos ['anchoCaja'] = 60;
			$atributos ['miEvento'] = '';
			// Valores a mostrar en el control
			$matrizItems = array (
					array ( 1, 'Si' ),
					array ( 2, 'No' )
			);
			$atributos ['matrizItems'] = $matrizItems;
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroLista ( $atributos );
			unset ( $atributos );
			// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
		
			// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
			$esteCampo = "responsableIVA";
			$atributos ['nombre'] = $esteCampo;
			$atributos ['id'] = $esteCampo;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ["etiquetaObligatorio"] = true;
			$atributos ['tab'] = $tab ++;
			$atributos ['anchoEtiqueta'] = 200;
			$atributos ['evento'] = '';
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['seleccion'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['seleccion'] = -1;
			}
			$atributos ['deshabilitado'] = true;
			$atributos ['columnas'] = 2;
			$atributos ['tamanno'] = 1;
			$atributos ['estilo'] = "jqueryui";
			$atributos ['validar'] = "required";
			$atributos ['limitar'] = false;
			$atributos ['anchoCaja'] = 60;
			$atributos ['miEvento'] = '';
			// Valores a mostrar en el control
			$matrizItems = array (
					array ( 1, 'Si' ),
					array ( 2, 'No' )
			);
			$atributos ['matrizItems'] = $matrizItems;
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroLista ( $atributos );
			unset ( $atributos );
			// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
		
		
			echo $this->miFormulario->marcoAgrupacion ( 'fin' );
			
			
			
			
			
				
			$esteCampo = "marcoRUT";
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
			$atributos ['tipoEtiqueta'] = 'inicio';
			$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
			echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
			
			
			
			//INICIO enlace boton descargar RUT
			//------------------Division para los botones-------------------------
			$atributos["id"]="botones";
			$atributos["estilo"]="marcoBotones";
			echo $this->miFormulario->division("inicio",$atributos);
			
			$enlace = "<br><a href='".$_REQUEST['DocumentoRUT']."' target='_blank'>";
			$enlace.="<img src='".$rutaBloque."/images/pdf.png' width='35px'><br>Registro Único Tributario ";
			$enlace.="</a>";
			echo $enlace;
			//------------------Fin Division para los botones-------------------------
			echo $this->miFormulario->division("fin");
			//FIN enlace boton descargar RUT
			echo $this->miFormulario->marcoAgrupacion ( 'fin' );
			
			
			
			
		
		
			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
			$esteCampo = 'descripcion';
			$atributos ['id'] = $esteCampo;
			$atributos ['nombre'] = $esteCampo;
			$atributos ['tipo'] = 'text';
			$atributos ['estilo'] = 'jqueryui';
			$atributos ['marco'] = true;
			$atributos ['estiloMarco'] = '';
			$atributos ["etiquetaObligatorio"] = true;
			$atributos ['columnas'] = 90;
			$atributos ['filas'] = 2;
			$atributos ['dobleLinea'] = 0;
			$atributos ['tabIndex'] = $tab;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ['validar'] = 'required,maxSize[250]';
			$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
			$atributos ['deshabilitado'] = true;
			$atributos ['tamanno'] = 20;
			$atributos ['maximoTamanno'] = '';
			$atributos ['anchoEtiqueta'] = 220;
			if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['valor'] = $_REQUEST [$esteCampo];
			} else {
				$atributos ['valor'] = '';
			}
			$tab ++;
		
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoTextArea ( $atributos );
			unset ( $atributos );
		
			// ---------------- FIN CONTROL: Cuadro de Texto --------------------------------------------------------
			 
			// ------------------Division para los botones-------------------------
			$atributos ["id"] = "botones";
			$atributos ["estilo"] = "marcoBotones";
			echo $this->miFormulario->division ( "inicio", $atributos );
			unset ( $atributos );
			{
				// -----------------CONTROL: Botón ----------------------------------------------------------------
				$esteCampo = 'botonRegresar';
				$atributos ["id"] = $esteCampo;
				$atributos ["tabIndex"] = $tab;
				$atributos ["tipo"] = 'boton';
				// submit: no se coloca si se desea un tipo button genérico
				$atributos ['submit'] = true;
				$atributos ["estiloMarco"] = '';
				$atributos ["estiloBoton"] = 'jqueryui';
				// verificar: true para verificar el formulario antes de pasarlo al servidor.
				$atributos ["verificar"] = true;
				$atributos ["tipoSubmit"] = 'jquery'; // Dejar vacio para un submit normal, en este caso se ejecuta la función submit declarada en ready.js
				$atributos ["valor"] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ['nombreFormulario'] = $esteBloque ['nombre'];
				$tab ++;
					
				// Aplica atributos globales al control
				$atributos = array_merge ( $atributos, $atributosGlobales );
				//echo $this->miFormulario->campoBoton ( $atributos );
				// -----------------FIN CONTROL: Botón -----------------------------------------------------------
			}
			echo $this->miFormulario->division ( 'fin' );
		
			echo $this->miFormulario->marcoAgrupacion ( 'fin' );
			// ---------------- FIN SECCION: Controles del Formulario -------------------------------------------
			// ----------------FINALIZAR EL FORMULARIO ----------------------------------------------------------
			// Se debe declarar el mismo atributo de marco con que se inició el formulario.
				
			// -----------------FIN CONTROL: Botón -----------------------------------------------------------
			// ------------------Fin Division para los botones-------------------------
				
				
		
		
				
				
				
			//********************************************************************************************** PERSONA NATURAL****************************
				
			$esteCampo = "marcoDatosNaturalUPV";
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
			$atributos ['tipoEtiqueta'] = 'inicio';
			$atributos ["leyenda"] =  $this->lenguaje->getCadena ( $esteCampo );
			echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
			unset ( $atributos );
			{
					
				$esteCampo = "marcoPersona";
				$atributos ['id'] = $esteCampo;
				$atributos ["estilo"] = "jqueryui";
				$atributos ['tipoEtiqueta'] = 'inicio';
				$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
				echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
		
		
				// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
				$esteCampo = "tipoDocumentoNat";
				$atributos ['nombre'] = $esteCampo;
				$atributos ['id'] = $esteCampo;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['tab'] = $tab ++;
				$atributos ['anchoEtiqueta'] = 200;
				$atributos ['evento'] = '';
				if (isset ( $_REQUEST [$esteCampo] )) {
					$atributos ['seleccion'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['seleccion'] = -1;
				}
				$atributos ['deshabilitado'] = true;
				$atributos ['columnas'] = 1;
				$atributos ['tamanno'] = 1;
				$atributos ['estilo'] = "jqueryui";
				$atributos ['validar'] = "required";
				$atributos ['limitar'] = false;
				$atributos ['anchoCaja'] = 60;
				$atributos ['miEvento'] = '';
				// Valores a mostrar en el control
		
				$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "consultarTipoDocumento" );
				$matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
				/*$matrizItems = array (
				 array ( 1, 'Registro Civil de Nacimiento' ),
				 array ( 2, 'Tarjeta de Identidad' ),
				 array ( 3, 'Cédula de Ciudadania' ),
				 array ( 4, 'Certificado de Registraduria' ),
				 array ( 5, 'Tarjeta de Extranjería' ),
				 array ( 6, 'Cédula de Extranjería' ),
				 array ( 7, 'Pasaporte' ),
				 array ( 8, 'Carne Diplomatico' )
				 );*/
				$atributos ['matrizItems'] = $matrizItems;
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroLista ( $atributos );
				unset ( $atributos );
				// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
					
					
				// ---------------- CONTROL: Cuadro de Texto NIT--------------------------------------------------------
				$esteCampo = 'documentoNat';
				$atributos ['id'] = $esteCampo;
				$atributos ['nombre'] = $esteCampo;
				$atributos ['tipo'] = 'text';
				$atributos ['estilo'] = 'jqueryui';
				$atributos ['marco'] = true;
				$atributos ['estiloMarco'] = '';
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['columnas'] = 3;
				$atributos ['dobleLinea'] = 0;
				$atributos ['tabIndex'] = $tab;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ['validar'] = 'required, minSize[1],maxSize[14],custom[onlyNumberSp]';
					
				if (isset ( $_REQUEST [$esteCampo] )) {
					$atributos ['valor'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['valor'] = '';
				}
				$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
				$atributos ['deshabilitado'] = true;
				$atributos ['tamanno'] = 15;
				$atributos ['maximoTamanno'] = 15;
				$atributos ['anchoEtiqueta'] = 200;
				$tab ++;
					
				// Aplica atributos globales al control
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroTexto ( $atributos );
				unset ( $atributos );
				// ---------------- FIN CONTROL: Cuadro de Texto  NIT--------------------------------------------------------
					
				// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
				$esteCampo = 'digitoNat';
				$atributos ['id'] = $esteCampo;
				$atributos ['nombre'] = $esteCampo;
				$atributos ['tipo'] = 'text';
				$atributos ['estilo'] = 'jqueryui';
				$atributos ['marco'] = true;
				$atributos ['estiloMarco'] = '';
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['columnas'] = 3;
				$atributos ['dobleLinea'] = 0;
				$atributos ['tabIndex'] = $tab;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ['validar'] = 'minSize[1],maxSize[2],custom[onlyNumberSp]';
					
				if (isset ( $_REQUEST [$esteCampo] )) {
					$atributos ['valor'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['valor'] = '';
				}
				$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
				$atributos ['deshabilitado'] = true;
				$atributos ['tamanno'] = 15;
				$atributos ['maximoTamanno'] = '';
				$atributos ['anchoEtiqueta'] = 200;
				$tab ++;
					
				// Aplica atributos globales al control
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroTexto ( $atributos );
				unset ( $atributos );
				// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
					
		
				// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
				$esteCampo = 'primerApellidoNat';
				$atributos ['id'] = $esteCampo;
				$atributos ['nombre'] = $esteCampo;
				$atributos ['tipo'] = 'text';
				$atributos ['estilo'] = 'jqueryui';
				$atributos ['marco'] = true;
				$atributos ['estiloMarco'] = '';
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['columnas'] = 2;
				$atributos ['dobleLinea'] = 0;
				$atributos ['tabIndex'] = $tab;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ['validar'] = 'required, minSize[1],maxSize[30]';
		
				if (isset ( $_REQUEST [$esteCampo] )) {
					$atributos ['valor'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['valor'] = '';
				}
				$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
				$atributos ['deshabilitado'] = true;
				$atributos ['tamanno'] = 15;
				$atributos ['maximoTamanno'] = '';
				$atributos ['anchoEtiqueta'] = 200;
				$tab ++;
		
				// Aplica atributos globales al control
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroTexto ( $atributos );
				unset ( $atributos );
				// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
		
				// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
				$esteCampo = 'segundoApellidoNat';
				$atributos ['id'] = $esteCampo;
				$atributos ['nombre'] = $esteCampo;
				$atributos ['tipo'] = 'text';
				$atributos ['estilo'] = 'jqueryui';
				$atributos ['marco'] = true;
				$atributos ['estiloMarco'] = '';
				$atributos ["etiquetaObligatorio"] = false;
				$atributos ['columnas'] = 2;
				$atributos ['dobleLinea'] = 0;
				$atributos ['tabIndex'] = $tab;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ['validar'] = 'minSize[1],maxSize[30]';
		
				if (isset ( $_REQUEST [$esteCampo] )) {
					$atributos ['valor'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['valor'] = '';
				}
				$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
				$atributos ['deshabilitado'] = true;
				$atributos ['tamanno'] = 15;
				$atributos ['maximoTamanno'] = '';
				$atributos ['anchoEtiqueta'] = 200;
				$tab ++;
		
				// Aplica atributos globales al control
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroTexto ( $atributos );
				unset ( $atributos );
				// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
		
				// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
				$esteCampo = 'primerNombreNat';
				$atributos ['id'] = $esteCampo;
				$atributos ['nombre'] = $esteCampo;
				$atributos ['tipo'] = 'text';
				$atributos ['estilo'] = 'jqueryui';
				$atributos ['marco'] = true;
				$atributos ['estiloMarco'] = '';
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['columnas'] = 2;
				$atributos ['dobleLinea'] = 0;
				$atributos ['tabIndex'] = $tab;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ['validar'] = 'required, minSize[1],maxSize[30]';
		
				if (isset ( $_REQUEST [$esteCampo] )) {
					$atributos ['valor'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['valor'] = '';
				}
				$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
				$atributos ['deshabilitado'] = true;
				$atributos ['tamanno'] = 15;
				$atributos ['maximoTamanno'] = '';
				$atributos ['anchoEtiqueta'] = 200;
				$tab ++;
		
				// Aplica atributos globales al control
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroTexto ( $atributos );
				unset ( $atributos );
				// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
		
				// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
				$esteCampo = 'segundoNombreNat';
				$atributos ['id'] = $esteCampo;
				$atributos ['nombre'] = $esteCampo;
				$atributos ['tipo'] = 'text';
				$atributos ['estilo'] = 'jqueryui';
				$atributos ['marco'] = true;
				$atributos ['estiloMarco'] = '';
				$atributos ["etiquetaObligatorio"] = false;
				$atributos ['columnas'] = 2;
				$atributos ['dobleLinea'] = 0;
				$atributos ['tabIndex'] = $tab;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ['validar'] = 'minSize[1],maxSize[30]';
		
				if (isset ( $_REQUEST [$esteCampo] )) {
					$atributos ['valor'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['valor'] = '';
				}
				$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
				$atributos ['deshabilitado'] = true;
				$atributos ['tamanno'] = 15;
				$atributos ['maximoTamanno'] = '';
				$atributos ['anchoEtiqueta'] = 200;
				$tab ++;
		
				// Aplica atributos globales al control
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroTexto ( $atributos );
				unset ( $atributos );
				// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
		
		
				// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
				$esteCampo = "generoNat";
				$atributos ['nombre'] = $esteCampo;
				$atributos ['id'] = $esteCampo;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['tab'] = $tab ++;
				$atributos ['anchoEtiqueta'] = 200;
				$atributos ['evento'] = '';
				if (isset ( $_REQUEST [$esteCampo] )) {
					$atributos ['seleccion'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['seleccion'] = -1;
				}
				$atributos ['deshabilitado'] = true;
				$atributos ['columnas'] = 1;
				$atributos ['tamanno'] = 1;
				$atributos ['estilo'] = "jqueryui";
				$atributos ['validar'] = "required";
				$atributos ['limitar'] = false;
				$atributos ['anchoCaja'] = 60;
				$atributos ['miEvento'] = '';
		
				// Valores a mostrar en el control
				$matrizItems = array (
						array ( 1, 'Masculino' ),
						array ( 2, 'Femenino' )
				);
				$atributos ['matrizItems'] = $matrizItems;
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroLista ( $atributos );
				unset ( $atributos );
				// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
		
		
				// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
				$esteCampo = 'cargoNat';
				$atributos ['id'] = $esteCampo;
				$atributos ['nombre'] = $esteCampo;
				$atributos ['tipo'] = 'text';
				$atributos ['estilo'] = 'jqueryui';
				$atributos ['marco'] = true;
				$atributos ['estiloMarco'] = '';
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['columnas'] = 2;
				$atributos ['dobleLinea'] = 0;
				$atributos ['tabIndex'] = $tab;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ['validar'] = 'required,minSize[1],maxSize[30]';
		
				if (isset ( $_REQUEST [$esteCampo] )) {
					$atributos ['valor'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['valor'] = '';
				}
				$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
				$atributos ['deshabilitado'] = true;
				$atributos ['tamanno'] = 40;
				$atributos ['maximoTamanno'] = '';
				$atributos ['anchoEtiqueta'] = 200;
				$tab ++;
		
				// Aplica atributos globales al control
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroTexto ( $atributos );
				unset ( $atributos );
				// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
		
				/*// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
				 $esteCampo = 'correoPerNat';
				$atributos ['id'] = $esteCampo;
				$atributos ['nombre'] = $esteCampo;
				$atributos ['tipo'] = 'text';
				$atributos ['estilo'] = 'jqueryui';
				$atributos ['marco'] = true;
				$atributos ['estiloMarco'] = '';
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['columnas'] = 2;
				$atributos ['dobleLinea'] = 0;
				$atributos ['tabIndex'] = $tab;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ['validar'] = 'required, custom[email], maxSize[40]';
		
				if (isset ( $_REQUEST [$esteCampo] )) {
				$atributos ['valor'] = $_REQUEST [$esteCampo];
				} else {
				$atributos ['valor'] = '';
				}
				$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
				$atributos ['deshabilitado'] = true;
				$atributos ['tamanno'] = 40;
				$atributos ['maximoTamanno'] = '';
				$atributos ['anchoEtiqueta'] = 200;
				$tab ++;
		
				// Aplica atributos globales al control
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroTexto ( $atributos );
				unset ( $atributos );
				// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------*/
		
				// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
				$esteCampo = "paisNacimientoNat";
				$atributos ['nombre'] = $esteCampo;
				$atributos ['id'] = $esteCampo;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['tab'] = $tab ++;
				$atributos ['anchoEtiqueta'] = 200;
				$atributos ['evento'] = '';
				if (isset ( $_REQUEST [$esteCampo] )) {
					$atributos ['seleccion'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['seleccion'] = -1;
				}
				$atributos ['deshabilitado'] = true;
				$atributos ['columnas'] = 1;
				$atributos ['tamanno'] = 1;
				$atributos ['estilo'] = "jqueryui";
				$atributos ['validar'] = "required";
				$atributos ['limitar'] = false;
				$atributos ['anchoCaja'] = 60;
				$atributos ['miEvento'] = '';
		
				$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "consultarPaises" );
				$matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
				/* Valores a mostrar en el control
				 $matrizItems = array (
				 array ( 1, 'Ahorros' ),
				 array ( 2, 'Corriente' )
				 );*/
				$atributos ['matrizItems'] = $matrizItems;
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroLista ( $atributos );
				unset ( $atributos );
				// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
		
				// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
				$esteCampo = "perfilNat";
				$atributos ['nombre'] = $esteCampo;
				$atributos ['id'] = $esteCampo;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['tab'] = $tab ++;
				$atributos ['anchoEtiqueta'] = 200;
				$atributos ['evento'] = '';
				if (isset ( $_REQUEST [$esteCampo] )) {
					$atributos ['seleccion'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['seleccion'] = -1;
				}
				$atributos ['deshabilitado'] = true;
				$atributos ['columnas'] = 1;
				$atributos ['tamanno'] = 1;
				$atributos ['estilo'] = "jqueryui";
				$atributos ['validar'] = "required";
				$atributos ['limitar'] = false;
				$atributos ['anchoCaja'] = 60;
				$atributos ['miEvento'] = '';
		
				// Valores a mostrar en el control
				$matrizItems = array (
						array ( 1, 'Asistencial' ),
						array ( 2, 'Técnico' ),
						array ( 3, 'Profesional' ),
						array ( 4, 'Profesional Especializado' ),
						array ( 5, 'No Aplica' )
				);
				$atributos ['matrizItems'] = $matrizItems;
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroLista ( $atributos );
				unset ( $atributos );
				// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
		
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
		
				$atributos ["id"] = "obligatorioProfesionNat";
				$atributos ["estilo"] = "Marco";
				echo $this->miFormulario->division ( "inicio", $atributos );
				unset ( $atributos );
				{
					// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
					$esteCampo = 'profesionNat';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'text';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['estiloMarco'] = '';
					$atributos ["etiquetaObligatorio"] = true;
					$atributos ['columnas'] = 2;
					$atributos ['dobleLinea'] = 0;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ['validar'] = 'required, minSize[1],maxSize[40]';
		
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = true;
					$atributos ['tamanno'] = 40;
					$atributos ['maximoTamanno'] = '';
					$atributos ['anchoEtiqueta'] = 200;
					$tab ++;
		
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					unset ( $atributos );
					// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
				}
				echo $this->miFormulario->division ( "fin");
		
				$atributos ["id"] = "obligatorioEspecialidadNat";
				$atributos ["estilo"] = "Marco";
				echo $this->miFormulario->division ( "inicio", $atributos );
				unset ( $atributos );
				{
					// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
					$esteCampo = 'especialidadNat';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'text';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['estiloMarco'] = '';
					$atributos ["etiquetaObligatorio"] = true;
					$atributos ['columnas'] = 2;
					$atributos ['dobleLinea'] = 0;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ['validar'] = 'required, minSize[1],maxSize[40]';
		
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = true;
					$atributos ['tamanno'] = 40;
					$atributos ['maximoTamanno'] = '';
					$atributos ['anchoEtiqueta'] = 200;
					$tab ++;
		
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					unset ( $atributos );
					// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
				}
				echo $this->miFormulario->division ( "fin");
		
		
		
		
		
		
		
		
		
				echo $this->miFormulario->marcoAgrupacion ( 'fin' );
		
				
				
				
				$esteCampo = "marcoCaracterizacion";
				$atributos ['id'] = $esteCampo;
				$atributos ["estilo"] = "jqueryui";
				$atributos ['tipoEtiqueta'] = 'inicio';
				$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
				echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
				
				
				
				// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
				$esteCampo = "grupoEtnico";
				$atributos ['nombre'] = $esteCampo;
				$atributos ['id'] = $esteCampo;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ["etiquetaObligatorio"] = false;
				$atributos ['tab'] = $tab ++;
				$atributos ['anchoEtiqueta'] = 200;
				$atributos ['evento'] = '';
				if (isset ( $_REQUEST [$esteCampo] )) {
					$atributos ['seleccion'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['seleccion'] = -1;
				}
				$atributos ['deshabilitado'] = true;
				$atributos ['columnas'] = 1;
				$atributos ['tamanno'] = 1;
				$atributos ['estilo'] = "jqueryui";
				$atributos ['validar'] = " ";
				$atributos ['limitar'] = false;
				$atributos ['anchoCaja'] = 60;
				$atributos ['miEvento'] = '';
				// Valores a mostrar en el control
				
				$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "consultarGrupoEtnico" );
				$matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
				
				$atributos ['matrizItems'] = $matrizItems;
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroLista ( $atributos );
				unset ( $atributos );
				// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
				
				// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
				$esteCampo = "comunidadLGBT";
				$atributos ['nombre'] = $esteCampo;
				$atributos ['id'] = $esteCampo;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['tab'] = $tab ++;
				$atributos ['anchoEtiqueta'] = 350;
				$atributos ['evento'] = '';
				if (isset ( $_REQUEST [$esteCampo] )) {
					$atributos ['seleccion'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['seleccion'] = -1;
				}
				$atributos ['deshabilitado'] = true;
				$atributos ['columnas'] = 1;
				$atributos ['tamanno'] = 1;
				$atributos ['estilo'] = "jqueryui";
				$atributos ['validar'] = "required";
				$atributos ['limitar'] = false;
				$atributos ['anchoCaja'] = 60;
				$atributos ['miEvento'] = '';
				// Valores a mostrar en el control
				
				$matrizItems=array(
						array(1,'Si'),
						array(2,'No')
				);
				$atributos['matrizItems'] = $matrizItems;
					
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroLista ( $atributos );
				unset ( $atributos );
				// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
				
				
				// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
				$esteCampo = "cabezaFamilia";
				$atributos ['nombre'] = $esteCampo;
				$atributos ['id'] = $esteCampo;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['tab'] = $tab ++;
				$atributos ['anchoEtiqueta'] = 350;
				$atributos ['evento'] = '';
				if (isset ( $_REQUEST [$esteCampo] )) {
					$atributos ['seleccion'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['seleccion'] = -1;
				}
				$atributos ['deshabilitado'] = true;
				$atributos ['columnas'] = 1;
				$atributos ['tamanno'] = 1;
				$atributos ['estilo'] = "jqueryui";
				$atributos ['validar'] = "required";
				$atributos ['limitar'] = false;
				$atributos ['anchoCaja'] = 60;
				$atributos ['miEvento'] = '';
				// Valores a mostrar en el control
				
				$matrizItems=array(
						array(1,'Si'),
						array(2,'No')
				);
				$atributos['matrizItems'] = $matrizItems;
					
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroLista ( $atributos );
				unset ( $atributos );
				// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
				
				// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
				$esteCampo = "personasCargo";
				$atributos ['nombre'] = $esteCampo;
				$atributos ['id'] = $esteCampo;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['tab'] = $tab ++;
				$atributos ['anchoEtiqueta'] = 350;
				$atributos ['evento'] = '';
				if (isset ( $_REQUEST [$esteCampo] )) {
					$atributos ['seleccion'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['seleccion'] = -1;
				}
				$atributos ['deshabilitado'] = true;
				$atributos ['columnas'] = 1;
				$atributos ['tamanno'] = 1;
				$atributos ['estilo'] = "jqueryui";
				$atributos ['validar'] = "required";
				$atributos ['limitar'] = false;
				$atributos ['anchoCaja'] = 60;
				$atributos ['miEvento'] = '';
				// Valores a mostrar en el control
				
				$matrizItems=array(
						array(1,'Si'),
						array(2,'No')
				);
				$atributos['matrizItems'] = $matrizItems;
					
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroLista ( $atributos );
				unset ( $atributos );
				// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
				
				
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				
				// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
				
				$atributos ["id"] = "obligatorioCantidadPersonasACargo";
				$atributos ["estilo"] = "Marco";
				echo $this->miFormulario->division ( "inicio", $atributos );
				unset ( $atributos );
				{
					// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
					$esteCampo = 'numeroPersonasCargo';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'text';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['estiloMarco'] = '';
					$atributos ["etiquetaObligatorio"] = true;
					$atributos ['columnas'] = 2;
					$atributos ['dobleLinea'] = 0;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ['validar'] = 'required, custom[number], minSize[1],maxSize[10]';
				
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = true;
					$atributos ['tamanno'] = 15;
					$atributos ['maximoTamanno'] = '';
					$atributos ['anchoEtiqueta'] = 350;
					$tab ++;
				
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					unset ( $atributos );
					// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
				}
				echo $this->miFormulario->division ( "fin");
				
				
				// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
				$esteCampo = "estadoCivil";
				$atributos ['nombre'] = $esteCampo;
				$atributos ['id'] = $esteCampo;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ["etiquetaObligatorio"] = false;
				$atributos ['tab'] = $tab ++;
				$atributos ['anchoEtiqueta'] = 200;
				$atributos ['evento'] = '';
				if (isset ( $_REQUEST [$esteCampo] )) {
					$atributos ['seleccion'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['seleccion'] = -1;
				}
				$atributos ['deshabilitado'] = true;
				$atributos ['columnas'] = 1;
				$atributos ['tamanno'] = 1;
				$atributos ['estilo'] = "jqueryui";
				$atributos ['validar'] = " ";
				$atributos ['limitar'] = false;
				$atributos ['anchoCaja'] = 60;
				$atributos ['miEvento'] = '';
				// Valores a mostrar en el control
				
				$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "consultarTipoEstadoCivil" );
				$matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
				
				$atributos ['matrizItems'] = $matrizItems;
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroLista ( $atributos );
				unset ( $atributos );
				// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
				
				
				
				
				// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
				$esteCampo = "discapacidad";
				$atributos ['nombre'] = $esteCampo;
				$atributos ['id'] = $esteCampo;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['tab'] = $tab ++;
				$atributos ['anchoEtiqueta'] = 350;
				$atributos ['evento'] = '';
				if (isset ( $_REQUEST [$esteCampo] )) {
					$atributos ['seleccion'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['seleccion'] = -1;
				}
				$atributos ['deshabilitado'] = true;
				$atributos ['columnas'] = 1;
				$atributos ['tamanno'] = 1;
				$atributos ['estilo'] = "jqueryui";
				$atributos ['validar'] = "required";
				$atributos ['limitar'] = false;
				$atributos ['anchoCaja'] = 60;
				$atributos ['miEvento'] = '';
				// Valores a mostrar en el control
				
				$matrizItems=array(
						array(1,'Si'),
						array(2,'No')
				);
				$atributos['matrizItems'] = $matrizItems;
					
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroLista ( $atributos );
				unset ( $atributos );
				// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
				
				
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				
				// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
				
				$atributos ["id"] = "obligatorioTipoDiscapacidad";
				$atributos ["estilo"] = "Marco";
				echo $this->miFormulario->division ( "inicio", $atributos );
				unset ( $atributos );
				{
					// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
					$esteCampo = "tipoDiscapacidad";
					$atributos ['nombre'] = $esteCampo;
					$atributos ['id'] = $esteCampo;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ["etiquetaObligatorio"] = true;
					$atributos ['tab'] = $tab ++;
					$atributos ['anchoEtiqueta'] = 200;
					$atributos ['evento'] = '';
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['seleccion'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['seleccion'] = -1;
					}
					$atributos ['deshabilitado'] = true;
					$atributos ['columnas'] = 1;
					$atributos ['tamanno'] = 1;
					$atributos ['estilo'] = "jqueryui";
					$atributos ['validar'] = "required";
					$atributos ['limitar'] = false;
					$atributos ['anchoCaja'] = 60;
					$atributos ['miEvento'] = '';
					// Valores a mostrar en el control
				
					$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "consultarTipoDiscapacidad" );
					$matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
				
					$atributos ['matrizItems'] = $matrizItems;
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroLista ( $atributos );
					unset ( $atributos );
					// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
				}
				echo $this->miFormulario->division ( "fin");
				
				
				
				
				
				
				
				
				echo $this->miFormulario->marcoAgrupacion ( 'fin' );
				
				
		
		
				$esteCampo = "marcoContacto";
				$atributos ['id'] = $esteCampo;
				$atributos ["estilo"] = "jqueryui";
				$atributos ['tipoEtiqueta'] = 'inicio';
				$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
				echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
		
		
		
				// ---------------- CONTROL: Select --------------------------------------------------------
				$esteCampo = 'personaNaturalContaDepartamento';
				$atributos['nombre'] = $esteCampo;
				$atributos['id'] = $esteCampo;
				$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos['tab'] = $tab;
				
				$atributos['evento'] = ' ';
				$atributos['deshabilitado'] = true;
				$atributos['limitar']= 50;
				$atributos['tamanno']= 1;
				$atributos['columnas']= 1;
					
				$atributos ['obligatorio'] = true;
				$atributos ['etiquetaObligatorio'] = true;
				$atributos ['validar'] = 'required';
				
				
				$cadenaTest = $this->miSql->getCadenaSql ( "buscarCiudad", $_REQUEST['fki_idciudadContacto']);
				$matrizPrev = $esteRecursoDB->ejecutarAcceso ( $cadenaTest, "busqueda" );
				
				$cadenaTestP = $this->miSql->getCadenaSql ( "buscarPaisXDepa", $matrizPrev[0]['id_departamento']);
				$matrizPrevP = $esteRecursoDB->ejecutarAcceso ( $cadenaTestP, "busqueda" );
				
				$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarDepartamentoAjax", $matrizPrevP[0]['id_pais'] );
				$matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
					
				$atributos['matrizItems'] = $matrizItems;
				$atributos['seleccion'] = $matrizPrev[0]['id_departamento'];
				if (isset ( $_REQUEST [$esteCampo] )) {
					$atributos ['valor'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['valor'] = $matrizPrev[0]['id_departamento'];
				}
				$tab ++;
					
				// Aplica atributos globales al control
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroLista ( $atributos );
				// --------------- FIN CONTROL : Select --------------------------------------------------
					
				// ---------------- CONTROL: Select --------------------------------------------------------
				$esteCampo = 'personaNaturalContaCiudad';
				$atributos['nombre'] = $esteCampo;
				$atributos['id'] = $esteCampo;
				$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos['tab'] = $tab;
				$atributos['seleccion'] = $_REQUEST['fki_idciudadContacto'];
				$atributos['evento'] = ' ';
				$atributos['deshabilitado'] = true;
				$atributos['limitar']= 50;
				$atributos['tamanno']= 1;
				$atributos['columnas']= 1;
					
				$atributos ['obligatorio'] = true;
				$atributos ['etiquetaObligatorio'] = true;
				$atributos ['validar'] = 'required';
					
				$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarCiudadAjax", $matrizPrev[0]['id_departamento']);
				$matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
				
				$atributos['matrizItems'] = $matrizItems;
					
				if (isset ( $_REQUEST [$esteCampo] )) {
					$atributos ['valor'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['valor'] = '';
				}
				$tab ++;
					
				// Aplica atributos globales al control
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroLista ( $atributos );
		
					
		
		
				
				 // ---------------- CONTROL: Cuadro de Texto CIUDAD--------------------------------------------------------
				 $esteCampo = 'direccionNat';
				 $atributos ['id'] = $esteCampo;
				 $atributos ['nombre'] = $esteCampo;
				 $atributos ['tipo'] = 'text';
				 $atributos ['estilo'] = 'jqueryui';
				 $atributos ['marco'] = true;
				 $atributos ['estiloMarco'] = '';
				 $atributos ["etiquetaObligatorio"] = true;
				 $atributos ['columnas'] = 1;
				 $atributos ['dobleLinea'] = 0;
				 $atributos ['tabIndex'] = $tab;
				 $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				 $atributos ['validar'] = 'required, minSize[1],maxSize[50]';
		
				 if (isset ( $_REQUEST [$esteCampo] )) {
				 $atributos ['valor'] = $_REQUEST [$esteCampo];
				 } else {
				 $atributos ['valor'] = '';
				 }
				 $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
				 $atributos ['deshabilitado'] = true;
				 $atributos ['tamanno'] = 60;
				 $atributos ['maximoTamanno'] = '';
				 $atributos ['anchoEtiqueta'] = 160;
				 $tab ++;
		
				 // Aplica atributos globales al control
				 $atributos = array_merge ( $atributos, $atributosGlobales );
				 echo $this->miFormulario->campoCuadroTexto ( $atributos );
				 unset ( $atributos );
				 // ---------------- FIN CONTROL: Cuadro de Texto  NIT--------------------------------------------------------
				
		
				/*
		
				$esteCampo = "marcoDatosDireccion";
				$atributos ['id'] = $esteCampo;
				$atributos ["estilo"] = "jqueryui";
				$atributos ['tipoEtiqueta'] = 'inicio';
				$atributos ["leyenda"] = "Dirección Tipo DIAN";
				echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
				{
		
					$atributos ["id"] = "panelDireccion";
					$atributos ["estilo"] = "row";
					echo $this->miFormulario->division ( "inicio", $atributos );
					{
						$atributos ["id"] = "ingresoDireccion";
						$atributos ["estilo"] = "col-md-6";
						echo $this->miFormulario->division ( "inicio", $atributos );
						{
							// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
							$esteCampo = 'direccionNat';
							$atributos ['id'] = $esteCampo;
							$atributos ['nombre'] = $esteCampo;
							$atributos ['estilo'] = '';
							$atributos ['marco'] = false;
							$atributos ['correccion'] = false;
							$atributos ['columnas'] = 50;
							$atributos ['filas'] = 4;
							$atributos ['tabIndex'] = $tab;
							$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
							$atributos ['anchoEtiqueta'] = 150;
							$atributos ['deshabilitado'] =false;
								
							$atributos ['obligatorio'] = true;
							$atributos ['etiquetaObligatorio'] = true;
							$atributos ['validar'] = 'required, minSize[1], maxSize[5]';
								
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else {
								$atributos ['valor'] = '';
							}
							$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
							$tab ++;
								
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoTextArea ( $atributos );
							// --------------- FIN CONTROL : Cuadro de Texto --------------------------------------------------
		
							unset($atributos);
		
							unset($atributos);
		
							$atributos ["id"] = "ingresoBotones";
							$atributos ["estilo"] = "col-md-12";
							echo $this->miFormulario->division ( "inicio", $atributos );
							{
		
								$atributos ["id"] = "botonesPanel";
								$atributos ["estilo"] = "col-md-12 btn-group btn-group-lg";
								echo $this->miFormulario->division ( "inicio", $atributos );
								{
									echo "<input type=\"button\" id=\"btOper1Nat\" value=\"A\" class=\"btn btn-primary\"/>";
									echo "<input type=\"button\" id=\"btOper2Nat\" value=\"B\" class=\"btn btn-primary\" />";
									echo "<input type=\"button\" id=\"btOper3Nat\" value=\"C\" class=\"btn btn-primary\"/>";
									echo "<input type=\"button\" id=\"btOper4Nat\" value=\"D\" class=\"btn btn-primary\" />";
									echo "<input type=\"button\" id=\"btOper5Nat\" value=\"E\" class=\"btn btn-primary\"/>";
									echo "<input type=\"button\" id=\"btOper6Nat\" value=\"F\" class=\"btn btn-primary\" />";
									echo "<input type=\"button\" id=\"btOper7Nat\" value=\"G\" class=\"btn btn-primary\"/>";
									echo "<input type=\"button\" id=\"btOper8Nat\" value=\"H\" class=\"btn btn-primary\" />";
									echo "<input type=\"button\" id=\"btOper9Nat\" value=\"I\" class=\"btn btn-primary\" />";
									echo "<input type=\"button\" id=\"btOper10Nat\" value=\"J\" class=\"btn btn-primary\" />";
									echo "<input type=\"button\" id=\"btOper11Nat\" value=\"K\" class=\"btn btn-primary\" />";
									echo "<input type=\"button\" id=\"btOper12Nat\" value=\"L\" class=\"btn btn-primary\" />";
									echo "<input type=\"button\" id=\"btOper13Nat\" value=\"Borrar\" class=\"btn btn-danger\" />";
								}
								echo $this->miFormulario->division ( "fin" );
		
							}
							echo $this->miFormulario->division ( "fin" );
		
		
		
		
		
						}
						echo $this->miFormulario->division ( "fin" );
							
						$atributos ["id"] = "variables";
						$atributos ["estilo"] = "col-md-6";
						echo $this->miFormulario->division ( "inicio", $atributos );
						{
							unset($atributos);
								
								
							$esteCampo = "marcoDatosParametros";
							$atributos ['id'] = $esteCampo;
							$atributos ["estilo"] = "jqueryui";
							$atributos ['tipoEtiqueta'] = 'inicio';
							$atributos ["leyenda"] = "Panel Nomenclaturas DIAN";
							echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
							{
									
								$atributos ["id"] = "listaNomenclaturasNatural";
								$atributos ["estilo"] = "col-md-12";
								echo $this->miFormulario->division ( "inicio", $atributos );
								{
									// ---------------- CONTROL: Select --------------------------------------------------------
									$esteCampo = 'listaNomenclaturasNat';
									$atributos['nombre'] = $esteCampo;
									$atributos['id'] = $esteCampo;
									$atributos['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
									$atributos ['anchoEtiqueta'] = 230;
									$atributos['tab'] = $tab;
									$atributos['seleccion'] = -1;
									$atributos['evento'] = ' ';
									$atributos['deshabilitado'] = false;
									$atributos['limitar']= 50;
									$atributos['tamanno']= 1;
									$atributos['columnas']= 1;
										
									$atributos ['obligatorio'] = false;
									$atributos ['etiquetaObligatorio'] = false;
									$atributos ['validar'] = '';
										
		
									$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscarNomenclaturas" );
									$matrizParametros = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
										
									$atributos['matrizItems'] = $matrizParametros;
										
									if (isset ( $_REQUEST [$esteCampo] )) {
										$atributos ['valor'] = $_REQUEST [$esteCampo];
									} else {
										$atributos ['valor'] = '';
									}
									$atributos ["titulo"] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
									$tab ++;
										
									// Aplica atributos globales al control
									$atributos = array_merge ( $atributos, $atributosGlobales );
									echo $this->miFormulario->campoCuadroLista ( $atributos );
									// --------------- FIN CONTROL : Select --------------------------------------------------
								}
								echo $this->miFormulario->division ( "fin" );
									
								unset($atributos);
									
								$atributos ["id"] = "parametrosNat";
								$atributos ["estilo"] = "col-md-12";
								echo $this->miFormulario->division ( "inicio", $atributos );
								{
									// ---------------- CONTROL: Select --------------------------------------------------------
									$esteCampo = 'seccionParametrosNat';
									$atributos['nombre'] = $esteCampo;
									$atributos['id'] = $esteCampo;
									$atributos['etiqueta'] = '';
									$atributos ['anchoEtiqueta'] = 180;
									$atributos['tab'] = $tab;
									$atributos['seleccion'] = 0;
									$atributos['evento'] = ' ';
									$atributos['deshabilitado'] = true;
									$atributos['limitar']= 50;
									$atributos['tamanno']= 1;
									$atributos['columnas']= 1;
										
									$atributos ['obligatorio'] = false;
									$atributos ['etiquetaObligatorio'] = false;
									$atributos ['validar'] = '';
										
									//$atributos ['cadena_sql'] = $this->miSql->getCadenaSql("buscarCategoriaParametro");
									//$matrizParametros=$primerRecursoDB->ejecutarAcceso($atributos['cadena_sql'], "busqueda");
										
									//$atributos['matrizItems'] = $matrizParametros;
										
									$matrizItems=array(
											array(1,'Nomenclatura'),
											array(2,'TEST'),
												
									);
									$atributos['matrizItems'] = $matrizItems;
										
									if (isset ( $_REQUEST [$esteCampo] )) {
										$atributos ['valor'] = $_REQUEST [$esteCampo];
									} else {
										$atributos ['valor'] = '';
									}
									$atributos ["titulo"] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
									$tab ++;
										
									// Aplica atributos globales al control
									$atributos = array_merge ( $atributos, $atributosGlobales );
									echo $this->miFormulario->campoCuadroLista ( $atributos );
									// --------------- FIN CONTROL : Select --------------------------------------------------
								}
								echo $this->miFormulario->division ( "fin" );
									
							}
							echo $this->miFormulario->marcoAgrupacion ( "fin" );
								
							unset($atributos);
								
						}
						echo $this->miFormulario->division ( "fin" );
							
							
							
					}
					echo $this->miFormulario->division ( "fin" );
		
				}
				echo $this->miFormulario->marcoAgrupacion ( "fin" );
		
		
				*/
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
				/*
				 // ---------------- CONTROL: Cuadro de Texto  Dirección--------------------------------------------------------
				 $esteCampo = 'direccionNat';
				 $atributos ['id'] = $esteCampo;
				 $atributos ['nombre'] = $esteCampo;
				 $atributos ['tipo'] = 'text';
				 $atributos ['estilo'] = 'jqueryui';
				 $atributos ['marco'] = true;
				 $atributos ['estiloMarco'] = '';
				 $atributos ["etiquetaObligatorio"] = true;
				 $atributos ['columnas'] = 2;
				 $atributos ['dobleLinea'] = 0;
				 $atributos ['tabIndex'] = $tab;
				 $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				 $atributos ['validar'] = 'required, minSize[1],maxSize[150]';
		
				 if (isset ( $_REQUEST [$esteCampo] )) {
				 $atributos ['valor'] = $_REQUEST [$esteCampo];
				 } else {
				 $atributos ['valor'] = '';
				 }
				 $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
				 $atributos ['deshabilitado'] = true;
				 $atributos ['tamanno'] = 30;
				 $atributos ['maximoTamanno'] = '';
				 $atributos ['anchoEtiqueta'] = 160;
				 $tab ++;
		
				 // Aplica atributos globales al control
				 $atributos = array_merge ( $atributos, $atributosGlobales );
				 echo $this->miFormulario->campoCuadroTexto ( $atributos );
				 unset ( $atributos );
				 // ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------*/
					
				// ---------------- CONTROL: Cuadro de Texto Correo--------------------------------------------------------
				$esteCampo = 'correoNat';
				$atributos ['id'] = $esteCampo;
				$atributos ['nombre'] = $esteCampo;
				$atributos ['tipo'] = 'text';
				$atributos ['estilo'] = 'jqueryui';
				$atributos ['marco'] = true;
				$atributos ['estiloMarco'] = '';
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['columnas'] = 1;
				$atributos ['dobleLinea'] = 0;
				$atributos ['tabIndex'] = $tab;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ['validar'] = 'required, custom[email], maxSize[40]';
					
				if (isset ( $_REQUEST [$esteCampo] )) {
					$atributos ['valor'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['valor'] = '';
				}
				$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
				$atributos ['deshabilitado'] = true;
				$atributos ['tamanno'] = 30;
				$atributos ['maximoTamanno'] = '30';
				$atributos ['anchoEtiqueta'] = 160;
				$tab ++;
					
				// Aplica atributos globales al control
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroTexto ( $atributos );
				unset ( $atributos );
				// ---------------- FIN CONTROL: Cuadro de Texto  NIT--------------------------------------------------------
		
				// ---------------- CONTROL: Cuadro de Texto  Sitio Web--------------------------------------------------------
				$esteCampo = 'sitioWebNat';
				$atributos ['id'] = $esteCampo;
				$atributos ['nombre'] = $esteCampo;
				$atributos ['tipo'] = 'text';
				$atributos ['estilo'] = 'jqueryui';
				$atributos ['marco'] = true;
				$atributos ['estiloMarco'] = '';
				$atributos ["etiquetaObligatorio"] = false;
				$atributos ['columnas'] = 2;
				$atributos ['dobleLinea'] = 0;
				$atributos ['tabIndex'] = $tab;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ['validar'] = 'minSize[1],maxSize[100]';
		
				if (isset ( $_REQUEST [$esteCampo] )) {
					$atributos ['valor'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['valor'] = '';
				}
				$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
				$atributos ['deshabilitado'] = true;
				$atributos ['tamanno'] = 30;
				$atributos ['maximoTamanno'] = '';
				$atributos ['anchoEtiqueta'] = 160;
				$tab ++;
		
				// Aplica atributos globales al control
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroTexto ( $atributos );
				unset ( $atributos );
				// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
		
				// ---------------- CONTROL: Cuadro de Texto Teléfono --------------------------------------------------------
				$esteCampo = 'movilNat';
				$atributos ['id'] = $esteCampo;
				$atributos ['nombre'] = $esteCampo;
				$atributos ['tipo'] = 'text';
				$atributos ['estilo'] = 'jqueryui';
				$atributos ['marco'] = true;
				$atributos ['estiloMarco'] = '';
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['columnas'] = 3;
				$atributos ['dobleLinea'] = 0;
				$atributos ['tabIndex'] = $tab;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ['validar'] = 'required, minSize[1],maxSize[13],custom[onlyNumberSp]';
		
				if (isset ( $_REQUEST [$esteCampo] )) {
					$atributos ['valor'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['valor'] = '';
				}
				$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
				$atributos ['deshabilitado'] = true;
				$atributos ['tamanno'] = 15;
				$atributos ['maximoTamanno'] = '';
				$atributos ['anchoEtiqueta'] = 160;
				$tab ++;
		
				// Aplica atributos globales al control
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroTexto ( $atributos );
				unset ( $atributos );
				// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
		
				// ---------------- CONTROL: Cuadro de Texto Extensión --------------------------------------------------------
				$esteCampo = 'telefonoNat';
				$atributos ['id'] = $esteCampo;
				$atributos ['nombre'] = $esteCampo;
				$atributos ['tipo'] = 'text';
				$atributos ['estilo'] = 'jqueryui';
				$atributos ['marco'] = true;
				$atributos ['estiloMarco'] = '';
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['columnas'] = 2;
				$atributos ['dobleLinea'] = 0;
				$atributos ['tabIndex'] = $tab;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ['validar'] = 'required, minSize[1],maxSize[13],custom[onlyNumberSp]';
		
				if (isset ( $_REQUEST [$esteCampo] )) {
					$atributos ['valor'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['valor'] = '';
				}
				$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
				$atributos ['deshabilitado'] = true;
				$atributos ['tamanno'] = 15;
				$atributos ['maximoTamanno'] = '';
				$atributos ['anchoEtiqueta'] = 160;
				$tab ++;
		
				// Aplica atributos globales al control
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroTexto ( $atributos );
				unset ( $atributos );
				// ---------------- FIN CONTROL: Cuadro de Texto  NIT--------------------------------------------------------
		
				// ---------------- CONTROL: Cuadro de Texto Movil--------------------------------------------------------
				$esteCampo = 'extensionNat';
				$atributos ['id'] = $esteCampo;
				$atributos ['nombre'] = $esteCampo;
				$atributos ['tipo'] = 'text';
				$atributos ['estilo'] = 'jqueryui';
				$atributos ['marco'] = true;
				$atributos ['estiloMarco'] = '';
				$atributos ["etiquetaObligatorio"] = false;
				$atributos ['columnas'] = 2;
				$atributos ['dobleLinea'] = 0;
				$atributos ['tabIndex'] = $tab;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ['validar'] = 'minSize[1],maxSize[7],custom[onlyNumberSp]';
		
				if (isset ( $_REQUEST [$esteCampo] )) {
					$atributos ['valor'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['valor'] = '';
				}
				$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
				$atributos ['deshabilitado'] = true;
				$atributos ['tamanno'] = 15;
				$atributos ['maximoTamanno'] = '';
				$atributos ['anchoEtiqueta'] = 160;
				$tab ++;
		
				// Aplica atributos globales al control
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroTexto ( $atributos );
				unset ( $atributos );
				// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
		
				// ---------------- CONTROL: Cuadro de Texto  Asesor Comercial--------------------------------------------------------
				$esteCampo = 'asesorComercialNat';
				$atributos ['id'] = $esteCampo;
				$atributos ['nombre'] = $esteCampo;
				$atributos ['tipo'] = 'text';
				$atributos ['estilo'] = 'jqueryui';
				$atributos ['marco'] = true;
				$atributos ['estiloMarco'] = '';
				$atributos ["etiquetaObligatorio"] = false;
				$atributos ['columnas'] = 2;
				$atributos ['dobleLinea'] = 0;
				$atributos ['tabIndex'] = $tab;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ['validar'] = 'minSize[1],maxSize[150]';
		
				if (isset ( $_REQUEST [$esteCampo] )) {
					$atributos ['valor'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['valor'] = '';
				}
				$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
				$atributos ['deshabilitado'] = true;
				$atributos ['tamanno'] = 15;
				$atributos ['maximoTamanno'] = '';
				$atributos ['anchoEtiqueta'] = 160;
				$tab ++;
		
				// Aplica atributos globales al control
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroTexto ( $atributos );
				unset ( $atributos );
				// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
		
				// ---------------- CONTROL: Cuadro de Texto Teléfono del Asesor--------------------------------------------------------
				$esteCampo = 'telAsesorNat';
				$atributos ['id'] = $esteCampo;
				$atributos ['nombre'] = $esteCampo;
				$atributos ['tipo'] = 'text';
				$atributos ['estilo'] = 'jqueryui';
				$atributos ['marco'] = true;
				$atributos ['estiloMarco'] = '';
				$atributos ["etiquetaObligatorio"] = false;
				$atributos ['columnas'] = 2;
				$atributos ['dobleLinea'] = 0;
				$atributos ['tabIndex'] = $tab;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ['validar'] = 'minSize[1],maxSize[15],custom[onlyNumberSp]';
		
				if (isset ( $_REQUEST [$esteCampo] )) {
					$atributos ['valor'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['valor'] = '';
				}
				$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
				$atributos ['deshabilitado'] = true;
				$atributos ['tamanno'] = 15;
				$atributos ['maximoTamanno'] = '';
				$atributos ['anchoEtiqueta'] = 160;
				$tab ++;
		
				// Aplica atributos globales al control
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroTexto ( $atributos );
				unset ( $atributos );
				// ---------------- FIN CONTROL: Cuadro de Texto  NIT--------------------------------------------------------
		
				echo $this->miFormulario->marcoAgrupacion ( 'fin' );
		
				$esteCampo = "marcoFinanciero";
				$atributos ['id'] = $esteCampo;
				$atributos ["estilo"] = "jqueryui";
				$atributos ['tipoEtiqueta'] = 'inicio';
				$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
				echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
		
				// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
				$esteCampo = "tipoCuentaNat";
				$atributos ['nombre'] = $esteCampo;
				$atributos ['id'] = $esteCampo;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['tab'] = $tab ++;
				$atributos ['anchoEtiqueta'] = 200;
				$atributos ['evento'] = '';
				if (isset ( $_REQUEST [$esteCampo] )) {
					$atributos ['seleccion'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['seleccion'] = -1;
				}
				$atributos ['deshabilitado'] = true;
				$atributos ['columnas'] = 2;
				$atributos ['tamanno'] = 1;
				$atributos ['estilo'] = "jqueryui";
				$atributos ['validar'] = "required";
				$atributos ['limitar'] = false;
				$atributos ['anchoCaja'] = 60;
				$atributos ['miEvento'] = '';
		
				// Valores a mostrar en el control
				$matrizItems = array (
						array ( 1, 'Ahorros' ),
						array ( 2, 'Corriente' )
				);
				$atributos ['matrizItems'] = $matrizItems;
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroLista ( $atributos );
				unset ( $atributos );
				// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
		
				// ---------------- CONTROL: Cuadro de Texto NIT--------------------------------------------------------
				$esteCampo = 'numeroCuentaNat';
				$atributos ['id'] = $esteCampo;
				$atributos ['nombre'] = $esteCampo;
				$atributos ['tipo'] = 'text';
				$atributos ['estilo'] = 'jqueryui';
				$atributos ['marco'] = true;
				$atributos ['estiloMarco'] = '';
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['columnas'] = 2;
				$atributos ['dobleLinea'] = 0;
				$atributos ['tabIndex'] = $tab;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ['validar'] = 'required, minSize[1],maxSize[15],custom[onlyNumberSp]';
		
				if (isset ( $_REQUEST [$esteCampo] )) {
					$atributos ['valor'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['valor'] = '';
				}
				$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
				$atributos ['deshabilitado'] = true;
				$atributos ['tamanno'] = 15;
				$atributos ['maximoTamanno'] = '';
				$atributos ['anchoEtiqueta'] = 200;
				$tab ++;
		
				// Aplica atributos globales al control
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroTexto ( $atributos );
				unset ( $atributos );
				// ---------------- FIN CONTROL: Cuadro de Texto  NIT--------------------------------------------------------
		
		
				// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
				$esteCampo = "entidadBancariaNat";
				$atributos ['nombre'] = $esteCampo;
				$atributos ['id'] = $esteCampo;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['tab'] = $tab ++;
				$atributos ['anchoEtiqueta'] = 200;
				$atributos ['evento'] = '';
				if (isset ( $_REQUEST [$esteCampo] )) {
					$atributos ['seleccion'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['seleccion'] = -1;
				}
				$atributos ['deshabilitado'] = true;
				$atributos ['columnas'] = 1;
				$atributos ['tamanno'] = 1;
				$atributos ['estilo'] = "jqueryui";
				$atributos ['validar'] = "required";
				$atributos ['limitar'] = false;
				$atributos ['anchoCaja'] = 60;
				$atributos ['miEvento'] = '';
		
				$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "consultarBanco" );
				$matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
				/* Valores a mostrar en el control
				 $matrizItems = array (
				 array ( 1, 'Ahorros' ),
				 array ( 2, 'Corriente' )
				 );*/
				$atributos ['matrizItems'] = $matrizItems;
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroLista ( $atributos );
				unset ( $atributos );
				// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
		
				// ---------------- CONTROL: Cuadro de Texto NIT--------------------------------------------------------
				$esteCampo = 'tipoConformacionNat';
				$atributos ['id'] = $esteCampo;
				$atributos ['nombre'] = $esteCampo;
				$atributos ['tipo'] = 'hidden';
				$atributos ['estilo'] = 'jqueryui';
				$atributos ['marco'] = true;
				$atributos ['estiloMarco'] = '';
				$atributos ['columnas'] = 2;
				$atributos ['dobleLinea'] = 0;
				$atributos ['tabIndex'] = $tab;
		
				$atributos ['valor'] = 'Persona Natural';
				$tab ++;
		
				// Aplica atributos globales al control
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroTexto ( $atributos );
				unset ( $atributos );
				// ---------------- FIN CONTROL: Cuadro de Texto  NIT--------------------------------------------------------
		
				// ---------------- CONTROL: Cuadro de Texto NIT--------------------------------------------------------
				$esteCampo = 'montoNat';
				$atributos ['id'] = $esteCampo;
				$atributos ['nombre'] = $esteCampo;
				$atributos ['tipo'] = 'text';
				$atributos ['estilo'] = 'jqueryui';
				$atributos ['marco'] = true;
				$atributos ['estiloMarco'] = '';
				$atributos ["etiquetaObligatorio"] = false;
				$atributos ['columnas'] = 1;
				$atributos ['dobleLinea'] = 0;
				$atributos ['tabIndex'] = $tab;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ['validar'] = 'minSize[1],maxSize[15]';
		
				if (isset ( $_REQUEST [$esteCampo] )) {
					$atributos ['valor'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['valor'] = '';
				}
				$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
				$atributos ['deshabilitado'] = true;
				$atributos ['tamanno'] = 15;
				$atributos ['maximoTamanno'] = '';
				$atributos ['anchoEtiqueta'] = 200;
				$tab ++;
		
				// Aplica atributos globales al control
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroTexto ( $atributos );
				unset ( $atributos );
				// ---------------- FIN CONTROL: Cuadro de Texto  NIT--------------------------------------------------------
		
		
				echo $this->miFormulario->marcoAgrupacion ( 'fin' );
		
					
				
				
				
				$esteCampo = "marcoRUT";
				$atributos ['id'] = $esteCampo;
				$atributos ["estilo"] = "jqueryui";
				$atributos ['tipoEtiqueta'] = 'inicio';
				$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
				echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
					
					// ----------------FIN CONTROL: DOCUMENTO--------------------------------------------------------
					
					//INICIO enlace boton descargar RUT
					//------------------Division para los botones-------------------------
					$atributos["id"]="botones";
					$atributos["estilo"]="marcoBotones";
					echo $this->miFormulario->division("inicio",$atributos);
					
						$enlace = "<br><a href='".$_REQUEST['DocumentoRUTNat']."' target='_blank'>";
						$enlace.="<img src='".$rutaBloque."/images/pdf.png' width='35px'><br>Registro Único Tributario ";
						$enlace.="</a>";
						echo $enlace;
						//------------------Fin Division para los botones-------------------------
					echo $this->miFormulario->division("fin");
				//FIN enlace boton descargar RUT
				echo $this->miFormulario->marcoAgrupacion ( 'fin' );
				
				
				
				
		
		
				// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
				$esteCampo = 'descripcionNat';
				$atributos ['id'] = $esteCampo;
				$atributos ['nombre'] = $esteCampo;
				$atributos ['tipo'] = 'text';
				$atributos ['estilo'] = 'jqueryui';
				$atributos ['marco'] = true;
				$atributos ['estiloMarco'] = '';
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['columnas'] = 90;
				$atributos ['filas'] = 2;
				$atributos ['dobleLinea'] = 0;
				$atributos ['tabIndex'] = $tab;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ['validar'] = 'required,maxSize[250]';
				$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
				$atributos ['deshabilitado'] = true;
				$atributos ['tamanno'] = 20;
				$atributos ['maximoTamanno'] = '';
				$atributos ['anchoEtiqueta'] = 220;
				if (isset ( $_REQUEST [$esteCampo] )) {
					$atributos ['valor'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['valor'] = '';
				}
				$tab ++;
		
				// Aplica atributos globales al control
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoTextArea ( $atributos );
				unset ( $atributos );
		
				// ---------------- FIN CONTROL: Cuadro de Texto --------------------------------------------------------
					
				// ------------------Division para los botones-------------------------
				$atributos ["id"] = "botones";
				$atributos ["estilo"] = "marcoBotones";
				echo $this->miFormulario->division ( "inicio", $atributos );
				unset ( $atributos );
				{
					// -----------------CONTROL: Botón ----------------------------------------------------------------
					$esteCampo = 'botonRegresarNat';
					$atributos ["id"] = $esteCampo;
					$atributos ["tabIndex"] = $tab;
					$atributos ["tipo"] = 'boton';
					// submit: no se coloca si se desea un tipo button genérico
					$atributos ['submit'] = true;
					$atributos ["estiloMarco"] = '';
					$atributos ["estiloBoton"] = 'jqueryui';
					// verificar: true para verificar el formulario antes de pasarlo al servidor.
					$atributos ["verificar"] = true;
					$atributos ["tipoSubmit"] = 'jquery'; // Dejar vacio para un submit normal, en este caso se ejecuta la función submit declarada en ready.js
					$atributos ["valor"] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ['nombreFormulario'] = $esteBloque ['nombre'];
					$tab ++;
		
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					//echo $this->miFormulario->campoBoton ( $atributos );
					// -----------------FIN CONTROL: Botón -----------------------------------------------------------
				}
				echo $this->miFormulario->division ( 'fin' );
		
		
		
		
		
		
			}
			echo $this->miFormulario->marcoAgrupacion ( 'fin' );
				
			//*************************************************************************************************************************************+
				
		}
		echo $this->miFormulario->division ( "fin" );
		
		
		
		
		
		
		/*
		// ---------------- FIN: Lista Variables Modificar--------------------------------------------------------		

                        
			$esteCampo = "marcoDatos";
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
			$atributos ['tipoEtiqueta'] = 'inicio';
			$atributos ["leyenda"] =  $this->lenguaje->getCadena ( $esteCampo );
			echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
			unset ( $atributos );
			{	
			
				$esteCampo = "marcoEmpresa";
				$atributos ['id'] = $esteCampo;
				$atributos ["estilo"] = "jqueryui";
				$atributos ['tipoEtiqueta'] = 'inicio';
				$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
				echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos ); 

					// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
						$esteCampo = "tipoPersona";
						$atributos ['nombre'] = $esteCampo;
						$atributos ['id'] = $esteCampo;
						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
						$atributos ["etiquetaObligatorio"] = true;
						$atributos ['tab'] = $tab ++;
						$atributos ['anchoEtiqueta'] = 200;
						$atributos ['evento'] = '';
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['seleccion'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['seleccion'] = -1;
						}
						$atributos ['deshabilitado'] = true;
						$atributos ['columnas'] = 3;
						$atributos ['tamanno'] = 1;
						$atributos ['estilo'] = "jqueryui";
						$atributos ['validar'] = "required";
						$atributos ['limitar'] = false;
						$atributos ['anchoCaja'] = 60;
						$atributos ['miEvento'] = '';
						// Valores a mostrar en el control
						$matrizItems = array (
								array ( 1, 'Natural' ),
								array ( 2, 'Jurídica' ) 
						);
						$atributos ['matrizItems'] = $matrizItems;
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroLista ( $atributos );
						unset ( $atributos );
					// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
				
					// ---------------- CONTROL: Cuadro de Texto NIT--------------------------------------------------------
						$esteCampo = 'nit';
						$atributos ['id'] = $esteCampo;
						$atributos ['nombre'] = $esteCampo;
						$atributos ['tipo'] = 'text';
						$atributos ['estilo'] = 'jqueryui';
						$atributos ['marco'] = true;
						$atributos ['estiloMarco'] = '';
						$atributos ["etiquetaObligatorio"] = true;
						$atributos ['columnas'] = 3;
						$atributos ['dobleLinea'] = 0;
						$atributos ['tabIndex'] = $tab;
						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
						$atributos ['validar'] = 'required, minSize[1],maxSize[14],custom[onlyNumberSp]';
						
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['valor'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['valor'] = '';
						}
						$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
						$atributos ['deshabilitado'] = true;
						$atributos ['tamanno'] = 15;
						$atributos ['maximoTamanno'] = '';
						$atributos ['anchoEtiqueta'] = 200;
						$tab ++;
						
						// Aplica atributos globales al control
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroTexto ( $atributos );
						unset ( $atributos );
					// ---------------- FIN CONTROL: Cuadro de Texto  NIT--------------------------------------------------------
		
					// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
						$esteCampo = 'digito';
						$atributos ['id'] = $esteCampo;
						$atributos ['nombre'] = $esteCampo;
						$atributos ['tipo'] = 'text';
						$atributos ['estilo'] = 'jqueryui';
						$atributos ['marco'] = true;
						$atributos ['estiloMarco'] = '';
						$atributos ["etiquetaObligatorio"] = true;
						$atributos ['columnas'] = 3;
						$atributos ['dobleLinea'] = 0;
						$atributos ['tabIndex'] = $tab;
						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
						$atributos ['validar'] = 'minSize[1],maxSize[2],custom[onlyNumberSp]';
						
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['valor'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['valor'] = '';
						}
						$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
						$atributos ['deshabilitado'] = true;
						$atributos ['tamanno'] = 15;
						$atributos ['maximoTamanno'] = '';
						$atributos ['anchoEtiqueta'] = 200;
						$tab ++;
						
						// Aplica atributos globales al control
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroTexto ( $atributos );
						unset ( $atributos );
					// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
					
					// ---------------- CONTROL: Cuadro de Texto NOMBRE EMPRESA--------------------------------------------------------
						$esteCampo = 'nombreEmpresa';
						$atributos ['id'] = $esteCampo;
						$atributos ['nombre'] = $esteCampo;
						$atributos ['tipo'] = 'text';
						$atributos ['estilo'] = 'jqueryui';
						$atributos ['marco'] = true;
						$atributos ['estiloMarco'] = '';
						$atributos ["etiquetaObligatorio"] = true;
						$atributos ['columnas'] = 1;
						$atributos ['dobleLinea'] = 0;
						$atributos ['tabIndex'] = $tab;
						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
						$atributos ['validar'] = 'required, minSize[1],maxSize[100]';
						
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['valor'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['valor'] = '';
						}
						$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
						$atributos ['deshabilitado'] = true;
						$atributos ['tamanno'] = 50;
						$atributos ['maximoTamanno'] = '';
						$atributos ['anchoEtiqueta'] = 200;
						$tab ++;
						
						// Aplica atributos globales al control
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroTexto ( $atributos );
						unset ( $atributos );
					// ---------------- FIN CONTROL: Cuadro de Texto NOMBRE EMPRESA--------------------------------------------------------

						
						// ---------------- CONTROL: Lista NACIONALIDAD Empresa --------------------------------------------------------
						$esteCampo = "paisEmpresa";
						$atributos ['nombre'] = $esteCampo;
						$atributos ['id'] = $esteCampo;
						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
						$atributos ["etiquetaObligatorio"] = true;
						$atributos ['tab'] = $tab ++;
						$atributos ['anchoEtiqueta'] = 200;
						$atributos ['evento'] = '';
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['seleccion'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['seleccion'] = -1;
						}
						$atributos ['deshabilitado'] = true;
						$atributos ['columnas'] = 1;
						$atributos ['tamanno'] = 1;
						$atributos ['estilo'] = "jqueryui";
						$atributos ['validar'] = "required";
						$atributos ['limitar'] = false;
						$atributos ['anchoCaja'] = 60;
						$atributos ['miEvento'] = '';
						// Valores a mostrar en el control
						$matrizItems = array (
								array ( 1, 'Nacional' ),
								array ( 2, 'Extranjero' )
						);
						$atributos ['matrizItems'] = $matrizItems;
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroLista ( $atributos );
						unset ( $atributos );
						// ----------------FIN CONTROL: Lista NACIONALIDAD Empresa--------------------------------------------------------
						
						
						$esteCampo = "marcoProcedencia";
						$atributos ['id'] = $esteCampo;
						$atributos ["estilo"] = "jqueryui";
						$atributos ['tipoEtiqueta'] = 'inicio';
						$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
						echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
						unset($atributos);
						{
							// ---------------- CONTROL: Cuadro de Texto PAIS--------------------------------------------------------
							$esteCampo = 'pais';
							$atributos ['id'] = $esteCampo;
							$atributos ['nombre'] = $esteCampo;
							$atributos ['tipo'] = 'text';
							$atributos ['estilo'] = 'jqueryui';
							$atributos ['marco'] = true;
							$atributos ['estiloMarco'] = '';
							$atributos ["etiquetaObligatorio"] = true;
							$atributos ['columnas'] = 2;
							$atributos ['dobleLinea'] = 0;
							$atributos ['tabIndex'] = $tab ++;
							$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
							$atributos ['validar'] = 'required, minSize[1],maxSize[50]';
								
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else {
								$atributos ['valor'] = '';
							}
							$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
							$atributos ['deshabilitado'] = true;
							$atributos ['tamanno'] = 30;
							$atributos ['maximoTamanno'] = '';
							$atributos ['anchoEtiqueta'] = 160;
							$tab ++;
								
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroTexto ( $atributos );
							unset ( $atributos );
							// ---------------- FIN CONTROL: Cuadro de Texto  PAIS--------------------------------------------------------
								
							// ---------------- CONTROL: Cuadro de Texto  Codigo Pais--------------------------------------------------------
							$esteCampo = 'codigoPais';
							$atributos ['id'] = $esteCampo;
							$atributos ['nombre'] = $esteCampo;
							$atributos ['tipo'] = 'text';
							$atributos ['estilo'] = 'jqueryui';
							$atributos ['marco'] = true;
							$atributos ['estiloMarco'] = '';
							$atributos ["etiquetaObligatorio"] = true;
							$atributos ['columnas'] = 2;
							$atributos ['dobleLinea'] = 0;
							$atributos ['tabIndex'] = $tab;
							$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
							$atributos ['validar'] = 'required, minSize[1],maxSize[30],custom[onlyNumberSp]';
								
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else {
								$atributos ['valor'] = '';
							}
							$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
							$atributos ['deshabilitado'] = true;
							$atributos ['tamanno'] = 30;
							$atributos ['maximoTamanno'] = '';
							$atributos ['anchoEtiqueta'] = 160;
							$tab ++;
								
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroTexto ( $atributos );
							unset ( $atributos );
							// ---------------- FIN CONTROL: Cuadro de Texto  Codigo Pais--------------------------------------------------------
								
							// ---------------- CONTROL: Cuadro de Texto Codigo Postal--------------------------------------------------------
							$esteCampo = 'codigoPostal';
							$atributos ['id'] = $esteCampo;
							$atributos ['nombre'] = $esteCampo;
							$atributos ['tipo'] = 'text';
							$atributos ['estilo'] = 'jqueryui';
							$atributos ['marco'] = true;
							$atributos ['estiloMarco'] = '';
							$atributos ["etiquetaObligatorio"] = true;
							$atributos ['columnas'] = 2;
							$atributos ['dobleLinea'] = 0;
							$atributos ['tabIndex'] = $tab ++;
							$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
							$atributos ['validar'] = 'required, minSize[1],maxSize[30],custom[onlyNumberSp]';
						
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['valor'] = $_REQUEST [$esteCampo];
							} else {
								$atributos ['valor'] = '';
							}
							$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
							$atributos ['deshabilitado'] = true;
							$atributos ['tamanno'] = 30;
							$atributos ['maximoTamanno'] = '';
							$atributos ['anchoEtiqueta'] = 160;
							$tab ++;
						
							// Aplica atributos globales al control
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroTexto ( $atributos );
							unset ( $atributos );
							// ---------------- FIN CONTROL: Cuadro de Texto  Codigo Postal--------------------------------------------------------
						
							// ---------------- CONTROL: Lista Tipo Identificacion Empresa --------------------------------------------------------
							$esteCampo = "tipoIdentifiExtranjera";
							$atributos ['nombre'] = $esteCampo;
							$atributos ['id'] = $esteCampo;
							$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
							$atributos ["etiquetaObligatorio"] = true;
							$atributos ['tab'] = $tab ++;
							$atributos ['anchoEtiqueta'] = 300;
							$atributos ['evento'] = '';
							if (isset ( $_REQUEST [$esteCampo] )) {
								$atributos ['seleccion'] = $_REQUEST [$esteCampo];
							} else {
								$atributos ['seleccion'] = -1;
							}
							$atributos ['deshabilitado'] = true;
							$atributos ['columnas'] = 1;
							$atributos ['tamanno'] = 1;
							$atributos ['estilo'] = "jqueryui";
							$atributos ['validar'] = "required";
							$atributos ['limitar'] = false;
							$atributos ['anchoCaja'] = 60;
							$atributos ['miEvento'] = '';
							// Valores a mostrar en el control
							$matrizItems = array (
									array ( 1, 'Cédula de extranjería' ),
									array ( 2, 'Pasaporte' )
							);
							$atributos ['matrizItems'] = $matrizItems;
							$atributos = array_merge ( $atributos, $atributosGlobales );
							echo $this->miFormulario->campoCuadroLista ( $atributos );
							unset ( $atributos );
							// ----------------FIN CONTROL: Lista Tipo Identificacion Empresa--------------------------------------------------------
								
							$atributos ["id"] = "obligatorioCedula";
							$atributos ["estilo"] = "Marco";
							echo $this->miFormulario->division ( "inicio", $atributos );
							unset ( $atributos );
							{
						
								// ---------------- CONTROL: Cuadro de Texto CEDULA EXTRANJERIA--------------------------------------------------------
								$esteCampo = 'cedulaExtranjeria';
								$atributos ['id'] = $esteCampo;
								$atributos ['nombre'] = $esteCampo;
								$atributos ['tipo'] = 'text';
								$atributos ['estilo'] = 'jqueryui';
								$atributos ['marco'] = true;
								$atributos ['estiloMarco'] = '';
								$atributos ["etiquetaObligatorio"] = true;
								$atributos ['columnas'] = 1;
								$atributos ['dobleLinea'] = 0;
								$atributos ['tabIndex'] = $tab ++;
								$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
								$atributos ['validar'] = 'required, minSize[1],maxSize[30],custom[onlyNumberSp]';
									
								if (isset ( $_REQUEST [$esteCampo] )) {
									$atributos ['valor'] = $_REQUEST [$esteCampo];
								} else {
									$atributos ['valor'] = '';
								}
								$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
								$atributos ['deshabilitado'] = true;
								$atributos ['tamanno'] = 30;
								$atributos ['maximoTamanno'] = '';
								$atributos ['anchoEtiqueta'] = 190;
								$tab ++;
									
								// Aplica atributos globales al control
								$atributos = array_merge ( $atributos, $atributosGlobales );
								echo $this->miFormulario->campoCuadroTexto ( $atributos );
								unset ( $atributos );
								// ---------------- FIN CONTROL: Cuadro de Texto  CEDULA EXTRANJERIA--------------------------------------------------------
						
							}
							echo $this->miFormulario->division ( 'fin' );
								
							$atributos ["id"] = "obligatorioPasaporte";
							$atributos ["estilo"] = "Marco";
							echo $this->miFormulario->division ( "inicio", $atributos );
							unset ( $atributos );
							{
									
								// ---------------- CONTROL: Cuadro de Texto  PASAPORTE--------------------------------------------------------
								$esteCampo = 'pasaporte';
								$atributos ['id'] = $esteCampo;
								$atributos ['nombre'] = $esteCampo;
								$atributos ['tipo'] = 'text';
								$atributos ['estilo'] = 'jqueryui';
								$atributos ['marco'] = true;
								$atributos ['estiloMarco'] = '';
								$atributos ["etiquetaObligatorio"] = true;
								$atributos ['columnas'] = 1;
								$atributos ['dobleLinea'] = 0;
								$atributos ['tabIndex'] = $tab;
								$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
								$atributos ['validar'] = 'required, minSize[1],maxSize[30],custom[onlyNumberSp]';
									
								if (isset ( $_REQUEST [$esteCampo] )) {
									$atributos ['valor'] = $_REQUEST [$esteCampo];
								} else {
									$atributos ['valor'] = '';
								}
								$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
								$atributos ['deshabilitado'] = true;
								$atributos ['tamanno'] = 30;
								$atributos ['maximoTamanno'] = '';
								$atributos ['anchoEtiqueta'] = 190;
								$tab ++;
									
								// Aplica atributos globales al control
								$atributos = array_merge ( $atributos, $atributosGlobales );
								echo $this->miFormulario->campoCuadroTexto ( $atributos );
								unset ( $atributos );
								// ---------------- FIN CONTROL: Cuadro de Texto  PASAPORTE--------------------------------------------------------
									
									
							}
							echo $this->miFormulario->division ( 'fin' );
								
						}
						echo $this->miFormulario->marcoAgrupacion ( 'fin' );
						
							
						
						
						
						
				echo $this->miFormulario->marcoAgrupacion ( 'fin' );			
			
			
				$esteCampo = "marcoContacto";
				$atributos ['id'] = $esteCampo;
				$atributos ["estilo"] = "jqueryui";
				$atributos ['tipoEtiqueta'] = 'inicio';
				$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
				echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );                    
				
					// ---------------- CONTROL: Cuadro de Texto CIUDAD--------------------------------------------------------
						$esteCampo = 'ciudad';
						$atributos ['id'] = $esteCampo;
						$atributos ['nombre'] = $esteCampo;
						$atributos ['tipo'] = 'text';
						$atributos ['estilo'] = 'jqueryui';
						$atributos ['marco'] = true;
						$atributos ['estiloMarco'] = '';
						$atributos ["etiquetaObligatorio"] = true;
						$atributos ['columnas'] = 2;
						$atributos ['dobleLinea'] = 0;
						$atributos ['tabIndex'] = $tab;
						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
						$atributos ['validar'] = 'required, minSize[1],maxSize[50]';
						
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['valor'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['valor'] = '';
						}
						$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
						$atributos ['deshabilitado'] = true;
						$atributos ['tamanno'] = 15;
						$atributos ['maximoTamanno'] = '';
						$atributos ['anchoEtiqueta'] = 160;
						$tab ++;
						
						// Aplica atributos globales al control
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroTexto ( $atributos );
						unset ( $atributos );
					// ---------------- FIN CONTROL: Cuadro de Texto  NIT--------------------------------------------------------
		
					// ---------------- CONTROL: Cuadro de Texto  Dirección--------------------------------------------------------
						$esteCampo = 'direccion';
						$atributos ['id'] = $esteCampo;
						$atributos ['nombre'] = $esteCampo;
						$atributos ['tipo'] = 'text';
						$atributos ['estilo'] = 'jqueryui';
						$atributos ['marco'] = true;
						$atributos ['estiloMarco'] = '';
						$atributos ["etiquetaObligatorio"] = true;
						$atributos ['columnas'] = 2;
						$atributos ['dobleLinea'] = 0;
						$atributos ['tabIndex'] = $tab;
						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
						$atributos ['validar'] = 'required, minSize[1],maxSize[150]';
						
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['valor'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['valor'] = '';
						}
						$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
						$atributos ['deshabilitado'] = true;
						$atributos ['tamanno'] = 30;
						$atributos ['maximoTamanno'] = '';
						$atributos ['anchoEtiqueta'] = 160;
						$tab ++;
						
						// Aplica atributos globales al control
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroTexto ( $atributos );
						unset ( $atributos );
					// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
					
					// ---------------- CONTROL: Cuadro de Texto Correo--------------------------------------------------------
						$esteCampo = 'correo';
						$atributos ['id'] = $esteCampo;
						$atributos ['nombre'] = $esteCampo;
						$atributos ['tipo'] = 'text';
						$atributos ['estilo'] = 'jqueryui';
						$atributos ['marco'] = true;
						$atributos ['estiloMarco'] = '';
						$atributos ["etiquetaObligatorio"] = true;
						$atributos ['columnas'] = 2;
						$atributos ['dobleLinea'] = 0;
						$atributos ['tabIndex'] = $tab;
						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
						$atributos ['validar'] = 'required, custom[email], maxSize[40]';
							
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['valor'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['valor'] = '';
						}
						$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
						$atributos ['deshabilitado'] = true;
						$atributos ['tamanno'] = 30;
						$atributos ['maximoTamanno'] = '30';
						$atributos ['anchoEtiqueta'] = 160;
						$tab ++;
							
						// Aplica atributos globales al control
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroTexto ( $atributos );
						unset ( $atributos );
					// ---------------- FIN CONTROL: Cuadro de Texto  NIT--------------------------------------------------------
		
					// ---------------- CONTROL: Cuadro de Texto  Sitio Web--------------------------------------------------------
						$esteCampo = 'sitioWeb';
						$atributos ['id'] = $esteCampo;
						$atributos ['nombre'] = $esteCampo;
						$atributos ['tipo'] = 'text';
						$atributos ['estilo'] = 'jqueryui';
						$atributos ['marco'] = true;
						$atributos ['estiloMarco'] = '';
						$atributos ["etiquetaObligatorio"] = true;
						$atributos ['columnas'] = 2;
						$atributos ['dobleLinea'] = 0;
						$atributos ['tabIndex'] = $tab;
						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
						$atributos ['validar'] = 'required, minSize[1],maxSize[100]';
						
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['valor'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['valor'] = '';
						}
						$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
						$atributos ['deshabilitado'] = true;
						$atributos ['tamanno'] = 30;
						$atributos ['maximoTamanno'] = '';
						$atributos ['anchoEtiqueta'] = 160;
						$tab ++;
						
						// Aplica atributos globales al control
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroTexto ( $atributos );
						unset ( $atributos );
					// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------

					// ---------------- CONTROL: Cuadro de Texto Teléfono --------------------------------------------------------
						$esteCampo = 'telefono';
						$atributos ['id'] = $esteCampo;
						$atributos ['nombre'] = $esteCampo;
						$atributos ['tipo'] = 'text';
						$atributos ['estilo'] = 'jqueryui';
						$atributos ['marco'] = true;
						$atributos ['estiloMarco'] = '';
						$atributos ["etiquetaObligatorio"] = true;
						$atributos ['columnas'] = 3;
						$atributos ['dobleLinea'] = 0;
						$atributos ['tabIndex'] = $tab;
						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
						$atributos ['validar'] = 'required, minSize[1],maxSize[13],custom[onlyNumberSp]';
						
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['valor'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['valor'] = '';
						}
						$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
						$atributos ['deshabilitado'] = true;
						$atributos ['tamanno'] = 15;
						$atributos ['maximoTamanno'] = '';
						$atributos ['anchoEtiqueta'] = 160;
						$tab ++;
						
						// Aplica atributos globales al control
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroTexto ( $atributos );
						unset ( $atributos );
					// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------

					// ---------------- CONTROL: Cuadro de Texto Extensión --------------------------------------------------------
						$esteCampo = 'extension';
						$atributos ['id'] = $esteCampo;
						$atributos ['nombre'] = $esteCampo;
						$atributos ['tipo'] = 'text';
						$atributos ['estilo'] = 'jqueryui';
						$atributos ['marco'] = true;
						$atributos ['estiloMarco'] = '';
						$atributos ["etiquetaObligatorio"] = true;
						$atributos ['columnas'] = 3;
						$atributos ['dobleLinea'] = 0;
						$atributos ['tabIndex'] = $tab;
						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
						$atributos ['validar'] = 'required, minSize[1],maxSize[7],custom[onlyNumberSp]';
						
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['valor'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['valor'] = '';
						}
						$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
						$atributos ['deshabilitado'] = true;
						$atributos ['tamanno'] = 15;
						$atributos ['maximoTamanno'] = '';
						$atributos ['anchoEtiqueta'] = 160;
						$tab ++;
						
						// Aplica atributos globales al control
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroTexto ( $atributos );
						unset ( $atributos );
					// ---------------- FIN CONTROL: Cuadro de Texto  NIT--------------------------------------------------------
		
					// ---------------- CONTROL: Cuadro de Texto Movil--------------------------------------------------------
						$esteCampo = 'movil';
						$atributos ['id'] = $esteCampo;
						$atributos ['nombre'] = $esteCampo;
						$atributos ['tipo'] = 'text';
						$atributos ['estilo'] = 'jqueryui';
						$atributos ['marco'] = true;
						$atributos ['estiloMarco'] = '';
						$atributos ["etiquetaObligatorio"] = true;
						$atributos ['columnas'] = 3;
						$atributos ['dobleLinea'] = 0;
						$atributos ['tabIndex'] = $tab;
						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
						$atributos ['validar'] = 'required, minSize[1],maxSize[12],custom[onlyNumberSp]';
						
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['valor'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['valor'] = '';
						}
						$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
						$atributos ['deshabilitado'] = true;
						$atributos ['tamanno'] = 15;
						$atributos ['maximoTamanno'] = '';
						$atributos ['anchoEtiqueta'] = 160;
						$tab ++;
						
						// Aplica atributos globales al control
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroTexto ( $atributos );
						unset ( $atributos );
					// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------

					// ---------------- CONTROL: Cuadro de Texto  Asesor Comercial--------------------------------------------------------
						$esteCampo = 'asesorComercial';
						$atributos ['id'] = $esteCampo;
						$atributos ['nombre'] = $esteCampo;
						$atributos ['tipo'] = 'text';
						$atributos ['estilo'] = 'jqueryui';
						$atributos ['marco'] = true;
						$atributos ['estiloMarco'] = '';
						$atributos ["etiquetaObligatorio"] = true;
						$atributos ['columnas'] = 2;
						$atributos ['dobleLinea'] = 0;
						$atributos ['tabIndex'] = $tab;
						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
						$atributos ['validar'] = 'required, minSize[1],maxSize[150]';
						
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['valor'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['valor'] = '';
						}
						$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
						$atributos ['deshabilitado'] = true;
						$atributos ['tamanno'] = 15;
						$atributos ['maximoTamanno'] = '';
						$atributos ['anchoEtiqueta'] = 160;
						$tab ++;
						
						// Aplica atributos globales al control
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroTexto ( $atributos );
						unset ( $atributos );
					// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------

					// ---------------- CONTROL: Cuadro de Texto Teléfono del Asesor--------------------------------------------------------
						$esteCampo = 'telAsesor';
						$atributos ['id'] = $esteCampo;
						$atributos ['nombre'] = $esteCampo;
						$atributos ['tipo'] = 'text';
						$atributos ['estilo'] = 'jqueryui';
						$atributos ['marco'] = true;
						$atributos ['estiloMarco'] = '';
						$atributos ["etiquetaObligatorio"] = true;
						$atributos ['columnas'] = 2;
						$atributos ['dobleLinea'] = 0;
						$atributos ['tabIndex'] = $tab;
						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
						$atributos ['validar'] = 'required, minSize[1],maxSize[15],custom[onlyNumberSp]';
						
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['valor'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['valor'] = '';
						}
						$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
						$atributos ['deshabilitado'] = true;
						$atributos ['tamanno'] = 15;
						$atributos ['maximoTamanno'] = '';
						$atributos ['anchoEtiqueta'] = 160;
						$tab ++;
						
						// Aplica atributos globales al control
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroTexto ( $atributos );
						unset ( $atributos );
					// ---------------- FIN CONTROL: Cuadro de Texto  NIT--------------------------------------------------------

				echo $this->miFormulario->marcoAgrupacion ( 'fin' );			

				$esteCampo = "marcoRepresentante";
				$atributos ['id'] = $esteCampo;
				$atributos ["estilo"] = "jqueryui";
				$atributos ['tipoEtiqueta'] = 'inicio';
				$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
				echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos ); 

				
					// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
						$esteCampo = "tipoDocumento";
						$atributos ['nombre'] = $esteCampo;
						$atributos ['id'] = $esteCampo;
						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
						$atributos ["etiquetaObligatorio"] = true;
						$atributos ['tab'] = $tab ++;
						$atributos ['anchoEtiqueta'] = 200;
						$atributos ['evento'] = '';
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['seleccion'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['seleccion'] = -1;
						}
						$atributos ['deshabilitado'] = true;
						$atributos ['columnas'] = 1;
						$atributos ['tamanno'] = 1;
						$atributos ['estilo'] = "jqueryui";
						$atributos ['validar'] = "required";
						$atributos ['limitar'] = false;
						$atributos ['anchoCaja'] = 60;
						$atributos ['miEvento'] = '';
						// Valores a mostrar en el control
						$matrizItems = array (
								array ( 1, 'Cédula de Ciudadania' ),
								array ( 2, 'Cédula de Extranjería' ), 
                                                                array ( 3, 'Otro' )
						);
						$atributos ['matrizItems'] = $matrizItems;
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroLista ( $atributos );
						unset ( $atributos );
					// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
								
	
					// ---------------- CONTROL: Cuadro de Texto NIT--------------------------------------------------------
						$esteCampo = 'numeroDocumento';
						$atributos ['id'] = $esteCampo;
						$atributos ['nombre'] = $esteCampo;
						$atributos ['tipo'] = 'text';
						$atributos ['estilo'] = 'jqueryui';
						$atributos ['marco'] = true;
						$atributos ['estiloMarco'] = '';
						$atributos ["etiquetaObligatorio"] = true;
						$atributos ['columnas'] = 1;
						$atributos ['dobleLinea'] = 0;
						$atributos ['tabIndex'] = $tab;
						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
						$atributos ['validar'] = 'required, minSize[1],maxSize[15],custom[onlyNumberSp]';
						
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['valor'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['valor'] = '';
						}
						$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
						$atributos ['deshabilitado'] = true;
						$atributos ['tamanno'] = 15;
						$atributos ['maximoTamanno'] = '';
						$atributos ['anchoEtiqueta'] = 200;
						$tab ++;
						
						// Aplica atributos globales al control
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroTexto ( $atributos );
						unset ( $atributos );
					// ---------------- FIN CONTROL: Cuadro de Texto  NIT--------------------------------------------------------
		
					// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
						$esteCampo = 'primerApellido';
						$atributos ['id'] = $esteCampo;
						$atributos ['nombre'] = $esteCampo;
						$atributos ['tipo'] = 'text';
						$atributos ['estilo'] = 'jqueryui';
						$atributos ['marco'] = true;
						$atributos ['estiloMarco'] = '';
						$atributos ["etiquetaObligatorio"] = true;
						$atributos ['columnas'] = 2;
						$atributos ['dobleLinea'] = 0;
						$atributos ['tabIndex'] = $tab;
						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
						$atributos ['validar'] = 'required, minSize[1],maxSize[30]';
						
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['valor'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['valor'] = '';
						}
						$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
						$atributos ['deshabilitado'] = true;
						$atributos ['tamanno'] = 15;
						$atributos ['maximoTamanno'] = '';
						$atributos ['anchoEtiqueta'] = 200;
						$tab ++;
						
						// Aplica atributos globales al control
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroTexto ( $atributos );
						unset ( $atributos );
					// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------

					// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
						$esteCampo = 'segundoApellido';
						$atributos ['id'] = $esteCampo;
						$atributos ['nombre'] = $esteCampo;
						$atributos ['tipo'] = 'text';
						$atributos ['estilo'] = 'jqueryui';
						$atributos ['marco'] = true;
						$atributos ['estiloMarco'] = '';
						$atributos ["etiquetaObligatorio"] = true;
						$atributos ['columnas'] = 2;
						$atributos ['dobleLinea'] = 0;
						$atributos ['tabIndex'] = $tab;
						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
						$atributos ['validar'] = 'minSize[1],maxSize[30]';
						
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['valor'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['valor'] = '';
						}
						$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
						$atributos ['deshabilitado'] = true;
						$atributos ['tamanno'] = 15;
						$atributos ['maximoTamanno'] = '';
						$atributos ['anchoEtiqueta'] = 200;
						$tab ++;
						
						// Aplica atributos globales al control
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroTexto ( $atributos );
						unset ( $atributos );
					// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------

					// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
						$esteCampo = 'primerNombre';
						$atributos ['id'] = $esteCampo;
						$atributos ['nombre'] = $esteCampo;
						$atributos ['tipo'] = 'text';
						$atributos ['estilo'] = 'jqueryui';
						$atributos ['marco'] = true;
						$atributos ['estiloMarco'] = '';
						$atributos ["etiquetaObligatorio"] = true;
						$atributos ['columnas'] = 2;
						$atributos ['dobleLinea'] = 0;
						$atributos ['tabIndex'] = $tab;
						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
						$atributos ['validar'] = 'required, minSize[1],maxSize[30]';
						
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['valor'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['valor'] = '';
						}
						$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
						$atributos ['deshabilitado'] = true;
						$atributos ['tamanno'] = 15;
						$atributos ['maximoTamanno'] = '';
						$atributos ['anchoEtiqueta'] = 200;
						$tab ++;
						
						// Aplica atributos globales al control
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroTexto ( $atributos );
						unset ( $atributos );
					// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
										
					// ---------------- CONTROL: Cuadro de Texto  DIGITO DE VERIFICACION--------------------------------------------------------
						$esteCampo = 'segundoNombre';
						$atributos ['id'] = $esteCampo;
						$atributos ['nombre'] = $esteCampo;
						$atributos ['tipo'] = 'text';
						$atributos ['estilo'] = 'jqueryui';
						$atributos ['marco'] = true;
						$atributos ['estiloMarco'] = '';
						$atributos ["etiquetaObligatorio"] = true;
						$atributos ['columnas'] = 2;
						$atributos ['dobleLinea'] = 0;
						$atributos ['tabIndex'] = $tab;
						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
						$atributos ['validar'] = 'minSize[1],maxSize[30]';
						
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['valor'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['valor'] = '';
						}
						$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
						$atributos ['deshabilitado'] = true;
						$atributos ['tamanno'] = 15;
						$atributos ['maximoTamanno'] = '';
						$atributos ['anchoEtiqueta'] = 200;
						$tab ++;
						
						// Aplica atributos globales al control
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroTexto ( $atributos );
						unset ( $atributos );
					// ---------------- FIN CONTROL: Cuadro de Texto  NUMERO CONTRATO--------------------------------------------------------
															
				echo $this->miFormulario->marcoAgrupacion ( 'fin' );
				
				$esteCampo = "marcoEmpresa";
				$atributos ['id'] = $esteCampo;
				$atributos ["estilo"] = "jqueryui";
				$atributos ['tipoEtiqueta'] = 'inicio';
				$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
				echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos ); 

					// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
						$esteCampo = "productoImportacion";
						$atributos ['nombre'] = $esteCampo;
						$atributos ['id'] = $esteCampo;
						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
						$atributos ["etiquetaObligatorio"] = true;
						$atributos ['tab'] = $tab ++;
						$atributos ['anchoEtiqueta'] = 350;
						$atributos ['evento'] = '';
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['seleccion'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['seleccion'] = -1;
						}
						$atributos ['deshabilitado'] = true;
						$atributos ['columnas'] = 1;
						$atributos ['tamanno'] = 1;
						$atributos ['estilo'] = "jqueryui";
						$atributos ['validar'] = "required";
						$atributos ['limitar'] = false;
						$atributos ['anchoCaja'] = 60;
						$atributos ['miEvento'] = '';
						// Valores a mostrar en el control
						$matrizItems = array (
								array ( 1, 'Si' ),
								array ( 2, 'No' ) 
						);
						$atributos ['matrizItems'] = $matrizItems;
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroLista ( $atributos );
						unset ( $atributos );
					// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
					
					// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
						$esteCampo = "regimenContributivo";
						$atributos ['nombre'] = $esteCampo;
						$atributos ['id'] = $esteCampo;
						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
						$atributos ["etiquetaObligatorio"] = true;
						$atributos ['tab'] = $tab ++;
						$atributos ['anchoEtiqueta'] = 200;
						$atributos ['evento'] = '';
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['seleccion'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['seleccion'] = -1;
						}
						$atributos ['deshabilitado'] = true;
						$atributos ['columnas'] = 3;
						$atributos ['tamanno'] = 1;
						$atributos ['estilo'] = "jqueryui";
						$atributos ['validar'] = "required";
						$atributos ['limitar'] = false;
						$atributos ['anchoCaja'] = 60;
						$atributos ['miEvento'] = '';
						// Valores a mostrar en el control
						$matrizItems = array (
								array ( 1, 'Comun' ),
								array ( 2, 'Simplificado' ) 
						);
						$atributos ['matrizItems'] = $matrizItems;
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroLista ( $atributos );
						unset ( $atributos );
					// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
					
					// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
						$esteCampo = "pyme";
						$atributos ['nombre'] = $esteCampo;
						$atributos ['id'] = $esteCampo;
						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
						$atributos ["etiquetaObligatorio"] = true;
						$atributos ['tab'] = $tab ++;
						$atributos ['anchoEtiqueta'] = 200;
						$atributos ['evento'] = '';
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['seleccion'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['seleccion'] = -1;
						}
						$atributos ['deshabilitado'] = true;
						$atributos ['columnas'] = 3;
						$atributos ['tamanno'] = 1;
						$atributos ['estilo'] = "jqueryui";
						$atributos ['validar'] = "required";
						$atributos ['limitar'] = false;
						$atributos ['anchoCaja'] = 60;
						$atributos ['miEvento'] = '';
						// Valores a mostrar en el control
						$matrizItems = array (
								array ( 1, 'Si' ),
								array ( 2, 'No' ) 
						);
						$atributos ['matrizItems'] = $matrizItems;
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroLista ( $atributos );
						unset ( $atributos );
					// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
				
					// ---------------- CONTROL: Lista TIPO DE PERSONA --------------------------------------------------------
						$esteCampo = "registroMercantil";
						$atributos ['nombre'] = $esteCampo;
						$atributos ['id'] = $esteCampo;
						$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
						$atributos ["etiquetaObligatorio"] = true;
						$atributos ['tab'] = $tab ++;
						$atributos ['anchoEtiqueta'] = 200;
						$atributos ['evento'] = '';
						if (isset ( $_REQUEST [$esteCampo] )) {
							$atributos ['seleccion'] = $_REQUEST [$esteCampo];
						} else {
							$atributos ['seleccion'] = -1;
						}
						$atributos ['deshabilitado'] = true;
						$atributos ['columnas'] = 3;
						$atributos ['tamanno'] = 1;
						$atributos ['estilo'] = "jqueryui";
						$atributos ['validar'] = "required";
						$atributos ['limitar'] = false;
						$atributos ['anchoCaja'] = 60;
						$atributos ['miEvento'] = '';
						// Valores a mostrar en el control
						$matrizItems = array (
								array ( 1, 'Si' ),
								array ( 2, 'No' ) 
						);
						$atributos ['matrizItems'] = $matrizItems;
						$atributos = array_merge ( $atributos, $atributosGlobales );
						echo $this->miFormulario->campoCuadroLista ( $atributos );
						unset ( $atributos );
					// ----------------FIN CONTROL: Lista TIPO DE PERSONA--------------------------------------------------------
				echo $this->miFormulario->marcoAgrupacion ( 'fin' );

				$esteCampo = "marcoRUT";
				$atributos ['id'] = $esteCampo;
				$atributos ["estilo"] = "jqueryui";
				$atributos ['tipoEtiqueta'] = 'inicio';
				$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
				echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );					
// ----------------INICIO CONTROL: DOCUMENTO--------------------------------------------------------					
		$esteCampo = "DocumentoRUT";
		$atributos ["id"] = $esteCampo; // No cambiar este nombre
		$atributos ["nombre"] = $esteCampo;
		$atributos ["tipo"] = "file";
		// $atributos ["obligatorio"] = true;
		$atributos ["etiquetaObligatorio"] = true;
		$atributos ["tabIndex"] = $tab ++;
		$atributos ["columnas"] = 2;
		$atributos ["estilo"] = "textoIzquierda";
		$atributos ["anchoEtiqueta"] = 400;
		$atributos ["tamanno"] = 500000;
		$atributos ["validar"] = "";
		$atributos ["etiqueta"] = $this->lenguaje->getCadena ( $esteCampo );
		// $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
		// $atributos ["valor"] = $valorCodificado;
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoCuadroTexto ( $atributos );
		unset ( $atributos );								
// ----------------FIN CONTROL: DOCUMENTO--------------------------------------------------------

//INICIO enlace boton descargar RUT	
		//------------------Division para los botones-------------------------
		$atributos["id"]="botones";
		$atributos["estilo"]="marcoBotones";
		echo $this->miFormulario->division("inicio",$atributos);
		
		$enlace = "<br><a href='".$_REQUEST['DocumentoRUT']."' target='_blank'>";
		$enlace.="<img src='".$rutaBloque."/images/pdf.png' width='35px'><br>Registro Único Tributario ";
		$enlace.="</a>";       
		echo $enlace;
		//------------------Fin Division para los botones-------------------------
		echo $this->miFormulario->division("fin"); 		
//FIN enlace boton descargar RUT 					
echo $this->miFormulario->marcoAgrupacion ( 'fin' );
				
				// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
				$esteCampo = 'descripcion';
				$atributos ['id'] = $esteCampo;
				$atributos ['nombre'] = $esteCampo;
				$atributos ['tipo'] = 'text';
				$atributos ['estilo'] = 'jqueryui';
				$atributos ['marco'] = true;
				$atributos ['estiloMarco'] = '';
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['columnas'] = 90;
				$atributos ['filas'] = 2;
				$atributos ['dobleLinea'] = 0;
				$atributos ['tabIndex'] = $tab;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ['validar'] = 'required,maxSize[250]';
				$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
				$atributos ['deshabilitado'] = true;
				$atributos ['tamanno'] = 20;
				$atributos ['maximoTamanno'] = '';
				$atributos ['anchoEtiqueta'] = 220;
				if (isset ( $_REQUEST [$esteCampo] )) {
					$atributos ['valor'] = $_REQUEST [$esteCampo];
				} else {
					$atributos ['valor'] = '';
				}
				$tab ++;
				
				// Aplica atributos globales al control
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoTextArea ( $atributos );
				unset ( $atributos );
                                
				// ---------------- FIN CONTROL: Cuadro de Texto --------------------------------------------------------								
		$esteCampo = 'idProveedor';
		$atributos ["id"] = $esteCampo; // No cambiar este nombre
		$atributos ["tipo"] = "hidden";
		$atributos ['estilo'] = '';
		$atributos ["obligatorio"] = false;
		$atributos ['marco'] = true;
		$atributos ["etiqueta"] = "";
		if (isset ( $_REQUEST [$esteCampo] )) {
			$atributos ['valor'] = $_REQUEST [$esteCampo];
		} else {
			$atributos ['valor'] = '';
		}
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoCuadroTexto ( $atributos );
		unset ( $atributos );                                
       				
				// ------------------Division para los botones-------------------------
				$atributos ["id"] = "botones";
				$atributos ["estilo"] = "marcoBotones";
				echo $this->miFormulario->division ( "inicio", $atributos );
				unset ( $atributos );
				{
					// -----------------CONTROL: Botón ----------------------------------------------------------------
					$esteCampo = 'botonGuardar';
					$atributos ["id"] = $esteCampo;
					$atributos ["tabIndex"] = $tab;
					$atributos ["tipo"] = 'boton';
					// submit: no se coloca si se desea un tipo button genérico
					$atributos ['submit'] = true;
					$atributos ["estiloMarco"] = '';
					$atributos ["estiloBoton"] = 'jqueryui';
					// verificar: true para verificar el formulario antes de pasarlo al servidor.
					$atributos ["verificar"] = true;
					$atributos ["tipoSubmit"] = 'jquery'; // Dejar vacio para un submit normal, en este caso se ejecuta la función submit declarada en ready.js
					$atributos ["valor"] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ['nombreFormulario'] = $esteBloque ['nombre'];
					$tab ++;
					
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoBoton ( $atributos );
					// -----------------FIN CONTROL: Botón -----------------------------------------------------------
				}
				echo $this->miFormulario->division ( 'fin' );
				
				echo $this->miFormulario->marcoAgrupacion ( 'fin' );
				
				// ---------------- FIN SECCION: Controles del Formulario -------------------------------------------
				// ----------------FINALIZAR EL FORMULARIO ----------------------------------------------------------
				// Se debe declarar el mismo atributo de marco con que se inició el formulario.
			}
			
			// -----------------FIN CONTROL: Botón -----------------------------------------------------------
			// ------------------Fin Division para los botones-------------------------
			echo $this->miFormulario->division ( "fin" );
			
			
			
			
			
			
			
			*/
			
			
			// ------------------- SECCION: Paso de variables ------------------------------------------------
			
			/**
			 * En algunas ocasiones es útil pasar variables entre las diferentes páginas.
			 * SARA permite realizar esto a través de tres
			 * mecanismos:
			 * (a). Registrando las variables como variables de sesión. Estarán disponibles durante toda la sesión de usuario. Requiere acceso a
			 * la base de datos.
			 * (b). Incluirlas de manera codificada como campos de los formularios. Para ello se utiliza un campo especial denominado
			 * formsara, cuyo valor será una cadena codificada que contiene las variables.
			 * (c) a través de campos ocultos en los formularios. (deprecated)
			 */
			// En este formulario se utiliza el mecanismo (b) para pasar las siguientes variables:
			
			$valorCodificado = "action=" . $esteBloque ["nombre"];
			$valorCodificado .= "&pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
			$valorCodificado .= "&bloque=" . $esteBloque ['nombre'];
			$valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
			$valorCodificado .= "&opcion=actualizar";

			/**
			 * SARA permite que los nombres de los campos sean dinámicos.
			 * Para ello utiliza la hora en que es creado el formulario para
			 * codificar el nombre de cada campo. Si se utiliza esta técnica es necesario pasar dicho tiempo como una variable:
			 * (a) invocando a la variable $_REQUEST ['tiempo'] que se ha declarado en ready.php o
			 * (b) asociando el tiempo en que se está creando el formulario
			 */
			$valorCodificado .= "&campoSeguro=" . $_REQUEST ['tiempo'];
			$valorCodificado .= "&tiempo=" . time ();
			// Paso 2: codificar la cadena resultante
			$valorCodificado = $this->miConfigurador->fabricaConexiones->crypto->codificar ( $valorCodificado );
			
			$atributos ["id"] = "formSaraData"; // No cambiar este nombre
			$atributos ["tipo"] = "hidden";
			$atributos ['estilo'] = '';
			$atributos ["obligatorio"] = false;
			$atributos ['marco'] = true;
			$atributos ["etiqueta"] = "";
			$atributos ["valor"] = $valorCodificado;
			echo $this->miFormulario->campoCuadroTexto ( $atributos );
			unset ( $atributos );
			
			$atributos ['marco'] = true;
			$atributos ['tipoEtiqueta'] = 'fin';
			echo $this->miFormulario->formulario ( $atributos );
			
			return true;
		}
	}
	function mensaje() {
		
		// Si existe algun tipo de error en el login aparece el siguiente mensaje
		$mensaje = $this->miConfigurador->getVariableConfiguracion ( 'mostrarMensaje' );
		$this->miConfigurador->setVariableConfiguracion ( 'mostrarMensaje', null );
		
		if ($mensaje) {
			
			$tipoMensaje = $this->miConfigurador->getVariableConfiguracion ( 'tipoMensaje' );
			
			if ($tipoMensaje == 'json') {
				
				$atributos ['mensaje'] = $mensaje;
				$atributos ['json'] = true;
			} else {
				$atributos ['mensaje'] = $this->lenguaje->getCadena ( $mensaje );
			}
			// -------------Control texto-----------------------
			$esteCampo = 'divMensaje';
			$atributos ['id'] = $esteCampo;
			$atributos ["tamanno"] = '';
			$atributos ["estilo"] = 'information';
			$atributos ["etiqueta"] = '';
			$atributos ["columnas"] = ''; // El control ocupa 47% del tamaño del formulario
			echo $this->miFormulario->campoMensaje ( $atributos );
			unset ( $atributos );
		}
		
		return true;
	}
}

$miFormulario = new Formulario ( $this->lenguaje, $this->miFormulario, $this->sql );

$miFormulario->formulario ();
$miFormulario->mensaje ();
?>