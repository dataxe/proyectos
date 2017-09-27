<?php
ini_set("session.use_trans_sid','0");
ini_set("session.use_only_cookies','1");
session_set_cookie_params(0, "/", $HTTP_SERVER_VARS["HTTP_HOST"], 0);
?>

