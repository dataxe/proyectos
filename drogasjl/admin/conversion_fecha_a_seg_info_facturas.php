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
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");

$mostrar_datos_sql = "SELECT * FROM info_impuesto_facturas";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);
$total_datos = mysql_num_rows($consulta);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>ALMACEN</title>
</head>
<body>
<center>
<table width="100%">
<tr> 	 	 	 	 	 	 	 	 	 	
<td align="center"><strong>cod_info_impuesto_facturas</strong></td>
<td align="center"><strong>descuento</strong></td>
<td align="center"><strong>iva</strong></td>
<td align="center"><strong>flete</strong></td>
<td align="center"><strong>cod_factura</strong></td>
<td align="center"><strong>cod_clientes</strong></td>
<td align="center"><strong>vlr_cancelado</strong></td>
<td align="center"><strong>vlr_vuelto</strong></td>
<td align="center"><strong>vendedor</strong></td>
<td align="center"><strong>estado</strong></td>
<td align="center"><strong>fecha_dia</strong></td>
<td align="center"><strong>fecha_mes</strong></td>
<td align="center"><strong>fecha_anyo</strong></td>
<td align="center"><strong>anyo</strong></td>
<td align="center"><strong>fecha_hora</strong></td>
</tr>
<?php do { ?>
<tr>
<td align="left"><?php echo $datos['cod_info_impuesto_facturas']; ?></td>
<td align="left"><?php echo $datos['descuento']; ?></td>
<td align="left"><?php echo $datos['iva']; ?></td>
<td align="left"><?php echo $datos['flete']; ?></td>
<td align="left"><?php echo $datos['cod_factura'];?></td>
<td align="left"><?php echo $datos['cod_clientes']; ?></td>
<td align="left"><?php echo $datos['vlr_cancelado']; ?></td>
<td align="left"><?php echo $datos['vlr_vuelto']; ?></td>
<td align="left"><?php echo $datos['vendedor']; ?></td>
<td align="left"><?php echo $datos['estado']; ?></td>
<td align="left"><?php echo strtotime($datos['fecha_dia']); ?></td>
<td align="left"><?php echo $datos['fecha_mes']; ?></td>
<td align="left"><?php echo $datos['fecha_anyo']; ?></td>
<td align="left"><?php echo $datos['anyo']; ?></td>
<td align="left"><?php echo $datos['fecha_hora']; ?></td>
</tr>
<?php } while ($datos = mysql_fetch_assoc($consulta)); ?>
</table>
