<?php
if (isset($_GET['valor']) && isset($_GET['id'])) {
require_once('../evitar_mensaje_error/error.php');
require_once('../conexiones/conexione.php'); 
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);

$valor_intro = $_GET['valor'];
$cod_productos_fiados = $_GET['id'];
$campo = $_GET['campo'];

$sql_modificar_consulta = "SELECT * FROM productos_fiados WHERE cod_productos_fiados = '$cod_productos_fiados'";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$datos_fiados = mysql_fetch_assoc($modificar_consulta);

$cod_productos_var = $datos_fiados['cod_productos'];
$cod_clientes = $datos_fiados['cod_clientes'];

$cod_factura = $datos_fiados['cod_factura'];
$tipo_pago = $datos_fiados['tipo_pago'];
$cod_uniq_credito = $datos_fiados['cod_uniq_credito'];

$sql_vent = "SELECT * FROM ventas WHERE cod_productos = '$cod_productos_var' AND cod_factura = '$cod_factura' AND cod_uniq_credito = '$cod_uniq_credito'";
$consulta_vent = mysql_query($sql_vent, $conectar) or die(mysql_error());
$datos_vent = mysql_fetch_assoc($consulta_vent);

$cod_ventas = $datos_vent['cod_ventas'];
$cod_marcas = $datos_vent['cod_marcas'];
$cod_proveedores = $datos_vent['cod_proveedores'];
///////////////////////////////////////////////////////////////////////////////////
$comentario = $datos_fiados['comentario'];
$origen_operacion = 'ventas';
$fecha_orig = $datos_fiados['fecha_anyo'];
$fecha = strtotime(date("Y/m/d"));
$fecha_mes = $datos_fiados['fecha_mes'];
$fecha_anyo = $datos_fiados['fecha_anyo'];
$anyo = $datos_fiados['anyo'];
$fecha_hora = $datos_fiados['fecha_hora'];
$ip = $_SERVER['REMOTE_ADDR'];
$cuenta = $cuenta_actual;
$fecha_time = time();
$nombre_productos = $datos_fiados['nombre_productos'];
$nombre_ccosto = $datos_fiados['nombre_ccosto'];
$precio_compra = $datos_fiados['precio_compra'];
$precio_costo = $datos_fiados['precio_costo'];
$precio_venta = $datos_fiados['precio_venta'];
$vlr_total_compra = $datos_fiados['vlr_total_compra'];
$detalles = $datos_fiados['detalles'];
$descuento_ptj = $datos_fiados['descuento_ptj'];
$iva = $datos_fiados['iva'];
$vendedor = $datos_fiados['vendedor'];
$fecha_devolucion = date("d/m/Y");
$hora_devolucion = date("H:i:s");
//////////////////////////////////////////////////////////////////////////////////

$sql = "SELECT * FROM productos WHERE cod_productos_var = '$cod_productos_var'";
$consulta = mysql_query($sql, $conectar) or die(mysql_error());
$data = mysql_fetch_assoc($consulta);

//------------------------------------------------ CONDICIONAL PARA EDICION DE LAS UNIDADES VENDIDAS ------------------------------------------------//
if ($campo == 'unidades_vendidas') {
$unidades_vendidas = $valor_intro;
$vlr_total_venta = $datos_fiados['precio_venta'] * $unidades_vendidas;

$unidades_actuales = $datos_fiados['unidades_vendidas'];
$und_vend_orig = $datos_fiados['unidades_vendidas'];
$unidades_devueltas = $unidades_actuales - $valor_intro;
$unidades_faltantes = $data['unidades_faltantes'] + $unidades_devueltas;

$actualizar_datos = "UPDATE productos_fiados SET unidades_vendidas = '$unidades_vendidas', vlr_total_venta = '$vlr_total_venta', und_vend_orig = '$und_vend_orig' 
WHERE cod_productos_fiados = '$cod_productos_fiados'";
$resultado_datos = mysql_query($actualizar_datos, $conectar) or die(mysql_error());

$actualizar_datos_prod = "UPDATE productos SET unidades_faltantes = '$unidades_faltantes' WHERE cod_productos_var = '$cod_productos_var'";
$resultado_datos_prod = mysql_query($actualizar_datos_prod, $conectar) or die(mysql_error());

$actualizar_ventas = "UPDATE ventas SET unidades_vendidas = '$unidades_vendidas', vlr_total_venta = '$vlr_total_venta' 
WHERE cod_productos = '$cod_productos_var' AND cod_factura = '$cod_factura' AND cod_uniq_credito = '$cod_uniq_credito'";
$resultado_ventas = mysql_query($actualizar_ventas, $conectar) or die(mysql_error());

$agregar_operacion = "INSERT INTO operacion (cod_ventas, cod_productos, nombre_productos, origen_operacion, cod_factura, cod_marcas, cod_proveedores, 
nombre_ccosto, unidades_vendidas, und_vend_orig, devoluciones, precio_compra, precio_costo, precio_venta, vlr_total_compra, vlr_total_venta, 
comentario, cod_clientes, detalles, descuento_ptj, iva, fecha_devolucion, hora_devolucion, fecha_orig, fecha, fecha_mes, fecha_anyo, anyo, 
fecha_hora, ip, vendedor, cuenta, fecha_time) 
VALUES ('$cod_ventas', '$cod_productos_var', '$nombre_productos', '$origen_operacion', '$cod_factura', '$cod_marcas', '$cod_proveedores', 
'$nombre_ccosto', '$unidades_vendidas', '$und_vend_orig', '$unidades_devueltas', '$precio_compra', '$precio_costo', '$precio_venta', 
'$vlr_total_compra', '$vlr_total_venta', '$comentario', '$cod_clientes', '$detalles', '$descuento_ptj', '$iva', '$fecha_devolucion', 
'$hora_devolucion', '$fecha_orig', '$fecha', '$fecha_mes', '$fecha_anyo', '$anyo', '$fecha_hora', '$ip', '$vendedor', 
'$cuenta', '$fecha_time')";
$resultado_operacion = mysql_query($agregar_operacion, $conectar) or die(mysql_error());

//------------------------------------------------ CALCULOS PARA EL TOTAL DE DEUDA Y LOS ABONOS ------------------------------------------------//
//------------------------------------------------ CALCULOS PARA EL TOTAL DE DEUDA Y LOS ABONOS ------------------------------------------------//
$sql_prod_fiados = "SELECT Sum(vlr_total_venta-(vlr_total_venta*(descuento_ptj/100))) As vlr_total_venta FROM productos_fiados 
WHERE cod_clientes = '$cod_clientes'";
$modificar_prod_fiados = mysql_query($sql_prod_fiados, $conectar) or die(mysql_error());
$datos_fiad = mysql_fetch_assoc($modificar_prod_fiados);

$sum_abonos_valor = "SELECT Sum(abonado) AS abonado FROM cuentas_cobrar_abonos WHERE cod_clientes = '$cod_clientes'";
$consulta_sum_abonos = mysql_query($sum_abonos_valor, $conectar) or die(mysql_error());
$sum_abonos = mysql_fetch_assoc($consulta_sum_abonos);

$monto_deuda = $datos_fiad['vlr_total_venta'];
$abonado = $sum_abonos['abonado'];

$actualizar_cuentas_cobrar = "UPDATE cuentas_cobrar SET monto_deuda = '$monto_deuda', abonado = '$abonado' WHERE cod_clientes = '$cod_clientes'";
$resultado_cuentas_cobrar = mysql_query($actualizar_cuentas_cobrar, $conectar) or die(mysql_error());
}
//------------------------------------------------ CONDICIONAL PARA LA EDICION DEL PRECIO DE VENTA Y EL TOTAL DE DEUDA Y LOS ABONOS ------------------------------------------------//
else {
$precio_venta = $valor_intro;
$vlr_total_venta = $datos_fiados['unidades_vendidas'] * $precio_venta;

$actualizar_prod_fiad = "UPDATE productos_fiados SET precio_venta = '$precio_venta', vlr_total_venta = '$vlr_total_venta' 
WHERE cod_productos_fiados = '$cod_productos_fiados'";
$resultado_prod_fiad = mysql_query($actualizar_prod_fiad, $conectar) or die(mysql_error()); 

//------------------------------------------------ CALCULOS PARA EL TOTAL DE DEUDA Y LOS ABONOS ------------------------------------------------//
$sql_prod_fiados = "SELECT Sum(vlr_total_venta-(vlr_total_venta*(descuento_ptj/100))) As vlr_total_venta FROM productos_fiados 
WHERE cod_clientes = '$cod_clientes'";
$modificar_prod_fiados = mysql_query($sql_prod_fiados, $conectar) or die(mysql_error());
$datos_fiad = mysql_fetch_assoc($modificar_prod_fiados);

$sum_abonos_valor = "SELECT Sum(abonado) AS abonado FROM cuentas_cobrar_abonos WHERE cod_clientes = '$cod_clientes'";
$consulta_sum_abonos = mysql_query($sum_abonos_valor, $conectar) or die(mysql_error());
$sum_abonos = mysql_fetch_assoc($consulta_sum_abonos);

$monto_deuda = $datos_fiad['vlr_total_venta'];
$abonado = $sum_abonos['abonado'];

$actualizar_cuentas_cobrar = "UPDATE cuentas_cobrar SET monto_deuda = '$monto_deuda', abonado = '$abonado' WHERE cod_clientes = '$cod_clientes'";
$resultado_cuentas_cobrar = mysql_query($actualizar_cuentas_cobrar, $conectar) or die(mysql_error());
}
}
?>