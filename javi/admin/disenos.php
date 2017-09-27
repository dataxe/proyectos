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
include ("../formato_entrada_sql/funcion_env_val_sql.php");

$formulario_agregar = $_SERVER['PHP_SELF'];

$mostrar_datos_sql = "SELECT * FROM disenos WHERE nombre_disenos  like '%$buscar%' order by disenos.cod_disenos";
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
<br>
<center>
<table border="1">
<tr>
<td align="center"><strong>NOMBRE DISE&Ntilde;O</strong></td>
<td align="center"><strong>URL</strong></td>
<td align="center"><strong>NOMBRE</strong></td>
<td colspan="2"><strong>OPERACIONES</strong></td>
</tr>
<?php do { ?>
<tr>
<td><?php echo $matriz_consulta['nombre_disenos']; ?></td>
<td><?php echo $matriz_consulta['url_img']; ?></td>
<td><?php echo $matriz_consulta['nombres']; ?></td>
<td><a href="../modificar_eliminar/modificar_disenos.php?cod_disenos=<?php echo $matriz_consulta['cod_disenos']; ?>"><center><img src=../imagenes/actualizar.png alt="Actualizar"></a></td>
<td><a href="../modificar_eliminar/eliminar_disenos.php?cod_disenos=<?php echo $matriz_consulta['cod_disenos']; ?>"><center><img src=../imagenes/eliminar.png alt="eliminar"></a></td>
</tr>
<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
</table>
<script>
window.onload = function() {
  document.getElementById("foco").focus();
}
</script>
<br>
<form method="post" name="formulario" action="<?php echo $formulario_agregar; ?>">
<table align="center">
<tr>
<td align="center"><strong>NOMBRE DISE&Ntilde;O</strong></td>
<td align="center"><strong>URL</strong></td>
<td align="center"><strong>NOMBRE</strong></td>
<?php do { ?>
<tr>
<td><input id="foco" type="text" name="nombre_disenos" value="" size="30"></td>
<td><input id="foco" type="text" name="url" value="" size="30"></td>
<td><input id="foco" type="text" name="nombres" value="" size="30"></td>
</tr>
<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td bordercolor="1"><input type="submit" id="boton1" value="Agregar"></td>
</tr>
</table>
<input type="hidden" name="insertar_datos" value="formulario">
</form>
</body>
</html>
<?php mysql_free_result($consulta);

if (isset($_POST["nombre_disenos"])) {
$nombre_disenos = $_POST["nombre_disenos"];
$cod_disenos = $_POST["cod_disenos"];
   // Hay campos en blanco
if($nombre_disenos==NULL) {
echo "<font color='yellow'><br><strong>Ha dejado el campo nombre vacio.</strong></font>";
} else {
// Comprobamos si el nombre_disenos ya existe
$verificar_nombre_disenos = mysql_query("SELECT nombre_disenos FROM disenos WHERE nombre_disenos = '$nombre_disenos'");
$existencia_nombre_disenos = mysql_num_rows($verificar_nombre_disenos);
         
if ($existencia_nombre_disenos > 0) {
echo "<font color='yellow'><br>La marca <strong>".$nombre_disenos." </strong>ya existe, verifique en la lista de dise&ntilde;os.</font>";
} else {
if ((isset($_POST["insertar_datos"])) && ($_POST["insertar_datos"] == "formulario")) {
$agregar_registros_sql1 = sprintf("INSERT INTO disenos (nombre_disenos, nombres, url_img) VALUES (%s, %s, %s)",
envio_valores_tipo_sql($_POST['nombre_disenos'], "text"),
envio_valores_tipo_sql($_POST['nombres'], "text"),
envio_valores_tipo_sql($_POST['url'], "text"));
					   
$resultado_sql1 = mysql_query($agregar_registros_sql1, $conectar) or die(mysql_error());
echo '<META HTTP-EQUIV="REFRESH" CONTENT="1; disenos.php">';
echo "<font color='yellow'>Se ha ingresado correctamente la marca <strong>".$nombre_disenos.".</strong></font>";
}
//mysql_query($agregar_registros_sql1) or die(mysql_error());
}
}
}
?>