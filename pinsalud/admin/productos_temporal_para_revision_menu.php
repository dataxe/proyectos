<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
$cuenta_actual = addslashes($_SESSION['usuario']);
include("../session/funciones_admin.php");
//include("../notificacion_alerta/mostrar_noficacion_alerta.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
  } else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
include ("../seguridad/seguridad_diseno_plantillas.php");
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");

$mostrar_datos_sql = "SELECT productos_temporal.numero_factura, productos_temporal.fechas_dia, 
productos_temporal.fechas_anyo, productos_temporal.fechas_hora, productos_temporal.cuenta, 
Count(productos_temporal.numero_factura) AS total_registros, Count(productos.cod_productos_var) AS total_actualizar
FROM productos_temporal LEFT JOIN productos ON productos_temporal.cod_productos_var = productos.cod_productos_var
GROUP BY productos_temporal.numero_factura ORDER BY fechas_anyo DESC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$tota_datos = mysql_num_rows($consulta);

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
<form name="form1" id="form1" action="#" method="post" style="margin:1;">  
<br>
<td align="center"><font color="yellow" size="+2">PRODUCTOS PARA REVISION <a href="../admin/importar_productos_temporal_archivo_plano.php"><font color='white'> - SUBIR ARCHIVO PLANO</font></font></a></font></td>
<br><br>

<table width='70%' border='1'>
<tr>
<td align="center">ELM</td>
<td align="center">CODIGO</td>
<td align="center">FECHA</td>
<td align="center">HORA</td>
<td align="center">CUENTA</td>
<td align="center">PARA INSERTAR</td>
<td align="center">PARA ACTUALIZAR</td>
</tr>
<?php 
while ($datos = mysql_fetch_assoc($consulta)) {
$numero_factura = $datos['numero_factura'];
$fechas_dia = $datos['fechas_dia'];
$fechas_anyo = $datos['fechas_anyo'];
$fechas_hora = $datos['fechas_hora'];
$cuenta = $datos['cuenta'];
$total_registros = $datos['total_registros'];
$total_actualizar = $datos['total_actualizar'];
$total_insertar = $total_registros - $total_actualizar;
?>
<tr>
<td align="center"><a href="../modificar_eliminar/eliminar_productos_temporal_menu.php?numero_factura=<?php echo $numero_factura?>&pagina=<?php echo $pagina?>"><img src=../imagenes/eliminar.png alt="Eliminar"></a></td>
<td align='center'><?php echo $numero_factura;?></td>
<td align='center'><?php echo $fechas_dia;?></td>
<td align='center'><?php echo $fechas_hora;?></td>
<td align='center'><?php echo $cuenta;?></td>
<td align="center"><a href="../admin/insertar_productos_desde_productos_temporal_masivo.php?numero_factura=<?php echo $numero_factura?>"><img src=../imagenes/boton_insertar.png alt="Insertar"><?php echo $total_insertar ?></a></td>
<td align="center"><a href="../admin/actualizar_productos_desde_productos_temporal_masivo.php?numero_factura=<?php echo $numero_factura?>"><img src=../imagenes/boton_actualizar.png alt="Actualizar"><?php echo $total_actualizar ?></a></td>
</tr>
<?php } ?>
</table>
</form>
</body>
</html>