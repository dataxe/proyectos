<?php
require_once "Barcode.php";
class Image_Barcode_code128 extends Image_Barcode {
var $estandar = 'code128';
var $altura_barras = 80;
var $tam_letra = 1;
var $_ancho_barras = 1;
var $code;

function &draw($cod_productos, $nombre_productos, $descripcion, $imagen_tipo = 'png') {
$iniciar_cod= $this->getStartCode();
$verificar_caracter = 104;
$todas_barras = $iniciar_cod;
$barras = '';
for ($i=0; $i < strlen($cod_productos); ++$i) {
$caracter = $cod_productos[$i];
$val = $this->getCharNumber($caracter);
$verificar_caracter += ($val * ($i + 1));
$barras = $this->getCharCode($caracter);
$todas_barras = $todas_barras . $barras;
}
$verificar_digito = $verificar_caracter % 103;
$barras = $this->getNumCode($verificar_digito);
$stopcode = $this->getStopCode();
$todas_barras = $todas_barras . $barras . $stopcode;
//------------------------ ANCHO DE LA IMAGEN ---------------------------//
$ancho_imagen = 100;
//-----------------------------------------------------------------------//
for ($i=0; $i < strlen($todas_barras); ++$i) {
$nval = $todas_barras[$i];
$ancho_imagen += ($nval * $this->_ancho_barras);
}
$longitudaltura_barras = (int) (imagefontheight($this->tam_letra) / 10) + $this->altura_barras;
//----------------------------- AUMENTAR LA ALTURA DE LA IMAGEN imagefontheight($this->tam_letra)+30) -----------------------------//
$imagen = ImageCreate($ancho_imagen, $longitudaltura_barras+ imagefontheight($this->tam_letra)+30);
$color_negro = ImageColorAllocate($imagen, 0, 0, 0);
$color_blanco = ImageColorAllocate($imagen, 255, 255, 255);
imagefill($imagen, 0, 0, $color_blanco);
//--------------------------- PARA AGREGAR EL CODIGO A LA IMAGEN -------------------------//
imagestring(
$imagen,
$this->tam_letra,
$ancho_imagen / 2 - strlen($cod_productos) / 2 * (imagefontwidth($this->tam_letra)),
$this->altura_barras + imagefontheight($this->tam_letra) / 1.5,
$cod_productos,
$color_negro);
//------------------------- PARA AGREGAR EL NOMBRE DEL PRODUCTO A LA IMAGEN ---------------------------//
imagestring(
$imagen,
$this->tam_letra,
$ancho_imagen / 2 - strlen($nombre_productos) / 2.3 * (imagefontwidth($this->tam_letra)),
$this->altura_barras + imagefontheight($this->tam_letra) / 0.53,
$nombre_productos,
$color_negro);
//------------------------------- PARA AGREGAR LA DESCRIPCION DEL PRODUCTO A LA IMAGEN---------------------------------//
imagestring(
$imagen,
$this->tam_letra,
$ancho_imagen / 2 - strlen($descripcion) / 2.5 * (imagefontwidth($this->tam_letra)),
$this->altura_barras + imagefontheight($this->tam_letra) / 0.3,
$descripcion,
$color_negro);
//---------------------------------- POSICION DEL CODIGO DE BARRAS EN EL EJE X-------------------------------//
$posicion_barras_x = 50;
//---------------------------------------------------------------------------------------------//
$bar = 1;
for ($i=0; $i < strlen($todas_barras); ++$i) {
$nval = $todas_barras[$i];
$ancho = $nval * $this->_ancho_barras;
if ($bar==1) {
imagefilledrectangle($imagen, $posicion_barras_x, 0, $posicion_barras_x + $ancho-1, $longitudaltura_barras, $color_negro);
$posicion_barras_x += $ancho;
$bar = 0;
} else {
$posicion_barras_x += $ancho;
$bar = 1;
}
}
return $imagen;
}
function Image_Barcode_code128() {
        $this->code[0] = "212222";  // " "
        $this->code[1] = "222122";  // "!"
        $this->code[2] = "222221";  // "{QUOTE}"
        $this->code[3] = "121223";  // "#"
        $this->code[4] = "121322";  // "$"
        $this->code[5] = "131222";  // "%"
        $this->code[6] = "122213";  // "&"
        $this->code[7] = "122312";  // "'"
        $this->code[8] = "132212";  // "("
        $this->code[9] = "221213";  // ")"
        $this->code[10] = "221312"; // "*"
        $this->code[11] = "231212"; // "+"
        $this->code[12] = "112232"; // ","
        $this->code[13] = "122132"; // "-"
        $this->code[14] = "122231"; // "."
        $this->code[15] = "113222"; // "/"
        $this->code[16] = "123122"; // "0"
        $this->code[17] = "123221"; // "1"
        $this->code[18] = "223211"; // "2"
        $this->code[19] = "221132"; // "3"
        $this->code[20] = "221231"; // "4"
        $this->code[21] = "213212"; // "5"
        $this->code[22] = "223112"; // "6"
        $this->code[23] = "312131"; // "7"
        $this->code[24] = "311222"; // "8"
        $this->code[25] = "321122"; // "9"
        $this->code[26] = "321221"; // ":"
        $this->code[27] = "312212"; // ";"
        $this->code[28] = "322112"; // "<"
        $this->code[29] = "322211"; // "="
        $this->code[30] = "212123"; // ">"
        $this->code[31] = "212321"; // "?"
        $this->code[32] = "232121"; // "@"
        $this->code[33] = "111323"; // "A"
        $this->code[34] = "131123"; // "B"
        $this->code[35] = "131321"; // "C"
        $this->code[36] = "112313"; // "D"
        $this->code[37] = "132113"; // "E"
        $this->code[38] = "132311"; // "F"
        $this->code[39] = "211313"; // "G"
        $this->code[40] = "231113"; // "H"
        $this->code[41] = "231311"; // "I"
        $this->code[42] = "112133"; // "J"
        $this->code[43] = "112331"; // "K"
        $this->code[44] = "132131"; // "L"
        $this->code[45] = "113123"; // "M"
        $this->code[46] = "113321"; // "N"
        $this->code[47] = "133121"; // "O"
        $this->code[48] = "313121"; // "P"
        $this->code[49] = "211331"; // "Q"
        $this->code[50] = "231131"; // "R"
        $this->code[51] = "213113"; // "S"
        $this->code[52] = "213311"; // "T"
        $this->code[53] = "213131"; // "U"
        $this->code[54] = "311123"; // "V"
        $this->code[55] = "311321"; // "W"
        $this->code[56] = "331121"; // "X"
        $this->code[57] = "312113"; // "Y"
        $this->code[58] = "312311"; // "Z"
        $this->code[59] = "332111"; // "["
        $this->code[60] = "314111"; // "\"
        $this->code[61] = "221411"; // "]"
        $this->code[62] = "431111"; // "^"
        $this->code[63] = "111224"; // "_"
        $this->code[64] = "111422"; // "`"
        $this->code[65] = "121124"; // "a"
        $this->code[66] = "121421"; // "b"
        $this->code[67] = "141122"; // "c"
        $this->code[68] = "141221"; // "d"
        $this->code[69] = "112214"; // "e"
        $this->code[70] = "112412"; // "f"
        $this->code[71] = "122114"; // "g"
        $this->code[72] = "122411"; // "h"
        $this->code[73] = "142112"; // "i"
        $this->code[74] = "142211"; // "j"
        $this->code[75] = "241211"; // "k"
        $this->code[76] = "221114"; // "l"
        $this->code[77] = "413111"; // "m"
        $this->code[78] = "241112"; // "n"
        $this->code[79] = "134111"; // "o"
        $this->code[80] = "111242"; // "p"
        $this->code[81] = "121142"; // "q"
        $this->code[82] = "121241"; // "r"
        $this->code[83] = "114212"; // "s"
        $this->code[84] = "124112"; // "t"
        $this->code[85] = "124211"; // "u"
        $this->code[86] = "411212"; // "v"
        $this->code[87] = "421112"; // "w"
        $this->code[88] = "421211"; // "x"
        $this->code[89] = "212141"; // "y"
        $this->code[90] = "214121"; // "z"
        $this->code[91] = "412121"; // "{"
        $this->code[92] = "111143"; // "|"
        $this->code[93] = "111341"; // "}"
        $this->code[94] = "131141"; // "~"
        $this->code[95] = "114113"; // 95
        $this->code[96] = "114311"; // 96
        $this->code[97] = "411113"; // 97
        $this->code[98] = "411311"; // 98
        $this->code[99] = "113141"; // 99
        $this->code[100] = "114131"; // 100
        $this->code[101] = "311141"; // 101
        $this->code[102] = "411131"; // 102
}
function getCharCode($c) {
$retval = $this->code[ord($c) - 32];
return $retval;
}
function getStartCode() {
return '211214';
}
function getStopCode() {
return '2331112';
}
function getNumCode($index) {
$retval = $this->code[$index];
return $retval;
}
function getCharNumber($c) {
$retval = ord($c) - 32;
return $retval;
}
} 
?>