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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<center>
<br><br>
<td><strong><font color='yellow' size='+1'>INFORMACION CAJA FISICA: </font></strong></td><br><br>
<table width="60%">
<tr>
<td align="center"><strong>TOTAL FISICO</strong></td>
<td align="center"><strong>TOTAL SISTEMA</strong></td>
<td align="center"><strong>DIFERENCIA</strong></td>
<td align="center"><strong>FECHA</strong></td>
<td align="center"><strong>HORA</strong></td>
<td align="center"><strong>VENDEDOR</strong></td>
</tr>
<?php do { ?>
<tr>
<td align="right"><font size='+1'><?php echo number_format($datos['total_ventas_fisico'], 0, ",", "."); ?></font></td>
<td align="right"><font size='+1'><?php echo number_format($datos['total_ventas_sistema'], 0, ",", "."); ?></font></td>
<td align="right"><font size='+1'><?php echo number_format($datos['total_ventas_sistema'] - $datos['total_ventas_fisico'], 0, ",", "."); ?></font></td>
<td align="center"><font size='+1'><?php echo date("d/m/Y", $datos['fecha']); ?></font></td>
<td align="center"><font size='+1'><?php echo $datos['fecha_hora']; ?></font></td>
<td><font size='+1'><?php echo $datos['usuario']; ?></font></td>
</tr>
<?php } while ($datos = mysql_fetch_assoc($consulta)); ?>
</table>
</center>
</body>