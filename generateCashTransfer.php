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
$cash_transfer_id=$_GET['cash'];
?>
<?php

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


	$dateSlip=date("Y-m-d His");

	$filename="treasury forms/cash transfer.xls";

	$newFilename="printout/Cash Transfer ".$dateSlip.".xls";
	copy($filename,$newFilename);
	$workSheetName="Cash Transfer";	
	$workbookname=$newFilename;
	$excel=loadExistingWorkbook($workbookname);

  	$ExWs=createWorksheet($excel,$workSheetName,"openActive");
	
	$db=new mysqli("localhost","root","","finance");
	
	
	$sql="select * from cash_transfer where id='".$cash_transfer_id."'";
	$rs=$db->query($sql);
	
	$row=$rs->fetch_assoc();
	$reference_id=$row['reference_id'];
	$transaction_id=$row['transaction_id'];
	
	
	$transactionSQL="select * from transaction where transaction_id='".$transaction_id."'";
	
	$transactionRS=$db->query($transactionSQL);
	$transactionRow=$transactionRS->fetch_assoc();
	$transact_type=$transactionRow['transaction_type'];
	
	$cashsql="select * from login where username='".$row['cash_assistant']."'";
	$cashrs=$db->query($cashsql);
	$cashrow=$cashrs->fetch_assoc();
	
	$cash_assistant=ucfirst(strtolower($cashrow['lastName'])).", ".ucfirst(strtolower($cashrow['firstName']));
	
	$ticketsellersql="select * from ticket_seller where id='".$row['ticket_seller']."'";
	$ticketsellerrs=$db->query($ticketsellersql);
	//$ticketsellernm=$ticketsellerrs->num_rows;
	$ticketsellerrow=$ticketsellerrs->fetch_assoc();
	
	$ticket_seller=ucfirst(strtolower($ticketsellerrow['last_name'])).", ".ucfirst(strtolower($ticketsellerrow['first_name']));
	
	
	$cashsql="select * from login where username='".$row['destination_ca']."'";
	$cashrs=$db->query($cashsql);
	$cashrow=$cashrs->fetch_assoc();
	
	$destination_cash_assistant=ucfirst(strtolower($cashrow['lastName'])).", ".ucfirst(strtolower($cashrow['firstName']));
	
	if($transact_type=="allocation"){
		addContent(setRange("A5","B5"),$excel,"From: ".$cash_assistant,"true",$ExWs);
		addContent(setRange("A6","B6"),$excel,"To: ".$ticket_seller,"true",$ExWs);
	}
	else if(($transact_type=="remittance")||($transact_type=="shortage")){
		addContent(setRange("A5","B5"),$excel,"From: ".$ticket_seller,"true",$ExWs);
		addContent(setRange("A6","B6"),$excel,"To: ".$cash_assistant,"true",$ExWs);
	
	}
	else if($transact_type=="catransfer"){
		addContent(setRange("A5","B5"),$excel,"From: ".$cash_assistant,"true",$ExWs);
		addContent(setRange("A6","B6"),$excel,"To: ".$destination_cash_assistant,"true",$ExWs);
	
	
	}
	
	
	
	if($cashrow['station']=="annex"){
		$station="Annex";
	}
	else {
		$stationsql="select * from station where id='".$row['station']."'";
		$stationrs=$db->query($stationsql);
		$stationrow=$stationrs->fetch_assoc();
		$station=$stationrow['station_name'];
	}
	
	addContent(setRange("A7","B7"),$excel,"Station: ".$station,"true",$ExWs);
	
	
//	addContent(setRange("C6","C7"),$excel,"Date: ".date("F d, Y",strtotime($row['time'])),"true",$ExWs);
	
	
	//addContent(setRange("C6","C7"),$excel,"Date: ".date("F d, Y",strtotime($row['time'])),"true",$ExWs);
	//addContent(setRange("C6","C7"),$excel,"Date: ".date("F d, Y",strtotime($row['time'])),"true",$ExWs);
	
	
	
	addContent(setRange("C5","C5"),$excel,"CTF No. ".$reference_id,"true",$ExWs);

	addContent(setRange("C6","C7"),$excel,"Date: ".date("m/d/Y",strtotime($row['time'])),"true",$ExWs);
	
	
	addContent(setRange("B23","C24"),$excel,$row['total_in_words'],"true",$ExWs);
	

	$denom="select * from denomination where cash_transfer_id='".$cash_transfer_id."'";
	$rsD=$db->query($denom);
	
	$nm2=$rsD->num_rows;
	
	for($i=0;$i<$nm2;$i++){
		$row2=$rsD->fetch_assoc();
		addContent(setRange("B".$grid["denom_".$row2['denomination']],"B".$grid["denom_".$row2['denomination']]),$excel,$row2['quantity'],"true",$ExWs);
	}
	
	
	/**

	$tickets[0]="sjt";
	$tickets[1]="sjd";
	$tickets[2]="svt";
	$tickets[3]="svd";
	
	$grid[0]=15;
	$grid[1]=16;
	$grid[2]=17;
	$grid[3]=18;
	
	
	
	for($i=0;$i<4;$i++){

		$sql="select * from allocation where control_id='".$control_id."' and type='".$tickets[$i]."'";

		$rs=$db->query($sql);
		$nm=$rs->num_rows;
		$row=$rs->fetch_assoc();
		$initial=$row['initial'];
		$additional=$row['additional'];
	

		addContent(setRange("B".$grid[$i],"B".$grid[$i]),$excel,$initial,"true",$ExWs);
		addContent(setRange("C".$grid[$i],"C".$grid[$i]),$excel,$additional,"true",$ExWs);

		
		
		
		$sql="select * from control_unsold where control_id='".$control_id."' and type='".$tickets[$i]."'";
		$rs=$db->query($sql);
		$nm=$rs->num_rows;
		$row=$rs->fetch_assoc();
			
		$sealed=$row['sealed'];
		$loose_good=$row['loose_good'];
		$loose_defective=$row['loose_defective'];
		
		addContent(setRange("D".$grid[$i],"D".$grid[$i]),$excel,$sealed,"true",$ExWs);
		addContent(setRange("E".$grid[$i],"E".$grid[$i]),$excel,$loose_good,"true",$ExWs);
		addContent(setRange("F".$grid[$i],"F".$grid[$i]),$excel,$loose_defective,"true",$ExWs);
		
		
		


	}
	$sql="select * from control_sales_amount where control_id='".$control_id."'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	$row=$rs->fetch_assoc();
		
	$sjt_amount=$row['sjt'];
	$sjd_amount=$row['sjd'];
	$svt_amount=$row['svt'];
	$svd_amount=$row['svd'];
	
	
	addContent(setRange("H15","H15"),$excel,$sjt_amount,"true",$ExWs);
	addContent(setRange("H16","H16"),$excel,$sjd_amount,"true",$ExWs);
	addContent(setRange("H17","H17"),$excel,$svt_amount,"true",$ExWs);
	addContent(setRange("H18","H18"),$excel,$svd_amount,"true",$ExWs);
	
	
	$sql="select * from fare_adjustment where control_id='".$control_id."'";

	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	$row=$rs->fetch_assoc();

	$fare_adjustment=$row['fare_adjustment'];
	$sjt_adjustment=$row['sjt'];
	$sjd_adjustment=$row['sjd'];
	$svt_adjustment=$row['svt'];
	$svd_adjustment=$row['svd'];	



	addContent(setRange("H23","H23"),$excel,$fare_adjustment,"true",$ExWs);
	addContent(setRange("H24","H24"),$excel,$sjt_adjustment,"true",$ExWs);
	addContent(setRange("H25","H25"),$excel,$sjd_adjustment,"true",$ExWs);
	addContent(setRange("H26","H26"),$excel,$svt_adjustment,"true",$ExWs);
	addContent(setRange("H27","H27"),$excel,$svd_adjustment,"true",$ExWs);

	

	$sql="select * from control_cash where control_id='".$control_id."'";
	$rs=$db->query($sql);
	$row=$rs->fetch_assoc();

	$overage=$row['overage'];
	$add_others=$row['add_others'];

	$discount=$row['discount'];
	$refund=$row['refund'];
	$less_others=$row['less_others'];
	$unpaid_shortage=$row['unpaid_shortage'];


	
	
	
	
	addContent(setRange("H35","H35"),$excel,$overage,"true",$ExWs);
	addContent(setRange("H36","H36"),$excel,$add_others,"true",$ExWs);
	addContent(setRange("H37","H37"),$excel,$refund,"true",$ExWs);
	addContent(setRange("H38","H38"),$excel,$unpaid_shortage,"true",$ExWs);
	addContent(setRange("H39","H39"),$excel,$discount,"true",$ExWs);

	*/
	
	save($ExWb,$excel,$newFilename); 	
	echo "Cash Transfer Slip has been generated!  Press right click and Save As: <a href='".$newFilename."'>Here</a>";
	




?>