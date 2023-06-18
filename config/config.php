<?php

//Configuracion del sistema
define("SITE_URL", "http://localhost/pasarela");
define("KEY_TOKEN", "ABC.wqc-354*");
define("MONEDA", "$");



//Configuracion de Paypal
define("CLIENT_ID", "AbalghECYa8q2MJ7-MMr6g7fSvz3xq02Vx4DTds9hnTAS9iiuR4gtgNTpDvMI-iX5El9oBk45q25j_9b");


//Configuracion de Mercado Pago
define("TOKEN_MP", "TEST-927378699045220-121410-b696bb65dce656d4fbcba8565565d92e-500492562");




//Datos para envio de correo electronico
define("MAIL_HOST", "wamnerluna@gmail.com");
define("MAIL_USER", "wamnerluna@gmail.com");
define("MAIL_PASS", "tu_password");
define("MAIL_PORT", "465");



session_start();

$num_cart =0;
if(isset($_SESSION['carrito']['productos'])){
	$num_cart = count($_SESSION['carrito']['productos']);
}

?>