<?php
	
?>
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
			<div class="row-fluid">
				<div class="span12">
					<div class="nonboxy-widget">
						<div class="widget-head">
							<h5 class="pull-left"> <?php echo $table_header; ?></h5> 
							<div class="btn-group pull-right">
								<li><a href="<?php echo $table_link; ?>"><span class="color-icons add_co"></span></a></li>
							</div>
						</div>
						<?php
							echo $search_result;
						?>
						<?php echo $table_result; ?>
						<?php
							echo $pagination_result;
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