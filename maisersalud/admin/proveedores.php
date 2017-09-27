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

$nivel_acceso = '3';
if ($seguridad_acceso['cod_seguridad'] <> $nivel_acceso) {
header("Location:../admin/acceso_denegado.php");
}
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");

$buscar = mysql_escape_string($_POST['palabra']);

$mostrar_datos_sql = "SELECT * FROM proveedores WHERE nombre_proveedores LIKE '%$buscar%' OR cod_proveedores = '$buscar' ORDER BY nombre_proveedores";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$matriz_consulta = mysql_fetch_assoc($consulta);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
<body>
<br>
<center>
<?php
include ("../busquedas/busqueda_proveedores.php");
?>
<br>
<center>
<td><strong><font color='white' size='5px'>PROVEEDORES - </font><a href="../admin/proveedores_reg.php"><font color='yellow' size='5px'><strong>REGISTRAR NUEVO</font></a></strong></td><br><br><br>
<table width='85%'>
<tr>
<td align="center"><strong>ELM</strong></td>
<td align="center"><strong>C&Oacute;DIGO</strong></td>
<td align="center"><strong>NIT</strong></td>
<td align="center"><strong>NOMBRE</strong></td>
<td align="center"><strong>CORREO</strong></td>
<td align="center"><strong>TELEFONO</strong></td>
<td align="center"><strong>CIUDAD</strong></td>
<td align="center"><strong>DIRECCION</strong></td>
<td align="center"><strong>EDIT</strong></td>
</tr>
<?php do { ?>
<tr>
<td  nowrap><a href="../modificar_eliminar/eliminar_proveedores.php?cod_proveedores=<?php echo $matriz_consulta['cod_proveedores']; ?>"><center><img src=../imagenes/eliminar.png alt="eliminar"></center></a></td>
<td><?php echo $matriz_consulta['cod_proveedores']; ?></td>
<td><?php echo $matriz_consulta['identificacion_proveedores']; ?></td>
<td><?php echo $matriz_consulta['nombre_proveedores']; ?></td>
<td><?php echo $matriz_consulta['correo_proveedores']; ?></td>
<td><?php echo $matriz_consulta['telefono_proveedores']; ?></td>
<td><?php echo $matriz_consulta['ciudad_proveedores']; ?></td>
<td><?php echo $matriz_consulta['direccion_proveedores']; ?></td>
<td ><a href="../modificar_eliminar/modificar_proveedores.php?cod_proveedores=<?php echo $matriz_consulta['cod_proveedores']; ?>"><center><img src=../imagenes/actualizar.png alt="Actualizar"></center></a></td>
</tr>
<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
</table>
