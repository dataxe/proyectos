<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
include ("../seguridad/seguridad_diseno_plantillas.php");
include ("../formato_entrada_sql/funcion_env_val_sql.php");
/*
$verificar_pag = "SELECT * FROM pagina_actual WHERE cod_pagina_actual='1'";
$resultado_verificar_pag = mysql_query($verificar_pag, $conectar) or die(mysql_error());

$nombre_pagina_actual = 'FACTURACIÓN';
if ($resultado_verificar_pag['nombre_pagina_actual'] <> $nombre_pagina_actual) {
$actualizar_nombre_pagina_actual = sprintf("UPDATE pagina_actual SET nombre_pagina_actual=%s WHERE cod_pagina_actual='1'",
envio_valores_tipo_sql($nombre_pagina_actual, "text"), envio_valores_tipo_sql($_POST['cod_pagina_actual'], "text"));
$resultado_actualizacion_pagina_actual = mysql_query($actualizar_nombre_pagina_actual, $conectar) or die(mysql_error());
}
*/
$buscar = $_POST['palabra'];
$mostrar_datos_sql = "SELECT * FROM marcas WHERE nombre_marcas  like '%$buscar%' order by marcas.cod_marcas";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$matriz_consulta = mysql_fetch_assoc($consulta);

$obtener_factur = "SELECT cod_factura, numero_factura FROM factura_cod WHERE cod_factura = 1";
$modificar_factur = mysql_query($obtener_factur, $conectar) or die(mysql_error());
$contenedor_factur = mysql_fetch_assoc($modificar_factur);
$num_factur = mysql_num_rows($modificar_factur);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>ALMACEN</title>

</head>
<body>
<p>
<script>
window.onload = function() {
  document.getElementById("cod_factur").focus();
}
</script>
<center>
<?php do { ?>

<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
<form method="post" name="formulario" action="<?php echo $formulario_agregar; ?>">
  <table align="center" >
<tr>
<td><div align="center"><strong>COD FACTURA</strong></div></td>
<td><div align="center"><strong>PRODUCTO</strong></div></td>
<td><div align="center"><strong>PRECIO</strong></div></td><?php do { ?>
<tr>
<td><input type="text" id="cod_factur" name="cod_facturacion" value="<?php echo $contenedor_factur['numero_factura'];?>" size="13"></td>
<td><input type="text" name="producto" value="MANO DE OBRA" size="16"></td>
<td><input type="text" name="precio" value="" size="12"></td>
<input type="hidden" name="cantidad" value="1" size="13">
<input type="hidden" name="cod_producto" value="." size="13">
<input type="hidden" name="marca" value="." size="13">
<input type="hidden" name="descripcion" value="." size="13">
<input type="hidden" name="fecha" value="<?php echo date("d/m/Y");?>" size="13">
</tr>
    <?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
<tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
<td bordercolor="1"><input type="submit" id="boton1" value="Agregar"></td>
    </tr>
  </table>
  <input type="hidden" name="insertar_datos" value="formulario">
</form>
</body>
</html>
<?php mysql_free_result($consulta);?>

<?php if (isset($_POST["producto"])) {
   $cod_facturacion = $_POST["cod_facturacion"];
   $producto = $_POST["producto"];
   $precio = $_POST["precio"];
   
   // Hay campos en blanco
   if($cod_facturacion==NULL || $producto==NULL || $precio==NULL) {
      echo "<font color='white'><br><br>Ha dejado algo vacio.</font>";
       }else{
if ((isset($_POST["insertar_datos"])) && ($_POST["insertar_datos"] == "formulario")) {
$agregar_registros_sql1 = sprintf("INSERT INTO facturacion (cod_factura, cod_producto, cod_facturacion, nombre_productos, vlr_unitario, vlr_total, marca, descripcion, fecha, cantidad) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       envio_valores_tipo_sql($_POST['cod_factura'], "text"),
					   envio_valores_tipo_sql($_POST['cod_producto'], "text"),
					   envio_valores_tipo_sql($_POST['cod_facturacion'], "text"),
					   envio_valores_tipo_sql($_POST['producto'], "text"),
					   envio_valores_tipo_sql($_POST['precio'], "text"),
					   envio_valores_tipo_sql($_POST['precio'], "text"),
					   envio_valores_tipo_sql($_POST['marca'], "text"),
					   envio_valores_tipo_sql($_POST['descripcion'], "text"),
					   envio_valores_tipo_sql($_POST['fecha'], "text"),
     				   envio_valores_tipo_sql($_POST['cantidad'], "text"));
					   
$resultado_sql1 = mysql_query($agregar_registros_sql1, $conectar) or die(mysql_error());
echo '<META HTTP-EQUIV="REFRESH" CONTENT="1; ../admin/facturacion.php">';
echo "<font color='white'>Se ha ingresado correctamente</strong></font>";
}
//mysql_query($agregar_registros_sql1) or die(mysql_error());
      }
   }
?>