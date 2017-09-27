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

$mostrar_datos_sql = "SELECT notificacion_alerta.nombre_productos, productos.unidades_faltantes, notificacion_alerta.nombre_notificacion_alerta, Notificacion_alerta.cod_productos_var, 
notificacion_alerta.fecha_dia, notificacion_alerta.fecha_hora, notificacion_alerta.cod_notificacion_alerta, notificacion_alerta.nombre_clientes, 
notificacion_alerta.nombre_proveedores, notificacion_alerta.cod_factura FROM notificacion_alerta LEFT JOIN productos ON (productos.cod_productos_var = notificacion_alerta.cod_productos_var)";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);
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
<br>
<td><strong><font color='yellow'>TODAS LAS ALERTAS</font></strong> <a href="../modificar_eliminar/eliminar_todas_alertas.php?verificacion=todo"><img src=../imagenes/eliminar.png alt="eliminar"></a></td><br><br>
</center>

<center>
<table width="90%">
<tr>
<td align="center"><strong>ALERTA</strong></td>
<td align="center"><strong>ENTIDAD</strong></td>
<td align="center"><strong>CLIENTE</strong></td>
<td align="center"><strong>PROVEEDOR</strong></td>
<td align="center"><strong>FACTURA</strong></td>
<td align="center"><strong>COD PRODUCTO</strong></td>
<td align="center"><strong>UND INV</strong></td>
<td align="center"><strong>FECHA-REG</strong></td>
<td align="center"><strong>HORA</strong></td>
<td align="center"><strong>ELM</strong></td>
</tr>
<?php do { 
$cod_notificacion_alerta = $datos['cod_notificacion_alerta'];
?>
<tr>
<td><font size='2'><?php echo $datos['nombre_notificacion_alerta'];?></font></td>
<td><font size='2'><?php echo $datos['nombre_productos']; ?></font></td>
<td><font size='2'><?php echo $datos['nombre_clientes']; ?></font></td>
<td><font size='2'><?php echo $datos['nombre_proveedores']; ?></font></td>
<td align="right"><font size='2'><?php echo $datos['cod_factura']; ?></font></td>
<td><font size='2'><?php echo $datos['cod_productos_var']; ?></font></td>
<td align="center"><font size='2'><?php echo $datos['unidades_faltantes']; ?></font></td>
<td align="center"><font size='2'><?php echo $datos['fecha_dia']; ?></font></td>
<td align="center"><font size='2'><?php echo $datos['fecha_hora']; ?></font></td>
<td align="center"><a href="../modificar_eliminar/eliminar_todas_alertas.php?verificacion=uno&cod_notificacion_alerta=<?php echo $cod_notificacion_alerta?>"><img src=../imagenes/eliminar.png alt="eliminar"></a></td>
</tr>
<?php } while ($datos = mysql_fetch_assoc($consulta)); ?>
</table>