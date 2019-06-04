<?php
class modal_class{
	private $template=null;
	private $template2=null;
	private $foo;
	private $params;
	private $page_name;
	private $modalId;
	
	function __construct($template,$template2=""){
		$this->template=$template;
		$this->template2=$template2;
	}
	function populate($params,$foo,$modalId,$page_name){
		global $data;
		global $connection;
		global $sqlbuilder;
		global $html;
		$this->modalId=$modalId;
		$this->page_name=$page_name;
		$this->foo=$foo;
		$this->params=$params;
		if(isset($_POST["ajax"])&&isset($_POST["action"])&&isset($_POST["modalSel"])&&$_POST["ajax"]==1&&$_POST["action"]=="modal"&&$_POST["modalSel"]==$modalId){
			$foo();
		}
	}
	function generateFunc(){
		$str="modalFunc_".$this->modalId."(event,";
		foreach(func_get_args() as $value){
			$str.=$value.",";
		}
		$str=substr($str, 0, -1);
		$str.=");";
		return $str;
	}
	function createModal(){
		global $html;
		ob_start();		
		include($html->sistemComponentView($this->template));
		$page = ob_get_contents();
		ob_end_clean();
		echo $page;
	}
	function createModalString(){
		global $html;
		ob_start();		
		include($html->sistemComponentView($this->template));
		$page = ob_get_contents();
		ob_end_clean();
		return $page;
	}
	function create() {
		return $this->createModalString();
	}
}

?>