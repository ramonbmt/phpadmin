<!DOCTYPE HTML>
<html lang="en">
<head>
	<?php include($html->componentView("head_old")); ?>
	<script src="http://code.jquery.com/ui/1.8.21/jquery-ui.min.js"></script>
</head>
<body>
	<?php include($html->componentView("topnav_old")); ?>
	<?php include($html->componentView("sidenav_old")); ?>
	<div id="main-content">
		<div class="container-fluid">
			<?php include($html->componentView("breadcrumb")); ?>
			<?php if(isset($error)){ ?>
			<div class="alert alert-error fade in">
				<button data-dismiss="alert" class="close" type="button">x</button>
				<strong>Error!</strong> <?php echo $error; ?>
			</div>
			<?php } ?>
			<div class="row-fluid">
				<div class="span12">
					<div class="nonboxy-widget">
						<div class="widget-head">
							<h5 class="pull-left"> EDITOR3 Beta</h5>
							<div class="btn-group pull-right">

							</div>
						</div>
						<div class="edit_place">
							<?php echo $editor3_general_template_form; ?>
						</div>
						<?php echo $editor3_general_template_ajax; ?>
						<div id="code_container">
							<span id="code"></span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include($html->componentView("js")); ?>
	<script>
		function selectCol(table,col){
			$("input[name='col']").val(table+"."+col);
			$("html, body").animate({ scrollTop: $("input[name='col']").offset().top-55 });
		}
		var pagination=0;
		function construct_table(data){
			//alert("hola1");
			construct_table2(data);
		}
	</script>
</body>
</html>
