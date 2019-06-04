<div class="widget-content">
	<div class="widget-box">
		<form class="form-horizontal well" method="POST" enctype='multipart/form-data' action="<?php echo $this->sendTo; ?>">
			<fieldset>
				<?php foreach($this->columns as $key=>$value){ ?>
				<div class="control-group">
					<label class="control-label" for="input01"><?php echo $value["as"]; ?></label>
					<div class="controls">
						<?php if(isset($value["link"])&&$value["link"]){ 
							echo $foo($validator->getValue($key),$value,$key);
						}else{
							$disabled="";
							if(isset($value["disabled"])&&$value["disabled"]==true){
								$disabled="disabled='disabled'";
							}
							switch($value["type"]){
								case "text":
									?>
									<input <?php echo $disabled; ?> type="<?php echo $value["type"]; ?>" name="<?php echo $key; ?>" value="<?php echo $validator->getValue($key); ?>" class="input-xlarge text-tip" id="<?php echo $key; ?>" <?php if(isset($value["tooltip"])) echo "title=\"".$value['tooltip']."\""; ?>/>
									<?php
								break;
								case "searchselect":
									?>
									<div class="searchSelect<?php echo $key; ?>">
										<input style="float:left;" <?php echo $disabled; ?> type="text" name="fake_<?php echo $key; ?>" value="<?php echo $validator->getValue("fake_".$key); ?>" class="input-xlarge text-tip searchSelectInputFake<?php echo $key; ?>" <?php if(isset($value["tooltip"])) echo "title=\"".$value['tooltip']."\""; ?>/>
										<input style="float:left;" <?php echo $disabled; ?> type="hidden" name="<?php echo $key; ?>" value="<?php echo $validator->getValue($key); ?>" class="input-xlarge text-tip searchSelectInput<?php echo $key; ?>" <?php if(isset($value["tooltip"])) echo "title=\"".$value['tooltip']."\""; ?>/>
										<div style="display:none;">
											<span style="cursor:pointer;float:left;" onclick="$(this).parent().hide();" class="color-icons accept_co"></span>
											<span style="cursor:pointer;float:left" onclick="$(this).parent().siblings('input').val('');$(this).parent().hide();" class="color-icons cross_co"></span>
											<div style="clear:both;"></div>
											<?php
											$search->setAll("search_template_self",$value["searchselect"]);
											$search->constructSearch();
											$value["searchselect"]->createTable();?>
										</div>
									</div>
									<script>
										$(function(){
											$(".searchSelectInputFake<?php echo $key; ?>").focus($.debounce(250,searchSelectOpen));
										});
									</script>
									<?php
								break;
								case "date":
									?>
									<input <?php echo $disabled; ?> class="datepicker" type="text" name="<?php echo $key; ?>" value="<?php echo $validator->getValue($key); ?>" class="input-xlarge text-tip" id="<?php echo $key; ?>" <?php if(isset($value["tooltip"])) echo "title=\"".$value['tooltip']."\""; ?>/>
									<?php
								break;
								case "textarea":
									?>
									<textarea <?php echo $disabled; ?> name="<?php echo $key; ?>" id="<?php echo $key; ?>"><?php echo $validator->getValue($key); ?></textarea>
									<?php
								break;
								case "select":
									echo "<select ".$disabled." name='".$key."'>";
									foreach($value["select"] as $value2){
										?>
										<option <?php if($value2["id"]==$validator->getValue($key)) echo "selected='selected'" ?> value="<?php echo $value2["id"]; ?>"><?php echo $value2["name"]; ?></option>
										<?php
									}
									echo "</select>";
								break;
								default:
									?>
									<input <?php echo $disabled; ?> type="<?php echo $value["type"]; ?>" name="<?php echo $key; ?>" value="<?php echo $validator->getValue($key); ?>" class="input-xlarge text-tip" id="<?php echo $key; ?>" <?php if(isset($value["tooltip"])) echo "title=\"".$value['tooltip']."\""; ?>/>
									<?php
								break;
							}
						?>
							<?php } ?>
						<?php if(isset($value["isset"])){ ?><span style="color:#EA0000">*</span><?php } ?>
					</div>
				</div>
				<?php } ?>
				<?php $validator->secureSendInput(); ?>
				<div class="form-actions">
					<button type="submit" class="btn btn-info">Agregar</button>
					<button onclick="event.preventDefault();window.location='<?php echo $this->returnhtml; ?>'" class="btn btn-warning">Cancelar</button>
				</div>
			</fieldset>
		</form>
	</div>
</div>
<script>
	$(function(){
		$(".datepicker").datetimepicker({
			//language:  'fr',
			format: 'yyyy-mm-dd',
			weekStart: 1,
			todayBtn:  1,
			autoclose: 1,
			todayHighlight: 1,
			startView: 2,
			minView: 2,
			forceParse: 0,
			showMeridian: 1
		});
		
		//$(".searchSelectInputFakename").keyup($.debounce(250,searchSelectOpen));
	});
	
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