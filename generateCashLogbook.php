<?php
session_start();
?>
<?php
require_once("phpexcel/Classes/PHPExcel.php");
require_once("phpexcel/Classes/PHPExcel/IOFactory.php");
require("excel functions.php");

$log_id=$_SESSION['log_id'];

//$startDate=$_GET['startDate'];
//$endDate=$_GET['endDate'];
//$viewType=$_GET['view'];


//$transaction_id=$_GET['trans'];
//$cash_transfer_id=$_GET['cash'];
?>
<?php
ini_set("date.timezone","Asia/Kuala_Lumpur");
?>
<?php
$rowCount=0;

$db=new mysqli("localhost","root","","finance");
$sql="select * from logbook where id='".$log_id."'";

$rs=$db->query($sql);
$row=$rs->fetch_assoc();

$logDate=date("F d, Y",strtotime($row['date']));
$logShift=$row['shift'];
$logUser=$row['cash_assistant'];
$logDayWeek=date("l",strtotime($row['date']));
$logST=$row['station'];


$stationSQL="select * from station where id='".$row['station']."'";
$stationRS=$db->query($stationSQL);
$stationRow=$stationRS->fetch_assoc();

$logStation=$stationRow['station_name'];


$shiftSQL="select * from shift where shift_id='S".$logShift."'";
$shiftRS=$db->query($shiftSQL);
$shiftRow=$shiftRS->fetch_assoc();
$shiftName=$shiftRow['shift_name'];
	

	$dateSlip=date("Y-m-d His");

	$filename="treasury forms/cash_logbook.xls";

	$newFilename="printout/Cash Logbook_".$log_id." ".$dateSlip.".xls";
	copy($filename,$newFilename);
	$workSheetName="Cash Logbook";	
	$workbookname=$newFilename;
	$excel=loadExistingWorkbook($workbookname);

  	$ExWs=createWorksheet($excel,$workSheetName,"openActive");
		
	
	$db=new mysqli("localhost","root","","finance");

	$station=$_SESSION['station'];


	$sql="select * from beginning_balance_cash where log_id='".$log_id."'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	if($nm>0){
		$row=$rs->fetch_assoc();
		$revolvingTotal=$row['revolving_fund'];
		$depositTotal=$row['for_deposit'];
		$grandTotal=($row['for_deposit']*1)+($row['revolving_fund']*1);
	}
	else {

		$alternate="SELECT * FROM beginning_balance_cash inner join logbook on beginning_balance_cash.log_id=logbook.id and station='".$station."' order by date desc,shift desc";

		$rs2=$db->query($alternate);
		$row=$rs2->fetch_assoc();
		$revolvingTotal=$row['revolving_fund'];
		$depositTotal=$row['for_deposit'];
//		$grandTotal=($row['for_deposit']*1)+($row['revolving_fund']*1);

	}		
	
	$rowCount+=6;
	addContent(setRange("B".$rowCount,"C".$rowCount),$excel,$logStation,"true",$ExWs);
	addContent(setRange("G".$rowCount,"K".$rowCount),$excel,$logDate,"true",$ExWs);

	$rowCount++;
	addContent(setRange("B".$rowCount,"C".$rowCount),$excel,$shiftName,"true",$ExWs);

	$rowCount+=5;
	
	addContent(setRange("B".$rowCount,"B".$rowCount),$excel,"Beginning Balance","true",$ExWs);

	addContent(setRange("M".$rowCount,"M".$rowCount),$excel,$revolvingTotal,"true",$ExWs);
	addContent(setRange("N".$rowCount,"N".$rowCount),$excel,$depositTotal,"true",$ExWs);
	

	addContent(setRange("O".$rowCount,"P".$rowCount),$excel,"=M".$rowCount."+N".$rowCount,"true",$ExWs);

	$rowCount++;

$db=new mysqli("localhost","root","","finance");
$sql="select * from transaction where log_id='".$log_id."' and log_type in ('cash') and transaction_type not in ('catransfer') order by id*1";
$rs=$db->query($sql);
$nm=$rs->num_rows;
$a=$rowCount;
$rowCount2=$rowCount*1-1;
$pp=1;
for($i=0;$i<$nm;$i++){

	$row=$rs->fetch_assoc();

	$date=date("h:i a",strtotime($row['date']));
	$edit_id=$row['id'];
	$transaction_id=$row['transaction_id'];
	
	$type=$row['transaction_type'];
	$log_type=$row['log_type'];
	
	if($row['reference_id']==""){
	$remarks="";
	}
	else {
	$remarks=$row['reference_id'];
	}
	if($type=="shortage"){
		$type="remittance";
		$log_type="shortage";
	}
	
	$suffix="";
	
	if($type=="deposit"){
		$cashSQL="select * from pnb_deposit where transaction_id='".$transaction_id."'";

		$cashRS=$db->query($cashSQL);
		
		$cashRow=$cashRS->fetch_assoc();	
		$deposit_type=$cashRow['type'];
		
	}
	else {
	
		if($type=="remittance"){
			if(($log_type=="cash")||($log_type=="shortage")){
				$cashSQL="select * from cash_transfer where transaction_id='".$transaction_id."'";

				$cashRS=$db->query($cashSQL);
				
				$cashRow=$cashRS->fetch_assoc();

				if($cashRow['station']==$logST){
				}
				else {
					if($cashRow['station']=="annex"){
					}
					else {
					$extensionSQL="select * from station where id='".$cashRow['station']."'";
					$extensionRS=$db->query($extensionSQL);
					$extensionRow=$extensionRS->fetch_assoc();
					
					$suffix=" - ".$extensionRow['station_name'];
					}
				}
				$cashStation=$cashRow['station'];	

				$ticketSellerSQL="select * from ticket_seller where id='".$cashRow['ticket_seller']."'";		

				$ticketRS=$db->query($ticketSellerSQL);
				$ticketRow=$ticketRS->fetch_assoc();
				
				$revolving=$cashRow['total'];
				$deposit=$cashRow['net_revenue'];
				$total=$revolving*1+$deposit*1;
			}
			/*
			else if($log_type=="control"){
				$control="select * from cash_remittance where control_transaction_id='".$transaction_id."'";
				$controlRS=$db->query($control);
				$controlRow=$controlRS->fetch_assoc();
				
				$ticketSellerSQL="select * from ticket_seller where id='".$controlRow['ticket_seller']."'";		

				$ticketRS=$db->query($ticketSellerSQL);
				$ticketRow=$ticketRS->fetch_assoc();
				
				$revolving=0;
				$deposit=$controlRow['control_remittance'];
				$total=$revolving*1+$deposit*1;
				
			
			}
			*/
		}
		else if($type=="allocation"){
		
			$cashSQL="select * from cash_transfer where transaction_id='".$transaction_id."'";

			$cashRS=$db->query($cashSQL);
			
			$cashRow=$cashRS->fetch_assoc();
				if($cashRow['station']==$logST){
				}
				else {
					if($cashRow['station']=="annex"){
					}
					else {
					$extensionSQL="select * from station where id='".$cashRow['station']."'";
					$extensionRS=$db->query($extensionSQL);
					$extensionRow=$extensionRS->fetch_assoc();
					
					$suffix=" - ".$extensionRow['station_name'];
					}
				}
			$cashStation=$cashRow['station'];	
			
			$ticketSellerSQL="select * from ticket_seller where id='".$cashRow['ticket_seller']."'";		

			$ticketRS=$db->query($ticketSellerSQL);
			$ticketRow=$ticketRS->fetch_assoc();
			
			$revolving=$cashRow['total'];
			$deposit=$cashRow['net_revenue'];
			$total=$revolving*1+$deposit*1;
		
		}
		
	}
	addContent(setRange("A".$rowCount,"A".$rowCount),$excel,$date,"true",$ExWs);

	if($type=="deposit")
	{ 
		addContent(setRange("B".$rowCount,"B".$rowCount),$excel,"PNB Deposit - ".strtoupper($deposit_type),"true",$ExWs);	


	} 
	else if($type=="remittance"){ 
		if($log_type=="cash"){
//			echo "<a href='#' style='text-decoration:none' onclick='window.open(\"cash_transfer.php?tID=".$edit_id."\",\"transfer\",\"height=800, width=500, scrollbars=yes\")'>".strtoupper($ticketRow['last_name']).", ".$ticketRow['first_name']."</a>"; 
			if($cashStation=="annex"){
			addContent(setRange("B".$rowCount,"B".$rowCount),$excel,"ANNEX","true",$ExWs);	

			}
			else {

			addContent(setRange("B".$rowCount,"B".$rowCount),$excel,strtoupper($ticketRow['last_name']).", ".$ticketRow['first_name'].$suffix,"true",$ExWs);	
			}
			
		}
		else if($log_type=="shortage"){
//			echo "<a href='#' style='text-decoration:none' onclick='window.open(\"cash_transfer.php?tID=".$edit_id."\",\"transfer\",\"height=800, width=500, scrollbars=yes\")'>Payment for Shortage</a>"; 		
			addContent(setRange("B".$rowCount,"B".$rowCount),$excel,"Payment for Shortage","true",$ExWs);	
				
		}
	} 
	else if($type=="allocation"){ 
		if($cashStation=="annex"){
			addContent(setRange("B".$rowCount,"B".$rowCount),$excel,"ANNEX","true",$ExWs);	

		}
		else {
		addContent(setRange("B".$rowCount,"B".$rowCount),$excel,strtoupper($ticketRow['last_name']).", ".$ticketRow['first_name'].$suffix,"true",$ExWs);
		}
	
	
	}

		if($type=="deposit"){

		}
		else if($type=="remittance"){
			if($log_type=="cash"){
				if($cashStation=="annex"){

				}
				else {
					addContent(setRange("C".$rowCount,"C".$rowCount),$excel,"'".$ticketRow['id'],"true",$ExWs);
				}

			}
			else {
				if($log_type=="shortage"){
					addContent(setRange("C".$rowCount,"C".$rowCount),$excel,"'".$ticketRow['id'],"true",$ExWs);
				}
			}
			
		}
		else { 
			if($cashStation=="annex"){
			}
			else {
				addContent(setRange("C".$rowCount,"C".$rowCount),$excel,"'".$ticketRow['id'],"true",$ExWs);
			}
		}		

	if($type=="remittance"){
		addContent(setRange("D".$rowCount,"D".$rowCount),$excel,$revolving*1,"true",$ExWs);
		addContent(setRange("E".$rowCount,"E".$rowCount),$excel,$deposit*1,"true",$ExWs);
		addContent(setRange("F".$rowCount,"G".$rowCount),$excel,"=D".$rowCount."+E".$rowCount,"true",$ExWs);


		addContent(setRange("M".$rowCount,"M".$rowCount),$excel,"=D".$rowCount."+M".($rowCount2),"true",$ExWs);
		addContent(setRange("N".$rowCount,"N".$rowCount),$excel,"=E".$rowCount."+N".($rowCount2),"true",$ExWs);
		addContent(setRange("O".$rowCount,"P".$rowCount),$excel,"=M".$rowCount."+N".$rowCount,"true",$ExWs);

		$overageSQL="select * from discrepancy where transaction_id='".$transaction_id."'";
		$overageRS=$db->query($overageSQL);
		$overageNM=$overageRS->num_rows;
		if($overageNM>0){
			$overageRow=$overageRS->fetch_assoc();
			if($overageRow['type']=="shortage"){
				$overageLabel=number_format($overageRow['amount'],2);
			
			}
			else if($overageRow['type']=="overage"){
				$overageLabel="(".number_format($overageRow['amount'],2).")";
			
			}
		}
		else {
			$overageLabel=0;
		}		
		
		addContent(setRange("H".$rowCount,"I".$rowCount),$excel,$overageLabel,"true",$ExWs);
		
		
	}		
	else if($type=="allocation"){	
		addContent(setRange("J".$rowCount,"J".$rowCount),$excel,$revolving*1,"true",$ExWs);
		addContent(setRange("K".$rowCount,"K".$rowCount),$excel,$deposit*1,"true",$ExWs);
		addContent(setRange("L".$rowCount,"L".$rowCount),$excel,"=J".$rowCount."+K".$rowCount,"true",$ExWs);


		addContent(setRange("M".$rowCount,"M".$rowCount),$excel,"=M".($rowCount2)."-J".($rowCount*1),"true",$ExWs);
		addContent(setRange("N".$rowCount,"N".$rowCount),$excel,"=N".($rowCount2)."+E".($rowCount*1)."-K".($rowCount*1),"true",$ExWs);
		addContent(setRange("O".$rowCount,"P".$rowCount),$excel,"=M".($rowCount)."+N".$rowCount,"true",$ExWs);
	
	}
	else if($type=="deposit"){
		
		//addContent(setRange("D".$rowCount,"D".$rowCount),$excel,$revolving,"true",$ExWs);
		addContent(setRange("K".$rowCount,"K".$rowCount),$excel,$cashRow['amount'],"true",$ExWs);
		addContent(setRange("L".$rowCount,"L".$rowCount),$excel,"=K".$rowCount,"true",$ExWs);
	
		addContent(setRange("M".$rowCount,"M".$rowCount),$excel,"=M".($rowCount2),"true",$ExWs);
		addContent(setRange("N".$rowCount,"N".$rowCount),$excel,"=N".$rowCount2."-K".($rowCount*1),"true",$ExWs);
		addContent(setRange("O".$rowCount,"P".$rowCount),$excel,"=M".$rowCount."+N".$rowCount,"true",$ExWs);
	
	
	}

	addContent(setRange("Q".$rowCount,"R".$rowCount),$excel,$remarks,"true",$ExWs);
	
	if($a==29){
		$rowCount2=$rowCount;
		
		$rowCount+=8;
		$rowCount+=12;
		$a=12;
	}
	else {
		if($rowCount>29){
			$pp=2;
		}
		if($rowCount>66){
			$pp=3;
		}

		$rowCount++;
		$rowCount2=$rowCount-1;
		$a++;	
	}
	if($i==($nm-1)){
		if($a<29){
			$excel->getActiveSheet()->removeRow(($rowCount),(29-$a));
			
			
			addContent(setRange("F".($rowCount+2),"J".($rowCount+2)),$excel,"Received the amount of Php","true",$ExWs);
			$excel->getActiveSheet()->getStyle("F".($rowCount+2).":J".($rowCount+2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			addContent(setRange("F".($rowCount+3),"K".($rowCount+3)),$excel,"as turned over by the Cash Assistant","true",$ExWs);
			$excel->getActiveSheet()->getStyle("F".($rowCount+3).":J".($rowCount+3))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				
			$rowCount+=9;
			$excel->getActiveSheet()->removeRow(($rowCount),1000);
				
			
		}
		
		
		
	}
	
	

}	
$sql="select * from transaction where transaction_type='catransfer' and log_id='".$log_id."'";
$rs=$db->query($sql);
$nm=$rs->num_rows;
if($nm>0){
	$row=$rs->fetch_assoc();
	$cTransferSQL="select * from cash_transfer where transaction_id='".$row['transaction_id']."'";
	$cTransferRS=$db->query($cTransferSQL);
	$cTR=$cTransferRS->fetch_assoc();
	$transaction_id=$row['transaction_id'];	
	$revolvingTransfer=$cTR['total'];
	$depositTransfer=$cTR['net_revenue'];
	$totalTransfer=$revolvingTransfer+$depositTransfer;
	
	$revolvingTotal-=$revolvingTransfer;
	$depositTotal-=$depositTransfer;
	$displayTotal-=$totalTransfer;
	$remarks=$cTR['reference_id'];
	$edit_id=$row['id'];


	$userSQL="select * from login where username='".$cTR['destination_ca']."'";
	$userRS=$db->query($userSQL);
	$userRow=$userRS->fetch_assoc();

	$destination_ca=$userRow['lastName'].", ".$userRow['firstName'];	
	
	$userSQL="select * from login where username='".$cTR['cash_assistant']."'";
	$userRS=$db->query($userSQL);
	$userRow=$userRS->fetch_assoc();

	$origin_ca=$userRow['lastName'].", ".$userRow['firstName'];	

	
	addContent(setRange("K31","K31"),$excel,number_format($totalTransfer,2),"true",$ExWs);
	addContent(setRange("B33","D33"),$excel,$origin_ca,"true",$ExWs);
	addContent(setRange("H33","K33"),$excel,$destination_ca,"true",$ExWs);
	
	
	
	
}	




	addContent(setRange("Q7","Q7"),$excel,$pp,"true",$ExWs);

	$userSQL="select * from login where username='".$_SESSION['username']."'";
	$userRS=$db->query($userSQL);
	$userRow=$userRS->fetch_assoc();
	
	$user_fullname=$userRow['lastName'].", ".$userRow['firstName'];
	
	addContent(setRange("J37","L37"),$excel,"Printed by: ".$user_fullname,"true",$ExWs);
	$timePrinted=date("Y-m-d H:i:s");
	$timePrintStamp=date("H:iA",strtotime($timePrinted));
	$datePrintStamp=date("m/d/Y",strtotime($timePrinted));
	
	addContent(setRange("M37","N37"),$excel,"Time Printed: ".$timePrintStamp,"true",$ExWs);
	
	addContent(setRange("O37","R37"),$excel,"Date Printed: ".$datePrintStamp,"true",$ExWs);
	
?>	
	
	
<?php	
/*	$sql="select * from cash_transfer where id='".$cash_transfer_id."'";
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
	
	
	//addContent(setRange("C6","C7"),$excel,"Date: ".date("F d, Y",strtotime($row['time'])),"true",$ExWs);


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
	$newFilename2=str_replace('xls','html',$newFilename);
	saveHTML($ExWb,$excel,$newFilename2); 	

	echo "Cash Logbook has been generated!  Press right click and Save As: <a href='".$newFilename."'>Here</a>";
	echo "<br>";	
	echo "Cash Logbook (HTML) has been generated!  Press right click and Save As: <a href='".$newFilename2."'>Here</a>";
	
	




?>