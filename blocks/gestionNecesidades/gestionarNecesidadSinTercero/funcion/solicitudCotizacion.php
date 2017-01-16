<?php

namespace hojaDeVida\crearDocente\funcion;

use hojaDeVida\crearDocente\funcion\redireccionar;

include_once ('redireccionar.php');
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}
class SolicitudCotizacion {
	
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
		
		$conexion = "agora";
		$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
		
		$esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );
		
		$rutaBloque = $this->miConfigurador->getVariableConfiguracion ( "raizDocumento" ) . "/blocks/asignacionPuntajes/salariales/";
		$rutaBloque .= $esteBloque ['nombre'];
		$host = $this->miConfigurador->getVariableConfiguracion ( "host" ) . $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/asignacionPuntajes/salariales/" . $esteBloque ['nombre'];
		
		$proveedores = unserialize ( stripslashes ( $_REQUEST ['idProveedor'] ) );
		
		$count = count ( $proveedores );
		
		
		
		
		for($i = 0; $i < $count; $i ++) {
			
			$datos = array (
					$_REQUEST ['idObjeto'],
					$proveedores [$i],
					'usuario' => $_REQUEST ['usuario'] 
			);
			// Inserto las solicitudes de cotizacion para cada proveedor
			$cadenaSql = $this->miSql->getCadenaSql ( 'ingresarCotizacion', $datos );
			$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "insertar" );
			
			if ($resultado) {
				// Envio correo al preveedor
				/*
				$to_mail = "jdavid.6700@gmail.com";
				//INICIO ENVIO DE CORREO AL USUARIO
				$rutaClases=$this->miConfigurador->getVariableConfiguracion("raizDocumento")."/classes";
				
				include_once($rutaClases."/mail/class.phpmailer.php");
				include_once($rutaClases."/mail/class.smtp.php");
				
				$mail = new PHPMailer();
				
				
				//configuracion de cuenta de envio
				$mail->Host     = "200.69.103.49";
				$mail->Mailer   = "smtp";
				$mail->SMTPAuth = true;
				$mail->Username = "condor@udistrital.edu.co";
				$mail->Password = "CondorOAS2012";
				$mail->Timeout  = 1200;
				$mail->Charset  = "utf-8";
				$mail->IsHTML(true);
				
				//remitente
				$fecha = date("d-M-Y g:i:s A");
				$to_mail=$_REQUEST ['correo'];
				
				$mail->AddAddress($to_mail);
				$mail->From='agora@udistrital.edu.co';
				$mail->FromName='UNIVERSIDAD DISTRITAL FRANCISCO JOSÉ DE CALDAS';
				$mail->Subject="Datos de Acceso - Registro de proveedores";
				$contenido="<p>Fecha de envio: " . $fecha . "</p>";
				$contenido.= "<p>Señor usuario usted hace parte del banco de proveedores de la Universidad Distrital Francisco José de Caldas y actualmente
						         existe una solicitud de Cotización para una Solicitud de Necesidad, en el cual el sistema lo identifico como candidato. </p>";
				$contenido.= "<p>La Actividad Economica de la Necesidad Refiere:</p>";
				$contenido.= "Actividad Economica:" . $_REQUEST ['nit'] . "<br>";
				$contenido.= "Objeto:" . $_REQUEST ['nit'];
				$contenido.= "Justificación:" . $_REQUEST ['nit'];
				$contenido.= "Valor para Contratación:" . $_REQUEST ['nit'];
				$contenido.= "<p>Por favior ingrese al Banco de proveedores de la Universidad Distrital. Al ingresar el sistema se le informara del proceso.</p>";
				$contenido.= "<p>Este mensaje ha sido generado automáticamente, favor no responder..</p>";
				
				$mail->Body=$contenido;
				
				if(!$mail->Send())
				{
					?>
				                <script language='javascript'>
				                alert('Error! El mensaje no pudo ser enviado, es posible que la dirección de correo electrónico no sea válido.!');
				                </script>
				                <?
				        }
						else
						{
				  			?>
							<script language='javascript'>
							alert('Se envió un correo con los datos de ingreso.');
							</script>
							<?php
						}    
				
				$mail->ClearAllRecipients();
				$mail->ClearAttachments();
				
				//FIN ENVIO DE CORREO AL USUARIO		
						
				*/
				
				
				
				
			}
		}
		
		
		
		// actualizo estado del objeto a contratar a 2(cotizacion)
		// actualizo fecha de solicitud
		// Actualizar estado del OBJETO CONTRATO A ASIGNADA
		$parametros = array (
				'idObjeto' => $_REQUEST ['idObjeto'],
				'estado' => 'COTIZACION', // solicitud de cotizacion
				'fecha' => date ( "Y-m-d" ),
				'usuario' => $_REQUEST ['usuario'] 
		);
		// Actualizo estado del objeto a contratar
		$cadenaSql = $this->miSql->getCadenaSql ( 'actualizarObjeto', $parametros );
		$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "insertar" );
		
		
		
		$parametros2 = array (
				'idObjeto' => $_REQUEST ['idObjeto'],
				'tipo' => 2, // objeto
				'fecha' => date ( "Y-m-d H:i:s" ),
				'usuario' => $_REQUEST ['usuario'] 
		);
		// Inserto codigo de validacion
		$cadenaSql = $this->miSql->getCadenaSql ( 'ingresarCodigo', $parametros2 );
		$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
		$datos = array (
				'idObjeto' => $_REQUEST ['idObjeto'],
				'idCodigo' => $resultado [0] ['id_codigo_validacion'] 
		);
		
		
		if ($resultado) {
			redireccion::redireccionar ( 'insertoCotizacion', $datos );
			exit ();
		} else {
			redireccion::redireccionar ( 'noInserto' );
			exit ();
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

$miRegistrador = new SolicitudCotizacion ( $this->lenguaje, $this->sql, $this->funcion );

$resultado = $miRegistrador->procesarFormulario ();

?>
