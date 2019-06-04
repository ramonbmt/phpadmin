<?php
	function renderTable($arr){
		global $ajax3;
		$cols=array();
		foreach($arr as $key=>$value){
			foreach($value as $key2=>$value2){
				$cols[$key2]=1;
			}
		}
		$data="";
		$data.="<table class='table table-default table-bordered'>";
		$data.="<tr>";
		foreach($cols as $key=>$value){
			$data.="<th>".$key."</th>";
		}
		$data.="<th>Eliminar</th>";
		$data.="<tr>";
		foreach($arr as $key=>$value){
			$data.="<tr>";
			foreach($cols as $key2=>$value2){
				if(isset($value[$key2])){
					$data.="<td>".$value[$key2]."</td>";
				}else{
					$data.="<td></td>";
				}
			}
			$data.="<td><span style='cursor:pointer;' onclick='".$ajax3->generateFunc($key)."'>X</span></td>";
			$data.="</tr>";
		}
		$data.="</table>";
		return $data;
	}
	function renderTableQuery($arr){
		global $ajax5;
		$cols=array();
		foreach($arr as $key=>$value){
			foreach($value as $key2=>$value2){
				$cols[$key2]=1;
			}
		}
		$data="";
		$data.="<table class='table table-default table-bordered'>";
		$data.="<tr>";
		foreach($cols as $key=>$value){
			$data.="<th>".$key."</th>";
		}
		$data.="<th>Eliminar</th>";
		$data.="<tr>";
		foreach($arr as $key=>$value){
			$data.="<tr>";
			foreach($cols as $key2=>$value2){
				if(isset($value[$key2])){
					$data.="<td>".$value[$key2]."</td>";
				}else{
					$data.="<td></td>";
				}
			}
			$data.="<td><span style='cursor:pointer;' onclick='".$ajax5->generateFunc($key)."'>X</span></td>";
			$data.="</tr>";
		}
		$data.="</table>";
		return $data;
	}
	function renderTableObjects($arr){
		global $ajax3;
		global $ajax4;
		global $ajax5;
		global $ajax6;
		$tabable=false;
		$cols=array();
		foreach($arr as $key=>$value){
			foreach($value as $key2=>$value2){
				$cols[$key2]=1;
			}
		}
		$data="";
		$data.="<table id='componentes' class='table table-default table-bordered'>";
		$data.="<tr>";
		unset($cols["tabable"]);
		foreach($cols as $key=>$value){
			$data.="<th>".$key."</th>";
		}
		$data.="<th>Seleccionar</th>";
		$data.="<th>Tababble</th>";
		$data.="<th>Variable</th>";
		$data.="<th>Eliminar</th>";
		$data.="<tr>";
		foreach($arr as $key=>$value){
			$data.="<tr id='table_item_".$key."'>";
			foreach($cols as $key2=>$value2){
				if(isset($value[$key2])){
					$data.="<td>".$value[$key2]."</td>";
				}else{
					$data.="<td></td>";
				}
			}
			$data.="<td><span style='cursor:pointer;' onclick='".$ajax4->generateFunc($value["id"])."'>X</span></td>";
			$data.="<td><span style='cursor:pointer;' onclick='".$ajax5->generateFunc($value["id"])."'>".($value["tabable"]==true ? "Eliminar " : "Agregar ")."X</span></td>";
			$data.="<td><span style='cursor:pointer;' onclick='".$ajax6->generateFunc($value["id"])."'>".($value["variable"])."X</span></td>";
			$data.="<td><span style='cursor:pointer;' onclick='".$ajax3->generateFunc($value["id"])."'>X</span></td>";
			$data.="</tr>";
		}
		$data.="</table>";
		return $data;
	}
	function renderTableCol($arr){
		global $ajax6;
		$cols=array();
		foreach($arr as $key=>$value){
			foreach($value as $key2=>$value2){
				$cols[$key2]=1;
			}
		}
		$data="";
		$data.="<table class='table table-default table-bordered'>";
		$data.="<tr>";
		foreach($cols as $key=>$value){
			$data.="<th>".$key."</th>";
		}
		$data.="<th>Select</th>";
		$data.="<th>Config</th>";
		$data.="<tr>";
		foreach($arr as $key=>$value){
			$data.="<tr>";
			foreach($cols as $key2=>$value2){
				if(isset($value[$key2])){
					$data.="<td>".$value[$key2]."</td>";
				}else{
					$data.="<td></td>";
				}
			}
			$data.="<td><span style='cursor:pointer;' onclick='selectCol(\"".$value["table"]."\",\"".$value["Field"]."\");'>X</span></td>";
			$data.="<td><span style='cursor:pointer;' onclick='".$ajax6->generateFunc('"'.$value["table"].'"','"'.$value["Field"].'"')."'>X</span></td>";
			$data.="</tr>";
		}
		$data.="</table>";
		return $data;
	}
	function renderTableTable($arr){
		global $ajax2;
		$cols=array();
		foreach($arr as $key=>$value){
			foreach($value as $key2=>$value2){
				$cols[$key2]=1;
			}
		}
		$data="";
		$data.="<table class='table table-default table-bordered'>";
		$data.="<tr>";
		foreach($cols as $key=>$value){
			$data.="<th>".$key."</th>";
		}
		$data.="<th>Select</th>";
		$data.="<tr>";
		foreach($arr as $key=>$value){
			$data.="<tr>";
			foreach($cols as $key2=>$value2){
				if(isset($value[$key2])){
					$data.="<td>".$value[$key2]."</td>";
					$data.="<td><span style='cursor:pointer;' onclick='".$ajax2->generateFunc('"'.$value[$key2].'"')."'>x</span></td>";
				}else{
					$data.="<td></td>";
				}
			}

			$data.="</tr>";
		}
		$data.="</table>";
		return $data;
	}

?>
