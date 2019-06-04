<?php 
	class view extends view_class {
		function index(){
			$filename=basename(__FILE__, ".php");
			$this->filename=$filename;
			$this->data["table_result"].= $this->table->objects[0]->createFullString();
			$this->template="list_template";
			echo $this->render();
		}
	
		function agregar(){
			$filename=basename(__FILE__, ".php");
			$this->filename=$filename;
			$this->data["form"].= $this->form->objects[0]->createFullString();
			$this->template="add_template";
			echo $this->render();
		}
	
	}
	$view = new view($registry);
	if($action!="" && $action!="?") {
		if(is_callable(array($view, $action))) {
			call_user_func_array(array($view, $action), array());
		}
	} else {
		$view->index();
	}
	?>