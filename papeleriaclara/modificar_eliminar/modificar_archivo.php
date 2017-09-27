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
date_default_timezone_set("America/Bogota");

$edicion_de_formulario = $_SERVER['PHP_SELF'];

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "formulario_de_actualizacion")) {
$actualizar_sql = sprintf("UPDATE facturas_cargadas SET cod_facturas=%s, fecha_llegada=%s, cod_proveedor=%s WHERE cod_facturas_cargadas=%s",
                       envio_valores_tipo_sql($_POST['cod_facturas'], "text"),
                       envio_valores_tipo_sql($_POST['fecha_llegada'], "text"),
                       envio_valores_tipo_sql($_POST['cod_proveedor'], "text"),
                  	   envio_valores_tipo_sql($_POST['cod_facturas_cargadas'], "text"));

$resultado_actualizacion = mysql_query($actualizar_sql, $conectar) or die(mysql_error());

echo '<META HTTP-EQUIV="REFRESH" CONTENT="0.2; ../admin/descargar_facturas.php">';
}
$cod_facturas_cargadas = mysql_escape_string($_GET['cod_facturas_cargadas']);

$sql_modificar_consulta = "SELECT * FROM facturas_cargadas where cod_facturas_cargadas = '$cod_facturas_cargadas'";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$matriz_modificar_consulta = mysql_fetch_assoc($modificar_consulta);
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
      <td nowrap align="right">Codigo Facturas:</td>
      <td><input type="text" name="cod_facturas" value="<?php echo $matriz_modificar_consulta['cod_facturas']; ?>" size="30"></td>
    </tr>
<tr valign="baseline">
      <td nowrap align="right">Fecha Factura:</td>
      <td><input type="text" name="fecha_llegada" value="<?php echo $matriz_modificar_consulta['fecha_llegada']; ?>" size="30"></td>
    </tr>
<tr valign="baseline">
<td nowrap align="right">Proveedor:</td>
<td><select name='cod_proveedor'>
<?php 
$dato_guardado1 = $matriz_modificar_consulta['cod_proveedor'];

$sql_buscar_proveedores = "SELECT * FROM proveedores where cod_proveedores LIKE '$dato_guardado1'";
$dato_proveedores = mysql_query($sql_buscar_proveedores, $conectar) or die(mysql_error());
$proveedor_encontrado = mysql_fetch_assoc($dato_proveedores);

$sql_consulta="SELECT * FROM proveedores order by nombre_proveedores";
$resultado_marcas = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while (list($contenedor_marcas)=mysql_fetch_array($resultado_marcas)) {
if ($contenedor_marcas == $dato_guardado1) { ?>
<option selected value="<?php echo $proveedor_encontrado['cod_proveedores'] ?>"><?php echo $proveedor_encontrado['nombre_proveedores'] ?></option>
<?php } else { ?>
<option><?php echo $contenedor_marcas; ?></option>
<?php }} ?>
</select>
</td>
<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td><input type="submit" id="boton1" value="Actualizar registro"></td>
</tr>
</table>
<input type="hidden" name="MM_update" value="formulario_de_actualizacion">
<input type="hidden" name="cod_facturas_cargadas" value="<?php echo $matriz_modificar_consulta['cod_facturas_cargadas']; ?>">
</form>
</center>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($modificar_consulta);
?>
<center>
<a href="../admin/descargar_facturas.php"><font color='yellow'>Regresar</font></a>