<?php
session_start();
?>
<?php

$log_id=$_SESSION['log_id'];

?>

<?php
ini_set("date.timezone","Asia/Kuala_Lumpur");
?>

<?php
$form_action="insert";
if(isset($_POST['ticket_seller'])){

	$year=$_POST['year'];
	$month=$_POST['month'];
	$day=$_POST['day'];
	
	$hour=$_POST['hour'];
	$minute=$_POST['minute'];
	$amorpm=$_POST['amorpm'];
	
	if($amorpm=='pm'){
		$hour+=(12*1);
		if($hour>=24){
			$hour=0;
		}
	}
	else {
		$hour=$hour;
		
	}
//	$type=$_POST['type'];
	$type="allocation";
	
	$sjt=$_POST['sjt'];
	$sjd=$_POST['sjd'];
	$svt=$_POST['svt'];
	$svd=$_POST['svd'];
	
	$sjt_loose=$_POST['sjt_loose'];
	$sjd_loose=$_POST['sjd_loose'];
	$svt_loose=$_POST['svt_loose'];
	$svd_loose=$_POST['svd_loose'];
	$station=$_POST['station'];

	
	$control_id=$_POST['ticket_seller'];
	
	$control_sql="select * from control_slip where id='".$control_id."' limit 1";
	$control_rs=$db->query($control_sql);
		
	$control_row=$control_rs->fetch_assoc();
		
	$ticket_seller=$control_row['ticket_seller'];
		
	$unit=$control_row['unit'];
	
	
	
	$cash_assistant=$_POST['cash_assistant'];
	$date=$year."-".$month."-".$day." ".$hour.":".$minute;
	$date_id=$year.$month.$day;
	$reference_id=$_POST['reference_id'];
	$ticket_type=$_POST['classification'];

	if($ticket_type=="ticket_seller"){
		$transaction_type="ticket";

	}
	else if($ticket_type=="catransfer"){
		$transaction_type="ticket_catransfer";
	}
	else if($ticket_type=="finance"){
		$transaction_type="finance";
		
	}
	else if($ticket_type=="annex"){

		$transaction_type="annex";
	}
	$unit_type=$_POST['unit_type'];
	$classification=$_POST['classification'];
	
	$db=new mysqli("localhost","root","","finance");
	
//	$sql="insert into transaction(date,log_id,log_type,transaction_type) values ('".$date."','".$log_id."','".$transaction_type."','".$type."')";
	
	if($_POST['form_action']=="insert"){
		
		$sql="insert into transaction(date,log_id,log_type,transaction_type) values ('".$date."','".$log_id."','".$transaction_type."','allocation')";

		$rs=$db->query($sql);

		$insert_id=$db->insert_id;
		
		$transaction_id=$date_id."_".$insert_id;
		$sql="update transaction set transaction_id='".$transaction_id."' where id='".$insert_id."'";
		$rs=$db->query($sql);
		
		$sql="insert into ticket_order(log_id,time,ticket_seller,cash_assistant,type,";
		$sql.="transaction_id,sjt,sjd,svt,svd,sjt_loose,sjd_loose,svt_loose,svd_loose,unit,classification,reference_id,station,control_id) values ";
		$sql.="('".$log_id."','".$date."','".$ticket_seller."','".$cash_assistant."','".$type."',";
		$sql.="'".$transaction_id."','".$sjt."','".$sjd."','".$svt."','".$svd."','".$sjt_loose."',";
		$sql.="'".$sjd_loose."','".$svt_loose."','".$svd_loose."','".$unit_type."','".$classification."','".$reference_id."','".$station."','".$control_id."')";

		$rs=$db->query($sql);
		$insert_id=$db->insert_id;
		$ticket_id=$insert_id;
		if($transaction_type=="ticket"){
			$sql="select * from control_slip where ticket_seller='".$ticket_seller."' and unit='".$unit_type."' and station='".$station."' and status='open' order by id desc";
			$rs=$db->query($sql);
			$nm=$rs->num_rows;		
			
			if($nm>0){
				$row=$rs->fetch_assoc();
				$control_id=$row['id'];
				
				$sql="select * from control_tracking where control_id='".$control_id."' and log_id='".$log_id."'";
				$rs=$db->query($sql);
				$nm=$rs->num_rows;

				if($nm==0){
					$update="insert into control_tracking(control_id,log_id) values ('".$control_id."','".$log_id."')";
					$updateRS=$db->query($update);
				}			
				
				
			}		
		}
		
		

	}
	else if($_POST['form_action']=="edit"){
		$form_action="edit";
		$sql="select * from transaction where id='".$_POST['trans_edit']."'";

		$rs=$db->query($sql);
		$row=$rs->fetch_assoc();
		$transaction_id=$row['transaction_id'];	
		$insert_id=$row['id'];
		
		$ticket_type=$_POST['classification'];		
		
		if($ticket_type=="ticket_seller"){
			$transaction_type="ticket";

		}
		else if($ticket_type=="catransfer"){
			$transaction_type="ticket_catransfer";
		}
		else if($ticket_type=="finance"){
			$transaction_type="finance";
			
		}
		else if($ticket_type=="annex"){

			$transaction_type="annex";
		}		
		$sql2="update transaction set log_type='".$transaction_type."' where id='".$_POST['trans_edit']."'";
		$rs2=$db->query($sql2);		
		
		
		$sql2="update ticket_order set ticket_seller='".$ticket_seller."',station='".$station."',sjt='".$sjt."',svt='".$svt."',sjd='".$sjd."',svd='".$svd."',sjt_loose='".$sjt_loose."',sjd_loose='".$sjd_loose."',svt_loose='".$svt_loose."',svd_loose='".$svd_loose."',control_id='".$control_id."' where transaction_id='".$row['transaction_id']."'";
		$rs2=$db->query($sql2);


		if($transaction_type=="ticket"){
			$sql="select * from control_slip where ticket_seller='".$ticket_seller."' and unit='".$unit_type."' and station='".$station."' and status='open' order by id desc";
			$rs=$db->query($sql);
			$nm=$rs->num_rows;		
			
			if($nm>0){
				$row=$rs->fetch_assoc();
				$control_id=$row['id'];
				
				$sql="select * from control_tracking where control_id='".$control_id."' and log_id='".$log_id."'";
				$rs=$db->query($sql);
				$nm=$rs->num_rows;

				if($nm==0){
					$update="insert into control_tracking(control_id,log_id) values ('".$control_id."','".$log_id."')";
					$updateRS=$db->query($update);
				}			
				
				
			}		
		}
		
	}
	
}


if(isset($_GET['tID'])){
	$db=new mysqli("localhost","root","","finance");
	$form_action="edit";
	$sql="select * from transaction where id='".$_GET['tID']."'";

	$rs=$db->query($sql);
	$row=$rs->fetch_assoc();

	$sql2="select * from ticket_order where transaction_id='".$row['transaction_id']."'";

	$tID=$_GET['tID'];	
	$rs2=$db->query($sql2);
	$row2=$rs2->fetch_assoc();

	$reference_id=$row2['reference_id'];
	
	$sjt=$row2['sjt'];
	$svd=$row2['svd'];
	$sjd=$row2['sjd'];

	$svt=$row2['svt'];

	$sjt_loose=$row2['sjt_loose'];
	$svd_loose=$row2['svd_loose'];
	$sjd_loose=$row2['sjd_loose'];

	$svt_loose=$row2['svt_loose'];
	
	$transactDate=$row2['time'];
	$classification=$row2['classification'];
	$ticket_seller=$row2['ticket_seller'];
	$unit=$row2['unit'];

	
}
else {
	$form_action="insert";

}





?>
<script language='javascript'>
function submitForm(){

	document.forms['ticket_order_form'].submit();
	window.opener.location.reload();
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

			
				optionsGrid+="<select name='ticket_seller' id='ticket_seller'>";				
				

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
<link rel="stylesheet" type="text/css" href="layout/control slip.css">
<form action='ticket_order.php<?php if(isset($_GET['tID'])){ echo "?tID=".$tID; } ?>' method='post' name='ticket_order_form' id='ticket_order_form' >
<table class='controlTable2'>
<tr class='header'>
<td>Reference Id (TOF No.)</td>
<td colspan=2><input type=text name='reference_id' value='<?php echo $reference_id; ?>' /><input type=hidden name='form_action' id='form_action' value='<?php echo $form_action; ?>' /><input type=hidden name='trans_edit' id='trans_edit' value='<?php echo $_GET['tID']; ?>'>
<?php	

//if(isset($_POST['ticket_seller'])){
if($allow=="true"){
//$transaction_id=$_SESSION['transact'];
?>		
<!--	//	<input type=button value='Open Discrepancy' onclick='window.open("discrepancy.php?type=<?php //echo $overageSwitch; ?>&overage=<?php ///echo $overage; ?>")&tID=<?php //echo $transaction_id; ?>")' />-->
<input type=button value='Open Discrepancy' onclick='window.open("discrepancy_ticket.php?tID=<?php echo $transaction_id; ?>&tsID=<?php echo $ticket_seller; ?>","discrepancy","height=350, width=400")' />
<?php
}
?>


</td>

</tr>
<tr class='grid'>
<td>Classification</td>
<td colspan=2>
<select name='classification' id='classification'>
	<option value='ticket_seller' <?php if($classification=='ticket_seller'){ echo "selected"; } ?> >To Ticket Seller</option>
	<option value='finance' <?php if($classification=='finance'){ echo "selected"; } ?>>From Finance Train</option>
	<option value='annex' <?php if($classification=='annex'){ echo "selected"; } ?>>From Annex</option>
	<option value='catransfer' <?php if($classification=='catransfer'){ echo "selected"; } ?>>Turnover to CA</option>
</select>

	<!--
	<br>
	<input type=button value='Add Ticket Seller' />
	-->
</td>
</tr>
<tr class='category'><td>Select Station</td>
<td colspan=2>

	<select name='station'>

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
	</td>
</tr>
<!--
<tr><td>Type</td>
<td colspan=2>
<select name='type'>
<option value='allocation'>Allocation</option>
<option value='remittance'>Remittance</option>
</select>
</td>
</tr>
-->
<tr class='grid'>
<td>Ticket Seller/Unit</td>
<td colspan=2>	


	<div id='cafill'>

	<?php

	$db=new mysqli("localhost","root","","finance");

	$sql="select control_slip.id as control_id,control_slip.*,ticket_seller.* from control_slip inner join ticket_seller on control_slip.ticket_seller=ticket_seller.id where control_slip.status='open' order by ticket_seller.last_name ";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;	
	
	?>
	<select name='ticket_seller'>
	<?php 
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
	?>
		<option value='<?php echo $row['control_id']; ?>' <?php if($control_post==$row['control_id']){ echo "selected"; } ?>><?php echo strtoupper($row['last_name']).", ".$row['first_name']."--".$row['unit']; ?></option>
	<?php
	}
	?>
	
	</select>

</td>
</tr>

<tr class='category'>
<td rowspan=2>Date and Time</td>
<td colspan=2>

<select name='month'>
<?php

$mm=date("m");
$yy=date("Y");
$dd=date("d");

$hh=date("h");

$min=date("i");
$aa=date("a");

if(isset($_GET['tID'])){
$mm=date("m",strtotime($transactDate));
$yy=date("Y",strtotime($transactDate));
$dd=date("d",strtotime($transactDate));

$hh=date("h",strtotime($transactDate));

$min=date("i",strtotime($transactDate));
$aa=date("a",strtotime($transactDate));

}

for($i=1;$i<13;$i++){
?>
	<option value='<?php echo $i; ?>' 
	<?php
	if($i==$mm){
		echo "selected";
	}
	?>
	>
	<?php
	echo date("F",strtotime(date("Y")."-".$i."-01"));
	?>
	</option>
<?php
}
?>
</select>
<select name='day'>
<?php
for($i=1;$i<=31;$i++){
?>
	<option value='<?php echo $i; ?>' 
	<?php
	if($i==$dd){
		echo "selected";
	}
	?>		
	>
	<?php
	
	echo $i;
	?>
	</option>
<?php
}
?>
</select>
<select name='year'>
<?php
$dateRecent=date("Y")*1+16;
for($i=1999;$i<=$dateRecent;$i++){
?>
	<option value='<?php echo $i; ?>' 
	<?php
	if($i==$yy){
		echo "selected";
	}
	?>		
	>
	<?php
	echo $i;
	?>
	</option>
<?php
}
?>
</select>
</td></tr>
<tr class='grid'><td colspan=2>
<select name='hour'>
<?php
for($i=1;$i<=12;$i++){
?>
	<option value='<?php echo $i; ?>' 
	<?php
	if($i*1==$hh*1){
		echo "selected";
	}
	?>		
	>
	<?php
	echo $i;
	?>
	</option>
<?php
}
?>
</select>
<select name='minute'>
<?php
for($i=0;$i<=59;$i++){
?>
	<option value='<?php echo $i; ?>' 
	<?php
	if($i*1==$min*1){
		echo "selected";
	}
	?>		
	>
	<?php
	if($i<10){
	echo "0".$i;
	}
	else {
	echo $i;
	}
	?>	
	</option>
<?php
}
?>
</select>
<select name='amorpm'>
<option value='am' <?php if($aa=="am"){ echo "selected"; } ?>>AM</option>
<option value='pm' <?php if($aa=="pm"){ echo "selected"; } ?>>PM</option>
</select>
</td>
</tr>
<tr class='subheader'>
<th>Ticket Type
</th>
<th>Packs/Pieces
</th>
<th>Loose
</th>
</tr>
<tr class='grid'><td>SJT (100 pieces)</td><td><input type='text' name='sjt' value='<?php echo $sjt; ?>' /></td>
<td><input type='text' name='sjt_loose'  value='<?php echo $sjt_loose; ?>' /></td>
</tr>
<tr class='category'><td>SJD (10 pieces)</td><td><input type='text' name='sjd' value='<?php echo $sjd; ?>' /></td><td><input type='text' name='sjd_loose' value='<?php echo $sjd_loose; ?>' /></td></tr>
<tr class='grid'><td>SVT (100 pieces)</td><td><input type='text' name='svt' value='<?php echo $svt; ?>' /></td><td><input type='text' name='svt_loose' value='<?php echo $svt_loose; ?>' /></td></tr>
<tr class='category'><td>SVD (10 pieces)</td><td><input type='text' name='svd' value='<?php echo $svd; ?>' /></td><td><input type='text' name='svd_loose' value='<?php echo $svd_loose; ?>' /></td></tr>
<tr ><td colspan=3>&nbsp;</td></tr>
<tr class='header'>
<td>Cash Assistant</td><td colspan=2>
<select name='cash_assistant'>
<?php
$db=new mysqli("localhost","root","","finance");
$sql="select * from login where status='active' order by lastName";
$rs=$db->query($sql);
$nm=$rs->num_rows;
for($i=0;$i<$nm;$i++){
$row=$rs->fetch_assoc();
?>
<option value='<?php echo $row['username']; ?>' <?php if($row['username']==$_SESSION['username']){ echo "selected"; } ?> >
<?php
echo strtoupper($row['lastName']).", ".$row['firstName'];
?>
</option>
<?php
}
?>
</select>

</td>
</tr>




<tr><td colspan=3 align=center><input type=button value='Submit' onclick='submitForm()'  /></td></tr>

</table>
</form>

<?php
if(isset($_POST['ticket_seller'])){
?>
<div align=center><input type=button value='Generate Printout' onclick='window.open("generateTicketOrder.php?trans=<?php echo $transaction_id; ?>","_blank")' /></div>
<?php
}
?>