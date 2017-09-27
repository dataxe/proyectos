<?php error_reporting(E_ALL ^ E_NOTICE);
date_default_timezone_set("America/Bogota");
require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
include ("../seguridad/seguridad_diseno_plantillas.php");
include ("../formato_entrada_sql/funcion_env_val_sql.php");
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../estilo_css/estilo.css">
<title>ALMACEN</title>
<meta charset="UTF-8">
</head>
<body>
<?php require_once("../busquedas/menu_mensajeria.php");?>
<div class="subir">
<form action="<?php echo $formulario_agregar;?>" name="formulario" method="post">
<br>
<td>Correo: <select name="cuenta">
<?php $sql_consulta="SELECT * FROM administrador WHERE cuenta <> '$cuenta_actual '";
$resultado = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option value="<?php echo $contenedor['cuenta'] ?>"><?php echo $contenedor['cuenta'] ?></option>
<?php }?>
</select></td>
<br>
Asunto:
<td><input type="text" name="asunto" value="" size="56"></td>
<br>
<textarea cols="40" rows="5" placeholder="Mensaje" name="mensaje"></textarea>
<input type="submit" name="boton" value="Enviar">
<input type="hidden" name="insertar_datos" value="formulario">
</form>
<br>
</div>
</body>
</html>
<?php
$mensaje_leido = "NO";
$fecha = date("Y/m/d - H:i:s");
$para = $_POST['cuenta'];
if (isset($_POST["cuenta"])) {
$agregar_registros_sql1 = sprintf("INSERT INTO mensajeria (para, de, asunto, mensaje, leido, fecha) VALUES (%s, %s, %s, %s, %s, %s)",
envio_valores_tipo_sql($_POST['cuenta'], "text"),
envio_valores_tipo_sql($cuenta_actual, "text"),
envio_valores_tipo_sql($_POST['asunto'], "text"),
envio_valores_tipo_sql($_POST['mensaje'], "text"),
envio_valores_tipo_sql($mensaje_leido, "text"),
envio_valores_tipo_sql($fecha, "text"));							   	
					   					   
$resultado_sql1 = mysql_query($agregar_registros_sql1, $conectar) or die(mysql_error());
echo '<META HTTP-EQUIV="REFRESH" CONTENT="3; ../admin/mensajeria.php">';
echo "<font color='white'><center>Mensaje enviado</center></font>";
}
?>