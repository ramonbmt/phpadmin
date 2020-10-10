<?php
class tabbable{
	private $template=null;
	private $numTabs=0;
	public $tabbs=array();
	private $data;

	function __construct($template){
		$this->template = $template;
	}
	function newTabb($body,$title,$icon="fas fa-table fa-lg"){
		$this->tabbs[]=array(
			'title'=>$title,
			'body'=>$body,
			'icon'=>$icon,
		);
	}
	private function make(){

	}
	function construct(){

	}
	function create(){
		return $this->constructString();
	}
	function constructString($foo=null){
		global $html;
		if(!is_array($this->tabbs))
			return false;
		ob_start();
		include($html->sistemComponentView($this->template));
		$page = ob_get_contents();
		ob_end_clean();
		return $page;
	}
}


?>
