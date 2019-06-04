<div id="prop_creator_<?php echo $this->propId; ?>">
	<?php echo $this->data["page_result_prop_".$this->propId]; ?>
</div>
<script>
	function construct_table_prop_<?php echo $this->propId; ?>(data){
		str="";
		if(typeof data.page_result_prop_<?php echo $this->propId; ?> != 'undefined'){
			//alert("hola");
			$("#prop_creator_<?php echo $this->propId; ?>").html(data.page_result_prop_<?php echo $this->propId; ?>);
		}
	}
	var old_foo = construct_table2;
	construct_table2 = (function() {
		var cached_function = construct_table2;
		return function(data) {
			//alert("hola3");
			if(typeof construct_table_prop_<?php echo $this->propId; ?>!='undefined') construct_table_prop_<?php echo $this->propId; ?>(data);
			cached_function.apply(this, arguments); // use .apply() to call it
		};
	}());
</script>