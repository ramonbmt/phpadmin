<?php
class validate{

private $valid=true;
public $args=array();
public $args_i=-1;
private $values=array();
private $error='';
private $def=false;
private $page="";
private $genCode=true;
public $objects=array();

	function __construct(){
		$this->valid=true;
	}
	function next(){
		$this->objects[]=new validate();
		$key=key( array_slice( $this->objects, -1, 1, TRUE ) );
		//$this->objects[$key]->page_name=$filename;
		$this->objects[$key]->setPage($key);
	}
	function setPage($page){
		$this->page=$page;
	}
	function getPage(){
		return $this->page;
	}
	function setValue($key,$value){
		$this->values[$key]=$value;
	}
	function clearValue($key){
		unset($this->values[$key]);
	}
	function clearValues(){
		$this->values=array();
		return true;
	}
	function secureSend(){
		//echo $_SESSION["hidden"]["hidden_".$this->page];
		if(isset($_POST['send_hidden_'.$this->page])&&$_POST["send_hidden_".$this->page]==$_SESSION["hidden"]["hidden_".$this->page]){
			//$_SESSION["hidden"]["hidden_".$this->page]=md5(mt_rand());
			return true;
		}else{
			//$_SESSION["hidden"]["hidden_".$this->page]=md5(mt_rand());
			return false;
		}
	}
	function secureSendInput(){
		echo $this->secureSendInputString();
		/*$_SESSION["hidden"]["hidden_".$this->page]=md5(mt_rand());
		echo '<input type="hidden" name="send_hidden_'.$this->page.'" value="'.$_SESSION["hidden"]["hidden_".$this->page].'" />';*/
	}
	function secureSendInputString(){
		if($this->genCode){
			$_SESSION["hidden"]["hidden_".$this->page]=md5(mt_rand());
			$this->genCode=false;
		}
		return '<input type="hidden" name="send_hidden_'.$this->page.'" value="'.$_SESSION["hidden"]["hidden_".$this->page].'" />';
	}
	function selfInsert($table){
		global $connection;
		$sql="insert into ".$table." (";
		$sql2="";
		if(!is_array($this->values)){
			$this->error="Formulario vacio";
			return false;
		}
		//print_r($this->values);
		$values=array();
		foreach($this->values as $key=>$value){
			$sql.="`".$key."`,";
			//print_r($this->args);
			if(isset($this->args[$key]["appendquotes"])){
				$sql2.="'?',";
			}else{
				$sql2.="?,";
			}
			$values[]=$value;
		}
		$sql=substr($sql,0,-1);
		$sql2=substr($sql2,0,-1);
		$sql.=") values (".$sql2.")";
		//echo $sql;
		$connection->query($sql,$values);
		return true;
	}
	function selfUpdate($table,$where){
		global $connection;
		$sql="update ".$table." SET ";
		$sql2="";
		if(!is_array($this->values)){
			$this->error="Formulario vacio";
			return false;
		}
		//print_r($this->values);
		$values=array();
		foreach($this->values as $key=>$value){
			$sql.="`".$key."`=";
			//print_r($this->args);
			if(isset($this->args[$key]["appendquotes"])){
				$sql.="'?',";
			}else{
				$sql.="?,";
			}
			$values[]=$value;
		}
		$sql=substr($sql,0,-1);
		if(is_array($where)){
			$sql.=" WHERE ";
			$i=0;
			foreach($where as $key=>$value){
				if($i!=0){
					$sql.="AND";
				}
				$sql.=" ".$key."='?' ";
				$i++;
				$values[]=$value;
				//print_r($value);
			}
		}else{
			$sql.=" ".$where;
		}
		//$sql2=substr($sql2,0,-1);
		//$sql.=") values (".$sql2.")";
		//echo $sql;
		$connection->query($sql,$values);
		return true;
	}
	function isNotEmpty($value, $error){
		if($value!=''){
			return true;
		}else{
			if(!$this->def){
				$this->error=$error;
			}
			return false;
		}
	}
	function isNumber($value, $error){
		//var_dump(is_numeric($value));
		if(is_numeric($value)){
			return true;
		}else{
			//echo "no es numero";
			if($value!=''||!$this->def){
				$this->error=$error;
			}
			return false;
		}
	}
	function isAlphaNumber($value, $error){
		//var_dump(is_numeric($value));
		if(ctype_alpha($value)){
			return true;
		}else{
			//echo "no es numero";
			if($value!=''||!$this->def){
				$this->error=$error;
			}
			return false;
		}
	}
	function isEmail($value, $error){
		//var_dump(is_numeric($value));
		if(filter_var($value, FILTER_VALIDATE_EMAIL)){
			return true;
		}else{
			//echo "no es numero";
			if($value!=''||!$this->def){
				$this->error=$error;
			}
			return false;
		}
	}
	function isGreaterThan($value, $value2){
		//var_dump(is_numeric($value));
		if($value>$value2){
			return true;
		}else{
			//echo "no es numero";
			if($value!=''||!$this->def){
				$this->error="El rango del valor es incorrecto";
			}
			return false;
		}
	}
	function isLessThan($value, $value2){
		//var_dump(is_numeric($value));
		if($value<$value2){
			return true;
		}else{
			//echo "no es numero";
			if($value!=''||!$this->def){
				$this->error="El rango del valor es incorrecto";
			}
			return false;
		}
	}
	function isAllSet($value, $error){
		if(1){
			return true;
		}else{
			return false;
		}

	}
	function isEqual($arg1, $arg2){
		if($arg1==$arg2){
			return true;
		}else{
			if($value!=''||!$this->def){
				$this->error="El rango del valor es incorrecto";
			}
			return false;
		}
	}
	function checkImageType($file,$ext){
		$allowedExts = array("gif", "jpeg", "jpg", "png", "PNG");
			$temp = explode(".", $file["name"]);
			$extension = end($temp);
			if (($file["size"] < 2000000)&& in_array($extension, $ext)){
				if($file["error"] > 0){
					$error="Error: " . $file["error"] . "<br>";
					$this->error=$error;
					$this->valid=false;
				}else{
					//echo "exito";
					return true;
				}
			}else{
				$this->error="Archivo invalido";
				$this->valid=false;
			}
	}
	function imagePath($file,$path,$key){
		$temp = explode(".", $file["name"]);
		$extension = end($temp);
		$filename=$file["name"];
		$i=0;
		$flag=1;
		$filename2=$temp[0]."_";
		//echo "hola";
		while(file_exists($path.$filename)){
			if($i>100){
				$error="Imposible de asignar nombre a la imagen";
				return false;
				$this->error=$error;
				$this->valid=false;
				$flag=0;
				break;
			}
			$i++;
			$filename=$filename2.$i.".".$extension;
		}
		if($flag){
			if(isset($this->args[$key]["height"])&&isset($this->args[$key]["width"])){
				$height=$this->args[$key]["height"];
				$width=$this->args[$key]["width"];
			}else{
				$hegiht=150;
				$width=150;
			}
			smart_resize_image($file["tmp_name"],
				$string             = null,
				$width              = $width,
				$height             = $height,
				$proportional       = true,
				$output             = $path.$filename
			);
			$this->values[$key]=$path.$filename;
			//echo $this->value[$key];
			return true;
		}
		return false;
		$this->error="Archivo invalido";
		$this->valid=false;
	}
	function filePath($file,$path,$key){
		$temp = explode(".", $file["name"]);
		$extension = end($temp);
		$filename=$file["name"];
		$i=0;
		$flag=1;
		$filename2=$temp[0]."_";
		//echo "hola";
		while(file_exists($path.$filename)){
			if($i>100){
				$error="Imposible de asignar nombre a la imagen";
				return false;
				$this->error=$error;
				$this->valid=false;
				$flag=0;
				break;
			}
			$i++;
			$filename=$filename2.$i.".".$extension;
		}
		if($flag){
			move_uploaded_file($file["tmp_name"],$path.$filename);
			$this->values[$key]=$path.$filename;
			return true;
		}
		return false;
		$this->error="Archivo invalido";
		$this->valid=false;
	}
	function getError(){
		return $this->error;
	}
	function getValue($str){
		if(isset($this->values[$str])){
			return $this->values[$str];
		}else{
			return;
		}
	}
	function unsetAll(){
		unset($this->values);
		$this->values=array();
		return;
	}
	function validator(array $args){
		$i=0;
		$this->valid=true;
		$this->args=$args;
		foreach($args as $key=>$value){
			$this->def=false;
			if($key!=null){
				if(isset($_POST[$key])||isset($_FILES[$key])){
					foreach($value as $key2=>$value2){
						//print_r($this->values);
						switch ($key2) {
							case "notempty":
								if($this->isNotEmpty($_POST[$key], $value2)){
									$this->values[$key]=$_POST[$key];
								}else{
									if(!$this->def){
										unset($this->values[$key]);
										$this->valid=false;
										continue 3;
									}
								}
								break;
							case "number":
								if($this->isNumber($_POST[$key], $value2)){
									$this->values[$key]=$_POST[$key];
								}else{
									if($_POST[$key]!=''||!$this->def){
										unset($this->values[$key]);
										$this->valid=false;
										continue 3;
									}
								}
								break;
							case "alphanumber":
								if($this->isAlphaNumber($_POST[$key], $value2)){
									$this->values[$key]=$_POST[$key];
								}else{
									if($_POST[$key]!=''||!$this->def){
										unset($this->values[$key]);
										$this->valid=false;
										continue 3;
									}
								}
								break;
							case "greaterthan":
								if($this->isGreaterThan($_POST[$key], $value2)){
									$this->values[$key]=$_POST[$key];
								}else{
									if($_POST[$key]!=''||!$this->def){
										unset($this->values[$key]);
										$this->valid=false;
										continue 3;
									}
								}
								break;
							case "lessthan":
								if($this->isLessThan($_POST[$key], $value2)){
									$this->values[$key]=$_POST[$key];
								}else{
									if($_POST[$key]!=''||!$this->def){
										unset($this->values[$key]);
										$this->valid=false;
										continue 3;
									}
								}
								break;
							case "equals":
								if($this->isEqual($_POST[$key], $value2)){
									$this->values[$key]=$_POST[$key];
								}else{
									if($_POST[$key]!=''||!$this->def){
										unset($this->values[$key]);
										$this->valid=false;
										continue 3;
									}
								}
								break;
							case "email":
								if($this->isEmail($_POST[$key], $value2)){
									$this->values[$key]=$_POST[$key];
								}else{
									if($_POST[$key]!=''||!$this->def){
										unset($this->values[$key]);
										$this->valid=false;
										continue 3;
									}
								}
								break;
							case "appendquote":
								//$this->values[$key]="'".$this->values[$key]."'";
								break;
							case "image_types":
								if(isset($_FILES[$key])&&$this->checkImageType($_FILES[$key],$value2)){
									$this->values[$key]=$_FILES[$key]["name"];
								}else{
									unset($this->values[$key]);
									$this->valid=false;
									continue 3;
								}
								break;
							case "image_path":
								if($this->imagePath($_FILES[$key],$value2,$key)){
									//$this->values[$key]=$_FILES[$key]["name"];
								}else{
									unset($this->values[$key]);
									$this->valid=false;
									continue 3;
								}
								break;
							case "file_path":
								if($this->filePath($_FILES[$key],$value2,$key)){
									//$this->values[$key]=$_FILES[$key]["name"];
								}else{
									unset($this->values[$key]);
									$this->valid=false;
									continue 3;
								}
								break;
							case "default":
								$this->def=true;
								if($_POST[$key]==""){
									$this->values[$key]=$value2;
								}else{
									$this->values[$key]=$_POST[$key];
								}

								break;
							default:

								break;
						}
					}
				}else{
					$this->valid=false;
					$this->error='Valor no recibido - '.$key;
				}
			}
			$i++;
		}
		//print_r(array($this->values,$this->valid));
		if($this->valid){
			return true;
		}else{
			return false;
		}
	}
	function getLast() {
		if (empty($this->objects)) {
			return $this;
		} else {
			return end($this->objects);
		}
	}

}

$validator = new validate();
/*
if(isset($_POST['send_hidden'])){
	$check=array(
		'nombre'=>array('isset'=>'', 'notempty'=>'Favor de ingresar un nombre'),
		'telefono'=>array('isset'=>'', 'notempty'=>'Favor de ingresar un telefono', 'number'=>'No es un numero'),
	);
	//var_dump($validator->validator($check, 'post'));
	if($validator->validator($check, 'post')){
		//do stuff
		echo "todo bien";
	}else{
		echo $validator->getError();
	}
}*/

?>
