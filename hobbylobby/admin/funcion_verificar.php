<?php
class bloquear_multiple_intento {
function iniciar() {?>
<input type='hidden' name='verificador' value='<?php echo md5(uniqid(rand(), true))?>'>
<?php
}
function bloquear($verificador) {
session_start();
if(isset($_SESSION['verificador'])) {
if ($verificador == $_SESSION['verificador']) {
return false;
} else {
$_SESSION['verificador'] = $verificador;
return true;
}
} else {
$_SESSION['verificador'] = $verificador;
return true;
}
}
}
?>