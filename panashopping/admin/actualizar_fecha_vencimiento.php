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
//----------------------------------------------------------------------------------------------------------------//
//----------------------------------------------------------------------------------------------------------------//
$datos_user = "SELECT cod_seguridad, cuenta FROM administrador WHERE cuenta = '$cuenta_actual'";
$consulta_user = mysql_query($datos_user, $conectar) or die(mysql_error());
$info_user = mysql_fetch_assoc($consulta_user);

$cod_seguridad = $info_user['cod_seguridad'];
//----------------------------------------------------------------------------------------------------------------//
//----------------------------------------------------------------------------------------------------------------//
$fecha_actual_en_segundos = time();
$fecha_actual_segundos = time();

$mostrar_meses_vencer = "SELECT * FROM meses_vencimiento WHERE cod_meses_vencimiento = '1'";
$consulta_meses_vencer = mysql_query($mostrar_meses_vencer, $conectar) or die(mysql_error());
$meses_vencer = mysql_fetch_assoc($consulta_meses_vencer);

//Queremos sumar 6 meses a la fecha actual:
$meses = $meses_vencer['meses'];
// Convertimos los meses a segundos y se los sumamos sumamos a la fecha_actual_segundos:
$fecha_actual_segundos += ($meses * 30 * 24 * 60 * 60);
/*
// Le damos al resultado el formato deseado:
$fecha_6_meses = date("d/m/Y", $fecha_actual_segundos);
$fecha_actual_normal = date("d/m/Y", $fecha_actual_en_segundos);
$fecha_vencimiento = '2013/11/18';
$fecha_vencimiento_segundos =  strtotime($fecha_vencimiento);
$fecha_vencimeinto_normal = date("d/m/Y", $fecha_vencimiento_segundos);
$resta_total = $fecha_actual_segundos - $fecha_vencimiento_segundos;
$fecha_resta_normal = date("d/m/Y", $resta_total);
*/
$buscar = addslashes($_POST['buscar']);

$mostrar_datos_sql = "SELECT * FROM productos WHERE (cod_productos_var = '$buscar' OR  nombre_productos LIKE '$buscar%') AND 
(fechas_vencimiento_seg  <= '$fecha_actual_segundos' AND fechas_vencimiento_seg <> '') ORDER BY fechas_vencimiento_seg";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);

$pagina = $_SERVER['PHP_SELF'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
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
myajax.Link('guardar_actualizar_fecha_vencimiento.php?valor='+valor+'&campo='+campo+'&id='+id);
}
</script>
</head>
<body>

<body onLoad="myajax = new isiAJAX();">
<br>
<center>
<td align="center"><font color="yellow" size="+2"><strong>ACTUALIZAR FECHA DE VENCIMIENTO - </font><a href="../admin/ver_actualizar_fecha_vencimiento.php"><font color="yellow" size="+2">VER LISTA</a></font></td>
<br><br>
<strong><font size='4' color='yellow'>PRODUCTOS A VENCER DENTRO DE <?php echo $meses; ?> MESES</font></strong>
<a href="../admin/cambiar_numero_meses.php?pagina=<?php echo $pagina?>"><font size='3' color='yellow'>(CAMBIAR NUMERO DE MESES)</font></a>
<br><br>
<center>
<form action="" method="post">
<input name="buscar" autofocus/>
<input type="submit" name="buscador" value="Buscar Productos" />
</form>
</center>
<table>
<tr>
<td align="center"><strong><font size='3px'>C&Oacute;DIGO</font></strong></td>
<td align="center"><strong><font size='3px'>NOMBRES</font></strong></td>
<td align="center"><strong><font size='3px'>UNDS</font></strong></td>
<td align="center"><font size='3px'>PRECIO VENTA</font></td>
<td align="center"><strong><font size='3px'>VENCIMIENTO</strong></td>
<?php do { 
$cod_productos = $datos['cod_productos'];
$cod_productos_var = $datos['cod_productos_var'];
$nombre_productos = $datos['nombre_productos'];
$unidades_faltantes = $datos['unidades_faltantes'];
$precio_venta = $datos['precio_venta'];
$fechas_vencimiento = $datos['fechas_vencimiento'];
?>
<tr>
<td><font size='3px'><?php echo $cod_productos_var; ?></font></td>
<td><font size='3px'><?php echo $nombre_productos; ?></font></td>
<td align="center"><font size='3px'><?php echo $unidades_faltantes; ?></font></td>
<td align="right"><font size='3px'><?php echo number_format($precio_venta, 0, ",", "."); ?></font></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'fechas_vencimiento', <?php echo $cod_productos;?>)" class="cajbarras" id="<?php echo $cod_productos;?>" value="<?php echo $fechas_vencimiento;?>" size="5"></td>
</tr>
<?php } while ($datos = mysql_fetch_assoc($consulta)); ?>
</table>