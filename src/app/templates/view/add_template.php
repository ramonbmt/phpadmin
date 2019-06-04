<!DOCTYPE HTML>
<html lang="en">
<head>
	<?php include($html->componentView("head")); ?>
</head>
<body>
	<?php include($html->componentView("topnav")); ?>
	<?php include($html->componentView("sidenav")); ?>
<div id="main-content">
	<div class="container-fluid">
		<?php include($html->componentView("breadcrumb")); ?>
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
		<div class="row-fluid">
			<div class="span7" style="margin:auto;float:none;">
				<div class="nonboxy-widget">
					<div class="widget-head">
						<h5> <?php echo $title; ?></h5>
					</div>
					<?php
						/*$form->createForm(function(){
						
						},$validator);*/
						echo $form;
					?>
					
				</div>
			</div>
		</div>
	</div>
</div>
	<?php include($html->componentView("js")); ?>
	<script>
		var pagination=0;
		function construct_table(data){
			//alert("hola1");
			construct_table2(data);
		}
	</script>
</body>
</html>