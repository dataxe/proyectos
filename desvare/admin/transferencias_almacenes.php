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

$nivel_acceso = '3';
if ($seguridad_acceso['cod_seguridad'] <> $nivel_acceso) {
header("Location:../admin/acceso_denegado.php");
}
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");

$formulario_agregar = $_SERVER['PHP_SELF'];
$buscar = $_POST['palabra'];
$mostrar_datos_sql = "SELECT * FROM transferencias_almacenes WHERE nombre_almacen like '%$buscar%' order by nombre_almacen";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$matriz_consulta = mysql_fetch_assoc($consulta);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<center>
<td><strong><font color='white'>LISTA DE ALMACENES: </font></strong></td><br><br>
<table id="table" width="95%">
<tr>
<td><div align="center"><strong>ELM</strong></div></td>
<td><div align="center"><strong>CODIGO</strong></div></td>
<td><div align="center"><strong>ALMACEN</strong></div></td>
<td><div align="center"><strong>ATIENDE</strong></div></td>
<td><div align="center"><strong>CORREO</strong></div></td>
<td><div align="center"><strong>DIRECCION</strong></div></td>
<td><div align="center"><strong>TELEFONO</strong></div></td>
<td><div align="center"><strong>EDIT</strong></div></td></tr>
<?php do { ?>
<tr>
<td  nowrap><a href="../modificar_eliminar/eliminar_transferencias_almacenes.php?cod_transferencias_almacenes=<?php echo $matriz_consulta['cod_transferencias_almacenes']; ?>"><center><img src=../imagenes/eliminar.png alt="eliminar"></a></td>
<td align="center"><?php echo $matriz_consulta['cod_transferencias_almacenes']; ?></td>
<td><?php echo $matriz_consulta['nombre_almacen']; ?></td>
<td><?php echo $matriz_consulta['atiende']; ?></td>
<td><?php echo $matriz_consulta['correo']; ?></td>
<td><?php echo $matriz_consulta['direccion']; ?></td>
<td align="right"><?php echo $matriz_consulta['telefono']; ?></td>
<td><a href="../modificar_eliminar/modificar_transferencias_almacenes.php?cod_transferencias_almacenes=<?php echo $matriz_consulta['cod_transferencias_almacenes']; ?>"><center><img src=../imagenes/actualizar.png alt="Actualizar"></a></td>
</tr>
<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
</table>
<script>
window.onload = function() {
  document.getElementById("foco").focus();
}
</script>
<br>
<form method="post" id="table" name="formulario" action="<?php echo $formulario_agregar; ?>">
<table align="center" id="table">
<tr>
<td><div align="center"><strong>ALMACEN</strong></div></td>
<td><div align="center"><strong>ATIENDE</strong></div></td>
<td><div align="center"><strong>CORREO</strong></div></td>
<td><div align="center"><strong>DIRECCION</strong></div></td>
<td><div align="center"><strong>TELEFONO</strong></div></td>
<?php do { ?>
<tr>
<td><input id="foco" type="text" name="nombre_almacen" value="" size="20" required autofocus></td>
<td><input type="text" name="atiende" value="" size="20"></td>
<td><input type="text" name="correo" value="" size="20"></td>
<td><input type="text" name="direccion" value="" size="20"></td>
<td><input type="text" name="telefono" value="" size="20"></td>
</tr>
<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td bordercolor="1"><input type="submit" id="boton1" value="Agregar"></td>
</tr>
</table>
<input type="hidden" name="insertar_datos" value="formulario">
</form>
</body>
</html>
<?php mysql_free_result($consulta);?>
<?php
   if (isset($_POST["nombre_almacen"])) {
   $nombre_almacen = $_POST["nombre_almacen"];
   $atiende = $_POST["atiende"];
   $correo = $_POST["correo"];
   $direccion = $_POST["direccion"];
   $telefono = $_POST["telefono"];
// Hay campos en blanco
if($nombre_almacen==NULL) {
echo "<font color='white'><br><strong>Ha dejado campos vacios.</strong></font>";
} else {
// Comprobamos si el cliente ya existe
$verificar_cliente = mysql_query("SELECT nombre_almacen FROM transferencias_almacenes WHERE nombre_almacen = '$nombre_almacen'");
$existencia_cliente = mysql_num_rows($verificar_cliente);
         
if ($existencia_cliente > 0) {
echo "<font color='white'><br>El Cliente <strong>".$nombre_almacen." </strong>ya existe, verifique en la lista de almacenes.</font>";
} else {

if ((isset($_POST["insertar_datos"])) && ($_POST["insertar_datos"] == "formulario")) {
$agregar_registros_sql1 = sprintf("INSERT INTO transferencias_almacenes (nombre_almacen, atiende, correo, direccion, telefono) VALUES (%s, %s, %s, %s, %s)",
             envio_valores_tipo_sql($_POST['nombre_almacen'], "text"),
					   envio_valores_tipo_sql($atiende, "text"),
					   envio_valores_tipo_sql($correo, "text"),
					   envio_valores_tipo_sql($direccion, "text"),
					   envio_valores_tipo_sql($telefono, "text"));
					   
$resultado_sql1 = mysql_query($agregar_registros_sql1, $conectar) or die(mysql_error());
echo '<META HTTP-EQUIV="REFRESH" CONTENT="0.2; transferencias_almacenes.php">';
echo "<font color='white'>Se ha ingresado correctamente el almacen <strong>".$nombre_almacen.".</strong></font>";
}
//mysql_query($agregar_registros_sql1) or die(mysql_error());
      }
   }
}
?>