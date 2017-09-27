<HTML>
<HEAD>
<TITLE>CONVERTIDOR CSV A SQL</TITLE>
</HEAD>
<BODY>
	<style type="text/css">
<!--
body {
background-color: #333333;
}
-->
</style>
<link href="../imagenes/icono.ico" type="image/x-icon" rel="shortcut icon" />
<center><H2><font color='white'>CONVERTIDOR CSV A SQL</font></H2></center>
<!-- Input form begin -->
<FORM NAME="csv_a_sql" METHOD=POST ACTION="">
<INPUT TYPE="HIDDEN" NAME="referencia" VALUE="csv_a_sql">
<font color='white'><strong>NOMBRE DE LA TABLA:</strong></font>
<BR>
<INPUT CLASS="DEFAULT" TYPE="TEXT" NAME="nombre_de_la_tabla" VALUE="" SIZE=50>
<BR><BR>
<font color='white'><strong>CODIGO CSV: </strong></font>
<BR>
<TEXTAREA CLASS="DEFAULT" NAME="codigo_csv" ROWS=10 COLS=150></TEXTAREA>
<BR><BR>
<INPUT CLASS="DEFAULT" TYPE=SUBMIT VALUE="     CONVERTIR A CODIGO SQL     ">
</FORM>
<!-- Input form end -->
<?php
error_reporting(E_ALL ^ E_NOTICE);
// Parse incoming information if above form was posted
if($_POST[referencia] == "csv_a_sql") {

echo "<center><h2><font color='white'>Salida de la operacion:</font></h2></center>";

// Get information from form & prepare it for parsing
$nombre_de_la_tabla = $_POST[nombre_de_la_tabla];
$codigo_csv   = $_POST[codigo_csv];
$matriz_csv    = explode("\n",$codigo_csv);
$nombres_columnas = explode(";",$matriz_csv[0]);

// Generate base query
$consulta_base = "INSERT INTO $nombre_de_la_tabla (";
$antes_principio      = true;
foreach($nombres_columnas as $nombre_columna) {
if(!$antes_principio)
$consulta_base .= ", ";	
$nombre_columna = trim($nombre_columna);
$consulta_base .= "`$nombre_columna`";
$antes_principio = false;
}
$consulta_base .= ") ";

// Loop through all CSV data rows and generate separate
// INSERT queries based on consulta_base + the row information
$ultimo_dato_de_la_consulta = count($matriz_csv) - 1;
for($conteo = 1; $conteo < $ultimo_dato_de_la_consulta; $conteo++) {
$valor_de_la_consulta = "VALUES (";
$antes_principio = true;
$datos_de_la_matriz = explode(";",$matriz_csv[$conteo]);
$valor_del_conteo = 0;
foreach($datos_de_la_matriz as $valor_del_dato)	{
if(!$antes_principio)
$valor_de_la_consulta .= ", ";	
$valor_del_dato = trim($valor_del_dato);
$valor_de_la_consulta .= "'$valor_del_dato'";
$antes_principio = false;
}
$valor_de_la_consulta .= ")";

// Combine generated queries to generate final consulta
$consulta = $consulta_base .$valor_de_la_consulta .";";
echo "<font color='white'>$consulta <BR></font>";
}
}
?>