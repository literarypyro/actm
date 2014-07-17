<?php
session_start();
?>
<?php

$log_id=$_SESSION['log_id'];
?>

<script language=javascript>
function openTransaction(){
	var transactWindow=document.getElementById('addTransaction').value;
	if(transactWindow=='transfer'){
		window.open("cash_transfer.php","transfer","height=800, width=500, scrollbars=yes");
	}
	else if(transactWindow=="deposit"){
		window.open("pnb_deposit.php","deposit","height=550, width=500");
	}
}

function openControlSlip(){
	//window.open("control_slip.php","control slip","height=800, width=500, scrollbars=yes");
	//document.getElementById('control_slip_form').target="control slip";
	
}
function deleteRecord(transaction,type){
	var check=confirm("Delete the Transaction?");
	
	if(check){
		window.open("delete_transaction.php?tID="+transaction+"&type="+type,"_blank");
	}
}
function searchTicketSeller(tName){
	var xmlHttp;
	
	var caHTML="";

	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlHttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlHttp.onreadystatechange=function()
	{
		if (xmlHttp.readyState==4 && xmlHttp.status==200)
		{
			caHTML=xmlHttp.responseText;


			if(caHTML=="None available"){
				//document.getElementById('searchResults').innerHTML="<td style='background-color:white; color:black;'></td><td style='background-color:white; color:black;'></td>";

			}
			else {

				var caTerms=caHTML.split("==>");
				
				var count=(caTerms.length)*1-1;

				var optionsGrid="";

			
				optionsGrid+="<select name='ticket_seller_control' id='ticket_seller_control'>";				
				

				for(var n=0;n<count;n++){
					var parts=caTerms[n].split(";");

					optionsGrid+="<option value='"+parts[0]+"' >";
					optionsGrid+=parts[2]+", "+parts[1];
					optionsGrid+="</option>";
					
				}
				optionsGrid+="</select>";
					
				document.getElementById('cafill').innerHTML=optionsGrid;
				//document.getElementById('programpageNumber').value="";
			}

		}
	} 
	

	xmlHttp.open("GET","process search.php?searchTS="+tName,true);
	xmlHttp.send();	



	


}



</script>
<body>
<?php
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

require("logbook header.php");

?>



<br><br>
<div align=right>
</div>
<table width='100%' id='menu'>
<tr id='selectLogbook'>
<td colspan=2>SELECT LOGBOOK | <a href='test_cash_logbook.php'>Logbook (New Design)</a></td>
</tr>
<tr id='menuHeader'>

<td>
Cash Logbook | <a href='sj_ticket_logbook.php'>SJ Logbook</a> | <a href='sv_ticket_logbook.php'>SV Logbook</a> | <a href='#' onclick='window.open("dsr_cash.php","_blank")'>Detailed Sales Report</a>
</td>
<td align=right >
<a id='logout' href='logout.php'>Log Out</a>
</td>
</tr>
</table>
<br>
<table width=100% class='logbookTable'>
<tr>
	<th colspan=3>Particulars</th><th colspan=3>Cash In</th><td class='subheader' rowspan=2>Short<br> (Overage)</td><th colspan=3>Cash Out</th><th colspan=3>Balance</th><td  class='subheader'  rowspan=2>Remarks</td>
</tr>
<tr class='subheader'>
	<td>Time</td>
	<td>Name</td>
	<td align=center>Id No.</td>
	
	<td align=right>Revolving Fund</td>
	<td align=right>For Deposit/<br>Net Revenue</td>
	<td align=center>Total</td>

	<td align=right>Revolving Fund</td>
	<td align=right>PNB Deposit</td>
	<td align=right>Total</td>

	<td align=right>Revolving Fund</td>
	<td align=right>For Deposit</td>
	<td align=center>Total</td>
</tr>
<?php
$station=$_SESSION['station'];

$db=new mysqli("localhost","root","","finance");

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

$alternate="SELECT * FROM beginning_balance_cash inner join logbook on beginning_balance_cash.log_id=logbook.id and station='".$station."' order by date desc,field(revenue,'close','open'),field(shift,2,1,3)";

$rs2=$db->query($alternate);
$row=$rs2->fetch_assoc();
	$revolvingTotal=$row['revolving_fund'];
	$depositTotal=$row['for_deposit'];
	$grandTotal=($row['for_deposit']*1)+($row['revolving_fund']*1);
	
	$insert="insert into beginning_balance_cash(log_id,revolving_fund,for_deposit) values ('".$log_id."','".$revolvingTotal."','".$depositTotal."')";
	$insertRS=$db->query($insert);	

}	
?>
<tr>
	<td colspan=3>Beginning Balance <a href='#' style='text-decoration:none' onclick='window.open("beginning data entry.php?loID=<?php echo $log_id; ?>&type=cash","beginning","height=300, width=300")' >[Data Entry]</a></td>

	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>

	<td>&nbsp;</td>

	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>

	<td align=right><?php echo number_format($row['revolving_fund']*1,2); ?></td>
	<td align=right><?php echo number_format($row['for_deposit']*1,2); ?></td>
	<td align=right><?php echo number_format(($row['for_deposit']*1)+($row['revolving_fund']*1),2); ?></td>
	<td>&nbsp;</td>

</tr>
	
<?php

$db=new mysqli("localhost","root","","finance");
$sql="select * from transaction where log_id='".$log_id."' and log_type in ('cash') and transaction_type not in ('catransfer') order by id*1";

$rs=$db->query($sql);
$nm=$rs->num_rows;

for($i=0;$i<$nm;$i++){
	$cash_asst="";
	$row=$rs->fetch_assoc();

	$date=date("h:i a",strtotime($row['date']));
	$edit_id=$row['id'];
	$transaction_id=$row['transaction_id'];
	
	$type=$row['transaction_type'];
	$log_type=$row['log_type'];
	
	if($row['reference_id']==""){
	$remarks="&nbsp;";
	}
	else {
	$remarks=$row['reference_id'];
	}
	if($type=="shortage"){
		$type="remittance";
		$log_type="shortage";


	}
/*
	else {
		echo $transaction_id."remit";
	
	}
	*/
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
				
				$cash_assistantSQL="select * from login where username='".$cashRow['cash_assistant']."'";
				$cash_assistantRS=$db->query($cash_assistantSQL);
				$cash_assistantRow=$cash_assistantRS->fetch_assoc();
				
				$cash_asst=$cash_assistantRow['lastName'].", ".$cash_assistantRow['firstName'];
				
				
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
	

	
	
	
?>	
	<?php 
	$style="";

	
	
	$sql3="select * from cash_remittance where ticket_seller='".$ticketRow['id']."' order by id desc";
	
	$rs3=$db->query($sql3);
	$nm3=$rs3->num_rows;
	if($nm3>0){
		$row3=$rs3->fetch_assoc();
		if($row3['cash_remittance']==""){
			if($type=="deposit"){
			}
			else {
			//	$style="style='background-color:yellow;'";
			}
		}
	}
	
	?>

<tr <?php echo $style; ?>>
	
	<td><?php echo $date; ?></td>
	<td>
	<?php 
	if($type=="deposit")
	{ echo "<a href='#' style='text-decoration:none' onclick='window.open(\"pnb_deposit.php?tID=".$edit_id."\",\"deposit\",\"height=550, width=500, scrollbars=yes\")'>PNB Deposit - ".strtoupper($deposit_type)."</a>"; 


	} 
	else if(($type=="remittance")||($type=="partial_remittance")){ 
		if($log_type=="cash"){
			if($cashStation=="annex"){
				if($_SESSION['viewMode']=="login"){
					echo "<a href='#' style='text-decoration:none' onclick='window.open(\"cash_transfer.php?tID=".$edit_id."\",\"transfer\",\"height=800, width=500, scrollbars=yes\")'>ANNEX</a>"; 
				}
				else {
					echo "ANNEX";
				}
			
			}
			else {
				if($_SESSION['viewMode']=="login"){
					echo "<a href='#' style='text-decoration:none' onclick='window.open(\"cash_transfer.php?tID=".$edit_id."\",\"transfer\",\"height=800, width=500, scrollbars=yes\")'>".strtoupper($ticketRow['last_name']).", ".$ticketRow['first_name'].$suffix."</a>"; 
				}
				else {
					echo strtoupper($ticketRow['last_name']).", ".$ticketRow['first_name'].$suffix;	
				}
			}
		}
		else if($log_type=="shortage"){
			if($_SESSION['viewMode']=="login"){
				echo "<a href='#' style='text-decoration:none' onclick='window.open(\"cash_transfer.php?tID=".$edit_id."\",\"transfer\",\"height=800, width=500, scrollbars=yes\")'>".strtoupper($ticketRow['last_name']).", ".$ticketRow['first_name'].$suffix." - Payment for Shortage</a>"; 		
			}
			else {
				echo strtoupper($ticketRow['last_name']).", ".$ticketRow['first_name'].$suffix." - Payment for Shortage";
			}
		}
	} 
	else if($type=="allocation"){ 
		if($cashStation=="annex"){
			if($_SESSION['viewMode']=="login"){
				echo "<a href='#' style='text-decoration:none' onclick='window.open(\"cash_transfer.php?tID=".$edit_id."\",\"transfer\",\"height=800, width=500, scrollbars=yes\")'>ANNEX</a>";  
			}
			else {
				echo "ANNEX";
			}
		}
		else {
			if($_SESSION['viewMode']=="login"){
				echo "<a href='#' style='text-decoration:none' onclick='window.open(\"cash_transfer.php?tID=".$edit_id."\",\"transfer\",\"height=800, width=500, scrollbars=yes\")'>".strtoupper($ticketRow['last_name']).", ".$ticketRow['first_name'].$suffix."</a>";  
			}
			else {
				echo strtoupper($ticketRow['last_name']).", ".$ticketRow['first_name'].$suffix;
			}
		}
	}	
	?>
	
	</td>
	<td align=center>
	<?php 
		if($type=="deposit"){
			echo "&nbsp;";
		}
		else if($type=="remittance"){
			if($log_type=="cash"){
				if($cashStation=="annex"){
					echo "&nbsp;";
				}
				else {
				echo $ticketRow['id'];
				}
			}
			else {
				echo "&nbsp;";
			}
			
		}
		else { 
			if($cashStation=="annex"){
				echo "&nbsp;";
			}
			else {
			echo $ticketRow['id'];
			}
		}	
		?>
	</td>	
	
	<?php 
	if(($type=="remittance")||($type=="partial_remittance")){ 
	?>
		<td align=right><?php echo number_format($revolving*1,2); ?></td>
		<td align=right><?php echo number_format($deposit*1,2); ?></td>
		<td align=right><?php echo number_format($total*1,2); ?></td>
	
	
	<?php
	
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
	?>
		<td align=right><?php echo $overageLabel; ?></td>

	<?php
	}
	else {
	?>

		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>

	<?php
	}
	?>
	
	<?php 
	if($type=="allocation"){
	?>
		<td align=right><?php echo number_format($revolving*1,2); ?></td>
		<td>&nbsp;</td>
		<td align=right><?php echo number_format($revolving*1,2); ?></td>
	


		
	<?php
	}
	else {
	?>
		<td>&nbsp;</td>
		<?php
		if($type=="deposit"){
		?>		
			<td align=right><?php echo number_format($cashRow['amount']*1,2); ?></td>
			<td align=right><?php echo number_format($cashRow['amount']*1,2); ?></td>
		
	<!--	<td>&nbsp;</td>
		-->
		<?php
		}
		else {
		?>		
			<td>&nbsp;</td>
			<td>&nbsp;</td>

	<!--	<td>&nbsp;</td>
		-->	
		<?php	
		}
		?>
	<?php
	}
	?>

	
	<?php 
	
	if($type=="allocation"){
		$revolvingTotal=$revolvingTotal-$revolving;
	
	}
	else if(($type=="remittance")||($type=="partial_remittance")){ 
		$revolvingTotal=$revolvingTotal+$revolving;
		
		$depositTotal=$depositTotal+$deposit;
	}
	
	if($type=="deposit"){
		$depositTotal=$depositTotal-($cashRow['amount']*1);
	
	}
	$displayTotal=($revolvingTotal*1)+($depositTotal*1);
	/*
	if($overageSwitch=="overage"){
		$displayTotal-=$overage;
	}
	else if($overageSwitch=="shortage"){
		$displayTotal+=$overage;
	}
	*/
	
	?>
	<td align=right><?php echo number_format($revolvingTotal*1,2); ?></td>
	<td align=right><?php echo number_format($depositTotal*1,2); ?></td>
	<td align=right><?php echo number_format($displayTotal*1,2); ?></td>
	<td align=right><?php echo $remarks; ?> <a href='#' class='delete'  onclick='deleteRecord("<?php echo $transaction_id; ?>","cash")' >X</a></td>
</tr>
<?php

}

?>
	
	
<?php
 if($revolvingTotal==""){ 
 } 
 else { 
	
 
 
 
	$next_id=$_SESSION['next_log_id'];
	$sqlBalance="select * from beginning_balance_cash where log_id='".$next_id."'";
	$rsBalance=$db->query($sqlBalance);
	$nmBalance=$rsBalance->num_rows;
	if($nmBalance>0){
		$transferBalance="update beginning_balance_cash set revolving_fund='".$revolvingTotal."',for_deposit='".$depositTotal."' where log_id='".$next_id."'";
	
	}
	else {
		$transferBalance="insert into beginning_balance_cash(log_id,revolving_fund,for_deposit) values ('".$next_id."','".$revolvingTotal."','".$depositTotal."')";

	}

	$transferRS=$db->query($transferBalance);	
	
	$sqlBalance="select * from ending_balance_cash where log_id='".$log_id."'";
	$rsBalance=$db->query($sqlBalance);
	$nmBalance=$rsBalance->num_rows;
	if($nmBalance>0){
		$transferBalance="update ending_balance_cash set revolving_fund='".$revolvingTotal."',for_deposit='".$depositTotal."' where log_id='".$log_id."'";
	
	}
	else {
		$transferBalance="insert into ending_balance_cash(log_id,revolving_fund,for_deposit) values ('".$log_id."','".$revolvingTotal."','".$depositTotal."')";
	
	}

	$transferRS=$db->query($transferBalance);		
	
	
	
	
 } 
 
 ?>
<?php
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
	
?>
<tr>
	<td>&nbsp;</td>
	<td>
	<?php
	if($_SESSION['viewMode']=="login"){
	?>
	<a href='#' style='text-decoration:none' onclick='window.open("cash_transfer.php?tID=<?php echo $edit_id; ?>","transfer","height=800, width=500, scrollbars=yes")'>
	<?php
	}
	?>
	Turnover to CA
	<?php
	if($_SESSION['viewMode']=="login"){
	?>
	</a>
	<?php
	}
	?>	
	</td>
	<td>&nbsp;</td>
	
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	
	<td align=right><?php echo number_format($revolvingTransfer,2); ?></td>
	<td align=right><?php echo number_format($depositTransfer,2); ?></td>
	<td align=right><?php echo number_format($totalTransfer,2); ?></td>

	<td align=center>
	<?php 
	if($revolvingTotal==0){ echo "---"; } else { 
		if($revolvingTotal<0){
			echo "(".number_format(($revolvingTotal*-1),2).")";
		
		}
		else {
			echo number_format($revolvingTotal,2); 
		}
	} 
	?></td>
	<td align=center>
	<?php 
	if($depositTotal==0){ echo "---"; } else { 
		if($depositTotal<0){
			echo "(".number_format(($depositTotal*-1),2).")";
		
		}
		else {
			echo number_format($depositTotal,2); 
		}
	} 
	?></td>
	<td align=center><?php if($displayTotal==0){ echo "---"; } 
	else { 
		if($displayTotal<0){
			echo "(".number_format(($displayTotal*-1),2).")";
		
		}
		else {
			echo number_format($displayTotal,2); 
		}
	} 
	?></td>

	<td align=right><?php echo $remarks; ?> <a class='delete' href='#' onclick='deleteRecord("<?php echo $transaction_id; ?>","cash")' >X</a></td>	
</tr>
<?php
}
?> 
</table>
<table width=100%>
<tr>
<td>
<a href='generateCashLogbook.php' target='_blank'>Generate Printout</a>

</td>
<td align=right>
<a href='select_log_shift.php'>Change Log Shift</a>
</td>
</tr>
</table>

<br>
<?php
if($_SESSION['viewMode']=="login"){
?>
<br>
<select name='addTransaction' id='addTransaction'>
<option value='transfer'>Cash Transfer Form</option>
<option value='deposit'>PNB Deposit</option>


</select>
<input type=button value='Open Window' onclick='openTransaction()' />
<br><br>
<?php 
}
if($_SESSION['viewMode']=="login"){
?>

<table class='controlslip' style='border: 1px solid gray'>
<tr id='cslip'><th colspan=3>Control Slips currently open:</th></tr>
<tr id='cheader'><th>Name</th><th>Station</th><th>Unit</th></tr>
<?php
$db=new mysqli("localhost","root","","finance");
$sql="select control_slip.id as control_id,control_slip.*,ticket_seller.* from control_slip inner join ticket_seller on control_slip.ticket_seller=ticket_seller.id where control_slip.status='open' order by ticket_seller.last_name ";
$rs=$db->query($sql);
$nm=$rs->num_rows;
if($nm>0){
	for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
		$stationSQL="select * from station where id='".$row['station']."'";
		$stationRS=$db->query($stationSQL);
		$stationRow=$stationRS->fetch_assoc();
		$station_name=$stationRow['station_name'];
		$control_id=$row['control_id'];
	?>
		<tr>
		<td><a href="#" onclick='window.open("control_slip.php?edit_control=<?php echo $control_id; ?>","control slip","height=750, width=800, scrollbars=yes")' ><?php echo strtoupper($row['last_name']).", ".$row['first_name']; ?></td>
		<td><?php echo $station_name; ?></td>
		<td><?php echo $row['unit']; ?></td>
		</tr>

<?php
	}
}

?>




</table>
<font class='red'> Please close Control Slips not <br>closed from previous day</font>
<br>
<br>
<form name='control_slip_form' id='control_slip_form' target='control slip' action='control_slip.php' method='post' onsubmit="window.open('control_slip.php','control slip','width=750,height=800,scrollbars=yes')">
<b>Control Slip</b><br>
Ticket Seller 	
<?php
	$db=new mysqli("localhost","root","","finance");
	$sql="select * from ticket_seller order by last_name";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	?>
	<div id='cafill' name='cafill'>
	<select name='ticket_seller_control'>
	<?php 
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
	?>
		<option value='<?php echo $row['id']; ?>'><?php echo strtoupper($row['last_name']).", ".$row['first_name']; ?></option>
	<?php
	}
	?>
	
	</select>
	</div>
	<select name='unit' id='unit'>
	<option>A/D1</option>
	<option>A/D2</option>
	<option>TIM1</option>
	<option>TIM2</option>
	<option>TIM3</option>

	</select>
	
		<select name='station' id='station'>

	<?php
	$db=new mysqli("localhost","root","","finance");
	$logSQL="select * from logbook where id='".$log_id."'";

	$logRS=$db->query($logSQL);
	$logNM=$logRS->num_rows;
	if($logNM>0){
		$logRow=$logRS->fetch_assoc();
		$cash_assistant=$logRow['cash_assistant'];

		$stationSQL="select * from station where id='".$logRow['station']."'";
		$stationRS=$db->query($stationSQL);
		$stationRow=$stationRS->fetch_assoc();
		$station_name=$stationRow['station_name'];
		$station_id=$stationRow['id'];

	}
	?>
	<option value='<?php echo $station_id; ?>'><?php echo $station_name; ?></option>
	<?php
	$extensionSQL="select * from extension inner join station on extension.extension=station.id where extension.station='".$logRow['station']."'";
	$extensionRS=$db->query($extensionSQL);
	$extensionNM=$extensionRS->num_rows;
	if($extensionNM>0){
		$extensionRow=$extensionRS->fetch_assoc();
		$extensionID=$extensionRow['extension'];
		$extensionName=$extensionRow['station_name'];
	?>
	<option value='<?php echo $extensionID; ?>'><?php echo $extensionName; ?></option>
	<?php
	}
	?>

	</select>
	
	<input type=submit value='Open Window'  />
		<br>
	Search Ticket Seller <input type=text name='searchTS' id='searchTS' onkeyup='searchTicketSeller(this.value)' />	
</form>
<?php
}
?>
</body>