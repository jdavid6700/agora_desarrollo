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
		
		$rutaBloque = $this->miConfigurador->getVariableConfiguracion ( "raizDocumento" ) . "/blocks/administracion/";
		$rutaBloque .= $esteBloque ['nombre'];
		$host = $this->miConfigurador->getVariableConfiguracion ( "host" ) . $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/administracion/" . $esteBloque ['nombre'];
		
		$hostFiles = $this->miConfigurador->getVariableConfiguracion ( "host" ) . $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/proveedor/registroProveedor";
		

		unset($resultado);
		
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
				$CambioARCHIVO = true;
				// guardamos el archivo a la carpeta files
				$destino = "/usr/local/apache/htdocs/agora/blocks/proveedor/registroProveedor/files/" . $nombreDoc;
		
				if (copy ( $archivo ['tmp_name'], $destino )) {
					$status = "Archivo subido: <b>" . $archivo1 . "</b>";
					$_REQUEST ['destino'] = $hostFiles . "/files/" . $prefijo . "-" . $archivo1;
					
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
				case 6 :
					$_REQUEST ['perfil'] = 38;
					break;
				case 7 :
					$_REQUEST ['perfil'] = 39;
					break;
			}
		}
		
		
		$fechaActualCambio = date ( 'Y-m-d' . ' - ' .'h:i:s A');
		
		
		$datosInformacionProveedorPersonaNatural = array (
				'id_Proveedor' => $_REQUEST['id_Proveedor'],
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
				'descripcion_proveedor' => $_REQUEST['descripcion'],
				'fecha_modificación' => $fechaActualCambio,
		);
		
		$cadenaSql = $this->miSql->getCadenaSql ( "actualizarInformacionProveedor", $datosInformacionProveedorPersonaNatural );
		$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso' );
		
		
		
		$datosTelefonoFijoPersonaProveedor = array (
				'id_telefono' => $_REQUEST['id_Telefono'],
				'num_telefono' => $_REQUEST['telefono'],
				'extension_telefono' => $_REQUEST['extension'],
				'tipo' => '1'
		);
		
		
		$cadenaSql = $this->miSql->getCadenaSql("actualizarInformacionProveedorTelefono",$datosTelefonoFijoPersonaProveedor);
		$id_TelefonoFijo = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
		
		
		
		$datosInformacionPersonaNatural = array (
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
				'monto_capital_autorizado' => null,
				'grupoEtnico' => null,
				'comunidadLGBT' => 'FALSE',
				'cabezaFamilia' => 'FALSE',
				'personasCargo' => 'FALSE',
				'numeroPersonasCargo' => null,
				'estadoCivil' => 'SOLTERO',
				'discapacidad' => 'FALSE',
				'tipoDiscapacidad' => null,
				'declarante_renta' => 'FALSE',//AGREGADO Beneficios Tributarios *****************
				'medicina_prepagada' => 'FALSE',
				'valor_uvt_prepagada' => null,
				'cuenta_ahorro_afc' => 'FALSE',
				'num_cuenta_bancaria_afc' => null,
				'id_entidad_bancaria_afc' => null,
				'interes_vivienda_afc' => null,
				'dependiente_hijo_menor_edad' => 'FALSE',
				'dependiente_hijo_menos23_estudiando' => 'FALSE',
				'dependiente_hijo_mas23_discapacitado' => 'FALSE',
				'dependiente_conyuge' => 'FALSE',
				'dependiente_padre_o_hermano' => 'FALSE'
		);
		
		
		//Guardar datos PROVEEDOR REPRESENTANTE
		$cadenaSql = $this->miSql->getCadenaSql ( "actualizarProveedorNatural", $datosInformacionPersonaNatural );
		$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso' );
		
		$datosProveedorXRepre = array (
				'fki_id_Proveedor' => $_REQUEST['id_Proveedor'],
				'fki_id_Representante' => $_REQUEST['numeroDocumento'],
				'correo_Repre' => $_REQUEST['correoPer'],
				'tel_Repre' => $_REQUEST['numeroContacto'],
		);
		
		$cadenaSql = $this->miSql->getCadenaSql("actualizarInformacionProveedorXRepresentante",$datosProveedorXRepre);
		$resultado = $esteRecursoDB->ejecutarAcceso($cadenaSql, "acceso");
		
		
		if(isset($_REQUEST['paisEmpresa'])){//CAST
			switch($_REQUEST['paisEmpresa']){
				case 1 :
					$_REQUEST['paisEmpresa']='NACIONAL';
					$_REQUEST['personaJuridicaCiudad'] = null;
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
			
		//Actualizar datos PROVEEDOR JURIDICA
		$cadenaSql = $this->miSql->getCadenaSql ( "actualizarProveedorJuridica", $datosInformacionPersonaJuridica );
		$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso' );

		

				if ($resultado) {
					redireccion::redireccionar ( 'actualizo', $_REQUEST['nit'] );
					exit();
				} else {
					redireccion::redireccionar ( 'noActualizo',  $_REQUEST['nit']);
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
