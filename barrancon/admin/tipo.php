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

$mostrar_datos_sql = "SELECT * FROM tipo WHERE nombre_tipo  like '%$buscar%' order by cod_tipo DESC";
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
<td><strong><font color='white'>TIPO PRESENTACION: </font></strong></td>
<table width="50%">
<tr>
<td align="center"><strong>ELIM</strong></td>
<td align="center"><strong>C&Oacute;DIGO</strong></td>
<td align="center"><strong>TIPO PRESENTACION</strong></td>
<td align="center"><strong>EDIT</strong></td>
</tr>
<?php do { ?>
<tr>
<td ><a href="../modificar_eliminar/eliminar_tipo.php?cod_tipo=<?php echo $matriz_consulta['cod_tipo']; ?>"><center><img src=../imagenes/eliminar.png alt="eliminar"></center></a></td>
<td ><?php echo $matriz_consulta['cod_tipo']; ?></td>
<td ><?php echo $matriz_consulta['nombre_tipo']; ?></td>
<td ><a href="../modificar_eliminar/modificar_tipo.php?cod_tipo=<?php echo $matriz_consulta['cod_tipo']; ?>"><center><img src=../imagenes/actualizar.png alt="Actualizar"></center></a></td>
</tr>
<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
</table>
<br>
<form method="post" name="formulario" action="<?php echo $formulario_agregar; ?>">
<table align="center">
<tr>
<td align="center"><strong>TIPO PRESENTACION</strong></td>
<?php do { ?>
<tr>
<td><input type="text" name="nombre_tipo" value="" size="30" required autofocus></td>
</tr>
<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
</table>
<tr valign="baseline">
<td bordercolor="1"><input type="submit" id="boton1" value="REGISTRAR"></td>
</tr>
<input type="hidden" name="insertar_datos" value="formulario">
</form>
</center>
</body>
</html>
<?php mysql_free_result($consulta);

if (isset($_POST["nombre_tipo"])) {
$nombre_tipo0 = $_POST["nombre_tipo"];
$nombre_tipo1 = preg_replace("/,/", '.', $nombre_tipo0);
$nombre_tipo2 = preg_replace("/'/", ' .', $nombre_tipo1);
$nombre_tipo = strtoupper(preg_replace('/"/', ' .', $nombre_tipo2));
   
   // Hay campos en blanco
if($nombre_tipo==NULL) {
echo "<embed SRC='../sonidos/alarma.mp3' hidden='true' autostart='true' loop='3'></embed>";
echo "<font color='yellow'><br><strong><center><img src=../imagenes/advertencia.gif alt='Advertencia'>Ha dejado el campo nombre vacio.<img src=../imagenes/advertencia.gif alt='Advertencia'></strong></font>";
} else {
         // Comprobamos si el nombre_tipo ya existe
$verificar_nombre_tipo = mysql_query("SELECT nombre_tipo FROM tipo WHERE nombre_tipo = '$nombre_tipo'");
$existencia_nombre_tipo = mysql_num_rows($verificar_nombre_tipo);
         
if ($existencia_nombre_tipo > 0) {
echo "<embed SRC='../sonidos/alarma.mp3' hidden='true' autostart='true' loop='3'></embed>";
echo "<font color='yellow'><br><center><img src=../imagenes/advertencia.gif alt='Advertencia'><strong>".$nombre_tipo." </strong>ya existe, verifique en la lista de tipo.<img src=../imagenes/advertencia.gif alt='Advertencia'></center></font>";
} else {
if ((isset($_POST["insertar_datos"])) && ($_POST["insertar_datos"] == "formulario")) {
$agregar_registros_sql1 = sprintf("INSERT INTO tipo (nombre_tipo) VALUES (%s)",
     				   envio_valores_tipo_sql($nombre_tipo, "text"));
					   
$resultado_sql1 = mysql_query($agregar_registros_sql1, $conectar) or die(mysql_error());
echo '<META HTTP-EQUIV="REFRESH" CONTENT="1; tipo.php">';
echo "<font color='yellow'><center>Se ha ingresado correctamente la marca <strong>".$nombre_tipo.".</strong></center></font>";
}
//mysql_query($agregar_registros_sql1) or die(mysql_error());
      }
   }
}
?>