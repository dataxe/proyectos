<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");
if (verificar_usuario()){
//print "Bienvenido (a), ".$_SESSION['usuario'].", al sistema.";
  } else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
include ("../seguridad/seguridad_diseno_plantillas.php");
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");
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
<form method="post" name="formulario" action="stiker_productos_fecha.php">
<table align="center">
<font color='white'>CREAR CODIGO DE BARRAS: </font> <input type="text" id="foco" name="fechas_dia" value="">
<input type="submit" value="Consultar">
</tr>
</table>
</form>
</center>
<?php
$buscar = addslashes($_POST['fechas_dia']);

if($buscar <> "") {
$mostrar_datos_sql = "SELECT * FROM productos WHERE nombre_productos like '$buscar%' OR cod_productos_var = '$buscar' OR numero_factura = '$buscar' OR fechas_anyo = '$buscar'";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);
}

if($buscar <> NULL) {
echo "<center><font color='yellow'>Resultados: ".$buscar."</font></center><br>";
}
?>
<center>
<table width="80%">
<tr>
<td align="center">C&Oacute;DIGO</td>
<td align="center">PRODUCTO</td>
<!--<td align="center">Und</td>-->
<td align="center">UND</td>
<td align="center">P.COMPRA</td>
<td align="center">P.VENTA</td>
<!--<td align="center">Detalles</td>-->
<td align="center">DESCRIPCION</td>  
<!--<td align="center">P.Letra</td>-->
<td align="center">FACTURA</td>
<td align="center">FECHA</td>
<td align="center">STIKER</td> 
</tr>
<?php do { 
$cod_productos_var = $datos['cod_productos_var'];
?>
<tr>
<td align="center"><?php echo $cod_productos_var; ?></td>
<td align="center"><?php echo $datos['nombre_productos']; ?></td>
<!--<td align="center"><?php //echo $datos['unidades']; ?></td>-->
<td align="right"><?php echo $datos['unidades_faltantes']; ?></td>
<td align="right"><?php echo $datos['precio_costo']; ?></td>
<td align="right"><?php echo $datos['precio_venta']; ?></td>
<!--<td align="center"><?php //echo $datos['detalles']; ?></td>-->
<td align="right"><?php echo $datos['descripcion']; ?></td>
<!--<td align="center"><?php //echo $datos['codificacion']; ?></td>-->
<td align="right"><?php echo $datos['numero_factura']; ?></td>
<td align="right"><?php echo $datos['fechas_anyo']; ?></td>
<td align="center"><a href="../admin/imprimir_stiker_unico_con_barras.php?cod_productos_var=<?php echo $cod_productos_var ?> "target="_blank"><img src=../imagenes/sticker_estandar_128_pdf_con_barra.png alt="Actualizar"></a></td>
</tr>
<?php } while ($datos = mysql_fetch_assoc($consulta)); ?>
</table>
<script>
window.onload = function() {
document.getElementById("foco").focus();
}
</script>