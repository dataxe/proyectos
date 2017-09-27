<?php
date_default_timezone_set("America/Bogota");
//Propagación en URL
//La forma de prevenir esto es no utilizar la URL para el identificador de sesión; utilizar únicamente las cookies.
ini_set('session.use_only_cookies', 1);
//Robo por Cross-Site Scripting
/*Este tipo de ataque se puede prevenir (además de evitando los ataques XSS) haciendo que las cookies de sesión tengan el atributo HttpOnly, 
que evita que puedan ser manejadas por javascript en la mayoría de navegadores.*/
ini_set('session.cookie_httponly', 1);

$_SESSION['userAgent'] = $_SERVER['HTTP_USER_AGENT'];
$_SESSION['SKey'] = uniqid(mt_rand(), true);
//$_SESSION['IPaddress'] = ExtractUserIpAddress();
$_SESSION['LastActivity'] = $_SERVER['REQUEST_TIME'];

$obtener_seguridad_diseno = "SELECT estilo_css, cod_seguridad FROM administrador WHERE cuenta = '$cuenta_actual'";
$resultado_diseno = mysql_query($obtener_seguridad_diseno, $conectar) or die(mysql_error());
$seguridad_acceso = mysql_fetch_assoc($resultado_diseno); 
?>
<link rel="stylesheet" type="text/css" href="../estilo_css/<?php echo $seguridad_acceso['estilo_css'];?>">
<?php

if ($seguridad_acceso['cod_seguridad'] == 3) {
require_once('../plantillas/plantilla_principal.php');
} 
elseif ($seguridad_acceso['cod_seguridad'] == 2 || $seguridad_acceso['cod_seguridad'] == 5) { 
require_once('../plantillas/plantilla_segundaria.php');
} 
elseif ($seguridad_acceso['cod_seguridad'] == 1) {
require_once('../plantillas/plantilla_tercearia.php');
}
elseif ($seguridad_acceso['cod_seguridad'] == 4) {
require_once('../plantillas/plantilla_cuater.php');
} 
else {
header("Location:acceso_denegado.php");
}
?>