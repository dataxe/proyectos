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
$data = explode('-', $buscar);

$fecha_mes = $data[0];
$vendedor = $data[1];

$mostrar = $fecha_mes.' - '.$vendedor;

$mostrar_datos_sql = "SELECT * FROM ventas WHERE fecha_mes = '$fecha_mes' AND vendedor = '$vendedor' AND porcentaje_vendedor = 'si' ORDER BY fecha DESC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);

$sql = "SELECT sum(vlr_total_venta) as vlr_total_venta FROM ventas WHERE fecha_mes = '$fecha_mes' AND vendedor = '$vendedor' AND porcentaje_vendedor = 'si' ORDER BY fecha DESC";
$consultan = mysql_query($sql, $conectar) or die(mysql_error());
$matriz = mysql_fetch_assoc($consultan);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<center>
<center>
<td><strong><font color='white'>RAYAS: </font></strong></td><br><br>
<table>
<td><a href="../admin/ventas_barras_diario.php"><font size='2' color='red'>DIARIO</font></a></td>
<td><br></td>
<td><a href="../admin/cambiar_porcentaje_rayas.php"><font size='2' color='red'>(CAMBIAR PORCENTAJE)</font></a></td>
<td><br></td>
<td><a href="../admin/ver_todas_rayas.php"><font size='2' color='red'>(VER TODOAS LAS RAYAS)</font></a></td>
</table>
<br>
<form method="post" name="formulario" action="">
<table align="center">
<td nowrap align="right">VENTAS MES - VENDEDOR:</td>
<td bordercolor="0">
<select name="fecha">
<?php $sql_consulta1="SELECT DISTINCT fecha_mes, vendedor FROM ventas ORDER BY fecha DESC";
$resultado = mysql_query($sql_consulta1, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option value="<?php echo $contenedor['fecha_mes'].'-'.$contenedor['vendedor'] ?>"><?php echo $contenedor['fecha_mes'].' - '.$contenedor['vendedor'] ?></option>
<?php }?>
</select></td>
</tr>
</table>
<br>
<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td bordercolor="1"><input type="submit" id="boton1" value="Consultar Ventas"></td>
</tr>
</table>
</form>
</center>
<center>
<?php echo "<strong><font color='yellow' size='+3'>PORCENTAJE RAYAS: ".$mostrar."</font></strong>";

$mostrar_datos = "SELECT * FROM rayas WHERE cod_rayas = '1'";
$consultance = mysql_query($mostrar_datos, $conectar) or die(mysql_error());
$porct_raya = mysql_fetch_assoc($consultance);

$porcentaje = $porct_raya['porcentaje_rayas'];
?>
<table width="100%">
<tr>
<td><div align="center"><strong>C&Oacute;DIGO</strong></div></td>
<td><div align="center"><strong>NOMBRE</strong></div></td>
<td><div align="center"><strong>UNDS</strong></div></td>
<td><div align="center"><strong>P.VENTA</strong></div></td>
<td><div align="center"><strong>TOTAL</strong></div></td>
<td><div align="center"><strong>FACTURA</strong></div></td>
<td><div align="center"><strong>FECHA VENTA</strong></div></td>
<td><div align="center"><strong>VENDEDOR</strong></div></td>
<td><div align="center"><strong>FECHA PAGO</strong></div></td>
<td><div align="center"><strong>PAGO A</strong></div></td>
<td><div align="center"><strong>HORA</strong></div></td>
<td><div align="center"><strong>IP</strong></div></td>
</tr>
<?php do { ?>
<tr>
<td ><?php echo $datos['cod_productos']; ?></td>
<td ><?php echo $datos['nombre_productos']; ?></td>
<td align="center"><?php echo $datos['unidades_vendidas']; ?></td>
<td align="right"><?php echo number_format($datos['precio_venta'], 0, ",", "."); ?></td>
<td align="right"><?php echo number_format($datos['vlr_total_venta'], 0, ",", "."); ?></td>
<td align="center"><?php echo $datos['cod_factura']; ?></td>
<td align="center"><?php echo $datos['fecha_anyo']; ?></td>
<td align="center"><?php echo $datos['vendedor']; ?></td>
<td align="center"><?php echo $datos['fecha_orig']; ?></td>
<td align="center"><?php echo $datos['cuenta']; ?></td>
<td align="center"><?php echo $datos['fecha_hora']; ?></td>
<td align="center"><?php echo $datos['ip']; ?></td>
</tr>
<?php } while ($datos = mysql_fetch_assoc($consulta)); ?>
</table>
<table>
<td align="center"><font color='red' size='+3'>TOTAL:</font></td>
<td align="center"><font color='yellow' size='+3'><?php echo number_format($matriz['vlr_total_venta'], 0, ",", "."); ?></font></td>

<td align="center"><font color='red' size='+3'>GANANCIA <?php echo $porcentaje?>%:</font></td>
<td align="center"><font color='yellow' size='+3'><?php echo number_format($matriz['vlr_total_venta'] * ($porcentaje / 100), 0, ",", "."); ?></font></td>
</table>