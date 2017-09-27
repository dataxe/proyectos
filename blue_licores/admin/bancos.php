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
$mostrar_datos_sql = "SELECT * FROM bancos WHERE nombre_bancos like '%$buscar%' order by nombre_bancos";
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
<td><strong><font color='white'>LISTA DE BANCOS: </font></strong></td><br><br>
<table>
<tr>
<td align="center"><strong>EDIT</strong></td>
<td align="center"><strong>NOMBRE</strong></td>
<td align="center"><strong>CUENTA</strong></td>
<td align="center"><strong>ELIM</strong></td></tr>
<?php do { ?>
<tr>
<td  nowrap><a href="../modificar_eliminar/eliminar_bancos.php?cod_bancos=<?php echo $matriz_consulta['cod_bancos']; ?>"><center><img src=../imagenes/eliminar.png alt="eliminar"></a></td>
<td><?php echo $matriz_consulta['nombre_bancos']; ?></td>
<td><?php echo $matriz_consulta['numero_cuenta_bancos']; ?></td>
<td><a href="../modificar_eliminar/modificar_bancos.php?cod_bancos=<?php echo $matriz_consulta['cod_bancos']; ?>"><center><img src=../imagenes/actualizar.png alt="Actualizar"></a></td>
</tr>
<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
</table>
<script>
window.onload = function() {
  document.getElementById("foco").focus();
}
</script>
<br>
<form method="post" name="formulario" action="<?php echo $formulario_agregar; ?>">
<table align="center">
<tr>
<td align="center"><strong>NOMBRE</strong></td>
<td align="center"><strong>CUENTA</strong></td>
<?php do { ?>
<tr>
<td><input id="foco" type="text" name="nombre_bancos" value="" size="20" required autofocus></td>
<td><input type="text" name="numero_cuenta_bancos" value="" size="20"></td>
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
   if (isset($_POST["nombre_bancos"])) {
   $nombre_bancos = $_POST["nombre_bancos"];
   $numero_cuenta_bancos = $_POST["numero_cuenta_bancos"];
// Hay campos en blanco
if($nombre_bancos==NULL) {
echo "<font color='white'><br><strong>Ha dejado el nombre del banco vacio</strong></font>";
} else {
// Comprobamos si el cliente ya existe
$verificar_numero_cuenta = mysql_query("SELECT * FROM bancos WHERE nombre_bancos = '$nombre_bancos'");
$existencia_numero_cuenta = mysql_num_rows($verificar_numero_cuenta);
         
if ($existencia_numero_cuenta > 0) {
echo "<font color='white'><br>El banco <strong>".$nombre_bancos." </strong>ya existe, verifique en la lista.</font>";
} else {

if ((isset($_POST["insertar_datos"])) && ($_POST["insertar_datos"] == "formulario")) {
$agregar_registros_sql1 = sprintf("INSERT INTO bancos (nombre_bancos, numero_cuenta_bancos) VALUES (%s, %s)",
envio_valores_tipo_sql($_POST['nombre_bancos'], "text"),
envio_valores_tipo_sql($_POST['numero_cuenta_bancos'], "text"));
					   
$resultado_sql1 = mysql_query($agregar_registros_sql1, $conectar) or die(mysql_error());
echo '<META HTTP-EQUIV="REFRESH" CONTENT="1; bancos.php">';
echo "<font color='white'>Se ha ingresado correctamente <strong></strong></font>";
}
//mysql_query($agregar_registros_sql1) or die(mysql_error());
      }
   }
}
?>