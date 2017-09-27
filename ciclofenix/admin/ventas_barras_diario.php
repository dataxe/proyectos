<?php error_reporting(E_ALL ^ E_NOTICE);
require_once('../conexiones/conexione.php'); 
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
?>
<center>
<td><strong><font color='white'>RAYAS: </font></strong></td><br><br>
<table>
<td><a href="../admin/ventas_barras_mensual.php"><font size='2' color='red'>MENSUAL</font></a></td>
<td><br></td>
<td><a href="../admin/cambiar_porcentaje_rayas.php"><font size='2' color='red'>(CAMBIAR PORCENTAJE)</font></a></td>
<td><br></td>
<td><a href="../admin/ver_todas_rayas.php"><font size='2' color='red'>(VER TODOAS LAS RAYAS)</font></a></td>
</table>
<br>
<form method="post" name="formulario" action="resultado_ventas_barras_diario.php">
<table align="center">
<td nowrap align="right">VENTAS DIA - VENDEDOR:</td>
<td bordercolor="0">
<select name="fecha">
<?php $sql_consulta1 = "SELECT fecha, fecha_anyo, vendedor FROM ventas GROUP BY fecha ORDER BY fecha DESC";
$resultado = mysql_query($sql_consulta1, $conectar) or die(mysql_error());
while ($contenedor = mysql_fetch_array($resultado)) {?>
<option value="<?php echo $contenedor['fecha'].'-'.$contenedor['vendedor'] ?>"><?php echo $contenedor['fecha_anyo'].' - '.$contenedor['vendedor'] ?></option>
<?php }?>
</select></td>
</tr>
</table>
<br>
<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td bordercolor="1"><input type="submit" id="boton1" value="Consultar Ventas"></td>
</form>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
</head>
