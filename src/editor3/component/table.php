<?php
$val=0;
global $current_object;
$current_object=&$_SESSION["editor"]["functions"][$_SESSION["editor"]["current_function"]]["objects"][$_SESSION["editor"]["current_object"]];
$this->current_object=$_SESSION["editor"]["functions"][$_SESSION["editor"]["current_function"]]["objects"][$_SESSION["editor"]["current_object"]];
//print_r(array($_SESSION["editor"]["functions"][$_SESSION["editor"]["current_function"]]["objects"],$_SESSION["editor"]["current_object"]));
global $validator;
$validator = new validate();
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
/******************* JOIN CONFIG **************/
global $validator2;
$validator2=new validate();
$validator2->setPage($filename."2");
if($validator2->secureSend()){
	$check=array(
		"jointype"=>array('isset'=>'','notempty'=>'Favor de ingresar un tipo de join','appendquotes'=>"'"),
		"jointable"=>array('isset'=>'','notempty'=>'Favor de ingresar un Nombre','appendquotes'=>"'"),
		"joinwith"=>array('isset'=>'','notempty'=>'Favor de ingresar una Tabla para relacionarlo','appendquotes'=>"'"),
		"col1"=>array('isset'=>'','notempty'=>'Favor de ingresar un Nombre','appendquotes'=>"'"),
		"col2"=>array('isset'=>'','notempty'=>'Favor de ingresar un Nombre','appendquotes'=>"'"),
	);
	if($validator2->validator($check, 'post')){
		$current_object=&$_SESSION["editor"]["functions"][$_SESSION["editor"]["current_function"]]["objects"][$_SESSION["editor"]["current_object"]];
		//print_r($validator2->getValue("col1")[$validator2->getValue("joinwith")]);
		$current_object["tables"][]=array(
			"querytype"=>"join",
			"jointype"=>$validator2->getValue("jointype"),
			"joinwith"=>$validator2->getValue("joinwith"),
			"name"=>$validator2->getValue("jointable"),
			"col1"=>$validator2->getValue("col1")[$validator2->getValue("joinwith")],
			"col2"=>$validator2->getValue("col2"),
		);
	}else{
		$data["error"]=$validator->getError();
	}
}
global $ajax2;
$ajax2 = new ajax_class("ajax_template_self","ajax_template_self");
$ajax2->populate(array(
	"table",
),function(){
	global $validator2;
	global $html;
	global $connection;
	global $current_object;
	$check=array(
		"table"=>array('isset'=>'','notempty'=>'Favor de ingresar un Nombre','appendquotes'=>"'"),
	);
	if($validator2->validator($check, 'post')){
		$current_object=&$_SESSION["editor"]["functions"][$_SESSION["editor"]["current_function"]]["objects"][$_SESSION["editor"]["current_object"]];
		if(!empty($current_object["tables"])){
			$form=new form_class("form_template");
			$data["success"]=0;
			$jointype=array(
				array("name"=>"Inner Join","id"=>" inner join "),
				array("name"=>"Left Join","id"=>" left join "),
				array("name"=>"Right Join","id"=>" right join "),
			);
			$jointables=array();
			foreach($current_object["tables"] as $key=>$value){
				if(isset($value["querytype"])&&($value["querytype"]!="join")) continue;
				$jointables[]=array("name"=>$value["name"],"id"=>$value["name"]);
			}
			$result_cols1=$this->connection->query("show columns from ?",$validator2->getValue("table"));
			foreach($result_cols1 as $key=>$value){
				$result_cols1[$key]=array("name"=>$value["Field"],"id"=>$value["Field"]);
			}
			$populate_array2=array(
				"jointype"=>array("as"=>"Join Type","type"=>"select","select"=>$jointype),
				"joinwith"=>array("as"=>"Join With","type"=>"select","select"=>$jointables),
				"jointable"=>array("as"=>"Tabla Join","type"=>"text",'isset'=>true),
				"col2"=>array("as"=>$validator2->getValue("table").".","type"=>"select","select"=>$result_cols1,'isset'=>true),
			);
			foreach($current_object["tables"] as $key=>$value){
				$result_cols=$this->connection->query("show columns from ?",$value["name"]);
				foreach($result_cols as $key2=>$value2){
					$result_cols[$key2]=array("name"=>$value2["Field"],"id"=>$value2["Field"]);
				}
				$populate_array2["col1[".$value["name"]."]"]=
					array("as"=>$value["name"].".","linkto"=>"joinwith","showwhenequal"=>"'".$value["name"]."'","type"=>"select","select"=>$result_cols,'isset'=>true);
			}
			$form->populate($populate_array2,$this->html->link("editor3"));
			$validator2->setValue("jointable",$validator2->getValue("table"));
			$form->setSendTo($this->html->link("editor3")."/?id=".$_GET["id"]);
			$form->setValidator($validator2);
			$data["form"]=$form->createFormString();
			$data["eval"]='$.colorbox({
				width: "630px",
				html:data.form,
			});';
			echo json_encode($data);
			die();
		}else{
			//print_r($current_object["tables"]);
			$current_object["tables"][]=array("name"=>$validator2->getValue("table"));
			//$this->current_object["tables"][]=array("name"=>$validator2->getValue("table"));
			$data["success"]=0;
			$data["jumpTo"]=$this->html->link("editor3")."/index/?".http_build_query(array_merge($_GET));
			echo json_encode($data);
			die();
		}
	}else{
		$data["error"]=$validator2->getError();
	}
},$filename."2",$filename);
/*******************************************/
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
		$data["jumpTo"]=$this->html->link("editor3")."/?id=3";
		echo json_encode($data);
		die();
	}else{
		$data["error"]=$validator->getError();
	}
},$filename."5",$filename);
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
$data["editor3_general_template_form"].='<p style="float:left; margin-right:10px;"><button onclick="'.$ajax->generateFunc("'table'").'" class="btn btn-success">Borrar Datos</button></p>
<div style="clear:both;"></div>';
$data["editor3_general_template_form"].='<div class="widget-head">
				<h5 class="pull-left"> Tabla</h5>
				<div class="btn-group pull-right">

				</div>
			</div>';
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
		'sort'=>array('default'=>'false', 'notempty'=>'Favor de ingresar un tipo de objecto'),
		'search'=>array('default'=>'false', 'notempty'=>'Favor de ingresar un tipo de objecto'),
	);
	if($validator->validator($check, 'post')){
		$current_object["cols"][]=array(
			"col"=>$validator->getValue("col"),
			"display"=>$validator->getValue("display"),
			"link"=>$validator->getValue("link"),
			"as"=>$validator->getValue("as"),
			"mysqlas"=>$validator->getValue("mysqlas"),
			"sort"=>$validator->getValue("sort"),
			"search"=>$validator->getValue("search")
		);
		if($validator->getValue("search")=="searchfull"){
			$key = key( array_slice( $current_object["cols"], -1, 1, TRUE ) );
			unset($current_object["cols"][$key]["search"]);
			$current_object["cols"][$key]["searchfull"]="true";
		}
		$validator->clearValues();
	}else{
		$error=$validator->getError();
	}
}
global $validator3;
$validator3=new validate();
$validator3->setPage($filename."3");
$this->validator4=new validate();
$this->validator4->setPage($filename."4");
if($this->validator4->secureSend()){
	$check=array(
		"rowid"=>array('isset'=>'','notempty'=>'Error al seleccionar el Caso Especial','appendquotes'=>"'"),
		"type"=>array('isset'=>'','notempty'=>'Favor de ingresar un Tipo','appendquotes'=>"'"),
		"link"=>array('default'=>'','notempty'=>'','appendquotes'=>"'"),
		"name"=>array('default'=>'','notempty'=>'','appendquotes'=>"'"),
		"borrar_key"=>array('default'=>'','notempty'=>'','appendquotes'=>"'"),
		"borrar_column"=>array('default'=>'','notempty'=>'','appendquotes'=>"'"),
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
			case 2:
				$temp='return $this->table->getLast()->funcGen("borrar",[
					"key"=>'.$this->validator4->getValue("borrar_key").',
					"column"=>"'.$this->validator4->getValue("borrar_column").'"
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
global $ajax4;
$ajax4 = new ajax_class("ajax_template_self","ajax_template_self");
$ajax4->populate(array(
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
			"borrar_key"=>array("as"=>"Llave","type"=>"text","linkto"=>"type","showwhenequal"=>2),
			"borrar_column"=>array("as"=>"Columna","type"=>"text","linkto"=>"type","showwhenequal"=>2),
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
//unset($current_object);
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
$form = new form_class("form_template");
$form->populate(array(
	"col"=>array("as"=>"Columna","type"=>"text",'isset'=>true),
	"as"=>array("as"=>"Nombre","type"=>"text",'isset'=>true),
	"mysqlas"=>array("as"=>"Alias","type"=>"text",'isset'=>true),
	"link"=>array("as"=>"Link","type"=>"select","select"=>$false_true),
	"display"=>array("as"=>"desplegar","type"=>"select","select"=>$true_false),
	"sort"=>array("as"=>"Sorteable","type"=>"select","select"=>$false_true),
	"search"=>array("as"=>"Buscable","type"=>"select","select"=>$searchFull),
),$this->html->link("editor3"));
$form->setValidator($validator);
$data["editor3_general_template_form"].=$form->createFormString();
$data["editor3_general_template_form"].='<div class="widget-head">
				<h5 class="pull-left"> Casos Especiales</h5>
				<div class="btn-group pull-right">

				</div>
			</div>';
$form2 = new form_class("form_template");
//var_dump($validator3);
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
//print_r($current_object["cols"]);
foreach($current_object["cols"] as $key=>$value){
	if($value["link"]=='true'){
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
	global $ajax4;
	return '<button onclick="'.$ajax4->generateFunc("'".$key."'").'" class="btn btn-info">Predeterminados</button>';
});
$form2->setValidator($validator3);
$data["editor3_general_template_form"].=$form2->createFormString();
$data["editor3_general_template_form"].='<div class="widget-head">
				<h5 class="pull-left"> Componentes Del Query</h5>
				<div class="btn-group pull-right">

				</div>
			</div>';
$data["editor3_general_template_form"].=renderTableQuery($current_object["tables"]);
$data["editor3_general_template_form"].='<div class="widget-head">
				<h5 class="pull-left"> Componentes de la Tabla</h5>
				<div class="btn-group pull-right">

				</div>
			</div>';
$data["editor3_general_template_form"].=renderTable($current_object["cols"]);
$data["editor3_general_template_form"].='<div class="widget-head">
				<h5 class="pull-left"> Codigo Controller</h5>
				<div class="btn-group pull-right">

				</div>
			</div>';
$data["editor3_general_template_form"].='<div class="code_syn"><pre class="prettyprint linenums">'.renderCodeCTable($current_object).'</pre></div>';
//$data["form"].=renderCodeTable($current_object);
$data["editor3_general_template_form"].='<div class="widget-head">
				<h5 class="pull-left"> Codigo Viewer</h5>
				<div class="btn-group pull-right">

				</div>
			</div>';
$data["editor3_general_template_form"].='<div class="code_syn"><pre class="prettyprint linenums">'.renderCodeVTable($current_object).'</pre></div>';
$data["editor3_general_template_form"].='<div class="widget-head">
				<h5 class="pull-left"> Vista Previa</h5>
				<div class="btn-group pull-right">

				</div>
			</div>';
if(!empty($current_object["cols"])){
	//unset($current_object);
	$data["result"]="";
	$this->data["result"]="";
	$table=new table_class("table_template_self");
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
			eval(renderCodeCTable($current_object));
		}
		if(1){
			eval(renderCodeVTable($current_object));
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
$data["editor3_general_template_form"].=$ajax2->createAjaxString();
$data["editor3_general_template_form"].=$ajax3->createAjaxString();
$data["editor3_general_template_form"].=$ajax4->createAjaxString();
$data["editor3_general_template_form"].=$ajax5->createAjaxString();
$data["editor3_general_template_form"].=$ajax6->createAjaxString();
$data["breadcrumb"]=array("index","editor3",
array("link"=>"editor3","id"=>5),
array("link"=>"editor3","id"=>$_GET["id"]));
?>
