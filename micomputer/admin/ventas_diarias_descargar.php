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
/*
$verificar_pag = "SELECT * FROM pagina_actual WHERE cod_pagina_actual='1'";
$resultado_verificar_pag = mysql_query($verificar_pag, $conectar) or die(mysql_error());

$nombre_pagina_actual = 'VENTAS';
if ($resultado_verificar_pag['nombre_pagina_actual'] <> $nombre_pagina_actual) {
$actualizar_nombre_pagina_actual = sprintf("UPDATE pagina_actual SET nombre_pagina_actual=%s WHERE cod_pagina_actual='1'",
envio_valores_tipo_sql($nombre_pagina_actual, "text"), envio_valores_tipo_sql($_POST['cod_pagina_actual'], "text"));
$resultado_actualizacion_pagina_actual = mysql_query($actualizar_nombre_pagina_actual, $conectar) or die(mysql_error());
}
*/
require_once("menu_ventas.php");
?>
<center>
<form method="post" name="formulario" action="descargar_reporte_ventas.php">
<table align="center">
<td nowrap align="right">REPORTE:</td>
<td bordercolor="0">
<select name="fecha">
<?php $sql_consulta1="SELECT DISTINCT fecha_anyo FROM ventas ORDER BY fecha DESC";
$resultado = mysql_query($sql_consulta1, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option value="<?php echo $contenedor['fecha_anyo'] ?>"><?php echo $contenedor['fecha_anyo'] ?></option>
<?php }?>
</select></td></td>
<input type="hidden" name="campo" value="fecha_anyo" size="1">
<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td bordercolor="1"><input type="submit" id="boton1" value="DESCARGAR"></td>
</tr>
</table>
</form>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
</head>
