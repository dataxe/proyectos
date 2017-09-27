<?php error_reporting(E_ALL ^ E_NOTICE);
require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
include ("../seguridad/seguridad_diseno_plantillas.php");
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");

$fecha = $_GET['fecha'];
$mostrar_datos_sql = "SELECT * FROM egresos";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);

$pagina = $_SERVER['PHP_SELF'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<script language="javascript" src="isiAJAX.js"></script>
<script language="javascript">
var last;
function Focus(elemento, valor) {
$(elemento).className = 'inputon';
last = valor;
}
function Blur(elemento, valor, campo, id) {
$(elemento).className = 'inputoff';
if (last != valor)
myajax.Link('editable_egresos_todo_utilidad.php?valor='+valor+'&campo='+campo+'&id='+id);
}
</script>

</head>
<body onLoad="myajax = new isiAJAX();">

<br><br>
<center>
<td ><font color ='white'>TABLA EGRESOS TODO EDITABLE</font></td>
<br><br>
<table width="85%">
<tr>
<td ><?php echo 'cod_egresos' ?></td>
<td ><?php echo 'conceptos' ?></td>
<td ><?php echo 'comentarios' ?></td>
<td ><?php echo 'costo' ?></td>
<td ><?php echo 'nombre_ccosto' ?></td>
<td ><?php echo 'fecha_anyo' ?></td>
<td ><?php echo 'fecha' ?></td>
<td ><?php echo 'fecha_seg' ?></td>
<td ><?php echo 'fecha_invert' ?></td>
<td ><?php echo 'fecha_mes' ?></td>
<td ><?php echo 'anyo' ?></td>
<td ><?php echo 'hora' ?></td>
<td ><?php echo 'ip' ?></td>
<td ><?php echo 'cuenta' ?></td>
</tr>
<?php do { 
$cod_egresos = $datos['cod_egresos']; 
$conceptos = $datos['conceptos']; 
$comentarios = $datos['comentarios']; 
$costo = $datos['costo']; 
$nombre_ccosto = $datos['nombre_ccosto']; 
$fecha_anyo = $datos['fecha_anyo']; 
$fecha_invert = $datos['fecha_invert'];
$fecha = strtotime($datos['fecha_invert']);
$fecha_seg = strtotime($datos['fecha_invert']); 
$fecha_anyo = date("m/d/Y", $fecha); 
$fecha_mes = $datos['fecha_mes']; 
$anyo = $datos['anyo']; 
$hora = $datos['hora']; 
$ip = $datos['ip']; 
$cuenta = $datos['cuenta'];
?>
<tr>
<td ><?php echo $cod_egresos ?></td>
<td ><?php echo $conceptos ?></td>
<td ><?php echo $comentarios ?></td>
<td ><?php echo $costo ?></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'nombre_ccosto', <?php echo $cod_egresos;?>)" class="cajbarras" id="<?php echo $cod_egresos;?>" value="<?php echo $nombre_ccosto;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'fecha_anyo', <?php echo $cod_egresos;?>)" class="cajbarras" id="<?php echo $cod_egresos;?>" value="<?php echo $fecha_anyo;?>" size="3"></td>
<td ><?php echo $fecha ?></td>
<td ><?php echo $fecha_seg ?></td>
<td ><?php echo $fecha_invert ?></td>
<td ><?php echo $fecha_mes ?></td>
<td ><?php echo $anyo ?></td>
<td ><?php echo $hora ?></td>
<td ><?php echo $ip ?></td>
<td ><?php echo $cuenta ?></td>
</tr>
<?php } while ($datos = mysql_fetch_assoc($consulta)); ?>
</table>
</body>
</html>