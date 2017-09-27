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

$mostrar_datos_sql = "SELECT * FROM camparacion_tablas GROUP BY cod_factura ORDER BY fecha_time DESC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$tota_datos = mysql_num_rows($consulta);
$datos = mysql_fetch_assoc($consulta);

$obtener_cod_seguridad = "SELECT cod_seguridad FROM administrador WHERE cuenta = '$cuenta_actual'";
$resultado_cod_seguridad = mysql_query($obtener_cod_seguridad, $conectar) or die(mysql_error());
$result_cod_seguridad = mysql_fetch_assoc($resultado_cod_seguridad);

$cod_seguriten = $result_cod_seguridad['cod_seguridad'];

$pagina = 'exportacion_lista_subida.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<center>
<a href="importar_comparacion_tablas.php"><font color='yellow'>REGRESAR</font></a><br><br>
<td><strong><font color='yellow'>LISTA RESULTADOS AUDITORIA: </font></strong><br><a href="../admin/ver_productos_no_auditoria_todos.php?pagina=<?php echo $pagina; ?>"><img src=../imagenes/ver3.png alt="Ver"></a></td><br>
<?php
if ($tota_datos <> 0) {
?>
<table width="80%">
<tr>
<?php
if ($cod_seguriten == 3) { ?>
<td align="center"><strong>ELM</strong></td>
<?php
} else { } ?>
<td align="center"><strong>COD AUDIT</strong></td>
<td align="center"><strong>CUENTA</strong></td>
<td align="center"><strong>RESULT AUDIT</strong></td>
<td align="center"><strong>REG AUDIT</strong></td>
<?php
if ($cod_seguriten == 3) { ?>
<td align="center"><strong>REG NO AUDIT</strong></td>
<?php
} else { } ?>
<td align="center"><strong>FECHA</strong></td>
<td align="center"><strong>HORA</strong></td>
</tr>
<?php do { 
$cod_factura = $datos['cod_factura'];
?>
<tr>
<?php
if ($cod_seguriten == 3) { ?>
<td ><a href="../modificar_eliminar/eliminar_lista_importacion_vendedor.php?cod_factura=<?php echo $cod_factura; ?>&pagina=<?php echo $pagina; ?>"><center><img src=../imagenes/eliminar.png alt="eliminar"></a></td>
<?php
} else { } ?>
<td align="center"><font size='5px'><?php echo $cod_factura; ?></font></td>
<td><font size='5px'><?php echo $datos['vendedor']; ?></font></td>
<td><a href="../admin/resultado_comparacion_tablas.php?cod_factura=<?php echo $cod_factura; ?>"><center><img src=../imagenes/cruze.gif alt="Cruze"></a></td>
<td><a href="../admin/ver_factura_importacion.php?cod_factura=<?php echo $cod_factura; ?>"><center><img src=../imagenes/ver.png alt="Ver"></a></td>
<?php
if ($cod_seguriten == 3) { ?>
<td><a href="../admin/ver_productos_no_auditoria.php?cod_factura=<?php echo $cod_factura; ?>"><center><img src=../imagenes/ver3.png alt="Ver"></a></td>
<?php
} else { } ?>
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