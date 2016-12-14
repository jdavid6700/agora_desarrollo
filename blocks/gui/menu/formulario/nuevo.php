<?php
// include_once("../Sql.class.php");
$miSql = new Sqlmenu ();
// var_dump($this->miConfigurador);
$esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");

$rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
$rutaBloque .= $this->miConfigurador->getVariableConfiguracion("site") . "/blocks/";
$rutaBloque .= $esteBloque ['grupo'] . "/" . $esteBloque ['nombre'];

$directorio = $this->miConfigurador->getVariableConfiguracion("host");
$directorio .= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
$directorio .= $this->miConfigurador->getVariableConfiguracion("enlace");

$conexion = "framework";
$frameworkRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

$miSesion = Sesion::singleton();

$miLogger = logger::singleton();

// verifica los roles del usuario en el sistema
$roles = $miSesion->RolesSesion();
$roles_unicos = $miSesion->RolesSesion_unico();

// consulta datos del usuario
$id_usuario = $miSesion->getSesionUsuarioId();

if($id_usuario == null || $_REQUEST['pagina'] == "registroProveedor"){




    //*****************************************************************************************************************
    //*****************************************************************************************************************
    //******************************** CASO ESPECIAL CREAR SESION PERFIL PUBLICO (REGISTRO PERSONA) *******************
    
    $_REQUEST ['tiempo'] = time ();

    //var_dump($_REQUEST);

    if($id_usuario != null && $id_usuario != 'REG777'){//Limpiar Posibles SESIONES Abiertas con Otros ROLES

        $_REQUEST['sesion'] = $miSesion->getSesionId();
        $_REQUEST ["usuario"] == $id_usuario;


        //session_start();
        $arregloLogin = array('CierreSesion',$_REQUEST ["usuario"],$_SERVER ['REMOTE_ADDR'],$_SERVER ['HTTP_USER_AGENT']);
        $argumento = json_encode($arregloLogin);
        $arreglo = array($_REQUEST ["usuario"],$argumento);
        
        $sesionActiva = $_REQUEST['sesion'];
        $log=array('accion'=>"SALIDA",
                    'id_registro'=>$_REQUEST ["usuario"]."|".$sesionActiva,
                    'tipo_registro'=>"LOGOUT",
                    'nombre_registro'=>$arreglo[1],
                    'descripcion'=>"Salida al sistemas del usuario ".$_REQUEST ["usuario"]." con la sesion ".$sesionActiva,
                   ); 
        //var_dump($log);
        //$_COOKIE["aplicativo"]=$estaSesion;
        $miLogger->log_usuario($log);
        
        $borrarSesion = $miSesion->borrarValorSesion('TODOS', $sesionActiva);
        $terminarSesion = $miSesion->terminarSesion($sesionActiva);
        //session_destroy();
        
    }


        if ($_REQUEST ['tiempo'] <= time() + $this->miConfigurador->getVariableConfiguracion('expiracion')) {


            if (isset($_REQUEST ["clave"]) || !isset($_REQUEST['id_usuario']) || $_REQUEST['id_usuario'] == 'REG777') {

                    $_REQUEST ['id_usuario'] = 'REG777';

                    // 1. Crear una sesión de trabajo
                    $estaSesion = $miSesion->crearSesion($_REQUEST ['id_usuario']);

                    $arregloLogin = array(
                        'autenticacionExitosa',
                        $_REQUEST ['id_usuario'],
                        $_SERVER ['REMOTE_ADDR'],
                        $_SERVER ['HTTP_USER_AGENT']
                    );


                    $argumento = json_encode($arregloLogin);
                    $arreglo = array(
                        $_REQUEST ['id_usuario'],
                        $argumento
                    );

                    $cadena_sql = $miSql->getCadenaSql("registrarEvento", $arreglo);
                    $registroAcceso = $frameworkRecursoDB->ejecutarAcceso($cadena_sql, "acceso");

                    if ($estaSesion) {

                        $log = array('accion' => "INGRESO",
                            'id_registro' => $_REQUEST ['id_usuario'] . "|" . $estaSesion,
                            'tipo_registro' => "LOGIN",
                            'nombre_registro' => $arreglo[1],
                            'descripcion' => "Ingreso al sistemas del usuario " . $_REQUEST ['id_usuario'] . " con la sesion " . $estaSesion,
                        );
                        //            var_dump($log);
                        $_COOKIE["aplicativo"] = $estaSesion;
                        $miLogger->log_usuario($log);

                      
                    }
            }
        }

    $id_usuario = $miSesion->getSesionUsuarioId();
    $_REQUEST ['usuario'] = $id_usuario;

    //$_REQUEST ['usuario'] = $_REQUEST ['id_usuario'];
    //$id_usuario = $_REQUEST ['usuario'];

    //*****************************************************************************************************************
    //*****************************************************************************************************************
    
    $cadena_sql = $miSql->getCadenaSql("datosUsuario", $id_usuario);
    $regUser = $frameworkRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");


    $parametros ['id_usuario'] = $id_usuario;
    $parametros ['tipo'] = 'inactivo';

    $cadena_sql = $miSql->getCadenaSql("RolesInactivos", $parametros);
    // $rolOut = $frameworkRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
    unset($parametros ['tipo']);
    $parametros ['tipo'] = 'caduco';
    $cadena_cad = $miSql->getCadenaSql("RolesInactivos", $parametros);
    $rolCad = $frameworkRecursoDB->ejecutarAcceso($cadena_cad, "busqueda");
    // CambiarContraseña
    $_REQUEST ['tiempo'] = time();
    $enlaceCambiarClave ['enlace'] = "pagina=cambiarClave";
    $enlaceCambiarClave ['enlace'] .= "&opcion=cambiarClave";
    $enlaceCambiarClave ['enlace'] .= "&campoSeguro=" . $_REQUEST ['tiempo'];
    $enlaceCambiarClave ['enlace'] .= "&usuario=" . $id_usuario;
    $enlaceCambiarClave ['urlCodificada'] = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($enlaceCambiarClave ['enlace'], $directorio);
    $enlaceCambiarClave ['nombre'] = "Cambiar Contraseña";

    // Fin de la sesión

    $enlaceFinSesion ['enlace'] = "action=loginAgora";
    $enlaceFinSesion ['enlace'] .= "&pagina=index";
    $enlaceFinSesion ['enlace'] .= "&bloque=loginAgora";
    $enlaceFinSesion ['enlace'] .= "&bloqueGrupo=registro";
    $enlaceFinSesion ['enlace'] .= "&opcion=finSesion";
    $enlaceFinSesion ['enlace'] .= "&campoSeguro=" . $_REQUEST ['tiempo'];
    $enlaceFinSesion ['enlace'] .= "&sesion=" . $miSesion->getSesionId();
    $enlaceFinSesion ['enlace'] .= "&usuario=" . $id_usuario;
    $enlaceFinSesion ['urlCodificada'] = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($enlaceFinSesion ['enlace'], $directorio);
    $enlaceFinSesion ['nombre'] = "Cerrar Sesión";

    // ------------------------------- Inicio del Menú-------------------------- //
    ?>
    <nav id="cbp-hrmenu" class="cbp-hrmenu">
        <ul>

            <?php
            if (isset($mMenu)) {
                // cada foreach arma encabezado del menu, grupo y servicio en su orden.
                foreach ($mMenu as $mkey => $menus) {
                    ?> <li><a href="#"><?php echo $mkey; ?> </a>
                        <div class="cbp-hrsub">
                            <div class="cbp-hrsub-inner"> 
                                <?php
                                foreach ($menus as $gkey => $grupos) {
                                    ?>  <div>
                                        <h4><?php echo $gkey; ?></h4>
                                        <ul>
                                            <?php
                                            foreach ($grupos as $skey => $service) {
                                                ?>
                                                <li><a
                                                        href="<?php echo $grupos[$skey]['urlCodificada'] ?>"><?php echo $skey ?></a></li>
                                                <?php } ?>                                 
                                        </ul>
                                    </div>
                                <?php } ?>
                            </div>
                            <!-- /cbp-hrsub-inner -->
                        </div> <!-- /cbp-hrsub --></li>
                    <?php
                }
            }
            ?>            
            <li><a href="#">Mi Sesión</a>
                <div class="cbp-hrsub">
                    <div class="cbp-hrsub-inner">
                        <div>
                            <h4>Usuario: <?php echo $regUser[0]['nombre'] . " " . $regUser[0]['apellido'] ?></h4>
                            <ul>
                                <li><a href="<?php echo $enlaceFinSesion['urlCodificada'] ?>"><?php echo ($enlaceFinSesion['nombre']) ?></a></li>
                            </ul>
                        </div>
                        <?php
                        if (isset($roles_unicos) && is_array($roles_unicos)) {
                            ?>                        
                            <div>
                                <h4>Perfiles Activos</h4>
                                <ul><?php
                                    foreach ($roles_unicos as $value) {
                                        ?>
                                        <li><a href="#"><?php echo $value['rol'] ?></a></li>    
                                        <?php
                                    }
                                    ?>
                                </ul>
                            </div>
                            <?php
                        }

                        if (isset($rolOut) && is_array($rolOut)) {
                            ?>                        
                            <div>
                                <h4>Perfiles Inactivos</h4>
                                <ul><?php
                                    foreach ($rolOut as $valueOut) {
                                        ?>
                                        <li><a href="#"><?php echo $valueOut['rol'] ?></a></li>    
                                        <?php
                                    }
                                    ?>
                                </ul>
                            </div>
                            <?php
                        }

                        if (isset($rolCad) && is_array($rolCad)) {
                            ?>                        
                            <div>
                                <h4>Perfiles Caducados</h4>
                                <ul><?php
                                    foreach ($rolCad as $valueCad) {
                                        ?>
                                        <li><a href="#"><?php echo $valueCad['rol'] . " - " . $valueCad['fecha_caduca'] ?></a></li>    
                                        <?php
                                    }
                                    ?>
                                </ul>
                            </div>
                        <?php } ?>

                    </div>
                    <!-- /cbp-hrsub-inner -->
                </div> <!-- /cbp-hrsub --></li>

        </ul>
    </nav>
    
<?php 

}else{



    $_REQUEST ['usuario'] = $id_usuario;
    $cadena_sql = $miSql->getCadenaSql("datosUsuario", $id_usuario);
    $regUser = $frameworkRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");


    if ($regUser [0] ['estado'] != 1) {
        $parametro ['cod_app'] = '';
        $parametro ['cod_rol'] = '';
    } else {
        $tam = (count($roles) - 1);
        $cod_rol = '';
        $cod_app = '';
        foreach ($roles as $key => $value) {
            if ($key < $tam) {
                $cod_rol .= $roles [$key] ['cod_rol'] . ",";
            } else {
                $cod_rol .= $roles [$key] ['cod_rol'];
            }

            if ($key < $tam) {
                $cod_app .= $roles [$key] ['cod_app'] . ",";
            } else {
                $cod_app .= $roles [$key] ['cod_app'];
            }
        }
        $parametro ['cod_app'] = $cod_app;
        $parametro ['cod_rol'] = $cod_rol;
    }


    // busca los datos de los servicios y los menus según los roles del usuario
    $cadena_sql = $miSql->getCadenaSql("datosMenus", $parametro);
    $reg_menu = $frameworkRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

    if ($reg_menu) {
        // Arma la matriz de menus con sus repectivos grupos y servicios
        $mMenu = array();
        foreach ($reg_menu as $key => $value) {
            if (isset($reg_menu [$key] ['url_host_enlace']) && $reg_menu [$key] ['url_host_enlace'] != '') {
                $host = $reg_menu [$key] ['url_host_enlace'];
            } else {
                $host = $directorio;
            }

            $enlaceServ ['URL'] = "pagina=" . $reg_menu [$key] ['pagina_enlace'];
            $enlaceServ ['URL'] .= "&usuario=" . $id_usuario;
            $enlaceServ ['URL'] .= $reg_menu [$key] ['parametros'];

            $enlaceServ ['urlCodificada'] = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($enlaceServ ['URL'], $host);


            $mMenu [$reg_menu [$key] ['menu']] [$reg_menu [$key] ['grupo']] [$reg_menu [$key] ['enlace']] = array(
                'urlCodificada' => $enlaceServ ['urlCodificada']
            );
            unset($enlaceServ);
        }
    }
    $parametros ['id_usuario'] = $id_usuario;
    $parametros ['tipo'] = 'inactivo';

    $cadena_sql = $miSql->getCadenaSql("RolesInactivos", $parametros);
    // $rolOut = $frameworkRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
    unset($parametros ['tipo']);
    $parametros ['tipo'] = 'caduco';
    $cadena_cad = $miSql->getCadenaSql("RolesInactivos", $parametros);
    $rolCad = $frameworkRecursoDB->ejecutarAcceso($cadena_cad, "busqueda");
    // CambiarContraseña
    $_REQUEST ['tiempo'] = time();
    $enlaceCambiarClave ['enlace'] = "pagina=cambiarClave";
    $enlaceCambiarClave ['enlace'] .= "&opcion=cambiarClave";
    $enlaceCambiarClave ['enlace'] .= "&campoSeguro=" . $_REQUEST ['tiempo'];
    $enlaceCambiarClave ['enlace'] .= "&usuario=" . $id_usuario;
    $enlaceCambiarClave ['urlCodificada'] = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($enlaceCambiarClave ['enlace'], $directorio);
    $enlaceCambiarClave ['nombre'] = "Cambiar Contraseña";

    // Fin de la sesión

    $enlaceFinSesion ['enlace'] = "action=loginAgora";
    $enlaceFinSesion ['enlace'] .= "&pagina=index";
    $enlaceFinSesion ['enlace'] .= "&bloque=loginAgora";
    $enlaceFinSesion ['enlace'] .= "&bloqueGrupo=registro";
    $enlaceFinSesion ['enlace'] .= "&opcion=finSesion";
    $enlaceFinSesion ['enlace'] .= "&campoSeguro=" . $_REQUEST ['tiempo'];
    $enlaceFinSesion ['enlace'] .= "&sesion=" . $miSesion->getSesionId();
    $enlaceFinSesion ['enlace'] .= "&usuario=" . $id_usuario;
    $enlaceFinSesion ['urlCodificada'] = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($enlaceFinSesion ['enlace'], $directorio);
    $enlaceFinSesion ['nombre'] = "Cerrar Sesión";

    // ------------------------------- Inicio del Menú-------------------------- //
    ?>
    <nav id="cbp-hrmenu" class="cbp-hrmenu">
        <ul>

            <?php
            if (isset($mMenu)) {
                // cada foreach arma encabezado del menu, grupo y servicio en su orden.
                foreach ($mMenu as $mkey => $menus) {
                    ?> <li><a href="#"><?php echo $mkey; ?> </a>
                        <div class="cbp-hrsub">
                            <div class="cbp-hrsub-inner"> 
                                <?php
                                foreach ($menus as $gkey => $grupos) {
                                    ?>  <div>
                                        <h4><?php echo $gkey; ?></h4>
                                        <ul>
                                            <?php
                                            foreach ($grupos as $skey => $service) {
                                                ?>
                                                <li><a
                                                        href="<?php echo $grupos[$skey]['urlCodificada'] ?>"><?php echo $skey ?></a></li>
                                                <?php } ?>                                 
                                        </ul>
                                    </div>
                                <?php } ?>
                            </div>
                            <!-- /cbp-hrsub-inner -->
                        </div> <!-- /cbp-hrsub --></li>
                    <?php
                }
            }
            ?>            
            <li><a href="#">Mi Sesión</a>
                <div class="cbp-hrsub">
                    <div class="cbp-hrsub-inner">
                        <div>
                            <h4>Usuario: <?php echo $regUser[0]['nombre'] . " " . $regUser[0]['apellido'] ?></h4>
                            <ul>
                                <li><a href="<?php echo $enlaceCambiarClave['urlCodificada'] ?>"><?php echo ($enlaceCambiarClave['nombre']) ?></a></li>
                                <li><a href="<?php echo $enlaceFinSesion['urlCodificada'] ?>"><?php echo ($enlaceFinSesion['nombre']) ?></a></li>
                            </ul>
                        </div>
                        <?php
                        if (isset($roles_unicos) && is_array($roles_unicos)) {
                            ?>                        
                            <div>
                                <h4>Perfiles Activos</h4>
                                <ul><?php
                                    foreach ($roles_unicos as $value) {
                                        ?>
                                        <li><a href="#"><?php echo $value['rol'] ?></a></li>    
                                        <?php
                                    }
                                    ?>
                                </ul>
                            </div>
                            <?php
                        }

                        if (isset($rolOut) && is_array($rolOut)) {
                            ?>                        
                            <div>
                                <h4>Perfiles Inactivos</h4>
                                <ul><?php
                                    foreach ($rolOut as $valueOut) {
                                        ?>
                                        <li><a href="#"><?php echo $valueOut['rol'] ?></a></li>    
                                        <?php
                                    }
                                    ?>
                                </ul>
                            </div>
                            <?php
                        }

                        if (isset($rolCad) && is_array($rolCad)) {
                            ?>                        
                            <div>
                                <h4>Perfiles Caducados</h4>
                                <ul><?php
                                    foreach ($rolCad as $valueCad) {
                                        ?>
                                        <li><a href="#"><?php echo $valueCad['rol'] . " - " . $valueCad['fecha_caduca'] ?></a></li>    
                                        <?php
                                    }
                                    ?>
                                </ul>
                            </div>
                        <?php } ?>

                    </div>
                    <!-- /cbp-hrsub-inner -->
                </div> <!-- /cbp-hrsub --></li>

        </ul>
    </nav>
    
<?php 

} 

?> 






