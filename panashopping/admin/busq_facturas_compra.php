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
<td><strong><font color='white'>LISTADO FACTURAS DE COMPRA: </font></strong></td><br><br>
<form method="post" name="formulario" action="buscar_facturas_compra.php">
<table align="center" id="table">
<td nowrap align="right">Cod Factura - Fecha Compra:</td>
<td bordercolor="0">
<select name="cod_factura" id="foco">
<?php $sql_consulta1="SELECT DISTINCT cod_factura, fecha_pago FROM cuentas_facturas WHERE cod_factura <> '0' ORDER BY fecha_invert DESC";
$resultado = mysql_query($sql_consulta1, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option value="<?php echo $contenedor['cod_factura'] ?>"><?php echo $contenedor['fecha_pago'].' - '.$contenedor['cod_factura']?></option>
<?php }?>
</select></td>
<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td bordercolor="1"><input type="submit" id="boton1" value="Consultar Factura"></td>
</tr>
</table>
</form>
</center>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
</head>
<script>
window.onload = function() {
document.getElementById("foco").focus();
}
</script>