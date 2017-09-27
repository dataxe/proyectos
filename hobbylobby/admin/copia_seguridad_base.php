<?php
require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php');
mysql_select_db($base_datos, $conectar);
date_default_timezone_set("America/Bogota");
include ("../registro_movimientos/registro_movimientos.php");

$cuenta_actual = addslashes($_SESSION['usuario']);

$nombre_copia_seguridad = $base_datos."_".date("d/m/Y")."_Hora_".date("H:i:s").".txt"; //Este es el nombre_copia_seguridad del archivo a generar
/* Determina si la tabla será vaciada (si existe) cuando  restauremos la tabla. */            
$borrar = false;
$tablas = false; //tablas de la base_datos
//$tablas = false; //tablas de la base_datos
// Tipo de compresion. Puede ser "gz", "bz2", o false (sin comprimir)
$compresion = false;
/* Conexion */
/* Se busca las tablas en la base de datos */
if ( empty($tablas) ) {
$consulta = "SHOW TABLES FROM $base_datos;";
$respuesta = mysql_query($consulta, $conectar)
or die("No se pudo ejecutar la consulta: ".mysql_error());
while ($fila = mysql_fetch_array($respuesta, MYSQL_NUM)) {
$tablas[] = $fila[0];
    }
}
/* Se crea la cabecera del archivo */
$info['dumpversion'] = "1.1b";
$info['fecha'] = date("d-m-Y");
$info['hora'] = date("h:m:s A");
$info['mysqlver'] = mysql_get_server_info();
$info['phpver'] = phpversion();
ob_start();
print_r($tablas);
$representacion = ob_get_contents();
ob_end_clean ();
preg_match_all('/(\[\d+\] => .*)\n/', $representacion, $coincidencias );
$info['tablas'] = implode(";  ", $coincidencias [1]);
$arrojar = <<<EOT
EOT;
foreach ($tablas as $tabla) {
$borrar_consulta_tablas = "";
$crear_consulta_tablas = "";
$insertar_consulta_vacia = "";
/* Se halla la consulta que será capaz vaciar la tabla. */
if ($borrar) {
$borrar_consulta_tablas = "DROP TABLE IF EXISTS `$tabla`;";
} else {
$borrar_consulta_tablas = "# No especificado.";
}
/* Se halla la consulta que será capaz de recrear la estructura de la tabla. */
$crear_consulta_tablas = "";
$consulta = "SHOW CREATE TABLE $tabla;";
$respuesta = mysql_query($consulta, $conectar)
or die("No se pudo ejecutar la consulta: ".mysql_error());
while ($fila = mysql_fetch_array($respuesta, MYSQL_NUM)) {
$crear_consulta_tablas = $fila[1].";";
}
/* Se halla la consulta que será capaz de insertar los datos. */
$insertar_consulta_vacia = "";
$consulta = "SELECT * FROM $tabla;";
$respuesta = mysql_query($consulta, $conectar)
or die("No se pudo ejecutar la consulta: ".mysql_error());
while ($fila = mysql_fetch_array($respuesta, MYSQL_ASSOC)) {
$columnas = array_keys($fila);
foreach ($columnas as $columna) {
if ( gettype($fila[$columna]) == "NULL" ) {
$valor[] = "NULL";
} else {
$valor[] = "'".mysql_real_escape_string($fila[$columna])."'";
}
}
$insertar_consulta_vacia .= "INSERT INTO `$tabla` VALUES (".implode(", ", $valor).");\n";
unset($valor);
} 
$arrojar .= <<<EOT
# | Vaciado de tabla '$tabla'
$borrar_consulta_tablas 
# | Estructura de la tabla '$tabla'
$crear_consulta_tablas
# | Carga de datos de la tabla '$tabla'
$insertar_consulta_vacia
EOT;
}
/* Envio */
if ( !headers_sent() ) {
header("Pragma: no-cache");
header("Expires: 0");
header("Content-Transfer-Encoding: binary");
switch ($compresion) {
case "gz":
header("Content-Disposition: attachment; filename=$nombre_copia_seguridad.gz");
header("Content-type: application/x-gzip");
echo gzencode($arrojar, 9);
break;
case "bz2": 
header("Content-Disposition: attachment; filename=$nombre_copia_seguridad.bz2");
header("Content-type: application/x-bzip2");
echo bzcompress($arrojar, 9);
break;
default:
header("Content-Disposition: attachment; filename=$nombre_copia_seguridad");
header("Content-type: application/force-download");
echo $arrojar;
}
} else {
echo "<b>ATENCION: Probablemente ha ocurrido un error</b><br />\n<pre>\n$arrojar\n</pre>";
}
?>