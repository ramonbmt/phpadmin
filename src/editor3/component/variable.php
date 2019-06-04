<?php
$validator=new validate();
$validator->setPage($filename);
$current_object=&$_SESSION["editor"]["functions"][$_SESSION["editor"]["current_function"]]["objects"][$_SESSION["editor"]["current_object"]];
$ajax = new ajax_class("ajax_template_self","ajax_template_self");
$ajax->populate(array(
	"idSession",
),function(){
	global $validator;
	global $html;
	global $current_object;
	$check=array(
		"idSession"=>array('isset'=>'','notempty'=>'Favor de ingresar un Nombre','appendquotes'=>"'"),
	);
	if($validator->validator($check, 'post')){
		$_SESSION["editor"]["functions"][$_SESSION["editor"]["current_function"]]["objects"][$_SESSION["editor"]["current_object"]]=array("type"=>"formulario","tables"=>array(),"cols"=>array(),"cases"=>array(),
		"tabable"=>false,"variable"=>"");
		$data["success"]=0;
		$data["jumpTo"]=$this->html->link("editor3")."/?".http_build_query(array_merge($_GET));
		echo json_encode($data);
		die();
	}else{
		$data["error"]=$validator->getError();
	}
},$filename,$filename);
global $ajax3;
$ajax3 = new ajax_class("ajax_template_self","ajax_template_self");
$ajax3->populate(array(
	"idRow",
),function(){
	global $validator;
	global $html;
	global $current_object;
	$check=array(
		"idRow"=>array('isset'=>'','notempty'=>'Favor de ingresar un Nombre','appendquotes'=>"'"),
	);
	if($validator->validator($check, 'post')){
		unset($_SESSION["editor"]["functions"][$_SESSION["editor"]["current_function"]]["objects"][$_SESSION["editor"]["current_object"]]["cols"][$validator->getValue("idRow")]);
		//unset($current_object["cols"][$validator->getValue("idRow")]);
		$data["success"]=0;
		$data["jumpTo"]=$this->html->link("editor3")."/?id=4";
		echo json_encode($data);
		die();
	}else{
		$data["error"]=$validator->getError();
	}
},$filename."3",$filename);
/*******************General Params******************/
$validator4=new validate();
$validator4->setPage($filename."4");
if($validator4->secureSend()){
	$check=array(
		'value_type'=>array('default'=>'', 'notempty'=>'Favor de ingresar un tipo de valor'),
		'variable'=>array('default'=>'', 'notempty'=>'Favor de ingresar una variable'),
		'valor'=>array('default'=>'', 'notempty'=>'Favor de ingresar un valor'),
	);
	if($validator4->validator($check, 'post')){
		$array_params=array(
			"value_type"=>$validator4->getValue("value_type"),
			"variable"=>$validator4->getValue("variable"),
			"valor"=>$validator4->getValue("valor")
		);
		$current_object["params"]=array_merge($current_object["params"],$array_params);
		//print_r($current_object["params"]);
		$validator4->clearValues();
	}else{
		$error=$validator4->getError();
	}
}
if(isset($current_object["params"]["value_type"])){
	$validator4->setValue("value_type",$current_object["params"]["value_type"]);
	$validator4->setValue("variable",$current_object["params"]["variable"]);
	$validator4->setValue("valor",$current_object["params"]["valor"]);
}

//print_r($current_object);
$form3 = new form_class("form_template");
$array_input_types=array(
	array("name"=>"Igualar","id"=>0),
	array("name"=>"Contatenar","id"=>1),
);
$form3->populate(array(
	"value_type"=>array("as"=>"Tipo de Valor","type"=>"select","select"=>$array_input_types),
	"variable"=>array("as"=>"Variable","type"=>"text"),
	"valor"=>array("as"=>"Valor","type"=>"cooltextarea"),
),$this->html->link("editor2"),"","",$validator4);
/*********************************************/
$data["editor3_general_template_form"].='<p style="float:left; margin-right:10px;"><button onclick="'.$ajax->generateFunc("'validator'").'" class="btn btn-success">Borrar Datos</button></p>
<div style="clear:both;"></div>';

$data["editor3_general_template_form"].='<div class="widget-head">
				<h5 class="pull-left"> Parametros</h5>
				<div class="btn-group pull-right">

				</div>
			</div>';
$data["editor3_general_template_form"].=$form3->createFormString(function(){
},$validator4);
$data["editor3_general_template_form"].='<div class="widget-head">
				<h5 class="pull-left"> Codigo Controller</h5>
				<div class="btn-group pull-right">

				</div>
			</div>';
$data["editor3_general_template_form"].='<div class="code_syn"><pre class="prettyprint linenums">'.renderCodeCVariable($current_object).'</pre></div>';
$data["editor3_general_template_form"].=$ajax3->createAjaxString();
$data["breadcrumb"]=array("index","editor3",
array("link"=>"editor3","id"=>5),
array("link"=>"editor3","id"=>$_GET["id"]));
?>
