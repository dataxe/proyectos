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
//include ("../registro_movimientos/registro_cierre_caja.php");

function formulario_de_registro(){
?>
<title>ALMACEN</title>
<head><link rel="stylesheet" type="text/css" href="../estilo_css/estilo_acceso.css">
<br>
<form id="acceso" action="" method="post">
<legend><font size="4">FORMULARIO DE REGISTRO</font></legend>
<br>
<li>
<label for="nombre">Nombres</label>
<input id="nombres" name="nombres" type=text placeholder="Escribe tus nombres" required autofocus>
</li>
<br>
<li>
<label for="nombre">Apellidos</label>
<input id="apellidos" name="apellidos" type=text placeholder="Escribe tus apellidos" required autofocus>
</li>
<br>
<li>
<label for="nombre">Sexo</label>
<select id="acceso" name="sexo">
<option value="O">Masculino</option>
<option value="A">Femenino</option>
</select> 
</li>
<br>
<li>
<label for="nombre">Usuario</label>
<input id="nombre_usuario" name="nombre_usuario" type=text placeholder="Escribe tu nombre de usuario" required autofocus>
</li>
<br>
<li>
<label for="nombre">Nivel Seguridad</label>
<select id="acceso" name="cod_seguridad">
<option value="1">Vendedor</option>
<option value="2">Administrador</option>
<option value="3">Super administrador</option>
</select> 
</li>
<br>
<li>
<label for="nombre">Contrase&ntilde;a</label>
<input id="contrasena1" name="contrasena1" type=password placeholder="Escribe tu contrase&ntilde;a" required autofocus>
</li>
<br>
<li>
<label for="nombre">Contrase&ntilde;a</label>
<input id="contrasena2" name="contrasena2" type=password placeholder="Repita la contrase&ntilde;a" required autofocus>
</li>
<br>
<li>
<label for="email">Email</label>
<input id="correo" name="correo" type="email" placeholder="ejemplo@dominio.com">
<input id="diseno" name="diseno" type="hidden" value="azul_verdoso.css">
</li>
<br><br>
 <input type="submit" id="submit" value="Registrar" />
<?php
}
// verificamos si se han enviado ya las variables necesarias.
if (isset($_POST["nombre_usuario"])) {
$nombres = strtoupper($_POST["nombres"]);
$apellidos = strtoupper($_POST["apellidos"]);
//$cuenta = $_POST["nombre_usuario"];
$cuenta = stripslashes($_POST["nombre_usuario"]);
$cuenta = strip_tags($cuenta);

$contrasena1 = stripslashes($_POST['contrasena1']);
$contrasena2 = stripslashes($_POST["contrasena2"]);
$contrasena1 = strip_tags($contrasena1);
$contrasena2 = strip_tags($contrasena2);

$clave_encriptada = sha1($contrasena1); //Encriptacion nivel 1
//$clave_encriptada2 = crc32($clave_encriptada1); //Encriptacion nivel 1
//$clave_encriptada3 = crypt($clave_encriptada2, "xtemp"); //Encriptacion nivel 2
//$clave_encriptada = sha1("xtemp".$clave_encriptada3); //Encriptacion nivel 3
$sexo = $_POST["sexo"];
$correo = $_POST["correo"];
$diseno = $_POST["diseno"];
$cod_seguridad = $_POST["cod_seguridad"];
$fecha_hora = date("H:i:s");
$fecha = date("d-m-Y");
$pagina = $_SERVER['PHP_SELF'];
// Hay campos en blanco
if($nombres==NULL || $apellidos==NULL || $cuenta==NULL || $contrasena1==NULL || $contrasena2==NULL || $cod_seguridad==NULL) {
formulario_de_registro();
echo "<font color='yellow' size='3' align='left'><br><br><strong><font color='yellow'><center> <img src=../imagenes/advertencia.gif alt='Advertencia'> Faltan campos por llenar. <img src=../imagenes/advertencia.gif alt='Advertencia'><font color='yellow'><center></strong></font>";
?>
<META HTTP-EQUIV="REFRESH" CONTENT="4; <?php echo $pagina;?>">
<?php
} else {
// ¿Coinciden las contraseñas?
if($contrasena1 != $contrasena2) {
formulario_de_registro();
echo "<br><br><font color='yellow' size='3' align='left'><strong><font color='yellow'><center><img src=../imagenes/advertencia.gif alt='Advertencia'> Las contrase&ntilde;as no coinciden. <img src=../imagenes/advertencia.gif alt='Advertencia'><font color='yellow'><center></strong></font>";
?>
<META HTTP-EQUIV="REFRESH" CONTENT="3; <?php echo $pagina;?>">
<?php
} else {
if($cod_seguridad < 1 || $cod_seguridad > 3) {
formulario_de_registro();
echo "<font color='yellow' size='3' align='left'><br><br><font color='yellow'><center><img src=../imagenes/advertencia.gif alt='Advertencia'> El codigo para el nivel de seguridad es invalido. <img src=../imagenes/advertencia.gif alt='Advertencia'><font color='yellow'><center></font>";
?>
<META HTTP-EQUIV="REFRESH" CONTENT="4; <?php echo $pagina;?>">
<?php
}
else {
// Comprobamos si el nombre de usuario o la cuenta de correo ya existían
$verificar_cuenta = mysql_query("SELECT cuenta FROM administrador WHERE cuenta = '$cuenta'");
$existenciar_cuenta = mysql_num_rows($verificar_cuenta);
/*
$verificar_correo = mysql_query("SELECT correo FROM administrador WHERE correo='$correo'");
$existencia_correo = mysql_num_rows($verificar_correo);
*/
if ($existenciar_cuenta>0) {
formulario_de_registro();
echo "<br><br><font color='yellow' size='3' align='left'><strong><font color='yellow'><center><img src=../imagenes/advertencia.gif alt='Advertencia'> El nombre de la cuenta ya esta en uso. <img src=../imagenes/advertencia.gif alt='Advertencia'><font color='yellow'><center></strong></font>";
?>
<META HTTP-EQUIV="REFRESH" CONTENT="4; <?php echo $pagina;?>">
<?php
} else {
$consulta = 'INSERT INTO administrador (nombres, apellidos, sexo, cuenta, contrasena, correo, cod_seguridad, estilo_css, creador, fecha_hora, fecha)
VALUES (\''.$nombres.'\',\''.$apellidos.'\',\''.$sexo.'\',\''.$cuenta.'\',\''.$clave_encriptada.'\',\''.$correo.'\',\''.$cod_seguridad.'\',\''.$diseno.'\',\''.$cuenta_actual.'\',\''.$fecha_hora.'\',\''.$fecha.'\')';
			
mysql_query($consulta) or die(mysql_error());
formulario_de_registro();
echo "<br><br><font color='yellow' size='3' align='left'><strong>$nombres $apellidos </strong> HA SIDO REGISTRAD$sexo DE MANERA SATISFACTORIA.</font>";
?>
<META HTTP-EQUIV="REFRESH" CONTENT="5; <?php echo $pagina;?>">
<?php
         }
      }
   }
 }
}else {
formulario_de_registro();
}
?>
</form>