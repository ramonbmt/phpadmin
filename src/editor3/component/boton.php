<?php
$val=0;
global $current_object;
$current_object=&$_SESSION["editor"]["functions"][$_SESSION["editor"]["current_function"]]["objects"][$_SESSION["editor"]["current_object"]];
$this->current_object=$_SESSION["editor"]["functions"][$_SESSION["editor"]["current_function"]]["objects"][$_SESSION["editor"]["current_object"]];
$current_func=&$_SESSION["editor"]["functions"][$_SESSION["editor"]["current_function"]];
//print_r(array($_SESSION["editor"]["functions"][$_SESSION["editor"]["current_function"]]["objects"],$_SESSION["editor"]["current_object"]));
$ajax_objects=array(
	array("name"=>"","id"=>"")
);
$i=0;
foreach($current_func["objects"] as $key=>$value){
	if($value["type"]=="ajax"){
		$ajax_objects[]=array("name"=>$i,"id"=>$i);
		$i++;
	}
}
//print_r($ajax);

global $validator;
$validator = new validate();
/******** Borrar Objeto *********/
global $ajax;
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
		$current_object=&$_SESSION["editor"]["functions"][$_SESSION["editor"]["current_function"]]["objects"][$_SESSION["editor"]["current_object"]];
		$current_object=array("tables"=>array(),"cols"=>array(),"cases"=>array());
		$data["success"]=0;
		$data["jumpTo"]=$this->html->link("editor3")."/?".http_build_query(array_merge($_GET));
		echo json_encode($data);
		die();
	}else{
		$data["error"]=$validator->getError();
	}
},$filename,$filename);
/********* Borrar Objeto ********/
/*******************/
global $ajax3;
$ajax3 = new ajax_class("ajax_template_self","ajax_template_self");
$ajax3->populate(array(
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
		unset($current_object["cols"][$validator->getValue("idRow")]);
		$data["success"]=0;
		$data["jumpTo"]=$this->html->link("editor3")."/?id=3";
		echo json_encode($data);
		die();
	}else{
		$data["error"]=$validator->getError();
	}
},$filename."3",$filename);
/**********************/
/*******************General Params******************/
$validator4=new validate();
$validator4->setPage($filename."4");
if($validator4->secureSend()){
	$check=array(
		'value_type'=>array('default'=>'', 'notempty'=>'Favor de ingresar un tipo de valor'),
		'link'=>array('default'=>'', 'notempty'=>'Favor de ingresar una variable'),
		'link_query'=>array('default'=>'', 'notempty'=>'Favor de ingresar una variable'),
		'link_query2'=>array('default'=>'', 'notempty'=>'Favor de ingresar una variable'),
		'link_function'=>array('default'=>'', 'notempty'=>'Favor de ingresar una variable'),
		'function'=>array('default'=>'', 'notempty'=>'Favor de ingresar un valor'),
		'ajax'=>array('default'=>'', 'notempty'=>'Favor de ingresar un valor'),
		'color'=>array('default'=>'', 'notempty'=>'Favor de ingresar un valor'),
		'text'=>array('default'=>'', 'notempty'=>'Favor de ingresar un valor'),
	);
	if($validator4->validator($check, 'post')){
		if($validator4->getValue("link_function")=='') $validator4->setValue("link_function","index");
		$array_params=array(
			"value_type"=>$validator4->getValue("value_type"),
			"link"=>$validator4->getValue("link"),
			"link_query"=>$validator4->getValue("link_query"),
			"link_query2"=>$validator4->getValue("link_query2"),
			"link_function"=>$validator4->getValue("link_function"),
			"link_path"=>$this->html->link($validator4->getValue("link"))."/".$validator4->getValue("link_function")."/".$validator4->getValue("link_query"),
			"function"=>$validator4->getValue("function"),
			"ajax"=>$validator4->getValue("ajax"),
			"color"=>$validator4->getValue("color"),
			"text"=>$validator4->getValue("text")
		);
		$current_object["params"]=array_merge($current_object["params"],$array_params);
		$validator4->clearValues();
	}else{
		$error=$validator4->getError();
		$data["error"]=$error;
	}
}
if(isset($current_object["params"]["value_type"])){
	$validator4->setValue("value_type",$current_object["params"]["value_type"]);
	$validator4->setValue("link",$current_object["params"]["link"]);
	$validator4->setValue("link_query",$current_object["params"]["link_query"]);
	$validator4->setValue("link_query2",$current_object["params"]["link_query2"]);
	$validator4->setValue("link_function",$current_object["params"]["link_function"]);
	$validator4->setValue("link_path",$current_object["params"]["link_path"]);
	$validator4->setValue("function",$current_object["params"]["function"]);
	$validator4->setValue("ajax",$current_object["params"]["ajax"]);
	$validator4->setValue("color",$current_object["params"]["color"]);
	$validator4->setValue("text",$current_object["params"]["text"]);
}

//print_r($current_object);
$form3 = new form_class("form_template");
$array_input_types=array(
	array("name"=>"Link","id"=>0),
	array("name"=>"OnClick","id"=>1),
  array("name"=>"Ajax","id"=>2),
);
$buton_colors=array(
	array("name"=>"Verde","id"=>0),
	array("name"=>"Rojo","id"=>1),
  array("name"=>"Amarillo","id"=>2),
);
$form3->populate(array(
	"value_type"=>array("as"=>"Tipo de Boton","type"=>"select","select"=>$array_input_types),
	"link"=>array("as"=>"Link","type"=>"text","linkto"=>"value_type","showwhenequal"=>0),
	"link_query"=>array("as"=>"Link Query Params","type"=>"text","linkto"=>"value_type","showwhenequal"=>0),
	"link_query2"=>array("as"=>"Link Query Params (mult)","type"=>"multiple","multiple"=>"text","linkto"=>"value_type","showwhenequal"=>0),
	"link_function"=>array("as"=>"Link","disabled"=>false,"type"=>"text","linkto"=>"value_type","showwhenequal"=>0),
	"link_path"=>array("as"=>"Link","disabled"=>true,"type"=>"text","linkto"=>"value_type","showwhenequal"=>0),
	"function"=>array("as"=>"Funcion","type"=>"cooltextarea","linkto"=>"value_type","showwhenequal"=>1),
  "ajax"=>array("as"=>"Ajax","type"=>"select","select"=>$ajax_objects,"linkto"=>"value_type","showwhenequal"=>2),
	"color"=>array("as"=>"Color","type"=>"select","select"=>$buton_colors),
	"text"=>array("as"=>"Text","type"=>"text"),
),$this->html->link("editor3"));
$form3->setValidator($validator4);
/******************** General Params *************************/
/********** Form Pagina ***********/
$data["editor3_general_template_form"].='<p style="float:left; margin-right:10px;"><button onclick="'.$ajax->generateFunc("'validator'").'" class="btn btn-success">Borrar Datos</button></p>
<div style="clear:both;"></div>';

$data["editor3_general_template_form"].='<div class="widget-head">
				<h5 class="pull-left"> Parametros Boton</h5>
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
$data["editor3_general_template_form"].='<div class="widget-head">
				<h5 class="pull-left"> Codigo Viewer</h5>
				<div class="btn-group pull-right">

				</div>
			</div>';
$data["editor3_general_template_form"].='<div class="code_syn"><pre class="prettyprint linenums">'.renderCodeVButon($current_object).'</pre></div>';
/********* Form Pagina ***********/
/********** Final Things ********/
//$data["editor3_general_template_form"].=$ajax2->createAjaxString();
//$data["editor3_general_template_form"].=$ajax3->createAjaxString();
//$data["editor3_general_template_form"].=$ajax4->createAjaxString();
//$data["editor3_general_template_form"].=$ajax5->createAjaxString();
//$data["editor3_general_template_form"].=$ajax6->createAjaxString();
if(isset($error)&&$error!=""){
	$data["error"]=$error;
}
$data["breadcrumb"]=array("index","editor3",
array("link"=>"editor3","id"=>5),
array("link"=>"editor3","id"=>$_GET["id"]));
/*********** Final Things ********/
?>
