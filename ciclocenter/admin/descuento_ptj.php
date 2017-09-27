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

$pagina_actual = $_SERVER["PHP_SELF"];
$formulario_agregar = $_SERVER['PHP_SELF'];

$mostrar_datos_sql = "SELECT * FROM descuento_ptj order by nombre_descuento_ptj DESC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$matriz_consulta = mysql_fetch_assoc($consulta);
?>
<br><br>
<center>
<td><strong><font color='yellow' size='+2'>DESCUENTOS %</font></strong></td><br><br>
<table width="30%">
<tr>
<td align="center"><strong>ELM</strong></td>
<td align="center"><strong>DESCUENTO %</strong></td>
</tr>
<?php do { ?>
<tr>
<td ><a href="../modificar_eliminar/eliminar_descuento_ptj.php?cod_descuento_ptj=<?php echo $matriz_consulta['cod_descuento_ptj']; ?>"><center><img src=../imagenes/eliminar.png alt="eliminar"></center></a></td>
<td align="center"><?php echo $matriz_consulta['nombre_descuento_ptj']; ?></span></td>
</tr>
<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
</table>
<br>
<form method="post" name="formulario" action="<?php echo $formulario_agregar; ?>">
<table align="center">
<tr>
<td align="center"><strong>DESCUENTO %</strong></td>
<tr>
<td><input type="text" name="nombre_descuento_ptj" value="" size="30" required autofocus></td>
</tr>
<tr valign="baseline">
<td  align="center" bordercolor="1"><input type="submit" id="boton1" value="Agregar"></td>
</tr>
</table>
<input type="hidden" name="insertar_datos" value="formulario">
</form>
</body>
</html>
<?php mysql_free_result($consulta);

if (isset($_POST["nombre_descuento_ptj"])) {
$nombre_descuento_ptj = $_POST["nombre_descuento_ptj"];
$abr_ptj = '%';

$agregar_reg = "INSERT INTO descuento_ptj (nombre_descuento_ptj, abr_ptj)
VALUES ('$nombre_descuento_ptj', '$abr_ptj')";
$resultado = mysql_query($agregar_reg, $conectar) or die(mysql_error());

echo "<meta http-equiv=refresh content=0.1;URL=../admin/descuento_ptj.php>";

}
?>
