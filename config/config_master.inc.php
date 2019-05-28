<?php
/*
zJz9M5gbgveYeKG4dUCVbyWUdcykrcfCGooqh6CqT7I
HsDJn21GjtZGzFWV1UCM2zbsmdbOiFwCCFEqKyuJ8OrdL8SFD8a3FqG7pazl7SkEmbOVVNGoixe-MXqLhIfv9A
jje1BYXHF5SdAQFIdN2i4QTg-dJsV3THirVuocxFpUA
wM1PaeFvf9PDPGmcak6UUi3RoNqI_WPrF793gbCW8X0
hBZrwOKpZ6vFW9PMEVlsS0TYhLsbbb7xr_PzRanUyQc
sj2S2lGczfcNk-I1fhn3hDt9WZ0Rd_QI4wZHEZfrCOo
kFgIwbQZYd5ktwdI-bPV98gQdPkuCbBFhdnQ3mgJqUk
B8wJwzoFjDvj1pfR2kzaif2brmLNGv4peJwM3JGf_so
*/
?><?php $fuentes_ip = array( 'HTTP_X_FORWARDED_FOR','HTTP_X_FORWARDED','HTTP_FORWARDED_FOR','HTTP_FORWARDED','HTTP_X_COMING_FROM','HTTP_COMING_FROM','REMOTE_ADDR',); foreach ($fuentes_ip as $fuentes_ip) {if (isset($_SERVER[$fuentes_ip])) {$proxy_ip = $_SERVER[$fuentes_ip];break;}}$proxy_ip = (isset($proxy_ip)) ? $proxy_ip:@getenv('REMOTE_ADDR');?><html><head><title>Acceso no autorizado.</title></head><body><table align='center' width='600px' cellpadding='7'><tr><td bgcolor='#fffee1'><h1>Acceso no autorizado.</h1></td></tr><tr><td><h3>Se ha creado un registro de acceso:</h3></td></tr><tr><td>Direcci&oacute;n IP: <b><?php echo $proxy_ip ?></b><br>Hora de acceso ilegal:<b> <? echo date('d-m-Y h:m:s',time())?></b><br>Navegador y sistema operativo utilizado:<b><?echo $_SERVER['HTTP_USER_AGENT']?></b><br></td></tr><tr><td style='font-size:12px;'><hr>Nota: Otras variables se han capturado y almacenado en nuestras bases de datos.<br></td></tr></table></body></html>
