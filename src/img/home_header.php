<div class="row-1">
    <div class="container_12">
      <div class="grid_12">
        <div class="logo fleft">
          <h1><a href="<?php echo $html->link('index'); ?>">BMT - Business Management Task</a></h1>
          <div class="slogan">Business Management Task</div>
        </div>
		<?php
			if($user->user_id!=0){
		?>
		<div class="login">
          <ul>
            <li><a href="<?php echo $html->link('profile_dashboard'); ?>"><?php echo 'bienvenido '.$user->name; ?></a></li>
            <li><a href="<?php echo $html->link('logout'); ?>">Finalizar Sesi&oacute;n</a></li>
          </ul>
        </div>
		<?php
			}else{
		?>
		<div class="login">
          <ul>
            <li><a class="prettyPhoto" href="#login_form">Login</a></li>
            <li><a href="<?php echo $html->link('home_register'); ?>">Registro</a></li>
          </ul>
        </div>
		<?php
			}
		?>
        <div class="clear"></div>
      </div>
      <div class="clear"></div>
    </div>
  </div>
  <div class="row-3">
    <div class="container_12">
      <div class="grid_12">
        <!-- menu -->
        <nav  id = "main-nav-menu">
          <ul class="sf-menu">
            <li <?php if($html->getPageFile($controller)=='index.php'){ echo 'class="active"'; } ?>><a href="<?php echo $html->link('index'); ?>">Inicio</a></li>
            <li <?php if($html->getPageFile($controller)=='home_about.php'){ echo 'class="active"'; } ?>><a href="<?php echo $html->link('home_about'); ?>">Acerca</a>
            </li>
            <li <?php if($html->getPageFile($controller)=='home_plans.php'){ echo 'class="active"'; } ?>><a href="<?php echo $html->link('home_plans'); ?>">Planes</a>
            </li>
            <li <?php if($html->getPageFile($controller)=='home_register.php'){ echo 'class="active"'; } ?>><a href="<?php echo $html->link('home_register'); ?>">Registrar</a>
            </li>
            <li <?php if($html->getPageFile($controller)=='home_contact.php'){ echo 'class="active"'; } ?>><a href="<?php echo $html->link('home_contact'); ?>">Contacto</a></li>
          </ul>
        </nav>
        <!-- end menu -->
        <a href="<?php echo $html->link('select_store'); ?>" class="donate-button button-blue">Area de clientes</a>
        <select id = "responsive-main-nav-menu" onchange = "javascript:window.location.replace(this.value);">
        </select>
        <div class="clear"></div>
      </div>
      <div class="clear"></div>
    </div>
  </div>
  <div id="login_form" style="display: none; width: 390px; margin-right: 0px; padding-right: 0px;">
	<div class="flex-bg" style="width: 390px; margin-right: 0px; padding-right: 0px;">
		<div class="flex-border" style="width: 320px; background: none; padding-right: 30px;">
		  <div class="slide-text-1 ident-bot-1">Iniciar sesion</div>
		  <div class="signup-form-ctnr">
			<form method="post" class="home-form" id="signup-form">
			  <fieldset>
			  <div class="field">
				<input id="email" type="text" value="Email :" name="email" class="itext smart_input" onBlur="this.value=!this.value?'Email :':this.value;"  onclick="if(this.value=='Email :')this.value='';" >
			  </div>
			  <p style="margin-bottom: 10px; color: #EEE;">Contrase&ntildea: </p>
			  <div class="field">
				<input id="password_form" type="password" value="" name="password" class="itext smart_input" onBlur=""  onclick="" >
			  </div>
			  </fieldset>
			  <input type="submit" onclick="send_form(this.form); return false;" class="button-red home-form-btn" value="Iniciar Sesion" style="cursor: pointer;">
			</form>
		  </div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		//$(".fancybox_login").fancybox();
		$(".prettyPhoto").prettyPhoto();
	});
	function send_form(form){
		var email = form[1].value;
		var password = form[2].value;
		$.post("<?php echo $html->link('login', 'ajax'); ?>", { email: email, password: password, xhrFields: { withCredentials: true } },
			function(data) {
				//alert("Data Loaded: " + data);
				if(data != 'incorrect'){
					location.reload();
				}else{
					alert('Usuario y/o contraseña incorrectos');
				}
		});
	}
</script>