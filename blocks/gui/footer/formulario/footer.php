<?php
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}

$rutaPrincipal = $this->miConfigurador->getVariableConfiguracion ( 'host' ) . $this->miConfigurador->getVariableConfiguracion ( 'site' );

$indice = $rutaPrincipal . "/index.php?";

$directorio = $rutaPrincipal . '/' . $this->miConfigurador->getVariableConfiguracion ( 'bloques' ) . "/menu_principal/imagen/";

$urlBloque = $this->miConfigurador->getVariableConfiguracion ( 'rutaUrlBloque' );

$enlace= $this->miConfigurador->getVariableConfiguracion ( 'enlace' );


?><hr>
<div class="footer">
	<div class="footer_messages  fcenter">
	<p>
		<strong> UNIVERSIDAD DISTRITAL Francisco Jos&eacute; de Caldas</strong>
		<br>
		Bogot&aacute;, D.C. - Colombia<br>
	</p>
	<hr class="hr_divisionPie">
</div>
	<div class="pieImagenes">
	<br>
		<div class="pieMenuImagen" align="center">
		      <div class="pieImagen fleft"> <a id="unidistrital" href="http://www.udistrital.edu.co" target="_blank"> <span>Universidad Distrital Francisco Jos&eacute; de Caldas</span> </a> </div>
		      <div class="pieImagen fleft"> <a id="minEducacion" href="http://www.mineducacion.gov.co" target="_blank"> <span>Ministerio de educaci&oacute;n</span> </a> </div>
		      <div class="pieImagen fleft"> <a id="segEducacionBogota" href="http://www.sedbogota.edu.co/" target="_blank"> <span>Secretaria de Educaci&oacute;n de Bogot&aacute;</span></a> </div>
		</div>
	<br>
	</div>
</div>

