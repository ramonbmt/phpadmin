<div class="widget-bottom">
	<div class="dataTables_paginate paging_full_numbers" id="website_pagination_<?php echo $this->page_type; ?>">
		<a onclick="getPage_<?php  echo $this->page_type; ?>(event, 0,'<?php echo $this->page_type; ?>');" tabindex="0" class="first paginate_button <?php if($this->current_page==0){ ?>paginate_button_disabled<?php } ?>" id="DataTables_Table_2_first">Primero</a>
		<a onclick="getPage_<?php  echo $this->page_type; ?>(event, <?php echo $this->current_page-1; ?>,'<?php echo $this->page_type; ?>');" tabindex="0" class="previous paginate_button <?php if($this->current_page==0){ ?>paginate_button_disabled<?php } ?>" id="DataTables_Table_2_previous">Anterior</a>
		<span>
			<?php
				if($this->n_pages>10)
					$n=10;
				else
					$n=$this->n_pages;
				for($i=0;$i<$n;$i++){
					if($i==0){
			?>
						<a tabindex="0" class="paginate_active" onclick="getPage_<?php  echo $this->page_type; ?>(event,<?php echo $i; ?>,'<?php echo $this->page_type; ?>');"><?php echo $i+1; ?></a>
			<?php
					}else{
			?>
						<a tabindex="0" class="paginate_button" onclick="getPage_<?php  echo $this->page_type; ?>(event,<?php echo $i; ?>,'<?php echo $this->page_type; ?>');"><?php echo $i+1; ?></a>
			<?php
					}
			?>
			<?php
				}
				if($this->n_pages>10){
			?>
				... <a tabindex="0" class="paginate_button" onclick="getPage_<?php  echo $this->page_type; ?>(event,<?php echo floor($this->n_pages); ?>,'<?php echo $this->page_type; ?>');"><?php echo floor($this->n_pages+1); ?></a>
			<?php
				}
			?>
		</span>
		<a onclick="getPage_<?php  echo $this->page_type; ?>(event, <?php echo $this->current_page+1; ?>,'<?php echo $this->page_type; ?>');" tabindex="0" class="next paginate_button <?php if($this->current_page==$this->n_pages){ ?>paginate_button_disabled<?php } ?>" id="DataTables_Table_2_next">Siguiente</a>
		<a onclick="getPage_<?php  echo $this->page_type; ?>(event, <?php echo floor($this->n_pages); ?>,'<?php echo $this->page_type; ?>');" tabindex="0" class="last paginate_button <?php if($this->current_page==$this->n_pages){ ?>paginate_button_disabled<?php } ?>" id="DataTables_Table_2_last">Ultimo</a>
	</div>
	<div class="clear"></div>
</div>
<script>
	var pagiantion=0;
	var current_page=<?php echo $this->current_page; ?>;
	var args=[];
	args=<?php echo json_encode($this->args); ?>;
	function getPage_<?php  echo $this->page_type; ?>(e,page,page_type){
		e.preventDefault();
		current_page=page;
		if(typeof sorting != 'undefined'){
			//args[1]=$.toJSON(sorting);
		}
		var n_pages=<?php echo $this->n_pages; ?>;
		if(page<0||page>n_pages){
			//alert("hola");
			return;
		}
		$.post('<?php echo $html->link('ajax_pagination', 'ajax'); ?>', {type: page_type, page: page, args:args},
		function(data) {
			//$('#block_elements').unblock();
			if(data.success){
				if (typeof construct_table == 'function') { 
					construct_table(data); 
				}
				construct_pagination(data,page_type);
			}
		}, "json");
	}
	function construct_pagination(data,page_type){
		var disabled="";
		var disabled_last="";
		var num_display=<?php echo $this->num_display; ?>;
		var n_pages=0;
		var more_pages=0;
		if(current_page==0){
			disabled="paginate_button_disabled";
		}
		if(current_page>=(data.count/num_display-1)){
			disabled_last="paginate_button_disabled";
		}
		if(data.count/num_display>9){
			n_pages=9;
			if(Math.ceil(data.count/num_display-1)<=(current_page+5)){
				more_pages=0;
			}else{
				more_pages=1;
			}
		}else{
			n_pages=data.count/num_display;
		}
		var offset=0;
		str='';
		str+='<a onclick="getPage_'+page_type+'(event, 0, \''+page_type+'\');" tabindex="0" class="first paginate_button '+disabled+'" id="DataTables_Table_2_first">Primero</a>';
		str+='<a onclick="getPage_'+page_type+'(event, '+(current_page-1)+', \''+page_type+'\');" tabindex="0" class="previous paginate_button '+disabled+'" id="DataTables_Table_2_previous">Anterior</a>';
		str+='<span>';
		/*for(var i=0;i<data.count/num_display;i++){
			if(i==current_page){
				str+='<a tabindex="0" class="paginate_active" onclick="getPage_'+page_type+'(event,'+i+',\''+page_type+'\');">'+(i+1)+'</a>';
			}else{
				str+='<a tabindex="0" class="paginate_button" onclick="getPage_'+page_type+'(event,'+i+',\''+page_type+'\');">'+(i+1)+'</a>';
			}
		}*/
		if(current_page>4){
			if(data.count/num_display<(current_page+5)){
				n_pages=Math.floor(data.count/num_display);
			}else{
				n_pages=current_page+5;
			}
			//alert(current_page);
			for(var i=(current_page-4);i<=n_pages;i++){
				if(i==current_page){
					str+='<a tabindex="0" class="paginate_active" onclick="getPage_'+page_type+'(event,'+i+', \''+page_type+'\');">'+(i+1)+'</a>';
				}else{
					str+='<a tabindex="0" class="paginate_button" onclick="getPage_'+page_type+'(event,'+i+', \''+page_type+'\');">'+(i+1)+'</a>';
				}
			}
		}else{	
			for(var i=0;i<n_pages;i++){
				if(i==current_page){
					str+='<a tabindex="0" class="paginate_active" onclick="getPage_'+page_type+'(event,'+i+', \''+page_type+'\');">'+(i+1)+'</a>';
				}else{
					str+='<a tabindex="0" class="paginate_button" onclick="getPage_'+page_type+'(event,'+i+', \''+page_type+'\');">'+(i+1)+'</a>';
				}
			}
		}
		if(more_pages){
			str+='... <a tabindex="0" class="paginate_button" onclick="getPage_'+page_type+'(event,'+Math.floor(data.count/num_display)+', \''+page_type+'\');">'+(Math.floor(data.count/num_display)+1)+'</a>';
		}
		if(Math.floor(data.count/num_display)==current_page){
			//str+='<a tabindex="0" class="paginate_active" onclick="getPage_'+page_type+'(event,'+current_page+', \''+page_type+'\');">'+(current_page+1)+'</a>';
		}
		str+='</span>';
		str+='<a onclick="getPage_'+page_type+'(event, '+(current_page+1)+', \''+page_type+'\');" tabindex="0" class="next paginate_button '+disabled_last+'" id="DataTables_Table_2_next">Siguiente</a>';
		str+='<a onclick="getPage_'+page_type+'(event, '+Math.floor(data.count/num_display)+', \''+page_type+'\');" tabindex="0" class="last paginate_button '+disabled_last+'" id="DataTables_Table_2_last">Ultimo</a>';
		$("#website_pagination_"+page_type).html(str);
	}
</script>