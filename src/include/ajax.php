<?php
class ajax_class{
	private $template=null;
	private $template2=null;
	private $foo;
	private $params;
	private $page_name;
	private $ajaxId;
	public $objects=array();

	function __construct($filename,$template,$template2=null){
		$this->template=$template;
		$this->template2=$template2;
		$this->page_name=$filename;
	}
	function next($filename,$template=null){
		$this->nextAjax($filename,$template);
	}
	function nextAjax($filename,$template=null){
		if($template==null){
			$this->objects[]=new ajax_class($filename,$this->template);
		}else{
			$this->objects[]=new ajax_class($filename,$template);
		}
		$key=key( array_slice( $this->objects, -1, 1, TRUE ) );
		$this->objects[$key]->page_name=$filename;
		$this->objects[$key]->ajaxId=$filename."_".$key;
	}
	function populate($params,$foo,$ajaxId=null,$page_name=null,$validator=null){
		global $data;
		global $connection;
		global $sqlbuilder;
		global $html;
		$this->foo=$foo;
		if($page_name != null){
			$this->page_name = $page_name;
		}
		if($ajaxId != null){
			$this->ajaxId = $ajaxId;
		}
		if($validator==null){
			global $validator;
		}
		$this->params=$params;
		if(isset($_POST["ajax"])&&isset($_POST["action"])&&isset($_POST["ajaxSel"])
			&&$_POST["ajax"]==1&&$_POST["action"]=="ajax"&&$_POST["ajaxSel"]==$this->ajaxId){
			$foo();
		}
	}
	function generateFunc(){
		$str="ajaxFunc_".$this->ajaxId."(event,";
		foreach(func_get_args() as $value){
			$str.=$value.",";
		}
		$str=substr($str, 0, -1);
		$str.=");";
		return $str;
	}
	function createAjax(){
		global $html;
		ob_start();
		include($html->sistemComponentView($this->template));
		$page = ob_get_contents();
		ob_end_clean();
		echo $page;
	}
	function createAjaxString(){
		global $html;
		ob_start();
		include($html->sistemComponentView($this->template));
		$page = ob_get_contents();
		ob_end_clean();
		return $page;
	}
	function create(){
		return $this->createAjaxString();
	}
	function getLast(){
		if(empty($this->objects)){
			return $this;
		}else{
			return end($this->objects);
		}
	}
}

?>
