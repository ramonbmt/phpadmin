<?php
$val=0;
global $current_object;
global $html;
$current_object=&$_SESSION["editor"]["functions"][$_SESSION["editor"]["current_function"]]["objects"][$_SESSION["editor"]["current_object"]];
$this->current_object=$_SESSION["editor"]["functions"][$_SESSION["editor"]["current_function"]]["objects"][$_SESSION["editor"]["current_object"]];
$current_func=&$_SESSION["editor"]["functions"][$_SESSION["editor"]["current_function"]];

$tablas=array(
	array("name"=>"","id"=>"")
);
$i=0;
foreach($current_func["objects"] as $key=>$value){
	if($value["type"]=="tabla"){
		$tablas[]=array("name"=>$i,"id"=>$i);
		$i++;
	}
}
$validators=array(
	array("name"=>"","id"=>"")
);
$i=0;
foreach($current_func["objects"] as $key=>$value){
	if($value["type"]=="validator"){
		$validators[]=array("name"=>$i,"id"=>$i);
		$i++;
	}
}
//print_r(array($_SESSION["editor"]["functions"][$_SESSION["editor"]["current_function"]]["objects"],$_SESSION["editor"]["current_object"]));
global $validator;
$validator->setPage($filename);
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
		$current_object=array("tables"=>array(),"cols"=>array(),"cases"=>array(),"params"=>array());
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
		//print_r($current_object);
		unset($_SESSION["editor"]["functions"][$_SESSION["editor"]["current_function"]]["objects"][$_SESSION["editor"]["current_object"]]["cols"][$validator->getValue("idRow")]);
		//unset($current_object["cols"][$validator->getValue("idRow")]);
		$data["success"]=0;
		$data["jumpTo"]=$this->html->link("editor3")."/?id=1";
		echo json_encode($data);
		die();
	}else{
		$data["error"]=$validator->getError();
	}
},$filename."3",$filename);
$array_select=array(
	array("id"=>0,"name"=>""),
	array("id"=>1,"name"=>"formulario"),
	array("id"=>2,"name"=>"editable"),
	array("id"=>3,"name"=>"tabla"),
);
/*******************General Params******************/
$validator4=new validate();
$validator4->setPage($filename."4");
if($validator4->secureSend()){
	$check=array(
		//'self_insert'=>array('isset'=>'', 'notempty'=>'Favor de ingresar si es Auto Updateable'),
		'return_to'=>array('default'=>'', 'notempty'=>'Favor de ingresar un '),
		'send_to'=>array('default'=>'', 'notempty'=>'Favor de ingresar un tipo de objecto'),
		'validator'=>array('default'=>'', 'notempty'=>'Favor de ingresar un Validator'),
	);
	if($validator4->validator($check, 'post')){
		$array_params=array(
			//"self_insert"=>$validator4->getValue("self_insert"),
			"return_to"=>$validator4->getValue("return_to"),
			"send_to"=>$validator4->getValue("send_to"),
			"validator"=>$validator4->getValue("validator")
		);
		$current_object["params"]=$array_params;
		$validator4->clearValues();
	}else{
		$error=$validator4->getError();
		$data["error"]=$error;
	}
}
if(isset($current_object["params"]["return_to"])){
	//$validator4->setValue("self_insert",$current_object["params"]["self_insert"]);
	$validator4->setValue("return_to",$current_object["params"]["return_to"]);
	$validator4->setValue("send_to",$current_object["params"]["send_to"]);
	$validator4->setValue("validator",$current_object["params"]["validator"]);
}
//print_r($current_object);
$form3 = new form_class("form_template");
$array_input_types=array(
	array("name"=>"false","id"=>"false"),
	array("name"=>"true","id"=>"true"),
);
$form3->populate(array(
	//"self_insert"=>array("as"=>"Self Update","type"=>"select","select"=>$array_input_types),
	"return_to"=>array("as"=>"Return To","type"=>"text"),
	"send_to"=>array("as"=>"Send To","type"=>"text"),
	"validator"=>array("as"=>"Validator","type"=>"select","select"=>$validators),
),$this->html->link("editor3"));
$form3->setValidator($validator4);
/*********************************************/
$validator2=new validate();
$validator2->setPage($filename."2");
if($validator2->secureSend()){
	$check=array(
		'type_object'=>array('isset'=>'', 'notempty'=>'Favor de ingresar un tipo de objecto'),
		'name'=>array('isset'=>'', 'notempty'=>'Favor de ingresar un Nombre'),
		'as'=>array('isset'=>'', 'notempty'=>'Favor de ingresar un Alias'),
		'select'=>array('default'=>'', 'notempty'=>'Favor de ingresar un Arreglo'),
		'table'=>array('default'=>'', 'notempty'=>'Favor de ingresar una Tabla'),
		'multiple1'=>array('default'=>'', 'notempty'=>'Favor de ingresar un tipo de objecto'),
		'multiple2'=>array('default'=>'', 'notempty'=>'Favor de ingresar un tipo de objecto'),
	);
	if($validator2->validator($check, 'post')){
		$array_input_types=array(
			"text",
			"textarea",
			"select",
			"searchselect",
			"multiple",
			"datetime",
			"date",
			"day",
			"cooltextarea",
			"checkbox",
			"radio"
		);
		$array_params=array(
		"type"=>$array_input_types[$validator2->getValue("type_object")],
		"name"=>$validator2->getValue("name"),
		"as"=>$validator2->getValue("as"));
		foreach($validator2->getValue("multiple1") as $key=>$value){
			$array_params[$value]=$validator2->getValue("multiple2")[$key];
		}
		switch($validator2->getValue("type_object")){
			case 3:
				$array_params["searchselect"]='$this->table->objects['.$validator2->getValue("table")."]";
			break;
			case 2:
				$array_params["select"]=$validator2->getValue("select");
			break;
		}
		$current_object["cols"][]=$array_params;

		$validator2->clearValues();
	}else{
		$error=$validator2->getError();
		$data["error"]=$error;
	}
}
$form = new form_class("form_template");
$array_input_types=array(
	array("name"=>"texto","id"=>0),
	array("name"=>"Area de texto","id"=>1),
	array("name"=>"Seleccionar","id"=>2),
	array("name"=>"Busqueda","id"=>3),
	array("name"=>"Multiple","id"=>4),
	array("name"=>"DateTime","id"=>5),
	array("name"=>"Date","id"=>6),
	array("name"=>"Day","id"=>7),
	array("name"=>"CoolTextArea","id"=>8),
	array("name"=>"Checkbox","id"=>9),
	array("name"=>"Radio","id"=>10),
);
$form->populate(array(
	"type_object"=>array("as"=>"Tipo de input","type"=>"select","select"=>$array_input_types),
	"name"=>array("as"=>"Name","type"=>"text"),
	"as"=>array("as"=>"As","type"=>"text"),
	"select"=>array("as"=>"Arreglo Select","type"=>"text","linkto"=>"type_object","showwhenequal"=>2),
	"table"=>array("as"=>"Tabla","type"=>"select","linkto"=>"type_object","showwhenequal"=>3,"select"=>	$tablas),
	"multiple1"=>array("as"=>"Opcion","type"=>"multiple","multiple"=>"text"),
	"multiple2"=>array("as"=>"Opcion","type"=>"multiple","multiple"=>"text"),
),$this->html->link("editor3"));
$form->setValidator($validator2);
//print_r($current_object);
$data["editor3_general_template_form"].='<p style="float:left; margin-right:10px;"><button onclick="'.$ajax->generateFunc("'form'").'" class="btn btn-success">Borrar Datos</button></p>
<div style="clear:both;"></div>';
$data["editor3_general_template_form"].='<div class="widget-head">
				<h5 class="pull-left"> Formulario</h5>
				<div class="btn-group pull-right">

				</div>
			</div>';
$data["editor3_general_template_form"].='<div class="widget-head">
				<h5 class="pull-left"> Parametros</h5>
				<div class="btn-group pull-right">

				</div>
			</div>';
$data["editor3_general_template_form"].=$form3->createFormString();
$data["editor3_general_template_form"].='<div class="widget-head">
				<h5 class="pull-left"> Formulario</h5>
				<div class="btn-group pull-right">

				</div>
			</div>';
$data["editor3_general_template_form"].=$form->createFormString();
$data["editor3_general_template_form"].='<div class="widget-head">
				<h5 class="pull-left"> Componentes</h5>
				<div class="btn-group pull-right">

				</div>
			</div>';
			//print_r($current_object);

$data["editor3_general_template_form"].=renderTable($current_object["cols"]);
$data["editor3_general_template_form"].='<div class="widget-head">
				<h5 class="pull-left"> Casos Especiales</h5>
				<div class="btn-group pull-right">

				</div>
			</div>';
$form2 = new form_class("form_template");
$validator3=new validate();
$validator3->setPage($filename."3");
if($validator3->secureSend()){
	$check=array(
		"cases"=>array('isset'=>'','notempty'=>'Favor de ingresar un Nombre','appendquotes'=>"'"),
	);
	if($validator3->validator($check, 'post')){
		//print_r($validator3->getValue("cases"));
		//print_r($_SESSION["editor"]["table"]["cases"]);
		foreach($validator3->getValue("cases") as $key=>$value){
			$current_object["cases"][$key]=$value;
		}
	}else{
		$data["error"]=$validator3->getError();
	}
}
$array_form=array();
foreach($current_object["cols"] as $key=>$value){
	if(isset($value["link"])&&$value["link"]=='true'){
		$array_form["cases[".$value["mysqlas"]."]"]=array("as"=>'Case "'.$value["mysqlas"].'"',"type"=>"textarea");
		if(isset($current_object["cases"][$value["mysqlas"]])){
			//print_r($_SESSION["editor"]["table"]["cases"]);
			$validator3->setValue("cases[".$value["mysqlas"]."]",$current_object["cases"][$value["mysqlas"]]);
		}
	}
}
$form2->populate($array_form,$this->html->link("editor3"));
$form2->setSendTo($this->html->link("editor3")."/?id=".$_GET["id"]);
$form2->setValidator($validator3);
$data["editor3_general_template_form"].=$form2->createFormString();
$data["editor3_general_template_form"].='<div class="widget-head">
				<h5 class="pull-left"> Codigo Controller</h5>
				<div class="btn-group pull-right">

				</div>
			</div>';
$codigo_controller="";
foreach($current_func["objects"] as $key=>$value){
	$array_components[]=array("name"=>$value["type"],"id"=>$key);
	switch($value["type"]){
		case "tabla":
			$codigo_controller.=renderCodeCTable($value);
		break;
		case "validator":
			$codigo_controller.=renderCodeCValidator($value);
		break;
	}
}
$data["editor3_general_template_form"].='<div class="code_syn"><pre class="prettyprint linenums">'.$codigo_controller.renderCodeCForm($current_object).'</pre></div>';
//$data["form"].=renderCodeTable($_SESSION["editor"]["table"]);
$data["editor3_general_template_form"].='<div class="widget-head">
				<h5 class="pull-left"> Codigo Viewer</h5>
				<div class="btn-group pull-right">

				</div>
			</div>';
$data["editor3_general_template_form"].='<div class="code_syn"><pre class="prettyprint linenums">'.renderCodeVForm($current_object).'</pre></div>';
$data["editor3_general_template_form"].='<div class="widget-head">
				<h5 class="pull-left"> Vista Previa</h5>
				<div class="btn-group pull-right">

				</div>
			</div>';

if(!empty($current_object["cols"])){
	$data["result"]="";
	$this->data["result"]="";
	$form=new form_class("form_template_self");
	global $error;
	function testexec($code){
		global $error;
		ob_start();
		eval('return true;' . $code);
		echo(error_get_last());
		if ('' !== $error = ob_get_clean()) {
				return false;
		}
		return true;
	}
	if(testexec($codigo_controller.renderCodeCForm($current_object).renderCodeVForm($current_object))){
		function excecute($code){
			return @eval('return true;' . $code);
		}
		if(1){
			eval($codigo_controller);
		}
		if(1){
			eval(renderCodeCForm($current_object));
		}
		if(1){
			eval(renderCodeVForm($current_object));
		}
	}
	if($error!=""){
		$data["error"]=$error;
	}
	//$data["form"].=$data["search_result"];
	$data["result"]=$this->data["result"];
	$data["editor3_general_template_form"].=$data["result"];
	//$data["form"].=$data["pagination_result"];
}
$data["editor3_general_template_form"].=$ajax3->createAjaxString();
$data["breadcrumb"]=array("index","editor3",
array("link"=>"editor3","id"=>5),
array("link"=>"editor3","id"=>$_GET["id"]));
?>
