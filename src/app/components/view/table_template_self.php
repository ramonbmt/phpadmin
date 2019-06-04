<?php
	$str="";
	$i=0;
	$str_get="";
	/*if(isset($_GET)){
		$str_get="";
		$i_str=0;
		foreach($_GET as $key=>$value){
			if($i_str==0){
				$str_get.="/?";
			}
			$str_get.="".$key."=".$value."&";
			$i_str++;
		}
	}*/
	$str_get=http_build_query(array_merge($_GET));
	$action="";
	$requestURI=urldecode($_SERVER['REQUEST_URI']);
	$requestURI = explode('/', $requestURI);
	if(isset($requestURI[2])){
		$action="/".$requestURI[2];
	}
	//$result_temp=array_pop($this->result);
?>
<table class="table table-default table-bordered">
	<thead>
		<tr>
			<?php foreach($this->columns as $key=>$value){ if(!$value["display"]) continue;
				if(isset($value["sort"])&&$value["sort"]==true){
					if($i!=0){
						$str.=",";
					}
					$str.="".$key.":0";
					$i++;
			?>
				<th onclick="sort(event,'<?php echo $key; ?>')" style="cursor:pointer;" id="sorting_<?php echo $key; ?>" class="sorting"><?php echo $value["as"]; ?></th>
			<?php }else{ ?>
				<th><?php echo $value["as"]; ?></th>
			<?php }} ?>
		</tr>
	</thead>
	<tbody id="<?php echo $this->bodyId; ?>">
		<?php
			$i_create=0;
			foreach($this->result as $key=>$value){
				echo "<tr>";
				//print_r($value);
				if($i_create==0){
					foreach($this->columns as $key2=>$value2){
						if($value2["display"]){
							if($value2["link"]){
								//$result_temp[$key2]="<td>".$foo($value[$key2],$value,$key2)."</td>";
								if(isset($value2["td"])&&!$value2["td"]){
									$result_temp[$key2]="'+v.".$key2."+'";
								}else{
									$result_temp[$key2]="<td>'+v.".$key2."+'</td>";
								}
							}else{
								if(isset($value2["type"])){
									switch($value2["type"]){
										case "money":
											$result_temp[$key2]="<td>$'+v.".$key2."+'</td>";
										break;
										case "percent":
											$result_temp[$key2]="<td>'+v.".$key2."+'%</td>";
										break;
										default:
											$result_temp[$key2]="<td>'+v.".$key2."+'</td>";
										break;
									}
								}else{
									$result_temp[$key2]="<td>'+v.".$key2."+'</td>";
								}
							}
						}
					}
				}else{
					foreach($this->columns as $key2=>$value2){
						if($value2["display"]){
							if($value2["link"]){
								$temp=$foo($value[$key2],$value,$key2);
								if($temp==="<#!jumprow!#>"){
									break;
								}
								if(isset($value2["td"])&&!$value2["td"]){
									echo $temp;
								}else{
									echo "<td>".$temp."</td>";
								}
							}else{
								if(isset($value2["type"])){
									switch($value2["type"]){
										case "money":
											echo "<td>$".$value[$key2]."</td>";
										break;
										case "percent":
											echo "<td>".$value[$key2]."%</td>";
										break;
										default:
											echo "<td>".$value[$key2]."</td>";
										break;
									}
								}else{
									echo "<td>".$value[$key2]."</td>";
								}
							}
						}
					}
				}
				$i_create++;
				echo "</tr>";
			}
		?>
	</tbody>
</table>
<script>
	function setValue(e,id,key){
		e.preventDefault();
		$("#"+key+"_"+id).hide();
		$("#set_"+key+"_"+id).show();
	}
	function unsetValue(e,id,key){
		e.preventDefault();
		$("#"+key+"_"+id).show();
		$("#set_"+key+"_"+id).hide();
	}
	function updateValue_<?php echo $this->bodyId; ?>(e,id,key,index){
		e.preventDefault();
		index = typeof index !== 'undefined' ? index : 'id';
		var val=$("#new_"+key+"_"+id).val();
		$.post('<?php echo $html->link($this->page_name).$action."/?".$str_get; ?>', {page:0,ajax:1,action:"update",id:id, str: key, type: index,val:val,table:"<?php echo $this->bodyId; ?>"},
		function(data) {
			$("#"+key+"_"+id).show();
			$("#set_"+key+"_"+id).hide();
			location.reload();
		}, "json");
	}
</script>
<script>
	function downloadReport(e){
		e.preventDefault();
		$.post('<?php echo $html->link($this->page_name).$action."/?".$str_get; ?>', {page:0,ajax:1,action:"excelSearch",id:0, str: "", type: '',table:"<?php echo $this->bodyId; ?>"},
		function(data) {

		}, "json");
	}	
</script>
<script>
	function construct_table_<?php echo $this->bodyId; ?>(data){
		str="";
		//alert("hola2");
		if(typeof data.page_result_<?php echo $this->bodyId; ?> != 'undefined'){
			$.each(data.page_result_<?php echo $this->bodyId; ?>, function (i,v){
				str+='<tr>';
				<?php foreach($result_temp as $value){ ?>
					<?php echo "str+='".$value."';"; ?>
				<?php } ?>
				str+='</tr>';
			});
			$("#<?php echo $this->bodyId; ?>").html(str);
		}
	}
	var old_foo = construct_table2;
	construct_table2 = (function() {
		var cached_function = construct_table2;
		return function(data) {
			//alert("hola3");
			construct_table_<?php echo $this->bodyId; ?>(data);
			if(typeof construct_pagination_<?php echo $this->bodyId; ?> !='undefined')construct_pagination_<?php echo $this->bodyId; ?>(data,"<?php echo $this->bodyId; ?>");
			cached_function.apply(this, arguments); // use .apply() to call it
		};
	}());
</script>
<script>
	function delete_<?php echo $this->bodyId; ?>(e,id,str){
		e.preventDefault();
		if(confirm("Esta seguro que lo desea borrar?")){
			$.post('<?php echo $html->link($this->page_name).$action."/?".$str_get; ?>', { page:0,ajax:1,action:"delete",id:id, str: str, type: '',table:"<?php echo $this->bodyId; ?>"},
			function(data) {
				//$('#block_elements').unblock();
				if(typeof data.success!='undefined'&&data.success==1){
					construct_table(data);
				}
			}, "json");
		}
	}
</script>
<script>
		var sorting = {
			<?php echo $str; ?>
		};
		function sort(e,name){
			if(sorting[name]==0){
				$("#sorting_"+name).removeClass("sorting");
				$("#sorting_"+name).removeClass("sorting_asc");
				$("#sorting_"+name).addClass("sorting_desc");
				sorting[name]=1;
				var sort=$.toJSON(sorting);
				$.post('<?php echo $html->link($this->page_name).$action."/?".$str_get; ?>', { page:0,ajax:1,action:"sort",id:0, str: sort, type: 'sort_<?php echo $this->bodyId; ?>',table:"<?php echo $this->bodyId; ?>"},
				function(data) {
					//$('#block_elements').unblock();
					if(typeof data.success!='undefined'&&data.success==1){
						construct_table(data);
					}
				}, "json");
			}else if(sorting[name]==1){
				$("#sorting_"+name).removeClass("sorting");
				$("#sorting_"+name).removeClass("sorting_desc");
				$("#sorting_"+name).addClass("sorting_asc");
				sorting[name]=2;
				var sort=$.toJSON(sorting);
				$.post('<?php echo $html->link($this->page_name).$action."/?".$str_get; ?>', { page:0,ajax:1,action:"sort",id:0, str: sort, type: 'sort_<?php echo $this->bodyId; ?>',table:"<?php echo $this->bodyId; ?>"},
				function(data) {
					//$('#block_elements').unblock();
					if(typeof data.success!='undefined'&&data.success==1){
						construct_table(data);
					}
				}, "json");
			}else if(sorting[name]>1){
				$("#sorting_"+name).removeClass("sorting_desc");
				$("#sorting_"+name).removeClass("sorting_asc");
				$("#sorting_"+name).addClass("sorting");
				sorting[name]=0;
			}
			
		}
</script>