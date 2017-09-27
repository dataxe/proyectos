<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
$cuenta_actual = addslashes($_SESSION['usuario']);
include("../session/funciones_admin.php");
//include("../notificacion_alerta/mostrar_noficacion_alerta.php");
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
/*
$datos_factura = "SELECT * FROM cargar_factura_temporal WHERE vendedor = '$cuenta_actual'";
$consulta = mysql_query($datos_factura, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);
$total_datos = mysql_num_rows($consulta);
*/
$sql = "SELECT * FROM ventas";
$consulta = mysql_query($sql, $conectar) or die(mysql_error());
$total_datos = mysql_num_rows($consulta);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>ALMACEN</title>
</head>
<br>
<table width="100%">
<tr>
<td>cod_ventas</td>
<td>cod_productos</td>
<td>cod_factura</td>
<td>cod_clientes</td>
<td>tipo_pago</td>
<td>nombre_productos</td>
<td>unidades_vendidas</td>
<td>und_vend_orig</td>
<td>devoluciones</td>
<td>precio_compra</td>
<td>precio_costo</td>
<td>precio_venta</td>
<td>vlr_total_venta</td>
<td>vlr_total_compra</td>
<td>tipo_venta</td>
<td>iva</td>
<td>iva_v</td>
<td>detalles</td>
<td>nombre_lineas</td>
<td>nombre_ccosto</td>
<td>cod_base_caja</td>
<td>descuento</td>
<td>descuento_ptj</td>
<td>precio_compra_con_descuento</td>
<td>porcentaje_vendedor</td>
<td>vendedor</td>
<td>cuenta</td>
<td>ip</td>
<td>fecha_devolucion</td>
<td>hora_devolucion</td>
<td>fecha_orig</td>
<td>fecha</td>
<td>fecha_mes</td>
<td>fecha_anyo</td>
<td>anyo</td>
<td>fecha_hora</td>
</tr>
<?php
while ($datos = mysql_fetch_assoc($consulta)) {
$cod_ventas = $datos['cod_ventas'];
$cod_productos = $datos['cod_productos'];
$cod_factura = $datos['cod_factura'];
$cod_clientes = $datos['cod_clientes'];
$tipo_pago = $datos['tipo_pago'];
$nombre_productos = $datos['nombre_productos'];
$unidades_vendidas = $datos['unidades_vendidas'];
$und_vend_orig = $datos['und_vend_orig'];
$devoluciones = $datos['devoluciones'];
$precio_compra = $datos['precio_compra'];
$precio_costo = $datos['precio_costo'];
$precio_venta = $datos['precio_venta'];
$vlr_total_venta = $datos['vlr_total_venta'];
$vlr_total_compra = $datos['vlr_total_compra'];
$tipo_venta = $datos['tipo_venta'];
$iva = $datos['iva'];
$iva_v = $datos['iva_v'];
$detalles = $datos['detalles'];
$nombre_lineas = $datos['nombre_lineas'];
$nombre_ccosto = $datos['nombre_ccosto'];
$cod_base_caja = $datos['cod_base_caja'];
$descuento = $datos['descuento'];
$descuento_ptj = $datos['descuento_ptj'];
$precio_compra_con_descuento = $datos['precio_compra_con_descuento'];
$porcentaje_vendedor = $datos['porcentaje_vendedor'];
$vendedor = $datos['vendedor'];
$cuenta = $datos['cuenta'];
$ip1 = $datos['ip'];
$ip = '1';
$fecha_devolucion = $datos['fecha_devolucion'];
$hora_devolucion = $datos['hora_devolucion'];
$fecha_orig = $datos['fecha_orig'];
//$fecha = $datos['fecha'];
$fecha_mes = $datos['fecha_mes'];
$fecha_anyo = $datos['fecha_anyo'];
$vector_fecha_anyo = explode('/', $fecha_anyo);
$dia_vect = $vector_fecha_anyo[0];
$mes_vect = $vector_fecha_anyo[1];
$anyo_vect = $vector_fecha_anyo[2];
$union_invert = $anyo_vect.'/'.$mes_vect.'/'.$dia_vect;
$fecha = strtotime($union_invert);
$anyo = $datos['anyo'];
$fecha_hora = $datos['fecha_hora'];
?>
<tr>
<td><?php echo $cod_ventas;?></td>
<td><?php echo $cod_productos;?></td>
<td><?php echo $cod_factura;?></td>
<td><?php echo $cod_clientes;?></td>
<td><?php echo $tipo_pago;?></td>
<td><?php echo $nombre_productos;?></td>
<td><?php echo $unidades_vendidas;?></td>
<td><?php echo $und_vend_orig;?></td>
<td><?php echo $devoluciones;?></td>
<td><?php echo $precio_compra;?></td>
<td><?php echo $precio_costo;?></td>
<td><?php echo $precio_venta;?></td>
<td><?php echo $vlr_total_venta;?></td>
<td><?php echo $vlr_total_compra;?></td>
<td><?php echo $tipo_venta;?></td>
<td><?php echo $iva;?></td>
<td><?php echo $iva_v;?></td>
<td><?php echo $detalles;?></td>
<td><?php echo $nombre_lineas;?></td>
<td><?php echo $nombre_ccosto;?></td>
<td><?php echo $cod_base_caja;?></td>
<td><?php echo $descuento;?></td>
<td><?php echo $descuento_ptj;?></td>
<td><?php echo $precio_compra_con_descuento;?></td>
<td><?php echo $porcentaje_vendedor;?></td>
<td><?php echo $vendedor;?></td>
<td><?php echo $cuenta;?></td>
<td><?php echo $ip;?></td>
<td><?php echo $fecha_devolucion;?></td>
<td><?php echo $hora_devolucion;?></td>
<td><?php echo $fecha_orig;?></td>
<td><?php echo $fecha;?></td>
<td><?php echo $fecha_mes;?></td>
<td><?php echo $fecha_anyo;?></td>
<td><?php echo $anyo;?></td>
<td><?php echo $fecha_hora;?></td>
</tr>
<?php } ?>
</table>