<?php
global $current_func;
$current_func=&$_SESSION["editor"]["functions"][$_SESSION["editor"]["current_function"]];
$this->current_func=$current_func;
//print_r($current_func);

//Acomodar objetos
if(isset($_POST["type"])&&$_POST["type"]=="updateListPosition"&&isset($_POST["ajax"])&&$_POST["ajax"]==1){
	$temp=$current_func["objects"];
	foreach($current_func["objects"] as $key=>$value){
		//echo $key."-".$_POST["table_item"][$key]."\n";
		if(isset($_POST["table_item"][$key])&&$key!=$_POST["table_item"][$key]){
			$current_func["objects"][$key]=$temp[$_POST["table_item"][$key]];
		}
	}

	die();
}

$validator = new validate();
$ajax = new ajax_class("ajax_template_self","ajax_template_self");
$ajax->populate(array(
	"idSession",
),function(){
	global $validator;
	global $html;
	global $current_func;
	$check=array(
		"idSession"=>array('isset'=>'','notempty'=>'Favor de ingresar un Nombre','appendquotes'=>"'"),
	);
	if($validator->validator($check, 'post')){
		$_SESSION["editor"]["functions"][$_SESSION["editor"]["current_function"]]=array("name"=>$this->current_func["name"],"id"=>$this->current_func["id"]);
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
	global $current_func;
	$check=array(
		"idRow"=>array('isset'=>'','notempty'=>'Favor de ingresar un Nombre','appendquotes'=>"'"),
	);
	if($validator->validator($check, 'post')){
		unset($_SESSION["editor"]["functions"][$_SESSION["editor"]["current_function"]]["objects"][$validator->getValue("idRow")]);
		//unset($_SESSION["editor"]["table"]["cols"][$validator->getValue("idRow")]);
		//unset($this->$current_func["objects"][$validator->getValue("idRow")]);
		$data["success"]=0;
		$data["jumpTo"]=$this->html->link("editor3")."/?id=5";
		echo json_encode($data);
		die();
	}else{
		$data["error"]=$validator->getError();
	}
},$filename."3",$filename);
global $ajax4;
$ajax4 = new ajax_class("ajax_template_self","ajax_template_self");
$ajax4->populate(array(
	"idRow",
),function(){
	global $validator;
	global $html;
	global $current_func;
	$check=array(
		"idRow"=>array('isset'=>'','notempty'=>'Favor de ingresar un Nombre','appendquotes'=>"'"),
	);
	if($validator->validator($check, 'post')){
		$_SESSION["editor"]["current_object"]=$validator->getValue("idRow");
		switch($this->current_func["objects"][$validator->getValue("idRow")]["type"]){
			case "tabla":
				$data["jumpTo"]=$this->html->link("editor3")."/?id=3";
			break;
			case "editable":
				$data["jumpTo"]=$this->html->link("editor3")."/?id=2";
			break;
			case "formulario":
				$data["jumpTo"]=$this->html->link("editor3")."/?id=1";
			break;
			case "validator":
				$data["jumpTo"]=$this->html->link("editor3")."/?id=4";
			break;
			case "ajax":
				$data["jumpTo"]=$this->html->link("editor3")."/?id=6";
			break;
			case "boton":
				$data["jumpTo"]=$this->html->link("editor3")."/?id=7";
			break;
			case "query":
				$data["jumpTo"]=$this->html->link("editor3")."/?id=8";
			break;
			case "variable":
				$data["jumpTo"]=$this->html->link("editor3")."/?id=9";
			break;
		}
		$data["success"]=0;
		echo json_encode($data);
		die();
	}else{
		$data["error"]=$validator->getError();
	}
},$filename."4",$filename);
global $ajax5;
$ajax5 = new ajax_class("ajax_template_self","ajax_template_self");
$ajax5->populate(array(
	"idRow",
),function(){
	global $validator;
	global $html;
	global $current_func;
	$check=array(
		"idRow"=>array('isset'=>'','notempty'=>'Favor de ingresar un Nombre','appendquotes'=>"'"),
	);
	if($validator->validator($check, 'post')){
		if($this->current_func["objects"][$validator->getValue("idRow")]["tabable"]){
			$_SESSION["editor"]["functions"][$_SESSION["editor"]["current_function"]]["objects"][$validator->getValue("idRow")]["tabable"]=false;
			//$this->current_func["objects"][$validator->getValue("idRow")]["tabable"]=false;
		}else{
			$_SESSION["editor"]["functions"][$_SESSION["editor"]["current_function"]]["objects"][$validator->getValue("idRow")]["tabable"]=true;
			//$this->current_func["objects"][$validator->getValue("idRow")]["tabable"]=true;
		}
		$data["jumpTo"]=$this->html->link("editor3")."/?id=5";
		$data["success"]=0;

		echo json_encode($data);
		die();
	}else{
		$data["error"]=$validator->getError();
	}
},$filename."5",$filename);
global $validator2;
$validator2 = new validate();
$validator2->setPage($filename);
if($validator2->secureSend()){
	$check=array(
		'rowid'=>array('isset'=>'', 'notempty'=>'Error, no se envío el valor del rowid'),
		'variable'=>array('default'=>'', 'notempty'=>'Favor de ingresar un nombre de variable'),
	);
	if($validator2->validator($check, 'post')){
		$_SESSION["editor"]["functions"][$_SESSION["editor"]["current_function"]]["objects"][$validator2->getValue("rowid")]["variable"]=$validator2->getValue("variable");
	}else{
		$data["error"]=$validator2->getError();
	}
}
global $ajax6;
$ajax6 = new ajax_class("ajax_template_self","ajax_template_self");
$ajax6->populate(array(
	"rowid",
),function(){
	global $validator;
	global $html;
	global $current_func;
	global $validator2;
	$check=array(
		"rowid"=>array('isset'=>'','notempty'=>'Error, no se envío el valor de rowid','appendquotes'=>"'"),
	);
	if($validator->validator($check, 'post')){
		$validator2->setValue("rowid",$validator->getValue("rowid"));
		$validator2->setValue("variable",$this->current_func["objects"][$validator->getValue("rowid")]["variable"]);
		$form=new form_class("form_template");
		$form->populate(array(
			"rowid"=>array("as"=>"Row ID","type"=>"hidden"),
			"variable"=>array("as"=>"Variable","type"=>"text"),
		),$this->html->link("editor3"));
		$form->setSendTo($this->html->link("editor3")."/?id=".$_GET["id"]);
		$form->setValidator($validator2);
		$data["form"]=$form->createFormString();
		$data["success"]=0;
		$data["eval"]='$.colorbox({
			width: "630px",
			html:data.form,
		});';
		echo json_encode($data);
		die();
	}else{
		$data["succes"]=0;
		$data["error"]=$validator->getError();
		echo json_encode($data);
		die();
	}
},$filename."6",$filename);
/*******************General Params******************/
$validator4=new validate();
$validator4->setPage($filename."4");
if($validator4->secureSend()){
	$check=array(
		'template'=>array('isset'=>'', 'notempty'=>'Favor de ingresar un Template'),
	);
	if($validator4->validator($check, 'post')){
		$array_params=array(
			"template"=>$validator4->getValue("template"),
		);
		$current_func["params"]=$array_params;
		$validator4->clearValues();
	}else{
		$error=$validator4->getError();
		$data["error"]=$error;
	}
}
if(isset($current_func["params"]["template"])){
	$validator4->setValue("template",$current_func["params"]["template"]);
}
//print_r($current_object);
$form3 = new form_class("form_template");
$array_input_types=array(
	array("name"=>"false","id"=>"false"),
	array("name"=>"true","id"=>"true"),
);
$form3->populate(array(
	"template"=>array("as"=>"Template","type"=>"text"),
),$this->html->link("editor3"));
$form3->setValidator($validator4);
/*********************************************/
$array_select=array(
	array("id"=>0,"name"=>""),
	array("id"=>1,"name"=>"formulario"),
	array("id"=>2,"name"=>"editable"),
	array("id"=>3,"name"=>"tabla"),
	array("id"=>4,"name"=>"validator"),
	array("id"=>6,"name"=>"Ajax"),
	array("id"=>7,"name"=>"Boton"),
	array("id"=>8,"name"=>"Query"),
	array("id"=>9,"name"=>"Variable"),
);
$form = new form_class("form_template");
$form->populate(array(
	"type_object"=>array("as"=>"Tipo de Objeto","type"=>"select","select"=>$array_select),
),$this->html->link("editor3"));
$validator->setPage($filename);
if($validator->secureSend()){
	$check=array(
		'type_object'=>array('isset'=>'', 'notempty'=>'Favor de ingresar un tipo de objecto'),
	);
	if($validator->validator($check, 'post')){
		switch($validator->getValue("type_object")){
			case 1:
				$i=0;
				foreach($current_func["objects"] as $key=>$value){
					if($value["type"]=="formulario")$i++;
				}
				$current_func["objects"][]=array("type"=>"formulario","tables"=>array(),"cols"=>array(),"cases"=>array(),
				"tabable"=>false,"variable"=>"","params"=>array());
				$current_func["objects"][max(array_keys($current_func["objects"]))]["id"]=$i;
				$_SESSION["editor"]["current_object"]=max(array_keys($current_func["objects"]));
			break;
			case 2:
				$i=0;
				foreach($current_func["objects"] as $key=>$value){
					if($value["type"]=="editable")$i++;
				}
				$current_func["objects"][]=array("type"=>"editable","tables"=>array(),"cols"=>array(),"cases"=>array(),
				"tabable"=>false,"variable"=>"","params"=>array());
				$current_func["objects"][max(array_keys($current_func["objects"]))]["id"]=$i;
				$_SESSION["editor"]["current_object"]=max(array_keys($current_func["objects"]));
			break;
			case 3:
				$i=0;
				foreach($current_func["objects"] as $key=>$value){
					if($value["type"]=="tabla")$i++;
				}
				$current_func["objects"][]=array("type"=>"tabla","tables"=>array(),"cols"=>array(),"cases"=>array(),
				"tabable"=>false,"variable"=>"","params"=>array());
				$current_func["objects"][max(array_keys($current_func["objects"]))]["id"]=$i;
				$_SESSION["editor"]["current_object"]=max(array_keys($current_func["objects"]));
			break;
			case 4:
				$i=0;
				foreach($current_func["objects"] as $key=>$value){
					if($value["type"]=="validator")$i++;
				}
				$current_func["objects"][]=array("type"=>"validator","tables"=>array(),"cols"=>array(),"cases"=>array(),
				"tabable"=>false,"variable"=>"","params"=>array());
				$current_func["objects"][max(array_keys($current_func["objects"]))]["id"]=$i;
				$_SESSION["editor"]["current_object"]=max(array_keys($current_func["objects"]));
			break;
			case 6:
				$i=0;
				foreach($current_func["objects"] as $key=>$value){
					if($value["type"]=="ajax")$i++;
				}
				$current_func["objects"][]=array("type"=>"ajax","tables"=>array(),"cols"=>array(),"cases"=>array(),
				"tabable"=>false,"variable"=>"","params"=>array());
				$current_func["objects"][max(array_keys($current_func["objects"]))]["id"]=$i;
				$_SESSION["editor"]["current_object"]=max(array_keys($current_func["objects"]));
			break;
			case 7:
				$i=0;
				foreach($current_func["objects"] as $key=>$value){
					if($value["type"]=="boton")$i++;
				}
				$current_func["objects"][]=array("type"=>"boton","tables"=>array(),"cols"=>array(),"cases"=>array(),
				"tabable"=>false,"variable"=>"","params"=>array());
				$current_func["objects"][max(array_keys($current_func["objects"]))]["id"]=$i;
				$_SESSION["editor"]["current_object"]=max(array_keys($current_func["objects"]));
			break;
			case 8:
				$i=0;
				foreach($current_func["objects"] as $key=>$value){
					if($value["type"]=="query")$i++;
				}
				$current_func["objects"][]=array("type"=>"query","tables"=>array(),"cols"=>array(),"cases"=>array(),
				"tabable"=>false,"variable"=>"","params"=>array());
				$current_func["objects"][max(array_keys($current_func["objects"]))]["id"]=$i;
				$_SESSION["editor"]["current_object"]=max(array_keys($current_func["objects"]));
			break;
			case 9:
				$i=0;
				foreach($current_func["objects"] as $key=>$value){
					if($value["type"]=="varible")$i++;
				}
				$current_func["objects"][]=array("type"=>"variable","tables"=>array(),"cols"=>array(),"cases"=>array(),
				"tabable"=>false,"variable"=>"","params"=>array());
				$current_func["objects"][max(array_keys($current_func["objects"]))]["id"]=$i;
				$_SESSION["editor"]["current_object"]=max(array_keys($current_func["objects"]));
			break;
		}
		header("Location: ".$this->html->link("editor3")."/?id=".$validator->getValue("type_object"));
		die();
	}else{
		$error=$validator->getError();
	}
}
$data["editor3_general_template_form"]='<div class="widget-head">
				<h5 class="pull-left">'.$_SESSION["editor"]["functions"][$_SESSION["editor"]["current_function"]]["name"].'</h5>
				<div class="btn-group pull-right">

				</div>
			</div>';
$data["editor3_general_template_form"].='<p style="float:left; margin-right:10px;"><button onclick="'.$ajax3->generateFunc("'table'").'" class="btn btn-success">Borrar Datos</button></p>
<div style="clear:both;"></div>';
$data["editor3_general_template_form"].='<div class="widget-head">
				<h5 class="pull-left"> Parametros</h5>
				<div class="btn-group pull-right">

				</div>
			</div>';
$data["editor3_general_template_form"].=$form3->createFormString();
$data["editor3_general_template_form"].="<div style='height:10px;'></div>";
$data["editor3_general_template_form"].=$form->createFormString(function(){
},$validator);
$data["editor3_general_template_form"].='<div class="widget-head">
				<h5 class="pull-left">Componentes</h5>
				<div class="btn-group pull-right">

				</div>
			</div>';
//print_r($current_func["objects"]);
if(isset($current_func["objects"])&&!empty($current_func["objects"])){
	$array_components=array();
	foreach($current_func["objects"] as $key=>$value){
		$array_components[]=array("name"=>$value["type"],"id"=>$key, "tabable"=>$value["tabable"],"variable"=>$value["variable"]);
	}
	//print_r($_SESSION["editor"][$_SESSION["editor"]["current_function"]]);
	$data["editor3_general_template_form"].=renderTableObjects($array_components);
}
$data["editor3_general_template_form"].='<div class="widget-head">
				<h5 class="pull-left">Vista Previa</h5>
				<div class="btn-group pull-right">

				</div>
			</div>';
if(isset($current_func["objects"])&&!empty($current_func["objects"])){
	$codigo_controller="";
	$codigo_viewer="";
	foreach($current_func["objects"] as $key=>$value){
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
	$tbol=false;
	foreach($current_func["objects"] as $key=>$value){
		if($value["tabable"]){
			$tbol=true;
		}
	}
	if($tbol){
	$codigo_viewer.='
$data["result"].=$this->tabbs->constructString();';
	}

	if(!empty($current_func["objects"])){
		//$codigo_viewer.=renderCodeVAll();
	}
	$data["result"]="";
	$this->data["result"]="";
	$data["editor3_general_template_form"].='<div class="code_syn"><pre class="prettyprint linenums">'.$codigo_controller.'</pre></div>';
	$data["editor3_general_template_form"].='<div class="code_syn"><pre class="prettyprint linenums">'.$codigo_viewer.'</pre></div>';
	$table=new table_class("table_template_self");
	$edit=new edit_class("edit_template_self");
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
	if(testexec($codigo_controller.$codigo_viewer)){
		if(@eval('return true;' . $codigo_controller.$codigo_viewer)){
			eval($codigo_controller.$codigo_viewer);
		}
	}
	if($error!=""){
		$data["error"]=$error;
	}
	//eval($codigo_controller.$codigo_viewer);
	$data["result"]=$this->data["result"];
	$data["editor3_general_template_form"].=$data["result"];
}
$data["editor3_general_template_form"].=$ajax4->createAjaxString();
$data["editor3_general_template_form"].=$ajax3->createAjaxString();
$data["editor3_general_template_form"].=$ajax5->createAjaxString();
$data["editor3_general_template_form"].=$ajax6->createAjaxString();
$data["editor3_general_template_form"].="<script>
//$('#componentes > tbody').ready($('#componentes > tbody').sortable());
//$('#componentes > tbody').on('sortbeforestop', function( event, ui ) { console.log('se cambio'); console.log(ui);} );
$('#componentes > tbody').sortable({
			opacity: 0.6,
			cursor: 'move',
			update: function() {
				var order = $(this).sortable('serialize') + '&type=updateListPosition&ajax=1';
				$.post('', order);
			}
		});
</script>";
$this->data["breadcrumb"]=array("index","editor3",
array("link"=>"editor3","id"=>5));
?>
