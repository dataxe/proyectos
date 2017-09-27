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
<br>
<td align="center"><font color="yellow" size="+2"><strong>VENTAS EDITABLE</strong></font></td>
<br><br>
<form action="" method="post">
<td><strong><font color='white'>BUSCAR VENTAS: </font></strong></td><input name="buscar" required autofocus />
<input type="submit" name="buscador" value="Buscar productos" />
</form>
</center>
<br>
<?php
$pagina_actual = $_SERVER["PHP_SELF"];
$formulario_agregar = $_SERVER['PHP_SELF'];

$numero_maximo_de_muestra = 500;
$numero_de_pagina = 0;
if (isset($_GET['numero_de_pagina'])) {
  $numero_de_pagina = $_GET['numero_de_pagina'];
}
$muestra_faltante = $numero_de_pagina * $numero_maximo_de_muestra;
$buscar = $_POST['palabra'];

$mostrar_datos_sql = "SELECT * FROM ventas WHERE cod_productos = '$buscar' OR nombre_productos LIKE '%$buscar%' OR fecha_anyo = '$buscar' ORDER BY $campo $ord";

$limite_consulta_sql = sprintf("%s LIMIT %d, %d", $mostrar_datos_sql, $muestra_faltante, $numero_maximo_de_muestra);
$consulta = mysql_query($limite_consulta_sql, $conectar) or die(mysql_error());
$matriz_consulta = mysql_fetch_assoc($consulta);

if (isset($_GET['numero_total_de_registros'])) {
  $numero_total_de_registros = $_GET['numero_total_de_registros'];
} else {
  $todo_consulta = mysql_query($mostrar_datos_sql);
  $numero_total_de_registros = mysql_num_rows($todo_consulta);
}
$total_pagina_consulta = ceil($numero_total_de_registros/$numero_maximo_de_muestra)-1;

$consulta_caracter_vacio = "";
if (!empty($_SERVER['QUERY_STRING'])) {
$parametros = explode("&", $_SERVER['QUERY_STRING']);
$nuevos_parametros = array();
foreach ($parametros as $parametro) {
if (stristr($parametro, "numero_de_pagina") == false && 
stristr($parametro, "numero_total_de_registros") == false) {
array_push($nuevos_parametros, $parametro);
}
}
if (count($nuevos_parametros) != 0) {
$consulta_caracter_vacio = "&" . htmlentities(implode("&", $nuevos_parametros));
}
}
$consulta_caracter_vacio = sprintf("&numero_total_de_registros=%d%s", $numero_total_de_registros, $consulta_caracter_vacio);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<center>
<table width="50%" align="center">
<tr>
<td width="23%" align="center"><?php if ($numero_de_pagina > 0) {?><a href="<?php printf("%s?numero_de_pagina=%d%s", $pagina_actual, 0, $consulta_caracter_vacio); ?>" >Primero</a><?php }?></td>
<td width="31%" align="center"><?php if ($numero_de_pagina > 0) {?><a href="<?php printf("%s?numero_de_pagina=%d%s", $pagina_actual, max(0, $numero_de_pagina - 1), $consulta_caracter_vacio); ?>" >Anterior</a><?php }?></td>
<td width="23%" align="center"><?php if ($numero_de_pagina < $total_pagina_consulta) {?><a href="<?php printf("%s?numero_de_pagina=%d%s", $pagina_actual, min($total_pagina_consulta, $numero_de_pagina + 1), $consulta_caracter_vacio); ?>" >Siguiente</a><?php }?></td>
<td width="23%" align="center"><?php if ($numero_de_pagina < $total_pagina_consulta) {?><a href="<?php printf("%s?numero_de_pagina=%d%s", $pagina_actual, $total_pagina_consulta, $consulta_caracter_vacio); ?>" >&Uacute;ltimo</a><?php }?></td>
</tr>
</table>
</center>
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
myajax.Link('guardar_editable_ventas_todo_utilidad_ajax.php?valor='+valor+'&campo='+campo+'&id='+id);
}
</script>

</head>
<body onLoad="myajax = new isiAJAX();">
<form name="form1" id="form1" action="#" method="post" style="margin:1;">  
<br><br>
<table width='100%' border='1'>
<tr>
<?php
$pagina = $_SERVER['PHP_SELF'];
if ($ord == 'desc') {?>
<td align="center">cod_ventas <br><a href="<?php echo $pagina?>?campo=cod_ventas&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">cod_productos <br><a href="<?php echo $pagina?>?campo=cod_productos&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">cod_factura <br><a href="<?php echo $pagina?>?campo=cod_factura&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">cod_clientes <br><a href="<?php echo $pagina?>?campo=cod_clientes&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">cod_proveedores <br><a href="<?php echo $pagina?>?campo=cod_proveedores&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">cod_marcas <br><a href="<?php echo $pagina?>?campo=cod_marcas&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">tipo_pago <br><a href="<?php echo $pagina?>?campo=tipo_pago&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">nombre_productos <br><a href="<?php echo $pagina?>?campo=nombre_productos&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">unidades_vendidas <br><a href="<?php echo $pagina?>?campo=unidades_vendidas&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">und_vend_orig <br><a href="<?php echo $pagina?>?campo=und_vend_orig&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">devoluciones <br><a href="<?php echo $pagina?>?campo=devoluciones&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">precio_compra <br><a href="<?php echo $pagina?>?campo=precio_compra&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">precio_costo <br><a href="<?php echo $pagina?>?campo=precio_costo&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">precio_venta <br><a href="<?php echo $pagina?>?campo=precio_venta&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">vlr_total_venta <br><a href="<?php echo $pagina?>?campo=vlr_total_venta&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">vlr_total_compra <br><a href="<?php echo $pagina?>?campo=vlr_total_compra&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">comentario <br><a href="<?php echo $pagina?>?campo=comentario&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">tipo_venta <br><a href="<?php echo $pagina?>?campo=tipo_venta&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">iva <br><a href="<?php echo $pagina?>?campo=iva&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">iva_v <br><a href="<?php echo $pagina?>?campo=iva_v&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">detalles <br><a href="<?php echo $pagina?>?campo=detalles&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">nombre_lineas <br><a href="<?php echo $pagina?>?campo=nombre_lineas&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">nombre_ccosto <br><a href="<?php echo $pagina?>?campo=nombre_ccosto&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">cod_base_caja <br><a href="<?php echo $pagina?>?campo=cod_base_caja&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">descuento <br><a href="<?php echo $pagina?>?campo=descuento&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">descuento_ptj <br><a href="<?php echo $pagina?>?campo=descuento_ptj&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">precio_compra_con_descuento <br><a href="<?php echo $pagina?>?campo=precio_compra_con_descuento&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">porcentaje_vendedor <br><a href="<?php echo $pagina?>?campo=porcentaje_vendedor&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">vendedor <br><a href="<?php echo $pagina?>?campo=vendedor&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">cuenta <br><a href="<?php echo $pagina?>?campo=cuenta&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">ip <br><a href="<?php echo $pagina?>?campo=ip&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">fecha_devolucion <br><a href="<?php echo $pagina?>?campo=fecha_devolucion&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">hora_devolucion <br><a href="<?php echo $pagina?>?campo=hora_devolucion&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">fecha_orig <br><a href="<?php echo $pagina?>?campo=fecha_orig&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">fecha <br><a href="<?php echo $pagina?>?campo=fecha&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">fecha_mes <br><a href="<?php echo $pagina?>?campo=fecha_mes&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">fecha_anyo <br><a href="<?php echo $pagina?>?campo=fecha_anyo&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">anyo <br><a href="<?php echo $pagina?>?campo=anyo&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">fecha_hora <br><a href="<?php echo $pagina?>?campo=fecha_hora&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<?php
} else {
?>
<td align="center">cod_ventas <br><a href="<?php echo $pagina?>?campo=cod_ventas&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">cod_productos <br><a href="<?php echo $pagina?>?campo=cod_productos&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">cod_factura <br><a href="<?php echo $pagina?>?campo=cod_factura&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">cod_clientes <br><a href="<?php echo $pagina?>?campo=cod_clientes&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">cod_proveedores <br><a href="<?php echo $pagina?>?campo=cod_proveedores&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">cod_marcas <br><a href="<?php echo $pagina?>?campo=cod_marcas&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">tipo_pago <br><a href="<?php echo $pagina?>?campo=tipo_pago&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">nombre_productos <br><a href="<?php echo $pagina?>?campo=nombre_productos&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">unidades_vendidas <br><a href="<?php echo $pagina?>?campo=unidades_vendidas&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">und_vend_orig <br><a href="<?php echo $pagina?>?campo=und_vend_orig&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">devoluciones <br><a href="<?php echo $pagina?>?campo=devoluciones&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">precio_compra <br><a href="<?php echo $pagina?>?campo=precio_compra&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">precio_costo <br><a href="<?php echo $pagina?>?campo=precio_costo&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">precio_venta <br><a href="<?php echo $pagina?>?campo=precio_venta&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">vlr_total_venta <br><a href="<?php echo $pagina?>?campo=vlr_total_venta&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">vlr_total_compra <br><a href="<?php echo $pagina?>?campo=vlr_total_compra&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">comentario <br><a href="<?php echo $pagina?>?campo=comentario&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">tipo_venta <br><a href="<?php echo $pagina?>?campo=tipo_venta&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">iva <br><a href="<?php echo $pagina?>?campo=iva&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">iva_v <br><a href="<?php echo $pagina?>?campo=iva_v&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">detalles <br><a href="<?php echo $pagina?>?campo=detalles&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">nombre_lineas <br><a href="<?php echo $pagina?>?campo=nombre_lineas&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">nombre_ccosto <br><a href="<?php echo $pagina?>?campo=nombre_ccosto&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">cod_base_caja <br><a href="<?php echo $pagina?>?campo=cod_base_caja&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">descuento <br><a href="<?php echo $pagina?>?campo=descuento&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">descuento_ptj <br><a href="<?php echo $pagina?>?campo=descuento_ptj&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">precio_compra_con_descuento <br><a href="<?php echo $pagina?>?campo=precio_compra_con_descuento&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">porcentaje_vendedor <br><a href="<?php echo $pagina?>?campo=porcentaje_vendedor&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">vendedor <br><a href="<?php echo $pagina?>?campo=vendedor&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">cuenta <br><a href="<?php echo $pagina?>?campo=cuenta&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">ip <br><a href="<?php echo $pagina?>?campo=ip&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">fecha_devolucion <br><a href="<?php echo $pagina?>?campo=fecha_devolucion&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">hora_devolucion <br><a href="<?php echo $pagina?>?campo=hora_devolucion&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">fecha_orig <br><a href="<?php echo $pagina?>?campo=fecha_orig&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">fecha <br><a href="<?php echo $pagina?>?campo=fecha&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">fecha_mes <br><a href="<?php echo $pagina?>?campo=fecha_mes&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">fecha_anyo <br><a href="<?php echo $pagina?>?campo=fecha_anyo&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">anyo <br><a href="<?php echo $pagina?>?campo=anyo&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">fecha_hora <br><a href="<?php echo $pagina?>?campo=fecha_hora&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<?php }?>
</tr>
<?php
while ($datos = mysql_fetch_assoc($consulta)) {
$cod_ventas = $datos['cod_ventas'];
$cod_productos = $datos['cod_productos'];
$cod_factura = $datos['cod_factura'];
$cod_clientes = $datos['cod_clientes'];
$cod_proveedores = $datos['cod_proveedores'];
$cod_marcas = $datos['cod_marcas'];
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
$comentario = $datos['comentario'];
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
$ip = $datos['ip'];
$fecha_devolucion = $datos['fecha_devolucion'];
$hora_devolucion = $datos['hora_devolucion'];
$fecha_orig = $datos['fecha_orig'];
$fecha = $datos['fecha'];
$fecha_mes = $datos['fecha_mes'];
$fecha_anyo = $datos['fecha_anyo'];
$anyo = $datos['anyo'];
$fecha_hora = $datos['fecha_hora'];
?>
<td align='left'><?php echo $cod_ventas ?></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'cod_productos', <?php echo $cod_ventas;?>)" class="cajbarras" id="<?php echo $cod_ventas;?>" value="<?php echo $cod_productos;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'cod_factura', <?php echo $cod_ventas;?>)" class="cajpequena" id="<?php echo $cod_ventas;?>" value="<?php echo $cod_factura;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'cod_clientes', <?php echo $cod_ventas;?>)" class="cajpequena" id="<?php echo $cod_ventas;?>" value="<?php echo $cod_clientes;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'cod_proveedores', <?php echo $cod_ventas;?>)" class="cajpequena" id="<?php echo $cod_ventas;?>" value="<?php echo $cod_proveedores;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'cod_marcas', <?php echo $cod_ventas;?>)" class="cajpequena" id="<?php echo $cod_ventas;?>" value="<?php echo $cod_marcas;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'tipo_pago', <?php echo $cod_ventas;?>)" class="cajpequena" id="<?php echo $cod_ventas;?>" value="<?php echo $tipo_pago;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'nombre_productos', <?php echo $cod_ventas;?>)" class="cajsuper" id="<?php echo $cod_ventas;?>" value="<?php echo $nombre_productos;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'unidades_vendidas', <?php echo $cod_ventas;?>)" class="cajpequena" id="<?php echo $cod_ventas;?>" value="<?php echo $unidades_vendidas;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'und_vend_orig', <?php echo $cod_ventas;?>)" class="cajpequena" id="<?php echo $cod_ventas;?>" value="<?php echo $und_vend_orig;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'devoluciones', <?php echo $cod_ventas;?>)" class="cajgrand" id="<?php echo $cod_ventas;?>" value="<?php echo $devoluciones;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'precio_compra', <?php echo $cod_ventas;?>)" class="cajgrand" id="<?php echo $cod_ventas;?>" value="<?php echo $precio_compra;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'precio_costo', <?php echo $cod_ventas;?>)" class="cajgrand" id="<?php echo $cod_ventas;?>" value="<?php echo $precio_costo;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'precio_venta', <?php echo $cod_ventas;?>)" class="cajgrand" id="<?php echo $cod_ventas;?>" value="<?php echo $precio_venta;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'vlr_total_venta', <?php echo $cod_ventas;?>)" class="cajgrand" id="<?php echo $cod_ventas;?>" value="<?php echo $vlr_total_venta;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'vlr_total_compra', <?php echo $cod_ventas;?>)" class="cajgrand" id="<?php echo $cod_ventas;?>" value="<?php echo $vlr_total_compra;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'comentario', <?php echo $cod_ventas;?>)" class="cajgrand" id="<?php echo $cod_ventas;?>" value="<?php echo $comentario;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'tipo_venta', <?php echo $cod_ventas;?>)" class="cajgrand" id="<?php echo $cod_ventas;?>" value="<?php echo $tipo_venta;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'iva', <?php echo $cod_ventas;?>)" class="cajpequena" id="<?php echo $cod_ventas;?>" value="<?php echo $iva;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'iva_v', <?php echo $cod_ventas;?>)" class="cajpequena" id="<?php echo $cod_ventas;?>" value="<?php echo $iva_v;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'detalles', <?php echo $cod_ventas;?>)" class="cajgrand" id="<?php echo $cod_ventas;?>" value="<?php echo $detalles;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'nombre_lineas', <?php echo $cod_ventas;?>)" class="cajgrand" id="<?php echo $cod_ventas;?>" value="<?php echo $nombre_lineas;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'nombre_ccosto', <?php echo $cod_ventas;?>)" class="cajgrand" id="<?php echo $cod_ventas;?>" value="<?php echo $nombre_ccosto;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'cod_base_caja', <?php echo $cod_ventas;?>)" class="cajgrand" id="<?php echo $cod_ventas;?>" value="<?php echo $cod_base_caja;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'descuento', <?php echo $cod_ventas;?>)" class="cajgrand" id="<?php echo $cod_ventas;?>" value="<?php echo $descuento;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'descuento_ptj', <?php echo $cod_ventas;?>)" class="cajpequena" id="<?php echo $cod_ventas;?>" value="<?php echo $descuento_ptj;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'precio_compra_con_descuento', <?php echo $cod_ventas;?>)" class="cajgrand" id="<?php echo $cod_ventas;?>" value="<?php echo $precio_compra_con_descuento;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'porcentaje_vendedor', <?php echo $cod_ventas;?>)" class="cajpequena" id="<?php echo $cod_ventas;?>" value="<?php echo $porcentaje_vendedor;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'vendedor', <?php echo $cod_ventas;?>)" class="cajbarras" id="<?php echo $cod_ventas;?>" value="<?php echo $vendedor;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'cuenta', <?php echo $cod_ventas;?>)" class="cajbarras" id="<?php echo $cod_ventas;?>" value="<?php echo $cuenta;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'ip', <?php echo $cod_ventas;?>)" class="cajbarras" id="<?php echo $cod_ventas;?>" value="<?php echo $ip;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'fecha_devolucion', <?php echo $cod_ventas;?>)" class="cajbarras" id="<?php echo $cod_ventas;?>" value="<?php echo $fecha_devolucion;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'hora_devolucion', <?php echo $cod_ventas;?>)" class="cajbarras" id="<?php echo $cod_ventas;?>" value="<?php echo $hora_devolucion;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'fecha_orig', <?php echo $cod_ventas;?>)" class="cajbarras" id="<?php echo $cod_ventas;?>" value="<?php echo $fecha_orig;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'fecha', <?php echo $cod_ventas;?>)" class="cajbarras" id="<?php echo $cod_ventas;?>" value="<?php echo $fecha;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'fecha_mes', <?php echo $cod_ventas;?>)" class="cajbarras" id="<?php echo $cod_ventas;?>" value="<?php echo $fecha_mes;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'fecha_anyo', <?php echo $cod_ventas;?>)" class="cajbarras" id="<?php echo $cod_ventas;?>" value="<?php echo $fecha_anyo;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'anyo', <?php echo $cod_ventas;?>)" class="cajgrand" id="<?php echo $cod_ventas;?>" value="<?php echo $anyo;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'fecha_hora', <?php echo $cod_ventas;?>)" class="cajbarras" id="<?php echo $cod_ventas;?>" value="<?php echo $fecha_hora;?>" size="3"></td>
</tr>
<?php } ?>
</table>
</form>
</body>
</html>
<?php mysql_free_result($consulta);?>