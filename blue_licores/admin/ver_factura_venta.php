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

$cod_factura = $_GET['cod_factura'];

$datos_factura = "SELECT * FROM ventas WHERE cod_factura like '$cod_factura'";
$consulta = mysql_query($datos_factura, $conectar) or die(mysql_error());
$matriz_consulta = mysql_fetch_assoc($consulta);

$datos_cliente = "SELECT * FROM ventas, clientes WHERE ventas.cod_clientes = clientes.cod_clientes AND cod_factura = '$cod_factura'";
$consulta_cliente = mysql_query($datos_cliente, $conectar) or die(mysql_error());
$cliente = mysql_fetch_assoc($consulta_cliente);

$datos_total_factura = "SELECT  Sum(vlr_total_venta) as vlr_totl FROM ventas WHERE cod_factura like '$cod_factura'";
$consulta_total = mysql_query($datos_total_factura, $conectar) or die(mysql_error());
$matriz_total_consulta = mysql_fetch_assoc($consulta_total);

$datos_impuesto = "SELECT * FROM info_impuesto_facturas WHERE cod_factura like '$cod_factura'";
$total_impuesto = mysql_query($datos_impuesto, $conectar) or die(mysql_error());
$matriz_impuesto = mysql_fetch_assoc($total_impuesto);
$pagina ="buscar_facturas_fecha";
?>
<br>
<center>
<table id="numero_factura" width="90%">
<td nowrap align="right"><strong>FACTURA N&ordm;:</td>
<td><input type="text" name="numero_factura" value="<?php echo $cod_factura;?>" size="8"></td>

<td nowrap align="right"><strong>FECHA:</td>
<?php $obtener_fecha = "SELECT * FROM ventas WHERE cod_factura = '$cod_factura'";
$consultar_fecha = mysql_query($obtener_fecha, $conectar) or die(mysql_error());
$matriz_fecha = mysql_fetch_assoc($consultar_fecha);
$num_fecha = mysql_num_rows($consultar_fecha); ?>
<td><input type="text" name="fech" value="<?php echo $matriz_fecha['fecha_anyo']; ?>" size="9"></td>

<td nowrap align="right"><strong>CLIENTE:</td>
<td><?php echo $cliente['nombres'].' '.$cliente['apellidos']; ?></td>

<td nowrap align="right"><strong>VENDEDOR:</td>
<td><?php echo $matriz_consulta['vendedor']; ?></td>
 </tr>
</table>
</center>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>ALMACEN</title>
<script language="javascript" src="isiAJAX.js"></script>
<script language="javascript">
var last;
function Focus(elemento, valor) {
$(elemento).className = 'inputon';
last = valor;
}
function Blur(elemento, valor, campo, id) {
$(elemento).className = 'inputoff';
if (last != valor)
myajax.Link('guardar_devoluciones_facturas.php?valor='+valor+'&campo='+campo+'&id='+id);
}
</script>

</head>
<body onLoad="myajax = new isiAJAX();">
<body>
<?php 
$calculo_subtotal = $matriz_total_consulta['vlr_totl'] - $_POST['descuento_factura']; 
$calculo_total = $calculo_subtotal;

$sql = "SELECT * FROM ventas WHERE cod_factura like '$cod_factura'";
$consulta = mysql_query($sql, $conectar) or die(mysql_error());
?>
<!--<form method="post" name="formulario" action="../admin/buscar_imprimir_factura.php" accept-charset="UTF-8" target="_blank">-->
<form name="form1" id="form1" action="#" method="post" style="margin:0;">  
<center>
<table id="table" width="90%">
<input type="hidden" name="numero_factura" value="<?php echo $cod_factura; ?>" size="8">
<tr>
<td><div align="center"><strong>CODIGO</strong></div></td>
<td><div align="center"><strong>NOMBRE</strong></div></td>
<td><div align="center"><strong>UNDS</strong></div></td>
<!--<td><div align="center"><strong>MARCA</strong></div></td>
<td><div align="center"><strong>DESCRIPCION</strong></div></td>-->
<td><div align="center"><strong>V. UNITARIO</strong></div></td>
<td><div align="center"><strong>V. TOTAL</strong></div></td>
<td><div align="center"><strong>FECHA - HORA</strong></div></td>
<td><div align="center" ><strong>ELIM</strong></div></td>
</tr>
<?php
while ($datos = mysql_fetch_assoc($consulta)) {
$cod_ventas = $datos['cod_ventas'];
$cod_productos = $datos['cod_productos'];
$nombre_productos = $datos['nombre_productos'];
$unidades_vendidas = $datos['unidades_vendidas'];
$tope_min = $datos['tope_min'];
$precio_compra = $datos['precio_compra'];
$precio_venta = $datos['precio_venta'];
$fechas_vencimiento = $datos['fechas_vencimiento'];
$vlr_total_venta = $datos['vlr_total_venta'];
$cod_facturan = $datos['cod_factura'];
?>
<tr>
<td><font><?php echo $datos['cod_productos']; ?></td></font>
<td><font><?php echo $datos['nombre_productos']; ?></td></font>
<!--<td><font color='yellow'><?php //echo $datos['marca']; ?></td></font>-->
<td><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'unidades_vendidas', <?php echo $cod_ventas;?>)" class="inputoff" id="b<?php echo $cod_ventas;?>" value="<?php echo $unidades_vendidas;?>" size="9"></td>
<!--<td><font color='yellow'><?php //echo $datos['unidades_vendidas']; ?></td></font>
<td><font color='yellow'><?php //echo $datos['descripcion']; ?></td></font>-->
<td align="right"><font><?php echo number_format($datos['precio_venta']); ?></td></font>
<td align="right"><font><?php echo number_format($datos['vlr_total_venta']); ?></td></font>
<td align="center"><font><?php echo $datos['fecha_anyo'].' - '.$datos['fecha_hora']; ?></td></font>
<td  nowrap><a href="../modificar_eliminar/eliminar_productos_factura.php?cod_productos=<?php echo rawurlencode($datos['cod_productos'])?>&cod_ventas=<?php echo urlencode($datos['cod_ventas'])?>&cod_factura=<?php echo $cod_factura;?>&pagina=<?php echo $pagina?>"><center><img src=../imagenes/eliminar.png alt="Eliminar"></center></a></td>
</tr>	 
<?php 
}
?>
<!--
<td align="center"><input type="submit" value="Ver Factura" /></td>-->
</form>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
</head>