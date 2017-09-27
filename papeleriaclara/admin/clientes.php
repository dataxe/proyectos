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
//include ("../formato_entrada_sql/funcion_env_val_sql.php");

$nivel_acceso = '3';
if ($seguridad_acceso['cod_seguridad'] <> $nivel_acceso) {
header("Location:../admin/acceso_denegado.php");
}
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");

$formulario_agregar = $_SERVER['PHP_SELF'];

$buscar = mysql_real_escape_string($_POST['palabra']);
$mostrar_datos_sql = "SELECT * FROM clientes WHERE cedula like '$buscar%' OR nombres like '$buscar%' OR apellidos like '$buscar%'order by nombres";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$matriz_consulta = mysql_fetch_assoc($consulta);

$max_cedula = "SELECT CONVERT(cedula, SIGNED INTEGER) AS cedula FROM clientes WHERE CONVERT(cedula, SIGNED INTEGER) ORDER BY cedula DESC LIMIT 0 , 1";
$consulta_cedula = mysql_query($max_cedula, $conectar) or die(mysql_error());
$datos_max_cedula = mysql_fetch_assoc($consulta_cedula);

$plus = $datos_max_cedula['cedula']+1;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<center>
<br>
<fieldset>
<td><strong><font color='yellow'>REGISTRAR CLIENTES </font></strong></td><br>
<form method="post" name="formulario" action="<?php echo $formulario_agregar; ?>">
<br>
<span id="envio_mensaje"></span>
<br>
<table align="center" width="95%">
<tr>
<td align="center"><strong>PLUS</strong></td>
<td align="center"><strong>CEDULA</strong></td>
<td align="center"><strong>NOMBRES</strong></td>
<td align="center"><strong>APELLIDOS</strong></td>
<td align="center"><strong>CORREO</strong></td>
<td align="center"><strong>DIRECCION</strong></td>
<td align="center"><strong>TELEFONO</strong></td>
<tr>
<td align="center"><font size='+2'><a href="<?php echo $formulario_agregar ?>?plus=<?php echo $plus?>"><?php echo $plus ?></a></font></td>
<td align="center"><input onblur="validar_cedula(this);" type="text" style="font-size:20px" name="cedula" value="<?php echo $_GET['plus'] ?>" id="cedula" size="20" required autofocus/></td>
<td align="center"><input type="text" style="font-size:20px" name="nombres" value="" size="20" required autofocus></td>
<td align="center"><input type="text" style="font-size:20px" name="apellidos" value="" size="20"></td>
<td align="center"><input type="text" style="font-size:20px" name="correo" value="" size="20"></td>
<td align="center"><input type="text" style="font-size:20px" name="direccion" value="" size="20"></td>
<td align="center"><input type="text" style="font-size:20px" name="telefono" value="" size="20"></td>
<input type="hidden" style="font-size:20px" name="pagina" value="<?php echo $formulario_agregar; ?>" size="20">
</tr>
</table>
<br>
<td bordercolor="1"><input type="submit" id="boton1" value="REGISTRAR"></td>
<input type="hidden" name="insertar_datos" value="formulario">
</form>
</fieldset>

<script src="prototype.js" type="text/javascript"></script>
<script type="text/javascript">
function validar_cedula(usuario)        {
var url = 'validar_cedula.php';
var parametros='cedula='+cedula.value;
var ajax = new Ajax.Updater('envio_mensaje',url,{method: 'get', parameters: parametros});
}
</script>
</body>
</html>
<?php
if (isset($_POST["nombres"])) {
$cedula = $_POST["cedula"];

$pagina = $_POST["pagina"];
$nombres0 = $_POST["nombres"];
$nombres1 = preg_replace("/,/", '.', $nombres0);
$nombres2 = preg_replace("/'/", ' .', $nombres1);
$nombres = strtoupper(preg_replace('/"/', ' .', $nombres2));

$apellidos0 = $_POST["apellidos"];
$apellidos1 = preg_replace("/,/", '.', $apellidos0);
$apellidos2 = preg_replace("/'/", ' .', $apellidos1);
$apellidos = strtoupper(preg_replace('/"/', ' .', $apellidos2));

$correo0 = $_POST["correo"];
$correo1 = preg_replace("/,/", '.', $correo0);
$correo2 = preg_replace("/'/", ' .', $correo1);
$correo = strtoupper(preg_replace('/"/', ' .', $correo2));

$direccion0 = $_POST["direccion"];
$direccion1 = preg_replace("/,/", '.', $direccion0);
$direccion2 = preg_replace("/'/", ' .', $direccion1);
$direccion = strtoupper(preg_replace('/"/', ' .', $direccion2));

$telefono = $_POST["telefono"];
// Hay campos en blanco
if($cedula==NULL || $nombres==NULL) {
echo "<font color='yellow'><br><strong>Ha dejado campos vacios.</strong></font>";
} else {
// Comprobamos si el cliente ya existe
$verificar_cliente = mysql_query("SELECT cedula FROM clientes WHERE cedula = '$cedula'");
$existencia_cliente = mysql_num_rows($verificar_cliente);
         
if ($existencia_cliente > 0) {
echo "<font color='yellow'><br>El Cliente <strong>".$cedula." </strong>ya existe, verifique en la lista de clientes.</font>";
?>
<META HTTP-EQUIV="REFRESH" CONTENT="2; <?php echo $pagina?>">
<?php
} else {

if ((isset($_POST["insertar_datos"])) && ($_POST["insertar_datos"] == "formulario")) {

$agregar_registros_sql1 = "INSERT INTO clientes (cedula, nombres, apellidos, correo, direccion, telefono) 
VALUES ('$cedula', UPPER('$nombres'), UPPER('$apellidos'), '$correo', '$direccion', '$telefono')";
$resultado_sql1 = mysql_query($agregar_registros_sql1, $conectar) or die(mysql_error());
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0.2; <?php echo $pagina?>">
<?php
echo "<font color='yellow'>Se ha ingresado correctamente el cliente <strong>".$nombres.' '.$apellidos.".</strong></font>";
}
//mysql_query($agregar_registros_sql1) or die(mysql_error());
}
}
}
?>
<td><strong><font color='yellow'>LISTA DE CLIENTES: </font></strong></td><br><br>
<form action="" method="POST">
<input name="palabra"/>
<input type="submit" name="buscador" value="Buscar" />
</form>
</center>
<table width="95%">
<tr>
<td align="center"><strong>ELM</strong></td>
<!--<td align="center"><strong>CODIGO</strong></td>-->
<td align="center"><strong>COD CLIENTE</strong></td>
<td align="center"><strong>CEDULA</strong></td>
<td align="center"><strong>NOMBRES</strong></td>
<td align="center"><strong>APELLIDOS</strong></td>
<td align="center"><strong>CORREO</strong></td>
<td align="center"><strong>DIRECCION</strong></td>
<td align="center"><strong>TELEFONO</strong></td>
<!--<td><strong>TOPE DEUDA</strong></td>-->
<td><strong>EDIT</strong></td></tr>
<?php do { ?>
<tr>
<td  nowrap><a href="../modificar_eliminar/eliminar_clientes.php?cod_clientes=<?php echo $matriz_consulta['cod_clientes']; ?>"><center><img src=../imagenes/eliminar.png alt="eliminar"></a></td>
<!--<td align="center"><?php echo $matriz_consulta['cod_clientes']; ?></td>-->
<td><?php echo $matriz_consulta['cod_clientes']; ?></td>
<td><?php echo $matriz_consulta['cedula']; ?></td>
<td><?php echo $matriz_consulta['nombres']; ?></td>
<td><?php echo $matriz_consulta['apellidos']; ?></td>
<td><?php echo $matriz_consulta['correo']; ?></td>
<td><?php echo $matriz_consulta['direccion']; ?></td>
<td align="right"><?php echo $matriz_consulta['telefono']; ?></td>
<!--<td><div align="right"><?php echo number_format($matriz_consulta['tope_deuda']); ?></td>-->
<td><a href="../modificar_eliminar/modificar_clientes.php?cod_clientes=<?php echo $matriz_consulta['cod_clientes']; ?>"><center><img src=../imagenes/actualizar.png alt="Actualizar"></a></td>
</tr>
<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
</table>
