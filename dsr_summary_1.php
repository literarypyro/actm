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
<?php 
    $start = microtime(true);
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
$previousDate=date("Y-m-d",strtotime($dsrDate."-1 day"));

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

//	$sql2="select sum(sjt+sjd+svt+svd+c+ot) as fare_adjustment from fare_adjustment inner join control_remittance on fare_adjustment.control_id=control_remittance.control_id where remit_log='".$log_id."' and station='".$stationStamp."'";
	$sql2="select (sjt+sjd+svt+svd+c+ot) as fare_adjustment from fare_adjustment inner join control_remittance on fare_adjustment.control_id=control_remittance.control_id where remit_log='".$log_id."' and station='".$stationStamp."'";

	$rs2=$db->query($sql2);
	$nm2=$rs2->num_rows;	
	for($k=0;$k<$nm2;$k++){
		$row2=$rs2->fetch_assoc();
		$fare_adjustment+=$row2['fare_adjustment'];
	}	
	
//	$sql2="select sum(sj+sv) as unreg_sale from unreg_sale inner join control_remittance on unreg_sale.control_id=control_remittance.control_id where remit_log='".$log_id."' and station='".$stationStamp."'";
	$sql2="select (sj+sv) as unreg_sale from unreg_sale inner join control_remittance on unreg_sale.control_id=control_remittance.control_id where remit_log='".$log_id."' and station='".$stationStamp."'";

	$rs2=$db->query($sql2);
	$nm2=$rs2->num_rows;	
	for($k=0;$k<$nm2;$k++){
		$row2=$rs2->fetch_assoc();
		$unreg_sale+=$row2['unreg_sale'];
	}		


//	$sql2="select sum(ot) as ot from control_cash inner join control_remittance on control_cash.control_id=control_remittance.control_id where remit_log='".$log_id."' and station='".$stationStamp."' group by type";
	$sql2="select (ot) as ot from control_cash inner join control_remittance on control_cash.control_id=control_remittance.control_id where remit_log='".$log_id."' and station='".$stationStamp."' group by type";

	$rs2=$db->query($sql2);
	$nm2=$rs2->num_rows;	
	for($k=0;$k<$nm2;$k++){
		$row2=$rs2->fetch_assoc();
		$ot_amount+=$row2['ot'];
	}	
	
//	$sql2="select sum(sj+sv) as discount from discount inner join control_remittance on discount.control_id=control_remittance.control_id where remit_log='".$log_id."' and station='".$stationStamp."'";
	$sql2="select (sj+sv) as discount from discount inner join control_remittance on discount.control_id=control_remittance.control_id where remit_log='".$log_id."' and station='".$stationStamp."'";

	$rs2=$db->query($sql2);
	$nm2=$rs2->num_rows;	
	for($k=0;$k<$nm2;$k++){
		$row2=$rs2->fetch_assoc();
		$discount+=$row2['discount'];
	}		
	$sql2="select (sj_amount+sv_amount) as refund from refund inner join control_remittance on refund.control_id=control_remittance.control_id where remit_log='".$log_id."' and station='".$stationStamp."'";
	
//	$sql2="select sum(sj_amount+sv_amount) as refund from refund inner join control_remittance on refund.control_id=control_remittance.control_id where remit_log='".$log_id."' and station='".$stationStamp."'";
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

?>
<meta http-equiv="refresh" content="60;url=dsr_summary_1.php" />
<link href="layout/dsr.css" rel="stylesheet" type="text/css" />

<?php

?>
<h3><?php echo strtoupper($logStation); 
if(isset($_GET['ext'])){
	echo " - Extension";
}

?></h3><br>
<div class='menuHeader'>
<a href='dsr_cash.php<?php echo $clause; ?>'>Part 1</a> | <a href='dsr_tickets_a.php<?php echo $clause; ?>'>Part 2</a> | <a href='dsr_tickets_b.php<?php echo $clause; ?>'>Part 3</a> | Summary
<?php 
if($extAvNM>0){ 
	if(isset($_GET['ext'])){ 
		echo "| <a href='dsr_summary_1.php'>Satellite</a> "; 
	}
	else {
		echo "| <a href='dsr_summary_1.php?ext=Y'>Extension</a> "; 
	
	}
} ?>
| <a href='#' onclick='window.open("generate_dsr.php<?php echo $clause; ?>","_blank")'>Printout</a>
</div>
<!--
<br><br>
<b>Summary</b>
<br>
-->
<table 
<?php
if(isset($_GET['ext'])){
	
}
else {

echo "width=100%";
}
?>
>
<tr>
<td valign=top width=30%>
<table class='dsrTable'>
<tr class='header'>
<th>Total Sales</th>
<th colspan=2>&nbsp;</th>
</tr>
<tr class='grid'>
	<th style='border:1px solid gray' width=40%>SJ</th>
	<td style='border:1px solid gray' align=right width=30%>PHP</td>
	<td style='border:1px solid gray' align=right width=30%><?php echo number_format($sjt_sales*1,2); ?></td>
</tr>	
<tr class='grid'>
	<th style='border:1px solid gray'>DSJ</th>
	<td style='border:1px solid gray'>&nbsp;</td>
	<td style='border:1px solid gray' align=right><?php echo number_format($sjd_sales*1,2); ?></td>
</tr>	
<tr class='grid'>
	<th style='border:1px solid gray'>SV</th>
	<td style='border:1px solid gray'>&nbsp;</td>
	<td style='border:1px solid gray' align=right><?php echo number_format($svt_sales*1,2); ?></td>
</tr>	
<tr class='grid'>
	<th style='border:1px solid gray'>DSV</th>
	<td style='border:1px solid gray'>&nbsp;</td>
	<td style='border:1px solid gray' align=right><?php echo number_format($svd_sales*1,2); ?></td>
</tr>	
<tr class='grid'>
	<th style='border:1px solid gray'>Fare Adjust.</th>
	<td style='border:1px solid gray'>&nbsp;</td>
	<td style='border:1px solid gray' align=right><?php echo number_format($fare_adjustment*1,2); ?></td>
</tr>	
<tr class='grid'>
	<th style='border:1px solid gray'>O.T.</th>
	<td style='border:1px solid gray'>&nbsp;</td>
	<td style='border:1px solid gray' align=right><?php echo number_format($ot_amount*1,2); ?></td>
</tr>	
<tr class='grid'>
	<th style='border:1px solid gray'>Unreg Sale</th>
	<td style='border:1px solid gray'>&nbsp;</td>
	<td style='border:1px solid gray' align=right><?php echo number_format($unreg_sale*1,2); ?></td>
</tr>	
<tr class='subheader'>
	<th style='border:1px solid gray'>Grand Total</th>
	<td style='border:1px solid gray'>&nbsp;</td>
	<td style='border:1px solid gray' align=right><b><?php echo number_format($grandTotal*1,2); ?></b></td>
</tr>	
<tr class='grid'>
	<th style='border:1px solid gray'>Less: Refund</th>
	<td style='border:1px solid gray'>&nbsp;</td>
	<td style='border:1px solid gray' align=right><?php echo number_format($refund*1,2); ?></td>
</tr>	
<tr class='grid'>
	<th style='border:1px solid gray'>Discount</th>
	<td style='border:1px solid gray'>&nbsp;</td>
	<td style='border:1px solid gray' align=right><?php echo number_format($discount*1,2); ?></td>
</tr>	
<tr class='header'>
	<th style='border:1px solid gray'>NET SALES</th>
	<td  style='border:1px solid gray' align=right>PHP</td>
	<td style='border:1px solid gray' align=right><b><?php echo number_format($netSales*1,2); ?></b></td>
</tr>	

</table>
</td>
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
	/*
	$rs2=$db->query($sql2);
	$nm2=$rs2->num_rows;	
	for($k=0;$k<$nm2;$k++){
		$row2=$rs2->fetch_assoc();
		if($row2['type']=="sjt"){
			$sjt_initial_amount+=$row2['initial'];

			$sjt_additional_amount+=$row2['additional'];	

			
		
		}
		else if($row2['type']=="sjd"){
			$sjd_initial_amount+=$row2['initial'];		
			$sjd_additional_amount+=$row2['additional'];	

		}
		else if($row2['type']=="svt"){
			$svt_initial_amount+=$row2['initial'];		
			$svt_additional_amount+=$row2['additional'];	

		}
		else if($row2['type']=="svd"){
			$svd_initial_amount+=$row2['initial'];		
			$svd_additional_amount+=$row2['additional'];	

		}
		
			
	}

*/	
	$sjt_subtotal=$sjt_beginning_balance+$sjt_initial_amount+$sjt_additional_amount;	
	$sjd_subtotal=$sjd_beginning_balance+$sjd_initial_amount+$sjd_additional_amount;	
	$svt_subtotal=$svt_beginning_balance+$svt_initial_amount+$svt_additional_amount;	
	$svd_subtotal=$svd_beginning_balance+$svd_initial_amount+$svd_additional_amount;	

	//$sql2="select sum(sjt) as sjt,sum(sjd) as sjd,sum(svt) as svt, sum(svd) as svd from control_sold inner join remittance on control_sold.control_id=remittance.control_id where log_id='".$log_id."'";
	$sql2="select * from control_sold inner join remittance on control_sold.control_id=remittance.control_id where log_id='".$log_id."'";

	
	$rs2=$db->query($sql2);
	$nm2=$rs2->num_rows;
	
	if($nm2>0){
		for($k=0;$k<$nm2;$k++){

		$row2=$rs2->fetch_assoc();
		$sjt_sold+=$row2['sjt'];
		$sjd_sold+=$row2['sjd'];
		$svt_sold+=$row2['svt'];
		$svd_sold+=$row2['svd'];
		}
	}

	$sql2="select * from control_unsold inner join control_remittance on control_unsold.control_id=control_remittance.control_id where log_id='".$log_id."'";

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

//	$sql2="select sum(amount) as ticket_sum,ticket_type,type from discrepancy_ticket where transaction_id in (select control_slip.id from control_slip inner join remittance on control_slip.id=remittance.control_id where remittance.log_id='".$log_id."') group by ticket_type";
	
//	$sql2="select sum(amount) as ticket_sum,ticket_type,type from discrepancy_ticket inner join control_remittance on transaction_id=control_id where remit_log='".$log_id."' group by ticket_type";
	
	$sql2="select (amount) as ticket_sum,ticket_type,type from discrepancy_ticket inner join control_remittance on transaction_id=control_id where remit_log='".$log_id."' group by ticket_type";
	
//	echo $sql2;
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

?>
<?php
if(isset($_GET['ext'])){
}
else {
?>
<td valign=top width=30%>
<table class='dsrTable'>
<tr class='header'>
	<th>Tickets</th>
	<th>SJT</th>
	<th>DSJT</th>
	<th>SVT</th>
	<th>DSVT</th>
</tr>
<tr class='grid' >
	<th style='border:1px solid gray'>Beginning Balance</th>
	<td style='border:1px solid gray' align=right><?php echo number_format($sjt_beginning_balance*1,0); ?></td>
	<td style='border:1px solid gray' align=right><?php echo number_format($sjd_beginning_balance*1,0); ?></td>
	<td style='border:1px solid gray' align=right><?php echo number_format($svt_beginning_balance*1,0); ?></td>
	<td style='border:1px solid gray' align=right><?php echo number_format($svd_beginning_balance*1,0); ?></td>
</tr>	
<tr class='grid'>
	<th style='border:1px solid gray'>Initial</th>
	<td style='border:1px solid gray' align=right><?php echo number_format($sjt_initial_amount*1,0); ?></td>
	<td style='border:1px solid gray' align=right><?php echo number_format($sjd_initial_amount*1,0); ?></td>
	<td style='border:1px solid gray' align=right><?php echo number_format($svt_initial_amount*1,0); ?></td>
	<td style='border:1px solid gray' align=right><?php echo number_format($svd_initial_amount*1,0); ?></td>
</tr>	
<tr class='grid'>
	<th style='border:1px solid gray'>Additional</th>
	<td style='border:1px solid gray' align=right><?php echo number_format($sjt_additional_amount*1,0); ?></td>
	<td style='border:1px solid gray' align=right><?php echo number_format($sjd_additional_amount*1,0); ?></td>
	<td style='border:1px solid gray' align=right><?php echo number_format($svt_additional_amount*1,0); ?></td>
	<td style='border:1px solid gray' align=right><?php echo number_format($svd_additional_amount*1,0); ?></td>
</tr>	
<tr class='subheader'>
	<th style='border:1px solid gray'><font>Total</font></th>
	<td style='border:1px solid gray' align=right><font><?php echo number_format($sjt_subtotal*1,0); ?></font></td>
	<td style='border:1px solid gray' align=right><font><?php echo number_format($sjd_subtotal*1,0); ?></font></td>
	<td style='border:1px solid gray' align=right><font><?php echo number_format($svt_subtotal*1,0); ?></font></td>
	<td style='border:1px solid gray' align=right><font><?php echo number_format($svd_subtotal*1,0); ?></font></td>
</tr>	
<tr class='grid'>
	<th style='border:1px solid gray'>Less: Tickets Sold</th>
	<td style='border:1px solid gray' align=right><?php echo number_format($sjt_sold*1,0); ?></td>
	<td style='border:1px solid gray' align=right><?php echo number_format($sjd_sold*1,0); ?></td>
	<td style='border:1px solid gray' align=right><?php echo number_format($svt_sold*1,0); ?></td>
	<td style='border:1px solid gray' align=right><?php echo number_format($svd_sold*1,0); ?></td>	
</tr>	
<tr class='grid'>
	<th style='border:1px solid gray'>Physically Defective</th>
	
	<td style='border:1px solid gray' align=right><?php echo number_format($sjt_physically_defective*1,0); ?></td>
	<td style='border:1px solid gray' align=right><?php echo number_format($sjd_physically_defective*1,0); ?></td>
	<td style='border:1px solid gray' align=right><?php echo number_format($svt_physically_defective*1,0); ?></td>
	<td style='border:1px solid gray' align=right><?php echo number_format($svd_physically_defective*1,0); ?></td>		
</tr>	




<tr class='grid'>
	<th style='border:1px solid gray'>Defective Tickets</th>
	<td style='border:1px solid gray' align=right><?php echo number_format($sjt_defective*1,0); ?></td>
	<td style='border:1px solid gray' align=right><?php echo number_format($sjd_defective*1,0); ?></td>
	<td style='border:1px solid gray' align=right><?php echo number_format($svt_defective*1,0); ?></td>
	<td style='border:1px solid gray' align=right><?php echo number_format($svd_defective*1,0); ?></td>		
</tr>	
<tr class='grid'>
	<th style='border:1px solid gray'>Over (Lacking)</th>
	<td style='border:1px solid gray' align=right><?php echo $sjt_label; ?></td>
	<td style='border:1px solid gray' align=right><?php echo $sjd_label; ?></td>
	<td style='border:1px solid gray' align=right><?php echo $svt_label; ?></td>
	<td style='border:1px solid gray' align=right><?php echo $svd_label; ?></td>		
</tr>	
<tr class='header'>
	<th style='border:1px solid gray'><font>Ending Balance</font></th>
	<td style='border:1px solid gray' align=right><font><?php echo number_format($sjt_grand_total*1,0); ?></font></td>
	<td style='border:1px solid gray' align=right><font><?php echo number_format($sjd_grand_total*1,0); ?></font></td>
	<td style='border:1px solid gray' align=right><font><?php echo number_format($svt_grand_total*1,0); ?></font></td>
	<td style='border:1px solid gray' align=right><font><?php echo number_format($svd_grand_total*1,0); ?></font></td>
</tr>	
</table>
</td>
<?php
}

?>
<?php
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

//	$sql2="select sum(sjt+sjd+svt+svd+c+ot) as fare_adjustment from fare_adjustment inner join control_remittance on fare_adjustment.control_id=control_remittance.control_id where  remit_log='".$log_id."'";
	$sql2="select (sjt+sjd+svt+svd+c+ot) as fare_adjustment from fare_adjustment inner join control_remittance on fare_adjustment.control_id=control_remittance.control_id where  remit_log='".$log_id."'";

	$rs2=$db->query($sql2);
	$nm2=$rs2->num_rows;	
	for($k=0;$k<$nm2;$k++){
		$row2=$rs2->fetch_assoc();
		$fare_adjustment+=$row2['fare_adjustment'];
	}	
	
//	$sql2="select sum(sj+sv) as unreg_sale from unreg_sale inner join control_remittance on unreg_sale.control_id=control_remittance.control_id where remit_log='".$log_id."'";
	$sql2="select (sj+sv) as unreg_sale from unreg_sale inner join control_remittance on unreg_sale.control_id=control_remittance.control_id where remit_log='".$log_id."'";

	$rs2=$db->query($sql2);
	$nm2=$rs2->num_rows;	
	for($k=0;$k<$nm2;$k++){
		$row2=$rs2->fetch_assoc();
		$unreg_sale+=$row2['unreg_sale'];
	}		


//	$sql2="select sum(ot) as ot from control_cash inner join control_remittance on control_cash.control_id=control_remittance.control_id where remit_log='".$log_id."' group by type";
	$sql2="select (ot) as ot from control_cash inner join control_remittance on control_cash.control_id=control_remittance.control_id where remit_log='".$log_id."' group by type";


	$rs2=$db->query($sql2);
	$nm2=$rs2->num_rows;	
	for($k=0;$k<$nm2;$k++){
		$row2=$rs2->fetch_assoc();
		$ot_amount+=$row2['ot'];
	}	
	
//	$sql2="select sum(sj+sv) as discount from discount inner join control_remittance on discount.control_id=control_remittance.control_id where remit_log='".$log_id."'";
	$sql2="select (sj+sv) as discount from discount inner join control_remittance on discount.control_id=control_remittance.control_id where remit_log='".$log_id."'";



	$rs2=$db->query($sql2);
	$nm2=$rs2->num_rows;	
	for($k=0;$k<$nm2;$k++){
		$row2=$rs2->fetch_assoc();
		$discount+=$row2['discount'];
	}		
	
//	$sql2="select sum(sj_amount+sv_amount) as refund from refund inner join control_remittance on refund.control_id=control_remittance.control_id where remit_log='".$log_id."'";
	$sql2="select (sj_amount+sv_amount) as refund from refund inner join control_remittance on refund.control_id=control_remittance.control_id where remit_log='".$log_id."'";

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

?>
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
//	$sql2="select (amount) as deposit from pnb_deposit where log_id='".$log_id."' and type='current'";

	$rs2=$db->query($sql2);
	$nm2=$rs2->num_rows;			
	if($nm2>0){
		$row2=$rs2->fetch_assoc();
		$pnb_deposit_c+=$row2['deposit'];
	}	
	
	$sql2="select sum(amount) as deposit from pnb_deposit where log_id='".$log_id."' and type='previous'";
//	$sql2="select (amount) as deposit from pnb_deposit where log_id='".$log_id."' and type='previous'";

	$rs2=$db->query($sql2);
	$nm2=$rs2->num_rows;			
	if($nm2>0){
		$row2=$rs2->fetch_assoc();
		$pnb_deposit_p+=$row2['deposit'];
	}	
	
//	$sql2="select sum(if(type='overage',amount,0)) as overage,sum(if(type='shortage',amount,0)) as shortage	from discrepancy where log_id='".$log_id."'";
	$sql2="select (if(type='overage',amount,0)) as overage,(if(type='shortage',amount,0)) as shortage	from discrepancy where log_id='".$log_id."'";
	//	$sql2="select sum(unpaid_shortage) as unpaid_shortage, sum(overage) as overage from control_cash where control_id in (SELECT control_id FROM remittance where log_id='".$log_id."')";
	$rs2=$db->query($sql2);
	$nm2=$rs2->num_rows;	
	
	for($k=0;$k<$nm2;$k++){
		$row2=$rs2->fetch_assoc();
		$overage+=$row2['overage'];
		
		$unpaid_shortage+=$row2['shortage'];
		
	}

	
	$discrepancySQL="SELECT * FROM transaction inner join cash_transfer on transaction.transaction_id=cash_transfer.transaction_id where transaction_type='shortage' and transaction.log_id='".$log_id."'";
//	echo $discrepancySQL;
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

?>
<?php
if(isset($_GET['ext'])){
}
else {
?>
<td valign=top align=center width=40%>
<table  class='dsrTable' width=80%>
<tr class='header'>
<th colspan=2>Cash</th>

</tr>
<tr class='grid'>
	<th style='border:1px solid gray'>Beginning Balance</th>
	<td style='border:1px solid gray' align=right><?php echo number_format($cash_beginning*1,2); ?></td>
</tr>	
<tr class='grid'>
	<th style='border:1px solid gray'>Revolving Fund</th>
	<td style='border:1px solid gray' align=right><?php echo number_format($revolving_fund*1,2); ?></td>
</tr>	
<tr class='grid'>
	<th style='border:1px solid gray'>Net Sales</th>
	<td style='border:1px solid gray' align=right><?php echo number_format($for_deposit*1,2); ?></td>
</tr>	
<tr class='subheader'>
	<th style='border:1px solid gray'><font>Total</font></th>
	<td style='border:1px solid gray' align=right><font><?php echo number_format($subtotal*1,2); ?></font></td>
</tr>
<tr class='grid'>
	<th style='border:1px solid gray'>PNB (Current)</th>
	<td style='border:1px solid gray' align=right><?php echo number_format($pnb_deposit_c*1,2); ?></td>
</tr>
<tr class='grid'>
	<th style='border:1px solid gray'>PNB (Previous)</th>
	<td style='border:1px solid gray' align=right><?php echo number_format($pnb_deposit_p*1,2); ?></td>
</tr>
<tr class='subheader'>
	<th style='border:1px solid gray'><font>Cash b-4 shortage</font></th>
	<td style='border:1px solid gray' align=right><font><?php echo number_format($subtotal_2*1,2); ?></font></td>
</tr>
<tr class='grid'>
	<th style='border:1px solid gray'>Add: Overage</th>
	<td style='border:1px solid gray' align=right><?php echo number_format($overage*1,2); ?></td>
</tr>
<tr class='grid'>
	<th style='border:1px solid gray'>Less: Unpaid Shortage</th>
	<td style='border:1px solid gray' align=right><?php echo number_format($unpaid_shortage*1,2); ?></td>
</tr>
<tr class='header'>
	<th style='border:1px solid gray'><font>Cash Ending Balance</font></th>
	<td style='border:1px solid gray' align=right><font><?php echo number_format($cash_ending*1,2); ?></font></td>
</tr>

</table>
</td>
<?php
}
?>
</tr>
</table>
<?php
    $mysql_exec_time = (microtime(true) - $start);
	echo "Loaded in ".$mysql_exec_time." seconds";
?>