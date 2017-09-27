<?php
require_once('../conexiones/conexione.php'); 
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");

date_default_timezone_set("America/Bogota");
$cuenta_actual = addslashes($_SESSION['usuario']);
include ("../registro_movimientos/registro_movimientos.php");

//en la sigte linea colocar entre comillas el nombre de la tabla .' - '.date("H:i:s")
$tabla="ventas";
$nombre = 'Reporte_'.$tabla.'_Descargado_'.date("d/m/Y").'_Hora_'.date("H:i:s");
header("Content-type: application/vnd.ms-excel" ) ;
header("Content-Disposition: attachment; filename=$nombre.xls" );

$consulta=mysql_query("select * from $tabla" ) ;
$campos = mysql_num_fields($consulta) ;
$i=0;
echo "<table><tr>";
while($i<$campos){
echo "<td>". mysql_field_name ($consulta, $i) ;
echo "</td>";
$i++;
}
echo "</tr>";
while($row=mysql_fetch_array($consulta)){
echo "<tr>";
for($j=0; $j<$campos; $j++) {
echo "<td>".$row[$j]."</td>";
}
echo "</tr>";
}
echo "</table>";
?>