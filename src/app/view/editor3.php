<?php
	class view extends view_class{
		function index(){
			$this->data=$this->view;
			//$this->data["breadcrumb"]=array("admin_index","list_gallery");
			$this->data["table_header"]="Galeria de Imagenes";
			$this->data["table_link"]=$this->html->link("list_gallery")."/agregar";
			$this->data["filename"]=$this->filename;

			//$this->data["table_result"]= $this->table->objects[0]->createFullTableString();

			$this->data["search_result"]="";
			$this->data["pagination_result"]="";
			$this->template="editor3_general_template";
			echo $this->render();
		}


	}
	$view = new view($registry);
	if($action!=""&&(strrpos($action,"?")===false)){
		if(is_callable(array($view, $action))){
			call_user_func_array(array($view, $action), array());
		}
	}else{
		$view->index();
	}
?>
