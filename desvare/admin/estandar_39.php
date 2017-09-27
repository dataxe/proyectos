<?php
// include Barcode39 class
include ("estandar_code39/Barcode39.php");

$cod_productos = $_GET['cod_productos'];

$bc = new Barcode39($cod_productos);
// set text size
$bc->barcode_text_size = 5;
// set barcode bar thickness (thick bars)
$bc->barcode_bar_thick = 4;
// set barcode bar thickness (thin bars)
$bc->barcode_bar_thin = 2;
// save barcode GIF file
$bc->draw();
?>