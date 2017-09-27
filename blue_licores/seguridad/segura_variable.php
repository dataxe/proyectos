<?php
function seguridad_var($dato_seguro) {
	//htmlentities($dato_seguro, ENT_QUOTES, 'utf-8');
	//addslashes(trim($dato_seguro));
	mysql_real_escape_string($dato_seguro);
	return $dato_seguro;
}
?>