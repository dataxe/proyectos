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
//---------------------------------------------------------------//
//-------------------INICIO MODULO DE SEGURIDAD------------------//
//---------------------------------------------------------------//
include ("../seguridad/seguridad_diseno_plantillas.php");
$nivel_acceso = '3';
if ($seguridad_acceso['cod_seguridad'] <> $nivel_acceso) {
header("Location:../admin/acceso_denegado.php");
}
//---------------------------------------------------------------//
//----------------------FIN MODULO DE SEGURIDAD------------------//
//---------------------------------------------------------------//
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");

$fecha = addslashes($_GET['fecha']);

$mostrar_datos_sql = "SELECT * FROM entrada_personal WHERE anyo = '$fecha' order by anyo DESC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);

$datos_sql_salida = "SELECT SUM(costo) AS costo FROM salida_personal WHERE anyo = '$fecha' order by anyo DESC";
$consulta_dat_salida = mysql_query($datos_sql_salida, $conectar) or die(mysql_error());
$data_costo_salida = mysql_fetch_assoc($consulta_dat_salida);

$datos_sql_entrada = "SELECT SUM(costo) AS costo FROM entrada_personal WHERE anyo = '$fecha' order by anyo DESC";
$consulta_dat_entrada = mysql_query($datos_sql_entrada, $conectar) or die(mysql_error());
$data_costo_entrada = mysql_fetch_assoc($consulta_dat_entrada);

$total_salida_personal = $data_costo_salida['costo'];
$total_entrada_personal = $data_costo_entrada['costo'];

$resta = $total_entrada_personal - $total_salida_personal;

$pagina = $_SERVER['PHP_SELF'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<center>
<td><strong><font color='yellow' size='+2'>ENTRADAS PERSONALES: </font></strong></td><br><br>
<td>
<a href="../admin/agregar_entrada_personal.php?pagina=<?php echo $pagina?>"><FONT color='white'>AGREGAR NUEVA ENTRADA PERSONAL</FONT></a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="../admin/agregar_concepto_egresos.php"><FONT color='white'>AGREGAR NUEVO CONCEPTO</FONT></a>
</td>
<?php include_once("menu_entradas_personales.php");?>
</center>
<br>
<center>
<form method="GET" name="formulario" action="<?php echo $formulario_agregar; ?>">
<table>
<tr>
<td align="center"><strong>ANUAL</strong></td>
<td><select name="fecha" require autofocus>
<?php $sql_consulta = "SELECT anyo FROM entrada_personal GROUP BY anyo DESC";
$resultado = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while ($contenedor = mysql_fetch_assoc($resultado)) {?>
<option style="font-size:20px" value="<?php echo $contenedor['anyo'] ?>"><?php echo $contenedor['anyo']?></option>
<?php }?></select></td>
<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td bordercolor="1"><input type="submit" id="boton1" value="Ver"></td>
</tr>
</table>
</form>
<?php
if (isset($_GET['fecha'])) {
echo "<font color ='yellow' size='+2'>RESULTADOS PARA: ".$fecha."</font>";
?>
<table>
<tr>
<td align="center"><strong><font color ='yellow' size='+1'>ENTRADA</font></strong></td>
<td align="center"><strong><font color ='yellow' size='+1'>SALIDA</font></strong></td>
<td align="center"><strong><font color ='yellow' size='+1'>RESULTADO</font></strong></td>
</tr>
<tr>
<td align="center"><font color ='yellow' size='+1'><?php echo number_format($total_entrada_personal, 0, ",", ".") ?></font></td>
<td align="center"><font color ='yellow' size='+1'><?php echo number_format($total_salida_personal, 0, ",", ".") ?></font></td>
<td align="center"><font color ='yellow' size='+1'><?php echo number_format($resta, 0, ",", ".") ?></font></td>
</tr>
<br>
</table>
<center>
<table width="85%">
<tr>
<td align="center"><strong>ELM</strong></td>
<td align="center"><strong>CONCEPTO</strong></td>
<td align="center"><strong>COSTO</strong></td>
<td align="center"><strong>COMENTARIO</strong></td>
<td align="center"><strong>CENTRO COSTO</strong></td>
<td align="center"><strong>FECHA</strong></td>
<td align="center"><strong>EDIT</strong></td>
</tr>
<?php do { 
$cod_entrada_personal = $datos['cod_entrada_personal'];
?>
<tr>
<td ><a href="../modificar_eliminar/eliminar_entrada_personal.php?cod_entrada_personal=<?php echo $cod_entrada_personal?>&fecha=<?php echo $fecha?>&pagina=<?php echo $pagina?>"><center><img src=../imagenes/eliminar.png alt="eliminar"></center></a></td>
<td ><?php echo $datos['conceptos']; ?></td>
<td align="right"><?php echo number_format($datos['costo'], 0, ",", "."); ?></td>
<td align="justify"><?php echo $datos['comentarios']; ?></td>
<td align="center"><?php echo $datos['nombre_ccosto']; ?></td>
<td align="center"><?php echo $datos['fecha_dmy']; ?></td>
<td ><a href="../modificar_eliminar/modificar_entrada_personal.php?cod_entrada_personal=<?php echo $cod_entrada_personal?>&fecha=<?php echo $fecha?>&pagina=<?php echo $pagina?>"><center><img src=../imagenes/actualizar.png alt="Actualizar"></center></a></td>
</tr>
<?php } while ($datos = mysql_fetch_assoc($consulta)); ?>
</table>
<?php
} else {
}
?>
</body>
</html>