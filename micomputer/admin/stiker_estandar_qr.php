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
/*
$verificar_pag = "SELECT * FROM pagina_actual WHERE cod_pagina_actual='1'";
$resultado_verificar_pag = mysql_query($verificar_pag, $conectar) or die(mysql_error());

$nombre_pagina_actual = 'STIKER ESTANDAR QR';
if ($resultado_verificar_pag['nombre_pagina_actual'] <> $nombre_pagina_actual) {
$actualizar_nombre_pagina_actual = sprintf("UPDATE pagina_actual SET nombre_pagina_actual=%s WHERE cod_pagina_actual='1'",
envio_valores_tipo_sql($nombre_pagina_actual, "text"), envio_valores_tipo_sql($_POST['cod_pagina_actual'], "text"));
$resultado_actualizacion_pagina_actual = mysql_query($actualizar_nombre_pagina_actual, $conectar) or die(mysql_error());
}
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<br>
<center>
<form method="post" id="table" name="formulario" action="">
<table align="center">
<td nowrap align="right">Fecha:</td>
<td bordercolor="0">
<select name="fechas_dia">
<?php $sql_consulta1="SELECT DISTINCT fechas_dia FROM productos ORDER BY fechas_dia DESC";
$resultado = mysql_query($sql_consulta1, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option value="<?php echo $contenedor['fechas_dia'] ?>"><?php echo $contenedor['fechas_dia'] ?></option>
<?php }?>
</select></td></td>
<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td bordercolor="1"><input type="submit" id="boton1" value="Consultar"></td>
</tr>
</table>
</form>
</center>
<?php
$buscar = $_POST['fechas_dia'];
if($buscar <> "") {
$mostrar_datos_sql = "SELECT * FROM productos WHERE fechas_dia  like '%$buscar%' order by fechas_dia DESC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$matriz_consulta = mysql_fetch_assoc($consulta);
}
if($buscar <> NULL) {
echo "<font color='yellow'><p align='left'><strong>Fecha: ".$buscar."</strong></font><br>";
}
?>
<center>
<table id="table" id="table">
<tr>
<td><div align="center" ><strong>Cod</strong></div></td>
<td><div align="center" ><strong>Nombre</strong></div></td>
<!--<td><div align="center" ><strong>Marca</strong></div></td>
<td><div align="center" ><strong>Prov</strong></div></td>-->
<!--<td><div align="center" ><strong>Und</strong></div></td>-->
<td><div align="center" ><strong>U.Inv</strong></div></td>
<td><div align="center" ><strong>P.compra</strong></div></td>
<td><div align="center" ><strong>P.venta</strong></div></td>
<!--<td><div align="center" ><strong>Detalles</strong></div></td>-->
<td><div align="center" ><strong>Descripcion</strong></div></td>  
<!--<td><div align="center" ><strong>P.Letra</strong></div></td>-->
<td><div align="center" ><strong>Factura</strong></div></td> 
<td><div align="center" ><strong>Generar</strong></div></td>   
</tr>
<?php do { ?>
<tr>
<td><?php echo $matriz_consulta['cod_productos_var']; ?></td>
<td><?php echo $matriz_consulta['nombre_productos']; ?></td>
<!--<td><?php //echo $matriz_consulta['nombre_marcas']; ?></td>
<td><?php //echo $matriz_consulta['cod_proveedores']; ?></td>-->
<!--<td><?php //echo $matriz_consulta['unidades']; ?></td>-->
<td align="right"><?php echo $matriz_consulta['unidades_faltantes']; ?></td>
<td align="right"><?php echo number_format($matriz_consulta['precio_compra']); ?></td>
<td align="right"><?php echo number_format($matriz_consulta['precio_venta']); ?></td>
<!--<td><?php //echo $matriz_consulta['detalles']; ?></td>-->
<td align="right"><?php echo $matriz_consulta['descripcion']; ?></td>
<!--<td><?php //echo $matriz_consulta['codificacion']; ?></td>-->
<td align="right"><?php echo $matriz_consulta['numero_factura']; ?></td>
<td ><a href="estandar_qr/generar_qr.php?cod_productos=<?php echo $matriz_consulta['cod_productos_var'] ?>&unidades=<?php echo $matriz_consulta['unidades_faltantes'];?>"target="_blank"><center><img src=../imagenes/sticker_qr.jpg alt="Actualizar"></a></td>
</tr>
<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
</table>