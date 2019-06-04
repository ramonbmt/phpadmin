<?php
	$alones=array();
?>
<table id="<?php echo $this->tableId; ?>_display" class="table table-striped table-bordered" style="width: inherit;background-color:#fff;">
	<tbody>
		<tr>
			<?php $i=0;
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
			?>
				<th><?php echo $this->columns[$key2]["as"]; ?></th>
				<td><?php 
					if(isset($this->columns[$key2]["link"])&&$this->columns[$key2]["link"]){
						echo $fooFirst($value2,$value,$key2);
					}else{
						if(isset($this->columns[$key2]["type"])){
							switch($this->columns[$key2]["type"]){
								case "image":
								?>
								<img src="/<?php echo $value2; ?>" alt="<?php echo $this->columns[$key2]["as"]; ?>" />
								<?php
								break;
								case "file":
								?>
								<a href="/<?php echo $value2; ?>" alt="<?php echo $this->columns[$key2]["as"]; ?>" /><?php echo $value2; ?></a>
								<?php
								break;
								default:
									echo $value2;
								break;
							}
						}else{
							echo $value2;
						}
					}
				?></td>
			<?php 
					if(($i+1)%3==0) echo "</tr><tr>";
					$i++;
				}
			}
			$n=3-$i%3;
			//$n=3-$n;
			$n=$n==3?0:$n;
			for($i=1;$i<=$n;$i++){
				echo "<th></th><td></td>";
			}
			?>
		</tr>
		<?php
			foreach($alones as $value){
		?>
		<tr>
			<th><?php echo $value["as"]; ?></th>
			<td colspan="5"><?php echo $value["value"]; ?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<table id="<?php echo $this->tableId; ?>_edit" class="table table-striped table-bordered" style="width: inherit;display:none;background-color:#fff;">
	<tbody>
		<form id="edit_user_form" method="post" enctype="multipart/form-data">
			<tr>
				<?php $i=0;
				foreach($this->result as $key=>$value){ 
					foreach($value as $key2=>$value2){
						//if($i==0) echo "<tr>";
						if(isset($this->columns[$key2]["display"])&&$this->columns[$key2]["display"]==false)
							continue;
						if(isset($this->columns[$key2]["alone"])&&$this->columns[$key2]["alone"]==true){
							continue;
						}
				?>
					<th><?php echo $this->columns[$key2]["as"]; ?></th>
					<?php
						if(isset($this->columns[$key2]["link"])&&$this->columns[$key2]["link"]){
							echo "<td>".$foo($value2,$value,$key2)."</td>";
						}else{
							if(isset($this->columns[$key2]["type"])){
								//echo $this->columns[$key2]["type"];
								switch($this->columns[$key2]["type"]){
									case "select":
										echo "<td><select name='".$key2."'>";
										foreach($this->columns[$key2]["select"] as $value3){
											if(isset($this->columns[$key2]["select_id"])){
												$selector=$value[$this->columns[$key2]["select_id"]];
											}else{
												$selector=$value2;
											}
											?>
											<option <?php if($selector==$value3["id"]) echo "selected='selected'"; ?> value="<?php echo $value3["id"]; ?>"><?php echo $value3["name"]; ?></option>
											<?php
										}
										echo "</select></td>";
										//echo "<td>"."hola"."</td>";
									break;
									case "textarea":
										?>
										<td><textarea <?php if(isset($this->columns[$key2]["disabled"])&&$this->columns[$key2]["disabled"]) echo "disabled='disabled'"; ?> name="<?php echo $key2; ?>" /><?php echo $value2; ?></textarea></td>
										<?php
									break;
									case "image":
										?>
										<td><div>
										<input id="" <?php if(isset($this->columns[$key2]["disabled"])&&$this->columns[$key2]["disabled"]) echo "disabled='disabled'"; ?> <?php if(isset($this->columns[$key2]["height"])){ ?>height="<?php echo $this->columns[$key2]["height"]; ?>"<?php } ?> <?php if(isset($this->columns[$key2]["width"])){ ?>height="<?php echo $this->columns[$key2]["width"]; ?>"<?php } ?> type="file"  name="<?php echo $key2; ?>" class=""/>
										</div>
										</td>
										<?php
									break;
									case "file":
										?>
										<td><div>
										<input id="" <?php if(isset($this->columns[$key2]["disabled"])&&$this->columns[$key2]["disabled"]) echo "disabled='disabled'"; ?> type="file"  name="<?php echo $key2; ?>" class=""/>
										</div>
										</td>
										<?php
									break;
									case "date":
										?>
										<td><input size="16" type="text" class="datepicker" name="<?php echo $key2; ?>" value="<?php echo $value2; ?>" /></td>
										<?php
									break;
								}
							}else{
					?>
						<td><input <?php if(isset($this->columns[$key2]["disabled"])&&$this->columns[$key2]["disabled"]) echo "disabled='disabled'"; ?> type="text" value="<?php echo $value2; ?>" name="<?php echo $key2; ?>" /></td>
					<?php  }} ?>
				<?php 
						if(($i+1)%3==0) echo "</tr><tr>";
						$i++;
					}
				} 
				$n=3-$i%3;
				//echo $n;
				//$n=3-$n;
				$n=$n==3?0:$n;
				for($i=1;$i<=$n;$i++){
					echo "<th></th><td></td>";
				}
				?>
			</tr>
			<?php
			foreach($alones as $key=>$value){
			?>
			<tr>
				<th><?php echo $value["as"]; ?></th>
				<td colspan="5"><textarea style="width:600px;" <?php if(isset($value["disabled"])&&$value["disabled"]) echo "disabled='disabled'"; ?> name="<?php echo $key; ?>" /><?php echo $value["value"]; ?></textarea></td>
			</tr>
			<?php } ?>
			<?php $validator->secureSendInput(); ?>
			<tr>
				<td colspan="1"><p><button onclick="document.getElementById('edit_user_form').submit();" class="btn btn-success">Actualizar</button></p></td>
				<td colspan="1"><p><button onclick="cancelEdit(event,'<?php echo $this->tableId; ?>');" class="btn btn-danger">Cancelar</button></p></td>
				<td colspan="4"></td>
			</tr>
		</form>
	</tbody>
</table>
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