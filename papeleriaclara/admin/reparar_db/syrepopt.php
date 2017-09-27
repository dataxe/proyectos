
<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../../conexiones/conexione.php'); 
require_once('../../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
include ("../../session/funciones_admin.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
include ("../../seguridad/seguridad_diseno_plantillas.php");
include ("../../formato_entrada_sql/funcion_env_val_sql.php");
include ("../../registro_movimientos/registro_movimientos.php");
//include ("../../registro_movimientos/registro_cierre_caja.php");

$nombre_db = $_POST["nombre_db"];
?>
<table width=760 cellspacing=5 cellpadding=5 border=1 bordercolor="#EFEFEF">
<TR>
<TD colspan=4>
Database <b>&quot;<?php echo $nombre_db; ?></b>&quot;
</TD>
</TR>
<TR>
<TD width=370 colspan=2>
Repairing...
</TD>
<TD width=370 colspan=2>
Optimizing...
</TD>
</TR>

<?php
$tbl_array = array(); $c = 0;
$result2 = mysql_list_tables($nombre_db);
for($x=0; $x<mysql_num_rows($result2); $x++) 
{ 	
 $tabelle = mysql_tablename($result2,$x);
// echo $tabelle."<BR>";
?>

<TR>
<TD width=300>
<?php echo $tabelle; ?>
</TD>
<TD align=center width=70>
<?php
$sql = "REPAIR TABLE `".$tabelle."`";
$result = mysql_query($sql,$conectar); 
if (!$result)
{
 print mysql_error();
} 
else
{
 echo "Status <font color=red><b>OK</b></font>";
}
?>
</TD>
<TD width=300>
<?php echo $tabelle; ?>
</TD>
<TD align=center width=70>
<?php
$sql = "OPTIMIZE TABLE `".$tabelle."`";
$result = mysql_query($sql,$conectar); 
if (!$result)
{
 print mysql_error();
} 
else
{
 echo "Status <font color=red><b>OK</b></font>";
}
}
?>
</TD>
</TR>
</TABLE>

