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


if (isset($_GET['campo'])) {
$campo = $_GET['campo'];
$ord = $_GET['ord'];
} else {
$campo = 'nombre_productos';
$ord = 'asc';
}
?>
<center>
<form action="" method="post">
<td><strong><font color='white'>BUSCAR PRODUCTOS: </font></strong></td><input name="buscar" required autofocus />
<input type="submit" name="buscador" value="Buscar productos" />
</form>
</center>
<?php
$buscar = $_POST['buscar'];

$mostrar_datos_sql = "SELECT * FROM productos WHERE (cod_productos_var = '$buscar' OR nombre_productos LIKE '%$buscar%') ORDER BY $campo $ord";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>ALMACEN</title>
</head>
<body>
<center>
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
myajax.Link('guardar_editable_inventario_todo_utilidad_ajax.php?valor='+valor+'&campo='+campo+'&id='+id);
}
</script>

</head>
<body onLoad="myajax = new isiAJAX();">
<form name="form1" id="form1" action="#" method="post" style="margin:1;">  
<td align="center"><font color="yellow" size="+2"><strong>INVENTARIO EDITABLE</strong></font></td>
<br><br>
<table width='100%' border='1'>
<tr>
<?php
if ($ord == 'desc') {?>
<td align="center">cod_productos <br><a href="../admin/inventario_productos.php?campo=cod_productos&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">cod_productos_var <br><a href="../admin/inventario_productos.php?campo=cod_productos_var&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">nombre_productos <br><a href="../admin/inventario_productos.php?campo=nombre_productos&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">cod_marcas <br><a href="../admin/inventario_productos.php?campo=cod_marcas&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">cod_proveedores <br><a href="../admin/inventario_productos.php?campo=cod_proveedores&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">cod_nomenclatura <br><a href="../admin/inventario_productos.php?campo=cod_nomenclatura&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">cod_tipo <br><a href="../admin/inventario_productos.php?campo=cod_tipo&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">cod_lineas <br><a href="../admin/inventario_productos.php?campo=cod_lineas&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">cod_ccosto <br><a href="../admin/inventario_productos.php?campo=cod_ccosto&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">cod_paises <br><a href="../admin/inventario_productos.php?campo=cod_paises&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">numero_factura <br><a href="../admin/inventario_productos.php?campo=numero_factura&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">unidades <br><a href="../admin/inventario_productos.php?campo=unidades&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">cajas <br><a href="../admin/inventario_productos.php?campo=cajas&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">unidades_total <br><a href="../admin/inventario_productos.php?campo=unidades_total&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">unidades_faltantes <br><a href="../admin/inventario_productos.php?campo=unidades_faltantes&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">precio_compra <br><a href="../admin/inventario_productos.php?campo=precio_compra&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">precio_costo <br><a href="../admin/inventario_productos.php?campo=precio_costo&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">precio_venta <br><a href="../admin/inventario_productos.php?campo=precio_venta&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">vlr_total_compra <br><a href="../admin/inventario_productos.php?campo=vlr_total_compra&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">vlr_total_venta <br><a href="../admin/inventario_productos.php?campo=vlr_total_venta&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">cod_interno <br><a href="../admin/inventario_productos.php?campo=cod_interno&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">tope_minimo <br><a href="../admin/inventario_productos.php?campo=tope_minimo&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">descuento <br><a href="../admin/inventario_productos.php?campo=descuento&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">tipo_pago <br><a href="../admin/inventario_productos.php?campo=tipo_pago&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">ip <br><a href="../admin/inventario_productos.php?campo=ip&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">codificacion <br><a href="../admin/inventario_productos.php?campo=codificacion&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">url <br><a href="../admin/inventario_productos.php?campo=url&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">cod_original <br><a href="../admin/inventario_productos.php?campo=cod_original&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">detalles <br><a href="../admin/inventario_productos.php?campo=detalles&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">descripcion <br><a href="../admin/inventario_productos.php?campo=descripcion&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">dto1 <br><a href="../admin/inventario_productos.php?campo=dto1&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">dto2 <br><a href="../admin/inventario_productos.php?campo=dto2&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">iva <br><a href="../admin/inventario_productos.php?campo=iva&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">iva_v <br><a href="../admin/inventario_productos.php?campo=iva_v&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">fechas_dia <br><a href="../admin/inventario_productos.php?campo=fechas_dia&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">fechas_mes <br><a href="../admin/inventario_productos.php?campo=fechas_mes&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">fechas_anyo <br><a href="../admin/inventario_productos.php?campo=fechas_anyo&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">fechas_hora <br><a href="../admin/inventario_productos.php?campo=fechas_hora&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">fechas_vencimiento <br><a href="../admin/inventario_productos.php?campo=fechas_vencimiento&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">porcentaje_vendedor <br><a href="../admin/inventario_productos.php?campo=porcentaje_vendedor&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">fechas_vencimiento_seg <br><a href="../admin/inventario_productos.php?campo=fechas_vencimiento_seg&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">fechas_agotado <br><a href="../admin/inventario_productos.php?campo=fechas_agotado&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">fechas_agotado_seg <br><a href="../admin/inventario_productos.php?campo=fechas_agotado_seg&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">vendedor <br><a href="../admin/inventario_productos.php?campo=vendedor&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">cuenta <br><a href="../admin/inventario_productos.php?campo=cuenta&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<?php
} else {
?>
<td align="center">cod_productos <br><a href="../admin/inventario_productos.php?campo=cod_productos&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">cod_productos_var <br><a href="../admin/inventario_productos.php?campo=cod_productos_var&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">nombre_productos <br><a href="../admin/inventario_productos.php?campo=nombre_productos&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">cod_marcas <br><a href="../admin/inventario_productos.php?campo=cod_marcas&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">cod_proveedores <br><a href="../admin/inventario_productos.php?campo=cod_proveedores&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">cod_nomenclatura <br><a href="../admin/inventario_productos.php?campo=cod_nomenclatura&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">cod_tipo <br><a href="../admin/inventario_productos.php?campo=cod_tipo&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">cod_lineas <br><a href="../admin/inventario_productos.php?campo=cod_lineas&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">cod_ccosto <br><a href="../admin/inventario_productos.php?campo=cod_ccosto&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">cod_paises <br><a href="../admin/inventario_productos.php?campo=cod_paises&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">numero_factura <br><a href="../admin/inventario_productos.php?campo=numero_factura&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">unidades <br><a href="../admin/inventario_productos.php?campo=unidades&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">cajas <br><a href="../admin/inventario_productos.php?campo=cajas&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">unidades_total <br><a href="../admin/inventario_productos.php?campo=unidades_total&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">unidades_faltantes <br><a href="../admin/inventario_productos.php?campo=unidades_faltantes&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">precio_compra <br><a href="../admin/inventario_productos.php?campo=precio_compra&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">precio_costo <br><a href="../admin/inventario_productos.php?campo=precio_costo&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">precio_venta <br><a href="../admin/inventario_productos.php?campo=precio_venta&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">vlr_total_compra <br><a href="../admin/inventario_productos.php?campo=vlr_total_compra&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">vlr_total_venta <br><a href="../admin/inventario_productos.php?campo=vlr_total_venta&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">cod_interno <br><a href="../admin/inventario_productos.php?campo=cod_interno&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">tope_minimo <br><a href="../admin/inventario_productos.php?campo=tope_minimo&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">descuento <br><a href="../admin/inventario_productos.php?campo=descuento&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">tipo_pago <br><a href="../admin/inventario_productos.php?campo=tipo_pago&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">ip <br><a href="../admin/inventario_productos.php?campo=ip&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">codificacion <br><a href="../admin/inventario_productos.php?campo=codificacion&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">url <br><a href="../admin/inventario_productos.php?campo=url&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">cod_original <br><a href="../admin/inventario_productos.php?campo=cod_original&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">detalles <br><a href="../admin/inventario_productos.php?campo=detalles&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">descripcion <br><a href="../admin/inventario_productos.php?campo=descripcion&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">dto1 <br><a href="../admin/inventario_productos.php?campo=dto1&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">dto2 <br><a href="../admin/inventario_productos.php?campo=dto2&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">iva <br><a href="../admin/inventario_productos.php?campo=iva&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">iva_v <br><a href="../admin/inventario_productos.php?campo=iva_v&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">fechas_dia <br><a href="../admin/inventario_productos.php?campo=fechas_dia&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">fechas_mes <br><a href="../admin/inventario_productos.php?campo=fechas_mes&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">fechas_anyo <br><a href="../admin/inventario_productos.php?campo=fechas_anyo&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">fechas_hora <br><a href="../admin/inventario_productos.php?campo=fechas_hora&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">fechas_vencimiento <br><a href="../admin/inventario_productos.php?campo=fechas_vencimiento&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">porcentaje_vendedor <br><a href="../admin/inventario_productos.php?campo=porcentaje_vendedor&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">fechas_vencimiento_seg <br><a href="../admin/inventario_productos.php?campo=fechas_vencimiento_seg&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">fechas_agotado <br><a href="../admin/inventario_productos.php?campo=fechas_agotado&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">fechas_agotado_seg <br><a href="../admin/inventario_productos.php?campo=fechas_agotado_seg&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">vendedor <br><a href="../admin/inventario_productos.php?campo=vendedor&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">cuenta <br><a href="../admin/inventario_productos.php?campo=cuenta&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<?php }?>
</tr>
<?php 
while ($datos = mysql_fetch_assoc($consulta)) {
$cod_productos = $datos['cod_productos'];
$cod_productos_var = $datos['cod_productos_var'];
$nombre_productos = $datos['nombre_productos'];
$cod_marcas = $datos['cod_marcas'];
$cod_proveedores = $datos['cod_proveedores'];
$cod_nomenclatura = $datos['cod_nomenclatura'];
$cod_tipo = $datos['cod_tipo'];
$cod_lineas = $datos['cod_lineas'];
$cod_ccosto = $datos['cod_ccosto'];
$cod_paises = $datos['cod_paises'];
$numero_factura = $datos['numero_factura'];
$unidades = $datos['unidades'];
$cajas = $datos['cajas'];
$unidades_total = $datos['unidades_total'];
$unidades_faltantes = $datos['unidades_faltantes'];
$precio_compra = $datos['precio_compra'];
$precio_costo = $datos['precio_costo'];
$precio_venta = $datos['precio_venta'];
$vlr_total_compra = $datos['vlr_total_compra'];
$vlr_total_venta = $datos['vlr_total_venta'];
$cod_interno = $datos['cod_interno'];
$tope_minimo = $datos['tope_minimo'];
$descuento = $datos['descuento'];
$tipo_pago = $datos['tipo_pago'];
$ip = $datos['ip'];
$codificacion = $datos['codificacion'];
$url = $datos['url'];
$cod_original = $datos['cod_original'];
$detalles = $datos['detalles'];
$descripcion = $datos['descripcion'];
$dto1 = $datos['dto1'];
$dto2 = $datos['dto2'];
$iva = $datos['iva'];
$iva_v = $datos['iva_v'];
$fechas_dia = $datos['fechas_dia'];
$fechas_mes = $datos['fechas_mes'];
$fechas_anyo = $datos['fechas_anyo'];
$fechas_hora = $datos['fechas_hora'];
$fechas_vencimiento = $datos['fechas_vencimiento'];
$porcentaje_vendedor = $datos['porcentaje_vendedor'];
$fechas_vencimiento_seg = $datos['fechas_vencimiento_seg'];
$fechas_agotado = $datos['fechas_agotado'];
$fechas_agotado_seg = $datos['fechas_agotado_seg'];
$vendedor = $datos['vendedor'];
$cuenta = $datos['cuenta'];
?>
<tr>
<td align='left'><?php echo $cod_productos ?></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'cod_productos_var', <?php echo $cod_productos;?>)" class="cajbarras" id="<?php echo $cod_productos;?>" value="<?php echo $cod_productos_var;?>" size="3"></td>
<td align='left'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'nombre_productos', <?php echo $cod_productos;?>)" class="cajsuper" id="<?php echo $cod_productos;?>" value="<?php echo $nombre_productos;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'cod_marcas', <?php echo $cod_productos;?>)" class="cajpequena" id="<?php echo $cod_productos;?>" value="<?php echo $cod_marcas;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'cod_proveedores', <?php echo $cod_productos;?>)" class="cajpequena" id="<?php echo $cod_productos;?>" value="<?php echo $cod_proveedores;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'cod_nomenclatura', <?php echo $cod_productos;?>)" class="cajpequena" id="<?php echo $cod_productos;?>" value="<?php echo $cod_nomenclatura;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'cod_tipo', <?php echo $cod_productos;?>)" class="cajpequena" id="<?php echo $cod_productos;?>" value="<?php echo $cod_tipo;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'cod_lineas', <?php echo $cod_productos;?>)" class="cajpequena" id="<?php echo $cod_productos;?>" value="<?php echo $cod_lineas;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'cod_ccosto', <?php echo $cod_productos;?>)" class="cajpequena" id="<?php echo $cod_productos;?>" value="<?php echo $cod_ccosto;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'cod_paises', <?php echo $cod_productos;?>)" class="cajpequena" id="<?php echo $cod_productos;?>" value="<?php echo $cod_paises;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'numero_factura', <?php echo $cod_productos;?>)" class="cajgrand" id="<?php echo $cod_productos;?>" value="<?php echo $numero_factura;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'unidades', <?php echo $cod_productos;?>)" class="cajgrand" id="<?php echo $cod_productos;?>" value="<?php echo $unidades;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'cajas', <?php echo $cod_productos;?>)" class="cajgrand" id="<?php echo $cod_productos;?>" value="<?php echo $cajas;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'unidades_total', <?php echo $cod_productos;?>)" class="cajgrand" id="<?php echo $cod_productos;?>" value="<?php echo $unidades_total;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'unidades_faltantes', <?php echo $cod_productos;?>)" class="cajgrand" id="<?php echo $cod_productos;?>" value="<?php echo $unidades_faltantes;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'precio_compra', <?php echo $cod_productos;?>)" class="cajgrand" id="<?php echo $cod_productos;?>" value="<?php echo $precio_compra;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'precio_costo', <?php echo $cod_productos;?>)" class="cajgrand" id="<?php echo $cod_productos;?>" value="<?php echo $precio_costo;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'precio_venta', <?php echo $cod_productos;?>)" class="cajgrand" id="<?php echo $cod_productos;?>" value="<?php echo $precio_venta;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'vlr_total_compra', <?php echo $cod_productos;?>)" class="cajgrand" id="<?php echo $cod_productos;?>" value="<?php echo $vlr_total_compra;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'vlr_total_venta', <?php echo $cod_productos;?>)" class="cajgrand" id="<?php echo $cod_productos;?>" value="<?php echo $vlr_total_venta;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'cod_interno', <?php echo $cod_productos;?>)" class="cajgrand" id="<?php echo $cod_productos;?>" value="<?php echo $cod_interno;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'tope_minimo', <?php echo $cod_productos;?>)" class="cajgrand" id="<?php echo $cod_productos;?>" value="<?php echo $tope_minimo;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'descuento', <?php echo $cod_productos;?>)" class="cajgrand" id="<?php echo $cod_productos;?>" value="<?php echo $descuento;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'tipo_pago', <?php echo $cod_productos;?>)" class="cajgrand" id="<?php echo $cod_productos;?>" value="<?php echo $tipo_pago;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'ip', <?php echo $cod_productos;?>)" class="cajgrand" id="<?php echo $cod_productos;?>" value="<?php echo $ip;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'codificacion', <?php echo $cod_productos;?>)" class="cajgrand" id="<?php echo $cod_productos;?>" value="<?php echo $codificacion;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'url', <?php echo $cod_productos;?>)" class="cajgrand" id="<?php echo $cod_productos;?>" value="<?php echo $url;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'cod_original', <?php echo $cod_productos;?>)" class="cajgrand" id="<?php echo $cod_productos;?>" value="<?php echo $cod_original;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'detalles', <?php echo $cod_productos;?>)" class="cajgrand" id="<?php echo $cod_productos;?>" value="<?php echo $detalles;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'descripcion', <?php echo $cod_productos;?>)" class="cajgrand" id="<?php echo $cod_productos;?>" value="<?php echo $descripcion;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'dto1', <?php echo $cod_productos;?>)" class="cajpequena" id="<?php echo $cod_productos;?>" value="<?php echo $dto1;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'dto2', <?php echo $cod_productos;?>)" class="cajpequena" id="<?php echo $cod_productos;?>" value="<?php echo $dto2;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'iva', <?php echo $cod_productos;?>)" class="cajpequena" id="<?php echo $cod_productos;?>" value="<?php echo $iva;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'iva_v', <?php echo $cod_productos;?>)" class="cajgrand" id="<?php echo $cod_productos;?>" value="<?php echo $iva_v;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'fechas_dia', <?php echo $cod_productos;?>)" class="cajbarras" id="<?php echo $cod_productos;?>" value="<?php echo $fechas_dia;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'fechas_mes', <?php echo $cod_productos;?>)" class="cajbarras" id="<?php echo $cod_productos;?>" value="<?php echo $fechas_mes;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'fechas_anyo', <?php echo $cod_productos;?>)" class="cajbarras" id="<?php echo $cod_productos;?>" value="<?php echo $fechas_anyo;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'fechas_hora', <?php echo $cod_productos;?>)" class="cajbarras" id="<?php echo $cod_productos;?>" value="<?php echo $fechas_hora;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'fechas_vencimiento', <?php echo $cod_productos;?>)" class="cajbarras" id="<?php echo $cod_productos;?>" value="<?php echo $fechas_vencimiento;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'porcentaje_vendedor', <?php echo $cod_productos;?>)" class="cajgrand" id="<?php echo $cod_productos;?>" value="<?php echo $porcentaje_vendedor;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'fechas_vencimiento_seg', <?php echo $cod_productos;?>)" class="cajbarras" id="<?php echo $cod_productos;?>" value="<?php echo $fechas_vencimiento_seg;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'fechas_agotado', <?php echo $cod_productos;?>)" class="cajbarras" id="<?php echo $cod_productos;?>" value="<?php echo $fechas_agotado;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'fechas_agotado_seg', <?php echo $cod_productos;?>)" class="cajbarras" id="<?php echo $cod_productos;?>" value="<?php echo $fechas_agotado_seg;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'vendedor', <?php echo $cod_productos;?>)" class="cajgrand" id="<?php echo $cod_productos;?>" value="<?php echo $vendedor;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'cuenta', <?php echo $cod_productos;?>)" class="cajgrand" id="<?php echo $cod_productos;?>" value="<?php echo $cuenta;?>" size="3"></td>
</tr>
<?php } ?>
</table>
</form>
</body>
</html>
<?php mysql_free_result($consulta);?>