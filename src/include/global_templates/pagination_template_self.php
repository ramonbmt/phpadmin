<?php
	$str="";
	/*if(isset($_REQUEST)){
		$str="";
		$i_str=0;
		foreach($_REQUEST as $key=>$value){
			if($i_str==0){
				$str.="/?";
			}
			$str.="".$key."=".$value."&";
			$i_str++;
		}
	}*/
	//var_dump($this);
	
	$action="";
	$requestURI=urldecode($_SERVER['REQUEST_URI']);
	$requestURI = explode('/', $requestURI);
	if(isset($requestURI[2])){
		$action="/".$requestURI[2];
	}
	if(strrpos($action, "?")!==false){
		$action="";
	}
	$str=http_build_query(array_merge($_GET,$_POST));
	//echo $this->n_pages;
?>
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
		<a onclick="getPage_<?php  echo $this->page_type; ?>(event, <?php echo ceil($this->n_pages-1); ?>,'<?php echo $this->page_type; ?>');" tabindex="0" class="last paginate_button <?php if($this->current_page==$this->n_pages){ ?>paginate_button_disabled<?php } ?>" id="DataTables_Table_2_last">Ultimo</a>
	</div>
	<div class="clear"></div>
</div>
<script>
	var pagiantion=0;
	var current_page=<?php echo $this->current_page; ?>;
	var args=<?php echo json_encode($this->args); ?>;
	function getPage_<?php  echo $this->page_type; ?>(e,page,page_type){
		e.preventDefault();
		current_page=page;
		var n_pages=<?php echo $this->n_pages; ?>;
		if(page<0||page>n_pages){
			//alert("hola");
			return;
		}
		var sort=$.toJSON(sorting_<?php  echo $this->page_type; ?>);
		var str2={};
		$(".ajax_search_<?php echo $this->page_type; ?>").map(function(){
			str2[$(this).attr("name")]=$(this).val();
		});
		var str=$.toJSON(str2);
		$.post('<?php echo $html->link($this->filename).$action."/?".$str; ?>', {action:"pagination",ajax:1,type: page_type, page: page, 
		args:args, ajax:1, sort:sort, str:str,table:"<?php echo $this->page_type; ?>"},
		function(data) {
			//$('#block_elements').unblock();
			if(typeof data.success!='undefined'){
				if (typeof construct_table == 'function') { 
					construct_table(data); 
				}
				construct_pagination_<?php echo $this->page_type; ?>(data,page_type);
			}
		}, "json");
	}
	function construct_pagination_<?php echo $this->page_type; ?>(data,page_type){
		var disabled="";
		var disabled_last="";
		var num_display=<?php echo $this->num_display; ?>;
		var n_pages=0;
		var more_pages=0;
		//var page_type="<?php echo $this->page_type; ?>";
		if(current_page==0){
			disabled="paginate_button_disabled";
		}
		data.count=data["count_"+page_type];
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
		var str='';
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
		str+='<a onclick="getPage_'+page_type+'(event, '+Math.ceil(data.count/num_display-1)+', \''+page_type+'\');" tabindex="0" class="last paginate_button '+disabled_last+'" id="DataTables_Table_2_last">Ultimo</a>';
		$("#website_pagination_"+page_type).html(str);
	}
</script>