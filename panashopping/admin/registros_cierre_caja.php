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
//include ("../registro_movimientos/registro_movimientos.php");

$buscar = $_POST['palabra'];
$mostrar_datos_sql = "SELECT * FROM cajas_registros order by fecha_invert DESC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$matriz_consulta = mysql_fetch_assoc($consulta);
$total_datos = mysql_num_rows($consulta);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>ALMACEN</title>
</head>
<body>
<center>
<td><strong><font color='yellow' size='+2'>REGISTROS CIERRE DE CAJA: </font></strong></td><br><br>
<td ><a href="../admin/caja_base.php"><strong><font color='yellow' size='+2'>REGRESAR A CAJA</font></strong></a></td><br><br>
<?php
if ($total_datos <> '0') {
?>
<table id="table" width="95%">
<tr>
<td align="center"><strong>ELIM</strong></td>
<td align="center"><strong>CAJA</strong></td>
<td align="center"><strong>BASE CAJA</strong></td>
<td align="center"><strong>TOTAL VENTA CONTADO</strong></td>
<td align="center"><strong>TOTAL VENTA CREDITO</strong></td>
<td align="center"><strong>TOTAL CAJA</strong></td>
<td align="center"><strong>FECHA CIERRE</strong></td>
<td align="center"><strong>HORA CIERRE</strong></td>
<td align="center"><strong>IP</strong></td>
<td align="center"><strong>CUENTA</strong></td>
<td align="center"><strong>EDIT</strong></td>
</tr>
<?php do { ?>
<tr>
<td><a href="../modificar_eliminar/eliminar_cajas_registros.php?cod_cajas_registros=<?php echo $matriz_consulta['cod_cajas_registros']; ?>"><center><img src=../imagenes/eliminar.png alt="eliminar"></center></a></td>
<td align="center"><font size='4'><?php echo $matriz_consulta['nombre_base_caja']; ?></font></td>
<td align="right"><font size='4'><?php echo number_format($matriz_consulta['valor_caja'], 0, ",", "."); ?></font></td>
<td align="right"><font size='4'><?php echo number_format($matriz_consulta['total_ventas'], 0, ",", "."); ?></font></td>
<td align="right"><font size='4'><?php echo number_format($matriz_consulta['total_venta_credito'], 0, ",", "."); ?></font></td>
<td align="right"><font size='4'><?php echo number_format($matriz_consulta['total_caja'], 0, ",", "."); ?></font></td>
<td align="right"><font size='4'><?php echo $matriz_consulta['fecha'];?></font></td>
<td align="right"><font size='4'><?php echo $matriz_consulta['hora']; ?></font></td>
<td align="right"><font size='4'><?php echo $matriz_consulta['ip'];?></font></td>
<td align="center"><font size='4'><?php echo $matriz_consulta['cuenta']; ?></font></td>
<td><a href="../modificar_eliminar/modificar_registros_cierre_caja.php?cod_cajas_registros=<?php echo $matriz_consulta['cod_cajas_registros']; ?>"><center><img src=../imagenes/actualizar.png alt="actualizar"></center></a></td>
</tr>
<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
</table>
<?php
} else {
}