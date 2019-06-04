<?php
define('ENVIRONMENT', 'development');
$domain = 'http://localhost:8000';
$domain_cookie = 'localhost';
$domain_name="Punto de Venta";
$admin = array(
	'email'=>'soporte@develbmt.com',
	'send_mail'=>'soporte@develbmt.com',
	'send_password'=>'cmm1kt9rlodr',
	'send_server'=>'mail.develbmt.com',
	'send_name'=>'Soporte',
	'send_port'=>25,
	'send_secure'=>'',
	'send_debug'=>0,//0 - no debug, 2 - debug
	'send_charset'=>'UTF-8',
	'points_divisor'=>10,
);
$paypal_config = array(
	'email'=>'tfh_20_1359937636_biz@hotmail.com',
	'url' => 'https://www.sandbox.paypal.com/cgi-bin/webscr ',
	'return'=>$domain,
	'cancel'=>$domain,
	'notify'=>$domain.'/paypal-ipn',
	'currency'=>'MXN'
);
$facturacion_moderna=array(
	'rfc_emisor'=>'ESI920427886',
	'url_timbrado'=>'https://t1demo.facturacionmoderna.com/timbrado/wsdl',
	'user_id'=>'UsuarioPruebasWS',
	'password'=>'b9ec2afa3361a59af4b4d102d3f704eabdf097d4'
);
$config = array(
	'domain'=>$domain,
	'domain_cookie'=>$domain_cookie,
	'domain_name'=>$domain_name,
	'mysql_host' => 'db',
	'mysql_user' => 'root',
	'mysql_password' => '123456',
	'mysql_database' => 'punto_de_venta',
	'mysql_encode'=>'utf8',
	'default_pagination'=>10,
	'paypal' => $paypal_config,
	'admin'=>$admin,
	'facturacion'=>$facturacion_moderna,
	'cookie'=>'l_sw',
	'admin_url'=>'admin',
	'permisos_type'=>1,			// 1 - Permisos por usuario		2 - Permisos por tipo de usuario
);


?>