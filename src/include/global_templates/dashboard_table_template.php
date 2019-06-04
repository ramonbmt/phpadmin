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
	if(strrpos($action, "?")!==false){
		$action="";
	}
	$showMobile="";
	//$result_temp=array_pop($this->result);
?>
<div style="height:100%;width:100%;">
<table class="dashboard-table table table-default table-bordered table-color-odd-even">
	<thead>
		<tr>
			<?php foreach($this->columns as $key=>$value){ if(!$value["display"]) continue;
				if(isset($value["showMobile"])&&$value["showMobile"]==false){
					$showMobile="class='hidden-phone'";
				}else{
					$showMobile="";
				}
				if(isset($value["sort"])&&$value["sort"]==true){
					if($i!=0){
						$str.=",";
					}
					$str.="".$key.":0";
					$i++;
			?>
				<th <?php echo $showMobile; ?> onclick="sort_<?php echo $this->bodyId; ?>(event,'<?php echo $key; ?>')" style="cursor:pointer;" id="sorting_<?php echo $key; ?>" class="sorting"><?php echo $value["as"]; ?></th>
			<?php }else{ ?>
				<th <?php echo $showMobile; ?>><?php echo $value["as"]; ?></th>
			<?php }} ?>
		</tr>
	</thead>
	<tbody id="<?php echo $this->bodyId; ?>">
		<?php
			$i_create=0;
			foreach($this->result as $key=>$value){
				//print_r($value);
				if($i_create==0){
					foreach($this->columns as $key2=>$value2){
						if($value2["display"]){
							if(isset($value2["showMobile"])&&$value2["showMobile"]==false){
								$showMobile="class=\'hidden-phone\'";
							}else{
								$showMobile="";
							}
							if($value2["link"]){
								//$result_temp[$key2]="<td>".$foo($value[$key2],$value,$key2)."</td>";
								if(isset($value2["td"])&&!$value2["td"]){
									$result_temp[$key2]="'+v.".$key2."+'";
								}else{
									$result_temp[$key2]="<td ".$showMobile.">'+v.".$key2."+'</td>";
								}
							}else{
								if(isset($value2["type"])){
									switch($value2["type"]){
										case "money":
											$result_temp[$key2]="<td ".$showMobile.">$'+v.".$key2."+'</td>";
										break;
										case "percent":
											$result_temp[$key2]="<td ".$showMobile.">'+v.".$key2."+'%</td>";
										break;
										default:
											$result_temp[$key2]="<td ".$showMobile.">'+v.".$key2."+'</td>";
										break;
									}
								}else{
									$result_temp[$key2]="<td ".$showMobile.">'+v.".$key2."+'</td>";
								}
							}
						}
					}
				}else{
					echo "<tr>";
					foreach($this->columns as $key2=>$value2){
						if($value2["display"]){
							if(isset($value2["showMobile"])&&$value2["showMobile"]==false){
								$showMobile="class='hidden-phone'";
							}else{
								$showMobile="";
							}
							if($value2["link"]){
								$temp=$foo($value[$key2],$value,$key2);
								if($temp==="<#!jumprow!#>"){
									break;
								}
								if(isset($value2["td"])&&!$value2["td"]){
									echo $temp;
								}else{
									echo "<td ".$showMobile.">".$temp."</td>";
								}
							}else{
								if(isset($value2["type"])){
									switch($value2["type"]){
										case "money":
											echo "<td ".$showMobile.">$".$value[$key2]."</td>";
										break;
										case "percent":
											echo "<td ".$showMobile.">".$value[$key2]."%</td>";
										break;
										default:
											echo "<td ".$showMobile.">".$value[$key2]."</td>";
										break;
									}
								}else{
									echo "<td ".$showMobile.">".$value[$key2]."</td>";
								}
							}
						}
					}
					echo "</tr>";
				}
				$i_create++;
			}
		?>
	</tbody>
</table>
</div>
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
			//location.reload();
			construct_table(data);
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
<script>
function post(path, params, method) {
    method = method || "post"; // Set method to post by default if not specified.

    // The rest of this code assumes you are not using a library.
    // It can be made less wordy if you use one.
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);

    for(var key in params) {
        if(params.hasOwnProperty(key)) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);

            form.appendChild(hiddenField);
         }
    }

    document.body.appendChild(form);
    form.submit();
}
</script>
<script>
	function openImage(e, link) {
		e.preventDefault();
		$.colorbox({
			href: "<?php echo $html->link("home"); ?>"+link,
			width: "auto"
		});
	}
</script>
<script>
	function downloadReport_<?php echo $this->bodyId; ?>(e,engine){
		e.preventDefault();
		engine = typeof engine !== 'undefined' ? engine : "excelSearch";
		/*$.post('<?php echo $html->link($this->page_name).$action."/?".$str_get; ?>', {page:0,ajax:1,action:"excel",id:0, str: "", type: '',table:"<?php echo $this->bodyId; ?>"},
		function(data) {

		}, "json");*/
		var sort=$.toJSON(sorting_<?php  echo $this->bodyId; ?>);
		var str2={};
		$(".ajax_search_<?php echo $this->bodyId; ?>").map(function(){
			str2[$(this).attr("name")]=$(this).val();
		});
		var str=$.toJSON(str2);
		var args=<?php echo json_encode($this->args); ?>;
		post('<?php echo $html->link($this->page_name).$action."/?".$str_get; ?>', {action:engine,ajax:1,type: '', page: 0,
		args:args, ajax:1, sort:sort, str:str,table:"<?php echo $this->bodyId; ?>"});
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
			if(typeof construct_table_<?php echo $this->bodyId; ?>!='undefined') construct_table_<?php echo $this->bodyId; ?>(data);
			if(typeof construct_pagination_<?php echo $this->bodyId; ?>!='undefined') construct_pagination_<?php echo $this->bodyId; ?>(data,"<?php echo $this->bodyId; ?>");
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
				if(typeof data.success!='undefined'){
					construct_table(data);
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
	}
</script>
<script>
		var sorting_<?php echo $this->bodyId; ?> = {
			<?php echo $str; ?>
		};
		var sorting= {
			<?php echo $str; ?>
		};
		function sort_<?php echo $this->bodyId; ?>(e,name){
			var str2={};
			$(".ajax_search_<?php echo $this->bodyId; ?>").map(function(){
				str2[$(this).attr("name")]=$(this).val();
			});
			var str=$.toJSON(str2);
			if(sorting_<?php echo $this->bodyId; ?>[name]==0){
				$("#sorting_"+name).removeClass("sorting");
				$("#sorting_"+name).removeClass("sorting_asc");
				$("#sorting_"+name).addClass("sorting_desc");
				sorting_<?php echo $this->bodyId; ?>[name]=1;
				var sort=$.toJSON(sorting_<?php echo $this->bodyId; ?>);
				$.post('<?php echo $html->link($this->page_name).$action."/?".$str_get; ?>', { page:0,ajax:1,action:"sort",id:0, sort: sort, str: str, type: 'sort_<?php echo $this->bodyId; ?>',table:"<?php echo $this->bodyId; ?>"},
				function(data) {
					//$('#block_elements').unblock();
					if(typeof data.success!='undefined'&&data.success==1){
						construct_table(data);
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
			}else if(sorting_<?php echo $this->bodyId; ?>[name]==1){
				$("#sorting_"+name).removeClass("sorting");
				$("#sorting_"+name).removeClass("sorting_desc");
				$("#sorting_"+name).addClass("sorting_asc");
				sorting_<?php echo $this->bodyId; ?>[name]=2;
				var sort=$.toJSON(sorting_<?php echo $this->bodyId; ?>);
				$.post('<?php echo $html->link($this->page_name).$action."/?".$str_get; ?>', { page:0,ajax:1,action:"sort",id:0, sort: sort, str: str, type: 'sort_<?php echo $this->bodyId; ?>',table:"<?php echo $this->bodyId; ?>"},
				function(data) {
					//$('#block_elements').unblock();
					if(typeof data.success!='undefined'&&data.success==1){
						construct_table(data);
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
			}else if(sorting_<?php echo $this->bodyId; ?>[name]>1){
				$("#sorting_"+name).removeClass("sorting_desc");
				$("#sorting_"+name).removeClass("sorting_asc");
				$("#sorting_"+name).addClass("sorting");
				sorting_<?php echo $this->bodyId; ?>[name]=0;
			}
		}
</script>
