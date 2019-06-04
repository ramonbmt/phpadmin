<?php
include('include/config.php');
include('include/pages.php');
include('include/mysql.php');
include('include/html_class.php');
include('include/user_class.php');
include('include/site_info_class.php');
include('include/mail_class.php');
include('include/validate.php');
include('include/pagination.php');
include('include/PHPExcel.php');
include('include/search.php');
include "include/registry.php";
//include('include/paypal/paypal.class.php');
include "include/tablecreator.php";
include "include/querybuilder.php";
include "include/formcreator.php";
include "include/editcreator.php";
//include "include/imageresize.php";
include "include/controller.php";
include "include/viewer.php";
include "include/ajax.php";
include "include/prop.php";
include "include/alertcreator.php";
include "include/chartcreator.php";

$registry = new Registry();
$registry->set('config', $config);
$registry->set('connection', $connection);
$registry->set('user', $user);
$registry->set('html', $html);
$registry->set('pagination', $pagination);
$registry->set('search', $search);
$registry->set('validator', $validator);
$registry->set('alert', $alert);
$registry->set('mail', $mail);
$registry->set('edit', new edit_class("edit_template"));
$registry->set('form', new form_class("form_template"));
$registry->set('prop', new prop_class("prop_template_self"));
$registry->set('sqlbuilder', new querybuilder_class(""));
$registry->set('table', new table_class("table_template_self"));
$registry->set('ajax', new ajax_class("ajax_template_self","ajax_template_self"));
$registry->set('chart', new chart_class("chart_template"));
$registry->set('view',array());


$data=array();
session_start();
if(!isset($_SESSION['pos']['products'])){
	$_SESSION['pos']=array('products'=>array(), 'payed'=>array());
	//$_SESSION['pos']=array('payed'=>array());
	//print_r($_SESSION);
}
/**
 * @param string $jumpTo
 */
function sendTo($jumpTo,$them=null){
	if(isset($_POST["ajax"])&&isset($_POST["page"])&&$_POST["ajax"]==1){
		$data["success"]=0;
		$data["jumpTo"]=$jumpTo;
		if($them!=null){
			$data=array_merge($data,$them->table->getData(),$them->edit->getData(),$them->form->getData(),$them->prop->getData());
		}
		echo json_encode($data);
		die();
	}else{
		header("Location: ".$jumpTo);
		die();
	}
}
function checkPermiso($i,$jumpTo=false){
	global $html;
	global $user;
	if(!isset($user->permiso[$i])||$user->permiso[$i] <= 1)	{
		if($jumpTo==false){
			return false;
		}else{
			header("Location: ".$html->link($jumpTo));
			die();
		}
	}
	return true;
}
function componentView($component){
	$str_path = "app/components/view/$component.php";
	if(file_exists($str_path)){
		include($str_path);
	}
}

function componentLogic($component){
	$str_path = "app/components/view/$component.php";
	if(file_exists($str_path)){
		include($str_path);
	}
}

function generate_hash($password, $cost=11){
	$s=true;
	$salt=substr(base64_encode(openssl_random_pseudo_bytes(17,$s)),0,22);
	$salt=str_replace("+",".",$salt);
	$param='$'.implode('$',array(
			"2y", //select the most secure version of blowfish (>=PHP 5.3.7)
			str_pad($cost,2,"0",STR_PAD_LEFT), //add the cost in two digits
			$salt //add the salt
	));
	$crypt=crypt($password,$param);
	return $crypt;
}

function validate_pw($password, $hash){
	return crypt($password, $hash)==$hash;
}

function tryLogin($user_name, $password){
	global $connection;
	global $user;
	global $config;
	//$login_result=$connection->query("select id from users where (user_name='?' or email='?') and password='?'",$user_name, $user_name, md5($password));
	$login_result=$connection->query("select users.id,users.user_name,users.name,users.lastname,users.email,users.random,users.password,users.admin,
	users.supervisor,users.user_type,users.status,if('?'=1,users.permiso,user_type.permiso) as permiso
	from users
	left join user_type on users.user_type = user_type.id
	where (user_name='?' or email='?')",$config["permisos_type"],$user_name, $user_name);
	//var_dump(crypt($password, $login_result[0]['password']));
	if(isset($login_result[0]['password'])&&validate_pw($password,$login_result[0]['password'])){
		$value = md5(uniqid(mt_rand(), true));
		//print_r($login_result);
		$connection->query("update users set random='?', last_login=? where id=?",$value, 'now()', $login_result[0]['id']);
		//setcookie("l_cc", $login_result[0]['id'].';'.$value);
		if($config['domain_cookie'] === "localhost"){
			setcookie($config['cookie'], $login_result[0]['id'].';'.$value, time()+60*60*24*365, '/', "localhost", false);
		} else {
			setcookie($config['cookie'], $login_result[0]['id'].';'.$value, time()+60*60*24*365, '/', '.'.$config['domain_cookie'], false);
		}
		// setcookie($config['cookie'], $login_result[0]['id'].';'.$value, time()+60*60*24*365, '/', '.'.$config['domain_cookie'], false);
		//$this->setUser($user_id, $user_name, $name, $last_name, $email, $random, 0);
		$user->setUser($login_result[0]['id'], $login_result[0]['user_name'], $login_result[0]['name'],
		$login_result[0]['lastname'], $login_result[0]["email"], $value, $login_result[0]["admin"], $login_result[0]["supervisor"],
		$login_result[0]["user_type"],$login_result[0]["status"],$login_result[0]["permiso"]);
		//var_dump($user);
		return true;
	}
	//print_r(array($login_result,$user_name,$password));
	/*if(count($login_result)>0){
		$value = md5(uniqid(mt_rand(), true));
		//print_r($login_result);
		$connection->query("update users set random='?', last_login=? where id=?",$value, 'now()', $login_result[0]['id']);
		//setcookie("l_cc", $login_result[0]['id'].';'.$value);
		setcookie($config['cookie'], $login_result[0]['id'].';'.$value, time()+60*60*24*365, '/', '.'.$config['domain_cookie'], false);
		//$user->setUser($login_result[0]['id'], $login_result[0]['user_name'], $login_result[0]['name'], $login_result[0]['last_name'], $value);
		//var_dump($user);
		return true;
	}*/
	return false;
}

function checkLogin(){
	global $connection;
	global $html;
	global $user;
	global $config;
	if(isset($_COOKIE[$config['cookie']])&&strrpos($_COOKIE[$config['cookie']], ";")!==false){
		$login_info=explode(';',$_COOKIE[$config['cookie']]);
		$login_result=$connection->query("select id,user_name,name,lastname,email,random,admin,status,supervisor,user_type,status,permiso
		from users where id='?' and random='?'",$login_info[0], $login_info[1]);
		if(count($login_result)>0&&$login_result[0]["status"]<2){
			$user->setUser($login_result[0]['id'], $login_result[0]['user_name'], $login_result[0]['name'],
			$login_result[0]['lastname'], $login_result[0]['email'], $login_result[0]['random'], $login_result[0]["admin"],
			$login_result[0]["supervisor"],$login_result[0]["user_type"],$login_result[0]["status"],$login_result[0]["permiso"]);
			return true;
		}else{
			return false;
		}
	}else{
		return false;
	}
	return false;
}

function forceLogin($login="default"){
	global $connection;
	global $html;
	global $user;
	global $site;
	global $config;
	if($login=="default"){
		$login=$site->getLogin();
	}else{
		$login=$html->link($login);
	}
	if(isset($_COOKIE[$config['cookie']])&&strrpos($_COOKIE[$config['cookie']], ";")!==false){	
		$login_info=explode(';',$_COOKIE[$config['cookie']]);
		$login_result=$connection->query("select users.id,users.user_name,users.name,users.lastname,users.email,users.random,users.password,users.admin,
		users.supervisor,users.user_type,users.status,if('?'=1,users.permiso,user_type.permiso) as permiso
		from users
		left join user_type on users.user_type = user_type.id
		where users.id='?' and users.random='?'",$config["permisos_type"],$login_info[0], $login_info[1]);
		if(count($login_result)>0&&$login_result[0]["status"]<2){
			$user->setUser($login_result[0]['id'], $login_result[0]['user_name'], $login_result[0]['name'],
			$login_result[0]['lastname'], $login_result[0]['email'], $login_result[0]['random'], $login_result[0]["admin"],
			$login_result[0]["supervisor"],$login_result[0]["user_type"],$login_result[0]["status"],$login_result[0]["permiso"]);
			//echo print_r($login_result);

			return true;
		}else{
			header('Location: '.$login);
			die();
		}
	}else{
		header('Location: '.$login);
		die();
	}
	return false;
}

function forceLoginCampain($login="default"){
	global $connection;
	global $html;
	global $user;
	global $site;
	global $campain_id;
	global $config;
	if($login=="default"){
		$login=$site->getLogin();
	}else{
		$login=$html->link($login);
	}
	if(isset($_COOKIE[$config['cookie']])&&strrpos($_COOKIE[$config['cookie']], ";")!==false){
		$login_info=explode(';',$_COOKIE[$config['cookie']]);
		$login_result=$connection->query("select id,user_name,name,lastname,email,random,admin,status,supervisor,user_type,status,permiso
		from users where id='?' and random='?'",$login_info[0], $login_info[1]);
		if(count($login_result)>0&&$login_result[0]["status"]<2){
			//echo "con login";
			$user->setUser($login_result[0]['id'], $login_result[0]['user_name'], $login_result[0]['name'],
			$login_result[0]['lastname'], $login_result[0]['email'], $login_result[0]['random'], $login_result[0]["admin"],
			$login_result[0]["supervisor"],$login_result[0]["user_type"],$login_result[0]["status"],$login_result[0]["permiso"]);
			//var_dump($user);
			return true;
		}else{
			header('Location: '.$login);
			die();
		}
	}else{
		header('Location: '.$login);
		die();
	}
	return false;
	//print_r($login_info);
}

function logout($url,$user_id){
	global $config;
	global $html;
	global $site;
	global $user;
	global $connection;
	if(!$user->admin&&isset($_SESSION['campain_logged_id'])){
		$campain_id=$_SESSION['campain_logged_id'];
		$connection->query("START TRANSACTION");
		$result_punch=$connection->query("SELECT TIME_TO_SEC(TIMEDIFF(now(), datetime)) as diff from punchcard
		where user_id='?' and punch_type='?' order by datetime desc limit 1",$user_id,1);
		$connection->query("update punchcard set atended='?' where user_id='?' and punch_type='?'",
		1,$user_id,1);
		$connection->query("insert into timecard (user_id,date,timesum,time_type,campain_id) values
		('?',?,'?','?','?')",$user_id,'now()',$result_punch[0]['diff'],1,$campain_id);
		$connection->query("insert into punchcard (user_id,datetime,campain_id,punch_type,atended) values
		('?',?,'?','?','?')",$user_id,'now()',$campain_id,2,1);
		$connection->query("COMMIT");
		unset($_SESSION['campain_logged_id']);
	}
	if($config['domain_cookie'] === "localhost"){
		setcookie($config['cookie'], '', time()-3600, '/', 'localhost', false);
	} else {
		setcookie($config['cookie'], '', time()-3600, '/', '.'.$config['domain_cookie'], false);
	}
	// setcookie($config['cookie'], '', time()-3600, '/', '.'.$config['domain_cookie'], false);
	//echo '.'.$config['domain_cookie'];
	//setcookie("l_cc", "", time()-60*60*24*466);
	header('Location: '.$html->link($url), 5);
}

function forceLoginAjax(){
	global $connection;
	global $html;
	global $user;
	global $config;
	if(isset($_COOKIE[$config['cookie']])&&strrpos($_COOKIE[$config['cookie']], ";")!==false){
		$login_info=explode(';',$_COOKIE[$config['cookie']]);
		$login_result=$connection->query("select id,user_name,name,lastname,email,random,admin,status,supervisor,user_type,status,permiso
		from users where id='?' and random='?'",$login_info[0], $login_info[1]);
		if(count($login_result)>0&&$login_result[0]["status"]<2){
			$user->setUser($login_result[0]['id'], $login_result[0]['user_name'], $login_result[0]['name'],
			$login_result[0]['lastname'], $login_result[0]['email'], $login_result[0]['random'], $login_result[0]["admin"],
			$login_result[0]["supervisor"],$login_result[0]["user_type"],$login_result[0]["status"],$login_result[0]["permiso"]);
			return true;
		}else{
			//header('Location: '.$html->link('login'));
			die();
		}
	}else{
		//header('Location: '.$html->link('login'));
		die();
	}
	return false;
	//print_r($login_info);
}

function forceLoginCampainAjax(){
	global $connection;
	global $html;
	global $user;
	global $config;
	if(isset($_COOKIE[$config['cookie']])&&strrpos($_COOKIE[$config['cookie']], ";")!==false){
		$login_info=explode(';',$_COOKIE[$config['cookie']]);
		$login_result=$connection->query("select id,user_name,name,lastname,email,random,admin,status, supervisor,user_type,status,permiso
		from users where id='?' and random='?'",$login_info[0], $login_info[1]);
		if(count($login_result)>0&&$login_result[0]["status"]<2){
			$user->setUser($login_result[0]['id'], $login_result[0]['user_name'], $login_result[0]['name'],
			$login_result[0]['lastname'], $login_result[0]['email'], $login_result[0]['random'], $login_result[0]["admin"],
			$login_result[0]["supervisor"],$login_result[0]["user_type"],$login_result[0]["status"],$login_result[0]["permiso"]);
			return true;
		}else{
			//header('Location: '.$html->link('login'));
			die();
		}
	}else{
		//header('Location: '.$html->link('login'));
		die();
	}
	return false;
	//print_r($login_info);
}

function checkCampainAccess($campain_id,$jumpTo="default"){
	global $connection;
	global $user;
	$result_campain=$connection->query("select count(id) as count from campains_access
	where user_id=? and campain_id=?",$user->user_id,$campain_id);
	if($result_campain[0]['count']>0){
		return true;
	}else{
		$connection->query("insert into punchcard (user_id,datetime,campain_id,punch_type) values ('?',?,'?','?')",
		$user->user_id,'now()',$_SESSION['campain_logged_id'],2);
		unset($_SESSION['campain_logged_id']);
		if($jumpTo=="default"){
			header("Location: ".$html->link("select_campain"));
			die();
		}else{
			header("Location: ".$html->link($jumpTo));
			die();
		}
	}
	return false;
}

function checkCampainAccessAjax($campain_id){
	global $connection;
	global $user;
	$result_campain=$connection->query("select count(id) as count from campains_access
	where user_id=? and campain_id=?",$user->user_id,$campain_id);
	if($result_campain[0]['count']>0){
		return true;
	}
	die();
}

function checkStoreAjax($store_id){
	global $connection;
	global $html;
	global $user;
	$result_admin_stores = $connection->query('select stores.id from stores left join store_access on stores.id=store_access.store_id where stores.id=? and (stores.user_id=? or store_access.user_id=?)', $store_id, $user->user_id, $user->user_id);
	if(count($result_admin_stores)>0){
		//echo "si puedes ver la informacion";
	}else{
		//header("Location: ".$html->link('select_store'));
		die();
		//echo "no puedes ver la informaicon";
	}
}

function checkZoneAccess($zone, $store_id){
	global $connection;
	global $html;
	global $user;
	$zone-=1;
	$result_check_admin=$connection->query("SELECT COUNT(id) as COUNT FROM  `stores` WHERE user_id =? AND id =?",
	$user->user_id, $store_id);
	if($result_check_admin[0]['COUNT'] > 0){
		return;
	}
	$store_zones=$connection->query("select id,name from store_zones where 1");
	$user_access=$connection->query("select access_crm,access_proveedores,access_hr,access_pos,access_metrics,
	access_todolist,access_calendar,access_administration,access_chat,access_users,access_transactions,access_ajustes,
	access_estados_financieros,access_facturas,access_store_info from store_access where user_id=? and store_id=?", $user->user_id, $store_id);
	if(!isset($store_zones[$zone]['name'])&&!isset($user_access[0][$store_zones[$zone]['name']])){
		return "Error de zona";
	}
	//echo $store_zones[$zone]['name'].'-'.$zone;
	if($user_access[0][$store_zones[$zone]['name']]==0){
		header("Location: ".$html->link('admin_index', 'admin', $store_id));
	}
	return;
}

function user_log_add($user,$log_type,$comments,$date='now()'){
	global $connection;
	if($date=='now()'){
		$connection->query("insert into user_log (user_id,date,log_type,coments)
		values ('?',?,'?','?')",$user, $date, $log_type, $comments);
	}else{
		$connection->query("insert into user_log (user_id,date,log_type,coments)
		values ('?','?','?','?')",$user, $date, $log_type, $comments);
	}
}

function sendPasswordChange(){

}

?>
