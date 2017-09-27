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

$mostrar_datos_sql = "SELECT * FROM lineas ORDER BY cod_lineas DESC";
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
<td><strong><font color='white'>TIPO LINEA: </font></strong></td>
<table width="40%">
<tr>
<td><div align="center"><strong>ELIM</strong></div></td>
<td><div align="center"><strong>C&Oacute;DIGO</strong></div></td>
<td><div align="center"><strong>TIPO LINEA</strong></div></td>
<td><div align="center"><strong>EDIT</strong></div></td>
</tr>
<?php do { ?>
<tr>
<td ><a href="../modificar_eliminar/eliminar_lineas.php?cod_lineas=<?php echo $matriz_consulta['cod_lineas']; ?>"><center><img src=../imagenes/eliminar.png alt="eliminar"></center></a></td>
<td ><?php echo $matriz_consulta['cod_lineas']; ?></td>
<td ><?php echo $matriz_consulta['nombre_lineas']; ?></td>
<td ><a href="../modificar_eliminar/modificar_lineas.php?cod_lineas=<?php echo $matriz_consulta['cod_lineas']; ?>"><center><img src=../imagenes/actualizar.png alt="Actualizar"></center></a></td>
</tr>
<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
</table>
<br>
<form method="post" name="formulario" action="<?php echo $formulario_agregar; ?>">
<table align="center">
<tr>
<td><div align="center" ><strong>TIPO LINEA</strong></div></td>
<?php do { ?>
<tr>
<td><input type="text" name="nombre_lineas" value="" size="30" required autofocus></td>
</tr>
<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
</tr>
</table>
<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td bordercolor="1"><input type="submit" id="boton1" value="REGISTRAR"></td>
<input type="hidden" name="insertar_datos" value="formulario">
</form>
</center>
</body>
</html>
<?php mysql_free_result($consulta);

if (isset($_POST["nombre_lineas"])) {
$nombre_lineas0 = $_POST["nombre_lineas"];
$nombre_lineas1 = preg_replace("/,/", '.', $nombre_lineas0);
$nombre_lineas2 = preg_replace("/'/", ' .', $nombre_lineas1);
$nombre_lineas = strtoupper(preg_replace('/"/', ' .', $nombre_lineas2));
   
   // Hay campos en blanco
if($nombre_lineas==NULL) {
echo "<center><embed SRC='../sonidos/alarma.mp3' hidden='true' autostart='true' loop='3'></embed></center>";
echo "<font color='yellow'><br><strong><center><img src=../imagenes/advertencia.gif alt='Advertencia'>Ha dejado el campo nombre vacio.<img src=../imagenes/advertencia.gif alt='Advertencia'></strong></font>";
} else {
         // Comprobamos si el nombre_lineas ya existe
$verificar_nombre_lineas = mysql_query("SELECT nombre_lineas FROM lineas WHERE nombre_lineas = '$nombre_lineas'");
$existencia_nombre_lineas = mysql_num_rows($verificar_nombre_lineas);
         
if ($existencia_nombre_lineas > 0) {
echo "<center><embed SRC='../sonidos/alarma.mp3' hidden='true' autostart='true' loop='3'></embed></center>";
echo "<font color='yellow'><br><center><img src=../imagenes/advertencia.gif alt='Advertencia'><strong>".$nombre_lineas." </strong>ya existe, verifique en la lista de lineas.<img src=../imagenes/advertencia.gif alt='Advertencia'></center></font>";
} else {
if ((isset($_POST["insertar_datos"])) && ($_POST["insertar_datos"] == "formulario")) {
$agregar_registros_sql1 = sprintf("INSERT INTO lineas (nombre_lineas) VALUES (%s)",
     				   envio_valores_tipo_sql($nombre_lineas, "text"));
					   
$resultado_sql1 = mysql_query($agregar_registros_sql1, $conectar) or die(mysql_error());
echo '<META HTTP-EQUIV="REFRESH" CONTENT="1; lineas.php">';
echo "<font color='yellow'><center>Se ha ingresado correctamente la marca <strong>".$nombre_lineas.".</strong></center></font>";
}
//mysql_query($agregar_registros_sql1) or die(mysql_error());
      }
   }
}
?>