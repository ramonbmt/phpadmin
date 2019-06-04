<?php
class prop_class{
	private $template=null;
	private $template2=null;
	private $foo;
	private $params;
	private $page_name;
	public $propId;
	private $data=array();
	public $objects=array();

	function __construct($template){
		$this->template=$template;
	}
	function next($filename,$template=null){
		$this->nextProp($filename,$template);
	}
	function nextProp($filename,$template=null){
		if($template==null){
			$this->objects[]=new prop_class($this->template);
		}else{
			$this->objects[]=new prop_class($template);
		}
		$key=key( array_slice( $this->objects, -1, 1, TRUE ) );
		$this->objects[$key]->propId = "propIdentifier_".$key;
		$this->objects[$key]->page_name=$filename;
		//$this->objects[$key]->propId=$key;
	}
	function populate($data,$propId=""){
		//$this->data=$data;
		if($propId==""){
			//$this->propId=uniqid();
		}else{
			$this->propId=$propId;
		}
		$this->data["page_result_prop_".$this->propId]=$data;
	}
	function getData(){
		return $this->data;
	}
	function createProp(){
		echo $this->createAjaxString();
	}
	function createPropString(){
		global $html;
		ob_start();
		include($html->sistemComponentView($this->template));
		$page = ob_get_contents();
		ob_end_clean();
		return $page;
	}
	function create(){
		return $this->createPropString();
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
