<div class="dataTables_filter" id="DataTables_Table_0_filter" style="width: 100%">
	<label>Buscar: <input type="text" id="ajax_search"></label>
</div>
<script>
	$(function(){
		$("#ajax_search").keyup($.debounce(250,ajax_send));
	});
	function ajax_send(){
		var page=0;
		var args=<?php echo json_encode($this->args); ?>;
		var str=$("#ajax_search").val();
		$.post('<?php echo $html->link('ajax_search', 'ajax'); ?>', {type:'<?php echo $this->page_type; ?>',page:page,args:args,str:str},
		function(data) {
			//$('#block_elements').unblock();
			if(data.success){
				if (typeof construct_table == 'function') { 
					construct_table(data); 
					//alert("construir");
				}
				//construct_pagination(data);
			}
		}, "json");
	}
</script>
<script src="/js/debounce.js"></script>