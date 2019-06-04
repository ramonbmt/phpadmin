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
	$alones=array();
	$result_temp=array();
	$result_temp2=array();
	
?>
<style>
	.table-condensed td{
		padding-right:30px;
	}
</style>	
<table id="<?php echo $this->tableId; ?>_display" class="table table-condensed table-striped" style="width: inherit;background-color:#fff;">
	<?php echo $this->data["page_result_".$this->tableId]["edit1"]; ?>
</table>
<form id="<?php echo $this->tableId; ?>_edit_form" method="post" enctype="multipart/form-data" action="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>">
<table id="<?php echo $this->tableId; ?>_edit" class="table table-striped table-bordered" style="width: inherit;display:none;background-color:#fff;">
	<?php echo $this->data["page_result_".$this->tableId]["edit2"]; ?>
</table>
</form>
<script type='text/javascript'>
	var request;
	function submitEdit(event){
		event.preventDefault();
		event.preventDefault();
		var $form = $("#<?php echo $this->tableId; ?>_edit_form");
		var url = $form.attr( 'action' );
		$inputs = $("#<?php echo $this->tableId; ?>_edit_form").find("input, select, button, textarea");
		$form.ajaxSubmit({
			complete:function(xmlHttp){
				$inputs.prop("disabled", false);
			},
			success:function(data){
				try {
					var data=jQuery.parseJSON(data);
					construct_table(data);
					if(typeof data.error!="undefined"){
						alert(data.error);
					}else if(typeof data.jumpTo!='undefined'){
						window.location=data.jumpTo;
					}else if(typeof data.eval!='undefined'){
						eval(data.eval);
					}
				} catch (e) {
					return false;
				}
			},
			error:function (jqXHR, textStatus, errorThrown){
				console.error(
					"The following error occurred: "+
					textStatus, errorThrown
				);
			},
			statusCode:{
				200: function(obj) {
					//window.history.back();
				}
		}});
		$inputs.prop("disabled", true);
		$("#<?php echo $this->tableId; ?>_edit").hide("slow");
		$("#<?php echo $this->tableId; ?>_display").show("slow");
		/*
		var $form = $("#<?php echo $this->tableId; ?>_edit").find("form");
		//var $inputs = $form.find("input, select, button, textarea");
		$inputs = $("#<?php echo $this->tableId; ?>_edit").find("input, select, button, textarea");
		var serializedData = $inputs.serialize(), url = $form.attr( 'action' );;
		$inputs.prop("disabled", true);
		request = $.ajax({
			url: url+"<?php echo $action."/?".$str_get ?>",
			type: "post",
			data: serializedData
		});
		request.done(function (response, textStatus, jqXHR){
			// Log a message to the console
			//console.log("Hooray, it worked!");
			var data=jQuery.parseJSON(response);
			$("#<?php echo $this->tableId; ?>_edit").hide("slow");
			$("#<?php echo $this->tableId; ?>_display").show("slow");
			construct_table(data);
		});
		request.fail(function (jqXHR, textStatus, errorThrown){
			console.error(
				"The following error occurred: "+
				textStatus, errorThrown
			);
		});
		request.always(function () {
			// Reenable the inputs
			$inputs.prop("disabled", false);
		});*/
		$inputs.prop("disabled", false);
	}
</script>
<script>
$(function(){
	
});
</script>
<script>
	function construct_table_<?php echo $this->tableId; ?>(data){
		str="";
		if(typeof data.page_result_<?php echo $this->tableId; ?> != 'undefined'){
			$("#<?php echo $this->tableId; ?>_display2").html(data.page_result_<?php echo $this->tableId; ?>.edit1);
			$("#<?php echo $this->tableId; ?>_edit2").html(data.page_result_<?php echo $this->tableId; ?>.edit2);
		}
	}
	var old_foo = construct_table2;
	construct_table2 = (function() {
		var cached_function = construct_table2;
		return function(data) {
			//alert("hola3");
			if(typeof construct_table_<?php echo $this->tableId; ?>!='undefined') construct_table_<?php echo $this->tableId; ?>(data);
			cached_function.apply(this, arguments); // use .apply() to call it
		};
	}());
</script>
<script>
	function startEdit(e,str){
		e.preventDefault();
		$("#"+str+"_edit").show("slow");
		$("#"+str+"_display").hide("slow");
	}
	function cancelEdit(e,str){
		e.preventDefault();
		$("#"+str+"_edit").hide("slow");
		$("#"+str+"_display").show("slow");
	}
</script>
<script>
	$(function(){
		$(".datepicker").datetimepicker({
			//language:  'fr',
			weekStart: 1,
			todayBtn:  1,
			autoclose: 1,
			todayHighlight: 1,
			startView: 2,
			forceParse: 0,
			showMeridian: 1
		});
	});
</script>
<script src="/js/ajaxSubmit.js"></script>