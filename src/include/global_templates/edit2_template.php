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
<tbody id="<?php echo $this->tableId; ?>_edit2">
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
									<option <?php if($selector==$value3["id"]) echo "selected='selected'"; ?> value="<?php echo htmlspecialchars($value3["id"]); ?>"><?php echo $value3["name"]; ?></option>
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
								<td><input <?php if(isset($this->columns[$key2]["disabled"])&&$this->columns[$key2]["disabled"]) echo "disabled='disabled'"; ?> size="16" type="text" class="datepicker" name="<?php echo $key2; ?>" value="<?php echo $value2; ?>" /></td>
								<?php
							break;
						}
					}else{
			?>
				<td><input <?php if(isset($this->columns[$key2]["disabled"])&&$this->columns[$key2]["disabled"]) echo "disabled='disabled'"; ?> type="text" value="<?php echo htmlspecialchars($value2); ?>" name="<?php echo $key2; ?>" /></td>
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
	foreach($this->alones as $key=>$value){
	?>
	<tr>
		<th><?php echo $value["as"]; ?></th>
		<td colspan="5"><textarea style="width:600px;" <?php if(isset($value["disabled"])&&$value["disabled"]) echo "disabled='disabled'"; ?> name="<?php echo $key; ?>" /><?php echo $value["value"]; ?></textarea></td>
	</tr>
	<?php } ?>
	<?php $this->validator->secureSendInput(); ?>
	<input type="hidden" name="ajax" value="1"/>
	<input type="hidden" name="page" value="1"/>
	<input type="hidden" name="action" value="edit"/>
	<input type="hidden" name="sel" value="<?php echo $this->tableId; ?>"/>
	<tr>
		<td colspan="1"><p><button onclick="submitEdit(event);" class="btn btn-success">Actualizar</button></p></td>
		<td colspan="1"><p><button onclick="cancelEdit(event,'<?php echo $this->tableId; ?>');" class="btn btn-danger">Cancelar</button></p></td>
		<td colspan="4"></td>
	</tr>
</tbody>
