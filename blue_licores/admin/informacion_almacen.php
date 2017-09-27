<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
include ("../seguridad/seguridad_diseno_plantillas.php");

$nivel_acceso = '3';
if ($seguridad_acceso['cod_seguridad'] <> $nivel_acceso) {
header("Location:../admin/acceso_denegado.php");
}
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");

$mostrar_datos_sql = "SELECT * FROM informacion_almacen WHERE cod_informacion_almacen = '1'";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$matriz_consulta = mysql_fetch_assoc($consulta);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<center>
<td><strong><font color='yellow'>INFORMACION DE LA EMPRESA: </font></strong></td><br><br>
<table aling="center">
<tr>
<td aling="center">TITULO</td>
<td aling="center">CABECERA PROGRAM</td>
<td aling="center">CABECERA FACTURA</td>
<td aling="center">RES</td>
<td aling="center">DE</td>
<td aling="center">A</td>
<td aling="center">LOCALIDAD</td>
<td aling="center">TELEFONO</td>
<td aling="center">DIRECCION</td>
<td aling="center">NIT</td>
<td aling="center">INFO LEGAL</td>
<!--<td>Logotipo</td>-->
<td aling="center">ICONO</td>
<td aling="center">EDIT</td>
</tr>
<?php do { ?>
<tr>
<td ><?php echo $matriz_consulta['titulo']; ?></td>
<td ><?php echo $matriz_consulta['nombre']; ?></td>
<td ><?php echo $matriz_consulta['cabecera']; ?></td>
<td ><?php echo $matriz_consulta['res']; ?></td>
<td ><?php echo $matriz_consulta['res1']; ?></td>
<td ><?php echo $matriz_consulta['res2']; ?></td>
<td ><?php echo $matriz_consulta['localidad']; ?></td>
<td ><?php echo $matriz_consulta['telefono']; ?></td>
<td ><?php echo $matriz_consulta['direccion']; ?></td>
<td ><?php echo $matriz_consulta['nit']; ?></td>
<td aling="justify"><?php echo $matriz_consulta['info_legal']; ?></td>
<!--<td><center><img src=../imagenes/<?php //echo $matriz_consulta['logotipo']; ?> alt="logotipo"></td>-->
<td><center><img src=../imagenes/<?php echo $matriz_consulta['icono']; ?> alt="icono"></td>
<td ><a href="../modificar_eliminar/modificar_informacion_almacen.php?cod_informacion_almacen=<?php echo $matriz_consulta['cod_informacion_almacen']; ?>"><center><img src=../imagenes/actualizar.png alt="Actualizar"></a></td>
</tr>
<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
</table>
<br>
</body>
</html>
<?php mysql_free_result($consulta);?>