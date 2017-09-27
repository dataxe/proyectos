<style type="text/css"> <!--body { background-color: #333333;}--></style>
<center>
<link href="../imagenes/icono.ico" type="image/x-icon" rel="shortcut icon" />
<title>CONERSION D/M/Y A SEG</title>
<br>
<a href="conversion.php"><font color='white'><strong>CONVERTIR DE SEGUNDOS A D/M/Y</strong></font></a>
<br><br>
<form action="" method="post">
<input name="dato" />
<input type="submit" name="buscador" value="CONVERSION D/M/Y A SEGUNDOS" />
</form>
<br>
</center>
<br>
<br>
<?php
if (isset($_POST['dato'])) {
$dato = $_POST['dato'];
$frag = explode('/', $dato);

$dia = $frag[0];
$mes = $frag[1];
$anyo = $frag[2];
$integracion_ymd = $anyo.'/'.$mes.'/'.$dia;

$dato_convertido = strtotime($integracion_ymd);
}
if (isset($dato_convertido)) {

echo "<strong><font color='white'>FECHA ORIG: ".$dato."</font></strong>";
echo "<BR>";
echo "<strong><font color='white'>FECHA INVERT: ".$integracion_ymd."</font></strong>";
echo "<BR>";
echo "<strong><font color='white'>FECHA SEGUNDOS: ".$dato_convertido."</font></strong>";
echo "<BR>";
echo "<strong><font color='white'>TRANF: ".date("d/m/Y", $dato_convertido)."</font></strong>";
}
?>