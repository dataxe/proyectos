<?php
$unidades_nuevas = $_GET['unidades_nuevas'];

if($unidades_nuevas >= 0) {
} else {
?>
<img src=../imagenes/advertencia.gif alt='advertencia'> 
<strong><i><font color='yellow'>EL NUMERO DE UNIDADES ES NEGATIVA POR FAVOR SOLO INTRODUCIR UNIDADES POSITIVAS </i></font></strong> 
<img src=../imagenes/advertencia.gif alt='advertencia'>
<?php
}
?>