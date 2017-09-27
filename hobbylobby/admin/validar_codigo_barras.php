<?php
require_once('../conexiones/conexione.php');
require_once('../evitar_mensaje_error/error.php');
mysql_select_db($base_datos, $conectar); 
$cod_productos_var = $_GET['cod_productos_var'];

$obtener_cedula = "SELECT nombre_productos FROM productos WHERE cod_productos_var = '$cod_productos_var'";
$consultar_cedula = mysql_query($obtener_cedula, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consultar_cedula);
$total = mysql_num_rows($consultar_cedula);

$nombre_productos = $datos['nombre_productos'];
if($total == 0) {
echo "<strong></strong>";
} else {
?>
<img src=../imagenes/advertencia.gif alt='advertencia'> 
<strong><i><font color='yellow'>EL CODIGO: </i><?php echo $cod_productos_var?> <i>YA ESTA REGISTRADO AL PRODUCTO:</i> <?php echo utf8_encode($nombre_productos)?></font></strong> 
<img src=../imagenes/advertencia.gif alt='advertencia'>
<?php
}
?>