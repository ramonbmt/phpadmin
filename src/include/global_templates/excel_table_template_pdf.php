<?php
	//$rendererName = PHPExcel_Settings::PDF_RENDERER_TCPDF;
	//$rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
	$rendererName = PHPExcel_Settings::PDF_RENDERER_DOMPDF;
	//$rendererLibrary = 'tcPDF5.9';
	//$rendererLibrary = 'mPDF5.4';
	//$rendererLibrary = 'mpdf60';
	//$rendererLibrary = 'domPDF0.6.0beta3';
	$rendererLibrary = 'dompdf_0-6-0_beta3';
	$rendererLibraryPath = 'include/' . $rendererLibrary;

	$objPHPExcel = new PHPExcel();
	// Set document properties
	$objPHPExcel->getProperties()->setCreator("Althea")
								 ->setLastModifiedBy("Althea")
								 ->setTitle("Catalogo")
								 ->setSubject("Catalogo")
								 ->setDescription("Catalogo.")
								 ->setKeywords("Catalogo Althea")
								 ->setCategory("Catalogo Althea");



//imagen xlsx
/*$gdImage = imagecreatefromjpeg('img/Logo horizontal.jpg');
 $objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
 $objDrawing->setName('Sample image');$objDrawing->setDescription('Sample image');
 $objDrawing->setImageResource($gdImage);
 $objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG);
 $objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
 $objDrawing->setHeight(150);
 $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
 $objDrawing->setCoordinates('A1');*/
$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(60);
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('PHPExcel logo');
$objDrawing->setDescription('PHPExcel logo');
$objDrawing->setPath('img/logo-horizontal.png', false);       // filesystem reference for the image file
//$objDrawing->setHeight(180);                 // sets the image height to 36px (overriding the actual image height);
$objDrawing->setCoordinates('A1');    // pins the top-left corner of the image to cell D24
//$objDrawing->setOffsetX(10);                // pins the top left corner of the image at an offset of 10 points horizontally to the right of the top-left corner of the cell
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

	$column = 'A';
	foreach($this->breadcrumb as $key=>$value){
		$objPHPExcel->getActiveSheet()->setCellValue($column.(0+3),$value);
		$column++;
		//$objPHPExcel->getActiveSheet()->setCellValue(('A'+$key)."1", $value);
	}

	//$objPHPExcel->getActiveSheet()->setCellValue('A1', "Datos");
	$row = 4;
	$column = 'A';
	foreach($this->columns as $key=>$value){
		if(!$value["display"]&&(!isset($value["excel"]))) continue;
		if((isset($value["excel"])&&!$value["excel"])) continue;
		$objPHPExcel->getActiveSheet()->setCellValue($column.$row, $value["as"]);
		$column++;
	}
	$i_create=0;
	$temp="";
	$row=5;
	//print_r($this->result);
	foreach($this->result as $key=>$value){
		$column = 'A';
		foreach($this->columns as $key2=>$value2){
			if($value2["display"]){
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
				if((isset($value2["excel_jump"])&&$value2["excel_jump"])) $temp="";
				$objPHPExcel->getActiveSheet()->setCellValue($column.($key+$row), strip_tags($temp));
				$column++;
			}
		}
	}

	$objPHPExcel->getActiveSheet()->setTitle('Datos');

	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);

	/*$objPHPExcel->getDefaultStyle()
    ->getBorders()
    ->getTop()
  	->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$objPHPExcel->getDefaultStyle()
    ->getBorders()
    ->getBottom()
    ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$objPHPExcel->getDefaultStyle()
    ->getBorders()
    ->getLeft()
    ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$objPHPExcel->getDefaultStyle()
    ->getBorders()
    ->getRight()
  	->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);*/
		/** Borders for all data */

		$objPHPExcel->getActiveSheet()->getStyle(
		 'B1:' .
		 $objPHPExcel->getActiveSheet()->getHighestColumn() .
		'2'
		)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_NONE);

   $objPHPExcel->getActiveSheet()->getStyle(
    'A3:' .
    $objPHPExcel->getActiveSheet()->getHighestColumn() .
    $objPHPExcel->getActiveSheet()->getHighestRow()
)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);





	if (!PHPExcel_Settings::setPdfRenderer(
			$rendererName,
			$rendererLibraryPath
		)) {
		die(
			'NOTICE: Please set the $rendererName and $rendererLibraryPath values' .
			'<br />' .
			'at the top of this script as appropriate for your directory structure'
		);
	}
	//filename
	$filename="punto-de-venta.pdf";
	if(isset($this->download_file_name)&&$this->download_file_name!=null){
		$filename=$this->download_file_name.".pdf";
	}

	// Redirect output to a client�s web browser (Excel2007)
	header('Content-Type: application/pdf');
	header('Content-Disposition: attachment;filename="'.$filename.'"');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');

	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
	$objWriter->save('php://output');
	die();
?>
