<?php

include ('include.php');

if (defined('ENVIRONMENT'))
{
	switch (ENVIRONMENT)
	{
		case 'development':
			error_reporting(E_ALL);
		break;
		case 'testing':
		case 'production':
			error_reporting(0);
		break;

		default:
			error_reporting(0);
	}
}
$requestURI=urldecode($_SERVER['REQUEST_URI']);
$requestURI = explode('/', $requestURI);
$scriptName = explode('/',$_SERVER['SCRIPT_NAME']);

$del = count($scriptName);
$i=0;
$comands;
$controller;
$controller_admin='';
$action='';
$store_id=0;
$campain_id=0;

foreach($requestURI as $value){
	if($i >= 4){
		$comands[] = $value;
	}
	if($i == 1){
		$controller = $value;
	}
	if($i == 2){
		$action = $value;
	}
	if($i==3){
		$controller_admin=$value;
	}
	$i++;
}
if($controller==$config["admin_url"]){
	$store_id=$action;
	$controller=$controller_admin;
}
if(empty($controller)){
	$controller = "inicio";
	$site->setZone('home');
	//$site->setPage($controller);
	$site->setPage($pages[$controller]);
	include "app/controller/".$site->getPage();
	include("app/view/".$site->getPage());
}
else{
	if($controller=='admin'){
		$site->setZone('admin');
		$campain_id=(integer)$action;
		$controller="admin";
		if($controller_admin==''){
			$controller_admin='inicio';
		}
		$site->setStoreid($campain_id);
		if(array_key_exists($controller_admin, $pages[$controller])&&file_exists("app/controller/".$pages[$controller][$controller_admin])){
			$site->setPage($pages[$controller][$controller_admin]);
			include "app/controller/".$site->getPage();
			include("app/view/".$site->getPage());
		}else{
			include '404.php';
			die();
		}
	}else
	if($controller=='ajax'){
		$site->setZone('ajax');
		if(array_key_exists($action, $pages[$controller])&&file_exists("app/ajax/controller/".$pages[$controller][$action])){
			$site->setPage($pages[$controller][$action]);
			include "app/ajax/controller/".$site->getPage();
			include("app/ajax/view/".$site->getPage());
		}
		else{
			include '404.php';
			die();
		}
	}else{
		$site->setZone('home');
		if(array_key_exists($controller, $pages)&&file_exists('app/controller/'.$pages[$controller])){
			$site->setPage($pages[$controller]);
			include "app/controller/".$site->getPage();
			include("app/view/".$site->getPage());
		}
		else{
			include '404.php';
			die();
		}
	}
}


?>
