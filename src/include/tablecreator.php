<?php
class table_class
{
	public $template=null;
	public $template_excel="excel_table_template_bk";
	public $template_pdf="excel_table_template_pdf";
	public $download_file_name=null;
	public $args=null;
	private $columns;
	private $result;
	private $condition="";
	public $bodyId="";
	private $num=0;
	public $page_name="";
	private $count_id=null;
	private $original_columns;
	private $isSearchFull=0;
	private $foo;
	private $fooAfter;
	private $fooBefore;
	private $preDelete;
	private $postDelete;
	private $preUpdate;
	private $postUpdate;
	private $data=array();
	public $objects=array();
	public $breadcrumb=array();
	private $nextCreated=false;
	private $limit=10;

	function __construct($template){
		$this->template = $template;
	}

	function next($filename,$template=null){
		$this->nextTable($filename,$template);
	}

	function nextTable($filename,$template=null){
		if($template==null){
			$this->objects[]=new table_class($this->template);
		}else{
			$this->objects[]=new table_class($template);
		}

		$key = key( array_slice( $this->objects, -1, 1, TRUE ) );
		$this->objects[$key]->page_name = $filename;
		$this->objects[$key]->bodyId = "tableIdentifier_".$key;
		$this->objects[$key]->nextCreated = true;
	}

	function populate($columns, $table, $condition = null, $foo = null, $bodyId=null, $page_name=null, $limit=null){
		global $data;
		global $html;

		if($limit == null) {
			$limit = $this->limit;
		}

		$this->foo = $foo;

		if(!is_array($columns))
			return false;
		if(!$this->nextCreated){
			if($bodyId != null){
				$this->bodyId = $bodyId;
			}
			if($page_name!=null){
				$this->page_name = $page_name;
			}
		}

		$this->columns = $columns;
		$this->count_id = key($columns);
		$this->original_columns = $columns;
		global $connection;
		$sql="select ";
		$i = 0;

		foreach($columns as $key=>$value){
			if($i != 0)
				$sql.=",";
			$sql.="'prueba'";

			if(isset($value["mysqlas"])){
				$sql.=" as ".$value["mysqlas"];
			}else{
				$sql.=" as ".$key;
			}
			$i++;
		}
		$sql.=" from ".$table;
		$sql.=" where 1 limit 1";
		$this->result = $connection->query($sql);
		$sql="select ";
		$i = 0;

		foreach($columns as $key=>$value){
			if($i != 0)
				$sql.=",";
			$sql.=$key;
			$value["original_key"]=$key;

			if(isset($value["mysqlas"])){
				unset($this->columns[$key]);
				$this->columns[$value["mysqlas"]]=$value;
				$sql.=" as ".$value["mysqlas"];
			}

			if(isset($value["searchfull"]) && $value["searchfull"] = 1){
				$this->isSearchFull = 1;
			}
			$i++;
		}
		//print_r($this->columns);
		$sql.=" from ".$table;
		if($condition == null){
			$sql.=" where 1";
			$this->condition=" where 1";
		}else{
			$sql.=" ".$condition;
			$this->condition=" ".$condition;
		}
		//print_r($this->columns);
		$flag = 1;
		//echo $sql;
		if(isset($_POST["ajax"]) && isset($_POST["page"]) && isset($_POST["action"]) && 
			isset($_POST["table"]) && $_POST["ajax"] == 1 && $_POST["table"] == $this->bodyId){
			$result2=array();
			//echo $sql;
			switch($_POST["action"]){
				case "pagination":
					$search_params = json_decode($_POST["str"]);
					$flag = 0;
					$optimized_columns = $this->original_columns;
					$optimized_search_params = get_object_vars($search_params);
					foreach($optimized_search_params as $key=>$value){
						if($value!=""){
							$flag=1;
						}else{
							foreach($optimized_columns as $columnKey=>$columnValue){
								if(isset($columnValue["mysqlas"]) && $columnValue["mysqlas"] == $key){
									unset($optimized_search_params[$key]);
									unset($optimized_columns[$columnKey]);
								}
							}
						}
					}

					$params = array();
					$i_params = 0;
					if($flag){
						if(!$this->isSearchFull){
							$search_str=$search_params->search;
							$sql2=$this->searchSql($this->original_columns,$i_params);
							$place = stripos($sql, 'where', 0);
							if($place===false){
								$sql.=$sql2;
							}else{
								$sql = substr_replace($sql, " ".$sql2." and ", $place, 5);
							}
							for($i=0;$i<$i_params;$i++){
								$params[]=$search_str;
							}
						}else{
							$sql2=$this->searchSqlAnd($optimized_columns,$i_params);
							$place = stripos($sql, 'where', 0);
							if($place===false){
								$sql.=$sql2;
							}else{
								$sql = substr_replace($sql, " ".$sql2." and ", $place, 5);
							}
							// $search_params=json_decode($_POST["str"]);
							foreach($optimized_search_params as $key=>$value){
								$params[]=$value;
							}
							// for($i=count($search_params);$i<$i_params;$i++){
							// 	//$params[]="";
							// }
						}
					}
					//echo $sql;
					$sorting = json_decode($_POST["sort"]);
					$order = $this->sortOrder($table,$sorting);
					$place = $order=="" ? false : stripos($sql, 'order by', 0);
					if($place===false){
						$sql.=" ? ";
					}else{
						$sql = substr_replace($sql, " ?, ", $place, 8);
					}
					$sql.=" limit ".($limit*$_POST["page"]).",".$limit;
					$params[]=$order;
					$result2=$connection->query($sql,$params);
					foreach($result2 as $key=>$value){
						foreach($this->columns as $key2=>$value2){
							if($value2["display"]){
								if($value2["link"]){
									//$result_temp[$key2]="<td>".$foo($value[$key2],$value,$key2)."</td>";
									$result2[$key][$key2]=$foo($value[$key2],$value,$key2);
									if($result2[$key][$key2]==="<#!jumprow!#>"){
										unset($result2[$key]);
										break;
									}
								}else{
									//$result_temp[$key2]="<td>'+v.".$value[$key2]."+'</td>";
									$result2[$key][$key2]=$value[$key2];
								}
							}
						}
					}
				break;
				case "search":
					$search_params = json_decode($_POST["str"]);
					$flag = 0;
					$optimized_columns = $this->original_columns;
					$optimized_search_params = get_object_vars($search_params);
					foreach($optimized_search_params as $key=>$value){
						if($value!=""){
							$flag=1;
						}else{
							foreach($optimized_columns as $columnKey=>$columnValue){
								if(isset($columnValue["mysqlas"]) && $columnValue["mysqlas"] == $key){
									unset($optimized_search_params[$key]);
									unset($optimized_columns[$columnKey]);
								}
							}
						}
					}
					$params=array();
					$i_params=0;
					if($flag){
						if(!$this->isSearchFull){
							$search_str=$search_params->search;
							$sql2=$this->searchSql($this->original_columns, $i_params);
							$place = stripos($sql, 'where', 0);
							if($place===false){
								$sql.=$sql2;
							}else{
								$sql = substr_replace($sql, " ".$sql2." and ", $place, 5);
							}
							for($i=0;$i<$i_params;$i++){
								$params[]=$search_str;
							}
						}else{
							$sql2=$this->searchSqlAnd($optimized_columns, $i_params);
							$place = stripos($sql, 'where', 0);
							if($place===false){
								$sql.=$sql2;
							}else{
								$sql = substr_replace($sql, " ".$sql2." and ", $place, 5);
							}
							// $search_params=json_decode($_POST["str"]);
							foreach($optimized_search_params as $key=>$value){
								$params[]=$value;
							}
							// for($i=count($search_params);$i<$i_params;$i++){
							// 	//$params[]="";
							// }
						}
					}
					$sorting=json_decode($_POST["sort"]);
					$order=$this->sortOrder($table,$sorting);
					$place = $order=="" ? false : stripos($sql, 'order by', 0);
					if($place===false){
						$sql.=" ? ";
					}else{
						$sql = substr_replace($sql, " ?, ", $place, 8);
					}
					$sql.=" limit ".($limit*$_POST["page"]).",".$limit;
					$params[]=$order;
					//print_r($params);
					$result2=$connection->query($sql,$params);
					foreach($result2 as $key=>$value){
						foreach($this->columns as $key2=>$value2){
							if($value2["display"]){
								if($value2["link"]){
									//$result_temp[$key2]="<td>".$foo($value[$key2],$value,$key2)."</td>";
									$result2[$key][$key2]=$foo($value[$key2],$value,$key2);
									if($result2[$key][$key2]==="<#!jumprow!#>"){
										unset($result2[$key]);
										break;
									}
								}else{
									//$result_temp[$key2]="<td>'+v.".$value[$key2]."+'</td>";
									$result2[$key][$key2]=$value[$key2];
								}
							}
						}
					}
				break;
				case "sort":
					$search_params=json_decode($_POST["str"]);
					$flag=0;
					$optimized_columns = $this->original_columns;
					$optimized_search_params = get_object_vars($search_params);
					foreach($optimized_search_params as $key=>$value){
						if($value!=""){
							$flag=1;
						}else{
							foreach($optimized_columns as $columnKey=>$columnValue){
								if(isset($columnValue["mysqlas"]) && $columnValue["mysqlas"] == $key){
									unset($optimized_search_params[$key]);
									unset($optimized_columns[$columnKey]);
								}
							}
						}
					}
					$params=array();
					$i_params=0;
					if($flag){
						if(!$this->isSearchFull){
							$search_str=$search_params->search;
							$sql2=$this->searchSql($this->original_columns,$i_params);
							$place = stripos($sql, 'where', 0);
							if($place===false){
								$sql.=$sql2;
							}else{
								$sql = substr_replace($sql, " ".$sql2." and ", $place, 5);
							}
							for($i=0;$i<$i_params;$i++){
								$params[]=$search_str;
							}
						}else{
							$sql2=$this->searchSqlAnd($optimized_columns, $i_params);
							$place = stripos($sql, 'where', 0);
							if($place===false){
								$sql.=$sql2;
							}else{
								$sql = substr_replace($sql, " ".$sql2." and ", $place, 5);
							}
							// $search_params=json_decode($_POST["str"]);
							foreach($optimized_search_params as $key=>$value){
								$params[]=$value;
							}
							for($i=count($search_params);$i<$i_params;$i++){
								//$params[]="";
							}
						}
					}
					$sorting=json_decode($_POST["sort"]);
					$order=$this->sortOrder($table,$sorting);
					$place = $order=="" ? false : stripos($sql, 'order by', 0);
					if($place===false){
						$sql.=" ? ";
					}else{
						$sql = substr_replace($sql, " ?, ", $place, 8);
					}
					$params[]=$order;
					$sql.=" limit ".($limit*$_POST["page"]).",".$limit;
					$result2=$connection->query($sql,$params);
					foreach($result2 as $key=>$value){
						foreach($this->columns as $key2=>$value2){
							if($value2["display"]){
								if($value2["link"]){
									//$result_temp[$key2]="<td>".$foo($value[$key2],$value,$key2)."</td>";
									$result2[$key][$key2]=$foo($value[$key2],$value,$key2);
									if($result2[$key][$key2]==="<#!jumprow!#>"){
										unset($result2[$key]);
										break;
									}
								}else{
									//$result_temp[$key2]="<td>'+v.".$value[$key2]."+'</td>";
									$result2[$key][$key2]=$value[$key2];
								}
							}
						}
					}
				break;
				case "delete":
					if(isset($_POST["id"])){
						try{
							$paramsFoo=array("type"=>$_POST["type"],"id"=>$_POST["id"],"key"=>$_POST["str"],
							"table"=>$table,"page"=>$_POST["page"]);
							if($this->preDelete!=null){
								$preDelete=$this->preDelete;
								$preDelete($paramsFoo);
							}
							$connection->query("delete from ? where ?='?'",$table,$_POST["str"],$_POST["id"]);
							if($this->postDelete!=null){
								$postDelete=$this->postDelete;
								$postDelete($paramsFoo);
							}
						}catch(Exception $e){
							$data["error"]=$e->getMessage();
						}
						$sql.=" limit ".($limit*$_POST["page"]).",".$limit;
						$result2=$connection->query($sql);
						foreach($result2 as $key=>$value){
							foreach($this->columns as $key2=>$value2){
								if($value2["display"]){
									if($value2["link"]){
										//$result_temp[$key2]="<td>".$foo($value[$key2],$value,$key2)."</td>";
										$result2[$key][$key2]=$foo($value[$key2],$value,$key2);
										if($result2[$key][$key2]==="<#!jumprow!#>"){
											unset($result2[$key]);
											break;
										}
									}else{
										//$result_temp[$key2]="<td>'+v.".$value[$key2]."+'</td>";
										$result2[$key][$key2]=$value[$key2];
									}
								}
							}
						}
					}
				break;
				case "excel":
					$result2=$connection->query($sql);
					foreach($result2 as $key=>$value){
						foreach($this->columns as $key2=>$value2){
							if($value2["display"]){
								if($value2["link"]){
									$result2[$key][$key2]=$foo($value[$key2],$value,$key2);
									if($result2[$key][$key2]==="<#!jumprow!#>"){
										unset($result2[$key]);
										break;
									}
								}else{
									$result2[$key][$key2]=$value[$key2];
								}
							}
						}
					}
					$this->result=$result2;
					include($html->sistemComponentView($this->template_excel));
				break;
				case "pdf":
					$result2=$connection->query($sql);
					foreach($result2 as $key=>$value){
						foreach($this->columns as $key2=>$value2){
							if($value2["display"]){
								if($value2["link"]){
									$result2[$key][$key2]=$foo($value[$key2],$value,$key2);
									if($result2[$key][$key2]==="<#!jumprow!#>"){
										unset($result2[$key]);
										break;
									}
								}else{
									$result2[$key][$key2]=$value[$key2];
								}
							}
						}
					}
					$this->result=$result2;
					include($html->sistemComponentView($this->template_pdf));
				break;
				case "excelSearch":
					$search_params = json_decode($_POST["str"]);
					$flag = 0;
					foreach($search_params as $key=>$value)
					{
						if($value!=""){
							$flag=1;
						}
					}

					$params = array();
					$i_params = 0;
					if($flag)
					{
						$search_params=json_decode($_POST["str"]);
						if(!$this->isSearchFull){
							$search_str=$search_params->search;
							$sql2=$this->searchSql($this->original_columns,$i_params);
							$place = stripos($sql, 'where', 0);
							if($place===false){
								$sql.=$sql2;
							}else{
								$sql = substr_replace($sql, " ".$sql2." and ", $place, 5);
							}
							for($i=0;$i<$i_params;$i++){
								$params[]=$search_str;
							}
						}else{
							$sql2=$this->searchSqlAnd($this->original_columns,$i_params);
							$place = stripos($sql, 'where', 0);
							if($place===false){
								$sql.=$sql2;
							}else{
								$sql = substr_replace($sql, " ".$sql2." and ", $place, 5);
							}
							$search_params=json_decode($_POST["str"]);
							foreach($search_params as $key=>$value){
								$params[]=$value;
							}
							for($i=count($search_params);$i<$i_params;$i++){
								//$params[]="";
							}
						}
					}
					$sorting=json_decode($_POST["sort"]);
					$order=$this->sortOrder($table,$sorting);
					$place = $order=="" ? false : stripos($sql, 'order by', 0);
					if($place===false){
						$sql.=" ? ";
					}else{
						$sql = substr_replace($sql, " ?, ", $place, 8);
					}
					//$sql.=" limit ".($limit*$_POST["page"]).",".$limit;
					$params[]=$order;
					//print_r($params);
					/*$result2=$connection->query($sql,$params);
					foreach($result2 as $key=>$value){
						foreach($this->columns as $key2=>$value2){
							if($value2["display"]){
								if($value2["link"]){
									//$result_temp[$key2]="<td>".$foo($value[$key2],$value,$key2)."</td>";
									$result2[$key][$key2]=$foo($value[$key2],$value,$key2);
									if($result2[$key][$key2]==="<#!jumprow!#>"){
										unset($result2[$key]);
										break;
									}
								}else{
									//$result_temp[$key2]="<td>'+v.".$value[$key2]."+'</td>";
									$result2[$key][$key2]=$value[$key2];
								}
							}
						}
					}*/
					$this->result=$result2;
					include($html->sistemComponentView($this->template_excel));
				break;
				case "update":
					if(isset($_POST["id"])&&isset($_POST["id"])){
						try{
							$paramsFoo=array("val"=>$_POST["val"],"type"=>$_POST["type"],"id"=>$_POST["id"],"key"=>$_POST["str"],
							"table"=>$table,"page"=>$_POST["page"]);
							if($this->preUpdate!=null){
								$preUpdate=$this->preUpdate;
								$preUpdate($paramsFoo);
							}
							$connection->query("update `?` set`?`='?' where `?`='?'",
							$table,$_POST["str"],$_POST["val"],$_POST["type"],$_POST["id"]);
							if($this->postUpdate!=null){
								$postUpdate=$this->postUpdate;
								$postUpdate($paramsFoo);
							}
						}catch(Exception $e){
							$data["error"]=$e->getMessage();
						}
						$sql.=" limit ".($limit*$_POST["page"]).",".$limit;
						$result2=$connection->query($sql);
						//print_r($result2);
						foreach($result2 as $key=>$value){
							foreach($this->columns as $key2=>$value2){
								if($value2["display"]){
									if($value2["link"]){
										//$result_temp[$key2]="<td>".$foo($value[$key2],$value,$key2)."</td>";
										$result2[$key][$key2]=$foo($value[$key2],$value,$key2);
										if($result2[$key][$key2]==="<#!jumprow!#>"){
											unset($result2[$key]);
											break;
										}
									}else{
										//$result_temp[$key2]="<td>'+v.".$value[$key2]."+'</td>";
										$result2[$key][$key2]=$value[$key2];
									}
								}
							}
						}
					}
				break;
			}

			//print_r($result_temp);
			$params=array();
			$sql=$this->condition;
			if(isset($_POST["str"])&&$_POST["str"]!=""&&($_POST["action"]=="search"||
				$_POST["action"]=="pagination"||$_POST["action"]=="sort")){
				$i_params=0;
				$search_params=json_decode($_POST["str"]);
				$params=array();
				$i_params=0;
				if($flag){
					if(!$this->isSearchFull){
						$search_str=$search_params->search;
						$sql2=$this->searchSql($this->original_columns,$i_params);
						$place = stripos($sql, 'where', 0);
						if($place===false){
							$sql.=$sql2;
						}else{
							$sql = substr_replace($sql, " ".$sql2." and ", $place, 5);
						}
						for($i=0;$i<$i_params;$i++){
							$params[]=$search_str;
						}
					}else{
						$sql2=$this->searchSqlAnd($optimized_columns,$i_params);
						$place = stripos($sql, 'where', 0);
						if($place===false){
							$sql.=$sql2;
						}else{
							$sql = substr_replace($sql, " ".$sql2." and ", $place, 5);
						}
						// $search_params=json_decode($_POST["str"]);
						foreach($optimized_search_params as $key=>$value){
							$params[]=$value;
						}
						for($i=count($search_params);$i<$i_params;$i++){
							$params[]="";
						}
					}
				}
			}
			$data["count"]=$connection->query("select count(count) as count from (select
			".$this->count_id." as count
			from ".$table." ".$sql.") as t",$params);
			if(isset($data["count"][0]["count"]))
				$data["count_".$this->bodyId]=$data["count"][0]["count"];
			else
				$data["count_".$this->bodyId]=0;
			$this->num=$data["count_".$this->bodyId];
			$data["page_result_".$this->bodyId]=$result2;
			$this->result=array_merge($this->result,$result2);
			$this->data=$data;
			//json_encode($data);
		}else if(isset($_POST["ajax"])&&isset($_POST["action"])&&$_POST["ajax"]==1){
			$sql.=" limit ".($limit*$_POST["page"]).",".$limit;
			$result2=$connection->query($sql);
			foreach($result2 as $key=>$value){
				foreach($this->columns as $key2=>$value2){
					if($value2["display"]){
						if($value2["link"]){
							//$result_temp[$key2]="<td>".$foo($value[$key2],$value,$key2)."</td>";
							$result2[$key][$key2]=$foo($value[$key2],$value,$key2);
							if($result2[$key][$key2]==="<#!jumprow!#>"){
								unset($result2[$key]);
								break;
							}
						}else{
							//$result_temp[$key2]="<td>'+v.".$value[$key2]."+'</td>";
							$result2[$key][$key2]=$value[$key2];
						}
					}
				}
			}
			$params=array();
			$sql=$this->condition;
			$data["count"]=$connection->query("select count(count) as count from (select
			".$this->count_id." as count
			from ".$table." ".$sql.") as t",$params);
			if(isset($data["count"][0]["count"]))
				$data["count_".$this->bodyId]=$data["count"][0]["count"];
			else
				$data["count_".$this->bodyId]=0;
			$this->num=$data["count_".$this->bodyId];
			$data["page_result_".$this->bodyId]=$result2;
			$this->result=array_merge($this->result,$result2);
			$this->data=$data;
		}else{
			$data["count"]=$connection->query("select count(count) as count from (select
			".$this->count_id." as count
			from ".$table." ".$this->condition.") as t");
			if(isset($data["count"][0]["count"]))
				$data["count_".$this->bodyId]=$data["count"][0]["count"];
			else
				$data["count_".$this->bodyId]=0;
			$this->num=$data["count_".$this->bodyId];
			$sql.=" limit ".$limit;
			$this->result=array_merge($this->result,$connection->query($sql));
			$data["page_result_".$this->bodyId]=$this->result;
			$this->data=$data;
		}
		//print_r($this->result);
	}
	function populateFromBuilder($sqlbuilder){
		$this->columns=$sqlbuilder->getTrace("columns");
		global $connection;
		$this->result=$connection->query($sqlbuilder->getSql());
	}
	function createTable($foo=null){
		//$this->bodyId=$bodyId;
		global $html;
		if($foo==null){
			$foo=$this->foo;
		}
		if(!is_array($this->result))
			return false;
		ob_start();
		include($html->sistemComponentView($this->template));
		$page = ob_get_contents();
		ob_end_clean();
		echo $page;
	}
	function createTableString($foo=null){
		global $html;
		if($foo==null){
			$foo=$this->foo;
		}
		if(!is_array($this->result))
			return false;
		ob_start();
		include($html->sistemComponentView($this->template));
		$page = ob_get_contents();
		ob_end_clean();
		return $page;
	}
	function createFullTable($foo=null){
		echo createFullTableString($foo);
	}
	function createFullTableString($foo=null){
		global $html;
		global $search;
		global $pagination;
		if($foo==null){
			$foo=$this->foo;
		}
		if(!is_array($this->result))
			return false;
		$search->setAll("search_template_self",$this);

		ob_start();
		include($html->sistemComponentView($this->template));
		$temp1.= ob_get_contents();
		$temp=$search->constructSearchString();
		$temp.=$temp1;
		$pagination->setAll("pagination_template_self",0);
		$temp.=$pagination->makePaginationString($this->getNum(),$this);
		ob_end_clean();

		return $temp;
	}
	function createTableSearchString($foo=null) {
		global $html;
		global $search;
		if($foo==null){
			$foo=$this->foo;
		}
		if(!is_array($this->result))
			return false;
		$search->setAll("search_template_self",$this);

		ob_start();
		include($html->sistemComponentView($this->template));
		$temp1.= ob_get_contents();
		$temp=$search->constructSearchString();
		$temp.=$temp1;
		ob_end_clean();

		return $temp;
	}
	function createTablePaginationString($foo=null){
		global $html;
		global $search;
		global $pagination;
		if($foo==null){
			$foo=$this->foo;
		}
		if(!is_array($this->result))
			return false;
		ob_start();
		include($html->sistemComponentView($this->template));
		$temp1.= ob_get_contents();
		$temp="";
		$temp.=$temp1;
		$pagination->setAll("pagination_template_self",0);
		$temp.=$pagination->makePaginationString($this->getNum(),$this);
		ob_end_clean();
		return $temp;
	}
	function create($foo=null){
		return $this->createFullTableString($foo);
	}
	function createFullString($foo=null){
		return $this->createFullTableString($foo);
	}
	function getLast(){
		if(empty($this->objects)){
			return $this;
		}else{
			return end($this->objects);
		}
	}
	function getTotal(){
		return count($this->objects);
	}
	function getNum(){
		return $this->num;
	}
	function getData(){
		return $this->data;
	}
	function getColumns(){
		return $this->columns;
	}
	function getResult(){
		return $this->result;
	}
	function getBodyId() {
		return $this->bodyId;
	}
	function setBodyId($bodyId) {
		$this->bodyId = $bodyId;
	}
	function setPreUpdate($preUpdate){
		$this->preUpdate=$preUpdate;
	}
	function setPostUpdate($postUpdate){
		$this->postUpdate=$postUpdate;
	}
	function setPreDelete($preDelete){
		$this->preDelete=$preDelete;
	}
	function setPostDelete($postDelete){
		$this->postDelete=$postDelete;
	}
	function setExcelTemplate($template){
		$this->template_excel=$template;
	}
	function setPDFTemplate($template){
		$this->template_pdf=$template;
	}
	function setDownloadFileName($name){
		$this->download_file_name=$name;
	}
	function setBreadcrumb($name){
		$this->breadcrumb=$name;
	}
	function setLimit($limit){
		$this->limit=$limit;
	}
	function sortOrder($table,$sorting){
		$order=" order by ";
		$i=0;
		foreach($sorting as $key=>$value){
			if($value==1){
				if($i!=0){
					$order.=",";
				}
				//$order.=$table.".".$key." desc ";
				//$order.=$key." desc ";
				$order.=$this->columns[$key]["original_key"]." desc ";
				$i++;
			}else if($value==2){
				if($i!=0){
					$order.=",";
				}
				//$order.=$table.".".$key." asc ";
				//$order.=$key." asc ";
				$order.=$this->columns[$key]["original_key"]." asc ";
				$i++;
			}
		}
		if($i==0){
			return "";
		}else{
			return $order;
		}
	}
	function searchSql($columns,&$i_params){
		$sql2="";
		$sql2.=" where (";
		foreach($columns as $key2=>$value2){
			if(isset($value2["search"])&&$value2["search"]){
				if($i_params==0){
					$sql2.=" ".$key2." like '%?%' ";
				}else{
					$sql2.=" or ".$key2." like '%?%' ";
				}
				$i_params++;
			}
		}
		$sql2.=" ) ";
		return $sql2;
	}
	function searchSqlAnd($columns,&$i_params){
		$sql2="";
		$sql2.=" where (";
		foreach($columns as $key2=>$value2){
			if(isset($value2["searchfull"])&&$value2["searchfull"]){
				if($i_params==0){
					$sql2.=" ".$key2." like '%?%' ";
				}else{
					$sql2.=" and ".$key2." like '%?%' ";
				}
				$i_params++;
			}
		}
		$sql2.=" ) ";
		return $sql2;
	}
	function funcGen($type,$params){
		switch($type){
			case "link":
				return '<a href="'.$params["link"].'">'.$params["name"].'</a>';
			break;
			case "image":
				return '<img style="height:100px;" src="'.$params["src"].'" alt="'.$params["title"].'">';
			break;
			case "borrar":
				return '<a onclick="delete_'.$this->bodyId.'(event,'.$params["key"].',\''.$params["column"].'\');" href="#"><span class="btn btn-danger"><i class="icon-trash icon-white"></i> Borrar</span></a>';
			break;
			case "disabled_borrar":
				return '<span class="btn btn-inverse disabled" disabled="disabled"><i class="icon-trash icon-white"></i> Borrar</span>';
			break;
			case "update":
				return '
					<td id="'.$params["column"].'_'.$params["row"].'">
						<a href="#" color="inherit" onclick="setValue(event,\''.$params["row"].'\',\''.$params["column"].'\');">'.$params["data"].'</a>
					</td>
					<td id="set_'.$params["column"].'_'.$params["row"].'" style="display:none">
						<div class="input-append">
							<input type="text" class="input-mini" id="new_'.$params["column"].'_'.$params["row"].'" name="612" value="'.$params["data"].'" onkeydown="updateKeyPress(this.id);">
							<span style="cursor:pointer;" onclick="updateValue_'.$this->bodyId.'(event,\''.$params["row"].'\',\''.$params["column"].'\');" class="color-icons accept_co"></span>
							<span style="cursor:pointer;" onclick="unsetValue(event,\''.$params["row"].'\',\''.$params["column"].'\');" class="color-icons cross_co"></span>
						</div>
					</td>
				';
			break;
			case "select":
				return '<a onclick="searchSelect(event,\''.$params["input_name"].'\',\''.$params["fake_name"].'\',\''.$params["real_value"].'\');" href="#"><button class="btn btn-small"><span class="color-icons accept_co"></span></button></a>';
			break;
			case "popover":
				return '
					<a href="javascript:void(0)" id="popover_'.$this->bodyId.'_'.$params["id"].'">'.$params["name"].'</a>
					<script>
						$(document).ready(function () {
							$(\'#popover_'.$this->bodyId.'_'.$params["id"].'\').popover({
								trigger: \'hover\',
								placement: \'right\',
								title: \''.$params["title"].'\',
								html: \'true\',
								content: \'<div style="text-align:center;"><img src="/'.$params["src"].'" /></div>\'
							});
						});
					</script>
				';
			break;
			case "id_btn":
				return '<a href="'.$params["link"].'"><button type="button" class="btn '.$params["btn_style"].' btn-info" style="min-width:4em;"><i class="icon-info-sign icon-white"></i> '.$params["name"].'</button></a>';
			break;
			case "name_btn":
				return '<a href="'.$params["link"].'"><button type="button" class="btn '.$params["btn_style"].' btn-info" style="min-width:4em;">'.$params["name"].'</button></a>';
			break;
			case "image_cbox":
				return '<a href="javascript:void(0)" onclick="openImage(event,\''.$params["src"].'\')"><img height="42" width="42" src="/'.$params["src"].'"></a>';
			break;
			case "update_btn":
				return '
					<td id="'.$params["column"].'_'.$params["row"].'">
						<button type="button" class="btn btn-info" style="padding:0.3em 1em;font-size:1.1em;min-width:3.5em;" onclick="setValue(event,\''.$params["row"].'\',\''.$params["column"].'\');">'.$params["data"].'</button>
					</td>
					<td id="set_'.$params["column"].'_'.$params["row"].'" style="display:none">
						<div class="input-append">
							<input type="text" class="input-mini" id="new_'.$params["column"].'_'.$params["row"].'" name="612" value="'.$params["data"].'" onkeydown="updateKeyPress(this.id);">
							<span style="cursor:pointer;" onclick="updateValue_'.$this->bodyId.'(event,\''.$params["row"].'\',\''.$params["column"].'\');" class="color-icons accept_co"></span>
							<span style="cursor:pointer;" onclick="unsetValue(event,\''.$params["row"].'\',\''.$params["column"].'\');" class="color-icons cross_co"></span>
						</div>
					</td>
				';
			break;
			case "btn_link":
				return '

				';
			break;
			case "show_table":
				global $html;
				return '
					<button type="button" id="ver_'.$params["id"].'" class="btn btn-info" style="padding:0.3em 1em;font-size:1.1em;min-width:3.5em;">'.$params["name"].'</button>
					<script type="text/javascript">
						$("#ver_'.$params["id"].'").click(function() {
							$.post(
								"'.$params["ajax"].'",
								{
									key: "show_table",
									id: "'.$params["id"].'"
								},
								function(data) {
									$.colorbox({
										width: "'.$params["width"].'",
										html: data.html
									});
								},
								"json"
							);
						});
					</script>
				';
			break;
			case "update_btn_ajax":
				return '
					<td id="'.$params["id"].'">
						<button type="button" class="btn btn-info" style="padding:0.3em 1em;font-size:1.1em;min-width:3.5em;" onclick="'.$params["ajax_btn"].'">'.$params["fake_data"].'</button>
					</td>
					<td id="set_'.$params["id"].'" style="display:none">
						<div class="input-append">
							<input type="text" class="input-mini" id="new_'.$params["id"].'" name="'.$params["id"].'" value="'.$params["data"].'" onkeydown="updateKeyPress(this.id);">
							<span style="cursor:pointer;" onclick="'.$params["ajax_set"].'" class="color-icons accept_co"></span>
							<span style="cursor:pointer;" onclick="'.$params["ajax_cancel"].'" class="color-icons cross_co"></span>
						</div>
					</td>
				';
			break;
			case "popover_btn":
				return '
					<span id="popover_'.$this->bodyId.'_'.$params["id"].'" class="label label-info" style="padding:0.5em 1em;font-size:1.1em;min-width:3.5em;cursor:default;font-weight:normal;">'.$params["name"].'</span>
					<script>
						$(document).ready(function () {
							$(\'#popover_'.$this->bodyId.'_'.$params["id"].'\').popover({
								trigger: \'hover\',
								placement: \'right\',
								title: \''.$params["title"].'\',
								html: \'true\',
								content: \'<div style="text-align:center;"><img src="/'.$params["src"].'" /></div>\'
							});
						});
					</script>
				';
			break;
		}
		return "";
	}
}

?>
