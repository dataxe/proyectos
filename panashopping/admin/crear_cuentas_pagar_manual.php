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
date_default_timezone_set("America/Bogota");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<br>
<center>
<td><strong><a href="../admin/cuentas_pagar.php"><font color='white'>REGRESAR</font></a><font color='yellow'> - NUEVA CUENTA POR PAGAR</font></strong></td>
<form method="post" name="formulario" action="agregar_cuentas_pagar_manual.php">
<br>
<table align="center">
<tr>
<td align="center"><strong>COD FACTURA</strong></td>
<td align="center"><strong>PROVEEDOR</strong></td>
<td align="center"><strong>DEUDA</strong></td>
<td align="center"><strong>FECHA PAGO</strong></td>
</tr>
<?php do { ?>
<tr>
<td align="center"><input type="text" style="font-size:30px" name="cod_factura" value="" size="12" required autofocus></td>

<td align="center"><select name="cod_proveedores">
<?php $sql_consulta="SELECT cod_proveedores, nombre_proveedores FROM proveedores ORDER BY nombre_proveedores ASC";
$resultado = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option style="font-size:30px" value="<?php echo $contenedor['cod_proveedores'] ?>"><?php echo $contenedor['nombre_proveedores'] ?></option>
<?php }?>
</select></td>

<td align="center"><input type="text" style="font-size:30px" name="monto_deuda" value="" size="12" required autofocus></td>

<td align="center"><input type="text" style="font-size:30px" name="fecha" value="<?PHP echo date("d/m/Y")?>" size="12" required autofocus></td>

</tr>
<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
</table>
<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td bordercolor="1"><input type="submit" id="boton1" value="Agregar"></td>
</tr>
<input type="hidden" name="insertar_datos" value="formulario">
</form>
</center>
</body>
</html>