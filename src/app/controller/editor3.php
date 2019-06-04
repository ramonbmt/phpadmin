<?php

	class controller_view_class extends controller_class{

		function index(){
			//define('ENVIRONMENT', 'superDevelopment');
			include("editor3/render.php");
			include("editor3/codigo.php");
			//sendTo($this->html->link("list_gallery")."/agregar",$this);
			$filename=basename(__FILE__, '.php');
			$this->filename=$filename;


			checkLogin();
			$filename=basename(__FILE__, '.php');
			if(!isset($_GET["id"])){
				$_GET["id"]=0;
			}
			//unset($_SESSION["editor"]);
			if(!isset($_SESSION["editor"])){
				$_SESSION["editor"]["functions"]=array();
				$_SESSION["editor"]["current_function"]=null;
				$_SESSION["editor"]["next_func"]=0;
				$_SESSION["editor"]["current_object"]=null;
				$_SESSION["editor"]["next_object"]=0;
			}
			$breadcrumb=array("index","editor2","");
			$data["form"]="";
			$data["editor3_general_template_form"]="";
			//$_SESSION["editor"]["form"]=array();
			switch($_GET["id"]){
				case 0:
					include "editor3/component/functions.php";
				break;
				case 5:
					include "editor3/component/objects.php";
				break;
				case 1:
					include "editor3/component/form.php";
				break;
				case 2:
					include "editor3/component/editor.php";
				break;
				case 3:
					include "editor3/component/table.php";
				break;
				case 4:
					include "editor3/component/validator.php";
				break;
				case 6:
					include "editor3/component/ajax.php";
				break;
				case 7:
					include "editor3/component/boton.php";
				break;
				case 8:
					include "editor3/component/query.php";
				break;
				case 9:
					include "editor3/component/variable.php";
				break;
			}
			$this->data=array_merge($data,$this->data);
			if(isset($_SESSION["error"])){
				$this->data["error"]=$_SESSION["error"];
				unset($_SESSION["error"]);
			}
			$this->data["editor3_general_template_ajax"]=$ajax->createAjaxString();
			//$this->data["editor3_general_template_ajax"].=`<script src="http://code.jquery.com/ui/1.8.21/jquery-ui.min.js"></script>`;
			//$this->data["editor3_general_template_ajax"].="<script>$($('#componentes > tbody').sortable());</script>";
			//$this->data["editor3_general_template_ajax"].=`<script>$('#componentes > tbody').sortable();</script>`;
			$this->view=$this->data;
		}
	}
	$controller = new controller_view_class($registry);
	if($action!=""&&is_callable(array($controller, $action))){
		call_user_func_array(array($controller, $action), array());

	}else{
		$controller->index();
	}
?>
