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
include ("../registro_movimientos/registro_movimientos.php");

$pagina_actual = $_SERVER["PHP_SELF"];
$formulario_agregar = $_SERVER['PHP_SELF'];

if ((isset($_POST["insertar_datos"])) && ($_POST["insertar_datos"] == "formulario")) {
$separador_fecha =explode('/', $_POST['fecha']);
$dias = $separador_fecha[0];
$meses = $separador_fecha[1];
$anyos = $separador_fecha[2];

$conceptos = $_POST['conceptos'];
$comentarios = $_POST['comentarios'];
$nombre_ccosto = $_POST['nombre_ccosto'];
$fecha = $_POST['fecha'];
$fecha_invert = $anyos.'/'.$meses.'/'.$dias;
$fecha_mes = $meses.'/'.$anyos;
$fecha_anyo = $anyos;
$hora = $_POST['hora'];
$cuenta = $cuenta_actual;
$costo = $_POST['costo'];

$agreg_egresos = "INSERT INTO egresos (conceptos, comentarios, nombre_ccosto, fecha, fecha_invert, fecha_mes, fecha_anyo, 
hora, cuenta, costo)
VALUES ('$conceptos', '$comentarios', '$nombre_ccosto', '$fecha', '$fecha_invert', '$fecha_mes', '$fecha_anyo', '$hora', '$cuenta', '$costo')";
$resultado_egresos = mysql_query($agreg_egresos, $conectar) or die(mysql_error());

echo '<META HTTP-EQUIV="REFRESH" CONTENT="0; egresos_vendedor.php">';
}
?>
<center>
<td>
<a href="../admin/egresos_vendedor.php"><FONT color='yellow'>VER LISTA DE EGRESOS</FONT></a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<!--<a href="../admin/agregar_concepto_egresos.php"><FONT color='yellow'>AGREGAR NUEVO CONCEPTO DE EGRESO</FONT></a>-->
</td>
</center>
<center>
<br>
<form method="post" name="formulario" action="<?php echo $formulario_agregar; ?>">
<table width="90%">
<tr>
<td align="center"><strong>CONCEPTO</strong></td>
<td align="center"><strong>COSTO</strong></td>
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
<td align="center"><input style="font-size:24px" type="text" name="comentarios" value="" size="30"></td>
<td align="center"><input style="font-size:24px" type="text" name="fecha" value="<?php echo date("d/m/Y");?>" size="10"></td>
<input type="hidden" name="hora" value="<?php echo date("H:i:s");?>" size="30">
</tr>

<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td bordercolor="1"><input type="submit" id="boton1" value="Agregar"></td>
</tr>
</table>
<input type="hidden" name="insertar_datos" value="formulario">
</form>
</center>