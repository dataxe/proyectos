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
include ("menu_grafico_ventas_vendedor.php");
?>
<br>
<center>
<form action="" method="post">
<?php
$sql = "SELECT cuenta FROM administrador ORDER BY cuenta ASC";
$result = mysql_query($sql, $conectar) or die(mysql_error());
$total = mysql_num_rows($result);
while ($registros = mysql_fetch_array($result)) {
?>
<input name="cuenta[]" value="<?php echo $registros["cuenta"] ?>">
<?php
}
?>
<input name="total" value="<?php echo $total ?>">
<input type="submit" name="buscador" value="GRAFICAR TODOS LOS VENDEDORES"/>
</form>
</center>

<?php
if (isset($_POST["cuenta"])) {

$total_datos = $_POST["total"];

$sql_info = "SELECT nombre FROM informacion_almacen WHERE cod_informacion_almacen = '1'";
$consulta_info = mysql_query($sql_info, $conectar) or die(mysql_error());
$info = mysql_fetch_assoc($consulta_info);

$empresa = $info['nombre'];
?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title></title>

		<script type="text/javascript" src="jquery.min.js"></script>
<style type="text/css">
${demo.css}
		</style>
		<script type="text/javascript">
<?php
for ($i=0; $i < $total_datos; $i++) { 
$cuenta = $_POST['cuenta'][$i];
//echo "<br>".$cuenta;
?>
$(function () {
    $('#container').highcharts({
        chart: {
            type: 'areaspline'
        },
        title: {
            text: '<?php echo $empresa ?>'
        },
        legend: {
            layout: 'vertical',
            align: 'left',
            verticalAlign: 'top',
            x: 150,
            y: 100,
            floating: true,
            borderWidth: 1,
            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
        },
        xAxis: {
            categories: [
<?php
$sql = "SELECT fecha_mes FROM ventas GROUP BY fecha_mes ORDER BY fecha ASC";
$result = mysql_query($sql, $conectar) or die(mysql_error());
while ($registros = mysql_fetch_array($result)) {
?>
'.',
<?php
}
?>
            ],
            plotBands: [{ // visualize the weekend
                from: 0,
                to: 0,
                color: 'rgba(68, 170, 213, .2)'
            }]
        },
        yAxis: {
            title: {
                text: ''
            }
        },
        tooltip: {
            shared: true,
            valueSuffix: ''
        },
        credits: {
            enabled: false
        },
        plotOptions: {
            areaspline: {
                fillOpacity: 0.5
            }
        },
        series: [{ 

            name: '<?php echo $cuenta ?>',
            data: [
<?php
$sql = "SELECT SUM(vlr_total_venta) AS vlr_total_venta FROM ventas WHERE vendedor = '$cuenta' GROUP BY fecha_mes ASC";
$result = mysql_query($sql, $conectar) or die(mysql_error());
while ($registros = mysql_fetch_array($result)) {
?>
<?php echo $registros["vlr_total_venta"] ?>,
<?php
}
?>
            ]
        }]

    });
});
<?php
//echo "<br>".$cuenta;
}
?>
		</script>
	</head>
	<body>
<script src="highcharts.js"></script>
<script src="exporting.js"></script>

<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
<?php
} else {
}
?>
	</body>
</html>
