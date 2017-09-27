<?php
require_once('../conexiones/conexione.php'); 
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");

date_default_timezone_set("America/Bogota");
$cuenta_actual = addslashes($_SESSION['usuario']);
include ("../registro_movimientos/registro_movimientos.php");

if ($_POST['si'] == 'SI') {
//en la sigte linea colocar entre comillas el nombre de la tabla .' - '.date("H:i:s")
$tabla = "ventas";
$nombre = 'Reporte_'.$tabla.'_Descargado_'.date("d/m/Y").'_Hora_'.date("H:i:s");
header("Content-type: application/vnd.ms-excel" ) ;
header("Content-Disposition: attachment; filename=$nombre.xls" );

$consulta = mysql_query("select * from $tabla" ) ;
$campos = mysql_num_fields($consulta) ;
$i = 0;
echo "<table><tr>";
while($i < $campos){
echo "<td>". mysql_field_name ($consulta, $i) ;
echo "</td>";
$i++;
}
echo "</tr>";
while($datos_ventas = mysql_fetch_array($consulta)){
echo "<tr>";
for($j = 0; $j < $campos; $j++) {
echo "<td>".$datos_ventas[$j]."</td>";
}
echo "</tr>";
}
echo "</table>";

$sql = "SELECT max(cod_ventas) AS cod_ventas FROM ventas";
$consulta = mysql_query($sql, $conectar) or die(mysql_error());
$dato = mysql_fetch_assoc($consulta);

$cod_ventas = $dato['cod_ventas'];

$sql_factura = "SELECT * FROM ventas WHERE cod_ventas = '$cod_ventas'";
$consulta_factura = mysql_query($sql_factura, $conectar) or die(mysql_error());
$dato_factura = mysql_fetch_assoc($consulta_factura);

$cod_factura = $dato_factura['cod_factura'];

$vaciar  = sprintf("DELETE FROM ventas WHERE cod_ventas <> '$cod_ventas'");
$resultado_vaciar = mysql_query($vaciar , $conectar) or die(mysql_error());

$vacio = '';
$actualizar = sprintf("UPDATE ventas SET cod_productos  = '$vacio', cod_clientes = '$vacio', cod_factura = '$vacio', nombre_productos = '$vacio', unidades_vendidas = '$vacio', 
precio_compra = '$vacio', precio_costo = '$vacio', precio_venta = '$vacio', vlr_total_venta = '$vacio', vlr_total_compra = '$vacio', descuento = '$vacio', 
precio_compra_con_descuento = '$vacio', porcentaje_vendedor = '$vacio', vendedor = '$vacio', ip = '$vacio', fecha = '$vacio', fecha_mes = '$vacio', fecha_anyo = '$vacio', 
anyo = '$vacio', fecha_hora = '$vacio' WHERE cod_ventas = '$cod_ventas'");
$resultado_actualizar = mysql_query($actualizar, $conectar) or die(mysql_error());

$vaciar_info_impuesto_facturas  = sprintf("DELETE FROM info_impuesto_facturas WHERE cod_factura <> '$cod_factura'");
$resultado_vaciar_info_impuesto_facturas = mysql_query($vaciar_info_impuesto_facturas , $conectar) or die(mysql_error());

$actualizar_info_impuesto_facturas = sprintf("UPDATE info_impuesto_facturas SET descuento = '$vacio', iva = '$vacio', flete = '$vacio', cod_clientes = '$vacio', 
vlr_cancelado = '$vacio', vlr_vuelto = '$vacio', vendedor = '$vacio', fecha_dia = '$vacio', fecha_mes = '$vacio', fecha_anyo = '$vacio', anyo = '$vacio', 
fecha_hora = '$vacio' WHERE cod_factura = '$cod_factura'");
$resultado_actualizar_info_impuesto_facturas = mysql_query($actualizar_info_impuesto_facturas, $conectar) or die(mysql_error());

//echo "<center><font color='yellow' size= '+2'>SE HAN VACIADO TODAS LAS VENTAS</font></center>";
} else {
echo '<META HTTP-EQUIV="REFRESH" CONTENT="0.1; descargar_ventas_borrar.php">';
}
?>