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
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");

$pagina_actual = $_SERVER["PHP_SELF"];
$formulario_agregar = $_SERVER['PHP_SELF'];

$mostrar_datos_sql = "SELECT * FROM inventario WHERE cuenta = '$cuenta_actual' ORDER BY nombre_valor DESC";
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
<center>
<td><a href="plata.php"><strong><font color='white'>PLATA: </font></strong></a></td><br><br>
<table width="40%" id="table">
<tr>
<td><div align="center"><strong>ELM</strong></div></td>
<td><div align="center"><strong>PLATA</strong></div></td>
<td><div align="center"><strong>UND</strong></div></td>
<td><div align="center"><strong>TOTAL</strong></div></td>
<td><div align="center"><strong>EDIT</strong></div></td>
</tr>
<?php do { ?>
<tr>
<td ><a href="../modificar_eliminar/eliminar_inventario.php?cod_inventario=<?php echo $matriz_consulta['cod_inventario']; ?>"><center><img src=../imagenes/eliminar.png alt="eliminar"></center></a></td>
<td align="right"><font size='6' color="white"><?php echo number_format($matriz_consulta['nombre_valor']); ?></font></td>
<td align="center"><font size='6' color="white"><?php echo $matriz_consulta['numero']; ?></font></td>
<td align="right"><font size='6' color="white"><?php echo number_format($matriz_consulta['total']); ?></font></td>
<td ><a href="../modificar_eliminar/modificar_inventario.php?cod_inventario=<?php echo $matriz_consulta['cod_inventario']; ?>"><center><img src=../imagenes/actualizar.png alt="Actualizar"></center></a></td>
</tr>
<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
</table>
<br>
<form method="post" id="table" name="formulario" action="<?php echo $formulario_agregar; ?>">
<table align="center" id="table">
<tr>
<td><div align="center" ><strong>PLATA</strong></div></td>
<td><div align="center" ><strong>UND</strong></div></td>
<?php do { ?>
<tr>
<td><input type="text" style="font-size:34px" name="nombre_valor" value="" size="6" required autofocus></td>
<td><input type="text" style="font-size:34px" name="numero" value="0" size="6" required autofocus></td>
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

if (isset($_POST["nombre_valor"])) {
$nombre_valor = $_POST["nombre_valor"];
$numero = $_POST["numero"];
$total = $nombre_valor * $numero;
   
 // Hay campos en blanco
if($nombre_valor==NULL) {
echo "<embed SRC='../sonidos/alarma.mp3' hidden='true' autostart='true' loop='3'></embed>";
echo "<font color='yellow'><br><center><img src=../imagenes/advertencia.gif alt='Advertencia'><strong>Ha dejado el campo plata vacio.</strong><img src=../imagenes/advertencia.gif alt='Advertencia'></font>";
} else {
         // Comprobamos si el nombre_valor ya existe
$verificar_nombre_valor = mysql_query("SELECT nombre_valor FROM inventario WHERE nombre_valor = '$nombre_valor' AND cuenta = '$cuenta_actual'");
$existencia_nombre_valor = mysql_num_rows($verificar_nombre_valor);
         
if ($existencia_nombre_valor > 0) {
  echo "<embed SRC='../sonidos/alarma.mp3' hidden='true' autostart='true' loop='3'></embed>";
echo "<font color='yellow'><br><center><img src=../imagenes/advertencia.gif alt='Advertencia'>El valor <strong>".$nombre_valor." </strong>ya existe, verifique en la lista de inventario.<img src=../imagenes/advertencia.gif alt='Advertencia'></font>";
echo '<META HTTP-EQUIV="REFRESH" CONTENT="2; crear_plata.php">';
} else {
if ((isset($_POST["insertar_datos"])) && ($_POST["insertar_datos"] == "formulario")) {
$agregar_registros_sql1 = sprintf("INSERT INTO inventario (numero, cuenta, total, nombre_valor) VALUES (%s, %s, %s, %s)",
envio_valores_tipo_sql($_POST['numero'], "text"),
envio_valores_tipo_sql($cuenta_actual, "text"),
envio_valores_tipo_sql($total, "text"),
envio_valores_tipo_sql($_POST['nombre_valor'], "text"));
					   
$resultado_sql1 = mysql_query($agregar_registros_sql1, $conectar) or die(mysql_error());
echo '<META HTTP-EQUIV="REFRESH" CONTENT="0.1; crear_plata.php">';
echo "<font color='yellow'>Se ha ingresado correctamente <strong>".$nombre_valor.".</strong></font>";
}
//mysql_query($agregar_registros_sql1) or die(mysql_error());
      }
   }
}
?>