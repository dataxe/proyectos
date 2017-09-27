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

$nivel_acceso = '3';
if ($seguridad_acceso['cod_seguridad'] <> $nivel_acceso) {
header("Location:../admin/acceso_denegado.php");
}
include ("../registro_movimientos/registro_movimientos.php");
/*
$verificar_pag = "SELECT * FROM pagina_actual WHERE cod_pagina_actual='1'";
$resultado_verificar_pag = mysql_query($verificar_pag, $conectar) or die(mysql_error());

$nombre_pagina_actual = 'AGOTADOS';
if ($resultado_verificar_pag['nombre_pagina_actual'] <> $nombre_pagina_actual) {
$actualizar_nombre_pagina_actual = sprintf("UPDATE pagina_actual SET nombre_pagina_actual=%s WHERE cod_pagina_actual='1'",
envio_valores_tipo_sql($nombre_pagina_actual, "text"), envio_valores_tipo_sql($_POST['cod_pagina_actual'], "text"));
$resultado_actualizacion_pagina_actual = mysql_query($actualizar_nombre_pagina_actual, $conectar) or die(mysql_error());
}
*/
$buscar = $_POST['palabra'];
$mostrar_datos_sql = "SELECT * FROM productos, marcas, proveedores, nomenclatura WHERE (nombre_productos like '%$buscar%' 
  OR cod_productos_var like '$buscar%') AND productos.cod_marcas = marcas.cod_marcas AND productos.cod_proveedores = proveedores.cod_proveedores 
AND productos.cod_nomenclatura = nomenclatura.cod_nomenclatura AND unidades_faltantes <= '0' order by nombre_productos";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);
$total_datos = mysql_num_rows($consulta);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="jquery.jeditable.js"></script>
<script type="text/javascript" src="js.js"></script>
</head>
<?php if ($total_datos <> 0) {
require_once("../busquedas/busqueda_productos_agotados.php");?>
<body>
<br>
<center>
<table >
<tr>
<td><div align="center"><strong>Cod</strong></div></td>
<td><div align="center"><strong>Nombre</strong></div></td>
<!--<td><div align="center"><strong>Marca</strong></div></td>-->
<td><div align="center"><strong>Prove</strong></div></td>
<!--<td><div align="center"><strong>Casilla</strong></div></td>
<td><div align="center"><strong>Unidades</strong></div></td>-->
<td><div align="center"><strong>Inv</strong></div></td>
<td><div align="center"><strong>P.Compra</strong></div></td>
<td><div align="center"><strong>P.Venta</strong></div></td>
<!--<td><div align="center"><strong>Margen Utilidad</strong></div></td>
<td><div align="center"><strong>Detalles</strong></div></td>-->
<td><div align="center"><strong>Descripcion</strong></div></td>
<td colspan="1"><div align="center" ><strong>Actualizar</strong></div></td>
</tr>
<?php do { 
$id = $datos['cod_productos_var'];
?>
<tr>
<td><div class="text" id="cod_productos_var-<?php echo $id?>"><?php echo $datos['cod_productos_var'];?></div></td>
<td><div class="textarea" id="nombre_productos-<?php echo $id?>"><?php echo $datos['nombre_productos'];?></div></td>
<!--<td><?php //echo $datos['nombre_marcas']; ?></td>-->
<td><div class="select" id="cod_proveedores-<?php echo $id?>"><?php echo $datos['nombre_proveedores'];?></td>
<!--<td><?php //echo $datos['nombre_nomenclatura']; ?></td>
<td align="right"><?php //echo $datos['unidades']; ?></td>-->
<td align="right"><div class="text" id="unidades_faltantes-<?php echo $id?>"><?php echo $datos['unidades_faltantes'];?></div></td>
<td align="right"><div class="text" id="precio_compra-<?php echo $id?>"><?php echo $datos['precio_compra'];?></div></td>
<td align="right"><div class="text" id="precio_venta-<?php echo $id?>"><?php echo $datos['precio_venta'];?></div></td>
<!--<td align="right"><?php //echo number_format($datos['utilidad']); ?></td>-->
<!--<td><?php //echo $datos['detalles']; ?></td>-->
<td align="right"><div class="textarea" id="descripcion-<?php echo $id?>"><?php echo $datos['descripcion'];?></td>
<td><a href="../modificar_eliminar/modificar_productos_agotados.php?cod_productos=<?php echo $datos['cod_productos_var']; ?>"><center><img src=../imagenes/actualizar.png alt="Actualizar"></a></td>
</tr>
<?php } while ($datos = mysql_fetch_assoc($consulta)); ?>
</table>
<?php } else {
echo "<center><font color='yellow'>No existen productos agotados</font></center>";
} ?>
</body>
</html>
<?php mysql_free_result($consulta);?>