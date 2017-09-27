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

$mostrar_datos_sql = "SELECT * FROM facturas_cargadas, proveedores WHERE nombre_archivo like '%$buscar%'  
AND (facturas_cargadas.cod_proveedor = proveedores.cod_proveedores) ORDER BY fecha_llegada ASC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$matriz_consulta = mysql_fetch_assoc($consulta);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISTEMA DE CONTENIDOS VIRTUALES</title>
</head>
<body>
<br>
<center>
<table>
<tr>
<td align="center"><strong>COD FACTURA</strong></td>
<td align="center"><strong>PROVEEDOR</strong></td>
<td align="center"><strong>DESCARGAR ARCHIVO</strong></td>
<td align="center"><strong>FECHA FACTURA</strong></td>
<td align="center"><strong>FECHA SUBIDA</strong></td>
<td align="center"><strong>EDIT</strong></td>
</tr>
<?php do { ?>
<tr>
<td ><?php echo $matriz_consulta['cod_facturas']; ?></td>
<td ><?php echo $matriz_consulta['nombre_proveedores']; ?></td>
<td ><a href="<?php echo $matriz_consulta['url_archivo']; ?>"target="_blank"><?php echo $matriz_consulta['nombre_archivo'];?>&nbsp;</a></td>
<td ><?php echo $matriz_consulta['fecha_llegada']; ?></td>
<td ><?php echo $matriz_consulta['fecha_cargue']; ?></td>
<td ><a href="../modificar_eliminar/modificar_archivo.php?cod_facturas_cargadas=<?php echo $matriz_consulta['cod_facturas_cargadas']; ?>"><center><img src=../imagenes/actualizar.png alt="Actualizar"></a></td>
</tr>
<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
</table>
<br>