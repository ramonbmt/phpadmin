<?php
	class view_class extends controller_class{
		function __construct($registry){
			$this->registry = $registry;
			$this->data["connection"]=$this->connection;
			$this->data["user"]=$this->user;
			$this->data["html"]=$this->html;
			$this->data["config"]=$this->config;
			//$this->view=array();
			//header("Access-Control-Allow-Origin: *");
			$filename=basename(__FILE__, '.php');
			$this->filename=$filename;
			if(isset($this->view["error"])){
				$this->data["error"]=$this->view["error"];
			}
			if(isset($_POST["ajax"])&&isset($_POST["page"])&&$_POST["ajax"]==1){
				$data["success"]=1;
				if(isset($this->view["error"])){
					$data["error"]=$this->view["error"];
					$data["success"]=0;
				}
				$data=array_merge(
					$data,
					$this->table->getLast()->getData(),
					$this->edit->getLast()->getData(),
					$this->form->getLast()->getData(),
					$this->prop->getLast()->getData()
				);
				echo json_encode($data);
				die();
			}
		}
	}
?>
