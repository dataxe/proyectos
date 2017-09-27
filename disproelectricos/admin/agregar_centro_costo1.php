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

$pagina_actual = $_SERVER["PHP_SELF"];
$formulario_agregar = $_SERVER['PHP_SELF'];

if ((isset($_POST["insertar_datos"])) && ($_POST["insertar_datos"] == "formulario")) {

$agregar_registros_sql1 = sprintf("INSERT INTO egresos (nombre_ccosto) VALUES (%s)",
envio_valores_tipo_sql($_POST['nombre_ccosto'], "text"));
             
$resultado_sql1 = mysql_query($agregar_registros_sql1, $conectar) or die(mysql_error());
echo '<META HTTP-EQUIV="REFRESH" CONTENT="0.1; agregar_centro_costo.php">';
}
?>
<center>
<td>
<td><strong><a href="centro_costo_dia.php"><font color='white'>CENTRO COSTO</font></a> - <font color='yellow'>AGREGAR CENTRO DE COSTO</font></strong></td><br><br>
</td>
</center>
<center>
<br>
<form method="post" name="formulario" action="<?php echo $formulario_agregar; ?>">
<table width="20%">
<tr>
<td align="center"><strong>CENTRO COSTO</strong></td>
</tr>
<?php do { ?>
<tr>
<td align="center"><input style="font-size:24px" type="text" name="nombre_ccosto" value="" size="30"></td>
</tr>
<?php } while ($datos = mysql_fetch_assoc($consulta)); ?>
<tr valign="baseline">
<td align="center" bordercolor="1"><input type="submit" id="boton1" value="Agregar"></td>
</tr>
</table>
<input type="hidden" name="insertar_datos" value="formulario">
</form>
</center>