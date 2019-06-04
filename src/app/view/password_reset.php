<?php
	if(checkLogin()){
		header('Location: '.$html->link('index'));
		die();
	}
	if(isset($_POST['send_hidden_resetpassword'])&&$_POST["send_hidden_resetpassword"]==$_SESSION["hidden"]["send_hidden_resetpassword"]){
		$check=array(
			'email'=>array('isset'=>'', 'notempty'=>'Favor de ingresar un correo electronico'),
		);
		if($validator->validator($check, 'post')){
			$b=$user->sendResetPassword($validator->getValue("email"));
			//var_dump($b);
			$success="Un correo con las instrucciones para reiniciar su contraseña se le a sido enviado.";
		}else{
			$error=$validator->getError();
			//echo $error;
		}
	}
	$_SESSION["hidden"]["send_hidden_resetpassword"]=md5(mt_rand());
?>
<!DOCTYPE HTML>
<html lang="en">
	<head>
		<?php include($html->componentView("head")); ?>
	</head>
	<body>
		<div class="navbar navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container-fluid">
					<div class="branding">
						<div class="logo">
							<a href="<?php echo $html->link("index"); ?>"><img src="img/logo_nuevo_s.png" width="40" height="40" alt="Logo"></a>
						</div>
					</div>
					<!--<ul class="nav pull-right">
						<li><a href="#"><i class="icon-share-alt icon-white"></i> Go to Main Site</a></li>
					</ul>-->
				</div>
			</div>
		</div>
		<div class="login-container">
			<div class="well-login">
				<?php
					if(isset($error)){
				?>
				<div class="alert alert-error fade in">
					<button data-dismiss="alert" class="close" type="button">x</button>
					<strong>Error!</strong> <?php echo $error; ?>
				</div>
				<?php
					}
				?>
				<?php
					if(isset($success)){
				?>
				<div class="alert alert-success fade in">
					<button data-dismiss="alert" class="close" type="button">x</button>
					<?php echo $success; ?>
				</div>
				<?php
					}
				?>
				<form method="POST" action="">
					<div class="control-group">
						<div class="controls">
							<div>
								<input name="email" type="text" placeholder="Correo electronico" class="login-input user-name">
							</div>
						</div>
					</div>
					<input type="hidden" name="send_hidden_resetpassword" value="<?php echo $_SESSION["hidden"]["send_hidden_resetpassword"]; ?>" />
					<div class="clearfix">
						<button class="btn btn-inverse login-btn" type="submit">Reiniciar contraseña</button>
					</div>
					<!--<div class="remember-me">
						<input class="rem_me" type="checkbox" value=""> Remeber Me
					</div>-->
				</form>
			</div>
		</div>
		<?php include($html->componentView("js")); ?>
	</body>
</html>