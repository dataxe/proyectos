<?php
$mostrar_datos_sql = "SELECT * FROM notificacion_alerta Order by rand() LIMIT 1";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($consulta)) {
$matriz_consulta = mysql_fetch_assoc($consulta);
}

$mensaje = $matriz_consulta['nombre_notificacion_alerta'];
$themessage = get_magic_quotes_gpc() ?
stripslashes(trim($mensaje)) :
trim($mensaje);
//$_SESSION['display'] = $themessage;

if(!empty($mensaje)) {
echo '<div id="alert">' . $mensaje . '</div>';
//unset($_SESSION['display']);
}
?>
<script type="text/javascript" src="js/alertas_jquery.min.js"></script>
<script type="text/javascript">
$(function () {
var $alert = $('#alert');
if($alert.length) {
var alerttimer = window.setTimeout(function () {
$alert.trigger('click');
}, 7000);
$alert.animate({height: $alert.css('line-height') || '50px'}, 200)
.click(function () {
window.clearTimeout(alerttimer);
$alert.animate({height: '0'}, 200);
});
}
});
</script>