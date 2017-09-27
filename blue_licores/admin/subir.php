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
/*
$verificar_pag = "SELECT * FROM pagina_actual WHERE cod_pagina_actual='1'";
$resultado_verificar_pag = mysql_query($verificar_pag, $conectar) or die(mysql_error());

$nombre_pagina_actual = 'DISEÑOS';
if ($resultado_verificar_pag['nombre_pagina_actual'] <> $nombre_pagina_actual) {
$actualizar_nombre_pagina_actual = sprintf("UPDATE pagina_actual SET nombre_pagina_actual=%s WHERE cod_pagina_actual='1'",
envio_valores_tipo_sql($nombre_pagina_actual, "text"), envio_valores_tipo_sql($_POST['cod_pagina_actual'], "text"));
$resultado_actualizacion_pagina_actual = mysql_query($actualizar_nombre_pagina_actual, $conectar) or die(mysql_error());
}
*/
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../estilo_css/estilo.css">
<title>ALMACEN</title>
<meta charset="UTF-8">
</head>
<body>
<div class="subir">
<form action="<?=$PHP_SELF?>" method="post" enctype="multipart/form-data" name="form1">
<br><br>Sube un archivo:
<input type="file" name="archivo" id="archivo" /> <br> 
<input type="submit" name="boton" value="Subir" />
</form>
</div>
</body>
</html>
<div class="resultado">
<?
if($boton) {
if (is_uploaded_file($HTTP_POST_FILES['archivo']['tmp_name'])) {
copy($HTTP_POST_FILES['archivo']['tmp_name'], "../estilo_css/" . $HTTP_POST_FILES['archivo']['name']);
$subio = true;
}
if($subio) {
echo "<font color='yellow'>El archivo se subio con exito.</font>";
} else {
echo "<font color='yellow'>El archivo no cumple con las reglas establecidas</font>";	
}
die();
}
?>
</div>
