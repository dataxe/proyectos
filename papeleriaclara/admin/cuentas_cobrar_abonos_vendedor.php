<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
$cuenta_actual = addslashes($_SESSION['usuario']);
include("../session/funciones_admin.php");
//include("../notificacion_alerta/mostrar_noficacion_alerta.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
  } else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
include ("../seguridad/seguridad_diseno_plantillas.php");
include ("../registro_movimientos/registro_movimientos.php");
/*
$nivel_acceso = '3';
if ($seguridad_acceso['cod_seguridad'] <> $nivel_acceso) {
header("Location:../admin/acceso_denegado.php");
}
*/
//include ("../registro_movimientos/registro_cierre_caja.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<?php
$cod_clientes = $_GET['cod_clientes'];
$cliente = $_GET['cliente'];
$pagina = $_SERVER['PHP_SELF'];

$sql = "SELECT * FROM cuentas_cobrar_abonos, clientes WHERE cuentas_cobrar_abonos.cod_clientes = '$cod_clientes' AND 
cuentas_cobrar_abonos.cod_clientes = clientes.cod_clientes ORDER BY fecha_invert DESC";
$consulta = mysql_query($sql, $conectar) or die(mysql_error());
$total_datos = mysql_num_rows($consulta);
?>
<center>
<td><strong><a href="../modificar_eliminar/modificar_cuentas_cobrar_vendedor.php?cod_clientes=<?php echo $cod_clientes;?>"><font color='white' size="5px">REGRESAR</font></a></strong></td><br><br>


<td><strong><font color='yellow' size="6px">ABONOS CLIENTE: <?php echo $cod_factura; ?> </font></strong></td><br><br>

<table width="100%">
<tr>
<!--<td align="center"><strong>CLIENTE</strong></td>-->
<td align="center"><strong>MONTO DEUDA</strong></td>
<td align="center"><strong>ABONOS</strong></td>
<td align="center"><strong>SUBTOTAL</strong></td>
<td align="center"><strong>VENDEDOR</strong></td>
<td align="center"><strong>PAGO A</strong></td>
<td align="center"><strong>MENSAJE</strong></td>
<td align="center"><strong>FECHA</strong></td>
<td align="center"><strong>HORA</strong></td>
</tr>
<?php
while ($datos = mysql_fetch_assoc($consulta)) {
?>
<tr>
<!--<td ><font size="4px"><?php echo $datos['nombres'].' '.$datos['apellidos']; ?></font></td>-->
<td align="right"><font size="4px"><?php echo $datos['monto_deuda']; ?></font></td>
<td align="right"><font size="4px"><?php echo $datos['abonado']; ?></font></td>
<td align="right"><font size="4px"><?php echo $datos['subtotal']; ?></font></td>
<td align="center"><font size="4px"><?php echo $datos['vendedor']; ?></font></td>
<td align="center"><font size="4px"><?php echo $datos['cuenta']; ?></font></td>
<td align="left"><font size="4px"><?php echo $datos['mensaje']; ?></font></td>
<td align="right"><font size="4px"><?php echo $datos['fecha_pago']; ?></font></td>
<td align="right"><font size="4px"><?php echo $datos['hora']; ?></font></td>
</tr>
<?php }
?>
</table>
<br>
<table>
</table>