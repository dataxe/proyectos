<?php
require('calendario.php');
$mes=$_GET['month'];
$anio=$_GET['year'];
$dia=1; 
calendar($mes,$anio);
?>

