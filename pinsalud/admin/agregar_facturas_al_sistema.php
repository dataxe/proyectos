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
?>
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
<form action="importar_facturas_digital.php" method="post" enctype="multipart/form-data" name="form1">
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
<a href="descargar_archivos_subidos.php">Mostrar Facturas</a>
<br>
<br>
</div>
</body>
</html>