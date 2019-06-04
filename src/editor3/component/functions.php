<?php
$current=&$_SESSION["editor"]["functions"];
$editor=&$_SESSION["editor"];
//print_r($editor);
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
		$_SESSION["editor"]["functions"]=array();
		$_SESSION["editor"]["current_function"]=null;
		$_SESSION["editor"]["next_func"]=0;
		$_SESSION["editor"]["current_object"]=null;
		$_SESSION["editor"]["next_object"]=0;
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
$ajax3->next($filename);
$ajax3->populate(array(
	"idRow",
),function(){
	global $validator;
	global $html;
	global $current_func;
	$check=array(
		"idRow"=>array('isset'=>'','notempty'=>'Favor de ingresar un Nombre','appendquotes'=>"'"),
	);
	if($validator->validator($check, 'post')){
		unset($_SESSION["editor"]);
		//unset($this->current_func["objects"][$validator->getValue("idRow")]);
		$data["success"]=0;
		$data["jumpTo"]=$this->html->link("editor3");
		echo json_encode($data);
		die();
	}else{
		$data["error"]=$validator->getError();
	}
},$filename."3",$filename);
//print_r($current);
global $ajax4;
$ajax4 = new ajax_class("ajax_template_self","ajax_template_self");
/*******************General Params******************/
$validator4=new validate();
$validator4->setPage($filename."4");
if($validator4->secureSend()){
	$check=array(
		'filename'=>array('default'=>'', 'notempty'=>'Favor de ingresar un Nombre de Archivo'),
		'pages_name'=>array('default'=>'', 'notempty'=>'Favor de ingresar un Nombre para Pages'),
	);
	if($validator4->validator($check, 'post')){
		$array_params=array(
			"filename"=>$validator4->getValue("filename"),
			"pages"=>$validator4->getValue("pages_name")
		);
		$editor["params"]=$array_params;
		$validator4->clearValues();
	}else{
		$error=$validator4->getError();
		$data["error"]=$error;
	}
}
if(isset($editor["params"]["filename"])){
	$validator4->setValue("filename",$editor["params"]["filename"]);
}
if(isset($editor["params"]["pages"])){
	$validator4->setValue("pages_name",$editor["params"]["pages"]);
}
//print_r($current_object);
$form3 = new form_class("form_template");
$array_input_types=array(
	array("name"=>"false","id"=>"false"),
	array("name"=>"true","id"=>"true"),
);
$form3->populate(array(
	"filename"=>array("as"=>"Filename","type"=>"text"),
	"pages_name"=>array("as"=>"Pages","type"=>"text"),
),$this->html->link("editor3"));
$form3->setValidator($validator4);
/*********************************************/
/******************* Generate Files ********************/
global $codigo_controller;
global $codigo_viewer;
$codigo_controller="";
$codigo_viewer="";
if(isset($current[0]["objects"])){
	$codigo_controller.='
	class controller_view_class extends controller_class {';
	$codigo_viewer.='
	class view extends view_class {';
	foreach($current as $key_top=>$value_top){
		if(!isset($value_top["params"]['template'])) $value_top["params"]['template']="";
		$codigo_viewer.='
		function '.$value_top['name'].'(){
			$filename=basename(__FILE__, ".php");
			$this->filename=$filename;
			';
		$codigo_controller.='
		function '.$value_top['name'].'(){
			$filename=basename(__FILE__, ".php");
			$this->filename=$filename;
			';
		foreach($value_top["objects"] as $key=>$value){
			$array_components[]=array("name"=>$value["type"],"id"=>$key);
			switch($value["type"]){
				case "tabla":
					$codigo_controller.=renderCodeCTable($value);
					$codigo_viewer.=renderCodeVTable($value);
				break;
				case "editable":
					$codigo_controller.=renderCodeCEdit($value);
					$codigo_viewer.=renderCodeVEdit($value);
				break;
				case "formulario":
					$codigo_controller.=renderCodeCForm($value);
					$codigo_viewer.=renderCodeVForm($value);
				break;
				case "validator":
					$codigo_controller.=renderCodeCValidator($value);
				break;
				case "variable":
					$codigo_controller.=renderCodeCVariable($value);
				break;
			}
		}
		$codigo_viewer.='
			$this->template="'.$value_top["params"]['template'].'";
			echo $this->render();
		}
	';
		$codigo_controller.='
			$this->view=$this->data;
		}
	';
	}
	$codigo_viewer.='
	}
	$view = new view($registry);
	if($action!="" && $action!="?") {
		if(is_callable(array($view, $action))) {
			call_user_func_array(array($view, $action), array());
		}
	} else {
		$view->index();
	}
	';
	$codigo_controller.='
	}
	$controller = new controller_view_class($registry);
	if($action!="" && is_callable(array($controller, $action))) {
		call_user_func_array(array($controller, $action), array());
	} else {
		$controller->index();
	}
	';
}
/**********************************************/
/**************** Save File *******************/
$ajax4->populate(array(
	"idRow",
),function(){
	global $validator;
	global $html;
	global $codigo_controller;
	global $codigo_viewer;
	$check=array(
		"idRow"=>array('isset'=>'','notempty'=>'Favor de ingresar un Nombre','appendquotes'=>"'"),
	);
	if($validator->validator($check, 'post')){
		$editor=&$_SESSION["editor"];
		//write Pages
		include("include/pages.php");
		$key = array_search($editor["params"]["filename"].".php", $pages);
		if($key){
			unset($pages[$key]);
		}
		$pages[$editor["params"]["pages"]]=$editor["params"]["filename"].".php";
		$pages_array = var_export($pages,true);
		$pages_array='<?php $pages = '.$pages_array.";";
		$pagesFile = fopen("include/pages.php", "w");
		fwrite($pagesFile, $pages_array);
		fclose($pagesFile);

		$configFile = fopen("app/configurator/".$editor["params"]["filename"].".php", "w");
		fwrite($configFile, serialize($editor));
		fclose($configFile);
		$controllerFile = fopen("app/controller/".$editor["params"]["filename"].".php", "w");
		fwrite($controllerFile, '<?php '.$codigo_controller.'?>');
		fclose($controllerFile);
		$viewFile = fopen("app/view/".$editor["params"]["filename"].".php", "w");
		fwrite($viewFile, '<?php '.$codigo_viewer.'?>');
		fclose($viewFile);
		//unset($_SESSION["editor"]["table"]["cols"][$validator->getValue("idRow")]);
		//unset($this->current_func["objects"][$validator->getValue("idRow")]);
		$data["success"]=0;
		//$data["jumpTo"]=$this->html->link("editor3");
		$data["eval"]="alert('Se guardo Exitosamente');";
		echo json_encode($data);
		die();
	}else{
		$data["error"]=$validator->getError();
	}
},$filename."4",$filename);
/**********************************************/
/**************** Save File *******************/
global $ajax5;
$ajax5 = new ajax_class("ajax_template_self","ajax_template_self");
$ajax5->populate(array(
	"idRow",
),function(){
	global $validator;
	global $html;
	global $codigo_controller;
	global $codigo_viewer;
	$check=array(
		"idRow"=>array('isset'=>'','notempty'=>'Favor de ingresar un Nombre','appendquotes'=>"'"),
	);
	if($validator->validator($check, 'post')){
		$editor=&$_SESSION["editor"];
		$file = file_get_contents("app/configurator/".$editor["params"]["filename"].".php", true);
		$editor=unserialize($file);
		//unset($_SESSION["editor"]["table"]["cols"][$validator->getValue("idRow")]);
		//unset($this->current_func["objects"][$validator->getValue("idRow")]);
		$data["success"]=0;
		$data["jumpTo"]=$this->html->link("editor3");
		$data["eval"]="alert('Se leyo Exitosamente');";
		echo json_encode($data);
		die();
	}else{
		$data["error"]=$validator->getError();
	}
},$filename."5",$filename);
/**********************************************/
$data["editor3_general_template_form"].='<p style="float:left; margin-right:10px;"><button onclick="'.$ajax3->generateFunc("'table'").'" class="btn btn-success">Borrar Datos</button></p>';
$data["editor3_general_template_form"].='<p style="float:right; margin-right:10px;"><button onclick="'.$ajax4->generateFunc("'table'").'" class="btn btn-success">Guardar</button></p>';
$data["editor3_general_template_form"].='<p style="float:right; margin-right:10px;"><button onclick="'.$ajax5->generateFunc("'table'").'" class="btn btn-success">Leer</button></p>
<div style="clear:both;"></div>';
$data["editor3_general_template_form"].='<div class="widget-head">
				<h5 class="pull-left"> Parametros</h5>
				<div class="btn-group pull-right">

				</div>
			</div>';
$data["editor3_general_template_form"].=$form3->createFormString();
$data["editor3_general_template_form"].="<div style='height:10px;'></div>";
$array_select=array(
	array("id"=>0,"name"=>""),
	array("id"=>1,"name"=>"formulario"),
	array("id"=>2,"name"=>"editable"),
	array("id"=>3,"name"=>"tabla"),
	array("id"=>4,"name"=>"validator"),
);
//Seleccionar funcion
$form = new form_class("form_template");
$form->populate(array(
	"sel_function"=>array("as"=>"Seleccionar Funcion","type"=>"select","select"=>$_SESSION["editor"]["functions"]),
),$this->html->link("editor3"));
$validator = new validate();
$validator->setPage($filename);
if($validator->secureSend()){
	$check=array(
		'sel_function'=>array('isset'=>'', 'notempty'=>'Favor de seleccionar una funcion'),
	);
	if($validator->validator($check, 'post')){
		$_SESSION["editor"]["current_function"]=$validator->getValue("sel_function");
		header("Location: ".$this->html->link("editor3")."/?id=5");
		die();
	}else{
		$error=$validator->getError();
	}
}
$data["editor3_general_template_form"].=$form->createFormString(function(){
},$validator);
$data["editor3_general_template_form"].="<div style='height:10px;'></div>";
//Agregar nueva funcion
$form2 = new form_class("form_template");
$form2->populate(array(
	"add_func"=>array("as"=>"Agregar Funcion","type"=>"text"),
),$this->html->link("editor3"));
$validator2 = new validate();
$validator2->setPage($filename."2");
if($validator2->secureSend()){
	$check=array(
		'add_func'=>array('isset'=>'', 'notempty'=>'Favor de ingresar una nueva funcion'),
	);
	if($validator2->validator($check, 'post')){
		$_SESSION["editor"]["functions"][]=array("name"=>$validator2->getValue("add_func"),"id"=>$_SESSION["editor"]["next_func"]++,"objects"=>array());
		header("Location: ".$this->html->link("editor3")."/?id=".$validator2->getValue("type_object"));
		die();
	}else{
		$error=$validator2->getError();
	}
}
$data["editor3_general_template_form"].=$form2->createFormString(function(){
},$validator2);
//print_r($current);

$data["editor3_general_template_form"].='<div class="widget-head">
				<h5 class="pull-left">Codigo Controller</h5>
				<div class="btn-group pull-right">

				</div>
			</div>';
$data["editor3_general_template_form"].='<div class="code_syn"><pre class="prettyprint linenums">'.$codigo_controller.'</pre></div>';
$data["editor3_general_template_form"].='<div class="widget-head">
				<h5 class="pull-left">Codigo Viewer</h5>
				<div class="btn-group pull-right">

				</div>
			</div>';
$data["editor3_general_template_form"].='<div class="code_syn"><pre class="prettyprint linenums">'.$codigo_viewer.'</pre></div>';
$data["editor3_general_template_form"].=$ajax3->createAjaxString();
$data["editor3_general_template_form"].=$ajax4->createAjaxString();
$data["editor3_general_template_form"].=$ajax5->createAjaxString();
$data["breadcrumb"]=array("index","editor3",
array("link"=>"editor3","id"=>0));
?>
