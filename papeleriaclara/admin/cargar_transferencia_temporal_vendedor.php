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
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<?php
$sql = "SELECT * FROM cargar_transferencias_temporal2 WHERE vendedor = '$cuenta_actual' ORDER BY cod_cargar_transferencias_temporal2 DESC";
$consulta = mysql_query($sql, $conectar) or die(mysql_error());
$total_datos = mysql_num_rows($consulta);

require_once("busqueda_inmediata_cargar_factura_temporal_vendedor.php");
if ($total_datos <> 0) {
require_once("informacion_factura_compra_vendedor.php");
} else {
}
if ($total_datos <> 0) {
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
myajax.Link('guardar_cargar_factura_temporal_vendedor.php?valor='+valor+'&campo='+campo+'&id='+id);
}
</script>

</head>
<body onLoad="myajax = new isiAJAX();">
<form name="form1" id="form1" action="#" method="post" style="margin:1;">
<table width="100%">
<tr>
<td><font size = "+1"><div align="center"><strong>ELM</strong></div></font></td>
<td><font size = "+1"><div align="center"><font><strong>C&Oacute;DIGO</strong></div></font></td>
<td><font size = "+1"><div align="center"><font><strong>PRODUCTO</strong></div></font></td>
<td><font size = "+1"><div align="center"><font><strong>F.VENCTO</strong></div></font></td>
<td><font size = "+1"><div align="center"><font><strong>OK</strong></div></font></td>
</tr>
<?php
while ($datos = mysql_fetch_assoc($consulta)) {
$cod_productos = $datos['cod_productos'];
$nombre_productos = $datos['nombre_productos'];
$fechas_vencimiento = $datos['fechas_vencimiento'];
?>
<tr>
<td><a href="../modificar_eliminar/eliminar_cargar_productos_temporal_vendedor.php?cod_productos=<?php echo $datos['cod_productos']?>&cod_cargar_factura_temporal_vendedor=<?php echo $datos['cod_cargar_factura_temporal']?>"><center><img src=../imagenes/eliminar.png alt="eliminar"></center></a></td>
<td><font size = "+1"><?php echo $cod_productos;?></font></td>
<td><font size = "+1."><?php echo $nombre_productos;?></font></td>
<td><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'fechas_vencimiento', <?php echo $cod_productos;?>)" class="alingcenter" id="<?php echo $cod_productos;?>" value="<?php echo $fechas_vencimiento;?>" size="8"></td>
<td><a href="<?php $_SERVER['PHP_SELF']?>"><center><img src=../imagenes/correcto.png alt="Listo"></center></a></td>
</tr>
<?php } ?>
</table>
</form>
<?php
} else {
}
?>