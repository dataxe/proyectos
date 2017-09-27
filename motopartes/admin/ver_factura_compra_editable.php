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
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");
?>
<center>
<?php
$cod_factura = $_GET['cod_factura'];
$cod_proveedores = $_GET['cod_proveedores'];
$proveedor = $_GET['proveedor'];
//-------------------------------------------- CALCULO PARA EL TOTAL DE COMPRA --------------------------------------------//
$mostrar_datos_totales = "SELECT sum(precio_compra_con_descuento) AS tot_fact, sum(valor_iva) AS valor_iva, 
sum(vlr_total_venta * cajas) AS vlr_total_ventas FROM facturas_cargadas_inv WHERE cod_factura = '$cod_factura'";
$consulta_totales = mysql_query($mostrar_datos_totales, $conectar) or die(mysql_error());
$totales = mysql_fetch_assoc($consulta_totales);

$tot_fact = $totales['tot_fact'];
$valor_iva = $totales['valor_iva'];
$vlr_total_ventas = $totales['vlr_total_ventas'];

$mostrar_datos_sql = "SELECT * FROM facturas_cargadas_inv WHERE cod_factura = '$cod_factura' ORDER BY cod_facturas_cargadas_inv DESC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$total_resultados = mysql_num_rows($consulta);
$datos = mysql_fetch_assoc($consulta);

require_once("menu_facturas_compra.php");
?>
<br>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>ALMACEN</title>
</head>
<body>
<script language="javascript" src="isiAJAX.js"></script>
<script language="javascript">
var last;
function Focus(elemento, valor) {
$(elemento).className = 'cajhabiltada';
last = valor;
}
function Blur(elemento, valor, campo, id) {
$(elemento).className = 'cajdeshabiltada';
if (last != valor)
myajax.Link('guardar_ver_factura_compra_editable_ajax.php?valor='+valor+'&campo='+campo+'&id='+id);
}
</script>

<body onLoad="myajax = new isiAJAX();">
<?php
if ($cod_factura <> NULL) {?>
<center>
<fieldset><legend><font color='yellow' size='+3'>COMPRAS TOTALES FACTURA: <?php echo $cod_factura.' - '.$proveedor;?></font></legend>
<table width='40%'>
<br>
<tr>
<td align="center"><strong>TOTAL FACTURA COMPRA</td>
<td align="center"><strong>TOTAL VENT PUBLIC (PROYEC)</td>
<td align="center"><strong>STIKER</td>
</tr>
<tr>
<td align="right"><font color="yellow" size="+2"><strong><?php echo number_format($tot_fact, 0, ",", "."); ?></font></td>
<td align="right"><font color="yellow" size="+2"><strong><?php echo number_format($vlr_total_ventas, 0, ",", "."); ?></font></td>
<td align="center"><a href="../admin/enviar_copia_stiker_sin_barras.php?cod_factura=<?php echo $cod_factura?>&proveedor=<?php echo $proveedor?>"><img src=../imagenes/sticker_estandar_128.jpg alt="sticker"></a></td>
</tr>
</table>
</center>
</fieldset>
<br>
<fieldset><legend><font color='yellow' size='+3'>COMPRAS FACTURA: <?php echo $cod_factura.' - '.$proveedor;?></font></legend>
<form method="post" name="formulario" action="actualizar_proveedor_factura_compra.php">
<table width='100%'>

<input type="hidden" name="cod_factura" value="<?php echo $cod_factura; ?>">

<select name="cod_proveedores">
<?php
if (isset($cod_proveedores)) {
echo "<option style='font-size:20px' value='-1' >...</option>";
} else {
echo  "<option style='font-size:20px' value='-1' selected >...</option>";
}
$consulta2 = mysql_query("SELECT cod_proveedores, nombre_proveedores FROM proveedores ORDER BY nombre_proveedores ASC");
while ($datos2 = mysql_fetch_array($consulta2)) {
if(isset($cod_proveedores) and $cod_proveedores == $datos2['cod_proveedores']) {
$seleccionado = "selected";
} else {
$seleccionado = "";
}
$codigo = $datos2['cod_proveedores'];
$nombre = $datos2['nombre_proveedores'];
echo "<option style='font-size:20px' value='".$codigo."' $seleccionado >".$nombre."</option>";
}
?>

<tr>
<td align="center"><strong>C&Oacute;DIGO</strong></td>
<td align="center"><strong>PRODUCTO</strong></td>
<td align="center"><strong>UND</strong></td>
<td align="center"><strong>MET</strong></td>
<td align="center"><strong>P.COMPRA</strong></td>
<td align="center"><strong>TOTAL COMPRA</strong></td>
<td align="center"><strong>%IVA</strong></td>
<td align="center"><strong>FACTURA</strong></td>
<td align="center"><strong>VENDEDOR</strong></td>
<td align="center"><strong>FECHA</strong></td>
<td align="center"><strong>HORA</strong></td>
<td align="center"><strong>IP</strong></td>
<td align="center"><strong>OK</strong></td>
</tr>
<?php 
do {
$cod_facturas_cargadas_inv = $datos['cod_facturas_cargadas_inv'];
$cod_productos = $datos['cod_productos'];
$nombre_productos = $datos['nombre_productos'];
$base = $datos['precio_venta']/1.16;
$iva_ptj = $datos['iva']/100;
$unidades_total = $datos['unidades_total'];
$iva_valor = ($base * $iva_ptj) * $unidades_total;
$precio_venta = $datos['precio_venta'];
$detalles = $datos['detalles'];
$precio_costo = $datos['precio_costo'];
?>
<tr>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'cod_productos', <?php echo $cod_facturas_cargadas_inv;?>)" class="cajgrand" id="<?php echo $cod_facturas_cargadas_inv;?>" value="<?php echo $cod_productos;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'nombre_productos', <?php echo $cod_facturas_cargadas_inv;?>)" class="cajsuper" id="<?php echo $cod_facturas_cargadas_inv;?>" value="<?php echo $nombre_productos;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'unidades_total', <?php echo $cod_facturas_cargadas_inv;?>)" class="alingizq" id="<?php echo $cod_facturas_cargadas_inv;?>" value="<?php echo $unidades_total;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'detalles', <?php echo $cod_facturas_cargadas_inv;?>)" class="alingizq" id="<?php echo $cod_facturas_cargadas_inv;?>" value="<?php echo $detalles;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'precio_costo', <?php echo $cod_facturas_cargadas_inv;?>)" class="cajgrand" id="<?php echo $cod_facturas_cargadas_inv;?>" value="<?php echo $precio_costo;?>" size="3"></td>
<td align="right"><?php echo number_format($datos['precio_compra_con_descuento'], 0, ",", "."); ?></td>
<td align="center"><?php echo intval($datos['iva']); ?></td>
<td align="center"><?php echo $datos['cod_factura']; ?></td>
<td align="center"><?php echo $datos['vendedor']; ?></td>
<td align="right"><?php echo $datos['fecha_anyo']; ?></td>
<td align="right"><?php echo $datos['fecha_hora']; ?></td>
<td align="right"><?php echo $datos['ip']; ?></td>
<td><a href="<?php $_SERVER['PHP_SELF']?>"><center><img src=../imagenes/correcto.png alt="Listo"></center></a></td> 
</tr>
<?php } while ($datos = mysql_fetch_assoc($consulta));?>
</table>
<br>
<td bordercolor="1"><input type="submit" id="boton1" value="Actualizar Proveedor"></td>
</tr>
<input type="hidden" name="insertar_datos" value="formulario">
</form>
</fieldset>
</body>
</html>
<?php mysql_free_result($consulta);?>
<br>
<center>
<?php mysql_free_result($consulta_totales);
} else {
}
?>