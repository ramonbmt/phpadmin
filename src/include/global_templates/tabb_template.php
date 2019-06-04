<div class="box-tab">
<div class="tabbable">
	<!-- Only required for left/right tabs -->
	<ul class="nav nav-tabs">
		<?php $i=0;foreach($this->tabbs as $key=>$value){ ?>
		<li <?php if($i==0){ ?>class="active"<?php } ?>><a href="#tab<?php echo $i; ?>" data-toggle="tab"><i class="black-icons <?php echo $value["icon"]; ?>"></i><?php echo $value["title"]; ?></a></li>
		<?php $i++;} ?>
	</ul>
	<div class="tab-content">
		<?php $i=0;foreach($this->tabbs as $key=>$value){ ?>
		<div class="tab-pane <?php if($i==0){ ?>active<?php } ?>" id="tab<?php echo $i; ?>">
			<?php echo $value["body"]; ?>
		</div>
		<?php $i++;} ?>
	</div>
</div>
</div>
