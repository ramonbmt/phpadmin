<?php
	class view extends view_class{
		function detalles(){
			$u_id=$_GET["id"];
			$this->edit->objects[0]->setId("display_user");
			$this->tabbs->newTabb(
				$this->edit->objects[0]->createFullString(),
				"Datos"
			);
			$this->tabbs->newTabb(
				$this->form->objects[0]->createFullString(),
				"Permisos"
			);
			// print_r($this->ajax->objects[0]->generateFunc($u_id));
			$this->tabbs->newTabb('<div>
						<p style="float:right;"> 
							<a href="#" onclick="'.$this->ajax->objects[0]->generateFunc($u_id).'" class="prettyPhoto btn btn-danger">
								Cerrar Caja
							</a> 
						</p>
					</div>'.$this->ajax->objects[0]->create().$this->table->objects[0]->createTableString(),
					"Ventas en caja"
				);
			$this->data["edit"]=$this->tabbs->constructString();
			$this->data["breadcrumb"]=array(
				"admin_index",
				"list_users",
				array(
					"link"=>"list_users",
					"path"=>"detalles",
					"id"=>$_GET["id"]
				)
			);
			$this->template="edit_template";
			$this->data["edit_title"]="Informacion del usuario #: ".$_GET["id"];
			echo $this->render();
		}
		
		function agregar(){
			$this->data["breadcrumb"]=array(
				"admin_index",
				"list_users",
				array(
					"link"=>"list_users",
					"path"=>"agregar"
				)
			);
			$this->template="add_template";
			$this->data["title"]="Agregar Usuario";
			$this->data["form"]=$this->form->objects[0]->createFormString();
			echo $this->render();
		}

		function index(){
			$this->data["breadcrumb"]=array(
				"admin_index",
				"list_users"
			);
			$this->data["table_header"]="Usuarios";
			$this->data["table_link"]=$this->html->link("list_users")."/agregar";

			$this->data["search_result"]="";
			$this->data["table_result"]= $this->table->objects[0]->createFullTableString();
			$this->data["pagination_result"]="";

			$this->template="list_template";
			echo $this->render();
		}
	}
	$view = new view($registry);
	if($action!="" && $action!="?"){
		if(is_callable(array($view, $action))){
			call_user_func_array(array($view, $action), array());
		}
	}else{
		$view->index();
	}
?>