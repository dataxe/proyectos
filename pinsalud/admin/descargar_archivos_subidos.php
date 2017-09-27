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

include ("../registro_movimientos/registro_movimientos.php");

$buscar = $_POST['palabra'];

$mostrar_datos_sql = "SELECT * FROM facturas_cargadas WHERE nombre_archivo like '%$buscar%' ORDER BY fecha_llegada ASC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$total = mysql_num_rows($consulta);
$datos = mysql_fetch_assoc($consulta);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISTEMA DE CONTENIDOS VIRTUALES</title>
</head>
<body>
<br>
<?php
if ($total <> 0) {
?>
<center>
<table width='95%'>
<tr>
<td align="center"><strong>ELM</strong></td>
<td align="center"><strong>ARCHIVO</strong></td>
<td align="center"><strong>DESCARGAR</strong></td>
<td align="center"><strong>FECHA</strong></td>
<td align="center"><strong>FECHA SUBIDA</strong></td>
<td align="center"><strong>CUENTA</strong></td>
</tr>
<?php do { 
$cod_facturas_cargadas = $datos['cod_facturas_cargadas'];
?>
<tr>
<td align="center"><a href="../modificar_eliminar/eliminar_archivo.php?cod_facturas_cargadas=<?php echo $cod_facturas_cargadas; ?>"><center><img src=../imagenes/eliminar.png alt="eliminar"></a></td>
<td><font size='+1'><?php echo $datos['nombre_archivo'];?></font></td>
<td align="center"><a href="<?php echo $datos['url_archivo']; ?>"target="_blank"><img src='../imagenes/descargar.png'></a></td>
<td><font size='+1'><?php echo $datos['fecha_llegada']; ?></font></td>
<td><font size='+1'><?php echo $datos['fecha_cargue']; ?></font></td>
<td><font size='+1'><?php echo $datos['cod_facturas']; ?></font></td>
</tr>
<?php } while ($datos = mysql_fetch_assoc($consulta)); ?>
</table>
<?php
} else {
echo "<center><td><font size='+3' color='white'>NO HAY ARCHIVOS CARGADOS PARA MOSTRAR</font></td></center>";
}
?>
<br>
