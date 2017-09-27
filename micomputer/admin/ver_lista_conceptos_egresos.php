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

$nivel_acceso = '3';
if ($seguridad_acceso['cod_seguridad'] <> $nivel_acceso) {
header("Location:../admin/acceso_denegado.php");
}
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");

$mostrar_datos_sql = "SELECT * FROM gastos_tabla order by conceptos ASC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<center>
<br>
<td><strong><font color='yellow' size='+2'>LISTA CONCEPTOS DE EGRESOS</font></strong></td><br><br>
<center>
<table width="50%">
<tr>
<td align="center"><strong>ELM</strong></td>
<td align="center"><strong>CONCEPTO</strong></td>
<td align="center"><strong>EDIT</strong></td>
</tr>
<?php do { ?>
<tr>
<td ><a href="../modificar_eliminar/eliminar_lista_conceptos_egresos.php?cod_gastos_tabla=<?php echo $datos['cod_gastos_tabla']; ?>"><center><img src=../imagenes/eliminar.png alt="eliminar"></center></a></td>
<td ><?php echo $datos['conceptos']; ?></td>
<td ><a href="../modificar_eliminar/modificar_lista_conceptos_egresos.php?cod_gastos_tabla=<?php echo $datos['cod_gastos_tabla']; ?>"><center><img src=../imagenes/actualizar.png alt="Actualizar"></center></a></td>
</tr>
<?php } while ($datos = mysql_fetch_assoc($consulta)); ?>
</table>
</body>
</html>
<?php mysql_free_result($consulta);