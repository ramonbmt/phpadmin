<?php
	checkLogin();
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
<?php include($html->componentView("head")); ?>
</head>
<body>
<style>
	.top-nav{
		margin:0px;
	}
</style>
<?php include($html->componentView("topnav")); ?>
<div class="container">
	<div class="row error-wrap">
		<div class="span3">
			<div class="errorcode">
				<span>404</span>
			</div>
		</div>
		<div class="span5">
			<div class="error-title">
				<span>Uh oh!</span>
			</div>
			<div>
				<h3 class="error-message"><span>Lo sentimos, la pagina que intenta buscar no existe.</span> 
				Es probable que se genero un error al introducir la direccion en el navegador.</h3>
				<p>
					Sugerencias:
				</p>
				<ul class="error-instruction">
					<li>Regresar a la pagina principal <a href="<?php echo $html->link("inicio"); ?>">Inicio</a></li>
				</ul>
			</div>
		</div>
	</div>
</div>
<?php include($html->componentView("js")); ?>
</body>
</html>