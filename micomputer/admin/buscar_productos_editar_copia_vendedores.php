<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");
include("../notificacion_alerta/mostrar_noficacion_alerta.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../index.php");
}

$cuenta_actual = addslashes($_SESSION['usuario']);
include ("../seguridad/seguridad_diseno_plantillas.php");
/*
$nivel_acceso = '3';
if ($seguridad_acceso['cod_seguridad'] <> $nivel_acceso) {
header("Location:../admin/acceso_denegado.php");
}
*/
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");

$pagina = 'buscar_productos_editar.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<center>
<br>
<form action="../modificar_eliminar/productos_actualizar_inventario_copia_vendedores.php" method="GET">
<td><strong><a href="productos_cargados_inventario_vendedores_comparacion_unidades.php"><font color='white'>PRODUCTOS CARGADOS</font></a></strong></td>&nbsp;&nbsp;&nbsp;&nbsp;
<td><strong><a href="productos_sin_cargar_inventario_vendedores.php"><font color='white'>FALTAN POR CARGAR</font></a></strong></td>&nbsp;&nbsp;&nbsp;&nbsp;
<td><strong><a href="cargar_inventario_copia_vendedores.php"><font color='white'>CARGAR INVENTARIO MANUAL VENDEDOR</font></a></strong></td>&nbsp;&nbsp;&nbsp;&nbsp;
<td><strong><font color='yellow'>CARGAR POR CODIGO VENDEDOR: </font></strong></td> <input name="cod_productos" placeholder="C&oacute;digo del producto" id="foco" style="height:26"/>
<input type="hidden" name="pagina" value="<?php echo $pagina;?>"/>
</select> 
<input type="submit" value="Editar producto" />
</form>
</center>
<body>
<script>
window.onload = function() {
  document.getElementById("foco").focus();
}
</script>