<?php
	ini_set('max_execution_time', 60);
	ini_set('memory_limit', '128M');
	$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_sqlite3;
	$cacheSettings = array( 'memoryCacheSize' =>'8MB');
	PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
	$objPHPExcel = new PHPExcel();
	// Set document properties
	$objPHPExcel->getProperties()->setCreator("PuntoDeVenta")
								 ->setLastModifiedBy("PuntoDeVenta")
								 ->setTitle("Reporte")
								 ->setSubject("Reporte")
								 ->setDescription("Reporte.")
								 ->setKeywords("Reporte PuntoDeVenta")
								 ->setCategory("Reporte PuntoDeVenta");
	$column = 'A';
	foreach($this->breadcrumb as $key=>$value){
		$objPHPExcel->getActiveSheet()->setCellValue($column.(0+1),$value);
		$column++;
		//$objPHPExcel->getActiveSheet()->setCellValue(('A'+$key)."1", $value);
	}
	//$objPHPExcel->getActiveSheet()->setCellValue('A1', "Datos");
	$row = 3;
	$column = 'A';
	$sheet=$objPHPExcel->getActiveSheet();
	foreach($this->columns as $key=>$value){
		//var_dump((!isset($value["excel"])));
		if(!$value["display"]&&(!isset($value["excel"]))) continue;
		if((isset($value["excel"])&&!$value["excel"])) continue;
		$sheet->setCellValue($column.$row, $value["as"]);
		$column++;
	}
	$i_create=0;
	$temp="";
	$row=4;
  $ikey=0;
	//print_r($this->result);
	if(!isset($params)){
		$params=[];
	}
  $result=$connection->queryAlone($sql,$params);
  if (!($result === true || $result==false || mysql_num_rows($result) == 0)){

    //foreach($result as $key=>$value){
    while($value = mysql_fetch_assoc($result)){
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
  				$sheet->setCellValue($column.($ikey+$row), strip_tags($temp));

  				$column++;
  			}
  		}
      $ikey++;
  }

    /*foreach($this->columns as $key2=>$value2){
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
    }*/
  }
	/*foreach($this->result as $key=>$value){
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
	}*/

	$objPHPExcel->getActiveSheet()->setTitle('Datos');

	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);

	//filename
	$filename="PuntoDeVenta.csv";
	if(isset($this->download_file_name)&&$this->download_file_name!=null){
		$filename=$this->download_file_name.".csv";
	}

	// Redirect output to a client�s web browser (Excel2007)
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=UTF-8');
	header('Content-Disposition: attachment;filename="'.$filename.'"');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');

	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0
	echo "\xEF\xBB\xBF";

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
	$objWriter->save('php://output');
	die();
?>
