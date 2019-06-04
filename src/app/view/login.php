<?php
	if(checkLogin()){
		header('Location: '.$html->link('index'));
		die();
	}
	if(isset($_POST['send_hidden_login'])&&isset($_SESSION["hidden"]["send_hidden_login"])&&$_POST["send_hidden_login"]==$_SESSION["hidden"]["send_hidden_login"]){
		$check=array(
			'email'=>array('isset'=>'', 'notempty'=>'Favor de ingresar un correo electronico'),
			'password'=>array('isset'=>'', 'notempty'=>'Favor de ingresar la contraseña'),
		);
		if($validator->validator($check, 'post')){
			if(tryLogin($validator->getValue("email"), $validator->getValue("password"))){
				header('Location: '.$html->link('index'));
				die();
			}else{
				$error="Correo y/o contraseña incorrectos";
			}
		}else{
			$error=$validator->getError();
			//echo $error;
		}
	}
	//unset($_SESSION["hidden"]["send_hidden_login"]);
	$_SESSION["hidden"]["send_hidden_login"]=md5(mt_rand());
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
							<a href="<?php echo $html->link("index"); ?>"><img src="img/logo_nuevo_s.png" style="height:40px;" alt="Logo"></a>
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
					<button data-dismiss="alert" class="close" type="button">×</button>
					<strong>Error!</strong> <?php echo $error; ?>
				</div>
				<?php
					}
				?>
				<form method="POST" action="" style="margin:0;">
					<div class="control-group">
						<div class="controls">
							<div>
								<input name="email" type="text" placeholder="Correo electronico" class="login-input user-name">
							</div>
						</div>
					</div>
					<div class="control-group">
						<div class="controls">
							<div>
								<input name="password" type="password" placeholder="Contraseña" class="login-input user-pass">
							</div>
						</div>
					</div>
					<input type="hidden" name="send_hidden_login" value="<?php echo $_SESSION["hidden"]["send_hidden_login"]; ?>" />
					<div class="clearfix">
						<button class="btn btn-inverse login-btn" type="submit">Ingresar</button>
					</div>
					<div class="remember-me">
						<!--<input class="rem_me" type="checkbox" value=""> Remeber Me-->
						<a href="<?php echo $html->link("password_reset"); ?>" />Olvido su contraseña?</a>
					</div>
				</form>
			</div>
		</div>
		<?php include($html->componentView("js")); ?>
	</body>
</html>