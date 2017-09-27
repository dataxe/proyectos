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
//require_once("busqueda_inmediata_base_caja.php");

$pagina_actual = $_SERVER["PHP_SELF"];
$formulario_agregar = $_SERVER['PHP_SELF'];
?>
<script>
window.onload = function() {
  document.getElementById("foco").focus();
}
</script>
<br>
<center>
<td><a href="../admin/caja_base.php"><font color='yellow' size= "+3">REGRESAR A CAJA</font></a></td><br><br>
<form method="post" id="table" name="formulario" action="<?php echo $formulario_agregar; ?>">
<table align="center" id="table">
<tr>
<td align="center"><strong>NOMBRE CAJA</strong></td>
<td align="center"><strong>BASE CAJA</strong></td>
<tr>
<td><input id="foco" type="text" name="nombre_base_caja" value="" size="30" required autofocus></td>
<td><input type="text" name="valor_caja" value="" size="30" required autofocus></td>
</tr>
<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td bordercolor="1"><input type="submit" id="boton1" value="Agregar"></td>
</tr>
</table>
<input type="hidden" name="insertar_datos" value="formulario">
</form>
</body>
</html>
<?php
$cero = '0';

if (isset($_POST["nombre_base_caja"])) {
   $nombre_base_caja = $_POST["nombre_base_caja"];
   $cod_base_caja = $_POST["cod_base_caja"];
   
   // Hay campos en blanco
if($nombre_base_caja==NULL) {
  echo "<embed SRC='../sonidos/alarma.mp3' hidden='true' autostart='true' loop='3'></embed>";
echo "<font color='yellow'><br><center><img src=../imagenes/advertencia.gif alt='Advertencia'><strong>Ha dejado el campo nombre vacio.</strong><img src=../imagenes/advertencia.gif alt='Advertencia'></font>";
} else {
         // Comprobamos si el nombre_base_caja ya existe
$verificar_nombre_base_caja = mysql_query("SELECT nombre_base_caja FROM base_caja WHERE nombre_base_caja = '$nombre_base_caja'");
$existencia_nombre_base_caja = mysql_num_rows($verificar_nombre_base_caja);
         
if ($existencia_nombre_base_caja > 0) {
echo "<embed SRC='../sonidos/alarma.mp3' hidden='true' autostart='true' loop='3'></embed>";
echo "<font color='yellow'><br><center><img src=../imagenes/advertencia.gif alt='Advertencia'>La caja <strong>".$nombre_base_caja." </strong>ya existe.<img src=../imagenes/advertencia.gif alt='Advertencia'></font>";
} else {
if ((isset($_POST["insertar_datos"])) && ($_POST["insertar_datos"] == "formulario")) {
$agregar_registros_sql1 = sprintf("INSERT INTO base_caja (valor_caja, nombre_base_caja, total_caja, total_ventas, total_caja_com, residuo) VALUES (%s, %s, %s, %s, %s, %s)",
envio_valores_tipo_sql($_POST['valor_caja'], "text"),
envio_valores_tipo_sql($_POST['nombre_base_caja'], "text"),
envio_valores_tipo_sql($_POST['valor_caja'], "text"),
envio_valores_tipo_sql($cero, "text"),
envio_valores_tipo_sql($cero, "text"),
envio_valores_tipo_sql($cero, "text"));
					   
$resultado_sql1 = mysql_query($agregar_registros_sql1, $conectar) or die(mysql_error());
echo '<META HTTP-EQUIV="REFRESH" CONTENT="1; caja_base.php">';
echo "<font color='yellow'>Se ha ingresado correctamente la caja <strong> ".$nombre_base_caja.".</strong></font>";
}
//mysql_query($agregar_registros_sql1) or die(mysql_error());
      }
   }
}
?>