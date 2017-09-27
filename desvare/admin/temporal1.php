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
/*
$datos_factura = "SELECT * FROM cargar_factura_temporal WHERE vendedor = '$cuenta_actual'";
$consulta = mysql_query($datos_factura, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);
$total_datos = mysql_num_rows($consulta);
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<?php
require_once("busqueda_inmediata_act.php");
require_once("informacion_factura_venta.php");
?>
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
myajax.Link('guardar_venta_temporal.php?valor='+valor+'&campo='+campo+'&id='+id);
}
</script>

</head>
<body onLoad="myajax = new isiAJAX();">
<form name="form1" id="form1" action="#" method="post" style="margin:1;">  
<?php
$sql = "SELECT * FROM temporal WHERE vendedor = '$cuenta_actual'";
$consulta = mysql_query($sql, $conectar) or die(mysql_error());

$unidad = 'unidad';
$caja = 'caja';
$pagina = 'temporal.php';
?>
<table width="100%">
<tr>
<td><div align="center"><strong>ELIM</strong></div></td>
<td><div align="center"><strong>C&Oacute;DIGO</strong></div></td>
<td><div align="center"><strong>PRODUCTO</strong></div></td>
<td><div align="center"><strong>CAJ</strong></div></td>
<td><div align="center"><strong>UND</strong></div></td>
<td><div align="center"><strong>U.CAJ</strong></div></td>
<td><div align="center"><strong>V.UNIT</strong></div></td>
<td><div align="center"><strong>V.TOTAL</strong></div></td>
<td><div align="center"><strong>OK</strong></div></td>
</tr>
<?php
while ($datos = mysql_fetch_assoc($consulta)) {
$cod_productos = $datos['cod_productos'];
$nombre_productos = $datos['nombre_productos'];
$unidades_cajas = $datos['unidades_cajas'];
$unidades_vendidas = $datos['unidades_vendidas'];
$precio_venta = $datos['precio_venta'];
$vlr_total_venta = $datos['vlr_total_venta'];
?>
<tr>
<td ><a href="../modificar_eliminar/eliminar_temporal_productos.php?cod_productos=<?php echo $datos['cod_productos']?>&cod_temporal=<?php echo $datos['cod_temporal']?>&pagina=<?php echo $pagina?>"><center><img src=../imagenes/eliminar.png alt="eliminar"></center></a></td>
<td><?php echo $cod_productos;?></td>
<td><?php echo $nombre_productos;?></td>
<td ><a href="../modificar_eliminar/actualizar_tipo_venta_productos.php?cod_productos=<?php echo $datos['cod_productos']?>&cod_temporal=<?php echo $datos['cod_temporal']?>&tipo=<?php echo $caja?>&pagina=<?php echo $pagina?>"><center><img src=../imagenes/cajas.png alt="Cajas"><?php echo ' '.$datos['cajas']?></center></a></td>
<td><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'unidades_vendidas', <?php echo $cod_productos;?>)" class="inputoff" id="<?php echo $cod_productos;?>" value="<?php echo $unidades_vendidas;?>" size="3"></td>
<td><?php echo $unidades_cajas;?></td>
<td><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'precio_venta', <?php echo $cod_productos;?>)" class="inputoff" id="<?php echo $cod_productos;?>" value="<?php echo $precio_venta;?>" size="3"></td>
<td><div align='right'><?php echo $vlr_total_venta;?></div></td>
<td><a href="<?php $_SERVER['PHP_SELF']?>"><center><img src=../imagenes/correcto.png alt="Listo"></center></a></td> 
</tr>
<?php } ?>
</table>
</form>