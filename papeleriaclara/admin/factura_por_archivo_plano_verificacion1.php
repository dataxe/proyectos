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

$mostrar_datos_sql = "SELECT * FROM  cuentas_facturas2 order by cod_factura DESC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);

$pagina = $_SERVER['PHP_SELF'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<br><br>
<center>
<td><strong><font color='yellow' size="5px">VERIFICACION DE FACTURAS POR ARCHIVO PLANO</font></strong></td><br><br>
<td>
<a href="../admin/importar_cargar_factura_archivo_plano.php"><font color='white'>ATRAS</font></a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="../admin/factura_por_archivo_plano_verificacion2.php"><font color='yellow'>SEGUNDA VERIFICACION</font></a>
</td>
</center>
<br>
<center>
<table width="60%">
<tr>
<td align="center"><strong>ELM</strong></td>
<td align="center"><strong>FACTURA</strong></td>
<td align="center"><strong>FECHA</strong></td>
</tr>
<?php do { 
$cod_factura = $datos['cod_factura'];
?>
<tr>
<td align="center"><a href="../modificar_eliminar/eliminar.php?tipo=eliminar&tab=cuentas_facturas2&cod_factura=<?php echo $cod_factura?>&pagina=<?php echo $pagina?>"><img src=../imagenes/eliminar.png alt="eliminar"></a></td>
<td align="center"><?php echo $datos['cod_factura']; ?></td>
<td align="center"><?php echo $datos['fecha_pago']; ?></td>
</tr>
<?php } while ($datos = mysql_fetch_assoc($consulta)); ?>
</table>
</body>
</html>
<?php mysql_free_result($consulta);