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
include ("../formato_entrada_sql/funcion_env_val_sql.php");

$pagina = $_SERVER['PHP_SELF'];
$ip = $_SERVER['REMOTE_ADDR'];
$fecha = date("d/m/Y");
$fecha_invert = date("Y/m/d");
$hora = date("H:i:s");

$agregar_reg = "INSERT INTO registro_movimientos (cuenta, pagina, ip, fecha, fecha_invert, hora)
VALUES ('$cuenta_actual','$pagina', '$ip', '$fecha','$fecha_invert','$hora')";
$resultado_reg = mysql_query($agregar_reg, $conectar) or die(mysql_error());

$buscar = $_POST['palabra'];
$mostrar_datos_sql = "SELECT * FROM administrador WHERE cod_seguridad = '1'";
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
<p>
<center>
<table border="1" id="table">
<tr>
<td><div align="center"><strong>Cod</strong></div></td>
<td><div align="center"><strong>Nombres</strong></div></td>
<td><div align="center"><strong>Apellidos</strong></div></td>
<td><div align="center"><strong>Cuenta</strong></div></td>
<td><div align="center"><strong>Contrasena</strong></div></td>
<td><div align="center"><strong>correo</strong></div></td>
<td><div align="center"><strong>Seguridad</strong></div></td>
<td><div align="center"><strong>Diseno</strong></div></td>
<td><div align="center"><strong>Fecha</strong></div></td>
<td colspan="2"><div align="center" ><strong>Operaciones</strong></div></td>
</tr>
<?php do { ?>
<tr>
<td ><?php echo $matriz_consulta['cod_administrador']; ?></td>
<td ><?php echo $matriz_consulta['nombres']; ?></td>
<td ><?php echo $matriz_consulta['apellidos']; ?></td>
<td ><?php echo $matriz_consulta['cuenta']; ?></td>
<td ><?php echo $matriz_consulta['contrasena']; ?></td>
<td ><?php echo $matriz_consulta['correo']; ?></td>
<td ><?php echo $matriz_consulta['cod_seguridad']; ?></td>
 <td ><?php echo $matriz_consulta['estilo_css']; ?></td>
<td ><?php echo $matriz_consulta['fecha']; ?></td>
<td ><a href="../modificar_eliminar/modificar_administrador.php?cod_administrador=<?php echo $matriz_consulta['cod_administrador']; ?>"><center><img src=../imagenes/actualizar.png alt="Actualizar"></a></td>
<td ><a href="../modificar_eliminar/eliminar_administrador.php?cod_administrador=<?php echo $matriz_consulta['cod_administrador']; ?>"><center><img src=../imagenes/eliminar.png alt="eliminar"></a></td>
</tr>
<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
</table>