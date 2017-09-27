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
date_default_timezone_set("America/Bogota");
require("funcion_verificar.php");
$requerir_funcion = new bloquear_multiple_intento;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<br>
<body>
<?php
$numero_factura = $_POST['numero_factura'];

$suma_temporal = "SELECT  Sum(vlr_total_venta) As total_venta FROM temporal WHERE vendedor = '$cuenta_actual'";
$consulta_temporal = mysql_query($suma_temporal, $conectar) or die(mysql_error());
$suma = mysql_fetch_assoc($consulta_temporal);

$descuento_factura = $_POST['descuento_factura'];
$iva = $_POST['iva'];
$vlr_cancelado = $_POST['vlr_cancelado'];
$cod_clientes = $_POST['cod_clientes'];
$tipo_pago = $_POST['tipo_pago'];
$flete = '0';
$vlr_vuelto = $vlr_cancelado - ($suma['total_venta'] - $descuento_factura);
$estado = 'cerrado';
$venta_total = $suma['total_venta'] - $descuento_factura;
$descuento = '0';
$monto_deuda = $_POST['monto_deuda'];
$subtotal = '0';
$vendedor = $cuenta_actual;
$ip = $_SERVER['REMOTE_ADDR'];
$fecha_dia = date("Y/m/d");
$fecha_mes = date("m/Y");
//$fecha_anyo = date("d/m/Y");
$fecha_anyo = $_POST['fecha_anyo'];
$anyo = date("Y");
$fecha_hora = date("H:i:s");

if (isset($_POST['vlr_cancelado']) && ($vlr_cancelado >= $venta_total) && ($tipo_pago == 'contado') && $requerir_funcion->bloquear($_POST['verificador'])) {

for($i=0; $i < $_POST['total_datos']; $i++) {
$cod_productos = $_POST['cod_productos'][$i];

$sql_modificar_consulta = "SELECT * FROM productos WHERE cod_productos_var = '$cod_productos'";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($modificar_consulta);

$nombre_productos = $_POST['nombre_productos'][$i];
$unidades_vendidas = $_POST['unidades_vendidas'][$i];
$precio_compra = $_POST['precio_compra'][$i];
$precio_venta = $_POST['precio_venta'][$i];
$vlr_total_venta = $_POST['vlr_total_venta'][$i];
$vlr_total_compra = $_POST['vlr_total_compra'][$i];
$precio_compra_con_descuento = $_POST['precio_compra_con_descuento'][$i];

$unidades_faltantes = $datos['unidades_faltantes'] - $unidades_vendidas;
$unidades_vendidax = $datos['unidades_vendidas'] + $unidades_vendidas;
$total_mercancia = $datos['precio_compra'] * $unidades_faltantes;
$total_venta = (($datos['unidades_vendidas'] + $unidades_vendidas) * $datos['precio_venta']);
$total_utilidad = ($datos['utilidad'] * $unidades_vendidax);
$porcentaje_vendedor = $datos['porcentaje_vendedor'];

$agregar_reg_ventas = "INSERT INTO ventas (cod_productos, cod_factura, cod_clientes, nombre_productos, unidades_vendidas, precio_compra, precio_venta, vlr_total_venta, 
vlr_total_compra, descuento, precio_compra_con_descuento, vendedor, ip, fecha, fecha_mes, fecha_anyo, anyo, fecha_hora, porcentaje_vendedor)
VALUES ('$cod_productos','$numero_factura', '$cod_clientes', '$nombre_productos','$unidades_vendidas','$precio_compra','$precio_venta','$vlr_total_venta','$vlr_total_compra',
'$descuento','$vlr_total_venta','$vendedor','$ip','$fecha_dia','$fecha_mes','$fecha_anyo','$anyo','$fecha_hora','$porcentaje_vendedor')";
$resultado_reg_ventas = mysql_query($agregar_reg_ventas, $conectar) or die(mysql_error());

/*$agregar_reg_facturacion = "INSERT INTO facturacion (cod_producto, cod_facturacion, cod_clientes, nombre_productos, cantidad, precio_compra, vlr_unitario, vlr_total, descuento, 
	vendedor, fecha, fecha_mes, fecha_anyo, anyo, porcentaje_vendedor)
VALUES ('$cod_productos','$numero_factura','$cod_clientes','$nombre_productos','$unidades_vendidas','$precio_compra','$precio_venta','$vlr_total_venta',
'$descuento','$vendedor','$fecha_dia','$fecha_mes','$fecha_anyo','$fecha_hora','$porcentaje_vendedor')";
$resultado_reg_facturacion = mysql_query($agregar_reg_facturacion, $conectar) or die(mysql_error());*/

$actualiza_productos = sprintf("UPDATE productos SET unidades_faltantes = '$unidades_faltantes', unidades_vendidas = '$unidades_vendidax', 
total_utilidad = '$total_utilidad', total_mercancia = '$total_mercancia', total_venta = '$total_venta', descuento = '$descuento' WHERE cod_productos_var = '$cod_productos'");
$resultado_actualiza_productos = mysql_query($actualiza_productos, $conectar) or die(mysql_error());
}
//cierre de la llave para los registros que son recorridos por el cliclo for

$agregar_regis = sprintf("UPDATE info_impuesto_facturas SET iva = '$iva', flete = $flete, cod_clientes = $cod_clientes, descuento = '$descuento_factura', 
estado = '$estado', vendedor = '$cuenta_actual', fecha_dia = '$fecha_dia', fecha_mes = '$fecha_mes', fecha_anyo = '$fecha_anyo', 
anyo = '$anyo', fecha_hora = '$fecha_hora', vlr_cancelado = '$vlr_cancelado', vlr_vuelto = '$vlr_vuelto', cod_factura = '$numero_factura' 
WHERE vendedor = '$cuenta_actual' AND estado = 'abierto'");
$resultado_regis = mysql_query($agregar_regis, $conectar) or die(mysql_error());

$suma_temporal = "SELECT  Sum(vlr_total_venta) As total_venta, cod_factura FROM temporal WHERE vendedor = '$cuenta_actual'";
$consulta_temporal = mysql_query($suma_temporal, $conectar) or die(mysql_error());
$suma = mysql_fetch_assoc($consulta_temporal);
$cod_factura = $suma['cod_factura'];
?>
<center>
<table>
<td><font color='green' size= "+2">Subtotal: </font></td><td><font color='green' size= "+2"><?php echo number_format($suma['total_venta']); ?></td>
<tr></tr>
<td><font color='green' size= "+2">Descuento: </font></td><td><font color='green' size= "+2"><?php echo number_format($descuento_factura); ?></td>
<tr></tr>
<td><font color='yellow' size= "+3">Total Venta: </font></td><td><font color='yellow' size= "+3"><?php echo number_format($venta_total); ?></td>
<tr></tr>
<td><font color='yellow' size= "+3">Recibido: </font></td><td><font color='yellow' size= "+3"><?php echo number_format($vlr_cancelado); ?></td>
<tr></tr>
<td><font color='yellow' size= "+4">Cambio: </font></td><td><font color='yellow' size= "+4"><?php echo number_format($vlr_cancelado - $venta_total); ?></td>
</table>
<br>
<form method="post" action="../admin/factura_eletronica.php">
<input type="image" id ="foco" src="../imagenes/listo.png" name="listo" value="listo" />
<a href="../admin/imprimir_factura.php?numero_factura=<?php echo $numero_factura?>" target="_blank"><img src=../imagenes/imprimir_.png alt="imprimir"></a>
</form>
</center>
<?php
$borrar_sql = sprintf("DELETE FROM temporal WHERE vendedor = '$cuenta_actual'");
$Result1 = mysql_query($borrar_sql, $conectar) or die(mysql_error());
} //llave de cierre del if de vender

elseif (($_POST['vlr_cancelado'] =='0') && ($tipo_pago == 'credito') && $requerir_funcion->bloquear($_POST['verificador'])) {

for($i=0; $i < $_POST['total_datos']; $i++) {
$cod_productos = $_POST['cod_productos'][$i];

$sql_modificar_consulta = "SELECT * FROM productos WHERE cod_productos_var = '$cod_productos'";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($modificar_consulta);

$nombre_productos = $_POST['nombre_productos'][$i];
$unidades_vendidas = $_POST['unidades_vendidas'][$i];
$precio_compra = $_POST['precio_compra'][$i];
$precio_venta = $_POST['precio_venta'][$i];
$vlr_total_venta = $_POST['vlr_total_venta'][$i];
$vlr_total_compra = $_POST['vlr_total_compra'][$i];
$precio_compra_con_descuento = $_POST['precio_compra_con_descuento'][$i];

$unidades_faltantes = $datos['unidades_faltantes'] - $unidades_vendidas;
$unidades_vendidax = $datos['unidades_vendidas'] + $unidades_vendidas;
$total_mercancia = $datos['precio_compra'] * $unidades_faltantes;
$total_venta = (($datos['unidades_vendidas'] + $unidades_vendidas) * $datos['precio_venta']);
$total_utilidad = ($datos['utilidad'] * $unidades_vendidax);
$porcentaje_vendedor = $datos['porcentaje_vendedor'];

/*$agregar_reg_facturacion = "INSERT INTO facturacion (cod_producto, cod_facturacion, cod_clientes, nombre_productos, cantidad, precio_compra, vlr_unitario, vlr_total, descuento, 
vendedor, fecha, fecha_mes, fecha_anyo, anyo, porcentaje_vendedor)
VALUES ('$cod_productos','$numero_factura','$cod_clientes','$nombre_productos','$unidades_vendidas','$precio_compra','$precio_venta','$vlr_total_venta',
'$descuento','$vendedor','$fecha_dia','$fecha_mes','$fecha_anyo','$fecha_hora','$porcentaje_vendedor')";
$resultado_reg_facturacion = mysql_query($agregar_reg_facturacion, $conectar) or die(mysql_error());*/

$actualiza_productos = sprintf("UPDATE productos SET unidades_faltantes = '$unidades_faltantes', unidades_vendidas = '$unidades_vendidax', 
total_utilidad = '$total_utilidad', total_mercancia = '$total_mercancia', total_venta = '$total_venta', descuento = '$descuento' WHERE cod_productos_var = '$cod_productos'");
$resultado_actualiza_productos = mysql_query($actualiza_productos, $conectar) or die(mysql_error());

$agregar_reg_productos_fiados = "INSERT INTO productos_fiados (cod_productos, cod_factura, cod_clientes, nombre_productos, unidades_vendidas, precio_compra, precio_venta, vlr_total_venta, 
vlr_total_compra, descuento, precio_compra_con_descuento, vendedor, ip, fecha, fecha_mes, fecha_anyo, anyo, fecha_hora, porcentaje_vendedor)
VALUES ('$cod_productos','$numero_factura', '$cod_clientes', '$nombre_productos','$unidades_vendidas','$precio_compra','$precio_venta','$vlr_total_venta','$vlr_total_compra',
'$descuento','$vlr_total_venta','$vendedor','$ip','$fecha_dia','$fecha_mes','$fecha_anyo','$anyo','$fecha_hora','$porcentaje_vendedor')";
$resultado_productos_fiados = mysql_query($agregar_reg_productos_fiados, $conectar) or die(mysql_error());
}//cierre de la llave para los registros que son recorridos por el cliclo for

$agregar_reg_ventas = "INSERT INTO cuentas_cobrar (cod_clientes, cod_factura, monto_deuda, subtotal, descuento, vendedor, fecha_pago)
VALUES ('$cod_clientes','$numero_factura','$monto_deuda','$subtotal','$descuento_factura','$vendedor','$fecha_anyo')";
$resultado_ventas = mysql_query($agregar_reg_ventas, $conectar) or die(mysql_error());

$agregar_regis = sprintf("UPDATE info_impuesto_facturas SET iva = '$iva', flete = $flete, cod_clientes = $cod_clientes, descuento = '$descuento_factura', 
estado = '$estado', vendedor = '$cuenta_actual', fecha_dia = '$fecha_dia', fecha_mes = '$fecha_mes', fecha_anyo = '$fecha_anyo', 
anyo = '$anyo', fecha_hora = '$fecha_hora', vlr_cancelado = '$vlr_cancelado', vlr_vuelto = '$vlr_vuelto', cod_factura = '$numero_factura' 
WHERE vendedor = '$cuenta_actual' AND estado = 'abierto'");
$resultado_regis = mysql_query($agregar_regis, $conectar) or die(mysql_error());
?>
<center>
<table>
<td><font color='green' size= "+2">Subtotal: </font></td><td><font color='green' size= "+2"><?php echo number_format($suma['total_venta']); ?></td>
<tr></tr>
<td><font color='green' size= "+2">Descuento: </font></td><td><font color='green' size= "+2"><?php echo number_format($descuento_factura); ?></td>
<tr></tr>
<td><font color='yellow' size= "+3">Total Venta: </font></td><td><font color='yellow' size= "+3"><?php echo number_format($venta_total); ?></td>
<tr></tr>
<td><font color='yellow' size= "+3">Recibido: </font></td><td><font color='yellow' size= "+3"><?php echo number_format($vlr_cancelado); ?></td>
</table>
<br>
<form method="post" action="../admin/factura_eletronica.php">
<input type="image" id ="foco" src="../imagenes/listo.png" name="listo" value="listo" />
<a href="../admin/imprimir_factura.php?numero_factura=<?php echo $numero_factura?>" target="_blank"><img src=../imagenes/imprimir_.png alt="eliminar"></a>

</form>
</center>
<?php
$borrar_sql = sprintf("DELETE FROM temporal WHERE vendedor = '$cuenta_actual'");
$Result1 = mysql_query($borrar_sql, $conectar) or die(mysql_error());

//echo '<META HTTP-EQUIV="REFRESH" CONTENT="0.1; factura_eletronica.php">';
} //llave de cierre del if de vender

elseif ($vlr_cancelado < $venta_total) {
echo "<center><font color='yellow' size= '+3'><br><img src=../imagenes/advertencia.gif alt='Advertencia'> El valor Recibido es menor que el valor total de la venta. 
 <img src=../imagenes/advertencia.gif alt='Advertencia'></font><center>";
?>
<table>
<td><font color='yellow' size= "+3">Total venta: </font></td><td><font color='yellow' size= "+3"><?php echo number_format($venta_total); ?></td>
<tr></tr><td><font color='yellow' size= "+3">Recibido: </font></td><td><font color='yellow' size= "+3"><?php echo number_format($vlr_cancelado); ?></td>
</table>
<br>
<form method="post" action="../admin/factura_eletronica.php">
<input type="image" id ="foco" src="../imagenes/regresar.png" name="listo" value="listo" />
</form>
</center>
<?php
}
else {
echo '<META HTTP-EQUIV="REFRESH" CONTENT="0.1; factura_eletronica.php">';
}// llave de cierre del else
?>
</body>
</html>
<script>
window.onload = function() {
document.getElementById("foco").focus();
}
</script>