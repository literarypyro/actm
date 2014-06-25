<?php
session_start();
?>
<?php
require_once("phpexcel/Classes/PHPExcel.php");
require_once("phpexcel/Classes/PHPExcel/IOFactory.php");
require("excel functions.php");

$log_id=$_SESSION['log_id'];

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




$userSQL="select * from login where username='".$logUser."'";
$userRS=$db->query($userSQL);
$userRow=$userRS->fetch_assoc();
	
$user_fullname=$userRow['lastName'].", ".$userRow['firstName'];


$stationSQL="select * from station where id='".$row['station']."'";
$stationRS=$db->query($stationSQL);
$stationRow=$stationRS->fetch_assoc();

$logStation=$stationRow['station_name'];


$shiftSQL="select * from shift where shift_id='S".$logShift."'";
$shiftRS=$db->query($shiftSQL);
$shiftRow=$shiftRS->fetch_assoc();
$shiftName=$shiftRow['shift_name'];
	



	$dateSlip=date("Y-m-d His");

	$filename="treasury forms/svt_logbook.xls";

	$newFilename="printout/SVT Logbook_".$log_id." ".$dateSlip.".xls";
	copy($filename,$newFilename);
	$workSheetName="SVT Logbook";	
	$workbookname=$newFilename;
	$excel=loadExistingWorkbook($workbookname);

  	$ExWs=createWorksheet($excel,$workSheetName,"openActive");
	$db=new mysqli("localhost","root","","finance");

	$station=$_SESSION['station'];

	$svt_loose_1=0;
	$svt_packs_1=0;


	$svd_loose_1=0;
	$svd_packs_1=0;

	
	
	$sql="select * from beginning_balance_svt where log_id='".$log_id."'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	if($nm>0){
		$row=$rs->fetch_assoc();

		$svt_loose_1=$row['svt_loose'];
		$svt_packs_1=$row['svt'];


		$svd_loose_1=$row['svd_loose'];
		$svd_packs_1=$row['svd'];
	}
	else {
		$alternate="SELECT * FROM beginning_balance_svt inner join logbook on beginning_balance_svt.log_id=logbook.id and station='".$station."' order by date desc,shift desc";

		$rs2=$db->query($alternate);
		$row=$rs2->fetch_assoc();
		$svt_loose_1=$row['svt_loose'];
		$svt_packs_1=$row['svt'];

		$svd_loose_1=$row['svd_loose'];
		$svd_packs_1=$row['svd'];

	}
	$rowCount+=6;

	addContent(setRange("C".$rowCount,"D".$rowCount),$excel,$logStation,"true",$ExWs);
	addContent(setRange("H".$rowCount,"I".$rowCount),$excel,$logDate,"true",$ExWs);	
	$rowCount++;
	addContent(setRange("H".$rowCount,"I".$rowCount),$excel,$shiftName,"true",$ExWs);
	addContent(setRange("C".$rowCount,"E".$rowCount),$excel,$user_fullname,"true",$ExWs);
	
	
	
	$rowCount+=5;
	addContent(setRange("B".$rowCount,"C".$rowCount),$excel,"Beginning Balance","true",$ExWs);

	addContent(setRange("Q".$rowCount,"Q".$rowCount),$excel,$svt_packs_1,"true",$ExWs);
	addContent(setRange("R".$rowCount,"S".$rowCount),$excel,$svt_loose_1,"true",$ExWs);
	addContent(setRange("T".$rowCount,"T".$rowCount),$excel,$svd_packs_1,"true",$ExWs);
	addContent(setRange("U".$rowCount,"U".$rowCount),$excel,$svd_loose_1,"true",$ExWs);

	$rowCount++;
	
	$sql="select * from transaction where log_id='".$log_id."' and log_type in ('ticket','initial','annex','finance') and transaction_type not in ('ticket_amount')  order by id*1";

	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	
	$db=new mysqli("localhost","root","","finance");
$a=$rowCount;
	$counter=0;
for($m=0;$m<$nm;$m++){

	$row=$rs->fetch_assoc();

	$date=date("h:i a",strtotime($row['date']));
	$transaction_id=$row['transaction_id'];
	$type=$row['transaction_type'];
	$edit_id=$row['id'];

	$log_type=$row['log_type'];
	if($row['reference_id']==""){
		$remarks="&nbsp;";
	}
	else {
		$remarks=$row['reference_id'];
	}		
	$svt_packs=0;
	$svd_packs=0;
	$svt_loose=0;
	$svd_loose=0;
	
	$svt_loose_in=0;
	$svd_loose_in=0;

	$suffix="";	

	if(($row['log_type']=='ticket')||($row['log_type']=="annex")||($row['log_type']=="finance")){
		$ticketSQL="select * from ticket_order where transaction_id='".$transaction_id."'";
		$ticketRS=$db->query($ticketSQL);
			
		$ticketRow=$ticketRS->fetch_assoc();
		
		if($ticketRow['station']==$logST){
		}
		else {
			$extensionSQL="select * from station where id='".$ticketRow['station']."'";
			$extensionRS=$db->query($extensionSQL);
			$extensionRow=$extensionRS->fetch_assoc();
			$suffix=" - ".$extensionRow['station_name'];
		}			
		//$svt_loose=$ticketRow['svt']%100;
		$svt_loose=$ticketRow['svt_loose'];
		$svt_packs=$ticketRow['svt'];
		
		//$svt_packs=($ticketRow['svt']*1-$svt_loose);
		$svd_loose=$ticketRow['svd_loose'];
		$svd_packs=$ticketRow['svd'];
			
		$unitType=$ticketRow['unit'];	
		$ticketSellerId=$ticketRow['ticket_seller'];
	}		
	else if($row['log_type']=='initial'){
		$svt_packs=0;
		$svd_packs=0;

		$svt_loose=0;
		$svd_loose=0;
		$svt_loose_in=0;
		$svd_loose_in=0;

		$trans_type=$row['transaction_type'];
		if($trans_type=="allocation"){
			$ticketSQL="select * from allocation where transaction_id='".$transaction_id."' and type in ('svd','svt')";

			$ticketRS=$db->query($ticketSQL);
			$ticketNM=$ticketRS->num_rows;		
			
			//$ticketSellerId=$ticketsRow['ticket_seller'];
			
			for($i=0;$i<$ticketNM;$i++){			
				$ticketRow=$ticketRS->fetch_assoc();			
				if($i==0){
					$control_id=$ticketRow['control_id'];
					$tsSQL="select * from control_slip where id='".$control_id."'";
					$tsRS=$db->query($tsSQL);
					$tsNM=$tsRS->num_rows;
					$ticketsRow=$tsRS->fetch_assoc();
					
					$ticketSellerId=$ticketsRow['ticket_seller'];
					$unitType=$ticketsRow['unit'];
					if($ticketsRow['station']==$logST){
					}
					else {
						$extensionSQL="select * from station where id='".$ticketRow['station']."'";
						$extensionRS=$db->query($extensionSQL);
						$extensionRow=$extensionRS->fetch_assoc();
								
						$suffix=" - ".$extensionRow['station_name'];
					}							
				}
		
				if($ticketRow['type']=='svt'){
					$svt_packs=$ticketRow['initial']*1;
					$svt_loose=$ticketRow['initial_loose']*1;
				}
				else if($ticketRow['type']=="svd"){
					$svd_packs=$ticketRow['initial']*1;
					$svd_loose=$ticketRow['initial_loose']*1;
				}				
			}	
		}
		else if($trans_type=="remittance"){
			$svt_packs=0;
			$svd_packs=0;
			
			$svt_loose=0;
			$svd_loose=0;		

			$svt_loose_in=0;
			$svd_loose_in=0;		

			
			$looseSQL="select * from control_unsold where transaction_id='".$transaction_id."' and type in ('svd','svt')";
			$looseRS=$db->query($looseSQL);
			$looseNM=$looseRS->num_rows;
		
			for($i=0;$i<$looseNM;$i++){
				$looseRow=$looseRS->fetch_assoc();
				if($i==0){
					$control_id=$looseRow['control_id'];
					$tsSQL="select * from control_slip where id='".$control_id."'";
					$tsRS=$db->query($tsSQL);
					$tsNM=$tsRS->num_rows;
					$ticketsRow=$tsRS->fetch_assoc();
					
					$ticketSellerId=$ticketsRow['ticket_seller'];
					$unitType=$ticketsRow['unit'];				
					if($ticketsRow['station']==$logST){
					}
					else {
						$extensionSQL="select * from station where id='".$ticketRow['station']."'";
						$extensionRS=$db->query($extensionSQL);
						$extensionRow=$extensionRS->fetch_assoc();
								
						$suffix=" - ".$extensionRow['station_name'];
					}						
				}
			
				if($looseRow['type']=='svt'){
					$loose_total=$looseRow['loose_defective']*1;
					$svt_loose=$loose_total;
					$svt_loose_in=$looseRow['loose_defective']*1+$looseRow['loose_good']*1;
					$svt_packs=$looseRow['sealed'];
				}
				else if($looseRow['type']=="svd"){
					
					$loose_total=$looseRow['loose_defective']*1;
					$svd_loose_in=$looseRow['loose_defective']*1+$looseRow['loose_good']*1;
					$svd_loose=$loose_total;
					$svd_packs=$looseRow['sealed'];
				}
			}
		}		
	}
	$ticketSellerSQL="select * from ticket_seller where id='".$ticketSellerId."'";		

	$ticketSellerRS=$db->query($ticketSellerSQL);
	$ticketSellerRow=$ticketSellerRS->fetch_assoc();

	$verify=$svt_packs+$svt_loose+$svd_packs+$svd_loose+$svt_loose_in+$svd_loose_in;
	if($verify==0){
	}
	else {
		addContent(setRange("A".$rowCount,"A".$rowCount),$excel,$date,"true",$ExWs);

		if($log_type=="initial"){
			addContent(setRange("B".$rowCount,"C".$rowCount),$excel,strtoupper($ticketSellerRow['last_name']).", ".$ticketSellerRow['first_name'],"true",$ExWs);
		}
		else if($log_type=="annex"){
			addContent(setRange("B".$rowCount,"C".$rowCount),$excel,"FROM ANNEX","true",$ExWs);
			
		}	
		else if($log_type=="finance"){
			addContent(setRange("B".$rowCount,"C".$rowCount),$excel,"FROM FINANCE TRAIN","true",$ExWs);
		}	
		else {
			$name=strtoupper($ticketSellerRow['last_name']).", ".$ticketSellerRow['first_name'].$suffix;
			if($unitType==""){ } else { $name." - ".$unitType; }
			addContent(setRange("B".$rowCount,"C".$rowCount),$excel,$name,"true",$ExWs);
		}				
		
		if($type=="deposit"){
		}
		else if($type=="remittance"){
			if($log_type=="cash"){
				addContent(setRange("D".$rowCount,"D".$rowCount),$excel,"'".$ticketSellerRow['id'],"true",$ExWs);

			}
			else {
				if(($log_type=='annex')||($log_type=='finance')){
				}
				else {
					addContent(setRange("D".$rowCount,"D".$rowCount),$excel,"'".$ticketSellerRow['id'],"true",$ExWs);
				}
			}
		}
		else { 
			if(($log_type=='annex')||($log_type=='finance')){
			}
			else {
				addContent(setRange("D".$rowCount,"D".$rowCount),$excel,"'".$ticketSellerRow['id'],"true",$ExWs);
			}
		}	
		
	if($log_type=="initial"){
		if($type=="remittance"){
			addContent(setRange("E".$rowCount,"E".$rowCount),$excel,$svt_packs,"true",$ExWs);
			addContent(setRange("F".$rowCount,"F".$rowCount),$excel,$svt_loose_in,"true",$ExWs);
			addContent(setRange("G".$rowCount,"G".$rowCount),$excel,$svd_packs,"true",$ExWs);
			addContent(setRange("H".$rowCount,"H".$rowCount),$excel,$svd_loose_in,"true",$ExWs);
			addContent(setRange("N".$rowCount,"N".$rowCount),$excel,$svt_loose,"true",$ExWs);
			addContent(setRange("P".$rowCount,"P".$rowCount),$excel,$svd_loose,"true",$ExWs);
		}	
		else if($type=="allocation"){
			addContent(setRange("M".$rowCount,"M".$rowCount),$excel,$svt_packs,"true",$ExWs);
			addContent(setRange("N".$rowCount,"N".$rowCount),$excel,$svt_loose,"true",$ExWs);
			addContent(setRange("O".$rowCount,"O".$rowCount),$excel,$svd_packs,"true",$ExWs);
			addContent(setRange("P".$rowCount,"P".$rowCount),$excel,$svd_loose,"true",$ExWs);
		}	
	}	
	else if(($log_type=="annex")||($log_type=="finance")){
		addContent(setRange("E".$rowCount,"E".$rowCount),$excel,$svt_packs,"true",$ExWs);
		addContent(setRange("F".$rowCount,"F".$rowCount),$excel,$svt_loose,"true",$ExWs);
		addContent(setRange("G".$rowCount,"G".$rowCount),$excel,$svd_packs,"true",$ExWs);
		addContent(setRange("H".$rowCount,"H".$rowCount),$excel,$svd_loose,"true",$ExWs);
	}		
	else if($log_type=="ticket"){
		addContent(setRange("M".$rowCount,"M".$rowCount),$excel,$svt_packs,"true",$ExWs);
		addContent(setRange("N".$rowCount,"N".$rowCount),$excel,$svt_loose,"true",$ExWs);
		addContent(setRange("O".$rowCount,"O".$rowCount),$excel,$svd_packs,"true",$ExWs);
		addContent(setRange("P".$rowCount,"P".$rowCount),$excel,$svd_loose,"true",$ExWs);
	}	
	if(($log_type=="annex")||($log_type=="finance")){
		$svt_packs_1+=$svt_packs*1;
		$svd_packs_1+=$svd_packs*1;
		$svd_loose_1+=$svd_loose;
		$svt_loose_1+=$svt_loose;
	}
	else if($log_type=="ticket"){
		if($type=="allocation"){
			$svt_packs_1-=$svt_packs*1;
			$svd_packs_1-=$svd_packs*1;
			$svd_loose_1-=$svd_loose;
			$svt_loose_1-=$svt_loose;
		}
		else if($type=="remittance"){
			$svd_loose_1+=$svd_loose_in-$svd_loose;	
			$svt_loose_1+=$svt_loose_in-$svt_loose;	
			$svt_packs_1+=$svt_packs*1;
			$svd_packs_1+=$svd_packs*1;
		}
	}
	else if($log_type=="initial"){
		if($type=="allocation"){
			$svt_packs_1-=$svt_packs*1;
			$svd_packs_1-=$svd_packs*1;
			$svd_loose_1-=$svd_loose*1;
			$svt_loose_1-=$svt_loose*1;
		}
		else if($type=="remittance"){
			$svd_loose_1+=$svd_loose_in-$svd_loose;	
			$svt_loose_1+=$svt_loose_in-$svt_loose;	
			$svt_packs_1+=$svt_packs*1;
			$svd_packs_1+=$svd_packs*1;
		}
	
	}
		addContent(setRange("Q".$rowCount,"Q".$rowCount),$excel,$svt_packs_1,"true",$ExWs);
		addContent(setRange("R".$rowCount,"S".$rowCount),$excel,$svt_loose_1,"true",$ExWs);
		addContent(setRange("T".$rowCount,"T".$rowCount),$excel,$svd_packs_1,"true",$ExWs);
		addContent(setRange("U".$rowCount,"U".$rowCount),$excel,$svd_loose_1,"true",$ExWs);
		
		if($a==28){
			$rowCount2=$rowCount;

			if($rowCount<=28){
				$pp=1;
				$pageIndex=30;
			}
			
			if($rowCount>28){
				$pp=2;
				$pageIndex=68;
			}
			
			if($rowCount>66){
				$pp=3;
				$pageIndex=106;
			}

			addContent(setRange("Q".$pageIndex,"Q".$pageIndex),$excel,"=Q".$rowCount,"true",$ExWs);
			addContent(setRange("R".$pageIndex,"S".$pageIndex),$excel,"=R".$rowCount,"true",$ExWs);
			addContent(setRange("T".$pageIndex,"T".$pageIndex),$excel,"=T".$rowCount,"true",$ExWs);
			addContent(setRange("U".$pageIndex,"U".$pageIndex),$excel,"=U".$rowCount,"true",$ExWs);	
			
			$rowCount+=10;
			$rowCount+=12;
			$a=12;
		}
		else {
			if($rowCount<=28){
				$pp=1;
				$pageIndex=30;
			}
			
			if($rowCount>28){
				$pp=2;
				$pageIndex=68;
			}
			
			if($rowCount>68){
				$pp=3;
				$pageIndex=106;
			}

			addContent(setRange("Q".$pageIndex,"Q".$pageIndex),$excel,"=Q".$rowCount,"true",$ExWs);
			addContent(setRange("R".$pageIndex,"S".$pageIndex),$excel,"=R".$rowCount,"true",$ExWs);
			addContent(setRange("T".$pageIndex,"T".$pageIndex),$excel,"=T".$rowCount,"true",$ExWs);
			addContent(setRange("U".$pageIndex,"U".$pageIndex),$excel,"=U".$rowCount,"true",$ExWs);	

			$rowCount++;
			$rowCount2=$rowCount-1;
			$a++;	
			
			
			
			
		}
			
	
	}
	$counter++;
	
	
	
	
	
	
}	

	$sqlDefective="select * from physically_defective where log_id='".$log_id."'";
	$rsDefective=$db->query($sqlDefective);
	$nmDefective=$rsDefective->num_rows;

	if($nmDefective>0){
		$rowDefective=$rsDefective->fetch_assoc();
		$verify=$rowDefective['svt']+$rowDefective['svd'];		
		if($verify>0){
			/*
			$date=date("h:i a",strtotime($rowDefective['date']));	
			addContent(setRange("A".$rowCount,"A".$rowCount),$excel,$date,"true",$ExWs);
			addContent(setRange("B".$rowCount,"C".$rowCount),$excel,"Physically Defective","true",$ExWs);
				
			addContent(setRange("N".$rowCount,"N".$rowCount),$excel,$rowDefective['svt'],"true",$ExWs);
			addContent(setRange("P".$rowCount,"P".$rowCount),$excel,$rowDefective['svd'],"true",$ExWs);
			

			$svd_loose_1-=$rowDefective['svd'];	
			$svt_loose_1-=$rowDefective['svt'];			
			addContent(setRange("Q".$rowCount,"Q".$rowCount),$excel,$svt_packs_1,"true",$ExWs);
			addContent(setRange("R".$rowCount,"S".$rowCount),$excel,$svt_loose_1,"true",$ExWs);
			addContent(setRange("T".$rowCount,"T".$rowCount),$excel,$svd_packs_1,"true",$ExWs);
			addContent(setRange("U".$rowCount,"U".$rowCount),$excel,$svd_loose_1,"true",$ExWs);
			*/
			
			
		}
		
	if($rowCount<=28){
		$pp=1;
		$pageIndex=30;				
	}

	if($rowCount>28){
		$pp=2;
		$pageIndex=68;				
	}
			
	if($rowCount>68){
		$pp=3;
		$pageIndex=106;
	}			
			addContent(setRange("Q".$pageIndex,"Q".$pageIndex),$excel,"=Q".$rowCount,"true",$ExWs);
			addContent(setRange("R".$pageIndex,"S".$pageIndex),$excel,"=R".$rowCount,"true",$ExWs);
			addContent(setRange("T".$pageIndex,"T".$pageIndex),$excel,"=T".$rowCount,"true",$ExWs);
			addContent(setRange("U".$pageIndex,"U".$pageIndex),$excel,"=U".$rowCount,"true",$ExWs);	


	}	



	addContent(setRange("S7","S7"),$excel,$pp,"true",$ExWs);

	$sql="SELECT * FROM transaction  where log_type='ticket_catransfer' and log_id='".$log_id."'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	if($nm>0){
		$row=$rs->fetch_assoc();
		$cTransferSQL="select * from ticket_order where transaction_id='".$row['transaction_id']."'";
		$cTransferRS=$db->query($cTransferSQL);
		$cTR=$cTransferRS->fetch_assoc();


		
		$turnover_sjt=0;
		$turnover_sjd=0;
		$turnover_svt=0;
		$turnover_svd=0;
		
		
		$turnover_sjt=$cTR['sjt']*1+$cTR['sjt_loose']*1;
		$turnover_sjd=$cTR['sjd']*1+$cTR['sjd_loose']*1;
		$turnover_svt=$cTR['svt']*1+$cTR['svt_loose']*1;
		$turnover_svd=$cTR['svd']*1+$cTR['svd_loose']*1;
		
		


		$userSQL="select * from login where username='".$cTR['destination_ca']."'";
		$userRS=$db->query($userSQL);
		$userRow=$userRS->fetch_assoc();

		$destination_ca=$userRow['lastName'].", ".$userRow['firstName'];	
		
		$userSQL="select * from login where username='".$cTR['cash_assistant']."'";
		$userRS=$db->query($userSQL);
		$userRow=$userRS->fetch_assoc();

		$origin_ca=$userRow['lastName'].", ".$userRow['firstName'];	

		addContent(setRange("H33","H33"),$excel,$turnover_sjt,"true",$ExWs);
		addContent(setRange("J33","J33"),$excel,$turnover_sjd,"true",$ExWs);


		addContent(setRange("B34","D34"),$excel,$origin_ca,"true",$ExWs);
		addContent(setRange("G34","J34"),$excel,$destination_ca,"true",$ExWs);

		
		$nextShiftTurnover=1;
		if($logShift==3){
			$nextShiftTurnover=1;
		}
		else {
			$nextShiftTurnover=$logShift+1;
		}
		
		
		addContent(setRange("B35","E35"),$excel,"Cash Assistant - Shift ".$logShift,"true",$ExWs);
		addContent(setRange("G35","J35"),$excel,"Cash Assistant - Shift ".$logShift,"true",$ExWs);


		
	}	
	
	


	
	
	$userSQL="select * from login where username='".$_SESSION['username']."'";
	$userRS=$db->query($userSQL);
	$userRow=$userRS->fetch_assoc();
	
	$user_fullname=$userRow['lastName'].", ".$userRow['firstName'];
	
	addContent(setRange("M38","P38"),$excel,"Printed by: ".$user_fullname,"true",$ExWs);
	$timePrinted=date("Y-m-d H:i:s");
	$timePrintStamp=date("H:iA",strtotime($timePrinted));
	$datePrintStamp=date("m/d/Y",strtotime($timePrinted));
	
	addContent(setRange("Q38","S38"),$excel,"Time Printed: ".$timePrintStamp,"true",$ExWs);
	
	addContent(setRange("T38","U38"),$excel,"Date Printed: ".$datePrintStamp,"true",$ExWs);		
	
	save($ExWb,$excel,$newFilename); 	


//	$newFilename2=str_replace('xls','pdf',$newFilename);
//	savePDF($ExWb,$excel,$newFilename2); 	

	$newFilename2=str_replace('xls','html',$newFilename);
	saveHTML($ExWb,$excel,$newFilename2); 	

	
	echo "SVT Logbook has been generated!  Press right click and Save As: <a href='".$newFilename."'>Here</a>";
	echo "<br>";
	echo "SVT (HTML) Logbook has been generated!  Press right click and Save As: <a href='".$newFilename2."'>Here</a>";
	
?>
