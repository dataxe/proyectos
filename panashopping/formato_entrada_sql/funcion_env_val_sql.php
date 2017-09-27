<?php
function envio_valores_tipo_sql($valor_sql_func, $tipo, $valor_definido = "", $valor_no_definido = "") {
  $valor_sql_func = (!get_magic_quotes_gpc()) ? addslashes($valor_sql_func) : $valor_sql_func;

  switch ($tipo) {
    case "text":
      $valor_sql_func = ($valor_sql_func != "") ? "'" . $valor_sql_func . "'" : "NULL";
      break;    
    case "largo":
    case "entero":
      $valor_sql_func = ($valor_sql_func != "") ? intval($valor_sql_func) : "NULL";
      break;
    case "doble":
      $valor_sql_func = ($valor_sql_func != "") ? "'" . doubleval($valor_sql_func) . "'" : "NULL";
      break;
    case "dato":
      $valor_sql_func = ($valor_sql_func != "") ? "'" . $valor_sql_func . "'" : "NULL";
      break;
    case "definido":
      $valor_sql_func = ($valor_sql_func != "") ? $valor_definido : $valor_no_definido;
      break;
  }
  return $valor_sql_func;
}
?>