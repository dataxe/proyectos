<style type="text/css"> <!--body { background-color: #333333;}--></style>
<center>
<link href="../imagenes/icono.ico" type="image/x-icon" rel="shortcut icon" />
<title>CONVERSION SEG A D/M/Y</title>
<br>
<a href="convertir_fecha_segundos.php"><font color='white'><strong>CONVERSION D/M/Y A SEGUNDOS</strong></font></a>
<br><br>
<form action="" method="post">
<input name="dato" />
<input type="submit" name="buscador" value="CONVERTIR DE SEGUNDOS A D/M/Y" />
</form>
<br>
</center>
<br>
<br>
<?php
if (isset($_POST['dato'])) {
$dato = $_POST['dato'];
/*
$frag = explode('/', $dato);

$dia = $frag[0];
$mes = $frag[1];
$anyo = $frag[2];
$integracion_ymd = $anyo.'/'.$mes.'/'.$dia;
*/
$dato_convertido = date("d/m/Y", $dato);
$dato_convertido2 = date("d/m/Y - H:i:s", $dato);

echo "<strong><font color='white'>FECHA ORIG: ".$dato."</font></strong>";
echo "<BR>";
echo "<strong><font color='white'>FECHA D/M/Y: ".$dato_convertido."</font></strong>";
echo "<BR>";
echo "<strong><font color='white'>FECHA D/M/Y - H:i:s: ".$dato_convertido2."</font></strong>";
}
?>