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
<!--<link rel="stylesheet" type="text/css" href="../estilo_css/estilo_notificacion_alerta.css">-->
</head>
<body>
<center>
<fieldset class="menu"><h3><div align="left"><?php if ($total_alerta <> '0') {
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
		     <li><a href="../admin/cargar_factura_temporal.php">Cargar Facturas</a></li>
		     <!--<li><a href="../admin/transferencias.php">Transferencias</a></li>
		     <li><a href="../admin/importar_comparacion_tablas.php">Auditoria</a></li>-->
		     <li><a href="../admin/cargar_inventario.php">Actualizar Invent</a></li>
		     <li><a href="../admin/marcas.php">Marcas</a></li>
		     <!--<li><a href="../admin/productos_modificados.php">Modificaciones</a></li>
		     <li><a href="../admin/buscar_productos_editar.php">Cargar por Codigo</a></li>-->
		     <li><a href="../admin/tipo.php">Presentaciones</a></li>
		     <li><a href="../admin/metrica.php">Metrica</a></li>
		     <li><a href="../admin/lineas.php">Linea</a></li>
			 <li><a href="../admin/ordenamiento.php">Estantes</a></li>
			 <!--<li><a href="../admin/productos_eliminados.php">Producto Elim</a></li>
			 <li><a href="../admin/procedencia.php">Pais</a></li>-->
			 <li><a href="../modificar_eliminar/productos_eliminar.php">Eliminar</a></li>
	    </ul>
	</li>
	<li><a href="../admin/proveedores.php">Proveedores</a></li>
	<li><a href="../admin/menu_inventario.php">Inventario</a></li>

	<li><a href="#">Facturaci&oacute;n</a>
	        <ul>
           <!--<li><a href="../admin/buscar_productos_facturacion_manual.php">Manual</a></li>
		   <li><a href="../modificar_eliminar/mano_de_obra.php">Mano de obra</a></li>
		   <li><a href="../admin/busq_facturas_cod_factura.php">Factura Cod</a></li>-->
		   <li><a href="../admin/ver_factura_ventas.php">Factura Venta</a></li>
		   <li><a href="../admin/factura_compra_por_factura.php">Factura Compra</a></li>
		   <li><a href="../admin/devoluciones_inventario_mensual.php">Devol Inventario</a></li>
		   <li><a href="../admin/devoluciones_ventas_mensual.php">Devol Ventas</a></li>
		   <!--<li><a href="../admin/buscar_para_hacer_compra_de_producto_historial.php">Historial Compra</a></li>-->
		   <!--<li><a href="../admin/buscar_para_hacer_venta_de_producto_historial.php">Historial Venta</a></li>-->
		   <!--<li><a href="../admin/factura_venta_vs_factura_compra_mes.php">Venta - Compra</a></li>-->
		   <!--<li><a href="../admin/devoluciones_inventario_mensual.php">Devol Inventario</a></li>-->
		   <!--<li><a href="../admin/devoluciones_ventas_mensual.php">Devol Ventas</a></li>-->
		   <!--<li><a href="../admin/busq_facturas_compra_vendedor.php">Factura.Compra.V</a></li>
		   <li><a href="../admin/agregar_facturas_al_sistema.php">Cargar Fact Digit</a></li>-->
	     </ul>
	</li>
	<li><a href="#">Ventas</a>
		    <ul>
		    	<li><a href="../admin/temporal_admin.php">Manual</a></li>
		    	<li><a href="../admin/factura_eletronica_admin.php">Electronica</a></li>
		    	<li><a href="../admin/ventas_diarias.php">Ver Ventas</a></li>
		    	<li><a href="../admin/producto_mas_vendido_mes.php">Mas Vendidos</a></li>
		    	<!--<li><a href="../admin/producto_mas_vendido_mes.php">Mas Vendidos</a></li>
		       <li><a href="../admin/ventas_diarias.php">Venta Diaria</a></li>
		       <li><a href="../admin/ventas_mensuales.php">Venta Mes</a></li>
		       <li><a href="../admin/ventas_todas.php">Venta Todas</a></li>
			    <li><a href="../admin/eliminar_ventas_sin_factura.php">Elim venta</a></li>
			    <li><a href="../admin/busq_factura_prod_cancel.php">Cancelados</a></li>
			    <li><a href="../admin/ventas_barras.php">Rayas</a></li>
			    <li><a href="../admin/productos_agotados_meses.php">Agotados meses</a></li>
			    <li><a href="../admin/pregunta_inventario_a_cero.php">Invetario Cero</a></li>-->
		        <li><a href="../admin/productos_agotados.php">Agotados</a></li>
				<li><a href="../admin/tope_bajo.php">Tope Minimo</a></li>
		    </ul>
	</li>
	<li><a href="#">Cuentas</a>
			<ul>
				<li><a href="../admin/cuentas_cobrar.php">Por cobrar</a></li>
				<li><a href="../admin/cuentas_pagar.php">Por pagar</a></li>
				<li><a href="../admin/centro_costo_dia.php">Centro costo</a></li>
				<li><a href="../admin/egreso_mensual.php">Egresos</a></li>
				<!--<li><a href="../admin/transferencias_almacenes.php">Almacenes</a></li>-->
				<li><a href="../admin/clientes.php">Clientes</a></li>
				<li><a href="../admin/descuento_ptj.php">% Descuento</a></li>
				<!--<li><a href="../admin/bancos.php">Bancos</a></li>
				<li><a href="../admin/caja_base.php">Base caja</a></li>
				<li><a href="../admin/caja_base.php">Base caja</a></li>-->
			</ul>
	</li>
<li><a href="../admin/kardex_venta_compra_invent_mensual_individual.php">Kardex</a></li>
	<!--
	<li><a href="#">Stiker</a>
			<ul>
				<li><a href="../admin/stiker_productos_fecha.php">Crear Barras</a></li>
				<li><a href="../admin/stiker_estandar_128.php">Barras Fecha</a></li>
				<li><a href="../admin/stiker_estandar_39.php">Code 39</a></li>
				<li><a href="../admin/stiker_estandar_qr.php">Cod QR</a></li>
				<li><a href="../admin/stiker_productos_transportar.php">Crear Barra Fact</a></li>	
			</ul>
		-->
	</li>
	<li><a href="../admin/selecionar_diseno.php">Dise&ntilde;os</a></li>
	<!--<li><a href="../admin/exportacion_csv_vendedor.php">Actualizar</a></li>-->

<li><a href="../admin/grafico_ventas_dias.php">Estadisticas</a></li>

	<li><a href="#">Administrador</a>
            <ul>
		  		<li><a href="../admin/informacion_almacen.php">Info Empresa</a></li>
		  		<!--<li><a href="../admin/registrar.php">Registrar</a></li>-->
		   		<li><a href="../admin/ver_administrador.php">Usuarios</a></li>
		   		<!--<li><a href="../admin/productos_fecha_vencimiento.php">Fecha vencimit</a></li>
		   		<li><a href="../admin/exportacion_csv.php">Exportar csv</a></li>
		   		<li><a href="../admin/importacion_csv.php">Importar csv</a></li>
		   		<li><a href="../admin/registro_movimientos.php">Movimientos</a></li>
		   		<li><a href="../admin/estadisticas.php">Estadisticas</a></li>
		   		<li><a href="../admin/mensajeria.php">Mensajeria</a></li>
		   	    <li><a href="../admin/descargar_productos.php">Rep. Productos</a></li>
		   		<li><a href="../admin/descargar_ventas.php">Rep. Ventas</a></li>-->
		   		<!--<li><a href="../admin/reparar_tablas.php">Reparar Tablas</a></li> 
		   	     <li><a href="../admin/descargar_ventas_borrar.php">Rep.Ventas.Y.Vaciar</a></li> -->
		   		<li><a href="../admin/glosario.php">Glosario</a></li>
		   		<li><a href="../admin/sesiones_usuarios.php">Sesiones</a></li>
		   </ul>
	</li>
	<li><a href="../session/salir_admin.php">Cerrar</a>
	</li>
</ul>
</center>
</body>
</html>
<br>
