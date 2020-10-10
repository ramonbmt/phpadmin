			<!-- Only required for left/right tabs -->
			<ul class="nav nav-pills mb-3" id="ex1" role="tablist">
				<?php $i=0;foreach($this->tabbs as $key=>$value){ ?>

					<li class="nav-item" role="presentation">
						<a class="nav-link<?php if($i==0){ echo ' active show'; } ?>" href="#tab<?php echo $i; ?>" data-toggle="pill">
							<i class="<?php echo $value["icon"]; ?>"></i> <?php echo $value["title"]; ?>
							<div class="ripple-container"></div>
						</a>
					</li>
				<?php $i++;} ?>
			</ul>
		</div> <!-- nav-tabs-wrapper -->
	</div> <!-- nav-tabs-navigation -->
</div> <!-- card-header card-header-tabs card-header-rose -->


<div class="card-body">
	<div class="tab-content">
		<?php $i=0;foreach($this->tabbs as $key=>$value){ ?>
		<div class="tab-pane<?php if($i==0){ ?> fade active show<?php } ?>" id="tab<?php echo $i; ?>" role="tabpanel">
			<?php echo $value["body"]; ?>
		</div>
		<?php $i++;} ?>
	</div>
</div>
