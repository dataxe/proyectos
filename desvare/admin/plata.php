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
//include ("../registro_movimientos/registro_cierre_caja.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>

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
myajax.Link('guardar_plata.php?valor='+valor+'&campo='+campo+'&id='+id);
}
</script>

</head>
<body onLoad="myajax = new isiAJAX();">
<form name="form1" id="form1" action="#" method="post" style="margin:1;">  
<?php
$unidad = 'unidad';
$pagina = $_SERVER['PHP_SELF'];
$fecha = date("d/m/Y");

$filtro_ventas = "SELECT SUM(vlr_total_venta) AS vlr_total_venta FROM ventas WHERE fecha_anyo = '$fecha' AND vendedor = '$cuenta_actual'";
$consulta_filtro = mysql_query($filtro_ventas, $conectar) or die(mysql_error());
$filtro = mysql_fetch_assoc($consulta_filtro);

$sql_invent = "SELECT sum(total) as invent_total FROM inventario WHERE cuenta = '$cuenta_actual'";
$consulta_invent = mysql_query($sql_invent, $conectar) or die(mysql_error());
$invent = mysql_fetch_assoc($consulta_invent);

$sql_adm = "SELECT * FROM administrador WHERE cuenta = '$cuenta_actual'";
$consulta_adm = mysql_query($sql_adm, $conectar) or die(mysql_error());
$matriz_adm = mysql_fetch_assoc($consulta_adm);

$cod_base_caja = $matriz_adm['cod_base_caja'];

$sql_caja = "SELECT * FROM base_caja WHERE cod_base_caja = '$cod_base_caja'";
$consulta_caja = mysql_query($sql_caja, $conectar) or die(mysql_error());
$caja = mysql_fetch_assoc($consulta_caja);

$total_ventas = $filtro['vlr_total_venta'];
$total_caja = $caja['valor_caja'] + $total_ventas;

$sql = "SELECT * FROM inventario WHERE cuenta = '$cuenta_actual' ORDER BY nombre_valor DESC";
$consulta = mysql_query($sql, $conectar) or die(mysql_error());
?>
<center>
<table width="100%">
<td><strong><font size='5' color="white">PLATA: <?php echo number_format($invent['invent_total']);?></font></strong></td>
<td><strong><font size='5' color="white">VENTAS: <?php echo number_format($total_ventas);?></font></strong></td>
<td><strong><font size='5' color="white">BASE CAJA: <?php echo number_format($caja['valor_caja']);?></font></strong></td>
<td><strong><font size='5' color="white">TOTAL CAJA: <?php echo number_format($total_caja);?></td>
<td><a href="../admin/crear_plata.php"><strong><font size='5' color="white">CREAR</font></strong></a></td><br>


</table>
<br>
<table>
<?php
if ($invent['invent_total'] == $total_caja) {
?>
<td><strong><font size='5' color="white">CORRECTO: <?php echo number_format($invent['invent_total'] - $total_caja);?></font></strong></td>
<?php
} elseif ($invent['invent_total'] > $total_caja) {
?>
<td><strong><font size='5' color="yellow">SOBRA: <?php echo number_format($invent['invent_total'] - $total_caja);?></font></strong></td>
<?php
} else {
?>
<td><strong><font size='5' color="red">FALTA: <?php echo number_format(($invent['invent_total'] - $total_caja) * -1);?></font></strong></td>
<?php
}
?>
</table>
<br>
<table width="30%">
<tr>
<td><div align="center"><strong>PLATA</strong></div></td>
<td><div align="center"><strong>UND</strong></div></td>
<td><div align="center"><strong>TOTAL</strong></div></td>
<td><div align="center"><strong>OK</strong></div></td>
</tr>
<?php
while ($datos = mysql_fetch_assoc($consulta)) {
$cod_inventario = $datos['cod_inventario'];
$nombre_valor = $datos['nombre_valor'];
$numero = $datos['numero'];
$total = $datos['total'];
?>
<tr>
<td><div align='right'><font size='5'><?php echo number_format($nombre_valor);?></font></div></td>
<td><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'numero', <?php echo $cod_inventario;?>)" class="cajund" id="<?php echo $cod_inventario;?>" value="<?php echo $numero;?>" size="3"></td>
<td><div align='right'><font size='5'><?php echo number_format($total);?></font></div></td>
<td><a href="<?php $_SERVER['PHP_SELF']?>"><center><img src=../imagenes/correcto.png alt="Listo"></center></a></td> 
</tr>
<?php }
?>
</table>
</center>
</form>