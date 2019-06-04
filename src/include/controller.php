<?php
	class controller_class{
		protected $data=array();
		//protected $view=array();
		protected $template="";
		protected $registry;
		protected $filename;
		
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
			//forceLogin();
			checkLogin();
		}
		public function __get($key) {
			return $this->registry->get($key);
		}
		public function __set($key, $value) {
			$this->registry->set($key, $value);
		}
		public function render(){
			extract($this->data);
			ob_start();
			require($this->html->templateView($this->template));
			$this->output = ob_get_contents();
			ob_end_clean();
			return $this->output;
		}
	}
	
	
?>