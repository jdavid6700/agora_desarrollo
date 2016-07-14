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
		

		unset($resultado);
			
			// Guardar el archivo
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
					// Actualizar RUT
					$cadenaSql = $this->miSql->getCadenaSql ( "actualizarRUT", $_REQUEST );
					$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso' );
				} else {
					$status = "<br>Error al subir el archivo1";
				}
			} else {
				$status = "<br>Error al subir archivo2";
			}
		} else {
			echo "<br>NO existe el archivo D:!!!";
		}		
		
		
		
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
						'cargo' => $_REQUEST['cargoNat'],
						'id_pais_nacimiento' => $_REQUEST['paisNacimientoNat'],
						'id_perfil' => $_REQUEST['perfilNat'],
						'profesion' => $_REQUEST['profesionNat'],
						'especialidad' => $_REQUEST['especialidadNat'],
						'monto_capital_autorizado' => $_REQUEST['montoNat']
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
