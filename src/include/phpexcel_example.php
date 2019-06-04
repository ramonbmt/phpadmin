<?php

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once 'PHPExcel.php';


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("BMT - Callcenter")
							 ->setLastModifiedBy("BMT - Callcenter")
							 ->setTitle("Reporte de metricas")
							 ->setSubject("Reporte de metricas")
							 ->setDescription("Reporte de metricas.")
							 ->setKeywords("reporte metricas bmt callcenter")
							 ->setCategory("reporte metricas");


// Add some data
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Hello')
            ->setCellValue('B2', 'world!')
            ->setCellValue('C1', 'Hello')
            ->setCellValue('D2', 'world!');
$objPHPExcel->getActiveSheet()->setTitle('Reporte 1');
// Miscellaneous glyphs, UTF-8
$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(1)
            ->setCellValue('A4', 'Miscellaneous glyphs')
            ->setCellValue('A5', '�����������������');

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Reporte 2');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client�s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="01simple.xlsx"');
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
exit;
?>