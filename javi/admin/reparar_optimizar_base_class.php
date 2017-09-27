<?php error_reporting(E_ALL ^ E_NOTICE);
require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 

$nombre_db = $_POST["nombre_db"];
?>
<center>
<table width='900' cellspacing='5' cellpadding='5' border='1' bordercolor="#EFEFEF">
<TR>
<TD colspan='4'>
<center><font size="6px" color="black">BASE DE DATOS: <b><?php echo $nombre_db; ?></font></b></center>

<title>.::EDITAXE - REPARAR BASE</title>
<link href="../imagenes/icono.ico" type="image/x-icon" rel="shortcut icon" />

</TD>
</TR>
<TR>
<TD width='370' colspan=2><font color='black'>REAPARANDO...</font></TD>
<TD width='370' colspan=2><font color='black'>OPTIMIZANDO...</font></TD>
</TR>
<?php
$tbl_array = array(); 
$c = 0;
$result2 = mysql_list_tables($nombre_db);
for($x=0; $x<mysql_num_rows($result2); $x++) { 	
$tablas = mysql_tablename($result2,$x);
// echo $tablas."<BR>";
?>
<TR>
<TD width='300'>
<?php echo $tablas; ?>
</TD>
<TD align=center width='70'>
<?php
$sql = "REPAIR TABLE `".$tablas."`";
$result = mysql_query($sql,$conectar); 
if (!$result) {
print mysql_error();
} else {
echo "Estado <font color=red><b>OK</b></font>";
}
?>
</TD>
<TD width='300'>
<?php echo $tablas; ?>
</TD>
<TD align=center width='70'>
<?php
$sql = "OPTIMIZE TABLE `".$tablas."`";
$result = mysql_query($sql,$conectar); 
if (!$result) {
print mysql_error();
} else {
echo "Estado <font color=red><b>OK</b></font>";
}
}
?>
</TD>
</TR>
</TABLE>
</center>
