<?php error_reporting(E_ALL ^ E_NOTICE);
require_once('conexiones/conexione.php');
//include('admin/capturar_navegador.php');
mysql_select_db($base_datos, $conectar);
include ("session/funciones_admin.php");
date_default_timezone_set("America/Bogota");
//usuario y clave pasados por el formulario
$usuario = stripslashes($_POST['cuenta']);
$usuario = strip_tags($usuario);
$clave = $_POST['contrasena'];

//$clave_encriptada = md5($clave); //Encriptacion nivel 1
//$clave_encriptada2 = crc32($clave_encriptada1); //Encriptacion nivel 1
//$clave_encriptada3 = crypt($clave_encriptada2, "xtemp"); //Encriptacion nivel 2
//$clave_encriptada = sha1("xtemp".$clave_encriptada3); //Encriptacion nivel 3

$sql = "SELECT * FROM informacion_almacen WHERE cod_informacion_almacen = '1'";
$consulta = mysql_query($sql, $conectar) or die(mysql_error());
$dato = mysql_fetch_assoc($consulta);

if (conexiones($usuario, $clave)) {

$cod_sesiones = $_POST['cod_sesiones'];
$ip = $_SERVER['REMOTE_ADDR'];
$navegador = $_SERVER['HTTP_USER_AGENT'];
$plataforma = date("Y/m/d");
$fecha_entrada =  date("Y/m/d - H:i:s");
$version = date("d/m/Y - H:i:s");
$fecha_ini_time = time();

$agregar_registros_sesion = "INSERT INTO sesiones (cod_sesiones, usuario, ip, navegador, version, plataforma, fecha_entrada, fecha_ini_time)
VALUES ('$cod_sesiones', '$usuario', '$ip', '$navegador', '$version', '$plataforma', '$fecha_entrada', '$fecha_ini_time')";
$resultado_sql1 = mysql_query($agregar_registros_sesion, $conectar) or die(mysql_error());

header("Location:inicio.php");
} else {
echo "<center><font color='yellow' size='4'><p align='center'><br>El nombre de usuario o la contrase&ntilde;a introducidos no son correctos.</p></font></center>";
//header("Location: index.php");
}
?>
<title><?php echo $dato['titulo'];?></title>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<link rel="stylesheet" type="text/css" href="estilo_css/estilo_acceso2.css">
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<link href="imagenes/icono.ico" type="image/x-icon" rel="shortcut icon" />
<br>
<form id="acceso" action="verificacion.php" method="post">
<fieldset>
<legend><font size="4">Entrar Al Sistema</font></legend><br>
<li>
<label for="usuario">Usuario</label>
<input id="usuario" name="cuenta" type=text placeholder="Usuario" required autofocus>
</li>
<br>
<br>
<li>
<label for="password">Contrase&ntilde;a</label>
<input name="contrasena" id="pass" type=password placeholder="Contrase&ntilde;a" required autofocus>
</li>
<br><br>
<input type="submit" id="enviar" onclick="cifrar()" value="Entrar" />
</fieldset>
</form>

<script src="admin/sha1.js"></script>
<script>
function cifrar(){
var input_pass = document.getElementById("pass");
input_pass.value = sha1(input_pass.value);
}
</script>
</body>
</html>