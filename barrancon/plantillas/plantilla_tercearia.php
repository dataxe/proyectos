<?php error_reporting(E_ALL ^ E_NOTICE);
require_once('../conexiones/conexione.php');
require_once('../session/funciones_admin.php');
mysql_select_db($base_datos, $conectar);
date_default_timezone_set("America/Bogota");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta content="text/html; charset=iso-8859-1" http-equiv=Content-Type> 
<?php
$cuenta_actual = addslashes($_SESSION['usuario']);

$obtener_diseno_plantilla = "SELECT * FROM administrador WHERE cuenta = '$cuenta_actual'";
$resultado_diseno_plantilla = mysql_query($obtener_diseno_plantilla, $conectar) or die(mysql_error());
$matriz_plantilla = mysql_fetch_assoc($resultado_diseno_plantilla);

$obtener_informacion = "SELECT * FROM informacion_almacen WHERE cod_informacion_almacen = '1'";
$consultar_informacion = mysql_query($obtener_informacion, $conectar) or die(mysql_error());
$matriz_informacion = mysql_fetch_assoc($consultar_informacion);

$obtener_notificacion_alerta = "SELECT * FROM notificacion_alerta ORDER BY rand() LIMIT 1";
$resultado_notificacion_alerta = mysql_query($obtener_notificacion_alerta, $conectar) or die(mysql_error());
$total_alerta = mysql_num_rows($resultado_notificacion_alerta);
$alerta = mysql_fetch_assoc($resultado_notificacion_alerta);

$cod_notificacion_alerta = $alerta['cod_notificacion_alerta'];
$tipo_notificacion_alerta = $alerta['tipo_notificacion_alerta'];
$msj = $alerta['nombre_notificacion_alerta'];
$cod_productos_ance = $alerta['cod_productos_var'];
$nombre_productos_ance = $alerta['nombre_productos'];
$nombre_proveedores_ance = $alerta['nombre_proveedores'];
$nombre_clientes_ance = $alerta['nombre_clientes'];
$producto_alerta = $nombre_productos_ance;
$cod_factura_ance = $alerta['cod_factura'];

$fecha_invert = intval($alerta['fecha_invert']);
$fecha_hoy_seg = strtotime(date("Y/m/d"));

$ipss = $_SERVER['REMOTE_ADDR'];
$fecha_hoy = date("d/m/Y");
?>
<link href="../imagenes/<?php echo $matriz_informacion['icono'];?>" type="image/x-icon" rel="shortcut icon" />
<link rel="stylesheet" type="text/css" href="../estilo_css/<?php echo $matriz_plantilla['estilo_css'];?>">

<title><?php echo $matriz_informacion['titulo'];?></title>
</head>
<body>
<center>
<fieldset class="menu">
<h3><div align="left"><?php if ($total_alerta <> '0') {
//------------------------------------------------------ ALERTA PRODUCTOS AGOTADOS ------------------------------------------------------//
if ($tipo_notificacion_alerta == 'yellow') {
?>
<td><strong><font color="yellow">FECHA: <?php echo $fecha_hoy.' - ';?> <font color="<?php echo $tipo_notificacion_alerta;?>">
<?php echo $msj?>:  <a href="../admin/alertas.php?cod_notificacion_alerta=<?php echo $cod_notificacion_alerta?>&tipo=agotado">
<font color="<?php echo $tipo_notificacion_alerta;?>"><?php echo $producto_alerta;?> 
</font> <img src=../imagenes/advertencia1.gif alt="advertencia"></a></td>
<?php
} 
//------------------------------------------------------ ALERTA FACTURAS APUNTO DE VENCER EL PAGO ------------------------------------------------------//
if ($tipo_notificacion_alerta == 'white' && $fecha_hoy_seg >= $fecha_invert) {
?>
<td><strong><font color="yellow">FECHA: <?php echo $fecha_hoy.' - ';?></font><font color="<?php echo $tipo_notificacion_alerta;?>">
<?php echo $msj?>:  <a href="../admin/alertas.php?cod_notificacion_alerta=<?php echo $cod_notificacion_alerta?>&tipo=pagar"></font>
<font color="<?php echo $tipo_notificacion_alerta;?>"><?php echo $nombre_proveedores_ance.' - '.$cod_factura_ance.' - '.$producto_alerta;?></font> 
<img src=../imagenes/advertencia1.gif alt="advertencia"></a></td>
<?php
}
//------------------------------------------------------ ALERTA FACTURAS APUNTO DE PAGAR PERO AUN NO HA LLEGADO LA FECHA ------------------------------------------------------//
if ($tipo_notificacion_alerta == 'white' && $fecha_hoy_seg < $fecha_invert) {
?>
<td><strong><font color="yellow">FECHA: <?php echo $fecha_hoy;?></font></strong></td>
<?php
}
//------------------------------------------------------ ALERTA CUENTAS POR COBRAR APUNTO DE VENCER EL PAGO ------------------------------------------------------//
if ($tipo_notificacion_alerta == 'orange' && $fecha_hoy_seg >= $fecha_invert) {
?>
<td><strong><font color="yellow">FECHA: <?php echo $fecha_hoy.' - ';?></font><font color="<?php echo $tipo_notificacion_alerta;?>">
<?php echo $msj?>:  <a href="../admin/alertas.php?cod_notificacion_alerta=<?php echo $cod_notificacion_alerta?>&tipo=cobrar"></font>
<font color="<?php echo $tipo_notificacion_alerta;?>"><?php echo $nombre_clientes_ance.' - '.$producto_alerta.' - '.$cod_factura_ance;?></font> 
<img src=../imagenes/advertencia1.gif alt="advertencia"></a></td>
<?php
}
//------------------------------------------------------ ALERTA CUENTAS POR COBRAR PERO AUN NO HA LLEGADO LA FECHA ------------------------------------------------------//
if ($tipo_notificacion_alerta == 'orange' && $fecha_hoy_seg < $fecha_invert) {
?>
<td><strong><font color="yellow">FECHA: <?php echo $fecha_hoy;?></font></strong></td>
<?php
}
} else {
?>
<td><strong><font color="yellow">FECHA: <?php echo $fecha_hoy;?></font></strong></td>
<?php
}
?>
</div></h3>
<h2><?php echo $matriz_informacion['nombre'];?></h2>
<h1><?php //echo $matriz_pagina_actual['nombre_pagina_actual'];?></h1>
<h4><?php echo "BIENVENID".$matriz_plantilla['sexo'].' '.$matriz_plantilla['nombres'].' '.$matriz_plantilla['apellidos'];?></h4></fieldset>
<ul class="menu">
<li><a href="#">Productos</a>
<ul>
<!--<li><a href="../admin/cargar_factura_temporal_vendedor.php">Cargar Facturas</a></li>
<li><a href="../admin/transferencias_vendedor.php">Cargar Transf</a></li>
<li><a href="../admin/cargar_exportacion_temporal_vendedor.php">Cargar Auditoria</a></li>-->
<li><a href="../admin/copia_seguridad_base.php">Copia Seguridad</a></li>
<li><a href="../admin/descargar_productos.php">Rep. Productos</a></li>
<li><a href="../admin/descargar_ventas.php">Rep. Ventas</a></li>
<!--<li><a href="../admin/reparar_optimizar_db.php">Reparar Base</a></li>-->
</ul>
<li><a href="#">Marcas</a></li>
<!--<li><a href="../admin/marcas_vendedores.php">Marcas</a></li>-->
<li><a href="#">Proveedores</a></li>
<!--<li><a href="../admin/proveedores_vendedores.php">Proveedores</a></li>-->
<li><a href="#">Agotados</a>
<ul>
<li><a href="../admin/productos_agotados.php">Agotados</a></li>
<li><a href="../admin/tope_bajo.php">Tope Minimo</a></li>
</ul>

</li>
<li><a href="#">Ventas</a>
		 <ul>
		 	<li><a href="../admin/temporal.php">Manual</a></li>
		 	<li><a href="../admin/factura_eletronica.php">Electronica</a></li>
		 	<!--<li><a href="../admin/productos_fecha_vencimiento.php">F. Vencimiento</a></li>
		       <li><a href="../admin/ventas_diarias.php">Venta Diaria</a></li>
		       <li><a href="../admin/ventas_mensuales.php">Venta Mes</a></li>
			    <li><a href="../admin/busq_factura_prod_cancel.php">Cancelados</a></li>
		        <li><a href="../modificar_eliminar/productos_agotados_vendedores.php">Agotados</a></li>-->
		 </ul>
<li><a href="#">Cuentas</a>
<ul>
<li><a href="../admin/cuentas_cobrar_vendedor.php">Cobrar</a>
<li><a href="../admin/egresos_vendedor.php">Egresos</a>
</ul>
<!--
<li><a href="#">Stiker</a>
<ul>
<li><a href="../admin/stiker_productos_fecha.php">Crear Barras</a></li>
<li><a href="../admin/glosario_vendedor.php">Glosario</a></li>
</ul>
-->
<li><a href="../admin/clientes_vendedor.php">Clientes</a></li>
<li><a href="../admin/selecionar_diseno.php">Dise&ntilde;os</a></li>
		
<li><a href="../admin/mensajeria.php">Mensajeria</a></li>
<!--<li><a href="../admin/exportacion_csv_vendedor.php">Actualizar</a></li>-->
<li><a href="../session/salir_admin.php">Cerrar</a></li>
</ul>
</center>
</body>
</html>
<br>
