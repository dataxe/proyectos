<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar);
date_default_timezone_set("America/Bogota");
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
$fecha = date("d/m/Y");
$hora = date("H:i:s");

$mostrar_datos_sql = "SELECT cod_camparacion_tablas, cod_productos, nombre_productos, unidades_faltantes, unidades_total 
FROM camparacion_tablas WHERE cod_factura = '$cod_factura'";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$tota_datos = mysql_num_rows($consulta);
$datos = mysql_fetch_assoc($consulta);

$pagina = 'exportacion_lista.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<center>
<br>
<a href="exportacion_lista_subida.php"><font color='yellow'>REGRESAR</font></a><br><br>
<td><strong><font color='yellow' size="6px">FACTURA <?php echo $cod_factura?>. AUDITORIA INVENTARIO DIA: <?php echo $fecha.' - '.$hora?> </font></strong></td><br><br>

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
myajax.Link('guardar_cargar_comparacion_tablas.php?valor='+valor+'&campo='+campo+'&id='+id);
}
</script>

</head>
<body onLoad="myajax = new isiAJAX();">
<form name="form1" id="form1" action="#" method="post" style="margin:1;">  
<?php
if ($tota_datos <> 0) {
?>
<table width="95%">
<tr>
<td align="center"><strong>CODIGO</strong></td>
<td align="center"><strong>PRODUCTO</strong></td>
<td align="center"><strong>UND SISTEMA</strong></td>
<td align="center"><strong>UND FISICA</strong></td>
<td align="center"><strong></strong></td>
<td align="center"><strong>CORRECION</strong></td>
<td align="center"><strong>OK</strong></td>
<!--
<td><strong>DESCARGAR</strong></td>
<td><strong>VER</strong></td>
<td><strong>FECHA</strong></td>
<td><strong>HORA</strong></td>
-->
<?php do {
$cod_camparacion_tablas = $datos['cod_camparacion_tablas'];
$cod_productos = $datos['cod_productos'];
$nombre_productos = $datos['nombre_productos'];
$nombre1 = $datos['nombre1'];
$unidades_faltantes = $datos['unidades_faltantes'];
$unidades_total = $datos['unidades_total'];
$resta = $unidades_total - $unidades_faltantes;
?>
<tr>
<td><font size="3px"><?php echo $cod_productos; ?></font></td>
<td><font size="3px"><?php echo utf8_decode($nombre_productos); ?></font></td>
<td align="center"><font size="3px"><?php echo $unidades_faltantes; ?></font></td>
<td align="center"><font size="3px"><?php echo $unidades_total; ?></font></td>
<?php
//SI EXISTEN HAY MAS UNIDADES EN EL SISTEMA QUE FISICAS. UNIDADES EXTRAVIADAS 
if ($resta < 0) {
?>
<td align="right" color="yellow"><font size="3px"><?php echo ($resta * -1) ?></font></td>
<td align="left" color="yellow"><font size="3px">UNDS FALTAN</font></td>
<?php
}
// SI HAY MAS UNIADES FISICAS QUE EN EL SISTEMA. 
elseif ($resta > 0) {
?>
<td align="right" color="yellow"><font size="3px"><?php echo $resta ?></font></td>
<td align="left" color="yellow"><font size="3px">UNDS SOBRAN</font></td>
<!--<td align='center'><input name="unidades_total" size="1" value="<?php echo $unidades_total;?>"></font></td>-->
<?php
// SI LAS UNIDADES DEL SISTEMA SON LAS MISMAS QUE HAY EN FISICO. EL INVENTARIO ES CORRECTO
} else {
?>
<td align="right" color="white"><font size="3px"><?php echo $resta ?></font></td>
<td align="left" color="white"><font size="3px">BIEN</font></td>
<?php
}
// SI HAY MAS UNIADES FISICAS QUE EN EL SISTEMA. 
if ($resta > 0) {
?>
<td><a href="../modificar_eliminar/modificacion_productos_comparacion.php?actualizar=actualizar&cod_productos=<?php echo $cod_productos?>&unidades_faltantes=<?php echo $unidades_total?>&cod_camparacion_tablas=<?php echo $cod_camparacion_tablas?>&cod_factura=<?php echo $cod_factura?>"><center><img src=../imagenes/borrar.gif alt="borrar"></center></a></td>
<?php
//SI EXISTEN HAY MAS UNIDADES EN EL SISTEMA QUE FISICAS. UNIDADES EXTRAVIADAS 
} elseif($resta < 0) {
?>
<td><a href="../admin/comparacion_tablas_resultado_extraviados.php?eliminar=eliminar&cod_factura=<?php echo $cod_factura?>&cod_productos=<?php echo $cod_productos?>"><center><img src=../imagenes/auxilio.gif alt="auxilio"></center></a></td>
<?php
// SI LAS UNIDADES DEL SISTEMA SON LAS MISMAS QUE HAY EN FISICO. EL INVENTARIO ES CORRECTO
} elseif($resta == 0) {
?>
<td><a href="../modificar_eliminar/modificacion_productos_comparacion.php?eliminar=eliminar&cod_camparacion_tablas=<?php echo $cod_camparacion_tablas?>&cod_factura=<?php echo $cod_factura?>"><center><img src=../imagenes/bien.png alt="Bien"></center></a></td>
<?php
}
?>
</tr>
<?php } while ($datos = mysql_fetch_assoc($consulta)); ?>
</table>
</form>
<?php
} else {
//echo "<br><center><font size='4px' color='yellow'>NO HAY NINGUNA FACTURA EXTERNA CARGADA<center>";
}
?>