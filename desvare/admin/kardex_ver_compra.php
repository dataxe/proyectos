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

$cod_productos = ($_GET['cod_productos']);
$fecha_mes = addslashes($_GET['fecha_mes']);
$pagina = $_GET['pagina'].'?fecha_mes='.$fecha_mes;
//-------------------------------------------------------------------------------------------------------------------------------------//
//---------------------------------------------------------------ACTUALIZACION REGISTRO KARDEX-----------------------------------------------------------------------//
if (isset($_GET['cod_productos'])) {
//--------------------------------------------------------------CALCULO KARDEX VENTAS------------------------------------------------------------------------//
$sql_kardex_ventas = "SELECT SUM(unidades_vendidas) AS und_venta FROM ventas WHERE cod_productos = '$cod_productos' AND fecha_mes = '$fecha_mes'";
$consulta_kardex_ventas = mysql_query($sql_kardex_ventas, $conectar) or die(mysql_error());
$datos_kardex_ventas = mysql_fetch_assoc($consulta_kardex_ventas);
//--------------------------------------------------------------CALCULO KARDEX COMPRAS------------------------------------------------------------------------//
$sql_kardex_compras = "SELECT SUM(unidades_total) AS und_compra FROM facturas_cargadas_inv WHERE cod_productos = '$cod_productos' AND fecha_mes = '$fecha_mes'";
$consulta_kardex_compras = mysql_query($sql_kardex_compras, $conectar) or die(mysql_error());
$datos_kardex_compras = mysql_fetch_assoc($consulta_kardex_compras);
//--------------------------------------------------------------CALCULO KARDEX TRANSFERENCIAS------------------------------------------------------------------------//
$sql_kardex_transf = "SELECT SUM(unidades_total) AS und_transf FROM transferencias WHERE cod_productos = '$cod_productos' AND fecha_mes = '$fecha_mes'";
$consulta_kardex_transf = mysql_query($sql_kardex_transf, $conectar) or die(mysql_error());
$datos_kardex_transf = mysql_fetch_assoc($consulta_kardex_transf);
//--------------------------------------------------------------CALCULO KARDEX TRANSFERENCIAS ENTRDAS------------------------------------------------------------------------//
$sql_kardex_transf_ent = "SELECT SUM(unidades_total) AS und_transf_ent FROM transferencias_entrada WHERE cod_productos = '$cod_productos' AND fecha_mes = '$fecha_mes'";
$consulta_kardex_transf_ent = mysql_query($sql_kardex_transf_ent, $conectar) or die(mysql_error());
$datos_kardex_transf_ent = mysql_fetch_assoc($consulta_kardex_transf_ent);
//---------------------------------------------------------------CALCULO KARDEX INVENTARIO-----------------------------------------------------------------------//
/*
$sql_kardex_invent = "SELECT unidades_faltantes FROM productos WHERE cod_productos_var = '$cod_productos'";
$consulta_kardex_invent = mysql_query($sql_kardex_invent, $conectar) or die(mysql_error());
$datos_kardex_invent = mysql_fetch_assoc($consulta_kardex_invent);
*/
//---------------------------------------------------------------TOTALES CALCULOS KARDEX-----------------------------------------------------------------------//
$und_venta = $datos_kardex_ventas['und_venta'];
$und_compra = $datos_kardex_compras['und_compra'];
$und_transf = $datos_kardex_transf['und_transf'];
$und_transf_ent = $datos_kardex_transf_ent['und_transf_ent'];
//$und_invent = $datos_kardex_invent['unidades_faltantes'];
//---------------------------------------------------------------ACTUALIZACION REGISTRO KARDEX-----------------------------------------------------------------------//
$actualizar_kardex = sprintf("UPDATE kardex_venta_compra_invent SET und_venta = '$und_venta', und_compra = '$und_compra', und_transf = '$und_transf', 
und_transf_ent = '$und_transf_ent' WHERE cod_productos = '$cod_productos' AND fecha_mes = '$fecha_mes'");
$resultado_actualiza_kardex = mysql_query($actualizar_kardex, $conectar) or die(mysql_error());
}
//----------------------------------------------------------------------- -----------------------------------------------------------------------------------------------//
$mostrar_datos_sql = "SELECT facturas_cargadas_inv.cod_productos, facturas_cargadas_inv.nombre_productos, facturas_cargadas_inv.unidades_total, facturas_cargadas_inv.cod_factura, 
proveedores.nombre_proveedores, facturas_cargadas_inv.fecha_anyo, facturas_cargadas_inv.fecha_mes
FROM proveedores INNER JOIN facturas_cargadas_inv ON proveedores.cod_proveedores = facturas_cargadas_inv.cod_proveedores
WHERE (((facturas_cargadas_inv.cod_productos)='$cod_productos') AND ((facturas_cargadas_inv.fecha_mes)='$fecha_mes')) ORDER BY fecha DESC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$total_resultados = mysql_num_rows($consulta);
$datos = mysql_fetch_assoc($consulta);
?>
<br>
<center>
<a href="<?php echo $pagina?>"><font color='yellow' size= "+1">REGRESAR</font></a>
<br><br>
<td><strong><font color='yellow' size="+2">KARDEX COMPRAS: </font></strong></td><br><br>
<table width="90%">
<tr>
<th align="center"><font size="+1">CODIGO</font></th>
<th align="center"><font size="+1">PRODUCTO</font></th>
<th align="center"><font size="+1">UND</font></th>
<th align="center"><font size="+1">FACTURA</font></th>
<th align="center"><font size="+1">PROVEEDOR</font></th>
<th align="center"><font size="+1">FECHA</font></th>
</tr>
<?php do { 
$cod_productos = $datos['cod_productos'];
$nombre_productos = $datos['nombre_productos'];
$cod_factura = $datos['cod_factura'];
$unidades_total = $datos['unidades_total'];
$nombre_proveedores = $datos['nombre_proveedores'];
$fecha_anyo = $datos['fecha_anyo'];
?>
<td><font size= "+1"><?php echo $cod_productos; ?></font></td>
<td><font size= "+1"><?php echo $nombre_productos; ?></font></td>
<td align="center"><font size= "+1"><?php echo $unidades_total; ?></font></td>
<td align="center"><font size= "+1"><?php echo $cod_factura; ?></font></td>
<td align="center"><font size= "+1"><?php echo $nombre_proveedores; ?></font></td>
<td align="center"><font size= "+1"><?php echo $fecha_anyo; ?></font></td>
</tr>
<?php 
}
while ($datos = mysql_fetch_assoc($consulta)); ?>
</table>
</table>
</form>
</center>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
</head>