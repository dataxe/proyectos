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

$mostrar_datos_sql = "SELECT * FROM administrador LEFT JOIN seguridad ON administrador.cod_seguridad = seguridad.cod_seguridad AND 
administrador.cod_seguridad <> '5'";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$matriz_consulta = mysql_fetch_assoc($consulta);

$correcto = '0';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<center>
<td><strong><font color='yellow' size='+1'>INFORMACION USUARIOS: </font></strong></td><br><br>
<td ><a href="../admin/registrar.php"><strong><font color='yellow' size='+1'>REGISTRAR</font></strong></a></td>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<td ><a href="../admin/ver_administrador_contrasena.php"><strong><font color='yellow' size='+1'>VER CONTRASE&Ntilde;A</font></strong></a></td><br><br>
<table width="95%">
<tr>
<td><div align="center"><strong>ELIM</strong></div></td>
<td><div align="center"><strong>CUENTA</strong></div></td>
<td><div align="center"><strong>NOMBRES</strong></div></td>
<td><div align="center"><strong>APELLIDOS</strong></div></td>
<td><div align="center"><strong>CORREO</strong></div></td>
<td><div align="center"><strong>TIPO</strong></div></td>
<td><div align="center"><strong>DISE&Ntilde;O</strong></div></td>
<td><div align="center"><strong>CREADOR</strong></div></td>
<td><div align="center"><strong>FECHA</strong></div></td>
<td><div align="center"><strong>EDIT</strong></div></td>
</tr>
<?php do { ?>
<tr>
<td ><a href="../modificar_eliminar/eliminar_administrador.php?cod_administrador=<?php echo $matriz_consulta['cod_administrador']; ?>"><center><img src=../imagenes/eliminar.png alt="eliminar"></center></a></td>
<td ><?php echo $matriz_consulta['cuenta']; ?></td>
<td ><?php echo $matriz_consulta['nombres']; ?></td>
<td ><?php echo $matriz_consulta['apellidos']; ?></td>
<td ><?php echo $matriz_consulta['correo']; ?></td>
<td ><?php echo $matriz_consulta['nombre_seguridad']; ?></td>
<td ><?php echo $matriz_consulta['estilo_css']; ?></td>
<td ><?php echo $matriz_consulta['creador']; ?></td>
<td ><?php echo $matriz_consulta['fecha']; ?></td>
<td ><a href="../modificar_eliminar/modificar_administrador.php?cod_administrador=<?php echo $matriz_consulta['cod_administrador']; ?>&verificacion=<?php echo $correcto ?>"><center><img src=../imagenes/actualizar.png alt="Actualizar"></center></a></td>
</tr>
<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
</table>
</center>
</body>