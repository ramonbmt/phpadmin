<?php
class mysql{

public $host;
public $user;
public $pass;
public $db_name;
public $connection;
public $sql;

	function __construct($host, $user, $pass, $db_name, $encode){
		$this->connection = mysql_connect($host, $user , $pass);
		mysql_select_db($db_name, $this->connection);
		mysql_set_charset($encode, $this->connection);
	}

	function query(){
		$args = func_get_args();
		$sql = $args[0];
		$result = array();
		$i = 0;
		if(isset($args[1])&&is_array($args[1])){
			$result=$args[1];
		}else{
			foreach ($args as $item) {
				if ($i == 0) {

				} else{
					$result[] = $item;
				}
				$i++;
			}
		}
		$sql = $this->mysql_bind($sql, $result);
		$quer = mysql_query($sql, $this->connection);
		if(!$quer){
			if (defined('ENVIRONMENT')&&ENVIRONMENT=='development'){
				echo $sql;
				die('Invalid query: ' . mysql_error());
			}else{
				return;
			}
		}
		return $this->mysql_fetch_all($quer);
	}
	function queryAlone(){
		$args = func_get_args();
		$sql = $args[0];
		$result = array();
		$i = 0;
		if(isset($args[1])&&is_array($args[1])){
			$result=$args[1];
		}else{
			foreach ($args as $item) {
				if ($i == 0) {

				} else{
					$result[] = $item;
				}
				$i++;
			}
		}
		$sql = $this->mysql_bind($sql, $result);
		$quer = mysql_query($sql, $this->connection);
		if(!$quer){
			if (defined('ENVIRONMENT')&&ENVIRONMENT=='development'){
				echo $sql;
				die('Invalid query: ' . mysql_error());
			}else{
				return;
			}
		}
		return $quer;
	}

	function mysql_bind($sql, $values=array()){
		$sql = (string) $sql;
		$offset = 0;
		$place=0;
		foreach ($values as &$value){
			//echo $value;
			$value = mysql_real_escape_string($value);
			//$sql = preg_replace('/\?/', $value, $sql, 1);
			$place = strpos($sql, '?', $offset);
			if($place===false){
				break 1;
			}
			$sql = substr_replace($sql, $value, $place, 1);
			$offset = $place + strlen($value);
		}
		//$sql = vsprintf( str_replace('?', "%s", $sql), $values);

		return $sql;
	}

	function mysql_fetch_all($result){
		//var_dump($result);
		if ($result === true || $result==false || mysql_num_rows($result) == 0){
			return array();
		}
		$resultArray = array();
		while(($resultArray[] = mysql_fetch_assoc($result)) || array_pop($resultArray));
		return $resultArray;
	}

	function cleanSqlArgs(){
		$args = func_get_args();
		echo $args[0];
		$result = array();
		$i = 0;
		foreach ($args as $item) {
			if ($i == 0) {

			} else{
				$result[] = $item;
			}
			$i++;
		}
		print_r($result);
	}

	function getLastId(){
		return mysql_insert_id();
	}

	function getAffectedRows(){
		return mysql_affected_rows();
	}

}

$connection = new mysql($config['mysql_host'], $config['mysql_user'], $config['mysql_password'], $config['mysql_database'],
$config['mysql_encode']);
//$connection->query("SET time_zone = '+6:00'");
//print_r($connection->query("select * from stores where id=2"));
//$data=$connection->query("select * from users where store_id=? and name='?' or asdf=?",1,'name',5);
//$host=$config['mysql_host'];
//phpinfo();
//$pdo=new PDO("mysql:host=".$config['mysql_host'].";dbname=".$config['mysql_database'], $config['mysql_user'], $config['mysql_password']);

?>
