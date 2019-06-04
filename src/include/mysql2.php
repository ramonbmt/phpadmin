<?php
require_once "config.php";
//phpinfo();
class mysql{

public $host;
public $user;
public $pass;
public $db_name;
public $connection;
public $sql;

	function __construct($host, $user, $pass, $db_name){
		$this->connection = mysql_connect($host, $user , $pass);
		mysql_select_db($db_name, $this->connection);
	}
	
	function query(){
		$args = func_get_args();
		$sql = $args[0];
		$result = array();
		$i = 0;
		foreach ($args as $item) {
			if ($i == 0) {
				
			} else{
				$result[] = $item;
			}
			$i++;
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
	
	function mysql_bind($sql, $values=array()){
		$sql = (string) $sql;
		$offset = 0;
		$place=0;
		foreach ($values as &$value){
			$value = mysql_real_escape_string($value);
			//$sql = preg_replace('/\?/', $value, $sql, 1);
			$place = strpos($sql, '?', $offset);
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

$connection = new mysql($config['mysql_host'], $config['mysql_user'], $config['mysql_password'], $config['mysql_database']);
//$db = new PDO('sqlite:host=localhost;dbname=develbmt_asertek;charset=utf8', $config['mysql_user'], $config['mysql_password']);
//$sql = 'SELECT * from users where 1';
//print_r($db->query($sql));
//print_r(PDO::getAvailableDrivers());
//$connection->query("SET time_zone = '+6:00'");
//print_r($connection->query("select * from stores where id=2"));
//$data=$connection->query("select * from users where store_id=? and name='?' or asdf=?",1,'name',5);

?>