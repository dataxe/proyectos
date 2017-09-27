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

$ipss = $_SERVER['REMOTE_ADDR'];
$fecha_hoy = date("d/m/Y");
/*
$obtener_venta_diaria = "SELECT sum(precio_compra_con_descuento) as vlr_total_venta, sum(vlr_total_compra) as vlr_total_compra FROM ventas 
WHERE fecha_anyo = '$fecha_hoy'";
$consultar_venta_diaria = mysql_query($obtener_venta_diaria, $conectar) or die(mysql_error());
$matriz_venta_diaria = mysql_fetch_assoc($consultar_venta_diaria);

$mostrar_datos_descuento_venta_facturacion = "SELECT Sum(descuento) As descuento_facturas FROM info_impuesto_facturas 
WHERE fecha_anyo = '$fecha_hoy'";
$consulta_descuento_venta_facturacion = mysql_query($mostrar_datos_descuento_venta_facturacion, $conectar) or die(mysql_error());
$matriz_descuento_venta_facturacion = mysql_fetch_assoc($consulta_descuento_venta_facturacion);

$total_descuento_dia = $matriz_descuento_venta_facturacion['descuento_facturas'];
*/
?>
<link href="../imagenes/<?php echo $matriz_informacion['icono'];?>" type="image/x-icon" rel="shortcut icon" />
<link rel="stylesheet" type="text/css" href="../estilo_css/<?php echo $matriz_plantilla['estilo_css'];?>">

<title><?php echo $matriz_informacion['titulo'];?></title>
<!--<link rel="stylesheet" type="text/css" href="../estilo_css/estilo_notificacion_alerta.css">-->
</head>
<body>
<center>
<fieldset class="menu"><!--<h3><div align="left">Venta: <font color='yellow'><?php //echo number_format($matriz_venta_diaria['vlr_total_venta'] - $total_descuento_dia);?></font>
Compra: <font color='yellow'><?php //echo number_format($matriz_venta_diaria['vlr_total_compra']);?></font> Fecha: <font color='yellow'>
<?php echo $fecha_hoy;?></font></div></h3>--><h3><div align="left"><font color='yellow'><strong>IP: <?php echo $ipss;?> - FECHA: <?php echo $fecha_hoy;?></strong></font></div></h3><h2><?php echo $matriz_informacion['nombre'];?></h2><h1><?php //echo $matriz_pagina_actual['nombre_pagina_actual'];?></h1>
<h4><?php echo "BIENVENID".$matriz_plantilla['sexo'].' '.$matriz_plantilla['nombres'].' '.$matriz_plantilla['apellidos'];?></h4></fieldset>
<ul class="menu">
	<li><a href="#">Productos</a>
		<ul>
		     <li><a href="../admin/cargar_factura_temporal.php">Cargar Facturas</a></li>
		     <li><a href="../admin/cargar_inventario.php">Cargar Inventario</a></li>
		     <li><a href="../admin/buscar_productos_editar.php">Cargar por Codigo</a></li>
			 <li><a href="../admin/tipo.php">Presentaciones</a></li>
			 <li><a href="../admin/ordenamiento.php">Estante</a></li>
			 <li><a href="../admin/productos_eliminados.php">Producto Elimdos</a></li>
			 <!--<li><a href="../admin/ordenamiento.php">Casilla</a></li>
             <li><a href="../admin/procedencia.php">Pais</a></li>-->
			 <li><a href="../modificar_eliminar/productos_eliminar.php">Eliminar</a></li>
	    </ul>
	</li>
	<li><a href="../admin/marcas.php">Marcas</a></li>
	<li><a href="../admin/proveedores.php">Proveedores</a></li>
	<li><a href="../admin/inventario_diario.php">Inventario</a></li>

	<li><a href="#">Facturaci&oacute;n</a>
	        <ul>
           <!--<li><a href="../admin/buscar_productos_facturacion_manual.php">Manual</a></li>
		   <li><a href="../modificar_eliminar/mano_de_obra.php">Mano de obra</a></li>
		   <li><a href="../admin/busq_facturas_cod_factura.php">Factura Cod</a></li>-->
		   <li><a href="../admin/busq_facturas_fecha.php">Factura Venta</a></li>
		   <li><a href="../admin/busq_facturas_compra.php">Factura Compra</a></li>
		   <li><a href="../admin/agregar_facturas_al_sistema.php">Cargar Fact Digit</a></li>
	     </ul>
	</li>
	<li><a href="#">Ventas</a>
		    <ul>
		    	<li><a href="../admin/temporal_admin.php">Manual</a></li>
		    	<li><a href="../admin/factura_eletronica_admin.php">Electronica</a></li>
		    	<li><a href="../admin/ventas_diarias.php">Ver Ventas</a></li>
		    	<li><a href="../admin/producto_mas_vendido_mes.php">Mas Vendidos</a></li>
		       <!--<li><a href="../admin/ventas_diarias.php">Venta Diaria</a></li>
		       <li><a href="../admin/ventas_mensuales.php">Venta Mes</a></li>
		       <li><a href="../admin/ventas_todas.php">Venta Todas</a></li>
			    <li><a href="../admin/eliminar_ventas_sin_factura.php">Elim venta</a></li>
			    <li><a href="../admin/busq_factura_prod_cancel.php">Cancelados</a></li>-->
			    <li><a href="../admin/ventas_barras.php">Rayas</a></li>
			    <li><a href="../admin/pregunta_inventario_a_cero.php">Invetario Cero</a></li>
		        <li><a href="../modificar_eliminar/productos_agotados.php">Agotados</a></li>
				<li><a href="../admin/tope_bajo.php">Tope Minimo</a></li>
		    </ul>
	</li>
	<li><a href="#">Cuentas</a>
			<ul>
				<li><a href="../admin/cuentas_cobrar.php">Por cobrar</a></li>
				<li><a href="../admin/cuentas_pagar.php">Por pagar</a></li>
				<li><a href="../admin/egreso_mensual.php">Egresos</a></li>
				<li><a href="../admin/clientes.php">Clientes</a></li>
				<li><a href="../admin/bancos.php">Bancos</a></li>
			</ul>
	</li>
	<li><a href="#">Stiker</a>
			<ul>
				<li><a href="../admin/stiker_productos_fecha.php">Crear Barras</a></li>
				<li><a href="../admin/stiker_estandar_128.php">Barras Fecha</a></li>
				<!--<li><a href="../admin/stiker_estandar_39.php">Code 39</a></li>
				<li><a href="../admin/stiker_estandar_qr.php">Cod QR</a></li>-->
				<li><a href="../admin/stiker_productos_transportar.php">Crear Barra Fact</a></li>	
			</ul>
	</li>
	<li><a href="../admin/selecionar_diseno.php">Dise&ntilde;os</a></li>

	<li><a href="#">Administrador</a>
            <ul>
		  		<li><a href="../admin/informacion_almacen.php">Info Almacen</a></li>
		  		<li><a href="../admin/registrar.php">Registrar</a></li>
		   		<li><a href="../admin/ver_administrador.php">Usuarios</a></li>
		   		<li><a href="../admin/glosario.php">Glosario</a></li>
		   		<li><a href="../admin/productos_fecha_vencimiento.php">Fecha vencimit</a></li>
		   		<li><a href="../admin/registro_movimientos.php">Movimientos</a></li>
		   		<li><a href="../admin/estadisticas.php">Estadisticas</a></li>
		   		<li><a href="../admin/mensajeria.php">Mensajeria</a></li>
		   		<li><a href="../admin/descargar_ventas.php">Reporte Ventas</a></li>
		   		<li><a href="../admin/descargar_productos.php">Reporte Product</a></li>
		   		<li><a href="../admin/copia_seguridad_base.php">Copia Base</a></li>
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
