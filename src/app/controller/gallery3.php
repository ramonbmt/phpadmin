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
	),
	"gallery",
	$this->sqlbuilder->setTable("gallery")->getSql(),
	function($data,$value,$key){
		global $html;
		switch($key){}
	},$this->filename,$this->filename);$this->data["table_header"]="Lista de Galerias";$this->data["search_result"]="";$this->data["pagination_result"]="";
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