<?php
	
	class alert {
		
		public $objects = [];
		public $params = [];
		
		function __construct($params) {
			$this->params = $params;
		}
		
		function sendMail($params) {
			global $config;
			global $mail;
			
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
			$mail->AddAddress($params["email"]);  // Add a recipient
			$mail->AddReplyTo($config['admin']['send_mail'], $config['admin']['send_name']);
			
			$mail->IsHTML(true);
			
			$mail->Subject = $params["subject"];
			$mail->Body = $params["body"];
			$mail->AltBody = $params["altBody"];
			
			if(!$mail->Send()) {
				if(ENVIRONMENT=="development"){
					$error='Message could not be sent. <br />';
					$error.='Mailer Error: '.$mail->ErrorInfo;
					echo $error;
				}
			   return false;
			}
			$mail->ClearAllRecipients();
			$mail->SmtpClose();
			return true;
		}
		
	}
	
	$alert = new alert(0);
	
?>
