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

$formulario_agregar = $_SERVER['PHP_SELF'];
$pagina_get = addslashes($_GET['pagina']);

if ((isset($_POST["insertar_datos"])) && ($_POST["insertar_datos"] == "formulario")) {
$conceptos = addslashes($_POST['conceptos']);
$comentarios = addslashes($_POST['comentarios']);
$nombre_ccosto = addslashes($_POST['nombre_ccosto']);
$fecha_dmy = addslashes($_POST['fecha']);
$costo = addslashes($_POST['costo']);

$separador_fecha =explode('/', $_POST['fecha']);
$dias = $separador_fecha[0];
$meses = $separador_fecha[1];
$anyos = $separador_fecha[2];
$fecha_mes = $meses.'/'.$anyos;
$anyo = $anyos;
$fecha_invert = $anyos.'/'.$meses.'/'.$dias;
$fecha_seg = strtotime($fecha_invert);
$fecha_time = time();
$cuenta = $cuenta_actual;

$ip = $_SERVER['REMOTE_ADDR'];
$pagina = addslashes($_POST['pagina']);

$sql_data = ("INSERT INTO salida_personal (conceptos, costo, comentarios, nombre_ccosto, fecha_dmy, fecha_mes, anyo, fecha_seg, fecha_time, ip, cuenta) 
VALUES ('$conceptos', '$costo', '$comentarios', '$nombre_ccosto', '$fecha_dmy', '$fecha_mes', '$anyo', '$fecha_seg', '$fecha_time', '$ip', '$cuenta')");
$exec_data = mysql_query($sql_data, $conectar) or die(mysql_error());
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0;URL=<?php echo $pagina?>">
<?php
}
?>
<br><br>
<center>
<td>
<a href="../admin/salida_personal.php"><FONT color='white'>VER LISTA DE SALIDAS</FONT></a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="../admin/agregar_concepto_egresos.php"><FONT color='white'>AGREGAR NUEVO CONCEPTO</FONT></a>
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
<?php $sql_consulta = "SELECT * FROM gastos_tabla ORDER BY cod_gastos_tabla ASC";
$resultado = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while ($contenedor = mysql_fetch_assoc($resultado)) {?>
<option style="font-size:24px" value="<?php echo $contenedor['conceptos'] ?>"><?php echo $contenedor['conceptos']?></option>
<?php }?></select></td>

<td align="center"><input style="font-size:24px" type="text" name="costo" value="" size="10" require autofocus></td>

<td align="center"><select name="nombre_ccosto" require autofocus>
<?php $sql_consulta = "SELECT * FROM centro_costo ORDER BY cod_ccosto ASC";
$resultado = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while ($contenedor = mysql_fetch_assoc($resultado)) {?>
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