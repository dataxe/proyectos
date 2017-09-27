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

$pagina_actual = $_SERVER["PHP_SELF"];
$formulario_agregar = $_SERVER['PHP_SELF'];

$conceptos = mysql_escape_string(strtoupper($_POST['conceptos']));

if ((isset($_POST["insertar_datos"])) && ($_POST["insertar_datos"] == "formulario")) {

$agregar_registros_sql1 = sprintf("INSERT INTO gastos_tabla (conceptos) VALUES (%s)",
envio_valores_tipo_sql($conceptos, "text"));
             
$resultado_sql1 = mysql_query($agregar_registros_sql1, $conectar) or die(mysql_error());
echo '<META HTTP-EQUIV="REFRESH" CONTENT="0.1; agregar_egresos.php">';
}
?>
<br><br>
<center>
<td>
<a href="../admin/egresos.php"><FONT color='white'>VER LISTA DE EGRESOS</FONT></a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="../admin/agregar_egresos.php"><FONT color='white'>AGREGAR NUEVO EGRESO</FONT></a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="../admin/ver_lista_conceptos_egresos.php"><FONT color='white'>VER LISTA DE CONCEPTOS</FONT></a>
</td>
</center>
<br>
<center>
<form method="post" name="formulario" action="<?php echo $formulario_agregar; ?>">
<table width="50%">
<tr valign="baseline">
<td nowrap align="right">CONCEPTO:</td>
<td><input style="font-size:24px" type="text" name="conceptos" value="" size="60" require autofocus></td>
</tr>
<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td bordercolor="1"><input type="submit" id="boton1" value="AGREGAR"></td>
</tr>
</table>
<input type="hidden" name="insertar_datos" value="formulario">
</form>
</center>