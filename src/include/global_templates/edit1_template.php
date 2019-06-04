<?php $num_disp=2; ?>
<tbody id="<?php echo $this->tableId; ?>_display2">
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
					$this->alones[$key2]=$this->columns[$key2];
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
								$result_temp[$this->columns[$key2]["as"]]=$value2;
								echo $value2;
							break;
						}
					}else{
						$result_temp[$this->columns[$key2]["as"]]=$value2;
						echo $value2;
					}
				}
			?></td>
		<?php
				if(($i+1)%$num_disp==0) echo "</tr><tr>";
				$i++;
			}
		}
		$n=$num_disp-$i%$num_disp;
		//$n=3-$n;
		$n=$n==$num_disp?0:$n;
		for($i=1;$i<=$n;$i++){
			echo "<th></th><td></td>";
		}
		?>
	</tr>
	<?php
		foreach($this->alones as $value){
	?>
	<tr>
		<th><?php echo $value["as"]; ?></th>
		<td colspan="5"><?php echo $value["value"]; ?></td>
	</tr>
	<?php } ?>
</tbody>
