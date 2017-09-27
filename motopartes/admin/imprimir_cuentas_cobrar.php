<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../index.php");
}
date_default_timezone_set("America/Bogota");
//$cuenta_actual = addslashes($_SESSION['usuario']);
$obtener_diseno = "SELECT * FROM disenos WHERE nombre_disenos LIKE 'por_defecto.css'";
$resultado_diseno = mysql_query($obtener_diseno, $conectar) or die(mysql_error());
$matriz_diseno = mysql_fetch_assoc($resultado_diseno); ?>
<link rel="stylesheet" type="text/css" href="../estilo_css/por_defecto.css">

<style type="text/css"><!--body { background-color: #333333;}--></style>
<?php
$obtener_informacion = "SELECT * FROM informacion_almacen WHERE cod_informacion_almacen = '1'";
$consultar_informacion = mysql_query($obtener_informacion, $conectar) or die(mysql_error());
$matriz_informacion = mysql_fetch_assoc($consultar_informacion);
$num_informacion = mysql_num_rows($consultar_informacion); 

$cod_clientes = $_POST['cod_clientes'];

$datos_factura = "SELECT * FROM cuentas_cobrar WHERE cod_clientes = '$cod_clientes'";
$consulta = mysql_query($datos_factura, $conectar) or die(mysql_error());
$matriz_consulta = mysql_fetch_assoc($consulta);

$total_factura = "SELECT Sum(vlr_total_venta) as suma_vlr_total_venta FROM cuentas_cobrar WHERE cod_clientes = '$cod_clientes'";
$total_consulta = mysql_query($total_factura, $conectar) or die(mysql_error());
$total = mysql_fetch_assoc($total_consulta);
?>
<center>
<table id="table" width="290" align="left">
<td><div align="center"><p style="font-size:8px"><?php echo $matriz_informacion['cabecera'];?> - <?php echo $matriz_informacion['localidad'];?>
<p style="font-size:8px">Direccion: <?php echo $matriz_informacion['direccion'];?> - Tel: <?php echo $matriz_informacion['telefono'];?>
<br><?php echo $matriz_informacion['nit'];?></div></td>
</table>
<br><br>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<link href="../imagenes/<?php echo $matriz_informacion['icono'];?>" type="image/x-icon" rel="shortcut icon" />
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title><?php echo "Factura No ".$cod_clientes;?></title>
</head>
<body>
<br>
<center>
<table id="table" width="290" align="left">
<tr>
<td><strong><div align="left"><p style="font-size:8px">C&oacute;digo</div></strong></td>
<td><strong><div align="left"><p style="font-size:8px">Descripci&oacute;n</div></strong></td>
<td><strong><div align="left"><p style="font-size:8px">Cant</div></strong></td>
<td><strong><div align="left"><p style="font-size:8px">V.unit</div></strong></td>
<td><strong><div align="left"><p style="font-size:8px">V.total</div></strong></td>
</tr>
<?php do { ?>
<tr>
<td align="left"><p style="font-size:8px"><?php echo $matriz_consulta['cod_productos']; ?></td>
<td align="left"><p style="font-size:8px"><?php echo $matriz_consulta['nombre_productos']; ?></td>
<td align="center"><p style="font-size:8px"><?php echo $matriz_consulta['unidades_vendidas']; ?></td>
<td align="right"><p style="font-size:8px"><?php echo number_format($matriz_consulta['precio_venta']); ?></td>
<td align="right"><p style="font-size:8px"><?php echo number_format($matriz_consulta['vlr_total_venta']); ?></td>
</tr>
<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta));?>
<td><p style="font-size:8px">Total: <?php echo number_format($total['suma_vlr_total_venta']);?></td>
<td><input align="left" type="image" id ="foco" src="../imagenes/imprimir.png" name="imprimir" onClick="window.print();"/></td>
</table>
<script>
window.onload = function() {
document.getElementById("foco").focus();
}
</script>