<?php
require_once('../conexiones/conexione.php');
require_once('../evitar_mensaje_error/error.php');
mysql_select_db($base_datos, $conectar); 
$cedula = $_GET['cedula'];

$obtener_cedula = "SELECT nombres, apellidos FROM clientes WHERE cedula = '$cedula'";
$consultar_cedula = mysql_query($obtener_cedula, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consultar_cedula);
$total = mysql_num_rows($consultar_cedula);

$cliente = $datos['nombres'].' '.$datos['apellidos'];
if($total == 0) {
echo "<strong></strong>";
} else {
?>
<img src=../imagenes/advertencia.gif alt='advertencia'> 
<strong><i><font color='yellow'>LA CEDULA: </i><?php echo $cedula?> <i>YA ESTA REGISTRADA A NOMBRE DE:</i> <?php echo utf8_encode($cliente)?></font></strong> 
<img src=../imagenes/advertencia.gif alt='advertencia'>
<?php
}
?>