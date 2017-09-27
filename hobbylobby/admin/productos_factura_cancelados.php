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
?>
<center>
<form method="post" id="table" name="formulario" action="productos_factura_cancelados.php">
<table align="center" id="table">
<td nowrap align="right">Cod Factura:</td>
<td bordercolor="0">
<select name="palabra">
<?php $sql_consulta1="SELECT DISTINCT cod_factura FROM factura_producto_cancelado ORDER BY cod_factura DESC";
$resultado = mysql_query($sql_consulta1, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option value="<?php echo $contenedor['cod_factura'] ?>"><?php echo $contenedor['cod_factura'] ?></option>
<?php }?>
</select></td>
<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td bordercolor="1"><input type="submit" value="Consultar Factura"></td>
</tr>
</table>
</form>
</center>
<?php
$buscar = $_POST['palabra'];
$mostrar_datos_sql = "SELECT * FROM factura_producto_cancelado WHERE cod_factura like '%$buscar%' order by cod_factura DESC";
$consulta_facturacion = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$matriz_facturacion = mysql_fetch_assoc($consulta_facturacion);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<p>
<center>
<table id="table" id="table">
<tr><br>
<td><div align="center" ><strong>Codigo</strong></div></td>
<td><div align="center" ><strong>Producto</strong></div></td>
<td><div align="center" ><strong>Unidades</strong></div></td>
<!--<td><div align="center" ><strong>Cliente</strong></div></td>-->
<td><div align="center" ><strong>Factura</strong></div></td>
<td><div align="center" ><strong>Valor</strong></div></td>
<td><div align="center" ><strong>Vlr Total</strong></div></td>
<td><div align="center" ><strong>Vendedor</strong></div></td>
<td><div align="center" ><strong>Fecha</strong></div></td>
<?php do { ?>
<tr>
<td><?php echo $matriz_facturacion['cod_productos']; ?></td>
<td><?php echo $matriz_facturacion['nombre_productos']; ?></td>
<td align="right"><?php echo $matriz_facturacion['unidades_vendidas']; ?></td>
<!--<td><?php //echo $matriz_facturacion['cliente']; ?></td>-->
<td align="right"><?php echo $matriz_facturacion['cod_factura']; ?></td>
<td align="right"><?php echo  number_format($matriz_facturacion['vlr_unitario']); ?></td>
<td align="right"><?php echo  number_format($matriz_facturacion['vlr_total']); ?></td>
<td align="right"><?php echo $matriz_facturacion['vendedor']; ?></td>
<td align="right"><?php echo $matriz_facturacion['fecha']; ?></td>
</tr>
<?php } while ($matriz_facturacion = mysql_fetch_assoc($consulta_facturacion)); ?>
</table>