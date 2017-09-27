<?php error_reporting(E_ALL ^ E_NOTICE);
require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
$cuenta_actual = addslashes($_SESSION['usuario']);
include("../session/funciones_admin.php");
//include("../notificacion_alerta/mostrar_noficacion_alerta.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
  } else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
include ("../seguridad/seguridad_diseno_plantillas.php");
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");

require_once("menu_estadisticas.php");
include ("menu_grafico_productos_mas_menos_vendidos.php");

$sql_info = "SELECT nombre FROM informacion_almacen WHERE cod_informacion_almacen = '1'";
$consulta_info = mysql_query($sql_info, $conectar) or die(mysql_error());
$info = mysql_fetch_assoc($consulta_info);

$empresa = $info['nombre'];

$fecha_actual_en_segundos = time();
$fecha_hoy_en_segundos = time();

$mostrar_meses_vencer = "SELECT * FROM meses_vencimiento WHERE cod_meses_vencimiento = '1'";
$consulta_meses_vencer = mysql_query($mostrar_meses_vencer, $conectar) or die(mysql_error());
$meses_vencer = mysql_fetch_assoc($consulta_meses_vencer);

//Queremos sumar 6 meses a la fecha actual:
$meses = $meses_vencer['meses'];
// Convertimos los meses a segundos y se los sumamos sumamos a la fecha_actual_segundos:
$fecha_dentro_de_meses_segundos = time();
$fecha_dentro_de_meses_segundos += ($meses * 30 * 24 * 60 * 60);
?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<script type="text/javascript" src="jquery.min.js"></script>
		<style type="text/css">
${demo.css}
		</style>
		<script type="text/javascript">
$(function () {
    $('#container').highcharts({
        chart: {
            type: 'pie',
            options3d: {
                enabled: true,
                alpha: 45
            }
        },
        title: {
            text: 'PRODUCTOS A VENCER EN <?php echo $meses; ?> MESES'
        },
        subtitle: {
            text: '<?php echo $empresa ?>'
        },
        plotOptions: {
            pie: {
                innerSize: 100,
                depth: 45
            }
        },
        series: [{
            name: '-',
            data: [
<?php

$sql = "SELECT nombre_productos, fechas_vencimiento_seg, fechas_vencimiento, precio_venta FROM productos WHERE fechas_vencimiento_seg <= '$fecha_dentro_de_meses_segundos' AND fechas_vencimiento_seg <= '$fecha_dentro_de_meses_segundos' AND fechas_vencimiento_seg <> '' ORDER BY fechas_vencimiento_seg LIMIT 0,100";
$result = mysql_query($sql, $conectar) or die(mysql_error());
while ($registros = mysql_fetch_array($result)) {

$fechas_vencimiento_frag = explode('/', $registros["fechas_vencimiento"]);
$dia = $fechas_vencimiento_frag[0];
$mes = $fechas_vencimiento_frag[1];
$anyo = $fechas_vencimiento_frag[2];
$fechas_vencimiento = $mes.'/'.$anyo;

$fechas_vencimiento_seg = $registros["fechas_vencimiento_seg"];
if ($fechas_vencimiento_seg >= $fecha_actual_en_segundos) {
?>
['<?php echo $registros["nombre_productos"].'<br>Precio Venta: '.number_format($registros["precio_venta"]).'<br>Fecha: '.$fechas_vencimiento ?>', <?php echo $fechas_vencimiento_seg ?>],
<?php
}
}
?>
            ]
        }]
    });
});
		</script>
	</head>
	<body>

<script src="highcharts.js"></script>
<script src="highcharts-3d.js"></script>
<script src="exporting.js"></script>

<div id="container" style="height: 800px"></div>
	</body>
</html>
