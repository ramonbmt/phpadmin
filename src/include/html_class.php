<?php

class html_class{

public $file;
public $link;
public $config;
public $pages;

	function __construct($config, $pages){
		$this->config = $config;
		$this->pages = $pages;
	}

	function echoLink($file){
		$path = array_search($file.'.php', $this->pages);
		echo $this->config['domain'].'/'.$path;
		return;
	}

	function link($file, $zone=null, $campain_id=null){
		if($zone==null||$zone=='home'){
			$path = array_search($file.'.php', $this->pages);
			return $this->config['domain'].'/'.$path;
		}
		if($zone=='ajax'){
			$path = array_search($file.'.php', $this->pages[$zone]);
			return $this->config['domain'].'/ajax/'.$path;
		}
		if($zone=='admin'){
			global $connection;
			$path = array_search($file.'.php', $this->pages[$zone]);
			return $this->config['domain'].'/campana/'.$campain_id.'/'.$path;

		}
	}

	function componentView($component){
		return "app/components/view/$component.php";
	}
	function getComponentView($component){
		ob_start();
		include("app/components/view/$component.php");
		$component = ob_get_contents();
		ob_end_clean();
		return $component;
	}
	function sistemComponentView($component){
		if(file_exists("include/global_templates/$component.php")){
			return "include/global_templates/$component.php";
		} else {
			return "app/custom_templates/$component.php";
		}
	}
	function componentLogic($component){
		return "app/components/logic/$component.php";
	}
	function templateView($page){
		return "app/templates/view/$page.php";
	}
	function tempalteLogic($page){
		return "app/templates/logic/$page.php";
	}

	function getPage($file, $zone=null){
		if($zone==null){
			$path = array_search($file.'.php', $this->pages);
			return $path;
		}else if($zone=='ajax'){
			$path = array_search($file.'.php', $this->pages[$zone]);
			return $path;
		}else if($zone=='admin'){
			$path = array_search($file.'.php', $this->pages[$zone]);
			return $path;
		}
	}

	function getPageFile($file, $zone=null){
		if($zone==null){
			$path = $this->pages[$file];
			return $path;
		}

	}
	function makePagination($table, $limitations, $page, $n_show=10){
		global $connection;
		$resul_table=$connection->query("show tables like '?'",$table);
		if(count($result_table)>0){
			$result_pagination=$connection->query("select count(id) from ? where ?",
			$table,$limitations);
			echo $result_table;
		}
	}

}

$html = new html_class($config, $pages);


?>
