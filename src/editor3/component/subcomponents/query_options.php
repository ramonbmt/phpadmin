<?php
/********************** Query Options ******************/
global $validator5;
$validator5=new validate();
$validator5->setPage($filename."5");
if($validator5->secureSend()){
	$check=array(
		"table"=>array('isset'=>'','notempty'=>'Favor de ingresar una Tabla','appendquotes'=>"'"),
		"column"=>array('isset'=>'','notempty'=>'Favor de ingresar una Columna','appendquotes'=>"'"),
		"option"=>array('isset'=>'','notempty'=>'Favor de ingresar una Opcion','appendquotes'=>"'"),
		"operand"=>array('isset'=>'','notempty'=>'Favor de ingresar una Operacion','appendquotes'=>"'"),
		"conector"=>array('isset'=>'','notempty'=>'Favor de ingresar un Conector','appendquotes'=>"'"),
		"valor"=>array('isset'=>'','notempty'=>'Favor de ingresar un Valor','appendquotes'=>"'"),
	);
	if($validator5->validator($check, 'post')){
		switch($validator5->getValue("option")){
			case 0:
				$option="where";
			break;
		}
		switch($validator5->getValue("operand")){
			case 0:
				$operand=" = ";
			break;
			case 1:
				$operand=" <> ";
			break;
		}
		switch($validator5->getValue("conector")){
			case 0:
				$conector=" AND ";
			break;
			case 1:
				$conector=" OR ";
			break;
		}
		$current_object["tables"][]=array(
			"querytype"=>$option,
			"name"=>$validator5->getValue("table"),
			"col1"=>$validator5->getValue("column"),
			"operand"=>$operand,
			"conector"=>$conector,
			"valor"=>$validator5->getValue("valor"),
		);
	}else{
		$data["error"]=$validator->getError();
	}
}
global $ajax6;
$ajax6 = new ajax_class("ajax_template_self","ajax_template_self");
$ajax6->populate(array(
	"table",
	"field"
),function(){
	global $validator;
	global $validator5;
	global $current_object;
	global $html;
	$check=array(
		"table"=>array('isset'=>'','notempty'=>'Favor de ingresar una Tabla','appendquotes'=>"'"),
		"field"=>array('isset'=>'','notempty'=>'Favor de ingresar una Columna','appendquotes'=>"'"),
	);
	if($validator->validator($check, 'post')){
		$form=new form_class("form_template");
		$options=array(
			array("id"=>0,"name"=>"where")
		);
		$options_operand=array(
			array("id"=>0,"name"=>"Igual"),
			array("id"=>1,"name"=>"Dirente")
		);
		$options_conector=array(
			array("id"=>0,"name"=>"AND"),
			array("id"=>1,"name"=>"OR")
		);
		$form->populate(array(
			"table"=>array("as"=>"Tabla","type"=>"text"),
			"column"=>array("as"=>"Columna","type"=>"text"),
			"option"=>array("as"=>"Tabla Join","type"=>"select","select"=>$options),
			"operand"=>array("as"=>"Operador","type"=>"select","select"=>$options_operand),
			"conector"=>array("as"=>"Conector","type"=>"select","select"=>$options_conector),
			"valor"=>array("as"=>"Valor","type"=>"text"),
		),$this->html->link("editor3"));
		$validator5->setValue("table",$validator->getValue("table"));
		$validator5->setValue("column",$validator->getValue("field"));
		$form->setSendTo($this->html->link("editor3")."/?id=".$_GET["id"]);
		$form->setValidator($validator5);
		$data["form"]=$form->createFormString();
		$data["eval"]='$.colorbox({
			width: "630px",
			html:data.form,
		});';
		$data["success"]=0;
		echo json_encode($data);
		die();
	}else{
		$data["error"]=$validator->getError();
	}
},$filename."6",$filename);
/***************************************************/
?>
