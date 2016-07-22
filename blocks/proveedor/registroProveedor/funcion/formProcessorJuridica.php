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
		$cadenaSql = $this->miSql->getCadenaSql ( "verificarProveedor", $_REQUEST ['nit']);
		$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'busqueda' );

		if ($resultado) {
			//El proveedor ya existe
			redireccion::redireccionar ( 'existeProveedor',  $_REQUEST ['nit']);
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
				
				if(isset($_REQUEST['tipoCuenta'])){//CAST
					switch($_REQUEST['tipoCuenta']){
						case 1 :
							$_REQUEST['tipoCuenta']='AHORROS';
							break;
						case 2 :
							$_REQUEST['tipoCuenta']='CORRIENTE';
							break;
					}
				}
				
				if(isset($_REQUEST['genero'])){//CAST
					switch($_REQUEST['genero']){
						case 1 :
							$_REQUEST['genero']='MASCULINO';
							break;
						case 2 :
							$_REQUEST['genero']='FEMENINO';
							break;
					}
				}
				
				
				if(isset($_REQUEST['perfil'])){//CAST
					switch($_REQUEST['perfil']){
						case 1 :
							$_REQUEST ['perfil'] = 18;
							break;
						case 2 :
							$_REQUEST ['perfil'] = 19;
							break;
						case 3 :
							$_REQUEST ['perfil'] = 20;
							break;
						case 4 :
							$_REQUEST ['perfil'] = 21;
							break;
						case 5 :
							$_REQUEST ['perfil'] = 22;
							break;
					}
				}	
				
				$fechaActual = date ( 'Y-m-d' . ' - ' .'h:i:s A');
				
				$datosInformacionProveedorPersonaJuridica = array (
						'tipoPersona' => $_REQUEST['tipoPersona'],
						'numero_documento' => $_REQUEST['nit'],
						'nombre_proveedor' => $_REQUEST['nombreEmpresa'],
						'id_ciudad_contacto' =>	$_REQUEST['ciudad'],
						'direccion_contacto' => $_REQUEST['direccion'],
						'correo_contacto' => $_REQUEST['correo'],
						'web_contacto' => $_REQUEST['sitioWeb'],
						'nom_asesor_comercial_contacto' => $_REQUEST['asesorComercial'],
						'tel_asesor_comercial_contacto' => $_REQUEST['telAsesor'],
						'tipo_cuenta_bancaria' => $_REQUEST['tipoCuenta'],
						'num_cuenta_bancaria' => $_REQUEST['numeroCuenta'],
						'id_entidad_bancaria' => $_REQUEST['entidadBancaria'],
						'anexo_rut' => $_REQUEST ['destino'],
						'descripcion_proveedor' => $_REQUEST['descripcion'],
						'fecha_registro' => $fechaActual,
						'fecha_modificación' => $fechaActual,
						'id_estado' => '2' //Estado Inactivo
				);
			
				//Guardar datos PROVEEDOR
				$cadenaSql = $this->miSql->getCadenaSql("insertarInformacionProveedor",$datosInformacionProveedorPersonaJuridica);
				$id_proveedor = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda", $datosInformacionProveedorPersonaJuridica, "insertarInformacionProveedor");
				
				$datosTelefonoFijoPersonaProveedor = array (
						'num_telefono' => $_REQUEST['telefono'],
						'extension_telefono' => $_REQUEST['extension'],
						'tipo' => '1'
				);
				
				
				$cadenaSql = $this->miSql->getCadenaSql("insertarInformacionProveedorTelefono",$datosTelefonoFijoPersonaProveedor);
				$id_TelefonoFijo = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda", $datosTelefonoFijoPersonaProveedor, "insertarInformacionProveedorTelefono");
				
				/*$datosTelefonoMovilPersonaProveedor = array (
						'num_telefono' => $_REQUEST['movil'],
						'extension_telefono' => null,
						'tipo' => '2'
				);
				
				$cadenaSql = $this->miSql->getCadenaSql("insertarInformacionProveedorTelefono",$datosTelefonoMovilPersonaProveedor);
				$id_TelefonoMovil = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda", $datosTelefonoMovilPersonaProveedor, "insertarInformacionProveedorTelefono");
				*/
				$datosTelefonoProveedorTipoA = array (
						'fki_id_tel' => $id_TelefonoFijo[0][0],
						'fki_id_Proveedor' => $id_proveedor[0][0]
				);
				
				$cadenaSql = $this->miSql->getCadenaSql("insertarInformacionProveedorXTelefono",$datosTelefonoProveedorTipoA);
				$resultado = $esteRecursoDB->ejecutarAcceso($cadenaSql, "acceso");
				/*
				$datosTelefonoProveedorTipoB = array (
						'fki_id_tel' => $id_TelefonoMovil[0][0],
						'fki_id_Proveedor' => $id_proveedor[0][0]
				);
				
				$cadenaSql = $this->miSql->getCadenaSql("insertarInformacionProveedorXTelefono",$datosTelefonoProveedorTipoB);
				$resultado = $esteRecursoDB->ejecutarAcceso($cadenaSql, "acceso");
				*/
				
				
				
				
				$datosInformacionPersonaNaturalRepresentante = array (
						'id_tipo_documento' =>	$_REQUEST['tipoDocumento'],
						'fki_numero_documento' => $_REQUEST['numeroDocumento'],
						'digito_verificacion' => $_REQUEST['digitoRepre'],
						'primer_apellido' => $_REQUEST['primerApellido'],
						'segundo_apellido' => $_REQUEST['segundoApellido'],
						'primer_nombre' => $_REQUEST['primerNombre'],
						'segundo_nombre' => $_REQUEST['segundoNombre'],
						'genero' => $_REQUEST['genero'],
						'cargo' => $_REQUEST['cargo'],
						'id_pais_nacimiento' => $_REQUEST['paisNacimiento'],
						'id_perfil' => $_REQUEST['perfil'],
						'profesion' => $_REQUEST['profesion'],
						'especialidad' => $_REQUEST['especialidad'],
						'monto_capital_autorizado' => null
				);
				
				
				//Guardar datos PROVEEDOR NATURAL
				$cadenaSql = $this->miSql->getCadenaSql ( "registrarProveedorNatural", $datosInformacionPersonaNaturalRepresentante );
				$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso' );
				
				
				
				
				 $datosProveedorXRepre = array (
				 		'fki_id_Proveedor' => $id_proveedor[0][0],
				 		'fki_id_Representante' => $_REQUEST['numeroDocumento'],
				 		'correo_Repre' => $_REQUEST['correoPer'],
				 		'tel_Repre' => $_REQUEST['numeroContacto'],
				 );
				
				 $cadenaSql = $this->miSql->getCadenaSql("insertarInformacionProveedorXRepresentante",$datosProveedorXRepre);
				 $resultado = $esteRecursoDB->ejecutarAcceso($cadenaSql, "acceso");
				 
				
				 
				 if(isset($_REQUEST['paisEmpresa'])){//CAST
				 	switch($_REQUEST['paisEmpresa']){
				 		case 1 :
				 			$_REQUEST['paisEmpresa']='NACIONAL';
				 			break;
				 		case 2 :
				 			$_REQUEST['paisEmpresa']='EXTRANJERO';
				 			break;
				 	}
				 }
				 
				 if(isset($_REQUEST['tipoIdentifiExtranjera'])){//CAST
				 	switch($_REQUEST['tipoIdentifiExtranjera']){
				 		case 1 :
				 			$_REQUEST['tipoIdentifiExtranjera']='CEDULA DE EXTRANJERIA';
				 			break;
				 		case 2 :
				 			$_REQUEST['tipoIdentifiExtranjera']='PASAPORTE';
				 			break;
				 	}
				 }
				 
				 if(isset($_REQUEST['regimenContributivo'])){//CAST
				 	switch($_REQUEST['regimenContributivo']){
				 		case 1 :
				 			$_REQUEST['regimenContributivo']='COMUN';
				 			break;
				 		case 2 :
				 			$_REQUEST['regimenContributivo']='SIMPLIFICADO';
				 			break;
				 	}
				 }
				 
				 
				 
				 // 7 campos TRUE FALSE
				 
				 if(isset($_REQUEST['productoImportacion'])){
				 	switch($_REQUEST ['productoImportacion']){
				 		case 1 :
				 			$_REQUEST ['productoImportacion']='TRUE';
				 			break;
				 		case 2 :
				 			$_REQUEST ['productoImportacion']='FALSE';
				 			break;
				 		default:
				 			$_REQUEST ['productoImportacion']='NULL';
				 			break;
				 	}
				 }
				 
				 if(isset($_REQUEST['pyme'])){
				 	switch($_REQUEST ['pyme']){
				 		case 1 :
				 			$_REQUEST ['pyme']='TRUE';
				 			break;
				 		case 2 :
				 			$_REQUEST ['pyme']='FALSE';
				 			break;
				 		default:
				 			$_REQUEST ['pyme']='NULL';
				 			break;
				 	}
				 }
				 
				 if(isset($_REQUEST['registroMercantil'])){
				 	switch($_REQUEST ['registroMercantil']){
				 		case 1 :
				 			$_REQUEST ['registroMercantil']='TRUE';
				 			break;
				 		case 2 :
				 			$_REQUEST ['registroMercantil']='FALSE';
				 			break;
				 		default:
				 			$_REQUEST ['registroMercantil']='NULL';
				 			break;
				 	}
				 }
				 
				 if(isset($_REQUEST['sujetoDeRetencion'])){
				 	switch($_REQUEST ['sujetoDeRetencion']){
				 		case 1 :
				 			$_REQUEST ['sujetoDeRetencion']='TRUE';
				 			break;
				 		case 2 :
				 			$_REQUEST ['sujetoDeRetencion']='FALSE';
				 			break;
				 		default:
				 			$_REQUEST ['sujetoDeRetencion']='NULL';
				 			break;
				 	}
				 }
				 
				 
				 if(isset($_REQUEST['agenteRetenedor'])){
				 	switch($_REQUEST ['agenteRetenedor']){
				 		case 1 :
				 			$_REQUEST ['agenteRetenedor']='TRUE';
				 			break;
				 		case 2 :
				 			$_REQUEST ['agenteRetenedor']='FALSE';
				 			break;
				 		default:
				 			$_REQUEST ['agenteRetenedor']='NULL';
				 			break;
				 	}
				 }
				 
				 if(isset($_REQUEST['responsableICA'])){
				 	switch($_REQUEST ['responsableICA']){
				 		case 1 :
				 			$_REQUEST ['responsableICA']='TRUE';
				 			break;
				 		case 2 :
				 			$_REQUEST ['responsableICA']='FALSE';
				 			break;
				 		default:
				 			$_REQUEST ['responsableICA']='NULL';
				 			break;
				 	}
				 }
				 
				 
				 if(isset($_REQUEST['responsableIVA'])){
				 	switch($_REQUEST ['responsableIVA']){
				 		case 1 :
				 			$_REQUEST ['responsableIVA']='TRUE';
				 			break;
				 		case 2 :
				 			$_REQUEST ['responsableIVA']='FALSE';
				 			break;
				 		default:
				 			$_REQUEST ['responsableIVA']='NULL';
				 			break;
				 	}
				 }
				 
				
				 $datosInformacionPersonaJuridica = array (
				 		'fki_numero_documento' => $_REQUEST['nit'],
				 		'digito_verificacion' => $_REQUEST['digito'],
				 		'procedencia_empresa' => $_REQUEST['paisEmpresa'],
				 		'id_ciudad_origen' => $_REQUEST['personaJuridicaCiudad'], 
				 		'codigo_pais_dian' => $_REQUEST['codigoPais'], 
				 		'codigo_postal' => $_REQUEST['codigoPostal'], 
				 		'tipo_identificacion_extranjera' => $_REQUEST['tipoIdentifiExtranjera'],
				 		'num_cedula_extranjeria' => $_REQUEST['cedulaExtranjeria'],
				 		'num_pasaporte' => $_REQUEST['pasaporte'],
				 		'id_tipo_conformacion' => $_REQUEST['tipoConformacion'],
				 		'monto_capital_autorizado' => $_REQUEST['monto'],
				 		'genero' => 'NO APLICA',
				 		'nom_proveedor' => $_REQUEST['nombreEmpresa'],
				 		'regimen_contributivo' => $_REQUEST['regimenContributivo'],
				 		'exclusividad_producto' => $_REQUEST ['productoImportacion'],
				 		'pyme' => $_REQUEST ['pyme'],
				 		'registro_mercantil' => $_REQUEST ['registroMercantil'],
				 		'sujeto_retencion' => $_REQUEST ['sujetoDeRetencion'],
				 		'agente_retenedor' => $_REQUEST ['agenteRetenedor'],
				 		'responsable_ICA' => $_REQUEST ['responsableICA'],
				 		'responsable_IVA' => $_REQUEST ['responsableIVA']
				 );
				 
				 //Guardar datos PROVEEDOR JURIDICA
				 $cadenaSql = $this->miSql->getCadenaSql ( "registrarProveedorJuridica", $datosInformacionPersonaJuridica );
				 $resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso' );

				
				if ($resultado) {
						//Insertar datos en la tabla USUARIO
						$_REQUEST ["contrasena"]= $this->miConfigurador->fabricaConexiones->crypto->codificarClave($_REQUEST ['nit'] );
						$_REQUEST ["tipo"] = 2;//usuario Normal
						$_REQUEST ["rolMenu"] = 9;//MENU usuario proveedor
						$_REQUEST ["estado"] = 2;//Para solicitar cambio de contraseña
						$_REQUEST ["nombre"] = $_REQUEST['nombreEmpresa'];
						$_REQUEST ["apellido"] = 'EMPRESA';
								
								//FALTA EL CAMPO DEL MENU
		
						
						$datosRegistroUsuario = array (
								'num_documento' => $_REQUEST ['nit'],
								'contrasena' => $_REQUEST ["contrasena"],
								'tipo' => $_REQUEST ["tipo"],
								'rolMenu' => $_REQUEST ["rolMenu"],
								'estado' => $_REQUEST ["estado"],
								'nombre' => $_REQUEST ["nombre"],
								'apellido' => $_REQUEST ["apellido"],
								'correo' => $_REQUEST['correo'],
								'telefono' => $_REQUEST['telefono']
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
