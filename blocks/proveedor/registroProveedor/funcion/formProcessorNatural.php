<?php

namespace hojaDeVida\crearDocente\funcion;

use hojaDeVida\crearDocente\funcion\redireccionar;

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
		$conexion = "estructura";
		$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
		
		$esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );
		
		$rutaBloque = $this->miConfigurador->getVariableConfiguracion ( "raizDocumento" ) . "/blocks/proveedor/";
		$rutaBloque .= $esteBloque ['nombre'];
		$host = $this->miConfigurador->getVariableConfiguracion ( "host" ) . $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/proveedor/" . $esteBloque ['nombre'];

		
		
		//Guardar RUT adjuntado Persona Natural
		$_REQUEST ['destino'] = '';
		
		if ($_FILES) {
			foreach ( $_FILES as $key => $values ) {
				$archivo = $_FILES [$key];
			}
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



		unset($resultado);
		//VERIFICAR SI LA CEDULA YA SE ENCUENTRA REGISTRADA
		$cadenaSql = $this->miSql->getCadenaSql ( "verificarProveedor", $_REQUEST ['documentoNat']);
		$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'busqueda' );
		
		
		
		if ($resultado) {
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
						'descripcion_proveedor' => $_REQUEST['descripcionNat'],
						'fecha_registro' => $fechaActual,
						'fecha_modificación' => $fechaActual,
						'id_estado' => '2' //Estado Inactivo
				);
				
				//Guardar datos PROVEEDOR
				$cadenaSql = $this->miSql->getCadenaSql("insertarInformacionProveedor",$datosInformacionProveedorPersonaNatural);
				$id_proveedor = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda", $datosInformacionProveedorPersonaNatural, "insertarInformacionProveedor");
				
				
				$datosTelefonoFijoPersonaProveedor = array (
						'num_telefono' => $_REQUEST['telefonoNat'],
						'extension_telefono' => $_REQUEST['extensionNat'],
						'tipo' => '1'
				);
				
				
				$cadenaSql = $this->miSql->getCadenaSql("insertarInformacionProveedorTelefono",$datosTelefonoFijoPersonaProveedor);
				$id_TelefonoFijo = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda", $datosTelefonoFijoPersonaProveedor, "insertarInformacionProveedorTelefono");
				
				$datosTelefonoMovilPersonaProveedor = array (
						'num_telefono' => $_REQUEST['movilNat'],
						'extension_telefono' => null,
						'tipo' => '2'
				);
				
				$cadenaSql = $this->miSql->getCadenaSql("insertarInformacionProveedorTelefono",$datosTelefonoMovilPersonaProveedor);
				$id_TelefonoMovil = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda", $datosTelefonoMovilPersonaProveedor, "insertarInformacionProveedorTelefono");
				
				$datosTelefonoProveedorTipoA = array (
						'fki_id_tel' => $id_TelefonoFijo[0][0],
						'fki_id_Proveedor' => $id_proveedor[0][0]
				);
				
				$cadenaSql = $this->miSql->getCadenaSql("insertarInformacionProveedorXTelefono",$datosTelefonoProveedorTipoA);
				$resultado = $esteRecursoDB->ejecutarAcceso($cadenaSql, "acceso");
				
				$datosTelefonoProveedorTipoB = array (
						'fki_id_tel' => $id_TelefonoMovil[0][0],
						'fki_id_Proveedor' => $id_proveedor[0][0]
				);
				
				$cadenaSql = $this->miSql->getCadenaSql("insertarInformacionProveedorXTelefono",$datosTelefonoProveedorTipoB);
				$resultado = $esteRecursoDB->ejecutarAcceso($cadenaSql, "acceso");
				
				
				$datosInformacionPersonaNatural = array (
					    'id_tipo_documento' =>	$_REQUEST['tipoDocumentoNat'],
						'fki_numero_documento' => $_REQUEST['documentoNat'],
						'digito_verificacion' => $_REQUEST['digitoNat'],
						'primer_apellido' => $_REQUEST['primerApellidoNat'],
						'segundo_apellido' => $_REQUEST['segundoApellidoNat'],
						'primer_nombre' => $_REQUEST['primerNombreNat'],
						'segundo_nombre' => $_REQUEST['segundoNombreNat'],
						'genero' => $_REQUEST['generoNat'],
						'cargo' => $_REQUEST['cargoNat'],
						'id_pais_nacimiento' => $_REQUEST['paisNacimientoNat'],
						'id_perfil' => $_REQUEST['perfilNat'],
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
						'tipoDiscapacidad' => $_REQUEST['tipoDiscapacidad']
				);
				
				
				//Guardar datos PROVEEDOR NATURAL
				$cadenaSql = $this->miSql->getCadenaSql ( "registrarProveedorNatural", $datosInformacionPersonaNatural );
				$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso' );
				
				if ($resultado) {
						//Insertar datos en la tabla USUARIO
						$_REQUEST ["contrasena"]= $this->miConfigurador->fabricaConexiones->crypto->codificarClave($_REQUEST ['documentoNat'] );
						$_REQUEST ["tipo"] = 2;//usuario Normal
						$_REQUEST ["rolMenu"] = 9;//MENU usuario proveedor
						$_REQUEST ["estado"] = 2;//Para solicitar cambio de contraseña
						$_REQUEST ["nombre"] = $_REQUEST ["primerNombreNat"] . ' ' . $_REQUEST ["segundoNombreNat"];
						$_REQUEST ["apellido"] = $_REQUEST ["primerApellidoNat"] . ' ' . $_REQUEST ["segundoApellidoNat"];;
								
								//FALTA EL CAMPO DEL MENU
								
						$datosRegistroUsuario = array (
								'num_documento' => $_REQUEST ['documentoNat'],
								'contrasena' => $_REQUEST ["contrasena"],
								'tipo' => $_REQUEST ["tipo"],
								'rolMenu' => $_REQUEST ["rolMenu"],
								'estado' => $_REQUEST ["estado"],
								'nombre' => $_REQUEST ["nombre"],
								'apellido' => $_REQUEST ["apellido"],
								'correo' => $_REQUEST['correoNat'],
								'telefono' => $_REQUEST['telefonoNat']
						);
		
						$cadenaSql = $this->miSql->getCadenaSql ( "registrarUsuario", $datosRegistroUsuario );
						$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso'); 
		
								redireccion::redireccionar ( 'registroProveedor',  $datosRegistroUsuario);
								exit();
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

$miRegistrador = new Formulario ( $this->lenguaje, $this->sql, $this->funcion );

$resultado = $miRegistrador->procesarFormulario ();

?>
