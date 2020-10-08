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
?>
<div class="widget-content">
	<div class="widget-box" id="form_creator_<?php echo $this->formId; ?>">
		<?php echo $this->data["page_result_form_".$this->formId]["form1"]; ?>
	</div>
</div>
<script type='text/javascript'>
	var request;
	function submitForm<?php echo $this->formId; ?>(event){
		event.preventDefault();
		var $form = $("#form_creator_<?php echo $this->formId; ?>").find("form");
		$inputs = $("#form_creator_<?php echo $this->formId; ?>").find("input, select, button, textarea");
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
						changePage(data.jumpTo);
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
					//alert(JSON.stringify(obj));
				}
			}
		});
		$inputs.prop("disabled", true);
		
	}
</script>
<script>
$(document ).ajaxComplete(function(event, request, settings){
     //console.log(settings.url);                           
     //console.log(request.getResponseHeader('Location')); 
});
</script>
<script>
	function construct_table_form_<?php echo $this->formId; ?>(data){
		str="";
		if(typeof data.page_result_form_<?php echo $this->formId; ?> != 'undefined'){
			$("#form_creator_<?php echo $this->formId; ?>").html(data.page_result_form_<?php echo $this->formId; ?>.form1);
		}
	}
	var old_foo = construct_table2;
	construct_table2 = (function() {
		var cached_function = construct_table2;
		return function(data) {
			//alert("hola3");
			if(typeof construct_table_form_<?php echo $this->formId; ?>!='undefined') construct_table_form_<?php echo $this->formId; ?>(data);
			cached_function.apply(this, arguments); // use .apply() to call it
		};
	}());
</script>
<script>

	
	function searchSelectOpen(){
		var str=$(this).val();
		var ket=$(this).attr("name");
		$(this).siblings('div').show();
		//alert("abrir");
		$(this).siblings('div').find("input").keyup();
	}
	function searchSelect(e,key,fake,real){
		e.preventDefault();
		$('.searchSelect'+key+' > .searchSelectInputFake'+key).val(fake);
		//alert('.searchSelect'+key+' > .searchSelectInputFake'+key);
		$('.searchSelect'+key+' > .searchSelectInput'+key).val(real);
		$('.searchSelect'+key+' > div').hide();
	}
	function updateSelect(e){
		e.preventDefault();
		alert($(this).parent().attr("id"));
	}
</script>
<script src="/js/debounce.js"></script>
<script src="/js/ajaxSubmit.js"></script>
