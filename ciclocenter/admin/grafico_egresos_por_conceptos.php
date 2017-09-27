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
include ("menu_grafico_egresos.php");

$sql_info = "SELECT nombre FROM informacion_almacen WHERE cod_informacion_almacen = '1'";
$consulta_info = mysql_query($sql_info, $conectar) or die(mysql_error());
$info = mysql_fetch_assoc($consulta_info);

$empresa = $info['nombre'];
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
            text: 'EGRESOS POR CONCEPTOS'
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
            name: 'Egreso',
            data: [
<?php
$sql = "SELECT conceptos, SUM(costo) AS costo FROM egresos GROUP BY conceptos ORDER BY costo DESC";
$result = mysql_query($sql, $conectar) or die(mysql_error());
while ($registros = mysql_fetch_array($result)) {

?>
['<?php echo strtoupper($registros["conceptos"]).'<br>'.number_format($registros["costo"], 0, ",", ".") ?>', <?php echo ($registros["costo"]) ?>],
<?php
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
<div id="container" style="height: 500px"></div>
	</body>
</html>
