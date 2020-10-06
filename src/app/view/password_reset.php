<?php
	if(checkLogin()){
		header('Location: '.$html->link('index'));
		die();
	}
	if(isset($_POST['send_hidden_resetpassword'])&&$_POST["send_hidden_resetpassword"]==$_SESSION["hidden"]["send_hidden_resetpassword"]){
		$check=array(
			'email'=>array('isset'=>'', 'notempty'=>'Favor de ingresar un correo electrónico'),
		);
		if($validator->validator($check, 'post')){
			$b=$user->sendResetPassword($validator->getValue("email"));
			// var_dump($b);
			if($b["response"]){
				$success="<b> ¡Listo, ".$b["message"]."! - </b> Revisa tu bandeja de correo, incluyendo en SPAM, para proceder a restablecer tu contraseña.";
			}else{
				$error = $b["message"];
			}
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
        <style>
            .background-header{
                height: 38vh;
                width: 100%;
                position: absolute;
                top: 0;
                z-index: 1;
                background-color: var(--bmt-gray-light);
            }

            .card{
                min-width: 450px;
                max-width: 500px;
                margin-top:5em;
            }

            footer{
                position: fixed;
                bottom: 0;
                width: 100%;
            }

            @media (max-height: 812px) {
                footer{
                    position: relative;
                }
            }

            @media (max-width: 1200px) {
                .card{
                    margin-top:3em;
                    min-width: 350px;
                }
                .navbar-logo {
                    max-width: 190px;
                }
            }

            @media (max-width: 321px) {
                .card{
                    min-width: 300px;
                    margin-top:3em;
                }
            }
        </style>
	</head>
    <body>
        <nav class="navbar navbar-light bg-white d-flex flex-column justify-content-center align-items-center" style="height: 15vh; z-index:2; min-height: 140px;">
            <div class="d-flex navbar-logo">
                <a href="<?php echo $html->link("index"); ?>"><img width="150px" height="auto" src="img/logoBMT.png" alt="logoBMT"></a>
            </div>
        </nav>
        <div class="d-flex flex-row justify-content-center align-items-start" style="height: 78vh;">
            <div class="background-header"></div>
            <div class="container d-flex flex-column justify-content-center align-items-center">
                <div class="card text-center" style="z-index:2;">
                    <div class="card-header bg-gray-dark py-3">
                        <span style="color:white; font-size: 1.5em; font-weight:bold;">Restablecer Contraseña</span>
                    </div>
                    <div class="card-body p-3 p-md-5">
                        <p class="text-left">Escribe tu correo para enviar las instrucciones correspondientes para restablecer tu contraseña.</p>
                        <?php
                            if(isset($error)){
                        ?>
                            <div class="d-flex flex-row justify-content-center alert alert-danger alert-dismissible fade show" role="alert">
                                <p class="small"><strong>¡Error!</strong> <?php echo $error; ?></p>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php
                            }
                        ?>
                        <?php
                            if(isset($success)){
                        ?>
                            <div class="d-flex flex-row justify-content-center alert alert-success alert-dismissible fade show" role="alert">
                                <p class="small"><?php echo $success; ?></p>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php
                            }
                        ?>
                        <form method="POST" action="" style="margin:0;">
                            <!-- Email input -->
                            <div class="form-outline mb-4">
                                <input class="form-control" <?php if(isset($success)){ ?>disabled="disabled"<?php } ?> name="email" type="email" id="email">
                                <label class="form-label" for="email">Correo electrónico</label>
                            </div>
                            <!-- Submit button -->
					        <input type="hidden" name="send_hidden_resetpassword" value="<?php echo $_SESSION["hidden"]["send_hidden_resetpassword"]; ?>" />
                            <button type="submit" class="btn btn-success btn-block">Restablecer contraseña</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <footer class="bg-gray text-center text-lg-left">
            <!-- Copyright -->
            <div class="text-center p-3" style="background-color: var(--bmt-gray-light);">
                Copyright © 2020 Business Management Task
            </div>
            <!-- Copyright -->
        </footer>
        <?php include($html->componentView("js")); ?>
		<script>
            (function() {
                var b=<?php if(isset($success)){ echo 1; }else{ echo 0;} ?>;
				var jumpTo="<?php echo $html->link("login"); ?>";
				if(b==1){
					myVar=setTimeout(function(){window.location.href = jumpTo},3000);
				}
            })();
		</script>
    </body>
</html>
