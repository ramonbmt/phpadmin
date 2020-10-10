<?php
	$alones=array();
?>
<style>
	.table-condensed td{
		padding-right:30px;
	}
</style>
<div id="<?php echo $this->tableId; ?>_display" class="flex-box" style="float:left;width:inherit;background-color:#fff;">

		<div class="row">
			<?php
			foreach($this->result as $key=>$value){
				foreach($value as $key2=>$value2){
					if(isset($this->columns[$key2]["display"])&&$this->columns[$key2]["display"]==false)
						continue;
					if(isset($this->columns[$key2]["alone"])&&$this->columns[$key2]["alone"]==true){
						//$alones[]=array($this->columns[$key2],);
						//$alones["value"]=$value2;
						$this->columns[$key2]["value"]=$value2;
						$alones[$key2]=$this->columns[$key2];
						continue;
					}
					if(isset($this->columns[$key2]["cols"])){
						$cols = $this->columns[$key2]["cols"];
					}else{
						$cols = "col-xs-12 col-sm-6 col-md-4 col-lg-4";
					}
				?>
				<div class="<?php echo $cols; ?>">
				<h4><?php echo $this->columns[$key2]["as"]; ?></h4>
				<?php
					if(isset($this->columns[$key2]["link"])&&$this->columns[$key2]["link"]){
						echo $fooFirst($value2,$value,$key2);
					}else{
						if(isset($this->columns[$key2]["type"])){
							switch($this->columns[$key2]["type"]){
								case "image":
									$file_source = "";
									if(isset($value2) and $value2 != ""){
										$file_source = $value2[0]=='/'?$value2:'/'.$value2;
									}else{
										$file_source="/assets/img/image_placeholder.jpg";
										echo '<p class="help-block">No hay imágen</p>';
									}
									?>
									<img width="150px" src="<?php echo $file_source; ?>" <?php if(isset($this->columns[$key2]["width"])) echo "style='width:".$this->columns[$key2]["width"]."px'" ?> alt="<?php echo $this->columns[$key2]["as"]; ?>" />
									<?php
								break;
								case "file":
									$file_source = "";
									if(isset($value2) and $value2 != "")
										$file_source = $value2[0]=='/'?$value2:'/'.$value2;
									?>
									<a href="<?php echo $file_source; ?>" alt="<?php echo $this->columns[$key2]["as"]; ?>" /><?php echo $value2; ?></a>
									<?php
								break;
								case "color": ?>
									<div style="height:auto; width:50%; background-color:<?php echo $value2; ?>"><?php echo $value2; ?></div>
									<?php
								break;
								default:
									echo '<blockquote><p>'.$value2.'</p></blockquote>';
								break;
							}
						}else{
							echo '<blockquote><p>'.$value2.'</p></blockquote>';
						}
					}
				?></div>
			<?php
				}
			}
			?>
		</div>
</div>
<div id="<?php echo $this->tableId; ?>_edit" class="flex-box" style="float:left;width:inherit;display:none;background-color:#fff;">
		<form id="edit_user_form" method="post" enctype="multipart/form-data">
			<div class="row p-3">
				<?php
				foreach($this->result as $key=>$value){
					foreach($value as $key2=>$value2){
						//if($i==0) echo "<tr>";
						if(isset($this->columns[$key2]["display"])&&$this->columns[$key2]["display"]==false)
							continue;
						if(isset($this->columns[$key2]["alone"])&&$this->columns[$key2]["alone"]==true){
							continue;
						}
					?>
					<?php

						if(isset($this->columns[$key2]["cols"])){
							$cols = $this->columns[$key2]["cols"];
						}else{
							$cols = "col-xs-12 col-sm-6 col-md-4 col-lg-4";
						}

						if(isset($this->columns[$key2]["input-cleditor"])){
							$cols = "col-xs-12 col-sm-12 col-md-12 col-lg-12";
						}

						if(isset($this->columns[$key2]["link"])&&$this->columns[$key2]["link"]){
							echo "<div class='".$cols."'><h4>".$this->columns[$key2]["as"]."</h4>".$foo($value2,$value,$key2)."</div>";
						}else{ ?>

							<div class="<?php echo $cols; ?>">
							<?php
							if(isset($this->columns[$key2]["type"])){
								//echo $this->columns[$key2]["type"];
								switch($this->columns[$key2]["type"]){
									case "select":
										echo "<select name='".$key2."' class='select' data-clear-button='true' data-visible-options='15' data-filter='true'";
										foreach($this->columns[$key2]["select"] as $value3){
											if(isset($this->columns[$key2]["select_id"])){
												$selector=$value[$this->columns[$key2]["select_id"]];
											}else{
												$selector=$value2;
											}
											?>
											<option <?php if($selector==$value3["id"]) echo "selected='selected'"; ?> value="<?php echo htmlspecialchars($value3["id"]); ?>"><?php echo $value3["name"]; ?></option>
											<?php
										}
										echo "</select><label class='form-label select-label'>".$this->columns[$key2]["as"]."</label>";
									break;
									case "textarea":
											if(isset($this->columns[$key2]["input-cleditor"])){ ?>
												<textarea style="display:none;" name="<?php echo $key2; ?>" /></textarea>
											<?php } ?>
											<textarea <?php if(isset($this->columns[$key2]["disabled"])&&$this->columns[$key2]["disabled"]) echo "disabled='disabled'"; if(isset($this->columns[$key2]["input-cleditor"])){ echo "class='input-cleditor' id='input-cleditor-".$key2."'"; } ?> name="<?php echo $key2; ?>" />
												<?php echo $value2; ?>
											</textarea>
										<?php
									break;
									case "image":
										$exists_file = false;
										if(isset($this->columns[$key2]["exists_image"])){
											$file_source = $this->columns[$key2]["exists_image"]==''?'/assets/img/image_placeholder.jpg':$this->columns[$key2]["exists_image"];
											//la siguiente linea lo unico que hace es agregar una '/' si no lo trae
											$file_source = substr($file_source,0,1)=='/'?$file_source:'/'.$file_source;
											$exists_file = true;
										}

										?>
										<div class="fileinput fileinput-new text-center" data-provides="fileinput">
											<div class="fileinput-new thumbnail">
												<img width="150px" src="<?php echo $file_source; ?>" alt="...">
											</div>
											<div class="fileinput-preview fileinput-exists thumbnail"></div>
											<div>
												<span class="btn btn-round btn-rose btn-file">
													<span class="fileinput-new">Subir Imágen</span>
													<span class="fileinput-exists">Cambiar Imágen</span>
													<input id="" <?php if(isset($this->columns[$key2]["disabled"])&&$this->columns[$key2]["disabled"]) echo "disabled='disabled'"; ?> <?php if(isset($this->columns[$key2]["height"])){ ?>height="<?php echo $this->columns[$key2]["height"]; ?>"<?php } ?> <?php if(isset($this->columns[$key2]["width"])){ ?>height="<?php echo $this->columns[$key2]["width"]; ?>"<?php } ?> type="file"  name="<?php echo $key2; ?>" class=""/>
													<div class="ripple-container"></div>
												</span>
												<br>
												<a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Borrar</a>
											</div>
											<p class="help-block"><strong>Nota:</strong> La imagen no debe de ser mayor de 2MB.</p>
										</div>
											<!-- <input id="" <?php if(isset($this->columns[$key2]["disabled"])&&$this->columns[$key2]["disabled"]) echo "disabled='disabled'"; ?> <?php if(isset($this->columns[$key2]["height"])){ ?>height="<?php echo $this->columns[$key2]["height"]; ?>"<?php } ?> <?php if(isset($this->columns[$key2]["width"])){ ?>height="<?php echo $this->columns[$key2]["width"]; ?>"<?php } ?> type="file"  name="<?php echo $key2; ?>" class=""/> -->
										<?php
									break;
									case "file": ?>
											<input id="" <?php if(isset($this->columns[$key2]["disabled"])&&$this->columns[$key2]["disabled"]) echo "disabled='disabled'"; ?> type="file"  name="<?php echo $key2; ?>" class=""/>
										<?php
									break;
									case "date":
										?>
                                            <div data-format="yyyy-mm-dd" data-toggle-button="false" class="form-outline mb-4 <?php if(!isset($this->columns[$key2]["disabled"]) || $this->columns[$key2]["disabled"] == false){ echo 'datepicker'; } ?>">
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    id="<?php echo $key2; ?>"
                                                    data-toggle="datepicker"
                                                    name="<?php echo $key2; ?>"
                                                    value="<?php echo $value2 ?>"
                                                    <?php if(isset($this->columns[$key2]["disabled"])) echo 'disabled'; ?>
                                                />
                                                <label for="<?php echo $key; ?>" class="form-label"><?php echo $this->columns[$key2]["as"]; ?></label>
                                            </div>
										<?php
									break;
									case "datetime":
										?>
											<input size="16" type="text" class="form-control datepicker" name="<?php echo $key2; ?>" value="<?php echo $value2; ?>" />
										<?php
									break;
									case "currency":
										$clean_value = str_replace(',', '' , $value2);;
										?>
										<input class="form-control" <?php if(isset($this->columns[$key2]["disabled"])&&$this->columns[$key2]["disabled"]) echo "disabled='disabled'"; if(isset($this->columns[$key2]["required"])&&$this->columns[$key2]["required"]) echo "required"; ?> type="text" value="<?php echo $clean_value; ?>" name="<?php echo $key2; ?>" />
										<?php
									break;
									case "color": ?>
										<input value="<?php if(isset($value2) && $value2 != ''){ echo $value2; }; ?>" class="form-control" <?php if(isset($this->columns[$key2]["disabled"])&&$this->columns[$key2]["disabled"]) echo "disabled='disabled'"; if(isset($this->columns[$key2]["required"])&&$this->columns[$key2]["required"]) echo "required"; ?> type="color" value="<?php echo $clean_value; ?>" name="<?php echo $key2; ?>" />
										<?php
									break;
									default: ?>
										<input class="form-control" <?php if(isset($this->columns[$key2]["disabled"])&&$this->columns[$key2]["disabled"]) echo "disabled='disabled'"; if(isset($this->columns[$key2]["required"])&&$this->columns[$key2]["required"]) echo "required"; ?> type="text" value="<?php echo htmlspecialchars($value2); ?>" name="<?php echo $key2; ?>" />
									<?php
									break;
								}
							}else{
							?>
                                <div class="form-outline mb-4">
                                    <input class="form-control" <?php if(isset($this->columns[$key2]["disabled"])&&$this->columns[$key2]["disabled"]) echo "disabled='disabled'"; if(isset($this->columns[$key2]["required"])&&$this->columns[$key2]["required"]) echo "required"; ?> type="text" value="<?php echo htmlspecialchars($value2); ?>" name="<?php echo $key2; ?>" />
                                    <label class="form-label" for="<?php echo $key2; ?>"><?php echo $this->columns[$key2]["as"]; ?></label>
                                </div>
							<?php  } ?>
						</div>
					<?php }
					}
				}
				?>
			</div>
			<?php
			foreach($alones as $key=>$value){
			?>
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<?php	echo '<h4>'.$value["as"].'</h4>'; ?>
					<textarea style="width:600px;" <?php if(isset($value["disabled"])&&$value["disabled"]) echo "disabled='disabled'"; ?> name="<?php echo $key; ?>" /><?php echo $value["value"]; ?></textarea>
				</div>
			</div>
			<?php } ?>
			<?php $validator->secureSendInput(); ?>
			<div class="row">
				<div style="text-align:center;" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<button onclick="pre_send();" class="btn btn-success">Actualizar</button>
				 	<button  style="margin-left:5px" onclick="cancelEdit(event,'<?php echo $this->tableId; ?>');" class="btn btn-danger">Cancelar</button>
				</div>
			</div>
		</form>
</div>
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
	var now = new Date();
	var now_format = now.getFullYear()+'-'+("0" + (now.getMonth() + 1)).slice(-2)+'-'+now.getDate()+' '+now.getHours()+':'+now.getMinutes();
	var month_before_format = now.getFullYear()+'-'+("0" + (now.getMonth())).slice(-2)+'-'+now.getDate()+' '+now.getHours()+':'+now.getMinutes();

	function pre_send(){
		//******* esto FUNCIONA
		// if( $('#input-cleditor').length ){
		// 	$("textarea[name='descripcion']").val($("#input-cleditor").val());
		// }
		//*********************

		// console.log("antes de enviar");
		// console.log($("textarea[name='descripcion']").val());
		//revisar si existe input-cleditor

		$( ".input-cleditor" ).each(function( index ) {
			if( $(this).length ){
				if ( $( this ).is( "textarea[name='descripcion']")) {
					$("textarea[name='descripcion']").val($(this).val());
		    }
				if ( $( this ).is( "textarea[name='caracteristicas']")) {
					$("textarea[name='caracteristicas']").val($(this).val());
		    }
			}
		});

		// console.log($("textarea[name='descripcion']").val());
		// alert("alto");
		var required_pending = false;
		$('input, textarea,select').filter('[required]:visible').each(function(){
			if($(this).val() == ""){
				required_pending = true;
			}
		});
		if(!required_pending){
			$('#edit_user_form').submit();
		}
	}
	$(document).ready(function() {
		$("#input-cleditor-descripcion").cleditor({
			width: 800, // width not including margins, borders or padding
			height: 250, // height not including margins, borders or padding
			controls: // controls to add to the toolbar
				 "bold italic underline strikethrough subscript superscript | font size " +
				 "style | color highlight removeformat | bullets numbering | outdent " +
				 "indent | alignleft center alignright justify | undo redo | " +
				 "rule image link unlink | cut copy paste pastetext | print source",
			colors: // colors in the color popup
				 "FFF FCC FC9 FF9 FFC 9F9 9FF CFF CCF FCF " +
				 "CCC F66 F96 FF6 FF3 6F9 3FF 6FF 99F F9F " +
				 "BBB F00 F90 FC6 FF0 3F3 6CC 3CF 66C C6C " +
				 "999 C00 F60 FC3 FC0 3C0 0CC 36F 63F C3C " +
				 "666 900 C60 C93 990 090 399 33F 60C 939 " +
				 "333 600 930 963 660 060 366 009 339 636 " +
				 "000 300 630 633 330 030 033 006 309 303",
			fonts: // font names in the font popup
				 "Arial,Arial Black,Comic Sans MS,Courier New,Narrow,Garamond," +
				 "Georgia,Impact,Sans Serif,Serif,Tahoma,Trebuchet MS,Verdana",
			sizes: // sizes in the font size popup
				 "1,2,3,4,5,6,7",
			styles: // styles in the style popup
				 [["Paragraph", "<p>"], ["Header 1", "<h1>"], ["Header 2", "<h2>"],
				 ["Header 3", "<h3>"],  ["Header 4","<h4>"],  ["Header 5","<h5>"],
				 ["Header 6","<h6>"]],
			useCSS: false, // use CSS to style HTML when possible (not supported in ie)
			docType: // Document type contained within the editor
				 '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">',
			docCSSFile: // CSS file used to style the document contained within the editor
				 "",
			bodyStyle: // style to assign to document body contained within the editor
				 "margin:4px; font:10pt Arial,Verdana; cursor:text"
		});
	});
</script>
