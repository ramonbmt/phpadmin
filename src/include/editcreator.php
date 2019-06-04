<?php
class edit_class{
	private $data=array();
	private $columns;
	private $template=null;
	private $result;
	private $tableId="";
	private $fooFirst;
	private $foo;
	private $validator=null;
	private $alones=array();
	public $objects=array();

	function __construct($template){
		$this->template=$template;
	}
	function next($filename=null,$template=null){
		return $this->nextEdit($filename,$template);
	}
	function nextEdit($filename,$template=null){
		if($template==null){
			$this->objects[]=new edit_class($this->template);
		}else{
			$this->objects[]=new edit_class($template);
		}
		$key=key( array_slice( $this->objects, -1, 1, TRUE ) );
		//$this->objects[$key]->page_name=$filename;
		$this->objects[$key]->tableId=$key;
	}
	function populate($columns,$table,$condition=null,$tableId="",$fooFirst=null,$foo=null,$validator=null){
		if(!is_array($columns))
			return false;
		$this->fooFirst=$fooFirst;
		$this->foo=$foo;
		if($validator==null){
			global $validator;
		}
		$this->validator=$validator;
		$this->tableId=$tableId;
		$this->columns=$columns;
		global $connection;
		global $html;
		$sql="select ";
		$i=0;
		foreach($columns as $key=>$value){
			if($i!=0)
				$sql.=",";
			$sql.=$key;
			if(isset($value["mysqlas"])){
				$this->columns[$value["mysqlas"]]=$value;
				unset($this->columns[$key]);
				$sql.=" as ".$value["mysqlas"];
			}
			$i++;
		}
		$sql.=" from ".$table;
		if($condition==null){
			$sql.=" where 1";
		}else{
			$sql.=" ".$condition;
		}
		$sql.=" limit 1";
		$this->result=$connection->query($sql);
		if($this->template=="edit_template_self"){
			ob_start();
			include($html->sistemComponentView("edit1_template"));
			$edit1 = ob_get_contents();
			ob_end_clean();
			ob_start();
			include($html->sistemComponentView("edit2_template"));
			$edit2 = ob_get_contents();
			ob_end_clean();
			$this->data["page_result_".$this->tableId]=array("edit1"=>$edit1,"edit2"=>$edit2);
		}
	}
	function makeEdit($fooFirst=null,$foo=null,$tableId="",$validator=null){
		global $html;
		if($tableId!=""){
			$this->tableId=$tableId;
		}
		if($fooFirst!=null){
			$this->fooFirst=$fooFirst;
		}
		if($foo!=null){
			$this->foo=$foo;
		}
		/*if($validator!=null){
			$this->validator=$validator;
		}
		if($validator==null){
			global $validator;
		}*/
		if($validator!=null){
			$this->validator=$validator;
		}else if($this->validator!=null){
			$validator=$this->validator;
		}else{
			global $validator;
		}
		if(!is_array($this->result))
			return false;
		ob_start();
		include($html->sistemComponentView($this->template));
		$page = ob_get_contents();
		ob_end_clean();
		echo $page;
	}
	function makeEditString($fooFirst=null,$foo=null,$tableId="",$validator=null){
		global $html;
		if($tableId!=""){
			$this->tableId=$tableId;
		}
		//print_r($this->validator);
		/*if($fooFirst!=null){
			$this->fooFirst=$fooFirst;
		}
		if($foo!=null){
			$this->foo=$foo;
		}*/

		if($fooFirst==null){
			$fooFirst=$this->fooFirst;
		}
		if($foo==null){
			$foo=$this->foo;
		}
		if($validator!=null){
			$this->validator=$validator;
		}else if($this->validator!=null){
			$validator=$this->validator;
		}else{
			global $validator;
		}
		/*if($validator!=null){
			$this->validator=$validator;
		}
		if($validator==null){
			global $validator;
		}*/
		if(!is_array($this->result))
			return false;
		ob_start();
		include($html->sistemComponentView($this->template));
		$page = ob_get_contents();
		ob_end_clean();
		return $page;
	}
	function createFullString($fooFirst=null,$foo=null,$tableId="",$validator=null){
		return $this->makeEditString($fooFirst,$foo,$tableId,$validator);
	}
	function create($fooFirst=null,$foo=null,$tableId="",$validator=null){
		return $this->makeEditString($fooFirst,$foo,$tableId,$validator);
	}
	function getResult(){
		return $this->result();
	}
	function getData(){
		return $this->data;
	}
	function setValidator($validator){
		$this->validator=$validator;
		return;
	}
	function setId($tableId=""){
		$this->tableId=$tableId;
		return;
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
