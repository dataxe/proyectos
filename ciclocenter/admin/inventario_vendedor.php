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
//include ("../registro_movimientos/registro_cierre_caja.php");

$mostrar_datos_sql = "SELECT * FROM inventario order by nombre_valor asc";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$matriz_consulta = mysql_fetch_assoc($consulta);

$suma_total = "SELECT sum(total) as total_suma FROM inventario";
$consulta_suma_total = mysql_query($suma_total, $conectar) or die(mysql_error());
$matriz_suma_total = mysql_fetch_assoc($consulta_suma_total);
$total_valor = $matriz_consulta['nombre_valor'] * $matriz_consulta['numero'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<br>
<center>
<table id="table">
<tr>
<td><div align="center"><strong>C&oacute;digo</strong></div></td>
<td><div align="center"><strong>Valor</strong></div></td>
<td><div align="center"><strong>Numero</strong></div></td>
<td><div align="center"><strong>Total</strong></div></td>
</tr>
<?php do { ?>
<tr>
<td ><?php echo $matriz_consulta['cod_inventario']; ?></td>
<td ><?php echo $matriz_consulta['nombre_valor']; ?></td>
<td ><?php echo $matriz_consulta['numero']; ?></td>
<td ><?php echo $matriz_consulta['total']; ?></td>
</tr>
<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
</table>
<br>
<center>
<table id="table">
<tr>
<td><div align="center"><strong>Total</strong></div></td>
</tr>
<tr>
<td ><?php echo $matriz_suma_total['total_suma']; ?></td>
</tr>
</table>