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

$edicion_de_formulario = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $edicion_de_formulario .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$subtotal = $_POST['total'] - $_POST['abonado'];
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "formulario_de_actualizacion")) {
 $actualizar_sql = sprintf("UPDATE cuentas_facturas SET cod_proveedores=%s, total=%s, abonado=%s, subtotal=%s, fecha_pago=%s WHERE cod_cuentas_facturas=%s",
  envio_valores_tipo_sql($_POST['cod_proveedores'], "text"),
  envio_valores_tipo_sql($_POST['total'], "text"),
  envio_valores_tipo_sql($_POST['abonado'], "text"),
  envio_valores_tipo_sql($subtotal, "text"),
  envio_valores_tipo_sql($_POST['fecha_pago'], "text"),
  envio_valores_tipo_sql($_POST['cod_cuentas_facturas'], "text"));

$resultado_actualizacion = mysql_query($actualizar_sql, $conectar) or die(mysql_error());
echo '<META HTTP-EQUIV="REFRESH" CONTENT="0.2; ../admin/cuentas_pagar.php">';
}
$valor = $_GET['cod_factura'];

$sql_modificar_consulta = "SELECT * FROM cuentas_facturas WHERE cod_factura = '$valor'";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($modificar_consulta);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>Documento sin t&iacute;tulo</title>
</head>
<br>
<body>
<center>
<form method="post" name="formulario_de_actualizacion" action="<?php echo $edicion_de_formulario; ?>">
<table align="center">
<tr valign="baseline">
<td nowrap align="left"><font size="6">FACTURA NO:</font></td>
<td><input type="text" name="cod_factura" style="font-size:28px" value="<?php echo $datos['cod_factura']; ?>" size="30"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left"><font size="6">PROVEEDOR:</font></td>
<td>
<select name='cod_proveedores' style="font-size:28px">
<?php $dato_guardado1 = $datos['cod_proveedores'];

$sql_buscar_proveedores = "SELECT * FROM proveedores where cod_proveedores = $dato_guardado1";
$dato_proveedores = mysql_query($sql_buscar_proveedores, $conectar) or die(mysql_error());
$proveedores_encontrado = mysql_fetch_assoc($dato_proveedores);

$sql_consulta="SELECT * FROM proveedores order by cod_proveedores";
$resultado_proveedores = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while (list($contenedor_proveedores)=mysql_fetch_array($resultado_proveedores)) {
if ($contenedor_proveedores == $dato_guardado1) { ?>
<option selected value="<?php echo $proveedores_encontrado['cod_proveedores'] ?>"><?php echo $proveedores_encontrado['nombre_proveedores'] ?></option>
<?php } else { ?>
<option><?php echo $contenedor_proveedores; ?></option>
<?php }} ?>
</select>
</tr>
<tr valign="baseline">
<td nowrap align="left"><font size="6">MONTO DEUDA:</font></td>
<td><input type="text" name="total" style="font-size:28px" value="<?php echo $datos['total']; ?>" size="30"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left"><font size="6">ABONADO:</font></td>
<td><input type="text" name="abonado" style="font-size:28px" value="<?php echo $datos['abonado']; ?>" size="30"  required autofocus></td>
</tr>
<tr valign="baseline">
<td nowrap align="left"><font size="6">DEUDA:</font></td>
<td><input type="text" name="subtotal" style="font-size:28px" value="<?php echo $datos['subtotal']; ?>" size="30"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left"><font size="6">FECHA PAGO:</font></td>
<td><input type="text" name="fecha_pago" style="font-size:28px" value="<?php echo $datos['fecha_pago']; ?>" size="30"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">&nbsp;</td>
<td><input type="submit" value="Actualizar registro"></td>
</tr>
</table>
<input type="hidden" name="MM_update" value="formulario_de_actualizacion">
<input type="hidden" name="cod_cuentas_facturas" value="<?php echo $datos['cod_cuentas_facturas']; ?>">
</form>
</center>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($modificar_consulta);
?>
