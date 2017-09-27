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

$mostrar_datos_sql = "SELECT * FROM administrador LEFT JOIN base_caja on base_caja.cod_base_caja = administrador.cod_base_caja ORDER BY administrador.cod_base_caja DESC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<center>
<td><strong><font color='yellow' size='+3'>BASE DE CAJA: </font></strong></td><br><br>
<td ><a href="../admin/registros_cierre_caja.php"><strong><font color='yellow' size='+2'>REGISTROS CIERRE DE CAJA</font></strong></a></td><br><br>
<td ><a href="../admin/crear_caja.php"><strong><font color='yellow' size='+2'>CREAR CAJA</font></strong></a></td><br><br>

<table id="table" width="95%">
<tr>
<td><div align="center"><strong>EDIT</strong></div></td>
<!--<td><div align="center"><strong>CODIGO</strong></div></td>-->
<td><div align="center"><strong>VENDEDOR DE LA CAJA</strong></div></td>
<td><div align="center"><strong>NOMBRE DE LA CAJA</strong></div></td>
<td><div align="center"><strong>BASE DE LA CAJA</strong></div></td>
<td><div align="center"><strong>TOTAL DE LA VENTA</strong></div></td>
<td><div align="center"><strong>TOTAL DE LA CAJA</strong></div></td>
<td><div align="center"><strong>ASIG CAJA</strong></div></td>
<td><div align="center"><strong>EDIT CIERRE</strong></div></td>
<td><div align="center"><strong>HORA CIERRE</strong></div></td>
<td><div align="center"><strong>SACAR</strong></div></td>
</tr>
<?php do { ?>
<tr>
<td><a href="../modificar_eliminar/modificar_base_caja.php?cod_base_caja=<?php echo $datos['cod_base_caja']; ?>"><center><img src=../imagenes/actualizar.png alt="Actualizar"></center></a></td>
<!--<td><div align="center"><font size= "+2"><?php //echo $datos['cod_base_caja']; ?></font></td>-->
<td align="left"><font size= "+2"><?php echo $datos['cuenta']; ?></font></td>
<td align="center"><font size= "+2"><?php echo $datos['nombre_base_caja']; ?></font></td>
<td align="right"><font size= "+2"><?php echo number_format($datos['valor_caja'], 0, ",", "."); ?></font></td>
<td align="right"><font size= "+2"><?php echo number_format($datos['total_ventas'], 0, ",", "."); ?></font></td>
<td align="right"><font size= "+2"><?php echo number_format($datos['total_caja'], 0, ",", "."); ?></font></td>
<td><a href="../admin/asignar_vendedor_caja.php?cuenta=<?php echo $datos['cuenta']; ?>"><center><img src=../imagenes/caja_vend.png alt="Vendedor cambiar"></center></a></td>
<td><a href="../admin/hora_cierre_caja.php?cod_base_caja=<?php echo $datos['cod_base_caja']; ?>"><center><img src=../imagenes/hora.png alt="Hora cambiar"></center></a></td>
<td align="right"><font size= "+2"><?php echo $datos['hora']; ?></font></td>
<td><a href="../modificar_eliminar/sacar_base_caja.php?cod_base_caja=<?php echo $datos['cod_base_caja']; ?>"><center><img src=../imagenes/base_caja.png alt="base caja"></center></a></td>
</tr>
<?php } while ($datos = mysql_fetch_assoc($consulta)); ?>
</table>