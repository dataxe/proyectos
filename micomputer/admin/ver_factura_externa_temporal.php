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
include ("../formato_entrada_sql/funcion_env_val_sql.php");
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");

$cod_factura = $_GET['cod_factura'];

$mostrar_datos_sql = "SELECT * FROM exportacion WHERE cod_factura = '$cod_factura' ORDER BY cod_exportacion DESC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$matriz_consulta = mysql_fetch_assoc($consulta);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<center>
<a href="exportacion_lista.php"><font color='yellow'>REGRESAR</font></a><br><br>
<td><strong><font color='yellow'>LISTA EXPORTACION: </font></strong></td><br><br>
<table id="table" width="80%">
<tr>
<td><div align="center"><strong>CODIGO</strong></div></td>
<td><div align="center"><strong>PRODUCTO</strong></div></td>
<td><div align="center"><strong>CAJAS</strong></div></td>
<td><div align="center"><strong>UND</strong></div></td>
<td><div align="center"><strong>U.TOTAL</strong></div></td>
<td><div align="center"><strong>FACTURA</strong></div></td>
<td><div align="center"><strong>CUENTA</strong></div></td>
<td><div align="center"><strong>FECHA</strong></div></td>
<td><div align="center"><strong>HORA</strong></div></td>
<?php do { ?>
<tr>
<td><?php echo $matriz_consulta['cod_productos']; ?></td>
<td><?php echo $matriz_consulta['nombre_productos']; ?></td>
<td align="center"><?php echo $matriz_consulta['cajas']; ?></td>
<td align="center"><?php echo $matriz_consulta['unidades']; ?></td>
<td align="center"><?php echo $matriz_consulta['unidades_total']; ?></td>
<td align="center"><?php echo $matriz_consulta['cod_factura']; ?></td>
<td><?php echo $matriz_consulta['vendedor']; ?></td>
<td align="center"><?php echo $matriz_consulta['fecha_anyo']; ?></td>
<td align="center"><?php echo $matriz_consulta['fecha_hora']; ?></td>
</tr>
<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
</table>