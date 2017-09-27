<?php
require_once('../conexiones/conexione.php'); 
mysql_select_db($base_datos, $conectar); 
?>
<head>
<title>Orden</title>
<script language="javascript" src="isiAJAX.js"></script>
<SCRIPT LANGUAGE="JavaScript">
var last;
function Focus(elemento, valor) {
$(elemento).className = 'inputon';
last = valor;
}
function Blur(elemento, valor, id) {
$(elemento).className = 'inputoff';
var aleatorio=Math.random();
if (last != valor)
myajax.Link('save.php?valor='+valor+'&id='+id+'&aleatorio='+aleatorio);
}
</SCRIPT>
<link rel="stylesheet" type="text/css" href="../estilo_css/azul_verdoso.css">
</head>
<body onLoad="myajax = new isiAJAX();">
<form name="form1" id="form1" action="#" method="post" style="margin:0;">  
<?php
$sql = "SELECT * FROM temporal";
$consulta = mysql_query($sql, $conectar) or die(mysql_error());
?>
<table width="1100" border="1">
<tr>
<th>cod_productos</th>
<th>nombre_productos</th>
<th>unidades_vendidas</th>
<!--<th>precio_compra</th>
<th>vlr_total_compra</th>-->
<th>precio_venta</th>
<th>vlr_total_venta</th>
</tr>
<?php
while ($datos = mysql_fetch_assoc($consulta)) {
$cod_productos=$datos['cod_productos'];
$nombre_productos=$datos['nombre_productos'];
$unidades_vendidas=$datos['unidades_vendidas'];
//$precio_compra=$datos['precio_compra'];
//$vlr_total_compra=$datos['vlr_total_compra'];
$precio_venta=$datos['precio_venta'];
$vlr_total_venta=$datos['vlr_total_venta'];
?>
<tr>
<td><?php echo $cod_productos;?></td>
<td><?php echo $nombre_productos;?></td>
<td><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, <?php echo $cod_productos;?>)" class="inputoff" id="unidades_vendidas<?php echo $cod_productos;?>" value="<?php echo $unidades_vendidas;?>" maxlength="3" size="5"/></td>
<!--<td><?php //echo $precio_compra;?></td>
<td><?php //echo $vlr_total_compra;?></td>-->
<td><?php echo $precio_venta;?></td>
<td><?php echo $vlr_total_venta;?></td>
</tr>
<?php } ?>
</table>
</form>
</body>
</html>
