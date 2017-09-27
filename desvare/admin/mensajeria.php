<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
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
require_once("../busquedas/menu_mensajeria.php");
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");

$buscar = $_POST['palabra'];
$mostrar_datos_sql = "SELECT * FROM mensajeria WHERE para = '$cuenta_actual' AND (asunto like '%$buscar%' OR de like '%$buscar%') order by fecha ASC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$matriz_consulta = mysql_fetch_assoc($consulta);
?>
<br>
<center>
<form action="../admin/mensajeria.php" method="post">
<input id="foco" name="palabra" />
<input type="submit" name="buscador" value="Buscar mensajes" />
</form>
</center>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<p>
<center>
<table width="70%" id="table">
<tr>
<td><div align="center"><strong>REMITENTE</strong></div></td>
<td><div align="center"><strong>ASUNTO</strong></div></td>
<td><div align="center"><strong>LEIDO</strong></div></td>
<td><div align="center"><strong>FECHA</strong></div></td>
<td><div align="center"><strong>ELIM</strong></div></td>
</tr>
<?php do { ?>
<tr>
<td ><a href="../modificar_eliminar/ver_mensajeria.php?cod_mensajeria=<?php echo $matriz_consulta['cod_mensajeria']; ?>"><center><?php echo $matriz_consulta['de']; ?></a></td>
<td ><a href="../modificar_eliminar/ver_mensajeria.php?cod_mensajeria=<?php echo $matriz_consulta['cod_mensajeria']; ?>"><center><?php echo $matriz_consulta['asunto']; ?></a></td>
<td ><?php echo $matriz_consulta['leido']; ?></span></td>
<td ><?php echo $matriz_consulta['fecha']; ?></span></td>
<td><a href="../modificar_eliminar/eliminar_mensajes.php?cod_mensajeria=<?php echo $matriz_consulta['cod_mensajeria']; ?>"><center><img src=../imagenes/eliminar.png></a></td>
</tr>
<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
</table>
<script>
window.onload = function() {
  document.getElementById("foco").focus();
}
</script>