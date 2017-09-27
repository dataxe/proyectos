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

$pagina = $_SERVER['PHP_SELF'];
//$mostrar_datos_sql = "SELECT * FROM devoluciones WHERE (doc = '$buscar' OR nombre_y_apellidos LIKE '%$buscar%') ORDER BY nombre_y_apellidos DESC";
$mostrar_datos_sql = "SELECT facturas_cargadas_inv.cod_factura, facturas_cargadas_inv.cod_proveedores, proveedores.nombre_proveedores, facturas_cargadas_inv.tipo_pago, facturas_cargadas_inv.fecha_anyo 
FROM facturas_cargadas_inv LEFT JOIN proveedores ON facturas_cargadas_inv.cod_proveedores = proveedores.cod_proveedores 
GROUP BY facturas_cargadas_inv.cod_factura ORDER BY fecha DESC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$total_resultados = mysql_num_rows($consulta);
$datos = mysql_fetch_assoc($consulta);

require_once("menu_facturas_compra.php");
?>
<br>
<center>
<table width="60%">
<tr>
<td align="center"><strong>ELM</strong></td>
<td align="center"><strong>FACTURA</strong></td>
<td align="center"><strong>PROVEEDOR</strong></td>
<td align="center"><strong>DESCARGAR</strong></td>
<td align="center"><strong>PAGO</strong></td>
<td align="center"><strong>FECHA</strong></td>
<td align="center"><strong>EDIT</strong></td>
<!--<td align="center"><strong>SQL UPDATE FECHA SEG</strong></td>-->
</tr>
<?php do { 
$cod_factura = $datos['cod_factura'];
$cod_proveedores = $datos['cod_proveedores'];
$nombre_proveedores = $datos['nombre_proveedores'];
$tipo_pago = $datos['tipo_pago'];
$fecha_anyo = $datos['fecha_anyo'];
$vect_fecha = explode('/', $fecha_anyo);
$dia = $vect_fecha[0];
$mes = $vect_fecha[1];
$anyo = $vect_fecha[2];
$fecha_ymd = $anyo.'/'.$mes.'/'.$dia;
$fecha_seg = strtotime($fecha_ymd);
?>
<td><a href="../modificar_eliminar/eliminar.php?tipo=eliminar&tab=facturas_cargadas_inv&cod_factura=<?php echo $cod_factura?>&pagina=<?php echo $pagina?>"><center><img src=../imagenes/eliminar.png alt="Eliminar"></center></a></td>
<td><a href="../admin/ver_factura_compra.php?cod_factura=<?php echo $cod_factura?>&proveedor=<?php echo $nombre_proveedores?>"><?php echo $cod_factura; ?></a></td>
<td><a href="../admin/ver_factura_compra.php?cod_factura=<?php echo $cod_factura?>&proveedor=<?php echo $nombre_proveedores?>"><?php echo $nombre_proveedores; ?></a></td>
<td align="center"><a href="../admin/descargar_factura_compra_csv.php?cod_factura=<?php echo $cod_factura?>&proveedor=<?php echo $nombre_proveedores?>"><img src=../imagenes/descargar.png alt="descargar"></a></td>
<td><a href="../admin/ver_factura_compra.php?cod_factura=<?php echo $cod_factura?>&proveedor=<?php echo $nombre_proveedores?>"><?php echo strtoupper($tipo_pago); ?></a></td>
<td align="center"><a href="../admin/ver_factura_compra.php?cod_factura=<?php echo $cod_factura?>&proveedor=<?php echo $nombre_proveedores?>"><?php echo $fecha_anyo; ?></a></td>
<td align="center"><a href="../admin/ver_factura_compra_editable.php?cod_factura=<?php echo $cod_factura?>&cod_proveedores=<?php echo $cod_proveedores?>&proveedor=<?php echo $nombre_proveedores?>"><img src=../imagenes/actualizar.png alt="actualizar"></a></td>
<!--<td>UPDATE facturas_cargadas_inv SET fecha = '<?php echo $fecha_seg?>' WHERE fecha_anyo ='<?php echo $fecha_anyo?>';</td>-->
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