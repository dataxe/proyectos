<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<link href="../imagenes/icono.ico" type="image/x-icon" rel="shortcut icon" />
<title>GENERAR CONTRASE&Ntilde;A ENCRIP</title>
<style type="text/css"> <!--body { background-color: #333333;}--></style>
<br>
<form id="acceso" action="" method="post">
<fieldset><legend><font size="4"><font color="white">Generar Contrase&ntilde;a</font></legend><br>

<td><a href="../admin/ecryp.php?tipo=md5"><font color='white'>MD5</font></a></td>
<br>
<td><a href="../admin/ecryp.php?tipo=sha1"><font color='white'>SHA1</font></a></td>
<br><br>
<?php
if (isset($_GET['tipo'])) {

if ($_GET['tipo'] == 'md5') {?>
<label for="password"><font color="white">Contrase&ntilde;a md5</font></label>
<input id="text" name="contrasena" type=password placeholder="Contrase&ntilde;a md5" required autofocus>

<input type="submit" id="boton1" value="Generar" />
<br><br>
</body>
<?php
if (isset($_POST['contrasena'])) {
//usuario y clave pasados por el formulario
$clave = stripslashes($_POST['contrasena']);
$clave = strip_tags($clave);

$clave_encriptada = md5($clave); //Encriptacion nivel 1

echo "<font color='white'>Contrasena: ".$clave."</font>";
echo "<br><br>";
echo "<font color='white'>Encriptacion: ".$clave_encriptada."</font>";
} else {
}

}
if ($_GET['tipo'] == 'sha1') {?>
<label for="password"><font color="white">Contrase&ntilde;a sha1</font></label>
<input id="text" name="contrasena" type=password placeholder="Contrase&ntilde;a sha1" required autofocus>

<input type="submit" id="boton1" value="Generar" />
<br><br>
</body>
<?php
if (isset($_POST['contrasena'])) {
//usuario y clave pasados por el formulario
$clave = stripslashes($_POST['contrasena']);
$clave = strip_tags($clave);

$clave_encriptada = sha1($clave); //Encriptacion nivel 1

echo "<font color='white'>Contrasena: ".$clave."</font>";
echo "<br><br>";
echo "<font color='white'>Encriptacion: ".$clave_encriptada."</font>";
} else {
}
}
}
?>