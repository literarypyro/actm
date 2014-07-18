<?php
session_start();
?>
<?php
require_once("phpexcel/Classes/PHPExcel.php");
require_once("phpexcel/Classes/PHPExcel/IOFactory.php");
require("excel functions.php");
?>
<?php
$dsrDate=$_SESSION['log_date'];
$station=$_SESSION['station'];
?>
<?php
function checkQuery($string,$db)
{
    static $mysql_exec_time = 0;
    global $mysql_exec_time;
    
    $start = microtime(true);

	$rs=$db->query($string);
	//    $result = mysql_query($string) OR die(mysql_error());
  //  $mysql_exec_time += (microtime(true) - $start);
    $mysql_exec_time = (microtime(true) - $start);
    
    return $result;
} 

function encapsulateFormula($formula){
	$phrase="=SUM(";
	$phrase.=$formula;
	$phrase.=")";
	return $phrase;
}
?>
<?php 
$db=new mysqli("localhost","root","","finance");
$stationStamp=$station;
if(isset($_GET['ext'])){
	$extSQL="select * from extension where station='".$station."'";
	$extRS=$db->query($extSQL);
	$extNM=$extRS->num_rows;

	if($extNM>0){
		$extRow=$extRS->fetch_assoc();
		$stationStamp=$extRow['extension'];
	}
}
$sql="select * from station where id='".$stationStamp."'";
$rs=$db->query($sql);
$row=$rs->fetch_assoc();

$logStationId=$row['id'];
$logStation=$row['station_name'];
$clause="";

if(isset($_GET['ext'])){
	$clause="?ext=Y";
}

$extAv="select * from extension where station='".$station."'";
$extAvRS=$db->query($extAv);
$extAvNM=$extAvRS->num_rows;

?>

<?php
$rowCount=0;
$dateSlip=date("Y-m-d His");

$filename="treasury forms/DSR.xls";

$newFilename="printout/DSR ".$dateSlip.".xls";
copy($filename,$newFilename);
$workSheetName="Detailed Sales Report";	
$workbookname=$newFilename;
$excel=loadExistingWorkbook($workbookname);

$ExWs=createWorksheet($excel,$workSheetName,"openActive");
	

$db=new mysqli("localhost","root","","finance");

$previousDate=date("Y-m-d",strtotime($dsrDate."-1 day"));

addContent(setRange("F3","H3"),$excel,$logStation,"true",$ExWs);
addContent(setRange("AA3","AE3"),$excel,date("m/d/Y",strtotime($dsrDate)),"true",$ExWs);


$sql="select * from logbook where date='".$dsrDate."' and station='".$station."' order by field(revenue,'open','close'),field(shift,3,1,2)";
//$sql="select * from logbook where date='".$dsrDate."' and station='".$station."' order by shift";

$rs=$db->query($sql);
$nm=$rs->num_rows;	
$rowCount+=8;	
$z=0;
$counter=8;
for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
	$log_id=$row['id'];
	
	$shift=$row['shift'];
	$revenue=$row['revenue'];
	
	
	

	$sql2="select * from control_remittance where remit_log='".$log_id."' and station='".$stationStamp."' group by remit_ticket_seller";

	$rs2=$db->query($sql2);
	$nm2=$rs2->num_rows;
	
	$cash_assistSQL="select * from login where username='".$row['cash_assistant']."'";

	$cash_assistRS=$db->query($cash_assistSQL);
	$cash_assistRow=$cash_assistRS->fetch_assoc();
	
	$cash_assistant=$cash_assistRow['lastName'].", ".$cash_assistRow['firstName'];

	if($nm2>0){	
		$start=0;
		$end=0;
		$for_ca_prefix=$start;	
		$for_ca_suffix=$end;	
		
		for($zz=68;$zz<78;$zz++){
			$sumFormula[chr($zz)]="";
		
		}
		
		for($zz=79;$zz<91;$zz++){
			$sumFormula[chr($zz)]="";
		
		}

		for($zz=65;$zz<75;$zz++){
			$sumFormula["A".chr($zz)]="";
		
		}

		
	
		
		for($k=0;$k<$nm2;$k++){

			if($k==0){
				$start=$rowCount;
				$end=$rowCount+($nm2*1-1);
				$for_ca_prefix=$start;	
				$for_ca_suffix=$end;	
			
			
			}

			$row2=$rs2->fetch_assoc();			
			$ticket_sellerID=$row2['remit_ticket_seller'];
			$ticketsellerSQL="select * from ticket_seller where id='".$row2['remit_ticket_seller']."'";	
			$ticketsellerRS=$db->query($ticketsellerSQL);
			$ticketsellerRow=$ticketsellerRS->fetch_assoc();
			$ticket_seller=$ticketsellerRow['last_name'].", ".$ticketsellerRow['first_name'];
			$ticket_id=$ticketsellerRow['id'];	

//			$allocationSQL="select * from control_sold where control_id in (SELECT control_id FROM control_remittance where remit_log='".$log_id."' and station='".$stationStamp."' and remit_ticket_seller='".$row2['remit_ticket_seller']."')";
			
		
		
		
			$allocationSQL="select * from control_sold inner join control_remittance on control_sold.control_id=control_remittance.control_id where remit_log='".$log_id."' and station='".$stationStamp."' and remit_ticket_seller='".$row2['remit_ticket_seller']."'";
			$allocationRS=$db->query($allocationSQL);
			$allocationNM=$allocationRS->num_rows;
			
			$sjtSold="";
			$sjdSold="";
			$svtSold="";
			$svdSold="";

			for($m=0;$m<$allocationNM;$m++){
				$allocationRow=$allocationRS->fetch_assoc();
				$sjtSold+=$allocationRow['sjt'];
				$sjdSold+=$allocationRow['sjd'];
				$svtSold+=$allocationRow['svt'];
				$svdSold+=$allocationRow['svd'];

			
			
			}			
			
			$adjustmentSQL="select * from control_sales_amount inner join control_remittance on control_sales_amount.control_id=control_remittance.control_id where remit_log='".$log_id."' and station='".$stationStamp."' and remit_ticket_seller='".$row2['remit_ticket_seller']."'";
			
			
			$adjustmentRS=$db->query($adjustmentSQL);
			$adjustmentNM=$adjustmentRS->num_rows;
			$sjtAmount=0;
			$svtAmount=0;
			$sjdAmount=0;
			$svdAmount=0;
			
			
			for($n=0;$n<$adjustmentNM;$n++){
				$adjustmentRow=$adjustmentRS->fetch_assoc();
				$sjtAmount+=$adjustmentRow['sjt']*1;
				$sjdAmount+=$adjustmentRow['sjd']*1;
				$svtAmount+=$adjustmentRow['svt']*1;
				$svdAmount+=$adjustmentRow['svd']*1;
				
			}			
			
			$ot_amount=0;
			$fare_adjustment=0;
			
			$fareSQL="select * from fare_adjustment inner join control_remittance on fare_adjustment.control_id=control_remittance.control_id where remit_log='".$log_id."' and station='".$stationStamp."' and remit_ticket_seller='".$row2['remit_ticket_seller']."'";
			$fareRS=$db->query($fareSQL);
			$fareNM=$fareRS->num_rows;
			$ot_amount=0;	
			for($n=0;$n<$fareNM;$n++){
				$fareRow=$fareRS->fetch_assoc();
				$fare_adjustment+=$fareRow['sjt']+$fareRow['sjd']+$fareRow['svt']+$fareRow['svd']+$fareRow['c'];
				$ot_amount+=$fareRow['ot'];
			}					
			
			$unregSQL="select * from unreg_sale inner join control_remittance on unreg_sale.control_id=control_remittance.control_id where remit_log='".$log_id."' and station='".$stationStamp."' and remit_ticket_seller='".$row2['remit_ticket_seller']."'";
			$unregRS=$db->query($unregSQL);
			
			$unregNM=$unregRS->num_rows;
			
			$sj_unreg=0;
			$sv_unreg=0;
			
			for($m=0;$m<$unregNM;$m++){
				$unregRow=$unregRS->fetch_assoc();
				$sj_unreg+=$unregRow['sj']*1;
				$sv_unreg+=$unregRow['sv']*1;
			
			}	

			//$discountSQL="select * from discount where control_id in (SELECT control_id FROM control_remittance where remit_log='".$log_id."' and station='".$stationStamp."' and remit_ticket_seller='".$row2['remit_ticket_seller']."')";
		

			$discountSQL="select * from discount inner join control_remittance on discount.control_id=control_remittance.control_id where remit_log='".$log_id."' and station='".$stationStamp."' and remit_ticket_seller='".$row2['remit_ticket_seller']."'";
			$discountRS=$db->query($discountSQL);
			
			$discountNM=$discountRS->num_rows;
			
			$sv_discount=0;
			$sj_discount=0;
			
			for($m=0;$m<$discountNM;$m++){
				$discountRow=$discountRS->fetch_assoc();
				$sj_discount+=$discountRow['sj']*1;
				$sv_discount+=$discountRow['sv']*1;
			
			}
			
			$refundSQL="select * from refund inner join control_remittance on refund.control_id=control_remittance.control_id where remit_log='".$log_id."' and station='".$stationStamp."' and remit_ticket_seller='".$row2['remit_ticket_seller']."'";
			$refundRS=$db->query($refundSQL);
			$refundNM=$refundRS->num_rows;

			$sv_refund=0;
			$sj_refund=0;
			$sj_r_amount=0;
			$sv_r_amount=0;

			for($m=0;$m<$refundNM;$m++){
				$refundRow=$refundRS->fetch_assoc();
				$sj_refund+=$refundRow['sj']*1;
				$sv_refund+=$refundRow['sv']*1;

				$sj_r_amount+=$refundRow['sj_amount']*1;
				$sv_r_amount+=$refundRow['sv_amount']*1;
			}

			$cashSQL="select sum(if(discrepancy.type='overage',amount,0)) as overage,sum(if(discrepancy.type='shortage',amount,0)) as unpaid_shortage from discrepancy inner join cash_transfer on discrepancy.transaction_id=cash_transfer.transaction_id where discrepancy.log_id='".$log_id."' and discrepancy.ticket_seller='".$row2['remit_ticket_seller']."' and cash_transfer.station='".$stationStamp."'";
			$cashRS=$db->query($cashSQL);
			$cashNM=$cashRS->num_rows;
			
			$overage=0;
			$unpaid_shortage=0;
			
			for($n=0;$n<$cashNM;$n++){
				$cashRow=$cashRS->fetch_assoc();
				$overage+=$cashRow['overage']*1;
				$unpaid_shortage+=$cashRow['unpaid_shortage']*1;
			}
			
		
			$discrepancySQL="SELECT * FROM transaction inner join cash_transfer on transaction.transaction_id=cash_transfer.transaction_id where transaction_type='shortage' and transaction.log_id='".$log_id."' and cash_transfer.station='".$stationStamp."' and cash_transfer.ticket_seller='".$row2['remit_ticket_seller']."'";
			$discrepancyRS=$db->query($discrepancySQL);

			$discrepancyNM=$discrepancyRS->num_rows;
			
			$paid_shortage=0;

			if($discrepancyNM>0){
				for($aa=0;$aa<$discrepancyNM;$aa++){
					$discrepancyRow=$discrepancyRS->fetch_assoc();
					$paid_shortage+=$discrepancyRow['net_revenue']+$discrepancyRow['total'];
				}
			}
			$unpaid_shortage-=$paid_shortage;				
			
//			$unsoldSQL="select type,sum(loose_good) as ticket_sum,sum(loose_defective) as ticket_sum2 from control_unsold where control_id in (SELECT control_id FROM control_remittance where remit_log='".$log_id."' and station='".$stationStamp."' and remit_ticket_seller='".$row2['remit_ticket_seller']."') group by type";


			$unsoldSQL="select type,sum(loose_good) as ticket_sum,sum(loose_defective) as ticket_sum2 from control_unsold inner join control_remittance on control_unsold.control_id=control_remittance.control_id where remit_log='".$log_id."' and station='".$stationStamp."' and remit_ticket_seller='".$row2['remit_ticket_seller']."' group by type";
			$unsoldRS=$db->query($unsoldSQL);
			$unsoldNM=$unsoldRS->num_rows;
			
			$sjtLoose=0;
			$sjdLoose=0;
			$svtLoose=0;
			$svdLoose=0;
			
			$sjtDefective=0;
			$sjdDefective=0;
			$svtDefective=0;
			$svdDefective=0;


			for($m=0;$m<$unsoldNM;$m++){
				$unsoldRow=$unsoldRS->fetch_assoc();
				if($unsoldRow['type']=='sjt'){
					$sjtLoose=$unsoldRow['ticket_sum'];	
					$sjtDefective=$unsoldRow['ticket_sum2'];

				
				}
				else if($unsoldRow['type']=='sjd'){
					$sjdLoose=$unsoldRow['ticket_sum'];	
					$sjdDefective=$unsoldRow['ticket_sum2'];

					
				}
				else if($unsoldRow['type']=='svt'){
					$svtLoose=$unsoldRow['ticket_sum'];	
					$svtDefective=$unsoldRow['ticket_sum2'];
					
				}
				else if($unsoldRow['type']=='svd'){
					$svdLoose=$unsoldRow['ticket_sum'];	
					$svdDefective=$unsoldRow['ticket_sum2'];
					
				}
			
			
			}
			//$discrepancyTicketSQL="select *,sum(amount) as new_amount from discrepancy_ticket where transaction_id in (select control_slip.id from control_slip inner join remittance on control_slip.id=remittance.control_id where remittance.log_id='".$log_id."' and control_slip.ticket_seller='".$row2['remit_ticket_seller']."' and control_slip.station='".$stationStamp."') group by ticket_type";

			$discrepancyTicketSQL="select *,sum(amount) as new_amount from discrepancy_ticket inner join control_remittance on transaction_id=control_id where remit_log='".$log_id."' and control_remittance.ticket_seller='".$row2['remit_ticket_seller']."' and station='".$stationStamp."' group by ticket_type";
//			where transaction_id in (select control_slip.id from control_slip inner join remittance on control_slip.id=remittance.control_id where remittance.log_id='".$log_id."' and control_slip.ticket_seller='".$row2['remit_ticket_seller']."' and control_slip.station='".$stationStamp."') group by ticket_type";
			
//			$discrepancyTicketSQL2="select *,sum(amount) as new_amount from discrepancy_ticket inner join control_slip on discrepancy_ticket.transaction_id=concat('control_',control_slip.id) where discrepancy_ticket.log_id='".$log_id."' and discrepancy_ticket.ticket_seller='".$row2['remit_ticket_seller']."' and control_slip.station='".$stationStamp."' group by ticket_type";
		//	echo $discrepancyTicketSQL2."<br>";
		//			$discrepancyTicketSQL="select *,sum(amount) as new_amount from discrepancy_ticket inner join ticket_order on discrepancy_ticket.transaction_id=ticket_order.transaction_id where discrepancy_ticket.log_id='".$log_id."' and discrepancy_ticket.ticket_seller='".$row2['remit_ticket_seller']."' and ticket_order.station='".$stationStamp."' group by ticket_type";
			$discrepancyTicketRS=$db->query($discrepancyTicketSQL);
			$discrepancyTicketNM=$discrepancyTicketRS->num_rows;
			
		//	$paid_shortage=0;
			$sjt_label="0";
			$sjd_label="0";
			$svt_label="0";
			$svd_label="0";

			$sjt_discrepancy=0;	
			$sjd_discrepancy=0;	
			$svt_discrepancy=0;	
			$svd_discrepancy=0;	
			
			$subtotal['sjt_discrepancy']=0; 
			$subtotal['sjd_discrepancy']=0; 
			$subtotal['svt_discrepancy']=0; 
			$subtotal['svd_discrepancy']=0; 			
			
			if($discrepancyTicketNM>0){
			
				for($n=0;$n<$discrepancyTicketNM;$n++){
					$discrepRow=$discrepancyTicketRS->fetch_assoc();	
					if($discrepRow['ticket_type']=="sjt"){
						$sjt_discrepancy=$discrepRow['new_amount'];
						
						if($discrepRow['type']=="shortage"){
						//	$sjt_label="(".$sjt_discrepancy.")";
						//	$subtotal['sjt_discrepancy']-=$sjt_discrepancy;
							$sjt_discrepancy*=-1;
						
						}
						else if($discrepRow['type']=="overage"){
//							$sjt_label=$sjt_discrepancy;
//							$subtotal['sjt_discrepancy']+=$sjt_discrepancy;
							$sjt_discrepancy*=1;
							
							
						}

					}
					else if($discrepRow['ticket_type']=="sjd"){
						$sjd_discrepancy=$discrepRow['new_amount'];
					
						if($discrepRow['type']=="shortage"){
							//$sjd_label="(".$sjd_discrepancy.")";
							//$subtotal['sjd_discrepancy']-=$sjd_discrepancy;
							$sjd_discrepancy*=-1;
						}
						else if($discrepRow['type']=="overage"){
						//	$sjd_label=$sjd_discrepancy;
						//	$subtotal['sjd_discrepancy']+=$sjd_discrepancy;

							$sjd_discrepancy*=1;
							
						}
					}
					else if($discrepRow['ticket_type']=="svt"){
						$svt_discrepancy=$discrepRow['new_amount'];
					
						if($discrepRow['type']=="shortage"){
//							$svt_label="(".$svt_discrepancy.")";
//							$subtotal['svt_discrepancy']-=$svt_discrepancy;
							
							$svt_discrepancy*=-1;
						
						}
						else if($discrepRow['type']=="overage"){
//							$svt_label=$svt_discrepancy;
//							$subtotal['svt_discrepancy']+=$svt_discrepancy;
							$svt_discrepancy*=1;
						}
					}
					else if($discrepRow['ticket_type']=="svd"){
						$svd_discrepancy=$discrepRow['new_amount'];
					
						if($discrepRow['type']=="shortage"){
//							$svd_label="(".$svd_discrepancy.")";
//							$subtotal['svd_discrepancy']-=$svd_discrepancy;
							$svd_discrepancy*=-1;
						
						}
						else if($discrepRow['type']=="overage"){
//							$svd_label=$svd_discrepancy;
//							$subtotal['svd_discrepancy']+=$svd_discrepancy;
							$svd_discrepancy*=1;

						}
					
					
					}
					
					
				
				}
			}			
			
			
			if($k==0){
			}
			else {
				if($counter>=27){
					$counter=8;
					
					$cont=$rowCount-27;
					$compare=$rowCount;	
					
//					$compare=$rowCount+27;
					//if($compare>$end){
					if($compare<$end){

						for($zz=68;$zz<78;$zz++){
							$sumFormula[chr($zz)].=chr($zz).$start.":".chr($zz).($rowCount*1).",";
						}
						
						for($zz=79;$zz<91;$zz++){
							$sumFormula[chr($zz)].=chr($zz).$start.":".chr($zz).($rowCount*1).",";
						}

						for($zz=65;$zz<75;$zz++){
							$sumFormula["A".chr($zz)].="A".chr($zz).$start.":A".chr($zz).($rowCount*1).",";
						}
					}
					
					$rowCount+=19;

					$rowCount+=8;
//					if($compare>$end){
					if($compare<$end){

						$start=$rowCount;
						$end+=26;
					}
					
				}
				else {
					$counter++;
					$rowCount++;
				}
			}
				addContent(setRange("B".$rowCount,"B".$rowCount),$excel,$ticket_sellerID,"true",$ExWs);

				addContent(setRange("C".$rowCount,"C".$rowCount),$excel,$ticket_seller,"true",$ExWs);
			
				addContent(setRange("D".$rowCount,"D".$rowCount),$excel,$sjtSold,"true",$ExWs);
				addContent(setRange("F".$rowCount,"F".$rowCount),$excel,$sjdSold,"true",$ExWs);
				addContent(setRange("H".$rowCount,"H".$rowCount),$excel,$svdSold,"true",$ExWs);
				addContent(setRange("J".$rowCount,"J".$rowCount),$excel,$svtSold,"true",$ExWs);
			
				addContent(setRange("E".$rowCount,"E".$rowCount),$excel,$sjtAmount,"true",$ExWs);
				addContent(setRange("G".$rowCount,"G".$rowCount),$excel,$sjdAmount,"true",$ExWs);
				addContent(setRange("I".$rowCount,"I".$rowCount),$excel,$svdAmount,"true",$ExWs);
				addContent(setRange("K".$rowCount,"K".$rowCount),$excel,$svtAmount,"true",$ExWs);

				addContent(setRange("L".$rowCount,"L".$rowCount),$excel,$fare_adjustment,"true",$ExWs);
				addContent(setRange("M".$rowCount,"M".$rowCount),$excel,$ot_amount,"true",$ExWs);
			
				addContent(setRange("O".$rowCount,"O".$rowCount),$excel,($sj_unreg*1+$sv_unreg*1),"true",$ExWs);

				addContent(setRange("P".$rowCount,"P".$rowCount),$excel,($sj_discount),"true",$ExWs);
				addContent(setRange("Q".$rowCount,"Q".$rowCount),$excel,($sv_discount),"true",$ExWs);

				addContent(setRange("R".$rowCount,"R".$rowCount),$excel,($sj_refund),"true",$ExWs);

				addContent(setRange("S".$rowCount,"S".$rowCount),$excel,($sj_r_amount),"true",$ExWs);
				addContent(setRange("T".$rowCount,"T".$rowCount),$excel,($sv_refund),"true",$ExWs);
				addContent(setRange("U".$rowCount,"U".$rowCount),$excel,($sv_r_amount),"true",$ExWs);
							
				addContent(setRange("V".$rowCount,"V".$rowCount),$excel,($overage),"true",$ExWs);

				addContent(setRange("W".$rowCount,"W".$rowCount),$excel,($paid_shortage),"true",$ExWs);
				addContent(setRange("X".$rowCount,"X".$rowCount),$excel,($unpaid_shortage),"true",$ExWs);

				/*
				addContent(setRange("Y".$rowCount,"Y".$rowCount),$excel,($sjtLoose),"true",$ExWs);
				addContent(setRange("Z".$rowCount,"Z".$rowCount),$excel,($sjdLoose),"true",$ExWs);
				addContent(setRange("AA".$rowCount,"AA".$rowCount),$excel,($svdLoose),"true",$ExWs);
				addContent(setRange("AB".$rowCount,"AB".$rowCount),$excel,($svtLoose),"true",$ExWs);
				*/

				addContent(setRange("Y".$rowCount,"Y".$rowCount),$excel,$sjt_discrepancy,"true",$ExWs);
				addContent(setRange("Z".$rowCount,"Z".$rowCount),$excel,$sjd_discrepancy,"true",$ExWs);
				addContent(setRange("AB".$rowCount,"AB".$rowCount),$excel,$svt_discrepancy,"true",$ExWs);
				addContent(setRange("AA".$rowCount,"AA".$rowCount),$excel,$svd_discrepancy,"true",$ExWs);

				
				addContent(setRange("AC".$rowCount,"AC".$rowCount),$excel,$sjtDefective,"true",$ExWs);
				addContent(setRange("AD".$rowCount,"AD".$rowCount),$excel,$sjdDefective,"true",$ExWs);
				addContent(setRange("AE".$rowCount,"AE".$rowCount),$excel,$svdDefective,"true",$ExWs);
				addContent(setRange("AF".$rowCount,"AF".$rowCount),$excel,$svtDefective,"true",$ExWs);

				/*
				addContent(setRange("AG".$rowCount,"AG".$rowCount),$excel,$sjtDefective,"true",$ExWs);
				addContent(setRange("AH".$rowCount,"AH".$rowCount),$excel,$sjdDefective,"true",$ExWs);
				addContent(setRange("AI".$rowCount,"AI".$rowCount),$excel,$svdDefective,"true",$ExWs);
				addContent(setRange("AJ".$rowCount,"AJ".$rowCount),$excel,$svtDefective,"true",$ExWs);
				*/

				
		}

		addContent(setRange("A".$for_ca_prefix,"A".$for_ca_suffix),$excel,$cash_assistant,"true",$ExWs);


		
	if($counter>=27){
		$counter=8;
		$rowCount+=19;

		$rowCount+=8;

	}
	else {
		$counter++;
		$rowCount++;
	}
	addContent(setRange("A".$rowCount,"C".$rowCount),$excel,"Subtotal","true",$ExWs);
	$excel->getActiveSheet()->getStyle("A".$rowCount.":C".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//	$excel->getActiveSheet()->getStyle("A".$rowCount.":AJ".$rowCount)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB("00ffff99");

	$excel->getActiveSheet()->getStyle("A".$rowCount.":AJ".$rowCount)->getFont()->setBold(true);

	for($zz=68;$zz<78;$zz++){
		$sumFormula[chr($zz)].=chr($zz).$start.":".chr($zz).$end;

		addContent(setRange(chr($zz).$rowCount,chr($zz).$rowCount),$excel,encapsulateFormula($sumFormula[chr($zz)]),"true",$ExWs);

	}
			
	for($zz=79;$zz<91;$zz++){
		$sumFormula[chr($zz)].=chr($zz).$start.":".chr($zz).$end;
		addContent(setRange(chr($zz).$rowCount,chr($zz).$rowCount),$excel,encapsulateFormula($sumFormula[chr($zz)]),"true",$ExWs);

	}

	for($zz=65;$zz<71;$zz++){
		$sumFormula["A".chr($zz)].="A".chr($zz).$start.":A".chr($zz).$end;
		addContent(setRange("A".chr($zz).$rowCount,"A".chr($zz).$rowCount),$excel,encapsulateFormula($sumFormula["A".chr($zz)]),"true",$ExWs);
		
	}
/*
	
	addContent(setRange("D".$rowCount,"D".$rowCount),$excel,"=sum(D".$start.":D".$end.")","true",$ExWs);
	addContent(setRange("E".$rowCount,"E".$rowCount),$excel,"=sum(E".$start.":E".$end.")","true",$ExWs);
	addContent(setRange("F".$rowCount,"F".$rowCount),$excel,"=sum(F".$start.":F".$end.")","true",$ExWs);
	addContent(setRange("G".$rowCount,"G".$rowCount),$excel,"=sum(G".$start.":G".$end.")","true",$ExWs);
	addContent(setRange("H".$rowCount,"H".$rowCount),$excel,"=sum(H".$start.":H".$end.")","true",$ExWs);
	addContent(setRange("I".$rowCount,"I".$rowCount),$excel,"=sum(I".$start.":I".$end.")","true",$ExWs);
	addContent(setRange("J".$rowCount,"J".$rowCount),$excel,"=sum(J".$start.":J".$end.")","true",$ExWs);
	addContent(setRange("K".$rowCount,"K".$rowCount),$excel,"=sum(K".$start.":K".$end.")","true",$ExWs);
	addContent(setRange("L".$rowCount,"L".$rowCount),$excel,"=sum(L".$start.":L".$end.")","true",$ExWs);
	addContent(setRange("M".$rowCount,"M".$rowCount),$excel,"=sum(M".$start.":M".$end.")","true",$ExWs);

	addContent(setRange("O".$rowCount,"O".$rowCount),$excel,"=sum(O".$start.":O".$end.")","true",$ExWs);
	addContent(setRange("P".$rowCount,"P".$rowCount),$excel,"=sum(P".$start.":P".$end.")","true",$ExWs);
	addContent(setRange("Q".$rowCount,"Q".$rowCount),$excel,"=sum(Q".$start.":Q".$end.")","true",$ExWs);
	addContent(setRange("R".$rowCount,"R".$rowCount),$excel,"=sum(R".$start.":R".$end.")","true",$ExWs);
	addContent(setRange("S".$rowCount,"S".$rowCount),$excel,"=sum(S".$start.":S".$end.")","true",$ExWs);
	addContent(setRange("T".$rowCount,"T".$rowCount),$excel,"=sum(T".$start.":T".$end.")","true",$ExWs);
	addContent(setRange("U".$rowCount,"U".$rowCount),$excel,"=sum(U".$start.":U".$end.")","true",$ExWs);
	addContent(setRange("V".$rowCount,"V".$rowCount),$excel,"=sum(V".$start.":V".$end.")","true",$ExWs);
	addContent(setRange("W".$rowCount,"W".$rowCount),$excel,"=sum(W".$start.":W".$end.")","true",$ExWs);
	addContent(setRange("X".$rowCount,"X".$rowCount),$excel,"=sum(X".$start.":X".$end.")","true",$ExWs);
	addContent(setRange("Y".$rowCount,"Y".$rowCount),$excel,"=sum(Y".$start.":Y".$end.")","true",$ExWs);
	addContent(setRange("Z".$rowCount,"Z".$rowCount),$excel,"=sum(Z".$start.":Z".$end.")","true",$ExWs);
	addContent(setRange("AA".$rowCount,"AA".$rowCount),$excel,"=sum(AA".$start.":AA".$end.")","true",$ExWs);
	addContent(setRange("AB".$rowCount,"AB".$rowCount),$excel,"=sum(AB".$start.":AB".$end.")","true",$ExWs);
	addContent(setRange("AC".$rowCount,"AC".$rowCount),$excel,"=sum(AC".$start.":AC".$end.")","true",$ExWs);
	addContent(setRange("AD".$rowCount,"AD".$rowCount),$excel,"=sum(AD".$start.":AD".$end.")","true",$ExWs);
	addContent(setRange("AE".$rowCount,"AE".$rowCount),$excel,"=sum(AE".$start.":AE".$end.")","true",$ExWs);
	addContent(setRange("AF".$rowCount,"AF".$rowCount),$excel,"=sum(AF".$start.":AF".$end.")","true",$ExWs);
	addContent(setRange("AG".$rowCount,"AG".$rowCount),$excel,"=sum(AG".$start.":AG".$end.")","true",$ExWs);
	addContent(setRange("AH".$rowCount,"AH".$rowCount),$excel,"=sum(AH".$start.":AH".$end.")","true",$ExWs);
	addContent(setRange("AI".$rowCount,"AI".$rowCount),$excel,"=sum(AI".$start.":AI".$end.")","true",$ExWs);
	addContent(setRange("AJ".$rowCount,"AJ".$rowCount),$excel,"=sum(AJ".$start.":AJ".$end.")","true",$ExWs);
*/
	$grid[$z]=$rowCount;
	$z++;
	if($counter>=27){
		$counter=8;
		$rowCount+=19;

		$rowCount+=8;

	}
	else {
		$counter++;
		$rowCount++;
	}
	}	
	
	if($shift==1){
		$s1_ca=$cash_assistant;
	}
	else if($shift==2){
		$s2_ca=$cash_assistant;
	}
	else if(($shift==3)&&($revenue=='close')){
		$s3_ca=$cash_assistant;
	}	
}	


$gridCount=count($grid);

addContent(setRange("A".$rowCount,"C".$rowCount),$excel,"Grand Total","true",$ExWs);
$excel->getActiveSheet()->getStyle("A".$rowCount.":C".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//$excel->getActiveSheet()->getStyle("A".$rowCount.":AJ".$rowCount)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB("00339966");
$excel->getActiveSheet()->getStyle("A".$rowCount.":AJ".$rowCount)->getFont()->setBold(true);
$counter++;

$prefix[0]="D";
$prefix[1]="E";
$prefix[2]="F";
$prefix[3]="G";
$prefix[4]="H";
$prefix[5]="I";
$prefix[6]="J";
$prefix[7]="K";
$prefix[8]="L";
$prefix[9]="M";
$prefix[10]="O";
$prefix[11]="P";
$prefix[12]="Q";
$prefix[13]="R";
$prefix[14]="T";
$prefix[15]="U";
$prefix[16]="V";
$prefix[17]="W";
$prefix[18]="X";
$prefix[19]="Y";
$prefix[20]="Z";
$prefix[21]="AA";
$prefix[22]="AB";
$prefix[23]="AC";
$prefix[24]="AD";
$prefix[25]="AE";
$prefix[26]="AF";
$prefix[27]="S";



		



/*
$prefix[27]="AG";
$prefix[28]="AH";
$prefix[29]="AI";
$prefix[30]="AJ";
*/

for($b=0;$b<count($prefix);$b++){

$gridLabel="=sum(";
for($i=0;$i<$gridCount;$i++){
	if($i==($gridCount*1-1)){
		$gridLabel.=$prefix[$b].$grid[$i];	
		
	}
	else {
		$gridLabel.=$prefix[$b].$grid[$i].",";
	}
}
$gridLabel.=")";

if($gridCount==0){
$gridLabel="";
}


addContent(setRange($prefix[$b].$rowCount,$prefix[$b].$rowCount),$excel,$gridLabel,"true",$ExWs);

}

	if($counter<27){
		$excel->getActiveSheet()->removeRow(($rowCount+1),(28-$counter));
			
		$rowCount+=20;
		$excel->getActiveSheet()->removeRow(($rowCount),2000);
				
			
	}



$sql="select * from logbook where date='".$dsrDate."' and station='".$station."' order by field(revenue,'open','close'),field(shift,3,1,2)";
//$sql="select * from logbook where date='".$dsrDate."' and station='".$station."' order by shift";

$rs=$db->query($sql);
$nm=$rs->num_rows;

$sjt_sales=0;
$sjd_sales=0;
$svt_sales=0;
$svd_sales=0;

$fare_adjustment=0;
$ot_amount=0;
$unreg_sale=0;

$discount=0;
$refund=0;

$grandTotal=0;
$deductionsTotal=0;
$netSales=0;

for($i=0;$i<$nm;$i++){


	$row=$rs->fetch_assoc();
	$log_id=$row['id'];

	$sql2="select * from control_sales_amount inner join control_remittance on control_sales_amount.control_id=control_remittance.control_id where remit_log='".$log_id."' and station='".$stationStamp."'";

	$rs2=$db->query($sql2);
	$nm2=$rs2->num_rows;	
	for($k=0;$k<$nm2;$k++){
		$row2=$rs2->fetch_assoc();
		$sjt_sales+=$row2['sjt']*1;		
		$svt_sales+=$row2['svt']*1;
		$sjd_sales+=$row2['sjd']*1;
		$svd_sales+=$row2['svd']*1;
			
	}

	$sql2="select sum(sjt+sjd+svt+svd+c+ot) as fare_adjustment  from fare_adjustment inner join control_remittance on fare_adjustment.control_id=control_remittance.control_id where remit_log='".$log_id."' and station='".$stationStamp."'";
	$rs2=$db->query($sql2);
	$nm2=$rs2->num_rows;	
	for($k=0;$k<$nm2;$k++){
		$row2=$rs2->fetch_assoc();
		$fare_adjustment+=$row2['fare_adjustment'];
	}	
	
//	$sql2="select sum(sj+sv) as unreg_sale from unreg_sale where control_id in (SELECT control_id FROM control_remittance where remit_log='".$log_id."' and station='".$stationStamp."')";



	$sql2="select sum(sjt+sjd+svt+svd) as unreg_sale from unreg_sale inner join control_remittance on unreg_sale.control_id=control_remittance.control_id  where remit_log='".$log_id."' and station='".$stationStamp."'";
	$rs2=$db->query($sql2);
	$nm2=$rs2->num_rows;	
	for($k=0;$k<$nm2;$k++){
		$row2=$rs2->fetch_assoc();
		$unreg_sale+=$row2['unreg_sale'];
	}		


//	$sql2="select sum(ot) as ot from control_cash where control_id in (SELECT control_id FROM control_remittance where remit_log='".$log_id."' and station='".$stationStamp."') group by type";

	$sql2="select sum(ot) as ot from control_cash inner join control_remittance on control_cash.control_id=control_remittance.control_id  where remit_log='".$log_id."' and station='".$stationStamp."'";
	$rs2=$db->query($sql2);
	$nm2=$rs2->num_rows;	
	for($k=0;$k<$nm2;$k++){
		$row2=$rs2->fetch_assoc();
		$ot_amount+=$row2['ot'];
	}	
	
//	$sql2="select sum(sj+sv) as discount from discount where control_id in (SELECT control_id FROM control_remittance where remit_log='".$log_id."' and station='".$stationStamp."')";

	$sql2="select sum(sj+sv) as discount from discount inner join control_remittance on discount.control_id=control_remittance.control_id  where remit_log='".$log_id."' and station='".$stationStamp."'";
	$rs2=$db->query($sql2);
	$nm2=$rs2->num_rows;	
	for($k=0;$k<$nm2;$k++){
		$row2=$rs2->fetch_assoc();
		$discount+=$row2['discount'];
	}		
	
//	$sql2="select sum(sj_amount+sv_amount) as refund from refund where control_id in (SELECT control_id FROM control_remittance where remit_log='".$log_id."' and station='".$stationStamp."')";
	
	
	
	
	$sql2="select sum(sj_amount+sv_amount) as refund from refund inner join control_remittance on refund.control_id=control_remittance.control_id  where remit_log='".$log_id."' and station='".$stationStamp."'";
	$rs2=$db->query($sql2);
	$nm2=$rs2->num_rows;	
	for($k=0;$k<$nm2;$k++){
		$row2=$rs2->fetch_assoc();
		$refund+=$row2['refund'];
	}	
	
}

$grandTotal+=$sjt_sales;
$grandTotal+=$sjd_sales;
$grandTotal+=$svt_sales;
$grandTotal+=$svd_sales;

$grandTotal+=$fare_adjustment;
$grandTotal+=$ot_amount;
$grandTotal+=$unreg_sale;

$unreg_deduction=$unreg_sale;

$deductionsTotal+=$discount;
$deductionsTotal+=$refund;

$netSales=$grandTotal-$deductionsTotal;

	addContent(setRange("C33","C33"),$excel,$sjt_sales,"true",$ExWs);
	addContent(setRange("C34","C34"),$excel,$sjd_sales+$svd_sales,"true",$ExWs);
	addContent(setRange("C35","C35"),$excel,$svt_sales,"true",$ExWs);
	addContent(setRange("C36","C36"),$excel,$fare_adjustment,"true",$ExWs);
	addContent(setRange("C37","C37"),$excel,$ot_amount,"true",$ExWs);
	addContent(setRange("C38","C38"),$excel,$unreg_sale,"true",$ExWs);

	//addContent(setRange("C39","C39"),$excel,"=sum(C33:C38)","true",$ExWs);
	addContent(setRange("C40","C40"),$excel,$refund,"true",$ExWs);
	addContent(setRange("C41","C41"),$excel,$discount,"true",$ExWs);

$sql="select * from logbook where date='".$dsrDate."' and station='".$station."' order by field(revenue,'open','close'),field(shift,3,1,2)";

$rs=$db->query($sql);
$nm=$rs->num_rows;

$sjt_beginning_balance=0;
$sjd_beginning_balance=0;
$svt_beginning_balance=0;
$svd_beginning_balance=0;

$sjt_initial_amount=0;
$sjd_initial_amount=0;
$svt_initial_amount=0;
$svd_initial_amount=0;

$sjt_additional_amount=0;	
$sjd_additional_amount=0;	
$svt_additional_amount=0;	
$svd_additional_amount=0;	

$sjt_subtotal=0;	
$sjd_subtotal=0;	
$svt_subtotal=0;	
$svd_subtotal=0;	

$sjt_deductions=0;	
$sjd_deductions=0;	
$svt_deductions=0;	
$svd_deductions=0;	

$sjt_sold=0;	
$sjd_sold=0;	
$svt_sold=0;	
$svd_sold=0;

$sjt_loose=0;	
$sjd_loose=0;	
$svt_loose=0;	
$svd_loose=0;	

$sjt_defective=0;	
$sjd_defective=0;	
$svt_defective=0;	
$svd_defective=0;	


$sjt_overage=0;
$sjd_overage=0;
$svt_overage=0;
$svd_overage=0;

$sjt_shortage=0;
$sjd_shortage=0;
$svt_shortage=0;
$svd_shortage=0;

$sjt_discrep=0;
$sjd_discrep=0;
$svt_discrep=0;
$svd_discrep=0;

$sjt_label="";
$sjd_label="";
$svt_label="";
$svd_label="";

$sjt_physically_defective=0;
$sjd_physically_defective=0;
$svt_physically_defective=0;
$svd_physically_defective=0;

for($i=0;$i<$nm;$i++){
	if($i==0){

		$row=$rs->fetch_assoc();
		$log_id=$row['id'];
		
		$sql2="select * from beginning_balance_sjt where log_id='".$log_id."'";

		$rs2=$db->query($sql2);
		$row2=$rs2->fetch_assoc();
		
		$sjt_beginning_balance=$row2['sjt']+$row2['sjt_loose'];
		$sjd_beginning_balance=$row2['sjd']+$row2['sjd_loose'];
		

		
		$sql2="select * from beginning_balance_svt where log_id='".$log_id."'";
		$rs2=$db->query($sql2);
		$row2=$rs2->fetch_assoc();

		$svt_beginning_balance=$row2['svt']+$row2['svt_loose'];
		$svd_beginning_balance=$row2['svd']+$row2['svd_loose'];
		
			
	
	}
	else {

		$row=$rs->fetch_assoc();
		$log_id=$row['id'];
	}
	
	$sql2="select * from transaction inner join ticket_order on transaction.transaction_id=ticket_order.transaction_id where transaction.log_id='".$log_id."' and log_type='finance'";
	$rs2=$db->query($sql2);
	$nm2=$rs2->num_rows;	
	if($nm2>0){
		for($k=0;$k<$nm2;$k++){
			$row2=$rs2->fetch_assoc();
			$sjt_initial_amount+=$row2['sjt']+$row2['sjt_loose'];
			$sjd_initial_amount+=$row2['sjd']+$row2['sjd_loose'];
			$svt_initial_amount+=$row2['svt']+$row2['svt_loose'];
			$svd_initial_amount+=$row2['svd']+$row2['svd_loose'];
	
		}
	}

	$sql2="select * from transaction inner join ticket_order on transaction.transaction_id=ticket_order.transaction_id where transaction.log_id='".$log_id."' and log_type='annex'";
	$rs2=$db->query($sql2);
	$nm2=$rs2->num_rows;	
	if($nm2>0){
		for($k=0;$k<$nm2;$k++){
			$row2=$rs2->fetch_assoc();

			$sjt_additional_amount+=$row2['sjt']+$row2['sjt_loose'];	
			$sjd_additional_amount+=$row2['sjd']+$row2['sjd_loose'];	
			$svt_additional_amount+=$row2['svt']+$row2['svt_loose'];	
			$svd_additional_amount+=$row2['svd']+$row2['svd_loose'];	
		
		
		}
	}	

	$sjt_subtotal=$sjt_beginning_balance+$sjt_initial_amount+$sjt_additional_amount;	
	$sjd_subtotal=$sjd_beginning_balance+$sjd_initial_amount+$sjd_additional_amount;	
	$svt_subtotal=$svt_beginning_balance+$svt_initial_amount+$svt_additional_amount;	
	$svd_subtotal=$svd_beginning_balance+$svd_initial_amount+$svd_additional_amount;	

	$sql2="select sum(sjt) as sjt,sum(sjd) as sjd,sum(svt) as svt, sum(svd) as svd from control_sold inner join control_remittance on control_sold.control_id=control_remittance.control_id where log_id='".$log_id."'";
	$rs2=$db->query($sql2);
	$nm2=$rs2->num_rows;
	
	if($nm2>0){
		$row2=$rs2->fetch_assoc();
		$sjt_sold+=$row2['sjt'];
		$sjd_sold+=$row2['sjd'];
		$svt_sold+=$row2['svt'];
		$svd_sold+=$row2['svd'];
	
	}

	$sql2="select * from control_unsold inner join remittance on control_unsold.control_id=remittance.control_id where log_id='".$log_id."'";
	$rs2=$db->query($sql2);
	$nm2=$rs2->num_rows;	
	for($k=0;$k<$nm2;$k++){

		$row2=$rs2->fetch_assoc();
		if($row2['type']=="sjt"){
			$sjt_loose+=$row2['loose_good'];

			$sjt_defective+=$row2['loose_defective'];	

			
		
		}
		else if($row2['type']=="sjd"){
			$sjd_loose+=$row2['loose_good'];

			$sjd_defective+=$row2['loose_defective'];	

		}
		else if($row2['type']=="svt"){
			$svt_loose+=$row2['loose_good'];

			$svt_defective+=$row2['loose_defective'];	

		}
		else if($row2['type']=="svd"){
			$svd_loose+=$row2['loose_good'];

			$svd_defective+=$row2['loose_defective'];	

		}
		
			
	}
	$sjt_deduction=$sjt_defective+$sjt_sold;	
	$sjd_deduction=$sjd_defective+$sjd_sold;	

	$svt_deduction=$svt_defective+$svt_sold;	
	$svd_deduction=$svd_defective+$svd_sold;		

	$sql2="select sum(amount) as ticket_sum,ticket_type,type from discrepancy_ticket where log_id='".$log_id."' group by ticket_type";
	$rs2=$db->query($sql2);
	$nm2=$rs2->num_rows;		
	
	for($k=0;$k<$nm2;$k++){
		$row2=$rs2->fetch_assoc();
		if($row2['ticket_type']=="sjt"){
			if($row2['type']=="shortage"){
				$sjt_shortage+=$row2['ticket_sum'];
			}
			else if($row2['type']=="overage"){
				$sjt_overage+=$row2['ticket_sum'];
			
			}

			
		
		}
		else if($row2['ticket_type']=="sjd"){
			if($row2['type']=="shortage"){
				$sjd_shortage+=$row2['ticket_sum'];
			
			}
			else if($row2['type']=="overage"){
				$sjd_overage+=$row2['ticket_sum'];
			
			}

		}
		else if($row2['ticket_type']=="svt"){
			if($row2['type']=="shortage"){
				$svt_shortage+=$row2['ticket_sum'];
			
			}
			else if($row2['type']=="overage"){
				$svt_overage+=$row2['ticket_sum'];
			
			}

		}
		else if($row2['ticket_type']=="svd"){
			if($row2['type']=="shortage"){
				$svd_shortage+=$row2['ticket_sum'];
			
			}
			else if($row2['type']=="overage"){
				$svd_overage+=$row2['ticket_sum'];
			
			}

		}
		
			
	}


	
	
	$sjt_discrep=$sjt_overage-$sjt_shortage;
	
	$sjd_discrep=$sjd_overage-$sjd_shortage;
	$svt_discrep=$svt_overage-$svt_shortage;
	$svd_discrep=$svd_overage-$svd_shortage;

	
	if($sjt_discrep<0){
		$sjt_label="(".($sjt_discrep*-1).")";
	}
	else {
		$sjt_label=$sjt_discrep;
	}
	if($sjd_discrep<0){
		$sjd_label="(".($sjd_discrep*-1).")";
	}
	else {
		$sjd_label=$sjd_discrep;
	}
	if($svt_discrep<0){
		$svt_label="(".($svt_discrep*-1).")";
	}
	else {
		$svt_label=$svt_discrep;
	}
	if($svd_discrep<0){
		$svd_label="(".($svd_discrep*-1).")";
	}
	else {
		$svd_label=$svd_discrep;
	}

	
	$sql2="select * from physically_defective where log_id='".$log_id."'";
	$rs2=$db->query($sql2);
	$nm2=$rs2->num_rows;	
	if($nm2>0){
		$row2=$rs2->fetch_assoc();
		$sjt_physically_defective+=$row2['sjt'];
		$sjd_physically_defective+=$row2['sjd'];
		$svt_physically_defective+=$row2['svt'];
		$svd_physically_defective+=$row2['svd'];
		

	}
	

	
}	
$sjt_grand_total=$sjt_subtotal-$sjt_physically_defective-$sjt_deduction+$sjt_discrep;
$sjd_grand_total=$sjd_subtotal-$sjd_physically_defective-$sjd_deduction+$sjd_discrep;
$svt_grand_total=$svt_subtotal-$svt_physically_defective-$svt_deduction+$svt_discrep;
$svd_grand_total=$svd_subtotal-$svd_physically_defective-$svd_deduction+$svd_discrep;
	
	if(isset($_GET['ext'])){
	}
	else {
	addContent(setRange("H33","H33"),$excel,$sjt_beginning_balance,"true",$ExWs);
	addContent(setRange("I33","I33"),$excel,$sjd_beginning_balance,"true",$ExWs);
	addContent(setRange("J33","J33"),$excel,$svt_beginning_balance,"true",$ExWs);
	addContent(setRange("K33","K33"),$excel,$svd_beginning_balance,"true",$ExWs);	
	
	addContent(setRange("H34","H34"),$excel,$sjt_initial_amount,"true",$ExWs);
	addContent(setRange("I34","I34"),$excel,$sjd_initial_amount,"true",$ExWs);
	addContent(setRange("J34","J34"),$excel,$svt_initial_amount,"true",$ExWs);
	addContent(setRange("K34","K34"),$excel,$svd_initial_amount,"true",$ExWs);

	addContent(setRange("H35","H35"),$excel,$sjt_additional_amount,"true",$ExWs);
	addContent(setRange("I35","I35"),$excel,$sjd_additional_amount,"true",$ExWs);
	addContent(setRange("J35","J35"),$excel,$svt_additional_amount,"true",$ExWs);
	addContent(setRange("K35","K35"),$excel,$svd_additional_amount,"true",$ExWs);	
	
	addContent(setRange("H37","H37"),$excel,$sjt_sold,"true",$ExWs);
	addContent(setRange("I37","I37"),$excel,$sjd_sold,"true",$ExWs);
	addContent(setRange("J37","J37"),$excel,$svt_sold,"true",$ExWs);
	addContent(setRange("K37","K37"),$excel,$svd_sold,"true",$ExWs);		
	
	addContent(setRange("H38","H38"),$excel,$sjt_physically_defective,"true",$ExWs);
	addContent(setRange("I38","I38"),$excel,$sjd_physically_defective,"true",$ExWs);
	addContent(setRange("J38","J38"),$excel,$svt_physically_defective,"true",$ExWs);
	addContent(setRange("K38","K38"),$excel,$svd_physically_defective,"true",$ExWs);		
	
	addContent(setRange("H39","H39"),$excel,$sjt_defective*1,"true",$ExWs);
	addContent(setRange("I39","I39"),$excel,$sjd_defective*1,"true",$ExWs);
	addContent(setRange("J39","J39"),$excel,$svt_defective*1,"true",$ExWs);
	addContent(setRange("K39","K39"),$excel,$svd_defective*1,"true",$ExWs);		

	addContent(setRange("H40","H40"),$excel,$sjt_discrep,"true",$ExWs);
	addContent(setRange("I40","I40"),$excel,$sjd_discrep,"true",$ExWs);
	addContent(setRange("J40","J40"),$excel,$svt_discrep,"true",$ExWs);
	addContent(setRange("K40","K40"),$excel,$svd_discrep,"true",$ExWs);		
	}
	
	$sql="select * from logbook where date='".$dsrDate."' and station='".$station."' order by field(revenue,'open','close'),field(shift,3,1,2)";

//$sql="select * from logbook where date='".$dsrDate."' and station='".$station."' order by shift";

$rs=$db->query($sql);
$nm=$rs->num_rows;

$sjt_sales=0;
$sjd_sales=0;
$svt_sales=0;
$svd_sales=0;

$fare_adjustment=0;
$ot_amount=0;
$unreg_sale=0;

$discount=0;
$refund=0;

$grandTotal=0;
$deductionsTotal=0;
$netSales=0;

/*
$sqlAlt="select * from logbook where date='".$previousDate."' and station='".$station."' and shift=3 and revenue='open'";
$rsAlt=$db->query($sqlAlt);
$nmAlt=$rsAlt->num_rows;
if($nmAlt>0){
	$nm++;
}
*/

for($i=0;$i<$nm;$i++){


	$row=$rs->fetch_assoc();
	$log_id=$row['id'];

	$sql2="select * from control_sales_amount inner join control_remittance on control_sales_amount.control_id=control_remittance.control_id where remit_log='".$log_id."'";
	$rs2=$db->query($sql2);
	$nm2=$rs2->num_rows;	
	for($k=0;$k<$nm2;$k++){
		$row2=$rs2->fetch_assoc();
		$sjt_sales+=$row2['sjt']*1;		
		$svt_sales+=$row2['svt']*1;
		$sjd_sales+=$row2['sjd']*1;
		$svd_sales+=$row2['svd']*1;
			
	}

	$sql2="select sum(sjt+sjd+svt+svd+c+ot) as fare_adjustment from fare_adjustment inner join control_remittance on fare_adjustment.control_id=control_remittance.control_id where remit_log='".$log_id."'";

	$rs2=$db->query($sql2);
	$nm2=$rs2->num_rows;	
	for($k=0;$k<$nm2;$k++){
		$row2=$rs2->fetch_assoc();
		$fare_adjustment+=$row2['fare_adjustment'];
	}	
	
	$sql2="select sum(sj+sv) as unreg_sale from unreg_sale inner join control_remittance on unreg_sale.control_id=control_remittance.control_id where remit_log='".$log_id."'";
	$rs2=$db->query($sql2);
	$nm2=$rs2->num_rows;	
	for($k=0;$k<$nm2;$k++){
		$row2=$rs2->fetch_assoc();
		$unreg_sale+=$row2['unreg_sale'];
	}		


	$sql2="select sum(ot) as ot from control_cash inner join control_remittance on control_cash.control_id=control_remittance.control_id where remit_log='".$log_id."' group by type";
	$rs2=$db->query($sql2);
	$nm2=$rs2->num_rows;	
	for($k=0;$k<$nm2;$k++){
		$row2=$rs2->fetch_assoc();
		$ot_amount+=$row2['ot'];
	}	
	
	$sql2="select sum(sj+sv) as discount from discount inner join control_remittance on discount.control_id=control_remittance.control_id where remit_log='".$log_id."'";
	$rs2=$db->query($sql2);
	$nm2=$rs2->num_rows;	
	for($k=0;$k<$nm2;$k++){
		$row2=$rs2->fetch_assoc();
		$discount+=$row2['discount'];
	}		
	
	$sql2="select sum(sj_amount+sv_amount) as refund from refund inner join control_remittance on refund.control_id=control_remittance.control_id where remit_log='".$log_id."'";
	$rs2=$db->query($sql2);
	$nm2=$rs2->num_rows;	
	for($k=0;$k<$nm2;$k++){
		$row2=$rs2->fetch_assoc();
		$refund+=$row2['refund'];
	}	
	
}

$grandTotal+=$sjt_sales;
$grandTotal+=$sjd_sales;
$grandTotal+=$svt_sales;
$grandTotal+=$svd_sales;

$grandTotal+=$fare_adjustment;
$grandTotal+=$ot_amount;
$grandTotal+=$unreg_sale;

$unreg_deduction=$unreg_sale;

$deductionsTotal+=$discount;
$deductionsTotal+=$refund;

$netSales=$grandTotal-$deductionsTotal;

$sql="select * from logbook where date='".$dsrDate."' and station='".$station."' order by field(revenue,'open','close'),field(shift,3,1,2)";
//$sql="select * from logbook where date='".$dsrDate."' and station='".$station."' order by shift";

$rs=$db->query($sql);
$nm=$rs->num_rows;
/*
$sqlAlt="select * from logbook where date='".$previousDate."' and station='".$station."' and shift=3 and revenue='open'";
$rsAlt=$db->query($sqlAlt);
$nmAlt=$rsAlt->num_rows;

if($nmAlt>0){
	$nm++;

}
*/

$cash_beginning=0;
$revolving_fund=0;
$for_deposit=0;
$subtotal=0;
$pnb_deposit_c=0;
$pnb_deposit_p=0;
$subtotal_2=0;
$overage=0;
$unpaid_shortage=0;
$cash_ending=0;

for($i=0;$i<$nm;$i++){
	if($i==0){

		$row=$rs->fetch_assoc();
		$log_id=$row['id'];
		
		$sql2="select * from beginning_balance_cash where log_id='".$log_id."'";
		//echo $sql2;
		$rs2=$db->query($sql2);
		$row2=$rs2->fetch_assoc();
	//	$cash_beginning=$row2['revolving_fund']+$row2['for_deposit'];
		$cash_beginning=$row2['for_deposit'];
		$revolving_fund=$row2['revolving_fund'];		
	}
	else {
		$row=$rs->fetch_assoc();
		$log_id=$row['id'];

	}
	/*
	$sql2="select sum(control_remittance) as net_sales from cash_remittance where log_id='".$log_id."'";
	$rs2=$db->query($sql2);
	$nm2=$rs2->num_rows;			
	if($nm2>0){
		$row2=$rs2->fetch_assoc();
		$for_deposit+=$row2['net_sales'];
	}
*/
	
	$for_deposit=$netSales;
	
	//	$sql2="select sum(total) as revolving_fund from cash_transfer where log_id='".$log_id."' and type='remittance'";
	
	$sql2="select * from beginning_balance_cash where log_id='".$log_id."'";
	//echo $sql2;
	$rs2=$db->query($sql2);
	$nm2=$rs2->num_rows;			
	if($nm2>0){
		$row2=$rs2->fetch_assoc();
		//$revolving_fund+=$row2['revolving_fund'];
//		$for_deposit+=$row2['for_deposit'];
		
		
	}

	
	$sql2="select sum(amount) as deposit from pnb_deposit where log_id='".$log_id."' and type='current'";
	$rs2=$db->query($sql2);
	$nm2=$rs2->num_rows;			
	if($nm2>0){
		$row2=$rs2->fetch_assoc();
		$pnb_deposit_c+=$row2['deposit'];
	}	
	
	$sql2="select sum(amount) as deposit from pnb_deposit where log_id='".$log_id."' and type='previous'";
	$rs2=$db->query($sql2);
	$nm2=$rs2->num_rows;			
	if($nm2>0){
		$row2=$rs2->fetch_assoc();
		$pnb_deposit_p+=$row2['deposit'];
	}	
	
	$sql2="select sum(if(type='overage',amount,0)) as overage,sum(if(type='shortage',amount,0)) as shortage	from discrepancy where log_id='".$log_id."'";
//	$sql2="select sum(unpaid_shortage) as unpaid_shortage, sum(overage) as overage from control_cash where control_id in (SELECT control_id FROM remittance where log_id='".$log_id."')";
	$rs2=$db->query($sql2);
	$nm2=$rs2->num_rows;	
	
	for($k=0;$k<$nm2;$k++){
		$row2=$rs2->fetch_assoc();
		$overage+=$row2['overage'];
		
		$unpaid_shortage+=$row2['shortage'];
		
	}
	
	
	$discrepancySQL="SELECT * FROM transaction inner join cash_transfer on transaction.transaction_id=cash_transfer.transaction_id where transaction_type='shortage' and transaction.log_id='".$log_id."'";
	$discrepancyRS=$db->query($discrepancySQL);

	$discrepancyNM=$discrepancyRS->num_rows;
	
	$paid_shortage=0;
	
	if($discrepancyNM>0){
		for($aa=0;$aa<$discrepancyNM;$aa++){
		$discrepancyRow=$discrepancyRS->fetch_assoc();
		$paid_shortage+=$discrepancyRow['net_revenue']+$discrepancyRow['total'];
		//$unpaid_shortage-=$paid_shortage;
		}
	
	}	
	$unpaid_shortage-=$paid_shortage;	
}	
	$subtotal=$for_deposit+$revolving_fund+$cash_beginning;	
	$deposit_total=$pnb_deposit_c+$pnb_deposit_p;
	$subtotal_2=$subtotal-$deposit_total;
	//$overage-=$unreg_deduction;
	$overage=$overage;
	$cash_ending=$subtotal_2+$overage-$unpaid_shortage;	
	if(isset($_GET['ext'])){
	}
	else {
		addContent(setRange("V32","Y32"),$excel,$cash_beginning,"true",$ExWs);
		addContent(setRange("V33","Y33"),$excel,$revolving_fund,"true",$ExWs);
		addContent(setRange("V34","Y34"),$excel,$for_deposit,"true",$ExWs);

		addContent(setRange("V36","Y36"),$excel,$pnb_deposit_c,"true",$ExWs);
		addContent(setRange("V37","Y37"),$excel,$pnb_deposit_p,"true",$ExWs);

		addContent(setRange("V39","Y39"),$excel,$overage,"true",$ExWs);
		addContent(setRange("V40","Y40"),$excel,$unpaid_shortage,"true",$ExWs);
	
	
	}
	
	addContent(setRange("B45","C45"),$excel,$s1_ca,"true",$ExWs);
	addContent(setRange("M45","O45"),$excel,$s2_ca,"true",$ExWs);
	addContent(setRange("X45","AD45"),$excel,$s3_ca,"true",$ExWs);
	
	save($ExWb,$excel,$newFilename);

	$newFilename2=str_replace('xls','html',$newFilename);
	
	$excel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_LEGAL);
	$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

	saveHTML($ExWb,$excel,$newFilename2);
		
	echo "DSR printout has been generated!  Press right click and Save As: <a href='".$newFilename."'>Here</a>";
	echo "<br>";	
	echo "DSR (HTML) printout has been generated!  Press right click and Save As: <a href='".$newFilename2."'>Here</a>";
		
?>