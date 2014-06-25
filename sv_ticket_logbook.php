<?php
session_start();
?>
<?php

$log_id=$_SESSION['log_id'];

?>
<script language=javascript>
function openTransaction(){
	var transactWindow=document.getElementById('addTransaction').value;
	if(transactWindow=='ticket_order'){
		window.open("ticket_order.php","ticket_order","height=420, width=510, scrollbars=yes");
	}
	else if(transactWindow=="defective"){
		window.open("physically defective.php","defective","height=300, width=450");
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
function deleteRecord(transaction,type){
	var check=confirm("Delete the Transaction?");
	
	if(check){
		window.open("delete_transaction.php?tID="+transaction+"&type="+type,"_blank");
	}
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

$station=$row['station'];
$stationSQL="select * from station where id='".$row['station']."'";
$stationRS=$db->query($stationSQL);
$stationRow=$stationRS->fetch_assoc();
$logST=$row['station'];

$logStation=$stationRow['station_name'];
$shiftSQL="select * from shift where shift_id='S".$logShift."'";
$shiftRS=$db->query($shiftSQL);
$shiftRow=$shiftRS->fetch_assoc();
$shiftName=$shiftRow['shift_name'];
require("logbook header.php");
?>



<br><br>

<table width='100%' id='menu' >
<tr id='selectLogbook'>
<td colspan=2>SELECT LOGBOOK</td>
</tr>
<tr id='menuHeader'>
<td>
<a href='cash_logbook.php'>Cash Logbook</a> | <a href='sj_ticket_logbook.php'>SJ Logbook</a> | SV Logbook | <a href='#' onclick='window.open("dsr_cash.php","_blank")'>Detailed Sales Report</a>
</td>
<td align=right><a href='logout.php'>Log Out</a>

</td>
</tr>
</table>
<br>
<table class='logbookTable'   width=100%>
<tr >
<th class='subheader' rowspan=3>Time</th>
<th colspan=2>Particulars</th>

<th colspan=4>Tickets Supplied In</th>

<th colspan=4>Tickets Supplied Out</th>

<th colspan=4>Tickets Remaining</th>
<th class='subheader'  rowspan=3>Remarks</th>
</tr>
<tr  class='subheader'>
<td align=center rowspan=2>Name</td>
<td align=center rowspan=2>Id No.</td>

<td align=center colspan=2>SV</td>
<td align=center colspan=2>SVD</td>

<td align=center colspan=2>SV</td>
<td align=center colspan=2>SVD</td>

<td align=center colspan=2>SV</td>
<td align=center colspan=2>SVD</td>

</tr>

<tr>
<td align=right>SV</td>
<td align=right>Loose</td>
<td align=right>SVD</td>
<td align=right>Loose</td>

<td align=right>SV</td>
<td align=right>Loose</td>
<td align=right>SVD</td>
<td align=right>Loose</td>

<td align=right>SV</td>
<td align=right>Loose</td>
<td align=right>SVD</td>
<td align=right>Loose</td>

</tr>
<tr>
<?php
$db=new mysqli("localhost","root","","finance");
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

$alternate="SELECT * FROM beginning_balance_svt inner join logbook on beginning_balance_svt.log_id=logbook.id and station='".$station."' order by date desc,field(revenue,'close','open'),field(shift,2,1,3)";

$rs2=$db->query($alternate);
$row=$rs2->fetch_assoc();
$svt_loose_1=$row['svt_loose'];
$svt_packs_1=$row['svt'];


$svd_loose_1=$row['svd_loose'];
$svd_packs_1=$row['svd'];

	$insert="insert into beginning_balance_svt(log_id,svt,svt_loose,svd,svd_loose) values ('".$log_id."','".$svt_packs_1."','".$svt_loose_1."','".$svd_packs_1."','".$svd_loose_1."')";
	$insertRS=$db->query($insert);	


}	
?>






<td colspan=3>Beginning Balance <a href='#' style='text-decoration:none' onclick='window.open("beginning data entry.php?loID=<?php echo $log_id; ?>&type=sv","beginning","height=300, width=300")' >[Data Entry]</a></td>
<td>&nbsp;</td>
<td>&nbsp;</td>

<td>&nbsp;</td>
<td>&nbsp;</td>

<td>&nbsp;</td>
<td>&nbsp;</td>

<td>&nbsp;</td>
<td>&nbsp;</td>





<td align=right><?php echo $svt_packs_1; ?></td>
<td align=right><?php echo $svt_loose_1; ?></td>

<td align=right><?php echo $svd_packs_1; ?></td>
<td align=right><?php echo $svd_loose_1; ?></td>
<td>&nbsp;</td>
</tr>
<?php
$db=new mysqli("localhost","root","","finance");
$sql="select * from transaction where log_id='".$log_id."' and log_type in ('ticket','initial','annex','finance') and transaction_type not in ('ticket_amount') order by id*1";
$rs=$db->query($sql);
$nm=$rs->num_rows;
for($a=0;$a<$nm;$a++){

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
	$suffix="";
	
	
	$svt_packs=0;
	$svd_packs=0;
	$svt_loose=0;
	$svd_loose=0;

	$svt_loose_in=0;	
	$svd_loose_in=0;	
	
	if(($row['log_type']=='ticket')||($row['log_type']=="annex")||($row['log_type']=="finance")){
		$svt_packs=0;
		$svd_packs=0;
		$svt_loose=0;
		$svd_loose=0;		
		$svt_loose_in=0;	
		$svd_loose_in=0;	

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
		
		
		
		$ticketSellerId=$ticketRow['ticket_seller'];			
		$unitType=$ticketRow['unit'];	
		$remarks=$ticketRow['reference_id'];	
		$svt_loose=$ticketRow['svt_loose'];
		$svt_packs=$ticketRow['svt'];
		
		//$svt_packs=($ticketRow['svt']*1-$svt_loose);
		$svd_loose=$ticketRow['svd_loose'];
		$svd_packs=$ticketRow['svd'];		
		
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
					
					if($ticketsRow['station']==$logST){
					}
					else {
						$extensionSQL="select * from station where id='".$ticketRow['station']."'";
						$extensionRS=$db->query($extensionSQL);
						$extensionRow=$extensionRS->fetch_assoc();
								
						$suffix=" - ".$extensionRow['station_name'];
							
					}		
					$remarks=$ticketsRow['reference_id'];
					$ticketSellerId=$ticketsRow['ticket_seller'];
					$unitType=$ticketsRow['unit'];					
				
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
			$svt_loose=0;
			$svd_loose=0;			

			$svt_packs=0;
			$svd_packs=0;	
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
					if($ticketsRow['station']==$logST){
					}
					else {
						$extensionSQL="select * from station where id='".$ticketRow['station']."'";
						$extensionRS=$db->query($extensionSQL);
						$extensionRow=$extensionRS->fetch_assoc();
								
						$suffix=" - ".$extensionRow['station_name'];
							
					}			
					$remarks=$ticketsRow['reference_id'];		
					$ticketSellerId=$ticketsRow['ticket_seller'];
					$unitType=$ticketsRow['unit'];				
				
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


?>
<tr>
	<td><?php echo $date; ?></td>
	<td>
	<?php
	if($log_type=="initial"){
		if($_SESSION['viewMode']=="login"){
			echo "<a href='#' style='text-decoration:none' onclick='window.open(\"control_slip.php?edit_control=".$control_id."\",\"control slip\",\"height=750, width=800, scrollbars=yes\")'>".strtoupper($ticketSellerRow['last_name']).", ".$ticketSellerRow['first_name']; 
			if($unitType==""){ } else { echo " - ".$unitType; } 
			echo "</a>"; 
		}
		else {
			echo strtoupper($ticketSellerRow['last_name']).", ".$ticketSellerRow['first_name'];
			if($unitType==""){ } else { echo " - ".$unitType; } 
			
		}

	}
	else if($log_type=="annex"){
		if($_SESSION['viewMode']=="login"){
			echo "<a href='#' style='text-decoration:none' onclick='window.open(\"ticket_order.php?tID=".$edit_id."\",\"transfer\",\"height=420, width=500, scrollbars=yes\")'>FROM ANNEX</a>"; 
		}
		else {
			echo "FROM ANNEX";
		}
	}
	else if($log_type=="finance"){
		if($_SESSION['viewMode']=="login"){
			echo "<a href='#' style='text-decoration:none' onclick='window.open(\"ticket_order.php?tID=".$edit_id."\",\"transfer\",\"height=420, width=500, scrollbars=yes\")'>FROM FINANCE TRAIN</a>"; 
		}
		else {
			echo "FROM FINANCE TRAIN";
		}

	}

	else {
		if($_SESSION['viewMode']=="login"){
			echo "<a href='#' style='text-decoration:none' onclick='window.open(\"ticket_order.php?tID=".$edit_id."\",\"transfer\",\"height=420, width=500, scrollbars=yes\")'>".strtoupper($ticketSellerRow['last_name']).", ".$ticketSellerRow['first_name'].$suffix; 
			if($unitType==""){ } else { echo " - ".$unitType; } echo "</a>"; 
		}
		else {
			echo strtoupper($ticketSellerRow['last_name']).", ".$ticketSellerRow['first_name'].$suffix;
			if($unitType==""){ } else { echo " - ".$unitType; }			
		}
	}	
	?></td>
	<td align=center>
	<?php 
		if($type=="deposit"){
			echo "&nbsp;";
		}
		else if($type=="remittance"){
			if($log_type=="cash"){
				echo $ticketSellerRow['id'];
			}
			else {
				if(($log_type=='annex')||($log_type=='finance')){
					echo "&nbsp;";
				}
				else {
					echo $ticketSellerRow['id'];
				
				}
				
			}
			
		}
		else { 
			if(($log_type=='annex')||($log_type=='finance')){
				echo "&nbsp;";
			}
			else {
				echo $ticketSellerRow['id'];
				
			}
		}	
		?>
	</td>		
	
<?php	
//First Grid
	if($log_type=="initial"){
		if($type=="remittance"){
?>
		<td align=right><?php echo $svt_packs; ?></td>
		<td align=right><?php echo $svt_loose_in; ?>
		<td align=right><?php echo $svd_packs; ?></td>
		<td align=right><?php echo $svd_loose_in; ?>
		
		<td>&nbsp;</td>
		<td align=right><?php echo $svt_loose; ?></td>
		<td>&nbsp;</td>
		<td align=right><?php echo $svd_loose; ?></td>
		
<?php			
		
		}
		else if($type=="allocation"){
?>
		<td>&nbsp;</td>	
		<td>&nbsp;</td>	
		<td>&nbsp;</td>	
		<td>&nbsp;</td>	

		<td align=right><?php echo $svt_packs; ?></td>
		<td align=right><?php echo $svt_loose; ?></td>
		<td align=right><?php echo $svd_packs; ?></td>
		<td align=right><?php echo $svd_loose; ?></td>
		
		
	<?php		
		}
	
	}
	else if(($log_type=="annex")||($log_type=="finance")){
?>
		<td align=right><?php echo $svt_packs; ?></td>
		<td align=right><?php echo $svt_loose; ?></td>
		<td align=right><?php echo $svd_packs; ?></td>
		<td align=right><?php echo $svd_loose; ?></td>	
		
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		
	
<?php	
	}
	else if($log_type=="ticket"){
?>		
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>

		<td align=right><?php echo $svt_packs; ?></td>
		<td align=right><?php echo $svt_loose; ?></td>
		<td align=right><?php echo $svd_packs; ?></td>
		<td align=right><?php echo $svd_loose; ?></td>
<?php	
	}

?>	

<?php
//Total
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
	?>
		<td align=right><?php echo $svt_packs_1; ?></td>
		<td align=right><?php echo $svt_loose_1; ?></td>
		<td align=right><?php echo $svd_packs_1; ?></td>	
		<td align=right><?php echo $svd_loose_1; ?></td>
	<?php
	
	
?>
<td align=right ><?php echo $remarks; ?> <a href='#' class='delete'  onclick='deleteRecord("<?php echo $transaction_id; ?>","ticket")' >X</a></td>

</tr>	


<?php
}
 
}

$sqlDefective="select * from physically_defective where log_id='".$log_id."'";
$rsDefective=$db->query($sqlDefective);
$nmDefective=$rsDefective->num_rows;

if($nmDefective>0){
	$rowDefective=$rsDefective->fetch_assoc();
	$verify=$rowDefective['svt']+$rowDefective['svd'];
	if($verify>0){

		$date=date("h:i a",strtotime($rowDefective['date']));
	?>	
		<td><?php echo $date; ?></td>
		<td>Physically Defective</td>
		<td>&nbsp;</td>
		
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>

		<td>&nbsp;</td>
		<td align=right><?php echo $rowDefective['svt']; ?></td>
		<td>&nbsp;</td>
		<td align=right><?php echo $rowDefective['svd']; ?></td>	
	
	<?php
		$svd_loose_1-=$rowDefective['svd'];	
		$svt_loose_1-=$rowDefective['svt'];			
	?>
		<td align=right><?php echo $svt_packs_1; ?></td>
		<td align=right><?php echo $svt_loose_1; ?></td>
		<td align=right><?php echo $svd_packs_1; ?></td>	
		<td align=right><?php echo $svd_loose_1; ?></td>
		<td align=right>&nbsp; <a class='delete'  href='#' onclick='deleteRecord("<?php echo $rowDefective['id']; ?>","defective")' >X</a></td>
	<?php	
	}


}



?>
</table>
<?php
$verify=$svt_packs_1+$svt_loose_1+$svd_packs_1+$svd_loose_1;	

if($verify==0){
}
else { 
	$next_id=$_SESSION['next_log_id'];
	$sqlBalance="select * from beginning_balance_svt where log_id='".$next_id."'";
	$rsBalance=$db->query($sqlBalance);
	$nmBalance=$rsBalance->num_rows;
	$svt_total=$svt_packs_1+$svt_loose_1;
	$svd_total=$svd_packs_1+$svd_loose_1;
	
	
	if($nmBalance>0){
		$transferBalance="update beginning_balance_svt set svt_loose='".$svt_loose_1."',svd_loose='".$svd_loose_1."',svt='".$svt_packs_1."',svd='".$svd_packs_1."' where log_id='".$next_id."'";
		
	}
	else {
		$transferBalance="insert into beginning_balance_svt(log_id,svt,svd,svt_loose,svd_loose) values ('".$next_id."','".$svt_packs_1."','".$svd_packs_1."','".$svt_loose_1."','".$svd_loose_1."')";
	
	}

	$transferRS=$db->query($transferBalance);	
	
	$sqlBalance="select * from ending_balance_svt where log_id='".$log_id."'";
	$rsBalance=$db->query($sqlBalance);
	$nmBalance=$rsBalance->num_rows;
	$svt_total=$svt_packs_1+$svt_loose_1;
	$svd_total=$svd_packs_1+$svd_loose_1;
	
	
	if($nmBalance>0){
		$transferBalance="update ending_balance_svt set svt_loose='".$svt_loose_1."',svd_loose='".$svd_loose_1."',svt='".$svt_packs_1."',svd='".$svd_packs_1."' where log_id='".$log_id."'";
		
	}
	else {
		$transferBalance="insert into ending_balance_svt(log_id,svt,svd,svt_loose,svd_loose) values ('".$log_id."','".$svt_packs_1."','".$svd_packs_1."','".$svt_loose_1."','".$svd_loose_1."')";
	
	}

	$transferRS=$db->query($transferBalance);		
	
	
}
?>
<table width=100%>
<tr>
<td>
<a href='generateSVTLogbook.php' target='_blank'>Generate Printout</a>

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
<option value='ticket_order'>Ticket Order</option>
<option value='defective'>Physically Defective</option>
</select>
<input type=button value='Open Window' onclick='openTransaction()' />
<br>
<br>
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
<font class=red>Please close Control Slips not <br>closed from previous day</font>
<br>
<br>
<form name='control_slip_form' id='control_slip_form' target='control slip' action='control_slip.php' method='post' onsubmit="window.open('control_slip.php','control slip','width=750,height=800, scrollbars=yes')">
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
		<option value='<?php echo $row['id']; ?>'><?php echo strtoupper($row['last_name']).", ".$row['first_name'];  ?></option>
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