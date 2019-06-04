<?php
class template_class extends controller_class{
	public $template=null;
	public $args=null;
	function __construct($template){
		$this->template = $template;
	}
	function createTemplate($foo=null){
		global $html;
		ob_start();
		$foo();
		global $breadcrumb;
		include($html->templateView($this->template));
		$page = ob_get_contents();
		ob_end_clean();
		echo $page;
	}
}
?>