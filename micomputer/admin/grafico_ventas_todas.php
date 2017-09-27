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
?>
<!-- Latest compiled and minified JavaScript -->
<script src="jquery.js"></script>
<!-- Importo el archivo Javascript de Highcharts directamente desde su servidor -->
<script src="highstock.js"></script>
<script src="exporting.js"></script>
<br><br><br><br>
<fieldset>
<?php
echo "<center><td><font size='+3' color='yellow'>Ventas por dias</font><td><center>";
?>
<center>
<table>
<?php
function conectarBD(){
$server = "localhost";
$usuario = "clara";
$pass = "7bd1b51788dadb8c4caaf1d2714d47d9";
$BD = "papeles_y_mas";
//variable que guarda la conexión de la base de datos
$conexion = mysqli_connect($server, $usuario, $pass, $BD);
//Comprobamos si la conexión ha tenido exito
if(!$conexion){
echo 'Ha sucedido un error inexperado en la conexion de la base de datos<br>';
}
//devolvemos el objeto de conexión para usarlo en las consultas  
return $conexion;
}  
/*Desconectar la conexion a la base de datos*/
function desconectarBD($conexion){
//Cierra la conexión y guarda el estado de la operación en una variable
$close = mysqli_close($conexion);
//Comprobamos si se ha cerrado la conexión correctamente
if(!$close){  
echo 'Ha sucedido un error inexperado en la desconexion de la base de datos<br>';
}    
//devuelve el estado del cierre de conexión
return $close;        
}
 //Devuelve un array multidimensional con el resultado de la consulta
function getArraySQL($sql){
//Creamos la conexión
$conexion = conectarBD();
//generamos la consulta
if(!$result = mysqli_query($conexion, $sql)) die(); 
$vector_de_datos = array();
//guardamos en un array multidimensional todos los datos de la consulta
$i=0;
while($matriz = mysqli_fetch_array($result)) {  
//guardamos en vector_de_datos todos los vectores/filas que nos devuelve la consulta
$vector_de_datos[$i] = $matriz;
$i++;
}
//Cerramos la base de datos
desconectarBD($conexion);
//devolvemos vector_de_datos
return $vector_de_datos;
}
//Sentencia SQL
$sql = "SELECT SUM(vlr_total_venta) AS vlr_total_venta, fecha FROM ventas GROUP BY fecha ORDER BY fecha ASC";
//Array Multidimensional
$vector_de_datos = getArraySQL($sql);
//Adaptar el tiempo
for($i=0;$i<count($vector_de_datos);$i++){
//$time = $vector_de_datos[$i]["fecha_invert"];
$time = date("Y/m/d", $vector_de_datos[$i]["fecha"]);
$date = new DateTime($time);
$vector_de_datos[$i]["fecha"]=$date->getTimestamp()*1000;
}
?> 
<HTML>
<BODY>
<meta charset="utf-8">
<div id="container"></div>
<script type='text/javascript'>
$(function () {
$(document).ready(function() {
Highcharts.setOptions({
global: {
useUTC: false
}
});
var chart;
$('#container').highcharts({
chart: {
type: 'spline',
animation: Highcharts.svg, // don't animate in old IE
marginRight: 10,
events: {
load: function() {
}
}
},
title: {
text: 'Grafica de ventas'
},
xAxis: {
type: 'datetime',
tickPixelInterval: 150
},
yAxis: {
title: {
text: 'Valores en pesos'
},
plotLines: [{
value: 0,
width: 1,
color: '#808080'
}]
},
tooltip: {
formatter: function() {
return '<b>'+ this.series.name +'</b><br/>'+ Highcharts.dateFormat('%d/%m/%Y', this.x) +'<br/><b>$'+ Highcharts.numberFormat(this.y, 0)+'</b>';
//Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) +'<br/>'+ Highcharts.numberFormat(this.y, 0);
}
},
legend: {
enabled: true
},
exporting: {
enabled: true
},
series: [{
name: 'Ventas',
data: (function() {
var data = [];
<?php
for($i = 0 ;$i<count($vector_de_datos);$i++){
?>
data.push([<?php echo $vector_de_datos[$i]["fecha"];?>,<?php echo $vector_de_datos[$i]["vlr_total_venta"];?>]);
<?php } ?>
return data;
})()
},{            
}]
});
});  
});
</script>
</html>
</table>
</fieldset>
</center>