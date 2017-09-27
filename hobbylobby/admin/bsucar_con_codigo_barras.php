<center>
<form action="../modificar_eliminar/agregar_lista_temporal.php" method="GET">
<td><strong><a href="cuentas_cobrar.php"><font color='white'>CUENTAS COBRAR</font></a></strong></td>&nbsp;&nbsp;&nbsp;&nbsp;
<td><strong><a href="temporal.php"><font color='white'>VENTA MANUAL</font></a></strong></td>&nbsp;&nbsp;&nbsp;&nbsp;
<td><strong><font color='yellow'>VENTA ELECTRONICA: </font></strong></td> <input name="cod_productos" id="foco" style="height:26" placeholder="C&oacute;digo del producto" required autofocus/>
<input type="hidden" name="cod_factura" value="<?php if ($cantidad_resultado == '1') { echo $info['cod_factura']; } else { echo $maximo['cod_factura']+1;}?>">
<input type="hidden" name="pagina" value="factura_eletronica.php">

<input type="submit" style="font-size:15px" name="buscador" tabindex=3 value="Vender Producto" />
</form>
</center>