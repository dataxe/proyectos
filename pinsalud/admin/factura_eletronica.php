<?php error_reporting(E_ALL ^ E_NOTICE);
require_once('../conexiones/conexione.php'); 
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
//include ("../registro_movimientos/registro_cierre_caja.php");
?>
<head>
<?php
$consql = "SELECT * FROM temporal WHERE vendedor = '$cuenta_actual'";
$getanz = mysql_query($consql, $conectar) or die(mysql_error());
$total_datos = mysql_num_rows($getanz);

require_once("bsucar_con_codigo_barras.php");
if ($total_datos <> 0) {
require_once("informacion_factura_venta_electronica.php");
}
?>
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
myajax.Link('guardar_cargar_factura_temporal1.php?valor='+valor+'&campo='+campo+'&id='+id);
}
</script>
</head>
<body onLoad="myajax = new isiAJAX();">
<form name="form1" id="form1" action="#" method="post" style="margin:0;">  
<?php
$unidad = 'unidad';
$caja = 'caja';
$pagina = $_SERVER['PHP_SELF'];

$sql = "SELECT * FROM temporal WHERE vendedor = '$cuenta_actual' ORDER BY cod_temporal DESC";
$consulta = mysql_query($sql, $conectar) or die(mysql_error());

if ($total_datos <> 0) {
?>
<table width="100%">
<tr>
<td align="center" width='10px'><strong>ELIM</strong></td>
<td align="center"><strong>C&Oacute;DIGO</strong></td>
<td align="center"><strong>PRODUCTO</strong></td>
<td align="center" width='90px'><strong>CAJ</strong></td>
<td align="center" width='150px'><strong>UND</strong></td>
<td align="center" width='150px'><strong>V.UNIT</strong></td>
<td align="center" width='150px'><strong>V.TOTAL</strong></td>
<td align="center" width='10px'><strong>OK</strong></td>
</tr>
<?php
while ($datos = mysql_fetch_assoc($consulta)) {
$cod_temporal = $datos['cod_temporal'];
$cod_productos = $datos['cod_productos'];
$nombre_productos = $datos['nombre_productos'];
$unidades_cajas = $datos['unidades_cajas'];
$unidades_vendidas = $datos['unidades_vendidas'];
$precio_venta = $datos['precio_venta'];
$vlr_total_venta = $datos['vlr_total_venta'];
$unidades_cajas = $datos['unidades_cajas'];
$detalles = $datos['detalles'];
$tipo_venta = $datos['tipo_venta'];
?>
<tr>
<td ><a href="../modificar_eliminar/eliminar_temporal_productos.php?cod_productos=<?php echo $cod_productos?>&cod_temporal=<?php echo $cod_temporal?>&pagina=<?php echo $pagina?>" tabindex=3><center><img src=../imagenes/eliminar.png alt="eliminar"></center></a></td>
<td><?php echo $cod_productos;?></td>
<td><?php echo $nombre_productos;?></td>

<?php if ($tipo_venta == '1') { ?> 
<td><a href="../admin/actualizar_tipo_venta.php?tipo_venta=0&cod_temporal=<?php echo $cod_temporal?>&cod_productos=<?php echo $cod_productos?>&pagina=<?php echo $pagina?>" tabindex=3><center><img src=../imagenes/unidad.png alt="unidad"><?php echo $unidades_cajas?></center></a></td> 
<td align="center"><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'unidades_vendidas', <?php echo $cod_temporal;?>)" class="cajund" id="<?php echo $cod_temporal;?>" value="<?php echo $unidades_vendidas;?>" size="3"></td>
<td align='right'><font size='6'><?php echo number_format($precio_venta, 0, ",", ".");?></font></td>
<?php } else {?>
<td><a href="../admin/actualizar_tipo_venta.php?tipo_venta=1&cod_temporal=<?php echo $cod_temporal?>&cod_productos=<?php echo $cod_productos?>&pagina=<?php echo $pagina?>" tabindex=3><center><img src=../imagenes/menudiado.png alt="menudiado"><?php echo $unidades_cajas?></center></a></td>
<td align="center"><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'unidades_vendidas', <?php echo $cod_temporal;?>)" class="cajund" id="<?php echo $cod_temporal;?>" value="<?php echo $unidades_vendidas;?>" size="3"></td>
<td align="center"><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'precio_venta', <?php echo $cod_temporal;?>)" class="cajvtotal" id="<?php echo $cod_temporal;?>" value="<?php echo $precio_venta;?>" size="3"></td>
<?php } ?>
<!--<td ><a href="../modificar_eliminar/actualizar_tipo_venta_productos.php?cod_productos=<?php echo $datos['cod_productos']?>&cod_temporal=<?php echo $datos['cod_temporal']?>&tipo=<?php echo $caja?>&pagina=<?php echo $pagina?>"><center><?php echo $unidades_cajas.' UND '?><img src=../imagenes/cajas.png alt="Cajas"><?php echo ' '.$cajas?></center></a></td>-->
<td align='right'><font size='6'><?php echo number_format($vlr_total_venta, 0, ",", ".");?></font></td>
<td><a href="<?php $_SERVER['PHP_SELF']?>"><center><img src=../imagenes/correcto.png alt="Listo"></center></a></td> 
</tr>
<?php } 
} else {
}
?>
</table>
</form>
</body>
</html>