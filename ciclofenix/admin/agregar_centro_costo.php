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

$pagina_actual = $_SERVER["PHP_SELF"];
$formulario_agregar = $_SERVER['PHP_SELF'];

$mostrar_datos_sql = "SELECT * FROM centro_costo";
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
<BR>
<td><strong><a href="centro_costo_dia.php"><font color='white'>CENTRO COSTO</font></a> - <font color='yellow'>AGREGAR CENTRO DE COSTO</font></strong></td><br><br>
<table width="40%">
<tr>
<td align="center"><strong>ELM</strong></td>
<td align="center"><strong>C&Oacute;DIGO</strong></td>
<td align="center"><strong>CENTRO COSTO</strong></td>
<td align="center"><strong>EDIT</strong></td>
</tr>
<?php do { ?>
<tr>
<td ><a href="../modificar_eliminar/eliminar_centro_costo.php?cod_ccosto=<?php echo $datos['cod_ccosto']; ?>"><center><img src=../imagenes/eliminar.png alt="eliminar"></center></a></td>
<td ><?php echo $datos['cod_ccosto']; ?></td>
<td ><?php echo $datos['nombre_ccosto']; ?></td>
<td ><a href="../modificar_eliminar/modificar_centro_costo.php?cod_ccosto=<?php echo $datos['cod_ccosto']; ?>"><center><img src=../imagenes/actualizar.png alt="Actualizar"></center></a></td>
</tr>
<?php } while ($datos = mysql_fetch_assoc($consulta)); ?>
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
<td align="center"><strong>NUEVO CENTRO COSTO</strong></td>
<?php do { ?>
<tr>
<td><input type="text" name="nombre_ccosto" value="" size="30" required autofocus></td>
</tr>
<?php } while ($datos = mysql_fetch_assoc($consulta)); ?>
<tr valign="baseline">
<td bordercolor="1"><input type="submit" id="boton1" value="Agregar"></td>
</tr>
</table>
<input type="hidden" name="insertar_datos" value="formulario">
</form>
</body>
</html>
<?php mysql_free_result($consulta);

if (isset($_POST["nombre_ccosto"])) {

$nombre_ccosto0 = $_POST["nombre_ccosto"];
$nombre_ccosto1 = preg_replace("/,/", '.', $nombre_ccosto0);
$nombre_ccosto2 = preg_replace("/'/", ' .', $nombre_ccosto1);
$nombre_ccosto = strtoupper(preg_replace('/"/', ' .', $nombre_ccosto2));
   
   // Hay campos en blanco
if($nombre_ccosto==NULL) {
echo "<embed SRC='../sonidos/alarma.mp3' hidden='true' autostart='true' loop='3'></embed>";
echo "<font color='yellow'><br><center><img src=../imagenes/advertencia.gif alt='Advertencia'><strong>
Ha dejado el campo centro costo vacio.</strong><img src=../imagenes/advertencia.gif alt='Advertencia'></font>";
} else {
         // Comprobamos si el nombre_ccosto ya existe
$verificar_nombre_ccosto = mysql_query("SELECT nombre_ccosto FROM centro_costo WHERE nombre_ccosto = '$nombre_ccosto'");
$existencia_nombre_ccosto = mysql_num_rows($verificar_nombre_ccosto);
         
if ($existencia_nombre_ccosto > 0) {
echo "<embed SRC='../sonidos/alarma.mp3' hidden='true' autostart='true' loop='3'></embed>";
echo "<font color='yellow'><br><center><img src=../imagenes/advertencia.gif alt='Advertencia'>
La centro costo <strong>".$nombre_ccosto." </strong>ya existe, verifique en la lista de centro_costo.<img src=../imagenes/advertencia.gif alt='Advertencia'></font>";
} else {
if ((isset($_POST["insertar_datos"])) && ($_POST["insertar_datos"] == "formulario")) {
$agregar_registros_sql1 = sprintf("INSERT INTO centro_costo (nombre_ccosto) VALUES (%s)",
envio_valores_tipo_sql($nombre_ccosto, "text"));
					   
$resultado_sql1 = mysql_query($agregar_registros_sql1, $conectar) or die(mysql_error());
echo '<META HTTP-EQUIV="REFRESH" CONTENT="0.1; agregar_centro_costo.php">';
echo "<font color='yellow'>Se ha ingresado correctamente la centro costo <strong>".$nombre_ccosto.".</strong></font>";
}
//mysql_query($agregar_registros_sql1) or die(mysql_error());
      }
   }
}
?>