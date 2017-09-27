<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<link href="../imagenes/icono.ico" type="image/x-icon" rel="shortcut icon" />
<title>GENERAR CONTRASE&Ntilde;A ENCRIP</title>
<style type="text/css"> <!--body { background-color: #333333;}--></style>
<br>
<form id="acceso" action="" method="post">
<fieldset>
<legend><font size="4"><font color="white">Generar Contrase&ntilde;a</font></legend><br>
<li>
<label for="password"><font color="white">Contrase&ntilde;a</font></label>
<input id="text" name="contrasena" type=password placeholder="Contrase&ntilde;a" required autofocus>
</li>
<br><br>
<input type="submit" id="boton1" value="Generar" />
<br /><br />
</body>
<?php
if (isset($_POST['contrasena'])) {
//usuario y clave pasados por el formulario
$clave = stripslashes($_POST['contrasena']);
$clave = strip_tags($clave);

$clave_encriptada = crypt($clave); //Encriptacion nivel 1

echo "<font color='white'>Contrasena: ".$clave."</font>";
echo "<br><br>";
echo "<font color='white'>Encriptacion: ".$clave_encriptada."</font>";
} else {

}
?>