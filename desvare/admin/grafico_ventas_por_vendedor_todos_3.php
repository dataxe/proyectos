<?php error_reporting(E_ALL ^ E_NOTICE);
require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
$cuenta_actual = addslashes($_SESSION['usuario']);
include("../session/funciones_admin.php");
//include("../notificacion_alerta/mostrar_noficacion_alerta.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
  } else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
include ("../seguridad/seguridad_diseno_plantillas.php");
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");

require_once("menu_estadisticas.php");
include ("menu_grafico_ventas_vendedor.php");
?>
<br>
<center>
<form action="" method="post">
<?php
$sql = "SELECT cuenta FROM administrador ORDER BY cuenta ASC";
$result = mysql_query($sql, $conectar) or die(mysql_error());
$total = mysql_num_rows($result);
while ($registros = mysql_fetch_array($result)) {
?>
<input name="cuenta[]" value="<?php echo $registros['cuenta'] ?>">
<?php
}
?>
<input name="total" value="<?php echo $total ?>">
<input type="submit" name="buscador" value="GRAFICAR TODOS LOS VENDEDORES"/>
</form>
</center>

<?php
if (isset($_POST['cuenta'])) {

$total_datos = $_POST["total"];

for ($i=0; $i < $total_datos; $i++) { 
$cuenta = $_POST['cuenta'][$i];

echo "<br>".$cuenta;
}
}
?>
	</body>
</html>
