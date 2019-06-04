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
		'pre_action'=>array('default'=>'', 'notempty'=>'Favor de ingresar si es Auto Updateable'),
		'action'=>array('default'=>'', 'notempty'=>'Favor de ingresar un '),
		'post_action'=>array('default'=>'', 'notempty'=>'Favor de ingresar un tipo de objecto'),
	);
	if($validator4->validator($check, 'post')){
		$array_params=array(
			"pre_action"=>$validator4->getValue("pre_action"),
			"action"=>$validator4->getValue("action"),
			"post_action"=>$validator4->getValue("post_action")
		);
		$current_object["params"]=array_merge($current_object["params"],$array_params);
		//print_r($current_object["params"]);
		$validator4->clearValues();
	}else{
		$error=$validator4->getError();
	}
}
if(isset($current_object["params"]["pre_action"])){
	$validator4->setValue("pre_action",$current_object["params"]["pre_action"]);
	$validator4->setValue("action",$current_object["params"]["action"]);
	$validator4->setValue("post_action",$current_object["params"]["post_action"]);
}
/*************** General Params *****************/
$validator2=new validate();
$validator2->setPage($filename."2");
if($validator2->secureSend()){
	$check=array(
		'self_insert'=>array('isset'=>'', 'notempty'=>'Favor de ingresar si es Auto Updateable'),
		'tabla'=>array('default'=>'', 'notempty'=>'Favor de ingresar una Tabla'),
		'wherearray'=>array('default'=>'', 'notempty'=>'Favor de ingresar una condicion "where"'),
	);
	if($validator2->validator($check, 'post')){
		$array_params=array(
			"self_insert"=>$validator2->getValue("self_insert"),
			"tabla"=>$validator2->getValue("tabla"),
			"wherearray"=>$validator2->getValue("wherearray")
		);
		$self1="";$self2="";
		switch($validator2->getValue("self_insert")){
			case 1:
			$self1='if($this->validator->getLast()->selfInsert("'.$validator2->getValue("tabla").'")){
			';
			$self2='}else{
			$error=$this->validator->getLast()->getError();
			$data["error"]=$error;
		}';
			break;
			case 2:
			$self1='if($this->validator->getLast()->selfUpdate("'.$validator2->getValue("tabla").'",'.$validator2->getValue("wherearray").')){
			';
			$self2='}else{
			$error=$this->validator->getLast()->getError();
			$data["error"]=$error;
		}';
			break;
		}
		$array_params["self1"]=$self1;
		$array_params["self2"]=$self2;

		$current_object["params"]=array_merge($current_object["params"],$array_params);
		$validator2->clearValues();
	}else{
		$error=$validator2->getError();
		$data["error"]=$error;
	}
}
if(isset($current_object["params"]["self_insert"])){
	$validator2->setValue("self_insert",$current_object["params"]["self_insert"]);
	$validator2->setValue("wherearray",htmlspecialchars($current_object["params"]["wherearray"]));
	$validator2->setValue("tabla",(!isset($current_object["params"]["tabla"]) ? "" : $current_object["params"]["tabla"]));
}
//print_r($current_object);
$form4 = new form_class("form_template");
$array_input_types=array(
	array("name"=>"None","id"=>0),
	array("name"=>"Self Insert","id"=>1),
	array("name"=>"Self Update","id"=>2),
);
$form4->populate(array(
	"self_insert"=>array("as"=>"Type Validator","type"=>"select","select"=>$array_input_types),
	"tabla"=>array("as"=>"Tabla","type"=>"text"),
	"wherearray"=>array("as"=>"Where Array","type"=>"text","linkto"=>"self_insert","showwhenequal"=>2)
),$this->html->link("editor2"));
$form4->setValidator($validator2);
/****************************************************/
//print_r($current_object);
$form3 = new form_class("form_template");
$array_input_types=array(
	array("name"=>"false","id"=>"false"),
	array("name"=>"true","id"=>"true"),
);
$form3->populate(array(
	//"self_inset"=>array("as"=>"Self Update","type"=>"select","select"=>$array_input_types),
	"pre_action"=>array("as"=>"Pre Accion","type"=>"cooltextarea"),
	"action"=>array("as"=>"Accion","type"=>"cooltextarea"),
	"post_action"=>array("as"=>"Post Accion","type"=>"cooltextarea"),
),$this->html->link("editor2"),"","",$validator4);
/*********************************************/
$data["editor3_general_template_form"].='<p style="float:left; margin-right:10px;"><button onclick="'.$ajax->generateFunc("'validator'").'" class="btn btn-success">Borrar Datos</button></p>
<div style="clear:both;"></div>';
$array_select=array(
	array("id"=>0,"name"=>""),
	array("id"=>1,"name"=>"Not Empty"),
	array("id"=>2,"name"=>"Default"),
);
$array_select2=array(
	array("id"=>0,"name"=>"Self Insert"),
	array("id"=>1,"name"=>"Self Update"),
	array("id"=>2,"name"=>"Special"),
);
$form = new form_class("form_template");
$form->populate(array(
	"key"=>array("as"=>"Key","type"=>"text"),
	"type_object"=>array("as"=>"validacion","type"=>"select","select"=>$array_select),
	"val_error"=>array("as"=>"Validacion Error","type"=>"text","linkto"=>"type_object","showwhenequal"=>1),
	"val_default"=>array("as"=>"Valor de Default","type"=>"text","linkto"=>"type_object","showwhenequal"=>2),
	"number"=>array("as"=>"Number","type"=>"text"),
	"alphanumber"=>array("as"=>"Alphanumber","type"=>"text"),
	"greaterthan"=>array("as"=>"greaterthan","type"=>"text"),
	"lessthan"=>array("as"=>"lessthan","type"=>"text"),
	"email"=>array("as"=>"email","type"=>"text"),
	"appendquotes"=>array("as"=>"appendquotes","type"=>"text"),
	"multiple1"=>array("as"=>"Opcion Key","type"=>"multiple","multiple"=>"text"),
	"multiple2"=>array("as"=>"Opcion Value","type"=>"multiple","multiple"=>"text"),
	//"iftrueaction"=>array("as"=>"IFTrueAccion","type"=>"select","select"=>$array_select2),
	//"iftrue"=>array("as"=>"IFTrue","type"=>"textarea"),
),$this->html->link("editor2"));
$validator->setPage($filename);
if($validator->secureSend()){
	$check=array(
		'key'=>array('isset'=>'', 'notempty'=>'Favor de ingresar un key'),
		'type_object'=>array('isset'=>'', 'notempty'=>'Favor de ingresar un tipo de objecto'),
		'val_error'=>array('default'=>'', 'notempty'=>'Favor de ingresar un valor de error'),
		'number'=>array('default'=>'', 'notempty'=>'Favor de ingresar un error para numero'),
		'alphanumber'=>array('default'=>'', 'notempty'=>'Favor de ingresar un error para alphanumero'),
		'greaterthan'=>array('default'=>'', 'notempty'=>'Favor de ingresar un valor que sea mayor'),
		'lessthan'=>array('default'=>'', 'notempty'=>'Favor de ingresar un valor que sea menor'),
		'email'=>array('default'=>'', 'notempty'=>'Favor de ingresar un error para email'),
		'appendquotes'=>array('default'=>'', 'notempty'=>'Favor de ingresar un valor para hacerle append'),
		//'iftrueaction'=>array('isset'=>'', 'notempty'=>'Favor de ingresar un tipo de objecto'),
		//'iftrue'=>array('default'=>'', 'notempty'=>'Favor de ingresar un tipo de objecto'),
		'multiple1'=>array('default'=>'', 'notempty'=>'Favor de ingresar un tipo de objecto'),
		'multiple2'=>array('default'=>'', 'notempty'=>'Favor de ingresar un tipo de objecto'),
	);
	if($validator->validator($check, 'post')){
		$array_params["key"]=$validator->getValue("key");
		switch($validator->getValue("type_object")){
			case 1:
				$array_params["notempty"]=$validator->getValue("val_error");
			break;
			case 2:
				$array_params["default"]=$validator->getValue("val_error");
			break;
		}
		if($validator->getValue("number")!=""){
			$array_params["number"]=$validator->getValue("number");
		}
		if($validator->getValue("alphanumber")!=""){
			$array_params["alphanumber"]=$validator->getValue("alphanumber");
		}
		if($validator->getValue("lessthan")!=""){
			$array_params["greatherthan"]=$validator->getValue("lessthan");
		}
		if($validator->getValue("email")!=""){
			$array_params["email"]=$validator->getValue("email");
		}
		if($validator->getValue("appendquotes")!=""){
			$array_params["appendquotes"]=$validator->getValue("appendquotes");
		}
		foreach($validator->getValue("multiple1") as $key=>$value){
			if($validator->getValue("multiple2")[$key]!=""){
				$array_params[$value]=$validator->getValue("multiple2")[$key];
			}
		}
		if($validator->getValue("type_object")!=""){

		}
		$current_object["cols"][]=$array_params;

		$validator->clearValues();
	}else{
		$error=$validator->getError();
	}
}
$data["editor3_general_template_form"].='<div class="widget-head">
				<h5 class="pull-left"> Parametros</h5>
				<div class="btn-group pull-right">

				</div>
			</div>';
$data["editor3_general_template_form"].=$form4->createFormString();
//print_r($_SESSION["editor"]["form"]);
$data["editor3_general_template_form"].='<div class="widget-head">
				<h5 class="pull-left"> Validator</h5>
				<div class="btn-group pull-right">

				</div>
			</div>';
$data["editor3_general_template_form"].=$form->createFormString(function(){
},$validator);
$data["editor3_general_template_form"].='<div class="widget-head">
				<h5 class="pull-left"> Componentes</h5>
				<div class="btn-group pull-right">

				</div>
			</div>';
$data["editor3_general_template_form"].=renderTable($current_object["cols"]);
$data["editor3_general_template_form"].=$form3->createFormString(function(){
},$validator4);
$data["editor3_general_template_form"].='<div class="widget-head">
				<h5 class="pull-left"> Codigo Controller</h5>
				<div class="btn-group pull-right">

				</div>
			</div>';
$data["editor3_general_template_form"].='<div class="code_syn"><pre class="prettyprint linenums">'.renderCodeCValidator($current_object).'</pre></div>';
$data["editor3_general_template_form"].=$ajax3->createAjaxString();
$data["breadcrumb"]=array("index","editor3",
array("link"=>"editor3","id"=>5),
array("link"=>"editor3","id"=>$_GET["id"]));
?>
