<?php
session_start();
?>
<?php
$dsrDate=$_SESSION['log_date'];
$station=$_SESSION['station'];
?>
<?php
$db=new mysqli("localhost","root","","finance");
?>
<meta http-equiv="refresh" content="60;url=dsr_tickets.php" />
<link href="layout/dsr.css" rel="stylesheet" type="text/css" />

<?php 
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
<h3><?php echo strtoupper($logStation); 
if(isset($_GET['ext'])){
	echo " - Extension";
}

?></h3>
<br>
<div class='menuHeader'>
<a href='dsr_cash.php<?php echo $clause; ?>'>Part 1</a> | Part 2 | <a href='dsr_summary_1.php<?php echo $clause; ?>'>Summary</a>
<?php 
if($extAvNM>0){ 
	if(isset($_GET['ext'])){ 
		echo "| <a href='dsr_tickets.php'>Satellite</a> "; 
	}
	else {
		echo "| <a href='dsr_tickets.php?ext=Y'>Extension</a> "; 
	
	}
} ?>
| <a href='#' onclick='window.open("generate_dsr.php<?php echo $clause; ?>","_blank")'>Printout</a>
</div>
<table class='dsrTable' border=1 width=100%>
<tr class='header'>
<th rowspan=2>Name of CA</th>
<th rowspan=2>Name of Ticket Seller</th>
<th rowspan=2>Id No.</th>

<th colspan=2>Unreg Sale</th>
<th colspan=2>Discount</th>
<th colspan=4>Refund</th>

<th rowspan=2>Overage</th>
<th colspan=2>Shortage</th>
<th colspan=4>Remitted Ticket (Loose)</th>

<th colspan=4>Over (Lacking)</th>
<th colspan=4>Defective Ticket</th>

</tr>
<tr class='subheader'>
<th>SJ</th>
<th>SV</th>

<th>SJ</th>
<th>SV</th>

<th>SJ</th>
<th>Amt.</th>
<th>SV</th>
<th>Amt.</th>

<th>PD</th>
<th>UPD</th>

<?php
for($a=0;$a<3;$a++){
?>
	<th>SJ</th>
	<th>DSJ</th>
	<th>DSV</th>
	<th>SV</th>

<?php
}
?>
</tr>

<?php
$previousDate=date("Y-m-d",strtotime($dsrDate."-1 day"));

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
for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
	$log_id=$row['id'];
	
	$sql2_backup="select * from control_remittance where remit_log='".$log_id."' and station='".$stationStamp."' group by remit_ticket_seller";
	$sql2="select * from control_remittance where remit_log='".$log_id."' and station='".$stationStamp."' group by remit_ticket_seller,unit";
	$rs2=$db->query($sql2);
	$nm2=$rs2->num_rows;
	
	$cash_assistSQL="select * from login where username='".$row['cash_assistant']."'";

	$cash_assistRS=$db->query($cash_assistSQL);
	$cash_assistRow=$cash_assistRS->fetch_assoc();
	
	$cash_assistant=$cash_assistRow['lastName'].", ".$cash_assistRow['firstName'];
	
	$subtotal['sj_unreg']=0; 
	$subtotal['sv_unreg']=0; 
	$subtotal['sj_discount']=0; 
	$subtotal['sv_discount']=0; 
	$subtotal['sj_refund']=0; 
	$subtotal['sv_refund']=0; 

	$subtotal['sj_r_amount']=0; 
	$subtotal['sv_r_amount']=0; 

	$subtotal['overage']=0; 

	$subtotal['paid_shortage']=0; 
	$subtotal['unpaid_shortage']=0; 

	$subtotal['sjtLoose']=0; 
	$subtotal['sjdLoose']=0; 
	$subtotal['svdLoose']=0; 
	$subtotal['svtLoose']=0; 
			
	$subtotal['sjt_label']=0; 
	$subtotal['sjd_label']=0; 
	$subtotal['svt_label']=0; 
	$subtotal['svd_label']=0; 
	
	$subtotal['sjt_discrepancy']=0; 
	$subtotal['sjd_discrepancy']=0; 
	$subtotal['svt_discrepancy']=0; 
	$subtotal['svd_discrepancy']=0; 
	
	

	$subtotal['sjtDefective']=0; 
	$subtotal['sjdDefective']=0; 
	$subtotal['svdDefective']=0; 
	$subtotal['svtDefective']=0; 
	
	
	
	
	
	
	
if($nm2>0){	
?>
<tr class='grid'>
<td rowspan='<?php echo $nm2*1; ?>'><?php echo $cash_assistant; ?></td>
<?php
for($k=0;$k<$nm2;$k++){
	$row2=$rs2->fetch_assoc();
	
	$unit=$row2['unit'];
	
	$ticketsellerSQL="select * from ticket_seller where id='".$row2['remit_ticket_seller']."'";	
	$ticketsellerRS=$db->query($ticketsellerSQL);
	$ticketsellerRow=$ticketsellerRS->fetch_assoc();
	$ticket_seller=$ticketsellerRow['last_name'].", ".$ticketsellerRow['first_name'];
	$ticket_id=$ticketsellerRow['id'];
	
	$discountSQL="select * from discount inner join control_remittance on discount.control_id=control_remittance.control_id where remit_log='".$log_id."' and station='".$stationStamp."' and remit_ticket_seller='".$row2['remit_ticket_seller']."' and unit='".$unit."'";
	$discountRS=$db->query($discountSQL);
	
	$discountNM=$discountRS->num_rows;
	
	$sv_discount=0;
	$sj_discount=0;
	
	for($m=0;$m<$discountNM;$m++){
		$discountRow=$discountRS->fetch_assoc();
		$sj_discount+=$discountRow['sj']*1;
		$sv_discount+=$discountRow['sv']*1;
	
	}

	$unregSQL="select * from unreg_sale inner join control_remittance on unreg_sale.control_id=control_remittance.control_id where remit_log='".$log_id."' and station='".$stationStamp."' and remit_ticket_seller='".$row2['remit_ticket_seller']."' and unit='".$unit."'";
	$unregRS=$db->query($unregSQL);
	
	$unregNM=$unregRS->num_rows;
	
	$sj_unreg=0;
	$sv_unreg=0;
	
	for($m=0;$m<$unregNM;$m++){
		$unregRow=$unregRS->fetch_assoc();
		$sj_unreg+=$unregRow['sj']*1;
		$sv_unreg+=$unregRow['sv']*1;
	
	}

	$unsoldSQL="select type,sum(loose_good) as ticket_sum,sum(loose_defective) as ticket_sum2 from control_unsold inner join control_remittance on control_unsold.control_id=control_remittance.control_id where remit_log='".$log_id."' and station='".$stationStamp."' and remit_ticket_seller='".$row2['remit_ticket_seller']."' and unit='".$unit."' group by type";

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
	
	$sjt_physically_defective=0;
	$sjd_physically_defective=0;
	$svt_physically_defective=0;
	$svd_physically_defective=0;
	
	
	$asql2="select * from physically_defective where log_id='".$log_id."' and station='".$stationStamp."'";

	$ars2=$db->query($asql2);
	$anm2=$ars2->num_rows;	
	if($anm2>0){
		$arow2=$ars2->fetch_assoc();
		$sjt_physically_defective=$arow2['sjt'];
		$sjd_physically_defective=$arow2['sjd'];
		$svt_physically_defective=$arow2['svt'];
		$svd_physically_defective=$arow2['svd'];
		

	}
	
	
	
	
/*
	$unsoldSQL="select sum(sjd) as sjd,sum(sjt) as sjt,sum(svd) as svd,sum(svt) as svt from physically_defective where log_id='".$log_id."' and ticket_seller='".$row2['remit_ticket_seller']."'";

	$unsoldRS=$db->query($unsoldSQL);
	$unsoldNM=$unsoldRS->num_rows;
	
	$sjtLoose=0;
	$sjdLoose=0;
	$svtLoose=0;
	$svdLoose=0;
	if($unsoldNM>0){
		$unsoldRow=$unsoldRS->fetch_assoc();
		
		$svtLoose=$unsoldRow['svt'];		
		$svdLoose=$unsoldRow['svd'];		
		$sjtLoose=$unsoldRow['sjt'];		
		$sjdLoose=$unsoldRow['sjd'];		
	}
*/
	$refundSQL="select * from refund inner join control_remittance on refund.control_id=control_remittance.control_id where remit_log='".$log_id."' and station='".$stationStamp."' and remit_ticket_seller='".$row2['remit_ticket_seller']."' and unit='".$unit."'";
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

	//	$cashSQL="select * from control_cash where control_id in (SELECT control_id FROM remittance where log_id='".$log_id."' and ticket_seller='".$row2['remit_ticket_seller']."')";
	$cashSQL="select sum(if(discrepancy.type='overage',amount,0)) as overage,sum(if(discrepancy.type='shortage',amount,0)) as unpaid_shortage from discrepancy inner join cash_transfer on discrepancy.transaction_id=cash_transfer.transaction_id where discrepancy.log_id='".$log_id."' and discrepancy.ticket_seller='".$row2['remit_ticket_seller']."' and cash_transfer.station='".$stationStamp."' and cash_transfer.unit='".$unit."'";
	//	$cashSQL="select * from control_cash where control_id in (SELECT control_id FROM remittance where log_id='".$log_id."' and ticket_seller='".$row2['remit_ticket_seller']."')";
	$cashRS=$db->query($cashSQL);
	$cashNM=$cashRS->num_rows;
	
	$overage=0;
	$unpaid_shortage=0;
	
	for($n=0;$n<$cashNM;$n++){
		$cashRow=$cashRS->fetch_assoc();
		$overage+=$cashRow['overage']*1;
		$unpaid_shortage+=$cashRow['unpaid_shortage']*1;
	
	}	

	$db=new mysqli("localhost","root","","finance");	
	//$discrepancySQL="SELECT * FROM transaction inner join cash_transfer on transaction.transaction_id=cash_transfer.transaction_id where transaction_type='shortage' and log_id='".$log_id."' and ticket_seller='".$row2['remit_ticket_seller']."'";
	
	$discrepancySQL="SELECT * FROM transaction inner join cash_transfer on transaction.transaction_id=cash_transfer.transaction_id where transaction_type='shortage' and transaction.log_id='".$log_id."' and cash_transfer.station='".$stationStamp."' and cash_transfer.ticket_seller='".$row2['remit_ticket_seller']."' and cash_transfer.unit='".$unit."'";


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
	

	$discrepancyTicketSQL="select *,sum(amount) as new_amount from discrepancy_ticket where transaction_id in (select control_slip.id from control_slip inner join remittance on control_slip.id=remittance.control_id where remittance.log_id='".$log_id."' and control_slip.ticket_seller='".$row2['remit_ticket_seller']."' and control_slip.station='".$stationStamp."' and control_slip.unit='".$unit."') group by ticket_type";
	//	echo $control;	

//	$discrepancyTicketSQL="select *,sum(amount) as new_amount from discrepancy_ticket inner join control_slip on discrepancy_ticket.transaction_id=concat('control_',control_slip.id) where discrepancy_ticket.log_id='".$log_id."' and discrepancy_ticket.ticket_seller='".$row2['remit_ticket_seller']."' and control_slip.station='".$stationStamp."' group by ticket_type";
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
					$sjt_label="(".$sjt_discrepancy.")";
					$subtotal['sjt_discrepancy']-=$sjt_discrepancy;
				}
				else if($discrepRow['type']=="overage"){
					$sjt_label=$sjt_discrepancy;
					$subtotal['sjt_discrepancy']+=$sjt_discrepancy;

				}
			
			
			}
			else if($discrepRow['ticket_type']=="sjd"){
				$sjd_discrepancy=$discrepRow['new_amount'];
			
				if($discrepRow['type']=="shortage"){
					$sjd_label="(".$sjd_discrepancy.")";
					$subtotal['sjd_discrepancy']-=$sjd_discrepancy;
				
				}
				else if($discrepRow['type']=="overage"){
					$sjd_label=$sjd_discrepancy;
					$subtotal['sjd_discrepancy']+=$sjd_discrepancy;
					
				}
			
			
			}
			else if($discrepRow['ticket_type']=="svt"){
				$svt_discrepancy=$discrepRow['new_amount'];
			
				if($discrepRow['type']=="shortage"){
					$svt_label="(".$svt_discrepancy.")";
					$subtotal['svt_discrepancy']-=$svt_discrepancy;
				
				}
				else if($discrepRow['type']=="overage"){
					$svt_label=$svt_discrepancy;
					$subtotal['svt_discrepancy']+=$svt_discrepancy;

				}
			
			
			}
			else if($discrepRow['ticket_type']=="svd"){
				$svd_discrepancy=$discrepRow['new_amount'];
			
				if($discrepRow['type']=="shortage"){
					$svd_label="(".$svd_discrepancy.")";
					$subtotal['svd_discrepancy']-=$svd_discrepancy;
				
				}
				else if($discrepRow['type']=="overage"){
					$svd_label=$svd_discrepancy;
					$subtotal['svd_discrepancy']+=$svd_discrepancy;

				}
			
			
			}
			
			
		
		}
	//	$discrepancyRow=$discrepancyRS->fetch_assoc();
	//	$paid_shortage+=$discrepancyRow['shortage'];
	
	}
//	$overage-=$sj_unreg+$sv_unreg;
	$overage=$overage;
	if($subtotal['sjt_discrepancy']>=0){
		$subtotal['sjt_label']=$subtotal['sjt_discrepancy']; 
	}
	else {
		$subtotal['sjt_label']="(".($subtotal['sjt_discrepancy']*-1).")";
	}
	
	if($subtotal['sjd_discrepancy']>=0){
		$subtotal['sjd_label']=$subtotal['sjd_discrepancy']; 
	}
	else {
		$subtotal['sjd_label']="(".($subtotal['sjd_discrepancy']*-1).")";
	}
	
	if($subtotal['svt_discrepancy']>=0){
		$subtotal['svt_label']=$subtotal['svt_discrepancy']; 
	}
	else {
		$subtotal['svt_label']="(".($subtotal['svt_discrepancy']*-1).")";
	}
	
	if($subtotal['svd_discrepancy']>=0){
		$subtotal['svd_label']=$subtotal['svd_discrepancy']; 
	}
	else {
		$subtotal['svd_label']="(".($subtotal['svd_discrepancy']*-1).")";
	}
	
	
	$subtotal['sj_unreg']+=$sj_unreg; 
	$subtotal['sv_unreg']+=$sv_unreg; 
	$subtotal['sj_discount']+=$sj_discount; 
	$subtotal['sv_discount']+=$sv_discount; 
	$subtotal['sj_refund']+=$sj_refund; 
	$subtotal['sv_refund']+=$sv_refund; 

	$subtotal['sj_r_amount']+=$sj_r_amount; 
	$subtotal['sv_r_amount']+=$sv_r_amount; 

	$subtotal['overage']+=$overage; 

	$subtotal['paid_shortage']+=$paid_shortage; 
	$subtotal['unpaid_shortage']+=$unpaid_shortage; 

	$subtotal['sjtLoose']+=$sjtLoose; 
	$subtotal['sjdLoose']+=$sjdLoose; 
	$subtotal['svdLoose']+=$svdLoose; 
	$subtotal['svtLoose']+=$svtLoose; 
			

	$subtotal['sjtDefective']+=$sjtDefective; 
	$subtotal['sjdDefective']+=$sjdDefective; 
	$subtotal['svdDefective']+=$svdDefective; 
	$subtotal['svtDefective']+=$svtDefective; 	
	


	
	
	if($k==0){
?>	
		<td><?php echo $ticket_seller; if($unit=="A/D"){ } else { echo " - ".$unit; }  ?></td>
		<td><?php echo $ticket_id; ?></td>		
		<td align=right><?php echo number_format($sj_unreg*1,2); ?></td>
		<td align=right><?php echo number_format($sv_unreg*1,2); ?></td>
		<td align=right><?php echo number_format($sj_discount*1,2); ?></td>
		<td align=right><?php echo number_format($sv_discount*1,2); ?></td>
		<td align=right><?php echo $sj_refund; ?></td>


		<td align=right><?php echo number_format($sj_r_amount*1,2); ?></td>
		<td align=right><?php echo $sv_refund; ?></td>
		<td align=right><?php echo number_format($sv_r_amount*1,2); ?></td>

		<td align=right><?php echo number_format($overage*1,2); ?></td>

		<td align=right><?php echo number_format($paid_shortage*1,2); ?></td>	
		<td align=right><?php echo number_format($unpaid_shortage*1,2); ?></td>

		<td align=right><?php echo $sjtLoose; ?></td>
		<td align=right><?php echo $sjdLoose; ?></td>
		<td align=right><?php echo $svdLoose; ?></td>
		<td align=right><?php echo $svtLoose; ?></td>		
			
		<td align=right><?php echo $sjt_label; ?></td>
		<td align=right><?php echo $sjd_label; ?></td>
		<td align=right><?php echo $svd_label; ?></td>
		<td align=right><?php echo $svt_label; ?></td>

		<td align=right><?php echo $sjtDefective; ?></td>
		<td align=right><?php echo $sjdDefective; ?></td>
		<td align=right><?php echo $svdDefective; ?></td>
		<td align=right><?php echo $svtDefective; ?></td>		
		
	</tr>
<?php	
	}
	else {
?>	
	<tr class='grid'>
		<td><?php echo $ticket_seller; if($unit=="A/D"){ } else { echo " - ".$unit; }  ?></td>
		<td><?php echo $ticket_id; ?></td>		
		<td align=right><?php echo number_format($sj_unreg*1,2); ?></td>
		<td align=right><?php echo number_format($sv_unreg*1,2); ?></td>
		<td align=right><?php echo number_format($sj_discount*1,2); ?></td>
		<td align=right><?php echo number_format($sv_discount*1,2); ?></td>
		<td align=right><?php echo $sj_refund; ?></td>
		<td align=right><?php echo number_format($sj_r_amount*1,2); ?></td>
		
		<td align=right><?php echo $sv_refund; ?></td>


		<td align=right><?php echo number_format($sv_r_amount*1,2); ?></td>
		<td align=right><?php echo number_format($overage*1,2); ?></td>

		<td align=right><?php echo number_format($paid_shortage*1,2); ?></td>	
		<td align=right><?php echo number_format($unpaid_shortage*1,2); ?></td>

		<td align=right><?php echo $sjtLoose; ?></td>
		<td align=right><?php echo $sjdLoose; ?></td>
		<td align=right><?php echo $svdLoose; ?></td>
		<td align=right><?php echo $svtLoose; ?></td>		

		<td align=right><?php echo $sjt_label; ?></td>
		<td align=right><?php echo $sjd_label; ?></td>
		<td align=right><?php echo $svd_label; ?></td>
		<td align=right><?php echo $svt_label; ?></td>

		<td align=right><?php echo $sjtDefective; ?></td>
		<td align=right><?php echo $sjdDefective; ?></td>
		<td align=right><?php echo $svdDefective; ?></td>
		<td align=right><?php echo $svtDefective; ?></td>		
		
	</tr>
		
	
<?php	
	}
	
	}
?>

<?php	

	$subtotal['sjtDefective']+=$sjt_physically_defective; 
	$subtotal['sjdDefective']+=$sjd_physically_defective; 
	$subtotal['svdDefective']+=$svd_physically_defective; 
	$subtotal['svtDefective']+=$svt_physically_defective; 	

	
}
if($nm2>0){
	$grandtotal['sj_unreg']+=$subtotal['sj_unreg']; 
	$grandtotal['sv_unreg']+=$subtotal['sv_unreg']; 
	$grandtotal['sj_discount']+=$subtotal['sj_discount']; 
	$grandtotal['sv_discount']+=$subtotal['sv_discount']; 
	$grandtotal['sj_refund']+=$subtotal['sj_refund']; 
	$grandtotal['sv_refund']+=$subtotal['sv_refund']; 

	$grandtotal['sj_r_amount']+=$subtotal['sj_r_amount']; 
	$grandtotal['sv_r_amount']+=$subtotal['sv_r_amount']; 

	$grandtotal['overage']+=$subtotal['overage']; 

	$grandtotal['paid_shortage']+=$subtotal['paid_shortage']; 
	$grandtotal['unpaid_shortage']+=$subtotal['unpaid_shortage']; 

	$grandtotal['sjtLoose']+=$subtotal['sjtLoose']; 
	$grandtotal['sjdLoose']+=$subtotal['sjdLoose']; 
	$grandtotal['svdLoose']+=$subtotal['svdLoose']; 
	$grandtotal['svtLoose']+=$subtotal['svtLoose']; 
	
	$grandtotal['sjt_discrepancy']+=$subtotal['sjt_discrepancy'];
	$grandtotal['sjd_discrepancy']+=$subtotal['sjd_discrepancy'];
	$grandtotal['svt_discrepancy']+=$subtotal['svt_discrepancy'];
	$grandtotal['svd_discrepancy']+=$subtotal['svd_discrepancy'];
	
/*	$grandtotal['sjt_label']+=$subtotal['sjt_label']; 
	$grandtotal['sjd_label']+=$subtotal['sjd_label']; 
	$grandtotal['svt_label']+=$subtotal['svt_label']; 
	$grandtotal['svd_label']+=$subtotal['svd_label']; 
*/
	$grandtotal['sjtDefective']+=$subtotal['sjtDefective']; 
	$grandtotal['sjdDefective']+=$subtotal['sjdDefective']; 
	$grandtotal['svdDefective']+=$subtotal['svdDefective']; 
	$grandtotal['svtDefective']+=$subtotal['svtDefective']; 

?>
<tr class='subheader'>
<th  colspan=3>Subtotal</th>
	<td align=right><font><?php echo number_format($subtotal['sj_unreg']*1,2); ?></font></td> 
	<td align=right><font><?php echo number_format($subtotal['sv_unreg']*1,2); ?></font></td> 
	<td align=right><font><?php echo number_format($subtotal['sj_discount']*1,2); ?></font></td> 
	<td align=right><font><?php echo number_format($subtotal['sv_discount']*1,2); ?></font></td> 
	<td align=right><font><?php echo $subtotal['sj_refund']; ?></font></td> 

	<td align=right><font><?php echo number_format($subtotal['sj_r_amount']*1,2); ?></font></td> 

	<td align=right><font><?php echo $subtotal['sv_refund']; ?></font></td> 

	<td align=right><font><?php echo number_format($subtotal['sv_r_amount']*1,2); ?></font></td> 

	<td align=right><font><?php echo number_format($subtotal['overage']*1,2); ?></font></td> 

	<td align=right><font><?php echo number_format($subtotal['paid_shortage']*1,2); ?></font></td> 
	<td align=right><font><?php echo number_format($subtotal['unpaid_shortage']*1,2); ?></font></td> 

	<td align=right><font><?php echo $subtotal['sjtLoose']; ?></font></td> 
	<td align=right><font><?php echo $subtotal['sjdLoose']; ?></font></td> 
	<td align=right><font><?php echo $subtotal['svdLoose']; ?></font></td> 
	<td align=right><font><?php echo $subtotal['svtLoose']; ?></font></td> 
			
	<td align=right><font><?php echo $subtotal['sjt_label']; ?></font></td> 
	<td align=right><font><?php echo $subtotal['sjd_label']; ?></font></td> 
	<td align=right><font><?php echo $subtotal['svd_label']; ?></font></td> 
	<td align=right><font><?php echo $subtotal['svt_label']; ?></font></td> 

	<td align=right><font><?php echo $subtotal['sjtDefective']; ?></font></td> 
	<td align=right><font><?php echo $subtotal['sjdDefective']; ?></font></td> 
	<td align=right><font><?php echo $subtotal['svdDefective']; ?></font></td> 
	<td align=right><font><?php echo $subtotal['svtDefective']; ?></font></td> 
	</tr>
<?php
}
}


	if($grandtotal['sjt_discrepancy']>=0){
		$grandtotal['sjt_label']=$grandtotal['sjt_discrepancy']; 
	}
	else {
		$grandtotal['sjt_label']="(".($grandtotal['sjt_discrepancy']*-1).")";
	}
	
	if($grandtotal['sjd_discrepancy']>=0){
		$grandtotal['sjd_label']=$grandtotal['sjd_discrepancy']; 
	}
	else {
		$grandtotal['sjd_label']="(".($grandtotal['sjd_discrepancy']*-1).")";
	}
	
	if($grandtotal['svt_discrepancy']>=0){
		$grandtotal['svt_label']=$grandtotal['svt_discrepancy']; 
	}
	else {
		$grandtotal['svt_label']="(".($grandtotal['svt_discrepancy']*-1).")";
	}
	
	if($grandtotal['svd_discrepancy']>=0){
		$grandtotal['svd_label']=$grandtotal['svd_discrepancy']; 
	}
	else {
		$grandtotal['svd_label']="(".($grandtotal['svd_discrepancy']*-1).")";
	}
	


?>
<tr class='header'>
<th colspan=3>Grand Total</th>
	<td align=right><font><?php echo number_format($grandtotal['sj_unreg']*1,2); ?></font></td> 
	<td align=right><font><?php echo number_format($grandtotal['sv_unreg']*1,2); ?></font></td> 
	<td align=right><font><?php echo number_format($grandtotal['sj_discount']*1,2); ?></font></td> 
	<td align=right><font><?php echo number_format($grandtotal['sv_discount']*1,2); ?></font></td> 
	<td align=right><font><?php echo $grandtotal['sj_refund']; ?></font></td> 
	<td align=right><font><?php echo number_format($grandtotal['sj_r_amount']*1,2); ?></font></td> 

	<td align=right><font><?php echo $grandtotal['sv_refund']; ?></font></td> 

	<td align=right><font><?php echo number_format($grandtotal['sv_r_amount']*1,2); ?></font></td> 

	<td align=right><font><?php echo number_format($grandtotal['overage']*1,2); ?></font></td> 

	<td align=right><font><?php echo number_format($grandtotal['paid_shortage']*1,2); ?></font></td> 
	<td align=right><font><?php echo number_format($grandtotal['unpaid_shortage']*1,2); ?></font></td> 

	<td align=right><font><?php echo $grandtotal['sjtLoose']; ?></font></td> 
	<td align=right><font><?php echo $grandtotal['sjdLoose']; ?></font></td> 
	<td align=right><font><?php echo $grandtotal['svdLoose']; ?></font></td> 
	<td align=right><font><?php echo $grandtotal['svtLoose']; ?></font></td> 
			
	<td align=right><font><?php echo $grandtotal['sjt_label']; ?></font></td> 
	<td align=right><font><?php echo $grandtotal['sjd_label']; ?></font></td> 
	<td align=right><font><?php echo $grandtotal['svd_label']; ?></font></td> 
	<td align=right><font><?php echo $grandtotal['svt_label']; ?></font></td> 

	<td align=right><font><?php echo $grandtotal['sjtDefective']; ?></font></td> 
	<td align=right><font><?php echo $grandtotal['sjdDefective']; ?></font></td> 
	<td align=right><font><?php echo $grandtotal['svdDefective']; ?></font></td> 
	<td align=right><font><?php echo $grandtotal['svtDefective']; ?></font></td> 
	</tr>
</table>