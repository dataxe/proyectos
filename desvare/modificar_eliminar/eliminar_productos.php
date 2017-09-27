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

$cod_productos = intval($_GET['cod_productos']);

$sql_consulta = "SELECT * FROM productos WHERE cod_productos_var = '$cod_productos'";
$consulta = mysql_query($sql_consulta, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);

$cod_marcas = $datos['cod_marcas']; $cod_proveedores = $datos['cod_proveedores']; $cod_nomenclatura = $datos['cod_nomenclatura']; 
$cod_original = $datos['cod_original']; $numero_factura = $datos['numero_factura']; $nombre_productos = $datos['nombre_productos']; 
$unidades = $datos['unidades']; $tope_minimo = $datos['tope_minimo']; $numero_factura = $datos['unidades_faltantes']; 
$unidades_vendidas = $datos['unidades_vendidas']; $precio_costo = $datos['precio_costo']; $precio_compra = $datos['precio_compra'];
$precio_venta = $datos['precio_venta']; $codificacion = $datos['codificacion']; $utilidad = $datos['utilidad']; 
$total_utilidad = $datos['total_utilidad']; $total_mercancia = $datos['total_mercancia']; $descripcion = $datos['descripcion']; 
$gasto = $datos['gasto']; $total_utilidad = $cod_tipo['cod_tipo']; $cod_paises = $datos['total_utilidad']; 
$vendedor = $cuenta_actual;
$ip = $_SERVER['REMOTE_ADDR'];
$fechas_dia = date("Y/m/d");
$fechas_mes = date("m/Y"); 
$fechas_anyo = date("d/m/Y");
$fechas_hora = date("H:i:s");


if ((isset($_GET['cod_productos'])) && ($_GET['cod_productos'] != "")) {
$agregar_productos_eliminados = "INSERT INTO productos_eliminados (cod_productos, cod_marcas, cod_proveedores, cod_nomenclatura, cod_original, 
numero_factura, nombre_productos, unidades, tope_minimo, unidades_faltantes, unidades_vendidas, precio_costo, precio_compra, precio_venta, codificacion, utilidad, 
total_utilidad, total_mercancia, descripcion, gasto, cod_tipo, fechas_dia, fechas_mes, cod_paises, vendedor, fechas_anyo, fechas_hora, ip)
VALUES ('$cod_productos','$cod_marcas','$cod_proveedores','$cod_nomenclatura','$cod_original','$numero_factura','$nombre_productos','$unidades','$tope_minimo',
'$unidades_faltantes','$unidades_vendidas','$precio_costo','$precio_compra','$precio_venta','$codificacion','$utilidad','$total_utilidad','$total_mercancia',
'$descripcion','$gasto','$cod_tipo','$fechas_dia','$fechas_mes','$cod_paises','$vendedor','$fechas_anyo','$fechas_hora','$ip')";

$resultado_productos_eliminados = mysql_query($agregar_productos_eliminados, $conectar) or die(mysql_error());

$borrar_sql = sprintf("DELETE FROM productos WHERE cod_productos = '$cod_productos'");
$Result1 = mysql_query($borrar_sql, $conectar) or die(mysql_error());

echo '<META HTTP-EQUIV="REFRESH" CONTENT="0.1; productos_eliminar.php">';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
</body>
</html>
