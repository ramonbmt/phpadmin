<?php
	/*if(isset($_GET)){
		$str="";
		$i_str=0;
		foreach($_GET as $key=>$value){
			if($i_str==0){
				$str.="/?";
			}
			$str.="".$key."=".$value."&";
			$i_str++;
		}
	}*/
	//print_r($this->columns);

	$action="";
	$requestURI=urldecode($_SERVER['REQUEST_URI']);
	$requestURI = explode('/', $requestURI);
	if(isset($requestURI[2])){
		$action="/".$requestURI[2];
	}
	if(strrpos($action, "?")!==false){
		$action="";
	}
	$str=$action."/?".http_build_query(array_merge($_GET,$_POST));
	$temp_arr=array();
	foreach($this->columns as $key=>$value){
		if(isset($value["searchfull"])&&$value["searchfull"]==1){
			$temp_arr[]=array(
				'key'=>$key,
				'as'=>$value["as"],
			);
		}
	}
	//print_r($temp_arr);
?>
<?php
	if(!empty($temp_arr)){
		?>
		<button style="margin-bottom:10px;" id="search_button" onclick="event.preventDefault();$('.dataTables_filter').show();$('#search_button').hide();" class="btn">
		<i class="black-icons"></i>
		<span>Buscar</span>
		</button>
		<div style="display:none;margin-bottom:10px;width: 100%" class="dataTables_filter" id="DataTables_Table_0_filter">
			<table align="left">
				<tbody>

		<?php foreach($temp_arr as $key=>$value){ ?>

		<tr><td><label>Busqueda por <?php echo $value["as"]; ?>: &nbsp;  </label></td>
		<td><input type="text" name="<?php echo $value["key"]; ?>" class="ajax_search_<?php echo $this->page_type; ?>"></td></tr>

		<?php }	?>
					<tr><td colspan="2" style="text-align: center; padding-left: 0px;" ><button onclick="event.preventDefault();$('.dataTables_filter').hide();$('#search_button').show();" class="btn btn-warning">Cancelar</button></td></tr>
				</tbody>
			</table>
		</div>
		<?php
	}else{
?>
<div class="dataTables_filter" id="DataTables_Table_0_filter" style="width: 100%">
	<label>Buscar: <input type="text" name="search" class="ajax_search_<?php echo $this->page_type; ?>"></label>
</div>
<?php
	}
?>
<script>
	$(function(){
		$(".ajax_search_<?php echo $this->page_type; ?>").keyup($.debounce(250,ajax_send_<?php echo $this->page_type; ?>));
	});
	function ajax_send_<?php echo $this->page_type; ?>(){
		var page=0;
		var args=<?php echo json_encode($this->args); ?>;
		//var str=$(".ajax_search_<?php echo $this->page_type; ?>").val();
		var sort=JSON.stringify(sorting_<?php echo $this->page_type; ?>);
		var str2={};
		$(".ajax_search_<?php echo $this->page_type; ?>").map(function(){
			str2[$(this).attr("name")]=$(this).val();
		});
		var str=JSON.stringify(str2);
		$.post('<?php echo $html->link($this->page_name).$str; ?>', {action:"search",ajax:1,type:'<?php echo $this->page_type; ?>',
		page:page,args:args,str:str,sort:sort,table:"<?php echo $this->page_type; ?>"},
		function(data) {
			//$('#block_elements').unblock();
			if(typeof data.success!='undefined'){
				if (typeof construct_table == 'function') {
					construct_table(data);
					//alert("construir");
				}
				//construct_pagination(data);
			}
			if(typeof data.error!="undefined"){
				alert(data.error);
			}else if(typeof data.jumpTo!='undefined'){
				changePage(data.jumpTo);
				//window.location=data.jumpTo;
			}else if(typeof data.eval!='undefined'){
				eval(data.eval);
			}
		}, "json");
	}
</script>
<script src="/js/debounce.js"></script>
