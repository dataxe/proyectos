<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
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
?>
<script type="text/javascript" src="busqueda_inmediata_compra_producto_historial_ajax.js"></script>
</head>
<body>
<center>
<br>
<td><strong><font color='yellow'>HISTORIAL DE COMPRA PRODUCTO: </font></strong></td> <input type="text" id="busqueda" name="busqueda" onkeyup="hacer_busqueda()" style="height:26" required placeholder="Buscar" required autofocus/>
<div id="logo_cargador"></div>
</center>