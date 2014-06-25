<?php
session_start();
?>
<?php
require_once("phpexcel/Classes/PHPExcel.php");
require_once("phpexcel/Classes/PHPExcel/IOFactory.php");
require("excel functions.php");
?>
<?php
	$timeStamp=date("Y-m-d His");
	
	$newFilename="printout/SVT Logbook  ".$timeStamp.".xls";
	$workSheetName="SVT Logbook";
	$workbookname=$newFilename;
	$excel=startCOMGiven();
	$ExWb=$workbookname;	
	
 	$ExWs=createWorksheet($excel,$workSheetName,"create");		
	$rowCount=1;

	
	
	
	save($ExWb,$excel,$newFilename); 
	echo "Report has been generated!  Click Here: <a href='".$newFilename."' style='text-decoration:none;color:red;'>".str_replace("printout/","",$newFilename)."</a>";




?>