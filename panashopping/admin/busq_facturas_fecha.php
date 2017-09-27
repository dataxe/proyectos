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
?>
<center>
<br>
<td><strong><font color='white'>BUSCAR POR NOMBRE, CODIGO DE PRODUCTO, CLIENTE, FACTURA O FECHA: </font></strong></td><br>
<br>
<form method="post" name="formulario" action="buscar_facturas_fecha.php">
<table align="center" id="table">
<input name="buscar" required autofocus>
<input type="submit" name="buscador" value="BUSCAR INFORMACION" />
<!--
<td nowrap align="right">Factura Fecha Venta:</td>
<td bordercolor="0">
<select name="cod_factura"  id="foco">
<?php //$sql_consulta1="SELECT DISTINCT cod_factura, fecha, fecha_anyo FROM ventas ORDER BY fecha DESC";
//$resultado = mysql_query($sql_consulta1, $conectar) or die(mysql_error());
//while ($contenedor=mysql_fetch_array($resultado)) {?>
<option value="<?php //echo $contenedor['cod_factura'] ?>"><?php //echo $contenedor['fecha_anyo'].' - '.$contenedor['cod_factura'] ?></option>
<?php //}?>
</select></td>

<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td bordercolor="1"><input type="submit" id="boton1" value="Consultar Factura"></td>
</tr>
-->
</table>
</form>
</center>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
</head>