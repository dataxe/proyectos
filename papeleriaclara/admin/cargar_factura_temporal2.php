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
include ("../seguridad/seguridad_diseno_plantillas.php");
include ("../registro_movimientos/registro_movimientos.php");
?>
<head>
<?php 
require_once("busqueda_inmediata_cargar_factura_temporal_vendedor.php");
require_once("informacion_factura_compra_vendedor.php");
?>
<script language="javascript" src="isiAJAX.js"></script>
<script LANGUAGE="JavaScript">
var last;
function Focus(elemento, valor) {
$(elemento).className = 'activo';
last = valor;
}
function Blur(elemento, valor, id) {
$(elemento).className = 'inactivo';
var aleatorio=Math.random();
if (last != valor)
myajax.Link('guardar_actualizar_valor_inmediato2.php?valor='+valor+'&id='+id+'&aleatorio='+aleatorio);
}
</script>
</head>
<body onLoad="myajax = new isiAJAX();">
<form name="form1" id="form1" action="#" method="post" style="margin:0;">  
<?php
$sql = "SELECT * FROM cargar_factura_temporal2 WHERE vendedor = '$cuenta_actual'";
$consulta = mysql_query($sql, $conectar) or die(mysql_error());
?>
<table width="100%">
<tr>
<td><div align="center"><strong>ELIM</strong></div></td>
<td><strong>C&Oacute;DIGO</strong></td>
<td><strong>PRODUCTO</strong></td>
<td><strong>UNDS</strong></td>
<!--<td>precio_compra</td>
<td>vlr_total_compra</td>-->
</tr>
<?php
while ($datos = mysql_fetch_assoc($consulta)) {
$cod_productos=$datos['cod_productos'];
$nombre_productos = $datos['nombre_productos'];
$unidades_vendidas = $datos['unidades_vendidas'];
$precio_venta = $datos['precio_venta'];
$vlr_total_venta = $datos['vlr_total_venta'];
?>
<tr>
<td><a href="../modificar_eliminar/eliminar_cargar_productos_temporal_vendedor.php?cod_productos=<?php echo $datos['cod_productos']?>&cod_cargar_factura_temporal_vendedor=<?php echo $datos['cod_cargar_factura_temporal']?>" tabindex=3><center><img src=../imagenes/eliminar.png alt="eliminar"></center></a></td><td><?php echo $cod_productos;?></td>
<td><?php echo $nombre_productos;?></td>
<td><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, <?php echo $cod_productos;?>)" class="inputoff" id="unidades_vendidas<?php echo $cod_productos;?>" value="<?php echo $unidades_vendidas;?>" maxlength="10" size="5"/></td>
<input type="hidden" name="cod_producto" value="<?php echo $datos['cod_productos'];?>">
<input type="hidden" name="cod_productos[]" value="<?php echo $datos['cod_productos'];?>">
<input type="hidden" name="nombre_productos[]" value="<?php echo $datos['nombre_productos'];?>">
<input type="hidden" name="unidades_vendidas[]" value="<?php echo $datos['unidades_vendidas'];?>">
<input type="hidden" name="precio_venta[]" value="<?php echo $datos['precio_venta'];?>">
<input type="hidden" name="vlr_total_venta[]" value="<?php echo $datos['vlr_total_venta'];?>">
<input type="hidden" name="precio_compra[]" value="<?php echo $datos['precio_compra'];?>">
<input type="hidden" name="precio_costo[]" value="<?php echo $datos['precio_costo'];?>">
<input type="hidden" name="vlr_total_compra[]" value="<?php echo $datos['vlr_total_compra'];?>">
<input type="hidden" name="precio_compra_con_descuento[]" value="<?php echo $datos['total_venta'];?>">
<input type="hidden" name="descuento[]" value="<?php echo $datos['descuento'];?>">
<input type="hidden" name="total_datos" value="<?php echo $total_datos;?>">
</tr>
<?php } ?>
</table>
</form>
</body>
</html>