<?php
class querybuilder_class{
	private $template;
	private $args=array();
	private $sql="";
	private $trace=array();
	private $trace_string=array();

	private $args_clone=array();
	private $sql_clone="";
	private $trace_clone=array();
	private $trace_string_clone=array();

	function __construct($template){
		$this->template=$template;
	}
	function select(){
		$sql="SELECT ";
		$arg_list = func_get_args();
		$this->trace["columns"]=$arg_list;
		$i=0;
		foreach($arg_list as $value){
			if($i!=0){
				$sql.=",";
			}
			if(is_array($value)){
				$key=key($value);
				$sql.=$key." as ".$value[$key];
			}else{
				$sql.=$value." ";
			}
			$i++;
		}
		$this->trace_string["colmns"]=$sql;
		$this->sql.=$sql;
		return $this;
	}
	function update(){
		$sql=" UPDATE ";
		$arg_list = func_get_args();
		$this->trace["columns"]=$arg_list[1];
		$sql.=$arg_list[0]." SET ";
		$i=0;
		foreach($arg_list[1] as $key=>$value){
			if($i!=0){
				$sql.=",";
			}
			$sql.=$key."=".$value;
			$i++;
		}
		$sql.=" ";
		$this->trace_string["update"]=$sql;
		$this->sql.=$sql;
		return $this;
	}
	function insert(){
		$sql=" INSERT ";
		$arg_list = func_get_args();
		$this->trace["columns"]=$arg_list[0];
		$sql.="INTO ".$arg_list[0]." (";
		$i=0;
		foreach($arg_list[1] as $key=>$value){
			if($i!=0){
				$sql.=",";
			}
			$sql.=$key;
			$i++;
		}
		$sql.=") VALUES (";
		$i=0;
		foreach($arg_list[1] as $key=>$value){
			if($i!=0){
				$sql.=",";
			}
			$sql.=$value;
			$i++;
		}
		$sql.=")";
		$this->trace_string["insert"]=$sql;
		$this->sql.=$sql;
		return $this;
	}
	function from($args){
		$sql=" FROM ";
		$sql.=$args;
		$this->sql.=$sql;
		$this->trace_string["table"]=$sql;
		$this->trace["table"]=$args;
		return $this;
	}
	function where(){
		$sql=" WHERE ";
		$arg_list = func_get_args();
		$i=0;
		foreach($arg_list[0] as $key=>$value){
			if($i!=0){
				$sql.="AND";
			}
			$sql.=" ".$key."=".$value." ";
			$i++;
			//print_r($value);
		}
		$this->sql.=$sql;
		$this->trace["where"]=$arg_list;
		$this->trace_string["where"]=$sql;
		return $this;
	}
	function whereFull(){
		$sql=" WHERE ";
		$arg_list = func_get_args();
		$i=0;
		foreach($arg_list[0] as $key=>$value){
			if($i!=0){
				$sql.=$value[0];
			}
			$sql.=" ".$value[1].$value[2].$value[3]." ";
			$i++;
			//print_r($value);
		}
		$this->sql.=$sql;
		$this->trace["where"]=$arg_list;
		$this->trace_string["where"]=$sql;
		return $this;
	}
	function limit(){
		$sql=" LIMIT ";
		$n=func_num_args();
		$arg_list = func_get_args();
		for($i=0;$i<$n;$i++){
			if($i>1){
				break;
			}
			if($i!=0){
				$sql.=",";
			}
			$sql.=$arg_list[$i];
		}
		$this->sql.=$sql;
		$this->trace["limit"]=$arg_list;
		$this->trace_string["limit"]=$sql;
		return $this;
	}
	function orderBy(){
		$sql=" ORDER BY ";
		$n=func_num_args();
		$arg_list = func_get_args();
		$sql.=" ".$arg_list[0]." ";
		if($n>1){
			$sql.=$arg_list[1];
		}
		$this->sql.=$sql;
		$this->trace["orderby"]=$arg_list;
		$this->trace_string["orderby"]=$sql;
		return $this;
	}
	function groupBy(){
		$sql=" GROUP BY ";
		$n=func_num_args();
		$arg_list = func_get_args();
		$sql.=" ".$arg_list[0]." ";
		$this->sql.=$sql;
		$this->trace["groupby"]=$arg_list;
		$this->trace_string["groupby"]=$sql;
		return $this;
	}
	function join(){
		$joins=array("INNER JOIN","LEFT JOIN","RIGHT JOIN");
		$sql=" ";
		$n=func_num_args();
		$arg_list = func_get_args();
		if($n<4){
			$sql.=$joins[0]." ";
		}else{
			$sql.=$arg_list[3]." ";
		}
		$sql.=$arg_list[0]." ";
		$sql.="ON ";
		if($n>4){
			$table=$arg_list[4];
		}else{
			$table=$this->trace["table"];
		}
		if(strcasecmp($arg_list[0],"left join")==0){
			$sql.=$arg_list[0].".".$arg_list[2]."=".$table.".".$arg_list[1];
		}else{
			$sql.=$table.".".$arg_list[1]."=".$arg_list[0].".".$arg_list[2];
		}


		$this->sql.=$sql;
		$this->trace["join"]=$arg_list;
		$this->trace_string["join"]=$sql;
		return $this;
	}
	function setParams(){
		$this->args=func_get_args();
		return $this;
	}
	function setTable($table){
		$this->trace["table"]=$table;
		return $this;
	}
	function getSql(){
		global $connection;
		$temp = $connection->mysql_bind($this->sql,$this->args);
		$this->cloneSql();
		$this->cleanSql();
		return $temp;
	}
	function execSql(){
		global $connection;
		$return=$connection->query($this->getSql());
		return $return;
	}
	function getTrace(){
		return $this->trace;
	}
	function getTraceString(){
		return $this->trace_string;
	}
	function cleanSql(){
		$this->args=array();
		$this->sql="";
		$this->trace=array();
		$this->trace_string=array();
		return;
	}
	function cloneSql(){
		$this->args_clone=array();
		$this->sql_clone="";
		$this->trace_clone=array();
		$this->trace_string_clone=array();
		return;
	}
	function custom($str){
		$this->sql.=" ".$str." ";
		return $this;
	}
	function transaction($querys){
		global $connection;
		$this->querys=$querys;
		try{
			$connection->query("START TRANSACTION");
			$querys();
			$this->commit();
		}catch(Exception $e){
			$this->rollback();
		}
		return;
	}
	function commit(){
		global $connection;
		$connection->query("COMMIT");
		return;
	}
	function rollback(){
		global $connection;
		$connection->query("ROLLBACK");
		return;
	}
}
/*
$sqlbuilder = new querybuilder_class("");
	echo $sqlbuilder->select("name","id","lastname")
		->from("users")
		->join("client","clientid","id")
		->join("table","tableid","id","inner join")
		->where(array(
			"id"=>"'?'",
			"name"=>"'?'",
		))
		->limit("?","?","?")
		->orderBy("name")
		->setParams(1,2,5,9)
		->getSql();
*/
?>
