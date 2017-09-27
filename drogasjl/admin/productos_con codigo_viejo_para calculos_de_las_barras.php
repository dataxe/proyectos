<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php');
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");
date_default_timezone_set("America/Bogota");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
include ("../seguridad/seguridad_diseno_plantillas.php");
//include("../notificacion_alerta/mostrar_noficacion_alerta.php");
include ("../registro_movimientos/registro_movimientos.php");

$pagina = $_SERVER['PHP_SELF'];
//---------------------------------------------PARA CALCULAR PLUS BARRAS LARGA---------------------------------------------------------//
//---------------------------------------------PARA CALCULAR PLUS BARRAS LARGA---------------------------------------------------------//
$max_cod_barra_larga = "SELECT max(cod_productos_var) AS cod_productos_var FROM productos";
$consulta_max_cod_barra_larga = mysql_query($max_cod_barra_larga, $conectar) or die(mysql_error());
$datos_cod_barra_larga = mysql_fetch_assoc($consulta_max_cod_barra_larga);

$cod_barra_larga = $datos_cod_barra_larga['cod_productos_var'];
//---------------------------------------------PARA CALCULAR PLUS BARRAS CORTA---------------------------------------------------------//
//---------------------------------------------PARA CALCULAR PLUS BARRAS CORTA---------------------------------------------------------//
//---------------------- SE CONSULTAN SOLO LOS VALORES NUMERICOS DEL CAMPO cod_productos_var CON REGEXP '^[0-9]+$' ---------------------------//
$max_cod_barra_corta = "SELECT cod_productos_var FROM productos WHERE cod_productos_var REGEXP '^[0-9]+$' AND cod_productos_var <= 9999 ORDER BY cod_productos_var DESC LIMIT 0 , 1";
$consulta_max_cod_barra_corta = mysql_query($max_cod_barra_corta, $conectar) or die(mysql_error());
$datos_cod_barra_corta = mysql_fetch_assoc($consulta_max_cod_barra_corta);

$cod_barra_corta = $datos_cod_barra_corta['cod_productos_var'];
//------------------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------------------//
$tipo_barra = $_GET['tipo_barra'];
//--------------------------------------------------------------------//
//--------------------------CON ESTE CONDICIONAL CREAMOS LA BARRA LARGA------------------------------------//
if ($tipo_barra == '' || $tipo_barra == 'larga') {
//--------------------------------CON ESTE VERIFICAMOS QUE EL EL VALOR OBTENIDOS DE LA CONSULTA DE LA BARRA LARGA SEA UN NUMERO---------------------//
if (is_numeric($cod_barra_larga)) {
//--------------------------------AQUI INCREMENTAMOS EN UNO LA BARRA LARGA CUANDO ES UN NUMERO------------------------------------//
$plus = $cod_barra_larga+1;
}
else {
//--------SI EL VALOR QUE NOS DA LA CONSULTA NO ES NUMERO PORQ CONTIENE LETRAS ENTONCES CREAMOS UN VALOR CON LA FUNCION UNIQID------------//
$plus = strtoupper(uniqid());
}
}
//--------AQUI CREAMOS LA BARRA CORTA DE 4 DIGITOS APARTIR LA CONSULTA QUE SOLO MUESTRA LOS VALORES NUMERICOS DEL CAMPO cod_productos_var------//
else {
$plus = $cod_barra_corta+1;
}	
?>
<br>
<center>
<a href="../admin/cargar_factura_temporal.php"><font color='white'><strong>REGRESAR</font></strong></font></a></td><br><br>
<td><strong><font color='white'>REGISTRAR PRODUCTO NUEVO: </font></strong></td>
<form method="post" name="formulario" action="agregar_productos_nuevos.php">
<table align="center">
<span id="envio_mensaje"></span><br><br>
<tr>
<td align="center"></td>
<?php
//-------------HABILITAR ESTA OPCION CUANDO LA BARRAS ES LARGA----------------//
if ($tipo_barra == '' || $tipo_barra == 'larga') { ?>
<td align="center"><strong>PLUS 1</strong></td>
<?php
//-------------HABILITAR ESTA OPCION CUANDO LA BARRAS ES CORTA----------------//
} if ($tipo_barra == 'corta') { ?>
<td align="center"><strong>PLUS 2</strong></td>
<?php
}
?>
<td align="center"><strong>C&Oacute;D BARRAS</strong></td>
<td align="center"><strong>NOMBRE</strong></td>
<td align="center"><strong>MARCA</strong></td>
<td align="center"><strong>PRESENTACION</strong></td>
<td align="center"><strong>ESTANTE</strong></td>
<td align="center"><strong>P.COMPRA</strong></td>
<td align="center"><strong>P.VENTA</strong></td>
<td align="center"><strong>DESCRIPCION</strong></td>
</tr>
<tr>
<?php
//-------------HABILITAR ESTA OPCION CUANDO LA BARRAS ES LARGA----------------//
if ($tipo_barra == '' || $tipo_barra == 'larga') { ?>
<td align="center"><a href="<?php echo $pagina ?>?tipo_barra=<?php echo 'corta'?>"><img src=../imagenes/plus2.png alt="P2"></a></td>
<td align="center"><font size='+2'><a href="<?php echo $pagina ?>?plus=<?php echo $plus?>&tipo_barra=<?php echo 'larga'?>"><?php echo $plus ?></a></font></td>
<?php
//-------------HABILITAR ESTA OPCION CUANDO LA BARRAS ES CORTA----------------//
} if ($tipo_barra == 'corta') { ?>
<td align="center"><a href="<?php echo $pagina ?>?tipo_barra=<?php echo 'larga'?>"><img src=../imagenes/plus1.png alt="P1"></a></td>
<td align="center"><font size='+2'><a href="<?php echo $pagina ?>?plus=<?php echo $plus?>&tipo_barra=<?php echo 'corta'?>"><?php echo $plus ?></a></font></td>
<?php
}
?>
<td align="center"><input onblur="validar_codigo(this);" type="text" style="font-size:13px" name="cod_productos_var" value="<?php echo $_GET['plus'] ?>" id="cod_productos_var" size="12" required autofocus/></td>

<td align="center"><input type="text" style="font-size:16px" name="nombre_productos" value="" required autofocus></td>

<td align="center"><select name="cod_marcas">
<?php $sql_consulta="SELECT cod_marcas, nombre_marcas FROM marcas ORDER BY marcas.cod_marcas ASC";
$resultado = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option style="font-size:16px" value="<?php echo $contenedor['cod_marcas'] ?>"><?php echo $contenedor['nombre_marcas'] ?></option>
<?php }?>
</select></td>

<td align="center"><select name="cod_tipo">
<?php $sql_consulta="SELECT * FROM tipo ORDER BY cod_tipo ASC";
$resultado = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option style="font-size:16px" value="<?php echo $contenedor['cod_tipo'] ?>"><?php echo $contenedor['nombre_tipo'] ?></option>
<?php }?>
</select></td>

<td align="center"><select name="cod_nomenclatura">
<?php $sql_consulta="SELECT * FROM nomenclatura ORDER BY cod_nomenclatura ASC";
$resultado = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option style="font-size:16px" value="<?php echo $contenedor['cod_nomenclatura'] ?>"><?php echo $contenedor['nombre_nomenclatura'] ?></option>
<?php }?>
</select></td>

<td align="center"><input type="text"style="font-size:16px" name="precio_compra" value="" size="5" required autofocus></td>
<td align="center"><input type="text" style="font-size:16px" name="precio_venta" value="" size="4" required autofocus></td>
<td align="center"><input type="text" style="font-size:16px" name="descripcion" value="" size="12"></td>

<input type="hidden" style="font-size:16px" name="porcentaje_vendedor" value="NO" size="2">
<input type="hidden" style="font-size:16px" name="fechas_vencimiento" value="0" size="10">
<input type="hidden" style="font-size:16px" name="unidades" value="0" size="10">
<input type="hidden" style="font-size:16px" name="cajas" value="1" size="10">
<input type="hidden" style="font-size:16px" name="unidades_total" value="0" size="10">
<input type="hidden" style="font-size:16px" name="und_orig" value="0" size="10">
<input type="hidden" name="cod_paises" value="1" size="30" required autofocus>
<input type="hidden" name="cod_original" value="" size="4">
<input type="hidden" name="tope_minimo" value="1" size="2">
<input type="hidden" name="iva" value="0" size="1">
<input type="hidden" name="flete" value="0" size="1">
<input type="hidden" name="ptj_ganancia" value="0" size="1">
</tr>
</table>
<td bordercolor="1"><input type="submit" id="boton1" value="REGISTRAR"></td>
<input type="hidden" name="insertar_datos" value="formulario">
</form>
</center>

<script src="prototype.js" type="text/javascript"></script>
<script type="text/javascript">
function validar_codigo(usuario)        {
var url = 'validar_codigo_barras.php';
var parametros='cod_productos_var='+cod_productos_var.value;
var ajax = new Ajax.Updater('envio_mensaje',url,{method: 'get', parameters: parametros});
}
</script>
</body>
</html>