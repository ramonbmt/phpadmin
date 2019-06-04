<canvas id="<?php echo $this->clx_id; ?>" width="auto" height="auto" style="width: auto !important; height: auto !important;"></canvas>
<script>
	var <?php echo $this->clx_id; ?> = $("#<?php echo $this->clx_id; ?>");
	var chartData = <?php echo $this->c_data; ?>;
	var chartOptions = <?php echo $this->options; ?>;

	var chart_<?php echo $this->clx_id; ?> = new Chart(<?php echo $this->clx_id; ?>,{
		type: <?php echo $this->type; ?>,
		data: chartData,
		options: chartOptions
	});
</script>
