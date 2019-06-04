<?php
$val=0;
global $current_object;
$current_object=&$_SESSION["editor"]["functions"][$_SESSION["editor"]["current_function"]]["objects"][$_SESSION["editor"]["current_object"]];
$this->current_object=$_SESSION["editor"]["functions"][$_SESSION["editor"]["current_function"]]["objects"][$_SESSION["editor"]["current_object"]];
//print_r(array($_SESSION["editor"]["functions"][$_SESSION["editor"]["current_function"]]["objects"],$_SESSION["editor"]["current_object"]));
global $validator;
$validator = new validate();
/******** Borrar Objecto *********/
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
		$current_object=array("tables"=>array(),"cols"=>array(),"cases"=>array(),"params"=>array());
		$data["success"]=0;
		$data["jumpTo"]=$this->html->link("editor3")."/?".http_build_query(array_merge($_GET));
		echo json_encode($data);
		die();
	}else{
		$data["error"]=$validator->getError();
	}
},$filename,$filename);
/********* Borrar Objeto ********/
/************* Borrar Parametro Ajax *************/
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
		$data["jumpTo"]=$this->html->link("editor3")."/?id=".$_GET["id"];
		echo json_encode($data);
		die();
	}else{
		$data["error"]=$validator->getError();
	}
},$filename."3",$filename);
/************* Borrar Parametro Ajax *************/
/***************** Special Cases **********************/
$this->validator4=new validate();
$this->validator4->setPage($filename."4");
if($this->validator4->secureSend()){
	$check=array(
		"rowid"=>array('isset'=>'','notempty'=>'Error al seleccionar el Caso Especial','appendquotes'=>"'"),
		"type"=>array('isset'=>'','notempty'=>'Favor de ingresar un Tipo','appendquotes'=>"'"),
		"link"=>array('default'=>'','notempty'=>'','appendquotes'=>"'"),
		"name"=>array('default'=>'','notempty'=>'','appendquotes'=>"'"),
		"input_name"=>array('default'=>'','notempty'=>'','appendquotes'=>"'"),
		"fake_name"=>array('default'=>'','notempty'=>'','appendquotes'=>"'"),
		"real_value"=>array('default'=>'','notempty'=>'','appendquotes'=>"'"),
	);
	if($this->validator4->validator($check, 'post')){
		$temp="";
		switch($this->validator4->getValue("type")){
			case 1:
				$temp='return $this->table->getLast()->funcGen("link",[
					"link"=>'.$this->validator4->getValue("link").',
					"name"=>'.$this->validator4->getValue("name").'
				]);';
			break;
			case 3:
				$temp='return $this->table->getLast()->funcGen("select",[
					"input_name"=>"'.$this->validator4->getValue("input_name").'",
					"fake_name"=>'.$this->validator4->getValue("fake_name").',
					"real_value"=>'.$this->validator4->getValue("real_value").'
				]);';
			break;
		}
		$current_object["cases"][$this->validator4->getValue("rowid")]=$temp;
	}else{
		$data["error"]=$this->validator4->getError();
	}
}
$this->ajax4 = new ajax_class("ajax_template_self","ajax_template_self");
$this->ajax4->populate(array(
	"idCase",
),function(){
	global $validator4;
	global $validator;
	global $current_object;
	global $html;
	$check=array(
		"idCase"=>array('isset'=>'','notempty'=>'Favor de ingresar un Nombre','appendquotes'=>"'"),
	);
	if($validator->validator($check, 'post')){
		$arraySelect=array(
			array("id"=>1,"name"=>"Link"),
			array("id"=>2,"name"=>"Borrar"),
			array("id"=>3,"name"=>"Seleccionar"),
		);
		$this->validator4->setValue("rowid",$validator->getValue("idCase"));
		$form=new form_class("form_template");
		$form->populate(array(
			"rowid"=>array("as"=>"Row ID","type"=>"hidden"),
			"type"=>array("as"=>"Tipo de Caso","type"=>"select",'select'=>$arraySelect),
			"link"=>array("as"=>"Link","type"=>"text","linkto"=>"type","showwhenequal"=>1),
			"name"=>array("as"=>"Nombre","type"=>"text","linkto"=>"type","showwhenequal"=>1),
			"input_name"=>array("as"=>"Input","type"=>"text","linkto"=>"type","showwhenequal"=>3),
			"fake_name"=>array("as"=>"Nombre Falso","type"=>"text","linkto"=>"type","showwhenequal"=>3),
			"real_value"=>array("as"=>"Valor Real","type"=>"text","linkto"=>"type","showwhenequal"=>3),
		),$this->html->link("editor3"));
		$form->setSendTo($this->html->link("editor3")."/?id=".$_GET["id"]);
		$form->setValidator($this->validator4);
		$data["form"]=$form->createFormString();
		$data["eval"]='$.colorbox({
			width: "630px",
			html:data.form,
		});';
		echo json_encode($data);
		die();
	}else{
		$data["error"]=$validator->getError();
	}
},$filename."4",$filename);
/***************** Special Cases **********************/
//unset($current_object);
/********************* Select Columns *********************************/
$validator->setPage($filename);
if($validator->secureSend()){
	$check=array(
		'param'=>array('isset'=>'', 'notempty'=>'Favor de ingresar un tipo de objecto'),
	);
	if($validator->validator($check, 'post')){
		$currcol_array=array(
			"param"=>$validator->getValue("param"),
		);
		$current_object["cols"][]=$currcol_array;

		$validator->clearValues();
	}else{
		$error=$validator->getError();
		$data["error"]=$error;
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
$searchFull=array(
	array("name"=>"false","id"=>"false"),
	array("name"=>"true","id"=>"true"),
	array("name"=>"searchfull","id"=>"searchfull"),
);
$form1_array=array(
	"param"=>array("as"=>"Parametro","type"=>"text"),
);
$form = new form_class("form_template");
$form->populate($form1_array,$this->html->link("editor3"));
$form->setValidator($validator);
$form2 = new form_class("form_template");
//var_dump($validator3);
/********************** Select Columns ***********************/
//print_r($current_object["cols"]);
/****************** Special Cases ************************/
global $validator3;
$validator3=new validate();
$validator3->setPage($filename."3");
if($validator3->secureSend()){
	$check=array(
		"cases"=>array('isset'=>'','notempty'=>'Favor de ingresar un Nombre','appendquotes'=>"'"),
	);
	if($validator3->validator($check, 'post')){
		//print_r($validator3->getValue("cases"));
		//print_r($current_object["cases"]);
		foreach($validator3->getValue("cases")[0] as $key=>$value){
			$current_object["cases"][$key]=$value;
		}
	}else{
		$data["error"]=$validator3->getError();
	}
}
$array_form=array();
$arraySelect=array(
	array("id"=>"","name"=>""),
);
foreach($current_object["cols"] as $key=>$value){
	if(isset($value["link"])&&$value["link"]=='true'){
		$array_form["cases[0][".$value["mysqlas"]."]"]=array("as"=>'Case "'.$value["mysqlas"].'"',"type"=>"textarea");
		if(isset($current_object["cases"][$value["mysqlas"]])){
			//print_r($current_object["cases"]);
			$validator3->setValue("cases[0][".$value["mysqlas"]."]",$current_object["cases"][$value["mysqlas"]]);
		}
		$array_form["".$value["mysqlas"].""]=array("as"=>'Case "'.$value["mysqlas"].'"',"link"=>true,"type"=>"select","select"=>$arraySelect);
	}
}
$form2->populate($array_form,$this->html->link("editor3"));
$form2->setSendTo($this->html->link("editor3")."/?id=".$_GET["id"]);
$form2->setFunction(function($data,$value,$key){
	return '<button onclick="'.$this->ajax4->generateFunc("'".$key."'").'" class="btn btn-info">Predeterminados</button>';
});
$form2->setValidator($validator3);
/****************** Special Cases ************************/
/********** Configurator ***************/
$data["editor3_general_template_form"].='<p style="float:left; margin-right:10px;"><button onclick="'.$ajax->generateFunc("'table'").'" class="btn btn-success">Borrar Datos</button></p>
<div style="clear:both;"></div>';
/*******************General Params******************/
$validator6=new validate();
$validator6->setPage($filename."6");
if($validator6->secureSend()){
	$check=array(
		'value_type'=>array('default'=>'', 'notempty'=>'Favor de ingresar un tipo de valor'),
	);
	if($validator6->validator($check, 'post')){
		$array_params=array(
			"value_type"=>$validator6->getValue("value_type"),
		);
		$current_object["params"]=array_merge($current_object["params"],$array_params);
		$validator6->clearValues();
	}else{
		$error=$validator6->getError();
		$data["error"]=$error;
	}
}
if(isset($current_object["params"]["value_type"])){
	$validator6->setValue("value_type",$current_object["params"]["value_type"]);
}
//print_r($current_object);
$form3 = new form_class("form_template");
$array_input_types=array(
	array("name"=>"JSON","id"=>0),
	array("name"=>"Modal","id"=>1),
  array("name"=>"Custom","id"=>2),
);
$form3->populate(array(
	"value_type"=>array("as"=>"Tipo de Retorno","type"=>"select","select"=>$array_input_types),
	"code"=>array("as"=>"Codigo","type"=>"cooltextarea")
),$this->html->link("editor3"));
$form3->setValidator($validator6);
/******************** General Params *************************/
/***************** Agregar Parametro *********************/
$data["editor3_general_template_form"].='<div class="widget-head">
				<h5 class="pull-left"> Agregar </h5>
				<div class="btn-group pull-right">

				</div>
			</div>';
$data["editor3_general_template_form"].=$form->createFormString();
/***************** Agregar Parametro *********************/
/*********************** Parametros del Ajax ************************/
$data["editor3_general_template_form"].='<div class="widget-head">
				<h5 class="pull-left"> Parametros del Ajax </h5>
				<div class="btn-group pull-right">

				</div>
			</div>';
$data["editor3_general_template_form"].=renderTable($current_object["cols"]);
/********************* Parametros del Ajax **************************/
/********** Form General Params ***********/
$data["editor3_general_template_form"].='<div class="widget-head">
				<h5 class="pull-left"> Parametros del Ajax</h5>
				<div class="btn-group pull-right">

				</div>
			</div>';
$data["editor3_general_template_form"].=$form3->createFormString();
/********** Form General Params ***********/
/***************** Code Controller *********************/
$data["editor3_general_template_form"].='<div class="widget-head">
				<h5 class="pull-left"> Codigo Controller</h5>
				<div class="btn-group pull-right">

				</div>
			</div>';
$data["editor3_general_template_form"].='<div class="code_syn"><pre class="prettyprint linenums">'.renderCodeCAjax($current_object).'</pre></div>';
/***************** Code Controller *********************/
/***************** Code Viewer *********************/
$data["editor3_general_template_form"].='<div class="widget-head">
				<h5 class="pull-left"> Codigo Viewer</h5>
				<div class="btn-group pull-right">

				</div>
			</div>';
$data["editor3_general_template_form"].='<div class="code_syn"><pre class="prettyprint linenums">'.renderCodeVAjax($current_object).'</pre></div>';
/***************** Code Viewer *********************/
/*********** Render ***********/
if(!empty($current_object["cols"])){
	//unset($current_object);
	$data["result"]="";
	$this->data["result"]="";
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
	if(testexec(renderCodeCTable($current_object).renderCodeVTable($current_object))){
		function excecute($code){
			return @eval('return true;' . $code);
		}
		if(1){
			eval(renderCodeCAjax($current_object));
		}
		if(1){
			eval(renderCodeVAjax($current_object));
		}
	}
	if($error!=""){
		$data["error"]=$error;
	}
	//$data["form"].=$data["search_result"];
	//$data["result"]=$this->data["result"];
	//$data["editor3_general_template_form"].=$data["result"];
	//$data["form"].=$data["pagination_result"];
}
/********** Render **************/

/********** Final Things ********/
//$data["editor3_general_template_form"].=$ajax2->createAjaxString();
$data["editor3_general_template_form"].=$ajax3->createAjaxString();
//$data["editor3_general_template_form"].=$ajax4->createAjaxString();
//$data["editor3_general_template_form"].=$ajax5->createAjaxString();
//$data["editor3_general_template_form"].=$ajax6->createAjaxString();
$data["breadcrumb"]=array("index","editor3",
array("link"=>"editor3","id"=>5),
array("link"=>"editor3","id"=>$_GET["id"]));
/*********** Final Things ********/

?>
