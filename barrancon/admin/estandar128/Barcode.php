<?php
require_once 'PEAR.php';
class Image_Barcode extends PEAR {
function &draw($cod_productos, $nombre_productos, $descripcion, $tipo_estdar='code128', $imag_tipo='png', $altura=40, $ancho_barras=1) {
include_once('code128.php');

//Image_Barcode_code128() es tomado de la clase code128.php ubicada en Image/Barcode/
@$objeto = & new Image_Barcode_code128();
if (isset($objeto->_altura_barras)) 
$objeto->_altura_barras = $altura;

if (isset($objeto->_ancho_barras)) 
$objeto->_ancho_barras = $ancho_barras;
$imagen = &$objeto->draw($cod_productos, $nombre_productos, $descripcion, $imag_tipo);

switch ($imag_tipo) {
default:
header("Content-type: image/png");
//header("Content-type: text/html"); 
//header('Content-type: text');
imagepng($imagen);
imagedestroy($imagen);
break;
}
return $imagen;
}
}
?>
