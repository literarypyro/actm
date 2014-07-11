<?php
session_start();
?>
<?php
$start = microtime(true);

$dsrDate=$_SESSION['log_date'];
$station=$_SESSION['station'];
?>
<?php
function discrepCheck($discrepancy){

	$label="";


	if($discrepancy>=0){
		$label=$discrepancy; 
	}
	else {
		$label="(".($discrepancy*-1).")";
	}

	return $label;
	
	
	
	
	
	
}

$clause="";

if(isset($_GET['ext'])){
	$clause="?ext=Y";
}

?>
<?php
$db=new mysqli("localhost","root","","finance");
?>

<meta http-equiv="refresh" content="60;url=dsr_tickets_a.php<?php echo $clause; ?>" />
<link href="layout/dsr.css" rel="stylesheet" type="text/css" />
<script language='javascript' src='ajax.js'></script>
<script language='javascript'>

var extension="";

function deleteRow(remitId,ext){

	var check=confirm("This will delete this Remittance Record, but other data will remain.  Continue?");
	if(check){
		if(ext=="Y"){
			extension="?ext=Y";
		}

		makeajax("processing.php?deleteRemittance="+remitId,"reloadPage");		
	}	
}

function reloadPage(ajaxHTML){
	self.location="dsr_tickets_a.php"+extension;


}

</script>
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
<a href='dsr_cash.php<?php echo $clause; ?>'>Part 1</a> | Part 2 | <a href='dsr_tickets_b.php<?php echo $clause; ?>'>Part 3</a> | <a href='dsr_summary_1.php<?php echo $clause; ?>'>Summary</a>
<?php 
if($extAvNM>0){ 
	if(isset($_GET['ext'])){ 
		echo "| <a href='dsr_tickets_a.php'>Satellite</a> "; 
	}
	else {
		echo "| <a href='dsr_tickets_a.php?ext=Y'>Extension</a> "; 
	
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
<th rowspan=2>&nbsp;</th>


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


</tr>
<?php
$previousDate=date("Y-m-d",strtotime($dsrDate."-1 day"));

$sql="select * from logbook where date='".$dsrDate."' and station='".$station."' order by field(revenue,'open','close'),field(shift,3,1,2)";


$rs=$db->query($sql);
$nm=$rs->num_rows;

for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
	$log_id=$row['id'];
		
	$sql2="select * from control_remittance where remit_log='".$log_id."' and station='".$stationStamp."' group by remit_ticket_seller,unit";
	$rs2=$db->query($sql2);
	
	
	$nm2=$rs2->num_rows;
	
	$cash_assistSQL="select * from login where username='".$row['cash_assistant']."' limit 1";
	//echo "explain ".$cash_assistSQL."<br>";
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
			$remit_id=$row2['remit_id'];
			$unit=$row2['unit'];		
			$ticketsellerSQL="select * from ticket_seller where id='".$row2['remit_ticket_seller']."'";	
			$ticketsellerRS=$db->query($ticketsellerSQL);
			$ticketsellerRow=$ticketsellerRS->fetch_assoc();
			$ticket_seller=$ticketsellerRow['last_name'].", ".$ticketsellerRow['first_name'];
			$ticket_id=$ticketsellerRow['id'];

			
			$unregSQL="select sum(sjt+sjd) as sj,sum(svt+svd) as sv from unreg_sale where control_id='".$row2['control_id']."'";
			$unregRS=$db->query($unregSQL);
			$unregNM=$unregRS->num_rows;
			
			$sj_unreg=0;
			$sv_unreg=0;
			
			for($m=0;$m<$unregNM;$m++){
				$unregRow=$unregRS->fetch_assoc();
				$sj_unreg+=$unregRow['sj']*1;
				$sv_unreg+=$unregRow['sv']*1;
			
			}
			
			$discountSQL="select sum(sj) as sj,sum(sv) as sv from discount where control_id='".$row2['control_id']."'";

			$discountRS=$db->query($discountSQL);
			
			$discountNM=$discountRS->num_rows;
			
			$sv_discount=0;
			$sj_discount=0;
			
			for($m=0;$m<$discountNM;$m++){
				$discountRow=$discountRS->fetch_assoc();
				$sj_discount+=$discountRow['sj']*1;
				$sv_discount+=$discountRow['sv']*1;
			
			}			
			
			$refundSQL="select sj_amount,sv_amount,sj,sv from refund  where control_id='".$row2['control_id']."'";

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
			
			$cashSQL="select sum(if(discrepancy.type='overage',amount,0)) as overage,sum(if(discrepancy.type='shortage',amount,0)) as unpaid_shortage from discrepancy inner join cash_transfer on discrepancy.transaction_id=cash_transfer.transaction_id where discrepancy.log_id='".$log_id."' and discrepancy.ticket_seller='".$row2['remit_ticket_seller']."' and cash_transfer.station='".$stationStamp."' and cash_transfer.unit='".$unit."'";

			$cashRS=$db->query($cashSQL);
			$cashNM=$cashRS->num_rows;
			
			$overage=0;
			$unpaid_shortage=0;
			
			for($n=0;$n<$cashNM;$n++){
				$cashRow=$cashRS->fetch_assoc();
				$overage+=$cashRow['overage']*1;
				$unpaid_shortage+=$cashRow['unpaid_shortage']*1;
			
			}	

			
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

			
			if($k==0){

	
			}
			else {
				echo "<tr class='grid'>";
			}
					
			
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
			<td><a href='#' onclick="deleteRow('<?php echo $remit_id; ?>','<?php echo $_GET['ext']; ?>')">X</a></td>
			
			</tr>
			
		<?php	
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
		}		
		
		
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
			<td>&nbsp;</td>
		</tr>
		<?php
		
	}
	?>

	
	
	
	<?php
	
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
		<td>&nbsp;</td>
	</tr>
</table>
<?php
   $mysql_exec_time = (microtime(true) - $start);
	echo "Loaded in ".$mysql_exec_time." seconds";
?>