<?php
/******************* JOIN CONFIG **************/
global $validator2;
$validator2=new validate();
$validator2->setPage($filename."2");
if($validator2->secureSend()){
	$check=array(
		"jointype"=>array('isset'=>'','notempty'=>'Favor de ingresar un tipo de join','appendquotes'=>"'"),
		"jointable"=>array('isset'=>'','notempty'=>'Favor de ingresar un Nombre','appendquotes'=>"'"),
		"joinwith"=>array('isset'=>'','notempty'=>'Favor de ingresar una Tabla para relacionarlo','appendquotes'=>"'"),
		"col1"=>array('isset'=>'','notempty'=>'Favor de ingresar un Nombre','appendquotes'=>"'"),
		"col2"=>array('isset'=>'','notempty'=>'Favor de ingresar un Nombre','appendquotes'=>"'"),
	);
	if($validator2->validator($check, 'post')){
		$current_object=&$_SESSION["editor"]["functions"][$_SESSION["editor"]["current_function"]]["objects"][$_SESSION["editor"]["current_object"]];
		//print_r($validator2->getValue("col1")[$validator2->getValue("joinwith")]);
		$current_object["tables"][]=array(
			"querytype"=>"join",
			"jointype"=>$validator2->getValue("jointype"),
			"joinwith"=>$validator2->getValue("joinwith"),
			"name"=>$validator2->getValue("jointable"),
			"col1"=>$validator2->getValue("col1")[$validator2->getValue("joinwith")],
			"col2"=>$validator2->getValue("col2"),
		);
	}else{
		$data["error"]=$validator->getError();
	}
}
global $ajax2;
$ajax2 = new ajax_class("ajax_template_self","ajax_template_self");
$ajax2->populate(array(
	"table",
),function(){
	global $validator2;
	global $html;
	global $connection;
	global $current_object;
	$check=array(
		"table"=>array('isset'=>'','notempty'=>'Favor de ingresar un Nombre','appendquotes'=>"'"),
	);
	if($validator2->validator($check, 'post')){
		$current_object=&$_SESSION["editor"]["functions"][$_SESSION["editor"]["current_function"]]["objects"][$_SESSION["editor"]["current_object"]];
		if(!empty($current_object["tables"])){
			$form=new form_class("form_template");
			$data["success"]=0;
			$jointype=array(
				array("name"=>"Inner Join","id"=>" inner join "),
				array("name"=>"Left Join","id"=>" left join "),
				array("name"=>"Right Join","id"=>" right join "),
			);
			$jointables=array();
			foreach($current_object["tables"] as $key=>$value){
				if(isset($value["querytype"])&&($value["querytype"]!="join")) continue;
				$jointables[]=array("name"=>$value["name"],"id"=>$value["name"]);
			}
			$result_cols1=$this->connection->query("show columns from ?",$validator2->getValue("table"));
			foreach($result_cols1 as $key=>$value){
				$result_cols1[$key]=array("name"=>$value["Field"],"id"=>$value["Field"]);
			}
			$populate_array2=array(
				"jointype"=>array("as"=>"Join Type","type"=>"select","select"=>$jointype),
				"joinwith"=>array("as"=>"Join With","type"=>"select","select"=>$jointables),
				"jointable"=>array("as"=>"Tabla Join","type"=>"text",'isset'=>true),
				"col2"=>array("as"=>$validator2->getValue("table").".","type"=>"select","select"=>$result_cols1,'isset'=>true),
			);
			foreach($current_object["tables"] as $key=>$value){
				$result_cols=$this->connection->query("show columns from ?",$value["name"]);
				foreach($result_cols as $key2=>$value2){
					$result_cols[$key2]=array("name"=>$value2["Field"],"id"=>$value2["Field"]);
				}
				$populate_array2["col1[".$value["name"]."]"]=
					array("as"=>$value["name"].".","linkto"=>"joinwith","showwhenequal"=>"'".$value["name"]."'","type"=>"select","select"=>$result_cols,'isset'=>true);
			}
			$form->populate($populate_array2,$this->html->link("editor3"));
			$validator2->setValue("jointable",$validator2->getValue("table"));
			$form->setSendTo($this->html->link("editor3")."/?id=".$_GET["id"]);
			$form->setValidator($validator2);
			$data["form"]=$form->createFormString();
			$data["eval"]='$.colorbox({
				width: "630px",
				html:data.form,
			});';
			echo json_encode($data);
			die();
		}else{
			//print_r($current_object["tables"]);
			$current_object["tables"][]=array("name"=>$validator2->getValue("table"));
			//$this->current_object["tables"][]=array("name"=>$validator2->getValue("table"));
			$data["success"]=0;
			$data["jumpTo"]=$this->html->link("editor3")."/index/?".http_build_query(array_merge($_GET));
			echo json_encode($data);
			die();
		}
	}else{
		$data["error"]=$validator2->getError();
	}
},$filename."2",$filename);
/*******************************************/
?>
