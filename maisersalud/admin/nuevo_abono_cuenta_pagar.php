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

$formulario_agregar = $_SERVER['PHP_SELF'];

$cod_factura = $_GET['cod_factura'];
$nombre_proveedores = $_GET['nombre_proveedores'];

$sql = "SELECT * FROM proveedores WHERE nombre_proveedores  = '$nombre_proveedores '";
$consulta = mysql_query($sql, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);

$cod_proveedores = $datos['cod_proveedores'];

if ((isset($_POST["insertar_datos"])) && ($_POST["insertar_datos"] == "formulario")) {

$abonado = $_POST['abonado'];
$mensaje = $_POST['mensaje'];
$cod_factura = $_POST['cod_factura'];
$cod_proveedores = $_POST['cod_proveedores'];
$nombre_proveedores = $_POST['nombre_proveedores'];
$fecha = $_POST['fecha'];
$fecha_pago = $fecha;
$fecha_anyo = $fecha;
$hora = date("H:i:s");

$separador_fecha =explode('/', $fecha);
$dias = $separador_fecha[0];
$meses = $separador_fecha[1];
$anyos = $separador_fecha[2];
$fecha_mes = $meses.'/'.$anyos;
$fecha_invert = $anyos.'/'.$meses.'/'.$dias;
$fecha_seg = strtotime($fecha_invert);
$fecha_mes = $meses.'/'.$anyos;
$anyo = $anyos;

$agregar_reg_cuentas_pagar = "INSERT INTO cuentas_pagar_abonos (cod_factura, cod_proveedores, abonado, mensaje, cuenta, fecha_pago, fecha_anyo, fecha_mes, anyo, 
fecha_invert, fecha_seg, hora)
VALUES ('$cod_factura', '$cod_proveedores', '$abonado', '$mensaje', '$cuenta_actual', '$fecha_pago', '$fecha_anyo', '$fecha_mes', '$anyo', '$fecha_invert', 
'$fecha_seg', '$hora')";
$resultado_cuentas_pagar = mysql_query($agregar_reg_cuentas_pagar, $conectar) or die(mysql_error());

$sql_sum_abonos = "SELECT Sum(abonado) As abonado FROM cuentas_pagar_abonos WHERE cod_factura = '$cod_factura'";
$consulta_sum_abonos  = mysql_query($sql_sum_abonos, $conectar) or die(mysql_error());
$sum_abonos = mysql_fetch_assoc($consulta_sum_abonos);

$sql_cuentas_pagar = "SELECT Sum(monto_deuda) As monto_deuda FROM cuentas_pagar WHERE cod_factura = '$cod_factura'";
$consulta_cuentas_pagar  = mysql_query($sql_cuentas_pagar, $conectar) or die(mysql_error());
$sum_cuentas_pagar = mysql_fetch_assoc($consulta_cuentas_pagar);

$monto_deuda = $sum_cuentas_pagar['monto_deuda'];
$abonado_sum = $sum_abonos['abonado'];
$deuda = $monto_deuda - $abonado_sum;
mysql_query("UPDATE cuentas_pagar SET abonado = '$abonado_sum' WHERE cod_factura = '$cod_factura'");

//------------------------------------------ PARA ELIMINAR ALERTAS Q HALLAN ACTUALIZADO LOS PRODUCTOS ------------------------------------------//
if ($deuda <= '0') {
$borrar_alerta  = sprintf("DELETE FROM notificacion_alerta WHERE cod_factura = '$cod_factura'");
$Resultado1 = mysql_query($borrar_alerta , $conectar) or die(mysql_error());
}
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0.1; ../admin/cuentas_pagar_abonos.php?cod_factura=<?php echo $cod_factura?>&nombre_proveedores=<?php echo $nombre_proveedores?>">
<?php
}
?>
<center>
<br><br>
<td>
<a href="../admin/cuentas_pagar_abonos.php?cod_factura=<?php echo $cod_factura?>&nombre_proveedores=<?php echo $nombre_proveedores?>"><FONT color='yellow' size="5px">REGRESAR</FONT></a>
</td>
</center>
<center>
<br>
<td><strong><font color='yellow' size="6px">PROVEEDOR: <?php echo $nombre_proveedores; ?> - FACTURA: <?php echo $cod_factura; ?></font></strong></td><br><br>
<form method="post" name="formulario" action="<?php echo $formulario_agregar; ?>">
<table width="90%">
<tr>
<td align="center"><strong>VALOR ABONO</strong></td>
<td align="center"><strong>COMENTARIO</strong></td>
<td align="center"><strong>FECHA</strong></td>
<tr>
<tr>
<td align="center"><input style="font-size:24px" type="text" name="abonado" value="" size="10"  required autofocus></td>
<td align="center"><input style="font-size:24px" type="text" name="mensaje" value="" size="50"></td>
<td align="center"><input style="font-size:24px" type="text" name="fecha" value="<?php echo date("d/m/Y");?>" size="10" required autofocus></td>
<input style="font-size:24px" type="hidden" name="cod_factura" value="<?php echo $cod_factura?>" size="10">
<input style="font-size:24px" type="hidden" name="cod_proveedores" value="<?php echo $cod_proveedores?>" size="10">
<input style="font-size:24px" type="hidden" name="nombre_proveedores" value="<?php echo $nombre_proveedores?>" size="10">
</tr>
</table>
<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td bordercolor="1"><input type="submit" id="boton1" value="Agregar"></td>
<input type="hidden" name="insertar_datos" value="formulario">
</form>
</center>