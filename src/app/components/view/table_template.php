<?php
	$str="";
	$i=0;
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
					$str.=$key.":0";
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
								$result_temp[$key2]="<td>'+v.".$key2."+'</td>";
							}else{
								$result_temp[$key2]="<td>'+v.".$key2."+'</td>";
							}
						}
					}
				}else{
					foreach($this->columns as $key2=>$value2){
						if($value2["display"]){
							if($value2["link"]){
								echo "<td>".$foo($value[$key2],$value,$key2)."</td>";
							}else{
								echo "<td>".$value[$key2]."</td>";
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
	/*$(function(){
		if(flag_table==1){
			function construct_table2(data){
				str="";
				alert("hola2");
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
			flag_table+=1;
		}else{
			var old_foo = construct_table2;
			construct_table2 = function(data) {
				alert("hola3");
				old_foo(data);
				construct_table2(data);
			}
		}
	});*/
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
			cached_function.apply(this, arguments); // use .apply() to call it
		};
	}());
	/*$(function(){
		if(flag_table==1){
			if(typeof consturct_table2 != 'undefined'){
				var old_foo = construct_table2;
			}else{
				var old_foo = function(){};
			}
			construct_table2 = function(data) {
				alert("hola3");
				old_foo(data);
				construct_table_<?php echo $this->bodyId; ?>(data);
			}
			flag_table=0;
		}
	});*/
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
				$.post('<?php echo $html->link('ajax_sort', 'ajax'); ?>', { id:0, str: sort, type: 'sort_<?php echo $this->bodyId; ?>'},
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
				$.post('<?php echo $html->link('ajax_sort', 'ajax'); ?>', { id:0, str: sort, type: 'sort_<?php echo $this->bodyId; ?>'},
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