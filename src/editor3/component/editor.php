<?php
$current_object=&$_SESSION["editor"]["functions"][$_SESSION["editor"]["current_function"]]["objects"][$_SESSION["editor"]["current_object"]];
$current_func=&$_SESSION["editor"]["functions"][$_SESSION["editor"]["current_function"]];
//print_r($current_object);

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

global $validator;
$validator->setPage($filename);
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
		$current_object=array("tables"=>array(),"cols"=>array(),"cases"=>array());
		$data["success"]=0;
		$data["jumpTo"]=$html->link("editor3")."/?".http_build_query(array_merge($_GET));
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
	global $current_object;
	global $html;
	$check=array(
		"idRow"=>array('isset'=>'','notempty'=>'Favor de ingresar un Nombre','appendquotes'=>"'"),
	);
	if($validator->validator($check, 'post')){
		unset($_SESSION["editor"]["functions"][$_SESSION["editor"]["current_function"]]["objects"][$_SESSION["editor"]["current_object"]]["cols"][$validator->getValue("idRow")]);
		//unset($current_object["cols"][$validator->getValue("idRow")]);
		$data["success"]=0;
		$data["jumpTo"]=$html->link("editor3")."/?id=2";
		echo json_encode($data);
		die();
	}else{
		$data["error"]=$validator->getError();
	}
},$filename."3",$filename);
include("subcomponents/join_config.php");
include("subcomponents/del_query_option.php");
include("subcomponents/query_options.php");
/***************General Params**********************/
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
$form2 = new form_class("form_template");
$array_input_types=array(
	array("name"=>"false","id"=>"false"),
	array("name"=>"true","id"=>"true"),
);
$form2->populate(array(
	"return_to"=>array("as"=>"Return To","type"=>"text"),
	"send_to"=>array("as"=>"Send To","type"=>"text"),
	"validator"=>array("as"=>"Validator","type"=>"select","select"=>$validators),
),$this->html->link("editor3"));
$form2->setValidator($validator4);
/*******************************/
$data["editor3_general_template_form"].='<p style="float:left; margin-right:10px;"><button onclick="'.$ajax->generateFunc("'edit'").'" class="btn btn-success">Borrar Datos</button></p>
<div style="clear:both;"></div>';
$data["editor3_general_template_form"].='<div class="widget-head">
				<h5 class="pull-left"> Editor</h5>
				<div class="btn-group pull-right">

				</div>
			</div>';
$data["editor3_general_template_form"].='<div class="widget-head">
				<h5 class="pull-left"> Parametros</h5>
				<div class="btn-group pull-right">

				</div>
			</div>';
$data["editor3_general_template_form"].=$form2->createFormString();
$data["editor3_general_template_form"].='<div class="widget-head">
				<h5 class="pull-left"> Configurar Tablas</h5>
				<div class="btn-group pull-right">

				</div>
			</div>';
$result_tables=$this->connection->query("show tables");
$data["editor3_general_template_form"].=renderTableTable($result_tables);
$data["editor3_general_template_form"].='<div class="widget-head">
				<h5 class="pull-left"> Configurar Columnas</h5>
				<div class="btn-group pull-right">

				</div>
			</div>';
if(!empty($current_object["tables"])){
	//print_r($current_object["tables"]);
	$result_tables_cols=array();
	foreach($current_object["tables"] as $key=>$value){
		if(isset($value["querytype"])&&($value["querytype"]!="join")) continue;
		$result_table=$this->connection->query("show columns from ?",$value["name"]);
		foreach($result_table as $key2=>$value2){
			$result_table[$key2]["table"]=$value["name"];
		}
		$result_tables_cols=array_merge($result_tables_cols,$result_table);
	}
	$data["editor3_general_template_form"].=renderTableCol($result_tables_cols);
}
$data["editor3_general_template_form"].='<div class="widget-head">
				<h5 class="pull-left"> Agregar Columnas</h5>
				<div class="btn-group pull-right">

				</div>
			</div>';
$validator->setPage($filename);
if($validator->secureSend()){
	$check=array(
		'col'=>array('isset'=>'', 'notempty'=>'Favor de ingresar un tipo de objecto'),
		'as'=>array('isset'=>'', 'notempty'=>'Favor de ingresar un tipo de objecto'),
		'mysqlas'=>array('isset'=>'', 'notempty'=>'Favor de ingresar un tipo de objecto'),
		'link'=>array('default'=>'false', 'notempty'=>'Favor de ingresar un tipo de objecto'),
		'display'=>array('default'=>'true', 'notempty'=>'Favor de ingresar un tipo de objecto'),
		'disabled'=>array('default'=>'false', 'notempty'=>'Favor de ingresar un tipo de objecto'),
	);
	if($validator->validator($check, 'post')){
		$current_object["cols"][]=array(
			"col"=>$validator->getValue("col"),
			"display"=>$validator->getValue("display"),
			"disabled"=>$validator->getValue("disabled"),
			"link"=>$validator->getValue("link"),
			"as"=>$validator->getValue("as"),
			"mysqlas"=>$validator->getValue("mysqlas"),
		);

		$validator->clearValues();
	}else{
		$error=$validator->getError();
	}
}
$true_false=array(
	array("name"=>"true","id"=>"true"),
	array("name"=>"false","id"=>"false")
);
$false_true=array(
	array("name"=>"false","id"=>"false"),
	array("name"=>"true","id"=>"true")
);
$form = new form_class("form_template");
$form->populate(array(
	"col"=>array("as"=>"Columna","type"=>"text",'isset'=>true),
	"as"=>array("as"=>"Nombre","type"=>"text",'isset'=>true),
	"mysqlas"=>array("as"=>"Alias","type"=>"text",'isset'=>true),
	"link"=>array("as"=>"Link","type"=>"select","select"=>$false_true),
	"display"=>array("as"=>"Desplegar","type"=>"select","select"=>$true_false),
	"disabled"=>array("as"=>"Desabilitar","type"=>"select","select"=>$false_true),
),$this->html->link("editor3"));
$form->setValidator($validator);
$data["editor3_general_template_form"].=$form->createFormString();
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
	if($value["link"]=='true'){
		$array_form["cases[".$value["mysqlas"]."]"]=array("as"=>'Case "'.$value["mysqlas"].'"',"type"=>"textarea");
		if(isset($current_object["cases"][$value["mysqlas"]])){
			//print_r($_SESSION["editor"]["table"]["cases"]);
			$validator3->setValue("cases[".$value["mysqlas"]."]",$current_object["cases"][$value["mysqlas"]]);
		}
	}
}
$form2->populate($array_form,$this->html->link("editor3"),$this->html->link("editor3")."/?id=".$_GET["id"]);
$form2->setValidator($validator3);
$data["editor3_general_template_form"].='<div class="widget-head">
				<h5 class="pull-left"> Componentes Del Query</h5>
				<div class="btn-group pull-right">

				</div>
			</div>';
$data["editor3_general_template_form"].=renderTableQuery($current_object["tables"]);
$data["editor3_general_template_form"].=$form2->createFormString();
$data["editor3_general_template_form"].='<div class="widget-head">
				<h5 class="pull-left"> Componentes</h5>
				<div class="btn-group pull-right">

				</div>
			</div>';
$data["editor3_general_template_form"].=renderTable($current_object["cols"]);
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
$data["editor3_general_template_form"].='<div class="code_syn"><pre class="prettyprint linenums">'.$codigo_controller.renderCodeCEdit($current_object).'</pre></div>';
//$data["form"].=renderCodeTable($_SESSION["editor"]["table"]);
$data["editor3_general_template_form"].='<div class="widget-head">
				<h5 class="pull-left"> Codigo Viewer</h5>
				<div class="btn-group pull-right">

				</div>
			</div>';
$data["editor3_general_template_form"].='<div class="code_syn"><pre class="prettyprint linenums">'.renderCodeVEdit($current_object).'</pre></div>';
$data["editor3_general_template_form"].='<div class="widget-head">
				<h5 class="pull-left"> Vista Previa</h5>
				<div class="btn-group pull-right">

				</div>
			</div>';
$data["editor3_general_template_form"].='<div class="widget-head">
				<h5 class="pull-left"> Editable</h5>
				<div class="btn-group pull-right">
					<li><a href="#" onclick="startEdit(event,\'editor1\');"><span class="color-icons page_white_edit_co"></span></a></li>
				</div>
			</div>';
if(!empty($current_object["cols"])){
	$data["result"]="";
	$this->data["result"]="";
	$edit=new edit_class("edit_template_self");
	function excecute($code){
		return @eval('return true;' . $code);
	}
	if(excecute($codigo_controller)){
		eval($codigo_controller);
	}
	if(excecute(renderCodeCEdit($current_object))){
		eval(renderCodeCEdit($current_object));
	}
	if(excecute(renderCodeVEdit($current_object))){
		eval(renderCodeVEdit($current_object));
	}
	//$data["form"].=$data["search_result"];
	$data["result"]=$this->data["result"];
	$data["editor3_general_template_form"].=$data["result"];
	//$data["form"].=$data["pagination_result"];
}
if(isset($this->data["error"])) $data["error"]=$this->data["error"];
$data["editor3_general_template_form"].=$ajax2->createAjaxString();
$data["editor3_general_template_form"].=$ajax3->createAjaxString();
$data["editor3_general_template_form"].=$ajax5->createAjaxString();
$data["editor3_general_template_form"].=$ajax6->createAjaxString();
$data["breadcrumb"]=array("index","editor3",
array("link"=>"editor3","id"=>5),
array("link"=>"editor3","id"=>$_GET["id"]));
?>
