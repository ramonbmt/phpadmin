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
                        <span style="color:white; font-size: 1.5em; font-weight:bold;">Iniciar sesión</span>
                    </div>
                    <div class="card-body p-5">
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
                        <form method="POST" action="" style="margin:0;">
                            <!-- Email input -->
                            <div class="form-outline mb-4">
                                <input name="email" type="email" id="email" class="form-control" />
                                <label class="form-label" for="email">E-mail</label>
                            </div>

                            <!-- Password input -->
                            <div class="form-outline mb-4">
                                <input name="password" type="password" id="password" class="form-control" />
                                <label class="form-label" for="password">Contraseña</label>
                            </div>

                            <!-- Submit button -->
                            <input type="hidden" name="send_hidden_login" value="<?php echo $_SESSION["hidden"]["send_hidden_login"]; ?>" />
                            <button type="submit" class="btn btn-success btn-block">Iniciar Sesión</button>

                        </form>
                        <div class="row mt-4">
                            <div class="col d-flex justify-content-center">
                                <a href="<?php echo $html->link("password_reset"); ?>" class="link-warning">¿Olvidaste tu contraseña?</a>
                            </div>
                        </div>
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
    </body>
</html>
