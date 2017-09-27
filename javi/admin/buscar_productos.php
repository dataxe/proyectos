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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<?php
$tipo_busqueda = "";
if($_SERVER['REQUEST_METHOD']=='POST') {
$tipo_busqueda = $_POST['tipo_busqueda'];
}
?>
<center>
<form action="" method="post">
<input name="palabra" id="foco" style="height:26"/>
<input type="submit" name="buscador" value="Buscar productos" />
</form>
</center>
<body>
<?php
$buscar = $_POST['palabra'];
if ($buscar <> NULL) {
$mostrar_datos_sql = "SELECT * FROM productos, marcas, proveedores, nomenclatura, paises, tipo WHERE productos.cod_marcas = marcas.cod_marcas AND 
productos.cod_proveedores = proveedores.cod_proveedores AND productos.cod_nomenclatura = nomenclatura.cod_nomenclatura AND 
productos.cod_paises = paises.cod_paises AND productos.cod_tipo = tipo.cod_tipo AND (cod_productos_var  like '$buscar%' OR nombre_productos  like '%$buscar%'
	 OR nombre_marcas  like '%$buscar%') order by nombre_productos DESC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$matriz_consulta = mysql_fetch_assoc($consulta);
$numero_datos = mysql_num_rows($consulta);
echo "<font color='yellow'><p align='left'><strong>Resultados para: ".$buscar."</strong></font><br>";

?>
<center>
<table>
<tr>
<td align="center"><strong>C&Oacute;DIGO</strong></td>
<td align="center"><strong>PRODUCTO</strong></td>
<td align="center"><strong>MARCA</strong></td>
<td align="center"><strong>PROV</strong></td>
<td align="center"><strong>TIPO</strong></td>
<td align="center"><strong>PAIS</strong></td>
<td align="center"><strong>CASILLA</strong></td>
<!--<td><strong>Und</strong></td>-->
<td align="center"><strong>UND</strong></td>
<td align="center"><strong>P.COMP</strong></td>
<td align="center"><strong>P.VENT</strong></td>
<td align="center"><strong>YTILIDAD</strong></td>
<!--<td><strong>Detalles</strong></td>-->
<td align="center"><strong>DESCRIPCI&Oacute;N</strong></td>
<td align="center"><strong>VENDER</strong></td>
</tr>
<?php do { ?>
<td><?php echo $matriz_consulta['cod_productos_var']; ?></td>
<td><?php echo $matriz_consulta['nombre_productos']; ?></td>
<td><?php echo $matriz_consulta['nombre_marcas']; ?></td>
<td><?php echo $matriz_consulta['nombre_proveedores']; ?></td>
<td><?php echo $matriz_consulta['nombre_tipo']; ?></td>
<td><?php echo $matriz_consulta['nombre_paises']; ?></td>
<td><?php echo $matriz_consulta['nombre_nomenclatura']; ?></td>
<!--<td><?php //echo $matriz_consulta['unidades']; ?></td>-->
<td align="right"><?php echo $matriz_consulta['unidades_faltantes']; ?></td>
<td align="right"><?php echo number_format($matriz_consulta['precio_compra']); ?></td>
<td align="right"><?php echo number_format($matriz_consulta['precio_venta']); ?></td>
<td align="right"><?php echo number_format($matriz_consulta['utilidad']); ?></td>
<!--<td><?php //echo $matriz_consulta['detalles']; ?></td>-->
<td align="right"><?php echo $matriz_consulta['descripcion']; ?></td>
<td  nowrap><a href="../modificar_eliminar/venta_productos.php?cod_productos=<?php echo $matriz_consulta['cod_productos_var']; ?>"><center><img src=../imagenes/vender.png alt="Vender"></a></td>
</tr>
<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
</table>
<?php } else {
}?>
<script>
window.onload = function() {
 document.getElementById("foco").focus();
}
</script>