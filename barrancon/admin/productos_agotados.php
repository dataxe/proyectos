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

$buscar = $_POST['palabra'];
$mostrar_datos_sql = "SELECT * FROM productos, marcas WHERE (nombre_productos like '%$buscar%' OR cod_productos_var like '$buscar%') 
AND productos.cod_marcas = marcas.cod_marcas AND unidades_faltantes <= '0' order by unidades_faltantes ASC";
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
<?php if ($total_datos <> 0) {
//require_once("../busquedas/busqueda_productos_agotados.php");?>
<body>
<center>
<td><strong><font color='white'>PRODUCTOS AGOTADOS: </font></strong></td><br><br>
<table width="80%">
<tr>
<!--<td align="center"><strong>EDIT</strong></td>-->
<td align="center"><strong>C&Oacute;DIGO</strong></td>
<td align="center"><strong>PRODUCTO</strong></td>
<td align="center"><strong>MARCA</strong></td>
<!--<td><strong>Prove</strong></td>
<td><strong>Casilla</strong></td>
<td><strong>Unidades</strong></td>-->
<td align="center"><strong>UND</strong></td>
<td align="center"><strong>MET</strong></td>
<!--<td align="center"><strong>P.COMPRA</strong></td>-->
<td align="center"><strong>P.VENTA</strong></td>
<!--<td><strong>Margen Utilidad</strong></td>
<td><strong>Detalles</strong></td>
<td><strong>DESCRIPCI&Oacute;N</strong></td>-->
</tr>
<?php do { ?>
<tr>
<!--<td><a href="../modificar_eliminar/modificar_productos_agotados.php?cod_productos=<?php echo $datos['cod_productos_var']; ?>"><center><img src=../imagenes/actualizar.png alt="Actualizar"></center></a></td>-->
<td><?php echo $datos['cod_productos_var'];?></td>
<td><?php echo $datos['nombre_productos'];?></td>
<td><?php echo $datos['nombre_marcas']; ?></td>
<!--<td><div class="select" id="cod_proveedores-<?php echo $id?>"><?php echo $datos['nombre_proveedores'];?></td>
<td><?php //echo $datos['nombre_nomenclatura']; ?></td>
<td align="right"><?php //echo $datos['unidades']; ?></td>-->
<td align="right"><?php echo $datos['unidades_faltantes'];?></td>
<td align="left"><?php echo $datos['detalles']; ?></td>
<!--<td align="right"><div class="text" id="precio_compra-<?php echo $id?>"><?php echo $datos['precio_compra'];?></td>-->
<td align="right"><?php echo $datos['precio_venta'];?></td>
<!--<td align="right"><?php //echo number_format($datos['utilidad']); ?></td>-->
<!--<td><?php //echo $datos['detalles']; ?></td>-->
<!--<td align="right"><div class="textarea" id="descripcion-<?php echo $id?>"><?php echo $datos['descripcion'];?></td>-->
</tr>
<?php } while ($datos = mysql_fetch_assoc($consulta)); ?>
</table>
<?php } else {
echo "<center><font color='yellow'>No existen productos agotados</font></center>";
} ?>
</body>
</html>
<?php mysql_free_result($consulta);?>