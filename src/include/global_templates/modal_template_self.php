<?php
	$str="";
	$i=0;
	$str_get="";
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
	$vars="";
	$var="var temp=[";
	foreach($this->params as $value){
		$vars.=$value.":".$value.",";
		$var.='"'.$value.'",';
	}
	$var.="];";
?>
<script>
	function modalFunc_<?php echo $this->modalId; ?>(){
		<?php echo $var; ?>
		arguments[0].preventDefault();
		for (var i = 1; i < arguments.length; i++) {
			window[temp[i-1]]=arguments[i];
		}
		$.post('<?php echo $html->link($this->page_name).$action."/?".$str_get; ?>', {page:0,ajax:1,action:"modal",modalSel:"<?php echo $this->modalId; ?>",<?php echo $vars; ?>},
		function(data) {
			if(typeof data.success!='undefined'&&data.success==1){
				$.colorbox({
					width: "630px",
					html:data.html,
				});
				construct_table(data);
			}else if(typeof data.error!='undefined'){
				alert(data.error);
			}else if(typeof data.jumpTo!='undefined'){
				window.location=data.jumpTo;
			}else if(typeof data.eval!='undefined'){
				eval(data.eval);
			}
		}, "json");
		
	}
</script>	