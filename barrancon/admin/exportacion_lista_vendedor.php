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

$obtener_cod_seguridad = "SELECT cod_seguridad FROM administrador WHERE cuenta = '$cuenta_actual'";
$resultado_cod_seguridad = mysql_query($obtener_cod_seguridad, $conectar) or die(mysql_error());
$result_cod_seguridad = mysql_fetch_assoc($resultado_cod_seguridad);

$cod_seguriten = $result_cod_seguridad['cod_seguridad'];

if ($cod_seguriten == 3) {
$mostrar_datos_sql = "SELECT * FROM exportacion_vendedor GROUP BY cod_factura ORDER BY fecha DESC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$tota_datos = mysql_num_rows($consulta);
$datos = mysql_fetch_assoc($consulta);
} else {
$mostrar_datos_sql = "SELECT * FROM exportacion_vendedor GROUP BY cod_factura ORDER BY fecha DESC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$tota_datos = mysql_num_rows($consulta);
$datos = mysql_fetch_assoc($consulta);
}

$pagina = 'exportacion_lista_vendedor.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<center>
<a href="cargar_exportacion_temporal_vendedor.php"><font color='yellow'>REGRESAR</font></a><br><br>
<td><strong><font color='yellow'>LISTA EXPORTACION EXTERNA VENDEDOR: </font></strong></td><br><br>
<?php
if ($tota_datos <> 0) {
?>
<table width="80%">
<tr>
<td align="center"><strong>ELM</strong></td>
<td align="center"><strong>COD AUDIT</strong></td>
<td align="center"><strong>CUENTA</strong></td>
<!-- //////////////////////////////////////////////////////////////////////////////////////// -->
<?php if ($cod_seguriten == 3 || $cod_seguriten == 2) { ?>
<td align="center"><strong>RESULT AUDIT</strong></td>
<?php } else { } ?>
<!-- //////////////////////////////////////////////////////////////////////////////////////// -->
<td align="center"><strong>DESCARGAR</strong></td>
<td align="center"><strong>VER</strong></td>
<td align="center"><strong>FECHA</strong></td>
<td align="center"><strong>HORA</strong></td>
<?php do { ?>
<tr>
<td><a href="../modificar_eliminar/eliminar_lista_exportacion_vendedor.php?cod_factura=<?php echo $datos['cod_factura']; ?>&pagina=<?php echo $pagina; ?>"><center><img src=../imagenes/eliminar.png alt="eliminar"></a></td>
<td align="center"><font size='5px'><?php echo $datos['cod_factura']; ?></font></td>
<td align="center"><font size='5px'><?php echo $datos['vendedor']; ?></font></td>
<!-- //////////////////////////////////////////////////////////////////////////////////////// -->
<?php if ($cod_seguriten == 3 || $cod_seguriten == 2) { ?>
<td><a href="../admin/transportar_datos_resultado_auditoria.php?cod_factura=<?php echo $datos['cod_factura']; ?>"><center><img src=../imagenes/cruze.gif alt="cruze"></a></td>
<?php } else { } ?>
<!-- //////////////////////////////////////////////////////////////////////////////////////// -->
<td><a href="../admin/descargar_factura_externa_temporal_archivo_csv.php?cod_factura=<?php echo $datos['cod_factura']; ?>"><center><img src=../imagenes/descargar.png alt="Descargar"></a></td>
<td><a href="../admin/ver_factura_externa_temporal_vendedor.php?cod_factura=<?php echo $datos['cod_factura']; ?>"><center><img src=../imagenes/ver.png alt="Ver"></a></td>
<td align="center"><font size='5px'><?php echo $datos['fecha_anyo']; ?></font></td>
<td align="center"><font size='5px'><?php echo $datos['fecha_hora']; ?></font></td>
</tr>
<?php } while ($datos = mysql_fetch_assoc($consulta)); ?>
</table>
<?php
} else {
//echo "<br><center><font size='4px' color='yellow'>NO HAY NINGUNA FACTURA EXTERNA CARGADA<center>";
}
?>