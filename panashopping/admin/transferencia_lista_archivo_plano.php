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

$nivel_acceso = '3';
if ($seguridad_acceso['cod_seguridad'] <> $nivel_acceso) {
header("Location:../admin/acceso_denegado.php");
}
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");

$sql = "SELECT * FROM info_transferencias_temporal ORDER BY fecha_dia DESC";
$consulta = mysql_query($sql, $conectar) or die(mysql_error());
$total_datos = mysql_num_rows($consulta);

$pagina = $_SERVER['PHP_SELF'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
</head>
<center>
<br>
<td><strong><a href="transferencias.php"><font color='yellow'>REGRESAR</font></a></strong></td>&nbsp;&nbsp;&nbsp;&nbsp;<br><br>
<td><strong><font color='yellow'>LISTA DE TRANSFERENCIAS</font></strong></td><br><br>
<table width="70%">
<tr>
<td align="center"><strong>ALMACEN</strong></td>
<td align="center"><strong>VER</strong></td>
<td align="center"><strong>CODIGO</strong></td>
<td align="center"><strong>FECHA</strong></td>
<td align="center"><strong>HORA</strong></td>
<td align="center"><strong>CUENTA</strong></td>
<td align="center"><strong>ELIM</strong></td>
</tr>
<?php
while ($datos = mysql_fetch_assoc($consulta)) {
$nombre_almacen = $datos['nombre_almacen'];
$fecha_anyo = $datos['fecha_anyo'];
$fecha_hora = $datos['fecha_hora'];
$vendedor = $datos['vendedor'];
$cod_factura = $datos['cod_factura'];
?>
<tr>
<td><a href="../admin/cargar_transferencia_temporal_archivo_plano_editable_vendedor.php?cod_factura=<?php echo $cod_factura?>"><?php echo $nombre_almacen?></a></td>
<td><a href="../admin/cargar_transferencia_temporal_archivo_plano_editable_vendedor.php?cod_factura=<?php echo $cod_factura?>"><center><img src=../imagenes/ver.png alt="ver"></center></a></td>
<td align="center"><?php echo $cod_factura;?></td>
<td align="center"><?php echo $fecha_anyo;?></td>
<td align="center"><?php echo $fecha_hora;?></td>
<td align="center"><?php echo $vendedor;?></td>
<td><a href="../modificar_eliminar/eliminar.php?cod_factura=<?php echo $cod_factura?>&tipo=eliminar&tab=info_y_trans_temp&pagina=<?php echo $pagina?>"><center><img src=../imagenes/eliminar.png alt="eliminar"></center></a></td>
</tr>
</center>
<?php } 
?>
