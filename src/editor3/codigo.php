<?php
function renderQueryComp($arr){
	$str="";
	foreach($arr as $key=>$value){
		switch($value["querytype"]){
			case "join":
				//$str.="inner join ".$value["name"]." on ".$table_parent.".".$value["col1"]."=".$value["name"].".".$value["col2"]."";
				$str.='->join("'.$value["name"].'","'.$value["col1"].'","'.$value["col2"].'","'.$value["jointype"].'","'.$value["joinwith"].'")';
			break;
			case "where":
				$str.='->whereFull(array(array("'.$value["conector"].'","'.$value["name"].".".$value["col1"].'","'.$value["operand"].'",'.$value["valor"].')))';
			break;
		}
	}
	return $str;
}
function renderCodeCTable($arr){
	$cols=array();
	if(!empty($arr["tables"])){
		$str='
$this->table->next($this->filename);
$this->table->getLast()->populate(array(
	';
		foreach($arr["cols"] as $key=>$value){
			$str.='"'.$value["col"].'"=>array(';
			unset($value["col"]);
			foreach($value as $key2=>$value2){
				if($value2=='true'||$value2=='false'){
					$str.='"'.$key2.'"=>'.$value2.',';
				}else{
					$str.='"'.$key2.'"=>"'.$value2.'",';
				}
			}
			$str.='),
	';
		}
		$str.='),
	';
		$str.='"'.$arr["tables"][0]["name"].'"';
		$str.=',
	';
		$table_parent=$arr["tables"][0]["name"];
		$str.='$this->sqlbuilder->setTable("'.$table_parent.'")';
		unset($arr["tables"][0]);
		$str.=renderQueryComp($arr["tables"]);
		$str.='->getSql(),
	';
		$str.='function($data,$value,$key){
		global $html;
		switch($key){';
		foreach($arr["cols"] as $key=>$value){
			if($value["link"]=='true'){
				$str.='
			case "'.$value["mysqlas"].'":
				';
				if(isset($arr["cases"][$value["mysqlas"]]))
				$str.=$arr["cases"][$value["mysqlas"]];
				$str.="
			break;
		";
			}
		}
		$str.='}
	},';
		$str.='$this->filename,$this->filename);';
		return $str;
	}
}
function renderCodeVTable($arr){
	$cols=array();
	if(!empty($arr["tables"])){
		/*$str='
foreach($this->table->objects as $key=>$value){
	$data["result"].= $value->createFullTableString();
}';*/
$str='';
if($arr["variable"]!=""){
	if($arr["tabable"]){
		$str='$this->tabbs->newTabb($this->table->objects['.$arr["id"].']->createFullString(),"");';
	}else{
		$str='$this->data["'.$arr["variable"].'"].= $this->table->objects['.$arr["id"].']->createFullString();';
	}
}
		return $str;
	}
}
function renderCodeCEdit($arr){
	$cols=array();
	if(isset($arr["params"]["validator"])){
		$validator='$this->validator->objects['.$arr["params"]["validator"].']';
	}else{
		$validator='$this->validator';
	}
	if(!empty($arr["tables"])){
		$str='
$this->edit->next($this->filename);
$this->edit->getLast()->populate(array(
	';
		foreach($arr["cols"] as $key=>$value){
			$str.='"'.$value["col"].'"=>array(';
			unset($value["col"]);
			foreach($value as $key2=>$value2){
				if($value2=='true'||$value2=='false'){
					$str.='"'.$key2.'"=>'.$value2.',';
				}else{
					$str.='"'.$key2.'"=>"'.$value2.'",';
				}
			}
			$str.='),
	';
		}
		$str.='),
	';
		$str.='"'.$arr["tables"][0]["name"].'"';
		$str.=',
	';
		$table_parent=$arr["tables"][0]["name"];
		$str.='$this->sqlbuilder->setTable("'.$table_parent.'")';
		unset($arr["tables"][0]);
		$str.=renderQueryComp($arr["tables"]);
		$str.='->getSql(),
	';

		$str.='"editor1",
	';
		$str.='function($data,$value,$key){
		global $html;
		switch($key){';
		foreach($arr["cols"] as $key=>$value){
			if($value["link"]=='true'){
				$str.='
			case "'.$value["mysqlas"].'":
				';
				if(isset($arr["cases"][$value["mysqlas"]]))
				$str.=$arr["cases"][$value["mysqlas"]];
				$str.="
			break;
		";
			}
		}
		$str.='}
	},';
	$str.='function($data,$value,$key){
		global $html;
		switch($key){';
		foreach($arr["cols"] as $key=>$value){
			if($value["link"]=='true'){
				$str.='
			case "'.$value["mysqlas"].'":
				';
				if(isset($arr["cases"][$value["mysqlas"]]))
				$str.=$arr["cases"][$value["mysqlas"]];
				$str.="
			break;
		";
			}
		}
		$str.='}
	});';
		$str.='
$this->edit->getLast()->setValidator('.$validator.');';
		return $str;
	}
}
function renderCodeVEdit($arr){
	$cols=array();
	if(!empty($arr["tables"])){
		/*$str='
$data["result"].= $this->edit->getLast()->makeEditString();';*/
$top='<div class="widget-head">
				<h5 class="pull-left"> Editable</h5>
				<div class="btn-group pull-right">
					<li><a href="#" onclick="startEdit(event,\'editor1\');"><span class="color-icons page_white_edit_co"></span></a></li>
				</div>
			</div>';
$str='';
if($arr["variable"]!=""){
	if($arr["tabable"]){
		$str='$this->tabbs->newTabb($this->edit->objects['.$arr["id"].']->createFullString(),"");';
	}else{
		$str='$this->data["'.$arr["variable"].'"].= $this->edit->objects['.$arr["id"].']->createFullString();';
	}
}
		return $str;
	}
}
function renderCodeCForm($arr){
	if(isset($arr["params"]["validator"])){
		$validator='$this->validator->objects['.$arr["params"]["validator"].']';
	}else{
		$validator='$this->validator';
	}
	$cols=array();
	if(!empty($arr["cols"])){
		$str='
$this->form->next($this->filename);
$this->form->getLast()->populate(array(
	';
		foreach($arr["cols"] as $key=>$value){
			$str.='"'.$value["name"].'"=>array(';
			unset($value["name"]);
			foreach($value as $key2=>$value2){
				if($value2=='true'||$value2=='false'||$key2=='searchselect'||$key2=='select'){
					$str.='"'.$key2.'"=>'.$value2.',';
				}else{
					$str.='"'.$key2.'"=>"'.$value2.'",';
				}
			}
			$str.='),
	';
		}
		$str.='),
	';
		if(isset($arr["params"]["return_to"])){
			$str.='"'.$arr["params"]["return_to"].'"';
		}else{
			$str.='""';
		}
		//$str.='"ReturnTo"';
		$str.=',
	';
		if(isset($arr["params"]["send_to"])){
			$str.='"'.$arr["params"]["send_to"].'"';
		}else{
			$str.='""';
		}
		$str.=');
$this->form->getLast()->setFunction(function($data,$value,$key){
global $html;
switch($key){';
foreach($arr["cols"] as $key=>$value){
	if(isset($value["link"])&&$value["link"]=='true'){
		$str.='
	case "'.$key.'":
		';
		if(isset($arr["cases"][$key]))
		$str.=$arr["cases"][$key];
		$str.="
	break;
";
	}
}
$str.='}
});
$this->form->getLast()->setValidator('.$validator.');
	';
		return $str;
	}
}
function renderCodeVForm($arr){
	$cols=array();
	if(!empty($arr["cols"])){
/*$str='
foreach($this->form->objects as $key=>$value){
	$data["result"].= $value->createFullString();
}';*/

$str='';
if($arr["variable"]!=""){
	if($arr["tabable"]){
		$str='$this->tabbs->newTabb($this->form->objects['.$arr["id"].']->createFullString(),"");';
	}else{
		$str='$this->data["'.$arr["variable"].'"].= $this->form->objects['.$arr["id"].']->createFullString();';
	}
}
		return $str;
	}
}
function renderCodeCValidator($arr){
	$cols=array();
	if(!isset($arr["params"]["pre_action"])) $arr["params"]["pre_action"] = "";
	if(!isset($arr["params"]["action"])) $arr["params"]["action"] = "";
	if(!isset($arr["params"]["post_action"])) $arr["params"]["post_action"] = "";
	if(!isset($arr["params"]["self1"])) $arr["params"]["self1"] = "";
	if(!isset($arr["params"]["self2"])) $arr["params"]["self2"] = "";
	$arr["params"]["action"].='
			$this->validator->getLast()->clearValues();';

	//var_dump($arr);
	if(!empty($arr["cols"])){
		$str='
$this->validator->next($this->filename);
if($this->validator->getLast()->secureSend()){
	$check=array(
	';
		foreach($arr["cols"] as $key=>$value){
			$str.='	"'.$value["key"].'"=>array(';
			unset($value["key"]);
			foreach($value as $key2=>$value2){
				if($value2=='true'||$value2=='false'){
					$str.='"'.$key2.'"=>'.$value2.',';
				}else{
					$str.='"'.$key2.'"=>"'.$value2.'",';
				}
			}
			$str.='),
	';
		}
		$str.=');
	';
	$str.=$arr["params"]["pre_action"].'
	if($this->validator->getLast()->validator($check, "post")){
		'.$arr["params"]["self1"].'
		'.$arr["params"]["action"].'
		'.$arr["params"]["self2"].'
		'.$arr["params"]["post_action"].'
	}else{
		$error=$this->validator->getLast()->getError();
		$this->data["error"]=$error;
	}
}';
		return $str;
	}
}
function renderCodeCVariable($arr){
	$str="";
	if(isset($arr["params"]["value_type"])){
		switch ($arr["params"]["value_type"]) {
			case 0:
				$str.='$this->data["'.$arr["params"]["variable"].'"]='.$arr["params"]["valor"].";";
			break;
			case 1:
				$str.='$this->data["'.$arr["params"]["variable"].'"].='.$arr["params"]["valor"].";";
			break;
		}
	}
	return $str;
}

function renderCodeCAjax($arr){
	$str='$this->ajax->next($this->filename);';
	$str.='$ajax->populate(array(
		"idSession",
	),function(){
		global $validator;
		$check=array(
			"idSession"=>array(\'isset\'=>\'\',\'notempty\'=>\'Favor de ingresar un Nombre\',\'appendquotes\'=>"\'"),
		);
		if($validator->validator($check, \'post\')){

			$data["success"]=0;
			$data["jumpTo"]=$this->html->link("editor3")."/?".http_build_query(array_merge($_GET));
			echo json_encode($data);
			die();
		}else{
			$data["error"]=$validator->getError();
		}
	},$this->filename,$this->filename);';
	return $str;
}
function renderCodeVAjax($arr){
	$str="";
	if(isset($arr["variable"])&&$arr["variable"]!=""){
		$str='$this->data["'.$arr["variable"].'"].= $this->ajax->objects['.$arr["id"].']->createAjaxString();';
	}
	return $str;
}
function renderCodeVButon($arr){
	$str='';
	if(isset($arr["params"]["value_type"])&&$arr["params"]["value_type"]==0){
		if(isset($arr["params"]["link_path"])&&isset($arr["params"]["link"])){
			$str.='<a href="'.$arr["params"]["link_path"].'"><button class="btn btn-success">'.$arr["params"]["text"].'</button></a>';
		}
	}

	//return print_r($arr,true);
	return $str;
}
function renderCodeCQuery($arr){
	$str="";

	return $str;
}
function renderCodeVAll(){
	$cols=array();
$str='
foreach($this->table->objects as $key=>$value){
	$this->data["result"].= $value->createFullString();
}';
$str.='
foreach($this->edit->objects as $key=>$value){
	$this->data["result"].= $value->createFullString();
}';
$str.='
foreach($this->form->objects as $key=>$value){
	$this->data["result"].= $value->createFullString();
}';
		return $str;
}
?>
