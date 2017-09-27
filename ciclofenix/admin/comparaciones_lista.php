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

$formulario_agregar = $_SERVER['PHP_SELF'];
$buscar = $_POST['palabra'];
$mostrar_datos_sql = "SELECT * FROM info_camparacion_tablas ORDER BY fecha_invert DESC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$tota_datos = mysql_num_rows($consulta);
$datos = mysql_fetch_assoc($consulta);

$pagina = 'comparaciones_lista.php';
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
<td><strong><font color='yellow'>LISTA DE COMPARACIONES: </font></strong></td><br><br>
<?php
if ($tota_datos <> 0) {
?>
<table id="table" width="90%">
<tr>
<td><div align="center"><strong>ELM</strong></div></td>
<td><div align="center"><strong>FACTURA</strong></div></td>
<td><div align="center"><strong>CUENTA</strong></div></td>
<!--<td><div align="center"><strong>DESCARGAR</strong></div></td>-->
<td><div align="center"><strong>VER</strong></div></td>
<td><div align="center"><strong>FECHA</strong></div></td>
<td><div align="center"><strong>HORA</strong></div></td>
<?php do { ?>
<tr>
<td ><a href="../modificar_eliminar/eliminar_lista_comparacion.php?cod_factura=<?php echo $datos['cod_factura']; ?>&pagina=<?php echo $pagina; ?>"><center><img src=../imagenes/eliminar.png alt="eliminar"></a></td>
<td><?php echo $datos['cod_factura']; ?></td>
<td><?php echo $datos['vendedor']; ?></td>
<!--<td  nowrap><a href="../admin/descargar_factura_externa_temporal_archivo_csv.php?cod_factura=<?php echo $datos['cod_factura']; ?>"><center><img src=../imagenes/descargar.png alt="Descargar"></a></td>-->
<td ><a href="../admin/resultado_comparacion_tablas.php?cod_factura=<?php echo $datos['cod_factura']; ?>"><center><img src=../imagenes/ver.png alt="Ver"></a></td>
<td align="center"><?php echo $datos['fecha']; ?></td>
<td align="center"><?php echo $datos['hora']; ?></td>
</tr>
<?php } while ($datos = mysql_fetch_assoc($consulta)); ?>
</table>
<?php
} else {
//echo "<br><center><font size='4px' color='yellow'>NO HAY NINGUNA FACTURA EXTERNA CARGADA<center>";
}
?>