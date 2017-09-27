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
$mostrar_datos_sql = "SELECT * FROM info_exportacion ORDER BY fecha_invert DESC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$tota_datos = mysql_num_rows($consulta);
$datos = mysql_fetch_assoc($consulta);

$pagina = 'exportacion_lista.php';
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
<td><strong><font color='yellow'>LISTA AUDITORIA EXPORTACION: </font></strong></td><br><br>
<?php
if ($tota_datos <> 0) {
?>
<table width="80%">
<tr>
<td align="center"><strong>ELM</strong></td>
<td align="center"><strong>FACTURA</strong></td>
<td align="center"><strong>CUENTA</strong></td>
<td align="center"><strong>DESCARGAR</strong></td>
<td align="center"><strong>VER REGISTROS</strong></td>
<td align="center"><strong>FECHA</strong></td>
<td align="center"><strong>HORA</strong></td>
<?php do { ?>
<tr>
<td ><a href="../modificar_eliminar/eliminar_lista_exportacion_vendedor.php?cod_factura=<?php echo $datos['cod_factura']; ?>&pagina=<?php echo $pagina; ?>"><center><img src=../imagenes/eliminar.png alt="eliminar"></a></td>
<td align="center"><font size='5px'><?php echo $datos['cod_factura']; ?></font></td>
<td><font size='5px'><?php echo $datos['vendedor']; ?></font></td>
<td ><a href="../admin/descargar_factura_externa_temporal_archivo_csv.php?cod_factura=<?php echo $datos['cod_factura']; ?>"><center><img src=../imagenes/descargar.png alt="Descargar"></a></td>
<td ><a href="../admin/ver_factura_externa_temporal.php?cod_factura=<?php echo $datos['cod_factura']; ?>"><center><img src=../imagenes/ver.png alt="Ver"></a></td>
<td align="center"><font size='5px'><?php echo $datos['fecha']; ?></font></td>
<td align="center"><font size='5px'><?php echo $datos['hora']; ?></font></td>
</tr>
<?php } while ($datos = mysql_fetch_assoc($consulta)); ?>
</table>
<?php
} else {
//echo "<br><center><font size='4px' color='yellow'>NO HAY NINGUNA FACTURA EXTERNA CARGADA<center>";
}
?>