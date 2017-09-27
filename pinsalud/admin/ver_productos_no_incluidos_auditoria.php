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
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");

$total_incluidos = intval($_POST['total_incluidos']);
$cod_factura = intval($_POST['cod_factura']);
$pagina = $_POST['pagina'].'?cod_factura='.$cod_factura;

for ($i=0; $i < $total_incluidos; $i++) { 

$cod_productos = ($_POST['cod_productos'][$i]);

$mostrar_datos_sql = "SELECT cod_productos_var, nombre_productos, unidades_faltantes FROM productos WHERE cod_productos_var <> '$cod_productos' AND unidades_faltantes <> 0";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);
$total_no_incluidos = mysql_num_rows($consulta);

$datos_en_cero = "SELECT cod_productos_var FROM productos WHERE cod_productos_var <> '$cod_productos' AND unidades_faltantes = 0";
$consulta_cero = mysql_query($datos_en_cero, $conectar) or die(mysql_error());
$datos_cero = mysql_fetch_assoc($consulta_cero);
$total_cero = mysql_num_rows($consulta_cero);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<center>
<br>
<a href="<?php echo $pagina?>"><font color='yellow'>REGRESAR</font></a><br><br>
<table width="70%">
<tr>
<td align="center"><strong><font color='yellow' size='+1'>CODIGO AUDITORIA</font></strong></td>
<td align="center"><strong><font size='+1'>TOTAL NO INCLUIDOS EN AUDITORIA <> 0</font></strong></td>
<td align="center"><strong><font color='yellow' size='+1'>TOTAL NO INCLUIDOS EN AUDITORIA EN CERO</font></strong></td>
<td align="center"><strong><font color='yellow' size='+1'>TOTAL INCLUIDOS EN AUDITORIA</font></strong></td>
<td align="center"><strong><font color='yellow' size='+1'>TOTAL</font></strong></td>
</tr>
<tr>
<td align="center"><font color='yellow' size='+2'><?php echo number_format($cod_factura, 0, ",", "."); ?></font></td>
<td align="center"><font size='+2'><?php echo number_format($total_no_incluidos, 0, ",", "."); ?></font></td>
<td align="center"><font color='yellow' size='+2'><?php echo number_format($total_cero, 0, ",", "."); ?></font></td>
<td align="center"><font color='yellow' size='+2'><?php echo number_format($total_incluidos, 0, ",", "."); ?></font></td>
<td align="center"><font color='yellow' size='+2'><?php echo number_format($total_no_incluidos + $total_cero, 0, ",", "."); ?></font></td>
</tr>
</table>
<br><br>

<table width="70%">
<tr>
<td align="center"><font size='+1'><strong>CODIGO</font></strong></td>
<td align="center"><font size='+1'><strong>PRODUCTO</font></strong></td>
<td align="center"><font size='+1'><strong>UNIDADES</font></strong></td>
</tr>
<?php do { ?>
<tr>
<td><font size='+1'><?php echo $datos['cod_productos_var']; ?></font></td>
<td><font size='+1'><?php echo $datos['nombre_productos']; ?></font></td>
<td align="right"><font size='+1'><?php echo number_format($datos['unidades_faltantes'], 0, ",", "."); ?></font></td>
</tr>
<?php } while ($datos = mysql_fetch_assoc($consulta)); ?>
</table>