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
?>
<center>
<table id="table" width="800">

 <td nowrap align="right"><strong>FACTURA N&ordm;:</td>
<?php $obtener_facturacion = "SELECT cod_factura, numero_factura FROM factura_cod WHERE cod_factura = 1";
$modificar_facturacion = mysql_query($obtener_facturacion, $conectar) or die(mysql_error());
$contenedor_factura = mysql_fetch_assoc($modificar_facturacion);
$num_facturacion = mysql_num_rows($modificar_facturacion); ?>
<td><input type="text" name="numero_factura" value="<?php echo $contenedor_factura['numero_factura']; ?>" size="8"></td>

<?php $factura_act = $contenedor_factura['numero_factura'];?>

<td nowrap align="right"><strong>FECHA:</td>
<?php $obtener_fecha = "SELECT cod_facturacion, fecha FROM facturacion WHERE cod_facturacion = '$factura_act'";
$consultar_fecha = mysql_query($obtener_fecha, $conectar) or die(mysql_error());
$matriz_fecha = mysql_fetch_assoc($consultar_fecha);
$num_fecha = mysql_num_rows($consultar_fecha); ?>
<td><input type="text" name="fech" value="<?php echo $matriz_fecha['fecha']; ?>" size="8"></td>
 </tr>
</table>
</center>
<?php
$buscar = $contenedor_factura['numero_factura'];

$muestra_faltante = $numero_de_pagina * $numero_maximo_de_muestra;
$datos_factura = "SELECT * FROM facturacion WHERE cod_facturacion like '$buscar'";
$limit_consultado = sprintf("%s LIMIT %d, %d", $datos_factura, $muestra_faltante, $numero_maximo_de_muestra);
$consulta = mysql_query($limit_consultado, $conectar) or die(mysql_error());
$matriz_consulta = mysql_fetch_assoc($consulta);

$datos_total_factura = "SELECT  Sum(vlr_total) as vlr_totl FROM facturacion WHERE cod_facturacion like '$buscar'";
$limit_consult = sprintf("%s LIMIT %d, %d", $datos_total_factura, $muestra_faltante, $numero_maximo_de_muestra);
$consulta_total = mysql_query($limit_consult, $conectar) or die(mysql_error());
$matriz_total_consulta = mysql_fetch_assoc($consulta_total);

$datos_vendedor = "SELECT DISTINCT * FROM facturacion WHERE cod_facturacion = $buscar";
$limit_vendedor = sprintf("%s LIMIT %d, %d", $datos_vendedor, $muestra_faltante, $numero_maximo_de_muestra);
$total_vendedor = mysql_query($limit_vendedor, $conectar) or die(mysql_error());
$matriz_vendedor = mysql_fetch_assoc($total_vendedor);

if (isset($_GET['numero_total_de_registros'])) {
  $numero_total_de_registros = $_GET['numero_total_de_registros'];
} else {
  $todo_consulta = mysql_query($datos_factura);
  $numero_total_de_registros = mysql_num_rows($todo_consulta);
}
$total_pagina_consulta = ceil($numero_total_de_registros/$numero_maximo_de_muestra)-1;

$consulta_caracter_vacio = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $parametros = explode("&", $_SERVER['QUERY_STRING']);
  $nuevos_parametros = array();
  foreach ($parametros as $parametro) {
    if (stristr($parametro, "numero_de_pagina") == false && 
        stristr($parametro, "numero_total_de_registros") == false) {
      array_push($nuevos_parametros, $parametro);
    }
  }
  if (count($nuevos_parametros) != 0) {
    $consulta_caracter_vacio = "&" . htmlentities(implode("&", $nuevos_parametros));
  }
}
$consulta_caracter_vacio = sprintf("&numero_total_de_registros=%d%s", $numero_total_de_registros, $consulta_caracter_vacio);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<?php $calculo_subtotal = $matriz_total_consulta['vlr_totl'] - $_POST['descuento_factura']; 
$calculo_total = $calculo_subtotal;
?>
<form method="post" name="formulario" action="../admin/imprimir_factura.php">
<center>
<table id="superior"width="800" id="table">
<tr valign="baseline">
<td nowrap align="left"><strong>MOTO:</td>
<td><?php echo $matriz_vendedor['moto']; ?></td>
</tr>
<tr valign="baseline">
<td nowrap align="left"><strong>NOMBRE:</td>
<td><?php echo $matriz_vendedor['nombre']; ?></td>
</tr>
<tr valign="baseline">
<td nowrap align="left"><strong>DIRRECION:</td>
<td><?php echo $matriz_vendedor['direccion']; ?></td>
</tr>
<tr valign="baseline">
<td nowrap align="left"><strong>CIUDAD:</td>
<td><?php echo $matriz_vendedor['ciudad']; ?></td>
</tr>
<tr valign="baseline">
<td nowrap align="left"><strong>VENDEDOR:</td>
<td><?php echo $matriz_vendedor['vendedor']; ?></td>
</tr>
<table id="table" width="800">
<tr>
<td><div align="center"><strong>CAN</strong></div></td>
<td><div align="center"><strong>REFERENCIA</strong></div></td>
<td><div align="center"><strong>PRODUCTO</strong></div></td>
<td><div align="center"><strong>MARCA</strong></div></td>
<td><div align="center"><strong>DESCRIPCION</strong></div></td>
<td><div align="center"><strong>V. UNITARIO</strong></div></td>
<td><div align="center"><strong>V. TOTAL</strong></div></td>
</tr>
<?php do { ?>
<tr>
<td><?php echo $matriz_consulta['cantidad']; ?></td>
<td><?php echo $matriz_consulta['cod_producto']; ?></td>
<td><?php echo $matriz_consulta['nombre_productos']; ?></td>
<td><?php echo $matriz_consulta['marca']; ?></td>
<td><?php echo $matriz_consulta['descripcion']; ?></td>
<td align="right"><?php echo $matriz_consulta['vlr_unitario']; ?></td>
<td align="right"><?php echo $matriz_consulta['vlr_total']; ?></td>
</tr>	 <?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
	
<table id="table" width="800">
<tr>
<td><div align="center"><strong>SUBTOTAL</strong></div></td>
<td><div align="center"><strong>DESCUENTO</strong></div></td>
<td><div align="center"><strong>SUBTOTAL</strong></div></td>
<td><div align="center"><strong>IVA</strong></div></td>
<td><div align="center"><strong>FLETE</strong></div></td>
<td><div align="center"><strong>TOTAL</strong></div></td>
</tr>
<?php do { ?>
<tr>
<td ><?php echo $matriz_total_consulta['vlr_totl']; ?></td>
<td width="10"><input type="text" name="descuento_factura" value="0" size="15"></td>
<td ><?php echo $calculo_subtotal; ?></td>
<td width="10"><input type="text" name="iva" value="0" size="15"></td>
<td width="10"><input type="text" name="flete" value="0" size="15"></td>
<td ><?php echo '.'; ?></td>
</tr>
<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
<td align="center"><input type="submit" value="Ver Factura" /></td>
</form>