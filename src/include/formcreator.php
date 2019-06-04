<?php
class form_class{
	public $template=null;
	public $args=null;
	private $columns;
	private $result;
	private $returnhtml;
	private $foo;
	private $div_id;
	private $validator=null;
	private $formId;
	private $data=array();
	public $objects=array();
	public $importObjects=array();
	private $sendButton=true;
	private $gostForm=false;
	private $addBtn;

	function __construct($template){
		$this->template = $template;
	}
	function next($filename=null,$template=null){
		return $this->nextForm($filename,$template);
	}
	function nextForm($filename,$template=null){
		if($template==null){
			$this->objects[]=new form_class($this->template);
		}else{
			$this->objects[]=new form_class($template);
		}
		$key=key( array_slice( $this->objects, -1, 1, TRUE ) );
		//$this->objects[$key]->page_name=$filename;
		$this->objects[$key]->formId=$key;
	}
	function importForm(&$form){
		$form->sendButton=false;
		$this->importObjects[]=$form->formId;
		$this->gostForm=true;
		return $this;
	}
	function populate($columns,$return,$sendTo="",$formId="",$div_id="",$addBtn="",$foo=null,$validator=null){
		global $html;
		if(!is_array($columns))
			return false;
		$this->columns=$columns;
		$this->returnhtml=$return;
		$this->sendTo=$sendTo;
		$this->formId=$formId;
		$this->foo=$foo;
		$this->div_id=$div_id;
		if ($addBtn == "") {
			$addBtn = "Agregar";
		}
		$this->addBtn=$addBtn;
		if($validator==null){
			global $validator;
		}
		$this->validator=$validator;
		if($this->template=="form_template_self"){
			ob_start();
			include($html->sistemComponentView("form1_template"));
			$form1 = ob_get_contents();
			ob_end_clean();
			$this->data["page_result_form_".$this->formId]=array("form1"=>$form1);
		}
	}
	function createForm($foo=null,$validator=null){
		/*global $html;
		global $search;
		if($foo!=null){
			$this->foo=$foo;
		}
		if($validator!=null){
			$this->validator=$validator;
		}
		ob_start();
		include($html->sistemComponentView($this->template));
		$page = ob_get_contents();
		ob_end_clean();
		echo $page;*/
		return createFormString($foo,$validator);
	}
	function createFormString($foo=null,$validator=null){
		global $html;
		global $search;
		if($foo!=null){
			$this->foo=$foo;
		}
		$foo=$this->foo;
		if($validator!=null){
			$this->validator=$validator;
		}
		ob_start();
		include($html->sistemComponentView($this->template));
		$page = ob_get_contents();
		ob_end_clean();
		return $page;
	}
	function create($foo=null,$validator=null){
		return $this->createFormString($foo,$validator);
	}
	function createFullString($foo=null,$validator=null){
		return $this->createFormString($foo,$validator);
	}
	function setCols($columns){
		$this->columns=$columns;
	}
	function setReturn($return){
		$this->returnhtml=$return;
	}
	function setSendTo($sendTo){
		$this->sendTo=$sendTo;
	}
	function setFormId($formId){
		$this->formId=$formId;
	}
	function setFunction($foo){
		$this->foo=$foo;
	}
	function setValidator($validator){
		$this->validator=$validator;
	}
	function setDivId($div_id){
		$this->div_id=$div_id;
	}
	function setAddBtn($addBtn){
		$this->addBtn=$addBtn;
	}
	function getData(){
		return $this->data;
	}
	function getFormId(){
		return $this->formId;
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
