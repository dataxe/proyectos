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

$formulario_agregar = $_SERVER['PHP_SELF'];
$pagina_get = $_GET['pagina'];

if ((isset($_POST["insertar_datos"])) && ($_POST["insertar_datos"] == "formulario")) {
$conceptos = mysql_escape_string($_POST['conceptos']);
$comentarios = mysql_escape_string($_POST['comentarios']);
$nombre_ccosto = mysql_escape_string($_POST['nombre_ccosto']);
$fecha_dmy = mysql_escape_string($_POST['fecha']);
$costo = mysql_escape_string($_POST['costo']);
$hora = date("H:i:s");

$separador_fecha =explode('/', $_POST['fecha']);
$dias = $separador_fecha[0];
$meses = $separador_fecha[1];
$anyos = $separador_fecha[2];
$fecha_mes = $meses.'/'.$anyos;
$anyo = $anyos;
$fecha_invert = $anyos.'/'.$meses.'/'.$dias;
$fecha_seg = strtotime($fecha_invert);

$ip = $_SERVER['REMOTE_ADDR'];
$pagina = $_POST['pagina'];

$agregar_registros_sql1 = sprintf("INSERT INTO egresos (conceptos, comentarios, nombre_ccosto, fecha, fecha_seg, fecha_anyo, fecha_invert, fecha_mes, anyo, 
hora, ip, cuenta, costo) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
envio_valores_tipo_sql($conceptos, "text"),
envio_valores_tipo_sql($comentarios, "text"),
envio_valores_tipo_sql($nombre_ccosto, "text"),
envio_valores_tipo_sql($fecha_seg, "text"),
envio_valores_tipo_sql($fecha_seg, "text"),
envio_valores_tipo_sql($fecha_dmy, "text"),
envio_valores_tipo_sql($fecha_invert, "text"),
envio_valores_tipo_sql($fecha_mes, "text"),
envio_valores_tipo_sql($anyo, "text"),
envio_valores_tipo_sql($hora, "text"),
envio_valores_tipo_sql($ip, "text"),
envio_valores_tipo_sql($cuenta_actual, "text"),
envio_valores_tipo_sql($costo, "text"));
$resultado_sql1 = mysql_query($agregar_registros_sql1, $conectar) or die(mysql_error());
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0;URL=<?php echo $pagina?>">
<?php
}
?>
<br><br>
<center>
<td>
<a href="../admin/egresos.php"><FONT color='white'>VER LISTA DE EGRESOS</FONT></a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="../admin/agregar_concepto_egresos.php"><FONT color='white'>AGREGAR NUEVO CONCEPTO DE EGRESO</FONT></a>
</td>
</center>
<center>
<br>
<form method="POST" name="formulario" action="<?php echo $formulario_agregar; ?>">
<table width="90%">
<tr>
<td align="center"><strong>CONCEPTO</strong></td>
<td align="center"><strong>COSTO</strong></td>
<td align="center"><strong>CENTRO COSTO</strong></td>
<td align="center"><strong>COMENTARIO</strong></td>
<td align="center"><strong>FECHA</strong></td>
<tr>
<td align="center"><select name="conceptos" require autofocus>
<?php $sql_consulta="SELECT * FROM gastos_tabla ORDER BY cod_gastos_tabla ASC";
$resultado = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option style="font-size:24px" value="<?php echo $contenedor['conceptos'] ?>"><?php echo $contenedor['conceptos']?></option>
<?php }?></select></td>

<td align="center"><input style="font-size:24px" type="text" name="costo" value="" size="10" require autofocus></td>

<td align="center"><select name="nombre_ccosto" require autofocus>
<?php $sql_consulta="SELECT * FROM centro_costo ORDER BY cod_ccosto ASC";
$resultado = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option style="font-size:24px" value="<?php echo $contenedor['nombre_ccosto'] ?>"><?php echo $contenedor['nombre_ccosto']?></option>
<?php }?></select></td>

<td align="center"><input style="font-size:24px" type="text" name="comentarios" value="" size="30"></td>

<td align="center"><input style="font-size:24px" type="text" name="fecha" value="<?php echo date("d/m/Y");?>" size="10"></td>
<input type="hidden" name="pagina" value="<?php echo $pagina_get?>" size="30">
</tr>
</table>
<br>
<td bordercolor="1"><input type="submit" id="boton1" value="REGISTRAR"></td>
<input type="hidden" name="insertar_datos" value="formulario">
</form>
</center>