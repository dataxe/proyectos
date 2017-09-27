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
date_default_timezone_set("America/Bogota");
$nivel_acceso = '3';
if ($seguridad_acceso['cod_seguridad'] <> $nivel_acceso) {
header("Location:../admin/acceso_denegado.php");
}
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");

$fecha_cargue = date("Y/m/d - H:i:s");
$cod_facturas = $_POST['cod_facturas'];
$cod_proveedor = $_POST['cod_proveedor'];
$fecha_llegada = $_POST['fecha_llegada'];
$url_archivo = "../facturas_cargadas/".$_FILES['nombre_archivo']['name'];
$nombre_archivo = $_FILES['nombre_archivo']['name'];

if($cod_facturas == NULL && $cod_proveedor == NULL && $fecha_llegada == NULL && $nombre_archivo == NULL) {
} else {
if($cod_facturas==NULL || $nombre_archivo==NULL) {
echo "<center><font color='white'><br><strong>Faltan datos por llenar.</strong></font><center> ";
echo '<META HTTP-EQUIV="REFRESH" CONTENT="5; agregar_facturas_al_sistema.php">';
}
// Comprobamos si ya existe
$verificar_cod_facturas = mysql_query("SELECT cod_facturas FROM facturas_cargadas WHERE cod_facturas LIKE '$cod_facturas'");
$existencia_cod_facturas = mysql_num_rows($verificar_cod_facturas);

$verificar_nombre_archivo = mysql_query("SELECT nombre_archivo FROM facturas_cargadas WHERE nombre_archivo LIKE '$nombre_archivo'");
$existencia_nombre_archivo = mysql_num_rows($verificar_nombre_archivo);

if ($existencia_cod_facturas > 0) {
echo "<center><font color='white'><br>La Factura: <strong> ".$cod_facturas." </strong>ya existe, verifique.</font><center>";
echo '<META HTTP-EQUIV="REFRESH" CONTENT="5; agregar_facturas_al_sistema.php">';
}
if ($existencia_nombre_archivo > 0) {
echo "<center><font color='white'><br>Dentro del sistema ya existe una factura con el nombre: <strong> ".$nombre_archivo." </strong>, verifique.</font><center>";
echo '<META HTTP-EQUIV="REFRESH" CONTENT="5; agregar_facturas_al_sistema.php">';
}
if ($cod_facturas!= NULL && $existencia_cod_facturas==0 && $existencia_nombre_archivo==0 && $nombre_archivo!= NULL) {
copy($_FILES['nombre_archivo']['tmp_name'], "../facturas_cargadas/" . $_FILES['nombre_archivo']['name']);
$subio = true;

$agregar_registros_sql1 = ("INSERT INTO facturas_cargadas (cod_facturas, cod_proveedor, fecha_llegada, nombre_archivo, url_archivo, 
fecha_cargue) VALUES ('$cod_facturas', '$cod_proveedor', '$fecha_llegada', '$nombre_archivo', '$url_archivo', '$fecha_cargue')");
$resultado_sql1 = mysql_query($agregar_registros_sql1, $conectar) or die(mysql_error());
echo "<center><font color='white' alling ='justify'>El archivo: ".$_FILES['nombre_archivo']['name']." se subio con exito</font><center> ";
echo '<META HTTP-EQUIV="REFRESH" CONTENT="5; agregar_facturas_al_sistema.php">';
 } else {
echo "<center><font color='white'>El archivo no cumple con las reglas establecidas, intente nuevamente.</font><center>";
echo '<META HTTP-EQUIV="REFRESH" CONTENT="5; agregar_facturas_al_sistema.php">';
}
}
?>
<script>
window.onload = function() {
document.getElementById("foco").focus();
}
</script>
<html>
<?php //require('calendario.php');?>
<head>
<link rel="stylesheet" type="text/css" href="../estilo_css/estilo_cargar_facturas.css">
<title>ALMACEN</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" media="all" href="calendario/calendar-blue.css"/>
<script type="text/javascript" src="calendario_js/calendar.js"></script>
<script type="text/javascript" src="calendario_js/calendar-en_sn_hora.js"></script>
<script type="text/javascript" src="calendario_js/calendar-setup.js"></script>
<script type="text/javascript" src="calendario_js/jquery.functions.js"></script>
</head>
<body>
<div class="subir">
<form action="" method="post" enctype="multipart/form-data" name="form1">
<br>
<td aling="right">Fecha Factura</td><br>
<input type="text" name="fecha_llegada" id="f_date_b" required autofocus/>
<br><br>
<td aling="right"> C&oacute;digo de Factura</td><br>
<input id="foco" name="cod_facturas" type=text required autofocus>
<br><br>
<td aling="right">Proveedor</td>
<select name="cod_proveedor">
<?php $sql_consulta="SELECT * FROM proveedores ORDER BY nombre_proveedores";
$resultado = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option value="<?php echo $contenedor['cod_proveedores'] ?>"><?php echo $contenedor['nombre_proveedores'] ?></option>
<?php }?>
</select>


<br><br>Buscar Factura:
<input type="file" name="nombre_archivo" id="archivo"/ required autofocus> <br>
<input type="submit" name="boton" value="Agregar Registro" />
</form>
<script type="text/javascript">
Calendar.setup({
inputField     :    "f_date_b",      // id of the input field
ifFormat       :    "%d/%m/%Y",       // format of the input field
showsTime      :    true,            // will display a time selector
button         :    "f_date_b",   // trigger for the calendar (button ID)
singleClick    :    true,           
step           :    1                // show all years in drop-down boxes (instead of every other year as default)
});
</script>
<br>
<a href="descargar_facturas.php">Mostrar Facturas</a>
<br>
<br>
</div>
</body>
</html>