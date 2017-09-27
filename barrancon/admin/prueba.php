<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 

$obtener_datos_filtro = mysql_query("SELECT * FROM productos WHERE nombre_productos = '$nombre_productos' AND cod_marcas = '$cod_marcas' 
AND cod_proveedores = '$cod_proveedores'");
$matriz_filtro = mysql_fetch_assoc($obtener_datos_filtro);
$existencia_de_datos = mysql_num_rows($obtener_datos_filtro);

$obtener_datos_filtro = mysql_query("SELECT nombre_productos, cod_marcas, cod_proveedores FROM productos WHERE nombre_productos = '$nombre_productos' AND cod_marcas = '$cod_marcas' 
AND cod_proveedores = '$cod_proveedores'");
$matriz_filtro = mysql_fetch_assoc($obtener_datos_filtro);
$existencia_de_datos = mysql_num_rows($obtener_datos_filtro);

   echo $nombre_productos = $_POST["nombre_productos"];
   echo "<br>".$cod_marcas = $_POST["cod_marcas"];
   echo "<br>".$cod_proveedores = $_POST["cod_proveedores"];
   echo "<br>".$existencia_de_datos;
?>
<form method="post" id="table" name="formulario" action="">

<td><input type="text" name="nombre_productos" value="" size="30" required autofocus></td>

<td><select name="cod_marcas">
<?php $sql_consulta="SELECT cod_marcas, nombre_marcas FROM marcas ORDER BY marcas.nombre_marcas ASC";
$resultado = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option value="<?php echo $contenedor['cod_marcas'] ?>"><?php echo $contenedor['nombre_marcas'] ?></option>
<?php }?>
</select></td>

<td><select name="cod_proveedores">
<?php $sql_consulta="SELECT cod_proveedores, nombre_proveedores FROM proveedores ORDER BY proveedores.nombre_proveedores ASC";
$resultado = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option value="<?php echo $contenedor['cod_proveedores'] ?>"><?php echo $contenedor['nombre_proveedores'] ?></option>
<?php }?>
</select></td>
<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td bordercolor="1"><input type="submit" id="boton1" value="Agregar"></td>
</tr>
</table>
<input type="hidden" name="insertar_datos" value="formulario">
</form>
</body>
</html>