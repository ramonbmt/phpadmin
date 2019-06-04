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
	if(empty($this->sendTo)){
		$this->sendTo=$action."/?".$str_get;
	}
?>
<form class="form-horizontal well" method="POST" enctype='multipart/form-data' action="<?php echo $this->sendTo; ?>">
	<fieldset>
		<?php foreach($this->columns as $key=>$value){ ?>
		<div class="control-group">
			<label class="control-label" for="input01"><?php echo $value["as"]; ?></label>
			<div class="controls">
				<?php if(isset($value["link"])&&$value["link"]){
					echo $foo($this->validator->getValue($key),$value,$key);
				}else{
					$disabled="";
					if(isset($value["disabled"])&&$value["disabled"]==true){
						$disabled="disabled='disabled'";
					}
					switch($value["type"]){
						case "text":
							?>
							<input <?php echo $disabled; ?> type="<?php echo $value["type"]; ?>" name="<?php echo $key; ?>" value="<?php echo $this->validator->getValue($key); ?>" class="input-xlarge text-tip" id="<?php echo $key; ?>" <?php if(isset($value["tooltip"])) echo "title=\"".$value['tooltip']."\""; ?>/>
							<?php if(isset($value["linkto"])){  ?>
							<script>
								$(function(){
									$("form [name='<?php echo $value["linkto"]; ?>']").change(function(){
										<?php if(isset($value["showwhenequal"])){ ?>
											if($(this).val()=='<?php echo $value["showwhenequal"]; ?>'){
												$("form [name='<?php echo $key; ?>']").parent().parent().show();
											}else{
												$("form [name='<?php echo $key; ?>']").parent().parent().hide();
											}
										<?php } ?>
									});
									$("form [name='<?php echo $value["linkto"]; ?>']").trigger( "change" );
								});
							</script>
							<?php } ?>
							<?php
						break;
						case "multiple":
							?>
							<div class="multiple">
								<input <?php echo $disabled; ?> type="<?php echo $value["multiple"]; ?>" name="<?php echo $key; ?>[1]" value="<?php echo $this->validator->getValue($key."1"); ?>" class="input-xlarge text-tip" id="<?php echo $key; ?>1" <?php if(isset($value["tooltip"])) echo "title=\"".$value['tooltip']."\""; ?>/>
							</div>
							<span class="multiple<?php echo $key; ?> color-icons add_co" style="cursor:pointer;margin-top:5px;"></span>
							<script>
								var imult<?php echo $key; ?>=1;
								$(function(){
									$(".multiple<?php echo $key; ?>").click(function(){
										imult<?php echo $key; ?>++;
										$(this).siblings('.multiple').append('<div style="clear:both;height:7px;"></div><input type="<?php echo $value["multiple"]; ?>" name="<?php echo $key; ?>['+imult<?php echo $key; ?>+']" value="" class="input-xlarge text-tip" id="<?php echo $key; ?>'+imult<?php echo $key; ?>+'" <?php if(isset($value["tooltip"])) echo "title=\"".$value['tooltip']."\""; ?>/>');
									});
								});
							</script>
							<?php
						break;
						case "searchselect":
							?>
							<div class="searchSelect<?php echo $key; ?>">
								<input style="float:left;" <?php echo $disabled; ?> type="text" name="fake_<?php echo $key; ?>" value="<?php echo $this->validator->getValue($key); ?>" class="input-xlarge text-tip searchSelectInputFake<?php echo $key; ?>" <?php if(isset($value["tooltip"])) echo "title=\"".$value['tooltip']."\""; ?>/>
								<input style="float:left;" <?php echo $disabled; ?> type="hidden" name="<?php echo $key; ?>" value="<?php echo $this->validator->getValue($key); ?>" class="input-xlarge text-tip searchSelectInput<?php echo $key; ?>" <?php if(isset($value["tooltip"])) echo "title=\"".$value['tooltip']."\""; ?>/>
								<div style="display:none;">
									<span style="cursor:pointer;float:left;" onclick="$(this).parent().hide();" class="color-icons accept_co"></span>
									<span style="cursor:pointer;float:left" onclick="$(this).parent().siblings('input').val('');$(this).parent().hide();" class="color-icons cross_co"></span>
									<div style="clear:both;"></div>
									<?php
									/* $search->setAll("search_template_self",$value["searchselect"]);
									$search->constructSearch();
									$value["searchselect"]->createTable(); */
									echo $value["searchselect"]->create();
									?>
								</div>
							</div>
							<script>
								$(function(){
									$(".searchSelectInputFake<?php echo $key; ?>").focus($.debounce(250,searchSelectOpen));
								});
							</script>
							<?php if(isset($value["linkto"])){  ?>
								<script>
									$(function(){
										$("form [name='<?php echo $value["linkto"]; ?>']").change(function(){
											<?php if(isset($value["showwhenequal"])){ ?>
												if($(this).val()=='<?php echo $value["showwhenequal"]; ?>'){
													$("form [name='<?php echo $key; ?>']").parent().parent().parent().show();
												}else{
													$("form [name='<?php echo $key; ?>']").parent().parent().parent().hide();
												}
											<?php } ?>
										});
										$("form [name='<?php echo $value["linkto"]; ?>']").trigger( "change" );
									});
								</script>
							<?php } ?>
							<?php
						break;
						case "date":
							?>
							<input <?php echo $disabled; ?> class="datepicker" type="text" name="<?php echo $key; ?>" value="<?php echo $this->validator->getValue($key); ?>" class="input-xlarge text-tip" id="<?php echo $key; ?>" <?php if(isset($value["tooltip"])) echo "title=\"".$value['tooltip']."\""; ?>/>
							<?php if(isset($value["linkto"])){  ?>
								<script>
									$(function(){
										$("form [name='<?php echo $value["linkto"]; ?>']").change(function(){
											<?php if(isset($value["showwhenequal"])){ ?>
												if($(this).val()=='<?php echo $value["showwhenequal"]; ?>'){
													$("form [name='<?php echo $key; ?>']").parent().parent().show();
												}else{
													$("form [name='<?php echo $key; ?>']").parent().parent().hide();
												}
											<?php } ?>
										});
										$("form [name='<?php echo $value["linkto"]; ?>']").trigger( "change" );
									});
								</script>
							<?php } ?>
							<?php
						break;
						case "textarea":
							?>
							<textarea <?php echo $disabled; ?> name="<?php echo $key; ?>" id="<?php echo $key; ?>"><?php echo $this->validator->getValue($key); ?></textarea>
							<?php if(isset($value["linkto"])){  ?>
							<script>
								$(function(){
									$("form [name='<?php echo $value["linkto"]; ?>']").change(function(){
										<?php if(isset($value["showwhenequal"])){ ?>
											if($(this).val()=='<?php echo $value["showwhenequal"]; ?>'){
												$("form [name='<?php echo $key; ?>']").parent().parent().show();
											}else{
												$("form [name='<?php echo $key; ?>']").parent().parent().hide();
											}
										<?php } ?>
									});
									$("form [name='<?php echo $value["linkto"]; ?>']").trigger( "change" );
								});
							</script>
							<?php } ?>
							<?php
						break;
						case "cooltextarea":
							?>
							<link rel="stylesheet" href="/css/codemirror.css"/>
							<script src="/js/codemirror.js"></script>
							<script src="/js/codemirror_mode/php/php.js"></script>
							<textarea <?php echo $disabled; ?> class="cooltextarea" name="<?php echo $key; ?>" id="<?php echo $key; ?>"><?php echo $this->validator->getValue($key); ?></textarea>
							<script>
								$(function(){
									$.each($("[name='<?php echo $key; ?>']"),function(k,v){
										CodeMirror.fromTextArea(v,{
											lineNumbers: true,
											 mode:  "php"
											//mode: "text/html",
											//matchBrackets: true
										});
									});
								});
							</script>
							<?php if(isset($value["linkto"])){  ?>
								<script>
									$(function(){
										$("form [name='<?php echo $value["linkto"]; ?>']").change(function(){
											<?php if(isset($value["showwhenequal"])){ ?>
												if($(this).val()=='<?php echo $value["showwhenequal"]; ?>'){
													$("form [name='<?php echo $key; ?>']").parent().parent().show();
												}else{
													$("form [name='<?php echo $key; ?>']").parent().parent().hide();
												}
											<?php } ?>
										});
										$("form [name='<?php echo $value["linkto"]; ?>']").trigger( "change" );
									});
								</script>
							<?php } ?>
							<?php
						break;
						case "select":
							echo "<select ".$disabled." name='".$key."'>";
							foreach($value["select"] as $value2){
								?>
								<option <?php if($value2["id"]==$this->validator->getValue($key)) echo "selected='selected'" ?> value="<?php echo $value2["id"]; ?>"><?php echo $value2["name"]; ?></option>
								<?php
							}
							echo "</select>";
						break;
						case "checkbox":
							foreach($value["checkbox"] as $key2=>$value2){
								?>
								<label class="checkbox inline">
								<input <?php if($value2["id"]==$this->validator->getValue($key)) echo "checked='checked'" ?> <?php echo $disabled; ?> value="<?php echo $key2; ?>" type="checkbox" name="<?php echo $key; ?>" class="input-xlarge text-tip" id="<?php echo $key."_".$key2; ?>" <?php if(isset($value["tooltip"])) echo "title=\"".$value['tooltip']."\""; ?>/>
								<?php echo $value2["name"]; ?>
								</label>
								<?php
							}
						break;
						case "radio":
							foreach($value["radio"] as $key2=>$value2){
								?>
								<label class="checkbox inline">
								<input <?php if($value2["id"]==$this->validator->getValue($key)) echo "checked='checked'" ?> <?php echo $disabled; ?> value="<?php echo $key2; ?>" type="radio" name="<?php echo $key; ?>" class="input-xlarge text-tip" id="<?php echo $key."_".$key2; ?>" <?php if(isset($value["tooltip"])) echo "title=\"".$value['tooltip']."\""; ?>/>
								<?php echo $value2["name"]; ?>
								</label>
								<?php
							}
						break;
						default:
							?>
							<input <?php echo $disabled; ?> type="<?php echo $value["type"]; ?>" name="<?php echo $key; ?>" value="<?php echo $this->validator->getValue($key); ?>" class="input-xlarge text-tip" id="<?php echo $key; ?>" <?php if(isset($value["tooltip"])) echo "title=\"".$value['tooltip']."\""; ?>/>
							<?php
						break;
					}
				?>
					<?php } ?>
				<?php if(isset($value["isset"])){ ?><span style="color:#EA0000">*</span><?php } ?>
			</div>
		</div>
		<?php } ?>
		<?php $this->validator->secureSendInput(); ?>
		<input type="hidden" name="ajax" value="1"/>
		<input type="hidden" name="page" value="1"/>
		<input type="hidden" name="action" value="form"/>
		<input type="hidden" name="sel" value="<?php echo $this->formId; ?>"/>
		<?php if(!isset($this->sendButton)||$this->sendButton){ ?>
		<div class="form-actions">
			<button onclick="submitForm<?php echo $this->formId; ?>(event);" class="btn btn-info">Agregar</button>
			<button onclick="event.preventDefault();window.location='<?php echo $this->returnhtml; ?>'" class="btn btn-warning">Cancelar</button>
		</div>
		<?php } ?>
	</fieldset>
</form>
