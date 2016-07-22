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
						'monto_capital_autorizado' => $_REQUEST['montoNat'] 
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
