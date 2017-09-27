 <?php error_reporting(E_ALL ^ E_NOTICE);
require_once('conexiones/conexione.php');
require_once('evitar_mensaje_error/error.php');
mysql_select_db($base_datos, $conectar);

$sql = "SELECT * FROM informacion_almacen WHERE cod_informacion_almacen = '1'";
$consulta = mysql_query($sql, $conectar) or die(mysql_error());
$dato = mysql_fetch_assoc($consulta);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="estilo_css/estilo_acceso2.css">
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title><?php echo $dato['titulo'];?></title>
<link href="imagenes/icono.ico" type="image/x-icon" rel="shortcut icon" />
</head>
<body>
<br><br>
<form id="acceso" action="verificacion.php" method="POST">
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

<?php $sql_consulta1="SELECT cod_sesiones FROM sesiones ORDER BY cod_sesiones DESC LIMIT 1";
$resultado = mysql_query($sql_consulta1, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<input type="hidden" name="cod_sesiones" value="<?php echo $contenedor['cod_sesiones']+1 ?>" size ="4">
<?php }?>
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