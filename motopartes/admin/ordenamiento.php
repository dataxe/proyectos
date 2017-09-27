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

$nivel_acceso = '3';
if ($seguridad_acceso['cod_seguridad'] <> $nivel_acceso) {
header("Location:../admin/acceso_denegado.php");
}
$numero_maximo_de_muestra = 4;
$numero_de_pagina = 0;
if (isset($_GET['numero_de_pagina'])) {
$numero_de_pagina = $_GET['numero_de_pagina'];
}
$muestra_faltante = $numero_de_pagina * $numero_maximo_de_muestra;
$buscar = $_POST['palabra'];
$mostrar_datos_sql = "SELECT * FROM nomenclatura order by cod_nomenclatura DESC";
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<center>
<table width="50%" align="center">
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
<td><strong><font color='white'>UBICACION ESTANTE: </font></strong></td><br><br>
<table width="30%">
<tr>
<td align="center"><strong>ELIM</strong></td>
<td align="center"><strong>CODIGO</strong></td>
<td align="center"><strong>NOMBRE</strong></td>
<td align="center"><strong>EDIT</strong></td>
</tr>
<?php do { ?>
<tr>
<td ><a href="../modificar_eliminar/eliminar_ordenamientos.php?cod_nomenclatura=<?php echo $matriz_consulta['cod_nomenclatura']; ?>"><center><img src=../imagenes/eliminar.png alt="eliminar"></a></td>
<td ><?php echo $matriz_consulta['cod_nomenclatura']; ?></td>
<td ><?php echo $matriz_consulta['nombre_nomenclatura']; ?></td>
<td ><a href="../modificar_eliminar/modificar_ordenamientos.php?cod_nomenclatura=<?php echo $matriz_consulta['cod_nomenclatura']; ?>"><center><img src=../imagenes/actualizar.png alt="Actualizar"></a></td>
</tr>
<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
</table>
<br>
<form method="post" name="formulario" action="<?php echo $formulario_agregar; ?>">
<table align="center">
<tr>
<td align="center"><strong>NOMBRE</strong></td>
<?php do { ?>
<tr>
<td><input type="text" name="nombre_nomenclatura" value="" size="30" required autofocus></td>
</tr>
<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
</tr>
</table>
<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td bordercolor="1"><input type="submit" id="boton1" value="Agregar"></td>
<input type="hidden" name="insertar_datos" value="formulario">
</form>
</body>
</html>
<?php mysql_free_result($consulta);?>

<?php
if (isset($_POST["nombre_nomenclatura"])) {

$nombre_nomenclatura0 = addslashes($_POST["nombre_nomenclatura"]);
$nombre_nomenclatura1 = preg_replace("/,/", '.', $nombre_nomenclatura0);
$nombre_nomenclatura2 = preg_replace("/'/", ' .', $nombre_nomenclatura1);
$nombre_nomenclatura = strtoupper(preg_replace('/"/', ' .', $nombre_nomenclatura2));

// Hay campos en blanco
if($nombre_nomenclatura==NULL) {
echo "<embed SRC='../sonidos/alarma.mp3' hidden='true' autostart='true' loop='3'></embed>";
      echo "<font color='yellow'><br><img src=../imagenes/advertencia.gif alt='Advertencia'><strong>Ha dejado el campo vacio.</strong><img src=../imagenes/advertencia.gif alt='Advertencia'></font>";
} else {
// Comprobamos si el nombre_marcas ya existe
$verificar_nombre_nomenclatura = mysql_query("SELECT nombre_nomenclatura FROM nomenclatura WHERE nombre_nomenclatura = '$nombre_nomenclatura'");
$existencia_nombre_nomenclatura = mysql_num_rows($verificar_nombre_nomenclatura);
         
if ($existencia_nombre_nomenclatura > 0) {
  echo "<embed SRC='../sonidos/alarma.mp3' hidden='true' autostart='true' loop='3'></embed>";
echo "<font color='yellow'><br><img src=../imagenes/advertencia.gif alt='Advertencia'>La marca <strong>".$nombre_nomenclatura." </strong>ya existe, verifique en la lista.<img src=../imagenes/advertencia.gif alt='Advertencia'></font>";
} else {
if ((isset($_POST["insertar_datos"])) && ($_POST["insertar_datos"] == "formulario")) {
$agregar_registros_sql1 = sprintf("INSERT INTO nomenclatura (nombre_nomenclatura) VALUES (%s)",
envio_valores_tipo_sql($nombre_nomenclatura, "text"));
             
$resultado_sql1 = mysql_query($agregar_registros_sql1, $conectar) or die(mysql_error());
echo '<META HTTP-EQUIV="REFRESH" CONTENT="1; ordenamiento.php">';
echo "<font color='yellow'>Se ha ingresado correctamente <strong>".$nombre_nomenclatura.".</strong></font>";
}
//mysql_query($agregar_registros_sql1) or die(mysql_error());
      }
   }
}
?>