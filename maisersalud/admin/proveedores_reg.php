<?php error_reporting(E_ALL ^ E_NOTICE);
require_once('../conexiones/conexione.php'); 
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

$formulario_agregar = $_SERVER['PHP_SELF'];
?>
<center>
<br><br>
<td><strong><a href="../admin/proveedores.php"><font color='yellow' size='5px'><strong>REGRESAR</font></a></strong></td><br><br>
<td><strong><font color='yellow' size='5px'>REGISTRAR NUEVO PROVEEDOR</font></strong></td><br><br><br>

<form method="post" name="formulario" action="<?php echo $formulario_agregar; ?>">
<table align="center">
<tr>
<td align="center"><strong>NOMBRE</strong></td>
<td align="center"><strong>NIT</strong></td>
<td align="center"><strong>CORREO</strong></td>
<td align="center"><strong>TELEFONO</strong></td>
<td align="center"><strong>CIUDAD</strong></td>
<td align="center"><strong>DIRECCION</strong></td>
<tr>
<td align="center"><input type="text" name="nombre_proveedores" value="" size="30"required autofocus></td>
<td align="center"><input type="text" name="identificacion_proveedores" value="" size="15"></td>
<td align="center"><input type="text" name="correo_proveedores" value="" size="15"></td>
<td align="center"><input type="text" name="telefono_proveedores" value="" size="15"></td>
<td align="center"><input type="text" name="ciudad_proveedores" value="" size="15"></td>
<td align="center"><input type="text" name="direccion_proveedores" value="" size="15"></td>
</tr>
</table>
<br>
<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td bordercolor="1"><input type="submit" id="boton1" value="REGISTRAR"></td>
</tr>
<input type="hidden" name="insertar_datos" value="formulario">
</form>
</center>
</body>
</html>
<?php
if (isset($_POST["nombre_proveedores"])) {
$nombre_proveedores0 = $_POST["nombre_proveedores"];
$nombre_proveedores1 = preg_replace("/,/", '.', $nombre_proveedores0);
$nombre_proveedores2 = preg_replace("/'/", ' .', $nombre_proveedores1);
$nombre_proveedores = strtoupper(preg_replace('/"/', ' .', $nombre_proveedores2));

$identificacion_proveedores = $_POST["identificacion_proveedores"];

$correo_proveedores0 = $_POST["correo_proveedores"];
$correo_proveedores1 = preg_replace("/,/", '.', $correo_proveedores0);
$correo_proveedores2 = preg_replace("/'/", ' .', $correo_proveedores1);
$correo_proveedores = strtoupper(preg_replace('/"/', ' .', $correo_proveedores2));

$telefono_proveedores = $_POST["telefono_proveedores"];

$ciudad_proveedores0 = $_POST["ciudad_proveedores"];
$ciudad_proveedores1 = preg_replace("/,/", '.', $ciudad_proveedores0);
$ciudad_proveedores2 = preg_replace("/'/", ' .', $ciudad_proveedores1);
$ciudad_proveedores = strtoupper(preg_replace('/"/', ' .', $ciudad_proveedores2));

$direccion_proveedores0 = $_POST["direccion_proveedores"];
$direccion_proveedores1 = preg_replace("/,/", '.', $direccion_proveedores0);
$direccion_proveedores2 = preg_replace("/'/", ' .', $direccion_proveedores1);
$direccion_proveedores = strtoupper(preg_replace('/"/', ' .', $direccion_proveedores2));
   
   // Hay campos en blanco
if($nombre_proveedores==NULL) {
  echo "<embed SRC='../sonidos/alarma.mp3' hidden='true' autostart='true' loop='3'></embed>";
echo "<font color='yellow'><br><center><img src=../imagenes/advertencia.gif alt='Advertencia'><strong>No ha colocado el nombre del proveedor.</strong><img src=../imagenes/advertencia.gif alt='Advertencia'></font>";
} else {
         // Comprobamos si el nombre_proveedores ya existe
$verificar_nombre_proveedores = mysql_query("SELECT nombre_proveedores FROM proveedores WHERE nombre_proveedores = '$nombre_proveedores'");
$existencia_nombre_proveedores = mysql_num_rows($verificar_nombre_proveedores);
         
if ($existencia_nombre_proveedores > 0) {
echo "<embed SRC='../sonidos/alarma.mp3' hidden='true' autostart='true' loop='3'></embed>";
echo "<font color='yellow'><br><center><img src=../imagenes/advertencia.gif alt='Advertencia'>El proveedor <strong>".$nombre_proveedores." </strong>ya existe, verifique en la lista de proveedores.<img src=../imagenes/advertencia.gif alt='Advertencia'></font>";
} else {

if ((isset($_POST["insertar_datos"])) && ($_POST["insertar_datos"] == "formulario")) {
$agregar_registros_sql1 = sprintf("INSERT INTO proveedores (nombre_proveedores, identificacion_proveedores, correo_proveedores, 
  telefono_proveedores, ciudad_proveedores, direccion_proveedores) VALUES (%s, %s, %s, %s, %s, %s)",
					   envio_valores_tipo_sql($nombre_proveedores, "text"),
             envio_valores_tipo_sql($identificacion_proveedores, "text"),
					   envio_valores_tipo_sql($correo_proveedores, "text"),
					   envio_valores_tipo_sql($telefono_proveedores, "text"),
					   envio_valores_tipo_sql($ciudad_proveedores, "text"),
     				 envio_valores_tipo_sql($direccion_proveedores, "text"));
					   
$resultado_sql1 = mysql_query($agregar_registros_sql1, $conectar) or die(mysql_error());
echo '<META HTTP-EQUIV="REFRESH" CONTENT="0; proveedores.php">';
echo "<font color='yellow'>Se ha ingresado correctamente la marca <strong>".$nombre_proveedores.".</strong></font>";
}
//mysql_query($agregar_registros_sql1) or die(mysql_error());
      }
   }
}
?>
<script>
window.onload = function() {
document.getElementById("foco").focus();
}
</script>