<?php

/*
 * To change this license header, choose License Headers in Project Properties. To change this template file, choose Tools | Templates and open the template in the editor.
 */
$esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );

$rutaBloque = $this->miConfigurador->getVariableConfiguracion ( "raizDocumento" ) . "/blocks/inventarios/";
$rutaBloque .= $esteBloque ['nombre'];
$host = $this->miConfigurador->getVariableConfiguracion ( "host" ) . $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/inventarios/" . $esteBloque ['nombre'];

$conexion = "estructura";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );

$resultado = '';




























ECHO "HOLA";

    $rutaClases=$this->miConfigurador->getVariableConfiguracion("raizDocumento")."/classes";

    include_once($rutaClases."/mail/class.phpmailer.php");
    include_once($rutaClases."/mail/class.smtp.php");

        $mail = new PHPMailer();     

        //configuracion de cuenta de envio
        $mail->Host     = '200.69.103.49';//"mail.udistrital.edu.co";
        $mail->Mailer   = "smtp";
        $mail->SMTPAuth = true;
        $mail->Username = "condor@udistrital.edu.co";
        $mail->Password = "CondorOAS2012";
        $mail->Timeout  = 1200;
        $mail->Charset  = "utf-8";
        $mail->IsHTML(true);

        //remitente
        $fecha = date("d-M-Y g:i:s A");
        $to_mail="benmotta@gmail.com";
        
        $mail->AddAddress($to_mail);
        
        
        
        //$to_mail1=isset($to_mail)?$to_mail:'';
        /*$i=0;
        while(isset($to_mail1[$i])){
            //echo $to_mail1[$i];
            $mail->AddAddress($to_mail1[$i]);
        $i++;
        }*/
        //$to_mail1="jenegui@gmail.com"; //Comentariar
        //$mail->AddAddress($to_mail); //Comentariar
           
            
            //$to_mail2='jesusneirag@hotmail.com';
            //$mail->AddCC($to_mail2);
        
       // $to_mail3 = 'recibos@correo.udistrital.edu.co';//Clave del correo recibos2012
        //$mail->AddBCC($to_mail3);
         $mail->From='evaldocente@udistrital.edu.co';
        $mail->FromName='UNIVERSIDAD DISTRITAL FRANCISCO JOSÉ DE CALDAS';
        $mail->Subject="PROVEEDORES PRUEBAS UD";
        
        $mail->Body="HOLA";
        
        
        var_dump($mail);
        //$mail->AddAddress($to_mail);
        if(!$mail->Send())
        {
            
           echo "no envioa";
                ?>
                <script language='javascript'>
                alert('Error! El mensaje no pudo ser enviado, es posible que la dirección de correo electrónico no sea válido.!');
                </script>
                <?php 
                $this->funcion->redireccionar ("paginaPrincipal");
        }
    else
    {
        echo "enviado";
    ?>
    <script language='javascript'>
    alert('Se envió un enlace al correo: <?echo $correoosEnviados ?>, remitase a su correo, haga clic en el enlace (o copie y pegue la URL en su navegador), para poder continuar con la recuperación de su contraseña !');
    </script>
    <?php
    $this->funcion->redireccionar ("paginaPrincipal");
    }    


//$mail->ClearAllRecipients();
//$mail->ClearAttachments();





























$fechaActual = date ( 'Y-m-d' );
//INICIO Calculo puntaje total
$puntajeTotal = 0;
$puntajeTotal = $_REQUEST ['tiempoEntrega'] + $_REQUEST ['cantidades'] + $_REQUEST ['conformidad'] + $_REQUEST ['funcionalidadAdicional'];
$_REQUEST ['reclamaciones'] = $_REQUEST ['reclamacionSolucion']==12?0:$_REQUEST ['reclamaciones'];
$puntajeTotal = $puntajeTotal + $_REQUEST ['reclamaciones'] + $_REQUEST ['reclamacionSolucion'] + $_REQUEST ['servicioVenta'] + $_REQUEST ['procedimientos'];
$_REQUEST ['garantia'] = $_REQUEST ['garantiaSatisfaccion']==15?0:$_REQUEST ['garantia'];
$puntajeTotal =  $puntajeTotal + $_REQUEST ['garantia'] + $_REQUEST ['garantiaSatisfaccion'];
//FIN Calculo puntaje total

//INICIO CALCULO CLASIFICACION
    function clasificacion($puntajeTotal = '') {
		if( $puntajeTotal > 79 )
			$valor = "A";
		elseif( $puntajeTotal > 45 )
			$valor = "B";
		else $valor = "C";
        return $valor;
    }
	$clasificacion = clasificacion($puntajeTotal); 
	
//FIN CALCULO CLASIFICACION
						
//Cargo array con los datos para insertar en la table evaluacionProveedor
$arreglo = array (
		$_REQUEST ['idContrato'],
		$fechaActual,
		$_REQUEST ['tiempoEntrega'],
		$_REQUEST ['cantidades'],
		$_REQUEST ['conformidad'],
		$_REQUEST ['funcionalidadAdicional'],
		$_REQUEST ['reclamaciones'],
		$_REQUEST ['reclamacionSolucion'],
		$_REQUEST ['servicioVenta'],		
		$_REQUEST ['procedimientos'],
		$_REQUEST ['garantia'],		
		$_REQUEST ['garantiaSatisfaccion'],
		$puntajeTotal,
		$clasificacion
);


//Guardar datos de la evaluacion
$cadenaSql = $this->sql->getCadenaSql ( "registroEvaluacion", $arreglo );
$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso' );



if ($resultado) {
	//Actualizar estado del CONTRATO A EVALUADO
		$parametros = array (
				'idContrato' => $_REQUEST ['idContrato'],
				'estado' => 2  //evaluado
		);
		$cadenaSql = $this->sql->getCadenaSql ( "actualizarContrato", $parametros );
		$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso');
	
	//Actualizar PUNTAJE TOTAL DEL PROVEEDOR Y SU CLASIFICACION
 		//Consulto puntaje total del Evaluado
		$cadenaSql = $this->sql->getCadenaSql ( 'consultarProveedorByID', $_REQUEST["idProveedor"] );
		$proveedor = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
		$puntajeActual = $proveedor[0]["puntaje_evaluacion"];
		$claseficacionActual = $proveedor[0]["clasificacion_evaluacion"];
	
		if(  $claseficacionActual == 'A' || $claseficacionActual == 'B' || $claseficacionActual == 'C' ){
			$puntajeNuevo = ( $puntajeActual + $puntajeTotal )/2;
			$clasficacionNueva = clasificacion($puntajeNuevo); 
		}else{
			$puntajeNuevo = $puntajeTotal;
			$clasficacionNueva = $clasificacion;
		}

		$valores = array (
				'idProveedor' => $_REQUEST ['idProveedor'],
				'puntajeNuevo' => $puntajeNuevo,
				'clasificacion' => $clasficacionNueva
		);
		
		$cadenaSql = $this->sql->getCadenaSql ( "actualizarProveedor", $valores );
		$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso');

		$this->funcion->Redireccionador ( 'registroExitoso', $_REQUEST['idContrato'] );
		exit();
} else {
		$this->funcion->Redireccionador ( 'noregistro', $_REQUEST['usuario'] );
		exit();
}
