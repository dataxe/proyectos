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

$cod_factura = intval($_GET['cod_factura']);
$proveedor = $_GET['proveedor'];

$mostrar_datos_sql = "SELECT cod_facturas_cargadas_stiker, precio_venta, iva, unidades_total, precio_venta, nombre_productos, cod_productos, 
cod_interno, detalles, precio_costo, precio_compra_con_descuento, cod_factura, vendedor, fecha_anyo, fecha_hora
FROM facturas_cargadas_stiker WHERE cod_factura = '$cod_factura'";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$total_resultados = mysql_num_rows($consulta);

$tab = 'facturas_cargadas_stiker';
$tipo = 'eliminar';
$campo = 'cod_facturas_cargadas_stiker';
$pagina = $_SERVER['PHP_SELF'];
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
myajax.Link('guardar_enviar_copia_stiker_sin_barras_ajax.php?valor='+valor+'&campo='+campo+'&id='+id);
}
</script>

</head>
<body onLoad="myajax = new isiAJAX();">
<form name="form1" id="form1" action="#" method="post" style="margin:1;">  
<table width='40%'>
<br>
<tr>
<td align="center"><strong>IMPRIMIR TODOS LOS STIKERS</td>
</tr>
<tr>
<td align="center">
<!--
<a href="../admin/imprimir_stiker_sin_barras_xps.php?cod_factura=<?php echo $cod_factura?>&proveedor=<?php echo $proveedor?>" target="_blank"><img src=../imagenes/sticker_estandar_128_xps_sin_barra.png alt="sticker"></a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
-->
<a href="../admin/imprimir_stiker_sin_barras.php?cod_factura=<?php echo $cod_factura?>&proveedor=<?php echo $proveedor?>" target="_blank"><img src=../imagenes/sticker_estandar_128_pdf_sin_barra.png alt="sticker"></a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="../admin/imprimir_stiker_con_barras.php?cod_factura=<?php echo $cod_factura?>&proveedor=<?php echo $proveedor?>" target="_blank"><img src=../imagenes/sticker_estandar_128_pdf_con_barra.png alt="sticker"></a>

</td>
</tr>
</table>
<br><br>
<fieldset><legend><font color='yellow' size='+3'>COMPRAS FACTURA: <?php echo $cod_factura.' - '.$proveedor;?></font></legend>
<table width='100%'>
<tr>
<td align="center"><strong>ELM</strong></td>
<td align="center"><strong>C&Oacute;DIGO</strong></td>
<td align="center"><strong>PRODUCTO</strong></td>
<td align="center"><strong>UND</strong></td>
<td align="center"><strong>COD INTERNO</strong></td>
<td align="center"><strong>MET</strong></td>
<td align="center"><strong>P.COMPRA</strong></td>
<td align="center"><strong>TOTAL COMPRA</strong></td>
<td align="center"><strong>%IVA</strong></td>
<td align="center"><strong>FACTURA</strong></td>
<td align="center"><strong>CUENTA</strong></td>
<td align="center"><strong>FECHA</strong></td>
<td align="center"><strong>HORA</strong></td>
<td align="center"><strong></strong></td>
<td align="center"><strong></strong></td>
</tr>
<?php 
while ($datos = mysql_fetch_assoc($consulta)) {
$cod_facturas_cargadas_stiker = $datos['cod_facturas_cargadas_stiker'];
$base = $datos['precio_venta']/1.16;
$iva_ptj = $datos['iva']/100;
$unidades_total = $datos['unidades_total'];
$iva_valor = ($base * $iva_ptj) * $unidades_total;
$precio_venta = $datos['precio_venta'];
$nombre_productos = $datos['nombre_productos'];
$cod_productos = $datos['cod_productos'];
$cod_interno = $datos['cod_interno'];
?>
<tr>
<td align="center"><a href="../modificar_eliminar/eliminar_stiker_sin_barras.php?llave=<?php echo $cod_facturas_cargadas_stiker?>&cod_factura=<?php echo $cod_factura?>&proveedor=<?php echo $proveedor?>&tab=<?php echo $tab?>&tipo=<?php echo $tipo?>&campo=<?php echo $campo?>&pagina=<?php echo $pagina?>"><img src=../imagenes/eliminar.png alt="eliminar"></a></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'cod_productos', <?php echo $cod_facturas_cargadas_stiker;?>)" class="cajbarras" id="<?php echo $cod_facturas_cargadas_stiker;?>" value="<?php echo $cod_productos;?>" size="3"></td>
<td align='left'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'nombre_productos', <?php echo $cod_facturas_cargadas_stiker;?>)" class="cajsuper" id="<?php echo $cod_facturas_cargadas_stiker;?>" value="<?php echo $nombre_productos;?>" size="3"></td>
<td align='left'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'unidades_total', <?php echo $cod_facturas_cargadas_stiker;?>)" class="cajpequena" id="<?php echo $unidades_total;?>" value="<?php echo $unidades_total;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'cod_interno', <?php echo $cod_facturas_cargadas_stiker;?>)" class="cajbarras" id="<?php echo $cod_facturas_cargadas_stiker;?>" value="<?php echo $cod_interno;?>" size="3"></td>
<td align="center"><?php echo $datos['detalles']; ?></td>
<td align="right"><?php echo number_format($datos['precio_costo'], 0, ",", "."); ?></td>
<td align="right"><?php echo number_format($datos['precio_compra_con_descuento'], 0, ",", "."); ?></td>
<td align="center"><?php echo intval($datos['iva']); ?></td>
<td align="center"><?php echo $datos['cod_factura']; ?></td>
<td align="center"><?php echo $datos['vendedor']; ?></td>
<td align="right"><?php echo $datos['fecha_anyo']; ?></td>
<td align="right"><?php echo $datos['fecha_hora']; ?></td>
<td align="center"><a href="../admin/imprimir_stiker_sin_barras_unico.php?cod_facturas_cargadas_stiker=<?php echo $cod_facturas_cargadas_stiker?>&cod_factura=<?php echo $cod_factura?>&proveedor=<?php echo $proveedor?>" target="_blank"><img src=../imagenes/sticker_estandar_128_pdf_sin_barra_peq.png alt="Sin barras"></a></td>
<td align="center"><a href="../admin/imprimir_stiker_con_barras_unico.php?cod_facturas_cargadas_stiker=<?php echo $cod_facturas_cargadas_stiker?>&cod_factura=<?php echo $cod_factura?>&proveedor=<?php echo $proveedor?>" target="_blank"><img src=../imagenes/sticker_estandar_128_pdf_con_barra_peq.png alt="Con barras"></a></td>
</tr>
<?php } ?>
</table>
</fieldset>
</form>
</body>
</html>