<?php
$result_pagination=$connection->query("select count(id) as count from permits 
where permits.atended=? and permits.ignored=? and permits.granted='?'",1,0,1);
$num_display=2;
$n_pages=$result_pagination[0]['count']/$num_display;
$current_page=0;
$page_type='list_permissions';
?>
<div class="widget-bottom">
	<div class="dataTables_paginate paging_full_numbers" id="website_pagination">
		<a onclick="getPage(event, 0);" tabindex="0" class="first paginate_button <?php if($current_page==0){ ?>paginate_button_disabled<?php } ?>" id="DataTables_Table_2_first">Primero</a>
		<a onclick="getPage(event, <?php echo $current_page-1; ?>);" tabindex="0" class="previous paginate_button <?php if($current_page==0){ ?>paginate_button_disabled<?php } ?>" id="DataTables_Table_2_previous">Anterior</a>
		<span>
			<?php
				for($i=0;$i<$n_pages;$i++){
					if($i==0){
			?>
						<a tabindex="0" class="paginate_active" onclick="getPage(event,<?php echo $id; ?>);"><?php echo $i+1; ?></a>
			<?php
					}else{
			?>
						<a tabindex="0" class="paginate_button" onclick="getPage(event,<?php echo $id; ?>);"><?php echo $i+1; ?></a>
			<?php
					}
			?>
			<?php
				}
			?>
		</span>
		<a onclick="getPage(event, <?php echo $n_pages; ?>);" tabindex="0" class="next paginate_button <?php if($current_page==$n_pages){ ?>paginate_button_disabled<?php } ?>" id="DataTables_Table_2_next">Siguiente</a>
		<a onclick="getPage(event, <?php echo $current_page+1; ?>);" tabindex="0" class="last paginate_button <?php if($current_page==$n_pages){ ?>paginate_button_disabled<?php } ?>" id="DataTables_Table_2_last">Ultimo</a>
	</div>
	<div class="clear"></div>
</div>
<script>
	var pagiantion=0;
	function getPage(e,page){
		e.preventDefault();
		var current_page=<?php echo $current_page; ?>;
		var n_pages=<?php echo $n_pages; ?>;
		if(page<0||page>n_pages){
			return;
		}
		$.post('<?php echo $html->link('ajax_pagination', 'ajax'); ?>', {type: '<?php echo $page_type; ?>', page: page},
		function(data) {
			//$('#block_elements').unblock();
			if(data.success){
				construct_pagination(data);
			}
		}, "json");
	}
	function construct_pagination(data){
		var current_page=<?php echo $current_page; ?>;
		var disabled="";
		var disabled_last="";
		var num_display=<?php echo $num_display; ?>;
		if(current_page==0){
			disabled="paginate_button_disabled";
		}
		if(current_page>=date.n_pages){
			disabled_last="paginate_button_disabled";
		}
		str+='';
		if(data.success){
			str+='<a onclick="getPage(event, 0);" tabindex="0" class="first paginate_button '+disabled+'" id="DataTables_Table_2_first">Primero</a>';
			str+='<a onclick="getPage(event, '+current_page-1+');" tabindex="0" class="previous paginate_button '+disabled+'" id="DataTables_Table_2_previous">Anterior</a>';
			str+='<span>';
			for(var i=0;i<data.n_pages/num_display;i++){
				if(i==0){
					str+='<a tabindex="0" class="paginate_active" onclick="getPage(event,'+i+');">'+(i+1)+'</a>';
				}else{
					str+='<a tabindex="0" class="paginate_button" onclick="getPage(event,'+i+');">'+(i+1)+'</a>';
				}
			}
			str+='</span>';
			str+='<a onclick="getPage(event, '+date.n_pages+');" tabindex="0" class="next paginate_button '+disabled_last+'" id="DataTables_Table_2_next">Siguiente</a>';
			str+='<a onclick="getPage(event, '+(current_page+1)+');" tabindex="0" class="last paginate_button '+disabled_last+'paginate_button_disabled" id="DataTables_Table_2_last">Ultimo</a>';
		}
	}
</script>