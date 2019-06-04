<div class="widget-content">
	<div class="widget-box">
		<form class="form-horizontal well" method="POST" enctype='multipart/form-data' action="<?php echo $this->sendTo; ?>">
			<fieldset>
				<?php foreach($this->columns as $key=>$value){ ?>
				<?php if(isset($value["display"])&&!$value["display"]){ ?>
				<div class="control-group" style="display:none;">
				<?php }else{ ?>
				<div class="control-group">
				<?php } ?>
					<label class="control-label" for="<?php echo $key; ?>"><?php echo $value["as"]; ?></label>
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
									<input <?php echo $disabled; ?> type="<?php echo $value["type"]; ?>" name="<?php echo $key; ?>" value="<?php echo $this->validator->getValue($key); ?>" class="input-xlarge text-tip" id="<?php echo $key; ?>" <?php if(isset($value["tooltip"])) echo "title=\"".$value['tooltip']."\""; ?> <?php echo (isset($value["required"]) && !isset($value["showwhenequal"])) ? "required" : ""; ?> <?php if (isset($value["unique_id"])) {echo 'id="'.$value["unique_id"].'"';} ?> />
									<?php if(isset($value["linkto"])){ ?>
										<script>
											$(function(){
												$("form [name='<?php echo $value["linkto"]; ?>']").change(function(){
													<?php
													if(isset($value["showwhenequal"])) {
														if (is_array($value["showwhenequal"])) {
															$a = 1;
															echo "if(";
															foreach ($value["showwhenequal"] as $ky => $val) {
																echo "$(this).val()==$val";
																if ($a < count($value["showwhenequal"])) {
																	echo " || ";
																}
																$a++;
															}
															echo ") {";
															echo "\n";
														} else { ?>
															if($(this).val()==<?php echo $value["showwhenequal"] ?>) {
															<?php
														} ?>
															$("form [name='<?php echo $key; ?>']").parent().parent().show();
														<?php
														if (isset($value["required"])) {
															if (isset($value["notrequiredwhen"])) {
																if (is_array($value["notrequiredwhen"])) {
																	$b = 1;
																	echo "if(";
																	foreach ($value["notrequiredwhen"] as $ke => $vl) {
																		echo "$(\"form [name=".$value["linkto"]."]\").val()==$vl";
																		if ($b < count($value["notrequiredwhen"])) {
																			echo " || ";
																		}
																		$b++;
																	}
																	echo ") {";
																	echo "\n"; ?>
																	$("form [name='<?php echo $key; ?>']").removeAttr("required");
																	<?php
																} else { ?>
																	if($("form [name='<?php echo $value["linkto"] ?>']").val()==<?php echo $value["notrequiredwhen"] ?>) {
																		$("form [name='<?php echo $key; ?>']").removeAttr("required");
																	<?php
																}
															} else { ?>
																$("form [name='<?php echo $key; ?>']").attr("required", "");
																<?php
															}
															if (isset($value["notrequiredwhen"])) { ?>
																} else {
																	$("form [name='<?php echo $key; ?>']").attr("required", "");
																}
																<?php
															}
														} ?>
														}else{
															$("form [name='<?php echo $key; ?>']").parent().parent().hide();
															$("form [name='<?php echo $key; ?>']").removeAttr("required");
														}
													<?php } ?>
												});
												$("form [name='<?php echo $value["linkto"]; ?>']").trigger( "change" );
											});
										</script>
									<?php } ?>
									<?php if (isset($value["required"])) { ?>
										<script>
											$("form [name='<?php echo $key; ?>']").on('change invalid',function(){
												var <?php echo $key; ?>_var = $(this).get(0);
												<?php echo $key; ?>_var.setCustomValidity('');
												if (!<?php echo $key; ?>_var.validity.valid) {
													<?php echo $key; ?>_var.setCustomValidity('<?php echo $value["error"] ?>');
												}
											});
										</script>
									<?php } ?>
									<?php
								break;
								case "multiple":
									?>
									<div class="multiple">
										<input <?php echo $disabled; ?> type="<?php echo $value["multiple"]; ?>" name="<?php echo $key; ?>[1]" value="<?php echo $this->validator->getValue($key."1"); ?>" class="input-xlarge text-tip" id="<?php echo $key; ?>1" <?php if(isset($value["tooltip"])) echo "title=\"".$value['tooltip']."\""; ?> <?php echo (isset($value["required"]) && !isset($value["showwhenequal"])) ? "required" : ""; ?> <?php if (isset($value["unique_id"])) {echo 'id="'.$value["unique_id"].'"';} ?> />
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
									<?php if (isset($value["required"])) { ?>
										<script>
											$("form [name='<?php echo $key; ?>']").on('change invalid',function(){
												var <?php echo $key; ?>_var = $(this).get(0);
												<?php echo $key; ?>_var.setCustomValidity('');
												if (!<?php echo $key; ?>_var.validity.valid) {
													<?php echo $key; ?>_var.setCustomValidity('<?php echo $value["error"] ?>');
												}
											});
										</script>
									<?php } ?>
									<?php
								break;
								case "searchselect":
									?>
									<div class="searchSelect<?php echo $key; ?>" <?php if (isset($value["unique_id"])) {echo 'id="'.$value["unique_id"].'"';} ?>>
										<input style="float:left;" <?php echo $disabled; ?> type="text" name="fake_<?php echo $key; ?>" value="<?php echo $this->validator->getValue($key); ?>" class="input-xlarge text-tip searchSelectInputFake<?php echo $key; ?>" <?php if(isset($value["tooltip"])) echo "title=\"".$value['tooltip']."\""; ?> <?php echo (isset($value["required"]) && !isset($value["showwhenequal"])) ? "required" : ""; ?> />
										<input style="float:left;" <?php echo $disabled; ?> type="hidden" name="<?php echo $key; ?>" value="<?php echo $this->validator->getValue($key); ?>" class="input-xlarge text-tip searchSelectInput<?php echo $key; ?>" <?php if(isset($value["tooltip"])) echo "title=\"".$value['tooltip']."\""; ?> />
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
												$("body").on('change', "form [name='<?php echo $value["linkto"]; ?>']", function(){
													<?php
													if(isset($value["showwhenequal"])) {
														if (is_array($value["showwhenequal"])) {
															$a = 1;
															echo "if(";
															foreach ($value["showwhenequal"] as $ky => $val) {
																echo "$(this).val()==$val";
																if ($a < count($value["showwhenequal"])) {
																	echo " || ";
																}
																$a++;
															}
															echo ") {";
															echo "\n";
														} else { ?>
															if($(this).val()==<?php echo $value["showwhenequal"] ?>) {
															<?php
														} ?>
															$("form [name='<?php echo $key; ?>']").parent().parent().parent().show();
														<?php
														if (isset($value["required"])) {
															if (isset($value["notrequiredwhen"])) {
																if (is_array($value["notrequiredwhen"])) {
																	$b = 1;
																	echo "if(";
																	foreach ($value["notrequiredwhen"] as $ke => $vl) {
																		echo "$(\"form [name=".$value["linkto"]."]\").val()==$vl";
																		if ($b < count($value["notrequiredwhen"])) {
																			echo " || ";
																		}
																		$b++;
																	}
																	echo ") {";
																	echo "\n"; ?>
																	$("form [name='fake_<?php echo $key; ?>']").removeAttr("required");
																	<?php
																} else { ?>
																	if($("form [name='<?php echo $value["linkto"] ?>']").val()==<?php echo $value["notrequiredwhen"] ?>) {
																		$("form [name='fake_<?php echo $key; ?>']").removeAttr("required");
																	<?php
																}
															} else { ?>
																$("form [name='fake_<?php echo $key; ?>']").attr("required", "");
																<?php
															}
															if (isset($value["notrequiredwhen"])) { ?>
																} else {
																	$("form [name='fake_<?php echo $key; ?>']").attr("required", "");
																}
																<?php
															}
														} ?>
														}else{
															$("form [name='<?php echo $key; ?>']").parent().parent().parent().hide();
															$("form [name='fake_<?php echo $key; ?>']").removeAttr("required");
														}
													<?php } ?>
												});
												$("form [name='<?php echo $value["linkto"]; ?>']").trigger( "change" );
											});
										</script>
									<?php } ?>
									<?php if (isset($value["required"])) { ?>
										<script>
											$("form [name='fake_<?php echo $key; ?>']").on('change invalid',function(){
												var <?php echo $key; ?>_var = $(this).get(0);
												<?php echo $key; ?>_var.setCustomValidity('');
												if (!<?php echo $key; ?>_var.validity.valid) {
													<?php echo $key; ?>_var.setCustomValidity('<?php echo $value["error"] ?>');
												}
											});
										</script>
									<?php } ?>
									<?php
								break;
								case "date":
									?>
									<input <?php echo $disabled; ?> class="datePicker" type="text" name="<?php echo $key; ?>" value="<?php echo $this->validator->getValue($key); ?>" class="input-xlarge text-tip" id="<?php echo $key; ?>" <?php if(isset($value["tooltip"])) echo "title=\"".$value['tooltip']."\""; ?> <?php echo (isset($value["required"]) && !isset($value["showwhenequal"])) ? "required" : ""; ?> <?php if (isset($value["unique_id"])) {echo 'id="'.$value["unique_id"].'"';} ?> />
									<?php if(isset($value["linkto"])){ ?>
										<script>
											$(function(){
												$("form [name='<?php echo $value["linkto"]; ?>']").change(function(){
													<?php
													if(isset($value["showwhenequal"])) {
														if (is_array($value["showwhenequal"])) {
															$a = 1;
															echo "if(";
															foreach ($value["showwhenequal"] as $ky => $val) {
																echo "$(this).val()==$val";
																if ($a < count($value["showwhenequal"])) {
																	echo " || ";
																}
																$a++;
															}
															echo ") {";
															echo "\n";
														} else { ?>
															if($(this).val()==<?php echo $value["showwhenequal"] ?>) {
															<?php
														} ?>
															$("form [name='<?php echo $key; ?>']").parent().parent().show();
														<?php
														if (isset($value["required"])) {
															if (isset($value["notrequiredwhen"])) {
																if (is_array($value["notrequiredwhen"])) {
																	$b = 1;
																	echo "if(";
																	foreach ($value["notrequiredwhen"] as $ke => $vl) {
																		echo "$(\"form [name=".$value["linkto"]."]\").val()==$vl";
																		if ($b < count($value["notrequiredwhen"])) {
																			echo " || ";
																		}
																		$b++;
																	}
																	echo ") {";
																	echo "\n"; ?>
																	$("form [name='<?php echo $key; ?>']").removeAttr("required");
																	<?php
																} else { ?>
																	if($("form [name='<?php echo $value["linkto"] ?>']").val()==<?php echo $value["notrequiredwhen"] ?>) {
																		$("form [name='<?php echo $key; ?>']").removeAttr("required");
																	<?php
																}
															} else { ?>
																$("form [name='<?php echo $key; ?>']").attr("required", "");
																<?php
															}
															if (isset($value["notrequiredwhen"])) { ?>
																} else {
																	$("form [name='<?php echo $key; ?>']").attr("required", "");
																}
																<?php
															}
														} ?>
														}else{
															$("form [name='<?php echo $key; ?>']").parent().parent().hide();
															$("form [name='<?php echo $key; ?>']").removeAttr("required");
														}
													<?php } ?>
												});
												$("form [name='<?php echo $value["linkto"]; ?>']").trigger( "change" );
											});
										</script>
									<?php } ?>
									<?php if (isset($value["required"])) { ?>
										<script>
											$("form [name='<?php echo $key; ?>']").on('change invalid',function(){
												var <?php echo $key; ?>_var = $(this).get(0);
												<?php echo $key; ?>_var.setCustomValidity('');
												if (!<?php echo $key; ?>_var.validity.valid) {
													<?php echo $key; ?>_var.setCustomValidity('<?php echo $value["error"] ?>');
												}
											});
										</script>
									<?php } ?>
									<?php
								break;
								case "datetime":
									?>
									<input <?php echo $disabled; ?> class="dateTimePicker" type="text" name="<?php echo $key; ?>" value="<?php echo $this->validator->getValue($key); ?>" class="input-xlarge text-tip" id="<?php echo $key; ?>" <?php if(isset($value["tooltip"])) echo "title=\"".$value['tooltip']."\""; ?> <?php echo (isset($value["required"]) && !isset($value["showwhenequal"])) ? "required" : ""; ?> <?php if (isset($value["unique_id"])) {echo 'id="'.$value["unique_id"].'"';} ?> />
									<?php if(isset($value["linkto"])){ ?>
										<script>
											$(function(){
												$("form [name='<?php echo $value["linkto"]; ?>']").change(function(){
													<?php
													if(isset($value["showwhenequal"])) {
														if (is_array($value["showwhenequal"])) {
															$a = 1;
															echo "if(";
															foreach ($value["showwhenequal"] as $ky => $val) {
																echo "$(this).val()==$val";
																if ($a < count($value["showwhenequal"])) {
																	echo " || ";
																}
																$a++;
															}
															echo ") {";
															echo "\n";
														} else { ?>
															if($(this).val()==<?php echo $value["showwhenequal"] ?>) {
															<?php
														} ?>
															$("form [name='<?php echo $key; ?>']").parent().parent().show();
														<?php
														if (isset($value["required"])) {
															if (isset($value["notrequiredwhen"])) {
																if (is_array($value["notrequiredwhen"])) {
																	$b = 1;
																	echo "if(";
																	foreach ($value["notrequiredwhen"] as $ke => $vl) {
																		echo "$(\"form [name=".$value["linkto"]."]\").val()==$vl";
																		if ($b < count($value["notrequiredwhen"])) {
																			echo " || ";
																		}
																		$b++;
																	}
																	echo ") {";
																	echo "\n"; ?>
																	$("form [name='<?php echo $key; ?>']").removeAttr("required");
																	<?php
																} else { ?>
																	if($("form [name='<?php echo $value["linkto"] ?>']").val()==<?php echo $value["notrequiredwhen"] ?>) {
																		$("form [name='<?php echo $key; ?>']").removeAttr("required");
																	<?php
																}
															} else { ?>
																$("form [name='<?php echo $key; ?>']").attr("required", "");
																<?php
															}
															if (isset($value["notrequiredwhen"])) { ?>
																} else {
																	$("form [name='<?php echo $key; ?>']").attr("required", "");
																}
																<?php
															}
														} ?>
														}else{
															$("form [name='<?php echo $key; ?>']").parent().parent().hide();
															$("form [name='<?php echo $key; ?>']").removeAttr("required");
														}
													<?php } ?>
												});
												$("form [name='<?php echo $value["linkto"]; ?>']").trigger( "change" );
											});
										</script>
									<?php } ?>
									<?php if (isset($value["required"])) { ?>
										<script>
											$("form [name='<?php echo $key; ?>']").on('change invalid',function(){
												var <?php echo $key; ?>_var = $(this).get(0);
												<?php echo $key; ?>_var.setCustomValidity('');
												if (!<?php echo $key; ?>_var.validity.valid) {
													<?php echo $key; ?>_var.setCustomValidity('<?php echo $value["error"] ?>');
												}
											});
										</script>
									<?php } ?>
									<?php
								break;
								case "textarea":
									?>
									<textarea <?php echo $disabled; ?> name="<?php echo $key; ?>" id="<?php echo $key; ?>" <?php echo (isset($value["required"]) && !isset($value["showwhenequal"])) ? "required" : ""; ?> <?php if (isset($value["unique_id"])) {echo 'id="'.$value["unique_id"].'"';} ?>><?php echo $this->validator->getValue($key); ?></textarea>
									<?php if(isset($value["linkto"])){  ?>
										<script>
											$(function(){
												$("form [name='<?php echo $value["linkto"]; ?>']").change(function(){
													<?php
													if(isset($value["showwhenequal"])) {
														if (is_array($value["showwhenequal"])) {
															$a = 1;
															echo "if(";
															foreach ($value["showwhenequal"] as $ky => $val) {
																echo "$(this).val()==$val";
																if ($a < count($value["showwhenequal"])) {
																	echo " || ";
																}
																$a++;
															}
															echo ") {";
															echo "\n";
														} else { ?>
															if($(this).val()==<?php echo $value["showwhenequal"] ?>) {
															<?php
														} ?>
															$("form [name='<?php echo $key; ?>']").parent().parent().show();
														<?php
														if (isset($value["required"])) {
															if (isset($value["notrequiredwhen"])) {
																if (is_array($value["notrequiredwhen"])) {
																	$b = 1;
																	echo "if(";
																	foreach ($value["notrequiredwhen"] as $ke => $vl) {
																		echo "$(\"form [name=".$value["linkto"]."]\").val()==$vl";
																		if ($b < count($value["notrequiredwhen"])) {
																			echo " || ";
																		}
																		$b++;
																	}
																	echo ") {";
																	echo "\n"; ?>
																	$("form [name='<?php echo $key; ?>']").removeAttr("required");
																	<?php
																} else { ?>
																	if($("form [name='<?php echo $value["linkto"] ?>']").val()==<?php echo $value["notrequiredwhen"] ?>) {
																		$("form [name='<?php echo $key; ?>']").removeAttr("required");
																	<?php
																}
															} else { ?>
																$("form [name='<?php echo $key; ?>']").attr("required", "");
																<?php
															}
															if (isset($value["notrequiredwhen"])) { ?>
																} else {
																	$("form [name='<?php echo $key; ?>']").attr("required", "");
																}
																<?php
															}
														} ?>
														}else{
															$("form [name='<?php echo $key; ?>']").parent().parent().hide();
															$("form [name='<?php echo $key; ?>']").removeAttr("required");
														}
													<?php } ?>
												});
												$("form [name='<?php echo $value["linkto"]; ?>']").trigger( "change" );
											});
										</script>
									<?php } ?>
									<?php if (isset($value["required"])) { ?>
										<script>
											$("form [name='<?php echo $key; ?>']").on('change invalid',function(){
												var <?php echo $key; ?>_var = $(this).get(0);
												<?php echo $key; ?>_var.setCustomValidity('');
												if (!<?php echo $key; ?>_var.validity.valid) {
													<?php echo $key; ?>_var.setCustomValidity('<?php echo $value["error"] ?>');
												}
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
									<textarea <?php echo $disabled; ?> class="cooltextarea" name="<?php echo $key; ?>" id="<?php echo $key; ?>" <?php echo (isset($value["required"]) && !isset($value["showwhenequal"])) ? "required" : ""; ?> <?php if (isset($value["unique_id"])) {echo 'id="'.$value["unique_id"].'"';} ?>><?php echo $this->validator->getValue($key); ?></textarea>
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
													<?php
													if(isset($value["showwhenequal"])) {
														if (is_array($value["showwhenequal"])) {
															$a = 1;
															echo "if(";
															foreach ($value["showwhenequal"] as $ky => $val) {
																echo "$(this).val()==$val";
																if ($a < count($value["showwhenequal"])) {
																	echo " || ";
																}
																$a++;
															}
															echo ") {";
															echo "\n";
														} else { ?>
															if($(this).val()==<?php echo $value["showwhenequal"] ?>) {
															<?php
														} ?>
															$("form [name='<?php echo $key; ?>']").parent().parent().show();
														<?php
														if (isset($value["required"])) {
															if (isset($value["notrequiredwhen"])) {
																if (is_array($value["notrequiredwhen"])) {
																	$b = 1;
																	echo "if(";
																	foreach ($value["notrequiredwhen"] as $ke => $vl) {
																		echo "$(\"form [name=".$value["linkto"]."]\").val()==$vl";
																		if ($b < count($value["notrequiredwhen"])) {
																			echo " || ";
																		}
																		$b++;
																	}
																	echo ") {";
																	echo "\n"; ?>
																	$("form [name='<?php echo $key; ?>']").removeAttr("required");
																	<?php
																} else { ?>
																	if($("form [name='<?php echo $value["linkto"] ?>']").val()==<?php echo $value["notrequiredwhen"] ?>) {
																		$("form [name='<?php echo $key; ?>']").removeAttr("required");
																	<?php
																}
															} else { ?>
																$("form [name='<?php echo $key; ?>']").attr("required", "");
																<?php
															}
															if (isset($value["notrequiredwhen"])) { ?>
																} else {
																	$("form [name='<?php echo $key; ?>']").attr("required", "");
																}
																<?php
															}
														} ?>
														}else{
															$("form [name='<?php echo $key; ?>']").parent().parent().hide();
															$("form [name='<?php echo $key; ?>']").removeAttr("required");
														}
													<?php } ?>
												});
												$("form [name='<?php echo $value["linkto"]; ?>']").trigger( "change" );
											});
										</script>
									<?php } ?>
									<?php if (isset($value["required"])) { ?>
										<script>
											$("form [name='<?php echo $key; ?>']").on('change invalid',function(){
												var <?php echo $key; ?>_var = $(this).get(0);
												<?php echo $key; ?>_var.setCustomValidity('');
												if (!<?php echo $key; ?>_var.validity.valid) {
													<?php echo $key; ?>_var.setCustomValidity('<?php echo $value["error"] ?>');
												}
											});
										</script>
									<?php } ?>
									<?php
								break;
								case "select":
									// echo "<select ".$disabled." name='".$key."'>";
									?>
									<select <?php echo $disabled; ?> name="<?php echo $key; ?>" <?php if (isset($value["unique_id"])) {echo 'id="'.$value["unique_id"].'"';} ?>>
									<?php
									foreach($value["select"] as $value2){
										?>
										<option <?php if($value2["id"]==$this->validator->getValue($key)) echo "selected='selected'" ?> value="<?php echo $value2["id"]; ?>" <?php echo (isset($value["required"]) && !isset($value["showwhenequal"])) ? "required" : ""; ?>><?php echo $value2["name"]; ?></option>
										<?php
									}
									echo "</select>"; ?>
									<?php if(isset($value["linkto"])){  ?>
										<script>
											$(function(){
												$("form [name='<?php echo $value["linkto"]; ?>']").change(function(){
													<?php
													if(isset($value["showwhenequal"])) {
														if (is_array($value["showwhenequal"])) {
															$a = 1;
															echo "if(";
															foreach ($value["showwhenequal"] as $ky => $val) {
																echo "$(this).val()==$val";
																if ($a < count($value["showwhenequal"])) {
																	echo " || ";
																}
																$a++;
															}
															echo ") {";
															echo "\n";
														} else { ?>
															if($(this).val()==<?php echo $value["showwhenequal"] ?>) {
															<?php
														} ?>
															$("form [name='<?php echo $key; ?>']").parent().parent().show();
														<?php
														if (isset($value["required"])) {
															if (isset($value["notrequiredwhen"])) {
																if (is_array($value["notrequiredwhen"])) {
																	$b = 1;
																	echo "if(";
																	foreach ($value["notrequiredwhen"] as $ke => $vl) {
																		echo "$(\"form [name=".$value["linkto"]."]\").val()==$vl";
																		if ($b < count($value["notrequiredwhen"])) {
																			echo " || ";
																		}
																		$b++;
																	}
																	echo ") {";
																	echo "\n"; ?>
																	$("form [name='<?php echo $key; ?>']").removeAttr("required");
																	<?php
																} else { ?>
																	if($("form [name='<?php echo $value["linkto"] ?>']").val()==<?php echo $value["notrequiredwhen"] ?>) {
																		$("form [name='<?php echo $key; ?>']").removeAttr("required");
																	<?php
																}
															} else { ?>
																$("form [name='<?php echo $key; ?>']").attr("required", "");
																<?php
															}
															if (isset($value["notrequiredwhen"])) { ?>
																} else {
																	$("form [name='<?php echo $key; ?>']").attr("required", "");
																}
																<?php
															}
														} ?>
														}else{
															$("form [name='<?php echo $key; ?>']").parent().parent().hide();
															$("form [name='<?php echo $key; ?>']").removeAttr("required");
														}
													<?php } ?>
												});
												$("form [name='<?php echo $value["linkto"]; ?>']").trigger( "change" );
											});
										</script>
									<?php } ?>
									<?php if (isset($value["required"])) { ?>
										<script>
											$("form [name='<?php echo $key; ?>']").on('change invalid',function(){
												var <?php echo $key; ?>_var = $(this).get(0);
												<?php echo $key; ?>_var.setCustomValidity('');
												if (!<?php echo $key; ?>_var.validity.valid) {
													<?php echo $key; ?>_var.setCustomValidity('<?php echo $value["error"] ?>');
												}
											});
										</script>
									<?php } ?>
								<?php
								break;
								case "checkbox":
									foreach($value["checkbox"] as $key2=>$value2){
										?>
										<label class="checkbox inline">
										<input <?php if($value2["id"]==$this->validator->getValue($key)) echo "checked='checked'" ?> <?php echo $disabled; ?> value="<?php echo $key2; ?>" type="checkbox" name="<?php echo $key; ?>" class="input-xlarge text-tip" id="<?php echo $key."_".$key2; ?>" <?php if(isset($value["tooltip"])) echo "title=\"".$value['tooltip']."\""; ?> <?php echo (isset($value["required"]) && !isset($value["showwhenequal"])) ? "required" : ""; ?> <?php if (isset($value["unique_id"])) {echo 'id="'.$value["unique_id"].'"';} ?> />
										<?php echo $value2["name"]; ?>
										</label>
										<?php if (isset($value["required"])) { ?>
											<script>
												$("form [name='<?php echo $key; ?>']").on('change invalid',function(){
													var <?php echo $key; ?>_var = $(this).get(0);
													<?php echo $key; ?>_var.setCustomValidity('');
													if (!<?php echo $key; ?>_var.validity.valid) {
														<?php echo $key; ?>_var.setCustomValidity('<?php echo $value["error"] ?>');
													}
												});
											</script>
										<?php } ?>
										<?php
									}
								break;
								case "radio":
									foreach($value["radio"] as $key2=>$value2){
										?>
										<label class="checkbox inline">
										<input <?php if($value2["id"]==$this->validator->getValue($key)) echo "checked='checked'" ?> <?php echo $disabled; ?> value="<?php echo $key2; ?>" type="radio" name="<?php echo $key; ?>" class="input-xlarge text-tip" id="<?php echo $key."_".$key2; ?>" <?php if(isset($value["tooltip"])) echo "title=\"".$value['tooltip']."\""; ?> <?php echo (isset($value["required"]) && !isset($value["showwhenequal"])) ? "required" : ""; ?> <?php if (isset($value["unique_id"])) {echo 'id="'.$value["unique_id"].'"';} ?> />
										<?php echo $value2["name"]; ?>
										</label>
										<?php if (isset($value["required"])) { ?>
											<script>
												$("form [name='<?php echo $key; ?>']").on('change invalid',function(){
													var <?php echo $key; ?>_var = $(this).get(0);
													<?php echo $key; ?>_var.setCustomValidity('');
													if (!<?php echo $key; ?>_var.validity.valid) {
														<?php echo $key; ?>_var.setCustomValidity('<?php echo $value["error"] ?>');
													}
												});
											</script>
										<?php } ?>
										<?php
									}
								break;
								/* case "slider": ?>
									<div name="<?php echo $key; ?>" style="max-width:200px;" <?php echo $disabled; ?>>
										<input type="text" id="<?php echo $key; ?>" value="<?php echo $this->validator->getValue($key); ?>" <?php if(isset($value["tooltip"])) {echo "title=\"".$value['tooltip']."\"";} ?> style="max-width:30px;margin-bottom:10px;" disabled="disabled" />
										<div id="<?php echo $key; ?>_value"></div>
									</div>
									<?php if(isset($value["linkto"])) { ?>
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
									<script>
										$(function() {
											$("#<?php echo $key; ?>_value").slider({
												animate: "400",
												max: 36,
												min: 30,
												step: 2,
												value: 36,
												slide: function(event, ui) {
													$("#<?php echo $key; ?>").val(ui.value + "\"");
												}
											});
											$("#<?php echo $key; ?>").val($("#<?php echo $key; ?>_value").slider("value") + "\"");
										});
									</script>
									<?php
								break; */
								default:
									?>
									<input <?php echo $disabled; ?> type="<?php echo $value["type"]; ?>" name="<?php echo $key; ?>" value="<?php echo $this->validator->getValue($key); ?>" class="input-xlarge text-tip" id="<?php echo $key; ?>" <?php if(isset($value["tooltip"])) echo "title=\"".$value['tooltip']."\""; ?> <?php echo (isset($value["required"]) && !isset($value["showwhenequal"])) ? "required" : ""; ?> <?php if (isset($value["unique_id"])) {echo 'id="'.$value["unique_id"].'"';} ?> />
										<?php if (isset($value["required"])) { ?>
											<script>
												$("form [name='<?php echo $key; ?>']").on('change invalid',function(){
													var <?php echo $key; ?>_var = $(this).get(0);
													<?php echo $key; ?>_var.setCustomValidity('');
													if (!<?php echo $key; ?>_var.validity.valid) {
														<?php echo $key; ?>_var.setCustomValidity('<?php echo $value["error"] ?>');
													}
												});
											</script>
										<?php } ?>
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
				<?php if(!isset($this->sendButton)||$this->sendButton){ ?>
				<div class="form-actions" >
					<button type="submit" class="btn btn-info"><?php echo $this->addBtn; ?></button>
					<button onclick="$('#<?php echo $this->div_id; ?>').hide('slow');event.preventDefault();this.form.reset();" class="btn btn-warning">Cancelar</button>
				</div>
				<?php } ?>
			</fieldset>
		</form>
	</div>
</div>
<script>
	function sendSubmit_<?php echo $this->formId; ?>(e){
		e.preventDefault();
		var forms =<?php echo json_encode($this->importObjects); ?>;
		forms.push("<?php echo $this->formId; ?>");
		var $last;
		var fullForm="<div style='display:none;'>"+
		"<form method='POST' enctype='multipart/form-data' action='<?php echo $this->sendTo; ?>' id='gostFormTrigger'>";
		fullForm+="</form></div>";
		$("body").append(fullForm);
		$("#gostFormTrigger").ready(function(){
			$.each(forms,function(k,v){
				var key="#form_creator_"+k;
				$last=$(key+" input,"+key+" select,"+key+" textarea").clone().appendTo("#gostFormTrigger");
				$last.map(function(k,v){
					v.value=$("[name='"+v.name+"']").val();
				});
				//$last.siblings("select").val($(key+" select").val());
				//$last.appendTo("#gostFormTrigger");
			});
		});
		$last.ready(function(){
			$("#gostFormTrigger").submit();
		});
	}
	// $(function(){
	// 	$(".datepicker").datetimepicker({
	// 		//language:  'fr',
	// 		format: 'yyyy-mm-dd',
	// 		weekStart: 1,
	// 		todayBtn:  1,
	// 		autoclose: 1,
	// 		todayHighlight: 1,
	// 		startView: 2,
	// 		minView: 2,
	// 		forceParse: 0,
	// 		showMeridian: 1
	// 	});
	// });

	$(function(){
		$(".dateTimePicker").datetimepicker({
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

	$(function(){
		$(".datePicker").datetimepicker({
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
