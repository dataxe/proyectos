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

$pagina_actual = $_SERVER["PHP_SELF"];
$formulario_agregar = $_SERVER['PHP_SELF'];
?>
<br><br>
<center>
<td><strong><a href="../admin/marcas.php"><font color='yellow' size='5px'><strong>REGRESAR</font></a></strong></td><br><br>
<td><strong><font color='yellow' size='5px'>REGISTRAR NUEVA MARCA</font></strong></td><br><br>

<form method="post" name="formulario" action="<?php echo $formulario_agregar; ?>">
<table align="center">
<tr>
<td align="center"><strong>NOMBRE MARCA</strong></td>
<tr>
<td><input type="text" name="nombre_marcas" value="" size="30" required autofocus></td>
</tr>
<br>
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
if (isset($_POST["nombre_marcas"])) {

$nombre_marcas0 = $_POST["nombre_marcas"];
$nombre_marcas1 = preg_replace("/,/", '.', $nombre_marcas0);
$nombre_marcas2 = preg_replace("/'/", ' .', $nombre_marcas1);
$nombre_marcas = strtoupper(preg_replace('/"/', ' .', $nombre_marcas2));
   
   // Hay campos en blanco
if($nombre_marcas==NULL) {
echo "<embed SRC='../sonidos/alarma.mp3' hidden='true' autostart='true' loop='3'></embed>";
echo "<font color='yellow'><br><center><img src=../imagenes/advertencia.gif alt='Advertencia'><strong>
Ha dejado el campo marca vacio.</strong><img src=../imagenes/advertencia.gif alt='Advertencia'></font>";
} else {
         // Comprobamos si el nombre_marcas ya existe
$verificar_nombre_marcas = mysql_query("SELECT nombre_marcas FROM marcas WHERE nombre_marcas = '$nombre_marcas'");
$existencia_nombre_marcas = mysql_num_rows($verificar_nombre_marcas);
         
if ($existencia_nombre_marcas > 0) {
echo "<embed SRC='../sonidos/alarma.mp3' hidden='true' autostart='true' loop='3'></embed>";
echo "<font color='yellow'><br><center><img src=../imagenes/advertencia.gif alt='Advertencia'>
La marca <strong>".$nombre_marcas." </strong>ya existe, verifique en la lista de marcas.<img src=../imagenes/advertencia.gif alt='Advertencia'></font>";
} else {
if ((isset($_POST["insertar_datos"])) && ($_POST["insertar_datos"] == "formulario")) {
$agregar_registros_sql1 = sprintf("INSERT INTO marcas (nombre_marcas) VALUES (%s)",
envio_valores_tipo_sql($nombre_marcas, "text"));
					   
$resultado_sql1 = mysql_query($agregar_registros_sql1, $conectar) or die(mysql_error());
echo '<META HTTP-EQUIV="REFRESH" CONTENT="0; marcas.php">';
echo "<font color='yellow'>Se ha ingresado correctamente la marca <strong>".$nombre_marcas.".</strong></font>";
}
//mysql_query($agregar_registros_sql1) or die(mysql_error());
      }
   }
}
?>