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
?>
<center>
<br>
<td><a href="../admin/ver_factura_ventas.php"><font size='4' color='yellow'>REGRESAR</font></a></td>
<br>
</center>
<?php
$buscar = addslashes($_POST['buscar']);
$pagina ="buscar_facturas_fecha";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<title>ALMACEN</title>
<script language="javascript" src="isiAJAX.js"></script>
<script language="javascript">
var last;
function Focus(elemento, valor) {
$(elemento).className = 'inputon';
last = valor;
}
function Blur(elemento, valor, campo, id) {
$(elemento).className = 'inputoff';
if (last != valor)
myajax.Link('guardar_devoluciones_facturas.php?valor='+valor+'&campo='+campo+'&id='+id);
}
</script>

</head>
<body onLoad="myajax = new isiAJAX();">
<body>
<?php
$sql = "SELECT * FROM ventas, clientes WHERE (ventas.cod_clientes = clientes.cod_clientes) AND (nombres LIKE '$buscar%' OR cod_productos = '$buscar' OR nombre_productos LIKE '%$buscar%'
OR cod_factura LIKE '$buscar%' OR fecha_anyo LIKE '$buscar%') ORDER BY fecha DESC";
$consulta = mysql_query($sql, $conectar) or die(mysql_error());
$tota_reg = mysql_num_rows($consulta);

 if ($tota_reg <> 0) {
?>
<center>
<br>
<form method="post" name="formulario" action="">
<table align="center">
<input name="buscar" required autofocus>
<input type="submit" name="buscador" value="BUSCAR INFORMACION" />
</form>

<form name="form1" id="form1" action="#" method="post" style="margin:0;">  
<br>
<table width="100%">
<input type="hidden" name="numero_factura" value="<?php echo $buscar ?>" size="8">
<tr>
<td align="center"><strong>CODIGO</strong></td>
<td align="center"><strong>PRODUCTO</strong></td>
<td align="center"><strong>UNDS</strong></td>
<td align="center"><strong>FACTURA</strong></td>
<td align="center"><strong>V. UNIT</strong></td>
<td align="center"><strong>V. TOTAL</strong></td>
<td align="center"><strong>COMENTARIO</strong></td>
<td align="center"><strong>CLIENTE</strong></td>
<td align="center"><strong>TIPO PAGO</strong></td>
<td align="center"><strong>FECHA</strong></td>
<td align="center"><strong>HORA</strong></td>
<td align="center"><strong>VENDEDOR</strong></td>
<td align="center"><strong>ELIM</strong></td>
</tr>
<?php
while ($datos = mysql_fetch_assoc($consulta)) {
$cod_ventas = $datos['cod_ventas'];
$cod_productos = $datos['cod_productos'];
$nombre_productos = $datos['nombre_productos'];
$unidades_vendidas = $datos['unidades_vendidas'];
$detalles = $datos['detalles'];
$tope_min = $datos['tope_min'];
$precio_compra = $datos['precio_compra'];
$precio_venta = $datos['precio_venta'];
$fechas_vencimiento = $datos['fechas_vencimiento'];
$vlr_total_venta = $datos['vlr_total_venta'];
$cod_facturan = $datos['cod_factura'];
$comentario = $datos['comentario'];
$descuento_ptj = $datos['descuento_ptj'];
?>
<tr>
<td><font><?php echo $datos['cod_productos']; ?></td></font>
<td><font><?php echo $datos['nombre_productos']; ?></td></font>
<td align="right"><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'unidades_vendidas', <?php echo $cod_ventas;?>)" class="cajextragrand" id="<?php echo $cod_ventas;?>" value="<?php echo $unidades_vendidas;?>" size="9"></td>
<td ><a href="../admin/ver_factura_venta.php?cod_factura=<?php echo rawurlencode($cod_facturan)?>"><center><?php echo $cod_facturan?></center></a></td>
<td align="right"><font><?php echo number_format($datos['precio_venta'], 0, ",", "."); ?></td></font>
<td align="right"><font><?php echo number_format($datos['vlr_total_venta'], 0, ",", "."); ?></td></font>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'comentario', <?php echo $cod_ventas;?>)" class="cajbarras" id="<?php echo $cod_ventas;?>" value="<?php echo $comentario;?>" size="3"></td>
<td align="left"><font><?php echo $datos['nombres']." ".$datos['apellidos'];?></td></font>
<td align="center"><font><?php echo $datos['tipo_pago']?></td></font>
<td align="center"><font><?php echo $datos['fecha_anyo']; ?></td></font>
<td align="center"><font><?php echo $datos['fecha_hora']; ?></td></font>
<td align="center"><font><?php echo $datos['vendedor']; ?></td></font>
<td  nowrap><a href="../modificar_eliminar/eliminar_productos_factura.php?cod_productos=<?php echo $cod_productos?>&cod_ventas=<?php echo $cod_ventas?>&cod_factura=<?php echo $_POST['cod_factura'];?>&pagina=<?php echo $pagina?>"><center><img src=../imagenes/eliminar.png alt="Eliminar"></center></a></td>
</tr>	 
<?php
}
} else {
?>
<br><br><center><TD><font color="white" size="+2">NO SE ENCONTRARON RESULTADOS</font></font></center>
<?php
echo "<META HTTP-EQUIV='REFRESH' CONTENT='2; ../admin/busq_facturas_fecha.php'>";
}
?>
</form>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
</head>
