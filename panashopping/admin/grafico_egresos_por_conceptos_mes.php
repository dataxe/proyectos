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
$pagina = $_SERVER['PHP_SELF'];
?>
<center>
<br>
<form method="POST" name="formulario" action="<?php echo $pagina; ?>">
<table>
<tr>
<td align="center"><strong>MES</strong></td>
<td><select name="fecha_mes" require autofocus>
<?php $sql_consulta="SELECT DISTINCT fecha_mes FROM egresos ORDER BY fecha_invert DESC";
$resultado = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option style="font-size:20px" value="<?php echo $contenedor['fecha_mes'] ?>"><?php echo $contenedor['fecha_mes']?></option>
<?php }?></select></td>
<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td bordercolor="1"><input type="submit" id="boton1" value="Ver"></td>
</tr>
</table>
</form>
<?php
if (isset($_POST['fecha_mes'])) {
$fecha_mes = $_POST['fecha_mes'];
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
            text: '<b><?php echo $empresa ?><br><b> MES: <?php echo $fecha_mes ?></b>'
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
$sql = "SELECT conceptos, SUM(costo) AS costo FROM egresos WHERE fecha_mes = '$fecha_mes' GROUP BY conceptos ORDER BY costo DESC";
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
<?php
} else {
}
?>
