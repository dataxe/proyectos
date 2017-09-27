<?php error_reporting(E_ALL ^ E_NOTICE);
require_once('../conexiones/conexione.php'); 
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

$nivel_acceso = '3';
if ($seguridad_acceso['cod_seguridad'] <> $nivel_acceso) {
header("Location:../admin/acceso_denegado.php");
}
include ("../registro_movimientos/registro_movimientos.php");

require_once("menu_estadisticas.php");
?>
<br><br><br>
<fieldset>
<?php
echo "<center><td><font size='+3' color='yellow'>Egresos por conceptos</font><td><center>";
?>
<center>
<table>
<script type="text/javascript" src="jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
var options = {
chart: {
renderTo: 'container',
plotBackgroundColor: null,
plotBorderWidth: null,
plotShadow: false
},
title: {
text: 'Grafico: Egresos por concepto'
},
tooltip: {
formatter: function() {
return '<b>'+ this.point.name +'</b>: '+ this.y + ' Pesos';
}
},
plotOptions: {
pie: {
allowPointSelect: true,
cursor: 'pointer',
dataLabels: {
enabled: true,
color: '#000000',
connectorColor: '#000000',
formatter: function() {
return '<b>'+ this.point.name +'</b>: '+ this.y;
}
}
}
},
series: [{
type: 'pie',
name: 'Egresos',
data: []
}]
}
$.getJSON("data_grafica_grafico_egresos_campana.php", function(json) {
options.series[0].data = json;
chart = new Highcharts.Chart(options);
});
});   
</script>
<script src="highcharts.js"></script>
<script src="exporting.js"></script>
</head>
<body>
<div id="container" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
</table>
</fieldset>
</center>