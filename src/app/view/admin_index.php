<?php
forceLogin("login");
$breadcrumb=array(["link"=>"admin_index","name"=>"Inicio"]);
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
	<?php include($html->componentView("head")); ?>
</head>
<body>

<style>
.span2{
	margin-left:0px!important;
	margin-right:15px!important;
	}
</style>
<?php include($html->componentView("topnav")); ?>
<?php include($html->componentView("sidenav")); ?>
<div id="main-content">
	<div class="container-fluid">
		<?php include($html->componentView("breadcrumb")); ?>
		<div class="dashboard-widget">
			<div class="row-fluid">
				<?php foreach($admin_index as $key=>$value){
						if((isset($value["checkPermiso"])&&checkPermiso($value["checkPermiso"]))||!isset($value["checkPermiso"])){
					?>
					<div class="span2">
						<div class="dashboard-wid-wrap" style="margin-bottom:10px">
							<div class="dashboard-wid-content">
								<a href="<?php echo $value["path"]; ?>" style="min-height:83px">
									<i class="dashboard-icons <?php echo $value["icon"]; ?>" ></i>
									<span class="dasboard-icon-title"><?php echo $value["name"]; ?></span>
								</a>
							</div>
						</div>
					</div>
				<?php }} ?>
			</div>
		</div>
	</div>
</div>
<?php include($html->componentView("js")); ?>
</body>
</html>
