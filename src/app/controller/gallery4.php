<?php 
	class controller_view_class extends controller_class {
		function index(){
			$filename=basename(__FILE__, ".php");
			$this->filename=$filename;
			$this->data["table_result"]="";
$this->table->next($this->filename);
$this->table->getLast()->populate(array(
	"gallery.id"=>array("display"=>true,"link"=>false,"as"=>"ID","mysqlas"=>"id","sort"=>true,"searchfull"=>true,),
	"gallery.name"=>array("display"=>true,"link"=>false,"as"=>"Nombre","mysqlas"=>"name","sort"=>false,"searchfull"=>true,),
	"''"=>array("display"=>true,"link"=>true,"as"=>"Borrar","mysqlas"=>"del","sort"=>false,"search"=>false,),
	),
	"gallery",
	$this->sqlbuilder->setTable("gallery")->getSql(),
	function($data,$value,$key){
		global $html;
		switch($key){
			case "del":
				return $this->table->getLast()->funcGen("borrar",[
					"key"=>$value["id"],
					"column"=>"id"
				]);
			break;
		}
	},$this->filename,$this->filename);$this->data["table_header"]="Lista de Galerias";$this->data["search_result"]="";$this->data["pagination_result"]="";$this->data["breadcrumb"]=array("index","gallery");$this->data["table_link"]=$this->html->link("gallery")."/agregar";
			$this->view=$this->data;
		}
	
		function agregar(){
			$filename=basename(__FILE__, ".php");
			$this->filename=$filename;
			$this->data["form"]="";
$this->validator->next($this->filename);
if($this->validator->getLast()->secureSend()){
	$check=array(
		"name"=>array("notempty"=>"Favor de ingresar un Nombre","appendquotes"=>"'",),
	);
	
	if($this->validator->getLast()->validator($check, "post")){
		if($this->validator->getLast()->selfInsert("gallery")){
			
		header("Location: ".$this->html->link("gallery"));
			$this->validator->getLast()->clearValues();
		}else{
			$error=$this->validator->getLast()->getError();
			$data["error"]=$error;
		}
		
	}else{
		$error=$this->validator->getLast()->getError();
		$this->data["error"]=$error;
	}
}
$this->form->next($this->filename);
$this->form->getLast()->populate(array(
	"name"=>array("type"=>"text","as"=>"Nombre",""=>"",),
	),
	"",
	"");
$this->form->getLast()->setFunction(function($data,$value,$key){
global $html;
switch($key){}
});
$this->form->getLast()->setValidator($this->validator->objects[0]);
	$this->data["breadcrumb"]=array("index","gallery",array("link"=>"gallery","path"=>"agregar"));$this->data["title"]="Agregar nueva Galeria";
			$this->view=$this->data;
		}
	
	}
	$controller = new controller_view_class($registry);
	if($action!="" && is_callable(array($controller, $action))) {
		call_user_func_array(array($controller, $action), array());
	} else {
		$controller->index();
	}
	?>