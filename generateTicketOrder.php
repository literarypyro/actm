<?php
session_start();
?>
<?php
require_once("phpexcel/Classes/PHPExcel.php");
require_once("phpexcel/Classes/PHPExcel/IOFactory.php");
require("excel functions.php");
$startDate=$_GET['startDate'];
$endDate=$_GET['endDate'];
$viewType=$_GET['view'];


$transaction_id=$_GET['trans'];
//$ticket_order_id=$_GET['ticket'];
?>
<?php
/*
	$grid["denom_1000"]=10;
	$grid["denom_500"]=11;
	$grid["denom_200"]=12;
	$grid["denom_100"]=13;
	$grid["denom_50"]=14;
	$grid["denom_20"]=15;
	$grid["denom_10"]=16;
	$grid["denom_5"]=17;
	$grid["denom_1"]=18;
	$grid["denom_.25"]=19;
	$grid["denom_.10"]=20;
	$grid["denom_.05"]=21;
*/

	$dateSlip=date("Y-m-d His");

	$filename="treasury forms/Ticket Order Form.xls";

	$newFilename="printout/Ticket Order_".$dateSlip.".xls";
	copy($filename,$newFilename);
	$workSheetName="Ticket Order";	
	$workbookname=$newFilename;
	$excel=loadExistingWorkbook($workbookname);

  	$ExWs=createWorksheet($excel,$workSheetName,"openActive");
	
	$db=new mysqli("localhost","root","","finance");

	$sql="select * from transaction where transaction_id='".$transaction_id."'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	if($nm>0){
		$row=$rs->fetch_assoc();
		$log_type=$row['log_type'];
		$transaction_date=date("m/d/Y",strtotime($row['date']));	
		
		
		$sql3="select * from ticket_order where transaction_id='".$transaction_id."'";
		$rs3=$db->query($sql3);
		$row3=$rs3->fetch_assoc();
		$reference_id=$row3['reference_id'];
		$station=$row3['station'];
		
		$sjt=$row3['sjt'];
		$sjd=$row3['sjd'];
		$svt=$row3['svt'];
		$svd=$row3['svd'];

		$sjt_loose=$row3['sjt_loose'];
		$sjd_loose=$row3['sjd_loose'];
		$svt_loose=$row3['svt_loose'];
		$svd_loose=$row3['svd_loose'];
		
		
		$loginSQL="select * from login where username='".$row3['cash_assistant']."'";
		$loginRS=$db->query($loginSQL);
		$loginRow=$loginRS->fetch_assoc();
		$cash_assistant=ucfirst(strtolower($loginRow['lastName'])).", ".ucfirst(strtolower($loginRow['firstName']));
		
		$tsSQL="select * from ticket_seller where id='".$row3['ticket_seller']."'";
		$tsRS=$db->query($tsSQL);
		$tsRow=$tsRS->fetch_assoc();
		$ticket_seller=ucfirst(strtolower($tsRow['last_name'])).", ".ucfirst(strtolower($tsRow['first_name']));
		

		
		if($log_type=="annex"){
			addContent(setRange("B8","B8"),$excel,"x","true",$ExWs);
		
		
		}
		else {
			addContent(setRange("B9","B9"),$excel,"x","true",$ExWs);
		
		
		
			$sql2="select * from station where id='".$station."'";
			$rs2=$db->query($sql2);
			$row2=$rs2->fetch_assoc();
			$station_name=$row2['station_name'];
			
			addContent(setRange("E9","M9"),$excel,$station_name,"true",$ExWs);
			
			addContent(setRange("D12","M12"),$excel,$ticket_seller,"true",$ExWs);
		
		}
		addContent(setRange("Q10","U10"),$excel,$transaction_date,"true",$ExWs);
		addContent(setRange("S8","U8"),$excel,$reference_id,"true",$ExWs);

		addContent(setRange("D11","M11"),$excel,$cash_assistant,"true",$ExWs);
		
		addContent(setRange("F17","J17"),$excel,$sjt,"true",$ExWs);
		addContent(setRange("F18","J18"),$excel,$sjd,"true",$ExWs);
		addContent(setRange("F19","J19"),$excel,$svt,"true",$ExWs);
		addContent(setRange("F20","J20"),$excel,$svd,"true",$ExWs);
			
		addContent(setRange("K17","P17"),$excel,$sjt_loose,"true",$ExWs);
		addContent(setRange("K18","P18"),$excel,$sjd_loose,"true",$ExWs);
		addContent(setRange("K19","P19"),$excel,$svt_loose,"true",$ExWs);
		addContent(setRange("K20","P20"),$excel,$svd_loose,"true",$ExWs);
		
		
	}
	
	
	
	/*
	$sql="select * from ticket_order where id='".$ticket_order_id."'";
	$rs=$db->query($sql);
	
	$row=$rs->fetch_assoc();
	$cashsql="select * from login where username='".$row['cash_assistant']."'";
	$cashrs=$db->query($cashsql);
	$cashrow=$cashrs->fetch_assoc();
	
	$cash_assistant=$cashrow['firstName']." ".$cashRow['lastName'];

	$stationsql="select * from station where id='".$cashrow['station']."'";
	
	$stationrs=$db->query($stationsql);
	$stationrow=$stationrs->fetch_assoc();
	$station=$stationrow['station_name'];
	addContent(setRange("A7","B7"),$excel,"Station: ".$station,"true",$ExWs);
	*/

	//addContent(setRange("C6","C7"),$excel,"Date: ".date("F d, Y",strtotime($row['time'])),"true",$ExWs);

	/*
	addContent(setRange("C6","C7"),$excel,"Date: ".date("F d, Y",strtotime($row['time'])),"true",$ExWs);
	
	
	addContent(setRange("B23","C24"),$excel,$row['total_in_words'],"true",$ExWs);
	

	$denom="select * from denomination where cash_transfer_id='".$cash_transfer_id."'";
	$rsD=$db->query($denom);
	
	$nm2=$rsD->num_rows;
	
	for($i=0;$i<$nm2;$i++){
		$row2=$rsD->fetch_assoc();
		addContent(setRange("B".$grid["denom_".$row2['denomination']],"B".$grid["denom_".$row2['denomination']]),$excel,$row2['quantity'],"true",$ExWs);
	}
	*/
	

	save($ExWb,$excel,$newFilename); 	
	echo "Ticket Order has been generated!  Press right click and Save As: <a href='".$newFilename."'>Here</a>";
	




?>