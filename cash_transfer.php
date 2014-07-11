<?php
session_start();
?>
<?php
$log_id=$_SESSION['log_id'];
?>
<?php
ini_set("date.timezone","Asia/Kuala_Lumpur");
?>
<link rel="stylesheet" type="text/css" href="layout/control slip.css">
<?php
require("calculateInWords.php");
?>
<?php
if((isset($_POST['cash_total']))&&($_POST['cash_total']>0)){
	

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
	$type=$_POST['type'];
	
	$total=$_POST['cash_total'];
	$totalWords=$_POST['total_in_pesos'];
	$net=$_POST['for_deposit'];
	$station_entry=$_POST['station'];

	$control_id=$_POST['ticket_seller'];
		
	$control_sql="select * from control_slip where id='".$control_id."' limit 1";
	$control_rs=$db->query($control_sql);
		
	$control_row=$control_rs->fetch_assoc();
		
	$ticket_seller=$control_row['ticket_seller'];
		
	$unit=$control_row['unit'];

	
//	$unit=$_POST['unit'];
	$date=$year."-".$month."-".$day." ".$hour.":".$minute;
	$date_id=$year.$month.$day;

	$destination_ca="";
	if($type=="catransfer"){
		$destination_ca=$_POST['destination_cash_assistant'];
		
	}
	
	$db=new mysqli("localhost","root","","finance");
	$reference_id=$_POST['reference_id'];
	
	if($_POST['form_action']=="insert"){
		$sql="insert into transaction(date,log_id,log_type,transaction_type,reference_id) values ('".$date."','".$log_id."','cash','".$type."','".$reference_id."')";
		$rs=$db->query($sql);

		$insert_id=$db->insert_id;
		
		$transaction_id=$date_id."_".$insert_id;
		$_SESSION['transact']=$transaction_id;
		$sql="update transaction set transaction_id='".$transaction_id."' where id='".$insert_id."'";
		$rs=$db->query($sql);
		
		
		
		
		$revolving=$_POST['revolving_remittance'];
		
		$sql="insert into cash_transfer(log_id,time,ticket_seller,cash_assistant,type,";
		$sql.="transaction_id,total_in_words,total,net_revenue,station,reference_id,unit,destination_ca,control_id) values ";
		$sql.="('".$log_id."','".$date."','".$ticket_seller."','".$_POST['cash_assistant']."','".$type."',";
		$sql.="'".$transaction_id."','".$totalWords."','".$revolving."','".$net."','".$station_entry."','".$reference_id."','".$unit."','".$destination_ca."','".$control_id."')";

		$rs=$db->query($sql);
		$insert_id=$db->insert_id;
		$cash_transfer=$insert_id;
		
		if($type=="catransfer"){
			$sql="update cash_transfer set destination_ca='".$_POST['destination_cash_assistant']."' where id='".$cash_transfer."'";
			$rs=$db->query($sql);
		}
		
		if($type=="remittance"){
			$controlSQL="select * from control_slip where ticket_seller='".$ticket_seller."'  order by id desc";
			//and status='close'
			$controlRS=$db->query($controlSQL);
			$controlRow=$controlRS->fetch_assoc();
			
			$control_log=$controlRow['log_id'];
			
				
			$sql="select * from cash_remittance where log_id='".$control_log."' and ticket_seller='".$ticket_seller."'";	
			$rs=$db->query($sql);
			$nm=$rs->num_rows;
			if($nm>0){
				$row=$rs->fetch_assoc();
				$update="update cash_remittance set cash_remittance='".$_POST['cash_total']."',transaction_id='".$transaction_id."' where id='".$row['id']."'";
				$rs2=$db->query($update);
			}
			else {
				$update="update cash_remittance set cash_remittance='".$_POST['cash_total']."',transaction_id='".$transaction_id."' where ticket_seller='".$ticket_seller."' and cash_remittance=''";
				$rs2=$db->query($update);
				
			}
		}
		else if($type=="shortage"){
			$update="update control_cash set unpaid_shortage=unpaid_shortage-".(($total+$net_revenue)*1)." where control_id='".$control_id."'";
			$rs2=$db->query($update);
		}

		$denom[0]["id"]="1000";
		$denom[1]["id"]="500";
		$denom[2]["id"]="200";
		$denom[3]["id"]="100";
		$denom[4]["id"]="50";
		$denom[5]["id"]="20";
		$denom[6]["id"]="10";
		$denom[7]["id"]="5";
		$denom[8]["id"]="1";
		$denom[9]["id"]=".25";
		$denom[10]["id"]=".10";
		$denom[11]["id"]=".05";
		

		$denom[0]["value"]=$_POST['1000denom'];
		$denom[1]["value"]=$_POST['500denom'];
		$denom[2]["value"]=$_POST['200denom'];
		$denom[3]["value"]=$_POST['100denom'];
		$denom[4]["value"]=$_POST['50denom'];
		$denom[5]["value"]=$_POST['20denom'];
		$denom[6]["value"]=$_POST['10denom'];
		$denom[7]["value"]=$_POST['5denom'];
		$denom[8]["value"]=$_POST['1denom'];
		$denom[9]["value"]=$_POST['25cdenom'];
		$denom[10]["value"]=$_POST['10cdenom'];
		$denom[11]["value"]=$_POST['5cdenom'];

		
		for($i=0;$i<count($denom);$i++){
			if($denom[$i]["value"]>0){
				$sqlInsert="insert into denomination(cash_transfer_id,denomination,quantity) ";
				$sqlInsert.=" values ('".$insert_id."','".$denom[$i]['id']."','".$denom[$i]['value']."')";
				$sqlInsertRS=$db->query($sqlInsert);
			}
		}
	}
	else if($_POST['form_action']=="edit"){

		if(isset($_POST['trans_edit'])){
	
			$sql="select * from transaction where id='".$_POST['trans_edit']."'";
			$rs=$db->query($sql);
			$row=$rs->fetch_assoc();
			$type=$_POST['type'];
			
			$update="update transaction set transaction_type='".$type."',reference_id='".$reference_id."' where id='".$row['id']."'";
			$updateRS=$db->query($update);
		
			$insert_id=$row['id'];
			
			$transaction_id=$row['transaction_id'];
			$_SESSION['transact']=$transaction_id;
			
			$ticket_seller=$_POST['ticket_seller'];	
			$revolving=$_POST['revolving_remittance'];
			$reference_id=$_POST['reference_id'];	
			
	
			$sql="update cash_transfer set ticket_seller='".$ticket_seller."',total='".$revolving."',net_revenue='".$net."',total_in_words='".$totalWords."',station='".$station_entry."',type='".$type."',unit='".$unit."', destination_ca='".$destination_ca."',reference_id='".$reference_id."',control_id='".$control_id."' where transaction_id='".$transaction_id."'";
			$rs=$db->query($sql);
			//			echo $sql;
			//			$sql="insert into cash_transfer(log_id,time,ticket_seller,cash_assistant,type,";
//			$sql.="transaction_id,total_in_words,total,net_revenue,station,reference_id) values ";
//			$sql.="('".$log_id."','".$date."','".$ticket_seller."','".$_POST['cash_assistant']."','".$type."',";
//			$sql.="'".$transaction_id."','".$totalWords."','".$revolving."','".$net."','".$station_entry."','".$reference_id."')";

			if($type=="catransfer"){
				$sql="update cash_transfer set destination_ca='".$_POST['destination_cash_assistant']."',cash_assistant='".$_POST['cash_assistant']."' where transaction_id='".$transaction_id."'";
//				echo $sql;
				$rs=$db->query($sql);
			}
			
			$sql="select * from cash_transfer where transaction_id='".$transaction_id."'";
			$rs=$db->query($sql);
			$row=$rs->fetch_assoc();
			
			$insert_id=$row['id'];
//			$insert_id=$insert_id;
			$cash_transfer=$insert_id;
			
			if($type=="remittance"){
				$sql="select * from cash_remittance where ticket_seller='".$ticket_seller."' and log_id='".$log_id."'";	
				$rs=$db->query($sql);
				$nm=$rs->num_rows;
				if($nm>0){
					$row=$rs->fetch_assoc();
					$update="update cash_remittance set cash_remittance='".$_POST['cash_total']."',transaction_id='".$transaction_id."' where id='".$row['id']."'";
					$rs2=$db->query($update);
				}
				else {
					$update="update cash_remittance set cash_remittance='".$_POST['cash_total']."',transaction_id='".$transaction_id."' where ticket_seller='".$ticket_seller."' and cash_remittance=''";
					$rs2=$db->query($update);
					
				}
			}
			
		
		
			$denom[0]["id"]="1000";
			$denom[1]["id"]="500";
			$denom[2]["id"]="200";
			$denom[3]["id"]="100";
			$denom[4]["id"]="50";
			$denom[5]["id"]="20";
			$denom[6]["id"]="10";
			$denom[7]["id"]="5";
			$denom[8]["id"]="1";
			$denom[9]["id"]=".25";
			$denom[10]["id"]=".10";
			$denom[11]["id"]=".05";
			

			$denom[0]["value"]=$_POST['1000denom'];
			$denom[1]["value"]=$_POST['500denom'];
			$denom[2]["value"]=$_POST['200denom'];
			$denom[3]["value"]=$_POST['100denom'];
			$denom[4]["value"]=$_POST['50denom'];
			$denom[5]["value"]=$_POST['20denom'];
			$denom[6]["value"]=$_POST['10denom'];
			$denom[7]["value"]=$_POST['5denom'];
			$denom[8]["value"]=$_POST['1denom'];
			$denom[9]["value"]=$_POST['25cdenom'];
			$denom[10]["value"]=$_POST['10cdenom'];
			$denom[11]["value"]=$_POST['5cdenom'];

				
			
			$sqlDenom="delete from denomination where cash_transfer_id='".$insert_id."'";
			$rsDenom=$db->query($sqlDenom);
			for($i=0;$i<count($denom);$i++){
				if($denom[$i]["value"]>0){
					//$sqlInsert="update denomination set quantity='".$denom[$i]['value']."' where demonination='".$denom[$i]['id']."' and cash_transfer_id='".$insert_id."'";
					$sqlInsert="insert into denomination(cash_transfer_id,denomination,quantity) ";
					$sqlInsert.=" values ('".$insert_id."','".$denom[$i]['id']."','".$denom[$i]['value']."')";
					$sqlInsertRS=$db->query($sqlInsert);
				}
			}
	
		}
	}
	$transaction_code=$transaction_id;
	$cash_code=$cash_transfer;


	if(isset($_GET['type'])){
		$type=$_GET['type'];
		$classification="cash";
		$transaction_id=$transaction_code;
		$reported="ticket seller";
		$amount=$_GET['amount'];
		$reference_id=$transaction_id;
		$t_seller=$ticket_seller;

		$update="insert into discrepancy(reference_id,classification,reported,amount,type,transaction_id,log_id,ticket_seller) values ('".$reference_id."','".$classification."','".$reported."','".$amount."','".$type."','".$transaction_id."','".$log_id."','".$t_seller."')";
		$updateRS=$db->query($update);

		
		if($type=="overage"){
			$update="update control_cash set overage='".$amount."' where control_id='".$control_id."'";
			$updateRS=$db->query($update);
		
		}
		
		
		
	}
	
	if($_GET['shortage_payment']=="Y"){
		$depositpost=$total;
		$control_post=$control_id;
		$station_id=$station_entry;
		$cash_assist=$_POST['cash_assistant'];
	}
}

?>
<script language='javascript'>
function checkRemittance(transaction){
	if(transaction.value=="partial_remittance"){
		var control_id=document.getElementById('ticket_seller').value;
		getCashAdvance(control_id);
	}
}

function amountCalculate(quantity,denomination,textAmount,e,nextField){
	//document.getElementById(textAmount).value=((denomination*1)*qty)*1;
	var denom=denomination*1;
	var qty=quantity*1;
	
	var amount=denom*qty;
	amount=Math.round(amount*100)/100;
	
	document.getElementById(textAmount).value=amount;	

	calculateTotal();
	
	if(e.keyCode==13){
		document.getElementById(nextField).focus();
		if(nextField=="revolving_remittance"){
			window.scrollBy(0,100);
		}
	
	}	
}

function calculateTotal(){
	var total=0;
	for(i=1;i<13;i++){
		total+=(document.getElementById('amount'+i).value)*1;

	}
	document.getElementById('cash_total').value=Math.round(total*100)/100;

	calculateNumber(Math.round(total*100)/100,"total_in_pesos");	

	var rev=document.getElementById('revolving_remittance').value;
	if(rev>0){
		document.getElementById('for_deposit').value=Math.round((document.getElementById('cash_total').value*1-rev*1)*100)/100;
	}
	else {
		document.getElementById('revolving_remittance').value=document.getElementById('cash_total').value;	
		document.getElementById('for_deposit').value=0;
	}
}
function submitForm(control_id){

	var transaction=document.getElementById('type').value;
	if(transaction=="remittance"){
		document.forms['cash_form'].submit();
		window.opener.location.reload();
		//goCalculateDiscrepancy(document.getElementById('cash_total').value,control_id);
	}
	else {
		document.forms['cash_form'].submit();
		window.opener.location.reload();
	}
}

function goCalculateDiscrepancy(cash_total,control_id){
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
			var caTerms=caHTML.split(";");
			
			if(caTerms[0]=="none"){
				document.forms['cash_form'].submit();
				window.opener.location.reload();
			}
			else {
				if(caTerms[0]=="shortage"){
					var confirm=prompt("You have a Shortage amount of P"+caTerms[1]);
					
					if(confirm==true){
						document.getElementById('cash_form').action="cash_transfer.php?shortage_payment=Y&type=shortage&amount="+caTerms[1];
						document.forms['cash_form'].submit();
						window.opener.location.reload();
								
					}
				
				
				}
				else if(caTerms[0]=="overage"){
					var confirm=prompt("You have an Overage amount of P"+caTerms[1]);
				
					if(confirm==true){
						document.getElementById('cash_form').action="cash_transfer.php?type=overage&amount="+caTerms[1];
						document.forms['cash_form'].submit();
						window.opener.location.reload();
					}
				}
			}
		}
	} 
	
	xmlHttp.open("GET","processing.php?calculateDiscrepancy="+control_id+"&total="+cash_total,true);
	xmlHttp.send();	
}

function openTicketSeller(){
	window.open("ticket_seller.php","ticket_seller","height=200, width=300");

}

function calculateDistribution(type){
	if(type=='revenue'){
		var revolving=document.getElementById('cash_total').value*1-document.getElementById('for_deposit').value*1;
		document.getElementById('revolving_remittance').value=Math.round(revolving*100)/100;
	
	}
	else if(type=='revolving'){
		var deposit=document.getElementById('cash_total').value*1-document.getElementById('revolving_remittance').value*1;
		document.getElementById('for_deposit').value=Math.round(deposit*100)/100;
	
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

function getCashAdvance(control_id){
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
			document.getElementById('revolving_remittance').value;
		}
	} 
	
	xmlHttp.open("GET","processing.php?getCashAdvance="+control_id,true);
	xmlHttp.send();	
}	

function updateLogbook(){
	window.opener.location.reload();
	
	
}

</script>

<?php
if(isset($_GET['tID'])){
	$db=new mysqli("localhost","root","","finance");
	$form_action="edit";
	$sql="select * from transaction where id='".$_GET['tID']."'";
//	echo $sql;

	$rs=$db->query($sql);
	$row=$rs->fetch_assoc();
	
	$transactType=$row['transaction_type'];
	$transactionID=$row['transaction_id'];
	
	$sql2="select * from cash_transfer where transaction_id='".$row['transaction_id']."'";
	$rs2=$db->query($sql2);
	$row2=$rs2->fetch_assoc();
	
	$reference_id=$row2['reference_id'];
	$cash_transfer_id=$row2['id'];
	$totalpost=$row2['total']+$row2['net_revenue'];
	$revolvingpost=$row2['total'];
	$depositpost=$row2['net_revenue'];
	$totalWordpost=$row2['total_in_words'];
	$ticketsellerpost=$row2['ticket_seller'];
	$control_post=$row2['control_id'];
	$transactDate=$row2['time'];
	$station=$row2['station'];
	$destination_ca=$row2['destination_ca'];
	$unit=$row2['unit'];
	$cash_assist=$row2['cash_assistant'];	
	
	$denomSQL="select * from denomination where cash_transfer_id='".$cash_transfer_id."'";
	
	$denomRS=$db->query($denomSQL);
	$denomNM=$denomRS->num_rows;
	for($i=0;$i<$denomNM;$i++){
		$denomRow=$denomRS->fetch_assoc();
		$currency[$denomRow['denomination']]['value']=$denomRow['quantity'];
		$currency[$denomRow['denomination']]['id']=$denomRow['denomination'];
	}
	$_SESSION['transact']=$_GET['tID'];
	
	
}
else {
	$form_action="insert";
}
?>

<?php
if(isset($_GET['cID'])){
	$control_id=$_GET['cID'];
	$db=new mysqli("localhost","root","","finance");
	$sql="select * from control_slip where id='".$control_id."'";
	
	$rs=$db->query($sql);
	$row=$rs->fetch_assoc();
	$ticket_seller=$row['ticket_seller'];
	$unit=$row['unit'];
	$transactType="remittance";
	$ticketsellerpost=$ticket_seller;
	$station=$row['station'];
	
	$sql="select sum(total) as total from cash_transfer where log_id in (select log_id from control_tracking where control_id='".$control_id."') and ticket_seller='".$ticket_seller."' and unit='".$unit."' and type in ('allocation')";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	if($nm>0){
		$row=$rs->fetch_assoc();
		$cash_advance=$row['total'];
	}
	
	$revolvingpost=$cash_advance;
}	
?>

<form action='cash_transfer.php<?php if(isset($_GET['tID'])){ echo "?tID=".$_GET['tID']; } ?>' method='post' id='cash_form' name='cash_form' >
<table class='controlTable2'>
<tr class='header'><td>Reference Id (CTF No.)</td><td colspan=2><input type=text name='reference_id' id='reference_id' value='<?php echo $reference_id; ?>' /><input type=hidden name='form_action' id='form_action' value='<?php echo $form_action; ?>' /><input type=hidden name='trans_edit' id='trans_edit' value='<?php echo $_GET['tID']; ?>'>
<?php	
if((isset($_POST['cash_total']))||(isset($_GET['tID']))){
$transaction_id=$_SESSION['transact'];

if(isset($_GET['tID'])){
	$transaction_id=$transactionID;
	$ticket_seller=$ticketsellerpost;

}
?>		
<!--	//	<input type=button value='Open Discrepancy' onclick='window.open("discrepancy.php?type=<?php //echo $overageSwitch; ?>&overage=<?php ///echo $overage; ?>")&tID=<?php //echo $transaction_id; ?>")' />-->
<input type=button value='Open Discrepancy' onclick='window.open("discrepancy.php?tID=<?php echo $transaction_id; ?>&tsID=<?php echo $ticket_seller; ?>","discrepancy","height=300, width=300")' />
<?php
}
?>
</td>
</tr>
<!--<tr>
<td>Select Transaction Flow</td>
<td colspan=2>
<select>
<option>CA to Ticket Seller</option>
<option>Ticket Seller to CA</option>

</select>
</td></tr>-->
<tr class='grid'>
<td>Cash Assistant</td>
<td colspan=2>
<select name='cash_assistant'>
<?php
$db=new mysqli("localhost","root","","finance");
$sql="select * from login where status='active' order by lastName";
$rs=$db->query($sql);
$nm=$rs->num_rows;
for($i=0;$i<$nm;$i++){
$row=$rs->fetch_assoc();
?>
<option value='<?php echo $row['username']; ?>' 
<?php 
if(isset($cash_assist)){
	if($row['username']==$cash_assist){ 
	echo "selected"; 
		
	}	
}	
else {
	if($row['username']==$_SESSION['username']){ 
	echo "selected"; 
		
	} 	
}?> >
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
<tr class='category'>
<td>Ticket Seller/Unit</td>
<td colspan=2>
	<div id='cafill' name='cafill'>
	<?php
		
	/*
	$db=new mysqli("localhost","root","","finance");
	$sql="select * from ticket_seller order by last_name";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	*/
	
	$db=new mysqli("localhost","root","","finance");
	
	$sql="select control_slip.id as control_id,control_slip.*,ticket_seller.* from control_slip inner join ticket_seller on control_slip.ticket_seller=ticket_seller.id where control_slip.status='open' order by ticket_seller.last_name ";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;	
	
	?>
	<select name='ticket_seller' id='ticket_seller' onchange='checkRemittance(document.getElementById("type"));'>
	<?php 
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
	?>
		<option value='<?php echo $row['control_id']; ?>' <?php if($control_post==$row['control_id']){ echo "selected"; } ?>><?php echo strtoupper($row['last_name']).", ".$row['first_name']."--".$row['unit']; ?></option>
	<?php
	}
	?>
	
	</select>
	</div>
	<!--
	<input type=button value='Add Ticket Seller' onclick='openTicketSeller()' />
	-->
</td>
</tr>
<!--
<tr class='grid'>
<td>Search Ticket Seller</td>
<td colspan=2>
<input type=text name='searchTS' id='searchTS' onkeyup='searchTicketSeller(this.value)' />	
	
</td>
</tr>
-->
<tr class='grid'><td>Select Station</td>
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
	<option value='<?php echo $station_id; ?>' <?php if($station_id==$station){ echo "selected"; } ?>><?php echo $station_name; ?></option>
	<?php
	$extensionSQL="select * from extension inner join station on extension.extension=station.id where extension.station='".$logRow['station']."'";
	$extensionRS=$db->query($extensionSQL);
	$extensionNM=$extensionRS->num_rows;
	if($extensionNM>0){
		$extensionRow=$extensionRS->fetch_assoc();
		$extensionID=$extensionRow['extension'];
		$extensionName=$extensionRow['station_name'];
	?>
	<option value='<?php echo $extensionID; ?>' <?php if($extensionID==$station){ echo "selected"; } ?>><?php echo $extensionName; ?></option>
	<?php
	}
	?>
	<option value='annex'>Annex</option>

	</select>
	</td>
</tr>

<tr class='category'><td>Transaction</td>
<td colspan=2>
<select name='type' id='type' onchange='checkRemittance(this)'>
<option <?php if($transactType=="allocation"){ echo "selected"; } ?> value='allocation'>Allocation</option>

<?php 
if(isset($_GET['cID'])){
?>
<option <?php if($transactType=="remittance"){ echo "selected"; } ?> value='remittance'>Final Remittance</option>
<?php
}
else {
?>
<option <?php if($transactType=="partial_remittance"){ echo "selected"; } ?> value='remittance'>Partial Remittance</option>
<?php
}



if($_GET['shortage_payment']=="Y"){
	$transactType="shortage";
}
?>
<option <?php if($transactType=="shortage"){ echo "selected"; } ?> value='shortage'>Shortage Payment</option>
<option <?php if($transactType=="catransfer"){ echo "selected"; } ?> value='catransfer'>Turnover to CA</option>

</select>
</td>
</tr>
<tr class='grid'>
<td>To CA (Turnover only)</td>
<td colspan=2>
<select name='destination_cash_assistant'>
<?php
$db=new mysqli("localhost","root","","finance");
$sql="select * from login where status='active'  order by lastName";
$rs=$db->query($sql);
$nm=$rs->num_rows;
for($i=0;$i<$nm;$i++){
$row=$rs->fetch_assoc();
?>
<option value='<?php echo $row['username']; ?>' <?php if($row['username']==$destination_ca){ echo "selected"; } ?> >
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
<tr class='header'>
<td>Date and Time</td>
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

<th>Denomination</th>
<th>Quantity</th>
<th>Amount</th>
</tr>
<tr class='grid'>
	<td align=center>1000</td><td><input type='text' style='text-align:right'  id='1000denom' name='1000denom' value='<?php echo $currency["1000"]['value']; ?>' onkeyup="amountCalculate(this.value,'1000','amount1',event,'500denom')" /></td><td><input style='text-align:right' type='text' name='amount1' id='amount1' value='<?php echo $currency['1000']['id']*$currency["1000"]['value']; ?>'  /></td>
</tr>
<tr class='category'>
	<td align=center>500</td><td><input type='text' style='text-align:right'  id='500denom' name='500denom' value='<?php echo $currency["500"]['value']; ?>' onkeyup="amountCalculate(this.value,'500','amount2',event,'200denom')" /></td><td><input style='text-align:right'  type='text' name='amount2' id='amount2'  value='<?php echo $currency['500']['id']*$currency["500"]['value']; ?>'  /></td>
</tr>
<tr class='grid'>
	<td align=center>200</td><td><input type='text' style='text-align:right'  id='200denom' name='200denom' value='<?php echo $currency["200"]['value']; ?>' onkeyup="amountCalculate(this.value,'200','amount3',event,'100denom')" /></td><td><input style='text-align:right'  type='text' name='amount3' id='amount3'  value='<?php echo $currency['200']['id']*$currency["200"]['value']; ?>' /></td>
</tr>
<tr class='category'>
	<td align=center>100</td><td><input type='text' style='text-align:right'  id='100denom' name='100denom' value='<?php echo $currency["100"]['value']; ?>' onkeyup="amountCalculate(this.value,'100','amount4',event,'50denom')" /></td><td><input style='text-align:right'  type='text' name='amount4' id='amount4'  value='<?php echo $currency['100']['id']*$currency["100"]['value']; ?>' /></td>
</tr>
<tr class='grid'>
	<td align=center>50</td><td><input type='text' style='text-align:right'  id='50denom' name='50denom' value='<?php echo $currency["50"]['value']; ?>' onkeyup="amountCalculate(this.value,'50','amount5',event,'20denom')" /></td><td><input style='text-align:right'  type='text' name='amount5' id='amount5'  value='<?php echo $currency['50']['id']*$currency["50"]['value']; ?>' /></td>
</tr>
<tr class='category'>
	<td align=center>20</td><td><input type='text' style='text-align:right'  id='20denom' name='20denom' value='<?php echo $currency["20"]['value']; ?>' onkeyup="amountCalculate(this.value,'20','amount6',event,'10denom')" /></td><td><input style='text-align:right'  type='text' name='amount6' id='amount6'  value='<?php echo $currency['20']['id']*$currency["20"]['value']; ?>' /></td>
</tr>
<tr class='grid'>
	<td align=center>10</td><td><input type='text' style='text-align:right'  id='10denom' name='10denom' value='<?php echo $currency["10"]['value']; ?>' onkeyup="amountCalculate(this.value,'10','amount7',event,'5denom')" /></td><td><input style='text-align:right'  type='text' name='amount7' id='amount7'  value='<?php echo $currency['10']['id']*$currency["10"]['value']; ?>' /></td>
</tr>
<tr class='category'>
	<td align=center>5</td><td><input type='text' style='text-align:right'  id='5denom' name='5denom' value='<?php echo $currency["5"]['value']; ?>' onkeyup="amountCalculate(this.value,'5','amount8',event,'1denom')" /></td><td><input style='text-align:right'  type='text' name='amount8' id='amount8'  value='<?php echo $currency['5']['id']*$currency["5"]['value']; ?>' /></td>
</tr>
<tr class='grid'>
	<td align=center>1</td><td><input type='text' style='text-align:right'  id='1denom' name='1denom' value='<?php echo $currency["1"]['value']; ?>' onkeyup="amountCalculate(this.value,'1','amount9',event,'25cdenom')" /></td><td><input style='text-align:right'  type='text' name='amount9' id='amount9'  value='<?php echo $currency['1']['id']*$currency["1"]['value']; ?>' /></td>
</tr>
<tr class='category'>
	<td align=center>.25</td><td><input type='text' style='text-align:right'  id='25cdenom' name='25cdenom' value='<?php echo $currency[".25"]['value']; ?>' onkeyup="amountCalculate(this.value,'.25','amount10',event,'10cdenom')" /></td><td><input style='text-align:right'  type='text' name='amount10' id='amount10'  value='<?php echo $currency['.25']['id']*$currency[".25"]['value']; ?>' /></td>
</tr>
<tr class='grid'>
	<td align=center>.10</td><td><input type='text' style='text-align:right'  id='10cdenom' name='10cdenom' value='<?php echo $currency[".10"]['value']; ?>' onkeyup="amountCalculate(this.value,'.10','amount11',event,'5cdenom')" /></td><td><input style='text-align:right'  type='text' name='amount11' id='amount11'  value='<?php echo $currency['.10']['id']*$currency[".10"]['value']; ?>' /></td>
</tr>
<tr class='category'>
	<td align=center>.05</td><td><input type='text' style='text-align:right'  id='5cdenom' name='5cdenom' value='<?php echo $currency[".05"]['value']; ?>' onkeyup="amountCalculate(this.value,'.05','amount12',event,'revolving_remittance')" /></td><td><input style='text-align:right'  type='text' name='amount12' id='amount12'  value='<?php echo $currency['.05']['id']*$currency[".05"]['value']; ?>' /></td>
</tr>
<tr class='header'>
	<td>&nbsp;</td>
	<th>Total</th>
	<td><input style='text-align:right'  name='cash_total' id='cash_total' type=text value='<?php echo $totalpost; ?>' />
	</td>
</tr>	

<tr >
<td  class='subheader'>Total In Words</td><td class='category' colspan=2><textarea cols=40 name='total_in_pesos' id='total_in_pesos'  ><?php echo $totalWordpost; ?></textarea></td>

</tr>
<tr><td  class='subheader'>Revolving Fund (Remittance)</td><td class='grid' colspan=2><input style='text-align:right'  type=text id='revolving_remittance' name='revolving_remittance' value='<?php echo $revolvingpost; ?>' onkeyup='calculateDistribution("revolving")' onblur='calculateDistribution("revolving")' /></td></tr>
<tr><td class='subheader'>For Deposit/Net Revenue</td><td class='category' colspan=2><input style='text-align:right'  type=text name='for_deposit' id='for_deposit' value='<?php echo $depositpost; ?>' onkeyup='calculateDistribution("revenue")' onblur='calculateDistribution("revenue")' /></td></tr>



<tr><td align=center colspan=3><input type=button value='Submit' onclick='submitForm("<?php echo $_GET['cID']; ?>")' /> 
<?php 
if(isset($_POST['cash_total'])){ 
?>
<input type=button value='Generate Printout' onclick='window.open("generateCashTransfer.php?trans=<?php echo $transaction_code; ?>&cash=<?php echo $cash_code; ?>")' />
<?php
}
?>
<?php
	$type=$_POST['type'];
	$ticket_seller=$_POST['ticket_seller'];		
	
	if($type=="remittance"){
		$sql="select * from cash_remittance where log_id='".$log_id."' and ticket_seller='".$ticket_seller."'";	
		$rs=$db->query($sql);
		
		$row=$rs->fetch_assoc();
		
		$control_remittance=$row['control_remittance'];
		$cash_remittance=$row['cash_remittance'];
		$transaction_id=$row['transaction_id'];	

			
		if($cash_remittance==$control_remittance){
			$overage="0";
		}
		else {
			if($cash_remittance>$control_remittance){
				$overage=$cash_remittance*1-$control_remittance*1;
				$overageSwitch="overage";
			}
			else if($control_remittance>$cash_remittance){
				$overage=$control_remittance*1-$cash_remittance*1;
				$overageSwitch="short";
			}
		?>
		
		
		<?php
		}			
		


		
	}
				
?>	

</td></tr>
</table>

</form>
