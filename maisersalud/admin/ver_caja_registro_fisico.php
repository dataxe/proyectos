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

$nivel_acceso = '3';
if ($seguridad_acceso['cod_seguridad'] <> $nivel_acceso) {
header("Location:../admin/acceso_denegado.php");
}
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");

$mostrar_datos_sql = "SELECT * FROM caja_registro_fisico ORDER BY fecha DESC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);

$pagina = $_SERVER['PHP_SELF'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
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
myajax.Link('guardar_caja_registro_fisico_y_sistema.php?valor='+valor+'&campo='+campo+'&id='+id);
}
</script>
</head>
<body onLoad="myajax = new isiAJAX();">
<form name="form1" id="form1" action="#" method="post" style="margin:1;">
<center>
<br><br>
<td><strong><font color='yellow' size='+1'>INFORMACION CAJA FISICA: </font></strong></td><br><br>
<table width="70%">
<tr>
<td align="center"><strong>TOTAL FISICO</strong></td>
<td align="center"><strong>TOTAL SISTEMA</strong></td>
<td align="center"><strong>DIFERENCIA</strong></td>
<td align="center"><strong>FECHA</strong></td>
<td align="center"><strong>HORA</strong></td>
<td align="center"><strong>VENDEDOR</strong></td>
<td align="center"><strong>OK</strong></td>
</tr>
<?php do {
$cod_caja_registro_fisico = $datos['cod_caja_registro_fisico'];
$total_ventas_fisico = $datos['total_ventas_fisico'];
$total_ventas_sistema = $datos['total_ventas_sistema'];
?>
<tr>
<td><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'cajas', <?php echo $cod_caja_registro_fisico;?>)" class="cajvtotal" id="<?php echo $cod_caja_registro_fisico;?>" value="<?php echo $total_ventas_fisico;?>" size="7"></td>
<td align="right"><font size='+3'><?php echo number_format($total_ventas_sistema, 0, ",", "."); ?></font></td>
<td align="right"><font size='+3'><?php echo number_format($total_ventas_fisico - $total_ventas_sistema, 0, ",", "."); ?></font></td>
<td align="center"><font size='+2'><?php echo date("d/m/Y", $datos['fecha']); ?></font></td>
<td align="center"><font size='+2'><?php echo $datos['fecha_hora']; ?></font></td>
<td><font size='+2'><?php echo $datos['usuario']; ?></font></td>
<td><a href="../admin/actualizar_caja_registro_fisico_y_sistema.php?cod_caja_registro_fisico=<?php echo $cod_caja_registro_fisico?>&pagina=<?php echo $pagina?>" tabindex=3><center><img src=../imagenes/correcto.png alt="correcto"><?php echo $unidades_cajas?></center></a></td> 
</tr>
<?php } while ($datos = mysql_fetch_assoc($consulta)); ?>
</table>
</center>
</body>