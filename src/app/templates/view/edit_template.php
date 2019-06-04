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
					<h5 class="pull-left"> <?php echo $edit_title; ?></h5> 
					<div class="btn-group pull-right">
						<li><a href="#" onclick="startEdit(event,'display_user');"><span class="color-icons page_white_edit_co"></span></a></li>
					</div>
				</div>
				<?php echo $edit; ?>
				<?php if(isset($table_title)){ ?>
				<div class="widget-head">
					<h5 class="pull-left"> <?php echo $table_title; ?></h5> 
					
				</div>
				<?php echo $table; ?>
				<?php } ?>
			</div>
		</div>
	</div>
    
    
  </div>
</div>
	<?php include($html->componentView("js")); ?>
	<script src="/js/debounce.js"></script>
	<script>
		pagination=0;
		function construct_table(data){
			construct_table2(data);
		}
	</script>
</body>
</html>