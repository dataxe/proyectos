<?php error_reporting(E_ALL ^ E_NOTICE);
require_once('../conexiones/conexione.php'); 
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
//include ("../registro_movimientos/registro_cierre_caja.php");
?>
<center>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<center>
<br><br>
<td><a href="../admin/busq_facturas_fecha.php"><font size='+2' color='yellow'>BUSCAR</font></a></td>
&nbsp;&nbsp;&nbsp;&nbsp;
<td><a href="../admin/busq_facturas_listado.php"><font size='+2' color='yellow'>VER LISTADO DE FACTURAS</font></a></td>
&nbsp;&nbsp;&nbsp;&nbsp;
<td><a href="../admin/buscar_por_mes_o_producto.php"><font size='+2' color='yellow'>BUSCAR POR MES O PRODUCTO</font></a></td>
&nbsp;&nbsp;&nbsp;&nbsp;
<td><a href="../admin/devoluciones_ventas_mensual.php"><font size='+2' color='yellow'>DEVOLUCIONES</font></a></td>
</head>