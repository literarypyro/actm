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
	
	$newFilename="printout/Cash Logbook  ".$timeStamp.".xls";
	$workSheetName="Cash Logbook";
	$workbookname=$newFilename;
	$excel=startCOMGiven();
	$ExWb=$workbookname;	

	
 	$ExWs=createWorksheet($excel,$workSheetName,"create");		

	$rowCount=1;
	$rowCount+=3;
	
		addContent(setRange("A".$rowCount,"C".$rowCount),$excel,"Particulars","true",$ExWs);
		addContent(setRange("D".$rowCount,"F".$rowCount),$excel,"Cash In","true",$ExWs);
		addContent(setRange("H".$rowCount,"J".$rowCount),$excel,"Cash Out","true",$ExWs);
		addContent(setRange("K".$rowCount,"M".$rowCount),$excel,"Balance","true",$ExWs);
		addContent(setRange("N".$rowCount,"N".$rowCount),$excel,"Remarks","true",$ExWs);
		addContent(setRange("G".$rowCount,"G".($rowCount+1)),$excel,"Short (Over)","true",$ExWs);
	
	$rowCount++;

		addContent(setRange("A".$rowCount,"A".$rowCount),$excel,"Time","true",$ExWs);
		addContent(setRange("B".$rowCount,"B".$rowCount),$excel,"Name","true",$ExWs);
		addContent(setRange("C".$rowCount,"C".$rowCount),$excel,"Id No.","true",$ExWs);


		addContent(setRange("D".$rowCount,"D".$rowCount),$excel,"Revolving Fund","true",$ExWs);
		addContent(setRange("E".$rowCount,"E".$rowCount),$excel,"For Deposit/Net Revenue","true",$ExWs);
		addContent(setRange("F".$rowCount,"F".$rowCount),$excel,"Total","true",$ExWs);
	

		addContent(setRange("H".$rowCount,"H".$rowCount),$excel,"CA/Tse Rev. Fund","true",$ExWs);
		addContent(setRange("I".$rowCount,"I".$rowCount),$excel,"PNB Deposit","true",$ExWs);
		addContent(setRange("J".$rowCount,"J".$rowCount),$excel,"Total","true",$ExWs);

		addContent(setRange("K".$rowCount,"K".$rowCount),$excel,"Revolving Fund","true",$ExWs);
		addContent(setRange("L".$rowCount,"L".$rowCount),$excel,"For Deposit","true",$ExWs);
		addContent(setRange("M".$rowCount,"M".$rowCount),$excel,"Total","true",$ExWs);
	
		addContent(setRange("N".$rowCount,"N".$rowCount),$excel,"(Report Form/Deposit No)","true",$ExWs);
	
		//		addContent(setRange("E".$grid[$i],"E".$grid[$i]),$excel,$loose_good,"true",$ExWs);
	
	
	
	
	save($ExWb,$excel,$newFilename); 
	echo "Report has been generated!  Click Here: <a href='".$newFilename."' style='text-decoration:none;color:red;'>".str_replace("printout/","",$newFilename)."</a>";




?>