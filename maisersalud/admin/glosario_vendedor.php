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
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");
/*
$verificar_pag = "SELECT * FROM pagina_actual WHERE cod_pagina_actual='1'";
$resultado_verificar_pag = mysql_query($verificar_pag, $conectar) or die(mysql_error());

$nombre_pagina_actual = 'glosario';
if ($resultado_verificar_pag['nombre_pagina_actual'] <> $nombre_pagina_actual) {
$actualizar_nombre_pagina_actual = sprintf("UPDATE pagina_actual SET nombre_pagina_actual=%s WHERE cod_pagina_actual='1'",
envio_valores_tipo_sql($nombre_pagina_actual, "text"), envio_valores_tipo_sql($_POST['cod_pagina_actual'], "text"));
$resultado_actualizacion_pagina_actual = mysql_query($actualizar_nombre_pagina_actual, $conectar) or die(mysql_error());
}
*/
$mostrar_datos_sql = "SELECT * FROM glosario";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<center>
<td><strong><font color='yellow' size='+1'>GLOSARIO: </font></strong></td><br><br>
<table width="60%">
<tr>
<!--<td><div align="center"><strong>ELIM</strong></div></td>-->
<td><div align="center"><strong>BOTON</strong></div></td>
<td><div align="center"><strong>DESCRIPCION</strong></div></td>
<!--<td><div align="center"><strong>EDIT</strong></div></td>-->
</tr>
<?php do { ?>
<tr>
<!--<td ><a href="../modificar_eliminar/eliminar_glosario.php?cod_glosario=<?php echo $datos['cod_glosario']; ?>"><center><img src=../imagenes/eliminar.png alt="eliminar"></center></a></td>-->
<td ><center><img src=<?php echo $datos['url_img'];?> alt="eliminar"></center></td>
<td ><?php echo $datos['descripcion']; ?></td>
<!--<td ><a href="../modificar_eliminar/modificar_glosario.php?cod_glosario=<?php echo $datos['cod_glosario']; ?>&verificacion=<?php echo $correcto ?>"><center><img src=../imagenes/actualizar.png alt="Actualizar"></center></a></td>-->
</tr>
<?php } while ($datos = mysql_fetch_assoc($consulta)); ?>
</table>
</center>
</body>