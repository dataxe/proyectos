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
/*
$nivel_acceso = '3';
if ($seguridad_acceso['cod_seguridad'] <> $nivel_acceso) {
header("Location:../admin/acceso_denegado.php");
}
*/
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");

//ver los que se estan agregando por los usuarios y las unidades de las dos
$mostrar_datos_sql = "SELECT productos_copia_seguridad.cod_productos_copia_seguridad, 
productos_copia_seguridad.cod_productos_var, productos_copia_seguridad.nombre_productos, productos_copia_seguridad.unidades_faltantes AS unidades_faltantes_nuevo, 
productos_copia_inventario.unidades_faltantes AS unidades_faltantes_viejo, productos_copia_seguridad.precio_compra, productos_copia_seguridad.precio_costo, 
productos_copia_seguridad.precio_venta, productos_copia_seguridad.vlr_total_compra, productos_copia_seguridad.vlr_total_venta, 
productos_copia_seguridad.detalles, productos_copia_seguridad.fechas_anyo, productos_copia_seguridad.cuenta
FROM productos_copia_seguridad 
LEFT JOIN productos_copia_inventario ON productos_copia_seguridad.cod_productos_var = productos_copia_inventario.cod_productos_var ORDER BY productos_copia_seguridad.fechas_anyo DESC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$total = mysql_num_rows($consulta);


$calculos_inventario_costo_viejo = "SELECT Sum(productos_copia_inventario.precio_costo*productos_copia_inventario.unidades_faltantes) AS tot_precio_costo_viejo 
FROM productos_copia_seguridad INNER JOIN productos_copia_inventario 
ON productos_copia_seguridad.cod_productos_var = productos_copia_inventario.cod_productos_var";
$consulta_calculos_inventario_costo_viejo = mysql_query($calculos_inventario_costo_viejo, $conectar) or die(mysql_error());
$matriz_inventario_costo_viejo = mysql_fetch_assoc($consulta_calculos_inventario_costo_viejo);

$calculos_inventario_costo_nuevo = "SELECT Sum(productos_copia_seguridad.precio_costo*productos_copia_seguridad.unidades_faltantes) AS tot_precio_costo_nuevo 
FROM productos_copia_seguridad";
$consulta_calculos_inventario_costo_nuevo = mysql_query($calculos_inventario_costo_nuevo, $conectar) or die(mysql_error());
$matriz_inventario_costo_nuevo = mysql_fetch_assoc($consulta_calculos_inventario_costo_nuevo);


$calculos_sobran = "SELECT productos_copia_seguridad.cod_productos_var, 
SUM((productos_copia_seguridad.unidades_faltantes - productos_copia_inventario.unidades_faltantes)*productos_copia_seguridad.precio_costo) 
AS total_sobra, 
productos_copia_inventario.unidades_faltantes AS unidades_faltantes_viejo, productos_copia_seguridad.precio_compra, 
productos_copia_seguridad.precio_costo 
FROM productos_copia_seguridad 
LEFT JOIN productos_copia_inventario ON productos_copia_seguridad.cod_productos_var = productos_copia_inventario.cod_productos_var 
WHERE ((productos_copia_seguridad.unidades_faltantes - productos_copia_inventario.unidades_faltantes) > 0)
ORDER BY productos_copia_seguridad.fechas_anyo DESC";
$consulta_calculos_sobran = mysql_query($calculos_sobran, $conectar) or die(mysql_error());
$matriz_sobran = mysql_fetch_assoc($consulta_calculos_sobran);


$calculos_faltan = "SELECT productos_copia_seguridad.cod_productos_var, 
SUM((productos_copia_seguridad.unidades_faltantes - productos_copia_inventario.unidades_faltantes)*productos_copia_inventario.precio_costo) 
AS total_falta, 
productos_copia_inventario.unidades_faltantes AS unidades_faltantes_viejo, productos_copia_seguridad.precio_compra, 
productos_copia_seguridad.precio_costo 
FROM productos_copia_seguridad 
LEFT JOIN productos_copia_inventario ON productos_copia_seguridad.cod_productos_var = productos_copia_inventario.cod_productos_var 
WHERE ((productos_copia_seguridad.unidades_faltantes - productos_copia_inventario.unidades_faltantes) < 0)
ORDER BY productos_copia_seguridad.fechas_anyo DESC";
$consulta_calculos_faltan = mysql_query($calculos_faltan, $conectar) or die(mysql_error());
$matriz_faltan = mysql_fetch_assoc($consulta_calculos_faltan);


$tot_precio_costo_viejo = $matriz_inventario_costo_viejo['tot_precio_costo_viejo'];
$tot_precio_costo_nuevo = $matriz_inventario_costo_nuevo['tot_precio_costo_nuevo'];
$desfase_total = $tot_precio_costo_nuevo - $tot_precio_costo_viejo;
$total_sobra = $matriz_sobran['total_sobra'];
$total_falta = $matriz_faltan['total_falta'];

//--------------------------------------------------------------------------------------------------------//
$datos_user = "SELECT cod_seguridad, cuenta FROM administrador WHERE cuenta = '$cuenta_actual'";
$consulta_user = mysql_query($datos_user, $conectar) or die(mysql_error());
$info_user = mysql_fetch_assoc($consulta_user);

$cod_seguridad = $info_user['cod_seguridad'];
//--------------------------------------------------------------------------------------------------------//

$pagina = $_SERVER['PHP_SELF'] ;
$tipo = 'eliminar';
$tab = 'productos_copia_seguridad';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>ALMACEN</title>
</head>
<body>
<center>
<table border='1'>
<td><strong><a href="cargar_inventario_copia_vendedores.php"><font color='white'>CARGAR INVENTARIO MANUAL VENDEDOR</font></a></strong></td>&nbsp;&nbsp;&nbsp;&nbsp;
<td><strong><a href="productos_sin_cargar_inventario_vendedores.php"><font color='white'>FALTAN POR CARGAR</font></a></strong></td>&nbsp;&nbsp;&nbsp;&nbsp;
<td><strong><a href="productos_cargados_inventario_vendedores_comparacion_unidades.php"><font color='yellow'>PRODUCTOS CARGADOS</font></a></strong></td>&nbsp;&nbsp;&nbsp;&nbsp;
</table>
<br>
<td align="center"><font color="yellow" size="+2"><strong>INVENTARIO DE PRODUCTOS CARGADOS [<?php echo number_format($total, 0, ",", ".") ?>]</font></td>
<br>
<!-- Total mercancia venta y utilidad mes -->
<table width='50%' border='1'>
<td align="center">TOTAL COSTO INV NUEVO</td>
<td align="center">TOTAL COSTO INV VIEJO</td>
<td align="center">SOBRAN $</td>
<td align="center">FALTAN $</td>
<td align="center">DESFASE TOTAL $</td>
<tr>
<td align="center"><font color="yellow" size="+1"><strong><?php echo number_format($tot_precio_costo_nuevo, 0, ",", "."); ?></font></td>
<td align="center"><font color="yellow" size="+1"><strong><?php echo number_format($tot_precio_costo_viejo, 0, ",", "."); ?></font></td>
<td align="center"><font color="yellow" size="+1"><strong><?php echo number_format($total_sobra, 0, ",", "."); ?></font></td>
<td align="center"><font color="yellow" size="+1"><strong><?php echo number_format($total_falta, 0, ",", ".") * -1; ?></font></td>
<td align="center">	<font color="yellow" size="+1"><strong>

<?php
if ($desfase_total < 0) {
echo number_format($desfase_total, 0, ",", "."); ?> <img src=../imagenes/abajo.gif alt="abajo">
<?php
} elseif ($desfase_total > 0) {
echo number_format($desfase_total, 0, ",", "."); ?> <img src=../imagenes/arriba.gif alt="arriba">
<?php
} else {
echo number_format($desfase_total, 0, ",", ".");
}
?>
</font></td>
</tr>
</table>

<br>
<table width='100%' border='1'>
<tr>
<td align="center">C&Oacute;DIGO</td>
<td align="center">PRODUCTO</td>
<td align="center">INV NUEVO</td>
<td align="center">INV VIEJO</td>
<td align="center"></td>
<td align="center">CORRECION</td>
<td align="center"></td>
<td align="center">$</td>
<?php if ($cod_seguridad == 3 || $cod_seguridad == 2) { ?>
<td align="center">P.COSTO</td>
<?php } else { } ?>
<td align="center">P.VENTA</td>
<td align="center">P.VENTA2</td>
<td align="center">FECHA</td>
<td align="center">CUENTA</td>
<!-- //////////////////////////////////////////////////////////////////////////////////////// -->
<?php if ($cod_seguridad == 3 || $cod_seguridad == 2) { ?>
<td align="center">ELM</td>
<?php } else { } ?>
<!-- //////////////////////////////////////////////////////////////////////////////////////// -->
</tr>
<?php 
while ($datos = mysql_fetch_assoc($consulta)) {
$cod_productos_copia_seguridad = $datos['cod_productos_copia_seguridad'];
$cod_productos_var = $datos['cod_productos_var'];
$nombre_productos = $datos['nombre_productos'];
$unidades_faltantes_nuevo = $datos['unidades_faltantes_nuevo'];
$unidades_faltantes_viejo = $datos['unidades_faltantes_viejo'];
$precio_costo = $datos['precio_costo'];
$precio_venta = $datos['precio_venta'];
$precio_compra = $datos['precio_compra'];
$vlr_total_venta = $datos['vlr_total_venta'];
$fechas_anyo = $datos['fechas_anyo'];
$fecha = date("d/m/Y - H:i:s", $fechas_anyo);
$cuenta = $datos['cuenta'];
$resta = $unidades_faltantes_nuevo - $unidades_faltantes_viejo;
?>
<tr>
<td align='left'><?php echo $cod_productos_var ?></td>
<td align='left'><?php echo $nombre_productos ?></td>
<td align='center'><?php echo $unidades_faltantes_nuevo ?></td>
<td align='center'><?php echo $unidades_faltantes_viejo ?></td>
<?php
//SI EXISTEN HAY MAS UNIDADES EN EL SISTEMA QUE FISICAS. UNIDADES EXTRAVIADAS 
if ($resta < 0) {
?>
<td align="right" color="yellow"><font size="3px"><?php echo ($resta * -1) ?></font></td>
<td align="left" color="yellow"><font size="1px"><?php echo "UNDS FALTAN"; ?></font></td>
<td align="center"><img src=../imagenes/auxilio.gif alt="auxilio"></td>
<td align="right" color="yellow"><font size="3px"><?php echo number_format((($resta * $precio_costo)* -1), 0, ",", ".") ?></font></td>
<?php
}
// SI HAY MAS UNIADES FISICAS QUE EN EL SISTEMA. 
elseif ($resta > 0) {
?>
<td align="right" color="yellow"><font size="3px"><?php echo $resta ?></font></td>
<td align="left" color="yellow"><font size="1px"><?php echo "UNDS SOBRAN"; ?></font></td>
<td align="center"><img src=../imagenes/borrar.gif alt="borrar"></td>
<td align="right" color="yellow"><font size="3px"><?php echo number_format(($resta * $precio_costo), 0, ",", ".") ?></font></td>
<?php
// SI LAS UNIDADES DEL SISTEMA SON LAS MISMAS QUE HAY EN FISICO. EL INVENTARIO ES CORRECTO
} else {
?>
<td align="right" color="white"><font size="3px"><?php echo $resta; ?></font></td>
<td align="left" color="white"><font size="1px"><?php echo "BIEN"; ?></font></td>
<td align="center"><img src=../imagenes/bien.png alt="Bien"></td>
<td align="right" color="yellow"><font size="3px"><?php echo number_format(($resta * $precio_costo), 0, ",", ".") ?></font></td>
<?php
}
?>
<?php if ($cod_seguridad == 3 || $cod_seguridad == 2) { ?>
<td align='right'><?php echo number_format($precio_costo, 0, ",", ".") ?></td>
<?php } else { } ?>
<td align='right'><?php echo number_format($precio_venta, 0, ",", ".") ?></td>
<td align='right'><?php echo number_format($vlr_total_venta, 0, ",", ".") ?></td>
<td align='center'><?php echo $fecha ?></td>
<td align='center'><?php echo $cuenta ?></td>
<!-- //////////////////////////////////////////////////////////////////////////////////////// -->
<?php if ($cod_seguridad == 3 || $cod_seguridad == 2) { ?>
<td align='center'><a href="../modificar_eliminar/eliminar.php?cod_productos_copia_seguridad=<?php echo $cod_productos_copia_seguridad?>&pagina=<?php echo $pagina?>&tipo=<?php echo $tipo?>&tab=<?php echo $tab?>"><img src=../imagenes/eliminar.png alt="eliminar"></a></td>
<?php } else { } ?>
<!-- //////////////////////////////////////////////////////////////////////////////////////// -->
</tr>
<?php } ?>
</table>
</center>
</body>
</html>