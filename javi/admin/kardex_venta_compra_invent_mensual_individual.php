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
//----------------------------------------------------------------------------------------------------------------//
//----------------------------------------------------------------------------------------------------------------//
$datos_user = "SELECT cod_seguridad, cuenta FROM administrador WHERE cuenta = '$cuenta_actual'";
$consulta_user = mysql_query($datos_user, $conectar) or die(mysql_error());
$info_user = mysql_fetch_assoc($consulta_user);

$cod_seguridad = $info_user['cod_seguridad'];
//----------------------------------------------------------------------------------------------------------------//
//----------------------------------------------------------------------------------------------------------------//
$pagina = $_SERVER['PHP_SELF'];
?>
<br>
<center>
<td><strong><font color='yellow' size="+2">KARDEX: </font></strong></td><br><br>
<?php require_once("menu_kardex.php"); ?>
<form method="post" name="formulario" action="">
<table align="center">
<td align="center"><select name="fecha_mes">
<?php $sql_consulta="SELECT fecha_mes, fecha_seg_ymd FROM kardex_venta_compra_invent GROUP BY fecha_mes ORDER BY fecha_seg_ymd DESC";
$resultado = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option style="font-size:16px" value="<?php echo $contenedor['fecha_mes'] ?>"><?php echo $contenedor['fecha_mes'] ?></option>
<?php }?>
</select></td>
<td bordercolor="1"><input type="submit" id="boton1" value="VER"></td>
</table>
</form>
</center>
<?php 
if (isset($_POST['fecha_mes'])) {
$fecha_mes = addslashes($_POST['fecha_mes']);

//$mostrar_datos_sql = "SELECT * FROM devoluciones WHERE (doc = '$buscar' OR nombre_y_apellidos LIKE '%$buscar%') ORDER BY nombre_y_apellidos DESC";
$mostrar_datos_sql = "SELECT cod_productos, nombre_productos, und_venta, und_compra, und_transf, und_invent, fecha_mes, fecha_seg_ymd 
FROM kardex_venta_compra_invent WHERE fecha_mes = '$fecha_mes' ORDER BY fecha_seg_ymd DESC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$total_resultados = mysql_num_rows($consulta);
$datos = mysql_fetch_assoc($consulta);
?>
<center>
<table width="90%">
<tr>
<th align="center"><font size= "+1">CODIGO</font></th>
<th align="center"><font size= "+1">PRODUCTO</font></th>
<th align="center"><font size= "+1">COMPRA</font></th>
<th align="center"><font size= "+1">VENTA</font></th>
<th align="center"><font size= "+1">INVENT</font></th>
<th align="center"><font size= "+1">MES</font></th>
</tr>
<?php do { 
$cod_productos = $datos['cod_productos'];
$nombre_productos = $datos['nombre_productos'];
$und_venta = $datos['und_venta'];
$und_compra = $datos['und_compra'];
$und_transf = $datos['und_transf'];
$und_invent = $datos['und_invent'];
$fecha_mes = $datos['fecha_mes'];
?>
<td><font size= "+1"><?php echo $cod_productos; ?></font></td>
<td><font size= "+1"><?php echo $nombre_productos; ?></font></td>
<td align="right"><a href="../admin/kardex_ver_compra.php?cod_productos=<?php echo $cod_productos?>&fecha_mes=<?php echo $fecha_mes?>&pagina=<?php echo $pagina?>"><font size= "+1"><?php echo number_format($und_compra, 0, ",", "."); ?></font><img src=../imagenes/mas.png alt="mas"></a></td>
<td align="right"><a href="../admin/kardex_ver_venta.php?cod_productos=<?php echo $cod_productos?>&fecha_mes=<?php echo $fecha_mes?>&pagina=<?php echo $pagina?>"><font size= "+1"><?php echo number_format($und_venta, 0, ",", "."); ?></font><img src=../imagenes/mas.png alt="mas"></a></td>
<?php if ($cod_seguridad == 3 || $cod_seguridad == 2) { ?>
<td align="right"><a href="../modificar_eliminar/productos_actualizar_inventario.php?cod_productos=<?php echo $cod_productos?>"><font size= "+1"><?php echo $und_invent; ?></font><img src=../imagenes/mas.png alt="mas"></a></td>
<?php } else { ?>
<td align="right"><font size= "+1"><?php echo $und_invent; ?></td>
<?php
} ?>
<td align="center"><font size= "+1"><?php echo $fecha_mes; ?></font></td>
</tr>
<?php 
}
while ($datos = mysql_fetch_assoc($consulta)); 
} else {
}
?>
</table>
</table>
</form>
</center>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
</head>