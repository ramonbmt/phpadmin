<?php
	$objPHPExcel = new PHPExcel();
	// Set document properties
	$objPHPExcel->getProperties()->setCreator("Althea")
								 ->setLastModifiedBy("Althea")
								 ->setTitle("Catalogo")
								 ->setSubject("Catalogo")
								 ->setDescription("Catalogo.")
								 ->setKeywords("Catalogo Althea")
								 ->setCategory("Catalogo Althea");
	$column = 'A';
	foreach($this->breadcrumb as $key=>$value){
		$objPHPExcel->getActiveSheet()->setCellValue($column.(0+1),$value);
		$column++;
		//$objPHPExcel->getActiveSheet()->setCellValue(('A'+$key)."1", $value);
	}
	//$objPHPExcel->getActiveSheet()->setCellValue('A1', "Datos");
	$row = 3;
	$column = 'A';
	foreach($this->columns as $key=>$value){
		//var_dump((!isset($value["excel"])));
		if(!$value["display"]&&(!isset($value["excel"]))) continue;
		if((isset($value["excel"])&&!$value["excel"])) continue;
		$objPHPExcel->getActiveSheet()->setCellValue($column.$row, $value["as"]);
		$column++;
	}
	$i_create=0;
	$temp="";
	$row=4;
	//print_r($this->result);
	foreach($this->result as $key=>$value){
		$column = 'A';
		foreach($this->columns as $key2=>$value2){
			if($value2["display"]||(isset($value2["excel"]))){
				if(isset($value2["type"])){
					switch($value2["type"]){
						case "money":
							$temp="$".$value[$key2];
						break;
						case "percent":
							$temp=$value[$key2]."%";
						break;
						default:
							$temp=$value[$key2];
						break;
					}
				}else{
					$temp=$value[$key2];
				}
				if((isset($value2["excel"])&&!$value2["excel"])) continue;
				$objPHPExcel->getActiveSheet()->setCellValue($column.($key+$row), strip_tags($temp));
				$column++;
			}
		}
	}

	$objPHPExcel->getActiveSheet()->setTitle('Datos');

	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);

	//filename
	$filename="althea.xlsx";
	if(isset($this->download_file_name)&&$this->download_file_name!=null){
		$filename=$this->download_file_name.".xlsx";
	}

	// Redirect output to a client�s web browser (Excel2007)
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="'.$filename.'"');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');

	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');
	die();
?>
