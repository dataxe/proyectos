<?php error_reporting(E_ALL ^ E_NOTICE);
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

$nivel_acceso = '3';
if ($seguridad_acceso['cod_seguridad'] <> $nivel_acceso) {
header("Location:../admin/acceso_denegado.php");
}
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");

$fecha = mysql_escape_string($_GET['fecha']);
$mostrar_datos_sql = "SELECT * FROM egresos WHERE fecha_mes = '$fecha' order by fecha_invert DESC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);

$datos_sql = "SELECT SUM(costo) AS costo FROM egresos WHERE fecha_mes = '$fecha' order by fecha_invert DESC";
$consulta_dat = mysql_query($datos_sql, $conectar) or die(mysql_error());
$total_egreso = mysql_fetch_assoc($consulta_dat);

$pagina = $_SERVER['PHP_SELF'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<center>
<td><strong><font color='yellow' size='+2'>EGRESOS: </font></strong></td><br><br>
<td>
<a href="../admin/agregar_egresos.php?pagina=<?php echo $pagina?>"><FONT color='white'>AGREGAR NUEVO EGRESO</FONT></a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="../admin/agregar_concepto_egresos.php"><FONT color='white'>AGREGAR NUEVO CONCEPTO DE EGRESO</FONT></a>
</td>

<?php require_once("menu_egresos.php");?>
</center>
<br>
<center>
<form method="GET" name="formulario" action="<?php echo $formulario_agregar; ?>">
<table>
<tr>
<td align="center"><strong>MES</strong></td>
<td><select name="fecha" require autofocus>
<?php $sql_consulta="SELECT DISTINCT fecha_mes FROM egresos ORDER BY fecha_invert DESC";
$resultado = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option style="font-size:20px" value="<?php echo $contenedor['fecha_mes'] ?>"><?php echo $contenedor['fecha_mes']?></option>
<?php }?></select></td>
<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td bordercolor="1"><input type="submit" id="boton1" value="Ver"></td>
</tr>
</table>
</form>
<?php
if (isset($_GET['fecha'])) {
echo "<font color ='yellow' size='+3'>RESULTADOS PARA: ".$fecha."</font>";
echo "<BR><BR><font color ='yellow' size='+3'><table>TOTAL: ".number_format($total_egreso['costo'], 0, ",", ".")."</table>";
?>
<center>
<table width="85%">
<tr>
<td align="center"><strong>ELM</strong></td>
<td align="center"><strong>CONCEPTO</strong></td>
<td align="center"><strong>COSTO</strong></td>
<td align="center"><strong>COMENTARIO</strong></td>
<td align="center"><strong>CENTRO COSTO</strong></td>
<td align="center"><strong>FECHA</strong></td>
<td align="center"><strong>EDIT</strong></td>
</tr>
<?php do { ?>
<tr>
<td ><a href="../modificar_eliminar/eliminar_egresos.php?cod_egresos=<?php echo $datos['cod_egresos']?>&fecha=<?php echo $fecha?>&pagina=<?php echo $pagina?>"><center><img src=../imagenes/eliminar.png alt="eliminar"></center></a></td>
<td ><?php echo $datos['conceptos']; ?></td>
<td align="right"><?php echo number_format($datos['costo'], 0, ",", "."); ?></td>
<td align="justify"><?php echo $datos['comentarios']; ?></td>
<td align="center"><?php echo $datos['nombre_ccosto']; ?></td>
<td align="center"><?php echo $datos['fecha_anyo']; ?></td>
<td ><a href="../modificar_eliminar/modificar_egresos.php?cod_egresos=<?php echo $datos['cod_egresos']?>&fecha=<?php echo $fecha?>&pagina=<?php echo $pagina?>"><center><img src=../imagenes/actualizar.png alt="Actualizar"></center></a></td>
</tr>
<?php } while ($datos = mysql_fetch_assoc($consulta)); ?>
</table>
<?php
} else {
}
?>
</body>
</html>
<?php mysql_free_result($consulta);