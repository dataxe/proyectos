<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
$obtener_diseno = "SELECT estilo_css, cod_seguridad FROM administrador WHERE cuenta LIKE '$cuenta_actual'";
$resultado_diseno = mysql_query($obtener_diseno, $conectar) or die(mysql_error());
$matriz_diseno = mysql_fetch_assoc($resultado_diseno); ?>
<link rel="stylesheet" type="text/css" href="../estilo_css/<?php echo $matriz_diseno['estilo_css'];?>">
<?php 
if ($matriz_diseno['cod_seguridad'] == 3) { 
require_once('../plantillas/plantilla_principal.php');
} 
if ($matriz_diseno['cod_seguridad'] == 2) { 
require_once('../plantillas/plantilla_segundaria.php');
} 
if ($matriz_diseno['cod_seguridad'] == 1) {
require_once('../plantillas/plantilla_tercearia.php');
}
?>
<script>
window.onload = function() {
  document.getElementById("foco").focus();
}
</script>
<center>
 <form action="../admin/productos_busqueda_opciones.php" method="post">
<select name="opcion_busqueda">
  <option value="nombre_productos">Nombre</option>
  <option value="cod_productos" selected>Codigo</option>
  <option value="unidades_faltantes">Unidades faltantes</option>
  <option value="fechas">Fecha Insc</option>
  <option value="fechas_inventario">Fecha invent</option>
</select> 
<input id="foco" name="palabra"/>
      <input type="submit" name="buscador" value="Buscar"/>
</form>
</center>
