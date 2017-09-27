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
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");

//$mostrar_datos_sql = "SELECT * FROM productos WHERE unidades_faltantes <> und_orig AND vendedor <> '$cuenta_actual' order by fechas_dia DESC";
$mostrar_datos_sql = "SELECT * FROM productos WHERE unidades_faltantes <> und_orig order by fechas_dia DESC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<center><strong><font color='yellow' size='5px'>PRODUCTOS MODIFICADOS</font></strong></center>
<br>
<center>
<table width='100%'>
<tr>
<td align="center"><font size='2px'><strong>CODIGO</strong></font></td>
<td align="center"><font size='2px'><strong>PRODUCTO</strong></font></td>
<td align="center"><font size='2px'><strong>UND ANT</strong></font></td>
<td align="center"><font size='2px'><strong>UND AGREG</strong></font></td>
<td align="center"><font size='2px'><strong>UNDS</strong></font></td>
<td align="center"><font size='2px'><strong>PRECIO COMPRA</strong></font></td>
<td align="center"><font size='2px'><strong>PRECIO VENTA</strong></font></td>
<td align="center"><font size='2px'><strong>CUENTA</strong></font></td>
<td align="center"><font size='2px'><strong>FECHA</strong></font></td>
<td align="center"><font size='2px'><strong>HORA</strong></font></td>
</tr>
<?php do { ?>
<tr>
<td><font size='2px'><?php echo $datos['cod_productos_var']; ?></font></td>
<td><font size='2px'><?php echo $datos['nombre_productos']; ?></font></td>
<td align="center"><font size='2px'><?php echo $datos['und_orig']; ?></font></td>
<td align="center"><font size='2px'><?php echo $datos['unidades_faltantes'] - $datos['und_orig']; ?></font></td>
<td align="center"><font size='2px'><?php echo $datos['unidades_faltantes']; ?></font></td>
<td align="right"><font size='2px'><?php echo number_format($datos['precio_costo']); ?></font></td>
<td align="right"><font size='2px'><?php echo number_format($datos['precio_venta']); ?></font></td>
<td align="center"><font size='2px'><?php echo $datos['vendedor']; ?></font></td>
<td align="center"><font size='2px'><?php echo $datos['fechas_anyo']; ?></font></td>
<td align="center"><font size='2px'><?php echo $datos['fechas_hora']; ?></font></td>
</tr>
<?php } while ($datos = mysql_fetch_assoc($consulta)); ?>
</table>
</body>
</html>