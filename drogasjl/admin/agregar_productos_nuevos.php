<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php');
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");
date_default_timezone_set("America/Bogota");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
include ("../seguridad/seguridad_diseno_plantillas.php");
//include("../notificacion_alerta/mostrar_noficacion_alerta.php");
include ("../registro_movimientos/registro_movimientos.php");

if (isset($_POST["nombre_productos"])) {
// $cod_productos = $_POST["cod_productos"];
$cod_productos_var = $_POST["cod_productos_var"];
$cod_marcas = $_POST["cod_marcas"];
$cod_proveedores = '1';
$cod_nomenclatura = $_POST["cod_nomenclatura"];
$cod_original = $_POST["cod_original"];
$nombre_factura = $_POST["nombre_factura"];
$nombre_productos0 = $_POST["nombre_productos"];
$nombre_productos1 = preg_replace('/,/', '.', $nombre_productos0);
$nombre_productos2 = preg_replace("/'/", ' -', $nombre_productos1);
$nombre_productos3 = preg_replace("/;/", ' :', $nombre_productos2);
$nombre_productos = strtoupper(preg_replace('/"/', ' -', $nombre_productos3));
$unidades = $_POST['unidades'];
$cajas = $_POST['cajas'];
$unidades_total = $_POST['unidades_total'];
$und_orig = $_POST['und_orig'];
$unidades_faltantes = $unidades;
$unidades_vendidas = $unidades;
$tope_minimo = $_POST["tope_minimo"];
$descripcion0 = $_POST["descripcion"];
$descripcion1 = preg_replace('/,/', '.', $descripcion0);
$descripcion2 = preg_replace("/'/", ' -', $descripcion1);
$descripcion3 = preg_replace("/;/", ' :', $nombre_productos2);
$descripcion = strtoupper(preg_replace('/"/', ' -', $descripcion3));
$cod_tipo = $_POST["cod_tipo"];
$cod_paises = intval($_POST["cod_paises"]);
$porcentaje_vendedor = $_POST["porcentaje_vendedor"];
$precio_costo = $_POST["precio_compra"];
$precio_compra = $_POST["precio_compra"];
$precio_venta = $_POST["precio_venta"];
$fechas_dia = date("Y/m/d");
$fechas_mes = date("m/Y");
$fechas_anyo = date("d/m/Y");
$fechas_hora = date("H:i:s");
$fechas_vencimiento = $_POST["fechas_vencimiento"];
$dato_fecha = explode('/', $fechas_vencimiento);
$dia = $dato_fecha[0];
$mes = $dato_fecha[1];
$anyo = $dato_fecha[2];
$fechas_vencimiento_Y_m_d = $anyo.'/'.$mes.'/'.$dia;
$fechas_vencimiento_seg = strtotime($fechas_vencimiento_Y_m_d);
$cuenta = $vendedor;
$dto1 = '0';
$dto2 = '0';
$iva = '0';
$iva_v = '0';
$tipo_pago = '0';
$codificacion = '0';
$ip = $_SERVER['REMOTE_ADDR'];
$url = '0';
$detalles = '0';
$cod_lineas = '1';
$fechas_agotado = '0';
$fechas_agotado_seg = '0';

$datos_filtro = "SELECT * FROM productos WHERE nombre_productos = '$nombre_productos' AND cod_marcas = '$cod_marcas'";
$consulta_filtro = mysql_query($datos_filtro, $conectar) or die(mysql_error());
$matriz_filtro = mysql_fetch_assoc($consulta_filtro);
$numero_de_productos = mysql_num_rows($consulta_filtro);

$filtro_nombre = $matriz_filtro['nombre_productos'];

$verificar_cod_productos = mysql_query("SELECT * FROM productos WHERE cod_productos_var = '$cod_productos_var'");
$existencia_cod_productos = mysql_num_rows($verificar_cod_productos);

if ($existencia_cod_productos > 0) {
echo "<center><font color='yellow' size='+6'><br><img src=../imagenes/advertencia.gif alt='Advertencia'> El codigo: <strong> ".$cod_productos_var. " </strong>ya existe. 
<img src=../imagenes/advertencia.gif alt='Advertencia'></font><center>";
echo "<center><embed SRC='../sonidos/alarma.mp3' hidden='true' autostart='true' loop='3'></embed></center>";
echo"<br><td><a href='../admin/productos.php'><center>REGRESAR</center></a></td>";
}
if($numero_de_productos > 0) {
$datos_marcas = "SELECT * FROM marcas WHERE cod_marcas = '$cod_marcas'";
$consulta_marcas = mysql_query($datos_marcas, $conectar) or die(mysql_error());
$matriz_marcas = mysql_fetch_assoc($consulta_marcas);

echo "<font color='yellow'><br><img src=../imagenes/advertencia.gif alt='Advertencia'> El producto ".$filtro_nombre." MARCA ".$matriz_marcas['nombre_marcas']. " existe dentro del inventario del sistema. 
<img src=../imagenes/advertencia.gif alt='Advertencia'></font>";
echo "<center><embed SRC='../sonidos/alarma.mp3' hidden='true' autostart='true' loop='2'></embed></center>";
//echo "<embed src='../sonidos/alarma.mp3' hidden='true autostart='true' loop='3'>";
}
// Hay campos en blanco
elseif($cod_productos_var==NULL || $nombre_productos==NULL) {
echo "<font color='yellow'><br><img src=../imagenes/advertencia.gif alt='Advertencia'> Ha dejado campos vacios. 
<img src=../imagenes/advertencia.gif alt='Advertencia'></font>";
echo "<center><embed SRC='../sonidos/alarma.mp3' hidden='true' autostart='true' loop='3'></embed></center>";
} else {
if ((isset($_POST["insertar_datos"])) && ($_POST["insertar_datos"] == "formulario")) {

$agregar_registros_sql1 = "INSERT INTO productos (cod_productos_var, cod_marcas, cod_proveedores, cod_nomenclatura, cod_original, numero_factura, 
nombre_productos, unidades, cajas, unidades_total, und_orig, dto1, dto2, iva, iva_v, tipo_pago, ip, url, detalles, cod_lineas,
fechas_agotado, fechas_agotado_seg, tope_minimo, unidades_faltantes, unidades_vendidas, precio_costo, precio_compra, precio_venta, 
codificacion, descripcion, cod_tipo, fechas_dia, fechas_mes, cod_paises, vendedor, cuenta, fechas_vencimiento, fechas_vencimiento_seg, 
porcentaje_vendedor, fechas_anyo, fechas_hora) 
VALUES ('$cod_productos_var', '$cod_marcas', '$cod_proveedores', '$cod_nomenclatura', '$cod_original', '$numero_factura', UPPER('$nombre_productos'), 
'$unidades', '$cajas', '$unidades_total', '$und_orig', '$dto1', '$dto2', '$iva', '$iva_v', '$tipo_pago', '$ip', '$url', '$detalles', 
'$cod_lineas', '$fechas_agotado', '$fechas_agotado_seg', '$tope_minimo', '$unidades_faltantes', '$unidades_vendidas', 
'$precio_costo', '$precio_compra', '$precio_venta', '$codificacion', UPPER('$descripcion'), '$cod_tipo', '$fechas_dia', '$fechas_mes', '$cod_paises', 
'$vendedor', '$cuenta', '$fechas_vencimiento', '$fechas_vencimiento_seg', '$porcentaje_vendedor', '$fechas_anyo', '$fechas_hora')";
$resultado_sql1 = mysql_query($agregar_registros_sql1, $conectar) or die(mysql_error());

echo '<META HTTP-EQUIV="REFRESH" CONTENT="3; productos.php">';
echo "<center><font color='yellow' size='15px'> Se ha ingresado correctamente el producto <strong>".$nombre_productos.".</strong></font><center>";
}
//mysql_query($agregar_registros_sql1) or die(mysql_error());
      }
   }
?>