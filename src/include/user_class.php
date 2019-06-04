<?php

class user_class{

public $user_id;
public $user_name;
public $name;
public $last_name;
public $email;
public $random;
public $admin;
public $supervisor;
public $user_type;
public $status;
public $permiso;

	function __construct($user_id){
		$this->user_name=$user_id;
	}

	function setUser($user_id, $user_name, $name, $last_name, $email, $random, $admin, $supervisor=0, $user_type=1,$status=0,$permiso=""){
		$this->user_id = $user_id;
		$this->user_name = $user_name;
		$this->name = $name;
		$this->last_name=$last_name;
		$this->email=$email;
		$this->random=$random;
		$this->admin=$admin;
		$this->supervisor=$supervisor;
		$this->user_type=$user_type;
		$this->status=$status;
		$this->permiso=unserialize($permiso);
	}

	function addUser($user_name, $name, $email, $last_name, $user_type, $password){
		global $connection;
		global $config;
		$result_user = $connection->query("select count(id) as count from users where email='?'", $email);
		if($result_user[0]['count'] > 0){
			return 'Email ya registrado';
		}
		$result_permiso=$connection->query("select permiso from user_type where id='?'",$user_type);
		$connection->query("START TRANSACTION");
		$result_modules=$connection->query("select id,name from modules where 1");
		$result_modules=array_fill_keys(array_keys($result_modules),0);
		$password=generate_hash($password);
		$connection->query("insert into users (user_name, name, email, date, lastname, password, last_login, admin, user_type,permiso)
		values ('?', '?', '?', now(), '?', '?', now(), '?', '?','?')", $user_name, $name, $email, $last_name, $password, 0, $user_type,$result_permiso[0]["permiso"]);
		//setUser($user_id, $user_name, $name, $last_name, $email, $random);
		//echo $connection->getLastId();
		$random = md5(uniqid(mt_rand(), true));
		$user_id = $connection->getLastId();
		$connection->query("update users set random='?' where id=?",$random, $user_id);
		$connection->query("COMMIT");
		//setcookie('l_cb', $user_id.';'.$random, time()+60*60*24*365, '/', '.'.$config['domain_cookie'], false);
		//setcookie("l_cb", $user_id.';'.$random);
		//$this->setUser($user_id, $user_name, $name, $last_name, $email, $random, 0, 0);
		return true;
	}

	function addUserToCampain($user_id, $campain_id){
		global $connection;
		$connection->query("INSERT INTO campains_access (campain_id, user_id)
		SELECT * FROM (SELECT ? as campain_id, ? as user_id) AS tmp
		WHERE NOT EXISTS (
			SELECT campain_id, user_id FROM campains_access WHERE campain_id=? and user_id=?
		) LIMIT 1",$campain_id,$user_id,$campain_id,$user_id);
		return true;
	}

	function removeUserFromCampain($user_id, $campain_id){
		global $connection;
		$connection->query("delete from campains_access where campain_id=? and user_id=?", $campain_id, $user_id);
		return true;
	}

	function sendResetPassword($email){
		global $connection;
		global $mail;
		global $config;
		global $html;
		$result_user=$connection->query("select id,email from users where email='?'",$email);
		if(!isset($result_user[0]['email']))
		{
			return false;
		}

		$mail->IsSMTP(); // Use SMTP
		$mail->Host        = $config['admin']['send_server']; // Sets SMTP server
		$mail->SMTPDebug   = $config['admin']['send_debug']; // 2 to enable SMTP debug information
		$mail->Port        = $config['admin']['send_port']; // set the SMTP port
		$mail->SMTPAuth    = TRUE; // enable SMTP authentication
		$mail->SMTPSecure  = $config['admin']['send_secure']; //Secure conection
		$mail->Username    = $config['admin']['send_mail']; // SMTP account username
		$mail->Password    = $config['admin']['send_password']; // SMTP account password
		$mail->Priority    = 1; // Highest priority - Email priority (1 = High, 3 = Normal, 5 = low)
		$mail->CharSet     = $config['admin']['send_charset'];
		$mail->Encoding    = '8bit';
		$mail->Subject     = $config['admin']['send_name'];
		$mail->ContentType = 'text/html; charset=utf-8\r\n';
		$mail->From        = $config['admin']['send_mail'];
		$mail->FromName    = $config['admin']['send_name'];
		$mail->WordWrap    = 900; // RFC 2822 Compliant for Max 998 characters per line
		$mail->AddAddress($email);  // Add a recipient
		$mail->AddReplyTo($config['admin']['send_mail'], $config['admin']['send_name']);


		$mail->IsHTML(true);                                  // Set email format to HTML

		$mail->Subject = 'Reseteo de contraseña';
		$s=true;
		$salt=substr(base64_encode(openssl_random_pseudo_bytes(17,$s)),0,22);
		$salt=str_replace("+",".",$salt);
		$hash=md5($salt);
		$connection->query("update users set change_password='?',date_change_request=? where id='?'",
		$hash,'now()',$result_user[0]["id"]);

		ob_start();
		include $html->sistemComponentView('reset_password_template');
		$mail->Body    = ob_get_clean();
		//$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
		$mail->AltBody = 'Este es el recibo';

		if(!$mail->Send()) {
			if(ENVIRONMENT=="development"){
				$error='Message could not be sent.';
				$error.='Mailer Error: ' . $mail->ErrorInfo;
				echo $error;
			}
		   return false;
		}
		$mail->SmtpClose();
		return true;
	}
	function changePassword($hash,$password){
		global $connection;
		$password=generate_hash($password);
		$result_user=$connection->query("select id,email,change_password,TIME_TO_SEC(TIMEDIFF(now(), date_change_request)) as diff from users where change_password<>'?' and change_password='?'",
		'',$hash);
		if(!isset($result_user[0]['email'])||$result_user[0]['diff']>21600){
			return false;
		}
		$s=true;
		$salt=substr(base64_encode(openssl_random_pseudo_bytes(17,$s)),0,22);
		$salt=str_replace("+",".",$salt);
		$hash=md5($salt);
		$connection->query("update users set password='?',change_password='?' where id='?'",
		$password,$hash,$result_user[0]['id']);
		return true;
	}

}

$user = new user_class(0);

?>
