<?php
	if(!isset($_GET['hash'])||checkLogin()){
		header('Location: '.$html->link('index'));
		die();
	}
	$hash=$_GET["hash"];
	$result_user=$connection->query("select id,TIME_TO_SEC(TIMEDIFF(now(), date_change_request)) as diff from users where change_password<>'?' and change_password='?'",
	'',$hash);
	if(!isset($result_user[0]['id'])||$result_user[0]['diff']>21600){
		header('Location: '.$html->link('index'));
		die();
	}
	if(isset($_POST['send_hidden_changepassword'])&&$_POST["send_hidden_changepassword"]==$_SESSION["hidden"]["send_hidden_changepassword"]){
		$check=array(
			'password'=>array('isset'=>'', 'notempty'=>'Favor de ingresar una nueva contraseña'),
			'repassword'=>array('isset'=>'', 'notempty'=>'Favor de ingresar de reingresar su contraseña nueva'),
		);
		if($validator->validator($check, 'post')){
			if($validator->getValue("password")==$validator->getValue("repassword")){
				if($user->changePassword($hash,$validator->getValue("password"))){
					$success="Su contraseña a sido cambiada con exito";
				}else{
					$error="Erro al cambiar su contraseña";
				}
			}else{
				$error="Las contraseñas no coinciden";
			}
		}else{
			$error=$validator->getError();
			//echo $error;
		}
	}
	$_SESSION["hidden"]["send_hidden_changepassword"]=md5(mt_rand());
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
							<a href="<?php echo $html->link("index"); ?>"><img src="/img/logo_nuevo_s.png" width="40" height="40" alt="Logo"></a>
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
								<input <?php if(isset($success)){ ?>disabled="disabled"<?php } ?> name="password" type="password" placeholder="Contraseña" class="login-input user-pass">
							</div>
						</div>
					</div>
					<div class="control-group">
						<div class="controls">
							<div>
								<input <?php if(isset($success)){ ?>disabled="disabled"<?php } ?> name="repassword" type="password" placeholder="Reingresar Contraseña" class="login-input user-pass">
							</div>
						</div>
					</div>
					<input type="hidden" name="send_hidden_changepassword" value="<?php echo $_SESSION["hidden"]["send_hidden_changepassword"]; ?>" />
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
		<script>
			$(document).ready(function(){
				var b=<?php if(isset($success)){ echo 1; }else{ echo 0;} ?>;
				var jumpTo="<?php echo $html->link("login"); ?>";
				if(b==1){
					myVar=setTimeout(function(){window.location.href = jumpTo},3000);
				}
			});
		</script>
	</body>
</html>