<?php
class chart_class
{
	private $type 			=	"";
	private $c_data 		= 	[];
	private $options 		= 	[];
	private $config 		=	[];
	private $width 			=	"";
	private $height 		=	"";
	private $nextCreated 	=	false;
	private $update 		= 	0;

	public $objects 		= 	[];
	public $template 		=	[];
	public $page_name 		=	"";
	public $clx_id 			=	"";

	function __construct($template) {
		$this->template = $template;
	}

	function nextChart($filename, $template = null)
	{
		if($template==null)
		{
			$this->objects[]=new chart_class($this->template);
		}else{
			$this->objects[]=new chart_class($template);
		}

		$key = key( array_slice( $this->objects, -1, 1, TRUE ) );
		$this->objects[$key]->page_name = $filename;
		$this->objects[$key]->clx_id = "clx_".$key;
		$this->objects[$key]->nextCreated = true;
	}

	function populate($c_data,$type = null,$dimension = null,$options = null,$config = null,$clx_id = null) {

		global $html;
		global $data;

		$this->c_data = $c_data;

		if (!$type == null) {
			$this->type = $type;
		} else {
			$this->type = '"line"';
		}

		if (!$dimension == null && is_array($dimension)) {
			$this->width  = $dimension["width"];
			$this->height = $dimension["height"];
		}

		if (!$options == null) {
			$this->options = $options;
		} else {
			$this->options = '{}';
		}

		if (!$config == null) {
			$this->config = $config;
		}

		if(!$this->nextCreated)
		{
			if($clx_id != null)
			{
				$this->clx_id = $clx_id;
			}
			if($page_name!=null){
				$this->page_name=$page_name;
			}
		}

	}

	function createFullChart() {
		global $html;

		ob_start();
		include($html->sistemComponentView($this->template));
		$temp= ob_get_contents();
		ob_end_clean();

		return $temp;
	}

	function create() {
		return $this->createFullChart();
	}

	function c_data() {
		var_dump($this->c_data);
	}

	function getLast(){
		if(empty($this->objects)){
			return $this;
		}else{
			return end($this->objects);
		}
	}

	function dimensions() {
		echo $this->width;
		echo "<br />";
		echo $this->height;
	}

	function getChartId() {
		return $this->clx_id;
	}
	
	function getData() {
		return $this->c_data;
	}
	
	function getOptions() {
		return $this->options;
	}

	// function update() {
	// 	global $html;
	//
	// 	$this->update = 1;
	//
	// 	ob_start();
	// 	include($html->sistemComponentView($this->template));
	// 	$temp= ob_get_contents();
	// 	ob_end_clean();
	//
	// 	$this->update = 0;
	// }

}

?>
