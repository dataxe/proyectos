<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar);

include("../session/funciones_admin.php");
//include("../notificacion_alerta/mostrar_noficacion_alerta.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
  } else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<?php
$buscar = mysql_escape_string($_POST['buscar']);

$mostrar_datos_sql = "SELECT cod_facturas_cargadas_inv, cod_productos, cod_factura, nombre_productos, unidades_faltantes, precio_venta, 
detalles, fecha, fecha_hora, vendedor, unidades_total FROM facturas_cargadas_inv WHERE (cod_productos = '$buscar') ORDER BY fecha DESC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);
$tota_reg = mysql_num_rows($consulta);

echo "<font color='yellow'><strong>".$tota_reg." Resultados para: ".$buscar."</strong></font><br>";
?>
<br>
<center>
<table width='90%'>
<tr>
<td align="center"><strong>CODIGO</strong></td>
<td align="center"><strong>NOMBRE</strong></td>
<td align="center"><strong>UND COMPRA</strong></td>
<td align="center"><strong>UND INVENT (ANTES)</strong></td>
<td align="center"><strong>UND INVENT (DESP)</strong></td>
<td align="center"><strong>FECHA</strong></td>
<td align="center"><strong>HORA</strong></td>
<td align="center"><strong>VENDEDOR</strong></td>
<td align="center"><strong>FACTURA</strong></td>
</tr>
<?php do { 
$cod_facturas_cargadas_inv = $datos['cod_facturas_cargadas_inv'];
$cod_productos = $datos['cod_productos'];
$cod_factura = $datos['cod_factura'];
$cod_clientes = $datos['cod_clientes'];
$nombre_productos = $datos['nombre_productos'];
$unidades_faltantes = $datos['unidades_faltantes'];
$precio_venta = $datos['precio_venta'];
$detalles = $datos['detalles'];
$fecha = $datos['fecha'];
$fecha_dmy = date("d/m/Y", $fecha);
$fecha_hora = $datos['fecha_hora'];
$vendedor = $datos['vendedor'];
$unidades_total = $datos['unidades_total'];
$suma_nuevos_viejos = $unidades_total + $unidades_faltantes;
?>
<td><font size ='3px'><?php echo $cod_productos; ?></font></td>
<td><font size ='3px'><?php echo $nombre_productos; ?></font></td>
<td align="center"><font size ='3px'><?php echo $unidades_total; ?></font></td>
<td align="center"><font size ='3px'><?php echo $unidades_faltantes; ?></font></td>
<td align="center"><font size ='3px'><?php echo $suma_nuevos_viejos; ?></font></td>
<td align="center"><font size ='3px'><?php echo $fecha_dmy; ?></font></td>
<td align="center"><font size ='3px'><?php echo $fecha_hora; ?></font></td>
<td align="center"><font size ='3px'><?php echo $vendedor; ?></font></td>
<td align="center"><font size ='3px'><?php echo $cod_factura; ?></font></td>
</tr>
<?php
}
while ($datos = mysql_fetch_assoc($consulta)); ?>
</table>
<?php
?>
