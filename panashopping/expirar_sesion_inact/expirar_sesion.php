<?php
session_start();
$fechaOld= $_SESSION["expirar_sesion"];
$ahora = date("Y-n-j H:i:s");
$tiempo_transcurrido = (strtotime($ahora)-strtotime($fechaOld));
     
if($tiempo_transcurrido >= 10) { //comparamos el tiempo y verificamos si pasaron 10 minutos o más
echo "<font color='white'>ya paso mas del tiempo indicado</font>";
//header("Location: ../session/salir_admin.php"); //enviamos al usuario a la página principal
} else {       //sino, actualizo la fecha de la sesión
$_SESSION["expirar_sesion"] = $ahora;
} 
?>

