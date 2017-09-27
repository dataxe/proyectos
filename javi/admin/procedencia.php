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
include ("../formato_entrada_sql/funcion_env_val_sql.php");

$nivel_acceso = '3';
if ($seguridad_acceso['cod_seguridad'] <> $nivel_acceso) {
header("Location:../admin/acceso_denegado.php");
}
/*
$verificar_pag = "SELECT * FROM pagina_actual WHERE cod_pagina_actual='1'";
$resultado_verificar_pag = mysql_query($verificar_pag, $conectar) or die(mysql_error());

$nombre_pagina_actual = 'PROCEDENCIA';
if ($resultado_verificar_pag['nombre_pagina_actual'] <> $nombre_pagina_actual) {
$actualizar_nombre_pagina_actual = sprintf("UPDATE pagina_actual SET nombre_pagina_actual=%s WHERE cod_pagina_actual='1'",
envio_valores_tipo_sql($nombre_pagina_actual, "text"), envio_valores_tipo_sql($_POST['cod_pagina_actual'], "text"));
$resultado_actualizacion_pagina_actual = mysql_query($actualizar_nombre_pagina_actual, $conectar) or die(mysql_error());
}
*/
$pagina_actual = $_SERVER["PHP_SELF"];

$numero_maximo_de_muestra = 4;
$numero_de_pagina = 0;
if (isset($_GET['numero_de_pagina'])) {
  $numero_de_pagina = $_GET['numero_de_pagina'];
}
$muestra_faltante = $numero_de_pagina * $numero_maximo_de_muestra;
$buscar = $_POST['palabra'];
$mostrar_datos_sql = "SELECT * FROM paises WHERE nombre_paises  like '%$buscar%' order by cod_paises DESC";
$limite_consulta_sql = sprintf("%s LIMIT %d, %d", $mostrar_datos_sql, $muestra_faltante, $numero_maximo_de_muestra);
$consulta = mysql_query($limite_consulta_sql, $conectar) or die(mysql_error());
$matriz_consulta = mysql_fetch_assoc($consulta);

if (isset($_GET['numero_total_de_registros'])) {
  $numero_total_de_registros = $_GET['numero_total_de_registros'];
} else {
  $todo_consulta = mysql_query($mostrar_datos_sql);
  $numero_total_de_registros = mysql_num_rows($todo_consulta);
}
$total_pagina_consulta = ceil($numero_total_de_registros/$numero_maximo_de_muestra)-1;

$consulta_caracter_vacio = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $parametros = explode("&", $_SERVER['QUERY_STRING']);
  $nuevos_parametros = array();
  foreach ($parametros as $parametro) {
    if (stristr($parametro, "numero_de_pagina") == false && 
        stristr($parametro, "numero_total_de_registros") == false) {
      array_push($nuevos_parametros, $parametro);
    }
  }
  if (count($nuevos_parametros) != 0) {
    $consulta_caracter_vacio = "&" . htmlentities(implode("&", $nuevos_parametros));
  }
}
$consulta_caracter_vacio = sprintf("&numero_total_de_registros=%d%s", $numero_total_de_registros, $consulta_caracter_vacio);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<p>
<center>
<table width="50%" align="center" id="table">
<tr>
<td width="23%" align="center"><?php if ($numero_de_pagina > 0) {?><a href="<?php printf("%s?numero_de_pagina=%d%s", $pagina_actual, 0, $consulta_caracter_vacio); ?>" >Primero</a><?php }?></td>
<td width="31%" align="center"><?php if ($numero_de_pagina > 0) {?><a href="<?php printf("%s?numero_de_pagina=%d%s", $pagina_actual, max(0, $numero_de_pagina - 1), $consulta_caracter_vacio); ?>" >Anterior</a><?php }?></td>
<td width="23%" align="center"><?php if ($numero_de_pagina < $total_pagina_consulta) {?><a href="<?php printf("%s?numero_de_pagina=%d%s", $pagina_actual, min($total_pagina_consulta, $numero_de_pagina + 1), $consulta_caracter_vacio); ?>" >Siguiente</a><?php }?></td>
<td width="23%" align="center"><?php if ($numero_de_pagina < $total_pagina_consulta) {?><a href="<?php printf("%s?numero_de_pagina=%d%s", $pagina_actual, $total_pagina_consulta, $consulta_caracter_vacio); ?>" >&Uacute;ltimo</a><?php }?></td>
</tr>
</table>
</center>
<br>
<center>
<table border="1" id="table">
<tr>
<td><div align="center"><strong>Editar</strong></div></td>
<td><div align="center"><strong>C&oacute;digo</strong></div></td>
<td><div align="center"><strong>Nombre pais</strong></div></td>
<td><div align="center"><strong>Eliminar</strong></div></td>
</tr>
<?php do { ?>
<tr>
<td ><a href="../modificar_eliminar/modificar_procedencia.php?cod_paises=<?php echo $matriz_consulta['cod_paises']; ?>"><center><img src=../imagenes/actualizar.png alt="Actualizar"></a></td>
<td ><?php echo $matriz_consulta['cod_paises']; ?></span></td>
<td ><?php echo $matriz_consulta['nombre_paises']; ?></span></td>
<td ><a href="../modificar_eliminar/eliminar_procedencia.php?cod_paises=<?php echo $matriz_consulta['cod_paises']; ?>"><center><img src=../imagenes/eliminar.png alt="eliminar"></a></td>
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
<td><div align="center" ><strong>Cod</strong></div></td>
<td><div align="center" ><strong>Nombre</strong></div></td>
<?php do { ?>
<tr>
<td><?php $sql_consulta1="SELECT cod_paises FROM paises ORDER BY cod_paises DESC LIMIT 1";
$resultado = mysql_query($sql_consulta1, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<input type="text" name="cod_paises" value="<?php echo $contenedor['cod_paises']+1 ?>" size ="3" required autofocus>
<?php }?></td>
<td><input id="foco" type="text" name="nombre_paises" value="" size="30" required autofocus></td>
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
<?php mysql_free_result($consulta);

if (isset($_POST["nombre_paises"])) {
$nombre_paises = addslashes($_POST["nombre_paises"]);
$cod_paises = intval($_POST["cod_paises"]);
   
   // Hay campos en blanco
if($nombre_paises==NULL) {
echo "<embed SRC='../sonidos/alarma.mp3' hidden='true' autostart='true' loop='3'></embed>";
echo "<font color='yellow'><br><center><img src=../imagenes/advertencia.gif alt='Advertencia'><strong>Ha dejado el campo Pais vacio.</strong><img src=../imagenes/advertencia.gif alt='Advertencia'></font>";
} else {
         // Comprobamos si el nombre_marcas ya existe
$verificar_nombre_marcas = mysql_query("SELECT nombre_paises FROM paises WHERE nombre_paises = '$nombre_paises'");
$existencia_nombre_marcas = mysql_num_rows($verificar_nombre_marcas);
         
if ($existencia_nombre_marcas > 0) {
echo "<embed SRC='../sonidos/alarma.mp3' hidden='true' autostart='true' loop='3'></embed>";
echo "<font color='yellow'><br><center><img src=../imagenes/advertencia.gif alt='Advertencia'>El pais <strong>".$nombre_paises." </strong>ya existe, verifique en la lista de paises.<img src=../imagenes/advertencia.gif alt='Advertencia'></font>";
} else {
if ((isset($_POST["insertar_datos"])) && ($_POST["insertar_datos"] == "formulario")) {
$agregar_registros_sql1 = sprintf("INSERT INTO paises (cod_paises, nombre_paises) VALUES (%s, %s)",
envio_valores_tipo_sql($_POST['cod_paises'], "text"),
envio_valores_tipo_sql($_POST['nombre_paises'], "text"));
					   
$resultado_sql1 = mysql_query($agregar_registros_sql1, $conectar) or die(mysql_error());
echo '<META HTTP-EQUIV="REFRESH" CONTENT="1; procedencia.php">';
echo "<font color='yellow'>Se ha ingresado correctamente el pais <strong>".$nombre_paises.".</strong></font>";
}
//mysql_query($agregar_registros_sql1) or die(mysql_error());
      }
   }
}
?>