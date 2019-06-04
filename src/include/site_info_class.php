<?php

class site_class{

private $store_id;
private $zone;
private $page;
private $login;

	function __construct(){
		
	}
	
	function setStoreid($store_id){
		global $html;
		$this->store_id=$store_id;
		if($this->zone!='admin'){
			$login='login';
			$zone='home';
		}else{
			$zone='admin';
			$login='admin_login';
		}
		$this->login=$html->link($login, $zone, $store_id);
	}
	
	function setZone($zone){
		global $html;
		$this->zone=$zone;
		if($this->store_id==null){
			$zone='home';
		}
		if($zone!='admin'){
			$login='login';
		}else{
			$login='admin_login';
		}
		$this->login=$html->link($login, $zone, $this->store_id);
		//echo $this->login;
	}
	
	function setPage($page){
		$this->page=$page;
	}
	
	function getStoreid(){
		return $this->store_id;
	}
	
	function getZone(){
		return $this->zone;
	}
	
	function getPage(){
		return $this->page;
	}
	
	function getLogin(){
		return $this->login;
	}
	
}

$site = new site_class();