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

$nivel_acceso = '3';
if ($seguridad_acceso['cod_seguridad'] <> $nivel_acceso) {
header("Location:../admin/acceso_denegado.php");
}
$buscar = $_POST['fecha'];
$mostrar_datos_sql = "SELECT * FROM ventas WHERE anyo = '$buscar' AND unidades_vendidas <> '0' ORDER BY fecha_hora DESC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$matriz_consulta = mysql_fetch_assoc($consulta);

require_once("menu_ventas.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<center>
<form method="post" id="table" name="formulario" action="">
<table align="center">
<td nowrap align="right">Ventas Anuales:</td>
<td bordercolor="0">
<select name="fecha">
<?php $sql_consulta1="SELECT DISTINCT anyo FROM ventas ORDER BY fecha DESC";
$resultado = mysql_query($sql_consulta1, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option value="<?php echo $contenedor['anyo'] ?>"><?php echo $contenedor['anyo'] ?></option>
<?php }?>
</select></td></td>
<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td bordercolor="1"><input type="submit" value="Consultar Ventas"></td>
</tr>
</table>
</form>
</center>
<center>
<?php echo "<font color='yellow' size='+3'>A&ntilde;o: ".$buscar."</font>";?>
<table id="table" width="100%">
<tr>
<td align="center"><strong>C&Oacute;DIGO</strong></td>
<td align="center"><strong>PRODUCTO</strong></td>
<td align="center"><strong>UND</strong></td>
<!--<td align="center"><strong>MET</strong></td>-->
<td align="center"><strong>P.VENTA</strong></td>
<td align="center"><strong>TOTAL</strong></td>
<td align="center"><strong>%DESC</strong></td>
<td align="center"><strong>FACTURA</strong></td>
<td align="center"><strong>FECHA VENTA</strong></td>
<td align="center"><strong>VENDEDOR</strong></td>
<td align="center"><strong>FECHA PAGO</strong></td>
<td align="center"><strong>PAGO A</strong></td>
<td align="center"><strong>HORA</strong></td>
<td align="center"><strong>IP</strong></td>
</tr>
<?php do { ?>
<tr>
<td ><?php echo $matriz_consulta['cod_productos']; ?></td>
<td ><?php echo $matriz_consulta['nombre_productos']; ?></td>
<td align="right"><?php echo $matriz_consulta['unidades_vendidas']; ?></td>
<!--<td align="center"><?php echo $matriz_consulta['detalles']; ?></td>-->
<td align="right"><?php echo $matriz_consulta['precio_venta']; ?></td>
<td align="right"><?php echo $matriz_consulta['vlr_total_venta']; ?></td>
<td align="right"><?php echo $matriz_consulta['descuento_ptj']; ?></td>
<td align="center"><?php echo $matriz_consulta['cod_factura']; ?></td>
<td align="right"><?php echo $matriz_consulta['fecha_orig']; ?></td>
<td align="center"><?php echo $matriz_consulta['vendedor']; ?></td>
<td align="right"><?php echo $matriz_consulta['fecha_anyo']; ?></td>
<td align="center"><?php echo $matriz_consulta['cuenta']; ?></td>
<td align="right"><?php echo $matriz_consulta['fecha_hora']; ?></td>
<td align="right"><?php echo $matriz_consulta['ip']; ?></td>
</tr>
<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
</table>