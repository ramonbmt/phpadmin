<?php
/******** Eliminar Componente del Query *********/
global $ajax5;
$ajax5 = new ajax_class("ajax_template_self","ajax_template_self");
$ajax5->populate(array(
	"idRow",
),function(){
	global $validator;
	global $current_object;
	global $html;
	$check=array(
		"idRow"=>array('isset'=>'','notempty'=>'Favor de ingresar un Nombre','appendquotes'=>"'"),
	);
	if($validator->validator($check, 'post')){
		$current_object=&$_SESSION["editor"]["functions"][$_SESSION["editor"]["current_function"]]["objects"][$_SESSION["editor"]["current_object"]];
		unset($current_object["tables"][$validator->getValue("idRow")]);
		$data["success"]=0;
		$data["jumpTo"]=$this->html->link("editor3")."/?id=2";
		echo json_encode($data);
		die();
	}else{
		$data["error"]=$validator->getError();
	}
},$filename."5",$filename);
/**********************************************/
?>
