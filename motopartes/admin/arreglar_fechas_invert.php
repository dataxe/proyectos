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

$nivel_acceso = '3';
if ($seguridad_acceso['cod_seguridad'] <> $nivel_acceso) {
header("Location:../admin/acceso_denegado.php");
}
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");
?>
<center>
<td><strong><a href="arreglar_fechas_ventas.php"><font color='white'>Ventas mes</font></a></strong></td>&nbsp;&nbsp;&nbsp;&nbsp;
<form method="post" id="table" name="formulario" action="arreglo_fecha_invert.php">
<table align="center">
<td nowrap align="right">Ventas dia invert:</td>
<td bordercolor="0">
<select name="fecha">
<?php $sql_consulta1="SELECT DISTINCT fecha, fecha_anyo FROM ventas ORDER BY fecha DESC";
$resultado = mysql_query($sql_consulta1, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option value="<?php echo $contenedor['fecha'] ?>"><?php echo $contenedor['fecha'].' - '.$contenedor['fecha_anyo'] ?></option>
<?php }?>
</select></td>
<td><input type="text" name="fecha_invert" style="height:26" required placeholder="anyo/mes/dia" required autofocus/></td>
<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td bordercolor="1"><input type="submit" id="boton1" value="Aplicar cambios"></td>
</tr>
</table>
</form>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
</head>
